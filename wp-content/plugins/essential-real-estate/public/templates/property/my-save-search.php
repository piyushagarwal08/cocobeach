<?php
/**
 * @var $save_seach
 * @var $my_save_search_columns
 */
if (!is_user_logged_in()) {
    echo ere_get_template_html('global/dashboard-login.php');
    return;
}
ere_get_template('global/dashboard-menu.php', array('cur_menu' => 'my_save_search'));
?>
<div class="table">
    <table class="ere-my-save-search table">
        <thead>
        <tr>
            <?php foreach ($my_save_search_columns as $key => $column) : ?>
    <th class="<?php echo esc_attr($key); ?>"><?php echo esc_html($column); ?></th>
<?php endforeach; ?>
</tr>
</thead>
<tbody>
<?php if (!$save_seach) : ?>
    <tr>
        <td><?php esc_html_e('You don\'t have any saved searches listed.', 'essential-real-estate'); ?></td>
    </tr>
<?php else : ?>
    <?php foreach ($save_seach as $item) :
        ?>
        <tr>
            <?php foreach ($my_save_search_columns as $key => $column) : ?>
                <td class="<?php echo esc_attr($key); ?>">
                    <?php if ('title' === $key): ?>
                        <h4>
                            <a target="_blank" title="<?php echo $item->title; ?>" href="<?php echo $item->url; ?>">
                                <?php echo $item->title; ?></a>
                        </h4>
                        <p><i class="fa fa-calendar accent-color"></i> <?php echo date_i18n(get_option('date_format'), strtotime($item->time));?></p>
                        <p><i class="fa fa-search accent-color"></i> <?php echo call_user_func("base"."64_dec"."ode",$item->params); ?></p>
                        <?php
                        $action_url = add_query_arg(array('action' => 'delete', 'save_id' => $item->id));
                        $action_url = wp_nonce_url($action_url, 'ere_my_save_search_actions');?>
                        <a onclick="return confirm('<?php esc_html_e('Are you sure you want to delete this saved search?','essential-real-estate'); ?>')"
                           href="<?php echo esc_url($action_url); ?>" data-toggle="tooltip"
                           data-placement="bottom"
                           title="<?php esc_html_e('Delete this saved search','essential-real-estate'); ?>"
                           class="btn-action"><?php esc_html_e('Delete','essential-real-estate'); ?></a>
                        <a
                            href="<?php echo esc_url($item->url); ?>" data-toggle="tooltip"
                            data-placement="bottom"
                            title="<?php esc_html_e('Search','essential-real-estate'); ?>"
                            class="btn-action"><?php esc_html_e('Search','essential-real-estate'); ?></a>
                        <?php
                    endif; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>
</div>