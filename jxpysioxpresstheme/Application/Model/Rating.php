<?php

namespace JidexStudio\JxPysioXpressTheme\Application\Model;

use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class Rating
 * @package JidexStudio\JxPysioXpressTheme\Application\Model
 */
class Rating extends Rating_parent
{
    /**
     * Override parent method. Disallow possibility to rate if parent product or one of the variants was already voted
     *
     * @param $sUserId
     * @param $sType
     * @param $sObjectId
     * @return bool
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    public function allowRating($sUserId, $sType, $sObjectId)
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $myConfig = $this->getConfig();

        if ($iRatingLogsTimeout = $myConfig->getConfigParam('iRatingLogsTimeout')) {
            $sExpDate = date('Y-m-d H:i:s', \OxidEsales\Eshop\Core\Registry::getUtilsDate()->getTime() - $iRatingLogsTimeout * 24 * 60 * 60);
            $oDb->execute("delete from oxratings where oxtimestamp < :expDate", [
                ':expDate' => $sExpDate
            ]);
        }

        /** @var Article $oArticle */
        $oArticle = oxNew(Article::class);
        $oArticle->load($sObjectId);
        $sParentId = $sObjectId;

        if ($oArticle->isLoaded()) {
            if ($oArticle->isVariant()) {
                $sParentId = $oArticle->getParentId();
            }
        }

        $sArticleTable = $oArticle->getViewName();

        $sSelect = "select oxid from oxratings 
            where oxuserid = :oxuserid 
                and oxtype = :oxtype 
                and oxobjectid IN (SELECT oxid FROM $sArticleTable WHERE oxid = :oxobjectid OR oxparentid = :oxparentid)";
        $params = [
            ':oxuserid' => $sUserId,
            ':oxtype' => $sType,
            ':oxobjectid' => $sObjectId,
            ':oxparentid' => $sParentId
        ];

        if ($oDb->getOne($sSelect, $params)) {
            return false;
        }

        return true;
    }
}
