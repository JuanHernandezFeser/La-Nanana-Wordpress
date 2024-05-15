<?php /**
 * @version 1.1
 * @package Any
 * @category Toolbar. UI Elements for Admin Panel
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2022-05-07
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
function wpbc_ajx_bookings_toolbar( $escaped_search_request_params ) {

    wpbc_clear_div();

    //wpbc_toolbar_search_by_id_bookings();                                       // Search bookings by  ID - form  at the top  right side of the page

    // wpbc_toolbar_btn__view_mode();                                              //  Vertical Buttons		//FixIn: 9.8.15.2

    //  Toolbar ////////////////////////////////////////////////////////////////

	$default_param_values = wpbc_ajx_get__request_params__names_default( 'default' );

	$selected_tab = ( isset( $escaped_search_request_params['ui_usr__default_selected_toolbar'] ) )
						   ? $escaped_search_request_params['ui_usr__default_selected_toolbar']
						   : 	   	  $default_param_values['ui_usr__default_selected_toolbar'];

    ?><div id="toolbar_booking_listing" class="wpbc_ajx_toolbar"><?php

		// <editor-fold     defaultstate="collapsed"                        desc=" T O P    T A B s "  >

        wpbc_bs_toolbar_tabs_html_container_start();

            wpbc_bs_display_tab(   array(
                                                'title'         => '&nbsp;' . __('Filters', 'booking')
												, 'hint' => array( 'title' => __('Filter bookings' ,'booking') , 'position' => 'top' )
                                                , 'onclick'     =>  "jQuery('.ui_container_toolbar').hide();"
                                                                    . "jQuery('.ui_container_filters').show();"
                                                                    . "jQuery('.wpbc_ajx_toolbar .nav-tab').removeClass('nav-tab-active');"		//FixIn: 9.8.15.2
                                                                    . "jQuery(this).addClass('nav-tab-active');"
                                                                    . "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
                                                                    . "jQuery('.nav-tab-active i').addClass('icon-white');"
																	/**
																	 * It will save such changes, and if we have selected bookings, then deselect them
																	 */
																 	//		. "wpbc_ajx_booking_send_search_request_with_params( { 'ui_usr__default_selected_toolbar': 'filters' });"
																	/**
																	 * It will save changes with NEXT search request, but not immediately
                                                                     * it is handy, in case if we have selected bookings,
                                                                     * we will not lose selection.
																	 */
																	. "wpbc_ajx_booking_listing.search_set_param( 'ui_usr__default_selected_toolbar', 'filters' );"
                                                , 'font_icon'   => 'wpbc_icn_published_with_changes' 					//FixIn: 9.0.1.4	'glyphicon glyphicon-random'
                                                , 'default'     => ( $selected_tab == 'filters' ) ? true : false
                                ) );
            wpbc_bs_display_tab(   array(
                                                'title'         => __('Actions', 'booking')
												, 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
                                                , 'onclick'     =>  "jQuery('.ui_container_toolbar').hide();"
                                                                    . "jQuery('.ui_container_actions').show();"
                                                                    . "jQuery('.wpbc_ajx_toolbar .nav-tab').removeClass('nav-tab-active');"		//FixIn: 9.8.15.2
                                                                    . "jQuery(this).addClass('nav-tab-active');"
                                                                    . "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
                                                                    . "jQuery('.nav-tab-active i').addClass('icon-white');"
																	/**
																	 * It will save such changes, and if we have selected bookings, then deselect them
																	 */
																	//		. "wpbc_ajx_booking_send_search_request_with_params( { 'ui_usr__default_selected_toolbar': 'actions' });"
																	/**
																	 * It will save changes with NEXT search request, but not immediately
                                                                     * it is handy, in case if we have selected bookings,
                                                                     * we will not lose selection.
																	 */
																    . "wpbc_ajx_booking_listing.search_set_param( 'ui_usr__default_selected_toolbar', 'actions' );"
                                                , 'font_icon'   => 'wpbc_icn_adjust'										//FixIn: 9.0.1.4	'glyphicon glyphicon-fire'
                                                , 'default'     => ( $selected_tab == 'actions' ) ? true : false

                                ) );

			if ( class_exists( 'wpdev_bk_personal' ) ) {        //FixIn: 9.6.1.5
	            wpbc_bs_display_tab(   array(
	                                                'title'         => __('Options', 'booking')								//FixIn: 9.5.4.3
	                                                , 'hint' => array( 'title' => __('User Options' ,'booking') , 'position' => 'top' )
	                                                , 'onclick'     =>  "jQuery('.ui_container_toolbar').hide();"
	                                                                    . "jQuery('.ui_container_options').show();"
	                                                                    . "jQuery('.wpbc_ajx_toolbar .nav-tab').removeClass('nav-tab-active');"		//FixIn: 9.8.15.2
	                                                                    . "jQuery(this).addClass('nav-tab-active');"
	                                                                    . "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
	                                                                    . "jQuery('.nav-tab-active i').addClass('icon-white');"
																		/**
																		 * It will save such changes, and if we have selected bookings, then deselect them
																		 */
																		// 		. "wpbc_ajx_booking_send_search_request_with_params( { 'ui_usr__default_selected_toolbar': 'options' });"
																		/**
																		 * It will save changes with NEXT search request, but not immediately
	                                                                     * it is handy, in case if we have selected bookings,
	                                                                     * we will not lose selection.
																		 */
																	    . "wpbc_ajx_booking_listing.search_set_param( 'ui_usr__default_selected_toolbar', 'options' );"
	                                                , 'font_icon'   => 'wpbc_icn_tune'										//FixIn: 9.0.1.4	'glyphicon glyphicon-fire'
	                                                , 'default'     => ( $selected_tab == 'options' ) ? true : false
													//, 'position' => 'right'

	                                ) );
			}
			wpbc_bs_dropdown_menu_help();

        wpbc_bs_toolbar_tabs_html_container_end();

		// </editor-fold>

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Filters
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		?><div><?php //Required for bottom border radius in container

			?><div class="ui_container    ui_container_toolbar		ui_container_filters    ui_container_filter_row_1" style="<?php echo ( $selected_tab == 'filters' ) ? 'display: flex' : 'display: none' ?>;"><?php

				?><div class="ui_group    ui_group__dates_status    ui_search_fields_group_1"><?php  						//	array( 'class' => 'group_nowrap' )	// Elements at Several or One Line

					wpbc_ajx__ui__booked_dates( $escaped_search_request_params, $default_param_values );

					wpbc_ajx__ui__booking_status( $escaped_search_request_params, $default_param_values );

				?></div><?php

				?><div class="ui_group    ui_group__keyword    ui_search_fields_group_2"><?php  							//	array( 'class' => 'group_nowrap' )	// Elements at Several or One Line

					wpbc_ajx_toolbar_keyword_search( $escaped_search_request_params, $default_param_values );

					wpbc_ajx_toolbar_clear_keyword_search( $escaped_search_request_params, $default_param_values );		//FixIn: 9.7.2.3

					wpbc_ajx_toolbar_force_reload_button( $escaped_search_request_params, $default_param_values );

				?></div><?php

			?></div><?php


			?><div class="ui_container    ui_container_toolbar		ui_container_small    ui_container_filters    ui_container_filter_row_2" style="<?php echo ( $selected_tab == 'filters' ) ? 'display: flex' : 'display: none' ?>;"><?php

				?><div class="ui_group    ui_group__statuses    ui_search_fields_group_1"><?php  					//	array( 'class' => 'group_nowrap' )	// Elements at Several or One Line

					wpbc_ajx__ui__booking_resources( $escaped_search_request_params, $default_param_values );

					wpbc_ajx__ui__existing_or_trash( $escaped_search_request_params, $default_param_values );

					wpbc_ajx__ui__all_or_new( $escaped_search_request_params, $default_param_values );

					wpbc_ajx__ui__creation_date( $escaped_search_request_params, $default_param_values );

					wpbc_ajx__ui__payment_status( $escaped_search_request_params, $default_param_values );

					wpbc_ajx__ui__cost_min_max( $escaped_search_request_params, $default_param_values );

					wpbc_ajx_toolbar_reset_button( $escaped_search_request_params, $default_param_values );

				?></div><?php

			?></div><?php

		?></div><?php //Required for bottom border radius in container


		// <editor-fold     defaultstate="collapsed"                        desc="   A c t i o n s    t o o l b a r  "  >

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Actions
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		?><div class="ui_container    ui_container_toolbar		ui_container_small    ui_container_actions    ui_container_actions_row_1" style="<?php echo ( $selected_tab == 'actions' ) ? 'display: flex' : 'display: none' ?>;"><?php

			?><div class="ui_group"><?php

				//	Approve		//	Pending
				?><div class="ui_element hide_button_if_no_selection"><?php
					wpbc_ajx__ui__action_button__approve( $escaped_search_request_params );
					wpbc_ajx__ui__action_button__pending( $escaped_search_request_params );
				?></div><?php

				//	Trash / Reject 		//	Restore 		//	Delete
				?><div class="ui_element hide_button_if_no_selection"><?php
					wpbc_ajx__ui__action_button__trash( $escaped_search_request_params );
					wpbc_ajx__ui__action_button__restore( $escaped_search_request_params );
					wpbc_ajx__ui__action_button__delete( $escaped_search_request_params );
					wpbc_ajx__ui__action_text__delete_reason( $escaped_search_request_params );
				?></div><?php

				//	Empty Trash
				?><div class="ui_element"><?php
					wpbc_ajx__ui__action_button__empty_trash( $escaped_search_request_params );
				?></div><?php

				//	Read All 		//	Read 		//	Unread
				?><div class="ui_element"><?php
					wpbc_ajx__ui__action_button__readall( $escaped_search_request_params );
					wpbc_ajx__ui__action_button__read( $escaped_search_request_params );
					wpbc_ajx__ui__action_button__unread( $escaped_search_request_params );
				?></div><?php

				//	Print
				?><div class="ui_element"><?php
					wpbc_ajx__ui__action_button__print( $escaped_search_request_params );
				?></div><?php

				//	Import
				?><div class="ui_element"><?php
					wpbc_ajx__ui__action_button__import( $escaped_search_request_params );
				?></div><?php

				//	Export page to CSV 		//	Export all pages to CSV
				?><div class="ui_element"><?php
					wpbc_ajx__ui__action_button__export_csv( $escaped_search_request_params );
					// wpbc_ajx__ui__action_button__export_csv_page( $escaped_search_request_params );
					// wpbc_ajx__ui__action_button__export_csv_all( $escaped_search_request_params );
				?></div><?php

			?></div><?php

		?></div><?php
		// </editor-fold>


		// <editor-fold     defaultstate="collapsed"                        desc="   O p t i o n s    t o o l b a r  "  >

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Options
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ( class_exists( 'wpdev_bk_personal' ) ) {        //FixIn: 9.6.1.5
		?><div class="ui_container    ui_container_toolbar		ui_container_small    ui_container_options    ui_container_options_row_1" style="<?php echo ( $selected_tab == 'options' ) ? 'display: flex' : 'display: none' ?>;"><?php

			?><div class="ui_group"><?php

				/*
				//	Is send Emails              //FixIn: 9.6.1.5
				?><div class="ui_element"><?php
					wpbc_ajx__ui__options_checkbox__send_emails( $escaped_search_request_params, $default_param_values );
				?></div><?php
				*/

				if ( class_exists( 'wpdev_bk_personal' ) ) {
					//	Is Expand Notes
					?><div class="ui_element"><?php

						wpbc_ajx__ui__options_checkbox__is_expand_remarks( $escaped_search_request_params, $default_param_values );

					?></div><?php
				}

			?></div><?php

		?></div><?php
		}
		// </editor-fold>

	?></div><?php
}

