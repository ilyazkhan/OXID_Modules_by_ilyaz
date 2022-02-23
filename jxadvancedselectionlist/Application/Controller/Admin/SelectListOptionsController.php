<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin;

use JidexStudio\JxAdvancedSelectionList\Application\Model\DynamicImageGenerator;
use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class SelectListOptionsController
 * @package JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin
 */
class SelectListOptionsController extends AdminDetailsController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'jxadvancedselectionlist_options.tpl';

    /**
     * Loads options information
     *
     * @return string
     */
    public function render()
    {
        parent::render();

        $sOxId = $this->getEditObjectId();
        $this->_aViewData["aOptions"] = [];

        if (isset($sOxId) && $sOxId != "-1") {
            // load object
            $oSelectList = oxNew(\OxidEsales\Eshop\Application\Model\SelectList::class);
            $oSelectList->loadInLang($this->_iEditLang, $sOxId);

            $aFieldArray = Registry::getUtils()->assignValuesFromText($oSelectList->oxselectlist__oxvaldesc->getRawValue());

            foreach ($aFieldArray as $sKey => $oField) {
                $sOptionId = implode('_', [$oField->name, $oField->price, $oField->priceUnit]);
                $this->_aViewData["aOptions"][$sOptionId] = $oField;
            }
        }

        return $this->_sThisTemplate;
    }

    /**
     * Load selected option data. Create appropriate record in DB if does not exist
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function selectOption()
    {
        $sOxId = $this->getEditObjectId();

        $sOptionId = Registry::getConfig()->getRequestParameter("option");

        if (isset($sOxId) && $sOxId != "-1") {
            // load object
            $oSelectList = oxNew(\OxidEsales\Eshop\Application\Model\SelectList::class);
            $oSelectList->loadInLang($this->_iEditLang, $sOxId);

            /** @var SelectionListOption $oOption */
            $oOption = oxNew(SelectionListOption::class);

            if (!$oOption->loadBySelectionListAndOptionId($sOxId, $sOptionId, $this->_iEditLang)) {
                $oOption->setSelectionListOxid($this->getEditObjectId());
                $oOption->setOptionId($sOptionId);
                $oOption->setLanguageId($this->_iEditLang);
                $oOption->save();
            }

            $this->_aViewData["oSelectedOption"] = $oOption;

            if ($oOption->getIcon()) {
                $this->_aViewData["icon"]['id'] = $oOption->getIcon();
                $this->_aViewData["icon"]['path'] = SelectionListOption::ICONSDIR . $oOption->getIcon();
            }

            if ($oOption->getImage()) {
                $this->_aViewData["image"]['id'] = $oOption->getImage();
                $this->_aViewData["image"]['path'] = SelectionListOption::IMAGESDIR . $oOption->getImage();
            }

            $this->_aViewData["editor"] = $this->generateTextEditor(
                "100%",
                300,
                $oOption,
                "jxadvancedselectionlistoptions__description",
                ""
            );
        }
    }

    /**
     * Save option
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function save()
    {
        parent::save();

        try {
            $sOptionOxid = Registry::getConfig()->getRequestParameter("jxadvancedselectionlistoptions__oxid");

            if (!$sOptionOxid) {
                throw new \Exception('Empty request params!');
            }

            /** @var SelectionListOption $oOption */
            $oOption = oxNew(SelectionListOption::class);
            $oOption->load($sOptionOxid);

            if (!$oOption) {
                throw new \Exception('Option not found!');
            }

            if ($sDescription = Registry::getConfig()->getRequestParameter("jxadvancedselectionlistoptions__description")) {
                $oOption->setDescription($sDescription);
            }

            $sIcon = Registry::getConfig()->getRequestParameter("jxadvancedselectionlistoptions__icon");
            $sImage = Registry::getConfig()->getRequestParameter("jxadvancedselectionlistoptions__image");
            $oOption->setIcon($sIcon);
            $oOption->setImage($sImage);

            if ($_FILES['icon']['tmp_name']) {
                $generator = new DynamicImageGenerator();
                $sIconName = Registry::getUtilsFile()->processFile('icon', SelectionListOption::ICONSDIR);
                $sIconNameGenerated = $generator->generateImageWithSizes(
                    SelectionListOption::ICONSDIR . $sIconName,
                    SelectionListOption::ICONSDIR . 'generated/' . $sIconName,
                    600,
                    600,
                    100
                );

                $oOption->setIcon($sIconNameGenerated ? 'generated/' . $sIconName : $sIconName);
            }

            if ($_FILES['image']['tmp_name']) {
                $sImageName = Registry::getUtilsFile()->processFile('image', SelectionListOption::IMAGESDIR);
                $oOption->setImage($sImageName);
            }

            $oOption->save();
        } catch (\Exception $e) {
            $this->_aViewData["errorMessage"] = Registry::getLang()->translateString($e->getMessage(), $this->_iEditLang, true);
        }

        return $this->selectOption();
    }
}
