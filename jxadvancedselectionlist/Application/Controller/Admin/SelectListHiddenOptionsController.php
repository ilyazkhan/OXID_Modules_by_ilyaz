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
class SelectListHiddenOptionsController extends SelectListOptionsController
{
    /**
     * Current class template name.
     *
     * @var string
     */
    protected $_sThisTemplate = 'jxadvancedselectionlist_hiddenoptions.tpl';

    /**
     * Load selected option data. Create appropriate record in DB if does not exist
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function selectOption()
    {
        parent::selectOption();

        $oConfig = Registry::getConfig();
        $bUseBilateralRelations = $oConfig->getConfigParam('jxadvancedselectionlist_main_hidden_options_bilateral_relations');

        $aAllSelectionLists = [];
        $aRelatedSelection = [];
        $aAllSelectionListOptions = [];
        $aAllHiddenOptionRelations = [];
        $aLockedHiddenOptionRelations = [];

        /** @var  SelectListList $oSelectListList */
        $oSelectListList = oxNew(SelectListList::class);
        $oSelectListItems = $oSelectListList->getList();

        foreach ($oSelectListItems as $oSelectListItem) {
            $aAllSelectionListOptions[$oSelectListItem->getId()]['title'] = $oSelectListItem->oxselectlist__oxident->value ?: $oSelectListItem->getLabel();
            $aAllSelectionListOptions[$oSelectListItem->getId()]['options'] = [];

            $aFieldArray = Registry::getUtils()->assignValuesFromText($oSelectListItem->oxselectlist__oxvaldesc->getRawValue());

            foreach ($aFieldArray as $sKey => $oField) {
                $sOptionId = implode('_', [$oField->name, $oField->price, $oField->priceUnit]);
                $aAllSelectionListOptions[$oSelectListItem->getId()]['options'][$sOptionId] = $oField;
            }
        }

        $sRelatedSelectionsList = $this->_aViewData["oSelectedOption"]->getRelatedHiddenSelectionsList();
        $aRelatedSelection = array_filter(explode('|||', (string)$sRelatedSelectionsList));

        $aResult = $this->_aViewData["oSelectedOption"]->getAllHiddenOptionRelations();

        foreach($aResult as $row) {
            $sColumn1Val = $row['SELECTIONLISTOPTION_1'];
            $sColumn2Val = $row['SELECTIONLISTOPTION_2'];

            $aColumn1Val = explode('|||', $sColumn1Val);
            $aColumn2Val = explode('|||', $sColumn2Val);

            if ($aColumn1Val[0] != $this->_aViewData["oSelectedOption"]->getSelectionListOxid()) {
                $aAllHiddenOptionRelations[$aColumn1Val[0]][$aColumn1Val[1]] = 1;

                if ($aColumn2Val[1] == '*') {
                    $aLockedHiddenOptionRelations[$aColumn1Val[0]] = 1;
                }
            } elseif ($aColumn2Val[0] != $this->_aViewData["oSelectedOption"]->getSelectionListOxid()) {
                $aAllHiddenOptionRelations[$aColumn2Val[0]][$aColumn2Val[1]] = 1;

                if ($aColumn1Val[1] == '*') {
                    $aLockedHiddenOptionRelations[$aColumn2Val[0]] = 1;
                }
            }
        }

        foreach($aAllHiddenOptionRelations as $sSelectListId => $data) {
            if (!in_array($sSelectListId, $aRelatedSelection)) {
                $aRelatedSelection[] = $sSelectListId;
            }
        }

        $this->_aViewData["aAllSelectionListOptions"] = $aAllSelectionListOptions;
        $this->_aViewData["aRelatedSelection"] = $aRelatedSelection;
        $this->_aViewData["aAllHiddenOptionRelations"] = $aAllHiddenOptionRelations;
        $this->_aViewData["aLockedHiddenOptionRelations"] = $bUseBilateralRelations ? $aLockedHiddenOptionRelations : [];
        $this->_aViewData["bUseBilateralRelations"] = $bUseBilateralRelations;
    }

    /**
     * Save option
     *
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function save()
    {
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

            $aSelectionLists = Registry::getConfig()->getRequestParameter("selectionlist");

            if (is_array($aSelectionLists) && !empty($aSelectionLists)) {
                $sRelatedSelection = implode('|||', $aSelectionLists);
            }

            $oOption->setRelatedHiddenSelectionsList($sRelatedSelection);
            $oOption->deleteAllHiddenOptionRelations();

            $aSelectionListsOptions = Registry::getConfig()->getRequestParameter("selectionlistoptions");

            $aSelectListIds = [];

            if (is_array($aSelectionLists) && !empty($aSelectionLists)) {
                foreach ($aSelectionListsOptions as $key => $aHiddenOptions) {
                    $sSelectListId = $aSelectionLists[$key] ?: null;

                    if (empty($sSelectListId)) {
                        continue;
                    }

                    if (in_array($sSelectListId, $aSelectListIds)) {
                        throw new \Exception('JXADVANCEDSELECTIONLIST_ERROR_DUPLICATE_SELECTIONS');
                    }

                    $aSelectListIds[] = $sSelectListId;

                    if (!empty($aHiddenOptions)) {
                        foreach ($aHiddenOptions as $sHiddenOptionId) {
                            $columnVal = $sSelectListId . '|||' . $sHiddenOptionId;
                            $oOption->addHiddenOptionRelation($columnVal);
                        }
                    } else {
                        $columnVal = $sSelectListId . '|||' . '*';
                        $oOption->addHiddenOptionRelation($columnVal);
                    }
                }
            }

            $oOption->save();
        } catch (\Exception $e) {
            $this->_aViewData["errorMessage"] = Registry::getLang()->translateString($e->getMessage(), $this->_iEditLang, true);
        }

        return $this->selectOption();
    }
}
