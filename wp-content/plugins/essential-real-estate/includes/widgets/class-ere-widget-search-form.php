<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Widget_Search_Form')) {

    class ERE_Widget_Search_Form extends ERE_Widget
    {
        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->widget_cssclass = 'ere_widget_search_form';
            $this->widget_description = esc_html__("Display the search form.", 'essential-real-estate');
            $this->widget_id = 'ere_widget_search_form';
            $this->widget_name = esc_html__('ERE Search Form', 'essential-real-estate');
            $this->settings = array(
                'title' => array(
                    'type' => 'text',
                    'std' => esc_html__('Search Form', 'essential-real-estate'),
                    'label' => esc_html__('Title:', 'essential-real-estate')
                ),
                'location' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Location', 'essential-real-estate')
                ),
                'city' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Cities', 'essential-real-estate')
                ),
                'status' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Status', 'essential-real-estate')
                ),
                'type' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Types', 'essential-real-estate')
                ),
                'number_bedroom' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Number Bedrooms', 'essential-real-estate')
                ),
                'number_bathroom' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Number Bathrooms', 'essential-real-estate')
                ),
                'slider_filter_price' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Filter Price', 'essential-real-estate')
                ),
                'slider_filter_area' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Filter Area', 'essential-real-estate')
                ),
                'new_tab' => array(
                    'type' => 'checkbox',
                    'std' => true,
                    'label' => esc_html__('Open new tab after submit.', 'essential-real-estate')
                ),
                'text_submit' => array(
                    'type' => 'text',
                    'std' => esc_html__('Go Search', 'essential-real-estate'),
                    'label' => esc_html__('Text button submit:', 'essential-real-estate')
                ),
            );

            parent::__construct();
        }
        /**
         * Output widget
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance)
        {
            $this->widget_start($args, $instance);

            echo ere_get_template_html('widgets/search-form/search-form.php', array('args' => $args, 'instance' => $instance));

            $this->widget_end($args);
        }
    }
}