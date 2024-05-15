<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4


if ( ! defined( 'WPBC_FULL_DAY_TIME_IN' ) ) {  define( 'WPBC_FULL_DAY_TIME_IN'    , 1 ); }          // =                          +1 sec.               '00:00:01'
if ( ! defined( 'WPBC_FULL_DAY_TIME_OUT' ) ) { define( 'WPBC_FULL_DAY_TIME_OUT'   , 86392 ); }      // = 24 * 60 * 60 = 86400  - 10 sec. + 2 sec.       '23:59:52'

// ---------------------------------------------------------------------------------------------------------------------
// Intervals
// ---------------------------------------------------------------------------------------------------------------------

/**
 * Merge several  intersected intervals or return not intersected:                        [[1,3],[2,6],[8,10],[15,18]]  ->   [[1,6],[8,10],[15,18]]
 *
 * @param array $intervals      [ [1,3],[2,4],[6,8],[9,10],[3,7] ]
 *
 * @return array                [ [1,8],[9,10] ]
 *
 * Example:     wpbc_merge_intersected_intervals(  array(  array(1,3), array(2,4), array(6,8), array(9,10), array(3,7)  );      ->    array(  array(1,8), array(9,10)  )
 */
function wpbc_merge_intersected_intervals( $intervals ){

	if ( empty( $intervals ) ) {
		return array();
	}

    $merged = array();

	// Sort array by first value in a list          [[8,10],[1,3],[15,18],[2,6]]    ->      [[1,3],[2,6],[8,10],[15,18]]
	usort( $intervals, function ( $compare_A, $compare_B ) {
		if ( $compare_A[0] > $compare_B[0] ) {
			return 1;
		} elseif ( $compare_A[0] < $compare_B[0] ) {
			return - 1;
		}
		return 0;
	} );


	$merged_interval = $intervals[0];                           // [1,3]

	for ( $i = 1; $i < count( $intervals ); $i ++ ) {
		$next_element = $intervals[ $i ];                       // [2,6]

		if ( $next_element[0] <= $merged_interval[1] ) {
			$merged_interval[1] = max( $merged_interval[1], $next_element[1] );
		} else {
			$merged[]        = $merged_interval;
			$merged_interval = $next_element;
		}
	}

	$merged[] = $merged_interval;

    return $merged;
}


/**
 * Is 2 intervals intersected:       [36011, 86392]    <=>    [1, 43192]  =>  true      ( intersected )
 *
 * Good explanation  here https://stackoverflow.com/questions/3269434/whats-the-most-efficient-way-to-test-if-two-ranges-overlap
 *
 * @param array $interval_A   array( 36011, 86392 )
 * @param array $interval_B   array(     1, 43192 )
 *
 * @return bool
 */
function wpbc_is__intervals__intersected( $interval_A, $interval_B ) {

	if (
			( empty( $interval_A ) )
	     || ( empty( $interval_B ) )
	){
		return false;
	}

	$is_intersected = max( $interval_A[0], $interval_B[0] ) - min( $interval_A[1], $interval_B[1] );

	// if ( 0 == $is_intersected ) {
	//	                                 // Such ranges going one after other, e.g.: [ 12, 15 ] and [ 15, 21 ]
	// }

	if ( $is_intersected < 0 ) {
		return true;                     // INTERSECTED
	}

	return false;                       // Not intersected
}



