<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4

// ---------------------------------------------------------------------------------------------------------------------
// ==  Ajax Response on creation of new booking
// ---------------------------------------------------------------------------------------------------------------------

/**
 * Response to Ajax request,  about loading calendar data
 *
 * @return void
 */
function ajax_WPBC_AJX_BOOKING__CREATE() {

	/**
	 * Tip   / translation /
	 * Please note,  translation  was loaded on hook add_action( 'plugins_loaded', 'wpbc_load_translation', 1000 ); and use $_REQUEST['wpbc_ajx_locale'], so  do not worry  about it.
	 */

	// Security  ------------------------------------------------------------------------------------------------------ // in Ajax Post:   'nonce': _wpbc.get_secure_param( 'nonce' ),
	$action_name    = 'wpbc_calendar_load_ajx' . '_wpbcnonce';
	$nonce_post_key = 'nonce';
	$result_check   = check_ajax_referer( $action_name, $nonce_post_key );

	// Response AJAX parameters
	$ajx_data_arr           = array();
	$ajx_data_arr['status'] = 'ok';

	$admin_uri = ltrim( str_replace( get_site_url( null, '', 'admin' ), '', admin_url( 'admin.php?' ) ), '/' );                                         // 'wp-admin/admin.php?'

	// Local parameters
	$local_params                        = array();
	$local_params['is_from_admin_panel'] = ( false !== strpos( $_SERVER['HTTP_REFERER'], $admin_uri ) );                                                            // true | false
	$local_params['user_id']             = ( isset( $_REQUEST['wpbc_ajx_user_id'] ) ) ? intval( $_REQUEST['wpbc_ajx_user_id'] ) : wpbc_get_current_user_id();       // 1

	// Request parameters
	$user_request = new WPBC_AJX__REQUEST( array(                                                                       // Using this class here only  for escaping variables
													'db_option_name'          => 'booking__wpbc_booking_create__request_params',    // Not necessary,  because we not save request, only sanitize it
													'user_id'                 => $local_params['user_id'],                          // Not necessary,  because we not save request, only sanitize it
													'request_rules_structure' => array(
																					'resource_id' => array( 'validate' => 'd', 'default' => 1 ),                     // 'digit_or_csd'

																					'aggregate_resource_id_arr' => array( 'validate' => 'digit_or_csd', 'default' => '' ),

																					'dates_ddmmyy_csv' => array( 'validate' => 'csv_dates', 'default' => '' ),     //FixIn: 9.9.1.1
																					'formdata'         => array( 'validate' => 'strong', 'default' => '' ),
																					'booking_hash'     => array( 'validate' => 'strong', 'default' => '' ),
																					'custom_form'      => array( 'validate' => 'strong', 'default' => '' ),

																					'captcha_chalange'   => array( 'validate' => 'strong', 'default' => '' ),
																					'captcha_user_input' => array( 'validate' => 'strong', 'default' => '' ),

																					'is_emails_send' => array( 'validate' => 'd', 'default' => 1 ),
																					'active_locale'  => array( 'validate' => 'strong', 'default' => '' )
																				)
												));

	// Escape of request params   in Ajax Post.         We use prefix 'calendar_request_params', if Ajax sent - $_REQUEST['calendar_request_params']['resource_id'], ...
	$request_prefix = 'calendar_request_params';

//$_REQUEST['calendar_request_params']['dates_ddmmyy_csv'] .= "'%2b(select+'box'+from(select+sleep(2)+from+dual+where+1=1*)a)%2b'-02-21+00:00:00";

	$request_params = $user_request->get_sanitized__in_request__value_or_default( $request_prefix );                    // NOT Direct: 	$_REQUEST['calendar_request_params']['resource_id']

	$request_params['request_uri'] = $_SERVER['HTTP_REFERER'];      // Parameter needed for Error in booking saving and reloading calendar again  with  these actual  parameters.

	// <editor-fold     defaultstate="collapsed"                        desc=" :: ERROR :: <-  CAPTCHA "  >
	wpbc_captcha__in_ajx__check( $request_params, $local_params['is_from_admin_panel'], $_REQUEST[ $request_prefix ] );
	// </editor-fold>

	// <editor-fold     defaultstate="collapsed"                        desc=" :: ERROR :: <-  BOOKING_RESOURCE  ID "  >
	if ( $request_params['resource_id'] <= 0 ) {
		$ajx_data_arr['status']                          = 'error';
		$ajx_data_arr['status_error']                    = 'resource_id_incorrect';
		$ajx_data_arr['ajx_after_action_message']        = 'Wrong ID of booking resource: ' . ' [ request ID: ' . $_REQUEST['calendar_request_params']['resource_id'] . ' | parsed ID: ' . $request_params['resource_id'] . ' ]';
		$ajx_data_arr['ajx_after_action_message_status'] = 'error';
		wp_send_json( array( 'ajx_data'           => $ajx_data_arr,
		                     'ajx_search_params'  => $_REQUEST[ $request_prefix ],
		                     'ajx_cleaned_params' => $request_params,
		                     'resource_id'        => $request_params['resource_id']
		) );
	}
	// </editor-fold>


	$request_save_params = array(
									'resource_id'               => $request_params['resource_id'],
									'dates_ddmmyy_csv'          => $request_params['dates_ddmmyy_csv'],
									'form_data'                 => $request_params['formdata'],
									'aggregate_resource_id_arr' => $request_params['aggregate_resource_id_arr'],        // Optional  can  be ''

									'booking_hash'        => $request_params['booking_hash'],
									'custom_form'         => $request_params['custom_form'],

									'is_emails_send'       => $request_params['is_emails_send'],
									'is_show_payment_form' => 1,
									'user_id'              => $local_params['user_id'],
									'request_uri'          => $_SERVER['HTTP_REFERER']
							);
	$booking_save_arr = wpbc_booking_save( $request_save_params );

	// <editor-fold     defaultstate="collapsed"                        desc=" :: ERROR :: <-  BOOKING "  >
	if ( 'ok' !== $booking_save_arr['ajx_data']['status'] ) {

		wp_send_json( array( 'ajx_data'           => $booking_save_arr['ajx_data'],
		                     'ajx_search_params'  => $_REQUEST[ $request_prefix ],
		                     'ajx_cleaned_params' => $request_params,
		                     'resource_id'        => $request_params['resource_id']
					));
	}
	// </editor-fold>

	$ajx_data_arr     = $booking_save_arr['ajx_data'];



	// TODO:    If we have the calendar  with  specific capacity,  then maybe showing by  dots (the booked child booking resources) the slots and not the time slot !

	if ( empty( $ajx_data_arr['ajx_after_action_message_status'] ) ) {
		$ajx_data_arr['ajx_after_action_message_status'] = 'success';
	}
	if ( empty( $ajx_data_arr['ajx_after_action_message'] ) ) {
		$ajx_data_arr['ajx_after_action_message'] = '';
	}

	//	$ajx_data_arr['ajx_after_action_message'] .= __( 'Booking was created with ID: ' . $booking_save_arr[ 'booking_id' ] , 'booking' );
	//	$ajx_data_arr['ajx_after_action_message'] .= '<hr>Total time: <strong>' . $booking_save_arr['php_performance']['total'] . ' s. </strong>';
	//	$ajx_data_arr['ajx_after_action_message'] .= str_replace( array( ',', '{', '}' ), '<br>', json_encode( $booking_save_arr['php_performance'] ) );
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



	/*  if admin edit ?
                var my_message = '<?php echo html_entity_decode( esc_js( __('Updated successfully' ,'booking') ),ENT_QUOTES) ; ?>';
                wpbc_admin_show_message( my_message, 'success', 3000 );
				location.href='<?php echo wpbc_get_bookings_url() ;?>&view_mode=vm_listing&tab=actions&wh_booking_id=<?php echo  $is_edit_booking['booking_id'] ; ?>';
    */


	// -----------------------------------------------------------------------------------------------------------------
	// ==   Ajax   ===
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * Send JSON. It will make "wp_json_encode" - so pass only array, and This function call wp_die( '', '', array( 'response' => null, ) ) .
	 * Pass JS OBJ: response_data in "jQuery.post " function on success.
	 *
	 * Other End Ajax actions:
	 *                              $error_obj = new WP_Error( 'WPBC_CREATE', __( 'test error.' ), 'some/data' );  wp_send_json_error( $error_obj );
	 *                              wp_send_json_error(   array( 'message' => 'invalid-api-key' ) );
	 *                              wp_send_json_success( array( 'message' =>'Ok:)' ) );
	 */
	wp_send_json( array(
						'booking_id'            => $booking_save_arr['booking_id'],
						'resource_id'           => $request_params['resource_id'],
						'ajx_data'              => $ajx_data_arr,
						'ajx_confirmation'      => $booking_save_arr['confirmation'],

						// For debug purpose <- comment in live serv
						'php_process_times'     => $booking_save_arr['php_performance'],
						'booking_arr'           => $booking_save_arr ['booking_arr'],

						// Not needed ?
						// 'ajx_search_params'  => $_REQUEST[ $request_prefix ],
						// 'ajx_cleaned_params' => $request_params,

					) );
}

// Ajax Hooks
if (  is_admin() && ( defined( 'DOING_AJAX' ) ) && ( DOING_AJAX )  ) {
	add_action( 'wp_ajax_nopriv_' . 'WPBC_AJX_BOOKING__CREATE', 'ajax_' . 'WPBC_AJX_BOOKING__CREATE' );                     // Client         (not logged in)
	add_action( 'wp_ajax_'        . 'WPBC_AJX_BOOKING__CREATE', 'ajax_' . 'WPBC_AJX_BOOKING__CREATE' );                     // Logged In users  (or admin panel)
}


