<?php
/**
 * Post Object 
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post Object class.
 */
class BM_Post_Object{
	/**
	 * Id.
	 *
	 * @var int
	 */
	protected $id;
	/**
	 * Post status.
	 *
	 * @var string
	 */
	protected $post_status;

	/**
	 * Post parent.
	 *
	 * @var string
	 */
	protected $post_parent = '';

	/**
	 * Created date.
	 *
	 * @var string
	 */
	protected $created_date;

	/**
	 * Modified date.
	 *
	 * @var string
	 */
	protected $modified_date;

	/**
	 * Get ID.
	 * 
	 * @return int
	 */
	public function get_id(){
		return $this->id;
	}

	/**
	 * Get post type.
	 * 
	 * @return string
	 */
	public function get_type() { 
		return $this->post_type;
	}

	/**
	 * Get post status.
	 * 
	 * @return string
	 */
	public function get_post_status(){
		return $this->post_status;
	}

	/**
	 * Get post parent.
	 * 
	 * @return string
	 */
	public function get_post_parent(){
		return $this->post_parent;
	}
	
	/**
	 * Get created date.
	 * 
	 * @return string
	 */
	public function get_created_date(){
		return $this->post_date;
	}
	
	/**
	 * Get created date.
	 * 
	 * @return string
	 */
	public function get_created_date_gmt(){
		return $this->post_date_gmt;
	}
	
	/**
	 * Get modified date.
	 * 
	 * @return string
	 */
	public function get_modified_date(){
		return $this->modified_date;
	}

	/**
	 * Constructor.
	 */
	public function __construct($id = 0){
		$this->id = $id;
		$this->read($id);
	}

	/**
	 * Read the object.
	 */
	public function read($id){
		if(!$id || !absint($id)){
			return;
		}
		
		$post_object = get_post($id);
		if(!is_object($post_object)){
			return;
		}
		
		$post_args  = array(
			'post_date'     => $post_object->post_date,
			'post_date_gmt' => $post_object->post_date_gmt,
			'post_type'     => $post_object->post_type,
			'post_title'    => $post_object->post_title,
			'post_status'   => $post_object->post_status,
			'post_author'   => $post_object->post_author,
			'post_parent'   => $post_object->post_parent,
		);
		
		foreach($post_args as $key => $value){
			$this->$key = $value;
		}
		
		$meta_data = get_metadata('post',$id);
		foreach($meta_data as $key => $value){
			$value = isset($value[0]) ? $value[0]:'';
			$this->$key = $value;
		}
	}
	
	/**
	 * Create an object.
	 */
	public function create($args = array(),$meta_args = array()){
		$default_args  = array(
			'post_date'     => current_time( 'mysql' ),
			'post_date_gmt' => current_time( 'mysql',1),
			'post_type'     => $this->get_type(),
			'post_title'    => 'post',
			'post_status'   => $this->get_post_status(),
			'post_author'   => get_current_user_id(),
			'post_parent'   => $this->get_post_parent(),
		);

		$args = array_merge($default_args,$args);
		
		$post_id = wp_insert_post(
			apply_filters(
				'bm_create_userslist_data',
				$args
			),
			true
		);

		$post = get_post($post_id );
		if ( $post_id && ! is_wp_error( $post_id ) && is_object($post) && !empty($meta_args) && is_array($meta_args)) {
			$this->update_post_meta( $post,$meta_args);
		}

		return $post_id;
	}

	/**
	 * Update an object.
	 */
	public function update($post_id,$post_args = array(),$meta_args = array()){
		$default_args  = array(
			'post_type'     => $this->get_type(),
			'post_status'   => $this->get_post_status(),
			'post_parent'   => $this->get_post_parent(),
			'post_modified' => current_time( 'mysql' ),
			'post_modified_gmt' => current_time( 'mysql', 1 ),
			'ID'                => $post_id,
		);

		$post_args = array_merge($default_args,$post_args);

		wp_update_post( $post_args);

		$post = get_post($post_id );
		if ( $post_id && ! is_wp_error( $post_id ) && is_object($post) && !empty($meta_args) && is_array($meta_args)) {
			$this->update_post_meta( $post,$meta_args);
		}

		return $post_id;
	}

	/**
	 * Update post meta.
	 */
	public function update_post_meta($post,$meta_args){
		foreach($meta_args as $meta_key => $meta_value){
			update_post_meta($post->ID,sanitize_key($meta_key),$meta_value);
		}
	}

}
