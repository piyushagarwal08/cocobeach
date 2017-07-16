<?php
/**
 * @var $property_floors
 */
?>

<div class="ere-heading-style2 mg-bottom-35 text-left">
	<h2><?php esc_html_e( 'Floor Plans', 'essential-real-estate' ); ?></h2>
</div>
<div class="property-floors-tab property-tab mg-bottom-60 sm-mg-bottom-40">
	<?php $index = 0; ?>
	<ul class="nav nav-tabs">
		<?php foreach ( $property_floors as $floor ): ?>
			<li <?php if ( $index === 0 ): ?>class="active"<?php endif; ?>><a data-toggle="tab"
			                                                                  href="#floor-<?php echo esc_attr( $index ); ?>">
					<?php echo !empty( $floor[ ERE_METABOX_PREFIX . 'floor_name' ] ) ? sanitize_text_field( $floor[ ERE_METABOX_PREFIX . 'floor_name' ] ) : (esc_html__( 'Floor', 'essential-real-estate' ) . ' ' . ($index + 1)) ?></a></li>
			<?php $index ++; ?>
		<?php endforeach; ?>
	</ul>
	<div class="tab-content">
		<?php $index = 0; ?>
		<?php foreach ( $property_floors as $floor ):
			$image_id = $floor[ ERE_METABOX_PREFIX . 'floor_image' ]['id'];
			$image_src = ere_image_resize_id( $image_id, 870, 420, true );
			$width = '870';
			$height = '420';
			$floor_name          = $floor[ ERE_METABOX_PREFIX . 'floor_name' ];
			$floor_size          = $floor[ ERE_METABOX_PREFIX . 'floor_size' ];
			$floor_size_postfix  = $floor[ ERE_METABOX_PREFIX . 'floor_size_postfix' ];
			$floor_bathrooms     = $floor[ ERE_METABOX_PREFIX . 'floor_bathrooms' ];
			$floor_price         = $floor[ ERE_METABOX_PREFIX . 'floor_price' ];
			$floor_price_postfix = $floor[ ERE_METABOX_PREFIX . 'floor_price_postfix' ];
			$floor_bedrooms      = $floor[ ERE_METABOX_PREFIX . 'floor_bedrooms' ];
			$floor_description   = $floor[ ERE_METABOX_PREFIX . 'floor_description' ];
			$gallery_id='ere_floor-'.rand();
			?>
			<div id="floor-<?php echo esc_attr( $index ) ?>"
			     class="tab-pane fade<?php if ( $index === 0 ): ?> in active<?php endif; ?>">
				<?php if(!empty( $image_src )): ?>
					<div class="floor-image ere-light-gallery">
						<img width="<?php echo esc_attr( $width ) ?>" height="<?php echo esc_attr( $height ) ?>"
						     src="<?php echo esc_url( $image_src ); ?>" alt="<?php the_title_attribute(); ?>">
						<a data-thumb-src="<?php echo esc_url($image_src); ?>" data-gallery-id="<?php echo esc_attr($gallery_id); ?>"
						   data-rel="ere_light_gallery" href="<?php echo esc_url($image_src); ?>" class="zoomGallery"><i
								class="fa fa-expand"></i></a>
					</div>
				<?php endif; ?>
				<div class="floor-info row">
					<div class="col-md-6">
						<table class="floor-tab-table">
							<tbody>
							<?php if ( isset( $floor_name ) && ! empty( $floor_name ) ): ?>
								<tr>
									<th><?php esc_html_e( 'Floor Name:', 'essential-real-estate' ); ?></th>
									<td><?php echo sanitize_text_field( $floor_name ); ?></td>
								</tr>
							<?php endif; ?>
							<?php if ( isset( $floor_size ) && ! empty( $floor_size ) ): ?>
								<tr>
									<th><?php esc_html_e( 'Floor Size:', 'essential-real-estate' ); ?></th>
									<td><?php echo sanitize_text_field( $floor_size ); ?>
										<?php echo ( isset( $floor_size_postfix ) && ! empty( $floor_size_postfix ) ) ? sanitize_text_field( $floor_size_postfix ) : '' ?>
									</td>
								</tr>
							<?php endif; ?>
							<?php if ( isset( $floor_bathrooms ) && ! empty( $floor_bathrooms ) ): ?>
								<tr>
									<th><?php esc_html_e( 'Floor Bathrooms:', 'essential-real-estate' ); ?></th>
									<td><?php echo sanitize_text_field( $floor_bathrooms ); ?></td>
								</tr>
							<?php endif; ?>
							</tbody>
						</table>
					</div>
					<div class="col-md-6 sm-mg-top-30">
						<table class="floor-tab-table">
							<tbody>
							<?php if ( isset( $floor_price ) && ! empty( $floor_price ) ): ?>
								<tr>
									<th><?php esc_html_e( 'Floor Price:', 'essential-real-estate' ); ?></th>
									<td><?php echo ere_get_format_money( $floor_price ); ?><?php echo ( isset( $floor_price_postfix ) && ! empty( $floor_price_postfix ) ) ? '<span class="accent-color">/' . sanitize_text_field( $floor_price_postfix ).'</span>' : '' ?></td>
								</tr>
							<?php endif; ?>
							<?php if ( isset( $floor_bedrooms ) && ! empty( $floor_bedrooms ) ): ?>
								<tr>
									<th><?php esc_html_e( 'Floor Bedrooms:', 'essential-real-estate' ); ?></th>
									<td><?php echo sanitize_text_field( $floor_bedrooms ); ?></td>
								</tr>
							<?php endif; ?>
							<?php if ( isset( $floor_description ) && ! empty( $floor_description ) ): ?>
								<tr>
									<th><?php esc_html_e( 'Description:', 'essential-real-estate' ); ?></th>
									<td><?php echo sanitize_text_field( $floor_description ); ?></td>
								</tr>
							<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<?php $index ++; ?>
		<?php endforeach; ?>
	</div>
</div>