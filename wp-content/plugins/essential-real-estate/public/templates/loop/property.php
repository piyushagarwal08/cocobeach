<?php
/**
 * @var $custom_property_image_size
 * @var $property_item_class
 */
$attach_id  = get_post_thumbnail_id();
$width = $height = '';
if(empty( $custom_property_image_size )){
	$image_src  = ere_image_resize_id( $attach_id, 330, 180, true );
} else {
	$image_sizes = explode( 'x', $custom_property_image_size );
	$image_src  = ere_image_resize_id( $attach_id, $image_sizes[0], $image_sizes[1], true );
}
if ( ! empty( $image_src ) ) {
	list( $width, $height ) = getimagesize( $image_src );
}

$property_meta_data    = get_post_custom( get_the_ID() );
$excerpt               = get_the_excerpt();
$price                 = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ][0] : '';
$price_postfix         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ][0] : '';
$property_address      = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ][0] : '';
$property_size         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ][0] : '';
$property_bedrooms     = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ][0] : '0';
$property_bathrooms    = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ][0] : '0';
$property_garage       = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage' ][0] : '0';
$property_featured     = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_featured' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_featured' ][0] : '0';

// Get Agent name
$agent_display_option = isset($property_meta_data[ ERE_METABOX_PREFIX . 'agent_display_option' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'agent_display_option' ][0] : '';
$property_agent       = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_agent' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_agent' ][0] : '';
$agent_name = $agent_link = '';
if ( $agent_display_option == 'author_info' ){
	global $post;
	$user_id = $post->post_author;
	$user_info = get_userdata( $user_id );
	$agent_name = $user_info->first_name . ' ' . $user_info->last_name;
	if(empty($agent_name)) {
		$agent_name = $user_info->display_name;
	}
	$author_agent_id = get_the_author_meta( ERE_METABOX_PREFIX . 'author_agent_id', $user_id );
	$agent_link = get_the_permalink($author_agent_id);
} elseif ( $agent_display_option == 'other_info'){
	$agent_name = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_name' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_name' ][0] : '';
} elseif ($agent_display_option == 'agent_info' && ! empty( $property_agent )){
	$agent_name= get_the_title($property_agent);
	$agent_link = get_the_permalink($property_agent);
}

