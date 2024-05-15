<?php /**
 * @version 1.1
 * @package Any
 * @category Toolbar for Availability page. UI Elements for Admin Panel
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2022-11-18
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit, if accessed directly

////////////////////////////////////////////////////////////////////////////////
//   T o o l b a r s
////////////////////////////////////////////////////////////////////////////////


/**
 * Show top toolbar on Booking Listing page
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 */
function wpbc_ajx_availability__toolbar( $escaped_search_request_params ) {

    wpbc_clear_div();

    //  Toolbar ////////////////////////////////////////////////////////////////

	$default_param_values = WPBC_AJX__Availability::get__request_values__default();

	$selected_tab = $escaped_search_request_params['ui_usr__availability_selected_toolbar'];

    ?><div id="toolbar_booking_availability" class="wpbc_ajx_toolbar"><?php

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Info
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		?><div><?php //Required for bottom border radius in container

			?><div class="ui_container    ui_container_toolbar		ui_container_mini    ui_container_info    ui_container_filter_row_1" style="<?php echo ( 'info' == $selected_tab ) ? 'display: flex' : 'display: none' ?>;"><?php

				// Here will be composed template with  real HTML
				?><div class="ui_group"  id="wpbc_hidden_template__select_booking_resource" ><?php  					//	array( 'class' => 'group_nowrap' )	// Elements at Several or One Line
					// Resource select-box here. 																		Defined as template at: 	private function template_toolbar_select_booking_resource(){
				?></div><?php

				?><div class="ui_group" id="wpbc_toolbar_dates_hint"><?php  																			//	array( 'class' => 'group_nowrap' )	// Elements at Several or One Line

					wpbc_ajx_avy__ui__info( $escaped_search_request_params, $default_param_values );

				?></div><?php

			?></div><?php

		?></div><?php //Required for bottom border radius in container


		// <editor-fold     defaultstate="collapsed"                        desc="   C a l e n d a r    S e t t i n g s  "  >

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// C a l e n d a r    S e t t i n g s
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		?><div class="ui_container    ui_container_toolbar		ui_container_small    ui_container_options    ui_container_actions_row_1" style="<?php echo ( 'calendar_settings' == $selected_tab ) ? 'display: flex' : 'display: none' ?>;"><?php

			?><div class="ui_group"><?php

				?><div class="ui_element"><?php
					wpbc_avy__ui__toolbar_erase_availability_button();
				?></div><?php

			?></div><?php

			?><div class="ui_group" style="flex:1 1 auto;"><?php

					//	Reset Button
				?><div class="ui_element" style="margin-left: auto;"><?php
					wpbc_avy__ui__toolbar_reset_button();
				?></div><?php

			?></div><?php

		?></div><?php
		// </editor-fold>


	?></div><?php
}


