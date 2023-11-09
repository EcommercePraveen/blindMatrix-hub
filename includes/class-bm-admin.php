<?php
/**
 * Admin Page
 *
 * @class BM_Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BM_Admin class.
 */
class BM_Admin {
	/**
	 * Userslist Post Type.
	 */
	public static $post_type = 'bm_users_list';
	/**
	 * Premium Request Post Type.
	 */
	public static $premium_request_post_type = 'bm_premium_request';

	/**
	 * Init.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ), 9 );
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_status' ), 9 );
		add_action( 'wp_loaded', array( __CLASS__, 'wp_loaded' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_action( 'blind_matrix_settings_tabs_activation_key',array(__CLASS__,'activation_key_email_settings'));
		add_action( 'blind_matrix_settings_tabs_appointment_activation_key',array(__CLASS__,'appointment_activation_key_email_settings') );
		add_action( 'blind_matrix_settings_tabs_premium_request',array(__CLASS__,'premium_request_email_settings') );
		add_action( 'blind_matrix_sections_promotional',array(__CLASS__,'promotional_email_settings'));
		add_action( 'blind_matrix_view_userslist_settings_tabs_plugin_settings' ,array(__CLASS__,'render_plugin_settings_view_userslist'));
		add_action( 'blind_matrix_view_userslist_settings_tabs_addon_settings',array(__CLASS__,'render_addon_settings_view_userslist') );
        add_action( 'blind_matrix_view_userslist_settings_tabs_system_status',array(__CLASS__,'render_system_status_view_userslist') );
		add_filter( 'set_screen_option_bm_per_page', array( __CLASS__, 'set_items_per_page' ), 10, 3 );
		add_filter( 'screen_layout_columns', array(__CLASS__, 'on_screen_layout_columns'), 10, 2);
	}
	
	/**
	 * On screen layout columns.
	 */
	public static function on_screen_layout_columns($columns, $screen) {
		$screen_ids = array('toplevel_page_bm_dashboard');
		foreach($screen_ids as $screen_id){
			if ($screen == $screen_id) {
				$columns[$screen_id] = 2;
			}
		}
		
		return $columns;
	}
	
	/**
	 * Admin menu.
	 */
	public static function admin_menu(){
		add_menu_page(__('BlindMatrix Hub','blindmatrix'), __('BlindMatrix Hub','blindmatrix'), 'manage_options', 'bm_dashboard',array( __CLASS__, 'dashboard_page'), 'dashicons-businessman',2);
		
		$hook = add_submenu_page('bm_dashboard',__('Dashboard','blindmatrix'), __('Dashboard','blindmatrix'), 'manage_options', 'bm_dashboard',   array( __CLASS__, 'dashboard_page' ) );	
		add_action("load-".$hook, array(__CLASS__,'on_load_menu'));

		$hook = add_submenu_page('bm_dashboard',__('Users List','blindmatrix'), __('Users List','blindmatrix'), 'manage_options', 'users_list_table',   array( __CLASS__, 'render_users_list_table' ) ,'dashicons-clipboard' );	
		add_action("load-".$hook, array(__CLASS__,'on_load_menu'));

		add_submenu_page('bm_dashboard',__('Email Template','blindmatrix'), __('Email Template','blindmatrix'), 'manage_options', 'bm_email_template',   array( __CLASS__, 'email_template_page' ) ,'dashicons-clipboard' );
		
		$hook = add_submenu_page('bm_dashboard',__('Premium Requests','blindmatrix'), __('Premium Requests','blindmatrix'), 'manage_options', 'premium_requests_list_table',   array( __CLASS__, 'render_premium_requests_list_table' ) ,'dashicons-clipboard' );	
	} 	
	
