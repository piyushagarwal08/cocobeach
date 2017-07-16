<?php
global $post;
$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'single-property', ERE_PLUGIN_URL . 'public/assets/js/property/ere-single-property' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'single-property');
wp_enqueue_script('lightgallery-all');
wp_enqueue_style('lightgallery-all');
?>
<div class="content-single-property">
	<?php
	/**
	 * ere_single_property_before_summary hook.
	 */
	do_action( 'ere_single_property_before_summary' );
	?>
	<?php
	/**
	* ere_single_property_summary hook.
	*
	* @hooked single_property_info_header - 5
	* @hooked single_property_gallery - 10
	* @hooked single_property_info_tabs - 15
	* @hooked single_property_floors - 20
	* @hooked single_property_map_directions - 25
	* @hooked single_property_nearby_places - 30
	* @hooked single_property_walk_score - 35
	* @hooked single_property_contact_agent - 40
	* @hooked single_property_info_footer - 90
	*/
	do_action( 'ere_single_property_summary' ); ?>
	<?php
	/**
	 * ere_single_property_after_summary hook.
	 */
	do_action( 'ere_single_property_after_summary' );
	?>

	<!-- Comment -->
	<?php if ( comments_open() || get_comments_number() ) : ?>
		<div class="entry-meta-comment">
			<?php echo comments_template(); ?>
		</div>
	<?php endif; ?>
	<!-- End Comment -->
</div>