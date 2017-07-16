<?php
$property_types = $property_status = $property_feature = $property_cities = $property_state =
$property_neighborhood = $property_labels = $property_featured = $is_carousel = $color_scheme = $category_filter = $filter_style =
$include_heading = $heading_sub_title = $heading_title = $item_amount = $columns_gap = $columns =
$dots = $nav = $autoplay = $autoplaytimeout = $property_type = $el_class = '';
extract(shortcode_atts(array(
    'property_types' => '',
    'property_status' => '',
    'property_feature' => '',
    'property_cities' => '',
    'property_state' => '',
    'property_neighborhood' => '',
    'property_labels' => '',
    'property_featured' => 'false',
    'is_carousel' => '',
    'color_scheme' => 'color-dark',
    'category_filter' => '',
    'filter_style' => 'filter-isotope',
    'include_heading' => '',
    'heading_sub_title' => '',
    'heading_title' => '',
    'item_amount' => '6',
    'columns_gap' => 'col-gap-0',
    'columns' => '4',
    'dots' => '',
    'nav' => '',
    'autoplay' => '',
    'autoplaytimeout' => '',
    'property_type' => '',
    'el_class' => ''
), $atts));

$property_item_class = array('property-item');
$property_content_class = array('property-content clearfix');
$property_content_attributes = array();
$content_attributes = array();
$filter_class = array('hidden-mb property-filter-content');
$filter_attributes = array();
if(empty( $property_types )) {
    $property_types_all = get_categories( array( 'taxonomy' => 'property-type', 'hide_empty' => 1, 'orderby' => 'ASC' ) );
    $property_types = array();
    if (is_array($property_types_all)) {
        foreach ($property_types_all as $property_typ) {
            $property_types[] = $property_typ->slug;
        }
        $property_types = join( ',', $property_types );
    }
}

if ($category_filter) {
    $filter_attributes[] = 'data-is-carousel="' . $is_carousel . '"';
    $filter_attributes[] = 'data-columns-gap="' . $columns_gap . '"';
    $filter_attributes[] = 'data-columns="' . $columns . '"';
    $filter_attributes[] = "data-item-amount='" . $item_amount . "'";
    $filter_attributes[] = "data-color-scheme='" . $color_scheme . "'";
    $filter_attributes[] = 'data-item=".property-item"';
    $content_attributes[] = 'data-filter-content="filter"';
    if (!empty( $property_types ) && empty($property_type)) {
        $property_type = explode(',', $property_types)[0];
    }
}
$wrapper_classes = array(
    'ere-property-gallery clearfix',
    $color_scheme,
    $el_class,
);

if ($columns_gap == 'col-gap-30') {
    $col_gap = 30;
} elseif ($columns_gap == 'col-gap-20') {
    $col_gap = 20;
} elseif ($columns_gap == 'col-gap-10') {
    $col_gap = 10;
} else {
    $col_gap = 0;
}
if ($is_carousel) {
    $content_attributes[] = 'data-type="carousel"';
    $property_content_class[] = 'owl-carousel manual';
    $owl_attributes = array(
        '"dots": ' . ($dots ? 'true' : 'false'),
        '"nav": ' . ($nav ? 'true' : 'false'),
        '"items": 1',
        '"autoplay": ' . ($autoplay ? 'true' : 'false'),
        '"autoplaySpeed": ' . $autoplaytimeout,
        '"responsive": {"0" : {"items" : 1, "margin": 0}, "480" : {"items" : 2, "margin": ' . $col_gap . '},
		"992" : {"items" : ' . (($columns >= 3) ? 3 : $columns) . ', "margin": ' . $col_gap . '},
		"1200" : {"items" : ' . $columns . ', "margin": ' . $col_gap . '}}'
    );
    $property_content_attributes[] = "data-plugin-options='{" . implode(', ', $owl_attributes) . "}'";
    if ($category_filter) {
        $filter_class[] = 'property-filter-carousel';
        $filter_attributes[] = 'data-filter-type="carousel"';
        $content_attributes[] = 'data-layout="filter"';
    }
} else {
    $content_attributes[] = 'data-type="grid"';
    $content_attributes[] = 'data-layout="fitRows"';
    $wrapper_classes[] = $columns_gap;
    if ($columns_gap == 'col-gap-30') {
        $property_item_class[] = 'mg-bottom-30';
    } elseif ($columns_gap == 'col-gap-20') {
        $property_item_class[] = 'mg-bottom-20';
    } elseif ($columns_gap == 'col-gap-10') {
        $property_item_class[] = 'mg-bottom-10';
    }
    $property_content_class[] = 'row';
    $property_content_class[] = 'columns-' . $columns;
    $property_content_class[] = 'columns-md-' . ($columns >= 3 ? 3 : $columns);
    $property_content_class[] = 'columns-sm-2';
    $property_content_class[] = 'columns-xs-2';
    $property_content_class[] = 'columns-mb-1';
    $property_item_class[] = 'ere-item-wrap';
    if ($category_filter) {
        $filter_attributes[] = 'data-filter-type="filter"';
        $filter_attributes[] = 'data-filter-style="' . $filter_style . '"';
    }
}