	/**
	 * On Load menu page.
	 */
	public static function on_load_menu(){
		// Set Screen options.
		$screen = get_current_screen();
		add_screen_option(
			'per_page',
			array(
				'default' => 20,
				'option'  => 'bm_per_page',
			)
		);
		
		// Add metabox in blindmatrix menu page.
		if('toplevel_page_bm_dashboard' == $screen->id){
			add_meta_box( 'bm_usage_overview', __( 'Total BlindMatrix Plugin Usage Overview' ,'blindmatrix'), array(__CLASS__, 'usage_overview_metabox'), $screen->id,'normal','high');
			add_meta_box( 'bm_pie_chart_overview', __( 'BlindMatrix Plugin Usage Overview by Current year' ,'blindmatrix'), array(__CLASS__, 'pie_chart_overview_metabox'), $screen->id,'side','high');
			add_meta_box( 'bm_bar_chart_overview', __( 'BlindMatrix Plugin Usage Overview by Current Year Month' ,'blindmatrix'), array(__CLASS__, 'bar_chart_overview_metabox'), $screen->id,'advanced','high');
		}
	}
	/**
	 * BlindMatrix Usage Overview Metabox.
	 */
	public static function usage_overview_metabox(){		
		include(BM_HUB_ABSPATH.'/includes/admin/views/meta-boxes/html-usage-overview-metabox.php');
	}	
	/**
	 * BlindMatrix Pie Chart Overview Metabox.
	 */
	public static function pie_chart_overview_metabox(){
		if(!bm_hub_get_user_lists_ids()){
			echo 'No Data Found.';
			return;
		}
		
		include(BM_HUB_ABSPATH.'/includes/admin/views/meta-boxes/html-pie-chart-overview-metabox.php');
	}
	/**
	 * BlindMatrix Bar Chart Overview Metabox.
	 */
	public static function bar_chart_overview_metabox(){
		if(!bm_hub_get_user_lists_ids()){
			echo 'No Data Found.';
			return;
		}
		
		include(BM_HUB_ABSPATH.'/includes/admin/views/meta-boxes/html-bar-chart-overview-metabox.php');
	}
	
	/**
	 * Set items per Page.
	 */
	public static function set_items_per_page($default, $option,$value ){
		return 'bm_per_page' === $option ? absint( $value ) : $default;
	}
	
	/**
	 * Dashboard Page.
	 */
	public static function dashboard_page(){
		$screen_id = 'toplevel_page_bm_dashboard';
		include(BM_HUB_ABSPATH.'/includes/admin/views/meta-boxes/html-main-metabox.php');
	}
	
