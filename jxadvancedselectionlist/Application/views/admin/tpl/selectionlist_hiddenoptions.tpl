[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="jxadvancedselectionlist_hiddenoptions">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="selectionlist" id="selectionlist" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="jxadvancedselectionlist_hiddenoptions">
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
    <hr/>
    <h3>[{oxmultilang ident="JXADVANCEDSELECTIONLIST_HIDDEN_OPTIONS"}]</h3>
    [{if $bUseBilateralRelations}]
        <div>[{oxmultilang ident="JXADVANCEDSELECTIONLIST_HIDDEN_OPTIONS_HELP"}]</div><br/><br/>
    [{else}]
        <div>[{oxmultilang ident="JXADVANCEDSELECTIONLIST_HIDDEN_OPTIONS_HELP_MINI"}]</div><br/><br/>
    [{/if}]
    [{if $bUseBilateralRelations && $aLockedHiddenOptionRelations}]
        <div class="error">[{oxmultilang ident="JXADVANCEDSELECTIONLIST_HIDDEN_OPTIONS_LOCKES_FOUND"}]</div><br/><br/>
    [{/if}]
    <form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post" enctype="multipart/form-data" style="display:block;margin-bottom: 20px;
    border-bottom: 2px solid #ccc;border-top: 2px solid #ccc;background-color: #eaeaea;padding: 10px 5px;">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="jxadvancedselectionlist_hiddenoptions">
        <input type="hidden" name="fnc" value="save">
        <input type="hidden" name="oxid" value="[{$oxid}]">
        <input type="hidden" name="option" value="[{$oSelectedOption->getOptionId()}]">
        <input type="hidden" name="jxadvancedselectionlistoptions__oxid" value="[{$oSelectedOption->jxadvancedselectionlistoptions__oxid->value}]">

        <table style="width:100%;table-layout: fixed;">
            <tr>
                [{section name=iteration loop=4}]
                [{assign var="i" value=$smarty.section.iteration.index}]
                <td>
                    <select name="selectionlist[[{$i}]]" style="vertical-align: top; max-width: 250px;width: 100%;">
                        <option value="">-</option>
                        [{foreach from=$aAllSelectionListOptions item=aSelectionListData key=sSelectionId}]
                        <option value="[{$sSelectionId}]" [{if $aRelatedSelection.$i == $sSelectionId}]selected[{/if}]>[{$aSelectionListData.title}]</option>
                        [{/foreach}]
                    </select>
                </td>
                [{/section}]
            </tr>
            <tr>
                [{section name=iteration loop=4}]
                [{assign var="i" value=$smarty.section.iteration.index}]
                <td>
                    [{assign var="sSelectionId" value=$aRelatedSelection.$i}]
                    [{if $aLockedHiddenOptionRelations.$sSelectionId}]
                    [{else}]
                        <input type="hidden" name="selectionlistoptions[[{$i}]]" value="">
                    [{/if}]
                    <select [{if $aLockedHiddenOptionRelations.$sSelectionId}]disabled[{/if}] name="selectionlistoptions[[{$i}]][]" style="vertical-align: top; max-width: 250px;width: 100%;" multiple>
                        [{foreach from=$aAllSelectionListOptions.$sSelectionId.options item=oOption key=sOptionId}]
                        <option value="[{$sOptionId}]" [{if $aAllHiddenOptionRelations.$sSelectionId.$sOptionId}]selected[{/if}]>[{$oOption->name}], [{$oOption->price}] [{$oOption->priceUnit}]</option>
                        [{/foreach}]
                    </select>
                </td>
                [{/section}]
            </tr>
        </table>
        <input type="submit" value="[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SAVE"}]" class="edittext" style="vertical-align: top;">
    </form>

[{/if}]

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
