<?php

/**

 * Initialize this version of the REST API.

 *

 */



defined( 'ABSPATH' ) || exit;



/**

 * Class responsible for loading the REST API and all REST API namespaces.

 */

class BM_REST_API_Controller {

	/**

	 * REST API namespaces and endpoints.

	 *

	 * @var array

	 */

	protected static $controllers = array();



	/**

	 * Hook into WordPress ready to init the REST API as needed.

	 */

	public static function init() { 

		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ), 10 );

	}



	/**

	 * Register REST API routes.

	 */

	public static function register_rest_routes() {

		if(!class_exists('WP_REST_Controller')){

			return;

		}

				

		foreach ( self::get_rest_namespaces() as $namespace => $controllers ) {

			foreach ( $controllers as $controller_name => $controller_class ) {

				include( dirname( __FILE__ ) . '/class-'.$controller_name.'-controller.php' );

				self::$controllers[ $namespace ][ $controller_name ] = new $controller_class();

				self::$controllers[ $namespace ][ $controller_name ]->register_routes();

			}

		}

	}



	/**

	 * Get API namespaces - new namespaces should be registered here.

	 *

	 * @return array List of Namespaces and Main controller classes.

	 */

	public static function get_rest_namespaces() {

		return apply_filters(

			'bm_rest_api_get_rest_namespaces',

			array(

				'bm/v1'        => self::get_v1_controllers(),

			)

		);

	}



	/**

	 * List of controllers in the wp/v1 namespace.

	 *

	 * @return array

	 */

	public static function get_v1_controllers() {

		return array(

			'userslist'                  => 'BM_Users_List_Controller',
			'premiumrequest'             => 'BM_Premium_Request_List_Controller'
		);

	}



}



BM_REST_API_Controller::init();