/**
 * Sorting
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__booking_sorting( $escaped_search_request_params, $defaults ){

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
						, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_swap_vert', 'position' => 'right', 'icon_img' => '' )
						, 'class'       => 'wpbc_ui_button inactive'
						, 'style'       => ''
						, 'attr'        => array()
					);

	$select_options = array (
											'booking_id__asc'  => __( 'ID', 'booking' ) . ' <i class="menu_icon icon-1x wpbc-bi-arrow-up-short"></i>',
											'booking_id__desc' => __( 'ID', 'booking' ) . ' <i class="menu_icon icon-1x wpbc-bi-arrow-down-short"></i>',
											'divider1'         => array( 'type' => 'html', 'html' => '<hr/>' ),
											'dates__asc'       => __( 'Dates', 'booking' ) . ' <i class="menu_icon icon-1x wpbc-bi-arrow-up-short"></i>',
											'dates__desc'      => __( 'Dates', 'booking' ) . ' <i class="menu_icon icon-1x wpbc-bi-arrow-down-short"></i>',
									 );
	if ( class_exists( 'wpdev_bk_personal' ) ) {
		$select_options['divider2']       = array( 'type' => 'html', 'html' => '<hr/>' );
		$select_options['resource__asc']  = __( 'Resource', 'booking' ) . ' <i class="menu_icon icon-1x wpbc-bi-arrow-up-short"></i>';
		$select_options['resource__desc'] = __( 'Resource', 'booking' ) . ' <i class="menu_icon icon-1x wpbc-bi-arrow-down-short"></i>';

	}
	if ( class_exists( 'wpdev_bk_biz_s' ) ) {
		$select_options['divider3']   = array( 'type' => 'html', 'html' => '<hr/>' );
		$select_options['cost__asc']  = __( 'Cost', 'booking' ) . ' <i class="menu_icon icon-1x wpbc-bi-arrow-up-short"></i>';
		$select_options['cost__desc'] = __( 'Cost', 'booking' ) . ' <i class="menu_icon icon-1x wpbc-bi-arrow-down-short"></i>';
	}

    $params = array(
					  'id'      => 'wh_sort'
					, 'default' => isset( $escaped_search_request_params['wh_sort'] ) ? $escaped_search_request_params['wh_sort'] : $defaults['wh_sort']
                    , 'label' 	=> ''//__('Sort by', 'booking') . ':'
                    , 'title' 	=> __('Sort by', 'booking')
					, 'hint' 	=> array( 'title' => __('Sort bookings by' ,'booking') , 'position' => 'top' )
					, 'li_options' => $select_options
                );

	?><div class="wpbc_ajx_toolbar wpbc_buttons_row">
		<div class="ui_container ui_container_small">
			<div class="ui_group">
				<div class="ui_element"><?php

					//wpbc_flex_addon( $params_addon );

					wpbc_flex_dropdown( $params );

	?></div></div></div></div><?php
}


/**
 * Is email  sending option - loacted inside of the page -- under the toolbar
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__booking__no_toolbar__is_email_sending( $escaped_search_request_params, $defaults ) {             //FixIn: 9.6.1.5

	?><div class="wpbc_ajx_toolbar wpbc_no_borders wpbc_not_toolbar_is_send_emails"><?php
		?><div class="ui_container ui_container_micro"><?php
			?><div class="ui_group"><?php
				?><div class="ui_element"><?php
					wpbc_ajx__ui__options_checkbox__send_emails( $escaped_search_request_params, $defaults );
				?></div><?php
			?></div><?php
		?></div><?php
	?></div><?php
}


// <editor-fold     defaultstate="collapsed"                        desc=" T o o l b a r       F i l t e r       B u t t o n s "  >

////////////////////////////////////////////////////////////////////////////////
/// 1st row
////////////////////////////////////////////////////////////////////////////////

/**
 * Booked dates
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__booked_dates( $escaped_search_request_params, $defaults ){

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
						, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_event', 'position' => 'right', 'icon_img' => '' )
						, 'class'       => 'wpbc_ui_button inactive'
						, 'style'       => ''
						, 'hint' 	=> array( 'title' => __('Filter bookings by booking dates' ,'booking') , 'position' => 'top' )
						, 'attr'        => array()
					);

    $dates_interval = array(
                                1 => '1' . ' ' . __('day' ,'booking')
                              , 2 => '2' . ' ' . __('days' ,'booking')
                              , 3 => '3' . ' ' . __('days' ,'booking')
                              , 4 => '4' . ' ' . __('days' ,'booking')
                              , 5 => '5' . ' ' . __('days' ,'booking')
                              , 6 => '6' . ' ' . __('days' ,'booking')
                              , 7 => '1' . ' ' . __('week' ,'booking')
                              , 14 => '2' . ' ' . __('weeks' ,'booking')
                              , 30 => '1' . ' ' . __('month' ,'booking')
                              , 60 => '2' . ' ' . __('months' ,'booking')
                              , 90 => '3' . ' ' . __('months' ,'booking')
                              , 183 => '6' . ' ' . __('months' ,'booking')
                              , 365 => '1' . ' ' . __('Year' ,'booking')
                        );

	$request_input_el_default = array(
		'wh_booking_date'             => isset( $escaped_search_request_params['wh_booking_date'] ) ? $escaped_search_request_params['wh_booking_date'] : $defaults['wh_booking_date'],
		'ui_wh_booking_date_radio'    => isset( $escaped_search_request_params['ui_wh_booking_date_radio'] ) ? $escaped_search_request_params['ui_wh_booking_date_radio'] : $defaults['ui_wh_booking_date_radio'],
		'ui_wh_booking_date_next'     => isset( $escaped_search_request_params['ui_wh_booking_date_next'] ) ? $escaped_search_request_params['ui_wh_booking_date_next'] : $defaults['ui_wh_booking_date_next'],
		'ui_wh_booking_date_prior'    => isset( $escaped_search_request_params['ui_wh_booking_date_prior'] ) ? $escaped_search_request_params['ui_wh_booking_date_prior'] : $defaults['ui_wh_booking_date_prior'],
		'ui_wh_booking_date_checkin'  => isset( $escaped_search_request_params['ui_wh_booking_date_checkin'] ) ? $escaped_search_request_params['ui_wh_booking_date_checkin'] : $defaults['ui_wh_booking_date_checkin'],
		'ui_wh_booking_date_checkout' => isset( $escaped_search_request_params['ui_wh_booking_date_checkout'] ) ? $escaped_search_request_params['ui_wh_booking_date_checkout'] : $defaults['ui_wh_booking_date_checkout']
	);

	$options = array (
						// 'header2'   => array( 'type' => 'header', 'title' => __( 'Complex Days', 'booking' ) ),
						// 'disabled1' => array( 'type' => 'simple', 'value' => '19', 'title' => __( 'This is option was disabled', 'booking' ), 'disabled' => true ),
						//todo: 2023-06-10
					    'fixed' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'selected_options_value' => array( 1 => array( 'value' ), 4 => array( 'value' ), 7 => array( 'value' ) ),												//  4 => array( 'value' )  			-->   $complex_option['input_options'][4]['value']
								'selected_options_title' => array( 1 => array( 'label', 'title' ), 'text1' => ': ', 4 => array( 'value' ), 'text2' => ' - ' ,7 => array( 'value' ) ),	//  1 => array( 'label', 'title' )  -->   $complex_option['input_options'][1]['label'][ 'title' ]
								'style' => 'min-width:290px',
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex:1 1 100%;margin-top:5px;display:none;">' )
													, array(
															'type' => 'radio'

														, 'id'       => 'ui_wh_booking_date_radio_3' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_radio'
														, 'label'    => array( 'title' => __('Dates' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''					// aria-label parameter
														, 'value'    => '6' 		// Some Value from optins array that selected by default
														, 'selected' => ( $request_input_el_default[ 'ui_wh_booking_date_radio' ] == '6' ) ? true : false 				// Selected or not
														//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 4px 4px 0;flex: 0 1 90px;order: 2;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_booking_date_checkin' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_checkin'
														, 'label'    => ''//__('Check-in' ,'booking')
														, 'style'    => 'width:100%;' 					// CSS of select element
														, 'class'    => 'wpdevbk-filters-section-calendar' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('Check-in' ,'booking')
														, 'value'    => $request_input_el_default[ 'ui_wh_booking_date_checkin'] 	// Some Value from optins array that selected by default
//														, 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_3').prop('checked', true);"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 0 4px 4px;flex: 0 1 90px;order: 4;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_booking_date_checkout' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_checkout'
														, 'label'    => ''//__('Check-out' ,'booking')
														, 'style'    => 'width:100%;' 					// CSS of select element
														, 'class'    => 'wpdevbk-filters-section-calendar' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('Check-out' ,'booking')
														, 'value'    => $request_input_el_default[ 'ui_wh_booking_date_checkout']  		// Some Value from optins array that selected by default
//														, 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_3').prop('checked', true);"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													//FixIn: 9.7.2.2
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding:0;flex: 0 1 14px;order: 2;">' )
													, array(
															'type' => 'button'
														  , 'title' => ''					  // Title of the button
														  , 'hint' => ''
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "jQuery('#ui_wh_booking_date_checkin').val('');jQuery('#ui_wh_booking_date_checkout').val('');"
														  , 'class' => 'wpbc_button_as_icon'     // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => array( 'icon_font' => 'wpbc_icn_horizontal_rule', 'position' => 'left', 'icon_img' => '' )
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => 'padding: 0px;font-size: 20px;color: #555;'                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )

													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 0 4px 4px;flex: 0 1 35px;order: 5;">' )
													, array(
															'type' => 'button'
														  , 'title' => ''					  // Title of the button
														  , 'hint' => array( 'title' => __('Apply' ,'booking') , 'position' => 'top' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "jQuery('#ui_wh_booking_date_radio_3').prop('checked', true);".
																		"wpbc_ui_dropdown_apply_click( { 
														  												'dropdown_id'		 : 'wh_booking_date', 
														  												'dropdown_radio_name': 'ui_wh_booking_date_radio' 
																									} );"				// JavaScript code
														  , 'class' => 'wpbc_ui_button_primary'     // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => array( 'icon_font' => 'wpbc_icn_arrow_forward', 'position' => 'left', 'icon_img' => '' )
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => 'padding:5px 7px;'                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px;flex: 0 1 20px;margin-left: auto;margin-right:-5px;order: 6;">' )
													, array(
															'type' => 'button'
														  , 'title' => ''					  // Title of the button
														  , 'hint' => array( 'title' => __('Reset check in/out dates filter' ,'booking') , 'position' => 'top' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' =>"if ( ( jQuery('#ui_wh_booking_date_checkin').val() == '') && ( jQuery('#ui_wh_booking_date_checkout').val() == '') ) { wpbc_field_highlight( '#ui_wh_booking_date_checkin' ); wpbc_field_highlight( '#ui_wh_booking_date_checkout' );  return false; }"
															 		 . "jQuery('#ui_wh_booking_date_checkin').val('');jQuery('#ui_wh_booking_date_checkout').val('');"
														  , 'class' => 'wpbc_button_as_icon'     // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => array( 'icon_font' => 'wpbc_icn_close', 'position' => 'left', 'icon_img' => '' )
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => 'padding-left: 0;padding-right: 0;'                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )

											)
						),
						'divider4' => array( 'type' => 'html', 'html' => '<hr/>' ),


						'0' => __( 'Current dates', 'booking' ),
						'1' => __( 'Today', 'booking' ),
						'2' => __( 'Previous dates', 'booking' ),
						'3' => __( 'All dates', 'booking' ),

						'divider1' => array( 'type' => 'html', 'html' => '<hr/>' ),

						'9' => __( 'Today check in/out', 'booking' ),
						'10' => __( 'Check in - Today', 'booking' ),
						'11' => __( 'Check out - Today', 'booking' ),
						'7' => __( 'Check in - Tomorrow', 'booking' ),
						'8' => __( 'Check out - Tomorrow', 'booking' ),

						'divider2' => array( 'type' => 'html', 'html' => '<hr/>' ),

						// Next  [ '4', '10' ]		- radio button (if selected)  value '4'    and select-box with  selected value   '10'
					    'next' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								// recheck if LI selected: 	 $options['next']['selected_options_value'] == $params['default],  e.g. ~  [ '4', '10' ]
								'selected_options_value' => array(
																	1 => array( 'value' ),					//  $options['next']['input_options'][ 1 ]['value']				'4'
																	4 => array( 'value' ) 					//  $options['next']['input_options'][ 4 ]['value']				'10'
																),
								// Get selected Title, for dropdown if $options['next'] selected
								'selected_options_title' => array(
																	1 => array( 'label', 'title' ),			// $options['next']['input_options'][ 1 ]['label'][ 'title' ]		'Next'
																	'text1' => ': ',						// if key 'text1' not exist in ['input_options'], then it text	': '
																	4 => array( 'options', $request_input_el_default['ui_wh_booking_date_next'] )					// 	'10 days'
																),
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element">' )
													, array(	 														// Default options from simple input element, like: wpbc_flex_radio()
														  'type' => 'radio'
														, 'id'       => 'ui_wh_booking_date_radio_1' 					// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_radio'
														, 'label'    => array( 'title' => __('Next' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 												// CSS of select element
														, 'class'    => '' 												// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 										// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''												// aria-label parameter
														, 'value'    => '4'
														, 'selected' => ( $request_input_el_default[ 'ui_wh_booking_date_radio' ] == '4' ) ? true : false 				// Selected or not
														//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"	// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"	// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element">' )
													, array(
															'type' => 'select'
														  , 'attr' => array()
														  , 'name' => 'ui_wh_booking_date_next'
														  , 'id' => 'ui_wh_booking_date_next'
														  , 'options' => $dates_interval
														  , 'value' => $request_input_el_default[ 'ui_wh_booking_date_next']
														  , 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_1').prop('checked', true);"									// JavaScript code
														  //, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"	// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),

						// Prior  [ '5', '10' ]
						'prior' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'style' => 'min-width: 244px;',
								'selected_options_value' => array( 1 => array( 'value' ), 4 => array( 'value' ) ),		//  4 => array( 'value' )  			-->   $complex_option['input_options'][4]['value']
								'selected_options_title' => array(    1 => array( 'label', 'title' )
																	, 'text1' => ': '
																	, 4 => array( 'options' , $request_input_el_default[ 'ui_wh_booking_date_prior'] )
																 ), 													//  1 => array( 'label', 'title' )  --> $complex_option['input_options'][1]['label'][ 'title' ]
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element">' )
													, array(
															'type' => 'radio'

														, 'id'       => 'ui_wh_booking_date_radio_2' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_radio'
														, 'label'    => array( 'title' => __('Prior' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''					// aria-label parameter
														, 'value'    => '5' 		// Some Value from optins array that selected by default
														, 'selected' => ( $request_input_el_default[ 'ui_wh_booking_date_radio' ] == '5' ) ? true : false 				// Selected or not
														//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element">' )
													, array(
															'type' => 'select'
														  , 'attr' => array()
														  , 'name' => 'ui_wh_booking_date_prior'
														  , 'id' => 'ui_wh_booking_date_prior'
														  , 'options' => $dates_interval
														  , 'value' => $request_input_el_default[ 'ui_wh_booking_date_prior']
														  , 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_2').prop('checked', true);"					// JavaScript code
														  //, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
							),
/* //todo: 2023-06-10
						// Fixed [ '6', '', '2022-05-21']
					    'fixed' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'selected_options_value' => array( 1 => array( 'value' ), 4 => array( 'value' ), 7 => array( 'value' ) ),												//  4 => array( 'value' )  			-->   $complex_option['input_options'][4]['value']
								'selected_options_title' => array( 1 => array( 'label', 'title' ), 'text1' => ': ', 4 => array( 'value' ), 'text2' => ' - ' ,7 => array( 'value' ) ),	//  1 => array( 'label', 'title' )  -->   $complex_option['input_options'][1]['label'][ 'title' ]
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex:1 1 100%;margin-top:5px;">' )
													, array(
															'type' => 'radio'

														, 'id'       => 'ui_wh_booking_date_radio_3' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_radio'
														, 'label'    => array( 'title' => __('Dates' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''					// aria-label parameter
														, 'value'    => '6' 		// Some Value from optins array that selected by default
														, 'selected' => ( $request_input_el_default[ 'ui_wh_booking_date_radio' ] == '6' ) ? true : false 				// Selected or not
														//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 4px 4px 0;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_booking_date_checkin' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_checkin'
														, 'label'    => ''//__('Check-in' ,'booking')
														, 'style'    => 'width:100%;' 					// CSS of select element
														, 'class'    => 'wpdevbk-filters-section-calendar' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('Check-in' ,'booking')
														, 'value'    => $request_input_el_default[ 'ui_wh_booking_date_checkin'] 	// Some Value from optins array that selected by default
														, 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_3').prop('checked', true);"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 0 4px 4px;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_booking_date_checkout' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_checkout'
														, 'label'    => ''//__('Check-out' ,'booking')
														, 'style'    => 'width:100%;' 					// CSS of select element
														, 'class'    => 'wpdevbk-filters-section-calendar' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('Check-out' ,'booking')
														, 'value'    => $request_input_el_default[ 'ui_wh_booking_date_checkout']  		// Some Value from optins array that selected by default
														, 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_3').prop('checked', true);"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),
*/
						'divider3' => array( 'type' => 'html', 'html' => '<hr/>' ),

					 	// Buttons
					    'buttons1' =>  array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'style' => 'justify-content: flex-end;',
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex: 0 1 auto;margin: 0;">' )
													, array(
															'type' => 'button'
														  , 'title' => __( 'Apply', 'booking' )                     										// Title of the button
														  , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "wpbc_ui_dropdown_apply_click( { 
														  												'dropdown_id'		 : 'wh_booking_date', 
														  												'dropdown_radio_name': 'ui_wh_booking_date_radio' 
																									} );"				// JavaScript code
														  , 'class' => 'wpbc_ui_button_primary'     // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => ''
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => ''                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex: 0 1 auto;margin: 0 0 0 1em;">' )
													, array(
															'type' => 'button'
														  , 'title' => __( 'Close', 'booking' )                     // Title of the button
														  , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "wpbc_ui_dropdown_close_click( 'wh_booking_date' );"                    // JavaScript code
														  , 'class' => ''     				  // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => ''
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => ''                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),
				);

    $params = array(
					  'id'      => 'wh_booking_date'
					, 'default' => $request_input_el_default[ 'wh_booking_date' ]
                    , 'label' 	=> ''//__('Approve 1', 'booking') . ':'
                    , 'title' 	=> ''//__('Approve 2', 'booking')
					, 'hint' 	=> array( 'title' => __('Filter bookings by booking dates' ,'booking') , 'position' => 'top' )
					, 'align' 	=> 'left'
					, 'li_options' => $options
                );

	?><div class="ui_element ui_nowrap"><?php

		wpbc_flex_addon( $params_addon );

		wpbc_flex_dropdown( $params );

	?></div><?php
}

/**
 * Approved | Pending | All
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__booking_status( $escaped_search_request_params, $defaults ){

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
						, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_done_all', 'position' => 'right', 'icon_img' => '' )
						, 'class'       => 'wpbc_ui_button inactive'
						, 'style'       => ''
						, 'attr'        => array()
					);

    $params = array(
					  'id'      => 'wh_approved'
					, 'default' => isset( $escaped_search_request_params['wh_approved'] ) ? $escaped_search_request_params['wh_approved'] : $defaults['wh_approved']
                    , 'label' 	=> ''//__('Status', 'booking') . ':'
                    , 'title' 	=> __('Status', 'booking')
					, 'hint' 	=> array( 'title' => __('Filter bookings by booking status' ,'booking') , 'position' => 'top' )
					, 'li_options' => array (
											'0' => __( 'Pending', 'booking' ),
											'1' => __( 'Approved', 'booking' ),
											'divider1' => array( 'type' => 'html', 'html' => '<hr/>' ),
											// 'header1' => array( 'type' => 'header', 'title' => __( 'Default', 'booking' ) ),
											'any' => array(
														'type'     => 'simple',
														'value'    => '',
														// 'disabled' => true,
														'title'    => __( 'Any', 'booking' )
											),
									 )
                );

	?><div class="ui_element ui_nowrap"><?php

		//wpbc_flex_addon( $params_addon );

		wpbc_flex_dropdown( $params );

	?></div><?php
}

/**
 * Keywords
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
*/
function wpbc_ajx_toolbar_keyword_search( $escaped_search_request_params, $defaults ){

	$el_id = 'wpbc_search_field';

	$default_value = '';

	// Old way of searching booking ID
	if ( ! empty( $_REQUEST['wh_booking_id'] ) ) {
		$wh_booking_id = intval( $_REQUEST['wh_booking_id'] );
		if ( $wh_booking_id > 0 ) {
			$_REQUEST['overwrite'] = 1;
			$_REQUEST['keyword']   = 'id:' . $wh_booking_id;
		}
	}

	if ( ( ! empty( $_REQUEST['overwrite'] ) ) && ( ! empty( $_REQUEST['keyword'] ) ) ) {

		// Searching for booking(s)  from URL: http://beta/wp-admin/admin.php?page=wpbc&view_mode=vm_booking_listing&keyword=id:2&overwrite=1

		$default_value = wpbc_sanitize_text( $_REQUEST['keyword'] );

		?><script type="text/javascript">
			jQuery( document ).ready( function (){
				// setTimeout( function () {
				// 	wpbc_ajx_booking_listing.search_set_param( 'wh_booking_type', [0] );
				// }, 950 );
				wpbc_ajx_booking_searching_after_few_seconds( '#<?php echo $el_id; ?>', 1000 ); 							// Immediate search after 0.5 second
			} );
		</script><?php
	}

    $params = array(
					'type'          => 'text'
					, 'id'          => $el_id
					, 'name'        => $el_id
					, 'label'       => ''
					, 'disabled'    => false
					, 'class'       => ''
					, 'style'       => ''
					, 'placeholder' => __( 'Enter keyword to search...', 'booking' )
					, 'attr'        => array()
					, 'value' 		=> $default_value
					, 'onfocus' 	=> ''
    );
	?><div class="ui_element"><?php
		wpbc_flex_text( $params );
	?></div><?php
}


//FixIn: 9.7.2.3
function wpbc_ajx_toolbar_clear_keyword_search( $escaped_search_request_params, $defaults ){

    $params  =  array(
	    'type'             => 'button' ,
	    'title'            => '',//__( 'Reset', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
	    'hint'             => array( 'title' => __( 'Reset keyword text field', 'booking' ), 'position' => 'top' ),  	// Hint
	    'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
	    'action'           => "jQuery( '#wpbc_search_field').val(''); 
							   wpbc_ajx_booking_send_search_request_with_params( {
										'keyword'  : '',
										'page_num': 1
									} );
							       ",																					// JavaScript
	    'icon' 			   => array(
									'icon_font' => 'wpbc_icn_close', //'wpbc_icn_rotate_left',
									'position'  => 'left',
									'icon_img'  => ''
								),
	    'class'            => 'wpbc_button_as_icon',  																	// ''  | 'wpbc_ui_button_primary'
	    'style'            => '', 																						// Any CSS class here
	    'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
	    'attr'             => array()
	);

	?><div class="ui_element" style="flex: 0 1 auto;margin-left: -50px;z-index: 1;"><?php
		wpbc_flex_button( $params );
	?></div><?php
}

