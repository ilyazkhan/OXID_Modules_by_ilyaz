[{if $oUnitPrice}]
    <span id="productPriceUnit" class="jx-price-per-unit" data-unit-quantity="[{$oDetailsProduct->getUnitQuantity()}]"><span class="unit-price">[{oxprice price=$oUnitPrice currency=$currency}]</span> / [{$oDetailsProduct->getUnitName()}]</span>
[{/if}]

[{assign var="currency" value=$oView->getActCurrency()}]
[{if $oDetailsProduct->getFPrice()}]
    [{assign var="fPrice" value=$oDetailsProduct->getFPrice()}]
    [{if $oDetailsProduct->isParentNotBuyable()}]
    [{assign var="fPrice" value=$oDetailsProduct->getFVarMinPrice()}]
    [{/if}]
[{/if}]
<div class="jx-price-per-pack"><span class="unit-price">
        [{assign var="discountUnitLabel_main" value=$oDetailsProduct->getDiscountUnitLabel()}]
        [{$fPrice}]</span> [{$currency->sign}] / [{if $discountUnitLabel_main}][{$discountUnitLabel_main}]
[{else}][{oxmultilang ident="_UNIT_PIECE"}][{/if}]</div>