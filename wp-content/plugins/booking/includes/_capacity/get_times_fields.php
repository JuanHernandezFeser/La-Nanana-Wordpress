<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4


/**
 * Get Time Slots from booking form for Each date:  [ '2023-10-25' => [ '12:20 - 12:55', '13:00 - 14:00' ], '2023-10-26' => [ ... ], ... ]
 *
 * @param $resource_id                              // Resource ID (used in case of MultiUser version for getting specific form  for regular  users)
 * @param $custom_form_name                         // Form  name for getting time slots configuration
 * @param $start_date_unix_timestamp                // Unix Timestamp to start dates count
 * @param $days_count                               // Int number of how many  dates to  check
 *
 * @return array                                    [ '2023-10-25' => [ '12:20 - 12:55', '13:00 - 14:00' ], '2023-10-26' => [ ... ], ... ]
 *
 *  Example:
 *            $timeslots_arr = wpbc_get_times_fields_configuration(
 *                                                                   1,
 *                                                                   'my_custom_form_name',
 *                                                                   strtotime( date( 'Y-m-d 00:00:00', strtotime( 'now' ) ) ),
 *                                                                   365
 *                                                                );
 */
function wpbc_get_times_fields_configuration__by_dates( $resource_id, $custom_form_name, $start_date_unix_timestamp, $days_count ){

	// Get booking form configuration CONTENT
	$booking_form_fields = wpbc_get__booking_form_fields__configuration( $resource_id, $custom_form_name );

	// -----------------------------------------------------------------------------------------------------------------
	// Get conditional  sections,  if exist
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 *  result=[   weekday-condition=[  0=[
	 *                                           type = "weekday"
	 *                                           value = "*"
	 *                                           options = false
	 *                                           content = "\r\n  Default:   [select rangetime  "10:00 - 11:00" "11:00 - 12:00" "12:00 - 13:00" "13:00 - 14:00" "14:00 - 15:00" "15:00 - 16:00" "16:00 - 17:00" "17:00 - 18:00"]\r\n"
	 *                                           structure = "[condition name="weekday-condition" type="weekday" value="*"]\r\n  Default:   [select rangetime  "10:00 - 11:00" "11:00 - 12:00" "12:00 - 13:00" "13:00 - 14:00" "14:00 - 15:00" "15:00 - 16:00" "16:00 - 17:00" "17:00 - 18:00"]\r\n[/condition]"
	 *                                  1 = [
	 *                                           type = "weekday"
	 *                                           value = "1,2"
	 *                                           options = false
	 *                                           content = "  \r\n  Monday, Tuesday:    [select rangetime  "10:00 - 12:00" "12:00 - 14:00"]\r\n"
	 *                                           structure = "[condition name="weekday-condition" type="weekday" value="1,2"]  \r\n  Monday, Tuesday:    [select rangetime  "10:00 - 12:00" "12:00 - 14:00"]\r\n[/condition]"
	 *                                  2 = [ ... ]
	 */
	$cached__sql_data__season_filters_arr = array();
	if ( function_exists( 'wpbc_conditions_form__get_sections' ) ) {

		$conditions = wpbc_conditions_form__get_sections( $booking_form_fields );

		if ( ! empty( $conditions ) ) {

			// ["High season", "Weekend season"]
			$season_names_arr = wpbc_conditions_get_season_titles_arr( $booking_form_fields );

			// Load all seasons from DB  to cache them - for having only one SQL request  to  DB,  if needed
			$cached__sql_data__season_filters_arr = wpbc_db_get_season_data_arr__for_these_season_names( $season_names_arr );
		}

	} else {
		$conditions = array();
	}
	// -----------------------------------------------------------------------------------------------------------------


	// -----------------------------------------------------------------------------------------------------------------
	// Get Time Slots           [ "10:30 - 10:45", "11:00 - 11:15", "11:30 - 12:00", ... ]       if   NO   conditional sections     - time slot the same for all dates. Get them once here.
	// -----------------------------------------------------------------------------------------------------------------
	if ( empty( $conditions ) ) {
		$time_slots_arr = wpbc_get_timeslots__from_content( $booking_form_fields );
	} else {
		$time_slots_arr = array();
	}
	// -----------------------------------------------------------------------------------------------------------------


	// -----------------------------------------------------------------------------------------------------------------
	// Here Go  for each DATE
	// -----------------------------------------------------------------------------------------------------------------
	$dates_times_fields_arr = array();
	for ( $day_num = 0; $day_num <= $days_count; $day_num ++ ) {

		$my_day_tag = date( 'Y-m-d', $start_date_unix_timestamp );                                   // '2023-07-19'

		if ( ( ! empty( $conditions ) ) && ( function_exists( 'wpbc_conditions_form__get_section__depend_from_date' ) ) ){

			// Get booking form conditional section depends on  DATE
			$section_content = wpbc_conditions_form__get_section__depend_from_date( $conditions, $my_day_tag , $cached__sql_data__season_filters_arr );

			// Get Time Slots           [ "10:30 - 10:45", "11:00 - 11:15", "11:30 - 12:00", ... ]
			$time_slots_in_section_arr = wpbc_get_timeslots__from_content( $section_content );

			$dates_times_fields_arr[ $my_day_tag ] = $time_slots_in_section_arr;
		} else {
			$dates_times_fields_arr[ $my_day_tag ] = $time_slots_arr;
		}

		// DEBUG: $dates_times_fields_arr[$my_day_tag] =  array( '00:00 - 24:00', '10:00 - 12:00', '12:00 - 14:00', '14:00 - 16:00', '16:00 - 18:00', '18:00 - 20:00' , '09:01 - 09:02' );

		$start_date_unix_timestamp += 24 * 60 * 60;                             // Go next day    	  +  1 day
	}

	return $dates_times_fields_arr;
}



	/**
	 * Get Time-Slots (CONVERT READABLE to SECONDS) seconds range array       for the specified date      from array of dates,     where each date include readable time-ranges array
	 *
	 * @param $dates_arr__with__readable_time_slots_arr     =   [
	 *                                                              2023-10-25 = [],
	 *                                                              2023-10-26 = [
	 *																				  0 => '00:00 - 24:00',
	 *																				  1 => '10:00 - 12:00',
	 *																				  2 => '12:00 - 14:00',
	 *																				  3 => '14:00 - 16:00',
	 *																				  4 => '16:00 - 18:00',
	 *																				  5 => '18:00 - 20:00',
	 *																				  6 => '09:01 - 09:02',
	 *                                                                           ],
	 *                                                              2023-10-27 = [], ...
	 *                                                          ]
	 * @param $my_day_tag                        = '2023-10-25'
	 *
	 * @return array|string[]  IF  ( $my_day_tag == '2023-10-25' )  =>  []
	 *                         OR  ( $my_day_tag == '2023-10-26' )  =>  [
	 *																	  0 => '1 - 86392',
	 *																	  1 => '36011 - 43192',
	 *																	  2 => '43211 - 50392',
	 *																	  3 => '50411 - 57592',
	 *																	  4 => '57611 - 64792',
	 *																	  5 => '64811 - 71992',
	 *																	  6 => '32471 - 32512',
	 *														            ]
	 */
	function wpbc_get__time_range_in_seconds_arr__for_date( $dates_arr__with__readable_time_slots_arr, $my_day_tag ) {

		if ( isset( $dates_arr__with__readable_time_slots_arr[ $my_day_tag ] ) ) {


			$bookingform__available_timeslots_in_sec__arr = array_map(
																	function ( $time_slot_readable ) {
																		return wpbc_convert__readable_time_slot__to__time_range_in_seconds( $time_slot_readable );
																	}
															, $dates_arr__with__readable_time_slots_arr[ $my_day_tag ] );

			return $bookingform__available_timeslots_in_sec__arr;
		}

		return array();
	}


// =====================================================================================================================
// Get Time-Slots from CONTENT  (booking form  configuration)
// =====================================================================================================================

/**
 * Get Time-Slots   ["10:30 - 10:45", "11:00 - 11:15", "11:30 - 12:00", ... ]        time slot values from booking form content ( or part of booking form configuration)
 *
 * @param string $content        '[calendar]... <p>Time: [select* rangetime "Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00"] ...'
 *
 * @return array
 */
function wpbc_get_timeslots__from_content( $content ){

    $time_slots_arr =array();

	// == RANGE TIME ==     [ '10:00 - 12:00', '12:00 - 14:00'  ... ]  -------------------------------------------------
	if ( false !== strpos( $content, ' rangetime ' ) ) {

		$time_slots_arr = wpbc_get_timeslots__for_rangetime( $content );

	} else {

		// == START TIME |  END TIME == --------------------------------------------------------------------------------
		if (
			( false !== strpos( $content, ' starttime ' ) )                     // Important to have empty space at the beginning, because it will search for [select starttime and skip this [starttime]
		 && ( false !== strpos( $content, ' endtime ' ) )                       // Important to have empty space at the beginning, because it will search for [select endtime   and skip this [endtime]
		){
			// Now we need to create the smallest time intervals ( 60 sec. ) for each start / end times.

			// == START TIME ==     [ '10:00 - 10:01', '12:00 - 12:01'  ... ]
			$start_time_slots_arr = wpbc_get_timeslots__for_starttime( $content , 60 );
			foreach ( $start_time_slots_arr as $ts ) {
				$time_slots_arr[] = $ts;
			}

			// == END TIME ==       [ '09:59 - 10:00', '11:59 - 12:00'  ... ]
			$end_time_slots_arr   = wpbc_get_timeslots__for_endtime(  $content , 60 );
			foreach ( $end_time_slots_arr as $ts ) {
				$time_slots_arr[] = $ts;
			}
		}

		// == START TIME |  DURATION TIME == ---------------------------------------------------------------------------
		if (
			( false !== strpos( $content, ' starttime ' ) )                    // Important to have empty space at the beginning, because it will search for [select starttime and skip this [starttime]
		 && ( false !== strpos( $content, ' durationtime ' ) )
		){
			// 900 -> e.g.: '00:15'
			$min_time_duration = wpbc_get__min_time_duration__in_seconds(  $content );

			if ( false !== $min_time_duration ) {

				// == START TIME ==     [ '10:00 - 10:15', '12:00 - 12:15'  ... ]
				$start_time_slots_arr = wpbc_get_timeslots__for_starttime( $content, $min_time_duration );
				foreach ( $start_time_slots_arr as $ts ) {
					$time_slots_arr[] = $ts;
				}
			}

		}
	}

	return $time_slots_arr;
}


	/**
	 * Get Time-Slots == RANGE TIME  == [ '10:00 - 12:00', '12:00 - 14:00'  ... ]    from  provided booking form configuration content
	 *
	 * @param $bookingform_configuration_content        - '[calendar]... <p>Time: [select* rangetime "Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00"] ...'
	 *
	 * @return array
	 *
	 * $time_slots_arr = [
	 *                        [0] => 00:00 - 24:00
	 *                        [1] => 10:00 - 12:00
	 *                        [2] => 12:00 - 14:00
	 *                        [3] => 14:00 - 16:00
	 *                        [4] => 16:00 - 18:00
	 *                        [5] => 18:00 - 20:00
	 *                    ]
	 *                 OR
	 *                    []
	 */
	function wpbc_get_timeslots__for_rangetime( $bookingform_configuration_content ) {

		$time_slots_arr   = array();
		$shortcode_values = wpbc_parse_form__get_first_shortcode_values( 'rangetime', $bookingform_configuration_content );

		if ( ! empty( $shortcode_values ) ) {
			foreach ( $shortcode_values as $timeslot_value ) {

				if ( ! empty( $timeslot_value['value'] ) ) {
					$time_slots_arr[] = $timeslot_value['value'];
				}
			}
		}

		return $time_slots_arr;
	}


	/**
	 * Get Time-Slots  from == START TIME  == [ '10:00 - 10:01', '12:00 - 12:01'  ... ]    searching in provided booking form configuration content
	 *
	 * @param string $bookingform_configuration_content        '[calendar]... <p>Time: [select* rangetime "Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00"] ...'
     * @param int $time_shift_duration_in_seconds           60
	 *
	 * @return array
	 *
	 * $time_slots_arr = [
	 *                        [1] => 10:00 - 10:01
	 *                        [2] => 12:00 - 12:01
	 *                        [3] => 14:00 - 14:01
	 *                        [4] => 16:00 - 16:01
	 *                        [5] => 18:00 - 18:01
	 *                    ]
	 *                 OR
	 *                    []
	 */
	function wpbc_get_timeslots__for_starttime( $bookingform_configuration_content , $time_shift_duration_in_seconds = 60 ) {

		$time_slots_arr   = array();

		$shortcode_values = wpbc_parse_form__get_first_shortcode_values( 'starttime', $bookingform_configuration_content );
		if ( ! empty( $shortcode_values ) ) {
			foreach ( $shortcode_values as $timeslot_value ) {

				if ( ! empty( $timeslot_value['value'] ) ) {
					// '12:00:00' -> 43190
					$time_in_seconds = wpbc_transform__24_hours_his__in__seconds( $timeslot_value['value'] . ':00' );

					$time_in_seconds += $time_shift_duration_in_seconds;

					// 43250 -> '12:01:00'
					$time_his = wpbc_transform__seconds__in__24_hours_his( $time_in_seconds );
					$time_his = substr( $time_his, 0, 5 );

					// '12:00 - 12:01'
					$time_slots_arr[] = $timeslot_value['value'] . ' - ' . $time_his;
				}
			}
		}


		return $time_slots_arr;
	}


	/**
	 * Get Time-Slots  from == END TIME  == [ '09:59 - 10:00', '11:59 - 12:00'  ... ]    searching in provided booking form configuration content
	 *
	 * @param string $bookingform_configuration_content        '[calendar]... <p>Time: [select* rangetime "Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00"] ...'
     * @param int $time_shift_duration_in_seconds           60
	 *
	 * @return array
	 *
	 * $time_slots_arr = [
	 *                        [1] => 09:59 - 10:00
	 *                        [2] => 11:59 - 12:00
	 *                        [3] => 13:59 - 14:00
	 *                        [4] => 15:59 - 16:00
	 *                        [5] => 17:59 - 18:00
	 *                    ]
	 *                 OR
	 *                    []
	 */
	function wpbc_get_timeslots__for_endtime( $bookingform_configuration_content , $time_shift_duration_in_seconds = 60 ) {

		$time_slots_arr   = array();

		$shortcode_values = wpbc_parse_form__get_first_shortcode_values( 'endtime', $bookingform_configuration_content );
		if ( ! empty( $shortcode_values ) ) {
			foreach ( $shortcode_values as $timeslot_value ) {

				if ( ! empty( $timeslot_value['value'] ) ) {
					// '12:00:00' -> 43190
					$time_in_seconds = wpbc_transform__24_hours_his__in__seconds( $timeslot_value['value'] . ':00' );

					$time_in_seconds -= $time_shift_duration_in_seconds;

					// 43130 -> '11:59:00'
					$time_his = wpbc_transform__seconds__in__24_hours_his( $time_in_seconds );
					$time_his = substr( $time_his, 0, 5 );

					// '11:59 - 12:00'
					$time_slots_arr[] = $time_his . ' - ' . $timeslot_value['value'];
				}
			}
		}

		return $time_slots_arr;
	}


	/**
	 * Get minimum duration of time ( from [select durationtime ... ] )  or FALSE, if no such  shortcode or no values.
	 *
	 * @param $bookingform_configuration_content
	 *
	 * @return false|int
	 */
	function wpbc_get__min_time_duration__in_seconds(  $bookingform_configuration_content ){

		$min_time_duration = false;
		$time_duration_in_seconds_arr = array();

		$shortcode_values = wpbc_parse_form__get_first_shortcode_values( 'durationtime', $bookingform_configuration_content );
		if ( ! empty( $shortcode_values ) ) {
			foreach ( $shortcode_values as $timeslot_value ) {

				if ( ! empty( $timeslot_value['value'] ) ) {
					// '00:15' -> 900
					$time_in_seconds = wpbc_transform__24_hours_his__in__seconds( $timeslot_value['value'] . ':00' );

					// [ 900 , ... ]
					$time_duration_in_seconds_arr[] = $time_in_seconds;
				}
			}
		}

		if ( ! empty( $time_duration_in_seconds_arr ) ) {
			$min_time_duration = min( $time_duration_in_seconds_arr );
		}

		return $min_time_duration;
	}