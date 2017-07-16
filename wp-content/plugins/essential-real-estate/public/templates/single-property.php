<?php
get_header('ere');
?>
<div class="ere-property-wrap">
    <div class="single-property-area">
        <?php
        do_action('ere_single_property_before_main_content');
        if (have_posts()):
            while (have_posts()): the_post(); ?>
                <?php ere_get_template_part('content', 'single-property'); ?>
            <?php endwhile;
        endif;
        do_action('ere_single_property_after_main_content');
        ?>
    </div>
</div>
<?php
/**
 * ere_sidebar_property hook.
 *
 * @hooked ere_sidebar_property - 10
 */
do_action('ere_sidebar_property');
get_footer('ere');
