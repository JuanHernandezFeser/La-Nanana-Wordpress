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

function wpbc_assign_global_js_for_calendar(calendar_params_arr) {//TODO: need to  test it before remove

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


function wpbc_show_inline_booking_calendar(calendar_params_arr) {
  if (0 === jQuery('#' + calendar_params_arr.html_id).length // If calendar DOM element not exist then exist
  || true === jQuery('#' + calendar_params_arr.html_id).hasClass('hasDatepick') // If the calendar with the same Booking resource already  has been activated, then exist.
  ) {
    return false;
  } //------------------------------------------------------------------------------------------------------------------
  //  JavaScript variables for front-end calendar
  //------------------------------------------------------------------------------------------------------------------


  wpbc_assign_global_js_for_calendar(calendar_params_arr); //------------------------------------------------------------------------------------------------------------------
  // Configure and show calendar
  //------------------------------------------------------------------------------------------------------------------

  jQuery('#' + calendar_params_arr.html_id).text('');
  jQuery('#' + calendar_params_arr.html_id).datepick({
    beforeShowDay: function beforeShowDay(date) {
      return wpbc__inline_booking_calendar__apply_css_to_days(date, calendar_params_arr, this);
    },
    onSelect: function onSelect(date) {
      jQuery('#' + calendar_params_arr.text_id).val(date); //wpbc_blink_element('.wpbc_widget_change_calendar_skin', 3, 220);

      return wpbc__inline_booking_calendar__on_days_select(date, calendar_params_arr, this);
    },
    onHover: function onHover(value, date) {
      //wpbc_cstm__prepare_tooltip__in_calendar( value, date, calendar_params_arr, this );
      return wpbc__inline_booking_calendar__on_days_hover(value, date, calendar_params_arr, this);
    },
    onChangeMonthYear: //null,
    function onChangeMonthYear(year, month) {
      return wpbc__inline_booking_calendar__on_change_year_month(year, month, calendar_params_arr, this);
    },
    showOn: 'both',
    numberOfMonths: calendar_params_arr.calendar__view__visible_months,
    stepMonths: 1,
    prevText: '&laquo;',
    nextText: '&raquo;',
    dateFormat: 'dd.mm.yy',
    // 'yy-mm-dd',
    changeMonth: false,
    changeYear: false,
    minDate: 0,
    //null,  	// Scroll as long as you need
    maxDate: calendar_params_arr.calendar__booking_max_monthes_in_calendar,
    // minDate: new Date(2020, 2, 1), maxDate: new Date(2020, 9, 31), 	// Ability to set any  start and end date in calendar
    showStatus: false,
    closeAtTop: false,
    firstDay: calendar_params_arr.calendar__booking_start_day_weeek,
    gotoCurrent: false,
    hideIfNoPrevNext: true,
    multiSeparator: ', ',

    /*  'multiSelect' can  be 0   for 'single', 'dynamic'
      			  and can  be 365 for 'multiple', 'fixed'
      			  																						// Maximum number of selectable dates:	 Single day = 0,  multi days = 365
     */
    multiSelect: 'single' == calendar_params_arr.calendar__days_selection_mode || 'dynamic' == calendar_params_arr.calendar__days_selection_mode ? 0 : 365,

    /*  'rangeSelect' true  for 'dynamic'
    				  false for 'single', 'multiple', 'fixed'
     */
    rangeSelect: 'dynamic' == calendar_params_arr.calendar__days_selection_mode,
    rangeSeparator: ' - ',
    //	' ~ ',	//' - ',
    // showWeeks: true,
    useThemeRoller: false
  });
  return true;
}
/**
 * When  we scroll  month in calendar  then  trigger specific event
 * @param year
 * @param month
 * @param calendar_params_arr
 * @param datepick_this
 */


function wpbc__inline_booking_calendar__on_change_year_month(year, month, calendar_params_arr, datepick_this) {
  /**
   *   We need to use inst.drawMonth  instead of month variable.
   *   It is because,  each  time,  when we use dynamic arnge selection,  the month here are different
   */
  var inst = jQuery.datepick._getInst(datepick_this);

  jQuery('body').trigger('wpbc__inline_booking_calendar__changed_year_month' // event name
  , [inst.drawYear, inst.drawMonth + 1, calendar_params_arr, datepick_this]); // To catch this event: jQuery( 'body' ).on('wpbc__inline_booking_calendar__changed_year_month', function( event, year, month, calendar_params_arr, datepick_this ) { ... } );
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


function wpbc__inline_booking_calendar__apply_css_to_days(date, calendar_params_arr, datepick_this) {
  var today_date = new Date(wpbc_today[0], parseInt(wpbc_today[1]) - 1, wpbc_today[2], 0, 0, 0);
  var class_day = date.getMonth() + 1 + '-' + date.getDate() + '-' + date.getFullYear(); // '1-9-2023'

  var sql_class_day = wpbc__get__sql_class_date(date); // '2023-01-09'

  var css_date__standard = 'cal4date-' + class_day;
  var css_date__additional = ' wpbc_weekday_' + date.getDay() + ' '; //--------------------------------------------------------------------------------------------------------------
  // WEEKDAYS :: Set unavailable week days from - Settings General page in "Availability" section

  for (var i = 0; i < user_unavilable_days.length; i++) {
    if (date.getDay() == user_unavilable_days[i]) {
      return [false, css_date__standard + ' date_user_unavailable' + ' weekdays_unavailable'];
    }
  } // BEFORE_AFTER :: Set unavailable days Before / After the Today date


  if (days_between(date, today_date) < block_some_dates_from_today || typeof wpbc_available_days_num_from_today !== 'undefined' && parseInt('0' + wpbc_available_days_num_from_today) > 0 && days_between(date, today_date) > parseInt('0' + wpbc_available_days_num_from_today)) {
    return [false, css_date__standard + ' date_user_unavailable' + ' before_after_unavailable'];
  } // SEASONS ::  					Booking > Resources > Availability page


  var is_date_available = calendar_params_arr.season_customize_plugin[sql_class_day];

  if (false === is_date_available) {
    //FixIn: 9.5.4.4
    return [false, css_date__standard + ' date_user_unavailable' + ' season_unavailable'];
  } // RESOURCE_UNAVAILABLE ::   	Booking > Customize page


  if (wpdev_in_array(calendar_params_arr.resource_unavailable_dates, sql_class_day)) {
    is_date_available = false;
  }

  if (false === is_date_available) {
    //FixIn: 9.5.4.4
    return [false, css_date__standard + ' date_user_unavailable' + ' resource_unavailable'];
  } //--------------------------------------------------------------------------------------------------------------
  //--------------------------------------------------------------------------------------------------------------
  // Is any bookings in this date ?


  if ('undefined' !== typeof calendar_params_arr.booked_dates[class_day]) {
    var bookings_in_date = calendar_params_arr.booked_dates[class_day];

    if ('undefined' !== typeof bookings_in_date['sec_0']) {
      // "Full day" booking  -> (seconds == 0)
      css_date__additional += '0' === bookings_in_date['sec_0'].approved ? ' date2approve ' : ' date_approved '; // Pending = '0' |  Approved = '1'

      css_date__additional += ' full_day_booking';
      return [false, css_date__standard + css_date__additional];
    } else if (Object.keys(bookings_in_date).length > 0) {
      // "Time slots" Bookings
      var is_approved = true;

      _.each(bookings_in_date, function (p_val, p_key, p_data) {
        if (!parseInt(p_val.approved)) {
          is_approved = false;
        }

        var ts = p_val.booking_date.substring(p_val.booking_date.length - 1);

        if (true === is_booking_used_check_in_out_time) {
          if (ts == '1') {
            css_date__additional += ' check_in_time' + (parseInt(p_val.approved) ? ' check_in_time_date_approved' : ' check_in_time_date2approve');
          }

          if (ts == '2') {
            css_date__additional += ' check_out_time' + (parseInt(p_val.approved) ? ' check_out_time_date_approved' : ' check_out_time_date2approve');
          }
        }
      });

      if (!is_approved) {
        css_date__additional += ' date2approve timespartly';
      } else {
        css_date__additional += ' date_approved timespartly';
      }

      if (!is_booking_used_check_in_out_time) {
        css_date__additional += ' times_clock';
      }
    }
  } //--------------------------------------------------------------------------------------------------------------


  return [true, css_date__standard + css_date__additional + ' date_available'];
} //TODO: need to  use wpbc_calendar script,  instead of this one

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


function wpbc__inline_booking_calendar__on_days_hover(value, date, calendar_params_arr, datepick_this) {
  if (null === date) {
    return;
  } // The same functions as in client.css *************************************************************
  //TODO: 2023-06-30 17:22


  if (true) {
    var bk_type = calendar_params_arr.resource_id;
    var is_calendar_booking_unselectable = jQuery('#calendar_booking_unselectable' + bk_type); //FixIn: 8.0.1.2

    var is_booking_form_also = jQuery('#booking_form_div' + bk_type); // Set unselectable,  if only Availability Calendar  here (and we do not insert Booking form by mistake).

    if (is_calendar_booking_unselectable.length == 1 && is_booking_form_also.length != 1) {
      jQuery('#calendar_booking' + bk_type + ' .datepick-days-cell-over').removeClass('datepick-days-cell-over'); // clear all highlight days selections

      jQuery('.wpbc_only_calendar #calendar_booking' + bk_type + ' .datepick-days-cell, ' + '.wpbc_only_calendar #calendar_booking' + bk_type + ' .datepick-days-cell a').css('cursor', 'default');
      return false;
    } //FixIn: 8.0.1.2	end


    return true;
  } // *************************************************************************************************


  if (null === date) {
    jQuery('.datepick-days-cell-over').removeClass('datepick-days-cell-over'); // clear all highlight days selections

    return false;
  }

  var inst = jQuery.datepick._getInst(document.getElementById('calendar_booking' + calendar_params_arr.resource_id));

  if (1 == inst.dates.length // If we have one selected date
  && 'dynamic' === calendar_params_arr.calendar__days_selection_mode // while have range days selection mode
  ) {
    var td_class;
    var td_overs = [];
    var is_check = true;
    var selceted_first_day = new Date();
    selceted_first_day.setFullYear(inst.dates[0].getFullYear(), inst.dates[0].getMonth(), inst.dates[0].getDate()); //Get first Date

    while (is_check) {
      td_class = selceted_first_day.getMonth() + 1 + '-' + selceted_first_day.getDate() + '-' + selceted_first_day.getFullYear();
      td_overs[td_overs.length] = '#calendar_booking' + calendar_params_arr.resource_id + ' .cal4date-' + td_class; // add to array for later make selection by class

      if (date.getMonth() == selceted_first_day.getMonth() && date.getDate() == selceted_first_day.getDate() && date.getFullYear() == selceted_first_day.getFullYear() || selceted_first_day > date) {
        is_check = false;
      }

      selceted_first_day.setFullYear(selceted_first_day.getFullYear(), selceted_first_day.getMonth(), selceted_first_day.getDate() + 1);
    } // Highlight Days


    for (var i = 0; i < td_overs.length; i++) {
      // add class to all elements
      jQuery(td_overs[i]).addClass('datepick-days-cell-over');
    }

    return true;
  }

  return true;
} //TODO: need to  use wpbc_calendar script,  instead of this one

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


function wpbc__inline_booking_calendar__on_days_select(dates_selection, calendar_params_arr) {
  var datepick_this = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

  // The same functions as in client.css			//TODO: 2023-06-30 17:22
  if (true) {
    var bk_type = calendar_params_arr.resource_id;
    var date = dates_selection; // Set unselectable,  if only Availability Calendar  here (and we do not insert Booking form by mistake).

    var is_calendar_booking_unselectable = jQuery('#calendar_booking_unselectable' + bk_type); //FixIn: 8.0.1.2

    var is_booking_form_also = jQuery('#booking_form_div' + bk_type);

    if (is_calendar_booking_unselectable.length > 0 && is_booking_form_also.length <= 0) {
      wpbc_calendar__unselect_all_dates(bk_type);
      jQuery('.wpbc_only_calendar .popover_calendar_hover').remove(); //Hide all opened popovers

      return false;
    } //FixIn: 8.0.1.2 end


    jQuery('#date_booking' + bk_type).val(date);
    jQuery(".booking_form_div").trigger("date_selected", [bk_type, date]);
  } else {
    // Functionality  from  Booking > Availability page
    var inst = jQuery.datepick._getInst(document.getElementById('calendar_booking' + calendar_params_arr.resource_id));

    var dates_arr = []; //  [ "2023-04-09", "2023-04-10", "2023-04-11" ]

    if (-1 !== dates_selection.indexOf('~')) {
      // Range Days
      dates_arr = wpbc_get_dates_arr__from_dates_range_js({
        'dates_separator': ' ~ ',
        //  ' ~ '
        'dates': dates_selection // '2023-04-04 ~ 2023-04-07'

      });
    } else {
      // Multiple Days
      dates_arr = wpbc_get_dates_arr__from_dates_comma_separated_js({
        'dates_separator': ', ',
        //  ', '
        'dates': dates_selection // '2023-04-10, 2023-04-12, 2023-04-02, 2023-04-04'

      });
    }

    wpbc_avy_after_days_selection__show_help_info({
      'calendar__days_selection_mode': calendar_params_arr.calendar__days_selection_mode,
      'dates_arr': dates_arr,
      'dates_click_num': inst.dates.length,
      'popover_hints': calendar_params_arr.popover_hints
    });
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


function wpbc_avy_after_days_selection__show_help_info(params) {
  // console.log( params );	//		[ "2023-04-09", "2023-04-10", "2023-04-11" ]
  var message, color;

  if (jQuery('#ui_btn_cstm__set_days_customize_plugin__available').is(':checked')) {
    message = params.popover_hints.toolbar_text_available; //'Set dates _DATES_ as _HTML_ available.';

    color = '#11be4c';
  } else {
    message = params.popover_hints.toolbar_text_unavailable; //'Set dates _DATES_ as _HTML_ unavailable.';

    color = '#e43939';
  }

  message = '<span>' + message + '</span>';
  var first_date = params['dates_arr'][0];
  var last_date = 'dynamic' == params.calendar__days_selection_mode ? params['dates_arr'][params['dates_arr'].length - 1] : params['dates_arr'].length > 1 ? params['dates_arr'][1] : '';
  first_date = jQuery.datepick.formatDate('dd M, yy', new Date(first_date + 'T00:00:00'));
  last_date = jQuery.datepick.formatDate('dd M, yy', new Date(last_date + 'T00:00:00'));

  if ('dynamic' == params.calendar__days_selection_mode) {
    if (1 == params.dates_click_num) {
      last_date = '___________';
    } else {
      if ('first_time' == jQuery('.wpbc_ajx_customize_plugin_container').attr('wpbc_loaded')) {
        jQuery('.wpbc_ajx_customize_plugin_container').attr('wpbc_loaded', 'done');
        wpbc_blink_element('.wpbc_widget_change_calendar_skin', 3, 220);
      }
    }

    message = message.replace('_DATES_', '</span>' //+ '<div>' + 'from' + '</div>'
    + '<span class="wpbc_big_date">' + first_date + '</span>' + '<span>' + '-' + '</span>' + '<span class="wpbc_big_date">' + last_date + '</span>' + '<span>');
  } else {
    // if ( params[ 'dates_arr' ].length > 1 ){
    // 	last_date = ', ' + last_date;
    // 	last_date += ( params[ 'dates_arr' ].length > 2 ) ? ', ...' : '';
    // } else {
    // 	last_date='';
    // }
    var dates_arr = [];

    for (var i = 0; i < params['dates_arr'].length; i++) {
      dates_arr.push(jQuery.datepick.formatDate('dd M yy', new Date(params['dates_arr'][i] + 'T00:00:00')));
    }

    first_date = dates_arr.join(', ');
    message = message.replace('_DATES_', '</span>' + '<span class="wpbc_big_date">' + first_date + '</span>' + '<span>');
  }

  message = message.replace('_HTML_', '</span><span class="wpbc_big_text" style="color:' + color + ';">') + '<span>'; //message += ' <div style="margin-left: 1em;">' + ' Click on Apply button to apply customize_plugin.' + '</div>';

  message = '<div class="wpbc_toolbar_dates_hints">' + message + '</div>';
  jQuery('.wpbc_help_text').html(message);
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


function wpbc_get_dates_arr__from_dates_comma_separated_js(params) {
  var dates_arr = [];

  if ('' !== params['dates']) {
    dates_arr = params['dates'].split(params['dates_separator']);
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


function wpbc_get_dates_arr__from_dates_range_js(params) {
  var dates_arr = [];

  if ('' !== params['dates']) {
    dates_arr = params['dates'].split(params['dates_separator']);
    var check_in_date_ymd = dates_arr[0];
    var check_out_date_ymd = dates_arr[1];

    if ('' !== check_in_date_ymd && '' !== check_out_date_ymd) {
      dates_arr = wpbc_get_dates_array_from_start_end_days_js(check_in_date_ymd, check_out_date_ymd);
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


function wpbc_get_dates_array_from_start_end_days_js(sStartDate, sEndDate) {
  sStartDate = new Date(sStartDate + 'T00:00:00');
  sEndDate = new Date(sEndDate + 'T00:00:00');
  var aDays = []; // Start the variable off with the start date

  aDays.push(sStartDate.getTime()); // Set a 'temp' variable, sCurrentDate, with the start date - before beginning the loop

  var sCurrentDate = new Date(sStartDate.getTime());
  var one_day_duration = 24 * 60 * 60 * 1000; // While the current date is less than the end date

  while (sCurrentDate < sEndDate) {
    // Add a day to the current date "+1 day"
    sCurrentDate.setTime(sCurrentDate.getTime() + one_day_duration); // Add this new day to the aDays array

    aDays.push(sCurrentDate.getTime());
  }

  for (var i = 0; i < aDays.length; i++) {
    aDays[i] = new Date(aDays[i]);
    aDays[i] = aDays[i].getFullYear() + '-' + (aDays[i].getMonth() + 1 < 10 ? '0' : '') + (aDays[i].getMonth() + 1) + '-' + (aDays[i].getDate() < 10 ? '0' : '') + aDays[i].getDate();
  } // Once the loop has finished, return the array of days.


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


function wpbc__inline_booking_calendar__change_year_month(resource_id, year, month) {
  var inst = jQuery.datepick._getInst(document.getElementById('calendar_booking' + resource_id));

  if (false != inst) {
    year = parseInt(year);
    month = parseInt(month) - 1;
    inst.cursorDate = new Date();
    inst.cursorDate.setFullYear(year, month, 1);
    inst.cursorDate.setMonth(month); // In some cases,  the setFullYear can  set  only Year,  and not the Month and day      //FixIn:6.2.3.5

    inst.cursorDate.setDate(1);
    inst.drawMonth = inst.cursorDate.getMonth();
    inst.drawYear = inst.cursorDate.getFullYear();

    jQuery.datepick._notifyChange(inst);

    jQuery.datepick._adjustInstDate(inst);

    jQuery.datepick._showDate(inst);

    jQuery.datepick._updateDatepick(inst);

    return true;
  }

  return false;
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImluY2x1ZGVzL19pbmxpbmVfY2FsZW5kYXJfanNfY3NzL19zcmMvd3BiY19pbmxpbmVfY2FsZW5kYXIuanMiXSwibmFtZXMiOlsid3BiY19hc3NpZ25fZ2xvYmFsX2pzX2Zvcl9jYWxlbmRhciIsImNhbGVuZGFyX3BhcmFtc19hcnIiLCJ3cGJjX3Nob3dfaW5saW5lX2Jvb2tpbmdfY2FsZW5kYXIiLCJqUXVlcnkiLCJodG1sX2lkIiwibGVuZ3RoIiwiaGFzQ2xhc3MiLCJ0ZXh0IiwiZGF0ZXBpY2siLCJiZWZvcmVTaG93RGF5IiwiZGF0ZSIsIndwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19hcHBseV9jc3NfdG9fZGF5cyIsIm9uU2VsZWN0IiwidGV4dF9pZCIsInZhbCIsIndwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19vbl9kYXlzX3NlbGVjdCIsIm9uSG92ZXIiLCJ2YWx1ZSIsIndwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19vbl9kYXlzX2hvdmVyIiwib25DaGFuZ2VNb250aFllYXIiLCJ5ZWFyIiwibW9udGgiLCJ3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fY2hhbmdlX3llYXJfbW9udGgiLCJzaG93T24iLCJudW1iZXJPZk1vbnRocyIsImNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRocyIsInN0ZXBNb250aHMiLCJwcmV2VGV4dCIsIm5leHRUZXh0IiwiZGF0ZUZvcm1hdCIsImNoYW5nZU1vbnRoIiwiY2hhbmdlWWVhciIsIm1pbkRhdGUiLCJtYXhEYXRlIiwiY2FsZW5kYXJfX2Jvb2tpbmdfbWF4X21vbnRoZXNfaW5fY2FsZW5kYXIiLCJzaG93U3RhdHVzIiwiY2xvc2VBdFRvcCIsImZpcnN0RGF5IiwiY2FsZW5kYXJfX2Jvb2tpbmdfc3RhcnRfZGF5X3dlZWVrIiwiZ290b0N1cnJlbnQiLCJoaWRlSWZOb1ByZXZOZXh0IiwibXVsdGlTZXBhcmF0b3IiLCJtdWx0aVNlbGVjdCIsImNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlIiwicmFuZ2VTZWxlY3QiLCJyYW5nZVNlcGFyYXRvciIsInVzZVRoZW1lUm9sbGVyIiwiZGF0ZXBpY2tfdGhpcyIsImluc3QiLCJfZ2V0SW5zdCIsInRyaWdnZXIiLCJkcmF3WWVhciIsImRyYXdNb250aCIsInRvZGF5X2RhdGUiLCJEYXRlIiwid3BiY190b2RheSIsInBhcnNlSW50IiwiY2xhc3NfZGF5IiwiZ2V0TW9udGgiLCJnZXREYXRlIiwiZ2V0RnVsbFllYXIiLCJzcWxfY2xhc3NfZGF5Iiwid3BiY19fZ2V0X19zcWxfY2xhc3NfZGF0ZSIsImNzc19kYXRlX19zdGFuZGFyZCIsImNzc19kYXRlX19hZGRpdGlvbmFsIiwiZ2V0RGF5IiwiaSIsInVzZXJfdW5hdmlsYWJsZV9kYXlzIiwiZGF5c19iZXR3ZWVuIiwiYmxvY2tfc29tZV9kYXRlc19mcm9tX3RvZGF5Iiwid3BiY19hdmFpbGFibGVfZGF5c19udW1fZnJvbV90b2RheSIsImlzX2RhdGVfYXZhaWxhYmxlIiwic2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4iLCJ3cGRldl9pbl9hcnJheSIsInJlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzIiwiYm9va2VkX2RhdGVzIiwiYm9va2luZ3NfaW5fZGF0ZSIsImFwcHJvdmVkIiwiT2JqZWN0Iiwia2V5cyIsImlzX2FwcHJvdmVkIiwiXyIsImVhY2giLCJwX3ZhbCIsInBfa2V5IiwicF9kYXRhIiwidHMiLCJib29raW5nX2RhdGUiLCJzdWJzdHJpbmciLCJpc19ib29raW5nX3VzZWRfY2hlY2tfaW5fb3V0X3RpbWUiLCJia190eXBlIiwicmVzb3VyY2VfaWQiLCJpc19jYWxlbmRhcl9ib29raW5nX3Vuc2VsZWN0YWJsZSIsImlzX2Jvb2tpbmdfZm9ybV9hbHNvIiwicmVtb3ZlQ2xhc3MiLCJjc3MiLCJkb2N1bWVudCIsImdldEVsZW1lbnRCeUlkIiwiZGF0ZXMiLCJ0ZF9jbGFzcyIsInRkX292ZXJzIiwiaXNfY2hlY2siLCJzZWxjZXRlZF9maXJzdF9kYXkiLCJzZXRGdWxsWWVhciIsImFkZENsYXNzIiwiZGF0ZXNfc2VsZWN0aW9uIiwid3BiY19jYWxlbmRhcl9fdW5zZWxlY3RfYWxsX2RhdGVzIiwicmVtb3ZlIiwiZGF0ZXNfYXJyIiwiaW5kZXhPZiIsIndwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19yYW5nZV9qcyIsIndwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19jb21tYV9zZXBhcmF0ZWRfanMiLCJ3cGJjX2F2eV9hZnRlcl9kYXlzX3NlbGVjdGlvbl9fc2hvd19oZWxwX2luZm8iLCJwb3BvdmVyX2hpbnRzIiwicGFyYW1zIiwibWVzc2FnZSIsImNvbG9yIiwiaXMiLCJ0b29sYmFyX3RleHRfYXZhaWxhYmxlIiwidG9vbGJhcl90ZXh0X3VuYXZhaWxhYmxlIiwiZmlyc3RfZGF0ZSIsImxhc3RfZGF0ZSIsImZvcm1hdERhdGUiLCJkYXRlc19jbGlja19udW0iLCJhdHRyIiwid3BiY19ibGlua19lbGVtZW50IiwicmVwbGFjZSIsInB1c2giLCJqb2luIiwiaHRtbCIsInNwbGl0Iiwic29ydCIsImNoZWNrX2luX2RhdGVfeW1kIiwiY2hlY2tfb3V0X2RhdGVfeW1kIiwid3BiY19nZXRfZGF0ZXNfYXJyYXlfZnJvbV9zdGFydF9lbmRfZGF5c19qcyIsInNTdGFydERhdGUiLCJzRW5kRGF0ZSIsImFEYXlzIiwiZ2V0VGltZSIsInNDdXJyZW50RGF0ZSIsIm9uZV9kYXlfZHVyYXRpb24iLCJzZXRUaW1lIiwid3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX2NoYW5nZV95ZWFyX21vbnRoIiwiY3Vyc29yRGF0ZSIsInNldE1vbnRoIiwic2V0RGF0ZSIsIl9ub3RpZnlDaGFuZ2UiLCJfYWRqdXN0SW5zdERhdGUiLCJfc2hvd0RhdGUiLCJfdXBkYXRlRGF0ZXBpY2siXSwibWFwcGluZ3MiOiJBQUFBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBQ0EsU0FBU0Esa0NBQVQsQ0FBNkNDLG1CQUE3QyxFQUFrRSxDQUNsRTs7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNDO0FBR0Q7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7O0FBQ0EsU0FBU0MsaUNBQVQsQ0FBNENELG1CQUE1QyxFQUFpRTtBQUVo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QUFDK0YsVUFBTCxDQUFnQkUsT0FBaEIsQ0FBeUIsQ0FBekI7QUFFQWpHLElBQUFBLElBQUksQ0FBQ0ksU0FBTCxHQUFpQkosSUFBSSxDQUFDK0YsVUFBTCxDQUFnQnJGLFFBQWhCLEVBQWpCO0FBQ0FWLElBQUFBLElBQUksQ0FBQ0csUUFBTCxHQUFpQkgsSUFBSSxDQUFDK0YsVUFBTCxDQUFnQm5GLFdBQWhCLEVBQWpCOztBQUVBekQsSUFBQUEsTUFBTSxDQUFDSyxRQUFQLENBQWdCMEksYUFBaEIsQ0FBK0JsRyxJQUEvQjs7QUFDQTdDLElBQUFBLE1BQU0sQ0FBQ0ssUUFBUCxDQUFnQjJJLGVBQWhCLENBQWlDbkcsSUFBakM7O0FBQ0E3QyxJQUFBQSxNQUFNLENBQUNLLFFBQVAsQ0FBZ0I0SSxTQUFoQixDQUEyQnBHLElBQTNCOztBQUNBN0MsSUFBQUEsTUFBTSxDQUFDSyxRQUFQLENBQWdCNkksZUFBaEIsQ0FBaUNyRyxJQUFqQzs7QUFFQSxXQUFRLElBQVI7QUFDQTs7QUFDRCxTQUFRLEtBQVI7QUFDQSIsInNvdXJjZXNDb250ZW50IjpbIlwidXNlIHN0cmljdFwiO1xyXG5cclxuLyoqXHJcbiAqIERlZmluZSBKYXZhU2NyaXB0IHZhcmlhYmxlcyBmb3IgZnJvbnQtZW5kIGNhbGVuZGFyIGZvciBiYWNrd2FyZCBjb21wYXRpYmlsaXR5XHJcbiAqXHJcbiAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyIGV4YW1wbGU6e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2h0bWxfaWQnICAgICAgICAgICA6ICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3RleHRfaWQnICAgICAgICAgICA6ICdkYXRlX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19ib29raW5nX3N0YXJ0X2RheV93ZWVlayc6IFx0ICBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fYm9va2luZ19zdGFydF9kYXlfd2VlZWssXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzJzogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlJzogIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlLFxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdyZXNvdXJjZV9pZCcgICAgICAgIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X25vbmNlX2NhbGVuZGFyJyA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLmFqeF9ub25jZV9jYWxlbmRhcixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29rZWRfZGF0ZXMnICAgICAgIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIuYm9va2VkX2RhdGVzLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXNvbl9jdXN0b21pemVfcGx1Z2luJzogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIuc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4sXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3Jlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzJyA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLnJlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hc3NpZ25fZ2xvYmFsX2pzX2Zvcl9jYWxlbmRhciggY2FsZW5kYXJfcGFyYW1zX2FyciApe1xyXG4vL1RPRE86IG5lZWQgdG8gIHRlc3QgaXQgYmVmb3JlIHJlbW92ZVxyXG4vKlxyXG5cdGlzX2Jvb2tpbmdfdXNlZF9jaGVja19pbl9vdXRfdGltZSA9ICgnT24nID09IGNhbGVuZGFyX3BhcmFtc19hcnIuYm9va2luZ19yYW5nZV9zZWxlY3Rpb25fdGltZV9pc19hY3RpdmUpID8gdHJ1ZSA6IGZhbHNlO1xyXG5cclxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8vIERhdGVzIEF2YWlsYWJpbGl0eSB2YXJpYWJsZXMgKHJlcXVpcmVkIGZvciBmcm9udC1lbmQgc2lkZSlcclxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdGlzX2FsbF9kYXlzX2F2YWlsYWJsZVsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdID0gdHJ1ZTtcdC8vdG9kbzpkZWxldGUgaXRcclxuXHRhdmFsYWliaWxpdHlfZmlsdGVyc1sgIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgXSA9IFtdO1x0XHQvL3RvZG86IGRlbGV0ZSBpdFxyXG5cclxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8vIERhdGVzIHNlbGVjdGlvblxyXG5cdC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0YmtfZGF5c19zZWxlY3Rpb25fbW9kZSA9IGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGU7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vICdzaW5nbGUnLCAnbXVsdGlwbGUnLCAnZml4ZWQnLCAnZHluYW1pYydcclxuXHJcblx0aWYgKCB0eXBlb2Ygd3BiY19nbG9iYWwzICE9PSAndW5kZWZpbmVkJyApe1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gQm9va2luZyBDYWxlbmRhciBCdXNpbmVzcyBTbWFsbCBvciBoaWdoZXIgdmVyc2lvbnNcclxuXHRcdGJrXzJjbGlja3NfbW9kZV9kYXlzX21pbiA9IGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX2JrXzJjbGlja3NfbW9kZV9kYXlzX21pbjsgIFx0XHRcdFx0XHRcdFx0Ly8gMTsgICAgXHQvLyBNaW5cclxuXHRcdGJrXzJjbGlja3NfbW9kZV9kYXlzX21heCA9IGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX2JrXzJjbGlja3NfbW9kZV9kYXlzX21heDtcdFx0XHRcdFx0XHRcdFx0Ly8gMzA7ICAgXHQvLyBNYXhcclxuXHRcdGJrXzJjbGlja3NfbW9kZV9kYXlzX3NwZWNpZmljID0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fYmtfMmNsaWNrc19tb2RlX2RheXNfc3BlY2lmaWMuc3BsaXQoICcsJyApLmZpbHRlciggZnVuY3Rpb24gKCB2ICl7IHJldHVybiB2ICE9PSAnJzsgfSApO1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIFs3LCAxNCwgMjFdOyAgIFx0Ly8gRXhhbXBsZSBbNSw3XVxyXG5cdFx0YmtfMmNsaWNrc19tb2RlX2RheXNfc3RhcnQgICAgPSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19ia18yY2xpY2tzX21vZGVfZGF5c19zdGFydC5zcGxpdCggJywnICkuZmlsdGVyKCBmdW5jdGlvbiAoIHYgKXsgcmV0dXJuIHYgIT09ICcnOyB9ICk7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gWzUsIDEsIDNdOyBcdC8vIHsgLTEgLSBBbnkgfCAwIC0gU3UsICAxIC0gTW8sICAyIC0gVHUsIDMgLSBXZSwgNCAtIFRoLCA1IC0gRnIsIDYgLSBTYXQgfVxyXG5cdFx0YmtfMWNsaWNrX21vZGVfZGF5c19udW0gICA9IGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX2JrXzFjbGlja19tb2RlX2RheXNfbnVtOyAgXHRcdFx0XHRcdFx0XHQvLyA3OyAgICBcdFx0Ly8gTnVtYmVyIG9mIGRheXMgc2VsZWN0aW9uIHdpdGggMSBtb3VzZSBjbGlja1xyXG5cdFx0YmtfMWNsaWNrX21vZGVfZGF5c19zdGFydCA9IGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX2JrXzFjbGlja19tb2RlX2RheXNfc3RhcnQuc3BsaXQoICcsJyApLmZpbHRlciggZnVuY3Rpb24gKCB2ICl7IHJldHVybiB2ICE9PSAnJzsgfSApOyAgXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gWy0xXTsgXHRcdC8vIHsgLTEgLSBBbnkgfCAwIC0gU3UsICAxIC0gTW8sICAyIC0gVHUsIDMgLSBXZSwgNCAtIFRoLCA1IC0gRnIsIDYgLSBTYXQgfVxyXG5cdH1cclxuXHRpZiAoIHR5cGVvZiB3cGJjX2dsb2JhbDQgIT09ICd1bmRlZmluZWQnICl7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyAgQm9va2luZyBDYWxlbmRhciBCdXNpbmVzcyBNZWRpdW0gb3IgaGlnaGVyIHZlcnNpb25zXHJcblx0XHRia18yY2xpY2tzX21vZGVfZGF5c19zZWxlY3Rpb25fX3NhdmVkX3ZhcmlhYmxlcyA9IFtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0YmtfMmNsaWNrc19tb2RlX2RheXNfc3BlY2lmaWMsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGJrXzJjbGlja3NfbW9kZV9kYXlzX21pbixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0YmtfMmNsaWNrc19tb2RlX2RheXNfbWF4LFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRia18xY2xpY2tfbW9kZV9kYXlzX251bVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBdO1xyXG5cdH1cclxuXHJcblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyBEZWZpbmUgdmFyaWFibGVzIGZvciBjb3N0cyBpbiBhIGRheXNcclxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdGlmICggMCAhPT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9kYXRlc19yYXRlcy5sZW5ndGggKXtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gSW4gbG93ZXIgdGhhbiBCTSB2ZXJzaW9ucyB0aGlzIGFycmF5IGlzIGVtcHR5XHJcblxyXG5cdFx0aXNfc2hvd19jb3N0X2luX3Rvb2x0aXBzIFx0PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX2RhdGVzX3JhdGVzWyAnaXNfc2hvd19jb3N0X2luX3Rvb2x0aXBzJyBdO1x0XHRcdC8vIGJvb2xcclxuXHRcdGlzX3Nob3dfY29zdF9pbl9kYXRlX2NlbGwgXHQ9IGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfZGF0ZXNfcmF0ZXNbICdpc19zaG93X2Nvc3RfaW5fZGF0ZV9jZWxsJyBdO1x0XHRcdC8vIGJvb2xcclxuXHRcdGNvc3RfY3VyZW5jeSBcdFx0XHRcdD0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9kYXRlc19yYXRlc1sgJ2Nvc3RfY3VyZW5jeScgXTtcdFx0XHRcdFx0XHQvLyBzdHJpbmcgOiAnQ29zdDogJ1xyXG5cdFx0d3BiY19jdXJlbmN5X3N5bWJvbCBcdFx0PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX2RhdGVzX3JhdGVzWyAnd3BiY19jdXJlbmN5X3N5bWJvbCcgXTtcdFx0XHRcdC8vIHN0cmluZyA6ICckJ1xyXG5cdFx0cHJpY2VzX3Blcl9kYXkgXHRcdFx0XHQ9IGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfZGF0ZXNfcmF0ZXNbICdwcmljZXNfcGVyX2RheScgXTtcdFx0XHRcdFx0XHQvLyBhcnJheSBbIDE6IE9iamVjdCB7IFwiNy0xMC0yMDIzXCI6IFwiMiAzNzYuMDBcIiwgXCI3LTExLTIwMjNcIjouLi4uXHJcblx0fVxyXG5cclxuXHJcblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyBEZWZpbmUgYm9va2VkIGRhdGVzIC0gbWFpbmx5ICBmb3IgdGltZXNsb3RzIGhpZ2hsaWdodGluZyBpbiBwb3BvdmVyXHJcblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRkYXRlX2FwcHJvdmVkID0gW107XHJcblx0ZGF0ZTJhcHByb3ZlICA9IFtdO1xyXG5cdF8uZWFjaCggY2FsZW5kYXJfcGFyYW1zX2Fyci5ib29rZWRfZGF0ZXMsIGZ1bmN0aW9uICggYm9va2VkX2RhdGVzX3ZhbCwgYm9va2VkX2RhdGVzX2tleSwgYm9va2VkX2RhdGVzX2RhdGEgKXtcclxuXHJcblx0XHRcdHZhciBjbGFzc19kYXkgPSBib29rZWRfZGF0ZXNfa2V5LnNwbGl0KCctJykubWFwKCBmdW5jdGlvbiAoZSl7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0cmV0dXJuIHBhcnNlSW50KCBlICk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0gKS5qb2luKCctJyk7XHJcblxyXG5cdFx0XHR2YXIgdGRfY2xhc3MgICA9IGNsYXNzX2RheTsvLyggZGF0ZS5nZXRNb250aCgpICsgMSApICsgJy0nICsgZGF0ZS5nZXREYXRlKCkgKyAnLScgKyBkYXRlLmdldEZ1bGxZZWFyKCk7XHJcblx0XHRcdCAgICAvL2NsYXNzX2RheSAgPSAoIGRhdGUuZ2V0TW9udGgoKSArIDEgKSArICctJyArIGRhdGUuZ2V0RGF0ZSgpICsgJy0nICsgZGF0ZS5nZXRGdWxsWWVhcigpO1x0XHRcdFx0XHRcdC8vICcxLTktMjAyMydcclxuXHJcblx0XHRcdC8vIElzIGFueSBib29raW5ncyBpbiB0aGlzIGRhdGUgP1xyXG5cdFx0XHRpZiAoICd1bmRlZmluZWQnICE9PSB0eXBlb2YoIGNhbGVuZGFyX3BhcmFtc19hcnIuYm9va2VkX2RhdGVzWyBjbGFzc19kYXkgXSApICl7XHJcblxyXG5cdFx0XHRcdHZhciBib29raW5nc19pbl9kYXRlID0gY2FsZW5kYXJfcGFyYW1zX2Fyci5ib29rZWRfZGF0ZXNbIGNsYXNzX2RheSBdO1xyXG5cclxuXHRcdFx0XHR2YXIgaXNfYXBwcm92ZWQgPSB0cnVlO1xyXG5cclxuXHRcdFx0XHRfLmVhY2goIGJvb2tpbmdzX2luX2RhdGUsIGZ1bmN0aW9uICggcF92YWwsIHBfa2V5LCBwX2RhdGEgKXtcclxuXHRcdFx0XHRcdGlmICggIXBhcnNlSW50KCBwX3ZhbC5hcHByb3ZlZCApICl7XHJcblx0XHRcdFx0XHRcdGlzX2FwcHJvdmVkID0gZmFsc2U7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHQvLyBwX3ZhbC5ib29raW5nX2RhdGUgPSBcIjIwMjQtMDYtMTQgMTU6MDA6MDFcIlxyXG5cdFx0XHRcdFx0dmFyIGJvb2tpbmdfZGF0ZSAgID0gcF92YWwuYm9va2luZ19kYXRlLnNwbGl0KCcgJyk7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIFtcIjIwMjQtMDYtMTRcIiwgXCIxNTowMDowMVwiXVxyXG5cdFx0XHRcdFx0dmFyIGJvb2tpbmdfZGF0ZV9kID0gYm9va2luZ19kYXRlWzBdLnNwbGl0KCctJyk7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIFtcIjIwMjRcIiwgXCIwNlwiLCBcIjE0XCJdXHJcblx0XHRcdFx0XHRib29raW5nX2RhdGVfZCA9IFsgYm9va2luZ19kYXRlX2RbIDEgXSwgYm9va2luZ19kYXRlX2RbIDIgXSwgYm9va2luZ19kYXRlX2RbIDAgXSBdOyBcdFx0Ly8gWyBcIjZcIiwgXCIxNFwiLCBcIjIwMjRcIiBdXHJcblx0XHRcdFx0XHR2YXIgYm9va2luZ19kYXRlX2ggPSBib29raW5nX2RhdGVbMV0uc3BsaXQoJzonKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gW1wiMTVcIiwgXCIwMFwiLCBcIjAxXCJdXHJcblxyXG5cdFx0XHRcdFx0Ym9va2luZ19kYXRlX2QgPSBib29raW5nX2RhdGVfZC5tYXAoZnVuY3Rpb24gKGUpe1xyXG5cdFx0XHRcdFx0XHRyZXR1cm4gcGFyc2VJbnQoIGUgKTtcclxuXHRcdFx0XHRcdH0pO1xyXG5cdFx0XHRcdFx0Ym9va2luZ19kYXRlX2ggPSBib29raW5nX2RhdGVfaC5tYXAoZnVuY3Rpb24gKGUpe1xyXG5cdFx0XHRcdFx0XHRyZXR1cm4gcGFyc2VJbnQoIGUgKTtcclxuXHRcdFx0XHRcdH0pO1xyXG5cclxuXHRcdFx0XHRcdGJvb2tpbmdfZGF0ZSA9IGJvb2tpbmdfZGF0ZV9kLmNvbmNhdCggYm9va2luZ19kYXRlX2ggKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBbIDYsIDE0LCAyMDI0LCAxNSwgMCwgMSBdXHJcblxyXG5cdFx0XHRcdFx0aWYgKCBpc19hcHByb3ZlZCApe1xyXG5cdFx0XHRcdFx0XHRpZiAoICd1bmRlZmluZWQnID09PSB0eXBlb2YoZGF0ZV9hcHByb3ZlZFsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdKSApeyBcdFx0XHRkYXRlX2FwcHJvdmVkWyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkIF0gPSBbXTsgXHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRpZiAoICd1bmRlZmluZWQnID09PSB0eXBlb2YoZGF0ZV9hcHByb3ZlZFtjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkXVt0ZF9jbGFzc10pICl7IFx0ZGF0ZV9hcHByb3ZlZFtjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkXVt0ZF9jbGFzc10gPSBbXTsgXHR9XHJcblx0XHRcdFx0XHRcdGRhdGVfYXBwcm92ZWRbY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZF1bdGRfY2xhc3NdLnB1c2goIGJvb2tpbmdfZGF0ZSApO1xyXG5cdFx0XHRcdFx0fSBlbHNlIHtcclxuXHRcdFx0XHRcdFx0aWYgKCAndW5kZWZpbmVkJyA9PT0gdHlwZW9mKGRhdGUyYXBwcm92ZVsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdKSApe1x0XHRcdFx0ZGF0ZTJhcHByb3ZlWyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkIF0gPSBbXTtcdFx0XHR9XHJcblx0XHRcdFx0XHRcdGlmICggJ3VuZGVmaW5lZCcgPT09IHR5cGVvZihkYXRlMmFwcHJvdmVbY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZF1bdGRfY2xhc3NdKSApe1x0XHRkYXRlMmFwcHJvdmVbY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZF1bdGRfY2xhc3NdID0gW107XHR9XHJcblx0XHRcdFx0XHRcdGRhdGUyYXBwcm92ZVtjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkXVt0ZF9jbGFzc10ucHVzaCggYm9va2luZ19kYXRlICk7XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdH0pO1xyXG5cdFx0XHR9XHJcblx0fSk7XHJcblxyXG5cclxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8vIFVuYXZhaWxhYmxlIFdlZWtkYXlzIGFuZCBvdGhlciBkYXlzXHJcblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHRpZiAoIHVuZGVmaW5lZCAhPSBjYWxlbmRhcl9wYXJhbXNfYXJyWyAnY2FsZW5kYXJfdW5hdmFpbGFibGUnIF0gKXtcclxuXHJcblx0XHQvLyBXZWVrZGF5c1xyXG5cdFx0aWYgKCAnJyA9PT0gY2FsZW5kYXJfcGFyYW1zX2FyclsgJ2NhbGVuZGFyX3VuYXZhaWxhYmxlJyBdWyAndXNlcl91bmF2aWxhYmxlX2RheXMnIF0gKXtcclxuXHRcdFx0dXNlcl91bmF2aWxhYmxlX2RheXMgPSBbXTtcclxuXHRcdH0gZWxzZSB7XHJcblx0XHRcdHVzZXJfdW5hdmlsYWJsZV9kYXlzID0gY2FsZW5kYXJfcGFyYW1zX2FyclsgJ2NhbGVuZGFyX3VuYXZhaWxhYmxlJyBdWyAndXNlcl91bmF2aWxhYmxlX2RheXMnIF0uc3BsaXQoICcsJyApO1xyXG5cdFx0fVxyXG5cclxuXHRcdGJsb2NrX3NvbWVfZGF0ZXNfZnJvbV90b2RheSBcdCAgID0gcGFyc2VJbnQoIGNhbGVuZGFyX3BhcmFtc19hcnJbICdjYWxlbmRhcl91bmF2YWlsYWJsZScgXVsgJ2Jsb2NrX3NvbWVfZGF0ZXNfZnJvbV90b2RheScgXSApO1xyXG5cdFx0d3BiY19hdmFpbGFibGVfZGF5c19udW1fZnJvbV90b2RheSA9IHBhcnNlSW50KCBjYWxlbmRhcl9wYXJhbXNfYXJyWyAnY2FsZW5kYXJfdW5hdmFpbGFibGUnIF1bICd3cGJjX2F2YWlsYWJsZV9kYXlzX251bV9mcm9tX3RvZGF5JyBdICk7XHJcblx0fVxyXG5cclxuXHJcblx0Ly8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyBBZGRpdGlvbmFsIGRhdGFfaW5mbyBmb3IgYm9va2luZ3MgKHNob3dpbmcgZm9yIGVuZCB0aW1lcylcclxuXHQvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdGlmICggMCAhPT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9kYXRlc19hZGRpdGlvbmFsX2luZm8ubGVuZ3RoICl7XHJcblxyXG5cdFx0Ymtfc2hvd19pbmZvX2luX2Zvcm0gPSB0cnVlO1xyXG5cdFx0ZGF0ZXNfYWRkaXRpb25hbF9pbmZvWyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkIF0gPSBbXTtcclxuXHJcblx0XHRfLmVhY2goIGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfZGF0ZXNfYWRkaXRpb25hbF9pbmZvLCBmdW5jdGlvbiAoIGRheV9hcnJfX3NlY29uZHNfdGl0bGVzX29iaiwgZGF5X3RhZywgYm9va2VkX2RhdGVzX2RhdGEgKXtcclxuXHJcblx0XHRcdF8uZWFjaCggZGF5X2Fycl9fc2Vjb25kc190aXRsZXNfb2JqLCBmdW5jdGlvbiAoIGJvb2tpbmdfdGl0bGUsIG15X3RpbWVfaW5fbWludXRlcywgc2Vjb25kX3RpdGxlc19vYmogKXtcclxuXHJcblx0XHRcdFx0aWYgKCBkYXRlc19hZGRpdGlvbmFsX2luZm9bIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgXVsgZGF5X3RhZyBdID09IHVuZGVmaW5lZCApe1xyXG5cdFx0XHRcdFx0ZGF0ZXNfYWRkaXRpb25hbF9pbmZvWyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkIF1bIGRheV90YWcgXSA9IFtdO1xyXG5cdFx0XHRcdH1cclxuXHRcdFx0XHRkYXRlc19hZGRpdGlvbmFsX2luZm9bIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgXVsgZGF5X3RhZyBdWyBteV90aW1lX2luX21pbnV0ZXMgXSA9IGJvb2tpbmdfdGl0bGU7XHJcblx0XHRcdH0gKTtcclxuXHJcblx0XHR9ICk7XHJcblx0fSBlbHNlIHtcclxuXHRcdGJrX3Nob3dfaW5mb19pbl9mb3JtID0gZmFsc2U7XHJcblx0fVxyXG4qL1xyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqIFx0TG9hZCBEYXRlcGljayBJbmxpbmUgY2FsZW5kYXJcclxuICpcclxuICogQHBhcmFtIGNhbGVuZGFyX3BhcmFtc19hcnJcdFx0ZXhhbXBsZTp7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnaHRtbF9pZCcgICAgICAgICAgIDogJ2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQndGV4dF9pZCcgICAgICAgICAgIDogJ2RhdGVfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX2Jvb2tpbmdfc3RhcnRfZGF5X3dlZWVrJzogXHQgIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX19ib29raW5nX3N0YXJ0X2RheV93ZWVlayxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdjYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHMnOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHMsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUnOiAgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUsXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3Jlc291cmNlX2lkJyAgICAgICAgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfbm9uY2VfY2FsZW5kYXInIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIuYWp4X25vbmNlX2NhbGVuZGFyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Jvb2tlZF9kYXRlcycgICAgICAgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5jYWxlbmRhcl9zZXR0aW5ncy5ib29rZWRfZGF0ZXMsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4nOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5zZWFzb25fY3VzdG9taXplX3BsdWdpbixcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXMnIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIucmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcbiAqIEByZXR1cm5zIHtib29sZWFufVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19zaG93X2lubGluZV9ib29raW5nX2NhbGVuZGFyKCBjYWxlbmRhcl9wYXJhbXNfYXJyICl7XHJcblxyXG5cdGlmIChcclxuXHRcdCAgICggMCA9PT0galF1ZXJ5KCAnIycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmh0bWxfaWQgKS5sZW5ndGggKVx0XHRcdFx0XHRcdFx0Ly8gSWYgY2FsZW5kYXIgRE9NIGVsZW1lbnQgbm90IGV4aXN0IHRoZW4gZXhpc3RcclxuXHRcdHx8ICggdHJ1ZSA9PT0galF1ZXJ5KCAnIycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmh0bWxfaWQgKS5oYXNDbGFzcyggJ2hhc0RhdGVwaWNrJyApIClcdC8vIElmIHRoZSBjYWxlbmRhciB3aXRoIHRoZSBzYW1lIEJvb2tpbmcgcmVzb3VyY2UgYWxyZWFkeSAgaGFzIGJlZW4gYWN0aXZhdGVkLCB0aGVuIGV4aXN0LlxyXG5cdCl7XHJcblx0ICAgcmV0dXJuIGZhbHNlO1xyXG5cdH1cclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyAgSmF2YVNjcmlwdCB2YXJpYWJsZXMgZm9yIGZyb250LWVuZCBjYWxlbmRhclxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0d3BiY19hc3NpZ25fZ2xvYmFsX2pzX2Zvcl9jYWxlbmRhciggY2FsZW5kYXJfcGFyYW1zX2FyciApO1xyXG5cclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvLyBDb25maWd1cmUgYW5kIHNob3cgY2FsZW5kYXJcclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdGpRdWVyeSggJyMnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5odG1sX2lkICkudGV4dCggJycgKTtcclxuXHRqUXVlcnkoICcjJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuaHRtbF9pZCApLmRhdGVwaWNrKHtcclxuXHRcdFx0XHRcdGJlZm9yZVNob3dEYXk6IFx0ZnVuY3Rpb24gKCBkYXRlICl7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0cmV0dXJuIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19hcHBseV9jc3NfdG9fZGF5cyggZGF0ZSwgY2FsZW5kYXJfcGFyYW1zX2FyciwgdGhpcyApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxyXG4gICAgICAgICAgICAgICAgICAgIG9uU2VsZWN0OiBcdCAgXHRmdW5jdGlvbiAoIGRhdGUgKXtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoICcjJyArIGNhbGVuZGFyX3BhcmFtc19hcnIudGV4dF9pZCApLnZhbCggZGF0ZSApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdC8vd3BiY19ibGlua19lbGVtZW50KCcud3BiY193aWRnZXRfY2hhbmdlX2NhbGVuZGFyX3NraW4nLCAzLCAyMjApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdHJldHVybiB3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19zZWxlY3QoIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIHRoaXMgKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcclxuICAgICAgICAgICAgICAgICAgICBvbkhvdmVyOiBcdFx0ZnVuY3Rpb24gKCB2YWx1ZSwgZGF0ZSApe1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdC8vd3BiY19jc3RtX19wcmVwYXJlX3Rvb2x0aXBfX2luX2NhbGVuZGFyKCB2YWx1ZSwgZGF0ZSwgY2FsZW5kYXJfcGFyYW1zX2FyciwgdGhpcyApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdHJldHVybiB3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19ob3ZlciggdmFsdWUsIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIHRoaXMgKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0fSxcclxuICAgICAgICAgICAgICAgICAgICBvbkNoYW5nZU1vbnRoWWVhcjpcdC8vbnVsbCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRmdW5jdGlvbiAoIHllYXIsIG1vbnRoICl7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRyZXR1cm4gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2NoYW5nZV95ZWFyX21vbnRoKCB5ZWFyLCBtb250aCwgY2FsZW5kYXJfcGFyYW1zX2FyciwgdGhpcyApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH0sXHJcbiAgICAgICAgICAgICAgICAgICAgc2hvd09uOiBcdFx0XHQnYm90aCcsXHJcbiAgICAgICAgICAgICAgICAgICAgbnVtYmVyT2ZNb250aHM6IFx0Y2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHMsXHJcbiAgICAgICAgICAgICAgICAgICAgc3RlcE1vbnRoczpcdFx0XHQxLFxyXG4gICAgICAgICAgICAgICAgICAgIHByZXZUZXh0OiBcdFx0XHQnJmxhcXVvOycsXHJcbiAgICAgICAgICAgICAgICAgICAgbmV4dFRleHQ6IFx0XHRcdCcmcmFxdW87JyxcclxuICAgICAgICAgICAgICAgICAgICBkYXRlRm9ybWF0OiBcdFx0J2RkLm1tLnl5JyxcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyAneXktbW0tZGQnLFxyXG4gICAgICAgICAgICAgICAgICAgIGNoYW5nZU1vbnRoOiBcdFx0ZmFsc2UsXHJcbiAgICAgICAgICAgICAgICAgICAgY2hhbmdlWWVhcjogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIG1pbkRhdGU6IFx0XHRcdDAsXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vbnVsbCwgIFx0Ly8gU2Nyb2xsIGFzIGxvbmcgYXMgeW91IG5lZWRcclxuXHRcdFx0XHRcdG1heERhdGU6IFx0XHRcdGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX2Jvb2tpbmdfbWF4X21vbnRoZXNfaW5fY2FsZW5kYXIsXHRcdFx0XHRcdC8vIG1pbkRhdGU6IG5ldyBEYXRlKDIwMjAsIDIsIDEpLCBtYXhEYXRlOiBuZXcgRGF0ZSgyMDIwLCA5LCAzMSksIFx0Ly8gQWJpbGl0eSB0byBzZXQgYW55ICBzdGFydCBhbmQgZW5kIGRhdGUgaW4gY2FsZW5kYXJcclxuICAgICAgICAgICAgICAgICAgICBzaG93U3RhdHVzOiBcdFx0ZmFsc2UsXHJcbiAgICAgICAgICAgICAgICAgICAgY2xvc2VBdFRvcDogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGZpcnN0RGF5Olx0XHRcdGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX2Jvb2tpbmdfc3RhcnRfZGF5X3dlZWVrLFxyXG4gICAgICAgICAgICAgICAgICAgIGdvdG9DdXJyZW50OiBcdFx0ZmFsc2UsXHJcbiAgICAgICAgICAgICAgICAgICAgaGlkZUlmTm9QcmV2TmV4dDpcdHRydWUsXHJcbiAgICAgICAgICAgICAgICAgICAgbXVsdGlTZXBhcmF0b3I6IFx0JywgJyxcclxuXHRcdFx0XHRcdC8qICAnbXVsdGlTZWxlY3QnIGNhbiAgYmUgMCAgIGZvciAnc2luZ2xlJywgJ2R5bmFtaWMnXHJcblx0XHRcdFx0XHQgIFx0XHRcdCAgYW5kIGNhbiAgYmUgMzY1IGZvciAnbXVsdGlwbGUnLCAnZml4ZWQnXHJcblx0XHRcdFx0XHQgIFx0XHRcdCAgXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBNYXhpbXVtIG51bWJlciBvZiBzZWxlY3RhYmxlIGRhdGVzOlx0IFNpbmdsZSBkYXkgPSAwLCAgbXVsdGkgZGF5cyA9IDM2NVxyXG5cdFx0XHRcdFx0ICovXHJcblx0XHRcdFx0XHRtdWx0aVNlbGVjdDogIChcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQgICAoICdzaW5nbGUnICA9PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlIClcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR8fCAoICdkeW5hbWljJyA9PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlIClcclxuXHRcdFx0XHRcdFx0XHRcdFx0ICAgPyAwXHJcblx0XHRcdFx0XHRcdFx0XHRcdCAgIDogMzY1XHJcblx0XHRcdFx0XHRcdFx0XHQgICksXHJcblx0XHRcdFx0XHQvKiAgJ3JhbmdlU2VsZWN0JyB0cnVlICBmb3IgJ2R5bmFtaWMnXHJcblx0XHRcdFx0XHRcdFx0XHRcdCAgZmFsc2UgZm9yICdzaW5nbGUnLCAnbXVsdGlwbGUnLCAnZml4ZWQnXHJcblx0XHRcdFx0XHQgKi9cclxuXHRcdFx0XHRcdHJhbmdlU2VsZWN0OiAgKCdkeW5hbWljJyA9PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlKSxcclxuXHRcdFx0XHRcdHJhbmdlU2VwYXJhdG9yOiAnIC0gJywgXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vXHQnIH4gJyxcdC8vJyAtICcsXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gc2hvd1dlZWtzOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgICAgIHVzZVRoZW1lUm9sbGVyOlx0XHRmYWxzZVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICk7XHJcblxyXG5cdHJldHVybiAgdHJ1ZTtcclxufVxyXG5cclxuXHJcblxyXG5cdC8qKlxyXG5cdCAqIFdoZW4gIHdlIHNjcm9sbCAgbW9udGggaW4gY2FsZW5kYXIgIHRoZW4gIHRyaWdnZXIgc3BlY2lmaWMgZXZlbnRcclxuXHQgKiBAcGFyYW0geWVhclxyXG5cdCAqIEBwYXJhbSBtb250aFxyXG5cdCAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHJcblx0ICogQHBhcmFtIGRhdGVwaWNrX3RoaXNcclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fY2hhbmdlX3llYXJfbW9udGgoIHllYXIsIG1vbnRoLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzICl7XHJcblxyXG5cdFx0LyoqXHJcblx0XHQgKiAgIFdlIG5lZWQgdG8gdXNlIGluc3QuZHJhd01vbnRoICBpbnN0ZWFkIG9mIG1vbnRoIHZhcmlhYmxlLlxyXG5cdFx0ICogICBJdCBpcyBiZWNhdXNlLCAgZWFjaCAgdGltZSwgIHdoZW4gd2UgdXNlIGR5bmFtaWMgYXJuZ2Ugc2VsZWN0aW9uLCAgdGhlIG1vbnRoIGhlcmUgYXJlIGRpZmZlcmVudFxyXG5cdFx0ICovXHJcblxyXG5cdFx0dmFyIGluc3QgPSBqUXVlcnkuZGF0ZXBpY2suX2dldEluc3QoIGRhdGVwaWNrX3RoaXMgKTtcclxuXHJcblx0XHRqUXVlcnkoICdib2R5JyApLnRyaWdnZXIoIFx0ICAnd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX2NoYW5nZWRfeWVhcl9tb250aCdcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIGV2ZW50IG5hbWVcclxuXHRcdFx0XHRcdFx0XHRcdCBcdCwgW2luc3QuZHJhd1llYXIsIChpbnN0LmRyYXdNb250aCsxKSwgY2FsZW5kYXJfcGFyYW1zX2FyciwgZGF0ZXBpY2tfdGhpc11cclxuXHRcdFx0XHRcdFx0XHRcdCk7XHJcblx0XHQvLyBUbyBjYXRjaCB0aGlzIGV2ZW50OiBqUXVlcnkoICdib2R5JyApLm9uKCd3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fY2hhbmdlZF95ZWFyX21vbnRoJywgZnVuY3Rpb24oIGV2ZW50LCB5ZWFyLCBtb250aCwgY2FsZW5kYXJfcGFyYW1zX2FyciwgZGF0ZXBpY2tfdGhpcyApIHsgLi4uIH0gKTtcclxuXHR9XHJcblxyXG5cdC8qKlxyXG5cdCAqIEFwcGx5IENTUyB0byBjYWxlbmRhciBkYXRlIGNlbGxzXHJcblx0ICpcclxuXHQgKiBAcGFyYW0gZGF0ZVx0XHRcdFx0XHQtICBKYXZhU2NyaXB0IERhdGUgT2JqOiAgXHRcdE1vbiBEZWMgMTEgMjAyMyAwMDowMDowMCBHTVQrMDIwMCAoRWFzdGVybiBFdXJvcGVhbiBTdGFuZGFyZCBUaW1lKVxyXG5cdCAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHQtICBDYWxlbmRhciBTZXR0aW5ncyBPYmplY3Q6ICBcdHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJodG1sX2lkXCI6IFwiY2FsZW5kYXJfYm9va2luZzRcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJ0ZXh0X2lkXCI6IFwiZGF0ZV9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX19ib29raW5nX3N0YXJ0X2RheV93ZWVla1wiOiAxLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRoc1wiOiAxMixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJyZXNvdXJjZV9pZFwiOiA0LFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImFqeF9ub25jZV9jYWxlbmRhclwiOiBcIjxpbnB1dCB0eXBlPVxcXCJoaWRkZW5cXFwiIC4uLiAvPlwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImJvb2tlZF9kYXRlc1wiOiB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMTItMjgtMjAyMlwiOiBbXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYm9va2luZ19kYXRlXCI6IFwiMjAyMi0xMi0yOCAwMDowMDowMFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYXBwcm92ZWRcIjogXCIxXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2lkXCI6IFwiMjZcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXNvbl9jdXN0b21pemVfcGx1Z2luJzp7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIyMDIzLTAxLTA5XCI6IHRydWUsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIyMDIzLTAxLTEwXCI6IHRydWUsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIyMDIzLTAxLTExXCI6IHRydWUsIC4uLlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHQgKiBAcGFyYW0gZGF0ZXBpY2tfdGhpc1x0XHRcdC0gdGhpcyBvZiBkYXRlcGljayBPYmpcclxuXHQgKlxyXG5cdCAqIEByZXR1cm5zIFtib29sZWFuLHN0cmluZ11cdC0gWyB7dHJ1ZSAtYXZhaWxhYmxlIHwgZmFsc2UgLSB1bmF2YWlsYWJsZX0sICdDU1MgY2xhc3NlcyBmb3IgY2FsZW5kYXIgZGF5IGNlbGwnIF1cclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fYXBwbHlfY3NzX3RvX2RheXMoIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIGRhdGVwaWNrX3RoaXMgKXtcclxuXHJcblx0XHR2YXIgdG9kYXlfZGF0ZSA9IG5ldyBEYXRlKCB3cGJjX3RvZGF5WyAwIF0sIChwYXJzZUludCggd3BiY190b2RheVsgMSBdICkgLSAxKSwgd3BiY190b2RheVsgMiBdLCAwLCAwLCAwICk7XHJcblxyXG5cdFx0dmFyIGNsYXNzX2RheSAgPSAoIGRhdGUuZ2V0TW9udGgoKSArIDEgKSArICctJyArIGRhdGUuZ2V0RGF0ZSgpICsgJy0nICsgZGF0ZS5nZXRGdWxsWWVhcigpO1x0XHRcdFx0XHRcdC8vICcxLTktMjAyMydcclxuXHRcdHZhciBzcWxfY2xhc3NfZGF5ID0gd3BiY19fZ2V0X19zcWxfY2xhc3NfZGF0ZSggZGF0ZSApO1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vICcyMDIzLTAxLTA5J1xyXG5cclxuXHRcdHZhciBjc3NfZGF0ZV9fc3RhbmRhcmQgICA9ICAnY2FsNGRhdGUtJyArIGNsYXNzX2RheTtcclxuXHRcdHZhciBjc3NfZGF0ZV9fYWRkaXRpb25hbCA9ICcgd3BiY193ZWVrZGF5XycgKyBkYXRlLmdldERheSgpICsgJyAnO1xyXG5cclxuXHRcdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHJcblx0XHQvLyBXRUVLREFZUyA6OiBTZXQgdW5hdmFpbGFibGUgd2VlayBkYXlzIGZyb20gLSBTZXR0aW5ncyBHZW5lcmFsIHBhZ2UgaW4gXCJBdmFpbGFiaWxpdHlcIiBzZWN0aW9uXHJcblx0XHRmb3IgKCB2YXIgaSA9IDA7IGkgPCB1c2VyX3VuYXZpbGFibGVfZGF5cy5sZW5ndGg7IGkrKyApe1xyXG5cdFx0XHRpZiAoIGRhdGUuZ2V0RGF5KCkgPT0gdXNlcl91bmF2aWxhYmxlX2RheXNbIGkgXSApIHtcclxuXHRcdFx0XHRyZXR1cm4gWyBmYWxzZSwgY3NzX2RhdGVfX3N0YW5kYXJkICsgJyBkYXRlX3VzZXJfdW5hdmFpbGFibGUnIFx0KyAnIHdlZWtkYXlzX3VuYXZhaWxhYmxlJyBdO1xyXG5cdFx0XHR9XHJcblx0XHR9XHJcblxyXG5cdFx0Ly8gQkVGT1JFX0FGVEVSIDo6IFNldCB1bmF2YWlsYWJsZSBkYXlzIEJlZm9yZSAvIEFmdGVyIHRoZSBUb2RheSBkYXRlXHJcblx0XHRpZiAoIFx0KCAoZGF5c19iZXR3ZWVuKCBkYXRlLCB0b2RheV9kYXRlICkpIDwgYmxvY2tfc29tZV9kYXRlc19mcm9tX3RvZGF5IClcclxuXHRcdFx0IHx8IChcclxuXHRcdFx0XHQgICAoIHR5cGVvZiggd3BiY19hdmFpbGFibGVfZGF5c19udW1fZnJvbV90b2RheSApICE9PSAndW5kZWZpbmVkJyApXHJcblx0XHRcdFx0JiYgKCBwYXJzZUludCggJzAnICsgd3BiY19hdmFpbGFibGVfZGF5c19udW1fZnJvbV90b2RheSApID4gMCApXHJcblx0XHRcdFx0JiYgKCBkYXlzX2JldHdlZW4oIGRhdGUsIHRvZGF5X2RhdGUgKSA+IHBhcnNlSW50KCAnMCcgKyB3cGJjX2F2YWlsYWJsZV9kYXlzX251bV9mcm9tX3RvZGF5ICkgKVxyXG5cdFx0XHRcdClcclxuXHRcdCl7XHJcblx0XHRcdHJldHVybiBbIGZhbHNlLCBjc3NfZGF0ZV9fc3RhbmRhcmQgKyAnIGRhdGVfdXNlcl91bmF2YWlsYWJsZScgXHRcdCsgJyBiZWZvcmVfYWZ0ZXJfdW5hdmFpbGFibGUnIF07XHJcblx0XHR9XHJcblxyXG5cdFx0Ly8gU0VBU09OUyA6OiAgXHRcdFx0XHRcdEJvb2tpbmcgPiBSZXNvdXJjZXMgPiBBdmFpbGFiaWxpdHkgcGFnZVxyXG5cdFx0dmFyICAgIGlzX2RhdGVfYXZhaWxhYmxlID0gY2FsZW5kYXJfcGFyYW1zX2Fyci5zZWFzb25fY3VzdG9taXplX3BsdWdpblsgc3FsX2NsYXNzX2RheSBdO1xyXG5cdFx0aWYgKCBmYWxzZSA9PT0gaXNfZGF0ZV9hdmFpbGFibGUgKXtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly9GaXhJbjogOS41LjQuNFxyXG5cdFx0XHRyZXR1cm4gWyBmYWxzZSwgY3NzX2RhdGVfX3N0YW5kYXJkICsgJyBkYXRlX3VzZXJfdW5hdmFpbGFibGUnXHRcdCsgJyBzZWFzb25fdW5hdmFpbGFibGUnIF07XHJcblx0XHR9XHJcblxyXG5cdFx0Ly8gUkVTT1VSQ0VfVU5BVkFJTEFCTEUgOjogICBcdEJvb2tpbmcgPiBDdXN0b21pemUgcGFnZVxyXG5cdFx0aWYgKCB3cGRldl9pbl9hcnJheShjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX3VuYXZhaWxhYmxlX2RhdGVzLCBzcWxfY2xhc3NfZGF5ICkgKXtcclxuXHRcdFx0aXNfZGF0ZV9hdmFpbGFibGUgPSBmYWxzZTtcclxuXHRcdH1cclxuXHRcdGlmICggIGZhbHNlID09PSBpc19kYXRlX2F2YWlsYWJsZSApe1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly9GaXhJbjogOS41LjQuNFxyXG5cdFx0XHRyZXR1cm4gWyBmYWxzZSwgY3NzX2RhdGVfX3N0YW5kYXJkICsgJyBkYXRlX3VzZXJfdW5hdmFpbGFibGUnXHRcdCsgJyByZXNvdXJjZV91bmF2YWlsYWJsZScgXTtcclxuXHRcdH1cclxuXHJcblx0XHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblxyXG5cclxuXHJcblxyXG5cdFx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cclxuXHJcblx0XHQvLyBJcyBhbnkgYm9va2luZ3MgaW4gdGhpcyBkYXRlID9cclxuXHRcdGlmICggJ3VuZGVmaW5lZCcgIT09IHR5cGVvZiggY2FsZW5kYXJfcGFyYW1zX2Fyci5ib29rZWRfZGF0ZXNbIGNsYXNzX2RheSBdICkgKSB7XHJcblxyXG5cdFx0XHR2YXIgYm9va2luZ3NfaW5fZGF0ZSA9IGNhbGVuZGFyX3BhcmFtc19hcnIuYm9va2VkX2RhdGVzWyBjbGFzc19kYXkgXTtcclxuXHJcblxyXG5cdFx0XHRpZiAoICd1bmRlZmluZWQnICE9PSB0eXBlb2YoIGJvb2tpbmdzX2luX2RhdGVbICdzZWNfMCcgXSApICkge1x0XHRcdC8vIFwiRnVsbCBkYXlcIiBib29raW5nICAtPiAoc2Vjb25kcyA9PSAwKVxyXG5cclxuXHRcdFx0XHRjc3NfZGF0ZV9fYWRkaXRpb25hbCArPSAoICcwJyA9PT0gYm9va2luZ3NfaW5fZGF0ZVsgJ3NlY18wJyBdLmFwcHJvdmVkICkgPyAnIGRhdGUyYXBwcm92ZSAnIDogJyBkYXRlX2FwcHJvdmVkICc7XHRcdFx0XHQvLyBQZW5kaW5nID0gJzAnIHwgIEFwcHJvdmVkID0gJzEnXHJcblx0XHRcdFx0Y3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gJyBmdWxsX2RheV9ib29raW5nJztcclxuXHJcblx0XHRcdFx0cmV0dXJuIFsgZmFsc2UsIGNzc19kYXRlX19zdGFuZGFyZCArIGNzc19kYXRlX19hZGRpdGlvbmFsIF07XHJcblxyXG5cdFx0XHR9IGVsc2UgaWYgKCBPYmplY3Qua2V5cyggYm9va2luZ3NfaW5fZGF0ZSApLmxlbmd0aCA+IDAgKXtcdFx0XHRcdC8vIFwiVGltZSBzbG90c1wiIEJvb2tpbmdzXHJcblxyXG5cdFx0XHRcdHZhciBpc19hcHByb3ZlZCA9IHRydWU7XHJcblxyXG5cdFx0XHRcdF8uZWFjaCggYm9va2luZ3NfaW5fZGF0ZSwgZnVuY3Rpb24gKCBwX3ZhbCwgcF9rZXksIHBfZGF0YSApIHtcclxuXHRcdFx0XHRcdGlmICggIXBhcnNlSW50KCBwX3ZhbC5hcHByb3ZlZCApICl7XHJcblx0XHRcdFx0XHRcdGlzX2FwcHJvdmVkID0gZmFsc2U7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHR2YXIgdHMgPSBwX3ZhbC5ib29raW5nX2RhdGUuc3Vic3RyaW5nKCBwX3ZhbC5ib29raW5nX2RhdGUubGVuZ3RoIC0gMSApO1xyXG5cdFx0XHRcdFx0aWYgKCB0cnVlID09PSBpc19ib29raW5nX3VzZWRfY2hlY2tfaW5fb3V0X3RpbWUgKXtcclxuXHRcdFx0XHRcdFx0aWYgKCB0cyA9PSAnMScgKSB7IGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgY2hlY2tfaW5fdGltZScgKyAoKHBhcnNlSW50KHBfdmFsLmFwcHJvdmVkKSkgPyAnIGNoZWNrX2luX3RpbWVfZGF0ZV9hcHByb3ZlZCcgOiAnIGNoZWNrX2luX3RpbWVfZGF0ZTJhcHByb3ZlJyk7IH1cclxuXHRcdFx0XHRcdFx0aWYgKCB0cyA9PSAnMicgKSB7IGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgY2hlY2tfb3V0X3RpbWUnICsgKChwYXJzZUludChwX3ZhbC5hcHByb3ZlZCkpID8gJyBjaGVja19vdXRfdGltZV9kYXRlX2FwcHJvdmVkJyA6ICcgY2hlY2tfb3V0X3RpbWVfZGF0ZTJhcHByb3ZlJyk7IH1cclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0fSk7XHJcblxyXG5cdFx0XHRcdGlmICggISBpc19hcHByb3ZlZCApe1xyXG5cdFx0XHRcdFx0Y3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gJyBkYXRlMmFwcHJvdmUgdGltZXNwYXJ0bHknXHJcblx0XHRcdFx0fSBlbHNlIHtcclxuXHRcdFx0XHRcdGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgZGF0ZV9hcHByb3ZlZCB0aW1lc3BhcnRseSdcclxuXHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdGlmICggISBpc19ib29raW5nX3VzZWRfY2hlY2tfaW5fb3V0X3RpbWUgKXtcclxuXHRcdFx0XHRcdGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgdGltZXNfY2xvY2snXHJcblx0XHRcdFx0fVxyXG5cclxuXHRcdFx0fVxyXG5cclxuXHRcdH1cclxuXHJcblx0XHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblxyXG5cdFx0cmV0dXJuIFsgdHJ1ZSwgY3NzX2RhdGVfX3N0YW5kYXJkICsgY3NzX2RhdGVfX2FkZGl0aW9uYWwgKyAnIGRhdGVfYXZhaWxhYmxlJyBdO1xyXG5cdH1cclxuXHJcbi8vVE9ETzogbmVlZCB0byAgdXNlIHdwYmNfY2FsZW5kYXIgc2NyaXB0LCAgaW5zdGVhZCBvZiB0aGlzIG9uZVxyXG5cdC8qKlxyXG5cdCAqIEFwcGx5IHNvbWUgQ1NTIGNsYXNzZXMsIHdoZW4gd2UgbW91c2Ugb3ZlciBzcGVjaWZpYyBkYXRlcyBpbiBjYWxlbmRhclxyXG5cdCAqIEBwYXJhbSB2YWx1ZVxyXG5cdCAqIEBwYXJhbSBkYXRlXHRcdFx0XHRcdC0gIEphdmFTY3JpcHQgRGF0ZSBPYmo6ICBcdFx0TW9uIERlYyAxMSAyMDIzIDAwOjAwOjAwIEdNVCswMjAwIChFYXN0ZXJuIEV1cm9wZWFuIFN0YW5kYXJkIFRpbWUpXHJcblx0ICogQHBhcmFtIGNhbGVuZGFyX3BhcmFtc19hcnJcdC0gIENhbGVuZGFyIFNldHRpbmdzIE9iamVjdDogIFx0e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImh0bWxfaWRcIjogXCJjYWxlbmRhcl9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInRleHRfaWRcIjogXCJkYXRlX2Jvb2tpbmc0XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX2Jvb2tpbmdfc3RhcnRfZGF5X3dlZWVrXCI6IDEsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzXCI6IDEyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInJlc291cmNlX2lkXCI6IDQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYWp4X25vbmNlX2NhbGVuZGFyXCI6IFwiPGlucHV0IHR5cGU9XFxcImhpZGRlblxcXCIgLi4uIC8+XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYm9va2VkX2RhdGVzXCI6IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIxMi0yOC0yMDIyXCI6IFtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2RhdGVcIjogXCIyMDIyLTEyLTI4IDAwOjAwOjAwXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJhcHByb3ZlZFwiOiBcIjFcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImJvb2tpbmdfaWRcIjogXCIyNlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRdLCAuLi5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4nOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMDlcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTBcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTFcIjogdHJ1ZSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHRcdFx0LSB0aGlzIG9mIGRhdGVwaWNrIE9ialxyXG5cdCAqXHJcblx0ICogQHJldHVybnMge2Jvb2xlYW59XHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfaG92ZXIoIHZhbHVlLCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzICl7XHJcblxyXG5cdFx0XHRcdFx0aWYoIG51bGwgPT09IGRhdGUgKXtcclxuXHRcdFx0XHRcdFx0cmV0dXJuO1xyXG5cdFx0XHRcdFx0fVxyXG5cclxuXHJcblxyXG5cdFx0XHRcdFx0Ly8gVGhlIHNhbWUgZnVuY3Rpb25zIGFzIGluIGNsaWVudC5jc3MgKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKlxyXG5cdFx0XHRcdFx0Ly9UT0RPOiAyMDIzLTA2LTMwIDE3OjIyXHJcblx0XHRcdFx0XHRpZiAoIHRydWUgKXtcclxuXHJcblx0XHRcdFx0XHRcdHZhciBia190eXBlID0gY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZFxyXG5cclxuXHJcblxyXG5cdFx0XHRcdFx0XHR2YXIgaXNfY2FsZW5kYXJfYm9va2luZ191bnNlbGVjdGFibGUgPSBqUXVlcnkoICcjY2FsZW5kYXJfYm9va2luZ191bnNlbGVjdGFibGUnICsgYmtfdHlwZSApO1x0XHRcdFx0Ly9GaXhJbjogOC4wLjEuMlxyXG5cdFx0XHRcdFx0XHR2YXIgaXNfYm9va2luZ19mb3JtX2Fsc28gPSBqUXVlcnkoICcjYm9va2luZ19mb3JtX2RpdicgKyBia190eXBlICk7XHJcblx0XHRcdFx0XHRcdC8vIFNldCB1bnNlbGVjdGFibGUsICBpZiBvbmx5IEF2YWlsYWJpbGl0eSBDYWxlbmRhciAgaGVyZSAoYW5kIHdlIGRvIG5vdCBpbnNlcnQgQm9va2luZyBmb3JtIGJ5IG1pc3Rha2UpLlxyXG5cdFx0XHRcdFx0XHRpZiAoIChpc19jYWxlbmRhcl9ib29raW5nX3Vuc2VsZWN0YWJsZS5sZW5ndGggPT0gMSkgJiYgKGlzX2Jvb2tpbmdfZm9ybV9hbHNvLmxlbmd0aCAhPSAxKSApe1xyXG5cdFx0XHRcdFx0XHRcdGpRdWVyeSggJyNjYWxlbmRhcl9ib29raW5nJyArIGJrX3R5cGUgKyAnIC5kYXRlcGljay1kYXlzLWNlbGwtb3ZlcicgKS5yZW1vdmVDbGFzcyggJ2RhdGVwaWNrLWRheXMtY2VsbC1vdmVyJyApOyAgICAgICAgLy8gY2xlYXIgYWxsIGhpZ2hsaWdodCBkYXlzIHNlbGVjdGlvbnNcclxuXHRcdFx0XHRcdFx0XHRqUXVlcnkoICcud3BiY19vbmx5X2NhbGVuZGFyICNjYWxlbmRhcl9ib29raW5nJyArIGJrX3R5cGUgKyAnIC5kYXRlcGljay1kYXlzLWNlbGwsICcgK1xyXG5cdFx0XHRcdFx0XHRcdFx0Jy53cGJjX29ubHlfY2FsZW5kYXIgI2NhbGVuZGFyX2Jvb2tpbmcnICsgYmtfdHlwZSArICcgLmRhdGVwaWNrLWRheXMtY2VsbCBhJyApLmNzcyggJ2N1cnNvcicsICdkZWZhdWx0JyApO1xyXG5cdFx0XHRcdFx0XHRcdHJldHVybiBmYWxzZTtcclxuXHRcdFx0XHRcdFx0fVx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vRml4SW46IDguMC4xLjJcdGVuZFxyXG5cclxuXHRcdFx0XHRcdFx0cmV0dXJuIHRydWU7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHQvLyAqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqKioqXHJcblxyXG5cclxuXHJcblxyXG5cclxuXHRcdGlmICggbnVsbCA9PT0gZGF0ZSApe1xyXG5cdFx0XHRqUXVlcnkoICcuZGF0ZXBpY2stZGF5cy1jZWxsLW92ZXInICkucmVtb3ZlQ2xhc3MoICdkYXRlcGljay1kYXlzLWNlbGwtb3ZlcicgKTsgICBcdCAgICAgICAgICAgICAgICAgICAgICAgIC8vIGNsZWFyIGFsbCBoaWdobGlnaHQgZGF5cyBzZWxlY3Rpb25zXHJcblx0XHRcdHJldHVybiBmYWxzZTtcclxuXHRcdH1cclxuXHJcblx0XHR2YXIgaW5zdCA9IGpRdWVyeS5kYXRlcGljay5fZ2V0SW5zdCggZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgKSApO1xyXG5cclxuXHRcdGlmIChcclxuXHRcdFx0ICAgKCAxID09IGluc3QuZGF0ZXMubGVuZ3RoKVx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIElmIHdlIGhhdmUgb25lIHNlbGVjdGVkIGRhdGVcclxuXHRcdFx0JiYgKCdkeW5hbWljJyA9PT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSkgXHRcdFx0XHRcdC8vIHdoaWxlIGhhdmUgcmFuZ2UgZGF5cyBzZWxlY3Rpb24gbW9kZVxyXG5cdFx0KXtcclxuXHJcblx0XHRcdHZhciB0ZF9jbGFzcztcclxuXHRcdFx0dmFyIHRkX292ZXJzID0gW107XHJcblx0XHRcdHZhciBpc19jaGVjayA9IHRydWU7XHJcbiAgICAgICAgICAgIHZhciBzZWxjZXRlZF9maXJzdF9kYXkgPSBuZXcgRGF0ZSgpO1xyXG4gICAgICAgICAgICBzZWxjZXRlZF9maXJzdF9kYXkuc2V0RnVsbFllYXIoaW5zdC5kYXRlc1swXS5nZXRGdWxsWWVhcigpLChpbnN0LmRhdGVzWzBdLmdldE1vbnRoKCkpLCAoaW5zdC5kYXRlc1swXS5nZXREYXRlKCkgKSApOyAvL0dldCBmaXJzdCBEYXRlXHJcblxyXG4gICAgICAgICAgICB3aGlsZSggIGlzX2NoZWNrICl7XHJcblxyXG5cdFx0XHRcdHRkX2NsYXNzID0gKHNlbGNldGVkX2ZpcnN0X2RheS5nZXRNb250aCgpICsgMSkgKyAnLScgKyBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RGF0ZSgpICsgJy0nICsgc2VsY2V0ZWRfZmlyc3RfZGF5LmdldEZ1bGxZZWFyKCk7XHJcblxyXG5cdFx0XHRcdHRkX292ZXJzWyB0ZF9vdmVycy5sZW5ndGggXSA9ICcjY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJyAuY2FsNGRhdGUtJyArIHRkX2NsYXNzOyAgICAgICAgICAgICAgLy8gYWRkIHRvIGFycmF5IGZvciBsYXRlciBtYWtlIHNlbGVjdGlvbiBieSBjbGFzc1xyXG5cclxuICAgICAgICAgICAgICAgIGlmIChcclxuXHRcdFx0XHRcdCggICggZGF0ZS5nZXRNb250aCgpID09IHNlbGNldGVkX2ZpcnN0X2RheS5nZXRNb250aCgpICkgICYmXHJcbiAgICAgICAgICAgICAgICAgICAgICAgKCBkYXRlLmdldERhdGUoKSA9PSBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RGF0ZSgpICkgICYmXHJcbiAgICAgICAgICAgICAgICAgICAgICAgKCBkYXRlLmdldEZ1bGxZZWFyKCkgPT0gc2VsY2V0ZWRfZmlyc3RfZGF5LmdldEZ1bGxZZWFyKCkgKVxyXG5cdFx0XHRcdFx0KSB8fCAoIHNlbGNldGVkX2ZpcnN0X2RheSA+IGRhdGUgKVxyXG5cdFx0XHRcdCl7XHJcblx0XHRcdFx0XHRpc19jaGVjayA9ICBmYWxzZTtcclxuXHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdHNlbGNldGVkX2ZpcnN0X2RheS5zZXRGdWxsWWVhciggc2VsY2V0ZWRfZmlyc3RfZGF5LmdldEZ1bGxZZWFyKCksIChzZWxjZXRlZF9maXJzdF9kYXkuZ2V0TW9udGgoKSksIChzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RGF0ZSgpICsgMSkgKTtcclxuXHRcdFx0fVxyXG5cclxuXHRcdFx0Ly8gSGlnaGxpZ2h0IERheXNcclxuXHRcdFx0Zm9yICggdmFyIGk9MDsgaSA8IHRkX292ZXJzLmxlbmd0aCA7IGkrKykgeyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBhZGQgY2xhc3MgdG8gYWxsIGVsZW1lbnRzXHJcblx0XHRcdFx0alF1ZXJ5KCB0ZF9vdmVyc1tpXSApLmFkZENsYXNzKCdkYXRlcGljay1kYXlzLWNlbGwtb3ZlcicpO1xyXG5cdFx0XHR9XHJcblx0XHRcdHJldHVybiB0cnVlO1xyXG5cclxuXHRcdH1cclxuXHJcblx0ICAgIHJldHVybiB0cnVlO1xyXG5cdH1cclxuXHJcbi8vVE9ETzogbmVlZCB0byAgdXNlIHdwYmNfY2FsZW5kYXIgc2NyaXB0LCAgaW5zdGVhZCBvZiB0aGlzIG9uZVxyXG5cclxuXHQvKipcclxuXHQgKiBPbiBEQVlzIHNlbGVjdGlvbiBpbiBjYWxlbmRhclxyXG5cdCAqXHJcblx0ICogQHBhcmFtIGRhdGVzX3NlbGVjdGlvblx0XHQtICBzdHJpbmc6XHRcdFx0ICcyMDIzLTAzLTA3IH4gMjAyMy0wMy0wNycgb3IgJzIwMjMtMDQtMTAsIDIwMjMtMDQtMTIsIDIwMjMtMDQtMDIsIDIwMjMtMDQtMDQnXHJcblx0ICogQHBhcmFtIGNhbGVuZGFyX3BhcmFtc19hcnJcdC0gIENhbGVuZGFyIFNldHRpbmdzIE9iamVjdDogIFx0e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImh0bWxfaWRcIjogXCJjYWxlbmRhcl9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInRleHRfaWRcIjogXCJkYXRlX2Jvb2tpbmc0XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX2Jvb2tpbmdfc3RhcnRfZGF5X3dlZWVrXCI6IDEsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzXCI6IDEyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInJlc291cmNlX2lkXCI6IDQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYWp4X25vbmNlX2NhbGVuZGFyXCI6IFwiPGlucHV0IHR5cGU9XFxcImhpZGRlblxcXCIgLi4uIC8+XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYm9va2VkX2RhdGVzXCI6IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIxMi0yOC0yMDIyXCI6IFtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2RhdGVcIjogXCIyMDIyLTEyLTI4IDAwOjAwOjAwXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJhcHByb3ZlZFwiOiBcIjFcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImJvb2tpbmdfaWRcIjogXCIyNlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRdLCAuLi5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2Vhc29uX2N1c3RvbWl6ZV9wbHVnaW4nOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMDlcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTBcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTFcIjogdHJ1ZSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHRcdFx0LSB0aGlzIG9mIGRhdGVwaWNrIE9ialxyXG5cdCAqXHJcblx0ICogQHJldHVybnMgYm9vbGVhblxyXG5cdCAqL1xyXG5cdGZ1bmN0aW9uIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19vbl9kYXlzX3NlbGVjdCggZGF0ZXNfc2VsZWN0aW9uLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzID0gbnVsbCApe1xyXG5cclxuXHJcblx0XHQvLyBUaGUgc2FtZSBmdW5jdGlvbnMgYXMgaW4gY2xpZW50LmNzc1x0XHRcdC8vVE9ETzogMjAyMy0wNi0zMCAxNzoyMlxyXG5cdFx0aWYgKCB0cnVlICl7XHJcblxyXG5cdFx0XHR2YXIgYmtfdHlwZSA9IGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWRcclxuXHRcdFx0dmFyIGRhdGUgPSBkYXRlc19zZWxlY3Rpb247XHJcblxyXG5cdFx0XHQvLyBTZXQgdW5zZWxlY3RhYmxlLCAgaWYgb25seSBBdmFpbGFiaWxpdHkgQ2FsZW5kYXIgIGhlcmUgKGFuZCB3ZSBkbyBub3QgaW5zZXJ0IEJvb2tpbmcgZm9ybSBieSBtaXN0YWtlKS5cclxuXHRcdFx0dmFyIGlzX2NhbGVuZGFyX2Jvb2tpbmdfdW5zZWxlY3RhYmxlID0galF1ZXJ5KCAnI2NhbGVuZGFyX2Jvb2tpbmdfdW5zZWxlY3RhYmxlJyArIGJrX3R5cGUgKTtcdFx0XHRcdC8vRml4SW46IDguMC4xLjJcclxuXHRcdFx0dmFyIGlzX2Jvb2tpbmdfZm9ybV9hbHNvID0galF1ZXJ5KCAnI2Jvb2tpbmdfZm9ybV9kaXYnICsgYmtfdHlwZSApO1xyXG5cclxuXHRcdFx0aWYgKCAoaXNfY2FsZW5kYXJfYm9va2luZ191bnNlbGVjdGFibGUubGVuZ3RoID4gMCkgJiYgKGlzX2Jvb2tpbmdfZm9ybV9hbHNvLmxlbmd0aCA8PSAwKSApe1xyXG5cclxuXHRcdFx0XHR3cGJjX2NhbGVuZGFyX191bnNlbGVjdF9hbGxfZGF0ZXMoIGJrX3R5cGUgKTtcclxuXHRcdFx0XHRqUXVlcnkoICcud3BiY19vbmx5X2NhbGVuZGFyIC5wb3BvdmVyX2NhbGVuZGFyX2hvdmVyJyApLnJlbW92ZSgpOyAgICAgICAgICAgICAgICAgICAgICBcdFx0XHRcdFx0Ly9IaWRlIGFsbCBvcGVuZWQgcG9wb3ZlcnNcclxuXHRcdFx0XHRyZXR1cm4gZmFsc2U7XHJcblx0XHRcdH1cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL0ZpeEluOiA4LjAuMS4yIGVuZFxyXG5cclxuXHRcdFx0alF1ZXJ5KCAnI2RhdGVfYm9va2luZycgKyBia190eXBlICkudmFsKCBkYXRlICk7XHJcblxyXG5cclxuXHJcblxyXG5cdFx0XHRqUXVlcnkoIFwiLmJvb2tpbmdfZm9ybV9kaXZcIiApLnRyaWdnZXIoIFwiZGF0ZV9zZWxlY3RlZFwiLCBbYmtfdHlwZSwgZGF0ZV0gKTtcclxuXHJcblx0XHR9IGVsc2Uge1xyXG5cclxuXHRcdFx0Ly8gRnVuY3Rpb25hbGl0eSAgZnJvbSAgQm9va2luZyA+IEF2YWlsYWJpbGl0eSBwYWdlXHJcblxyXG5cdFx0XHR2YXIgaW5zdCA9IGpRdWVyeS5kYXRlcGljay5fZ2V0SW5zdCggZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgKSApO1xyXG5cclxuXHRcdFx0dmFyIGRhdGVzX2FyciA9IFtdO1x0Ly8gIFsgXCIyMDIzLTA0LTA5XCIsIFwiMjAyMy0wNC0xMFwiLCBcIjIwMjMtMDQtMTFcIiBdXHJcblxyXG5cdFx0XHRpZiAoIC0xICE9PSBkYXRlc19zZWxlY3Rpb24uaW5kZXhPZiggJ34nICkgKSB7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIFJhbmdlIERheXNcclxuXHJcblx0XHRcdFx0ZGF0ZXNfYXJyID0gd3BiY19nZXRfZGF0ZXNfYXJyX19mcm9tX2RhdGVzX3JhbmdlX2pzKCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19zZXBhcmF0b3InIDogJyB+ICcsICAgICAgICAgICAgICAgICAgICAgICAgIC8vICAnIH4gJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnZGF0ZXMnICAgICAgICAgICA6IGRhdGVzX3NlbGVjdGlvbiwgICAgXHRcdCAgIC8vICcyMDIzLTA0LTA0IH4gMjAyMy0wNC0wNydcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHJcblx0XHRcdH0gZWxzZSB7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBNdWx0aXBsZSBEYXlzXHJcblx0XHRcdFx0ZGF0ZXNfYXJyID0gd3BiY19nZXRfZGF0ZXNfYXJyX19mcm9tX2RhdGVzX2NvbW1hX3NlcGFyYXRlZF9qcygge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnZGF0ZXNfc2VwYXJhdG9yJyA6ICcsICcsICAgICAgICAgICAgICAgICAgICAgICAgIFx0Ly8gICcsICdcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2RhdGVzJyAgICAgICAgICAgOiBkYXRlc19zZWxlY3Rpb24sICAgIFx0XHRcdC8vICcyMDIzLTA0LTEwLCAyMDIzLTA0LTEyLCAyMDIzLTA0LTAyLCAyMDIzLTA0LTA0J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHR3cGJjX2F2eV9hZnRlcl9kYXlzX3NlbGVjdGlvbl9fc2hvd19oZWxwX2luZm8oe1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUnOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnZGF0ZXNfYXJyJyAgICAgICAgICAgICAgICAgICAgOiBkYXRlc19hcnIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19jbGlja19udW0nICAgICAgICAgICAgICA6IGluc3QuZGF0ZXMubGVuZ3RoLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncG9wb3Zlcl9oaW50cydcdFx0XHRcdFx0OiBjYWxlbmRhcl9wYXJhbXNfYXJyLnBvcG92ZXJfaGludHNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHRcdH1cclxuXHJcblx0XHRyZXR1cm4gdHJ1ZTtcclxuXHJcblx0fVxyXG5cclxuXHJcblx0XHQvKipcclxuXHRcdCAqIFNob3cgaGVscCBpbmZvIGF0IHRoZSB0b3AgIHRvb2xiYXIgYWJvdXQgc2VsZWN0ZWQgZGF0ZXMgYW5kIGZ1dHVyZSBhY3Rpb25zXHJcblx0XHQgKlxyXG5cdFx0ICogQHBhcmFtIHBhcmFtc1xyXG5cdFx0ICogXHRcdFx0XHRcdEV4YW1wbGUgMTogIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlOiBcImR5bmFtaWNcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRhdGVzX2FycjogIFsgXCIyMDIzLTA0LTAzXCIgXSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRhdGVzX2NsaWNrX251bTogMVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3BvcG92ZXJfaGludHMnXHRcdFx0XHRcdDogY2FsZW5kYXJfcGFyYW1zX2Fyci5wb3BvdmVyX2hpbnRzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0ICogXHRcdFx0XHRcdEV4YW1wbGUgMjogIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlOiBcImR5bmFtaWNcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfYXJyOiBBcnJheSgxMCkgWyBcIjIwMjMtMDQtMDNcIiwgXCIyMDIzLTA0LTA0XCIsIFwiMjAyMy0wNC0wNVwiLCDigKYgXVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfY2xpY2tfbnVtOiAyXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncG9wb3Zlcl9oaW50cydcdFx0XHRcdFx0OiBjYWxlbmRhcl9wYXJhbXNfYXJyLnBvcG92ZXJfaGludHNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHQgKi9cclxuXHRcdGZ1bmN0aW9uIHdwYmNfYXZ5X2FmdGVyX2RheXNfc2VsZWN0aW9uX19zaG93X2hlbHBfaW5mbyggcGFyYW1zICl7XHJcbi8vIGNvbnNvbGUubG9nKCBwYXJhbXMgKTtcdC8vXHRcdFsgXCIyMDIzLTA0LTA5XCIsIFwiMjAyMy0wNC0xMFwiLCBcIjIwMjMtMDQtMTFcIiBdXHJcblxyXG5cdFx0XHR2YXIgbWVzc2FnZSwgY29sb3I7XHJcblx0XHRcdGlmIChqUXVlcnkoICcjdWlfYnRuX2NzdG1fX3NldF9kYXlzX2N1c3RvbWl6ZV9wbHVnaW5fX2F2YWlsYWJsZScpLmlzKCc6Y2hlY2tlZCcpKXtcclxuXHRcdFx0XHQgbWVzc2FnZSA9IHBhcmFtcy5wb3BvdmVyX2hpbnRzLnRvb2xiYXJfdGV4dF9hdmFpbGFibGU7Ly8nU2V0IGRhdGVzIF9EQVRFU18gYXMgX0hUTUxfIGF2YWlsYWJsZS4nO1xyXG5cdFx0XHRcdCBjb2xvciA9ICcjMTFiZTRjJztcclxuXHRcdFx0fSBlbHNlIHtcclxuXHRcdFx0XHRtZXNzYWdlID0gcGFyYW1zLnBvcG92ZXJfaGludHMudG9vbGJhcl90ZXh0X3VuYXZhaWxhYmxlOy8vJ1NldCBkYXRlcyBfREFURVNfIGFzIF9IVE1MXyB1bmF2YWlsYWJsZS4nO1xyXG5cdFx0XHRcdGNvbG9yID0gJyNlNDM5MzknO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHRtZXNzYWdlID0gJzxzcGFuPicgKyBtZXNzYWdlICsgJzwvc3Bhbj4nO1xyXG5cclxuXHRcdFx0dmFyIGZpcnN0X2RhdGUgPSBwYXJhbXNbICdkYXRlc19hcnInIF1bIDAgXTtcclxuXHRcdFx0dmFyIGxhc3RfZGF0ZSAgPSAoICdkeW5hbWljJyA9PSBwYXJhbXMuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUgKVxyXG5cdFx0XHRcdFx0XHRcdD8gcGFyYW1zWyAnZGF0ZXNfYXJyJyBdWyAocGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCAtIDEpIF1cclxuXHRcdFx0XHRcdFx0XHQ6ICggcGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCA+IDEgKSA/IHBhcmFtc1sgJ2RhdGVzX2FycicgXVsgMSBdIDogJyc7XHJcblxyXG5cdFx0XHRmaXJzdF9kYXRlID0galF1ZXJ5LmRhdGVwaWNrLmZvcm1hdERhdGUoICdkZCBNLCB5eScsIG5ldyBEYXRlKCBmaXJzdF9kYXRlICsgJ1QwMDowMDowMCcgKSApO1xyXG5cdFx0XHRsYXN0X2RhdGUgPSBqUXVlcnkuZGF0ZXBpY2suZm9ybWF0RGF0ZSggJ2RkIE0sIHl5JywgIG5ldyBEYXRlKCBsYXN0X2RhdGUgKyAnVDAwOjAwOjAwJyApICk7XHJcblxyXG5cclxuXHRcdFx0aWYgKCAnZHluYW1pYycgPT0gcGFyYW1zLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlICl7XHJcblx0XHRcdFx0aWYgKCAxID09IHBhcmFtcy5kYXRlc19jbGlja19udW0gKXtcclxuXHRcdFx0XHRcdGxhc3RfZGF0ZSA9ICdfX19fX19fX19fXydcclxuXHRcdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdFx0aWYgKCAnZmlyc3RfdGltZScgPT0galF1ZXJ5KCAnLndwYmNfYWp4X2N1c3RvbWl6ZV9wbHVnaW5fY29udGFpbmVyJyApLmF0dHIoICd3cGJjX2xvYWRlZCcgKSApe1xyXG5cdFx0XHRcdFx0XHRqUXVlcnkoICcud3BiY19hanhfY3VzdG9taXplX3BsdWdpbl9jb250YWluZXInICkuYXR0ciggJ3dwYmNfbG9hZGVkJywgJ2RvbmUnIClcclxuXHRcdFx0XHRcdFx0d3BiY19ibGlua19lbGVtZW50KCAnLndwYmNfd2lkZ2V0X2NoYW5nZV9jYWxlbmRhcl9za2luJywgMywgMjIwICk7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0fVxyXG5cdFx0XHRcdG1lc3NhZ2UgPSBtZXNzYWdlLnJlcGxhY2UoICdfREFURVNfJywgICAgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLysgJzxkaXY+JyArICdmcm9tJyArICc8L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3BhbiBjbGFzcz1cIndwYmNfYmlnX2RhdGVcIj4nICsgZmlyc3RfZGF0ZSArICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4+JyArICctJyArICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4gY2xhc3M9XCJ3cGJjX2JpZ19kYXRlXCI+JyArIGxhc3RfZGF0ZSArICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4+JyApO1xyXG5cdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdC8vIGlmICggcGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCA+IDEgKXtcclxuXHRcdFx0XHQvLyBcdGxhc3RfZGF0ZSA9ICcsICcgKyBsYXN0X2RhdGU7XHJcblx0XHRcdFx0Ly8gXHRsYXN0X2RhdGUgKz0gKCBwYXJhbXNbICdkYXRlc19hcnInIF0ubGVuZ3RoID4gMiApID8gJywgLi4uJyA6ICcnO1xyXG5cdFx0XHRcdC8vIH0gZWxzZSB7XHJcblx0XHRcdFx0Ly8gXHRsYXN0X2RhdGU9Jyc7XHJcblx0XHRcdFx0Ly8gfVxyXG5cdFx0XHRcdHZhciBkYXRlc19hcnIgPSBbXTtcclxuXHRcdFx0XHRmb3IoIHZhciBpID0gMDsgaSA8IHBhcmFtc1sgJ2RhdGVzX2FycicgXS5sZW5ndGg7IGkrKyApe1xyXG5cdFx0XHRcdFx0ZGF0ZXNfYXJyLnB1c2goICBqUXVlcnkuZGF0ZXBpY2suZm9ybWF0RGF0ZSggJ2RkIE0geXknLCAgbmV3IERhdGUoIHBhcmFtc1sgJ2RhdGVzX2FycicgXVsgaSBdICsgJ1QwMDowMDowMCcgKSApICApO1xyXG5cdFx0XHRcdH1cclxuXHRcdFx0XHRmaXJzdF9kYXRlID0gZGF0ZXNfYXJyLmpvaW4oICcsICcgKTtcclxuXHRcdFx0XHRtZXNzYWdlID0gbWVzc2FnZS5yZXBsYWNlKCAnX0RBVEVTXycsICAgICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4gY2xhc3M9XCJ3cGJjX2JpZ19kYXRlXCI+JyArIGZpcnN0X2RhdGUgKyAnPC9zcGFuPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCsgJzxzcGFuPicgKTtcclxuXHRcdFx0fVxyXG5cdFx0XHRtZXNzYWdlID0gbWVzc2FnZS5yZXBsYWNlKCAnX0hUTUxfJyAsICc8L3NwYW4+PHNwYW4gY2xhc3M9XCJ3cGJjX2JpZ190ZXh0XCIgc3R5bGU9XCJjb2xvcjonK2NvbG9yKyc7XCI+JykgKyAnPHNwYW4+JztcclxuXHJcblx0XHRcdC8vbWVzc2FnZSArPSAnIDxkaXYgc3R5bGU9XCJtYXJnaW4tbGVmdDogMWVtO1wiPicgKyAnIENsaWNrIG9uIEFwcGx5IGJ1dHRvbiB0byBhcHBseSBjdXN0b21pemVfcGx1Z2luLicgKyAnPC9kaXY+JztcclxuXHJcblx0XHRcdG1lc3NhZ2UgPSAnPGRpdiBjbGFzcz1cIndwYmNfdG9vbGJhcl9kYXRlc19oaW50c1wiPicgKyBtZXNzYWdlICsgJzwvZGl2Pic7XHJcblxyXG5cdFx0XHRqUXVlcnkoICcud3BiY19oZWxwX3RleHQnICkuaHRtbChcdG1lc3NhZ2UgKTtcclxuXHRcdH1cclxuXHJcblx0LyoqXHJcblx0ICogICBQYXJzZSBkYXRlcyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuXHRcdC8qKlxyXG5cdFx0ICogR2V0IGRhdGVzIGFycmF5LCAgZnJvbSBjb21tYSBzZXBhcmF0ZWQgZGF0ZXNcclxuXHRcdCAqXHJcblx0XHQgKiBAcGFyYW0gcGFyYW1zICAgICAgID0ge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiAnZGF0ZXNfc2VwYXJhdG9yJyA9PiAnLCAnLCAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBEYXRlcyBzZXBhcmF0b3JcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogJ2RhdGVzJyAgICAgICAgICAgPT4gJzIwMjMtMDQtMDQsIDIwMjMtMDQtMDcsIDIwMjMtMDQtMDUnICAgICAgICAgLy8gRGF0ZXMgaW4gJ1ktbS1kJyBmb3JtYXQ6ICcyMDIzLTAxLTMxJ1xyXG5cdFx0XHRcdFx0XHRcdFx0IH1cclxuXHRcdCAqXHJcblx0XHQgKiBAcmV0dXJuIGFycmF5ICAgICAgPSBbXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqIFswXSA9PiAyMDIzLTA0LTA0XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqIFsxXSA9PiAyMDIzLTA0LTA1XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqIFsyXSA9PiAyMDIzLTA0LTA2XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqIFszXSA9PiAyMDIzLTA0LTA3XHJcblx0XHRcdFx0XHRcdFx0XHRdXHJcblx0XHQgKlxyXG5cdFx0ICogRXhhbXBsZSAjMTogIHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19jb21tYV9zZXBhcmF0ZWRfanMoICB7ICAnZGF0ZXNfc2VwYXJhdG9yJyA6ICcsICcsICdkYXRlcycgOiAnMjAyMy0wNC0wNCwgMjAyMy0wNC0wNywgMjAyMy0wNC0wNScgIH0gICk7XHJcblx0XHQgKi9cclxuXHRcdGZ1bmN0aW9uIHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19jb21tYV9zZXBhcmF0ZWRfanMoIHBhcmFtcyApe1xyXG5cclxuXHRcdFx0dmFyIGRhdGVzX2FyciA9IFtdO1xyXG5cclxuXHRcdFx0aWYgKCAnJyAhPT0gcGFyYW1zWyAnZGF0ZXMnIF0gKXtcclxuXHJcblx0XHRcdFx0ZGF0ZXNfYXJyID0gcGFyYW1zWyAnZGF0ZXMnIF0uc3BsaXQoIHBhcmFtc1sgJ2RhdGVzX3NlcGFyYXRvcicgXSApO1xyXG5cclxuXHRcdFx0XHRkYXRlc19hcnIuc29ydCgpO1xyXG5cdFx0XHR9XHJcblx0XHRcdHJldHVybiBkYXRlc19hcnI7XHJcblx0XHR9XHJcblxyXG5cdFx0LyoqXHJcblx0XHQgKiBHZXQgZGF0ZXMgYXJyYXksICBmcm9tIHJhbmdlIGRheXMgc2VsZWN0aW9uXHJcblx0XHQgKlxyXG5cdFx0ICogQHBhcmFtIHBhcmFtcyAgICAgICA9ICB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqICdkYXRlc19zZXBhcmF0b3InID0+ICcgfiAnLCAgICAgICAgICAgICAgICAgICAgICAgICAvLyBEYXRlcyBzZXBhcmF0b3JcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogJ2RhdGVzJyAgICAgICAgICAgPT4gJzIwMjMtMDQtMDQgfiAyMDIzLTA0LTA3JyAgICAgIC8vIERhdGVzIGluICdZLW0tZCcgZm9ybWF0OiAnMjAyMy0wMS0zMSdcclxuXHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0ICpcclxuXHRcdCAqIEByZXR1cm4gYXJyYXkgICAgICAgID0gW1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMF0gPT4gMjAyMy0wNC0wNFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMV0gPT4gMjAyMy0wNC0wNVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMl0gPT4gMjAyMy0wNC0wNlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbM10gPT4gMjAyMy0wNC0wN1xyXG5cdFx0XHRcdFx0XHRcdFx0ICBdXHJcblx0XHQgKlxyXG5cdFx0ICogRXhhbXBsZSAjMTogIHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19yYW5nZV9qcyggIHsgICdkYXRlc19zZXBhcmF0b3InIDogJyB+ICcsICdkYXRlcycgOiAnMjAyMy0wNC0wNCB+IDIwMjMtMDQtMDcnICB9ICApO1xyXG5cdFx0ICogRXhhbXBsZSAjMjogIHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19yYW5nZV9qcyggIHsgICdkYXRlc19zZXBhcmF0b3InIDogJyAtICcsICdkYXRlcycgOiAnMjAyMy0wNC0wNCAtIDIwMjMtMDQtMDcnICB9ICApO1xyXG5cdFx0ICovXHJcblx0XHRmdW5jdGlvbiB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMoIHBhcmFtcyApe1xyXG5cclxuXHRcdFx0dmFyIGRhdGVzX2FyciA9IFtdO1xyXG5cclxuXHRcdFx0aWYgKCAnJyAhPT0gcGFyYW1zWydkYXRlcyddICkge1xyXG5cclxuXHRcdFx0XHRkYXRlc19hcnIgPSBwYXJhbXNbICdkYXRlcycgXS5zcGxpdCggcGFyYW1zWyAnZGF0ZXNfc2VwYXJhdG9yJyBdICk7XHJcblx0XHRcdFx0dmFyIGNoZWNrX2luX2RhdGVfeW1kICA9IGRhdGVzX2FyclswXTtcclxuXHRcdFx0XHR2YXIgY2hlY2tfb3V0X2RhdGVfeW1kID0gZGF0ZXNfYXJyWzFdO1xyXG5cclxuXHRcdFx0XHRpZiAoICgnJyAhPT0gY2hlY2tfaW5fZGF0ZV95bWQpICYmICgnJyAhPT0gY2hlY2tfb3V0X2RhdGVfeW1kKSApe1xyXG5cclxuXHRcdFx0XHRcdGRhdGVzX2FyciA9IHdwYmNfZ2V0X2RhdGVzX2FycmF5X2Zyb21fc3RhcnRfZW5kX2RheXNfanMoIGNoZWNrX2luX2RhdGVfeW1kLCBjaGVja19vdXRfZGF0ZV95bWQgKTtcclxuXHRcdFx0XHR9XHJcblx0XHRcdH1cclxuXHRcdFx0cmV0dXJuIGRhdGVzX2FycjtcclxuXHRcdH1cclxuXHJcblx0XHRcdC8qKlxyXG5cdFx0XHQgKiBHZXQgZGF0ZXMgYXJyYXkgYmFzZWQgb24gc3RhcnQgYW5kIGVuZCBkYXRlcy5cclxuXHRcdFx0ICpcclxuXHRcdFx0ICogQHBhcmFtIHN0cmluZyBzU3RhcnREYXRlIC0gc3RhcnQgZGF0ZTogMjAyMy0wNC0wOVxyXG5cdFx0XHQgKiBAcGFyYW0gc3RyaW5nIHNFbmREYXRlICAgLSBlbmQgZGF0ZTogICAyMDIzLTA0LTExXHJcblx0XHRcdCAqIEByZXR1cm4gYXJyYXkgICAgICAgICAgICAgLSBbIFwiMjAyMy0wNC0wOVwiLCBcIjIwMjMtMDQtMTBcIiwgXCIyMDIzLTA0LTExXCIgXVxyXG5cdFx0XHQgKi9cclxuXHRcdFx0ZnVuY3Rpb24gd3BiY19nZXRfZGF0ZXNfYXJyYXlfZnJvbV9zdGFydF9lbmRfZGF5c19qcyggc1N0YXJ0RGF0ZSwgc0VuZERhdGUgKXtcclxuXHJcblx0XHRcdFx0c1N0YXJ0RGF0ZSA9IG5ldyBEYXRlKCBzU3RhcnREYXRlICsgJ1QwMDowMDowMCcgKTtcclxuXHRcdFx0XHRzRW5kRGF0ZSA9IG5ldyBEYXRlKCBzRW5kRGF0ZSArICdUMDA6MDA6MDAnICk7XHJcblxyXG5cdFx0XHRcdHZhciBhRGF5cz1bXTtcclxuXHJcblx0XHRcdFx0Ly8gU3RhcnQgdGhlIHZhcmlhYmxlIG9mZiB3aXRoIHRoZSBzdGFydCBkYXRlXHJcblx0XHRcdFx0YURheXMucHVzaCggc1N0YXJ0RGF0ZS5nZXRUaW1lKCkgKTtcclxuXHJcblx0XHRcdFx0Ly8gU2V0IGEgJ3RlbXAnIHZhcmlhYmxlLCBzQ3VycmVudERhdGUsIHdpdGggdGhlIHN0YXJ0IGRhdGUgLSBiZWZvcmUgYmVnaW5uaW5nIHRoZSBsb29wXHJcblx0XHRcdFx0dmFyIHNDdXJyZW50RGF0ZSA9IG5ldyBEYXRlKCBzU3RhcnREYXRlLmdldFRpbWUoKSApO1xyXG5cdFx0XHRcdHZhciBvbmVfZGF5X2R1cmF0aW9uID0gMjQqNjAqNjAqMTAwMDtcclxuXHJcblx0XHRcdFx0Ly8gV2hpbGUgdGhlIGN1cnJlbnQgZGF0ZSBpcyBsZXNzIHRoYW4gdGhlIGVuZCBkYXRlXHJcblx0XHRcdFx0d2hpbGUoc0N1cnJlbnREYXRlIDwgc0VuZERhdGUpe1xyXG5cdFx0XHRcdFx0Ly8gQWRkIGEgZGF5IHRvIHRoZSBjdXJyZW50IGRhdGUgXCIrMSBkYXlcIlxyXG5cdFx0XHRcdFx0c0N1cnJlbnREYXRlLnNldFRpbWUoIHNDdXJyZW50RGF0ZS5nZXRUaW1lKCkgKyBvbmVfZGF5X2R1cmF0aW9uICk7XHJcblxyXG5cdFx0XHRcdFx0Ly8gQWRkIHRoaXMgbmV3IGRheSB0byB0aGUgYURheXMgYXJyYXlcclxuXHRcdFx0XHRcdGFEYXlzLnB1c2goIHNDdXJyZW50RGF0ZS5nZXRUaW1lKCkgKTtcclxuXHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdGZvciAobGV0IGkgPSAwOyBpIDwgYURheXMubGVuZ3RoOyBpKyspIHtcclxuXHRcdFx0XHRcdGFEYXlzWyBpIF0gPSBuZXcgRGF0ZSggYURheXNbaV0gKTtcclxuXHRcdFx0XHRcdGFEYXlzWyBpIF0gPSBhRGF5c1sgaSBdLmdldEZ1bGxZZWFyKClcclxuXHRcdFx0XHRcdFx0XHRcdCsgJy0nICsgKCggKGFEYXlzWyBpIF0uZ2V0TW9udGgoKSArIDEpIDwgMTApID8gJzAnIDogJycpICsgKGFEYXlzWyBpIF0uZ2V0TW9udGgoKSArIDEpXHJcblx0XHRcdFx0XHRcdFx0XHQrICctJyArICgoICAgICAgICBhRGF5c1sgaSBdLmdldERhdGUoKSA8IDEwKSA/ICcwJyA6ICcnKSArICBhRGF5c1sgaSBdLmdldERhdGUoKTtcclxuXHRcdFx0XHR9XHJcblx0XHRcdFx0Ly8gT25jZSB0aGUgbG9vcCBoYXMgZmluaXNoZWQsIHJldHVybiB0aGUgYXJyYXkgb2YgZGF5cy5cclxuXHRcdFx0XHRyZXR1cm4gYURheXM7XHJcblx0XHRcdH1cclxuXHJcblxyXG4vKipcclxuICogU2Nyb2xsIHRvICBzcGVjaWZpYyBcIlllYXIgJiBNb250aFwiIFx0aW4gSW5saW5lIEJvb2tpbmcgQ2FsZW5kYXJcclxuICpcclxuICogQHBhcmFtIHtudW1iZXJ9IHJlc291cmNlX2lkXHRcdDFcclxuICogQHBhcmFtIHtudW1iZXJ9IHllYXJcdFx0XHRcdDIwMjNcclxuICogQHBhcmFtIHtudW1iZXJ9IG1vbnRoXHRcdFx0MTJcdFx0XHQoZnJvbSAxIHRvICAxMilcclxuICpcclxuICogQHJldHVybnMge2Jvb2xlYW59XHRcdFx0Ly8gY2hhbmdlZCBvciBub3RcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19jaGFuZ2VfeWVhcl9tb250aCggcmVzb3VyY2VfaWQsIHllYXIsIG1vbnRoICl7XHJcblxyXG5cdHZhciBpbnN0ID0galF1ZXJ5LmRhdGVwaWNrLl9nZXRJbnN0KCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggJ2NhbGVuZGFyX2Jvb2tpbmcnICsgcmVzb3VyY2VfaWQpICk7XHJcblxyXG5cdGlmICggZmFsc2UgIT0gaW5zdCApe1xyXG5cclxuXHRcdHllYXIgPSBwYXJzZUludCggeWVhciApO1xyXG5cdFx0bW9udGggPSBwYXJzZUludCggbW9udGggKSAtIDE7XHJcblxyXG5cdFx0aW5zdC5jdXJzb3JEYXRlID0gbmV3IERhdGUoKTtcclxuXHRcdGluc3QuY3Vyc29yRGF0ZS5zZXRGdWxsWWVhciggeWVhciwgbW9udGgsIDEgKTtcclxuXHRcdGluc3QuY3Vyc29yRGF0ZS5zZXRNb250aCggbW9udGggKTtcdFx0XHRcdFx0XHQvLyBJbiBzb21lIGNhc2VzLCAgdGhlIHNldEZ1bGxZZWFyIGNhbiAgc2V0ICBvbmx5IFllYXIsICBhbmQgbm90IHRoZSBNb250aCBhbmQgZGF5ICAgICAgLy9GaXhJbjo2LjIuMy41XHJcblx0XHRpbnN0LmN1cnNvckRhdGUuc2V0RGF0ZSggMSApO1xyXG5cclxuXHRcdGluc3QuZHJhd01vbnRoID0gaW5zdC5jdXJzb3JEYXRlLmdldE1vbnRoKCk7XHJcblx0XHRpbnN0LmRyYXdZZWFyICA9IGluc3QuY3Vyc29yRGF0ZS5nZXRGdWxsWWVhcigpO1xyXG5cclxuXHRcdGpRdWVyeS5kYXRlcGljay5fbm90aWZ5Q2hhbmdlKCBpbnN0ICk7XHJcblx0XHRqUXVlcnkuZGF0ZXBpY2suX2FkanVzdEluc3REYXRlKCBpbnN0ICk7XHJcblx0XHRqUXVlcnkuZGF0ZXBpY2suX3Nob3dEYXRlKCBpbnN0ICk7XHJcblx0XHRqUXVlcnkuZGF0ZXBpY2suX3VwZGF0ZURhdGVwaWNrKCBpbnN0ICk7XHJcblxyXG5cdFx0cmV0dXJuICB0cnVlO1xyXG5cdH1cclxuXHRyZXR1cm4gIGZhbHNlO1xyXG59Il0sImZpbGUiOiJpbmNsdWRlcy9faW5saW5lX2NhbGVuZGFyX2pzX2Nzcy9fb3V0L3dwYmNfaW5saW5lX2NhbGVuZGFyLmpzIn0=
