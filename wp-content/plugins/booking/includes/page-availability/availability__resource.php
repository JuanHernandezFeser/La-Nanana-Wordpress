<?php
/**
 * @version     1.0
 * @package     Booking Calendar
 * @category    Resource Availability - define "unavailable" "date_status" in "wp_booking_dates_props" table.
 * @author      wpdevelop
 *
 * @web-site    https://wpbookingcalendar.com/
 * @email       info@wpbookingcalendar.com
 * @modified    2022-11-23
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/**
 * Get unavailable dates for the booking resource. Based on 'resource availability' - new Availability UI.
 *
 * @param int $resource_id
 *
 * @return array|object|stdClass[]|null
 */
function wpbc_resource__get_unavailable_dates( $resource_id = 1 ){

	$resource_id = intval( $resource_id );
	if ( empty( $resource_id ) ) {
		$resource_id = 1;
	}

	$resource_availability =  wpbc_availability__get_dates_status__sql(    array(
											  'calendar_date' => 'CURDATE',  	                            // 'CURDATE' | 'ALL'
											  'prop_name'     => 'date_status',                             // 'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
											  'prop_value'    => 'unavailable',                             // 'unavailable', 'available', 'pending', 'approved'
											  'resource_id'   => intval( $resource_id )                     // int or dcv
										) );

	$unavailable__dates_y_m_d__arr = array();

	foreach ( $resource_availability as $unavailable_db_obj ) {
		list( $date_y_m_d, $time_his ) = explode( ' ', $unavailable_db_obj->calendar_date );
		$unavailable__dates_y_m_d__arr[] = $date_y_m_d;
	}

	return  $unavailable__dates_y_m_d__arr;
}


/**
 *  Get unavailable dates for array of booking resources
 *
 * @param array $resource_id_arr            // [1, 13, 8, 6, 7]
 * @param mixed $search_dates               // 'CURDATE' | 'ALL' |  [ "2023-08-03" , "2023-08-04" , "2023-08-05" ]
 *
 * @return array   - array of unavailable dates:       array(   resource_A_ID => array( DATES_Y_M_D, .... ),
																resource_B_ID => array( DATES_Y_M_D, .... ), ...
											Example:
													   array(    1 => array( "2023-08-03" , "2023-08-04" ),
																 8 => array( "2023-08-03" ), ...
 */
function wpbc_for_resources_arr__get_unavailable_dates( $resource_id_arr, $search_dates = 'CURDATE' ) {                 //FixIn: 9.7.3.11

	// Booking resources -----------------------------------------------------------------------------------------------
	$resource_id_csd  = implode( ',', $resource_id_arr );       //  '9,13,5,6,7,1,2,10,11,12,3,4,8'

	// Dates -----------------------------------------------------------------------------------------------------------
	if ( is_array( $search_dates ) ) {                          // Array  of dates: ["2023-08-03"]

		foreach ( $search_dates as $date_ymd ) {
			$search_dates_arr[] = $date_ymd . ' 00:00:00';
		}
		$search_dates_formated = implode( ',', $search_dates_arr );

	} else {                                                    // Probably 'CURDATE' | 'ALL'

		$search_dates_formated = ( in_array( $search_dates, array( 'ALL', 'CURDATE' ) ) )
									? $search_dates
									: 'CURDATE' ;
	}

	// Unavailable objects ---------------------------------------------------------------------------------------------
	$resource_availability = wpbc_availability__get_dates_status__sql( array(
		'calendar_date' => $search_dates_formated,                              // 'CURDATE' | 'ALL'
		'prop_name'     => 'date_status',                                       // 'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
		'prop_value'    => 'unavailable',                                       // 'unavailable', 'available', 'pending', 'approved'
		'resource_id'   => $resource_id_csd                                     // int or dcv
	) );

	// Formatted array  of unavailable booking resource dates ----------------------------------------------------------
	$resource_unavailable_dates_arr = array();
	foreach ( $resource_availability as $resource_unavailable_obj ) {

		if ( ! isset( $resource_unavailable_dates_arr[ $resource_unavailable_obj->resource_id ] ) ) {
			$resource_unavailable_dates_arr[ $resource_unavailable_obj->resource_id ] = array();
		}
		$calendar_date = explode( ' ', $resource_unavailable_obj->calendar_date );
		$resource_unavailable_dates_arr[ $resource_unavailable_obj->resource_id ][] = $calendar_date[0];
	}

	return $resource_unavailable_dates_arr;
}


