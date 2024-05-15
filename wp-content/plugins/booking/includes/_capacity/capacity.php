<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4


// <editor-fold     defaultstate="collapsed"                        desc="  ==  SQL - Get Dates array from DB  ==  "  >

	// -----------------------------------------------------------------------------------------------------------------
	// SQL - Get Dates array from DB
	// -----------------------------------------------------------------------------------------------------------------

	/**
	 * Get SQL dates array from DB as:   [   [0] => std_object( booking_date:'2023-08-08 10:00:01', approved:0, booking_id:45, type:1 ... ),  [1] => std_object( ), ... ]
	 *
	 * @param array $params   =   array(
				                      'approved'            => 'all' | 0 | 1                      default 'all'
								    , 'resource_id'         => array, CSD, int                   if empty then default '1'
								    , 'additional_bk_types' => array, CSD, int      OPTIONAL               default array()
								    , 'skip_booking_id'     => CSD                                default ''
				                )
	 *
	 * @return array            Array (  [0] => stdClass Object (   [booking_date] => 2023-08-08 10:00:01
														            [approved] => 0
														            [date_res_type] =>
														            [booking_id] => 45
														            [form] => select-one^rangetime2^10:00 - 12:00~text^selected_short_dates_hint2^...
														            [parent] => 0
														            [prioritet] => 20
														            [type] => 2
														        )
									    [1] => stdClass Object (
														            [booking_date] => 2023-08-08 10:00:01
														            [approved] => 0
														            [date_res_type] =>
														            [booking_id] => 46
														            [form] => select-one^rangetime10^10:00 - 12:00~text^selected_short_dates_hint10^...
														            [parent] => 2
														            [prioritet] => 1
														            [type] => 10
														        ), ...
	 *
	 *
	 * Example:

			$sql_dates_params = array(
								  'approved'        => ( ( 'On' == get_bk_option( 'booking_is_show_pending_days_as_available' ) ) ? '1' : 'all' )
								, 'resource_id'     => $type_id
								, 'skip_booking_id' => wpbc_get_booking_id__from_hash_in_url() 								// int | ''		// Booking IDs  to skip getting from DB
							);
			$dates_approve = wpbc__sql__get_booking_dates( $sql_dates_params );

	 */
	function wpbc__sql__get_booking_dates( $params  ){

		//TODO:  think about pre-caching SQL request for all  booking resources - useful  for [bookingselect  shortcode ...]          $params['resource_id'] = implode(',',range(1,17));

		// <editor-fold     defaultstate="collapsed"                        desc=" = CACHE - GET    ::    check if such request was cached and get it = "  >

		$params_for_cache = maybe_serialize( $params );
		$cache_result = wpbc_cache__get( 'wpbc__sql__get_booking_dates', $params_for_cache );
		if ( ! is_null( $cache_result ) ) {
			return $cache_result;
		}

		// </editor-fold>

		global $wpdb;

	    $defaults = array(
			              'dates_to_check'      => 'CURDATE'                                    //  'CURDATE'  |  'ALL'  |  [ '2023-07-15' , '2023-07-21' ]
	                    , 'approved'            => 'all'            // 'all' | 0 | 1
					    , 'resource_id'         => ''               // INT
					    , 'additional_bk_types' => array()          // arrays only
					    , 'skip_booking_id'     => ''               // CSD              '3,5'
		                , 'as_single_resource'  => false            // get dates as for 'single resource' or 'parent' resource including bookings in all 'child booking resources'
	                );
	    $params   = wp_parse_args( $params, $defaults );


		// Convert $params['additional_bk_types']  and $params['resource_id'] to  one CSD variable   or default '1' if all empty
		$resource_id__arr = wpbc_get_unique_array_of_resources_id( $params['resource_id'], $params['additional_bk_types'] );
		$resource_id__csd = implode( ',', $resource_id__arr );

		// S a n i t i z e
		$resource_id__csd          = wpbc_sanitize_digit_or_csd( $resource_id__csd );
		$params['skip_booking_id'] = wpbc_sanitize_digit_or_csd( $params['skip_booking_id'] );
		$params['approved']        = ( 'all' == $params['approved'] ) ? 'all' : intval( $params['approved'] );


		$sql_trash_bookings    = ' AND bk.trash != 1 ';
		$sql_skip_bookings     = (    '' == $params['skip_booking_id'] ) ? '' : " AND dt.booking_id NOT IN ( {$params['skip_booking_id']} ) ";
		$sql_approved_bookings = ( 'all' == $params['approved'] )        ? '' : " AND dt.approved = {$params['approved']} ";

		$or__sql_child_bookings_4_parent = ( ! $params['as_single_resource'] ) ? " OR bt.parent  IN ( {$resource_id__csd} ) " : '' ;

		$where_dates = wpbc_get__sql_where__for__dates( $params );

		/**
		 * Description:
		 *              OR dt.type_id IN ( {$resource_id__csd} )
		 *                                                          It is means that sometimes bookings can be started in one booking resource,
		 *                                                          and in future dates can be finished in other 'child' booking resource(s).
		 *                                                          It's possible,  when 'main' booking resource,  where booking was started
		 *                                                          has future dates as 'unavailable' or booked
		 *
		 *              OR dt.type_id IN ( {$resource_id__csd} )
		 *                                                          It's means that this booking belong to child booking resource, and do not belong to parent booking resource!
		 */

		if (  class_exists('wpdev_bk_biz_l') ) {

					$my_sql =  "SELECT DISTINCT dt.booking_date, dt.approved, dt.type_id as date_res_type, dt.booking_id, bk.form, bt.parent, bt.prioritet, bt.booking_type_id as type  
				                FROM {$wpdb->prefix}bookingdates as dt 
				
				                    INNER JOIN {$wpdb->prefix}booking as bk  
				                    ON    bk.booking_id = dt.booking_id 
				                    
										INNER JOIN {$wpdb->prefix}bookingtypes as bt  
										ON    bk.booking_type = bt.booking_type_id 
				
				                WHERE 1 = 1       
										{$where_dates} 
										AND (
										           bk.booking_type IN ( {$resource_id__csd} ) 
										        OR dt.type_id IN ( {$resource_id__csd} )        
												{$or__sql_child_bookings_4_parent}
					                        )                       
										{$sql_approved_bookings} 
										{$sql_trash_bookings} 
										{$sql_skip_bookings} 
				                ORDER BY dt.booking_date";

					// Explanation:
					// OR bt.parent  IN ( ... ) -> Bookings from CHILD booking resources
					// OR dt.type_id IN ( ... ) -> Dates from other booking resources, which belong to this booking resource

		} else if ( class_exists('wpdev_bk_biz_m') ) {  // Here we get  form  for booking details.

			$my_sql =  "SELECT DISTINCT dt.booking_date, dt.approved, dt.booking_id, bk.booking_type as type, bk.form 
		                FROM {$wpdb->prefix}bookingdates as dt 
		
		                    INNER JOIN {$wpdb->prefix}booking as bk  
		                    ON    bk.booking_id = dt.booking_id  
		
		                WHERE 1 = 1       
								{$where_dates} 
								AND bk.booking_type IN ( {$resource_id__csd} )                       
								{$sql_approved_bookings} 
								{$sql_trash_bookings} 
								{$sql_skip_bookings} 
		                ORDER BY dt.booking_date";

		} else {                                      // Fast request  only  for dates
			$my_sql =  "SELECT DISTINCT dt.booking_date, dt.approved, dt.booking_id, bk.booking_type as type  
		                FROM {$wpdb->prefix}bookingdates as dt 
		
		                    INNER JOIN {$wpdb->prefix}booking as bk  
		                    ON    bk.booking_id = dt.booking_id  
		
		                WHERE 1 = 1       
								{$where_dates} 
								AND bk.booking_type IN ( {$resource_id__csd} )                       
								{$sql_approved_bookings} 
								{$sql_trash_bookings} 
								{$sql_skip_bookings} 
		                ORDER BY dt.booking_date";
		}

		// Show past bookings, as well
		// $my_sql = str_replace( 'AND dt.booking_date >= CURDATE()', '', $my_sql );

		$sql__dates_obj__arr = $wpdb->get_results( $my_sql );


		// <editor-fold     defaultstate="collapsed"                        desc=" = CACHE - SAVE :: = "  >
		$cache_result = wpbc_cache__save( 'wpbc__sql__get_booking_dates', $params_for_cache, $sql__dates_obj__arr );
		// </editor-fold>

		return $sql__dates_obj__arr;
	}


			/**
			 * Get SQL WHERE condition for Dates
			 *
			 * @param array $params     [ 'dates_to_check' => 'CURDATE' ]   |   [ 'dates_to_check' => 'ALL' ]   |   [ 'dates_to_check' => [ '2023-07-15' , '2023-07-21' ]  ]
			 *
			 * @return string           ' AND (  dt.booking_date >= CURDATE()  ) '
			 */
			function wpbc_get__sql_where__for__dates( $params ){

			    $defaults = array(
					              'dates_to_check' => 'CURDATE'        //  'CURDATE'  |  'ALL'  |  [ '2023-07-15' , '2023-07-21' ]
			                );
			    $params   = wp_parse_args( $params, $defaults );


				if ( 'ALL' == $params['dates_to_check'] ) {                                                             // All  dates           ->   ''

					return   '';

				} else if ( 'CURDATE' == $params['dates_to_check'] ) {                                                  // Current dates        ->   CURDATE()

					return   ' AND (  dt.booking_date >= CURDATE()  ) ' ;

				} else if ( is_array( $params['dates_to_check'] ) ) {                                                   // Specific Date(s)     ->   [ '2023-07-15' , '2023-07-21' ]

					$dates_sql_array = array();

					for( $i = 0; $i <  count( $params['dates_to_check'] ); $i++) {

						$dates_sql_array[] =  "( (  dt.booking_date >= '{$params['dates_to_check'][$i]} 00:00:00'  ) AND (  dt.booking_date <= '{$params['dates_to_check'][$i]} 23:59:59'  ) )";
					}

					$dates_sql_str = implode( ' OR ', $dates_sql_array );

					return   " AND (  {$dates_sql_str}  ) " ;

				}

				// Default CURDATE
				return ' AND (  dt.booking_date >= CURDATE()  ) ';
			}



	/**
	 * Get array of "Booked Dates" as:   ['2023-08-30'][ > resource_id < ][ > time_seconds_range < ] => booking_date_obj[ booking_id:45, approved:1, ...
	 *
	 * @param array $params   array(
	 *				                      'approved'            => 'all'                        // 'all' | 0 | 1
	 *								    , 'resource_id'         => '1'                          // arrays | CSD | int
	 *								    , 'additional_bk_types' => array()     OPTIONAL         // arrays | CSD | int
	 *								    , 'skip_booking_id'     => '' 		                    // CSD              '3,5'
	 *				                )
	 *
	 * @return array        [  [2023-08-09]: [  [3]: [  '43211 - 86392': object(  'booking_date' => 2023-08-09 12:00:01 ... ), ... ], ... ], [2023-08-10]:  ...  ]
	 *
	 *    Explanation:
	 *                         [2023-08-09]: [
	 *										    [3]: [
	 *													'43211 - 86392': object(
	 *																			[booking_date] => 2023-08-09 12:00:01
	 *																			[approved] => 0
	 *																			[date_res_type] =>
	 *																			[booking_id] => 65
	 *																			[form] => text^selected_short_dates_hint3^August 10...
	 *																			[parent] => 0
	 *																			[prioritet] => 30
	 *																			[type] => 3
	 *                                                                          [__summary__booking] : [
	 *
	 *							                                                        [sql__booking_dates__arr] : [
	 *																								                    [1691668811] => 2023-08-10 12:00:01
	 *																								                    [1691675992] => 2023-08-10 14:00:02
	 *																							                    ]
	 *							                                                        [__dates_obj] => WPBC_BOOKING_DATE Object(
	 *																											[readable_dates]: [   '2023-08-10': [ 12:00:01 - 14:00:02 ]   ]
	 *																											[is_debug] => 0
	 *																										)
	 *							                                                        [__dates_obj__bm__extended] => WPBC_BOOKING_DATE_EXTENDED Object (
	 *																															[readable_dates] : [
	 *																																				[2023-08-09] : [ 12:00:01 - 24:00:00 ]
	 *																																				[2023-08-10] : [ 00:00:00 - 24:00:00 ]
	 *																																				[2023-08-11] : [ 00:00:00 - 24:00:00 ]
	 *																																				[2023-08-12] : [ 00:00:00 - 24:00:00 ]
	 *																																				[2023-08-13] : [ 00:00:00 - 14:00:02 ]
	 *																																			]
	 *																															[is_debug] => 0
	 *																															[extended_dates_times_arr] : []
	 *																															[debug_dates_times_arr] : []
	 *																														)
	 *							                                                        [sql__booking_dates__arr__extended] : 	[
	 *																											[1691582401] => 2023-08-09 12:00:01
	 *																											[1691625600] => 2023-08-10 00:00:00
	 *																											[1691712000] => 2023-08-11 00:00:00
	 *																											[1691798400] => 2023-08-12 00:00:00
	 *																											[1691935202] => 2023-08-13 14:00:02
	 *																										]
	 *
	 *																									]
	 *							                                            )
	 *													'64811 - 86392': object(
	 *							                            [booking_date] => 2023-08-09 18:00:01
	 *							                            [approved] => 0
	 *							                            [date_res_type] =>
	 *							                            [booking_id] => 66
	 *														....
	 *							                        )
	 *
	 *							                ]
	 *
	 *										  ]
	 *
	 *							[2023-08-10]: [ ... ]
	 *							...
	 *
	 *     Example 1:
	 *                  $booked_dates__per_resources__arr = wpbc_get__booked_dates__per_resources__arr( array( 'resource_id' => $type_id ) );
	 *     Example 2:
	 *                  $booked_dates__per_resources__arr = wpbc_get__booked_dates__per_resources__arr(
	 * 																			                      'approved'            => 'all'        // 'all' | 0 | 1
	 * 																							    , 'resource_id'         => '1'          // arrays | CSD | int
	 * 																							    , 'additional_bk_types' => array()      // arrays | CSD | int
	 * 																							    , 'skip_booking_id'     => '' 		    // int | ''             // CSD
	 * 																			                );
	 *
	 *
	 *
	 *
	 *      $booked_dates__resources__seconds__arr[ '2023-08-08' ][ 'resource_id' ][ 'time_seconds_range' ] =  Booking Date Object()
	 *
	 *      Seconds:    [36011 - 43192] -  [start_time_second - end_time_second]
	 *      Seconds:    [0]             -  FULL DAY Booking
	 *
	 *      Day start at   ( 10 + 1 )                      second
	 *      Day End at     ( ( 24 * 60 * 60 ) - 10 + 2 )   second
	 *
	 *      To prevent intersections:
	 *          Each  booking Start time has    +10 seconds
	 *          Each  booking End   time has    -10 seconds
	 *      So real time here [36011 - 43192]  is   [ 36001 - 43202 ]
	 *
	 *      Full day booking end with       0
	 *      Start time booking end with     1
	 *      End time booking end with       2
	 */
	function wpbc_get__booked_dates__per_resources__arr( $params ){

	    $defaults = array(
						  'dates_to_check'      => 'CURDATE'                                    //  'CURDATE'  |  'ALL'  |  [ '2023-07-15' , '2023-07-21' ]
	                    , 'approved'            => ( ( get_bk_option( 'booking_is_show_pending_days_as_available' ) == 'On' ) ? '1' : 'all' )             // 'all' | 0 | 1
					    , 'resource_id'         => '1'                                          // arrays | CSD | int
					    , 'additional_bk_types' => array()                                      // arrays | CSD | int       // OPTIONAL
					    , 'skip_booking_id'     => wpbc_get_booking_id__from_hash_in_url() 		// int | ''                 // CSD         '3,5'
	                    , 'as_single_resource'  => false                                        // get dates as for 'single resource' or 'parent' resource including bookings in all 'child booking resources'
	                    , 'is_days_always_available' => ( ( 'On' == get_bk_option( 'booking_is_days_always_available' ) ) ? true : false )

		                , 'aggregate_params' => array()  //Optional.  It can contain: [   'aggregate_resource_id_arr' =>  [ 4 ] , 'resource_id__with_children__arr' => [3,25] ]
	                );
		$params_for_dates = wp_parse_args( $params, $defaults );


		if ( $params_for_dates['is_days_always_available'] ) {          // All  bookings showing as available dates.
			return array();
		}


		/**
		 * 1. SQL - get array of all  booked dates for our booking resource(s):     [   [0] => date_object{ [booking_date] => 2023-08-08 10:00:01, [approved] => 0, .. },  ..   ]
		 *
		 * Example:   [
		                [0] => stdClass Object (    [booking_date] => 2023-08-08 10:00:01
										            [approved] => 0
										            [date_res_type] =>
										            [booking_id] => 45
										            [form] => select-one^rangetime2^10:00 - 12:00~text^selected_short_dates_hint2^...
										            [parent] => 0
										            [prioritet] => 20
										            [type] => 2
										        )
					    [1] => stdClass Object (
										            [booking_date] => 2023-08-08 10:00:01
										            [approved] => 0
										            [date_res_type] =>
										            [booking_id] => 46
										            [form] => select-one^rangetime10^10:00 - 12:00~text^selected_short_dates_hint10^...
										            [parent] => 2
										            [prioritet] => 1
										            [type] => 10
										        ), ...
					  ]
		 */
		$sql__b_dates__arr = wpbc__sql__get_booking_dates( $params_for_dates );


		/**
		 * 2. From SQL dates into:          [ > resource_id < ][ > booking_id < ] -> __summary__booking['sql__booking_dates__arr'] = [   [ '>seconds<' ]: '> SQL DATE <' , ...   ]
		 *                                                                                                                              [1693303211]  => 2023-08-29 10:00:01
		 *                                                                                                                              [1693353600]  => 2023-08-30 00:00:00
		 *                                                                                                                              [1693483192]  => 2023-08-31 12:00:02
		 *                                                                                                                           ]
		 */
		$resources__booking_id__obj = wpbc_support__transform__into__resource_booking_dates__arr(
									     array( 'sql_dates_arr' => wpbc_clone_array_of_objects( $sql__b_dates__arr )        , 'is_sort__dates_arr' => true , 'is_apply__check_in_out__10s' => true  ) );


		/**
		 * 2.0 Transfer AGGREGATE resource bookings to Main resources
		 *
		 *
		 */
		if ( ! empty( $params['aggregate_params'] ) ) {
			$resources__booking_id__obj = wpbc_aggregate__merge_bookings__from_aggregate__in_source_resource__arr(
																$resources__booking_id__obj,
																$params['aggregate_params']['resource_id__with_children__arr'],
																$params['aggregate_params']['aggregate_resource_id_arr']
															);
		}


		/**
		 * 2.1 Get                          [ > resource_id < ][ > booking_id < ] -> __summary__booking[ '_readable_dates' ]  =  [    '2023-08-01': [  0: "10:00:01 - 12:00:02" ], ...
		 *                                                                                                                            '2023-08-05': [  0: "10:00:01 - 12:00:02" ], ...
		 *                                                                                                                       ]
		 */
		$resources__booking_id__obj = wpbc__in_all_bookings__create_WPBC_BOOKING_DATE( $resources__booking_id__obj );


		/**
		 *  2.2 Extend check in/out dates   [ > resource_id < ][ > booking_id < ] -> __summary__booking[ 'sql__booking_dates__arr__extended' ]
		 */
		if ( function_exists( 'wpbc__extend_availability__before_after__check_in_out' ) ) {

			$resources__booking_id__obj =  wpbc__extend_availability__before_after__check_in_out( $resources__booking_id__obj );
		}


		/**
		 * 3. Into:      ['2024-01-28'][ > resource_id < ][ > booking_id < ][ > only_time_seconds < ]  => obj(   booking_date: "2024-01-28 10:00:01", ... )
		 *
		 *    From:                    [ > resource_id < ][ > booking_id < ]                           => obj(   booking_date: "2024-01-28 10:00:01",  ['__summary__booking']['sql__booking_dates__arr'][] , ...   )
	     */
		$dt__res__bk__seconds__arr = wpbc_support__transform__into__date_resource_booking_timesec__arr(
												array( 'resource_booking_dates_arr' => $resources__booking_id__obj          , 'is_apply__check_in_out__10s' => true  ) );


		/**
		 * 4. Into:    ['2023-08-30'][ > resource_id < ][ > time_seconds_range < ] => booking_date_obj[ booking_id:45, approved:1, ...
		 *
		 * , where    'time_seconds_range'    can be: '0' | '36011 - 43192', ...
		 */
		$dt__res__time_seconds_range__arr = wpbc_support__transform__into__date_resource_timesec_range__arr(
												array( 'date_resource_booking_timesec_arr' => $dt__res__bk__seconds__arr    , 'is_apply__check_in_out__10s' => true  ) );

		return $dt__res__time_seconds_range__arr;
	}

