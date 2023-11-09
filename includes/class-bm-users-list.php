<?php
/**
 * Users List Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Users List class.
 */
class BM_Users_List_Object extends BM_Post_Object {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'bm_users_list';

	/**
	 * Post status.
	 *
	 * @var string
	 */
	protected $post_status = 'not_activated';
	
	/**
	 * URL Info.
	 *
	 * @var string
	 */
	protected $url_info;
	/**
	 * IP Address.
	 *
	 * @var string
	 */
	protected $ip_address;
	/**
	 * User Info.
	 *
	 * @var string
	 */
	protected $user_info;
	/**
	 * Plugin Activated Date.
	 *
	 * @var string
	 */
	protected $plugin_activated_date;
	/**
	 * Plugin Status.
	 *
	 * @var string
	 */
	protected $plugin_status;
	/**
	 * Premium Activated Date.
	 *
	 * @var string
	 */
	protected $premium_activated_date;
	/**
	 * Activation key.
	 *
	 * @var string
	 */
	protected $activation_key;
	
	/**
	 * Plugin Expired date.
	 *
	 * @var string
	 */
	protected $plugin_expired_date;
	/**
	 * Premium user info.
	 *
	 * @var string
	 */
	protected $premium_user_info;
	/**
	 * Premium ip address.
	 *
	 * @var string
	 */
	protected $premium_ip_address;
	/**
	 * Premium Site URL.
	 *
	 * @var string
	 */
	protected $premium_site_url; 
	/**
	 * Appointment activation key.
	 *
	 * @var string
	 */
	protected $appointment_activation_key;
	/**
	 * Appointment status.
	 *
	 * @var string
	 */
	protected $appointment_status;
	/**
	 * Appointment date.
	 *
	 * @var string
	 */
	protected $appointment_date; 
    
    /**
	 * Reports.
	 *
	 * @var string
	 */
	protected $reports; 

	/**
	 * Stores userslist data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'url_info'               	 => '',
		'ip_address'             	 => '',
		'user_info'              	 => '',
		'plugin_activated_date'  	 => '',
		'plugin_status'           	 => '',
		'premium_activated_date' 	 => '',
		'activation_key'         	 => '',
		'plugin_expired_date'    	 => '',
		'premium_site_url'       	 => '',
		'premium_user_info'      	 => '',
		'premium_ip_address'         => '',
		'appointment_activation_key' => '',
		'appointment_status' 		 => '',
		'appointment_date'           => '',
        'reports'                    => '',
	);
	
	/**
	 * Get appointment api name.
	 */
	public function get_appointment_api_name(){
		$data = $this->get_appointment_activation_key() ? json_decode(base64_decode($this->get_appointment_activation_key())):'';
		return isset($data->name) ? $data->name:'';
	} 
	
	/**
	 * Get api name.
	 */
	public function get_api_name(){
		$data = $this->get_activation_key() ? json_decode(base64_decode($this->get_activation_key())):'';
		return isset($data->name) ? $data->name:'';
	}
	
	/**
	 * Get url type.
	 */
	public function get_url_type(){
		$data = $this->get_activation_key() ? json_decode(base64_decode($this->get_activation_key())):'';
		return isset($data->server) ? $data->server:'';
	}
	
	/**
	 * Get user id.
	 */
	public function get_user_id(){
		$user_data = unserialize(unserialize($this->get_user_info()));
		return isset($user_data['userid']) ? $user_data['userid']:'';
	}
	
	/**
	 * Get user name.
	 */
	public function get_user_name(){
		$user_data = unserialize(unserialize($this->get_user_info()));
		return isset($user_data['from_name']) ? $user_data['from_name']:'';
	}
	
	/**
	 * Get user email.
	 */
	public function get_user_email(){
		$user_data = unserialize(unserialize($this->get_user_info()));
		return isset($user_data['from_address']) ? $user_data['from_address']:'';
	}

