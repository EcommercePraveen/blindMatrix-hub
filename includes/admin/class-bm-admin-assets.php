<?php
/**
 * Admin Assets
 *
 * @class BM_Admin_Assets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BM_Admin_Assets class.
 */
class BM_Admin_Assets {
	/**
	 * Init.
	 */
	public static function init() {
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ), 9 );
		add_action( "admin_footer-toplevel_page_bm_dashboard", array( __CLASS__, 'footer_scripts' ) );
	}

	/**
	 * Admin Scripts.
	 */
	public static function admin_scripts($hook_suffix){
		$screen       = get_current_screen();
		$screen_id = is_object($screen) ? $screen->id:'';
		if(!$screen_id){
			return;
		}
		$screen_ids = array('toplevel_page_bm_dashboard','blindmatrix_page_users_list_table','blindmatrix_page_bm_email_template');
		if(!in_array($screen_id,$screen_ids)){
			return;
		}
				
		if ( 'toplevel_page_bm_dashboard' == $screen_id ){
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
		}

		$start_yr = '01-01-'.date('Y');
		$end_yr = '31-12-'.date('Y');
		
		wp_register_style( 'jquery-confirm-css', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css');
		wp_enqueue_style( 'jquery-confirm-css' );
		wp_register_script( 'jquery-confirm', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js', [], null, false );
		wp_register_script( 'jquery-chart', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js', [], null, false );
		wp_enqueue_script( 'bm-hub-admin', untrailingslashit( plugins_url( '/', BM_HUB_PLUGIN_FILE ) ) . '/assets/js/admin/admin.js', array( 'jquery','jquery-confirm','jquery-chart'), BM_VERSION );
		wp_localize_script(
			'bm-hub-admin',
			'bm_admin_params',
			array(
				'ajax_url'                  => admin_url( 'admin-ajax.php' ),
				'csv_emails_html'           => get_csv_emails_html(),
				'free_trial_count'          => count(bm_hub_get_user_lists_ids('free_trial',false,false,false,$start_yr,$end_yr)),
				'not_activated_count'       => count(bm_hub_get_user_lists_ids('not_activated',false,false,false,$start_yr,$end_yr)),
				'premium_count'             => count(bm_hub_get_user_lists_ids('premium',false,false,false,$start_yr,$end_yr)),
				'expired_count'             => count(bm_hub_get_user_lists_ids('expired',false,false,false,$start_yr,$end_yr)),
				'screen_id'                 => $screen_id,
				'months_count_data' => implode(',',array_values(bm_get_current_yr_user_lists_count_based_on_months())),
				'bm_get_activation_key_html'=> bm_get_activation_key_html(),
				'csv_userslist_html'        => get_csv_userslist_html(),
				'bm_get_appointment_activation_key_html' => bm_get_appointment_activation_key_html(),
			)
		);
	}
	/**
	 * Footer Scripts.
	 */
	public static function footer_scripts() { 
		$screen       = get_current_screen();
		$screen_id = is_object($screen) ? $screen->id:'';
		if(!$screen_id){
			return;
		}
		$screen_ids = array('toplevel_page_bm_dashboard','blindmatrix_page_users_list_table','blindmatrix_page_bm_email_template');
		if(!in_array($screen_id,$screen_ids)){
			return;
		}
		
		$screen_id = 'toplevel_page_bm_dashboard';
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($) {
				$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
				postboxes.add_postbox_toggles( '<?php echo $screen_id; ?>' );
			});
		</script>
		<?php
	}
}

BM_Admin_Assets::init();
