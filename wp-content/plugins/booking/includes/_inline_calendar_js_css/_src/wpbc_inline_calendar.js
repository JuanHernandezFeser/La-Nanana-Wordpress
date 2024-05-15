"use strict";

/**
 * Define JavaScript variables for front-end calendar for backward compatibility
 *
 * @param calendar_params_arr example:{
											'html_id'           : 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
											'text_id'           : 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,

											'calendar__booking_start_day_weeek': 	  calendar_params_arr.ajx_cleaned_params.calendar__booking_start_day_weeek,
											'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
											'calendar__days_selection_mode':  calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,

											'resource_id'        : calendar_params_arr.ajx_cleaned_params.resource_id,
											'ajx_nonce_calendar' : calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
											'booked_dates'       : calendar_params_arr.ajx_data_arr.booked_dates,
											'season_customize_plugin': calendar_params_arr.ajx_data_arr.season_customize_plugin,

											'resource_unavailable_dates' : calendar_params_arr.ajx_data_arr.resource_unavailable_dates
										}
 */
function wpbc_assign_global_js_for_calendar( calendar_params_arr ){
//TODO: need to  test it before remove
/*
	is_booking_used_check_in_out_time = ('On' == calendar_params_arr.booking_range_selection_time_is_active) ? true : false;

	// -----------------------------------------------------------------------------------------------------------------
	// Dates Availability variables (required for front-end side)
	// -----------------------------------------------------------------------------------------------------------------
	is_all_days_available[ calendar_params_arr.resource_id ] = true;	//todo:delete it
	avalaibility_filters[  calendar_params_arr.resource_id ] = [];		//todo: delete it

	// -----------------------------------------------------------------------------------------------------------------
	// Dates selection
	// -----------------------------------------------------------------------------------------------------------------
	bk_days_selection_mode = calendar_params_arr.calendar__days_selection_mode;											// 'single', 'multiple', 'fixed', 'dynamic'

	if ( typeof wpbc_global3 !== 'undefined' ){																			// Booking Calendar Business Small or higher versions
		bk_2clicks_mode_days_min = calendar_params_arr.calendar__bk_2clicks_mode_days_min;  							// 1;    	// Min
		bk_2clicks_mode_days_max = calendar_params_arr.calendar__bk_2clicks_mode_days_max;								// 30;   	// Max
		bk_2clicks_mode_days_specific = calendar_params_arr.calendar__bk_2clicks_mode_days_specific.split( ',' ).filter( function ( v ){ return v !== ''; } );																											// [7, 14, 21];   	// Example [5,7]
		bk_2clicks_mode_days_start    = calendar_params_arr.calendar__bk_2clicks_mode_days_start.split( ',' ).filter( function ( v ){ return v !== ''; } );																											// [5, 1, 3]; 	// { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat }
		bk_1click_mode_days_num   = calendar_params_arr.calendar__bk_1click_mode_days_num;  							// 7;    		// Number of days selection with 1 mouse click
		bk_1click_mode_days_start = calendar_params_arr.calendar__bk_1click_mode_days_start.split( ',' ).filter( function ( v ){ return v !== ''; } );  																											// [-1]; 		// { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat }
	}
	if ( typeof wpbc_global4 !== 'undefined' ){																			//  Booking Calendar Business Medium or higher versions
		bk_2clicks_mode_days_selection__saved_variables = [
															bk_2clicks_mode_days_specific,
															bk_2clicks_mode_days_min,
															bk_2clicks_mode_days_max,
															bk_1click_mode_days_num
														  ];
	}

	// -----------------------------------------------------------------------------------------------------------------
	// Define variables for costs in a days
	// -----------------------------------------------------------------------------------------------------------------
	if ( 0 !== calendar_params_arr.calendar_dates_rates.length ){														// In lower than BM versions this array is empty

		is_show_cost_in_tooltips 	= calendar_params_arr.calendar_dates_rates[ 'is_show_cost_in_tooltips' ];			// bool
		is_show_cost_in_date_cell 	= calendar_params_arr.calendar_dates_rates[ 'is_show_cost_in_date_cell' ];			// bool
		cost_curency 				= calendar_params_arr.calendar_dates_rates[ 'cost_curency' ];						// string : 'Cost: '
		wpbc_curency_symbol 		= calendar_params_arr.calendar_dates_rates[ 'wpbc_curency_symbol' ];				// string : '$'
		prices_per_day 				= calendar_params_arr.calendar_dates_rates[ 'prices_per_day' ];						// array [ 1: Object { "7-10-2023": "2 376.00", "7-11-2023":....
	}


	// -----------------------------------------------------------------------------------------------------------------
	// Define booked dates - mainly  for timeslots highlighting in popover
	// -----------------------------------------------------------------------------------------------------------------
	date_approved = [];
	date2approve  = [];
	_.each( calendar_params_arr.booked_dates, function ( booked_dates_val, booked_dates_key, booked_dates_data ){

			var class_day = booked_dates_key.split('-').map( function (e){
																return parseInt( e );
															} ).join('-');

			var td_class   = class_day;//( date.getMonth() + 1 ) + '-' + date.getDate() + '-' + date.getFullYear();
			    //class_day  = ( date.getMonth() + 1 ) + '-' + date.getDate() + '-' + date.getFullYear();						// '1-9-2023'

			// Is any bookings in this date ?
			if ( 'undefined' !== typeof( calendar_params_arr.booked_dates[ class_day ] ) ){

				var bookings_in_date = calendar_params_arr.booked_dates[ class_day ];

				var is_approved = true;

				_.each( bookings_in_date, function ( p_val, p_key, p_data ){
					if ( !parseInt( p_val.approved ) ){
						is_approved = false;
					}
					// p_val.booking_date = "2024-06-14 15:00:01"
					var booking_date   = p_val.booking_date.split(' ');											// ["2024-06-14", "15:00:01"]
					var booking_date_d = booking_date[0].split('-');											// ["2024", "06", "14"]
					booking_date_d = [ booking_date_d[ 1 ], booking_date_d[ 2 ], booking_date_d[ 0 ] ]; 		// [ "6", "14", "2024" ]
					var booking_date_h = booking_date[1].split(':');											// ["15", "00", "01"]

					booking_date_d = booking_date_d.map(function (e){
						return parseInt( e );
					});
					booking_date_h = booking_date_h.map(function (e){
						return parseInt( e );
					});

					booking_date = booking_date_d.concat( booking_date_h );												// [ 6, 14, 2024, 15, 0, 1 ]

					if ( is_approved ){
						if ( 'undefined' === typeof(date_approved[ calendar_params_arr.resource_id ]) ){ 			date_approved[ calendar_params_arr.resource_id ] = []; 			}
						if ( 'undefined' === typeof(date_approved[calendar_params_arr.resource_id][td_class]) ){ 	date_approved[calendar_params_arr.resource_id][td_class] = []; 	}
						date_approved[calendar_params_arr.resource_id][td_class].push( booking_date );
					} else {
						if ( 'undefined' === typeof(date2approve[ calendar_params_arr.resource_id ]) ){				date2approve[ calendar_params_arr.resource_id ] = [];			}
						if ( 'undefined' === typeof(date2approve[calendar_params_arr.resource_id][td_class]) ){		date2approve[calendar_params_arr.resource_id][td_class] = [];	}
						date2approve[calendar_params_arr.resource_id][td_class].push( booking_date );
					}

				});
			}
	});


	// -----------------------------------------------------------------------------------------------------------------
	// Unavailable Weekdays and other days
	// -----------------------------------------------------------------------------------------------------------------
	if ( undefined != calendar_params_arr[ 'calendar_unavailable' ] ){

		// Weekdays
		if ( '' === calendar_params_arr[ 'calendar_unavailable' ][ 'user_unavilable_days' ] ){
			user_unavilable_days = [];
		} else {
			user_unavilable_days = calendar_params_arr[ 'calendar_unavailable' ][ 'user_unavilable_days' ].split( ',' );
		}

		block_some_dates_from_today 	   = parseInt( calendar_params_arr[ 'calendar_unavailable' ][ 'block_some_dates_from_today' ] );
		wpbc_available_days_num_from_today = parseInt( calendar_params_arr[ 'calendar_unavailable' ][ 'wpbc_available_days_num_from_today' ] );
	}


	// -----------------------------------------------------------------------------------------------------------------
	// Additional data_info for bookings (showing for end times)
	// -----------------------------------------------------------------------------------------------------------------
	if ( 0 !== calendar_params_arr.calendar_dates_additional_info.length ){

		bk_show_info_in_form = true;
		dates_additional_info[ calendar_params_arr.resource_id ] = [];

		_.each( calendar_params_arr.calendar_dates_additional_info, function ( day_arr__seconds_titles_obj, day_tag, booked_dates_data ){

			_.each( day_arr__seconds_titles_obj, function ( booking_title, my_time_in_minutes, second_titles_obj ){

				if ( dates_additional_info[ calendar_params_arr.resource_id ][ day_tag ] == undefined ){
					dates_additional_info[ calendar_params_arr.resource_id ][ day_tag ] = [];
				}
				dates_additional_info[ calendar_params_arr.resource_id ][ day_tag ][ my_time_in_minutes ] = booking_title;
			} );

		} );
	} else {
		bk_show_info_in_form = false;
	}
*/
}