$property_types = get_the_terms(get_the_ID(), 'property-type');
$property_label = get_the_terms( get_the_ID(), 'property-labels' );
$property_item_status = get_the_terms( get_the_ID(), 'property-status' );
$property_link = get_the_permalink();
?>
<div class="<?php echo join( ' ', $property_item_class ); ?>">
	<div class="property-inner">
		<div class="property-avatar">
			<?php if ( ! empty( $image_src ) ): ?>
				<img width="<?php echo esc_attr( $width ) ?>"
				     height="<?php echo esc_attr( $height ) ?>"
				     src="<?php echo esc_url( $image_src ) ?>" alt="<?php the_title(); ?>"
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
		<div class="property-item-content">
			<div class="property-heading">
				<h4 class="property-title fs-18"><a href="<?php echo esc_url( $property_link ); ?>"
				                                    title="<?php the_title(); ?>"><?php the_title() ?></a>
				</h4>
				<?php if ( ! empty( $price ) ): ?>
					<div class="property-price">
						<span><?php echo ere_get_format_money( $price ) ?><?php if(!empty( $price_postfix )) {echo '<span class="fs-12 accent-color">/'.$price_postfix.'</span>';} ?></span>
						<?php if(empty( $price_postfix ) && !empty( $property_size )):?>
							<span class="price-per-unit">
								<?php
								$measurement_units = ere_get_option('measurement_units','SqFt');
								echo ere_get_format_money(round( $price / $property_size, 2 )).'/'.$measurement_units;
								?>
							</span>
						<?php endif; ?>
					</div>
				<?php elseif (ere_get_option( 'empty_price_text', '' ) != '' ): ?>
					<div class="property-price">
						<span><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
					</div>
				<?php endif; ?>
			</div>
			<?php if ($property_types): ?>
				<div class="property-type">
					<i class="fa fa-tag accent-color"></i>
					<?php foreach ($property_types as $type):?>
						<a href="<?php echo esc_url( get_term_link( $type->slug, 'property-type' ) ); ?>" title="<?php echo esc_attr( $type->name ); ?>"><span><?php echo esc_attr( $type->name ); ?> </span></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $property_address ) ): ?>
				<div class="property-position">
					<p class="position-inline" title="<?php echo esc_attr( $property_address ) ?>">
						<i class="fa fa-map-marker accent-color"></i>
						<span><?php echo esc_attr( $property_address ) ?></span>
					</p>
				</div>
			<?php endif; ?>
			<div class="property-element-inline">
				<?php if ($property_types): ?>
					<div class="property-type-list">
						<i class="fa fa-tag accent-color"></i>
						<?php foreach ($property_types as $type):?>
							<a href="<?php echo esc_url( get_term_link( $type->slug, 'property-type' ) ); ?>" title="<?php echo esc_attr( $type->name ); ?>"><span><?php echo esc_attr( $type->name ); ?> </span></a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if(!empty( $agent_name )): ?>
					<div class="property-agent">
						<?php echo !empty( $agent_link ) ? ('<a href="'.$agent_link.'" title="'.$agent_name.'">') : ''; ?>
							<i class="fa fa-user accent-color"></i>
							<span><?php echo esc_attr( $agent_name ) ?></span>
						<?php echo !empty( $agent_link ) ? ('</a>') : ''; ?>
					</div>
				<?php endif; ?>
				<div class="property-date"><?php printf( _x( '<i class="fa fa-calendar accent-color"></i> %s ago', '%s = human-readable time difference', 'g5plus-real-estate' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); ?></div>
			</div>
			<?php if ( isset( $excerpt ) && ! empty( $excerpt ) ): ?>
				<div class="property-excerpt">
					<p><?php echo esc_html( $excerpt ) ?></p>
				</div>
			<?php endif; ?>
			<div class="property-info">
				<div class="property-info-inner">
					<?php if ( ! empty( $property_size ) ): ?>
						<div class="property-area">
							<div class="property-area-inner property-info-item-tooltip" data-toggle="tooltip" title="<?php esc_html_e( 'Area', 'essential-real-estate' ); ?>">
								<span class="fa fa-arrows"></span>
								<span class="hidden-md fs-12"><?php esc_html_e( 'Area', 'essential-real-estate' ); ?>: </span>
	                            <span class="property-info-value"><?php
									$measurement_units = ere_get_option('measurement_units','SqFt');
									echo esc_attr( $property_size.' '.$measurement_units ) ?>
		                                            </span>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $property_bedrooms ) ): ?>
						<div class="property-bedrooms">
							<div class="property-bedrooms-inner property-info-item-tooltip" data-toggle="tooltip"
							     title="<?php echo ere_get_number_text($property_bedrooms, esc_html__( 'Bedrooms', 'essential-real-estate' ), esc_html__( 'Bedroom', 'essential-real-estate' )); ?>">
								<span class="fa fa-hotel"></span>
								<span class="hidden-md fs-12"><?php esc_html_e( 'Bedroom', 'essential-real-estate' ); ?>: </span>
								<span class="property-info-value"><?php echo esc_attr( $property_bedrooms ) ?></span>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $property_bathrooms ) ): ?>
						<div class="property-bathrooms">
							<div class="property-bathrooms-inner property-info-item-tooltip" data-toggle="tooltip"
						     title="<?php echo ere_get_number_text($property_bathrooms, esc_html__( 'Bathrooms', 'essential-real-estate' ), esc_html__( 'Bathroom', 'essential-real-estate' )); ?>">
								<span class="fa fa-bath"></span>
								<span class="hidden-md fs-12"><?php esc_html_e( 'Bathroom', 'essential-real-estate' ); ?>: </span>
	                            <span class="property-info-value"><?php echo esc_attr( $property_bathrooms ) ?></span>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $property_garage ) ): ?>
						<div class="property-garage">
							<div class="property-garage-inner property-info-item-tooltip" data-toggle="tooltip"
						     title="<?php echo ere_get_number_text($property_garage, esc_html__( 'Garages', 'essential-real-estate' ), esc_html__( 'Garage', 'essential-real-estate' )); ?>">
								<span class="fa fa-car"></span>
								<span class="hidden-md fs-12"><?php esc_html_e( 'Garage', 'essential-real-estate' ); ?>: </span>
	                            <span class="property-info-value"><?php echo esc_attr( $property_garage ) ?></span>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>