(function ($) {
    'use strict';
    $(document).ready(function ($) {
        if (typeof ere_property_vars !== "undefined") {
            var ajax_url = ere_property_vars.ajax_url,
                googlemap_zoom_level=ere_property_vars.googlemap_zoom_level,
                upload_nonce = ere_property_vars.upload_nonce,
                fileTypeTitle = ere_property_vars.fileTypeTitle,
                msg_digits = ere_property_vars.msg_digits,
                max_property_images = ere_property_vars.max_property_images,
                image_max_file_size = ere_property_vars.image_max_file_size;

            var floor_name_text = ere_property_vars.floor_name_text,
                floor_size_text = ere_property_vars.floor_size_text,
                floor_size_postfix_text = ere_property_vars.floor_size_postfix_text,
                floor_bedrooms_text = ere_property_vars.floor_bedrooms_text,
                floor_bathrooms_text = ere_property_vars.floor_bathrooms_text,
                floor_price_text = ere_property_vars.floor_price_text,
                floor_price_postfix_text = ere_property_vars.floor_price_postfix_text,
                floor_image_text = ere_property_vars.floor_image_text,
                floor_description_text = ere_property_vars.floor_description_text,
                floor_upload_text = ere_property_vars.floor_upload_text;

            var property_title = ere_property_vars.property_title,
                property_price = ere_property_vars.property_price,
                property_type = ere_property_vars.property_type,
                property_status = ere_property_vars.property_status,
                property_labels = ere_property_vars.property_labels,
                property_price_postfix = ere_property_vars.property_price_postfix,
                property_bedrooms = ere_property_vars.property_bedrooms,
                property_bathrooms = ere_property_vars.property_bathrooms,
                property_size = ere_property_vars.property_size,
                property_land = ere_property_vars.property_land,
                property_garage = ere_property_vars.property_garage,
                property_year = ere_property_vars.property_year,
                property_address = ere_property_vars.property_address,
                ere_metabox_prefix = ere_property_vars.ere_metabox_prefix;

            var ere_validation = function (field_required) {
                return (field_required==1);
            };
            /* Validate Submit Property Form */

            $('#submit_property_form').validate({
                ignore: ":not(select:hidden, input:visible, textarea:visible)",
                rules: {
                    property_title: {
                        required: ere_validation(property_title)
                    },
                    property_price: {
                        required: ere_validation(property_price),
                        number: true
                    },
                    property_type: {
                        required: ere_validation(property_type)
                    },
                    property_labels: {
                        required: ere_validation(property_labels)
                    },
                    property_price_postfix: {
                        required: ere_validation(property_price_postfix)
                    },
                    property_size: {
                        required: ere_validation(property_size),
                        number: true
                    },
                    property_land: {
                        required: ere_validation(property_land),
                        number: true
                    },
                    property_bedrooms: {
                        required: ere_validation(property_bedrooms),
                        number: true
                    },
                    property_bathrooms: {
                        required: ere_validation(property_bathrooms),
                        number: true
                    },
                    property_garage: {
                        required: ere_validation(property_garage),
                        number: true
                    },
                    property_year: {
                        required: ere_validation(property_year),
                        number: true
                    },
                    property_map_address: {
                        required: ere_validation(property_address)
                    }
                },
                messages: {
                    property_title: "",
                    property_des: "",
                    property_price: "",
                    property_bedrooms: "",
                    property_bathrooms: "",
                    property_size: "",
                    property_map_address: "",
                    property_type: "",
                    property_labels: "",
                    property_price_postfix: "",
                    property_land: "",
                    property_garage: "",
                    property_year: ""
                }
            });

            var ere_geocomplete_map = function ()
            {
                var property_form=$('input[name="property_form"]').val();
                $("#geocomplete").geocomplete({
                    map: ".map_canvas",
                    details: "form",
                    types: ["geocode", "establishment"],
                    mapOptions: {
                        zoom: parseInt(googlemap_zoom_level)
                    },
                    markerOptions: {
                        draggable: true
                    }
                }).one("geocode:result", function(){

                });
                $("#geocomplete").bind("geocode:dragged", function (event, latLng) {
                    $("input[name=lat]").val(latLng.lat());
                    $("input[name=lng]").val(latLng.lng());
                    $("#reset").show();
                });
                $("#reset").on('click', function () {
                    $("#geocomplete").geocomplete("resetMarker");
                    $("#reset").hide();
                    return false;
                });

                $("#find").on('click', function (e) {
                    e.preventDefault();
                    $("#geocomplete").trigger("geocode");
                });
            };
            ere_geocomplete_map();

            $("input[name='agent_display_option']").on('change', function () {
                $("select[name='property_agent']").hide();
                if ($(this).val() == 'other_info') {
                    $("#property_other_contact").slideDown('slow');
                }
                else {
                    $("#property_other_contact").slideUp('slow');
                }
            });
            /* ------------------------------------------------------------------------ */
            /*	Property additional Features
             /* ------------------------------------------------------------------------ */
            var ere_execute_additional_order = function () {
                var $i = 0;
                $('tr', '#ere_additional_details').each(function () {
                    var input_title = $('input[name*="additional_feature_title"]', $(this)),
                        input_value = $('input[name*="additional_feature_value"]', $(this));
                    input_title.attr('name', 'additional_feature_title[' + $i + ']');
                    input_title.attr('id', 'additional_feature_title_' + $i);
                    input_value.attr('name', 'additional_feature_value[' + $i + ']');
                    input_value.attr('id', 'additional_feature_value_' + $i);
                    $i++;
                });
            };
            $('#ere_additional_details').sortable({
                revert: 100,
                placeholder: "detail-placeholder",
                handle: ".sort-additional-row",
                cursor: "move",
                stop: function (event, ui) {
                    ere_execute_additional_order();
                }
            });

            $('.add-additional-feature').on('click', function (e) {
                e.preventDefault();
                var row_num = $(this).data("increment") + 1;
                $(this).data('increment', row_num);
                $(this).attr({
                    "data-increment": row_num
                });

                var new_feature = '<tr>' +
                    '<td class="action-field">' +
                    '<span class="sort-additional-row"><i class="fa fa-navicon"></i></span>' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control" type="text" name="additional_feature_title[' + row_num + ']" id="additional_feature_title_' + row_num + '" value="">' +
                    '</td>' +
                    '<td>' +
                    '<input class="form-control" type="text" name="additional_feature_value[' + row_num + ']" id="additional_feature_value_' + row_num + '" value="">' +
                    '</td>' +
                    '<td>' +
                    '<span data-remove="' + row_num + '" class="remove-additional-feature"><i class="fa fa-remove"></i></span>' +
                    '</td>' +
                    '</tr>';
                $('#ere_additional_details').append(new_feature);
                ere_remove_additional_feature();
            });

            var ere_remove_additional_feature = function () {
                $('.remove-additional-feature').on('click', function (event) {
                    event.preventDefault();
                    var $this = $(this),
                        parent = $this.closest('.additional-block'),
                        button_add = parent.find('.add-additional-feature'),
                        increment = parseInt(button_add.data('increment')) - 1;

                    $this.closest('tr').remove();
                    button_add.data('increment', increment);
                    button_add.attr('data-increment', increment);
                    ere_execute_additional_order();
                });
            };
            ere_remove_additional_feature();

            /* ------------------------------------------------------------------------ */
            /*	Floors
             /* ------------------------------------------------------------------------ */
            var ere_execute_floor_order = function () {
                var $i = 0;
                $('tr', '#ere_floors').each(function () {
                    var label_name = $('label[for*="' + ere_metabox_prefix + 'floor_name_"]', $(this)),
                        input_name = $('input[name*="' + ere_metabox_prefix + 'floor_name"]', $(this)),
                        label_price = $('label[for*="' + ere_metabox_prefix + 'floor_price_"]', $(this)),
                        input_price = $('input[name*="' + ere_metabox_prefix + 'floor_price"]', $(this)),
                        label_price_postfix = $('label[for*="' + ere_metabox_prefix + 'floor_price_postfix_"]', $(this)),
                        input_price_postfix = $('input[name*="' + ere_metabox_prefix + 'floor_price_postfix"]', $(this)),
                        label_size = $('label[for*="' + ere_metabox_prefix + 'floor_size_"]', $(this)),
                        input_size = $('input[name*="' + ere_metabox_prefix + 'floor_size"]', $(this)),
                        label_size_postfix = $('label[for*="' + ere_metabox_prefix + 'floor_size_postfix_"]', $(this)),
                        input_size_postfix = $('input[name*="' + ere_metabox_prefix + 'floor_size_postfix"]', $(this)),
                        label_bedrooms = $('label[for*="' + ere_metabox_prefix + 'floor_bedrooms_"]', $(this)),
                        input_bedrooms = $('input[name*="' + ere_metabox_prefix + 'floor_bedrooms"]', $(this)),
                        label_bathrooms = $('label[for*="' + ere_metabox_prefix + 'floor_bathrooms_"]', $(this)),
                        input_bathrooms = $('input[name*="' + ere_metabox_prefix + 'floor_bedrooms"]', $(this)),
                        label_image_url = $('label[for*="' + ere_metabox_prefix + 'floor_image_url_"]', $(this)),
                        input_image_url = $('input[id*="' + ere_metabox_prefix + 'floor_image_url"]', $(this)),
                        input_image_id = $('input[id*="' + ere_metabox_prefix + 'floor_image_id"]', $(this)),
                        input_image_button = $('button[class*="ere_floorsImg"]', $(this)),
                        label_description = $('label[for*="' + ere_metabox_prefix + 'floor_description_"]', $(this)),
                        input_description = $('input[id*="' + ere_metabox_prefix + 'floor_description"]', $(this));

                    label_name.attr('for', ere_metabox_prefix + 'floor_name_' + $i);
                    input_name.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_name]');
                    input_name.attr('id', ere_metabox_prefix + 'floor_name_' + $i);

                    label_price.attr('for', ere_metabox_prefix + 'floor_price_' + $i);
                    input_price.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_price]');
                    input_price.attr('id', ere_metabox_prefix + 'floor_price_' + $i);

                    label_price_postfix.attr('for', ere_metabox_prefix + 'floor_price_postfix_' + $i);
                    input_price_postfix.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_price_postfix]');
                    input_price_postfix.attr('id', ere_metabox_prefix + 'floor_price_postfix_' + $i);

                    label_size.attr('for', ere_metabox_prefix + 'floor_size_' + $i);
                    input_size.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_size]');
                    input_size.attr('id', ere_metabox_prefix + 'floor_size_' + $i);

                    label_size_postfix.attr('for', ere_metabox_prefix + 'floor_size_postfix_' + $i);
                    input_size_postfix.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_size_postfix]');
                    input_size_postfix.attr('id', ere_metabox_prefix + 'floor_size_postfix_' + $i);

                    label_bedrooms.attr('for', ere_metabox_prefix + 'floor_bedrooms_' + $i);
                    input_bedrooms.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_bedrooms]');
                    input_bedrooms.attr('id', ere_metabox_prefix + 'floor_bedrooms_' + $i);

                    label_bathrooms.attr('for', ere_metabox_prefix + 'floor_bathrooms_' + $i);
                    input_bathrooms.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_bathrooms]');
                    input_bathrooms.attr('id', ere_metabox_prefix + 'floor_bathrooms_' + $i);

                    label_image_url.attr('for', ere_metabox_prefix + 'floor_image_url_' + $i);
                    input_image_url.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_image][url]');
                    input_image_url.attr('id', ere_metabox_prefix + 'floor_image_url_' + $i);

                    input_image_id.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_image][id]');
                    input_image_id.attr('id', ere_metabox_prefix + 'floor_image_id_' + $i);

                    input_image_button.attr('id', $i);

                    label_description.attr('for', ere_metabox_prefix + 'floor_description_' + $i);
                    input_description.attr('name', ere_metabox_prefix + 'floors[' + $i + '][' + ere_metabox_prefix + 'floor_description]');
                    input_description.attr('id', ere_metabox_prefix + 'floor_description_' + $i);
                    $i++;
                });
            };
            $('#ere_floors').sortable({
                revert: 100,
                placeholder: "detail-placeholder",
                handle: ".sort-floors-row",
                cursor: "move",
                stop: function (event, ui) {
                    ere_execute_floor_order();
                }
            });

            $('#add-floors-row').on('click', function (e) {
                e.preventDefault();

                var row_num = $(this).data("increment") + 1;
                $(this).data('increment', row_num);
                $(this).attr({
                    "data-increment": row_num
                });

                var new_floor = '' +
                    '<tr>' +
                    '<td class="row-sort">' +
                    '<span class="sort sort-floors-row"><i class="fa fa-navicon"></i></span>' +
                    '</td>' +
                    '<td class="sort-middle">' +
                    '<div class="sort-inner-block">' +
                    '<div class="row">' +
                    '<div class="col-sm-12">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_name_' + row_num + '">' + floor_name_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_name]" type="text" id="' + ere_metabox_prefix + 'floor_name_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_price_' + row_num + '">' + floor_price_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_price]" type="text" id="' + ere_metabox_prefix + 'floor_price_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_price_postfix_' + row_num + '">' + floor_price_postfix_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_price_postfix]" type="text" id="' + ere_metabox_prefix + 'floor_price_postfix_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_size_' + row_num + '">' + floor_size_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_size]" type="text" id="' + ere_metabox_prefix + 'floor_size_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_size_postfix_' + row_num + '">' + floor_size_postfix_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_size_postfix]" type="text" id="' + ere_metabox_prefix + 'floor_size_postfix_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_bedrooms_' + row_num + '">' + floor_bedrooms_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_bedrooms]" type="text" id="' + ere_metabox_prefix + 'floor_bedrooms_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_bathrooms_' + row_num + '">' + floor_bathrooms_text + '</label>' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_bathrooms]" type="text" id="' + ere_metabox_prefix + 'floor_bathrooms_' + row_num + '" class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_image_url_' + row_num + '">' + floor_image_text + '</label>' +
                    '<div id="ere-floor-plupload-container" class="file-upload-block">' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_image][url]" type="text" id="' + ere_metabox_prefix + 'floor_image_url_' + row_num + '" class="ere_floor_image_url form-control">' +
                    '<input name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_image][id]" type="hidden" id="' + ere_metabox_prefix + 'floor_image_id_' + row_num + '" class="ere_floor_image_id">' +
                    '<button id="' + row_num + '" style="position: absolute" title="' + floor_upload_text + '" class="ere_floorsImg"><i class="fa fa-file-image-o"></i></button>' +
                    '</div>' +
                    '<div id="ere-floor-errors-log"></div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-sm-6">' +
                    '<div class="form-group">' +
                    '<label for="' + ere_metabox_prefix + 'floor_description_' + row_num + '">' + floor_description_text + '</label>' +
                    '<textarea name="' + ere_metabox_prefix + 'floors[' + row_num + '][' + ere_metabox_prefix + 'floor_description]" rows="4" id="' + ere_metabox_prefix + 'floor_description_' + row_num + '" class="form-control"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '<td class="row-remove">' +
                    '<span data-remove="' + row_num + '" class="remove-floors-row remove"><i class="fa fa-remove"></i></span>' +
                    '</td>' +
                    '</tr>';

                $('#ere_floors').append(new_floor);
                ere_remove_floor();
                ere_floor_images();
            });

            var ere_remove_floor = function () {
                $('.remove-floors-row').on('click', function (event) {
                    event.preventDefault();
                    var $this = $(this),
                        parent = $this.closest('.add-sort-table'),
                        button_add = parent.find('#add-floors-row'),
                        increment = parseInt(button_add.data('increment')) - 1;

                    $this.closest('tr').remove();
                    button_add.data('increment', increment);
                    button_add.attr('data-increment', increment);
                    ere_execute_floor_order();
                });
            };
            ere_remove_floor();

            var ere_execute_floor = function () {
                var $this = $('[name="floors_enable"][checked]', '.property-floors-control'),
                    enable_val = $this.val(),
                    floor_data = $this.closest('.property-floors-control').next('.property-floors-data');
                if (enable_val == 1) {
                    floor_data.slideDown('slow');
                } else if (enable_val == 0) {
                    floor_data.slideUp('slow');
                }
                $('input[name="floors_enable"]', '.property-floors-control').each(function () {
                    $(this).on('click', function () {
                        enable_val = $(this).val();
                        if (enable_val == 1) {
                            floor_data.slideDown('slow');
                        } else if (enable_val == 0) {
                            floor_data.slideUp('slow');
                        }
                    });
                });
            };
            ere_execute_floor();
            // Property Thumbnails
            var ere_property_gallery_event = function () {

                // Set Featured Image
                $('.icon-featured', '.property-media').on('click', function () {

                    var $this = $(this);
                    var thumb_id = $this.data('attachment-id');
                    var icon = $this.find('i');

                    $('.gallery-thumb .featured_image_id').remove();
                    $('.gallery-thumb .icon-featured i').removeClass('fa-star').addClass('fa-star-o');

                    $this.closest('.gallery-thumb').append('<input type="hidden" class="featured_image_id" name="featured_image_id" value="' + thumb_id + '">');
                    icon.removeClass('fa-star-o').addClass('fa-star');
                });

                $('.icon-delete', '.property-media').on('click', function () {
                    var $this = $(this),
                        icon_delete = $this.children('i'),
                        thumbnail = $this.closest('.property-thumb'),
                        property_id = $this.data('property-id'),
                        thumb_id = $this.data('attachment-id');

                    icon_delete.addClass('fa-spinner fa-spin');

                    $.ajax({
                        type: 'post',
                        url: ajax_url,
                        dataType: 'json',
                        data: {
                            'action': 'ere_remove_property_thumbnail_ajax',
                            'property_id': property_id,
                            'thumbnail_id': thumb_id,
                            'removeNonce': upload_nonce
                        },
                        success: function (response) {
                            if (response.success) {
                                thumbnail.remove();
                                thumbnail.hide();
                            }
                            icon_delete.removeClass('fa-spinner fa-spin');
                        },
                        error: function () {
                            icon_delete.removeClass('fa-spinner fa-spin');
                        }
                    });
                });
            };

            ere_property_gallery_event();

            // Property Gallery images
            var ere_property_gallery_images = function () {

                $("#property-thumbs-container").sortable();

                /* initialize uploader */
                var uploader = new plupload.Uploader({
                    browse_button: 'select-images',          // this can be an id of a DOM element or the DOM element itself
                    file_data_name: 'property_upload_file',
                    container: 'ere-gallery-plupload-container',
                    drop_element: 'ere-gallery-plupload-container',
                    multi_selection: true,
                    url: ajax_url + "?action=ere_property_img_upload_ajax&nonce=" + upload_nonce,
                    filters: {
                        mime_types: [
                            {title: fileTypeTitle, extensions: "jpg,jpeg,gif,png"}
                        ],
                        max_file_size: image_max_file_size,
                        prevent_duplicates: true
                    }
                });
                uploader.init();

                uploader.bind('FilesAdded', function (up, files) {
                    var propertyThumb = "";
                    var maxfiles = max_property_images;
                    if (up.files.length > maxfiles) {
                        up.splice(maxfiles);
                        alert('no more than ' + maxfiles + ' file(s)');
                        return;
                    }
                    plupload.each(files, function (file) {
                        propertyThumb += '<div id="holder-' + file.id + '" class="col-sm-2 property-thumb"></div>';
                    });
                    document.getElementById('property-thumbs-container').innerHTML += propertyThumb;
                    up.refresh();
                    uploader.start();
                });

                uploader.bind('UploadProgress', function (up, file) {
                    document.getElementById("holder-" + file.id).innerHTML = '<span><i class="fa fa-spinner fa-spin"></i></span>';
                });

                uploader.bind('Error', function (up, err) {
                    document.getElementById('ere-gallery-errors-log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });

                uploader.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);

                    if (response.success) {

                        var $html =
                            '<figure class="gallery-thumb">' +
                            '<img src="' + response.url + '" alt="" />' +
                            '<div class="gallery-item-actions">' +
                            '<a class="icon icon-delete" data-property-id="' + 0 + '"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-trash-o"></i></a>' +
                            '<a class="icon icon-featured" data-property-id="' + 0 + '"  data-attachment-id="' + response.attachment_id + '" href="javascript:;" ><i class="fa fa-star-o"></i></a>' +
                            '<input type="hidden" class="property_image_ids" name="property_image_ids[]" value="' + response.attachment_id + '"/>' +
                            '<span style="display: none;" class="icon icon-loader"><i class="fa fa-spinner fa-spin"></i></span>' +
                            '</div>' +
                            '</figure>';

                        document.getElementById("holder-" + file.id).innerHTML = $html;
                        ere_property_gallery_event();
                    }
                });
            };
            ere_property_gallery_images();
            // Image 360
            var ere_image_360 = function () {

                var uploader_image_360 = new plupload.Uploader({
                    browse_button: 'select-images-360',
                    file_data_name: 'property_upload_file',
                    container: 'ere-image-360-plupload-container',
                    url: ajax_url + "?action=ere_property_img_upload_ajax&nonce=" + upload_nonce,
                    filters: {
                        mime_types: [
                            {title: fileTypeTitle, extensions: "jpg,jpeg,gif,png"}
                        ],
                        max_file_size: image_max_file_size,
                        prevent_duplicates: true
                    }
                });
                uploader_image_360.init();

                uploader_image_360.bind('FilesAdded', function (up, files) {
                    var maxfiles = max_property_images;
                    if (up.files.length > maxfiles) {
                        up.splice(maxfiles);
                        alert('no more than ' + maxfiles + ' file(s)');
                        return;
                    }
                    plupload.each(files, function (file) {

                    });
                    up.refresh();
                    uploader_image_360.start();
                });
                uploader_image_360.bind('Error', function (up, err) {
                    document.getElementById('ere-image-360-errors-log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });
                uploader_image_360.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);
                    if (response.success) {
                        $('.ere_image_360_url').val(response.full_image);
                        $('.ere_image_360_id').val(response.attachment_id);
                        var plugin_url=$('#ere-property-image-360-view').attr('data-plugin-url');
                        var _iframe='<iframe width="100%" height="200" scrolling="no" allowfullscreen src="'+ plugin_url +'public/assets/packages/vr-view/index.html?image='+ response.full_image+'"></iframe>';
                        $('#ere-property-image-360-view').html(_iframe);
                    }
                });
            };
            ere_image_360();
            // Floor image
            var ere_floor_images = function () {

                var uploader_floor = new plupload.Uploader({
                    browse_button: '0',
                    file_data_name: 'property_upload_file',
                    container: 'ere-floor-plupload-container',
                    url: ajax_url + "?action=ere_property_img_upload_ajax&nonce=" + upload_nonce,
                    filters: {
                        mime_types: [
                            {title: fileTypeTitle, extensions: "jpg,jpeg,gif,png"}
                        ],
                        max_file_size: image_max_file_size,
                        prevent_duplicates: true
                    }
                });
                uploader_floor.init();

                uploader_floor.bind('FilesAdded', function (up, files) {
                    var maxfiles = max_property_images;
                    if (up.files.length > maxfiles) {
                        up.splice(maxfiles);
                        alert('no more than ' + maxfiles + ' file(s)');
                        return;
                    }
                    plupload.each(files, function (file) {

                    });
                    up.refresh();
                    uploader_floor.start();
                });
                uploader_floor.bind('Error', function (up, err) {
                    document.getElementById('ere-floor-errors-log').innerHTML += "<br/>" + "Error #" + err.code + ": " + err.message;
                });
                var current_button_id;
                uploader_floor.bind('FileUploaded', function (up, file, ajax_response) {
                    var response = $.parseJSON(ajax_response.response);
                    if (response.success) {
                        $('#' + current_button_id).parents('tr').find('.ere_floor_image_url').val(response.full_image);
                        $('#' + current_button_id).parents('tr').find('.ere_floor_image_id').val(response.attachment_id);
                    }
                });
                $('.ere_floorsImg').mouseenter(function () {
                    current_button_id = $(this).attr('id');
                    uploader_floor.setOption("browse_button", $(this).attr('id'));
                    uploader_floor.refresh();
                });
            };
            ere_floor_images();

            var ere_get_states_by_country = function () {
                if( $(".ere-property-country-ajax").length )
                {
                    var selected_country = $(".ere-property-country-ajax").val();
                    if(selected_country!='')
                    {
                        $.ajax({
                            type: "POST",
                            url: ajax_url,
                            data: {
                                'action': 'ere_get_states_by_country_ajax',
                                'country': selected_country,
                                'type': 0
                            },
                            success: function (response) {
                                $(".ere-property-state-ajax").html(response);
                                var val_selected = $(".ere-property-state-ajax").attr('data-selected');
                                if(val_selected!='undefined')
                                {
                                    $(".ere-property-state-ajax").val(val_selected);
                                }
                            }
                        });
                    }
                }
            };
            ere_get_states_by_country();

            $(".ere-property-country-ajax").on('change', function (){
                ere_get_states_by_country();
            });

            var ere_get_cities_by_state = function () {
                if( $(".ere-property-state-ajax").length )
                {
                    var selected_state = $(".ere-property-state-ajax").val();
                    if(selected_state!='')
                    {
                        $.ajax({
                            type: "POST",
                            url: ajax_url,
                            data: {
                                'action': 'ere_get_cities_by_state_ajax',
                                'state': selected_state,
                                'type': 0
                            },
                            success: function (response) {
                                $(".ere-property-city-ajax").html(response);
                                var val_selected = $(".ere-property-city-ajax").attr('data-selected');
                                if(val_selected!='undefined')
                                {
                                    $(".ere-property-city-ajax").val(val_selected);
                                }
                            }
                        });
                    }
                }
            };
            ere_get_cities_by_state();
            
            $(".ere-property-state-ajax").on('change', function (){
                ere_get_cities_by_state();
            });

            var ere_get_neighborhoods_by_city = function () {
                if( $(".ere-property-city-ajax").length )
                {
                    var selected_city = $(".ere-property-city-ajax").val();
                    if(selected_city!='')
                    {
                        $.ajax({
                            type: "POST",
                            url: ajax_url,
                            data: {
                                'action': 'ere_get_neighborhoods_by_city_ajax',
                                'city': selected_city,
                                'type': 0
                            },
                            success: function (response) {
                                $(".ere-property-neighborhood-ajax").html(response);
                                var val_selected = $(".ere-property-neighborhood-ajax").attr('data-selected');
                                if(val_selected!='undefined')
                                {
                                    $(".ere-property-neighborhood-ajax").val(val_selected);
                                }
                            }
                        });
                    }
                }
            };
            ere_get_neighborhoods_by_city();

            $(".ere-property-city-ajax").on('change', function (){
                ere_get_neighborhoods_by_city();
            });
        }
    });
    $(window).load(function () {
        $("#geocomplete").trigger("geocode");
    });
})(jQuery);