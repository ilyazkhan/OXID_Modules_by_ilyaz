<?php
namespace JidexStudio\JxAdvancedSelectionList\Core;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\DbMetaDataHandler;

/**
 * Class Module
 * @package JidexStudio\JxAdvancedSelectionList\Core
 */
class Module
{
    /**
     * Execute action on activate event.
     *
     * @return void
     */
    public static function onActivate()
    {
        self::addDatabaseStructure();
        self::updateViews();
        self::clearTmp();
    }

    /**
     * Execute action on deactivate event.
     *
     * @return void
     */
    public static function onDeactivate()
    {
        self::clearTmp();
    }

    /**
     * Regenerates database view-tables.
     *
     * @return void
     */
    public static function updateViews()
    {
        $oDbHandler = oxNew(DbMetaDataHandler::class);
        $oDbHandler->updateViews();
    }

    /**
     * Clear tmp dir and smarty cache.
     *
     * @return void
     */
    public static function clearTmp()
    {
        $sTmpDir = getShopBasePath() . "/tmp/";
        $sSmartyDir = $sTmpDir . "smarty/";

        foreach (glob($sTmpDir . "*.txt") as $sFileName) {
            unlink($sFileName);
        }
        foreach (glob($sSmartyDir . "*.php") as $sFileName) {
            unlink($sFileName);
        }
    }

    /**
     * Creating database structure changes.
     *
     * @return void
     */
    public static function addDatabaseStructure()
    {
        //ADD NEW TABLE
        self::addTableIfNotExists(
            'jxadvancedselectionlistoptions',
            "CREATE TABLE jxadvancedselectionlistoptions (
              `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
              SELECTIONLISTOXID char(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              OPTIONID varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              DESCRIPTION text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              ICON varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              IMAGE varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              LANGUAGEID INT(8) DEFAULT 0 NOT NULL,
              PRIMARY KEY (OXID)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );

        //ADD NEW TABLE
        self::addTableIfNotExists(
            'jxadvancedselectionlistimagesets',
            "CREATE TABLE jxadvancedselectionlistimagesets (
              `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
              SELECTIONLISTOPTION_1 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              SELECTIONLISTOPTION_2 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              SELECTIONLISTOPTION_3 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              SELECTIONLISTOPTION_4 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              SELECTIONLISTOPTION_5 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              SELECTIONLISTOPTION_6 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              SELECTIONLISTOPTION_7 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              SELECTIONLISTOPTION_8 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              IMAGE varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              PRIMARY KEY (OXID)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );

        // '1.0.1' changes
        self::addColumnIfNotExists("jxadvancedselectionlistimagesets", 'PRODUCTID', "ALTER TABLE jxadvancedselectionlistimagesets ADD COLUMN PRODUCTID char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;");

        // '1.0.2' changes
        //ADD NEW TABLE
        self::addTableIfNotExists(
            'jxadvancedselectionlisthiddenoptionsmm',
            "CREATE TABLE jxadvancedselectionlisthiddenoptionsmm (
              `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
              SELECTIONLISTOPTION_1 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              SELECTIONLISTOPTION_2 varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
              PRIMARY KEY (OXID)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;"
        );
        self::addColumnIfNotExists("jxadvancedselectionlistoptions", 'RELATEDHIDDENSELECTIONSLIST', "ALTER TABLE jxadvancedselectionlistoptions ADD COLUMN RELATEDHIDDENSELECTIONSLIST text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");

        // '1.0.3' changes
        //ADD NEW FIELDS
        self::addColumnIfNotExists("jxadvancedselectionlistimagesets", 'DESCRIPTION', "ALTER TABLE jxadvancedselectionlistimagesets ADD COLUMN DESCRIPTION text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");

        // '1.0.4' changes
        //ADD NEW FIELDS
        self::addColumnIfNotExists("oxarticles", 'JXADVANCEDSELECTIONLISTUSESTEPBYSTEPSELECTION', "ALTER TABLE oxarticles ADD COLUMN JXADVANCEDSELECTIONLISTUSESTEPBYSTEPSELECTION int(8) DEFAULT 0 NOT NULL;");

        // '1.1.0' changes
        //ADD NEW FIELDS
        self::addColumnIfNotExists("oxarticles", 'JXADVANCEDSELECTIONLISTVARIANTSDATA', "ALTER TABLE oxarticles ADD COLUMN JXADVANCEDSELECTIONLISTVARIANTSDATA text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;");

        // '1.1.1' changes
        //ADD NEW FIELDS
        self::addColumnIfNotExists("oxarticles", 'JXADVANCEDSELECTIONLISTSHAREDISCOUNTS', "ALTER TABLE oxarticles ADD COLUMN JXADVANCEDSELECTIONLISTSHAREDISCOUNTS int(8) DEFAULT 0 NOT NULL;");

    }

    /**
     * Add a database table.
     *
     * @param string $sTableName table to add
     * @param string $sQuery     sql-query to add table
     *
     * @return boolean true or false
     */
    public static function addTableIfNotExists($sTableName, $sQuery)
    {
        if (!DatabaseProvider::getDb()->select("SHOW TABLES LIKE '{$sTableName}'")->count()) {
            DatabaseProvider::getDb()->execute($sQuery);
            return true;
        }
        return false;
    }

    /**
     * Add a column to a database table.
     *
     * @param string $sTableName  table name
     * @param string $sColumnName column name
     * @param string $sQuery      sql-query to add column to table
     *
     * @return boolean true or false
     */
    public static function addColumnIfNotExists($sTableName, $sColumnName, $sQuery)
    {
        if (!DatabaseProvider::getDb()->select("SHOW COLUMNS FROM {$sTableName} LIKE '{$sColumnName}'")->count()) {
            DatabaseProvider::getDb()->execute($sQuery);
            return true;
        }
        return false;
    }

    /**
     * Insert a database row to an existing table.
     *
     * @param string $sTableName database table name
     * @param array  $aKeyValue  keys of rows to add for existance check
     * @param string $sQuery     sql-query to insert data
     *
     * @return boolean true or false
     */
    public static function insertRowIfNotExists($sTableName, $aKeyValue, $sQuery)
    {
        $sWhere = '';
        foreach ($aKeyValue as $key => $value) {
            $sWhere .= " AND $key = '$value'";
        }

        if (!DatabaseProvider::getDb()->select("SELECT * FROM {$sTableName} WHERE 1" . $sWhere)->count()) {
            DatabaseProvider::getDb()->execute($sQuery);
            return true;
        }
        return false;
    }
}
