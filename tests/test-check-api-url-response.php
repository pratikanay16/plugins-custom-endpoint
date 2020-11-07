<?php
/**
 * Class Check Api Url Response
 *
 * @package Custom_Endpoint
 */

/**
 * Check api url response
 */
class CheckApiUrlResponse extends WP_UnitTestCase {
	/**
	 * Check api url response
	 */
	public function test_check_api_url_response() {
		$response      = wp_remote_get( API_CALL_URL . 'users/' );
		$response_code = wp_remote_retrieve_response_code( $response );

		$this->assertTrue( ! empty( json_decode( $response['body'] ) ) );
		$this->assertEquals( 200, $response_code );
	}
}
