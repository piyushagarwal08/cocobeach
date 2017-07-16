<?php
$agencies_term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
$agencies_content = get_term_meta( $agencies_term->term_id, 'agencies_des', true );
$agencies_address = get_term_meta( $agencies_term->term_id, 'agencies_address', true );
$agencies_map_address = get_term_meta( $agencies_term->term_id, 'agencies_map_address', true );

$agencies_email = get_term_meta( $agencies_term->term_id, 'agencies_email', true );
$agencies_mobile_number = get_term_meta( $agencies_term->term_id, 'agencies_mobile_number', true );
$agencies_fax_number = get_term_meta( $agencies_term->term_id, 'agencies_fax_number', true );

$agencies_licenses = get_term_meta( $agencies_term->term_id, 'agencies_licenses', true );

$agencies_office_number = get_term_meta( $agencies_term->term_id, 'agencies_office_number', true );
$agencies_website_url = get_term_meta( $agencies_term->term_id, 'agencies_website_url', true );
$agencies_vimeo_url = get_term_meta( $agencies_term->term_id, 'agencies_vimeo_url', true );
$agencies_facebook_url = get_term_meta( $agencies_term->term_id, 'agencies_facebook_url', true );
$agencies_twitter_url = get_term_meta( $agencies_term->term_id, 'agencies_twitter_url', true );
$agencies_googleplus_url = get_term_meta( $agencies_term->term_id, 'agencies_googleplus_url', true );
$agencies_linkedin_url = get_term_meta( $agencies_term->term_id, 'agencies_linkedin_url', true );
$agencies_pinterest_url = get_term_meta( $agencies_term->term_id, 'agencies_pinterest_url', true );
$agencies_instagram_url = get_term_meta( $agencies_term->term_id, 'agencies_instagram_url', true );
$agencies_skype = get_term_meta( $agencies_term->term_id, 'agencies_skype', true );
$agencies_youtube_url = get_term_meta( $agencies_term->term_id, 'agencies_youtube_url', true );

$enable_agents_of_agency = ere_get_option( 'enable_agents_of_agency', '1' );

// Query Property of Agency
$args = array(
    'post_type'      => 'agent',
    'post_status'    => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'agencies',
            'field'    => 'slug',
            'terms'    => array($agencies_term->slug),
            'operator' => 'IN'
        )
    )
);
$agent_data = new WP_Query($args);
$agent_id_arr = $author_id_arr = '';
if($agent_data->have_posts()){
    while ($agent_data->have_posts()){
        $agent_data->the_post();
        $agent_id_arr .= get_the_ID() . ',';
        $author_id_arr .= get_post_meta( get_the_ID(), ERE_METABOX_PREFIX . 'agent_user_id', true ) . ',';
    }
    $agent_id_arr = substr( $agent_id_arr, 0, strlen( $agent_id_arr ) - 1 );
    $author_id_arr = substr( $author_id_arr, 0, strlen( $author_id_arr ) - 1 );
}
$ere_property = new ERE_Property();
$total_property = $ere_property->get_total_properties_by_user($agent_id_arr, $author_id_arr);


$min_suffix_js = ere_get_option('enable_min_js', 0) == 1 ? '.min' : '';
wp_enqueue_script(ERE_PLUGIN_PREFIX . 'taxonomy-agencies', ERE_PLUGIN_URL . 'public/assets/js/agent/ere-taxonomy-agencies' . $min_suffix_js . '.js', array('jquery'), ERE_PLUGIN_VER, true);

$min_suffix = ere_get_option('enable_min_css', 0) == 1 ? '.min' : '';
wp_enqueue_style(ERE_PLUGIN_PREFIX . 'taxonomy-agencies', ERE_PLUGIN_URL . 'public/assets/css/taxonomy-agencies' . $min_suffix . '.css');
?>
    <div class="agency-single-info row">
<?php
$logo_src = get_term_meta($agencies_term->term_id, 'agencies_logo', true);
if ($logo_src && !empty($logo_src['url'])) {
    $logo_src = $logo_src['url'];
}
if (!empty($logo_src)) :
    list($width, $height) = getimagesize($logo_src); ?>
    <div class="agency-logo col-md-3 col-sm-5">
        <div class="agency-logo-inner">
            <img width="<?php echo esc_attr($width) ?>"
                 height="<?php echo esc_attr($height) ?>"
                 src="<?php echo esc_url($logo_src) ?>"
                 alt="<?php echo esc_attr($agencies_term->name) ?>"
                 title="<?php echo esc_attr($agencies_term->name) ?>">
        </div>
    </div>
    <div class="agency-content col-md-5 col-sm-7 xs-pd-top-60">
    <?php else: ?>
    <div class="agency-content col-md-8 col-sm-12">