/**
 * Reset button - init default filter options
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx_toolbar_reset_button( $escaped_search_request_params, $defaults ){

    $params  =  array(
	    'type'             => 'button' ,
	    'title'            => __( 'Reset', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
	    'hint'             => array( 'title' => __( 'Reset search filter and user options to default values', 'booking' ) . '. ' . __( 'Show all bookings.', 'booking' ), 'position' => 'top' ),  	// Hint
	    'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
	    'action'           => "wpbc_ajx_booking_send_search_request_with_params( {
															'ui_reset': 'make_reset',
															'page_num': 1
										} );",																			// JavaScript
	    'icon' 			   => array(
									'icon_font' => 'wpbc_icn_settings_backup_restore', //'wpbc_icn_rotate_left',
									'position'  => 'left',
									'icon_img'  => ''
								),
	    'class'            => 'wpbc_ui_button',  																		// ''  | 'wpbc_ui_button_primary'
	    'style'            => '',// 'color: #ef6500;', 						//FixIn: 9.7.2.1								// Any CSS class here
	    'mobile_show_text' => !true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
	    'attr'             => array()
	);

	?><div class="ui_element" style="margin-left: auto;"><?php
		wpbc_flex_button( $params );
	?></div><?php
}

////////////////////////////////////////////////////////////////////////////////
/// 2nd row
////////////////////////////////////////////////////////////////////////////////

/**
 * Booking resources
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__booking_resources( $escaped_search_request_params, $defaults ){

	if ( ! class_exists( 'wpdev_bk_personal' ) ) {
		return false;
	}

	$params_button = array(
						'type'             => 'button' ,
						'title'            => '',//__( 'Reset', 'booking' ) . '&nbsp;&nbsp;',  							// Title of the button
						'hint'             => array( 'title' => __( 'Remove all booking resources', 'booking' ), 'position' => 'top' ),  	// Hint
						'link'             => 'javascript:void(0)',  													// Direct link or skip  it
						'action'           => "remove_all_options_from_choozen('#wh_booking_type');",					// JavaScript
						'icon' 			   => array(
													'icon_font' => 'wpbc_icn_close',
													'position'  => 'left',
													'icon_img'  => ''
												),
						'class'            => 'wpbc_ui_button',  														// ''  | 'wpbc_ui_button_primary'
						'style'            => '',																		// Any CSS class here
						'mobile_show_text' => true,																		// Show  or hide text,  when viewing on Mobile devices (small window size).
						'attr'             => array( 'id' => 'wpbc_booking_listing_reload_button')
					);

	/**
	 * result = {array} [12]
							 1 = {array} [10]
												  booking_type_id = "1"
												  title = "Standard"
												  users = "3"
												  import = null
												  export = null
												  cost = "25"
												  default_form = "owner-custom-form-1"
												  prioritet = "2"
												  parent = "0"
												  visitors = "2"
							 2 = {array} [10]
												  booking_type_id = "2"
												  title = "Apartment#1"   ...
	 */
	$resources_sql_arr = wpbc_ajx_get_all_booking_resources_arr();

	/**
	 * $resources_arr = array( 			 linear_resources = {array} [12] 			single_or_parent = {array} [5]				child = {array} [2]  )
	 *
		$resources_arr = {array} [3]
										 linear_resources = {array} [12]
																			  1 = {array} [12]
																							   booking_type_id = "1"
																							   title = "Standard"
																							   users = "3"
																							   import = null
																							   export = null
																							   cost = "25"
																							   default_form = "owner-custom-form-1"
																							   prioritet = "2"
																							   parent = "0"
																							   visitors = "2"
																							   id = "1"
																							   count = {int} 5
																			  5 = {array} [12]
																							   booking_type_id = "5"
																							   title = "Standard-1"
																							   users = "1"
																							   import = null
	 */
	$resources_arr     = wpbc_ajx_arrange_booking_resources_arr( $resources_sql_arr );
	$style = '';
	$select_box_options = array();            //FixIn: 4.3.2.1
	if ( ! empty( $resources_arr ) ) {

		$linear_resources_arr = $resources_arr['linear_resources'];



		if ( count( $linear_resources_arr ) > 1 ) {

			$resources_id_arr   = array();
			foreach ( $linear_resources_arr as $bkr ) {
				$resources_id_arr[] = $bkr['id'];
			}

			$select_box_options[ /*implode( ',', $resources_id_arr )*/0 ] = array(
																			  'title' => __( 'All resources', 'booking' )
																			, 'attr'  => array( 'title' => '<strong>' . __( 'All resources', 'booking' ) . '</strong>' )
																			, 'style' => 'font-weight:600;'
																		);
		}

		foreach ( $linear_resources_arr as $bkr ) {

			$option_title = apply_bk_filter( 'wpdev_check_for_active_language', $bkr['title'] );

			if ( isset( $bkr['parent'] ) ) {
				if ( $bkr ['parent'] == 0 ) {
					$option_title = $option_title;
					$style = 'font-weight:600;';
				} else {
					$option_title = '&nbsp;&nbsp;&nbsp;' . $option_title;
					$style = 'font-weight:400;';
				}
			}
			$select_box_options[ $bkr ['id'] ] = array(
															  'title' => $option_title
															, 'attr'  => array( 'title' => $option_title )
															, 'style' => $style
														);
		}
	}



	$el_id = 'wh_booking_type';
 	$params_select = array(
						  'id'       => $el_id 												// HTML ID  of element
						, 'name'     => $el_id
		 				, 'label' => '' 	// __( 'Next Days', 'booking' )					// Label (optional)
						, 'style' => ''                     								// CSS of select element
						, 'class' => 'chzn-select'                     								// CSS Class of select element
						, 'multiple' => true
    					, 'attr' => array( 'data-placeholder' => __( 'Select booking resources', 'booking' ) )			// Any additional attributes, if this radio | checkbox element
						, 'disabled' => false
		 				, 'disabled_options' => array()     								// If some options disabled, then it has to list here
						, 'options' => $select_box_options

						, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
						//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
						//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"						// JavaScript code

					  );

	 // Booking resources
 	?><div class="ui_element ui_nowrap"><?php

		wpbc_flex_select( $params_select );

		wpbc_flex_button( $params_button );

	?></div><?php

	?><script type="text/javascript">
            function remove_all_options_from_choozen( selectbox_id ){
				jQuery( selectbox_id + ' option' ).prop( 'selected', false );    										// Disable selection in the real selectbox
				jQuery( selectbox_id ).trigger( 'chosen:updated' );            											// Remove all fields from the Choozen field	//FixIn: 8.7.9.9
				jQuery( selectbox_id ).trigger( 'change' );
            }

			if ( 'function' === typeof( jQuery("#<?php echo $el_id; ?>").chosen ) ) {

				jQuery( "#<?php echo $el_id; ?>" ).chosen( {no_results_text: "No results matched"} );

				jQuery("#<?php echo $el_id; ?>").chosen().on('change', function(va){									// Catch any selections in the Choozen

					if ( jQuery( "#<?php echo $el_id; ?>" ).val() != null ){
						//So we are having aready values
						jQuery.each( jQuery( "#<?php echo $el_id; ?>" ).val(), function ( index, value ){

							if ( (value.indexOf( ',' ) > 0) || ('0' === value) ){ 															// Ok we are have array with  all booking resources ID

								// Disable selection in the real selectbox
								jQuery( '#<?php echo $el_id; ?>' + ' option' ).removeAttr( 'selected' );

								// Select "All resources" option in real selectbox
								jQuery( '#<?php echo $el_id; ?>' + ' option:first-child' ).prop( "selected", true );

								//Highlight options in chosen, before removing
								jQuery( '#<?php echo $el_id; ?>_chosen li.search-choice:not(:contains(' + '<?php echo html_entity_decode( esc_js( __( 'All resources', 'booking' ) ) ); ?>' + '))' )
											.fadeOut( 350 ).fadeIn( 300 )
											.fadeOut( 350 ).fadeIn( 400 )
											.fadeOut( 350 ).fadeIn( 300 )
											.fadeOut( 350 ).fadeIn( 400 )
											.animate( {opacity: 1}, 4000 ) ;

								// Update chosen LI choices, relative selected options in selectbox
								var all_resources_timer = setTimeout( function (){

									jQuery( '#<?php echo $el_id; ?>' ).trigger( 'chosen:updated' );            			// Remove all fields from the Choozen field
								}, 2000 );

								var my_message = '<?php echo html_entity_decode( esc_js( __( 'Please note, its not possible to add new resources, if "All resources" option is selected. Please clear the selection, then add new resources.', 'booking' ) ), ENT_QUOTES ); ?>';
								wpbc_admin_show_message( my_message, 'warning', 10000 );
							}
						} );
					}
				});

			} else {
				alert( 'WPBC Error. JavaScript library "chosen" was not defined.' );
			}
        </script><?php
}

/**
 * Existing | Trash | Any
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__existing_or_trash( $escaped_search_request_params, $defaults ){

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
						, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_delete_outline', 'position' => 'right', 'icon_img' => '' )
						, 'class'       => 'wpbc_ui_button inactive'
						, 'style'       => ''
						, 'attr'        => array()
						, 'hint' 	=> array( 'title' => __('Show trashed or existing bookings' ,'booking') , 'position' => 'top' )
					);

	$el_id = 'wh_trash';
	$params = array(
					  'id'      => $el_id
					, 'default' => isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ]
					, 'label' 	=> ''
					, 'title' 	=> ''	//__('Bookings', 'booking')
					, 'hint' 	=> array( 'title' => __('Show trashed or existing bookings' ,'booking') , 'position' => 'top' )
					, 'li_options' => array(
										  '0'        => __( 'Existing', 'booking' ),
										  'trash'    => __( 'In Trash / Rejected', 'booking' ),
										  'divider1' => array( 'type' => 'html', 'html' => '<hr/>' ),
										  'any'      => __( 'Any', 'booking' )
									  )
					//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( '#{$el_id}' ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
					//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() 		, 'in element:' , jQuery( this ) );"					// JavaScript code
					//, 'onchange' => "wpbc_ajx_booking_send_search_request_with_params( { '{$el_id}': JSON.parse( jQuery( this ).val() )[0], 'page_num': 1 } );"
				);

	?><div class="ui_element ui_nowrap"><?php

		wpbc_flex_addon( $params_addon );

		wpbc_flex_dropdown( $params );

	?></div><?php
}

/**
 *  All bookings | New bookings	| Imported bookings	| Plugin bookings
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__all_or_new( $escaped_search_request_params, $defaults ){

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
						, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_visibility', 'position' => 'right', 'icon_img' => '' )
						, 'class'       => 'wpbc_ui_button inactive'
						, 'style'       => ''
						, 'attr'        => array()
						, 'hint' 	=> array( 'title' => __('Filter bookings by additional criteria' ,'booking') , 'position' => 'top' )
					);

		$el_id = 'wh_what_bookings';
		$params = array(
						  'id'      => $el_id
						, 'default' => isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ]
						, 'label' 	=> ''
						, 'title' 	=> '' //__('Show', 'booking')
						, 'hint' 	=> array( 'title' => __('Filter bookings by additional criteria' ,'booking') , 'position' => 'top' )
						, 'li_options' => array(
											  'all' => array(
												  'type'  => 'simple',
												  'value' => 'any',
												  'title' => __( 'Any', 'booking' )
											  ),
											  'divider1' => array( 'type' => 'html', 'html' => '<hr/>' ),
											  'new'        	=> __( 'New', 'booking' ),
											  'imported' 	=> __( 'Imported', 'booking' ),
											  'in_plugin'   => __( 'Plugin bookings', 'booking' )
										  )
						//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( '#{$el_id}' ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
						//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() 		, 'in element:' , jQuery( this ) );"					// JavaScript code
						//, 'onchange' => "wpbc_ajx_booking_send_search_request_with_params( { '{$el_id}': JSON.parse( jQuery( this ).val() )[0], 'page_num': 1 } );"
					);


	?><div class="ui_element ui_nowrap"><?php

		wpbc_flex_addon( $params_addon );

		wpbc_flex_dropdown( $params );

	?></div><?php
}

/**
 *  "Creation Date"   of bookings
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__creation_date( $escaped_search_request_params, $defaults ){

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
						, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_edit_calendar', 'position' => 'right', 'icon_img' => '' )
						, 'class'       => 'wpbc_ui_button inactive'
						, 'style'       => ''
						, 'attr'        => array()
						, 'hint' 	=> array( 'title' => __('Filter bookings by creation booking date' ,'booking') , 'position' => 'top' )
					);

	$dates_interval = array(
								1 => '1' . ' ' . __('day' ,'booking')
							  , 2 => '2' . ' ' . __('days' ,'booking')
							  , 3 => '3' . ' ' . __('days' ,'booking')
							  , 4 => '4' . ' ' . __('days' ,'booking')
							  , 5 => '5' . ' ' . __('days' ,'booking')
							  , 6 => '6' . ' ' . __('days' ,'booking')
							  , 7 => '1' . ' ' . __('week' ,'booking')
							  , 14 => '2' . ' ' . __('weeks' ,'booking')
							  , 30 => '1' . ' ' . __('month' ,'booking')
							  , 60 => '2' . ' ' . __('months' ,'booking')
							  , 90 => '3' . ' ' . __('months' ,'booking')
							  , 183 => '6' . ' ' . __('months' ,'booking')
							  , 365 => '1' . ' ' . __('Year' ,'booking')
						);

	$el_id = 'wh_modification_date';

	$request_input_el_default = array(
		$el_id             			  	   => isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ],
		'ui_wh_modification_date_radio'    => isset( $escaped_search_request_params['ui_wh_modification_date_radio'] ) ? $escaped_search_request_params['ui_wh_modification_date_radio'] : $defaults['ui_wh_modification_date_radio'],
		'ui_wh_modification_date_prior'    => isset( $escaped_search_request_params['ui_wh_modification_date_prior'] ) ? $escaped_search_request_params['ui_wh_modification_date_prior'] : $defaults['ui_wh_modification_date_prior'],
		'ui_wh_modification_date_checkin'  => isset( $escaped_search_request_params['ui_wh_modification_date_checkin'] ) ? $escaped_search_request_params['ui_wh_modification_date_checkin'] : $defaults['ui_wh_modification_date_checkin'],
		'ui_wh_modification_date_checkout' => isset( $escaped_search_request_params['ui_wh_modification_date_checkout'] ) ? $escaped_search_request_params['ui_wh_modification_date_checkout'] : $defaults['ui_wh_modification_date_checkout']
	);

	$options = array (
						'1' => __( 'Today', 'booking' ),
						'3' => __( 'All dates', 'booking' ),

						'divider1' => array( 'type' => 'html', 'html' => '<hr/>' ),

						// Prior  [ '5', '10' ]
						'prior' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'style' => 'min-width: 244px;',
								'selected_options_value' => array( 1 => array( 'value' ), 4 => array( 'value' ) ),		//  4 => array( 'value' )  			-->   $complex_option['input_options'][4]['value']
								'selected_options_title' => array(    1 => array( 'label', 'title' )
																	, 'text1' => ': '
																	, 4 => array( 'options' , $request_input_el_default[ 'ui_wh_modification_date_prior'] )
																 ), 													//  1 => array( 'label', 'title' )  --> $complex_option['input_options'][1]['label'][ 'title' ]
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element">' )
													, array(
															'type' => 'radio'

														, 'id'       => 'ui_wh_modification_date_radio_2' 		// HTML ID  of element
														, 'name'     => 'ui_wh_modification_date_radio'
														, 'label'    => array( 'title' => __('Prior' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''					// aria-label parameter
														, 'value'    => '5' 		// Some Value from optins array that selected by default
														, 'selected' => ( $request_input_el_default[ 'ui_wh_modification_date_radio' ] == '5' ) ? true : false 				// Selected or not
														//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element">' )
													, array(
															'type' => 'select'
														  , 'attr' => array()
														  , 'name' => 'ui_wh_modification_date_prior'
														  , 'id' => 'ui_wh_modification_date_prior'
														  , 'options' => $dates_interval
														  , 'value' => $request_input_el_default[ 'ui_wh_modification_date_prior']
														  , 'onfocus' =>  "jQuery('#ui_wh_modification_date_radio_2').prop('checked', true);"					// JavaScript code
														  //, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
							),

						// Fixed [ '6', '', '2022-05-21']
						'fixed' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'selected_options_value' => array( 1 => array( 'value' ), 4 => array( 'value' ), 7 => array( 'value' ) ),												//  4 => array( 'value' )  			-->   $complex_option['input_options'][4]['value']
								'selected_options_title' => array( 1 => array( 'label', 'title' ), 'text1' => ': ', 4 => array( 'value' ), 'text2' => ' - ' ,7 => array( 'value' ) ),	//  1 => array( 'label', 'title' )  -->   $complex_option['input_options'][1]['label'][ 'title' ]
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex:1 1 100%;margin-top:5px;">' )
													, array(
															'type' => 'radio'

														, 'id'       => 'ui_wh_modification_date_radio_3' 		// HTML ID  of element
														, 'name'     => 'ui_wh_modification_date_radio'
														, 'label'    => array( 'title' => __('Dates' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''					// aria-label parameter
														, 'value'    => '6' 		// Some Value from optins array that selected by default
														, 'selected' => ( $request_input_el_default[ 'ui_wh_modification_date_radio' ] == '6' ) ? true : false 				// Selected or not
														//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 4px 4px 0;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_modification_date_checkin' 		// HTML ID  of element
														, 'name'     => 'ui_wh_modification_date_checkin'
														, 'label'    => ''//__('Check-in' ,'booking')
														, 'style'    => 'width:100%;' 					// CSS of select element
														, 'class'    => 'wpdevbk-filters-section-calendar' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('From' ,'booking')	// date( 'Y-m-d' )
														, 'value'    => $request_input_el_default[ 'ui_wh_modification_date_checkin'] 	// Some Value from optins array that selected by default
														, 'onfocus' =>  "jQuery('#ui_wh_modification_date_radio_3').prop('checked', true);"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 0 4px 4px;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_modification_date_checkout' 		// HTML ID  of element
														, 'name'     => 'ui_wh_modification_date_checkout'
														, 'label'    => ''//__('Check-out' ,'booking')
														, 'style'    => 'width:100%;' 					// CSS of select element
														, 'class'    => 'wpdevbk-filters-section-calendar' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('To' ,'booking')		// date( 'Y-m-d' )
														, 'value'    => $request_input_el_default[ 'ui_wh_modification_date_checkout']  		// Some Value from optins array that selected by default
														, 'onfocus' =>  "jQuery('#ui_wh_modification_date_radio_3').prop('checked', true);"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),

						'divider3' => array( 	'type'  => 'html', 		'html' => '<hr/>' ),

						// Buttons
						'buttons1' =>  array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'style' => 'justify-content: flex-end;',
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex: 0 1 auto;margin: 0;">' )
													, array(
															'type' => 'button'
														  , 'title' => __( 'Apply', 'booking' )                     // Title of the button
														  , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "wpbc_ui_dropdown_apply_click( { 
																									'dropdown_id'		 : 'wh_modification_date', 
																									'dropdown_radio_name': 'ui_wh_modification_date_radio' 
																								} );"				// JavaScript code
														  , 'class' => 'wpbc_ui_button_primary'     // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => ''
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => ''                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex: 0 1 auto;margin: 0 0 0 1em;">' )
													, array(
															'type' => 'button'
														  , 'title' => __( 'Close', 'booking' )                     // Title of the button
														  , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "wpbc_ui_dropdown_close_click( 'wh_modification_date' );"				// JavaScript code
														  , 'class' => ''     				  // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => ''
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => ''                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),
				);

	$params = array(
					  'id'      => $el_id
					, 'default' => isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ]
					, 'label' 	=> ''
					, 'title' 	=> ''//__('Creation', 'booking')
					, 'hint' 	=> array( 'title' => __('Filter bookings by creation booking date' ,'booking') , 'position' => 'top' )
					, 'li_options' => $options
					//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( '#{$el_id}' ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
					//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() 		, 'in element:' , jQuery( this ) );"					// JavaScript code
					//, 'onchange' => "wpbc_ajx_booking_send_search_request_with_params( { '{$el_id}': JSON.parse( jQuery( this ).val() ), 'page_num': 1 "
					//						// Frontend selected elements (saving for future use, after F5)
					//						. " ,'ui_wh_modification_date_radio'   : jQuery( 'input[name=\"ui_wh_modification_date_radio\"]:checked' ).val()"
					//						. " ,'ui_wh_modification_date_prior'   : jQuery( '#ui_wh_modification_date_prior' ).val()"
					//						. " ,'ui_wh_modification_date_checkin' : jQuery( '#ui_wh_modification_date_checkin' ).val()"
					//						. " ,'ui_wh_modification_date_checkout': jQuery( '#ui_wh_modification_date_checkout' ).val()"
					//				."} );"
				);

	?><div class="ui_element ui_nowrap"><?php

		wpbc_flex_addon( $params_addon );

		wpbc_flex_dropdown( $params );

	?></div><?php
}

/**
 *  Payment Status
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__payment_status( $escaped_search_request_params, $defaults ){

	if ( ! class_exists( 'wpdev_bk_biz_s' ) ) {
		return false;
	}

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
						, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_payments', 'position' => 'right', 'icon_img' => '' )
						, 'class'       => 'wpbc_ui_button inactive'
						, 'style'       => ''
						, 'attr'        => array()
						, 'hint' 	=> array( 'title' => __('Filter bookings by payment status' ,'booking') , 'position' => 'top' )
					);

	$el_id = 'wh_pay_status';

	$request_input_el_default = array(
		$el_id             			 => isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ],
		'ui_wh_pay_status_radio'     => isset( $escaped_search_request_params['ui_wh_pay_status_radio'] ) ?  $escaped_search_request_params['ui_wh_pay_status_radio']  : $defaults['ui_wh_pay_status_radio'],
		'ui_wh_pay_status_custom'   => isset( $escaped_search_request_params['ui_wh_pay_status_custom'] ) ?  $escaped_search_request_params['ui_wh_pay_status_custom']  : $defaults['ui_wh_pay_status_custom']
	);

	$options = array (
						'all'           => __( 'Any Status', 'booking' ),
						'divider0'      => array( 'type' => 'html', 'html' => '<hr/>' ),
						'group_ok'      => __( 'Paid OK', 'booking' ),
						'group_unknown' => __( 'Unknown Status', 'booking' ),
						'group_pending' => __( 'Not Completed', 'booking' ),
						'group_failed'  => __( 'Failed', 'booking' ),

						'divider1' => array( 'type' => 'html', 'html' => '<hr/>' ),

						// Fixed [ '6', '', '2022-05-21']
						'custom' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'selected_options_value' => array( 1 => array( 'value' ), 4 => array( 'value' ) ),												//  4 => array( 'value' )  			-->   $complex_option['input_options'][4]['value']
								'selected_options_title' => array( 1 => array( 'label', 'title' ), 'text1' => ': ', 4 => array( 'value' ) ),	//  1 => array( 'label', 'title' )  -->   $complex_option['input_options'][1]['label'][ 'title' ]
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex:1 1 100%;margin-top:5px;">' )
													, array(
															'type' => 'radio'

														, 'id'       => 'ui_wh_pay_status_radio_1' 		// HTML ID  of element
														, 'name'     => 'ui_wh_pay_status_radio'
														, 'label'    => array( 'title' => __('Custom' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''					// aria-label parameter
														, 'value'    => 'user_entered' 		// Some Value from optins array that selected by default
														, 'selected' => ( $request_input_el_default[ 'ui_wh_pay_status_radio' ] == 'user_entered' ) ? true : false 				// Selected or not
														//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex:1 1 100%;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_pay_status_custom' 		// HTML ID  of element
														, 'name'     => 'ui_wh_pay_status_custom'
														, 'label'    => ''//__('Check-out' ,'booking')
														, 'style'    => 'max-width:100%;width:100%;' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('Payment status' ,'booking')		// date( 'Y-m-d' )
														, 'value'    => $request_input_el_default[ 'ui_wh_pay_status_custom']  		// Some Value from optins array that selected by default
														, 'onfocus' =>  "jQuery('#ui_wh_pay_status_radio_1').prop('checked', true);"					// JavaScript code
														//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),


						// Buttons
						'buttons1' =>  array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'style' => 'justify-content: flex-end;',
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex: 0 1 auto;margin: 0;">' )
													, array(
															'type' => 'button'
														  , 'title' => __( 'Apply', 'booking' )                     // Title of the button
														  , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "wpbc_ui_dropdown_apply_click( { 
																									'dropdown_id'		 : 'wh_pay_status', 
																									'dropdown_radio_name': 'ui_wh_pay_status_radio' 
																								} );"				// JavaScript code
														  , 'class' => 'wpbc_ui_button_primary'     // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => ''
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => ''                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex: 0 1 auto;margin: 0 0 0 1em;">' )
													, array(
															'type' => 'button'
														  , 'title' => __( 'Close', 'booking' )                     // Title of the button
														  , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "wpbc_ui_dropdown_close_click( 'wh_pay_status' );"           // JavaScript code
														  , 'class' => ''     				  // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => ''
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => ''                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),
				);

	$params = array(
					  'id'      => $el_id
					, 'default' => isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ]
					, 'label' 	=> ''
					, 'title' 	=> ''//__('Payment', 'booking')
					, 'hint' 	=> array( 'title' => __('Filter bookings by payment status' ,'booking') , 'position' => 'top' )
					, 'li_options' => $options
					//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( '#{$el_id}' ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
					//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() 		, 'in element:' , jQuery( this ) );"					// JavaScript code
					///*, 'onchange' =>*/. "wpbc_ajx_booking_send_search_request_with_params( { '{$el_id}': JSON.parse( jQuery( this ).val() ), 'page_num': 1 "
					//						// Frontend selected elements (saving for future use, after F5)
					//						. " ,'ui_wh_pay_status_radio'  : ( undefined === jQuery( 'input[name=\"ui_wh_pay_status_radio\"]:checked' ).val() ) ? '' : jQuery( 'input[name=\"ui_wh_pay_status_radio\"]:checked' ).val()"
					//						. " ,'ui_wh_pay_status_custom' : jQuery( '#ui_wh_pay_status_custom' ).val()"
					//				."} );"
				);


	?><div class="ui_element ui_nowrap"><?php

		wpbc_flex_addon( $params_addon );

		wpbc_flex_dropdown( $params );

	?></div><?php
}

