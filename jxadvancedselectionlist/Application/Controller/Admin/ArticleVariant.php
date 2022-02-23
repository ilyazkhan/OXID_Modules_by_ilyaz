<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin;

use OxidEsales\Eshop\Application\Model\Article;
use JidexStudio\JxAdvancedSelectionList\Application\Service\ArticleService;

/**
 * Admin article variants manager.
 * Collects and updates article variants data.
 * Admin Menu: Manage Products -> Articles -> Variants.
 */
class ArticleVariant extends ArticleVariant_parent
{
    /**
     * Changes name of variant.
     */
    public function changename()
    {
        parent::changename();

        $soxId = $this->getEditObjectId();

        /** @var Article $oArticle */
        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        if ($soxId != "-1") {
            $oArticle->loadInLang($this->_iEditLang, $soxId);
        }

        /** @var ArticleService $articleService */
        $articleService = oxNew(ArticleService::class, $this->getConfig());
        $articleService->shareDiscountsBetweenVariants($oArticle);
    }
}
