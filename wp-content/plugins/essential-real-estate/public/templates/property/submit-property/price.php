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
        <h2><?php esc_html_e( 'Property Price', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-price row">
        <?php
        if (!in_array("property_price", $hide_property_fields)) {
        ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="property_price"> <?php esc_html_e( 'Sale or Rent Price', 'essential-real-estate' ); echo ere_required_field( 'property_price' );
                        print esc_html(ere_get_option('currency_sign', '')) . ' ';?>  </label>
                    <input type="text" id="property_price" class="form-control" name="property_price" value="">
                </div>
            </div>
        <?php } ?>
        <?php
        if (!in_array("property_price_postfix", $hide_property_fields)) {
         ?>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="property_price_postfix"><?php esc_html_e( 'After Price Label (ex: monthly)', 'essential-real-estate' ); echo ere_required_field( 'property_price_postfix' ); ?></label>
                    <input type="text" id="property_price_postfix" class="form-control" name="property_price_postfix">
                </div>
            </div>
        <?php } ?>
    </div>
</div>
