[{assign var="bHasSelectionList" value=false}]
[{if $oViewConf->showSelectLists()}]
    [{assign var="oSelections" value=$oDetailsProduct->getSelections()}]
    [{if $oSelections}]
        [{assign var="bHasSelectionList" value=true}]
    [{/if}]
[{/if}]


        [{if $oDetailsProduct->getFPrice()}]
        [{assign var="sFrom" value=""}]
        [{assign var="fPrice" value=$oDetailsProduct->getFPrice()}]
        [{if $oDetailsProduct->isParentNotBuyable()}]
        [{assign var="fPrice" value=$oDetailsProduct->getFVarMinPrice()}]
        [{if $oDetailsProduct->isRangePrice()}]
        [{assign var="sFrom" value="PRICE_FROM"|oxmultilangassign}]
        [{/if}]
        [{/if}]
        <label id="productPrice" class="price"
               data-original-price="[{$fPrice}]"
               data-decimalsseparator="[{if $oCurrency->dec}][{$oCurrency->dec}][{else}],[{/if}]"
               data-thousandseparator="[{if $oCurrency->thousand}][{$oCurrency->thousand}][{else}].[{/if}]">
            [{block name="details_productmain_tprice"}]
                [{oxhasrights ident="SHOWARTICLEPRICE"}]
                    [{if $tprice}]
                        <del class="price-old" data-original-price="[{$tprice->getBruttoPrice()}]"><span>[{$tprice->getBruttoPrice()}]</span> [{$currency->sign}]</del>
                    [{/if}]
                [{/oxhasrights}]
            [{/block}]
            <span class="one-item-price [{if $tprice && $tprice->getBruttoPrice() > $price->getBruttoPrice()}] text-danger[{/if}]">
            <span class="price-from">[{$sFrom}]</span>
            <span class="price">[{$fPrice}]</span>
            <span class="currency">[{$currency->sign}]</span>
            [{if $oView->isVatIncluded()}]
            <span class="price-markup">*</span>
            [{/if}]
            <span class="hidden">
                <span itemprop="price">[{$fPrice}] [{$currency->sign}]</span>
            </span>
            </span>
            <div class="all-items-price">
                <div class="without-discount">
                    <span class="all-items-price__price-from">[{$sFrom}]</span>
                    <span class="all-items-price__price"></span>
                    <span class="all-items-price__currency">[{$currency->sign}]</span>
                    [{if $oView->isVatIncluded()}]
                    <span class="all-items-price__price-markup">*</span>
                    [{/if}]
                </div>
                <div class="with-discount">
                    <span class="all-items-price__price-from">[{$sFrom}]</span>
                    <span class="all-items-price__price"></span>
                    <span class="all-items-price__currency">[{$currency->sign}]</span>
                    [{if $oView->isVatIncluded()}]
                    <span class="all-items-price__price-markup">*</span>
                    [{/if}]
                </div>
                <div class="saving-notice">
                    <b>[{oxmultilang ident="JXPYSIOXPRESSTHEME_SAVING"}]:</b>
                    <span class="saving-notice__price"></span>
                    <span class="saving-notice__currency">[{$currency->sign}]</span>
                </div>
            </div>
        </label>
        [{/if}]
 
        [{if $oDetailsProduct->loadAmountPriceInfo()}]
        [{assign var="currency" value=$oView->getActCurrency()}]
        <div class="discount-prices">
            <div class="discount-prices-header">
                <span>[{oxmultilang ident="QUANTITY"}]</span><span>
                [{oxmultilang ident="DISCOUNT"}]</span><span>
                [{oxmultilang ident="PRICE"}]</span>
            </div>
            <div class="discount-prices-body">
                [{foreach from=$oDetailsProduct->loadAmountPriceInfo() item=priceItem name=amountPrice}]
                <div class="discount-row">
                    [{assign var="discountUnitLabel" value=$oDetailsProduct->getDiscountUnitLabel()}]
                    <span class="discount-qty" data-qty=[{$priceItem->oxprice2article__oxamount->value}]>[{oxmultilang ident="PRICE_FROM"}] [{$priceItem->oxprice2article__oxamount->value}] [{if $discountUnitLabel}][{$discountUnitLabel}][{else}][{oxmultilang ident="_UNIT_PIECE"}][{/if}]</span>
					
					
					
					<span class="discount-value">
                    [{if $priceItem->oxprice2article__oxaddperc->value}]
                        <i class="discount-data" data-discount-value="[{$priceItem->oxprice2article__oxaddperc->value}]" data-discount-type="perc"></i>
                        [{$priceItem->oxprice2article__oxaddperc->value|number_format:2}]%
                    [{else}]
                        <i class="discount-data" data-discount-value="[{$priceItem->fbrutprice}]" data-dicount-type="abs"></i>
                        [{block name="details_productmain_price"}]
                        [{$priceItem->fbrutprice}] [{$currency->sign}]
                        [{if $oDetailsProduct->getUnitName() and $priceItem->fbrutamountprice}]
                            ([{$priceItem->fbrutamountprice}] [{$currency->sign}] / [{$oDetailsProduct->getUnitName()}])
                        [{/if}]
                        [{/block}]
                    [{/if}]
                </span><span class="discount-final-price">
                    <span class="price-val"></span> [{$currency->sign}]
                </span>
                </div>
                [{/foreach}]
            </div>
        </div>
        [{/if}]
		[{if $oDetailsProduct->oxarticles__jxpackamount->value}]
        <div id="pack-amount-indicator" style="display: none;" data-amount="[{$oDetailsProduct->oxarticles__jxpackamount->value}]" data-pack-label="[{oxmultilang ident="_UNIT_PIECE"}]"></div>
        [{/if}]
[{oxscript add="
    $(function () {
        if (window.updateDiscountPriceBlock) {
            window.updateDiscountPriceBlock();
        }
    });
"}]