/**
 * Get dates status of specific booking resource
 *
 * @param array $params     = array(
						                  'prop_name'    => 'date_status',                            // 'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
						                  'prop_value'   => 'unavailable',                            // 'unavailable', 'available', 'pending', 'approved'
										  'resource_id' => 1                                          // int or dcv
									)
 *
 * booking date property can be relative to:  	prop_name =  'date_status', 'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
 *
 * 											    prop_name =  'date_status'		->	 	prop_value =  'unavailable', 'available', 'pending', 'approved'
 *
 * @return array of DB objects          array(
												 0 = {stdClass} {
																  ->booking_dates_prop_id = "1"
																  ->resource_id = "4"
																  ->calendar_date = "2023-04-04 00:00:00"
																  ->prop_name = "date_status"
																  ->prop_value = "unavailable"
																}
										, ... )
 *
 *  Example:
			  wpbc_availability__get_dates_status__sql(    array(
						  'calendar_date' => 'CURDATE',                                 // 'CURDATE' | 'ALL' | '2023-07-15 00:00:00,2023-07-21 00:00:00'
		                  'prop_name'     => 'date_status',                             // 'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
		                  'prop_value'    => 'unavailable',                             // 'unavailable', 'available', 'pending', 'approved'
						  'resource_id'   => 1                                          // int or dcv
					) );
*/
function wpbc_availability__get_dates_status__sql( $params ){

	// CACHE - GET    ::    check if such request was cached and get it  -----------------------------------------------    //FixIn: 9.7.3.14
	$params_for_cache_key = $params;
	if ( isset( $params_for_cache_key['resource_id'] ) ) {
		$params_for_cache_key['resource_id'] = (string) $params_for_cache_key['resource_id'];
	}
	$params_for_cache = maybe_serialize( $params_for_cache_key );
	$cache_result = wpbc_cache__get( 'wpbc_availability__get_dates_status__sql', $params_for_cache );
	if ( ! is_null( $cache_result ) ) {
		return $cache_result;
	}
	// -----------------------------------------------------------------------------------------------------------------


	$defaults = array(
						  'calendar_date' => 'CURDATE',                                 // 'CURDATE' | 'ALL'  | '2023-07-15 00:00:00,2023-07-21 00:00:00'           //FixIn: 9.7.3.11
		                  'prop_name'     => 'date_status',                             // 'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
		                  'prop_value'    => 'unavailable',                             // 'unavailable', 'available', 'pending', 'approved'
						  'resource_id'   => 1                                          // int or dcv
					);
	$params   = wp_parse_args( $params, $defaults );

	// S a n i t i z e
	$params_rules  = array(
							'calendar_date' => array( 'validate' => 's',            'default' => 'CURDATE' ) ,          // array( 'validate' => array(  'CURDATE', 'ALL' ),  'default' => 'CURDATE' ) ,
							'prop_name'     => array( 'validate' => 's',            'default' => 'date_status' ) ,
							'prop_value'    => array( 'validate' => 's',            'default' => 'unavailable' ) ,
							'resource_id'   => array( 'validate' => 'digit_or_csd', 'default' => 1 )
						);
	$params = wpbc_sanitize_params_in_arr( $params, $params_rules );
	/**

		$params['resource_id'] = ( '' != $params['resource_id'] ) ? wpbc_sanitize_digit_or_csd( $params['resource_id'] ) : 1;
		$params['prop_name']   = wpbc_sanitize_text( $params['prop_name'] );
		$params['prop_value']  = wpbc_sanitize_text( $params['prop_value'] );
	*/


	// S Q L
	global $wpdb;
	$sql      = array();
	$sql_args = array();


	$sql['start_select'] = "SELECT * ";                                     // "SELECT DISTINCT calendar_date ";
	$sql['from']         = "FROM {$wpdb->prefix}booking_dates_props ";

	// W H E R E
	$sql['where']  = 'WHERE ( 1 = 1 ) ';
	$sql['where'] .= " AND resource_id IN ( {$params['resource_id']} ) ";

	if ( '' != $params['calendar_date'] ) {

		//FixIn: 9.7.3.11
		if ( 'ALL' == $params['calendar_date'] ) {
																					// All  dates
		} else if ( 'CURDATE' == $params['calendar_date'] ) {

			$sql['where'] .= " AND calendar_date >= CURDATE() ";                    // Current dates

		} else {                                                                    // Specific Date(s)

			if ( false === strpos( $params['calendar_date'], ',' ) ) {	                    // One date:                -  '2023-07-15 00:00:00'

				$sql['where'] .= " AND ( calendar_date = %s ) ";
				$sql_args[] = $params['calendar_date'];
			} else {	                                                                    // Comma seperated dates:   -  '2023-07-15 00:00:00, 2023-07-16 00:00:00'

				$dates_arr     = explode( ',', $params['calendar_date'] );
				$in_search_arr = array();
				foreach ( $dates_arr as $date_ymd ) {
					$in_search_arr[] = '%s';
					$sql_args[]      = $date_ymd;
				}
				$in_search_dates_csv = implode( ',', $in_search_arr );
				$sql['where']        .= " AND calendar_date IN ( {$in_search_dates_csv} ) ";
			}
		}
	}

	if ( '' != $params['prop_name'] ) {
		$sql['where'] .= " AND ( prop_name =  %s ) ";
		$sql_args[] = $params['prop_name'];
	}

	if ( '' != $params['prop_value'] ) {
		$sql['where'] .= " AND ( prop_value =  %s ) ";
		$sql_args[] = $params['prop_value'];
	}

	// O R D E R
    $sql['order'] = " ORDER BY calendar_date";

	// L I M I T
	$sql['limit'] = '';
	/**
	 * $sql['limit'] = " LIMIT %d, %d ";
		$sql_args[] = ( $params['page_num'] - 1 ) * $params['page_items_count'];
		$sql_args[] = $params['page_items_count'];
	*/



	/**
	 * Good Practice: https://blog.ircmaxell.com/2017/10/disclosure-wordpress-wpdb-sql-injection-technical.html
	 *
			$sql['where'] .= "( bk.form LIKE %s ) ";
			$sql_args[] = '%' . $wpdb->esc_like( $params['keyword'] ) . '%';

			if ( is_numeric( $params['keyword'] ) ) {
				$sql['where'] .= " OR ( bk.booking_id = %d ) ";
				$sql_args[] =  intval( $params['keyword'] );
			}
	 *
	 */
    $sql_prepared = $wpdb->prepare(
									  $sql['start_select']
									. $sql['from']
									. $sql['where']
									. $sql['order']
									. $sql['limit']
								, $sql_args
                        );

    $bookings_sql_obj = $wpdb->get_results($sql_prepared);

	// CACHE - SAVE ::  ------------------------------------------------------------------------------------------------
	$cache_result = wpbc_cache__save( 'wpbc_availability__get_dates_status__sql', $params_for_cache, $bookings_sql_obj );

	return $bookings_sql_obj;
	/*
				"CREATE TABLE {$wpdb->prefix}booking_dates_props (
	             booking_dates_prop_id bigint(20) unsigned NOT NULL auto_increment,
	             resource_id bigint(10) NOT NULL default 1,
	             calendar_date datetime NOT NULL default '0000-00-00 00:00:00',
	             prop_name varchar(200) NOT NULL default '',
	             prop_value text,
	             PRIMARY KEY  (booking_dates_prop_id)
	            )";
	*/
}


