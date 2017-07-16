<?php
/**
 * Shortcode attributes
 * @var $atts
 */
$map_style = $icon = $property_id = $lat = $lng = $map_height = $el_class = '';
extract(shortcode_atts(array(
    'map_style'   => 'property',
    'icon'        => '',
    'property_id' => '',
    'lat'         => '',
    'lng'         => '',
    'map_height'  => '500px',
    'el_class'    => ''
), $atts));

$wrapper_attributes = array();
$wrapper_styles = array();
if ($map_height != '') {
    $wrapper_styles[] = 'style="height: ' . $map_height . '"';
}

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'google-map-property');

$google_map_style = ere_get_option('googlemap_style', '');
$googlemap_zoom_level = ere_get_option('googlemap_zoom_level', '12');
wp_enqueue_script('google-map');
//wp_enqueue_script('infobox');
/*Set variable javascript property*/
wp_localize_script(ERE_PLUGIN_PREFIX . 'main', 'ere_property_map_vars',
    array(
        'google_map_style' => $google_map_style
    )
);
?>
<div id="map-property-single" <?php echo implode(' ', $wrapper_styles); ?>>
</div>

<script>
    jQuery(document).ready(function () {
        var bounds = new google.maps.LatLngBounds();
        <?php
        $title = $icon_url = $price = $img_src = $property_address = $link = $share_social = '';
        $icon = isset($icon) ? $icon : '';
        if ($map_style == 'property') {
            $lat = $lng = '';
            $property_id = isset($property_id) ? $property_id : '';
            if (!empty($property_id)) {
                $attach_id = get_post_thumbnail_id($property_id);
                $image_src = ere_image_resize_id($attach_id, 100, 100, true);
                $title = get_the_title($property_id);
                $link = get_the_permalink($property_id);
                $price = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_price', true);
                $share_social = '';
                $location = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_location', true);
                if (!empty($location)) {
                    list($lat, $lng) = explode(',', $location['location']);
                }
                $property_address = get_post_meta($property_id, ERE_METABOX_PREFIX . 'property_address', true);
                if (empty($icon)) {
                    $property_type = get_the_terms($property_id, 'property-type');
                    if ($property_type) {
                        $property_type_id = $property_type[0]->term_id;
                        $property_type_icon = get_term_meta($property_type_id, 'property_type_icon', true);
                        if (is_array($property_type_icon) && count($property_type_icon) > 0) {
                            $icon = $property_type_icon['id'];
                        }
                    }
                }
            }
        }
        if (!empty($icon)) {
            $icon = wp_get_attachment_image_src($icon, 'full');
            $icon_url = $icon[0];
        }
        if(!empty($lat) && !empty($lng)): ?>
        var lat = '<?php echo $lat ?>', lng = '<?php echo $lng ?>';
        var infoWindow = new google.maps.InfoWindow(), marker;
        var position = new google.maps.LatLng(lat, lng);
        var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
        var isDraggable = w > 1024;
        var mapOptions = {
            mapTypeId: 'roadmap',
            center: position,
            draggable: isDraggable,
            scrollwheel: false
        };
        var map = new google.maps.Map(document.getElementById("map-property-single"), mapOptions);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: '<?php echo $title ?>',
            icon: '<?php echo $icon_url ?>',
            animation: google.maps.Animation.DROP
        });
        <?php if($map_style == 'property') :?>
        var infobox = new InfoBox({
            disableAutoPan: true, //false
            maxWidth: 250,
            alignBottom: true,
            pixelOffset: new google.maps.Size(-148, -90),
            zIndex: null,
            infoBoxClearance: new google.maps.Size(1, 1),
            isHidden: false,
            pane: "floatPane",
            enableEventPropagation: false,
            boxStyle: {
                width: "300px"
            }
        });

        google.maps.event.addListener(marker, 'click', function () {
            infobox.setContent(
                '<div class = "marker-content">' +
                '<div class = "marker-content-item">' +
                '<div class = "item-thumb">' +
                '<a href="<?php echo esc_url($link) ?>"><img src="<?php echo esc_url($img_src) ?>" alt=" <?php echo esc_attr($title) ?>" style="width: 100px;"></a>' +
                '</div>' +
                '<div class="item-body">' +
                '<a href="<?php echo esc_url($link) ?>" class="title-marker"><?php echo esc_attr($title) ?></a>' +
                '<div class="price-marker"><?php echo ere_get_format_money($price)?></div>' +
                '<div class="address-marker" title="<?php echo esc_attr($property_address) ?>"><i class="fa fa-map-marker accent-color"></i><?php echo esc_attr($property_address) ?></div>' +
                '</div>' +
                '</div>' +
                '</div>'
            );
            infobox.open(map, this);
        });
        <?php endif; ?>
        map.fitBounds(bounds);
        var google_map_style = ere_property_map_vars.google_map_style;
        if (google_map_style !== '') {
            var styles = JSON.parse(google_map_style);
            map.setOptions({styles: styles});
        }
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
            this.setZoom(<?php echo $googlemap_zoom_level; ?>);
            google.maps.event.removeListener(boundsListener);
        });
        <?php else: ?>
        document.getElementById('map-property-single').append('No Location!');
        <?php endif; ?>
    });
</script>