// ---------------------------------------------------------------------------------------------------------------------
// ==  Save Booking
// ---------------------------------------------------------------------------------------------------------------------

/**
 * Save Booking   -   ADD NEW   or   UPDATE exist    booking
 *
 * @param $request_params = [
 *								resource_id         = 2                                                                 REQUIRED        Default: 1
 *								dates_ddmmyy_csv    = '27.10.2023, 28.10.2023, 29.10.2023'                              REQUIRED
 *								form_data            = 'text^selected_short_dates_hint2^Fri, ...9, 2023~text^...'        REQUIRED
 *								booking_hash        = ''                                                                Optional        Default: ''
 *								custom_form         = ''                                                                Optional        Default: ''
 *								is_emails_send      = 1                                                                 Optional        Default: 1
 *                              is_show_payment_form => 1                                                               Optional        Default: 1
 *								user_id             = 1                                                                 Optional        Default: 0 or ID of logged-in user
 *								request_uri         = 'http://beta/resource-id2/', // Optional   Default: for front-end: $_SERVER['REQUEST_URI']   |   ajax: $_SERVER['HTTP_REFERER']
 *
 *								'sync_gid'           => 'ghjgjgjgh5f5f45f'                                              // Really Optional:  can be passed only during import .ics
 *                              'is_approve_booking' => 0                                                               // Really Optional:   0 | 1
 *                              'save_booking_even_if_unavailable' => 0                                                 // Really Optional:   0 | 1,  if 1 then force save booking even if dates unavailable.
 *                          ]
 *
 * @return []   ok:       [
 *
 *                        ]
 *              error:    [
 *                           'ajx_data': [ 'status':'error', 'status_error':'booking_can_not_save', 'ajx_after_action_message': 'Can not save booking', 'ajx_after_action_message_status': 'warning' ]
 *                        ]
 *
 *  Example:
 *
 *       wpbc_booking_save( array(
 *									'resource_id'       => 2,
 *									'dates_ddmmyy_csv'  => '04.10.2023, 05.10.2023, 06.10.2023',
 *									'form_data'         => 'text^cost_hint2^150.00฿~select-multiple^rangetime2[]^14:00 - 16:00~text^name2^John~text^secondname2^Smith~email^email2^john.smith@server.com~select-one^visitors2^2~select-one^children2^0~textarea^details2^test',
 *									'booking_hash'      => '',
 *									'custom_form'       => '',
 *									'is_emails_send'    => 1,
 *									'is_show_payment_form' => 1,
 *									'user_id'           => 1,
 *									'request_uri'       => 'http://beta/resource-id2/'
 *       ) );
 *
 */
