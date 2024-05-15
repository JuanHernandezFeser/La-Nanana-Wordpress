<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4

// ---------------------------------------------------------------------------------------------------------------------
// Check  where to  save the booking
// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * Get booking resources for each  selected date, where we can save the booking,  or false if we can not save it
	 *
	 * @param array $params = [
	 *							'resource_id'                 => 2
	 *							'skip_booking_id'             => '',            |  125 if edit booking
	 *						    'dates_only_sql_arr'          => [ "2023-10-18", "2023-10-25", "2023-11-25" ]
	 *							'time_as_seconds_arr'         => [ 36000, 39600 ]
     *                          'how_many_items_to_book'      => 1
	 *                          'request_uri'                 => 'http://beta/resource-id2/'            // Optional     default ->  DOING_AJAX  ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI']
	 *                          'as_single_resource'          => false,                                 // Optional     default false
	 *                        'is_use_booking_recurrent_time' => false                                  // Optional     default ( 'On' === get_bk_option( 'booking_recurrent_time' ) )
	 *                       ]
	 * @return array       = [ 'result' => 'ok', 'main__resource_id' => 2, 'resources_in_dates' => [  '2023-09-23':[2,10,11],  '2023-09-24':[2,10,11]  ],   'time_to_book' => [ "00:00:00", "24:00:00" ]   ]
	 *                              Note.  'main__resource_id'  - it's first booking resource in the list, basically  needed for saving in wp_booking DB table.
	 *                              OR
	 *                     = [ 'result' => 'error', 'message' => 'Booking can not be saved ...' ]
	 */
	function wpbc__where_to_save_booking( $local_params ){

		$defaults = array(
						'request_uri'                   => ( ( ( defined( 'DOING_AJAX' ) ) && ( DOING_AJAX ) ) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'] ), //  front-end: $_SERVER['REQUEST_URI'] | ajax: $_SERVER['HTTP_REFERER']                      // It different in Ajax requests. It's used for change-over days to detect for exception at specific pages
						'as_single_resource'            => false,
						'is_use_booking_recurrent_time' => ( 'On' === get_bk_option( 'booking_recurrent_time' ) ),
						'aggregate_resource_id_arr'     => array()
					);
		$local_params   = wp_parse_args( $local_params, $defaults );

		$time_as_seconds_arr    = $local_params['time_as_seconds_arr'];
		$time_as_seconds_arr[0] = ( 0 != $time_as_seconds_arr[0] ) ? $time_as_seconds_arr[0] + 1 : $time_as_seconds_arr[0];                 // +1 s. -- set check  in time with  ended 1 second
		$time_as_seconds_arr[1] = ( ( 24 * 60 * 60 ) != $time_as_seconds_arr[1] ) ? $time_as_seconds_arr[1] + 2 : $time_as_seconds_arr[1];  // +2 s. -- set check out time with  ended 2 seconds
		$local_params['time_as_his_arr'] = array(
													wpbc_transform__seconds__in__24_hours_his( $time_as_seconds_arr[0] ),
													wpbc_transform__seconds__in__24_hours_his( $time_as_seconds_arr[1] )
											);

		// If we are using the unavailable before/after booking dates,  then we need to extend "$local_params['dates_only_sql_arr']" to such  dates.
		if ( function_exists( 'wpbc__extend_checking_dates_range__if_used__extra_seconds__before_after' ) ) {
			$maybe_extended__dates_to_check_arr = wpbc__extend_checking_dates_range__if_used__extra_seconds__before_after( $local_params['dates_only_sql_arr'] );
		} else {
			$maybe_extended__dates_to_check_arr = $local_params['dates_only_sql_arr'];
		}
		// [ "dates": [ "2023-11-05": [ "day_availability":4, ... "2": [ "is_day_unavailable":false, ...]], '2023-11-06':[...] ] ,"resources_id_arr__in_dates":[2,12,10,11]}
		$availability_per_days = wpbc_get_availability_per_days_arr( array(
																			'resource_id'        => $local_params['resource_id'],
																			'skip_booking_id'    => $local_params['skip_booking_id'],
																			'dates_to_check'     => $maybe_extended__dates_to_check_arr,
																			'request_uri'        => $local_params['request_uri'],
																			'as_single_resource' => $local_params['as_single_resource'],     // default FALSE  ::  get dates as for 'single resource' or 'parent' resource including bookings in all 'child booking resources'
																			'additional_bk_types' => $local_params['aggregate_resource_id_arr']
																		) );

		// Get value of how many  booking resources to  book
		$how_many_items_to_book = $local_params['how_many_items_to_book'];
		// -------------------------------------------------------------------------------------------------------------
		// [ 2023-10-18: [ 0:[ resource_id: 2, is_available: true, booked__seconds: [], booked__readable: [], time_to_book__seconds: [ 36030, 39570 ], time_to_book__readable: "10:00:00", "11:00:00" ] ], 1: [...], 2: [...], 3: [...] ], 2023-10-25: [...], 2023-11-25: [...] ]
		$available_slots = wpbc__get_available_slots__for_selected_dates_times__bl( array(
																						'dates_sql__arr'                => $local_params['dates_only_sql_arr'],
																						'time_as_seconds_arr'           => $local_params['time_as_seconds_arr'],
																						'is_use_booking_recurrent_time' => $local_params['is_use_booking_recurrent_time'],
																						'availability_per_days'         => $availability_per_days
																					) );

		$main__resource_id = 0;
		// == In      SAME      'child' booking resources   ============================================================
		if ( 'On' === get_bk_option( 'booking_is_dissbale_booking_for_different_sub_resources' ) ) {

			$availability_in_same_child_resources = $availability_per_days['resources_id_arr__in_dates'];

			foreach ( $availability_per_days['resources_id_arr__in_dates'] as $child_resource_id ) {
				foreach ( $local_params['dates_only_sql_arr'] as $day_sql_key ) {
					foreach ( $available_slots[ $day_sql_key ] as $available_slot ) {
						if (
							    ( $child_resource_id == $available_slot['resource_id'] )
						     && ( ! $available_slot['is_available'] )
						){
							$availability_in_same_child_resources = array_diff( $availability_in_same_child_resources, array( $child_resource_id ) );       // Remove $child_resource_id from  array
							break;
						}
					}
				}
			}

			// Reset  indexes in this array  for ability to  get  first [0]  element in this arr.
			$availability_in_same_child_resources = array_values( $availability_in_same_child_resources );
			$main__resource_id = ( ! empty( $availability_in_same_child_resources ) ) ? $availability_in_same_child_resources[0] : 0;

			// [ 2, 12, 11 ]  	<- 		ID of child booking resources, where we can  save our booking in the same child booking resource!
			if ( count( $availability_in_same_child_resources ) >= $how_many_items_to_book ) {

				$availability_in_same_child_resources_by_dates = array();
				foreach ( $local_params['dates_only_sql_arr'] as $day_sql_key ) {
					$availability_in_same_child_resources_by_dates[ $day_sql_key ] = $availability_in_same_child_resources;
				}
				// Good Save it
				return array(
								'result'             => 'ok',
								'resources_in_dates' => $availability_in_same_child_resources_by_dates,                 // [ 2023-09-23 = [ 2, 10, 11 ],  2023-09-24 = [  2, 10, 11 ]
								'time_to_book'       => $local_params['time_as_his_arr'],                               // [ "00:00:00", "24:00:00" ]
								'main__resource_id'  => $main__resource_id
							);
			} else {
				// Bad not save
				return array(
								'result'  => 'error',
								'message' => __( 'These dates and times in this calendar are already booked or unavailable.', 'booking' )
								             . ' <br> '
											 . __( 'It is not possible to store this sequence of the dates into the one same resource.' , 'booking' )
								             . ' <br> '
											 . __( 'Please choose alternative date(s), times, or adjust the number of slots booked.' , 'booking' )
											 . ' <a href="javascript:void(0)" onclick="'
								                             .'jQuery( this ).next().toggle();'
								                             .'jQuery( this).hide();'
															 .'jQuery( this ).parents(\'.wpbc_alert_message\').parent().on(\'hide\',function(even){jQuery(this).show();});'
				                                    .'">'
								             .    __( 'Show more details' )
								             . '</a>'
								             . ' <div style="display:none;">'
								                    . '<p><hr><code>'
								                    . json_encode( $available_slots )
								                    . '</code></p>'
								             . '</div>'
								             . ' <div style="display:none;">' . json_encode( $available_slots ) . '</div>'
							);
			}

		} else {

			// == In      ANY      'child' booking resources   =========================================================

			$availability_in_any_child_resources = array();
			foreach ( $available_slots as $day_sql_key => $available_slot ) {                           // '2023-09-10': [], '2023-09-14': [],
				$availability_in_any_child_resources[ $day_sql_key ] = array();
				foreach ( $available_slot as $ind => $slot_value ) {                                    // [ 0:{ resource_id: 2,  is_available: false,...} , .... ]
					if ( $slot_value['is_available'] ) {
						if ( empty( $main__resource_id ) ) {
							$main__resource_id = $slot_value['resource_id'];
						}
						$availability_in_any_child_resources[ $day_sql_key ][] = $slot_value['resource_id'];
					}
				}
			}
	        /**
	         * {    "2023-09-10": [ 2, 10, 11 ]				<- available ID of child resources in this date
	                "2023-09-14": [ 4 ]						<- available ID of child resources in this date
	           }
	         */

			foreach ( $availability_in_any_child_resources as $day_sql_key2 => $available_res_in_day_arr ) {
				if ( count( $available_res_in_day_arr ) < $how_many_items_to_book ) {
					// Bad not save
					// We can not store booking in this date  day_sql_key2,  number of available child booking resources less than  required
					return array(
								'result'  => 'error',
								'message' => __( 'These dates and times in this calendar are already booked or unavailable.', 'booking' )
								             . ' <br> '
											 . __( 'Please choose alternative date(s), times, or adjust the number of slots booked.' , 'booking' )
											 . ' <a href="javascript:void(0)" onclick="'
								                             .'jQuery( this ).next().toggle();'
								                             .'jQuery( this).hide();'
															 .'jQuery( this ).parents(\'.wpbc_alert_message\').parent().on(\'hide\',function(even){jQuery(this).show();});'
				                                    .'">'
								             .    __( 'Show more details' )
								             . '</a>'
								             . ' <div style="display:none;">'
								                    . '<p><strong>'
										            . sprintf( __( 'Booking can not be saved in this date %s, number of available (single or child) booking resource(s) (%d) less than required (%d).', 'booking' )
																, $day_sql_key2, count( $available_res_in_day_arr ), $how_many_items_to_book )
													. '</strong><hr><code>'
								                    . json_encode( $available_slots )
								                    . '</code></p>'
								             . '</div>'

							);
				}
			}

			// Good Save it
			return  array(
								'result'             => 'ok',
								'resources_in_dates' => $availability_in_any_child_resources,                           // [ 2023-09-23 = [ 2, 10, 11 ],  2023-09-24 = [  2, 10, 11 ]
								'time_to_book'       => $local_params['time_as_his_arr'],                                // [ "00:00:00", "24:00:00" ]
								'main__resource_id'  => $main__resource_id
							);
		}
	}


		/**
		 * Get available slots per each date in each slot (child resource). It's checking times as well.
		 *
		 * @param $params       [
		 *							dates_sql__arr        = [ "2023-10-18", "2023-10-25", "2023-11-25" ]
		 *							time_as_seconds_arr   = [ 36030,  39570 ]
		 *							availability_per_days = [ dates = [...], resources_id_arr__in_dates = [...] ]
		 *                      ]
		 *
		 * @return array        [
		 *							2023-10-18 = [
		 *											 0 = [
		 *												  resource_id = 2
		 *												  is_available = true
		 *												  booked__seconds = []
		 *												  booked__readable = []
		 *												  time_to_book__seconds = [ 36030,  39570 ]
		 *												  time_to_book__readable =  "10:00:00",  "11:00:00" ]
		 *												]
		 *											 1 = [...]
		 *											 2 = [...]
		 *											 3 = [...]
		 *                                       ]
		 *							2023-10-25 = [...]
		 *							2023-11-25 = [...]
		 *                       ]
		 */
		function wpbc__get_available_slots__for_selected_dates_times__bl( $params ) {

			$defaults = array(
								'dates_sql__arr'         => array(),
								'time_as_seconds_arr'    => array(),
								'availability_per_days'  => array(),
								'is_use_booking_recurrent_time' => ( 'On' === get_bk_option( 'booking_recurrent_time' ) )
							);
			$params   = wp_parse_args( $params, $defaults );

			$time_as_seconds_arr = $params['time_as_seconds_arr'];

	        // Shift time interval  in 30 seconds
			$shift__30sec = array( 30, 30 );
	        $time_as_seconds_arr[ 0 ] = $time_as_seconds_arr[ 0 ] + $shift__30sec[0];
	        $time_as_seconds_arr[ 1 ] = $time_as_seconds_arr[ 1 ] - $shift__30sec[1];

			$available_resources_arr = array();

			foreach ( $params['dates_sql__arr'] as $sql_class_day_number => $sql_class_day ) {
				$available_resources_arr[ $sql_class_day ] = array();
				$date_shift__30sec = $shift__30sec;

				if ( isset( $params['availability_per_days']['dates'][ $sql_class_day ] ) ) {

					$this_date_time_as_seconds_arr = $time_as_seconds_arr;

					//TODO: debug   this checking in  biz_l.js   2023-09-05 16:40    ---   If we are using not time slot but the check in/out times ?
					if (
						   ( ! $params['is_use_booking_recurrent_time'] )
						&& ( count( $params['dates_sql__arr'] ) > 1 )
					) {
						if ( 0 == $sql_class_day_number ) {                                                             // check in
							$this_date_time_as_seconds_arr[1] = 24 * 60 * 60;
							$date_shift__30sec[1]             = 0;
						} else if ( ( count( $params['dates_sql__arr'] ) - 1 ) == $sql_class_day_number ) {             // check out
							$this_date_time_as_seconds_arr[0] = 0;
							$date_shift__30sec[0]             = 0;
						} else {
							$this_date_time_as_seconds_arr = array( 0, 24 * 60 * 60 );
							$date_shift__30sec             = array( 0, 0 );
						}
					}

					$date_bookings_obj = $params['availability_per_days']['dates'][ $sql_class_day ];

					 foreach ( $params['availability_per_days']['resources_id_arr__in_dates'] as $child_resource_id ) {

	                    if ( $date_bookings_obj[ $child_resource_id ]->is_day_unavailable ){
		                    $merged_seconds = array( array( 0, 24 * 60 * 60 ) );                                            // Unavailable
		                    $merged_readable = array( wpbc_transform_timerange__seconds_arr__in__formated_hours( array( 0, 24 * 60 * 60 ) ) );
	                    } else {
	                        $merged_seconds  = $date_bookings_obj[ $child_resource_id ]->booked_time_slots['merged_seconds'];       // [  [ 25211, 27002 ], [ 36011, 86400 ]  ]    // Time slots or Available
		                    $merged_readable = $date_bookings_obj[ $child_resource_id ]->booked_time_slots['merged_readable'];
	                    }

	                    $is_intersect = wpbc_is_intersect__range_time_interval(
	                                                                            array(
	                                                                                array(
	                                                                                  intval( $this_date_time_as_seconds_arr[ 0 ] ) + 20
	                                                                                , intval( $this_date_time_as_seconds_arr[ 1 ] ) - 20
	                                                                                )
	                                                                            )
	                                                                         , $merged_seconds );


						$this_date_available_resource = array(
										                        'resource_id'           => $child_resource_id,
										                        'is_available'          => (! $is_intersect),
										                        'booked__seconds'       => $merged_seconds,
										                        'booked__readable'      => $merged_readable,
										                        'time_to_book__seconds' => $this_date_time_as_seconds_arr,
										                        'time_to_book__readable'=> array()
										                    );
						$this_date_available_resource['time_to_book__readable'][] = wpbc_transform__seconds__in__24_hours_his(
																						 (
																							    ( ( $this_date_time_as_seconds_arr[0] - $date_shift__30sec[0] ) < 0 )
																								? 0
																							    : ( $this_date_time_as_seconds_arr[0] - $date_shift__30sec[0] )
																						 )
						                                                            );
						$this_date_available_resource['time_to_book__readable'][] = wpbc_transform__seconds__in__24_hours_his(
																						(
																								( ( $this_date_time_as_seconds_arr[1] + $date_shift__30sec[1] ) > 86400 )
																								? 86400
																								: ( $this_date_time_as_seconds_arr[1] + $date_shift__30sec[1] )
																						)
																					);

	                    $available_resources_arr[ $sql_class_day ][] = $this_date_available_resource;
					 }
				}
			}

			return $available_resources_arr;
		}


			/**
			 * Is these array of intervals intersected ?    -   overlay of Math function
			 *
			 * @param array $time_interval_A		array( array( 21600, 23400 )                         )
			 * @param array $time_interval_B		array( array( 25211, 27002 ), array( 36011, 86400 )  )
			 *
			 * @return bool
			 */
			function wpbc_is_intersect__range_time_interval( $time_interval_A, $time_interval_B ){

				for ( $i = 0; $i < count($time_interval_A); $i++ ){

					for ( $j = 0; $j < count($time_interval_B); $j++ ){

						$is_intersect = wpbc_is__intervals__intersected( $time_interval_A[ $i ], $time_interval_B[ $j ] );

						if ( $is_intersect ){
							return true;
						}
					}
				}

				return false;
			}
