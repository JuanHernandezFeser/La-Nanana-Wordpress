"use strict";

/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Remove spin icon from  button and Enable this button.
 *
 * @param button_clicked_element_id		- HTML ID attribute of this button
 * @return string						- CSS classes that was previously in button icon
 */
function wpbc_button__remove_spin( button_clicked_element_id ){

	var previos_classes = '';
	if (
		   ( undefined != button_clicked_element_id )
		&& ( '' != button_clicked_element_id )
	){
		var jElement = jQuery( '#' + button_clicked_element_id );
		if ( jElement.length ){
			previos_classes = wpbc_button_disable_loading_icon( jElement.get( 0 ) );
		}
	}

	return previos_classes;
}


	/**
	 * Show Loading (rotating arrow) icon for button that has been clicked
	 *
	 * @param this_button		- this object of specific button
	 * @return string			- CSS classes that was previously in button icon
	 */
	function wpbc_button_enable_loading_icon( this_button ){

		var jButton = jQuery( this_button );
		var jIcon = jButton.find( 'i' );
		var previos_classes = jIcon.attr( 'class' );

		jIcon.removeClass().addClass( 'menu_icon icon-1x wpbc_icn_rotate_right wpbc_spin' );	// Set Rotate icon
		//jIcon.addClass( 'wpbc_animation_pause' );												// Pause animation
		//jIcon.addClass( 'wpbc_ui_red' );														// Set icon color red

		jIcon.attr( 'wpbc_previous_class', previos_classes )

		jButton.addClass( 'disabled' );															// Disable button
		//jButton.prop( "disabled", true );
		// We need to  set  here attr instead of prop, because for A elements,  attribute 'disabled' do  not added with jButton.prop( "disabled", true );

		jButton.attr( 'wpbc_previous_onclick', jButton.attr( 'onclick' ) );		//Save this value
		jButton.attr( 'onclick', '' );											// Disable actions "on click"

		return previos_classes;
	}


	/**
	 * Hide Loading (rotating arrow) icon for button that was clicked and show previous icon and enable button
	 *
	 * @param this_button		- this object of specific button
	 * @return string			- CSS classes that was previously in button icon
	 */
	function wpbc_button_disable_loading_icon( this_button ){

		var jButton = jQuery( this_button );
		var jIcon = jButton.find( 'i' );

		var previos_classes = jIcon.attr( 'wpbc_previous_class' );
		if (
			   ( undefined != previos_classes )
			&& ( '' != previos_classes )
		){
			jIcon.removeClass().addClass( previos_classes );
		}

		jButton.removeClass( 'disabled' );															// Remove Disable button

		var previous_onclick = jButton.attr( 'wpbc_previous_onclick' )
		if (
			   ( undefined != previous_onclick )
			&& ( '' != previous_onclick )
		){
			jButton.attr( 'onclick', previous_onclick );
		}

		return previos_classes;
	}


/**
 * Blink specific HTML element to set attention to this element.
 *
 * @param {string} element_to_blink		  - class or id of element: '.wpbc_widget_available_unavailable'
 * @param {int} how_many_times			  - 4
 * @param {int} how_long_to_blink		  - 350
 */
function wpbc_blink_element( element_to_blink, how_many_times = 4, how_long_to_blink = 350 ){

	for ( let i = 0; i < how_many_times; i++ ){
		jQuery( element_to_blink ).fadeOut( how_long_to_blink ).fadeIn( how_long_to_blink );
	}
    jQuery( element_to_blink ).animate( {opacity: 1}, 500 );
}


