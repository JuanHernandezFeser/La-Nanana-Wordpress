<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/**
 * Bookings from  AGGREGATE parameter adding ONLY  to  PARENT booking resource !.   But unavailable dates from  Booking > Availability page added to  PARENT and CHILDREN booking resources.
 */

/**
 * Merge unavailable dates from  AGGREGATE resources into REAL resource
 *
 * @param $unavailable_dates__per_resources__arr            =  [
																	  3 => [    0 => '2023-12-01',
																			    1 => '2023-12-02',
																			    2 => '2023-12-03'
																	       ],
																	  4 => [
																			    0 => '2023-12-03',
																			    1 => '2023-12-04',
																			    2 => '2023-12-05',
																	       ],
																	  25 =>[
																			    0 => '2023-12-09',
																			    1 => '2023-12-10',
																	       ]
																]
 * @param $aggregate_resource_id_arr                        = [ 3, 25 ]
 * @param $aggregate_resource_id_arr                        = [ 4 ]
 *
 * @return mixed
 */
function wpbc_aggregate_merge_availability( $unavailable_dates__per_resources__arr, $source_res_id__with_children__arr,  $aggregate_resource_id_arr ) {

	$new_resource_arr__dates_arr = array();

	// Get availability only  for Real Resource
	foreach ( $source_res_id__with_children__arr as $source_res_id ) {

		if ( ! in_array( $source_res_id, $aggregate_resource_id_arr ) ) {
			$new_resource_arr__dates_arr[ $source_res_id ] = array();

			if ( ! empty( $unavailable_dates__per_resources__arr[ $source_res_id ] ) ) {
				$new_resource_arr__dates_arr[ $source_res_id ] = $unavailable_dates__per_resources__arr[ $source_res_id ];
			}
		}
	}


	// Add aggregate resources availability
	foreach ( $aggregate_resource_id_arr as $aggregate_res_id ) {
		if ( ! empty( $unavailable_dates__per_resources__arr[ $aggregate_res_id ] ) ) {

			// Go by dates
			foreach ( $unavailable_dates__per_resources__arr[ $aggregate_res_id ] as $date_unavailable ) {

				// Now need to  add this date into all Real resources (which  can  be parent and child resources )
				foreach ( $new_resource_arr__dates_arr as $merged_resource_id => $dates_array ) {

					$new_resource_arr__dates_arr[ $merged_resource_id ][] = $date_unavailable;           // Append date to merged real resource array

					$dates_array = array_unique( $new_resource_arr__dates_arr[ $merged_resource_id ] );                                    // Remove Duplicates
					sort( $new_resource_arr__dates_arr[ $merged_resource_id ] );                                                           // Sort
				}
			}

		}
	}


	return $new_resource_arr__dates_arr;
}


/**
 * Merge all  bookings from AGGREGATE resources into REAL resources
 *
 * @param $resources__booking_id__obj           = [
 *                                                   3 = [
 *                                                              5370 = {stdClass}
 *                                                       ]
 *                                                   4 = [
 *                                                              5369 = {stdClass}
 *                                                              5364 = {stdClass}
 *                                                              5367 = {stdClass}
 *                                                              5366 = {stdClass}
 *                                                              5368 = {stdClass}
 *                                                       ]
 *                                                 ]
 * @param $source_res_id__with_children__arr [ 3, 25 ]
 * @param $aggregate_resource_id_arr [ 4 ]
 *
 * @return array                                 = [
 *                                                     4 = [
 *                                                              5370 = {stdClass}
 *                                                              5369 = {stdClass}
 *                                                              5364 = {stdClass}
 *                                                              5367 = {stdClass}
 *                                                              5366 = {stdClass}
 *                                                              5368 = {stdClass}
 *                                                         ]
 *                                                   ]
 */
function wpbc_aggregate__merge_bookings__from_aggregate__in_source_resource__arr( $resources__booking_id__obj, $source_res_id__with_children__arr,  $aggregate_resource_id_arr ) {

	$new_resource_arr__bookings_arr = array();

	// Get availability only  for Real Resource
	foreach ( $source_res_id__with_children__arr as $source_res_id ) {

		if ( ! in_array( $source_res_id, $aggregate_resource_id_arr ) ) {
			$new_resource_arr__bookings_arr[ $source_res_id ] = array();

			if ( ! empty( $resources__booking_id__obj[ $source_res_id ] ) ) {
				$new_resource_arr__bookings_arr[ $source_res_id ] = wpbc_clone_array_of_objects( $resources__booking_id__obj[ $source_res_id ] );
			}
		}
	}



	// Add aggregate resources bookings into  ALL source resources
	foreach ( $aggregate_resource_id_arr as $aggregate_res_id ) {
		if ( ! empty( $resources__booking_id__obj[ $aggregate_res_id ] ) ) {

			// Go by bookings
			foreach ( $resources__booking_id__obj[ $aggregate_res_id ] as $aggregate_booking_id => $aggregate_booking_obj ) {

				// Now need to add this   $aggregate_bookings_arr      into all Real resources (which  can  be parent and child resources )
				foreach ( $new_resource_arr__bookings_arr as $merged_resource_id => $bookings_array ) {
						/*
						// $aggregate_booking_obj->form   it has ID as  $aggregate_res_id     but we need to have NEW   as   $merged_resource_id

						$new_resource_id = $merged_resource_id;
						$old_resource_id = $aggregate_res_id;
						$aggregate_booking_obj->form = wpbc_get__form_data__with_replaced_id( $aggregate_booking_obj->form, $new_resource_id, $old_resource_id );
						$aggregate_booking_obj->type = $new_resource_id;
						*/
						$new_resource_arr__bookings_arr[ $merged_resource_id ][ $aggregate_booking_id ] = wpbc_clone_array_of_objects( $aggregate_booking_obj );
				}
			}

		}
	}


	return $new_resource_arr__bookings_arr;
}