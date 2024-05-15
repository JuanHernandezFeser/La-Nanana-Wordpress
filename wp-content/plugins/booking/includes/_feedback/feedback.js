/**
 * Open feedback  Modal window
 */
function wpbc_open_feedback_modal(){
	if ( 'function' === typeof( jQuery('#wpbc_modal__feedback_01__section').wpbc_my_modal ) ) {

		// Insert Feedback modal after 'wpbc-admin-page' section,  for ability to  blur this content
		var wpbc_modal__feedback_01 = jQuery( '#wpbc_modal__feedback_01__section_mover' ).detach();
		wpbc_modal__feedback_01.appendTo( '.wrap.wpbc_page' );

		// Add blur
		jQuery( '.wpbc_admin_page,.wpbc_admin_message,.wpbc_header' ).css( { filter: 'blur(3px)' } );

		// Show Modal
		jQuery('#wpbc_modal__feedback_01__section').wpbc_my_modal('show');
	} else {
		console.log( 'Warning! wpbc_my_modal module has not found. Please, recheck about any conflicts by deactivating other plugins.');
	}

}

// Remove blur, after closing modal
jQuery('#wpbc_modal__feedback_01__section').on('hide.wpbc.modal', function (event) {
  //var modal = jQuery(this);
  jQuery( '.wpbc_admin_page,.wpbc_admin_message,.wpbc_header' ).css( {filter: 'none'} );
})


/**
 * Click  on Stars to rate
 *
 * @param int star_num_over		- star number
 * @param string is_over			- mouse action 	'click' | 'over' | 'out'
 */
function wpbc_feedback_01__over_star( star_num_over , is_over ){

	var star_fill    = 'wpbc-bi-star-fill';	//'wpbc_icn_star';
	var star_outline = 'wpbc-bi-star';	//'wpbc_icn_star_outline';

	if ( 'click' == is_over ){
		jQuery( '.wpbc_mouseover_star_selected' ).removeClass( 'wpbc_mouseover_star_selected' );
		jQuery( '#wpbc_modal__feedback_01__button_next__step_1').removeClass('disabled');
	}
	if ( 'over' == is_over ){
		jQuery( '.wpbc_mouseover_star_selected' ).removeClass( star_fill ).addClass( star_outline );
	}

	for ( var star_num = 1; star_num <= star_num_over; star_num++ ){

		if ( 'click' == is_over ){

			jQuery( '#wpbc_feedback_01_star_' + star_num + ' i' ).removeClass( star_outline ).addClass( star_fill+ ' wpbc_mouseover_star_selected' );
		}
		if ( 'over' == is_over ){
			jQuery( '#wpbc_feedback_01_star_' + star_num + ' i' ).removeClass( star_outline ).addClass( star_fill );
		}
		if ( 'out' == is_over ){
			jQuery( '#wpbc_feedback_01_star_' + star_num + ' i' ).removeClass( star_fill ).addClass( star_outline );
		}
	}
	if ( 'out' == is_over ){
		jQuery( '.wpbc_mouseover_star_selected' ).removeClass( star_outline ).addClass( star_fill );
	}

}


/**
 * Click  on Next  button  to  show next  section.
 *
 * @param _this
 * @param go_to_this_step	'class' of section to show
 * @returns {boolean}
 */
function wpbc_ajx_booking__ui_click__send_feedback_01(_this, go_to_this_step = ''){

	if ( jQuery( _this ).hasClass( 'disabled' ) ){
		return false;
	}

	jQuery( '.wpbc_modal__feedback_01__steps').hide();

	if ( '' != go_to_this_step ){
		jQuery( go_to_this_step ).show();
		return;
	}

 	var stars_selected =  jQuery( '.wpbc_mouseover_star_selected').length;



	switch (stars_selected) {
	  case 1:
	  case 2:
		jQuery( '.wpbc_modal__feedback_01__step_2').show();
		break;
	  case 3:
	  case 4:
		jQuery( '.wpbc_modal__feedback_01__step_4').show();
		break;
	  case 5:
		jQuery( '.wpbc_modal__feedback_01__step_6').show();
		break;
	  default:

	}

	return;

    var visible_step = jQuery( '.wpbc_modal__feedback_01__steps:visible');
	jQuery( '.wpbc_modal__feedback_01__steps').hide();
	visible_step.next().show();
}

