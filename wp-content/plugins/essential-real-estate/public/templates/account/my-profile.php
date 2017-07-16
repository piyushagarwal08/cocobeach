<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/11/16
 * Time: 5:11 PM
 */
if (!is_user_logged_in()) {
    echo ere_get_template_html('global/dashboard-login.php');
    return;
}
global $current_user;
wp_get_current_user();
$user_id = $current_user->ID;
$user_login = $current_user->user_login;
$user_firstname = get_the_author_meta('first_name', $user_id);
$user_lastname = get_the_author_meta('last_name', $user_id);
$user_email = get_the_author_meta('user_email', $user_id);
$user_mobile_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_mobile_number', $user_id);
$user_fax_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_fax_number', $user_id);
$user_company = get_the_author_meta(ERE_METABOX_PREFIX . 'author_company', $user_id);
$user_licenses = get_the_author_meta(ERE_METABOX_PREFIX . 'author_licenses', $user_id);
$user_office_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_number', $user_id);
$user_office_address = get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_address', $user_id);
$user_des = get_the_author_meta('description', $user_id);
$user_facebook_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_facebook_url', $user_id);
$user_twitter_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_twitter_url', $user_id);
$user_linkedin_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_linkedin_url', $user_id);
$user_pinterest_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_pinterest_url', $user_id);
$user_instagram_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_instagram_url', $user_id);
$user_googleplus_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_googleplus_url', $user_id);
$user_youtube_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_youtube_url', $user_id);
$user_vimeo_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_vimeo_url', $user_id);
$user_skype = get_the_author_meta(ERE_METABOX_PREFIX . 'author_skype', $user_id);
$user_website_url = get_the_author_meta('user_url', $user_id);

