<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Dev API for integration Booking Calendar with  third party
 * @category API
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2017-06-24
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

//FixIn: 8.0

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Add New Booking 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Add New Booking
 * 
 * @param array $booking_dates			// array( '2017-06-24',  '2017-06-24', '2017-06-25' );
 * @param array $booking_data			// array(
 *													'secondname' => array( 'value' =>  'Rika'			, 'type' => 'text' )
 *												  , 'name'       => 'Jo'															// 'text' field type, if in such format
 *												  , 'rangetime'  => array( 'value' =>  '14:00 - 16:00', 'type' => 'select-one' )
 *												  , 'email'	     => array( 'value' =>  'rika@cost.com', 'type' => 'email' )
 *												)
 * @param int $resource_id				// Optional. Default: 1
 * @param type $params					// Optional. Default:  array( 					  
 *																		  'is_send_emeils'		 => 0
 *																		, 'booking_form_type'	 => ''
 *																		, 'wpdev_active_locale'  => 'en_US'
 *																		, 'is_show_payment_form' => 0
 *																		, 'is_edit_booking'		 => false  | array( 'booking_id' => 75, 'booking_type' => 1 )
 *                                                                      , 'save_booking_even_if_unavailable' => 0               // 0 | 1 - if 1 then  save booking without checking if this date available. Usually  save to  main  booking resource
 *                                                                      , 'is_use_booking_recurrent_time'    => false
 *																)
 * @return int|WP_Error - booking ID        or WP_Error
 * 
 * 
 *********************************************************************************************************************** 
 * Notes!
 *        If you need to book for specific time, then  its have to  be in appropriate field(s) at $booking_data - booking form. In $booking_dates times have been sliced.
 *	      It does not check about booked | available dates in calendar with  capacity > 1 !!!!
 *		  If the single booking resource booked for specific dates and settings have activated "Checking to prevent double booking, during submitting booking" then  system  just  DIE
 ***********************************************************************************************************************
 * 
 * Examples:
 * 
 - AddSimple
			$booking = array(
							  'dates'	 => array( '2017-06-24', '2017-06-24', '2017-06-25', '2017-06-26' )
						    , 'data'	 => array(
												  'secondname'   => array( 'value' => 'Rika', 'type' => 'text' )
												, 'name'		 => 'John'
												, 'email'		 => array( 'value' => 'rika@cost.com', 'type' => 'email' )
											)
			);
			$booking_id = wpbc_api_booking_add_new( $booking[ 'dates' ], $booking[ 'data' ] );

 - Resource
			$booking = array(
							  'dates'	 => array( '2017-06-24', '2017-06-24', '2017-06-25', '2017-06-26' )
						    , 'data'	 => array(
												  'secondname' => array( 'value' => 'Rika', 'type' => 'text' )
												, 'name'		 => 'JoNNNNNNNNNN'
												, 'rangetime'	 => array( 'value' => '14:00 - 16:00', 'type' => 'select-one' )
												, 'email'		 => array( 'value' => 'rika@cost.com', 'type' => 'email' )
											)
							, 'resource_id' => 3
				 
			);
			$booking_id = wpbc_api_booking_add_new( $booking[ 'dates' ], $booking[ 'data' ], $booking[ 'resource_id' ]  );


 - Edit:
			$booking = array(
							  'dates'	 => array( '2017-06-24', '2017-06-24', '2017-06-25', '2017-06-28' )
						    , 'data'	 => array(
												  'secondname' => array( 'value' => 'Rika', 'type' => 'text' )
												, 'name'		 => 'BoBy'
												, 'rangetime'	 => array( 'value' => '14:00 - 16:00', 'type' => 'select-one' )
												, 'email'		 => array( 'value' => 'rika@cost.com', 'type' => 'email' )
											)
							, 'resource_id' => 3
							, 'params'      => array(  'is_edit_booking' => array( 'booking_id' => 79, 'booking_type' => 3 ) )
				 
			);
			$booking_id = wpbc_api_booking_add_new( $booking[ 'dates' ], $booking[ 'data' ], $booking[ 'resource_id' ], $booking[ 'params' ]  );
 * 
 */