function wpbc_ajx_booking__ui_click__close_feedback_01(){
	// Close modal
	if ( 'function' === typeof( jQuery('#wpbc_modal__feedback_01__section').wpbc_my_modal ) ){
		jQuery( '#wpbc_modal__feedback_01__section' ).wpbc_my_modal( 'hide' );
	}
}


// Ajax Sending:

	/**
	 * Click on Remind later link
	 * Send ajax request for opening feedback  in few days
	 */
	function wpbc_ajx_booking__ui_click__feedback_01_remind_later(){


		wpbc_feedback__ajax_action_request( {
												'booking_action'       : 'feedback_01',
												'feedback_stars'       : jQuery( '.wpbc_mouseover_star_selected').length,
												'feedback__note' 	   : 'remind_later',
												'ui_clicked_element_id': 'wpbc_modal__feedback_01__button_next__step_1'
		} );
		wpbc_button_enable_loading_icon( jQuery( '#wpbc_modal__feedback_01__button_next__step_1' ).get( 0 ) );

		wpbc_ajx_booking__ui_click__close_feedback_01();			// Close modal
	}


	/**
	 * Click  on Done | Submit  button  to  send feedback
	 *
	 * @param _this
	 * @param go_to_this_step	'class' of last section
	 */
	function wpbc_ajx_booking__ui_click__submit_feedback_01( _this, go_to_this_step){

		if ( jQuery( _this ).hasClass( 'disabled' ) ){
			return false;
		}

		var stars_selected =  jQuery( '.wpbc_mouseover_star_selected').length;
		var feedback__note = '';

		switch ( go_to_this_step ) {

		  case '.wpbc_modal__feedback_01__step_3':
			  feedback__note = jQuery( '#wpbc_modal__feedback_01__reason_of_action__step_2').val();
			break;

		  case '.wpbc_modal__feedback_01__step_5':
			  feedback__note = jQuery( '#wpbc_modal__feedback_01__reason_of_action__step_4').val();
			break;

		  case '.wpbc_modal__feedback_01__step_7':
			  feedback__note = jQuery( '#wpbc_modal__feedback_01__reason_of_action__step_7').val();
			break;

		  default:

		}

		// Send Ajax
		wpbc_feedback__ajax_action_request( {
												'booking_action'       : 'feedback_01',
												'feedback_stars'       : stars_selected,
												'feedback__note' 	   : feedback__note,
												'ui_clicked_element_id': jQuery( _this ).attr( 'id' )
		} );
		wpbc_button_enable_loading_icon( jQuery( _this ).get( 0 ) );

		//wpbc_ajx_booking__ui_click__close_feedback_01();			// Close modal
	}


	function wpbc_feedback__ajax_action_request( action_param ){

		wpbc_admin_show_message_processing( '' );

		var jq_el = jQuery( '#wpbc_ajax__feedback_01' );

		var action_post_params = {
									action          : 'WPBC_AJX_FEEDBACK',
									nonce           : jq_el.attr( 'data-nonce' ),
									wpbc_ajx_user_id: jq_el.attr( 'data-user-id' ),

									action_params	: action_param
								};

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

console.log( ' == Ajax Feedback :: Response WPBC_AJX_BOOKING_ACTIONS == ', response_data );

						// Probably Error
						if ( (typeof response_data !== 'object') || (response_data === null) ){

							wpbc_admin_show_message(
														'<div class="wpbc-settings-notice notice-warning" style="text-align:left">' +
															response_data +
														'</div>'
													);
							return;
						}

						wpbc_admin_show_message(
													  response_data[ 'ajx_after_action_message' ].replace( /\n/g, "<br />" )
													, ( '1' == response_data[ 'ajx_after_action_result' ] ) ? 'success' : 'error'
													, 10000
												);

						var closed_timer = setTimeout( function (){
																	if ( 5 == response_data['ajx_after_action_result_all_params_arr'][ 'feedback_stars' ] ){
																		document.location.href = 'https://wordpress.org/support/plugin/booking/reviews/?filter=5#new-post'
																	}
																}
													, 2000 );

						// Remove spin icon from  button and Enable this button.
						wpbc_button__remove_spin( response_data[ 'ajx_cleaned_params' ][ 'ui_clicked_element_id' ] )

						// Hide modals
						wpbc_popup_modals__hide();

						jQuery( '#ajax_respond' ).html( response_data );		// For ability to show response, add such DIV element to page
					}
				  ).fail( function ( jqXHR, textStatus, errorThrown ) {    if ( window.console && window.console.log ){ console.log( 'Ajax_Error', jqXHR, textStatus, errorThrown ); }

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