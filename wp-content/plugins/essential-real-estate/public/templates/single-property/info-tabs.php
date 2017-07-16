<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 17/01/2017
 * Time: 09:50 AM
 */
global $post;
$property_meta_data = get_post_custom(get_the_ID());
$property_types = get_the_terms(get_the_ID(), 'property-type');
$property_type_arr = array();
if ($property_types) {
    foreach ($property_types as $property_type) {
        $property_type_arr[] = $property_type->name;
    }
}

$property_status = get_the_terms(get_the_ID(), 'property-status');
$property_status_arr = array();
if ($property_status) {
    foreach ($property_status as $property_stt) {
        $property_status_arr[] = $property_stt->name;
    }
}

$property_features = get_the_terms(get_the_ID(), 'property-feature');

$property_label = get_the_terms(get_the_ID(), 'property-labels');
$property_label_arr = array();
if ($property_label) {
    foreach ($property_label as $label) {
        $property_label_arr[] = $label->name;
    }
}

$property_neighborhood = get_the_terms(get_the_ID(), 'property-neighborhood');
$property_neighborhood_arr = array();
if ($property_neighborhood) {
    foreach ($property_neighborhood as $neighborhood_item) {
        $property_neighborhood_arr[] = $neighborhood_item->name;
    }
}

$property_city = get_the_terms(get_the_ID(), 'property-city');
$property_city_arr = array();
if ($property_city) {
    foreach ($property_city as $city_item) {
        $property_city_arr[] = $city_item->name;
    }
}

$property_state = get_the_terms(get_the_ID(), 'property-state');
$property_state_arr = array();
if ($property_state) {
    foreach ($property_state as $state_item) {
        $property_state_arr[] = $state_item->name;
    }
}

$property_location = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_location', true);
$property_identity = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_identity']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_identity'][0] : '';
$property_video = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_video_url']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_video_url'][0] : '';
$property_video_image = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_video_image']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_video_image'][0] : '';
$property_image_360 = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_image_360', true);
$property_image_360 = (isset($property_image_360)&& is_array($property_image_360)) ? $property_image_360['url'] : '';
$property_virtual_tour = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_virtual_tour', true);
$property_virtual_tour_type = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_virtual_tour_type', true);
if(empty($property_virtual_tour_type))
{
    $property_virtual_tour_type='0';
}
$price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
$price_postfix      = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ][0] : '';
$property_year = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_year']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_year'][0] : '';
$property_bathrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0] : '0';
$property_bedrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0] : '0';
$property_garage_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size'][0] : '';
$property_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0] : '';
$additional_features = isset($property_meta_data[ERE_METABOX_PREFIX . 'additional_features']) ? $property_meta_data[ERE_METABOX_PREFIX . 'additional_features'][0] : '';
$measurement_units = ere_get_option('measurement_units', 'SqFt');

$half_additional_features = 0;
$additional_feature_title = $additional_feature_value = null;
if ($additional_features > 0) {
    $additional_feature_title = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'additional_feature_title', true);
    $additional_feature_value = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'additional_feature_value', true);
    $half_additional_features = floor($additional_features / 2);
}
$property_garage = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage'][0] : '0';
$property_land = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_land']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_land'][0] : '';
$property_address = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_address']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_address'][0] : '';
$property_country = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_country']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_country'][0] : '';
$property_zip = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_zip']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_zip'][0] : '';
?>

