<?php
/**
 * Loop Add to Cart
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product, $trav_options;

if ( $trav_options['shop_catalog_mode'] ) { 
	$class = 'read_more button';
	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		sprintf( '<a rel="nofollow" href="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
			esc_url( get_the_permalink() ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $class ) ? $class : 'button' ),
			esc_html( __('View Details', 'trav') )
		),
	$product );
} else { 
	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $class ) ? $class : 'button' ),
			esc_html( $product->add_to_cart_text() )
		),
	$product );
}
