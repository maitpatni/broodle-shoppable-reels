jQuery(document).ready(function ($) {
    // Function to open WordPress Media Uploader
    function openMediaUploader(callback) {
        var mediaUploader = wp.media({
            title: 'Select Video',
            multiple: false,
            library: {
                type: 'video'
            },
            button: {
                text: 'Select'
            }
        });

        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            jQuery('.newreeluploadvideo').val(attachment.url);
            jQuery('.newreeluploadvideo_frame source').attr('src', attachment.url);
            // callback(attachment.url);
        });

        mediaUploader.open();
    }

    // Event handler for the select-media-video button
    jQuery('.select-media-video').on('click', function (e) {
        e.preventDefault();

        var targetInput = jQuery(this).data('target');

        openMediaUploader(function (url) {
            // Set the selected video URL to the corresponding input field
            jQuery('.' + targetInput).val(url);

            // You can also update the video tag or any other related elements as needed
        });
    });
});

function cloneDiv3() {
    let pluginsPath = jQuery('#pluginsPath').text();
    var random_num = Math.floor((Math.random() * 10000) + 1);

    jQuery(".custom_img_box_wrap3:last").clone().appendTo(".pkg_img_wrap3");

    jQuery(".custom_img_box_wrap3:last").find(".upload_image_button").attr('src', `${pluginsPath}/cstm_plugin/assets/img/placeholder.jpg`);

    var data_class1 = jQuery(".custom_img_box_wrap3:last").find(".upload_image_button").attr('data-class1');
    jQuery(".custom_img_box_wrap3:last").find(".upload_image_button").removeClass(data_class1);
    jQuery(".custom_img_box_wrap3:last").find(".upload_image_button").addClass('upload_image_src' + random_num);

    jQuery(".custom_img_box_wrap3:last").find(".second_field_image").removeClass(data_class1);
    jQuery(".custom_img_box_wrap3:last").find(".second_field_image").addClass('upload_image' + random_num);

    jQuery(".custom_img_box_wrap3:last").find(".upload_image_button").attr('data-class', 'upload_image' + random_num);
    jQuery(".custom_img_box_wrap3:last").find(".upload_image_button").attr('data-class1', 'upload_image_src' + random_num);

    jQuery(".custom_img_box_wrap3:last").find(".second_field_image").val('');

    var matched = jQuery(".pkg_img_wrap3 .custom_img_box_wrap3");

    if (matched.length > 1) {

        jQuery(".pkg_img_wrap3 .custom_price_btn_remove").show();

    }



}
function removeDiv3(obj) {

    jQuery(obj).parent('div').parent('div').remove();

    var matched = jQuery(".pkg_img_wrap3 .custom_img_box_wrap3");



    if (matched.length == 1) {

        jQuery(".pkg_img_wrap3 .custom_price_btn_remove").hide();

    }

}

jQuery(document).ready(function ($) {

    var matched2 = jQuery(".pkg_img_wrap2 .custom_img_box_wrap2");
    if (matched2.length > 1) {
        jQuery(".pkg_img_wrap2 .custom_price_btn_remove").show();
    } else {
        jQuery(".pkg_img_wrap2 .custom_price_btn_remove").hide();
    }

    var matched3 = jQuery(".pkg_img_wrap3 .custom_img_box_wrap3");

    if (matched3.length > 1) {
        jQuery(".pkg_img_wrap3 .custom_price_btn_remove").show();
    } else {
        jQuery(".pkg_img_wrap3 .custom_price_btn_remove").hide();
    }

    // Uploading files
    var file_frame;
    jQuery(document).on('click', '.upload_image_button', function (event) {


        event.preventDefault();

        var class_nm = jQuery(this).attr("data-class");
        var class_nm1 = jQuery(this).attr("data-class1");




        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text'),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });


        // When an image is selected, run a callback.
        file_frame.on('select', function (data) {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            // Do something with attachment.id and/or attachment.url here
            if (attachment.url != "") {
                // jQuery(".upload_image").val(attachment.url);
                jQuery("." + class_nm).val(attachment.url);
                jQuery("." + class_nm1).find('source').attr('src', attachment.url);
                jQuery("." + class_nm1)[0].load();
                jQuery("." + class_nm1)[0].play();


            }

        });

        // Finally, open the modal
        file_frame.open();
    });

    jQuery(document).on('click', '.video_box svg', function () {
        jQuery(this).parent('div').find('.medium_video').val('');
        jQuery(this).parent('div').find('source').attr('src', '');
        jQuery(this).parent('div').find('video')[0].load();
    });

});