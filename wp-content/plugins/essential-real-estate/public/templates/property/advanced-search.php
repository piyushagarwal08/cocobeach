<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 10/01/2017
 * Time: 1:50 CH
 */
$location = $city = $status = $type = $bedroom = $bathroom = $min_price = $max_price = $min_area =
$max_area = $country = $state = $garage = $min_garage_area = $max_garage_area = $min_year = $max_year =
$labels = $land_area_min = $land_area_max = $features = '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$location = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$city = isset($_GET['city']) ? $_GET['city'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$bedrooms = isset($_GET['bedrooms']) ? $_GET['bedrooms'] : '';
$bathrooms = isset($_GET['bathrooms']) ? $_GET['bathrooms'] : '';
$min_price = isset($_GET['min-price']) ? $_GET['min-price'] : '';
$max_price = isset($_GET['max-price']) ? $_GET['max-price'] : '';
$min_area = isset($_GET['min-area']) ? $_GET['min-area'] : '';
$max_area = isset($_GET['max-area']) ? $_GET['max-area'] : '';
$country = isset($_GET['country']) ? $_GET['country'] : '';
$state = isset($_GET['state']) ? $_GET['state'] : '';
$neighborhood = isset($_GET['neighborhood']) ? $_GET['neighborhood'] : '';
$advanced_search = isset($_GET['advanced']) ? $_GET['advanced'] : '';
if($advanced_search == '1'){
    $garage = isset($_GET['garage']) ? $_GET['garage'] : '';
    $min_garage_area = isset($_GET['min-garage-area']) ? $_GET['min-garage-area'] : '';
    $max_garage_area = isset($_GET['max-garage-area']) ? $_GET['max-garage-area'] : '';
    $min_year = isset($_GET['min-year']) ? $_GET['min-year'] : '';
    $max_year = isset($_GET['max-year']) ? $_GET['max-year'] : '';
    $label = isset($_GET['label']) ? $_GET['label'] : '';
    $land_area_min = isset($_GET['min-land-area']) ? $_GET['min-land-area'] : '';
    $land_area_max = isset($_GET['max-land-area']) ? $_GET['max-land-area'] : '';
    $property_identity = isset($_GET['property_identity']) ? $_GET['property_identity'] : '';
    $featured_search = isset($_GET['featured-search']) ? $_GET['featured-search'] : '';
    if($featured_search == '1'){
        $features = isset($_GET['other_feature']) ? $_GET['other_feature'] : '';
        if(!empty($features)) {
            $features = explode( ';',$features );
        }
    }
}

$meta_query = array();
$tax_query = array();
$parameters='';
$keyword_array = '';

$property_item_class = array('property-item');
$property_content_class = array('property-content');
$property_content_attributes = array();

$wrapper_classes = array(
    'ere-property clearfix',
);
$custom_property_layout_style = ere_get_option( 'search_property_layout', 'property-grid' );
$custom_property_items_amount = ere_get_option( 'search_item_amount', '6' );
$custom_property_columns      = ere_get_option( 'search_property_columns', '3' );
$custom_property_columns_gap  = ere_get_option( 'search_columns_gap', 'col-gap-30' );
$custom_property_items_md = ere_get_option( 'search_items_md', '3' );
$custom_property_items_sm = ere_get_option( 'search_items_sm', '2' );
$custom_property_items_xs = ere_get_option( 'search_items_xs', '1' );
$custom_property_items_mb = ere_get_option( 'search_items_mb', '1' );

if(isset( $_SESSION["property_view_as"] ) && !empty( $_SESSION["property_view_as"] ) && in_array($_SESSION["property_view_as"], array('property-list', 'property-grid'))) {
    $custom_property_layout_style = $_SESSION["property_view_as"];
}
$property_item_class         = array();

$wrapper_classes = array(
    'ere-property clearfix',
    $custom_property_layout_style,
    $custom_property_columns_gap
);

if($custom_property_layout_style=='property-list'){
    $wrapper_classes[] = 'list-1-column';
}

if ( $custom_property_columns_gap == 'col-gap-30' ) {
    $property_item_class[] = 'mg-bottom-30';
} elseif ( $custom_property_columns_gap == 'col-gap-20' ) {
    $property_item_class[] = 'mg-bottom-20';
} elseif ( $custom_property_columns_gap == 'col-gap-10' ) {
    $property_item_class[] = 'mg-bottom-10';
}