/**
 * 	Load Datepick Inline calendar
 *
 * @param calendar_params_arr		example:{
											'html_id'           : 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
											'text_id'           : 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,

											'calendar__booking_start_day_weeek': 	  calendar_params_arr.ajx_cleaned_params.calendar__booking_start_day_weeek,
											'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
											'calendar__days_selection_mode':  calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,

											'resource_id'        : calendar_params_arr.ajx_cleaned_params.resource_id,
											'ajx_nonce_calendar' : calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
											'booked_dates'       : calendar_params_arr.ajx_data_arr.calendar_settings.booked_dates,
											'season_customize_plugin': calendar_params_arr.ajx_data_arr.season_customize_plugin,

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
	//  JavaScript variables for front-end calendar
	//------------------------------------------------------------------------------------------------------------------
	wpbc_assign_global_js_for_calendar( calendar_params_arr );


	//------------------------------------------------------------------------------------------------------------------
	// Configure and show calendar
	//------------------------------------------------------------------------------------------------------------------
	jQuery( '#' + calendar_params_arr.html_id ).text( '' );
	jQuery( '#' + calendar_params_arr.html_id ).datepick({
					beforeShowDay: 	function ( date ){
										return wpbc__inline_booking_calendar__apply_css_to_days( date, calendar_params_arr, this );
									},
                    onSelect: 	  	function ( date ){
										jQuery( '#' + calendar_params_arr.text_id ).val( date );
										//wpbc_blink_element('.wpbc_widget_change_calendar_skin', 3, 220);
										return wpbc__inline_booking_calendar__on_days_select( date, calendar_params_arr, this );
									},
                    onHover: 		function ( value, date ){
										//wpbc_cstm__prepare_tooltip__in_calendar( value, date, calendar_params_arr, this );
										return wpbc__inline_booking_calendar__on_days_hover( value, date, calendar_params_arr, this );
									},
                    onChangeMonthYear:	//null,
										function ( year, month ){
											return wpbc__inline_booking_calendar__on_change_year_month( year, month, calendar_params_arr, this );
										},
                    showOn: 			'both',
                    numberOfMonths: 	calendar_params_arr.calendar__view__visible_months,
                    stepMonths:			1,
                    prevText: 			'&laquo;',
                    nextText: 			'&raquo;',
                    dateFormat: 		'dd.mm.yy',																		// 'yy-mm-dd',
                    changeMonth: 		false,
                    changeYear: 		false,
                    minDate: 			0,																				//null,  	// Scroll as long as you need
					maxDate: 			calendar_params_arr.calendar__booking_max_monthes_in_calendar,					// minDate: new Date(2020, 2, 1), maxDate: new Date(2020, 9, 31), 	// Ability to set any  start and end date in calendar
                    showStatus: 		false,
                    closeAtTop: 		false,
                    firstDay:			calendar_params_arr.calendar__booking_start_day_weeek,
                    gotoCurrent: 		false,
                    hideIfNoPrevNext:	true,
                    multiSeparator: 	', ',
					/*  'multiSelect' can  be 0   for 'single', 'dynamic'
					  			  and can  be 365 for 'multiple', 'fixed'
					  			  																						// Maximum number of selectable dates:	 Single day = 0,  multi days = 365
					 */
					multiSelect:  (
										   ( 'single'  == calendar_params_arr.calendar__days_selection_mode )
										|| ( 'dynamic' == calendar_params_arr.calendar__days_selection_mode )
									   ? 0
									   : 365
								  ),
					/*  'rangeSelect' true  for 'dynamic'
									  false for 'single', 'multiple', 'fixed'
					 */
					rangeSelect:  ('dynamic' == calendar_params_arr.calendar__days_selection_mode),
					rangeSeparator: ' - ', 																				//	' ~ ',	//' - ',
                    // showWeeks: true,
                    useThemeRoller:		false
                }
        );

	return  true;
}



	/**
	 * When  we scroll  month in calendar  then  trigger specific event
	 * @param year
	 * @param month
	 * @param calendar_params_arr
	 * @param datepick_this
	 */
	function wpbc__inline_booking_calendar__on_change_year_month( year, month, calendar_params_arr, datepick_this ){

		/**
		 *   We need to use inst.drawMonth  instead of month variable.
		 *   It is because,  each  time,  when we use dynamic arnge selection,  the month here are different
		 */

		var inst = jQuery.datepick._getInst( datepick_this );

		jQuery( 'body' ).trigger( 	  'wpbc__inline_booking_calendar__changed_year_month'													// event name
								 	, [inst.drawYear, (inst.drawMonth+1), calendar_params_arr, datepick_this]
								);
		// To catch this event: jQuery( 'body' ).on('wpbc__inline_booking_calendar__changed_year_month', function( event, year, month, calendar_params_arr, datepick_this ) { ... } );
	}

	/**
	 * Apply CSS to calendar date cells
	 *
	 * @param date					-  JavaScript Date Obj:  		Mon Dec 11 2023 00:00:00 GMT+0200 (Eastern European Standard Time)
	 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																	  "html_id": "calendar_booking4",
																	  "text_id": "date_booking4",
																	  "calendar__booking_start_day_weeek": 1,
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
																		'season_customize_plugin':{
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
		var sql_class_day = wpbc__get__sql_class_date( date );															// '2023-01-09'

		var css_date__standard   =  'cal4date-' + class_day;
		var css_date__additional = ' wpbc_weekday_' + date.getDay() + ' ';

		//--------------------------------------------------------------------------------------------------------------

		// WEEKDAYS :: Set unavailable week days from - Settings General page in "Availability" section
		for ( var i = 0; i < user_unavilable_days.length; i++ ){
			if ( date.getDay() == user_unavilable_days[ i ] ) {
				return [ false, css_date__standard + ' date_user_unavailable' 	+ ' weekdays_unavailable' ];
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
			return [ false, css_date__standard + ' date_user_unavailable' 		+ ' before_after_unavailable' ];
		}

		// SEASONS ::  					Booking > Resources > Availability page
		var    is_date_available = calendar_params_arr.season_customize_plugin[ sql_class_day ];
		if ( false === is_date_available ){																				//FixIn: 9.5.4.4
			return [ false, css_date__standard + ' date_user_unavailable'		+ ' season_unavailable' ];
		}

		// RESOURCE_UNAVAILABLE ::   	Booking > Customize page
		if ( wpdev_in_array(calendar_params_arr.resource_unavailable_dates, sql_class_day ) ){
			is_date_available = false;
		}
		if (  false === is_date_available ){																			//FixIn: 9.5.4.4
			return [ false, css_date__standard + ' date_user_unavailable'		+ ' resource_unavailable' ];
		}

		//--------------------------------------------------------------------------------------------------------------




		//--------------------------------------------------------------------------------------------------------------


		// Is any bookings in this date ?
		if ( 'undefined' !== typeof( calendar_params_arr.booked_dates[ class_day ] ) ) {

			var bookings_in_date = calendar_params_arr.booked_dates[ class_day ];


			if ( 'undefined' !== typeof( bookings_in_date[ 'sec_0' ] ) ) {			// "Full day" booking  -> (seconds == 0)

				css_date__additional += ( '0' === bookings_in_date[ 'sec_0' ].approved ) ? ' date2approve ' : ' date_approved ';				// Pending = '0' |  Approved = '1'
				css_date__additional += ' full_day_booking';

				return [ false, css_date__standard + css_date__additional ];

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

//TODO: need to  use wpbc_calendar script,  instead of this one
	/**
	 * Apply some CSS classes, when we mouse over specific dates in calendar
	 * @param value
	 * @param date					-  JavaScript Date Obj:  		Mon Dec 11 2023 00:00:00 GMT+0200 (Eastern European Standard Time)
	 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																	  "html_id": "calendar_booking4",
																	  "text_id": "date_booking4",
																	  "calendar__booking_start_day_weeek": 1,
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
																		'season_customize_plugin':{
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

					if( null === date ){
						return;
					}



					// The same functions as in client.css *************************************************************
					//TODO: 2023-06-30 17:22
					if ( true ){

						var bk_type = calendar_params_arr.resource_id



						var is_calendar_booking_unselectable = jQuery( '#calendar_booking_unselectable' + bk_type );				//FixIn: 8.0.1.2
						var is_booking_form_also = jQuery( '#booking_form_div' + bk_type );
						// Set unselectable,  if only Availability Calendar  here (and we do not insert Booking form by mistake).
						if ( (is_calendar_booking_unselectable.length == 1) && (is_booking_form_also.length != 1) ){
							jQuery( '#calendar_booking' + bk_type + ' .datepick-days-cell-over' ).removeClass( 'datepick-days-cell-over' );        // clear all highlight days selections
							jQuery( '.wpbc_only_calendar #calendar_booking' + bk_type + ' .datepick-days-cell, ' +
								'.wpbc_only_calendar #calendar_booking' + bk_type + ' .datepick-days-cell a' ).css( 'cursor', 'default' );
							return false;
						}																											//FixIn: 8.0.1.2	end

						return true;
					}
					// *************************************************************************************************





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

//TODO: need to  use wpbc_calendar script,  instead of this one

	/**
	 * On DAYs selection in calendar
	 *
	 * @param dates_selection		-  string:			 '2023-03-07 ~ 2023-03-07' or '2023-04-10, 2023-04-12, 2023-04-02, 2023-04-04'
	 * @param calendar_params_arr	-  Calendar Settings Object:  	{
																	  "html_id": "calendar_booking4",
																	  "text_id": "date_booking4",
																	  "calendar__booking_start_day_weeek": 1,
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
																		'season_customize_plugin':{
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


		// The same functions as in client.css			//TODO: 2023-06-30 17:22
		if ( true ){

			var bk_type = calendar_params_arr.resource_id
			var date = dates_selection;

			// Set unselectable,  if only Availability Calendar  here (and we do not insert Booking form by mistake).
			var is_calendar_booking_unselectable = jQuery( '#calendar_booking_unselectable' + bk_type );				//FixIn: 8.0.1.2
			var is_booking_form_also = jQuery( '#booking_form_div' + bk_type );

			if ( (is_calendar_booking_unselectable.length > 0) && (is_booking_form_also.length <= 0) ){

				wpbc_calendar__unselect_all_dates( bk_type );
				jQuery( '.wpbc_only_calendar .popover_calendar_hover' ).remove();                      					//Hide all opened popovers
				return false;
			}																											//FixIn: 8.0.1.2 end

			jQuery( '#date_booking' + bk_type ).val( date );




			jQuery( ".booking_form_div" ).trigger( "date_selected", [bk_type, date] );

		} else {

			// Functionality  from  Booking > Availability page

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
		}

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
			if (jQuery( '#ui_btn_cstm__set_days_customize_plugin__available').is(':checked')){
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
					if ( 'first_time' == jQuery( '.wpbc_ajx_customize_plugin_container' ).attr( 'wpbc_loaded' ) ){
						jQuery( '.wpbc_ajx_customize_plugin_container' ).attr( 'wpbc_loaded', 'done' )
						wpbc_blink_element( '.wpbc_widget_change_calendar_skin', 3, 220 );
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

			//message += ' <div style="margin-left: 1em;">' + ' Click on Apply button to apply customize_plugin.' + '</div>';

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
 * Scroll to  specific "Year & Month" 	in Inline Booking Calendar
 *
 * @param {number} resource_id		1
 * @param {number} year				2023
 * @param {number} month			12			(from 1 to  12)
 *
 * @returns {boolean}			// changed or not
 */
function wpbc__inline_booking_calendar__change_year_month( resource_id, year, month ){

	var inst = jQuery.datepick._getInst( document.getElementById( 'calendar_booking' + resource_id) );

	if ( false != inst ){

		year = parseInt( year );
		month = parseInt( month ) - 1;

		inst.cursorDate = new Date();
		inst.cursorDate.setFullYear( year, month, 1 );
		inst.cursorDate.setMonth( month );						// In some cases,  the setFullYear can  set  only Year,  and not the Month and day      //FixIn:6.2.3.5
		inst.cursorDate.setDate( 1 );

		inst.drawMonth = inst.cursorDate.getMonth();
		inst.drawYear  = inst.cursorDate.getFullYear();

		jQuery.datepick._notifyChange( inst );
		jQuery.datepick._adjustInstDate( inst );
		jQuery.datepick._showDate( inst );
		jQuery.datepick._updateDatepick( inst );

		return  true;
	}
	return  false;
}