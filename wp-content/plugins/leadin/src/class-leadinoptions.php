<?php

namespace Leadin;

use \Datetime;

/**
 * Class that wraps the functions to access the Leadin's options on the db.
 */
class LeadinOptions {
	const ACQUISITION_ATTRIBUTION = 'hubspot_acquisition_attribution';
	const PORTAL_ID               = 'leadin_portalId';
	const AFFILIATE_CODE          = 'hubspot_affiliate_code';
	const WPE_TEMPLATE            = 'wpe_template';

	/**
	 * Cache for options.
	 *
	 * @var Array $cache
	 */
	private static $cache = array();

	/**
	 * Wrapper of WordPress' get_option function.
	 *
	 * @param String $option_name Option name.
	 */
	private static function get_option( $option_name ) {
		if ( isset( self::$cache[ $option_name ] ) ) {
			return self::$cache[ $option_name ];
		}
		return get_option( $option_name );
	}

	/**
	 * Get acquisition attribution
	 */
	public static function get_acquisition_attribution() {
		return self::get_option( self::ACQUISITION_ATTRIBUTION );
	}

	/**
	 * Return portal id
	 */
	public static function get_portal_id() {
		return self::get_option( self::PORTAL_ID );
	}

	/**
	 * Return affiliate code
	 */
	public static function get_affiliate_code() {
		$affiliate_code_option = trim( self::get_option( self::AFFILIATE_CODE ) );
		preg_match( '/(?:(?:hubs\.to)|(?:mbsy\.co))\/([a-zA-Z0-9]+)/', $affiliate_code_option, $matches );

		if ( count( $matches ) === 2 ) {
			$affiliate_link = $matches[1];
		} else {
			$affiliate_link = $affiliate_code_option;
		}

		return $affiliate_link;
	}


	/**
	 * Return WPEngine template
	 */
	public static function get_wpe_template() {
		return self::get_option( self::WPE_TEMPLATE );
	}
}
