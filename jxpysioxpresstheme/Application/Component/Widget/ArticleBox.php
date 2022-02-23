<?php

namespace JidexStudio\JxPysioXpressTheme\Application\Component\Widget;

use OxidEsales\Eshop\Application\Model\Article;

/**
 * Class ArticleBox
 * @package JidexStudio\JxPysioXpressTheme\Application\Component\Widget
 */
class ArticleBox extends ArticleBox_parent
{
    /**
     * Rating value.
     *
     * @var double
     */
    protected $_dRatingValue = null;

    /**
     * Template variable getter. Returns if review module is on.
     *
     * @return bool
     */
    public function isReviewActive()
    {
        return $this->getConfig()->getConfigParam('bl_perfLoadReviews');
    }

    /**
     * Template variable getter. Returns rating value.
     *
     * @return double
     */
    public function getRatingValue()
    {
        /** @var Article $oDetailsProduct */
        $oDetailsProduct = $this->getProduct();

        if ($oDetailsProduct->isVariant()) {
            $oDetailsProduct = $oDetailsProduct->getParentArticle();
        }

        if ($this->_dRatingValue === null) {
            $this->_dRatingValue = (double)0;
            if ($this->isReviewActive() && $oDetailsProduct) {
                $blShowVariantsReviews = $this->getConfig()->getConfigParam('blShowVariantReviews');
                $this->_dRatingValue = round($oDetailsProduct->getArticleRatingAverage($blShowVariantsReviews), 1);
            }
        }

        return (double)$this->_dRatingValue;
    }

    /**
     * @return bool
     */
    public function isNewProduct()
    {
        $activeView = $this->getConfig()->getTopActiveView();

        return $this->getProduct()->isNewProduct($activeView->newProducts);
    }

    /**
     * @return bool
     */
    public function isSetProduct()
    {
        $activeView = $this->getConfig()->getTopActiveView();

        return $this->getProduct()->isSetProduct($activeView->setProducts);
    }

    /**
     * @return bool
     */
    public function isConfiguratorProduct()
    {
        return $this->getProduct()->isConfiguratorProduct();
    }

    /**
     * @return bool
     */
    public function isSaleProduct()
    {
        return $this->getProduct()->isSaleProduct();
    }
}
