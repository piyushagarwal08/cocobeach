<?php
global $post;
?>
<div class="property-info-footer">
	<span class="property-date">
		<i class="fa fa-calendar accent-color"></i> <?php echo get_the_time(get_option('date_format')); ?>
	</span>
	<span class="property-views-count">
		<i class="fa fa-eye accent-color"></i>
		<?php
		$ere_property=new ERE_Property();
		$total_views= $ere_property->get_total_views($post->ID);
		if($total_views<2)
		{
			printf(esc_html__('%s view','essential-real-estate'),$total_views);
		}
		else
		{
			printf(esc_html__('%s views','essential-real-estate'),$total_views);
		}
		?>
	</span>
</div>