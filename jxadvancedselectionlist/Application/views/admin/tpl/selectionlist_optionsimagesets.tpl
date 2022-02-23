[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{else}]
    [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="jxadvancedselectionlist_optionsimagesets">
    <input type="hidden" name="editlanguage" value="[{$editlanguage}]">
</form>

<form name="selectionlist" id="selectionlist" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="cl" value="jxadvancedselectionlist_optionsimagesets">
    <input type="hidden" name="fnc" value="selectOption">

    <select name="option" style="vertical-align: top;">
        <option value="">-</option>
        [{foreach from=$aOptions item=oOption key=sOptionId}]
        <option value="[{$sOptionId}]" [{if $oSelectedOption && $oSelectedOption->getOptionId() eq $sOptionId}]selected[{/if}]>[{$oOption->name}], [{$oOption->price}] [{$oOption->priceUnit}]</option>
        [{/foreach}]
    </select>
    <select name="productid" style="vertical-align: top;">
        <option value="">[{oxmultilang ident="JXADVANCEDSELECTIONLIST_ALL_PRODUCTS"}]</option>
        [{foreach from=$aArticleListItems item=aArticleItem}]
            <option value="[{$aArticleItem.oxid}]" [{if $sSelectedArticleOxid eq $aArticleItem.oxid}]selected[{/if}]>[{$aArticleItem.title}]</option>
        [{/foreach}]
    </select>
    <input type="submit" value="[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SELECT"}]" class="edittext" style="vertical-align: top;">
</form>
<br/>
[{if $errorMessage}]
    <div class="error">[{$errorMessage}]</div><br/>
[{/if}]

