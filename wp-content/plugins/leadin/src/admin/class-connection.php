<?php

namespace Leadin\admin;

/**
 * Handles portal connection to the plugin.
 */
class Connection {
	/**
	 * Retrieves user ID and create new metadata
	 *
	 * @param Array $user_meta array of pairs metadata - value.
	 */
	private static function add_metadata( $user_meta ) {
		$wp_user    = wp_get_current_user();
		$wp_user_id = $wp_user->ID;
		foreach ( $user_meta as $key => $value ) {
			add_user_meta( $wp_user_id, $key, $value );
		}
	}
	/**
	 * Retrieves user ID and deletes a piece of the users meta data.
	 *
	 * @param String $meta_key is the key of the data you want to delete.
	 */
	private static function delete_metadata( $meta_key ) {
		$wp_user    = wp_get_current_user();
		$wp_user_id = $wp_user->ID;
		delete_user_meta( $wp_user_id, $meta_key );
	}
	/**
	 * Connect portal id, domain, name to WordPress options and HubSpot email to user meta data.
	 *
	 * @param Number $portal_id     HubSpot account id.
	 * @param String $portal_name   HubSpot account name.
	 * @param String $portal_domain HubSpot account domain.
	 * @param String $hs_user_email HubSpot user email.
	 */
	public static function connect( $portal_id, $portal_name, $portal_domain, $hs_user_email ) {
		self::disconnect();

		add_option( 'leadin_portalId', $portal_id );
		add_option( 'leadin_account_name', $portal_name );
		add_option( 'leadin_portal_domain', $portal_domain );

		self::exit_intro();
		self::add_metadata( array( 'leadin_email' => $hs_user_email ) );
	}

	/**
	 * Removes portal id and domain from the WordPress options.
	 */
	public static function disconnect() {
		delete_option( 'leadin_portalId' );
		delete_option( 'leadin_account_name' );
		delete_option( 'leadin_portal_domain' );
		$users = get_users( array( 'fields' => array( 'ID' ) ) );
		foreach ( $users as $user ) {
			delete_user_meta( $user->ID, 'leadin_email' );
		}

		add_option( 'leadin_did_disconnect', true );
	}

	/**
	 * Store the options needed for Preview mode
	 *
	 * @param String $default_app landing page on the unauthed use.
	 */
	public static function skip_connect( $default_app ) {
		self::add_metadata( array( 'leadin_default_app' => $default_app ) );
	}

	/**
	 * Remove the option that will load Preview mode
	 */
	public static function exit_intro() {
		self::delete_metadata( 'leadin_default_app' );
	}
}
