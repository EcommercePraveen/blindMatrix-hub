<?php
/**
 * REST API Userslist Controller
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * REST API UsersList controller class.
 *
 */
class BM_Users_List_Controller extends WP_REST_Controller {
	/**
 	 * Constructor.
 	 */
	public function __construct(){
		$this->namespace = 'bm/v1';
		$this->rest_base= 'userslist';
		
		add_filter( 'user_has_cap', array($this,'check_user_has_capability'), 10, 3 );
	}

	/**
 	 * Check user has its capability to edit posts.
 	 */
	public function check_user_has_capability($allcaps, $caps, $args ){
		if ( isset( $caps[0] ) ) {
			switch ( $caps[0] ) {
				case 'blind_matrix_create_item':
				case 'blind_matrix_read_item': 
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
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
				'args'                => $this->get_collection_params(),
			),
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
	 * Check whether a given request has permission to read user lists.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check( $request ) {
		if ( !current_user_can('blind_matrix_read_item') ) {
			return new WP_Error( 'woocommerce_rest_cannot_view', __( 'Sorry, you cannot list resources.', 'woocommerce' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return true;
	}

	/**
	 * Get all user lists.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$post_id = isset($request['id']) ? $request['id']:0;
		$url_info = isset($request['url_info']) ? $request['url_info']:'';
		if(!$post_id && $url_info){
			$post_ids = get_posts(
				array(
					'post_type' => 'bm_users_list',
					'post_status' => array('free_trial','premium','expired','not_activated'),
					'fields' => 'ids',
					'posts_per_page' => '-1',
					'meta_key' => 'url_info',
					'meta_value' => $url_info
				)
			);
			$post_id  = isset($post_ids[0]) ? $post_ids[0]:'';
		}
		
		if(!$post_id){
			return new WP_Error( 'invalid_data', __( 'No Data Found', 'blindmatrix' ) ); 
		}
		
		$object = new BM_Users_List_Object($post_id);
		if(!is_object($object) || 'bm_users_list' != get_post_type($post_id)){
			return new WP_Error( 'invalid_data', __( 'Invalid Object', 'blindmatrix' ) );
		}
		
		$post_args = array(
			'post_status'      => $object->get_post_status(),
			'id'               => $object->get_id(), 
			'post_parent'      => $object->get_post_parent(),
			'created_date'     => $object->get_created_date(),
			'modified_date'    => $object->get_modified_date(),
		);
		
		$meta_args = array(
			'user_name'              => $object->get_user_name(),
			'user_email'             => $object->get_user_name(), 
			'url_info'               => $object->get_url_info(),
			'ip_address'             => $object->get_ip_address(),
			'user_info'              => $object->get_user_info(),
			'plugin_activated_date'  => $object->get_plugin_activated_date(),
			'plugin_status'          => $object->get_plugin_status(),
			'premium_activated_date' => $object->get_premium_activated_date(),
			'activation_key'         => $object->get_activation_key(),
			'plugin_expired_date'    => $object->get_plugin_expired_date(),
			'premium_site_url'       => $object->get_premium_site_url(),
			'premium_user_info'      => $object->get_premium_user_info(),
			'premium_ip_address'     => $object->get_premium_ip_address(),
			'appointment_activation_key' => $object->get_appointment_activation_key(),
			'appointment_status' 		 => $object->get_appointment_status(),
			'appointment_date'           => $object->get_appointment_date(),
            'reports'                    => $object->get_reports(),
		);
		
		$response = array( 'post_data' => array_merge($post_args,$meta_args),'post_id' => $object->get_id());
		return $response;
	}

	/**
	 * Create a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item( $request ) {
		$url_info = isset($request['url_info']) ? $request['url_info']:'';
		$ip_address = isset($request['ip_address']) ? $request['ip_address']:'';
		$user_info = isset($request['user_info']) ? $request['user_info']:'';
		$plugin_activated_date = isset($request['plugin_activated_date']) ? $request['plugin_activated_date']:'';
		$plugin_status = isset($request['plugin_status']) ? $request['plugin_status']:'';
		$id = isset($request['id']) ? $request['id']:0;
		$post_status = isset($request['post_status']) ? $request['post_status']:0;
		$premium_activated_date = isset($request['premium_activated_date']) ? $request['premium_activated_date']:'';
		$activation_key =  isset($request['activation_key']) ? $request['activation_key']:'';
		$plugin_expired_date = isset($request['plugin_expired_date']) ? $request['plugin_expired_date']:'';
		$premium_site_url = isset($request['premium_site_url']) ? $request['premium_site_url']:'';
		$premium_user_info = isset($request['premium_user_info']) ? $request['premium_user_info']:'';
		$premium_ip_address = isset($request['premium_ip_address']) ? $request['premium_ip_address']:'';
		$appointment_activation_key = isset($request['appointment_activation_key']) ? $request['appointment_activation_key']:'';
		$appointment_status = isset($request['appointment_status']) ? $request['appointment_status']:'';
		$appointment_date = isset($request['appointment_date']) ? $request['appointment_date']:'';
        $reports = isset($request['reports']) ? $request['reports']:'';
		if(!$plugin_status){
			return new WP_Error( 'invalid_data', __( 'Invalid params on creation', 'blindmatrix' ) );
		}

		$args = array( 'plugin_status' => $plugin_status);
		
		if($url_info){
			$args['url_info'] = $url_info;
		}
		
		if($ip_address){
			$args['ip_address'] = $ip_address;
		}
		
		if($user_info){
			$args['user_info'] = $user_info;
		}
		
		if($plugin_activated_date){
			$args['plugin_activated_date'] = $plugin_activated_date;
		}
		
		if($premium_activated_date){
			$args['premium_activated_date'] = $premium_activated_date;
		}
		
		if($activation_key){
			$args['activation_key'] = $activation_key;
		}
		
		if($plugin_expired_date){
			$args['plugin_expired_date'] = $plugin_expired_date;
		}
		
		if($premium_site_url){
			$args['premium_site_url'] = $premium_site_url;
		}
		
		if($premium_user_info){
			$args['premium_user_info'] = $premium_user_info;
		}
		
		if($premium_ip_address){
			$args['premium_ip_address'] = $premium_ip_address;
		}

		if($appointment_date){
			$args['appointment_date'] = $appointment_date;
		}
		
		if($appointment_activation_key){
			$args['appointment_activation_key'] = $appointment_activation_key;
		}
		
		if($appointment_status){
			$args['appointment_status'] = $appointment_status;
		}
        
        if($reports){
        	$args['reports'] = $reports;
        }
		
		$post_args = array();
		$post_args['post_status'] = $id ? get_post_status($id):'not_activated';
		if($post_status && $id){
			$status = '' != $post_status ? $post_status:get_post_status($id);
			$post_args['post_status'] = $status ? $status:'';
		}
		
		if($id){
			$object = new BM_Users_List_Object($id);
			$id = $object->update($id,$post_args,$args);
		}else{
			$object = new BM_Users_List_Object();
			$id = $object->create($post_args,$args);
		}
		
		$response = array('post_id' => $id);
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