/**
 *  Costs 	Min - Max
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx_avy__ui__info( $escaped_search_request_params, $defaults ){

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        =>   sprintf( __('%sSelect days%s in calendar then select %sAvailable%s / %sUnavailable%s status and click %sApply%s availability button.' ,'booking')
														, '<strong>', '&nbsp;</strong>'
														, '<strong>&nbsp;', '&nbsp;</strong>'
														, '<strong>&nbsp;', '&nbsp;</strong>'
														, '<strong>&nbsp;', '&nbsp;</strong>'
											   )
						//, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_info_outline', 'position' => 'left', 'icon_img' => '' )
						, 'class'       => 'wpbc_help_text'
						, 'style'       => 'height:auto;'
						//, 'hint' 	=> array( 'title' => __('Filter bookings by booking dates' ,'booking') , 'position' => 'top' )
						, 'attr'        => array()
					);

	?><div class="ui_element"><?php

		wpbc_flex_addon( $params_addon );

	?></div><?php
	?><script type="text/javascript">

		jQuery(document).ready(function(){

			jQuery( '.wpbc_ajx_availability_container' ).on( 'wpbc_page_content_loaded', function ( event, ajx_data_arr, ajx_search_params, ajx_cleaned_params ){
				wpbc_blink_element('.wpbc_help_text', 4, 350);
				jQuery( '.wpbc_ajx_availability_container' ).off( 'wpbc_page_content_loaded' );
			} );

		} );
	</script>  <?php

}



////////////////////////////////////////////////////////////////////////////////
//   T e m p l a t e s    UI
////////////////////////////////////////////////////////////////////////////////

function wpbc_ajx_avy__ui__available_radio(){

		$booking_action = 'set_days_availability';

		$el_id = 'ui_btn_avy__' . $booking_action . '__available';

		//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

		wpbc_flex_vertical_color( array(	'vertical_line' => 'border-left: 4px solid #11be4c;' 	) );				// Green line

		?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id . '__outer_button'; ?>" style="padding-right: 8px;"><?php
			$params_radio = array(
							  'id'       => $el_id 				// HTML ID  of element
							, 'name'     => $booking_action
							, 'label'    => array( 'title' => __('Available' ,'booking') , 'position' => 'right' )
							, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
									, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'legend'   => ''					// aria-label parameter
									, 'value'    => 'available' 		// Some Value from options array that selected by default
									, 'selected' => !false 				// Selected or not
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
									//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() , 'in element:' , jQuery( this ) );"							// JavaScript code
								);
			wpbc_flex_radio( $params_radio );

		?></span><?php




}


function wpbc_ajx_avy__ui__unavailable_radio(){


		$booking_action = 'set_days_availability';

		$el_id = 'ui_btn_avy__' . $booking_action . '__unavailable';

		//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

		wpbc_flex_vertical_color( array(	'vertical_line' => 'border-left: 4px solid #e43939;' 	) );				// Green line

		?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id . '__outer_button'; ?>" style="padding-right: 8px;"><?php
			$params_radio = array(
							  'id'       => $el_id 				// HTML ID  of element
							, 'name'     => $booking_action
							, 'label'    => array( 'title' => __('Unavailable' ,'booking') , 'position' => 'right' )
							, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
									, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'legend'   => ''					// aria-label parameter
									, 'value'    => 'unavailable' 		// Some Value from options array that selected by default
									, 'selected' => !false 				// Selected or not
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
									//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() , 'in element:' , jQuery( this ) );"							// JavaScript code
								);
			wpbc_flex_radio( $params_radio );

		?></span><?php

}


function wpbc_ajx_avy__ui__availability_apply_btn(){

	$el_id = 'wpbc_availability_apply_btn';

	$params_button = array(
			  'type' => 'button'
			, 'title' => __( 'Apply', 'booking' )                     // Title of the button
			, 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
			, 'link' => 'javascript:void(0)'    // Direct link or skip  it
			, 'action' => //"console.log( 'ON CLICK:', jQuery( '[name=\"set_days_availability\"]:checked' ).val() , jQuery( 'textarea[id^=\"date_booking\"]' ).val() );"                    // Some JavaScript to execure, for example run  the function
						  "		wpbc_ajx_availability__send_request_with_params( {
															  'do_action': 'set_availability'
															, 'dates_availability':  jQuery( '[name=\"set_days_availability\"]:checked' ).val()
															, 'dates_selection':     jQuery( 'textarea[id^=\"date_booking\"]' ).val()
															, 'ui_clicked_element_id': '{$el_id}'
														} );
								wpbc_button_enable_loading_icon( this );
								wpbc_admin_show_message_processing( '' );
						  "
			, 'class' => 'wpbc_ui_button_primary'     				  // wpbc_ui_button  | wpbc_ui_button_primary
			//, 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
			, 'icon' 			   => array(
										'icon_font' => 'wpbc_icn_check', // 'wpbc_icn_check_circle_outline',
										'position'  => 'left',
										'icon_img'  => ''
									)
			, 'style' => ''                     // Any CSS class here
			, 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
			, 'attr' => array( 'id' => $el_id )
	);

	wpbc_flex_button( $params_button );
}



/**
 * Reset button - init to default options
 *
 */
function wpbc_avy__ui__toolbar_reset_button(){

    $params  =  array(
	    'type'             => 'button' ,
	    'title'            => __( 'Reset', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
	    'hint'             => array( 'title' => __( 'Reset selected options to default values', 'booking' ), 'position' => 'top' ),  	// Hint
	    'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
	    'action'           => "wpbc_ajx_availability__send_request_with_params( {
	    													'do_action': 'make_reset'
										} );
							   wpbc_button_enable_loading_icon( this );",																			// JavaScript
	    'icon' 			   => array(
									'icon_font' => 'wpbc_icn_settings_backup_restore', //'wpbc_icn_rotate_left',
									'position'  => 'left',
									'icon_img'  => ''
								),
	    'class'            => 'wpbc_ui_button',  																		// ''  | 'wpbc_ui_button_primary'
	    'style'            => '',																						// Any CSS class here
	    'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
	    'attr'             => array()
	);

	wpbc_flex_button( $params );

}



function wpbc_avy__ui__toolbar_erase_availability_button(){

		$booking_action = 'erase_availability';

		$el_id = 'ui_btn_' . $booking_action;

//		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
//			return false;
//		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Reset availability', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Remove all unavailable dates for this calendar', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to do this ?', 'booking' ) ) . "') ) {
										wpbc_ajx_availability__send_request_with_params( {
	    													'do_action':             '{$booking_action}',
	    													'ui_clicked_element_id': '{$el_id}'
										} );
							   			wpbc_button_enable_loading_icon( this ); 
									}" ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_close',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => 'wpbc_ui_button_danger',  																						// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
}