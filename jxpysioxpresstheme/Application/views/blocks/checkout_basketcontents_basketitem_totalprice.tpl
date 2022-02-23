[{* product quantity * price *}]
<td class="totalPrice">
    [{assign var="priceWithoutDiscount" value=$basketitem->getDetailsPageCalculatedPriceWithoutDiscount()}]
    [{assign var="summPriceWithoutDiscount" value=$priceWithoutDiscount*$basketitem->getAmount()}]
    [{assign var="oPrice" value=$basketitem->getPrice()}]
    [{assign var="summTotalPrice" value=$oPrice->getPrice()}]
    [{math equation="x - y" x=$summPriceWithoutDiscount y=$summTotalPrice assign="priceSaving" format="%.2f"}]
    [{*oxprice price=$basketitem->getPrice() currency=$currency*}]
	
	[{assign var="oArticle"   value=$basketitem->getArticle()}]
    [{assign var="oUnitPrice" value=$oArticle->getUnitPrice()}]
    [{assign var="tprice"     value=$oArticle->getTPrice()}]
    [{assign var="price"      value=$oArticle->getPrice()}]
	[{if $tprice && $tprice->getBruttoPrice() > $price->getBruttoPrice()}]
    [{assign var="tpricebrutto" value=$tprice->getBruttoPrice()}]
    [{assign var="tpricesumm" value=$tpricebrutto*$basketitem->getAmount()}]
    <span class="oldPrice text-muted">
        <del>[{$basketitem->formatPrice($tpricesumm)}] [{$currency->sign}]</del>
    </span>
    <br/>
    [{else}]
        [{assign var="priceWithoutDiscount" value=$basketitem->getDetailsPageCalculatedPriceWithoutDiscount()}]
        [{assign var="summPriceWithoutDiscount" value=$priceWithoutDiscount*$basketitem->getAmount()}]
        [{assign var="oPrice" value=$basketitem->getPrice()}]
        [{assign var="summTotalPrice" value=$oPrice->getPrice()}]
        [{math equation="x - y" x=$summPriceWithoutDiscount y=$summTotalPrice assign="priceSaving" format="%.2f"}]
        
    [{/if}]

    [{if $basketitem->getUnitPrice()}]
    [{assign var="oPrice" value=$basketitem->getPrice()}]
    [{assign var="summTotalPrice" value=$oPrice->getPrice()}]
    [{$basketitem->formatPrice($summTotalPrice)}] [{$currency->sign}]
    [{/if}]
	
    [{if $priceSaving && $priceSaving gt 0}]
    <div class="saving-notice">
        <b>[{oxmultilang ident="JXPYSIOXPRESSTHEME_SAVING"}]:</b>
        <span class="saving-notice__price">[{$priceSaving}]</span>
        <span class="saving-notice__currency">[{$currency->sign}]</span>
    </div>
    [{/if}]
</td>