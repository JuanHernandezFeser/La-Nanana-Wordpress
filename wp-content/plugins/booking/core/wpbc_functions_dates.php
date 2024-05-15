<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

// ---------------------------------------------------------------------------------------------------------------------
// Localize dates
// ---------------------------------------------------------------------------------------------------------------------


/**
 * Return date / time in    'LOCAL_FORMAT'.
 *
 * @param string|int $date_str_ymdhis        Date to format.
 * @param string     $format                 Optional. Date/Time Format,  like 'Y-m-d H:i:s'
 * @param bool       $is_add_timezone_offset Optional. Timezone offset.
 *
 * @return string
 */
function wpbc_datetime_localized( $date_str_ymdhis, $format = '', $is_add_timezone_offset = false ) {

	if ( empty( $format ) ) {
		$format = sprintf( '%s %s', get_option( 'booking_date_format' ), get_option( 'booking_time_format' ) );
	}
	if ( empty( $format ) ) {
		$format = sprintf( '%s %s', get_option( 'date_format' ), get_option( 'time_format' ) );
	}

	$server_zone = date_default_timezone_get();                                                                         // If in 'Theme' or 'other plugin' set  default timezone other than UTC. Save it.
	if ( 'UTC' !== $server_zone ) {                                                                                     // Needed for WP date functions  - set timezone to  UTC
		@date_default_timezone_set( 'UTC' );
	}

	if ( is_string( $date_str_ymdhis ) ) {
		$date_str_ymdhis = strtotime( $date_str_ymdhis );
	}

	if ( $is_add_timezone_offset ) {
		$date_str_ymdhis += (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
	}

	$local_date = date_i18n( $format, $date_str_ymdhis );

	if ( 'UTC' !== $server_zone ) {                                                                                     // Back  to  previos state,  if it was changed.
		@date_default_timezone_set( $server_zone );
	}

	return $local_date;
}

	// -----------------------------------------------------------------------------------------------------------------

	//FixIn: 9.9.0.17
	/**
	 * Return date / time in    'LOCAL_FORMAT'    with  WordPress Timezone   offset   from WordPress  > Settings General page
	 *
	 * @param string|int $date_str_ymdhis        Date to format.
	 * @param string     $format                 Optional. Date/Time Format,  like 'Y-m-d H:i:s'
	 *
	 * @return string
	 */
	function wpbc_datetime_localized__use_wp_timezone( $date_str_ymdhis, $format = '') {

		$is_add_timezone_offset = true;

		$datetime_localized = wpbc_datetime_localized( $date_str_ymdhis, $format, $is_add_timezone_offset );

		return $datetime_localized;
	}

	/**
	 * Return date / time in    'LOCAL_FORMAT'    without  WordPress Timezone   offset       - NO TIMEZONE
	 *
	 * @param string|int $date_str_ymdhis        Date to format.
	 * @param string     $format                 Optional. Date/Time Format,  like 'Y-m-d H:i:s'
	 *
	 * @return string
	 */
	function wpbc_datetime_localized__no_wp_timezone( $date_str_ymdhis, $format = '') {

		$is_add_timezone_offset = false;

		$datetime_localized = wpbc_datetime_localized( $date_str_ymdhis, $format, $is_add_timezone_offset );

		return $datetime_localized;
	}


	// -----------------------------------------------------------------------------------------------------------------

	/**
	 * Return date / time in    'LOCAL_FORMAT'    with  WordPress Timezone   offset   from WordPress  > Settings General page
	 *
	 * @param string     $format                 Optional. Date/Time Format,  like 'Y-m-d H:i:s'
	 * @param string|int $date_str_ymdhis        Date to format.
	 *
	 * @return string
	 */
	function wpbc_datetime__use_wp_timezone( $format, $date_str_ymdhis = '' ) {

		if ( '' === $date_str_ymdhis ) {
			$date_str_ymdhis = date( 'Y-m-d H:i:s', strtotime( 'now' ) );
		}

		return wpbc_datetime_localized__use_wp_timezone( $date_str_ymdhis, $format );
	}


	/**
	 * Return date / time in    'LOCAL_FORMAT'    without  WordPress Timezone   offset       - NO TIMEZONE
	 *
	 * @param string     $format                 Optional. Date/Time Format,  like 'Y-m-d H:i:s'
	 * @param string|int $date_str_ymdhis        Date to format.
	 *
	 * @return string
	 */
	function wpbc_datetime__no_wp_timezone( $format, $date_str_ymdhis = '' ) {

		if ( '' === $date_str_ymdhis ) {
			$date_str_ymdhis = date( 'Y-m-d H:i:s', strtotime( 'now' ) );
		}

		return wpbc_datetime_localized__no_wp_timezone( $date_str_ymdhis, $format );
	}


	// -----------------------------------------------------------------------------------------------------------------


/**
 * Return date       in    'LOCAL_FORMAT'.
 *
 * @param string|int $date_str_ymd           Date to format.
 * @param string     $format                 Optional. Date/Time Format,  like 'Y-m-d'
 * @param bool       $is_add_timezone_offset Optional. Timezone offset.
 *
 * @return string
 */
function wpbc_date_localized( $date_str_ymd, $format = '', $is_add_timezone_offset = false ) {

	if ( empty( $format ) ) {
		$format = get_option( 'booking_date_format' );
	}
	if ( $format === '' ) {
		$format = get_option( 'date_format' );
	}

	return wpbc_datetime_localized( $date_str_ymd, $format, $is_add_timezone_offset );
}

/**
 * Return date       in    'LOCAL_FORMAT'.
 *
 * @param string|int $time_str_his           Date to format.
 * @param string     $format                 Optional. Date/Time Format,  like 'Y-m-d'
 * @param bool       $is_add_timezone_offset Optional. Timezone offset.
 *
 * @return string
 */
function wpbc_time_localized( $time_str_his, $format = '', $is_add_timezone_offset = false ) {

	if ( empty( $format ) ) {
		$format = get_option( 'booking_time_format' );
	}
	if ( $format === '' ) {
		$format = get_option( 'time_format' );
	}

	return wpbc_datetime_localized( $time_str_his, $format, $is_add_timezone_offset );
}


// ---------------------------------------------------------------------------------------------------------------------
// Localize support
// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * Get localized Dates as comma separated string     from  SQL  comma separated Dates
	 *
	 * @param  string $dates_str_in_sql_format - '2015-02-29 00:00:00, 2015-02-30 00:00:00'   |   '2015-02-29, 2015-02-30'   |   '2015-02-29 16:00:00, 2015-02-30 12:00:00'
	 *
	 * @return string - localized comma separated Dates string
	 */
	function wpbc_get_dates_comma_string_localized( $dates_str_in_sql_format ) {

		if ( empty( $dates_str_in_sql_format ) ) {
			return '';
		}

	    $dates_array_in_sql_format = explode( ',', $dates_str_in_sql_format );

	    $result_dates_arr = array();

	    foreach ( $dates_array_in_sql_format as $date_sql ) {

		    $date_sql = trim( $date_sql );

		    $date_arr = explode( ' ', $date_sql );

		    if ( count( $date_arr ) > 1 ) {
			    $time_arr = explode( ':', $date_arr[1] );
		    } else {
			    $time_arr = array( '00', '00', '00' );
		    }

		    if ( $time_arr == array( '00', '00', '00' ) ) {
			    $result_dates_arr[] = wpbc_date_localized(     $date_sql );     // Only Date
		    } else {
			    $result_dates_arr[] = wpbc_datetime_localized( $date_sql );     // Date and Time
		    }
	    }

		$mydates_result = implode( ', ', $result_dates_arr );

	    return $mydates_result;
	}


// ---------------------------------------------------------------------------------------------------------------------
// Readable dates
// ---------------------------------------------------------------------------------------------------------------------

/**
 * Get readable dates for showing text  to  Users.
 *
 * @param $dates_ymd_arr              [ '2023-10-11', '2023-11-12', '2023-11-13' ]
 * @param $params                     [ ]  Optional
 *
 * @return string
 */
function wpbc_get_redable_dates( $dates_ymd_arr , $params = array() ){

	$defaults = array(
                      'is_use_booking_recurrent_time' => ( 'On' === get_bk_option( 'booking_recurrent_time' ) ),
	                  'output_dates'                  => get_bk_option( 'booking_date_view_type' ),                      //  'short' | 'wide'
					  'date_label'                    => ( wpbc_is_multiple_dates( $dates_ymd_arr ) ) ? __( 'Dates', 'booking' ) : __( 'Date', 'booking' ),
					  'is_in_html'                    => true
    			);
    $params   = wp_parse_args( $params, $defaults );

	if ( 'short' == $params['output_dates'] ) {
		$dates_view = wpbc_get_dates_short_format(           implode( ',', $dates_ymd_arr ) );      // Short dates:                     '01 Jan 2023 - 30 Jan 2023'
	} else {
		$dates_view = wpbc_get_dates_comma_string_localized( implode( ',', $dates_ymd_arr ) );      // Comma separated dates:           '01 Jan 2023, 02 Jan 2023, ... 30 Jan 2023'
	}


	$readable_dates_html = '';

	$readable_dates_html .= ( ! empty( $params['date_label'] ) )
							?  $params['date_label'] . ': '
							: '';

	$readable_dates_html .= ( $params['is_in_html'] )
							? '<strong>' . $dates_view . '</strong>'
							: $dates_view;

	return $readable_dates_html;
}




/**
 * Get readable dates for showing text  to  Users.
 *
 * @param $dates_ymd_arr              [ '2023-10-11', '2023-11-12', '2023-11-13' ]
 * @param $times_arr_his              [ "16:00:01",  "18:00:02" ]
 * @param $params                     [ ]  Optional
 *
 * @return string
 */
function wpbc_get_redable_times( $dates_ymd_arr , $times_arr_his, $params = array() ){

	$defaults = array(
                      'is_use_booking_recurrent_time' => ( 'On' === get_bk_option( 'booking_recurrent_time' ) ),
	                  'output_dates'                  => get_bk_option( 'booking_date_view_type' ),                      //  'short' | 'wide'
					  'times_label'                   => ( wpbc_is_multiple_dates( $dates_ymd_arr ) ) ? __( 'Dates', 'booking' ) : __( 'Date', 'booking' ),
					  'is_in_html'                    => true
    			);
    $params   = wp_parse_args( $params, $defaults );

	if ( wpbc_is_times_used_as_timeslots( $dates_ymd_arr ) ){
		$readable_times_html = __( 'Time', 'booking' )      . ': <strong>' . wpbc_time_localized( $times_arr_his[0] ) . ' - ' . wpbc_time_localized( $times_arr_his[1] ) . '</strong>';
	} else {
		$readable_times_html = __( 'Check in', 'booking' )  . ': <strong>' . wpbc_time_localized( $times_arr_his[0] ) . '</strong>' . ' '
		                     . __( 'Check out', 'booking' ) . ': <strong>' . wpbc_time_localized( $times_arr_his[1] ) . '</strong>';
	}

	return $readable_times_html;


//	$readable_times_html = '';
//
//	$readable_times_html .= ( ! empty( $params['date_label'] ) )
//							?  $params['date_label'] . ': '
//							: '';
//
//	$readable_times_html .= ( $params['is_in_html'] )
//							? '<strong>' . $dates_view . '</strong>'
//							: $dates_view;
//
//	return $readable_times_html;
}



	/**
	 * Get days in short format view
	 *
	 * @param string $sql_dates_str Dates: 2023-10-12, 2023-10-13, 2023-10-14
	 *
	 * @return string             Dates in format: 2023-10-12 - 2023-10-14
	 */
	function wpbc_get_dates_short_format( $sql_dates_str ) {                                 // $days - string with comma seperated dates

		if ( empty( $sql_dates_str ) ) {
			return '';
		}

		$sql_dates_arr   = explode( ',', $sql_dates_str );
		$previos_date    = '';
		$short_dates_arr = array();

		foreach ( $sql_dates_arr as $date_sql ) {

			$date_sql = trim( $date_sql );

			if ( empty( $previos_date ) ) {

				// 1st date
				$short_dates_arr[] = wpbc_get_dates_comma_string_localized( $date_sql );                // Readable date format

			} else {

				// 2nd and more
				//if ( wpbc_is_next_day( $date_sql, $previos_date ) ) {
				$next_day_if__check_in__then__check_out = true;
				if ( wpbc_is_less_than_next_day( $date_sql, $previos_date, $next_day_if__check_in__then__check_out ) ) {

					if ( ' - ' != $short_dates_arr[ ( count( $short_dates_arr ) - 1 ) ] ){
						$short_dates_arr[] = ' - ';
					}

				} else {

					if ( ' - ' == $short_dates_arr[ ( count( $short_dates_arr ) - 1 ) ] ){

						$short_dates_arr[] = wpbc_get_dates_comma_string_localized( $previos_date );
					}

					$short_dates_arr[] = ',  ';
					$short_dates_arr[] = wpbc_get_dates_comma_string_localized( $date_sql );            // Readable date format
				}
			}

			$previos_date = $date_sql;
		}

		if ( ' - ' == $short_dates_arr[ ( count( $short_dates_arr ) - 1 ) ] ){
			$short_dates_arr[] = wpbc_get_dates_comma_string_localized( $previos_date );
		}

		$result_string = implode( '', $short_dates_arr );

		return $result_string;
	}



// ---------------------------------------------------------------------------------------------------------------------
// Dates Math
// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * Is multiple dates
	 *
	 * @param $dates_ymd_arr              [ '2023-10-11', '2023-11-12', '2023-11-13' ]
	 *
	 * @return bool
	 */
	function wpbc_is_multiple_dates( $dates_ymd_arr ) {

		return (bool) ( count( $dates_ymd_arr ) );
	}


	/**
	 * Is times use as      'TIME-SLOT'      or      'CHECK IN/OUT'      [start/end time] for first and last dates ?
	 *
	 * @param $dates_ymd_arr              [ '2023-10-11', '2023-11-12', '2023-11-13' ]
	 *
	 * @return bool
	 */
	function wpbc_is_times_used_as_timeslots( $dates_ymd_arr ) {

		if ( ( count( $dates_ymd_arr ) > 1 ) && ( 'On' != get_bk_option( 'booking_recurrent_time' ) ) ) {
			return false;       // Check In/Out
		} else {
			return true;        // Time Slots
		}
	}


	/**
	 * Check if     $now_date_sql   is TOMORROW    of  $previous_date_sql
	 *
	 * @param string $now_date_sql      : '2015-02-29'    |     '2015-02-29 00:00:00'
	 * @param string $previous_date_sql : '2015-02-30'    |     '2015-02-30 00:00:00'
	 *
	 * @return boolean              : true | false
	 */
	function wpbc_is_next_day( $now_date_sql, $previous_date_sql ) {

		if ( empty( $previous_date_sql ) ) {
			return false;
		}

		$now_date_sql      = wpbc_date_sql__ymd_his__to__ymd_000( $now_date_sql );
		$previous_date_sql = wpbc_date_sql__ymd_his__to__ymd_000( $previous_date_sql );

		$is_next_date = ( strtotime( $now_date_sql ) == strtotime( '+1 day', strtotime( $previous_date_sql ) ) );

		return $is_next_date;
	}

	/**
	 * Check if     $now_date_sql   is TOMORROW    of  $previous_date_sql
	 *
	 * @param string $now_date_sql      : '2015-02-29'    |     '2015-02-29 00:00:00'
	 * @param string $previous_date_sql : '2015-02-30'    |     '2015-02-30 00:00:00'
	 *
	 * @return boolean              : true | false
	 */
	function wpbc_is_less_than_next_day( $now_date_sql, $previous_date_sql, $next_day_if__check_in__then__check_out = false ) {

		if ( empty( $previous_date_sql ) ) {
			return false;
		}

		$now_date_sql      = wpbc_date_sql__ymd_his__to__ymd_000( $now_date_sql );
		$previous_date_sql = wpbc_date_sql__ymd_his__to__ymd_000( $previous_date_sql );

		$is_next_date = ( strtotime( $now_date_sql ) <= strtotime( '+1 day', strtotime( $previous_date_sql ) ) );

		// One exception  for dates with  check  in/out times.   For example   '2023-10-13 13:00:01' <=> '2023-10-13 10:00:02'
		//      return  false,
		//  because it's check in/out dates for the dif bookings or "recurrent times"
		if (   ( $is_next_date )
			&& ( $next_day_if__check_in__then__check_out )
			&& ( '1' == substr( $now_date_sql, 18 ) )
			&& ( '2' == substr( $previous_date_sql, 18 ) )
		){
			$is_next_date = false;
		}


		return $is_next_date;
	}


	/**
	 * Convert SQL date '2023-10-12 14:40:01'  -> '2023-10-12 00:00:00'
	 *
	 * @param $sql_date '2023-10-12 14:40:01'
	 *
	 * @return string   '2023-10-12 00:00:00'
	 */
	function wpbc_date_sql__ymd_his__to__ymd_000( $sql_date ) {

		$sql_date = trim( $sql_date );

		$date_arr = explode( ' ', $sql_date );

		return $date_arr[0] . ' 00:00:00';
	}




/**
 * Get weekday NUM from  date_sql_str  '2023-10-26'  ->  4
 *
 * @param $date_sql_str
 *
 * @return int
 */
function wpbc_date_get_week_day_num( $date_sql_str ) {

	return intval( date( 'w', strtotime( $date_sql_str ) ) );
}














// ---------------------------------------------------------------------------------------------------------------------
// Dates Debug
// ---------------------------------------------------------------------------------------------------------------------

//TODO: replace functions like this:        date_i18n( 'Y-m-d H:i:s'  ...            to             wpbc_datetime_localized( date( 'Y-m-d H:i:s',

// date_default_timezone_set( 'Europe/Amsterdam' );          // For Debug in Booking Calendar - booking form,  for shortcode [wpbc_test_dates_functions] this line can be commented

// <editor-fold     defaultstate="collapsed"                        desc="  ==  [wpbc_test_dates_functions]  ==  "  >
//TODO: delete it in version 9.9 or later,  if no errors
/**
 * Shortcode [wpbc_test_dates_functions]  to  test different dates functions on the server.
 *
 * @return void
 */
function wpbc_test_dates_functions() {

	if ( wpbc_is_on_edit_page() ) {
		return wpbc_get_preview_for_shortcode( 'wpbc_test_dates_functions', array() );      //FixIn: 9.9.0.39
	}

	ob_start();
	ob_clean();

	$server_zone = date_default_timezone_get();
	debuge( array( 'init' => 'Default Timezones', 'server'  => date_default_timezone_get(), 'wordpress'   => wp_timezone()->getName(), 'wordpress - gmt_offset' => get_option( 'gmt_offset' ) ) );

//	date_default_timezone_set( 'Europe/Oslo' );
//	date_default_timezone_set( 'Europe/Amsterdam' );
//	date_default_timezone_set( 'UTC' );

	debuge( array( 'init' => 'After WPBC changed timezone to UTC', 'server'  => date_default_timezone_get(), 'wordpress'   => wp_timezone()->getName(), 'wordpress - gmt_offset' => get_option( 'gmt_offset' ) ) );
	echo '<hr>';
	echo '<h1>Test  dates functions: </h1>';


	echo '<hr>';

	for ( $i = 0; $i < 366; $i ++ ) {

		$ts = date( 'Y-m-d 16:00:01', strtotime( '+' . $i . ' days' ) );

		$ts_arr = explode(' ',$ts);



		$date_arr = array();
		$date_arr['str']       = '+' . $i . ' days for  ' .   $ts_arr[0] . ' ' .$ts_arr[1] ;

		$date_arr['server'] = date_default_timezone_get();
		$date_arr['wordpress'] = wp_timezone()->getName();

		$date_arr['loc_DT'] = wpbc_datetime_localized( $ts );
		$date_arr['loc_D']  = wpbc_date_localized( $ts );
		$date_arr['loc_T']  = wpbc_time_localized( $ts );

		$date_arr['loc_D_param_D']  = wpbc_date_localized( $ts_arr[0] );
		$date_arr['loc_T_param_T']  = wpbc_time_localized( $ts_arr[1] );



		echo '<pre><code>' . var_export( $date_arr, 1 ) . '</code></pre><br>';
	}

	date_default_timezone_set( $server_zone );

	debuge( array( 'end' => 'Back to default previos timezone', 'server'  => date_default_timezone_get(), 'wordpress'   => wp_timezone_string()  ) );

	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}
add_shortcode( 'wpbc_test_dates_functions', 'wpbc_test_dates_functions' );
// </editor-fold>

//TODO: it's only  for Debug: Comment it. //FixIn: 9.9.0.17
// date_default_timezone_set( 'America/Chicago' );          // For Debug in Booking Calendar - booking form,  for shortcode [wpbc_test_dates_functions] this line can be commented
