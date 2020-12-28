<?php
/**
 * Custom Sensors for WPForms
 *
 * Class file for alert manager.
 *
 * @since   1.0.0
 * @package Wsal
 */

/**
 * Custom sensor class to process WPForms events.
 *
 * @since 1.0.0
 */
class WSAL_Sensors_WPFormsSensor extends WSAL_AbstractSensor {

	/**
	 * Holds a cached value if the checked alert has recently fired.
	 *
	 * @var null|array
	 */
	private $cached_alert_checks = null;

	/**
	 * Hook events related to sensor.
	 *
	 * @since 1.0.0
	 */
	public function HookEvents() {
		add_action( 'pre_post_update', array( $this, 'get_before_post_edit_data' ), 10, 2 );
		add_action( 'save_post', array( $this, 'event_form_saved' ), 10, 3 );
		add_action( 'delete_post', array( $this, 'event_form_deleted' ), 10, 1 );
		add_action( 'wpforms_pre_delete', array( $this, 'event_entry_deleted' ), 10, 1 );
		add_action( 'wpforms_pro_admin_entries_edit_submit_completed', array( $this, 'event_entry_modified' ), 5, 4 );
		add_action( 'updated_option', array( $this, 'event_settings_updated' ), 10, 3 );
		add_action( 'added_option', array( $this, 'event_added_option' ), 10, 2 );
		add_action( 'wpforms_plugin_activated', array( $this, 'addon_plugin_activated' ), 10, 1 );
		add_action( 'wpforms_plugin_deactivated', array( $this, 'addon_plugin_deactivated' ), 10, 1 );
		add_action( 'wpforms_plugin_installed', array( $this, 'addon_plugin_installed' ), 10, 1 );
	}

	/**
	 * Get Post Data.
	 *
	 * Collect old post data before post update event.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id - Post ID.
	 */
	public function get_before_post_edit_data( $post_id ) {
		$post_id = absint( $post_id ); // Making sure that the post id is integer.
		$post    = get_post( $post_id ); // Get post.

		// If post exists.
		if ( ! empty( $post ) && $post instanceof WP_Post ) {
			$this->_old_post = $post;
		}
	}

	/**
	 * Form renamed event.
	 *
	 * Detect when forms title has been changed.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $post_id - Post ID.
	 * @param object $post    - Post data.
	 * @param bool   $update  - Whether this is an existing post being updated or not.
	 */
	public function event_form_saved( $post_id, $post, $update ) {
		$post_id             = absint( $post_id ); // Making sure that the post id is integer.
		$form                = get_post( $post_id );
		$has_alert_triggered = false; // Create a variable so we can determine if an alert has already fired.

		// Handling form creation. First lets check an old post was set and its not flagged as an update, then finally check its not a duplicate.
		if ( ! isset( $this->_old_post->post_title ) && ! $update && ! preg_match( '/\s\(ID #[0-9].*?\)/', $form->post_title ) && 'wpforms' === $post->post_type ) {
			$alert_code  = 5500;
			$editor_link = esc_url(
				add_query_arg(
					array(
						'view'    => 'fields',
						'form_id' => $post_id,
					),
					admin_url( 'admin.php?page=wpforms-builder' )
				)
			);

			$variables = array(
				'EventType'      => 'created',
				'PostTitle'      => sanitize_text_field( $post->post_title ),
				'PostID'         => $post_id,
				'EditorLinkForm' => $editor_link,
			);

			$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'check_if_duplicate' ) );
			$has_alert_triggered = true;