/**
 *  Costs 	Min - Max
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx__ui__cost_min_max( $escaped_search_request_params, $defaults ){

	if ( ! class_exists( 'wpdev_bk_biz_s' ) ) {
		return false;
	}

	$el_id = 'wh_cost';

	$params = array(
							  'id'       => $el_id 		// HTML ID  of element
							, 'name'     => $el_id
							, 'label'    => '<span class="" style="font-weight:600;">' . __( 'Cost', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
							, 'style'    => 'max-width: 69px;' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							, 'disabled' => false
							, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
							, 'placeholder' => '0'
							, 'value'    => isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ]
							//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
							//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
			);
	?><div class="ui_element" style="margin-right: 5px;"><?php

	wpbc_flex_text( $params );

	?></div><?php


	$el_id = 'wh_cost2';

	$params = array(
							  'id'       => $el_id 		// HTML ID  of element
							, 'name'     => $el_id
							, 'label'    => '<span class="" style="font-weight:600;"> &dash; </span>'
							, 'style'    => 'max-width: 69px;' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							, 'disabled' => false
							, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
							, 'placeholder' => '10000'
							, 'value'    => isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ]
							//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
							//, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
			);
	?><div class="ui_element"><?php

	wpbc_flex_text( $params );

	?></div><?php

}

/**
 * Reload button - force loading of ajax data
 *
 * @param $escaped_search_request_params   	-	escaped search request parameters array
 * @param $defaults							-   default parameters values
 */
function wpbc_ajx_toolbar_force_reload_button( $escaped_search_request_params, $defaults ){

    $params  =  array(
	    'type'             => 'button' ,
	    'title'            => '',//__( 'Reset', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
	    'hint'             => array( 'title' => __( 'Reload bookings listing', 'booking' ), 'position' => 'top' ),  	// Hint
	    'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
	    'action'           => "wpbc_ajx_booking_send_search_request_with_params( { } );",	// Some JavaScript
	    'icon' 			   => array(
									'icon_font' => 'wpbc_icn_rotate_right wpbc_spin', //'wpbc_icn_rotate_left',
									'position'  => 'left',
									'icon_img'  => ''
								),
	    'class'            => 'wpbc_ui_button wpbc_ui_button_primary',  																// ''  | 'wpbc_ui_button_primary'
	    'style'            => '',																						// Any CSS class here
	    'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
	    'attr'             => array( 'id' => 'wpbc_booking_listing_reload_button')
	);

	?><div class="ui_element" style="flex: 0 1 auto;"><?php
		wpbc_flex_button( $params );
	?></div><?php
}

// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" T o o l b a r       A c t i o n       B u t t o n s "  >

////////////////////////////////////////////////////////////////////////////////
//   T o o l b a r       A C T I O N S       UI elements
////////////////////////////////////////////////////////////////////////////////

	function wpbc_ajx__ui__action_button__approve( $escaped_search_request_params ){

		$booking_action = 'set_booking_approved';

		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Approve', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Approve selected bookings', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : wpbc_get_selected_row_id(),
																			'reason_of_action' : jQuery( '#reason_of_action' ).val(),
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
								   wpbc_button_enable_loading_icon( this ); " ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_check_circle_outline',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => 'wpbc_ui_button_primary hide_button_if_no_selection',  																// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__pending( $escaped_search_request_params ){

		$booking_action = 'set_booking_pending';

		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Pending', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Set selected bookings as pending', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to set booking as pending ?', 'booking' ) ) . "') ) {
										wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : wpbc_get_selected_row_id(),
																			'reason_of_action' : jQuery( '#reason_of_action' ).val(),
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
										wpbc_button_enable_loading_icon( this ); 
									}" ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_block',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => 'hide_button_if_no_selection',  																						// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__trash( $escaped_search_request_params ){

		$booking_action = 'move_booking_to_trash';

		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Trash / Reject', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Reject booking - move selected bookings to trash', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to do this ?', 'booking' ) ) . "') ) {
										wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : wpbc_get_selected_row_id(),
																			'reason_of_action' : jQuery( '#reason_of_action' ).val(),
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
										wpbc_button_enable_loading_icon( this ); 
									}" ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_delete_outline',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => 'hide_button_if_no_selection',  																						// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__restore( $escaped_search_request_params ){

		$booking_action = 'restore_booking_from_trash';

		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Restore', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Restore selected bookings', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to do this ?', 'booking' ) ) . "') ) {
										wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : wpbc_get_selected_row_id(),
																			'reason_of_action' : jQuery( '#reason_of_action' ).val(),
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
										wpbc_button_enable_loading_icon( this ); 
									}" ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_rotate_left',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => 'hide_button_if_no_selection',  																						// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__delete( $escaped_search_request_params ){

		$booking_action = 'delete_booking_completely';

		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Delete', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Delete selected bookings', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to delete selected booking(s) ?', 'booking' ) ) . "') ) {
										wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : wpbc_get_selected_row_id(),
																			'reason_of_action' : jQuery( '#reason_of_action' ).val(),
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
										wpbc_button_enable_loading_icon( this ); 
									}" ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_close',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => 'hide_button_if_no_selection',  																						// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_text__delete_reason( $escaped_search_request_params ){

		$params = array(
						'type'          => 'text'
						, 'id'          => 'reason_of_action'
						, 'name'        => 'reason_of_action'
						, 'label'       => ''
						, 'disabled'    => false
						, 'class'       => 'hide_button_if_no_selection'
						, 'style'       => ''
						, 'placeholder' => __( 'Reason of action', 'booking' )
						, 'attr'        => array()
						, 'value' => ''
						, 'onfocus' => ''
		);
		wpbc_flex_text( $params );
	}

	function wpbc_ajx__ui__action_button__readall( $escaped_search_request_params ){

		$booking_action = 'set_booking_as_read';

		$el_id = 'ui_btn_all_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Read All', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Mark as read all bookings', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : '-1',
																			'reason_of_action' : jQuery( '#reason_of_action' ).val(),
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
								   wpbc_button_enable_loading_icon( this ); " ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_disabled_visible',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => '',  																// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => !true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__read( $escaped_search_request_params ){

		$booking_action = 'set_booking_as_read';

		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Read', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Mark as read selected bookings', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : wpbc_get_selected_row_id(),
																			'reason_of_action' : jQuery( '#reason_of_action' ).val(),
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
								   wpbc_button_enable_loading_icon( this ); " ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_visibility_off',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => 'hide_button_if_no_selection',  																// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => !true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__unread( $escaped_search_request_params ){

		$booking_action = 'set_booking_as_unread';

		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params  =  array(
			'type'             => 'button' ,
			'title'            => __( 'Unread', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
			'hint'             => array( 'title' => __( 'Mark as Unread selected bookings', 'booking' ), 'position' => 'top' ),  	// Hint
			'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
			'action'           => "wpbc_ajx_booking_ajax_action_request( { 
																			'booking_action'   : '{$booking_action}', 
																			'booking_id'       : wpbc_get_selected_row_id(),
																			'reason_of_action' : jQuery( '#reason_of_action' ).val(),
																			'ui_clicked_element_id': '{$el_id}'  
																		} );
								   wpbc_button_enable_loading_icon( this ); " ,
			'icon' 			   => array(
										'icon_font' => 'wpbc_icn_visibility',
										'position'  => 'right',
										'icon_img'  => ''
									),
			'class'            => 'hide_button_if_no_selection',  																// ''  | 'wpbc_ui_button_primary'
			'style'            => '',																						// Any CSS class here
			'mobile_show_text' => !true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
			'attr'             => array( 'id' => $el_id )
		);

		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__print( $escaped_search_request_params ){
		$user_bk_id = 1;
		$params = array(
			'type'   => 'button',
			'title'  => __( 'Print', 'booking' ) . '&nbsp;&nbsp;',
			'hint'   => array(
								'title'    => __( 'Print bookings listing', 'booking' ),
								'position' => 'top'
							),
			'link'   => 'javascript:void(0)',
			'action' => "wpbc_print_dialog__show();",
			'icon'   => array(
								'icon_font' => 'wpbc_icn_print',
								'position'  => 'right',
								'icon_img'  => ''
							),
			'class'  => '',
			'style'  => '',
			'attr'   => array(),
			'mobile_show_text' => true
		);
		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__import( $escaped_search_request_params ){

		$booking_action = 'import_google_calendar';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}


		$booking_gcal_feed = get_bk_option( 'booking_gcal_feed');

		if ( ( ! class_exists( 'wpdev_bk_personal' ) ) && ( $booking_gcal_feed == '' ) ) {
			$is_this_btn_disabled = true;
		} else {
			$is_this_btn_disabled = false;
		}

		$params = array(
			'type'   => 'button',
			'title'  => __( 'Import', 'booking' ) . '&nbsp;&nbsp;',
			'hint'   => array(
								'title'    => __( 'Import Google Calendar Events', 'booking' ),
								'position' => 'top'
							),
			'link'   => 'javascript:void(0)',
			'action' => " if ( 'function' === typeof( jQuery('#wpbc_modal__import_google_calendar__section').wpbc_my_modal ) ) {
								jQuery('#wpbc_modal__import_google_calendar__section').wpbc_my_modal('show');
							} else {
								alert( 'Warning! wpbc_my_modal module has not found. Please, recheck about any conflicts by deactivating other plugins.');
							}",
			'icon'   => array(
								'icon_font' => 'wpbc_icn_event',
								'position'  => 'right',
								'icon_img'  => ''
							),
			'class'  => '',//( $is_this_btn_disabled ? ' disabled' : '' ),
			'style'  => '',
			'attr'   => array(),
			'mobile_show_text' => true
		);
		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__export_csv( $escaped_search_request_params ){

		if ( ! class_exists( 'wpdev_bk_personal' ) ) {
			return false;
		}

		$booking_action = 'export_csv';
		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'   => 'button',
			'title'  => __( 'Export to CSV', 'booking' ) . '&nbsp;&nbsp;',
			'hint'   => array(
								'title'    => __( 'Export bookings to CSV format', 'booking' ),
								'position' => 'top'
							),
			'link'   => 'javascript:void(0)',
			//'action' => wpbc_csv_get_url_for_button( 'page' ),
			'action' => "if ( 'function' === typeof( jQuery('#wpbc_modal__export_csv__section').wpbc_my_modal ) ) {							
							jQuery('#wpbc_modal__export_csv__section').wpbc_my_modal('show');
						} else {
							alert( 'Warning! wpbc_my_modal module has not found. Please, recheck about any conflicts by deactivating other plugins.');
						}",
			'icon'   => array(
								'icon_font' => 'wpbc_icn_file_upload',
								'position'  => 'right',
								'icon_img'  => ''
							),
			'class'  => '',
			'style'  => '',
			'attr'   => array( 'id' => $el_id ),
			'mobile_show_text' => true
		);
		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__export_csv_page( $escaped_search_request_params ){

		if ( ! class_exists( 'wpdev_bk_personal' ) ) {
			return false;
		}

		$booking_action = 'export_csv';
		$el_id = 'ui_btn_' . $booking_action;

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'   => 'button',
			'title'  => __( 'Export page to CSV', 'booking' ) . '&nbsp;&nbsp;',
			'hint'   => array(
								'title'    => __( 'Export only current page of bookings to CSV format', 'booking' ),
								'position' => 'top'
							),
			'link'   => 'javascript:void(0)',
			//'action' => wpbc_csv_get_url_for_button( 'page' ),
			'action' => "wpbc_ajx_booking__ui_click__export_csv( {
																	'booking_action'   : '{$booking_action}', 
																	'ui_clicked_element_id': '{$el_id}',
																	'export_type': 'csv_page'  
															} );",
			'icon'   => array(
								'icon_font' => 'wpbc_icn_file_upload',
								'position'  => 'right',
								'icon_img'  => ''
							),
			'class'  => '',
			'style'  => '',
			'attr'   => array( 'id' => $el_id ),
			'mobile_show_text' => true
		);
		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__export_csv_all( $escaped_search_request_params ){

		if ( ! class_exists( 'wpdev_bk_personal' ) ) {
			return false;
		}

		$booking_action = 'export_csv';
		$el_id = 'ui_btn_' . $booking_action . '_all';

		if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
			return false;
		}

		$params = array(
			'type'   => 'button',
			'title'  => __( 'Export all pages to CSV', 'booking' ) . '&nbsp;&nbsp;',
			'hint'   => array(
								'title'    => __( 'Export All bookings to CSV format', 'booking' ),
								'position' => 'top'
							),
			'link'   => 'javascript:void(0)',
			//'action' => wpbc_csv_get_url_for_button( 'all' ),
			'action' => "wpbc_ajx_booking__ui_click__export_csv( {
																	'booking_action'   : '{$booking_action}', 
																	'ui_clicked_element_id': '{$el_id}',
																	'export_type': 'csv_all'  
															} );",

			'icon'   => array(
								'icon_font' => 'wpbc_icn_publish',
								'position'  => 'right',
								'icon_img'  => ''
							),
			'class'  => '',
			'style'  => '',
			'attr'   => array( 'id' => $el_id ),
			'mobile_show_text' => true
		);
		wpbc_flex_button( $params );
	}

	function wpbc_ajx__ui__action_button__empty_trash( $escaped_search_request_params ){

			$booking_action = 'empty_trash';

			$el_id = 'ui_btn_' . $booking_action;

			if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
				return false;
			}

			$params  =  array(
				'type'             => 'button' ,
				'title'            => __( 'Empty Trash', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
				'hint'             => array( 'title' => __( 'Empty Trash', 'booking' ), 'position' => 'top' ),  	// Hint
				'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
				'action'           => "if ( wpbc_are_you_sure('" . esc_attr( __( 'Do you really want to do this ?', 'booking' ) ) . "') ) {
											wpbc_ajx_booking_ajax_action_request( { 
																				'booking_action'   : '{$booking_action}', 
																				'ui_clicked_element_id': '{$el_id}'  
																			} );
											wpbc_button_enable_loading_icon( this ); 
									   }" ,
				'icon' 			   => array(
											'icon_font' => 'wpbc_icn_delete_forever',
											'position'  => 'right',
											'icon_img'  => ''
										),
				'class'            => '',  																// ''  | 'wpbc_ui_button_primary'
				'style'            => '',																						// Any CSS class here
				'mobile_show_text' => true,																					// Show  or hide text,  when viewing on Mobile devices (small window size).
				'attr'             => array( 'id' => $el_id )
			);

			wpbc_flex_button( $params );
	}
// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" T o o l b a r       O p t i o n s       B u t t o n s "  >


function wpbc_ajx__ui__options_checkbox__send_emails( $escaped_search_request_params, $defaults ){

	$el_id = 'ui_usr__send_emails';

	$el_value = isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ];

	$params_checkbox = array(
							  'id'       => $el_id 		// HTML ID  of element
							, 'name'     => $el_id
							, 'label'    => array( 'title' => __( 'Emails sending', 'booking' ) , 'position' => 'right' )           //FixIn: 9.6.1.5
							, 'style'    => '' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							, 'disabled' => false
							, 'attr'     => array() 							// Any  additional attributes, if this radio | checkbox element
							, 'legend'   => ''									// aria-label parameter
							, 'value'    => $el_value 							// Some Value from optins array that selected by default
							, 'selected' => ( ( 'send' == $el_value ) ? true : false )		// Selected or not
							//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
							, 'onchange' => "wpbc_ajx_booking_send_search_request_with_params( {'ui_usr__send_emails': (jQuery( this ).is(':checked') ? 'send' : 'not_send') } );"					// JavaScript code
							//, 'hint' 	=> array( 'title' => __('Filter bookings by booking status' ,'booking') , 'position' => 'top' )
						);

	wpbc_flex_toggle( $params_checkbox );
}


function wpbc_ajx__ui__options_checkbox__is_expand_remarks( $escaped_search_request_params, $defaults ){

	$el_id = 'ui_usr__is_expand_remarks';

	$el_value = isset( $escaped_search_request_params[ $el_id ] ) ? $escaped_search_request_params[ $el_id ] : $defaults[ $el_id ];

	$params_checkbox = array(
							  'id'       => $el_id 		// HTML ID  of element
							, 'name'     => $el_id
							, 'label'    => array( 'title' => __( 'Show / hide notes', 'booking' ) , 'position' => 'left' )
							, 'legend'   => __('Check this box if you want to open notes section by default in Booking Listing page.' ,'booking')
							, 'style'    => '' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							, 'disabled' => false
							, 'attr'     => array() 							// Any  additional attributes, if this radio | checkbox element
							, 'legend'   => ''									// aria-label parameter
							, 'value'    => $el_value 							// Some Value from optins array that selected by default
							, 'selected' => ( ( 'On' == $el_value ) ? true : false )		// Selected or not
							//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
							, 'onchange' => "wpbc_ajx_booking_send_search_request_with_params( {'ui_usr__is_expand_remarks': (jQuery( this ).is(':checked') ? 'On' : 'Off') } );"					// JavaScript code
						);

	wpbc_flex_toggle( $params_checkbox );
}
// </editor-fold>

////////////////////////////////////////////////////////////////////////////////
//  Complex  elements
////////////////////////////////////////////////////////////////////////////////

/**
 * Show FLEX  Dropdown
 *
 * Dropdown always have value as array. For example: [ '0' ], [ '4', '10' ]  or [ '6', '', '2022-05-24' ]
 *
 * @param array $args
 *
 *  = Simple Example: ==================================================================================================

    $params = array(
					  'id'      => 'wh_approved'
					, 'default' => isset( $escaped_search_request_params['wh_approved'] ) ? $escaped_search_request_params['wh_approved'] : $defaults['wh_approved']
                    , 'label' 	=> ''//__('Status', 'booking') . ':'
                    , 'title' 	=> __('Status', 'booking')
					, 'hint' 	=> array( 'title' => __('Filter bookings by booking status' ,'booking') , 'position' => 'top' )
					, 'li_options' => array (
											'0' => __( 'Pending', 'booking' ),
											'1' => __( 'Approved', 'booking' ),
											'divider1' => array( 'type' => 'html', 'html' => '<hr/>' ),
											// 'header1' => array( 'type' => 'header', 'title' => __( 'Default', 'booking' ) ),
											'any' => array(
														'type'     => 'simple',
														'value'    => '',
														// 'disabled' => true,
														'title'    => __( 'Any', 'booking' )
											),
									 )
                );

	?><div class="ui_element ui_nowrap"><?php

		wpbc_flex_dropdown( $params );

	?></div><?php
 *
 *
 *  = Complex Example: =================================================================================================
 *

	$params_addon = array(
						  'type'        => 'span'
						, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
						, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_event', 'position' => 'right', 'icon_img' => '' )
						, 'class'       => 'wpbc_ui_button inactive'
						, 'style'       => ''
						, 'attr'        => array()
					);

    $dates_interval = array(
                                1 => '1' . ' ' . __('day' ,'booking')
                              , 2 => '2' . ' ' . __('days' ,'booking')
                              , 3 => '3' . ' ' . __('days' ,'booking')
                              , 4 => '4' . ' ' . __('days' ,'booking')
                              , 5 => '5' . ' ' . __('days' ,'booking')
                              , 6 => '6' . ' ' . __('days' ,'booking')
                              , 7 => '1' . ' ' . __('week' ,'booking')
                              , 14 => '2' . ' ' . __('weeks' ,'booking')
                              , 30 => '1' . ' ' . __('month' ,'booking')
                              , 60 => '2' . ' ' . __('months' ,'booking')
                              , 90 => '3' . ' ' . __('months' ,'booking')
                              , 183 => '6' . ' ' . __('months' ,'booking')
                              , 365 => '1' . ' ' . __('Year' ,'booking')
                        );

	$request_input_el_default = array(
		'wh_booking_date'             => isset( $escaped_search_request_params['wh_booking_date'] ) ? $escaped_search_request_params['wh_booking_date'] : $defaults['wh_booking_date'],
		'ui_wh_booking_date_radio'    => isset( $escaped_search_request_params['ui_wh_booking_date_radio'] ) ? $escaped_search_request_params['ui_wh_booking_date_radio'] : $defaults['ui_wh_booking_date_radio'],
		'ui_wh_booking_date_next'     => isset( $escaped_search_request_params['ui_wh_booking_date_next'] ) ? $escaped_search_request_params['ui_wh_booking_date_next'] : $defaults['ui_wh_booking_date_next'],
		'ui_wh_booking_date_prior'    => isset( $escaped_search_request_params['ui_wh_booking_date_prior'] ) ? $escaped_search_request_params['ui_wh_booking_date_prior'] : $defaults['ui_wh_booking_date_prior'],
		'ui_wh_booking_date_checkin'  => isset( $escaped_search_request_params['ui_wh_booking_date_checkin'] ) ? $escaped_search_request_params['ui_wh_booking_date_checkin'] : $defaults['ui_wh_booking_date_checkin'],
		'ui_wh_booking_date_checkout' => isset( $escaped_search_request_params['ui_wh_booking_date_checkout'] ) ? $escaped_search_request_params['ui_wh_booking_date_checkout'] : $defaults['ui_wh_booking_date_checkout']
	);

	$options = array (
						// 'header2'   => array( 'type' => 'header', 'title' => __( 'Complex Days', 'booking' ) ),
						// 'disabled1' => array( 'type' => 'simple', 'value' => '19', 'title' => __( 'This is option was disabled', 'booking' ), 'disabled' => true ),

						'0' => __( 'Current dates', 'booking' ),
						'1' => __( 'Today', 'booking' ),
						'2' => __( 'Previous dates', 'booking' ),
						'3' => __( 'All dates', 'booking' ),

						'divider1' => array( 'type' => 'html', 'html' => '<hr/>' ),

						'9' => __( 'Today check in/out', 'booking' ),
						'7' => __( 'Check In - Tomorrow', 'booking' ),
						'8' => __( 'Check Out - Tomorrow', 'booking' ),

						'divider2' => array( 'type' => 'html', 'html' => '<hr/>' ),

						// Next  [ '4', '10' ]		- radio button (if selected)  value '4'    and select-box with  selected value   '10'
					    'next' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								// recheck if LI selected: 	 $options['next']['selected_options_value'] == $params['default],  e.g. ~  [ '4', '10' ]
								'selected_options_value' => array(
																	1 => array( 'value' ),					//  $options['next']['input_options'][ 1 ]['value']				'4'
																	4 => array( 'value' ) 					//  $options['next']['input_options'][ 4 ]['value']				'10'
																),
								// Get selected Title, for dropdown if $options['next'] selected
								'selected_options_title' => array(
																	1 => array( 'label', 'title' ),			// $options['next']['input_options'][ 1 ]['label'][ 'title' ]		'Next'
																	'text1' => ': ',						// if key 'text1' not exist in ['input_options'], then it text	': '
																	4 => array( 'options', $request_input_el_default['ui_wh_booking_date_next'] )					// 	'10 days'
																),
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element">' )
													, array(	 														// Default options from simple input element, like: wpbc_flex_radio()
														  'type' => 'radio'
														, 'id'       => 'ui_wh_booking_date_radio_1' 					// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_radio'
														, 'label'    => array( 'title' => __('Next' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 												// CSS of select element
														, 'class'    => '' 												// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 										// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''												// aria-label parameter
														, 'value'    => '4'
														, 'selected' => ( $request_input_el_default[ 'ui_wh_booking_date_radio' ] == '4' ) ? true : false 				// Selected or not
														, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"	// JavaScript code
														, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"	// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element">' )
													, array(
															'type' => 'select'
														  , 'attr' => array()
														  , 'name' => 'ui_wh_booking_date_next'
														  , 'id' => 'ui_wh_booking_date_next'
														  , 'options' => $dates_interval
														  , 'value' => $request_input_el_default[ 'ui_wh_booking_date_next']
														  , 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_1').prop('checked', true);"									// JavaScript code
														  , 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"	// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),

						// Prior  [ '5', '10' ]
						'prior' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'style' => 'min-width: 244px;',
								'selected_options_value' => array( 1 => array( 'value' ), 4 => array( 'value' ) ),		//  4 => array( 'value' )  			-->   $complex_option['input_options'][4]['value']
								'selected_options_title' => array(    1 => array( 'label', 'title' )
																	, 'text1' => ': '
																	, 4 => array( 'options' , $request_input_el_default[ 'ui_wh_booking_date_prior'] )
																 ), 													//  1 => array( 'label', 'title' )  --> $complex_option['input_options'][1]['label'][ 'title' ]
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element">' )
													, array(
															'type' => 'radio'

														, 'id'       => 'ui_wh_booking_date_radio_2' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_radio'
														, 'label'    => array( 'title' => __('Prior' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''					// aria-label parameter
														, 'value'    => '5' 		// Some Value from optins array that selected by default
														, 'selected' => ( $request_input_el_default[ 'ui_wh_booking_date_radio' ] == '5' ) ? true : false 				// Selected or not
														, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
														, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element">' )
													, array(
															'type' => 'select'
														  , 'attr' => array()
														  , 'name' => 'ui_wh_booking_date_prior'
														  , 'id' => 'ui_wh_booking_date_prior'
														  , 'options' => $dates_interval
														  , 'value' => $request_input_el_default[ 'ui_wh_booking_date_prior']
														  , 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_2').prop('checked', true);"					// JavaScript code
														  , 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
							),

						// Fixed [ '6', '', '2022-05-21']
					    'fixed' => array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'selected_options_value' => array( 1 => array( 'value' ), 4 => array( 'value' ), 7 => array( 'value' ) ),												//  4 => array( 'value' )  			-->   $complex_option['input_options'][4]['value']
								'selected_options_title' => array( 1 => array( 'label', 'title' ), 'text1' => ': ', 4 => array( 'value' ), 'text2' => ' - ' ,7 => array( 'value' ) ),	//  1 => array( 'label', 'title' )  -->   $complex_option['input_options'][1]['label'][ 'title' ]
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex:1 1 100%;margin-top:5px;">' )
													, array(
															'type' => 'radio'

														, 'id'       => 'ui_wh_booking_date_radio_3' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_radio'
														, 'label'    => array( 'title' => __('Dates' ,'booking') , 'position' => 'right' )
														, 'style'    => '' 					// CSS of select element
														, 'class'    => '' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'legend'   => ''					// aria-label parameter
														, 'value'    => '6' 		// Some Value from optins array that selected by default
														, 'selected' => ( $request_input_el_default[ 'ui_wh_booking_date_radio' ] == '6' ) ? true : false 				// Selected or not
														, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
														, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 4px 4px 0;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_booking_date_checkin' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_checkin'
														, 'label'    => ''//__('Check-in' ,'booking')
														, 'style'    => 'width:100%;' 					// CSS of select element
														, 'class'    => 'wpdevbk-filters-section-calendar' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('Check-in' ,'booking')
														, 'value'    => $request_input_el_default[ 'ui_wh_booking_date_checkin'] 	// Some Value from optins array that selected by default
														, 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_3').prop('checked', true);"					// JavaScript code
														, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex-flow: row wrap;padding: 4px 0 4px 4px;">' )
													, array(
														  'type'     => 'text'
														, 'id'       => 'ui_wh_booking_date_checkout' 		// HTML ID  of element
														, 'name'     => 'ui_wh_booking_date_checkout'
														, 'label'    => ''//__('Check-out' ,'booking')
														, 'style'    => 'width:100%;' 					// CSS of select element
														, 'class'    => 'wpdevbk-filters-section-calendar' 					// CSS Class of select element
														, 'disabled' => false
														, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
														, 'placeholder' => __('Check-out' ,'booking')
														, 'value'    => $request_input_el_default[ 'ui_wh_booking_date_checkout']  		// Some Value from optins array that selected by default
														, 'onfocus' =>  "jQuery('#ui_wh_booking_date_radio_3').prop('checked', true);"					// JavaScript code
														, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),

						'divider3' => array( 'type' => 'html', 'html' => '<hr/>' ),

					 	// Buttons
					    'buttons1' =>  array(
								'type'  => 'complex',
								'class' => 'ui_complex_option_element',
								'style' => 'justify-content: flex-end;',
								'input_options' => array(
													  array( 'type' => 'html',  'html' => '<div class="ui_element" style="flex: 0 1 auto;margin: 0;">' )
													, array(
															'type' => 'button'
														  , 'title' => __( 'Apply', 'booking' )                     										// Title of the button
														  , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "wpbc_ui_dropdown_apply_click( {
														  												'dropdown_id'		 : 'wh_booking_date',
														  												'dropdown_radio_name': 'ui_wh_booking_date_radio'
																									} );"				// JavaScript code
														  , 'class' => 'wpbc_ui_button_primary'     // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => ''
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => ''                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
													, array( 'type' => 'html', 'html' => '<div class="ui_element" style="flex: 0 1 auto;margin: 0 0 0 1em;">' )
													, array(
															'type' => 'button'
														  , 'title' => __( 'Close', 'booking' )                     // Title of the button
														  , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
														  , 'link' => 'javascript:void(0)'    // Direct link or skip  it
														  , 'action' => "wpbc_ui_dropdown_close_click( 'wh_booking_date' );"                    // JavaScript code
														  , 'class' => ''     				  // wpbc_ui_button  | wpbc_ui_button_primary
														  , 'icon' => ''
														  , 'font_icon' => ''
														  , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
														  , 'style' => ''                     // Any CSS class here
														  , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
														  , 'attr' => array()
													  )
													, array( 'type' => 'html', 'html' => '</div>' )
											)
						),
				);

    $params = array(
					  'id'      => 'wh_booking_date'
					, 'default' => $request_input_el_default[ 'wh_booking_date' ]
                    , 'label' 	=> ''//__('Approve 1', 'booking') . ':'
                    , 'title' 	=> ''//__('Approve 2', 'booking')
					, 'hint' 	=> array( 'title' => __('Filter bookings by booking dates' ,'booking') , 'position' => 'top' )
					, 'align' 	=> 'left'
					, 'li_options' => $options
                );

	?><div class="ui_element ui_nowrap"><?php

		wpbc_flex_addon( $params_addon );

		wpbc_flex_dropdown( $params );

	?></div><?php

 *
 * =====================================================================================================================
 */
