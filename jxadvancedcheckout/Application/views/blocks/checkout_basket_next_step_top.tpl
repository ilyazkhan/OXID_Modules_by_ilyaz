[{$smarty.block.parent}]

[{assign var="_product" value=$oView->getAdvancedCheckoutTrendingProduct()}]

[{if $_product}]
<div class="trending-product">
    <div class="trending-product-header"><span>[{oxmultilang ident="JXADVANCEDCHECKOUT_OUR_TIP"}]</span></div>
    [{oxid_include_widget cl="oxwArticleBox" isMinibasketAccessory=1 defaultAmountInputValue=1 _parent=$oView->getClassName() nocookie=1 _navurlparams=$oViewConf->getNavUrlParams() iLinkType=$_product->getLinkType() _object=$_product anid=$_product->getId() sWidgetType=product sListType=listitem_grid iIndex=$testid blDisableToCart=1 isVatIncluded=$oView->isVatIncluded() showMainLink=$showMainLink recommid=$recommid owishid=$owishid toBasketFunction=$toBasketFunction removeFunction=$removeFunction altproduct=$altproduct inlist=$_product->isInList() skipESIforUser=1 testid=$testid}]
</div>
[{/if}]