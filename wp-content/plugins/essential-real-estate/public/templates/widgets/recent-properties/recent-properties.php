<?php
/**
 * Created by G5Theme.
 * User: ThangLK
 * Date: 21/12/2016
 * Time: 11:00 AM
 */

$number = (!empty($instance['number'])) ? absint($instance['number']) : 3;
if (!$number)
    $number = 3;

$args = array(
    'post_type' => 'property',
    'ignore_sticky_posts' => true,
    'posts_per_page' => $number,
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
);

$data = new WP_Query($args);

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'recent-properties');
wp_print_styles( ERE_PLUGIN_PREFIX . 'property');

?>
    <div class="list-recent-properties">
        <?php if ($data->have_posts()):
            while ($data->have_posts()): $data->the_post();
                $attach_id = get_post_thumbnail_id();
                $image_src = ere_image_resize_id($attach_id, 370, 180, true);

                $property_link = get_the_permalink();
                $property_id= get_the_ID();
                $property_label = get_the_terms($property_id, 'property-labels');
                $price = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', true);
                $property_address = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_address', true);

                ?>
                <div class="property-item">
                    <div class="property-inner">
                        <?php if (!empty($image_src)): ?>
                        <div class="property-avatar">
                            <img width="370" height="180"
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
                            <?php if ($property_label): ?>
                                <div class="property-labels">
                                    <?php foreach ($property_label as $label_item): ?>
                                        <p class="label-item"><span><?php echo esc_attr($label_item->name) ?></span></p>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <div class="property-item-content">
                                <h4 class="property-title fs-18"><a href="<?php echo esc_url($property_link); ?>"
                                                                    title="<?php the_title(); ?>"><?php the_title() ?></a>
                                </h4>
                            <div class="property-element-inline">
                                <?php if (!empty($price)): ?>
                                    <div class="property-price">
                                        <span><?php echo ere_get_format_money($price) ?></span>
                                    </div>
                                <?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
                                    <div class="property-price">
                                        <span><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($property_address)): ?>
                                    <div class="property-position">
                                        <p title="<?php echo esc_attr($property_address) ?>">
                                            <i class="fa fa-map-marker accent-color"></i>
                                            <span><?php echo esc_attr($property_address) ?></span>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            endwhile;
        else: ?>
            <div class="item-not-found"><?php esc_html_e('No item found', 'essential-real-estate'); ?></div>
        <?php endif; ?>
    </div>
<?php
wp_reset_postdata();