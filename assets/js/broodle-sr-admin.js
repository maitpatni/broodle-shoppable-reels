jQuery(document).ready(function ($) {

    // ─── Searchable Select Dropdown for Primary Product ───
    function initSearchableSelect() {
        var $select = $('select.reelSliderProduct');
        if (!$select.length) return;

        // Hide original select
        $select.hide();

        // Build options data from the original select
        var options = [];
        $select.find('option').each(function () {
            options.push({
                value: $(this).val(),
                text: $(this).text(),
                selected: $(this).is(':selected')
            });
        });

        // Find currently selected
        var selectedOption = options.find(function (o) { return o.selected && o.value !== ''; });
        var displayText = selectedOption ? selectedOption.text : '— Select Product —';
        var isPlaceholder = !selectedOption;

        // Build the custom dropdown HTML
        var $wrapper = $('<div class="broodle-sr-searchable-select"></div>');
        var $trigger = $(
            '<div class="broodle-sr-select-trigger">' +
            '<span class="broodle-sr-select-text ' + (isPlaceholder ? 'placeholder' : '') + '">' + escapeHtml(displayText) + '</span>' +
            '<span class="broodle-sr-select-arrow"></span>' +
            '</div>'
        );
        var $dropdown = $(
            '<div class="broodle-sr-select-dropdown">' +
            '<div class="broodle-sr-select-search-wrap">' +
            '<input type="text" class="broodle-sr-select-search" placeholder="Search products..." autocomplete="off" />' +
            '</div>' +
            '<div class="broodle-sr-select-options"></div>' +
            '</div>'
        );

        var $optionsList = $dropdown.find('.broodle-sr-select-options');

        // Populate options
        $.each(options, function (i, opt) {
            if (opt.value === '') return; // skip placeholder option
            var selectedClass = opt.selected ? ' selected' : '';
            var $opt = $('<div class="broodle-sr-select-option' + selectedClass + '" data-value="' + escapeAttr(opt.value) + '">' + escapeHtml(opt.text) + '</div>');
            $optionsList.append($opt);
        });

        $wrapper.append($trigger).append($dropdown);
        $select.after($wrapper);

        // Toggle dropdown
        $trigger.on('click', function (e) {
            e.stopPropagation();
            var isOpen = $wrapper.hasClass('open');
            // Close all other open dropdowns
            $('.broodle-sr-searchable-select.open').removeClass('open');
            if (!isOpen) {
                $wrapper.addClass('open');
                $dropdown.find('.broodle-sr-select-search').val('').trigger('input').focus();
            }
        });

        // Search filtering
        $dropdown.find('.broodle-sr-select-search').on('input', function () {
            var query = $(this).val().toLowerCase();
            var hasVisible = false;
            $optionsList.find('.broodle-sr-select-option').each(function () {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(query) > -1) {
                    $(this).show();
                    hasVisible = true;
                } else {
                    $(this).hide();
                }
            });
            // Show/hide no results message
            $optionsList.find('.broodle-sr-select-no-results').remove();
            if (!hasVisible) {
                $optionsList.append('<div class="broodle-sr-select-no-results">No products found</div>');
            }
        });

        // Prevent search input click from closing
        $dropdown.find('.broodle-sr-select-search').on('click', function (e) {
            e.stopPropagation();
        });

        // Select an option
        $optionsList.on('click', '.broodle-sr-select-option', function (e) {
            e.stopPropagation();
            var val = String($(this).attr('data-value'));
            var text = $(this).text();

            // Update original select
            $select.val(val).trigger('change');

            // Update UI
            $optionsList.find('.broodle-sr-select-option').removeClass('selected');
            $(this).addClass('selected');
            $trigger.find('.broodle-sr-select-text').text(text).removeClass('placeholder');

            // Close dropdown
            $wrapper.removeClass('open');
        });

        // Close on outside click
        $(document).on('click', function (e) {
            if (!$wrapper[0].contains(e.target)) {
                $wrapper.removeClass('open');
            }
        });

        // Close on Escape key
        $(document).on('keydown', function (e) {
            if (e.key === 'Escape') {
                $wrapper.removeClass('open');
            }
        });
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function escapeAttr(str) {
        return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Initialize the searchable select
    initSearchableSelect();

    // ─── End Searchable Select ───

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

    jQuery(document).on('click', '.video_box svg, .broodle-sr-video-remove', function () {
        var container = jQuery(this).closest('.video_box, .broodle-sr-video-preview');
        container.find('.medium_video').val('');
        container.find('source').attr('src', '');
        var video = container.find('video');
        if (video.length) {
            video.get(0).load();
        }
        // If in new editor layout, show empty state
        if (container.hasClass('broodle-sr-video-preview')) {
            container.html(
                '<div class="broodle-sr-video-empty upload_image_src upload_image_button" data-class="upload_image" data-class1="upload_image_src">' +
                '<span class="dashicons dashicons-video-alt3"></span>' +
                '<span>Click to upload video</span>' +
                '</div>' +
                '<input class="upload_image medium_video newreeluploadvideo" type="hidden" name="medium_video" value="" />'
            );
        }
    });

});