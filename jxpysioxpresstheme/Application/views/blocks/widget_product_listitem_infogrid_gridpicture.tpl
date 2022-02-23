[{assign var="hoverImg" value=$product->getPictureUrl(2)}]
[{assign var="hasHoverImg" value=1}]
[{if $hoverImg|strstr:"nopic.jpg"}]
    [{assign var="hasHoverImg" value=0}]
[{/if}]

[{assign var="isNewProduct" value=$oView->isNewProduct()}]
[{assign var="isSetProduct" value=$oView->isSetProduct()}]
[{assign var="isSaleProduct" value=$oView->isSaleProduct()}]
[{assign var="isConfiguratorProduct" value=$oView->isConfiguratorProduct()}]


<div class="picture text-center list-product-pictures-wrapper">
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
    <img src="[{$oViewConf->getImageUrl('spinner.gif')}]" data-src="[{$product->getThumbnailUrl()}]" alt="[{$product->oxarticles__oxtitle->value}] [{$product->oxarticles__oxvarselect->value}]" class="img-responsive [{if $hasHoverImg}]with-hover-img[{/if}]">
    [{if $hasHoverImg}]
        <div class="img-responsive list-product-hover-img">
            <div style="background-image: url('[{$hoverImg}]')"></div>
        </div>
    [{/if}]
</div>