// ---------------------------------------------------------------------------------------------------------------------
//  Time - Slots    functions
// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * Get time in seconds from  SQL date format               '2024-01-28 12:00:02' -> 1706443192
	 *
	 * @param $sql_date__ymd_his     '2024-01-28 12:00:02'  Date in SQL format
	 * @param bool $is_apply__check_in_out__10s          : check_in_time = +10sec     check_out_time = -10sec
	 *
	 * @return int
	 *
	 *            if $is_apply__check_in_out__10s == true (it's default)
	 *
	 *            then apply   +10 seconds for check  in time         it's time, that  ended with  1
	 *            and  apply   -10 seconds for check out time         it's time, that  ended with  2

	 */
	function wpbc_convert__sql_date__to_seconds( $sql_date__ymd_his, $is_apply__check_in_out__10s = true  ){

		list( $sql_date_ymd, $sql_time_his ) = explode( ' ', $sql_date__ymd_his );                                      // '2024-01-28',  '12:00:02'

		$date_in_seconds = wpbc_convert__date_ymd__to_seconds( $sql_date_ymd );
		$time_in_seconds = wpbc_convert__time_his__to_seconds( $sql_time_his, $is_apply__check_in_out__10s );

		$in_seconds = $date_in_seconds + $time_in_seconds;

		return $in_seconds;
	}


		/**
		 * Get date in seconds from  SQL only_date format          '2024-01-28' -> 1706400000
		 *
		 * @param $sql_date_ymd     '2024-01-28'
		 *
		 * @return int
		 */
		function wpbc_convert__date_ymd__to_seconds( $sql_date_ymd ){

			// DATE
			$sql_date_time = $sql_date_ymd . ' 00:00:00';

			$in_seconds = strtotime( $sql_date_time );

			return $in_seconds;
		}


		/**
		 * Get time in seconds from  SQL time format                '12:00:02' -> 43192
		 *
		 * @param string $sql_time_his     '12:00:02'
		 * @param bool $is_apply__check_in_out__10s          : check_in_time = +10sec     check_out_time = -10sec
		 *
		 * @return int
		 *
		 *            if $is_apply__check_in_out__10s == true (it's default)
		 *
		 *            then apply   +10 seconds for check  in time         it's time, that  ended with  1
		 *            and  apply   -10 seconds for check out time         it's time, that  ended with  2
		 */
		function wpbc_convert__time_his__to_seconds( $sql_time_his, $is_apply__check_in_out__10s = true ){

			// TIME
			$in_seconds = explode( ':', $sql_time_his );

			$in_seconds = intval( $in_seconds[0] ) * 60 * 60 + intval( $in_seconds[1] ) * 60 + intval( $in_seconds[2] );        // 43202

			if ( $is_apply__check_in_out__10s ) {

				$is_check_in_out__or_full = $in_seconds % 10;                                                                       // 0, 1, 2

				if ( 1 == $is_check_in_out__or_full ) {
					$in_seconds += 10;
				}
				if ( 2 == $is_check_in_out__or_full ) {
					$in_seconds -= 10;
				}
			}

			$in_seconds = intval( $in_seconds );

			return $in_seconds;
		}


		/**
		 * Get time in SQL time format from  seconds                43192  ->  '12:00:02'
		 *
		 * @param int $in_seconds                               43192
		 * @param bool $is_apply__check_in_out__10s          : check_in_time = +10sec     check_out_time = -10sec
		 *
		 * @return int
		 *
		 *            if $is_apply__check_in_out__10s == true (it's default)
		 *
		 *            then apply   +10 seconds for check  in time         it's time, that  ended with  1
		 *            and  apply   -10 seconds for check out time         it's time, that  ended with  2
		 */
		function wpbc_convert__seconds__to_time_his( $in_seconds, $is_apply__check_in_out__10s = true ){

			//$is_check_in_out__or_full = $seconds__this__booking % 10;                                                       // 1, 2  (not 0)
			$is_check_in_out__or_full = substr( ( (string) $in_seconds ), -1 );                           // '1', '2'  (not '0')

			if ( '1' == $is_check_in_out__or_full ) {
				if ( WPBC_FULL_DAY_TIME_IN == $in_seconds ) {           // situation: 00:00     e.g. 1 sec.
					$in_seconds = 0;
				} else {
					$in_seconds = $in_seconds - 10 - 1;
				}
			}
			if ( '2' == $is_check_in_out__or_full ) {
				if ( WPBC_FULL_DAY_TIME_OUT == $in_seconds ) {           // situation: 24:00     e.g. 86400 -10 +2 sec.
					$in_seconds = 86400;
				} else {
					$in_seconds = $in_seconds + 10 - 2;
				}
			}

			$rest_without_hours_as_seconds = $in_seconds % ( 60 * 60 );

			$in_hours = ( $in_seconds - $rest_without_hours_as_seconds ) / ( 60 * 60 );                                     // 43200
			$in_hours = intval( $in_hours );    // from 20.99 hours get 20          // round( $in_hours,   0, PHP_ROUND_HALF_DOWN );

			$in_minutes = $rest_without_hours_as_seconds / 60;                                                                // 43200
			$in_minutes = intval( $in_minutes );  // from 55.99 minutes get 55        // round( $in_minutes, 0, PHP_ROUND_HALF_DOWN );     // making 1.5 into 1 and -1.5 into -1.

			$in_only_seconds = $in_seconds - ( $in_hours * 60 * 60 ) - ( $in_minutes * 60 );                           // 43200
			$in_only_seconds = intval( $in_only_seconds );  // from 55.99 minutes get 55        // round( $in_minutes, 0, PHP_ROUND_HALF_DOWN );     // making 1.5 into 1 and -1.5 into -1.

			if ( '1' == $is_check_in_out__or_full ) {
				$in_only_seconds += 1;
			}
			if ( '2' == $is_check_in_out__or_full ) {
				$in_only_seconds += 2;
			}


			$in_hours        = ( $in_hours < 10 ) ? '0' . $in_hours : $in_hours;
			$in_minutes      = ( $in_minutes < 10 ) ? '0' . $in_minutes : $in_minutes;
			$in_only_seconds = ( $in_only_seconds < 10 ) ? '0' . $in_only_seconds : $in_only_seconds;

			$sql_time_his = $in_hours . ':' . $in_minutes . ':'. $in_only_seconds;

			return $sql_time_his;
		}


	/**
	 * Convert date timestamp (date in seconds)  to  SQL date format            1706400000 -> '2024-01-28 00:00:00'
	 *
	 * @param int $in_seconds                       1706400000
	 * @param bool $is_apply__check_in_out__10s     true                    : check_in_time = -10sec     check_out_time = +10sec
	 * @param string $sql_date_format               'Y-m-d H:i:s'           : sometimes need to have ''Y-m-d 00:00:01' or other...
	 *
	 * @return false|string                         '2024-01-28 00:00:00'
	 */
	function wpbc_convert__seconds__to_sql_date( $in_seconds, $is_apply__check_in_out__10s = true, $sql_date_format = 'Y-m-d H:i:s' ){

		if ( $is_apply__check_in_out__10s ) {

			$is_check_in_out__or_full = $in_seconds % 10;                                                                       // 0, 1, 2

			if ( 1 == $is_check_in_out__or_full ) {
				$in_seconds -= 10;
			}
			if ( 2 == $is_check_in_out__or_full ) {
				$in_seconds += 10;
			}
		}

		$sql_date = date( $sql_date_format, $in_seconds );

		return $sql_date;
	}

	// -----------------------------------------------------------------------------------------------------------------

	/**
	 * Convert usual to seconds time range     "10:15 - 12:00"    ->    "36911 - 43192"
	 *
	 * It's means that "10:15 - 12:00" in seconds "36900 - 43200"   and with intersection fix:  "(36900 +10 +1) - (43200 -10 +2)" e.g. "36911 - 43192"
	 *
	 * @param string $readable_time_slot        "10:15 - 12:00"
	 *
	 * @return string time_range_in_seconds     "36911 - 43192"
	 */
	function wpbc_convert__readable_time_slot__to__time_range_in_seconds( $readable_time_slot ) {

		$rangeA_arr = explode( '-', $readable_time_slot );
		$rangeA_arr[0] =  trim( $rangeA_arr[0] );
		$rangeA_arr[1] =  trim( $rangeA_arr[1] );

		$time_range_in_seconds = array();

		foreach ( $rangeA_arr as $is_end_time => $only_time ) {                 // $is_end_time = 0 | 1

			$in_seconds = explode( ':', $only_time );
			$in_seconds = intval( $in_seconds[0] ) * 60 * 60 + intval( $in_seconds[1] ) * 60;                               // 43200

			if ( ! $is_end_time ) {

				if ( 0 == $in_seconds ) {               // situation: 00:00     e.g. 0 sec.
					$in_seconds = WPBC_FULL_DAY_TIME_IN;
				} else {
					$in_seconds = $in_seconds + 10 + 1;
				}

			} else {

				if ( 86400 == $in_seconds ) {           // situation: 24:00     e.g. 86400 sec.
					$in_seconds = WPBC_FULL_DAY_TIME_OUT;
				} else {
					$in_seconds = $in_seconds - 10 + 2;
				}

			}

			$time_range_in_seconds[] = $in_seconds;
		}

		$time_range_in_seconds = implode( ' - ', $time_range_in_seconds );

		return $time_range_in_seconds;
	}


	/**
	 * Convert seconds to usual time range:     "36911 - 43192"    ->    "10:15 - 12:00"
	 *
	 * It's means  "36911 - 43192"  with intersection fix:  "(36900 +10 +1) - (43200 -10 +2)" e.g. in seconds "36900 - 43200"     is equal to    "10:15 - 12:00"
	 *
	 * @param string $time_range_in_seconds                  "36911 - 43192"
	 *
	 * @return string $time_slot      "10:15 - 12:00"
	 */
	function wpbc_convert__time_range_in_seconds__to__readable_time_slot( $time_range_in_seconds ) {

		$rangeA_arr = explode( '-', $time_range_in_seconds );
		$rangeA_arr[0] =  intval( trim( $rangeA_arr[0] ) );
		$rangeA_arr[1] =  intval( trim( $rangeA_arr[1] ) );

		$time_slot = array();

		foreach ( $rangeA_arr as $is_end_time => $in_seconds ) {                 // $is_end_time = 0 | 1

			if ( ! $is_end_time ) {

				if ( WPBC_FULL_DAY_TIME_IN == $in_seconds ) {           // situation: 00:00     e.g. 1 sec.
					$in_seconds = 0;
				} else {
					$in_seconds = $in_seconds - 10 - 1;
				}

			} else {

				if ( WPBC_FULL_DAY_TIME_OUT == $in_seconds ) {           // situation: 24:00     e.g. 86400 -10 +2 sec.
					$in_seconds = 86400;
				} else {
					$in_seconds = $in_seconds + 10 - 2;
				}
			}

			$just_minutes_as_seconds = $in_seconds % ( 60 * 60 );

			$in_hours   = ( $in_seconds - $just_minutes_as_seconds ) / ( 60 * 60 );                                     // 43200
			$in_minutes = $just_minutes_as_seconds / 60;                                                                // 43200

			$in_hours   = intval($in_hours);    // from 20.99 hours get 20          // round( $in_hours,   0, PHP_ROUND_HALF_DOWN );
			$in_minutes = intval($in_minutes);  // from 55.99 minutes get 55        // round( $in_minutes, 0, PHP_ROUND_HALF_DOWN );     // making 1.5 into 1 and -1.5 into -1.

			$in_hours   = ( $in_hours < 10 )   ? '0' . $in_hours   : $in_hours;
			$in_minutes = ( $in_minutes < 10 ) ? '0' . $in_minutes : $in_minutes;

			$time_slot[] = $in_hours . ':' . $in_minutes;
		}

		$time_slot = implode( ' - ', $time_slot );

		return $time_slot;
	}


	/**
	 * Get SQL TIME  from SECONDS       43211 -> '12:00:11'
	 *
	 * @param int $in_seconds   43201
	 *
	 * @return string       '10:00:02'
	 */
	function wpbc_transform__seconds__in__24_hours_his( $in_seconds ){

		$rest_without_hours_as_seconds = $in_seconds % ( 60 * 60 );

		$in_hours = ( $in_seconds - $rest_without_hours_as_seconds ) / ( 60 * 60 );                                     // 12
		$in_hours = intval( $in_hours );    // from 20.99 hours get 20          // round( $in_hours,   0, PHP_ROUND_HALF_DOWN );

		$in_minutes = $rest_without_hours_as_seconds / 60;                                                                // 43200
		$in_minutes = intval( $in_minutes );  // from 55.99 minutes get 55        // round( $in_minutes, 0, PHP_ROUND_HALF_DOWN );     // making 1.5 into 1 and -1.5 into -1.

		$in_only_seconds = $in_seconds - ( $in_hours * 60 * 60 ) - ( $in_minutes * 60 );                           // 43200
		$in_only_seconds = intval( $in_only_seconds );  // from 55.99 minutes get 55        // round( $in_minutes, 0, PHP_ROUND_HALF_DOWN );     // making 1.5 into 1 and -1.5 into -1.


		$in_hours        = ( $in_hours < 10 ) ? '0' . $in_hours : $in_hours;
		$in_minutes      = ( $in_minutes < 10 ) ? '0' . $in_minutes : $in_minutes;
		$in_only_seconds = ( $in_only_seconds < 10 ) ? '0' . $in_only_seconds : $in_only_seconds;

		$sql_time_his = $in_hours . ':' . $in_minutes . ':'. $in_only_seconds;

		return $sql_time_his;
	}


	/**
	 * Get time in seconds from  SQL time format                '12:00:02' -> 43192
	 *
	 * @param string $sql_time_his     '12:00:02'
	 *
	 * @return int
	 */
	function wpbc_transform__24_hours_his__in__seconds( $in_24_hours_his ) {

		$is_apply__check_in_out__10s = false;

		return wpbc_convert__time_his__to_seconds( $in_24_hours_his, $is_apply__check_in_out__10s );
	}

	/**
	 * Get AM/PM or 24 hour TIME  from SECONDS       43211 -> '12:00 PM'    |    86400 -> '12:00 AM'
	 *
	 * @param int $in_seconds           43201
	 * @param string $time_format      ''       (optional) time format, like 'H:i:s'  otherwise get from Booking > Settings General
	 *
	 * @return string                   '10:00:02' |  '10:00 AM'
	 */
	function wpbc_transform__seconds__in__formated_hours( $in_seconds, $time_format = '' ) {

		if ( empty( $time_format ) ) {
			$time_format = get_bk_option( 'booking_time_format' );          // get  from  Booking Calendar
			if ( empty( $time_format ) ) {
				$time_format = get_option( 'time_format' );                 // Get  WordPress default
			}
		}

		if ( $in_seconds >= 86400 ) {
			// if we have 24:00 then  instead of 00:00 show 24:00 ... Please check more here https://www.php.net/manual/en/datetime.format.php
			$time_format = str_replace( array( 'H', 'G' ), '24', $time_format );
		}

		$s_tm = date_i18n( $time_format, $in_seconds );

		return $s_tm;
	}

	// -----------------------------------------------------------------------------------------------------------------


	/**
	 * Union 2 time-slot ranges:       "100 - 900" & "500 - 3500" -> [ "100 - 3500" ]  OR   "10 - 70" & "100 - 500" -> [ "10 - 70", "100 - 500" ]
	 *
	 * @param $timeslot_A_in_range_seconds '36011 - 86392'   it is means:  "'Start time1 in seconds' - 'End time1 in seconds'"  e.g.   "10:00 - 24:00"
	 * @param $timeslot_B_in_range_seconds '1 - 43192'       it is means:  "'Start time2 in seconds' - 'End time2 in seconds'"  e.g.   "00:00 - 12:00"
	 *
	 * @return array [ "1 - 86392" ]  one or two ranges
	 *
	 */
	function wpbc_union__timeslots_seconds( $timeslot_A_in_range_seconds, $timeslot_B_in_range_seconds ) {

		if ( ! wpbc_is__timeslots_seconds__intersected( $timeslot_A_in_range_seconds, $timeslot_B_in_range_seconds ) ) {
			return array( $timeslot_A_in_range_seconds, $timeslot_B_in_range_seconds );
		}

		if ( ( '0' == $timeslot_A_in_range_seconds ) || ( '0' == $timeslot_B_in_range_seconds ) ) {
			return array( '0 - 86400' );                                                                                // Old Full day booking, it is means always intersect.
		}

		$rangeA_arr = explode( '-', $timeslot_A_in_range_seconds );
		$rangeB_arr = explode( '-', $timeslot_B_in_range_seconds );

		$rangeA_arr[0] = intval( trim( $rangeA_arr[0] ) );
		$rangeA_arr[1] = intval( trim( $rangeA_arr[1] ) );
		$rangeB_arr[0] = intval( trim( $rangeB_arr[0] ) );
		$rangeB_arr[1] = intval( trim( $rangeB_arr[1] ) );

		$start  = min( $rangeA_arr[0], $rangeB_arr[0] );
		$end    = max( $rangeA_arr[1], $rangeB_arr[1] );
		$result = array( $start . ' - ' . $end );

		return $result;
	}


	/**
	 * Is 2 time-slots ranges intersected:       "36011 - 86392"    <=>    "1 - 43192"  =  true      ( intersected )
	 *
	 * Good explanation  here https://stackoverflow.com/questions/3269434/whats-the-most-efficient-way-to-test-if-two-ranges-overlap
	 *
	 * @param $timeslot_A_in_range_seconds '36011 - 86392'   it is means:  "'Start time1 in seconds' - 'End time1 in seconds'"  e.g.   "10:00 - 24:00"
	 * @param $timeslot_B_in_range_seconds '1 - 43192'       it is means:  "'Start time2 in seconds' - 'End time2 in seconds'"  e.g.   "00:00 - 12:00"
	 *
	 * @return bool
	 *
	 *
	 *   Some exceptions:
	 *                      if range = '0' - it is means Full  day  booking,  it's always intersected !
	 *    all start times ended with "1" second and previously was added +10 seconds, that's why 36011 is means 36011 - 10 - 1 = 36000  e.g. = 10:00
	 *    all end times ended with "2" second and previously was -10 seconds, that's why 43192 is means 43192 + 10 - 2 = 43200  e.g. = 12:00
	 *
	 *    When  in some date we have ONLY  start  time or end time,  it's means that this time start from  00:00 or end at  24:00  (because usually  such bookings for SEVERAL days)
	 *    and we autofill such  times by  adding start  time or end time:
	 *                                                                      Autofilled start time is always 1       = 0           +1     - 00:01
	 *                                                                      Autofilled end   time is always 86392   = 86400  -10  +2     - 23:59:58
	 */
	function wpbc_is__timeslots_seconds__intersected( $timeslot_A_in_range_seconds, $timeslot_B_in_range_seconds ) {

		// Full  day  booking,  it is means always intersect.
		if ( ( '0' == $timeslot_A_in_range_seconds ) || ( '0' == $timeslot_B_in_range_seconds ) ) {
			return true;                                                        // Time ranges INTERSECTED
		}

		$rangeA_arr = explode( '-', $timeslot_A_in_range_seconds );
		$rangeB_arr = explode( '-', $timeslot_B_in_range_seconds );

		$rangeA_arr[0] = intval( trim( $rangeA_arr[0] ) );
		$rangeA_arr[1] = intval( trim( $rangeA_arr[1] ) );
		$rangeB_arr[0] = intval( trim( $rangeB_arr[0] ) );
		$rangeB_arr[1] = intval( trim( $rangeB_arr[1] ) );


		$is_intersected = max( $rangeA_arr[0], $rangeB_arr[0] ) - min( $rangeA_arr[1], $rangeB_arr[1] );

		// if ( 0 == $is_intersected ) {
		//	                                 // Such ranges going one after other, e.g.: "12:00 - 15:00" and "15:00 - 21:00"
		// }

		if ( $is_intersected < 0 ) {
			return true;                     // Time ranges INTERSECTED
		}

		return false;                       // Otherwise time slot not intersected
	}


	/**
	 * Is 2 time-slots ranges intersected:       "10:00 - 24:00"    <=>    "00:00 - 12:00"  =  true      ( intersected )
	 *
	 *
	 * @param $timeslot_A_readable '10:00 - 24:00'
	 * @param $timeslot_B_readable '00:00 - 12:00'
	 *
	 * @return bool
	 *
	 *
	 *   See more here:     wpbc_is__timeslots_seconds__intersected()
	 */
	function wpbc_is__timeslots_readable__intersected( $timeslot_A_readable, $timeslot_B_readable ) {

		// Full  day  booking,  it is means always intersect.
		if ( ( '0' == $timeslot_A_readable ) || ( '0' == $timeslot_B_readable ) ) {
			return true;                                                        // Time ranges INTERSECTED
		}

		$timeslot_A_in_range_seconds = wpbc_convert__readable_time_slot__to__time_range_in_seconds( $timeslot_A_readable );
		$timeslot_B_in_range_seconds = wpbc_convert__readable_time_slot__to__time_range_in_seconds( $timeslot_B_readable );

		return wpbc_is__timeslots_seconds__intersected( $timeslot_A_in_range_seconds, $timeslot_B_in_range_seconds );
	}


	// -----------------------------------------------------------------------------------------------------------------

		/**
		 * Check if  BOOKING_FORM  time-slots     available and not intersect  with  booked time slots from  bookings
		 *
		 *
		 * @param array $checking__timerange_sec__arr [ '36011 - 43192', '56011 - 86392' ]
		 * @param array $booked__timerange_sec__arr   [ '36011 - 43192' ]
		 *
		 * @return bool                               true | false
		 *
		 *
		 *      if $checking__timerange_sec__arr EMPTY  then  TRUE
		 *
		 */
		function wpbc_is_some_timeslot__available__in_arr( $checking__timerange_sec__arr, $booked__timerange_sec__arr ) {

			if ( empty( $checking__timerange_sec__arr ) ) {
				return true;
			}

			$is_available__timeslots__arr = array();

			// Available_Form time-slots:  [ "10:00 - 12:00", "12:00 - 14:00", "14:00 - 16:00" ]
			foreach ( $checking__timerange_sec__arr as $checking__timeslot__key => $checking__timeslot__value ) {

				$is_available__timeslots__arr[ $checking__timeslot__key ] = true;

				// Booked time-slots:  [ "12:00 - 14:00", "14:00 - 16:00" ]
				foreach ( $booked__timerange_sec__arr as $booked__timeslot_value ) {

					$is_time_intersected = wpbc_is__timeslots_seconds__intersected( $booked__timeslot_value, $checking__timeslot__value );      // '36011 - 43192', '56011 - 86392'
					if ( $is_time_intersected ) {
						$is_available__timeslots__arr[ $checking__timeslot__key ] = false;
						break;
					}
				}
			}

			// If at least one time slot available then day still available
			$is_some_timeslot_available = false;
			foreach ( $is_available__timeslots__arr as $is__timeslot_available ) {
				if ( $is__timeslot_available ) {
					$is_some_timeslot_available = true;
					break;
				}
			}

			return $is_some_timeslot_available;
		}

	// -----------------------------------------------------------------------------------------------------------------

	/**
	 * '43211 - 86392'   into   [43201, 86400]
	 *
	 * @param string $booked_times__key_arr
	 * @param bool $is_apply_end_time_correction
	 *
	 * @return array|string[]
	 */
	function wpbc_transform_timerange__s_range__in__seconds_arr( $booked_times__key_arr, $is_apply_end_time_correction = true ){

		$booked_times__key_arr = explode( ' - ', $booked_times__key_arr );

		$booked_times__key_arr[0] = intval( trim( $booked_times__key_arr[0] ) );
		$booked_times__key_arr[1] = intval( trim( $booked_times__key_arr[1] ) );

		if ( $is_apply_end_time_correction ) {

			// Fix when  we have 10:00:11 seconds, and we need to  show the 14:00:02
			if ( '1' == substr( (string) $booked_times__key_arr[0], - 1 ) ) {
				$booked_times__key_arr[0] = $booked_times__key_arr[0] - 10;
			}
			$booked_times__key_arr[0] = ( $booked_times__key_arr[0] < 0 ) ? 1 : $booked_times__key_arr[0];

			// Fix when  we have 13:59:52 seconds, and we need to  show the 14:00:02
			if ( '2' == substr( (string) $booked_times__key_arr[1], - 1 ) ) {
				$booked_times__key_arr[1] = $booked_times__key_arr[1] + 10;
			}

			$booked_times__key_arr[1] = ( $booked_times__key_arr[1] > 86400 ) ? 86400 : $booked_times__key_arr[1];
		}

		return $booked_times__key_arr;
	}


	/**
	 * [43211, 86400] ->    '12:00:11 - 24:00:00'
	 *
	 * @param $booked_times__key_arr
	 *
	 * @return string
	 */
	function wpbc_transform_timerange__seconds_arr__in__24_hours_range( $booked_times__key_arr ){

		$readable_time_slot_formatted =  wpbc_transform__seconds__in__24_hours_his( $booked_times__key_arr[0] )
		                                . ' - '
		                                . wpbc_transform__seconds__in__24_hours_his( $booked_times__key_arr[1] ) ;

		return $readable_time_slot_formatted;
	}


	/**
	 * [43211, 86400] ->    '12:00 PM  - 12:00 AM'      -   transform  time-range       in      General Settings - Time Format
	 *
	 * @param $booked_times__key_arr
	 *
	 * @return string
	 */
	function wpbc_transform_timerange__seconds_arr__in__formated_hours( $booked_times__key_arr ){

		$readable_time_slot_formatted =  wpbc_transform__seconds__in__formated_hours( $booked_times__key_arr[0] )
		                                . ' - '
		                                . wpbc_transform__seconds__in__formated_hours( $booked_times__key_arr[1] ) ;

		return $readable_time_slot_formatted;
	}


	/**
	 * Converts a period of time in seconds into a human-readable format representing the interval.
	 *
	 * @param  int $time_interval_in_seconds    : 90    -   A period of time in seconds.
	 *
	 * @return string                           : '1 minute 30 seconds'
	 *
	 * Example:
	 *
	 *     echo wpbc_get_readable_time_interval( 90 );          <-  1 minute 30 seconds
	 *
	 */
	function wpbc_get_readable_time_interval( $time_interval_in_seconds ) {

		// Array of time period chunks.
		$chunks = array(
			/* translators: 1: The number of years in an interval of time. */
			array( 60 * 60 * 24 * 365, _n_noop( '%s year', '%s years', 'booking' ) ),
			/* translators: 1: The number of months in an interval of time. */
			array( 60 * 60 * 24 * 30, _n_noop( '%s month', '%s months', 'booking' ) ),
			/* translators: 1: The number of weeks in an interval of time. */
			array( 60 * 60 * 24 * 7, _n_noop( '%s week', '%s weeks', 'booking' ) ),
			/* translators: 1: The number of days in an interval of time. */
			array( 60 * 60 * 24, _n_noop( '%s day', '%s days', 'booking' ) ),
			/* translators: 1: The number of hours in an interval of time. */
			array( 60 * 60, _n_noop( '%s hour', '%s hours', 'booking' ) ),
			/* translators: 1: The number of minutes in an interval of time. */
			array( 60, _n_noop( '%s minute', '%s minutes', 'booking' ) ),
			/* translators: 1: The number of seconds in an interval of time. */
			array( 1, _n_noop( '%s second', '%s seconds', 'booking' ) ),
		);

		if ( ( $time_interval_in_seconds <= 0 ) || ( ! is_int( $time_interval_in_seconds ) ) ) {
			return __( 'now', 'booking' );
		}

		/**
		 * We only want to output two chunks of time here, eg:
		 * x years, xx months
		 * x days, xx hours
		 * so there's only two bits of calculation below:
		 */
		$j = count( $chunks );

		// Step one: the first chunk.
		for ( $i = 0; $i < $j; $i++ ) {
			$seconds = $chunks[ $i ][0];
			$name = $chunks[ $i ][1];

			// Finding the biggest chunk (if the chunk fits, break).
			$count = floor( $time_interval_in_seconds / $seconds );
			if ( $count ) {
				break;
			}
		}

		// Set output var.
		$output = sprintf( translate_nooped_plural( $name, $count, 'booking' ), $count );

		// Step two: the second chunk.
		if ( $i + 1 < $j ) {
			$seconds2 = $chunks[ $i + 1 ][0];
			$name2 = $chunks[ $i + 1 ][1];
			$count2 = floor( ( $time_interval_in_seconds - ( $seconds * $count ) ) / $seconds2 );
			if ( $count2 ) {
				// Add to output var.
				$output .= ' ' . sprintf( translate_nooped_plural( $name2, $count2, 'booking' ), $count2 );
			}
		}

		return $output;
	}


// =====================================================================================================================
//  ==  D A T E S   M a i n l y  ===
// =====================================================================================================================


/**
 * Conception:  - Time-Slots     A  - B                    '10:00:01 - 12:00:02'                 '10:00:01 - 12:00:02'
 *
 *              - Check in       A  - ...                  '14:00:01 -  ...  '                   '14:00:01 - 24:00:00'
 *              - Check out      ... -  B                  '  ...  - 12:00:02'                   '00:00:00 - 12:00:02'
 *
 *              - Full day       ... - ...                 '  ...  -  ...  '                     '00:00:00 - 24:00:00'
 *
 *              - Full IN       ..1 - ...                  '00:00:01 -  ...  '                   '00:00:01 - 24:00:00'
 *              - Full OUT       ... - ..2                 '  ...  - 24:00:02'                   '00:00:00 - 24:00:02'
 *
 *
 *              1) Always in one specific date,  can  be start & end times.
 *              2) Such  start  end times can  define
 *
 */

// ---------------------------------------------------------------------------------------------------------------------
// Support Transformation functions for dates arrays
// ---------------------------------------------------------------------------------------------------------------------

	/**
	 *  From SQL dates array  generate new Extended Dates Array:   [ > resource_id < ][ > booking_id < ] => Obj(  'sql__booking_dates__arr':[
	 *																												                            [ >seconds< ] =>       > SQL DATE <
	 *																												                            [1693303211]  => 2023-08-29 10:00:01
	 *																												                            [1693353600]  => 2023-08-30 00:00:00
	 *																												                            [1693483192]  => 2023-08-31 12:00:02
	 *																												                          ]
	 *																								            , ... )
	 * @param array $params = array [
	                                    'sql_dates_arr'      = [
																	 [0] => stdClass Object (
																            [booking_date] => 2023-08-08 10:00:01
																            [approved] => 1
																            [date_res_type] =>
																            [booking_id] => 45
																            [form] => select-one^rangetime2^10:00 - 12:00~text^...
																            [parent] => 0
																            [prioritet] => 20
																            [type] => 2
																        )
																    [1] => stdClass Object (
																            [booking_date] => 2023-08-08 10:00:01
																            [approved] => 0
																            [date_res_type] =>
																            [booking_id] => 46
																            [form] => select-one^rangetime10^10:00 - 12:00~text^...
																            [parent] => 2
																            [prioritet] => 1
																            [type] => 10
																        ), ...
														    , ...]
										, 'is_sort__dates_arr'          => true
										, 'is_apply__check_in_out__10s' => true
						            ]
	 *
	 * @return array =   [ > resource_id < ][ > booking_id < ] => obj( ... )

			Example:
				         [
								[3] = [
											[64] = obj{
														   booking_date = "2023-09-26 14:00:01"
														   approved = "0"
														   date_res_type = null
														   booking_id = "64"
														   form = "text^selected_short_dates_hint3^...
														   parent = "0"
														   prioritet = "30"
														   type = "3"
														   __summary__booking = [
																		        sql__booking_dates__arr = [  1695736811 = "2023-09-26 14:00:01"
																							       1695772800 = "2023-09-27 00:00:00"
																							       1695902392 = "2023-09-28 12:00:02"
											                                                    ]
											                                 ]
													  }
											[47] => stdClass Object(...)
											 ...
								       ]
							    ...
			            ]
	 *
	 *
	 *  Each such  booking is date object with  additional property:        -> __summary__booking[ 'sql__booking_dates__arr' ]
	 *  It is array of ALL Dates (in SQL date format) for specific booking
	 *  Then if needed, we extend booked dates   on specific number of times or hours/minutes
	 *
	 *
	 *   Relative this:   ['__summary__booking']['sql__booking_dates__arr'] = [
																	1695736811 = "2023-09-26 14:00:01"
																	1695772800 = "2023-09-27 00:00:00"
																	1695902392 = "2023-09-28 12:00:02"
																]
	 *                  '1695736811'  - timestamp of check in/out dates on 10 seconds less/higher than SQL date '2023-09-26 14:00:01'
	 *
	 */
	function wpbc_support__transform__into__resource_booking_dates__arr( $params ){

		$defaults = array(
		                  'sql_dates_arr'               => array()
						, 'is_sort__dates_arr'          => true
						, 'is_apply__check_in_out__10s' => true
					);
		$params   = wp_parse_args( $params, $defaults );


		$result_arr = array();

		foreach ( $params['sql_dates_arr'] as $date_object ) {

			$booking_id  = $date_object->booking_id;                                                                           // '43'
			$resource_id = ( ! empty( $date_object->date_res_type ) ) ? $date_object->date_res_type : $date_object->type;      // '12'

			if ( empty( $result_arr[ $resource_id ] ) ) {                   $result_arr[ $resource_id ] = array(); }
			if ( empty( $result_arr[ $resource_id ][$booking_id] ) ) {

				$result_arr[ $resource_id ][$booking_id] = $date_object;
				$result_arr[ $resource_id ][$booking_id]->__summary__booking = array(	'sql__booking_dates__arr' => array()   );     // New '__summary__booking' property
			}

			// Get seconds timestamp
			$in_seconds = wpbc_convert__sql_date__to_seconds( $date_object->booking_date, $params['is_apply__check_in_out__10s'] );

			$result_arr[ $resource_id ][ $booking_id ]->__summary__booking[ 'sql__booking_dates__arr' ][ $in_seconds ] = $date_object->booking_date;


			// Sort new array  with dates
			if ( $params['is_sort__dates_arr'] ) {
				ksort( $result_arr[ $resource_id ][ $booking_id ]->__summary__booking[ 'sql__booking_dates__arr' ] );
			}
		}

		return $result_arr;
	}


	/**
	 *  Transform Into:      ['2024-01-28'][ > resource_id < ][ > booking_id < ][ > only_time_seconds_int < ]  => obj(   booking_date: "2024-01-28 10:00:01", ... )
	 *
	 * @param array $params = [
	 *                          'is_apply__check_in_out__10s' => true
	 *                          'resource_booking_dates_arr'  => array = [ > resource_id < ][ > booking_id < ] => obj( ... )
	 *	                      ]
	 *
	 *   Example:
	 *
	 *	 $params['resource_booking_dates_arr'] = [
	 *													 [3] = [
	 *																 [64] = obj{
	 *																			    booking_date = "2023-09-26 14:00:01"
	 *																			    approved = "0"
	 *																			    date_res_type = null
	 *																			    booking_id = "64"
	 *																			    form = "text^selected_short_dates_hint3^...
	 *																			    parent = "0"
	 *																			    prioritet = "30"
	 *																			    type = "3"
	 *																			    __summary__booking = [
	 *																							         sql__booking_dates__arr = [  1695736811 = "2023-09-26 14:00:01"
	 *																												        1695772800 = "2023-09-27 00:00:00"
	 *																												        1695902392 = "2023-09-28 12:00:02"
	 *																                                                     ]
	 *																                                  ]
	 *																		   }
	 *																  [47] => stdClass Object(...)
	 *																  ...
	 *													        ]
	 *												     ...
	 *								             ]
	 *
	 * @return array  -  ['2024-01-28'][ > resource_id < ][ > booking_id < ][ > only_time_seconds < ]  => obj(   booking_date: "2024-01-28 10:00:01", ... )
	 *
	 *      Example:
	 *		result = [
	 *					2023-08-01 = [
	 *								    3 = [
	 *											   62 = [
	 *													    36011 = {stdClass}
	 *													    43192 = {
	 *																     booking_date = "2023-08-01 12:00:02"
	 *																     approved = "0"
	 *																     date_res_type = null
	 *																     booking_id = "62"
	 *																     form = "select-multiple^rangetime3[]^10:00 - 12:00~...
	 *																     parent = "0"
	 *																     prioritet = "30"
	 *																     type = "3"
	 *																     __summary__booking = [
	 *																					      sql__booking_dates__arr = [
	 *																										       1690884011 = "2023-08-01 10:00:01"
	 *																										       1690891192 = "2023-08-01 12:00:02"
	 *																										  ]
	 *																					  ]
	 *															     }
	 *												    ]
	 *									    ]
	 *						    ]
	 *					2023-08-05 = [ ... ]
	 *				]
	 */
	function wpbc_support__transform__into__date_resource_booking_timesec__arr( $params ){

		$defaults = array(
			                  'resource_booking_dates_arr'  => array()
							, 'is_apply__check_in_out__10s' => true
					);
		$params   = wp_parse_args( $params, $defaults );


		$result__arr = array();
		foreach ( $params['resource_booking_dates_arr'] as $resource_id => $bookings_arr ) {

			foreach ( $bookings_arr as $booking_id => $booking_obj ) {

				$extended_dates_arr = ( isset( $booking_obj->__summary__booking['sql__booking_dates__arr__extended'] ) )
										? $booking_obj->__summary__booking['sql__booking_dates__arr__extended']
										: $booking_obj->__summary__booking['sql__booking_dates__arr'];

				foreach ( $extended_dates_arr as $extended_date_time_stamp => $extended_date_sql ) {

					$new_extended_date_obj = wpbc_clone_array_of_objects( $booking_obj );
					$new_extended_date_obj->booking_date = $extended_date_sql;

					list( $date_without_time, $only_time ) = explode( ' ', $new_extended_date_obj->booking_date );          // '2024-01-28',  '12:00:02'
					$seconds_int = wpbc_convert__time_his__to_seconds( $only_time, $params['is_apply__check_in_out__10s'] );                                         // 43202

					if ( ! isset( $result__arr[ $date_without_time ] ) ) {                                $result__arr[ $date_without_time ] = array(); }
					if ( ! isset( $result__arr[ $date_without_time ][ $resource_id ] ) ) {                $result__arr[ $date_without_time ][ $resource_id ] = array(); }
					if ( ! isset( $result__arr[ $date_without_time ][ $resource_id ][ $booking_id ] ) ) { $result__arr[ $date_without_time ][ $resource_id ][ $booking_id ] = array(); }

					$result__arr[ $date_without_time ][ $resource_id ][ $booking_id ][ $seconds_int ] = $new_extended_date_obj;
				}
			}
		}

		return $result__arr;
		/**
			2024-01-28 = [ > resource_id <
	                            12 => [ > booking_id <
		                                    43 => [ > seconds <
			                                            36001 =>    Obj[
																		    booking_date    = "2024-01-28 10:00:01"
																		    date_res_type   = null
																		    booking_id      = "43"
																		    approved        = "0"
																		    form            = "select-one^rangetime12^10:00 - 12:00~text^..."
																		    parent          = "2"
																		    type            = "12"
																		    ...
				                                                        ]
			                                            43202 =>    Obj[
																	        type            = "12"
		 */
	}


	/**
	 * Transform Into:     ['2023-08-30'][ > resource_id < ][ > time_seconds_range < ] => booking_date_obj[ booking_id:45, approved:1, ...
	 *
	 * @param array $params  [
	 *          'is_apply__check_in_out__10s'       => true
	 *          'date_resource_booking_timesec_arr' =>  [
	 *                                                       2023-08-01 = [
	 *                                                      			      3 = [
	 *                                                      						    62 = [
	 *                                                      								     36011 = {stdClass}
	 *                                                      								     43192 = {
	 *                                                                                                         booking_date = "2023-08-01 12:00:02"
	 *                                                                                                         approved = "0"
	 *                                                                                                         date_res_type = null
	 *                                                                                                         booking_id = "62"
	 *                                                                                                         form = "select-multiple^rangetime3[]^10:00 - 12:00~...
	 *                                                                                                         parent = "0"
	 *                                                                                                         prioritet = "30"
	 *                                                                                                         type = "3"
	 *                                                                                                         __summary__booking = [
	 *                                                                                                         			            sql__booking_dates__arr = [
	 *                                                                                                         								          1690884011 = "2023-08-01 10:00:01"
	 *                                                                                                         								          1690891192 = "2023-08-01 12:00:02"
	 *                                                                                                         								        ]
	 *                                                                                                         			         ]
	 *                                                      										      }
	 *                                                      							     ]
	 *                                                      				     ]
	 *                                                      	     ]
	 *                                                       2023-08-05 = [ ... ]
	 *                                                  ]
	 *
	 * @return array    =  ['2023-08-30'][ > resource_id < ][ > time_seconds_range < ] => booking_date_obj[ booking_id:45, approved:1, ...
	 *
	 *   Example:  [
				    [2023-08-08] => [
							            [2] => [
					                                [36011 - 86392] => stdClass Object (    [booking_date] => 2023-08-08 10:00:01          , .... )
				                                ]
			                            [10] => [
	                                                [36011 - 86392] => stdClass Object (    [booking_date] => 2023-08-08 10:00:01          , .... )
	                                             ]
				                    ]
				    [2023-08-09] => [
	                                    [2] => [
		                                            [0]             => stdClass Object (    [booking_date] => 2023-08-09 00:00:00, .... )
			                                    ]
										[10] => [
	                                                [36011 - 43192] => stdClass Object (    [booking_date] => 2023-08-30 12:00:02
		                                                                                    , ....
																							__summary__booking = [
																											     sql__booking_dates__arr = [
																																  1690884011 = "2023-08-01 10:00:01"
																																  1690891192 = "2023-08-01 12:00:02"
																							                                    ]
																							                  ]
			                                                                            )
			                                    ]
	                               ]
	              ]

	 */
	function wpbc_support__transform__into__date_resource_timesec_range__arr( $params ){

		$defaults = array(
			                  'date_resource_booking_timesec_arr'  => array()
							, 'is_apply__check_in_out__10s' => true
					);
		$params   = wp_parse_args( $params, $defaults );


		$result__arr = array();

		foreach ( $params[ 'date_resource_booking_timesec_arr' ] as $a_only_date => $a_resources_arr ) { // '2023-07-25' => array( resource_id   => bookings_arr )

			foreach ( $a_resources_arr as $a_resource_id => $a_bookings_arr ) {             //              1   => array( booking_id => times_arr )

				foreach ( $a_bookings_arr as $a_booking_id => $a_seconds_arr_in_1_booking ) {    //              10       => array ( second => stdObj( booking_date obj from DB )

					if ( ! isset( $result__arr[ $a_only_date ] ) ) {                   $result__arr[ $a_only_date ] = array(); }
					if ( ! isset( $result__arr[ $a_only_date ][ $a_resource_id ] ) ) { $result__arr[ $a_only_date ][ $a_resource_id ] = array(); }

					// Check all TIMES for  SAME  booking:
					$all_seconds_for_this_booking_arr = array();

					foreach ( $a_seconds_arr_in_1_booking as $a_second => $a_booking_obj ) {     //                      0 =>  stdObj( booking_date obj from DB )
						$all_seconds_for_this_booking_arr[] = $a_second;
					}

					/**
					 * [ 36011 ] -> '36011 - 86392'     |       [36011, 43192]  ->  '36011 - 43192'
					 *
					 * In case if we have only start time or end time for specific SAME Booking,  then we need to  fill  times:  .. - 10:00  or 14:00 - ..
					 */
					$all_seconds__key__time_range = wpbc__get_time_range__from_times_arr( $all_seconds_for_this_booking_arr );


					// For debugging only ! -------------------------------------------------------------------------------
					$test_unexpected = explode( '-', $all_seconds__key__time_range );
					if ( count( $test_unexpected ) > 2 ) {
						echo "WPBC. Unexpected situation. We have more than 2 times for the booking {$a_booking_id}: {$all_seconds__key__time_range}";
						debuge( '$a_only_date, $a_resource_id, $a_booking_id, $all_seconds_for_this_booking_arr',
							     $a_only_date, $a_resource_id, $a_booking_id, $all_seconds_for_this_booking_arr, __FILE__, __LINE__ );
					}
					// For debugging only ! -------------------------------------------------------------------------------

					$result__arr[ $a_only_date ][ $a_resource_id ][ $all_seconds__key__time_range ] = $a_booking_obj;
				}
			}
		}

		return $result__arr;
	}


		/**
		 *  Transform [ 36011 ] -> '36011 - 86392'     |       [36011, 43192]  ->  '36011 - 43192'
		 *
		 * @param array $all_seconds_for_this_booking_arr   [ 36011 ]           [36011, 43192]
		 *
		 * @return string                                   '36011 - 86392'     '36011 - 43192'
		 */
		function wpbc__get_time_range__from_times_arr( $all_seconds_for_this_booking_arr ){

	        // In case if we have only start time or end time for specific SAME Booking,  then we need to  fill  times:  .. - 10:00  or 14:00 - ..
			if  ( 1 == count( $all_seconds_for_this_booking_arr ) )  {

				$seconds__this__booking   = $all_seconds_for_this_booking_arr[0];
				//$is_check_in_out__or_full = $seconds__this__booking % 10;                                                       // 1, 2  (not 0)

				$is_check_in_out__or_full = substr( ( (string) $seconds__this__booking ), -1 );                           // '1', '2'  (not '0')

				if ( '1' == $is_check_in_out__or_full ) {
					$all_seconds_for_this_booking_arr = array( $seconds__this__booking, WPBC_FULL_DAY_TIME_OUT );   // 24 hours  - 10 seconds + 2 seconds (2 sec. - because check  out)
				}
				if ( '2' == $is_check_in_out__or_full ) {
					$all_seconds_for_this_booking_arr = array( WPBC_FULL_DAY_TIME_IN, $seconds__this__booking );    // + 1 second (1 sex. - because check  in)
				}
			}

			if  ( count( $all_seconds_for_this_booking_arr ) > 2 )  {
				// Non standard situation ??? !!!
			}

			$all_seconds__key__time_range = implode( ' - ', $all_seconds_for_this_booking_arr );

			return $all_seconds__key__time_range;
		}



// ---------------------------------------------------------------------------------------------------------------------
// Get READABLE_DATES_ARR  & create WPBC_BOOKING_DATE   for each  bookings in   [ > resource_id < ][ > booking_id < ][ ... ]
// ---------------------------------------------------------------------------------------------------------------------

// GOOD
/**
 * Loop all Resources / Bookings  to set 'WPBC_BOOKING_DATE' Objects:  [ > resource_id < ][ > booking_id < ] => ['__summary__booking']['sql__booking_dates__arr']['_readable_dates'] = [ 2023-09-01: [ 0: "00:00:01 - 24:00:00" ] , ... ]
 *
 * @param $resources__booking_id__obj     =   [ > resource_id < ][ > booking_id < ] => obj( ... )
 *
 * @return $resources__booking_id__obj = [
 *                                          3 = [
 *                                                  62 = {stdClass}
 *                                                  63 = {
 *                                                          booking_date = "2023-09-01 00:00:00"
 *                                                          approved = "0"
 *                                                          date_res_type = null
 *                                                          booking_id = "63"
 *                                                          form = "text^selected_short_dates_hint3^September 1, 2023...
 *                                                          parent = "0"
 *                                                          prioritet = "30"
 *                                                          type = "3"
 *                                                          __summary__booking = [
 *                                                                              sql__booking_dates__arr = [
 *
 *                                              ....
 */
function wpbc__in_all_bookings__create_WPBC_BOOKING_DATE( $resources__booking_id__obj ){


	// [ > resource_id < ][ > booking_id < ] => obj(  ['__summary__booking']['sql__booking_dates__arr'] [  ... ,  [ ' >seconds< '] => ' > SQL DATE < ' , ...  ]  )
	foreach ( $resources__booking_id__obj as $resource_id => $bookings_arr ) {

		foreach ( $bookings_arr as $booking_id => $booking_obj ) {

			$readable_datestimeslots_arr = wpbc__get__readable_dates_arr__from_timestamp_arr( $booking_obj->__summary__booking[ 'sql__booking_dates__arr' ] );

			// New Dates Object     -       for this booking ID
			$boking_dates_obj = new WPBC_BOOKING_DATE( $readable_datestimeslots_arr );

			$resources__booking_id__obj[$resource_id][$booking_id]->__summary__booking[ '__dates_obj' ] = $boking_dates_obj;

			if ( $boking_dates_obj->is_debug ) {
				$resources__booking_id__obj[$resource_id][$booking_id]->__summary__booking[ '__dates_obj__24_hour_arr' ]    = $boking_dates_obj->loop_all_dates( 'get_times__as_24_hour_arr' );
				$resources__booking_id__obj[$resource_id][$booking_id]->__summary__booking[ '__dates_obj__seconds_arr' ]    = $boking_dates_obj->loop_all_dates( 'get_times__as_seconds_arr' );
				$resources__booking_id__obj[$resource_id][$booking_id]->__summary__booking[ '__dates_obj__timestamps_arr' ] = $boking_dates_obj->loop_all_dates( 'get_times__as_timestamps_arr' );
			}

		}
	}

	return $resources__booking_id__obj;
}


// =====================================================================================================================
// Transform SQL dates INTO  Readable time-slots by Dates
//
// All these below 5 functions required for transformation booked SQL dates:    [  1690884011 : "2023-08-01 10:00:01", ... ]
// Into  readable time slots by  dates:                                         [ '2023-08-01': [  "10:00:01 - 12:00:02"  ], '2023-08-05': [ "10:00:01 - 12:00:02"  ]  ]
// =====================================================================================================================

	// GOOD
	/**
	 * Transform SQL dates INTO  Readable time-slots by Dates.      Transform linear [ 1690884011 = "2023-08-01 10:00:01" , ... ]    ->   [ '2023-08-01': [  0: "10:00:01 - 12:00:02" ], '2023-08-05': [ 0:"10:00:01 - 12:00:02" ] ...]
	 *
	 * @param array $sql_dates_arr    [
	 *  										 1690884011 = "2023-08-01 10:00:01"
	 *	    									 1690891192 = "2023-08-01 12:00:02"
	 *                                           ...
	 *                                ]
	 *
	 * @return array
	 *                                [
	 *                                    2023-08-01 = [  0 = "10:00:01 - 12:00:02"  ]
	 *                                    2023-08-05 = [  0 = "10:00:01 - 12:00:02"  ]
	 *                                ]
	 *                         OR
	 *                                [
	 *                                    2023-09-01 = [  0 = "00:00:01 - 24:00:00"  ]
	 *                                    2023-09-03 = [  0 = "00:00:00 - 24:00:00"  ]
	 *                                    2023-09-14 = [  0 = "00:00:00 - 24:00:02"  ]
	 *                                ]
	 *
	 */
	function wpbc__get__readable_dates_arr__from_timestamp_arr( $sql_dates_arr ){

		// Full  Dates
		$is_all_dates_full = wpbc_is_all__fulldays__in_dates_arr( $sql_dates_arr );
		if ( $is_all_dates_full ) {
			$sql_dates_arr = wpbc__add_to__fulldays__check_in_out( $sql_dates_arr );
		}

		/**
		 * Convert timestamps to                ->   [   '2023-08-01' : [ 36011 = "10:00:01", 43192 = "12:00:02" ]     ,   '2023-08-05' : [...] ...   ]
		 */
		$times_arr___by_dates = wpbc__transform__timestamp_arr__in__times_by_dates__arr(  $sql_dates_arr );

		/**
		 * Convert to our concept times:        ->   [   '2023-08-01' : [  0 = "10:00:01 - 12:00:02"  ],    '2023-08-05' : [  0 = "10:00:01 - 12:00:02"  ]   ]
		 */
		$times_arr___by_dates = wpbc__transform__times_by_dates__in__readable_dates_arr(  $times_arr___by_dates );

		return $times_arr___by_dates;
	}


		// GOOD
		/**
		 * Convert times by dates:   [ '2023-08-01': [ 36011: "10:00:01", ... ] ... ]   ->   [ '2023-08-01': [  0: "10:00:01 - 12:00:02" ], '2023-08-05': [ 0:"10:00:01 - 12:00:02" ] ...]
		 *
		 * @param $times_arr___by_dates = [
		 *                                    2023-08-01 = [
		 *                                                   36001 = "10:00:01"
		 *                                                   43202 = "12:00:02"
		 *                                                 ]
		 *                                    2023-08-05 = [
		 *                                                   36001 = "10:00:01"
		 *                                                   43202 = "12:00:02"
		 *                                                 ]
		 *                                ]
		 *
		 * @return array
		 *                                [
		 *                                    2023-08-01 = [  0 = "10:00:01 - 12:00:02"  ]
		 *                                    2023-08-05 = [  0 = "10:00:01 - 12:00:02"  ]
		 *                                ]
		 *                         OR
		 *                                [
		 *                                    2023-09-01 = [  0 = "00:00:01 - 24:00:00"  ]
		 *                                    2023-09-03 = [  0 = "00:00:00 - 24:00:00"  ]
		 *                                    2023-09-14 = [  0 = "00:00:00 - 24:00:02"  ]
		 *                                ]
		 *
		 *
		 *
		 *
		 * from this:
		 *
		 *   $times_arr___by_dates = [
		 *                              2023-09-01 = [
		 *                                              1 = "00:00:01"
		 *                                           ]
		 *                              2023-09-03 = [
		 *                                              0 = "00:00:00"
		 *                                           ]
		 *                              2023-09-14 = [
		 *                                              86402 = "24:00:02"
		 *                                           ]
		 *                          ]
		 *      OR this:
		 *                          [
		 *                              2023-08-01 = [
		 *                                              36001 = "10:00:01"
		 *                                              43202 = "12:00:02"
		 *                                           ]
		 *                              2023-08-05 = [
		 *                                              36001 = "10:00:01"
		 *                                              43202 = "12:00:02"
		 *                                           ]
		 *                          ]
		 *              OR
		 *                          [
		 *                              2023-09-26 = [
		 *                                              50401 = "14:00:01"
		 *                                           ]
		 *                              2023-09-27 = [
		 *                                              0 = "00:00:00"
		 *                                           ]
		 *                              2023-09-28 = [
		 *                                              43202 = "12:00:02"
		 *                                           ]
		 *                          ]
		 *   INTO
		 *
		 *              - Time-Slots     A  - B                      '10:00:01 - 12:00:02'
		 *
		 *              - Check in       A  - ...                    '14:00:01 - 24:00:00'
		 *              - Check out      ... -  B                    '00:00:00 - 12:00:02'
		 *
		 *                  - Full day       ... - ...                   '00:00:00 - 24:00:00'
		 *
		 *                  - Full IN       ..1 - ...                    '00:00:01 - 24:00:00'
		 *                  - Full OUT       ... - ..2                   '00:00:00 - 24:00:02'
		 *
		 */
		function wpbc__transform__times_by_dates__in__readable_dates_arr( $times_arr___by_dates ) {


			foreach ( $times_arr___by_dates as $only_date_key => $times_arr ) {

				/**
				 *  Because it's dates_times from 1 booking, then in each date we have only 1 or 2 times.
				 */

				if ( 1 == count( $times_arr___by_dates[ $only_date_key ] ) ) {

					// One Loop only,  to  get  the $time_only_24hours value
					foreach ( $times_arr___by_dates[ $only_date_key ] as $time_only_seconds => $time_only_24hours ) {

						if ( '00:00:00' == $time_only_24hours ) {                                                       // Full Day

							$times_arr___by_dates[ $only_date_key ] = array( '00:00:00', '24:00:00' );

						} else if( '00:00:01' == $time_only_24hours ) {                                                 // Check In  ___ full day

							$times_arr___by_dates[ $only_date_key ] = array( '00:00:01', '24:00:00' );

						} else if( '24:00:02' == $time_only_24hours ) {                                                 // Check Out ___ full day

							$times_arr___by_dates[ $only_date_key ] = array( '00:00:00', '24:00:02' );
						} else {

							// Check in / out
							$is_check_in_out__or_full = substr( $time_only_24hours, -1 );                               // '1', '2'  (not '0')

							if ( '1' == $is_check_in_out__or_full ) {                                                    // Check In

								$times_arr___by_dates[ $only_date_key ] = array( $time_only_24hours, '24:00:00' );

							} else if ( '2' == $is_check_in_out__or_full ) {                                            // Check Out

								$times_arr___by_dates[ $only_date_key ] = array( '00:00:00', $time_only_24hours );

							} else {                                                                                    // Unexpected ?
								echo "WPBC. Unexpected data value: {$time_only_24hours} Timestamp ended with value different than: 1|2 for the day {$only_date_key} in one booking ";
							}

						}
					}
				}


				if ( 2 == count( $times_arr___by_dates[ $only_date_key ] ) ) {

					// Time slots       or already defined      check in / out      times
					$times_his_values = implode( ' - ', $times_arr___by_dates[ $only_date_key ] );

					$times_arr___by_dates[ $only_date_key ]   = array();
					$times_arr___by_dates[ $only_date_key ][] = $times_his_values;

				} else {

					// Something wrong ????  Check it.
					echo "WPBC. Unexpected data value. We have more than 2 time slots for single day {$only_date_key} in one booking ";
					debuge( $times_arr___by_dates, __FILE__, __LINE__ );
				}
			}

			return $times_arr___by_dates;
		}


		// GOOD
		/**
		 *  Transform linear [ 1690884011 = "2023-08-01 10:00:01" , ... ]    ->   [   '2023-08-01' : [ 36011 = "10:00:01", 43192 = "12:00:02", ... ]  , '2023-08-05' : [...] ...   ]
         *
		 * @param array $datetimestamps_datesql_arr [
		 *												1690884011 = "2023-08-01 10:00:01"
		 *												1690891192 = "2023-08-01 12:00:02"
		 *												1691229611 = "2023-08-05 10:00:01"
		 *												1691236792 = "2023-08-05 12:00:02"
		 *                                          ]
		 *
		 * @return array                    [
		 *										2023-08-01 = [
		 *													    36011 = "10:00:01"
		 *													    43192 = "12:00:02"
		 *										2023-08-05 = [
		 *													    36011 = "10:00:01"
		 *													    43192 = "12:00:02"
		 *                                  ]
		 */
		function wpbc__transform__timestamp_arr__in__times_by_dates__arr( $datetimestamps_datesql_arr ) {

			ksort( $datetimestamps_datesql_arr );

			$by_dates__timestamp_datesql__arr = array();

			foreach ( $datetimestamps_datesql_arr as $this_datetime_stamp => $this_date_sql ) {

				//    '2023-08-01'    '10:00:01'
				list( $only_day_sql, $only_time_sql ) = explode( ' ', $this_date_sql );

				$only_day_sql  = trim( $only_day_sql );                                                                     // '2023-08-01'
				$only_time_sql = trim( $only_time_sql );                                                                    // '10:00:01'

				if ( ! isset( $by_dates__timestamp_datesql__arr[ $only_day_sql ] ) ) {
					$by_dates__timestamp_datesql__arr[ $only_day_sql ] = array();                                           // [   '2023-08-01': []   ]
				}

				$only_time_in_sec = wpbc_convert__time_his__to_seconds( $only_time_sql, false );                            // 36001,            false <-  $is_apply__check_in_out__10s

				//                                  '2023-08-01'            36001            '10:00:01'
				$by_dates__timestamp_datesql__arr[ $only_day_sql ][ $only_time_in_sec ] = $only_time_sql;
			}

			return $by_dates__timestamp_datesql__arr;
		}


		// GOOD
		/**
		 * Add to  the first  and last  dates the check in/out times
		 *
		 * @param array $sql_dates_arr
		 *
		 * @return array $sql_dates_arr
		 */
		function wpbc__add_to__fulldays__check_in_out( $sql_dates_arr ){

			// Add check in/out to  first  and last  dates !
			$is_apply__check_in_out__10s = false;

			// EVEN if we have only 1 day,  it has to work! from
			$all_timestamps_arr = array_keys( $sql_dates_arr );


			// CHECK_IN date,  -  lowest timestamp:  -------------------------------------------------------------------
			$check_in_timestamp = min( $all_timestamps_arr );
			// Remove this date
			if ( isset( $sql_dates_arr[ $check_in_timestamp ] ) ) { unset( $sql_dates_arr[ $check_in_timestamp ] ); }

			// Add new date:             see definition  of                 WPBC_FULL_DAY_TIME_IN       <-      00:00:01
			$real__check_in__date_sql     = date( 'Y-m-d', $check_in_timestamp ) . ' 00:00:01';
			$real__check_in_timestamp_new = wpbc_convert__sql_date__to_seconds( date( 'Y-m-d', $check_in_timestamp ) . ' 00:00:01', $is_apply__check_in_out__10s );
			$sql_dates_arr[ $real__check_in_timestamp_new ]  = $real__check_in__date_sql;


			// CHECK_OUT date,  - highest timestamp:  ------------------------------------------------------------------
			$check_out_timestamp = max( $all_timestamps_arr );
			// Remove this date
			if ( isset( $sql_dates_arr[ $check_out_timestamp ] ) ) {
				unset( $sql_dates_arr[ $check_out_timestamp ] );
			}
			// Add new date:             see definition  of                 WPBC_FULL_DAY_TIME_OUT      <-      23:59:52
			$real__check_out__date_sql     = date( 'Y-m-d', $check_out_timestamp ) . ' 24:00:02';
			$real__check_out_timestamp_new = wpbc_convert__sql_date__to_seconds( date( 'Y-m-d', $check_out_timestamp ) . ' 23:59:52', $is_apply__check_in_out__10s );
			$sql_dates_arr[ $real__check_out_timestamp_new ] = $real__check_out__date_sql;

			// Sort such dates again, because we changed the order   ---------------------------------------------------
			ksort( $sql_dates_arr );

			return $sql_dates_arr;
		}


		// GOOD
		/**
		 * Check  if all  dates in array it is full  dates  e.g.   2023-07-25 00:00:00
		 * and not the check  in/out,  such  as 2023-07-25 12:00:02
		 *
		 * @param array $booking_sqldates_arr  [
		 *  										 1690884011 = "2023-08-01 10:00:01"
		 *	    									 1690891192 = "2023-08-01 12:00:02"
		 *                                           ...
		 *                                     ]
		 *
		 * @return bool
		 */
		function wpbc_is_all__fulldays__in_dates_arr( $booking_sqldates_arr ) {

			$is_all_dates_full = true;

			foreach ( $booking_sqldates_arr as $date_sql ) {

				$is_check_in_out__or_full = (string) $date_sql;
				$is_check_in_out__or_full = substr( $is_check_in_out__or_full, - 1 );                                   // '0', '1', '2' from   "2023-08-01 12:00:02"
				if ( '0' !== $is_check_in_out__or_full ) {
					$is_all_dates_full = false;
					break;
				}
			}

			return $is_all_dates_full;
		}

// =====================================================================================================================


/**
 * Convert  [ "2023-10-20", "2023-10-25", "2023-11-23" ]  -> [ '20.10.2023', '25.10.2023', '23.11.2023' ]
 *
 * @param $dates_arr__yyyy_mm_dd            [ "2023-10-20", "2023-10-25", "2023-11-23" ]
 *
 * @return []                               [ '20.10.2023', '25.10.2023', '23.11.2023' ]
 */
function wpbc_convert_dates_arr__yyyy_mm_dd__to__dd_mm_yyyy( $dates_arr__yyyy_mm_dd ) {

	$dates_arr__dd_mm_yyyy = array_map( function ( $value ) {
																$value_arr = explode( '-', $value );
																return ( count( $value_arr ) > 2 )
																		?  $value_arr[2] . '.' . $value_arr[1] . '.' . $value_arr[0]
																		: '';
															}
										, $dates_arr__yyyy_mm_dd );    // [ "2023-10-20", "2023-10-25", "2023-11-23" ]

	$dates_arr__dd_mm_yyyy = array_filter( $dates_arr__dd_mm_yyyy );                                                    // All entries of array equal to FALSE (0, '', '0' ) will be removed.
	return $dates_arr__dd_mm_yyyy;
}


/**
 * Convert  '20.10.2023, 25.10.2023, 23.10.2023'   ->   '2023-10-20,2023-10-23,2023-10-25'
 *
 * or even: '20.10.2023 - 23.10.2023'   ->   '2023-10-20,2023-10-21,2023-10-22,2023-10-23'
 *
 * @param $str_dates__dd_mm_yyyy            '20.10.2023, 25.10.2023, 23.10.2023'
 *
 * @return string                           '2023-10-20,2023-10-23,2023-10-25'
 */
function wpbc_convert_dates_str__dd_mm_yyyy__to__yyyy_mm_dd( $str_dates__dd_mm_yyyy ) {

	$str_dates__dd_mm_yyyy = str_replace( '|', ',', $str_dates__dd_mm_yyyy );       // Check  this for some old versions of plugin

	if ( strpos( $str_dates__dd_mm_yyyy, ' - ' ) !== false ) {                      // Recheck for any type of Range Days Formats
		$arr_check_in_out_dates = explode( ' - ', $str_dates__dd_mm_yyyy );
		$str_dates__dd_mm_yyyy  = wpbc_get_comma_seprated_dates_from_to_day( $arr_check_in_out_dates[0], $arr_check_in_out_dates[1] );
	}

	$dates_arr__dd_mm_yyyy = explode( ',', $str_dates__dd_mm_yyyy );                         // Create dates Array

	$dates_arr__yyyy_mm_dd = array_map( function ( $value ) {
																$value = trim( $value );
																$value_arr = explode( '.', $value );
																return ( count( $value_arr ) > 2 )
																		? $value_arr[2] . '-' . $value_arr[1] . '-' . $value_arr[0]
																		: '';
															}
										, $dates_arr__dd_mm_yyyy );    // [ '20.10.2023', '25.10.2023', '23.11.2023' ]
	$dates_arr__yyyy_mm_dd = array_filter( $dates_arr__yyyy_mm_dd );                                                    // All entries of array equal to FALSE (0, '', '0' ) will be removed.
	// Remove duplicates
	$dates_arr__yyyy_mm_dd = array_unique( $dates_arr__yyyy_mm_dd );

	// Sort Dates
	sort( $dates_arr__yyyy_mm_dd );

	$dates_str__yyyy_mm_dd = implode( ',', $dates_arr__yyyy_mm_dd );

	return $dates_str__yyyy_mm_dd;                                     // '2023-10-20,2023-10-23,2023-10-25'
}

