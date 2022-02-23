<?php

namespace JidexStudio\JxPysioXpressTheme\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Article amount price list
 */
class AmountPriceList extends AmountPriceList_parent
{
    /**
     * Get data from db
     *
     * @return array
     * @deprecated underscore prefix violates PSR12, will be renamed to "loadFromDb" in next major
     */
    protected function _loadFromDb() // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
    {
        $sArticleId = $this->getArticle()->getId();

        $res = $this->loadFromDbByArticleId($sArticleId);

        if (empty($res)
            && $this->getConfig()->getConfigParam('blVariantInheritAmountPrice')
            && $this->getArticle()->getParentId()
        ) {
            $sArticleId = $this->getArticle()->getParentId();

            $res = $this->loadFromDbByArticleId($sArticleId);
        }

        return $res;
    }

    /**
     * @param $sArticleId
     * @return array
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseConnectionException
     * @throws \OxidEsales\Eshop\Core\Exception\DatabaseErrorException
     */
    protected function loadFromDbByArticleId($sArticleId)
    {
        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

        $params = [
            ':oxartid' => $sArticleId
        ];

        if ($this->getConfig()->getConfigParam('blMallInterchangeArticles')) {
            $sShopSelect = '1';
        } else {
            $sShopSelect = " `oxshopid` = :oxshopid ";
            $params[':oxshopid'] = $this->getConfig()->getShopId();
        }

        $sSql = "SELECT * FROM `oxprice2article` 
            WHERE `oxartid` = :oxartid AND $sShopSelect ORDER BY `oxamount` ";

        return $db->getAll($sSql, $params);
    }
}
