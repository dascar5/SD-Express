<?php
/**
 * Plugin Name: WP Activity Log for WPForms
 * Plugin URI: https://wpactivitylog.com/extensions/wpforms-activity-log/
 * Description: A WP Activity Log plugin extension to keep a log of changes within WPForms.
 * Text Domain: wsal-wpforms
 * Author: WP White Security
 * Author URI: http://www.wpwhitesecurity.com/
 * Version: 1.1
 * License: GPL2
 * Network: true
 *
 * @package Wsal
 * @subpackage Wsal Custom Events Loader
 */

/*
	Copyright(c) 2020  WP White Security  (email : info@wpwhitesecurity.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
 REQUIRED. Here we include and fire up the main core class. This will be needed regardless so be sure to leave line 37-39 in tact.
*/
require_once plugin_dir_path( __FILE__ ) . 'core/class-extension-core.php';
$core_settings = array(
	'text_domain'      => 'wsal-wpforms',
	'custom_alert_path' => trailingslashit( dirname( __FILE__ ) ) . 'wp-security-audit-log',
	'custom_sensor_path' => trailingslashit( trailingslashit( dirname( __FILE__ ) ) . 'wp-security-audit-log' . DIRECTORY_SEPARATOR . 'custom-sensors' ),
);
$wsal_extension = new WPWhiteSecurity\ActivityLog\Extensions\Common\Core( $core_settings );

/**
 * Adds new custom event objects for our plugin
 *
 * @method wsal_wpforms_add_custom_event_objects
 * @since  1.0.0
 * @param  array $objects An array of default objects.
 * @return array
 */
function wsal_wpforms_add_custom_event_objects( $objects ) {
	$new_objects = array(
		'wpforms'               => esc_html__( 'WPForms', 'wsal-wpforms' ),
		'wpforms-notifications' => esc_html__( 'Notifications in WPForms', 'wsal-wpforms' ),
		'wpforms_notifications' => esc_html__( 'Notifications in WPForms', 'wsal-wpforms' ),
		'wpforms-entries'       => esc_html__( 'Entries in WPForms', 'wsal-wpforms' ),
		'wpforms_entries'       => esc_html__( 'Entries in WPForms', 'wsal-wpforms' ),
		'wpforms-fields'        => esc_html__( 'Fields in WPForms', 'wsal-wpforms' ),
		'wpforms_fields'        => esc_html__( 'Fields in WPForms', 'wsal-wpforms' ),
		'wpforms_forms'         => esc_html__( 'Forms in WPForms', 'wsal-wpforms' ),
	);

	// combine the two arrays.
	$objects = array_merge( $objects, $new_objects );

	return $objects;
}

/**
 * Adds new ignored CPT for our plugin
 *
 * @method wsal_wpforms_add_custom_ignored_cpt
 * @since  1.0.0
 * @param  array $post_types An array of default post_types.
 * @return array
 */
function wsal_wpforms_add_custom_ignored_cpt( $post_types ) {
	$new_post_types = array(
		'wpforms',    // WP Forms CPT.
	);

	// combine the two arrays.
	$post_types = array_merge( $post_types, $new_post_types );
	return $post_types;
}

/**
 * Adds new meta formatting for our plugion
 *
 * @method wsal_wpforms_add_custom_meta_format
 * @since  1.0.0
 */
function wsal_wpforms_add_custom_meta_format( $value, $name ) {
	$check_value = (string) $value;
	if ( '%EditorLinkForm%' === $name ) {
		if ( 'NULL' !== $check_value ) {
			return '<a target="_blank" href="' . esc_url( $value ) . '">' . __( 'View form in the editor', 'wsal-wpforms' ) . '</a>';
		} else {
			return '';
		}
	}

	if ( '%EditorLinkEntry%' === $name ) {
		if ( 'NULL' !== $check_value ) {
			return '<a target="_blank" href="' . esc_url( $value ) . '">' . __( 'View entry in the editor', 'wsal-wpforms' ) . '</a>';
		} else {
			return '';
		}
	}
	return $value;
}

/**
 * Adds new meta formatting for our plugion
 *
 * @method wsal_wpforms_add_custom_meta_format_value
 * @since  1.0.0
 */
function wsal_wpforms_add_custom_meta_format_value( $value, $name ) {
	$check_value = (string) $value;
	if ( '%EditorLinkForm%' === $name ) {
		if ( 'NULL' !== $check_value ) {
			return '<a target="_blank" href="' . esc_url( $value ) . '">' . __( 'View form in the editor', 'wsal-wpforms' ) . '</a>';
		} else {
			return '';
		}
	}

	if ( '%EditorLinkEntry%' === $name ) {
		if ( 'NULL' !== $check_value ) {
			return '<a target="_blank" href="' . esc_url( $value ) . '">' . __( 'View entry in the editor', 'wsal-wpforms' ) . '</a>';
		} else {
			return '';
		}
	}
	return $value;
}

/**
 * Add our filters.
 */
add_filter( 'wsal_link_filter', 'wsal_wpforms_add_custom_meta_format_value', 10, 2 );
add_filter( 'wsal_meta_formatter_custom_formatter', 'wsal_wpforms_add_custom_meta_format', 10, 2 );
add_filter( 'wsal_event_objects', 'wsal_wpforms_add_custom_event_objects' );
add_filter( 'wsal_ignored_custom_post_types', 'wsal_wpforms_add_custom_ignored_cpt' );
