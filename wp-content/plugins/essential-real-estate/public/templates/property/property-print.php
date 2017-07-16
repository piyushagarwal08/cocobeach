<?php
/**
 * @var $isRTL
 * @var $property_id
 */
$the_post = get_post( $property_id );

if ( $the_post->post_type != 'property' ) {
	esc_html_e( 'Posts ineligible to print!', 'essential-real-estate' );
	return;
}
$page_url  = get_bloginfo( 'url', '' );

print  '<html><head><title>' . $page_url . '</title>';
print  '<link href="' . ERE_PLUGIN_URL . '/public/assets/css/property-print.css" rel="stylesheet" type="text/css" />';
print  '<link href="' . ERE_PLUGIN_URL . '/public/assets/packages/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />';
print  '<link href="' . ERE_PLUGIN_URL . '/public/assets/packages/fonts-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />';

if( $isRTL == 'true' ) {
	print '<link href="'.ERE_PLUGIN_URL.'/public/assets/css/property-print-rtl.css" rel="stylesheet" type="text/css" />';
}
print '</head>';
print  '<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script><script>$(window).load(function(){ print(); });</script>';
print  '<body>';

$print_logo = ere_get_option( 'print_logo', '' );
$attach_id = '';
if(is_array( $print_logo ) && count( $print_logo ) > 0) {
	$attach_id = $print_logo['id'];
}
$image_size = ere_get_option( 'print_logo_size','200x100' );
$image_src  = '';
$width      = '';
$height     = '';
if($attach_id) {
	if ( preg_match( '/\d+x\d+/', $image_size ) ) {
		$image_sizes = explode( 'x', $image_size );
		$image_src  = ere_image_resize_id( $attach_id, $image_sizes[0], $image_sizes[1], true );
	} else {
		if ( ! in_array( $image_size, array( 'full', 'thumbnail' ) ) ) {
			$image_size = 'full';
		}
		$image_src = wp_get_attachment_image_src( $attach_id, $image_size );
		if ( $image_src && ! empty( $image_src[0] ) ) {
			$image_src = $image_src[0];
		}
	}
}
if(!empty( $image_src )) {
	list( $width, $height ) = getimagesize( $image_src );
}
$page_name = get_bloginfo( 'name', '' );

$property_meta_data = get_post_custom( $property_id );

$property_label     = get_the_terms( $property_id, 'property-labels' );
$property_label_arr = array();
if ( $property_label ) {
	foreach ( $property_label as $label ) {
		$property_label_arr[] = $label->name;
	}
}
$property_types    = get_the_terms( $property_id, 'property-type' );
$property_type_arr = array();
if ( $property_types ) {
	foreach ( $property_types as $property_type ) {
		$property_type_arr[] = $property_type->name;
	}
}
$property_identity    = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_identity' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_identity' ][0] : '';
$price                = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_price' ][0] : '';
$property_address     = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_address' ][0] : '';
$property_bedrooms    = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bedrooms' ][0] : '0';
$property_bathrooms   = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_bathrooms' ][0] : '0';
$property_garage      = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage' ][0] : '0';
$property_zip         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_zip' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_zip' ][0] : '';
$property_year        = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_year' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_year' ][0] : '';
$property_garage_size = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage_size' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_garage_size' ][0] : '';

$property_neighborhood     = get_the_terms( $property_id, 'property-neighborhood' );
$property_neighborhood_arr = array();
if ( $property_neighborhood ) {
	foreach ( $property_neighborhood as $neighborhood_item ) {
		$property_neighborhood_arr[] = $neighborhood_item->name;
	}
}
$property_city     = get_the_terms( $property_id, 'property-city' );
$property_city_arr = array();
if ( $property_city ) {
	foreach ( $property_city as $city_item ) {
		$property_city_arr[] = $city_item->name;
	}
}
$property_state     = get_the_terms( $property_id, 'property-state' );
$property_state_arr = array();
if ( $property_state ) {
	foreach ( $property_state as $state_item ) {
		$property_state_arr[] = $state_item->name;
	}
}
$property_features     = get_the_terms( $property_id, 'property-feature' );

