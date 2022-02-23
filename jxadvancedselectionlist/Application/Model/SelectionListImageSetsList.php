<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Model;

use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class SelectionListOptionsImageSetsList
 * @package JidexStudio\JxAdvancedSelectionList\Application\Model
 */
class SelectionListImageSetsList extends \OxidEsales\Eshop\Core\Model\ListModel
{
    const MAX_ALLOWED_OPTIONS_IN_SET = 8;

    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = SelectionListImageSetsItem::class;


    /**
     * @param $sSelectionOption
     * @param $sSelectedArticleOxid
     * @return $this
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getAllBySelectionOptionAndArticleId($sSelectionOption, $sSelectedArticleOxid)
    {
        $oListObject = $this->getBaseObject();
        $sQ = "select * from " . $oListObject->getViewName();

        $sQ .= " where (1=0 ";

        for($i = 1; $i <= static::MAX_ALLOWED_OPTIONS_IN_SET; $i++) {
            $sQ .= " OR SELECTIONLISTOPTION_{$i} = " . DatabaseProvider::getDb()->quote($sSelectionOption);
        }

        $sQ .= ") ";

        $sQ .= " AND PRODUCTID = " . DatabaseProvider::getDb()->quote($sSelectedArticleOxid ?: '');

        $this->selectString($sQ);

        return $this;
    }

    /**
     * @param $sSelectionListOxid
     * @param $sSelectedArticleOxid
     * @return $this
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getAllBySelectionListAndArticleId($sSelectionListOxid, $sSelectedArticleOxid)
    {
        $oListObject = $this->getBaseObject();
        $sQ = "select * from " . $oListObject->getViewName();

        $sQ .= " where (1=0 ";

        for($i = 1; $i <= static::MAX_ALLOWED_OPTIONS_IN_SET; $i++) {
            $sQ .= " OR SELECTIONLISTOPTION_{$i} LIKE " . DatabaseProvider::getDb()->quote($sSelectionListOxid . '|||%');
        }

        $sQ .= ") ";

        $sQ .= " AND (PRODUCTID = " . DatabaseProvider::getDb()->quote($sSelectedArticleOxid) . " OR PRODUCTID ='')";

        $this->selectString($sQ);

        return $this;
    }
}
