<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:46 PM
 */
global $hide_property_fields;
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
        <h2><?php esc_html_e( 'Property Type', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-type row">
        <?php if (!in_array("property_type", $hide_property_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="property_type"><?php esc_html_e('Type', 'essential-real-estate');
                        echo ere_required_field('property_type'); ?></label>
                    <select name="property_type" id="property_type">
                        <?php ere_get_taxonomy('property-type'); ?>
                    </select>
                </div>
            </div>
        <?php } ?>
        <?php if (!in_array("property_status", $hide_property_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="property_status"><?php esc_html_e('Status', 'essential-real-estate');?></label>
                    <select name="property_status" id="property_status">
                        <?php ere_get_taxonomy('property-status',false,false); ?>
                    </select>
                </div>
            </div>
        <?php } ?>

        <?php if (!in_array("property_labels", $hide_property_fields)) {?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="property_labels"><?php esc_html_e('Label', 'essential-real-estate');
                        echo ere_required_field('property_labels'); ?></label>
                    <select name="property_labels" id="property_labels">
                        <?php ere_get_taxonomy('property-labels'); ?>
                    </select>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
