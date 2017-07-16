<?php
global $post;
$property_meta_data = get_post_custom( get_the_ID() );
$property_identity         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_identity' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_identity' ][0] : '';
$property_size         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ][0] : '';
$property_bedrooms    = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ][0] : '0';
$property_bathrooms   = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ][0] : '0';
$property_garage      = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage' ][0] : '0';

$property_title = get_the_title();
$price = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ][0] : '';
$property_address     = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ][0] : '';
$property_status = get_the_terms( get_the_ID(), 'property-status' );
?>
<div class="property-info-header property-info-action mg-bottom-60 sm-mg-bottom-40">
	<?php
	/**
	 * ere_single_property_info_header_title hook.
	 *
	 * @hooked ere_single_property_info_header_title - 5
	 */
	do_action( 'ere_single_property_info_header_title' ); ?>
	<div class="property-info">
		<div class="property-id">
			<span class="fa fa-barcode accent-color"></span>
			<div class="content-property-info">
				<p class="property-info-value"><?php
					if(!empty($property_identity))
					{
						echo esc_html($property_identity);
					}
					else
					{
						echo get_the_ID();
					}
					?></p>
				<p class="property-info-title"><?php esc_html_e( 'Property ID', 'essential-real-estate' ); ?></p>
			</div>
		</div>
		<?php if ( ! empty( $property_size ) ): ?>
			<div class="property-area">
				<span class="fa fa-arrows accent-color"></span>
				<div class="content-property-info">
					<p class="property-info-value"><?php
						echo esc_attr( $property_size ) ?>
							<span><?php
								$measurement_units = ere_get_option('measurement_units','SqFt');
								echo esc_html($measurement_units); ?></span>
					</p>
					<p class="property-info-title"><?php esc_html_e( 'Area', 'essential-real-estate' ); ?></p>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $property_bedrooms ) ): ?>
			<div class="property-bedrooms">
				<span class="fa fa-hotel accent-color"></span>
				<div class="content-property-info">
					<p class="property-info-value"><?php echo esc_attr( $property_bedrooms ) ?></p>
					<p class="property-info-title"><?php echo ere_get_number_text($property_bedrooms, esc_html__( 'Bedrooms', 'essential-real-estate' ), esc_html__( 'Bedroom', 'essential-real-estate' )); ?></p>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $property_bathrooms ) ): ?>
			<div class="property-bathrooms">
				<span class="fa fa-bath accent-color"></span>
				<div class="content-property-info">
					<p class="property-info-value"><?php echo esc_attr( $property_bathrooms ) ?></p>
					<p class="property-info-title"><?php echo ere_get_number_text($property_bathrooms, esc_html__( 'Bathrooms', 'essential-real-estate' ), esc_html__( 'Bathroom', 'essential-real-estate' )); ?></p>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $property_garage ) ): ?>
			<div class="property-garage">
				<span class="fa fa-car accent-color"></span>
				<div class="content-property-info">
					<p class="property-info-value"><?php echo esc_attr( $property_garage ) ?></p>
					<p class="property-info-title"><?php echo ere_get_number_text($property_garage, esc_html__( 'Garages', 'essential-real-estate' ), esc_html__( 'Garage', 'essential-real-estate' )); ?></p>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="property-action">
		<div class="property-action-inner clearfix">
			<?php
			/**
			 * ere_property_action hook.
			 *
			 * @hooked property_social_share - 5
			 * @hooked property_favorite - 10
			 * @hooked property_compare - 15
			 */
			do_action( 'ere_property_action' ); ?>
			<?php if(ere_get_option('enable_print_property','1')=='1'):?>
			<a href="javascript:;" id="property-print"
			   data-ajax-url="<?php echo ERE_AJAX_URL; ?>" data-toggle="tooltip"
			   data-original-title="<?php esc_html_e( 'Print', 'essential-real-estate' ); ?>"
			   data-property-id="<?php echo esc_attr( get_the_ID() ); ?>"><i class="fa fa-print"></i></a>
			<?php endif;?>
		</div>
	</div>
</div>