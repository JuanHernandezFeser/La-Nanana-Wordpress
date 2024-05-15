"use strict";

/**
 * Request Object
 * Here we can  define Search parameters and Update it later,  when  some parameter was changed
 *
 */

var wpbc_ajx_customize_plugin = (function ( obj, $) {

	// Secure parameters for Ajax	------------------------------------------------------------------------------------
	var p_secure = obj.security_obj = obj.security_obj || {
															user_id: 0,
															nonce  : '',
															locale : ''
														  };

	obj.set_secure_param = function ( param_key, param_val ) {
		p_secure[ param_key ] = param_val;
	};

	obj.get_secure_param = function ( param_key ) {
		return p_secure[ param_key ];
	};


	// Listing Search parameters	------------------------------------------------------------------------------------
	var p_listing = obj.search_request_obj = obj.search_request_obj || {
																		// sort            : "booking_id",
																		// sort_type       : "DESC",
																		// page_num        : 1,
																		// page_items_count: 10,
																		// create_date     : "",
																		// keyword         : "",
																		// source          : ""
																	};

	obj.search_set_all_params = function ( request_param_obj ) {
		p_listing = request_param_obj;
	};

	obj.search_get_all_params = function () {
		return p_listing;
	};

	obj.search_get_param = function ( param_key ) {
		return p_listing[ param_key ];
	};

	obj.search_set_param = function ( param_key, param_val ) {
		// if ( Array.isArray( param_val ) ){
		// 	param_val = JSON.stringify( param_val );
		// }
		p_listing[ param_key ] = param_val;
	};

	obj.search_set_params_arr = function( params_arr ){
		_.each( params_arr, function ( p_val, p_key, p_data ){															// Define different Search  parameters for request
			this.search_set_param( p_key, p_val );
		} );
	}


	// Other parameters 			------------------------------------------------------------------------------------
	var p_other = obj.other_obj = obj.other_obj || { };

	obj.set_other_param = function ( param_key, param_val ) {
		p_other[ param_key ] = param_val;
	};

	obj.get_other_param = function ( param_key ) {
		return p_other[ param_key ];
	};


	return obj;
}( wpbc_ajx_customize_plugin || {}, jQuery ));

var wpbc_ajx_bookings = [];

/**
 *   Show Content  ---------------------------------------------------------------------------------------------- */

/**
 * Show Content - Calendar and UI elements
 *
 * @param ajx_data
 * @param ajx_search_params
 * @param ajx_cleaned_params
 */
