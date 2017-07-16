<?php
/**
 * @var $form
 * @var $action
 * @var $property_id
 * @var $submit_button_text
 * @var $step
 */
if (!defined('ABSPATH')) exit;
$user_can_submit = ere_get_option('user_can_submit', 1);
if ($user_can_submit!=1) {
    wp_redirect(home_url());
}
if(!is_user_logged_in()){
    echo ere_get_template_html('global/dashboard-login.php');
    return;
}
global $property_data, $property_meta_data, $hide_property_fields, $current_user;
$hide_property_fields = ere_get_option('hide_property_fields', array());
if (!is_array($hide_property_fields)) {
    $hide_property_fields = array();
}
if ($form == 'edit-property') {
    $property_data = get_post($property_id);
    $property_meta_data = get_post_custom($property_data->ID);
} else {
    $paid_submission_type = ere_get_option('paid_submission_type','no');
    if ($paid_submission_type == 'per_package') {
        wp_get_current_user();
        $user_id = $current_user->ID;
        $ere_profile=new ERE_Profile();
        $check_package=$ere_profile->user_package_available($user_id);
        $select_packages_link = ere_get_permalink('packages');
        if ($check_package==0) {
            print '<div class="ere-message alert alert-warning" role="alert">' . esc_html__('You are not yet subscribed to a listing! Before you can list a property, you must select a listing package. Click the button below to select a listing package.', 'essential-real-estate') . ' </div>
                   <a class="btn btn-default" href="' . $select_packages_link . '">' . esc_html__('Get a Listing Package', 'essential-real-estate') . '</a>';
            return;
        } elseif($check_package==-1) {
            print '<div class="ere-message alert alert-warning" role="alert">' . esc_html__('Your current listing package has expired! Please click the button below to select a new listing package.', 'essential-real-estate') . '</div>
                   <a class="btn btn-default" href="' . $select_packages_link . '">' . esc_html__('Upgrade Listing Package', 'essential-real-estate') . '</a>';
            return;
        } elseif($check_package==-2) {
            print '<div class="ere-message alert alert-warning" role="alert">' . esc_html__('Your current listing package doesn\'t allow you to publish any more properties! Please click the button below to select a new listing package.', 'essential-real-estate') . '</div>
                   <a class="btn btn-default" href="' . $select_packages_link . '">' . esc_html__('Upgrade Listing Package', 'essential-real-estate') . '</a>';
            return;
        }
    }
}
wp_enqueue_script('plupload');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('jquery-geocomplete');
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'property');
?>
<form action="<?php echo esc_url($action); ?>" method="post" id="submit_property_form" class="property-manager-form"
      enctype="multipart/form-data">
    <?php do_action('ere_before_submit_property'); ?>
    <?php
    $layout = ere_get_option('property_form_sections', array('title_des', 'location', 'type', 'price', 'features', 'details', 'media', 'floors', 'contact'));
    if ($layout): foreach ($layout as $value) {
        switch ($value) {
            case 'title_des':
                ere_get_template('property/' . $form . '/title-des.php');
                break;
            case 'location':
                ere_get_template('property/' . $form . '/location.php');
                break;
            case 'type':
                ere_get_template('property/' . $form . '/type.php');
                break;
            case 'price':
                ere_get_template('property/' . $form . '/price.php');
                break;
            case 'features':
                ere_get_template('property/' . $form . '/features.php');
                break;
            case 'details':
                ere_get_template('property/' . $form . '/details.php');
                break;
            case 'media':
                ere_get_template('property/' . $form . '/media.php');
                break;
            case 'floors':
                ere_get_template('property/' . $form . '/floors.php');
                break;
            case 'contact':
                ere_get_template('property/' . $form . '/contact.php');
                break;
        }
    }
    endif;
    ?>
    <?php do_action('ere_after_submit_property'); ?>
    <p>
        <?php wp_nonce_field('ere_submit_property_action', 'ere_submit_property_nonce_field'); ?>
        <input type="hidden" name="property_form" value="<?php echo esc_attr($form); ?>"/>
        <input type="hidden" name="property_action" value="<?php echo esc_attr($action) ?>"/>
        <input type="hidden" name="property_id" value="<?php echo esc_attr($property_id); ?>"/>
        <input type="hidden" name="step" value="<?php echo esc_attr($step); ?>"/>
        <input type="submit" name="submit_property" class="button"
               value="<?php esc_attr_e($submit_button_text); ?>"/>
    </p>
</form>