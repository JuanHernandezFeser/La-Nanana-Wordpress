"use strict";

/**
 *   Ajax   ----------------------------------------------------------------------------------------------------- */
//var is_this_action = false;
/**
 * Send Ajax action request,  like approving or cancellation
 *
 * @param action_param
 */
function wpbc_ajx_booking_ajax_action_request( action_param = {} ){

console.groupCollapsed( 'WPBC_AJX_BOOKING_ACTIONS' ); console.log( ' == Ajax Actions :: Params == ', action_param );
//is_this_action = true;

	wpbc_booking_listing_reload_button__spin_start();

	// Get redefined Locale,  if action on single booking !
	if (  ( undefined != action_param[ 'booking_id' ] ) && ( ! Array.isArray( action_param[ 'booking_id' ] ) ) ){				// Not array

		action_param[ 'locale' ] = wpbc_get_selected_locale( action_param[ 'booking_id' ], wpbc_ajx_booking_listing.get_secure_param( 'locale' ) );
	}

	var action_post_params = {
								action          : 'WPBC_AJX_BOOKING_ACTIONS',
								nonce           : wpbc_ajx_booking_listing.get_secure_param( 'nonce' ),
								wpbc_ajx_user_id: ( ( undefined == action_param[ 'user_id' ] ) ? wpbc_ajx_booking_listing.get_secure_param( 'user_id' ) : action_param[ 'user_id' ] ),
								wpbc_ajx_locale:  ( ( undefined == action_param[ 'locale' ] )  ? wpbc_ajx_booking_listing.get_secure_param( 'locale' )  : action_param[ 'locale' ] ),

								action_params	: action_param
							};

	// It's required for CSV export - getting the same list  of bookings
	if ( typeof action_param.search_params !== 'undefined' ){
		action_post_params[ 'search_params' ] = action_param.search_params;
		delete action_post_params.action_params.search_params;
	}

	// Start Ajax
	jQuery.post( wpbc_global1.wpbc_ajaxurl ,

				action_post_params ,

				/**
				 * S u c c e s s
				 *
				 * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
				 * @param textStatus		-	'success'
				 * @param jqXHR				-	Object
				 */
				function ( response_data, textStatus, jqXHR ) {

console.log( ' == Ajax Actions :: Response WPBC_AJX_BOOKING_ACTIONS == ', response_data ); console.groupEnd();

					// Probably Error
					if ( (typeof response_data !== 'object') || (response_data === null) ){
						jQuery( '.wpbc_ajx_under_toolbar_row' ).hide();	 												//FixIn: 9.6.1.5
						jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html(
																	'<div class="wpbc-settings-notice notice-warning" style="text-align:left">' +
																		response_data +
																	'</div>'
															);
						return;
					}

					wpbc_booking_listing_reload_button__spin_pause();

					wpbc_admin_show_message(
												  response_data[ 'ajx_after_action_message' ].replace( /\n/g, "<br />" )
												, ( '1' == response_data[ 'ajx_after_action_result' ] ) ? 'success' : 'error'
												, ( ( 'undefined' === typeof(response_data[ 'ajx_after_action_result_all_params_arr' ][ 'after_action_result_delay' ]) )
													? 10000
													: response_data[ 'ajx_after_action_result_all_params_arr' ][ 'after_action_result_delay' ] )
											);

					// Success response
					if ( '1' == response_data[ 'ajx_after_action_result' ] ){

						var is_reload_ajax_listing = true;

						// After Google Calendar import show imported bookings and reload the page for toolbar parameters update
						if ( false !== response_data[ 'ajx_after_action_result_all_params_arr' ][ 'new_listing_params' ] ){

							wpbc_ajx_booking_send_search_request_with_params( response_data[ 'ajx_after_action_result_all_params_arr' ][ 'new_listing_params' ] );

							var closed_timer = setTimeout( function (){

									if ( wpbc_booking_listing_reload_button__is_spin() ){
										if ( undefined != response_data[ 'ajx_after_action_result_all_params_arr' ][ 'new_listing_params' ][ 'reload_url_params' ] ){
											document.location.href = response_data[ 'ajx_after_action_result_all_params_arr' ][ 'new_listing_params' ][ 'reload_url_params' ];
										} else {
											document.location.reload();
										}
									}
																}
													, 2000 );
							is_reload_ajax_listing = false;
						}

						// Start download exported CSV file
						if ( undefined != response_data[ 'ajx_after_action_result_all_params_arr' ][ 'export_csv_url' ] ){
							wpbc_ajx_booking__export_csv_url__download( response_data[ 'ajx_after_action_result_all_params_arr' ][ 'export_csv_url' ] );
							is_reload_ajax_listing = false;
						}

						if ( is_reload_ajax_listing ){
							wpbc_ajx_booking__actual_listing__show();	//	Sending Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
						}

					}

					// Remove spin icon from  button and Enable this button.
					wpbc_button__remove_spin( response_data[ 'ajx_cleaned_params' ][ 'ui_clicked_element_id' ] )

					// Hide modals
					wpbc_popup_modals__hide();

					jQuery( '#ajax_respond' ).html( response_data );		// For ability to show response, add such DIV element to page
				}
			  ).fail( function ( jqXHR, textStatus, errorThrown ) {    if ( window.console && window.console.log ){ console.log( 'Ajax_Error', jqXHR, textStatus, errorThrown ); }
					jQuery( '.wpbc_ajx_under_toolbar_row' ).hide();														//FixIn: 9.6.1.5
					var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown ;
					if ( jqXHR.responseText ){
						error_message += jqXHR.responseText;
					}
					error_message = error_message.replace( /\n/g, "<br />" );

					wpbc_ajx_booking_show_message( error_message );
			  })
	          // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
			  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
			  ;  // End Ajax
}



