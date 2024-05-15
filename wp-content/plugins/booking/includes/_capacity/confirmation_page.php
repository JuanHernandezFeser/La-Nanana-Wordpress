<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4

// ---------------------------------------------------------------------------------------------------------------------
// == Confirmation Page
// ---------------------------------------------------------------------------------------------------------------------
/*
//Delete it.
function wpbc_shortcode__booking_confirmation_debug($attr, $content, $tag) {

	// Normalize attribute keys, lowercase
	$atts = array_change_key_case( (array) $attr, CASE_LOWER );

	// override default attributes with user attributes
	$wpbc_atts = shortcode_atts( array(
										'mode' => 'calendar',
										'id'   => 0
					) , $atts, $tag );

	$attr = wpbc_escape_shortcode_params( $wpbc_atts );


	$content = preg_replace( '/\R+/', '', $content );
	$content_utf = mb_convert_encoding( html_entity_decode( $content ), 'HTML-ENTITIES', 'UTF-8' );
	$search = array( '&lsquo;', '&rsquo;', '&ldquo;', '&rdquo;', '&mdash;' );
	$replace = array( "'", "'", '"', '"', '-' );
    $content_utf2 = str_replace($search, $replace, $content_utf);

	$content_utf2 = strip_tags( $content_utf2 );

	$content_json =  json_decode( $content_utf2 );
	debuge($content_json);

	die;


	$content_str = json_encode( $content_json );
	$content_str = str_replace(',', ",\n", $content_str);

	$return = json_encode( $attr ) . '<p><pre>' . $content_str . '</pre></p>' . $tag;

	$return .=  '<hr/>';

    return $return ;
}
*/

	/**
	 * Load shortcode checking correctly
	 * @return void
	 */
	function wpbc_init_shortcode__wpbc_booking_confirmation() {

		// Docs:  https://developer.wordpress.org/plugins/shortcodes/basic-shortcodes/

		// Universal  shortcode 'wpbc' with  'mode' parameter:
		// Example #1: [wpbc mode="booking_confirm"]                                                    <- Get Hash  from  $_GET['booking_hash']
		// Example #2: [wpbc mode="booking_confirm" booking_hash="b190e1a1fc6480c03a59444956d3ed22"]    <- Always use this HASH from  parameter
		add_shortcode( 'wpbc', 'wpbc_shortcode__booking_confirmation' );

		// ShortHand
		add_shortcode( 'booking_confirm', 'wpbc_do_shortcode__booking_confirm' );
	}
	add_action( 'init', 'wpbc_init_shortcode__wpbc_booking_confirmation', 9999 );                                       // <- priority  to  load it last


	/**
	 * Universal shortcode worker
	 *
	 * @param array $attr
	 * @param string|null $content
	 * @param string $tag
	 *
	 * @return string
	 */
	function wpbc_shortcode__booking_confirmation( $attr = array(), $content = null, $tag = '' ) {

		// ob_start();

		$atts = array_change_key_case( (array) $attr, CASE_LOWER );                                                     // Changes the case of all keys in an array

		/*
			$wpbc_atts = shortcode_atts( array(
											'mode' => 'calendar',
											'id'   => 1
						) , $atts );                                                    // Override default attributes with user attributes

			$attr = wpbc_escape_shortcode_params( $wpbc_atts );                             // My escaping
		*/

		$return = '';

		switch ( $attr['mode'] ) {

		    case 'booking_confirm':

			    $return .= wpbc_do_shortcode__booking_confirm( $attr );
		        break;

		    default:
			    $return = '<p style="font-size:9px;">'
			              . 'Invalid shortcode configuration: <strong>[' . $tag . ' ... ]</strong> with the following parameters: ' . json_encode( $attr ) . '. '
			              . sprintf( __( 'Find more in the %sFAQ%s', 'booking' ), '<a href="https://wpbookingcalendar.com/faq/">', '</a>.' )
			              . '</p>';
		}

		// $possible_output = ob_get_clean();
		// $return .= $possible_output;

	    return $return ;
	}



