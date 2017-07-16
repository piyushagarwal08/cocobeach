<?php
/**
 * @var $cur_menu
 * @var $max_num_pages
 */
global $current_user;
wp_get_current_user();
$user_login = $current_user->user_login;
$user_id = $current_user->ID;
$ere_property=new ERE_Property();
$total_properties = $ere_property->get_total_my_properties(array('publish', 'pending', 'expired'));
$ere_invoice=new ERE_Invoice();
$total_invoices = $ere_invoice->get_total_my_invoice();
$total_favorite=$ere_property->get_total_favorite();
$ere_save_search= new ERE_Save_Search();
$total_save_search=$ere_save_search->get_total_save_search();
$user_can_submit = ere_get_option('user_can_submit', 1);
?>
<div class="row">
    <div class="col-sm-6">
        <h4 class="ere-dashboard-title"><?php echo sprintf(__('Welcome: %s', 'essential-real-estate'),$user_login);?></h4>
    </div>
    <div class="col-sm-6 text-right">
        <?php if ($permalink = ere_get_permalink('submit_property')) :
            if($user_can_submit==1):?>
            <a class="btn btn-default"
               href="<?php echo esc_url($permalink); ?>"><?php esc_html_e('Add New Property', 'essential-real-estate'); ?></a>
        <?php endif; endif; ?>
    </div>
</div>
<ul class="nav nav-tabs">
    <?php if ($permalink = ere_get_permalink('my_profile')) : ?>
        <li<?php if ($cur_menu == 'my_profile') echo ' class="active"' ?>>
            <a href="<?php echo esc_url($permalink); ?>"><?php esc_html_e('My Profile', 'essential-real-estate'); ?></a>
        </li>
    <?php endif; ?>
    <?php if ($user_can_submit==1) : ?>
        <?php if ($permalink = ere_get_permalink('my_properties')) : ?>
            <li<?php if ($cur_menu == 'my_properties') echo ' class="active"' ?>>
                <a href="<?php echo esc_url($permalink); ?>"><?php esc_html_e('My Properties ', 'essential-real-estate');
                    echo '<span class="badge">' . $total_properties . '</span>' ?></a>
            </li>
        <?php endif; ?>
        <?php if ($permalink = ere_get_permalink('my_invoices')) : ?>
            <li<?php if ($cur_menu == 'my_invoices') echo ' class="active"' ?>>
                <a href="<?php echo esc_url($permalink); ?>"><?php esc_html_e('My Invoices ', 'essential-real-estate');
                    echo '<span class="badge">' . $total_invoices . '</span>' ?></a>
            </li>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    $enable_favorite = ere_get_option('enable_favorite_property', 1);
    if($enable_favorite==1):?>
        <?php if ($permalink = ere_get_permalink('my_favorites')) : ?>
            <li<?php if ($cur_menu == 'my_favorites') echo ' class="active"' ?>>
                <a href="<?php echo esc_url($permalink); ?>"><?php esc_html_e('My Favorites ', 'essential-real-estate');
                        echo '<span class="badge">' .$total_favorite . '</span>'; ?></a>
            </li>
        <?php endif;
    endif;
    $enable_saved_search = ere_get_option('enable_saved_search', 1);
    if($enable_saved_search==1):?>
        <?php if ($permalink = ere_get_permalink('my_save_search')) : ?>
            <li<?php if ($cur_menu == 'my_save_search') echo ' class="active"' ?>>
                <a href="<?php echo esc_url($permalink); ?>"><?php esc_html_e('My Saved Searches ', 'essential-real-estate');
                    echo '<span class="badge">' .$total_save_search . '</span>'; ?></a>
            </li>
        <?php endif;?>
    <?php endif; ?>
</ul>