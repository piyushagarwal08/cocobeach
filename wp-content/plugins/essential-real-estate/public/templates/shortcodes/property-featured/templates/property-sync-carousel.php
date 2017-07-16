<?php
/**
 * @var $layout_style
 * @var $data
 * @var $color_scheme
 * @var $item_amount
 * @var $include_heading
 * @var $heading_sub_title
 * @var $heading_title
 * @var $heading_text_align
 * @var $property_cities
 */

$property_content_class = array('property-sync-content-wrap row');
$property_item_class = array('property-item');

?>
<div class="<?php echo join( ' ', $property_content_class ); ?>">
	<?php if ($data->have_posts()) :
		$list_property_img_html = array();?>
		<div class="property-main-content col-lg-6 col-md-12">
			<div class="main-content-inner">
				<div class="">
					<?php if($include_heading && (!empty($heading_sub_title) || !empty($heading_title))) :?>
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
				<div class="property-content-carousel owl-carousel manual ere-carousel-manual">
				<?php while ($data->have_posts()): $data->the_post();
					$attach_id = get_post_thumbnail_id();
					$image_src = ere_image_resize_id( $attach_id, 945, 605, true );
					$list_property_img_html[] = '<img width="945" height="605"
					     src="'.$image_src.'" alt="'.get_the_title().'"
					     title="'.get_the_title().'">';

					$excerpt = get_the_excerpt();

					$property_meta_data = get_post_custom( get_the_ID() );

					$price = isset( $property_meta_data[ ERE_METABOX_PREFIX. 'property_price'] ) ? $property_meta_data[ ERE_METABOX_PREFIX. 'property_price'][0] : '';
					$price_postfix         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ][0] : '';
					$property_address = isset( $property_meta_data[ ERE_METABOX_PREFIX. 'property_address'] ) ? $property_meta_data[ ERE_METABOX_PREFIX. 'property_address'][0] : '';
					$property_size = isset( $property_meta_data[ ERE_METABOX_PREFIX. 'property_size'] ) ? $property_meta_data[ ERE_METABOX_PREFIX. 'property_size'][0] : '';
					$property_bedrooms = isset( $property_meta_data[ ERE_METABOX_PREFIX. 'property_bedrooms'] ) ? $property_meta_data[ ERE_METABOX_PREFIX. 'property_bedrooms'][0] : '0';
					$property_bathrooms = isset( $property_meta_data[ ERE_METABOX_PREFIX. 'property_bathrooms'] ) ? $property_meta_data[ ERE_METABOX_PREFIX. 'property_bathrooms'][0] : '0';
					$property_garage = isset( $property_meta_data[ ERE_METABOX_PREFIX. 'property_garage'] ) ? $property_meta_data[ ERE_METABOX_PREFIX. 'property_garage'][0] : '0';
					
					$property_status    = get_the_terms( get_the_ID(), 'property-status' );

					$property_link = get_the_permalink();
					?>
					<div class="<?php echo join(' ', $property_item_class); ?>">
						<div class="property-item-content">
							<div class="property-heading">
								<h4><a href="<?php echo esc_url( $property_link ); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
								<div class="property-element-inline">
									<div>
										<?php if ( ! empty( $price ) ): ?>
											<span class="property-price"><?php echo ere_get_format_money( $price ) ?><?php if(!empty( $price_postfix )) {echo '<span class="fs-12 accent-color">/'.$price_postfix.'</span>';} ?></span>
										<?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
											<span class="property-price"><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
										<?php endif; ?>
										<?php if ( $property_status ) : ?>
											<div class="property-status">
												<?php foreach ( $property_status as $status ) : ?>
													<span><?php echo esc_attr( $status->name ); ?></span>
												<?php endforeach; ?>
											</div>
										<?php endif; ?>
									</div>
									<?php if ( ! empty( $property_address ) ): ?>
										<div class="property-position" title="<?php echo esc_attr( $property_address ) ?>">
											<div class="property-position-inner">
												<i class="fa fa-map-marker accent-color"></i>
												<span><?php echo esc_attr( $property_address ) ?></span>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
							<?php if ( isset( $excerpt ) && ! empty( $excerpt ) ): ?>
								<div class="property-excerpt">
									<p><?php echo wp_kses_post( $excerpt ); ?></p>
								</div>
							<?php endif; ?>
							<div class="property-info">
								<div class="property-info-inner">
									<?php if ( ! empty( $property_size ) ): ?>
										<div class="property-area">
											<div class="property-info-item-inner">
												<span class="fa fa-arrows accent-color"></span>
												<div class="content-property-info">
													<p class="property-info-value"><?php echo esc_attr( $property_size ) ?>
														<?php if ( isset( $property_size ) && ! empty( $property_size ) ): ?>
															<span><?php
																$measurement_units = ere_get_option('measurement_units','SqFt');
																echo esc_html($measurement_units); ?></span>
														<?php endif; ?>
													</p>
													<p class="property-info-title"><?php esc_html_e( 'Area', 'essential-real-estate' ); ?></p>
												</div>
											</div>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $property_bedrooms ) ): ?>
										<div class="property-bedrooms">
											<div class="property-info-item-inner">
												<span class="fa fa-hotel accent-color"></span>
												<div class="content-property-info">
													<p class="property-info-value"><?php echo esc_attr( $property_bedrooms ) ?></p>
													<p class="property-info-title"><?php echo ere_get_number_text($property_bedrooms, esc_html__( 'Bedrooms', 'essential-real-estate' ), esc_html__( 'Bedroom', 'essential-real-estate' )); ?></p>
												</div>
											</div>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $property_bathrooms ) ): ?>
										<div class="property-bathrooms">
											<div class="property-info-item-inner">
												<span class="fa fa-bath accent-color"></span>
												<div class="content-property-info">
													<p class="property-info-value"><?php echo esc_attr( $property_bathrooms ) ?></p>
													<p class="property-info-title"><?php echo ere_get_number_text($property_bathrooms, esc_html__( 'Bathrooms', 'essential-real-estate' ), esc_html__( 'Bathroom', 'essential-real-estate' )); ?></p>
												</div>
											</div>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $property_garage ) ): ?>
										<div class="property-garage">
											<div class="property-info-item-inner">
												<span class="fa fa-car accent-color"></span>
												<div class="content-property-info">
													<p class="property-info-value"><?php echo esc_attr( $property_garage ) ?></p>
													<p class="property-info-title"><?php echo ere_get_number_text($property_garage, esc_html__( 'Garages', 'essential-real-estate' ), esc_html__( 'Garage', 'essential-real-estate' )); ?></p>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile;?>
			</div>
				</div>
			</div>
		</div>
		<div class="property-image-content col-lg-6 col-md-12">
			<div class="property-image-carousel owl-carousel manual ere-carousel-manual">
				<?php
				foreach ($list_property_img_html as $img_html) {?>
					<div class="property-item">
						<?php echo wp_kses_post( $img_html ); ?>
					</div>
				<?php }?>
			</div>
		</div>
	<?php else: ?>
		<div class="item-not-found"><?php esc_html_e('No item found','essential-real-estate'); ?></div>
	<?php endif; ?>
</div>