function wpbc_flex_dropdown( $args = array() ) {

	// $milliseconds = round( microtime( true ) * 1000 );

    $defaults = array(
                          'id' => ''                        // HTML ID  of element					Example: 'wh_booking_date'
                        , 'default' => array()              // Selected by default value(s)			Example: 'default' 	=> array( $defaults['wh_booking_date'] , $defaults['wh_booking_date2'] )
						, 'hint'  => ''    					// Mouse over tooltip					Example: 'hint' 	=> array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
						, 'style' => '' 					// CSS style of dropdown element (optional)
						, 'class' => '' 					// CSS class of dropdown element (optional)
						, 'label' => ''                     // Label of element "at Top of element"
						, 'title' => ''                     // Title of element "Inside of element"
						, 'align' => 'left'                 // Align: left | right
                        , 'li_options'  => array()                                 // Options
                        , 'disabled' => array()                                 // If some options disabled,  then option values list here
						, 'onfocus'  => ''										// JavaScript code
						, 'onchange' => ''										// JavaScript code
						, 'is_use_for_template' => false						// In case, if we are using it for template, then we skip  JavaScript code for initial value. Need to define it manually.		//FixIn: 9.4.3.5
                    );
    $params = wp_parse_args( $args, $defaults );

	// If default value not array,  then  define it as single value in arr.
	if ( ! is_array( $params['default'] ) ) {
		$params['default'] = array( $params['default'] );
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/**
	 * Recomposition of  simple options configuration  from
	 *   	'any' => __( 'Any', 'booking' )
	 *  to
	 * 		'any' => array( 'type' => 'simple', 'value' => 'any', 'title' => __( 'Any', 'booking' ) );
	 */
    $is_this_simple_list =  true;
    foreach ( $params['li_options'] as $key_value => $option_data ) {

		if ( ! is_array( $option_data ) ) {

            $params['li_options'][ $key_value ] = array( 'type' => 'simple', 'value' => (string) $key_value, 'title' => $option_data );

        } else {
			if ( ( isset( $option_data['type'] ) ) && ( 'complex' == $option_data['type'] ) ) {
				$is_this_simple_list = false;
			}
		}
    }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Rechecking about selected LI option,  based on $params['default'] like  ['4','10']  and getting title of such  option
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$default_selected_title = array();
	foreach ( $params['li_options'] as $li_option ) {

		if ( 'simple' == $li_option['type'] ) {
			if ( $li_option['value'] === $params['default'][0] ) {
				$default_selected_title = $li_option['title'] ;
			}
		}

		if ( 'complex' == $li_option['type'] ) {

			// $option[ 'selected_options_value' ] => array( 1 => array( 'value' ), 4 => array( 'value' ) ),
			// $option[ 'selected_options_title' ] => array( 1 => array( 'label', 'title' ), 'text1' => ': ', 4 => array( 'value' ) ),

			// Get value of this LI
			$li_this_value = array();
			if ( isset( $li_option['selected_options_value'] ) )
				foreach ( $li_option['selected_options_value'] as $li_key => $input_keys ) {

					if ( isset( $li_option['input_options'][ $li_key ] ) ) {									// ['input_options'][4]

						$li_input_deep_value = $li_option['input_options'][ $li_key ];

						foreach ( $input_keys as $input_key_value ) {

							if ( isset( $li_input_deep_value[$input_key_value] ) ) {				// ['input_options'][4]['value']
								$li_input_deep_value = $li_input_deep_value[ $input_key_value ];
							}
						}
						$li_this_value[] = $li_input_deep_value;
					}
				}

			// Is this LI selected ?
			$is_same_value = array_diff( $params['default'], $li_this_value ) == array();

			if ( $is_same_value ) {

				// Get value of this LI
				$li_this_value = array();
				foreach ( $li_option['selected_options_title'] as $li_key => $input_keys ) {

					if ( isset( $li_option['input_options'][ $li_key ] ) ) {									// ['input_options'][4]

						$li_input_deep_value = $li_option['input_options'][ $li_key ];

						foreach ( $input_keys as $input_key_value ) {

							if ( isset( $li_input_deep_value[$input_key_value] ) ) {						// ['input_options'][4]['value']
								$li_input_deep_value = $li_input_deep_value[ $input_key_value ];
							}
						}
						$li_this_value[] = $li_input_deep_value;
					} else {
						$li_this_value[] = $input_keys; //some text
					}
				}

				$default_selected_title = implode( '', $li_this_value );
			}
		}

		if ( ! empty( $default_selected_title ) ) {
			break;
		}
	}

	if ( is_array( $default_selected_title ) ) {
		$default_selected_title = implode( '', $default_selected_title );		// Error::   ? no values ?
	}


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Show only, 		if 		$item_params['label']   	! EMPTY
	wpbc_flex_label(
						array(
							  'id' 	  => $params['id']
							, 'label' => $params['label']
							, 'class' => 'wpbc_ui_dropdown__outside_label'
						)
				   );

    ?><div    class="wpbc_ui_dropdown <?php echo esc_attr( $params['class'] ); ?>"
              style="<?php echo esc_attr( $params['style'] ); ?>"
		><?php

			?><a 	 href="javascript:void(0)"
					 id="<?php echo esc_attr( $params['id'] ); ?>_selector"
					 data-toggle="wpbc_dropdown"
					 class="wpbc_ui_control wpbc_ui_button dropdown-toggle <?php
							echo ( ! empty( $params['hint'] ) ) ? 'tooltip_' . $params['hint']['position'] . ' ' : '' ;
							?>"
					 <?php if (! $is_this_simple_list ) { ?>
					 	onclick="javascript:jQuery('#<?php echo $params['id']; ?>_container').show();"
					 <?php } ?>
					  <?php if ( ! empty( $params['hint'] ) ) { ?>
					 	title="<?php  echo esc_attr( $params['hint']['title'] ); ?>"
					  <?php } ?>
					 <?php echo wpbc_get_custom_attr( $params ); ?>
			><?php

				  	?><label class="wpbc_ui_dropdown__inside_label" <?php if ( empty( $params['title'] ) )  { echo ' style="display:none;" '; } ?> ><?php

						echo html_entity_decode(
											  wp_kses_post( $params['title'] )			// Sanitizes content for allowed HTML tags for post content
											, ENT_QUOTES								// Convert &quot;  to " and '
											, get_bloginfo( 'charset' ) 				// 'UTF-8'  or other
										);												// Convert &amp;dash;  to &dash;  etc...
						  ?>: <?php
				    ?></label> <?php

				  	?><span class="wpbc_selected_in_dropdown" ><?php
						echo html_entity_decode(
											  wp_kses_post( $default_selected_title )	// Sanitizes content for allowed HTML tags for post content
											, ENT_QUOTES								// Convert &quot;  to " and '
											, get_bloginfo( 'charset' ) 				// 'UTF-8'  or other
										);												// Convert &amp;dash;  to &dash;  etc...
				    ?></span> &nbsp; <?php

				    ?><span class="wpbc_ui_dropdown__inside_caret"></span><?php

			?></a><?php

			?><ul id="<?php echo $params['id']; ?>_container" 	class="ui_dropdown_menu ui_dropdown_menu-<?php echo esc_attr( $params['align'] ); ?>" ><?php

						wpbc_flex_dropdown__options( $params, array( 'is_this_simple_list' => $is_this_simple_list ) );

			?></ul><?php

			?><input type="hidden"
					 autocomplete="off"
					 value=""
					 id="<?php 		echo esc_attr( $params['id'] ); ?>"
					 name="<?php 	echo esc_attr( $params['id'] ); ?>"
				/><?php
		if( ! $params['is_use_for_template'] ){                                                                         //FixIn: 9.4.3.5
			?>
			<script type="text/javascript">
				<?php /* document.getElementById("<?php echo $params['id']; ?>").value = "<?php echo wp_slash( json_encode($params['default']  ) ); ?>";  */ ?>
				jQuery(document).ready(function(){

					jQuery( '#<?php echo $params['id']; ?>').val( "<?php echo wp_slash( json_encode( $params['default'] ) ); ?>" );

					<?php if (! empty( $params['onchange'] )) { ?>

						jQuery( '#<?php echo $params['id']; ?>' ).on( 'change', function( event ){

							<?php echo $params['onchange']; ?>
						});

					<?php } ?>

					<?php if (! empty( $params['onfocus'] )) { ?>

						jQuery( '#<?php echo $params['id']; ?>_selector' ).on( 'focus', function( event ){

							<?php echo $params['onfocus']; ?>
						});

					<?php } ?>
				})
			</script>
			<?php
		}
	?></div><?php
}

/**
 * Options list  for Dropdown
 *
 * @param       $params
 * @param array $args
 */
function wpbc_flex_dropdown__options( $params, $args = array() ) {

	$defaults = array(
		// 'milliseconds'        => round( microtime( true ) * 1000 ),
		'is_this_simple_list' => true
	);
	$args   = wp_parse_args( $args, $defaults );


	foreach ( $params['li_options'] as $option_name => $li_option ) {

		$default_option = array(
								  'type'  => ''
								, 'class' => ''
								, 'style' => ''
								, 'title' => ''
								, 'disabled' => false
								, 'attr'     => array()
								, 'value'    => ''
								, 'html'     => ''
							);
		$li_option = wp_parse_args( $li_option, $default_option );


		// Is disabled ?
		if ( true === in_array( $li_option['value'], $params['disabled'] ) ) {
			$li_option['disabled'] = true;
		}
		if ( $li_option['disabled'] ) {
			$li_option['class'] .= ' disabled';
		}
		// Is header ?
		if ( 'header' == $li_option['type'] ) {
			$li_option['class'] .= ' dropdown-header';
		}


		?><li role="presentation"
			  class="<?php echo esc_attr( $li_option['class'] ); ?>"
			  style="<?php echo esc_attr( $li_option['style'] ); ?>"
			  <?php echo wpbc_get_custom_attr( $li_option ); ?>
		><?php

			switch ( $li_option['type'] ) {

				case 'simple':

					?><a  role="menuitem"
						  tabindex="-1"
		  			<?php if ( ! $li_option['disabled'] ) {

								if( false !== filter_var( $li_option['value'], FILTER_VALIDATE_URL ) ){ ?>

											href="<?php echo $li_option['value']; ?>"

								<?php } else { ?>

											href="javascript:void(0)"
											onclick="javascript: wpbc_ui_dropdown_simple_click( {
																								  'dropdown_id'        : '<?php echo $params['id']; ?>'
																								, 'is_this_simple_list':  <?php echo ( $args['is_this_simple_list'] ) ? 'true' : 'false'; ?>
																								, 'value'              : '<?php echo $li_option['value']; ?>'
																								, '_this'              : this
																							} );"
								<?php }

					} ?>
					   ><?php
						  echo $li_option['title'];
					?></a><?php

					break;

				case 'html':
					echo $li_option['html'];
					break;

				case 'header':
					echo $li_option['title'];
					break;

				case 'complex' :

					foreach ( $li_option['input_options'] as $input_option ) {

						switch ( $input_option['type'] ) {

							case 'html':
								echo $input_option['html'];
								break;

							case 'button':
								wpbc_flex_button( $input_option );
								break;

							case 'label':
								wpbc_flex_label( $input_option );
								break;

							case 'text':
								wpbc_flex_text( $input_option );
								break;

							case 'select':
								wpbc_flex_select( $input_option );
								break;

							case 'checkbox':
								wpbc_flex_checkbox( $input_option );
								break;

							case 'radio':
								wpbc_flex_radio( $input_option );
								break;

							case 'addon':
								wpbc_flex_addon( $input_option );
								break;

							default: // Default
						}
					}
					break;

				default: // Default
			}

		?></li><?php
	}
}


////////////////////////////////////////////////////////////////////////////////
//  Simple  elements
////////////////////////////////////////////////////////////////////////////////

/**
 * Show FLEX Button
 *
 * @param array $item
                        array(
                                'type' => 'button'
                              , 'title' => ''                     // Title of the button
                              , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
                              , 'link' => 'javascript:void(0)'    // Direct link or skip  it
                              , 'action' => ''                    // Some JavaScript to execure, for example run  the function
                              , 'class' => ''     				  // wpbc_ui_button  | wpbc_ui_button_primary
                              , 'icon' => ''
                              , 'font_icon' => ''
                              , 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
                              , 'style' => ''                     // Any CSS class here
                              , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
                              , 'attr' => array()
                        );
 */
function wpbc_flex_button( $item ) {

    $default_item_params = array(
                                'type' => 'button'
                              , 'title' => ''                     // Title of the button
                              , 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
                              , 'link' => 'javascript:void(0)'    // Direct link or skip  it
                              , 'action' => ''                    // Some JavaScript to execure, for example run  the function
		                      , 'id' 	=> ''     				  // ''  | 'wpbc_ui_button_primary'
                              , 'class' => ''     				  // ''  | 'wpbc_ui_button_primary'
							  , 'icon' => false					  // array( 'icon_font' => 'wpbc_icn_check_circle_outline', 'position' => 'right', 'icon_img' => '' )
                              , 'style' => ''                     // Any CSS class here
                              , 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
                              , 'attr' => array()
							  , 'options' => array( 'link' => 'esc_attr' ) // array( 'link' => 'decode' )
                        );
    $item_params = wp_parse_args( $item, $default_item_params );

	$css_button_class = esc_attr( $item_params['class'] );

	if ( 'wpbc_button_as_icon' !== $css_button_class ) {
		$css_button_class = 'wpbc_ui_button ' . $css_button_class;
	}

    ?><a  class="wpbc_ui_control <?php echo $css_button_class;
													  echo ( ! empty( $item_params['hint'] ) ) ? ' tooltip_' . esc_attr( $item_params['hint']['position'] ) . ' ' : '' ; ?>"
		  style="<?php 		  echo esc_attr( $item_params['style'] ); ?>"
		  href="<?php
					  if ( 'esc_attr' == $item_params['options']['link'] ) {
						  echo esc_attr( $item_params['link'] );
					  }
					  if ( 'decode' == $item_params['options']['link'] ) {
						  echo str_replace( '"', '', htmlspecialchars_decode( esc_attr( $item_params['link'] ), ENT_QUOTES ) );
					  }
					  if ( 'no_decode' == $item_params['options']['link'] ) {
						  echo str_replace( '"', '',  $item_params['link'] );
					  }
		  		?>"
          <?php if ( ! empty( $item_params['id'] ) ) { ?>
              id="<?php echo $item_params['id']; ?>"
          <?php } ?>
          <?php if ( ! empty( $item_params['action'] ) ) { ?>
              onclick="javascript:<?php echo wpbc_esc_js( $item_params['action'] ); ?>"
          <?php } ?>
		  <?php if ( ! empty( $item_params['hint'] ) ) { ?>
			  title="<?php  echo esc_attr( $item_params['hint']['title'] ); ?>"
		  <?php } ?>
          <?php echo wpbc_get_custom_attr( $item_params ); ?>
        ><?php
              $btn_icon = '';

			  // Icon
			  if ( ( ! empty( $item_params['icon'] ) ) && ( is_array( $item_params['icon'] ) ) ) {

				  // Icon IMG
				  if ( ! empty( $item_params['icon']['icon_img'] ) ) {

					  if ( substr( $item_params['icon']['icon_img'], 0, 4 ) != 'http' ) {
						  $img_path = WPBC_PLUGIN_URL . '/assets/img/' . $item_params['icon']['icon_img'];
					  } else {
						  $img_path = $item_params['icon']['icon_img'];
					  }
					  $btn_icon = '<img class="menuicons" src="' . esc_url( $img_path ) . '" />';    // Img  Icon
				  }

				  // Icon Font
				  if ( ! empty( $item_params['icon']['icon_font'] ) ) {
					  $btn_icon = '<i class="menu_icon icon-1x ' . esc_attr( $item_params['icon']['icon_font'] ) . '"></i>';                         // Font Icon
				  }
			  }

			  if ( ( ! empty( $btn_icon ) ) && ( $item_params['icon']['position'] == 'left' ) ) {

				  echo $btn_icon;

				  if ( ! empty( $item_params['title'] ) ) {
					  echo '&nbsp;';
				  }
			  }

              // Text
              echo '<span' . ( (  ( ! empty( $btn_icon ) )  && ( ! $item_params['mobile_show_text'] ) )? ' class="in-button-text"' : '' ) . '>';

				echo html_entity_decode(
											  wp_kses_post( $item_params['title'] )		// Sanitizes content for allowed HTML tags for post content
											, ENT_QUOTES								// Convert &quot;  to " and '
											, get_bloginfo( 'charset' ) 				// 'UTF-8'  or other
										);												// Convert &amp;dash;  to &dash;  etc...

			  	if ( ( ! empty( $btn_icon ) ) && ( $item_params['icon']['position'] == 'right' ) ) {
					echo '&nbsp;';
				}

              echo '</span>';

				if ( ( ! empty( $btn_icon ) ) && ( $item_params['icon']['position'] == 'right' ) ) {
					echo $btn_icon;
				}
    ?></a><?php
}

/**
 * Show FLEX Label
 *
 * @param array $item
                        array(
                                  'id' => ''                        // HTML ID  of INPUT  element
                                , 'label' => __('Text..','booking') // Label  text  here
                                , 'style' => ''                     // CSS of select element
                                , 'class' => ''                     // CSS Class of select element
                                , 'disabled' => false
                                , 'attr' => array()                 // Any  additional attributes
                        )
*/
function wpbc_flex_label( $item ) {

    $default_item_params = array(
                                  'id' => ''                        // HTML ID  of element
								, 'label' => ''						// Label
                                , 'style' => ''                     // CSS of select element
                                , 'class' => ''                     // CSS Class of select element
                                , 'disabled' => false
								, 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
                                , 'attr' => array()                 // Any additional attributes, if this radio | checkbox element
                        );
    $item_params = wp_parse_args( $item, $default_item_params );

	if ( ( ! empty( $item_params['label'] ) ) || ( ! empty( $btn_icon ) ) ) {

		?><label for="<?php echo esc_attr( $item_params['id'] ); ?>"
				 class="wpbc_ui_control_label <?php echo esc_attr( $item_params['class'] );
				 									echo ( ! empty( $item_params['hint'] ) ) ? ' tooltip_' . esc_attr( $item_params['hint']['position'] ) . ' ' : '' ; ?>"
				 style="<?php echo esc_attr( $item_params['style'] ); ?>"
				  <?php if ( ! empty( $item_params['hint'] ) ) { ?>
					  title="<?php  echo esc_attr( $item_params['hint']['title'] ); ?>"
				  <?php } ?>
				<?php disabled( $item_params['disabled'], true ); ?>
				<?php echo wpbc_get_custom_attr( $item_params ); ?>
		><?php

		echo html_entity_decode(
									  wp_kses_post( $item_params['label'] )		// Sanitizes content for allowed HTML tags for post content
									, ENT_QUOTES								// Convert &quot;  to " and '
									, get_bloginfo( 'charset' ) 				// 'UTF-8'  or other
								);												// Convert &amp;dash;  to &dash;  etc...
		?></label><?php
	}

}

/**
 * Show FLEX text
 *
 * @param array $item
 *
 *  Example:
				$params_checkbox = array(
 										  'id'       => 'my_check_id' 		// HTML ID  of element
										, 'name'     => 'my_check_id'
										, 'label'    => __('Approve' ,'booking')
										, 'style'    => '' 					// CSS of select element
										, 'class'    => '' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
										, 'placeholder' => ''
										, 'value'    => 'CHECK_VAL_1' 		// Some Value from optins array that selected by default
										, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
										, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
                        );
 				?><div class="ui_element"><?php

				wpbc_flex_text( $params_select );

				?></div><?php
 *
 */