$args = array(
    'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
    'post_type' => 'property',
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
);
if (!empty($author)) {
    $args['author'] = $author;
}
$args['tax_query'] = array();
if ($property_type != '') {
    $args['tax_query'][] =  array(
        'taxonomy' => 'property-type',
        'field' => 'slug',
        'terms' => explode(',', $property_type),
        'operator' => 'IN'
    );
}
if (!empty( $property_types ) || !empty( $property_status ) || !empty( $property_feature ) || !empty( $property_city )
    || !empty( $property_state ) || !empty( $property_neighborhood ) || !empty( $property_labels )) {
    if(!empty( $property_types ) && empty( $property_type )) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-type',
            'field' => 'slug',
            'terms' => explode(',', $property_types),
            'operator' => 'IN'
        );
    }
    if(!empty( $property_status )) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-status',
            'field' => 'slug',
            'terms' => explode(',', $property_status),
            'operator' => 'IN'
        );
    }
    if(!empty( $property_feature )) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-feature',
            'field' => 'slug',
            'terms' => explode(',', $property_feature),
            'operator' => 'IN'
        );
    }
    if(!empty( $property_city )) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-city',
            'field' => 'slug',
            'terms' => explode(',', $property_city),
            'operator' => 'IN'
        );
    }
    if(!empty( $property_state )) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-state',
            'field' => 'slug',
            'terms' => explode(',', $property_state),
            'operator' => 'IN'
        );
    }
    if(!empty( $property_neighborhood )) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-neighborhood',
            'field' => 'slug',
            'terms' => explode(',', $property_neighborhood),
            'operator' => 'IN'
        );
    }
    if(!empty( $property_labels )) {
        $args['tax_query'][] = array(
            'taxonomy' => 'property-labels',
            'field' => 'slug',
            'terms' => explode(',', $property_labels),
            'operator' => 'IN'
        );
    }
}

if('true' == $property_featured) {
    $args['meta_query'] = array(
        array(
            'key'       => ERE_METABOX_PREFIX.'property_featured',
            'value'     => true,
            'compare'   => '=',
        )
    );
}

$data = new WP_Query($args);
$total_post = $data->found_posts;

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'property-gallery');

