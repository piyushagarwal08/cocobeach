<?php
get_header('ere');
$agencies = ere_get_option('agent_agencies', '');
$agent_layout_style = ere_get_option('archive_agent_layout_style', 'agent-grid');
$posts_per_page = ere_get_option('archive_agent_item_amount', 12);
$column_lg = ere_get_option('archive_agent_column_lg', '4');
$column_md = ere_get_option('archive_agent_column_md', '3');
$column_sm = ere_get_option('archive_agent_column_sm', '2');
$column_xs = ere_get_option('archive_agent_column_xs', '2');
$column_mb = ere_get_option('archive_agent_column_mb', '1');

if (isset($_SESSION["agent_view_as"]) && !empty($_SESSION["agent_view_as"]) && in_array($_SESSION["agent_view_as"], array('agent-list', 'agent-grid'))) {
    $agent_layout_style = $_SESSION["agent_view_as"];
}

$wrapper_classes = array(
    'ere-agent clearfix',
    $agent_layout_style,
);
if ($agent_layout_style == 'agent-list') {
    $wrapper_classes[] = 'list-1-column';
}

$gf_item_wrap = '';

$gf_item_wrap = 'ere-item-wrap';
$wrapper_classes[] = 'row columns-' . $column_lg . ' columns-md-' . $column_md . ' columns-sm-' . $column_sm . ' columns-xs-' . $column_xs . ' columns-mb-' . $column_mb . '';

$args = array(
    'posts_per_page' => $posts_per_page,
    'post_type' => 'agent',
    'orderby' => 'date',
    'order' => 'DESC',
    'offset' => (max(1, get_query_var('paged')) - 1) * $posts_per_page,
    'ignore_sticky_posts' => 1,
    'post_status' => 'publish'
);
if (isset($_GET['sortby']) && in_array($_GET['sortby'], array('a_date','d_date','a_name','d_name'))) {
    if ($_GET['sortby'] == 'a_date') {
        $args['orderby'] = 'date';
        $args['order'] = 'ASC';
    } else if ($_GET['sortby'] == 'd_date') {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }else if ($_GET['sortby'] == 'a_name') {
        $args['orderby'] = 'post_title';
        $args['order'] = 'ASC';
    }else if ($_GET['sortby'] == 'd_name') {
        $args['orderby'] = 'post_title';
        $args['order'] = 'DESC';
    }
}
if ($agencies != '') {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'agencies',
            'field' => 'slug',
            'terms' => $agencies,
            'operator' => 'IN'
        )
    );
}
$keyword = '';
if (isset ($_GET['agent_name'])) {
    $keyword = trim($_GET['agent_name']);
    if (!empty($keyword)) {
        $args['s'] = $keyword;
    }
}
$data = new WP_Query($args);
$total_post = $data->found_posts;
$wrapper_classes = implode(' ', array_filter($wrapper_classes));

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'agent');
wp_print_styles( ERE_PLUGIN_PREFIX . 'archive-agent');

$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'archive-agent', ERE_PLUGIN_URL . 'public/assets/js/agent/ere-archive-agent' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
    <div class="ere-archive-agent-wrap">
        <?php do_action('ere_archive_agent_before_main_content');?>
        <div class="ere-archive-agent">
            <div class="above-archive-agent mg-bottom-60 sm-mg-bottom-40">
                <?php do_action('ere_archive_agent_heading', $total_post); ?>
                <?php do_action('ere_archive_agent_action', $keyword); ?>
            </div>
            <?php if ($data->have_posts()): ?>
                <div class="<?php echo esc_attr($wrapper_classes) ?>">
                    <?php while ($data->have_posts()): $data->the_post(); ?>
                        <?php ere_get_template('content-agent.php', array(
                            'gf_item_wrap' => $gf_item_wrap,
                            'agent_layout_style' => $agent_layout_style
                        )); ?>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
                <?php
            endif; ?>
            <div class="clearfix"></div>
            <?php
            $max_num_pages = $data->max_num_pages;
            ere_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages));
            wp_reset_postdata(); ?>
        </div>
        <?php do_action('ere_archive_agent_after_main_content');?>
    </div>
<?php
/**
 * ere_sidebar_agent hook.
 *
 * @hooked ere_sidebar_agent - 10
 */
do_action('ere_sidebar_agent');
get_footer('ere');