// </editor-fold>


// -----------------------------------------------------------------------------------------------------------------
//  Get Resource array filled by booked Dates
// -----------------------------------------------------------------------------------------------------------------


/**
 * Get all dates STATUSES as:   ['2023-08-30'][ 'summary']['status_for_day':'available', 'day_availability':3, [ > resource_id < ] => std_object( '_day_status':'full_day_booking', '__booked__times__details':[ '1 - 43192': std_object( booking_id:46, 'approved':0, ... ), '50300 - 63192': ], 'is_day_unavailable':1, ...
 *
 * @param int $single_or_parent__resource_id            // 1   --- INT (currently  it's works only  for one resource (single or parent)
 * array $params[ 'timeslots_to_check_intersect' ]           // [ '10:00 - 12:00' ] if bookings intersect with all time slots in array, then the date unavailable, if empty array then it's skipped
 *
 * @return array        {
 *							 calendar_2: {
 *											 id: 2
 *											 dates: { "2023-07-21": {…}
 *													  "2023-07-22": {
 *																		 ['summary']['status_for_bookings']: "pending"
 *																		 ['summary']['status_for_day']: "available"
 *																		 day_availability: 4
 *																		 max_capacity: 4
 *																		 statuses: Object { day: "check_in|check_in|available|available", bookings: "approved|pending||" }
 *
 * 2:  Object { is_day_unavailable: false, _day_status: "check_in", check_in_out: "check_in", … }
 *																		 10: Object { is_day_unavailable: false, _day_status: "check_in", check_in_out: "check_in", … }
 *																		 11: Object { is_day_unavailable: false, _day_status: "available", pending_approved: [] }
 *																		 12: Object { is_day_unavailable: false, _day_status: "available", pending_approved: [] }
 *                                                                  }
 *                                                  , "2023-07-23": {…}
 *                                                  , …
 *                                             }
 *                                   }
 *							calendar_5: {...}
 *                          , ...
 *                      }
 *
 *
 * Important Dates properties:
 *
		                        * ['summary']['status_for_bookings']:  "" | "pending" | "approved"
								* ['summary']['status_for_day']:     | 'available';
														* | 'time_slots_booking';
														* | 'full_day_booking';
														* | 'season_filter';
														* | 'resource_availability';
                                                        * | 'weekday_unavailable';  'from_today_unavailable' | 'limit_available_from_today'
														* | 'change_over';
														* | 'check_in';
														* | 'check_out';
														* | 'change_over check_out' .... variations....
 *
 *
 * @return array  array(
					'dates'                       => $availability_per_day              like ['2023-08-17'] = ...
				  , 'resources_id_arr__in_dates'  => $source__resource_id_arr                   like [ 1,10,11,12,14]
		    )
 */