$wrapper_classes[]     = 'columns-' . $custom_property_columns;
$wrapper_classes[]     = 'columns-md-' . $custom_property_items_md;
$wrapper_classes[]     = 'columns-sm-' . $custom_property_items_sm;
$wrapper_classes[]     = 'columns-xs-' . $custom_property_items_xs;
$wrapper_classes[]     = 'columns-mb-' . $custom_property_items_mb;
$property_item_class[] = 'ere-item-wrap';

$orderby = 'date';
$order   = 'DESC';

$args = array(
    'posts_per_page'      => $custom_property_items_amount,
    'post_type'           => 'property',
    'orderby' => 'date',
    'order' => 'DESC',
    'offset'              => ( max( 1, get_query_var( 'paged' ) ) - 1 ) * $custom_property_items_amount,
    'ignore_sticky_posts' => 1,
    'post_status'         => 'publish',
);
$IDs = array();
if(!empty($title)){
    global $wpdb;
    $table = $wpdb->posts;
    $data = $wpdb->get_results( "SELECT DISTINCT * FROM $table WHERE post_type='property' and post_status='publish' and (post_title LIKE '%$title%' OR post_content LIKE '%$title%')" );
    foreach($data as $prop){
        $IDs[] = $prop->ID;
    }
}
if(!empty($IDs)){
    $args['post__in'] = $IDs;
}

if (isset($_GET['sortby']) && in_array($_GET['sortby'], array('a_price', 'd_price', 'a_date','d_date', 'featured'))) {
    if ($_GET['sortby'] == 'a_price') {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = ERE_METABOX_PREFIX . 'property_price';
        $args['order'] = 'ASC';
    } else if ($_GET['sortby'] == 'd_price') {
        $args['orderby'] = 'meta_value_num';
        $args['meta_key'] = ERE_METABOX_PREFIX . 'property_price';
        $args['order'] = 'DESC';
    } else if ($_GET['sortby'] == 'featured') {
        $args['meta_key'] = ERE_METABOX_PREFIX . 'property_featured';
        $args['meta_value'] = '1';
    } else if ($_GET['sortby'] == 'a_date') {
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
    } else if ($_GET['sortby'] == 'd_date') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }
}
//Query get properties with keyword location
if (isset($location) ? $location : '') {
    $address_array = array(
        'key' => ERE_METABOX_PREFIX. 'property_address',
        'value' => $location,
        'type' => 'CHAR',
        'compare' => 'LIKE',
    );

    $keyword_array = array(
        'relation' => 'OR',
        $address_array,
    );
    $parameters.=sprintf( __('Location: <strong>%s</strong>; ', 'essential-real-estate'), $location );
}

//tax query property type
if (isset($type) && !empty($type)) {
    $tax_query[] = array(
        'taxonomy' => 'property-type',
        'field' => 'slug',
        'terms' => $type
    );
    $parameters.=sprintf( __('Type: <strong>%s</strong>; ', 'essential-real-estate'), $type );
}

//tax query property status
if (isset($status) && !empty($status)) {
    $tax_query[] = array(
        'taxonomy' => 'property-status',
        'field' => 'slug',
        'terms' => $status
    );
    $parameters.=sprintf( __('Status: <strong>%s</strong>; ', 'essential-real-estate'), $status );
}

//tax query property labels
if (isset($label) && !empty($label)) {
    $tax_query[] = array(
        'taxonomy' => 'property-labels',
        'field' => 'slug',
        'terms' => $label
    );
    $parameters.=sprintf( __('Labels: <strong>%s</strong>; ', 'essential-real-estate'), $label );
}

//initial cities and cities search

if (!empty($city)) {
    $tax_query[] = array(
        'taxonomy' => 'property-city',
        'field' => 'slug',
        'terms' => $city
    );
    $parameters.=sprintf( __('City: <strong>%s</strong>; ', 'essential-real-estate'), $city );
}

//bathroom check
if (!empty($bathrooms) && $bathrooms != 'any') {
    $bathrooms = sanitize_text_field($bathrooms);
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_bathrooms',
        'value' => $bathrooms,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Bathrooms: <strong>%s</strong>; ', 'essential-real-estate'), $bathrooms );
}
// bedrooms check
if (!empty($bedrooms) && $bedrooms != 'any') {
    $bedrooms = sanitize_text_field($bedrooms);
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_bedrooms',
        'value' => $bedrooms,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Bedrooms: <strong>%s</strong>; ', 'essential-real-estate'), $bedrooms );
}

// bedrooms check
if (!empty($garage) && $garage != 'any') {
    $garage = sanitize_text_field($garage);
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_garage',
        'value' => $garage,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Garage: <strong>%s</strong>; ', 'essential-real-estate'), $garage );
}

/**
 * Min Max Price & Area Property
 */