<?php endif; ?>
    <div class="agency-content-top agency-title mg-bottom-25">
        <?php if (!empty($agencies_term->name)): ?>
            <h2 class="fs-32 fw-semi-bold heading-color mg-bottom-10"><?php echo esc_attr($agencies_term->name) ?></h2>
        <?php endif; ?>
        <?php if (!empty($agencies_address)): ?>
            <div class="agency-address fw-medium fs-16">
                <span><?php echo esc_attr($agencies_address) ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="agency-data fw-medium mg-bottom-10">
        <div class="agency-data-item agency-data-property">
                    <span
                        class="agency-data-item-title accent-color"><?php esc_html_e('Properties', 'essential-real-estate'); ?>: </span>
            <span><?php echo esc_attr($total_property) ?></span>
        </div>
        <div class="agency-data-item agency-data-agent">
            <span class="agency-data-item-title accent-color"><?php esc_html_e('Agents', 'essential-real-estate'); ?>: </span>
            <span><?php echo esc_attr($agencies_term->count) ?></span>
        </div>
        <?php if (!empty($agencies_licenses)): ?>
            <div class="agency-data-item agency-data-licenses">
                <span class="agency-data-item-title accent-color"><?php esc_html_e('Licenses', 'essential-real-estate'); ?>: </span>
                <span><?php echo esc_attr($agencies_licenses) ?></span>
            </div>
        <?php endif; ?>
    </div>
    <div class="agency-contact fw-normal">
        <div class="agency-info">
            <?php if (!empty($agencies_office_number)): ?>
                <div class="agency-info-item agency-office-number">
                            <span class="agency-info-title heading-color"><i
                                    class="fa fa-phone accent-color"></i> <?php esc_html_e('Phone', 'essential-real-estate') ?>: </span>
                    <span class="agency-info-value"><?php echo esc_attr($agencies_office_number) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($agencies_mobile_number)): ?>
                <div class="agency-info-item agency-mobile-number">
                            <span class="agency-info-title heading-color"><i
                                    class="fa fa-mobile-phone accent-color"></i> <?php esc_html_e('Mobile', 'essential-real-estate') ?>: </span>
                    <span class="agency-info-value"><?php echo esc_attr($agencies_mobile_number) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($agencies_fax_number)): ?>
                <div class="agency-info-item agency-fax-number">
                            <span class="agency-info-title heading-color"><i
                                    class="fa fa-print accent-color"></i> <?php esc_html_e('Fax', 'essential-real-estate') ?>: </span>
                    <span class="agency-info-value"><?php echo esc_attr($agencies_fax_number) ?></span>
                </div>
            <?php endif; ?>
            <?php if (!empty($agencies_website_url)): ?>
                <div class="agency-info-item agency-website">
                            <span class="agency-info-title heading-color"><i
                                    class="fa fa-external-link-square accent-color"></i> <?php esc_html_e('Website', 'essential-real-estate') ?>: </span>
                    <a href="<?php echo esc_url($agencies_website_url) ?>" title=""
                       class="agency-info-value"><?php echo esc_url($agencies_website_url) ?></a>
                </div>
            <?php endif; ?>
            <?php if (!empty($agencies_email)): ?>
                <div class="agency-info-item agency-email">
                            <span class="agency-info-title heading-color"><i
                                    class="fa fa-envelope accent-color"></i> <?php esc_html_e('Email', 'essential-real-estate') ?>: </span>
                    <span class="agency-info-value"><?php echo esc_attr($agencies_email) ?></span>
                </div>
            <?php endif; ?>
        </div>
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
    <div class="contact-agency col-md-4 col-sm-12 sm-pd-top-60">
        <div class="contact-agency-title">
            <h4 class="uppercase fw-semi-bold fs-18 mg-bottom-30"><i
                    class="icon-mail-envelope-open6 accent-color"></i> <?php esc_html_e('Contact This Company', 'essential-real-estate'); ?>
            </h4>
        </div>
        <form method="post" action="<?php echo ERE_AJAX_URL; ?>" id="contact-agent-form">
            <input type="hidden" name="target-email" value="<?php echo esc_attr($agencies_email); ?>">

            <div class="form-group">
                <input class="form-control" name="name" type="text"
                       placeholder="<?php esc_html_e('Full Name', 'essential-real-estate'); ?> *">

                <div
                    class="hidden name-error form-error"><?php esc_html_e('Please enter your Name!', 'essential-real-estate'); ?></div>
            </div>
            <div class="form-group">
                <input class="form-control" name="phone" type="text"
                       placeholder="<?php esc_html_e('Phone Number', 'essential-real-estate'); ?> *">

                <div
                    class="hidden phone-error form-error"><?php esc_html_e('Please enter your Phone!', 'essential-real-estate'); ?></div>
            </div>
            <div class="form-group">
                <input class="form-control" name="email" type="email"
                       placeholder="<?php esc_html_e('Email Adress', 'essential-real-estate'); ?> *">

                <div class="hidden email-error form-error"
                     data-not-valid="<?php esc_html_e('Your Email address is not Valid!', 'essential-real-estate') ?>"
                     data-error="<?php esc_html_e('Please enter your Email!', 'essential-real-estate') ?>"><?php esc_html_e('Please enter your Email!', 'essential-real-estate'); ?></div>
            </div>
            <div class="form-group">
                            <textarea class="form-control" name="message" rows="5"
                                      placeholder="<?php esc_html_e('Message', 'essential-real-estate'); ?> *"></textarea>

                <div
                    class="hidden message-error form-error"><?php esc_html_e('Please enter your Message!', 'essential-real-estate'); ?></div>
            </div>
            <button type="submit"
                    class="agent-contact-btn btn btn-block"><?php esc_html_e('Submit Request', 'essential-real-estate'); ?></button>
            <div class="form-messages"></div>
        </form>
    </div>
    </div>