/**
 * Hide all open modal popups windows
 */
function wpbc_popup_modals__hide(){

	// Hide modals
	if ( 'function' === typeof (jQuery( '.wpbc_popup_modal' ).wpbc_my_modal) ){
		jQuery( '.wpbc_popup_modal' ).wpbc_my_modal( 'hide' );
	}
}


/**
 *   Dates  Short <-> Wide    ----------------------------------------------------------------------------------- */

function wpbc_ajx_click_on_dates_short(){
	jQuery( '#booking_dates_small,.booking_dates_full' ).hide();
	jQuery( '#booking_dates_full,.booking_dates_small' ).show();
	wpbc_ajx_booking_send_search_request_with_params( {'ui_usr__dates_short_wide': 'short'} );
}

function wpbc_ajx_click_on_dates_wide(){
	jQuery( '#booking_dates_full,.booking_dates_small' ).hide();
	jQuery( '#booking_dates_small,.booking_dates_full' ).show();
	wpbc_ajx_booking_send_search_request_with_params( {'ui_usr__dates_short_wide': 'wide'} );
}

function wpbc_ajx_click_on_dates_toggle(this_date){

	jQuery( this_date ).parents( '.wpbc_col_dates' ).find( '.booking_dates_small' ).toggle();
	jQuery( this_date ).parents( '.wpbc_col_dates' ).find( '.booking_dates_full' ).toggle();

	/*
	var visible_section = jQuery( this_date ).parents( '.booking_dates_expand_section' );
	visible_section.hide();
	if ( visible_section.hasClass( 'booking_dates_full' ) ){
		visible_section.parents( '.wpbc_col_dates' ).find( '.booking_dates_small' ).show();
	} else {
		visible_section.parents( '.wpbc_col_dates' ).find( '.booking_dates_full' ).show();
	}*/
	console.log( 'wpbc_ajx_click_on_dates_toggle', this_date );
}

/**
 *   Locale   --------------------------------------------------------------------------------------------------- */

/**
 * 	Select options in select boxes based on attribute "value_of_selected_option" and RED color and hint for LOCALE button   --  It's called from 	wpbc_ajx_booking_define_ui_hooks()  	each  time after Listing loading.
 */
function wpbc_ajx_booking__ui_define__locale(){

	jQuery( '.wpbc_listing_container select' ).each( function ( index ){

		var selection = jQuery( this ).attr( "value_of_selected_option" );			// Define selected select boxes

		if ( undefined !== selection ){
			jQuery( this ).find( 'option[value="' + selection + '"]' ).prop( 'selected', true );

			if ( ('' != selection) && (jQuery( this ).hasClass( 'set_booking_locale_selectbox' )) ){								// Locale

				var booking_locale_button = jQuery( this ).parents( '.ui_element_locale' ).find( '.set_booking_locale_button' )

				//booking_locale_button.css( 'color', '#db4800' );		// Set button  red
				booking_locale_button.addClass( 'wpbc_ui_red' );		// Set button  red
				 if ( 'function' === typeof( wpbc_tippy ) ){
					booking_locale_button.get(0)._tippy.setContent( selection );
				 }
			}
		}
	} );
}