[{if $oSelectedOption}]
    [{assign var="selectionListOxid" value=$oSelectedOption->getSelectionListOxid()}]
    <hr/>
    <button class="saveButton" onclick="document.getElementById('add-new-image-block').style.display='block';">[{oxmultilang ident="JXADVANCEDSELECTIONLIST_ADD_NEW_IMAGE"}]</button>
    <div id="add-new-image-block" style="display: none">
        <form name="addnewimagesetitem" id="addnewimagesetitem" action="[{$oViewConf->getSelfLink()}]" method="post" enctype="multipart/form-data">
            [{$oViewConf->getHiddenSid()}]
            <input type="hidden" name="oxid" value="[{$oxid}]">
            <input type="hidden" name="option" value="[{$oSelectedOption->getOptionId()}]">
            <input type="hidden" name="cl" value="jxadvancedselectionlist_optionsimagesets">
            <input type="hidden" name="productid" value="[{$sSelectedArticleOxid}]">
            <input type="hidden" name="fnc" value="addNewItem">

            <table style="width:100%;table-layout: fixed;">
                <tr>
                    [{section name=iteration loop=8}]
                    [{assign var="i" value=$smarty.section.iteration.iteration}]
                    <td>
                        <select name="selectionlistoption_[{$i}]" style="vertical-align: top; max-width: 250px;width: 100%;">
                            [{foreach from=$aAllSelectionListOptions item=aSelectionListData key=sSelectionId}]
                            [{if $i == 1}]
                                [{if $sSelectionId == $oxid}]
                                    [{foreach from=$aSelectionListData.options item=oOption key=sOptionId}]
                                    [{if $oSelectedOption->getOptionId() == $sOptionId}]
                                    <option value="[{$sSelectionId}]|||[{$sOptionId}]" >[{$oOption->name}], [{$oOption->price}] [{$oOption->priceUnit}]</option>
                                    [{/if}]
                                    [{/foreach}]
                                [{/if}]
                            [{else}]
                            <option value="">-</option>
                            <optgroup label="[{$aSelectionListData.title}]">
                                [{foreach from=$aSelectionListData.options item=oOption key=sOptionId}]
                                [{if $oSelectedOption->getOptionId() != $sOptionId}]
                                <option value="[{$sSelectionId}]|||[{$sOptionId}]" >[{$oOption->name}], [{$oOption->price}] [{$oOption->priceUnit}]</option>
                                [{/if}]
                                [{/foreach}]
                            </optgroup>
                            [{/if}]

                            [{/foreach}]
                        </select>
                    </td>
                    [{/section}]
                </tr>
                <tr>
                    <td>
                        <textarea rows="8" cols="50" name="description"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>
            </table>
            <input type="submit" value="[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SAVE"}]" class="edittext" style="vertical-align: top;">
        </form>
    </div>
    <br/>
    <br/>
    <br/>
    <hr/>
    <h3>[{oxmultilang ident="JXADVANCEDSELECTIONLIST_OPTION_IMAGES"}]</h3>
    <div>[{oxmultilang ident="JXADVANCEDSELECTIONLIST_OPTION_IMAGES_HELP"}]</div><br/><br/>
    [{foreach from=$oSelectionListImageSetsItems item=oOptionSet}]
    <form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post" enctype="multipart/form-data" style="display:block;margin-bottom: 20px;
    border-bottom: 2px solid #ccc;border-top: 2px solid #ccc;background-color: #eaeaea;padding: 10px 5px;">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="jxadvancedselectionlist_optionsimagesets">
        <input type="hidden" name="fnc" value="save">
        <input type="hidden" name="oxid" value="[{$oxid}]">
        <input type="hidden" name="option" value="[{$oSelectedOption->getOptionId()}]">
        <input type="hidden" name="productid" value="[{$sSelectedArticleOxid}]">
        <input type="hidden" name="jxadvancedselectionlistimagesets__oxid" value="[{$oOptionSet->jxadvancedselectionlistimagesets__oxid->value}]">

        <table style="width:100%;table-layout: fixed;">
            <tr>
                [{section name=iteration loop=8}]
                [{assign var="i" value=$smarty.section.iteration.iteration}]
                <td>
                    <select name="selectionlistoption_[{$i}]" style="vertical-align: top; max-width: 250px;width: 100%;">
                        [{if $oOptionSet->getSelectionListOption($i)}]
                            [{foreach from=$aAllSelectionListOptions item=aSelectionListData key=sSelectionId}]
                                [{foreach from=$aSelectionListData.options item=oOption key=sOptionId}]
                                    [{assign var="sOptionVal" value=$sSelectionId|cat:"|||"|cat:$sOptionId}]
                                    [{if $oOptionSet->getSelectionListOption($i) == $sOptionVal}]
                                    <option value="[{$sOptionVal}]" >[{$oOption->name}], [{$oOption->price}] [{$oOption->priceUnit}]</option>
                                    [{/if}]
                                [{/foreach}]
                            [{/foreach}]
                        [{else}]
                            <option value="">-</option>
                        [{/if}]
                    </select>
                </td>
                [{/section}]
            </tr>
            <tr>
                <td colspan="2">
                    <input type="hidden" id="option-image-input-[{$oOptionSet->getId()}]" name="jxadvancedselectionlistimagesets__image" value="[{$oOptionSet->getImage()}]">
                    <input type="file" name="image">
                </td>
                <td colspan="2" valign="top" class="edittext" id="option-image-[{$oOptionSet->getId()}]" style="text-align: center;">
                    <div style="font-weight: bold; margin-bottom: 10px;">[{oxmultilang ident="JXADVANCEDSELECTIONLIST_COMBINATION_IMAGE"}]</div>
                    [{if $oOptionSet->getImage()}]
                    <img src="/[{$sImgDir}]/[{$oOptionSet->getImage()}]" style="max-height: 200px; max-width: 300px; width: 100%;">
                    [{/if}]
                </td>
                <td colspan="4" valign="top" class="edittext">
                    <div style="font-weight: bold; margin-bottom: 10px;">[{oxmultilang ident="JXADVANCEDSELECTIONLIST_COMBINATION_DESC"}]</div>
                    <textarea rows="8" cols="50" name="description">[{$oOptionSet->getDescription()}]</textarea>
                </td>
            </tr>
        </table>
        <input type="submit" name="delete" onclick="return confirm('[{oxmultilang ident="JXADVANCEDSELECTIONLIST_DELETE_CONFIRM"}]');" value="[{oxmultilang ident="JXADVANCEDSELECTIONLIST_DELETE"}]" class="edittext" style="vertical-align: top;">
        <input type="submit" value="[{oxmultilang ident="JXADVANCEDSELECTIONLIST_SAVE"}]" class="edittext" style="vertical-align: top;">
    </form>
    [{/foreach}]

[{/if}]

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
