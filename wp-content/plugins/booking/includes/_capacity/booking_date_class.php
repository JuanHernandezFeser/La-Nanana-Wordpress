<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4


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

class WPBC_BOOKING_DATE {

	public $readable_dates;

	public $is_debug;

	/**
	 * Define booking date object  by readable dates array  >>  [ '2023-08-01': [  0: "10:00:01 - 12:00:02" ], '2023-08-05': [ 0:"10:00:01 - 12:00:02" ] ...]
	 * @param $boking_dates_arr
	 */
	function __construct( $boking_dates_arr ) {

		//  [ '2023-08-01': [  0: "10:00:01 - 12:00:02" ], '2023-08-05': [ 0:"10:00:01 - 12:00:02" ] ...]
		$this->readable_dates = $boking_dates_arr;

		$this->is_debug = false;     // Gathering additional  data  into the variables
	}


		/**
		 * Is this readable_time 'Check In'         '10:00:01 - 12:00:02'  |  '14:00:01 - 24:00:00'  |  '00:00:01 - 24:00:00'       ->      true
		 *
		 * @param $readable_time_range  '10:00:01 - 12:00:02'
		 *
		 * @return bool                 true | false
		 */
		public function is_time_check_in( $readable_time_range ){

			$time_24_hours_arr  = explode( '-', $readable_time_range );

			$time_24_hours_slot = (string) trim( $time_24_hours_arr[0] );

			$is_check_in_out__or_full = substr( $time_24_hours_slot, -1 );                                              // '1', '2'  (not '0')

			$result = ( '1' === $is_check_in_out__or_full ) ? true : false;

			return $result;
		}


		/**
		 * Is this readable_time 'Check Out'        '10:00:01 - 12:00:02'  |  '00:00:00 - 12:00:02'  |  '00:00:00 - 24:00:02'       ->      true
		 *
		 * @param $readable_time_range  '10:00:01 - 12:00:02'
		 *
		 * @return bool                 true | false
		 */
		public function is_time_check_out( $readable_time_range ){

			$time_24_hours_arr  = explode( '-', $readable_time_range );

			$time_24_hours_slot = (string) trim( $time_24_hours_arr[1] );

			$is_check_in_out__or_full = substr( $time_24_hours_slot, -1 );                                              // '1', '2'  (not '0')

			$result = ( '2' === $is_check_in_out__or_full ) ? true : false;

			return $result;
		}


		/**
		 * Is this readable_time 'Full Day'        '00:00:00 - 24:00:00'  =>  true
		 *
		 * @param $readable_time_range  '10:00:01 - 12:00:02'
		 *
		 * @return bool                 true | false
		 */
		public function is_time_full_day( $readable_time_range ){

			$result = ( '00:00:00 - 24:00:00' === $readable_time_range ) ? true : false;

			return $result;
		}



	/**
	 * Loop all  dates,  and    APPLY_SPECIFIC_ACTION   to      Times Arr   of specific dates
	 *
	 * @param string $filter_action     'get_times__as_24_hour_arr' | 'get_times__as_seconds_arr' | 'get_times__as_timestamps_arr'
	 *
	 * @return array
	 */
	public function loop_all_dates( $filter_action ){

		$result = array();

		foreach ( $this->readable_dates as $only_date_sql => $readable_times_arr ) {

			switch ( $filter_action ) {

			    case 'get_times__as_24_hour_arr':

					$result[$only_date_sql] = $this->get__filtered_times__as_arr( $only_date_sql );
			        break;

			    case 'get_times__as_seconds_arr':

				    $result[ $only_date_sql ] = $this->get__filtered_times__as_arr( $only_date_sql,    array( 'filter' => 'as_seconds' )   );
			        break;

			    case 'get_times__as_timestamps_arr':

				    $result[ $only_date_sql ] = $this->get__filtered_times__as_arr( $only_date_sql,    array( 'filter' => 'as_timestamps' )   );
			        break;

			    default:
			       // Default
			}
	    }

		return $result;
	}


	/**
	 * Get times in different formats:       [ "10:00:01", "12:00:02" ]      |       [ 36001, 43202 ]
	 *
	 * @param $date_sql     '2023-07-30'
	 * @param $params       []                              |       [ 'filter' =>  'as_seconds' ]
	 *
	 * @return array        [ "10:00:01", "12:00:02" ]      |       [ 36001, 43202 ]
	 */
	protected function get__filtered_times__as_arr( $date_sql, $params = array() ){

		if ( ! isset( $this->readable_dates[ $date_sql ] ) ) {
			return array();
		}

		$defaults = array(
							'filter' => ''
						);
		$params   = wp_parse_args( $params, $defaults );


		$times_as_arr = array();
		foreach ( $this->readable_dates[ $date_sql ] as $time_range ) {

			$time_24_hours_arr  = explode( '-', $time_range );

			foreach ( $time_24_hours_arr as $time_24_hour ) {           //  [ '10:00:01', '14:00:02' ]

				$time_24_hour = trim( $time_24_hour );

				switch ( $params['filter'] ) {

				    case 'as_seconds':

						$filtered_time = $this->transform__24_hours__in__seconds( $time_24_hour );
				        break;

				    case 'as_timestamps':

						$filtered_time = $this->transform__24_hours__in__timestamp( $date_sql, $time_24_hour );
				        break;

				    default:
				       $filtered_time = $time_24_hour;
				}

				$times_as_arr[] = $filtered_time;
			}
		}

		return $times_as_arr;
	}


		// -------------------------------------------------------------------------------------------------------------

