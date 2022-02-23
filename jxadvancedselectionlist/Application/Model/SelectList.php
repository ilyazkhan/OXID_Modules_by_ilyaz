<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class SelectList
 * @package JidexStudio\JxAdvancedSelectionList\Application\Model
 */
class SelectList extends SelectList_parent
{
    /**
     * Returns array of oxSelection's
     *
     * @return array
     */
    public function getSelections()
    {
        parent::getSelections();
        $aAllOptions = [];

        if ($this->_aList) {
            $aOriginalList = $this->assignValuesFromTextWithoutFEProcessing();
            $oCur = Registry::getConfig()->getActShopCurrencyObject();
            $sDecimalsSeparator = $oCur->dec ?: ',';
            $sThousandSeparator = $oCur->thousand ?: '.';

            /** @var  SelectionListOptionList $oSelectionListOptionList */
            $oSelectionListOptionList = oxNew(SelectionListOptionList::class);
            $oSelectionListOptions = $oSelectionListOptionList->getAllBySelectionList($this->oxselectlist__oxid->value, $this->getLanguage());

            foreach ($oSelectionListOptions as $oOption) {
                $aAllOptions[$oOption->getOptionId()] = $oOption;
            }

            foreach ($this->_aList as $sKey => $oField) {
                $oOriginalField = $aOriginalList[$sKey] ?: $oField;
                $optionId = implode('_', [$oOriginalField->name, $oOriginalField->price, $oOriginalField->priceUnit]);

                $this->_aList[$sKey]->rawName = $oOriginalField->name;
                $this->_aList[$sKey]->optionId = $optionId;
                $this->_aList[$sKey]->description = '';
                $this->_aList[$sKey]->icon = '';
                $this->_aList[$sKey]->image = '';

                // retrieve option final price value after all FE calculations (remove everything except price and reformat price)
                $fePrice = trim(str_replace([$oOriginalField->name, $oCur->sign, '+'], "", $this->_aList[$sKey]->getName()));
                $this->_aList[$sKey]->fePrice = str_replace([$sThousandSeparator, $sDecimalsSeparator], ['', '.'], $fePrice);

                if ($aAllOptions[$optionId]) {
                    $this->_aList[$sKey]->description = $aAllOptions[$optionId]->getDescription() ?: '';
                    $this->_aList[$sKey]->icon = $aAllOptions[$optionId]->getIcon() ? SelectionListOption::ICONSDIR . $aAllOptions[$optionId]->getIcon() : '';
                    $this->_aList[$sKey]->image = $aAllOptions[$optionId]->getImage() ? SelectionListOption::IMAGESDIR . $aAllOptions[$optionId]->getImage() : '';
                }

                // Image sets data
                $sFullOptionId = $this->oxselectlist__oxid->value . '|||' . $optionId;
                $this->_aList[$sKey]->fullOptionId = $sFullOptionId;
            }
        }

        return $this->_aList;
    }

    /**
     * Returns array of images related to some specific option
     *
     * @return SelectionListImageSetsList
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getImageSets($sArticleId)
    {
        $aAllImages = [];

        /** @var  SelectionListImageSetsList $oSelectionListImageSetsList */
        $oSelectionListImageSetsList = oxNew(SelectionListImageSetsList::class);
        $oSelectionListImageSetsItems = $oSelectionListImageSetsList->getAllBySelectionListAndArticleId($this->oxselectlist__oxid->value, $sArticleId);

        foreach ($oSelectionListImageSetsItems as $oSelectionListImageSetsItem) {
            $aImgData = [];

            for ($i = 1; $i <= SelectionListImageSetsList::MAX_ALLOWED_OPTIONS_IN_SET; $i++) {
                $sFieldName = "jxadvancedselectionlistimagesets__selectionlistoption_{$i}";
                $sFieldVal = trim($oSelectionListImageSetsItem->{$sFieldName}->value);
                $aImgData['options'][$i] = $sFieldVal;
            }

            $aImgData['img'] = SelectionListImageSetsItem::IMAGESDIR . $oSelectionListImageSetsItem->getImage();
            $aImgData['description'] = $oSelectionListImageSetsItem->getDescription();
            $aImgData['isGlobal'] = $oSelectionListImageSetsItem->getProductId() ? false : true;

            $aAllImages[] = $aImgData;
        }

        return $aAllImages;
    }

    /**
     * Returns array of hidden options relations
     *
     * @return SelectionListImageSetsList
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getAllHiddenOptionsRelations()
    {
        $oConfig = Registry::getConfig();
        $bUseBilateralRelations = $oConfig->getConfigParam('jxadvancedselectionlist_main_hidden_options_bilateral_relations');

        $aFinalArray = [];
        $sSelect = "SELECT * FROM jxadvancedselectionlisthiddenoptionsmm";
        $aResult = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->getAll($sSelect);

        foreach($aResult as $row){
            $sColumn1Val = $row['SELECTIONLISTOPTION_1'];
            $sColumn2Val = $row['SELECTIONLISTOPTION_2'];

            $aFinalArray[$sColumn1Val][] = $sColumn2Val;

            if ($bUseBilateralRelations) {
                $aFinalArray[$sColumn2Val][] = $sColumn1Val;
            }
        }

        return $aFinalArray;
    }

    /**
     * We need to do it in admin mode to get raw results without additional FE processing
     */
    protected function assignValuesFromTextWithoutFEProcessing()
    {
        /** @var  \OxidEsales\Eshop\Core\Utils $oUtils */
        $oUtils = Registry::getUtils();
        $isAdmin = $oUtils->isAdmin();

        $oUtils->setAdminMode(true);
        $aList = $oUtils->assignValuesFromText($this->oxselectlist__oxvaldesc->getRawValue(), $this->getVat());
        $oUtils->setAdminMode($isAdmin);

        return $aList;
    }
}
