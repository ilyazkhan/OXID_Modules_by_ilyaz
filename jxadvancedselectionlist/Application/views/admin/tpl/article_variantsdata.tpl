[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]
[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{else}]
    [{assign var="readonly" value=""}]
    [{/if}]

<script type="text/javascript">
    <!--
    window.onload = function ()
    {
        [{if $updatelist == 1}]
        top.oxid.admin.updateList('[{$oxid}]');
        [{/if}]
        top.reloadEditFrame();
    }
    function editThis( sID )
    {
        var oTransfer = top.basefrm.edit.document.getElementById( "transfer" );
        oTransfer.oxid.value = sID;
        oTransfer.cl.value = top.basefrm.list.sDefClass;

        //forcing edit frame to reload after submit
        top.forceReloadingEditFrame();

        var oSearch = top.basefrm.list.document.getElementById( "search" );
        oSearch.oxid.value = sID;
        oSearch.actedit.value = 0;
        oSearch.submit();
    }
    function processUnitInput( oSelect, sInputId )
    {
        document.getElementById( sInputId ).disabled = oSelect.value ? true : false;
    }
    //-->
</script>

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="jxadvancedselectionlist_variantsdata">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>
<br/>
[{if $errorMessage}]
    <div class="error">[{$errorMessage}]</div><br/>
[{/if}]

[{if $aVariantsData}]
    <h2>[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SELECTION_OPTIONS_DATA"}]</h2>
    <form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" enctype="multipart/form-data" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="[{$iMaxUploadFileSize}]">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="jxadvancedselectionlist_variantsdata">
        <input type="hidden" name="fnc" value="save">
        <input type="hidden" name="oxid" value="[{$oxid}]">
        <input type="hidden" name="voxid" value="[{$oxid}]">
        <input type="hidden" name="oxparentid" value="[{$oxparentid}]">
        <input type="hidden" name="editval[article__oxid]" value="[{$oxid}]">

        <table cellspacing="0" cellpadding="5" border="1" height="100%" width="100%">
            <tr height="10">
                <th>[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SELECTION_OPTION_TITLE"}]</th>
                <th></th>
                <th>[{oxmultilang ident="JXADVANCEDSELECTIONLIST_ICON"}]</th>
            </tr>
            [{foreach from=$aVariantsData.selectionOptions item=aOptionData key=sOptionKey}]
            <tr>
                <td colspan="">
                    [{$aOptionData.title}]
                    <input type="hidden" name="variantsdata[selectionOptions][[{$sOptionKey}]][title]" value="[{$aOptionData.title}]">
                </td>
                <td colspan="">
                    <input type="hidden" id="option-image-input-[{$sOptionKey}]" name="variantsdata[selectionOptions][[{$sOptionKey}]][icon]" value="[{$aOptionData.icon}]">
                    <input type="file" name="variantsdataoptionicons_[{$sOptionKey}]">
                </td>
                <td colspan="" class="edittext" id="option-image-[{$sOptionKey}]" style="text-align: center;">
                    [{if $aOptionData.icon}]
                        <img src="/[{$sImgDir}]/[{$aOptionData.icon}]" style="max-height:100px; max-width:100px; width:100%; vertical-align:middle;">
                        <a href="#" class="delete" style="float:none; display:inline-block; vertical-align:middle;" onclick="document.getElementById('option-image-[{$sOptionKey}]').innerHTML='';document.getElementById('option-image-input-[{$sOptionKey}]').value='';return false"></a>
                    [{/if}]
                </td>
            </tr>
            [{/foreach}]
        </table>
        <br/>
        <input type="submit" value="[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SAVE"}]" class="edittext" style="vertical-align: top;">
    </form>
[{else}]
    [{oxmultilang ident="JXADVANCEDSELECTIONLIST_VARIANTS_DATA_NOT_AVAILABLE"}]
[{/if}]

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
