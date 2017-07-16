<?php
/**
 * @var $favorites
 * @var $max_num_pages
 */
if (!is_user_logged_in()) {
    echo ere_get_template_html('global/dashboard-login.php');
    return;
}
ere_get_template('global/dashboard-menu.php', array('cur_menu' => 'my_favorites'));

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_enqueue_style(ERE_PLUGIN_PREFIX . 'property', array('dashicons'));
wp_enqueue_style(ERE_PLUGIN_PREFIX . 'archive-property');

$wrapper_classes = array(
    'ere-property clearfix',
    'property-grid',
    'col-gap-10',
    'columns-3',
    'columns-md-2',
    'columns-sm-2',
    'columns-xs-1'
);
$property_item_class = array(
    'ere-item-wrap',
    'mg-bottom-10'
);
?>
    <div class="property-favorites">
        <div class="<?php echo join(' ', $wrapper_classes) ?>">
            <?php if ($favorites->have_posts()) :
                while ($favorites->have_posts()): $favorites->the_post();
                    $attach_id = get_post_thumbnail_id();
                    $image_src = ere_image_resize_id($attach_id, 330, 180, true);

                    $property_meta_data = get_post_custom(get_the_ID());
                    $excerpt = get_the_excerpt();
                    $price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
                    $property_address = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_address']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_address'][0] : '';
                    $property_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0] : '';
                    $property_bedrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0] : '0';
                    $property_bathrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0] : '0';
                    $property_garage = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage'][0] : '0';
                    $property_featured       = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_featured' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_featured' ][0] : '0';

                    $property_label = get_the_terms(get_the_ID(), 'property-labels');
                    $property_item_status = get_the_terms( get_the_ID(), 'property-status' );

                    $property_link = get_the_permalink();
                    $measurement_units = ere_get_option('measurement_units', 'SqFt');
                    $property_avatar_class = array();
                    $property_item_content_class = array();
                    ?>
                    <div class="<?php echo join(' ', $property_item_class); ?>">
                        <div class="property-inner">
                            <div class="property-avatar <?php echo join(' ', $property_avatar_class); ?>">
                                <?php if (!empty($image_src)): ?>
                            <img width="330" height="180"
                                 src="<?php echo esc_url($image_src) ?>" alt="<?php the_title(); ?>"
                                 title="<?php the_title(); ?>">
                                <div class="property-action block-center">
                                    <div class="block-center-inner">
                                        <?php
                                        /**
                                         * ere_property_action hook.
                                         *
                                         * @hooked property_social_share - 5
                                         * @hooked property_favorite - 10
                                         * @hooked property_compare - 15
                                         */
                                        do_action( 'ere_property_action' ); ?>
                                    </div>
                                    <a class="property-link" href="<?php echo esc_url( $property_link ); ?>"
                                       title="<?php the_title(); ?>"></a>
                                </div>
                                    <?php if( $property_label || $property_featured): ?>
                                        <div class="property-labels property-featured">
                                            <?php if( $property_featured ): ?>
                                                <p class="label-item">
                                                    <span class="property-label-bg"><?php esc_html_e( 'Featured', 'essential-real-estate' ); ?><span class="property-arrow"></span></span>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ( $property_label ): ?>
                                                <?php foreach ( $property_label as $label_item ): ?>
                                                    <?php $label_color = get_term_meta( $label_item->term_id, 'property_labels_color', true ); ?>
                                                    <p class="label-item">
														<span class="property-label-bg"
                                                              style="background-color: <?php echo esc_attr( $label_color ) ?>"><?php echo esc_attr( $label_item->name ) ?>
                                                            <span class="property-arrow"
                                                                  style="border-left-color: <?php echo esc_attr( $label_color ) ?>; border-right-color: <?php echo esc_attr( $label_color ) ?>"></span>
														</span>
                                                    </p>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif;?>
                                    <?php if( $property_item_status ): ?>
                                        <div class="property-status">
                                            <?php foreach ( $property_item_status as $status ): ?>
                                                <?php $status_color = get_term_meta( $status->term_id, 'property_status_color', true ); ?>
                                                <p class="status-item">
											<span class="property-status-bg"
                                                  style="background-color: <?php echo esc_attr( $status_color ) ?>"><?php echo esc_attr( $status->name ) ?>
                                                <span class="property-arrow"
                                                      style="border-left-color: <?php echo esc_attr( $status_color ) ?>; border-right-color: <?php echo esc_attr( $status_color ) ?>"></span>
											</span>
                                                </p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <div class="property-item-content <?php echo join(' ', $property_item_content_class); ?>">
                                <h4 class="property-title fs-18"><a href="<?php echo esc_url($property_link); ?>"
                                                                    title="<?php the_title(); ?>"><?php the_title() ?></a>
                                </h4>
                                <?php if(!empty( $price )): ?>
                                    <div class="property-price">
                                        <span><?php echo ere_get_format_money($price) ?></span>
                                    </div>
                                <?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
                                    <div class="property-price">
                                        <span><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="property-position">
                                    <i class="fa fa-map-marker accent-color"></i>
                                    <span><?php echo esc_attr($property_address) ?></span>
                                </div>
                                <div class="property-excerpt">
                                    <p><?php echo esc_html($excerpt) ?></p>
                                </div>
                                <div class="property-info">
                                    <div class="property-info-inner">
                                        <div class="property-area">
                                            <span class="fa fa-arrows"></span>
                                            <span
                                                class="property-info-value"><?php echo esc_attr($property_size . ' ' . $measurement_units) ?></span>
                                        </div>
                                        <div class="property-bedrooms">
                                            <span class="fa fa-hotel"></span>
                                            <span
                                                class="property-info-value"><?php echo esc_attr($property_bedrooms) ?></span>
                                        </div>
                                        <div class="property-bathrooms">
                                            <span class="fa fa-bath"></span>
                                            <span
                                                class="property-info-value"><?php echo esc_attr($property_bathrooms) ?></span>
                                        </div>
                                        <div class="property-garage">
                                            <span class="fa fa-car"></span>
                                            <span
                                                class="property-info-value"><?php echo esc_attr($property_garage) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else: ?>
                <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <br>
<?php ere_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages)); ?>