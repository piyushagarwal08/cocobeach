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
$status_enable = $el_class ='';
extract(shortcode_atts(array(
    'status_enable' => '',
    'el_class' => '',
), $atts));
$request_keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$request_status = isset($_GET['status']) ? $_GET['status'] : '';

$wrapper_class='ere-mini-search-properties clearfix';
$wrapper_classes = array(
    $wrapper_class,
    $el_class,
);

$ere_search = new ERE_Search();
$enable_auto_complete = ere_get_option('auto_complete_enable');

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
if ($enable_auto_complete != 0) {
    wp_enqueue_script('jquery-ui-autocomplete');
}
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'mini_search_js', ERE_PLUGIN_URL.'public/templates/shortcodes/property-mini-search/assets/js/property-mini-search' . $min_suffix_js . '.js', array(), ERE_PLUGIN_VER, true);
if ($enable_auto_complete != 0) {
    wp_enqueue_script('jquery-ui-autocomplete');
    wp_localize_script(ERE_PLUGIN_PREFIX . 'mini_search_js', 'ere_mini_search_vars',
        array(
            'keyword_auto_complete' => $ere_search->keyword_auto_complete_search(),
        )
    );
}

wp_print_styles( ERE_PLUGIN_PREFIX . 'mini_search_style');
$advanced_search = ere_get_permalink('advanced_search');
?>
<div class="<?php echo join(' ', $wrapper_classes) ?>">
    <div data-href="<?php echo esc_url($advanced_search) ?>" class="ere-mini-search-properties-form">
        <?php if ($status_enable == 'true'): ?>
            <select name="status" title="<?php esc_html_e('Property Status', 'essential-real-estate') ?>"
                    class="ere-status search-field" data-default-value="">
                <?php ere_get_taxonomy_slug('property-status', $request_status); ?>
            </select>
        <?php endif; ?>
        <input type="text" class="ere-location search-field" data-default-value=""
               value="<?php echo esc_attr($request_keyword); ?>"
               name="keyword"
               placeholder="<?php esc_html_e('Where do you like to live?', 'essential-real-estate') ?>">
        <button type="button" id="mini-search-btn"><i class="fa fa-search"></i>
        </button>
    </div>
</div>