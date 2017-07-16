<?php

/**
 * The file that defines the core plugin class
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if (!class_exists('Essential_Real_Estate')) {
    /**
     * The core plugin class
     * Class Essential_Real_Estate
     */
    class Essential_Real_Estate
    {
        /**
         * The loader that's responsible for maintaining and registering all hooks that power
         */
        protected $loader;
        protected $forms;
        /**
         * Instance variable for singleton pattern
         */
        private static $instance = null;
        /**
         * Return class instance
         * @return Essential_Real_Estate|null
         */
        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }
        /**
         * Define the core functionality of the plugin
         */
        private function __construct()
        {
            $this->include_library();
            $this->set_locale();
            $this->admin_hooks();
            $this->public_hooks();
        }
        /**
         * Load the required dependencies for this plugin
         */
        private function include_library()
        {
            if (!is_admin()) {
                // wp_handle_upload
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                // wp_generate_attachment_metadata
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // image_add_caption
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                // submit_button
                require_once(ABSPATH . 'wp-admin/includes/template.php');
            }
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-admin-texts.php';
            // add_screen_option
            require_once(ABSPATH . 'wp-admin/includes/screen.php');
            /**
             * The class responsible for orchestrating the actions and filters of the
             * core plugin.
             */
            require_once ERE_PLUGIN_DIR . 'includes/class-ere-loader.php';
            $this->loader = new ERE_Loader();
            require_once ERE_PLUGIN_DIR . 'includes/ere-core-functions.php';
            /**
             * The class responsible for defining internationalization functionality
             * of the plugin.
             */
            require_once ERE_PLUGIN_DIR . 'includes/class-ere-i18n.php';
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-setup-admin.php';
            /**
             * The class responsible for defining all actions that occur in the admin area.
             */
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-admin.php';
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-setup-metaboxes.php';
            /**
             * The class responsible for providing property custom post type and related stuff.
             */
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-property-admin.php';
            /**
             * The class responsible for providing agent custom post type and related stuff.
             */
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-agent-admin.php';
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-package-admin.php';
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-user-package-admin.php';
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-invoice-admin.php';
            require_once ERE_PLUGIN_DIR . 'admin/class-ere-trans-log-admin.php';
            require_once ERE_PLUGIN_DIR . 'public/class-ere-public.php';
            /**
             * The class defining Widget
             */
            require_once ERE_PLUGIN_DIR . 'includes/widgets/class-ere-register-widget.php';
            /**
             * The class include all Shortcodes
             */
            require_once ERE_PLUGIN_DIR . 'includes/vc-params/ere-vc-params.php';
            require_once ERE_PLUGIN_DIR . 'includes/shortcodes/class-ere-shortcodes.php';
            require_once ERE_PLUGIN_DIR . 'includes/shortcodes/class-ere-vcmap.php';
            if(ere_get_option('enable_add_shortcode_tool', '1')=='1')
            {
                require_once ERE_PLUGIN_DIR . 'includes/insert-shortcode/class-ere-insert-shortcode.php';
            }
            /**
             * The class responsible for defining all actions that occur in the public-facing
             * side of the site.
             */
            require_once ERE_PLUGIN_DIR . 'includes/forms/class-ere-forms.php';

            require_once ERE_PLUGIN_DIR . 'includes/class-ere-schedule.php';

            $this->forms = new ERE_Forms();
        }
        /**
         * Define the locale for this plugin for internationalization.
         */
        private function set_locale()
        {
            $plugin_i18n = new ERE_i18n();
            $plugin_i18n->set_domain(ERE_PLUGIN_NAME);
            $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
        }

        /**
         * Register all of the hooks related to the admin area functionality
         */
        private function admin_hooks()
        {
            $plugin_texts= new ERE_Admin_Texts();
            $this->loader->add_action('current_screen', $plugin_texts, 'add_hooks');

            $plugin_admin = new ERE_Admin();

            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
            $this->loader->add_action('init', $plugin_admin, 'register_post_status');

            $register_widgets = new ERE_Register_Widgets();
            $this->loader->add_action('widgets_init', $register_widgets, 'register_widgets');

            $this->loader->add_filter('gsf_register_post_type', $plugin_admin, 'register_post_type');
            $this->loader->add_filter('gsf_meta_box_config', $plugin_admin, 'register_meta_boxes');
            $this->loader->add_filter('gsf_register_taxonomy', $plugin_admin, 'register_taxonomy');
            $this->loader->add_filter('gsf_term_meta_config', $plugin_admin, 'register_term_meta');
            $this->loader->add_filter('gsf_option_config', $plugin_admin, 'register_options_config');
            $this->loader->add_filter('gsf_image_default_dir', $plugin_admin, 'image_default_dir');

            // Property Post Type
            $property_admin = new ERE_Property_Admin();
            $this->loader->add_action('restrict_manage_posts', $property_admin, 'filter_restrict_manage_property');
            $this->loader->add_filter('parse_query', $property_admin, 'property_filter');
            $this->loader->add_action('admin_init', $property_admin, 'approve_property');
            $this->loader->add_action('admin_init', $property_admin, 'expire_property');
            $this->loader->add_action('admin_init', $property_admin, 'hidden_property');
            $this->loader->add_action('admin_init', $property_admin, 'show_property');
            // Filters to modify URL slugs
            $this->loader->add_filter('ere_property_slug', $property_admin, 'modify_property_slug');
            $this->loader->add_filter('ere_property_type_slug', $property_admin, 'modify_property_type_slug');
            $this->loader->add_filter('ere_property_status_slug', $property_admin, 'modify_property_status_slug');
            $this->loader->add_filter('ere_property_feature_slug', $property_admin, 'modify_property_feature_slug');
            $this->loader->add_filter('ere_property_city_slug', $property_admin, 'modify_property_city_slug');
            $this->loader->add_filter('ere_property_neighborhood_slug', $property_admin, 'modify_property_neighborhood_slug');
            $this->loader->add_filter('ere_property_state_slug', $property_admin, 'modify_property_state_slug');
            $this->loader->add_filter('ere_property_lable_slug', $property_admin, 'modify_property_lable_slug');
            // Agent Post Type
            $agent_admin = new ERE_Agent_Admin();
            $this->loader->add_filter('ere_agent_slug', $agent_admin, 'modify_agent_slug');
            $this->loader->add_filter('ere_agencies_slug', $agent_admin, 'modify_agencies_slug');
            $this->loader->add_action('restrict_manage_posts', $agent_admin, 'filter_restrict_manage_agent');
            $this->loader->add_filter('parse_query', $agent_admin, 'agent_filter');

            $this->loader->add_action('save_post', $agent_admin, 'save_agent_meta', 10, 2);
            $this->loader->add_action('admin_init', $agent_admin, 'approve_agent');
            // Package Post Type
            $package_admin = new ERE_Package_Admin();
            $this->loader->add_filter('ere_package_slug', $package_admin, 'modify_package_slug');

            // Agent Packages Post Type
            $user_package_admin = new ERE_User_Package_Admin();
            $this->loader->add_filter('ere_user_package_slug', $user_package_admin, 'modify_user_package_slug');
            $this->loader->add_action('restrict_manage_posts', $user_package_admin, 'filter_restrict_manage_user_package');
            $this->loader->add_filter('parse_query', $user_package_admin, 'user_package_filter');
            // Invoice Post Type
            $invoice_admin = new ERE_Invoice_Admin();
            $this->loader->add_filter('ere_invoice_slug', $invoice_admin, 'modify_invoice_slug');
            $this->loader->add_action('restrict_manage_posts', $invoice_admin, 'filter_restrict_manage_invoice');
            $this->loader->add_filter('parse_query', $invoice_admin, 'invoice_filter');
            // Trans Log Post Type
            $trans_log_admin = new ERE_Trans_Log_Admin();
            $this->loader->add_filter('ere_trans_log_slug', $trans_log_admin, 'modify_trans_log_slug');
            $this->loader->add_action('restrict_manage_posts', $trans_log_admin, 'filter_restrict_manage_trans_log');
            $this->loader->add_filter('parse_query', $trans_log_admin, 'trans_log_filter');
            if (is_admin()) {
                global $pagenow;
                $setup_page = new ERE_Setup_Admin();
                $this->loader->add_action('admin_menu', $setup_page, 'admin_menu', 12);
                $this->loader->add_action('admin_init', $setup_page, 'redirect');

                // property custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'property') {
                    $this->loader->add_filter('manage_edit-property_columns', $property_admin, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $property_admin, 'display_custom_column');
                    $this->loader->add_filter('manage_edit-property_sortable_columns', $property_admin, 'sortable_columns');

                    $this->loader->add_filter('post_row_actions', $property_admin, 'modify_list_row_actions',10,2);
                }

                // agent custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'agent') {
                    $this->loader->add_filter('manage_edit-agent_columns', $agent_admin, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $agent_admin, 'display_custom_column');
                    $this->loader->add_filter('post_row_actions', $agent_admin, 'modify_list_row_actions',10,2);
                }
                // package custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'package') {
                    $this->loader->add_filter('manage_edit-package_columns', $package_admin, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $package_admin, 'display_custom_column');
                }
                // agent package custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'user_package') {
                    $this->loader->add_filter('manage_edit-user_package_columns', $user_package_admin, 'register_custom_column_titles');
                    $this->loader->add_filter('manage_edit-user_package_sortable_columns', $user_package_admin, 'sortable_columns');
                    $this->loader->add_action('manage_posts_custom_column', $user_package_admin, 'display_custom_column');
                }
                // Invoice custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'invoice') {
                    $this->loader->add_filter('manage_edit-invoice_columns', $invoice_admin, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $invoice_admin, 'display_custom_column');
                    $this->loader->add_filter('manage_edit-invoice_sortable_columns', $invoice_admin, 'sortable_columns');
                }
                // Trans_log custom columns
                if ($pagenow == 'edit.php' && isset($_GET['post_type']) && esc_attr($_GET['post_type']) == 'trans_log') {
                    $this->loader->add_filter('manage_edit-trans_log_columns', $trans_log_admin, 'register_custom_column_titles');
                    $this->loader->add_action('manage_posts_custom_column', $trans_log_admin, 'display_custom_column');
                    $this->loader->add_filter('manage_edit-trans_log_sortable_columns', $trans_log_admin, 'sortable_columns');
                }
                $setup_metaboxes = new ERE_Setup_Metaboxes();
                $this->loader->add_action('load-post.php', $setup_metaboxes, 'meta_boxes_setup');
                $this->loader->add_action('load-post-new.php', $setup_metaboxes, 'meta_boxes_setup');
                $vc_map = new ERE_Vc_map();
                $this->loader->add_action('vc_before_init', $vc_map, 'register_vc_map');
            }
        }
        /**
         * Register all of the hooks related to the public-facing functionality
         */
        private function public_hooks()
        {
            $this->loader->add_action('init', $this, 'do_output_buffer');
            $plugin_public = new ERE_Public();

            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
            $this->loader->add_action('wp_footer', $plugin_public, 'enqueue_styles_rtl');
            $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
            $this->loader->add_filter('template_include', $plugin_public, 'template_loader');
            $this->loader->add_action('pre_get_posts', $plugin_public, 'set_posts_per_page');
            $profile = new ERE_Profile();
            $this->loader->add_filter('user_contactmethods', $profile, 'user_info', 10, 1);
            $this->loader->add_action('profile_update', $profile, 'profile_update');

            $this->loader->add_action('wp_ajax_ere_profile_image_upload_ajax', $profile, 'profile_image_upload_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_profile_image_upload_ajax', $profile, 'profile_image_upload_ajax');

            $this->loader->add_action('wp_ajax_ere_update_profile_ajax', $profile, 'update_profile_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_update_profile_ajax', $profile, 'update_profile_ajax');

            $this->loader->add_action('wp_ajax_ere_delete_account_ajax', $profile, 'delete_account_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_delete_account_ajax', $profile, 'delete_account_ajax');

            $this->loader->add_action('wp_ajax_ere_change_password_ajax', $profile, 'change_password_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_change_password_ajax', $profile, 'change_password_ajax');

            $this->loader->add_action('wp_ajax_ere_register_user_as_agent_ajax', $profile, 'register_user_as_agent_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_register_user_as_agent_ajax', $profile, 'register_user_as_agent_ajax');

            $this->loader->add_action('wp_ajax_ere_leave_agent_ajax', $profile, 'leave_agent_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_leave_agent_ajax', $profile, 'leave_agent_ajax');

            $login_register = new ERE_Login_Register();
            $this->loader->add_action('init', $login_register, 'hide_admin_bar', 9);
            $this->loader->add_action('admin_init', $login_register, 'restrict_admin_access');
            $this->loader->add_action('wp_footer', $login_register, 'login_register_modal');
            $this->loader->add_action('wp_ajax_ere_login_ajax', $login_register, 'login_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_login_ajax', $login_register, 'login_ajax');

            $this->loader->add_action('wp_ajax_ere_register_ajax', $login_register, 'register_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_register_ajax', $login_register, 'register_ajax');

            $this->loader->add_action('wp_ajax_ere_reset_password_ajax', $login_register, 'reset_password_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_reset_password_ajax', $login_register, 'reset_password_ajax');

            $property = new ERE_Property();
            $this->loader->add_action('wp_ajax_ere_property_img_upload_ajax', $property, 'property_img_upload_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_img_upload_ajax', $property, 'property_img_upload_ajax');
            $this->loader->add_action('wp_ajax_ere_remove_property_thumbnail_ajax', $property, 'remove_property_thumbnail_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_remove_property_thumbnail_ajax', $property, 'remove_property_thumbnail_ajax');
            $this->loader->add_filter('ere_submit_property', $property, 'submit_property');
            $this->loader->add_action('wp_ajax_ere_contact_agent_ajax', $property, 'contact_agent_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_contact_agent_ajax', $property, 'contact_agent_ajax');
            $this->loader->add_action('wp_ajax_property_print_ajax', $property, 'property_print_ajax');
            $this->loader->add_action('wp_ajax_nopriv_property_print_ajax', $property, 'property_print_ajax');
            $this->loader->add_action('before_delete_post', $property, 'delete_property_images');
            $this->loader->add_action('template_redirect', $property, 'set_views_counter',9999);

            $this->loader->add_action('wp_ajax_ere_get_states_by_country_ajax', $property, 'get_states_by_country_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_get_states_by_country_ajax', $property, 'get_states_by_country_ajax');

            $this->loader->add_action('wp_ajax_ere_get_cities_by_state_ajax', $property, 'get_cities_by_state_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_get_cities_by_state_ajax', $property, 'get_cities_by_state_ajax');

            $this->loader->add_action('wp_ajax_ere_get_neighborhoods_by_city_ajax', $property, 'get_neighborhoods_by_city_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_get_neighborhoods_by_city_ajax', $property, 'get_neighborhoods_by_city_ajax');

            //favorites
            $this->loader->add_action('wp_ajax_ere_favorite_ajax', $property, 'favorite_ajax');

            $invoice=new ERE_Invoice();
            $this->loader->add_action('wp_ajax_ere_invoice_print_ajax', $invoice, 'invoice_print_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_invoice_print_ajax', $invoice, 'invoice_print_ajax');

            //compare
            $compare = new ERE_Compare();
            $this->loader->add_action('init', $compare, 'open_session', 1);
            $this->loader->add_action('wp_logout', $compare, 'close_session');
            $this->loader->add_action('ere_show_compare', $compare, 'show_compare_listings', 5);

            $this->loader->add_action('wp_ajax_ere_compare_add_remove_property_ajax', $compare, 'compare_add_remove_property_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_compare_add_remove_property_ajax', $compare, 'compare_add_remove_property_ajax');

            $this->loader->add_action('wp_footer', $compare, 'template_compare_listing');

            $shortcode_property = new ERE_Shortcode_Property();
            $this->loader->add_action('wp', $shortcode_property, 'shortcode_property_action_handler');
            $this->loader->add_action('ere_my_properties_content_edit', $shortcode_property, 'edit_property');
            $this->loader->add_action('init', $this->forms, 'load_posted_form');

            $payment = new ERE_Payment();
            $this->loader->add_action('wp_ajax_ere_paypal_payment_per_listing_ajax', $payment, 'paypal_payment_per_listing_ajax');
            $this->loader->add_action('wp_ajax_ere_paypal_payment_per_package_ajax', $payment, 'paypal_payment_per_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_paypal_payment_per_package_ajax', $payment, 'paypal_payment_per_package_ajax');

            $this->loader->add_action('wp_ajax_ere_wire_transfer_per_package_ajax', $payment, 'wire_transfer_per_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_wire_transfer_per_package_ajax', $payment, 'wire_transfer_per_package_ajax');

            $this->loader->add_action('wp_ajax_ere_wire_transfer_per_listing_ajax', $payment, 'wire_transfer_per_listing_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_wire_transfer_per_listing_ajax', $payment, 'wire_transfer_per_listing_ajax');

            $this->loader->add_action('wp_ajax_ere_free_package_ajax', $payment, 'free_package_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_free_package_ajax', $payment, 'free_package_ajax');

            $shortcode = new ERE_Shortcode();
            $this->loader->add_action('wp_ajax_ere_property_gallery_fillter_ajax', $shortcode, 'property_gallery_fillter_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_gallery_fillter_ajax', $shortcode, 'property_gallery_fillter_ajax');

            $this->loader->add_action('wp_ajax_ere_property_featured_fillter_city_ajax', $shortcode, 'property_featured_fillter_city_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_featured_fillter_city_ajax', $shortcode, 'property_featured_fillter_city_ajax');

            $this->loader->add_action('wp_ajax_ere_property_paging_ajax', $shortcode, 'property_paging_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_paging_ajax', $shortcode, 'property_paging_ajax');

            $this->loader->add_action('wp_ajax_ere_agent_paging_ajax', $shortcode, 'agent_paging_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_agent_paging_ajax', $shortcode, 'agent_paging_ajax');

            $this->loader->add_action('wp_ajax_ere_agency_paging_ajax', $shortcode, 'agency_paging_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_agency_paging_ajax', $shortcode, 'agency_paging_ajax');

            $this->loader->add_action('wp_ajax_ere_property_set_session_view_as_ajax', $shortcode, 'property_set_session_view_as_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_set_session_view_as_ajax', $shortcode, 'property_set_session_view_as_ajax');

            $this->loader->add_action('wp_ajax_ere_agent_set_session_view_as_ajax', $shortcode, 'agent_set_session_view_as_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_agent_set_session_view_as_ajax', $shortcode, 'agent_set_session_view_as_ajax');

            $search=new ERE_Search();
            $this->loader->add_action('wp_ajax_ere_property_search_ajax', $search, 'ere_property_search_ajax');
            $this->loader->add_action('wp_ajax_nopriv_ere_property_search_ajax', $search, 'ere_property_search_ajax');
            $this->loader->add_action('wp_ajax_ere_ajax_search_on_change_value', $search, 'ere_ajax_search_on_change_value');
            $this->loader->add_action('wp_ajax_nopriv_ere_ajax_search_on_change_value', $search, 'ere_ajax_search_on_change_value');
            $this->loader->add_action('wp_ajax_ere_title_auto_complete_search', $search, 'ere_title_auto_complete_search');
            $this->loader->add_action('wp_ajax_nopriv_ere_title_auto_complete_search', $search, 'ere_title_auto_complete_search');

            $save_search=new ERE_Save_Search();
            $this->loader->add_action('wp_ajax_ere_save_search_ajax', $save_search, 'save_search_ajax');

            $schedule = new ERE_Schedule();
            $this->loader->add_action('init', $schedule, 'scheduled_hook');
            $this->loader->add_action('ere_per_listing_check_expire', $schedule, 'per_listing_check_expire');
            $this->loader->add_action('ere_saved_search_check_result', $schedule, 'saved_search_check_result');
        }
        /**
         * Run the loader to execute all of the hooks with WordPress
         */
        public function run()
        {
            $this->loader->run();
        }

        /**
         * The reference to the class that orchestrates the hooks with the plugin.
         */
        public function get_loader()
        {
            return $this->loader;
        }

        /**
         * do_output_buffer
         */
        function do_output_buffer()
        {
            ob_start();
        }

        /**
         * Get forms
         * @return mixed
         */
        public function get_forms()
        {
            return $this->forms;
        }

        /**
         * Get template path
         * @return mixed
         */
        public function template_path()
        {
            return apply_filters('ere_template_path', 'ere-templates/');
        }
    }
}
if(!function_exists('ERE'))
{
    function ERE() {
        return Essential_Real_Estate::get_instance();
    }
}
// Global for backwards compatibility.
$GLOBALS['Essential_Real_Estate'] = ERE();