function wpbc_ajx_customize_plugin__page_content__show( ajx_data, ajx_search_params , ajx_cleaned_params ){

	// Content ---------------------------------------------------------------------------------------------------------
	var template__customize_plugin_main_page_content = wp.template( 'wpbc_ajx_customize_plugin_main_page_content' );
	jQuery( wpbc_ajx_customize_plugin.get_other_param( 'listing_container' ) ).html( template__customize_plugin_main_page_content( {
																'ajx_data'              : ajx_data,
																'ajx_search_params'     : ajx_search_params,								// $_REQUEST[ 'search_params' ]
																'ajx_cleaned_params'    : ajx_cleaned_params
									} ) );

	var template__inline_calendar;
	var data_arr = {
							'ajx_data'              : ajx_data,
							'ajx_search_params'     : ajx_search_params,
							'ajx_cleaned_params'    : ajx_cleaned_params
						};

	switch ( ajx_data['customize_steps']['current'] ){

		case 'calendar_skin':

			// Calendar  --------------------------------------------------------------------------------------------
			template__inline_calendar = wp.template( 'wpbc_ajx_customize_plugin__inline_calendar' );
			jQuery('.wpbc_ajx_cstm__section_left').html(	template__inline_calendar( data_arr ) );

			// Calendar Skin
			var template__wiget_calendar_skin = wp.template( 'wpbc_ajx_widget_change_calendar_skin' );
			jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__wiget_calendar_skin( data_arr ) );

			// Shortcode
			// var template__widget_plugin_shortcode = wp.template( 'wpbc_ajx_widget_plugin_shortcode' );
			// jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__widget_plugin_shortcode( data_arr ) );

			// Size
			// var template__wiget_calendar_size = wp.template( 'wpbc_ajx_widget_calendar_size' );
			// jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__wiget_calendar_size( data_arr ) );

			break;

		case 'calendar_size':

			// Calendar  --------------------------------------------------------------------------------------------
			template__inline_calendar = wp.template( 'wpbc_ajx_customize_plugin__inline_calendar' );
			jQuery('.wpbc_ajx_cstm__section_left').html(	template__inline_calendar( data_arr ) );

			// Calendar Skin
			var template__wiget_calendar_size = wp.template( 'wpbc_ajx_widget_calendar_size' );
			jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__wiget_calendar_size( data_arr ) );

			// Shortcode
			// var template__widget_plugin_shortcode = wp.template( 'wpbc_ajx_widget_plugin_shortcode' );
			// jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__widget_plugin_shortcode( data_arr ) );

			break;

		case 'calendar_dates_selection':

			// Calendar  --------------------------------------------------------------------------------------------
			template__inline_calendar = wp.template( 'wpbc_ajx_customize_plugin__inline_calendar' );
			jQuery('.wpbc_ajx_cstm__section_left').html(	template__inline_calendar( data_arr ) );

			jQuery('.wpbc_ajx_cstm__section_left').append('<div class="clear" style="width:100%;margin:50px 0 0;"></div>');

			var message_html_id = wpbc_ajx_customize_plugin__show_message(
													'<strong>' +	'You can test days selection in calendar' + '</strong>'
													, {
															'container': '.wpbc_ajx_cstm__section_left',		// '#ajax_working',
															'style'    : 'margin: 6px auto;  padding: 6px 20px;z-index: 999999;',
															'type'     : 'info',
															'delay'    : 5000
														}
													);
			wpbc_blink_element( '#' + message_html_id, 3, 320 );

			// Widget - Dates selection
			 var template__widget_plugin_calendar_dates_selection = wp.template( 'wpbc_ajx_widget_calendar_dates_selection' );
			 jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__widget_plugin_calendar_dates_selection( data_arr ) );

			break;

		case 'calendar_weekdays_availability':

			// Scroll  to  current month
			var s_year = wpbc_ajx_customize_plugin.search_set_param( 'calendar__start_year', 0 );
			var s_month = wpbc_ajx_customize_plugin.search_set_param( 'calendar__start_month', 0 );

			// Calendar  --------------------------------------------------------------------------------------------
			template__inline_calendar = wp.template( 'wpbc_ajx_customize_plugin__inline_calendar' );
			jQuery('.wpbc_ajx_cstm__section_left').html(	template__inline_calendar( data_arr ) );

			// Widget - Weekdays Availability
			 var template__widget_plugin_calendar_weekdays_availability = wp.template( 'wpbc_ajx_widget_calendar_weekdays_availability' );
			 jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__widget_plugin_calendar_weekdays_availability( data_arr ) );

			break;

		case 'calendar_additional':

			// Calendar  --------------------------------------------------------------------------------------------
			template__inline_calendar = wp.template( 'wpbc_ajx_customize_plugin__inline_calendar' );
			jQuery('.wpbc_ajx_cstm__section_left').html(	template__inline_calendar( data_arr ) );

			// Calendar Skin
			var template__wiget_calendar_additional = wp.template( 'wpbc_ajx_widget_calendar_additional' );
			jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__wiget_calendar_additional( data_arr ) );

			// Shortcode
			// var template__widget_plugin_shortcode = wp.template( 'wpbc_ajx_widget_plugin_shortcode' );
			// jQuery('.wpbc_ajx_cstm__section_right .wpbc_widgets').append(	template__widget_plugin_shortcode( data_arr ) );

			break;

		default:
			//console.log( `Sorry, we are out of ${expr}.` );
	}

	// Toolbar ---------------------------------------------------------------------------------------------------------
	var template__customize_plugin_toolbar_page_content = wp.template( 'wpbc_ajx_customize_plugin_toolbar_page_content' );
	jQuery( wpbc_ajx_customize_plugin.get_other_param( 'toolbar_container' ) ).html( template__customize_plugin_toolbar_page_content( {
																'ajx_data'              : ajx_data,
																'ajx_search_params'     : ajx_search_params,								// $_REQUEST[ 'search_params' ]
																'ajx_cleaned_params'    : ajx_cleaned_params
									} ) );


		// Booking resources  ------------------------------------------------------------------------------------------
		var wpbc_ajx_select_booking_resource = wp.template( 'wpbc_ajx_select_booking_resource' );
		jQuery( '#wpbc_hidden_template__select_booking_resource').html( wpbc_ajx_select_booking_resource( {
																	'ajx_data'              : ajx_data,
																	'ajx_search_params'     : ajx_search_params,
																	'ajx_cleaned_params'    : ajx_cleaned_params
										} 	) );
		/*
		 * By  default hided at ../wp-content/plugins/booking/includes/page-customize/_src/customize_plugin_page.css  #wpbc_hidden_template__select_booking_resource { display: none; }
		 *
		 * 	We can hide  ///-	Hide resources!
		 * 				 //setTimeout( function (){ jQuery( '#wpbc_hidden_template__select_booking_resource' ).html( '' ); }, 1000 );
		 */




	// Other  ---------------------------------------------------------------------------------------------------------
	jQuery( '.wpbc_processing.wpbc_spin').parent().parent().parent().parent( '[id^="wpbc_notice_"]' ).hide();


	// Load calendar ---------------------------------------------------------------------------------------------------------
	wpbc_ajx_customize_plugin__calendar__show( {
											'resource_id'       : ajx_cleaned_params.resource_id,
											'ajx_nonce_calendar': ajx_data.ajx_nonce_calendar,
											'ajx_data_arr'          : ajx_data,
											'ajx_cleaned_params'    : ajx_cleaned_params
										} );

	//------------------------------------------------------------------------------------------------------------------
	/**
	 * Change calendar skin view
	 */
	jQuery( '.wpbc_radio__set_days_customize_plugin' ).on('change', function ( event, resource_id, inst ){
		wpbc__calendar__change_skin( jQuery( this ).val() );
	});


	// Re-load Tooltips
	jQuery( document ).ready( function (){
		wpbc_define_tippy_tooltips( wpbc_ajx_customize_plugin.get_other_param( 'listing_container' ) + ' ' );
		wpbc_define_tippy_tooltips( wpbc_ajx_customize_plugin.get_other_param( 'toolbar_container' ) + ' ' );
	});
}


