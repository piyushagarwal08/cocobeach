<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/01/16
 * Time: 5:11 PM
 */
wp_enqueue_script( ERE_PLUGIN_PREFIX . 'login');
?>
<div class="ere-login-wrap">
    <div id="ere_messages_login" class="ere_messages message"></div>
    <form>
        <div class="form-group control-username">
            <input id="login_username" name="username" class="form-control control-icon"
                   placeholder="<?php esc_html_e('Username', 'essential-real-estate'); ?>" type="text"/>
        </div>
        <div class="form-group control-password">
            <input id="password" name="password" class="form-control control-icon"
                   placeholder="<?php esc_html_e('Password', 'essential-real-estate'); ?>" type="password"/>
        </div>
        <div class="checkbox">
            <label>
                <input name="remember" id="remember" type="checkbox">
                <?php esc_html_e('Remember me', 'essential-real-estate'); ?>
            </label>
        </div>
        <?php wp_nonce_field('ere_login_nonce_ajax', 'ere_security_login'); ?>
        <input type="hidden" name="action" id="login_action" value="ere_login_ajax">
        <a href="javascript:;" class="ere-reset-password"><?php esc_html_e('Lost password','essential-real-estate')?></a>
        <button type="submit"
                class="ere-login-button btn btn-primary btn-block"><?php esc_html_e('Login', 'essential-real-estate'); ?></button>
    </form>
    <hr>
    <?php if( has_action('wordpress_social_login') ){ do_action( 'wordpress_social_login' ); } ?>
</div>
<div class="ere-reset-password-wrap" style="display: none">
    <div id="ere_msg_reset" class="ere_messages message"></div>
    <form>
        <div class="form-group control-username">
            <input name="username_or_email" id="username_or_email" class="form-control control-icon"
                   placeholder="<?php esc_html_e('Enter your username or email', 'essential-real-estate'); ?>">
            <?php wp_nonce_field('ere_reset_password_ajax_nonce', 'ere_security_reset_password'); ?>
            <button type="button" id="ere_forgetpass"
                    class="btn btn-primary btn-block"><?php esc_html_e('Get new password', 'essential-real-estate'); ?></button>
        </div>
    </form>
    <a href="javascript:;" class="ere-back-to-login"><?php esc_html_e('Back to Login','essential-real-estate')?></a>
</div>