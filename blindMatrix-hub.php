<?php

/**

 * Plugin Name: BlindMatrix-hub

 * Plugin URI: https://live.blindssoftware.com/

 * Description: BlindMatrix Centralised Hub

 * Version: 1.0

 * Author: BlindMatrix

 * Author URI: https://live.blindssoftware.com/

 * Text Domain: blindmatrix

 * Tested up to: 6.0.3

 *

 */



defined( 'ABSPATH' ) || exit;  



if ( ! defined( 'BM_HUB_PLUGIN_FILE' ) ) {

	define( 'BM_HUB_PLUGIN_FILE', __FILE__ );

}



/**

 * Main Class.

 *

 * @class BM_HUB_BlindMatrix

 */

final class BM_HUB_BlindMatrix {

   /**

	 * Plugin version.

	 */

	public $version = '1.0';



   /**

	 * The single instance of the class.

	 */

	protected static $_instance = null;



   /**

	 * Main Instance.

	 *

	 * Ensures only one instance of object is loaded or can be loaded.

	 *

	 * @return object - Main instance.

	 */

	public static function instance() {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();

		}

		return self::$_instance;

	}



   /**

	 * Cloning is forbidden.

	 */

	public function __clone() {

		wc_doing_it_wrong( __FUNCTION__, __( 'Cloning is forbidden.', 'blindmatrix' ), '1.0' );

	}



	/**

	 * Unserializing instances of this class is forbidden.

	 */

	public function __wakeup() {

		wc_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of this class is forbidden.', 'blindmatrix' ), '1.0' );

	}



   /**

	 * Constructor.

	 */

	public function __construct() {

		$this->define_constants();

		$this->includes();

		$this->init_hooks();

	}



   /**

	 * Define Plugin Constants.

	 */

   public function define_constants(){

	  define( 'BM_HUB_ABSPATH' , dirname(__FILE__));

      define( 'BM_VERSION', $this->version );

   }

   

   /**

	 * Include required core files used in admin and on the frontend.

	 */

   public function includes(){

		include_once(BM_HUB_ABSPATH.'/includes/bm-core-functions.php');

		include_once(BM_HUB_ABSPATH.'/includes/class-bm-admin.php');

	    include_once(BM_HUB_ABSPATH.'/includes/class-bm-post-object.php');

	   	include_once(BM_HUB_ABSPATH.'/includes/class-bm-users-list.php');

		include_once(BM_HUB_ABSPATH.'/includes/class-bm-premium-request-list.php');

		if(is_admin()){

			include_once(BM_HUB_ABSPATH.'/includes/admin/class-bm-admin-assets.php');

			include_once(BM_HUB_ABSPATH.'/includes/admin/class-bm-ajax.php');

		}

   }



   /**

	 * Hook into actions and filters.

	 */

   public function init_hooks(){

		add_action('init',array($this,'init_callback'));

   }



   /**

	 * Init hook callback.

	 */

   public function init_callback(){

		include( BM_HUB_ABSPATH . '/includes/rest-api/class-rest-controller.php' );

   }



}



BM_HUB_BlindMatrix::instance();