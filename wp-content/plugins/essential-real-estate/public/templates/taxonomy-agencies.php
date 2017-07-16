<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 07/02/2017
 * Time: 2:37 CH
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header('ere');
do_action( 'ere_taxonomy_agencies_before_main_content' );
?>
<div class="ere-agency-single-wrap">
    <div class="ere-agency-single">
        <div class="agency-single container">
            <?php
            /**
             * ere_taxonomy_agencies_before_summary hook.
             */
            do_action( 'ere_taxonomy_agencies_before_summary' );
            ?>
            <?php
            /**
             * ere_taxonomy_agencies_summary hook.
             *
             * @hooked taxonomy_agencies_detail - 10
             */
            do_action( 'ere_taxonomy_agencies_summary' ); ?>
            <?php
            /**
             * ere_taxonomy_agencies_after_summary hook.
             */
            do_action( 'ere_taxonomy_agencies_after_summary' );
            ?>

        </div>
    </div>
</div>
<?php wp_reset_postdata(); ?>

<?php
do_action( 'ere_taxonomy_agencies_after_main_content' );
get_footer('ere')?>