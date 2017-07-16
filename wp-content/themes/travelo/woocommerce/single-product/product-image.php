<?php
/**
 * Single Product Image
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
) );
?>

<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
	<figure class="woocommerce-product-gallery__wrapper">
		<?php

		if ( has_post_thumbnail() ) {
			$attachment_ids = $product->get_gallery_image_ids();

			if ( $attachment_ids ) {
				array_unshift( $attachment_ids, $post_thumbnail_id );
				?>

				<div class="flexslider photo-gallery style1" id="product-slideshow" data-sync="#product-thumbnail">
					<ul class="slides">
						<?php 
						foreach ( $attachment_ids as $gallery_img ) {
							$full_size_image  = wp_get_attachment_image_src( $gallery_img, 'full' );
							$thumbnail_post   = get_post( $gallery_img );
							$image_title      = $thumbnail_post->post_content;

							$attributes = array(
								'title'                   => $image_title,
								'data-src'                => $full_size_image[0],
								'data-large_image'        => $full_size_image[0],
								'data-large_image_width'  => $full_size_image[1],
								'data-large_image_height' => $full_size_image[2],
							);

							echo '<li>' . wp_get_attachment_image( $gallery_img, 'shop_single', false, $attributes ) . '</li>';
						} 
						?>
					</ul>
				</div>

				<div class="flexslider image-carousel style1" id="product-thumbnail"  data-animation="slide" data-item-width="70" data-item-margin="10" data-sync="#product-slideshow">
					<ul class="slides">
						<?php 
						foreach ( $attachment_ids as $gallery_img ) {
							$full_size_image  = wp_get_attachment_image_src( $gallery_img, 'full' );
							$thumbnail_post   = get_post( $gallery_img );
							$image_title      = $thumbnail_post->post_content;

							$attributes = array(
								'title'                   => $image_title,
								'data-src'                => $full_size_image[0],
								'data-large_image'        => $full_size_image[0],
								'data-large_image_width'  => $full_size_image[1],
								'data-large_image_height' => $full_size_image[2],
							);

							echo '<li>' . wp_get_attachment_image( $gallery_img, 'widget-thumb', false, $attributes ) . '</li>';
						} 
						?>
					</ul>
				</div>

				<?php
			}
		} else {
			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html .= '</div>';

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );
		}

		do_action( 'woocommerce_product_thumbnails' );
		?>
	</figure>
</div>