$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'property_gallery', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-gallery/assets/js/property-gallery' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
wp_enqueue_script('isotope', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-gallery/assets/js/isotope.min.js', array('jquery'), 'v2.2.0', true);
wp_enqueue_script('imageLoaded', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-gallery/assets/js/imageLoaded.min.js', array('jquery'), 'v3.1.8', true);
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'owl_carousel', ERE_PLUGIN_URL . 'public/assets/js/ere-carousel' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
<div class="ere-property-wrap">
    <div class="<?php echo join(' ', $wrapper_classes) ?>">
        <?php $filter_id = rand(); ?>
        <?php if ($category_filter):
            $filter_item_class = 'portfolio-filter-category';
            ?>
            <div class="filter-wrap">
                <div class="container">
                    <div class="filter-inner" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
                        <?php if ($include_heading && (!empty($heading_sub_title) || !empty($heading_title))) : ?>
                            <div class="ere-heading <?php echo esc_attr($color_scheme) ?>">
                                <span></span>
                                <?php if (!empty($heading_sub_title)): ?>
                                    <p><?php echo esc_html($heading_sub_title); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($heading_title)): ?>
                                    <h2><?php echo esc_html($heading_title); ?></h2>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div data-filter_id="<?php echo esc_attr($filter_id); ?>" <?php echo implode(' ', $filter_attributes); ?>
                            class="<?php echo implode(' ', $filter_class); ?>">
                            <?php
                            if (!empty($property_types)) {
                                $index = 1;
                                $property_type_arr = explode( ',',$property_types );
                                foreach ($property_type_arr as $property_type) {
                                    $type = get_term_by( 'slug', $property_type, 'property-type', 'OBJECT' ); ?>
                                    <a class="<?php echo esc_attr($filter_item_class); ?><?php echo ($index == 1)? ' active-filter': '' ?>"
                                       data-filter=".<?php echo esc_attr($property_type); ?>"><?php echo esc_attr( $type->name ) ?></a>
                                    <?php
                                    $index++;
                                }
                            } ?>
                        </div>
                        <div class="visible-mb">
                            <select class="property-filter-mb" title="">
                                <?php
                                if (!empty($property_types)) {
                                    $index = 1;
                                    $property_type_arr = explode( ',',$property_types );
                                    foreach ($property_type_arr as $property_type) {
                                        $type = get_term_by( 'slug', $property_type, 'property-type', 'OBJECT' ); ?>
                                        <option value=".<?php echo esc_attr($property_type); ?>"<?php echo ($index == 1)? ' selected': '' ?>><?php echo esc_attr( $type->name ) ?></option>
                                        <?php
                                        $index++;
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if($is_carousel): ?>
            <div class="<?php echo join(' ', $property_content_class) ?>" <?php if ($category_filter): ?> data-filter_id="<?php echo esc_attr($filter_id); ?>"<?php endif; ?>
            data-callback="owl_callback" <?php echo implode(' ', $property_content_attributes); ?>
            <?php echo implode(' ', $content_attributes); ?>>
        <?php else: ?>
            <div class="<?php echo join(' ', $property_content_class) ?>" <?php if ($category_filter): ?> data-filter_id="<?php echo esc_attr($filter_id); ?>"<?php endif; ?>
                <?php echo implode(' ', $content_attributes); ?>>
        <?php endif; ?>
            <?php if ($data->have_posts()) :
                while ($data->have_posts()): $data->the_post();
                    $attach_id = get_post_thumbnail_id();
                    $image_src = ere_image_resize_id($attach_id, 540, 320, true);
                    $price = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_price', true);
                    $price_postfix = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_price_postfix', true);
                    $property_address = get_post_meta(get_the_ID(), ERE_METABOX_PREFIX . 'property_address', true);
                    $property_link = get_the_permalink();

                    $property_type_list = get_the_terms(get_the_ID(), 'property-type');
                    $property_type_class = array();
                    if ($property_type_list) {
                        foreach ($property_type_list as $type) {
                            $property_type_class[] = $type->slug;
                        }
                    }
                    ?>
                    <div class="<?php echo join(' ', array_merge($property_item_class, $property_type_class)); ?>">
                        <div class="property-inner">
                            <div class="property-avatar">
                                <?php if (!empty($image_src)): ?>
                                    <img width="540" height="320"
                                         src="<?php echo esc_url($image_src) ?>" alt="<?php the_title(); ?>"
                                         title="<?php the_title(); ?>">
                                <?php endif; ?>
                                <div class="property-item-content">
                                    <h4 class="property-title fs-18"><a href="<?php echo esc_url($property_link); ?>"
                                                                        title="<?php the_title(); ?>"><?php the_title() ?></a>
                                    </h4>

                                    <div class="property-info">
                                        <?php if (!empty($price)): ?>
                                            <span class="property-price"><?php echo ere_get_format_money( $price ) ?><?php if(!empty( $price_postfix )) {echo '<span class="fs-12 accent-color">/'.$price_postfix.'</span>';} ?></span>
                                            <?php elseif (ere_get_option('empty_price_text', '') !=''): ?>
                                            <span class="property-price"><?php echo ere_get_option('empty_price_text', '') ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($property_address)): ?>
                                            <div class="property-position">
                                                <div class="property-position-inner" title="<?php echo esc_attr( $property_address ) ?>">
                                                    <i class="fa fa-map-marker accent-color"></i>
                                                    <span><?php echo esc_attr($property_address) ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <a class="property-link" href="<?php echo esc_url($property_link); ?>"
                                   title="<?php the_title(); ?>"></a>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
            else: ?>
                <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
            <?php endif; ?>
        </div>
        <?php wp_reset_postdata(); ?>
    </div>
</div>