if (!empty($min_price) && !empty($max_price)) {
    $min_price = doubleval(ere_clean($min_price));
    $max_price = doubleval(ere_clean($max_price));

    if ($min_price >= 0 && $max_price >= $min_price) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_price',
            'value' => array($min_price, $max_price),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        $parameters.=sprintf( __('Price: <strong>%s - %s</strong>; ', 'essential-real-estate'), $min_price, $max_price);
    }
} else if (!empty($min_price)) {
    $min_price = doubleval(ere_clean($min_price));
    if ($min_price >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_price',
            'value' => $min_price,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        $parameters.=sprintf( __('Min Price: <strong>%s</strong>; ', 'essential-real-estate'), $min_price);
    }
} else if (!empty($max_price)) {
    $max_price = doubleval(ere_clean($max_price));
    if ($max_price >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_price',
            'value' => $max_price,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
        $parameters.=sprintf( __('Max Price: <strong>%s</strong>; ', 'essential-real-estate'), $max_price);
    }
}

// min and max area logic
if (!empty($min_area) && !empty($max_area)) {
    $min_area = intval($min_area);
    $max_area = intval($max_area);

    if ($min_area >= 0 && $max_area >= $min_area) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_size',
            'value' => array($min_area, $max_area),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        $parameters.=sprintf( __('Size Area: <strong>%s - %s</strong>; ', 'essential-real-estate'), $min_area, $max_area);
    }

} else if (!empty($max_area)) {
    $max_area = intval($max_area);
    if ($max_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_size',
            'value' => $max_area,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
        $parameters.=sprintf( __('Max Area: <strong> %s</strong>; ', 'essential-real-estate'), $max_area);
    }
} else if (!empty($min_area)) {
    $min_area = intval($min_area);
    if ($min_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_size',
            'value' => $min_area,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        $parameters.=sprintf( __('Min Area: <strong> %s</strong>; ', 'essential-real-estate'), $min_area);
    }
}

// min and max year built logic
if (!empty($min_year) && !empty($max_year)) {
    $min_year = intval($min_year);
    $max_year = intval($max_year);

    if ($min_year >= 0 && $max_year >= $min_year) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_year',
            'value' => array($min_year, $max_year),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        $parameters.=sprintf( __('Year: <strong>%s - %s</strong>; ', 'essential-real-estate'), $min_year, $max_year);
    }

} else if (!empty($max_year)) {
    $max_year = intval($max_year);
    if ($max_year >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_year',
            'value' => $max_year,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
        $parameters.=sprintf( __('Max Year: <strong>%s</strong>; ', 'essential-real-estate'), $max_year);
    }
} else if (!empty($min_year)) {
    $min_year = intval($min_year);
    if ($min_year >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_year',
            'value' => $min_year,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        $parameters.=sprintf( __('Min Year: <strong>%s</strong>; ', 'essential-real-estate'), $min_year);
    }
}

// min and max garage area logic
if (!empty($min_garage_area) && !empty($max_garage_area)) {
    $min_garage_area = intval($min_garage_area);
    $max_garage_area = intval($max_garage_area);

    if ($min_garage_area >= 0 && $max_garage_area >= $min_garage_area) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_garage_size',
            'value' => array($min_garage_area, $max_garage_area),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        $parameters.=sprintf( __('Garage size: <strong>%s - %s</strong>; ', 'essential-real-estate'), $min_garage_area, $max_garage_area);
    }

} else if (!empty($max_garage_area)) {
    $max_garage_area = intval($max_garage_area);
    if ($max_garage_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_garage_size',
            'value' => $max_garage_area,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
        $parameters.=sprintf( __('Max Garage size: <strong>%s</strong>; ', 'essential-real-estate'), $max_garage_area);
    }
} else if (!empty($min_garage_area)) {
    $min_garage_area = intval($min_garage_area);
    if ($min_garage_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_garage_size',
            'value' => $min_garage_area,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        $parameters.=sprintf( __('Min Garage size: <strong>%s</strong>; ', 'essential-real-estate'), $min_garage_area);
    }
}

