<?php
/**
 * @var $property
 */
?>
    <div class="property-submitted-content">
        <div class="ere-message alert alert-success" role="alert">
            <?php
            switch ($property->post_status) :
                case 'publish' :
                    printf(__('<strong>Success!</strong> Your property was submitted successfully. To view your property listing <a class="accent-color" href="%s">click here</a>.', 'essential-real-estate'), get_permalink($property->ID));
                    break;
                case 'pending' :
                    printf(__('<strong>Success!</strong> Your property was submitted successfully. Once approved, your listing will be visible on the site.', 'essential-real-estate'), get_permalink($property->ID));
                    break;
                default :
                    do_action('ere_property_submitted_content_' . str_replace('-', '_', sanitize_title($property->post_status)), $property);
                    break;
            endswitch;
            ?></div>
        <a class="btn btn-primary" href="<?php echo ere_get_permalink('my_properties'); ?>"
           title="<?php esc_html_e('Go to Dashboard', 'essential-real-estate') ?>"><?php esc_html_e('Return to Your Dashboard', 'essential-real-estate') ?></a>
    </div>
<?php
do_action('ere_property_submitted_content_after', sanitize_title($property->post_status), $property);