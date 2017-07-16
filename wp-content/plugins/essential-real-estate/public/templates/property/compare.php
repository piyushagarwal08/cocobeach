<?php
global $hide_compare_fields;

$hide_compare_fields = ere_get_option('hide_compare_fields', array());

if (!is_array($hide_compare_fields)) {
	$hide_compare_fields = array();
}

$property_ids = $_SESSION['ere_compare_properties'];
$property_ids = array_diff($property_ids, ["0"]);

if (!empty($property_ids)) {
	$args = array(
		'post_type'   => 'property',
		'post__in'    => $property_ids,
		'post_status' => 'publish'
	);

	$data = New WP_Query($args);

	$property_item = $types = $status = $year = $size = $bedrooms = $bathrooms = $garage = $garage_size = $land = $zip = '';
	$empty_field='<td><i class="fa fa-minus"></i></td>';
	if ($data->have_posts()): while ($data->have_posts()): $data->the_post();

		$property_meta_data = get_post_custom(get_the_ID());

		$property_types = get_the_terms(get_the_ID(), 'property-type');
		$property_type_arr = array();
		if ($property_types) {
			foreach ($property_types as $property_type) {
				$property_type_arr[] = $property_type->name;
			}
		}

		$property_status = get_the_terms(get_the_ID(), 'property-status');
		$property_status_arr = array();
		if ($property_status) {
			foreach ($property_status as $statuss) {
				$property_status_arr[] = $statuss->name;
			}
		}

		$property_label = get_the_terms(get_the_ID(), 'property-labels');
		$property_label_arr = array();
		if ($property_label) {
			foreach ($property_label as $label) {
				$property_label_arr[] = $label->name;
			}
		}

		$property_year = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_year']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_year'][0] : '';
		$property_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0] : '';
		$property_bedrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0] : '';
		$property_bathrooms = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0] : '';
		$property_garage = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage'][0] : '';
		$property_garage_size = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size'][0] : '';
		$property_land = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_land']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_land'][0] : '';
		$property_zip = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_zip']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_zip'][0] : '';

		$attach_id = get_post_thumbnail_id();
		$avatar_src = ere_image_resize_id($attach_id, 332, 180, true);

		$price = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_price']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_price'][0] : '';
		$property_address = isset($property_meta_data[ERE_METABOX_PREFIX . 'property_address']) ? $property_meta_data[ERE_METABOX_PREFIX . 'property_address'][0] : '';

		$property_link = get_the_permalink();
		$measurement_units = ere_get_option('measurement_units','SqFt');
		$property_item .= '<th><div class="property-inner">';
		if (!empty($avatar_src)) {
			$property_item .= '<div class="property-avatar">
									<a href="' . $property_link . '" title="' . get_the_title() . '"></a>
									<img src="' . $avatar_src . '" alt="' . get_the_title() . '" title="' . get_the_title() . '">
								</div>';
		}
		if (!empty($property_label)) {
			$property_item .= '<div class="property-labels">';
			foreach ($property_label as $label_item):
				if (!empty($property_label)) {
					$property_item .= '<p class="label-item">
											<span class="property-label-bg" >
												' . $label_item->name . '
												<span class="property-arrow"></span>
											</span>
										</p>';
				}
			endforeach;
			$property_item .= '</div>';
		}
		$property_item .= '<div class="property-item-content">
								<h4 class="property-title fs-18"><a href="' . $property_link . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h4>
								<div class="property-info">
									<span class="property-price fs-16">' . ere_get_format_money( $price ) . '</span>
									<div class="property-position">
										<i class="fa fa-map-marker"></i>
										<span>' . $property_address . '</span>
									</div>
								</div>
							</div>';
		$property_item .= '</div></th>';
		if (!in_array("property_type", $hide_compare_fields)) {
			if (!empty($property_types)) {
				$types .= '<td>' . join(', ', $property_type_arr) . '</td>';
			} else {
				$types .= $empty_field;
			}
		}
		if (!in_array("property_status", $hide_compare_fields)) {
			if (!empty($property_status)) {
				$status .= '<td>' . join(', ', $property_status_arr) . '</td>';
			} else {
				$status .= $empty_field;
			}
		}
		if (!in_array("property_year", $hide_compare_fields)) {
			if (!empty($property_year)) {
				$year .= '<td>' . $property_year . '</td>';
			} else {
				$year .= $empty_field;
			}
		}
		if (!in_array("property_size", $hide_compare_fields)) {
			if (!empty($property_size)) {
				$size .= '<td>' . $property_size . ' ' . $measurement_units . '</td>';
			} else {
				$size .= $empty_field;
			}
		}
		if (!in_array("property_bedrooms", $hide_compare_fields)) {
			if (!empty($property_bedrooms)) {
				$bedrooms .= '<td>' . $property_bedrooms . '</td>';
			} else {
				$bedrooms .= $empty_field;
			}
		}
		if (!in_array("property_bathrooms", $hide_compare_fields)) {
			if (!empty($property_bathrooms)) {
				$bathrooms .= '<td>' . $property_bathrooms . '</td>';
			} else {
				$bathrooms .= $empty_field;
			}
		}
		if (!in_array("property_garage", $hide_compare_fields)) {
			if (!empty($property_garage)) {
				$garage .= '<td>' . $property_garage . '</td>';
			} else {
				$garage .= $empty_field;
			}
		}
		if (!in_array("property_garage_size", $hide_compare_fields)) {
			if (!empty($property_garage_size)) {
				$garage_size .= '<td>' . $property_garage_size . ' ' . $measurement_units . '</td>';
			} else {
				$garage_size .= $empty_field;
			}
		}
		if (!in_array("property_land", $hide_compare_fields)) {
			if (!empty($property_land)) {
				$measurement_units = ere_get_option('measurement_units','SqFt');
				$land .= '<td>' . $property_land . ' ' . $measurement_units . '</td>';
			} else {
				$land .= $empty_field;
			}
		}
		if (!in_array("property_zip", $hide_compare_fields)) {
			if (!empty($property_zip)) {
				$zip .= '<td>' . $property_zip . '</td>';
			} else {
				$zip .= $empty_field;
			}
		}
	endwhile; endif;
	?>
	<div class="row">
		<div class="compare-table-wrap col-sm-12">
			<table class="compare-tables table-striped">
				<thead>
				<tr>
					<th class="title-list-check"></th>
					<?php echo $property_item; ?>
				</tr>
				</thead>
				<tbody>
				<?php if (!empty($types)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Type', 'essential-real-estate'); ?></td>
						<?php echo $types; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($status)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Status', 'essential-real-estate'); ?></td>
						<?php echo $status; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($year)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Year Built', 'essential-real-estate'); ?></td>
						<?php echo $year; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($size)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Area Size', 'essential-real-estate'); ?></td>
						<?php echo $size; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($bedrooms)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Bedrooms', 'essential-real-estate'); ?></td>
						<?php echo $bedrooms; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($bathrooms)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Bathrooms', 'essential-real-estate'); ?></td>
						<?php echo $bathrooms; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($garage)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Garages', 'essential-real-estate'); ?></td>
						<?php echo $garage; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($garage_size)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Garages Size', 'essential-real-estate'); ?></td>
						<?php echo $garage_size; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($land)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Land Area', 'essential-real-estate'); ?></td>
						<?php echo $land; ?>
					</tr>
				<?php } ?>

				<?php if (!empty($zip)) { ?>
					<tr>
						<td class="title-list-check"><?php esc_html_e('Zip', 'essential-real-estate'); ?></td>
						<?php echo $zip; ?>
					</tr>
				<?php } ?>
				<?php
				$all_property_feature = get_categories(array(
					'hide_empty' => 0,
					'taxonomy'  => 'property-feature'
				));
				$compare_terms = array();
				foreach ($property_ids as $post_id) {
					$compare_terms[$post_id] = wp_get_post_terms($post_id, 'property-feature', array('fields' => 'ids'));
				}
				foreach ($all_property_feature as $feature)
				{
					?>
					<tr>
						<td class="title-list-check"><?php echo $feature->name; ?></td>
						<?php
						foreach ($property_ids as $post_id)
						{
							if (in_array($feature->term_id, $compare_terms[$post_id]))
							{
								echo '<td><div class="check-yes"><i class="fa fa-check"></i></div></td>';
							}
							else
							{
								echo '<td><div class="check-no"><i class="fa fa-minus"></i></div></td>';
							}
						}
						?>
					</tr>
					<?php
				} ?>
				</tbody>
			</table>
		</div>
	</div>
<?php
	wp_reset_postdata();
} else {?>
	<div class="item-not-found"><?php esc_html_e('No item compare', 'essential-real-estate'); ?></div>
<?php } ?>