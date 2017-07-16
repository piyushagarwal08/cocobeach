<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://themeforest.net/user/G5Themes
 * @since      1.0.0
 *
 * @package    Essential_Real_Estate
 * @subpackage Essential_Real_Estate/includes
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Template_Hooks')) {
    /**
     * Class ERE_Template_Hooks
     */
    require_once ERE_PLUGIN_DIR . 'includes/class-ere-loader.php';

    class ERE_Template_Hooks
    {
        protected $loader;

        public function __construct()
        {
            $this->loader = new ERE_Loader();
            //property_sidebar
            //$this->loader->add_action('ere_sidebar_property', $this, 'sidebar_property', 10);
            //$this->loader->add_action('ere_sidebar_agent', $this, 'sidebar_agent', 10);
            //$this->loader->add_action('ere_sidebar_invoice', $this, 'sidebar_invoice', 10);
            //Archive Property
            $this->loader->add_action('ere_archive_property_before_main_content', $this, 'archive_property_search', 10);
            $this->loader->add_action('ere_archive_property_heading', $this, 'archive_property_heading', 10, 4);
            $this->loader->add_action('ere_archive_property_action', $this, 'archive_property_action', 10, 1);
            $this->loader->add_action('ere_loop_property', $this, 'loop_property', 10, 2);
            //Archive Agent
            $this->loader->add_action('ere_archive_agent_heading', $this, 'archive_agent_heading', 10, 1);
            $this->loader->add_action('ere_archive_agent_action', $this, 'archive_agent_action', 10, 1);
            $this->loader->add_action('ere_loop_agent', $this, 'loop_agent', 10, 2);

            //Single Property
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_info_header', 5);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_gallery', 10);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_info_tabs', 15);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_floors', 20);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_map_directions', 25);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_nearby_places', 30);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_walk_score', 35);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_contact_agent', 40);
            $this->loader->add_action('ere_single_property_summary', $this, 'single_property_info_footer', 90);
            //Single Property property_info_header_main
            $this->loader->add_action('ere_single_property_info_header_title', $this, 'single_property_info_header_title', 5);
            //Single Agent
            $this->loader->add_action('ere_single_agent_summary', $this, 'single_agent_info', 5);
            $this->loader->add_action('ere_single_agent_summary', $this, 'single_agent_property', 10);
            $this->loader->add_action('ere_single_agent_summary', $this, 'single_agent_other', 15);
            //Single Invoice
            $this->loader->add_action('ere_single_invoice_summary', $this, 'single_invoice', 10);

            //Taxonomy
            $this->loader->add_action('ere_taxonomy_agencies_summary', $this, 'taxonomy_agencies_detail', 10);
            $this->loader->add_action('ere_taxonomy_agencies_agents', $this, 'taxonomy_agencies_agents', 10, 1);
            //Property Action
            $this->loader->add_action('ere_property_action', $this, 'property_social_share', 5);
            $this->loader->add_action('ere_property_action', $this, 'property_favorite', 10);
            $this->loader->add_action('ere_property_action', $this, 'property_compare', 15);
            $this->loader->run();
        }

        public function archive_property_search()
        {
            $enable_archive_search = ere_get_option( 'enable_archive_search', '1' );
            if($enable_archive_search=='1'):
            $title_enable = ere_get_option( 'archive_search_title_enable', 'true' );
            $location_enable = ere_get_option( 'archive_search_location_enable', 'true' );
            $countries_enable = ere_get_option( 'archive_search_countries_enable', 'false' );
            $states_enable = ere_get_option( 'archive_search_states_enable', 'false' );
            $cities_enable = ere_get_option( 'archive_search_cities_enable', 'true' );
            $neighborhoods_enable = ere_get_option( 'archive_search_neighborhoods_enable', 'false' );

            $types_enable = ere_get_option( 'archive_search_types_enable', 'true' );
            $status_enable = ere_get_option( 'archive_search_status_enable', 'true' );
            $number_bedrooms_enable = ere_get_option( 'archive_search_number_bedrooms_enable', 'true' );
            $number_bathrooms_enable = ere_get_option( 'archive_search_number_bathrooms_enable', 'true' );
            $price_enable = ere_get_option( 'archive_search_price_enable', 'true' );
            $area_enable = ere_get_option( 'archive_search_area_enable', 'true' );

            $year_built_enable = ere_get_option( 'archive_search_year_built_enable', 'true' );
            $labels_enable = ere_get_option( 'archive_search_labels_enable', 'true' );
            $number_garage_enable = ere_get_option( 'archive_search_number_garage_enable', 'true' );
            $garage_area_enable = ere_get_option( 'archive_search_garage_area_enable', 'true' );
            $land_area_enable = ere_get_option( 'archive_search_land_area_enable', 'true' );
            $property_identity_enable = ere_get_option( 'archive_search_property_identity_enable', 'true' );
            $other_features_enable = ere_get_option( 'archive_search_other_features_enable', 'true' );
            ?>
            <div class="ere-heading-style2 mg-bottom-35 text-left">
                <h2><?php esc_html_e('Search Property','essential-real-estate') ?></h2>
            </div>
            <?php
            echo do_shortcode('[ere_property_search show_status_tab="true" color_scheme="color-dark" title_enable="'.$title_enable.'"  location_enable="'.$location_enable.'"  countries_enable="'.$countries_enable.'" states_enable="'.$states_enable.'" cities_enable="'.$cities_enable.'" neighborhoods_enable="'.$neighborhoods_enable.'" types_enable="'.$types_enable.'" status_enable="'.$status_enable.'" number_bedrooms_enable="'.$number_bedrooms_enable.'" number_bathrooms_enable="'.$number_bathrooms_enable.'" price_enable="'.$price_enable.'" area_enable="'.$area_enable.'" map_search_enable="" advanced_search_enable="true" year_built_enable="'.$year_built_enable.'" labels_enable="'.$labels_enable.'" number_garage_enable="'.$number_garage_enable.'" garage_area_enable="'.$garage_area_enable.'" land_area_enable="'.$land_area_enable.'" property_identity_enable="'.$property_identity_enable.'" other_features_enable="'.$other_features_enable.'" is_page_search="1"]');
            endif;
        }

        /**
         * property_sidebar
         */
        public function sidebar_property()
        {
            ere_get_template('global/sidebar-property.php');
        }

        /**
         *agent_sidebar
         */
        public function sidebar_agent()
        {
            ere_get_template('global/sidebar-agent.php');
        }

        /**
         * invoice_sidebar
         */
        public function sidebar_invoice()
        {
            ere_get_template('global/sidebar-invoice.php');
        }

        /**
         * archive_property_heading
         * @param $total_post
         * @param $taxonomy_title
         * @param $agent_id
         * @param $author_id
         */
        public function archive_property_heading($total_post, $taxonomy_title, $agent_id, $author_id)
        {
            ?>
            <div class="ere-heading">
                <span></span>
                <?php if (is_tax()):?>
                    <p class="uppercase"><?php echo sprintf(__('%s Results of','essential-real-estate'), $total_post); ?></p>
                    <h2 class="uppercase"><?php echo esc_attr($taxonomy_title); ?></h2>
                <?php elseif(!empty($agent_id) && $agent_id>0):
                    $agent_name=get_the_title($agent_id);
                    ?>
                    <p class="uppercase"><?php echo sprintf(__('%s Results of agent','essential-real-estate'), $total_post); ?></p>
                    <h2 class="uppercase"><?php echo esc_html($agent_name); ?></h2>
                <?php elseif (!empty($author_id) && $author_id >0 ):
                    $user_info = get_userdata($author_id);
                    $agent_name = $user_info->first_name . ' ' . $user_info->last_name; ?>
                    <p class="uppercase"><?php echo sprintf(__('%s Results of user','essential-real-estate'), $total_post); ?></p>
                    <h2 class="uppercase"><?php echo esc_html($agent_name); ?></h2>
                <?php else:?>
                    <p class="uppercase"><?php echo $total_post . ' ' . ere_get_number_text($total_post, esc_html__('Results', 'essential-real-estate'), esc_html__('Result', 'essential-real-estate')); ?></p>
                    <h2 class="uppercase"><?php esc_html_e('All Properties', 'essential-real-estate') ?></h2>
                <?php endif; ?>
            </div>
            <?php
        }

        /**
         * archive_property_action
         * @param $taxonomy_name
         */
        public function archive_property_action($taxonomy_name)
        {
            ?>
            <div class="archive-property-action">
                <?php if ($taxonomy_name != 'property-status'): ?>
                    <div class="archive-property-action-item">
                        <div class="property-status property-filter">
                            <ul>
                                <li class="active"><a data-status="all" href="<?php
                                    $pot_link_status = add_query_arg(array('status' => 'all'));
                                    echo esc_url($pot_link_status) ?>"
                                                      title="<?php esc_html_e('All', 'essential-real-estate'); ?>"><?php esc_html_e('All', 'essential-real-estate'); ?></a>
                                </li>
                                <?php
                                $property_status = get_categories(array('taxonomy' => 'property-status', 'hide_empty' => 1, 'orderby' => 'ASC'));
                                if ($property_status) :
                                    foreach ($property_status as $status):?>
                                        <li><a data-status="<?php echo esc_attr($status->slug) ?>" href="<?php
                                            $pot_link_status = add_query_arg(array('status' => $status->slug));
                                            echo esc_url($pot_link_status) ?>"
                                               title="<?php echo esc_attr($status->name) ?>"><?php echo esc_attr($status->name) ?></a>
                                        </li>
                                    <?php endforeach;
                                endif;
                                ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="archive-property-action-item">
                    <div class="sort-property property-filter">
                        <span
                            class="property-filter-placeholder"><?php esc_html_e('Sort By', 'essential-real-estate'); ?></span>
                        <ul>
                            <li><a data-sortby="default" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'default'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Default Order', 'essential-real-estate'); ?>"><?php esc_html_e('Default Order', 'essential-real-estate'); ?></a>
                            </li>
                            <li><a data-sortby="featured" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'featured'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Featured', 'essential-real-estate'); ?>"><?php esc_html_e('Featured', 'essential-real-estate'); ?></a>
                            </li>
                            <li><a data-sortby="a_price" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'a_price'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Price (Low to High)', 'essential-real-estate'); ?>"><?php esc_html_e('Price (Low to High)', 'essential-real-estate'); ?></a>
                            </li>
                            <li><a data-sortby="d_price" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'd_price'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Price (High to Low)', 'essential-real-estate'); ?>"><?php esc_html_e('Price (High to Low)', 'essential-real-estate'); ?></a>
                            </li>
                            <li><a data-sortby="a_date" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'a_date'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Date (Old to New)', 'essential-real-estate'); ?>"><?php esc_html_e('Date (Old to New)', 'essential-real-estate'); ?></a>
                            </li>
                            <li><a data-sortby="d_date" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'd_date'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Date (New to Old)', 'essential-real-estate'); ?>"><?php esc_html_e('Date (New to Old)', 'essential-real-estate'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="view-as" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
						<span data-view-as="property-list" class="view-as-list"
                              title="<?php esc_html_e('View as List', 'essential-real-estate') ?>">
							<i class="fa fa-list-ul"></i>
						</span>
						<span data-view-as="property-grid" class="view-as-grid"
                              title="<?php esc_html_e('View as Grid', 'essential-real-estate') ?>">
							<i class="fa fa-th-large"></i>
						</span>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * archive_agent_heading
         * @param $total_post
         */
        public function archive_agent_heading($total_post)
        {
            ?>
            <div class="ere-heading sm-mg-bottom-40">
                <span></span>

                <p class="uppercase"><?php echo $total_post . ' ' . ere_get_number_text($total_post, esc_html__('Results', 'essential-real-estate'), esc_html__('Result', 'essential-real-estate')); ?></p>

                <h2 class="uppercase"><?php esc_html_e('All Agent', 'essential-real-estate') ?></h2>
            </div>
            <?php
        }

        /**
         * archive_agent_action
         * @param $keyword
         */
        public function archive_agent_action($keyword)
        {
            ?>
            <div class="archive-agent-action">
                <div class="archive-agent-action-item">
                    <form method="get">
                        <div class="form-group input-group search-box"><input type="text" name="agent_name"
                                                                              value="<?php echo esc_attr($keyword); ?>"
                                                                              class="form-control"
                                                                              placeholder="<?php esc_html_e('Search...', 'essential-real-estate'); ?>"> <span
                                class="input-group-btn"><button type="submit" class="button"><i
                                        class="fa fa-search"></i></button> </span>
                        </div>
                    </form>
                </div>
                <div class="archive-agent-action-item">
                    <div class="sort-agent">
                        <span class="sort-by"><?php esc_html_e('Sort By', 'essential-real-estate'); ?></span>
                        <ul>
                            <li><a data-sortby="a_name" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'a_name'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Name (A to Z)', 'essential-real-estate'); ?>"><?php esc_html_e('Name (A to Z)', 'essential-real-estate'); ?></a>
                            </li>
                            <li><a data-sortby="d_name" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'd_name'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Name (Z to A)', 'essential-real-estate'); ?>"><?php esc_html_e('Name (Z to A)', 'essential-real-estate'); ?></a>
                            </li>
                            <li><a data-sortby="a_date" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'a_date'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Date (Old to New)', 'essential-real-estate'); ?>"><?php esc_html_e('Date (Old to New)', 'essential-real-estate'); ?></a>
                            </li>
                            <li><a data-sortby="d_date" href="<?php
                                $pot_link_sortby = add_query_arg(array('sortby' => 'd_date'));
                                echo esc_url($pot_link_sortby) ?>"
                                   title="<?php esc_html_e('Date (New to Old)', 'essential-real-estate'); ?>"><?php esc_html_e('Date (New to Old)', 'essential-real-estate'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="view-as" data-admin-url="<?php echo ERE_AJAX_URL; ?>">
                            <span data-view-as="agent-list" class="view-as-list"
                                  title="<?php esc_html_e('View as List', 'essential-real-estate') ?>">
                                <i class="fa fa-list-ul"></i>
                            </span>
                            <span data-view-as="agent-grid" class="view-as-grid"
                                  title="<?php esc_html_e('View as Grid', 'essential-real-estate') ?>">
                                <i class="fa fa-th-large"></i>
                            </span>
                    </div>
                </div>
            </div>
            <?php
        }

        /**
         * loop_property
         * @param $property_item_class
         * @param $custom_property_image_size
         */
        public function loop_property($property_item_class, $custom_property_image_size)
        {
            ere_get_template('loop/property.php', array('property_item_class' => $property_item_class, 'custom_property_image_size' => $custom_property_image_size));
        }

        /**
         * loop_agent
         * @param $gf_item_wrap
         * @param $agent_layout_style
         */
        public function loop_agent($gf_item_wrap, $agent_layout_style)
        {
            ere_get_template('loop/agent.php', array('gf_item_wrap' => $gf_item_wrap, 'agent_layout_style' => $agent_layout_style));
        }
        /**
         * single_property_info_header
         */
        public function single_property_info_header()
        {
            ere_get_template('single-property/info-header.php');
        }
        /**
         * single_property_info_footer
         */
        public function single_property_info_footer()
        {
            ere_get_template('single-property/info-footer.php');
        }
        /**
         * single_property_info_header_title
         */
        public function single_property_info_header_title()
        {
            ere_get_template( 'single-property/info-header-title.php');
        }
        /**
         * single_property_gallery
         */
        public function single_property_gallery()
        {
            ere_get_template('single-property/gallery.php');
        }

        /**
         * single_property_info_tabs
         */
        public function single_property_info_tabs()
        {
            ere_get_template('single-property/info-tabs.php');
        }

        /**
         * single_property_floors
         */
        public function single_property_floors()
        {
            global $post;
            $property_meta_data = get_post_custom($post->ID);
            $property_floors = get_post_meta($post->ID, ERE_METABOX_PREFIX . 'floors', true);
            $property_floor_enable = isset($property_meta_data[ERE_METABOX_PREFIX . 'floors_enable']) ? $property_meta_data[ERE_METABOX_PREFIX . 'floors_enable'][0] : '';
            if ($property_floor_enable && $property_floors) {
                ere_get_template('single-property/floors.php', array('property_floors' => $property_floors));
            }
        }

        /**
         * single_property_map_directions
         */
        public function single_property_map_directions()
        {
            global $post;
            $enable_map_directions = ere_get_option('enable_map_directions', 1);
            if ($enable_map_directions == 1):?>
                <div class="property-directions mg-bottom-60 sm-mg-bottom-40">
                    <div class="ere-heading-style2 mg-bottom-35 text-left">
                        <h2><?php esc_html_e('Get Directions', 'essential-real-estate'); ?></h2>
                    </div>
                    <?php ere_get_template('single-property/google-map-directions.php', array('property_id' => $post->ID)); ?>
                </div>
            <?php endif;
        }

        /**
         * single_property_nearby_places
         */
        public function single_property_nearby_places()
        {
            global $post;
            $enable_nearby_places = ere_get_option('enable_nearby_places', 1);
            if ($enable_nearby_places == 1):?>
                <div class="property-nearby-places mg-bottom-60 sm-mg-bottom-40">
                    <div class="ere-heading-style2 mg-bottom-35 text-left">
                        <h2><?php esc_html_e('Nearby Places', 'essential-real-estate'); ?></h2>
                    </div>
                    <?php ere_get_template('single-property/nearby-places.php', array('property_id' => $post->ID)); ?>
                </div>
            <?php endif;
        }
        /**
         * single_property_walk_score
         */
        public function single_property_walk_score()
        {
            global $post;
            $enable_walk_score = ere_get_option('enable_walk_score', 0);
            if ($enable_walk_score == 1):?>
                <div class="property-walk-score mg-bottom-60 sm-mg-bottom-40">
                    <div class="ere-heading-style2 mg-bottom-35 text-left">
                        <h2><?php esc_html_e('Walk Score', 'essential-real-estate'); ?></h2>
                    </div>
                    <?php ere_get_template('single-property/walk-score.php', array('property_id' => $post->ID)); ?>
                </div>
            <?php endif;
        }
        /**
         * single_property_contact_agent
         */
        public function single_property_contact_agent()
        {
            $property_form_sections = ere_get_option('property_form_sections', array('title_des', 'location', 'type', 'price', 'features', 'details', 'media', 'floors', 'agent'));
            $hide_contact_information_if_not_login = ere_get_option('hide_contact_information_if_not_login', 0);
            if($hide_contact_information_if_not_login==0)
            {
                if (in_array('contact', $property_form_sections)) {
                    ere_get_template('single-property/contact-agent.php');
                }
            }
            else
            {
                if(is_user_logged_in()){
                    if (in_array('contact', $property_form_sections)) {
                        ere_get_template('single-property/contact-agent.php');
                    }
                }
                else
                {
                    ?>
                    <p class="ere-account-sign-in"><?php esc_attr_e('Please login or register to view contact information for this agent/owner', 'essential-real-estate'); ?>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ere_signin_modal">
                            <?php esc_html_e('Login', 'essential-real-estate'); ?>
                        </button>
                    </p>
                    <?php
                }
            }

        }

        /**
         * single_agent_info
         */
        public function single_agent_info()
        {
            ere_get_template('single-agent/agent-info.php');
        }

        /**
         * single_agent_property
         */
        public function single_agent_property()
        {
            $enable_property_of_agent = ere_get_option('enable_property_of_agent');
            if ($enable_property_of_agent == 1) {
                ere_get_template('single-agent/agent-property.php');
            }
        }

        /**
         * single_agent_other
         */
        public function single_agent_other()
        {
            $enable_other_agent = ere_get_option('enable_other_agent');
            if ($enable_other_agent == 1) {
                ere_get_template('single-agent/other-agent.php');
            }
        }

        /**
         * single_invoice
         */
        public function single_invoice()
        {
            ere_get_template('single-invoice/invoice.php');
        }

        /**
         * taxonomy_agencies_detail
         */
        public function taxonomy_agencies_detail()
        {
            ere_get_template('taxonomy/agencies-detail.php');
        }

        /**
         * taxonomy_agencies_agents
         * @param $agencies_term_slug
         */
        public function taxonomy_agencies_agents($agencies_term_slug)
        {
            ere_get_template('taxonomy/agencies-agents.php', array('agencies_term_slug' => $agencies_term_slug));
        }

        /**
         * Social Share
         */
        public function property_social_share()
        {
            if (ere_get_option('enable_social_share', '1') == '1') {
                ere_get_template('global/social-share.php');
            }
        }

        /**
         * Favorite
         */
        public function property_favorite()
        {
            if (ere_get_option('enable_favorite_property', '1') == '1') {
                ere_get_template('property/favorite.php');
            }
        }

        /**
         * Compare
         */
        public function property_compare()
        {
            if (ere_get_option('enable_compare_properties', '1') == '1'):?>
                <a class="compare-property" href="javascript:;"
                   data-property-id="<?php the_ID() ?>" data-toggle="tooltip"
                   title="<?php esc_html_e('Compare', 'essential-real-estate') ?>">
                    <i class="fa fa-plus"></i>
                </a>
            <?php endif;
        }
    }

    new ERE_Template_Hooks();
}