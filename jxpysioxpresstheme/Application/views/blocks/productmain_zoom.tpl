[{assign var="bHasSelectionList" value=false}]
[{assign var="bHasVarinatsList" value=false}]
[{if $oViewConf->showSelectLists()}]
    [{assign var="oSelections" value=$oDetailsProduct->getSelections()}]
    [{if $oSelections}]
    [{assign var="bHasSelectionList" value=true}]
    [{/if}]
[{/if}]
[{if $oView->getVariantSelections()}]
    [{assign var="bHasVarinatsList" value=true}]
[{/if}]

[{if $bHasSelectionList || $bHasVarinatsList}]
[{oxscript add="
    // DOM structure change if selection list is available (it is quite hard to implement it in template only so will do it by js)
    if ($('.cl-details').length && $('#product-tobasket-alternative-box').length) {
        $('#productPrice').detach().appendTo('#product-tobasket-alternative-box');
        $('#priceunitrene').detach().appendTo('#product-tobasket-alternative-box');
        $('.jx-current-selection-combination-desc').detach().appendTo('#product-tobasket-alternative-box');
        $('#details .tobasket').detach().appendTo('#product-tobasket-alternative-box');
        $('#morePicsContainer').detach().prependTo('.product-sticky-container');
    }
"
}]
    <div class="product-sticky-container">
[{/if}]

[{assign var="newProducts" value=''}]
[{assign var="setProducts" value=''}]
[{assign var="isNewProduct" value=$oDetailsProduct->isNewProduct($newProducts)}]
[{assign var="isSetProduct" value=$oDetailsProduct->isSetProduct($setProducts)}]
[{assign var="isSaleProduct" value=$oDetailsProduct->isSaleProduct()}]
[{assign var="isConfiguratorProduct" value=$oDetailsProduct->isConfiguratorProduct()}]
[{if $isNewProduct || $isSetProduct}]
<div class="badge-wrapper-left">
    [{if $isNewProduct}]<div class="badge badge-new">[{oxmultilang ident="JXPYSIOXPRESSTHEME_BADGE_NEW"}]</div>[{/if}]
    [{if $isSetProduct}]<div class="badge badge-set">[{oxmultilang ident="JXPYSIOXPRESSTHEME_BADGE_SET"}]</div>[{/if}]
</div>
[{/if}]
[{if $isSaleProduct || $isConfiguratorProduct}]
<div class="badge-wrapper-right">
    [{if $isSaleProduct}]<div class="badge badge-sale">[{oxmultilang ident="JXPYSIOXPRESSTHEME_BADGE_SALE"}]</div>[{/if}]
    [{if $isConfiguratorProduct}]<div class="badge badge-configurator">[{oxmultilang ident="JXPYSIOXPRESSTHEME_BADGE_CONFIGURATOR"}]</div>[{/if}]
</div>
[{/if}]
[{oxscript add="
    if ($('.cl-details').length && $('.details-col-left .picture a').length) {
        $('.details-col-left .badge-wrapper-left').detach().appendTo('.details-col-left .picture a');
        $('.details-col-left .badge-wrapper-right').detach().appendTo('.details-col-left .picture a');
    }
"
}]
[{$smarty.block.parent}]

[{if $bHasSelectionList || $bHasVarinatsList}]
    <div id="product-tobasket-alternative-box"></div>
    </div>
[{/if}]