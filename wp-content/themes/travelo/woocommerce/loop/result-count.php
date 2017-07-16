<?php
/**
 * Result Count
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $wp_query;

if ( ! woocommerce_products_will_display() )
    return;
?>
<h4 class="woocommerce-result-count search-results-title">
    <i class="soap-icon-search"></i>
    <?php
    $paged    = max( 1, $wp_query->get( 'paged' ) );
    $per_page = $wp_query->get( 'posts_per_page' );
    $total    = $wp_query->found_posts;
    $first    = ( $per_page * $paged ) - $per_page + 1;
    $last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );

    if ( $total <= $per_page || -1 === $per_page ) {
        /* translators: %d: total results */
        printf( _n( 'Showing the single result', 'Showing all <b>%d</b> results', $total, 'trav' ), $total );
    } else {
        /* translators: 1: first result 2: last result 3: total results */
        printf( _nx( 'Showing the single result', '<b>%3$d</b> results found', $total, '%1$d = first, %2$d = last, %3$d = total', 'trav' ), $first, $last, $total );
    }
    ?>
</h4>