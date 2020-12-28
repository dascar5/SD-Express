<?php

namespace Leadin\wp;

/**
 * Static function that wraps WordPress utility functions for the WordPress site.
 */
class Website {
	/**
	 * Return homepage url.
	 */
	public static function get_url() {
		return get_home_url();
	}
}
