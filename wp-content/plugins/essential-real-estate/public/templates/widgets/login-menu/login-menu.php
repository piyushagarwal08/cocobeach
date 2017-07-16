<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 15/12/2016
 * Time: 10:59 SA
 */
if(!is_user_logged_in()):?>
    <a class="login-link topbar-link" data-toggle="modal" data-target="#ere_signin_modal"><i class="fa fa-user accent-color"></i><span class="hidden-xs"><?php esc_html_e('Login or Register','essential-real-estate') ?></span></a>
<?php else:
    global $current_user;
    wp_get_current_user();
    $user_login = $current_user->user_login;
    $user_id = $current_user->ID;
    $user_can_submit = ere_get_option('user_can_submit', 1);
    $cur_menu='';
    ?>
    <div class="user-dropdown">
        <span class="user-display-name"><i class="fa fa-user accent-color"></i><span class="hidden-xs"><?php echo $user_login; ?></span></span>
        <ul class="user-dropdown-menu">
            <?php if ($permalink = ere_get_permalink('my_profile')) : ?>
                <li<?php if ($cur_menu == 'my_profile') echo ' class="active"' ?>>
                    <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-info-circle accent-color"></i><?php esc_html_e('My Profile', 'essential-real-estate'); ?></a>
                </li>
            <?php endif; ?>
            <?php if ($user_can_submit==1) : ?>
                <?php if ($permalink = ere_get_permalink('my_properties')) : ?>
                    <li<?php if ($cur_menu == 'my_properties') echo ' class="active"' ?>>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-list-alt accent-color"></i><?php esc_html_e('My Properties ', 'essential-real-estate');?></a>
                    </li>
                <?php endif; ?>
                <?php if ($permalink = ere_get_permalink('my_invoices')) : ?>
                    <li<?php if ($cur_menu == 'my_invoices') echo ' class="active"' ?>>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-credit-card accent-color"></i><?php esc_html_e('My Invoices ', 'essential-real-estate'); ?></a>
                    </li>
                <?php endif; ?>
                <?php if ($permalink = ere_get_permalink('submit_property')) : ?>
                    <li>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-plus-circle accent-color"></i><?php esc_html_e('Add New Property', 'essential-real-estate'); ?></a></li>
                <?php endif; ?>
            <?php endif;
            $enable_favorite = ere_get_option('enable_favorite_property', 1);
            if($enable_favorite==1):?>
                <?php if ($permalink = ere_get_permalink('my_favorites')) : ?>
                    <li<?php if ($cur_menu == 'my_favorites') echo ' class="active"' ?>>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-heart accent-color"></i><?php esc_html_e('My Favorites ', 'essential-real-estate');?></a>
                    </li>
                <?php endif;
            endif;
            $enable_saved_search = ere_get_option('enable_saved_search', 1);
            if($enable_saved_search==1):
                if ($permalink = ere_get_permalink('my_save_search')) : ?>
                    <li<?php if ($cur_menu == 'my_save_search') echo ' class="active"' ?>>
                        <a href="<?php echo esc_url($permalink); ?>"><i class="fa fa-search accent-color"></i><?php esc_html_e('My Saved Searches', 'essential-real-estate'); ?></a>
                    </li>
            <?php endif;
            endif; ?>
            <li>
                <?php $permalink=get_permalink(); ?>
                <a href="<?php echo wp_logout_url( $permalink ); ?>"><i class="fa fa-sign-out accent-color"></i><?php esc_html_e('Logout', 'essential-real-estate');?></a>
            </li>
        </ul>
    </div>
<?php endif;?>