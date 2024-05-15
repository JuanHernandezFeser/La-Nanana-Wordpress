<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


global $wpbc_global_cache;                                                                                              //FixIn: 9.7.3.14
$wpbc_global_cache = array();

// GET Saved request ---------------------------------------------------------------------------------------------------

/**
 * GET saved cache request result,  if exists       otherwise   return  null
 *
 * @param string $function_name             'wpbc__sql__get_booking_dates'  - name of function,  where saved DB request
 * @param string|array  $params             [ array with  parameters in this function,  that  saved ]
 *
 * @return array|mixed|object|null          if not saved previously  then  returned null
 */
function wpbc_cache__get( $function_name, $params ){

	// return null;    // Stop caching hack

    global $wpbc_global_cache;

	if ( ! is_serialized( $params ) ) {
		$params_cache = maybe_serialize( $params );
	} else {
		$params_cache = $params;
	}

	if (
		   ( isset( $wpbc_global_cache[ $function_name ] ) )
	    && ( isset( $wpbc_global_cache[ $function_name ][ $params_cache ] ) )
	){

		//return $wpbc_global_cache[ $function_name ][ $params_cache ];

		// CLONING array of objects
		$clone_array_of_objects = wpbc_clone_array_of_objects( $wpbc_global_cache[ $function_name ][ $params_cache ] );

		return $clone_array_of_objects;
	}

	return null;
}


// SAVE ----------------------------------------------------------------------------------------------------------------


/**
 * SAVE request result to CACHE var
 *
 * @param string $function_name      'wpbc__sql__get_booking_dates'  - name of function,  where saved DB request
 * @param string|array  $params      [ array with  parameters in this function,  that  saved ]
 * @param mixed $value_to_save        value  to  save -- usually  it's result from  DB
 *
 * @return mixed                     return CLONED   $value_to_save
 */
function wpbc_cache__save( $function_name, $params, $value_to_save ){

    global $wpbc_global_cache;


	if ( ! is_serialized( $params ) ) {
		$params_cache = maybe_serialize( $params );
	} else {
		$params_cache = $params;
	}


	if ( ! isset( $wpbc_global_cache[ $function_name ] ) ) {
		$wpbc_global_cache[ $function_name ] = array();
	}

	//$wpbc_global_cache[ $function_name ][ $params_cache ] = $value_to_save;

	// CLONING array of objects
	$wpbc_global_cache[ $function_name ][ $params_cache ] = wpbc_clone_array_of_objects( $value_to_save );

	return $wpbc_global_cache[ $function_name ][ $params_cache ];
}





/**
 * Clone array  of Objects
 * Return independent array instances, instead of reference to previous objects.
 * Useful, if we do not need to apply changes in original array
 *
 * @param array|object|mixed $object_arr
 *
 * @return array|object|mixed
 *
 *
 * CLONING tips...
 *
			 We clone array of booking Objects,  because later  we make manipulations with some properties, e.g.   unset()
			 for example on this line:
			                             unset( $this_date_bookings[ $booked_times__key ]->form );         // Hide booking details

			 and for having (not changed Cache object from DB  $wpbc_global_cache['wpbc__sql__get_booking_dates'][ $params_cache ] )
			 During loading bookings in wpbc_get__booked_dates__per_resources__arr(...)

			 we need to  clone each  such  object, otherwise if we change something in this variable,  it will be changed
			 in cache  $wpbc_global_cache['wpbc__sql__get_booking_dates'][ $params_cache ] ),  as well

			 Otherwise, if we will  not clone and use this,  then we do not need to  unset some properties:

			 $this_date_bookings = $booked_dates__per_resources__arr[ $my_day_tag ][ $resource_id ];
				v.s.
			 $this_date_bookings = array_map(
											   function ( $object ) { return clone $object; }
											 , $booked_dates__per_resources__arr[ $my_day_tag ][ $resource_id ]
										);

 */
function wpbc_clone_array_of_objects( $object_arr ){

	$cloned_result = false;

	if ( is_array( $object_arr ) ) {

		$cloned_result = array_map(
									function ( $object ) {
										return ( ( is_object( $object ) ) ? ( clone $object ) : $object );
									}
									, $object_arr
								);

	} else if ( is_object( $object_arr ) ) {

		$cloned_result = ( clone $object_arr );

	} else {
		$cloned_result = $object_arr;
	}

	return $cloned_result;
}