$user_position = get_the_author_meta(ERE_METABOX_PREFIX . 'author_position', $user_id);
$user_custom_picture = get_the_author_meta(ERE_METABOX_PREFIX . 'author_custom_picture', $user_id);
$author_picture_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_picture_id', $user_id);
if (empty($user_custom_picture)) {
    $user_custom_picture = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
}
$current_user_meta = get_user_meta($user_id);
$user_data = get_userdata($user_id);
$role = $user_data->roles[0];
$user_as_agent = ere_get_option('user_as_agent', 1);
$user_can_submit = ere_get_option('user_can_submit', 1);
$paid_submission_type = ere_get_option('paid_submission_type', 'no');
wp_enqueue_script('plupload');
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'profile');
ere_get_template('global/dashboard-menu.php', array('cur_menu' => 'my_profile'));
?>
<div class="row ere-my-profile-wrap">
    <div class="col-md-9 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading"><?php esc_html_e('Information', 'essential-real-estate'); ?></div>
            <div class="panel-body profile-wrap update-profile">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <div id="user-profile-img">
                            <div class="profile-thumb text-center">
                                <?php
                                if (!empty($author_picture_id)) {
                                    $author_picture_id = intval($author_picture_id);
                                    if ($author_picture_id) {
                                        echo wp_get_attachment_image($author_picture_id);
                                        echo '<input type="hidden" class="profile-pic-id" id="profile-pic-id" name="profile-pic-id" value="' . esc_attr($author_picture_id) . '"/>';
                                    }
                                } else {
                                    print '<img width="' . get_option('thumbnail_size_w') . '" height="' . get_option('thumbnail_size_h') . '" id="profile-image" src="' . esc_url($user_custom_picture) . '" alt="user image" >';
                                }
                                ?>
                            </div>
                        </div>

                        <div class="profile-img-controls">
                            <div id="errors-log"></div>
                        </div>
                        <div id="ere-profile-plupload-container">
                            <a id="select-profile-image" class="btn btn-primary"
                               href="javascript:;"><?php esc_html_e('Update Profile Picture', 'essential-real-estate'); ?></a>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label
                                        for="user_firstname"><?php esc_html_e('First Name', 'essential-real-estate'); ?></label>
                                    <input type="text" name="user_firstname" id="user_firstname" class="form-control"
                                           value="<?php echo esc_attr($user_firstname); ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label
                                        for="user_lastname"><?php esc_html_e('Last Name', 'essential-real-estate'); ?></label>
                                    <input type="text" name="user_lastname" id="user_lastname" class="form-control"
                                           value="<?php echo esc_attr($user_lastname); ?>">
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label
                                        for="user_email"><?php esc_html_e('Email', 'essential-real-estate'); ?></label>
                                    <input type="text" name="user_email" id="user_email" class="form-control"
                                           value="<?php echo esc_attr($user_email); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

                <div class="form-group">
                    <label for="user_des"><?php esc_html_e('About me', 'essential-real-estate'); ?></label>
                            <textarea id="user_des" class="form-control"
                                      rows="5"><?php echo esc_attr($user_des); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_mobile_number"><?php esc_html_e('Mobile', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_mobile_number" class="form-control"
                                   value="<?php echo esc_attr($user_mobile_number); ?>" name="usermobile">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_fax_number"><?php esc_html_e('Fax', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_fax_number" class="form-control"
                                   value="<?php echo esc_attr($user_fax_number); ?>" name="usermobile">
                        </div>
                    </div>
                </div>
                <?php if(ere_is_agent()):?>
                <div class="row ere-agent-company">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label
                                for="user_company"><?php esc_html_e('Company', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_company" class="form-control"
                                   value="<?php echo esc_attr($user_company); ?>" name="userphone">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_position"><?php esc_html_e('Position', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_position" name="title"
                                   value="<?php echo esc_attr($user_position); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_office_number"><?php esc_html_e('Office Number', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_office_number" class="form-control"
                                   value="<?php echo esc_attr($user_office_number); ?>" name="userphone">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_office_address"><?php esc_html_e('Office Address', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_office_address" class="form-control"
                                   value="<?php echo esc_attr($user_office_address); ?>" name="userphone">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_licenses"><?php esc_html_e('Licenses', 'essential-real-estate'); ?></label>
                            <input type="text" name="user_licenses" id="user_licenses" class="form-control"
                                   value="<?php echo esc_attr($user_licenses); ?>">
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="user_skype"><?php esc_html_e('Skype', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_skype" class="form-control"
                                   value="<?php echo esc_attr($user_skype); ?>" name="userskype">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_facebook_url"><?php esc_html_e('Facebook URL', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_facebook_url" name="facebook"
                                   value="<?php echo esc_url($user_facebook_url); ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_twitter_url"><?php esc_html_e('Twitter URL', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_twitter_url" class="form-control"
                                   value="<?php echo esc_url($user_twitter_url); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_linkedin_url"><?php esc_html_e('Linkedin URL', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_linkedin_url" class="form-control"
                                   value="<?php echo esc_url($user_linkedin_url); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_instagram_url"><?php esc_html_e('Instagram URL', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_instagram_url" class="form-control"
                                   value="<?php echo esc_url($user_instagram_url); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_pinterest_url"><?php esc_html_e('Pinterest Url', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_pinterest_url" class="form-control"
                                   value="<?php echo esc_url($user_pinterest_url); ?>" name="pinterest">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_googleplus_url"><?php esc_html_e('Google Plus Url', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_googleplus_url" class="form-control"
                                   value="<?php echo esc_url($user_googleplus_url); ?>" name="googleplus">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_youtube_url"><?php esc_html_e('Youtube Url', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_youtube_url" class="form-control"
                                   value="<?php echo esc_url($user_youtube_url); ?>" name="youtube">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_vimeo_url"><?php esc_html_e('Vimeo Url', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_vimeo_url" class="form-control"
                                   value="<?php echo esc_url($user_vimeo_url); ?>" name="vimeo">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label
                                for="user_website_url"><?php esc_html_e('Website URL', 'essential-real-estate'); ?></label>
                            <input type="text" id="user_website_url" class="form-control"
                                   value="<?php echo esc_url($user_website_url); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <input type="hidden" name="ere_account_id" id="ere_account_id" value="<?php echo $user_id; ?>">
                        <?php wp_nonce_field('ere_delete_profile_ajax_nonce', 'ere_security_delete_profile'); ?>
                        <button class="btn btn-danger btn-dark"
                                id="ere_delete_account"> <?php esc_html_e('Delete My Account', 'essential-real-estate'); ?> </button>
                    </div>
                    <div class="col-sm-6 text-right">
                        <?php wp_nonce_field('ere_update_profile_ajax_nonce', 'ere_security_update_profile'); ?>
                        <button class="btn btn-primary"
                                id="ere_update_profile"><?php esc_html_e('Update Profile', 'essential-real-estate'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    if ($user_as_agent == 1 && $user_can_submit == 1) {
        $is_agent = ere_is_agent();
        $heading = $message = '';
        if (!$is_agent) {
            $heading = esc_html__('Agent Account', 'essential-real-estate');
            $become_agent_terms_condition = ere_get_option('become_agent_terms_condition');
            $message = sprintf(wp_kses(__('If you want to become an agent, please read our <a class="accent-color" target="_blank" href="%s">Terms & Conditions</a> first', 'essential-real-estate'), array(
                'a' => array(
                    'target' => array(),
                    'class' => array(),
                    'href' => array()
                )
            )), get_permalink($become_agent_terms_condition));
        } else {
            $heading = esc_html__('Remove Agent Account', 'essential-real-estate');
            $message = esc_html__('Your current account type is set to agent, if you want to remove your agent account, and return to normal account, you must click the button below', 'essential-real-estate');
        }
        ?>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo($heading); ?> </div>
                <div class="panel-body">
                    <div class="form-group">
                        <p class="ere-message alert alert-success"><?php echo($message); ?></p>

                        <div class="form-group">
                            <?php if (!$is_agent): ?>
                                <?php wp_nonce_field('ere_become_agent_ajax_nonce', 'ere_security_become_agent'); ?>
                                <button type="button" class="btn btn-primary btn-block"
                                        id="ere_user_as_agent"><?php esc_html_e('Become an Agent', 'essential-real-estate'); ?></button>

                            <?php else: ?>
                                <?php wp_nonce_field('ere_leave_agent_ajax_nonce', 'ere_security_leave_agent'); ?>
                                <button type="button" class="btn btn-primary btn-block"
                                        id="ere_leave_agent"><?php esc_html_e('Remove Agent Account', 'essential-real-estate'); ?></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    if ($paid_submission_type == 'per_package' && $user_can_submit == 1): ?>
        <div class="col-md-3 col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading"><?php esc_html_e('My Listing Package', 'essential-real-estate'); ?></div>
                <?php ere_get_template('widgets/my-package/my-package.php'); ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-md-3 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading"><?php esc_html_e('Change password', 'essential-real-estate'); ?></div>
            <div class="panel-body profile-wrap change-password">
                <div id="password_reset_msgs" class="ere_messages message"></div>
                <div class="form-group">
                    <label for="oldpass"><?php esc_html_e('Old Password', 'essential-real-estate'); ?></label>
                    <input id="oldpass" value="" class="form-control" name="oldpass" type="password">
                </div>
                <div class="form-group">
                    <label for="newpass"><?php esc_html_e('New Password ', 'essential-real-estate'); ?></label>
                    <input id="newpass" value="" class="form-control" name="newpass" type="password">
                </div>
                <div class="form-group">
                    <label
                        for="confirmpass"><?php esc_html_e('Confirm New Password', 'essential-real-estate'); ?></label>
                    <input id="confirmpass" value="" class="form-control" name="confirmpass" type="password">
                </div>
                <div class="form-group">
                    <?php wp_nonce_field('ere_change_password_ajax_nonce', 'ere_security_change_password'); ?>
                    <button class="btn btn-primary btn-block"
                            id="ere_change_pass"><?php esc_html_e('Update Password', 'essential-real-estate'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>