/**
 * Show inline month view calendar              with all predefined CSS (sizes and check in/out,  times containers)
 * @param {obj} calendar_params_arr
			{
				'resource_id'       	: ajx_cleaned_params.resource_id,
				'ajx_nonce_calendar'	: ajx_data_arr.ajx_nonce_calendar,
				'ajx_data_arr'          : ajx_data_arr = { ajx_booking_resources:[],  resource_unavailable_dates:[], season_customize_plugin:{},.... }
				'ajx_cleaned_params'    : {
											calendar__days_selection_mode: "dynamic"
											calendar__timeslot_day_bg_as_available: ""
											calendar__view__cell_height: ""
											calendar__view__months_in_row: 4
											calendar__view__visible_months: 12
											calendar__view__width: "100%"

											dates_customize_plugin: "unavailable"
											dates_selection: "2023-03-14 ~ 2023-03-16"
											do_action: "set_customize_plugin"
											resource_id: 1
											ui_clicked_element_id: "wpbc_customize_plugin_apply_btn"
											ui_usr__customize_plugin_selected_toolbar: "info"
								  		 }
			}
*/
function wpbc_ajx_customize_plugin__calendar__show( calendar_params_arr ){

	// Update nonce
	jQuery( '#ajx_nonce_calendar_section' ).html( calendar_params_arr.ajx_nonce_calendar );


	//------------------------------------------------------------------------------------------------------------------
	// Update bookings
	//------------------------------------------------------------------------------------------------------------------
	if ( 'undefined' == typeof (wpbc_ajx_bookings[ calendar_params_arr.resource_id ]) ){ wpbc_ajx_bookings[ calendar_params_arr.resource_id ] = []; }
	wpbc_ajx_bookings[ calendar_params_arr.resource_id ] = calendar_params_arr[ 'ajx_data_arr' ][ 'calendar_settings' ][ 'booked_dates' ];


	//------------------------------------------------------------------------------------------------------------------
 	// Get scrolling month  or year  in calendar  and save it to  the init parameters
	//------------------------------------------------------------------------------------------------------------------
	jQuery( 'body' ).off( 'wpbc__inline_booking_calendar__changed_year_month' );
	jQuery( 'body' ).on( 'wpbc__inline_booking_calendar__changed_year_month', function ( event, year, month, calendar_params_arr, datepick_this ){

		wpbc_ajx_customize_plugin.search_set_param( 'calendar__start_year', year );
		wpbc_ajx_customize_plugin.search_set_param( 'calendar__start_month', month );
	} );

	//------------------------------------------------------------------------------------------------------------------
	// Define showing mouse over tooltip on unavailable dates
	//------------------------------------------------------------------------------------------------------------------
	jQuery( 'body' ).on( 'wpbc_datepick_inline_calendar_refresh', function ( event, resource_id, inst ){

		/**
		 * It's defined, when calendar REFRESHED (change months or days selection) loaded in jquery.datepick.wpbc.9.0.js :
		 * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_refresh', ...		//FixIn: 9.4.4.13
		 */

		// inst.dpDiv  it's:  <div class="datepick-inline datepick-multi" style="width: 17712px;">....</div>

		inst.dpDiv.find( '.season_unavailable,.before_after_unavailable,.weekdays_unavailable' ).on( 'mouseover', function ( this_event ){
			// also available these vars: 	resource_id, jCalContainer, inst
			var jCell = jQuery( this_event.currentTarget );
			wpbc_cstm__show_tooltip__for_element( jCell, calendar_params_arr[ 'ajx_data_arr' ]['popover_hints'] );
		});

	});


	//------------------------------------------------------------------------------------------------------------------
	//  Define height of the calendar  cells, 	and  mouse over tooltips at  some unavailable dates
	//------------------------------------------------------------------------------------------------------------------
	jQuery( 'body' ).on( 'wpbc_datepick_inline_calendar_loaded', function ( event, resource_id, jCalContainer, inst ){

		/**
		 * It's defined, when calendar loaded in jquery.datepick.wpbc.9.0.js :
		 * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_loaded', ...		//FixIn: 9.4.4.12
		 */

		// Remove highlight day for today  date
		jQuery( '.datepick-days-cell.datepick-today.datepick-days-cell-over' ).removeClass( 'datepick-days-cell-over' );

		// Set height of calendar  cells if defined this option
		var stylesheet = document.getElementById( 'wpbc-calendar-cell-height' );
		if ( null !== stylesheet ){
			stylesheet.parentNode.removeChild( stylesheet );
		}
		if ( '' !== calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height ){
			jQuery( 'head' ).append( '<style type="text/css" id="wpbc-calendar-cell-height">'
										+ '.hasDatepick .datepick-inline .datepick-title-row th, '
										+ '.hasDatepick .datepick-inline .datepick-days-cell {'
											+ 'height: ' + calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height + ' !important;'
										+ '}'
									+'</style>' );
		}

		// Define showing mouse over tooltip on unavailable dates
		jCalContainer.find( '.season_unavailable,.before_after_unavailable,.weekdays_unavailable' ).on( 'mouseover', function ( this_event ){
			// also available these vars: 	resource_id, jCalContainer, inst
			var jCell = jQuery( this_event.currentTarget );
			wpbc_cstm__show_tooltip__for_element( jCell, calendar_params_arr[ 'ajx_data_arr' ]['popover_hints'] );
		});
	} );


	//------------------------------------------------------------------------------------------------------------------
	// Define months_in_row
	//------------------------------------------------------------------------------------------------------------------
	if (   ( undefined == calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row )
		      || ( '' == calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row )
	){
		calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row = calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months;
	}
	
	//------------------------------------------------------------------------------------------------------------------
	// Define width of entire calendar
	//------------------------------------------------------------------------------------------------------------------
	var width =   '';					// var width = 'width:100%;max-width:100%;';
	// Width																											/* FixIn: 9.7.3.4 */
	if (   ( undefined != calendar_params_arr.ajx_cleaned_params.calendar__view__width )
		      && ( '' !== calendar_params_arr.ajx_cleaned_params.calendar__view__width )
	){
		width += 'max-width:' 	+ calendar_params_arr.ajx_cleaned_params.calendar__view__width + ';';
		width += 'width:100%;';
	}


	//------------------------------------------------------------------------------------------------------------------
	// Add calendar container: "Calendar is loading..."  and textarea
	//------------------------------------------------------------------------------------------------------------------
	jQuery( '.wpbc_ajx_cstm__calendar' ).html(

		'<div class="'	+ ' bk_calendar_frame'
						+ ' months_num_in_row_' + calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row
						+ ' cal_month_num_' 	+ calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months
						+ ' ' 					+ calendar_params_arr.ajx_cleaned_params.calendar__timeslot_day_bg_as_available 				// 'wpbc_timeslot_day_bg_as_available' || ''
				+ '" '
			+ 'style="' + width + '">'

				+ '<div id="calendar_booking' + calendar_params_arr.resource_id + '">' + 'Calendar is loading...' + '</div>'

		+ '</div>'

		+ '<textarea      id="date_booking' + calendar_params_arr.resource_id + '"'
					+ ' name="date_booking' + calendar_params_arr.resource_id + '"'
					+ ' autocomplete="off"'
					+ ' style="display:none;width:100%;height:10em;margin:2em 0 0;"></textarea>'
	);


	//------------------------------------------------------------------------------------------------------------------
	// Define variables for calendar
	//------------------------------------------------------------------------------------------------------------------
	var cal_param_arr =  calendar_params_arr.ajx_data_arr.calendar_settings;
	cal_param_arr[ 'html_id' ] 						= 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id;
	cal_param_arr[ 'text_id' ] 						= 'date_booking' 	 + calendar_params_arr.ajx_cleaned_params.resource_id;
	cal_param_arr[ 'resource_id' ] 					= calendar_params_arr.ajx_cleaned_params.resource_id;
	cal_param_arr[ 'ajx_nonce_calendar' ] 			= calendar_params_arr.ajx_data_arr.ajx_nonce_calendar;
	cal_param_arr[ 'season_customize_plugin' ] 		= calendar_params_arr.ajx_data_arr.season_customize_plugin;
	cal_param_arr[ 'resource_unavailable_dates' ] 	= calendar_params_arr.ajx_data_arr.resource_unavailable_dates;
	cal_param_arr[ 'popover_hints' ] 				= calendar_params_arr.ajx_data_arr.popover_hints;					// {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}


	//------------------------------------------------------------------------------------------------------------------
	// Show Calendar
	//------------------------------------------------------------------------------------------------------------------
	wpbc_show_inline_booking_calendar( cal_param_arr );


	//------------------------------------------------------------------------------------------------------------------
	// Scroll  to  specific Year and Month,  if defined in init parameters
	//------------------------------------------------------------------------------------------------------------------
	var s_year  = wpbc_ajx_customize_plugin.search_get_param( 'calendar__start_year' );
	var s_month = wpbc_ajx_customize_plugin.search_get_param( 'calendar__start_month' );
	if ( ( 0 !== s_year ) && ( 0 !== s_month ) ){
		 wpbc__inline_booking_calendar__change_year_month( cal_param_arr[ 'resource_id' ], s_year, s_month )
	}
}

