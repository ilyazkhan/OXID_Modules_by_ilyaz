[{$smarty.block.parent}]

[{assign var=isMinibasketAccessory value=$oView->getViewParameter('isMinibasketAccessory')}]
[{assign var=defaultAmountInputValue value=$oView->getViewParameter('defaultAmountInputValue')}]

[{if $isMinibasketAccessory}]
    [{if $product->isNotBuyable() || ($aVariantSelections&&$aVariantSelections.selections) || $product->hasMdVariants() || ($oViewConf->showSelectListsInList() && $product->getSelections(1)) || $product->getVariants()}]
        <a class="more-info" href="[{$_productLink}]">
            <button class="btn btn-primary" title="[{oxmultilang ident="MORE_INFO"}]">
                [{oxmultilang ident="MORE_INFO"}]
            </button>
        </a>
    [{else}]
        [{oxhasrights ident="TOBASKET"}]
        <input type="hidden" name="cl" value="[{$oViewConf->getTopActiveClassName()}]">
        <input type="hidden" name="fnc" value="tobasket">
        <input type="hidden" name="aid" value="[{$product->oxarticles__oxid->value}]">
            [{if $altproduct}]
        <input type="hidden" name="anid" value="[{$altproduct}]">
            [{else}]
        <input type="hidden" name="anid" value="[{$product->oxarticles__oxnid->value}]">
            [{/if}]
        <div class="form-group">
            <input type="number" name="am" value="[{$defaultAmountInputValue}]" size="3" autocomplete="off" class="form-control amount">
        </div>
        <button type="submit" class="btn btn-primary hasTooltip" title="[{oxmultilang ident="TO_CART"}]">
            [{oxmultilang ident="TO_CART"}]
        </button>
        [{/oxhasrights}]
    [{/if}]
[{/if}]