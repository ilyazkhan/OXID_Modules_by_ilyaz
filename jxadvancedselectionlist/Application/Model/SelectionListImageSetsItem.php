<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;

/**
 * Class SelectionListImageSetsItem
 * @package JidexStudio\JxAdvancedSelectionList\Application\Model
 */
class SelectionListImageSetsItem extends \OxidEsales\Eshop\Core\Model\BaseModel
{
    const IMAGESDIR = 'out/pictures/jxadvancedselectionlist/images/';

    /**
     * Class constructor, initiates parent constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->init('jxadvancedselectionlistimagesets');
    }

    /**
     * @param string $iIndex
     */
    public function setSelectionListOption($sSelectionListOption, $iIndex)
    {
        $sFieldName = "jxadvancedselectionlistimagesets__selectionlistoption_{$iIndex}";

        $this->{$sFieldName} = new Field($sSelectionListOption, Field::T_TEXT);
    }

    /**
     * @param $iIndex
     * @return mixed
     */
    public function getSelectionListOption($iIndex)
    {
        $sFieldName = "jxadvancedselectionlistimagesets__selectionlistoption_{$iIndex}";

        return $this->{$sFieldName}->value;
    }

    /**
     * @param string $sImage
     */
    public function setImage($sImage)
    {
        $this->jxadvancedselectionlistimagesets__image = new Field($sImage, Field::T_TEXT);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->jxadvancedselectionlistimagesets__image->value;
    }

    /**
     * @param string $sDescription
     */
    public function setDescription($sDescription)
    {
        $this->jxadvancedselectionlistimagesets__description = new Field($sDescription, Field::T_TEXT);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->jxadvancedselectionlistimagesets__description->value;
    }

    /**
     * @param string $sProductId
     */
    public function setProductId($sProductId)
    {
        $this->jxadvancedselectionlistimagesets__productid = new Field($sProductId, Field::T_TEXT);
    }

    /**
     * @return string
     */
    public function getProductId()
    {
        return $this->jxadvancedselectionlistimagesets__productid->value;
    }

    /**
     * @param $aOptionSet
     * @param $sProductId
     * @return bool
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function loadByOptionsSet($aOptionSet, $sProductId = '')
    {
        $aSelectionArray = [];

        for ($i = 1; $i <= SelectionListImageSetsList::MAX_ALLOWED_OPTIONS_IN_SET; $i++) {
            $sOptionVal = array_shift($aOptionSet);
            $aSelectionArray[$this->getViewName() . ".selectionlistoption" . "_" . $i] = $sOptionVal ?: '';
        }

        $aSelectionArray[$this->getViewName() . ".productid"] = $sProductId;

        $sSelect = $this->buildSelectString($aSelectionArray);

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
}
