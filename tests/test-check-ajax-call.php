<?php
/**
 * Class CheckAjaxCall
 *
 * @package Custom_Endpoint
 */

/**
 * Check ajax call
 */
class CheckAjaxCall extends WP_Ajax_UnitTestCase {
	/**
	 * Check ajax call
	 */
	public function test_check_api_url_response() {
		try {
			$this->_handleAjax( 'userlist' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		$response = json_decode( $this->_last_response, true );
		$this->assertTrue( true, ! empty( $response['data'] ) );
	}
}
