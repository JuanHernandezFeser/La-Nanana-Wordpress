<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4


class WPBC_RESOURCE_SUPPORT {

	public $resource_id_arr;
	public $as_single_resource;

	private $bookings_sql_obj_arr;

	function __construct( $params =array() ) {

		$defaults = array(
		                  'resource_id' => 1                        // CSD | D
						, 'as_single_resource'  => false   		    // If true,  then  skip child booking resources
					);
		$params   = wp_parse_args( $params, $defaults );

		$this->resource_id_arr    = $params['resource_id'];
		$this->as_single_resource = $params['as_single_resource'];

		if ( ! class_exists( 'wpdev_bk_personal' ) ) {
			$this->bookings_sql_obj_arr = $this->get_free_resource();
		} else {
			$this->bookings_sql_obj_arr = $this->get__sql__booking_resources__arr();
		}

		$this->reindex_booking_resources__by__resource_id();

		$this->update__capacity__in_booking_resources();
	}


	/**
	 * Get default data for Free version
	 * @return array
	 */
	private function get_free_resource() {

		$free_resource_obj = new stdClass();
		$free_resource_obj->booking_type_id = 1;
		$free_resource_obj->default_form    = 'standard';
		$free_resource_obj->title     = __( 'Default', 'booking' );
		$free_resource_obj->users     = 1;
		$free_resource_obj->import    = '';
		$free_resource_obj->export    = '';
		$free_resource_obj->cost      = 0;
		$free_resource_obj->prioritet = 1;
		$free_resource_obj->parent    = 0;
		$free_resource_obj->visitors  = 1;
		$free_resource_obj->capacity  = 1;

		return array( $free_resource_obj );
	}


	/**
	 * Get booking resources from the DB,  based on request  parameters
	 *
	 * @return array|mixed|object|stdClass[]|null
	 */
	function get__sql__booking_resources__arr() {

		// CACHE - GET    ::    check if such request was cached and get it  -----------------------------------------------    //FixIn: 9.7.3.14
		$params_for_cache = maybe_serialize( $this->resource_id_arr );
		$cache_result     = wpbc_cache__get( 'WPBC_RESOURCE_SUPPORT__sql', $params_for_cache );
		if ( ! is_null( $cache_result ) ) {
			return $cache_result;
		}
		// -----------------------------------------------------------------------------------------------------------------

		global $wpdb;

		// Init params
		$params = array(
			'resource_id' => implode( ',', $this->resource_id_arr )
		);
		// S a n i t i z e
		$params_rules = array(
								'resource_id' => array( 'validate' => 'digit_or_csd', 'default' => 1 )
							);
		$params = wpbc_sanitize_params_in_arr( $params, $params_rules );

		// S Q L
		$sql      = array();
		$sql_args = array();

		$sql['start_select'] = "SELECT * ";                                     // "SELECT DISTINCT calendar_date ";
		$sql['from']         = "FROM {$wpdb->prefix}bookingtypes ";

		// W H E R E
		$sql['where'] = 'WHERE ( 1 = 1 ) ';
		$sql['where'] .= " AND ( ";
		$sql['where'] .= "      booking_type_id IN ( {$params['resource_id']} ) ";
		if ( ( class_exists( 'wpdev_bk_biz_l' ) ) && ( ! $this->as_single_resource ) ) {
			$sql['where'] .= "   OR parent IN ( {$params['resource_id']} ) ";
		}
		$sql['where'] .= " ) ";

		/**
		 *
			if ( '' != $params['prop_value'] ) {
				$sql['where'] .= " AND ( prop_value =  %s ) ";
				$sql_args[] = $params['prop_value'];
			}
		 */

		// O R D E R
		if ( class_exists( 'wpdev_bk_biz_l' ) ) {
			$sql['order'] = " ORDER BY parent, prioritet, title, booking_type_id";
		} else {
			$sql['order'] = " ORDER BY title, booking_type_id";
		}

		// L I M I T
		$sql['limit'] = '';                                                    /**
		 * $sql['limit'] = " LIMIT %d, %d ";
		 * $sql_args[] = ( $params['page_num'] - 1 ) * $params['page_items_count'];
		 * $sql_args[] = $params['page_items_count'];
		 */


		/**
		 * Good Practice: https://blog.ircmaxell.com/2017/10/disclosure-wordpress-wpdb-sql-injection-technical.html
		 *
		 * $sql['where'] .= "( bk.form LIKE %s ) ";
		 * $sql_args[] = '%' . $wpdb->esc_like( $params['keyword'] ) . '%';
		 *
		 * if ( is_numeric( $params['keyword'] ) ) {
		 * $sql['where'] .= " OR ( bk.booking_id = %d ) ";
		 * $sql_args[] =  intval( $params['keyword'] );
		 * }
		 *
		 */
		if ( empty( $sql_args ) ) {
			$sql_prepared =                 $sql['start_select'] . $sql['from'] . $sql['where'] . $sql['order'] . $sql['limit'];
		} else {
			$sql_prepared = $wpdb->prepare( $sql['start_select'] . $sql['from'] . $sql['where'] . $sql['order'] . $sql['limit']     , $sql_args );
		}

		$bookings_sql_obj = $wpdb->get_results( $sql_prepared );

		// CACHE - SAVE ::  ------------------------------------------------------------------------------------------------
		$cache_result = wpbc_cache__save( 'WPBC_RESOURCE_SUPPORT__sql', $params_for_cache, $bookings_sql_obj );

		return $bookings_sql_obj;
	}