	/**
	 * Email template page.
	 */
	public static function email_template_page(){
		global $current_tab;
		$tabs = array(
			'activation_key' => __('Activation Key Email','blindmatrix'),
			'appointment_activation_key' => __('Appointment Activation Key Email','blindmatrix'),
			'promotional' => __('Promotional Email','blindmatrix'),
			'premium_request' => __('Premium Request Email','blindmatrix')
		);
		
		$current_tab_label = isset( $tabs[ $current_tab ] ) ? $tabs[ $current_tab ] : '';
		if ( ! isset( $tabs[ $current_tab ] ) ) :
			wp_safe_redirect( admin_url( 'admin.php?page=bm_email_template' ) );
			exit;
		endif;
		
		include(BM_HUB_ABSPATH.'/includes/admin/views/html-promotional-tab-main.php');
	}
	/**
	 * Activation key email settings.
	 */
	public static function activation_key_email_settings(){
		$email_subject = bm_hub_get_plugin_activation_key_email_subject();
		$email_message = bm_hub_get_plugin_activation_key_email_msg();
		include(BM_HUB_ABSPATH.'/includes/admin/views/html-activation-key-email-template.php');
	}
	/**
	 * Appointment Activation key email settings.
	 */
	public static function appointment_activation_key_email_settings(){
		$email_subject = get_option('bm_hub_appointment_email_subject','BlindMatrix Appointment Activation Key');
		$email_message = get_option('bm_hub_appointment_email_message','Hi{username},<br/><br/>Activation Key for the BlindMatrix Plugin is <div style="border: 2px dotted; background: #ededed;text-align: center; padding: 10px;"> {activation_key} </div>. <br/><br/>Thanks');
		include(BM_HUB_ABSPATH.'/includes/admin/views/html-appointment-email-template.php');
	}
	/**
	 * Premium Request email template settings.
	 */
	public static function premium_request_email_settings(){
		$email_subject = get_option('bm_hub_premium_request_email_subject','BlindMatrix ECommerce Premium Request');
		$email_message = get_option('bm_hub_premium_request_email_message','Hi,<br/><br/>Premium Requested Details,<br><br><b>URL:</b>{url}<br><b>Name:</b>{name}<br><b>Email:</b>{email}<br><b>Phone:</b>{phone}<br><b>Company Name:</b>{company}<br><b>Requested Date</b>:{date}<br><br/>Thanks');
		include(BM_HUB_ABSPATH.'/includes/admin/views/html-premium-request-email-template.php');
	}
	/**
	 * Promotional email settings.
	 */
	public static function promotional_email_settings(){
		global $current_section;
		$sections = array(
			'settings' => __('Settings','blindmatrix'),
			'template' => __('Template','blindmatrix')
		); 
		
		echo '<ul class="subsubsub">';
		$array_keys = array_keys( $sections );
		foreach ( $sections as $id => $label ) :
			$url       = admin_url( 'admin.php?page=bm_email_template&tab=promotional&section=' . sanitize_title( $id ) );
			$class     = ( $current_section === $id ? 'current' : '' );
			$separator = ( end( $array_keys ) === $id ? '' : '|' );
			$text      = esc_html( $label );
			echo "<li><a href='$url' class='$class'>$text</a> $separator </li>";
		endforeach;
		
		echo '</ul><br class="clear" />';
		
		switch($current_section){
			case 'settings':
				include(BM_HUB_ABSPATH.'/includes/admin/views/html-promotional-settings.php');
				break;
			case 'template':
				$email_subject = get_option('bm_hub_promotional_email_subject','Promotional Email');
				$email_message = get_option('bm_hub_promotional_email_message','Hi,<br/><br/>Current Plugin status is <b>{status}</b>.<br/><br/>Thanks');
				include(BM_HUB_ABSPATH.'/includes/admin/views/html-promotional-email-template.php');
				break;
		}
	}
	/**
	 * Render premium requests list table.
	 */
	public static function render_premium_requests_list_table(){
		if ( ! class_exists( 'BM_Premium_Requests_List_Table' ) ) {
			require_once BM_HUB_ABSPATH.'/includes/admin/wp-list-table/class-bm-premium-requests-list-table.php';
		}

		$table_object = new BM_Premium_Requests_List_Table();
		$table_object->display();
	}
	/**
	 * Render plugin settings in view userslist tab.
	 */
	public static function render_plugin_settings_view_userslist(){
		$post_id = absint($_GET['bm_hub_post_id']);
		$user_lists_object = new BM_Users_List_Object($post_id);
		$recipient = '';
		if(is_object($user_lists_object)):
			$user_data = unserialize(unserialize($user_lists_object->get_user_info()));
			$recipient = isset($user_data['from_address']) ?$user_data['from_address']:'' ;
		endif;
		
		include(BM_HUB_ABSPATH.'/includes/admin/views/html-view-userslist-table.php');
	}
	
	/**
	 * Render plugin settings in view userslist tab.
	 */
	public static function render_addon_settings_view_userslist(){
		$post_id = absint($_GET['bm_hub_post_id']);
		$user_lists_object = new BM_Users_List_Object($post_id);
		include(BM_HUB_ABSPATH.'/includes/admin/views/html-view-userslist-appointment-table.php');
	}
    
    /**
	 * Render system status in view userslist tab.
	 */
    public static function render_system_status_view_userslist(){
    	$post_id = absint($_GET['bm_hub_post_id']);
		$user_lists_object = new BM_Users_List_Object($post_id);
        $reports = json_decode($user_lists_object->get_reports());
        include(BM_HUB_ABSPATH.'/includes/admin/views/html-view-userslist-system-status.php');
    }
	
	/**
	 * WP Loaded callback.
	 */
	public static function wp_loaded(){
		self::save_view_userslist_settings();
		self::save_email_template_settings();
	}
	
	/**
	 * Save view userslist settings.
	 */
	public static function save_view_userslist_settings(){
		if(!isset($_GET['page'],$_GET['bm_hub_post_id'],$_GET['action']) || 'users_list_table' != $_GET['page'] || 'edit' != $_GET['action']){
			return;
		}
				
		global $current_view_userlist_tab;
		$current_view_userlist_tab     = empty( $_GET['tab'] ) ? 'plugin_settings' : sanitize_title( wp_unslash( $_GET['tab'] ) );
	}
	
