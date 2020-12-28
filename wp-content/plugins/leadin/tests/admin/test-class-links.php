<?php
/**
 * Class LeadinUtilsTest
 *
 * @package Leadin
 */

use \Leadin\admin\Links;
/**
 * Test leadin-links.php
 */
class LeadinLinksTest extends WP_UnitTestCase {
	const TEST_PORTAL_ID = 12345;
	const TEST_DOMAIN    = 'my.test.domain';

	public function test_search_string_array() {
		$search_query_map = Links::get_search_string_array();
		$this->assertEqualSets( array( 'l', 'php', 'v', 'wp', 'theme', 'admin', 'ajaxUrl', 'domain', 'nonce' ), array_keys( $search_query_map ) );
	}
}