		/**
		 * Get timestamp  from  SQL DATE    '2023-07-30 10:00:01'    ->      1690884001
		 *
		 * @param $sql_date_ymd_his '2023-08-02 22:14:01'
		 *
		 * @return false|int
		 */
		protected function transform__sql_date_ymd_his__in__timestamp( $sql_date_ymd_his ){

			$date_ymd__in_seconds = strtotime( $sql_date_ymd_his );

			return $date_ymd__in_seconds;
		}

		/**
		 * Get timestamp    '2023-08-01', '10:00:01'    ->      1690884001
		 *
		 * @param $only_date_sql '2023-08-01'
		 * @param $time_24_hour  '10:00:01'
		 *
		 * @return float|int        1690884001
		 */
		protected function transform__24_hours__in__timestamp( $only_date_sql, $time_24_hour ){

			$date_ymd__in_seconds = $this->transform__only_date_ymd__in__timestamp( $only_date_sql );

			$time_in_seconds = $this->transform__24_hours__in__seconds( $time_24_hour );

			$timestamp = $date_ymd__in_seconds + $time_in_seconds;

			return $timestamp;
		}


		/**
		 * Get timestamp  from  SQL only DATE    '2023-07-30'    ->      1690884001
		 *
		 * @param $only_date_sql
		 *
		 * @return false|int
		 */
		protected function transform__only_date_ymd__in__timestamp( $only_date_sql ){

			// $date_ymd__in_seconds = wpbc_convert__date_ymd__to_seconds( $date_sql );

			$date_ymd__in_seconds = strtotime( $only_date_sql . ' 00:00:00' );

			return $date_ymd__in_seconds;
		}


		/**
		 * Get SQL DATE from timestamp    1690884001    ->      '2023-07-30 10:00:01'
		 *
		 * @param int $in_seconds
		 * @param string $sql_date_format       'Y-m-d H:i:s'   |   'Y-m-d 00:00:00'
		 *
		 * @return false|string
		 */
		protected function transform__timestamp__to_sql_date( $in_seconds, $sql_date_format = 'Y-m-d H:i:s' ){

			$sql_date = date( $sql_date_format, $in_seconds );

			return $sql_date;
		}


		// -------------------------------------------------------------------------------------------------------------

		/**
		 * Get SQL TIME  from SECONDS       43201 -> '10:00:02'
		 *
		 * @param int $in_seconds   43201
		 *
		 * @return string       '10:00:02'
		 */
		protected function transform__seconds__in__24_hours_his( $in_seconds ){

			return wpbc_transform__seconds__in__24_hours_his( $in_seconds );
		}


		/**
		 * Get seconds    '10:00:01'    ->      36001
		 *
		 * @param $time_24_hour    '10:00:01'
		 *
		 * @return float|int        36001
		 */
		protected function transform__24_hours__in__seconds( $time_24_hour ){

			$time_24_hour_arr = explode( ':', $time_24_hour );

			// 43202
			$in_seconds =   intval( $time_24_hour_arr[0] ) * 60 * 60        // Hours
			              + intval( $time_24_hour_arr[1] ) * 60             // Minutes
			              + intval( $time_24_hour_arr[2] );                 // Seconds

			return $in_seconds;
		}


	/**
	 * Convert Readable dates to  SQL dates array for using in Calendar:    [ "2023-09-30":["10:00:01 - 24:00:00"],"2023-10-01":["00:00:00 - 24:00:00"], .. ] -> [ [1696068001] => 2023-09-30 10:00:01, [1696118400] => 2023-10-01 00:00:00, [1696204800] => 2023-10-02 00:00:00 ... ]
	 *
	 * @return array    [
	 *                      [1696068001] => 2023-09-30 10:00:01
	 *                      [1696118400] => 2023-10-01 00:00:00
	 *                      [1696204800] => 2023-10-02 00:00:00
	 *                      [1696334402] => 2023-10-03 12:00:02
	 *                  ...
	 *                  ]
	 */
	public function convert_readable_dates__into__sql_dates__arr(){

		$sql_dates_times_arr = array();

		// Get unavailable extended time_ranges arr
		foreach ( $this->readable_dates as $only_date_sql => $readable_times_arr ) {

			foreach ( $this->readable_dates[ $only_date_sql ] as $readable_time_range ) {

				$time_24_hours_arr  = explode( ' - ', $readable_time_range );

				$only_date_timestamp = $this->transform__only_date_ymd__in__timestamp( $only_date_sql );
				$check_in_seconds    = $this->transform__24_hours__in__seconds( $time_24_hours_arr[0] );
				$check_out_seconds   = $this->transform__24_hours__in__seconds( $time_24_hours_arr[1] );

				$is_add_check_in = true;
				$is_add_check_out = true;

				if (
						  ( '00:00:00 - 24:00:00' == $readable_time_range )                                             // Full Day
					 ||   ( $check_out_seconds >= 86400 )                                                               // Check out >= '24:00:00' then skip end time
				){
					$is_add_check_out = false;
				}

				if (
				     ( '00:00:00' == $time_24_hours_arr[0] ) && ( $check_out_seconds > 0 ) && ( $check_out_seconds < 86400 )   // '00:00:00 - 12:00:02'
				){
					$is_add_check_in = false;
				}


				// Check In
				if ( $is_add_check_in ) {
					$sql_dates_times_arr[ $only_date_timestamp + $check_in_seconds ] = $only_date_sql . ' ' . $time_24_hours_arr[0];
				}

				// Check  out
				if ( $is_add_check_out ) {
					$sql_dates_times_arr[ $only_date_timestamp + $check_out_seconds ] = $only_date_sql . ' ' . $time_24_hours_arr[1];
				}
			}
		}

		return $sql_dates_times_arr;
	}
}
