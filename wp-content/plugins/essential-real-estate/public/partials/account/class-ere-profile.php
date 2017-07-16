<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('ERE_Profile')) {
    /**
     * Class ERE_Profile
     */
    class ERE_Profile
    {
        /**
         * Upload profile avatar
         */
        public function profile_image_upload_ajax()
        {
            // Verify Nonce
            $nonce = $_REQUEST['nonce'];
            if (!wp_verify_nonce($nonce, 'ere_allow_upload_nonce')) {
                $ajax_response = array('success' => false, 'reason' => 'Security check failed!');
                echo json_encode($ajax_response);
                wp_die();
            }

            $submitted_file = $_FILES['ere_upload_file'];
            $uploaded_image = wp_handle_upload($submitted_file, array('test_form' => false));

            if (isset($uploaded_image['file'])) {
                $file_name = basename($submitted_file['name']);
                $file_type = wp_check_filetype($uploaded_image['file']);
                $attachment_details = array(
                    'guid' => $uploaded_image['url'],
                    'post_mime_type' => $file_type['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $attach_id = wp_insert_attachment($attachment_details, $uploaded_image['file']);
                $attach_data = wp_generate_attachment_metadata($attach_id, $uploaded_image['file']);
                wp_update_attachment_metadata($attach_id, $attach_data);

                $thumbnail_url = wp_get_attachment_thumb_url($attach_id);

                $ajax_response = array(
                    'success' => true,
                    'url' => $thumbnail_url,
                    'attachment_id' => $attach_id
                );

                echo json_encode($ajax_response);
                wp_die();

            } else {
                $ajax_response = array('success' => false, 'reason' => 'Image upload failed!');
                echo json_encode($ajax_response);
                wp_die();
            }
        }

        /**
         * Update profile
         */
        public function update_profile_ajax()
        {
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            check_ajax_referer('ere_update_profile_ajax_nonce', 'ere_security_update_profile');

            $user_firstname = $user_lastname = $user_des = $user_position = $user_email = $user_mobile_number = $user_fax_number = $user_company = $user_office_number = $user_office_address = $user_facebook_url = $user_twitter_url = $user_googleplus_url = $user_linkedin_url = $user_pinterest_url = $user_instagram_url = $user_skype = $user_youtube_url = $user_vimeo_url = $user_website_url = '';
            $profile_pic_id = '';

            // Update first name
            if (!empty($_POST['user_firstname'])) {
                $user_firstname = sanitize_text_field($_POST['user_firstname']);
                update_user_meta($user_id, 'first_name', $user_firstname);
            } else {
                delete_user_meta($user_id, 'first_name');
            }

            // Update last name
            if (!empty($_POST['user_lastname'])) {
                $user_lastname = sanitize_text_field($_POST['user_lastname']);
                update_user_meta($user_id, 'last_name', $user_lastname);
            } else {
                delete_user_meta($user_id, 'last_name');
            }

            // Update author_position
            if (!empty($_POST['user_position'])) {
                $user_position = sanitize_text_field($_POST['user_position']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_position', $user_position);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_position');
            }
            // Update author_fax_number
            if (!empty($_POST['user_fax_number'])) {
                $user_fax_number = sanitize_text_field($_POST['user_fax_number']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_fax_number', $user_fax_number);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_fax_number');
            }
            // Update author_company
            if (!empty($_POST['user_company'])) {
                $user_company = sanitize_text_field($_POST['user_company']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_company', $user_company);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_company');
            }
            // Update author_company
            if (!empty($_POST['user_licenses'])) {
                $user_licenses = sanitize_text_field($_POST['user_licenses']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_licenses', $user_licenses);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_licenses');
            }
            if (!empty($_POST['user_office_address'])) {
                $user_office_address = sanitize_text_field($_POST['user_office_address']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_address', $user_office_address);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_address');
            }

            // Update Phone
            if (!empty($_POST['user_office_number'])) {
                $user_office_number = sanitize_text_field($_POST['user_office_number']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_number', $user_office_number);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_office_number');
            }

            // Update Mobile
            if (!empty($_POST['user_mobile_number'])) {
                $user_mobile_number = sanitize_text_field($_POST['user_mobile_number']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_mobile_number', $user_mobile_number);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_mobile_number');
            }

            // Update Skype
            if (!empty($_POST['user_skype'])) {
                $user_skype = sanitize_text_field($_POST['user_skype']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_skype', $user_skype);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_skype');
            }

            // Update facebook
            if (!empty($_POST['user_facebook_url'])) {
                $user_facebook_url = sanitize_text_field($_POST['user_facebook_url']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_facebook_url', $user_facebook_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_facebook_url');
            }

            // Update twitter
            if (!empty($_POST['user_twitter_url'])) {
                $user_twitter_url = sanitize_text_field($_POST['user_twitter_url']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_twitter_url', $user_twitter_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_twitter_url');
            }

            // Update linkedin
            if (!empty($_POST['user_linkedin_url'])) {
                $user_linkedin_url = sanitize_text_field($_POST['user_linkedin_url']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_linkedin_url', $user_linkedin_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_linkedin_url');
            }

            // Update instagram
            if (!empty($_POST['user_instagram_url'])) {
                $user_instagram_url = sanitize_text_field($_POST['user_instagram_url']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_instagram_url', $user_instagram_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_instagram_url');
            }

            // Update pinterest
            if (!empty($_POST['user_pinterest_url'])) {
                $user_pinterest_url = sanitize_text_field($_POST['user_pinterest_url']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_pinterest_url', $user_pinterest_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_pinterest_url');
            }

            // Update youtube
            if (!empty($_POST['user_youtube_url'])) {
                $user_youtube_url = sanitize_text_field($_POST['user_youtube_url']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_youtube_url', $user_youtube_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_youtube_url');
            }

            // Update vimeo
            if (!empty($_POST['user_vimeo_url'])) {
                $user_vimeo_url = sanitize_text_field($_POST['user_vimeo_url']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_vimeo_url', $user_vimeo_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_vimeo_url');
            }

            // Update Googleplus
            if (!empty($_POST['user_googleplus_url'])) {
                $user_googleplus_url = sanitize_text_field($_POST['user_googleplus_url']);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_googleplus_url', $user_googleplus_url);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_googleplus_url');
            }

            // Update Profile Picture
            if (!empty($_POST['profile_pic'])) {
                $profile_pic_id = sanitize_text_field($_POST['profile_pic']);
                $profile_pic = wp_get_attachment_url($profile_pic_id);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_custom_picture', $profile_pic);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_picture_id', $profile_pic_id);
            } else {
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_custom_picture');
                delete_user_meta($user_id, ERE_METABOX_PREFIX . 'author_picture_id');
            }
            // Update About
            if (!empty($_POST['user_des'])) {
                $user_des = sanitize_text_field($_POST['user_des']);
                wp_update_user(array('ID' => $user_id, 'description' => $user_des));
            } else {
                $user_des = '';
                wp_update_user(array('ID' => $user_id, 'description' => $user_des));
            }
            // Update website
            if (!empty($_POST['user_website_url'])) {
                $user_website_url = sanitize_text_field($_POST['user_website_url']);
                wp_update_user(array('ID' => $user_id, 'user_url' => $user_website_url));
            } else {
                $user_website_url = '';
                wp_update_user(array('ID' => $user_id, 'user_url' => $user_website_url));
            }
            // Update email
            if (!empty($_POST['user_email'])) {
                $user_email = sanitize_email($_POST['user_email']);
                $user_email = is_email($user_email);
                if (!$user_email) {
                    echo json_encode(array('success' => false, 'message' => esc_html__('The Email you entered is not valid. Please try again.', 'essential-real-estate')));
                    wp_die();
                } else {
                    $email_exists = email_exists($user_email);
                    if ($email_exists) {
                        if ($email_exists != $user_id) {
                            echo json_encode(array('success' => false, 'message' => esc_html__('This Email is already used by another user. Please try a different one.', 'essential-real-estate')));
                            wp_die();
                        }
                    } else {
                        $return = wp_update_user(array('ID' => $user_id, 'user_email' => $user_email));
                        if (is_wp_error($return)) {
                            $error = $return->get_error_message();
                            echo esc_attr($error);
                            wp_die();
                        }
                    }
                }
            }
            $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
            $user_as_agent = ere_get_option('user_as_agent', 1);
            if ($user_as_agent == 1 && !empty($agent_id) && (get_post_type($agent_id) == 'agent')) {
                if (!empty($user_firstname) || !empty($user_lastname)) {
                    wp_update_post(array(
                        'ID' => $agent_id,
                        'post_title' => $user_firstname . ' ' . $user_lastname,
                        'post_content' => $user_des
                    ));
                }
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_description', $user_des);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_position', $user_position);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_email', $user_email);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_mobile_number', $user_mobile_number);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_fax_number', $user_fax_number);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_company', $user_company);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_number', $user_office_number);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_address', $user_office_address);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_facebook_url', $user_facebook_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_twitter_url', $user_twitter_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_googleplus_url', $user_googleplus_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_linkedin_url', $user_linkedin_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_pinterest_url', $user_pinterest_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_instagram_url', $user_instagram_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_skype', $user_skype);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_youtube_url', $user_youtube_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_vimeo_url', $user_vimeo_url);
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_website_url', $user_website_url);
                update_post_meta($agent_id, '_thumbnail_id', $profile_pic_id);
            }
            echo json_encode(array('success' => true, 'message' => esc_html__('Profile updated', 'essential-real-estate')));
            wp_die();
        }

        /**
         * Register user as seller
         */
        public function leave_agent_ajax()
        {
            check_ajax_referer('ere_leave_agent_ajax_nonce', 'ere_security_leave_agent');
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
            if (!empty($agent_id) && (get_post_type($agent_id) == 'agent')) {
                wp_delete_post($agent_id);
                update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_agent_id', '');
            }
            $ajax_response = array('success' => true, 'message' => 'Success!');
            echo json_encode($ajax_response);
            wp_die();
        }
        /**
         * Register user as seller
         */
        public function register_user_as_agent_ajax()
        {
            check_ajax_referer('ere_become_agent_ajax_nonce', 'ere_security_become_agent');
            $user_as_agent = ere_get_option('user_as_agent', 1);
            if ($user_as_agent == 1) {
                global $current_user;
                wp_get_current_user();
                $user_id = $current_user->ID;
                $full_name = $current_user->user_login;
                $agent_firstname = $current_user->first_name;
                $agent_lastname = $current_user->last_name;
                $agent_description = $current_user->description;
                if (!empty($agent_firstname) || !empty($agent_lastname)) {
                    $full_name=$agent_firstname . ' ' . $agent_lastname;
                }
                $post_status='publish';
                $auto_approved_agent = ere_get_option('auto_approved_agent', 1);
                if($auto_approved_agent!=1)
                {
                    $post_status='pending';
                }
                //Insert Agent
                $agent_id = wp_insert_post(array(
                    'post_title' => $full_name,
                    'post_type' => 'agent',
                    'post_status' => $post_status,
                    'post_content' => $agent_description
                ));
                if ($agent_id > 0) {
                    if($auto_approved_agent!=1)
                    {
                        $args = array(
                            'agent_name' => $full_name,
                            'agent_url' => get_permalink($agent_id)
                        );
                        $admin_email = get_bloginfo('admin_email');
                        ere_send_email($admin_email, 'mail_approved_agent', $args);
                    }
                    update_user_meta($user_id, ERE_METABOX_PREFIX . 'author_agent_id', $agent_id);
                    $agent_email = $current_user->user_email;
                    $agent_website_url = $current_user->user_url;
                    $agent_mobile_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_mobile_number', $user_id);
                    $agent_fax_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_fax_number', $user_id);
                    $agent_company = get_the_author_meta(ERE_METABOX_PREFIX . 'author_company', $user_id);
                    $agent_licenses = get_the_author_meta(ERE_METABOX_PREFIX . 'author_licenses', $user_id);
                    $agent_office_number = get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_number', $user_id);
                    $agent_office_address = get_the_author_meta(ERE_METABOX_PREFIX . 'author_office_address', $user_id);
                    $agent_facebook_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_facebook_url', $user_id);
                    $agent_twitter_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_twitter_url', $user_id);
                    $agent_linkedin_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_linkedin_url', $user_id);
                    $agent_pinterest_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_pinterest_url', $user_id);
                    $agent_instagram_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_instagram_url', $user_id);
                    $agent_googleplus_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_googleplus_url', $user_id);
                    $agent_youtube_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_youtube_url', $user_id);
                    $agent_vimeo_url = get_the_author_meta(ERE_METABOX_PREFIX . 'author_vimeo_url', $user_id);
                    $agent_skype = get_the_author_meta(ERE_METABOX_PREFIX . 'author_skype', $user_id);
                    $agent_position = get_the_author_meta(ERE_METABOX_PREFIX . 'author_position', $user_id);
                    $author_picture_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_picture_id', $user_id);

                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_user_id', $user_id);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_description', $agent_description);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_position', $agent_position);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_email', $agent_email);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_mobile_number', $agent_mobile_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_fax_number', $agent_fax_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_company', $agent_company);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_licenses', $agent_licenses);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_number', $agent_office_number);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_office_address', $agent_office_address);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_facebook_url', $agent_facebook_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_twitter_url', $agent_twitter_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_googleplus_url', $agent_googleplus_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_linkedin_url', $agent_linkedin_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_pinterest_url', $agent_pinterest_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_instagram_url', $agent_instagram_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_skype', $agent_skype);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_youtube_url', $agent_youtube_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_vimeo_url', $agent_vimeo_url);
                    update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_website_url', $agent_website_url);
                    update_post_meta($agent_id, '_thumbnail_id', $author_picture_id);
                    if($auto_approved_agent!=1)
                    {
                        $ajax_response = array('success' => true, 'message' => 'You have successfully registered and is pending approval by an admin!');
                    }
                    else
                    {
                        $ajax_response = array('success' => true, 'message' => 'You have successfully registered!');
                    }
                } else {
                    $ajax_response = array('success' => true, 'message' => 'Failed!');
                }
            } else {
                $ajax_response = array('success' => false, 'message' => 'Failed!');
            }
            echo json_encode($ajax_response);
            wp_die();
        }

        /**
         * Change password
         */
        public function change_password_ajax()
        {
            check_ajax_referer('ere_change_password_ajax_nonce', 'ere_security_change_password');
            global $current_user;
            wp_get_current_user();
            $user_id = $current_user->ID;
            $allowed_html = array();

            $oldpass = wp_kses($_POST['oldpass'], $allowed_html);
            $newpass = wp_kses($_POST['newpass'], $allowed_html);
            $confirmpass = wp_kses($_POST['confirmpass'], $allowed_html);

            if ($newpass == '' || $confirmpass == '') {
                echo json_encode(array('success' => false, 'message' => esc_html__('New password or confirm password is blank', 'essential-real-estate')));
                wp_die();
            }
            if ($newpass != $confirmpass) {
                echo json_encode(array('success' => false, 'message' => esc_html__('Passwords do not match', 'essential-real-estate')));
                wp_die();
            }

            $user = get_user_by('id', $user_id);
            if ($user && wp_check_password($oldpass, $user->data->user_pass, $user_id)) {
                wp_set_password($newpass, $user_id);
                echo json_encode(array('success' => true, 'message' => esc_html__('Password Updated', 'essential-real-estate')));
            } else {
                echo json_encode(array('success' => false, 'message' => esc_html__('Old password is not correct', 'essential-real-estate')));
            }
            wp_die();
        }

        /**
         * user_info
         * @param $info
         * @return mixed
         */
        public function user_info($info)
        {
            $info[ERE_METABOX_PREFIX . 'author_position'] = esc_html__('Position', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_mobile_number'] = esc_html__('Mobile', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_fax_number'] = esc_html__('Fax Number', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_company'] = esc_html__('Company Name', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_office_address'] = esc_html__('Office Address', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_office_number'] = esc_html__('Office Number', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_skype'] = esc_html__('Skype', 'essential-real-estate');

            $info[ERE_METABOX_PREFIX . 'author_facebook_url'] = esc_html__('Facebook', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_linkedin_url'] = esc_html__('LinkedIn', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_twitter_url'] = esc_html__('Twitter', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_pinterest_url'] = esc_html__('Pinterest', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_instagram_url'] = esc_html__('Instagram', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_youtube_url'] = esc_html__('Youtube', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_vimeo_url'] = esc_html__('Vimeo', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_googleplus_url'] = esc_html__('Google Plus', 'essential-real-estate');
            $info[ERE_METABOX_PREFIX . 'author_custom_picture'] = esc_html__('Picture Url', 'essential-real-estate');
            return $info;
        }

        /**
         * Delete account
         */
        public function delete_account_ajax()
        {
            check_ajax_referer('ere_delete_profile_ajax_nonce', 'ere_security_delete_profile');
            $user_id = get_current_user_id();
            $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
            if (!empty($agent_id) && (get_post_type($agent_id) == 'agent')) {
                wp_delete_post($agent_id);
            }
            wp_delete_user($user_id);
            echo json_encode(array('success' => true, 'message' => esc_html__('success', 'essential-real-estate')));
            wp_die();
        }

        public function profile_update($user_id)
        {
            $agent_id = get_the_author_meta(ERE_METABOX_PREFIX . 'author_agent_id', $user_id);
            if (!empty($agent_id)) {
                update_post_meta($agent_id, ERE_METABOX_PREFIX . 'agent_user_id', $user_id);
            }
        }

        /**
         * Check package available
         * @param $user_id
         * @return int
         */
        public function user_package_available($user_id)
        {
            $package_id = get_the_author_meta(ERE_METABOX_PREFIX . 'package_id', $user_id);
            if (empty($package_id)) {
                return 0;
            } else {
                $ere_package = new ERE_Package();
                $package_unlimited_time = get_post_meta($package_id, ERE_METABOX_PREFIX . 'package_unlimited_time', true);
                if($package_unlimited_time==0)
                {
                    $expired_date = $ere_package->get_expired_time($package_id, $user_id);
                    $today = time();
                    if ($today > $expired_date) {
                        return -1;
                    }
                }
                $package_num_properties = get_the_author_meta(ERE_METABOX_PREFIX . 'package_number_listings', $user_id);
                if ($package_num_properties != -1 && $package_num_properties < 1) {
                    return -2;
                }
            }
            return 1;
        }
    }
}