/**
 *   Remark   --------------------------------------------------------------------------------------------------- */

/**
 * Define content of remark "booking note" button and textarea.  -- It's called from 	wpbc_ajx_booking_define_ui_hooks()  	each  time after Listing loading.
 */
function wpbc_ajx_booking__ui_define__remark(){

	jQuery( '.wpbc_listing_container .ui_remark_section textarea' ).each( function ( index ){
		var text_val = jQuery( this ).val();
		if ( (undefined !== text_val) && ('' != text_val) ){

			var remark_button = jQuery( this ).parents( '.ui_group' ).find( '.set_booking_note_button' );

			if ( remark_button.length > 0 ){

				remark_button.addClass( 'wpbc_ui_red' );		// Set button  red
				if ( 'function' === typeof (wpbc_tippy) ){
					//remark_button.get( 0 )._tippy.allowHTML = true;
					//remark_button.get( 0 )._tippy.setContent( text_val.replace(/[\n\r]/g, '<br>') );

					remark_button.get( 0 )._tippy.setProps( {
						allowHTML: true,
						content  : text_val.replace( /[\n\r]/g, '<br>' )
					} );
				}
			}
		}
	} );
}

/**
 * Actions ,when we click on "Remark" button.
 *
 * @param jq_button  -	this jQuery button  object
 */
function wpbc_ajx_booking__ui_click__remark( jq_button ){

	jq_button.parents('.ui_group').find('.ui_remark_section').toggle();
}


/**
 *   Change booking resource   ---------------------------------------------------------------------------------- */

function wpbc_ajx_booking__ui_click_show__change_resource( booking_id, resource_id ){

	// Define ID of booking to hidden input
	jQuery( '#change_booking_resource__booking_id' ).val( booking_id );

	// Select booking resource  that belong to  booking
	jQuery( '#change_booking_resource__resource_select' ).val( resource_id ).trigger( 'change' );
	var cbr;

	// Get Resource section
	cbr = jQuery( "#change_booking_resource__section" ).detach();

	// Append it to booking ROW
	cbr.appendTo( jQuery( "#ui__change_booking_resource__section_in_booking_" + booking_id ) );
	cbr = null;

	// Hide sections of "Change booking resource" in all other bookings ROWs
	//jQuery( ".ui__change_booking_resource__section_in_booking" ).hide();
	if ( ! jQuery( "#ui__change_booking_resource__section_in_booking_" + booking_id ).is(':visible') ){
		jQuery( ".ui__under_actions_row__section_in_booking" ).hide();
	}

	// Show only "change booking resource" section  for current booking
	jQuery( "#ui__change_booking_resource__section_in_booking_" + booking_id ).toggle();
}

function wpbc_ajx_booking__ui_click_save__change_resource( this_el, booking_action, el_id ){

	wpbc_ajx_booking_ajax_action_request( {
											'booking_action'       : booking_action,
											'booking_id'           : jQuery( '#change_booking_resource__booking_id' ).val(),
											'selected_resource_id' : jQuery( '#change_booking_resource__resource_select' ).val(),
											'ui_clicked_element_id': el_id
	} );

	wpbc_button_enable_loading_icon( this_el );

	// wpbc_ajx_booking__ui_click_close__change_resource();
}

function wpbc_ajx_booking__ui_click_close__change_resource(){

	var cbrce;

	// Get Resource section
	cbrce = jQuery("#change_booking_resource__section").detach();

	// Append it to hidden HTML template section  at  the bottom  of the page
	cbrce.appendTo(jQuery("#wpbc_hidden_template__change_booking_resource"));
	cbrce = null;

	// Hide all change booking resources sections
	jQuery(".ui__change_booking_resource__section_in_booking").hide();
}

/**
 *   Duplicate booking in other resource   ---------------------------------------------------------------------- */

