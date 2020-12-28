<?php

namespace Leadin\admin\api;

use Leadin\admin\Connection;
use Leadin\utils\RequestUtils;

/**
 * Registration API, used to store the portal id and the domain as a WordPress option.
 */
class SkipConnectApi extends ApiGenerator {
	/**
	 * Registration API constructor. Adds the ajax hooks.
	 *
	 * @var String $endpoint API endpoint.
	 */
	public $endpoint = 'wp_ajax_leadin_skip_signup';

	/**
	 * Registration API runner. It validates the portal id and domain and stores them as WordPress options.
	 */
	public function run() {
		$request_body = file_get_contents( 'php://input' );
		$data         = json_decode( $request_body, true );
		$default_app  = $data['defaultApp'];

		if ( empty( $default_app ) ) {
			Connection::exit_intro();
		} else {
			Connection::skip_connect( $default_app );
		}

		RequestUtils::send_message( 'Success' );
	}
}