/**
 * Update dates status
 *
 * @param $params       array(
						  'resource_id'     => 1,                   //  int
						  'prop_name'       => 'date_status',       // 											  'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
						  'prop_value'      => 'unavailable',       // 'available', 'unavailable'				, 'pending', 'approved'
						  'dates_selection' => ''   	            // '2023-04-04 | 2023-04-07'

					)
 *
 * @return bool|int - return  how many DB rows affected -how many dates processed
 */
function wpbc_availability__update_dates_status__sql( $params ){

	$defaults = array(
						  'resource_id'     => 1,                   //  int
						  'prop_name'       => 'date_status',       // 											  'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
						  'prop_value'      => 'unavailable',       // 'available', 'unavailable'				, 'pending', 'approved'
						  'dates_selection' => ''   	            // '2023-04-04 | 2023-04-07'

					);
	$params   = wp_parse_args( $params, $defaults );

	// S a n i t i z e
	$params_rules  = array(
							'dates_selection' => array( 'validate' => 's',            'default' => '' ) ,
							'prop_name'       => array( 'validate' => 's',            'default' => 'date_status' ) ,
							'prop_value'      => array( 'validate' => 's',            'default' => 'unavailable' ) ,
							'resource_id'     => array( 'validate' => 'digit_or_csd', 'default' => 1 )
						);
	$params = wpbc_sanitize_params_in_arr( $params, $params_rules );


	if ( false !== strpos( $params['dates_selection'], '~' ) ) {                                        // Range Days

		$dates_arr = wpbc_get_dates_arr__from_dates_range( array(
			'dates_separator' => ' ~ ',                         //  ' ~ '
			'dates'           => $params['dates_selection'],    // '2023-04-04 ~ 2023-04-07'
		) );

	} else {                                                                                            // Multiple Days
		$dates_arr = wpbc_get_dates_arr__from_dates_comma_separated( array(
																			'dates_separator' => ', ',                         //  ', '
																			'dates'           => $params['dates_selection'],    // '2023-04-04 ~ 2023-04-07'
																		) );
	}

	$row_number = false;

	if ( ! empty( $dates_arr ) ) {

		global $wpdb;

		// Remove dates ------------------------------------------------------------------------------------------------

		//      "DELETE FROM {$wpdb->prefix}booking_dates_props WHERE resource_id = %d AND prop_name = %s AND ( calendar_date = %s OR calendar_date = %s )"
		$sql = "DELETE FROM {$wpdb->prefix}booking_dates_props WHERE resource_id = %d AND prop_name = %s ";

		$sql_args   = array();
		$sql_args[] = $params['resource_id'];
		$sql_args[] = $params['prop_name'];

		$sub_sql = array();
		foreach ( $dates_arr as $calendar_date ) {

			$sub_sql[]  = 'calendar_date = %s';
			$sql_args[] = $calendar_date . ' 00:00:00';
		}
		if ( count( $sub_sql ) > 0 ) {
			$sql     .= ' AND ( ';
			$sub_sql = implode( ' OR ', $sub_sql );
			$sql     .= $sub_sql;
			$sql     .= " )";
		}

		$sql_prepared = $wpdb->prepare( $sql, $sql_args );
		$row_number   = $wpdb->query( $sql_prepared );


		// If we set the dates as 'available', then we skip INSERT into DB, because previously we have already deleted all 'unavailable' dates status for such dates
		if ( ( 'date_status' == $params['prop_name'] ) && ( 'available' == $params['prop_value'] ) ) {
			return $row_number;
		}

		// Insert new dates ------------------------------------------------------------------------------------------------
		$sql_args = array();

		$sql = "INSERT INTO {$wpdb->prefix}booking_dates_props ( resource_id, calendar_date, prop_name, prop_value) VALUES ";

		$sub_sql = array();
		foreach ( $dates_arr as $calendar_date ) {

			$sub_sql[]  = "( %d, %s, %s, %s )";
			$sql_args[] = $params['resource_id'];
			$sql_args[] = $calendar_date . ' 00:00:00';
			$sql_args[] = $params['prop_name'];
			$sql_args[] = $params['prop_value'];
		}

		$sub_sql = implode( ',', $sub_sql );
		$sql     .= $sub_sql;

		$sql_prepared = $wpdb->prepare( $sql, $sql_args );
		$row_number   = $wpdb->query( $sql_prepared );
	}

	return $row_number;
}


