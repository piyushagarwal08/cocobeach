<?php
global $post;
$agent_post_meta_data = get_post_custom( get_the_ID() );
$custom_agent_image_size_single = ere_get_option('custom_agent_image_size_single', '370x490');
$agent_name = get_the_title();
$agent_id=get_the_ID();
$agent_position = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_position']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_position'][0] : '';

$agent_description = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_description']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_description'][0] : '';
$agent_company = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_company']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_company'][0] : '';
$agent_licenses = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_licenses']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_licenses'][0] : '';
$agent_office_address = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address'][0] : '';
$agent_mobile_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number'][0] : '';
$agent_fax_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_fax_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_fax_number'][0] : '';
$agent_office_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_number'][0] : '';
$email = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email'][0] : '';
$agent_website_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url'][0] : '';

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
$user = get_user_by('id', $agent_user_id);
if (empty($user)) {
	$agent_user_id = 0;
}
$ere_property = new ERE_Property();
$total_property = $ere_property->get_total_properties_by_user($agent_id, $agent_user_id);

?>

<div class="agent-single container">
	<div class="agent-single-inner row">
		<?php
		$avatar_id = get_post_thumbnail_id(get_the_ID());
		$avatar_src = '';
		$width = '';
		$height = '';
		if (preg_match('/\d+x\d+/', $custom_agent_image_size_single)) {
			$image_size = explode('x', $custom_agent_image_size_single);
			$avatar_src = ere_image_resize_id($avatar_id, $image_size[0], $image_size[1], true);
			if (empty($avatar_src)) {
				$avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
			}
		} else {
			if (!in_array($custom_agent_image_size_single, array('full', 'thumbnail'))) {
				$custom_agent_image_size_single = 'full';
			}
			$avatar_src = wp_get_attachment_image_src($avatar_id, $custom_agent_image_size_single);
			if ($avatar_src && !empty($avatar_src[0])) {
				$avatar_src = $avatar_src[0];
			} else {
				$avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
			}
		}
		if (!empty($avatar_src)) {
			list($width, $height) = getimagesize($avatar_src);
		}?>
		<div class="agent-avatar text-center col-md-3 col-sm-12">
			<img width="<?php echo esc_attr($width) ?>"
				 height="<?php echo esc_attr($height) ?>"
				 src="<?php echo esc_url($avatar_src) ?>"
				 alt="<?php echo esc_attr($agent_name) ?>"
				 title="<?php echo esc_attr($agent_name) ?>">
			<?php if($total_property>0):?>
			<a class="btn btn-primary btn-block" href="<?php echo get_post_type_archive_link( 'property' ); ?>?agent_id=<?php echo esc_attr($agent_id); ?>" title="<?php echo esc_attr( $agent_name ) ?>"><?php esc_html_e( 'View All Properties', 'essential-real-estate' ); ?></a>
			<?php endif;?>
		</div>
		<div class="agent-content col-md-6 col-sm-12">
			<div class="agent-content-top agent-title">
				<?php if (!empty($agent_name)): ?>
					<h2 class="fs-32 fw-semi-bold heading-color"><?php echo esc_attr($agent_name) ?></h2>
				<?php endif; ?>
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
				<?php if (!empty($agent_position)): ?>
					<span class="fs-16 fw-medium"><?php echo esc_html($agent_position) ?></span>
				<?php endif; ?>
				<?php if($total_property>1):?>
					<span class="fs-16 fw-medium btn-block accent-color"><?php echo sprintf(__('%s properties','essential-real-estate'),$total_property) ?></span>
				<?php else:?>
					<span class="fs-16 fw-medium btn-block accent-color"><?php echo sprintf(__('%s property','essential-real-estate'),$total_property) ?></span>
				<?php endif; ?>
			</div>
			<div class="agent-contact agent-info">
					<?php if (!empty($agent_office_address)): ?>
						<span class="fw-normal"><i
								class="fa fa-map-marker accent-color"></i> <?php esc_attr_e('Address:','essential-real-estate');  ?>
							<strong>
								<?php echo esc_html($agent_office_address) ?>
							</strong>
						</span>
					<?php endif; ?>
					<?php if (!empty($email)): ?>
						<span class="fw-normal"><i
								class="fa fa-envelope accent-color"></i> <?php esc_attr_e('Email:','essential-real-estate');  ?>
							<a style="display: inline;" href="mailto:<?php echo esc_attr($email) ?>" title="<?php esc_attr_e('Website:','essential-real-estate');?>">
								<strong>
									<?php echo esc_html($email) ?>
								</strong>
							</a>
						</span>
					<?php endif; ?>
					<?php if (!empty($agent_mobile_number)): ?>
						<span class="fw-normal"><i class="fa fa-phone accent-color"></i>
							<?php esc_attr_e('Phone:','essential-real-estate');?>
							<strong>
								<?php echo esc_html($agent_mobile_number) ?>
							</strong>
						</span>
					<?php endif; ?>
					<?php if (!empty($agent_website_url)): ?>
						<span class="fw-normal">
							<i
								class="fa fa-link accent-color"></i>
							<?php esc_attr_e('Website:','essential-real-estate');?>
							<a style="display: inline;" href="<?php echo esc_url($agent_website_url) ?>" title="<?php esc_attr_e('Website:','essential-real-estate');?>">
								<strong><?php echo esc_url($agent_website_url); ?></strong>
							</a>
						</span>
					<?php endif; ?>
					<hr class="mg-top-20">
					<?php if (!empty($agent_company)): ?>
						<span class="fw-normal">
							<?php esc_attr_e('Company:','essential-real-estate');?>
							<strong>
								<?php echo esc_html($agent_company); ?>
							</strong>
						</span>
					<?php endif; ?>
					<?php if (!empty($agent_licenses)): ?>
						<span class="fw-normal">
							<?php esc_attr_e('Licenses:','essential-real-estate');?>
							<strong>
								<?php echo esc_html($agent_licenses); ?>
							</strong>
						</span>
					<?php endif; ?>
					<?php if (!empty($agent_office_number)): ?>
						<span class="fw-normal">
							<?php esc_attr_e('Office Number:','essential-real-estate');?>
							<strong>
								<?php echo esc_html($agent_office_number);?>
							</strong>
						</span>
					<?php endif; ?>
					<?php  if (!empty($agent_office_address)): ?>
						<span class="fw-normal">
							<?php esc_attr_e('Office Address:','essential-real-estate');?>
							<strong>
								<?php echo esc_html($agent_office_address);?>
							</strong>
						</span>
					<?php endif; ?>
			</div>
		</div>
		<div class="contact-agent col-md-3 col-sm-12">
			<div class="contact-agent-title">
				<h4 class="uppercase fw-semi-bold fs-18"><?php esc_html_e( 'Contact', 'essential-real-estate' ); ?></h4>
			</div>
			<form method="post" action="<?php echo ERE_AJAX_URL; ?>" id="contact-agent-form">
				<input type="hidden" name="target-email" value="<?php echo esc_attr( $email ); ?>">
				<div class="form-group">
					<input class="form-control" name="name" type="text"
					       placeholder="<?php esc_html_e( 'Full Name', 'essential-real-estate' ); ?> *">
					<div
						class="hidden name-error form-error"><?php esc_html_e( 'Please enter your Name!', 'essential-real-estate' ); ?></div>
				</div>
				<div class="form-group">
					<input class="form-control" name="phone" type="text"
					       placeholder="<?php esc_html_e( 'Phone Number', 'essential-real-estate' ); ?> *">
					<div
						class="hidden phone-error form-error"><?php esc_html_e( 'Please enter your Phone!', 'essential-real-estate' ); ?></div>
				</div>
				<div class="form-group">
					<input class="form-control" name="email" type="email"
					       placeholder="<?php esc_html_e( 'Email Adress', 'essential-real-estate' ); ?> *">
					<div class="hidden email-error form-error"
					     data-not-valid="<?php esc_html_e( 'Your Email address is not Valid!', 'essential-real-estate' ) ?>"
					     data-error="<?php esc_html_e( 'Please enter your Email!', 'essential-real-estate' ) ?>"><?php esc_html_e( 'Please enter your Email!', 'essential-real-estate' ); ?></div>
				</div>
				<div class="form-group">
						<textarea class="form-control" name="message" rows="5"
						          placeholder="<?php esc_html_e( 'Message', 'essential-real-estate' ); ?> *"></textarea>
					<div
						class="hidden message-error form-error"><?php esc_html_e( 'Please enter your Message!', 'essential-real-estate' ); ?></div>
				</div>
				<button type="submit"
				        class="agent-contact-btn btn btn-block"><?php esc_html_e( 'Submit Request', 'essential-real-estate' ); ?></button>
				<div class="form-messages"></div>
			</form>
		</div>
		<?php if (!empty($agent_description)): ?>
			<div class="col-md-12 mg-top-40">
				<p class="fs-14 line-2x"><?php echo esc_attr($agent_description) ?></p>
			</div>
		<?php endif; ?>
	</div>
</div>