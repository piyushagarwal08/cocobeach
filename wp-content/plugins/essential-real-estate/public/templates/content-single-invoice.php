<?php
global $post;
/**
 * ere_single_invoice_before_summary hook.
 */
do_action( 'ere_single_invoice_before_summary' );
?>
<?php
/**
 * ere_single_invoice_summary hook.
 *
 * @hooked single_invoice - 5
 */
do_action( 'ere_single_invoice_summary' ); ?>
<?php
/**
 * ere_single_invoice_after_summary hook.
 */
do_action( 'ere_single_invoice_after_summary' );