<?php
/**
 * Plugin Name: ERE Colors
 * Plugin URI: http://plugins.g5plus.net/ere/ere-colors
 * Description: Customize the colors of the Essential Real Estate plugin
 * Version: 1.0
 * Author: G5Theme
 * Author URI: http://themeforest.net/user/g5theme
 * Tags: Essential Real Estate, Colors, Customizer, Custom Colors, Theme Colors
 * Text Domain: essential-real-estate-colors
 * License: GPL
 *
 * =====================================================================================
 * Copyright (C) 2017 G5Theme
 * =====================================================================================
 */
function ere_colors_load_textdomain()
{
    load_plugin_textdomain('ere-colors');
}

add_action('plugins_loaded', 'ere_colors_load_textdomain');
/**
 * @return array
 */
function ere_colors_register_option()
{
    return array(
        array(
            'id' => 'ere_colors_option',
            'title' => esc_html__('Colors', 'ere-colors'),
            'icon' => 'dashicons dashicons-art',
            'fields' => array(
                array(
                    'id' => 'accent_color',
                    'type' => 'color',
                    'alpha' => true,
                    'title' => esc_html__('Accent Color', 'ere-colors'),
                    'default' => '#fb6a19',
                    'validate' => 'color',
                ),
                array(
                    'id' => 'contrast_color',
                    'type' => 'color',
                    'title' => esc_html__('Contrast Color', 'ere-colors'),
                    'default' => '#222',
                    'validate' => 'color',
                    'alpha' => true,
                ),
                array(
                    'id' => 'text_color',
                    'type' => 'color',
                    'title' => esc_html__('Text Color', 'ere-colors'),
                    'default' => '#aaa',
                    'validate' => 'color',
                    'alpha' => true,
                ),
            )
        )
    );
}

add_filter('ere_register_options_config_bottom', 'ere_colors_register_option', 10);

require( 'color-patterns.php' );
/**
 * @return string
 */
function ere_colors_generate_css()
{
    return ere_colors_accent_generate_css() . ere_colors_contrast_generate_css() . ere_colors_text_generate_css();
}

/**
 * ere_colors_print_output
 */
function ere_colors_print_output()
{ ?>
    <style id="ere_colors" type="text/css">
        <?php echo ere_colors_generate_css();?>
    </style>
    <?php
}

add_action('wp_print_styles', 'ere_colors_print_output');