function wpbc_booking_save( $request_params ){
																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_START( 'total', array() );
																														// </editor-fold>
	$ajx_data_arr           = array();
	$ajx_data_arr['status'] = 'ok';

	// -----------------------------------------------------------------------------------------------------------------
	// 1. Direct Clean Params
	// -----------------------------------------------------------------------------------------------------------------
	$validate_arr_rules = array(
								'resource_id'           => array( 'validate' => 'd',      'default' => 1 ),             // INT
								'dates_ddmmyy_csv'      => array( 'validate' => 'csv_dates', 'default' => '' ),         //FixIn: 9.9.1.1
								'form_data'             => array( 'validate' => 'strong', 'default' => '' ),
								'booking_hash'          => array( 'validate' => 'strong', 'default' => '' ),
								'custom_form'           => array( 'validate' => 'strong', 'default' => '' ),
								'is_emails_send'        => array( 'validate' => 'd',      'default' => 1 ),             // 0 | 1
								'is_show_payment_form'  => array( 'validate' => 'd',      'default' => 1 ),             // 0 | 1
								'user_id'               => array( 'validate' => 'd',      'default' => wpbc_get_current_user_id() ),        // INT
								'request_uri'           => array( 'validate' => 'strong', 'default'  => ( ( defined( 'DOING_AJAX' ) ) && ( DOING_AJAX ) ) ? $_SERVER['HTTP_REFERER'] : $_SERVER['REQUEST_URI'] ),     //  front-end: $_SERVER['REQUEST_URI'] | ajax: $_SERVER['HTTP_REFERER']
								// Really Optional:
								'aggregate_resource_id_arr'         => array( 'validate' => 'digit_or_csd', 'default' => '' ),
								'is_approve_booking'                => array( 'validate' => 'd',      'default' => 0 ),       // 0 | 1
								'save_booking_even_if_unavailable'  => array( 'validate' => 'd',      'default' => 0 ),       // 0 | 1
								'sync_gid'                          => array( 'validate' => 'strong', 'default' => '' ),
								'is_use_booking_recurrent_time'     => array( 'validate' => 'd',      'default' => intval( ( 'On' === get_bk_option( 'booking_recurrent_time' ) ) ) )
						);
	$re_cleaned_params = wpbc_sanitize_params_in_arr( $request_params, $validate_arr_rules );

	$admin_uri = ltrim( str_replace( get_site_url( null, '', 'admin' ), '', admin_url( 'admin.php?' ) ), '/' );         // wp-admin/admin.php?


	// -----------------------------------------------------------------------------------------------------------------
	// Local parameters
	// -----------------------------------------------------------------------------------------------------------------
	$local_params                        = array();
	$local_params['is_from_admin_panel'] = ( false !== strpos( $re_cleaned_params['request_uri'], $admin_uri ) );       // true | false
	$local_params['user_id']             = $re_cleaned_params['user_id'];                                               // 1
	$local_params['sync_gid']            = $re_cleaned_params['sync_gid'];                                              // ''
	$local_params['is_approve_booking']  = $re_cleaned_params['is_approve_booking'];                                    // 0 | 1
	$local_params['is_use_booking_recurrent_time'] = ( 1 === $re_cleaned_params['is_use_booking_recurrent_time'] );     // false | true

	// -----------------------------------------------------------------------------------------------------------------
	// Parse Local parameters for later use
	// -----------------------------------------------------------------------------------------------------------------
	/**
	 * Get parsed booking form:                 = [ name = "John", secondname = "Smith", email = "john.smith@server.com", visitors = "2",... ]
	 */
	$local_params['structured_booking_data_arr'] = wpbc_get_parsed_booking_data_arr( $re_cleaned_params["form_data"], $re_cleaned_params["resource_id"], array( 'get' => 'value' ) );
	$local_params['all_booking_data_arr']        = wpbc_get_parsed_booking_data_arr( $re_cleaned_params["form_data"], $re_cleaned_params["resource_id"] );
	//  Important! : [ 64800, 72000 ]
	$local_params['time_as_seconds_arr'] = wpbc_get_in_booking_form__time_to_book_as_seconds_arr( $local_params['structured_booking_data_arr'] );
				 // [ "18:00:00", "20:00:00" ]
				 $time_as_seconds_arr    = $local_params['time_as_seconds_arr'];
 				 $time_as_seconds_arr[0] = ( 0 != $time_as_seconds_arr[0] ) ? $time_as_seconds_arr[0] + 1 : $time_as_seconds_arr[0];                 // set check  in time with  ended 1 second
 				 $time_as_seconds_arr[1] = ( ( 24 * 60 * 60 ) != $time_as_seconds_arr[1] ) ? $time_as_seconds_arr[1] + 2 : $time_as_seconds_arr[1];  // set check out time with  ended 2 seconds
	$local_params['time_as_his_arr']     = array(
													wpbc_transform__seconds__in__24_hours_his( $time_as_seconds_arr[0] ),
													wpbc_transform__seconds__in__24_hours_his( $time_as_seconds_arr[1] )
											);
	// [ '2023-09-10', '2023-09-11' ]
	$local_params['dates_only_sql_arr'] = wpbc_convert_dates_str__dd_mm_yyyy__to__yyyy_mm_dd( $re_cleaned_params["dates_ddmmyy_csv"] );
	$local_params['dates_only_sql_arr'] = explode( ',', $local_params['dates_only_sql_arr'] );

	$local_params['is_show_payment_form'] = $re_cleaned_params["is_show_payment_form"];

	//FixIn: 9.9.0.35
	if ( $local_params['is_show_payment_form'] ) {
		$local_params['is_show_payment_form'] = ( false !== strpos( $re_cleaned_params['request_uri'], 'is_show_payment_form=Off' ) )
												? 0
												: $local_params['is_show_payment_form'];       // 1|0
	}

	// Get EDIT booking data
	$local_params['edit_resource_id']     = '';
	$local_params['skip_booking_id']      = '';
	$local_params['is_edit_booking']      = 0;
	$local_params['is_duplicate_booking'] = 0;
	$is_edit_booking                      = wpbc_get_data__if_edit_booking( $re_cleaned_params['booking_hash'], $re_cleaned_params['request_uri'] );
	if ( false !== $is_edit_booking ) {
		$local_params['edit_resource_id'] = $is_edit_booking['resource_id'];        // can be parent booking resource,  where we edit the booking
		$local_params['skip_booking_id']  = $is_edit_booking['booking_id'];         // booking ID
		$local_params['is_edit_booking']  = $is_edit_booking['booking_id'];         // booking ID
		if (
			   (  ! empty(               $local_params['structured_booking_data_arr']['wpbc_other_action'] ) )
			&& ( 'duplicate_booking' === $local_params['structured_booking_data_arr']['wpbc_other_action'] )
		){
			$local_params['is_duplicate_booking'] = 1;
		}
	}
	// It can be request resource ID     or   if we edit booking,  it can  be 'edit resource' - (e.g. child resource)
	$local_params['initial_resource_id'] = ( ! empty( $local_params['edit_resource_id'] ) ) ? $local_params['edit_resource_id'] : $re_cleaned_params['resource_id'];

	// 2
	$local_params['how_many_items_to_book'] = wpbc_get__how_many_items_to_book__in_booking_form( $local_params['structured_booking_data_arr'], $local_params['initial_resource_id'] );


	$local_params['aggregate_resource_id_arr'] = explode( ',', $re_cleaned_params['aggregate_resource_id_arr'] );
	$local_params['aggregate_resource_id_arr'] = array_filter( $local_params['aggregate_resource_id_arr'] );            // All entries of array equal to FALSE (0, '', '0' ) will be removed.
	$local_params['aggregate_resource_id_arr'] = array_unique( $local_params['aggregate_resource_id_arr'] );            // Erase duplicates

	// -----------------------------------------------------------------------------------------------------------------
	// Here GO
	// -----------------------------------------------------------------------------------------------------------------

	//  Force   -   resource saving parameters,  instead of wpbc__where_to_save_booking()
	if ( ! empty( $re_cleaned_params["save_booking_even_if_unavailable"] ) ) {

		$local_params['how_many_items_to_book'] = 1;

		$dates_keys_arr = array_values( $local_params['dates_only_sql_arr'] );                                          // [ '2023-09-23',  '2023-09-24' ]

		$resources_in_dates = array_fill_keys(  $dates_keys_arr  , array( $local_params['initial_resource_id'] )  );    // [ 2023-09-23 = [ 2 ],  2023-09-24 = [ 2 ] ]

		$where_to_save_booking = array();
		$where_to_save_booking['result']             = 'ok';
		$where_to_save_booking['resources_in_dates'] = $resources_in_dates;                                             // [ 2023-09-23 = [ 2, 10, 11 ],  2023-09-24 = [  2, 10, 11 ]
		$where_to_save_booking['time_to_book']       = $local_params['time_as_his_arr'];                                // [ "00:00:00", "24:00:00" ]
		$where_to_save_booking['main__resource_id']  = $local_params['initial_resource_id'];                            // here  edit or request (parent/single)  resource

	} else {
																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
		$php_performance = php_performance_START( 'wpbc__where_to_save_booking' , $php_performance );
																														// </editor-fold>

		/**
		 *  Get slots [] where we can save booking    =    [    'resources_in_dates' => [     2023-10-18 = [ 2, 12, 10, 11 ]
		 *                                                                                    2023-10-19 = [ 2, 12, 10, 11 ]
		 *                                                                                    2023-10-20 = [ 2, 12, 10, 11 ]
		 *                                                                              ],
		 *                                                       'time_to_book'      => [  "14:00:01"  ,  "12:00:01"  ],
		 *                                                       'result'            => 'ok'
		 *                                                       'main__resource_id' => 2
		 *                                                 ]
		 *                                            OR
		 *                                                 [ 'result' => 'error', 'message' => 'Booking can not be saved ...' ]
		 */
		$where_to_save_booking = wpbc__where_to_save_booking( array(
										'resource_id'                   => $local_params['initial_resource_id'],                // 2 //TODO: If edit booking. What to pass 'edit' or 'parent' resource ID?
										'skip_booking_id'               => $local_params['skip_booking_id'],                    // '',            |  125 if edit booking
										'dates_only_sql_arr'            => $local_params['dates_only_sql_arr'],                 // [ "2023-10-18", "2023-10-25", "2023-11-25" ]
										'time_as_seconds_arr'           => $local_params['time_as_seconds_arr'],                // [ 36000, 39600 ]
										'how_many_items_to_book'        => $local_params['how_many_items_to_book'],             // 1
										'request_uri'                   => $re_cleaned_params['request_uri'],                   // 'http://beta/resource-id2/'
										'is_use_booking_recurrent_time' => $local_params['is_use_booking_recurrent_time'],      // true | false
										'as_single_resource'            => false,                                                // false
										'aggregate_resource_id_arr'     => $local_params['aggregate_resource_id_arr']           // Optional  can  be ''
								    ));
		// <editor-fold     defaultstate="collapsed"                        desc=" :: ERROR :: <-  NO SLOTS TO SAVE "  >
		if ( 'error' == $where_to_save_booking['result'] ) {
			$ajx_data_arr['status']                          = 'error';
			$ajx_data_arr['status_error']                    = 'booking_can_not_save';
			$ajx_data_arr['ajx_after_action_message']        = $where_to_save_booking['message'];
			$ajx_data_arr['ajx_after_action_message_status'] = 'warning';
			return array( 'ajx_data' => $ajx_data_arr );
		}
		// </editor-fold>

																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
		$php_performance = php_performance_END( 'wpbc__where_to_save_booking' , $php_performance );
																														// </editor-fold>
	}


	// Get parameters, from  REQUEST
	$create_params                   = $local_params;
	$create_params['resource_id']    = ( ! empty( $local_params['edit_resource_id'] ) )
		? $local_params['edit_resource_id']                           // If we edit,  then  use original resource ???
		: $where_to_save_booking['main__resource_id'];                // Here is important TIP, resource can be where is free,  and not where we submit
	/**
	 * TODO: I think  it's resolved!    Just  test about this situation,  when  we edit the booking - and it's means that we have   $local_params['edit_resource_id']
	 *  but what, if  $where_to_save_booking        do not contain this $local_params['edit_resource_id'] as available resource.
	 *  or even  we have        $local_params['edit_resource_id'] = 2      and      $where_to_save_booking  contain resources like [ 1, 2, 3, 4 ]
	 *  we make booking for 3 slots
	 *  in this case,  main  resource will be 2
	 *  but then when  we loop  resources in wpbc_db__booking_save() we will  save child booking resources for dates like:   2, 3, 4  ( and it's wrong )
	 *       "(205, '2023-10-04 00:00:00', 0, NULL)"        <-  main resource  '2'   e.g.   $local_params['edit_resource_id'] = 2
	 *       "(205, '2023-10-04 00:00:00', 0, 2)"      ?    <-  child resource '2'   e.g.   [ .., 2, .. ] in $where_to_save_booking         WHICH IS WRONG
	 */
	$create_params['is_emails_send'] = $re_cleaned_params['is_emails_send'];
	$create_params['custom_form']    = $re_cleaned_params['custom_form'];

	make_bk_action( 'check_multiuser_params_for_client_side', $create_params['resource_id'] );                                 // Activate working with specific user in WP MU

																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_START( 'wpbc_db__booking_save' , $php_performance );
																														// </editor-fold>

	// -----------------------------------------------------------------------------------------------------------------
	// ==   CREATE_THE 'NEW_BOOKING'   ==
	// -----------------------------------------------------------------------------------------------------------------
	$create_booking_params = array(
									'resource_id'                   => $create_params['resource_id'],
									'custom_form'                   => $create_params['custom_form'],
									'all_booking_data_arr'          => $create_params['all_booking_data_arr'],
									'dates_only_sql_arr'            => $create_params['dates_only_sql_arr'],
									'time_as_his_arr'               => $create_params['time_as_his_arr'],
									'is_from_admin_panel'           => $create_params['is_from_admin_panel'],
									'is_edit_booking'               => $create_params['is_edit_booking'],
									'is_duplicate_booking'          => $create_params['is_duplicate_booking'],
									'is_approve_booking'            => $create_params['is_approve_booking'],
									'how_many_items_to_book'        => $create_params['how_many_items_to_book'],
									'is_use_booking_recurrent_time' => $create_params['is_use_booking_recurrent_time']     // true | false
								);
	if ( ! empty( $create_params['sync_gid'] ) ) { $create_booking_params['sync_gid'] = $create_params['sync_gid']; }

	$booking_new_arr = wpbc_db__booking_save( $create_booking_params, $where_to_save_booking );

	// <editor-fold     defaultstate="collapsed"                        desc=" :: ERROR :: <-  BOOKING CREATION "  >
	if ( 'ok' !== $booking_new_arr['status'] ) {
		$ajx_data_arr['status']                          = $booking_new_arr['status'];
		$ajx_data_arr['status_error']                    = 'booking_can_not_save';
		$ajx_data_arr['ajx_after_action_message']        = $booking_new_arr['message'];
		$ajx_data_arr['ajx_after_action_message_status'] = 'error';
		return array( 'ajx_data' => $ajx_data_arr );
	}
	// </editor-fold>

	//FixIn: 9.9.0.36
	if (
		   ( 0 !== $create_params['is_edit_booking'] )               // If edit booking
		&& ( 1 != $create_params['is_duplicate_booking'] )          // If not duplicate
	) {
			// Log the cost  info.
			$is_add_timezone_offset = true;
			$booking_note = wpbc_date_localized( gmdate( 'Y-m-d H:i:s' ), '[Y-m-d H:i]', $is_add_timezone_offset ) . ' ';
			$booking_note .= __( 'The booking has been edited', 'booking' ) . '. | Edit URL: ' . esc_url_raw( $re_cleaned_params['request_uri'] ) . '';
			make_bk_action( 'wpdev_make_update_of_remark',  $booking_new_arr['booking_id'], $booking_note, true );
	}
																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_END( 'wpbc_db__booking_save' , $php_performance );
																														// </editor-fold>

	// -----------------------------------------------------------------------------------------------------------------
	// Get payment form(s)          and             Update COST      of the booking
	// -----------------------------------------------------------------------------------------------------------------
	$payment_params = array();

	// Usually we have this:  ( $str_dates__dd_mm_yyyy == $create_params['dates_only_sql_arr'] )  - but for ensure,  use saved dates,  e.g. $str_dates__dd_mm_yyyy
	$payment_params['booked_dates_times_arr'] = array(
							'dates_ymd_arr' => array_keys( $where_to_save_booking['resources_in_dates'] ),              // [ 2023-10-20=>[],  2023-10-25=>[] ] -> [ "2023-10-20", "2023-10-25" ]
							'times_his_arr' => $where_to_save_booking['time_to_book']                                   // ['16:00:01', '18:00:02']
						);
	$str_dates__dd_mm_yyyy = wpbc_convert_dates_arr__yyyy_mm_dd__to__dd_mm_yyyy( $payment_params['booked_dates_times_arr']['dates_ymd_arr'] ); // ['2023-10-20','2023-10-25'] => ['20.10.2023','25.10.2023']
	$payment_params['str_dates__dd_mm_yyyy'] = implode( ',', $str_dates__dd_mm_yyyy );                                  // REQUIRED --    '14.11.2023, 15.11.2023, 16.11.2023, 17.11.2023'
	$payment_params['booking_id']            = $booking_new_arr['booking_id'];                                          // REQUIRED --    '2'
	$payment_params['resource_id']           = $create_params['resource_id'];                                           // REQUIRED --    '2'  can be child resource (changed in wpbc_where_to_save() )
	$payment_params['initial_resource_id']   = $local_params['initial_resource_id'];                                    // REQUIRED --    '2'  initial calendar - parent resource
	$payment_params['form_data']             = $booking_new_arr['form_data'];      // we re-save it,  because here can be sync_guid and custom form new data from  wpbc_db__booking_save(..)    // REQUIRED --    'text^selected_short_timedates_hint4^06/11/2018 14:00...'
	$payment_params['times_array']           = array(
		explode( ':', $where_to_save_booking['time_to_book'][0] ),         // ["10","00","00"]
		explode( ':', $where_to_save_booking['time_to_book'][1] )          // ["12","00","00"]
	);
	// Additional  options
	$payment_params['is_edit_booking']      = $create_params['is_edit_booking'];                //           => 0        0 | int - ID of the booking
	$payment_params['custom_form']          = $create_params['custom_form'];                    //           => ''       '' | 'some_name'
	$payment_params['is_duplicate_booking'] = $create_params['is_duplicate_booking'];           //           => 0        0 | 1
	$payment_params['is_from_admin_panel']  = $create_params['is_from_admin_panel'];            //           => false    true | false
	$payment_params['is_show_payment_form'] = $create_params['is_show_payment_form'];           //           => 1        0 | 1
	if ( $payment_params['is_from_admin_panel'] ) {
		// $payment_params['is_show_payment_form'] = 0;                     //FixIn: 9.9.0.21
	}
																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_START( 'wpbc_maybe_get_payment_form' , $php_performance );
																														// </editor-fold>

	// GET PAYMENT FORMS ===============================================================================================
	if ( function_exists( 'wpbc_maybe_get_payment_form' ) ) {

		$response__payment_form__arr = wpbc_maybe_get_payment_form( $payment_params );

		// <editor-fold     defaultstate="collapsed"                        desc=" :: ERROR :: <-  COSTS || PAYMENT_SYSTEMS "  >
		if (
			   ( ! empty( $response__payment_form__arr['status'] ) )
			&& (  'ok' != $response__payment_form__arr['status'] )
		) {
			$ajx_data_arr['status']                          = $response__payment_form__arr['status'];
			$ajx_data_arr['status_error']                    = 'booking_can_not_save';
			$ajx_data_arr['ajx_after_action_message']        = $response__payment_form__arr['message'];
			$ajx_data_arr['ajx_after_action_message_status'] = 'error';
			return array( 'ajx_data' => $ajx_data_arr );
		}
		// </editor-fold>

		if ( ! empty( $response__payment_form__arr['costs_arr']['form_data'] ) ) {
			$payment_params['form_data'] = $response__payment_form__arr['costs_arr']['form_data'];       // we re-save it,  because here can be [cost_correction] shortcode data
		}
		//TODO: Do  we really  need this in output $ajx_data_arr ???,  because it stored in $confirmation
		if ( ! empty( $response__payment_form__arr['gateways_output_arr'] ) ) {
			$ajx_data_arr['gateways_output_arr'] = $response__payment_form__arr['gateways_output_arr'];
		}
		if ( function_exists( 'wpbc_if_zero_cost__approve_booking_dates' ) ) {
			wpbc_if_zero_cost__approve_booking_dates( $payment_params['booking_id'] );
		}

	} else {
		$response__payment_form__arr = $payment_params;
		$response__payment_form__arr['status'] = 'ok';
	}


																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_END( 'wpbc_maybe_get_payment_form' , $php_performance );

	$php_performance = php_performance_START( 'emails_sending' , $php_performance );
																														// </editor-fold>

	// -----------------------------------------------------------------------------------------------------------------
	// ==   Emails   ===
	// -----------------------------------------------------------------------------------------------------------------
	if ( 1 == $re_cleaned_params['is_emails_send'] ) {

		$email_content = $payment_params['form_data'];

		// If we output any  text,  then  probably  it's errors or warnings,  we need to  catch  them
		ob_start();
		ob_clean();

		if ( 0 === $local_params['is_edit_booking'] ) {

			// New booking to Admin
			wpbc_send_email_new_admin( $payment_params['booking_id'], $payment_params['resource_id'], $email_content );

			// New pending to Visitor
            wpbc_send_email_new_visitor( $payment_params['booking_id'], $payment_params['resource_id'], $email_content ) ;

			$is_booking_approved = wpbc_is_booking_approved( $payment_params['booking_id'] );
			if ( $is_booking_approved ) {
				// New approved to Visitor / Admin
				wpbc_send_email_approved( $payment_params['booking_id'], 1 );
			}

			// Payment request from admin panel,  if needed
			if(
				   ( $payment_params['is_from_admin_panel'] )
				&& ( 'On' == get_bk_option( 'booking_payment_request_auto_send_in_bap' ) )
				&& ( function_exists( 'wpbc_send_email_payment_request' ) )
			){
				$payment_reason = '';
				$is_send = wpbc_send_email_payment_request( $payment_params['booking_id'], $payment_params['resource_id'], $email_content , $payment_reason );
			}

			do_action( 'wpbc_booking_approved' , $payment_params['booking_id'] , (int) $is_booking_approved );

		} else {

			// Edited booking   to Visitor / Admin
			if ( function_exists( 'wpbc_send_email_modified' ) ) {
				wpbc_send_email_modified( $payment_params['booking_id'], $payment_params['resource_id'], $email_content );
			}
		}

		$errors_on_email_sending_html = ob_get_contents();
	    ob_end_clean();

		if ( ! empty( $errors_on_email_sending_html ) ) {
			// Show these messages as warning after creation  of the booking
			$errors_on_email_sending_html = strip_tags( $errors_on_email_sending_html );
			$errors_on_email_sending_html = esc_attr( $errors_on_email_sending_html );
			$errors_on_email_sending_html = str_replace( "\\n", '', $errors_on_email_sending_html );

			$ajx_data_arr['ajx_after_action_message_status'] = 'warning';
			$ajx_data_arr['ajx_after_action_message']        = $errors_on_email_sending_html;
		}
	}

																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_END(   'emails_sending' , $php_performance );
																														// </editor-fold>

	// -----------------------------------------------------------------------------------------------------------------
	// ==   Track booking  -  New | Edit   ===
	// -----------------------------------------------------------------------------------------------------------------
    /**
     * Useful hook  for Google Ads Conversion tracking.    How to  use this hook?
	 *
	 * Add code similar  to this in your functions.php file in your theme,  or in some other php file:
	 *
		// Track adding new booking
		//
		// @param $params = array (
		//				'str_dates__dd_mm_yyyy'   => '08.10.2023,09.10.2023,10.10.2023,11.10.2023',
		//				'booking_id'              => 254,
		//				'resource_id'             => 11,                      // child or parent or single
		//				'initial_resource_id'     => 2,                       //          parent or single
		//				'form_data'               => 'text^selected_short_dates_hint11^Sun...',
		//				'times_array'             => array ( array ( '14', '00', '01' ), array( '12', '00', '02' ) ),
		//				'is_edit_booking'         => 0,
		//				'custom_form'             => '',
		//				'is_duplicate_booking'    => 0,
		//				'is_from_admin_panel'     => false,
		//				'is_show_payment_form'    => 1
		//						   )
		function my_booking_tracking( $params ){
			// Your code here
			?><!-- Google Code for Booking Conversion Page -->
			  <script type="text/javascript">
				 // Insert bellow your Google Conversion Code
			  </script><?php
		}
        add_action( 'wpbc_track_new_booking', 'my_booking_tracking' );
     *
     *
     *
     * Useful hook  booking edit tracking
	 *
	 * Add code similar  to this in your functions.php file in your theme,  or in some other php file:
	 *
		// Track edit existing booking
		//
		// @param $params = array (
		//				'str_dates__dd_mm_yyyy'   => '08.10.2023,09.10.2023,10.10.2023,11.10.2023',
		//				'booking_id'              => 254,
		//				'resource_id'             => 11,                      // child or parent or single
		//				'initial_resource_id'     => 2,                       //          parent or single
		//				'form_data'               => 'text^selected_short_dates_hint11^Sun...',
		//				'times_array'             => array ( array ( '14', '00', '01' ), array( '12', '00', '02' ) ),
		//				'is_edit_booking'         => 1,
		//				'custom_form'             => '',
		//				'is_duplicate_booking'    => 0,
		//				'is_from_admin_panel'     => false,
		//				'is_show_payment_form'    => 1
		//						   )
		function my_edit_booking_tracking( $params ){
			// Your code here
			?><!-- Google Code for Booking Conversion Page -->
			  <script type="text/javascript">
				 // Insert bellow your Google Conversion Code
			  </script><?php

		}
		add_action( 'wpbc_track_edit_booking', 'my_edit_booking_tracking' );
     */
	if ( 0 === $local_params['is_edit_booking'] ) {
		do_action( 'wpbc_track_new_booking', $payment_params );
	} else {
		do_action( 'wpbc_track_edit_booking', $payment_params );
	}


																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_START( 'confirmation' , $php_performance );
																														// </editor-fold>

	// <editor-fold     defaultstate="collapsed"                        desc="  == Confirmation data ==  "  >


	$confirmation_params_arr = array(
						'is_from_admin_panel' => $payment_params['is_from_admin_panel'],

						'booking_id'    => $booking_new_arr['booking_id'],                                              // 16102023
						'resource_id'   => $payment_params['resource_id'],                                              // 1
						'form_data'     => $payment_params['form_data'],                                                // 'text^selected_short_dates_hint11^Sun...',
						'dates_ymd_arr' => $payment_params['booked_dates_times_arr']['dates_ymd_arr'],                  // [ '2023-10-20', '2023-10-25' ]
						'times_his_arr' => $payment_params['booked_dates_times_arr']['times_his_arr'],                  // [ '16:00:01', '18:00:02' ]

						'total_cost'   => ( isset( $response__payment_form__arr['costs_arr'] ) && isset( $response__payment_form__arr['costs_arr']['total_cost'] ) )
											? $response__payment_form__arr['costs_arr']['total_cost']
											: 0,
						'deposit_cost' => ( isset( $response__payment_form__arr['costs_arr'] ) && isset( $response__payment_form__arr['costs_arr']['deposit_cost'] ) )
											? $response__payment_form__arr['costs_arr']['deposit_cost']
											: 0,
						'booking_summary' => (  ( ! empty( $response__payment_form__arr['gateways_output_arr'] ) ) && ( ! empty( $response__payment_form__arr['gateways_output_arr']['booking_summary'] ) )  )
											? $response__payment_form__arr['gateways_output_arr']['booking_summary']
											: '',
						'gateway_rows'    => (  ( ! empty( $response__payment_form__arr['gateways_output_arr'] ) ) && ( ! empty( $response__payment_form__arr['gateways_output_arr']['gateway_rows'] ) )   )
											? $response__payment_form__arr['gateways_output_arr']['gateway_rows']
											: ''
					);
	// It will  not show payment form  in Booking > Add booking page and if defined,  do not make redirect
	if ( $payment_params['is_from_admin_panel'] ) {

		$confirmation_params_arr['ty_is_redirect'] = 'message';                                                         // Do not make redirect,  if it's in admin panel!

		// But if we edit / duplicate the booking, then do redirection to Booking Listing page                          //FixIn: 9.9.0.3
		if (
			   (  0 !== $local_params['is_edit_booking'] )
			// && ( empty( $local_params['is_duplicate_booking'] ) )
		){
			$confirmation_params_arr['ty_is_redirect'] = 'page';
			$confirmation_params_arr['ty_url'] = wpbc_get_bookings_url() . '&view_mode=vm_listing&tab=actions&wh_booking_id=' . $confirmation_params_arr['booking_id'];
		}
	}
	$confirmation = wpbc_booking_confirmation( $confirmation_params_arr );

	// </editor-fold>

																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_END(   'confirmation' , $php_performance );
																														// </editor-fold>


	make_bk_action( 'finish_check_multiuser_params_for_client_side', $create_params['resource_id'] );                   // Deactivate working with  specific user in WP MU


																														// <editor-fold defaultstate="collapsed" desc=" = PERFORMANCE = "  >
	$php_performance = php_performance_END(   'total' , $php_performance );
	$php_performance['other_code'] = - 1 * array_reduce( $php_performance,
																			function ( $sum, $item ) {
																										$sum += $item;
																										return $sum;
																									}
														, - 2 * $php_performance['total'] );                            // PERFORMANCE OTHER - after TOTAL
																														// </editor-fold>


	return array(	'ajx_data'        => $ajx_data_arr,                         // [ 'status' => "ok", 'wpbc_payment_output' => "<p>Dear John<br..." ]
					'booking_id'      => $booking_new_arr['booking_id'],        // 254
					'booking_arr'     => $payment_params,
					'php_performance' => $php_performance,                      // [ ... ]
					'confirmation'    => $confirmation                          // [ ]
				);
}


