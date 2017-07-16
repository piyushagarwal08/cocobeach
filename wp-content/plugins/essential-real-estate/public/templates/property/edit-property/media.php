<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:44 PM
 */
global $property_data,$hide_property_fields,$property_meta_data;
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
        <h2><?php esc_html_e( 'Property Images and Video', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-media">
        <div class="media-gallery">
            <div class="row">
                <div id="property-thumbs-container">
                    <?php
                    $property_img_arg =  get_post_meta( $property_data->ID,ERE_METABOX_PREFIX. 'property_images', false );
                    $property_img=(isset($property_img_arg) && is_array($property_img_arg) && count( $property_img_arg ) > 0)? $property_img_arg[0]: '';
                    $property_images = explode('|', $property_img);
                    $featured_image_id = get_post_thumbnail_id( $property_data->ID );
                    if($featured_image_id) {
                        $property_images[] = $featured_image_id;
                    }
                    $property_images = array_unique($property_images);
                    if( !empty($property_images[0])) {
                        foreach ($property_images as $prop_image_id) {

                            $is_featured_image = ($featured_image_id == $prop_image_id);
                            $featured_icon = ($is_featured_image) ? 'fa-star' : 'fa-star-o';
                            echo '<div class="col-sm-2 property-thumb">';
                            echo '<figure class="gallery-thumb">';
                            echo wp_get_attachment_image($prop_image_id, 'thumbnail');
                            echo '<div class="gallery-item-actions">';
                            echo '<a class="icon icon-delete" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '" href="javascript:;">';
                            echo '<i class="fa fa-trash-o"></i>';
                            echo '</a>';
                            echo '<a class="icon icon-fav icon-featured" data-property-id="' . intval($property_data->ID) . '" data-attachment-id="' . intval($prop_image_id) . '" href="javascript:;">';
                            echo '<i class="fa ' . esc_attr($featured_icon) . '"></i>';
                            echo '</a>';
                            echo '<input type="hidden" class="property_image_ids" name="property_image_ids[]" value="' . intval($prop_image_id) . '">';
                            echo '<span style="display: none;" class="icon icon-loader">';
                            echo '<i class="fa fa-spinner fa-spin"></i>';
                            echo '</span>';
                            echo '</div>';
                            if ($is_featured_image) {
                                echo '<input type="hidden" class="featured_image_id" name="featured_image_id" value="' . intval($prop_image_id) . '">';
                            }
                            echo '</figure>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="ere-gallery-plupload-container" class="media-drag-drop">
            <h4>
                <i class="fa fa-cloud-upload"></i> <?php esc_html_e('Drag and drop images here', 'essential-real-estate'); ?>
            </h4>
            <h4><?php esc_html_e('or', 'essential-real-estate'); ?></h4>
            <a id="select-images" href="javascript:;"
               class="btn btn-primary"><?php esc_html_e('Select Images', 'essential-real-estate'); ?></a>
        </div>
        <div id="ere-gallery-errors-log"></div>
        <?php if (!in_array("property_video_url", $hide_property_fields)) {?>
            <div class="property-video-url mg-top-40">
                <label for="property_video_url"><?php esc_html_e('Video URL', 'essential-real-estate'); ?></label>
                <input type="text" class="form-control" name="property_video_url" id="property_video_url"
                       placeholder="<?php esc_html_e('YouTube, Vimeo, SWF File, MOV File', 'essential-real-estate'); ?>"
                       value="<?php if (isset($property_meta_data[ERE_METABOX_PREFIX . 'property_video_url'])) {
                           echo sanitize_text_field($property_meta_data[ERE_METABOX_PREFIX . 'property_video_url'][0]);
                       } ?>">
            </div>
        <?php } ?>
        <?php if (!in_array("property_image_360", $hide_property_fields)) :
            $property_image_360_arr = get_post_meta( $property_data->ID,ERE_METABOX_PREFIX. 'property_image_360', false );
            $property_image_360_id=(isset($property_image_360_arr) && is_array($property_image_360_arr) && count( $property_image_360_arr ) > 0)? $property_image_360_arr[0]['id']: '';
            $property_image_360_url=(isset($property_image_360_arr) && is_array($property_image_360_arr) && count( $property_image_360_arr ) > 0)? $property_image_360_arr[0]['url']: '';
        ?>
        <div class="property-image-360 mg-top-40 row">
            <div class="col-md-8 col-sm-6">
                <h4><?php esc_html_e('Image 360', 'essential-real-estate'); ?></h4>
                <div id="ere-image-360-plupload-container" class="file-upload-block">
                    <input
                        name="property_image_360_url"
                        type="text"
                        id="image_360_url"
                        class="ere_image_360_url form-control" value="<?php echo esc_url($property_image_360_url); ?>">
                    <button id="select-images-360" style="position: absolute" title="<?php esc_html_e('Choose image','essential-real-estate') ?>" class="ere_image360"><i class="fa fa-file-image-o"></i></button>
                    <input type="hidden" class="ere_image_360_id"
                           name="property_image_360_id"
                           value="<?php echo esc_attr($property_image_360_id); ?>" id="ere_image_360_id"/>

                </div>
                <div id="ere-image-360-errors-log"></div>
            </div>
            <?php if(!empty($property_image_360_url)):?>
            <div class="col-md-4 col-sm-6" id="ere-property-image-360-view" data-plugin-url="<?php echo ERE_PLUGIN_URL; ?>">
                <iframe width="100%" height="200" scrolling="no" allowfullscreen src="<?php echo ERE_PLUGIN_URL."public/assets/packages/vr-view/index.html?image=".esc_url($property_image_360_url); ?>"></iframe>
            </div>
            <?php endif;?>
        </div>
        <?php endif; ?>
    </div>
</div>