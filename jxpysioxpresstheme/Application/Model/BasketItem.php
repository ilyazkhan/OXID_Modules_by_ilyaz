<?php

namespace JidexStudio\JxPysioXpressTheme\Application\Model;

use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class BasketItem
 * @package JidexStudio\JxPysioXpressTheme\Application\Model
 */
class BasketItem extends BasketItem_parent
{
    /**
     * Price that it was calculated on details page after all selections/variants are chosen but without discount
     *
     * @var double
     */
    protected $_dDetailsPageCalculatedPriceWithoutDiscount = null;

    /**
     * @return float
     */
    public function getDetailsPageCalculatedPriceWithoutDiscount()
    {
        if (null == $this->_dDetailsPageCalculatedPriceWithoutDiscount) {
            // price without calculated discounts (not old price)
            $dDetailsPageCalculatedPriceWithoutDiscount = $this->getArticle()->getBasketPrice(1, $this->getSelList(), $this->getSession()->getBasket())->getPrice();
            $oTprice = $this->getArticle()->getTPrice();

            // if old price is set for the article and it is bigger then calculated price without discounts - consider old price as price without discounts
            if ($oTprice && $oTprice->getBruttoPrice() > $dDetailsPageCalculatedPriceWithoutDiscount) {
                $dDetailsPageCalculatedPriceWithoutDiscount = $oTprice->getBruttoPrice();
            }

            $this->_dDetailsPageCalculatedPriceWithoutDiscount = $dDetailsPageCalculatedPriceWithoutDiscount;
        }

        return $this->_dDetailsPageCalculatedPriceWithoutDiscount;
    }

    /**
     * @return float
     */
    public function getFDetailsPageCalculatedPriceWithoutDiscount()
    {
        return \OxidEsales\Eshop\Core\Registry::getLang()->formatCurrency($this->getDetailsPageCalculatedPriceWithoutDiscount());
    }

    /**
     * @return float
     */
    public function getFTotalDetailsPageCalculatedPriceWithoutDiscount()
    {
        return \OxidEsales\Eshop\Core\Registry::getLang()->formatCurrency($this->getDetailsPageCalculatedPriceWithoutDiscount() * $this->getAmount());
    }

    /**
     * @return float
     */
    public function formatPrice($price)
    {
        return \OxidEsales\Eshop\Core\Registry::getLang()->formatCurrency($price);
    }
}
