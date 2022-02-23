<?php

namespace JidexStudio\JxAdvancedSelectionList\Application\Service;

use OxidEsales\Eshop\Application\Model\AmountPriceList;

/**
 * Class ArticleService
 * @package JidexStudio\JxAdvancedSelectionList\Application\Service
 */
class ArticleService
{
    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Changes name of variant.
     */
    public function shareDiscountsBetweenVariants($oArticle)
    {
        if ($oArticle->oxarticles__jxadvancedselectionlistsharediscounts->value) {
            $groupedVariants = [];
            $variants = $oArticle->getVariants();

            if ($variants) {
                foreach ($variants as $variant) {
                    if ($variant->oxarticles__oxartnum->value) {
                        $groupedVariants[$variant->oxarticles__oxartnum->value][] = $variant;
                    }
                }
            }

            foreach ($groupedVariants as $artNum => $variants) {
                $firstFoundVariantWithDiscounts = null;

                foreach ($variants as $variant) {
                    /** @var AmountPriceList $oAmPriceList */
                    $oAmPriceList = oxNew(\OxidEsales\Eshop\Application\Model\AmountPriceList::class);
                    $oAmPriceList->load($variant);
                    if ($oAmPriceList->count()) {
                        $firstFoundVariantWithDiscounts = $oAmPriceList;
                        break;
                    }
                }

                if ($firstFoundVariantWithDiscounts) {
                    foreach ($variants as $variant) {
                        $this->deletePricesForVariant($variant->getId());
                        $this->addPricesForVariant($variant->getId(), $firstFoundVariantWithDiscounts);
                    }
                }
            }
        }
    }

    /**
     * Adds amount price to article
     */
    protected function deletePricesForVariant($oxid)
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $oDb->execute("delete from oxprice2article where oxartid = :oxartid and oxshopid = :oxshopid", [
            ':oxartid' => $oxid,
            ':oxshopid' => $this->config->getShopID()
        ]);
    }

    /**
     * Adds amount price to article
     */
    protected function addPricesForVariant($oxid, $discounts)
    {
        foreach ($discounts as $discount) {
            $aParams = [];

            $aParams['oxprice2article__oxshopid'] = $this->config->getShopID();
            $aParams['oxprice2article__oxartid'] = $oxid;
            $aParams['oxprice2article__oxamount'] = $discount->oxprice2article__oxamount->value;
            $aParams['oxprice2article__oxamountto'] = $discount->oxprice2article__oxamountto->value;
            $sType = !empty($discount->oxprice2article__oxaddabs->value) ? 'oxprice2article__oxaddabs' : 'oxprice2article__oxaddperc';
            $dPrice = $discount->$sType->value;

            $oArticlePrice = oxNew(\OxidEsales\Eshop\Core\Model\BaseModel::class);
            $oArticlePrice->init("oxprice2article");
            $oArticlePrice->assign($aParams);

            $oArticlePrice->$sType = new \OxidEsales\Eshop\Core\Field($dPrice);

            $oArticlePrice->save();
        }
    }
}
