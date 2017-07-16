<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 17/01/2017
 * Time: 10:41 AM
 */
$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'single-agent');

$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'single-agent', ERE_PLUGIN_URL . 'public/assets/js/agent/ere-single-agent' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
global $post;
?>
<div class="ere-agent-single-wrap">
	<div class="ere-agent-single">
		<?php
		/**
		 * ere_single_agent_before_summary hook.
		 */
		do_action( 'ere_single_agent_before_summary' );
		?>
		<?php
		/**
		 * ere_single_agent_summary hook.
		 *
		 * @hooked single_agent_info - 5
		 * @hooked single_agent_property - 10
		 * @hooked single_agent_other - 15
		 */
		do_action( 'ere_single_agent_summary' ); ?>
		<?php
		/**
		 * ere_single_agent_after_summary hook.
		 */
		do_action( 'ere_single_agent_after_summary' );
		?>
	</div>
</div>