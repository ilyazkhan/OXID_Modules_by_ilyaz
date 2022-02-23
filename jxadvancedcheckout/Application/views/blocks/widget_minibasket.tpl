[{if $_prefix == 'modal'}]
    [{if $oxcmp_basket->getProductsCount()}]
        [{oxhasrights ident="TOBASKET"}]
        [{assign var="currency" value=$oView->getActCurrency()}]
        [{oxscript include="js/libs/jquery.flexslider.min.js" priority=2}]
        [{oxscript include=$oViewConf->getModuleUrl('jxadvancedcheckout','out/src/js/jxadvancedcheckout.js') priority=10}]
        <div class="modal fade basketFlyout" id="basketModal" tabindex="-1" role="dialog" aria-labelledby="basketModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">[{oxmultilang ident="CLOSE"}]</span>
                        </button>
                        <h4 class="modal-title" id="basketModalLabel">[{$oxcmp_basket->getItemsCount()}] [{oxmultilang ident="ITEMS_IN_BASKET"}]</h4>
                    </div>
                    <div class="modal-body">
                        [{if $oxcmp_basket->getProductsCount()}]
                        [{oxhasrights ident="TOBASKET"}]
                        <div id="[{$_prefix}]basketFlyout" class="basketFlyout">
                            <div class="last-added-item">
                                [{foreach from=$oxcmp_basket->getContents() name=miniBasketList item=_product}]
                                    [{if $_product->isLastAddedItem()}]
                                        [{assign var="minibasketItemTitle" value=$_product->getTitle()}]
                                        [{assign var="oArticle" value=$_product->getArticle()}]
                                        [{assign var="oAccessoires" value=$oArticle->getAccessoires()}]
                                        [{assign var="iAccessoiresCount" value=$oAccessoires|@count}]
                                        <div class="item-image">
                                            <a href="[{$_product->getLink()}]" title="[{$minibasketItemTitle|strip_tags}]">
                                                [{*
                                                    we add # in the end of the url to prevent stupid magiczoomplus to proccess our image
                                                    (if we are on detail page and the current basket item is the same as detail product)
                                                *}]
                                                <img src="[{$oArticle->getThumbnailUrl()}]#" alt="[{$minibasketItemTitle|strip_tags}]" onerror="this.src='/out/pflanzenheld/img/nopic.jpg'"/>
                                            </a>
                                        </div>
                                        <div class="item-description">
                                            <div class="title">
                                                <a href="[{$_product->getLink()}]" title="[{$minibasketItemTitle|strip_tags}]">[{$minibasketItemTitle|strip_tags}]</a>
                                            </div>
                                            <div class="artnum">
                                                [{oxmultilang ident="ARTNUM" suffix="COLON"}] [{$oDetailsProduct->oxarticles__oxartnum->value}]
                                            </div>
                                            <div class="price-wrapper">
                                                <span class="amount">[{$_product->getAmount()}] [{oxmultilang ident="_UNIT_PIECE"}]</span>
                                                <span class="price">
                                                    [{assign var="priceWithoutDiscount" value=$_product->getDetailsPageCalculatedPriceWithoutDiscount()}]
                                                    [{assign var="summPriceWithoutDiscount" value=$priceWithoutDiscount*$_product->getAmount()}]
                                                    [{assign var="oPrice" value=$_product->getPrice()}]
                                                    [{assign var="summTotalPrice" value=$oPrice->getPrice()}]
                                                    [{math equation="x - y" x=$summPriceWithoutDiscount y=$summTotalPrice assign="priceSaving" format="%.2f"}]
                                                    [{if $priceSaving}]
                                                        <div class="without-discount">
                                                            <span class="all-items-price__price">[{$_product->getFTotalDetailsPageCalculatedPriceWithoutDiscount()}]</span>
                                                            <span class="all-items-price__currency">[{$currency->sign}]
                                                            [{if $oView->isVatIncluded()}]
                                                                [{if !($oArticle->hasMdVariants() || ($oViewConf->showSelectListsInList() && $oArticle->getSelections(1)) || $oArticle->getVariants())}]*[{/if}]
                                                            [{/if}]
                                                            </span>
                                                        </div>
                                                        <div class="with-discount">
                                                            <span class="all-items-price__price">[{$_product->getFTotalPrice()}]</span>
                                                            <span class="all-items-price__currency">[{$currency->sign}]
                                                            [{if $oView->isVatIncluded()}]
                                                                [{if !($oArticle->hasMdVariants() || ($oViewConf->showSelectListsInList() && $oArticle->getSelections(1)) || $oArticle->getVariants())}]*[{/if}]
                                                            [{/if}]
                                                            </span>
                                                        </div>
                                                        <div class="saving-notice">
                                                            <b>[{oxmultilang ident="JXPYSIOXPRESSTHEME_SAVING"}]:</b>
                                                            <span class="saving-notice__price">[{$priceSaving}]</span>
                                                            <span class="saving-notice__currency">[{$currency->sign}]</span>
                                                        </div>
                                                    [{else}]
                                                        [{$_product->getFTotalPrice()}]&nbsp;[{$currency->sign}]
                                                        [{if $oView->isVatIncluded()}]
                                                            [{if !($oArticle->hasMdVariants() || ($oViewConf->showSelectListsInList() && $oArticle->getSelections(1)) || $oArticle->getVariants())}]*[{/if}]
                                                        [{/if}]
                                                    [{/if}]
                                                </span>
                                            </div>
                                        </div>

                                        [{if $iAccessoiresCount gt 0}]
                                            [{capture append="oxidBlock_basketmodal_accessoires_products"}]
                                                <div class="boxwrapper">
                                                <div class="flexslider">
                                                    <div class="accessoires-block-header">
                                                        <b>[{oxmultilang ident="JXADVANCEDCHECKOUT_ACCESSORIES_HEADER"}]</b> [{$minibasketItemTitle}]
                                                    </div>
                                                    <ul class="slides">
                                                        [{foreach from=$oAccessoires item="_product" name="accessoires"}]
                                                        [{assign var="type" value=$oViewConf->getViewThemeParam('sStartPageListDisplayType')}]
                                                        <li>
                                                            [{oxid_include_widget cl="oxwArticleBox" isMinibasketAccessory=1 defaultAmountInputValue=0 _parent=$oView->getClassName() nocookie=1 _navurlparams=$oViewConf->getNavUrlParams() iLinkType=$_product->getLinkType() _object=$_product anid=$_product->getId() sWidgetType=product sListType=listitem_grid iIndex=$testid blDisableToCart=1 isVatIncluded=$oView->isVatIncluded() showMainLink=$showMainLink recommid=$recommid owishid=$owishid toBasketFunction=$toBasketFunction removeFunction=$removeFunction altproduct=$altproduct inlist=$_product->isInList() skipESIforUser=1 testid=$testid}]
                                                        </li>
                                                        [{/foreach}]
                                                    </ul>
                                                </div>
                                            </div>
                                            [{/capture}]
                                        [{/if}]
                                    [{/if}]
                                [{/foreach}]
                            </div>
                            [{include file="widget/minibasket/countdown.tpl"}]
                            <div class="accessoires-block">
                                [{foreach from=$oxidBlock_basketmodal_accessoires_products item="_block"}]
                                [{$_block}]
                                [{/foreach}]
                            </div>
                        </div>
                        [{/oxhasrights}]
                        [{/if}]
                    </div>
                    <div class="modal-footer">
                        <div class="action-buttons">
                            <button type="button" class="btn btn-default close-btn" data-dismiss="modal">[{oxmultilang ident="DD_MINIBASKET_CONTINUE_SHOPPING"}]</button>
                            <a href="[{oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=basket"}]" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="[{oxmultilang ident="DISPLAY_BASKET"}]">[{oxmultilang ident="DISPLAY_BASKET"}]</a>
                            <a href="[{oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=basket"}]" style="display: none" id="add-accessories-btn" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="[{oxmultilang ident="JXADVANCEDCHECKOUT_ACCESSORIES_SUBMIT"}]">[{oxmultilang ident="JXADVANCEDCHECKOUT_ACCESSORIES_SUBMIT"}]</a>
                        </div><br>
                    </div>
                </div>
            </div>
        </div>
        [{oxscript add="$('#basketModal').modal('show');"}]
        [{/oxhasrights}]
    [{else}]
        [{block name="dd_layout_page_header_icon_menu_minibasket_alert_empty"}]
        <div class="alert alert-info">[{oxmultilang ident="BASKET_EMPTY"}]</div>
        [{/block}]
    [{/if}]
[{else}]
    [{$smarty.block.parent}]
[{/if}]