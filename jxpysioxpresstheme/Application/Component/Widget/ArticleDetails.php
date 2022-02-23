<?php

namespace JidexStudio\JxPysioXpressTheme\Application\Component\Widget;

use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class ArticleDetails
 * @package JidexStudio\JxPysioXpressTheme\Application\Component\Widget
 */
class ArticleDetails extends ArticleDetails_parent
{
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
     * Template variable getter. Returns rating count.
     *
     * @return integer
     */
    public function getRatingCount()
    {
        /** @var Article $oDetailsProduct */
        $oDetailsProduct = $this->getProduct();

        if ($oDetailsProduct->isVariant()) {
            $oDetailsProduct = $oDetailsProduct->getParentArticle();
        }

        if ($this->_iRatingCnt === null) {
            $this->_iRatingCnt = false;
            if ($this->isReviewActive() && $oDetailsProduct) {
                $blShowVariantsReviews = $this->getConfig()->getConfigParam('blShowVariantReviews');
                $this->_iRatingCnt = $oDetailsProduct->getArticleRatingCount($blShowVariantsReviews);
            }
        }

        return $this->_iRatingCnt;
    }

    /**
     * Template variable getter. Returns reviews of current article.
     * If article is variant - returns reviews of the parent article
     *
     * @return array
     */
    public function getReviews()
    {
        if ($this->_aReviews === null) {
            $this->_aReviews = false;
            if ($this->getConfig()->getConfigParam('bl_perfLoadReviews')) {
                /** @var Article $oDetailsProduct */
                $oDetailsProduct = $this->getProduct();

                if ($oDetailsProduct->isVariant()) {
                    $oDetailsProduct = $oDetailsProduct->getParentArticle();
                }

                $this->_aReviews = $oDetailsProduct->getReviews();
            }
        }

        return $this->_aReviews;
    }
}