	/**
	 * Save email template settings.
	 */
	public static function save_email_template_settings(){
		if(!isset($_GET['page']) || 'bm_email_template' != $_GET['page']){
			return;
		}
		
		global $current_tab,$current_section;   
		$current_tab     = empty( $_GET['tab'] ) ? 'activation_key' : sanitize_title( wp_unslash( $_GET['tab'] ) );
		$current_section = empty( $_REQUEST['section'] ) ? 'settings' : sanitize_title( wp_unslash( $_REQUEST['section'] ) ); 
		   
		if(!isset($_REQUEST['bm_hub_save_email_template'])){
			return;
		}
		   
		$option_args = array();
		if('activation_key' == $current_tab){
		  $activation_key_args = array(
			'bm_hub_recipients' => isset($_REQUEST['bm_hub_recipients']) ? $_REQUEST['bm_hub_recipients']:'',
			'bm_hub_email_subject' => isset($_REQUEST['bm_hub_email_subject']) ? $_REQUEST['bm_hub_email_subject']:'',
			'bm_hub_email_message' => isset($_REQUEST['bm_hub_email_message']) ? wp_unslash($_REQUEST['bm_hub_email_message']):'',
			);
			
			$option_args = array_merge($option_args,$activation_key_args);
		}
		
		if('appointment_activation_key' == $current_tab){
		  $activation_key_args = array(
			'bm_hub_appointment_recipients' => isset($_REQUEST['bm_hub_appointment_recipients']) ? $_REQUEST['bm_hub_appointment_recipients']:'',
			'bm_hub_appointment_email_subject' => isset($_REQUEST['bm_hub_appointment_email_subject']) ? $_REQUEST['bm_hub_appointment_email_subject']:'',
			'bm_hub_appointment_email_message' => isset($_REQUEST['bm_hub_appointment_email_message']) ? wp_unslash($_REQUEST['bm_hub_appointment_email_message']):'',
			);
			
			$option_args = array_merge($option_args,$activation_key_args);
		}
		
		if('promotional' == $current_tab){
			$promotional_args = array(
				'bm_hub_promotional_recipients' => isset($_REQUEST['bm_hub_promotional_recipients']) ? wp_unslash($_REQUEST['bm_hub_promotional_recipients']):'',
				'bm_hub_promotional_email_subject' => isset($_REQUEST['bm_hub_promotional_email_subject']) ? wp_unslash($_REQUEST['bm_hub_promotional_email_subject']):'',
				'bm_hub_promotional_email_message' => isset($_REQUEST['bm_hub_promotional_email_message']) ? wp_unslash($_REQUEST['bm_hub_promotional_email_message']):'',
			);
			
			$option_args = array_merge($option_args,$promotional_args);
		}

		if('premium_request' == $current_tab){
			$premium_request_args = array(
				'bm_hub_premium_request_recipients' => isset($_REQUEST['bm_hub_premium_request_recipients']) ? wp_unslash($_REQUEST['bm_hub_premium_request_recipients']):'',
				'bm_hub_premium_request_email_subject' => isset($_REQUEST['bm_hub_premium_request_email_subject']) ? wp_unslash($_REQUEST['bm_hub_premium_request_email_subject']):'',
				'bm_hub_premium_request_email_message' => isset($_REQUEST['bm_hub_premium_request_email_message']) ? wp_unslash($_REQUEST['bm_hub_premium_request_email_message']):'',
				);

			$option_args = array_merge($option_args,$premium_request_args);
		}

		if(empty($option_args)){
			return;
		}
								
		foreach($option_args as $option_key => $option_value){
			update_option(sanitize_key($option_key),$option_value);
		}
	}
	
	/**
	 * Admin init action.
	 */
	public static function admin_init(){
		self::handle_export_csv();
		self::handle_upload_for_user_list_table();
		self::handle_upload();
		self::render_preview_activation_key_email();
		self::render_preview_premium_request_email();
	}
	/**
	 * Handle export CSV.
	 */
	public static function handle_export_csv(){
		if(!isset($_GET['page'],$_GET['page']) || 'users_list_table' != $_GET['page'] ){
			return;
		}
		
		if(!isset($_REQUEST['bm_hub_export_action'])){
			return;
		}
		
		include(BM_HUB_ABSPATH.'/includes/class-bm-csv-exporter.php');
	}
	