			// Handling form rename. Check if this is a form and if an old title is set.
		} elseif ( isset( $this->_old_post->post_title ) && $this->_old_post->post_title !== $post->post_title && 'wpforms' === $post->post_type && $update ) {

			// Checking to ensure this is not a draft or fresh form.
			if ( isset( $post->post_status ) && 'auto-draft' !== $post->post_status ) {
				$alert_code  = 5506;
				$post        = get_post( $post_id );
				$editor_link = esc_url(
					add_query_arg(
						array(
							'view'    => 'fields',
							'form_id' => $post_id,
						),
						admin_url( 'admin.php?page=wpforms-builder' )
					)
				);

				$variables = array(
					'EventType'      => 'renamed',
					'old_form_name'  => sanitize_text_field( $this->_old_post->post_title ),
					'new_form_name'  => sanitize_text_field( $post->post_title ),
					'PostID'         => $post_id,
					'EditorLinkForm' => $editor_link,
				);

				$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
				$has_alert_triggered = true;
			}
		}

		// Handling duplicated forms by checking to see if the post has ID # in the title.
		if ( preg_match( '/\s\(ID #[0-9].*?\)/', $form->post_title ) && 'wpforms' === $form->post_type ) {
			$post_created  = new DateTime( $form->post_date_gmt );
			$post_modified = new DateTime( $form->post_modified_gmt );
			$alert_code    = 5502;

			// Check if this is indeed a new form.
			if ( $form->post_date_gmt === $form->post_modified_gmt ) {
				// Grab old form ID from its post content.
				$old_form_content = json_decode( $this->_old_post->post_content );
				$editor_link      = esc_url(
					add_query_arg(
						array(
							'view'    => 'fields',
							'form_id' => $post_id,
						),
						admin_url( 'admin.php?page=wpforms-builder' )
					)
				);

				if ( isset( $old_form_content->id ) ) {
					$variables = array(
						'OldPostTitle'   => sanitize_text_field( $this->_old_post->post_title ),
						'PostTitle'      => sanitize_text_field( $form->post_title ),
						'SourceID'       => sanitize_text_field( $old_form_content->id ),
						'PostID'         => $post_id,
						'EditorLinkForm' => $editor_link,
					);
					$this->plugin->alerts->Trigger( $alert_code, $variables );
					$has_alert_triggered = true;
					remove_action( 'save_post', array( $this, 'event_form_saved' ), 10, 3 );
				}
			}
		}

		// Handling form notifications.
		if ( 'wpforms' === $form->post_type && isset( $this->_old_post ) && $update && ! $this->was_triggered_recently( 5500 ) ) {
			// Checking to ensure this is not a draft or fresh form.
			if ( isset( $post->post_status ) && 'auto-draft' !== $post->post_status ) {
				$form_content     = json_decode( $form->post_content );
				$old_form_content = json_decode( $this->_old_post->post_content );
				$post_created     = new DateTime( $post->post_date_gmt );
				$post_modified    = new DateTime( $post->post_modified_gmt );
				$editor_link      = esc_url(
					add_query_arg(
						array(
							'view'    => 'fields',
							'form_id' => $post_id,
						),
						admin_url( 'admin.php?page=wpforms-builder' )
					)
				);

				// Create 2 arrays from the notification object for comparison later.
				if ( isset( $form_content->settings->notifications ) && isset( $old_form_content->settings->notifications ) ) {
					$form_content_array     = json_decode( json_encode( $form_content->settings->notifications ), true );
					$old_form_content_array = json_decode( json_encode( $old_form_content->settings->notifications ), true );

					// Compare the 2 arrays and create array of added items.
					$compare_added_items = array_diff(
						array_map( 'serialize', $form_content_array ),
						array_map( 'serialize', $old_form_content_array )
					);
					$added_items         = array_map( 'unserialize', $compare_added_items );

					// Compare the 2 arrays and create array of removed items.
					$compare_removed_items = array_diff(
						array_map( 'serialize', $old_form_content_array ),
						array_map( 'serialize', $form_content_array )
					);
					$removed_items         = array_map( 'unserialize', $compare_removed_items );

					// Compare the 2 arrays and create array of changed.
					$compare_changed_items = array_diff_assoc(
						array_map( 'serialize', $old_form_content_array ),
						array_map( 'serialize', $form_content_array )
					);
					$changed_items         = array_map( 'unserialize', $compare_removed_items );

					// Check new content size determine if something has been added.
					if ( count( $form_content_array ) > count( $old_form_content_array ) ) {
						$alert_code = 5503;
						foreach ( $added_items as $notification ) {
							if ( isset( $notification['notification_name'] ) ) {
								$notification_name = $notification['notification_name'];
							} else {
								$notification_name = esc_html__( 'Default Notification', 'wsal-wpforms' );
							}
							$variables = array(
								'EventType'        => 'created',
								'notifiation_name' => sanitize_text_field( $notification_name ),
								'form_name'        => sanitize_text_field( $form->post_title ),
								'PostID'           => $post_id,
								'EditorLinkForm'   => $editor_link,
							);
							$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
							$has_alert_triggered = true;
						}
						// Check new content size determine if something has been removed.
					} elseif ( count( $form_content_array ) < count( $old_form_content_array ) ) {
						$alert_code = 5503;
						foreach ( $removed_items as $notification ) {
							if ( isset( $notification['notification_name'] ) ) {
								$notification_name = $notification['notification_name'];
							} else {
								$notification_name = esc_html__( 'Default Notification', 'wsal-wpforms' );
							}
							$variables = array(
								'EventType'        => 'deleted',
								'notifiation_name' => sanitize_text_field( $notification_name ),
								'form_name'        => sanitize_text_field( $form->post_title ),
								'PostID'           => $post_id,
								'EditorLinkForm'   => $editor_link,
							);
							$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
							$has_alert_triggered = true;
						}
						// Compare old post and new post to see if the notifications have been disabled.
					} elseif ( $old_form_content->settings->notification_enable && ! $form_content->settings->notification_enable ) {
						$alert_code = 5505;
						$variables  = array(
							'EventType'      => 'disabled',
							'form_name'      => sanitize_text_field( $form_content->settings->form_title ),
							'PostID'         => $post_id,
							'EditorLinkForm' => $editor_link,
						);
						$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
						$has_alert_triggered = true;

					} elseif ( ! $old_form_content->settings->notification_enable && $form_content->settings->notification_enable ) {
						$alert_code = 5505;
						$variables  = array(
							'EventType'      => 'enabled',
							'form_name'      => sanitize_text_field( $form_content->settings->form_title ),
							'PostID'         => $post_id,
							'EditorLinkForm' => $editor_link,
						);
						$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
						$has_alert_triggered = true;

						// Finally, as none of the above triggered anything, lets see if the notifications themselves have been modified.
					} elseif ( $changed_items ) {

						// Check time and also if there is an actual change in the post content.
						if ( abs( $post_created->diff( $post_modified )->s ) <= 1 ) {
							// post hasn't changed return without event trigger.
							return;
						}

						$alert_code = 5503;
						foreach ( $removed_items as $notification ) {
							if ( isset( $notification['notification_name'] ) ) {
								$notification_name = $notification['notification_name'];
							} else {
								$notification_name = esc_html__( 'Default Notification', 'wsal-wpforms' );
							}
							$variables = array(
								'EventType'        => 'modified',
								'notifiation_name' => sanitize_text_field( $notification_name ),
								'form_name'        => sanitize_text_field( $form->post_title ),
								'PostID'           => $post_id,
								'EditorLinkForm'   => $editor_link,
							);
							$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
							$has_alert_triggered = true;
						}
					}
				}
			}
		}

		// Handling fields.
		if ( 'wpforms' === $form->post_type && isset( $this->_old_post ) ) {
			// Checking to ensure this is not a draft or fresh form.
			if ( isset( $post->post_status ) && 'auto-draft' !== $post->post_status ) {
				$form_content     = json_decode( $form->post_content );
				$old_form_content = json_decode( $this->_old_post->post_content );
				$post_created     = new DateTime( $post->post_date_gmt );
				$post_modified    = new DateTime( $post->post_modified_gmt );
				$editor_link      = esc_url(
					add_query_arg(
						array(
							'view'    => 'fields',
							'form_id' => $post_id,
						),
						admin_url( 'admin.php?page=wpforms-builder' )
					)
				);

				// First lets see if we have BOTH old and new content to compare.
				if ( isset( $form_content->fields ) && isset( $old_form_content->fields ) && serialize( $form_content->fields ) !== serialize( $old_form_content->fields ) ) {
					// Create 2 arrays from the fields object for comparison later.
					$form_content_array     = json_decode( json_encode( $form_content->fields ), true );
					$old_form_content_array = json_decode( json_encode( $old_form_content->fields ), true );

					// Compare the 2 arrays and create array of added items.
					if ( $form_content_array !== $old_form_content_array ) {
						$compare_added_items = array_diff(
							array_map( 'serialize', $form_content_array ),
							array_map( 'serialize', $old_form_content_array )
						);
						$added_items         = array_map( 'unserialize', $compare_added_items );
					} else {
						$added_items = $form_content_array;
					}

					// Compare the 2 arrays and create array of removed items.
					$compare_removed_items = array_diff(
						array_map( 'serialize', $old_form_content_array ),
						array_map( 'serialize', $form_content_array )
					);
					$removed_items         = array_map( 'unserialize', $compare_removed_items );

					$compare_changed_items = array_diff_assoc(
						array_map( 'serialize', $old_form_content_array ),
						array_map( 'serialize', $form_content_array )
					);
					$changed_items         = array_map( 'unserialize', $compare_removed_items );
					$changed_items         = array_intersect_key( $added_items, $changed_items );

					if ( ! empty( $added_items ) ) {
						$added_items = array_diff(
							array_map( 'serialize', $added_items ),
							array_map( 'serialize', $changed_items )
						);
						$added_items = array_map( 'unserialize', $added_items );
					}

					// Check new content size determine if something has been added.
					if ( $added_items && $added_items !== $changed_items ) {
						$alert_code = 5501;
						foreach ( $added_items as $fields ) {
							$variables = array(
								'EventType'      => 'created',
								'field_name'     => sanitize_text_field( $fields['label'] ),
								'form_name'      => sanitize_text_field( $form->post_title ),
								'PostID'         => $post_id,
								'EditorLinkForm' => $editor_link,
							);
							$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
							$has_alert_triggered = true;
						}
					}

					// Check new content size determine if something has been removed.
					if ( $removed_items ) {
						$alert_code = 5501;
						foreach ( $removed_items as $fields => $value ) {

							if ( ! empty( $changed_items ) ) {
								if ( ! $changed_items[ $fields ] ) {
									$variables = array(
										'EventType'      => 'deleted',
										'field_name'     => sanitize_text_field( $value['label'] ),
										'form_name'      => sanitize_text_field( $form->post_title ),
										'PostID'         => $post_id,
										'EditorLinkForm' => $editor_link,
									);
									$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
									$has_alert_triggered = true;
								}
							} else {
								$variables = array(
									'EventType'      => 'deleted',
									'field_name'     => sanitize_text_field( $value['label'] ),
									'form_name'      => sanitize_text_field( $form->post_title ),
									'PostID'         => $post_id,
									'EditorLinkForm' => $editor_link,
								);
								$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
								$has_alert_triggered = true;
							}
						}
					}

					// Check content to see if anything has been modified.
					if ( $changed_items && ! $this->was_triggered_recently( 5500 ) ) {
						$alert_code = 5501;
						foreach ( $changed_items as $fields ) {
							$variables = array(
								'EventType'      => 'modified',
								'field_name'     => sanitize_text_field( $fields['label'] ),
								'form_name'      => sanitize_text_field( $form->post_title ),
								'PostID'         => $post_id,
								'EditorLinkForm' => $editor_link,
							);
							$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
							$has_alert_triggered = true;
						}
					}

					// Now we shall check if we have just a single new field thats been added.
				} elseif ( isset( $form_content->fields ) && ! isset( $old_form_content->fields ) ) {
					// Create 2 arrays from the fields object for comparison later.
					$form_content_array = json_decode( json_encode( $form_content->fields ), true );
					$alert_code         = 5501;
					foreach ( $form_content_array as $fields ) {
						$variables = array(
							'EventType'      => 'created',
							'field_name'     => sanitize_text_field( $fields['label'] ),
							'form_name'      => sanitize_text_field( $form->post_title ),
							'PostID'         => $post_id,
							'EditorLinkForm' => $editor_link,
						);
						$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
						$has_alert_triggered = true;
					}

					// Finally we shall check if we have just a single new field thats been removed.
				} elseif ( ! isset( $form_content->fields ) && isset( $old_form_content->fields ) ) {
					// Create 2 arrays from the fields object for comparison later.
					$form_content_array = json_decode( json_encode( $old_form_content->fields ), true );
					$alert_code         = 5501;
					foreach ( $form_content_array as $fields ) {
						$variables = array(
							'EventType'      => 'deleted',
							'field_name'     => sanitize_text_field( $fields['label'] ),
							'form_name'      => sanitize_text_field( $form->post_title ),
							'PostID'         => $post_id,
							'EditorLinkForm' => $editor_link,
						);
						$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'must_not_be_new_form' ) );
						$has_alert_triggered = true;
					}
				}
			}
		}

		// Finally, if all of the above didnt catch anything, but the form as still been modified in some way, lets handle that.
		if ( ! $has_alert_triggered && 'wpforms' === $form->post_type && isset( $this->_old_post ) && ! $update && ! $this->was_triggered_recently( 5500 ) ) {
			if ( isset( $post->post_status ) && 'auto-draft' !== $post->post_status ) {
				$alert_code       = 5500;
				$form_content     = json_decode( $form->post_content );
				$old_form_content = json_decode( $this->_old_post->post_content );

				// First, lets check the content is available in the current and old post.
				if ( isset( $form_content ) && isset( $old_form_content ) ) {

					// Content is found, so lets create some arrays to compare for changes.
					$form_content_array     = json_decode( json_encode( $form_content ), true );
					$old_form_content_array = json_decode( json_encode( $old_form_content ), true );
					$compare_changed_items  = array_diff_assoc(
						array_map( 'serialize', $old_form_content_array ),
						array_map( 'serialize', $form_content_array )
					);

					// Round up any changes into a neat array, could expand in this later also.
					$changed_items = array_map( 'unserialize', $compare_changed_items );

					// Now lets check if anything has been added to our array, if it has, somethings changed so lets alert.
					if ( $changed_items ) {
						$editor_link = esc_url(
							add_query_arg(
								array(
									'view'    => 'fields',
									'form_id' => $post_id,
								),
								admin_url( 'admin.php?page=wpforms-builder' )
							)
						);

						$variables = array(
							'EventType'      => 'modified',
							'PostTitle'      => sanitize_text_field( $post->post_title ),
							'PostID'         => $post_id,
							'EditorLinkForm' => $editor_link,
						);

						$this->plugin->alerts->TriggerIf( $alert_code, $variables, array( $this, 'check_if_duplicate' ) );
						remove_action( 'save_post', array( $this, 'event_form_saved' ), 10, 3 );
					}
				}
			}
		}

	}

	/**
	 * Form deleted event.
	 *
	 * Detect when a form has been fully deleted.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id - Post ID.
	 */
	public function event_form_deleted( $post_id ) {
		$alert_code = 5500;
		$post_id    = absint( $post_id );
		$post       = get_post( $post_id );
		if ( 'wpforms' === $post->post_type ) {
			$variables = array(
				'EventType' => 'deleted',
				'PostTitle' => $post->post_title,
				'PostID'    => $post_id,
			);

			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}
	}

	/**
	 * Delete entry event.
	 *
	 * Detect when an entry has been deleted.
	 *
	 * @since 1.0.0
	 */
	public function event_entry_deleted( $row_id ) {
		$alert_code = 5504;
		$entry      = wpforms()->entry->get( $row_id );
		$form       = get_post( $entry->form_id );

		// Grab from content.
		$form_content = (string) $entry->fields;

		// Search it for any email address
		$email_address = $this->extract_emails( $form_content );

		// Now lets see if we have more than one email present, if so, just grab the 1st one,
		if ( $email_address && is_array( $email_address ) ) {
			$email_address = $email_address[0];
		} elseif ( $email_address && ! is_array( $email_address ) ) {
			$email_address = $email_address;
		} else {
			$email_address = esc_html__( 'No email provided', 'wsal-wpforms' );
		}

		$editor_link = esc_url(
			add_query_arg(
				array(
					'view'    => 'fields',
					'form_id' => $entry->form_id,
				),
				admin_url( 'admin.php?page=wpforms-builder' )
			)
		);

		$variables = array(
			'entry_email'    => sanitize_text_field( $email_address ),
			'entry_id'       => sanitize_text_field( $row_id ),
			'form_name'      => sanitize_text_field( $form->post_title ),
			'form_id'        => $entry->form_id,
			'EditorLinkForm' => $editor_link,
		);
		$this->plugin->alerts->Trigger( $alert_code, $variables );
		remove_action( 'wpforms_pre_delete', array( $this, 'event_entry_deleted' ), 10, 1 );
	}

	/**
	 * Modify entry event.
	 *
	 * @since 1.0.3
	 */
	public function event_entry_modified( $form_data, $response, $updated_fields, $entry ) {
		$alert_code = 5507;

		$fields = json_decode( $entry->fields, true );

		foreach ( $updated_fields as $updated_field ) {

			$modified_value = array( array_search( $updated_field['name'], array_column( $fields, 'name', 'value' ) ) );

			$editor_link = esc_url(
				add_query_arg(
					array(
						'view'     => 'edit',
						'entry_id' => $entry->entry_id,
					),
					admin_url( 'admin.php?page=wpforms-entries' )
				)
			);

			if ( isset( $updated_field['name'] ) ) {
				$variables = array(
					'entry_id'        => $entry->entry_id,
					'form_name'       => $form_data['settings']['form_title'],
					'field_name'      => $updated_field['name'],
					'old_value'       => implode( $modified_value ),
					'new_value'       => $updated_field['value'],
					'EditorLinkEntry' => $editor_link,
				);

				$this->plugin->alerts->Trigger( $alert_code, $variables );
			}
		}

	}

	public function event_settings_updated( $option_name, $old_value, $value ) {

		if ( $value !== $old_value ) {

			if ( ! is_array( $old_value ) || ! is_array( $value ) ) {
				return;
			}

			// For access settings, we need to check its the correct thing updateing.
			if ( 'wp_user_roles' === $option_name ) {
				// Gather role names as we need them later.
				$roles = wp_roles()->get_names();
				// Array of possible capabilities which wpforms can add/remove from a role.
				$wpforms_caps = array( 'wpforms_create_forms', 'wpforms_view_own_forms', 'wpforms_view_others_forms', 'wpforms_edit_own_forms', 'wpforms_edit_others_forms', 'wpforms_delete_own_forms', 'wpforms_delete_others_forms', 'wpforms_view_entries_own_forms', 'wpforms_view_entries_others_forms', 'wpforms_edit_entries_own_forms', 'wpforms_edit_entries_others_forms', 'wpforms_delete_entries_own_forms', 'wpforms_delete_entries_others_forms' );
				// Create empty arrays to be filled later.
				$updated_new = array();
				$updated_old = array();

				// Loop through each availble role and build a simple array of the available
				// wpforms capabilities, assiging applicable roles to each as we find them.
				foreach ( $roles as $role_index_name => $role_label ) {

					// Create array of current values.
					if ( isset( $value[ $role_index_name ] ) ) {
						foreach ( $wpforms_caps as $capability ) {
							if ( $this->array_key_exists_recursive( $capability, $value[ $role_index_name ] ) ) {
								$roles = isset( $updated_new[ $capability ]['roles'] ) ? $updated_new[ $capability ]['roles'] . ', ' . $value[ $role_index_name ]['name'] : $value[ $role_index_name ]['name'];
								// Ensure we only have unique values, to avoid duplicated being added when looping.
								$updated_new[ $capability ] = array(
									'roles' => $roles,
								);
							}
							// Fill up array with capability anyway, even if its blank.
							if ( ! isset( $updated_new[ $capability ] ) ) {
								$updated_new[ $capability ] = array(
									'roles' => '',
								);
							}
						}
					}

					// Create array of old values for comparison.
					if ( isset( $old_value[ $role_index_name ] ) ) {
						foreach ( $wpforms_caps as $capability ) {
							if ( $this->array_key_exists_recursive( $capability, $old_value[ $role_index_name ] ) ) {
								$roles = isset( $updated_old[ $capability ]['roles'] ) ? $updated_old[ $capability ]['roles'] . ', ' . $old_value[ $role_index_name ]['name'] : $old_value[ $role_index_name ]['name'];
								// Ensure we only have unique values, to avoid duplicated being added when looping.
								$updated_old[ $capability ] = array(
									'roles' => $roles
								);
							}
							// Fill up array with capability anyway, even if its blank.
							if ( ! isset( $updated_old[ $capability ] ) ) {
								$updated_old[ $capability ] = array(
									'roles' => '',
								);
							}
						}
					}
				}

				// Detect changes for each wpforms capability and fire off 5508 if a change is found.
				foreach ( $wpforms_caps as $wpforms_capability ) {
					// Compare old and new to see if something has been tinkered with.
					if ( isset( $updated_new[ $wpforms_capability ] ) && $updated_new[ $wpforms_capability ] !== $updated_old[ $wpforms_capability ] ) {
						$alert_code = 5508;
						// Tidy up name for event.
						$setting_name = ucwords( str_replace( '_', ' ', str_replace( 'wpforms', '', $wpforms_capability ) ) );
						// Determine the type of setting thats been changed.
						if ( strpos( $wpforms_capability, 'own' ) !== false ) {
							$setting_type = __( 'Own', 'wsal-wpforms' );
						} elseif ( strpos( $wpforms_capability, 'other' ) !== false ) {
							$setting_type = __( 'Other', 'wsal-wpforms' );
						} else {
							$setting_type = __( 'N/A', 'wsal-wpforms' );
						}
						// Setup event variables using above.
						$variables = array(
							'setting_name' => $setting_name,
							'setting_type' => $setting_type,
							'old_value'    => substr( implode( ',', array_unique( explode( ',', $updated_old[ $wpforms_capability ]['roles'] ) ) ), 2 ),
							'new_value'    => substr( implode( ',', array_unique( explode( ',', $updated_new[ $wpforms_capability ]['roles'] ) ) ), 2 ),
						);
						// Fire off 5508.
						$this->plugin->alerts->Trigger( $alert_code, $variables );
					}
				}
			}

			// Event 5509 (Change of currency).
			if ( 'wpforms_settings' === $option_name && isset( $value['currency'] ) && function_exists( 'wpforms_get_currencies' ) ) {
				$wp_forms_currencies = wpforms_get_currencies();
				$alert_code          = 5509;
				$variables           = array(
					'old_value' => $wp_forms_currencies[ $old_value['currency'] ]['name'] . ' (' . $old_value['currency'] . ')',
					'new_value' => $wp_forms_currencies[ $value['currency'] ]['name'] . ' (' . $value['currency'] . ')',
				);

				$this->plugin->alerts->Trigger( $alert_code, $variables );
			}

			// Event 5510 (Integration enabled/disabled).
			if ( 'wpforms_providers' === $option_name ) {

				$providers = array(
					'mailchimpv3',
					'aweber',
					'constant-contact',
					'zapier',
					'getresponse',
					'drip',
					'campaign-monitor',
				);

				foreach ( $providers as $provider ) {
					if ( isset( $value[ $provider ] ) ) {
						if ( ! empty( $value[ $provider ] ) && empty( $old_value[ $provider ] ) ) {
							$event_type       = 'added';
							$connection_label = array_column( $value[ $provider ], 'label' );
						} else {
							$event_type       = 'deleted';
							$connection_label = array_column( $old_value[ $provider ], 'label' );
						}

						// Tidy labels up.
						if ( 'mailchimpv3' === $provider ) {
							$provider = __( 'Mailchimp', 'wsal-wpforms' );
						} elseif ( 'getresponse' === $provider ) {
							$provider = __( 'GetResponse', 'wsal-wpforms' );
						}

						$alert_code      = 5510;
						$connection_name = ! empty( $connection_label ) ? $connection_label[0] : null;
						$variables       = array(
							'EventType'       => $event_type,
							'service_name'    => ucwords( str_replace( '-', ' ', $provider ) ),
							'connection_name' => $connection_name,
						);
						$this->plugin->alerts->Trigger( $alert_code, $variables );
					}
				}
			}
		}

	}

	/**
	 * Detect initial changes to WPforms option. These typically use the "added_option"
	 * hook as no previous option is present to update.
	 *
	 * @param  string $option_name Name of option being changed.
	 * @param  array  $value       New values.
	 */
	public function event_added_option( $option_name, $value ) {
		// Event 5509 (Initial change of currency).
		if ( 'wpforms_settings' === $option_name && isset( $value['currency'] ) && function_exists( 'wpforms_get_currencies' ) ) {
			$wp_forms_currencies = wpforms_get_currencies();
			$alert_code          = 5509;
			$variables           = array(
				'old_value' => $wp_forms_currencies['USD']['name'] . ' (USD)',
				'new_value' => $wp_forms_currencies[ $value['currency'] ]['name'] . ' (' . $value['currency'] . ')',
			);

			$this->plugin->alerts->Trigger( $alert_code, $variables );
		}
	}

	public function addon_plugin_activated( $plugin ) {
		$event_type = 'activated';
		$this->generate_addon_event( $plugin, $event_type );
	}

	public function addon_plugin_deactivated( $plugin ) {
		$event_type = 'deactivated';
		$this->generate_addon_event( $plugin, $event_type );
	}

	public function addon_plugin_installed( $plugin ) {
		$event_type = 'installed';
		$this->generate_addon_event( $plugin, $event_type );
	}

	public function generate_addon_event( $plugin, $event_type ) {
		$alert_code       = 5511;
		$tidy_plugin_name = preg_replace( "/\.[^.]+$/", "", basename( $plugin ) );
		$variables        = array(
			'EventType'       => $event_type,
			'addon_name'      => str_replace( 'Wpforms', 'WPForms', ucwords( str_replace( '-', ' ', $tidy_plugin_name ) ) ),
		);
		$this->plugin->alerts->Trigger( $alert_code, $variables );
	}

	public function check_other_changes( WSAL_AlertManager $manager ) {
		if ( $manager->WillOrHasTriggered( 5501 ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Method: This function make sures that alert 5500
	 * has not been triggered before triggering.
	 *
	 * @param  WSAL_AlertManager $manager - WSAL Alert Manager.
	 * @return bool
	 */
	public function must_not_be_new_form( WSAL_AlertManager $manager ) {
		if ( $manager->WillOrHasTriggered( 5500 ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Method: This function make sures that alert 5502
	 * has not been triggered before triggering
	 *
	 * @param  WSAL_AlertManager $manager - WSAL Alert Manager.
	 * @return bool
	 */
	public function check_if_duplicate( WSAL_AlertManager $manager ) {
		if ( $manager->WillOrHasTriggered( 5502 ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Check if the alert was triggered recently.
	 *
	 * Checks last 5 events if they occured less than 20 seconds ago.
	 *
	 * @param integer|array $alert_id - Alert code.
	 * @return boolean
	 */
	private function was_triggered_recently( $alert_id ) {
		// if we have already checked this don't check again.
		if ( isset( $this->cached_alert_checks ) && array_key_exists( $alert_id, $this->cached_alert_checks ) && $this->cached_alert_checks[ $alert_id ] ) {
			return true;
		}
		$query = new WSAL_Models_OccurrenceQuery();
		$query->addOrderBy( 'created_on', true );
		$query->setLimit( 5 );
		$last_occurences  = $query->getAdapter()->Execute( $query );
		$known_to_trigger = false;
		foreach ( $last_occurences as $last_occurence ) {
			if ( $known_to_trigger ) {
				break;
			}
			if ( ! empty( $last_occurence ) && ( $last_occurence->created_on + 20 ) > time() ) {
				if ( ! is_array( $alert_id ) && $last_occurence->alert_id === $alert_id ) {
					$known_to_trigger = true;
				} elseif ( is_array( $alert_id ) && in_array( $last_occurence[0]->alert_id, $alert_id, true ) ) {
					$known_to_trigger = true;
				}
			}
		}
		// once we know the answer to this don't check again to avoid queries.
		$this->cached_alert_checks[ $alert_id ] = $known_to_trigger;
		return $known_to_trigger;
	}

	/**
	 * Extract email address from a string.
	 *
	 * @param string $string  - String to search.
	 * @return string
	 */
	private function extract_emails( $string ) {
		// This regular expression extracts all emails from a string:
		$regexp = '/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i';
		preg_match( $regexp, $string, $m );
		return isset( $m[0] ) ? $m[0] : array();
	}

	private function array_key_exists_recursive( $key, $array ) {
		if ( is_array( $array ) && array_key_exists( $key, $array ) ) {
			return true;
		}
		if ( is_array( $array ) ) {
			foreach ( $array as $k => $value ) {
				if ( is_array( $value ) && $this->array_key_exists_recursive( $key, $value ) ) {
						return true;
				}
			}
		}
		return false;
	}

}
