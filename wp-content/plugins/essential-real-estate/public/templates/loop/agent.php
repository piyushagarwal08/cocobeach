<?php
/**
 * @var $gf_item_wrap
 * @var $agent_layout_style
 */
$agent_id = get_the_ID();
$agent_name = get_the_title();
$agent_link = get_the_permalink();

$agent_post_meta_data = get_post_custom(get_the_ID());

$agent_description = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_description']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_description'][0] : '';
$email = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email'][0] : '';

$agent_facebook_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url'][0] : '';
$agent_twitter_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url'][0] : '';
$agent_googleplus_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_googleplus_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_googleplus_url'][0] : '';
$agent_linkedin_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url'][0] : '';
$agent_pinterest_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url'][0] : '';
$agent_instagram_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url'][0] : '';
$agent_skype = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype'][0] : '';
$agent_youtube_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url'][0] : '';
$agent_vimeo_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url'][0] : '';
$agent_user_id = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_user_id']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_user_id'][0] : '';
$user=get_user_by('id', $agent_user_id);
if(empty($user))
{
    $agent_user_id=0;
}
$ere_property = new ERE_Property();
$avatar_id = get_post_thumbnail_id($agent_id);
$avatar_src = ere_image_resize_id($avatar_id, 270, 340, true);
if (empty($avatar_src)) {
    $default_avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
}
?>
<div class="agent-item <?php echo esc_attr($gf_item_wrap) ?>">
    <div class="agent-item-inner">
        <?php if (!empty($avatar_src)) : ?>
            <div class="agent-avatar text-center">
                <img width="270" height="340"
                     src="<?php echo esc_url($avatar_src) ?>"
                     alt="<?php echo esc_attr($agent_name) ?>"
                     title="<?php echo esc_attr($agent_name) ?>">
            </div>
        <?php else: ?>
            <div class="agent-avatar agent-avatar-bg" style="background-image: url(<?php echo esc_url($default_avatar_src) ?>); padding-bottom: 125.93%;"></div>
        <?php endif; ?>
        <div class="agent-content">
            <div class="agent-info">
                <?php if (!empty($agent_name)): ?>
                    <h4 class="fs-18 fw-medium mg-bottom-0"><a
                            title="<?php echo esc_attr($agent_name) ?>"
                            href="<?php echo esc_url($agent_link) ?>"><?php echo esc_attr($agent_name) ?></a>
                    </h4>
                <?php endif; ?>
                <span class="fw-normal"><?php
                    $total_property = $ere_property->get_total_properties_by_user($agent_id, $agent_user_id);
                    if ($total_property > 1) {
                        printf(__('%s properties', 'essential-real-estate'), $total_property);
                    }
                    else
                    {
                        printf(__('%s property', 'essential-real-estate'), $total_property);
                    }
                    ?></span>
                <?php if (!empty($agent_description)): ?>
                    <p class="fs-12 line-2x"><?php echo esc_attr($agent_description) ?></p>
                <?php endif; ?>
            </div>
            <div class="agent-social">
                <?php if (!empty($agent_facebook_url)): ?>
                    <a title="Facebook" href="<?php echo esc_url($agent_facebook_url); ?>">
                        <i class="fa fa-facebook"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($agent_twitter_url)): ?>
                    <a title="Twitter" href="<?php echo esc_url($agent_twitter_url); ?>">
                        <i class="fa fa-twitter"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($agent_googleplus_url)): ?>
                    <a title="Google Plus" href="<?php echo esc_url($agent_googleplus_url); ?>">
                        <i class="fa fa-google-plus"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($email)): ?>
                    <a title="Email" href="mailto:<?php echo esc_attr($email); ?>">
                        <i class="fa fa-envelope"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($agent_skype)): ?>
                    <a title="Skype" href="skype:<?php echo esc_url($agent_skype); ?>?call">
                        <i class="fa fa-skype"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($agent_linkedin_url)): ?>
                    <a title="Linkedin" href="<?php echo esc_url($agent_linkedin_url); ?>">
                        <i class="fa fa-linkedin"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($agent_pinterest_url)): ?>
                    <a title="Pinterest" href="<?php echo esc_url($agent_pinterest_url); ?>">
                        <i class="fa fa-pinterest"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($agent_instagram_url)): ?>
                    <a title="Instagram" href="<?php echo esc_url($agent_instagram_url); ?>">
                        <i class="fa fa-instagram"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($agent_youtube_url)): ?>
                    <a title="Youtube" href="<?php echo esc_url($agent_youtube_url); ?>">
                        <i class="fa fa-youtube-play"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($agent_vimeo_url)): ?>
                    <a title="Vimeo" href="<?php echo esc_url($agent_vimeo_url); ?>">
                        <i class="fa fa-vimeo"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>