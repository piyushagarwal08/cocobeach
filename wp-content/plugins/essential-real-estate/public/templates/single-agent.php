<?php
get_header('ere');
do_action( 'ere_single_agent_before_main_content' );
if (have_posts()):
	while (have_posts()): the_post(); ?>

		<?php ere_get_template_part( 'content', 'single-agent' ); ?>

	<?php endwhile;
endif;
do_action( 'ere_single_agent_after_main_content' );
/**
 * ere_sidebar_agent hook.
 *
 * @hooked ere_sidebar_agent - 10
 */
do_action('ere_sidebar_agent');
get_footer('ere');