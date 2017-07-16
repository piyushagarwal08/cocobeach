jQuery(document).ready(function ($) {
    "use strict";
    var ajax_url = ere_search_vars.ajax_url;
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
                        'type': 1
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
                        'type': 1
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
                        'type': 1
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

    $(window).resize(function(){
        ere_full_screen();
    });
    $(window).on('orientationchange', function () {
        ere_full_screen();
    });

    function ere_execute_url_search() {
        $('#search-btn', '.search-properties-form').on('click', function (e) {
            e.preventDefault();
            var search_form = $(this).closest('.search-properties-form'),
                search_url = search_form.data('href'),
                search_field = [],
                query_string = '?',
                advanced = $('[name="advanced"]').attr('value');
            if(search_url.indexOf('?') !== -1) {
                query_string = '&';
            }
            $('.search-field', search_form).each(function () {
                var $this = $(this),
                    field_name = $this.attr('name'),
                    current_value = $this.val(),
                    default_value = $this.data('default-value');
                if($this.closest('.search-advanced-info').length != 1 || advanced == '1') {
                    if (current_value != default_value) {
                        search_field[field_name] = current_value;
                    }
                }
            });
            $('.ere-sliderbar-filter', search_form).each(function () {
                var $this = $(this),
                    field_name_min = $this.find('.min-input-request').attr('name'),
                    field_name_max = $this.find('.max-input-request').attr('name'),
                    current_value_min = $this.find('.min-input-request').val(),
                    current_value_max = $this.find('.max-input-request').val(),
                    default_value_min = $this.data('min-default'),
                    default_value_max = $this.data('max-default');
                if($this.closest('.search-advanced-info').length != 1 || advanced == '1') {
                    if (current_value_min != default_value_min || current_value_max != default_value_max) {
                        search_field[field_name_min] = current_value_min;
                        search_field[field_name_max] = current_value_max;
                    }
                }
            });
            if(typeof(search_field['featured-search']) != 'undefined') {
                var other_feature = '';
                $('[name="other_feature"]', search_form).each(function () {
                    var $this = $(this),
                        value = $this.attr('value');
                    if ($this.is(':checked')) {
                        other_feature += value+";";
                    }
                });
                if(other_feature !== ''){
                    other_feature = other_feature.substring('0', other_feature.length - 1);
                    search_field['other_feature'] = other_feature;
                }
            }
            if(search_field !== []) {
                for (var k in search_field){
                    if (search_field.hasOwnProperty(k)) {
                        query_string += k+"="+search_field[k] + "&";
                    }
                }
            }
            query_string = query_string.substring('0', query_string.length - 1);
            window.location.href = search_url+query_string;
        });
    }
    ere_execute_url_search();

    function ere_check_scroll_result_by_title() {
        var $this = $('ul.listing-item-by-name'),
            max_height = 244,
            height = $this.height();
        if (height > max_height) {
            $this.css('overflow-y', 'scroll');
        } else {
            $this.css('overflow-y', 'auto');
        }
    }
    /* Setting full screen vertical search map */
    function ere_full_screen(){
        if($('.ere-search-properties.style-vertical').length > 0) {
            var $window_height = $(window).outerHeight(),
                admin_height = $('#wpadminbar').outerHeight();

            if (admin_height == null) {
                admin_height = 0;
            }
            var header_height = $('header').outerHeight(),
                footer_height = $('footer').outerHeight(),
                admin_bar_height = $('.wpadminbar').outerHeight(),
                map_height = $window_height - admin_height - header_height - footer_height - admin_bar_height;
            $('.ere-search-properties.style-vertical .ere-map-search').css('height', map_height);
            $('.ere-search-properties.style-vertical .ere-map-search .ere-map-result').css('height', map_height);
            $('.col-scroll-vertical').css({
                'height': map_height,
                'overflow-y': 'scroll',
                'overflow-x': 'hidden'
            });

            var $container = $('.owl-carousel', '.list-property-result-ajax'),
                $newElems = $('.property-item', $container);
            $container.trigger('destroy.owl.carousel');
            $container.css('opacity', 1);
            $container.imagesLoaded(function () {
                ERE.set_item_effect($newElems, 'hide');
                ERE_Carousel.owlCarousel();
                $newElems = $('.property-item', $container);
                ERE.set_item_effect($newElems, 'show');
            });
        }
    }
    ere_full_screen();


    /* Slider filter use jquery UI*/
    function ere_set_slider_filter(elm){
        var $container = elm,
            min = parseInt($container.attr('data-min-default')),
            max = parseInt($container.attr('data-max-default')),
            min_value = $container.attr('data-min'),
            max_value = $container.attr('data-max'),
            $sidebar_filter = $container.find('.sidebar-filter'),
            x, y;
        $sidebar_filter.slider({
            min: min,
            max: max,
            range: true,
            values: [min_value, max_value],
            slide: function (event, ui) {
                x = ui.values[0];
                y = ui.values[1];
                $container.attr('data-min', x);
                $container.attr('data-max', y);
                $container.find('input.min-input-request').attr('value', x);
                $container.find('input.max-input-request').attr('value', y);
                if ( $container.find('span').hasClass( "not-format" ) ) {
                    $container.find('span.min-value').html(x);
                    $container.find('span.max-value').html(y);
                }
                else
                {
                    $container.find('span.min-value:not(.not-format)').html(ERE.number_format(x));
                    $container.find('span.max-value:not(.not-format)').html(ERE.number_format(y));
                }
            },
            stop: function (event, ui) {
                var $this = $(event.target),
                    map_id = $this.parents('div.ere-search-properties').find('.ere-map-result').attr('id'),
                    map_text = '#'+map_id;
                if ($(map_text).length > 0) {
                    var current_form = $(this).parents('.search-properties-form');
                    ere_search_on_change(current_form,map_id);
                }
            }
        });
    }
    $(".ere-sliderbar-filter.ere-sliderbar-price").on('register.again', function () {
        $(".ere-sliderbar-filter.ere-sliderbar-price").each(function () {
            var slider_filter = $(this);
            ere_set_slider_filter(slider_filter);
        });
        $(".ere-sliderbar-filter.ere-sliderbar-area").each(function () {
            var slider_filter = $(this);
            ere_set_slider_filter(slider_filter);
        });
    });
    function ere_register_slider_filter() {
        $(".ere-sliderbar-filter").each(function (){
            var slider_filter = $(this);
            ere_set_slider_filter(slider_filter);
        });
    }
    ere_register_slider_filter();

    /* Setting value and display slider when value not is min or max*/
    function ere_set_slider_value() {
        $('.ere-sliderbar-filter').each(function(){
            var $this = $(this),
                min_default = $this.attr('data-min-default'),
                max_default = $this.attr('data-max-default'),
                min_value = $this.attr('data-min'),
                max_value = $this.attr('data-max'),
                left = (min_value - min_default)/(max_default - min_default)*100+'%',
                width = (max_value - min_value)/(max_default - min_default)*100+'%',
                left_max = (max_value - min_default)/(max_default - min_default)*100+'%';
            $this.find('.ui-slider-range.ui-corner-all.ui-widget-header').css({
                'left': left,
                'width': width
            });
            $this.find('.ui-slider-handle.ui-corner-all.ui-state-default').css('left',left);
            $this.find('.ui-slider-handle.ui-corner-all.ui-state-default:last-child').css('left',left_max);
        })
    }
    ere_set_slider_value();

    /*Click show hide advanced search and search*/
    $('.search-properties-form .advanced-search-button').each(function () {
        var button_click = $(this);
        button_click.on('click', function (event) {
            var $button = $(this),
                map_id = $button.parents('div.ere-search-properties').find('.ere-map-result').attr('id'),
                map_id_text = '#'+map_id,
                $container = $button.parent().parent().parent(),
                input = button_click.find('input[name="advanced"]'),
                status = input.attr('value');
            $container.find('.search-advanced-info').slideToggle();
            if (status == '0') {
                input.attr('value', '1');
            } else {
                input.attr('value', '0');
            }
            if ($(map_id_text).length > 0) {
                var current_form = $(this).parents('.search-properties-form');
                ere_search_on_change(current_form,map_id);
            }
        });
    });

    $('.other-featured .enable-featured .button-other-featured').each(function () {
        var button_click = $(this);
        button_click.on('click', function (event) {
            event.preventDefault();
            var $button = $(this),
                map_id = $button.parents('div.ere-search-properties').find('.ere-map-result').attr('id'),
                map_text = '#'+map_id,
                $enable_button = $button.parent(),
                $container = $button.parent().parent(),
                input = $('input[name="featured-search"]',$enable_button);
            $container.find('.featured-list').slideToggle();
            $button.toggleClass('show');
            if ($button.hasClass('show') == true) {
                $button.find('.hide-featured-text').removeClass('hide');
                $button.find('.show-featured-text').addClass('hide');
                input.attr('value', '1');
            } else {
                $button.find('.hide-featured-text').addClass('hide');
                $button.find('.show-featured-text').removeClass('hide');
                input.attr('value', '0');
            }
            if ($(map_text).length > 0) {
                var current_form = $(this).parents('.search-properties-form');
                ere_search_on_change(current_form,map_id);
            }
        });
    });

    if (typeof ere_search_vars !== "undefined") {
        /*=========================================================
         * Auto complete get text input and search after change text
         * =========================================================*/
        var keyword_auto_complete = ere_search_vars.keyword_auto_complete;
        var ere_auto_complete = function () {
            var ere_source = $.parseJSON(keyword_auto_complete);
            var ere_auto = $('input[name="keyword"]').autocomplete({
                source: ere_source,
                delay: 300,
                minLength: 1,
                change: function () {
                    var map_id = $(this).parents('div.ere-search-properties').find('.ere-map-result').attr('id');
                    var map_text = '#'+map_id;
                    if ($(map_text).length > 0) {
                        var current_form = $(this).parents('.search-properties-form');
                        ere_search_on_change(current_form,map_id);
                    }
                }
            });
            ere_auto.autocomplete('option', 'change');
        };
        ere_auto_complete();

        var ere_title_auto_complete = function(){
            var count_prop = 0,
                auto_complete_container = $('.ere-result-by-title');
            $('input[name="title"]').keyup(function(){
                var $this = $( this ),
                    ere_result_by_title = $this.parents().find( '.ere-result-by-title' );
                if ( $( this ).val().length >= 2 ) {
                    ere_result_by_title.fadeIn();
                    $.ajax({
                        type: 'POST',
                        url: ajax_url,
                        data: {
                            'action': 'ere_title_auto_complete_search',
                            'title': $( this ).val()
                        },
                        beforeSend: function( ) {
                            count_prop++;
                            if ( count_prop == 1 ) {
                                ere_result_by_title.html('<div class="processing-title"><i class="fa fa-spinner fa-spin fa-fw"></i></div>');
                            }
                        },
                        success: function(data) {
                            count_prop--;
                            if ( count_prop == 0 ) {
                                ere_result_by_title.show();
                                if( data != '' ) {
                                    ere_result_by_title.empty().html(data).bind();
                                    ere_check_scroll_result_by_title();
                                    ere_result_by_title.find('a.link-close').on('click',function(event){
                                        event.preventDefault();
                                        ere_result_by_title.fadeOut();
                                    });
                                }
                            }
                        },
                        error: function(xhr) {
                            count_prop--;
                            if ( count_prop == 0 ) {
                                ere_result_by_title.html('<div class="processing-title"><i class="fa fa-spinner fa-spin fa-fw"></i></div>');
                            }
                            var err = eval("(" + xhr.responseText + ")");
                            console.log(err.Message);
                        }
                    });
                } else {
                    auto_complete_container.fadeOut();
                }
            });
            auto_complete_container.on( 'click', 'li', function (){
                $('input[name="title"]').val( $( this ).data( 'text' ) );
                auto_complete_container.fadeOut();
            }).bind();
        };
        ere_title_auto_complete();

        var ere_map,
            markers = [],
            marker_cluster = null,
            googlemap_default_zoom = ere_search_vars.googlemap_default_zoom,
            not_found = ere_search_vars.not_found,
            clusterIcon = ere_search_vars.clusterIcon,
            initial_city = ere_search_vars.initial_city,
            google_map_style = ere_search_vars.google_map_style,
            pin_cluster_enable = ere_search_vars.pin_cluster_enable;
            var drgflag = true;
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                drgflag = false;
            }

            var ere_search_map_option = {
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scrollwheel: false,
                scroll: {x: $(window).scrollLeft(), y: $(window).scrollTop()},
                zoom: parseInt(googlemap_default_zoom),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                draggable: drgflag,
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }
            };

            var infobox = new InfoBox({
                disableAutoPan: true, //false
                maxWidth: 310,
                alignBottom: true,
                pixelOffset: new google.maps.Size(-140, -55),
                zIndex: null,
                closeBoxMargin: "0 0 -16px -16px",
                infoBoxClearance: new google.maps.Size(1, 1),
                isHidden: false,
                pane: "floatPane",
                enableEventPropagation: false
            });

        var ere_add_markers = function (props, map) {
            $.each(props, function (i, prop) {
                var latlng = new google.maps.LatLng(prop.lat, prop.lng),
                    marker_url = prop.marker_icon,
                    marker_size = new google.maps.Size(44, 60);

                var marker_icon = {
                    url: marker_url,
                    size: marker_size,
                    scaledSize: new google.maps.Size(44, 60),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(7, 27)
                };

                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: marker_icon,
                    draggable: false,
                    animation: google.maps.Animation.DROP
                });

                var prop_title = prop.data ? prop.data.post_title : prop.title,
                    display_css = '';
                if (prop.image_url == '' || typeof(prop.image_url) == 'undefined') {
                    display_css = 'style="display: none;"';
                }

                var contentString = document.createElement("div");
                contentString.className = 'marker-content clearfix';
                contentString.innerHTML = '<div class="marker-content-inner clearfix">' +
                    '<div class = "item-thumb" ' + display_css + '>' +
                    '<a href="' + prop.url + '">' +
                    '<img src="' + prop.image_url + '" alt="' + prop_title + '" style="width: 100px;">' +
                    '</a>' +
                    '</div>' +
                    '<div class="item-body">' +
                    '<a href="' + prop.url + '" class="title-marker">' + prop_title + '</a>' +
                    '<div class="price-marker">' + prop.price + '</div>' +
                    '<div class="address-marker"><i class="fa fa-map-marker"></i>' + prop.address + '</div>' +
                    '</div>' +
                    '</div>';
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        var scale = Math.pow(2, map.getZoom()),
                            offsety = ( (100 / scale) || 0 ),
                            projection = map.getProjection(),
                            markerPosition = marker.getPosition(),
                            markerScreenPosition = projection.fromLatLngToPoint(markerPosition),
                            pointHalfScreenAbove = new google.maps.Point(markerScreenPosition.x, markerScreenPosition.y - offsety),
                            aboveMarkerLatLng = projection.fromPointToLatLng(pointHalfScreenAbove);
                        map.setCenter(aboveMarkerLatLng);
                        setTimeout(function () {
                            infobox.setContent(contentString);
                            infobox.open(map, marker);
                        }, 300)
                    }
                })(marker, i));
                markers.push(marker);
            });
        };

        var ere_reload_markers = function () {
            for (var i = 0; i < markers.length; i++) {
                markers[i].setMap(null);
            }
            markers = [];
        };

        //Search on change and map result ajax
        var ere_search_on_change = function (current_form,map_ID) {
            var country, city, state,neighborhood, title, area, status, type, bedrooms, bathrooms, min_price, max_price,
                advanced, min_area, max_area, keyword, garage, min_year, max_year, features, label, min_garage_area,
                max_garage_area, min_land_area, max_land_area,property_identity, featured_enable,map_result;
            title = current_form.find('input[name="title"]').val();
            keyword = current_form.find('input[name="keyword"]').val();
            city = current_form.find('select[name="city"]').val();
            type = current_form.find('select[name="type"]').val();
            status = current_form.find('select[name="status"]').val();
            if(status==undefined)
            {
                status = current_form.find('input[name="status"]').val();
            }
            bedrooms = current_form.find('select[name="bedrooms"]').val();
            bathrooms = current_form.find('select[name="bathrooms"]').val();
            min_price = current_form.find('.ere-sliderbar-filter.ere-sliderbar-price').attr('data-min');
            max_price = current_form.find('.ere-sliderbar-filter.ere-sliderbar-price').attr('data-max');
            min_area = current_form.find('.ere-sliderbar-filter.ere-sliderbar-area').attr('data-min');
            max_area = current_form.find('.ere-sliderbar-filter.ere-sliderbar-area').attr('data-max');
            state = current_form.find('select[name="state"]').val();
            country = current_form.find('select[name="country"]').val();
            neighborhood = current_form.find('select[name="neighborhood"]').val();
            advanced = current_form.find('input[name="advanced"]').val();
            if(advanced == '1'){
                min_year = current_form.find('.ere-sliderbar-filter.ere-sliderbar-year-built').attr('data-min');
                max_year = current_form.find('.ere-sliderbar-filter.ere-sliderbar-year-built').attr('data-max');
                label = current_form.find('select[name="label"]').val();
                garage = current_form.find('select[name="garage"]').val();
                min_garage_area = current_form.find('.ere-sliderbar-filter.ere-sliderbar-garage-area').attr('data-min');
                max_garage_area = current_form.find('.ere-sliderbar-filter.ere-sliderbar-garage-area').attr('data-max');
                min_land_area = current_form.find('.ere-sliderbar-filter.ere-sliderbar-land-area').attr('data-min');
                max_land_area = current_form.find('.ere-sliderbar-filter.ere-sliderbar-land-area').attr('data-max');
                property_identity = current_form.find('input[name="property_identity"]').val();
                featured_enable = current_form.find('input[name="featured-search"]').val();
                if(featured_enable == '1'){
                    features = '';
                    current_form.find('.featured-list input[type=checkbox]:checked').each(function () {
                        features += $(this).val() + ';';
                    });
                    if(features != '') {
                        features = features.substring(0, features.length-1);
                    }
                }
            }
            map_result = map_ID;

            var search_type = 'map_only';
            if($(".list-property-result-ajax ").length > 0){
                search_type = 'map_and_content';
            }

            ere_properties_search(title, keyword, country, state,city, neighborhood, type, status, bedrooms, bathrooms, min_price, max_price,
                min_area, max_area, min_year, max_year, label, garage, min_garage_area,
                max_garage_area, min_land_area, max_land_area,property_identity, features, map_result, search_type);
        };
        var ere_properties_search = function (title, address_keyword, country, state, city, neighborhood, type, status, bedrooms, bathrooms, min_price, max_price, min_area, max_area, min_year, max_year, label, garage, min_garage_area, max_garage_area, min_land_area, max_land_area,property_identity, features, map_result, search_type) {
            var ere_security_search_map = $('#ere_security_search_map').val(),
                map_result_content = $('#'+map_result);
            $.ajax({
                dataType: 'json',
                url: ajax_url,
                data: {
                    'action': 'ere_property_search_ajax',
                    'title': title,
                    'address_keyword': address_keyword,
                    'country': country,
                    'state': state,
                    'city': city,
                    'neighborhood':neighborhood,
                    'type': type,
                    'status': status,
                    'bedrooms': bedrooms,
                    'bathrooms': bathrooms,
                    'min_price': min_price,
                    'max_price': max_price,
                    'min_area': min_area,
                    'max_area': max_area,
                    'min_year': min_year,
                    'max_year': max_year,
                    'label': label,
                    'garage': garage,
                    'min_garage_area': min_garage_area,
                    'max_garage_area': max_garage_area,
                    'min_land_area': min_land_area,
                    'max_land_area': max_land_area,
                    'property_identity':property_identity,
                    'features': features,
                    'search_type': search_type,
                    'ere_security_search_map': ere_security_search_map
                },
                beforeSend: function () {
                    map_result_content.parents('div.ere-search-properties').find('#ere-map-loading').fadeIn();
                },
                success: function (data) {
                    if(search_type == 'map_and_content') {
                        var $container = $('.owl-carousel', '.list-property-result-ajax'),
                            $wrap = $('.list-property-result-ajax');
                        $container.empty();
                        if (data.success === false) {
                            $wrap.find('.title-result h2 .number-result').hide();
                            $wrap.find('.title-result h2 .text-no-result').show();
                            $wrap.find('.title-result h2 .text-result').hide();
                        } else {
                            var $newElems = $('.property-item', data.property_html);
                            $container.css('opacity', 0);
                            $container.trigger('destroy.owl.carousel');
                            $container.html($newElems);
                            $container.css('opacity', 1);
                            $container.imagesLoaded(function () {
                                ERE.set_item_effect($newElems, 'hide');
                                ERE_Carousel.owlCarousel();
                                $newElems = $('.property-item', $container);
                                ERE.set_item_effect($newElems, 'show');
                            });
                            if ($newElems.length != '0') {
                                $wrap.find('.title-result h2 .number-result').html($newElems.length);
                                $wrap.find('.title-result h2 .number-result').show();
                                $wrap.find('.title-result h2 .text-no-result').hide();
                                $wrap.find('.title-result h2 .text-result').show();
                            }
                        }
                        ERE.favorite();
                        ERE.tooltip();
                        ERE_Compare.register_event_compare();
                    }
                    ere_map = new google.maps.Map(document.getElementById(map_result), ere_search_map_option);
                    ere_map.set('scrollwheel', false);
                    google.maps.event.trigger(ere_map, 'resize');
                    if (data.success === true) {
                        if (data.properties) {
                            var count_properties = data.properties.length;
                        }
                    }
                    if (count_properties == 1) {
                        var boundsListener = google.maps.event.addListener((ere_map), 'bounds_changed', function (event) {
                            this.setZoom(parseInt(googlemap_default_zoom));
                            google.maps.event.removeListener(boundsListener);
                        });
                    }
                    if (google_map_style !== '') {
                        var styles = JSON.parse(google_map_style);
                        ere_map.setOptions({styles: styles});
                    }
                    var mapPosition = new google.maps.LatLng('', '');
                    ere_map.setCenter(mapPosition);
                    ere_map.setZoom(parseInt(googlemap_default_zoom));
                    ere_remove_map_loader(ere_map);

                    if (data.success === true) {
                        ere_reload_markers();
                        ere_add_markers(data.properties, ere_map);
                        ere_map.fitBounds(markers.reduce(function (bounds, marker) {
                            return bounds.extend(marker.getPosition());
                        }, new google.maps.LatLngBounds()));

                        google.maps.event.trigger(ere_map, 'resize');
                        if(pin_cluster_enable == '1'){
                            marker_cluster = new MarkerClusterer(ere_map, markers, {
                                gridSize: 60,
                                styles: [
                                    {
                                        url: clusterIcon,
                                        width: 48,
                                        height: 48,
                                        textColor: "#fff"
                                    }
                                ]
                            });
                        }
                    } else {
                        map_result_content.empty().html('<div class="map-notfound">' + not_found + '</div>');
                    }
                    map_result_content.closest('div.ere-search-properties').find('#ere-map-loading').fadeOut('slow');
                },
                error: function (xhr) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                    map_result_content.closest('div.ere-search-properties').find('#ere-map-loading').fadeOut('slow');
                }
            });
        };
        var ere_remove_map_loader = function (map) {
            google.maps.event.addListener(map, 'tilesloaded', function () {
                $('#ere-map-loading').fadeOut();
            });
        };

        $('.ere-search-properties').each(function(){
            var this_form = $(this),
                map_ID = $(this).find('.ere-map-result').attr('id'),
                map_id_text = '#'+map_ID;
            $('.ere-search-status-tab .btn-status-filter',this_form).on('click',function () {
                $(this).parent().find('input').val($(this).data("value"));
                $(this).parent().find('button').removeClass('active');
                $(this).addClass('active');
            });
            if ($(map_id_text).length > 0) {
                $('select[name="bedrooms"], select[name="bathrooms"],select[name="type"],select[name="status"],input[name="status"] , ' +
                    'select[name="garage"], select[name="label"],input[name="keyword"],input[name="title"],input[name="property_identity"], ' +
                    'select[name="city"], select[name="country"], select[name="state"], select[name="neighborhood"]',this_form).on('change', function () {
                    var current_form = this_form.find('.search-properties-form');
                    ere_search_on_change(current_form,map_ID);
                });
                $('.ere-search-status-tab .btn-status-filter',this_form).on('click',function () {
                    var current_form = this_form.find('.search-properties-form');
                    ere_search_on_change(current_form,map_ID);
                });
                $('input[name="other_feature"]',this_form).on('change', function () {
                    var current_form = this_form.find('.search-properties-form');
                    ere_search_on_change(current_form,map_ID);
                });
                ere_properties_search('','','','',initial_city,'','','','','','','','','','','','','','','','','','','',map_ID, 'map_only');
            }
        })
    }
});