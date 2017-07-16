<?php
global $post;
$property_meta_data = get_post_custom(get_the_ID());
?>

<div class="ere-heading-style2 mg-bottom-35 text-left">
	<h2><?php esc_html_e( 'Contact Information', 'essential-real-estate' ); ?></h2>
</div>
<div class="property-contact-agent row">
	<?php
	$agent_display_option = isset($property_meta_data[ ERE_METABOX_PREFIX . 'agent_display_option' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'agent_display_option' ][0] : '';
	$property_agent       = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_agent' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_agent' ][0] : '';
	$property_other_contact_mail = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_mail' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_mail' ][0] : '';
	$agent_type = '';$user_id=0;
	if ( $agent_display_option == 'author_info' || ( $agent_display_option == 'other_info' && !empty( $property_other_contact_mail )) || ( $agent_display_option == 'agent_info' && ! empty( $property_agent ) ) ): ?>
		<div class="col-md-5 agent-info">
			<?php
			$email = $avatar_src = $agent_link = $agent_name = $agent_position = $agent_facebook_url = $agent_twitter_url =
			$agent_googleplus_url = $agent_linkedin_url = $agent_pinterest_url = $agent_skype =
			$agent_youtube_url = $agent_vimeo_url = $agent_mobile_number = $agent_office_address = $agent_website_url = $agent_description = '';

			if ( $agent_display_option != 'other_info' ) {

				if( $agent_display_option == 'author_info') {
					global $post;
					$user_id = $post->post_author;
					$email = get_userdata( $user_id )->user_email;
					$user_info      = get_userdata( $user_id );
					// Show Property Author Info (Get info via User. Apply for User, Agent, Seller)
					$author_picture_id = get_the_author_meta( ERE_METABOX_PREFIX . 'author_picture_id', $user_id );
					$avatar_src        = wp_get_attachment_image_src( $author_picture_id, 'full' );
					if ( is_array( $avatar_src ) && count( $avatar_src ) > 0 ) {
						$avatar_src = $avatar_src[0];
					}

					$agent_name     = $user_info->first_name . ' ' . $user_info->last_name;

					$agent_facebook_url   = get_the_author_meta( ERE_METABOX_PREFIX . 'author_facebook_url', $user_id );
					$agent_twitter_url    = get_the_author_meta( ERE_METABOX_PREFIX . 'author_twitter_url', $user_id );
					$agent_googleplus_url = get_the_author_meta( ERE_METABOX_PREFIX . 'author_googleplus_url', $user_id );
					$agent_linkedin_url   = get_the_author_meta( ERE_METABOX_PREFIX . 'author_linkedin_url', $user_id );
					$agent_pinterest_url  = get_the_author_meta( ERE_METABOX_PREFIX . 'author_pinterest_url', $user_id );
					$agent_instagram_url  = get_the_author_meta( ERE_METABOX_PREFIX . 'author_instagram_url', $user_id );
					$agent_skype          = get_the_author_meta( ERE_METABOX_PREFIX . 'author_skype', $user_id );
					$agent_youtube_url    = get_the_author_meta( ERE_METABOX_PREFIX . 'author_youtube_url', $user_id );
					$agent_vimeo_url      = get_the_author_meta( ERE_METABOX_PREFIX . 'author_vimeo_url', $user_id );

					$agent_mobile_number  = get_the_author_meta( ERE_METABOX_PREFIX . 'author_mobile_number', $user_id );
					$agent_office_address = get_the_author_meta( ERE_METABOX_PREFIX . 'author_office_address', $user_id );
					$agent_website_url    = get_the_author_meta( 'user_url', $user_id );
					$agent_position = esc_html__( 'Property Seller', 'essential-real-estate' );
					$agent_type = esc_html__( 'Seller', 'essential-real-estate' );
				} else {
					$agent_post_meta_data = get_post_custom( $property_agent);
					$email = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_email'][0] : '';
					$agent_name     = get_the_title($property_agent);
					$avatar_id = get_post_thumbnail_id($property_agent);
					$avatar_src = wp_get_attachment_image_src($avatar_id, 'full');
					if ($avatar_src && !empty($avatar_src[0])) {
						$avatar_src = $avatar_src[0];
					} else {
						$avatar_src = ERE_PLUGIN_URL . 'public/assets/images/profile-avatar.png';
					}
					$agent_facebook_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_facebook_url'][0] : '';
					$agent_twitter_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_twitter_url'][0] : '';
					$agent_googleplus_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_googleplus_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_googleplus_url'][0] : '';
					$agent_linkedin_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_linkedin_url'][0] : '';
					$agent_pinterest_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_pinterest_url'][0] : '';
					$agent_instagram_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_instagram_url'][0] : '';
					$agent_skype = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_skype'][0] : '';
					$agent_youtube_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_youtube_url'][0] : '';
					$agent_vimeo_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_vimeo_url'][0] : '';

					$agent_mobile_number = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_mobile_number'][0] : '';
					$agent_office_address = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_office_address'][0] : '';
					$agent_website_url = isset($agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url']) ? $agent_post_meta_data[ERE_METABOX_PREFIX . 'agent_website_url'][0] : '';

					$agent_position = esc_html__( 'Property Agent', 'essential-real-estate' );
					$agent_type = esc_html__( 'Agent', 'essential-real-estate' );
					$agent_link     = get_the_permalink( $property_agent );
				}
			} elseif ( $agent_display_option == 'other_info' ) {
				$email = $property_other_contact_mail;
				$agent_name = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_name' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_name' ][0] : '';
				$agent_mobile_number = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_phone' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_phone' ][0] : '';
				$agent_description = isset($property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_description' ]) ? $property_meta_data[ ERE_METABOX_PREFIX . 'property_other_contact_description' ][0] : '';
			}
			?>
			<?php if ( ! empty( $avatar_src ) ): ?>
				<div class="agent-avatar text-center" style="background-image: url('<?php echo esc_url( $avatar_src ); ?>')">
					<?php if ( ! empty( $agent_link ) ): ?><a title="<?php echo esc_attr( $agent_name ) ?>"
					   href="<?php echo esc_url( $agent_link ) ?>"></a><?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="agent-content mg-bottom-60">
				<div class="agent-heading">
					<?php if ( ! empty( $agent_name ) ): ?>
						<h4><?php if ( ! empty( $agent_link ) ): ?><a title="<?php echo esc_attr( $agent_name ) ?>" href="<?php echo esc_url( $agent_link ) ?>"><?php endif; ?><?php echo esc_attr( $agent_name ) ?><?php if ( ! empty( $agent_link ) ): ?></a><?php endif; ?></h4>
					<?php endif; ?>
					<?php if ( ! empty( $agent_position ) ): ?>
						<span class="fw-normal"><?php echo esc_attr( $agent_position ) ?></span>
					<?php endif; ?>
				</div>
				<div class="agent-social">
					<?php if ( ! empty( $agent_facebook_url ) ): ?>
						<a title="Facebook" href="<?php echo esc_url( $agent_facebook_url ); ?>">
							<i class="fa fa-facebook"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_twitter_url ) ): ?>
						<a title="Twitter" href="<?php echo esc_url( $agent_twitter_url ); ?>">
							<i class="fa fa-twitter"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_googleplus_url ) ): ?>
						<a title="Google Plus" href="<?php echo esc_url( $agent_googleplus_url ); ?>">
							<i class="fa fa-google-plus"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $email ) ): ?>
						<a title="Email" href="mailto:<?php echo esc_attr( $email ); ?>">
							<i class="fa fa-envelope"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_skype ) ): ?>
						<a title="Skype" href="skype:<?php echo esc_attr( $agent_skype ); ?>?chat">
							<i class="fa fa-skype"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_linkedin_url ) ): ?>
						<a title="Linkedin" href="<?php echo esc_url( $agent_linkedin_url ); ?>">
							<i class="fa fa-linkedin"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_pinterest_url ) ): ?>
						<a title="Pinterest" href="<?php echo esc_url( $agent_pinterest_url ); ?>">
							<i class="fa fa-pinterest"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_instagram_url ) ): ?>
						<a title="Instagram" href="<?php echo esc_url( $agent_instagram_url ); ?>">
							<i class="fa fa-instagram"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_youtube_url ) ): ?>
						<a title="Youtube" href="<?php echo esc_url( $agent_youtube_url ); ?>">
							<i class="fa fa-youtube-play"></i>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $agent_vimeo_url ) ): ?>
						<a title="Vimeo" href="<?php echo esc_url( $agent_vimeo_url ); ?>">
							<i class="fa fa-vimeo"></i>
						</a>
					<?php endif; ?>
				</div>
				<div class="agent_info">
					<?php if ( ! empty( $agent_office_address ) ): ?>
						<div class="agent-address">
							<i class="fa fa-map-marker accent-color"></i>
							<span><?php echo esc_attr( $agent_office_address ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $agent_mobile_number ) ): ?>
						<div class="agent-mobile">
							<i class="fa fa-phone accent-color"></i>
							<span><?php echo esc_attr( $agent_mobile_number ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $email ) ): ?>
						<div class="agent-email">
							<i class="fa fa-envelope accent-color"></i>
							<span><?php echo esc_attr( $email ); ?></span>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $agent_website_url ) ): ?>
						<div class="agent-website">
							<i class="fa fa-link accent-color"></i>
							<a href="<?php echo esc_url( $agent_website_url ); ?>" title=""><?php echo esc_url( $agent_website_url ); ?></a>
						</div>
					<?php endif; ?>
				</div>
				<?php if(!empty( $agent_description )): ?>
					<div class="description">
						<p><?php echo wp_kses_post( $agent_description ); ?></p>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $property_agent ) ): ?>
					<a class="view-single-agent" href="<?php echo esc_url( $agent_link ) ?>" title="<?php echo esc_attr( $agent_name ) ?>"><?php esc_html_e( 'View Profile', 'essential-real-estate' ); ?></a>
					<a class="view-my-properties" href="<?php echo get_post_type_archive_link( 'property' ); ?>?agent_id=<?php echo esc_attr($property_agent) ?>" title="<?php echo esc_attr( $agent_name ) ?>"><?php esc_html_e( 'Other Properties', 'essential-real-estate' ); ?></a>
				<?php else:?>
					<a class="view-my-properties" href="<?php echo get_post_type_archive_link( 'property' ); ?>?user_id=<?php echo esc_attr($user_id) ?>" title="<?php echo esc_attr( $agent_name ) ?>"><?php esc_html_e( 'Other Properties', 'essential-real-estate' ); ?></a>
				<?php endif; ?>

			</div>
		</div>
		<div class="col-md-7 contact-agent sm-mg-top-60">
			<?php if ( ! empty( $email ) ): ?>
				<div class="contact-agent-title">
					<span class="icon-mail-envelope-open6 accent-color"></span>
					<h4 class="fs-18"><?php esc_html_e( 'Contact', 'essential-real-estate' ); ?></h4>
				</div>
				<form method="post" action="<?php echo ERE_AJAX_URL; ?>"
				      id="contact-agent-form">
					<input type="hidden" name="target-email" value="<?php echo esc_attr( $email ); ?>">
					<input type="hidden" name="property-url" value="<?php echo get_permalink(); ?>">
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
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>