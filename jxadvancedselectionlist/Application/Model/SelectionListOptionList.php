<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Model;

use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class SelectionListOptionList
 * @package JidexStudio\JxAdvancedSelectionList\Application\Model
 */
class SelectionListOptionList extends \OxidEsales\Eshop\Core\Model\ListModel
{

    /**
     * List Object class name
     *
     * @var string
     */
    protected $_sObjectsInListName = SelectionListOption::class;

    /**
     * @param $sSelectionListOxid
     * @param int $iLanguageId
     * @return $this
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getAllBySelectionList($sSelectionListOxid, $iLanguageId = 0)
    {
        $oListObject = $this->getBaseObject();
        $sQ = "select * from " . $oListObject->getViewName();

        $sQ .= " where selectionlistoxid = " . DatabaseProvider::getDb()->quote($sSelectionListOxid);
        $sQ .= " AND languageId = " . DatabaseProvider::getDb()->quote($iLanguageId);

        $this->selectString($sQ);

        return $this;
    }

    /**
     * @param int $iLanguageId
     * @return $this
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     */
    public function getAll($iLanguageId = 0)
    {
        $oListObject = $this->getBaseObject();
        $sQ = "select * from " . $oListObject->getViewName();

        $sQ .= " where languageId = " . DatabaseProvider::getDb()->quote($iLanguageId);
        $sQ .= " order by OPTIONID";
        $this->selectString($sQ);

        return $this;
    }
}