		/**
		 * Update capacity  property  to  all booking resources
		 * @return void
		 */
		private function update__capacity__in_booking_resources(){

			foreach ( $this->bookings_sql_obj_arr as $key => $resource_obj ) {

				$is_this_child = ( ( isset( $resource_obj->parent ) ) && ( 0 != $resource_obj->parent ) ) ? true : false;
				$resource_id   = $resource_obj->booking_type_id;
				$capacity_count = 1;

				if ( ( ! $is_this_child ) && ( class_exists( 'wpdev_bk_biz_l' ) ) ) {
					foreach ( $this->bookings_sql_obj_arr as $child_key => $child_resource_obj ) {
						if ( ( isset( $resource_obj->parent ) ) && ( $resource_id == $child_resource_obj->parent ) ) {
							$capacity_count++;
						}
					}
				}

				$this->bookings_sql_obj_arr[ $key ]->capacity = $capacity_count;
			}
		}


	/**
	 * Reindex booking resource - set  keys as resource_id
	 * @return array
	 */
	private function reindex_booking_resources__by__resource_id (){

		$resource__arr = array();
		foreach ( $this->bookings_sql_obj_arr as $sort_key => $resource_obj ) {
			$resource_obj->sort_key=$sort_key;
			$resource__arr[ $resource_obj->booking_type_id ] = $resource_obj;
		}
		$this->bookings_sql_obj_arr = $resource__arr;

		return $this->bookings_sql_obj_arr;
	}


	/**
	 * Get booking resource object  with  all  properties from DB
	 *
	 * @param int $resource_id
	 *
	 * @return false|mixed|stdClass             if not fount then false
	 */
	function get__booking_resource_obj( $resource_id ) {

		if ( isset( $this->bookings_sql_obj_arr[ $resource_id ] ) ) {
			return $this->bookings_sql_obj_arr[ $resource_id ];
		} else {
			return false;
		}
	}


	/**
	 * Get title of booking resource
	 *
	 * @param int $resource_id
	 *
	 * @return string
	 */
	function get_resource_title( $resource_id ) {

		$resource_title = '';

		$resource_obj = $this->get__booking_resource_obj( $resource_id );

		if ( false !== $resource_obj ) {
			$resource_title = $resource_obj->title;

			$resource_title = apply_bk_filter( 'wpdev_check_for_active_language', $resource_title );
		}

		return $resource_title;
	}


	/**
	 * Get ID of all  booking resources         [ 2, 10, 11, 12 ]
	 *
	 * @return array        // [ 2, 10, 11, 12 ]
	 */
	function get_resource_id_arr() {

		$resource_id_arr = array_keys( $this->bookings_sql_obj_arr );
		return $resource_id_arr;
	}


	/**
	 * Get cost of all  booking resources       [ 'resource_id' => cost, ... ]
	 *
	 * @return array        // [ 2=>20, 10>100, 12=>101, 13=>120 ]
	 */
	function get_resources_base_cost_arr() {

		$resource__arr = array();
		foreach ( $this->bookings_sql_obj_arr as $key => $resource_obj ) {
			$resource__arr[ $resource_obj->booking_type_id ] = isset( $resource_obj->cost ) ? $resource_obj->cost : 0;
		}
		return $resource__arr;
	}

}





// <editor-fold     defaultstate="collapsed"                        desc="  ==  Support for booking resource IDs  ==  "  >

	// -----------------------------------------------------------------------------------------------------------------
	// Support for booking resource IDs
	// -----------------------------------------------------------------------------------------------------------------

	/**
	 *  Convert   $resource_id   and   $additional_bk_types   to  one array
	 *
	 *  in BL this $params['additional_bk_types']   is INT  or  CSD  ,in low version it ARRAY
	 *
	 * @param mixed $resource_id            - array, CSD, int
	 * @param mixed $additional_bk_types    - array, CSD, int
	 *
	 * @return array                     unique array of booking resource        array( 1 )  |  array( 3, 9, 10, 11 )
	 */
	function wpbc_get_unique_array_of_resources_id( $resource_id = '', $additional_bk_types = '' ){

		// in BL this       $params['additional_bk_types']   is INT  or  CSD
		// in low version   $params['additional_bk_types']   is ARRAY

		// If we do not pass any  parameters, then  use default booking resource  with  ID = 1
		if ( ( empty( $resource_id ) ) && ( empty( $additional_bk_types ) ) ) {
			$resource_id = '1';
		}

		// Get strings from parameters
		if ( is_array( $resource_id ) ) {
			$resource_id = implode( ',', $resource_id );
		} else {
			$resource_id = (string) $resource_id;
		}

		if ( is_array( $additional_bk_types ) ) {
			$additional_bk_types = implode( ',', $additional_bk_types );
		} else {
			$additional_bk_types = (string) $additional_bk_types;
		}

		$resource_id_csd = $resource_id . ',' . $additional_bk_types;           // Concat 2 lists
		$resource_id_csd = str_replace( ';', ',', $resource_id_csd );           // Check for possible mistakes in separators

		$resource_id__arr = explode( ',', $resource_id_csd );                   // Get one array  of booking resources
		$resource_id__arr = array_unique( $resource_id__arr );                  // Removes duplicate values from an array
		$resource_id__arr = array_filter( $resource_id__arr );                  // All entries of array equal to FALSE (0, '', '0' ) will be removed.

		// Sanitize ID values
		foreach ( $resource_id__arr as $resource_key => $resource_value ) {
			$resource_id__arr[ $resource_key ] = intval( $resource_value );
		}

		return $resource_id__arr;
	}

// </editor-fold>