/**
 * Show calendar in  different Skin
 *
 * @param selected_skin_url
 */
function wpbc__calendar__change_skin( selected_skin_url ){

//console.log( 'SKIN SELECTION ::', selected_skin_url );

	// Remove CSS skin
	var stylesheet = document.getElementById( 'wpbc-calendar-skin-css' );
	stylesheet.parentNode.removeChild( stylesheet );


	// Add new CSS skin
	var headID = document.getElementsByTagName( "head" )[ 0 ];
	var cssNode = document.createElement( 'link' );
	cssNode.type = 'text/css';
	cssNode.setAttribute( "id", "wpbc-calendar-skin-css" );
	cssNode.rel = 'stylesheet';
	cssNode.media = 'screen';
	cssNode.href = selected_skin_url;	//"http://beta/wp-content/plugins/booking/css/skins/green-01.css";
	headID.appendChild( cssNode );
}


	/**
	 *   Tooltips  ---------------------------------------------------------------------------------------------- */

	/**
	 * Define showing tooltip,  when  mouse over on  SELECTABLE (available, pending, approved, resource unavailable),  days
	 * Can be called directly  from  datepick init function.
	 *
	 * @param value
	 * @param date
	 * @param calendar_params_arr
	 * @param datepick_this
	 * @returns {boolean}
	 */
	function wpbc_cstm__prepare_tooltip__in_calendar( value, date, calendar_params_arr, datepick_this ){

		if ( null == date ){  return false;  }

		var td_class = ( date.getMonth() + 1 ) + '-' + date.getDate() + '-' + date.getFullYear();

		var jCell = jQuery( '#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class );

		wpbc_cstm__show_tooltip__for_element( jCell, calendar_params_arr[ 'popover_hints' ] );
		return true;
	}


	/**
	 * Define tooltip  for showing on UNAVAILABLE days (season, weekday, today_depends unavailable)
	 *
	 * @param jCell					jQuery of specific day cell
	 * @param popover_hints		    Array with tooltip hint texts	 : {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}
	 */
	function wpbc_cstm__show_tooltip__for_element( jCell, popover_hints ){

		var tooltip_time = '';

		if ( jCell.hasClass( 'season_unavailable' ) ){
			tooltip_time = popover_hints[ 'season_unavailable' ];
		} else if ( jCell.hasClass( 'weekdays_unavailable' ) ){
			tooltip_time = popover_hints[ 'weekdays_unavailable' ];
		} else if ( jCell.hasClass( 'before_after_unavailable' ) ){
			tooltip_time = popover_hints[ 'before_after_unavailable' ];
		} else if ( jCell.hasClass( 'date2approve' ) ){

		} else if ( jCell.hasClass( 'date_approved' ) ){

		} else {

		}

		jCell.attr( 'data-content', tooltip_time );

		var td_el = jCell.get(0);	//jQuery( '#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class ).get(0);

		if ( ( undefined == td_el._tippy ) && ( '' != tooltip_time ) ){

				wpbc_tippy( td_el , {
					content( reference ){

						var popover_content = reference.getAttribute( 'data-content' );

						return '<div class="popover popover_tippy">'
									+ '<div class="popover-content">'
										+ popover_content
									+ '</div>'
							 + '</div>';
					},
					allowHTML        : true,
					trigger			 : 'mouseenter focus',
					interactive      : ! true,
					hideOnClick      : true,
					interactiveBorder: 10,
					maxWidth         : 550,
					theme            : 'wpbc-tippy-times',
					placement        : 'top',
					delay			 : [400, 0],			//FixIn: 9.4.2.2
					ignoreAttributes : true,
					touch			 : true,				//['hold', 500], // 500ms delay			//FixIn: 9.2.1.5
					appendTo: () => document.body,
				});
		}
	}





/**
 *   Ajax  ------------------------------------------------------------------------------------------------------ */

/**
 * Send Ajax show request
 */
function wpbc_ajx_customize_plugin__ajax_request(){

console.groupCollapsed( 'WPBC_AJX_CUSTOMIZE_PLUGIN' ); console.log( ' == Before Ajax Send - search_get_all_params() == ' , wpbc_ajx_customize_plugin.search_get_all_params() );

	wpbc_customize_plugin_reload_button__spin_start();

	// Start Ajax
	jQuery.post( wpbc_global1.wpbc_ajaxurl,
				{
					action          : 'WPBC_AJX_CUSTOMIZE_PLUGIN',
					wpbc_ajx_user_id: wpbc_ajx_customize_plugin.get_secure_param( 'user_id' ),
					nonce           : wpbc_ajx_customize_plugin.get_secure_param( 'nonce' ),
					wpbc_ajx_locale : wpbc_ajx_customize_plugin.get_secure_param( 'locale' ),

					search_params	: wpbc_ajx_customize_plugin.search_get_all_params()
				},
				/**
				 * S u c c e s s
				 *
				 * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
				 * @param textStatus		-	'success'
				 * @param jqXHR				-	Object
				 */
				function ( response_data, textStatus, jqXHR ) {

console.log( ' == Response WPBC_AJX_CUSTOMIZE_PLUGIN == ', response_data ); console.groupEnd();

					// Probably Error
					if ( (typeof response_data !== 'object') || (response_data === null) ){

						wpbc_ajx_customize_plugin__actual_content__hide();
						wpbc_ajx_customize_plugin__show_message( response_data );

						return;
					}

					// Reload page, after filter toolbar has been reset
					if (       (     undefined != response_data[ 'ajx_cleaned_params' ])
							&& ( 'reset_done' === response_data[ 'ajx_cleaned_params' ][ 'do_action' ])
					){
						location.reload();
						return;
					}

					// Show listing
					wpbc_ajx_customize_plugin__page_content__show( response_data[ 'ajx_data' ], response_data[ 'ajx_search_params' ] , response_data[ 'ajx_cleaned_params' ] );

					//wpbc_ajx_customize_plugin__define_ui_hooks();						// Redefine Hooks, because we show new DOM elements
					if ( '' != response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" ) ){
						wpbc_admin_show_message(
													  response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" )
													, ( '1' == response_data[ 'ajx_data' ][ 'ajx_after_action_result' ] ) ? 'success' : 'error'
													, 10000
												);
					}

					wpbc_customize_plugin_reload_button__spin_pause();
					// Remove spin icon from  button and Enable this button.
					wpbc_button__remove_spin( response_data[ 'ajx_cleaned_params' ][ 'ui_clicked_element_id' ] )

					jQuery( '#ajax_respond' ).html( response_data );		// For ability to show response, add such DIV element to page
				}
			  ).fail( function ( jqXHR, textStatus, errorThrown ) {    if ( window.console && window.console.log ){ console.log( 'Ajax_Error', jqXHR, textStatus, errorThrown ); }

					var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown ;
					if ( jqXHR.status ){
						error_message += ' (<b>' + jqXHR.status + '</b>)';
						if (403 == jqXHR.status ){
							error_message += ' Probably nonce for this page has been expired. Please <a href="javascript:void(0)" onclick="javascript:location.reload();">reload the page</a>.';
						}
					}
					if ( jqXHR.responseText ){
						error_message += ' ' + jqXHR.responseText;
					}
					error_message = error_message.replace( /\n/g, "<br />" );

					wpbc_ajx_customize_plugin__actual_content__hide();
					wpbc_ajx_customize_plugin__show_message( error_message );
			  })
	          // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
			  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
			  ;  // End Ajax

}



/**
 *   H o o k s  -  its Action/Times when need to re-Render Views  ----------------------------------------------- */

/**
 * Send Ajax Search Request after Updating search request parameters
 *
 * @param params_arr
 */
function wpbc_ajx_customize_plugin__send_request_with_params ( params_arr ){

	// Define different Search  parameters for request
	_.each( params_arr, function ( p_val, p_key, p_data ) {
		//console.log( 'Request for: ', p_key, p_val );
		wpbc_ajx_customize_plugin.search_set_param( p_key, p_val );
	});

	// Send Ajax Request
	wpbc_ajx_customize_plugin__ajax_request();
}


	/**
	 * Search request for "Page Number"
	 * @param page_number	int
	 */
	function wpbc_ajx_customize_plugin__pagination_click( page_number ){

		wpbc_ajx_customize_plugin__send_request_with_params( {
											'page_num': page_number
										} );
	}



/**
 *   Show / Hide Content  --------------------------------------------------------------------------------------- */

/**
 *  Show Listing Content 	- 	Sending Ajax Request	-	with parameters that  we early  defined
 */
function wpbc_ajx_customize_plugin__actual_content__show(){

	wpbc_ajx_customize_plugin__ajax_request();			// Send Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
}

/**
 * Hide Listing Content
 */
function wpbc_ajx_customize_plugin__actual_content__hide(){

	jQuery(  wpbc_ajx_customize_plugin.get_other_param( 'listing_container' )  ).html( '' );
}



/**
 *   M e s s a g e  --------------------------------------------------------------------------------------------- */

/**
 *
 */



/**
 * Show message in content
 *
 * @param message				Message HTML
 * @param params = {
 *                   ['type']				'warning' | 'info' | 'error' | 'success'		default: 'warning'
 *                   ['container']			'.wpbc_ajx_cstm__section_left'		default: wpbc_ajx_customize_plugin.get_other_param( 'listing_container' )
 *                   ['is_append']			true | false						default: true
 *				   }
 * Example:
 * 			var html_id = wpbc_ajx_customize_plugin__show_message( 'You can test days selection in calendar', 'info', '.wpbc_ajx_cstm__section_left', true );
 *
 *
 * @returns string  - HTML ID
 */
function wpbc_ajx_customize_plugin__show_message( message, params = {} ){

	var params_default = {
								'type'     : 'warning',
								'container': wpbc_ajx_customize_plugin.get_other_param( 'listing_container' ),
								'is_append': true,
								'style'    : 'text-align:left;',
								'delay'    : 0
							};
	_.each( params, function ( p_val, p_key, p_data ){
		params_default[ p_key ] = p_val;
	} );
	params = params_default;

    var unique_div_id = new Date();
    unique_div_id = 'wpbc_notice_' + unique_div_id.getTime();

	var alert_class = 'notice ';
	if ( params['type'] == 'error' ){
		alert_class += 'notice-error ';
		message = '<i style="margin-right: 0.5em;color: #d63638;" class="menu_icon icon-1x wpbc_icn_report_gmailerrorred"></i>' + message;
	}
	if ( params['type'] == 'warning' ){
		alert_class += 'notice-warning ';
		message = '<i style="margin-right: 0.5em;color: #e9aa04;" class="menu_icon icon-1x wpbc_icn_warning"></i>' + message;
	}
	if ( params['type'] == 'info' ){
		alert_class += 'notice-info ';
	}
	if ( params['type'] == 'success' ){
		alert_class += 'notice-info alert-success updated ';
		message = '<i style="margin-right: 0.5em;color: #64aa45;" class="menu_icon icon-1x wpbc_icn_done_outline"></i>' + message;
	}

	message = '<div id="' + unique_div_id + '" class="wpbc-settings-notice ' + alert_class + '" style="' + params[ 'style' ] + '">' + message + '</div>';

	if ( params['is_append'] ){
		jQuery( params['container'] ).append( message );
	} else {
		jQuery( params['container'] ).html( message );
	}

	params['delay'] = parseInt( params['delay'] );
	if ( params['delay'] > 0 ){

		var closed_timer = setTimeout( function (){
																	jQuery( '#' + unique_div_id ).fadeOut( 1500 );
																}
												, params[ 'delay' ]
											 );
	}

	return unique_div_id;
}



/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Spin button in Filter toolbar  -  Start
 */
function wpbc_customize_plugin_reload_button__spin_start(){
	jQuery( '#wpbc_customize_plugin_reload_button .menu_icon.wpbc_spin').removeClass( 'wpbc_animation_pause' );
}

/**
 * Spin button in Filter toolbar  -  Pause
 */
function wpbc_customize_plugin_reload_button__spin_pause(){
	jQuery( '#wpbc_customize_plugin_reload_button .menu_icon.wpbc_spin' ).addClass( 'wpbc_animation_pause' );
}

/**
 * Spin button in Filter toolbar  -  is Spinning ?
 *
 * @returns {boolean}
 */
function wpbc_customize_plugin_reload_button__is_spin(){
    if ( jQuery( '#wpbc_customize_plugin_reload_button .menu_icon.wpbc_spin' ).hasClass( 'wpbc_animation_pause' ) ){
		return true;
	} else {
		return false;
	}
}
