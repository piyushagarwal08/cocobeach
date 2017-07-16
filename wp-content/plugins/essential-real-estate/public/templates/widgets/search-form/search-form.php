<?php
/**
 * Created by G5Theme.
 * User: Kaga
 * Date: 21/12/2016
 * Time: 9:35 AM
 */

$location = (!empty($instance['location'])) ? ($instance['location']) : '0';
$city = (!empty($instance['city'])) ? ($instance['city']) : '0';
$status = (!empty($instance['status'])) ? ($instance['status']) : '0';
$type = (!empty($instance['type'])) ? ($instance['type']) : '0';
$number_bedroom = (!empty($instance['number_bedroom'])) ? ($instance['number_bedroom']) : '0';
$number_bathroom = (!empty($instance['number_bathroom'])) ? ($instance['number_bathroom']) : '0';
$slider_filter_price = (!empty($instance['slider_filter_price'])) ? ($instance['slider_filter_price']) : '0';
$slider_filter_area = (!empty($instance['slider_filter_area'])) ? ($instance['slider_filter_area']) : '0';
$new_tab = (!empty($instance['new_tab'])) ? ($instance['new_tab']) : '0';
$text_submit = (!empty($instance['text_submit'])) ? ($instance['text_submit']) : '';

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'search-form-widget');
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'search-form-widget-js', ERE_PLUGIN_URL . 'public/templates/widgets/search-form/assets/js/search-form' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);

$args = array(
    'post_type' => 'property',
    'posts_per_page' => -1,
    'post_status' => 'publish'
);
$data = get_posts($args);
$price = $property_address = $property_size = array();
foreach ($data as $property):
    $property_ID = $property->ID;
    $price[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_price', true);
    $property_size[] = get_post_meta($property_ID, ERE_METABOX_PREFIX . 'property_size', true);
endforeach;

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
?>
    <div class="ere-widget-search-form">
        <?php  $advanced_search = ere_get_permalink('advanced_search');?>
        <?php if($new_tab === 1){
            $new_tab_set = 'target="_blank"';
        }else{
            $new_tab_set = '';
        }?>
        <form method="get" <?php if(!empty($new_tab_set)){esc_attr_e($new_tab_set);}?> action="<?php echo esc_url($advanced_search)?>" class="search-properties-form widget-search-form">
            <?php if($location === 1):?>
                <div class="form-group">
                    <input type="text" class="ere-location form-control"
                           value="<?php echo isset ($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                           name="keyword" placeholder="<?php esc_html_e('Location', 'essential-real-estate') ?>">
                </div>
            <?php endif;?>
            <?php if($city === 1):?>
                <div class="form-group">
                    <select name="city">
                        <?php ere_get_taxonomy_slug('property-city'); ?>
                        <option value="" selected>
                            <?php esc_html_e('All Cities', 'essential-real-estate'); ?>
                        </option>
                    </select>
                </div>
            <?php endif;?>
            <?php if($type === 1):?>
                <div class="form-group">
                    <select name="type">
                        <?php ere_get_taxonomy_slug('property-type'); ?>
                        <option value="" selected>
                            <?php esc_html_e('All Types', 'essential-real-estate') ?>
                        </option>
                    </select>
                </div>
            <?php endif;?>
            <?php if($status === 1):?>
                <div class="form-group">
                    <select name="status">
                        <?php ere_get_taxonomy_slug('property-status'); ?>
                        <option value="" selected>
                            <?php esc_html_e('All Status', 'essential-real-estate') ?>
                        </option>
                    </select>
                </div>
            <?php endif;?>

            <?php if($number_bedroom === 1):?>
                <div class="form-group">
                    <select name="bedrooms">
                        <option value="any">
                            <?php esc_html_e('Any Bedrooms', 'essential-real-estate') ?>
                        </option>
                        <?php
                        $max_bedrooms = '';
                        $max_bedrooms = ere_get_option('max_number_bedrooms');
                        $max_bedrooms = (int)$max_bedrooms;
                        ?>
                        <?php for($i = 1; $i<=$max_bedrooms; $i++):?>
                            <option value="<?php echo esc_attr($i)?>">
                                <?php echo esc_html($i);?>
                            </option>
                        <?php endfor;?>
                    </select>
                </div>
            <?php endif;?>


            <?php if($number_bathroom === 1):?>
                <div class="form-group">
                    <select name="bathrooms">
                        <option value="any">
                            <?php esc_html_e('Any Bathrooms', 'essential-real-estate') ?>
                        </option>
                        <?php
                        $max_bathrooms = '';
                        $max_bathrooms = ere_get_option('max_number_bathrooms');
                        $max_bathrooms = (int)$max_bathrooms;
                        ?>
                        <?php for($i = 1; $i<=$max_bathrooms; $i++):?>
                            <option value="<?php echo esc_attr($i)?>">
                                <?php echo esc_html($i);?>
                            </option>
                        <?php endfor;?>
                    </select>
                </div>
            <?php endif;?>

            <?php if($slider_filter_price === 1):?>
                <div class="form-group ere-sliderbar-price ere-sliderbar-filter"
                     data-min-default="<?php echo esc_attr($min_price) ?>"
                     data-max-default="<?php echo esc_attr($max_price); ?>"
                     data-min="<?php echo esc_attr($min_price) ?>"
                     data-max="<?php echo esc_attr($max_price); ?>">
                    <div class="title-slider-filter">
                        <span><?php esc_html_e('Price', 'essential-real-estate') ?> [</span><span
                            class="min-value"><?php echo ere_get_format_number($min_price) ?></span> - <span
                            class="max-value"><?php echo ere_get_format_number($max_price) ?></span><span>]<?php echo ere_get_option('currency_sign', '$').'</span>'; ?>
                        <input type="hidden" name="min-price" class="min-input-request" value="<?php echo esc_attr($min_price)?>">
                        <input type="hidden" name="max-price" class="max-input-request" value="<?php echo esc_attr($max_price)?>">
                    </div>
                    <div class="sidebar-filter">
                    </div>
                </div>
            <?php endif;?>

            <?php if($slider_filter_area === 1):?>
                <div class="form-group ere-sliderbar-area ere-sliderbar-filter"
                     data-min-default="<?php echo esc_attr($min_area) ?>"
                     data-max-default="<?php echo esc_attr($max_area) ?>"
                     data-min="<?php echo esc_attr($min_area) ?>"
                     data-max="<?php echo esc_attr($max_area); ?>">
                    <div class="title-slider-filter">
                        <span><?php esc_html_e('Area', 'essential-real-estate') ?> [</span><span
                            class="min-value"><?php echo ere_get_format_number($min_area) ?></span> - <span
                            class="max-value"><?php echo ere_get_format_number($max_area) ?></span><span>]
                        <?php $measurement_units = ere_get_option('measurement_units','SqFt'); echo esc_attr($measurement_units).'</span>';?>
                        <input type="hidden" name="min-area" class="min-input-request" value="<?php echo esc_attr($min_area)?>">
                        <input type="hidden" name="max-area" class="max-input-request" value="<?php echo esc_attr($max_area)?>">
                    </div>
                    <div class="sidebar-filter">
                    </div>
                </div>
            <?php endif;?>
            <div class="form-group group-submit">
                <button type="submit">
                    <?php if(!empty($text_submit)):?>
                        <?php echo esc_attr($text_submit);?>
                    <?php else:?>
                        <?php esc_html_e('Go Search', 'essential-real-estate')?>
                    <?php endif;?>
                </button>
            </div>
        </form>
    </div>
<?php
wp_reset_postdata();