// min and max land area logic
if (!empty($min_land_area) && !empty($max_land_area)) {
    $min_land_area = intval($min_land_area);
    $max_land_area = intval($max_land_area);

    if ($min_land_area >= 0 && $max_land_area >= $min_land_area) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_land',
            'value' => array($min_land_area, $max_land_area),
            'type' => 'NUMERIC',
            'compare' => 'BETWEEN',
        );
        $parameters.=sprintf( __('Land size: <strong>%s - %s</strong>; ', 'essential-real-estate'), $min_land_area, $max_land_area);
    }

} else if (!empty($max_land_area)) {
    $max_land_area = intval($max_land_area);
    if ($max_land_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_land',
            'value' => $max_land_area,
            'type' => 'NUMERIC',
            'compare' => '<=',
        );
        $parameters.=sprintf( __('Max Land size: <strong>%s</strong>; ', 'essential-real-estate'), $max_land_area);
    }
} else if (!empty($min_land_area)) {
    $min_land_area = intval($min_land_area);
    if ($min_land_area >= 0) {
        $meta_query[] = array(
            'key' => ERE_METABOX_PREFIX. 'property_land',
            'value' => $min_land_area,
            'type' => 'NUMERIC',
            'compare' => '>=',
        );
        $parameters.=sprintf( __('Min Land size: <strong>%s</strong>; ', 'essential-real-estate'), $min_land_area);
    }
}
/*Country*/
if (!empty($country)) {
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_country',
        'value' => $country,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Country: <strong>%s</strong>; ', 'essential-real-estate'), $country);
}

/*Search advanced by province/state*/
if (!empty($state)) {
    $tax_query[] = array(
        'taxonomy' => 'property-state',
        'field' => 'slug',
        'terms' => $state
    );
    $parameters.=sprintf( __('State: <strong>%s</strong>; ', 'essential-real-estate'), $state);
}
/*Search advanced by neighborhood*/
if (!empty($neighborhood)) {
    $tax_query[] = array(
        'taxonomy' => 'property-neighborhood',
        'field' => 'slug',
        'terms' => $neighborhood
    );
    $parameters.=sprintf( __('Neighborhood: <strong>%s</strong>; ', 'essential-real-estate'), $neighborhood);
}
if (!empty($property_identity)) {
    $property_identity = sanitize_text_field($property_identity);
    $meta_query[] = array(
        'key' => ERE_METABOX_PREFIX. 'property_identity',
        'value' => $property_identity,
        'type' => 'CHAR',
        'compare' => '=',
    );
    $parameters.=sprintf( __('Property ID: <strong>%s</strong>; ', 'essential-real-estate'), $bathrooms );
}
/* other featured query*/
if (!empty($features)) {
    foreach($features as $feature){
        $tax_query[] = array(
            'taxonomy' => 'property-feature',
            'field' => 'slug',
            'terms' => $feature
        );
        $parameters.=sprintf( __('Feature: <strong>%s</strong>; ', 'essential-real-estate'), $feature);
    }
}

$args['meta_query'] = array(
    'relation' => 'AND',
    $keyword_array,
    array(
        'relation' => 'AND',
        $meta_query
    ),
);

$tax_count = count($tax_query);
if ($tax_count > 0) {
    $args['tax_query'] = array(
        'relation' => 'AND',
        $tax_query
    );
}
$data       = new WP_Query( $args );
$search_query=$args;
$total_post = $data->found_posts;
$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'property');
wp_print_styles( ERE_PLUGIN_PREFIX . 'archive-property');

