<?php /**
 * @version 1.0
 * @description AJX_Bookings
 * @category  AJX_Bookings Actions
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2022-06-10
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Main Ajax handler
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Ajax Actions - Get Listing Data and Response to JS script
 */
function wpbc_ajax_WPBC_AJX_BOOKING_ACTIONS() {

	if ( ! isset( $_POST['action_params'] ) || empty( $_POST['action_params'] ) ) {
		exit;
	}

	$ajax_errors = new WPBC_AJAX_ERROR_CATCHING();

	// Security  -----------------------------------------------------------------------------------------------    	// in Ajax Post:   'nonce': wpbc_ajx_booking_listing.get_secure_param( 'nonce' ),
	$action_name    = 'wpbc_ajx_booking_listing_ajx' . '_wpbcnonce';
	$nonce_post_key = 'nonce';
	$result_check   = check_ajax_referer( $action_name, $nonce_post_key );

	$user_id = ( isset( $_REQUEST['wpbc_ajx_user_id'] ) )  ?  intval( $_REQUEST['wpbc_ajx_user_id'] )  :  wpbc_get_current_user_id();

	// Get clean Parameters for SQL  ---------------------------------------------------------------------------
	$request_rules_structure = array(
						'booking_action' =>  array( 'validate' => array(
																			'set_booking_locale',
																			'set_booking_pending' ,
																			'set_booking_approved',
																			'move_booking_to_trash',
																			'restore_booking_from_trash',
																			'delete_booking_completely',
																			'set_booking_as_read',
																			'set_booking_as_unread',
																			'empty_trash',
																			'set_booking_note',
																			'change_booking_resource',
																			'duplicate_booking_to_other_resource',
																			'set_payment_status',
																			'set_booking_cost',
																			'send_payment_request',
																			'import_google_calendar',
																			'export_csv',
																			'feedback_01'

														), 'default' => '' ),
						'booking_id'              => array( 'validate' => 'digit_or_csd', 'default' => 0 ),
						'selected_resource_id'    => array( 'validate' => 'd', 'default' => 0 ),
						'booking_meta_locale'     => array( 'validate' => 's', 'default' => '' ),
						'reason_of_action'        => array( 'validate' => 's', 'default' => '' ),
						'remark'                  => array( 'validate' => 's', 'default' => '' ),
						'ui_clicked_element_id'   => array( 'validate' => 's', 'default' => '' ),
						'selected_payment_status' => array( 'validate' => 's', 'default' => '' ),
						'booking_cost' 			  => array( 'validate' => 's', 'default' => '' ),

						'export_type' 			  => array( 'validate' => array( 'csv_all', 'csv_page' ), 'default' => 'csv_page' ),
						'csv_export_separator'    => array( 'validate' => array( 'semicolon', 'comma' ), 'default' => ';' ),
						'csv_export_skip_fields'  => array( 'validate' => 's', 'default' => '' ),

						'booking_gcal_events_from'              => array( 'validate' => 's', 'default' => '' ),
						'booking_gcal_events_from_offset'       => array( 'validate' => 's', 'default' => '' ),
						'booking_gcal_events_from_offset_type'  => array( 'validate' => 's', 'default' => '' ),
						'booking_gcal_events_until'             => array( 'validate' => 's', 'default' => '' ),
						'booking_gcal_events_until_offset'      => array( 'validate' => 's', 'default' => '' ),
						'booking_gcal_events_until_offset_type' => array( 'validate' => 's', 'default' => '' ),
						'booking_gcal_events_max'               => array( 'validate' => 'd', 'default' => 25 ),
						'booking_gcal_resource'                 => array( 'validate' => 's', 'default' => '' ),

						'feedback__note'                  => array( 'validate' => 's', 'default' => '' ),
						'feedback_stars'                  => array( 'validate' => 'd', 'default' => 0 )
				);

	$user_request = new WPBC_AJX__REQUEST( array(
											   'db_option_name'          => 'booking_listing_request_params',
											   'user_id'                 => wpbc_get_current_user_id(),
											   'request_rules_structure' => $request_rules_structure
											)
					);
	$request_prefix = 'action_params';
	$request_params = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 			// NOT Direct: 	$_REQUEST['action_params']['resource_id']





	$action_result = array();

	switch ( $request_params['booking_action'] ) {

	    case 'set_booking_locale':
			$action_result = wpbc_booking_do_action__set_booking_locale( $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'booking_locale' => $request_params['booking_meta_locale']
																			  )
																		);
	        break;

	    case 'set_booking_pending':        																				// Pending
		    $action_result = wpbc_booking_do_action__set_booking_approved_or_pending(    $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'reason_of_action' => $request_params['reason_of_action'],
																				'is_approve' 	   => '0'
																			  )
																		);
	        break;

	    case 'set_booking_approved':																					// Approve
		    $action_result = wpbc_booking_do_action__set_booking_approved_or_pending(    $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'reason_of_action' => $request_params['reason_of_action'],
																				'is_approve' 	   => '1'
																			  )
																		);
	        break;

	    case 'move_booking_to_trash':																					// Approve
		    $action_result = wpbc_booking_do_action__trash_booking_or_restore(    $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'reason_of_action' => $request_params['reason_of_action'],
																				'is_trash' 		   => '1'
																			  )
																		);
	        break;

		case 'restore_booking_from_trash':
		    $action_result = wpbc_booking_do_action__trash_booking_or_restore(    $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'reason_of_action' => $request_params['reason_of_action'],
																				'is_trash' 		   => '0'
																			  )
																		);
	        break;

		case 'delete_booking_completely':
		    $action_result = wpbc_booking_do_action__delete_booking_completely(    $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'reason_of_action' => $request_params['reason_of_action'],
																			  )
																		);
	        break;

		case 'set_booking_as_read':
		    $action_result = wpbc_booking_do_action__set_booking_as_read_unread(    $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'is_new' 		   => '0'
																			  )
																		);
	        break;

		case 'set_booking_as_unread':
		    $action_result = wpbc_booking_do_action__set_booking_as_read_unread(    $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'is_new' 		   => '1'
																			  )
																		);
	        break;

		case 'empty_trash':
		    $action_result = wpbc_booking_do_action__empty_trash( array(
																	'user_id'          => $user_id
															    ) );
	        break;

		case 'set_booking_note':
		    $action_result = wpbc_booking_do_action__set_booking_note(    $request_params['booking_id']
																			, array(
																				'user_id' => $user_id,
																				'remark'  => $request_params['remark'],
																			  )
																		);
			break;

	    case 'change_booking_resource':        																				// Pending
		    $action_result = wpbc_booking_do_action__change_booking_resource( $request_params['booking_id']
																			, $request_params['selected_resource_id']
																			, array(
																				'user_id'          => $user_id
																			  )
																		);
	        break;
		case 'duplicate_booking_to_other_resource':
		    $action_result = wpbc_booking_do_action__duplicate_booking_to_other_resource( $request_params['booking_id']
																			, $request_params['selected_resource_id']
																			, array(
																				'user_id'          => $user_id
																			  )
																		);
	        break;
		case 'set_payment_status':
		    $action_result = wpbc_booking_do_action__set_payment_status( $request_params['booking_id']
																			, $request_params['selected_payment_status']
																			, array(
																				'user_id'          => $user_id
																			  )
																		);
	        break;
		case 'set_booking_cost':
		    $action_result = wpbc_booking_do_action__set_booking_cost( $request_params['booking_id']
																			, $request_params['booking_cost']
																			, array(
																				'user_id'          => $user_id
																			  )
																		);
	        break;
		case 'send_payment_request':
		    $action_result = wpbc_booking_do_action__send_payment_request( $request_params['booking_id']
																			, $request_params['reason_of_action']
																			, array(
																				'user_id'          => $user_id
																			  )
																		);
	        break;

		case 'import_google_calendar':
		    $action_result = wpbc_booking_do_action__import_google_calendar(  $request_params
																			, array(
																				'user_id'          => $user_id
																			  )
																		);
			break;

		case 'export_csv':

		    $action_result = wpbc_booking_do_action__export_csv(  $request_params
																			, array(
																				'user_id'          => $user_id
																			  )
																		);
			break;

		case 'feedback_01':

		    $action_result = wpbc_booking_do_action__feedback_01(  $request_params
																			, array(
																				'user_id'          => $user_id
																			  )
																		);
			break;

	    default:
	}


	$defaults = array(
					    'new_listing_params'   => false		// required for Import Google Calendar bookings
                  	  , 'after_action_result'  => false
					  , 'after_action_message' => sprintf( __('No actions %s has been processed.', 'booking')
														 , ' <strong>' . $request_params['booking_action'] . '</strong> ' )
			);
	$action_result = wp_parse_args( $action_result, $defaults );

	// Check if there were some errors  --------------------------------------------------------------------------------
	$error_messages = $ajax_errors->get_error_messages();
	if ( ! empty( $error_messages ) ) {
		$action_result['after_action_message'] .= $error_messages;
	}

	// Hook for other integrations
	do_action( 'wpbc_' . $request_params['booking_action'], $request_params, $action_result );							//FixIn: 9.5.3.3

	//------------------------------------------------------------------------------------------------------------------
	// Send JSON. Its will make "wp_json_encode" - so pass only array, and This function call wp_die( '', '', array( 'response' => null, ) )		Pass JS OBJ: response_data in "jQuery.post( " function on success.
	wp_send_json( array(
						'ajx_action_params'                      => $_REQUEST['action_params'],							// Do not clean input parameters
						'ajx_cleaned_params'                     => $request_params,									// Cleaned input parameters
						'ajx_after_action_message'               => $action_result['after_action_message'],				// Message to  show
						'ajx_after_action_result'                => (int) $action_result['after_action_result'],		// Result key 				0 | 1
						'ajx_after_action_result_all_params_arr' => $action_result										// All result parameters
					) );
}


