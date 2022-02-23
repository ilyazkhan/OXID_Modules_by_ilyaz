<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Controller\Admin;

use JidexStudio\JxAdvancedSelectionList\Application\Service\ArticleService;
use OxidEsales\Eshop\Application\Model\Article;

/**
 * Admin article inventory manager.
 * Collects such information about article as stock quantity, delivery status,
 * stock message, etc; Updates information (on user submit).
 * Admin Menu: Manage Products -> Articles -> Inventory.
 */
class ArticleStock extends ArticleStock_parent
{
    /**
     * Updates all amount prices for article at once
     */
    public function updateprices()
    {
        parent::updateprices();

        $soxId = $this->getEditObjectId();
        /** @var Article $oArticle */
        $oArticle = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $oArticle->loadInLang($this->_iEditLang, $soxId);

        if ($oArticle->isVariant()) {
            $parentId = $oArticle->getParentId();
            /** @var Article $oArticle */
            $oParent = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            $oParent->loadInLang($this->_iEditLang, $parentId);

            /** @var ArticleService $articleService */
            $articleService = oxNew(ArticleService::class, $this->getConfig());
            $articleService->shareDiscountsBetweenVariants($oParent);
        }
    }
}
