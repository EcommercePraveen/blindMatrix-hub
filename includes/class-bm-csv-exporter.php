<?php
/**
 * Handles CSV export.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * BM_CSV_Exporter Class.
 */
class BM_CSV_Exporter {
	/**
	 * Filename.
	 */
	public static $filename = 'userslist';
	/**
	 * Row data.
	 */
	public static $row_data = array();
	/**
	 * Column names.
	 */
	public static $column_names;
	/**
	 * The delimiter parameter sets the field delimiter (one character only).
	 */
	public static $delimiter = ',';

	/**
	 * Init.
	 */
	public static function init(){
		self::prepare_column_names();
		self::prepare_data_to_export();
		self::send_headers();
		self::send_content( chr( 239 ) . chr( 187 ) . chr( 191 ) . self::export_column_headers().self::get_csv_data() );
		die();
	}

	/**
	 * Prepare column names.
	 */
	public static function prepare_column_names(){
		self::$column_names = array(
				'id'            			=> __( 'Post Data', 'blindmatrix' ),
				'url_info'           		=> __( 'Url Name', 'blindmatrix' ),
				'user_id'       			=> __( 'User ID','blindmatrix' ),  
				'username'      			=> __( 'User Name', 'blindmatrix' ),
				'email'        			    => __( 'Email', 'blindmatrix' ),
				'ip_address'                => __( 'IP Address' ,'blindmatrix'),
				'plugin_activated_date'     => __( 'Trial Activation Date', 'blindmatrix' ),
				'premium_activation_date'   => __( 'Premium Activation Date', 'blindmatrix' ),
				'plugin_expired_date'       => __( 'Plugin Expired Date', 'blindmatrix' ),
				'status'        			=> __( 'Status Name', 'blindmatrix' ),
				'plugin_status'        		=> __( 'Plugin Status', 'blindmatrix' ),
				'activation_key'        	=> __( 'Activation Key', 'blindmatrix' ),
				'date_created_gmt'			=> __( 'Date Created', 'blindmatrix' ),
		);
	}
	
	/**
	 * Prepare data to export.
	 */
	public static function prepare_data_to_export(){
		$user_list_ids = bm_hub_get_user_lists_ids();
		foreach ( $user_list_ids as $user_list_id ) {
			$user_list_object = new BM_Users_List_Object($user_list_id);
			self::$row_data[] = array(
				'id'            			=> $user_list_object->get_id(),
				'url_info'           		=> $user_list_object->get_url_info(),
				'user_id'       			=> $user_list_object->get_user_id(),  
				'username'      			=> $user_list_object->get_user_name(),  
				'email'        			    => $user_list_object->get_user_email(),  
				'ip_address'                => $user_list_object->get_ip_address(),  
				'plugin_activated_date'     => $user_list_object->get_plugin_activated_date(),  
				'premium_activation_date'   => $user_list_object->get_premium_activated_date(),  
				'plugin_expired_date'       => $user_list_object->get_plugin_expired_date(),  
				'status'        			=> $user_list_object->get_post_status(),  
				'plugin_status'        		=> $user_list_object->get_plugin_status(),  
				'activation_key'        	=> $user_list_object->get_activation_key(),  
				'date_created_gmt'			=> $user_list_object->get_created_date_gmt(),  
			);
		}
	}
	
	/**
	 * Send Headers.
	 */
	public static function send_headers(){
		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . self::get_filename() );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
	}
	
	/**
	 * Get filename.
	 */
	public static function get_filename(){
		return sanitize_file_name( str_replace( '.csv', '', self::$filename ) . '.csv' );
	}
	
	/**
	 * Send Content.
	 */
	public static function send_content($csv_data){
		echo $csv_data;
	}
	
	/**
	 * Export column headers.
	 */
	public static function export_column_headers(){
		$buffer     = fopen( 'php://output', 'w' );
		ob_start();
		$export_row = array();
		foreach(self::$column_names as $column_name){
			$export_row[] = self::format_data( $column_name );
		}
		
		self::fputcsv( $buffer,$export_row);
		return ob_get_clean();
	}
	
	/**
	 * Get CSV data.
	 */
	public static function get_csv_data(){
		$buffer  = fopen( 'php://output', 'w' );
		ob_start();
		array_walk( self::$row_data, array( __CLASS__, 'export_row' ), $buffer );
		return ob_get_clean();
	}
	/**
	 * Export row.
	 */
	public static function export_row($row_data, $key, $buffer){
		$export_row = array();

		foreach ( self::$column_names as $column_id => $column_name ) {
			if ( isset( $row_data[ $column_id ] ) ) {
				$export_row[] = self::format_data( $row_data[ $column_id ] );
			} else {
				$export_row[] = '';
			}
		}

		self::fputcsv( $buffer, $export_row );
	}
	
	/**
	 * Format data.
	 */
	public static function format_data( $data ) {
		if ( ! is_scalar( $data ) ) {
			if ( is_a( $data, 'WC_Datetime' ) ) {
				$data = $data->date( 'Y-m-d G:i:s' );
			} else {
				$data = ''; 
			}
		} elseif ( is_bool( $data ) ) {
			$data = $data ? 1 : 0;
		}

		$use_mb = function_exists( 'mb_convert_encoding' );

		if ( $use_mb ) {
			$encoding = mb_detect_encoding( $data, 'UTF-8, ISO-8859-1', true );
			$data     = 'UTF-8' === $encoding ? $data : utf8_encode( $data );
		}

		return self::escape_data( $data );
	}
	
	/**
	 * Escape data.
	 */
	public static function escape_data( $data ) {
		$active_content_triggers = array( '=', '+', '-', '@' );
		if ( in_array( mb_substr( $data, 0, 1 ), $active_content_triggers, true ) ) {
			$data = "'" . $data;
		}

		return $data;
	}
	
	/**
	 * Write to the CSV file, ensuring escaping works across versions of PHP.
	 */
	public static function fputcsv( $buffer, $export_row ) {
		if ( version_compare( PHP_VERSION, '5.5.4', '<' ) ) {
			ob_start();
			$temp = fopen( 'php://output', 'w' ); 
    		fputcsv( $temp, $export_row, self::$delimiter, '"' ); 
			fclose( $temp );
			$row = ob_get_clean();
			$row = str_replace( '\\"', '\\""', $row );
			fwrite( $buffer, $row ); 
		} else {
			fputcsv( $buffer, $export_row, self::$delimiter, '"', "\0" ); 
		}
	}
}

BM_CSV_Exporter::init();