// <editor-fold     defaultstate="collapsed"                        desc="  ==  Ajax Actions for bookings  ==  "  >

	/**
	 * Action: set booking as Locale
	 *
	 * @param int $booking_id				ID of booking			10
	 * @param $params
	 *
	 * @return array
	 */
	function wpbc_booking_do_action__set_booking_locale( $booking_id, $params ){

		$booking_locale = $params['booking_locale'];

		// LOG ---------------------------------------------------------------------------------------------------------
		$curr_user = get_user_by( 'id', (int) $params['user_id'] );
		wpbc_db__add_log_info( 		$booking_id,
								sprintf( __( 'Booking locale changed to %s by:', 'booking' ), $booking_locale )
								. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
						 );

		// Action  ---------------------------------------------------------------------------------------------------------
		$option_arr = array( 'booking_meta_locale' => $booking_locale );

		$result = wpbc_save_booking_meta_option( $booking_id, $option_arr );

		if ( $result ) {
			$after_action_result  = true;
			$after_action_message = ( ( false === strpos( $booking_id, ',' ) )
										? sprintf( __( 'Booking has been changed locale to %s', 'booking' ), "<strong>{$booking_locale}</strong>" )
										: sprintf( __( 'Bookings have been changed locale to %s', 'booking' ), "<strong>{$booking_locale}</strong>" )
									)
									. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id . '</strong> )</span>';
		} else {
			$after_action_result  = false;
			$after_action_message = ( ( false === strpos( $booking_id, ',' ) )
										? sprintf( __( 'Booking has NOT been changed locale to %s', 'booking' ), "<strong>{$booking_locale}</strong>" )
										: sprintf( __( 'Bookings have NOT been changed locale to %s', 'booking' ), "<strong>{$booking_locale}</strong>" )
									)
									. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id . '</strong> )</span>';
		}

		return array(
					'after_action_result' => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}

	/**
	 * Action: Get Google Calendar Link
	 *
	 * @param $booking_data					array(
														'form_data'   => array( ... )
														'form_show'   => ....
														'dates_short' => array( ... )
												)
	 *
	 * @return string	URL  of encoded link for adding to  Google Calendar
	 */
	function wpbc_booking_do_action__get_google_calendar_link( $booking_data ){

		$params = array();
		$params['timezone'] = get_bk_option('booking_gcal_timezone');

		$booking_gcal_events_form_fields = maybe_unserialize( get_bk_option( 'booking_gcal_events_form_fields') );			// array( [title]=>text^name,  [description]=>textarea^details,  [where]=>text^	)

		// Fields ----------------------------------------------------------------------------------------------------------
		$fields = array( 'title' => '', 'description' => '', 'where' => '' );

		foreach ( $fields as $key_name => $field_value ) {

			if ( ! empty( $booking_gcal_events_form_fields[ $key_name ] ) ) {

				$field_name = explode( '^', $booking_gcal_events_form_fields[ $key_name ] );

				$field_name = $field_name[ ( count( $field_name ) - 1 ) ];                                                  //FixIn: 8.7.7.6

				if ( 'description' === $key_name ) {                                                                	//FixIn: 8.1.3.2

					if ( isset( $booking_data['form_show'] ) ) {                                                    	//FixIn: 8.7.3.14
																															//FixIn: 8.7.11.4
							$fields[ $key_name ] = $booking_data['form_show'];
							$fields[ $key_name ] = htmlspecialchars_decode($fields[ $key_name ], ENT_QUOTES );
							$fields[ $key_name ] = urlencode($fields[ $key_name ]);
							$fields[ $key_name ] = htmlentities($fields[ $key_name ] );
							$fields[ $key_name ] = htmlspecialchars_decode ( $fields[ $key_name ], ENT_NOQUOTES );
					}

				} else if ( ( ! empty( $field_name ) )
					 && ( ! empty( $booking_data['form_data'] ) )
					 && ( ! empty( $booking_data['form_data'][ $field_name ] ) )
				) {
					//FixIn: 8.7.11.4
					$fields[ $key_name ] = $booking_data['form_data'][ $field_name ];
					$fields[ $key_name ] = htmlspecialchars_decode($fields[ $key_name ], ENT_QUOTES );
					/**
					 *  // Convert here from  usual  symbols to URL symbols https://www.url-encode-decode.com/
						$fields[ $key_name ] = str_replace(    array( '%','#', '+', '&' )
															 , array( '%25','%23', '%2B', '%26')
															 , $fields[ $key_name ]
												);
					 */
					$fields[ $key_name ] = urlencode($fields[ $key_name ]);
					$fields[ $key_name ] = htmlentities($fields[ $key_name ] );
					$fields[ $key_name ] = htmlspecialchars_decode ( $fields[ $key_name ], ENT_NOQUOTES );
				}
			}
		}

		// Dates -----------------------------------------------------------------------------------------------------------
		$check_in_timestamp = $check_out_timestamp = '';
		if ( ! empty( $booking_data[ 'dates_short' ] ) ) {

			/**
			 * All day events, you can use 20161208/20161209 - note that the old google documentation gets it wrong.
			 * You must use the following date as the end date for a one day all day event,
			 * or +1 day to whatever you want the end date to be.
			 */

			$check_in_timestamp  = strtotime( $booking_data[ 'dates_short' ][ 0 ], current_time( 'timestamp' ) );
			if ( trim( substr( $booking_data[ 'dates_short' ][ 0 ], 11 ) ) == '00:00:00' ) {
				$check_in_timestamp = date( "Ymd", $check_in_timestamp );				// All day
			} else {
				$check_in_timestamp = date( "Ymd\THis", $check_in_timestamp );			//$check_in_timestamp = date( "Ymd\THis\Z", $check_in_timestamp );
			}

			$check_out_timestamp = strtotime( $booking_data[ 'dates_short' ][ ( count( $booking_data[ 'dates_short' ] ) - 1 ) ], current_time( 'timestamp' ) );
			if ( trim( substr( $booking_data[ 'dates_short' ][ ( count( $booking_data[ 'dates_short' ] ) - 1 ) ], 11 ) ) == '00:00:00' ) {
				$check_out_timestamp = strtotime( '+1 day', $check_out_timestamp );
				$check_out_timestamp = date( "Ymd", $check_out_timestamp );				// All day
			} else {
				$check_out_timestamp = date( "Ymd\THis", $check_out_timestamp );		//$check_out_timestamp = date( "Ymd\THis\Z", $check_out_timestamp );
			}
		}

		// Link  -----------------------------------------------------------------------------------------------------------
		/**
		 * Convert an ISO date/time to a UNIX timestamp
		function iso_to_ts( $iso ) {
			sscanf( $iso, "%u-%u-%uT%u:%u:%uZ", $year, $month, $day, $hour, $minute, $second );
			return mktime( $hour, $minute, $second, $month, $day, $year );
		 20140127T224000Z
		 date("Ymd\THis\Z", time());
		*/

		/**
		 * 	action:		action=TEMPLATE
						A default required parameter.

	src:
		Example: src=default%40gmail.com
		Format: src=text
		This is not covered by Google help but is an optional parameter in order to add an event to a shared calendar rather than a user's default.

	text:
		Example: text=Garden%20Waste%20Collection
		Format: text=text
		This is a required parameter giving the event title.

	dates:
		Example: dates=20090621T063000Z/20090621T080000Z (i.e. an event on 21 June 2009 from 7.30am to 9.0am British Summer Time (=GMT+1)).
		Format: dates=YYYYMMDDToHHMMSSZ/YYYYMMDDToHHMMSSZ
		This required parameter gives the start and end dates and times (in Greenwich Mean Time) for the event.

	location:
		Example: location=Home
		Format: location=text
		The obvious location field.

	trp:
		Example: trp=false
		Format: trp=true/false
		Show event as busy (true) or available (false)

	sprop:
		Example: sprop=http%3A%2F%2Fwww.me.org
		Example: sprop=name:Home%20Page
		Format: sprop=website and/or sprop=name:website_name
		 */

		//$link_add2gcal  = 'http://www.google.com/calendar/event?action=TEMPLATE';
		//$link_add2gcal .= '&text=' . $fields['title'];
		$link_add2gcal = 'https://calendar.google.com/calendar/r/eventedit?';												//FixIn: 8.7.3.10
		$link_add2gcal .= 'text=' . $fields['title'];																		//FixIn: 8.7.11.4
		$link_add2gcal .= '&dates=' . $check_in_timestamp . '/' . $check_out_timestamp;					//$link_add2gcal .= '&dates=[start-custom format='Ymd\\THi00\\Z']/[end-custom format='Ymd\\THi00\\Z']';
		$link_add2gcal .= '&details=' . $fields['description'];                												//FixIn: 8.7.11.4
		$link_add2gcal .= '&location=' . $fields['where'];                     												//FixIn: 8.7.11.4
		$link_add2gcal .= '&trp=false';
		if ( ! empty( $params['timezone'] ) ) {
			$link_add2gcal .= '&ctz=' . str_replace( '%', '%25', $params['timezone'] );                   					//FixIn: 8.7.3.10		//TimeZone
		}
		//$link_add2gcal .= '&sprop=';
		//$link_add2gcal .= '&sprop=name:';

		return $link_add2gcal;
	}

	/**
	 * Action: move booking to  Trash | Restore
	 *
	 * @param $booking_id_arr	array or int	of booking ID
	 * @param $params			array			array of parameters: 		array(	'user_id' => 1, 'reason_of_action' => 'Because...', 'is_trash' => '1' )
	 *
	 * @return array
	 *
	 * Example:
	 */
	function wpbc_booking_do_action__trash_booking_or_restore( $booking_id_arr, $params ){

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_is_csd = join( ',',  $booking_id_arr  );
		$booking_is_csd = wpbc_clean_digit_or_csd( $booking_is_csd );


		// Get reason of action --------------------------------------------------------------------------------------------
		$action_reason = $params['reason_of_action'];	// stripslashes( $params['reason_of_action'] ); // translate words like don\'t to don't

		// Get reason of action --------------------------------------------------------------------------------------------
		$is_trash_or_restore = $params['is_trash'];

		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );


		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_is_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result   = true;
			if ( $is_trash_or_restore == '1' ) {
				$after_action_message = '<strong style="font-size: 1.4em;">' . count( explode( ',', $booking_is_csd ) ) .'</strong> '
										. ( ( false === strpos( $booking_is_csd, ',' ) )
											? sprintf( __( 'Booking has been %s trashed %s', 'booking' ), '<strong>', '</strong>' )
											: sprintf( __( 'Bookings have been %s trashed %s', 'booking' ) , '<strong>', '</strong>' )
										)
										. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_is_csd . '</strong> )</span>';
			} else {
				$after_action_message = '<strong style="font-size: 1.4em;">' . count( explode( ',', $booking_is_csd ) ) .'</strong> '
										. ( ( false === strpos( $booking_is_csd, ',' ) )
											? sprintf( __( 'Booking has been set as %s restored %s', 'booking' ), '<strong>', '</strong>' )
											: sprintf( __( 'Bookings have been set as %s restored %s', 'booking' ), '<strong>', '</strong>' )
										)
										. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_is_csd . '</strong> )</span>';
			}
			//    SQL    ---------------------------------------------------------------------------------------------------
			global $wpdb;
			$prepared_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.trash = %s WHERE booking_id IN ({$booking_is_csd})", $is_trash_or_restore );
			if ( $is_trash_or_restore == '1' ) {
				// Trash
				$my_trash_date = wpbc_datetime_localized( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' );
				$prepared_sql  = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET  bk.trash = %s, bk.is_trash = %s WHERE booking_id IN ({$booking_is_csd})", $is_trash_or_restore, $my_trash_date );
			} else {
				// Restore
				$prepared_sql  = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET  bk.trash = %s, bk.is_trash = NULL WHERE booking_id IN ({$booking_is_csd})", $is_trash_or_restore );
			}


			$after_action_result = $wpdb->query( $prepared_sql );
			if ( false === $after_action_result ) {
				$after_action_message = 'Error during updating to DB. File:' . __FILE__ . ' on line: ' . __LINE__;
			} else {
				$after_action_result = true;
			}

			// Update the Hash and Cost of the booking
			$bk_id_arr = explode(',', $booking_is_csd );																	//FixIn: 8.6.1.11
			foreach ( $bk_id_arr as $bk_id ) {
				wpbc_hash__update_booking_hash( $bk_id );
			}

			// LOG ---------------------------------------------------------------------------------------------------------
			$curr_user = get_user_by( 'id', (int) $params['user_id'] );
			wpbc_db__add_log_info( 		explode( ',', $booking_is_csd ),
									( ( $is_trash_or_restore == '1' ) ? __( 'Trashed by:', 'booking' ) : __( 'Restored by:', 'booking' ) )
									. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
							 );

			// ::  ?  ::   Update 'is_new' status of bookings in Database
			// wpbc_db_update_number_new_bookings( explode( ',', $booking_is_csd ), '0', $params['user_id'] );

			// Just  action  hook  for some other addons
			do_action( 'wpbc_booking_action__trash', $booking_is_csd, $is_trash_or_restore );                                 		//FixIn: 8.7.6.2

			// Emails ------------------------------------------------------------------------------------------------------
			if ( ! empty( $is_send_emeils ) ) {

				if ( $is_trash_or_restore == '1' ) {
					wpbc_send_email_trash( $booking_is_csd, $is_send_emeils, $action_reason );
				} else {
					//wpbc_send_email_approved( $booking_is_csd, $is_send_emeils, $action_reason );
					//wpbc_send_email_deny( 	$booking_is_csd, $is_send_emeils, $action_reason );
				}
			}
		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}

	/**
	 * Action: set booking as 	Approved | Pending
	 *
	 * @param $booking_id_arr	array or int	of booking ID
	 * @param $params			array			array of parameters: 		array(	'user_id' => 1, 'reason_of_action' => 'Because...', 'is_approve' => '1' )
	 *
	 * @return array
	 *
	 * Example:
	 * 			Approved:
								$action_result = wpbc_booking_do_action__set_booking_approved_or_pending(    $request_params['booking_id']
																				 , array(
																					 'user_id'          => $user_id,
																					 'reason_of_action' => $request_params['reason_of_action'],
																					 'is_approve' => '1'
																				   )
																			 );
	 * 			Pending:
								$action_result = wpbc_booking_do_action__set_booking_approved_or_pending(    $request_params['booking_id']
																				 , array(
																					 'user_id'          => $user_id,
																					 'reason_of_action' => $request_params['reason_of_action'],
																					 'is_approve' => '0'
																				   )
																			 );
	 */
	function wpbc_booking_do_action__set_booking_approved_or_pending( $booking_id_arr, $params ){

		/**
		 *
		 * For creation  of new bookings or editing use something like this:
		 *
		  // Be sure to  make load in Booking Listing  this:  wp_enqueue_script( 'wpbc-main-client', wpbc_plugin_url( '/js/client.js' ), array( 'wpbc-datepick' ), WP_BK_VERSION_NUM );
				new wpdev_booking(); // Define ability to  work  with  shortcodes
				$return_sh  = do_shortcode('[booking type=1 nummonths=2]');
				wp_send_json($return_sh); // Send calendar
		*/

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_id_csd = join( ',',  $booking_id_arr  );
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );


		// Get reason of action --------------------------------------------------------------------------------------------
		$action_reason = $params['reason_of_action'];	// stripslashes( $params['reason_of_action'] ); // translate words like don\'t to don't

		// Get reason of action --------------------------------------------------------------------------------------------
		$is_approve_or_pending = $params['is_approve'];

		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );


		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_id_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result   = true;
			if ( $is_approve_or_pending == '1' ) {
				$after_action_message = '<strong style="font-size: 1.4em;">' . count( explode( ',', $booking_id_csd ) ) .'</strong> '
										. ( ( false === strpos( $booking_id_csd, ',' ) )
											? sprintf( __( 'Booking has been %s approved %s', 'booking' ), '<strong>', '</strong>' )
											: sprintf( __( 'Bookings have been %s approved %s', 'booking' ) , '<strong>', '</strong>' )
										)
										. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id_csd . '</strong> )</span>';
			} else {
				$after_action_message = '<strong style="font-size: 1.4em;">' . count( explode( ',', $booking_id_csd ) ) .'</strong> '
										. ( ( false === strpos( $booking_id_csd, ',' ) )
											? sprintf( __( 'Booking has been set as %s pending %s', 'booking' ), '<strong>', '</strong>' )
											: sprintf( __( 'Bookings have been set as %s pending %s', 'booking' ), '<strong>', '</strong>' )
										)
										. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id_csd . '</strong> )</span>';
			}
			//    SQL    ---------------------------------------------------------------------------------------------------
			global $wpdb;
			$prepared_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}bookingdates SET approved = %s WHERE booking_id IN ({$booking_id_csd})", $is_approve_or_pending );
			$after_action_result = $wpdb->query( $prepared_sql );
			if ( false === $after_action_result ) {
				$after_action_message = 'Error during updating to DB. File:' . __FILE__ . ' on line: ' . __LINE__;
			} else {
				$after_action_result = true;
			}

			// LOG ---------------------------------------------------------------------------------------------------------
			$curr_user = get_user_by( 'id', (int) $params['user_id'] );
			wpbc_db__add_log_info( 		explode( ',', $booking_id_csd ),
									( ( $is_approve_or_pending == '1' ) ? __( 'Approved by:', 'booking' ) : __( 'Declined by:', 'booking' ) )
									. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
							 );

			// Update 'is_new' status of bookings in Database
			wpbc_db_update_number_new_bookings( explode( ',', $booking_id_csd ), '0', $params['user_id'] );

			// Just  action  hook  for some other addons
			do_action( 'wpbc_booking_action__approved', $booking_id_csd, $is_approve_or_pending );                                 //FixIn: 8.7.6.1

			// Emails ------------------------------------------------------------------------------------------------------
			if ( ! empty( $is_send_emeils ) ) {

				if ( $is_approve_or_pending == '1' ) {
					wpbc_send_email_approved( $booking_id_csd, $is_send_emeils, $action_reason );
				} else {
					wpbc_send_email_deny( $booking_id_csd, $is_send_emeils, $action_reason );
				}
			}

			// Cancel  other pending bookings for the same date	------------------------------------------------------------
			if ( $is_approve_or_pending == '1' ) {
				$all_bk_id_what_canceled = apply_bk_filter( 'cancel_pending_same_resource_bookings_for_specific_dates', false, $booking_id_csd );
			}
		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}

	/**
	 * Action:  booking 	Completely Delete
	 *
	 * @param $booking_id_arr	array or int	of booking ID
	 * @param $params			array			array of parameters: 		array(	'user_id' => 1, 'reason_of_action' => 'Because...' )
	 *
	 * @return array
	 *
	 * Example:
	 */
	function wpbc_booking_do_action__delete_booking_completely( $booking_id_arr, $params ){

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_is_csd = join( ',',  $booking_id_arr  );
		$booking_is_csd = wpbc_clean_digit_or_csd( $booking_is_csd );


		// Get reason of action --------------------------------------------------------------------------------------------
		$action_reason = $params['reason_of_action'];	// stripslashes( $params['reason_of_action'] ); // translate words like don\'t to don't

		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );


		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_is_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result   = true;
			$bookings_count = '<strong style="font-size: 1.4em;">' . count( explode( ',', $booking_is_csd ) ) .'</strong> ';
			$after_action_message = $bookings_count . ' '
								  . ( ( false === strpos( $booking_is_csd, ',' ) )
									  	? strtolower( sprintf( __( 'Booking has been %s deleted %s', 'booking' ), '<strong>', '</strong>' ) )
										: strtolower( sprintf( __( 'Bookings have been %s deleted %s', 'booking' ) , '<strong>', '</strong>' ) )
									)
								 . ' <span style="font-size:0.9em;">( ID = <strong>' . implode( ', ', explode( ',', $booking_is_csd ) ) . '</strong> )</span>';

			// LOG ---------------------------------------------------------------------------------------------------------
			$curr_user = get_user_by( 'id', (int) $params['user_id'] );
			wpbc_db__add_log_info( 		explode( ',', $booking_is_csd ),
									__( 'Deleted by:', 'booking' )
									. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
							 );

			// Just  action  hook  for some other addons
			do_action( 'wpbc_booking_action__delete', $booking_is_csd );                                 						//FixIn: 8.7.6.3

			// Emails ------------------------------------------------------------------------------------------------------
			if ( ! empty( $is_send_emeils ) ) {

				wpbc_send_email_deleted( $booking_is_csd, $is_send_emeils, $action_reason );
			}

			//    SQL    ---------------------------------------------------------------------------------------------------
			global $wpdb;

			// Dates
			$prepared_sql = "DELETE FROM {$wpdb->prefix}bookingdates WHERE booking_id IN ({$booking_is_csd})";
			$after_action_result = $wpdb->query( $prepared_sql );
			if ( false === $after_action_result ) {
				$after_action_message = 'Error during deleting dates in DB. File:' . __FILE__ . ' on line: ' . __LINE__;
			} else {
				$after_action_result = true;
			}
			// Bookings
			$prepared_sql = "DELETE FROM {$wpdb->prefix}booking WHERE booking_id IN ({$booking_is_csd})";
			$after_action_result = $wpdb->query( $prepared_sql );
			if ( false === $after_action_result ) {
				$after_action_message = 'Error during deleting bookings in DB. File:' . __FILE__ . ' on line: ' . __LINE__;
			} else {
				$after_action_result = true;
			}

		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}

	/**
	 * Action: set booking as 	Read (Old) | Unread (New)
	 *
	 * @param $booking_id_arr	array or int	of booking ID
	 * @param $params			array			array of parameters: 		array(	'user_id' => 1, 'is_new' => '1' )
	 *
	 * @return array
	 *
	 * Example:
		    $action_result = wpbc_booking_do_action__set_booking_as_read_unread(    $request_params['booking_id']
																			, array(
																				'user_id'          => $user_id,
																				'is_new' 		   => '0'
																			  )
																		);

	 */
	function wpbc_booking_do_action__set_booking_as_read_unread( $booking_id_arr, $params ){

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_id_csd = join( ',',  $booking_id_arr  );
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );

		// Get reason of action --------------------------------------------------------------------------------------------
		$is_new = $params['is_new'];

		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_id_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result   = true;
			if ( $is_new == '1' ) {
				$after_action_message =  ( ( false === strpos( $booking_id_csd, ',' ) )
											? sprintf( __( 'Booking has been set as %s new %s', 'booking' ), '<strong>', '</strong>' )
											: sprintf( __( 'Bookings have been set as %s new %s', 'booking' ) , '<strong>', '</strong>' )
										)
										. ' <span style="font-size:0.9em;">( ID = <strong>' . (( '-1' == $booking_id_csd ) ? __( 'all', 'booking' ) : $booking_id_csd) . '</strong> )</span>';
			} else {
				$after_action_message = ( ( false === strpos( $booking_id_csd, ',' ) )
											? sprintf( __( 'Booking has been set as set as %s read %s', 'booking' ), '<strong>', '</strong>' )
											: sprintf( __( 'Bookings have been set as set as %s read %s', 'booking' ), '<strong>', '</strong>' )
										)
										. ' <span style="font-size:0.9em;">( ID = <strong>' . (( '-1' == $booking_id_csd ) ? __( 'all', 'booking' ) : $booking_id_csd) . '</strong> )</span>';
			}
			//    SQL    ---------------------------------------------------------------------------------------------------
			global $wpdb;
			if ('-1' == $booking_id_csd ){
				$prepared_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.is_new = %s", $is_new );

				$prepared_sql .= " WHERE  ( 1 = 1 ) ";
				$prepared_sql = apply_bk_filter('update_where_sql_for_getting_bookings_in_multiuser', $prepared_sql );
			} else {
				$prepared_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.is_new = %s WHERE booking_id IN ({$booking_id_csd})", $is_new );
			}
			$after_action_result = $wpdb->query( $prepared_sql );
			if ( false === $after_action_result ) {
				$after_action_message = 'Error during updating to DB. File:' . __FILE__ . ' on line: ' . __LINE__;
			} else {
				$after_action_result = true;
			}

			// LOG ---------------------------------------------------------------------------------------------------------
			$curr_user = get_user_by( 'id', (int) $params['user_id'] );
			wpbc_db__add_log_info( 		explode( ',', $booking_id_csd ),
									( ( $is_new == '1' ) ? __( 'Set as unread by:', 'booking' ) : __( 'Set as read by:', 'booking' ) )
									. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
							 );

			// Just  action  hook  for some other addons
			do_action( 'wpbc_booking_action__changed_new_status', $booking_id_csd, $is_new );                                 //FixIn: 8.7.6.1

			// Emails ------------------------------------------------------------------------------------------------------
			if ( ! empty( $is_send_emeils ) ) {

				if ( $is_new == '1' ) {
					//wpbc_send_email_approved( $booking_id_csd, $is_send_emeils, $action_reason );
				} else {
					//wpbc_send_email_deny( $booking_id_csd, $is_send_emeils, $action_reason );
				}
			}
		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}

	/**
	 * Action:  Emty Trash
	 *
	 * @param $params			array			array of parameters: 		array(	'user_id' => 1 )
	 *
	 * @return array
	 *
	 * Example:
	 */
	function wpbc_booking_do_action__empty_trash( $params ){

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

		// Get bookings ID to delete -------------------------------------------------------------------------------
		global $wpdb;

		$sql = "SELECT * FROM {$wpdb->prefix}booking as bk WHERE bk.trash = 1";

		$sql = apply_bk_filter( 'update_where_sql_for_getting_bookings_in_multiuser', $sql, $params['user_id'] );                    // Get booking resources of this user only: $user_id

		$max_bookings_to_erase = 1000;
		$sql .= " LIMIT 0, " . $max_bookings_to_erase;

		$bookings_in_trash = $wpdb->get_results( $sql );			//Get ID of all bookings in a trash.

		$bookings_id_in_trash_arr = array();

		foreach ( $bookings_in_trash as $booking_obj ) {
			$bookings_id_in_trash_arr[] = $booking_obj->booking_id;
		}
		$booking_is_csd = implode( ',', $bookings_id_in_trash_arr );

		// Empty trash  ------------------------------------------------------------------------------------------------

		if ( empty( $booking_is_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings in trash to erase.', 'booking' );

		} else {
			$after_action_result   = true;
			if ( count( $bookings_id_in_trash_arr ) < $max_bookings_to_erase ) {
				$after_action_message = sprintf( __( 'Trash has been erased.', 'booking' ) , '<strong>', '</strong>' )
										. ' <span style="font-size:0.9em;">( ID = <strong>' . substr( $booking_is_csd, 0 , 500 ) . '</strong> )</span>';
			} else {
				$after_action_message = sprintf( __( 'From trash has been erased %s bookings.', 'booking' ) , '<strong> ' . count( $bookings_id_in_trash_arr ) . ' </strong> ' ) ;
			}

			// LOG -----------------------------------------------------------------------------------------------------
			//			$curr_user = get_user_by( 'id', (int) $params['user_id'] );
			//			wpbc_db__add_log_info( 		explode( ',', $booking_is_csd ),
			//									__( 'Deleted by:', 'booking' )
			//									. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
			//							 );

			// Just  action  hook  for some other addons
			do_action( 'wpbc_booking_action__empty_trash' );                                 											//FixIn: 8.7.6.3

			// Emails ------------------------------------------------------------------------------------------------------
			if ( ! empty( $is_send_emeils ) ) {

				// wpbc_send_email_deleted( $booking_is_csd, $is_send_emeils, __( 'Empty Trash', 'booking' )  );
			}

			//    SQL    ---------------------------------------------------------------------------------------------------

			// Dates
			$prepared_sql = "DELETE FROM {$wpdb->prefix}bookingdates WHERE booking_id IN ({$booking_is_csd})";
			$after_action_result = $wpdb->query( $prepared_sql );
			if ( false === $after_action_result ) {
				$after_action_message = 'Error during deleting dates in DB. File:' . __FILE__ . ' on line: ' . __LINE__;
			} else {
				$after_action_result = true;
			}
			// Bookings
			$prepared_sql = "DELETE FROM {$wpdb->prefix}booking WHERE booking_id IN ({$booking_is_csd})";
			$after_action_result = $wpdb->query( $prepared_sql );
			if ( false === $after_action_result ) {
				$after_action_message = 'Error during deleting bookings in DB. File:' . __FILE__ . ' on line: ' . __LINE__;
			} else {
				$after_action_result = true;
			}

		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}

	/**
	 * Action: save booking   Remark
	 *
	 * @param $booking_id_arr	array or int	of booking ID
	 * @param $params			array			array of parameters: 		array(	'user_id' => 1, 'remark' => 'Because...')
	 *
	 * @return array
	 *
	 * Example:
	 */
	function wpbc_booking_do_action__set_booking_note( $booking_id_arr, $params ){

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_id_csd = join( ',',  $booking_id_arr  );
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );

		// Get remark --------------------------------------------------------------------------------------------------
		$remark_text = $params['remark'];	// stripslashes( $params['reason_of_action'] ); // translate words like don\'t to don't

		// Is send email  for this action ------------------------------------------------------------------------------
		// $is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );


		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_id_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result   = true;
			$after_action_message = __( 'Note has been saved', 'booking' )
									. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id_csd . '</strong> )</span>';
			//    SQL    ---------------------------------------------------------------------------------------------------
			global $wpdb;
			$prepared_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking SET remark = %s WHERE booking_id IN ({$booking_id_csd})", $remark_text );
			$after_action_result = $wpdb->query( $prepared_sql );
			if ( false === $after_action_result ) {
				$after_action_message = 'Error during updating to DB. File:' . __FILE__ . ' on line: ' . __LINE__;
			} else {
				$after_action_result = true;
			}

			// LOG ---------------------------------------------------------------------------------------------------------
			if (0) {
					$curr_user = get_user_by( 'id', (int) $params['user_id'] );
					wpbc_db__add_log_info( 		explode( ',', $booking_id_csd ),
											 __( 'Note saved by:', 'booking' )
											. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
									 );
			}

			// Just  action  hook  for some other addons
			do_action( 'wpbc_booking_action__note_saved', $booking_id_csd, $remark_text );

			// Emails ------------------------------------------------------------------------------------------------------
			if ( 0 ) {
				if ( ! empty( $is_send_emeils ) ) {
					wpbc_send_email_approved( $booking_id_csd, $is_send_emeils, $remark_text );
				}
			}
		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}

	/**
	 * Action: change Booking Resource
	 *
	 * @param $booking_id_arr			array or int	of booking ID
	 * @param $selected_resource_id	 	int				of booking resource
	 * @param $params					array			array of parameters: 		array(	'user_id' => 1 )
	 *
	 * @return array
	 *
	 * Example:
	 */
	function wpbc_booking_do_action__change_booking_resource( $booking_id_arr, $selected_resource_id, $params ) {

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_id_csd = join( ',',  $booking_id_arr  );
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );

		// ID of booking resource ------------------------------------------------------------------------------------------
		$selected_resource_id = intval($selected_resource_id);

		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_id_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result   = true;

			$after_action_message = ( ( false === strpos( $booking_id_csd, ',' ) )
										? sprintf( __( 'Booking has been changed %s booking resource %s', 'booking' ), '<strong>', '</strong>' )
										: sprintf( __( 'Bookings have been changed %s booking resource %s', 'booking' ), '<strong>', '</strong>' )
									)
									. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id_csd . '</strong> )</span>';


			$work_booking_id_arr = explode( ',', $booking_id_csd );

			foreach ( $work_booking_id_arr as $selected_booking_id ) {

				//    SQL    ---------------------------------------------------------------------------------------------------
				list( $after_action_result, $after_action_message, $formdata_new ) = wpbc__sql__change_booking_resource_for_booking( $selected_booking_id, $selected_resource_id );

				if ( $after_action_result ) {
					// LOG ---------------------------------------------------------------------------------------------------------
					$curr_user = get_user_by( 'id', (int) $params['user_id'] );
					wpbc_db__add_log_info( 		explode( ',', $selected_booking_id ),
											__( 'Booking resource changed by:', 'booking' )
											. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
									 );

					// Just  action  hook  for some other addons
					do_action( 'wpbc_booking_action__change_booking_resource', $selected_booking_id, $selected_resource_id );

					// Emails ------------------------------------------------------------------------------------------------------
					if ( ! empty( $is_send_emeils ) ) {
						wpbc_send_email_modified( $selected_booking_id, $selected_resource_id, $formdata_new );
					}
				}
			}
		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);

	}

		/**
		 * Change booking resource for specific booking -- SQL manipulation
		 * @param $selected_booking_id
		 * @param $selected_resource_id
		 *
		 * @return array
		 */
		function wpbc__sql__change_booking_resource_for_booking( $selected_booking_id, $selected_resource_id ){

			global $wpdb;

			$booking_id   = intval( $selected_booking_id );
			$resource_id  = intval( $selected_resource_id );
			$db_form_data_new = '';

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 1.Get dates of SOURCE booking
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if ( 1 ) {
				// 1.1.Get booking data of SOURCE booking
				$sql              = $wpdb->prepare( "SELECT * FROM  {$wpdb->prefix}booking as bk WHERE booking_id = %d ", $booking_id );
				$res              = $wpdb->get_row( $sql );
				$db_form_data_old = $res->form;
				$resource_id_old  = $res->booking_type;

				// 1.2. Get dates of SOURCE booking
				$sql                      = $wpdb->prepare( "SELECT * FROM  {$wpdb->prefix}bookingdates as dt WHERE booking_id = %d ORDER BY booking_date ASC ", $booking_id );
				$old_resource_dates_array = $wpdb->get_results( $sql );
			}


			//TODO: 2023-10-03 14:49  this is new workflow have to be:
			if (0){
				$booking_p = array(
								  'dates'	 => TRANSFORM_IT( $old_resource_dates_array ) // ->  array( '2017-06-23 14:00:01', '2017-06-24 00:00:00', '2017-06-25', '2017-06-26 12:00:02' )
								, 'resource_id' => $resource_id
							    , 'params'      => array()			// 'is_use_booking_recurrent_time' => false )		// Commented,  because get from  Booking > Settings General page
						   );
				$is_dates_booked = wpbc_api_is_dates_booked( $booking_p[ 'dates' ], $booking_p[ 'resource_id' ], $booking_p[ 'params' ] );
			}

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 2. Get bookings of selected booking resource - checking if some dates there is booked or not
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$exist_dates_results = wpbc_get_booking_dates_in_resource_for_folowing_dates( $old_resource_dates_array , $resource_id );


			if ( ( count( $exist_dates_results ) == 3 ) && ( false === $exist_dates_results[0] ) ){
				// // ERROR :: number of check in/dates does not equal  to  number of check  out dates.
				return $exist_dates_results;
			}

			if ( get_bk_option('booking_change_resource_skip_checking') === 'On' ) {									//FixIn: 8.4.5.4
				$is_date_time_booked = false;
			} else {
				$is_date_time_booked = wpbc_check_dates_intersections( $old_resource_dates_array, $exist_dates_results );
			}

			if (  $is_date_time_booked ) {

				$after_action_result  = false;
				$after_action_message = __( 'Warning! The resource was not changed. Current dates are already booked there.', 'booking' );

			} else {		// Possible to change

				$db_form_data_new = wpbc_update_resource_id_in_dbformatted_booking_data( $db_form_data_old, $resource_id_old, $resource_id );

				// Update
				$update_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.form=%s, bk.booking_type=%d WHERE bk.booking_id=%d;"
													, $db_form_data_new, $resource_id, $booking_id );
				if ( false === $wpdb->query( $update_sql ) ) {
					$after_action_result  = false;
					$after_action_message = get_debuge_error( 'Error during updating booking reource type in BD', __FILE__, __LINE__ );

					return array( $after_action_result, $after_action_message, $db_form_data_new );
				}


				if ( class_exists( 'wpdev_bk_biz_l' ) ) {
					$update_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}bookingdates SET type_id=NULL WHERE booking_id=%d ", $booking_id );
					if ( false === $wpdb->query( $update_sql ) ) {
						$after_action_result  = false;
						$after_action_message = get_debuge_error( 'Error during updating dates type in BD', __FILE__, __LINE__ );

						return array( $after_action_result, $after_action_message, $db_form_data_new );
					}
				}

				$booking_resources_arr = wpbc_ajx_get_all_booking_resources_arr();
				$after_action_result  = true;
				$after_action_message = sprintf( __( 'Booking %s has been changed booking resource from %s to %s' )
												, '<strong>[ID=' . $booking_id . ']</strong>'
												, '<strong>' . apply_bk_filter( 'wpdev_check_for_active_language', $booking_resources_arr[ $resource_id_old ]['title'] ) . '</strong>'
												, '<strong>' . apply_bk_filter( 'wpdev_check_for_active_language', $booking_resources_arr[ $resource_id ]['title'] ) . '</strong>'
											);

				// Everything Cool :) - booking resource changed
			}

			return array( $after_action_result, $after_action_message , $db_form_data_new );
		}

			/**
			 * Get booking dates (bookings obj) of selected booking resource - checking if some dates there is booked or not
			 *
			 * @param $old_resource_dates_array		array  of OBJ with  ..->booking_date
			 * @param int $resource_id				Resource ID,  where we get dates.
			 *
			 * @return array		array  of booking OBJ with  ..->booking_date
			 */
			function wpbc_get_booking_dates_in_resource_for_folowing_dates( $old_resource_dates_array , $resource_id ){

				global $wpdb;

				$dates_sql_between   = '';
				$check_in_dates_arr  = array();
				$check_out_dates_arr = array();

				if ( 'On' === get_bk_option( 'booking_recurrent_time' ) ) {    // If we are using  recurrent time slots ?

					foreach ( $old_resource_dates_array as $k => $v ) {

						if ( ':02' == substr( $v->booking_date, - 3 ) ) {
							$check_out_dates_arr[] = $v->booking_date;
						}
						if ( ':01' == substr( $v->booking_date, - 3 ) ) {
							$check_in_dates_arr[] = $v->booking_date;
						}
					}

					if ( count( $check_out_dates_arr ) == count( $check_in_dates_arr ) ) {
						$dates_sql_between_arr = array();
						foreach ( $check_in_dates_arr as $k => $v ) {

							$dates_sql_between_arr [] = ' ( dt.booking_date BETWEEN "' . $check_in_dates_arr[ $k ] . '" AND "' . $check_out_dates_arr[ $k ] . '" ) ';
						}
						$dates_sql_between = implode( 'OR', $dates_sql_between_arr );

						//TODO: remove (),  if only 1 element in array

					} else {
						// ERROR :: number of check in/dates does not equal  to  number of check  out dates.
						$after_action_result  = false;
						$after_action_message = '<strong>Error</strong>. Number of check in times of booking dates does not equal to number of check out days.'
												. '<br>Check  in dates: ' . implode( ', ', $check_in_dates_arr )
												. '<br>Check out dates: ' . implode( ', ', $check_out_dates_arr );

						return array( $after_action_result, $after_action_message, '' );
					}
				}

				if ( '' == $dates_sql_between ) {

					$temp_check_in  = $old_resource_dates_array[0]->booking_date;
					$temp_check_out = $old_resource_dates_array[ ( count( $old_resource_dates_array ) - 1 ) ]->booking_date;

					if ( ':02' != substr( $temp_check_out, - 3 ) ) {
						$temp_check_out = date( 'Y-m-d H:i:s', strtotime( '+1 day -1 second', strtotime( $temp_check_out ) ) );
					}

					$dates_sql_between .= ' dt.booking_date BETWEEN "' . $temp_check_in . '" AND "' . $temp_check_out . '" ';
				}


				$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}booking as bk
												 INNER JOIN {$wpdb->prefix}bookingdates as dt
												 ON    bk.booking_id = dt.booking_id
										  WHERE  bk.trash != 1 AND bk.booking_type = %d", $resource_id );
				$sql .= " 						 AND ( {$dates_sql_between} ) ";

				// In BL version its does not check for booking belonging to several booking resources
				if ( class_exists( 'wpdev_bk_biz_l' ) ) {
					$sql .= " OR  bk.booking_id IN ( "
							. " SELECT DISTINCT booking_id FROM {$wpdb->prefix}bookingdates as dtt 
									WHERE  dtt.type_id = {$resource_id} "
							//."AND DATE(dt.booking_date) IN ( {$dates_string} )"
							. " AND ( " . str_replace( 'dt.', 'dtt.', $dates_sql_between ) . " ) "
							. ") ";
				}
				$sql .= "   ORDER BY bk.booking_id DESC, dt.booking_date ASC ";

				$exist_dates_results = $wpdb->get_results( $sql );

				// We have found only  intersected dates. Now we need to get all  dates from such  bookings,  for having correct  "Start  and end time times for the booking"
				// and does not add some "start  time at the begining of the day inside of wpbc_check_dates_intersections( ) function
				if ( count( $exist_dates_results ) > 0 ) {

					// Get ID of all  bookings that  inside of this interval
					$my_booking_id_arr = [];
					foreach ( $exist_dates_results as $key => $booking_obj ) {
						$my_booking_id_arr[] = $booking_obj->booking_id;
					}
					// Get all dates of such  bookings (not only  intersected!
					$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}booking as bk
													 INNER JOIN {$wpdb->prefix}bookingdates as dt
													 ON   bk.booking_id = dt.booking_id 
											  WHERE  bk.booking_id IN (" . implode( ',', $my_booking_id_arr ) . ") AND bk.trash != 1 AND bk.booking_type = %d", $resource_id );
					$sql .= "   ORDER BY bk.booking_id DESC, dt.booking_date ASC ";

					$exist_dates_results = $wpdb->get_results( $sql );
				}

				return $exist_dates_results;
			}

			/**
			 * Update "Content of booking fields data" from  old booking resource id SUFFIX to  new booking Resource ID
			 *
			 * @param string $db_form_data_old
			 * @param int $resource_id_old
			 * @param int $resource_id_new
			 *
			 * @return void
			 */
			function wpbc_update_resource_id_in_dbformatted_booking_data( $db_form_data_old, $resource_id_old, $resource_id_new ) {

				$db_form_data_new = array();

				$form_data_arr = explode( '~', $db_form_data_old );
				$fields_count  = count( $form_data_arr );

				for ( $i = 0; $i < $fields_count; $i ++ ) {

					list( $type, $element_name, $value ) = explode( '^', $form_data_arr[ $i ] );

					if ( substr( $element_name, - 2 ) == '[]' ) {
						$element_name = str_replace( '[]', '', $element_name );
					}

					$element_name = substr( $element_name, 0, - 1 * strlen( $resource_id_old ) ) . $resource_id_new;  	// Change resource ID in field

					$db_form_data_new[] = $type . '^' . $element_name . '^' . $value;
				}

				return implode( '~', $db_form_data_new );
			}

	/**
	 * Action: Duplicate Booking into other Resource
	 *
	 * @param $booking_id_arr			array or int	of booking ID
	 * @param $selected_resource_id	 	int				of booking resource
	 * @param $params					array			array of parameters: 		array(	'user_id' => 1 )
	 *
	 * @return array
	 *
	 * Example:
	 */
	function wpbc_booking_do_action__duplicate_booking_to_other_resource( $booking_id_arr, $selected_resource_id, $params ) {

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_id_csd = join( ',',  $booking_id_arr  );
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );

		// ID of booking resource ------------------------------------------------------------------------------------------
		$selected_resource_id = intval($selected_resource_id);

		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_id_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result   = true;

			$after_action_message = ( ( false === strpos( $booking_id_csd, ',' ) )
										? sprintf( __( 'Booking has been %s duplicated %s', 'booking' ), '<strong>', '</strong>' )
										: sprintf( __( 'Bookings have been %s duplicated %s', 'booking' ), '<strong>', '</strong>' )
									)
									. ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id_csd . '</strong> )</span>';


			$work_booking_id_arr = explode( ',', $booking_id_csd );

			foreach ( $work_booking_id_arr as $selected_booking_id ) {

				//    SQL    ---------------------------------------------------------------------------------------------------
				list( $after_action_result, $after_action_message, $formdata_new ) = wpbc__sql__duplicate_booking_to_other_resource_for_booking( $selected_booking_id, $selected_resource_id, $params );

				if ( $after_action_result ) {
					// LOG ---------------------------------------------------------------------------------------------------------
					$curr_user = get_user_by( 'id', (int) $params['user_id'] );
					wpbc_db__add_log_info( 		explode( ',', $selected_booking_id ),
											__( 'Booking resource changed by:', 'booking' )
											. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
									 );

					// Just  action  hook  for some other addons
					do_action( 'wpbc_booking_action__duplicate_booking_to_other_resource', $selected_booking_id, $selected_resource_id );

					// Emails ------------------------------------------------------------------------------------------------------
					if ( ! empty( $is_send_emeils ) ) {
						// 	We are sending emails about the new booking,  if we have created it.
						//	wpbc_send_email_modified( $selected_booking_id, $selected_resource_id, $formdata_new );
					}
				}
			}
		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);

	}

		/**
		 * Duplicate booking in another resource -- SQL manipulation
		 * @param $selected_booking_id
		 * @param $selected_resource_id
		 *
		 * @return array
		 */
		function wpbc__sql__duplicate_booking_to_other_resource_for_booking( $selected_booking_id, $selected_resource_id, $params ){
			global $wpdb;

			$booking_id   = intval( $selected_booking_id );
			$resource_id  = intval( $selected_resource_id );
			$db_form_data_new = '';
			$booking_resources_arr = wpbc_ajx_get_all_booking_resources_arr();

			$is_send_emails = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 1.Get dates of SOURCE booking
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if ( 1 ) {
				// 1.1.Get booking data of SOURCE booking
				$sql              = $wpdb->prepare( "SELECT * FROM  {$wpdb->prefix}booking as bk WHERE booking_id = %d ", $booking_id );
				$res              = $wpdb->get_row( $sql );
				$db_form_data_old = $res->form;
				$resource_id_old  = $res->booking_type;

				// 1.2. Get dates of SOURCE booking
				$sql                      = $wpdb->prepare( "SELECT * FROM  {$wpdb->prefix}bookingdates as dt WHERE booking_id = %d ORDER BY booking_date ASC ", $booking_id );
				$old_resource_dates_array = $wpdb->get_results( $sql );
			}

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// 2. Get bookings of selected booking resource - checking if some dates there is booked or not
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$exist_dates_results = wpbc_get_booking_dates_in_resource_for_folowing_dates( $old_resource_dates_array , $resource_id );

			if ( ( count( $exist_dates_results ) == 3 ) && ( false === $exist_dates_results[0] ) ){
				// // ERROR :: number of check in/dates does not equal  to  number of check  out dates.
				return $exist_dates_results;
			}

			if ( get_bk_option('booking_change_resource_skip_checking') === 'On' ) {									//FixIn: 8.4.5.4
				$is_date_time_booked = false;
			} else {
				$is_date_time_booked = wpbc_check_dates_intersections( $old_resource_dates_array, $exist_dates_results );
			}

			if (  $is_date_time_booked ) {

				$after_action_result  = false;
				$after_action_message = '<strong>' . __( 'Warning', 'booking' ) . '!</strong> '
										. sprintf( __( 'Booking %s has not been duplicated in booking resource %s. Current dates are already booked there.' )
														, '<strong style="font-size:0.9em;">[ID=' . $booking_id . ']</strong>'
														, '<strong>' . apply_bk_filter( 'wpdev_check_for_active_language', $booking_resources_arr[ $resource_id ]['title'] ) . '</strong>'
											   );

			} else {		// Possible to change

				$db_form_data_new = wpbc_update_resource_id_in_dbformatted_booking_data( $db_form_data_old, $resource_id_old, $resource_id );

				$wpdev_active_locale = isset( $_REQUEST['wpbc_ajx_locale'] ) ?  esc_js( $_REQUEST['wpbc_ajx_locale'] ) : 'en_US';


				// Change dates from DateObj( $selected_date->booking_date = '2015-10-17' ) 	to    array ( '17.10.2015' )
				$my_dates_for_sql = array();
				foreach ($old_resource_dates_array as $selected_date) {
					$selected_date = explode( '-', $selected_date->booking_date );
					$my_dates_for_sql[]  = sprintf( "%02d.%02d.%04d", $selected_date[2], $selected_date[1], $selected_date[0] );
				}
				$my_dates_for_sql = implode( ', ', $my_dates_for_sql );

				// Create a new booking
				$request_save_params = array(
											 'resource_id'         => $resource_id,					// 2
											 'dates_ddmmyy_csv'    => $my_dates_for_sql,			// '04.10.2023, 05.10.2023, 06.10.2023'
											 'form_data'           => $db_form_data_new,			// 'text^cost_hint2^150.00฿~select-multiple^rangetime2[]^14:00...'
											 'booking_hash'        => '',							// 'sdfsf34534rf'
											 'custom_form'         => '',							// 'custom_form_name'
											 'is_emails_send'       => $is_send_emails,				// 0 | 1
										 	 'is_show_payment_form' => 0							// 0 | 1
												// 'request_uri'          	=> $_SERVER['HTTP_REFERER']
												// 'user_id' 				=> wpbc_get_current_user_id()
												// 'sync_gid' 				=> ''
												// 'is_approve_booking'   	=> 0
										);
				$booking_save_arr = wpbc_booking_save( $request_save_params );

				if ( 'ok' === $booking_save_arr['ajx_data']['status'] ) {												// Everything Cool :) - booking has been duplicated

					$booking_id_new = $booking_save_arr['booking_id'];
					$after_action_result  = true;
					$after_action_message = sprintf( __( 'Booking %s has been duplicated in booking resource %s. New booking %s.' )
														, '<strong style="font-size:0.9em;">[ID=' . $booking_id . ']</strong>'
														, '<strong>' . apply_bk_filter( 'wpdev_check_for_active_language', $booking_resources_arr[ $resource_id ]['title'] ) . '</strong>'
														, '<strong style="font-size:0.9em;">[ID=' . $booking_id_new . ']</strong>'
											   );
				} else {																								// Error
					$after_action_result  = false;
					$after_action_message = $booking_save_arr['ajx_data']['ajx_after_action_message'];
				}
			}

			return array( $after_action_result, $after_action_message , $db_form_data_new );
		}

	/**
	 * Action: Set payment status for the booking
	 *
	 * @param $booking_id_arr			array or int	of booking ID
	 * @param $selected_payment_status	string			payment status of booking
	 * @param $params					array			array of parameters: 		array(	'user_id' => 1 )
	 *
	 * @return array
	 *
	 * Example:
	 */
	function wpbc_booking_do_action__set_payment_status( $booking_id_arr, $selected_payment_status, $params ) {

		global $wpdb;

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_id_csd = join( ',',  $booking_id_arr  );
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );


		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_id_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result  = true;
			$after_action_message = '';

			$work_booking_id_arr = explode( ',', $booking_id_csd );

			foreach ( $work_booking_id_arr as $selected_booking_id ) {

				//    SQL    ---------------------------------------------------------------------------------------------------
				$sql       = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}booking as bk WHERE bk.booking_id= %d ", $selected_booking_id );
				$result_bk = $wpdb->get_results( $sql );

				$old_pay_status = '';

				if ( ( 0 == count( $result_bk ) ) ) {
					// Error
					$after_action_result = false;
					$after_action_message .= sprintf(	 __( 'There is no booking %s', 'booking' )
														 , ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id_csd . '</strong> )</span>'
													);
				} else {
					$old_pay_status = $result_bk[0]->pay_status;
				}

				$update_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.pay_status= %s WHERE bk.booking_id= %d ", $selected_payment_status, $selected_booking_id );
				if ( false === $wpdb->query( $update_sql ) ) {
					// Error
					$after_action_result = false;
					$after_action_message .= 'Error during updating to DB. File:' . __FILE__ . ' on line: ' . __LINE__;
				} else {
					// Success
					$after_action_message .= sprintf(  __( 'Payment status for Booking %s has been updated from %s to %s', 'booking' )
														, ' <span style="font-size:0.9em;">( ID = <strong>' . $selected_booking_id . '</strong> )</span>'
														, '<strong>"' . wpbc__format__get_payment_status_title( $old_pay_status ) . '"</strong>'
														, '<strong>"' . wpbc__format__get_payment_status_title( $selected_payment_status ) . '"</strong>'
											 		) . '<br/>';
				}


				if ( $after_action_result ) {
					// LOG ---------------------------------------------------------------------------------------------------------
					$curr_user = get_user_by( 'id', (int) $params['user_id'] );
					wpbc_db__add_log_info( 		explode( ',', $selected_booking_id ),
											$after_action_message
											. '. ' . __( 'Changed by:', 'booking' )
											. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
									 );

					// Just  action  hook  for some other addons
					do_action( 'wpbc_booking_action__set_payment_status', $selected_booking_id, $selected_payment_status , $old_pay_status );

					// Emails ------------------------------------------------------------------------------------------------------
					if ( ! empty( $is_send_emeils ) ) {
						// 	We are sending emails about the new booking,  if we have created it.
						//	wpbc_send_email_modified( $selected_booking_id, $selected_resource_id, $formdata_new );
					}
				}
			}
		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);

	}

		/**
		 * Get title of the payment status
		 *
		 * @param $payment_status_key
		 *
		 * @return string
		 */
		function wpbc__format__get_payment_status_title( $payment_status_key ){

			$selected_payment_status_text = $payment_status_key;

			$select_box_options = get_payment_status_titles();
			$select_box_options = array_flip( $select_box_options );

			if ( ! empty( $select_box_options[ $payment_status_key ] ) ) {
				$selected_payment_status_text = $select_box_options[ $payment_status_key ];
			}

			return $selected_payment_status_text;
		}


	/**
	 * Action: Set Booking Cost
	 *
	 * @param $booking_id_arr			array or int	of booking ID
	 * @param $booking_cost				string			booking cost
	 * @param $params					array			array of parameters: 		array(	'user_id' => 1 )
	 *
	 * @return array
	 *
	 * Example:
	 */
	function wpbc_booking_do_action__set_booking_cost( $booking_id_arr, $booking_cost, $params ) {

		global $wpdb;

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_id_csd = join( ',',  $booking_id_arr  );
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );


		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

		// -----------------------------------------------------------------------------------------------------------------

		$booking_cost = str_replace(',', '.', $booking_cost);
		$booking_cost = floatval(  $booking_cost );


		if ( empty( $booking_id_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result  = true;
			$after_action_message = '';

			$work_booking_id_arr = explode( ',', $booking_id_csd );

			foreach ( $work_booking_id_arr as $selected_booking_id ) {

				//    SQL    ---------------------------------------------------------------------------------------------------
				$sql       = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}booking as bk WHERE bk.booking_id= %d ", $selected_booking_id );
				$result_bk = $wpdb->get_results( $sql );

				$old_booking_cost = '';

				if ( ( 0 == count( $result_bk ) ) ) {
					// Error
					$after_action_result = false;
					$after_action_message .= sprintf(	 __( 'There is no booking %s', 'booking' )
														 , ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id_csd . '</strong> )</span>'
													);
				} else {
					$old_booking_cost = $result_bk[0]->cost;
				}

				$update_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.cost = %f WHERE bk.booking_id = %d ", $booking_cost, $selected_booking_id );
				if ( false === $wpdb->query( $update_sql ) ) {
					// Error
					$after_action_result = false;
					$after_action_message .= 'Error during updating to DB. File:' . __FILE__ . ' on line: ' . __LINE__;
				} else {
					// Success
					$after_action_message .= sprintf(  __( 'Cost for Booking %s has been updated from %s to %s', 'booking' )
														, ' <span style="font-size:0.9em;">( ID = <strong>' . $selected_booking_id . '</strong> )</span>'
														, '<strong>"' . wpbc__format__get_payment_status_title( $old_booking_cost ) . '"</strong>'
														, '<strong>"' . wpbc__format__get_payment_status_title( $booking_cost ) . '"</strong>'
											 		) . '<br/>';
				}


				if ( $after_action_result ) {
					// LOG ---------------------------------------------------------------------------------------------------------
					$curr_user = get_user_by( 'id', (int) $params['user_id'] );
					wpbc_db__add_log_info( 		explode( ',', $selected_booking_id ),
											$after_action_message
											. '. ' . __( 'Changed by:', 'booking' )
											. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
									 );

					// Just  action  hook  for some other addons
					do_action( 'wpbc_booking_action__set_booking_cost', $selected_booking_id, $booking_cost , $old_booking_cost );

					// Emails ------------------------------------------------------------------------------------------------------
					if ( ! empty( $is_send_emeils ) ) {

						if ( get_bk_option( 'booking_send_email_on_cost_change' ) == 'On' ) {                                    //FixIn: 8.1.3.30
							$booking_data = apply_bk_filter( 'wpbc_get_booking_data', $selected_booking_id );
							wpbc_send_email_modified( $selected_booking_id, $booking_data['type'], $booking_data['form'] );
						}
					}

				}
			}
		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}


	/**
	 * Action: Send Payment request
	 *
	 * @param $booking_id_arr			array or int	of booking ID
	 * @param $booking_cost				string			booking cost
	 * @param $params					array			array of parameters: 		array(	'user_id' => 1 )
	 *
	 * @return array
	 */
	function wpbc_booking_do_action__send_payment_request($booking_id_arr, $reason_of_action, $params ) {
		global $wpdb;

		make_bk_action('check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		// Get ID list of bookings,  like  '1' or '3,7,9'	----------------------------------------------------------------
		if ( ! is_array( $booking_id_arr ) ) {
			$booking_id_arr = array( $booking_id_arr );
		}
		$booking_id_csd = join( ',',  $booking_id_arr  );
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );


		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

		// -----------------------------------------------------------------------------------------------------------------

		if ( empty( $booking_id_csd ) ) {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been selected. Please select one or more bookings.', 'booking' );

		} else {
			$after_action_result  = true;
			$after_action_message = '';

			$work_booking_id_arr = explode( ',', $booking_id_csd );

			foreach ( $work_booking_id_arr as $selected_booking_id ) {

				//    SQL    ---------------------------------------------------------------------------------------------------
				$sql       = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}booking as bk WHERE bk.booking_id= %d ", $selected_booking_id );
				$result_bk = $wpdb->get_results( $sql );

				$old_booking_cost = '';

				if ( ( 0 == count( $result_bk ) ) ) {
					// Error
					$after_action_result = false;
					$after_action_message .= sprintf(	 __( 'There is no booking %s', 'booking' )
														 , ' <span style="font-size:0.9em;">( ID = <strong>' . $booking_id_csd . '</strong> )</span>'
													);
				} else {
					$old_booking_cost = $result_bk[0]->cost;

					$is_email_payment_request_adress = get_bk_option( 'booking_is_email_payment_request_adress' );
					if ( 'Off' != $is_email_payment_request_adress ) {

						$reason_of_action = htmlspecialchars( str_replace( '\"', '"', $reason_of_action ) );
						$reason_of_action = str_replace( "\'", "'", $reason_of_action );

						foreach ( $result_bk as $res ) {

							$is_send = wpbc_send_email_payment_request( $res->booking_id, $res->booking_type, $res->form, $reason_of_action );

							if ( $is_send ) {
								// Update Payment request number
								$pay_request_numer = $res->pay_request + 1;
								$update_sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}booking AS bk SET bk.pay_request= %d WHERE bk.booking_id= %d ", $pay_request_numer, $res->booking_id );
								if ( false === $wpdb->query( $update_sql ) ) {
									// Error
									$after_action_result = false;
									$after_action_message .= 'Error during updating to DB. File:' . __FILE__ . ' on line: ' . __LINE__;
								} else {
									// Success
									$after_action_message .= sprintf(  __( 'Payment request for Booking %s has been sent. Cost for payment: %s', 'booking' )
																		, ' <span style="font-size:0.9em;">( ID = <strong>' . $selected_booking_id . '</strong> )</span>'
																		, '<strong>"' . wpbc__format__get_payment_status_title( $old_booking_cost ) . '"</strong>'
																	) . '<br/>';
								}
							}
						}
					}
				}

				if ( $after_action_result ) {
					// LOG ---------------------------------------------------------------------------------------------------------
					$curr_user = get_user_by( 'id', (int) $params['user_id'] );
					wpbc_db__add_log_info( 		explode( ',', $selected_booking_id ),
											$after_action_message
											. '. ' . __( 'Send by:', 'booking' )
											. ' ' . $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')'
									 );

					// Just  action  hook  for some other addons
					do_action( 'wpbc_booking_action__send_payment_request', $selected_booking_id, $reason_of_action , $old_booking_cost );

					// Emails ------------------------------------------------------------------------------------------------------
					/*
					if ( ! empty( $is_send_emeils ) ) {

						if ( get_bk_option( 'booking_send_email_on_cost_change' ) == 'On' ) {
							$booking_data = apply_bk_filter( 'wpbc_get_booking_data', $selected_booking_id );
							wpbc_send_email_modified( $selected_booking_id, $booking_data['type'], $booking_data['form'] );
						}
					}
					*/
				}
			}

		}

		return array(
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}


	function wpbc_booking_do_action__import_google_calendar( $request_params, $params ) {

		$return_array = array();

		$user_bk_id = (int) $params['user_id'];

		global $wpdb;

		$wpbc_Google_Calendar = new WPBC_Google_Calendar();

		$wpbc_Google_Calendar->setSilent();

		$wpbc_Google_Calendar->set_timezone( get_bk_option('booking_gcal_timezone') );

		$wpbc_Google_Calendar->set_events_from_with_array( array(
																	$request_params['booking_gcal_events_from'],
																	$request_params['booking_gcal_events_from_offset'],
																	$request_params['booking_gcal_events_from_offset_type']
																) );

		$wpbc_Google_Calendar->set_events_until_with_array( array(
																	$request_params['booking_gcal_events_until'],
																	$request_params['booking_gcal_events_until_offset'],
																	$request_params['booking_gcal_events_until_offset_type']
																) );

		$wpbc_Google_Calendar->set_events_max( $request_params['booking_gcal_events_max']  );

		if ( ( isset( $request_params['booking_gcal_resource'] ) ) && ( empty( $request_params['booking_gcal_resource'] ) ) ) {

			$wpbc_Google_Calendar->setUrl( get_bk_option( 'booking_gcal_feed') );
			$import_result = $wpbc_Google_Calendar->run();

		} else {

			if ( $request_params['booking_gcal_resource'] != 'all' ) {                             // One resource

				$wpbc_booking_resource_id = intval( $request_params['booking_gcal_resource'] );

				$wpbc_Google_Calendar->setResource( $wpbc_booking_resource_id );

				$wpbc_booking_resource_feed = get_booking_resource_attr( $wpbc_booking_resource_id );
				$wpbc_booking_resource_feed = $wpbc_booking_resource_feed->import;
				$wpbc_Google_Calendar->setUrl( $wpbc_booking_resource_feed );

				$import_result = $wpbc_Google_Calendar->run();
			} else {                                                                // All  resources

				$where = '';                                                        // Where for the different situation: BL and MU

				if ( class_exists( 'wpdev_bk_multiuser' ) ) {                       // MultiUser - only specific booking resources for specific Regular User in Admin panel.

					$is_user_super_admin = apply_bk_filter( 'is_user_super_admin', $user_bk_id );

					if ( ! $is_user_super_admin ) {
						$where .= ' WHERE users = ' . intval( $user_bk_id ) . ' ';
					}
				}

				$my_sql = "SELECT booking_type_id, import FROM {$wpdb->prefix}bookingtypes {$where}";

				$types_list = $wpdb->get_results( $my_sql );

				foreach ($types_list as $wpbc_booking_resource) {
					$wpbc_booking_resource_id   = $wpbc_booking_resource->booking_type_id;
					$wpbc_booking_resource_feed = $wpbc_booking_resource->import;
					if ( ( ! empty( $wpbc_booking_resource_feed ) ) && ( $wpbc_booking_resource_feed != null ) && ( $wpbc_booking_resource_feed != '/' ) ) {

						$wpbc_Google_Calendar->setUrl( $wpbc_booking_resource_feed );
						$wpbc_Google_Calendar->setResource( $wpbc_booking_resource_id );
						$import_result = $wpbc_Google_Calendar->run();
					}
				}
			}
		}


		if ( ( isset( $import_result ) ) && ( false != $import_result ) ) {
			$after_action_result  = true;
			$after_action_message = sprintf(  __( '%s new bookings have been imported', 'booking' )
											, ' <span style="font-size:1em;"> <strong>' . count( $wpbc_Google_Calendar->events ) . '</strong> </span>'
											) ;
			if ( 0 != count( $wpbc_Google_Calendar->events ) ) {
				$after_action_message .= '<br/><br/>' . __( 'The filter settings have been updated to reflect these imported bookings. The page will be reloaded.', 'booking' );
			}

			if ( ! empty( $wpbc_Google_Calendar->getErrorMessage() ) ) {
				$after_action_message .= '<div style="max-width:60rem;margin:2em 10px;">' . $wpbc_Google_Calendar->getErrorMessage() . '</div>';
				$return_array['after_action_result_delay'] = 100000000000000;
			}

		} else {
			$after_action_result  = false;
			$after_action_message = __( 'No bookings have been imported.', 'booking' )
									. '<br/><br/>'
			                        . sprintf( __( 'Please configure settings for import Google Calendar events', 'booking' ), '<b>', ',</b>' )
                           				. ' <a href="' . wpbc_get_settings_url() . '&tab=sync&subtab=gcal' . '">' . __('here' ,'booking') . '.</a>'
									. '<br/><br/>'
									. $wpbc_Google_Calendar->getErrorMessage();
			$return_array['after_action_result_delay'] = 100000000000000;
		}


		if ( 0 == count( $wpbc_Google_Calendar->events ) ) {
			$new_listing_params = false;
		} else {
			$new_listing_params = array(
				"sort"                             => "booking_id",
				"sort_type"                        => "DESC",
				"page_num"                         => 1,
				"page_items_count"                 => "50",
				"create_date"                      => "",
				"keyword"                          => "",
				"source"                           => "",
				"wh_booking_type"                  => array( "0" ),
				"wh_approved"                      => "",
				"wh_booking_date"                  => array( "3" ),
				"ui_wh_booking_date_radio"         => 0,
				"ui_wh_booking_date_next"          => 1,
				"ui_wh_booking_date_prior"         => 1,
				"ui_wh_booking_date_checkin"       => "",
				"ui_wh_booking_date_checkout"      => "",
				"wh_what_bookings"                 => "imported",
				"wh_modification_date"             => array( "1" ),
				"ui_wh_modification_date_prior"    => "1",
				"ui_wh_modification_date_checkin"  => "",
				"ui_wh_modification_date_checkout" => "",
				"wh_pay_status"                    => array( "all" ),
				"ui_wh_pay_status_radio"           => "",
				"ui_wh_pay_status_custom"          => "",
				"wh_cost"                          => "",
				"wh_cost2"                         => "",
				"wh_sort"                          => "booking_id__desc",
				"wh_trash"                         => "any",
				'reload_url_params'				   => htmlspecialchars_decode( esc_url( wpbc_get_bookings_url() . '&view_mode=vm_booking_listing' ) )
			);
		}


		$return_array['after_action_result']  = $after_action_result;
		$return_array['after_action_message'] = $after_action_message;
		$return_array['new_listing_params']   = $new_listing_params;

		return $return_array;
	}


	function wpbc_booking_do_action__export_csv( $request_params, $params ){

		global $wpdb;

		make_bk_action( 'check_multiuser_params_for_client_side_by_user_id', $params['user_id'] );

		$booking_id_csd = $request_params['booking_id'];
		$booking_id_csd = wpbc_clean_digit_or_csd( $booking_id_csd );
		if ( empty( $booking_id_csd ) ) {
			// Export all  bookings (no selected bookings was in the listing
		}

		$export_type = $request_params['export_type']; // csv_page | csv_all

		// Is send email  for this action ----------------------------------------------------------------------------------
		$is_send_emeils = wpbc_ajx__user_request_option__is_send_emails( $params['user_id']  );

		// -----------------------------------------------------------------------------------------------------------------

		if ( ! isset( $_POST['search_params'] ) || empty( $_POST['search_params'] ) ) {

			$after_action_result  = false;
			$after_action_message = __('Error', 'booking')  . '! ' . 'Search parameters for CSV generating are empty.';

		} else {


			$user_request = new WPBC_AJX__REQUEST( array(
													   'db_option_name'          => 'booking_listing_request_params',
													   'user_id'                 => wpbc_get_current_user_id(),
													   'request_rules_structure' => wpbc_ajx_get__request_params__names_default()
													)
							);
			$request_prefix = 'search_params';
			$request_params_for_listing = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 		// NOT Direct: 	$_REQUEST['search_params']['resource_id']



			// $data_arr = wpbc_ajx_get_booking_data_arr( $request_params_for_listing );

			$export_csv_url = wpbc_csv_get_url_export( $request_params_for_listing );

			$after_action_result  = true;
			$after_action_message = __('Processing' ,'booking') . '... ';
		}


		$wpbc_ajx_locale  = ( isset( $_REQUEST['wpbc_ajx_locale'] ) )  ? esc_js( $_REQUEST['wpbc_ajx_locale'] )  : 'en_US';

		return array(

					'export_csv_url' => $export_csv_url
										. '&export_type=' . $export_type
										. '&selected_id=' . $booking_id_csd
										. '&csv_export_separator='   . $request_params['csv_export_separator']
										. '&csv_export_skip_fields=' . $request_params['csv_export_skip_fields']
										. '&wpbc_ajx_locale=' . $wpbc_ajx_locale
										. '&wpbc_ajx_locale_reload=force' ,
					'after_action_result'  => $after_action_result,
					'after_action_message' => $after_action_message
				);
	}
// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc="  ==  Button Actions Templates for UI  ==  "  >


	/**
	 * Show Costs booking Test field
	 */
	function wpbc_for_booking_template__action_cost(){

		if ( ! class_exists('wpdev_bk_biz_s') ) {
			return  false;
		}

		$booking_action = 'set_booking_cost';
		$el_id = 'ui_btn_' . $booking_action . '{{data.parsed_fields.booking_id}}';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params_addon = array(
				  'type'        => 'span'
				, 'label'        => '{{{data[\'parsed_fields\'][\'currency_symbol\']}}}'
				, 'html'        => '{{{data[\'parsed_fields\'][\'currency_symbol\']}}}'//''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
				//, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_event', 'position' => 'right', 'icon_img' => '' )
				//, 'class'       => 'wpbc_ui_button inactive'
				, 'style'       => 'font-weight:600;font-size: 14px;line-height: 28px;'
				, 'attr'        => array()
			);

		  $param_text = array(
						'type'          => 'text'
						, 'id'          => $el_id . '_cost'
						, 'name'        => $el_id . '_cost'
						, 'label'       => ''
						, 'disabled'    => false
						, 'class'       => 'set_booking_cost_text_field'
						, 'style'       => 'width:5em;font-weight:600;'
						, 'placeholder' => '0'
						, 'attr'        => array()
						, 'is_escape_value' => false
						, 'value' => '{{data[\'parsed_fields\'][\'cost\']}}'
						, 'onfocus' => ''
						, 'onkeydown' => "jQuery('.ui__set_booking_cost__section_in_booking_{{data['parsed_fields']['booking_id']}}').show();"			// JavaScript code
		);

		?><div class="ui_element ui_nowrap"><?php
			//wpbc_flex_addon( $params_addon );
			wpbc_flex_label( $params_addon );
			wpbc_flex_text( $param_text );
		?></div><?php


		$params_button_save = array(
			'type'             => 'button' ,
			'title'            => __( 'Save cost', 'booking' ) . '&nbsp;&nbsp;',  												// Title of the button
			'hint'             => array( 'title' => __( 'Save Changes', 'booking' ), 'position' => 'top' ),  				// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "wpbc_ajx_booking__ui_click_save__set_booking_cost( {{data['parsed_fields']['booking_id']}}, this, '{$booking_action}', '{$el_id}' );" ,
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
			'icon' 			   => array( 'icon_font' => 'wpbc_icn_check _circle_outline', 'position'  => 'right', 'icon_img'  => '' ),
			'class'            => 'wpbc_ui_button_primary',  																// ''  | 'wpbc_ui_button_primary'
			'attr'             => array( 'id' => $el_id . '_save' )
		);

		$params_button_cancel = array(
			'type'             => 'button' ,
			'title'            => __( 'Cancel', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __('Close' ,'booking'), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "wpbc_ajx_booking__ui_click_close__set_booking_cost();" ,
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
			'icon' 			   => array( 'icon_font' => 'wpbc_icn_close _block', 'position'  => 'right', 'icon_img'  => '' ),
			'class'            => '',  																// ''  | 'wpbc_ui_button_primary'
			'attr'             => array( 'id' => $el_id . '_cancel' )
		);

		?><div class="ui_element ui_nowrap ui__set_booking_cost__section_in_booking ui__set_booking_cost__section_in_booking_{{data['parsed_fields']['booking_id']}}"><?php
			wpbc_flex_button( $params_button_save );
		?></div><?php
		?><div class="ui_element ui_nowrap ui__set_booking_cost__section_in_booking ui__set_booking_cost__section_in_booking_{{data['parsed_fields']['booking_id']}}"><?php
			wpbc_flex_button( $params_button_cancel );
		?></div><?php

	}

	/**
	 * Show Payment Request booking Button
	 */
	function wpbc_for_booking_template__action_payment_request(){

		if ( ! class_exists('wpdev_bk_biz_s') ) {
			return  false;
		}

		$booking_action = 'send_payment_request';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Send payment request to visitor', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			//'action'           => "js_func( {{data['parsed_fields']['booking_id']}} );",

			'action'           => "if ( 'function' === typeof( jQuery('#wpbc_modal__payment_request__section').wpbc_my_modal ) ) {
									jQuery('#wpbc_modal__payment_request__booking_id').val({{data['parsed_fields']['booking_id']}});
									jQuery('#wpbc_modal__payment_request__section').wpbc_my_modal('show');
								} else {
									alert( 'Warning! wpbc_my_modal module has not found. Please, recheck about any conflicts by deactivating other plugins.');
								}",

			'icon'             => array(
											'icon_font' => 'wpbc_icn_forward_to_inbox',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);


		?><div class="ui_element"><?php
			wpbc_flex_button( $params );
		?></div><?php
	}


	/**
	 * Show Payment Status booking Button
	 */
	function wpbc_for_booking_template__action_set_payment_status(){

		if ( ! class_exists('wpdev_bk_biz_s') ) {
			return  false;
		}

		$booking_action = 'set_payment_status';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Payment status', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			'action'           => "wpbc_ajx_booking__ui_click_show__set_payment_status( {{data['parsed_fields']['booking_id']}} );",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_sell',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);


		?><div class="ui_element"><?php
			wpbc_flex_button( $params );
		?></div><?php
	}

		/**
		 * Selectbox | Save | Close buttons for "Payment status" section
		 * @return false|void
		 */
		function wpbc_for_booking_template_section__set_payment_status(){

			if ( ! class_exists('wpdev_bk_biz_s') ) {
				return  false;
			}

			$booking_action = 'set_payment_status';

			$el_id = 'ui_btn_' . $booking_action . '{{data.parsed_fields.booking_id}}';

			if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
				return false;
			}

			$select_box_options = get_payment_status_titles();
			$select_box_options = array_flip( $select_box_options );
			$params_select = array(
						  'id'       => $el_id 												// HTML ID  of element
						, 'name'     => $el_id
		 				, 'label' =>  ''//__( 'Payment status', 'booking' )					// Label (optional)
						, 'style' => ''                     								// CSS of select element
						, 'class' => ''                     								// CSS Class of select element
						, 'multiple' => false
    					, 'attr' => array(
											'data-placeholder'   => __( 'Change status', 'booking' ),
											'ajx-selected-value' => '{{data.parsed_fields.pay_status}}'
										)
						, 'disabled'         => false
		 				, 'disabled_options' => array()     								// If some options disabled, then it has to list here
						, 'options' => $select_box_options
						//, 'value' => "{{data['parsed_fields']['pay_status']}}"//isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
						//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"			// JavaScript code
						//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"			// JavaScript code
					  );

			$params_button_save = array(
				'type'             => 'button' ,
				'title'            => __( 'Change status', 'booking' ) . '&nbsp;&nbsp;',  												// Title of the button
				'hint'             => array( 'title' => __( 'Save Changes', 'booking' ), 'position' => 'top' ),  				// Hint
				'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
				'action'           => "wpbc_ajx_booking__ui_click_save__set_payment_status( {{data['parsed_fields']['booking_id']}}, this, '{$booking_action}', '{$el_id}' );" ,
				'style'            => '',																						// Any CSS class here
				'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
				'icon' 			   => array( 'icon_font' => 'wpbc_icn_check _circle_outline', 'position'  => 'right', 'icon_img'  => '' ),
				'class'            => 'wpbc_ui_button_primary',  																// ''  | 'wpbc_ui_button_primary'
				'attr'             => array( 'id' => $el_id . '_save' )
			);

			$params_button_cancel = array(
				'type'             => 'button' ,
				'title'            => __( 'Cancel', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
				'hint'             => array( 'title' => __('Close' ,'booking'), 'position' => 'top' ),  	// Hint
				'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
				'action'           => "wpbc_ajx_booking__ui_click_close__set_payment_status();" ,
				'style'            => '',																						// Any CSS class here
				'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
				'icon' 			   => array( 'icon_font' => 'wpbc_icn_close _block', 'position'  => 'right', 'icon_img'  => '' ),
				'class'            => '',  																// ''  | 'wpbc_ui_button_primary'
				'attr'             => array( 'id' => $el_id . '_cancel' )
			);


			?><div class="ui_element" style="flex:100%;"></div>
			  <div    id="ui__set_payment_status__section_in_booking_{{data['parsed_fields']['booking_id']}}"
				   class="ui__set_payment_status__section_in_booking ui__under_actions_row__section_in_booking"
			 >
					<div class="wpbc_ajx_toolbar wpbc_buttons_row wpbc_buttons_row_for_booking highlight_action_section"> <div class="ui_container ui_container_small"> <div class="ui_group">

						<div class="ui_element">
							<?php wpbc_flex_select($params_select); ?>
						</div>

						<div class="ui_element" >
							<?php wpbc_flex_button($params_button_save); ?>
						</div>

						<div class="ui_element">
							<?php wpbc_flex_button($params_button_cancel); ?>
						</div>

					</div> </div> </div>
					<div class="clear"></div>

			  </div><?php

		}


	/**
	 * Show Edit booking Button
	 */
	function wpbc_for_booking_template__action_edit_booking(){

		//FixIn: 9.2.3.4
//		if ( ! class_exists('wpdev_bk_personal') ) {
//			return  false;
//		}

		$booking_action = 'edit_booking';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$edit_booking_url = 'admin.php?page=' . wpbc_get_new_booking_url( false, false)
							. '&booking_type={{data.parsed_fields.resource_id}}&booking_hash={{data.parsed_fields.hash}}&parent_res=1' ;
		$edit_booking_url .= ( 'Off' !== get_bk_option( 'booking_is_resource_no_update__during_editing' ) ) ? '&resource_no_update=1' : '';        //FixIn: 9.4.2.3

		$edit_booking_url .= '&booking_form={{data.parsed_fields.wpbc_custom_booking_form}}';							//FixIn: 9.4.3.12

		$edit_booking_url .= '&is_show_payment_form=Off';																//FixIn: 9.9.0.38

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Edit Booking', 'booking' ),
											'position' => 'top'
										),
			'link'             => $edit_booking_url,//'javascript:void(0)',
			'action'           => '',//"js_func( {{data['parsed_fields']['booking_id']}} );",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_draw',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);


		?><div class="ui_element <# if ( '' == data['parsed_fields']['hash']) { #>disabled<# } #>"><?php
			wpbc_flex_button( $params );
		?></div><?php
	}

	/**
	 * Show Change booking resource for booking Button
	 */
	function wpbc_for_booking_template__action_change_resource(){

		if ( ! class_exists('wpdev_bk_personal') ) {
			return  false;
		}

		$booking_action = 'change_booking_resource';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Change Resource', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			'action'           => "wpbc_ajx_booking__ui_click_show__change_resource( {{data['parsed_fields']['booking_id']}}, {{data['parsed_fields']['resource_id']}} );",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_shuffle',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);


		?><div class="ui_element"><?php
			wpbc_flex_button( $params );
		?></div><?php
	}

		/**
		 * Selectbox | Save | Close buttons for "Change booking resources" section
		 * @return false|void
		 */
		function wpbc_for_booking_template_section__change_booking_resource(){

			if ( ! class_exists('wpdev_bk_personal') ) {
				return  false;
			}

			$booking_action = 'change_booking_resource';

			$el_id = 'ui_btn_' . $booking_action;

			if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
				return false;
			}

			?><div class="ui_element" style="flex:100%;"></div>
			  <div    id="ui__change_booking_resource__section_in_booking_{{data['parsed_fields']['booking_id']}}"
				   class="ui__change_booking_resource__section_in_booking ui__under_actions_row__section_in_booking"
			 ></div><?php
		}


	/**
	 * Show Duplicate booking Button
	 */
	function wpbc_for_booking_template__action_duplicate_booking_to_other_resource(){

		if ( ! class_exists('wpdev_bk_personal') ) {
			return  false;
		}

		$booking_action = 'duplicate_booking_to_other_resource';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Duplicate Booking', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			'action'           => "wpbc_ajx_booking__ui_click_show__duplicate_booking( {{data['parsed_fields']['booking_id']}}, {{data['parsed_fields']['resource_id']}} );",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_content_copy',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);


		?><div class="ui_element"><?php
			wpbc_flex_button( $params );
		?></div><?php
	}

		/**
		 * Selectbox | Save | Close buttons for "Duplicate booking" section
		 * @return false|void
		 */
		function wpbc_for_booking_template_section__duplicate_booking_to_other_resource(){

			if ( ! class_exists('wpdev_bk_personal') ) {
				return  false;
			}

			$booking_action = 'duplicate_booking_to_other_resource';

			$el_id = 'ui_btn_' . $booking_action;

			if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
				return false;
			}

			?><div class="ui_element" style="flex:100%;"></div>
			  <div    id="ui__duplicate_booking_to_other_resource__section_in_booking_{{data['parsed_fields']['booking_id']}}"
				   class="ui__duplicate_booking_to_other_resource__section_in_booking ui__under_actions_row__section_in_booking"
			 ></div><?php
		}


	/**
	 * Show Print booking Button
	 */
	function wpbc_for_booking_template__action_print(){

		if ( ! class_exists('wpdev_bk_personal') ) {
			//return  false;
		}

		$booking_action = 'set_print';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Print', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			'action'           => "wpbc_print_dialog__show( {{data['parsed_fields']['booking_id']}} );",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_print',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);


		?><div class="ui_element"><?php
			wpbc_flex_button( $params );
		?></div><?php
	}

	/**
	 * Show Remark booking Button
	 */
	function wpbc_for_booking_template__action_remark(){

		if ( ! class_exists('wpdev_bk_personal') ) {
			return  false;
		}

		$booking_action = 'set_booking_note';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Edit Note', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			'action'           => "wpbc_ajx_booking__ui_click__remark( jQuery( this ) );",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_mode_comment',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => "{$booking_action}_button",
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array( 'id' => "{$booking_action}_button{{data.parsed_fields.booking_id}}" )
		);

		?><div class="ui_element"><?php
			wpbc_flex_button( $params );
		?></div><?php
	}

	function wpbc_for_booking_template_section__action_remark_textarea(){

		if ( ! class_exists('wpdev_bk_personal') ) {
			return  false;
		}

		$booking_action = 'set_booking_note';

		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params_textarea = array(
						  'id'       => "{$booking_action}_text_{{data.parsed_fields.booking_id}}" 		// HTML ID  of element
						, 'name'     => "{$booking_action}_text_{{data.parsed_fields.booking_id}}" 		// HTML ID  of element
						, 'label'    => ''
						, 'style'    => 'height: 8em; width: 100%;' 					// CSS of select element
						, 'class'    => "{$booking_action}_text" 					// CSS Class of select element
						, 'disabled' => false
						, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
						, 'rows' 		=> '3'
						, 'cols' 		=> '50'
						, 'placeholder' => ''
						, 'value'    => '{{{data.parsed_fields.remark}}}'												//FixIn: 9.4.4.1
						, 'is_escape_value' => true
						//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
						//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
		);

		$params_button_save = array(
			'type'             => 'button' ,
			'title'            => __( 'Save', 'booking' ) . '&nbsp;&nbsp;',  												// Title of the button
			'hint'             => array( 'title' => __( 'Save Changes', 'booking' ), 'position' => 'top' ),  				// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : {{data.parsed_fields.booking_id}},
																			'remark'       	   : jQuery( '#{$booking_action}_text_{{data.parsed_fields.booking_id}}' ).val(),																		
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
								   wpbc_button_enable_loading_icon( this ); " ,
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
			'icon' 			   => array( 'icon_font' => 'wpbc_icn_check _circle_outline', 'position'  => 'right', 'icon_img'  => '' ),
			'class'            => 'wpbc_ui_button_primary',  																// ''  | 'wpbc_ui_button_primary'
			'attr'             => array( 'id' => $el_id )
		);

		$params_button_cancel = array(
			'type'             => 'button' ,
			'title'            => __( 'Cancel', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __('Close' ,'booking'), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "wpbc_ajx_booking__ui_click__remark( jQuery( '#{$booking_action}_button{{data.parsed_fields.booking_id}}' ) );" ,
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
			'icon' 			   => array( 'icon_font' => 'wpbc_icn_close _block', 'position'  => 'right', 'icon_img'  => '' ),
			'class'            => '',  																// ''  | 'wpbc_ui_button_primary'
			'attr'             => array( 'id' => $el_id . '_cancel' )
		);

		//$user_id = ( isset( $_REQUEST['wpbc_ajx_user_id'] ) )  ?  intval( $_REQUEST['wpbc_ajx_user_id'] )  :  wpbc_get_current_user_id();
		//$is_expand_remarks  = wpbc_ajx__user_request_option__is_expand_remarks( $user_id );

		?><div class="ui_remark_section  is_expand_remarks_<?php //echo ($is_expand_remarks) ? 'on' : 'off'; ?>"
			   style="<# if ( 'Off' === wpbc_ajx_booking_listing.search_get_param('ui_usr__is_expand_remarks') ) { #>display: none;<# } #>"
		><?php

			wpbc_flex_textarea( $params_textarea );

			?><div class="ui_group"><?php

				?><div class="ui_element" ><?php
					wpbc_flex_button($params_button_save);
				?></div><?php

				?><div class="ui_element"><?php
					wpbc_flex_button($params_button_cancel);
				?></div><?php

			?></div><?php

		?></div><?php
	}

	/**
	 * Show Locale booking Button
	 */
	function wpbc_for_booking_template__action_locale(){

		$booking_action = 'set_booking_locale';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		?><span class="ui_element_locale"><?php

			$params = array(
				'type'             => 'button',
				'id' 			   => "button_locale_for_booking{{data['parsed_fields']['booking_id']}}",
				'title'            => '',
				'hint'             => array(
												'title'    => __( 'Change Language', 'booking' ),
												'position' => 'top'
											),
				'link'             => 'javascript:void(0)',
				'action'           => "jQuery('#locale_for_booking{{data[\'parsed_fields\'][\'booking_id\']}}').toggle();jQuery(this).hide();",
				'icon'             => array(
												'icon_font' => 'wpbc_icn_language',
												'position'  => 'left',
												'icon_img'  => ''
											),
				'class'            => 'set_booking_locale_button',
				'style'            => '',
				'mobile_show_text' => true,
				'attr' 			   => array()
			);

			?><div class="ui_element"><?php
				wpbc_flex_button( $params );
			?></div><?php


			$el_id = "locale_for_booking{{data.parsed_fields.booking_id}}";
			?><#
				var selected_locale_value = '';
				if (
					   ( 'undefined' !== typeof( data.parsed_fields.booking_options) )
					&& ( 'undefined' !== typeof( data.parsed_fields.booking_options.booking_meta_locale ) )
				){
					var selected_locale_value = data.parsed_fields.booking_options.booking_meta_locale;
				}
			#><?php

			$availbale_locales_in_system = get_available_languages();
			$select_box_options          = array();
			$select_box_options['']      = __( 'Default Locale', 'booking' );
			$select_box_options['en_US'] = 'English (United States)';

			foreach ( $availbale_locales_in_system as $locale ) {
				$select_box_options[$locale] = $locale;
			}

			$params_select = array(
								  'id'       => $el_id 												// HTML ID  of element
								, 'name'     => $el_id
								, 'label' => '' 	// __( 'Next Days', 'booking' )					// Label (optional)
								, 'style' => 'display: none;'                     								// CSS of select element
								, 'class' => 'set_booking_locale_selectbox'                     								// CSS Class of select element
								//, 'multiple' => true
								, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
								, 'disabled' => false
								, 'disabled_options' => array()     								// If some options disabled, then it has to list here
								, 'options' => $select_box_options
								//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
								//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
								, 'onchange' =>  "jQuery(this).hide();
												 var jButton = jQuery('#button_locale_for_booking{{data[\'parsed_fields\'][\'booking_id\']}}');
												 jButton.show();
												 wpbc_button_enable_loading_icon( jButton.get(0) ); "
												 . " wpbc_ajx_booking_ajax_action_request( { 
																							'booking_action' : '{$booking_action}', 
																							'booking_id'     : {{data[\'parsed_fields\'][\'booking_id\']}},  
																							'booking_meta_locale' : jQuery('#locale_for_booking{{data[\'parsed_fields\'][\'booking_id\']}} option:selected').val()	
																						} );"

							  );

			?><div class="ui_element"><?php
				wpbc_flex_select( $params_select );
			?></div><?php

		?></span><?php
	}

	/**
	 * Show "Add to Google Calendar" booking Button
	 */
	function wpbc_for_booking_template__action_add_google_calendar(){

		$booking_action = 'booking_add_google_calendar';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Add to Google Calendar', 'booking' ),
											'position' => 'top'
										),
			'link' 			   => "{{{data['parsed_fields']['google_calendar_link']}}}",		// "{{{encodeURIComponent(data['parsed_fields']['google_calendar_link'])}}}",
			'options' 		   => array( 'link' => 'no_decode' ),
			//'link'             => 'javascript:void(0)',
			//'action'           => "	wpbc_ajx_booking_ajax_action_request( {
			//																'booking_action' : '{$booking_action}',
			//																'booking_id'     : {{data['parsed_fields']['booking_id']}}
			//															} );
			//					  ",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_event',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array('target'=>'_blank')
		);

		?><div class="ui_element"><?php
			wpbc_flex_button( $params );
		?></div><?php
	}

	/**
	 * Show Trash booking Button
	 */
	function wpbc_for_booking_template__action_trash(){

		$booking_action = 'move_booking_to_trash';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'hint'             => array( 'title'    => __( 'Reject - move to trash', 'booking' ), 'position' => 'top' ),
			'title'            => '',
			'link'             => 'javascript:void(0)',
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to do this ?', 'booking' ) ) . "') ) {
										wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action' : '{$booking_action}', 
																			'booking_id'     : {{data['parsed_fields']['booking_id']}}  
																		} );
										wpbc_button_enable_loading_icon( this ); 
								   }",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_delete_outline',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);

		?><# if ( '0' == data['parsed_fields']['trash']) { #><?php
			?><div class="ui_element"><?php
				wpbc_flex_button( $params );
			?></div><?php
		?><# } #><?php
	}

	/**
	 * Show Restore from Trash booking Button
	 */
	function wpbc_for_booking_template__action_trash_restore(){

		$booking_action = 'restore_booking_from_trash';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'hint'             => array( 'title'    => __( 'Restore', 'booking' ), 'position' => 'top' ),
			'title'            => '',
			'link'             => 'javascript:void(0)',
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to do this ?', 'booking' ) ) . "') ) {
										wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action' : '{$booking_action}', 
																			'booking_id'     : {{data['parsed_fields']['booking_id']}}  
																		} );
										wpbc_button_enable_loading_icon( this ); 
								   }",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_rotate_left',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);

		?><# if ( '1' == data['parsed_fields']['trash']) { #><?php
			?><div class="ui_element"><?php
				wpbc_flex_button( $params );
			?></div><?php
		?><# } #><?php
	}

	/**
	 * Show Completely Delete booking Button
	 */
	function wpbc_for_booking_template__action_delete(){

		$booking_action = 'delete_booking_completely';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'hint'             => array( 'title'    => __( 'Completely Delete', 'booking' ), 'position' => 'top' ),
			'title'            => '',
			'link'             => 'javascript:void(0)',
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to delete this booking ?', 'booking' ) ) . "') ) {
										wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action' : '{$booking_action}', 
																			'booking_id'     : {{data['parsed_fields']['booking_id']}}  
																		} );
										wpbc_button_enable_loading_icon( this ); 
								   }",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_close',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);

		?><# if ( '1' == data['parsed_fields']['trash']) { #><?php
			?><div class="ui_element"><?php
				wpbc_flex_button( $params );
			?></div><?php
		?><# } #><?php
	}

	/**
	 * Show Approved booking Button
	 */
	function wpbc_for_booking_template__action_approved(){

		$booking_action = 'set_booking_approved';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Approve', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			'action'           => "wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action' : '{$booking_action}', 
																			'booking_id'     : {{data['parsed_fields']['booking_id']}}  
																		} );
								   wpbc_button_enable_loading_icon( this ); ",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_check_circle_outline',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);

		?><# if ( '0' == data['approved']) { #><?php
			?><div class="ui_element"><?php
				wpbc_flex_button( $params );
			?></div><?php
		?><# } #><?php
	}

	/**
	 * Show Pending booking Button
	 */
	function wpbc_for_booking_template__action_pending(){

		$booking_action = 'set_booking_pending';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'Pending', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to set booking as pending ?', 'booking' ) ) . "') ) {
										wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action' : '{$booking_action}', 
																			'booking_id'     : {{data['parsed_fields']['booking_id']}}  
																		} ); 
										wpbc_button_enable_loading_icon( this ); 
								  }",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_block',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => '',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);

		?><# if ( '1' == data['approved']) { #><?php
			?><div class="ui_element"><?php
				wpbc_flex_button( $params );
			?></div><?php
		?><# } #><?php
	}

	/**
	 * Show Read booking Button
	 */
	function wpbc_for_booking_template__action_read(){

		$booking_action = 'set_booking_as_read';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'             => 'button',
			'title'            => '',
			'hint'             => array(
											'title'    => __( 'New booking', 'booking' ),
											'position' => 'top'
										),
			'link'             => 'javascript:void(0)',
			'action'           => "	wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action' : '{$booking_action}', 
																			'booking_id'     : {{data['parsed_fields']['booking_id']}}  
																		} ); 
									wpbc_button_enable_loading_icon( this ); ",
			'icon'             => array(
											'icon_font' => 'wpbc_icn_visibility',
											'position'  => 'left',
											'icon_img'  => ''
										),
			'class'            => 'wpbc_is_new_button',
			'style'            => '',
			'mobile_show_text' => true,
			'attr'             => array()
		);

		?><# if ( '1' == data.parsed_fields.is_new ) { #><?php
			wpbc_flex_button( $params );
		?><# } #><?php
	}

// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc="  	==  Hidden Templates for sub action sections  ==  "  >

	/**
	 * Hidden Templates for - Booking Actions
	 *
	 * @param $page
	 *
	 * @return void
	 */
	function wpbc_for_booking_template__hidden_templates( $page ){

		// page=wpbc&view_mode=vm_booking_listing
		if ( 'wpbc-ajx_booking'  === $page ) {		// it's from >>	do_action( 'wpbc_hook_settings_page_footer', 'wpbc-ajx_booking' );
			?><div class="wpbc_hidden_templates"><?php

				wpbc_hidden_template__change_booking_resource();

				wpbc_hidden_template__duplicate_booking_to_other_resource();

			?></div><?php

				wpbc_hidden_template__content_for_modal_payment_request();

				wpbc_hidden_template__content_for_modal_import_google_calendar();

				wpbc_hidden_template__content_for_modal_export_csv();

				do_action( 'wpbc_hook_booking_template__hidden_templates' );		//FixIn: 9.2.3.6
		}
	}

	if ( 'vm_booking_listing' == wpbc_ajx_booking_listing__get_default_view_mode() ) {
		//if  ( strpos( $_SERVER['REQUEST_URI'], 'view_mode=vm_booking_listing' ) !== false ) {									// Load only  at  AJX_Bookings Settings Page
		add_action( 'wpbc_hook_settings_page_footer', 'wpbc_for_booking_template__hidden_templates' );
	}


		/**
		 * Hidden template  -- 	Change booking resources
		 *
		 * We need to  have such  hidden template for generating select box  for booking resource only  once, while we show Listing.
		 * It's called each  time from wpbc_ajx_define_templates__resource_manipulation() in wpbc_ajx_booking_show_listing()
		 * So we renew booking resource  list with  each  Ajax request  for Booking Listing.
		 *
		 * @return void
		 */
		function wpbc_hidden_template__change_booking_resource(){

			// Here will be composed template with  real HTML 	defined in "booking_listing.js" file in function wpbc_ajx_booking_show_listing( ...
			?><div id="wpbc_hidden_template__change_booking_resource"></div><?php

			// Template
			?><script type="text/html" id="tmpl-wpbc_ajx_change_booking_resource"><?php

				if ( ! class_exists('wpdev_bk_personal') ) {
					echo '</script>';
					return  false;
				}
				/*
				?><# console.log( ' == TEMPLATE PARAMS "wpbc_ajx_change_booking_resource" == ', data ); #><?php
				*/
				$booking_action = 'change_booking_resource';

				$el_id = 'ui_btn_' . $booking_action;

				if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
					echo '</script>';
					return false;
				}

				$params_button_save = array(
					'type'             => 'button' ,
					'title'            => __( 'Change', 'booking' ) . '&nbsp;&nbsp;',  												// Title of the button
					'hint'             => array( 'title' => __( 'Save Changes', 'booking' ), 'position' => 'top' ),  				// Hint
					'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
					'action'           => "wpbc_ajx_booking__ui_click_save__change_resource( this, '{$booking_action}', '{$el_id}' );" ,
					'style'            => '',																						// Any CSS class here
					'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
					'icon' 			   => array( 'icon_font' => 'wpbc_icn_check _circle_outline', 'position'  => 'right', 'icon_img'  => '' ),
					'class'            => 'wpbc_ui_button_primary',  																// ''  | 'wpbc_ui_button_primary'
					'attr'             => array( 'id' => $el_id )
				);

				$params_button_cancel = array(
					'type'             => 'button' ,
					'title'            => __( 'Cancel', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
					'hint'             => array( 'title' => __('Close' ,'booking'), 'position' => 'top' ),  	// Hint
					'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
					'action'           => "wpbc_ajx_booking__ui_click_close__change_resource();" ,
					'style'            => '',																						// Any CSS class here
					'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
					'icon' 			   => array( 'icon_font' => 'wpbc_icn_close _block', 'position'  => 'right', 'icon_img'  => '' ),
					'class'            => '',  																// ''  | 'wpbc_ui_button_primary'
					'attr'             => array( 'id' => $el_id . '_cancel' )
				);

				?><div id="change_booking_resource__section" class="highlight_action_section">
						<div class="wpbc_ajx_toolbar wpbc_buttons_row wpbc_buttons_row_for_booking"> <div class="ui_container ui_container_small"> <div class="ui_group">

							<input type="hidden" value="" id="change_booking_resource__booking_id" />

							<div class="ui_element">

								<select id="change_booking_resource__resource_select" name="change_booking_resource__resource_select" class="wpbc_ui_control wpbc_ui_select change_booking_resource_selectbox">
									<# 																					<?php if (0) { ?><script type="text/javascript"><?php  /* Hack  for showing  JavaScript syntax */ } ?>
									_.each( data.ajx_booking_resources, function ( p_resource, p_resource_id, p_data ){
										#><option value="{{p_resource.booking_type_id}}"
												  style="<#
															if( undefined != p_resource.parent ) {
																if( '0' == p_resource.parent ) {
																	#>font-weight:600;<#
																} else {
																	#>font-size:0.95em;padding-left:20px;<#
																}
															}
														#>"
										><#
											if( undefined != p_resource.parent ) {
												if( '0' != p_resource.parent ) {
													#>&nbsp;&nbsp;&nbsp;<#
												}
											}
										#>{{p_resource.title}}</option><#
									});																					<?php if (0) { ?></script><?php  /* Hack  for showing  JavaScript syntax */ } ?>
									#>
								</select>

							</div>

							<div class="ui_element" >
								<?php wpbc_flex_button($params_button_save); ?>
							</div>

							<div class="ui_element">
								<?php wpbc_flex_button($params_button_cancel); ?>
							</div>

						</div> </div> </div>
						<div class="clear"></div>
					</div>

			</script><?php
		}


		/**
		 * Hidden template  -- 	Duplicate booking to  other resources
		 *
		 * We need to  have such  hidden template for generating select box  for booking resource only  once, while we show Listing.
		 * It's called each  time from wpbc_ajx_define_templates__resource_manipulation() in wpbc_ajx_booking_show_listing()
		 * So we renew booking resource  list with  each  Ajax request  for Booking Listing.
		 *
		 * @return void
		 */
		function wpbc_hidden_template__duplicate_booking_to_other_resource(){

			// Here will be composed template with  real HTML 	defined in "booking_listing.js" file in function wpbc_ajx_booking_show_listing( ...
			?><div id="wpbc_hidden_template__duplicate_booking_to_other_resource"></div><?php

			// Template
			?><script type="text/html" id="tmpl-wpbc_ajx_duplicate_booking_to_other_resource"><?php

				if ( ! class_exists('wpdev_bk_personal') ) {
					echo '</script>';
					return  false;
				}
				/*
				?><# console.log( ' == TEMPLATE PARAMS "wpbc_ajx_duplicate_booking_to_other_resource" == ', data ); #><?php
				/**/
				$booking_action = 'duplicate_booking_to_other_resource';

				$el_id = 'ui_btn_' . $booking_action;

				if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
					echo '</script>';
					return false;
				}

				$params_button_save = array(
					'type'             => 'button' ,
					'title'            => __( 'Duplicate Booking', 'booking' ) . '&nbsp;&nbsp;',  												// Title of the button
					'hint'             => array( 'title' => __( 'Save Changes', 'booking' ), 'position' => 'top' ),  				// Hint
					'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
					'action'           => "wpbc_ajx_booking__ui_click_save__duplicate_booking( this, '{$booking_action}', '{$el_id}' );" ,
					'style'            => '',																						// Any CSS class here
					'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
					'icon' 			   => array( 'icon_font' => 'wpbc_icn_check _circle_outline', 'position'  => 'right', 'icon_img'  => '' ),
					'class'            => 'wpbc_ui_button_primary',  																// ''  | 'wpbc_ui_button_primary'
					'attr'             => array( 'id' => $el_id )
				);

				$params_button_cancel = array(
					'type'             => 'button' ,
					'title'            => __( 'Cancel', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
					'hint'             => array( 'title' => __('Close' ,'booking'), 'position' => 'top' ),  	// Hint
					'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
					'action'           => "wpbc_ajx_booking__ui_click_close__duplicate_booking();" ,
					'style'            => '',																						// Any CSS class here
					'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
					'icon' 			   => array( 'icon_font' => 'wpbc_icn_close _block', 'position'  => 'right', 'icon_img'  => '' ),
					'class'            => '',  																// ''  | 'wpbc_ui_button_primary'
					'attr'             => array( 'id' => $el_id . '_cancel' )
				);

				?><div id="duplicate_booking_to_other_resource__section" class="highlight_action_section">
						<div class="wpbc_ajx_toolbar wpbc_buttons_row wpbc_buttons_row_for_booking"> <div class="ui_container ui_container_small"> <div class="ui_group">

							<input type="hidden" value="" id="duplicate_booking_to_other_resource__booking_id" />

							<div class="ui_element">

								<select id="duplicate_booking_to_other_resource__resource_select" name="duplicate_booking_to_other_resource__resource_select" class="wpbc_ui_control wpbc_ui_select duplicate_booking_to_other_resource_selectbox">
									<#   																				<?php if (0) { ?><script><?php  /* Hack  for showing  JavaScript syntax */ } ?>
									_.each( data.ajx_booking_resources, function ( p_resource, p_resource_id, p_data ){
										#><option value="{{p_resource.booking_type_id}}"
												  style="<#
															if( undefined != p_resource.parent ) {
																if( '0' == p_resource.parent ) {
																	#>font-weight:600;<#
																} else {
																	#>font-size:0.95em;padding-left:20px;<#
																}
															}
														#>"
										><#
											if( undefined != p_resource.parent ) {
												if( '0' != p_resource.parent ) {
													#>&nbsp;&nbsp;&nbsp;<#
												}
											}
										#>{{p_resource.title}}</option><#
									}); 																				<?php if (0) { ?></script><?php  /* Hack  for showing  JavaScript syntax */ } ?>
									#>
								</select>

							</div>

							<div class="ui_element" >
								<?php wpbc_flex_button($params_button_save); ?>
							</div>

							<div class="ui_element">
								<?php wpbc_flex_button($params_button_cancel); ?>
							</div>

						</div> </div> </div>
						<div class="clear"></div>
					</div>

			</script><?php
		}


		/** Payment Request Layout - Modal Window structure */
		function wpbc_hidden_template__content_for_modal_payment_request() {

			if ( ! class_exists('wpdev_bk_biz_s') ) {
				return  false;
			}
			?><span class="wpdevelop">
				<div id="wpbc_modal__payment_request__section" class="modal wpbc_popup_modal" tabindex="-1" role="dialog">
				  <div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"><?php _e('Send payment request to customer' ,'booking'); ?></h4>
						</div>
						<div class="modal-body">
							<textarea id="wpbc_modal__payment_request__reason_of_action"  name="wpbc_modal__payment_request__reason_of_action" style="width:100%;" cols="87" rows="5"></textarea>
							<label class="help-block"><?php printf(__('Type your %sreason for payment%s request' ,'booking'),'<b>',',</b>');?></label>
							<input type="hidden" id="wpbc_modal__payment_request__booking_id" value="" />
						</div>
						<div class="modal-footer">
							<a id="wpbc_modal__payment_request__button_send" class="button button-primary"
							   onclick="javascript: wpbc_ajx_booking__ui_click__send_payment_request();" href="javascript:void(0);"
							  ><?php _e('Send Request' ,'booking'); ?></a>
							<a href="javascript:void(0)" class="button button-secondary" data-dismiss="modal"><?php _e('Close' ,'booking'); ?></a>
						</div>
					</div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			</span><?php
		}

		/** Payment Request Loyout - Modal Window structure */
		function wpbc_hidden_template__content_for_modal_import_google_calendar() {

			?><span class="wpdevelop"><?php

				$booking_gcal_feed = get_bk_option( 'booking_gcal_feed');
				$is_this_btn_disabled = false;

				if ( ( ! class_exists('wpdev_bk_personal') ) && ( $booking_gcal_feed == '' ) ) {

					$is_this_btn_disabled = true;
					$settigns_link        = wpbc_get_settings_url() . "&tab=sync&subtab=gcal";
				} else {
					$booking_gcal_events_from             = get_bk_option( 'booking_gcal_events_from' );
					$booking_gcal_events_from_offset      = get_bk_option( 'booking_gcal_events_from_offset' );
					$booking_gcal_events_from_offset_type = get_bk_option( 'booking_gcal_events_from_offset_type' );

					$booking_gcal_events_until             = get_bk_option( 'booking_gcal_events_until' );
					$booking_gcal_events_until_offset      = get_bk_option( 'booking_gcal_events_until_offset' );
					$booking_gcal_events_until_offset_type = get_bk_option( 'booking_gcal_events_until_offset_type' );

					$booking_gcal_events_max = get_bk_option( 'booking_gcal_events_max' );
					// $booking_gcal_timezone = get_bk_option( 'booking_gcal_timezone');


				}
				?><div id="wpbc_modal__import_google_calendar__section" class="modal wpbc_popup_modal wpbc_modal__import_google_calendar__section" tabindex="-1" role="dialog">
					  <div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title"><?php
									if ( $is_this_btn_disabled )    _e('Warning!' ,'booking');
									else                            _e('Retrieve Google Calendar Events ' ,'booking');
								?></h4>
							</div>
							<div class="modal-body">
								<?php if ($is_this_btn_disabled) { ?>
								   <label class="help-block" style="display:block;">
									   <?php printf(__('Please configure settings for import Google Calendar events' ,'booking'),'<b>',',</b>'); ?>
									   <a href="<?php echo $settigns_link; ?>"><?php _e('here' ,'booking');?></a>
								   </label>
								 <?php } else { ?>

									   <table class="visibility_gcal_feeds_settings form-table0 settings-table0 table"  >
										   <tbody>
										   <?php
											   if ( function_exists('wpbc_gcal_settings_content_field_selection_booking_resources') )
												   wpbc_gcal_settings_content_field_selection_booking_resources();
											   else {
												   ?><input type="hidden" name="wpbc_booking_resource" id="wpbc_booking_resource" value="" /><?php
											   }
											   wpbc_gcal_settings_content_field_from( $booking_gcal_events_from, $booking_gcal_events_from_offset, $booking_gcal_events_from_offset_type );
											   wpbc_gcal_settings_content_field_until( $booking_gcal_events_until, $booking_gcal_events_until_offset, $booking_gcal_events_until_offset_type );
											   wpbc_gcal_settings_content_field_max_feeds( $booking_gcal_events_max );
											   // wpbc_gcal_settings_content_field_timezone($booking_gcal_timezone);

										   ?>
										   </tbody>
									   </table>

								 <?php }  ?>
							</div>
							<div class="modal-footer" style="text-align:center;">
								<?php if ($is_this_btn_disabled) { ?>
								<a href="<?php  echo $settigns_link; ?>"
								   class="button button-primary"  style="float:none;" >
									<?php _e('Configure' ,'booking'); ?>
								</a>
								<?php } else { ?>
								<a href="javascript:void(0)" class="button button-primary"  style="float:none;"
								   id="wpbc_modal__import_google_calendar__button_send"
								   onclick="javascript:wpbc_ajx_booking__ui_click__import_google_calendar();jQuery('#wpbc_modal__import_google_calendar__section').wpbc_my_modal('hide');"
								   ><?php _e('Import Google Calendar Events' ,'booking'); ?></a>
								<?php } ?>
								<a href="javascript:void(0)" class="button" style="float:none;" data-dismiss="modal"><?php _e('Close' ,'booking'); ?></a>
						   </div>
						</div><!-- /.modal-content -->
					  </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<?php

			?></span><?php
		}


		/** Payment Request Layout - Modal Window structure */
		function wpbc_hidden_template__content_for_modal_export_csv() {

			if ( ! class_exists('wpdev_bk_personal') ) {
				return  false;
			}

			$user_id = ( isset( $_REQUEST['wpbc_ajx_user_id'] ) )  ?  intval( $_REQUEST['wpbc_ajx_user_id'] )  :  wpbc_get_current_user_id();
			$booking_csv_export_params = get_user_option( 'booking_csv_export_params', (int) $user_id );
			$booking_csv_export_params = ( ! empty( $booking_csv_export_params ) ) ? $booking_csv_export_params : array();
			$defaults= array(
				'export_type'            => 'csv_all',
				'selected_id'            => '',
				'csv_export_separator'   => 'semicolon',
				'csv_export_skip_fields' => ''
			);
			$export_params_arr   = wp_parse_args( $booking_csv_export_params, $defaults );

			?><span class="wpdevelop">
				<div id="wpbc_modal__export_csv__section" class="modal wpbc_popup_modal" tabindex="-1" role="dialog">
				  <div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title"><?php _e('CSV Export' ,'booking'); ?></h4>
						</div>
						<div class="modal-body">
							<table class="form-table"><tbody>
							<?php
								$default_options_values = wpbc_get_default_options();

								$field_options = array(
														  'semicolon' => '; - ' . __( 'semicolon', 'booking' )
														, 'comma' => ', - ' . __( 'comma', 'booking' )
													);
								$fields = array(
												  'type'        => 'select'
												, 'value'     => $export_params_arr['csv_export_separator']   //';'
												, 'title'       => __('CSV data separator', 'booking')
												, 'description' => ''//sprintf(__('Select separator of data for export bookings to CSV.' ,'booking'),'<b>','</b>')
												, 'options'     => $field_options
												, 'label'             => ''
												, 'disabled'          => false
												, 'disabled_options'  => array()
												, 'multiple'          => false
												, 'description_tag'   => 'p'
												, 'tr_class'          => ''
												, 'class'             => ''
												, 'css'               => ''
												, 'only_field'        => false
												, 'attr'              => array()
												//, 'value'             => ''
											);

								WPBC_Settings_API::field_select_row_static( 'wpbc_field_booking_csv_export_separator', $fields );


								$field_options = array(
														  'csv_page' => __( 'Current page', 'booking' )
														, 'csv_all'  => __( 'All pages', 'booking' )
													);
								$fields = array(
												  'type'        => 'select'
												, 'value'     => $export_params_arr['export_type']
												, 'title'       => __('Export pages', 'booking')
												, 'description' => ''//sprintf(__('Select exporting current page or all pages.' ,'booking'),'<b>','</b>')
												, 'options'     => $field_options
												, 'label'             => ''
												, 'disabled'          => false
												, 'disabled_options'  => array()
												, 'multiple'          => false
												, 'description_tag'   => 'p'
												, 'tr_class'          => ''
												, 'class'             => ''
												, 'css'               => ''
												, 'only_field'        => false
												, 'attr'              => array()
												//, 'value'             => ''
											);

								WPBC_Settings_API::field_select_row_static( 'wpbc_field_booking_csv_export_type', $fields );
							?>
							</tbody></table>
							<textarea id="wpbc_field_booking_csv_export_skip_fields"
									  name="wpbc_field_booking_csv_export_skip_fields"
									  style="width:100%;"
									  cols="87" rows="2"
									  placeholder="<?php echo 'trash,is_new,secondname'; ?>"
							><?php echo esc_textarea( $export_params_arr['csv_export_skip_fields'] ); ?></textarea>
							<label class="help-block"><?php printf(__('Enter field names separated by commas to %sskip the export%s' ,'booking'),'<b>','</b>');?></label>
							<input type="hidden" id="wpbc_modal__export_csv__booking_id" value="" />
						</div>
						<div class="modal-footer">
							<a id="wpbc_modal__export_csv__button_send" class="button button-primary"
							   onclick="javascript: wpbc_ajx_booking__ui_click__export_csv( {
																	'booking_action'       	: 'export_csv',
																	'ui_clicked_element_id'	: 'wpbc_modal__export_csv__button_send',
																	'export_type'			: jQuery('#wpbc_field_booking_csv_export_type option:selected').val(),
																	'csv_export_separator'	: jQuery('#wpbc_field_booking_csv_export_separator option:selected').val(),
																	'csv_export_skip_fields': jQuery('#wpbc_field_booking_csv_export_skip_fields').val()
															} );"  href="javascript:void(0);"
							  ><?php _e('Export' ,'booking'); ?></a>
							<a href="javascript:void(0)" class="button button-secondary" data-dismiss="modal"><?php _e('Close' ,'booking'); ?></a>
						</div>
					</div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
			</span><?php
		}

