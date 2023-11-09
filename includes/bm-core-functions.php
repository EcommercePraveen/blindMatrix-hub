<?php
/**
 * BlindMatrix Hub Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get user lists post statuses.
 *
 * @return array
 */
function bm_hub_user_lists_post_statuses(){
	return array('free_trial','premium','expired','not_activated');
}

/**
 * Get user lists ids.
 *
 * @return array
 */
function bm_hub_get_user_lists_ids($post_status = false,$post_ids = false,$offset = false,$per_page = false,$from_date= false,$to_date = false,$order_by = false,$order = false) {
	$args = array(
		'post_type' => 'bm_users_list',
		'post_status' => bm_hub_user_lists_post_statuses(),
		'fields' => 'ids',
		'posts_per_page' => -1,
	);

	if($post_status){
		$args['post_status'] = array($post_status);
	}
	
	if($post_ids){
		$args['post__in'] = $post_ids;
	}
	
	if($offset){
		$args['offset'] = $offset;
	}
	
	if($per_page){
		$args['posts_per_page'] = $per_page;
	}
	
	if($from_date && $to_date){
		$args['date_query'] = array(
			array(
        		'after' => $from_date,
				'before' => $to_date,
        		'inclusive' => true,
    			),
		);
	} else if($from_date ){
		$args['date_query'] = array(
			array(
        		'after' => $from_date,
    			),
		);
	} else if($to_date){
		$args['date_query'] = array(
			array(
        		'before' => $to_date,
    			),
		);
	}
	
	if('ID' == $order_by){
		$args['orderby'] = $order_by;
	}else if('url_info' == $order_by){
		$args['meta_key'] = 'url_info';
		$args['order_by'] = 'meta_value';
	}
	
	if($order){
		$args['order'] = $order;
	}
			
	return get_posts($args);
}

/**
 * Get user lists ids based on search.
 *
 * @return array
 */
