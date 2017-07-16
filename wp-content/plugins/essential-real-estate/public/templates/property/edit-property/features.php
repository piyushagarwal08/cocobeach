<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:46 PM
 */
global $property_data, $property_meta_data;
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
        <h2><?php esc_html_e( 'Property Features', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-features row">
        <?php
        $features_terms_id = array();
        $features_terms = get_the_terms( $property_data->ID, 'property-feature' );
        if ( $features_terms && ! is_wp_error( $features_terms ) ) {
            foreach( $features_terms as $feature ) {
                $features_terms_id[] = intval( $feature->term_id );
            }
        }

        $property_features = get_categories(array(
            'hide_empty' => 0,
            'taxonomy'  => 'property-feature'
        ));

        if( !empty( $property_features ) ) {
            $feature_array = array();
            $count = 1;
            foreach( $property_features as $feature ) {

                echo '<div class="col-sm-3">';
                echo '<div class="checkbox">';
                echo '<label>';
                if ( in_array( $feature->term_id, $features_terms_id ) ) {
                    echo '<input type="checkbox" name="property_feature[]" id="feature-' . esc_attr( $count ) . '" value="' . esc_attr( $feature->term_id ) . '" checked />';
                    echo esc_attr( $feature->name );
                } else {
                    echo '<input type="checkbox" name="property_feature[]" id="feature-' . esc_attr( $count ) . '" value="' . esc_attr( $feature->term_id ) . '" />';
                    echo esc_attr( $feature->name );
                }
                echo '</label>';
                echo '</div>';
                echo '</div>';
                $count++;
            }
        }
        ?>
    </div>
</div>