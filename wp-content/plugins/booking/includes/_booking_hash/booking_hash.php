<?php /**
 * @version 1.0
 * @description Booking Hash Functions
 * @category  Booking Hash
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2022-08-05
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

//     H   A   S   H                                                                                                            //FixIn: 9.2.3.3

/**
 * Get booking ID and resource ID  by booking HASH
 *
 * @param $booking_hash
 *
 * @return array|false		-   array( $booking_id, $resource_id )  |  false 		if not found
 */
function wpbc_hash__get_booking_id__resource_id( $booking_hash ) {

	if ( '' == $booking_hash ) {
		return false;
	}
	global $wpdb;

	if ( class_exists( 'wpdev_bk_personal' ) ) {

		$sql = $wpdb->prepare( "SELECT booking_id as id, booking_type as type FROM {$wpdb->prefix}booking as bk  WHERE  bk.hash = %s", $booking_hash );

		$res = $wpdb->get_results( $sql );

		if ( ( ! empty( $res ) ) && ( is_array( $res ) ) && ( isset( $res[0]->id ) ) && ( isset( $res[0]->type ) ) ) {          //FixIn: 8.1.2.13
			return array( $res[0]->id, $res[0]->type );
		}
	} else {

		$sql = $wpdb->prepare( "SELECT booking_id as id FROM {$wpdb->prefix}booking as bk  WHERE  bk.hash = %s", $booking_hash );

		$res = $wpdb->get_results( $sql );

		if ( ( ! empty( $res ) ) && ( is_array( $res ) ) && ( isset( $res[0]->id ) ) ) {                                        //FixIn: 8.1.2.13
			return array( $res[0]->id, 1 );
		}
	}

	return false;
}


/**
 * Get booking HASH and resource ID
 * by booking ID
 *
 * @param $booking_id
 *
 * @return array|false		-   array( $hash, $resource_id )  |  false 		if not found
 */
function wpbc_hash__get_booking_hash__resource_id( $booking_id ) {

	if ( '' == $booking_id ) {
		return false;
	}
	global $wpdb;

	if ( class_exists( 'wpdev_bk_personal' ) ) {

		$sql = $wpdb->prepare( "SELECT hash, booking_type as type FROM {$wpdb->prefix}booking as bk  WHERE  bk.booking_id = %d", $booking_id );

		$res = $wpdb->get_results( $sql );

		if ( ( ! empty( $res ) ) && ( is_array( $res ) ) && ( isset( $res[0]->hash ) ) && ( isset( $res[0]->type ) ) ) {
			return array( $res[0]->hash, $res[0]->type );
		}
	} else {

		$sql = $wpdb->prepare( "SELECT hash FROM {$wpdb->prefix}booking as bk  WHERE  bk.booking_id = %d", $booking_id );

		$res = $wpdb->get_results( $sql );

		if ( ( ! empty( $res ) ) && ( is_array( $res ) ) && ( isset( $res[0]->hash ) ) ) {
			return array( $res[0]->hash, 1 );
		}
	}

	return false;
}


/**
 * Update booking hash to newly generated. 	Run after creation/modification of booking in post request
 *
 * @param $booking_id
 * @param $resource_id
 *
 * @return void
 */
function wpbc_hash__update_booking_hash( $booking_id, $resource_id = '1' ) {
	global $wpdb;

	$update_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.hash = MD5(%s) WHERE bk.booking_id = %d"
									, time() . '_' . rand( 1000, 1000000 )
									, $booking_id
								);
	if ( false === $wpdb->query( $update_sql ) ) {
		?><script type="text/javascript"> document.getElementById( 'submiting<?php echo $resource_id; ?>' ).innerHTML = '<div style=&quot;height:20px;width:100%;text-align:center;margin:15px auto;&quot;><?php debuge_error( 'Error during updating hash in BD', __FILE__, __LINE__ ); ?></div>'; </script> <?php
		die();
	}
}


/**
 * Get JavaScript for start dates selection  in calendar after 1.5 second
 *
 * @param array $to_select__dates_sql_arr
 * @param int 	$resource_id
 *
 * @return string	-	 JavaScript code as text
 */
function wpbc_get_dates_selection_js_code( $to_select__dates_sql_arr, $resource_id ){

	$dates_selection_arr = array();

	foreach ( $to_select__dates_sql_arr as $date_time ) {

		$date_time  = trim( $date_time );
		$date_time = explode( ' ', $date_time );
		$date_only = explode( '-', $date_time[0] );

		$dates_selection_arr[] = 'new Array( ' . $date_only[0] . ', ' . $date_only[1] . ', ' . $date_only[2] . ' ) ';
	}
	$dates_selection = 'new Array( ' . implode( ', ', $dates_selection_arr ) . ' ) ';

	$dates_selection_js_code  = '<script type="text/javascript">';
	$dates_selection_js_code .= 'jQuery(document).ready(function(){';
	$dates_selection_js_code .= ' timeout_DSwindow = setTimeout( "wpbc_select_days_in_calendar(' . $resource_id . ', ' . $dates_selection . ' )", 1500);';
	$dates_selection_js_code .= '});';
	$dates_selection_js_code .= '</script>';

	return $dates_selection_js_code;
}


/**
 * Get booking ID,  from  the $_GET['booking_hash'] in url
 *
 * @param $booking_hash		if default false,  then  get  hash  from $_GET['booking_hash'] Otherwise get  it from  this parameter
 *
 * @return int|string	ID of booking or '', if not found
 */
function wpbc_get_booking_id__from_hash_in_url( $booking_hash = false ){

	$result_arr =  wpbc_get_booking_arr__from_hash_in_url( $booking_hash );

	return $result_arr['booking_id'];
}


/**
 * Get booking ID,  from  the $_GET['booking_hash'] in url
 *
 * @param $booking_hash		if default false,  then  get  hash  from $_GET['booking_hash'] Otherwise get  it from  this parameter
 *
 * @return int|string	ID of ooking resource or '', if not found
 */
function wpbc_get_resource_id__from_hash_in_url( $booking_hash = false ){

	$result_arr = wpbc_get_booking_arr__from_hash_in_url( $booking_hash );

	return $result_arr['resource_id'];
}



/**
 * Get booking data ['booking_id'  => 2453, 'resource_id' => 4 ],  from  the   URL   -- $_GET['booking_hash']
 *
 * @param $booking_hash		if default false,  then  get  hash  from $_REQUEST['booking_hash'] 		Otherwise get  it from  this parameter
 *
 * @return array        if not found then 	['booking_id'  => '',   'resource_id' => '']
 * 						otherwise  			['booking_id'  => 2453, 'resource_id' => 4 ]
 */
function wpbc_get_booking_arr__from_hash_in_url( $booking_hash = false ){

	if ( empty( $booking_hash ) ) {
		$booking_hash = ( isset( $_REQUEST['booking_hash'] ) ) ? $_REQUEST['booking_hash'] : '';
	}

	$booking_id  = '';
	$resource_id = '';

	if ( ! empty( $booking_hash ) ) {
		$my_booking_id_type = wpbc_hash__get_booking_id__resource_id( $booking_hash );

		if (false !== $my_booking_id_type ){
			list( $booking_id, $resource_id ) = $my_booking_id_type;
		}
	}

	$result = array(
					'booking_id'  => $booking_id,
					'resource_id' => $resource_id
				);

	return $result;

}