	/**
	 * Handle upload for users list.
	 */
	public static function handle_upload_for_user_list_table(){
		if(!isset($_GET['page'],$_GET['page'],$_GET['bm_userlist_upload'],$_REQUEST['bm-upload-userlist-action']) || 'users_list_table' != $_GET['page']){
			return true;
		}
								
		if(isset($_FILES['import']['name']) && !$_FILES['import']['name']){
			return array('status' => 'error' ,'msg' => __('CSV File is left empty','blindmatrix'));
		}
		
		if ( isset($_FILES['import']['name'] ) && ! self::is_file_valid_csv( ( wp_unslash( $_FILES['import']['name'] ) ), false ) ) {
				return array('status' => 'error' ,'msg' => __( 'Invalid file type. The importer supports CSV and TXT file formats.', 'blindmatrix' ) );
		}
		
		$overrides = array(
				'test_form' => false,
				'mimes'     => array( 'csv' => 'text/csv','txt' => 'text/plain'),
			);
		$import    = $_FILES['import'];
		$upload    = wp_handle_upload( $import, $overrides );
		$file_path = isset($upload['file']) ? $upload['file']:'';
		if ( isset( $upload['error'] ) || !$file_path || !file_exists($file_path) ) {
			 return array('status' => 'error' ,'msg' => __( 'Invalid file', 'blindmatrix' ) );
		}
		
		$handle = fopen($upload['file'], 'r' ); 
		$raw_data = array();
		$email_ids = array();
		if ( false !== $handle ) {
			$row_keys = array_map( 'trim', fgetcsv( $handle, 0, ',', '"', "\0" ) ) ;
			while ( 1 ) {
				$row = fgetcsv( $handle, 0, ',', '"', "\0" ) ;
				if ( false !== $row ) {
					$raw_data[]   = $row;
				} else {
					break;
				}
			}
			
		if(!empty($raw_data)){
			update_option('bm_stored_csv_userslist_data',$raw_data);
			wp_safe_redirect(add_query_arg(array('import_userlist' => true),admin_url( 'admin.php?page=users_list_table')));
			exit;
		}
		
		return true;
		}
	}
	
	/**
	 * Handle upload.
	 */
	public static function handle_upload(){
		if(!isset($_GET['page'],$_GET['page'],$_GET['tab']) || 'bm_email_template' != $_GET['page'] || 'promotional' != $_GET['tab']){
			return true;
		}
		
		if(!isset($_REQUEST['bm-upload-action'])){
			return true;
		}
		
		if(isset($_FILES['import']['name']) && !$_FILES['import']['name']){
			return array('status' => 'error' ,'msg' => __('CSV File is left empty','blindmatrix'));
		}

		if ( isset($_FILES['import']['name'] ) && ! self::is_file_valid_csv( ( wp_unslash( $_FILES['import']['name'] ) ), false ) ) {
				return array('status' => 'error' ,'msg' => __( 'Invalid file type. The importer supports CSV and TXT file formats.', 'blindmatrix' ) );
		}
		
		$overrides = array(
				'test_form' => false,
				'mimes'     => array( 'csv' => 'text/csv','txt' => 'text/plain'),
			);
		$import    = $_FILES['import'];
		$upload    = wp_handle_upload( $import, $overrides );
		$file_path = isset($upload['file']) ? $upload['file']:'';
		if ( isset( $upload['error'] ) || !$file_path || !file_exists($file_path) ) {
			 return array('status' => 'error' ,'msg' => __( 'Invalid file', 'blindmatrix' ) );
		}
		
		$handle = fopen($upload['file'], 'r' ); 
		$raw_data = array();
		$email_ids = array();
		if ( false !== $handle ) {
			$row_keys = array_map( 'trim', fgetcsv( $handle, 0, ',', '"', "\0" ) ) ;
			while ( 1 ) {
				$row = fgetcsv( $handle, 0, ',', '"', "\0" ) ;
				if ( false !== $row ) {
					$raw_data[]   = $row;
				} else {
					break;
				}
			}
			
			foreach($raw_data as $emails){
				$email_ids[] = isset($emails[0]) ? $emails[0]:'';
			}
		}
		
		delete_option('bm_stored_csv_email_ids');
		if(!empty($email_ids)){
			update_option('bm_stored_csv_email_ids',$email_ids);
			wp_safe_redirect(add_query_arg(array('import' => true),admin_url( 'admin.php?page=bm_email_template&tab=promotional&section=settings')));
			exit;
		}
		
		return true;
	}
	