// </editor-fold>


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Support functions
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Catch some errors, if such errors exist during our Ajx request
 */
class WPBC_AJAX_ERROR_CATCHING{

	public $errors_arr;

	function __construct() {

		$this->errors_arr = array();

		// Catch Error messages
		add_action( 'wp_error_added', array( $this, 'wpbc_wp_error_added' ), 10, 4 );

		if ( ! defined( 'WPBC_AJAX_ERROR_CATCH' ) ) { define( 'WPBC_AJAX_ERROR_CATCH', true ); }		// Check somewhere to not show error messages:  if ( ( defined( 'WPBC_AJAX_ERROR_CATCH' ) ) && (  WPBC_AJAX_ERROR_CATCH ) ) { return  false; }
	}

	/**
	 * Catch Error messages
	 *
	 * @param $code
	 * @param $message
	 * @param $data
	 * @param $this_link
	 *
	 * @return void
	 */
	function wpbc_wp_error_added( $code, $message, $data, $this_link ){

		$this->errors_arr[] = array(
			'code'      => $code,
			'message'   => $message,
			'data'      => $data,
			'this_link' => $this_link
		) ;
	}

	/**
	 * Get summary  of all  errors
	 * @return string
	 */
	function get_error_messages(){

		$error_message = array();

		foreach ( $this->errors_arr as $error ) {
			$error_message[] = 'Error: ' . $error['code'] . '. ' . $error['message'];
		}
		$error_message = implode( '<br/>', $error_message );

		if ( ! empty( $error_message ) ) {
			$error_message =   '<div class="wpbc_ajx_errors">'
							   	.'<br/><strong>' . __( 'Some errors were encountered.', 'booking' ) . '</strong><br/>'
							   	. $error_message
							 . '</div>';
		}
		return $error_message;
	}
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Check what user can  do
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Check permission of this user on specific actions,  like approving or deleting of bookings
 *
 * @param string  	$action		like 'set_pnding'
 * @param int 		$user_id	like 1
 *
 * @return bool
 */
function wpbc_is_user_can( $action, $user_id = 0 ){

	if ( 0 == $user_id ) {
		$user_id = wpbc_get_current_user_id();
	}
	$user       = get_userdata( $user_id );
	$user_roles = (array) ( empty( $user ) ? array() : $user->roles );        // Standard WordPress Roles:  'administrator' | 'editor' | 'author' | 'contributor' | 'subscriber'


	// Get here list  of actions that  user can do
    switch ( $action ) {
        case 'set_booking_pending':
            break;
        case 'set_booking_approved':
            break;
		case 'move_booking_to_trash':
			break;
		case 'restore_booking_from_trash':
			break;
		case 'delete_booking_completely':
			// if ( ! in_array( 'administrator', $user_roles ) ) { return false; }
			break;
		case 'booking_add_google_calendar':
			break;
		case 'set_booking_locale':
			break;
		case 'set_booking_as_read':
			break;
		case 'set_booking_as_unread':
			break;
		case 'empty_trash':
			break;
		case 'set_booking_note':
			break;
		case 'edit_booking':
			break;
		case 'change_booking_resource':
			break;
		case 'duplicate_booking_to_other_resource':
			break;
		case 'set_payment_status':
			break;
		case 'set_booking_cost':
			break;
		case 'send_payment_request':
			break;
		case 'import_google_calendar':
			break;
		case 'export_csv':
			break;
        default:
           // Default
    }

	return true;
}