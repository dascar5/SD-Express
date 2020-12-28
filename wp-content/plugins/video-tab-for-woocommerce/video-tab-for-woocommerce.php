<?php
/*
 * Plugin Name: Video Tab For WooCommerce
 * Version: 1.0.1
 * Plugin URI: https://wordpress.org/plugins/video-tab-for-woocommerce
 * Description: Plugin to add the new tab for video or additional content like contact forms, shortcodes, important features and other useful information to WooCommerce products. This Video Tab For WooCommerce plugin extends WooCommerce to allow shop owners to add additional information on the tab of individual product pages at the right of the default tabs.
 * Author: Promenade Themes
 * Author URI: https://promenadethemes.com/
 * License: GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Text Domain: video-tab-for-woocommerce
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'VTFW_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'VTFW_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

/**
 * Set up and initialize
 */
if( !class_exists('Video_Tab_For_WooCommerce_Main') ){

	class Video_Tab_For_WooCommerce_Main {

		private static $instance;

		/**
		 * Actions setup
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'vtfw_load_main_files' ), 3 );
			add_action( 'admin_notices', array( $this, 'vtfw_admin_notice' ), 4 );

		}

		/**
		 * Load main files for plugin
		 */
		function vtfw_load_main_files() {
			
			//To add tab at admin
			require_once( VTFW_DIR . '/custom-video-tab/video-tab-admin.php' );

			//To rented tab at frontend
			require_once( VTFW_DIR . '/custom-video-tab/video-tab-front.php' );

		}

		/**
		 * Dispaly notice if WooCommerce plugin is not active
		 */
		function vtfw_admin_notice() {

			//Display notice if woocommerce is not active
			if( !class_exists( 'WooCommerce' ) ){

			    echo '<div class="error">';

			    echo '<p>Video Tab For WooCommerce '.__('works if <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> plugin is active. Please click here <a href="'.admin_url('plugin-install.php?tab=search&type=term&s=WooCommerce').'" target="_blank">(WooCommerce)</a> to install and activate it.', 'video-tab-for-woocommerce').'</p>';

			    echo '</div>';		

			}
		}

		/**
		 * Returns the instance.
		 */
		public static function get_instance() {

			if ( !self::$instance )
				self::$instance = new self;

			return self::$instance;
		}
	}

	function vtfw_init_instance() {

		return Video_Tab_For_WooCommerce_Main::get_instance();

	}

}

add_action( 'plugins_loaded', 'vtfw_init_instance', 1 );