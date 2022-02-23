[{$smarty.block.parent}]
[{assign var="oArticle" value=$basketitem->getArticle()}]
[{if $oArticle->oxarticles__jxpackamount->value}]
    <td class="basket-pack-amount-indicator" data-amount="[{$oArticle->oxarticles__jxpackamount->value}]" style="display: none"></td>
[{/if}]