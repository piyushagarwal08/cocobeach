<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:46 PM
 */
global $property_data, $property_meta_data, $hide_property_fields;
$auto_property_id = ere_get_option('auto_property_id',0);
$measurement_units = ere_get_option('measurement_units','SqFt');
$additional_features = get_post_meta($property_data->ID, ERE_METABOX_PREFIX . 'additional_features', true);
$additional_feature_title = get_post_meta($property_data->ID, ERE_METABOX_PREFIX . 'additional_feature_title', true);
$additional_feature_value = get_post_meta($property_data->ID, ERE_METABOX_PREFIX . 'additional_feature_value', true);
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
        <h2><?php esc_html_e( 'Property Details', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-detail row">
        <?php if (!in_array("property_size", $hide_property_fields)) {?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_size"><?php printf(__('Area Size ( %s ) %s', 'essential-real-estate'),$measurement_units,ere_required_field('property_size')); ?></label>
                    <input type="text" id="property_size" class="form-control" name="property_size"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_size'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_size'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_land", $hide_property_fields)) {?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_land"><?php printf(__('Land Area ( %s ) %s', 'essential-real-estate'),$measurement_units, ere_required_field('property_land')); ?></label>
                    <input type="text" id="property_land" class="form-control" name="property_land"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_land'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_land'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_bedrooms", $hide_property_fields)) {?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_bedrooms"><?php echo esc_html__('Bedrooms', 'essential-real-estate') . ere_required_field('property_bedrooms'); ?></label>
                    <input type="text" id="property_bedrooms" class="form-control" name="property_bedrooms"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_bedrooms'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_bathrooms", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_bathrooms"><?php echo esc_html__('Bathrooms', 'essential-real-estate') . ere_required_field('property_bathrooms'); ?></label>
                    <input type="text" id="property_bathrooms" class="form-control" name="property_bathrooms"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_bathrooms'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_garage", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_garage"><?php echo esc_html__('Garages', 'essential-real-estate') . ere_required_field('property_garage'); ?></label>
                    <input type="text" id="property_garage" class="form-control" name="property_garage"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_garage'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_garage_size", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="property_garage_size"><?php printf(__('Garages Size ( %s )', 'essential-real-estate'),$measurement_units); ?></label>
                    <input type="text" id="property_garage_size" class="form-control" name="property_garage_size"
                           value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_garage_size'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("property_year", $hide_property_fields)) { ?>
        <div class="col-sm-3">
            <div class="form-group">
                <label
                    for="property_year"><?php echo esc_html__('Year Built', 'essential-real-estate') . ere_required_field('property_year'); ?></label>
                <input type="text" id="property_year" class="form-control" name="property_year"
                       value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_year'])) {
                           echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_year'][0]);
                       } ?>">
            </div>
        </div>
        <?php } ?>
        <?php if (!in_array("property_identity", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="property_identity"><?php esc_html_e('Property ID', 'essential-real-estate'); ?></label>
                    <input type="text" class="form-control" name="property_identity" id="property_identity" value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_identity'])) {
                               echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_identity'][0]);
                           } ?>">
                </div>
            </div>
        <?php } ?>
    </div>
    <?php if (!in_array("additional_details", $hide_property_fields)) { ?>
        <div class="add-tab-row">
            <h4><?php esc_html_e('Additional  details', 'essential-real-estate'); ?></h4>
            <table class="additional-block">
                <thead>
                <tr>
                    <td>&nbsp</td>
                    <td><label><?php esc_html_e('Title', 'essential-real-estate'); ?></label></td>
                    <td><label><?php esc_html_e('Value', 'essential-real-estate'); ?></label></td>
                    <td>&nbsp</td>
                </tr>
                </thead>
                <tbody id="ere_additional_details">
                <?php
                if (!empty($additional_features)) {
                    for ($i = 0; $i < $additional_features; $i++) { ?>
                        <tr>
                            <td>
                                <span class="sort-additional-row"><i class="fa fa-navicon"></i></span>
                            </td>
                            <td>
                                <input class="form-control" type="text"
                                       name="additional_feature_title[<?php echo esc_attr($i); ?>]"
                                       id="additional_feature_title_<?php echo esc_attr($i); ?>"
                                       value="<?php echo esc_attr($additional_feature_title[$i]); ?>">
                            </td>
                            <td>
                                <input class="form-control" type="text"
                                       name="additional_feature_value[<?php echo esc_attr($i); ?>]"
                                       id="additional_feature_value_<?php echo esc_attr($i); ?>"
                                       value="<?php echo esc_attr($additional_feature_value[$i]); ?>">
                            </td>

                            <td>
                                    <span data-remove="<?php echo esc_attr($i); ?>" class="remove-additional-feature"><i
                                            class="fa fa-remove"></i></span>
                            </td>
                        </tr>
                    <?php }; ?>
                <?php } ?>

                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td colspan="3">
                        <button data-increment="<?php echo esc_attr($additional_features - 1); ?>"
                                class="add-additional-feature"><i
                                class="fa fa-plus"></i> <?php esc_html_e('Add New', 'essential-real-estate'); ?></button>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    <?php } ?>
</div>