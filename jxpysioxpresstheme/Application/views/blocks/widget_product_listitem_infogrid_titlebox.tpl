[{$smarty.block.parent}]

[{assign var="iRatingValue" value=$oView->getRatingValue()}]

<div class="list-block-rating">
    
        [{section name="starRatings" start=0 loop=5}]
        [{if $iRatingValue == 0}]
            <i class="fa fa-star rating-star-empty"></i>
        [{else}]
            [{if $iRatingValue > 1}]
                <i class="fa fa-star rating-star-filled"></i>
                [{assign var="iRatingValue" value=$iRatingValue-1}]
            [{else}]
                [{if $iRatingValue < 0.5}]
                    [{if $iRatingValue < 0.3}]
                        <i class="fa fa-star rating-star-empty"></i>
                    [{else}]
                        <i class="fa fa-star-half-o rating-star-filled"></i>
                    [{/if}]
                    [{assign var="iRatingValue" value=0}]
                [{elseif $iRatingValue > 0.7}]
                    <i class="fa fa-star rating-star-filled"></i>
                    [{assign var="iRatingValue" value=0}]
                [{else}]
                    <i class="fa fa-star-half-o rating-star-filled"></i>
                    [{assign var="iRatingValue" value=0}]
                [{/if}]
            [{/if}]
        [{/if}]
        [{/section}]
   
</div>