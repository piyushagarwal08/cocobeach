<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('ERE_Insert_Shortcode')) {
	/**
	 * Support insert shortcode for editor
	 * Class ERE_Insert_Shortcode
	 */
	class ERE_Insert_Shortcode
	{
		/*
		 * loader instances
		 */
		public static $instance;

		/**
		 * Init SP_Loader
		 * *******************************************************
		 */
		public static function init()
		{
			if (self::$instance == NULL) {
				self::$instance = new self();
			}
			return self::$instance;
		}
		/**
		 * construct
		 */
		public function __construct()
		{
			global $pagenow;
			if (in_array($pagenow, array('edit.php', 'post.php', 'post-new.php'))) {
				add_action('init', array($this, 'add_action'));
			}
		}

		/**
		 * add_action
		 */
		public function add_action()
		{
			add_action('admin_enqueue_scripts', array($this, 'enqueue_generator_scripts'),5);
			add_action('admin_footer', array($this, 'content_display'),12);
			add_filter('media_buttons', array($this, 'register_button'));
		}
		/**
		 * enqueue_generator_scripts
		 */
		public function enqueue_generator_scripts() {
			wp_enqueue_style(ERE_PLUGIN_PREFIX.'insert-shortcode', ERE_PLUGIN_URL . 'includes/insert-shortcode/assets/css/insert-shortcode.min.css', array(), ERE_PLUGIN_VER, 'all');
			wp_enqueue_style('chosen', ERE_PLUGIN_URL . 'includes/insert-shortcode/assets/packages/chosen/chosen.min.css');
			wp_enqueue_style('magnific-popup', ERE_PLUGIN_URL . 'includes/insert-shortcode/assets/packages/magnific-popup/magnific-popup.min.css');

			wp_enqueue_script('chosen', ERE_PLUGIN_URL . 'includes/insert-shortcode/assets/packages/chosen/chosen.jquery.min.js', array('jquery'), ERE_PLUGIN_VER, true);
			wp_enqueue_script('magnific-popup', ERE_PLUGIN_URL . 'includes/insert-shortcode/assets/packages/magnific-popup/magnific-popup.min.js', array('jquery'), ERE_PLUGIN_VER, true);
			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'insert-shortcode-popup', ERE_PLUGIN_URL . 'includes/insert-shortcode/assets/js/popup.min.js', array('jquery'), ERE_PLUGIN_VER, true);
			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'insert-shortcode-upload', ERE_PLUGIN_URL . 'includes/insert-shortcode/assets/js/upload.min.js', array('jquery'), ERE_PLUGIN_VER, true);
			wp_enqueue_script(ERE_PLUGIN_PREFIX . 'insert-shortcode', ERE_PLUGIN_URL . 'includes/insert-shortcode/assets/js/insert-shortcode.min.js', array('jquery'), ERE_PLUGIN_VER, true);
		}

		/**
		 *
		 */
		public function register_button() {
			echo '<a class="button ere-insert-shortcode-button" href="javascript:;">'. esc_html__("Add ERE Real Estate Shortcodes", 'essential-real-estate').'</a>';
		}
		/**
		 * option_element
		 * @param $name
		 * @param $attr_option
		 * @return null|string
		 */
		private function option_element($name, $attr_option) {
			$option_element = null;
			$desc = (isset($attr_option['desc']) && !empty($attr_option['desc'])) ? '<p class="des">' . $attr_option['desc'] . '</p>' : '';

			$default = isset($attr_option['default']) ? ('data-default-value="'.$attr_option['default'].'"') : '';
			$required = isset($attr_option['required']) ? $attr_option['required'] : '';
			$required_attr = '';
			if( is_array($required) && $required != array()) {
				$required_attr = 'data-required-element="'.$required['element'].'" data-required-value="'.(is_array($required['value'])? implode(',', $required['value'] ) : $required['value']).'"';
			}
			$option_element .= '<div class="option-item-wrap '.$name.'">';
			switch ($attr_option['type']) {
				case 'checkbox':
					$option_element .= '<div class="label"><label for="' . $name . '"><strong>' . $attr_option['title'] . ': </strong></label></div>
			<div class="content"> <input '.$required_attr.' name="'.$name.'" type="checkbox" class="' . $name . '" id="' . $name . '" '.$default.' />' . $desc . '</div> ';
					break;
				case 'select':
					$option_element .= '
		<div class="label"><label for="' . $name . '"><strong>' . $attr_option['title'] . ': </strong></label></div>

		<div class="content"><select id="' . $name . '" name="'.$name.'" '.$required_attr.' '.$default.'>';
					$values = $attr_option['values'];
					foreach ($values as $key => $value) {
						$option_element .= '<option value="' . $key . '">' . $value . '</option>';
					}
					$option_element .= '</select>' . $desc . '</div>';
					break;
				case 'ere_selectize':
					$option_element .= '
		<div class="label"><label for="' . $name . '"><strong>' . $attr_option['title'] . ': </strong></label></div>
		<div class="content"><select class="ere-selectize-input" '.$required_attr.' multiple="multiple" id="' . $name . '" name="'.$name.'">';
					$values = $attr_option['values'];
					foreach ($values as $k => $v) {
						$option_element .= '<option value="' . $k . '">' . $v . '</option>';
					}
					$option_element .= '</select>' . $desc . '</div>';
					break;
				case 'textarea':
					$option_element .= '
		<div class="label"><label for="' . $name . '"><strong>' . $attr_option['title'] . ': </strong></label></div>
		<div class="content"><textarea '.$required_attr.' id="'.$name.'" name="' . $name . '"></textarea> ' . $desc . '</div>';
					break;
				case 'image':
					$option_element .= '
				<div class="shortcode-dynamic-item" id="options-item" data-name="image-upload">
					<div class="label"><label><strong> ' . $attr_option['title'] . ' </strong></label></div>
					<div class="content">

					 <input type="hidden" id="options-item-id" name="'.$name.'" value="" />
			         <img class="ere-image-screenshot" id="'.$name.'" src=""/>
			         <a data-update="Select File" data-choose="Choose a File" href="javascript:void(0);" class="ere-image-upload button-secondary" rel-id="">' . __('Upload', 'essential-real-estate') . '</a>
			         <a href="javascript:void(0);" class="ere-image-upload-remove" style="display: none;">' . __('Remove Upload', 'essential-real-estate') . '</a>';

					if (!empty($desc)) $option_element .= $desc;
					$option_element .= '
					</div>
				</div>';
					break;
				case 'text':
				default:
					$option_element .= '
		<div class="label"><label for="' . $name . '"><strong>' . $attr_option['title'] . ': </strong></label></div>
		<div class="content"><input '.$default.' '.$required_attr.' id="'.$name.'" type="text" name="' . $name . '" value="" />' . $desc . '</div>';
					break;
			}
			$option_element .= '</div>';
			return $option_element;
		}

		/**
		 * add_narrow_taxonomy
		 * @param $taxonomy
		 * @param $title
		 * @return array
		 */
		private function add_narrow_taxonomy($taxonomy, $title)
		{
			$taxonomies = array();
			$taxonomy_arr = get_categories(array('taxonomy' => $taxonomy, 'hide_empty' => 1, 'orderby' => 'ASC'));
			if (is_array($taxonomy_arr)) {
				foreach ($taxonomy_arr as $tx) {
					$taxonomies[$tx->slug] = $tx->name;
				}
			}
			return array(
				'type' => 'ere_selectize',
				'title' => esc_html__( 'Narrow ', 'essential-real-estate' ) . $title,
				'desc' => __('Enter ' . $title . ' by names to narrow output.', 'essential-real-estate'),
				'values' => $taxonomies
			);
		}

		/**
		 * add_narrow_property_type
		 * @return array
		 */
		private function add_narrow_property_type()
		{
			$type = array();
			$types = get_categories(array('taxonomy' => 'property-type', 'hide_empty' => 1, 'orderby' => 'ASC'));
			if (is_array($types)) {
				foreach ($types as $st) {
					$type[$st->slug] = $st->name;
				}
			}
			return array(
				'type'        => 'select',
				'title'     => esc_html__('Narrow Type', 'essential-real-estate'),
				'values'       => $type,
				'des' => esc_html__('Enter type by names to narrow output.', 'essential-real-estate')
			);
		}

		/**
		 * content_display
		 */
		public function content_display() {
			//Image with Animation
			$ere_shortcodes['ere_property'] = array(
				'type' => 'custom',
				'title' => __('Property', 'essential-real-estate'),
				'attr' => array(
					'layout_style' => array(
						'type' => 'select',
						'title' => __('Layout Style', 'essential-real-estate'),
						'values' => array(
							"property-grid" => __("Grid", 'essential-real-estate'),
							"property-list" => __("List", 'essential-real-estate'),
							"property-zigzac" => __("Zigzac", 'essential-real-estate'),
							"property-carousel" => __("Carousel", 'essential-real-estate')
						),
						'default' => 'property-grid'
					),
					'item_amount' => array(
						'type' => 'text',
						'title' => esc_html__('Items Amount', 'essential-real-estate'),
						'default' => '6'
					),
					'columns' => array(
						'type' => 'select',
						'title' => esc_html__('Columns', 'essential-real-estate'),
						'values' => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6'
						),
						'default' => '3',
						'required' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
					),
					'items_md' => array(
						'type' => 'select',
						'title' => esc_html__('Items Desktop Small', 'essential-real-estate'),
						'des' => esc_html__('Browser Width < 1199', 'essential-real-estate'),
						'values' => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default' => '3',
						'required' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
					),
					'items_sm' => array(
						'type' => 'select',
						'title' => esc_html__('Items Tablet', 'essential-real-estate'),
						'des' => esc_html__('Browser Width < 992', 'essential-real-estate'),
						'values' => array(
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default' => '2',
						'required' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
					),
					'items_xs' => array(
						'type' => 'select',
						'title' => esc_html__('Items Tablet Small', 'essential-real-estate'),
						'des' => esc_html__('Browser Width < 768', 'essential-real-estate'),
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default' => '1',
						'required' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
					),
					'items_mb' => array(
						'type' => 'select',
						'title' => esc_html__('Items Mobile', 'essential-real-estate'),
						'des' => esc_html__('Browser Width < 480', 'essential-real-estate'),
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default' => '1',
						'required' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
					),
					'image_size' => array(
						'type' => 'text',
						'title' => esc_html__('Image Size', 'essential-real-estate'),
						'des' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space)).', 'essential-real-estate'),
						'default' => '280x180',
						'required' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
					),
					'columns_gap' => array(
						'type' => 'select',
						'title' => esc_html__('Columns Gap', 'essential-real-estate'),
						'values' => array(
							'col-gap-0' => '0px',
							'col-gap-10' => '10px',
							'col-gap-20' => '20px',
							'col-gap-30' => '30px',
						),
						'default' => 'col-gap-30',
						'required' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-carousel'))
					),
					'view_all_link' => array(
						'type' => 'text',
						'title' => esc_html__('View All Link', 'essential-real-estate'),
						'value' => ''
					),
					'show_paging' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show Paging', 'essential-real-estate'),
						'required' => array('element' => 'layout_style', 'value' => array('property-grid', 'property-list', 'property-zigzac')),
					),
					'include_heading' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Include title', 'essential-real-estate')
					),
					'heading_title' => array(
						'type' => 'text',
						'title' => esc_html__('Title', 'essential-real-estate'),
						'value' => '',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'heading_sub_title' => array(
						'type' => 'text',
						'title' => esc_html__('Sub Title', 'essential-real-estate'),
						'value' => '',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'dots' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show Pagination Control', 'essential-real-estate'),
						'required' => array('element' => 'layout_style', 'value' => 'property-carousel')
					),
					'nav' => array(
						'type' => 'checkbox',
						'default' => 'true',
						'title' => esc_html__('Show Navigation Control', 'essential-real-estate'),
						'required' => array('element' => 'layout_style', 'value' => 'property-carousel')
					),
					'move_nav' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Move Navigation Par With Top title', 'essential-real-estate'),
						'required' => array('element' => 'nav', 'value' => 'true')
					),
					'nav_position' => array(
						'type' => 'select',
						'title' => esc_html__('Navigation Position', 'essential-real-estate'),
						'values' => array(
							'' => esc_html__('Middle Center', 'essential-real-estate'),
							'top-right' => esc_html__('Top Right', 'essential-real-estate'),
							'bottom-center' => esc_html__('Bottom Center', 'essential-real-estate'),
						),
						'default' => '',
						'required' => array('element' => 'move_nav', 'value' => 'false')
					),
					'autoplay' => array(
						'type' => 'checkbox',
						'default' => 'true',
						'title' => esc_html__('Auto play', 'essential-real-estate'),
						'required' => array('element' => 'layout_style', 'value' => 'property-carousel')
					),
					'autoplaytimeout' => array(
						'type' => 'text',
						'title' => esc_html__('Autoplay Timeout', 'essential-real-estate'),
						'des' => esc_html__('Autoplay interval timeout.', 'essential-real-estate'),
						'default' => 5000,
						'required' => array('element' => 'autoplay', 'value' => 'true')
					),
					'property_type' => $this-> add_narrow_taxonomy('property-type', esc_html__( 'Type', 'essential-real-estate')),
					'property_status' =>$this-> add_narrow_taxonomy('property-status', esc_html__( 'Status', 'essential-real-estate')),
					'property_feature' =>$this-> add_narrow_taxonomy('property-feature', esc_html__( 'Feature', 'essential-real-estate')),
					'property_city' => $this->add_narrow_taxonomy('property-city', esc_html__( 'City', 'essential-real-estate')),
					'property_state' => $this->add_narrow_taxonomy('property-state', esc_html__( 'Province/State', 'essential-real-estate')),
					'property_neighborhood' => $this->add_narrow_taxonomy('property-neighborhood', esc_html__( 'Neighborhood', 'essential-real-estate')),
					'property_labels' =>$this-> add_narrow_taxonomy('property-labels', esc_html__( 'Label', 'essential-real-estate')),
                    'property_featured' => array(
                        'type' => 'checkbox',
                        'title' => esc_html__('Property Featured', 'essential-real-estate'),
                        'default' => 'false'
                    ),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_property_carousel'] = array(
				'type' => 'custom',
				'title' => __('Property Carousel', 'essential-real-estate'),
				'attr' => array(
					'item_amount' => array(
						'type' => 'text',
						'title' => esc_html__('Items Amount', 'essential-real-estate'),
						'default' => '6'
					),
					'image_size' => array(
						'type' => 'text',
						'title' => esc_html__('Image Size', 'essential-real-estate'),
						'des' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space)).', 'essential-real-estate'),
						'default' => '280x180'
					),
					'color_scheme' => array(
						'type' => 'select',
						'title' => esc_html__('Color Scheme', 'essential-real-estate'),
						'values' => array(
							'color-dark' => esc_html__('Dark', 'essential-real-estate'),
							'color-light' => esc_html__('Light', 'essential-real-estate')
						),
						'default' => 'color-dark',
					),
					'include_heading' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Include title', 'essential-real-estate')
					),
					'heading_title' => array(
						'type' => 'text',
						'title' => esc_html__('Title', 'essential-real-estate'),
						'value' => '',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'heading_sub_title' => array(
						'type' => 'text',
						'title' => esc_html__('Sub Title', 'essential-real-estate'),
						'value' => '',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'property_type' =>$this-> add_narrow_taxonomy('property-type', esc_html__( 'Type', 'essential-real-estate')),
					'property_status' =>$this-> add_narrow_taxonomy('property-status', esc_html__( 'Status', 'essential-real-estate')),
					'property_feature' =>$this-> add_narrow_taxonomy('property-feature', esc_html__( 'Feature', 'essential-real-estate')),
					'property_city' => $this->add_narrow_taxonomy('property-city', esc_html__( 'City', 'essential-real-estate')),
					'property_state' => $this->add_narrow_taxonomy('property-state', esc_html__( 'Province/State', 'essential-real-estate')),
					'property_neighborhood' =>$this-> add_narrow_taxonomy('property-neighborhood', esc_html__( 'Neighborhood', 'essential-real-estate')),
					'property_labels' => $this->add_narrow_taxonomy('property-labels', esc_html__( 'Label', 'essential-real-estate')),
                    'property_featured' => array(
                        'type' => 'checkbox',
                        'title' => esc_html__('Property Featured', 'essential-real-estate'),
                        'default' => 'false'
                    ),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_property_slider'] = array(
				'type' => 'custom',
				'title' => __('Property Slider', 'essential-real-estate'),
				'attr' => array(
					'layout_style' => array(
						'type' => 'select',
						'title' => esc_html__('Layout Style', 'essential-real-estate'),
						'values' => array(
							'navigation-middle' => esc_html__('Navigation Middle', 'essential-real-estate'),
							'pagination-image' => esc_html__('Pagination as Image', 'essential-real-estate')
						),
						'default' => 'navigation-middle'
					),
					'item_amount' => array(
						'type' => 'text',
						'title' => esc_html__('Items Amount', 'essential-real-estate'),
						'default' => '6'
					),
					'image_size' => array(
						'type' => 'text',
						'title' => esc_html__('Image Size', 'essential-real-estate'),
						'des' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 280x180, 330x180, 380x180 (Not Include Unit, Space)).', 'essential-real-estate'),
						'default' => '280x180'
					),
					'property_type' => $this->add_narrow_taxonomy('property-type', esc_html__( 'Type', 'essential-real-estate')),
					'property_status' =>$this-> add_narrow_taxonomy('property-status', esc_html__( 'Status', 'essential-real-estate')),
					'property_feature' => $this->add_narrow_taxonomy('property-feature', esc_html__( 'Feature', 'essential-real-estate')),
					'property_city' => $this->add_narrow_taxonomy('property-city', esc_html__( 'City', 'essential-real-estate')),
					'property_state' => $this->add_narrow_taxonomy('property-state', esc_html__( 'Province/State', 'essential-real-estate')),
					'property_neighborhood' => $this->add_narrow_taxonomy('property-neighborhood', esc_html__( 'Neighborhood', 'essential-real-estate')),
					'property_labels' => $this->add_narrow_taxonomy('property-labels', esc_html__( 'Label', 'essential-real-estate')),
                    'property_featured' => array(
                        'type' => 'checkbox',
                        'title' => esc_html__('Property Featured', 'essential-real-estate'),
                        'default' => 'false'
                    ),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_property_gallery'] = array(
				'type' => 'custom',
				'title' => __('Property Gallery', 'essential-real-estate'),
				'attr' => array(
					'is_carousel' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Display Carousel?', 'essential-real-estate'),
					),
					'color_scheme' => array(
						'type' => 'select',
						'title' => esc_html__('Color Scheme', 'essential-real-estate'),
						'values' => array(
							'color-dark' => esc_html__('Dark', 'essential-real-estate'),
							'color-light' => esc_html__('Light', 'essential-real-estate')
						)
					),
					'category_filter' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Category Filter', 'essential-real-estate')
					),
					'filter_style' => array(
						'type' => 'select',
						'title' => esc_html__('Filter Style', 'essential-real-estate'),
						'values' => array(
							'filter-isotope' => esc_html__('Isotope', 'essential-real-estate'),
							'filter-ajax' => esc_html__('Ajax', 'essential-real-estate')
						),
						'des' => 'Not applicable for carousel',
						'default' => 'filter-isotope'
					),
					'include_heading' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Include title', 'essential-real-estate'),
						'required' => array('element' => 'category_filter', 'value' => 'true')
					),
					'heading_title' => array(
						'type' => 'text',
						'title' => esc_html__('Title', 'essential-real-estate'),
						'value' => '',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'heading_sub_title' => array(
						'type' => 'text',
						'title' => esc_html__('Sub Title', 'essential-real-estate'),
						'value' => '',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'item_amount' => array(
						'type' => 'text',
						'title' => esc_html__('Items Amount', 'essential-real-estate'),
						'default' => '6'
					),
					'columns' => array(
						'type' => 'select',
						'title' => esc_html__('Columns', 'essential-real-estate'),
						'values' => array(
							'2' => '2',
							'3' => '3',
							'4' => '4'
						),
						'default' => '4'
					),
					'columns_gap' => array(
						'type' => 'select',
						'title' => esc_html__('Columns Gap', 'essential-real-estate'),
						'values' => array(
							'col-gap-0' => '0px',
							'col-gap-10' => '10px',
							'col-gap-20' => '20px',
							'col-gap-30' => '30px',
							'default' => 'col-gap-0'
						)
					),
					'dots' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show Pagination Control', 'essential-real-estate'),
						'required' => array('element' => 'is_carousel', 'value' => 'true')
					),
					'nav' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show Navigation Control', 'essential-real-estate'),
						'required' => array('element' => 'is_carousel', 'value' => 'true')
					),
					'autoplay' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Auto play', 'essential-real-estate'),
						'required' => array('element' => 'is_carousel', 'value' => 'true'),
						'default' => 'true'
					),
					'autoplaytimeout' => array(
						'type' => 'text',
						'title' => esc_html__('Autoplay Timeout', 'essential-real-estate'),
						'default' => 5000,
						'required' => array('element' => 'autoplay', 'value' => 'true')
					),
					'property_type' => $this->add_narrow_taxonomy('property-type', esc_html__( 'Type', 'essential-real-estate')),
					'property_status' =>$this-> add_narrow_taxonomy('property-status', esc_html__( 'Status', 'essential-real-estate')),
					'property_feature' => $this->add_narrow_taxonomy('property-feature', esc_html__( 'Feature', 'essential-real-estate')),
					'property_city' => $this->add_narrow_taxonomy('property-city', esc_html__( 'City', 'essential-real-estate')),
					'property_state' => $this->add_narrow_taxonomy('property-state', esc_html__( 'Province/State', 'essential-real-estate')),
					'property_neighborhood' => $this->add_narrow_taxonomy('property-neighborhood', esc_html__( 'Neighborhood', 'essential-real-estate')),
					'property_labels' => $this->add_narrow_taxonomy('property-labels', esc_html__( 'Label', 'essential-real-estate')),
                    'property_featured' => array(
                        'type' => 'checkbox',
                        'title' => esc_html__('Property Featured', 'essential-real-estate'),
                        'default' => 'false'
                    ),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_property_featured'] = array(
				'type' => 'custom',
				'title' => __('Property Featured', 'essential-real-estate'),
				'attr' => array(
					'layout_style' => array(
						'type' => 'select',
						'title' => esc_html__('Layout Style', 'essential-real-estate'),
						'values' => array(
							'property-list-two-columns' => esc_html__('List Two Columns', 'essential-real-estate'),
							'property-cities-filter' => esc_html__('Cities Filter', 'essential-real-estate'),
							'property-single-carousel' => esc_html__('Single Carousel', 'essential-real-estate'),
							'property-sync-carousel' => esc_html__('Sync Carousel', 'essential-real-estate')
						),
						'default' => 'property-list-two-columns',
						'des' => esc_html__('Select Layout Style.', 'essential-real-estate')
					),
					'color_scheme' => array(
						'type' => 'select',
						'title' => esc_html__('Color Scheme', 'essential-real-estate'),
						'values' => array(
							'color-dark' => esc_html__('Dark', 'essential-real-estate'),
							'color-light' => esc_html__('Light', 'essential-real-estate')
						),
						'default' => 'color-dark'
					),
					'item_amount' => array(
						'type' => 'text',
						'title' => esc_html__('Items Amount', 'essential-real-estate'),
						'default' => '6'
					),
					'include_heading' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Include title', 'essential-real-estate')
					),
					'heading_title' => array(
						'type' => 'text',
						'title' => esc_html__('Title', 'essential-real-estate'),
						'value' => '',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'heading_sub_title' => array(
						'type' => 'text',
						'title' => esc_html__('Sub Title', 'essential-real-estate'),
						'value' => '',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'heading_text_align' => array(
						'type' => 'select',
						'title' => esc_html__('Text Align', 'essential-real-estate'),
						'des' => esc_html__('Select title alignment.', 'essential-real-estate'),
						'values' => array(
							'text-left' => esc_html__('Left', 'essential-real-estate'),
							'text-center' => esc_html__('Center', 'essential-real-estate'),
							'text-right' => esc_html__('Right', 'essential-real-estate'),
						),
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'property_type' =>$this-> add_narrow_taxonomy('property-type', esc_html__( 'Type', 'essential-real-estate')),
					'property_status' =>$this-> add_narrow_taxonomy('property-status', esc_html__( 'Status', 'essential-real-estate')),
					'property_feature' => $this->add_narrow_taxonomy('property-feature', esc_html__( 'Feature', 'essential-real-estate')),
					'property_city' => $this->add_narrow_taxonomy('property-city', esc_html__( 'City', 'essential-real-estate')),
					'property_state' => $this->add_narrow_taxonomy('property-state', esc_html__( 'Province/State', 'essential-real-estate')),
					'property_neighborhood' =>$this-> add_narrow_taxonomy('property-neighborhood', esc_html__( 'Neighborhood', 'essential-real-estate')),
					'property_labels' => $this->add_narrow_taxonomy('property-labels', esc_html__( 'Label', 'essential-real-estate')),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_property_type'] = array(
				'type' => 'custom',
				'title' => __('Property Type', 'essential-real-estate'),
				'attr' => array(
					'property-type' => $this->add_narrow_property_type(),
					'type_image' => array(
						'type' => 'image',
						'title' => esc_html__('Upload Type Image', 'essential-real-estate'),
						'value' => '',
						'des' => esc_html__('Upload the custom image.', 'essential-real-estate')
					),
					'image_size' => array(
						'type' => 'text',
						'title' => esc_html__('Image Size', 'essential-real-estate'),
						'value' => 'full',
						'des' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example: 200x100 (Not Include Unit, Space)).', 'essential-real-estate')
					),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_property_map'] = array(
				'type' => 'custom',
				'title' => __('Property Map', 'essential-real-estate'),
				'attr' => array(
					'map_style' => array(
						'type' => 'select',
						'title' => esc_html__('Map Style', 'essential-real-estate'),
						'values' => array(
							'normal' => esc_html__( 'Normal', 'essential-real-estate' ),
							'property' => esc_html__( 'Single Property', 'essential-real-estate' )
						),
						'default' => 'property'
					),
					'icon' => array(
						'type' => 'image',
						'title' => esc_html__( 'Marker Icon', 'essential-real-estate' ),
						'value' => '',
						'des' => esc_html__( 'Choose an image from media library.', 'essential-real-estate' ),
					),
					'lat' => array(
						'type' => 'text',
						'title' => esc_html__('Latitude ', 'essential-real-estate'),
						'value' => '',
						'required' => array('element'=>'map_style', 'value'=>'normal')
					),
					'lng' => array(
						'type' => 'text',
						'title' => esc_html__('Longitude ', 'essential-real-estate'),
						'value' => '',
						'required' => array('element'=>'map_style', 'value'=>'normal')
					),
					'property_id' => array(
						'title' => esc_html__( 'Property ID', 'essential-real-estate' ),
						'type' => 'text',
						'value' => '',
						'required' => array('element'=>'map_style', 'value'=>'property')
					),
					'map_height' => array(
						'type' => 'text',
						'title' => esc_html__('Map height (px or %)', 'essential-real-estate'),
						'default' => '500px'
					),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_property_search'] = array(
				'type' => 'custom',
				'title' => __('Property Search', 'essential-real-estate'),
				'attr' => array(
					'search_styles' => array(
						'type' => 'select',
						'title' => esc_html__('Search Form Style','essential-real-estate'),
						'des' => __('Select one in styles below for search form. Almost, you should use layout full-width for search form to can display it best', 'essential-real-estate'),
						'values' => array(
							'style-default' => esc_html__('Form Default ','essential-real-estate'),
							'style-default-small' => esc_html__('Form Default Small ','essential-real-estate'),
							'style-mini-line' => esc_html__('Mini Inline','essential-real-estate'),
							'style-absolute' => esc_html__('Form Absolute Map ','essential-real-estate'),
							'style-vertical' => esc_html__('Map Vertical','essential-real-estate')
						),
					),
					'title_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Title Enable','essential-real-estate'),
						'des' => __('Check to show location title field.', 'essential-real-estate'),
						'default' => 'true',
					),
					'location_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Location Enable','essential-real-estate'),
						'des' => __('Check to show address search field.', 'essential-real-estate'),
						'default' => 'true',
					),
					'countries_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Countries', 'essential-real-estate'),
					),
					'states_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Province/States', 'essential-real-estate'),
					),
					'cities_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('City Enable','essential-real-estate'),
						'des' => __('Check to show cities search field.', 'essential-real-estate')
					),
					'neighborhoods_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Neighborhoods Enable','essential-real-estate'),
						'des' => __('Check to show neighborhoods search field.', 'essential-real-estate')
					),
					'types_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Type Enable','essential-real-estate'),
						'des' => __('Check to show types search field.', 'essential-real-estate')
					),
					'status_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Status Enable','essential-real-estate'),
						'des' => __('Check to show status search field.', 'essential-real-estate')
					),
					'number_bedrooms_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Bedrooms Enable','essential-real-estate'),
						'des' => __('Check to show bedrooms search field.', 'essential-real-estate')
					),
					'number_bathrooms_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Bathrooms Enable','essential-real-estate'),
						'des' => __('Check to show bathroom search field.', 'essential-real-estate')
					),
					'price_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Price Enable','essential-real-estate'),
						'des' => __('Check to show slider filter price properties.', 'essential-real-estate')
					),
					'area_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Area Enable','essential-real-estate'),
						'des' => __('Check to show slider filter area properties.', 'essential-real-estate')
					),
					'map_search_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Map Search  Enable', 'essential-real-estate'),
						'des' => __('Show map and search properties with form and show result by map', 'essential-real-estate'),
						'default' => 'true',
						'required' => array( 'element' => 'search_styles', 'value' => array('style-mini-line', 'style-default','style-default-small'))
					),
					'show_status_tab' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show status tab', 'essential-real-estate'),
						'des' => __('Select property status field like tab', 'essential-real-estate'),
						'default' => 'true',
						'required' => array( 'element' => 'search_styles', 'value' => array('style-default', 'style-default-small','style-absolute','style-vertical'))
					),
					'advanced_search_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Advanced Option Enable', 'essential-real-estate'),
						'des' => __('Select to show advanced search and other fields.', 'essential-real-estate'),
						'required' => array( 'element' => 'search_styles', 'value' => array('style-mini-line', 'style-default','style-default-small'))
					),
					'year_built_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Year Built', 'essential-real-estate'),
						'required' => array( 'element' => 'advanced_search_enable', 'value' => array('true'))
					),
					'labels_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Lables', 'essential-real-estate'),
						'required' => array( 'element' => 'advanced_search_enable', 'value' => array('true'))
					),
					'number_garage_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Number Garage', 'essential-real-estate'),
						'required' => array( 'element' => 'advanced_search_enable', 'value' => array('true'))
					),
					'garage_area_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Garage Area', 'essential-real-estate'),
						'required' => array( 'element' => 'advanced_search_enable', 'value' => array('true'))
					),
					'land_area_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Land Area', 'essential-real-estate'),
						'required' => array( 'element' => 'advanced_search_enable', 'value' => array('true'))
					),
					'property_identity_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Property ID', 'essential-real-estate'),
						'required' => array( 'element' => 'advanced_search_enable', 'value' => array('true'))
					),
					'other_features_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Other Features', 'essential-real-estate'),
						'required' => array( 'element' => 'advanced_search_enable', 'value' => array('true'))
					),
					'color_scheme' => array(
						'type' => 'select',
						'title' => esc_html__('Color Scheme','essential-real-estate'),
						'des' => __('Select color scheme for form search', 'essential-real-estate'),
						'values' => array(
							'color-dark' => esc_html__('Dark','essential-real-estate'),
							'color-light' => esc_html__('Light','essential-real-estate')
						),
					),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					),
				)
			);
			$ere_shortcodes['ere_property_mini_search'] = array(
				'type' => 'custom',
				'title' => __('Property Mini Search', 'essential-real-estate'),
				'attr' => array(
					'status_enable' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Status Enable','essential-real-estate'),
						'des' => __('Check to show status search field.', 'essential-real-estate')
					),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					),
				)
			);
			$ere_shortcodes['ere_agent'] = array(
				'type' => 'custom',
				'title' => __('Agent', 'essential-real-estate'),
				'attr' => array(
					'agencies' => $this->add_narrow_taxonomy('agencies', esc_html__( 'Agencies', 'essential-real-estate' )),
					'layout_style' => array(
						'type' => 'select',
						'title' => esc_html__('Layout Style', 'essential-real-estate'),
						'values' => array(
							'agent-slider' => esc_html__('Carousel', 'essential-real-estate'),
							'agent-grid' => esc_html__('Grid', 'essential-real-estate'),
							'agent-list' => esc_html__('List', 'essential-real-estate')
						),
						'default' => 'agent-slider'
					),
					'item_amount' => array(
						'type' => 'text',
						'title' => esc_html__('Items Amount', 'essential-real-estate'),
						'default' => '12'
					),
					'items' => array(
						'type' => 'select',
						'title' => esc_html__('Columns', 'essential-real-estate'),
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6'
						),
						'default' => '4',
						'required' => array( 'element' => 'layout_style', 'value' => array('agent-grid','agent-slider'))
					),
					'image_size' => array(
						'type' => 'text',
						'title' => esc_html__('Image Size', 'essential-real-estate'),
						'des' => esc_html__('Enter image size ("thumbnail" or "full"). Alternatively enter size in pixels (Example : 270x340 (Not Include Unit, Space)).', 'essential-real-estate'),
						'default' => '270x340'
					),
					'show_paging' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show Paging', 'essential-real-estate'),
						'required' => array( 'element' => 'layout_style', 'value' => array('agent-grid', 'agent-list') )
					),
					'dots' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show pagination control', 'essential-real-estate'),
						'required' => array('element' => 'layout_style', 'value' => 'agent-slider')
					),
					'nav' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show navigation control', 'essential-real-estate'),
						'required' => array('element' => 'layout_style', 'value' => 'agent-slider'),
						'default' => 'true'
					),
					'nav_position' => array(
						'type' => 'select',
						'title' => esc_html__('Navigation Position', 'essential-real-estate'),
						'values' => array(
							'center' => esc_html__('Center', 'essential-real-estate'),
							'top-right' => esc_html__('Top Right', 'essential-real-estate')
						),
						'default' => 'center',
						'required' => array('element' => 'nav', 'value' => 'true')
					),
					'autoplay' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Auto play', 'essential-real-estate'),
						'required' => array('element' => 'layout_style', 'value' => 'agent-slider'),
						'default' => 'true'
					),
					'autoplaytimeout' => array(
						'type' => 'text',
						'title' => esc_html__('Autoplay Timeout', 'essential-real-estate'),
						'des' => esc_html__('Autoplay interval timeout.', 'essential-real-estate'),
						'default' => 5000,
						'required' => array('element' => 'autoplay', 'value' => 'true')
					),
					'items_md' => array(
						'type' => 'select',
						'title' => esc_html__('Items Desktop Small', 'essential-real-estate'),
						'des' => esc_html__('Browser Width < 1199', 'essential-real-estate'),
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default' => '3',
						'required' => array( 'element' => 'layout_style', 'value' => array('agent-grid','agent-slider'))
					),
					'items_sm' => array(
						'type' => 'select',
						'title' => esc_html__('Items Tablet', 'essential-real-estate'),
						'des' => esc_html__('Browser Width < 992', 'essential-real-estate'),
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default' => '2',
						'required' => array( 'element' => 'layout_style', 'value' => array('agent-grid','agent-slider'))
					),
					'items_xs' => array(
						'type' => 'select',
						'title' => esc_html__('Items Tablet Small', 'essential-real-estate'),
						'des' => esc_html__('Browser Width < 768', 'essential-real-estate'),
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default' => '2',
						'required' => array( 'element' => 'layout_style', 'value' => array('agent-grid','agent-slider'))
					),
					'items_mb' => array(
						'type' => 'select',
						'title' => esc_html__('Items Mobile', 'essential-real-estate'),
						'des' => esc_html__('Browser Width < 480', 'essential-real-estate'),
						'values' => array(
							'1' => '1',
							'2' => '2',
							'3' => '3',
							'4' => '4',
							'5' => '5',
							'6' => '6',
						),
						'default' => '1',
						'required' => array( 'element' => 'layout_style', 'value' => array('agent-grid','agent-slider'))
					),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_agency'] = array(
				'type' => 'custom',
				'title' => __('Agency', 'essential-real-estate'),
				'attr' => array(
					'item_amount' => array(
						'type' => 'text',
						'title' => esc_html__('Items Amount', 'essential-real-estate'),
						'default' => '6'
					),
					'show_paging' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Show Paging', 'essential-real-estate')
					),
					'include_heading' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Include title', 'essential-real-estate')
					),
					'heading_title' => array(
						'type' => 'text',
						'title' => esc_html__('Title', 'essential-real-estate'),
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'heading_sub_title' => array(
						'type' => 'text',
						'title' => esc_html__('Sub Title', 'essential-real-estate'),
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'heading_text_align' => array(
						'type' => 'select',
						'title' => esc_html__('Text Align', 'essential-real-estate'),
						'des' => esc_html__('Select title alignment.', 'essential-real-estate'),
						'values' => array(
							'text-left' => esc_html__('Left', 'essential-real-estate'),
							'text-center' => esc_html__('Center', 'essential-real-estate'),
							'text-right' => esc_html__('Right', 'essential-real-estate')
						),
						'default' => 'text-left',
						'required' => array('element' => 'include_heading', 'value' => 'true')
					),
					'el_class' => array(
						'type' => 'text',
						'title' => __('Extra class name', 'essential-real-estate'),
						'des' => __('Style particular content element differently - add a class name and refer to it in custom CSS.', 'essential-real-estate'),
					)
				)
			);
			$ere_shortcodes['ere_login'] = array(
				'type' => 'custom',
				'title' => __('Login', 'essential-real-estate')
			);
			$ere_shortcodes['ere_register'] = array(
				'type' => 'custom',
				'title' => __('Register', 'essential-real-estate')
			);
			$ere_shortcodes['ere_profile'] = array(
				'type' => 'custom',
				'title' => __('Profile', 'essential-real-estate')
			);
			$ere_shortcodes['ere_reset_password'] = array(
				'type' => 'custom',
				'title' => __('Reset Password', 'essential-real-estate')
			);
			$ere_shortcodes['ere_my_invoices'] = array(
				'type' => 'custom',
				'title' => __('My Invoice', 'essential-real-estate')
			);
			$ere_shortcodes['ere_package'] = array(
				'type' => 'custom',
				'title' => __('Package', 'essential-real-estate')
			);
			$ere_shortcodes['ere_my_properties'] = array(
				'type' => 'custom',
				'title' => __('My Properties', 'essential-real-estate')
			);
			$ere_shortcodes['ere_submit_property'] = array(
				'type' => 'custom',
				'title' => __('Submit Property', 'essential-real-estate')
			);
			$ere_shortcodes['ere_my_favorites'] = array(
				'type' => 'custom',
				'title' => __('My Favorites', 'essential-real-estate')
			);
			$ere_shortcodes['ere_advanced_search'] = array(
				'type' => 'custom',
				'title' => __('Advanced Search', 'essential-real-estate')
			);
			$ere_shortcodes['ere_compare'] = array(
				'type' => 'custom',
				'title' => __('Compare', 'essential-real-estate')
			);
			$ere_shortcodes['ere_my_save_search'] = array(
				'type' => 'custom',
				'title' => __('My Saved Searches', 'essential-real-estate')
			);

			//Shortcode html
			$html_options = null;

			$shortcode_html = '
		<div id="ere-input-shortcode" class="mfp-hide mfp-with-anim">
			<div class="shortcode-content">
				<div id="ere-sc-header">
					<div class="label"><strong>' . __('ERE Shortcodes', 'essential-real-estate') . '</strong></div>
					<div class="content">
					<select id="ere-shortcodes" data-placeholder="' . __("Choose a shortcode", 'essential-real-estate') . '">
				    <option></option>';
			foreach ($ere_shortcodes as $shortcode => $options) {
				if (strpos($shortcode, 'header') !== false) {
					$shortcode_html .= '<optgroup label="' . $options['title'] . '">';
				} else {
					$shortcode_html .= '<option value="' . $shortcode . '">' . $options['title'] . '</option>';
					$html_options .= '<div class="shortcode-options" id="options-' . $shortcode . '" data-name="' . $shortcode . '" data-type="' . $options['type'] . '">';

					if (!empty($options['attr'])) {
						$index = 0;
						foreach ($options['attr'] as $name => $attr_option) {
							if($index % 2 == 0){
								$html_options .= '<div class="two-option-wrap">';
							}
							$html_options .= $this-> option_element($name, $attr_option);
							$index++;
							if($index % 2 == 0 || $index >= count($options['attr'])){
								$html_options .= '</div>';
								$html_options .= '<div class="clearfix"></div>';
							}
						}
					}
					$html_options .= '</div>';
				}
			}
			$shortcode_html .= '
				</select>
			</div>
			<div class="clearfix"></div>
		</div>';
			echo $shortcode_html . $html_options;
			echo '<a class="btn" id="insert-shortcode">'. esc_html__("Insert Shortcode", "essential-real-estate").'</a>
		</div>
	</div>';
		}
	}
	/**
	 * Instantiate the ERE_Insert_Shortcode class.
	 */
	ERE_Insert_Shortcode::init();
}