function bm_hub_get_user_lists_ids_based_on_search($searched_keyword = false){
	return get_posts(
		array(
        'fields' => 'ids',
        'post_type' => 'bm_users_list',
		'post_status' => bm_hub_user_lists_post_statuses(),
		'posts_per_page' => -1,
        'meta_query' => array(
			array(
				'key' => 'plugin_status',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'plugin_activated_date',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'user_info',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'ip_address',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'url_info',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			'relation' => 'OR'
		),
		));
}

/**
 * Get premium request lists ids.
 *
 * @return string
 */
function bm_hub_get_premium_request_lists_ids($post_ids = false,$offset = false,$per_page = false,$order_by= false,$order = false){
	$args = array(
		'post_type' => 'bm_premium_request',
		'post_status' => array('publish'),
		'fields' => 'ids',
		'posts_per_page' => -1,
	);

	if($post_ids){
		$args['post__in'] = $post_ids;
	}
	
	if($offset){
		$args['offset'] = $offset;
	}
	
	if($per_page){
		$args['posts_per_page'] = $per_page;
	}

	if('ID' == $order_by){
		$args['orderby'] = $order_by;
	}else if('name' == $order_by){
		$args['meta_key'] = 'name';
		$args['order_by'] = 'meta_value';
	}
	
	if($order){
		$args['order'] = $order;
	}
	
	return get_posts($args);
}

function bm_hub_get_premium_requests_based_on_search($searched_keyword = false){
	return get_posts(
		array(
        'fields' => 'ids',
        'post_type' => 'bm_premium_request',
		'post_status' => array('publish'),
		'posts_per_page' => -1,
        'meta_query' => array(
			array(
				'key' => 'email',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'name',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'phone_number',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			array(
				'key' => 'company_name',
				'value' => $searched_keyword,
				'compare' => 'LIKE'
			),
			'relation' => 'OR'
		),
		));
}

/**
 * Get formatted date in local time.
 *
 * @return string
 */
function bm_get_formatted_date($date){
	if(!$date){
		return '';
	}
	
	$timestamp_with_offset = strtotime($date) + get_option( 'gmt_offset', 0 ) * HOUR_IN_SECONDS;
	$format = get_option('date_format') .' '. get_option('time_format');
	return date_i18n( $format, $timestamp_with_offset );
}
/**
 * Display post status name 
 *
 * @return string
 */
function bm_display_post_status($status){
	$status_name = '';
	switch($status){
		case 'free_trial':
			$status_name = __('Free Trial','blindmatrix');
			break;
		case 'premium':
			$status_name = __('Premium','blindmatrix');
			break;
		case 'expired':
			$status_name = __('Expired','blindmatrix');
			break;
		case 'not_activated':
			$status_name = __('Not Activated','blindmatrix');
			break;
	}
	
	return $status_name;
}
/**
 * Get CSV emails HTML
 *
 * @return string
 */
function get_csv_emails_html(){
	$emails = get_option('bm_stored_csv_email_ids');
	if(empty($emails)){
		return;
	}
	$count = 1;
	ob_start();
	include(BM_HUB_ABSPATH.'/includes/admin/views/html-csv-emails-list.php');
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * Get CSV emails HTML
 *
 * @return string
 */
function get_csv_userslist_html(){
	$userslist_data = get_option('bm_stored_csv_userslist_data');
	if(empty($userslist_data)){
		return;
	}
	
	$count = 1;
	ob_start();
	include(BM_HUB_ABSPATH.'/includes/admin/views/html-csv-users-list.php');
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

/**
 * Get current year user lists count based on months.
 *
 * @return array
 */
function bm_get_current_yr_user_lists_count_based_on_months(){
	$months = array('January','February','March','April','May','June','July','August','September','October','November','December');
	$count_data = array();
	foreach($months as $month){
		$timestamp    = strtotime("$month ".date('Y'));
		$start_month = date('01-m-Y', $timestamp);
		$end_month  = date('t-m-Y', $timestamp); 
		$count_data[$month] = count(bm_hub_get_user_lists_ids(false,false,false,false,$start_month,$end_month));
	}
	
	return $count_data;
}
/**
 * Get activation key HTML
 *
 * @return html
 */
function bm_get_activation_key_html(){
	$post_id = isset($_GET['bm_hub_post_id']) ? $_GET['bm_hub_post_id']:0;
	if(!$post_id){
		return "";
	}
	
	$object = new BM_Users_List_Object($post_id);
	if(!is_object($object)){
		return "";
	}
	
	$activation_key = $object->get_activation_key();
	ob_start();
	include(BM_HUB_ABSPATH.'/includes/admin/views/html-copy-activation-key.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get activation key HTML
 *
 * @return html
 */
function bm_get_appointment_activation_key_html(){
	$post_id = isset($_GET['bm_hub_post_id']) ? $_GET['bm_hub_post_id']:0;
	if(!$post_id){
		return "";
	}
	
	$object = new BM_Users_List_Object($post_id);
	if(!is_object($object)){
		return "";
	}
	
	$activation_key = $object->get_appointment_activation_key();
	ob_start();
	include(BM_HUB_ABSPATH.'/includes/admin/views/html-copy-activation-key.php');
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
}

/**
 * Get activation key email subject.
 *
 * @return string
 */
function bm_hub_get_plugin_activation_key_email_subject(){
	return !empty(get_option('bm_hub_email_subject')) ? get_option('bm_hub_email_subject'):__('BlindMatrix Plugin Activation Key','blindmatrix');
}

/**
 * Get activation key email message.
 *
 * @return string
 */
function bm_hub_get_plugin_activation_key_email_msg(){
	$image          = plugin_dir_url( __DIR__ ).'assets/img/activation-key-image.jpg';
	$key_border_img = plugin_dir_url( __DIR__ ).'assets/img/activation-key-border.jpg';
	$logo           = plugin_dir_url( __DIR__ ).'assets/img/logo.png';
	ob_start();
	?>
	<img class="alignleft size-full wp-image-41" style="margin: 0;" src="<?php echo esc_url($image);?>" alt="" width="900" height="550" />
	<div style="float: left; display: flex; background: url('<?php echo esc_url($key_border_img);?>') no-repeat; width: 900px; height: 100px; font-size: 15px; font-weight: bold; text-align: center; background-size: contain; background-position: center; margin-bottom: 5px;"><span style="margin: auto;">{activation_key}</span></div>
	<p style="clear:both;text-align:center;max-width:900px;"><b>Note:</b> Activation key will be active for 24 hours.</p>
	<p style="clear: both; font-size: 17px;"><strong>Regards,</strong>
	Blindmatrix ECommerce</p>
	<img class="alignleft wp-image-23" style="clear: both;" src="<?php echo esc_url($logo);?>" alt="" width="191" height="51" />
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	return !empty(get_option('bm_hub_email_message')) ? get_option('bm_hub_email_message'):$content;
}