<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class SelectionListOption
 * @package JidexStudio\JxAdvancedSelectionList\Application\Model
 */
class SelectionListOption extends \OxidEsales\Eshop\Core\Model\BaseModel
{
    const ICONSDIR = 'out/pictures/jxadvancedselectionlist/icons/';
    const IMAGESDIR = 'out/pictures/jxadvancedselectionlist/images/';

    /**
     * Class constructor, initiates parent constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('jxadvancedselectionlistoptions');
    }

    /**
     * @param string $sOxid
     */
    public function setSelectionListOxid($sOxid)
    {
        $this->jxadvancedselectionlistoptions__selectionlistoxid = new Field($sOxid, Field::T_TEXT);
    }

    /**
     * @return string
     */
    public function getSelectionListOxid()
    {
        return $this->jxadvancedselectionlistoptions__selectionlistoxid->value;
    }

    /**
     * @param string $sOptionId
     */
    public function setOptionId($sOptionId)
    {
        $this->jxadvancedselectionlistoptions__optionid = new Field($sOptionId, Field::T_TEXT);
    }

    /**
     * @return string
     */
    public function getOptionId()
    {
        return $this->jxadvancedselectionlistoptions__optionid->value;
    }

    /**
     * @param string $sDescription
     */
    public function setDescription($sDescription)
    {
        $this->jxadvancedselectionlistoptions__description = new Field($sDescription, Field::T_TEXT);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->jxadvancedselectionlistoptions__description->value;
    }

    /**
     * @param string $sIcon
     */
    public function setIcon($sIcon)
    {
        $this->jxadvancedselectionlistoptions__icon = new Field($sIcon, Field::T_TEXT);
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->jxadvancedselectionlistoptions__icon->value;
    }

    /**
     * @param string $sImage
     */
    public function setImage($sImage)
    {
        $this->jxadvancedselectionlistoptions__image = new Field($sImage, Field::T_TEXT);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->jxadvancedselectionlistoptions__image->value;
    }

    /**
     * @param int $iLanguageId
     */
    public function setLanguageId($iLanguageId)
    {
        $this->jxadvancedselectionlistoptions__languageid = new Field($iLanguageId, Field::T_RAW);
    }

    /**
     * @return int
     */
    public function getLanguageId()
    {
        return $this->jxadvancedselectionlistoptions__languageid->value;
    }

    /**
     * @param string $sRelatedSelectionsList
     */
    public function setRelatedHiddenSelectionsList($sRelatedSelectionsList)
    {
        $this->jxadvancedselectionlistoptions__relatedhiddenselectionslist = new Field($sRelatedSelectionsList, Field::T_RAW);
    }

    /**
     * @return string
     */
    public function getRelatedHiddenSelectionsList()
    {
        return $this->jxadvancedselectionlistoptions__relatedhiddenselectionslist->value;
    }

    /**
     * @param $sSelectionListId
     * @param $sOptionId
     * @return bool
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function loadBySelectionListAndOptionId($sSelectionListId, $sOptionId, $iLanguageId = 0)
    {
        $sSelect = $this->buildSelectString([
            $this->getViewName() . ".selectionlistoxid" => $sSelectionListId,
            $this->getViewName() . ".optionid" => $sOptionId,
            $this->getViewName() . ".languageid" => $iLanguageId,
        ]);

        $aData = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->getRow($sSelect);

        if ($aData) {
            $this->assign($aData);
            $this->_isLoaded = true;

            return true;
        }

        return false;
    }

    /**
     * Loads object data from DB (object data ID must be passed to method).
     * Converts dates (oxarticle::oxarticles__oxinsert)
     * to international format (oxutils.php oxRegistry::get("oxUtilsDate")->formatDBDate(...)).
     * Returns true if article was loaded successfully.
     *
     * @param string $sOXID Article object ID
     *
     * @return bool
     */
    public function load($sOXID)
    {
        $sSelect = $this->buildSelectString([$this->getViewName() . ".oxid" => $sOXID]);
        $aData = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->getRow($sSelect);

        if ($aData) {
            $this->assign($aData);
            $this->_isLoaded = true;

            return true;
        }

        return false;
    }

    /**
     * @param $sColumn2Val
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function addHiddenOptionRelation($sColumn2Val)
    {
        $sColumn1Val = $this->getSelectionListOxid() . '|||' . $this->getOptionId();
        $sOxid = Registry::getUtilsObject()->generateUID();
        $query = "INSERT IGNORE INTO jxadvancedselectionlisthiddenoptionsmm (OXID, SELECTIONLISTOPTION_1, SELECTIONLISTOPTION_2) 
                  VALUES(:oxid,:val1,:val2);";

        DatabaseProvider::getDb()->execute($query, [
            ':oxid' => $sOxid,
            ':val1' => $sColumn1Val,
            ':val2' => $sColumn2Val
        ]);
    }

    /**
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function deleteAllHiddenOptionRelations()
    {
        $oConfig = Registry::getConfig();
        $bUseBilateralRelations = $oConfig->getConfigParam('jxadvancedselectionlist_main_hidden_options_bilateral_relations');

        $sColumnVal = $this->getSelectionListOxid() . '|||' . $this->getOptionId();

        if ($bUseBilateralRelations) {
            $query = "DELETE FROM jxadvancedselectionlisthiddenoptionsmm  WHERE SELECTIONLISTOPTION_1 = :val OR SELECTIONLISTOPTION_2 = :val";
        } else {
            $query = "DELETE FROM jxadvancedselectionlisthiddenoptionsmm  WHERE SELECTIONLISTOPTION_1 = :val";
        }

        DatabaseProvider::getDb()->execute($query, [
            ':val' => $sColumnVal
        ]);
    }

    /**
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function getAllHiddenOptionRelations()
    {
        $oConfig = Registry::getConfig();
        $bUseBilateralRelations = $oConfig->getConfigParam('jxadvancedselectionlist_main_hidden_options_bilateral_relations');
        $sColumnVal = $this->getSelectionListOxid() . '|||' . $this->getOptionId();
        $sColumnValGeneral = $this->getSelectionListOxid() . '|||' . '*';

        if ($bUseBilateralRelations) {
            $sSelect = "SELECT * FROM jxadvancedselectionlisthiddenoptionsmm  WHERE SELECTIONLISTOPTION_1 = :val OR SELECTIONLISTOPTION_2 = :val OR SELECTIONLISTOPTION_1 = :val1 OR SELECTIONLISTOPTION_2 = :val1";
        } else {
            $sSelect = "SELECT * FROM jxadvancedselectionlisthiddenoptionsmm  WHERE SELECTIONLISTOPTION_1 = :val OR SELECTIONLISTOPTION_1 = :val1";
        }

        return DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->getAll($sSelect, [
            ':val' => $sColumnVal,
            ':val1' => $sColumnValGeneral
        ]);
    }
}
