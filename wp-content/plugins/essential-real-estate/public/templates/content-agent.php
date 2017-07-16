<?php
/**
 * @var $gf_item_wrap
 * @var $agent_layout_style
 */
/**
 * ere_before_loop_agent hook.
 */
do_action( 'ere_before_loop_agent' );
/**
 * ere_loop_agent hook.
 *
 * @hooked ere_loop_agent - 10
 */
do_action( 'ere_loop_agent', $gf_item_wrap, $agent_layout_style);
/**
 * ere_after_loop_agent hook.
 */
do_action( 'ere_after_loop_agent' );