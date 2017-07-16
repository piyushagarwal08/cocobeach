<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Search')) {
    /**
     * Class ERE_Search
     */

    class ERE_Search
    {

        public function query_all_properties()
        {
            $data = array(
                'post_type' => 'property',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            );
            return new WP_Query($data);
        }

        public function keyword_auto_complete_search()
        {
            $loop = $this->query_all_properties();
            $suggestions_address = array();
            while ($loop->have_posts()) {
                $loop->the_post();
                $suggestions_address[] = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_address', true);
            }
            wp_reset_postdata();
            $suggestions_address = array_unique($suggestions_address);
            $suggestions_address = array_values($suggestions_address);
            $response = json_encode($suggestions_address);
            return $response;
        }

        public function ere_title_auto_complete_search()
        {
            global $wpdb;
            $table = $wpdb->posts;
            $key = $_POST['title'];
            $data = $wpdb->get_results( "SELECT DISTINCT * FROM $table WHERE post_type='property' and post_status='publish' and (post_title LIKE '%$key%' OR post_content LIKE '%$key%')" );
            if ( sizeof( $data ) != 0 ) {
                echo '<ul class="listing-item-by-name">';
                foreach ( $data as $post ) {
                    $property_id = $post->ID;
                    $number_beds = get_post_meta( $property_id, ERE_METABOX_PREFIX.'property_bedrooms', true );
                    $number_baths = get_post_meta( $property_id, ERE_METABOX_PREFIX.'property_bathrooms', true );
                    $size_area = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_size', true);
                    $thumnail_url = get_the_post_thumbnail_url( $property_id, array ( 45, 45 ) );
                    ?>
                    <li class="result-item-by-name clearfix" data-text="<?php echo $post->post_title; ?>">
                        <div class="result-thumbnail-by-name">
                            <a href="<?php the_permalink( $property_id ); ?>" class="media-object">
                                <img src="<?php echo $thumnail_url; ?>" width="45" height="45">
                            </a>
                        </div>
                        <div class="property-info-by-title">
                            <h4>
                                <a href="<?php the_permalink( $property_id ); ?>">
                                    <?php echo $post->post_title; ?>
                                </a>
                            </h4>
                            <ul class="property-meta">
                                <?php if ( !empty( $size_area ) ) : ?>
                                    <li class="area-size-info">
                                        <i class="fa fa-arrows"></i>
                                        <?php echo $size_area; ?>
                                        <?php $measurement_units = ere_get_option('measurement_units', 'SqFt');
                                        echo esc_html(' '.$measurement_units); ?>
                                    </li>
                                <?php endif; ?>
                                <?php if ( !empty( $number_beds ) ) : ?>
                                    <li class="bed-info">
                                        <i class="fa fa-hotel"></i>
                                        <?php echo $number_beds; ?>
                                    </li>
                                <?php endif; ?>
                                <?php if ( !empty( $number_baths ) ) : ?>
                                    <li class="bath-info">
                                        <i class="fa fa-bath"></i>
                                        <?php echo $number_baths; ?>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                    <?php
                }
                echo '</ul>';?>
                <div class="mes-and-link-result">
                    <span class="total-result">
                        <?php echo sizeof($data);?>
                        <?php echo esc_html__(' result.','essential-real-estate');?>
                    </span>
                    <div class="link-area">
                        <?php
                        $advanced_search = ere_get_permalink('advanced_search');
                        $link_result = $advanced_search.'?title='.$key;
                        ?>
                        <a href="<?php echo esc_url($link_result);?>" class="link-go-search">
                            <?php echo esc_html__('View result','essential-real-estate');?>
                        </a>
                        <a href="javascript:;" class="link-close">
                            <i class="icon-cross"></i>
                        </a>
                    </div>
                </div>
                <?php wp_die();
            }else{
            ?>
                <div class="result">
                   <p> <?php esc_html_e('No result by your title','essential-real-estate'); ?> </p>
                </div>
            <?php }
        }

        public function get_property_countries($country_target_code)
        {
            $data = array(
                'post_type' => 'property',
                'posts_per_page' => -1,
                'post_status' => 'publish'
            );
            $properties = get_posts($data);
            $key_country = array();
            foreach($properties as $prop){
                $key_country[] = get_post_meta($prop->ID, ERE_METABOX_PREFIX . 'property_country', true);
            }
            $key_country = array_unique($key_country);
            foreach($key_country as $key){
                $country_name = ere_get_country_by_code($key);
                if($key == $country_target_code){
                    echo '<option value="' . $key . '" selected>' . $country_name . '</option>';
                }else{
                    echo '<option value="' . $key . '">' . $country_name . '</option>';
                }

            }
            wp_reset_query();
        }

        public function ere_property_search_ajax()
        {
            check_ajax_referer('ere_search_map_ajax_nonce', 'ere_security_search_map');
            $meta_query = array();
            $tax_query = array();

            $title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
            $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
            $address_keyword = isset($_REQUEST['address_keyword']) ? $_REQUEST['address_keyword'] : '';
            $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
            $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : '';
            $bathrooms = isset($_REQUEST['bathrooms']) ? $_REQUEST['bathrooms'] : '';
            $bedrooms = isset($_REQUEST['bedrooms']) ? $_REQUEST['bedrooms'] : '';
            $min_area = isset($_REQUEST['min_area']) ? $_REQUEST['min_area'] : '';
            $max_area = isset($_REQUEST['max_area']) ? $_REQUEST['max_area'] : '';
            $min_price = isset($_REQUEST['min_price']) ? $_REQUEST['min_price'] : '';
            $max_price = isset($_REQUEST['max_price']) ? $_REQUEST['max_price'] : '';
            $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
            $country = isset($_REQUEST['country']) ? $_REQUEST['country'] : '';
            $neighborhood = isset($_REQUEST['neighborhood']) ? $_REQUEST['neighborhood'] : '';
            $min_year = isset($_REQUEST['min_year']) ? $_REQUEST['min_year'] : '';
            $max_year = isset($_REQUEST['max_year']) ? $_REQUEST['max_year'] : '';
            $label = isset($_REQUEST['label']) ? $_REQUEST['label'] : '';
            $garage = isset($_REQUEST['garage']) ? $_REQUEST['garage'] : '';
            $min_garage_area = isset($_REQUEST['min_garage_area']) ? $_REQUEST['min_garage_area'] : '';
            $max_garage_area = isset($_REQUEST['max_garage_area']) ? $_REQUEST['max_garage_area'] : '';
            $min_land_area = isset($_REQUEST['min_land_area']) ? $_REQUEST['min_land_area'] : '';
            $max_land_area = isset($_REQUEST['max_land_area']) ? $_REQUEST['max_land_area'] : '';
            $property_identity = isset($_REQUEST['property_identity']) ? $_REQUEST['property_identity'] : '';
            $features = isset($_REQUEST['features']) ? $_REQUEST['features'] : '';
            if($features != '') {
                $features = explode( ';',$features );
            }
            $search_type = isset($_REQUEST['search_type']) ? $_REQUEST['search_type'] : '';

            $query_args = array(
                'post_type' => 'property',
                'posts_per_page' => -1,
                'post_status' => 'publish',
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
                $query_args['post__in'] = $IDs;
            }
            $address_array = array();
            if (!empty($address_keyword)) {
                $address_keyword = sanitize_text_field($address_keyword);
                $address_array = array(
                    'key' => ERE_METABOX_PREFIX. 'property_address',
                    'value' => $address_keyword,
                    'type' => 'CHAR',
                    'compare' => 'LIKE',
                );
            }

            //tax query property type
            if (!empty($type)) {
                $tax_query[] = array(
                    'taxonomy' => 'property-type',
                    'field' => 'slug',
                    'terms' => $type
                );
            }

            //tax query property status
            if (!empty($status)) {
                $tax_query[] = array(
                    'taxonomy' => 'property-status',
                    'field' => 'slug',
                    'terms' => $status
                );
            }

            //tax query property labels
            if (!empty($label)) {
                $tax_query[] = array(
                    'taxonomy' => 'property-labels',
                    'field' => 'slug',
                    'terms' => $label
                );
            }

            //city
            if (!empty($city)) {
                $tax_query[] = array(
                    'taxonomy' => 'property-city',
                    'field' => 'slug',
                    'terms' => $city
                );
            }

            //bathroom
            if (!empty($bathrooms) && $bathrooms != 'any') {
                $bathrooms = sanitize_text_field($bathrooms);
                $meta_query[] = array(
                    'key' => ERE_METABOX_PREFIX. 'property_bathrooms',
                    'value' => $bathrooms,
                    'type' => 'CHAR',
                    'compare' => '=',
                );
            }

            // bedrooms
            if (!empty($bedrooms) && $bedrooms != 'any') {
                $bedrooms = sanitize_text_field($bedrooms);
                $meta_query[] = array(
                    'key' => ERE_METABOX_PREFIX. 'property_bedrooms',
                    'value' => $bedrooms,
                    'type' => 'CHAR',
                    'compare' => '=',
                );
            }

            // bedrooms
            if (!empty($garage) && $garage != 'any') {
                $garage = sanitize_text_field($garage);
                $meta_query[] = array(
                    'key' => ERE_METABOX_PREFIX. 'property_garage',
                    'value' => $garage,
                    'type' => 'CHAR',
                    'compare' => '=',
                );
            }

            // min and max price logic
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
                }
            } else if (!empty($max_price)) {
                $max_price = doubleval(ere_clean($max_price));
                if ($max_price >= 0) {
                    $meta_query[] = array(
                        'key' => ERE_METABOX_PREFIX . 'property_price',
                        'value' => $max_price,
                        'type' => 'NUMERIC',
                        'compare' => '<=',
                    );
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
                }
            }
            /*Country*/
            if (!empty($country)) {//check value
                $meta_query[] = array(
                    'key' => ERE_METABOX_PREFIX. 'property_country',
                    'value' => $country,
                    'type' => 'CHAR',
                    'compare' => '=',
                );
            }
            if (!empty($property_identity)) {
                $property_identity = sanitize_text_field($property_identity);
                $meta_query[] = array(
                    'key' => ERE_METABOX_PREFIX. 'property_identity',
                    'value' => $property_identity,
                    'type' => 'CHAR',
                    'compare' => '=',
                );
            }
            /*Search advanced by province/state*/
            if (!empty($state)) {
                $tax_query[] = array(
                    'taxonomy' => 'property-state',
                    'field' => 'slug',
                    'terms' => $state
                );
            }
            /*Search advanced by neighborhood*/
            if (!empty($neighborhood)) {
                $tax_query[] = array(
                    'taxonomy' => 'property-neighborhood',
                    'field' => 'slug',
                    'terms' => $neighborhood
                );
            }
            /* other featured query*/
            if (!empty($features)) {
                foreach($features as $feature){
                    $tax_query[] = array(
                        'taxonomy' => 'property-feature',
                        'field' => 'slug',
                        'terms' => $feature
                    );
                }
            }

            $query_args['meta_query'] = array(
                'relation' => 'AND',
                $address_array,
                array(
                    'relation' => 'AND',
                    $meta_query
                ),
            );

            $tax_count = count($tax_query);
            if ($tax_count > 0) {
                $query_args['tax_query'] = array(
                    'relation' => 'AND',
                    $tax_query
                );
            }

            $query_args = new WP_Query($query_args);
            $properties = array();
            $total_post = $query_args->found_posts;
            $property_html = '';
            if($total_post > 0){
                $custom_property_image_size = '370x220';
                $property_item_class = array('property-item');
                if($search_type == 'map_and_content') {
                    $property_html = '<div class="ere-ajax-property-wrap">';
                }
                while ($query_args->have_posts()): $query_args->the_post();
                    $property_id = get_the_ID();
                    $property_location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
                    if (!empty($property_location['location'])) {
                        $lat_lng = explode(',', $property_location['location']);
                    } else {
                        $lat_lng = array();
                    }
                    $attach_id = get_post_thumbnail_id();
                    $width = 103;
                    $height = 97;
                    if (!empty($attach_id)) {
                        $image_src = ere_image_resize_id($attach_id, $height, $width, true);
                    } else {
                        $image_src = '';
                    }
                    $property_type = get_the_terms($property_id, 'property-type');
                    $property_url = '';
                    if ($property_type) {
                        $property_type_id = $property_type[0]->term_id;
                        $property_type_icon = get_term_meta($property_type_id, 'property_type_icon', true);
                        if (is_array($property_type_icon) && count($property_type_icon) > 0) {
                            $property_url = $property_type_icon['url'];
                        }
                    }

                    $property_address = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_address', true);
                    $properties_price = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', true);
                    $properties_price = ere_get_format_money($properties_price);
                    $prop = new stdClass();
                    $prop->image_url = $image_src;
                    $prop->title = get_the_title();
                    $prop->lat = $lat_lng[0];
                    $prop->lng = $lat_lng[1];
                    $prop->url = get_permalink();
                    $prop->price = $properties_price;
                    $prop->address = $property_address;
                    if ($property_url == '') {
                        $property_url = ERE_PLUGIN_URL . 'public/assets/images/map-marker-icon.png';
                        $default_marker=ere_get_option('marker_icon','');
                        if($default_marker!='')
                        {
                            $property_url=$default_marker['url'];
                        }
                    }
                    $prop->marker_icon = $property_url;
                    array_push($properties, $prop);

                    if($search_type == 'map_and_content') {
                        $property_html .= ere_get_template_html('content-property.php', array(
                            'custom_property_image_size' => $custom_property_image_size,
                            'property_item_class' => $property_item_class,
                        ));
                    }
                endwhile;
                if($search_type == 'map_and_content') {
                    $property_html .= '</div>';
                }
            }
            wp_reset_postdata();

            if (count($properties) > 0) {
                echo json_encode(array('success' => true, 'properties' => $properties, 'property_html' => $property_html));
            } else {
                echo json_encode(array('success' => false));
            }
            die();
        }

        public function ere_ajax_search_on_change_value()
        {
            $type = isset($_POST['type']) ? $_POST['type'] : '';
            $status = isset($_POST['status']) ? $_POST['status'] : '';

            /*Query all property publish*/
            $query_args = array(
                'post_type' => 'property',
                'posts_per_page' => -1,
                'post_status' => 'publish',
            );

            $query_args['tax_query'] = array();
            if (!empty($type) && !empty($status)) {
                $query_args['tax_query'] = array(
                    'relation' => 'AND'
                );
            }

            //tax query property type
            if (!empty($type)) {
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'property-type',
                    'field' => 'slug',
                    'terms' => $type
                );
            }

            //tax query property status
            if (!empty($status)) {
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'property-status',
                    'field' => 'slug',
                    'terms' => $status
                );
            }

            $property_price = array();
            $property_area = array();
            $data = get_posts($query_args);
            foreach ($data as $property):
                $property_id = $property->ID;
                $property_price[] = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', true);
                $property_area[] = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_size', true);
            endforeach;
            wp_reset_postdata();
            echo json_encode(array('success' => true, 'property_price' => $property_price, 'property_area' => $property_area));

            die();
        }
    }
}