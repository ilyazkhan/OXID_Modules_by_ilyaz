[{if $aVariantSelections && $aVariantSelections.selections}]
    [{oxstyle include=$oViewConf->getModuleUrl('jxadvancedselectionlist','out/src/css/jxadvancedselectionlist.css')}]
[{/if}]

[{assign var="oParentProduct" value=$oDetailsProduct}]
[{if $oDetailsProduct->isVariant()}]
    [{assign var="oParentProduct" value=$oDetailsProduct->getParentArticle()}]
[{/if}]

[{oxscript add="$(function () {
    $('#variants .dropdown-menu.vardrop a').each(function (i, e) {
        if (!$(this).attr('data-selection-id')) {
            $(this).closest('li').hide();
        }
    });

    $('#variants .selectbox').each(function (i, e) {
        if ($(this).find('input[type=\'hidden\']').first().val()) {
            $('#variants  .selectbox').eq( $('#variants  .selectbox').index( $(this) ) + 1 ).show();
        }
    });

    $('#variants .selectbox a.active').click(function (i, e) {
        $(this).closest('.vardrop').find('a').first().click();
    });

    var iconsDirPath = $('.variants-wrapper').first().attr('data-icons-dir-path');
    var variantsData = $('.variants-wrapper').first().attr('data-variants-data');
    variantsData = JSON.parse(variantsData);

    $('#variants a[data-selection-id]').each(function (i, e) {
        var id = $(this).attr('data-selection-id');
        var optionData = variantsData && variantsData['selectionOptions'] && variantsData['selectionOptions'][id] ? variantsData['selectionOptions'][id] : [];
        var title = null;
        var icon = null;

        if (optionData && optionData.icon) {
            icon = optionData.icon;
            title = optionData.title;

            $(this).append('<img class=\"option-icon\" src=\"/'+iconsDirPath+icon+'\" alt=\"'+title+'\" title=\"'+title+'\" width=\"50\">');
            $(this).addClass('with-icon');
        }
    });

});
"}]
<div class="variants-wrapper [{if $oParentProduct->oxarticles__jxadvancedselectionlistusestepbystepselection->value}]step-by-step-selection-enabled[{/if}]"
     data-icons-dir-path="[{$oParentProduct->getVariantsIconDirPath()}]"
     data-variants-data='[{$oParentProduct->getVariantsAdditionalData()|@json_encode}]'>
    [{$smarty.block.parent}]
</div>