// ---------------------------------------------------------------------------------------------------------------------
// New booking creation sub functions
// ---------------------------------------------------------------------------------------------------------------------

/**
 * Save booking in DB
 *
 * @param array $create_params = [
 *								  'resource_id'             => 10,
 *								  'dates_only_sql_arr'      => [ '2023-10-04', '2023-10-05', '2023-10-06'],
 *								  'time_as_his_arr'         => [ '14:00:01', '16:00:02' ],
 *								  'is_from_admin_panel'     => false,
 *								  'is_edit_booking'         => 0,
 *								  'is_duplicate_booking'    => 0,
 *                                'is_approve_booking'      => 0,
 *								  'how_many_items_to_book'  => 2,
 *								  'custom_form'             => '',
 *                          'is_use_booking_recurrent_time' => false,
 *								  'all_booking_data_arr'    => [   'rangetime'  => [
 *																				      'type' => 'select-multiple',
 *																				      'original_name' => 'rangetime2[]',        //NOTE: here RESOURCE ID (2) can  be from Parent resource, but resource_id can rely on child (10) resource
 *																				      'name' => 'rangetime',
 *																				      'value' => '14:00 - 16:00',
 *																				   ],
 *															        'name'      => [ 'type' => 'text', 'original_name' => 'name2', ... ],
 *																	...
 *														        ]
 *                              ]
 *
 * @param $where_to_save_booking    = [
 *                                          'result'             = 'ok',
 *                                          'main__resource_id'  = 2
 *                                          'resources_in_dates' = [      2023-10-18 = [ 2, 12, 10, 11 ]
 *                                                                        2023-10-19 = [ 2, 12, 10, 11 ]
 *                                                                        2023-10-20 = [ 2, 12, 10, 11 ]
 *                                                                  ],
 *                                          'time_to_book' =       [  "14:00:01"  ,  "16:00:02"  ]
 *                                     ]
 *
 * @return     [
 *                  'status'      => 'ok'        'ok' | 'error' | 'warning'
 *                  'booking_id'  => 100         int
 *                  'message'     => ''          'If error,  then  here can  be description  of error'
 *                  'form_data'   =>             If 'ok' form data can be different here, 'custom_form' parameter, so it can add 'wpbc_custom_booking_form' field for identification, what custom booking form was used,
 *             ]
 */