function wpbc_do_shortcode__booking_confirm( $attr ){

	if ( wpbc_is_on_edit_page() ) {
		return wpbc_get_preview_for_shortcode( 'booking_confirm', array() );      //FixIn: 9.9.0.39
	}

	ob_start();

	$atts = array_change_key_case( (array) $attr, CASE_LOWER );                     // Changes the case of all keys in an array

	$wpbc_atts = shortcode_atts( array(
										//'mode'         => 'calendar',   // ?
										//'id'           => 1,            // ?
										'booking_hash' => ''                        // Optional!. Required only for specific situation to show always SAME booking at this page without the $_REQUEST['booking_hash']
					) , $atts );                                                    // Override default attributes with user attributes

	$attr = wpbc_escape_shortcode_params( $wpbc_atts );                             // My escaping

	$booking_resource_id__arr = wpbc_get_booking_arr__from_hash_in_url( $attr['booking_hash'] );        // <- $attr['booking_hash'] Optional and usually  it == '', required only for specific situation to show always SAME booking at this page!
	$booking_id  = intval( $booking_resource_id__arr['booking_id'] );
	$resource_id = intval( $booking_resource_id__arr['resource_id'] );

	if (  empty( $booking_id ) ) {

		return '<strong>' . __('Oops!' ,'booking') . '</strong> '
		                  . __('We could not find your booking. The link you used may be incorrect or has expired. If you need assistance, please contact our support team.' ,'booking');
	} else {


		// DB Booking Details
		$booking_data  = wpbc_db_get_booking_details( $booking_id );      // {... , "form":"text^cost_hint4^\u20ac76.49~text^original_cost_hint4^...","booking_type":"4","remark":"---","cost":"7.65" ... }

		// DB Booking Dates
		$booking_dates = wpbc_db_get_booking_dates(   $booking_id );      // [ {"booking_dates_id":"10236","booking_id":"4195","booking_date":"2023-11-27 18:00:01","approved":"0","type_id":null} ,...]

		// Parse dates
		$dates_ymd_arr = array_map( function ( $value ) {
															$sql_booking_date = $value->booking_date;
															$value_arr = explode( ' ', $sql_booking_date );
															return $value_arr[0];
											}, $booking_dates );         // ["2023-11-27","2023-11-28","2023-11-29"]
		// Remove duplicates
		$dates_ymd_arr = array_unique( $dates_ymd_arr );
		// Sort Dates
		sort( $dates_ymd_arr );                                                                                         // [ '2023-10-20', '2023-10-25' ]


		$params_arr = array();
		$params_arr['booking_id']    = $booking_id;
		$params_arr['resource_id']   = $resource_id;
		$params_arr['form_data']     = $booking_data->form;                                                                             // 'text^selected_short_dates_hint11^Sun...',
		$params_arr['dates_ymd_arr'] = $dates_ymd_arr;                                                                                  // [ '2023-10-20', '2023-10-25' ]
		$params_arr['times_his_arr'] = wpbc_get_times_his_arr__in_form_data( $params_arr['form_data'], $params_arr['resource_id'] );    // [ '16:00:01', '18:00:02' ]

				/**
				 * Another way  of get data
				 *		// 3. Get booking dates (sql)
				 *		$bookings_obj = array( $booking_data );
				 *		$booking_dates_obj = wpbc_ajx_get__booking_dates_obj__sql( $bookings_obj );
				 *
				 *		// 4. Include dates into bookings   (Wide and Short dates view)
				 *		$bookings_with_dates = wpbc_ajx_include_bookingdates_in_bookings( $bookings_obj, $booking_dates_obj );
				 *
				 *		// 5. Parse forms
				 *		$parsed_bookings = wpbc_ajx_parse_bookings( $bookings_with_dates, $resources_arr );  ??
				 *
				 *		wpbc_get_booking_different_params_arr( $booking_data->booking_id, $booking_data->form, $booking_resource_id__arr['resource_id'] );
				 *
				 *		$booking_data = wpbc_api_get_booking_by_id( $booking_id );
				 *
				 *		$params_arr['form_data']   = $bookings_with_dates[ $booking_id ]->booking_db->form;                                            // 'text^selected_short_dates_hint11^Sun...',
				 *
				 *		$params_arr['dates_ymd_arr'] = array_map( function ( $value ) {
				 *															$value_arr = explode( ' ', $value );
				 *															return $value_arr[0];
				 *														}, $bookings_with_dates[ $booking_id ]->dates );    // .->booking_db->dates = [ "2023-11-27 18:00:01", "2023-11-28 00:00:00", "2023-11-29 20:00:02" ]
				 *		// Remove duplicates
				 *		$params_arr['dates_ymd_arr'] = array_unique( $params_arr['dates_ymd_arr'] );
				 *
				 *		// Sort Dates
				 *		sort( $params_arr['dates_ymd_arr'] );                                        // [ '2023-10-20', '2023-10-25' ]
				 */

		// $booking_data_arr    = wpbc_get_parsed_booking_data_arr(     $params_arr['form_data'], $params_arr['resource_id'], array( 'get' => 'value' ) );                  // Get parsed booking form: = [ name = "John", secondname = "Smith", email = "john.smith@server.com", visitors = "2",... ]
		// $time_as_seconds_arr = wpbc_get_times_his_arr__in_form_data( $params_arr['form_data'], $params_arr['resource_id'], array( 'get' => 'time_as_seconds_arr' ) );	// [ 64800, 72000 ]

		$payment_cost = ( class_exists( 'wpdev_bk_biz_s' ) ) ? ( (float) $booking_data->cost ) : 0;
		$params_arr['payment_cost']    = $payment_cost;                 // $bookings_with_dates[ $booking_id ]->booking_db->cost;
		$params_arr['total_cost']      = $payment_cost;
		$params_arr['deposit_cost']    = $payment_cost;
		$params_arr['is_deposit']      = false;                         // Optional.
		$params_arr['booking_summary'] = '';                            // Optional.
		$params_arr['gateways_arr']    = '';                            // Optional.
		$params_arr['is_show_coupon_discount_text'] = false;            // Because we are at the 'Thank you' page,  it can be request,  and we do not show this coupon discount ??



		// =============================================================================================================
		// Get payment form(s)          and             Update COST      of the booking
		// =============================================================================================================
		$payment_params = array();

		// Usually we have this:  ( $str_dates__dd_mm_yyyy == $create_params['dates_only_sql_arr'] )  - but for ensure,  use saved dates,  e.g. $str_dates__dd_mm_yyyy
		$payment_params['booked_dates_times_arr'] = array(
															'dates_ymd_arr' => $params_arr['dates_ymd_arr'],            // [ '2023-10-20', '2023-10-25' ]
															'times_his_arr' => $params_arr['times_his_arr']             // ['16:00:01', '18:00:02']
														);
		$str_dates__dd_mm_yyyy = wpbc_convert_dates_arr__yyyy_mm_dd__to__dd_mm_yyyy( $params_arr['dates_ymd_arr'] );    // ['2023-10-20','2023-10-25'] => ['20.10.2023','25.10.2023']
		$payment_params['str_dates__dd_mm_yyyy'] = implode( ',', $str_dates__dd_mm_yyyy );                                  // REQUIRED --    '14.11.2023, 15.11.2023, 16.11.2023, 17.11.2023'
		$payment_params['booking_id']            = $params_arr['booking_id'];                                           // REQUIRED --    '2'
		$payment_params['resource_id']           = $params_arr['resource_id'];                                          // REQUIRED --    '2'  can be child resource (changed in wpbc_where_to_save() )

		// This field required only  for make cost  calculation based on PARENT booking resource. But here we already have the cost  from  DB, so  no need to use parent booking resource
		$payment_params['initial_resource_id']   = $params_arr['resource_id'];                                          // REQUIRED --    '2'  initial calendar - parent resource

		$payment_params['form_data']   = $params_arr['form_data'];              // we re-save it,  because here can be sync_guid and custom form new data from  wpbc_db__booking_save(..)    // REQUIRED --    'text^selected_short_timedates_hint4^06/11/2018 14:00...'
		$payment_params['times_array'] = array( explode( ':', $params_arr['times_his_arr'][0] ), explode( ':', $params_arr['times_his_arr'][1] ) );      // [  ["10","00","00"],  ["12","00","00"]  ]

		$payment_params['custom_form'] = wpbc_get__custom_booking_form_name( $params_arr['resource_id'], $params_arr['form_data'] );    //  => ''       '' | 'some_name'
		// <editor-fold     defaultstate="collapsed"                        desc="  --  Trick for legacy code  --  "  >
		$_POST['booking_form_type'] = $payment_params['custom_form'];       // Trick for legacy code for correct cost calculation,  relative to "Advanced cost". Required in biz_m.php file in  function advanced_cost_apply( $summ , $form , $bktype , $days_array , $is_get_description = false )
		// </editor-fold>

		// Additional  options
		$payment_params['is_edit_booking']      = 0;                                                //           => 0        0 | int - ID of the booking
		$payment_params['is_duplicate_booking'] = 0;                                                //           => 0        0 | 1
		$payment_params['is_from_admin_panel']  = false;                                            //           => false    true | false
		$payment_params['is_show_payment_form'] = 1;                                                //           => 1        0 | 1

		//FixIn: 9.9.0.35
		if ( ( ! empty( $_GET['is_show_payment_form'] ) ) && ( 'Off' === $_GET['is_show_payment_form'] ) ) {
			$payment_params['is_show_payment_form'] = 0;
		}

		$payment_params['costs_arr'] = array(
 											  'total_cost'   => $params_arr['payment_cost'],
											  'deposit_cost' => $params_arr['payment_cost']
										);
		if ( ( ! empty( $_GET['booking_pay'] ) ) && ( 1 == intval( $_GET['booking_pay'] ) ) ) {
			$payment_params['booking_payment_form_in_request_only'] = 1;
			    $params_arr['booking_payment_form_in_request_only'] = 1;
        }

		// Situation,  when we show payment form  for "child booking resources",  and need to  recalculate total  booking cost,  based on cost  of parent resource for "Cash  payment" - calc_cost_hint | calc_deposit_hint | calc_original_cost_hint ...
		if(
			   ( function_exists( 'wpbc_is_this_child_resource' ) )
			&& ( wpbc_is_this_child_resource( $payment_params['resource_id'] ) )
	    ){
		    $bk_parent_br_id = wpbc_get_parent_resource( $payment_params['resource_id'] );
			$payment_params['initial_resource_id'] = $bk_parent_br_id;
			//$payment_params['resource_id']         = $payment_params['resource_id'];
	    }

		// GET PAYMENT FORMS
		if ( function_exists( 'wpbc_maybe_get_payment_form' ) ) {

			$response__payment_form__arr = wpbc_maybe_get_payment_form( $payment_params );

			if ( ( ! empty( $response__payment_form__arr['gateways_output_arr'] ) ) && ( ! empty( $response__payment_form__arr['gateways_output_arr']['gateway_rows'] ) ) ) {
				$params_arr['gateway_rows'] = $response__payment_form__arr['gateways_output_arr']['gateway_rows'];
			}
			if ( ( ! empty( $response__payment_form__arr['gateways_output_arr'] ) ) && ( ! empty( $response__payment_form__arr['gateways_output_arr']['booking_summary'] ) ) ) {
				$params_arr['booking_summary'] = $response__payment_form__arr['gateways_output_arr']['booking_summary'];
			}

		} else {
			$response__payment_form__arr = $payment_params;
			$response__payment_form__arr['status'] = 'ok';
		}

		// =============================================================================================================


		$confirmation_arr = wpbc_booking_confirmation( $params_arr );

		$confirmation_arr['ty_is_redirect'] = 'message';                // We already  at  this page,  so  do not make redirect
		$confirmation_arr['ty_message']     = '';                       // Reset "Thank you" message,  because it is oin the Thank you | Payment request  page now.


		$json_arr = array();
		$json_arr['ajx_confirmation'] = $confirmation_arr;
		$json_arr['resource_id']      = $resource_id;

		$return_str =  '<div class="wpbc_container ">'
		                  . '<div id="booking_form' . $resource_id . '"></div>'
						  . '<script type="text/javascript">'
		                    . ' wpbc_show_thank_you_message_after_booking(' .wp_json_encode( $json_arr ) . '); '
		                    . ' jQuery(document).ready(function(){ setTimeout( function (){ wpbc_do_scroll( "#wpbc_scroll_point_' . intval( $resource_id ) . '", 10 ); }, 500 );  });'
		               .'</script>'
					 . '</div>';
	}


	$possible_output = ob_get_clean();

	$return_str .= $possible_output;

	return $return_str;	                // return '<pre>' . var_export($confirmation_arr,  true) . '</pre>';
}