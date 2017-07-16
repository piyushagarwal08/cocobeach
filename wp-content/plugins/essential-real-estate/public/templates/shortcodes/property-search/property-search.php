<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/11/16
 * Time: 5:11 PM
 */
/**
 * @var $atts
 */
$initial_city = ere_get_option('search_initial_city', '');
$search_styles = $show_status_tab = $title_enable = $location_enable = $cities_enable = $types_enable = $status_enable = $number_bedrooms_enable =
$number_bathrooms_enable = $price_enable = $area_enable = $map_search_enable = $advanced_search_enable =
$countries_enable = $states_enable =$neighborhoods_enable= $year_built_enable = $labels_enable = $number_garage_enable = $garage_area_enable =
$land_area_enable = $property_identity_enable=$other_features_enable = $color_scheme = $el_class = $is_page_search = $request_city='';
extract(shortcode_atts(array(
    'search_styles' => 'style-default',
    'show_status_tab' => 'true',
    'search_title' => '',
    'title_enable' => 'true',
    'location_enable' => 'true',
    'countries_enable' => '',
    'states_enable' => '',
    'cities_enable' => '',
    'neighborhoods_enable' => '',
    'types_enable' => '',
    'status_enable' => '',
    'number_bedrooms_enable' => '',
    'number_bathrooms_enable' => '',
    'price_enable' => '',
    'area_enable' => '',
    'map_search_enable' => 'true',
    'advanced_search_enable' => '',
    'year_built_enable' => '',
    'labels_enable' => '',
    'number_garage_enable' => '',
    'garage_area_enable' => '',
    'land_area_enable' => '',
    'property_identity_enable' => '',
    'other_features_enable' => '',
    'color_scheme' => 'color-light',
    'el_class' => '',
    'is_page_search' => '0',
), $atts));
if($is_page_search == '1'){
    $request_city = isset($_GET['city']) ? $_GET['city'] : '';
}else{
    if ($map_search_enable === 'true')
    {
        if ($cities_enable == 'true')
        {
            $request_city=$initial_city;
        }
    }
    else
    {
        $request_city='';
    }
}
$request_keyword_title = isset($_GET['title']) ? $_GET['title'] : '';
$request_keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$request_type = isset($_GET['type']) ? $_GET['type'] : '';
$request_status = isset($_GET['status']) ? $_GET['status'] : '';
$request_bathrooms = isset($_GET['bathrooms']) ? $_GET['bathrooms'] : '';
$request_bedrooms = isset($_GET['bedrooms']) ? $_GET['bedrooms'] : '';
$request_min_area = isset($_GET['min-area']) ? $_GET['min-area'] : '';
$request_max_area = isset($_GET['max-area']) ? $_GET['max-area'] : '';
$request_min_price = isset($_GET['min-price']) ? $_GET['min-price'] : '';
$request_max_price = isset($_GET['max-price']) ? $_GET['max-price'] : '';
$request_state = isset($_GET['state']) ? $_GET['state'] : '';
$request_country = isset($_GET['country']) ? $_GET['country'] : '';
$request_neighborhood = isset($_GET['neighborhood']) ? $_GET['neighborhood'] : '';
$request_min_year = isset($_GET['min-year']) ? $_GET['min-year'] : '';
$request_max_year = isset($_GET['max-year']) ? $_GET['max-year'] : '';
$request_label = isset($_GET['label']) ? $_GET['label'] : '';
$request_property_identity = isset($_GET['property_identity']) ? $_GET['property_identity'] : '';
$request_garage = isset($_GET['garage']) ? $_GET['garage'] : '';
$request_min_garage_area = isset($_GET['min-garage-area']) ? $_GET['min-garage-area'] : '';
$request_max_garage_area = isset($_GET['max-garage-area']) ? $_GET['max-garage-area'] : '';
$request_min_land_area = isset($_GET['min-land-area']) ? $_GET['min-land-area'] : '';
$request_max_land_area = isset($_GET['max-land-area']) ? $_GET['max-land-area'] : '';
$request_features = isset($_GET['other_feature']) ? $_GET['other_feature'] : '';
if(!empty($request_features)) {
    $request_features = explode( ';',$request_features );
}
$request_advanced_search = isset($_GET['advanced']) ? $_GET['advanced'] : '0';
$request_featured_search = isset($_GET['featured-search']) ? $_GET['featured-search'] : '0';
$wrapper_class='ere-search-properties clearfix';
if ($map_search_enable == 'true'){
    $wrapper_class.=' ere-google-map-search';
}
if($show_status_tab=='true' && $search_styles!='style-mini-line')
{
    $wrapper_class.=' ere-show-status-tab';
}
$wrapper_classes = array(
    $wrapper_class,
    $search_styles,
    $color_scheme,
    $el_class,
);

