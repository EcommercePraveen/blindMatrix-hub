<?php
/**
 * Premium Requests List Table
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * BM_Premium_Requests_List_Table class.
 */
class BM_Premium_Requests_List_Table extends WP_List_Table {
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
				'singular' => 'premium request',
				'plural'   => 'premium requests',
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
			'url'                => __('URL','blindmatrix'),
			'name'               => __( 'Name', 'blindmatrix' ),
			'email'              => __( 'Email Address', 'blindmatrix' ),
			'phone_number' 		 => __( 'Phone Number', 'blindmatrix' ),
			'company_name' 		 => __('Company Name','blindmatrix'),
			'requested_date'     => __('Requested Date','blindmatrix'),
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
			'name' => array( 'name', true ),
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
				return self::$count++.'.';
				break;
			case 'url':
				return !empty($item->get_site_url()) ? '<a href="'.$item->get_site_url().'">'.$item->get_site_url().'</a>':'-';
				break;
			case 'name':
				return !empty($item->get_name()) ? $item->get_name():'-';
				break;
			case 'email':
				return !empty($item->get_email()) ? $item->get_email():'-';
				break;
			case 'phone_number':
				return !empty($item->get_phone_number()) ? $item->get_phone_number():'-';
				break;
			case 'company_name':
				return !empty($item->get_company_name()) ? $item->get_company_name():'-';
				break;
			case 'requested_date':
				return bm_get_formatted_date($item->get_created_date());
				break;
		}

		return '';
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
		
		$count = count(bm_hub_get_premium_request_lists_ids());
		$searched_keyword = isset($_REQUEST['s']) ? $_REQUEST['s']:false;
		$post_ids         = bm_hub_get_premium_requests_based_on_search($searched_keyword);
		$order_by       = !empty( $_REQUEST['orderby'] ) ?$_REQUEST['orderby'] : false;
		$order          = !empty( $_REQUEST['order'] ) ?$_REQUEST['order'] : false;
		$premium_request_list_ids    = bm_hub_get_premium_request_lists_ids($post_ids,$offset,$per_page,$order_by,$order);	
		
		if(is_array($premium_request_list_ids) && !empty($premium_request_list_ids)){
			foreach($premium_request_list_ids as $premium_request_list_id){
				$object = new BM_Premium_Request_List_Object($premium_request_list_id);
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
		$title   = esc_html__( 'Premium Requests List', 'blindmatrix' );
		echo "
			<div class='wrap'>
			<div class='bm-premium-requests-list-wrapper'>
				<h1 class='wp-heading-inline'>{$title}</h1>
				<hr class='wp-header-end'>
		";
		echo '<form method="post" class="bm-premium-requests-list-form">';
		$this->handle_bulk_actions();
		$this->prepare_items();
		$searched_keyword = isset($_REQUEST['s']) ? $_REQUEST['s']:false;
		if($searched_keyword){
			echo '<span class="subtitle">Search results for: <strong>'.$searched_keyword.'</strong></span>';
		}
		
		$this->search_box( esc_html__( 'Search', 'blindmatrix' ), 'premium-requests-list-search-input' );
		parent::display();
		echo '</form>';
		echo '</div>';
		echo '</div>';
	}
}