<?php
$layout_style = $property_type = $property_status = $property_feature = $property_cities = $property_state =
$property_neighborhood = $property_labels = $color_scheme = $item_amount = $include_heading =
$heading_sub_title = $heading_title = $heading_text_align = $property_city = $el_class = '';
extract( shortcode_atts( array(
	'layout_style' => 'property-list-two-columns',
	'property_type' => '',
	'property_status' => '',
	'property_feature' => '',
	'property_cities' => '',
	'property_state' => '',
	'property_neighborhood' => '',
	'property_labels' => '',
	'color_scheme'=>'color-dark',
	'item_amount' => '6',
	'include_heading' => '',
	'heading_sub_title' => '',
	'heading_title' => '',
	'heading_text_align' => '',
	'property_city' => '',
	'el_class' => ''
), $atts ) );

$wrapper_attributes = array();
$wrapper_styles = array();
$property_item_class = array();
$property_content_class = array('property-content-wrap');

if(empty( $property_cities )) {
	$property_ids = array();
	$args1        = array(
		'posts_per_page' => - 1,
		'post_type'      => 'property',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key'     => ERE_METABOX_PREFIX . 'property_featured',
				'value'   => true,
				'compare' => '=',
			)
		)
	);
	$data         = new WP_Query( $args1 );
	if ( $data->have_posts() ) :
		while ( $data->have_posts() ): $data->the_post();
			$property_ids[] = get_the_ID();
		endwhile;
	endif;
	wp_reset_postdata();

	$property_city_all = wp_get_object_terms( $property_ids, 'property-city' );
	$property_cities = array();
	if (is_array($property_city_all)) {
		foreach ($property_city_all as $property_ct) {
			$property_cities[] = $property_ct->slug;
		}
		$property_cities = join( ',', $property_cities );
	}
}
if ( $layout_style == 'property-cities-filter' ) {
	if ( !empty( $property_cities ) && empty( $property_city ) ) {
		$property_city = explode(',', $property_cities)[0];
	}
}
$wrapper_classes = array(
	'ere-property-featured clearfix',
	$layout_style,
	$color_scheme,
	$el_class
);

if($layout_style == 'property-list-two-columns') {
	$property_content_class[] = 'row';
	$property_item_class[] = 'mg-bottom-30';
	$property_content_class[] = 'columns-2';
	$property_item_class[] = 'ere-item-wrap';
	$property_item_class[] = 'mg-bottom-30';
	$wrapper_classes[] = 'ere-property property-list';
}

