<?php

namespace JidexStudio\JxPysioXpressTheme\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;

/**
 * Class Article
 * @package JidexStudio\JxPysioXpressTheme\Application\Model
 */
class Article extends Article_parent
{
    /**
     * EXTEND PARENT LOGIC: add PRICE_FROM label for products with selections as well
     *
     * @return bool
     */
    public function isRangePrice()
    {
        if ($this->_blIsRangePrice === null) {
            $this->setRangePrice(false);

            if ($this->_hasAnyVariant()) {
                $dPrice = $this->_getPrice();
                $dMinPrice = $this->_getVarMinPrice();
                $dMaxPrice = $this->_getVarMaxPrice();

                if ($dMinPrice != $dMaxPrice) {
                    $this->setRangePrice();
                } elseif (!$this->isParentNotBuyable() && $dMinPrice != $dPrice) {
                    $this->setRangePrice();
                }
            } elseif ($this->getSelections()) {
                $this->setRangePrice(true);
            }
        }

        return $this->_blIsRangePrice;
    }

    /**
     * @return string
     */
    public function getDiscountUnitLabel()
    {
        $label = $this->oxarticles__jxdiscountpieceunit->value;

        if (!$label && $this->isVariant()) {
            $label = $this->getParentArticle()->oxarticles__jxdiscountpieceunit->value;
        }

        return $label;
    }

    /**
     * @return bool
     */
    public function isNewProduct(&$newProducts)
    {
        if (!is_array($newProducts)) {
            $newProducts = [];
            $articleList = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
            $oBaseObject = $articleList->getBaseObject();
            $sArticleTable = getViewName('oxarticles');

            if ($this->getConfig()->getConfigParam('blNewArtByInsert')) {
                $sType = 'oxinsert';
            } else {
                $sType = 'oxtimestamp';
            }

            $sSelect = "select OXID from $sArticleTable ";
            $sSelect .= "where oxparentid = '' and " . $oBaseObject->getSqlActiveSnippet() . " and oxissearch = 1 order by $sType desc ";
            $sSelect .= "limit 50";
            $articleList->selectString($sSelect);
            $newProducts = $articleList->getArray();
        }

        // only parent product should be checked
        if ($this->isVariant()) {
            return isset($newProducts[$this->getParentArticle()->getId()]);;
        }

        return isset($newProducts[$this->getId()]);
    }

    /**
     * @return bool
     */
    public function isConfiguratorProduct()
    {
        $selections = $this->getSelections();

        return !empty($selections);
    }

    /**
     * @param $setProducts
     * @return bool
     */
    public function isSetProduct(&$setProducts)
    {
        if (!is_array($setProducts)) {
            $setProducts = [];
            $articleList = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
            $oBaseObject = $articleList->getBaseObject();
            $sArticleTable = getViewName('oxarticles');

            $sSelect = "select OXID from $sArticleTable ";
            $sSelect .= "where " . $oBaseObject->getSqlActiveSnippet();
            $sSelect .= ' and ((oxtitle REGEXP ' . DatabaseProvider::getDb()->quote('[[:<:]]set[[:>:]]') . ')';
            $sSelect .= ' or (oxvarselect REGEXP ' . DatabaseProvider::getDb()->quote('[[:<:]]set[[:>:]]') . '))';

            $articleList->selectString($sSelect);
            $setProducts = $articleList->getArray();
        }

        if (isset($setProducts[$this->getId()])) {
            return true;
        }

        $variants = $this->getVariants();

        foreach ($variants as $variant) {
            if (isset($setProducts[$variant->getId()])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSaleProduct()
    {
        $bprice = $this->oxarticles__oxtprice->value;
        $price = $this->getPrice();

        if ($bprice && $bprice > $price->getNettoPrice()) {
            return true;
        }

        $variants = $this->_loadVariantList(false, true, null);

        foreach ($variants as $variant) {
            $bprice = $this->oxarticles__oxtprice->value;
            $price = $variant->getPrice();

            if ($bprice && $bprice > $price->getNettoPrice()) {
                return true;
            }
        }

        return false;
    }
}
