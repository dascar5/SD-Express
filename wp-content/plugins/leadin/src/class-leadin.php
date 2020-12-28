<?php

namespace Leadin;

use \Leadin\AssetsManager;
use \Leadin\PageHooks;
use \Leadin\admin\LeadinAdmin;

/**
 * Main class of the plugin.
 */
class Leadin {
	/**
	 * Plugin's constructor. Everything starts here.
	 */
	public function __construct() {
		new PageHooks();
		if ( is_admin() ) {
			new LeadinAdmin();
		}
	}
}
