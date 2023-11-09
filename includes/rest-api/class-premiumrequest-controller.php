<?php
/**
 * REST API Premium Request Controller
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST API Premium Request controller class.
 *
 */
class BM_Premium_Request_List_Controller extends WP_REST_Controller {
	/**
 	 * Constructor.
 	 */
	public function __construct(){
		$this->namespace = 'bm/v1';
		$this->rest_base= 'premiumrequest';
		
		add_filter( 'user_has_cap', array($this,'check_user_has_capability'), 10, 3 );
	}

	/**
 	 * Check user has its capability to edit posts.
 	 */
	public function check_user_has_capability($allcaps, $caps, $args ){
		if ( isset( $caps[0] ) ) {
			switch ( $caps[0] ) {
				case 'blind_matrix_create_item':
					$allcaps[$caps[0]] = true;
					break;
			}
		}
				
		return $allcaps;
	}

	/**
	 * Register the routes for userslist.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args'                => $this->get_endpoint_args_for_item_schema(),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
	}

	/**
	 * Create a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		$parent_id = isset($request['parent_id']) ? $request['parent_id']:'';
		$args = array(
			'name'               => isset($request['name']) ? $request['name']:'',
			'email'              => isset($request['email']) ? $request['email']:'',
			'phone_number'       => isset($request['phone_number']) ? $request['phone_number']:'',
			'site_url'      	 => isset($request['site_url']) ? $request['site_url']:'',
			'company_name'  	 => isset($request['company_name']) ? $request['company_name']:'',
		);

		$object = new BM_Premium_Request_List_Object();
		$id = $object->create(array('post_parent' => $parent_id),$args);
		$response = array('post_id' => $id);

		// Admin Email on premium requests 
		$email_subject = get_option('bm_hub_premium_request_email_subject','BlindMatrix ECommerce Premium Request');
		$email_message = get_option('bm_hub_premium_request_email_message','Hi,<br/><br/>Premium Requested Details,<br><br><b>URL:</b>{url}<br><b>Name:</b>{name}<br><b>Email:</b>{email}<br><b>Phone:</b>{phone}<br><b>Company Name:</b>{company}<br><b>Requested Date</b>:{date}<br><br/>Thanks');
		$url = isset($request['site_url']) ? $request['site_url']:'';
		$name = isset($request['name']) ? $request['name']:'';
		$email = isset($request['email']) ? $request['email']:'';
		$phone = isset($request['phone_number']) ? $request['phone_number']:'';
		$company = isset($request['company_name']) ? $request['company_name']:'';
		$date = gmdate('Y-m-d');
		$email_message = str_replace(array('{url}','{name}','{email}','{phone}','{company}','{date}'),array($url,$name,$email,$phone,$company,$date),$email_message);
		$recipients    = !empty(get_option('bm_hub_premium_request_recipients')) ? get_option('bm_hub_premium_request_recipients'):'praveen@blindmatrix.com';
		wp_mail($recipients,$email_subject,$email_message);

		return $response;
	}

	/**
	 * Check if a given request has access to create an item.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function create_item_permissions_check( $request ) {
		if(!current_user_can('blind_matrix_create_item')){
			return new WP_Error( "creation_restriction", 'Restricted to access', array( 'status' => 400 ) );
		}

		return true;
	}

}
