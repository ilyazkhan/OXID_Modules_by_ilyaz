<?php

namespace JidexStudio\JxAdvancedCheckout\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * Class BasketItem
 * @package JidexStudio\JxAdvancedCheckout\Application\Model
 */
class BasketItem extends BasketItem_parent
{
    /**
     * @return float
     */
    public function isLastAddedItem()
    {
        /** @var \OxidEsales\Eshop\Application\Model\BasketItem $lastAddedBasketItem */
        $lastAddedBasketItem = Registry::getSession()->getVariable('lastAddedBasketItem');

        return $lastAddedBasketItem->getProductId() == $this->getProductId()
            && $lastAddedBasketItem->getTitle() == $this->getTitle()
            && $lastAddedBasketItem->getVarSelect() == $this->getVarSelect()
            && $lastAddedBasketItem->getChosenSelList() == $this->getChosenSelList();
    }
}
