<?php

namespace JidexStudio\JxPysioXpressTheme\Application\Controller;

use oxArticle;
use oxArticleList;
use oxCategory;
use oxDeliveryList;
use oxDeliverySetList;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\MailValidator;
use OxidEsales\Eshop\Core\Registry;
use oxPaymentList;
use oxRegistry;
use oxField;
use oxVariantSelectList;

/**
 * Class ArticleDetailsController
 * @package JidexStudio\JxPysioXpressTheme\Application\Controller
 */
class ArticleDetailsController extends ArticleDetailsController_parent
{
    /**
     * Saves user ratings and review text (oxReview object)
     *
     * @return null
     */
    public function saveReview()
    {
        parent::saveReview();

        if (!Registry::getSession()->checkSessionChallenge()) {
            return;
        }

        if (
            $this->canAcceptFormData() &&
            ($user = $this->getUser()) && ($article = $this->getProduct())
        ) {
            $articleRating = $this->getConfig()->getRequestParameter('artrating');
            if ($articleRating !== null) {
                $articleRating = (int) $articleRating;
            }

            $reviewText = trim((string) $this->getConfig()->getRequestParameter('rvw_txt', true));

            // If review is empty, save review in anyway
            if (empty($reviewText)) {
                $review = oxNew(\OxidEsales\Eshop\Application\Model\Review::class);
                $review->oxreviews__oxobjectid = new Field($article->getId());
                $review->oxreviews__oxtype = new Field('oxarticle');
                $review->oxreviews__oxtext = new Field('', Field::T_RAW);
                $review->oxreviews__oxlang = new Field(Registry::getLang()->getBaseLanguage());
                $review->oxreviews__oxuserid = new Field($user->getId());
                $review->oxreviews__oxrating = new Field(($articleRating !== null) ? $articleRating : 0);
                $review->save();
            }
        }
    }
}
