<?php
global $post;
$property_meta_data = get_post_custom( get_the_ID() );
$property_title = get_the_title();
$price              = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ][0] : '';
$price_postfix      = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ][0] : '';
$property_address   = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ][0] : '';
$property_status    = get_the_terms( get_the_ID(), 'property-status' );
?>

<div class="property-main-info">
	<div class="property-heading">
		<?php if ( ! empty( $property_title ) ): ?>
			<h4><?php the_title(); ?></h4>
		<?php endif; ?>
		<div class="property-info-block-inline">
			<div>
				<?php if (!empty( $price ) ): ?>
					<span class="property-price"><?php echo ere_get_format_money( $price ); ?><?php if(!empty( $price_postfix )) {echo '<span class="fs-14 accent-color">/'.$price_postfix.'</span>';} ?></span>
				<?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
					<span class="property-price"><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
				<?php endif; ?>
				<?php
				$property_status = get_the_terms( get_the_ID(), 'property-status' );
				if ( $property_status ) : ?>
					<div class="property-status">
						<?php foreach ( $property_status as $status ) : ?>
							<span><?php echo esc_attr( $status->name ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $property_address ) ): ?>
				<div class="property-position" title="<?php echo esc_attr( $property_address ) ?>">
					<i class="fa fa-map-marker accent-color"></i>
					<span><?php echo esc_attr( $property_address ) ?></span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>