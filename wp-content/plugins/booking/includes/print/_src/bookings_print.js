"use strict";

function wpbc_print_dialog__show( booking_id_arr ){
	
	if ( 'function' === typeof (jQuery( '#wpbc_ajx_print_modal' ).wpbc_my_modal) ){
		jQuery( '#wpbc_ajx_print_modal' ).wpbc_my_modal( 'show' );


		if ( undefined == booking_id_arr ){
			booking_id_arr = [];
		}
		if ( ! Array.isArray( booking_id_arr ) ){
			booking_id_arr = [booking_id_arr]
		}
		wpbc_print_dialog__define_content( booking_id_arr );
	} else {
		alert( 'Warning! Modal module( wpbc_my_modal ) had not define.' )
	}
}

function wpbc_print_dialog__define_content( booking_id_arr = [] ){
	// Set content
	jQuery( '#wpbc__print_frame__inner' ).html( jQuery( '.wpbc_ajx_booking_listing_container' ).html() );
	// Define the same classes
	jQuery( '#wpbc__print_frame__inner' ).addClass( 'wpbc_listing_container wpbc_selectable_table wpbc_ajx_booking_listing_container' );
	/*
	jQuery( '#wpbc__print_frame__inner .check-column').hide();
	jQuery( '#wpbc__print_frame__inner .wpbc_actions_buttons').hide();
	jQuery( '#wpbc_ajx_print_modal .modal-body').css({
		  maxHeight : "500px",
		  overflowY: "scroll"
		});
 	*/

	// Hide some not selected rows,  if user selected them
	var selected_rows_arr;

	if ( booking_id_arr.length == 0 ){
		selected_rows_arr = wpbc_get_selected_row_id();
	} else {
		selected_rows_arr = booking_id_arr;
	}

	if ( selected_rows_arr.length > 0 ){

		jQuery( '#wpbc__print_frame__inner .wpbc_selectable_body .wpbc_listing_usual_row' ).hide();

		for ( var i = 0; i < selected_rows_arr.length; ++i ){
			jQuery( "#wpbc__print_frame__inner #row_id_" + selected_rows_arr[ i ] ).show();
		}
	}

	// Add cost as text labels to print layout
	jQuery( '#wpbc__print_frame__inner .wpbc_selectable_body .wpbc_listing_usual_row' ).find( '.set_booking_cost_text_field' ).each( function ( index ){

		var currency = jQuery( jQuery( this ).parent( '.ui_element' ).find( '.wpbc_ui_control_label' )[ 0 ] ).html();

		jQuery( this ).parents( '.wpbc_listing_usual_row ' ).find( '.wpbc_col_booking_labels .content_text' ).append( '<span class="wpbc_label wpbc_label_booking_id">' + currency + ' ' + jQuery( this ).val() + '</span>' );
	} );


	// Add remark to the content of booking details
	jQuery( '#wpbc__print_frame__inner .wpbc_selectable_body .wpbc_listing_usual_row' ).find( '.set_booking_note_text' ).each( function ( index ){

		var remark_text = jQuery( this ).val();

		if ( '' != remark_text ){

			jQuery( this ).parents( '.wpbc_listing_usual_row ' ).find( '.wpbc_col_data .content_text' ).append(
																												'<div class="wpbc_remark_text">'
																												+ '<hr/><strong>Notes:</strong><br/>'
																												+ remark_text
																												+ '</div>'
																											);
		}

	} );
}

function wpbc_print_dialog__do_printing(){
    jQuery( '#wpbc_content_for_js_print' ).wpbc_js_print(
		{
			debug               : false,                       	// show the iframe for debugging
			importCSS           : true,                    		// import parent page css
			importStyle         : true,                  		// import style tags
			printContainer      : true,               			// print outer container/$.selector
			loadCSS             : "",                        	// path to additional css file - use an array [] for multiple
			pageTitle           : "",                      		// add title to print page
			removeInline        : false,                		// remove inline styles from print elements
			removeInlineSelector: "*",          				// custom selectors to filter inline styles. removeInline must be true
			printDelay          : 300,                   		// variable print delay
			header              : null,                       	// prefix to html
			footer              : null,                       	// postfix to html
			base                : false,                        // preserve the BASE tag or accept a string for the URL
			formValues          : true,                   		// preserve input/form values
			canvas              : true,                       	// copy canvas content
			doctypeString       : '<!DOCTYPE html>',   			// enter a different doctype for older markup
			removeScripts       : false,               			// remove script tags from print content
			copyTagClasses      : true,               			// copy classes from the html & body tag
			copyTagStyles       : true,                			// copy styles from html & body tag (for CSS Variables)
			beforePrintEvent    : null,             			// callback function for printEvent in iframe
			beforePrint         : null,                  		// function called before iframe is filled
			afterPrint          : null                    		// function called before iframe is removed
		}
	);
	jQuery( '#wpbc_ajx_print_modal' ).wpbc_my_modal( 'hide' );
}