function wpbc_db__booking_save( $create_params, $where_to_save_booking ) {

	/**
	 * Tip:  $create_params['all_booking_data_arr']         - contain:       [ 'field_name' => [ 'type' = "checkbox", 'original_name' = "fixed_fee2[]", 'name' = "fixed_fee", 'value' = "true" ]   , ... ]
	 *       $create_params['structured_booking_data_arr']  - contain:       [ 'field_name' => 'field_value'   , ... ]
	 */

	// <editor-fold     defaultstate="collapsed"                        desc=" :: ERROR :: <-  'Wrong resource ID "  >
	if ( empty( $create_params['resource_id'] ) ) {
		return array( 'status' => 'error', 'message' => 'Wrong ID of booking resource: ' . $create_params['resource_id'] );
	}
	// </editor-fold>


	$defaults = array(
						'is_use_booking_recurrent_time' => ( 'On' === get_bk_option( 'booking_recurrent_time' ) )
					);
	$create_params   = wp_parse_args( $create_params, $defaults );


	$sql_field_arr = array();



	// Is it was used custom booking form ?
	if ( ! empty( $create_params['custom_form'] ) ) {
		$create_params['all_booking_data_arr']['wpbc_custom_booking_form'] = array(
																					'type'          => 'text',
																					'original_name' => 'wpbc_custom_booking_form' . $create_params['resource_id'],
																					'name'          => 'wpbc_custom_booking_form',
																					'value'         => $create_params['custom_form']
																				);
	}

	if ( ! empty( $create_params['sync_gid'] ) ) {
		/**
		 * Such  fields are comming from '../wp-content/plugins/booking/core/sync/wpbc-gcal-class.php'  in function run()
		*/

		// Escape any XSS injection from  values in booking form
		list( $create_params['sync_gid'] ) = wpbc_escape_any_xss_in_arr( array( $create_params['sync_gid'] ) );

		$sql_field_arr[] = array( 'name' => 'sync_gid',     'type' => '%s',     'value' => $create_params['sync_gid'] );
	}

	// Escape any XSS injection from  values in booking form
	$create_params['all_booking_data_arr'] = wpbc_escape_any_xss_in_arr( $create_params['all_booking_data_arr'] );


	//Set LAST check out day as AVAILABLE  - remove it
	if (
  		   ( 'On' === get_bk_option( 'booking_last_checkout_day_available' ) )
		&& ( ! empty( $create_params['dates_only_sql_arr'] ) )
		&& ( count( $create_params['dates_only_sql_arr'] ) > 1 )
	) {
		unset( $create_params['dates_only_sql_arr'][ ( count( $create_params['dates_only_sql_arr'] ) - 1 ) ] );                    // Remove LAST selected day in calendar //FixIn: 6.2.3.6
		// Delete last  item    //FixIn: 9.9.0.19
		$resources_in_dates_last_key = key( array_slice( $where_to_save_booking['resources_in_dates'], - 1, 1, true ) );
		unset( $where_to_save_booking['resources_in_dates'][ $resources_in_dates_last_key ] );
	}

	// :: ERROR ::
	if ( empty( $create_params['dates_only_sql_arr'] )  ) {                                                             // No dates :?
		return array(  'status' => 'error', 'message' => 'Sent request with no dates.' );
	}

	// <editor-fold     defaultstate="collapsed"                        desc=" :: ERROR :: <-  CHECK_IN_DATE_OLDER_THAN_CHECK_OUT "  >
	if ( count( $create_params['dates_only_sql_arr'] ) == 1 ) {															// Is it single selected date ?

		// Is 'check in' date/time older than 'check out' date/time when SINGLE day for booking?  Then show error.

		/**
		 *  If we are having "change over" days activated and selected only 1 day in calendar,
		 *  then we can have error: "Warning! Number of check in != check out times.", because "check in" day older than "check out" date.
		 */

		$is_apply__check_in_out__10s = false;
		$datestamp_check_in  = wpbc_convert__sql_date__to_seconds( $create_params['dates_only_sql_arr'][0] . ' ' . $create_params['time_as_his_arr'][0], $is_apply__check_in_out__10s );
		$datestamp_check_out = wpbc_convert__sql_date__to_seconds( $create_params['dates_only_sql_arr'][0] . ' ' . $create_params['time_as_his_arr'][1], $is_apply__check_in_out__10s );


		if ( $datestamp_check_in > $datestamp_check_out ) {
			$error_message = sprintf( 'Error! Your check  in date %s older than check out date %s. <br>Try to select more dates or use different time.'
												, '<strong>' . wpbc_convert__seconds__to_sql_date( $datestamp_check_in, $is_apply__check_in_out__10s ) . '</strong>'
												, '<strong>' . wpbc_convert__seconds__to_sql_date( $datestamp_check_out, $is_apply__check_in_out__10s ) . '</strong>'
												);
			$error_message .= '<br>' . '<strong>Settings that can be reason of the issue:</strong> ';
			if( 'On' === get_bk_option( 'booking_last_checkout_day_available' )){
				$error_message .= '<br>' . 'You have enabled <strong>"Set check out date as available"</strong> option at Booking > Settings General page in "Calendar" section. ';
			}
			if ( 'On' === get_bk_option( 'booking_range_selection_time_is_active' ) ){
				$error_message .= '<br>' . 'You have enabled <strong>"Use changeover days"</strong> option at Booking > Settings General page in "Calendar" section with check-in/out times: ' . get_bk_option( 'booking_range_selection_start_time' ) . '/' . get_bk_option( 'booking_range_selection_end_time' );
			}
			if ( $create_params['is_use_booking_recurrent_time'] ) {
				$error_message .= '<br>' . 'You have enabled <strong>"Use time selections as recurrent time slots"</strong> option at Booking > Settings General page in "Calendar" section. ';
			}
			return array(  'status' => 'error', 'message' => $error_message );
		}
	}
	// </editor-fold>

	// Compose form data for DB. Can be different resource:  'select-multiple^rangetime10[]^14..' <-> 'select-multiple^rangetime2[]^14..' -> 'rangetime10' - child res. Previously 'rangetime2' - parent
	$form_data = wpbc_encode_booking_data_to_string( $create_params['all_booking_data_arr'], $create_params['resource_id'] );


	// -----------------------------------------------------------------------------------------------------------------
	// APPROVED / PENDING
	// -----------------------------------------------------------------------------------------------------------------
	// Is approve booking
	$auto_approve_new_bookings_is_active = trim( get_bk_option( 'booking_auto_approve_new_bookings_is_active' ) );
	$is_approved_dates = ( $auto_approve_new_bookings_is_active == 'On' ) ? 1 : intval( $create_params['is_approve_booking'] );

	// Auto approve only for specific booking resources
	/**
	 * How to use "Auto approve bookings only for specific booking resources" ?
	 * Add code similar  to this in your functions.php file in your theme,  or in some other php file:
	 *
			function my_wpbc_get_booking_resources_arr_to_auto_approve( $resources_to_approve ) {
				$resources_to_approve = array( 9, 12, 33 );		// Array  of booking resources ID,  which  you need to auto  approve
				return $resources_to_approve;
			}
	 		add_filter( 'wpbc_get_booking_resources_arr_to_auto_approve', 'my_wpbc_get_booking_resources_arr_to_auto_approve' );
	 */
	$booking_resources_to_approve = array();
	$booking_resources_to_approve = apply_filters( 'wpbc_get_booking_resources_arr_to_auto_approve', $booking_resources_to_approve );       //FixIn: 8.5.2.27
	if ( in_array( $create_params['resource_id'], $booking_resources_to_approve ) ) {
		$is_approved_dates = 1;
	}

	if (
			( $create_params['is_from_admin_panel'] )                                                                   // true | false
	     && ( get_bk_option( 'booking_auto_approve_bookings_if_added_in_admin_panel' ) == 'On' )
	){                                                                                                                  //FixIn: 8.1.3.27
		$is_approved_dates = 1;
	}

	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Save Booking  ==  "  >
	// -----------------------------------------------------------------------------------------------------------------
	// Save Booking
	// -----------------------------------------------------------------------------------------------------------------
	global $wpdb;

	$sql_field_arr[] = array( 'name' => 'form',              'type' => '%s',        'value' => $form_data );
	$sql_field_arr[] = array( 'name' => 'booking_type',      'type' => '%d',        'value' => $create_params['resource_id'] );
	$sql_field_arr[] = array( 'name' => 'modification_date', 'type' => '%s',        'value' =>  gmdate( 'Y-m-d H:i:s' ) );
	$sql_field_arr[] = array( 'name' => 'sort_date',         'type' => '%s',        'value' => $create_params['dates_only_sql_arr'][0] . ' ' . $create_params['time_as_his_arr'][0] );
	$sql_field_arr[] = array( 'name' => 'hash',              'type' => 'MD5(%s)',   'value' => time() . '_' . rand( 1000, 1000000 ) );


	if (
		   ( 0 == $create_params['is_edit_booking'] )               // If not edit,  then INSERT
		|| ( 1 == $create_params['is_duplicate_booking'] )          // If duplicate, then INSERT
	){
		// Saved only  for new booking creation.
		$sql_field_arr[] = array( 'name' => 'creation_date',     'type' => '%s',        'value' =>  gmdate( 'Y-m-d H:i:s' ) );

		$sql_prepare_arr = array();
		$sql_prepare_arr['name']  = array_map( function ( $value ) { return $value['name']; },  $sql_field_arr );
		$sql_prepare_arr['type']  = array_map( function ( $value ) { return $value['type']; },  $sql_field_arr );
		$sql_prepare_arr['value'] = array_map( function ( $value ) { return $value['value']; }, $sql_field_arr );

		$sql_prepare_arr['name'] = implode( ', ', $sql_prepare_arr['name'] );
		$sql_prepare_arr['type'] = implode( ', ', $sql_prepare_arr['type'] );

		$sql = $wpdb->prepare(  "INSERT INTO {$wpdb->prefix}booking "
										. "			  ( {$sql_prepare_arr['name']} )"
										. "	  VALUES  ( {$sql_prepare_arr['type']} )"
										, $sql_prepare_arr['value']
									);

		if ( false === $wpdb->query( $sql ) ) {
			return array( 'status'  => 'error','message' => 'Error. INSERT New Data in DB.' . '  FILE:' . __FILE__ . ' LINE:' . __LINE__ . ' SQL:' . $sql );
		}
		// Get ID of booking
		$booking_id = (int) $wpdb->insert_id;

	} else {                                                    // Edit - UPDATE

		$booking_id = (int) $create_params['is_edit_booking'];

		$sql_prepare_arr = array();
		$sql_prepare_arr['set']   = array_map( function ( $value ) { return $value['name'] . '=' .  $value['type']; },  $sql_field_arr );
		$sql_prepare_arr['value'] = array_map( function ( $value ) { return $value['value']; }, $sql_field_arr );

		$sql_prepare_arr['set'] = implode( ', ', $sql_prepare_arr['set'] );

		$sql = $wpdb->prepare(  "UPDATE {$wpdb->prefix}booking SET "
		                        . " {$sql_prepare_arr['set']} "
		                        . " WHERE booking_id={$booking_id};"
								, $sql_prepare_arr['value']
							);
        if ( false === $wpdb->query( $sql  ) ){
			return array( 'status'  => 'error','message' => 'Error. UPDATE Exist Data in DB.' . '  FILE:' . __FILE__ . ' LINE:' . __LINE__ . ' SQL:' . $sql );
        }

		// Check if dates previously was approved.
		$slct_sql = "SELECT approved FROM {$wpdb->prefix}bookingdates WHERE booking_id IN ({$booking_id}) LIMIT 0,1";
		$slct_sql_results = $wpdb->get_results( $slct_sql );
		$is_approved_dates = ( count( $slct_sql_results ) > 0 ) ? $slct_sql_results[0]->approved : $is_approved_dates;


        $delete_sql = "DELETE FROM {$wpdb->prefix}bookingdates WHERE booking_id IN ({$booking_id})";
        if ( false === $wpdb->query( $delete_sql  ) ){
			return array( 'status'  => 'error','message' => 'Error. DELETE Old Dates in DB.' . '  FILE:' . __FILE__ . ' LINE:' . __LINE__ . ' SQL:' . $delete_sql );
        }
	}
	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Compose    D A T E S    for DB  ==  "  >
	// -----------------------------------------------------------------------------------------------------------------
	// Compose    D A T E S    for DB
	// -----------------------------------------------------------------------------------------------------------------

	if ( class_exists( 'wpdev_bk_biz_l' ) ) {
		$field_names_arr = array( 'booking_id', 'booking_date', 'approved', 'type_id' );
	} else {
		$field_names_arr = array( 'booking_id', 'booking_date', 'approved' );
	}
	$field_names = implode( ', ', $field_names_arr );

	$dates_sql = "INSERT INTO {$wpdb->prefix}bookingdates ( {$field_names} ) VALUES ";
	$insert_dates_arr = array();

	$how_many_items_to_book = $create_params['how_many_items_to_book'];
	// $i - INDEX of child booking resource  to  book (if  $how_many_items_to_book=1,  then  index always = 0 ) in  [ 'resources_in_dates' = [ '2023-10-18' = [ 9, 12, 10, 11 ], ... ]...]  - e.g. here = 9
	for( $i = 0; $i < $how_many_items_to_book; $i++) {

		$date_number = 0;
		foreach ( $where_to_save_booking['resources_in_dates'] as $only_date_sql => $resources_in_date_arr ) {

			$date_resource_id = $resources_in_date_arr[ $i ];

			// $create_params['resource_id']    <-  Main resource (saved in wp_booking )
			// $only_date_sql                   <- '2023-09-12'
			// $date_resource_id                <- Child resource (need to  save in wp_bookingdates)     OR      if ( $create_params['resource_id'] == $date_resource_id )   then    NULL

			// ---------------------------------------------------------------------------------------------------------
			// Is full day  booking:
			if (
					( '00:00' === substr( $where_to_save_booking['time_to_book'][0], 0, 5 ) )
				&&  (
						( '24:00' === substr( $where_to_save_booking['time_to_book'][1], 0, 5 ) )
					 || ( '00:00' === substr( $where_to_save_booking['time_to_book'][1], 0, 5 ) )
					)
			){
				// Full days *******************************************************************************************
				$full_date_sql = $only_date_sql . ' 00:00:00';

				$insert_dates_arr[] = wpbc_prepare_date_row( $booking_id, $full_date_sql, $is_approved_dates, $date_resource_id, $create_params['resource_id'] );

			} else { // Times

				if (
					   ( $create_params['is_use_booking_recurrent_time'] )                                              // Activated option  to  book times as 'time-slots'   OR
				    || ( 1 === count( $where_to_save_booking['resources_in_dates'] ) )                                  // Selected only 1 date,  so  use time as time-slot
				) {
					// Time slots in each day **************************************************************************

					// Start Time
					$full_date_sql = $only_date_sql . ' ' . $where_to_save_booking['time_to_book'][0];
					$insert_dates_arr[] = wpbc_prepare_date_row( $booking_id, $full_date_sql, $is_approved_dates, $date_resource_id, $create_params['resource_id'] );

					// End Time
					$full_date_sql = $only_date_sql . ' ' . $where_to_save_booking['time_to_book'][1];
					$insert_dates_arr[] = wpbc_prepare_date_row( $booking_id, $full_date_sql, $is_approved_dates, $date_resource_id, $create_params['resource_id'] );

				} else {
					// Check in/out ************************************************************************************

					if ( 0 == $date_number ) {                                                                          // Is check in ?

						$full_date_sql = $only_date_sql . ' ' . $where_to_save_booking['time_to_book'][0];

					} else  if ( ( count( $where_to_save_booking['resources_in_dates'] ) - 1 ) == $date_number ) {      // Is check out ?

						$full_date_sql = $only_date_sql . ' ' . $where_to_save_booking['time_to_book'][1];

					} else {                                                                                            // Middle date - Full Date
						$full_date_sql = $only_date_sql . ' 00:00:00';
					}

					$insert_dates_arr[] = wpbc_prepare_date_row( $booking_id, $full_date_sql, $is_approved_dates, $date_resource_id, $create_params['resource_id'] );

				}
			}
			// ---------------------------------------------------------------------------------------------------------
			$date_number++;
		}
	}

	$dates_sql .= implode( ', ', $insert_dates_arr );

	if ( false === $wpdb->query( $dates_sql ) ) {
		return array( 'status'  => 'error','message' => 'Error. INSERT "D A T E S" in DB.' . '  FILE:' . __FILE__ . ' LINE:' . __LINE__ . ' SQL:' . $dates_sql );
	}

	// -----------------------------------------------------------------------------------------------------------------
	// End    D A T E S
	// -----------------------------------------------------------------------------------------------------------------
	// </editor-fold>


	return array(
					  'status' => 'ok'
					, 'booking_id' => $booking_id
					, 'form_data'  => $form_data
					, 'message'    => ''
			);
}


		/**
		 * Get SQL ROWs of VALUES for INSERT to DB
		 *
		 * @param int $booking_id               101                     ID of the booking
		 * @param string $full_date_sql         '2023-09-12 10:00:01'   SQL date
		 * @param int $is_approved_dates        1                       1 - approved, 0 - pending
		 * @param int $date_resource_id         ( >= biz_l ):   4       ID of child booking resource (if we save ONE booking in SEVERAL booking resources)      field 'type_id' in wp_bookingdates
		 * @param int $main_resource_id         ( >= biz_l ):   1       ID of main (parent booking resource)
		 *
		 * @return string - SQL  for dates VALUES to  insert
		 */
		function wpbc_prepare_date_row( $booking_id, $full_date_sql, $is_approved_dates, $date_resource_id, $main_resource_id ) {

			global $wpdb;

			if ( class_exists( 'wpdev_bk_biz_l' ) ) {
				$insert_dates_arr = ( $main_resource_id != $date_resource_id )
									? $wpdb->prepare( "(%d, %s, %d, %d)",   $booking_id, $full_date_sql, $is_approved_dates, $date_resource_id )
									: $wpdb->prepare( "(%d, %s, %d, NULL)", $booking_id, $full_date_sql, $is_approved_dates );
			} else {
				$insert_dates_arr =   $wpdb->prepare( "(%d, %s, %d)",       $booking_id, $full_date_sql, $is_approved_dates );
			}

			return $insert_dates_arr;
		}


