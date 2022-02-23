<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin;

use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListImageSetsItem;
use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListImageSetsList;
use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOptionList;
use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectListList;
use OxidEsales\Codeception\Page\Lists\ProductList;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class SelectListOptionsImageSetsController
 * @package JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin
 */
class SelectListOptionsImageSetsController extends SelectListOptionsController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'jxadvancedselectionlist_optionsimagesets.tpl';

    /**
     * Load selected option data. Create appropriate record in DB if does not exist
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function selectOption()
    {
        parent::selectOption();

        $aAllSelectionListOptions = [];
        $aArticleListItems = [];

        /** @var  ArticleList $oArticleList */
        $oArticleList = oxNew(ArticleList::class);
        $sArticleTable = $oArticleList->getBaseObject()->getViewName();

        $sSelect = "select oxid, oxtitle from $sArticleTable ";
        $sSelect .= "where " . $oArticleList->getBaseObject()->getSqlActiveSnippet() . " and $sArticleTable.oxissearch = 1 and oxid > '' ";
        $sSelect .= "and $sArticleTable.oxparentid = '' ";
        $sSelect .= "order by $sArticleTable.oxtitle asc";

        $oArticleList->selectString($sSelect);

        foreach ($oArticleList as $oArticle) {
            $aArticleListItems[] = [
                'title' => $oArticle->oxarticles__oxtitle->value,
                'oxid' => $oArticle->getId()
            ];
        }

        $sSelectedArticleOxid = Registry::getConfig()->getRequestParameter("productid");

        /** @var  SelectListList $oSelectListList */
        $oSelectListList = oxNew(SelectListList::class);
        $oSelectListItems = $oSelectListList->getList();

        foreach ($oSelectListItems as $oSelectListItem) {
            $aAllSelectionListOptions[$oSelectListItem->getId()]['title'] = $oSelectListItem->oxselectlist__oxident->value ?: $oSelectListItem->getLabel();

            $aFieldArray = Registry::getUtils()->assignValuesFromText($oSelectListItem->oxselectlist__oxvaldesc->getRawValue());

            foreach ($aFieldArray as $sKey => $oField) {
                $sOptionId = implode('_', [$oField->name, $oField->price, $oField->priceUnit]);
                $aAllSelectionListOptions[$oSelectListItem->getId()]['options'][$sOptionId] = $oField;
            }
        }

        /** @var  SelectListList $oSelectListList */
        $oSelectionListImageSetsList = oxNew(SelectionListImageSetsList::class);
        $sOptionVal = $this->_aViewData["oSelectedOption"]->getSelectionListOxid() . '|||' . $this->_aViewData["oSelectedOption"]->getOptionId();
        $oSelectionListImageSetsItems = $oSelectionListImageSetsList->getAllBySelectionOptionAndArticleId($sOptionVal, $sSelectedArticleOxid);

        $this->_aViewData["aAllSelectionListOptions"] = $aAllSelectionListOptions;
        $this->_aViewData["oSelectionListImageSetsItems"] = $oSelectionListImageSetsItems;
        $this->_aViewData["aArticleListItems"] = $aArticleListItems;
        $this->_aViewData["sSelectedArticleOxid"] = $sSelectedArticleOxid;
        $this->_aViewData["sImgDir"] = SelectionListImageSetsItem::IMAGESDIR;
    }

    /**
     * Save option
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function addNewItem()
    {
        $aOptionsSet = [];
        $sProductId = Registry::getConfig()->getRequestParameter("productid");
        $sDescription = Registry::getConfig()->getRequestParameter("description");

        try {
            for ($i = 1; $i <= SelectionListImageSetsList::MAX_ALLOWED_OPTIONS_IN_SET; $i++) {
                $sOptionVal = Registry::getConfig()->getRequestParameter("selectionlistoption_" . $i);
                $aOptionsSet[] = $sOptionVal ?: '';
            }

            $aOptionsSet = array_filter($aOptionsSet);

            usort($aOptionsSet, function ($a, $b) {
                $a = implode('|||', array_reverse(explode('|||', $a)));
                $b = implode('|||', array_reverse(explode('|||', $b)));

                return strcasecmp($a, $b);
            });

            /** @var SelectionListImageSetsItem $oSelectionListImageSetsItem */
            $oSelectionListImageSetsItem = oxNew(SelectionListImageSetsItem::class);
            $oSelectionListImageSetsItem->loadByOptionsSet($aOptionsSet, $sProductId);

            if ($oSelectionListImageSetsItem->isLoaded()) {
                throw new \Exception('Image for such options set already exists! Please, edit it below in the list.');
            }

            if ($_FILES['image']['tmp_name']) {
                $sImageName = Registry::getUtilsFile()->processFile('image', SelectionListImageSetsItem::IMAGESDIR);
                $oSelectionListImageSetsItem->setImage($sImageName);
            } else {
                throw new \Exception('Image field is required!');
            }

            for ($i = 1; $i <= SelectionListImageSetsList::MAX_ALLOWED_OPTIONS_IN_SET; $i++) {
                $oSelectionListImageSetsItem->setSelectionListOption(array_shift($aOptionsSet) ?: '', $i);
            }

            $oSelectionListImageSetsItem->setProductId($sProductId);
            $oSelectionListImageSetsItem->setDescription($sDescription);

            $oSelectionListImageSetsItem->save();
        } catch (\Exception $e) {
            $this->_aViewData["errorMessage"] = Registry::getLang()->translateString($e->getMessage(), $this->_iEditLang, true);
        }

        return $this->selectOption();
    }

    /**
     * Save option
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function save()
    {
        try {
            $sImageSetItemOxid = Registry::getConfig()->getRequestParameter("jxadvancedselectionlistimagesets__oxid");

            if (!$sImageSetItemOxid) {
                throw new \Exception('Empty request params!');
            }

            /** @var SelectionListOption $oSelectionListImageSetsItem */
            $oSelectionListImageSetsItem = oxNew(SelectionListImageSetsItem::class);
            $oSelectionListImageSetsItem->load($sImageSetItemOxid);

            if (!$oSelectionListImageSetsItem) {
                throw new \Exception('Image set record not found!');
            }

            if (Registry::getConfig()->getRequestParameter("delete")) {
                $oSelectionListImageSetsItem->delete();
                return $this->selectOption();
            }

            $sImage = Registry::getConfig()->getRequestParameter("jxadvancedselectionlistimagesets__image");
            $oSelectionListImageSetsItem->setImage($sImage);

            if ($_FILES['image']['tmp_name']) {
                $sImageName = Registry::getUtilsFile()->processFile('image', SelectionListOption::IMAGESDIR);
                $oSelectionListImageSetsItem->setImage($sImageName);
            }

            $oSelectionListImageSetsItem->setProductId(Registry::getConfig()->getRequestParameter("productid"));
            $oSelectionListImageSetsItem->setDescription(Registry::getConfig()->getRequestParameter("description"));

            $oSelectionListImageSetsItem->save();
        } catch (\Exception $e) {
            $this->_aViewData["errorMessage"] = Registry::getLang()->translateString($e->getMessage(), $this->_iEditLang, true);
        }

        return $this->selectOption();
    }
}
