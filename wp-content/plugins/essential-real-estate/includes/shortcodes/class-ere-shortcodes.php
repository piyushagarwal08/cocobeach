<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 08/11/2016
 * Time: 10:30 SA
 */
if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('ERE_Shortcode')) {
	/**
	 * ERE_Shortcode_Agent class.
	 */
	class ERE_Shortcode
	{

		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->include_system_shortcode();
			$this->register_public_shortcode();
		}

		/**
		 * Include system shortcode
		 */
		public function include_system_shortcode()
		{
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-account.php';
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-property.php';
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-package.php';
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-payment.php';
			require_once ERE_PLUGIN_DIR . 'includes/shortcodes/system/class-ere-shortcode-invoice.php';
		}

		/**
		 * Register shortcode
		 */
		public function register_public_shortcode()
		{
			add_shortcode('ere_property', array($this, 'property_shortcode'));
			add_shortcode('ere_property_carousel', array($this, 'property_carousel_shortcode'));
			add_shortcode('ere_property_slider', array($this, 'property_slider_shortcode'));
			add_shortcode('ere_property_gallery', array($this, 'property_gallery_shortcode'));
			add_shortcode('ere_property_featured', array($this, 'property_featured_shortcode'));
			add_shortcode('ere_property_type', array($this, 'property_type_shortcode'));
			add_shortcode('ere_property_search', array($this, 'property_search_shortcode'));
			add_shortcode('ere_property_mini_search', array($this, 'property_mini_search_shortcode'));
			add_shortcode('ere_property_map', array($this, 'property_map_shortcode'));
			add_shortcode('ere_agent', array($this, 'agent_shortcode'));
			add_shortcode('ere_agency', array($this, 'agency_shortcode'));
		}

		/**
		 * Property Gallery
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_gallery_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property-gallery/property-gallery.php', array('atts' => $atts));
		}

		/**
		 * Property Carousel with Left Navigation
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_carousel_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property-carousel/property-carousel.php', array('atts' => $atts));
		}

		/**
		 * Property Slider
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_slider_shortcode($atts) {
			return ere_get_template_html('shortcodes/property-slider/property-slider.php', array('atts' => $atts));
		}

		/**
		 * Property Search
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_search_shortcode($atts) {
			return ere_get_template_html('shortcodes/property-search/property-search.php', array('atts' => $atts));
		}

		/**
		 * Mini Search
		 * @param $atts
		 * @return string
		 */
		public function property_mini_search_shortcode($atts) {
			return ere_get_template_html('shortcodes/property-mini-search/property-mini-search.php', array('atts' => $atts));
		}

		/**
		 * Property Featured
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_featured_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property-featured/property-featured.php', array('atts' => $atts));
		}

		/**
		 * Property type
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_type_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property-type/property-type.php', array('atts' => $atts));
		}

		/**
		 * Property shortcode
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property/property.php', array('atts' => $atts));
		}

		/**
		 * Agent shortcode
		 * @param $atts
		 *
		 * @return string
		 */
		public function agent_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/agent/agent.php', array('atts' => $atts));
		}

		/**
		 * Agency shortcode
		 * @param $atts
		 *
		 * @return string
		 */
		public function agency_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/agency/agency.php', array('atts' => $atts));
		}

		/**
		 * googlemap property
		 * @param $atts
		 *
		 * @return string
		 */
		public function property_map_shortcode($atts)
		{
			return ere_get_template_html('shortcodes/property-map/property-map.php', array('atts' => $atts));
		}

		/**
		 * Filter Ajax callback
		 */
		public function property_gallery_fillter_ajax()
		{
			$property_type = str_replace('.', '', $_REQUEST['property_type']);
			$is_carousel = $_REQUEST['is_carousel'];
			$columns_gap = $_REQUEST['columns_gap'];
			$columns = $_REQUEST['columns'];
			$item_amount = $_REQUEST['item_amount'];
			$color_scheme = $_REQUEST['color_scheme'];

			$short_code = '[ere_property_gallery is_carousel="' . $is_carousel . '" color_scheme="' . $color_scheme . '"
		columns="' . $columns . '" item_amount="' . $item_amount . '" columns_gap="' . $columns_gap . '"
		category_filter="true" property_type="' . $property_type . '"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		/**
		 * Filter City Ajax callback
		 */
		public function property_featured_fillter_city_ajax()
		{
			$property_city = str_replace('.', '', $_REQUEST['property_city']);
			$layout_style = $_REQUEST['layout_style'];
			$item_amount = $_REQUEST['item_amount'];
			$color_scheme = $_REQUEST['color_scheme'];

			$short_code = '[ere_property_featured layout_style="' . $layout_style . '" color_scheme="' . $color_scheme . '"
		item_amount="' . $item_amount . '" property_city="' . $property_city . '"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		/**
		 * Property paging
		 */
		public function property_paging_ajax()
		{
			$paged = $_REQUEST['paged'];
			$layout = $_REQUEST['layout'];
			$items_amount = $_REQUEST['items_amount'];
			$columns = $_REQUEST['columns'];
			$image_size = $_REQUEST['image_size'];
			$columns_gap = $_REQUEST['columns_gap'];
			$view_all_link = $_REQUEST['view_all_link'];
			$author_id = $_REQUEST['author_id'];
			$agent_id = $_REQUEST['agent_id'];
			$short_code = '[ere_property item_amount="' . $items_amount . '" layout_style="' . $layout . '"
					view_all_link="' . $view_all_link . '" show_paging="true" columns="' . $columns . '"
					image_size="' . $image_size . '" columns_gap="' . $columns_gap . '" paged="' . $paged . '"
				    author_id="' . $author_id . '" agent_id="' . $agent_id . '"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		/**
		 * Agent paging
		 */
		public function agent_paging_ajax()
		{
			$paged = $_REQUEST['paged'];
			$layout = $_REQUEST['layout'];
			$item_amount = $_REQUEST['item_amount'];
			$items = $_REQUEST['items'];
			$show_paging = $_REQUEST['show_paging'];
			$post_not_in = $_REQUEST['post_not_in'];

			$short_code = '[ere_agent layout_style="' . $layout . '" item_amount="' . $item_amount . '" items ="' . $items . '" paged="' . $paged . '" show_paging="' . $show_paging . '" post_not_in="' . $post_not_in . '"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		/**
		 * Agency paging
		 */
		public function agency_paging_ajax()
		{
			$paged = $_REQUEST['paged'];
			$item_amount = $_REQUEST['items_amount'];

			$short_code = '[ere_agency item_amount="' . $item_amount . '" paged="' . $paged . '" show_paging="true"]';
			echo do_shortcode($short_code);
			wp_die();
		}

		public function property_set_session_view_as_ajax() {
			$view_as = $_REQUEST['view_as'];
			if (!empty( $view_as ) && in_array($view_as, array('property-list', 'property-grid'))) {
				$_SESSION['property_view_as'] = $view_as;
			}
		}

		public function agent_set_session_view_as_ajax() {
			$view_as = $_REQUEST['view_as'];
			if (!empty( $view_as ) && in_array($view_as, array('agent-list', 'agent-grid'))) {
				$_SESSION['agent_view_as'] = $view_as;
			}
		}

	}
}
new ERE_Shortcode();

