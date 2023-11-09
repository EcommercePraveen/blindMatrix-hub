<?php
/**
 * Premium Request List Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Premium Request List class.
 */
class BM_Premium_Request_List_Object extends BM_Post_Object {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'bm_premium_request';

	/**
	 * Post status.
	 *
	 * @var string
	 */
	protected $post_status = 'publish';
	
	/**
	 * Name.
	 *
	 * @var string
	 */
	protected $name;
	/**
	 * Email.
	 *
	 * @var string
	 */
	protected $email;
	/**
	 * Phone number.
	 *
	 * @var string
	 */
	protected $phone_number;
	/**
	 * Site URL.
	 *
	 * @var string
	 */
	protected $site_url;
	/**
	 * Company name.
	 *
	 * @var string
	 */
	protected $company_name;

	/**
	 * Stores userslist data.
	 *
	 * @var array
	 */
	protected $extra_data = array(
		'name'               => '',
		'email'              => '',
		'phone_number'       => '',
		'site_url'           => '',
		'company_name'  	 => '',
	);

	/**
	 * Get name.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_name( $context = 'view' ) {
		return $this->name;
	}

	/**
	 * Get email.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_email( $context = 'view' ) {
		return $this->email;
	}

	/**
	 * Get phone number.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_phone_number( $context = 'view' ) {
		return $this->phone_number;
	}

	/**
	 * Get site URL.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_site_url( $context = 'view' ) {
		return $this->site_url;
	}

	/**
	 * Get company name.
	 *
	 * @param  string $context What the value is for. Valid values are view and edit.
	 * @return string
	 */
	public function get_company_name( $context = 'view' ) {
		return $this->company_name;
	}

	/**
	 * Set name.
	 *
	 * @param string $value Value to set.
	 */
	public function set_name( $value ) {
		$this->name =$value;
	}

	/**
	 * Set email.
	 *
	 * @param string $value Value to set.
	 */
	public function set_email( $value ) {
		$this->email = $value;
	}

	/**
	 * Set phone number.
	 *
	 * @param string $value Value to set.
	 */
	public function set_phone_number( $value ) {
		$this->phone_number = $value;
	}

	/**
	 * Set site URL.
	 *
	 * @param string $value Value to set.
	 */
	public function set_site_url( $value ) {
		$this->site_url = $value;
	}

	/**
	 * Set company name.
	 *
	 * @param string $value Value to set.
	 */
	public function set_company_name( $value ) {
		$this->company_name = $value;
	}
}