// ---------------------------------------------------------------------------------------------------------------------
// Support
// ---------------------------------------------------------------------------------------------------------------------

	/**
	 * Get booking_id and resource_id if we are editing the booking,  by  booking hash
	 *
	 * @param $booking_hash
	 * @param $server_request_url
	 *
	 * @return array | false            false if not edit       Otherwise       [ 'booking_id': 100,  'resource_id': 3 ]
	 */
	function wpbc_get_data__if_edit_booking( $booking_hash, $server_request_url ) {

		$is_edit_booking = false;
		if ( ! empty( $booking_hash ) ) {

			$my_booking_id_type = wpbc_hash__get_booking_id__resource_id( $booking_hash );
			if ( $my_booking_id_type !== false ) {

				$is_edit_booking                = array();
				$is_edit_booking['booking_id']  = intval( $my_booking_id_type[0] );
				$is_edit_booking['resource_id'] = intval( $my_booking_id_type[1] );

				//TODO: test it. Check situation when  we have editing "child booking resource",  so  need to  re-update calendar and form  to have it for parent resource.        //FixIn: 6.1.1.9
				if ( strpos( $server_request_url, 'resource_no_update' ) === false ) {                                  //FixIn: 9.4.2.3

					if ( ( function_exists( 'wpbc_is_this_child_resource' ) ) && ( wpbc_is_this_child_resource( $is_edit_booking['resource_id'] ) ) ) {
						$bk_parent_br_id = wpbc_get_parent_resource( $is_edit_booking['resource_id'] );

						$is_edit_booking['resource_id'] = intval( $bk_parent_br_id );
					}
				}
			}
		}
		return $is_edit_booking;
	}


	/**
	 * Get how many items to book.      Usually  it's from [select visitors "1" ... ] field.
	 *
	 * @param booking_form_data__arr =     [
	 *                                                     selected_short_dates_hint = "September 27, 2023 - September 28, 2023"
	 *                                                     days_number_hint = "2"
	 *                                                     rangetime = "16:00 - 18:00"
	 *                                                     starttime = "21:00"
	 *                                                     durationtime = "00:30"
	 *                                                     name = "John"
	 *                                                     secondname = "Smith"
	 *                                                     email = "john.smith@server.com"
	 *                                                     visitors = "1"
	 *                                                     children = "0"
	 *                                                     details = ""
     *                                              ]
	 *
	 * @return int
	 */
	function wpbc_get__how_many_items_to_book__in_booking_form( $booking_form_data__arr, $resource_id ){

		$how_many_items_to_book = 1;

		//TODO: Check about some URL parameter: '&resource_no_update' to book parent resource as single resource!
		if (
               ( class_exists( 'wpdev_bk_biz_l' ) )
			&& ( 0 !== wpbc_get_child_resources_number( $resource_id ) )                                        // Here several  child booking resources
		) {
			$booking_capacity_field = wpbc_get__booking_capacity_field__name();
			if (
				   ( ! empty( $booking_capacity_field ) )
				&& ( isset( $booking_form_data__arr[ $booking_capacity_field ] ) )
			){
				$how_many_items_to_book = intval( $booking_form_data__arr[ $booking_capacity_field ] );         // Get value of how many  booking resources to  book
				$how_many_items_to_book = ( $how_many_items_to_book > 0 ) ? $how_many_items_to_book : 1;
			}
		}

		return $how_many_items_to_book;
	}


	/**
	 * Get capacity field -- 'pure name'
	 *
	 * @return string      - field name,  or empty  string - '' if not defined
	 *
	 *  In DB,  we are saved field name type and possible name of custom  booking form in format: 'custom_form_name^field_type^capacity_field_name' -> 'minimal^select^adults'
	 *                                                                     or  for standard form: 'select^visitors'
	 *  and here we get  only  field name:    'visitors'
	 */
	function wpbc_get__booking_capacity_field__name() {

		if ( class_exists( 'wpdev_bk_biz_l' ) ) {

			if ( 'On' == get_bk_option( 'booking_quantity_control' ) ) {

				$booking_capacity_field = get_bk_option( 'booking_capacity_field' );

				if ( ! empty( $booking_capacity_field ) ) {

					$booking_capacity_field = explode( '^', $booking_capacity_field );

					if ( ! empty( $booking_capacity_field ) ) {

						$booking_capacity_field_name = $booking_capacity_field[ count( $booking_capacity_field ) - 1 ];

						return $booking_capacity_field_name;
					}
				}
			}
		}
		return '';
	}


	/**
	 * Get time for booking from  the booking form, as array  of seconds: [ 0, 24 * 60 * 60 ]  OR   [ 10*60*60, 14*60*60 ].    If timefields not exist,  then get time for full day  booking.
	 * @param $booking_form_data__arr
	 *
	 * @return array            [ 0, 24 * 60 * 60 ]  OR   [ 10*60*60, 14*60*60 ]        <- start  and end time in seconds
	 *
	 * Example:
	 *
	 *          // Firstly, we need to Get parsed booking form:     [ name = "John", secondname = "Smith", email = "john.smith@server.com", visitors = "2",... ]
	 *          $structured_booking_data_arr = wpbc_get_parsed_booking_data_arr( $params["form_data"], $params["resource_id"], array( 'get' => 'value' ) );
	 *
	 *          // Now get start/end times as seconds:              [ 64800, 72000 ]
	 *          $time_as_seconds_arr = wpbc_get_in_booking_form__time_to_book_as_seconds_arr( $structured_booking_data_arr );
	 */
	function wpbc_get_in_booking_form__time_to_book_as_seconds_arr( $booking_form_data__arr ){

		$selected_time_fields = wpbc_get__selected_time_fields__in_booking_form__as_arr( $booking_form_data__arr );

        // 2.2 Get selected SECONDS to  book ---------------------------------------------------------------------------
		$time_as_seconds_arr = array( 0, 24 * 60 * 60 );                                    // Full day  booking by  default

		foreach ( $selected_time_fields as $time_fields_obj ) {                             // { times_as_seconds: [ 21600, 23400 ], value_option_24h: '06:00 - 06:30', name: 'rangetime'}

			if ( false !== strpos( $time_fields_obj['name'], 'rangetime' ) ) {
                $time_as_seconds_arr[ 0 ] = $time_fields_obj['times_as_seconds'][ 0 ];
                $time_as_seconds_arr[ 1 ] = $time_fields_obj['times_as_seconds'][ 1 ];
                //break;                                                                      // If we have range-time then  skip  this loop
            }
			if ( false !== strpos( $time_fields_obj['name'], 'starttime' ) ) {
                $time_as_seconds_arr[ 0 ] = $time_fields_obj['times_as_seconds'][ 0 ];
            }
			if ( false !== strpos( $time_fields_obj['name'], 'endtime' ) ) {
                $time_as_seconds_arr[ 1 ] = $time_fields_obj['times_as_seconds'][ 0 ];
            }
        }

		// For duration time we need to  make a new loop,  because we need to be sure that was defined START_TIME before this,
        // and end time was NOT defined,  e.g. ==  (otherwise it's means that  we already  used END_TIME or RANGE_TIME)
        if (
               (           ( 0 ) !== $time_as_seconds_arr[ 0 ] )
            && ( (24 * 60 * 60 ) === $time_as_seconds_arr[ 1 ] )
        ){
			foreach ( $selected_time_fields as $time_fields_obj ) {      // { times_as_seconds: [ 21600 ], value_option_24h: '06:00', name: 'durationtime'}

				if ( false !== strpos( $time_fields_obj['name'], 'durationtime' ) ) {
                    $time_as_seconds_arr[ 1 ] = $time_as_seconds_arr[ 0 ] + $time_fields_obj['times_as_seconds'][ 0 ];
                    break;
                }
            }
        }

		return $time_as_seconds_arr;
	}


		/**
		 * Get all time fields in the booking form as array  of objects
		 *
		 * @param booking_form_data__arr =     [
		 *                                                     selected_short_dates_hint = "September 27, 2023 - September 28, 2023"
		 *                                                     days_number_hint = "2"
		 *                                                     rangetime = "16:00 - 18:00"
		 *                                                     starttime = "21:00"
		 *                                                     durationtime = "00:30"
		 *                                                     name = "John"
		 *                                                     secondname = "Smith"
		 *                                                     email = "john.smith@server.com"
		 *                                                     visitors = "1"
		 *                                                     children = "0"
		 *                                                     details = ""
	     *                                              ]
		 * @returns []
		 *
		 *        Example:
		 *                    [
		 *                        [
		 *                            name = "rangetime"
		 *                            value_option_24h = "16:00 - 18:00"
		 *                            times_as_seconds = [ 57600,  64800 ]
		 *                        ]
		 *                        [
		 *                            name = "starttime"
		 *                            value_option_24h = "21:00"
		 *                            times_as_seconds =  [ 75600 ]
		 *                        ]
		 *                        [
		 *                            name = "durationtime"
		 *                            value_option_24h = "00:30"
		 *                            times_as_seconds =  [ 1800 ]
		 *                        ]
		 *                     ]
		 */
		function wpbc_get__selected_time_fields__in_booking_form__as_arr( $booking_form_data__arr ){

			/**
			 * Fields with  []  like this   select[name="rangetime1[]"]
			 * it's when we have 'multiple' in shortcode:   [select* rangetime multiple  "06:00 - 06:30" ... ]
			 */
			$time_fields_arr = array( 'rangetime', 'starttime', 'endtime', 'durationtime' );

			$time_fields_obj_arr = array();

			// Loop all Time Fields
			for ( $ctf = 0; $ctf < count( $time_fields_arr ); $ctf ++ ) {

				$time_field = $time_fields_arr[ $ctf ];
				if ( isset( $booking_form_data__arr[ $time_field ] ) ) {

					$value_option_seconds_arr = explode( '-', $booking_form_data__arr[ $time_field ] );
					$times_as_seconds_arr = array();

					foreach ( $value_option_seconds_arr as $time_val ) {
						$time_val = trim( $time_val );
						if ( ! empty( $time_val ) ) {
							$start_end_times_arr    = explode( ':', $time_val );
							$time_in_seconds        = intval( $start_end_times_arr[0] ) * 60 * 60 + intval( $start_end_times_arr[1] ) * 60;
							$times_as_seconds_arr[] = $time_in_seconds;
						}
					}

					if (! empty($times_as_seconds_arr)) {

						$time_fields_obj_arr[] = array(
														'name'             => $time_field,
														'value_option_24h' => $booking_form_data__arr[ $time_field ],
														'times_as_seconds' => $times_as_seconds_arr
													);
					}
				}
			}

			return $time_fields_obj_arr;
		}


