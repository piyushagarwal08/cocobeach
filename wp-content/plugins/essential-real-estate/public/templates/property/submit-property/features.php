<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:46 PM
 */
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
        <h2><?php esc_html_e( 'Property Features', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-features row">
        <?php
        $property_features = get_categories(array(
            'hide_empty' => 0,
            'taxonomy'  => 'property-feature'
        ));
        if (!empty($property_features)) {
            $count = 1;
            foreach ($property_features as $feature) {
                echo '<div class="col-sm-3">';
                echo '<div class="checkbox">';
                echo '<label>';
                echo '<input type="checkbox" name="property_feature[]" id="feature-' . esc_attr($count) . '" value="' . esc_attr($feature->term_id) . '" />';
                echo esc_attr($feature->name);
                echo '</label>';
                echo '</div>';
                echo '</div>';
                $count++;
            }
        }
        ?>
    </div>
</div>