	/**
	 * Get url info.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_url_info( $context = 'view' ) {
		return $this->url_info;
	}

	/**
	 * Get IP address.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_ip_address( $context = 'view' ) {
		return $this->ip_address;
	}

	/**
	 * Get user info.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_user_info( $context = 'view' ) {
		return $this->user_info;
	}

	/**
	 * Get plugin activated date.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_plugin_activated_date( $context = 'view' ) {
		return $this->plugin_activated_date;
	}

	/**
	 * Get plugin status.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_plugin_status( $context = 'view' ) {
		return $this->plugin_status;
	}
	
	/**
	 * Get premium activated date.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_premium_activated_date($context = 'view'){
		return $this->premium_activated_date;
	}
	
	/**
	 * Get activation key.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_activation_key(){
		return $this->activation_key;
	}
	
	/**
	 * Get plugin expired date.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_plugin_expired_date(){
		return $this->plugin_expired_date;
	}
	
	/**
	 * Get premium site URL.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_premium_site_url(){
		return $this->premium_site_url;
	}
	
	/**
	 * Get premium user info.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_premium_user_info(){
		return $this->premium_user_info;
	}
	
	/**
	 * Get premium ip address.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_premium_ip_address(){
		return $this->premium_ip_address;
	}
	
	/**
	 * Get appointment activation key.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_appointment_activation_key(){
		return $this->appointment_activation_key;
	}
	
	/**
	 * Get appointment status.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_appointment_status(){
		return $this->appointment_status;
	}
	
	/**
	 * Get appointment date.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_appointment_date(){
		return $this->appointment_date;
	}
    
    /**
	 * Get reports.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 */
    public function get_reports(){
    	return $this->reports;
    }

	/**
	 * Set url info.
	 *
	 * @param string $value Value to set.
	 */
	public function set_url_info( $value ) {
		$this->url_info =$value;
	}

	/**
	 * Set IP address.
	 *
	 * @param string $value Value to set.
	 */
	public function set_ip_address( $value ) {
		$this->ip_address = $value;
	}

	/**
	 * Set user info.
	 *
	 * @param string $value Value to set.
	 */
	public function set_user_info( $value ) {
		$this->user_info = $value;
	}

	/**
	 * Set plugin activated date.
	 *
	 * @param string $value Value to set.
	 */
	public function set_plugin_activated_date( $value ) {
		$this->plugin_activated_date = $value;
	}

	/**
	 * Set plugin status.
	 *
	 * @param string $value Value to set.
	 */
	public function set_plugin_status($value){
		$this->plugin_status = $value;
	}
	
	/**
	 * Set premium activated date.
	 *
	 * @param string $value Value to set.
	 */
	public function set_premium_activated_date($value){
		$this->premium_activated_date = $value;
	}
	
	/**
	 * Set activation key.
	 *
	 * @param string $value Value to set.
	 */
	public function set_activation_key($value){
		$this->activation_key = $value;
	}
	
	/**
	 * Set plugin expired date.
	 *
	 * @param string $value Value to set.
	 */
	public function set_plugin_expired_date($value){
		$this->plugin_expired_date = $value;
	}
	
	/**
	 * Set premium site URL.
	 *
	 * @param string $value Value to set.
	 */
	public function set_premium_site_url($value){
		$this->premium_site_url = $value;
	}
	
	/**
	 * Set premium user info.
	 *
	 * @param string $value Value to set.
	 */
	public function set_premium_user_info($value){
		$this->premium_user_info = $value;
	}
	
	/**
	 * Set premium ip address.
	 *
	 * @param string $value Value to set.
	 */
	public function set_premium_ip_address($value){
		$this->premium_ip_address = $value;
	}
	/**
	 * Set appointment activation key.
	 *
	 * @param string $value Value to set.
	 */
	public function set_appointment_activation_key($value){
		$this->appointment_activation_key = $value;
	}
	
	/**
	 * Set appointment status.
	 *
	 * @param string $value Value to set.
	 */
	public function set_appointment_status($value){
		$this->appointment_status = $value;
	}
	
	/**
	 * Set appointment date.
	 *
	 * @param string $value Value to set.
	 */
	public function set_appointment_date($value){
		$this->appointment_date = $value;
	}
    
    /**
	 * Set reports.
	 *
	 * @param string $value Value to set.
	 */
    public function set_reports(){
    	$this->reports = $value;
    }
}