/**
 * Delete the dates status from DB - erase availability
 *
 * @param array $params       array(
						  'resource_id'     => 1,                   //  int
						  'prop_name'       => 'date_status',       // 											  'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
						  'prop_value'      => 'unavailable',       // 'available', 'unavailable'				, 'pending', 'approved'
						  'dates_selection' => 'all'   	            // '2023-04-04 ~ 2023-04-07'

					)
 *
 * @return bool|int     -   count of deleted rows   or false if operation was not applied
 *
 * Examples:
 *          wpbc_availability__delete_dates_status__sql(array( 'resource_id' => 1 ));
 *          wpbc_availability__delete_dates_status__sql(array( 'resource_id' => 1, 'prop_name' => 'date_status' ))
 *          wpbc_availability__delete_dates_status__sql(array( 'resource_id' => 1, 'prop_name' => 'date_status', 'dates_selection' => 'all' ))
 *          wpbc_availability__delete_dates_status__sql(array( 'resource_id' => 1, 'prop_name' => 'date_status', 'dates_selection' => '2023-04-04 ~ 2023-04-07' ))
 *          wpbc_availability__delete_dates_status__sql(array( 'resource_id' => 1, 'prop_name' => 'date_status', 'dates_selection' => '2023-04-04, 2023-04-07, 2023-04-05' ))
 */
