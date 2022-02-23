[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="jxadvancedselectionlist_options">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="selectionlist" id="selectionlist" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="jxadvancedselectionlist_options">
    <input type="hidden" name="fnc" value="selectOption">

    <select name="option" style="vertical-align: top;">
        <option value="">-</option>
        [{foreach from=$aOptions item=oOption key=sOptionId}]
        <option value="[{$sOptionId}]" [{if $oSelectedOption && $oSelectedOption->getOptionId() eq $sOptionId}]selected[{/if}]>[{$oOption->name}], [{$oOption->price}] [{$oOption->priceUnit}]</option>
        [{/foreach}]
    </select>
    <input type="submit" value="[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SELECT"}]" class="edittext" style="vertical-align: top;">
</form>
<br/>
[{if $errorMessage}]
    <div class="error">[{$errorMessage}]</div><br/>
[{/if}]

[{if $oSelectedOption}]
<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post" enctype="multipart/form-data">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="jxadvancedselectionlist_options">
    <input type="hidden" name="fnc" value="save">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="option" value="[{$oSelectedOption->getOptionId()}]">
    <input type="hidden" name="jxadvancedselectionlistoptions__oxid" value="[{$oSelectedOption->jxadvancedselectionlistoptions__oxid->value}]">

    <table cellspacing="0" cellpadding="0" border="0" width="98%">
        <tr>
            <td valign="top" class="edittext" style="min-width: 150px">
                [{oxmultilang ident="JXADVANCEDSELECTIONLIST_DESCRIPTION"}]
            </td>
            <td valign="top" class="edittext" colspan="2">
                [{$editor}]
            </td>
        </tr>
        <tr>
            <td valign="top" class="edittext">
                [{oxmultilang ident="JXADVANCEDSELECTIONLIST_ICON"}]
            </td>
            <td valign="top" class="edittext">
                <input type="hidden" id="option-icon-input" name="jxadvancedselectionlistoptions__icon" value="[{$icon.id}]">
                <input type="file" name="icon"><br/>
            </td>
            <td valign="top" class="edittext" id="option-icon">
                [{if $icon.path}]
                    <a href="#" class="delete" style="float:left" onclick="document.getElementById('option-icon').innerHTML='';document.getElementById('option-icon-input').value='';return false"></a>
                    <img src="/[{$icon.path}]" width="100">
                [{/if}]
            </td>
        </tr>
        <tr>
            <td valign="top" class="edittext">
                [{oxmultilang ident="JXADVANCEDSELECTIONLIST_IMAGE"}]
            </td>
            <td valign="top" class="edittext">
                <input type="hidden" id="option-image-input" name="jxadvancedselectionlistoptions__image" value="[{$image.id}]">
                <input type="file" name="image"><br/>
            </td>
            <td valign="top" class="edittext" id="option-image">
                [{if $image.path}]
                    <a href="#" class="delete" style="float:left" onclick="document.getElementById('option-image').innerHTML='';document.getElementById('option-image-input').value='';return false"></a>
                    <img src="/[{$image.path}]" width="400">
                [{/if}]
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <input type="submit" value="[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SAVE"}]" class="edittext">
            </td>
        </tr>
    </table>
</form>
[{/if}]

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
