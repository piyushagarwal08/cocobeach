<?php
/**
 * @var $my_properties_columns
 * @var $properties
 * @var $max_num_pages
 * @var $post_status
 * @var $title
 * @var $property_id
 * @var $property_status
 */
if(!is_user_logged_in()){
    echo ere_get_template_html('global/dashboard-login.php');
    return;
}
$user_can_submit = ere_get_option('user_can_submit', 1);
if ($user_can_submit!=1)
{
    wp_redirect(home_url());
}
ere_get_template('global/dashboard-menu.php', array('cur_menu' => 'my_properties'));
$post_status_all = ere_get_permalink('my_properties');
$ere_property=new ERE_Property();
$total_properties = $ere_property->get_total_my_properties(array('publish', 'pending', 'expired', 'hidden'));
$post_status_approved = add_query_arg(array('post_status' => 'publish'));
$total_approved = $ere_property->get_total_my_properties('publish');
$post_status_pending = add_query_arg(array('post_status' => 'pending'));
$total_pending = $ere_property->get_total_my_properties('pending');
$post_status_expired = add_query_arg(array('post_status' => 'expired'));
$total_expired = $ere_property->get_total_my_properties('expired');

$post_status_hidden = add_query_arg(array('post_status' => 'hidden'));
$total_hidden = $ere_property->get_total_my_properties('hidden');

