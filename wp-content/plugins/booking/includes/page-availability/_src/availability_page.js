"use strict";

/**
 * Request Object
 * Here we can  define Search parameters and Update it later,  when  some parameter was changed
 *
 */

var wpbc_ajx_availability = (function ( obj, $) {

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
}( wpbc_ajx_availability || {}, jQuery ));

var wpbc_ajx_bookings = [];

/**
 *   Show Content  ---------------------------------------------------------------------------------------------- */

/**
 * Show Content - Calendar and UI elements
 *
 * @param ajx_data_arr
 * @param ajx_search_params
 * @param ajx_cleaned_params
 */
function wpbc_ajx_availability__page_content__show( ajx_data_arr, ajx_search_params , ajx_cleaned_params ){

	var template__availability_main_page_content = wp.template( 'wpbc_ajx_availability_main_page_content' );

	// Content
	jQuery( wpbc_ajx_availability.get_other_param( 'listing_container' ) ).html( template__availability_main_page_content( {
																'ajx_data'              : ajx_data_arr,
																'ajx_search_params'     : ajx_search_params,								// $_REQUEST[ 'search_params' ]
																'ajx_cleaned_params'    : ajx_cleaned_params
									} ) );

	jQuery( '.wpbc_processing.wpbc_spin').parent().parent().parent().parent( '[id^="wpbc_notice_"]' ).hide();
	// Load calendar
	wpbc_ajx_availability__calendar__show( {
											'resource_id'       : ajx_cleaned_params.resource_id,
											'ajx_nonce_calendar': ajx_data_arr.ajx_nonce_calendar,
											'ajx_data_arr'          : ajx_data_arr,
											'ajx_cleaned_params'    : ajx_cleaned_params
										} );


	/**
	 * Trigger for dates selection in the booking form
	 *
	 * jQuery( wpbc_ajx_availability.get_other_param( 'listing_container' ) ).on('wpbc_page_content_loaded', function(event, ajx_data_arr, ajx_search_params , ajx_cleaned_params) { ... } );
	 */
	jQuery( wpbc_ajx_availability.get_other_param( 'listing_container' ) ).trigger( 'wpbc_page_content_loaded', [ ajx_data_arr, ajx_search_params , ajx_cleaned_params ] );
}


