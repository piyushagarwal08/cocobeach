<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if (!class_exists('ERE_Package_Admin')) {
    /**
     * Class ERE_Package_Admin
     */
    class ERE_Package_Admin
    {
        /**
         * Modify package slug
         * @param $existing_slug
         * @return string
         */
        public function modify_package_slug($existing_slug)
        {
            $package_url_slug = ere_get_option('package_url_slug');
            if ($package_url_slug) {
                return $package_url_slug;
            }
            return $existing_slug;
        }

        /**
         * Register custom column titles
         * @param $columns
         * @return array
         */
        public function register_custom_column_titles($columns)
        {
            $columns['cb'] = "<input type=\"checkbox\" />";
            $columns['title'] = esc_html__('Name', 'essential-real-estate');
            $columns['price'] = esc_html__('Price', 'essential-real-estate');
            $columns['featured'] = '<span data-tip="'.  esc_html__('Featured?', 'essential-real-estate') .'" class="tips dashicons dashicons-star-filled"></span>';
            return $columns;
        }

        /**
         * Display custom column
         * @param $column
         */
        public function display_custom_column($column)
        {
            global $post;
            switch ($column) {
                case 'price':
                    $package_free = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'package_free', true);
                    if($package_free==1)
                    {
                        esc_html_e('Free', 'essential-real-estate');
                    }
                    else
                    {
                        $package_price = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'package_price', true);
                        if ($package_price > 0) {
                            echo ere_get_format_money($package_price);
                        } else {
                            esc_html_e('Free', 'essential-real-estate');
                        }
                    }

                    break;
                case 'featured':
                    $featured = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'package_featured', true);
                    if ($featured == 1) {
                        echo '<i data-tip="'.  esc_html__('Featured', 'essential-real-estate') .'" class="tips accent-color dashicons dashicons-star-filled"></i>';
                    } else {
                        echo '<i data-tip="'.  esc_html__('Not Feature', 'essential-real-estate') .'" class="tips dashicons dashicons-star-empty"></i>';
                    }
                    break;
            }
        }
    }
}