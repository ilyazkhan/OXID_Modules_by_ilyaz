[{$smarty.block.parent}]
<tr>
    <td colspan=9 style="padding-bottom: 20px;">
        [{oxmultilang ident="JXADVANCEDSELECTIONLIST_USE_STEP_BY_STEP_SELECTION"}]
        <input type="hidden" name="editval[oxarticles__jxadvancedselectionlistusestepbystepselection]" value="0">
        <input class="edittext" type="checkbox" name="editval[oxarticles__jxadvancedselectionlistusestepbystepselection]" value='1' [{if $edit->oxarticles__jxadvancedselectionlistusestepbystepselection->value == 1}]checked[{/if}] [{$readonly}]>
    </td>
</tr>
<tr>
    <td colspan=9 style="padding-bottom: 20px;">
        [{oxmultilang ident="JXADVANCEDSELECTIONLIST_SHARE_DISCOUNTS_BETWEEN_VARIANTS"}]
        <input type="hidden" name="editval[oxarticles__jxadvancedselectionlistsharediscounts]" value="0">
        <input class="edittext" type="checkbox" name="editval[oxarticles__jxadvancedselectionlistsharediscounts]" value='1' [{if $edit->oxarticles__jxadvancedselectionlistsharediscounts->value == 1}]checked[{/if}] [{$readonly}]>
    </td>
</tr>