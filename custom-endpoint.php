<?php
/**
 * Custom endpoint to show users list
 *
 * @package custom endpoint
 * @version 1.0.0
 */

/*
Plugin Name: custom endpoint
Description: Plugin which run on custom url which is not WordPress page,post,category or tag, Once you activate plugin it will enable yoursitename.com/users/
Author: Pratik Patel
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

define( 'PLUGIN_DIR', plugin_dir_path( __FILE__ ) ); // plugin directory path.
define( 'PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // plugin full url.
define( 'API_CALL_URL', 'https://jsonplaceholder.typicode.com/' ); // api call url.
register_activation_hook( __FILE__, 'cu_rewrite_activation' );

/**
 * To refresh permalink rules
 */
function cu_rewrite_activation() {
	cu_rewrite_add_rewrites();
	flush_rewrite_rules();
}

add_action( 'init', 'cu_rewrite_add_rewrites' );

/**
 * New rewrite rules for custom url page like yoursitename.com/users/
 */
function cu_rewrite_add_rewrites() {
	add_rewrite_tag( '%cpage%', '[^/]' );
	add_rewrite_rule(
		'^users/?$',
		'index.php?cpage=custom_page_url',
		'top'
	);
}

add_action( 'template_redirect', 'cu_rewrite_catch_page' );

/**
 * Template for custom rewrite page
 */
function cu_rewrite_catch_page() {
	if ( get_query_var( 'cpage' ) === 'custom_page_url' ) {
		// Bootstrap,datatable styles.
		wp_enqueue_style( 'Datatable-css', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css', '', '1.10.22' );
		wp_enqueue_style( 'Datatable-css-1', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', '', '3.3.7' );
		wp_enqueue_style( 'Datatable-css-3', 'https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap.min.css', '', '2.2.6' );
		wp_enqueue_style( 'customurl-css', PLUGIN_URL . '/custom-endpoint/css/customurl.css', '', '1.0' );

		// Bootstrap,datatable js.
		wp_enqueue_script( 'Datatable-jquery', 'https://code.jquery.com/jquery-3.5.1.js', '', '3.5.1', true );
		wp_enqueue_script( 'customurl-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', '', '3.3.7', true );
		wp_enqueue_script( 'customurl-bootstrap-1', 'https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap.min.js', '', '1.10.22', true );
		wp_enqueue_script( 'customurl-bootstrap-1', 'https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js', '', '2.2.6', true );
		wp_enqueue_script( 'customurl-bootstrap-1', 'https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap.min.js', '', '2.2.6', true );
		wp_enqueue_script( 'Datatable-js', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js', '', '1.10.22', true );
		wp_enqueue_script( 'customurl-userdata-js', PLUGIN_URL . '/custom-endpoint/js/customurl_userdata.js', '', '1.0', true );

		// Pass ajax_url to script.js.
		wp_localize_script(
			'customurl-userdata-js',
			'customurl_ajax_object',
			array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nonce_token' => wp_create_nonce( 'nonce_custom_endpoint' ),
			)
		);

		// user list template.
		include_once PLUGIN_DIR . 'view/users-template.php';
		exit();
	}
}

add_action( 'wp_ajax_userlist', 'userlist_callback' );
add_action( 'wp_ajax_nopriv_userlist', 'userlist_callback' );

/**
 * Call to api url to get users list
 *
 * @function : userlist_callback
 */
function userlist_callback() {
	try {
		$response     = wp_remote_get( API_CALL_URL . 'users/' );
		$data['data'] = json_decode( $response['body'] );
		echo wp_json_encode( $data );
	} catch ( Exception $ex ) {
		$data['data'] = null;
		echo wp_json_encode( $data );
	}
	wp_die();
}


add_action( 'wp_ajax_userdetail', 'userdetail_callback' );
add_action( 'wp_ajax_nopriv_userdetail', 'userdetail_callback' );

/**
 * Call to api url to get users list
 *
 * @function : userdetail_callback
 */
function userdetail_callback() {
	if ( isset( $_REQUEST['security'] ) ) {
		$nonce = strval( wp_unslash( $_REQUEST['security'] ) );
		if ( wp_verify_nonce( $nonce, 'nonce_custom_endpoint' ) ) {
			$userid = isset( $_REQUEST['userid'] ) ? intval( $_REQUEST['userid'] ) : 0;
			if ( 0 !== $userid ) {
				$response              = wp_remote_get( API_CALL_URL . 'users/' . $userid );
				$data['data']['error'] = '';
				$data['data']          = json_decode( $response['body'] );

				echo wp_json_encode( $data );
			} else {
				$data['data'] = array( 'error' );
				echo wp_json_encode( $data );
			}
			wp_die();
		} else {
			wp_die();
		}
	} else {
		wp_die();
	}
}



