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

$property_content_class        = array( 'property-content-wrap row' );
$property_item_class           = array( 'property-item' );
$property_content_attributes   = array( 'data-type="carousel"' );
$filter_class                  = array( 'hidden-mb property-filter-content' );
$filter_attributes             = array();
$filter_attributes[]           = 'data-layout-style="' . $layout_style . '"';
$filter_attributes[]           = "data-item-amount='" . $item_amount . "'";
$filter_attributes[]           = "data-color-scheme='" . $color_scheme . "'";
$filter_attributes[]           = 'data-item=".property-item"';
$property_content_attributes[] = 'data-filter-content="filter"';

$owl_attributes = array(
	'"dots": true',
	'"nav": false',
	'"items": 1',
	'"autoplay": true',
	'"autoplaySpeed": 5000'
);

$property_content_attributes[] = "data-plugin-options='{" . implode( ', ', $owl_attributes ) . "}'";
$filter_class[]                = 'property-filter-carousel';
$filter_attributes[]           = 'data-filter-type="carousel"';
$property_content_attributes[] = 'data-layout="filter"';
?>
<?php $filter_id = rand(); ?>
<?php if($include_heading && (!empty($heading_sub_title) || !empty($heading_title))) :?>
	<div
		class="ere-heading mg-bottom-60 sm-mg-bottom-40 <?php echo sprintf( "%s %s", $color_scheme, $heading_text_align ); ?>">
		<span></span>
		<?php if ( ! empty( $heading_sub_title ) ): ?>
			<p><?php echo esc_html( $heading_sub_title ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $heading_title ) ): ?>
			<h2><?php echo esc_html( $heading_title ); ?></h2>
		<?php endif; ?>
	</div>
<?php endif; ?>
<div class="<?php echo join( ' ', $property_content_class ); ?>">
	<div class="filter-wrap col-md-3" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
		<div data-filter_id="<?php echo esc_attr( $filter_id ); ?>"
		     class="<?php echo join( ' ', $filter_class ); ?>" <?php echo join( ' ', $filter_attributes ); ?>>
			<?php
			if (!empty($property_cities)) {
				$property_city_arr = explode( ',',$property_cities );
				$index = 0;
				foreach ($property_city_arr as $property_city) {
					$city = get_term_by( 'slug', $property_city, 'property-city', 'OBJECT' ); ?>
					<a class="portfolio-filter-category<?php if ($index == 0): ?> active-filter<?php endif; ?>"
					   data-filter=".<?php echo esc_attr($property_city); ?>"><?php echo esc_attr( $city->name ) ?></a>
					<?php
					$index++;
				}
			} ?>
		</div>
		<div class="visible-mb">
			<select class="property-filter-mb">
				<?php
				if (!empty($property_cities)) {
					$property_city_arr = explode( ',',$property_cities );
					$index = 0;
					foreach ($property_city_arr as $property_city) {
						$city = get_term_by( 'slug', $property_city, 'property-city', 'OBJECT' ); ?>
						<option<?php if ($index == 0): ?> selected<?php endif; ?>
							value=".<?php echo esc_attr($property_city); ?>"><?php echo esc_attr( $city->name ) ?></option>
						<?php
						$index++;
					}
				} ?>
			</select>
		</div>
	</div>
	<div class="property-content-inner col-md-9">
		<div class="property-content owl-carousel" <?php echo join( ' ', $property_content_attributes ); ?>
		     data-filter_id="<?php echo esc_attr( $filter_id ); ?>">
			<?php if ( $data->have_posts() ) :
				while ( $data->have_posts() ): $data->the_post();
					$attach_id  = get_post_thumbnail_id();
					$image_src = ere_image_resize_id( $attach_id, 835, 320, true );
					if (! empty( $image_src ) ) {
						list( $width, $height ) = getimagesize( $image_src );
					}
					$excerpt = get_the_excerpt();

					$property_meta_data = get_post_custom( get_the_ID() );

					$price                 = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ][0] : '';
					$price_postfix         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price_postfix' ][0] : '';
					$property_size         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ][0] : '';
					$property_bedrooms     = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ][0] : '0';
					$property_bathrooms    = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ][0] : '0';
					$property_garage       = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage' ][0] : '0';

					$property_link = get_the_permalink();
					?>
					<div class="<?php echo join( ' ', $property_item_class ); ?>">
						<div class="property-inner">
							<div class="property-avatar">
								<?php if ( ! empty( $image_src ) ): ?>
									<a href="<?php echo esc_url( $property_link ); ?>"
									   title="<?php the_title(); ?>"></a>
									<img width="835" height="320"
									     src="<?php echo esc_url( $image_src ) ?>" alt="<?php the_title(); ?>"
									     title="<?php the_title(); ?>">
								<?php endif; ?>
							</div>
							<div class="property-item-content">
								<div class="property-heading">
									<a href="<?php echo esc_url( $property_link ); ?>"
									   title="<?php the_title(); ?>"><?php the_title(); ?></a>
									<?php if ( ! empty( $price ) ): ?>
										<span class="property-price"><?php echo ere_get_format_money( $price ) ?><?php if(!empty( $price_postfix )) {echo '<span class="fs-12 accent-color">/'.$price_postfix.'</span>';} ?></span>
									<?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
										<span class="property-price"><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
									<?php endif; ?>
								</div>
								<div class="property-info">
									<div class="property-info-inner">
										<div class="property-id">
											<div class="property-info-item-inner">
												<span class="fa fa-barcode accent-color"></span>
												<div class="content-property-info">
													<p class="property-info-value"><?php
														$property_id = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_id' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_id' ][0] : '';
														if(!empty($property_id))
														{
															echo esc_html($property_id);
														}
														else
														{
															echo get_the_ID();
														}?></p>
													<p class="property-info-title"><?php esc_html_e( 'Property ID', 'essential-real-estate' ); ?></p>
												</div>
											</div>
										</div>
										<?php if ( ! empty( $property_size ) ): ?>
											<div class="property-area">
												<div class="property-info-item-inner">
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
					</div>
				<?php endwhile;

			else: ?>
				<div class="item-not-found"><?php esc_html_e( 'No item found', 'essential-real-estate' ); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>