wp_enqueue_script(ERE_PLUGIN_PREFIX . 'archive-property', ERE_PLUGIN_URL . 'public/assets/js/property/ere-archive-property' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
<div class="ere-property-wrap">
    <?php
    $title_enable = ere_get_option( 'search_title_enable', 'true' );
    $location_enable = ere_get_option( 'search_location_enable', 'true' );
    $countries_enable = ere_get_option( 'search_countries_enable', 'false' );
    $states_enable = ere_get_option( 'search_states_enable', 'false' );
    $cities_enable = ere_get_option( 'search_cities_enable', 'true' );
    $neighborhoods_enable = ere_get_option( 'search_neighborhoods_enable', 'false' );
    $types_enable = ere_get_option( 'search_types_enable', 'true' );
    $status_enable = ere_get_option( 'search_status_enable', 'true' );
    $number_bedrooms_enable = ere_get_option( 'search_number_bedrooms_enable', 'true' );
    $number_bathrooms_enable = ere_get_option( 'search_number_bathrooms_enable', 'true' );
    $price_enable = ere_get_option( 'search_price_enable', 'true' );
    $area_enable = ere_get_option( 'search_area_enable', 'true' );
    $year_built_enable = ere_get_option( 'search_year_built_enable', 'true' );
    $labels_enable = ere_get_option( 'search_labels_enable', 'true' );
    $number_garage_enable = ere_get_option( 'search_number_garage_enable', 'true' );
    $garage_area_enable = ere_get_option( 'search_garage_area_enable', 'true' );
    $land_area_enable = ere_get_option( 'search_land_area_enable', 'true' );
    $property_identity_enable = ere_get_option( 'search_property_identity_enable', 'true' );
    $other_features_enable = ere_get_option( 'search_other_features_enable', 'true' );

    echo do_shortcode('[ere_property_search color_scheme="color-light" title_enable="'.$title_enable.'"  location_enable="'.$location_enable.'" countries_enable="'.$countries_enable.'" states_enable="'.$states_enable.'"  cities_enable="'.$cities_enable.'"  neighborhoods_enable="'.$neighborhoods_enable.'" types_enable="'.$types_enable.'" status_enable="'.$status_enable.'" number_bedrooms_enable="'.$number_bedrooms_enable.'" number_bathrooms_enable="'.$number_bathrooms_enable.'" price_enable="'.$price_enable.'" area_enable="'.$area_enable.'" map_search_enable="" advanced_search_enable="true" year_built_enable="'.$year_built_enable.'" labels_enable="'.$labels_enable.'" number_garage_enable="'.$number_garage_enable.'" garage_area_enable="'.$garage_area_enable.'" land_area_enable="'.$land_area_enable.'" property_identity_enable="'.$property_identity_enable.'" other_features_enable="'.$other_features_enable.'" is_page_search="1"]');
    $enable_saved_search = ere_get_option('enable_saved_search', 1);
    if($enable_saved_search==1): ?>
        <div class="text-center">
            <button type="button" class="btn btn-primary btn-xs btn-save-search" data-toggle="modal" data-target="#ere_save_search_modal">
                <?php esc_html_e( 'Save Search', 'essential-real-estate' ) ?>
            </button>
        </div>
        <?php ere_get_template('global/save-search-modal.php',array('parameters'=>$parameters,'search_query'=>$search_query));
    endif; ?>
    <div class="archive-property mg-top-60">
        <div class="above-archive-property mg-bottom-60 sm-mg-bottom-40">
            <div class="ere-heading">
                <span></span>
                <p class="uppercase"><?php echo sprintf(__('%s results','essential-real-estate'), $total_post); ?></p>
                <h2 class="uppercase"><?php esc_html_e( 'Search', 'essential-real-estate' ) ?></h2>
            </div>
            <div class="archive-property-action">
                <div class="sort-property property-dropdown">
                    <span class="property-filter-placeholder"><?php esc_html_e( 'Sort By', 'essential-real-estate' ); ?></span>
                    <ul>
                        <li><a data-sortby="default" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'default' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_html_e( 'Default Order', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Default Order', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="featured" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'featured' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_html_e( 'Featured', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Featured', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="a_price" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'a_price' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_html_e( 'Price (Low to High)', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Price (Low to High)', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="d_price" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'd_price' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_html_e( 'Price (High to Low)', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Price (High to Low)', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="a_date" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'a_date' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_html_e( 'Date (Old to New)', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Date (Old to New)', 'essential-real-estate' ); ?></a>
                        </li>
                        <li><a data-sortby="d_date" href="<?php
                            $pot_link_sortby = add_query_arg( array( 'sortby' => 'd_date' ) );
                            echo esc_url( $pot_link_sortby ) ?>"
                               title="<?php esc_html_e( 'Date (New to Old)', 'essential-real-estate' ); ?>"><?php esc_html_e( 'Date (New to Old)', 'essential-real-estate' ); ?></a>
                        </li>
                    </ul>
                </div>
                <div class="view-as" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
                    <span data-view-as="property-list" class="view-as-list" title="<?php esc_html_e( 'View as List', 'essential-real-estate' ) ?>">
                        <i class="fa fa-list-ul"></i>
                    </span>
                    <span data-view-as="property-grid" class="view-as-grid" title="<?php esc_html_e( 'View as Grid', 'essential-real-estate' ) ?>">
                        <i class="fa fa-th-large"></i>
                    </span>
                </div>
            </div>
        </div>
        <div class="<?php echo join( ' ', $wrapper_classes ) ?>">
            <?php if ( $data->have_posts() ) :
                while ( $data->have_posts() ): $data->the_post(); ?>

                    <?php ere_get_template( 'content-property.php', array(
                        'custom_property_image_size' => '',
                        'property_item_class' => $property_item_class
                    )); ?>

                <?php endwhile;
            else: ?>
                <div class="item-not-found"><?php esc_html_e( 'No item found', 'essential-real-estate' ); ?></div>
            <?php endif; ?>
            <div class="clearfix"></div>
            <?php
            $max_num_pages = $data->max_num_pages;
            ere_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
            wp_reset_postdata(); ?>
        </div>
    </div>
</div>