function wpbc_flex_text( $item ) {

    $default_item_params = array(
                                  'type'        => 'text'
                                , 'id'          => ''
                                , 'name'        => ''
                                , 'label'       => ''
                                , 'disabled'    => false
                                , 'class'       => ''
                                , 'style'       => ''
                                , 'placeholder' => ''
                                , 'attr'        => array()
                                , 'value' 		=> ''
								, 'is_escape_value' => true
                                , 'onfocus' => ''					// JavaScript code
                                , 'onchange' => ''					// JavaScript code
								, 'onkeydown' => ''					// JavaScript code

    );
    $item_params = wp_parse_args( $item, $default_item_params );

	if ( 		 ( empty( $item_params['name'] ) )
			&& ( ! empty( $item_params['id'] ) )
	) {
		$item_params['name'] = $item_params['id'];
	}

	// Show only, 		if 		$item_params['label']   	! EMPTY
	wpbc_flex_label(
						array(
							  'id' 	  => $item_params['id']
							, 'label' => $item_params['label']
						)
				   );

	if ( $item_params['is_escape_value'] ) {
		$escaped_value = esc_attr( $item_params['value'] );
	} else {
		$escaped_value = $item_params['value'];
	}

    ?><input  type="<?php 	echo esc_attr( $item_params['type'] ); ?>"
              id="<?php 	echo esc_attr( $item_params['id'] ); ?>"
              name="<?php 	echo esc_attr( $item_params['name'] ); ?>"
              style="<?php 	echo esc_attr( $item_params['style'] ); ?>"
              class="wpbc_ui_control wpbc_ui_text <?php echo esc_attr( $item_params['class'] ); ?>"
              placeholder="<?php 	echo esc_attr( $item_params['placeholder'] ); ?>"
              value="<?php 	echo $escaped_value; ?>"
	 		  autocomplete="off"
              <?php disabled( $item_params['disabled'], true ); ?>
              <?php echo wpbc_get_custom_attr( $item_params ); ?>
              <?php
                if ( ! empty( $item_params['onfocus'] ) ) {
                    ?> onfocus="javascript:<?php echo wpbc_esc_js( $item_params['onfocus'] ); ?>" <?php
                }
				if ( ! empty( $item_params['onchange'] ) ) {
					?> onchange="javascript:<?php echo wpbc_esc_js( $item_params['onchange'] ); ?>" <?php
				}
				if ( ! empty( $item_params['onkeydown'] ) ) {
					?> onkeydown="javascript:<?php echo wpbc_esc_js( $item_params['onkeydown'] ); ?>" <?php
				}
              ?>
          /><?php
}

/**
 * Show FLEX textarea
 *
 * @param array $item
 *
 *  Example:
				$params_textarea = array(
 										  'id'       => 'my_check_id' 		// HTML ID  of element
										, 'name'     => 'my_check_id'
										, 'label'    => __('Approve' ,'booking')
										, 'style'    => '' 					// CSS of select element
										, 'class'    => '' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
										, 'rows' 		=> '3'
										, 'cols' 		=> '50'
										, 'placeholder' => ''
										, 'value'    => 'Test VAL 1' 		// Some Value from optins array that selected by default
										, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
										, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
                        );
 				?><div class="ui_element"><?php

					wpbc_flex_textarea( $params_textarea );

				?></div><?php
 *
 */
function wpbc_flex_textarea( $item ) {

    $default_item_params = array(
                                  'id'          => ''
                                , 'name'        => ''
                                , 'label'       => ''
                                , 'disabled'    => false
                                , 'class'       => ''
                                , 'style'       => ''
                                , 'placeholder' => ''
                                , 'attr'        => array()
                        		, 'rows' 		=> '3'
                                , 'cols' 		=> '50'
                                , 'value' 		=> ''
								, 'is_escape_value' => true
                                , 'onfocus' => ''					// JavaScript code
                                , 'onchange' => ''					// JavaScript code
    );
    $item_params = wp_parse_args( $item, $default_item_params );

	if ( 		 ( empty( $item_params['name'] ) )
			&& ( ! empty( $item_params['id'] ) )
	) {
		$item_params['name'] = $item_params['id'];
	}

	// Show only, 		if 		$item_params['label']   	! EMPTY
	wpbc_flex_label(
						array(
							  'id' 	  => $item_params['id']
							, 'label' => $item_params['label']
						)
				   );

	if ( $item_params['is_escape_value'] ) {
		$escaped_value = esc_textarea( $item_params['value'] );
	} else {
		$escaped_value = $item_params['value'];
	}

    ?><textarea   id="<?php 	echo esc_attr( $item_params['id'] ); ?>"
				  name="<?php 	echo esc_attr( $item_params['name'] ); ?>"
				  style="<?php 	echo esc_attr( $item_params['style'] ); ?>"
				  class="wpbc_ui_control wpbc_ui_textarea <?php echo esc_attr( $item_params['class'] ); ?>"
				  placeholder="<?php 	echo esc_attr( $item_params['placeholder'] ); ?>"
				  autocomplete="off"
				  rows="<?php 	echo esc_attr( $item_params['rows'] ); ?>"
				  cols="<?php 	echo esc_attr( $item_params['cols'] ); ?>"
				  <?php disabled( $item_params['disabled'], true ); ?>
				  <?php echo wpbc_get_custom_attr( $item_params ); ?>
				  <?php
					if ( ! empty( $item_params['onfocus'] ) ) {
						?> onfocus="javascript:<?php echo wpbc_esc_js( $item_params['onfocus'] ); ?>" <?php
					}
					if ( ! empty( $item_params['onchange'] ) ) {
						?> onchange="javascript:<?php echo wpbc_esc_js( $item_params['onchange'] ); ?>" <?php
					}
				  ?>
          ><?php echo $escaped_value; ?></textarea><?php
}

/**
	 * Show FLEX selectbox
 *
 * @param array $item
                        array(
                                  'id' => ''                        // HTML ID  of element
                                , 'name' => ''
                                , 'style' => ''                     // CSS of select element
                                , 'class' => ''                     // CSS Class of select element
                                , 'multiple' => false
                                , 'disabled' => false
                                , 'attr' => array()                 // Any  additional attributes, if this radio | checkbox element
                                , 'options' => array()              // Associated array  of titles and values
                                , 'disabled_options' => array()     // If some options disbaled,  then its must list  here
                                , 'value' => ''                     // Some Value from optins array that selected by default
								, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"					// JavaScript code
								, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"					// JavaScript code
                        )
 *
 *
 *  Simple Example :
 *
	 $params_select = array(
						  'id' => 'next_days'                        						// HTML ID  of element
						, 'name' => 'next_days'
		 				, 'label' => '' 	// __( 'Next Days', 'booking' )					// Label (optional)
						, 'style' => ''                     								// CSS of select element
						, 'class' => ''                     								// CSS Class of select element
						, 'multiple' => false
						, 'attr' => array()                 								// Any additional attributes, if this radio | checkbox element
						, 'disabled' => false
		 				, 'disabled_options' => array( 2, 30 )     							// If some options disabled, then it has to list here
						, 'options' => array(												// Associated array of titles and values
											  , 1 => '1' . ' ' . __('day' ,'booking')
											  , 2 => '2' . ' ' . __('days' ,'booking')
											  , 7 => '1' . ' ' . __('week' ,'booking')
											  , 30 => '1' . ' ' . __('month' ,'booking')
											  , 365 => '1' . ' ' . __('Year' ,'booking')
										)

						, 'value' => ( isset( $escaped_search_request_params[ 'next_days' ] ) ) ? $escaped_search_request_params[ 'next_days' ] : '183'	// Some Value from options array that selected by default
						, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"					// JavaScript code
						, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"					// JavaScript code

					  );

 	?><div class="ui_element"><?php

	wpbc_flex_select( $params_select );

	?></div><?php
 *
 *
 *
 * Example complex:
 *
 *
 *

	 $params_select = array(
						  'id' => 'next_days'                        // HTML ID  of element
						, 'name' => 'next_days'
		 				, 'label' => '<span class="" style="font-weight:600;">' . __( 'Cost', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
						, 'style' => ''                     // CSS of select element
						, 'class' => ''                     // CSS Class of select element
						, 'multiple' => false
						, 'attr' => array()                 // Any additional attributes, if this radio | checkbox element
						, 'disabled' => false
		 				, 'disabled_options' => array( 2, 5 )     // If some options disabled, then it has to list here
						, 'options' => array(				// Associated array of titles and values:   array( $option_value => $option_data, ... )
											    'group_days' => array( 'optgroup' => true, 'close' => false, 'title' => __( 'days', 'booking' ) )
											  , 1 => '1' . ' ' . __('day' ,'booking')
											  , 2 => '2' . ' ' . __('days' ,'booking')
											  , 3 => '3' . ' ' . __('days' ,'booking')
											  , 4 => '4' . ' ' . __('days' ,'booking')
											  , 5 => '5' . ' ' . __('days' ,'booking')
											  , 6 => '6' . ' ' . __('days' ,'booking')
			 								  	, 'group_days_end' => array( 'optgroup' => true, 'close' => true )

			 								  	, 'group_weeks' => array( 'optgroup' => true, 'close' => false, 'title' => __( 'weeks', 'booking' ) )
											  , 7 => '1' . ' ' . __('week' ,'booking')
											  , 14 => '2' . ' ' . __('weeks' ,'booking')
			 								  , 'group_weeks_end' => array( 'optgroup' => true, 'close' => true )

			 								  	, 'group_months' => array( 'optgroup' => true, 'close' => false, 'title' => __( 'months', 'booking' ) )
											  , 30 => '1' . ' ' . __('month' ,'booking')
											  , 60 => '2' . ' ' . __('months' ,'booking')
											  , 90 => '3' . ' ' . __('months' ,'booking')
											  , 183 => '6' . ' ' . __('months' ,'booking')
											  , 365 => '1' . ' ' . __('Year' ,'booking')
			 								  	, 'group_months_end' => array( 'optgroup' => true, 'close' => true )

											  	, 'complex_value' => array(
																		  'title' => 'Complex Option Here'		// Text  in selectbox option
																		, 'style' => ''                         // CSS of select element
																		, 'class' => ''                         // CSS Class of select element
																		, 'disabled' => false
																		, 'selected' => true
																		, 'attr' => array()                     // Any  additional attributes, if this radio | checkbox element
																		, 'optgroup' => false                   // Use only  if you need to show OPTGROUP - Also  need to  use 'title' of start, end 'close' for END
																		, 'close'  => false
																)
										)

						, 'value' => ( isset( $escaped_search_request_params[ 'next_days' ] ) ) ? $escaped_search_request_params[ 'next_days' ] : '183'	// Some Value from options array that selected by default
						, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"					// JavaScript code
						, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"					// JavaScript code

					  );

 	?><div class="ui_element"><?php

	wpbc_flex_select( $params_select );

	?></div><?php

 *
 */
function wpbc_flex_select( $item ) {

    $default_item_params = array(
                                  'id' => ''                        // HTML ID  of element
                                , 'name' => ''
								, 'label' => ''						// Label
                                , 'style' => ''                     // CSS of select element
                                , 'class' => ''                     // CSS Class of select element
                                , 'multiple' => false
                                , 'disabled' => false
                                , 'attr' => array()                 // Any additional attributes, if this radio | checkbox element
                                , 'options' => array()              // Associated array of titles and values:   array( $option_value => $option_data, ... )
                                , 'disabled_options' => array()     // If some options disabled, then it has to list here
                                , 'value' => ''                     // Some Value from options array that selected by default
                                , 'onfocus' => ''					// JavaScript code
                                , 'onchange' => ''					// JavaScript code
                        );
    $item_params = wp_parse_args( $item, $default_item_params );

	// Show only, 		if 		$item_params['label']   	! EMPTY
	wpbc_flex_label(
						array(
							  'id' 	  => $item_params['id']
							, 'label' => $item_params['label']
						)
				   );

    ?><select
            id="<?php 	echo esc_attr( $item_params['id'] ); ?>"
            name="<?php echo esc_attr( $item_params['name'] ); echo ( $item_params['multiple'] ? '[]' : '' ); ?>"
            class="wpbc_ui_control wpbc_ui_select <?php echo esc_attr( $item_params['class'] ); ?>"
            style="<?php echo esc_attr( $item_params['style'] ); ?>"
            <?php disabled( $item_params['disabled'], true ); ?>
            <?php echo wpbc_get_custom_attr( $item_params ); ?>
            <?php echo ( $item_params['multiple'] ? ' multiple="MULTIPLE"' : '' ); ?>
            <?php
                if ( ! empty( $item_params['onfocus'] ) ) {
                    ?> onfocus="javascript:<?php echo wpbc_esc_js( $item_params['onfocus'] ); ?>" <?php
                }
                if ( ! empty( $item_params['onchange'] ) ) {
                    ?> onchange="javascript:<?php echo wpbc_esc_js( $item_params['onchange'] ); ?>" <?php
                }
            ?>
            autocomplete="off"
        ><?php
        foreach ( $item_params['options'] as $option_value => $option_data ) {

	        if ( ! is_array( $option_data ) ) {
		        $option_data   = array( 'title' => $option_data );
		        $is_was_simple = true;
	        } else {
		        $is_was_simple = false;
	        }

            $default_option_params = array(
                                          'title' => ''							// Text  in selectbox option
                                        , 'style' => ''                         // CSS of select element
                                        , 'class' => ''                         // CSS Class of select element
                                        , 'disabled' => false
                                        , 'selected' => false
                                        , 'attr' => array()                     // Any  additional attributes, if this radio | checkbox element

                                        , 'optgroup' => false                   // Use only  if you need to show OPTGROUP - Also  need to  use 'title' of start, end 'close' for END
                                        , 'close'  => false

                                );
            $option_data = wp_parse_args( $option_data, $default_option_params );

            if ( $option_data['optgroup'] ) {                                   // OPTGROUP

                if ( ! $option_data['close'] ) {
                    ?><optgroup label="<?php  echo esc_attr( $option_data['title'] ); ?>"><?php
                } else {
                    ?></optgroup><?php
                }

            } else {                                                            // OPTION

                ?><option
                        value="<?php echo esc_attr( $option_value ); ?>"
                        <?php
                        if ( $is_was_simple ) {
                            selected( $option_value, $item_params['value'] );
                            disabled( in_array( $option_value, $item_params['disabled_options'] ), true );
                        }
                        ?>
                    class="<?php echo esc_attr( $option_data['class'] ); ?>"
                    style="<?php echo esc_attr( $option_data['style'] ); ?>"
                    <?php echo wpbc_get_custom_attr( $option_data ); ?>
                    <?php selected(  $option_data['selected'], true ); ?>
					<?php disabled( $option_data['disabled'], true ); ?>

					<?php
					if ( ! empty( $item_params['value'] ) ) {

						if ( is_array( $item_params['value'] ) ) {
							selected( in_array( esc_attr( $option_value ), $item_params['value'] ), true );		// SELECT multiple,  have several items
						} else {
							selected( $item_params['value'], esc_attr( $option_value ) ); 						//Recheck  global  selected parameter
						}
					}
					?>

                ><?php
					echo html_entity_decode(
												  wp_kses_post( $option_data['title'] )		// Sanitizes content for allowed HTML tags for post content
												, ENT_QUOTES								// Convert &quot;  to " and '
												, get_bloginfo( 'charset' ) 				// 'UTF-8'  or other
											);												// Convert &amp;dash;  to &dash;  etc...


				?></option><?php
            }
        }
    ?></select><?php
}

/**
 * Show FLEX checkbox
 *
 * @param array $item
 *
 *  Example:
				$params_checkbox = array(
										  'id'       => 'my_check_id' 		// HTML ID  of element
										, 'name'     => 'my_check_id'
										, 'label'    => array( 'title' => __('Approve' ,'booking') , 'position' => 'right' )
										, 'style'    => '' 					// CSS of select element
										, 'class'    => '' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
										, 'legend'   => ''					// aria-label parameter
										, 'value'    => 'CHECK_VAL_1' 		// Some Value from optins array that selected by default
										, 'selected' => !false 				// Selected or not
										, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
										, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
                    					, 'is_use_toggle' => false          // Show checkbox  as toggle
                    					, 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
									);
				?><div class="ui_element"><?php

				wpbc_flex_checkbox( $params_select );

				?></div><?php
 */
function wpbc_flex_checkbox( $item ) {

    $default_item_params = array(
                                  'type' => 'checkbox'
                                , 'id' => ''                        // HTML ID  of element
                                , 'name' => ''
								, 'label' => ''      				// Label	Example: 'label' => array( 'title' => __('Select status' ,'booking') , 'position' => 'left' )
                                , 'style' => ''                     // CSS of select element
                                , 'class' => ''                     // CSS Class of select element
                                , 'disabled' => false
                                , 'attr' => array()                 // Any  additional attributes, if this radio | checkbox element
                                , 'legend' => ''                    // aria-label parameter
                                , 'value' => ''                     // Some Value from optins array that selected by default
                                , 'selected' => false               // Selected or not
                                , 'onfocus' => ''					// JavaScript code
                                , 'onchange' => ''					// JavaScript code
								, 'is_use_toggle' => false          // Show checkbox  as toggle
								, 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
                      );
    $item_params = wp_parse_args( $item, $default_item_params );


	// Use toggle element,  instead of checkbox.
	if ( $item_params['is_use_toggle'] ) {

		wpbc_flex_toggle( $item );
		return;
	}

	if ( ( ! empty( $item_params['label'] ) ) && ( 'left' == $item_params['label']['position'] ) ) {
		wpbc_flex_label( array( 'id' => $item_params['id'], 'label' => $item_params['label']['title'] ) );
	}

    ?><input    type="<?php echo esc_attr( $item_params['type'] ); ?>"
                id="<?php echo esc_attr( $item_params['id'] ); ?>"
                name="<?php echo esc_attr( $item_params['name'] ); ?>"
                value="<?php echo esc_attr( $item_params['value'] ); ?>"
                aria-label="<?php echo esc_attr( $item_params['legend'] ); ?>"
                class="wpbc_ui_<?php echo esc_attr( $item_params['type'] ); ?> <?php echo esc_attr( $item_params['class'] ); ?> <?php echo ( ! empty( $item_params['hint'] ) ) ? ' tooltip_' . esc_attr( $item_params['hint']['position'] ) . ' ' : '' ; ?>"
                style="<?php echo esc_attr( $item_params['style'] ); ?>"
				<?php
					if ( ! empty( $item_params['hint'] ) ) { ?>
						title="<?php echo esc_attr( $item_params['hint']['title'] ); ?>" <?php
					}
				?>
                <?php echo wpbc_get_custom_attr( $item_params ); ?>
                <?php checked(  $item_params['selected'], true ); ?>
                <?php disabled( $item_params['disabled'], true ); ?>
				<?php
					if ( ! empty( $item_params['onfocus'] ) ) {
						?> onfocus="javascript:<?php echo wpbc_esc_js( $item_params['onfocus'] ); ?>" <?php
					}
					if ( ! empty( $item_params['onchange'] ) ) {
						?> onchange="javascript:<?php echo wpbc_esc_js( $item_params['onchange'] ); ?>" <?php
					}
				?>
				autocomplete="off"
        /><?php

	if ( ( ! empty( $item_params['label'] ) ) && ( 'right' == $item_params['label']['position'] ) ) {
		wpbc_flex_label( array( 'id' => $item_params['id'], 'label' => $item_params['label']['title'] ) );
	}
}


