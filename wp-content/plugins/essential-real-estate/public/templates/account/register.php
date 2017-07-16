<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 01/11/16
 * Time: 5:11 PM
 */
$register_terms_condition = ere_get_option('register_terms_condition');
$enable_password = ere_get_option('enable_password',0);
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'register');
?>
<div class="ere-register-wrap">
    <div id="ere_messages_register" class="ere_messages message"></div>
    <form>
        <div class="form-group control-username">
            <input id="register_username" name="username" class="form-control control-icon" type="text"
                   placeholder="<?php esc_html_e('Username', 'essential-real-estate'); ?>"/>
        </div>
        <div class="form-group control-email">
            <input id="useremail" name="useremail" type="email" class="form-control control-icon"
                   placeholder="<?php esc_html_e('Email', 'essential-real-estate'); ?>"/>
        </div>

        <?php if ($enable_password) { ?>
            <div class="form-group control-password">
                <input id="register_pass" name="register_pass" class="form-control control-icon"
                       placeholder="<?php esc_html_e('Password', 'essential-real-estate'); ?>" type="password"/>
            </div>
            <div class="form-group control-ere-password">
                <input id="register_pass_retype" name="register_pass_retype" class="form-control control-icon"
                       placeholder="<?php esc_html_e('Retype Password', 'essential-real-estate'); ?>" type="password"/>
            </div>
        <?php } ?>
        <div class="form-group control-term-condition">
            <div class="checkbox">
                <label>
                    <input name="term_condition" id="term_condition" type="checkbox">
                    <?php echo sprintf(wp_kses(__('I agree with your <a target="_blank" href="%s">Terms & Conditions</a>', 'essential-real-estate'), array(
                        'a' => array(
                            'target'=> array(),
                            'href' => array()
                        )
                    )), get_permalink($register_terms_condition)); ?>
                </label>
            </div>
        </div>
        <?php wp_nonce_field('ere_register_nonce', 'ere_register_security'); ?>
        <input type="hidden" name="action" value="ere_register_ajax" id="register_action">
        <button type="submit"
                class="ere-register-button btn btn-primary btn-block"><?php esc_html_e('Register', 'essential-real-estate'); ?></button>
    </form>
</div>