//TODO: 2023-10-13 16:37
// - create new function  for confirmation  section
// - Update payment request
// - create redirection  to the "Thank you." page and show there conformation,  and payment request"
// - define in the settings new Stripe and PayPal  button  design as default !
// - Be  sure that 'booking_log_booking_actions' active by  default
// - test  it in dark theme
// - test closing booked dates if all  time slot was booked - it's relative to  'different time slots in dif dates'
// - add migrate support old dates selection  variables
// - check  and remove other global Js vars
// - Create wizard booking form style with steps ?
// - Check  Booking > Availability page relative new functionality  of calendar_load !
// - Update last func in wpbc-booking-new.php and remove it.
// - Test  capacity  in dates and time slots
// - Next 9.9 Customizer Wizard
// - Next 9.9 Update search admin UI
// - Next 9.9 - update functionality in Search functionality  based on similar to  where_to_save function.
// - Next 9.9 refactor Dates functions.
// Done.                                                                                                                - Next 9.9 Update Booking > Settings General page  to  show tabs vertically  in left  column
// Create the same navigation panel  at  the Pro  Booking > Settings > Form page. At left  column custom  forms,  at  top tolbar selectio  of Booking form  and "Content of booking fields data" form.
// Create the same navigation  for Emails.
/**
 * TODO: Performance improvement:
 *  "other_code":                   0.003080129623413086
 *  "wpbc__where_to_save_booking":  0.060331106185913086
 *  "wpbc_db__booking_save":         0.023384809494018555
 *  "wpbc_maybe_get_payment_form":  1.3650128841400146          <-
 *  "emails_sending":               1.5024309158325195          <-
 *  "total":                        2.9542438983917236
 */
