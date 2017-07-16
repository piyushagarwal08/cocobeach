<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:46 PM
 */
global $hide_property_fields;
$measurement_units = ere_get_option('measurement_units', 'SqFt');
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
        <h2><?php esc_html_e('Property Details', 'essential-real-estate'); ?></h2>
    </div>
    <div class="property-fields property-detail row">
        <?php if (!in_array("property_size", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_size"><?php printf(__('Area Size ( %s ) %s', 'essential-real-estate'), $measurement_units, ere_required_field('property_size')); ?></label>
                    <input type="text" id="property_size" class="form-control" name="property_size" value="">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_land", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_land"><?php printf(__('Land Area ( %s ) %s', 'essential-real-estate'), $measurement_units, ere_required_field('property_land')); ?></label>
                    <input type="text" id="property_land" class="form-control" name="property_land" value="">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_bedrooms", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_bedrooms"><?php echo esc_html__('Bedrooms', 'essential-real-estate') . ere_required_field('property_bedrooms'); ?></label>
                    <input type="text" id="property_bedrooms" class="form-control" name="property_bedrooms" value="">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_bathrooms", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_bathrooms"><?php echo esc_html__('Bathrooms', 'essential-real-estate') . ere_required_field('property_bathrooms'); ?></label>
                    <input type="text" id="property_bathrooms" class="form-control" name="property_bathrooms" value="">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_garage", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_garage"><?php echo esc_html__('Garages', 'essential-real-estate') . ere_required_field('property_garage'); ?></label>
                    <input type="text" id="property_garage" class="form-control" name="property_garage" value="">
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_garage_size", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_garage_size"><?php printf(__('Garages Size ( %s )', 'essential-real-estate'), $measurement_units); ?></label>
                    <input type="text" id="property_garage_size" class="form-control" name="property_garage_size"
                           value="">
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("property_year", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label
                        for="property_year"><?php echo esc_html__('Year Built', 'essential-real-estate') . ere_required_field('property_year'); ?></label>
                    <input type="text" id="property_year" class="form-control" name="property_year" value="">
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("property_identity", $hide_property_fields)) { ?>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="property_identity"><?php esc_html_e('Property ID', 'essential-real-estate'); ?></label>
                    <input type="text" class="form-control" name="property_identity" id="property_identity">
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
                    <td></td>
                    <td><label><?php esc_html_e('Title', 'essential-real-estate'); ?></label></td>
                    <td><label><?php esc_html_e('Value', 'essential-real-estate'); ?></label></td>
                    <td></td>
                </tr>
                </thead>
                <tbody id="ere_additional_details">
                </tbody>
                <tfoot>
                <tr>
                    <td></td>
                    <td colspan="3">
                        <button data-increment="-1" class="add-additional-feature"><i
                                class="fa fa-plus"></i> <?php esc_html_e('Add New', 'essential-real-estate'); ?></button>
                    </td>
                </tr>
                </tfoot>
            </table>

        </div>
    <?php } ?>
</div>