$args = array(
	'posts_per_page' => ($item_amount > 0) ? $item_amount : -1,
	'post_type' => 'property',
	'orderby' => 'date',
	'order' => 'DESC',
	'post_status' => 'publish',
	'meta_query'    => array(
		array(
			'key'       => ERE_METABOX_PREFIX.'property_featured',
			'value'     => true,
			'compare'   => '=',
		)
	)
);
$args['tax_query'] = array();
if (!empty( $property_city )) {
	$args['tax_query'][] = array(
		'taxonomy' => 'property-city',
		'field' => 'slug',
		'terms' => array($property_city),
		'operator' => 'IN'
	);
}
if (!empty( $property_type ) || !empty( $property_status ) || !empty( $property_feature ) || !empty( $property_cities )
    || !empty( $property_state ) || !empty( $property_neighborhood ) || !empty( $property_labels )) {
	if(!empty( $property_type )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-type',
			'field' => 'slug',
			'terms' => explode(',', $property_type),
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
	if(!empty( $property_cities ) && empty( $property_city )) {
		$args['tax_query'][] = array(
			'taxonomy' => 'property-city',
			'field' => 'slug',
			'terms' => explode(',', $property_cities),
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

$data = new WP_Query($args);
$total_post = $data->found_posts;


$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'property-featured');
wp_print_styles( ERE_PLUGIN_PREFIX . 'property');

$min_suffix_js = ere_get_option('enable_min_js',0) == 1 ? '.min' : '';

wp_enqueue_script(ERE_PLUGIN_PREFIX . 'owl_carousel', ERE_PLUGIN_URL . 'public/assets/js/ere-carousel' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'property_featured', ERE_PLUGIN_URL . 'public/templates/shortcodes/property-featured/assets/js/property-featured' . $min_suffix_js . '.js', array('jquery',ERE_PLUGIN_PREFIX . 'owl_carousel'), ERE_PLUGIN_VER, true);
?>
<div class="<?php echo join(' ', $wrapper_classes) ?>" <?php echo implode(' ', $wrapper_attributes); ?>>
	<?php if ( $layout_style == 'property-list-two-columns' ): ?>
		<?php if($include_heading) :?>
			<div class="ere-heading <?php echo sprintf("%s %s", $color_scheme, $heading_text_align ); ?>">
				<span></span>
				<?php if (!empty($heading_sub_title)): ?>
					<p><?php echo esc_html($heading_sub_title); ?></p>
				<?php endif; ?>
				<?php if (!empty($heading_title)): ?>
					<h2><?php echo esc_html($heading_title); ?></h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="<?php echo join( ' ', $property_content_class ); ?>">
			<?php if ($data->have_posts()) :
				$index = 0;
				while ($data->have_posts()): $data->the_post();
					$attach_id = get_post_thumbnail_id();
					$image_src = '';

					$image_src = ere_image_resize_id( $attach_id, 240, 180, true );
					$excerpt = get_the_excerpt();

					$property_meta_data = get_post_custom( get_the_ID() );

					$price = isset( $property_meta_data[ ERE_METABOX_PREFIX. 'property_price'] ) ? $property_meta_data[ ERE_METABOX_PREFIX. 'property_price'][0] : '';
					$price_postfix         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ][0] : '';
					$property_address = isset( $property_meta_data[ ERE_METABOX_PREFIX. 'property_address'] ) ? $property_meta_data[ ERE_METABOX_PREFIX. 'property_address'][0] : '';
					$property_featured       = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_featured' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_featured' ][0] : '0';

					$property_label = get_the_terms(get_the_ID(), 'property-labels');
					$property_item_status = get_the_terms( get_the_ID(), 'property-status' );

					$property_link = get_the_permalink();
					?>
					<div class="<?php echo join(' ', $property_item_class); ?>">
						<div class="property-inner">
							<div class="property-avatar">
								<?php if (!empty($image_src)):?>
									<img width="240" height="180"
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
								<?php endif;?>
							</div>
							<div class="property-item-content">
								<h4 class="property-title fs-18"><a href="<?php echo esc_url( $property_link ); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a></h4>
								<div class="property-element-inline">
									<?php if ( ! empty( $price ) ): ?>
										<div class="property-price">
											<span><?php echo ere_get_format_money( $price ) ?><?php if(!empty( $price_postfix )) {echo '<span class="fs-12 accent-color">/'.$price_postfix.'</span>';} ?></span>
										</div>
									<?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
										<div class="property-price">
											<span><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $property_address ) ): ?>
										<div class="property-position">
											<div class="property-position-inner" title="<?php echo esc_attr( $property_address ) ?>">
												<i class="fa fa-map-marker accent-color"></i>
												<span><?php echo esc_attr( $property_address ) ?></span>
											</div>
										</div>
									<?php endif; ?>
								</div>
								<?php if ( isset( $excerpt ) && ! empty( $excerpt ) ): ?>
									<div class="property-excerpt">
										<p><?php echo esc_html( $excerpt ) ?></p>
									</div>
								<?php endif; ?>
								<div class="property-link-detail">
									<a href="<?php echo esc_url( $property_link ); ?>" title="<?php the_title(); ?>">
										<span><?php esc_html_e( 'Details', 'essential-real-estate' ); ?></span>
										<i class="fa fa-long-arrow-right"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
					<?php $index++;?>
				<?php endwhile;
			else: ?>
				<div class="item-not-found"><?php esc_html_e('No item found','essential-real-estate'); ?></div>
			<?php endif; ?>
		</div>
	<?php else: ?>
		<?php ere_get_template( 'shortcodes/property-featured/templates/'.$layout_style.'.php',
			array('layout_style'=>$layout_style, 'data'=>$data, 'color_scheme' => $color_scheme,
			      'item_amount' => $item_amount, 'include_heading' => $include_heading,
			      'heading_sub_title' => $heading_sub_title,'heading_title' => $heading_title,'heading_text_align' => $heading_text_align,
			      'property_cities' => $property_cities)); ?>
	<?php endif; ?>
</div>
<?php wp_reset_postdata(); ?>

