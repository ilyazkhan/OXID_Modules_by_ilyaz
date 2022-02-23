[{$smarty.block.parent}]

[{assign var="oConfig" value=$oViewConf->getConfig()}]
[{assign var="freeDeliveryTotalPrice" value=$oConfig->getConfigParam('jxpysioxpresstheme_checkout_freedelivery_price_total')}]
[{assign var="oPrice" value=$oxcmp_basket->getPrice()}]
[{assign var="fulltotalPrice" value=$oPrice->getPrice()}]
[{assign var="shipPrice" value=$deliveryCost->getBruttoPrice()}]
[{assign var="totalPrice" value=$fulltotalPrice-$shipPrice}]



[{if $freeDeliveryTotalPrice && $freeDeliveryTotalPrice > $totalPrice}]
    [{assign var="difference" value=$freeDeliveryTotalPrice-$totalPrice}]
    
    [{php}]
        $difference = $this->get_template_vars('difference');
        $this->assign('fDifference',\OxidEsales\Eshop\Core\Registry::getLang()->formatCurrency($difference));
    [{/php}]
    <div class="free-delivery-notice">
        <div class="delivery_meter"><meter id="meter_value" value="[{$totalPrice}]" min="0" max="[{$freeDeliveryTotalPrice}]"></meter><br></div>
        <span class="price">[{$fDifference}] [{$currency->sign}]</span> [{oxmultilang ident="JXPYSIOXPRESSTHEME_CHECKOUT_FREE_DELIVERY_NOTICE"}]
    </div>
[{/if}]