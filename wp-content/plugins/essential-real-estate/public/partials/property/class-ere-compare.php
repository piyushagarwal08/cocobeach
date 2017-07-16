<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Compare')) {
    /**
     * Class ERE_Compare
     */
    class ERE_Compare
    {
        /**
         * Add property to comapre
         */
        public function compare_add_remove_property_ajax()
        {
            $property_id = (int)$_POST['property_id'];
            $max_items = 4;
            $current_number = (isset($_SESSION['ere_compare_properties']) && is_array($_SESSION['ere_compare_properties'])) ? count($_SESSION['ere_compare_properties']) : 0;

            if (is_array($_SESSION['ere_compare_properties']) && in_array($property_id, $_SESSION['ere_compare_properties']))
                unset($_SESSION['ere_compare_properties'][array_search($property_id, $_SESSION['ere_compare_properties'])]);
            elseif ($current_number < $max_items) {

                $_SESSION['ere_compare_properties'][] = $property_id;
            }

            $_SESSION['ere_compare_properties'] = array_unique($_SESSION['ere_compare_properties']);

			$this->show_compare_listings();
            wp_die();
        }
        /*
         * Open new session
         */
        public function open_session()
        {
            if (!session_id()) {
                session_start();
                if (!isset($_SESSION['ere_compare_starttime'])) $_SESSION['ere_compare_starttime'] = time();
                if (!isset($_SESSION['ere_compare_properties'])) $_SESSION['ere_compare_properties'] = array();
            }
            if (isset($_SESSION['ere_compare_starttime'])) {
                if ((int)$_SESSION['ere_compare_starttime'] > time() + 86400) {
                    unset($_SESSION['ere_compare_properties']);
                }
            }
        }
        /**
         * output compare basket
         */
        public function show_compare_listings()
        {
            ?>
            <div id="compare-properties-listings">
                <?php if (isset($_SESSION['ere_compare_properties']) && count($_SESSION['ere_compare_properties'])): ?>
                    <div class="compare-listing-body">
                        <div class="compare-thumb-main row">
                            <?php foreach( $_SESSION[ 'ere_compare_properties' ] as $key ) : ?>
							<?php if( $key != 0 ) : ?>
								<div class="compare-thumb compare-property" data-property-id="<?php echo $key; ?>">
                                    <?php echo get_the_post_thumbnail( (double) $key, 'ere-widget-prop', array( 'class' => 'compare-property-img' ) ); ?>
                                    <button class="compare-property-remove"><i class="fa fa-times"></i></button>
                                </div>
                            <?php endif; ?>
						<?php endforeach; ?>
                        </div>
                        <button type="button"
                                class="btn btn-primary btn-xs compare-properties-button"><?php esc_html_e('Go', 'essential-real-estate'); ?></button>
					</div>
                    <button class="btn btn-primary listing-btn"><i class="fa fa-angle-left"></i></button>
                <?php endif; ?>
            </div>
            <?php
        }
        /*
         * Close session
         * */
        public function close_session()
        {
            if (isset($_SESSION))
                session_destroy();
        }

        /**
         * Compare template
         */
        public function template_compare_listing()
        {
            ere_get_template('property/compare-listing.php');
        }
    }
}