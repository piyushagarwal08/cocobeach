<?php
global $post;
$other_agent_layout_style = ere_get_option('other_agent_layout_style', 'agent-slider');
$other_agents_item_amount = ere_get_option('other_agents_item_amount', 12);
$other_agent_image_size = ere_get_option('other_agent_image_size', '270x340');
$other_agent_show_paging = ere_get_option('other_agent_show_paging', array());

$other_agent_column_lg = ere_get_option('other_agent_column_lg', '4');
$other_agent_column_md = ere_get_option('other_agent_column_md', '3');
$other_agent_column_xs = ere_get_option('other_agent_column_sm', '2');
$other_agent_column_sm = ere_get_option('other_agent_column_xs', '2');
$other_agent_column_mb = ere_get_option('other_agent_column_mb', '1');

if (!is_array($other_agent_show_paging)) {
	$other_agent_show_paging = array();
}
if (in_array("show_paging_other_agent", $other_agent_show_paging)) {
	$agent_show_paging = 'true';
} else {
	$agent_show_paging = '';
}

if ($other_agent_layout_style == 'agent-slider') {
	$agent_show_paging = '';
}

$agencies = ere_get_option('agent_agencies', '');
if (!empty($agencies)) {
	$agencies = implode(",", $agencies);
}
$agent_shortcode = '[ere_agent agencies = "' . $agencies . '" layout_style = "' . $other_agent_layout_style . '"
    item_amount="' . $other_agents_item_amount . '" items="' . $other_agent_column_lg . '"
    items_md="' . $other_agent_column_md . '" items_sm="' . $other_agent_column_sm . '" 
    items_xs="' . $other_agent_column_xs . '" items_mb="' . $other_agent_column_mb . '" 
    image_size="' . $other_agent_image_size . '" show_paging = "' . $agent_show_paging . '"
    post_not_in = "' . get_the_ID() . '"]';
?>
<div class="agent-other">
	<div class="agent-other-inner container">
		<div class="ere-heading text-center mg-bottom-60">
			<span></span>
			<h2 class="uppercase"><?php esc_html_e('other agents', 'essential-real-estate'); ?></h2>
		</div>
		<?php echo do_shortcode($agent_shortcode); ?>
	</div>
</div>