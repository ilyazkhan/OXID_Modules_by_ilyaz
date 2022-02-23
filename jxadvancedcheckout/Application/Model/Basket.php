<?php

namespace JidexStudio\JxAdvancedCheckout\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class Basket
 * @package JidexStudio\JxAdvancedCheckout\Application\Model
 */
class Basket extends Basket_parent
{
    /**
     * Adds user item to basket. Returns oxBasketItem object if adding succeeded
     *
     * @param string $sProductID id of product
     * @param double $dAmount product amount
     * @param mixed $aSel product select lists (default null)
     * @param mixed $aPersParam product persistent parameters (default null)
     * @param bool $blOverride marker to accumulate passed amount or renew (default false)
     * @param bool $blBundle marker if product is bundle or not (default false)
     * @param mixed $sOldBasketItemId id if old basket item if to change it
     *
     * @return object oxArticleInputException, oxNoArticleException
     * @throws \OxidEsales\Eshop\Core\Exception\ArticleInputException
     * @throws \OxidEsales\Eshop\Core\Exception\NoArticleException
     * @throws \OxidEsales\Eshop\Core\Exception\OutOfStockException
     *
     */
    public function addToBasket($sProductID, $dAmount, $aSel = null, $aPersParam = null, $blOverride = false, $blBundle = false, $sOldBasketItemId = null)
    {
        $basketItem = parent::addToBasket($sProductID, $dAmount, $aSel, $aPersParam, $blOverride, $blBundle, $sOldBasketItemId);

        Registry::getSession()->setVariable('lastAddedBasketItem', $basketItem);

        return $basketItem;
    }

}
