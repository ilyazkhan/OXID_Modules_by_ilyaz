[{block name="jx_details_productmain_selectlists"}]
[{if $oViewConf->showSelectLists()}]
    [{assign var="oSelections" value=$oDetailsProduct->getSelections()}]
    [{assign var="sDetailsProductId" value=$oDetailsProduct->getId()}]
    [{assign var="oCurrency" value=$oView->getActCurrency()}]
    [{assign var="oPrice" value=$oDetailsProduct->getPrice()}]
    [{assign var="oConfig" value=$oViewConf->getConfig()}]

    [{if $oDetailsProduct->isParentNotBuyable()}]
        [{assign var="oPrice" value=$oDetailsProduct->getVarMinPrice()}]
    [{/if}]

    [{if $oSelections}]
    [{oxstyle include=$oViewConf->getModuleUrl('jxadvancedselectionlist','out/src/css/jxadvancedselectionlist.css')}]
    [{oxscript add="
    $(function () {
        var hiddenOptionsRelationDataTable = JSON.parse($('#jx-hidden-options-indicator').attr('data-table'));

        function jx_number_format (number, decimals, decPoint, thousandsSep) {
          number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
          var n = !isFinite(+number) ? 0 : +number;
          var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
          var sep = typeof thousandsSep === 'undefined' ? ',' : thousandsSep;
          var dec = typeof decPoint === 'undefined' ? '.' : decPoint;
          var s = '';

          var toFixedFix = function toFixedFix(n, prec) {
            if (('' + n).indexOf('e') === -1) {
              return +(Math.round(n + 'e+' + prec) + 'e-' + prec);
            } else {
              var arr = ('' + n).split('e');
              var sig = '';

              if (+arr[1] + prec > 0) {
                sig = '+';
              }

              return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec);
            }
          }; // @todo: for IE parseFloat(0.55).toFixed(0) = 0;


          s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.');

          if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
          }

          if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
          }

          return s.join(dec);
        }

        function reinit_pswp() {
            // Remove old PhotoSwipe click event
                $('#zoom1').off('click');
                pswp = Flow.setPhotoSwipe();

                $('#zoom1').click( function(e) {
                    e.preventDefault();
                    var iIndex = 0;

                    if ($('#morePicsContainer').length) {
                        iIndex = $('#morePicsContainer').find('a.selected').data('num') - 1;
                    }

                    pswp.open(iIndex, this);
                }
            );
        }

        function jx_update_price() {
            var defaultPrice = $('#jx-price-indicator').attr('data-price');
            var decimalsSeparator = $('#jx-price-indicator').attr('data-decimalsseparator');
            var thousandSeparator = $('#jx-price-indicator').attr('data-thousandseparator');
            var currentPrice = $('#jx-price-indicator').attr('data-currentprice');
            var firstImg = $('#morePicsContainer img').first();
            var firstImgA = $(firstImg).closest('a');
            var soloImage = $('.details-col-left img').first();
            var soloImageA = $(soloImage).closest('a');
            var calculatedPrice = defaultPrice;
            var possibleImages = new Object();
            var possibleDescriptions = new Object();
            var possibleImagesKeys = null;
            var possibleImagesMaxKey = null;
            var formattedPrice = null;
            var parts = null;
            var productImage = null;
            var productImageDesc = '';

            if (!currentPrice) {
                currentPrice = jx_number_format(calculatedPrice, 2, decimalsSeparator, thousandSeparator);
            }

            $('.jx-selectorsBox input:radio').each(function () {
                var fePrice = $(this).attr('data-feprice');

                if (this.checked) {
                    if (fePrice) {
                        if (fePrice.indexOf('%') === -1) {
                            calculatedPrice = parseFloat(calculatedPrice) + parseFloat(fePrice);
                        } else {
                            parts = fePrice.split('%');
                            calculatedPrice = parseFloat(calculatedPrice) + (parseFloat(calculatedPrice) * parts[0] / 100);
                        }
                    }
                }
            });

            formattedPrice = jx_number_format(calculatedPrice, 2, decimalsSeparator, thousandSeparator);

            $('#productPrice .price').text($('#productPrice .price').text().replace(currentPrice, formattedPrice));
            $('#jx-price-indicator').attr('data-currentprice', formattedPrice);
            $('#jx-price-indicator').trigger('price-data-updated');



            // Image detection
            $('.option-images-data-block .img-set-data').each(function () {
                var imgPower = 0;
                var imgPath = $(this).attr('data-img');
                var imgDesc = $(this).attr('data-desc');
                var isGlobal = $(this).attr('data-is-global');

                if (!isGlobal) {
                    imgPower++;
                }

                $(this).find('i').each(function(){
                    var optionVal = $(this).attr('data-option-val');
                    var selectedInput = $('.jx-selectorsBox input:radio:checked[data-full-option-id=\"'+optionVal+'\"]');

                    if (!optionVal) {
                        return;
                    }

                    if (selectedInput.length) {
                        imgPower = imgPower + 10;
                    } else {
                        imgPower = -999999;
                    }
                });

                if (imgPower > 0) {
                    possibleImages[imgPower] = imgPath;
                    possibleDescriptions[imgPower] = imgDesc;
                }
            });

            possibleImagesKeys = Object.keys(possibleImages).map(function(item) {
                return parseInt(item);
            });

            possibleImagesMaxKey = possibleImagesKeys.length ? Math.max.apply(Math, possibleImagesKeys) : 0;

            if (possibleImagesMaxKey) {
                productImage = possibleImages[possibleImagesMaxKey];
                productImageDesc = possibleDescriptions[possibleImagesMaxKey];
            }

            // We have at least 2 product images
            if ($(firstImg).length) {
                if (productImage) {
                    if (!$(firstImg).attr('data-default-icon')) {
                        $(firstImg).attr('data-default-icon', $(firstImg).attr('src'));
                    }

                    if (!$(firstImgA).attr('data-default-zoom-url')) {
                        $(firstImgA).attr('data-default-zoom-url', $(firstImgA).attr('data-zoom-url'));
                    }

                    if (!$(firstImgA).attr('data-default-href')) {
                        $(firstImgA).attr('data-default-href', $(firstImgA).attr('href'));
                    }

                    $(firstImg).attr('src', productImage);
                    $(firstImgA).removeData('zoom-url');
                    $(firstImgA).attr('href', productImage).attr('data-zoom-url', productImage);
                    $('.jx-current-selection-combination-desc').text(productImageDesc);
                    $(firstImgA).click();
                    reinit_pswp();
                } else {
                    $(firstImg).attr('src', $(firstImg).attr('data-default-icon'));
                    $(firstImgA).removeData('zoom-url');
                    $(firstImgA).attr('href', $(firstImgA).attr('data-default-href')).attr('data-zoom-url', $(firstImgA).attr('data-default-zoom-url'));
                    $('.jx-current-selection-combination-desc').text('');
                    $(firstImgA).click();
                    reinit_pswp();
                }
            } else if ($(soloImage).length) {
                if (productImage) {
                    if (!$(soloImage).attr('data-default-src')) {
                        $(soloImage).attr('data-default-src', $(soloImage).attr('src'));
                    }

                    if (!$(soloImageA).attr('data-default-href')) {
                        $(soloImageA).attr('data-default-href', $(soloImageA).attr('href'));
                    }

                    $(soloImage).attr('src', productImage);
                    $(soloImageA).attr('href', productImage);
                    $('.jx-current-selection-combination-desc').text(productImageDesc);
                    reinit_pswp();
                } else {
                    $(soloImage).attr('src', $(soloImage).attr('data-default-src'));
                    $(soloImageA).attr('href', $(soloImageA).attr('data-default-href'))
                    $('.jx-current-selection-combination-desc').text('');
                    reinit_pswp();
                }
            }
        }

        function updateRelatedOptionsVisibility(){
            var firstAvailable = null;

            $('.jx-selectorsBox input:radio:checked').each(function () {
                var fullOptionId = $(this).attr('data-full-option-id');
                var fullOptionIdGeneral = $(this).attr('data-full-option-id-general');

                if (fullOptionId && hiddenOptionsRelationDataTable[fullOptionId]){
                    $.each(hiddenOptionsRelationDataTable[fullOptionId], function(i, v){
                        $('[data-full-option-id=\"'+v+'\"]').attr('disabled', true);
                        $('[data-full-option-id=\"'+v+'\"]').closest('.option-wrap').addClass('disabled');
                        $('[data-full-option-id-general=\"'+v+'\"]').attr('disabled', true);
                        $('[data-full-option-id-general=\"'+v+'\"]').closest('.option-wrap').addClass('disabled');
                    });
                }

                if (fullOptionIdGeneral && hiddenOptionsRelationDataTable[fullOptionIdGeneral]){
                    $.each(hiddenOptionsRelationDataTable[fullOptionIdGeneral], function(i, v){
                        $('[data-full-option-id=\"'+v+'\"]').attr('disabled', true);
                        $('[data-full-option-id=\"'+v+'\"]').closest('.option-wrap').addClass('disabled');
                        $('[data-full-option-id-general=\"'+v+'\"]').attr('disabled', true);
                        $('[data-full-option-id-general=\"'+v+'\"]').closest('.option-wrap').addClass('disabled');
                    });
                }
            });

            $('.option-wrap.disabled input[type=\"radio\"][disabled]:checked').each(function () {
                firstAvailable = $(this).closest('.jx-option-group').find('.option-wrap:not(.disabled)');

                if (firstAvailable.length) {
                    $(firstAvailable).find('label').click();
                } else {
                    firstAvailable = $(this).closest('.jx-option-group').find('.option-wrap').first();
                    $(firstAvailable).removeClass('disabled');
                    $(firstAvailable).find('input').attr('disabled', false);
                    $(firstAvailable).find('label').click();
                }
            });
        }

        $('.jx-selectorsBox input[type=radio]').change(function() {
            jx_update_price();

            $('.option-wrap').removeClass('disabled');
            $('.jx-selectorsBox input:radio').removeAttr('disabled');
            updateRelatedOptionsVisibility();
        });

        jx_update_price();
        updateRelatedOptionsVisibility();
    });
    "}]
    <div class="jx-selectorsBox-wrapper">
        <span id="jx-price-indicator"
              data-price="[{$oPrice->getPrice()}]"
              data-currency="[{$oCurrency->name}]"
              data-decimalsseparator="[{if $oCurrency->dec}][{$oCurrency->dec}][{else}],[{/if}]"
              data-thousandseparator="[{if $oCurrency->thousand}][{$oCurrency->thousand}][{else}].[{/if}]"
        ></span>
        [{if $oConfig->getConfigParam('jxadvancedselectionlist_main_showexpandbutton')}]
            <a class="btn btn-primary" data-toggle="collapse" href="#productSelections" role="button" aria-expanded="false" aria-controls="productSelections">
                [{oxmultilang ident="JXADVANCEDSELECTIONLIST_CUSTOMIZATION"}]
            </a>
        [{/if}]
        <div class="jx-selectorsBox[{if $oConfig->getConfigParam('jxadvancedselectionlist_main_showexpandbutton')}] collapse[{/if}]" id="productSelections">
            [{foreach from=$oSelections item=oSelectionList name=selections}]
                [{assign var="iKey" value=$smarty.foreach.selections.index}]
                [{assign var="oOptions" value=$oSelectionList->getSelections()}]

                [{if $iKey == 0}]
                    [{assign var="aAllHiddenOptionsRelations" value=$oSelectionList->getAllHiddenOptionsRelations()}]
                    <span id="jx-hidden-options-indicator"
                          data-table='[{$aAllHiddenOptionsRelations|@json_encode}]'
                    ></span>
                [{/if}]

                [{if $oOptions}]
                    [{assign var="aImageSets" value=$oSelectionList->getImageSets($sDetailsProductId)}]

                    <div class="option-images-data-block">
                        [{foreach from=$aImageSets item=aImageSetItem}]
                        <div class="img-set-data" data-img="/[{$aImageSetItem.img}]" data-desc="[{$aImageSetItem.description}]" data-is-global="[{$aImageSetItem.isGlobal}]">
                            [{foreach from=$aImageSetItem.options item=optionVal key=index}]
                            <i data-option-val="[{$optionVal}]"></i>
                            [{/foreach}]
                        </div>
                        [{/foreach}]
                    </div>

                    <div class="jx-option-group">
                        <div class="jx-option-group-label">[{$oSelectionList->getLabel()}]</div>
                        [{if $editable !== false}]
                        [{foreach from=$oOptions item=oOption}]
                        <div class="option-wrap">
                            <input type="radio"
                                   [{if $oOption->isDisabled()}]disabled[{/if}]
                                   id="jx-selection-[{$iKey}]-[{$oOption->getValue()}]"
                                   name="sel[[{$iKey}]]"
                                   value="[{$oOption->getValue()}]"
                                   [{if $oOption->isActive()}] checked[{/if}]
                                   data-feprice="[{$oOption->fePrice}]"
                                   data-full-option-id="[{$oOption->fullOptionId}]"
                                   data-full-option-id-general="[{$oSelectionList->getId()}]|||*">
                            <label class="[{if $oOption->isActive()}] active[{/if}][{if $oOption->icon}] with-icon[{/if}]" for="jx-selection-[{$iKey}]-[{$oOption->getValue()}]">
                                [{if $oOption->icon}]<img class="option-icon" src="/[{$oOption->icon}]" alt="[{$oSelectionList->getLabel()}] [{$oOption->getName()}]" title="[{$oSelectionList->getLabel()}] [{$oOption->getName()}]" width="50">[{/if}]
                                <i class="fa fa-check-circle selected-marker" aria-hidden="true"></i>
                                [{$oOption->getName()}]
                                [{if $oOption->description || $oOption->icon}]
                                    <button type="button"
                                            data-toggle="modal"
                                            class="open-description-modal"
                                            data-target="#jx-selection-modal-[{$iKey}]-[{$oOption->getValue()}]">
                                        <i class="fa fa-info"></i>
                                    </button>
                                    <div class="modal fade jx-selection-modal" id="jx-selection-modal-[{$iKey}]-[{$oOption->getValue()}]" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="modal-title">[{$oOption->getName()}]</div>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                [{if $oOption->image}]
                                                <div class="option-image"><img src="/[{$oOption->image}]" alt="[{$oSelectionList->getLabel()}] [{$oOption->getName()}]" img="[{$oSelectionList->getLabel()}] [{$oOption->getName()}]"></div>
                                                [{/if}]
                                                [{if $oOption->description}]
                                                <div class="option-description">[{$oOption->description|html_entity_decode}]</div>
                                                [{/if}]
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">[{oxmultilang ident="CLOSE"}]</button>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                [{/if}]
                            </label>
                        </div>
                        [{/foreach}]
                        [{/if}]
                    </div>
                [{/if}]
            [{/foreach}]
        </div>
    </div>
    <div class="jx-current-selection-combination-desc"></div>
    [{/if}]
[{/if}]
[{/block}]