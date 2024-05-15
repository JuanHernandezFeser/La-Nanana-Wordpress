"use strict";

// ---------------------------------------------------------------------------------------------------------------------
//  A j a x    A d d    N e w    B o o k i n g
// ---------------------------------------------------------------------------------------------------------------------


/**
 * Submit new booking
 *
 * @param params   =     {
                                'resource_id'        : resource_id,
                                'dates_ddmmyy_csv'   : document.getElementById( 'date_booking' + resource_id ).value,
                                'formdata'           : formdata,
                                'booking_hash'       : my_booking_hash,
                                'custom_form'        : my_booking_form,

                                'captcha_chalange'   : captcha_chalange,
                                'captcha_user_input' : user_captcha,

                                'is_emails_send'     : is_send_emeils,
                                'active_locale'      : wpdev_active_locale
						}
 *
 */
function wpbc_ajx_booking__create( params ){

console.groupCollapsed( 'WPBC_AJX_BOOKING__CREATE' );
console.groupCollapsed( '== Before Ajax Send ==' );
console.log( params );
console.groupEnd();

	params = wpbc_captcha__simple__maybe_remove_in_ajx_params( params );

	// Start Ajax
	jQuery.post( wpbc_global1.wpbc_ajaxurl,
				{
					action          : 'WPBC_AJX_BOOKING__CREATE',
					wpbc_ajx_user_id: _wpbc.get_secure_param( 'user_id' ),
					nonce           : _wpbc.get_secure_param( 'nonce' ),
					wpbc_ajx_locale : _wpbc.get_secure_param( 'locale' ),

					calendar_request_params : params

					/**
					 *  Usually  params = { 'resource_id'        : resource_id,
					 *						'dates_ddmmyy_csv'   : document.getElementById( 'date_booking' + resource_id ).value,
					 *						'formdata'           : formdata,
					 *						'booking_hash'       : my_booking_hash,
					 *						'custom_form'        : my_booking_form,
					 *
					 *						'captcha_chalange'   : captcha_chalange,
					 *						'user_captcha'       : user_captcha,
					 *
					 *						'is_emails_send'     : is_send_emeils,
					 *						'active_locale'      : wpdev_active_locale
					 *				}
					 */
				},

				/**
				 * S u c c e s s
				 *
				 * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
				 * @param textStatus		-	'success'
				 * @param jqXHR				-	Object
				 */
				function ( response_data, textStatus, jqXHR ) {
console.log( ' == Response WPBC_AJX_BOOKING__CREATE == ' );
for ( var obj_key in response_data ){
	console.groupCollapsed( '==' + obj_key + '==' );
	console.log( ' : ' + obj_key + ' : ', response_data[ obj_key ] );
	console.groupEnd();
}
console.groupEnd();


					// <editor-fold     defaultstate="collapsed"     desc=" = Error Message! Server response with String.  ->  E_X_I_T  "  >
					// -------------------------------------------------------------------------------------------------
					// This section execute,  when server response with  String instead of Object -- Usually  it's because of mistake in code !
					// -------------------------------------------------------------------------------------------------
					if ( (typeof response_data !== 'object') || (response_data === null) ){

						var calendar_id = wpbc_get_resource_id__from_ajx_post_data_url( this.data );
						var jq_node = '#booking_form' + calendar_id;

						if ( '' == response_data ){
							response_data = '<strong>' + 'Error! Server respond with empty string!' + '</strong> ' ;
						}
						// Show Message
						wpbc_front_end__show_message( response_data , { 'type'     : 'error',
																		'show_here': {'jq_node': jq_node, 'where': 'after'},
																		'is_append': true,
																		'style'    : 'text-align:left;',
																		'delay'    : 0
																	} );
						// Enable Submit | Hide spin loader
						wpbc_booking_form__on_response__ui_elements_enable( calendar_id );
						return;
					}
					// </editor-fold>


					// <editor-fold     defaultstate="collapsed"     desc="  ==  This section execute,  when we have KNOWN errors from Booking Calendar.  ->  E_X_I_T  "  >
					// -------------------------------------------------------------------------------------------------
					// This section execute,  when we have KNOWN errors from Booking Calendar
					// -------------------------------------------------------------------------------------------------

					if ( 'ok' != response_data[ 'ajx_data' ][ 'status' ] ) {

						switch ( response_data[ 'ajx_data' ][ 'status_error' ] ){

							case 'captcha_simple_wrong':
								wpbc_captcha__simple__update( {
																'resource_id': response_data[ 'resource_id' ],
																'url'        : response_data[ 'ajx_data' ][ 'captcha__simple' ][ 'url' ],
																'challenge'  : response_data[ 'ajx_data' ][ 'captcha__simple' ][ 'challenge' ],
																'message'    : response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" )
															}
														);
								break;

							case 'resource_id_incorrect':																// Show Error Message - incorrect  booking resource ID during submit of booking.
								var message_id = wpbc_front_end__show_message( response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" ),
																{
																	'type' : ('undefined' !== typeof (response_data[ 'ajx_data' ][ 'ajx_after_action_message_status' ]))
																			? response_data[ 'ajx_data' ][ 'ajx_after_action_message_status' ] : 'warning',
																	'delay'    : 0,
																	'show_here': { 'where': 'after', 'jq_node': '#booking_form' + params[ 'resource_id' ] }
																} );
								break;

							case 'booking_can_not_save':																// We can not save booking, because dates are booked or can not save in same booking resource all the dates
								var message_id = wpbc_front_end__show_message( response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" ),
																{
																	'type' : ('undefined' !== typeof (response_data[ 'ajx_data' ][ 'ajx_after_action_message_status' ]))
																			? response_data[ 'ajx_data' ][ 'ajx_after_action_message_status' ] : 'warning',
																	'delay'    : 0,
																	'show_here': { 'where': 'after', 'jq_node': '#booking_form' + params[ 'resource_id' ] }
																} );

								// Enable Submit | Hide spin loader
								wpbc_booking_form__on_response__ui_elements_enable( response_data[ 'resource_id' ] );

								break;


							default:

								// <editor-fold     defaultstate="collapsed"                        desc=" = For debug only ? --  Show Message under the form = "  >
								// --------------------------------------------------------------------------------------------------------------------------------
								if (
										( 'undefined' !== typeof (response_data[ 'ajx_data' ][ 'ajx_after_action_message' ]) )
									 && ( '' != response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" ) )
								){

									var calendar_id = wpbc_get_resource_id__from_ajx_post_data_url( this.data );
									var jq_node = '#booking_form' + calendar_id;

									var ajx_after_booking_message = response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" );

									console.log( ajx_after_booking_message );

									/**
									 * // Show Message
										var ajx_after_action_message_id = wpbc_front_end__show_message( ajx_after_booking_message,
																	{
																		'type' : ('undefined' !== typeof (response_data[ 'ajx_data' ][ 'ajx_after_action_message_status' ]))
																				? response_data[ 'ajx_data' ][ 'ajx_after_action_message_status' ] : 'info',
																		'delay'    : 10000,
																		'show_here': {
																						'jq_node': jq_node,
																						'where'  : 'after'
																					 }
																	} );
									 */
								}
								// </editor-fold>
						}


						// -------------------------------------------------------------------------------------------------
						// Reactivate calendar again ?
						// -------------------------------------------------------------------------------------------------
						// Enable Submit | Hide spin loader
						wpbc_booking_form__on_response__ui_elements_enable( response_data[ 'resource_id' ] );

						// Unselect  dates
						wpbc_calendar__unselect_all_dates( response_data[ 'resource_id' ] );

						// 'resource_id'    => $params['resource_id'],
						// 'booking_hash'   => $booking_hash,
						// 'request_uri'    => $_SERVER['REQUEST_URI'],                                            // Is it the same as window.location.href or
						// 'custom_form'    => $params['custom_form'],                                             // Optional.
						// 'aggregate_resource_id_str' => implode( ',', $params['aggregate_resource_id_arr'] )     // Optional. Resource ID   from  aggregate parameter in shortcode.

						// Load new data in calendar.
						wpbc_calendar__load_data__ajx( {
											  'resource_id' : response_data[ 'resource_id' ]							// It's from response ...AJX_BOOKING__CREATE of initial sent resource_id
											, 'booking_hash': response_data[ 'ajx_cleaned_params' ]['booking_hash'] 	// ?? we can not use it,  because HASH chnaged in any  case!
											, 'request_uri' : response_data[ 'ajx_cleaned_params' ]['request_uri']
											, 'custom_form' : response_data[ 'ajx_cleaned_params' ]['custom_form']
																			// Aggregate booking resources,  if any ?
											, 'aggregate_resource_id_str' : _wpbc.booking__get_param_value( response_data[ 'resource_id' ], 'aggregate_resource_id_arr' ).join(',')

													} );
						// Exit
						return;
					}

					// </editor-fold>


/*
	// Show Calendar
	wpbc_calendar__loading__stop( response_data[ 'resource_id' ] );

	// -------------------------------------------------------------------------------------------------
	// Bookings - Dates
	_wpbc.bookings_in_calendar__set_dates(  response_data[ 'resource_id' ], response_data[ 'ajx_data' ]['dates']  );

	// Bookings - Child or only single booking resource in dates
	_wpbc.booking__set_param_value( response_data[ 'resource_id' ], 'resources_id_arr__in_dates', response_data[ 'ajx_data' ][ 'resources_id_arr__in_dates' ] );
	// -------------------------------------------------------------------------------------------------

	// Update calendar
	wpbc_calendar__update_look( response_data[ 'resource_id' ] );
*/

					// Hide spin loader
					wpbc_booking_form__spin_loader__hide( response_data[ 'resource_id' ] );

					// Hide booking form
					wpbc_booking_form__animated__hide( response_data[ 'resource_id' ] );

					// Show Confirmation | Payment section
					wpbc_show_thank_you_message_after_booking( response_data );

					setTimeout( function (){
						wpbc_do_scroll( '#wpbc_scroll_point_' + response_data[ 'resource_id' ], 10 );
					}, 500 );



				}
			  ).fail(
				  // <editor-fold     defaultstate="collapsed"                        desc=" = This section execute,  when  NONCE field was not passed or some error happened at  server! = "  >
				  function ( jqXHR, textStatus, errorThrown ) {    if ( window.console && window.console.log ){ console.log( 'Ajax_Error', jqXHR, textStatus, errorThrown ); }

					// -------------------------------------------------------------------------------------------------
					// This section execute,  when  NONCE field was not passed or some error happened at  server!
					// -------------------------------------------------------------------------------------------------

					// Get Content of Error Message
					var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown ;
					if ( jqXHR.status ){
						error_message += ' (<b>' + jqXHR.status + '</b>)';
						if (403 == jqXHR.status ){
							error_message += '<br> Probably nonce for this page has been expired. Please <a href="javascript:void(0)" onclick="javascript:location.reload();">reload the page</a>.';
							error_message += '<br> Otherwise, please check this <a style="font-weight: 600;" href="https://wpbookingcalendar.com/faq/request-do-not-pass-security-check/">troubleshooting instruction</a>.<br>'
						}
					}
					if ( jqXHR.responseText ){
						// Escape tags in Error message
						error_message += '<br><strong>Response</strong><div style="padding: 0 10px;margin: 0 0 10px;border-radius:3px; box-shadow:0px 0px 1px #a3a3a3;">' + jqXHR.responseText.replace(/&/g, "&amp;")
																 .replace(/</g, "&lt;")
																 .replace(/>/g, "&gt;")
																 .replace(/"/g, "&quot;")
																 .replace(/'/g, "&#39;")
										+'</div>';
					}
					error_message = error_message.replace( /\n/g, "<br />" );

					var calendar_id = wpbc_get_resource_id__from_ajx_post_data_url( this.data );
					var jq_node = '#booking_form' + calendar_id;

					// Show Message
					wpbc_front_end__show_message( error_message , { 'type'     : 'error',
																	'show_here': {'jq_node': jq_node, 'where': 'after'},
																	'is_append': true,
																	'style'    : 'text-align:left;',
																	'delay'    : 0
																} );
					// Enable Submit | Hide spin loader
					wpbc_booking_form__on_response__ui_elements_enable( calendar_id );
			  	 }
				 // </editor-fold>
			  )
	          // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
			  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
			  ;  // End Ajax

	return true;
}


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  CAPTCHA ==  "  >

	/**
	 * Update image in captcha and show warning message
	 *
	 * @param params
	 *
	 * Example of 'params' : {
	 *							'resource_id': response_data[ 'resource_id' ],
	 *							'url'        : response_data[ 'ajx_data' ][ 'captcha__simple' ][ 'url' ],
	 *							'challenge'  : response_data[ 'ajx_data' ][ 'captcha__simple' ][ 'challenge' ],
	 *							'message'    : response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" )
	 *						}
	 */
	function wpbc_captcha__simple__update( params ){

		document.getElementById( 'captcha_input' + params[ 'resource_id' ] ).value = '';
		document.getElementById( 'captcha_img' + params[ 'resource_id' ] ).src = params[ 'url' ];
		document.getElementById( 'wpdev_captcha_challenge_' + params[ 'resource_id' ] ).value = params[ 'challenge' ];

		// Show warning 		After CAPTCHA Img
		var message_id = wpbc_front_end__show_message__warning( '#captcha_input' + params[ 'resource_id' ] + ' + img', params[ 'message' ] );

		// Animate
		jQuery( '#' + message_id + ', ' + '#captcha_input' + params[ 'resource_id' ] ).fadeOut( 350 ).fadeIn( 300 ).fadeOut( 350 ).fadeIn( 400 ).animate( {opacity: 1}, 4000 );
		// Focus text  field
		jQuery( '#captcha_input' + params[ 'resource_id' ] ).trigger( 'focus' );    									//FixIn: 8.7.11.12


		// Enable Submit | Hide spin loader
		wpbc_booking_form__on_response__ui_elements_enable( params[ 'resource_id' ] );
	}


	/**
	 * If the captcha elements not exist  in the booking form,  then  remove parameters relative captcha
	 * @param params
	 * @returns obj
	 */
	function wpbc_captcha__simple__maybe_remove_in_ajx_params( params ){

		if ( ! wpbc_captcha__simple__is_exist_in_form( params[ 'resource_id' ] ) ){
			delete params[ 'captcha_chalange' ];
			delete params[ 'captcha_user_input' ];
		}
		return params;
	}


	/**
	 * Check if CAPTCHA exist in the booking form
	 * @param resource_id
	 * @returns {boolean}
	 */
	function wpbc_captcha__simple__is_exist_in_form( resource_id ){

		return (
						(0 !== jQuery( '#wpdev_captcha_challenge_' + resource_id ).length)
					 || (0 !== jQuery( '#captcha_input' + resource_id ).length)
				);
	}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Send Button | Form Spin Loader  ==  "  >

	/**
	 * Disable Send button  |  Show Spin Loader
	 *
	 * @param resource_id
	 */
	function wpbc_booking_form__on_submit__ui_elements_disable( resource_id ){

		// Disable Submit
		wpbc_booking_form__send_button__disable( resource_id );

		// Show Spin loader in booking form
		wpbc_booking_form__spin_loader__show( resource_id );
	}

	/**
	 * Enable Send button  |   Hide Spin Loader
	 *
	 * @param resource_id
	 */
	function wpbc_booking_form__on_response__ui_elements_enable(resource_id){

		// Enable Submit
		wpbc_booking_form__send_button__enable( resource_id );

		// Hide Spin loader in booking form
		wpbc_booking_form__spin_loader__hide( resource_id );
	}

		/**
		 * Enable Submit button
		 * @param resource_id
		 */
		function wpbc_booking_form__send_button__enable( resource_id ){

			// Activate Send button
			jQuery( '#booking_form_div' + resource_id + ' input[type=button]' ).prop( "disabled", false );
			jQuery( '#booking_form_div' + resource_id + ' button' ).prop( "disabled", false );
		}

		/**
		 * Disable Submit button  and show  spin
		 *
		 * @param resource_id
		 */
		function wpbc_booking_form__send_button__disable( resource_id ){

			// Disable Send button
			jQuery( '#booking_form_div' + resource_id + ' input[type=button]' ).prop( "disabled", true );
			jQuery( '#booking_form_div' + resource_id + ' button' ).prop( "disabled", true );
		}

		/**
		 * Show booking form  Spin Loader
		 * @param resource_id
		 */
		function wpbc_booking_form__spin_loader__show( resource_id ){

			// Show Spin Loader
			jQuery( '#booking_form' + resource_id ).after(
				'<div id="wpbc_booking_form_spin_loader' + resource_id + '" class="wpbc_booking_form_spin_loader" style="position: relative;"><div class="wpbc_spins_loader_wrapper"><div class="wpbc_spins_loader_mini"></div></div></div>'
			);
		}

		/**
		 * Remove / Hide booking form  Spin Loader
		 * @param resource_id
		 */
		function wpbc_booking_form__spin_loader__hide( resource_id ){

			// Remove Spin Loader
			jQuery( '#wpbc_booking_form_spin_loader' + resource_id ).remove();
		}


		/**
		 * Hide booking form wth animation
		 *
		 * @param resource_id
		 */
		function wpbc_booking_form__animated__hide( resource_id ){

			// jQuery( '#booking_form' + resource_id ).slideUp(  1000
			// 												, function (){
			//
			// 														// if ( document.getElementById( 'gateway_payment_forms' + response_data[ 'resource_id' ] ) != null ){
			// 														// 	wpbc_do_scroll( '#submiting' + resource_id );
			// 														// } else
			// 														if ( jQuery( '#booking_form' + resource_id ).parent().find( '.submiting_content' ).length > 0 ){
			// 															//wpbc_do_scroll( '#booking_form' + resource_id + ' + .submiting_content' );
			//
			// 															 var hideTimeout = setTimeout(function () {
			// 																				  wpbc_do_scroll( jQuery( '#booking_form' + resource_id ).parent().find( '.submiting_content' ).get( 0 ) );
			// 																				}, 100);
			//
			// 														}
			// 												  }
			// 										);

			jQuery( '#booking_form' + resource_id ).hide();

			// var hideTimeout = setTimeout( function (){
			//
			// 	if ( jQuery( '#booking_form' + resource_id ).parent().find( '.submiting_content' ).length > 0 ){
			// 		var random_id = Math.floor( (Math.random() * 10000) + 1 );
			// 		jQuery( '#booking_form' + resource_id ).parent().before( '<div id="scroll_to' + random_id + '"></div>' );
			// 		console.log( jQuery( '#scroll_to' + random_id ) );
			//
			// 		wpbc_do_scroll( '#scroll_to' + random_id );
			// 		//wpbc_do_scroll( jQuery( '#booking_form' + resource_id ).parent().get( 0 ) );
			// 	}
			// }, 500 );
		}
	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Mini Spin Loader  ==  "  >

		/**
		 * Show mini Spin Loader
		 * @param parent_html_id
		 */
		function wpbc__spin_loader__mini__show( parent_html_id , color = '#0071ce' ){

			if ( ('undefined' !== typeof (color)) && ('' != color) ){
				color = 'border-color:' + color + ';';
			}
			// Show Spin Loader
			jQuery( '#' + parent_html_id ).after(
				'<div id="wpbc_mini_spin_loader' + parent_html_id + '" class="wpbc_booking_form_spin_loader" style="position: relative;min-height: 2.8rem;"><div class="wpbc_spins_loader_wrapper"><div class="wpbc_one_spin_loader_mini 0wpbc_spins_loader_mini" style="'+color+'"></div></div></div>'
			);
		}

		/**
		 * Remove / Hide mini Spin Loader
		 * @param parent_html_id
		 */
		function wpbc__spin_loader__mini__hide( parent_html_id ){

			// Remove Spin Loader
			jQuery( '#wpbc_mini_spin_loader' + parent_html_id ).remove();
		}

	// </editor-fold>

//TODO: what  about showing only  Thank you. message without payment forms.
/**
 * Show 'Thank you'. message and payment forms
 *
 * @param response_data
 */
function wpbc_show_thank_you_message_after_booking( response_data ){

	if (
 		   ('undefined' !== typeof (response_data[ 'ajx_confirmation' ][ 'ty_is_redirect' ]))
		&& ('undefined' !== typeof (response_data[ 'ajx_confirmation' ][ 'ty_url' ]))
		&& ('page' == response_data[ 'ajx_confirmation' ][ 'ty_is_redirect' ])
		&& ('' != response_data[ 'ajx_confirmation' ][ 'ty_url' ])
	){
		window.location.href = response_data[ 'ajx_confirmation' ][ 'ty_url' ];
		return;
	}

	var resource_id = response_data[ 'resource_id' ]
	var confirm_content ='';

	if ( 'undefined' === typeof (response_data[ 'ajx_confirmation' ][ 'ty_message' ]) ){
					  			 response_data[ 'ajx_confirmation' ][ 'ty_message' ] = '';
	}
	if ( 'undefined' === typeof (response_data[ 'ajx_confirmation' ][ 'ty_payment_payment_description' ] ) ){
		 			  			 response_data[ 'ajx_confirmation' ][ 'ty_payment_payment_description' ] = '';
	}
	if ( 'undefined' === typeof (response_data[ 'ajx_confirmation' ][ 'payment_cost' ] ) ){
					  			 response_data[ 'ajx_confirmation' ][ 'payment_cost' ] = '';
	}
	if ( 'undefined' === typeof (response_data[ 'ajx_confirmation' ][ 'ty_payment_gateways' ] ) ){
					  			 response_data[ 'ajx_confirmation' ][ 'ty_payment_gateways' ] = '';
	}
	var ty_message_hide 						= ('' == response_data[ 'ajx_confirmation' ][ 'ty_message' ]) ? 'wpbc_ty_hide' : '';
	var ty_payment_payment_description_hide 	= ('' == response_data[ 'ajx_confirmation' ][ 'ty_payment_payment_description' ].replace( /\\n/g, '' )) ? 'wpbc_ty_hide' : '';
	var ty_booking_costs_hide 				= ('' == response_data[ 'ajx_confirmation' ][ 'payment_cost' ]) ? 'wpbc_ty_hide' : '';
	var ty_payment_gateways_hide 			= ('' == response_data[ 'ajx_confirmation' ][ 'ty_payment_gateways' ].replace( /\\n/g, '' )) ? 'wpbc_ty_hide' : '';

	if ( 'wpbc_ty_hide' != ty_payment_gateways_hide ){
		jQuery( '.wpbc_ty__content_text.wpbc_ty__content_gateways' ).html( '' );	// Reset  all  other possible gateways before showing new one.
	}

	confirm_content += `<div id="wpbc_scroll_point_${resource_id}"></div>`;
	confirm_content += `  <div class="wpbc_after_booking_thank_you_section">`;
	confirm_content += `    <div class="wpbc_ty__message ${ty_message_hide}">${response_data[ 'ajx_confirmation' ][ 'ty_message' ]}</div>`;
    confirm_content += `    <div class="wpbc_ty__container">`;
    confirm_content += `      <div class="wpbc_ty__header">${response_data['ajx_confirmation']['ty_message_booking_id']}</div>`;
    confirm_content += `      <div class="wpbc_ty__content">`;
	confirm_content += `        <div class="wpbc_ty__content_text wpbc_ty__payment_description ${ty_payment_payment_description_hide}">${response_data[ 'ajx_confirmation' ][ 'ty_payment_payment_description' ].replace( /\\n/g, '' )}</div>`;
    confirm_content += `      	<div class="wpbc_ty__content_text wpbc_cols_2">${response_data['ajx_confirmation']['ty_customer_details']}</div>`;
    confirm_content += `      	<div class="wpbc_ty__content_text wpbc_cols_2">${response_data['ajx_confirmation']['ty_booking_details']}</div>`;
	confirm_content += `        <div class="wpbc_ty__content_text wpbc_ty__content_costs ${ty_booking_costs_hide}">${response_data[ 'ajx_confirmation' ][ 'ty_booking_costs' ]}</div>`;
	confirm_content += `        <div class="wpbc_ty__content_text wpbc_ty__content_gateways ${ty_payment_gateways_hide}">${response_data[ 'ajx_confirmation' ][ 'ty_payment_gateways' ].replace( /\\n/g, '' ).replace( /ajax_script/gi, 'script' )}</div>`;
    confirm_content += `      </div>`;
    confirm_content += `    </div>`;
	confirm_content += `</div>`;

 	jQuery( '#booking_form' + resource_id ).after( confirm_content );
}
