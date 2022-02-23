// Short-Handle for document.ready
$(function () {
    function number_format(number, decimals, decPoint, thousandsSep) {
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

    // Details page js
    // Discount calculation START
    if ($('.cl-details').length) {
        $(document).on('click', '.discount-prices .discount-row', function () {
            var qty = parseInt($(this).find('.discount-qty').data('qty'));

            $('#amountToBasket').val(qty);
            $('#amountToBasket').trigger('change');
            //recalculateDiscountPrice();
        });

        $(document).on('change', '#amountToBasket', function () {
            adjustPackAmount();
            recalculateDiscountPrice();
            showPriceSaving();
            updatePricePerUnitBlock();
        });

        window.updateDiscountPriceBlock = function () {
            $('.discount-prices .discount-row').each(function () {
                var qty = parseInt($(this).find('.discount-qty').data('qty'));
                var formattedPrice = recalculateDiscountPrice(qty, true);

                $(this).find('.discount-final-price .price-val').text(formattedPrice);
            });
            $('#amountToBasket').trigger('change');
        };

        // Compatibility with jxadvancedselectionlist module
        if ($('#jx-price-indicator').length) {
            $('#jx-price-indicator').on('price-data-updated', function () {
                recalculateDiscountPrice();
                showPriceSaving();

                // Update discount prices only after #jx-price-indicator was updated
                window.updateDiscountPriceBlock();
            });
        } else {
            window.updateDiscountPriceBlock();
        }

        /**
         * Recalculate product price based on quantity set in the amountToBasket input.
         * You can also use this function for calculating discount price for the required quantity. In this case you have to
         * pass desired quantity as function argument as well as set asReturn arg to true
         *
         * @param selectedQty
         * @param asReturn
         * @returns {any}
         */
        function recalculateDiscountPrice(selectedQty = 0, asReturn = false) {
            var priceSpan = $('#productPrice .price');
            var selectedQty = selectedQty ? selectedQty : parseInt($('#amountToBasket').val());
            var decimalsSeparator = $('#productPrice').data('decimalsseparator');
            var thousandSeparator = $('#productPrice').data('thousandseparator');
            var originalPrice = $('#productPrice').data('original-price').replace(thousandSeparator, '').replace(decimalsSeparator, '.');
            var originalTPrice = $('.price-old').data('original-price');
            var finalVal = null;
            var finalTVal = null;
            var formattedPrice = null;
            var formattedTPrice = null;

            if ($('#jx-price-indicator').length) {
                if ($('#jx-price-indicator').attr('data-currentprice')) {
                    originalPrice = $('#jx-price-indicator').attr('data-currentprice').replace(thousandSeparator, '').replace(decimalsSeparator, '.');
                } else {
                    originalPrice = $('#jx-price-indicator').attr('data-price');
                }
            }

            if (originalTPrice && selectedQty > 0) {
                finalTVal = selectedQty * originalTPrice;
                formattedTPrice = number_format(finalTVal, 2, decimalsSeparator, thousandSeparator);
                $('.price-old span').text(formattedTPrice);
            }

            $('.discount-prices .discount-row').each(function () {
                var qty = parseInt($(this).find('.discount-qty').data('qty'));
                var type = $(this).find('.discount-data').data('discount-type');
                var value = parseFloat($(this).find('.discount-data').data('discount-value'));

                if (selectedQty < qty) {
                    return;
                }

                if (type == 'perc') {
                    finalVal = originalPrice - (originalPrice * value / 100);
                } else {
                    finalVal = originalPrice - value;

                    if (finalVal < 0) {
                        finalVal = 0;
                    }
                }
            });

            if (finalVal !== null) {
                formattedPrice = number_format(finalVal, 2, decimalsSeparator, thousandSeparator);
                return asReturn
                    ? formattedPrice
                    : $(priceSpan)
                        .addClass('has-discount')
                        .attr('data-price-without-discount', originalPrice)
                        .text(formattedPrice);
            } else {
                formattedPrice = number_format(originalPrice, 2, decimalsSeparator, thousandSeparator);
                return asReturn ? formattedPrice : $(priceSpan).removeClass('has-discount').text(formattedPrice);
            }
        }

        function showPriceSaving() {
            var selectedQty = parseInt($('#amountToBasket').val());
            var decimalsSeparator = $('#productPrice').data('decimalsseparator');
            var thousandSeparator = $('#productPrice').data('thousandseparator');
            var priceWithDiscount = $('#productPrice .price').text().replace(thousandSeparator, '').replace(decimalsSeparator, '.');
            var priceWithoutDiscount = $('#productPrice .price').hasClass('has-discount')
                ? $('#productPrice .price').attr('data-price-without-discount')
                : priceWithDiscount;
            var summPriceWithoutDiscount = priceWithoutDiscount * selectedQty;
            var summPriceWithDiscount = priceWithDiscount * selectedQty;
            var summSaving = summPriceWithoutDiscount - summPriceWithDiscount;
            var summPriceWithoutDiscountFormatted = number_format(summPriceWithoutDiscount, 2, decimalsSeparator, thousandSeparator);
            var summPriceWithDiscountFormatted = number_format(summPriceWithDiscount, 2, decimalsSeparator, thousandSeparator);
            var summSavingFormatted = number_format(summSaving, 2, decimalsSeparator, thousandSeparator);

            if (selectedQty > 0) {
                $('#productPrice .one-item-price').hide();

                if ($('#productPrice .price').hasClass('has-discount')) {
                    $('.price-old').hide();
                    $('#productPrice .all-items-price .without-discount').addClass('crossed');
                    $('#productPrice .all-items-price .without-discount .all-items-price__price').text(summPriceWithoutDiscountFormatted);
                    $('#productPrice .all-items-price .with-discount .all-items-price__price').text(summPriceWithDiscountFormatted);
                    $('#productPrice .all-items-price .saving-notice .saving-notice__price').text(summSavingFormatted);
                    $('#productPrice .all-items-price .with-discount').show();
                    $('#productPrice .all-items-price .saving-notice').show();
                } else {
                    $('.price-old').show();
                    $('#productPrice .all-items-price .without-discount').removeClass('crossed');
                    $('#productPrice .all-items-price .without-discount .all-items-price__price').text(summPriceWithoutDiscountFormatted);
                    $('#productPrice .all-items-price .with-discount').hide();
                    $('#productPrice .all-items-price .saving-notice').hide();
                }

                $('#productPrice .all-items-price').show();
            } else {
                $('#productPrice .one-item-price').show();
                $('#productPrice .all-items-price').hide();
            }
        }

        function updatePricePerUnitBlock() {
            var pricePerUnitBlock = $('.jx-price-per-unit');
            var pricePerPackBlock = $('.jx-price-per-pack');
            var unitQuantity = $(pricePerUnitBlock).data('unit-quantity');
            var thousandSeparator = $('#productPrice').data('thousandseparator');
            var decimalsSeparator = $('#productPrice').data('decimalsseparator');
            var priceWithDiscount = $('#productPrice .price').text().replace(thousandSeparator, '').replace(decimalsSeparator, '.');
            var pricePerPack = $('#productPrice .price').text();
            var pricePerUnit = priceWithDiscount/unitQuantity;

            if (pricePerUnitBlock.length) {
                $(pricePerUnitBlock).find('.unit-price').text(number_format(pricePerUnit, 2, decimalsSeparator, thousandSeparator));
            }

            if (pricePerPackBlock.length) {
                $(pricePerPackBlock).find('.unit-price').text(pricePerPack);
            }
        }

        function initPackAmount() {
            var indicator = $('#pack-amount-indicator');
            var packAmount = indicator.length ? parseInt($(indicator).attr('data-amount')) : 0;

            if (!packAmount) {
                return;
            }

            $('#amountToBasket').val(packAmount);
            $('#amountToBasket').attr('data-old-amount', packAmount);
            $('#amountToBasket').trigger('change');
        }

        function adjustPackAmount() {
            var indicator = $('#pack-amount-indicator');
            var packAmount = indicator.length ? parseInt($(indicator).attr('data-amount')) : 0;
            var packLabel = indicator.length ? $(indicator).attr('data-pack-label') : 'Piece';
            var curAmount = parseInt($('#amountToBasket').val());
            var newAmount = 0;
            var oldAmount = parseInt($('#amountToBasket').attr('data-old-amount'));
            var packLabelItem = $('#pack-label');

            if (!packAmount) {
                return;
            }

            if (curAmount % packAmount != 0) {
                if (curAmount > oldAmount) {
                    newAmount = curAmount - (curAmount % packAmount) + packAmount;
                } else {
                    newAmount = curAmount - (curAmount % packAmount);
                }

                if (newAmount < packAmount) {
                    newAmount = packAmount;
                }

                $('#amountToBasket').val(newAmount)
                $('#amountToBasket').attr('data-old-amount', newAmount);
            }

            if (packLabelItem.length <= 0) {
                packLabelItem = $('<span id="pack-label"><i>' + packLabel + '</i></span>');
                $(packLabelItem).insertAfter('#amountToBasket');
            }
        }

        initPackAmount();
    }
    // Discount calculation END

    // FIX for cases when form is wrapped with a tag and we do not need link redirect when focus on input
    $('#basketModal .accessoires-block a, .trending-product a').click(function (e) {
        if (e.target == $(this).find('input.amount')[0]) {
            e.preventDefault();
        }
    })

    if ($('#promo-carousel').length) {
        var promoCarouselFlexslider = $('#promo-carousel').flexslider(
            {
                animation: "fade",
                animationSpeed: 300,
                controlNav: false,
                directionNav: false,
                after: function (slider) {
                    var activeSlider = $(slider).find('.flex-active-slide');
                    var index = 0;
                    var indexDetected = false;

                    $(slider).find('.item').not('.clone').each(function (i, el) {
                        if (indexDetected) {
                            return;
                        }

                        if (el == activeSlider[0]) {
                            indexDetected = true;
                        }
                        index++;
                    });

                    $('#promo-carousel-wrapper .slider-links .item').removeClass('active');
                    $('#promo-carousel-wrapper .slider-links .item').eq(index - 1).addClass('active');
                },
            }
        );

        $('#promo-carousel-wrapper .slider-links li').hover(
            function () {
                var index = $(this).index();
                $('#promo-carousel').data('flexslider').flexAnimate(index, true);
            }, function () {
                // do nothing
            }
        );
    }

    if ($('#basket_form').length){
        function initBasketPackAmount() {
            $('.basket-pack-amount-indicator').each(function(){
                var indicator = $(this);
                var packAmount = parseInt($(indicator).attr('data-amount'));
                var input = $(this).closest('tr').find('td.quantity input[type="number"]');

                if (!packAmount) {
                    return;
                }

                $(input).attr('data-old-amount', $(input).val());
                $(input).trigger('change');
            });
        }

        function adjustBasketPackAmount(input) {
            var indicator = $(input).closest('tr').find('.basket-pack-amount-indicator');
            var packAmount = indicator.length ? parseInt($(indicator).attr('data-amount')) : 0;
            var curAmount = parseInt($(input).val());
            var newAmount = 0;
            var oldAmount = parseInt($(input).attr('data-old-amount'));

            if (!packAmount) {
                return;
            }

            if (curAmount % packAmount != 0) {
                if (curAmount > oldAmount) {
                    newAmount = curAmount - (curAmount % packAmount) + packAmount;
                } else {
                    newAmount = curAmount - (curAmount % packAmount);
                }

                if (newAmount < packAmount) {
                    newAmount = packAmount;
                }

                $(input).val(newAmount)
                $(input).attr('data-old-amount', newAmount);
            }
        }

        $(document).on('change', 'td.quantity input[type="number"]', function () {
            adjustBasketPackAmount($(this));
        });

        initBasketPackAmount();
    }
});