/**
 * Show FLEX toggle    instead of checkbox
 *
 * @param array $item
 *
 *  Example:
				$params_checkbox = array(
										  'id'       => 'my_check_id' 		// HTML ID  of element
										, 'name'     => 'my_check_id'
										, 'label'    => array( 'title' => __('Approve' ,'booking') , 'position' => 'right' )
										, 'style'    => '' 					// CSS of select element
										, 'toggle_style' => ''              // Styles CSS of toggle container
										, 'toggle_class' => ''              // Class  CSS of toggle container
										, 'class' => ''                     // CSS Class of input element
										, 'disabled' => false
										, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
										, 'legend'   => ''					// aria-label parameter
										, 'value'    => 'CHECK_VAL_1' 		// Some Value from optins array that selected by default
										, 'selected' => !false 				// Selected or not
										, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
										, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
									);
				?><div class="ui_element"><?php

					wpbc_flex_toggle( $params_select );

				?></div><?php
 */
function wpbc_flex_toggle( $item ){

	$default_item_params = array(
							  'type' => 'checkbox'
							, 'id' => ''                        // HTML ID  of element
							, 'name' => ''
							, 'label' => ''      				// Label	Example: 'label' => array( 'title' => __('Select status' ,'booking') , 'position' => 'left' )
							, 'style' => ''                     // CSS of select element
							, 'toggle_style' => ''              // Styles CSS of toggle container
							, 'toggle_class' => ''              // Class  CSS of toggle container
							, 'class' => ''                     // CSS Class of input element
							, 'disabled' => false
							, 'attr' => array()                 // Any  additional attributes, if this radio | checkbox element
							, 'legend' => ''                    // aria-label parameter
							, 'value' => ''                     // Some Value from optins array that selected by default
							, 'selected' => false               // Selected or not
							, 'onfocus' => ''					// JavaScript code
							, 'onchange' => ''					// JavaScript code
							, 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
				  );
    $item_params = wp_parse_args( $item, $default_item_params );
	/**
	 * Structure:
					?><span class="wpbc_ui__toggle">
						<input type="checkbox" name="field_name"  id="wpbc_field_id" value="On" />
						<label class="wpbc_ui__toggle_icon"  	 for="wpbc_field_id"></label>
						<label class="wpbc_ui__toggle_label" 	 for="wpbc_field_id">Some Text</label>
						<i class="wpbc_help_tooltip"></i>
					</span><?php
	*/

	?><span class="wpbc_ui__toggle <?php echo esc_attr( $item_params['toggle_class'] ); ?>" style="<?php echo esc_attr( $item_params['toggle_style'] ); ?>"><?php

		?><input    type="<?php 		 echo esc_attr( $item_params['type'] ); ?>"
					id="<?php 			 echo esc_attr( $item_params['id'] ); ?>"
					name="<?php 		 echo esc_attr( $item_params['name'] ); ?>"
					value="<?php 		 echo esc_attr( $item_params['value'] ); ?>"
					aria-label="<?php 	 echo esc_attr( $item_params['legend'] ); ?>"
					class="wpbc_ui_<?php echo esc_attr( $item_params['type'] ); ?> <?php echo esc_attr( $item_params['class'] ); ?>"
					<?php echo wpbc_get_custom_attr( $item_params ); ?>
					<?php checked(  $item_params['selected'], true ); ?>
					<?php disabled( $item_params['disabled'], true ); ?>
					<?php
						if ( ! empty( $item_params['onfocus'] ) ) {
							?> onfocus="javascript:<?php echo wpbc_esc_js( $item_params['onfocus'] ); ?>" <?php
						}
						if ( ! empty( $item_params['onchange'] ) ) {
							?> onchange="javascript:<?php echo wpbc_esc_js( $item_params['onchange'] ); ?>" <?php
						}
					?>
					autocomplete="off"
			/><?php

			?><label class="wpbc_ui__toggle_icon
					 		<?php echo ( ! empty( $item_params['hint'] ) ) ? ' tooltip_' . esc_attr( $item_params['hint']['position'] ) . ' ' : '' ; ?>
						   "
					 for="<?php echo esc_attr( $item_params['id'] ); ?>"
					 <?php
						if ( ! empty( $item_params['hint'] ) ) { ?>
					 	    title="<?php echo esc_attr( $item_params['hint']['title'] ); ?>" <?php
						}
					 ?>
				></label><?php

			if ( ! empty( $item_params['label'] ) ) {
				wpbc_flex_label( array( 'id' => $item_params['id'], 'class' => 'wpbc_ui__toggle_label', 'label' => $item_params['label']['title'] ) );
			}

			?><i class="wpbc_help_tooltip"></i><?php

	?></span><?php

}


/**
	 * Show FLEX radio button
 *
 * @param array $item
 *
 *  Example:
				$params_checkbox = array(
										  'id'       => 'my_check_id' 		// HTML ID  of element
										, 'name'     => 'my_check_id'
										, 'label'    => array( 'title' => __('Approve' ,'booking') , 'position' => 'right' )
										, 'style'    => '' 					// CSS of select element
										, 'class'    => '' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
										, 'legend'   => ''					// aria-label parameter
										, 'value'    => 'CHECK_VAL_1' 		// Some Value from optins array that selected by default
										, 'selected' => !false 				// Selected or not
										, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
										, 'onchange' => "console.log( 'ON CHANGE:', jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
									);
				?><div class="ui_element"><?php

				wpbc_flex_radio( $params_select );

				$params_checkbox['id'] = 'my_check_id2';
				$params_checkbox['value'] = 'CHECK_VAL_2';

				wpbc_flex_radio( $params_select );

				?></div><?php
 */
function wpbc_flex_radio( $item ) {
    $item['type'] = 'radio';
	$item['is_use_toggle'] = false;
    wpbc_flex_checkbox( $item );
}

/**
 * Show FLEX addon (image or text  can  be here)
 *
 * @param array $item
                        array(
                            'type' => 'span'          // HTML tag that  will  bound content
                          , 'html' => ''			  // Any  other HTML content
                    	  , 'icon' => false			  // array( 'icon_font' => 'wpbc_icn_check_circle_outline', 'position' => 'right', 'icon_img' => '' )
                          , 'style' => ''             // CSS of select element
                          , 'class' => ''             // CSS Class of select element		// default included class  is .wpbc_ui_addon
                          , 'attr' => array()         // Any  additional attributes
                        );
 * Example 1:
`				$params_span = array(
									  'type'        => 'span'
									, 'html'        => '<i class="menu_icon icon-1x wpbc_icn_event"></i> &nbsp; Approve '
                     				, 'icon'        => false		// array( 'icon_font' => 'wpbc_icn_check_circle_outline', 'position' => 'right', 'icon_img' => '' )
									, 'class'       => 'wpbc_ui_button inactive ui_nowrap'
									, 'style'       => ''
									, 'attr'        => array()
								);

				?><div class="ui_element"><?php

					wpbc_flex_addon( $params_span );

					wpbc_flex_text( $params_text );

					wpbc_flex_addon( $params_span );

				?></div><?php
 * Example 2:
 		$params_addon = array(
							  'type'        => 'span'
							, 'html'        => ''// '<i class="menu_icon icon-1x wpbc_icn_event"></i>' //'<strong>' . __( 'Dates', 'booking ' ) . '</strong>'
							, 'icon'        =>  array( 'icon_font' => 'wpbc_icn_event', 'position' => 'right', 'icon_img' => '' )
							, 'class'       => 'wpbc_ui_button inactive'
							, 'style'       => ''
							, 'attr'        => array()
						);
		?><div class="ui_element ui_nowrap"><?php
			wpbc_flex_addon( $params_addon );
		?></div><?php

 */
function wpbc_flex_addon( $item ) {

    $default_item_params = array(
                                  'type'        => 'span'
                                , 'html'        => ''
								, 'icon'        => false
                                , 'class'       => ''
                                , 'style'       => ''
								, 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
                                , 'attr'        => array()
    );
    $item_params = wp_parse_args( $item, $default_item_params );

	// Icon
	$btn_icon = '';
	if ( ( ! empty( $item_params['icon'] ) ) && ( is_array( $item_params['icon'] ) ) ) {

		// Icon IMG
		if ( ! empty( $item_params['icon']['icon_img'] ) ) {

			if ( substr( $item_params['icon']['icon_img'], 0, 4 ) != 'http' ) {
				$img_path = WPBC_PLUGIN_URL . '/assets/img/' . $item_params['icon']['icon_img'];
			} else {
				$img_path = $item_params['icon']['icon_img'];
			}
			$btn_icon = '<img class="menuicons" src="' . esc_url( $img_path ) . '" />';    // Img  Icon
		}

		// Icon Font
		if ( ! empty( $item_params['icon']['icon_font'] ) ) {
			$btn_icon = '<i class="menu_icon icon-1x ' . esc_attr( $item_params['icon']['icon_font'] ) . '"></i>';                         // Font Icon
		}
	}

	?><<?php echo esc_attr( $item_params['type'] ); ?>
			class="wpbc_ui_control wpbc_ui_addon <?php echo esc_attr( $item_params['class'] );
													   echo ( ! empty( $item_params['hint'] ) ) ? ' tooltip_' . esc_attr( $item_params['hint']['position'] ) . ' ' : '' ; ?>"
			style="<?php echo esc_attr( $item_params['style'] ); ?>"
			<?php echo wpbc_get_custom_attr( $item_params ); ?>
		  <?php if ( ! empty( $item_params['hint'] ) ) { ?>
			  title="<?php  echo esc_attr( $item_params['hint']['title'] ); ?>"
		  <?php } ?>

	><?php

		if ( ( ! empty( $btn_icon ) ) && ( 'left' == $item_params['icon']['position'] ) ) {
				echo $btn_icon;
		}

		echo html_entity_decode(
									  wp_kses_post( $item_params['html'] )		// Sanitizes content for allowed HTML tags for post content
									, ENT_QUOTES								// Convert &quot;  to " and '
									, get_bloginfo( 'charset' ) 				// 'UTF-8'  or other
								);												// Convert &amp;dash;  to &dash;  etc...


		if ( ( ! empty( $btn_icon ) ) && ( 'right' == $item_params['icon']['position'] ) ) {
				echo $btn_icon;
		}

	?></<?php echo esc_attr( $item_params['type'] ); ?>><?php
}

function wpbc_flex_divider( $item = array() ){

    $default_item_params = array(
                                  'type'        => 'span'
                                , 'html'        => ''
								, 'icon'        => false
                                , 'class'       => ''
                                , 'style'       => ''
								, 'hint' => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
                                , 'attr'        => array()
    );
    $item_params = wp_parse_args( $item, $default_item_params );

	?><div class="wpbc_ui_control ui_elements_divider <?php echo esc_attr( $item_params['class'] );
													   echo ( ! empty( $item_params['hint'] ) ) ? ' tooltip_' . esc_attr( $item_params['hint']['position'] ) . ' ' : '' ; ?>"
			style="<?php echo esc_attr( $item_params['style'] ); ?>"
			<?php echo wpbc_get_custom_attr( $item_params ); ?>
		  <?php if ( ! empty( $item_params['hint'] ) ) { ?>
			  title="<?php  echo esc_attr( $item_params['hint']['title'] ); ?>"
		  <?php } ?>
	  ></div><?php
}

/**
 * Show FLEX Vertical line for "wpbc_ui_control" elements 	with vertical  border color
 *
 * @param $item array
 *
 * @return void
 *
 *             Example:
 *             ?><div class="ui_element ui_nowrap"><?php
						wpbc_flex_vertical_color( array(
															'vertical_line' => 'border-left: 4px solid #11be4c;'
												) );
 *             ?></div><?php
 */
function wpbc_flex_vertical_color( $item = array() ){

    $default_item_params = array(
								  'id' 			=> ''
                                , 'html'        => '<span></span>'
								, 'icon'        => false
                                , 'class'       => ''
                                , 'style'       => ''
								, 'hint' 		=> ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
                                , 'attr'        => array()
								, 'vertical_line' => 'border-left: 4px solid #11be4c;'
    );
    $item_params = wp_parse_args( $item, $default_item_params );

	?><div
	            id="<?php 	echo esc_attr( $item_params['id'] ); ?>"
				class="wpbc_ui_control wpbc_ui_button ui_elements_vertical_color <?php echo esc_attr( $item_params['class'] );
													   echo ( ! empty( $item_params['hint'] ) ) ? ' tooltip_' . esc_attr( $item_params['hint']['position'] ) . ' ' : '' ; ?>"
				style="<?php echo esc_attr( $item_params['vertical_line'] ) . ';'; ?>padding: 0;<?php echo esc_attr( $item_params['style'] ); ?>"
			<?php echo wpbc_get_custom_attr( $item_params ); ?>
			<?php if ( ! empty( $item_params['hint'] ) ) { ?>
			  title="<?php  echo esc_attr( $item_params['hint']['title'] ); ?>"
			<?php } ?>
	  ><?php  echo $item_params['html']; ?></div><?php

}




/**
 * Show FLEX Horizontal Text Bar line for "wpbc_ui_control" elements 	with vertical  border color
 *
 * @param $item array
 *
 * @return void
 *
 *             Example:
 *
						$params_text_bar_1 = array(	  'id' 			 => ''
													, 'html'         => '1. ' . __( 'Calendar Skin', 'booking' )
													, 'option_class' => 'wpbc_option_step wpbc_passed_step'	// 'wpbc_option_step', 'wpbc_option_separator', 'wpbc_option_step wpbc_selected_step', 'wpbc_option_separator wpbc_passed_step'
													, 'class'        => ''
													, 'style'        => 'height:auto;'
													, 'hint' 		 => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
													, 'attr'         => array()
													, 'tag' 		 => 'span'
											);
						$params_text_bar_hr = array('id' 			 => ''
													, 'html'         => '&gt;'
													, 'option_class' => 'wpbc_option_separator'	// 'wpbc_option_step', 'wpbc_option_separator', 'wpbc_option_step wpbc_selected_step', 'wpbc_option_separator wpbc_passed_step'
													, 'class'        => ''
													, 'style'        => 'height:auto;'
													, 'hint' 		 => ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
													, 'attr'         => array()
													, 'tag' 		 => 'span'
											);

						$params_text_bar_2                 = $params_text_bar_1;
						$params_text_bar_2['html']         = '2. ' . __( 'Calendar size', 'booking' );
						$params_text_bar_2['option_class'] = 'wpbc_option_step wpbc_selected_step';

						$params_text_bar_3                 = $params_text_bar_1;
						$params_text_bar_3['html']         = '3. ' . __( 'Dates selection', 'booking' );
						$params_text_bar_3['option_class'] = 'wpbc_option_step';

 *             ?><div class="ui_element ui_nowrap"><?php
 *
					wpbc_flex_horizontal_text_bar( $params_text_bar_1 );

					wpbc_flex_horizontal_text_bar( $params_text_bar_hr );

					wpbc_flex_horizontal_text_bar( $params_text_bar_2 );

 *             ?></div><?php
 */
function wpbc_flex_horizontal_text_bar( $item = array() ){

    $default_item_params = array(
								  'id' 			=> ''
                                , 'html'        => '<span></span>'
                                , 'class'       => ''
                                , 'style'       => ''
								, 'hint' 		=> ''                      // , 'hint' => array( 'title' => __('Select status' ,'booking') , 'position' => 'bottom' )
                                , 'attr'        => array()
								, 'tag' => 'span'
								, 'option_class'       => 'wpbc_option_step'	// 'wpbc_option_step', 'wpbc_option_separator', 'wpbc_option_step wpbc_selected_step', 'wpbc_option_separator wpbc_passed_step'
    );
    $item_params = wp_parse_args( $item, $default_item_params );

	?><<?php echo esc_attr( $item_params['tag'] );  ?>
				<?php if ( ! empty( $item_params['id'] ) ) { echo ' id="' . esc_attr( $item_params['id'] ) . '" '; } ?>
				class="wpbc_ui_control wpbc_ui_addon wpbc_text_bar <?php echo esc_attr( $item_params['class'] );
													   echo ( ! empty( $item_params['hint'] ) ) ? ' tooltip_' . esc_attr( $item_params['hint']['position'] ) . ' ' : '' ; ?>"
				style="<?php echo esc_attr( $item_params['style'] ); ?>"
			<?php echo wpbc_get_custom_attr( $item_params ); ?>
			<?php if ( ! empty( $item_params['hint'] ) ) { ?>
			  title="<?php  echo esc_attr( $item_params['hint']['title'] ); ?>"
			<?php } ?>
	><span class="<?php echo esc_attr( $item_params['option_class'] ); ?>"><?php
			echo $item_params['html'];
	?></span></<?php echo esc_attr( $item_params['tag'] );  ?>><?php

}



////////////////////////////////////////////////////////////////////////////////
//  JS & CSS Loading
////////////////////////////////////////////////////////////////////////////////

/**
 * CSS files loading
 *
 * @param string $where_to_load
 */
function wpbc_ajx_toolbar_enqueue_css_files( $where_to_load ) {

	if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

		wp_enqueue_style( 'wpbc-flex-toolbar', wpbc_plugin_url( '/includes/_toolbar_ui/_src/toolbar_ui.css' ), array(), WP_BK_VERSION_NUM );
	}
}
add_action( 'wpbc_enqueue_css_files', 'wpbc_ajx_toolbar_enqueue_css_files', 50 );


/**
 * JS files loading
 *
 * @param string $where_to_load
 */
function wpbc_ajx_toolbar_enqueue_js_files( $where_to_load ) {

	$in_footer = true;

	if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

		wp_enqueue_script( 'wpbc-flex-toolbar-ui', wpbc_plugin_url( '/includes/_toolbar_ui/_out/toolbar_ui.js' ), array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

		/**
		 *  wp_localize_script( 'wpbc-global-vars', 'wpbc_live_request_obj'
								, array(
										'contacts'  => '',
										'reminders' => ''
									)
			);
		*/
	}
}
add_action( 'wpbc_enqueue_js_files', 'wpbc_ajx_toolbar_enqueue_js_files', 50 );