$property_size         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_size' ][0] : '';
$property_land         = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_land' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_land' ][0] : '';

$additional_features      = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'additional_features' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'additional_features' ][0] : '';
$half_additional_features = 0;
$additional_feature_title = $additional_feature_value = null;
if ( $additional_features > 0 ) {
	$additional_feature_title = get_post_meta( $property_id, ERE_METABOX_PREFIX . 'additional_feature_title', true );
	$additional_feature_value = get_post_meta( $property_id, ERE_METABOX_PREFIX . 'additional_feature_value', true );
	$half_additional_features = floor( $additional_features / 2 );
}
$measurement_units = ere_get_option('measurement_units','SqFt');
?>

	<div id="property-print-wrap">
		<div class="property-print-inner">
			<?php if(!empty( $image_src )): ?>
				<div class="home-page-info">
					<img src="<?php echo esc_url( $image_src ) ?>" alt="<?php echo esc_attr( $page_name ) ?>"
					     width="<?php echo esc_attr( $width ) ?>" height="<?php echo esc_attr( $height ) ?>">
				</div>
			<?php endif; ?>
			<div class="property-main-info">
				<div class="property-heading">
					<div class="pull-left">
						<?php $title = get_the_title( $property_id );
						if ( isset( $title ) && ! empty( $title ) ):?>
							<h3 class="property-title fs-24"><?php echo esc_attr( $title ); ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $property_address ) ): ?>
							<div class="property-position">
								<i class="fa fa-map-marker accent_color"></i>
								<span><?php echo esc_attr( $property_address ) ?></span>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $price ) ): ?>
							<div class="property-price">
								<span><?php echo ere_get_format_money( $price ) ?></span>
							</div>
						<?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
							<div class="property-price">
								<span><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
							</div>
						<?php endif; ?>
					</div>
					<div class="property-link-api pull-right">
						<img class="qr-image"
						     src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl=<?php echo esc_url( get_permalink( $property_id ) ); ?>&choe=UTF-8"
						     title="<?php echo esc_attr( get_the_title( $property_id ) ); ?>"/>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="property-info">
					<div class="property-id">
						<span class="fa fa-barcode"></span>
						<div class="content-property-info">
							<p class="property-info-value"><?php
								if(!empty($property_identity))
								{
									echo esc_html($property_identity);
								}
								else
								{
									echo esc_html($property_id);
								}
								?></p>
							<p class="property-info-title"><?php esc_html_e( 'Property ID', 'essential-real-estate' ); ?></p>
						</div>
					</div>
					<?php if ( ! empty( $property_size ) ): ?>
						<div class="property-area">
							<span class="fa fa-arrows"></span>
							<div class="content-property-info">
								<p class="property-info-value"><?php echo esc_attr( $property_size ) ?>
										<span><?php echo esc_attr( $measurement_units ) ?></span>
								</p>
								<p class="property-info-title"><?php esc_html_e( 'Area', 'essential-real-estate' ); ?></p>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $property_bedrooms ) ): ?>
						<div class="property-bedrooms">
							<span class="fa fa-hotel"></span>
							<div class="content-property-info">
								<p class="property-info-value"><?php echo esc_attr( $property_bedrooms ) ?></p>
								<p class="property-info-title"><?php esc_html_e( 'Bedroom', 'essential-real-estate' ); ?></p>
							</div>
						</div>
					<?php endif; ?>
					<?php if (! empty( $property_bathrooms ) ): ?>
						<div class="property-bathrooms">
							<span class="fa fa-bath"></span>
							<div class="content-property-info">
								<p class="property-info-value"><?php echo esc_attr( $property_bathrooms ) ?></p>
								<p class="property-info-title"><?php esc_html_e( 'Bathroom', 'essential-real-estate' ); ?></p>
							</div>
						</div>
					<?php endif; ?>
					<?php if (! empty( $property_garage ) ): ?>
						<div class="property-garage">
							<span class="fa fa-car accent_color"></span>
							<div class="content-property-info">
								<p class="property-info-value"><?php echo esc_attr( $property_garage ) ?></p>
								<p class="property-info-title"><?php esc_html_e( 'Garage', 'essential-real-estate' ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="property-thumb">
					<?php
					$attach_id = get_post_thumbnail_id( $property_id );
					$image_src = '';

					$image_src = ere_image_resize_id( $attach_id, 1160, 500, true );
					if (! empty( $image_src ) ) {?>
						<img width="1160" height="500"
						     src="<?php echo esc_url( $image_src ) ?>" alt="<?php the_title(); ?>"
						     title="<?php the_title(); ?>">
					<?php } ?>
				</div>
			</div>
			<?php
			$agent_display_option = isset($property_meta_data[ ERE_METABOX_PREFIX . 'agent_display_option' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'agent_display_option' ][0] : '';
			$property_agent       = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_agent' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_agent' ][0] : '';
			$property_other_contact_mail = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_mail' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_mail' ][0] : '';
			if ( $agent_display_option == 'author_info' || ( $agent_display_option == 'other_info' && !empty( $property_other_contact_mail )) || ( $agent_display_option == 'agent_info' && ! empty( $property_agent ) ) ) {
				$email = $avatar_src = $agent_link = $agent_name = $agent_position = $agent_mobile_number = $agent_office_address = $agent_website_url = $agent_description = '';
				if ( $agent_display_option != 'other_info' ) {
					if ( $agent_display_option == 'author_info' ) {
						$user_id = $the_post->post_author;
						$email   = get_userdata( $user_id )->user_email;
					} else {
						$email   = get_post_meta( $property_agent, ERE_METABOX_PREFIX . 'agent_email', true );
						$user_id = get_user_by( 'email', $email )->ID;
					}
					$author_agent_id = get_the_author_meta( ERE_METABOX_PREFIX . 'author_agent_id', $user_id );
					$user_info       = get_userdata( $user_id );

					// Show Property Author Info (Get info via User. Apply for User, Agent, Seller)
					$author_picture_id = get_the_author_meta( ERE_METABOX_PREFIX . 'author_picture_id', $user_id );
					$avatar_src        = ere_image_resize_id( $author_picture_id, 110, 110, true );
					$agent_name = $user_info->first_name . ' ' . $user_info->last_name;

					$agent_mobile_number  = get_the_author_meta( ERE_METABOX_PREFIX . 'author_mobile_number', $user_id );
					$agent_website_url    = get_the_author_meta( 'user_url', $user_id );
				} elseif ( $agent_display_option == 'other_info' ) {
					$email               = $property_other_contact_mail;
					$agent_name          = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_name' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_name' ][0] : '';
					$agent_mobile_number = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_phone' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_phone' ][0] : '';
				} ?>

				<div class="property-block agent-block clearfix">
					<?php if(!empty( $avatar_src )): ?>
						<div class="agent-image">
							<img src="<?php echo esc_url( $avatar_src ); ?>"
							     alt="<?php echo esc_attr( $agent_name ); ?>" height="110" width="110">
						</div>
					<?php endif; ?>
					<div class="agent-info">
						<h4 class="property-block-title"><?php esc_html_e( 'Contact Agent', 'essential-real-estate' ); ?></h4>
						<ul>
							<?php if ( isset( $agent_name ) && ! empty( $agent_name ) ): ?>
								<li><strong><?php echo esc_attr( $agent_name ); ?></strong></li>
							<?php endif; ?>
							<?php if (! empty( $agent_mobile_number ) ): ?>
								<li>
									<span><strong><?php esc_html_e( 'Mobile:', 'essential-real-estate' ); ?></strong></span> <span><?php echo esc_attr( $agent_mobile_number ); ?></span>
								</li>
							<?php endif; ?>
							<?php if ( ! empty( $email ) ): ?>
								<li>
									<span><strong><?php esc_html_e( 'Email:', 'essential-real-estate' ); ?></strong></span> <span><?php echo esc_attr( $email ); ?></span>
								</li>
							<?php endif; ?>
							<?php if ( ! empty( $agent_website_url ) ): ?>
								<li>
									<span><strong><?php esc_html_e( 'Website:', 'essential-real-estate' ); ?></strong></span> <span><?php echo esc_url( $agent_website_url ); ?></span>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			<?php } ?>
			<?php $description = $the_post->post_content;
			if ( isset( $description ) && ! empty( $description ) ):?>
				<div class="property-block description-block clearfix">
					<h4 class="property-block-title"><?php esc_html_e( 'Description', 'essential-real-estate' ); ?></h4>
					<?php echo wp_kses_post( $description ); ?>
				</div>
			<?php endif; ?>
			<div class="property-block overview-block clearfix">
				<h4 class="property-block-title"><?php esc_html_e( 'Overview', 'essential-real-estate' ); ?></h4>
				<table class="overview-table">
					<tbody>
					<?php if ( !empty( $price ) ): ?>
						<tr>
							<th><?php esc_html_e( 'Price', 'essential-real-estate' ); ?></th>
							<td><span><?php echo ere_get_format_money( $price ) ?></span></td>
						</tr>
					<?php elseif (ere_get_option( 'empty_price_text', '' )!='' ): ?>
						<tr>
							<th><?php esc_html_e( 'Price', 'essential-real-estate' ); ?></th>
							<td><span><?php echo ere_get_option( 'empty_price_text', '' ) ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( $property_types ): ?>
						<tr>
							<th><?php esc_html_e( 'Property Type', 'essential-real-estate' ); ?></th>
							<td><span><?php echo join( ', ', $property_type_arr ) ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( !empty($property_year) ): ?>
						<tr>
							<th><?php esc_html_e( 'Year Built', 'essential-real-estate' ); ?></th>
							<td><span><?php echo esc_attr( $property_year ) ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( !empty($property_bathrooms) ): ?>
						<tr>
							<th><?php esc_html_e( 'Bathrooms', 'essential-real-estate' ); ?></th>
							<td><span><?php echo esc_attr( $property_bathrooms ) ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( !empty($property_garage_size) ): ?>
						<tr>
							<th><?php esc_html_e( 'Garage Size', 'essential-real-estate' ); ?></th>
							<td>
								<span><?php echo sprintf( "%s %s", $property_garage_size, $measurement_units ); ?></span>
							</td>
						</tr>
					<?php endif; ?>
					<?php if ( !empty($property_size) ): ?>
						<tr>
							<th><?php esc_html_e( 'Area size', 'essential-real-estate' ); ?></th>
							<td>
								<span><?php echo sprintf( "%s %s", $property_size, $measurement_units ); ?></span>
							</td>
						</tr>
					<?php endif; ?>
					<?php for ( $i = 0; $i < $half_additional_features; $i ++ ) { ?>
						<?php if ( ! empty( $additional_feature_title[ $i ] ) && ! empty( $additional_feature_value[ $i ] ) ): ?>
							<tr>
								<th><?php echo esc_attr( $additional_feature_title[ $i ] ); ?>:</th>
								<td><span><?php echo esc_attr( $additional_feature_value[ $i ] ) ?></span>
								</td>
							</tr>
						<?php endif; ?>
					<?php } ?>
					</tbody>
				</table>
				<table class="overview-table">
					<tbody>
					<tr>
						<th><?php esc_html_e( 'Property ID', 'essential-real-estate' ); ?></th>
						<td><span><?php
								if(!empty($property_identity))
								{
									echo esc_html($property_identity);
								}
								else
								{
									echo esc_html($property_id);
								}?></span></td>
					</tr>
					<?php if ( $property_label ): ?>
						<tr>
							<th><?php esc_html_e( 'Labels', 'essential-real-estate' ); ?></th>
							<td><?php if ( $property_label_arr ): ?>
									<span><?php echo join( ', ', $property_label_arr ) ?></span><?php endif; ?>
							</td>
						</tr>
					<?php endif; ?>
					<?php if ( !empty($property_bedrooms) ): ?>
						<tr>
							<th><?php esc_html_e( 'Bedrooms', 'essential-real-estate' ); ?></th>
							<td><span><?php echo esc_attr( $property_bedrooms ) ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( !empty($property_garage) ): ?>
						<tr>
							<th><?php esc_html_e( 'Garages', 'essential-real-estate' ); ?></th>
							<td><span><?php echo esc_attr( $property_garage ) ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( !empty($property_land) ): ?>
						<tr>
							<th><?php esc_html_e( 'Land area', 'essential-real-estate' ); ?></th>
							<td>
								<span><?php
									$measurement_units = ere_get_option('measurement_units','SqFt');
									echo sprintf( "%s %s", $property_land, $measurement_units ); ?></span>
							</td>
						</tr>
					<?php endif; ?>
					<?php for ( $i = $half_additional_features; $i < $additional_features; $i ++ ) { ?>
						<?php if ( ! empty( $additional_feature_title[ $i ] ) && ! empty( $additional_feature_value[ $i ] ) ): ?>
							<tr>
								<th><?php echo esc_attr( $additional_feature_title[ $i ] ); ?>:</th>
								<td><span><?php echo esc_attr( $additional_feature_value[ $i ] ) ?></span>
								</td>
							</tr>
						<?php endif; ?>
					<?php } ?>
					</tbody>
				</table>
				<div class="clearfix"></div>
			</div>

			<?php if ( $property_features ): ?>
				<div class="property-block features-block clearfix">
					<h4 class="property-block-title"><?php esc_html_e( 'Features', 'essential-real-estate' ); ?></h4>
					<?php foreach ( $property_features as $features_item ) {
						echo '<div class="feature-item"><span><i class="fa fa-check-square-o"></i> '.$features_item->name.'</span></div>';
					}?>
				</div>
			<?php endif; ?>

			<div class="property-block location-block clearfix">
				<h4 class="property-block-title"><?php esc_html_e( 'Location', 'essential-real-estate' ); ?></h4>
				<table class="location-table">
					<tbody>
					<?php if ( ! empty( $property_address ) ): ?>
						<tr>
							<th><?php esc_html_e( 'Address', 'essential-real-estate' ); ?></th>
							<td><span><?php echo esc_attr( $property_address ) ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( $property_city ): ?>
						<tr>
							<th><?php esc_html_e( 'Cities', 'essential-real-estate' ); ?></th>
							<td><span><?php echo join( ', ', $property_city_arr ); ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( $property_neighborhood ): ?>
						<tr>
							<th><?php esc_html_e( 'Neighborhood', 'essential-real-estate' ); ?></th>
							<td><span><?php echo join( ', ', $property_neighborhood_arr ); ?></span></td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
				<table class="location-table">
					<tbody>
					<?php if ( $property_state ): ?>
						<tr>
							<th><?php esc_html_e( 'Province / State', 'essential-real-estate' ); ?></th>
							<td><span><?php echo join( ', ', $property_state_arr ); ?></span></td>
						</tr>
					<?php endif; ?>
					<?php if ( ! empty( $property_zip ) ): ?>
						<tr>
							<th><?php esc_html_e( 'Postal code / ZIP', 'essential-real-estate' ); ?></th>
							<td><span><?php echo esc_attr( $property_zip ) ?></span></td>
						</tr>
					<?php endif; ?>
					</tbody>
				</table>
				<div class="clearfix"></div>
			</div>

			<?php $property_floors = get_post_meta( $property_id, ERE_METABOX_PREFIX . 'floors', true );
			$property_floor_enable = isset( $property_meta_data[ ERE_METABOX_PREFIX . 'floors_enable' ] ) ? $property_meta_data[ ERE_METABOX_PREFIX . 'floors_enable' ][0] : '';
			if ( $property_floor_enable && $property_floors ): ?>
				<div class="property-block floors-block">
					<h4 class="property-block-title"><?php esc_html_e( 'Floor Plans', 'essential-real-estate' ); ?></h4>
				</div>
				<?php $index = 0; ?>
				<?php foreach ( $property_floors as $floor ):
					$image_id = $floor[ ERE_METABOX_PREFIX . 'floor_image' ]['id'];
					$width         = '870';
					$height        = '420';
					$image_src     = ere_image_resize_id( $image_id, 870, 420, true );
					$floor_name          = $floor[ ERE_METABOX_PREFIX . 'floor_name' ];
					$floor_size          = $floor[ ERE_METABOX_PREFIX . 'floor_size' ];
					$floor_size_postfix  = $floor[ ERE_METABOX_PREFIX . 'floor_size_postfix' ];
					$floor_bathrooms     = $floor[ ERE_METABOX_PREFIX . 'floor_bathrooms' ];
					$floor_price         = $floor[ ERE_METABOX_PREFIX . 'floor_price' ];
					$floor_price_postfix = $floor[ ERE_METABOX_PREFIX . 'floor_price_postfix' ];
					$floor_bedrooms      = $floor[ ERE_METABOX_PREFIX . 'floor_bedrooms' ];
					$floor_description   = $floor[ ERE_METABOX_PREFIX . 'floor_description' ];
					?>
					<div class="floor-item">
						<div class="floor-info">
							<?php if ( isset( $floor_name ) && ! empty( $floor_name ) ): ?>
								<h4><?php echo !empty( $floor_name ) ? sanitize_text_field( $floor_name ) : (esc_html__( 'Floor', 'essential-real-estate' ) . ' ' . ($index + 1)) ?></h4>
							<?php endif; ?>
							<div class="pull-right floor-main-info">
								<?php if ( isset( $floor_size ) && ! empty( $floor_size ) ): ?>
									<div class="floor-size">
												<span
													class="floor-info-title"><?php esc_html_e( 'Size:', 'essential-real-estate' ); ?></span>
												<span
													class="floor-info-value"><?php echo sanitize_text_field( $floor_size ); ?>
													<?php echo ( isset( $floor_size_postfix ) && ! empty( $floor_size_postfix ) ) ? sanitize_text_field( $floor_size_postfix ) : '' ?></span>
									</div>
								<?php endif; ?>
								<?php if ( isset( $floor_bathrooms ) && ! empty( $floor_bathrooms ) ): ?>
									<div class="floor-bath">
												<span
													class="floor-info-title"><?php esc_html_e( 'Bathrooms:', 'essential-real-estate' ); ?></span>
												<span
													class="floor-info-value"><?php echo sanitize_text_field( $floor_bathrooms ); ?></span>
									</div>
								<?php endif; ?>
								<?php if ( isset( $floor_bedrooms ) && ! empty( $floor_bedrooms ) ): ?>
									<div class="floor-bed">
												<span
													class="floor-info-title"><?php esc_html_e( 'Bedrooms:', 'essential-real-estate' ); ?></span>
												<span
													class="floor-info-value"><?php echo sanitize_text_field( $floor_bedrooms ); ?></span>
									</div>
								<?php endif; ?>
								<?php if ( isset( $floor_price ) && ! empty( $floor_price ) ): ?>
									<div class="floor-price">
												<span
													class="floor-info-title"><?php esc_html_e( 'Price:', 'essential-real-estate' ); ?></span>
												<span
													class="floor-info-value"><?php echo ere_get_format_money( $floor_price ); ?>
													<?php echo ( isset( $floor_price_postfix ) && ! empty( $floor_price_postfix ) ) ? '/' . sanitize_text_field( $floor_price_postfix ) : '' ?></span>
									</div>
								<?php endif; ?>
							</div>
						</div>
						<?php if ( ! empty( $image_src ) ): ?>
							<div class="floor-image">
								<img width="<?php echo esc_attr( $width ) ?>"
								     height="<?php echo esc_attr( $height ) ?>"
								     src="<?php echo esc_url( $image_src ); ?>"
								     alt="<?php the_title_attribute(); ?>">
							</div>
						<?php endif; ?>
						<?php if ( isset( $floor_description ) && ! empty( $floor_description ) ): ?>
							<div class="floor-description">
								<?php echo sanitize_text_field( $floor_description ); ?>
							</div>
						<?php endif; ?>
					</div>
					<?php $index ++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
<?php
print '</body></html>';
wp_die();