<div class="property-info-tabs property-tab mg-bottom-45 sm-mg-bottom-25">
    <?php $content = get_the_content(); ?>
    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#overview"><?php esc_html_e('Overview', 'essential-real-estate'); ?></a>
        </li>
        <?php if ($property_features): ?>
            <li><a data-toggle="tab" href="#features"><?php esc_html_e('Features', 'essential-real-estate'); ?></a></li>
        <?php endif; ?>
        <?php if (isset($content) && !empty($content)): ?>
            <li><a data-toggle="tab" href="#description"><?php esc_html_e('Description', 'essential-real-estate'); ?></a></li>
        <?php endif; ?>
        <li><a data-toggle="tab" href="#location"><?php esc_html_e('Location', 'essential-real-estate'); ?></a></li>
        <?php if (!empty($property_video)) : ?>
            <li><a data-toggle="tab" href="#video"><?php esc_html_e('Video', 'essential-real-estate'); ?></a></li>
        <?php endif; ?>

        <?php

        if ((!empty($property_image_360)||!empty($property_virtual_tour))&& ($property_virtual_tour_type=='0' || $property_virtual_tour_type=='1')): ?>
            <li><a data-toggle="tab" href="#virtual_tour_360"><?php esc_html_e('Virtual Tour', 'essential-real-estate'); ?></a></li>
        <?php endif; ?>
    </ul>
    <div class="tab-content">
        <div id="overview" class="tab-pane fade in active row">
            <div class="col-md-6">
                <table class="overview-table">
                    <tbody>
                    <?php if (!empty($price)): ?>
                        <tr>
                            <th><?php esc_html_e('Price', 'essential-real-estate'); ?></th>
                            <td><span><?php echo ere_get_format_money($price) ?><?php if(!empty( $price_postfix )) {echo '<span>/'.$price_postfix.'</span>';} ?></span></td>
                        </tr>
                    <?php elseif (ere_get_option('empty_price_text', '')!=''): ?>
                        <tr>
                            <th><?php esc_html_e('Price', 'essential-real-estate'); ?></th>
                            <td><span><?php echo ere_get_option('empty_price_text', '') ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (count($property_type_arr) > 0): ?>
                        <tr>
                            <th><?php esc_html_e('Property Type', 'essential-real-estate'); ?></th>
                            <td><span><?php echo join(', ', $property_type_arr) ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_year)): ?>
                        <tr>
                            <th><?php esc_html_e('Year Built', 'essential-real-estate'); ?></th>
                            <td><span><?php echo esc_attr($property_year) ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_bathrooms)): ?>
                        <tr>
                            <th><?php esc_html_e('Bathrooms', 'essential-real-estate'); ?></th>
                            <td><span><?php echo esc_attr($property_bathrooms) ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_garage_size)): ?>
                        <tr>
                            <th><?php esc_html_e('Garage Size', 'essential-real-estate'); ?></th>
                            <td>
                                <span><?php echo sprintf("%s %s", $property_garage_size, $measurement_units); ?></span>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_size)): ?>
                        <tr>
                            <th><?php esc_html_e('Area size', 'essential-real-estate'); ?></th>
                            <td>
                                <span><?php echo sprintf("%s %s", $property_size, $measurement_units); ?></span>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php for ($i = 0; $i < $half_additional_features; $i++) { ?>
                        <?php if (!empty($additional_feature_title[$i]) && !empty($additional_feature_value[$i])): ?>
                            <tr>
                                <th><?php echo esc_attr($additional_feature_title[$i]); ?></th>
                                <td><span><?php echo esc_attr($additional_feature_value[$i]) ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6 sm-mg-top-30">
                <table class="overview-table">
                    <tbody>
                    <tr>
                        <th><?php esc_html_e('Property ID', 'essential-real-estate'); ?></th>
                        <td><span><?php
                                if(!empty($property_identity))
                                {
                                    echo esc_html($property_identity);
                                }
                                else
                                {
                                    echo get_the_ID();
                                }
                                ?></span></td>
                    </tr>
                    <?php if (count($property_status_arr) > 0): ?>
                        <tr>
                            <th><?php esc_html_e('Property status', 'essential-real-estate'); ?></th>
                            <td><span><?php echo join(', ', $property_status_arr) ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (count($property_label_arr) > 0): ?>
                        <tr>
                            <th><?php esc_html_e('Labels', 'essential-real-estate'); ?></th>
                            <td><?php if ($property_label_arr): ?>
                                    <span><?php echo join(', ', $property_label_arr) ?></span><?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_bedrooms)): ?>
                        <tr>
                            <th><?php esc_html_e('Bedrooms', 'essential-real-estate'); ?></th>
                            <td><span><?php echo esc_attr($property_bedrooms) ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_garage)): ?>
                        <tr>
                            <th><?php esc_html_e('Garages', 'essential-real-estate'); ?></th>
                            <td><span><?php echo esc_attr($property_garage) ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_land)): ?>
                        <tr>
                            <th><?php esc_html_e('Land area', 'essential-real-estate'); ?></th>
                            <td>
							<span><?php $measurement_units = ere_get_option('measurement_units', 'SqFt');
                                echo sprintf("%s %s", $property_land, $measurement_units); ?></span>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php for ($i = $half_additional_features; $i < $additional_features; $i++) { ?>
                        <?php if (!empty($additional_feature_title[$i]) && !empty($additional_feature_value[$i])): ?>
                            <tr>
                                <th><?php echo esc_attr($additional_feature_title[$i]); ?></th>
                                <td><span><?php echo esc_attr($additional_feature_value[$i]) ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if ($property_features): ?>
            <div id="features" class="tab-pane fade in row">
                <?php foreach ($property_features as $features_item) {
                    echo '<div class="col-md-3 col-xs-6 col-mb-12 mg-bottom-10"><span><i class="fa fa-check-square-o"></i> ' . $features_item->name . '</span></div>';
                } ?>
            </div>
        <?php endif; ?>
        <?php if (isset($content) && !empty($content)): ?>
            <div id="description" class="tab-pane fade">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>
        <div id="location" class="tab-pane fade row">
            <div class="col-md-6">
                <table class="location-table">
                    <tbody>
                    <?php if (!empty($property_address)): ?>
                        <tr>
                            <th><?php esc_html_e('Address', 'essential-real-estate'); ?></th>
                            <td><span><?php echo esc_attr($property_address) ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php
                    $google_map_address_url = $property_location['address'];
                    if (!empty($google_map_address_url)):
                        $google_map_address_url = "http://maps.google.com/?q=" . $google_map_address_url;
                        ?>
                        <tr>
                            <th><?php esc_html_e('View Map', 'essential-real-estate'); ?></th>
                            <td><a target="_blank"
                                   href="<?php echo esc_url($google_map_address_url); ?>"><?php esc_html_e('Open on Google Maps', 'essential-real-estate'); ?>
                                    <i class="fa fa-map-marker accent-color"></i></a></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_country)): ?>
                        <?php $property_country = ere_get_country_by_code($property_country); ?>
                        <tr>
                            <th><?php esc_html_e('Country', 'essential-real-estate'); ?></th>
                            <td><span><?php echo esc_attr($property_country); ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (count($property_neighborhood_arr) > 0): ?>
                        <tr>
                            <th><?php esc_html_e('Neighborhood', 'essential-real-estate'); ?></th>
                            <td><span><?php echo join(', ', $property_neighborhood_arr); ?></span></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="location-table">
                    <tbody>
                    <?php if (count($property_city_arr) > 0): ?>
                        <tr>
                            <th><?php esc_html_e('Cities', 'essential-real-estate'); ?></th>
                            <td><span><?php echo join(', ', $property_city_arr); ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (count($property_state_arr) > 0): ?>
                        <tr>
                            <th><?php esc_html_e('Province / State', 'essential-real-estate'); ?></th>
                            <td><span><?php echo join(', ', $property_state_arr); ?></span></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($property_zip)): ?>
                        <tr>
                            <th><?php esc_html_e('Postal code / ZIP', 'essential-real-estate'); ?></th>
                            <td><span><?php echo esc_attr($property_zip) ?></span></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        if (!empty($property_video)) :?>
            <div id="video" class="tab-pane fade">
                <div class="video<?php if (!empty($property_video_image)): ?> video-has-thumb<?php endif; ?>">
                    <div class="entry-thumb-wrap">
                        <?php if (wp_oembed_get($property_video)) : ?>
                            <?php
                            $image_src = ere_image_resize_id($property_video_image, 870, 420, true);
                            $width = '870';
                            $height = '420';
                            if (!empty($image_src)):?>
                                <div class="entry-thumbnail property-video ere-light-gallery">
                                    <img class="img-responsive" src="<?php echo esc_url($image_src); ?>"
                                         width="<?php echo esc_attr($width) ?>"
                                         height="<?php echo esc_attr($height) ?>"
                                         alt="<?php the_title_attribute(); ?>"/>
                                    <a class="ere-view-video"
                                       data-src="<?php echo esc_url($property_video); ?>"><i class="fa fa-play-circle-o"></i></a>
                                </div>
                            <?php else: ?>
                                <div class="embed-responsive embed-responsive-16by9 embed-responsive-full">
                                    <?php echo wp_oembed_get($property_video, array('wmode' => 'transparent')); ?>
                                </div>
                            <?php endif; ?>
                        <?php else : ?>
                            <div class="embed-responsive embed-responsive-16by9 embed-responsive-full">
                                <?php echo wp_kses_post($property_video); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php
        if (!empty($property_image_360) && $property_virtual_tour_type=='0') :?>
            <div id="virtual_tour_360" class="tab-pane fade">
                <iframe width="100%" height="600" scrolling="no" allowfullscreen src="<?php echo ERE_PLUGIN_URL."public/assets/packages/vr-view/index.html?image=".esc_url($property_image_360); ?>"></iframe>
            </div>
        <?php elseif(!empty($property_virtual_tour)&& $property_virtual_tour_type=='1'): ?>
            <div id="virtual_tour_360" class="tab-pane fade">
                <?php echo (!empty($property_virtual_tour)?  do_shortcode($property_virtual_tour): '') ?>
            </div>
        <?php endif; ?>
    </div>
</div>