	/**
	 * Is valid CSV file.
	 */
	public static  function is_file_valid_csv( $file, $check_path = true ) {
		if ( $check_path && false !== stripos( $file, '://' ) ) {
			return false;
		}

		$valid_filetypes =array(
			'csv' => 'text/csv',
			'txt' => 'text/plain',
		);

		$filetype = wp_check_filetype( $file, $valid_filetypes );
		if ( in_array( $filetype['type'], $valid_filetypes, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Render Users List Table.
	 */
	public static function render_users_list_table(){
		if(isset($_GET['page'],$_GET['bm_hub_post_id'],$_GET['action']) && 'users_list_table' == $_GET['page'] && $_GET['bm_hub_post_id'] && 'edit' == $_GET['action'] && !isset($_GET['bm_userlist_upload']) && !isset($_GET['import_userlist'])){
			$post_id = absint($_GET['bm_hub_post_id']);
			$user_lists_object = new BM_Users_List_Object($post_id);
        	$reports = json_decode($user_lists_object->get_reports());
            
			global $current_view_userlist_tab;
			$tabs = array(
				'plugin_settings' => __('Plugin Settings','blindmatrix'),
				'addon_settings' => __('Addon Settings','blindmatrix'),
			);
            if(!empty($reports)){
            	$tabs['system_status'] = __('System Status','blindmatrix');
            }
			$current_tab_label = isset( $tabs[ $current_view_userlist_tab ] ) ? $tabs[ $current_view_userlist_tab ] : '';
			include(BM_HUB_ABSPATH.'/includes/admin/views/html-view-userslist-main-settings.php');
			
			
			?>

			<?php
		}else if(isset($_GET['page']) && 'users_list_table' == $_GET['page'] && !isset($_GET['bm_userlist_upload']) && !isset($_GET['import_userlist'])){
			if ( ! class_exists( 'BM_Users_List_Table' ) ) {
				require_once BM_HUB_ABSPATH.'/includes/admin/wp-list-table/class-bm-users-list-table.php';
			}

			$table_object = new BM_Users_List_Table();
			$table_object->display();
		}else if(isset($_GET['page'],$_GET['bm_userlist_upload']) && 'users_list_table' == $_GET['page'] && 'yes' == $_GET['bm_userlist_upload']){
			include(BM_HUB_ABSPATH.'/includes/admin/views/html-upload-userslist-data.php');
		}else if(isset($_GET['page'],$_GET['import_userlist']) && $_GET['import_userlist']){
			include(BM_HUB_ABSPATH.'/includes/admin/views/html-import-userslist-data.php');
		}
	}

	/**
	 * Render Preview Activation Key Email.
	 */
	public static function render_preview_activation_key_email(){
		if ( isset( $_GET['preview_activation_key_mail'] ) || isset($_GET['preview_appointment_activation_key_mail']) ){
			$post_ids = get_posts(
				array(
				'post_type' =>'bm_users_list',
				'post_status' => bm_hub_user_lists_post_statuses(),
				'fields' => 'ids',
				'posts_per_page' => -1,
			   )
			);
			
			$random_key=array_rand($post_ids,1);
			$post_id = isset($post_ids[$random_key]) ? $post_ids[$random_key]:'';
			$email_subject = '';
			$email_message = '';
			$activation_key = '';
			if($post_id){
				$post_object = new BM_Users_List_Object($post_id);
				if(is_object($post_object)){
					$user_name = $post_object->get_user_name();
					
					if(isset( $_GET['preview_activation_key_mail'] )){
						$email_subject = bm_hub_get_plugin_activation_key_email_subject();
						$email_message = bm_hub_get_plugin_activation_key_email_msg();
						$activation_key = !empty($post_object->get_activation_key()) ? $post_object->get_activation_key():'-';
					}
			
					if(isset( $_GET['preview_appointment_activation_key_mail'] )){
						$email_subject = get_option('bm_hub_appointment_email_subject','BlindMatrix Appointment Activation Key');
						$email_message = get_option('bm_hub_appointment_email_message','Hi{username},<br/><br/>Activation Key for the BlindMatrix Plugin is {activation_key}. <br/><br/>Thanks');
						$activation_key = !empty($post_object->get_appointment_activation_key()) ? $post_object->get_appointment_activation_key():'-';
					}
					
					$email_message = str_replace(array('{username}','{activation_key}'),array($user_name,$activation_key),$email_message);
				}
			}
			
			echo "<div><h1>$email_subject</h1></div>"; 
			echo wpautop($email_message);
			exit;
		}
	}

	/**
	 * Render preview premium request email.
	 */
	public static function render_preview_premium_request_email(){
		if ( isset( $_GET['preview_premium_request_mail'] ) ){
			$post_ids = get_posts(
				array(
				'post_type' =>'bm_premium_request',
				'post_status' => array('publish'),
				'fields' => 'ids',
				'posts_per_page' => -1,
			   )
			);
			
			$random_key=array_rand($post_ids,1);
			$post_id = isset($post_ids[$random_key]) ? $post_ids[$random_key]:'';
			$email_subject = '';
			$email_message = '';
			$activation_key = '';
			if($post_id){
				$post_object = new BM_Premium_Request_List_Object($post_id);
				if(is_object($post_object)){
					$email_subject = get_option('bm_hub_premium_request_email_subject','BlindMatrix ECommerce Premium Request');
					$email_message = get_option('bm_hub_premium_request_email_message','Hi,<br/><br/>Premium Requested Details,<br><br><b>URL:</b>{url}<br><b>Name:</b>{name}<br><b>Email:</b>{email}<br><b>Phone:</b>{phone}<br><b>Company Name:</b>{company}<br><b>Requested Date</b>:{date}<br><br/>Thanks');
					$url = $post_object->get_site_url();
					$name = $post_object->get_name();
					$email = $post_object->get_email();
					$phone = $post_object->get_phone_number();
					$company = $post_object->get_company_name();
					$date = $post_object->get_created_date();
					$email_message = str_replace(array('{url}','{name}','{email}','{phone}','{company}','{date}'),array($url,$name,$email,$phone,$company,$date),$email_message);
				}
			}
			
			echo "<div><h1>$email_subject</h1></div>"; 
			echo wpautop($email_message);
			exit;
		}
	}

	/**
	 * Register Post Types.
	 */
	public static function register_post_types(){
		register_post_type(
			self::$post_type,
			apply_filters(
				'bm_users_list_post_type_args',
				array(
					'label'           => __( 'Users List', 'blindmatrix' ),
					'public'          => false,
					'hierarchical'    => false,
					'supports'        => false,
					'capability_type' => 'post',
					'rewrite'         => false,
				)
			)
		);
		register_post_type(
			self::$premium_request_post_type,
				array(
					'label'           => __( 'Premium Request List', 'blindmatrix' ),
					'public'          => false,
					'hierarchical'    => false,
					'supports'        => false,
					'capability_type' => 'post',
					'rewrite'         => false,
				)
		);
	}

	/**
	 * Register Post Status.
	 */
	public static function register_post_status(){
		$post_statuses = apply_filters(
			'bm_users_list_post_statuses_args',
			array(
					'not_activated'    => array(
						'label'                     => __( 'Not Activated', 'Blinds status', 'blindmatrix' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						/* translators: %s: number of Not activated */
						'label_count'               => _n_noop( 'Not Activated <span class="count">(%s)</span>', 'Not Activated <span class="count">(%s)</span>', 'blindmatrix' ),
					),
					'free_trial'    => array(
						'label'                     => __( 'Free Trial', 'Blinds status', 'blindmatrix' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						/* translators: %s: number of free trials */
						'label_count'               => _n_noop( 'Free Trial <span class="count">(%s)</span>', 'Free Trial <span class="count">(%s)</span>', 'blindmatrix' ),
					),
					'premium' => array(
						'label'                     => _x( 'Premium', 'Blinds status', 'blindmatrix' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						/* translators: %s: number of premiums */
						'label_count'               => _n_noop( 'Premium <span class="count">(%s)</span>', 'Premium <span class="count">(%s)</span>', 'blindmatrix' ),
					),
					'expired' => array(
						'label'                     => _x( 'Expired', 'Blinds status', 'blindmatrix' ),
						'public'                    => false,
						'exclude_from_search'       => false,
						'show_in_admin_all_list'    => true,
						'show_in_admin_status_list' => true,
						/* translators: %s: number of expired */
						'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'blindmatrix' ),
					)
				)
			);

		foreach ( $post_statuses as $status => $values ) {
			register_post_status( $status, $values );
		} 
	}
}

BM_Admin::init();