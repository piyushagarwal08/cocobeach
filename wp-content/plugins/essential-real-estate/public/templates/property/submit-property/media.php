<?php
/**
 * Created by G5Theme.
 * User: trungpq
 * Date: 18/11/16
 * Time: 5:44 PM
 */
global $hide_property_fields;
?>
<div class="property-fields-wrap">
    <div class="ere-heading-style2 mg-bottom-20 text-left property-fields-title">
        <h2><?php esc_html_e( 'Property Images and Video', 'essential-real-estate' ); ?></h2>
    </div>
    <div class="property-fields property-media">
        <div class="media-gallery">
            <div class="row">
                <div id="property-thumbs-container">
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
        <?php if (!in_array("property_video_url", $hide_property_fields)) { ?>
        <div class="property-video-url mg-top-40">
            <label for="property_video_url"><?php esc_html_e('Video URL', 'essential-real-estate'); ?></label>
            <input type="text" class="form-control" name="property_video_url" id="property_video_url"
                   placeholder="<?php esc_html_e('YouTube, Vimeo, SWF File, MOV File', 'essential-real-estate'); ?>">
        </div>
        <?php } ?>
        <?php if (!in_array("property_image_360", $hide_property_fields)) : ?>
        <div class="property-image-360 mg-top-40 row">
            <div class="col-md-8 col-sm-6">
                <h4><?php esc_html_e('Image 360', 'essential-real-estate'); ?></h4>
                <div id="ere-image-360-plupload-container" class="file-upload-block">
                    <input
                        name="property_image_360_url"
                        type="text"
                        id="image_360_url"
                        class="ere_image_360_url form-control" value="">
                    <button id="select-images-360" style="position: absolute" title="<?php esc_html_e('Choose image','essential-real-estate') ?>" class="ere_image360"><i class="fa fa-file-image-o"></i></button>
                    <input type="hidden" class="ere_image_360_id"
                           name="property_image_360_id"
                           value="" id="ere_image_360_id"/>
                </div>
                <div id="ere-image-360-errors-log"></div>
            </div>
            <div class="col-md-4 col-sm-6" id="ere-property-image-360-view" data-plugin-url="<?php echo ERE_PLUGIN_URL; ?>">

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
