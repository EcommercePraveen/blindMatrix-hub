<?php
/**
 * Admin Ajax
 *
 * @class BM_Admin_Ajax
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * BM_Admin_Ajax class.
 */
class BM_Admin_Ajax {
	/**
	 * Init.
	 */
	public static function init() {
		$ajax_events = array(
			'generate_key_and_send_activation_email',
			'promotional_send_mail_action',
			'appointment_send_mail_action',
			'userslist_import_action',
		);

		foreach($ajax_events as $ajax_event){
			add_action( 'wp_ajax_bm_'.$ajax_event, array( __CLASS__, $ajax_event ));
		}
	}

	/**
	 * Generate key and send activation email.
	 */
	public static function generate_key_and_send_activation_email(){
		try{
			if(!isset($_POST) || !isset($_POST['api_server_type'],$_POST['api_name'],$_POST['post_id'])){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}

			$post_id = absint($_POST['post_id']);
			$post_object = new BM_Users_List_Object($post_id);
			if(!is_object($post_object)){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}
			
			$user_data     = unserialize(unserialize($post_object->get_user_info()));
			$email_address  = isset($user_data['from_address']) ? $user_data['from_address']:'';
			if(!$email_address){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}

			$server_type = isset($_POST['api_server_type'])?$_POST['api_server_type']:'' ;
			$name = isset($_POST['api_name']) ? $_POST['api_name']:'';
			
			if(!$server_type || !$name){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}
			
			$encoded_string = base64_encode(json_encode(array('server' => $server_type,'name' => $name,'id'=>$post_id,'sec' => time())));
			if(!$encoded_string){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}
			
			update_post_meta($post_id,'activation_key',$encoded_string);
			
			$email_subject = get_option('bm_hub_email_subject','BlindMatrix Plugin Activation Key');
			$email_message = get_option('bm_hub_email_message','Hi{username},<br/><br/>Activation Key for the BlindMatrix Plugin is {activation_key}. <br/><br/>Thanks');
			$from_name     = isset($user_data['from_name']) ? $user_data['from_name']:'';
			$email_message = str_replace(array('{activation_key}','{username}'),array('<b>'.$encoded_string.'</b>','<b>'.$from_name.'</b>'),$email_message);
			$recipients    = !empty(get_option('bm_hub_recipients')) ? explode(',',get_option('bm_hub_recipients')):array();
			$to_addresses  = implode(',',array_merge(array($email_address),$recipients));
// 			$success       = wp_mail($to_addresses,$email_subject,$email_message);
// 			if(!$success){
// 				throw new Exception( __( 'Email Not Sent', 'blindmatrix' ) );
// 			}

			wp_send_json_success(true);
		}catch(Exception $ex){
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Promotional send email action.
	 */
	public static function promotional_send_mail_action(){
		try{
			if(!isset($_POST)){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}
			
			$status_selection = isset($_POST['status_selection'])?$_POST['status_selection']:'' ;
			$mail_sent = false;
			if(isset($_GET['import'])){
				$email_ids = get_option('bm_stored_csv_email_ids');
				if(empty($email_ids)){
					throw new Exception( __( 'Invalid file', 'blindmatrix' ) );
				}
				
				foreach($email_ids as $email_id){
					$email_address = $object->get_user_email();
					$email_subject = get_option('bm_hub_promotional_email_subject','Promotional Email');
					$email_message = get_option('bm_hub_promotional_email_message');
					if(!$email_message){
						continue;
					}
					
					$recipients    = !empty(get_option('bm_hub_promotional_recipients')) ? explode(',',get_option('bm_hub_promotional_recipients')):array();
					$to_addresses  = implode(',',array_merge(array($email_address),$recipients));
					$mail_sent = wp_mail($to_addresses,$email_subject,$email_message);
				}
			}else{
			$user_lists_ids = bm_hub_get_user_lists_ids($status_selection);
			if(!is_array($user_lists_ids) || empty($user_lists_ids)){
				throw new Exception( __( 'No Data Found', 'blindmatrix' ) );
			}
						
			foreach($user_lists_ids as $user_lists_id){
				$object        = new BM_Users_List_Object($user_lists_id);
				if(!is_object($object)){
					continue;
				}
				
				$email_address = $object->get_user_email();
				$email_subject = get_option('bm_hub_promotional_email_subject','Promotional Email');
				$email_message = get_option('bm_hub_promotional_email_message','Hi,<br/><br/>Current Plugin status is <b>{status}</b>.<br/><br/>Thanks');
				$email_message = str_replace('{status}',bm_display_post_status($object->get_post_status()),$email_message);
				$recipients    = !empty(get_option('bm_hub_promotional_recipients')) ? explode(',',get_option('bm_hub_promotional_recipients')):array();
				$to_addresses  = implode(',',array_merge(array($email_address),$recipients));
				$mail_sent = wp_mail($to_addresses,$email_subject,$email_message);
			  }
			} 
			
			if(!$mail_sent){
				throw new Exception( __( 'Email Not Sent', 'blindmatrix' ) );
			}
			
			wp_send_json_success(array('url' =>admin_url( 'admin.php?page=bm_email_template&tab=promotional&section=settings') ));
		}catch(Exception $ex){
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	/**
	 * Appointment send mail action.
	 */
	public static function appointment_send_mail_action(){
		try{
			if(!isset($_POST) || !isset($_POST['api_name'],$_POST['post_id'])){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}

			$post_id = absint($_POST['post_id']);			
			$post_object = new BM_Users_List_Object($post_id);
			if(!is_object($post_object)){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}
			
			$email_address     = $post_object->get_user_email();
			if(!$email_address){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}

			$name = isset($_POST['api_name']) ? $_POST['api_name']:'';
			if(!$name){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}
			
			$encoded_string = base64_encode(json_encode(array('name' => $name,'id'=>$post_id,'sec' => time())));
			if(!$encoded_string){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}
						
			update_post_meta($post_id,'appointment_activation_key',$encoded_string);
			
			$email_subject = get_option('bm_hub_appointment_email_subject','BlindMatrix Appointment Activation Key');
			$email_message = get_option('bm_hub_appointment_email_message','Hi{username},<br/><br/>Activation Key for the BlindMatrix Plugin is {activation_key}. <br/><br/>Thanks');
			$from_name     = isset($user_data['from_name']) ? $user_data['from_name']:'';
			$email_message = str_replace(array('{activation_key}','{username}'),array('<b>'.$encoded_string.'</b>','<b>'.$from_name.'</b>'),$email_message);
			$recipients    = !empty(get_option('bm_hub_appointment_recipients')) ? explode(',',get_option('bm_hub_appointment_recipients')):array();
			$to_addresses  = implode(',',array_merge(array($email_address),$recipients));
			$success       = wp_mail($to_addresses,$email_subject,$email_message);
			if(!$success){
				throw new Exception( __( 'Email Not Sent', 'blindmatrix' ) );
			}

			wp_send_json_success(true);
		}catch(Exception $ex){
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
	
	/**
	 * Userslist import action.
	 */
	public static function userslist_import_action(){
		try{
			if(!isset($_POST)){
				throw new Exception( __( 'Invalid Data', 'blindmatrix' ) );
			}
			
			$mail_sent = false;
			$stored_csv_userslist_data = get_option('bm_stored_csv_userslist_data');
			if(empty($stored_csv_userslist_data)){
				throw new Exception( __( 'Invalid file', 'blindmatrix' ) );
			}
				
			foreach($stored_csv_userslist_data as $value){
					$user_id = isset($value[2]) && !empty($value[2]) ? $value[2]:'';
					$user_name = isset($value[3]) && !empty($value[3]) ? $value[3]:'';
					$user_email = isset($value[4]) && !empty($value[4]) ? $value[4]:'';
					$serialized_data = serialize(
						array(
							'userid'       => $user_id,
							'from_name'    => $user_name,
							'from_address' => $user_email
						)
					);
					$post_status = isset($value[9]) && !empty($value[9]) ? $value[9]:'';
					$date_created = isset($value[12]) && !empty($value[12]) ? $value[12]:'';
					$post_args = array(
						'post_status' => $post_status,
						'post_date_gmt' => $date_created,
						'post_date' => $date_created,
					);
					
					$meta_args = array(
						'url_info' => isset($value[1]) && !empty($value[1]) ? $value[1]:'',
						'user_info' => $serialized_data,
						'ip_address' => isset($value[5]) && !empty($value[5]) ? $value[5]:'',
						'plugin_activated_date' => isset($value[6]) && !empty($value[6]) ? $value[6]:'',
						'premium_activated_date' => isset($value[7]) && !empty($value[7]) ? $value[7]:'',
						'plugin_expired_date' => isset($value[8]) && !empty($value[8]) ? $value[8]:'',
						'plugin_status' => isset($value[10]) && !empty($value[10]) ? $value[10]:'',
						'activation_key' => isset($value[11]) && !empty($value[11]) ? $value[11]:'',
					);
					
					$object = new BM_Users_List_Object();
					$id = $object->create($post_args,$meta_args);
			}

			wp_send_json_success(array('url' =>admin_url( 'admin.php?page=users_list_table') ));
		}catch(Exception $ex){
			wp_send_json_error( array( 'error' => $ex->getMessage() ) );
		}
	}
}

BM_Admin_Ajax::init();
