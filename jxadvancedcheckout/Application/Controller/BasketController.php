<?php

namespace JidexStudio\JxAdvancedCheckout\Application\Controller;

use JidexStudio\JxAdvancedSelectionList\Application\Model\SelectionListOption;
use OxidEsales\Eshop\Application\Controller\Admin\AdminDetailsController;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\ArticleList;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class BasketController
 * @package JidexStudio\JxAdvancedCheckout\Application\Controller
 */
class BasketController extends BasketController_parent
{
    /**
     * @var array
     */
    protected $advancedCheckoutTrendingProducts;

    /**
     * @var array
     */
    protected $advancedCheckoutBasketProductsIds;

    /**
     * Return trending product
     *
     * @return mixed|null
     */
    public function getAdvancedCheckoutTrendingProduct()
    {
        if (null === $this->advancedCheckoutTrendingProducts) {
            /** @var ArticleList $oArticlesList */
            $oArticlesList = oxNew(ArticleList::class);
            $oArticlesList->loadActionArticles('jxadvancedcheckouttrendproduct', 10);
            $this->advancedCheckoutTrendingProducts = $oArticlesList->count() ? $oArticlesList : [];
        }

        if (null === $this->advancedCheckoutBasketProductsIds) {
            $this->advancedCheckoutBasketProductsIds = [];
            $aBasketArticles = $this->getBasketArticles();

            foreach($aBasketArticles as $oBasketArticle) {
                $this->advancedCheckoutBasketProductsIds[] = $oBasketArticle->getId();
            }
        }

        foreach ($this->advancedCheckoutTrendingProducts as $product) {
            if (!in_array($product->getid(), $this->advancedCheckoutBasketProductsIds)) {
                return $product;
            }
        }

        // Load random
        $oArticlesList = oxNew(ArticleList::class);
        $oBaseObject = $oArticlesList->getBaseObject();
        $sArticleTable = $oBaseObject->getViewName();
        $sArticleFields = $oBaseObject->getSelectFields();
        $sActiveSql = $oBaseObject->getSqlActiveSnippet();

        $sSelect = "select $sArticleFields from $sArticleTable 
                    where $sActiveSql and $sArticleTable.oxid is not null 
                    order by RAND() LIMIT 1";

        $oArticlesList->selectString($sSelect);

        return $oArticlesList->count() ? $oArticlesList->current() : null;
    }
}
