<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4

// ---------------------------------------------------------------------------------------------------------------------
// ==  Get Booking Confirmation Data
// ---------------------------------------------------------------------------------------------------------------------

/**
 * Get confirmation parameters for booking confimration window
 *
 * @param array $params_arr = [
 *		                        'booking_id'  => 0,                                              // 16102023
 *								'resource_id' => 1,
 *								'form_data'   => '',                                             // 'text^selected_short_dates_hint11^Sun...',
 *								'dates_ymd_arr' => array(),                                      // [ '2023-10-20', '2023-10-25' ]
 *								'times_his_arr' => array(),                                      // [ '16:00:01', '18:00:02' ]
 *
 *								'total_cost'   => 0,                        Optional.
 *								'deposit_cost' => 0,                        Optional.
 *
 *								'booking_summary' => '',                        Optional.
 *								'gateway_rows'    => ''                         Optional.
 *                            ]
 *
 * @return array
 */
function wpbc_booking_confirmation( $params_arr ){

	$defaults = array(
						'booking_id'  => 0,                                              // 16102023
						'resource_id' => 1,
						'form_data'   => '',                                             // 'text^selected_short_dates_hint11^Sun...',
						'dates_ymd_arr' => array(),                                      // [ '2023-10-20', '2023-10-25' ]
						'times_his_arr' => array(),                                      // [ '16:00:01', '18:00:02' ]

						'total_cost'   => 0,                        // 143.991
						'deposit_cost' => 0,                        // 14.3991

						'booking_summary' => '',                    // '<p>Dear John, please make payment for your booking...'
						'gateway_rows'    => '',                    // [   [ gateway_id = "stripe_v3",  payment_params = [...] , is_for_deposit = 0,  output = '<div class="stripe_v3_div...' ], .... ]

						// Optional !
						'is_show_coupon_discount_text'  => true,
						'ty_is_redirect'                => esc_js( get_bk_option( 'booking_type_of_thank_you_message' ) )                  // 'message' | 'page'
				);
	$params_arr = wp_parse_args( $params_arr, $defaults );


	$str_dates__dd_mm_yyyy = wpbc_convert_dates_arr__yyyy_mm_dd__to__dd_mm_yyyy( $params_arr['dates_ymd_arr'] );        // ['2023-10-20','2023-10-25'] => ['20.10.2023','25.10.2023']
	$params_arr['str_dates__dd_mm_yyyy'] = implode( ',', $str_dates__dd_mm_yyyy );                                      // REQUIRED --    '14.11.2023, 15.11.2023, 16.11.2023, 17.11.2023'
	$params_arr['times_array']           = array(
													explode( ':', $params_arr['times_his_arr'][0] ),                    // ["10","00","00"]
													explode( ':', $params_arr['times_his_arr'][1] )                     // ["12","00","00"]
												);


	// -- Booking Hash -------------------------------------------------------------------------------------------------
	$booking_hash = '';
	$hash__arr = wpbc_hash__get_booking_hash__resource_id( $params_arr['booking_id'] );              // Get  booking hash
	if ( ! empty( $hash__arr ) ) {
		list( $booking_hash, $resource_id ) = $hash__arr;
	}


	$confirmation = array();
	// $confirmation['hash'] = $booking_hash;
	$confirmation['ty_is_redirect'] = $params_arr['ty_is_redirect'];


	if ( 0 ) {
		// TODO: Probably  all  this info,  we already had? (2023-10-06 18:10).    Optimize and refactor this function,  which  used in page-gateways.php file in wpbc_get_gateway_forms( $blank, $params )  during generating payment forms.
		// $replace_params = wpbc_get_booking_different_params_arr( $params_arr['booking_id'], $params_arr['form_data'], $params_arr['resource_id'] );
		// TODO: make this function  universal for all emails and for other purpose
		// $booking_params_like_in_email = wpbc__get_replace_shortcodes__email_new_admin( $params_arr['booking_id'] , $params_arr['resource_id'] , $params_arr['form_data'] );
		/**
		 * [    booking_id = {int} 3945
				id = {int} 3945
				dates = "Wed, Nov 1, 2023 10:00 - Wed, Nov 1, 2023 12:00"
	            ...
				is_new = "1"
				status = ""
				sort_date = "2023-11-01 10:00:01"
				creation_date = "2023-10-06 17:51:49"
				pay_status = "169660391431.09"
				pay_request = "0"
				booking_featured_image = {int} 0
		 * ]
		 */
	}


	// =================================================================================================================
	// == Confirmation data ==          / TY - Thank you /
	// =================================================================================================================

	$confirmation['ty_message'] = stripslashes( esc_js( apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_title_after_reservation' ) ) ) );   // 'Thank you for booking!'
	$confirmation['ty_message_booking_id'] = sprintf( __( 'Your booking id: %s', 'booking' ), "<strong>{$params_arr['booking_id']}</strong>" );

	// -- Customer details --
	$plain_form_data_show = wpbc_get__booking_form_data__show( $params_arr['form_data'], $params_arr['resource_id'], array( 'unknown_shortcodes_replace_by' => ' ... ' ) );

	$confirmation['ty_customer_details']  = '<div class="wpbc_ty__section_header">' . __( 'Personal information', 'booking' ) . '</div>';
	$confirmation['ty_customer_details'] .= $plain_form_data_show;

	// -- Booking details --
	$confirmation['ty_booking_details']  = '<div class="wpbc_ty__section_header">' . __( 'Booking details', 'booking' ) . '</div>';
	$confirmation['ty_booking_details'] .= '<h4  class="wpbc_ty__section_text_resource">' . wpbc_get_resource_title( $params_arr['resource_id'] ) . '</h4>';

	// -- Dates text --
	$confirmation['ty_booking_details'] .= '<div class="wpbc_ty__section_text_dates">'
                                            . wpbc_get_redable_dates( $params_arr['dates_ymd_arr']  )
	                                     . '</div>';
	// -- Times text --
	if (
		   ( wpbc_transform__24_hours_his__in__seconds( $params_arr['times_his_arr'][0] ) > 0 )
		&& ( wpbc_transform__24_hours_his__in__seconds( $params_arr['times_his_arr'][0] ) < 86400 )
	) {
		$confirmation['ty_booking_details'] .= '<div class="wpbc_ty__section_text_times">'
                                            . wpbc_get_redable_times( $params_arr['dates_ymd_arr']
																	, $params_arr['times_his_arr']
                                              )
	                                     . '</div>';
	}
	// -- Costs text --
	$confirmation['ty_booking_costs'] = '';
	$confirmation['payment_cost'] = '';

	if ( class_exists('wpdev_bk_biz_s')) {

		$is_deposit = ( $params_arr['total_cost'] != $params_arr['deposit_cost'] );

		if ($is_deposit){
			$confirmation['payment_cost'] = $params_arr['deposit_cost'];
		} else {
			$confirmation['payment_cost'] = $params_arr['total_cost'];
		}


		$confirmation['ty_booking_costs']  .= '<div class="wpbc_ty__section_text_costs">';

		// -------------------------------------------------------------------------------------------------------------
		// -- Coupon Code Discounts --
		// -------------------------------------------------------------------------------------------------------------
		$coupon_discount_value = false;
		if (
			    ( class_exists( 'wpdev_bk_biz_l' ) )
		     && ( $params_arr['is_show_coupon_discount_text'] )
		){

			// Get al  fields (again?)         ? -> $payment_params['structured_booking_data_arr']   <- Use this or get  the info again ?
			$structured_booking_data_arr = wpbc_get_parsed_booking_data_arr( $params_arr['form_data'], $params_arr['resource_id'] );
			// Find filled coupon code field - if not found then we get []  otherwise [ 'discount' => ['value' => 'test', ... ] ]
			$filtered_booking_data_arr = array_filter(    $structured_booking_data_arr
														, function ( $v  ) {
														   return ( ( ! empty( $v['value'] ) ) && ( 'coupon' == $v['type'] ) );
														} );

			if ( ! empty( $filtered_booking_data_arr ) ) {

				// Get total cost without discount.
				$total_cost_without_discount = wpbc_calc__booking_cost( array(
														  'resource_id'           => $params_arr['resource_id']           	    // '2'
														, 'str_dates__dd_mm_yyyy' => $params_arr['str_dates__dd_mm_yyyy']       // '14.11.2023, 15.11.2023, 16.11.2023, 17.11.2023'
														, 'times_array' 	      => $params_arr['times_array']                 // [  ["10","00","00"], ["12","00","00"]  ]
														, 'form_data'             => $params_arr['form_data']     		 	    // 'text^selected_short_timedates_hint4^06/11/2018 14:00...'
																, 'is_discount_calculate' => ! true                                 // Default  true
																, 'is_only_original_cost' => false                                  // Default  false
												) );
				$total_cost_without_discount = floatval( $total_cost_without_discount );                                            // from double  > float


				$coupon_text_info      = apply_bk_filter( 'wpdev_get_additional_description_about_coupons', ''
												, $params_arr['resource_id'], $params_arr['str_dates__dd_mm_yyyy'], $params_arr['times_his_arr'], $params_arr['form_data'] );
				$coupon_discount_value = apply_bk_filter( 'wpbc_get_coupon_code_discount_value', ''
												, $params_arr['resource_id'], $params_arr['str_dates__dd_mm_yyyy'], $params_arr['times_his_arr'], $params_arr['form_data'] );

				$coupon_text_info = str_replace( array( '[', ']' ), '', $coupon_text_info );

				$confirmation['ty_booking_costs'] .= '<div class="wpbc_ty__section_text_costs_header">';
				$confirmation['ty_booking_costs'] .= __( 'Total Cost', 'booking' ) . ': ';
				$confirmation['ty_booking_costs'] .=      wpbc_get_cost_with_currency_for_user(   $total_cost_without_discount
																								, $params_arr['resource_id']
				                                            );
				// $confirmation['ty_booking_costs'] .= '<br/>';
				// $confirmation['ty_booking_costs'] .= __( 'Discount', 'booking' ) . ': ';
				// $confirmation['ty_booking_costs'] .=      wpbc_get_cost_with_currency_for_user(   $coupon_discount_value
				//																				, $params_arr['resource_id']
				//                                            );
				$confirmation['ty_booking_costs'] .= '<br/>';
				$confirmation['ty_booking_costs'] .= $coupon_text_info;
				$confirmation['ty_booking_costs'] .= '<br/>';
				$confirmation['ty_booking_costs'] .= '</div>';
			}
		}

		// -- Deposit | Balance | Total --
		if ( $is_deposit ){
			$confirmation['ty_booking_costs'] .=    '<div class="wpbc_ty__section_text_costs_header">';
			$confirmation['ty_booking_costs'] .=    ( empty( $coupon_discount_value ) ) ? __( 'Total cost', 'booking' ) . ': ' : __( 'Subtotal cost', 'booking' ) . ': ';
			$confirmation['ty_booking_costs'] .=      wpbc_get_cost_with_currency_for_user(   $params_arr['total_cost']
																							, $params_arr['resource_id']
			                                            );
			$confirmation['ty_booking_costs'] .= '<br/>';
			$confirmation['ty_booking_costs'] .= __( 'Deposit Due', 'booking' ). ': ';
			$confirmation['ty_booking_costs'] .=      wpbc_get_cost_with_currency_for_user(   $params_arr['deposit_cost']
																							, $params_arr['resource_id']
			                                            );
			$confirmation['ty_booking_costs'] .= '<br/>';
			$confirmation['ty_booking_costs'] .= __( 'Balance Remaining', 'booking' ). ': ';
			$confirmation['ty_booking_costs'] .=      wpbc_get_cost_with_currency_for_user(   ($params_arr['total_cost'] - $params_arr['deposit_cost'] )
																							, $params_arr['resource_id']
			                                            );
			$confirmation['ty_booking_costs'] .= '<br/>';
			$confirmation['ty_booking_costs'] .=    '</div>';
		}


		$confirmation['ty_booking_costs'] .= '</div>';


		// ---------------------------------------------------------------------------------------------------------
		// "Update Note" in booking with  all  datails. Again ?
		// ---------------------------------------------------------------------------------------------------------
		$cost_booking_note = preg_replace( "@(&lt;|<)br\s*/?(&gt;|>)(\r\n)?@", "\n", $confirmation['ty_booking_costs'] );
		$cost_booking_note = strip_tags( $cost_booking_note );
		$cost_booking_note = str_replace( array( '  ', "\n " ), array( ' ', "\n" ), $cost_booking_note );

		if ( ! empty( $params_arr['gateway_rows'] ) ) {

			$is_add_timezone_offset = true;
			$booking_note = wpbc_date_localized( gmdate( 'Y-m-d H:i:s' ), '[Y-m-d H:i]', $is_add_timezone_offset ) . ' ';

			$booking_note .= ' ' . __( 'Payment section displayed', 'booking' );
			if ( empty( $cost_booking_note ) ) {
				foreach ( $params_arr['gateway_rows'] as $row_num => $gateway_row ) {
					$booking_note .= ' | ' . strip_tags( html_entity_decode( $gateway_row['header'] ) );
				}
			}
			$booking_note .= "\n";

			if ( false !== strpos( $cost_booking_note, "\n" ) ) { $booking_note .= '-----------------------------------------' . "\n"; }
			$booking_note .= $cost_booking_note;
			if ( false !== strpos( $cost_booking_note, "\n" ) ) { $booking_note .= "\n" . '-----------------------------------------' . "\n"; }

			make_bk_action( 'wpdev_make_update_of_remark', $params_arr['booking_id'], $booking_note, true );
		}
		// TODO:  2.   what  about additional  calendars payment forms ?. It has to be returned from wpbc_get_gateway_forms() with  ->  'payment_form_target': ' target="_blank" '


		// -------------------------------------------------------------------------------------------------------------
		// -- Payment gateways
		// -------------------------------------------------------------------------------------------------------------
		$confirmation['ty_payment_gateways'] = '';
		$confirmation['ty_payment_payment_description'] = '';
		if ( ! empty( $params_arr['booking_summary'] ) ) {
			$confirmation['ty_payment_payment_description'] = $params_arr['booking_summary'];
		}

		if (
			    (
				      ( 'On' !== get_bk_option( 'booking_payment_form_in_request_only' )  )
				  ||  (    ! empty( $params_arr['booking_payment_form_in_request_only'] ) )
			    )
			&& ( ! empty( $params_arr['gateway_rows'] ) )
	    ){

			$is_show_both_deposit_and_total = ( ( 'On' == get_bk_option( 'booking_show_deposit_and_total_payment' ) ) && ( $is_deposit ) );

			foreach ( $params_arr['gateway_rows'] as $row_num => $gateway_row ) {

				$ty_payment_gateways_before = '';
				$ty_payment_gateways_after  = '';

				// Cost Header
				$ty_payment_gateways_before .= '<div class="wpbc_ty__gateway ">';
				$ty_payment_gateways_before .=  '<h4 class="wpbc_ty__section_text_costs_header"  style="margin-left: auto;margin-right: 1.5em;">';
				$ty_payment_gateways_before .=   $params_arr['gateway_rows'][ $row_num ]['header'];
				$ty_payment_gateways_before .=  '</h4>';
				$ty_payment_gateways_before .= '</div>';

				// Gateways Buttons
				foreach ( $params_arr['gateway_rows'][ $row_num ]['gateways_arr'] as $payment_gateway ) {

					/**
					 *  $payment_gateway['payment_params']      =  [	booking_id = "3842"
																		id = "3842"
																		days_input_format = "10.12.2023"
																		days_only_sql = "2023-12-10"
																		...
																		visitorbookingediturl   = "http://beta/wpbc-my-booking/?booking_hash=65410858c2dcab4298ad7494d65b8ab0"
																	]
					 * $payment_gateway['gateway_id']          =   stripe_v3
					 */

					if ( ! in_array( $payment_gateway['gateway_id'], array( 'bank_transfer', 'pay_cash' ) ) ) {

						if (    ( $is_show_both_deposit_and_total )
							 && ( $params_arr['gateway_rows'][ $row_num ]['is_for_deposit'] )
						     && ( in_array( $payment_gateway['gateway_id'], array( 'stripe_v3' ) ) ) ) {

							// Skip 'Stripe' for showing total  cost, if activated both "Total | Deposit",  because we can show only 1 button

						} else {
							$ty_payment_gateways_before .= '<div class="wpbc_ty__gateway wpbc_col_auto_width">'
							                               . $payment_gateway['output']
							                               . '</div>';
						}
					} else {

						$ty_payment_gateways_after .=   '<div class="wpbc_ty__gateway">'
						                              .     $payment_gateway['output']
						                              . '</div>';
					}
				}

				$confirmation['ty_payment_gateways'] .= $ty_payment_gateways_before . $ty_payment_gateways_after;
			}
		}
	}



	// If showing payment form,  that  we do not make redirection  and show Message only
	if ( ! empty( $confirmation['ty_payment_gateways'] ) ) {
		$confirmation['ty_is_redirect'] = 'message';
	}

	if ( 'page' == $confirmation['ty_is_redirect'] ) {

		//FixIn: 9.9.0.3
		if ( ! empty( $params_arr['ty_url'] ) ) {

			$confirmation['ty_url'] = $params_arr['ty_url'];

		} else {

			$confirmation['ty_url'] = wpbc_make_link_absolute( apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_thank_you_page_URL' ) ) );  // '/thank-you'

			$confirmation['ty_url'] .= ( ( false === strpos( $confirmation['ty_url'], '?' ) ) ? '?' : '&' ) . 'booking_hash=' . $booking_hash;
		}

	} else {
		$confirmation['ty_url'] = '';
	}

	return $confirmation;
}