function wpbc_get_availability_per_days_arr( $params ) {

	if ( 0 ) {      //FixIn: 9.8.3.1 ?
		wpbc_set_limit_php( 300 );      // Set 300 seconds for php execution.
	}


    $defaults = array(
					  'dates_to_check'      => 'CURDATE'                                    //  'CURDATE'  |  'ALL'  |  [ '2023-07-15' , '2023-07-21' ]
    				, 'approved'            => ( ( get_bk_option( 'booking_is_show_pending_days_as_available' ) == 'On' ) ? '1' : 'all' )     // 'all' | 0 | 1
				    , 'resource_id'         => '1'                                          // Single or parent booking resource!!!!       // arrays | CSD | int ???
				    , 'additional_bk_types' => array()                                      // arrays | CSD | int       // OPTIONAL    -> aggregate_resource_id_arr ... it is array of booking resources from aggregate shortcode parameter
				    , 'skip_booking_id'     => wpbc_get_booking_id__from_hash_in_url() 		// int | ''                 // CSD         '3,5'
                    , 'as_single_resource'  => false                                        // get dates as for 'single resource' or 'parent' resource including bookings in all 'child booking resources'
	                , 'max_days_count'      => wpbc_get_max_visible_days_in_calendar()      // 365
	                , 'timeslots_to_check_intersect' => array()                             // array( '12:20 - 12:55', '13:00 - 14:00' )   //TODO: ? do we really need it, because below we get it from function
                    , 'request_uri'         => ( ( ( defined( 'DOING_AJAX' ) ) && ( DOING_AJAX ) ) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'] )     //  front-end: $_SERVER['REQUEST_URI'] | ajax: $_SERVER['HTTP_REFERER']                      // It different in Ajax requests. It's used for change-over days to detect for exception at specific pages
						, 'custom_form'         => 'standard'                                   // Required for checking all available time-slots and compare with  booked time slots
    			);
	$params   = wp_parse_args( $params, $defaults );


	// -----------------------------------------------------------------------------------------------------------------
	// R e s o u r c e (s)    D a t a
	// -----------------------------------------------------------------------------------------------------------------
	// Get array  of booking resource ID    [1, 13, 8, 6, 7]   or  default:     [ 1 ]
	$source__resource_id_arr = wpbc_get_unique_array_of_resources_id( $params['resource_id'] );                             // [1, 13, 8, 6, 7]
	//$source__resource_id_arr = wpbc_get_unique_array_of_resources_id( $params['resource_id'], $params['additional_bk_types'] );                             // [1, 13, 8, 6, 7]
	$first_resource_id = $source__resource_id_arr[0];                                                                           // First from  the list,  or 1 if not provided.

	// Get here Obj. of booking resource (which can include CHILD booking resources)
	$resources_obj = new WPBC_RESOURCE_SUPPORT( array(
									                  'resource_id'        => $source__resource_id_arr,                         //CSD | D
													  'as_single_resource' => $params['as_single_resource']             // If true,  then  skip child booking resources
											));
	// Resource ID arr (Can be Parent and Child resource ID)
	$resource_id__with_children__arr = $resources_obj->get_resource_id_arr();                                       // [ 2, 10, 11, 12 ]

	// Get Maximum capacity of resource
	$resource_obj = $resources_obj->get__booking_resource_obj( $first_resource_id );
	$max_resource_capacity_int = $resource_obj->capacity;                                           // 1 | 4

	// Get base cost of resource
	$resource_base_cost = $resources_obj->get_resources_base_cost_arr();                            // [ 0, 25, 100, 99]




	// -----------------------------------------------------------------------------------------------------------------
	//	Get real AGGREGATE resource ID arr
	// -----------------------------------------------------------------------------------------------------------------
	$real_resource_id_arr = wpbc_get_unique_array_of_resources_id( $params['resource_id'] );                       // [ 1 ]
	if ( empty( $params['additional_bk_types'] ) ) {
		$aggregate_resource_id_arr = array();
	} else {
		$aggregate_resource_id_arr = wpbc_get_unique_array_of_resources_id( $params['additional_bk_types'] );               // [1, 13, 8, 6, 7]
	}
	// Excluding from 'additional_bk_types'  all  resources in 'resource_id'
	$aggregate_resource_id_arr = array_diff( $aggregate_resource_id_arr, $real_resource_id_arr );                       // [ 13, 8, 6, 7 ]
	$aggregate_resource_id_arr = array_values( $aggregate_resource_id_arr );                                            // reindex array,  for ability to  transfer array  instead of obj to JS client side.

	$params['aggregate_params'] = array(
										'aggregate_resource_id_arr'       => $aggregate_resource_id_arr,
										'resource_id__with_children__arr' => $resource_id__with_children__arr
									);

	// -----------------------------------------------------------------------------------------------------------------
	// B o o k i n g s
	// -----------------------------------------------------------------------------------------------------------------
	// Main function to get "Bookings":   ['2023-08-30'][ > resource_id < ][ > time_seconds_range < ] => booking_date_obj[ booking_id:45, approved:1, ...
	$bookings__in_dates = wpbc_get__booked_dates__per_resources__arr( $params );



	// -----------------------------------------------------------------------------------------------------------------
	// Booking > Availability page
	// -----------------------------------------------------------------------------------------------------------------
	$search_dates = $params['dates_to_check'];    // 'CURDATE' | 'ALL' |  [ "2023-08-03" , "2023-08-04" , "2023-08-05" ]

	//FixIn: 9.8.15.10  Aggregate or not Aggregate Booking > Availability ?     aggregate_type = 'all' | 'bookings_only'
	if ( ( ! empty( $params['aggregate_type'] ) ) && ( 'bookings_only' === $params['aggregate_type'] ) ) {              //FixIn: 9.9.0.16
		$unavailable_dates__per_resources__arr = wpbc_for_resources_arr__get_unavailable_dates(
																$resource_id__with_children__arr,                       // Only One Primary Resource !!
																$search_dates
														);    // [ 2: ['2023-08-01', '2023-08-02'], 10: ['2023-08-05', '2023-08-06'] ]
	} else {
		$unavailable_dates__per_resources__arr = wpbc_for_resources_arr__get_unavailable_dates(
														wpbc_get_unique_array_of_resources_id( $resource_id__with_children__arr, $aggregate_resource_id_arr ),      // Get  unavailable dates for primary  and aggregate booking resources
														$search_dates
													);    // [ 2: ['2023-08-01', '2023-08-02'], 10: ['2023-08-05', '2023-08-06'] ]
	}
	$unavailable_dates__per_resources__arr = wpbc_aggregate_merge_availability( $unavailable_dates__per_resources__arr,
																				$resource_id__with_children__arr,
																				$aggregate_resource_id_arr );

	// -----------------------------------------------------------------------------------------------------------------
	// Booking > Resources > Availability page
	// -----------------------------------------------------------------------------------------------------------------
    $season_filters_availability = array();
	if ( class_exists('wpdev_bk_biz_m') ) {
		foreach ( $resource_id__with_children__arr as $resource_id_for_season ) {
			$season_filters_availability[ $resource_id_for_season ] = apply_bk_filter( 'get_available_days', $resource_id_for_season );
		}
	}




	// -----------------------------------------------------------------------------------------------------------------
	//   P a r a m e t e r s    f o r    L O O P
	// -----------------------------------------------------------------------------------------------------------------

	// - Is this Booking > Add booking page ? --------------------------------------------------------------------------
	$is_this_bap_page  = ( false !== strpos( $params['request_uri'], 'page=wpbc-new' ) ) ? true : false;
	$is_this_hash_page = ( false !== strpos( $params['request_uri'], 'booking_hash' ) ) ? true : false;                // Set it to TRUE for adding booking in past at Booking > Add booking page
	if ( ( $is_this_bap_page ) && ( $is_this_hash_page ) ) {        // Start  days in calendar  from        = PAST
		$params['dates_to_check'] = 'ALL';
	}

	if ( 'ALL' == $params['dates_to_check'] ) {                                                                         // All  dates           ->   ''

		$start_day_number     = - 1 * $params['max_days_count'];
		//$today_inix_timestamp = strtotime( '-' . intval( $params['max_days_count'] ) . ' days' );                       // - 300 days

		// Get time, relative to Timezone from WordPress  > Settings General page   //FixIn: 9.9.0.17
		$start_date_unix       = strtotime( '-' . intval( $params['max_days_count'] ) . ' days' );                     // - 365 days
		$date_with_wp_timezone = wpbc_datetime_localized__use_wp_timezone( date( 'Y-m-d H:i:s', $start_date_unix ), 'Y-m-d 00:00:00' );
		$today_inix_timestamp  = strtotime( $date_with_wp_timezone );

	} else if ( 'CURDATE' == $params['dates_to_check'] ) {                                                              // Current dates        ->   CURDATE()

		$start_day_number     = 1;
		// $today_inix_timestamp = strtotime( date( 'Y-m-d 00:00:00', strtotime( 'now' ) ) );                              // Today 00:00:00

		// Get time, relative to Timezone from WordPress  > Settings General page   //FixIn: 9.9.0.17
		$start_date_unix        = strtotime( 'now' );
		$date_with_wp_timezone  = wpbc_datetime_localized__use_wp_timezone( date( 'Y-m-d H:i:s', $start_date_unix ), 'Y-m-d 00:00:00' );
		$today_inix_timestamp = strtotime( $date_with_wp_timezone );

	} else if ( is_array( $params['dates_to_check'] ) ) {

		$start_day_number = 1;

		$today_inix_timestamp = strtotime( $params['dates_to_check'][0] . ' 00:00:00' );                                // '2023-09-03 00:00:00'

		$dif_in_days =   strtotime( $params['dates_to_check'][ ( count( $params['dates_to_check'] ) - 1 ) ] . ' 00:00:00' )
		               - strtotime( $params['dates_to_check'][0] . ' 00:00:00' );

		$dif_in_days = $dif_in_days / ( 24 * 60 * 60 );
		$params['max_days_count'] = ceil( $dif_in_days ) + 1;
	}


	// Get Time Slots from booking form for Each date:      [   '2023-10-25' => [ '12:20 - 12:55', '13:00 - 14:00' ],         '2023-10-26' => [ ... ], ...   ]
	$readable_timeslots_arr__in_dates_arr__to_check_intersect = wpbc_get_times_fields_configuration__by_dates(
													$first_resource_id,                                     // Resource ID (used in case of MultiUser version for getting specific form  for regular  users)
													$params['custom_form'],                                 // Form  name for getting time slots configuration
													$today_inix_timestamp,                                  // start_date_unix_timestamp
													( $params['max_days_count'] - $start_day_number )       // days_count
												);

	// =================================================================================================================
	// --  Here are GO
	// =================================================================================================================
	$availability_per_day = array();

	for ( $day_num = $start_day_number; $day_num <= $params['max_days_count']; $day_num ++ ) {

		$my_day_tag = date( 'Y-m-d', $today_inix_timestamp );                                   // '2023-07-19'


		//--------------------------------------------------------------------------------------------------------------
		// Get Time-Slots from  booking form  to  CHECK INTERSECT    ::   Convert readable time slot to  time slot as seconds [ '12:20 - 12:55', '13:00 - 14:00' ]  OR [] if not time slots
		$as_seconds_timeslots_arr__to_check_intersect = wpbc_get__time_range_in_seconds_arr__for_date( $readable_timeslots_arr__in_dates_arr__to_check_intersect, $my_day_tag );
		/*
		// TODO: check this, if it's required ?  // If empty,  maybe get  default 'timeslots_to_check_intersect' from  root of this function  parameters ????
		if ( empty( $as_seconds_timeslots_arr__to_check_intersect ) ) {


			if ( ! is_array( $params[ 'timeslots_to_check_intersect' ] ) ) {            // Root parameters from  this function
				$params[ 'timeslots_to_check_intersect' ] = array( $params[ 'timeslots_to_check_intersect' ] );
			}

			$as_seconds_timeslots_arr__to_check_intersect = array();
			foreach ( $params[ 'timeslots_to_check_intersect' ] as $time_slot_readable ) {
				$as_seconds_timeslots_arr__to_check_intersect[] = wpbc_convert__readable_time_slot__to__time_range_in_seconds( $time_slot_readable );
			}
		}
		*/
		//--------------------------------------------------------------------------------------------------------------


		$availability_per_day[ $my_day_tag ] = array();

		// Helpful tip. Definition here variables in such  order, useful for having such  order in _wpbc JS variable for showing it with _wpbc.bookings_in_calendars__get_all(); command
		$availability_per_day[ $my_day_tag ]['day_availability'] = $max_resource_capacity_int;          // 4
		$availability_per_day[ $my_day_tag ]['max_capacity']     = $max_resource_capacity_int;          // 4
		$availability_per_day[ $my_day_tag ]['statuses']         = array();
		$availability_per_day[ $my_day_tag ]['statuses']['day_status']      = array();                  // per each child booking resource
		$availability_per_day[ $my_day_tag ]['statuses']['bookings_status'] = array();                  // per each child booking resource
		$availability_per_day[ $my_day_tag ]['summary'] = array();
		$availability_per_day[ $my_day_tag ]['summary']['status_for_day']       = '';                   // available | time_slots_booking | full_day_booking | ...
		$availability_per_day[ $my_day_tag ]['summary']['status_for_bookings']  = '';                   // pending   |  approved   | CO:   pending_pending  |  pending_approved  |   approved_pending  |   approved_approved
		$availability_per_day[ $my_day_tag ]['summary']['tooltip_times']        = '';
		$availability_per_day[ $my_day_tag ]['summary']['tooltip_availability'] = '';
		$availability_per_day[ $my_day_tag ]['summary']['tooltip_day_cost']     = '';
		$availability_per_day[ $my_day_tag ]['summary']['tooltip_booking_details']     = '';


		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// R E S O U R C E S   -   S t a r t
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		foreach ( $resource_id__with_children__arr as $resource_id ) {                  // [1, 13, 8, 6, 7]

			$availability_per_day[ $my_day_tag ][ $resource_id ] = new stdClass();
			$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = false;
			$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status = 'available';
			$availability_per_day[ $my_day_tag ][ $resource_id ]->pending_approved = array();
			$availability_per_day[ $my_day_tag ][ $resource_id ]->tooltips = array(
																					'resource_title' => $resources_obj->get_resource_title( $resource_id ),
																					'times'        => '',
																					'details'      => array()
																				);
			$availability_per_day[ $my_day_tag ][ $resource_id ]->booked_time_slots = array(
																					'in_seconds'      => array(),       // seconds
																					'readable24h'     => array(),       // readable  24H  -  for debug only
																					'merged_seconds'  => array(),       // seconds (reduced number of time_ranges)
																					'merged_readable' => array()        // readable  12AM
																				);
			// Day  Cost / Rates ---------------------------------------------------------------------------------------
			$fin_day_cost = wpbc_support_capacity__get_day_cost( $my_day_tag, $resource_id, $resource_base_cost[ $resource_id ] );
			$availability_per_day[ $my_day_tag ][ $resource_id ]->date_cost_rate = $fin_day_cost;


			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// B O O K I N G S   -   S t a r t
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

			if (    ( isset( $bookings__in_dates[ $my_day_tag ] ) )
			     && ( isset( $bookings__in_dates[ $my_day_tag ][ $resource_id ] ) )   ){

				$this_date_bookings = $bookings__in_dates[ $my_day_tag ][ $resource_id ];                               // [ [36011 - 86392] => stdClass Object(   [approved] => 1  , ... ) ,... ]
				ksort( $this_date_bookings );
				$availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details = $this_date_bookings;

			} else {
				$this_date_bookings = array();
			}


			/**
			 * If bookings exist then:
			 *                          1. Removing some properties here to hide personal details,
			 *                          2. Set Approved / Pending status of the bookings
			 *                          3. By default, set "_day_status" as 'time_slots_booking'  ( later we will change to 'full_day_booking', if applied )
			 */
			if ( ! empty( $this_date_bookings ) ) {

				// =====================================================================================================
				//  T i m e s   -   Start
				// =====================================================================================================
				foreach ( $this_date_bookings as $booked_times__key => $booked_times__value ) {

					// By default, set "_day_status" as 'time_slots_booking'  ( later we will change to 'full_day_booking', if applied )
					$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status = 'time_slots_booking';

					// -------------------------------------------------------------------------------------------------
					// Get Time-Slot
					if ( false === strpos( $booked_times__key, '-' ) ) {                                                // Full day ?   -   0 == $booked_times__key
						$time_range__seconds__arr = array( 0, 86400 );                                                          // 24 * 60 * 60
					} else {
						$time_range__seconds__arr = wpbc_transform_timerange__s_range__in__seconds_arr( $booked_times__key );   // '43211 - 86392'   into   [43211, 86400]
					}

					// Real time: [43211, 86400]
					$availability_per_day[ $my_day_tag ][ $resource_id ]->booked_time_slots['in_seconds'][] = $time_range__seconds__arr;

					// For DEBUG only  --  Readable 24H:  '10:00:01 - 12:30:02'
					$availability_per_day[ $my_day_tag ][ $resource_id ]->booked_time_slots['readable24h'][] = wpbc_transform_timerange__seconds_arr__in__24_hours_range( $time_range__seconds__arr );

					// -------------------------------------------------------------------------------------------------
					// Pending | Approved  ::  for correct order of all statuses - make it based on start time index    / $time_range__seconds__arr[0] == 0   in situation of  FULL day booking
					$availability_per_day[ $my_day_tag ][ $resource_id ]->pending_approved[ $time_range__seconds__arr[0] ] = ( '1' == $booked_times__value->approved ) ? 'approved' : 'pending';


					//   T O O L T I P   ::   Booking Details
					$booked_time_title = ( ( 0 == $time_range__seconds__arr[0] ) && ( $time_range__seconds__arr[1] >= 86400 ) )
											? __( 'Full day', 'booking' )
											: wpbc_transform_timerange__seconds_arr__in__formated_hours( $time_range__seconds__arr );
					$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__tooltip__set_for_details( $availability_per_day, $my_day_tag, $resource_id,
																															$this_date_bookings, $booked_times__key, $resources_obj, $booked_time_title );

					// Unset   ...->form  and other fields...
					$this_date_bookings[ $booked_times__key ] = wpbc_support_capacity__unset_some_fields( $this_date_bookings[ $booked_times__key ] );

				}  //  T i m e s   -   E n d     Booked Times Loop  -  for current resource


				// =====================================================================================================
				//   Merge intersected time intervals =
				// =====================================================================================================
				$merged_seconds_arr = wpbc_merge_intersected_intervals( $availability_per_day[ $my_day_tag ][ $resource_id ]->booked_time_slots['in_seconds'] );

				$availability_per_day[ $my_day_tag ][ $resource_id ]->booked_time_slots['merged_seconds'] = $merged_seconds_arr;

				foreach ( $merged_seconds_arr as $booked_time_slots__in_seconds ) {
					$availability_per_day[ $my_day_tag ][ $resource_id ]->booked_time_slots['merged_readable'][] = wpbc_transform_timerange__seconds_arr__in__formated_hours( $booked_time_slots__in_seconds );
				}

				//   T O O L T I P   ::   Times
				$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__tooltip_times__set_for_times( $availability_per_day, $my_day_tag, $resource_id, $resource_id__with_children__arr );

				// =====================================================================================================
				// End times
				// =====================================================================================================


				// For correct order of all booking statuses - we will make it based on start time index - Sort array starting from  1 second 00:00  to  12:00 to  23:00  etc...
				ksort( $availability_per_day[ $my_day_tag ][ $resource_id ]->pending_approved );

				// array_keys( $booked_time_slots ) can be:   [0] -> 'Full day'   |   ['36011 - 43192', '56011 - 86392'] -> '10:00 - 12:00', '15:55 - 24:00'
				$booked_time_slots = array_keys( $this_date_bookings );


				// =====================================================================================================
				// Full day  booking -----------------------------------------------------------------------------------       full_by_bookings
				// =====================================================================================================
				if ( in_array( '0', $booked_time_slots ) ) {

					$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = true;
					$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status = 'full_day_booking';

					// Reset all other possible time slot statuses, in general  they  must  not exist,  but for some case
					$availability_per_day[ $my_day_tag ][ $resource_id ]->pending_approved = array();

					// Pending or approved,  for full day  booking:     $this_date_bookings['0']
					$availability_per_day[ $my_day_tag ][ $resource_id ]->pending_approved[] = ( '1' == $this_date_bookings['0']->approved ) ? 'approved' : 'pending';

					//   T O O L T I P   ::   Times - Full Day Booked
					$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__tooltip_times__set_full_day( $availability_per_day, $my_day_tag, $resource_id
																															 , $resource_id__with_children__arr, __( 'Full day', 'booking' ) );

				}   // end 'full day' if


				// =====================================================================================================
				// All time slots booked ? -----------------------------------------------------------------------------
				// =====================================================================================================
				if (
					   ( ! empty( $as_seconds_timeslots_arr__to_check_intersect ) )                     // We need to  check  intersection  with some time slot     '36011 - 43192'
					&& ( ! $availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable )   // And this date still free                                 false
				){

					$is_some_timeslot_available = wpbc_is_some_timeslot__available__in_arr( $as_seconds_timeslots_arr__to_check_intersect, $booked_time_slots );

					// -------------------------------------------------------------------------------------------------        full_by_bookings
					if ( ! $is_some_timeslot_available ) {
						// All  time slots booked !!!

						$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = true;
						$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status = 'full_day_booking';
						$availability_per_day[ $my_day_tag ][ $resource_id ]->_booking_form_timeslots_unavailable = implode( '|', $params[ 'timeslots_to_check_intersect' ] );

						//   T O O L T I P   ::   Times - Full Day Booked
						$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__tooltip_times__set_full_day( $availability_per_day, $my_day_tag, $resource_id
																																 , $resource_id__with_children__arr, __( 'Full day', 'booking' ) );
					} else {
						// Some time slots available or there is no time slot passed in this function
					}

				}   // end 'all time slots booked' if



				// =====================================================================================================
				// Change over days  =
				// =====================================================================================================
				if (
					   ( function_exists( 'wpbc_is_booking_used_check_in_out_time' ) )
					&& ( wpbc_is_booking_used_check_in_out_time( $params[ 'request_uri' ] ) )
					&& ( ! $availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable )   // And this date still free                                 false
				) {

					/**
					 * "Change over days" - possible situations:      check_in | check_out | change_over | ...
					 *
					 * $availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status =    check_in
					 *                                                                      | check_out
					 *                                                                      | change_over
					 *                                                                      | 'mixed'
					 *  'mixed' - e.g. 'change_over check_out'  where several child resources and some is 'change_over' and others are 'check_out'
					 *
					 *
					 * Possible situations for change-over days:
					 *
					 * // A. If in $booked_time_slots array one of values  (or in $this_date_bookings one of keys)
					 * //      1. Started with time  that is ended with  1 second
					 * //      2. and Ended with '24:00' it is means '86392',  like this:   '28811 - 86392'
					 * // then it's means that  we have 'check_in' time here
					 *
					 * // B. If in $booked_time_slots array one of values  (or in $this_date_bookings one of keys)
					 * //      1. Started with '00:00' it is means '1'
					 * //      2. and Ended  time  that is ended with  2 second
					 * //                                                          like this:   '1 - 32402'
					 * // then it's means that  we have 'check_out' time here
					 *
					 * // C. If we have A. and B.  then  we have 'change_over' here
					 */

					$availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out = array();

					foreach ( $this_date_bookings as $booked_times__key => $booked_times__value ) {

						if ( false === strpos( $booked_times__key, '-' ) ) {
							continue;       // for situation  with  FULL day  booking,  here        $booked_times__key = 0
						}

						$rangeA_arr    = explode( '-', $booked_times__key );
						$rangeA_arr[0] = intval( trim( $rangeA_arr[0] ) );
						$rangeA_arr[1] = intval( trim( $rangeA_arr[1] ) );

						// start time
						$is_starttime__check_in = $rangeA_arr[0] % 10;                                                  // 0, 1, 2
						$is_starttime__check_in = ( 1 == $is_starttime__check_in ) ? true : false;

						// end time
						$is_endtime__check_out = $rangeA_arr[1] % 10;
						$is_endtime__check_out = ( 2 == $is_endtime__check_out ) ? true : false;


						if ( ( $is_starttime__check_in ) && ( WPBC_FULL_DAY_TIME_OUT == $rangeA_arr[1] ) ) {                                // A  ::   start_time - 86392    e.g.     14:00 - 24:00
							// only "check in" time here
							$availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out[] = 'check_in';

						} else if ( ( WPBC_FULL_DAY_TIME_IN == $rangeA_arr[0] ) && ( $is_endtime__check_out ) ) {                           // B  ::        1 - end_time     e.g.     00:00 - 10:00
							// only "check out" time here
							$availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out[] = 'check_out';
						}

					}
					$availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out = array_unique( $availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out );
					$availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out = array_filter( $availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out );    // All entries of array equal to FALSE (0, '', '0' ) will be removed.

					if (
						   ( in_array( 'check_in',  $availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out ) )
						&& ( in_array( 'check_out', $availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out ) )
					) {
						$availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out       = array( 'change_over' );      // both check in / out times here
						$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = true;

						// Possible statuses: pending_pending | pending_approved | approved_pending | approved_approved
						$availability_per_day[ $my_day_tag ][ $resource_id ]->pending_approved = array( implode( '_', $availability_per_day[ $my_day_tag ][ $resource_id ]->pending_approved ) );
					}

					$availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out = implode( '|', $availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out );

					if ( ! empty( $availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out ) ) {
						$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status = $availability_per_day[ $my_day_tag ][ $resource_id ]->check_in_out;
					}
				}   // end change-over if


			}
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
			// B O O K I N G S   -   E n d
			// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


			// TODO: remove JS var         and all  relative functions.
			//       remove JS vars:    user_unavilable_days for weekdays,  block_some_dates_from_today, wpbc_available_days_num_from_today
			//       remove JS vars:
			//                          date2approve
			//                          date_approved
			//                          reserved_days_count - both_check_in_out_num  - checkout_days_count

			//==========================================================================================================
			//      $availability_per_day[..]->_day_status  =  'season_filter' | ...
			//==========================================================================================================
			// Booking > Resources > Availability page          >>>            'season_filter'
			$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__day_status__season_filter( $availability_per_day, $my_day_tag, $resource_id, $season_filters_availability );

			// Booking > Availability page                      >>>             'resource_availability'
			$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__day_status__resource_availability( $availability_per_day, $my_day_tag, $resource_id, $unavailable_dates__per_resources__arr );

			// Week Days unavailable                            >>>            'weekday_unavailable'
			$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__day_status__weekday_unavailable( $availability_per_day, $my_day_tag, $resource_id );

			if ( ( ! $is_this_bap_page ) || ( ! $is_this_hash_page ) ) {
				// Do not apply these settings at Booking > Add booking page, when we edit booking - e.g. exist 'booking_hash'

				// Unavailable days from today                  >>>            'from_today_unavailable'
				$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__day_status__from_today_unavailable( $availability_per_day, $my_day_tag, $resource_id );

				// Limit available days from today              >>>            'limit_available_from_today'
				$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__day_status__limit_available_from_today( $availability_per_day, $my_day_tag, $resource_id );
			}
			//==========================================================================================================


			//   T O O L T I P   ::   Times - Unavailable Day
			if ( in_array( $availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status, array( 'season_filter',
																									 'resource_availability',
																									 'weekday_unavailable',
																									 'from_today_unavailable',
																									 'limit_available_from_today'
																								) )
			){
				$availability_per_day[ $my_day_tag ][ $resource_id ] = wpbc_support_capacity__tooltip_times__set_full_day( $availability_per_day, $my_day_tag, $resource_id
																														 , $resource_id__with_children__arr, __( 'Unavailable', 'booking' ) );
			}


			// =========================================================================================================
			// Entire    D A Y    statuses
			// =========================================================================================================

			if  ( $availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable ) {
				// Reduce DAY day_availability,  if some season or days unavailable or full by  bookings
				$availability_per_day[ $my_day_tag ][ 'day_availability' ]--;
			}

			// 'resource_availability' | 'weekday_unavailable' | 'from_today_unavailable' | 'limit_available_from_today' | 'season_filter' | 'full_day_booking' | 'time_slots_booking' | 'available'
			$availability_per_day[ $my_day_tag ]['statuses']['day_status'][] = $availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status;

			// 'approved' | 'pending'
			$availability_per_day[ $my_day_tag ]['statuses']['bookings_status'][] = implode( '|', $availability_per_day[ $my_day_tag ][ $resource_id ]->pending_approved );

			// Go to next child booking resource
		}
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
		// R E S O U R C E S   -   E n d
		// +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


		// -------------------------------------------------------------------------------------------------------------
        // Day summary :
		// -------------------------------------------------------------------------------------------------------------
		$availability_per_day[ $my_day_tag ] = wpbc_update_summary_availability_per_this_day( $availability_per_day[ $my_day_tag ], $my_day_tag, $resource_id__with_children__arr );

		// Go next day    	  +  1 day
		$today_inix_timestamp += 24 * 60 * 60;
	}


	return array(
					'dates'                       => $availability_per_day
				  , 'resources_id_arr__in_dates'  => $resource_id__with_children__arr
	              , 'aggregate_resource_id_arr'   => $aggregate_resource_id_arr
				);
}


// <editor-fold     defaultstate="collapsed"                        desc="  ==  S U P P O R T  ==  "  >


	// S U P P O R T

	/**
	 * Update Summary statuses for the Day
	 *
	 * @param $availability_per_this_day
	 * @param $my_day_tag
	 * @param $resource_id_arr
	 *
	 * @return mixed
	 *
	 *
	 * Dates Status Concept Strategy ::
	 *
	 * Check date booking status based on 'all booking resources':
	 * ( and as one common situation, when we have only  1 resource )
	 *
	 * | 'approved'                 - when all booking resources has approved FULL day  bookings or some booking resource unavailable
	 * | 'pending'                  - when at least one booking in one booking resource has pending status and there is no available booking resources
	 * | 'times_partially_booked'   - when at least one booking in one booking resource has time bookings and there is no available booking resources
	 *                                  and if all  time slot approved then  status approved
	 *                                  otherwise if at least one time slot booking has pending status then  it has pending status
	 *
	 *                              if all 'time_slots' booked (it's time slot passed into function as parameter)
	 *                              then  such  date in specific booking resource  has pending or approved status,  based on 'pending'|'approved' rule
	 *
	 * | 'check_in'                 - if activated 'change over days'  and all bookings have 'check-in' status
	 * | 'check_out'                - if activated 'change over days'  and all bookings have 'check-out' status
     * |                                if in booking resource  exist  both check in/out bookings,  and  activated 'change over days'
	 *                                  then such booking resource date has approved or pending status depends on from  status of these bookings
	 * | 'season_unavailable'       - if all  booking resources defined as unavailable at  Booking > Resources > Availability page
	 * | 'resource_unavailable'     - if all  booking resources defined as unavailable at  Booking > Availability page
	 * | 'other_unavailable'        - if all  booking resources defined as unavailable at  Booking > Settings General page in "Availability" section
	 *
	 */
	function wpbc_update_summary_availability_per_this_day( $availability_per_this_day, $my_day_tag, $resource_id_arr ){

		// -------------------------------------------------------------------------------------------------------------
		// Summary Tooltips
		// -------------------------------------------------------------------------------------------------------------
		$availability_per_this_day['summary']['tooltip_times']           = wpbc_support_capacity__tooltip__summary__times( $availability_per_this_day, $my_day_tag, $resource_id_arr );
		$availability_per_this_day['summary']['tooltip_booking_details'] = wpbc_support_capacity__tooltip__summary__booked_details( $availability_per_this_day, $my_day_tag, $resource_id_arr );
		$availability_per_this_day['summary']['tooltip_day_cost']        = wpbc_support_capacity__tooltip__summary__day_cost( $availability_per_this_day, $my_day_tag, $resource_id_arr );
		$availability_per_this_day['summary']['tooltip_availability']    = wpbc_support_capacity__tooltip__summary__availability( $availability_per_this_day, $my_day_tag, $resource_id_arr );




		$availability_per_this_day['summary']['hint__in_day__cost'] = wpbc_support_capacity__in_day_hint__summary__day_cost( $availability_per_this_day, $my_day_tag, $resource_id_arr );

		// -------------------------------------------------------------------------------------------------------------
		// ['summary']['status_for_day']
		// -------------------------------------------------------------------------------------------------------------
		$temp_status = array();
		foreach ( $availability_per_this_day['statuses']['day_status'] as $bookings_status_arr ) {
			$bookings_status_arr = explode( '|', $bookings_status_arr );
			foreach ( $bookings_status_arr as $bookings_status_element ) {
				$temp_status[] = $bookings_status_element;
			}
		}
		$temp_status = array_filter( $temp_status );                                                                    // All entries of array equal to FALSE (0, '', '0' ) will be removed.
		$temp_status = array_unique( $temp_status );                                                                    // Erase duplicates
		$availability_per_this_day['summary']['status_for_day'] = $temp_status;

		/**
		 * Possible day statuses (searched by  value of _day_status):
		 *
		 * available
		 * time_slots_booking
		 * full_day_booking
		 *
		 * limit_available_from_today
		 * from_today_unavailable
		 * weekday_unavailable
		 * resource_availability
		 * season_filter
		 *
		 *      change_over
		 *      check_out
		 *      check_in
		 *              'mixed'  from check_in/check_out/change_over
		 */

		// Priority:    available > time_slots_booking > full_day_booking
		//                        > season_filter > resource_availability
		//                        > weekday_unavailable > from_today_unavailable > limit_available_from_today

		// if at least one booking "available" then day has this status
		if ( in_array( 'available', $availability_per_this_day['summary']['status_for_day'] ) ) {                      $availability_per_this_day['summary']['status_for_day'] = 'available';
		} else if ( in_array( 'time_slots_booking', $availability_per_this_day['summary']['status_for_day'] ) ) {      $availability_per_this_day['summary']['status_for_day'] = 'time_slots_booking';
		} else if ( ( ! in_array( 'check_out',  $availability_per_this_day['summary']['status_for_day'] ) )
	               && ( in_array( 'check_in', $availability_per_this_day['summary']['status_for_day'] ) ) ) {          $availability_per_this_day['summary']['status_for_day'] = 'check_in';
		} else if ( ( ! in_array( 'check_in',  $availability_per_this_day['summary']['status_for_day'] ) )
	               && ( in_array( 'check_out', $availability_per_this_day['summary']['status_for_day'] ) ) ) {         $availability_per_this_day['summary']['status_for_day'] = 'check_out';
		} else if ( in_array( 'full_day_booking', $availability_per_this_day['summary']['status_for_day'] ) ) {        $availability_per_this_day['summary']['status_for_day'] = 'full_day_booking';
		} else if ( in_array( 'season_filter', $availability_per_this_day['summary']['status_for_day'] ) ) {           $availability_per_this_day['summary']['status_for_day'] = 'season_filter';
		} else if ( in_array( 'resource_availability', $availability_per_this_day['summary']['status_for_day'] ) ) {   $availability_per_this_day['summary']['status_for_day'] = 'resource_availability';
		} else if ( in_array( 'weekday_unavailable', $availability_per_this_day['summary']['status_for_day'] ) ) {     $availability_per_this_day['summary']['status_for_day'] = 'weekday_unavailable';
		} else if ( in_array( 'from_today_unavailable', $availability_per_this_day['summary']['status_for_day'] ) ) {  $availability_per_this_day['summary']['status_for_day'] = 'from_today_unavailable';
		} else if ( in_array( 'limit_available_from_today', $availability_per_this_day['summary']['status_for_day'])){ $availability_per_this_day['summary']['status_for_day'] = 'limit_available_from_today';
		} else {
			$availability_per_this_day['summary']['status_for_day'] = implode( ' ', $availability_per_this_day['summary']['status_for_day'] );
		}


		// -------------------------------------------------------------------------------------------------------------
		// ['summary']['status_for_bookings']
		// -------------------------------------------------------------------------------------------------------------
		$temp_status = array();
		foreach ( $availability_per_this_day['statuses']['bookings_status'] as $bookings_status_arr ) {
			$bookings_status_arr = explode( '|', $bookings_status_arr );
			foreach ( $bookings_status_arr as $bookings_status_element ) {
				$temp_status[] = $bookings_status_element;
			}
		}
		$temp_status = array_filter( $temp_status );                                                                    // All entries of array equal to FALSE (0, '', '0' ) will be removed.
		$temp_status = array_unique( $temp_status );
		$availability_per_this_day['summary']['status_for_bookings'] = $temp_status;

		// Priority:    pending > approved > ''

		// if at least one booking pending then day is pending
		if ( in_array( 'pending',  $availability_per_this_day['summary']['status_for_bookings'] ) ) {                  $availability_per_this_day['summary']['status_for_bookings'] = 'pending';
		} else if ( in_array( 'pending_pending',  $availability_per_this_day['summary']['status_for_bookings'] ) ) {   $availability_per_this_day['summary']['status_for_bookings'] = 'pending_pending';
			// Usual  situations for change over days:  pending_pending  |  pending_approved  |   approved_pending  |   approved_approved
		} else {
			 $availability_per_this_day['summary']['status_for_bookings'] = implode( ' ',  $availability_per_this_day['summary']['status_for_bookings'] );
		}


		// -------------------------------------------------------------------------------------------------------------
		return $availability_per_this_day;
	}


		// T O O L T I P s

		/**
		 * Set tooltip summary  -- Time-Slots
		 *
		 * @param $availability_per_this_day
		 * @param $my_day_tag
		 * @param $resource_id_arr
		 *
		 * @return string       -   tooltip content or ''
		 */
		function wpbc_support_capacity__tooltip__summary__times( $availability_per_this_day, $my_day_tag, $resource_id_arr ){

		    $tooltip = '';

			if ( 'On' != get_bk_option( 'booking_disable_timeslots_in_tooltip' ) ) {                                    //FixIn: 9.5.0.2.2

				$tooltip_title_word = get_bk_option( 'booking_highlight_timeslot_word' );
				$tooltip_title_word = apply_bk_filter( 'wpdev_check_for_active_language', $tooltip_title_word );
				$tooltip_title_word = ( empty( $tooltip_title_word ) )
										? ''
										: '<div class="wpbc_tooltip_title">' . $tooltip_title_word . '</div>' . ' ';

				$status_summary_tooltip_times = array();
				foreach ( $resource_id_arr as $resource_id ) {
					if ( ! empty( $availability_per_this_day[ $resource_id ]->tooltips['times'] ) ) {
						$status_summary_tooltip_times[] = $availability_per_this_day[ $resource_id ]->tooltips['times'];
					}
				}

				if ( ! empty( $status_summary_tooltip_times ) ) {

					$tooltip = '<div class="wpbc_tooltip_section tooltip__times">'
					                . $tooltip_title_word
					                . implode( '', $status_summary_tooltip_times )
					           . '</div>';
				}
			}

			return $tooltip;
		}

		function wpbc_support_capacity__tooltip__summary__availability( $availability_per_this_day, $my_day_tag, $resource_id_arr ){

			$tooltip = '';

            if ( ( 'On' == get_bk_option( 'booking_is_show_availability_in_tooltips' ) ) && ( $availability_per_this_day['max_capacity'] > 1 ) ) {

				$tooltip_title_word = get_bk_option( 'booking_highlight_availability_word' );
				$tooltip_title_word = apply_bk_filter( 'wpdev_check_for_active_language', $tooltip_title_word );
				$tooltip_title_word = ( empty( $tooltip_title_word ) )
										? ''
										: '<div class="wpbc_tooltip_title">' . $tooltip_title_word . '</div>' . ' ';

				$tooltip_content_header = '';

				if ( ! empty( $availability_per_this_day['day_availability'] ) ) {

					$tooltip = '<div class="wpbc_tooltip_section tooltip__availability">'
				                    . $tooltip_title_word
									. '<div class="wpbc_tooltip_resource_container">'
										. $tooltip_content_header
										. '<div class="wpbc_tooltip_item">'
												. $availability_per_this_day['day_availability']
										. '</div>'
									. '</div>'
					           . '</div>';
				}
			}
			return $tooltip;
		}

		function wpbc_support_capacity__tooltip__summary__day_cost( $availability_per_this_day, $my_day_tag, $resource_id_arr ){

			$tooltip = '';
			if (
				  ( ! empty( $availability_per_this_day['day_availability'] ) )
               && ( 'On' == get_bk_option( 'booking_is_show_cost_in_tooltips' ) )
			){

				$tooltip_title_word = get_bk_option( 'booking_highlight_cost_word' );
				$tooltip_title_word = apply_bk_filter( 'wpdev_check_for_active_language', $tooltip_title_word );
				$tooltip_title_word = ( empty( $tooltip_title_word ) )
										? ''
										: '<div class="wpbc_tooltip_title">' . $tooltip_title_word . '</div>' . ' ';

				$id_of_first_resource = $resource_id_arr[0];
				$tooltip_content_header = '';

				if ( ! empty( $availability_per_this_day[ $id_of_first_resource ]->date_cost_rate ) ) {

					 $cur_sym  = get_bk_option( 'booking_cost_in_date_cell_currency' );       // in Booking > Settings General page in "Calendar" section
					//$cur_sym = wpbc_get_currency_symbol();                                  // in Booking > Settings > Payment page

					$cost_text = wpbc_formate_cost_hint__no_html( $availability_per_this_day[ $id_of_first_resource ]->date_cost_rate, $cur_sym );
					$cost_text = html_entity_decode( $cost_text );

					$tooltip = '<div class="wpbc_tooltip_section tooltip__day_cost">'
				                    . $tooltip_title_word
									. '<div class="wpbc_tooltip_resource_container">'
										. $tooltip_content_header
										. '<div class="wpbc_tooltip_item">'
												. $cost_text
										. '</div>'
									. '</div>'
					           . '</div>';
				}
			}
			return $tooltip;
		}


		function wpbc_support_capacity__in_day_hint__summary__day_cost( $availability_per_this_day, $my_day_tag, $resource_id_arr ){

			$tooltip = '';
			if (
				  ( ! empty( $availability_per_this_day['day_availability'] ) )
               && ( 'On' == get_bk_option( 'booking_is_show_cost_in_date_cell' ) )
			){

				$id_of_first_resource = $resource_id_arr[0];

				if ( ! empty( $availability_per_this_day[ $id_of_first_resource ]->date_cost_rate ) ) {

					 $cur_sym  = get_bk_option( 'booking_cost_in_date_cell_currency' );       // in Booking > Settings General page in "Calendar" section
					//$cur_sym = wpbc_get_currency_symbol();                                  // in Booking > Settings > Payment page

					$cost_text = $availability_per_this_day[ $id_of_first_resource ]->date_cost_rate;
					$cost_text = number_format( floatval( $cost_text ), wpbc_get_cost_decimals(), '.', '' );
					$cost_text = strip_tags( wpbc_cost_show( $cost_text, array(  'currency' => 'CURRENCY_SYMBOL' ) ) );
					$cost_text = str_replace( array( 'CURRENCY_SYMBOL', '&' ), array( $cur_sym, '&amp;' ), $cost_text );
					$cost_text = html_entity_decode($cost_text);

					$tooltip =  $cost_text;
				}
			}
			return $tooltip;
		}

		/**
		 * Set tooltip summary  -- Booking Details
		 *
		 * @param $availability_per_this_day
		 * @param $my_day_tag
		 * @param $resource_id_arr
		 *
		 * @return string       -   tooltip content or ''
		 */
		function wpbc_support_capacity__tooltip__summary__booked_details( $availability_per_this_day, $my_day_tag, $resource_id_arr ){

			$tooltip = '';

			if ( 'On' == get_bk_option( 'booking_is_show_booked_data_in_tooltips' ) ) {                                            //FixIn: 9.5.0.2.2

				$tooltip_title_word = '<div class="wpbc_tooltip_title">' . __('Booking details','booking') . '</div>';

				$status_summary_tooltip_times = array();
				foreach ( $resource_id_arr as $resource_id ) {
					if ( ! empty( $availability_per_this_day[ $resource_id ]->tooltips['details'] ) ) {

						if ( ! empty( $availability_per_this_day[ $resource_id ]->tooltips['resource_title'] ) ){
							$tooltip_content_header = '<div class="wpbc_tooltip_header">'
															. $availability_per_this_day[ $resource_id ]->tooltips['resource_title'] . ': '
			                                        . '</div>';
						} else{
							$tooltip_content_header = '';
						}

						$booking_details_text = '<div class="wpbc_tooltip_resource_container">'
						                            . $tooltip_content_header
						                            . implode( ' ', $availability_per_this_day[ $resource_id ]->tooltips['details'] )
                                              . '</div>';

						$status_summary_tooltip_times[] = $booking_details_text;
					}
				}

				if ( ! empty( $status_summary_tooltip_times ) ) {

					$tooltip = '<div class="wpbc_tooltip_section tooltip__booking_details">'
					                . $tooltip_title_word
					                . implode( '', $status_summary_tooltip_times )
					           . '</div>';
				}
			}
			return $tooltip;
		}

				/**
				*  Set Tooltip -- Booking Details
				*
				* @param $availability_per_day
				* @param $my_day_tag
				* @param $resource_id
				* @param $this_date_bookings
				* @param $booked_times__key
				* @param $resources_obj
				*
				* @return array - $availability_per_day[ $my_day_tag ][ $resource_id ];
				*/
				function wpbc_support_capacity__tooltip__set_for_details( $availability_per_day, $my_day_tag, $resource_id, $this_date_bookings, $booked_times__key, $resources_obj, $booked_time ) {

                    // Get booking details for tooltip,  if needed
					if ( 'On' == get_bk_option( 'booking_is_show_booked_data_in_tooltips' ) ) {

						$booking_id               = $this_date_bookings[ $booked_times__key ]->booking_id;
						$this_booking_resource_id = $this_date_bookings[ $booked_times__key ]->type;

					    $booking_details_text = wpbc_support_capacity__get_booking_details(
																						$this_date_bookings[ $booked_times__key ],
																						$resources_obj->get__booking_resource_obj( $this_booking_resource_id )
																					);
						$tooltip_text  = '';
						$tooltip_text .= '<div class="wpbc_tooltip_item tooltip_booked_time">' . $booked_time          . '</div>';           // Booked time
						$tooltip_text .= '<div class="wpbc_tooltip_item">' . $booking_details_text . '</div>';           // Booking details

						$availability_per_day[ $my_day_tag ][ $resource_id ]->tooltips['details'][ $booking_id ] = $tooltip_text;
					}

					return $availability_per_day[ $my_day_tag ][ $resource_id ];
				}

				/**
				 *  Set Tooltip Times -- Time-Slots
				 *
				 * @param $availability_per_day
				 * @param $my_day_tag
				 * @param $resource_id
				 * @param $resource_id_arr
				 *
				 * @return array - $availability_per_day[ $my_day_tag ][ $resource_id ];
				 */
				function wpbc_support_capacity__tooltip_times__set_for_times( $availability_per_day, $my_day_tag, $resource_id, $resource_id_arr ){

					// Times Tooltip -  time slot list  --------------------------------------------------------------------
					$tooltip_content_header = '';

					if ( count( $resource_id_arr ) > 1 ) {

						$class_name = 'tooltip_items_count_' . count( $availability_per_day[ $my_day_tag ][ $resource_id ]->booked_time_slots['merged_readable'] );

						$tooltip_content_header = '<div class="wpbc_tooltip_header ' . $class_name . ' ">'
														. $availability_per_day[ $my_day_tag ][ $resource_id ]->tooltips['resource_title'] . ': '
		                                        . '</div>';
					}

					$tooltip_content_arr = array_map(      function ( $item ) {
																				return '<div class="wpbc_tooltip_item">' . $item . '</div>';
																			}
														, $availability_per_day[ $my_day_tag ][ $resource_id ]->booked_time_slots['merged_readable']
													);

					$availability_per_day[ $my_day_tag ][ $resource_id ]->tooltips['times'] = '<div class="wpbc_tooltip_resource_container">'
					                                                                                . $tooltip_content_header
					                                                                                . implode( '', $tooltip_content_arr )
					                                                                          . '</div>';
					return $availability_per_day[ $my_day_tag ][ $resource_id ];
				}

				/**
				 *  Set Tooltip Times -- Full Day
				 *
				 * @param $availability_per_day
				 * @param $my_day_tag
				 * @param $resource_id
				 * @param $resource_id_arr
				 * @param $status_title
				 *
				 * @return array - $availability_per_day[ $my_day_tag ][ $resource_id ];
				 */
				function wpbc_support_capacity__tooltip_times__set_full_day( $availability_per_day, $my_day_tag, $resource_id, $resource_id_arr, $status_title ){

					// Times Tooltip -  FULL DAY  --------------------------------------------------------------------
					$tooltip_content_header = '';

					if ( count( $resource_id_arr ) > 1 ) {

						$class_name = 'tooltip_items_count_1';

						$tooltip_content_header = '<div class="wpbc_tooltip_header ' . $class_name . ' ">'
														. $availability_per_day[ $my_day_tag ][ $resource_id ]->tooltips['resource_title'] . ': '
		                                        . '</div>';
					}

					$tooltip_content_text =  '<div class="wpbc_tooltip_item">' . $status_title . '</div>';

					$availability_per_day[ $my_day_tag ][ $resource_id ]->tooltips['times'] = '<div class="wpbc_tooltip_resource_container">'
					                                                                                . $tooltip_content_header
					                                                                                . $tooltip_content_text
					                                                                          . '</div>';
					return $availability_per_day[ $my_day_tag ][ $resource_id ];
				}



		/**
		 * Get parsed booking data, if required
		 *
		 * @param object $this_date_bookings
		 * @param object $resource_obj
		 *
		 * @return string
		 */
		function wpbc_support_capacity__get_booking_details( $this_date_bookings, $resource_obj ) {

			if (    ( ! class_exists( 'wpdev_bk_biz_m' ) )
			     || ( 'On' !== get_bk_option( 'booking_is_show_booked_data_in_tooltips' ) )
			){
			    return '';
		    }

			$booking_details_for_hint = '';

			if ( isset( $this_date_bookings->form ) ) {

				// Get shortcodes
				$booking_shortcodes_in_tooltips = get_bk_option( 'booking_booked_data_in_tooltips' );                       // '[name] [secondname]'

				$resource_id = $this_date_bookings->type;

				// Get parsed booking data field values -- e.g.:  [ "rangetime":"12:00 - 14:00", "secondname":"Smith",... ]
				$booking_data_arr = wpbc_get_parsed_booking_data_arr( $this_date_bookings->form, $resource_id, array( 'get' => 'value' ) );
				$booking_data_arr['booking_id']     = $this_date_bookings->booking_id;
				$booking_data_arr['resource_id']    = $resource_id;
				$booking_data_arr['resource_title'] = ( ! empty( $resource_obj ) ) ? apply_bk_filter( 'wpdev_check_for_active_language', $resource_obj->title ) : '';

				// Replace existing shortcodes
				foreach ( $booking_data_arr as $replace_shortcode => $replace_value ) {

					if ( is_null( $replace_value ) ) { $replace_value = ''; };

					$replace_value = wpbc_replace__true_false__to__yes_no( $replace_value );									    //FixIn: 9.8.9.1

		            $booking_shortcodes_in_tooltips = str_replace(  array(
																			'[' . $replace_shortcode . ']'
		                                                                  , '{' . $replace_shortcode . '}'
			                                                        )
		                                                            , $replace_value
		                                                            , $booking_shortcodes_in_tooltips
		                                                );
		        }

				$booking_details_for_hint = $booking_shortcodes_in_tooltips;

		        // Remove all shortcodes, which is not replaced early.
				$booking_details_for_hint = preg_replace( '/[\[\{][a-zA-Z0-9.,_-]{0,}[\]\}]/', '', $booking_details_for_hint );
			}

			return $booking_details_for_hint;
		}


		/**
		 * Unset  some fields from booking data for do  not show personal data at front-end side
		 *
		 * @param object $this_date_bookings
		 *
		 * @return object
		 */
		function wpbc_support_capacity__unset_some_fields( $this_date_bookings ) {

			if ( isset( $this_date_bookings->form ) ) {
				unset( $this_date_bookings->form );               // Hide booking details
			}

			if ( isset( $this_date_bookings->booking_date ) ) {
				unset( $this_date_bookings->booking_date );       // Confusing property of last end booking date, because we have 'sql summary booking dates arrays' for all dates and times
			}

			return $this_date_bookings;
		}


		// D A Y    S T A T U S    U N A V A I L A B L E

		/**
		 * Set > _day_status = 'limit_available_from_today'  depends on   Booking > Settings General page --->  "Limit available days from today"
		 *
		 * @param $availability_per_day                                 [ ... ]
		 * @param $my_day_tag                                           - '2023-08-08'
		 * @param $resource_id                                          - 2
		 *
		 * @return array                                                -   $availability_per_day[ $my_day_tag ][ $resource_id ]
		 */
		function wpbc_support_capacity__day_status__limit_available_from_today( $availability_per_day, $my_day_tag, $resource_id ){

			// -----------------------------------------------------------------------------------------------------        // limit_available_from_today
			// 3. Limit available days from today
			if ( function_exists( 'wpbc_is_day_available__relative__limit_available_from_today' ) ) {
				if ( ! wpbc_is_day_available__relative__limit_available_from_today( $my_day_tag ) ) {
					// this date unavailable
					$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = true;
					$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status        = 'limit_available_from_today';

					if ( empty( $availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details ) ) {
						$availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details = array();               // Reset  bookings details
					}
				}
			}

			return $availability_per_day[ $my_day_tag ][ $resource_id ];
		}


		/**
		 * Set > _day_status = 'from_today_unavailable'  depends on   Booking > Settings General page --->  "Unavailable days from today"
		 *
		 * @param $availability_per_day                                 [ ... ]
		 * @param $my_day_tag                                           - '2023-08-08'
		 * @param $resource_id                                          - 2
		 *
		 * @return array                                                -   $availability_per_day[ $my_day_tag ][ $resource_id ]
		 */
		function wpbc_support_capacity__day_status__from_today_unavailable( $availability_per_day, $my_day_tag, $resource_id ){

			// -----------------------------------------------------------------------------------------------------        // from_today_unavailable
			// 2. Unavailable days from today
			$block_some_dates_from_today = get_bk_option( 'booking_unavailable_days_num_from_today' );

			//FixIn: 9.9.0.17
			$start_date_unix       = strtotime( '+' . intval( $block_some_dates_from_today ) . ' days' );                   // + 0 days
			$date_with_wp_timezone = wpbc_datetime_localized__use_wp_timezone( date( 'Y-m-d H:i:s', $start_date_unix ), 'Y-m-d 00:00:00' );
			$today_timestamp_wp_timezone  = strtotime( $date_with_wp_timezone );
			$days_number = intval( (  $today_timestamp_wp_timezone - strtotime( $my_day_tag ) ) / 86400 );

			//$days_number = intval( ( strtotime( '+' . intval( $block_some_dates_from_today ) . ' days' ) - strtotime( $my_day_tag ) ) / 86400 );


			if ( $days_number > 0 ) {
				// this date unavailable
				$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = true;
				$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status        = 'from_today_unavailable';

				if ( empty( $availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details ) ) {
					$availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details = array();                   // Reset  bookings details
				}
			}

			return $availability_per_day[ $my_day_tag ][ $resource_id ];
		}


		/**
		 * Set > _day_status = 'weekday_unavailable'  depends on   Booking > Settings General page --->  "Week Days unavailable"
		 *
		 * @param $availability_per_day                                 [ ... ]
		 * @param $my_day_tag                                           - '2023-08-08'
		 * @param $resource_id                                          - 2
		 *
		 * @return array                                                -   $availability_per_day[ $my_day_tag ][ $resource_id ]
		 */
		function wpbc_support_capacity__day_status__weekday_unavailable( $availability_per_day, $my_day_tag, $resource_id ){

			// 1. Week Days unavailable --------------------------------------------------------------------------------        // weekday_unavailable
			$unavailable_weekdays = array();
			foreach ( range( 0, 6 ) as $week_day_num ) {
				$unavailable_weekdays[ $week_day_num ] = ( 'On' == get_bk_option( 'booking_unavailable_day' . $week_day_num ) ) ? true : false;
			}

			$this_day_week_num = date( 'w', strtotime( $my_day_tag ) ); // 0 - 6

			if ( $unavailable_weekdays[ $this_day_week_num ] ) {
				// this date unavailable
				$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = true;
				$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status = 'weekday_unavailable';

				if ( empty( $availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details ) ) {
					$availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details = array();                   // Reset  bookings details
				}
			}

			return $availability_per_day[ $my_day_tag ][ $resource_id ];
		}


		/**
		 * Set > _day_status = 'resource_availability'  depends on   Booking > Availability page
		 *
		 * @param $availability_per_day                               [ ... ]
		 * @param $my_day_tag                                         - '2023-08-08'
		 * @param $resource_id                                        - 2
		 * @param array $unavailable_dates__per_resources__arr        - [ 2: ['2023-08-01', '2023-08-02'], 10: ['2023-08-05', '2023-08-06'] ]
		 *
		 * @return array                                    -   $availability_per_day[ $my_day_tag ][ $resource_id ]
		 */
		function wpbc_support_capacity__day_status__resource_availability( $availability_per_day, $my_day_tag, $resource_id, $unavailable_dates__per_resources__arr ){

			// Booking > Availability page -----------------------------------------------------------------------------        // resource_availability
			/**
			 * $unavailable_dates__per_resources__arr =  Array (     [2] => Array(   [0] => 2023-08-01
																	                 [1] => 2023-08-02
																		        )
																    [10] => Array(   [0] => 2023-08-05
																		             [1] => 2023-08-06
																		        )
																)
			 */
			if (
				   ( isset( $unavailable_dates__per_resources__arr[ $resource_id ] ) )
		        && ( in_array( $my_day_tag, $unavailable_dates__per_resources__arr[ $resource_id ] ) )
			) {

				$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = true;
				$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status = 'resource_availability';

				if ( empty( $availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details ) ) {
					$availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details = array();                   // Reset  bookings details
				}
			}

			return $availability_per_day[ $my_day_tag ][ $resource_id ];
		}


		/**
		 * Set > _day_status = 'season_filter'  depends on   Booking > Resources > Availability page
		 *
		 * @param $availability_per_day                               [ ... ]
		 * @param $my_day_tag                                         - '2023-08-08'
		 * @param $resource_id                                        - 2
		 * @param array $season_filters_availability                  - [...]
		 *
		 * @return array                                              -   $availability_per_day[ $my_day_tag ][ $resource_id ]
		 */
		function wpbc_support_capacity__day_status__season_filter( $availability_per_day, $my_day_tag, $resource_id, $season_filters_availability ){

		    if ( function_exists( 'wpbc_is_this_day_available_in_season_filters' ) ) {                                       // BM  and higher

				$is_date_available = wpbc_is_this_day_available_in_season_filters( $my_day_tag, $resource_id, $season_filters_availability[ $resource_id ] );    // Get availability
				if ( ! $is_date_available ) {

					$availability_per_day[ $my_day_tag ][ $resource_id ]->is_day_unavailable = true;
					$availability_per_day[ $my_day_tag ][ $resource_id ]->_day_status = 'season_filter';

					if ( empty( $availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details ) ) {
						$availability_per_day[ $my_day_tag ][ $resource_id ]->__booked__times__details = array();               // Reset  bookings details
					}
				}
			}

			return $availability_per_day[ $my_day_tag ][ $resource_id ];
		}


		// D A Y   C O S T

		/**
		 * Get day cost     depend on Rates
		 * @param $my_day_tag                                         - '2023-08-08'
		 * @param $resource_id                                        - 2
		 * @param $base_cost                                          - 25
		 *
		 * @return int|string
		 */
		function wpbc_support_capacity__get_day_cost( $my_day_tag, $resource_id, $base_cost ) {

			$fin_day_cost = 0;
			if ( function_exists( 'wpbc_get_1_day_cost_apply_rates' ) ) {

				list( $year, $month, $day ) = explode( '-', $my_day_tag );

				$day   = intval( $day );
				$month = intval( $month );
				$year  = intval( $year );

				$fin_day_cost = wpbc_get_1_day_cost_apply_rates( $resource_id, $base_cost, $day, $month, $year );
			}

			return $fin_day_cost;
		}


// </editor-fold>



//TODO:         M I G R A T E        to      9.8
// .
// 		instead of this:  	<script type="text/javascript">  wpbc_settings.set_option( 'pending_days_selectable', true ); </script>
// 			   use this:    <script type="text/javascript">  _wpbc.calendar__set_param_value( " . intval( $resource_id ) . ", 'pending_days_selectable' , true  );  </script>
//  Booking Calendar -  JavaScript Settings
//  Example or redefine some settings:
//  <script type="text/javascript">  wpbc_settings.set_option( 'pending_days_selectable', true ); </script>
//  [booking type=1]
//   .
//     here relations:
//							'bk_days_selection_mode'			days_select_mode
//							'bk_1click_mode_days_num'			fixed__days_num
//							'bk_1click_mode_days_start' 		fixed__week_days__start
//							'bk_2clicks_mode_days_max' 			dynamic__days_max
//							'bk_2clicks_mode_days_min'			dynamic__days_min
//							'bk_2clicks_mode_days_specific' 	dynamic__days_specific
//							'bk_2clicks_mode_days_start'		dynamic__week_days__start
// .
// 1. Define parameters for calendar(s):
// 											_wpbc.calendar__set_parameters( " . intval( $resource_id ) . ", {'days_select_mode': 'single'}  );
// 											_wpbc.calendar__set_parameters( " . intval( $resource_id ) . ", {'days_select_mode': 'multiple'}  );
// 											_wpbc.calendar__set_parameters( " . intval( $resource_id ) . ", {'days_select_mode': 'fixed'}  );
// 												_wpbc.calendar__set_parameters( " . intval( $resource_id ) . ", {'fixed__days_num'  : 7 } );
// 												_wpbc.calendar__set_parameters( " . intval( $resource_id ) . ", {'fixed__week_days__start': [-1] } ); // { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat }
// 											_wpbc.calendar__set_parameters( " . intval( $resource_id ) . ", {'days_select_mode': 'dynamic'}  );
// 												_wpbc.calendar__set_param_value( " . intval( $resource_id ) . ", 'dynamic__days_min'      , 7  );
// 												_wpbc.calendar__set_param_value( " . intval( $resource_id ) . ", 'dynamic__days_max'      , 14  );
// 												_wpbc.calendar__set_param_value( " . intval( $resource_id ) . ", 'dynamic__days_specific' , []  );	// Example [5,7]
// 												_wpbc.calendar__set_param_value( " . intval( $resource_id ) . ", 'dynamic__week_days__start'    , [-1]  ); // { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat }
//  JavaScript functions changes:
// 		wpbc_unselect_all_days( resource_id ) 		                        ->	wpbc_calendar__unselect_all_dates( resource_id )
// 		showErrorMessage( element , errorMessage , isScrollStop );          ->  wpbc_front_end__show_message__warning( jq_node, message );
// 		showMessageUnderElement( element , errorMessage , message_type);    ->  wpbc_front_end__show_message__warning_under_element( jq_node, message );
// 		makeScroll( object_name );                                          ->  wpbc_do_scroll( jq_node );
// .
//  JavaScript hooks changes:
//								jQuery( ".booking_form_div" ).trigger( "date_selected", [ resource_id, date ] );
//					become
//								jQuery( ".booking_form_div" ).trigger( "date_selected", [ resource_id, mouse_clicked_dates, all_selected_dates_arr ] );
// 								where:
// 											mouse_clicked_dates  		Can be: "05.10.2023 - 07.10.2023"  |  "10.10.2023 - 10.10.2023"  |
// 											all_selected_dates_arr 		Can be: [ "2023-10-05", "2023-10-06", "2023-10-07", … ]
// PHP actions:
//   from: 	make_bk_action('wpdev_booking_post_inserted', $booking_id, $bktype, $str_dates__dd_mm_yyyy,  array($start_time, $end_time ) , $formdata );
//   removed!
// .
//   from:	apply_filters('wpdev_booking_form_content', $form , $resource_id);
//   to:     	apply_filters( 'wpbc_booking_form_html__update__append_change_over_times', $form, $resource_id );
// .
// Removed PHP actions:
// . 'show_payment_forms__for_ajax'
//	 *  make_bk_action('wpbc_ add_new_booking' , array(
//	 *		 'bktype' => 1
//	 *		 , 'dates' => '27.08.2014, 28.08.2014, 29.08.2014'
//	 *		 , 'form' => 'select-one^rangetime1^10:00 - 12:00~text^name1^Jo~text^secondname1^Smith~email^email1^smith@gmail.com~text^phone1^678676678~text^address1^Linkoln Street~text^city1^London~text^postcode1^78788~select-one^country1^GB~select-one^visitors1^1~select-one^children1^1~textarea^details1^Rooms with sea view~checkbox^term_and_condition1[]^I Accept term and conditions'
//	 *		 , 'is_send_emeils' => 0
//	 *		// , 'booking_form_type' => ''
//	 *		// , 'wpdev_active_locale' => 'en_US'
//	 *		) );
// .
//      add_bk_filter('wpbc_add_new_booking_filter' , 'wpbc_add_new_booking' );
// TODO: recheck  hooks in wpbc-dev-api.php