/**
 * Show inline month view calendar              with all predefined CSS (sizes and check in/out,  times containers)
 * @param {obj} calendar_params_arr
			{
				'resource_id'       	: ajx_cleaned_params.resource_id,
				'ajx_nonce_calendar'	: ajx_data_arr.ajx_nonce_calendar,
				'ajx_data_arr'          : ajx_data_arr = { ajx_booking_resources:[], booked_dates: {}, resource_unavailable_dates:[], season_availability:{},.... }
				'ajx_cleaned_params'    : {
											calendar__days_selection_mode: "dynamic"
											calendar__start_week_day: "0"
											calendar__timeslot_day_bg_as_available: ""
											calendar__view__cell_height: ""
											calendar__view__months_in_row: 4
											calendar__view__visible_months: 12
											calendar__view__width: "100%"

											dates_availability: "unavailable"
											dates_selection: "2023-03-14 ~ 2023-03-16"
											do_action: "set_availability"
											resource_id: 1
											ui_clicked_element_id: "wpbc_availability_apply_btn"
											ui_usr__availability_selected_toolbar: "info"
								  		 }
			}
*/
function wpbc_ajx_availability__calendar__show( calendar_params_arr ){

	// Update nonce
	jQuery( '#ajx_nonce_calendar_section' ).html( calendar_params_arr.ajx_nonce_calendar );

	//------------------------------------------------------------------------------------------------------------------
	// Update bookings
	if ( 'undefined' == typeof (wpbc_ajx_bookings[ calendar_params_arr.resource_id ]) ){ wpbc_ajx_bookings[ calendar_params_arr.resource_id ] = []; }
	wpbc_ajx_bookings[ calendar_params_arr.resource_id ] = calendar_params_arr[ 'ajx_data_arr' ][ 'booked_dates' ];


	//------------------------------------------------------------------------------------------------------------------
	/**
	 * Define showing mouse over tooltip on unavailable dates
	 * It's defined, when calendar REFRESHED (change months or days selection) loaded in jquery.datepick.wpbc.9.0.js :
	 * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_refresh', ...		//FixIn: 9.4.4.13
	 */
	jQuery( 'body' ).on( 'wpbc_datepick_inline_calendar_refresh', function ( event, resource_id, inst ){
		// inst.dpDiv  it's:  <div class="datepick-inline datepick-multi" style="width: 17712px;">....</div>
		inst.dpDiv.find( '.season_unavailable,.before_after_unavailable,.weekdays_unavailable' ).on( 'mouseover', function ( this_event ){
			// also available these vars: 	resource_id, jCalContainer, inst
			var jCell = jQuery( this_event.currentTarget );
			wpbc_avy__show_tooltip__for_element( jCell, calendar_params_arr[ 'ajx_data_arr' ]['popover_hints'] );
		});

	}	);

	//------------------------------------------------------------------------------------------------------------------
	/**
	 * Define height of the calendar  cells, 	and  mouse over tooltips at  some unavailable dates
	 * It's defined, when calendar loaded in jquery.datepick.wpbc.9.0.js :
	 * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_loaded', ...		//FixIn: 9.4.4.12
	 */
	jQuery( 'body' ).on( 'wpbc_datepick_inline_calendar_loaded', function ( event, resource_id, jCalContainer, inst ){

		// Remove highlight day for today  date
		jQuery( '.datepick-days-cell.datepick-today.datepick-days-cell-over' ).removeClass( 'datepick-days-cell-over' );

		// Set height of calendar  cells if defined this option
		if ( '' !== calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height ){
			jQuery( 'head' ).append( '<style type="text/css">'
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
			wpbc_avy__show_tooltip__for_element( jCell, calendar_params_arr[ 'ajx_data_arr' ]['popover_hints'] );
		});
	} );

	//------------------------------------------------------------------------------------------------------------------
	// Define width of entire calendar
	var width =   'width:'		+   calendar_params_arr.ajx_cleaned_params.calendar__view__width + ';';					// var width = 'width:100%;max-width:100%;';

	if (   ( undefined != calendar_params_arr.ajx_cleaned_params.calendar__view__max_width )
		&& ( '' != calendar_params_arr.ajx_cleaned_params.calendar__view__max_width )
	){
		width += 'max-width:' 	+ calendar_params_arr.ajx_cleaned_params.calendar__view__max_width + ';';
	} else {
		width += 'max-width:' 	+ ( calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row * 341 ) + 'px;';
	}

	//------------------------------------------------------------------------------------------------------------------
	// Add calendar container: "Calendar is loading..."  and textarea
	jQuery( '.wpbc_ajx_avy__calendar' ).html(

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
	var cal_param_arr = {
							'html_id'           : 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
							'text_id'           : 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,

							'calendar__start_week_day': 	  calendar_params_arr.ajx_cleaned_params.calendar__start_week_day,
							'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
							'calendar__days_selection_mode':  calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,

							'resource_id'        : calendar_params_arr.ajx_cleaned_params.resource_id,
							'ajx_nonce_calendar' : calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
							'booked_dates'       : calendar_params_arr.ajx_data_arr.booked_dates,
							'season_availability': calendar_params_arr.ajx_data_arr.season_availability,

							'resource_unavailable_dates' : calendar_params_arr.ajx_data_arr.resource_unavailable_dates,

							'popover_hints': calendar_params_arr[ 'ajx_data_arr' ]['popover_hints']		// {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}
						};
	wpbc_show_inline_booking_calendar( cal_param_arr );

	//------------------------------------------------------------------------------------------------------------------
	/**
	 * On click AVAILABLE |  UNAVAILABLE button  in widget	-	need to  change help dates text
	 */
	jQuery( '.wpbc_radio__set_days_availability' ).on('change', function ( event, resource_id, inst ){
		wpbc__inline_booking_calendar__on_days_select( jQuery( '#' + cal_param_arr.text_id ).val() , cal_param_arr );
	});

	// Show 	'Select days  in calendar then select Available  /  Unavailable status and click Apply availability button.'
	jQuery( '#wpbc_toolbar_dates_hint').html(     '<div class="ui_element"><span class="wpbc_ui_control wpbc_ui_addon wpbc_help_text" >'
													+ cal_param_arr.popover_hints.toolbar_text
												+ '</span></div>'
											);
}


/**
 * 	Load Datepick Inline calendar
 *
 * @param calendar_params_arr		example:{
											'html_id'           : 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
											'text_id'           : 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,

											'calendar__start_week_day': 	  calendar_params_arr.ajx_cleaned_params.calendar__start_week_day,
											'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
											'calendar__days_selection_mode':  calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,

											'resource_id'        : calendar_params_arr.ajx_cleaned_params.resource_id,
											'ajx_nonce_calendar' : calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
											'booked_dates'       : calendar_params_arr.ajx_data_arr.booked_dates,
											'season_availability': calendar_params_arr.ajx_data_arr.season_availability,

											'resource_unavailable_dates' : calendar_params_arr.ajx_data_arr.resource_unavailable_dates
										}
 * @returns {boolean}
 */
function wpbc_show_inline_booking_calendar( calendar_params_arr ){

	if (
		   ( 0 === jQuery( '#' + calendar_params_arr.html_id ).length )							// If calendar DOM element not exist then exist
		|| ( true === jQuery( '#' + calendar_params_arr.html_id ).hasClass( 'hasDatepick' ) )	// If the calendar with the same Booking resource already  has been activated, then exist.
	){
	   return false;
	}

	//------------------------------------------------------------------------------------------------------------------
	// Configure and show calendar
	jQuery( '#' + calendar_params_arr.html_id ).text( '' );
	jQuery( '#' + calendar_params_arr.html_id ).datepick({
					beforeShowDay: 	function ( date ){
										return wpbc__inline_booking_calendar__apply_css_to_days( date, calendar_params_arr, this );
									},
                    onSelect: 	  	function ( date ){
										jQuery( '#' + calendar_params_arr.text_id ).val( date );
										//wpbc_blink_element('.wpbc_widget_available_unavailable', 3, 220);
										return wpbc__inline_booking_calendar__on_days_select( date, calendar_params_arr, this );
									},
                    onHover: 		function ( value, date ){

										//wpbc_avy__prepare_tooltip__in_calendar( value, date, calendar_params_arr, this );

										return wpbc__inline_booking_calendar__on_days_hover( value, date, calendar_params_arr, this );
									},
                    onChangeMonthYear:	null,
                    showOn: 			'both',
                    numberOfMonths: 	calendar_params_arr.calendar__view__visible_months,
                    stepMonths:			1,
                    prevText: 			'&laquo;',
                    nextText: 			'&raquo;',
                    dateFormat: 		'yy-mm-dd',// 'dd.mm.yy',
                    changeMonth: 		false,
                    changeYear: 		false,
                    minDate: 					 0,		//null,  //Scroll as long as you need
					maxDate: 					'10y',	// minDate: new Date(2020, 2, 1), maxDate: new Date(2020, 9, 31), 	// Ability to set any  start and end date in calendar
                    showStatus: 		false,
                    closeAtTop: 		false,
                    firstDay:			calendar_params_arr.calendar__start_week_day,
                    gotoCurrent: 		false,
                    hideIfNoPrevNext:	true,
                    multiSeparator: 	', ',
					multiSelect: (('dynamic' == calendar_params_arr.calendar__days_selection_mode) ? 0 : 365),			// Maximum number of selectable dates:	 Single day = 0,  multi days = 365
					rangeSelect:  ('dynamic' == calendar_params_arr.calendar__days_selection_mode),
					rangeSeparator: 	' ~ ',					//' - ',
                    // showWeeks: true,
                    useThemeRoller:		false
                }
        );

	return  true;
}


	/**
	 * Apply CSS to calendar date cells
	 *
	 * @param date					-  JavaScript Date Obj:  		Mon Dec 11 2023 00:00:00 GMT+0200 (Eastern European Standard Time)
	 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																	  "html_id": "calendar_booking4",
																	  "text_id": "date_booking4",
																	  "calendar__start_week_day": 1,
																	  "calendar__view__visible_months": 12,
																	  "resource_id": 4,
																	  "ajx_nonce_calendar": "<input type=\"hidden\" ... />",
																	  "booked_dates": {
																		"12-28-2022": [
																		  {
																			"booking_date": "2022-12-28 00:00:00",
																			"approved": "1",
																			"booking_id": "26"
																		  }
																		], ...
																		}
																		'season_availability':{
																			"2023-01-09": true,
																			"2023-01-10": true,
																			"2023-01-11": true, ...
																		}
																	  }
																	}
	 * @param datepick_this			- this of datepick Obj
	 *
	 * @returns [boolean,string]	- [ {true -available | false - unavailable}, 'CSS classes for calendar day cell' ]
	 */
	function wpbc__inline_booking_calendar__apply_css_to_days( date, calendar_params_arr, datepick_this ){

		var today_date = new Date( wpbc_today[ 0 ], (parseInt( wpbc_today[ 1 ] ) - 1), wpbc_today[ 2 ], 0, 0, 0 );

		var class_day  = ( date.getMonth() + 1 ) + '-' + date.getDate() + '-' + date.getFullYear();						// '1-9-2023'
		var sql_class_day = wpbc__get__sql_class_date( date );																			// '2023-01-09'

		var css_date__standard   =  'cal4date-' + class_day;
		var css_date__additional = ' wpbc_weekday_' + date.getDay() + ' ';

		//--------------------------------------------------------------------------------------------------------------

		// WEEKDAYS :: Set unavailable week days from - Settings General page in "Availability" section
		for ( var i = 0; i < user_unavilable_days.length; i++ ){
			if ( date.getDay() == user_unavilable_days[ i ] ) {
				return [ !!false, css_date__standard + ' date_user_unavailable' 	+ ' weekdays_unavailable' ];
			}
		}

		// BEFORE_AFTER :: Set unavailable days Before / After the Today date
		if ( 	( (days_between( date, today_date )) < block_some_dates_from_today )
			 || (
				   ( typeof( wpbc_available_days_num_from_today ) !== 'undefined' )
				&& ( parseInt( '0' + wpbc_available_days_num_from_today ) > 0 )
				&& ( days_between( date, today_date ) > parseInt( '0' + wpbc_available_days_num_from_today ) )
				)
		){
			return [ !!false, css_date__standard + ' date_user_unavailable' 		+ ' before_after_unavailable' ];
		}

		// SEASONS ::  					Booking > Resources > Availability page
		var    is_date_available = calendar_params_arr.season_availability[ sql_class_day ];
		if ( false === is_date_available ){																				//FixIn: 9.5.4.4
			return [ !!false, css_date__standard + ' date_user_unavailable'		+ ' season_unavailable' ];
		}

		// RESOURCE_UNAVAILABLE ::   	Booking > Availability page
		if ( wpdev_in_array(calendar_params_arr.resource_unavailable_dates, sql_class_day ) ){
			is_date_available = false;
		}
		if (  false === is_date_available ){																			//FixIn: 9.5.4.4
			return [ !false, css_date__standard + ' date_user_unavailable'		+ ' resource_unavailable' ];
		}

		//--------------------------------------------------------------------------------------------------------------

		//--------------------------------------------------------------------------------------------------------------


		// Is any bookings in this date ?
		if ( 'undefined' !== typeof( calendar_params_arr.booked_dates[ class_day ] ) ) {

			var bookings_in_date = calendar_params_arr.booked_dates[ class_day ];


			if ( 'undefined' !== typeof( bookings_in_date[ 'sec_0' ] ) ) {			// "Full day" booking  -> (seconds == 0)

				css_date__additional += ( '0' === bookings_in_date[ 'sec_0' ].approved ) ? ' date2approve ' : ' date_approved ';				// Pending = '0' |  Approved = '1'
				css_date__additional += ' full_day_booking';

				return [ !false, css_date__standard + css_date__additional ];

			} else if ( Object.keys( bookings_in_date ).length > 0 ){				// "Time slots" Bookings

				var is_approved = true;

				_.each( bookings_in_date, function ( p_val, p_key, p_data ) {
					if ( !parseInt( p_val.approved ) ){
						is_approved = false;
					}
					var ts = p_val.booking_date.substring( p_val.booking_date.length - 1 );
					if ( true === is_booking_used_check_in_out_time ){
						if ( ts == '1' ) { css_date__additional += ' check_in_time' + ((parseInt(p_val.approved)) ? ' check_in_time_date_approved' : ' check_in_time_date2approve'); }
						if ( ts == '2' ) { css_date__additional += ' check_out_time' + ((parseInt(p_val.approved)) ? ' check_out_time_date_approved' : ' check_out_time_date2approve'); }
					}

				});

				if ( ! is_approved ){
					css_date__additional += ' date2approve timespartly'
				} else {
					css_date__additional += ' date_approved timespartly'
				}

				if ( ! is_booking_used_check_in_out_time ){
					css_date__additional += ' times_clock'
				}

			}

		}

		//--------------------------------------------------------------------------------------------------------------

		return [ true, css_date__standard + css_date__additional + ' date_available' ];
	}


	/**
	 * Apply some CSS classes, when we mouse over specific dates in calendar
	 * @param value
	 * @param date					-  JavaScript Date Obj:  		Mon Dec 11 2023 00:00:00 GMT+0200 (Eastern European Standard Time)
	 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																	  "html_id": "calendar_booking4",
																	  "text_id": "date_booking4",
																	  "calendar__start_week_day": 1,
																	  "calendar__view__visible_months": 12,
																	  "resource_id": 4,
																	  "ajx_nonce_calendar": "<input type=\"hidden\" ... />",
																	  "booked_dates": {
																		"12-28-2022": [
																		  {
																			"booking_date": "2022-12-28 00:00:00",
																			"approved": "1",
																			"booking_id": "26"
																		  }
																		], ...
																		}
																		'season_availability':{
																			"2023-01-09": true,
																			"2023-01-10": true,
																			"2023-01-11": true, ...
																		}
																	  }
																	}
	 * @param datepick_this			- this of datepick Obj
	 *
	 * @returns {boolean}
	 */
	function wpbc__inline_booking_calendar__on_days_hover( value, date, calendar_params_arr, datepick_this ){

		if ( null === date ){
			jQuery( '.datepick-days-cell-over' ).removeClass( 'datepick-days-cell-over' );   	                        // clear all highlight days selections
			return false;
		}

		var inst = jQuery.datepick._getInst( document.getElementById( 'calendar_booking' + calendar_params_arr.resource_id ) );

		if (
			   ( 1 == inst.dates.length)															// If we have one selected date
			&& ('dynamic' === calendar_params_arr.calendar__days_selection_mode) 					// while have range days selection mode
		){

			var td_class;
			var td_overs = [];
			var is_check = true;
            var selceted_first_day = new Date();
            selceted_first_day.setFullYear(inst.dates[0].getFullYear(),(inst.dates[0].getMonth()), (inst.dates[0].getDate() ) ); //Get first Date

            while(  is_check ){

				td_class = (selceted_first_day.getMonth() + 1) + '-' + selceted_first_day.getDate() + '-' + selceted_first_day.getFullYear();

				td_overs[ td_overs.length ] = '#calendar_booking' + calendar_params_arr.resource_id + ' .cal4date-' + td_class;              // add to array for later make selection by class

                if (
					(  ( date.getMonth() == selceted_first_day.getMonth() )  &&
                       ( date.getDate() == selceted_first_day.getDate() )  &&
                       ( date.getFullYear() == selceted_first_day.getFullYear() )
					) || ( selceted_first_day > date )
				){
					is_check =  false;
				}

				selceted_first_day.setFullYear( selceted_first_day.getFullYear(), (selceted_first_day.getMonth()), (selceted_first_day.getDate() + 1) );
			}

			// Highlight Days
			for ( var i=0; i < td_overs.length ; i++) {                                                             // add class to all elements
				jQuery( td_overs[i] ).addClass('datepick-days-cell-over');
			}
			return true;

		}

	    return true;
	}


	/**
	 * On DAYs selection in calendar
	 *
	 * @param dates_selection		-  string:			 '2023-03-07 ~ 2023-03-07' or '2023-04-10, 2023-04-12, 2023-04-02, 2023-04-04'
	 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																	  "html_id": "calendar_booking4",
																	  "text_id": "date_booking4",
																	  "calendar__start_week_day": 1,
																	  "calendar__view__visible_months": 12,
																	  "resource_id": 4,
																	  "ajx_nonce_calendar": "<input type=\"hidden\" ... />",
																	  "booked_dates": {
																		"12-28-2022": [
																		  {
																			"booking_date": "2022-12-28 00:00:00",
																			"approved": "1",
																			"booking_id": "26"
																		  }
																		], ...
																		}
																		'season_availability':{
																			"2023-01-09": true,
																			"2023-01-10": true,
																			"2023-01-11": true, ...
																		}
																	  }
																	}
	 * @param datepick_this			- this of datepick Obj
	 *
	 * @returns boolean
	 */
	function wpbc__inline_booking_calendar__on_days_select( dates_selection, calendar_params_arr, datepick_this = null ){

		var inst = jQuery.datepick._getInst( document.getElementById( 'calendar_booking' + calendar_params_arr.resource_id ) );

		var dates_arr = [];	//  [ "2023-04-09", "2023-04-10", "2023-04-11" ]

		if ( -1 !== dates_selection.indexOf( '~' ) ) {                                        // Range Days

			dates_arr = wpbc_get_dates_arr__from_dates_range_js( {
																	'dates_separator' : ' ~ ',                         //  ' ~ '
																	'dates'           : dates_selection,    		   // '2023-04-04 ~ 2023-04-07'
																} );

		} else {                                                                                // Multiple Days
			dates_arr = wpbc_get_dates_arr__from_dates_comma_separated_js( {
																	'dates_separator' : ', ',                         	//  ', '
																	'dates'           : dates_selection,    			// '2023-04-10, 2023-04-12, 2023-04-02, 2023-04-04'
																} );
		}

		wpbc_avy_after_days_selection__show_help_info({
														'calendar__days_selection_mode': calendar_params_arr.calendar__days_selection_mode,
														'dates_arr'                    : dates_arr,
														'dates_click_num'              : inst.dates.length,
														'popover_hints'					: calendar_params_arr.popover_hints
													} );
		return true;
	}

		/**
		 * Show help info at the top  toolbar about selected dates and future actions
		 *
		 * @param params
		 * 					Example 1:  {
											calendar__days_selection_mode: "dynamic",
											dates_arr:  [ "2023-04-03" ],
											dates_click_num: 1
											'popover_hints'					: calendar_params_arr.popover_hints
										}
		 * 					Example 2:  {
											calendar__days_selection_mode: "dynamic"
											dates_arr: Array(10) [ "2023-04-03", "2023-04-04", "2023-04-05", … ]
											dates_click_num: 2
											'popover_hints'					: calendar_params_arr.popover_hints
										}
		 */
		function wpbc_avy_after_days_selection__show_help_info( params ){
// console.log( params );	//		[ "2023-04-09", "2023-04-10", "2023-04-11" ]

			var message, color;
			if (jQuery( '#ui_btn_avy__set_days_availability__available').is(':checked')){
				 message = params.popover_hints.toolbar_text_available;//'Set dates _DATES_ as _HTML_ available.';
				 color = '#11be4c';
			} else {
				message = params.popover_hints.toolbar_text_unavailable;//'Set dates _DATES_ as _HTML_ unavailable.';
				color = '#e43939';
			}

			message = '<span>' + message + '</span>';

			var first_date = params[ 'dates_arr' ][ 0 ];
			var last_date  = ( 'dynamic' == params.calendar__days_selection_mode )
							? params[ 'dates_arr' ][ (params[ 'dates_arr' ].length - 1) ]
							: ( params[ 'dates_arr' ].length > 1 ) ? params[ 'dates_arr' ][ 1 ] : '';

			first_date = jQuery.datepick.formatDate( 'dd M, yy', new Date( first_date + 'T00:00:00' ) );
			last_date = jQuery.datepick.formatDate( 'dd M, yy',  new Date( last_date + 'T00:00:00' ) );


			if ( 'dynamic' == params.calendar__days_selection_mode ){
				if ( 1 == params.dates_click_num ){
					last_date = '___________'
				} else {
					if ( 'first_time' == jQuery( '.wpbc_ajx_availability_container' ).attr( 'wpbc_loaded' ) ){
						jQuery( '.wpbc_ajx_availability_container' ).attr( 'wpbc_loaded', 'done' )
						wpbc_blink_element( '.wpbc_widget_available_unavailable', 3, 220 );
					}
				}
				message = message.replace( '_DATES_',    '</span>'
														//+ '<div>' + 'from' + '</div>'
														+ '<span class="wpbc_big_date">' + first_date + '</span>'
														+ '<span>' + '-' + '</span>'
														+ '<span class="wpbc_big_date">' + last_date + '</span>'
														+ '<span>' );
			} else {
				// if ( params[ 'dates_arr' ].length > 1 ){
				// 	last_date = ', ' + last_date;
				// 	last_date += ( params[ 'dates_arr' ].length > 2 ) ? ', ...' : '';
				// } else {
				// 	last_date='';
				// }
				var dates_arr = [];
				for( var i = 0; i < params[ 'dates_arr' ].length; i++ ){
					dates_arr.push(  jQuery.datepick.formatDate( 'dd M yy',  new Date( params[ 'dates_arr' ][ i ] + 'T00:00:00' ) )  );
				}
				first_date = dates_arr.join( ', ' );
				message = message.replace( '_DATES_',    '</span>'
														+ '<span class="wpbc_big_date">' + first_date + '</span>'
														+ '<span>' );
			}
			message = message.replace( '_HTML_' , '</span><span class="wpbc_big_text" style="color:'+color+';">') + '<span>';

			//message += ' <div style="margin-left: 1em;">' + ' Click on Apply button to apply availability.' + '</div>';

			message = '<div class="wpbc_toolbar_dates_hints">' + message + '</div>';

			jQuery( '.wpbc_help_text' ).html(	message );
		}

	/**
	 *   Parse dates  ------------------------------------------------------------------------------------------- */

		/**
		 * Get dates array,  from comma separated dates
		 *
		 * @param params       = {
											* 'dates_separator' => ', ',                                        // Dates separator
											* 'dates'           => '2023-04-04, 2023-04-07, 2023-04-05'         // Dates in 'Y-m-d' format: '2023-01-31'
								 }
		 *
		 * @return array      = [
											* [0] => 2023-04-04
											* [1] => 2023-04-05
											* [2] => 2023-04-06
											* [3] => 2023-04-07
								]
		 *
		 * Example #1:  wpbc_get_dates_arr__from_dates_comma_separated_js(  {  'dates_separator' : ', ', 'dates' : '2023-04-04, 2023-04-07, 2023-04-05'  }  );
		 */
		function wpbc_get_dates_arr__from_dates_comma_separated_js( params ){

			var dates_arr = [];

			if ( '' !== params[ 'dates' ] ){

				dates_arr = params[ 'dates' ].split( params[ 'dates_separator' ] );

				dates_arr.sort();
			}
			return dates_arr;
		}

		/**
		 * Get dates array,  from range days selection
		 *
		 * @param params       =  {
											* 'dates_separator' => ' ~ ',                         // Dates separator
											* 'dates'           => '2023-04-04 ~ 2023-04-07'      // Dates in 'Y-m-d' format: '2023-01-31'
								  }
		 *
		 * @return array        = [
											* [0] => 2023-04-04
											* [1] => 2023-04-05
											* [2] => 2023-04-06
											* [3] => 2023-04-07
								  ]
		 *
		 * Example #1:  wpbc_get_dates_arr__from_dates_range_js(  {  'dates_separator' : ' ~ ', 'dates' : '2023-04-04 ~ 2023-04-07'  }  );
		 * Example #2:  wpbc_get_dates_arr__from_dates_range_js(  {  'dates_separator' : ' - ', 'dates' : '2023-04-04 - 2023-04-07'  }  );
		 */
		function wpbc_get_dates_arr__from_dates_range_js( params ){

			var dates_arr = [];

			if ( '' !== params['dates'] ) {

				dates_arr = params[ 'dates' ].split( params[ 'dates_separator' ] );
				var check_in_date_ymd  = dates_arr[0];
				var check_out_date_ymd = dates_arr[1];

				if ( ('' !== check_in_date_ymd) && ('' !== check_out_date_ymd) ){

					dates_arr = wpbc_get_dates_array_from_start_end_days_js( check_in_date_ymd, check_out_date_ymd );
				}
			}
			return dates_arr;
		}

			/**
			 * Get dates array based on start and end dates.
			 *
			 * @param string sStartDate - start date: 2023-04-09
			 * @param string sEndDate   - end date:   2023-04-11
			 * @return array             - [ "2023-04-09", "2023-04-10", "2023-04-11" ]
			 */
			function wpbc_get_dates_array_from_start_end_days_js( sStartDate, sEndDate ){

				sStartDate = new Date( sStartDate + 'T00:00:00' );
				sEndDate = new Date( sEndDate + 'T00:00:00' );

				var aDays=[];

				// Start the variable off with the start date
				aDays.push( sStartDate.getTime() );

				// Set a 'temp' variable, sCurrentDate, with the start date - before beginning the loop
				var sCurrentDate = new Date( sStartDate.getTime() );
				var one_day_duration = 24*60*60*1000;

				// While the current date is less than the end date
				while(sCurrentDate < sEndDate){
					// Add a day to the current date "+1 day"
					sCurrentDate.setTime( sCurrentDate.getTime() + one_day_duration );

					// Add this new day to the aDays array
					aDays.push( sCurrentDate.getTime() );
				}

				for (let i = 0; i < aDays.length; i++) {
					aDays[ i ] = new Date( aDays[i] );
					aDays[ i ] = aDays[ i ].getFullYear()
								+ '-' + (( (aDays[ i ].getMonth() + 1) < 10) ? '0' : '') + (aDays[ i ].getMonth() + 1)
								+ '-' + ((        aDays[ i ].getDate() < 10) ? '0' : '') +  aDays[ i ].getDate();
				}
				// Once the loop has finished, return the array of days.
				return aDays;
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
	function wpbc_avy__prepare_tooltip__in_calendar( value, date, calendar_params_arr, datepick_this ){

		if ( null == date ){  return false;  }

		var td_class = ( date.getMonth() + 1 ) + '-' + date.getDate() + '-' + date.getFullYear();

		var jCell = jQuery( '#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class );

		wpbc_avy__show_tooltip__for_element( jCell, calendar_params_arr[ 'popover_hints' ] );
		return true;
	}


	/**
	 * Define tooltip  for showing on UNAVAILABLE days (season, weekday, today_depends unavailable)
	 *
	 * @param jCell					jQuery of specific day cell
	 * @param popover_hints		    Array with tooltip hint texts	 : {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}
	 */
	function wpbc_avy__show_tooltip__for_element( jCell, popover_hints ){

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
function wpbc_ajx_availability__ajax_request(){

console.groupCollapsed( 'WPBC_AJX_AVAILABILITY' ); console.log( ' == Before Ajax Send - search_get_all_params() == ' , wpbc_ajx_availability.search_get_all_params() );

	wpbc_availability_reload_button__spin_start();

	// Start Ajax
	jQuery.post( wpbc_global1.wpbc_ajaxurl,
				{
					action          : 'WPBC_AJX_AVAILABILITY',
					wpbc_ajx_user_id: wpbc_ajx_availability.get_secure_param( 'user_id' ),
					nonce           : wpbc_ajx_availability.get_secure_param( 'nonce' ),
					wpbc_ajx_locale : wpbc_ajx_availability.get_secure_param( 'locale' ),

					search_params	: wpbc_ajx_availability.search_get_all_params()
				},
				/**
				 * S u c c e s s
				 *
				 * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
				 * @param textStatus		-	'success'
				 * @param jqXHR				-	Object
				 */
				function ( response_data, textStatus, jqXHR ) {

console.log( ' == Response WPBC_AJX_AVAILABILITY == ', response_data ); console.groupEnd();

					// Probably Error
					if ( (typeof response_data !== 'object') || (response_data === null) ){

						wpbc_ajx_availability__show_message( response_data );

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
					wpbc_ajx_availability__page_content__show( response_data[ 'ajx_data' ], response_data[ 'ajx_search_params' ] , response_data[ 'ajx_cleaned_params' ] );

					//wpbc_ajx_availability__define_ui_hooks();						// Redefine Hooks, because we show new DOM elements
					if ( '' != response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" ) ){
						wpbc_admin_show_message(
													  response_data[ 'ajx_data' ][ 'ajx_after_action_message' ].replace( /\n/g, "<br />" )
													, ( '1' == response_data[ 'ajx_data' ][ 'ajx_after_action_result' ] ) ? 'success' : 'error'
													, 10000
												);
					}

					wpbc_availability_reload_button__spin_pause();
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

					wpbc_ajx_availability__show_message( error_message );
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
function wpbc_ajx_availability__send_request_with_params ( params_arr ){

	// Define different Search  parameters for request
	_.each( params_arr, function ( p_val, p_key, p_data ) {
		//console.log( 'Request for: ', p_key, p_val );
		wpbc_ajx_availability.search_set_param( p_key, p_val );
	});

	// Send Ajax Request
	wpbc_ajx_availability__ajax_request();
}


	/**
	 * Search request for "Page Number"
	 * @param page_number	int
	 */
	function wpbc_ajx_availability__pagination_click( page_number ){

		wpbc_ajx_availability__send_request_with_params( {
											'page_num': page_number
										} );
	}



/**
 *   Show / Hide Content  --------------------------------------------------------------------------------------- */

/**
 *  Show Listing Content 	- 	Sending Ajax Request	-	with parameters that  we early  defined
 */
function wpbc_ajx_availability__actual_content__show(){

	wpbc_ajx_availability__ajax_request();			// Send Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
}

/**
 * Hide Listing Content
 */
function wpbc_ajx_availability__actual_content__hide(){

	jQuery(  wpbc_ajx_availability.get_other_param( 'listing_container' )  ).html( '' );
}



/**
 *   M e s s a g e  --------------------------------------------------------------------------------------------- */

/**
 * Show just message instead of content
 */
function wpbc_ajx_availability__show_message( message ){

	wpbc_ajx_availability__actual_content__hide();

	jQuery( wpbc_ajx_availability.get_other_param( 'listing_container' ) ).html(
												'<div class="wpbc-settings-notice notice-warning" style="text-align:left">' +
													message +
												'</div>'
										);
}



/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Spin button in Filter toolbar  -  Start
 */
function wpbc_availability_reload_button__spin_start(){
	jQuery( '#wpbc_availability_reload_button .menu_icon.wpbc_spin').removeClass( 'wpbc_animation_pause' );
}

/**
 * Spin button in Filter toolbar  -  Pause
 */
function wpbc_availability_reload_button__spin_pause(){
	jQuery( '#wpbc_availability_reload_button .menu_icon.wpbc_spin' ).addClass( 'wpbc_animation_pause' );
}

/**
 * Spin button in Filter toolbar  -  is Spinning ?
 *
 * @returns {boolean}
 */
function wpbc_availability_reload_button__is_spin(){
    if ( jQuery( '#wpbc_availability_reload_button .menu_icon.wpbc_spin' ).hasClass( 'wpbc_animation_pause' ) ){
		return true;
	} else {
		return false;
	}
}
