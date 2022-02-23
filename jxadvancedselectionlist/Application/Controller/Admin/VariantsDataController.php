<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin;

use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Registry;

/**
 *
 */
class VariantsDataController extends AdminDetailsController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'jxadvancedselectionlist_variantsdata.tpl';

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
            /** @var Article $oProduct */
            $oProduct = oxNew(Article::class);
            $oProduct->load($sOxId);

            if ($oProduct->isVariant()) {
                return $this->_sThisTemplate;
            }

            $aVariantsData = $this->buildFinalVariantsDataArray($oProduct);

            $this->_aViewData["aVariantsData"] = $aVariantsData;
            $this->_aViewData["sImgDir"] = SelectionListOption::ICONSDIR;
        }

        return $this->_sThisTemplate;
    }

    /**
     * @param Article $oProduct
     * @return mixed
     */
    protected function buildFinalVariantsDataArray(Article $oProduct)
    {
        $aVariantsData = $oProduct->getVariantsAdditionalData();

        $variants = $oProduct->getVariants();

        foreach ($variants as $variant) {
            $varSel = $variant->oxarticles__oxvarselect->value;
            $varSel = explode('|', $varSel);
            $varSel = array_filter($varSel);

            foreach ($varSel as $option) {
                $option = trim($option);
                $optionKey = md5($option);
                $aVariantsData['selectionOptions'][$optionKey]['title'] = $option;
            }
        }

        return $aVariantsData;
    }

    /**
     * Save options data
     */
    public function save()
    {
        try {
            $sOxId = $this->getEditObjectId();
            $aVariantsData = Registry::getConfig()->getRequestParameter("variantsdata");

            if (empty($aVariantsData)) {
                throw new \Exception('Empty request params!');
            }


            /** @var Article $oProduct */
            $oProduct = oxNew(Article::class);
            $oProduct->load($sOxId);

            if (!$oProduct) {
                throw new \Exception('Product record not found!');
            }

            foreach ($aVariantsData['selectionOptions'] as $optionKey => $optionData) {
                if ($_FILES['variantsdataoptionicons_' . $optionKey]['tmp_name']) {
                    $sImageName = Registry::getUtilsFile()->processFile('variantsdataoptionicons_' . $optionKey, SelectionListOption::ICONSDIR);
                    $aVariantsData['selectionOptions'][$optionKey]['icon'] = $sImageName;
                }
            }

            $oProduct->setVariantsAdditionalData($aVariantsData);
            $oProduct->save();
        } catch (\Exception $e) {
            $this->_aViewData["errorMessage"] = Registry::getLang()->translateString($e->getMessage(), $this->_iEditLang, true);
        }
    }
}
