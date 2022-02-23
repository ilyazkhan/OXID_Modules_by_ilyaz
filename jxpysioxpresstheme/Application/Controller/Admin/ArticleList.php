<?php

namespace JidexStudio\JxPysioXpressTheme\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Model\Article;
use oxRegistry;
use oxDb;

/**
 * Class ArticleList
 * @package JidexStudio\JxPysioXpressTheme\Application\Controller\Admin
 */
class ArticleList extends ArticleList_parent
{
    /**
     * Collects articles base data and passes them according to filtering rules,
     * returns name of template file "article_list.tpl".
     *
     * @return string
     */
    public function render()
    {
        $myConfig = $this->getConfig();
        $sPwrSearchFld = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("pwrsearchfld");
        $sPwrSearchFld = $sPwrSearchFld ? strtolower($sPwrSearchFld) : "oxtitle";

        $sDateTime = $this->getServerDateTime();
        $blUseTimeCheck = $this->getConfig()->getConfigParam('blUseTimeCheck');
        $oArticle = null;
        $oList = $this->getItemList();
        if ($oList) {
            foreach ($oList as $key => $oArticle) {
                $sFieldName = "oxarticles__{$sPwrSearchFld}";

                // formatting view
                if (!$myConfig->getConfigParam('blSkipFormatConversion')) {
                    if ($oArticle->$sFieldName->fldtype == "datetime") {
                        \OxidEsales\Eshop\Core\Registry::getUtilsDate()->convertDBDateTime($oArticle->$sFieldName);
                    } elseif ($oArticle->$sFieldName->fldtype == "timestamp") {
                        \OxidEsales\Eshop\Core\Registry::getUtilsDate()->convertDBTimestamp($oArticle->$sFieldName);
                    } elseif ($oArticle->$sFieldName->fldtype == "date") {
                        \OxidEsales\Eshop\Core\Registry::getUtilsDate()->convertDBDate($oArticle->$sFieldName);
                    }
                }

                $oArticle->showActiveCheckInAdminPanel = $this->isArticleActive($sDateTime, $blUseTimeCheck, $oArticle);

                if (empty($oArticle->oxarticles__oxtitle->value)) {
                    $oArticle->oxarticles__oxtitle->value = $oArticle->oxarticles__oxvarselect->value;
                }

                $oArticle->pwrsearchval = $oArticle->$sFieldName->value;

                $oList[$key] = $oArticle;
            }
        }

        parent::render();

        // load fields
        if (!$oArticle && $oList) {
            $oArticle = $oList->getBaseObject();
        }
        $this->_aViewData["pwrsearchfields"] = $oArticle ? $this->getSearchFields() : null;
        $this->_aViewData["pwrsearchfld"] = strtoupper($sPwrSearchFld);

        $aFilter = $this->getListFilter();
        if (isset($aFilter["oxarticles"][$sPwrSearchFld])) {
            $this->_aViewData["pwrsearchinput"] = $aFilter["oxarticles"][$sPwrSearchFld];
        }

        $sType = '';
        $sValue = '';

        $sArtCat = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("art_category");
        if ($sArtCat && strstr($sArtCat, "@@") !== false) {
            list($sType, $sValue) = explode("@@", $sArtCat);
        }
        $this->_aViewData["art_category"] = $sArtCat;

        // parent categorie tree
        $this->_aViewData["cattree"] = $this->getCategoryList($sType, $sValue);

        // manufacturer list
        $this->_aViewData["mnftree"] = $this->getManufacturerlist($sType, $sValue);

        // vendor list
        $this->_aViewData["vndtree"] = $this->getVendorList($sType, $sValue);

        return "article_list.tpl";
    }

    /**
     * @return bool|string
     */
    private function getServerDateTime()
    {
        $sDateTimeAsTimestamp = \OxidEsales\Eshop\Core\Registry::getUtilsDate()->getTime();
        $sDateTime = \OxidEsales\Eshop\Core\Registry::getUtilsDate()->formatDBTimestamp($sDateTimeAsTimestamp);

        return $sDateTime;
    }

    /**
     * @param bool|string $sDateTime
     * @param bool        $blUseTimeCheck
     * @param Article     $oArticle
     *
     * @return bool
     */
    private function isArticleActive($sDateTime, $blUseTimeCheck, $oArticle)
    {
        if (!is_bool($sDateTime) && isset($oArticle->oxarticles__oxactive) && $oArticle->oxarticles__oxactive->value === '1') {
            return true;
        } else {
            if (
                !is_bool($sDateTime) && isset($oArticle->oxarticles__oxactivefrom) &&
                isset($oArticle->oxarticles__oxactiveto) && $blUseTimeCheck &&
                $oArticle->oxarticles__oxactivefrom->value <= $sDateTime &&
                $oArticle->oxarticles__oxactiveto->value >= $sDateTime
            ) {
                return true;
            }
        }

        return false;
    }
}