$paid_submission_type = ere_get_option('paid_submission_type', 'no');
$ere_profile=new ERE_Profile();
global $current_user;
wp_get_current_user();
$user_id = $current_user->ID;
?>
    <ul class="ere-my-properties-filter">
        <li class="ere-status-all<?php if (is_array($post_status)) echo ' active' ?>"><a
                href="<?php echo esc_url($post_status_all); ?>"><?php printf(__('All (%s)', 'essential-real-estate'),$total_properties);?></a>
        </li>
        <li class="ere-status-publish<?php if ($post_status == 'publish') echo ' active' ?>"><a
                href="<?php echo esc_url($post_status_approved); ?>">
                <?php printf(__('Approved (%s)', 'essential-real-estate'),$total_approved);?></a>
        </li>
        <li class="ere-status-pending<?php if ($post_status == 'pending') echo ' active' ?>"><a
                href="<?php echo esc_url($post_status_pending); ?>">
                <?php printf(__('Pending (%s)', 'essential-real-estate'),$total_pending);?></a>
        </li>
        <li class="ere-status-expired<?php if ($post_status == 'expired') echo ' active' ?>"><a
                href="<?php echo esc_url($post_status_expired); ?>">
                <?php printf(__('Expired (%s)', 'essential-real-estate'),$total_expired);?></a>
        </li>
        <li class="ere-status-hidden<?php if ($post_status == 'hidden') echo ' active' ?>"><a
                href="<?php echo esc_url($post_status_hidden); ?>">
                <?php printf(__('Hidden (%s)', 'essential-real-estate'),$total_hidden);?></a>
        </li>
    </ul>
    <form method="get" class="form-inline ere-my-properties-search">
        <div class="form-group">
            <label class="sr-only" for="property_status"><?php esc_html_e('Property Status', 'essential-real-estate');?></label>
            <select name="property_status" id="property_status" title="<?php esc_html_e('Property Status', 'essential-real-estate') ?>">
                <?php ere_get_taxonomy_slug('property-status',$property_status); ?>
                <option
                    value="" <?php if (empty($property_status)) echo esc_attr('selected'); ?>>
                    <?php esc_html_e('All Status', 'essential-real-estate') ?>
                </option>
            </select>
        </div>
        <div class="form-group">
            <label class="sr-only" for="property_id"><?php esc_html_e('Property ID', 'essential-real-estate');?></label>
            <input type="text" name="property_id" id="property_id"
                   value="<?php echo esc_attr($property_id); ?>"
                   class="form-control"
                   placeholder="<?php esc_html_e('Property ID', 'essential-real-estate'); ?>">
        </div>
        <div class="form-group">
            <label class="sr-only" for="title"><?php esc_html_e('Title', 'essential-real-estate');?></label>
            <input type="text" name="title" id="title"
                   value="<?php echo esc_attr($title); ?>"
                   class="form-control"
                   placeholder="<?php esc_html_e('Title', 'essential-real-estate'); ?>">
        </div>
        <button type="submit" class="btn btn-default"><?php esc_html_e('Search', 'essential-real-estate'); ?></button>
    </form>
    <div class="table-responsive">
        <table class="ere-my-properties table">
            <thead>
            <tr>
                <?php foreach ($my_properties_columns as $key => $column) : ?>
                    <th class="<?php echo esc_attr($key); ?>"><?php echo esc_html($column); ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php if (!$properties) : ?>
                <tr>
                    <td colspan="6"><?php esc_html_e('You don\'t have any properties listed.', 'essential-real-estate'); ?></td>
                </tr>
            <?php else : ?>
                <?php foreach ($properties as $property) : ?>
                    <tr>
                        <?php foreach ($my_properties_columns as $key => $column) : ?>
                            <td class="<?php echo esc_attr($key); ?>">
                                <?php if ('picture' === $key) :
                                    $property_item_status = get_the_terms( $property->ID, 'property-status' );
                                    if( $property_item_status ): ?>
                                    <div class="property-status">
                                        <?php foreach ( $property_item_status as $status ): ?>
                                            <?php $status_color = get_term_meta( $status->term_id, 'property_status_color', true ); ?>
                                            <p class="status-item">
                                                <span class="property-status-bg"
                                                      style="background-color: <?php echo esc_attr( $status_color ) ?>"><?php echo esc_attr( $status->name ) ?>
                                                </span>
                                            </p>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php endif;
                                    if ($property->post_status == 'publish') : ?>
                                        <a target="_blank" title="<?php echo $property->post_title; ?>" href="<?php echo get_permalink($property->ID); ?>">
                                            <?php echo get_the_post_thumbnail($property->ID, 'thumbnail'); ?>
                                        </a>
                                    <?php else :
                                            echo get_the_post_thumbnail($property->ID, 'thumbnail');
                                    endif;
                                elseif ('title' === $key) : ?>
                                    <?php if ($property->post_status == 'publish') : ?>
                                        <h4>
                                            <a target="_blank" title="<?php echo $property->post_title; ?>" href="<?php echo get_permalink($property->ID); ?>"><?php echo $property->post_title; ?></a>
                                        </h4>
                                    <?php else : ?>
                                        <h4><?php echo $property->post_title; ?></h4>
                                    <?php endif; ?>
                                    <?php
                                    $price = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_price', true);
                                    $price_postfix=get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_price_postfix', true);
                                    if ( ! empty( $price ) ): ?>
                                        <span class="btn-block fw-bold"><?php echo ere_get_format_money( $price ) ?><?php if(!empty( $price_postfix )) {echo '<span class="fs-12 accent-color">/'.$price_postfix.'</span>';} ?></span>
                                    <?php elseif (ere_get_option( 'empty_price_text', '' ) != '' ): ?>
                                        <span class="btn-block fw-bold"><?php echo ere_get_option( 'empty_price_text', '' ) ?></span>
                                    <?php endif; ?>
                                    <span class="btn-block"><i class="fa fa-map-marker accent-color"></i>
                                        <?php echo get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_address', true); ?>
                                    </span>
                                    <span class="btn-block mg-bottom-10"><i class="fa fa-eye accent-color"></i>
                                        <?php
                                        $total_views= $ere_property->get_total_views($property->ID);
                                        if($total_views<2)
                                        {
                                            printf(esc_html__('%s view','essential-real-estate'),$total_views);
                                        }
                                        else
                                        {
                                            printf(esc_html__('%s views','essential-real-estate'),$total_views);
                                        }
                                        ?>
                                    </span>
                                    <ul class="ere-dashboard-actions">
                                        <?php
                                        $actions = array();
                                        $payment_status = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'payment_status', true);
                                        switch ($property->post_status) {
                                            case 'publish' :
                                                $prop_featured = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_featured', true);
                                                if ($paid_submission_type == 'per_package') {
                                                    $current_package_key = get_the_author_meta(ERE_METABOX_PREFIX . 'package_key', $user_id);
                                                    $property_package_key = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'package_key', true);

                                                    $check_package=$ere_profile->user_package_available($user_id);
                                                    if(!empty($property_package_key) && $current_package_key==$property_package_key)
                                                    {
                                                        if($check_package!=-1 && $check_package!=0)
                                                        {
                                                            $actions['edit'] = array('label' => __('Edit', 'essential-real-estate'),'tooltip' => __('Edit property', 'essential-real-estate'), 'nonce' => false, 'confirm' => '');
                                                        }
                                                        $package_num_featured_listings = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_featured', $user_id);
                                                        if ($package_num_featured_listings > 0 && ($prop_featured != 1) && ($check_package!=-1)  && ($check_package!= 0)) {
                                                            $actions['mark_featured'] = array('label' => __('Mark featured', 'essential-real-estate'),'tooltip' => __('Make this a Featured Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to mark this property as Featured?', 'essential-real-estate'));
                                                        }
                                                    }
                                                    elseif( $current_package_key!=$property_package_key && $check_package==1)
                                                    {
                                                        $actions['allow_edit'] = array('label' => __('Allow Editing', 'essential-real-estate'),'tooltip' => __('This property listing belongs to an expired Package therefore if you wish to edit it, it will be charged as a new listing from your current Package.', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to allow editing this property listing?', 'essential-real-estate'));
                                                    }
                                                }
                                                else
                                                {
                                                    if ($paid_submission_type != 'no' && $prop_featured != 1) {
                                                        $actions['mark_featured'] = array('label' => __('Mark featured', 'essential-real-estate'),'tooltip' => __('Make this a Featured Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to mark this property as Featured?', 'essential-real-estate'));
                                                    }
                                                    $actions['edit'] = array('label' => __('Edit', 'essential-real-estate'),'tooltip' => __('Edit Property', 'essential-real-estate'), 'nonce' => false, 'confirm' => '');
                                                }

                                                break;
                                            case 'expired' :
                                                if ($paid_submission_type == 'per_package') {
                                                    $check_package=$ere_profile->user_package_available($user_id);
                                                    if($check_package==1)
                                                    {
                                                        $actions['relist_per_package'] = array('label' => __('Reactivate Listing', 'essential-real-estate'),'tooltip' => __('Reactivate Listing', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to reactivate this property?', 'essential-real-estate'));
                                                    }
                                                }
                                                if ($paid_submission_type == 'per_listing' && $payment_status == 'paid') {
                                                    $actions['relist_per_listing'] = array('label' => __('Resend this Listing for Approval', 'essential-real-estate'),'tooltip' => __('Resend this Listing for Approval', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to resend this property for approval?', 'essential-real-estate'));
                                                }
                                                break;
                                            case 'pending' :
                                                $actions['edit'] = array('label' => __('Edit', 'essential-real-estate'),'tooltip' => __('Edit Property', 'essential-real-estate'), 'nonce' => false, 'confirm' => '');
                                                break;
                                            case 'hidden' :
                                                $actions['show'] = array('label' => __('Show', 'essential-real-estate'),'tooltip' => __('Show Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to show this property?', 'essential-real-estate'));
                                                break;
                                        }
                                        $actions['delete'] = array('label' => __('Delete', 'essential-real-estate'),'tooltip' => __('Delete Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to delete this property?', 'essential-real-estate'));
                                        if ($property->post_status == 'publish') {
                                            $actions['hidden'] = array('label' => __('Hide', 'essential-real-estate'),'tooltip' => __('Hide Property', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to hide this property?', 'essential-real-estate'));
                                        }

                                        if ($paid_submission_type == 'per_listing' && $payment_status != 'paid' && $property->post_status != 'hidden') {
                                            $actions['payment_listing'] = array('label' => __('Pay Now', 'essential-real-estate'),'tooltip' => __('Pay for this property listing', 'essential-real-estate'), 'nonce' => true, 'confirm' => esc_html__('Are you sure you want to pay for this listing?', 'essential-real-estate'));
                                        }

                                        $actions = apply_filters('ere_my_properties_actions', $actions, $property);
                                        foreach ($actions as $action => $value) {
                                            $action_url = add_query_arg(array('action' => $action, 'property_id' => $property->ID));
                                            if ($value['nonce']) {
                                                $action_url = wp_nonce_url($action_url, 'ere_my_properties_actions');
                                            }
                                            ?>
                                            <li>
                                                <a <?php if (!empty($value['confirm'])): ?> onclick="return confirm('<?php echo esc_html($value['confirm']); ?>')" <?php endif; ?>
                                                    href="<?php echo esc_url($action_url); ?>" data-toggle="tooltip"
                                                    data-placement="bottom"
                                                    title="<?php echo esc_html($value['tooltip']); ?>"
                                                    class="btn-action ere-dashboard-action-<?php echo esc_attr($action); ?>"><?php echo esc_html($value['label']); ?></a>
                                            </li>
                                            <?php
                                        }

                                        ?>
                                    </ul>
                                <?php elseif ('id' === $key):
                                    $property_identity = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_identity', true);
                                    echo $property_identity;
                                ?>
                                <?php elseif ('date' === $key) :
                                    echo date_i18n(get_option('date_format'), strtotime($property->post_date));
                                    $listing_expire = ere_get_option('per_listing_expire_days');
                                    if ($paid_submission_type == 'per_listing' && $listing_expire == 1) :
                                        $number_expire_days = ere_get_option('number_expire_days');
                                        $property_date=$property->post_date;
                                        $expired_date = strtotime($property_date) + intval($number_expire_days) * 24 * 60 * 60;
                                        $expired_date=date('Y-m-d H:i:s',$expired_date);
                                        $expired_date = new DateTime($expired_date);
                                        $now = new DateTime();
                                        $interval = $now->diff($expired_date);
                                        $days = $interval->d;
                                        $hours = $interval->h;
                                        $invert=$interval->invert;
                                        if($invert==0)
                                        {
                                            if($days>0)
                                            {
                                                echo '<br><span class="badge">'. sprintf( __( 'Expire: %s days %s hours', 'essential-real-estate' ), $days, $hours).'</span>';
                                            }
                                            else
                                            {
                                                echo '<br><span class="badge">'. sprintf( __( 'Expire: %s hours', 'essential-real-estate' ), $hours).'</span>';
                                            }
                                        }
                                        else
                                        {
                                            echo '<br><span class="badge">'. sprintf( __( 'Expire: -%s days', 'essential-real-estate' ), $days).'</span>';
                                        }
                                    endif;
                                elseif ('status' === $key):
                                    switch ($property->post_status) {
                                        case 'publish':
                                            esc_html_e('Published','essential-real-estate');
                                            break;
                                        case 'expired':
                                            esc_html_e('Expired','essential-real-estate');
                                            break;
                                        case 'pending':
                                            esc_html_e('Pending','essential-real-estate');
                                            break;
                                        case 'hidden':
                                            esc_html_e('Hidden','essential-real-estate');
                                            break;
                                        default:
                                            echo $property->post_status;
                                    }
                                elseif ('featured' === $key):
                                    $prop_featured = get_post_meta($property->ID, ERE_METABOX_PREFIX . 'property_featured', true);
                                    if ($prop_featured == 1):?>
                                        <span data-toggle="tooltip"
                                              data-placement="bottom"
                                              title="<?php esc_html_e('Featured', 'essential-real-estate') ?>"
                                              class="fa fa-star accent-color"></span>
                                    <?php else: ?>
                                        <span data-toggle="tooltip"
                                              data-placement="bottom"
                                              title="<?php esc_html_e('Not Featured', 'essential-real-estate') ?>"
                                              class="fa fa-minus"></span>
                                    <?php endif;
                                else:
                                    do_action('ere_my_properties_column_' . $key, $property); ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<br>
<?php ere_get_template('global/pagination.php', array('max_num_pages' => $max_num_pages)); ?>