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

    $('.btn-status-filter','.ere-search-status-tab').on('click', function (e) {
        e.preventDefault();
        $(this).parent().find('input').val($(this).data("value"));
        $(this).parent().find('button').removeClass('active');
        $(this).addClass('active');
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
                $container = $button.parent().parent().parent(),
                input = button_click.find('input[name="advanced"]'),
                status = input.attr('value');
            $container.find('.search-advanced-info').slideToggle();
            if (status == '0') {
                input.attr('value', '1');
            } else {
                input.attr('value', '0');
            }
        });
    });

    $('.other-featured .enable-featured .button-other-featured').each(function () {
        var button_click = $(this);
        button_click.on('click', function (event) {
            event.preventDefault();
            var $button = $(this),
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
    }
});