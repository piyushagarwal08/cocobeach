<?php
/**
 * Shortcode attributes
 * @var $atts
 */
$item_amount = $show_paging = $include_heading = $heading_sub_title
	= $heading_title = $heading_text_align = $paged = $el_class = '';
extract( shortcode_atts( array(
	'item_amount'       => '6',
	'show_paging'       => '',
	'include_heading'   => '',
	'heading_sub_title' => '',
	'heading_title'     => '',
	'heading_text_align' => 'text-left',
	'paged'             => '1',
	'el_class'          => ''
), $atts ) );

$agency_item_class = array( 'agency-item mg-bottom-60 sm-mg-bottom-40' );
$wrapper_classes = array(
	'ere-agency clearfix',
	$el_class
);

$offset = ($item_amount * ($paged -1));
$args = array(
	'number' => ( $item_amount > 0 ) ? $item_amount : - 1,
	'taxonomy'      => 'agencies',
	'orderby'        => 'date',
	'offset'          => $offset,
	'order'          => 'DESC'
);

$agencies = get_categories($args);

$min_suffix = ere_get_option( 'enable_min_css', 0 ) == 1 ? '.min' : '';
wp_print_styles( ERE_PLUGIN_PREFIX . 'agency');

$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'agency', ERE_PLUGIN_URL . 'public/templates/shortcodes/agency/assets/js/agency' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);
?>
<div class="ere-agency-wrap">
	<div class="<?php echo join( ' ', $wrapper_classes ) ?>">
		<?php if ( $include_heading && (!empty( $heading_sub_title ) || !empty( $heading_title ))) : ?>
			<div class="container">
				<div class="ere-heading mg-bottom-60 sm-mg-bottom-40 <?php echo esc_attr( $heading_text_align ); ?>">
					<span></span>
					<?php if ( ! empty( $heading_sub_title ) ): ?>
						<p><?php echo esc_html( $heading_sub_title ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $heading_title ) ): ?>
						<h2><?php echo esc_html( $heading_title ); ?></h2>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<div class="agency-content">
			<?php if ( $agencies ) :
				foreach ($agencies as $agency) :
					$agency_id = $agency->term_id;
					$agencies_address = get_term_meta( $agency_id, 'agencies_address', true );
					$agencies_map_address = get_term_meta( $agency_id, 'agencies_map_address', true );

					$agencies_email = get_term_meta( $agency_id, 'agencies_email', true );
					$agencies_mobile_number = get_term_meta( $agency_id, 'agencies_mobile_number', true );
					$agencies_fax_number = get_term_meta( $agency_id, 'agencies_fax_number', true );

					$agencies_licenses = get_term_meta( $agency_id, 'agencies_licenses', true );

					$agencies_office_number = get_term_meta( $agency_id, 'agencies_office_number', true );
					$agencies_website_url = get_term_meta( $agency_id, 'agencies_website_url', true );
					$agencies_vimeo_url = get_term_meta( $agency_id, 'agencies_vimeo_url', true );
					$agencies_facebook_url = get_term_meta( $agency_id, 'agencies_facebook_url', true );
					$agencies_twitter_url = get_term_meta( $agency_id, 'agencies_twitter_url', true );
					$agencies_googleplus_url = get_term_meta( $agency_id, 'agencies_googleplus_url', true );
					$agencies_linkedin_url = get_term_meta( $agency_id, 'agencies_linkedin_url', true );
					$agencies_pinterest_url = get_term_meta( $agency_id, 'agencies_pinterest_url', true );
					$agencies_instagram_url = get_term_meta( $agency_id, 'agencies_instagram_url', true );
					$agencies_skype = get_term_meta( $agency_id, 'agencies_skype', true );
					$agencies_youtube_url = get_term_meta( $agency_id, 'agencies_youtube_url', true );

					$logo_src = get_term_meta( $agency_id, 'agencies_logo', true );
					if ($logo_src && !empty($logo_src['url'])) {
						$logo_src = $logo_src['url'];
					}

					$agency_link = get_term_link( $agency->slug, 'agencies' );
					?>
					<div class="<?php echo join( ' ', $agency_item_class ); ?>">
						<div class="agency-inner pd-bottom-60 sm-pd-bottom-40">
							<?php if ( ! empty( $logo_src ) ): ?>
								<?php list( $width, $height ) = getimagesize( $logo_src ); ?>
								<div class="agency-avatar">
									<img width="<?php echo esc_attr( $width ) ?>"
									     height="<?php echo esc_attr( $height ) ?>"
									     src="<?php echo esc_url( $logo_src ) ?>" alt="<?php echo esc_attr( $agency->name ); ?>"
									     title="<?php echo esc_attr( $agency->name ); ?>">
								</div>
							<?php endif; ?>
							<div class="agency-item-content">
								<div class="agency-heading agency-element-inline">
									<div>
										<?php if(!empty( $agency->name )): ?>
											<h4 class="agency-title fs-18 fw-semi-bold">
												<a href="<?php echo esc_url( $agency_link ); ?>" title="<?php echo esc_attr( $agency->name ); ?>"><?php echo esc_attr( $agency->name ); ?></a>
											</h4>
										<?php endif; ?>
										<?php if ( ! empty( $agencies_address ) ): ?>
											<div class="agency-position">
												<p title="<?php echo esc_attr( $agencies_address ) ?>">
													<span><?php echo esc_attr( $agencies_address ) ?></span>
												</p>
											</div>
										<?php endif; ?>
									</div>
									<div class="agency-social">
										<?php if (!empty($agencies_facebook_url)): ?>
											<a title="Facebook" href="<?php echo esc_url($agencies_facebook_url); ?>">
												<i class="fa fa-facebook"></i>
											</a>
										<?php endif; ?>
										<?php if (!empty($agencies_twitter_url)): ?>
											<a title="Twitter" href="<?php echo esc_url($agencies_twitter_url); ?>">
												<i class="fa fa-twitter"></i>
											</a>
										<?php endif; ?>
										<?php if (!empty($agencies_googleplus_url)): ?>
											<a title="Google Plus" href="<?php echo esc_url($agencies_googleplus_url); ?>">
												<i class="fa fa-google-plus"></i>
											</a>
										<?php endif; ?>
										<?php if (!empty($agencies_skype)): ?>
											<a title="Skype" href="skype:<?php echo esc_url($agencies_skype); ?>?call">
												<i class="fa fa-skype"></i>
											</a>
										<?php endif; ?>
										<?php if (!empty($agencies_linkedin_url)): ?>
											<a title="Linkedin" href="<?php echo esc_url($agencies_linkedin_url); ?>">
												<i class="fa fa-linkedin"></i>
											</a>
										<?php endif; ?>
										<?php if (!empty($agencies_pinterest_url)): ?>
											<a title="Pinterest" href="<?php echo esc_url($agencies_pinterest_url); ?>">
												<i class="fa fa-pinterest"></i>
											</a>
										<?php endif; ?>
										<?php if (!empty($agencies_instagram_url)): ?>
											<a title="Instagram" href="<?php echo esc_url($agencies_instagram_url); ?>">
												<i class="fa fa-instagram"></i>
											</a>
										<?php endif; ?>
										<?php if (!empty($agencies_youtube_url)): ?>
											<a title="Youtube" href="<?php echo esc_url($agencies_youtube_url); ?>">
												<i class="fa fa-youtube-play"></i>
											</a>
										<?php endif; ?>
										<?php if (!empty($agencies_vimeo_url)): ?>
											<a title="Vimeo" href="<?php echo esc_url($agencies_vimeo_url); ?>">
												<i class="fa fa-vimeo"></i>
											</a>
										<?php endif; ?>
									</div>
								</div>
								<?php
								$excerpt = $agency->description; ?>
								<?php if ( isset( $excerpt ) && ! empty( $excerpt ) ): ?>
									<div class="agency-excerpt fw-normal">
										<p><?php echo esc_html( $excerpt ) ?></p>
									</div>
								<?php endif; ?>
								<div class="agency-info">
									<div class="agency-info-inner fw-normal">
										<?php if (!empty($agencies_office_number)): ?>
											<div class="agency-info-item agency-office-number">
												<span class="agency-info-title heading-color"><i class="fa fa-phone accent-color"></i> <?php esc_html_e( 'Phone', 'essential-real-estate' ) ?>: </span>
												<span class="agency-info-value"><?php echo esc_attr($agencies_office_number) ?></span>
											</div>
										<?php endif; ?>
										<?php if (!empty($agencies_mobile_number)):?>
											<div class="agency-info-item agency-mobile-number">
												<span class="agency-info-title heading-color"><i class="fa fa-mobile-phone accent-color"></i> <?php esc_html_e( 'Mobile', 'essential-real-estate' ) ?>: </span>
												<span class="agency-info-value"><?php echo esc_attr($agencies_mobile_number) ?></span>
											</div>
										<?php endif;?>
										<?php if (!empty($agencies_fax_number)):?>
											<div class="agency-info-item agency-fax-number">
												<span class="agency-info-title heading-color"><i class="fa fa-print accent-color"></i> <?php esc_html_e( 'Fax', 'essential-real-estate' ) ?>: </span>
												<span class="agency-info-value"><?php echo esc_attr($agencies_fax_number) ?></span>
											</div>
										<?php endif;?>
										<?php if (!empty($agencies_email)): ?>
											<div class="agency-info-item agency-email">
												<span class="agency-info-title heading-color"><i class="fa fa-envelope accent-color"></i> <?php esc_html_e( 'Email', 'essential-real-estate' ) ?>: </span>
												<span class="agency-info-value"><?php echo esc_attr($agencies_email) ?></span>
											</div>
										<?php endif; ?>
										<?php if (!empty($agencies_website_url)): ?>
											<div class="agency-info-item agency-website">
												<span class="agency-info-title heading-color"><i class="fa fa-external-link-square accent-color"></i> <?php esc_html_e( 'Website', 'essential-real-estate' ) ?>: </span>
												<a href="<?php echo esc_url($agencies_website_url) ?>" title="" class="agency-info-value"><?php echo esc_url($agencies_website_url) ?></a>
											</div>
										<?php endif; ?>
										<?php if(!empty( $agencies_licenses )): ?>
											<div class="agency-info-item agency-licenses">
												<span class="agency-info-title heading-color"><i class="fa fa-balance-scale accent-color"></i> <?php esc_html_e( 'Licenses', 'essential-real-estate' ); ?>: </span>
												<span><?php echo esc_attr( $agencies_licenses ) ?></span>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach;
			else: ?>
				<div class="item-not-found"><?php esc_html_e( 'No item found', 'essential-real-estate' ); ?></div>
			<?php endif; ?>
		</div>
		<div class="clearfix"></div>
		<?php
		if ( $show_paging ) {?>
			<div class="agency-paging-wrap" data-admin-url="<?php echo ERE_AJAX_URL; ?>"
			     data-items-amount="<?php echo esc_attr( $item_amount ); ?>" >
				<?php
				$all_agencies = get_categories(array('taxonomy'=>'agencies'));
				$max_num_pages = floor(count( $all_agencies ) / $item_amount);
				if(count( $all_agencies ) % $item_amount > 0) {
					$max_num_pages++;
				}
				set_query_var( 'paged', $paged );
				ere_get_template( 'global/pagination.php', array( 'max_num_pages' => $max_num_pages ) );
				?>
			</div>
		<?php }
		wp_reset_postdata(); ?>
	</div>
</div>