function wpbc_availability__delete_dates_status__sql( $params ){

	$defaults = array(
						  'resource_id'     => 1,                   //  int
						  'prop_name'       => 'date_status',       // 											  'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
						  'prop_value'      => 'unavailable',       // 'available', 'unavailable'				, 'pending', 'approved'
						  'dates_selection' => 'all'   	            // '2023-04-04 ~ 2023-04-07'

					);
	$params   = wp_parse_args( $params, $defaults );

	// S a n i t i z e
	$params_rules  = array(
							'dates_selection' => array( 'validate' => 's',            'default' => '' ) ,
							'prop_name'       => array( 'validate' => 's',            'default' => 'date_status' ) ,
							'prop_value'      => array( 'validate' => 's',            'default' => 'unavailable' ) ,
							'resource_id'     => array( 'validate' => 'digit_or_csd', 'default' => 1 )
						);
	$params = wpbc_sanitize_params_in_arr( $params, $params_rules );



	if ( 'all' === $params['dates_selection'] ) {                                                                       // All Dates

		$dates_arr = array();

	} elseif ( false !== strpos( $params['dates_selection'], '~' ) ) {                                                  // Range Days

		$dates_arr = wpbc_get_dates_arr__from_dates_range( array(
			'dates_separator' => ' ~ ',                         //  ' ~ '
			'dates'           => $params['dates_selection'],    // '2023-04-04 ~ 2023-04-07'
		) );

	} else {                                                                                                            // Multiple Days
		$dates_arr = wpbc_get_dates_arr__from_dates_comma_separated( array(
																			'dates_separator' => ', ',                         //  ', '
																			'dates'           => $params['dates_selection'],    // '2023-04-04, 2023-04-07, 2023-04-05'
																		) );
	}

	$row_number = false;

	global $wpdb;

	// Remove dates ------------------------------------------------------------------------------------------------

	//      "DELETE FROM {$wpdb->prefix}booking_dates_props WHERE resource_id = %d AND prop_name = %s AND ( calendar_date = %s OR calendar_date = %s )"
	$sql = "DELETE FROM {$wpdb->prefix}booking_dates_props WHERE resource_id = %d AND prop_name = %s ";

	$sql_args   = array();
	$sql_args[] = $params['resource_id'];
	$sql_args[] = $params['prop_name'];

	// If we transfer here dates,  instead of 'all',  then  we delete only  such  specific dates status from  DB
	if ( ! empty( $dates_arr ) ) {
		$sub_sql = array();
		foreach ( $dates_arr as $calendar_date ) {

			$sub_sql[]  = 'calendar_date = %s';
			$sql_args[] = $calendar_date . ' 00:00:00';
		}
		if ( count( $sub_sql ) > 0 ) {
			$sql     .= ' AND ( ';
			$sub_sql = implode( ' OR ', $sub_sql );
			$sql     .= $sub_sql;
			$sql     .= " )";
		}
	}

	$sql_prepared = $wpdb->prepare( $sql, $sql_args );
	$row_number   = $wpdb->query( $sql_prepared );

	return $row_number;
}