function wpbc_ajx_booking__ui_click_show__duplicate_booking( booking_id, resource_id ){

	// Define ID of booking to hidden input
	jQuery( '#duplicate_booking_to_other_resource__booking_id' ).val( booking_id );

	// Select booking resource  that belong to  booking
	jQuery( '#duplicate_booking_to_other_resource__resource_select' ).val( resource_id ).trigger( 'change' );
	var cbr;

	// Get Resource section
	cbr = jQuery( "#duplicate_booking_to_other_resource__section" ).detach();

	// Append it to booking ROW
	cbr.appendTo( jQuery( "#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id ) );
	cbr = null;

	// Hide sections of "Duplicate booking" in all other bookings ROWs
	if ( ! jQuery( "#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id ).is(':visible') ){
		jQuery( ".ui__under_actions_row__section_in_booking" ).hide();
	}

	// Show only "Duplicate booking" section  for current booking ROW
	jQuery( "#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id ).toggle();
}

function wpbc_ajx_booking__ui_click_save__duplicate_booking( this_el, booking_action, el_id ){

	wpbc_ajx_booking_ajax_action_request( {
											'booking_action'       : booking_action,
											'booking_id'           : jQuery( '#duplicate_booking_to_other_resource__booking_id' ).val(),
											'selected_resource_id' : jQuery( '#duplicate_booking_to_other_resource__resource_select' ).val(),
											'ui_clicked_element_id': el_id
	} );

	wpbc_button_enable_loading_icon( this_el );

	// wpbc_ajx_booking__ui_click_close__change_resource();
}

function wpbc_ajx_booking__ui_click_close__duplicate_booking(){

	var cbrce;

	// Get Resource section
	cbrce = jQuery("#duplicate_booking_to_other_resource__section").detach();

	// Append it to hidden HTML template section  at  the bottom  of the page
	cbrce.appendTo(jQuery("#wpbc_hidden_template__duplicate_booking_to_other_resource"));
	cbrce = null;

	// Hide all change booking resources sections
	jQuery(".ui__duplicate_booking_to_other_resource__section_in_booking").hide();
}

/**
 *   Change payment status   ------------------------------------------------------------------------------------ */

function wpbc_ajx_booking__ui_click_show__set_payment_status( booking_id ){

	var jSelect = jQuery( '#ui__set_payment_status__section_in_booking_' + booking_id ).find( 'select' )

	var selected_pay_status = jSelect.attr( "ajx-selected-value" );

	// Is it float - then  it's unknown
	if ( !isNaN( parseFloat( selected_pay_status ) ) ){
		jSelect.find( 'option[value="1"]' ).prop( 'selected', true );								// Unknown  value is '1' in select box
	} else {
		jSelect.find( 'option[value="' + selected_pay_status + '"]' ).prop( 'selected', true );		// Otherwise known payment status
	}

	// Hide sections of "Change booking resource" in all other bookings ROWs
	if ( ! jQuery( "#ui__set_payment_status__section_in_booking_" + booking_id ).is(':visible') ){
		jQuery( ".ui__under_actions_row__section_in_booking" ).hide();
	}

	// Show only "change booking resource" section  for current booking
	jQuery( "#ui__set_payment_status__section_in_booking_" + booking_id ).toggle();
}

function wpbc_ajx_booking__ui_click_save__set_payment_status( booking_id, this_el, booking_action, el_id ){

	wpbc_ajx_booking_ajax_action_request( {
											'booking_action'       : booking_action,
											'booking_id'           : booking_id,
											'selected_payment_status' : jQuery( '#ui_btn_set_payment_status' + booking_id ).val(),
											'ui_clicked_element_id': el_id + '_save'
	} );

	wpbc_button_enable_loading_icon( this_el );

	jQuery( '#' + el_id + '_cancel').hide();
	//wpbc_button_enable_loading_icon( jQuery( '#' + el_id + '_cancel').get(0) );

}

function wpbc_ajx_booking__ui_click_close__set_payment_status(){
	// Hide all change  payment status for booking
	jQuery(".ui__set_payment_status__section_in_booking").hide();
}


/**
 *   Change booking cost   -------------------------------------------------------------------------------------- */

function wpbc_ajx_booking__ui_click_save__set_booking_cost( booking_id, this_el, booking_action, el_id ){

	wpbc_ajx_booking_ajax_action_request( {
											'booking_action'       : booking_action,
											'booking_id'           : booking_id,
											'booking_cost' 		   : jQuery( '#ui_btn_set_booking_cost' + booking_id + '_cost').val(),
											'ui_clicked_element_id': el_id + '_save'
	} );

	wpbc_button_enable_loading_icon( this_el );

	jQuery( '#' + el_id + '_cancel').hide();
	//wpbc_button_enable_loading_icon( jQuery( '#' + el_id + '_cancel').get(0) );

}

function wpbc_ajx_booking__ui_click_close__set_booking_cost(){
	// Hide all change  payment status for booking
	jQuery(".ui__set_booking_cost__section_in_booking").hide();
}


/**
 *   Send Payment request   -------------------------------------------------------------------------------------- */

function wpbc_ajx_booking__ui_click__send_payment_request(){

	wpbc_ajx_booking_ajax_action_request( {
											'booking_action'       : 'send_payment_request',
											'booking_id'           : jQuery( '#wpbc_modal__payment_request__booking_id').val(),
											'reason_of_action' 	   : jQuery( '#wpbc_modal__payment_request__reason_of_action').val(),
											'ui_clicked_element_id': 'wpbc_modal__payment_request__button_send'
	} );
	wpbc_button_enable_loading_icon( jQuery( '#wpbc_modal__payment_request__button_send' ).get( 0 ) );
}


/**
 *   Import Google Calendar  ------------------------------------------------------------------------------------ */

function wpbc_ajx_booking__ui_click__import_google_calendar(){

	wpbc_ajx_booking_ajax_action_request( {
											'booking_action'       : 'import_google_calendar',
											'ui_clicked_element_id': 'wpbc_modal__import_google_calendar__button_send'

											, 'booking_gcal_events_from' : 				jQuery( '#wpbc_modal__import_google_calendar__section #booking_gcal_events_from option:selected').val()
											, 'booking_gcal_events_from_offset' : 		jQuery( '#wpbc_modal__import_google_calendar__section #booking_gcal_events_from_offset' ).val()
											, 'booking_gcal_events_from_offset_type' : 	jQuery( '#wpbc_modal__import_google_calendar__section #booking_gcal_events_from_offset_type option:selected').val()

											, 'booking_gcal_events_until' : 			jQuery( '#wpbc_modal__import_google_calendar__section #booking_gcal_events_until option:selected').val()
											, 'booking_gcal_events_until_offset' : 		jQuery( '#wpbc_modal__import_google_calendar__section #booking_gcal_events_until_offset' ).val()
											, 'booking_gcal_events_until_offset_type' : jQuery( '#wpbc_modal__import_google_calendar__section #booking_gcal_events_until_offset_type option:selected').val()

											, 'booking_gcal_events_max' : 	jQuery( '#wpbc_modal__import_google_calendar__section #booking_gcal_events_max' ).val()
											, 'booking_gcal_resource' : 	jQuery( '#wpbc_modal__import_google_calendar__section #wpbc_booking_resource option:selected').val()
	} );
	wpbc_button_enable_loading_icon( jQuery( '#wpbc_modal__import_google_calendar__section #wpbc_modal__import_google_calendar__button_send' ).get( 0 ) );
}


/**
 *   Export bookings to CSV  ------------------------------------------------------------------------------------ */
function wpbc_ajx_booking__ui_click__export_csv( params ){

	var selected_booking_id_arr = wpbc_get_selected_row_id();

	wpbc_ajx_booking_ajax_action_request( {
											'booking_action'        : params[ 'booking_action' ],
											'ui_clicked_element_id' : params[ 'ui_clicked_element_id' ],

											'export_type'           : params[ 'export_type' ],
											'csv_export_separator'  : params[ 'csv_export_separator' ],
											'csv_export_skip_fields': params[ 'csv_export_skip_fields' ],

											'booking_id'	: selected_booking_id_arr.join(','),
											'search_params' : wpbc_ajx_booking_listing.search_get_all_params()
										} );

	var this_el = jQuery( '#' + params[ 'ui_clicked_element_id' ] ).get( 0 )

	wpbc_button_enable_loading_icon( this_el );
}

/**
 * Open URL in new tab - mainly  it's used for open CSV link  for downloaded exported bookings as CSV
 *
 * @param export_csv_url
 */
function wpbc_ajx_booking__export_csv_url__download( export_csv_url ){

	//var selected_booking_id_arr = wpbc_get_selected_row_id();

	document.location.href = export_csv_url;// + '&selected_id=' + selected_booking_id_arr.join(',');

	// It's open additional dialog for asking opening ulr in new tab
	// window.open( export_csv_url, '_blank').focus();
}