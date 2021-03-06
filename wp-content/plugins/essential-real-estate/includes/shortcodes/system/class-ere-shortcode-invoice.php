<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Shortcode_Invoice')) {
    /**
     * Class ERE_Shortcode_Invoice
     */
    class ERE_Shortcode_Invoice
    {
        private $ere_message = '';

        /**
         * Constructor.
         */
        public function __construct()
        {
            add_shortcode('ere_my_invoices', array($this, 'my_invoices'));
        }

        /**
         * My invoices Shortcode
         * @param $atts
         * @return null|string
         */
        public function my_invoices($atts)
        {
            if (!is_user_logged_in()) {
                echo ere_get_template_html('global/dashboard-login.php');
                return null;
            }
            $posts_per_page = '25';
            $date_query = array();
            $start_date = $end_date = $invoice_status = $invoice_type = '';
            extract(shortcode_atts(array(
                'posts_per_page' => '25',
                'start_date' => '',
                'end_date' => '',
                'invoice_status' => '',
                'invoice_type' => ''
            ), $atts));
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            ob_start();
            $meta_query = array(
                array(
                    'key' => ERE_METABOX_PREFIX . 'invoice_user_id',
                    'value' => $user_id,
                    'compare' => '='
                )
            );
            if (!empty($start_date) && !empty($end_date)) {
                $date_query = array(
                    'after' => $start_date,
                    'before' => $end_date
                );
            } else {
                if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '' && isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
                    $start_date = esc_html($_REQUEST['start_date']);
                    $end_date = esc_html($_REQUEST['end_date']);
                    $date_query = array(
                        'after' => $start_date,
                        'before' => $end_date
                    );
                }
            }
            if (!empty($invoice_status)) {
                $meta_query[] = array(
                    'key' => ERE_METABOX_PREFIX . 'invoice_payment_status',
                    'value' => $invoice_status,
                    'compare' => '=',
                    'type' => 'NUMERIC'
                );
            } else {
                if (isset($_REQUEST['invoice_status']) && $_REQUEST['invoice_status'] != '') {
                    $invoice_status = esc_html($_REQUEST['invoice_status']);
                    $meta_query[] = array(
                        'key' => ERE_METABOX_PREFIX . 'invoice_payment_status',
                        'value' => $invoice_status,
                        'compare' => '=',
                        'type' => 'NUMERIC'
                    );
                }
            }
            if (!empty($invoice_type)) {
                $meta_query[] = array(
                    'key' => ERE_METABOX_PREFIX . 'invoice_payment_type',
                    'value' => $invoice_type,
                    'compare' => 'LIKE',
                    'type' => 'CHAR'
                );
            } else {
                if (isset($_REQUEST['invoice_type']) && $_REQUEST['invoice_type'] != '') {
                    $invoice_type = esc_html($_REQUEST['invoice_type']);
                    $meta_query[] = array(
                        'key' => ERE_METABOX_PREFIX . 'invoice_payment_type',
                        'value' => $invoice_type,
                        'compare' => 'LIKE',
                        'type' => 'CHAR'
                    );
                }
            }

            $args = apply_filters('ere_my_invoices_query_args', array(
                'post_type' => 'invoice',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => $posts_per_page,
                'offset' => (max(1, get_query_var('paged')) - 1) * $posts_per_page,
                'orderby' => 'date',
                'order' => 'desc',
                'date_query' => $date_query,
                'meta_query' => $meta_query
            ));
            $invoices = new WP_Query;
            echo $this->ere_message;

            $my_invoices_columns = apply_filters('ere_my_invoices_columns', array(
                'id' => esc_html__('Order ID', 'essential-real-estate'),
                'date' => esc_html__('Purchase Date', 'essential-real-estate'),
                'type' => esc_html__('Type', 'essential-real-estate'),
                'item_name' => esc_html__('Item Name', 'essential-real-estate'),
                'status' => esc_html__('Status', 'essential-real-estate'),
                'total' => esc_html__('Total', 'essential-real-estate'),
                'view' => '',
            ));
            ere_get_template('invoice/my-invoices.php', array('invoices' => $invoices->query($args), 'max_num_pages' => $invoices->max_num_pages, 'my_invoices_columns' => $my_invoices_columns, 'start_date' => $start_date, 'end_date' => $end_date, 'invoice_status' => $invoice_status, 'invoice_type' => $invoice_type));
            wp_reset_postdata();
            return ob_get_clean();
        }
    }
}
new ERE_Shortcode_Invoice();