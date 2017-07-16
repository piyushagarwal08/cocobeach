<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'ERE_Property' ) ) {
	/**
	 * Class ERE_Property
	 */
	class ERE_Property {
		/**
		 * Remove property thumbnail
		 */
		public function remove_property_thumbnail_ajax() {
			$nonce = $_POST['removeNonce'];
			if ( ! wp_verify_nonce( $nonce, 'property_allow_upload' ) ) {
				$json_response = array(
					'success'            => false,
					'reason'             => esc_html__( 'Security check fails', 'essential-real-estate' )
				);
				echo json_encode( $json_response );
				wp_die();
			}
			$success=false;
			if ( isset( $_POST['property_id'] ) && isset( $_POST['thumbnail_id'] ) ) {
				$property_id   = intval( $_POST['property_id'] );
				$attachment_id = intval( $_POST['thumbnail_id'] );
				if ( $property_id > 0 ) {
					delete_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_images', $attachment_id );
					$success=true;
				}
				if ( $attachment_id > 0 ) {
					wp_delete_attachment( $attachment_id );
					$success=true;
				}
			}
			$ajax_response = array(
				'success'            => $success,
			);
			echo json_encode( $ajax_response );
			wp_die();
		}

		public function delete_property_images($postid)
		{
			global $post_type;
			if ($post_type == 'property') {
				$media = get_children(array(
					'post_parent' => $postid,
					'post_type' => 'attachment'
				));
				if (!empty($media)) {
					foreach ($media as $file) {
						wp_delete_attachment($file->ID);
					}
				}
				$attachment_ids = get_post_meta($postid, ERE_METABOX_PREFIX. 'property_images', false);
				if (!empty($attachment_ids)) {
					foreach ($attachment_ids as $id) {
						wp_delete_attachment($id);
					}
				}
			}
			return;
		}

		public function property_img_upload_ajax() {
			$nonce = $_REQUEST['nonce'];
			if ( ! wp_verify_nonce( $nonce, 'property_allow_upload' ) ) {
				$ajax_response = array( 'success' => false, 'reason' => 'Security check failed!' );
				echo json_encode( $ajax_response );
				wp_die();
			}

			$submitted_file = $_FILES['property_upload_file'];
			$uploaded_image = wp_handle_upload( $submitted_file, array( 'test_form' => false ) );

			if ( isset( $uploaded_image['file'] ) ) {
				$file_name          = basename( $submitted_file['name'] );
				$file_type          = wp_check_filetype( $uploaded_image['file'] );
				$attachment_details = array(
					'guid'           => $uploaded_image['url'],
					'post_mime_type' => $file_type['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_name ) ),
					'post_content'   => '',
					'post_status'    => 'inherit'
				);

				$attach_id   = wp_insert_attachment( $attachment_details, $uploaded_image['file'] );
				$attach_data = wp_generate_attachment_metadata( $attach_id, $uploaded_image['file'] );
				wp_update_attachment_metadata( $attach_id, $attach_data );
				$thumbnail_url = wp_get_attachment_thumb_url( $attach_id );
				$fullimage_url = wp_get_attachment_image_src( $attach_id, 'full' );

				$ajax_response = array(
					'success'       => true,
					'url'           => $thumbnail_url,
					'attachment_id' => $attach_id,
					'full_image'    => $fullimage_url[0]
				);
				echo json_encode( $ajax_response );
				wp_die();

			} else {
				$ajax_response = array( 'success' => false, 'reason' => 'Image upload failed!' );
				echo json_encode( $ajax_response );
				wp_die();
			}
		}

		/**
		 * Submit property
		 * @param array $new_property
		 * @return int|null|WP_Error
		 */
		public function submit_property( $new_property = array() ) {
			$new_property['post_type'] = 'property';
			global $current_user;
			wp_get_current_user();
			$user_id                     = $current_user->ID;
			$new_property['post_author'] = $user_id;
			$auto_publish                = ere_get_option( 'auto_publish', 1 );
			$paid_submission_type        = ere_get_option( 'paid_submission_type','no');

			if ( isset( $_POST['property_title'] ) ) {
				$new_property['post_title'] = sanitize_text_field( $_POST['property_title'] );
			}

			if ( isset( $_POST['property_des'] ) ) {
				$new_property['post_content'] = wp_kses_post( $_POST['property_des'] );
			}

			$submit_action = $_POST['property_form'];
			$property_id   = 0;
			if ( $submit_action == 'submit-property' ) {
				if ( $auto_publish == 1 && ( $paid_submission_type == 'no' || $paid_submission_type == 'per_package' ) ) {
					$new_property['post_status'] = 'publish';
				} else {
					$new_property['post_status'] = 'pending';
				}
				$property_id = wp_insert_post( $new_property, true );
				if ( $property_id > 0 ) {
					if ( $paid_submission_type == 'per_package' ) {
						$package_key = get_the_author_meta(ERE_METABOX_PREFIX . 'package_key', $user_id);
						update_post_meta( $property_id, ERE_METABOX_PREFIX . 'package_key', $package_key );
						$package_num_properties = get_the_author_meta( ERE_METABOX_PREFIX . 'package_number_listings', $user_id );
						if ( $package_num_properties - 1 >= 0 ) {
							update_user_meta( $user_id, ERE_METABOX_PREFIX . 'package_number_listings', $package_num_properties - 1 );
						}
					}
					do_action( 'wp_insert_post', 'wp_insert_post' );
				}
			} else if ( $submit_action == 'edit-property' ) {
				$new_property['ID'] = intval( $_POST['property_id'] );
				if ($paid_submission_type == 'per_package') {
					$current_package_key = get_the_author_meta(ERE_METABOX_PREFIX . 'package_key', $user_id);
					$property_package_key = get_post_meta($new_property['ID'], ERE_METABOX_PREFIX . 'package_key', true);
					$ere_profile=new ERE_Profile();
					$check_package=$ere_profile->user_package_available($user_id);
					if(empty($property_package_key)||($current_package_key!=$property_package_key)||($check_package==-1) || ($check_package==0))
					{
						return -1;
					}
				}

				$property_id        = wp_update_post( $new_property );

			}
			if ( $property_id > 0 ) {

				if ( isset( $_POST['property_price'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_price', sanitize_text_field( $_POST['property_price'] ) );
					if ( isset( $_POST['property_price_postfix'] ) ) {
						update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_price_postfix', sanitize_text_field( $_POST['property_price_postfix'] ) );
					}
				}

				if ( isset( $_POST['property_size'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_size', sanitize_text_field( $_POST['property_size'] ) );
				}

				if ( isset( $_POST['property_land'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_land', sanitize_text_field( $_POST['property_land'] ) );
				}

				if ( isset( $_POST['property_bedrooms'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_bedrooms', sanitize_text_field( $_POST['property_bedrooms'] ) );
				}

				if ( isset( $_POST['property_bathrooms'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_bathrooms', sanitize_text_field( $_POST['property_bathrooms'] ) );
				}

				if ( isset( $_POST['property_garage'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_garage', sanitize_text_field( $_POST['property_garage'] ) );
				}

				if ( isset( $_POST['property_garage_size'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_garage_size', sanitize_text_field( $_POST['property_garage_size'] ) );
				}

				if ( isset( $_POST['property_year'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_year', sanitize_text_field( $_POST['property_year'] ) );
				}

				if ( isset( $_POST['property_video_url'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_video_url', sanitize_text_field( $_POST['property_video_url'] ) );
				}
				if ( isset( $_POST['property_identity'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_identity', sanitize_text_field( $_POST['property_identity'] ) );
				}
				else
				{
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_identity', $property_id );
				}
				if ( isset( $_POST['property_image_360_id'] ) && isset( $_POST['property_image_360_url'] )) {
					$property_image_360=array(
						'id'=>$_POST['property_image_360_id'],
						'url'=>$_POST['property_image_360_url'],
					);
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_image_360', $property_image_360);
				}
				if ( isset( $_POST['property_image_ids'] ) ) {
					if ( ! empty( $_POST['property_image_ids'] ) && is_array( $_POST['property_image_ids'] ) ) {
						$property_image_ids = array();
						$str_img_ids        = '';
						foreach ( $_POST['property_image_ids'] as $property_img_id ) {
							$property_image_ids[] = intval( $property_img_id );
							$str_img_ids .= '|' . intval( $property_img_id );
						}
						$str_img_ids = substr( $str_img_ids, 1 );
						update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_images', $str_img_ids );
						if ( isset( $_POST['featured_image_id'] ) ) {
							$featured_image_id = intval( $_POST['featured_image_id'] );
							if ( in_array( $featured_image_id, $property_image_ids ) ) {
								update_post_meta( $property_id, '_thumbnail_id', $featured_image_id );
								if (! empty( $_POST['property_video_url'] ) ) {
									update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_video_image', $featured_image_id );
								}
							}
						} elseif ( ! empty ( $property_image_ids ) ) {
							update_post_meta( $property_id, '_thumbnail_id', $property_image_ids[0] );
						}
					}
				}

				if ( isset( $_POST['property_type'] ) && ( $_POST['property_type'] != '-1' ) ) {
					wp_set_object_terms( $property_id, intval( $_POST['property_type'] ), 'property-type' );
				}

				if ( isset( $_POST['property_status'] ) && ( $_POST['property_status'] != '-1' ) ) {
					wp_set_object_terms( $property_id, intval( $_POST['property_status'] ), 'property-status' );
				}

				if ( isset( $_POST['property_labels'] ) && ( $_POST['property_labels'] != '-1' ) ) {
					wp_set_object_terms( $property_id, intval( $_POST['property_labels'] ), 'property-labels' );
				}

				if ( isset( $_POST['locality'] ) ) {
					$property_city = sanitize_text_field( $_POST['locality'] );
					wp_set_object_terms( $property_id, $property_city, 'property-city' );
				}elseif ( isset( $_POST['property_city'] ) ) {
					$property_city = sanitize_text_field( $_POST['property_city'] );
					wp_set_object_terms( $property_id, $property_city, 'property-city' );
				}
				if ( isset( $_POST['neighborhood'] ) ) {
					$property_neighborhood = sanitize_text_field( $_POST['neighborhood'] );
					wp_set_object_terms( $property_id, $property_neighborhood, 'property-neighborhood' );
				}elseif ( isset( $_POST['property_neighborhood'] ) ) {
					$property_neighborhood = sanitize_text_field( $_POST['property_neighborhood'] );
					wp_set_object_terms( $property_id, $property_neighborhood, 'property-neighborhood' );
				}

				if ( isset( $_POST['administrative_area_level_1'] ) ) {
					$property_state = sanitize_text_field( $_POST['administrative_area_level_1'] );
					wp_set_object_terms( $property_id, $property_state, 'property-state' );
				}elseif ( isset( $_POST['property_state'] ) ) {
					$property_state = sanitize_text_field( $_POST['property_state'] );
					wp_set_object_terms( $property_id, $property_state, 'property-state' );
				}

				if ( isset( $_POST['property_feature'] ) ) {
					$features_array = array();
					foreach ( $_POST['property_feature'] as $feature_id ) {
						$features_array[] = intval( $feature_id );
					}
					wp_set_object_terms( $property_id, $features_array, 'property-feature' );
				}

				if ( isset( $_POST['floors_enable'] ) ) {
					$floors_enable = $_POST['floors_enable'];
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'floors_enable', $floors_enable );
				}

				if ( isset( $_POST[ERE_METABOX_PREFIX . 'floors'] ) ) {
					$floors_post = $_POST[ERE_METABOX_PREFIX . 'floors'];
					if ( ! empty( $floors_post ) ) {
						update_post_meta( $property_id, ERE_METABOX_PREFIX . 'floors', $floors_post );
					}
				}

				if ( isset( $_POST['agent_display_option'] ) ) {
					$property_agent_display_option = sanitize_text_field( $_POST['agent_display_option'] );
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'agent_display_option', $property_agent_display_option );
					if ( $property_agent_display_option == 'other_info' ) {
						if ( isset( $_POST['property_other_contact_name'] ) ) {
							update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_other_contact_name', sanitize_text_field( $_POST['property_other_contact_name'] ) );
						}
						if ( isset( $_POST['property_other_contact_mail'] ) ) {
							update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_other_contact_mail', sanitize_text_field( $_POST['property_other_contact_mail'] ) );
						}
						if ( isset( $_POST['property_other_contact_phone'] ) ) {
							update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_other_contact_phone', sanitize_text_field( $_POST['property_other_contact_phone'] ) );
						}
						if ( isset( $_POST['property_other_contact_description'] ) ) {
							update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_other_contact_description', sanitize_text_field( $_POST['property_other_contact_description'] ) );
						}
					}
					else
					{
						update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_author', $user_id );
					}

				} else {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'agent_display_option', 'author_info' );
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_author', $user_id );
				}

				if ( isset( $_POST['property_map_address'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_address', sanitize_text_field( $_POST['property_map_address'] ) );
				}

				if ( ( isset( $_POST['lat'] ) && ! empty( $_POST['lat'] ) ) && ( isset( $_POST['lng'] ) && ! empty( $_POST['lng'] ) ) ) {
					$lat          = sanitize_text_field( $_POST['lat'] );
					$lng          = sanitize_text_field( $_POST['lng'] );
					$lat_lng      = $lat . ',' . $lng;
					$address      = sanitize_text_field( $_POST['property_map_address'] );
					$arr_location = array(
						'location' => $lat_lng,
						'address'  => $address
					);
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_location', $arr_location );
				}
				if ( isset( $_POST['country_short'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_country', sanitize_text_field( $_POST['country_short'] ) );
				}
				elseif ( isset( $_POST['property_country'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_country', sanitize_text_field( $_POST['property_country'] ) );
				}
				if ( isset( $_POST['postal_code'] ) ) {
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'property_zip', sanitize_text_field( $_POST['postal_code'] ) );
				}

				if(isset( $_POST['additional_feature_title'] ) && isset( $_POST['additional_feature_value'] )) {
					$additional_feature_title = $_POST['additional_feature_title'];
					$additional_feature_value = $_POST['additional_feature_value'];
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'additional_features', count( $additional_feature_title ) );
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'additional_feature_title', $additional_feature_title );
					update_post_meta( $property_id, ERE_METABOX_PREFIX . 'additional_feature_value', $additional_feature_value );
				}
				return $property_id;
			}

			return null;
		}
		/**
		 * True if an the user can edit a property.
		 * @param $property_id
		 * @return mixed
		 */
		public function user_can_edit_property( $property_id ) {
			$can_edit = true;

			if ( ! is_user_logged_in() || ! $property_id ) {
				$can_edit = false;
			} else {
				$property = get_post( $property_id );

				if ( ! $property || ( absint( $property->post_author ) !== get_current_user_id() && ! current_user_can( 'edit_post', $property_id ) ) ) {
					$can_edit = false;
				}
			}

			return apply_filters( 'ere_user_can_edit_property', $can_edit, $property_id );
		}

		/**
		 * Get total my properties
		 * @param $post_status
		 * @return int
		 */
		public function get_total_my_properties( $post_status ) {
			$args       = array(
				'post_type'   => 'property',
				'post_status' => $post_status,
				'author'      => get_current_user_id(),
			);
			$properties = new WP_Query( $args );
			wp_reset_postdata();
			return $properties->found_posts;
		}

		/**
		 * Get total properties by user
		 * @param $agent_id
		 * @param $user_id
		 * @return int
		 */
		public function get_total_properties_by_user( $agent_id, $user_id ) {
			$args = array(
				'post_type'   => 'property',
				'post_status' => 'publish',
				'meta_query'=>array(
					'relation' => 'OR',
					array(
						'key' =>ERE_METABOX_PREFIX. 'property_agent',
						'value' => explode(',', $agent_id),
						'compare' => 'IN'
					),
					array(
						'key' =>ERE_METABOX_PREFIX. 'property_author',
						'value' => explode(',', $user_id),
						'compare' => 'IN'
					)
				)
			);
			$properties = new WP_Query( $args );
			wp_reset_postdata();
			return $properties->found_posts;
		}

		/**
		 * Contact agent
		 */
		public function contact_agent_ajax() {
			$sender_phone = sanitize_text_field( $_POST['sender_phone'] );

			$target_email = sanitize_email( $_POST['target_email'] );
			$property_url = esc_url( $_POST['property_url'] );
			$target_email = is_email( $target_email );
			if ( ! $target_email ) {
				esc_html_e( '%s Target Email address is not properly configured!', 'essential-real-estate' );
				wp_die();
			}

			$sender_email = sanitize_email( $_POST['sender_email'] );

			$sender_name = sanitize_text_field( $_POST['sender_name'] );
			$sender_msg  = wp_kses_post( $_POST['sender_msg'] );

			$email_subject = sprintf( esc_html__( 'New message sent by %s using contact form at %s', 'essential-real-estate' ), $sender_name, get_bloginfo( 'name' ) );

			$email_body = esc_html__( 'You have received a message from: ', 'essential-real-estate' ) . $sender_name . " <br/>";
			if ( ! empty( $sender_phone ) ) {
				$email_body .= esc_html__( 'Phone Number : ', 'essential-real-estate' ) . $sender_phone . " <br/>";
			}
			if ( ! empty( $property_url ) ) {
				$email_body .= esc_html__( 'Property Url: ', 'essential-real-estate' ) . '<a href="'. $property_url . '">'. $property_url . '</a><br/>';
			}
			$email_body .= esc_html__( 'Additional message is as follows.', 'essential-real-estate' ) . " <br/>";
			$email_body .= wpautop( $sender_msg ) . " <br/>";
			$email_body .= sprintf( esc_html__( 'You can contact %s via email %s', 'essential-real-estate' ), $sender_name, $sender_email );

			$header = 'Content-type: text/html; charset=utf-8' . "\r\n";
			$header = apply_filters( "ere_contact_agent_mail_header", $header );
			$header .= 'From: ' . $sender_name . " <" . $sender_email . "> \r\n";

			if ( wp_mail( $target_email, $email_subject, $email_body, $header ) ) {
				esc_html_e( 'Message Sent Successfully!', 'essential-real-estate' );
			} else {
				esc_html_e( 'Server Error: WordPress mail function failed!', 'essential-real-estate' );
			}
			wp_die();
		}

		/**
		 * Favorite property
		 */
		public function favorite_ajax() {
			global $current_user;
			$property_id = intval( $_POST['property_id'] );
			wp_get_current_user();
			$user_id       = $current_user->ID;
			$added         = $removed = false;
			$ajax_response = '';
			if ( $user_id > 0 ) {
				$my_favorites = get_user_meta( $user_id, ERE_METABOX_PREFIX . 'favorites_property', true );

				if ( ! empty( $my_favorites ) && ( ! in_array( $property_id, $my_favorites ) ) ) {
					array_push( $my_favorites, $property_id );
					$added = true;
				} else {
					if ( empty( $my_favorites ) ) {
						$my_favorites = array( $property_id );
						$added        = true;
					} else {
						//Delete favorite
						$key = array_search( $property_id, $my_favorites );
						if ( $key !== false ) {
							unset( $my_favorites[ $key ] );
							$removed = true;
						}
					}
				}

				update_user_meta( $user_id, ERE_METABOX_PREFIX . 'favorites_property', $my_favorites );
				if ( $added ) {
					$ajax_response = array( 'added' => true, 'message' => esc_html__( 'Added', 'essential-real-estate' ) );
				}
				if ( $removed ) {
					$ajax_response = array( 'added' => false, 'message' => esc_html__( 'Removed', 'essential-real-estate' ) );
				}
			} else {
				$ajax_response = array(
					'added'   => false,
					'message' => esc_html__( 'You are not login', 'essential-real-estate' )
				);
			}
			echo json_encode( $ajax_response );
			wp_die();
		}

		/**
		 * Get total favorite
		 * @return int
		 */
		public function get_total_favorite(){
			$user_id = get_current_user_id();
			$my_favorites = get_user_meta($user_id, ERE_METABOX_PREFIX . 'favorites_property', true);
			if(empty($my_favorites))
			{
				$my_favorites=array(0);
			}
			$args = array(
				'post_type' => 'property',
				'post__in' => $my_favorites
			);
			$favorites = new WP_Query( $args );
			wp_reset_postdata();
			return $favorites->found_posts;
		}
		/**
		 * Print Property
		 */
		public function property_print_ajax() {
			if ( ! isset( $_POST['property_id'] ) || ! is_numeric( $_POST['property_id'] ) ) {
				return;
			}
			$property_id = intval( $_POST['property_id'] );
			$isRTL = 'false';
			if(isset( $_POST['isRTL'] ))
			{
				$isRTL = $_POST['isRTL'];
			}
			ere_get_template( 'property/property-print.php', array('property_id'=>$property_id, 'isRTL'=>$isRTL));
		}

		/**
		 *	set_views_counter
		 */
		public function set_views_counter()
		{
			global $post;
			// Is it a single post
			if (is_single() && (get_post_type() == 'property'))
			{
				// Check if user already visited this page
				$visited_posts = array();
				// Check cookie for list of visited posts
				if (isset($_COOKIE['property_views'])) {
					// We expect list of comma separated post ids in the cookie
					$visited_posts = array_map('intval', explode(',', $_COOKIE['property_views']));
				}
				if (in_array($post->ID, $visited_posts)) {
					// User already visited this post
					return;
				}
				// The visitor is reading this post first time, so we count
				// Get current view count
				$views = (int)get_post_meta($post->ID, ERE_METABOX_PREFIX.'property_views_count', true);
				// Increase by one and save
				update_post_meta($post->ID, ERE_METABOX_PREFIX.'property_views_count', $views + 1);
				// Add post id and set cookie
				$visited_posts[] = $post->ID;
				// Set cookie for one hour, it shoudl be set on all pages se we use / as path
				setcookie('property_views', implode(',', $visited_posts), time() + 3600, '/');
			}
		}

		/**
		 * get_total_views
		 * @param null $post_id
		 * @return int
		 */
		public function get_total_views($post_id = null)
		{
			global $post;
			/**
			 * If no given post id, then current post
			 */
			if (!$post_id && isset($post->ID)) {
				$post_id = $post->ID;
			}
			if (!$post_id) {
				return 0;
			}
			$views = get_post_meta($post_id, ERE_METABOX_PREFIX.'property_views_count', true);
			return intval($views);
		}

		public function get_states_by_country_ajax()
		{
			if ( ! isset( $_POST['country'] ) ) {
				return;
			}
			$country =  $_POST['country'];
			$type =  $_POST['type'];
			if(!empty($country))
			{
				$taxonomy_terms = get_categories(
					array(
						'taxonomy'=>'property-state',
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => 0,
						'meta_query' => array(
							array(
								'key'     => 'property_state_country',
								'value'   => $country,
								'compare' => '=',
							)
						)
					)
				);
			}
			else{
				$taxonomy_terms = get_categories(
					array(
						'taxonomy'=>'property-state',
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => 0,
					)
				);
			}

			$html= '';
			if($type==0)
			{
				$html= '<option value="">' . esc_html__('None', 'essential-real-estate') . '</option>';
			}
			if (!empty($taxonomy_terms)) {
				foreach ($taxonomy_terms as $term) {
					$html.= '<option value="' . $term->slug . '">' . $term->name . '</option>';
				}
			}
			if($type==1)
			{
				$html.= '<option value="" selected="selected">' . esc_html__('All States', 'essential-real-estate') . '</option>';
			}
			echo $html;
			wp_die();
		}

		public function get_cities_by_state_ajax()
		{
			if ( ! isset( $_POST['state'] ) ) {
				return;
			}
			$state =  $_POST['state'];
			$type =  $_POST['type'];
			$property_state = get_term_by('slug', $state, 'property-state');
			if(!empty($state) && $property_state)
			{
				$taxonomy_terms = get_categories(
					array(
						'taxonomy'=>'property-city',
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => 0,
						'meta_query' => array(
							array(
								'key'     => 'property_city_state',
								'value'   => $property_state->term_id,
								'compare' => '=',
							)
						)
					)
				);
			}
			else
			{
				$taxonomy_terms = get_categories(
					array(
						'taxonomy'=>'property-city',
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => 0,
					)
				);
			}
			$html= '';
			if($type==0)
			{
				$html= '<option value="">' . esc_html__('None', 'essential-real-estate') . '</option>';
			}
			if (!empty($taxonomy_terms)) {
				foreach ($taxonomy_terms as $term) {
					$html.= '<option value="' . $term->slug . '">' . $term->name . '</option>';
				}
			}
			if($type==1)
			{
				$html.= '<option value="" selected="selected">' . esc_html__('All Cities', 'essential-real-estate') . '</option>';
			}
			echo $html;
			wp_die();
		}

		public function get_neighborhoods_by_city_ajax()
		{
			if ( ! isset( $_POST['city'] ) ) {
				return;
			}
			$city =  $_POST['city'];
			$type =  $_POST['type'];
			$property_city = get_term_by('slug', $city, 'property-city');
			if(!empty($city) && $property_city)
			{
				$taxonomy_terms = get_categories(
					array(
						'taxonomy'=>'property-neighborhood',
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => 0,
						'meta_query' => array(
							array(
								'key'     => 'property_neighborhood_city',
								'value'   => $property_city->term_id,
								'compare' => '=',
							)
						)
					)
				);
			}
			else
			{
				$taxonomy_terms = get_categories(
					array(
						'taxonomy'=>'property-neighborhood',
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => false,
						'parent' => 0,
					)
				);
			}

			$html= '';
			if($type==0)
			{
				$html= '<option value="">' . esc_html__('None', 'essential-real-estate') . '</option>';
			}
			if (!empty($taxonomy_terms)) {
				foreach ($taxonomy_terms as $term) {
					$html.= '<option value="' . $term->slug . '">' . $term->name . '</option>';
				}
			}
			if($type==1)
			{
				$html.= '<option value="" selected="selected">' . esc_html__('All Neighborhoods', 'essential-real-estate') . '</option>';
			}
			echo $html;
			wp_die();
		}
	}
}