$ere_search = new ERE_Search();
$enable_auto_complete = ere_get_option('auto_complete_enable');

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
if ($enable_auto_complete != 0) {
    wp_enqueue_script('jquery-ui-autocomplete');
}
if ($map_search_enable == 'true'){
    $googlemap_zoom_level = ere_get_option('googlemap_zoom_level', '12');
    $pin_cluster_enable = ere_get_option('googlemap_pin_cluster', '1');
    $google_map_style = ere_get_option('googlemap_style', '');
    wp_enqueue_script('google-map');
    //wp_enqueue_script('infobox');
    //wp_enqueue_script('moment');
    wp_enqueue_script('markerclusterer');
    $google_map_needed = 'true';
    wp_enqueue_script(ERE_PLUGIN_PREFIX . 'search_js_map', ERE_PLUGIN_URL.'public/templates/shortcodes/property-search/assets/js/property-search-map' . $min_suffix_js . '.js', array(), ERE_PLUGIN_VER, true);
    wp_localize_script(ERE_PLUGIN_PREFIX . 'search_js_map', 'ere_search_vars',
        array(
            'ajax_url' => ERE_AJAX_URL,
            'not_found' => esc_html__("We didn't find any results, you can retry with other keyword.", 'essential-real-estate'),
            'googlemap_default_zoom' => $googlemap_zoom_level,
            'clusterIcon' => ERE_PLUGIN_URL . 'public/assets/images/cluster-icon.png',
            'google_map_needed' => $google_map_needed,
            'initial_city' => $initial_city,
            'keyword_auto_complete' => $ere_search->keyword_auto_complete_search(),
            'google_map_style' => $google_map_style,
            'pin_cluster_enable' => $pin_cluster_enable,
        )
    );
}else{
    wp_enqueue_script(ERE_PLUGIN_PREFIX . 'search_js', ERE_PLUGIN_URL.'public/templates/shortcodes/property-search/assets/js/property-search' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
    wp_localize_script(ERE_PLUGIN_PREFIX . 'search_js', 'ere_search_vars',
        array(
            'ajax_url' => ERE_AJAX_URL,
            'initial_city' => $initial_city,
            'keyword_auto_complete' => $ere_search->keyword_auto_complete_search(),
        )
    );
}


wp_print_styles( ERE_PLUGIN_PREFIX . 'search_style');


$args = array(
    'post_type' => 'property',
    'posts_per_page' => -1,
    'post_status' => 'publish'
);
$args['tax_query'] = array();

if (!empty($request_type) && !empty($request_status)) {
    $args['tax_query'] = array(
        'relation' => 'AND'
    );
}

//tax query property type
if (!empty($request_type)) {
    $args['tax_query'][] = array(
        'taxonomy' => 'property-type',
        'field' => 'slug',
        'terms' => $request_type
    );
}

//tax query property status
if (!empty($request_status)) {
    $args['tax_query'][] = array(
        'taxonomy' => 'property-status',
        'field' => 'slug',
        'terms' => $request_status
    );
}
$data = new WP_Query($args);

$price = $property_size = array();
while ($data -> have_posts()) {
    $data->the_post();
    $property_ID = get_the_ID();
    $price[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_price', true);
    $property_size[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_size', true);
}
$args = array(
    'post_type' => 'property',
    'posts_per_page' => -1,
    'post_status' => 'publish'
);
$data = get_posts($args);
foreach ($data as $property) {
    $property_ID = $property->ID;
    $property_garage_size[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_garage_size', true);
    $property_year[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_year', true);
    $property_land_area[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_land', true);
    if(count($price) <= 1) {
        $price[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_price', true);
        $property_size[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_size', true);
    }
}
wp_reset_postdata();
/*Min max price*/
$min_price = 0;
$max_price = 0;
if (!empty($price)) {
    $min_price = min($price);
    if ($min_price == '') $min_price = 0;
    $max_price = max($price);
    if ($max_price == '') $max_price = 0;
}

/*Min max area*/
$min_area = 0;
$max_area = 0;
if (!empty($property_size)) {
    $min_area = min($property_size);
    if ($min_area == '') $min_area = 0;
    $max_area = max($property_size);
    if ($max_area == '') $max_area = 0;
}

/* Min max gareage area*/
$min_garage_area = 0;
$max_garage_area = 0;
if (!empty($property_garage_size)) {
    $min_garage_area = min($property_garage_size);
    if ($min_garage_area == '') $min_garage_area = 0;
    $max_garage_area = max($property_garage_size);
    if ($max_garage_area == '') $max_garage_area = 0;
}

/* Min max year*/
$min_year = 0;
$max_year = 0;
if (!empty($property_year)) {
    $min_year = min($property_year);
    if ($min_year == '') $min_year = 0;
    $max_year = max($property_year);
    if ($max_year == '') $max_year = 0;
}

/* Min max land area*/
$min_land_area = 0;
$max_land_area = 0;
if (!empty($property_land_area)) {
    $min_land_area = min($property_land_area);
    if ($min_land_area == '') $min_land_area = 0;
    $max_land_area = max($property_land_area);
    if ($max_land_area == '') $max_land_area = 0;
}
$geo_location = ere_get_option('geo_location');
/* Class col style for form*/
switch ($search_styles) {
    case 'style-mini-line':
        $class_col_input = 'col-lg-3 col-md-6 col-sm-6 col-xs-12';
        $class_col_button = 'col-md-2 col-md-4 col-sm-12 col-xs-12';
        $class_col_filter = 'col-lg-2 col-md-4 col-sm-6 col-xs-12';
        $show_status_tab='false';
        break;
    case 'style-default-small':
        $class_col_input = 'col-md-4 col-sm-6 col-xs-6 col-mb-12';
        $class_col_button = 'col-md-4 col-sm-6 col-xs-6 col-mb-12';
        $class_col_filter = 'col-md-4 col-sm-6 col-xs-6 col-mb-12';
        break;
    case 'style-absolute':
        $class_col_input = 'col-md-12 col-sm-12 col-xs-6 col-mb-12';
        $class_col_button = 'col-md-12 col-sm-12 col-xs-6 col-mb-12';
        $class_col_filter = 'col-md-12 col-sm-12 col-xs-6 col-mb-12';
        break;
    case 'style-vertical':
        $class_col_input = 'col-md-6 col-sm-6 col-xs-6 col-mb-12';
        $class_col_button = 'col-md-12 col-sm-12 col-xs-12 text-center';
        $class_col_filter = 'col-md-6 col-sm-6 col-xs-6 col-mb-12';
        break;
    default:
        $class_col_input = 'col-md-4 col-sm-6 col-xs-6 col-mb-12';
        $class_col_button = 'col-lg-2 col-md-4 col-sm-6 col-xs-6 col-mb-12';
        $class_col_filter = 'col-md-4 col-sm-6 col-xs-6 col-mb-12';
        break;
}

if ($search_styles === 'style-vertical') {
    $class_col_half_map = 'col-md-6 col-no-padding';
} else {
    $class_col_half_map = '';
}

$map_ID = 'ere_result_map-'.rand();
?>
<div class="<?php echo join(' ', $wrapper_classes) ?>">
    <?php if ($map_search_enable === 'true'): ?>
        <div class="ere-map-search clearfix <?php if (!empty($class_col_half_map)) echo esc_attr($class_col_half_map); ?>">
            <div class="search-map-inner clearfix">
                <div id="<?php echo esc_attr($map_ID)?>" class="ere-map-result">
                </div>
                <div id="ere-map-loading">
                    <div class="block-center">
                        <div class="block-center-inner">
                            <i class="fa fa-spinner fa-spin"></i>
                        </div>
                    </div>
                </div>
                <?php wp_nonce_field('ere_search_map_ajax_nonce', 'ere_security_search_map'); ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if($search_styles === 'style-vertical'):?>
    <div class="col-scroll-vertical col-md-6 col-no-padding">
        <?php endif;?>
        <div class="form-search-wrap">
            <div class="form-search-inner">
                <div class="ere-search-content">
                    <?php $advanced_search = ere_get_permalink('advanced_search'); ?>
                    <div data-href="<?php echo esc_url($advanced_search) ?>" class="search-properties-form">
                        <?php if($status_enable == 'true' && $show_status_tab=='true'):?>
                            <div class="ere-search-status-tab">
                                <span>
                                    <i class="fa fa-search"></i>
                                </span>
                                <input class="search-field" type='hidden' name="status" value="<?php echo esc_attr($request_status); ?>" data-default-value=""/>
                                <button type="button" data-value="" class="btn-status-filter<?php if(empty($request_status)) echo " active" ?>"><?php esc_html_e('All Listing','essential-real-estate') ?></button>
                                <?php
                                $property_status = get_categories(array('taxonomy' => 'property-status', 'hide_empty' => 1, 'orderby' => 'ASC'));
                                if ($property_status) :
                                    foreach ($property_status as $status):?>
                                        <button type="button" data-value="<?php echo esc_attr($status->slug) ?>" class="btn-status-filter<?php if($request_status==$status->slug) echo " active" ?>"><?php echo esc_attr($status->name) ?></button>
                                    <?php endforeach;
                                endif;
                                ?>
                            </div>
                        <?php endif;?>
                        <div class="row">
                            <?php if ($title_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_input); ?>">
                                    <div class="form-group no-margin">
                                        <input type="text" class="ere-title form-control search-field" data-default-value=""
                                               value="<?php echo esc_attr($request_keyword_title); ?>"
                                               name="title"
                                               placeholder="<?php esc_html_e('Title', 'essential-real-estate') ?>">
                                        <div id="ere_result_by_title" class="ere-result-by-title"></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($location_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_input); ?>">
                                    <div class="form-group no-margin">
                                        <input type="text" class="ere-location form-control search-field" data-default-value=""
                                               value="<?php echo esc_attr($request_keyword); ?>"
                                               name="keyword"
                                               placeholder="<?php esc_html_e('Address', 'essential-real-estate') ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($countries_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_input); ?>">
                                    <div class="form-group">
                                        <select name="country" class="ere-property-country-ajax search-field" title="<?php esc_html_e('Countries', 'essential-real-estate'); ?>" data-selected="<?php echo esc_attr($request_country); ?>" data-default-value="">
                                            <?php
                                            $ere_search->get_property_countries($request_country); ?>
                                            <option
                                                value="" <?php if (empty($request_country)) echo esc_attr('selected'); ?>>
                                                <?php esc_html_e('All Countries', 'essential-real-estate'); ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($states_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_input); ?>">
                                    <select name="state" class="ere-property-state-ajax search-field" title="<?php esc_html_e('States', 'essential-real-estate'); ?>" data-selected="<?php echo esc_attr($request_state); ?>" data-default-value="">
                                        <?php ere_get_taxonomy_slug('property-state', $request_state); ?>
                                        <option value="" <?php if (empty($request_state)) echo esc_attr('selected'); ?>>
                                            <?php esc_html_e('All States', 'essential-real-estate'); ?>
                                        </option>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <?php if ($cities_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_input); ?>">
                                    <div class="form-group">
                                        <select name="city" class="ere-property-city-ajax search-field" title="<?php esc_html_e('Cities', 'essential-real-estate'); ?>" data-selected="<?php echo esc_attr($request_city); ?>" data-default-value="">
                                            <?php if(!empty($request_city)):?>
                                                <?php ere_get_taxonomy_slug('property-city', $request_city); ?>
                                                <option value="" >
                                                    <?php esc_html_e('All Cities', 'essential-real-estate'); ?>
                                                </option>
                                            <?php else:?>
                                                <?php ere_get_taxonomy_slug('property-city'); ?>
                                                <option value="" selected="selected">
                                                    <?php esc_html_e('All Cities', 'essential-real-estate'); ?>
                                                </option>
                                            <?php endif;?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($neighborhoods_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_input); ?>">
                                    <select name="neighborhood" class="ere-property-neighborhood-ajax search-field" title="<?php esc_html_e('Property Neighborhoods', 'essential-real-estate'); ?>" data-selected="<?php echo esc_attr($request_neighborhood); ?>" data-default-value="">
                                        <?php ere_get_taxonomy_slug('property-neighborhood', $request_neighborhood); ?>
                                        <option value="" <?php if (empty($request_neighborhood)) echo esc_attr('selected'); ?>>
                                            <?php esc_html_e('All Neighborhoods', 'essential-real-estate'); ?>
                                        </option>
                                    </select>
                                </div>
                            <?php endif; ?>
                            <?php if ($types_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_input); ?>">
                                    <div class="form-group">
                                        <select name="type" title="<?php esc_html_e('Property Types', 'essential-real-estate') ?>"
                                                class="search-field" data-default-value="">
                                            <?php ere_get_taxonomy_slug('property-type', $request_type); ?>
                                            <option
                                                value="" <?php if (empty($request_type)) echo esc_attr('selected'); ?>>
                                                <?php esc_html_e('All Types', 'essential-real-estate') ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (($status_enable == 'true') && $show_status_tab!='true'): ?>
                                <div class="<?php echo esc_attr($class_col_input); ?>">
                                    <div class="form-group">
                                        <select name="status" title="<?php esc_html_e('Property Status', 'essential-real-estate') ?>"
                                                class="search-field" data-default-value="">
                                            <?php ere_get_taxonomy_slug('property-status', $request_status); ?>
                                            <option
                                                value="" <?php if (empty($request_status)) echo esc_attr('selected'); ?>>
                                                <?php esc_html_e('All Status', 'essential-real-estate') ?>
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($number_bedrooms_enable == 'true'): ?>
                                <?php if ($search_styles == 'style-mini-line') {
                                    $class_col_input_mini = 'col-md-2';
                                } ?>
                                <div class="<?php if ($search_styles != 'style-mini-line') {
                                    echo esc_attr($class_col_input);
                                } else {
                                    echo esc_attr($class_col_input_mini);
                                } ?>">
                                    <div class="form-group">
                                        <select name="bedrooms" title="<?php esc_html_e('Property Bedrooms', 'essential-real-estate') ?>"
                                                class="search-field" data-default-value="any">
                                            <option value="any">
                                                <?php esc_html_e('Any Bedrooms', 'essential-real-estate') ?>
                                            </option>
                                            <?php
                                            $max_bedrooms = '';
                                            $max_bedrooms = ere_get_option('max_number_bedrooms');
                                            $max_bedrooms = (int)$max_bedrooms;
                                            ?>
                                            <?php for ($i = 1; $i <= $max_bedrooms; $i++): ?>
                                                <option
                                                    value="<?php echo esc_attr($i) ?>" <?php if ($i == $request_bedrooms) {
                                                    echo esc_attr('selected');
                                                } ?>>
                                                    <?php echo esc_attr($i); ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($number_bathrooms_enable == 'true'): ?>
                                <div class="<?php if ($search_styles != 'style-mini-line') {
                                    echo esc_attr($class_col_input);
                                } else {
                                    echo esc_attr($class_col_input_mini);
                                } ?>">
                                    <div class="form-group">
                                        <select name="bathrooms" title="<?php esc_html_e('Property Bathrooms', 'essential-real-estate') ?>"
                                                class="search-field" data-default-value="any">
                                            <option value="any">
                                                <?php esc_html_e('Any Bathrooms', 'essential-real-estate') ?>
                                            </option>
                                            <?php
                                            $max_bathrooms = '';
                                            $max_bathrooms = ere_get_option('max_number_bathrooms');
                                            $max_bathrooms = (int)$max_bathrooms;
                                            ?>
                                            <?php for ($i = 1; $i <= $max_bathrooms; $i++): ?>
                                                <option
                                                    value="<?php echo esc_attr($i) ?>" <?php if ($i == $request_bathrooms) {
                                                    echo esc_attr('selected');
                                                } ?>>
                                                    <?php echo esc_attr($i); ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($price_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_filter) ?>">
                                    <?php if (!empty($request_min_price) && !empty($request_max_price)) {
                                        $min_price_change = $request_min_price;
                                        $max_price_change = $request_max_price;
                                    } else {
                                        $min_price_change = $min_price;
                                        $max_price_change = $max_price;
                                    } ?>
                                    <div class="form-group ere-sliderbar-price ere-sliderbar-filter"
                                         data-min-default="<?php echo esc_attr($min_price) ?>"
                                         data-max-default="<?php echo esc_attr($max_price); ?>"
                                         data-min="<?php echo esc_attr($min_price_change) ?>"
                                         data-max="<?php echo esc_attr($max_price_change); ?>">
                                        <div class="title-slider-filter">
                                            <?php esc_html_e('Price', 'essential-real-estate') ?> [<span
                                                class="min-value"><?php echo ere_get_format_number($min_price_change) ?></span> - <span
                                                class="max-value"><?php echo ere_get_format_number($max_price_change) ?></span>]<?php echo ere_get_option('currency_sign'); ?>
                                            <input type="hidden" name="min-price" class="min-input-request"
                                                   value="<?php echo esc_attr($min_price_change) ?>">
                                            <input type="hidden" name="max-price" class="max-input-request"
                                                   value="<?php echo esc_attr($max_price_change) ?>">
                                        </div>
                                        <div class="sidebar-filter">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($area_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_filter) ?>">
                                    <?php if (!empty($request_min_area) && !empty($request_max_area)) {
                                        $min_area_change = $request_min_area;
                                        $max_area_change = $request_max_area;
                                    } else {
                                        $min_area_change = $min_area;
                                        $max_area_change = $max_area;
                                    } ?>
                                    <div class="form-group ere-sliderbar-area ere-sliderbar-filter"
                                         data-min-default="<?php echo esc_attr($min_area) ?>"
                                         data-max-default="<?php echo esc_attr($max_area) ?>"
                                         data-min="<?php echo esc_attr($min_area_change) ?>"
                                         data-max="<?php echo esc_attr($max_area_change); ?>">
                                        <div class="title-slider-filter">
                                            <span><?php esc_html_e('Area', 'essential-real-estate') ?> [</span><span
                                                class="min-value"><?php echo ere_get_format_number($min_area_change) ?></span> - <span
                                                class="max-value"><?php echo ere_get_format_number($max_area_change) ?></span><span>]
                                                <?php $measurement_units = ere_get_option('measurement_units', 'SqFt');
                                                echo esc_html($measurement_units).'</span>'; ?>
                                                <input type="hidden" name="min-area" class="min-input-request"
                                                       value="<?php echo esc_attr($min_area_change) ?>">
                                        <input type="hidden" name="max-area" class="max-input-request"
                                               value="<?php echo esc_attr($max_area_change) ?>">
                                        </div>
                                        <div class="sidebar-filter">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="<?php echo esc_attr($class_col_button); ?> pull-right submit-search-form">
                                <div class="form-group">
                                    <button type="button" id="search-btn"><i class="fa fa-search"></i>
                                        <?php esc_html_e('Go Search', 'essential-real-estate') ?>
                                    </button>
                                </div>
                            </div>

                            <?php if ($advanced_search_enable == 'true'): ?>
                                <div class="<?php echo esc_attr($class_col_button); ?> pull-right">
                                    <div class="form-group">
                                        <button type="button" class="advanced-search-button btn-outline">
                                            <?php esc_html_e('Advanced Option', 'essential-real-estate') ?>
                                            <input type="hidden" name="advanced" class="search-field" data-default-value="0"
                                                   value="<?php if (!empty($request_advanced_search) && $request_advanced_search == '1') {
                                                       echo esc_attr('1');
                                                   } else {
                                                       echo esc_attr('0');
                                                   } ?>">
                                        </button>
                                    </div>
                                </div>
                                <?php if ($search_styles == 'style-mini-line') {
                                    $class_col_input = $class_col_filter = $class_col_button = 'col-md-3';
                                } ?>
                                <?php if (!empty($request_advanced_search) && $request_advanced_search == '1') {
                                    $class_show = 'ere-display-block';
                                } else {
                                    $class_show = '';
                                } ?>
                                <div class="search-advanced-info <?php if (!empty($class_show)) {
                                    echo esc_attr($class_show);
                                }; ?>">
                                    <?php if ($year_built_enable == 'true'): ?>
                                        <?php if (!empty($request_min_year) && !empty($request_max_year)) {
                                            $min_year_change = $request_min_year;
                                            $max_year_change = $request_max_year;
                                        } else {
                                            $min_year_change = $min_year;
                                            $max_year_change = $max_year;
                                        } ?>
                                        <div class="<?php echo esc_attr($class_col_filter); ?>">
                                            <div class="form-group ere-sliderbar-year-built ere-sliderbar-filter"
                                                 data-min-default="<?php echo esc_attr($min_year) ?>"
                                                 data-max-default="<?php echo esc_attr($max_year) ?>"
                                                 data-min="<?php echo esc_attr($min_year_change); ?>"
                                                 data-max="<?php echo esc_attr($max_year_change); ?>">
                                                <div class="title-slider-filter">
                                                    <?php esc_html_e('Year Built ', 'essential-real-estate') ?>[<span
                                                        class="min-value not-format"><?php echo esc_html($min_year_change); ?></span>
                                                    - <span
                                                        class="max-value not-format"><?php echo esc_html($max_year_change); ?></span>]
                                                    <input type="hidden" name="min-year" class="min-input-request"
                                                           value="<?php echo esc_attr($min_year_change) ?>">
                                                    <input type="hidden" name="max-year" class="max-input-request"
                                                           value="<?php echo esc_attr($max_year_change) ?>">
                                                </div>
                                                <div class="sidebar-filter">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($labels_enable == 'true'): ?>
                                        <div class="<?php echo esc_attr($class_col_input); ?>">
                                            <div class="form-group">
                                                <select name="label" id="label" title="<?php esc_html_e('Property Labels', 'essential-real-estate') ?>"
                                                        class="search-field" data-default-value="">
                                                    <?php ere_get_taxonomy_slug('property-labels', $request_label); ?>
                                                    <option
                                                        value="" <?php if (empty($request_label)) echo esc_attr('selected'); ?>>
                                                        <?php esc_html_e('All Labels', 'essential-real-estate') ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>


                                    <?php if ($number_garage_enable == 'true'): ?>
                                        <div class="<?php echo esc_attr($class_col_input); ?>">
                                            <div class="form-group">
                                                <select name="garage" id="garage" title="<?php esc_html_e('Property Garages', 'essential-real-estate') ?>"
                                                        class="search-field" data-default-value="any">
                                                    <option value="any">
                                                        <?php esc_html_e('Any Garages', 'essential-real-estate') ?>
                                                    </option>
                                                    <?php
                                                    $max_garage = '';
                                                    $max_garage = ere_get_option('max_number_garage');
                                                    $max_garage = (int)$max_garage;
                                                    ?>
                                                    <?php for ($i = 1; $i <= $max_garage; $i++): ?>
                                                        <option
                                                            value="<?php echo esc_attr($i) ?>" <?php if ($i == $request_garage) {
                                                            echo esc_attr('selected');
                                                        } ?>>
                                                            <?php echo esc_html__($i); ?>
                                                        </option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($garage_area_enable == 'true'): ?>
                                        <div class="<?php echo esc_attr($class_col_filter); ?>">
                                            <?php if (!empty($request_min_garage_area) && !empty($request_max_garage_area)) {
                                                $min_garage_area_change = $request_min_garage_area;
                                                $max_garage_area_change = $request_max_garage_area;
                                            } else {
                                                $min_garage_area_change = $min_garage_area;
                                                $max_garage_area_change = $max_garage_area;
                                            } ?>
                                            <div class="form-group ere-sliderbar-garage-area ere-sliderbar-filter"
                                                 data-min-default="<?php echo esc_attr($min_garage_area) ?>"
                                                 data-max-default="<?php echo esc_attr($max_garage_area) ?>"
                                                 data-min="<?php echo esc_attr($min_garage_area_change); ?>"
                                                 data-max="<?php echo esc_attr($max_garage_area_change); ?>">
                                                <div class="title-slider-filter">
                                                    <span><?php esc_html_e('Garage Area', 'essential-real-estate') ?> [</span><span
                                                        class="min-value"><?php echo ere_get_format_number($min_garage_area_change,0) ?></span>
                                                    - <span class="max-value"><?php echo ere_get_format_number($max_garage_area_change,0) ?></span><span>]
                                                        <?php $measurement_units = ere_get_option('measurement_units', 'SqFt');
                                                        echo esc_html__($measurement_units).'</span>'; ?>
                                                        <input type="hidden" name="min-garage-area" class="min-input-request"
                                                               value="<?php echo esc_attr($min_garage_area_change) ?>">
                                                <input type="hidden" name="max-garage-area" class="max-input-request"
                                                       value="<?php echo esc_attr($max_garage_area_change) ?>">
                                                </div>
                                                <div class="sidebar-filter">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($land_area_enable == 'true'): ?>
                                        <div class="<?php echo esc_attr($class_col_filter); ?>">
                                            <?php if (!empty($request_min_land_area) && !empty($request_max_land_area)) {
                                                $min_land_area_change = $request_min_land_area;
                                                $max_land_area_change = $request_max_land_area;
                                            } else {
                                                $min_land_area_change = $min_land_area;
                                                $max_land_area_change = $max_land_area;
                                            } ?>
                                            <div class="form-group ere-sliderbar-land-area ere-sliderbar-filter"
                                                 data-min-default="<?php echo esc_attr($min_land_area) ?>"
                                                 data-max-default="<?php echo esc_attr($max_land_area) ?>"
                                                 data-min="<?php echo esc_attr($min_land_area_change); ?>"
                                                 data-max="<?php echo esc_attr($max_land_area_change); ?>">
                                                <div class="title-slider-filter">
                                                    <span><?php esc_html_e('Land Area', 'essential-real-estate') ?> [</span><span
                                                        class="min-value"><?php echo ere_get_format_number($min_land_area_change) ?></span>
                                                    - <span
                                                        class="max-value"><?php echo ere_get_format_number($max_land_area_change) ?></span><span>]
                                                        <?php $measurement_units = ere_get_option('measurement_units', 'SqFt');
                                                        echo esc_html($measurement_units).'</span>'; ?>
                                                        <input type="hidden" name="min-land-area" class="min-input-request"
                                                               value="<?php echo esc_attr($min_land_area_change) ?>">
                                                <input type="hidden" name="max-land-area" class="max-input-request"
                                                       value="<?php echo esc_attr($max_land_area_change) ?>">
                                                </div>
                                                <div class="sidebar-filter">
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($property_identity_enable == 'true'): ?>
                                        <div class="<?php echo esc_attr($class_col_input); ?>">
                                            <div class="form-group no-margin">
                                                <input type="text" class="ere-property-identity form-control search-field" data-default-value=""
                                                       value="<?php echo esc_attr($request_property_identity); ?>"
                                                       name="property_identity"
                                                       placeholder="<?php esc_html_e('Property ID', 'essential-real-estate') ?>">
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($other_features_enable == 'true'): ?>
                                        <div class="other-featured clearfix">
                                            <div class="<?php echo esc_attr($class_col_button); ?> enable-featured">
                                                <?php if (!empty($request_featured_search) && $request_featured_search == '1') {
                                                    $class_on_other_featured = 'show';
                                                } else {
                                                    $class_on_other_featured = '';
                                                } ?>
                                                <a href="javascript:;" class="button-other-featured <?php echo esc_attr($class_on_other_featured);?>">
                                                    <?php if (!empty($request_featured_search) && $request_featured_search == '1') {
                                                        $class_hide_text = '';
                                                        $class_show_text = 'hide';
                                                    } else {
                                                        $class_hide_text = 'hide';
                                                        $class_show_text = '';
                                                    } ?>
                                                    <span class="hide-featured-text <?php echo esc_attr($class_hide_text)?>">
                                                    <?php esc_html_e('Hide Featured', 'essential-real-estate');?>
                                                </span>
                                                <span class="show-featured-text <?php echo esc_attr($class_show_text)?>">
                                                    <?php esc_html_e('Show Featured', 'essential-real-estate');?>
                                                </span>
                                                </a>
                                                <input type="hidden" name="featured-search" class="search-field" data-default-value="0"
                                                       value="<?php if (!empty($request_featured_search) && $request_featured_search == '1') {
                                                           echo esc_attr('1');
                                                       } else {
                                                           echo esc_attr('0');
                                                       } ?>">
                                            </div>
                                            <?php if (!empty($request_featured_search) && $request_featured_search == '1') {
                                                $class_featured_show = 'ere-display-block';
                                            } else {
                                                $class_featured_show = '';
                                            } ?>
                                            <div class="featured-list <?php echo esc_attr($class_featured_show); ?>">
                                                <?php
                                                $feature_terms = get_categories(array(
                                                    'hide_empty' => 0,
                                                    'taxonomy'  => 'property-feature'
                                                ));
                                                if (!empty($feature_terms)) {
                                                    $count = 1;
                                                    foreach ($feature_terms as $term) {
                                                        echo '<div class="col-sm-2"><div class="checkbox"><label>';
                                                        if (!empty($request_features) && in_array($term->slug, $request_features)) {
                                                            echo '<input type="checkbox" name="other_feature" id="feature-' . esc_attr($count) . '" value="' . esc_attr($term->slug) . '" checked/>';
                                                        } else {
                                                            echo '<input type="checkbox" name="other_feature" id="feature-' . esc_attr($count) . '" value="' . esc_attr($term->slug) . '" />';
                                                        }
                                                        echo esc_attr($term->name);
                                                        echo '</label></div></div>';
                                                        $count++;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($search_styles === 'style-vertical'): ?>
            <div class="property-result-wrap">
                <div class="list-property-result-ajax ">
                    <?php
                    $args_prop = $tax_query = array();
                    $args_prop = array(
                        'post_type' => 'property',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    );
                    if ($cities_enable == 'true' && !empty($initial_city)) {
                        $args_prop['tax_query'][] = array(
                            'taxonomy' => 'property-city',
                            'field' => 'slug',
                            'terms' => $initial_city
                        );
                    }
                    $data_vertical = new WP_Query($args_prop);
                    $total_post = $data_vertical->found_posts;
                    $custom_property_image_size = '370x220';
                    $property_item_class = array('property-item');
                    $min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
                    wp_print_styles( ERE_PLUGIN_PREFIX . 'property');
                    ?>
                    <div class="title-result">
                        <h2 class="uppercase">
                            <span class="number-result"><?php echo esc_html($total_post) ?></span>
                            <span class="text-result"><?php esc_html_e(' Properties', 'essential-real-estate') ?></span>
                            <span class="text-no-result"><?php esc_html_e(' No property found', 'essential-real-estate') ?></span>
                        </h2>
                    </div>
                    <div class="ere-property property-carousel">
                        <?php
                        $owl_responsive_attributes = array();
                        $owl_responsive_attributes[] = '"0" : {"items" : 1}';
                        $owl_responsive_attributes[] = '"600" : {"items" : 2}';
                        $owl_responsive_attributes[] = '"992" : {"items" : 1}';
                        $owl_responsive_attributes[] = '"1200" : {"items" : 2}';
                        $owl_attributes = array(
                            '"items":2',
                            '"margin":30',
                            '"nav": true',
                            '"responsive": {' . implode(', ', $owl_responsive_attributes) . '}'
                        );
                        $property_content_attributes[] = "data-plugin-options='{" . implode( ', ', $owl_attributes ) . "}'";
                        ?>
                        <div class="owl-carousel" <?php echo implode( ' ', $property_content_attributes ); ?>>
                            <?php if ($data_vertical->have_posts()) :
                                $index = 0;
                                while ($data_vertical->have_posts()): $data_vertical->the_post();?>
                                    <?php ere_get_template('content-property.php', array(
                                        'custom_property_image_size' => $custom_property_image_size,
                                        'property_item_class' => $property_item_class,
                                    )); ?>
                                <?php endwhile;
                            else: ?>
                                <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php wp_reset_postdata();?>
        <?php endif; ?>
        <?php if($search_styles === 'style-vertical'):?>
    </div>
<?php endif;?>
</div>