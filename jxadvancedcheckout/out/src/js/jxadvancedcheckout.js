// Short-Handle for document.ready
$(function () {
    function getBasketModalAccessoiresGridSize() {
        return (window.innerWidth < 651) ? 2 : 3;
    }

    $("#basketModal .accessoires-block .flexslider").flexslider({
        animation: "slide",
        maxItems: getBasketModalAccessoiresGridSize(),
        minItems: getBasketModalAccessoiresGridSize(),
        itemWidth: 100,
        itemMargin: 40,
        controlNav: false,
        directionNav: true,
        slideshow: false,
        start: function (slider) {
            flexslider = slider;
        }
    })

    $('.accessoires-block form input[name="am"]').change(function () {
        var generalAm = 0;

        $('.accessoires-block form input[name="am"]').each(function () {
            var amInput = $(this);
            var amount = $(amInput).val();

            if (amount > 0) {
                generalAm++;
            }
        });

        if (generalAm > 0) {
            $('#add-accessories-btn').show();
        } else {
            $('#add-accessories-btn').hide();
        }
    });

    $('#add-accessories-btn').one('click', function (e) {
        e.preventDefault();
        var link = $(this).attr('href');
        var allFormCount = $('.accessoires-block form').length;
        var formProcessed = 0;

        $(this).attr('disabled', '1');

        $('.accessoires-block form').each(function () {
            var form = $(this);
            var url = form.attr('action');
            var amInput = $(this).find('input[name="am"]');
            var amount = $(amInput).length ? $(amInput).val() : 0;

            if (amount > 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function (data) {
                        formProcessed++;
                    },
                    fail: function () {
                        formProcessed++;
                    },
                });
            } else {
                formProcessed++;
            }
        });

        // Wait until all forms are processed then redirect to basket
        var timer = setInterval(function () {
                if (allFormCount == formProcessed) {
                    clearInterval(timer);
                    window.location.href = link;
                }
            },
            100
        );
    });
});