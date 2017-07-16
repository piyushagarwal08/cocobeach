<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/11/16
 * Time: 5:11 PM
 */
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'login');
?>
<div class="ere-resset-password-wrap">
    <div id="ere_messages_reset_password" class="ere_messages message"></div>
    <form>
        <div class="form-group control-username">
            <input name="username_or_email" id="username_or_email" class="form-control control-icon"
                   placeholder="<?php esc_html_e('Enter your username or email', 'essential-real-estate'); ?>">
            <?php wp_nonce_field('ere_reset_password_ajax_nonce', 'ere_security_reset_password'); ?>
            <button type="button" id="ere_forgetpass"
                    class="btn btn-primary btn-block"><?php esc_html_e('Get new password', 'essential-real-estate'); ?></button>
        </div>
    </form>
</div>
