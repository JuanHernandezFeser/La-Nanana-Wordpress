"use strict";


/**
 * Encode HTML text to safe HTML entities
 *
 * Replace all characters in the given range (unicode 00A0 - 9999, as well as ampersand, greater & less than)
 * with their html entity equivalents, which is simply &#nnn; where nnn is the unicode value we get from charCodeAt
 *
 * @param rawStr
 * @returns {*}
 */
function wpbc_get_safe_html_text( rawStr ){

	var encodedStr = rawStr.replace( /[\u00A0-\u9999<>\&]/g, function ( i ){
		return '&#' + i.charCodeAt( 0 ) + ';';
	} );

	return encodedStr;
}


/**
 * Change Value and Title of dropdown after clicking on Apply button
 *
 * @param params	Example: { 'dropdown_id': 'wh_booking_date', 'dropdown_radio_name': 'ui_wh_booking_date_radio' }
 */
function wpbc_ui_dropdown_apply_click( params ){

	// Get input values of all elements in LI section,  where RADIO was selected
	var filter_ui_dates_arr = jQuery( 'input[name="' + params[ 'dropdown_radio_name' ] + '"]:checked' )
										.parents( 'li' ).find( ':input' )
										.map( function (){
											return wpbc_get_safe_html_text( jQuery( this ).val() );
										} ).get();

	if ( 0 !== filter_ui_dates_arr.length ){  // Continue only if radio button  was selected, and we are having value

		// Get titles of all elements in LI section,  where RADIO was selected
		var filter_ui_titles_arr = jQuery( 'input[name="' + params[ 'dropdown_radio_name' ] + '"]:checked' )
										  	.parents( 'li' ).find( ':input' )
											.map( function (){
												if ( 'text' == jQuery( this ).prop( 'type' ) ){
													return jQuery( this ).val();
												}
												if ( 'select-one' == jQuery( this ).prop( 'type' ) ){
													return jQuery( this ).find( ':selected' ).text();
												}
												if (
														( 'radio' == jQuery( this ).prop( 'type' ) )
													 || ( 'checkbox' == jQuery( this ).prop( 'type' ) )
												){
													var input_selected = jQuery( this ).filter(':checked').next( '.wpbc_ui_control_label' ).html();
													if ( undefined == input_selected ) {
														input_selected = jQuery( this ).filter(':checked').prev( '.wpbc_ui_control_label' ).html();
													}
													return ( undefined !== input_selected ) ? input_selected : '';
												}

												return jQuery( this ).val();
											} ).get();

		// Update Value to  dropdown input hidden elements. Such  value stringify.
		jQuery( '#' + params[ 'dropdown_id' ] ).val( JSON.stringify( filter_ui_dates_arr ) );

		// Generate change action,  for ability to  send Ajax request
		jQuery( '#' + params[ 'dropdown_id' ] ).trigger( 'change' );

		// Get Label of selected Radio button
		var filter_ui_dates_title = jQuery( 'input[name="' + params[ 'dropdown_radio_name' ] + '"]:checked' ).next( '.wpbc_ui_control_label' ).html() + ': ';

		// Remove selected value of radio button from beginning, we will use Label title instead
		filter_ui_titles_arr.shift();

		// Update Title in dropdown
		var encoded_html_text = wpbc_get_safe_html_text( filter_ui_dates_title + filter_ui_titles_arr.join( ' - ' ) );
		jQuery( '#' + params[ 'dropdown_id' ] + '_selector .wpbc_selected_in_dropdown' ).html( encoded_html_text );
	}

	jQuery( '#' + params[ 'dropdown_id' ] + '_container' ).hide();
}


/**
 * Close dropdown after clicking on Close button
 *
 * @param dropdown_id	ID of dropdown
 */
function wpbc_ui_dropdown_close_click( dropdown_id ){

	jQuery( '#' + dropdown_id + '_container' ).hide();
}


/**
 * Simple option click on dropdown
 *
 * @param params	Example: { 'dropdown_id': 'wh_booking_date', 'is_this_simple_list': true, 'value': '5', '_this': this }
 */
function wpbc_ui_dropdown_simple_click( params ){

	jQuery( '#' + params[ 'dropdown_id' ] + '_selector .wpbc_selected_in_dropdown' ).html( jQuery( params[ '_this' ] ).html() );

	jQuery( '#' + params[ 'dropdown_id' ] ).val( JSON.stringify( [params[ 'value' ]] ) );

	jQuery( '#' + params[ 'dropdown_id' ] + '_container li input[type=checkbox],'
		  + '#' + params[ 'dropdown_id' ] + '_container li input[type=radio]' ).prop( 'checked', false );

	jQuery( '#' + params[ 'dropdown_id' ] ).trigger( 'change' );

	if ( ! params[ 'is_this_simple_list' ] ){
		jQuery( '#' + params[ 'dropdown_id' ] + '_container' ).hide();
	}
}