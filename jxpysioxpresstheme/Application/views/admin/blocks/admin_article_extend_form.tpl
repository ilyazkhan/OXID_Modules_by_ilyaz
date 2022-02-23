[{$smarty.block.parent}]
<tr>
    <td class="edittext">
        [{oxmultilang ident="JXPYSIOXPRESSTHEME_PACK_AMOUNT"}]
    </td>
    <td class="edittext">
        <input type="text" pattern="[0-9.]+" class="editinput" name="editval[oxarticles__jxpackamount]" value="[{$edit->oxarticles__jxpackamount->value}]" [{$readonly}]>
    </td>
</tr>