function wpbc_api_booking_add_new( $booking_dates, $booking_data, $resource_id = 1, $params = array() ) {
	
	/*
	// Dates in format: 'Y-m-d'
	$booking_dates = array( '2017-06-24',  '2017-06-24', '2017-06-25' );
	
	// Booking Form params
	$booking_data  = array(
							  'secondname' => array( 'value' =>  'Rika'			, 'type' => 'text' )
							, 'rangetime'  => array( 'value' =>  '14:00 - 16:00', 'type' => 'select-one' )
							, 'email'	   => array( 'value' =>  'rika@cost.com', 'type' => 'email' )
					);
	// Booking resource ID
	$resource_id = 1;
	*/
	
	// Other params
	$defaults = array(
					  'is_send_emeils'		            => 0
					, 'booking_form_type'	            => ''				// custom_form_name
					, 'wpdev_active_locale'             => 'en_US'			// locale
					, 'is_show_payment_form'            => 0				// Parameters for adding booking in the HTML:
					, 'is_edit_booking'		            => false			// array( 'booking_id' => 75, 'booking_type' => 1 )				// Update Booking params
					, 'is_use_booking_recurrent_time'   => ( 'On' === get_bk_option( 'booking_recurrent_time' ) )
				);
    $params = wp_parse_args( $params, $defaults );
	
	
	// booking resource ID
	$resource_id = intval( $resource_id );
	$resource_id = ( empty( $resource_id ) ? 1 :  $resource_id  );
	$params[ 'bktype' ] = $resource_id;
	
	
	// Dates ///////////////////////////////////////////////////////////////////////////////////////////////////////////
	$booking_dates = array_map( 'strtotime', $booking_dates );							// Array ( [0] => 1498262400 [1] => 1498348800 )
	sort( $booking_dates );																// Sort
	$booking_dates = array_unique( $booking_dates );									// Remove Duplicates
	$dates_formats = array_fill( 0, count( $booking_dates ), "d.m.Y" );					// Array ( [0] => d.m.Y [1] => d.m.Y )
	$booking_dates = array_map( 'date_i18n', $dates_formats , $booking_dates );			// Array ( [0] => 24.06.2017 [1] => 25.06.2017 )
	$booking_dates = implode(', ', $booking_dates);										// 24.06.2017, 25.06.2017
	$params[ 'dates' ]  = $booking_dates;
	
				
	// Booking Form ////////////////////////////////////////////////////////////////////////////////////////////////////
	$booking_form = array();
	foreach ( $booking_data as $field_name => $field_params ) {
		
		if ( is_array( $field_params ) ) {
			
			$booking_form_field = array(  $field_params['type'], $field_name . $resource_id, $field_params['value'] );
		} else { // value just string
			$booking_form_field = array(  'text',				 $field_name . $resource_id, $field_params );			
		}		
		$booking_form_field[ 0 ] = str_replace( array( '^', '~' ), array( 'curret', 'tilde' ), $booking_form_field[ 0 ] );	// replace to  temp symbols
		$booking_form_field[ 1 ] = str_replace( array( '^', '~' ), array( 'curret', 'tilde' ), $booking_form_field[ 1 ] );
		$booking_form_field[ 2 ] = str_replace( array( '^', '~' ), array( 'curret', 'tilde' ), $booking_form_field[ 2 ] );
		
		$booking_form_field   = implode( '^' , $booking_form_field );
		$booking_form		[]= $booking_form_field;
	}
	$booking_form = implode( '~' , $booking_form );
	$params[ 'form' ] = $booking_form;


	$resource_id  = $params['bktype'];


	$booking_hash = '';
	if ( ! empty( $params['is_edit_booking'] ) ) {

		$hash__arr = wpbc_hash__get_booking_hash__resource_id( $params['is_edit_booking']['booking_id'] );              // Get new booking hash

		if ( ! empty( $hash__arr ) ) {
			list( $booking_hash, $resource_id ) = $hash__arr;
		}
	}


	// Create a new booking
	$request_save_params = array(
								 'resource_id'         => $resource_id,					                                // 2
								 'dates_ddmmyy_csv'    => $params[ 'dates' ],					                    // '04.10.2023, 05.10.2023, 06.10.2023'
								 'form_data'           => $params[ 'form' ],					                    // 'text^cost_hint2^150.00฿~select-multiple^rangetime2[]^14:00...'
								 'booking_hash'        => $booking_hash,										                // 'sdfsf34534rf'
								 'custom_form'         => $params['booking_form_type'],								// 'custom_form_name'
								 'is_emails_send'      => $params['is_send_emeils'],			                    // 0 | 1
								 'is_show_payment_form' => intval( $params['is_show_payment_form'] ),                // 0 | 1
									// 'request_uri'          => $_SERVER['HTTP_REFERER']
									// 'user_id' 			 => wpbc_get_current_user_id()
								 'sync_gid'             => ( ( ! empty( $params['sync_gid'] ) ) ? $params['sync_gid'] : '' ),
								 'is_approve_booking'   => ( ( ! empty( $params['is_approve_booking'] ) ) ? $params['is_approve_booking'] : 0 ),		// Auto  approve booking ??
								 'save_booking_even_if_unavailable' => ( ( ! empty( $params['save_booking_even_if_unavailable'] ) ) ? $params['save_booking_even_if_unavailable'] : 0 ),		// Force saving booking without checking
								 'is_use_booking_recurrent_time'    => intval( $params['is_use_booking_recurrent_time'] )
							);

	$booking_save_arr = wpbc_booking_save( $request_save_params );
	if ( 'ok' === $booking_save_arr['ajx_data']['status'] ) {												// Everything Cool :) - booking has been duplicated
		$booking_id = $booking_save_arr['booking_id'];
	} else {																								// Error
		$booking_id = 0;
		// Error message: 		$booking_save_arr['ajx_data']['ajx_after_action_message'];
		return new WP_Error( 'wpbc_api_booking_add_new__error', $booking_save_arr['ajx_data']['ajx_after_action_message'] );
	}

	return $booking_id;
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Is Date Booked ?
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/**
 * Check if dates available   in specific resource
 * 
 * @param array  $booking_dates			// [ "2023-09-27 23:30:01","2023-09-28 00:00:00","2023-09-29 00:59:52" ]        Range List of Dates in MySQL format,  like:  array( '2017-06-23 14:00:01', '2017-06-24 00:00:00', '2017-06-25', '2017-06-26 12:00:02' );
 * @param int    $resource_id			// 9                                                                            Optional. Default: 1
 * @param array  $params				// [ 'is_use_booking_recurrent_time' => false ]                                 Optional. Default:  array( )  -- For future improvement
 *
 * @return bool                         // true | false
 * 
 * Examples:
 *
 *			$booking = array(
 *							  'dates'	 => array( '2017-06-23 14:00:01', '2017-06-24 00:00:00', '2017-06-25', '2017-06-26 12:00:02' )
 *							, 'resource_id' => 1
 *                          , 'params'      => array( 'is_use_booking_recurrent_time' => false )
 *			);
 *			$result = wpbc_api_is_dates_booked( $booking[ 'dates' ], $booking[ 'resource_id' ], $booking[ 'params' ] );
 */
function wpbc_api_is_dates_booked( $booking_dates, $resource_id = 1, $params = array() ) {

	$defaults = array(
	                  'is_use_booking_recurrent_time' => ( 'On' === get_bk_option( 'booking_recurrent_time' ) )
				);
	$params   = wp_parse_args( $params, $defaults );


	// Convert:     ["2023-09-27 23:30:01","2023-09-28 00:00:00","2023-09-29 00:59:52"]  ->  [ "2023-10-18", "2023-10-25", "2023-11-25" ]
	$dates_only_sql_arr = array_map( function ( $value ) {
															$value_arr = explode( ' ', $value );
															return $value_arr[0];
														}, $booking_dates );

	// Convert:     ["2023-09-27 23:30:01","2023-09-28 00:00:00","2023-09-29 00:59:52"]  ->  [ 36000, 39600 ]
	$time_as_seconds_arr = array( $booking_dates[0], $booking_dates[ ( count( $booking_dates ) - 1 ) ], );
	$time_as_seconds_arr = array_map( function ( $value ) {
															$value_arr = explode( ' ', $value );
															$time_sec = wpbc_transform__24_hours_his__in__seconds( $value_arr[1] );
															return $time_sec;
														}, $time_as_seconds_arr );


	/**
	 *  Get slots [] where we can save booking    =    [    'resources_in_dates' => [     2023-10-18 = [ 2, 12, 10, 11 ]
	 *                                                                                    2023-10-19 = [ 2, 12, 10, 11 ]
	 *                                                                                    2023-10-20 = [ 2, 12, 10, 11 ]
	 *                                                                              ],
	 *                                                       'time_to_book'      => [  "14:00:01"  ,  "12:00:01"  ],
	 *                                                       'result'            => 'ok'
	 *                                                       'main__resource_id' => 2
	 *                                                 ]
	 *                                            OR
	 *                                                 [ 'result' => 'error', 'message' => 'Booking can not be saved ...' ]
	 */
	$where_to_save_booking = wpbc__where_to_save_booking( array(
											 'resource_id'            => $resource_id,                              // 2
											 'skip_booking_id'        => '',                                        // '',            |  125 if edit booking
											 'dates_only_sql_arr'     => $dates_only_sql_arr,                       // [ "2023-10-18", "2023-10-25", "2023-11-25" ]
											 'time_as_seconds_arr'    => $time_as_seconds_arr,                      // [ 36000, 39600 ]
											 'how_many_items_to_book' => 1,                                         // 1
											 'request_uri'            => ( ( ( defined( 'DOING_AJAX' ) ) && ( DOING_AJAX ) ) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'] ),     //  front-end: $_SERVER['REQUEST_URI'] | ajax: $_SERVER['HTTP_REFERER']                      // It different in Ajax requests. It's used for change-over days to detect for exception at specific pages,         // 'http://beta/resource-id2/'
											 'as_single_resource'     => true,                                       // false
									  'is_use_booking_recurrent_time' => $params['is_use_booking_recurrent_time']
									) );

	if ( 'ok' != $where_to_save_booking['result'] ) {
		$is_dates_times_unavailable = true;
		/**
		 *
				$ajx_data_arr['status']                          = 'error';
				$ajx_data_arr['status_error']                    = 'booking_can_not_save';
				$ajx_data_arr['ajx_after_action_message']        = $where_to_save_booking['message'];
				$ajx_data_arr['ajx_after_action_message_status'] = 'warning';
				return array( 'ajx_data' => $ajx_data_arr );
		*/
	} else {
		$is_dates_times_unavailable = false;
	}

	return $is_dates_times_unavailable;
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Get Bookings Array	-	[Listing]
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
	 * Get bookings array from  Booking Calendar
 * 
 * @param aray $params
 * @return array	
			(   [bookings] => Array (			
                    [2661] => stdClass Object (
                            [booking_id] => 2661
                            [trash] => 0
                            [sync_gid] => 5t3ogfsb3tqj09po7fiou6hh60@google.com
                            [is_new] => 1
                            [status] => 
                            [sort_date] => 2017-08-07 20:00:01
                            [modification_date] => 2017-07-08 11:54:03
                            [form] => text^name4^Event (timezone Pacific GMT-07:00)~....
                            [hash] => 69afc11e2ce86044dd55fbddf582ce66
                            [booking_type] => 4
                            [remark] => 
                            [cost] => 51.98
                            [pay_status] => 149950764311
                            [pay_request] => 0
                            [dates] => Array (
                                    [0] => stdClass Object (
                                            [booking_id] => 2661
                                            [booking_date] => 2017-08-07 20:00:01
                                            [approved] => 0
                                            [type_id] =>  )
                                    [1] => stdClass Object (
                                            [booking_id] => 2661
                                            [booking_date] => 2017-08-08 00:00:00
                                            [approved] => 0
                                            [type_id] => 
                                        ) 
								)
                            [dates_short] => Array (
                                    [0] => 2017-08-07 20:00:01
                                    [1] => -
                                    [2] => 2017-08-08 00:00:00 )
                            [form_show] => 'First Name: John....'
                            [form_data] => Array (
                                    [email] => ics@beta
                                    [name] => Event (timezone Pacific GMT-07:00)
                                    [secondname] => 
                                    [visitors] => 1
                                    [coupon] => 
                                    [_all_] => Array (
                                            [name4] => Event (timezone Pacific GMT-07:00)
                                            [details4] => 8/7/2017 1:00pm  TO   3:30pm  8/8/2017  (GMT-07:00) Pacific Time
                                            [email4] => ics@beta
                                            [rangetime4] => 20:00 - 00:00
                                            [sync_gid4] => 5t3ogfsb3tqj09po7fiou6hh60@google.com
                                        )
                                    [_all_fields_] => Array (
                                            [name] => Event (timezone Pacific GMT-07:00)
                                            [details] => 8/7/2017 1:00pm  TO   3:30pm  8/8/2017  (GMT-07:00) Pacific Time
                                            [email] => ics@beta
                                            [rangetime] => 20:00 - 00:00
                                            [sync_gid] => 5t3ogfsb3tqj09po7fiou6hh60@google.com
                                            [booking_resource_id] => 4
                                            [resource_id] => 4
                                            [type_id] => 4
                                            [type] => 4
                                            [resource] => 4
                                            [booking_id] => 2661
                                            [resource_title] => stdClass Object (
                                                    [booking_type_id] => 4
                                                    [title] => Apartment#3
                                                    [users] => 1
                                                    [import] => some_email@group.calendar.google.com
                                                    [cost] => 25.99
                                                    [default_form] => standard
                                                    [prioritet] => 40
                                                    [parent] => 0
                                                    [visitors] => 1
                                                    [id] => 4
                                                    [count] => 1
                                                    [ID] => 4
                                                )
                                        )
                                    [rangetime] => 20:00 - 00:00
                                )
                            [dates_short_id] => Array (
                                    [0] => 
                                    [1] => 
                                    [2] => 
                                )
                        )
                    ....
                )
            [resources] => Array (
                    [4] => stdClass Object
                        (
                            [booking_type_id] => 4
                            [title] => Apartment#3
                            [users] => 1
                            [import] => some_email@group.calendar.google.com
                            [cost] => 25.99
                            [default_form] => standard
                            [prioritet] => 40
                            [parent] => 0
                            [visitors] => 1
                            [id] => 4
                            [count] => 1
                            [ID] => 4
                        )
					....
                )
            [bookings_count] => 2
            [page_num] => 1
            [count_per_page] => 100000
        )
 *	
 */
function wpbc_api_get_bookings_arr( $params = array() ) {
	
	// Start Date of getting bookings
	$real_date = strtotime( 'now' );
	$wh_booking_date = date_i18n( "Y-m-d", $real_date );							// '2012-12-01';

	// End date of getting bookings
	$real_date = strtotime( '+1 year' );
	$wh_booking_date2 = date_i18n( "Y-m-d", $real_date );							// '2013-02-31';                    
	
	// params
	$defaults = array(
		  'wh_booking_type' => '1'
		, 'wh_approved' => ''
		, 'wh_booking_id' => ''
		, 'wh_is_new' => ''
		, 'wh_pay_status' => 'all'
		, 'wh_keyword' => ''
		, 'wh_booking_date' => $wh_booking_date
		, 'wh_booking_date2' => $wh_booking_date2
		, 'wh_modification_date' => '3'
		, 'wh_modification_date2' => ''
		, 'wh_cost' => ''
		, 'wh_cost2' => ''
		, 'or_sort' => ''
		, 'page_num' => '1'
		, 'wh_trash' => ''                                                          // '' | trash | any                 //FixIn: 8.0.2.8
		, 'limit_hours' => '0,24'
		, 'only_booked_resources' => 0
		, 'page_items_count' => '100000'
	);
	$params = wp_parse_args( $params, $defaults );
		
	$bookings_arr = wpbc_get_bookings_objects( $params );   

	return $bookings_arr;
}

//FixIn: 8.7.6.4
/**
 * Get Booking Data as array of properties
 *
 * @param string $booking_id  - digit '11' or comma separated '11,19,12'
 *
 * @return array
 */
function wpbc_api_get_booking_by_id( $booking_id = '' ) {

	global $wpdb;
	$booking_id = wpbc_clean_digit_or_csd( $booking_id );

	$slct_sql         = "SELECT * FROM {$wpdb->prefix}booking as b left join {$wpdb->prefix}bookingdates as bd on (b.booking_id = bd.booking_id) WHERE b.booking_id IN (%s) LIMIT 0,1";
	$slct_sql         = $wpdb->prepare( $slct_sql, $booking_id );
	$slct_sql_results = $wpdb->get_results( $slct_sql, ARRAY_A );

	$data = array();

	if ( count( $slct_sql_results ) > 0 ) {
		$data           = $slct_sql_results[0];
		$formdata_array = explode( '~', $data['form'] );

		$formdata_array_count = count( $formdata_array );
		for ( $i = 0; $i < $formdata_array_count; $i ++ ) {

			if ( empty( $formdata_array[ $i ] ) ) {
				continue;
			}
			$elemnts                           = explode( '^', $formdata_array[ $i ] );
			$type                              = $elemnts[0];
			$element_name                      = $elemnts[1];
			$value                             = $elemnts[2];
			$value                             = nl2br( $value );
			$data['formdata'][ $element_name ] = $value;
		}
	}
	return $data;
}

//FixIn: 8.7.7.3
/**
 * Get booking form  fields in Booking Calendar Free version
 * @return array
 */
function wpbc_get_form_fields_free() {
	$obj = array();
	if ( class_exists( 'WPBC_Page_SettingsFormFieldsFree' ) ) {

		//FixIn: 8.7.8.7
		$form_free = new WPBC_Page_SettingsFormFieldsFree();
		$form_fields = $form_free->get_booking_form_structure_for_visual();
		foreach ( $form_fields as $field ) {
			//FixIn: 8.7.8.7
			if (    ( ! empty( $field['name'] ) )
			     && ( ! empty( $field['label'] ) )
		         && ( ! in_array( $field['type'], array(
														'captcha',
														'submit'
													) ) )
	        ) {
				$obj[ $field['name'] ] = $field['label'];
			}
		}
	}
	return $obj;
}


/**
 * DEPRECATED :: Hook action after creation  new booking
 * @param int $booking_id
 * @param int $resource_id
 * @param string $str_dates__dd_mm_yyyy    - "30.02.2014, 31.02.2014, 01.03.2014"
 * @param array  $times_array              - array($start_time, $end_time )
 * @param string $booking_form
	function your_cust_func_add_new_booking( $booking_id, $resource_id, $str_dates__dd_mm_yyyy, $times_array , $booking_form  ) {

	}
	add_action( 'show_payment_forms__for_ajax', 'your_cust_func_add_new_booking', 100, 5 );
 *
	Previously:  add_action( 'wpdev _new_booking', 'your_cust_func_add_new_booking', 100, 5 );
 */

/**
 *  NEW       :: Hook action after creation / edit bookings
 *
 * How to  use this hook?
 *
 * Add code similar  to this in your functions.php file in your theme,  or in some other php file:
 *
 * // Track adding new booking
 * //
 * // @param $params = array (
 *                           'str_dates__dd_mm_yyyy'   => '08.10.2023,09.10.2023,10.10.2023,11.10.2023',
 *                           'booking_id'              => 254,
 *                           'resource_id'             => 11,                      // child or parent or single
 *                           'initial_resource_id'     => 2,                       //          parent or single
 *                           'form_data'               => 'text^selected_short_dates_hint11^Sun...',
 *                           'times_array'             => array ( array ( '14', '00', '01' ), array( '12', '00', '02' ) ),
 *                           'is_edit_booking'         => 0,
 *                           'custom_form'             => '',
 *                           'is_duplicate_booking'    => 0,
 *                           'is_from_admin_panel'     => false,
 *                           'is_show_payment_form'    => 1
 *           )
 * function my_booking_tracking( $params ){
 *      // Your code here
 *      ?><!-- Google Code for Booking Conversion Page -->
 *      <script type="text/javascript">
 *           // Insert bellow your Google Conversion Code
 *      </script><?php
 * }
 * add_action( 'wpbc_track_new_booking', 'my_booking_tracking' );
 *
 *
 *
 * Useful hook  booking edit tracking
 *
 * Add code similar  to this in your functions.php file in your theme,  or in some other php file:
 *
* // Track edit existing booking
* //
* // @param $params = array (
* 				'str_dates__dd_mm_yyyy'   => '08.10.2023,09.10.2023,10.10.2023,11.10.2023',
* 				'booking_id'              => 254,
* 				'resource_id'             => 11,                      // child or parent or single
* 				'initial_resource_id'     => 2,                       //          parent or single
* 				'form_data'               => 'text^selected_short_dates_hint11^Sun...',
* 				'times_array'             => array ( array ( '14', '00', '01' ), array( '12', '00', '02' ) ),
* 				'is_edit_booking'         => 1,
* 				'custom_form'             => '',
* 				'is_duplicate_booking'    => 0,
* 				'is_from_admin_panel'     => false,
* 				'is_show_payment_form'    => 1
* 			  )
* function my_edit_booking_tracking( $params ){
*       // Your code here
*       ?><!-- Google Code for Booking Conversion Page -->
*       <script type="text/javascript">
*            // Insert bellow your Google Conversion Code
*       </script><?php
* }
* add_action( 'wpbc_track_edit_booking', 'my_edit_booking_tracking' );
*/

/**
 * Hook action after approving of booking:  do_action( 'wpbc_booking_approved' , $booking_id , $is_approved_dates );
 * @param int/string $booking_id            - can be '1' or 99  or comma separated ID of bookings: '10,22,45'
 * @param int/string $is_approved_dates     - '1' | '0' | 1 | 0      1 -approved, 0 - pending
	function your_cust_func_wpbc_booking_approved( $booking_id, $is_approved_dates  ) {

	}
	add_action( 'wpbc_booking_approved', 'your_cust_func_wpbc_booking_approved', 100, 2 );                                  //FixIn: 8.7.6.1
 */

/**
 * Hook action after trash of booking:
 * do_action( 'wpbc_booking_trash', $booking_id, $is_trash );                                						    //FixIn: 8.7.6.2
 */

/**
 * Hook action after delete of booking:
 * do_action( 'wpbc_booking_delete', $approved_id_str );															    //FixIn: 8.7.6.3
 */

/**
 * Hooks in new Booking Listing page for different actions:
 *                                                                                                                      //FixIn: 9.5.3.3
	do_action( 'wpbc_set_booking_locale', $params, $action_result );	    // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_set_booking_pending', $params, $action_result );	    // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_set_booking_approved' $params, $action_result );	    // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_move_booking_to_trash' $params, $action_result );	    // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_restore_booking_from_trash' $params, $action_result );	// where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_delete_booking_completely' $params, $action_result );	// where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_set_booking_as_read' $params, $action_result );	    // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_set_booking_as_unread' $params, $action_result );	    // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.

	do_action( 'wpbc_set_booking_note' $params, $action_result );	        // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_change_booking_resource' $params, $action_result );	// where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.

	do_action( 'wpbc_set_payment_status' $params, $action_result );	        // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_set_booking_cost' $params, $action_result );	        // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_send_payment_request' $params, $action_result );	    // where $params is array,  which  contain	$params['booking_id'], and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_import_google_calendar' $params, $action_result );	    // where $params is array and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.
	do_action( 'wpbc_export_csv' $params, $action_result );	                // where $params is array  and 	$action_result array and have $action_result['after_action_result'], which contains a boolean value of the result of an operation.

 */

/**
 *    Hook for adding new payment status(es) in Booking Listing  page:
 *
 *      apply_filters ('wpbc_filter_payment_status_list' , $payment_status_titles );
 *
 *    Use this code in your functions.php file in your theme to add your own Payment status for Booking Listing  page:
 *
 *      function my_wpbc_filter_payment_status_list( $payment_status_titles ) {
 *          $payment_status_titles[ 'invoice' ] = ' Invoice Payment';
 *          return  $payment_status_titles;
 *      }
 *      add_filter( 'wpbc_filter_payment_status_list', 'my_wpbc_filter_payment_status_list', 10, 1 );
 */