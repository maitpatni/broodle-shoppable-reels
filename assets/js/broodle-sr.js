/**
 * Broodle Shoppable Reels — Frontend JS
 * Compatible with Bootstrap 5.x (vanilla JS API)
 */

(function($) {
    'use strict';

    // ─── Bootstrap 5 Modal Helper ───
    // Works with Bootstrap 5 (no jQuery plugin) and falls back gracefully
    function showModal(selector) {
        var el = document.querySelector(selector);
        if (!el) return null;
        // Check if Bootstrap 5 is available
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var instance = bootstrap.Modal.getInstance(el);
            if (!instance) {
                instance = new bootstrap.Modal(el, { backdrop: 'static', keyboard: false });
            }
            instance.show();
            return instance;
        }
        // Fallback: manually show modal (for edge cases where bootstrap JS failed to load)
        $(el).addClass('show').css('display', 'block');
        $('body').addClass('modal-open');
        // Add backdrop
        if (!$('.modal-backdrop').length) {
            $('body').append('<div class="modal-backdrop fade show"></div>');
        }
        return null;
    }

    function hideModal(selector) {
        var el = document.querySelector(selector);
        if (!el) return;
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var instance = bootstrap.Modal.getInstance(el);
            if (instance) {
                instance.hide();
                return;
            }
        }
        // Fallback
        $(el).removeClass('show').css('display', 'none');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }

    // ─── Toggle Head ───
    $("body").on("click", ".toggle-head", function () {
        $(".toggle-body").toggleClass("d-none");
    });

    // ─── Close Product Detail Modal ───
    // This handles video pause + cleanup. Bootstrap 5's data-bs-dismiss handles the actual close.
    $(document).on("click", "#productDetail_modal .close", function (e) {
        e.preventDefault();
        e.stopPropagation();
        var modal = $("#productDetail_modal");
        // Pause any playing videos first
        modal.find('video').each(function() { this.pause(); });
        hideModal('#productDetail_modal');
        modal.find('.loader').show();
    });

    // ─── Size Option Selection ───
    $("body").on("click", ".option_item", function () {
        $(".option_item").removeClass("active");
        $(this).addClass("active");
        var $size = $(this).find("input").val();
        var $addtocart = $(".productDataSide .addtocart_moreinfo .add-to-cart").attr("href");
        if ($addtocart) {
            var $newarray = $addtocart.split("?");
            if ($newarray.length == 3) {
                $newarray.push('attribute_size=' + $size);
            } else {
                $newarray[3] = 'attribute_size=' + $size;
            }
            $(".productDataSide .addtocart_moreinfo .add-to-cart").attr("href", $newarray.join("?"));
        }
    });

    // ─── Initialize Reel Sliders ───
    $(document).ready(function () {
        if ($('.reelUpSlider').length && $.fn.slick) {
            $('.reelUpSlider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                infinite: true,
                dots: true,
                centerMode: false,
                variableWidth: false,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        }
    });

    // ─── Click on Reel Product Card → Open Modal ───
    $("body").on('click', '.reel_product', function () {
        var $this = $(this);
        var product_id = $this.data('product_id');
        var reel_id = $this.data('reel_id');

        // Ensure hidden listener is bound
        bindModalHiddenListener();

        // Show modal
        showModal('#productDetail_modal');

        // Perform AJAX request
        $.ajax({
            type: "POST",
            dataType: "json",
            url: broodle_sr_ajax.ajax_url,
            data: {
                action: "broodle_sr_create_user_form_ajax",
                product_id: product_id,
                reel_id: reel_id,
                broodle_sr_nonce: broodle_sr_ajax.nonce
            },
            success: function (response) {
                var modal = $('#productDetail_modal');

                modal.find('.reel').html(response.reel_data);

                var product_images = response.product.product_images;
                modal.find('.product_slider').remove();
                modal.find('.productDataSide').prepend(product_images);

                // Build price display based on whether product is on sale
                var priceHtml = '';
                var sellingPrice = parseFloat(response.product.selling_price) || 0;
                var originalPrice = parseFloat(response.product.original_price) || 0;
                var hasPrice = response.product.has_price;

                if (hasPrice) {
                    if (sellingPrice > 0 && originalPrice > 0 && sellingPrice < originalPrice) {
                        priceHtml = '<div class="selling_price">₹<span>' + sellingPrice.toFixed(2) + '</span></div><div class="regular_price">₹<del><span>' + originalPrice.toFixed(2) + '</span></del></div>';
                    } else if (originalPrice > 0) {
                        priceHtml = '<div class="selling_price">₹<span>' + originalPrice.toFixed(2) + '</span></div>';
                    } else if (sellingPrice > 0) {
                        priceHtml = '<div class="selling_price">₹<span>' + sellingPrice.toFixed(2) + '</span></div>';
                    }
                    modal.find('.sel_org_price').html(priceHtml).show();
                    modal.find('.addtocart_moreinfo').html(
                        '<a href="' + response.product.product_url + '" class="more-info">More info</a><a href="' + response.product.add_to_cart + '" class="add-to-cart">Add to Cart</a>'
                    ).show();
                } else {
                    modal.find('.sel_org_price').html('').hide();
                    modal.find('.addtocart_moreinfo').html(
                        '<a href="' + response.product.product_url + '" class="more-info">More info</a>'
                    ).show();
                }
                modal.find('.product_name').html('<h5>' + response.product.product_name + '</h5>');

                var sizeOption = response.product.product_attributes ? response.product.product_attributes.size : null;

                modal.find('.size .option').remove();
                modal.find('.size p').remove();

                if (sizeOption) {
                    modal.find('.size').html('<P>Size:</P><div class="option"></div>');
                    for (var s = 0; s < sizeOption.length; s++) {
                        var size = sizeOption[s];
                        modal.find('.size .option').append(
                            '<span class="option_item"><input type="radio" class="size" name="size_' + size + '" id="size_' + size + '" value="' + size + '"><label for="size_' + size + '">' + size + '</label></span>'
                        );
                    }
                }

                // Hide loader on success
                modal.find('.loader').hide();

                // Mobile layout fix
                if (window.innerWidth <= 767) {
                    if (modal.find('.product-details').length) {
                        modal.find('.product-details').children().unwrap();
                    }
                    setTimeout(function() {
                        var productElements = modal.find('.product_name, .sel_org_price, .size, .addtocart_moreinfo');
                        if (productElements.length > 0) {
                            productElements.wrapAll('<div class="product-details"></div>');
                        }
                    }, 100);
                }

                // Initialize product image slider
                if (modal.find('.product_slider').length && $.fn.slick) {
                    modal.find('.product_slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerMode: true,
                        focusOnSelect: true,
                        dots: true,
                        arrows: false,
                        adaptiveHeight: false,
                        responsive: [
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    centerMode: false,
                                    focusOnSelect: true,
                                    dots: true,
                                    arrows: false,
                                    adaptiveHeight: false
                                }
                            }
                        ]
                    });
                }
            },
            error: function() {
                $('#productDetail_modal').find('.loader').hide();
            }
        });
    });

    // ─── Click on Related Product → Open Modal ───
    $("body").on('click', '.relproductModel', function () {
        var product_id = $(this).data('product_id');
        var reel_id = $(this).data('reel_id');

        // Ensure hidden listener is bound
        bindModalHiddenListener();

        showModal('#productDetail_modal');
        $('#productDetail_modal').find('.loader').show();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: broodle_sr_ajax.ajax_url,
            data: {
                action: "broodle_sr_create_user_form_ajax",
                product_id: product_id,
                reel_id: reel_id,
                broodle_sr_nonce: broodle_sr_ajax.nonce
            },
            success: function (response) {
                var modal = $('#productDetail_modal');

                modal.find('.reel').html(response.reel_data);

                var product_images = response.product.product_images;
                modal.find('.product_slider').remove();
                modal.find('.productDataSide').prepend(product_images);

                var priceHtml = '';
                var sellingPrice = parseFloat(response.product.selling_price) || 0;
                var originalPrice = parseFloat(response.product.original_price) || 0;
                var hasPrice = response.product.has_price;

                if (hasPrice) {
                    if (sellingPrice > 0 && originalPrice > 0 && sellingPrice < originalPrice) {
                        priceHtml = '<div class="selling_price">₹<span>' + sellingPrice.toFixed(2) + '</span></div><div class="regular_price">₹<del><span>' + originalPrice.toFixed(2) + '</span></del></div>';
                    } else if (originalPrice > 0) {
                        priceHtml = '<div class="selling_price">₹<span>' + originalPrice.toFixed(2) + '</span></div>';
                    } else if (sellingPrice > 0) {
                        priceHtml = '<div class="selling_price">₹<span>' + sellingPrice.toFixed(2) + '</span></div>';
                    }
                    modal.find('.sel_org_price').html(priceHtml).show();
                    modal.find('.addtocart_moreinfo').html(
                        '<a href="' + response.product.product_url + '" class="more-info">More info</a><a href="' + response.product.add_to_cart + '" class="add-to-cart">Add to Cart</a>'
                    ).show();
                } else {
                    modal.find('.sel_org_price').html('').hide();
                    modal.find('.addtocart_moreinfo').html(
                        '<a href="' + response.product.product_url + '" class="more-info">More info</a>'
                    ).show();
                }
                modal.find('.product_name').html('<h5>' + response.product.product_name + '</h5>');

                var sizeOption = response.product.product_attributes ? response.product.product_attributes.size : null;

                modal.find('.size .option').remove();
                modal.find('.size p').remove();

                if (sizeOption) {
                    modal.find('.size').html('<P>Size:</P><div class="option"></div>');
                    for (var s = 0; s < sizeOption.length; s++) {
                        var size = sizeOption[s];
                        modal.find('.size .option').append(
                            '<span class="option_item"><input type="radio" class="size" name="size_' + size + '" id="size_' + size + '" value="' + size + '"><label for="size_' + size + '">' + size + '</label></span>'
                        );
                    }
                }

                // Hide loader on success
                modal.find('.loader').hide();

                // Mobile layout fix
                if (window.innerWidth <= 767) {
                    if (modal.find('.product-details').length) {
                        modal.find('.product-details').children().unwrap();
                    }
                    setTimeout(function() {
                        var productElements = modal.find('.product_name, .sel_org_price, .size, .addtocart_moreinfo');
                        if (productElements.length > 0) {
                            productElements.wrapAll('<div class="product-details"></div>');
                        }
                    }, 100);
                }

                if (modal.find('.product_slider').length && $.fn.slick) {
                    modal.find('.product_slider').slick({
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerMode: true,
                        focusOnSelect: true,
                        dots: true,
                        arrows: false,
                        adaptiveHeight: false,
                        responsive: [
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1,
                                    slidesToScroll: 1,
                                    centerMode: false,
                                    focusOnSelect: true,
                                    dots: true,
                                    arrows: false,
                                    adaptiveHeight: false
                                }
                            }
                        ]
                    });
                }
            },
            error: function() {
                $('#productDetail_modal').find('.loader').hide();
            }
        });
    });

    // ─── Single Product Page Reel Slider ───
    $(document).ready(function () {
        if ($(".singlePagereelUpSlider").length && $.fn.slick) {
            $(".singlePagereelUpSlider").slick({
                slidesToShow: 2,
                slidesToScroll: 1,
                spaceBetween: 10,
                responsive: [
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        }
    });

    // ─── Pause video when modal is hidden (Bootstrap 5 event) ───
    // Bootstrap 5 dispatches native events, so we need to bind directly to the element.
    // Since the modal may be rendered in wp_footer after this script, we use a one-time setup.
    var _modalListenerBound = false;
    function bindModalHiddenListener() {
        if (_modalListenerBound) return;
        var el = document.getElementById('productDetail_modal');
        if (el) {
            _modalListenerBound = true;
            el.addEventListener('hidden.bs.modal', function () {
                $(el).find('video').each(function() {
                    this.pause();
                });
                // Reset loader for next open
                $(el).find('.loader').show();
            });
        }
    }

    // Try binding immediately, and also on DOMContentLoaded and window load as fallbacks
    bindModalHiddenListener();
    $(document).ready(function() { bindModalHiddenListener(); });
    $(window).on('load', function() { bindModalHiddenListener(); });

})(jQuery);
