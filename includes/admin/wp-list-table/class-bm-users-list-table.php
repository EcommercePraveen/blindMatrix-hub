<?php
/**
 * Users List Table
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * BM_Users_List_Table class.
 */
class BM_Users_List_Table extends WP_List_Table {
	/**
	 * Count.
	 */
	protected static $count = 1;
	/**
	 * Initialize the table list.
	 */
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'user list',
				'plural'   => 'user lists',
				'ajax'     => false,
			)
		);
	}

	/**
	 * No items found text.
	 */
	public function no_items() {
		esc_html_e( 'No data found.', 'blindmatrix' );
	}
	
	/**
	 * Handle bulk actions.
	 */
	public function handle_bulk_actions() {
		$action = $this->current_action();
		if ( ! $action ) {
			return;
		}

		$bulk_action_ids = isset($_REQUEST['ids']) && !empty($_REQUEST['ids']) ? $_REQUEST['ids']:array();
		if(!empty($bulk_action_ids) && 'delete' == $action ){
			foreach($bulk_action_ids as $bulk_action_id){
				wp_delete_post($bulk_action_id);
			}
		}
		
		$id = isset($_REQUEST['bm_hub_post_id']) ? absint($_REQUEST['bm_hub_post_id']):'';
		if('delete' == $action && $id){
			wp_delete_post($id);
		}		
	}
	
	/**
	 * Table list views.
	 *
	 * @return array
	 */
	protected function get_views() {
		$status_links   = array();
		$statuses       = array_merge(array('all'),bm_hub_user_lists_post_statuses());
		$status_request = !empty( $_REQUEST['status'] ) ?$_REQUEST['status'] : 'all'; // WPCS: input var okay. CSRF ok.
		
		foreach ( $statuses as $status_name ) {
			$user_lists_ids_count = count(bm_hub_get_user_lists_ids($status_name));
			if(!$user_lists_ids_count):
				continue;
			endif;
			
			$class_name= $status_name == $status_request ? 'current':'';
            $display_status = $status_name;
            if('not_activated' == $status_name ){
            	$display_status = 'Inactive';
            }
			
			$url = "admin.php?page=users_list_table&status=".$status_name;
			$status_links[$status_name] = sprintf('<a href="%s" class="%s">%s <span>(%s)</span></a>',$url,$class_name,ucfirst(str_replace('_',' ',$display_status)),$user_lists_ids_count);
		}

		return $status_links;
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'        		 => '<input type="checkbox" />',
			'sno'                => __('S.No','blindmatrix'),
			'url'               => __( 'Site URL', 'blindmatrix' ),
			'email'             => __( 'Email Address', 'blindmatrix' ),
			'trial_activation_date' => __( 'Trial Activation Date', 'blindmatrix' ),
			'premium_activation_date' => __('Premium Activation Date','blindmatrix'),
			'expiry_date'      => __('Expiry Date','blindmatrix'),
			'status'            => __( 'Current Status', 'blindmatrix' ),
			'plugin_status'     => __( 'Plugin Status', 'blindmatrix' ),
			'appointment' 		=>  __( 'Appointment', 'blindmatrix' ),
		);
	}

	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'sno' => array( 'ID', true ),
			'url' => array( 'url_info', true ),
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {
		return array(
			'delete' => __( 'Delete', 'blindmatrix' ),
		);
	}

	/**
	 * Column cb.
	 *
	 * @param  $item userslist instance.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf( '<input type="checkbox" name="ids[]" value="%s" />',$item->get_id() );
	}
	/**
	 * Default Columns.
	 *
	 * @return array
	 */
	public function column_default($item, $column_name ){
		switch ( $column_name ) {
			case 'sno':
				$url = add_query_arg(array('bm_hub_post_id' => $item->get_id(),'action' => 'edit'),admin_url( 'admin.php?page=users_list_table'));
                $sys_status_url = $item->get_url_info().'?bme_system_status=yes';
				$delete_url = add_query_arg(array('bm_hub_post_id' => $item->get_id(),'action' => 'delete'),admin_url( 'admin.php?page=users_list_table'));
				$reset_options_url = $item->get_url_info().'?blindmatrix_reset_options=yes';
				return sprintf('<a href="%s">%s</a>',esc_url($url),esc_html(self::$count++))."<div class='row-actions'>
				<span class='edit'><a href='$url'>Edit</a></span> |
                <span class='system-status'><a href='$sys_status_url' target='_blank'>System Status</a></span> |
				<span class='delete'><a href='$delete_url' class='bm-delete-post'>Delete</a></span> | 
				<span class='delete reset-option'><a class='bm-delete-post' target='_blank' href='$reset_options_url'>Reset plugin data</a></span>
			</div>" ;
				break;
			case 'url':
				return '<a href="'.$item->get_url_info().'">'.$item->get_url_info().'</a>';
				break;
			case 'email':
				return '<a href="#">'.$item->get_user_email().'</a>';
				break;
			case 'trial_activation_date':
				if(empty($item->get_plugin_activated_date())){
					return '-';
				}
				
				return bm_get_formatted_date($item->get_plugin_activated_date());
				break;
			case 'premium_activation_date':
				if(empty($item->get_premium_activated_date())){
					return '-';
				}
				return bm_get_formatted_date($item->get_premium_activated_date());
				break;
			case 'expiry_date':
				if(empty($item->get_plugin_activated_date())){
					return '-';
				}
				
				if(empty($item->get_premium_activated_date()) && 'free_trial' == $item->get_post_status()){
					$date = bm_get_formatted_date(gmdate('Y-m-d H:i:s',strtotime('+14 Days',strtotime($item->get_plugin_activated_date()))));
					return sprintf(__('<b>Trial Expiry Date: </b>%s','blindmatrix'),$date);
				}
				
				if(!empty($item->get_premium_activated_date()) && 'premium' == $item->get_post_status()){
					$date = bm_get_formatted_date(gmdate('Y-m-d H:i:s',strtotime('+1 Year',strtotime($item->get_plugin_activated_date()))));
					return sprintf(__('<b>Premium Expiry Date: </b>%s','blindmatrix'),$date);
				}
				
				return '-';
				break;
			case 'status':
				if('free_trial' == $item->get_post_status()){
					 $status =__('Free Trial','blindmatrix');
					 $background = '#00FFFF';
				}else if('premium' == $item->get_post_status()){
					 $background = '#00FF00';
					 $status = __('Premium','blindmatrix');
				}else if('not_activated' == $item->get_post_status()){
					 $background = '#FFFF00';
					 $status = __('Inactive','blindmatrix');
				}else{
					 $background = '#FF0000';
					 $status = __('Expired','blindmatrix');
				}
				
				echo '<mark class="bm-status" style="background: '.$background.';display: inline-flex;line-height: 2.5em;color: #000;"><span style="margin:0 1em;"><b>'.$status.'</b></span></mark>';
				break;
			case 'plugin_status':
				return 'activated' == $item->get_plugin_status() ? '<span style="color:#000;font-weight:bold;">'.__('Activated','blindmatrix').'</span>':'<span style="color:#000;font-weight:bold;">'.__('Deactivated','blindmatrix').'</span>';
				break;
			case 'appointment':
				if ('' == $item->get_appointment_status() || 'Disabled' == $item->get_appointment_status() ){
					return '<span style="color:#FF0000;">'.__('Disabled','blindmatrix').'</span>';
				}else if( 'active' == $item->get_appointment_status() ){
					return '<span style="color:#0000FF;">'.__('Enabled','blindmatrix').'</span>';
				};
				break;
		}

		return '';
	}

	/**
	 * Render Extra Table Contents.
	 */
	public function extra_tablenav($which) {
		if('top' != $which){
			return $which;
		}
		
		echo sprintf('<a href="%s" class="button button-primary" style="margin-right:10px;">Import</a>',admin_url('admin.php?page=users_list_table&bm_userlist_upload=yes'));
		if(!count(bm_hub_get_user_lists_ids())){
			return;
		}

		echo "<button class='button button-primary' name='bm_hub_export_action' value='bm_hub_export'>Export</button>";
	}

	/**
	 * Prepare table list items.
	 */
	public function prepare_items() {		
		$this->prepare_column_headers();
		$per_page     = $this->get_items_per_page( 'bm_per_page' );
		$current_page = $this->get_pagenum();
		if ( 1 < $current_page ) {
			$offset = $per_page * ( $current_page - 1 );
		} else {
			$offset = 0;
		}
		
		self::$count = $offset ? $offset+1:1;
		
		$count = count(bm_hub_get_user_lists_ids());
		$searched_keyword = isset($_REQUEST['s']) ? $_REQUEST['s']:false;
		$post_ids         = bm_hub_get_user_lists_ids_based_on_search($searched_keyword);
		$status_request = !empty( $_REQUEST['status'] ) ?$_REQUEST['status'] : false;
		$order_by       = !empty( $_REQUEST['orderby'] ) ?$_REQUEST['orderby'] : false;
		$order          = !empty( $_REQUEST['order'] ) ?$_REQUEST['order'] : false;
		$user_list_ids    = bm_hub_get_user_lists_ids($status_request,$post_ids,$offset,$per_page,false,false,$order_by,$order);	
		
		if(is_array($user_list_ids) && !empty($user_list_ids)){
			foreach($user_list_ids as $user_list_id){
				$object = new BM_Users_List_Object($user_list_id);
				if(!is_object($object)){
					continue;
				}
								
				$this->items[] = $object;
			}
		}
		
		//Set the pagination.
		$this->set_pagination_args(
			array(
				'total_items' => $count,
				'per_page'    => $per_page,
				'total_pages' => ceil( $count / $per_page ),
			)
		);
	}
	
	/**
	 * Set _column_headers property for table list
	 */
	protected function prepare_column_headers() {
		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
		);
	}

	/**
	 * Prepare table list items.
	 */
	public function display(){
		$title   = esc_html__( 'Users List', 'blindmatrix' );
		echo "
			<div class='wrap'>
			<div class='bm-users-list-wrapper'>
				<h1 class='wp-heading-inline'>{$title}</h1>
				<hr class='wp-header-end'>
		";
		echo '<form method="post" class="bm-users-list-form">';
		$this->handle_bulk_actions();
		$this->prepare_items();
		$this->views();
		$searched_keyword = isset($_REQUEST['s']) ? $_REQUEST['s']:false;
		if($searched_keyword){
			echo '<span class="subtitle">Search results for: <strong>'.$searched_keyword.'</strong></span>';
		}
		
		$this->search_box( esc_html__( 'Search Users List', 'blindmatrix' ), 'users-list-search-input' );
		parent::display();
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
}