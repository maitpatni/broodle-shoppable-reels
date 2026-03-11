jQuery("body").on("click", ".toggle-head", function () {
    jQuery(".toggle-body").toggleClass("d-none");
})
jQuery("#productDetail_modal .close").on("click", function () {
    jQuery("#productDetail_modal").modal("hide");
    jQuery('#productDetail_modal').find('.loader').show();
});
jQuery("body").on("click", ".option_item", function () {
    jQuery(".option_item").removeClass("active");
    jQuery(this).addClass("active");
    $size = jQuery(this).find("input").val();
    $addtocart = jQuery(".productDataSide .addtocart_moreinfo .add-to-cart").attr("href");
    $newarray = $addtocart.split("?");
    if ($newarray.length == 3) {
        $newarray.push(`attribute_size=${$size}`);
    } else {
        $newarray[3] = `attribute_size=${$size}`;
    }
    jQuery(".productDataSide .addtocart_moreinfo .add-to-cart").attr("href", $newarray.join("?"));
})

jQuery(document).ready(function () {
    jQuery('.reelUpSlider').slick({
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
})

jQuery("body").on('click', '.reel_product', function () {
    jQuery('#productDetail_modal').modal('show');
    $product_id = jQuery(this).data('product_id');
    $reel_id = jQuery(this).data('reel_id');

    // Perform AJAX request
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: broodle_sr_ajax.ajax_url,
        data: {
            action: "broodle_sr_create_user_form_ajax",
            product_id: $product_id,
            reel_id: $reel_id,
            broodle_sr_nonce: broodle_sr_ajax.nonce
        },
        success: function (response) {

            jQuery('#productDetail_modal').find('.reel').html(response.reel_data);

            $product_images = response.product.product_images;
            jQuery('#productDetail_modal').find('.product_slider').remove();
            jQuery('#productDetail_modal').find('.productDataSide').prepend($product_images);

            // Build price display based on whether product is on sale
            var priceHtml = '';
            var sellingPrice = parseFloat(response.product.selling_price) || 0;
            var originalPrice = parseFloat(response.product.original_price) || 0;
            
            if (sellingPrice > 0 && originalPrice > 0 && sellingPrice < originalPrice) {
                priceHtml = `<div class="selling_price">₹<span>${sellingPrice.toFixed(2)}</span></div><div class="regular_price">₹<del><span>${originalPrice.toFixed(2)}</span></del></div>`;
            } else {
                priceHtml = `<div class="selling_price">₹<span>${originalPrice.toFixed(2)}</span></div>`;
            }
            jQuery('#productDetail_modal').find('.sel_org_price').html(priceHtml);
            jQuery('#productDetail_modal').find('.addtocart_moreinfo').html(
                `<a href="${response.product.product_url}" class="more-info">More info</a><a href="${response.product.add_to_cart}" class="add-to-cart">Add to Cart</a>`
            );
            jQuery('#productDetail_modal').find('.product_name').html(
                `<h5>${response.product.product_name}</h5>`);

            $sizeOption = response.product.product_attributes.size;

            jQuery('#productDetail_modal').find('.size .option').remove();
            jQuery('#productDetail_modal').find('.size p').remove();

            if ($sizeOption) {
                jQuery('#productDetail_modal').find('.size').html(
                    '<P>Size:</P><div class="option"></div>');
                for (const size of $sizeOption) {
                    jQuery('#productDetail_modal').find('.size .option').append(
                        `<span class="option_item"><input type="radio" class="size" name="size_${size}" id="size" value="${size}"><label for="size_${size}">${size}</label></span>`
                    );
                }
            }

            // loader close on success
            jQuery('#productDetail_modal').find('.loader').hide();

            // Mobile layout fix
            if (window.innerWidth <= 767) {
                if (jQuery('#productDetail_modal').find('.product-details').length) {
                    jQuery('#productDetail_modal').find('.product-details').children().unwrap();
                }
                setTimeout(function() {
                    var productElements = jQuery('#productDetail_modal').find('.product_name, .sel_org_price, .size, .addtocart_moreinfo');
                    if (productElements.length > 0) {
                        productElements.wrapAll('<div class="product-details"></div>');
                    }
                }, 100);
            }

            // Show the modal after initializing the slider
            jQuery('.product_slider').slick({
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
    });
});

jQuery("body").on('click', '.relproductModel', function () {
    jQuery('#productDetail_modal').modal('show');
    jQuery('#productDetail_modal').find('.loader').show();
    $product_id = jQuery(this).data('product_id');
    $reel_id = jQuery(this).data('reel_id');

    // Perform AJAX request
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: broodle_sr_ajax.ajax_url,
        data: {
            action: "broodle_sr_create_user_form_ajax",
            product_id: $product_id,
            reel_id: $reel_id,
            broodle_sr_nonce: broodle_sr_ajax.nonce
        },
        success: function (response) {

            jQuery('#productDetail_modal').find('.reel').html(response.reel_data);

            $product_images = response.product.product_images;
            jQuery('#productDetail_modal').find('.product_slider').remove();
            jQuery('#productDetail_modal').find('.productDataSide').prepend($product_images);

            var priceHtml = '';
            var sellingPrice = parseFloat(response.product.selling_price) || 0;
            var originalPrice = parseFloat(response.product.original_price) || 0;
            
            if (sellingPrice > 0 && originalPrice > 0 && sellingPrice < originalPrice) {
                priceHtml = `<div class="selling_price">₹<span>${sellingPrice.toFixed(2)}</span></div><div class="regular_price">₹<del><span>${originalPrice.toFixed(2)}</span></del></div>`;
            } else {
                priceHtml = `<div class="selling_price">₹<span>${originalPrice.toFixed(2)}</span></div>`;
            }
            jQuery('#productDetail_modal').find('.sel_org_price').html(priceHtml);
            jQuery('#productDetail_modal').find('.addtocart_moreinfo').html(
                `<a href="${response.product.product_url}" class="more-info">More info</a><a href="${response.product.add_to_cart}" class="add-to-cart">Add to Cart</a>`
            );
            jQuery('#productDetail_modal').find('.product_name').html(
                `<h5>${response.product.product_name}</h5>`);

            $sizeOption = response.product.product_attributes.size;

            jQuery('#productDetail_modal').find('.size .option').remove();
            jQuery('#productDetail_modal').find('.size p').remove();

            if ($sizeOption) {
                jQuery('#productDetail_modal').find('.size').html(
                    '<P>Size:</P><div class="option"></div>');
                for (const size of $sizeOption) {
                    jQuery('#productDetail_modal').find('.size .option').append(
                        `<span class="option_item"><input type="radio" class="size" name="size_${size}" id="size" value="${size}"><label for="size_${size}">${size}</label></span>`
                    );
                }
            }

            // Hide loader on success
            jQuery('#productDetail_modal').find('.loader').hide();

            // Mobile layout fix
            if (window.innerWidth <= 767) {
                if (jQuery('#productDetail_modal').find('.product-details').length) {
                    jQuery('#productDetail_modal').find('.product-details').children().unwrap();
                }
                setTimeout(function() {
                    var productElements = jQuery('#productDetail_modal').find('.product_name, .sel_org_price, .size, .addtocart_moreinfo');
                    if (productElements.length > 0) {
                        productElements.wrapAll('<div class="product-details"></div>');
                    }
                }, 100);
            }

            jQuery('.product_slider').slick({
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
    });
});

jQuery(document).ready(function () {
    jQuery(".singlePagereelUpSlider").slick({
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
});

// Pause video when popup is closed
jQuery('#productDetail_modal').on('hidden.bs.modal', function () {
    jQuery(this).find('video').each(function() {
        this.pause();
    });
});