<?php $enable_property_of_agency = ere_get_option('enable_property_of_agency', '1'); ?>
    <div class="agency-details-tab mg-top-45 pd-bottom-60 sm-pd-bottom-40">
        <ul class="nav nav-tabs">
            <?php if (!empty($agencies_content)): ?>
                <li class="active"><a data-toggle="tab"
                                      href="#agency-overview"><?php esc_html_e('Overview', 'essential-real-estate'); ?></a>
                </li>
            <?php endif; ?>
            <?php if ($enable_property_of_agency): ?>
                <li<?php if (empty($agencies_content)): ?> class="active"<?php endif; ?>><a data-toggle="tab"
                                                                                            href="#agency-properties"><?php esc_html_e('Properties', 'essential-real-estate'); ?></a>
                </li>
            <?php endif; ?>
            <?php if (!empty($agencies_map_address['location'])): ?>
                <li<?php if (empty($agencies_content) && !$enable_property_of_agency): ?> class="active"<?php endif; ?>>
                    <a data-toggle="tab" href="#agency-location"><?php esc_html_e('Location', 'essential-real-estate'); ?></a>
                </li>
            <?php endif; ?>
        </ul>
        <div class="tab-content">
            <?php if (!empty($agencies_content)): ?>
                <div id="agency-overview" class="tab-pane fade in active">
                    <?php echo wp_kses_post($agencies_content); ?>
                </div>
            <?php endif; ?>
            <?php if ($enable_property_of_agency): ?>
                <div id="agency-properties"
                     class="tab-pane fade in<?php if (empty($agencies_content)): ?> active<?php endif; ?>">
                    <?php
                    $property_of_agency_layout_style = ere_get_option('property_of_agency_layout_style', 'property-grid');
                    $property_of_agency_items_amount = ere_get_option('property_of_agency_items_amount', 6);
                    $property_of_agency_image_size = ere_get_option('property_of_agency_image_size', '280x180');
                    $property_of_agency_show_paging = ere_get_option('property_of_agency_show_paging', array());

                    $property_of_agency_column_lg = ere_get_option('property_of_agency_column_lg', '3');
                    $property_of_agency_column_md = ere_get_option('property_of_agency_column_md', '3');
                    $property_of_agency_column_sm = ere_get_option('property_of_agency_column_sm', '2');
                    $property_of_agency_column_xs = ere_get_option('property_of_agency_column_xs', '1');
                    $property_of_agency_column_mb = ere_get_option('property_of_agency_column_mb', '1');

                    $property_of_agency_columns_gap = ere_get_option('property_of_agency_columns_gap', 'col-gap-30');

                    if (!is_array($property_of_agency_show_paging)) {
                        $property_of_agency_show_paging = array();
                    }

                    if (in_array("show_paging_property_of_agency", $property_of_agency_show_paging)) {
                        $property_of_agency_show_paging = 'true';
                    } else {
                        $property_of_agency_show_paging = '';
                        $property_of_agency_items_amount = '-1';
                    }

                    $property_agency_shortcode = '[ere_property layout_style = "' . $property_of_agency_layout_style . '"
                                item_amount = "' . $property_of_agency_items_amount . '" columns="' . $property_of_agency_column_lg . '"
                                items_md="' . $property_of_agency_column_md . '"
                                items_sm="' . $property_of_agency_column_sm . '"
                                items_xs="' . $property_of_agency_column_xs . '"
                                items_mb="' . $property_of_agency_column_mb . '"
                                image_size = "' . $property_of_agency_image_size . '"
                                columns_gap = "' . $property_of_agency_columns_gap . '"
                                show_paging = "' . $property_of_agency_show_paging . '"
                                author_id = "' . $author_id_arr . '" agent_id = "' . $agent_id_arr . '"]';
                    echo do_shortcode($property_agency_shortcode);
                    ?>
                </div>
            <?php endif; ?>
            <div id="agency-location"
                 class="tab-pane fade in<?php if (empty($agencies_content) && !$enable_property_of_agency): ?> active<?php endif; ?>">
                <?php
                list($lat, $lng) = explode(',', $agencies_map_address['location']);
                $map_shortcode = '[ere_property_map map_style="normal"
                            lat="' . $lat . '" lng="' . $lng . '" map_height=""400px]';
                echo do_shortcode($map_shortcode);
                ?>
            </div>
        </div>
    </div>
<?php
$agencies_term_slug=$agencies_term->slug;
do_action( 'ere_taxonomy_agencies_agents', $agencies_term_slug); ?>