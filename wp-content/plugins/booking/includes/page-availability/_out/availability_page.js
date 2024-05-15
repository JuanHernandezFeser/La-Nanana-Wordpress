"use strict";
/**
 * Request Object
 * Here we can  define Search parameters and Update it later,  when  some parameter was changed
 *
 */

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

var wpbc_ajx_availability = function (obj, $) {
  // Secure parameters for Ajax	------------------------------------------------------------------------------------
  var p_secure = obj.security_obj = obj.security_obj || {
    user_id: 0,
    nonce: '',
    locale: ''
  };

  obj.set_secure_param = function (param_key, param_val) {
    p_secure[param_key] = param_val;
  };

  obj.get_secure_param = function (param_key) {
    return p_secure[param_key];
  }; // Listing Search parameters	------------------------------------------------------------------------------------


  var p_listing = obj.search_request_obj = obj.search_request_obj || {// sort            : "booking_id",
    // sort_type       : "DESC",
    // page_num        : 1,
    // page_items_count: 10,
    // create_date     : "",
    // keyword         : "",
    // source          : ""
  };

  obj.search_set_all_params = function (request_param_obj) {
    p_listing = request_param_obj;
  };

  obj.search_get_all_params = function () {
    return p_listing;
  };

  obj.search_get_param = function (param_key) {
    return p_listing[param_key];
  };

  obj.search_set_param = function (param_key, param_val) {
    // if ( Array.isArray( param_val ) ){
    // 	param_val = JSON.stringify( param_val );
    // }
    p_listing[param_key] = param_val;
  };

  obj.search_set_params_arr = function (params_arr) {
    _.each(params_arr, function (p_val, p_key, p_data) {
      // Define different Search  parameters for request
      this.search_set_param(p_key, p_val);
    });
  }; // Other parameters 			------------------------------------------------------------------------------------


  var p_other = obj.other_obj = obj.other_obj || {};

  obj.set_other_param = function (param_key, param_val) {
    p_other[param_key] = param_val;
  };

  obj.get_other_param = function (param_key) {
    return p_other[param_key];
  };

  return obj;
}(wpbc_ajx_availability || {}, jQuery);

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

function wpbc_ajx_availability__page_content__show(ajx_data_arr, ajx_search_params, ajx_cleaned_params) {
  var template__availability_main_page_content = wp.template('wpbc_ajx_availability_main_page_content'); // Content

  jQuery(wpbc_ajx_availability.get_other_param('listing_container')).html(template__availability_main_page_content({
    'ajx_data': ajx_data_arr,
    'ajx_search_params': ajx_search_params,
    // $_REQUEST[ 'search_params' ]
    'ajx_cleaned_params': ajx_cleaned_params
  }));
  jQuery('.wpbc_processing.wpbc_spin').parent().parent().parent().parent('[id^="wpbc_notice_"]').hide(); // Load calendar

  wpbc_ajx_availability__calendar__show({
    'resource_id': ajx_cleaned_params.resource_id,
    'ajx_nonce_calendar': ajx_data_arr.ajx_nonce_calendar,
    'ajx_data_arr': ajx_data_arr,
    'ajx_cleaned_params': ajx_cleaned_params
  });
  /**
   * Trigger for dates selection in the booking form
   *
   * jQuery( wpbc_ajx_availability.get_other_param( 'listing_container' ) ).on('wpbc_page_content_loaded', function(event, ajx_data_arr, ajx_search_params , ajx_cleaned_params) { ... } );
   */

  jQuery(wpbc_ajx_availability.get_other_param('listing_container')).trigger('wpbc_page_content_loaded', [ajx_data_arr, ajx_search_params, ajx_cleaned_params]);
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


function wpbc_ajx_availability__calendar__show(calendar_params_arr) {
  // Update nonce
  jQuery('#ajx_nonce_calendar_section').html(calendar_params_arr.ajx_nonce_calendar); //------------------------------------------------------------------------------------------------------------------
  // Update bookings

  if ('undefined' == typeof wpbc_ajx_bookings[calendar_params_arr.resource_id]) {
    wpbc_ajx_bookings[calendar_params_arr.resource_id] = [];
  }

  wpbc_ajx_bookings[calendar_params_arr.resource_id] = calendar_params_arr['ajx_data_arr']['booked_dates']; //------------------------------------------------------------------------------------------------------------------

  /**
   * Define showing mouse over tooltip on unavailable dates
   * It's defined, when calendar REFRESHED (change months or days selection) loaded in jquery.datepick.wpbc.9.0.js :
   * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_refresh', ...		//FixIn: 9.4.4.13
   */

  jQuery('body').on('wpbc_datepick_inline_calendar_refresh', function (event, resource_id, inst) {
    // inst.dpDiv  it's:  <div class="datepick-inline datepick-multi" style="width: 17712px;">....</div>
    inst.dpDiv.find('.season_unavailable,.before_after_unavailable,.weekdays_unavailable').on('mouseover', function (this_event) {
      // also available these vars: 	resource_id, jCalContainer, inst
      var jCell = jQuery(this_event.currentTarget);
      wpbc_avy__show_tooltip__for_element(jCell, calendar_params_arr['ajx_data_arr']['popover_hints']);
    });
  }); //------------------------------------------------------------------------------------------------------------------

  /**
   * Define height of the calendar  cells, 	and  mouse over tooltips at  some unavailable dates
   * It's defined, when calendar loaded in jquery.datepick.wpbc.9.0.js :
   * 		$( 'body' ).trigger( 'wpbc_datepick_inline_calendar_loaded', ...		//FixIn: 9.4.4.12
   */

  jQuery('body').on('wpbc_datepick_inline_calendar_loaded', function (event, resource_id, jCalContainer, inst) {
    // Remove highlight day for today  date
    jQuery('.datepick-days-cell.datepick-today.datepick-days-cell-over').removeClass('datepick-days-cell-over'); // Set height of calendar  cells if defined this option

    if ('' !== calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height) {
      jQuery('head').append('<style type="text/css">' + '.hasDatepick .datepick-inline .datepick-title-row th, ' + '.hasDatepick .datepick-inline .datepick-days-cell {' + 'height: ' + calendar_params_arr.ajx_cleaned_params.calendar__view__cell_height + ' !important;' + '}' + '</style>');
    } // Define showing mouse over tooltip on unavailable dates


    jCalContainer.find('.season_unavailable,.before_after_unavailable,.weekdays_unavailable').on('mouseover', function (this_event) {
      // also available these vars: 	resource_id, jCalContainer, inst
      var jCell = jQuery(this_event.currentTarget);
      wpbc_avy__show_tooltip__for_element(jCell, calendar_params_arr['ajx_data_arr']['popover_hints']);
    });
  }); //------------------------------------------------------------------------------------------------------------------
  // Define width of entire calendar

  var width = 'width:' + calendar_params_arr.ajx_cleaned_params.calendar__view__width + ';'; // var width = 'width:100%;max-width:100%;';

  if (undefined != calendar_params_arr.ajx_cleaned_params.calendar__view__max_width && '' != calendar_params_arr.ajx_cleaned_params.calendar__view__max_width) {
    width += 'max-width:' + calendar_params_arr.ajx_cleaned_params.calendar__view__max_width + ';';
  } else {
    width += 'max-width:' + calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row * 341 + 'px;';
  } //------------------------------------------------------------------------------------------------------------------
  // Add calendar container: "Calendar is loading..."  and textarea


  jQuery('.wpbc_ajx_avy__calendar').html('<div class="' + ' bk_calendar_frame' + ' months_num_in_row_' + calendar_params_arr.ajx_cleaned_params.calendar__view__months_in_row + ' cal_month_num_' + calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months + ' ' + calendar_params_arr.ajx_cleaned_params.calendar__timeslot_day_bg_as_available // 'wpbc_timeslot_day_bg_as_available' || ''
  + '" ' + 'style="' + width + '">' + '<div id="calendar_booking' + calendar_params_arr.resource_id + '">' + 'Calendar is loading...' + '</div>' + '</div>' + '<textarea      id="date_booking' + calendar_params_arr.resource_id + '"' + ' name="date_booking' + calendar_params_arr.resource_id + '"' + ' autocomplete="off"' + ' style="display:none;width:100%;height:10em;margin:2em 0 0;"></textarea>'); //------------------------------------------------------------------------------------------------------------------

  var cal_param_arr = {
    'html_id': 'calendar_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
    'text_id': 'date_booking' + calendar_params_arr.ajx_cleaned_params.resource_id,
    'calendar__start_week_day': calendar_params_arr.ajx_cleaned_params.calendar__start_week_day,
    'calendar__view__visible_months': calendar_params_arr.ajx_cleaned_params.calendar__view__visible_months,
    'calendar__days_selection_mode': calendar_params_arr.ajx_cleaned_params.calendar__days_selection_mode,
    'resource_id': calendar_params_arr.ajx_cleaned_params.resource_id,
    'ajx_nonce_calendar': calendar_params_arr.ajx_data_arr.ajx_nonce_calendar,
    'booked_dates': calendar_params_arr.ajx_data_arr.booked_dates,
    'season_availability': calendar_params_arr.ajx_data_arr.season_availability,
    'resource_unavailable_dates': calendar_params_arr.ajx_data_arr.resource_unavailable_dates,
    'popover_hints': calendar_params_arr['ajx_data_arr']['popover_hints'] // {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}

  };
  wpbc_show_inline_booking_calendar(cal_param_arr); //------------------------------------------------------------------------------------------------------------------

  /**
   * On click AVAILABLE |  UNAVAILABLE button  in widget	-	need to  change help dates text
   */

  jQuery('.wpbc_radio__set_days_availability').on('change', function (event, resource_id, inst) {
    wpbc__inline_booking_calendar__on_days_select(jQuery('#' + cal_param_arr.text_id).val(), cal_param_arr);
  }); // Show 	'Select days  in calendar then select Available  /  Unavailable status and click Apply availability button.'

  jQuery('#wpbc_toolbar_dates_hint').html('<div class="ui_element"><span class="wpbc_ui_control wpbc_ui_addon wpbc_help_text" >' + cal_param_arr.popover_hints.toolbar_text + '</span></div>');
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


function wpbc_show_inline_booking_calendar(calendar_params_arr) {
  if (0 === jQuery('#' + calendar_params_arr.html_id).length // If calendar DOM element not exist then exist
  || true === jQuery('#' + calendar_params_arr.html_id).hasClass('hasDatepick') // If the calendar with the same Booking resource already  has been activated, then exist.
  ) {
    return false;
  } //------------------------------------------------------------------------------------------------------------------
  // Configure and show calendar


  jQuery('#' + calendar_params_arr.html_id).text('');
  jQuery('#' + calendar_params_arr.html_id).datepick({
    beforeShowDay: function beforeShowDay(date) {
      return wpbc__inline_booking_calendar__apply_css_to_days(date, calendar_params_arr, this);
    },
    onSelect: function onSelect(date) {
      jQuery('#' + calendar_params_arr.text_id).val(date); //wpbc_blink_element('.wpbc_widget_available_unavailable', 3, 220);

      return wpbc__inline_booking_calendar__on_days_select(date, calendar_params_arr, this);
    },
    onHover: function onHover(value, date) {
      //wpbc_avy__prepare_tooltip__in_calendar( value, date, calendar_params_arr, this );
      return wpbc__inline_booking_calendar__on_days_hover(value, date, calendar_params_arr, this);
    },
    onChangeMonthYear: null,
    showOn: 'both',
    numberOfMonths: calendar_params_arr.calendar__view__visible_months,
    stepMonths: 1,
    prevText: '&laquo;',
    nextText: '&raquo;',
    dateFormat: 'yy-mm-dd',
    // 'dd.mm.yy',
    changeMonth: false,
    changeYear: false,
    minDate: 0,
    //null,  //Scroll as long as you need
    maxDate: '10y',
    // minDate: new Date(2020, 2, 1), maxDate: new Date(2020, 9, 31), 	// Ability to set any  start and end date in calendar
    showStatus: false,
    closeAtTop: false,
    firstDay: calendar_params_arr.calendar__start_week_day,
    gotoCurrent: false,
    hideIfNoPrevNext: true,
    multiSeparator: ', ',
    multiSelect: 'dynamic' == calendar_params_arr.calendar__days_selection_mode ? 0 : 365,
    // Maximum number of selectable dates:	 Single day = 0,  multi days = 365
    rangeSelect: 'dynamic' == calendar_params_arr.calendar__days_selection_mode,
    rangeSeparator: ' ~ ',
    //' - ',
    // showWeeks: true,
    useThemeRoller: false
  });
  return true;
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


function wpbc__inline_booking_calendar__apply_css_to_days(date, calendar_params_arr, datepick_this) {
  var today_date = new Date(wpbc_today[0], parseInt(wpbc_today[1]) - 1, wpbc_today[2], 0, 0, 0);
  var class_day = date.getMonth() + 1 + '-' + date.getDate() + '-' + date.getFullYear(); // '1-9-2023'

  var sql_class_day = wpbc__get__sql_class_date(date); // '2023-01-09'

  var css_date__standard = 'cal4date-' + class_day;
  var css_date__additional = ' wpbc_weekday_' + date.getDay() + ' '; //--------------------------------------------------------------------------------------------------------------
  // WEEKDAYS :: Set unavailable week days from - Settings General page in "Availability" section

  for (var i = 0; i < user_unavilable_days.length; i++) {
    if (date.getDay() == user_unavilable_days[i]) {
      return [!!false, css_date__standard + ' date_user_unavailable' + ' weekdays_unavailable'];
    }
  } // BEFORE_AFTER :: Set unavailable days Before / After the Today date


  if (days_between(date, today_date) < block_some_dates_from_today || typeof wpbc_available_days_num_from_today !== 'undefined' && parseInt('0' + wpbc_available_days_num_from_today) > 0 && days_between(date, today_date) > parseInt('0' + wpbc_available_days_num_from_today)) {
    return [!!false, css_date__standard + ' date_user_unavailable' + ' before_after_unavailable'];
  } // SEASONS ::  					Booking > Resources > Availability page


  var is_date_available = calendar_params_arr.season_availability[sql_class_day];

  if (false === is_date_available) {
    //FixIn: 9.5.4.4
    return [!!false, css_date__standard + ' date_user_unavailable' + ' season_unavailable'];
  } // RESOURCE_UNAVAILABLE ::   	Booking > Availability page


  if (wpdev_in_array(calendar_params_arr.resource_unavailable_dates, sql_class_day)) {
    is_date_available = false;
  }

  if (false === is_date_available) {
    //FixIn: 9.5.4.4
    return [!false, css_date__standard + ' date_user_unavailable' + ' resource_unavailable'];
  } //--------------------------------------------------------------------------------------------------------------
  //--------------------------------------------------------------------------------------------------------------
  // Is any bookings in this date ?


  if ('undefined' !== typeof calendar_params_arr.booked_dates[class_day]) {
    var bookings_in_date = calendar_params_arr.booked_dates[class_day];

    if ('undefined' !== typeof bookings_in_date['sec_0']) {
      // "Full day" booking  -> (seconds == 0)
      css_date__additional += '0' === bookings_in_date['sec_0'].approved ? ' date2approve ' : ' date_approved '; // Pending = '0' |  Approved = '1'

      css_date__additional += ' full_day_booking';
      return [!false, css_date__standard + css_date__additional];
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


function wpbc__inline_booking_calendar__on_days_hover(value, date, calendar_params_arr, datepick_this) {
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


function wpbc__inline_booking_calendar__on_days_select(dates_selection, calendar_params_arr) {
  var datepick_this = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

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

  if (jQuery('#ui_btn_avy__set_days_availability__available').is(':checked')) {
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
      if ('first_time' == jQuery('.wpbc_ajx_availability_container').attr('wpbc_loaded')) {
        jQuery('.wpbc_ajx_availability_container').attr('wpbc_loaded', 'done');
        wpbc_blink_element('.wpbc_widget_available_unavailable', 3, 220);
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

  message = message.replace('_HTML_', '</span><span class="wpbc_big_text" style="color:' + color + ';">') + '<span>'; //message += ' <div style="margin-left: 1em;">' + ' Click on Apply button to apply availability.' + '</div>';

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


function wpbc_avy__prepare_tooltip__in_calendar(value, date, calendar_params_arr, datepick_this) {
  if (null == date) {
    return false;
  }

  var td_class = date.getMonth() + 1 + '-' + date.getDate() + '-' + date.getFullYear();
  var jCell = jQuery('#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class);
  wpbc_avy__show_tooltip__for_element(jCell, calendar_params_arr['popover_hints']);
  return true;
}
/**
 * Define tooltip  for showing on UNAVAILABLE days (season, weekday, today_depends unavailable)
 *
 * @param jCell					jQuery of specific day cell
 * @param popover_hints		    Array with tooltip hint texts	 : {'season_unavailable':'...','weekdays_unavailable':'...','before_after_unavailable':'...',}
 */


function wpbc_avy__show_tooltip__for_element(jCell, popover_hints) {
  var tooltip_time = '';

  if (jCell.hasClass('season_unavailable')) {
    tooltip_time = popover_hints['season_unavailable'];
  } else if (jCell.hasClass('weekdays_unavailable')) {
    tooltip_time = popover_hints['weekdays_unavailable'];
  } else if (jCell.hasClass('before_after_unavailable')) {
    tooltip_time = popover_hints['before_after_unavailable'];
  } else if (jCell.hasClass('date2approve')) {} else if (jCell.hasClass('date_approved')) {} else {}

  jCell.attr('data-content', tooltip_time);
  var td_el = jCell.get(0); //jQuery( '#calendar_booking' + calendar_params_arr.resource_id + ' td.cal4date-' + td_class ).get(0);

  if (undefined == td_el._tippy && '' != tooltip_time) {
    wpbc_tippy(td_el, {
      content: function content(reference) {
        var popover_content = reference.getAttribute('data-content');
        return '<div class="popover popover_tippy">' + '<div class="popover-content">' + popover_content + '</div>' + '</div>';
      },
      allowHTML: true,
      trigger: 'mouseenter focus',
      interactive: !true,
      hideOnClick: true,
      interactiveBorder: 10,
      maxWidth: 550,
      theme: 'wpbc-tippy-times',
      placement: 'top',
      delay: [400, 0],
      //FixIn: 9.4.2.2
      ignoreAttributes: true,
      touch: true,
      //['hold', 500], // 500ms delay			//FixIn: 9.2.1.5
      appendTo: function appendTo() {
        return document.body;
      }
    });
  }
}
/**
 *   Ajax  ------------------------------------------------------------------------------------------------------ */

/**
 * Send Ajax show request
 */


function wpbc_ajx_availability__ajax_request() {
  console.groupCollapsed('WPBC_AJX_AVAILABILITY');
  console.log(' == Before Ajax Send - search_get_all_params() == ', wpbc_ajx_availability.search_get_all_params());
  wpbc_availability_reload_button__spin_start(); // Start Ajax

  jQuery.post(wpbc_global1.wpbc_ajaxurl, {
    action: 'WPBC_AJX_AVAILABILITY',
    wpbc_ajx_user_id: wpbc_ajx_availability.get_secure_param('user_id'),
    nonce: wpbc_ajx_availability.get_secure_param('nonce'),
    wpbc_ajx_locale: wpbc_ajx_availability.get_secure_param('locale'),
    search_params: wpbc_ajx_availability.search_get_all_params()
  },
  /**
   * S u c c e s s
   *
   * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
   * @param textStatus		-	'success'
   * @param jqXHR				-	Object
   */
  function (response_data, textStatus, jqXHR) {
    console.log(' == Response WPBC_AJX_AVAILABILITY == ', response_data);
    console.groupEnd(); // Probably Error

    if (_typeof(response_data) !== 'object' || response_data === null) {
      wpbc_ajx_availability__show_message(response_data);
      return;
    } // Reload page, after filter toolbar has been reset


    if (undefined != response_data['ajx_cleaned_params'] && 'reset_done' === response_data['ajx_cleaned_params']['do_action']) {
      location.reload();
      return;
    } // Show listing


    wpbc_ajx_availability__page_content__show(response_data['ajx_data'], response_data['ajx_search_params'], response_data['ajx_cleaned_params']); //wpbc_ajx_availability__define_ui_hooks();						// Redefine Hooks, because we show new DOM elements

    if ('' != response_data['ajx_data']['ajx_after_action_message'].replace(/\n/g, "<br />")) {
      wpbc_admin_show_message(response_data['ajx_data']['ajx_after_action_message'].replace(/\n/g, "<br />"), '1' == response_data['ajx_data']['ajx_after_action_result'] ? 'success' : 'error', 10000);
    }

    wpbc_availability_reload_button__spin_pause(); // Remove spin icon from  button and Enable this button.

    wpbc_button__remove_spin(response_data['ajx_cleaned_params']['ui_clicked_element_id']);
    jQuery('#ajax_respond').html(response_data); // For ability to show response, add such DIV element to page
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (window.console && window.console.log) {
      console.log('Ajax_Error', jqXHR, textStatus, errorThrown);
    }

    var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown;

    if (jqXHR.status) {
      error_message += ' (<b>' + jqXHR.status + '</b>)';

      if (403 == jqXHR.status) {
        error_message += ' Probably nonce for this page has been expired. Please <a href="javascript:void(0)" onclick="javascript:location.reload();">reload the page</a>.';
      }
    }

    if (jqXHR.responseText) {
      error_message += ' ' + jqXHR.responseText;
    }

    error_message = error_message.replace(/\n/g, "<br />");
    wpbc_ajx_availability__show_message(error_message);
  }) // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
  ; // End Ajax
}
/**
 *   H o o k s  -  its Action/Times when need to re-Render Views  ----------------------------------------------- */

/**
 * Send Ajax Search Request after Updating search request parameters
 *
 * @param params_arr
 */


function wpbc_ajx_availability__send_request_with_params(params_arr) {
  // Define different Search  parameters for request
  _.each(params_arr, function (p_val, p_key, p_data) {
    //console.log( 'Request for: ', p_key, p_val );
    wpbc_ajx_availability.search_set_param(p_key, p_val);
  }); // Send Ajax Request


  wpbc_ajx_availability__ajax_request();
}
/**
 * Search request for "Page Number"
 * @param page_number	int
 */


function wpbc_ajx_availability__pagination_click(page_number) {
  wpbc_ajx_availability__send_request_with_params({
    'page_num': page_number
  });
}
/**
 *   Show / Hide Content  --------------------------------------------------------------------------------------- */

/**
 *  Show Listing Content 	- 	Sending Ajax Request	-	with parameters that  we early  defined
 */


function wpbc_ajx_availability__actual_content__show() {
  wpbc_ajx_availability__ajax_request(); // Send Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
}
/**
 * Hide Listing Content
 */


function wpbc_ajx_availability__actual_content__hide() {
  jQuery(wpbc_ajx_availability.get_other_param('listing_container')).html('');
}
/**
 *   M e s s a g e  --------------------------------------------------------------------------------------------- */

/**
 * Show just message instead of content
 */


function wpbc_ajx_availability__show_message(message) {
  wpbc_ajx_availability__actual_content__hide();
  jQuery(wpbc_ajx_availability.get_other_param('listing_container')).html('<div class="wpbc-settings-notice notice-warning" style="text-align:left">' + message + '</div>');
}
/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Spin button in Filter toolbar  -  Start
 */


function wpbc_availability_reload_button__spin_start() {
  jQuery('#wpbc_availability_reload_button .menu_icon.wpbc_spin').removeClass('wpbc_animation_pause');
}
/**
 * Spin button in Filter toolbar  -  Pause
 */


function wpbc_availability_reload_button__spin_pause() {
  jQuery('#wpbc_availability_reload_button .menu_icon.wpbc_spin').addClass('wpbc_animation_pause');
}
/**
 * Spin button in Filter toolbar  -  is Spinning ?
 *
 * @returns {boolean}
 */


function wpbc_availability_reload_button__is_spin() {
  if (jQuery('#wpbc_availability_reload_button .menu_icon.wpbc_spin').hasClass('wpbc_animation_pause')) {
    return true;
  } else {
    return false;
  }
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImluY2x1ZGVzL3BhZ2UtYXZhaWxhYmlsaXR5L19zcmMvYXZhaWxhYmlsaXR5X3BhZ2UuanMiXSwibmFtZXMiOlsid3BiY19hanhfYXZhaWxhYmlsaXR5Iiwib2JqIiwiJCIsInBfc2VjdXJlIiwic2VjdXJpdHlfb2JqIiwidXNlcl9pZCIsIm5vbmNlIiwibG9jYWxlIiwic2V0X3NlY3VyZV9wYXJhbSIsInBhcmFtX2tleSIsInBhcmFtX3ZhbCIsImdldF9zZWN1cmVfcGFyYW0iLCJwX2xpc3RpbmciLCJzZWFyY2hfcmVxdWVzdF9vYmoiLCJzZWFyY2hfc2V0X2FsbF9wYXJhbXMiLCJyZXF1ZXN0X3BhcmFtX29iaiIsInNlYXJjaF9nZXRfYWxsX3BhcmFtcyIsInNlYXJjaF9nZXRfcGFyYW0iLCJzZWFyY2hfc2V0X3BhcmFtIiwic2VhcmNoX3NldF9wYXJhbXNfYXJyIiwicGFyYW1zX2FyciIsIl8iLCJlYWNoIiwicF92YWwiLCJwX2tleSIsInBfZGF0YSIsInBfb3RoZXIiLCJvdGhlcl9vYmoiLCJzZXRfb3RoZXJfcGFyYW0iLCJnZXRfb3RoZXJfcGFyYW0iLCJqUXVlcnkiLCJ3cGJjX2FqeF9ib29raW5ncyIsIndwYmNfYWp4X2F2YWlsYWJpbGl0eV9fcGFnZV9jb250ZW50X19zaG93IiwiYWp4X2RhdGFfYXJyIiwiYWp4X3NlYXJjaF9wYXJhbXMiLCJhanhfY2xlYW5lZF9wYXJhbXMiLCJ0ZW1wbGF0ZV9fYXZhaWxhYmlsaXR5X21haW5fcGFnZV9jb250ZW50Iiwid3AiLCJ0ZW1wbGF0ZSIsImh0bWwiLCJwYXJlbnQiLCJoaWRlIiwid3BiY19hanhfYXZhaWxhYmlsaXR5X19jYWxlbmRhcl9fc2hvdyIsInJlc291cmNlX2lkIiwiYWp4X25vbmNlX2NhbGVuZGFyIiwidHJpZ2dlciIsImNhbGVuZGFyX3BhcmFtc19hcnIiLCJvbiIsImV2ZW50IiwiaW5zdCIsImRwRGl2IiwiZmluZCIsInRoaXNfZXZlbnQiLCJqQ2VsbCIsImN1cnJlbnRUYXJnZXQiLCJ3cGJjX2F2eV9fc2hvd190b29sdGlwX19mb3JfZWxlbWVudCIsImpDYWxDb250YWluZXIiLCJyZW1vdmVDbGFzcyIsImNhbGVuZGFyX192aWV3X19jZWxsX2hlaWdodCIsImFwcGVuZCIsIndpZHRoIiwiY2FsZW5kYXJfX3ZpZXdfX3dpZHRoIiwidW5kZWZpbmVkIiwiY2FsZW5kYXJfX3ZpZXdfX21heF93aWR0aCIsImNhbGVuZGFyX192aWV3X19tb250aHNfaW5fcm93IiwiY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzIiwiY2FsZW5kYXJfX3RpbWVzbG90X2RheV9iZ19hc19hdmFpbGFibGUiLCJjYWxfcGFyYW1fYXJyIiwiY2FsZW5kYXJfX3N0YXJ0X3dlZWtfZGF5IiwiY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUiLCJib29rZWRfZGF0ZXMiLCJzZWFzb25fYXZhaWxhYmlsaXR5IiwicmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXMiLCJ3cGJjX3Nob3dfaW5saW5lX2Jvb2tpbmdfY2FsZW5kYXIiLCJ3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19zZWxlY3QiLCJ0ZXh0X2lkIiwidmFsIiwicG9wb3Zlcl9oaW50cyIsInRvb2xiYXJfdGV4dCIsImh0bWxfaWQiLCJsZW5ndGgiLCJoYXNDbGFzcyIsInRleHQiLCJkYXRlcGljayIsImJlZm9yZVNob3dEYXkiLCJkYXRlIiwid3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX2FwcGx5X2Nzc190b19kYXlzIiwib25TZWxlY3QiLCJvbkhvdmVyIiwidmFsdWUiLCJ3cGJjX19pbmxpbmVfYm9va2luZ19jYWxlbmRhcl9fb25fZGF5c19ob3ZlciIsIm9uQ2hhbmdlTW9udGhZZWFyIiwic2hvd09uIiwibnVtYmVyT2ZNb250aHMiLCJzdGVwTW9udGhzIiwicHJldlRleHQiLCJuZXh0VGV4dCIsImRhdGVGb3JtYXQiLCJjaGFuZ2VNb250aCIsImNoYW5nZVllYXIiLCJtaW5EYXRlIiwibWF4RGF0ZSIsInNob3dTdGF0dXMiLCJjbG9zZUF0VG9wIiwiZmlyc3REYXkiLCJnb3RvQ3VycmVudCIsImhpZGVJZk5vUHJldk5leHQiLCJtdWx0aVNlcGFyYXRvciIsIm11bHRpU2VsZWN0IiwicmFuZ2VTZWxlY3QiLCJyYW5nZVNlcGFyYXRvciIsInVzZVRoZW1lUm9sbGVyIiwiZGF0ZXBpY2tfdGhpcyIsInRvZGF5X2RhdGUiLCJEYXRlIiwid3BiY190b2RheSIsInBhcnNlSW50IiwiY2xhc3NfZGF5IiwiZ2V0TW9udGgiLCJnZXREYXRlIiwiZ2V0RnVsbFllYXIiLCJzcWxfY2xhc3NfZGF5Iiwid3BiY19fZ2V0X19zcWxfY2xhc3NfZGF0ZSIsImNzc19kYXRlX19zdGFuZGFyZCIsImNzc19kYXRlX19hZGRpdGlvbmFsIiwiZ2V0RGF5IiwiaSIsInVzZXJfdW5hdmlsYWJsZV9kYXlzIiwiZGF5c19iZXR3ZWVuIiwiYmxvY2tfc29tZV9kYXRlc19mcm9tX3RvZGF5Iiwid3BiY19hdmFpbGFibGVfZGF5c19udW1fZnJvbV90b2RheSIsImlzX2RhdGVfYXZhaWxhYmxlIiwid3BkZXZfaW5fYXJyYXkiLCJib29raW5nc19pbl9kYXRlIiwiYXBwcm92ZWQiLCJPYmplY3QiLCJrZXlzIiwiaXNfYXBwcm92ZWQiLCJ0cyIsImJvb2tpbmdfZGF0ZSIsInN1YnN0cmluZyIsImlzX2Jvb2tpbmdfdXNlZF9jaGVja19pbl9vdXRfdGltZSIsIl9nZXRJbnN0IiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsImRhdGVzIiwidGRfY2xhc3MiLCJ0ZF9vdmVycyIsImlzX2NoZWNrIiwic2VsY2V0ZWRfZmlyc3RfZGF5Iiwic2V0RnVsbFllYXIiLCJhZGRDbGFzcyIsImRhdGVzX3NlbGVjdGlvbiIsImRhdGVzX2FyciIsImluZGV4T2YiLCJ3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMiLCJ3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfY29tbWFfc2VwYXJhdGVkX2pzIiwid3BiY19hdnlfYWZ0ZXJfZGF5c19zZWxlY3Rpb25fX3Nob3dfaGVscF9pbmZvIiwicGFyYW1zIiwibWVzc2FnZSIsImNvbG9yIiwiaXMiLCJ0b29sYmFyX3RleHRfYXZhaWxhYmxlIiwidG9vbGJhcl90ZXh0X3VuYXZhaWxhYmxlIiwiZmlyc3RfZGF0ZSIsImxhc3RfZGF0ZSIsImZvcm1hdERhdGUiLCJkYXRlc19jbGlja19udW0iLCJhdHRyIiwid3BiY19ibGlua19lbGVtZW50IiwicmVwbGFjZSIsInB1c2giLCJqb2luIiwic3BsaXQiLCJzb3J0IiwiY2hlY2tfaW5fZGF0ZV95bWQiLCJjaGVja19vdXRfZGF0ZV95bWQiLCJ3cGJjX2dldF9kYXRlc19hcnJheV9mcm9tX3N0YXJ0X2VuZF9kYXlzX2pzIiwic1N0YXJ0RGF0ZSIsInNFbmREYXRlIiwiYURheXMiLCJnZXRUaW1lIiwic0N1cnJlbnREYXRlIiwib25lX2RheV9kdXJhdGlvbiIsInNldFRpbWUiLCJ3cGJjX2F2eV9fcHJlcGFyZV90b29sdGlwX19pbl9jYWxlbmRhciIsInRvb2x0aXBfdGltZSIsInRkX2VsIiwiZ2V0IiwiX3RpcHB5Iiwid3BiY190aXBweSIsImNvbnRlbnQiLCJyZWZlcmVuY2UiLCJwb3BvdmVyX2NvbnRlbnQiLCJnZXRBdHRyaWJ1dGUiLCJhbGxvd0hUTUwiLCJpbnRlcmFjdGl2ZSIsImhpZGVPbkNsaWNrIiwiaW50ZXJhY3RpdmVCb3JkZXIiLCJtYXhXaWR0aCIsInRoZW1lIiwicGxhY2VtZW50IiwiZGVsYXkiLCJpZ25vcmVBdHRyaWJ1dGVzIiwidG91Y2giLCJhcHBlbmRUbyIsImJvZHkiLCJ3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FqYXhfcmVxdWVzdCIsImNvbnNvbGUiLCJncm91cENvbGxhcHNlZCIsImxvZyIsIndwYmNfYXZhaWxhYmlsaXR5X3JlbG9hZF9idXR0b25fX3NwaW5fc3RhcnQiLCJwb3N0Iiwid3BiY19nbG9iYWwxIiwid3BiY19hamF4dXJsIiwiYWN0aW9uIiwid3BiY19hanhfdXNlcl9pZCIsIndwYmNfYWp4X2xvY2FsZSIsInNlYXJjaF9wYXJhbXMiLCJyZXNwb25zZV9kYXRhIiwidGV4dFN0YXR1cyIsImpxWEhSIiwiZ3JvdXBFbmQiLCJ3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX3Nob3dfbWVzc2FnZSIsImxvY2F0aW9uIiwicmVsb2FkIiwid3BiY19hZG1pbl9zaG93X21lc3NhZ2UiLCJ3cGJjX2F2YWlsYWJpbGl0eV9yZWxvYWRfYnV0dG9uX19zcGluX3BhdXNlIiwid3BiY19idXR0b25fX3JlbW92ZV9zcGluIiwiZmFpbCIsImVycm9yVGhyb3duIiwid2luZG93IiwiZXJyb3JfbWVzc2FnZSIsInN0YXR1cyIsInJlc3BvbnNlVGV4dCIsIndwYmNfYWp4X2F2YWlsYWJpbGl0eV9fc2VuZF9yZXF1ZXN0X3dpdGhfcGFyYW1zIiwid3BiY19hanhfYXZhaWxhYmlsaXR5X19wYWdpbmF0aW9uX2NsaWNrIiwicGFnZV9udW1iZXIiLCJ3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FjdHVhbF9jb250ZW50X19zaG93Iiwid3BiY19hanhfYXZhaWxhYmlsaXR5X19hY3R1YWxfY29udGVudF9faGlkZSIsIndwYmNfYXZhaWxhYmlsaXR5X3JlbG9hZF9idXR0b25fX2lzX3NwaW4iXSwibWFwcGluZ3MiOiJBQUFBO0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7OztBQUVBLElBQUlBLHFCQUFxQixHQUFJLFVBQVdDLEdBQVgsRUFBZ0JDLENBQWhCLEVBQW1CO0FBRS9DO0FBQ0EsTUFBSUMsUUFBUSxHQUFHRixHQUFHLENBQUNHLFlBQUosR0FBbUJILEdBQUcsQ0FBQ0csWUFBSixJQUFvQjtBQUN4Q0MsSUFBQUEsT0FBTyxFQUFFLENBRCtCO0FBRXhDQyxJQUFBQSxLQUFLLEVBQUksRUFGK0I7QUFHeENDLElBQUFBLE1BQU0sRUFBRztBQUgrQixHQUF0RDs7QUFNQU4sRUFBQUEsR0FBRyxDQUFDTyxnQkFBSixHQUF1QixVQUFXQyxTQUFYLEVBQXNCQyxTQUF0QixFQUFrQztBQUN4RFAsSUFBQUEsUUFBUSxDQUFFTSxTQUFGLENBQVIsR0FB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Q0FBRzlCLHFCQUFxQixDQUFDNkIsZUFBdEIsQ0FBdUMsbUJBQXZDLENBQUgsQ0FBTixDQUF5RVUsSUFBekUsQ0FBK0UsRUFBL0U7QUFDQTtBQUlEO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFDQSxTQUFTd0wsbUNBQVQsQ0FBOENoRSxPQUE5QyxFQUF1RDtBQUV0RGdGLEVBQUFBLDJDQUEyQztBQUUzQ2pOLEVBQUFBLE1BQU0sQ0FBRTlCLHFCQUFxQixDQUFDNkIsZUFBdEIsQ0FBdUMsbUJBQXZDLENBQUYsQ0FBTixDQUF1RVUsSUFBdkUsQ0FDVyw4RUFDQ3dILE9BREQsR0FFQSxRQUhYO0FBS0E7QUFJRDtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBQ0EsU0FBU29ELDJDQUFULEdBQXNEO0FBQ3JEckwsRUFBQUEsTUFBTSxDQUFFLHVEQUFGLENBQU4sQ0FBaUUyQixXQUFqRSxDQUE4RSxzQkFBOUU7QUFDQTtBQUVEO0FBQ0E7QUFDQTs7O0FBQ0EsU0FBUzBLLDJDQUFULEdBQXNEO0FBQ3JEck0sRUFBQUEsTUFBTSxDQUFFLHVEQUFGLENBQU4sQ0FBa0V5SCxRQUFsRSxDQUE0RSxzQkFBNUU7QUFDQTtBQUVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUNBLFNBQVN5Rix3Q0FBVCxHQUFtRDtBQUMvQyxNQUFLbE4sTUFBTSxDQUFFLHVEQUFGLENBQU4sQ0FBa0VtRCxRQUFsRSxDQUE0RSxzQkFBNUUsQ0FBTCxFQUEyRztBQUM3RyxXQUFPLElBQVA7QUFDQSxHQUZFLE1BRUk7QUFDTixXQUFPLEtBQVA7QUFDQTtBQUNEIiwic291cmNlc0NvbnRlbnQiOlsiXCJ1c2Ugc3RyaWN0XCI7XHJcblxyXG4vKipcclxuICogUmVxdWVzdCBPYmplY3RcclxuICogSGVyZSB3ZSBjYW4gIGRlZmluZSBTZWFyY2ggcGFyYW1ldGVycyBhbmQgVXBkYXRlIGl0IGxhdGVyLCAgd2hlbiAgc29tZSBwYXJhbWV0ZXIgd2FzIGNoYW5nZWRcclxuICpcclxuICovXHJcblxyXG52YXIgd3BiY19hanhfYXZhaWxhYmlsaXR5ID0gKGZ1bmN0aW9uICggb2JqLCAkKSB7XHJcblxyXG5cdC8vIFNlY3VyZSBwYXJhbWV0ZXJzIGZvciBBamF4XHQtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHR2YXIgcF9zZWN1cmUgPSBvYmouc2VjdXJpdHlfb2JqID0gb2JqLnNlY3VyaXR5X29iaiB8fCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdHVzZXJfaWQ6IDAsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdG5vbmNlICA6ICcnLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRsb2NhbGUgOiAnJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB9O1xyXG5cclxuXHRvYmouc2V0X3NlY3VyZV9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5LCBwYXJhbV92YWwgKSB7XHJcblx0XHRwX3NlY3VyZVsgcGFyYW1fa2V5IF0gPSBwYXJhbV92YWw7XHJcblx0fTtcclxuXHJcblx0b2JqLmdldF9zZWN1cmVfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSApIHtcclxuXHRcdHJldHVybiBwX3NlY3VyZVsgcGFyYW1fa2V5IF07XHJcblx0fTtcclxuXHJcblxyXG5cdC8vIExpc3RpbmcgU2VhcmNoIHBhcmFtZXRlcnNcdC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHZhciBwX2xpc3RpbmcgPSBvYmouc2VhcmNoX3JlcXVlc3Rfb2JqID0gb2JqLnNlYXJjaF9yZXF1ZXN0X29iaiB8fCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIHNvcnQgICAgICAgICAgICA6IFwiYm9va2luZ19pZFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyBzb3J0X3R5cGUgICAgICAgOiBcIkRFU0NcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gcGFnZV9udW0gICAgICAgIDogMSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gcGFnZV9pdGVtc19jb3VudDogMTAsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIGNyZWF0ZV9kYXRlICAgICA6IFwiXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIGtleXdvcmQgICAgICAgICA6IFwiXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIHNvdXJjZSAgICAgICAgICA6IFwiXCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH07XHJcblxyXG5cdG9iai5zZWFyY2hfc2V0X2FsbF9wYXJhbXMgPSBmdW5jdGlvbiAoIHJlcXVlc3RfcGFyYW1fb2JqICkge1xyXG5cdFx0cF9saXN0aW5nID0gcmVxdWVzdF9wYXJhbV9vYmo7XHJcblx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9nZXRfYWxsX3BhcmFtcyA9IGZ1bmN0aW9uICgpIHtcclxuXHRcdHJldHVybiBwX2xpc3Rpbmc7XHJcblx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9nZXRfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSApIHtcclxuXHRcdHJldHVybiBwX2xpc3RpbmdbIHBhcmFtX2tleSBdO1xyXG5cdH07XHJcblxyXG5cdG9iai5zZWFyY2hfc2V0X3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXksIHBhcmFtX3ZhbCApIHtcclxuXHRcdC8vIGlmICggQXJyYXkuaXNBcnJheSggcGFyYW1fdmFsICkgKXtcclxuXHRcdC8vIFx0cGFyYW1fdmFsID0gSlNPTi5zdHJpbmdpZnkoIHBhcmFtX3ZhbCApO1xyXG5cdFx0Ly8gfVxyXG5cdFx0cF9saXN0aW5nWyBwYXJhbV9rZXkgXSA9IHBhcmFtX3ZhbDtcclxuXHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX3NldF9wYXJhbXNfYXJyID0gZnVuY3Rpb24oIHBhcmFtc19hcnIgKXtcclxuXHRcdF8uZWFjaCggcGFyYW1zX2FyciwgZnVuY3Rpb24gKCBwX3ZhbCwgcF9rZXksIHBfZGF0YSApe1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIERlZmluZSBkaWZmZXJlbnQgU2VhcmNoICBwYXJhbWV0ZXJzIGZvciByZXF1ZXN0XHJcblx0XHRcdHRoaXMuc2VhcmNoX3NldF9wYXJhbSggcF9rZXksIHBfdmFsICk7XHJcblx0XHR9ICk7XHJcblx0fVxyXG5cclxuXHJcblx0Ly8gT3RoZXIgcGFyYW1ldGVycyBcdFx0XHQtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHR2YXIgcF9vdGhlciA9IG9iai5vdGhlcl9vYmogPSBvYmoub3RoZXJfb2JqIHx8IHsgfTtcclxuXHJcblx0b2JqLnNldF9vdGhlcl9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5LCBwYXJhbV92YWwgKSB7XHJcblx0XHRwX290aGVyWyBwYXJhbV9rZXkgXSA9IHBhcmFtX3ZhbDtcclxuXHR9O1xyXG5cclxuXHRvYmouZ2V0X290aGVyX3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXkgKSB7XHJcblx0XHRyZXR1cm4gcF9vdGhlclsgcGFyYW1fa2V5IF07XHJcblx0fTtcclxuXHJcblxyXG5cdHJldHVybiBvYmo7XHJcbn0oIHdwYmNfYWp4X2F2YWlsYWJpbGl0eSB8fCB7fSwgalF1ZXJ5ICkpO1xyXG5cclxudmFyIHdwYmNfYWp4X2Jvb2tpbmdzID0gW107XHJcblxyXG4vKipcclxuICogICBTaG93IENvbnRlbnQgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBTaG93IENvbnRlbnQgLSBDYWxlbmRhciBhbmQgVUkgZWxlbWVudHNcclxuICpcclxuICogQHBhcmFtIGFqeF9kYXRhX2FyclxyXG4gKiBAcGFyYW0gYWp4X3NlYXJjaF9wYXJhbXNcclxuICogQHBhcmFtIGFqeF9jbGVhbmVkX3BhcmFtc1xyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYXZhaWxhYmlsaXR5X19wYWdlX2NvbnRlbnRfX3Nob3coIGFqeF9kYXRhX2FyciwgYWp4X3NlYXJjaF9wYXJhbXMgLCBhanhfY2xlYW5lZF9wYXJhbXMgKXtcclxuXHJcblx0dmFyIHRlbXBsYXRlX19hdmFpbGFiaWxpdHlfbWFpbl9wYWdlX2NvbnRlbnQgPSB3cC50ZW1wbGF0ZSggJ3dwYmNfYWp4X2F2YWlsYWJpbGl0eV9tYWluX3BhZ2VfY29udGVudCcgKTtcclxuXHJcblx0Ly8gQ29udGVudFxyXG5cdGpRdWVyeSggd3BiY19hanhfYXZhaWxhYmlsaXR5LmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbCggdGVtcGxhdGVfX2F2YWlsYWJpbGl0eV9tYWluX3BhZ2VfY29udGVudCgge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfZGF0YScgICAgICAgICAgICAgIDogYWp4X2RhdGFfYXJyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfc2VhcmNoX3BhcmFtcycgICAgIDogYWp4X3NlYXJjaF9wYXJhbXMsXHRcdFx0XHRcdFx0XHRcdC8vICRfUkVRVUVTVFsgJ3NlYXJjaF9wYXJhbXMnIF1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X2NsZWFuZWRfcGFyYW1zJyAgICA6IGFqeF9jbGVhbmVkX3BhcmFtc1xyXG5cdFx0XHRcdFx0XHRcdFx0XHR9ICkgKTtcclxuXHJcblx0alF1ZXJ5KCAnLndwYmNfcHJvY2Vzc2luZy53cGJjX3NwaW4nKS5wYXJlbnQoKS5wYXJlbnQoKS5wYXJlbnQoKS5wYXJlbnQoICdbaWRePVwid3BiY19ub3RpY2VfXCJdJyApLmhpZGUoKTtcclxuXHQvLyBMb2FkIGNhbGVuZGFyXHJcblx0d3BiY19hanhfYXZhaWxhYmlsaXR5X19jYWxlbmRhcl9fc2hvdygge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3Jlc291cmNlX2lkJyAgICAgICA6IGFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfbm9uY2VfY2FsZW5kYXInOiBhanhfZGF0YV9hcnIuYWp4X25vbmNlX2NhbGVuZGFyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9kYXRhX2FycicgICAgICAgICAgOiBhanhfZGF0YV9hcnIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X2NsZWFuZWRfcGFyYW1zJyAgICA6IGFqeF9jbGVhbmVkX3BhcmFtc1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHJcblxyXG5cdC8qKlxyXG5cdCAqIFRyaWdnZXIgZm9yIGRhdGVzIHNlbGVjdGlvbiBpbiB0aGUgYm9va2luZyBmb3JtXHJcblx0ICpcclxuXHQgKiBqUXVlcnkoIHdwYmNfYWp4X2F2YWlsYWJpbGl0eS5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSApLm9uKCd3cGJjX3BhZ2VfY29udGVudF9sb2FkZWQnLCBmdW5jdGlvbihldmVudCwgYWp4X2RhdGFfYXJyLCBhanhfc2VhcmNoX3BhcmFtcyAsIGFqeF9jbGVhbmVkX3BhcmFtcykgeyAuLi4gfSApO1xyXG5cdCAqL1xyXG5cdGpRdWVyeSggd3BiY19hanhfYXZhaWxhYmlsaXR5LmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkudHJpZ2dlciggJ3dwYmNfcGFnZV9jb250ZW50X2xvYWRlZCcsIFsgYWp4X2RhdGFfYXJyLCBhanhfc2VhcmNoX3BhcmFtcyAsIGFqeF9jbGVhbmVkX3BhcmFtcyBdICk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogU2hvdyBpbmxpbmUgbW9udGggdmlldyBjYWxlbmRhciAgICAgICAgICAgICAgd2l0aCBhbGwgcHJlZGVmaW5lZCBDU1MgKHNpemVzIGFuZCBjaGVjayBpbi9vdXQsICB0aW1lcyBjb250YWluZXJzKVxyXG4gKiBAcGFyYW0ge29ian0gY2FsZW5kYXJfcGFyYW1zX2FyclxyXG5cdFx0XHR7XHJcblx0XHRcdFx0J3Jlc291cmNlX2lkJyAgICAgICBcdDogYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cdFx0XHRcdCdhanhfbm9uY2VfY2FsZW5kYXInXHQ6IGFqeF9kYXRhX2Fyci5hanhfbm9uY2VfY2FsZW5kYXIsXHJcblx0XHRcdFx0J2FqeF9kYXRhX2FycicgICAgICAgICAgOiBhanhfZGF0YV9hcnIgPSB7IGFqeF9ib29raW5nX3Jlc291cmNlczpbXSwgYm9va2VkX2RhdGVzOiB7fSwgcmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXM6W10sIHNlYXNvbl9hdmFpbGFiaWxpdHk6e30sLi4uLiB9XHJcblx0XHRcdFx0J2FqeF9jbGVhbmVkX3BhcmFtcycgICAgOiB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRjYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZTogXCJkeW5hbWljXCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX19zdGFydF93ZWVrX2RheTogXCIwXCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX190aW1lc2xvdF9kYXlfYmdfYXNfYXZhaWxhYmxlOiBcIlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRjYWxlbmRhcl9fdmlld19fY2VsbF9oZWlnaHQ6IFwiXCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNhbGVuZGFyX192aWV3X19tb250aHNfaW5fcm93OiA0XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRjYWxlbmRhcl9fdmlld19fdmlzaWJsZV9tb250aHM6IDEyXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRjYWxlbmRhcl9fdmlld19fd2lkdGg6IFwiMTAwJVwiXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfYXZhaWxhYmlsaXR5OiBcInVuYXZhaWxhYmxlXCJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRhdGVzX3NlbGVjdGlvbjogXCIyMDIzLTAzLTE0IH4gMjAyMy0wMy0xNlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRkb19hY3Rpb246IFwic2V0X2F2YWlsYWJpbGl0eVwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRyZXNvdXJjZV9pZDogMVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0dWlfY2xpY2tlZF9lbGVtZW50X2lkOiBcIndwYmNfYXZhaWxhYmlsaXR5X2FwcGx5X2J0blwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHR1aV91c3JfX2F2YWlsYWJpbGl0eV9zZWxlY3RlZF90b29sYmFyOiBcImluZm9cIlxyXG5cdFx0XHRcdFx0XHRcdFx0ICBcdFx0IH1cclxuXHRcdFx0fVxyXG4qL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2NhbGVuZGFyX19zaG93KCBjYWxlbmRhcl9wYXJhbXNfYXJyICl7XHJcblxyXG5cdC8vIFVwZGF0ZSBub25jZVxyXG5cdGpRdWVyeSggJyNhanhfbm9uY2VfY2FsZW5kYXJfc2VjdGlvbicgKS5odG1sKCBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9ub25jZV9jYWxlbmRhciApO1xyXG5cclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8vIFVwZGF0ZSBib29raW5nc1xyXG5cdGlmICggJ3VuZGVmaW5lZCcgPT0gdHlwZW9mICh3cGJjX2FqeF9ib29raW5nc1sgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdKSApeyB3cGJjX2FqeF9ib29raW5nc1sgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdID0gW107IH1cclxuXHR3cGJjX2FqeF9ib29raW5nc1sgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCBdID0gY2FsZW5kYXJfcGFyYW1zX2FyclsgJ2FqeF9kYXRhX2FycicgXVsgJ2Jvb2tlZF9kYXRlcycgXTtcclxuXHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0LyoqXHJcblx0ICogRGVmaW5lIHNob3dpbmcgbW91c2Ugb3ZlciB0b29sdGlwIG9uIHVuYXZhaWxhYmxlIGRhdGVzXHJcblx0ICogSXQncyBkZWZpbmVkLCB3aGVuIGNhbGVuZGFyIFJFRlJFU0hFRCAoY2hhbmdlIG1vbnRocyBvciBkYXlzIHNlbGVjdGlvbikgbG9hZGVkIGluIGpxdWVyeS5kYXRlcGljay53cGJjLjkuMC5qcyA6XHJcblx0ICogXHRcdCQoICdib2R5JyApLnRyaWdnZXIoICd3cGJjX2RhdGVwaWNrX2lubGluZV9jYWxlbmRhcl9yZWZyZXNoJywgLi4uXHRcdC8vRml4SW46IDkuNC40LjEzXHJcblx0ICovXHJcblx0alF1ZXJ5KCAnYm9keScgKS5vbiggJ3dwYmNfZGF0ZXBpY2tfaW5saW5lX2NhbGVuZGFyX3JlZnJlc2gnLCBmdW5jdGlvbiAoIGV2ZW50LCByZXNvdXJjZV9pZCwgaW5zdCApe1xyXG5cdFx0Ly8gaW5zdC5kcERpdiAgaXQnczogIDxkaXYgY2xhc3M9XCJkYXRlcGljay1pbmxpbmUgZGF0ZXBpY2stbXVsdGlcIiBzdHlsZT1cIndpZHRoOiAxNzcxMnB4O1wiPi4uLi48L2Rpdj5cclxuXHRcdGluc3QuZHBEaXYuZmluZCggJy5zZWFzb25fdW5hdmFpbGFibGUsLmJlZm9yZV9hZnRlcl91bmF2YWlsYWJsZSwud2Vla2RheXNfdW5hdmFpbGFibGUnICkub24oICdtb3VzZW92ZXInLCBmdW5jdGlvbiAoIHRoaXNfZXZlbnQgKXtcclxuXHRcdFx0Ly8gYWxzbyBhdmFpbGFibGUgdGhlc2UgdmFyczogXHRyZXNvdXJjZV9pZCwgakNhbENvbnRhaW5lciwgaW5zdFxyXG5cdFx0XHR2YXIgakNlbGwgPSBqUXVlcnkoIHRoaXNfZXZlbnQuY3VycmVudFRhcmdldCApO1xyXG5cdFx0XHR3cGJjX2F2eV9fc2hvd190b29sdGlwX19mb3JfZWxlbWVudCggakNlbGwsIGNhbGVuZGFyX3BhcmFtc19hcnJbICdhanhfZGF0YV9hcnInIF1bJ3BvcG92ZXJfaGludHMnXSApO1xyXG5cdFx0fSk7XHJcblxyXG5cdH1cdCk7XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0LyoqXHJcblx0ICogRGVmaW5lIGhlaWdodCBvZiB0aGUgY2FsZW5kYXIgIGNlbGxzLCBcdGFuZCAgbW91c2Ugb3ZlciB0b29sdGlwcyBhdCAgc29tZSB1bmF2YWlsYWJsZSBkYXRlc1xyXG5cdCAqIEl0J3MgZGVmaW5lZCwgd2hlbiBjYWxlbmRhciBsb2FkZWQgaW4ganF1ZXJ5LmRhdGVwaWNrLndwYmMuOS4wLmpzIDpcclxuXHQgKiBcdFx0JCggJ2JvZHknICkudHJpZ2dlciggJ3dwYmNfZGF0ZXBpY2tfaW5saW5lX2NhbGVuZGFyX2xvYWRlZCcsIC4uLlx0XHQvL0ZpeEluOiA5LjQuNC4xMlxyXG5cdCAqL1xyXG5cdGpRdWVyeSggJ2JvZHknICkub24oICd3cGJjX2RhdGVwaWNrX2lubGluZV9jYWxlbmRhcl9sb2FkZWQnLCBmdW5jdGlvbiAoIGV2ZW50LCByZXNvdXJjZV9pZCwgakNhbENvbnRhaW5lciwgaW5zdCApe1xyXG5cclxuXHRcdC8vIFJlbW92ZSBoaWdobGlnaHQgZGF5IGZvciB0b2RheSAgZGF0ZVxyXG5cdFx0alF1ZXJ5KCAnLmRhdGVwaWNrLWRheXMtY2VsbC5kYXRlcGljay10b2RheS5kYXRlcGljay1kYXlzLWNlbGwtb3ZlcicgKS5yZW1vdmVDbGFzcyggJ2RhdGVwaWNrLWRheXMtY2VsbC1vdmVyJyApO1xyXG5cclxuXHRcdC8vIFNldCBoZWlnaHQgb2YgY2FsZW5kYXIgIGNlbGxzIGlmIGRlZmluZWQgdGhpcyBvcHRpb25cclxuXHRcdGlmICggJycgIT09IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X19jZWxsX2hlaWdodCApe1xyXG5cdFx0XHRqUXVlcnkoICdoZWFkJyApLmFwcGVuZCggJzxzdHlsZSB0eXBlPVwidGV4dC9jc3NcIj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnLmhhc0RhdGVwaWNrIC5kYXRlcGljay1pbmxpbmUgLmRhdGVwaWNrLXRpdGxlLXJvdyB0aCwgJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdCsgJy5oYXNEYXRlcGljayAuZGF0ZXBpY2staW5saW5lIC5kYXRlcGljay1kYXlzLWNlbGwgeydcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCsgJ2hlaWdodDogJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X19jZWxsX2hlaWdodCArICcgIWltcG9ydGFudDsnXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnfSdcclxuXHRcdFx0XHRcdFx0XHRcdFx0Kyc8L3N0eWxlPicgKTtcclxuXHRcdH1cclxuXHJcblx0XHQvLyBEZWZpbmUgc2hvd2luZyBtb3VzZSBvdmVyIHRvb2x0aXAgb24gdW5hdmFpbGFibGUgZGF0ZXNcclxuXHRcdGpDYWxDb250YWluZXIuZmluZCggJy5zZWFzb25fdW5hdmFpbGFibGUsLmJlZm9yZV9hZnRlcl91bmF2YWlsYWJsZSwud2Vla2RheXNfdW5hdmFpbGFibGUnICkub24oICdtb3VzZW92ZXInLCBmdW5jdGlvbiAoIHRoaXNfZXZlbnQgKXtcclxuXHRcdFx0Ly8gYWxzbyBhdmFpbGFibGUgdGhlc2UgdmFyczogXHRyZXNvdXJjZV9pZCwgakNhbENvbnRhaW5lciwgaW5zdFxyXG5cdFx0XHR2YXIgakNlbGwgPSBqUXVlcnkoIHRoaXNfZXZlbnQuY3VycmVudFRhcmdldCApO1xyXG5cdFx0XHR3cGJjX2F2eV9fc2hvd190b29sdGlwX19mb3JfZWxlbWVudCggakNlbGwsIGNhbGVuZGFyX3BhcmFtc19hcnJbICdhanhfZGF0YV9hcnInIF1bJ3BvcG92ZXJfaGludHMnXSApO1xyXG5cdFx0fSk7XHJcblx0fSApO1xyXG5cclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8vIERlZmluZSB3aWR0aCBvZiBlbnRpcmUgY2FsZW5kYXJcclxuXHR2YXIgd2lkdGggPSAgICd3aWR0aDonXHRcdCsgICBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fd2lkdGggKyAnOyc7XHRcdFx0XHRcdC8vIHZhciB3aWR0aCA9ICd3aWR0aDoxMDAlO21heC13aWR0aDoxMDAlOyc7XHJcblxyXG5cdGlmICggICAoIHVuZGVmaW5lZCAhPSBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fdmlld19fbWF4X3dpZHRoIClcclxuXHRcdCYmICggJycgIT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX21heF93aWR0aCApXHJcblx0KXtcclxuXHRcdHdpZHRoICs9ICdtYXgtd2lkdGg6JyBcdCsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX21heF93aWR0aCArICc7JztcclxuXHR9IGVsc2Uge1xyXG5cdFx0d2lkdGggKz0gJ21heC13aWR0aDonIFx0KyAoIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X19tb250aHNfaW5fcm93ICogMzQxICkgKyAncHg7JztcclxuXHR9XHJcblxyXG5cdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0Ly8gQWRkIGNhbGVuZGFyIGNvbnRhaW5lcjogXCJDYWxlbmRhciBpcyBsb2FkaW5nLi4uXCIgIGFuZCB0ZXh0YXJlYVxyXG5cdGpRdWVyeSggJy53cGJjX2FqeF9hdnlfX2NhbGVuZGFyJyApLmh0bWwoXHJcblxyXG5cdFx0JzxkaXYgY2xhc3M9XCInXHQrICcgYmtfY2FsZW5kYXJfZnJhbWUnXHJcblx0XHRcdFx0XHRcdCsgJyBtb250aHNfbnVtX2luX3Jvd18nICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX21vbnRoc19pbl9yb3dcclxuXHRcdFx0XHRcdFx0KyAnIGNhbF9tb250aF9udW1fJyBcdCsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzXHJcblx0XHRcdFx0XHRcdCsgJyAnIFx0XHRcdFx0XHQrIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX190aW1lc2xvdF9kYXlfYmdfYXNfYXZhaWxhYmxlIFx0XHRcdFx0Ly8gJ3dwYmNfdGltZXNsb3RfZGF5X2JnX2FzX2F2YWlsYWJsZScgfHwgJydcclxuXHRcdFx0XHQrICdcIiAnXHJcblx0XHRcdCsgJ3N0eWxlPVwiJyArIHdpZHRoICsgJ1wiPidcclxuXHJcblx0XHRcdFx0KyAnPGRpdiBpZD1cImNhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCArICdcIj4nICsgJ0NhbGVuZGFyIGlzIGxvYWRpbmcuLi4nICsgJzwvZGl2PidcclxuXHJcblx0XHQrICc8L2Rpdj4nXHJcblxyXG5cdFx0KyAnPHRleHRhcmVhICAgICAgaWQ9XCJkYXRlX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCArICdcIidcclxuXHRcdFx0XHRcdCsgJyBuYW1lPVwiZGF0ZV9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgKyAnXCInXHJcblx0XHRcdFx0XHQrICcgYXV0b2NvbXBsZXRlPVwib2ZmXCInXHJcblx0XHRcdFx0XHQrICcgc3R5bGU9XCJkaXNwbGF5Om5vbmU7d2lkdGg6MTAwJTtoZWlnaHQ6MTBlbTttYXJnaW46MmVtIDAgMDtcIj48L3RleHRhcmVhPidcclxuXHQpO1xyXG5cclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHZhciBjYWxfcGFyYW1fYXJyID0ge1xyXG5cdFx0XHRcdFx0XHRcdCdodG1sX2lkJyAgICAgICAgICAgOiAnY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHRcdFx0XHRcdFx0XHQndGV4dF9pZCcgICAgICAgICAgIDogJ2RhdGVfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5yZXNvdXJjZV9pZCxcclxuXHJcblx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19zdGFydF93ZWVrX2RheSc6IFx0ICBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fc3RhcnRfd2Vla19kYXksXHJcblx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRocyc6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRocyxcclxuXHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUnOiAgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUsXHJcblxyXG5cdFx0XHRcdFx0XHRcdCdyZXNvdXJjZV9pZCcgICAgICAgIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblx0XHRcdFx0XHRcdFx0J2FqeF9ub25jZV9jYWxlbmRhcicgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5hanhfbm9uY2VfY2FsZW5kYXIsXHJcblx0XHRcdFx0XHRcdFx0J2Jvb2tlZF9kYXRlcycgICAgICAgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5ib29rZWRfZGF0ZXMsXHJcblx0XHRcdFx0XHRcdFx0J3NlYXNvbl9hdmFpbGFiaWxpdHknOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5zZWFzb25fYXZhaWxhYmlsaXR5LFxyXG5cclxuXHRcdFx0XHRcdFx0XHQncmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXMnIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIucmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXMsXHJcblxyXG5cdFx0XHRcdFx0XHRcdCdwb3BvdmVyX2hpbnRzJzogY2FsZW5kYXJfcGFyYW1zX2FyclsgJ2FqeF9kYXRhX2FycicgXVsncG9wb3Zlcl9oaW50cyddXHRcdC8vIHsnc2Vhc29uX3VuYXZhaWxhYmxlJzonLi4uJywnd2Vla2RheXNfdW5hdmFpbGFibGUnOicuLi4nLCdiZWZvcmVfYWZ0ZXJfdW5hdmFpbGFibGUnOicuLi4nLH1cclxuXHRcdFx0XHRcdFx0fTtcclxuXHR3cGJjX3Nob3dfaW5saW5lX2Jvb2tpbmdfY2FsZW5kYXIoIGNhbF9wYXJhbV9hcnIgKTtcclxuXHJcblx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHQvKipcclxuXHQgKiBPbiBjbGljayBBVkFJTEFCTEUgfCAgVU5BVkFJTEFCTEUgYnV0dG9uICBpbiB3aWRnZXRcdC1cdG5lZWQgdG8gIGNoYW5nZSBoZWxwIGRhdGVzIHRleHRcclxuXHQgKi9cclxuXHRqUXVlcnkoICcud3BiY19yYWRpb19fc2V0X2RheXNfYXZhaWxhYmlsaXR5JyApLm9uKCdjaGFuZ2UnLCBmdW5jdGlvbiAoIGV2ZW50LCByZXNvdXJjZV9pZCwgaW5zdCApe1xyXG5cdFx0d3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfc2VsZWN0KCBqUXVlcnkoICcjJyArIGNhbF9wYXJhbV9hcnIudGV4dF9pZCApLnZhbCgpICwgY2FsX3BhcmFtX2FyciApO1xyXG5cdH0pO1xyXG5cclxuXHQvLyBTaG93IFx0J1NlbGVjdCBkYXlzICBpbiBjYWxlbmRhciB0aGVuIHNlbGVjdCBBdmFpbGFibGUgIC8gIFVuYXZhaWxhYmxlIHN0YXR1cyBhbmQgY2xpY2sgQXBwbHkgYXZhaWxhYmlsaXR5IGJ1dHRvbi4nXHJcblx0alF1ZXJ5KCAnI3dwYmNfdG9vbGJhcl9kYXRlc19oaW50JykuaHRtbCggICAgICc8ZGl2IGNsYXNzPVwidWlfZWxlbWVudFwiPjxzcGFuIGNsYXNzPVwid3BiY191aV9jb250cm9sIHdwYmNfdWlfYWRkb24gd3BiY19oZWxwX3RleHRcIiA+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCsgY2FsX3BhcmFtX2Fyci5wb3BvdmVyX2hpbnRzLnRvb2xiYXJfdGV4dFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8L3NwYW4+PC9kaXY+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiBcdExvYWQgRGF0ZXBpY2sgSW5saW5lIGNhbGVuZGFyXHJcbiAqXHJcbiAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHRcdGV4YW1wbGU6e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2h0bWxfaWQnICAgICAgICAgICA6ICdjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLnJlc291cmNlX2lkLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3RleHRfaWQnICAgICAgICAgICA6ICdkYXRlX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19zdGFydF93ZWVrX2RheSc6IFx0ICBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9jbGVhbmVkX3BhcmFtcy5jYWxlbmRhcl9fc3RhcnRfd2Vla19kYXksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzJzogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMuY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlJzogIGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2NsZWFuZWRfcGFyYW1zLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlLFxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdyZXNvdXJjZV9pZCcgICAgICAgIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfY2xlYW5lZF9wYXJhbXMucmVzb3VyY2VfaWQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X25vbmNlX2NhbGVuZGFyJyA6IGNhbGVuZGFyX3BhcmFtc19hcnIuYWp4X2RhdGFfYXJyLmFqeF9ub25jZV9jYWxlbmRhcixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29rZWRfZGF0ZXMnICAgICAgIDogY2FsZW5kYXJfcGFyYW1zX2Fyci5hanhfZGF0YV9hcnIuYm9va2VkX2RhdGVzLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXNvbl9hdmFpbGFiaWxpdHknOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5zZWFzb25fYXZhaWxhYmlsaXR5LFxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdyZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlcycgOiBjYWxlbmRhcl9wYXJhbXNfYXJyLmFqeF9kYXRhX2Fyci5yZXNvdXJjZV91bmF2YWlsYWJsZV9kYXRlc1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuICogQHJldHVybnMge2Jvb2xlYW59XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX3Nob3dfaW5saW5lX2Jvb2tpbmdfY2FsZW5kYXIoIGNhbGVuZGFyX3BhcmFtc19hcnIgKXtcclxuXHJcblx0aWYgKFxyXG5cdFx0ICAgKCAwID09PSBqUXVlcnkoICcjJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuaHRtbF9pZCApLmxlbmd0aCApXHRcdFx0XHRcdFx0XHQvLyBJZiBjYWxlbmRhciBET00gZWxlbWVudCBub3QgZXhpc3QgdGhlbiBleGlzdFxyXG5cdFx0fHwgKCB0cnVlID09PSBqUXVlcnkoICcjJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuaHRtbF9pZCApLmhhc0NsYXNzKCAnaGFzRGF0ZXBpY2snICkgKVx0Ly8gSWYgdGhlIGNhbGVuZGFyIHdpdGggdGhlIHNhbWUgQm9va2luZyByZXNvdXJjZSBhbHJlYWR5ICBoYXMgYmVlbiBhY3RpdmF0ZWQsIHRoZW4gZXhpc3QuXHJcblx0KXtcclxuXHQgICByZXR1cm4gZmFsc2U7XHJcblx0fVxyXG5cclxuXHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdC8vIENvbmZpZ3VyZSBhbmQgc2hvdyBjYWxlbmRhclxyXG5cdGpRdWVyeSggJyMnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5odG1sX2lkICkudGV4dCggJycgKTtcclxuXHRqUXVlcnkoICcjJyArIGNhbGVuZGFyX3BhcmFtc19hcnIuaHRtbF9pZCApLmRhdGVwaWNrKHtcclxuXHRcdFx0XHRcdGJlZm9yZVNob3dEYXk6IFx0ZnVuY3Rpb24gKCBkYXRlICl7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0cmV0dXJuIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19hcHBseV9jc3NfdG9fZGF5cyggZGF0ZSwgY2FsZW5kYXJfcGFyYW1zX2FyciwgdGhpcyApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxyXG4gICAgICAgICAgICAgICAgICAgIG9uU2VsZWN0OiBcdCAgXHRmdW5jdGlvbiAoIGRhdGUgKXtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRqUXVlcnkoICcjJyArIGNhbGVuZGFyX3BhcmFtc19hcnIudGV4dF9pZCApLnZhbCggZGF0ZSApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdC8vd3BiY19ibGlua19lbGVtZW50KCcud3BiY193aWRnZXRfYXZhaWxhYmxlX3VuYXZhaWxhYmxlJywgMywgMjIwKTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRyZXR1cm4gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfc2VsZWN0KCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCB0aGlzICk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdH0sXHJcbiAgICAgICAgICAgICAgICAgICAgb25Ib3ZlcjogXHRcdGZ1bmN0aW9uICggdmFsdWUsIGRhdGUgKXtcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0Ly93cGJjX2F2eV9fcHJlcGFyZV90b29sdGlwX19pbl9jYWxlbmRhciggdmFsdWUsIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIHRoaXMgKTtcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0cmV0dXJuIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19vbl9kYXlzX2hvdmVyKCB2YWx1ZSwgZGF0ZSwgY2FsZW5kYXJfcGFyYW1zX2FyciwgdGhpcyApO1xyXG5cdFx0XHRcdFx0XHRcdFx0XHR9LFxyXG4gICAgICAgICAgICAgICAgICAgIG9uQ2hhbmdlTW9udGhZZWFyOlx0bnVsbCxcclxuICAgICAgICAgICAgICAgICAgICBzaG93T246IFx0XHRcdCdib3RoJyxcclxuICAgICAgICAgICAgICAgICAgICBudW1iZXJPZk1vbnRoczogXHRjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRocyxcclxuICAgICAgICAgICAgICAgICAgICBzdGVwTW9udGhzOlx0XHRcdDEsXHJcbiAgICAgICAgICAgICAgICAgICAgcHJldlRleHQ6IFx0XHRcdCcmbGFxdW87JyxcclxuICAgICAgICAgICAgICAgICAgICBuZXh0VGV4dDogXHRcdFx0JyZyYXF1bzsnLFxyXG4gICAgICAgICAgICAgICAgICAgIGRhdGVGb3JtYXQ6IFx0XHQneXktbW0tZGQnLC8vICdkZC5tbS55eScsXHJcbiAgICAgICAgICAgICAgICAgICAgY2hhbmdlTW9udGg6IFx0XHRmYWxzZSxcclxuICAgICAgICAgICAgICAgICAgICBjaGFuZ2VZZWFyOiBcdFx0ZmFsc2UsXHJcbiAgICAgICAgICAgICAgICAgICAgbWluRGF0ZTogXHRcdFx0XHRcdCAwLFx0XHQvL251bGwsICAvL1Njcm9sbCBhcyBsb25nIGFzIHlvdSBuZWVkXHJcblx0XHRcdFx0XHRtYXhEYXRlOiBcdFx0XHRcdFx0JzEweScsXHQvLyBtaW5EYXRlOiBuZXcgRGF0ZSgyMDIwLCAyLCAxKSwgbWF4RGF0ZTogbmV3IERhdGUoMjAyMCwgOSwgMzEpLCBcdC8vIEFiaWxpdHkgdG8gc2V0IGFueSAgc3RhcnQgYW5kIGVuZCBkYXRlIGluIGNhbGVuZGFyXHJcbiAgICAgICAgICAgICAgICAgICAgc2hvd1N0YXR1czogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGNsb3NlQXRUb3A6IFx0XHRmYWxzZSxcclxuICAgICAgICAgICAgICAgICAgICBmaXJzdERheTpcdFx0XHRjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19zdGFydF93ZWVrX2RheSxcclxuICAgICAgICAgICAgICAgICAgICBnb3RvQ3VycmVudDogXHRcdGZhbHNlLFxyXG4gICAgICAgICAgICAgICAgICAgIGhpZGVJZk5vUHJldk5leHQ6XHR0cnVlLFxyXG4gICAgICAgICAgICAgICAgICAgIG11bHRpU2VwYXJhdG9yOiBcdCcsICcsXHJcblx0XHRcdFx0XHRtdWx0aVNlbGVjdDogKCgnZHluYW1pYycgPT0gY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSkgPyAwIDogMzY1KSxcdFx0XHQvLyBNYXhpbXVtIG51bWJlciBvZiBzZWxlY3RhYmxlIGRhdGVzOlx0IFNpbmdsZSBkYXkgPSAwLCAgbXVsdGkgZGF5cyA9IDM2NVxyXG5cdFx0XHRcdFx0cmFuZ2VTZWxlY3Q6ICAoJ2R5bmFtaWMnID09IGNhbGVuZGFyX3BhcmFtc19hcnIuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUpLFxyXG5cdFx0XHRcdFx0cmFuZ2VTZXBhcmF0b3I6IFx0JyB+ICcsXHRcdFx0XHRcdC8vJyAtICcsXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gc2hvd1dlZWtzOiB0cnVlLFxyXG4gICAgICAgICAgICAgICAgICAgIHVzZVRoZW1lUm9sbGVyOlx0XHRmYWxzZVxyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICk7XHJcblxyXG5cdHJldHVybiAgdHJ1ZTtcclxufVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogQXBwbHkgQ1NTIHRvIGNhbGVuZGFyIGRhdGUgY2VsbHNcclxuXHQgKlxyXG5cdCAqIEBwYXJhbSBkYXRlXHRcdFx0XHRcdC0gIEphdmFTY3JpcHQgRGF0ZSBPYmo6ICBcdFx0TW9uIERlYyAxMSAyMDIzIDAwOjAwOjAwIEdNVCswMjAwIChFYXN0ZXJuIEV1cm9wZWFuIFN0YW5kYXJkIFRpbWUpXHJcblx0ICogQHBhcmFtIGNhbGVuZGFyX3BhcmFtc19hcnJcdC0gIENhbGVuZGFyIFNldHRpbmdzIE9iamVjdDogIFx0e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImh0bWxfaWRcIjogXCJjYWxlbmRhcl9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInRleHRfaWRcIjogXCJkYXRlX2Jvb2tpbmc0XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX3N0YXJ0X3dlZWtfZGF5XCI6IDEsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiY2FsZW5kYXJfX3ZpZXdfX3Zpc2libGVfbW9udGhzXCI6IDEyLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcInJlc291cmNlX2lkXCI6IDQsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYWp4X25vbmNlX2NhbGVuZGFyXCI6IFwiPGlucHV0IHR5cGU9XFxcImhpZGRlblxcXCIgLi4uIC8+XCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIFwiYm9va2VkX2RhdGVzXCI6IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCIxMi0yOC0yMDIyXCI6IFtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2RhdGVcIjogXCIyMDIyLTEyLTI4IDAwOjAwOjAwXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJhcHByb3ZlZFwiOiBcIjFcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcImJvb2tpbmdfaWRcIjogXCIyNlwiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRdLCAuLi5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnc2Vhc29uX2F2YWlsYWJpbGl0eSc6e1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMjAyMy0wMS0wOVwiOiB0cnVlLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMjAyMy0wMS0xMFwiOiB0cnVlLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMjAyMy0wMS0xMVwiOiB0cnVlLCAuLi5cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICB9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0ICogQHBhcmFtIGRhdGVwaWNrX3RoaXNcdFx0XHQtIHRoaXMgb2YgZGF0ZXBpY2sgT2JqXHJcblx0ICpcclxuXHQgKiBAcmV0dXJucyBbYm9vbGVhbixzdHJpbmddXHQtIFsge3RydWUgLWF2YWlsYWJsZSB8IGZhbHNlIC0gdW5hdmFpbGFibGV9LCAnQ1NTIGNsYXNzZXMgZm9yIGNhbGVuZGFyIGRheSBjZWxsJyBdXHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX2FwcGx5X2Nzc190b19kYXlzKCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzICl7XHJcblxyXG5cdFx0dmFyIHRvZGF5X2RhdGUgPSBuZXcgRGF0ZSggd3BiY190b2RheVsgMCBdLCAocGFyc2VJbnQoIHdwYmNfdG9kYXlbIDEgXSApIC0gMSksIHdwYmNfdG9kYXlbIDIgXSwgMCwgMCwgMCApO1xyXG5cclxuXHRcdHZhciBjbGFzc19kYXkgID0gKCBkYXRlLmdldE1vbnRoKCkgKyAxICkgKyAnLScgKyBkYXRlLmdldERhdGUoKSArICctJyArIGRhdGUuZ2V0RnVsbFllYXIoKTtcdFx0XHRcdFx0XHQvLyAnMS05LTIwMjMnXHJcblx0XHR2YXIgc3FsX2NsYXNzX2RheSA9IHdwYmNfX2dldF9fc3FsX2NsYXNzX2RhdGUoIGRhdGUgKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vICcyMDIzLTAxLTA5J1xyXG5cclxuXHRcdHZhciBjc3NfZGF0ZV9fc3RhbmRhcmQgICA9ICAnY2FsNGRhdGUtJyArIGNsYXNzX2RheTtcclxuXHRcdHZhciBjc3NfZGF0ZV9fYWRkaXRpb25hbCA9ICcgd3BiY193ZWVrZGF5XycgKyBkYXRlLmdldERheSgpICsgJyAnO1xyXG5cclxuXHRcdC8vLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuXHJcblx0XHQvLyBXRUVLREFZUyA6OiBTZXQgdW5hdmFpbGFibGUgd2VlayBkYXlzIGZyb20gLSBTZXR0aW5ncyBHZW5lcmFsIHBhZ2UgaW4gXCJBdmFpbGFiaWxpdHlcIiBzZWN0aW9uXHJcblx0XHRmb3IgKCB2YXIgaSA9IDA7IGkgPCB1c2VyX3VuYXZpbGFibGVfZGF5cy5sZW5ndGg7IGkrKyApe1xyXG5cdFx0XHRpZiAoIGRhdGUuZ2V0RGF5KCkgPT0gdXNlcl91bmF2aWxhYmxlX2RheXNbIGkgXSApIHtcclxuXHRcdFx0XHRyZXR1cm4gWyAhIWZhbHNlLCBjc3NfZGF0ZV9fc3RhbmRhcmQgKyAnIGRhdGVfdXNlcl91bmF2YWlsYWJsZScgXHQrICcgd2Vla2RheXNfdW5hdmFpbGFibGUnIF07XHJcblx0XHRcdH1cclxuXHRcdH1cclxuXHJcblx0XHQvLyBCRUZPUkVfQUZURVIgOjogU2V0IHVuYXZhaWxhYmxlIGRheXMgQmVmb3JlIC8gQWZ0ZXIgdGhlIFRvZGF5IGRhdGVcclxuXHRcdGlmICggXHQoIChkYXlzX2JldHdlZW4oIGRhdGUsIHRvZGF5X2RhdGUgKSkgPCBibG9ja19zb21lX2RhdGVzX2Zyb21fdG9kYXkgKVxyXG5cdFx0XHQgfHwgKFxyXG5cdFx0XHRcdCAgICggdHlwZW9mKCB3cGJjX2F2YWlsYWJsZV9kYXlzX251bV9mcm9tX3RvZGF5ICkgIT09ICd1bmRlZmluZWQnIClcclxuXHRcdFx0XHQmJiAoIHBhcnNlSW50KCAnMCcgKyB3cGJjX2F2YWlsYWJsZV9kYXlzX251bV9mcm9tX3RvZGF5ICkgPiAwIClcclxuXHRcdFx0XHQmJiAoIGRheXNfYmV0d2VlbiggZGF0ZSwgdG9kYXlfZGF0ZSApID4gcGFyc2VJbnQoICcwJyArIHdwYmNfYXZhaWxhYmxlX2RheXNfbnVtX2Zyb21fdG9kYXkgKSApXHJcblx0XHRcdFx0KVxyXG5cdFx0KXtcclxuXHRcdFx0cmV0dXJuIFsgISFmYWxzZSwgY3NzX2RhdGVfX3N0YW5kYXJkICsgJyBkYXRlX3VzZXJfdW5hdmFpbGFibGUnIFx0XHQrICcgYmVmb3JlX2FmdGVyX3VuYXZhaWxhYmxlJyBdO1xyXG5cdFx0fVxyXG5cclxuXHRcdC8vIFNFQVNPTlMgOjogIFx0XHRcdFx0XHRCb29raW5nID4gUmVzb3VyY2VzID4gQXZhaWxhYmlsaXR5IHBhZ2VcclxuXHRcdHZhciAgICBpc19kYXRlX2F2YWlsYWJsZSA9IGNhbGVuZGFyX3BhcmFtc19hcnIuc2Vhc29uX2F2YWlsYWJpbGl0eVsgc3FsX2NsYXNzX2RheSBdO1xyXG5cdFx0aWYgKCBmYWxzZSA9PT0gaXNfZGF0ZV9hdmFpbGFibGUgKXtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly9GaXhJbjogOS41LjQuNFxyXG5cdFx0XHRyZXR1cm4gWyAhIWZhbHNlLCBjc3NfZGF0ZV9fc3RhbmRhcmQgKyAnIGRhdGVfdXNlcl91bmF2YWlsYWJsZSdcdFx0KyAnIHNlYXNvbl91bmF2YWlsYWJsZScgXTtcclxuXHRcdH1cclxuXHJcblx0XHQvLyBSRVNPVVJDRV9VTkFWQUlMQUJMRSA6OiAgIFx0Qm9va2luZyA+IEF2YWlsYWJpbGl0eSBwYWdlXHJcblx0XHRpZiAoIHdwZGV2X2luX2FycmF5KGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfdW5hdmFpbGFibGVfZGF0ZXMsIHNxbF9jbGFzc19kYXkgKSApe1xyXG5cdFx0XHRpc19kYXRlX2F2YWlsYWJsZSA9IGZhbHNlO1xyXG5cdFx0fVxyXG5cdFx0aWYgKCAgZmFsc2UgPT09IGlzX2RhdGVfYXZhaWxhYmxlICl7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL0ZpeEluOiA5LjUuNC40XHJcblx0XHRcdHJldHVybiBbICFmYWxzZSwgY3NzX2RhdGVfX3N0YW5kYXJkICsgJyBkYXRlX3VzZXJfdW5hdmFpbGFibGUnXHRcdCsgJyByZXNvdXJjZV91bmF2YWlsYWJsZScgXTtcclxuXHRcdH1cclxuXHJcblx0XHQvLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblxyXG5cdFx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cclxuXHJcblx0XHQvLyBJcyBhbnkgYm9va2luZ3MgaW4gdGhpcyBkYXRlID9cclxuXHRcdGlmICggJ3VuZGVmaW5lZCcgIT09IHR5cGVvZiggY2FsZW5kYXJfcGFyYW1zX2Fyci5ib29rZWRfZGF0ZXNbIGNsYXNzX2RheSBdICkgKSB7XHJcblxyXG5cdFx0XHR2YXIgYm9va2luZ3NfaW5fZGF0ZSA9IGNhbGVuZGFyX3BhcmFtc19hcnIuYm9va2VkX2RhdGVzWyBjbGFzc19kYXkgXTtcclxuXHJcblxyXG5cdFx0XHRpZiAoICd1bmRlZmluZWQnICE9PSB0eXBlb2YoIGJvb2tpbmdzX2luX2RhdGVbICdzZWNfMCcgXSApICkge1x0XHRcdC8vIFwiRnVsbCBkYXlcIiBib29raW5nICAtPiAoc2Vjb25kcyA9PSAwKVxyXG5cclxuXHRcdFx0XHRjc3NfZGF0ZV9fYWRkaXRpb25hbCArPSAoICcwJyA9PT0gYm9va2luZ3NfaW5fZGF0ZVsgJ3NlY18wJyBdLmFwcHJvdmVkICkgPyAnIGRhdGUyYXBwcm92ZSAnIDogJyBkYXRlX2FwcHJvdmVkICc7XHRcdFx0XHQvLyBQZW5kaW5nID0gJzAnIHwgIEFwcHJvdmVkID0gJzEnXHJcblx0XHRcdFx0Y3NzX2RhdGVfX2FkZGl0aW9uYWwgKz0gJyBmdWxsX2RheV9ib29raW5nJztcclxuXHJcblx0XHRcdFx0cmV0dXJuIFsgIWZhbHNlLCBjc3NfZGF0ZV9fc3RhbmRhcmQgKyBjc3NfZGF0ZV9fYWRkaXRpb25hbCBdO1xyXG5cclxuXHRcdFx0fSBlbHNlIGlmICggT2JqZWN0LmtleXMoIGJvb2tpbmdzX2luX2RhdGUgKS5sZW5ndGggPiAwICl7XHRcdFx0XHQvLyBcIlRpbWUgc2xvdHNcIiBCb29raW5nc1xyXG5cclxuXHRcdFx0XHR2YXIgaXNfYXBwcm92ZWQgPSB0cnVlO1xyXG5cclxuXHRcdFx0XHRfLmVhY2goIGJvb2tpbmdzX2luX2RhdGUsIGZ1bmN0aW9uICggcF92YWwsIHBfa2V5LCBwX2RhdGEgKSB7XHJcblx0XHRcdFx0XHRpZiAoICFwYXJzZUludCggcF92YWwuYXBwcm92ZWQgKSApe1xyXG5cdFx0XHRcdFx0XHRpc19hcHByb3ZlZCA9IGZhbHNlO1xyXG5cdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0dmFyIHRzID0gcF92YWwuYm9va2luZ19kYXRlLnN1YnN0cmluZyggcF92YWwuYm9va2luZ19kYXRlLmxlbmd0aCAtIDEgKTtcclxuXHRcdFx0XHRcdGlmICggdHJ1ZSA9PT0gaXNfYm9va2luZ191c2VkX2NoZWNrX2luX291dF90aW1lICl7XHJcblx0XHRcdFx0XHRcdGlmICggdHMgPT0gJzEnICkgeyBjc3NfZGF0ZV9fYWRkaXRpb25hbCArPSAnIGNoZWNrX2luX3RpbWUnICsgKChwYXJzZUludChwX3ZhbC5hcHByb3ZlZCkpID8gJyBjaGVja19pbl90aW1lX2RhdGVfYXBwcm92ZWQnIDogJyBjaGVja19pbl90aW1lX2RhdGUyYXBwcm92ZScpOyB9XHJcblx0XHRcdFx0XHRcdGlmICggdHMgPT0gJzInICkgeyBjc3NfZGF0ZV9fYWRkaXRpb25hbCArPSAnIGNoZWNrX291dF90aW1lJyArICgocGFyc2VJbnQocF92YWwuYXBwcm92ZWQpKSA/ICcgY2hlY2tfb3V0X3RpbWVfZGF0ZV9hcHByb3ZlZCcgOiAnIGNoZWNrX291dF90aW1lX2RhdGUyYXBwcm92ZScpOyB9XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdH0pO1xyXG5cclxuXHRcdFx0XHRpZiAoICEgaXNfYXBwcm92ZWQgKXtcclxuXHRcdFx0XHRcdGNzc19kYXRlX19hZGRpdGlvbmFsICs9ICcgZGF0ZTJhcHByb3ZlIHRpbWVzcGFydGx5J1xyXG5cdFx0XHRcdH0gZWxzZSB7XHJcblx0XHRcdFx0XHRjc3NfZGF0ZV9fYWRkaXRpb25hbCArPSAnIGRhdGVfYXBwcm92ZWQgdGltZXNwYXJ0bHknXHJcblx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHRpZiAoICEgaXNfYm9va2luZ191c2VkX2NoZWNrX2luX291dF90aW1lICl7XHJcblx0XHRcdFx0XHRjc3NfZGF0ZV9fYWRkaXRpb25hbCArPSAnIHRpbWVzX2Nsb2NrJ1xyXG5cdFx0XHRcdH1cclxuXHJcblx0XHRcdH1cclxuXHJcblx0XHR9XHJcblxyXG5cdFx0Ly8tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cclxuXHRcdHJldHVybiBbIHRydWUsIGNzc19kYXRlX19zdGFuZGFyZCArIGNzc19kYXRlX19hZGRpdGlvbmFsICsgJyBkYXRlX2F2YWlsYWJsZScgXTtcclxuXHR9XHJcblxyXG5cclxuXHQvKipcclxuXHQgKiBBcHBseSBzb21lIENTUyBjbGFzc2VzLCB3aGVuIHdlIG1vdXNlIG92ZXIgc3BlY2lmaWMgZGF0ZXMgaW4gY2FsZW5kYXJcclxuXHQgKiBAcGFyYW0gdmFsdWVcclxuXHQgKiBAcGFyYW0gZGF0ZVx0XHRcdFx0XHQtICBKYXZhU2NyaXB0IERhdGUgT2JqOiAgXHRcdE1vbiBEZWMgMTEgMjAyMyAwMDowMDowMCBHTVQrMDIwMCAoRWFzdGVybiBFdXJvcGVhbiBTdGFuZGFyZCBUaW1lKVxyXG5cdCAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHQtICBDYWxlbmRhciBTZXR0aW5ncyBPYmplY3Q6ICBcdHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJodG1sX2lkXCI6IFwiY2FsZW5kYXJfYm9va2luZzRcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJ0ZXh0X2lkXCI6IFwiZGF0ZV9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX19zdGFydF93ZWVrX2RheVwiOiAxLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRoc1wiOiAxMixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJyZXNvdXJjZV9pZFwiOiA0LFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImFqeF9ub25jZV9jYWxlbmRhclwiOiBcIjxpbnB1dCB0eXBlPVxcXCJoaWRkZW5cXFwiIC4uLiAvPlwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImJvb2tlZF9kYXRlc1wiOiB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMTItMjgtMjAyMlwiOiBbXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYm9va2luZ19kYXRlXCI6IFwiMjAyMi0xMi0yOCAwMDowMDowMFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYXBwcm92ZWRcIjogXCIxXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2lkXCI6IFwiMjZcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXNvbl9hdmFpbGFiaWxpdHknOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMDlcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTBcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTFcIjogdHJ1ZSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHRcdFx0LSB0aGlzIG9mIGRhdGVwaWNrIE9ialxyXG5cdCAqXHJcblx0ICogQHJldHVybnMge2Jvb2xlYW59XHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19faW5saW5lX2Jvb2tpbmdfY2FsZW5kYXJfX29uX2RheXNfaG92ZXIoIHZhbHVlLCBkYXRlLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzICl7XHJcblxyXG5cdFx0aWYgKCBudWxsID09PSBkYXRlICl7XHJcblx0XHRcdGpRdWVyeSggJy5kYXRlcGljay1kYXlzLWNlbGwtb3ZlcicgKS5yZW1vdmVDbGFzcyggJ2RhdGVwaWNrLWRheXMtY2VsbC1vdmVyJyApOyAgIFx0ICAgICAgICAgICAgICAgICAgICAgICAgLy8gY2xlYXIgYWxsIGhpZ2hsaWdodCBkYXlzIHNlbGVjdGlvbnNcclxuXHRcdFx0cmV0dXJuIGZhbHNlO1xyXG5cdFx0fVxyXG5cclxuXHRcdHZhciBpbnN0ID0galF1ZXJ5LmRhdGVwaWNrLl9nZXRJbnN0KCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggJ2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCApICk7XHJcblxyXG5cdFx0aWYgKFxyXG5cdFx0XHQgICAoIDEgPT0gaW5zdC5kYXRlcy5sZW5ndGgpXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gSWYgd2UgaGF2ZSBvbmUgc2VsZWN0ZWQgZGF0ZVxyXG5cdFx0XHQmJiAoJ2R5bmFtaWMnID09PSBjYWxlbmRhcl9wYXJhbXNfYXJyLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlKSBcdFx0XHRcdFx0Ly8gd2hpbGUgaGF2ZSByYW5nZSBkYXlzIHNlbGVjdGlvbiBtb2RlXHJcblx0XHQpe1xyXG5cclxuXHRcdFx0dmFyIHRkX2NsYXNzO1xyXG5cdFx0XHR2YXIgdGRfb3ZlcnMgPSBbXTtcclxuXHRcdFx0dmFyIGlzX2NoZWNrID0gdHJ1ZTtcclxuICAgICAgICAgICAgdmFyIHNlbGNldGVkX2ZpcnN0X2RheSA9IG5ldyBEYXRlKCk7XHJcbiAgICAgICAgICAgIHNlbGNldGVkX2ZpcnN0X2RheS5zZXRGdWxsWWVhcihpbnN0LmRhdGVzWzBdLmdldEZ1bGxZZWFyKCksKGluc3QuZGF0ZXNbMF0uZ2V0TW9udGgoKSksIChpbnN0LmRhdGVzWzBdLmdldERhdGUoKSApICk7IC8vR2V0IGZpcnN0IERhdGVcclxuXHJcbiAgICAgICAgICAgIHdoaWxlKCAgaXNfY2hlY2sgKXtcclxuXHJcblx0XHRcdFx0dGRfY2xhc3MgPSAoc2VsY2V0ZWRfZmlyc3RfZGF5LmdldE1vbnRoKCkgKyAxKSArICctJyArIHNlbGNldGVkX2ZpcnN0X2RheS5nZXREYXRlKCkgKyAnLScgKyBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RnVsbFllYXIoKTtcclxuXHJcblx0XHRcdFx0dGRfb3ZlcnNbIHRkX292ZXJzLmxlbmd0aCBdID0gJyNjYWxlbmRhcl9ib29raW5nJyArIGNhbGVuZGFyX3BhcmFtc19hcnIucmVzb3VyY2VfaWQgKyAnIC5jYWw0ZGF0ZS0nICsgdGRfY2xhc3M7ICAgICAgICAgICAgICAvLyBhZGQgdG8gYXJyYXkgZm9yIGxhdGVyIG1ha2Ugc2VsZWN0aW9uIGJ5IGNsYXNzXHJcblxyXG4gICAgICAgICAgICAgICAgaWYgKFxyXG5cdFx0XHRcdFx0KCAgKCBkYXRlLmdldE1vbnRoKCkgPT0gc2VsY2V0ZWRfZmlyc3RfZGF5LmdldE1vbnRoKCkgKSAgJiZcclxuICAgICAgICAgICAgICAgICAgICAgICAoIGRhdGUuZ2V0RGF0ZSgpID09IHNlbGNldGVkX2ZpcnN0X2RheS5nZXREYXRlKCkgKSAgJiZcclxuICAgICAgICAgICAgICAgICAgICAgICAoIGRhdGUuZ2V0RnVsbFllYXIoKSA9PSBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RnVsbFllYXIoKSApXHJcblx0XHRcdFx0XHQpIHx8ICggc2VsY2V0ZWRfZmlyc3RfZGF5ID4gZGF0ZSApXHJcblx0XHRcdFx0KXtcclxuXHRcdFx0XHRcdGlzX2NoZWNrID0gIGZhbHNlO1xyXG5cdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0c2VsY2V0ZWRfZmlyc3RfZGF5LnNldEZ1bGxZZWFyKCBzZWxjZXRlZF9maXJzdF9kYXkuZ2V0RnVsbFllYXIoKSwgKHNlbGNldGVkX2ZpcnN0X2RheS5nZXRNb250aCgpKSwgKHNlbGNldGVkX2ZpcnN0X2RheS5nZXREYXRlKCkgKyAxKSApO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHQvLyBIaWdobGlnaHQgRGF5c1xyXG5cdFx0XHRmb3IgKCB2YXIgaT0wOyBpIDwgdGRfb3ZlcnMubGVuZ3RoIDsgaSsrKSB7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIGFkZCBjbGFzcyB0byBhbGwgZWxlbWVudHNcclxuXHRcdFx0XHRqUXVlcnkoIHRkX292ZXJzW2ldICkuYWRkQ2xhc3MoJ2RhdGVwaWNrLWRheXMtY2VsbC1vdmVyJyk7XHJcblx0XHRcdH1cclxuXHRcdFx0cmV0dXJuIHRydWU7XHJcblxyXG5cdFx0fVxyXG5cclxuXHQgICAgcmV0dXJuIHRydWU7XHJcblx0fVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogT24gREFZcyBzZWxlY3Rpb24gaW4gY2FsZW5kYXJcclxuXHQgKlxyXG5cdCAqIEBwYXJhbSBkYXRlc19zZWxlY3Rpb25cdFx0LSAgc3RyaW5nOlx0XHRcdCAnMjAyMy0wMy0wNyB+IDIwMjMtMDMtMDcnIG9yICcyMDIzLTA0LTEwLCAyMDIzLTA0LTEyLCAyMDIzLTA0LTAyLCAyMDIzLTA0LTA0J1xyXG5cdCAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHQtICBDYWxlbmRhciBTZXR0aW5ncyBPYmplY3Q6ICBcdHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJodG1sX2lkXCI6IFwiY2FsZW5kYXJfYm9va2luZzRcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJ0ZXh0X2lkXCI6IFwiZGF0ZV9ib29raW5nNFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX19zdGFydF93ZWVrX2RheVwiOiAxLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImNhbGVuZGFyX192aWV3X192aXNpYmxlX21vbnRoc1wiOiAxMixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgXCJyZXNvdXJjZV9pZFwiOiA0LFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImFqeF9ub25jZV9jYWxlbmRhclwiOiBcIjxpbnB1dCB0eXBlPVxcXCJoaWRkZW5cXFwiIC4uLiAvPlwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ICBcImJvb2tlZF9kYXRlc1wiOiB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiMTItMjgtMjAyMlwiOiBbXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYm9va2luZ19kYXRlXCI6IFwiMjAyMi0xMi0yOCAwMDowMDowMFwiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFwiYXBwcm92ZWRcIjogXCIxXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XCJib29raW5nX2lkXCI6IFwiMjZcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlYXNvbl9hdmFpbGFiaWxpdHknOntcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMDlcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTBcIjogdHJ1ZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcIjIwMjMtMDEtMTFcIjogdHJ1ZSwgLi4uXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCAgfVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdCAqIEBwYXJhbSBkYXRlcGlja190aGlzXHRcdFx0LSB0aGlzIG9mIGRhdGVwaWNrIE9ialxyXG5cdCAqXHJcblx0ICogQHJldHVybnMgYm9vbGVhblxyXG5cdCAqL1xyXG5cdGZ1bmN0aW9uIHdwYmNfX2lubGluZV9ib29raW5nX2NhbGVuZGFyX19vbl9kYXlzX3NlbGVjdCggZGF0ZXNfc2VsZWN0aW9uLCBjYWxlbmRhcl9wYXJhbXNfYXJyLCBkYXRlcGlja190aGlzID0gbnVsbCApe1xyXG5cclxuXHRcdHZhciBpbnN0ID0galF1ZXJ5LmRhdGVwaWNrLl9nZXRJbnN0KCBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCggJ2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCApICk7XHJcblxyXG5cdFx0dmFyIGRhdGVzX2FyciA9IFtdO1x0Ly8gIFsgXCIyMDIzLTA0LTA5XCIsIFwiMjAyMy0wNC0xMFwiLCBcIjIwMjMtMDQtMTFcIiBdXHJcblxyXG5cdFx0aWYgKCAtMSAhPT0gZGF0ZXNfc2VsZWN0aW9uLmluZGV4T2YoICd+JyApICkgeyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBSYW5nZSBEYXlzXHJcblxyXG5cdFx0XHRkYXRlc19hcnIgPSB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19zZXBhcmF0b3InIDogJyB+ICcsICAgICAgICAgICAgICAgICAgICAgICAgIC8vICAnIH4gJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2RhdGVzJyAgICAgICAgICAgOiBkYXRlc19zZWxlY3Rpb24sICAgIFx0XHQgICAvLyAnMjAyMy0wNC0wNCB+IDIwMjMtMDQtMDcnXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cclxuXHRcdH0gZWxzZSB7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBNdWx0aXBsZSBEYXlzXHJcblx0XHRcdGRhdGVzX2FyciA9IHdwYmNfZ2V0X2RhdGVzX2Fycl9fZnJvbV9kYXRlc19jb21tYV9zZXBhcmF0ZWRfanMoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19zZXBhcmF0b3InIDogJywgJywgICAgICAgICAgICAgICAgICAgICAgICAgXHQvLyAgJywgJ1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2RhdGVzJyAgICAgICAgICAgOiBkYXRlc19zZWxlY3Rpb24sICAgIFx0XHRcdC8vICcyMDIzLTA0LTEwLCAyMDIzLTA0LTEyLCAyMDIzLTA0LTAyLCAyMDIzLTA0LTA0J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHRcdH1cclxuXHJcblx0XHR3cGJjX2F2eV9hZnRlcl9kYXlzX3NlbGVjdGlvbl9fc2hvd19oZWxwX2luZm8oe1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2NhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlJzogY2FsZW5kYXJfcGFyYW1zX2Fyci5jYWxlbmRhcl9fZGF5c19zZWxlY3Rpb25fbW9kZSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19hcnInICAgICAgICAgICAgICAgICAgICA6IGRhdGVzX2FycixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdkYXRlc19jbGlja19udW0nICAgICAgICAgICAgICA6IGluc3QuZGF0ZXMubGVuZ3RoLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3BvcG92ZXJfaGludHMnXHRcdFx0XHRcdDogY2FsZW5kYXJfcGFyYW1zX2Fyci5wb3BvdmVyX2hpbnRzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG5cdFx0cmV0dXJuIHRydWU7XHJcblx0fVxyXG5cclxuXHRcdC8qKlxyXG5cdFx0ICogU2hvdyBoZWxwIGluZm8gYXQgdGhlIHRvcCAgdG9vbGJhciBhYm91dCBzZWxlY3RlZCBkYXRlcyBhbmQgZnV0dXJlIGFjdGlvbnNcclxuXHRcdCAqXHJcblx0XHQgKiBAcGFyYW0gcGFyYW1zXHJcblx0XHQgKiBcdFx0XHRcdFx0RXhhbXBsZSAxOiAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGU6IFwiZHluYW1pY1wiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfYXJyOiAgWyBcIjIwMjMtMDQtMDNcIiBdLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZGF0ZXNfY2xpY2tfbnVtOiAxXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncG9wb3Zlcl9oaW50cydcdFx0XHRcdFx0OiBjYWxlbmRhcl9wYXJhbXNfYXJyLnBvcG92ZXJfaGludHNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHQgKiBcdFx0XHRcdFx0RXhhbXBsZSAyOiAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGU6IFwiZHluYW1pY1wiXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRkYXRlc19hcnI6IEFycmF5KDEwKSBbIFwiMjAyMy0wNC0wM1wiLCBcIjIwMjMtMDQtMDRcIiwgXCIyMDIzLTA0LTA1XCIsIOKApiBdXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRkYXRlc19jbGlja19udW06IDJcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdwb3BvdmVyX2hpbnRzJ1x0XHRcdFx0XHQ6IGNhbGVuZGFyX3BhcmFtc19hcnIucG9wb3Zlcl9oaW50c1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH1cclxuXHRcdCAqL1xyXG5cdFx0ZnVuY3Rpb24gd3BiY19hdnlfYWZ0ZXJfZGF5c19zZWxlY3Rpb25fX3Nob3dfaGVscF9pbmZvKCBwYXJhbXMgKXtcclxuLy8gY29uc29sZS5sb2coIHBhcmFtcyApO1x0Ly9cdFx0WyBcIjIwMjMtMDQtMDlcIiwgXCIyMDIzLTA0LTEwXCIsIFwiMjAyMy0wNC0xMVwiIF1cclxuXHJcblx0XHRcdHZhciBtZXNzYWdlLCBjb2xvcjtcclxuXHRcdFx0aWYgKGpRdWVyeSggJyN1aV9idG5fYXZ5X19zZXRfZGF5c19hdmFpbGFiaWxpdHlfX2F2YWlsYWJsZScpLmlzKCc6Y2hlY2tlZCcpKXtcclxuXHRcdFx0XHQgbWVzc2FnZSA9IHBhcmFtcy5wb3BvdmVyX2hpbnRzLnRvb2xiYXJfdGV4dF9hdmFpbGFibGU7Ly8nU2V0IGRhdGVzIF9EQVRFU18gYXMgX0hUTUxfIGF2YWlsYWJsZS4nO1xyXG5cdFx0XHRcdCBjb2xvciA9ICcjMTFiZTRjJztcclxuXHRcdFx0fSBlbHNlIHtcclxuXHRcdFx0XHRtZXNzYWdlID0gcGFyYW1zLnBvcG92ZXJfaGludHMudG9vbGJhcl90ZXh0X3VuYXZhaWxhYmxlOy8vJ1NldCBkYXRlcyBfREFURVNfIGFzIF9IVE1MXyB1bmF2YWlsYWJsZS4nO1xyXG5cdFx0XHRcdGNvbG9yID0gJyNlNDM5MzknO1xyXG5cdFx0XHR9XHJcblxyXG5cdFx0XHRtZXNzYWdlID0gJzxzcGFuPicgKyBtZXNzYWdlICsgJzwvc3Bhbj4nO1xyXG5cclxuXHRcdFx0dmFyIGZpcnN0X2RhdGUgPSBwYXJhbXNbICdkYXRlc19hcnInIF1bIDAgXTtcclxuXHRcdFx0dmFyIGxhc3RfZGF0ZSAgPSAoICdkeW5hbWljJyA9PSBwYXJhbXMuY2FsZW5kYXJfX2RheXNfc2VsZWN0aW9uX21vZGUgKVxyXG5cdFx0XHRcdFx0XHRcdD8gcGFyYW1zWyAnZGF0ZXNfYXJyJyBdWyAocGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCAtIDEpIF1cclxuXHRcdFx0XHRcdFx0XHQ6ICggcGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aCA+IDEgKSA/IHBhcmFtc1sgJ2RhdGVzX2FycicgXVsgMSBdIDogJyc7XHJcblxyXG5cdFx0XHRmaXJzdF9kYXRlID0galF1ZXJ5LmRhdGVwaWNrLmZvcm1hdERhdGUoICdkZCBNLCB5eScsIG5ldyBEYXRlKCBmaXJzdF9kYXRlICsgJ1QwMDowMDowMCcgKSApO1xyXG5cdFx0XHRsYXN0X2RhdGUgPSBqUXVlcnkuZGF0ZXBpY2suZm9ybWF0RGF0ZSggJ2RkIE0sIHl5JywgIG5ldyBEYXRlKCBsYXN0X2RhdGUgKyAnVDAwOjAwOjAwJyApICk7XHJcblxyXG5cclxuXHRcdFx0aWYgKCAnZHluYW1pYycgPT0gcGFyYW1zLmNhbGVuZGFyX19kYXlzX3NlbGVjdGlvbl9tb2RlICl7XHJcblx0XHRcdFx0aWYgKCAxID09IHBhcmFtcy5kYXRlc19jbGlja19udW0gKXtcclxuXHRcdFx0XHRcdGxhc3RfZGF0ZSA9ICdfX19fX19fX19fXydcclxuXHRcdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdFx0aWYgKCAnZmlyc3RfdGltZScgPT0galF1ZXJ5KCAnLndwYmNfYWp4X2F2YWlsYWJpbGl0eV9jb250YWluZXInICkuYXR0ciggJ3dwYmNfbG9hZGVkJyApICl7XHJcblx0XHRcdFx0XHRcdGpRdWVyeSggJy53cGJjX2FqeF9hdmFpbGFiaWxpdHlfY29udGFpbmVyJyApLmF0dHIoICd3cGJjX2xvYWRlZCcsICdkb25lJyApXHJcblx0XHRcdFx0XHRcdHdwYmNfYmxpbmtfZWxlbWVudCggJy53cGJjX3dpZGdldF9hdmFpbGFibGVfdW5hdmFpbGFibGUnLCAzLCAyMjAgKTtcclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHR9XHJcblx0XHRcdFx0bWVzc2FnZSA9IG1lc3NhZ2UucmVwbGFjZSggJ19EQVRFU18nLCAgICAnPC9zcGFuPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vKyAnPGRpdj4nICsgJ2Zyb20nICsgJzwvZGl2PidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCsgJzxzcGFuIGNsYXNzPVwid3BiY19iaWdfZGF0ZVwiPicgKyBmaXJzdF9kYXRlICsgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3Bhbj4nICsgJy0nICsgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3BhbiBjbGFzcz1cIndwYmNfYmlnX2RhdGVcIj4nICsgbGFzdF9kYXRlICsgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3Bhbj4nICk7XHJcblx0XHRcdH0gZWxzZSB7XHJcblx0XHRcdFx0Ly8gaWYgKCBwYXJhbXNbICdkYXRlc19hcnInIF0ubGVuZ3RoID4gMSApe1xyXG5cdFx0XHRcdC8vIFx0bGFzdF9kYXRlID0gJywgJyArIGxhc3RfZGF0ZTtcclxuXHRcdFx0XHQvLyBcdGxhc3RfZGF0ZSArPSAoIHBhcmFtc1sgJ2RhdGVzX2FycicgXS5sZW5ndGggPiAyICkgPyAnLCAuLi4nIDogJyc7XHJcblx0XHRcdFx0Ly8gfSBlbHNlIHtcclxuXHRcdFx0XHQvLyBcdGxhc3RfZGF0ZT0nJztcclxuXHRcdFx0XHQvLyB9XHJcblx0XHRcdFx0dmFyIGRhdGVzX2FyciA9IFtdO1xyXG5cdFx0XHRcdGZvciggdmFyIGkgPSAwOyBpIDwgcGFyYW1zWyAnZGF0ZXNfYXJyJyBdLmxlbmd0aDsgaSsrICl7XHJcblx0XHRcdFx0XHRkYXRlc19hcnIucHVzaCggIGpRdWVyeS5kYXRlcGljay5mb3JtYXREYXRlKCAnZGQgTSB5eScsICBuZXcgRGF0ZSggcGFyYW1zWyAnZGF0ZXNfYXJyJyBdWyBpIF0gKyAnVDAwOjAwOjAwJyApICkgICk7XHJcblx0XHRcdFx0fVxyXG5cdFx0XHRcdGZpcnN0X2RhdGUgPSBkYXRlc19hcnIuam9pbiggJywgJyApO1xyXG5cdFx0XHRcdG1lc3NhZ2UgPSBtZXNzYWdlLnJlcGxhY2UoICdfREFURVNfJywgICAgJzwvc3Bhbj4nXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQrICc8c3BhbiBjbGFzcz1cIndwYmNfYmlnX2RhdGVcIj4nICsgZmlyc3RfZGF0ZSArICc8L3NwYW4+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0KyAnPHNwYW4+JyApO1xyXG5cdFx0XHR9XHJcblx0XHRcdG1lc3NhZ2UgPSBtZXNzYWdlLnJlcGxhY2UoICdfSFRNTF8nICwgJzwvc3Bhbj48c3BhbiBjbGFzcz1cIndwYmNfYmlnX3RleHRcIiBzdHlsZT1cImNvbG9yOicrY29sb3IrJztcIj4nKSArICc8c3Bhbj4nO1xyXG5cclxuXHRcdFx0Ly9tZXNzYWdlICs9ICcgPGRpdiBzdHlsZT1cIm1hcmdpbi1sZWZ0OiAxZW07XCI+JyArICcgQ2xpY2sgb24gQXBwbHkgYnV0dG9uIHRvIGFwcGx5IGF2YWlsYWJpbGl0eS4nICsgJzwvZGl2Pic7XHJcblxyXG5cdFx0XHRtZXNzYWdlID0gJzxkaXYgY2xhc3M9XCJ3cGJjX3Rvb2xiYXJfZGF0ZXNfaGludHNcIj4nICsgbWVzc2FnZSArICc8L2Rpdj4nO1xyXG5cclxuXHRcdFx0alF1ZXJ5KCAnLndwYmNfaGVscF90ZXh0JyApLmh0bWwoXHRtZXNzYWdlICk7XHJcblx0XHR9XHJcblxyXG5cdC8qKlxyXG5cdCAqICAgUGFyc2UgZGF0ZXMgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcblx0XHQvKipcclxuXHRcdCAqIEdldCBkYXRlcyBhcnJheSwgIGZyb20gY29tbWEgc2VwYXJhdGVkIGRhdGVzXHJcblx0XHQgKlxyXG5cdFx0ICogQHBhcmFtIHBhcmFtcyAgICAgICA9IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogJ2RhdGVzX3NlcGFyYXRvcicgPT4gJywgJywgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gRGF0ZXMgc2VwYXJhdG9yXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqICdkYXRlcycgICAgICAgICAgID0+ICcyMDIzLTA0LTA0LCAyMDIzLTA0LTA3LCAyMDIzLTA0LTA1JyAgICAgICAgIC8vIERhdGVzIGluICdZLW0tZCcgZm9ybWF0OiAnMjAyMy0wMS0zMSdcclxuXHRcdFx0XHRcdFx0XHRcdCB9XHJcblx0XHQgKlxyXG5cdFx0ICogQHJldHVybiBhcnJheSAgICAgID0gW1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMF0gPT4gMjAyMy0wNC0wNFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMV0gPT4gMjAyMy0wNC0wNVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbMl0gPT4gMjAyMy0wNC0wNlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiBbM10gPT4gMjAyMy0wNC0wN1xyXG5cdFx0XHRcdFx0XHRcdFx0XVxyXG5cdFx0ICpcclxuXHRcdCAqIEV4YW1wbGUgIzE6ICB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfY29tbWFfc2VwYXJhdGVkX2pzKCAgeyAgJ2RhdGVzX3NlcGFyYXRvcicgOiAnLCAnLCAnZGF0ZXMnIDogJzIwMjMtMDQtMDQsIDIwMjMtMDQtMDcsIDIwMjMtMDQtMDUnICB9ICApO1xyXG5cdFx0ICovXHJcblx0XHRmdW5jdGlvbiB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfY29tbWFfc2VwYXJhdGVkX2pzKCBwYXJhbXMgKXtcclxuXHJcblx0XHRcdHZhciBkYXRlc19hcnIgPSBbXTtcclxuXHJcblx0XHRcdGlmICggJycgIT09IHBhcmFtc1sgJ2RhdGVzJyBdICl7XHJcblxyXG5cdFx0XHRcdGRhdGVzX2FyciA9IHBhcmFtc1sgJ2RhdGVzJyBdLnNwbGl0KCBwYXJhbXNbICdkYXRlc19zZXBhcmF0b3InIF0gKTtcclxuXHJcblx0XHRcdFx0ZGF0ZXNfYXJyLnNvcnQoKTtcclxuXHRcdFx0fVxyXG5cdFx0XHRyZXR1cm4gZGF0ZXNfYXJyO1xyXG5cdFx0fVxyXG5cclxuXHRcdC8qKlxyXG5cdFx0ICogR2V0IGRhdGVzIGFycmF5LCAgZnJvbSByYW5nZSBkYXlzIHNlbGVjdGlvblxyXG5cdFx0ICpcclxuXHRcdCAqIEBwYXJhbSBwYXJhbXMgICAgICAgPSAge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0KiAnZGF0ZXNfc2VwYXJhdG9yJyA9PiAnIH4gJywgICAgICAgICAgICAgICAgICAgICAgICAgLy8gRGF0ZXMgc2VwYXJhdG9yXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQqICdkYXRlcycgICAgICAgICAgID0+ICcyMDIzLTA0LTA0IH4gMjAyMy0wNC0wNycgICAgICAvLyBEYXRlcyBpbiAnWS1tLWQnIGZvcm1hdDogJzIwMjMtMDEtMzEnXHJcblx0XHRcdFx0XHRcdFx0XHQgIH1cclxuXHRcdCAqXHJcblx0XHQgKiBAcmV0dXJuIGFycmF5ICAgICAgICA9IFtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogWzBdID0+IDIwMjMtMDQtMDRcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogWzFdID0+IDIwMjMtMDQtMDVcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogWzJdID0+IDIwMjMtMDQtMDZcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCogWzNdID0+IDIwMjMtMDQtMDdcclxuXHRcdFx0XHRcdFx0XHRcdCAgXVxyXG5cdFx0ICpcclxuXHRcdCAqIEV4YW1wbGUgIzE6ICB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMoICB7ICAnZGF0ZXNfc2VwYXJhdG9yJyA6ICcgfiAnLCAnZGF0ZXMnIDogJzIwMjMtMDQtMDQgfiAyMDIzLTA0LTA3JyAgfSAgKTtcclxuXHRcdCAqIEV4YW1wbGUgIzI6ICB3cGJjX2dldF9kYXRlc19hcnJfX2Zyb21fZGF0ZXNfcmFuZ2VfanMoICB7ICAnZGF0ZXNfc2VwYXJhdG9yJyA6ICcgLSAnLCAnZGF0ZXMnIDogJzIwMjMtMDQtMDQgLSAyMDIzLTA0LTA3JyAgfSAgKTtcclxuXHRcdCAqL1xyXG5cdFx0ZnVuY3Rpb24gd3BiY19nZXRfZGF0ZXNfYXJyX19mcm9tX2RhdGVzX3JhbmdlX2pzKCBwYXJhbXMgKXtcclxuXHJcblx0XHRcdHZhciBkYXRlc19hcnIgPSBbXTtcclxuXHJcblx0XHRcdGlmICggJycgIT09IHBhcmFtc1snZGF0ZXMnXSApIHtcclxuXHJcblx0XHRcdFx0ZGF0ZXNfYXJyID0gcGFyYW1zWyAnZGF0ZXMnIF0uc3BsaXQoIHBhcmFtc1sgJ2RhdGVzX3NlcGFyYXRvcicgXSApO1xyXG5cdFx0XHRcdHZhciBjaGVja19pbl9kYXRlX3ltZCAgPSBkYXRlc19hcnJbMF07XHJcblx0XHRcdFx0dmFyIGNoZWNrX291dF9kYXRlX3ltZCA9IGRhdGVzX2FyclsxXTtcclxuXHJcblx0XHRcdFx0aWYgKCAoJycgIT09IGNoZWNrX2luX2RhdGVfeW1kKSAmJiAoJycgIT09IGNoZWNrX291dF9kYXRlX3ltZCkgKXtcclxuXHJcblx0XHRcdFx0XHRkYXRlc19hcnIgPSB3cGJjX2dldF9kYXRlc19hcnJheV9mcm9tX3N0YXJ0X2VuZF9kYXlzX2pzKCBjaGVja19pbl9kYXRlX3ltZCwgY2hlY2tfb3V0X2RhdGVfeW1kICk7XHJcblx0XHRcdFx0fVxyXG5cdFx0XHR9XHJcblx0XHRcdHJldHVybiBkYXRlc19hcnI7XHJcblx0XHR9XHJcblxyXG5cdFx0XHQvKipcclxuXHRcdFx0ICogR2V0IGRhdGVzIGFycmF5IGJhc2VkIG9uIHN0YXJ0IGFuZCBlbmQgZGF0ZXMuXHJcblx0XHRcdCAqXHJcblx0XHRcdCAqIEBwYXJhbSBzdHJpbmcgc1N0YXJ0RGF0ZSAtIHN0YXJ0IGRhdGU6IDIwMjMtMDQtMDlcclxuXHRcdFx0ICogQHBhcmFtIHN0cmluZyBzRW5kRGF0ZSAgIC0gZW5kIGRhdGU6ICAgMjAyMy0wNC0xMVxyXG5cdFx0XHQgKiBAcmV0dXJuIGFycmF5ICAgICAgICAgICAgIC0gWyBcIjIwMjMtMDQtMDlcIiwgXCIyMDIzLTA0LTEwXCIsIFwiMjAyMy0wNC0xMVwiIF1cclxuXHRcdFx0ICovXHJcblx0XHRcdGZ1bmN0aW9uIHdwYmNfZ2V0X2RhdGVzX2FycmF5X2Zyb21fc3RhcnRfZW5kX2RheXNfanMoIHNTdGFydERhdGUsIHNFbmREYXRlICl7XHJcblxyXG5cdFx0XHRcdHNTdGFydERhdGUgPSBuZXcgRGF0ZSggc1N0YXJ0RGF0ZSArICdUMDA6MDA6MDAnICk7XHJcblx0XHRcdFx0c0VuZERhdGUgPSBuZXcgRGF0ZSggc0VuZERhdGUgKyAnVDAwOjAwOjAwJyApO1xyXG5cclxuXHRcdFx0XHR2YXIgYURheXM9W107XHJcblxyXG5cdFx0XHRcdC8vIFN0YXJ0IHRoZSB2YXJpYWJsZSBvZmYgd2l0aCB0aGUgc3RhcnQgZGF0ZVxyXG5cdFx0XHRcdGFEYXlzLnB1c2goIHNTdGFydERhdGUuZ2V0VGltZSgpICk7XHJcblxyXG5cdFx0XHRcdC8vIFNldCBhICd0ZW1wJyB2YXJpYWJsZSwgc0N1cnJlbnREYXRlLCB3aXRoIHRoZSBzdGFydCBkYXRlIC0gYmVmb3JlIGJlZ2lubmluZyB0aGUgbG9vcFxyXG5cdFx0XHRcdHZhciBzQ3VycmVudERhdGUgPSBuZXcgRGF0ZSggc1N0YXJ0RGF0ZS5nZXRUaW1lKCkgKTtcclxuXHRcdFx0XHR2YXIgb25lX2RheV9kdXJhdGlvbiA9IDI0KjYwKjYwKjEwMDA7XHJcblxyXG5cdFx0XHRcdC8vIFdoaWxlIHRoZSBjdXJyZW50IGRhdGUgaXMgbGVzcyB0aGFuIHRoZSBlbmQgZGF0ZVxyXG5cdFx0XHRcdHdoaWxlKHNDdXJyZW50RGF0ZSA8IHNFbmREYXRlKXtcclxuXHRcdFx0XHRcdC8vIEFkZCBhIGRheSB0byB0aGUgY3VycmVudCBkYXRlIFwiKzEgZGF5XCJcclxuXHRcdFx0XHRcdHNDdXJyZW50RGF0ZS5zZXRUaW1lKCBzQ3VycmVudERhdGUuZ2V0VGltZSgpICsgb25lX2RheV9kdXJhdGlvbiApO1xyXG5cclxuXHRcdFx0XHRcdC8vIEFkZCB0aGlzIG5ldyBkYXkgdG8gdGhlIGFEYXlzIGFycmF5XHJcblx0XHRcdFx0XHRhRGF5cy5wdXNoKCBzQ3VycmVudERhdGUuZ2V0VGltZSgpICk7XHJcblx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHRmb3IgKGxldCBpID0gMDsgaSA8IGFEYXlzLmxlbmd0aDsgaSsrKSB7XHJcblx0XHRcdFx0XHRhRGF5c1sgaSBdID0gbmV3IERhdGUoIGFEYXlzW2ldICk7XHJcblx0XHRcdFx0XHRhRGF5c1sgaSBdID0gYURheXNbIGkgXS5nZXRGdWxsWWVhcigpXHJcblx0XHRcdFx0XHRcdFx0XHQrICctJyArICgoIChhRGF5c1sgaSBdLmdldE1vbnRoKCkgKyAxKSA8IDEwKSA/ICcwJyA6ICcnKSArIChhRGF5c1sgaSBdLmdldE1vbnRoKCkgKyAxKVxyXG5cdFx0XHRcdFx0XHRcdFx0KyAnLScgKyAoKCAgICAgICAgYURheXNbIGkgXS5nZXREYXRlKCkgPCAxMCkgPyAnMCcgOiAnJykgKyAgYURheXNbIGkgXS5nZXREYXRlKCk7XHJcblx0XHRcdFx0fVxyXG5cdFx0XHRcdC8vIE9uY2UgdGhlIGxvb3AgaGFzIGZpbmlzaGVkLCByZXR1cm4gdGhlIGFycmF5IG9mIGRheXMuXHJcblx0XHRcdFx0cmV0dXJuIGFEYXlzO1xyXG5cdFx0XHR9XHJcblxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogICBUb29sdGlwcyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuXHQvKipcclxuXHQgKiBEZWZpbmUgc2hvd2luZyB0b29sdGlwLCAgd2hlbiAgbW91c2Ugb3ZlciBvbiAgU0VMRUNUQUJMRSAoYXZhaWxhYmxlLCBwZW5kaW5nLCBhcHByb3ZlZCwgcmVzb3VyY2UgdW5hdmFpbGFibGUpLCAgZGF5c1xyXG5cdCAqIENhbiBiZSBjYWxsZWQgZGlyZWN0bHkgIGZyb20gIGRhdGVwaWNrIGluaXQgZnVuY3Rpb24uXHJcblx0ICpcclxuXHQgKiBAcGFyYW0gdmFsdWVcclxuXHQgKiBAcGFyYW0gZGF0ZVxyXG5cdCAqIEBwYXJhbSBjYWxlbmRhcl9wYXJhbXNfYXJyXHJcblx0ICogQHBhcmFtIGRhdGVwaWNrX3RoaXNcclxuXHQgKiBAcmV0dXJucyB7Ym9vbGVhbn1cclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX2F2eV9fcHJlcGFyZV90b29sdGlwX19pbl9jYWxlbmRhciggdmFsdWUsIGRhdGUsIGNhbGVuZGFyX3BhcmFtc19hcnIsIGRhdGVwaWNrX3RoaXMgKXtcclxuXHJcblx0XHRpZiAoIG51bGwgPT0gZGF0ZSApeyAgcmV0dXJuIGZhbHNlOyAgfVxyXG5cclxuXHRcdHZhciB0ZF9jbGFzcyA9ICggZGF0ZS5nZXRNb250aCgpICsgMSApICsgJy0nICsgZGF0ZS5nZXREYXRlKCkgKyAnLScgKyBkYXRlLmdldEZ1bGxZZWFyKCk7XHJcblxyXG5cdFx0dmFyIGpDZWxsID0galF1ZXJ5KCAnI2NhbGVuZGFyX2Jvb2tpbmcnICsgY2FsZW5kYXJfcGFyYW1zX2Fyci5yZXNvdXJjZV9pZCArICcgdGQuY2FsNGRhdGUtJyArIHRkX2NsYXNzICk7XHJcblxyXG5cdFx0d3BiY19hdnlfX3Nob3dfdG9vbHRpcF9fZm9yX2VsZW1lbnQoIGpDZWxsLCBjYWxlbmRhcl9wYXJhbXNfYXJyWyAncG9wb3Zlcl9oaW50cycgXSApO1xyXG5cdFx0cmV0dXJuIHRydWU7XHJcblx0fVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogRGVmaW5lIHRvb2x0aXAgIGZvciBzaG93aW5nIG9uIFVOQVZBSUxBQkxFIGRheXMgKHNlYXNvbiwgd2Vla2RheSwgdG9kYXlfZGVwZW5kcyB1bmF2YWlsYWJsZSlcclxuXHQgKlxyXG5cdCAqIEBwYXJhbSBqQ2VsbFx0XHRcdFx0XHRqUXVlcnkgb2Ygc3BlY2lmaWMgZGF5IGNlbGxcclxuXHQgKiBAcGFyYW0gcG9wb3Zlcl9oaW50c1x0XHQgICAgQXJyYXkgd2l0aCB0b29sdGlwIGhpbnQgdGV4dHNcdCA6IHsnc2Vhc29uX3VuYXZhaWxhYmxlJzonLi4uJywnd2Vla2RheXNfdW5hdmFpbGFibGUnOicuLi4nLCdiZWZvcmVfYWZ0ZXJfdW5hdmFpbGFibGUnOicuLi4nLH1cclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX2F2eV9fc2hvd190b29sdGlwX19mb3JfZWxlbWVudCggakNlbGwsIHBvcG92ZXJfaGludHMgKXtcclxuXHJcblx0XHR2YXIgdG9vbHRpcF90aW1lID0gJyc7XHJcblxyXG5cdFx0aWYgKCBqQ2VsbC5oYXNDbGFzcyggJ3NlYXNvbl91bmF2YWlsYWJsZScgKSApe1xyXG5cdFx0XHR0b29sdGlwX3RpbWUgPSBwb3BvdmVyX2hpbnRzWyAnc2Vhc29uX3VuYXZhaWxhYmxlJyBdO1xyXG5cdFx0fSBlbHNlIGlmICggakNlbGwuaGFzQ2xhc3MoICd3ZWVrZGF5c191bmF2YWlsYWJsZScgKSApe1xyXG5cdFx0XHR0b29sdGlwX3RpbWUgPSBwb3BvdmVyX2hpbnRzWyAnd2Vla2RheXNfdW5hdmFpbGFibGUnIF07XHJcblx0XHR9IGVsc2UgaWYgKCBqQ2VsbC5oYXNDbGFzcyggJ2JlZm9yZV9hZnRlcl91bmF2YWlsYWJsZScgKSApe1xyXG5cdFx0XHR0b29sdGlwX3RpbWUgPSBwb3BvdmVyX2hpbnRzWyAnYmVmb3JlX2FmdGVyX3VuYXZhaWxhYmxlJyBdO1xyXG5cdFx0fSBlbHNlIGlmICggakNlbGwuaGFzQ2xhc3MoICdkYXRlMmFwcHJvdmUnICkgKXtcclxuXHJcblx0XHR9IGVsc2UgaWYgKCBqQ2VsbC5oYXNDbGFzcyggJ2RhdGVfYXBwcm92ZWQnICkgKXtcclxuXHJcblx0XHR9IGVsc2Uge1xyXG5cclxuXHRcdH1cclxuXHJcblx0XHRqQ2VsbC5hdHRyKCAnZGF0YS1jb250ZW50JywgdG9vbHRpcF90aW1lICk7XHJcblxyXG5cdFx0dmFyIHRkX2VsID0gakNlbGwuZ2V0KDApO1x0Ly9qUXVlcnkoICcjY2FsZW5kYXJfYm9va2luZycgKyBjYWxlbmRhcl9wYXJhbXNfYXJyLnJlc291cmNlX2lkICsgJyB0ZC5jYWw0ZGF0ZS0nICsgdGRfY2xhc3MgKS5nZXQoMCk7XHJcblxyXG5cdFx0aWYgKCAoIHVuZGVmaW5lZCA9PSB0ZF9lbC5fdGlwcHkgKSAmJiAoICcnICE9IHRvb2x0aXBfdGltZSApICl7XHJcblxyXG5cdFx0XHRcdHdwYmNfdGlwcHkoIHRkX2VsICwge1xyXG5cdFx0XHRcdFx0Y29udGVudCggcmVmZXJlbmNlICl7XHJcblxyXG5cdFx0XHRcdFx0XHR2YXIgcG9wb3Zlcl9jb250ZW50ID0gcmVmZXJlbmNlLmdldEF0dHJpYnV0ZSggJ2RhdGEtY29udGVudCcgKTtcclxuXHJcblx0XHRcdFx0XHRcdHJldHVybiAnPGRpdiBjbGFzcz1cInBvcG92ZXIgcG9wb3Zlcl90aXBweVwiPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0KyAnPGRpdiBjbGFzcz1cInBvcG92ZXItY29udGVudFwiPidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQrIHBvcG92ZXJfY29udGVudFxyXG5cdFx0XHRcdFx0XHRcdFx0XHQrICc8L2Rpdj4nXHJcblx0XHRcdFx0XHRcdFx0ICsgJzwvZGl2Pic7XHJcblx0XHRcdFx0XHR9LFxyXG5cdFx0XHRcdFx0YWxsb3dIVE1MICAgICAgICA6IHRydWUsXHJcblx0XHRcdFx0XHR0cmlnZ2VyXHRcdFx0IDogJ21vdXNlZW50ZXIgZm9jdXMnLFxyXG5cdFx0XHRcdFx0aW50ZXJhY3RpdmUgICAgICA6ICEgdHJ1ZSxcclxuXHRcdFx0XHRcdGhpZGVPbkNsaWNrICAgICAgOiB0cnVlLFxyXG5cdFx0XHRcdFx0aW50ZXJhY3RpdmVCb3JkZXI6IDEwLFxyXG5cdFx0XHRcdFx0bWF4V2lkdGggICAgICAgICA6IDU1MCxcclxuXHRcdFx0XHRcdHRoZW1lICAgICAgICAgICAgOiAnd3BiYy10aXBweS10aW1lcycsXHJcblx0XHRcdFx0XHRwbGFjZW1lbnQgICAgICAgIDogJ3RvcCcsXHJcblx0XHRcdFx0XHRkZWxheVx0XHRcdCA6IFs0MDAsIDBdLFx0XHRcdC8vRml4SW46IDkuNC4yLjJcclxuXHRcdFx0XHRcdGlnbm9yZUF0dHJpYnV0ZXMgOiB0cnVlLFxyXG5cdFx0XHRcdFx0dG91Y2hcdFx0XHQgOiB0cnVlLFx0XHRcdFx0Ly9bJ2hvbGQnLCA1MDBdLCAvLyA1MDBtcyBkZWxheVx0XHRcdC8vRml4SW46IDkuMi4xLjVcclxuXHRcdFx0XHRcdGFwcGVuZFRvOiAoKSA9PiBkb2N1bWVudC5ib2R5LFxyXG5cdFx0XHRcdH0pO1xyXG5cdFx0fVxyXG5cdH1cclxuXHJcblxyXG5cclxuXHJcblxyXG4vKipcclxuICogICBBamF4ICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBTZW5kIEFqYXggc2hvdyByZXF1ZXN0XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FqYXhfcmVxdWVzdCgpe1xyXG5cclxuY29uc29sZS5ncm91cENvbGxhcHNlZCggJ1dQQkNfQUpYX0FWQUlMQUJJTElUWScgKTsgY29uc29sZS5sb2coICcgPT0gQmVmb3JlIEFqYXggU2VuZCAtIHNlYXJjaF9nZXRfYWxsX3BhcmFtcygpID09ICcgLCB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuc2VhcmNoX2dldF9hbGxfcGFyYW1zKCkgKTtcclxuXHJcblx0d3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCgpO1xyXG5cclxuXHQvLyBTdGFydCBBamF4XHJcblx0alF1ZXJ5LnBvc3QoIHdwYmNfZ2xvYmFsMS53cGJjX2FqYXh1cmwsXHJcblx0XHRcdFx0e1xyXG5cdFx0XHRcdFx0YWN0aW9uICAgICAgICAgIDogJ1dQQkNfQUpYX0FWQUlMQUJJTElUWScsXHJcblx0XHRcdFx0XHR3cGJjX2FqeF91c2VyX2lkOiB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuZ2V0X3NlY3VyZV9wYXJhbSggJ3VzZXJfaWQnICksXHJcblx0XHRcdFx0XHRub25jZSAgICAgICAgICAgOiB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuZ2V0X3NlY3VyZV9wYXJhbSggJ25vbmNlJyApLFxyXG5cdFx0XHRcdFx0d3BiY19hanhfbG9jYWxlIDogd3BiY19hanhfYXZhaWxhYmlsaXR5LmdldF9zZWN1cmVfcGFyYW0oICdsb2NhbGUnICksXHJcblxyXG5cdFx0XHRcdFx0c2VhcmNoX3BhcmFtc1x0OiB3cGJjX2FqeF9hdmFpbGFiaWxpdHkuc2VhcmNoX2dldF9hbGxfcGFyYW1zKClcclxuXHRcdFx0XHR9LFxyXG5cdFx0XHRcdC8qKlxyXG5cdFx0XHRcdCAqIFMgdSBjIGMgZSBzIHNcclxuXHRcdFx0XHQgKlxyXG5cdFx0XHRcdCAqIEBwYXJhbSByZXNwb25zZV9kYXRhXHRcdC1cdGl0cyBvYmplY3QgcmV0dXJuZWQgZnJvbSAgQWpheCAtIGNsYXNzLWxpdmUtc2VhcmNnLnBocFxyXG5cdFx0XHRcdCAqIEBwYXJhbSB0ZXh0U3RhdHVzXHRcdC1cdCdzdWNjZXNzJ1xyXG5cdFx0XHRcdCAqIEBwYXJhbSBqcVhIUlx0XHRcdFx0LVx0T2JqZWN0XHJcblx0XHRcdFx0ICovXHJcblx0XHRcdFx0ZnVuY3Rpb24gKCByZXNwb25zZV9kYXRhLCB0ZXh0U3RhdHVzLCBqcVhIUiApIHtcclxuXHJcbmNvbnNvbGUubG9nKCAnID09IFJlc3BvbnNlIFdQQkNfQUpYX0FWQUlMQUJJTElUWSA9PSAnLCByZXNwb25zZV9kYXRhICk7IGNvbnNvbGUuZ3JvdXBFbmQoKTtcclxuXHJcblx0XHRcdFx0XHQvLyBQcm9iYWJseSBFcnJvclxyXG5cdFx0XHRcdFx0aWYgKCAodHlwZW9mIHJlc3BvbnNlX2RhdGEgIT09ICdvYmplY3QnKSB8fCAocmVzcG9uc2VfZGF0YSA9PT0gbnVsbCkgKXtcclxuXHJcblx0XHRcdFx0XHRcdHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fc2hvd19tZXNzYWdlKCByZXNwb25zZV9kYXRhICk7XHJcblxyXG5cdFx0XHRcdFx0XHRyZXR1cm47XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0Ly8gUmVsb2FkIHBhZ2UsIGFmdGVyIGZpbHRlciB0b29sYmFyIGhhcyBiZWVuIHJlc2V0XHJcblx0XHRcdFx0XHRpZiAoICAgICAgICggICAgIHVuZGVmaW5lZCAhPSByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdKVxyXG5cdFx0XHRcdFx0XHRcdCYmICggJ3Jlc2V0X2RvbmUnID09PSByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdWyAnZG9fYWN0aW9uJyBdKVxyXG5cdFx0XHRcdFx0KXtcclxuXHRcdFx0XHRcdFx0bG9jYXRpb24ucmVsb2FkKCk7XHJcblx0XHRcdFx0XHRcdHJldHVybjtcclxuXHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHQvLyBTaG93IGxpc3RpbmdcclxuXHRcdFx0XHRcdHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fcGFnZV9jb250ZW50X19zaG93KCByZXNwb25zZV9kYXRhWyAnYWp4X2RhdGEnIF0sIHJlc3BvbnNlX2RhdGFbICdhanhfc2VhcmNoX3BhcmFtcycgXSAsIHJlc3BvbnNlX2RhdGFbICdhanhfY2xlYW5lZF9wYXJhbXMnIF0gKTtcclxuXHJcblx0XHRcdFx0XHQvL3dwYmNfYWp4X2F2YWlsYWJpbGl0eV9fZGVmaW5lX3VpX2hvb2tzKCk7XHRcdFx0XHRcdFx0Ly8gUmVkZWZpbmUgSG9va3MsIGJlY2F1c2Ugd2Ugc2hvdyBuZXcgRE9NIGVsZW1lbnRzXHJcblx0XHRcdFx0XHRpZiAoICcnICE9IHJlc3BvbnNlX2RhdGFbICdhanhfZGF0YScgXVsgJ2FqeF9hZnRlcl9hY3Rpb25fbWVzc2FnZScgXS5yZXBsYWNlKCAvXFxuL2csIFwiPGJyIC8+XCIgKSApe1xyXG5cdFx0XHRcdFx0XHR3cGJjX2FkbWluX3Nob3dfbWVzc2FnZShcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIHJlc3BvbnNlX2RhdGFbICdhanhfZGF0YScgXVsgJ2FqeF9hZnRlcl9hY3Rpb25fbWVzc2FnZScgXS5yZXBsYWNlKCAvXFxuL2csIFwiPGJyIC8+XCIgKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgKCAnMScgPT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9kYXRhJyBdWyAnYWp4X2FmdGVyX2FjdGlvbl9yZXN1bHQnIF0gKSA/ICdzdWNjZXNzJyA6ICdlcnJvcidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsIDEwMDAwXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCk7XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0d3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSgpO1xyXG5cdFx0XHRcdFx0Ly8gUmVtb3ZlIHNwaW4gaWNvbiBmcm9tICBidXR0b24gYW5kIEVuYWJsZSB0aGlzIGJ1dHRvbi5cclxuXHRcdFx0XHRcdHdwYmNfYnV0dG9uX19yZW1vdmVfc3BpbiggcmVzcG9uc2VfZGF0YVsgJ2FqeF9jbGVhbmVkX3BhcmFtcycgXVsgJ3VpX2NsaWNrZWRfZWxlbWVudF9pZCcgXSApXHJcblxyXG5cdFx0XHRcdFx0alF1ZXJ5KCAnI2FqYXhfcmVzcG9uZCcgKS5odG1sKCByZXNwb25zZV9kYXRhICk7XHRcdC8vIEZvciBhYmlsaXR5IHRvIHNob3cgcmVzcG9uc2UsIGFkZCBzdWNoIERJViBlbGVtZW50IHRvIHBhZ2VcclxuXHRcdFx0XHR9XHJcblx0XHRcdCAgKS5mYWlsKCBmdW5jdGlvbiAoIGpxWEhSLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93biApIHsgICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdBamF4X0Vycm9yJywganFYSFIsIHRleHRTdGF0dXMsIGVycm9yVGhyb3duICk7IH1cclxuXHJcblx0XHRcdFx0XHR2YXIgZXJyb3JfbWVzc2FnZSA9ICc8c3Ryb25nPicgKyAnRXJyb3IhJyArICc8L3N0cm9uZz4gJyArIGVycm9yVGhyb3duIDtcclxuXHRcdFx0XHRcdGlmICgganFYSFIuc3RhdHVzICl7XHJcblx0XHRcdFx0XHRcdGVycm9yX21lc3NhZ2UgKz0gJyAoPGI+JyArIGpxWEhSLnN0YXR1cyArICc8L2I+KSc7XHJcblx0XHRcdFx0XHRcdGlmICg0MDMgPT0ganFYSFIuc3RhdHVzICl7XHJcblx0XHRcdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSArPSAnIFByb2JhYmx5IG5vbmNlIGZvciB0aGlzIHBhZ2UgaGFzIGJlZW4gZXhwaXJlZC4gUGxlYXNlIDxhIGhyZWY9XCJqYXZhc2NyaXB0OnZvaWQoMClcIiBvbmNsaWNrPVwiamF2YXNjcmlwdDpsb2NhdGlvbi5yZWxvYWQoKTtcIj5yZWxvYWQgdGhlIHBhZ2U8L2E+Lic7XHJcblx0XHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdGlmICgganFYSFIucmVzcG9uc2VUZXh0ICl7XHJcblx0XHRcdFx0XHRcdGVycm9yX21lc3NhZ2UgKz0gJyAnICsganFYSFIucmVzcG9uc2VUZXh0O1xyXG5cdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSA9IGVycm9yX21lc3NhZ2UucmVwbGFjZSggL1xcbi9nLCBcIjxiciAvPlwiICk7XHJcblxyXG5cdFx0XHRcdFx0d3BiY19hanhfYXZhaWxhYmlsaXR5X19zaG93X21lc3NhZ2UoIGVycm9yX21lc3NhZ2UgKTtcclxuXHRcdFx0ICB9KVxyXG5cdCAgICAgICAgICAvLyAuZG9uZSggICBmdW5jdGlvbiAoIGRhdGEsIHRleHRTdGF0dXMsIGpxWEhSICkgeyAgIGlmICggd2luZG93LmNvbnNvbGUgJiYgd2luZG93LmNvbnNvbGUubG9nICl7IGNvbnNvbGUubG9nKCAnc2Vjb25kIHN1Y2Nlc3MnLCBkYXRhLCB0ZXh0U3RhdHVzLCBqcVhIUiApOyB9ICAgIH0pXHJcblx0XHRcdCAgLy8gLmFsd2F5cyggZnVuY3Rpb24gKCBkYXRhX2pxWEhSLCB0ZXh0U3RhdHVzLCBqcVhIUl9lcnJvclRocm93biApIHsgICBpZiAoIHdpbmRvdy5jb25zb2xlICYmIHdpbmRvdy5jb25zb2xlLmxvZyApeyBjb25zb2xlLmxvZyggJ2Fsd2F5cyBmaW5pc2hlZCcsIGRhdGFfanFYSFIsIHRleHRTdGF0dXMsIGpxWEhSX2Vycm9yVGhyb3duICk7IH0gICAgIH0pXHJcblx0XHRcdCAgOyAgLy8gRW5kIEFqYXhcclxuXHJcbn1cclxuXHJcblxyXG5cclxuLyoqXHJcbiAqICAgSCBvIG8gayBzICAtICBpdHMgQWN0aW9uL1RpbWVzIHdoZW4gbmVlZCB0byByZS1SZW5kZXIgVmlld3MgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU2VuZCBBamF4IFNlYXJjaCBSZXF1ZXN0IGFmdGVyIFVwZGF0aW5nIHNlYXJjaCByZXF1ZXN0IHBhcmFtZXRlcnNcclxuICpcclxuICogQHBhcmFtIHBhcmFtc19hcnJcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fc2VuZF9yZXF1ZXN0X3dpdGhfcGFyYW1zICggcGFyYW1zX2FyciApe1xyXG5cclxuXHQvLyBEZWZpbmUgZGlmZmVyZW50IFNlYXJjaCAgcGFyYW1ldGVycyBmb3IgcmVxdWVzdFxyXG5cdF8uZWFjaCggcGFyYW1zX2FyciwgZnVuY3Rpb24gKCBwX3ZhbCwgcF9rZXksIHBfZGF0YSApIHtcclxuXHRcdC8vY29uc29sZS5sb2coICdSZXF1ZXN0IGZvcjogJywgcF9rZXksIHBfdmFsICk7XHJcblx0XHR3cGJjX2FqeF9hdmFpbGFiaWxpdHkuc2VhcmNoX3NldF9wYXJhbSggcF9rZXksIHBfdmFsICk7XHJcblx0fSk7XHJcblxyXG5cdC8vIFNlbmQgQWpheCBSZXF1ZXN0XHJcblx0d3BiY19hanhfYXZhaWxhYmlsaXR5X19hamF4X3JlcXVlc3QoKTtcclxufVxyXG5cclxuXHJcblx0LyoqXHJcblx0ICogU2VhcmNoIHJlcXVlc3QgZm9yIFwiUGFnZSBOdW1iZXJcIlxyXG5cdCAqIEBwYXJhbSBwYWdlX251bWJlclx0aW50XHJcblx0ICovXHJcblx0ZnVuY3Rpb24gd3BiY19hanhfYXZhaWxhYmlsaXR5X19wYWdpbmF0aW9uX2NsaWNrKCBwYWdlX251bWJlciApe1xyXG5cclxuXHRcdHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fc2VuZF9yZXF1ZXN0X3dpdGhfcGFyYW1zKCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncGFnZV9udW0nOiBwYWdlX251bWJlclxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHR9XHJcblxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIFNob3cgLyBIaWRlIENvbnRlbnQgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqICBTaG93IExpc3RpbmcgQ29udGVudCBcdC0gXHRTZW5kaW5nIEFqYXggUmVxdWVzdFx0LVx0d2l0aCBwYXJhbWV0ZXJzIHRoYXQgIHdlIGVhcmx5ICBkZWZpbmVkXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FjdHVhbF9jb250ZW50X19zaG93KCl7XHJcblxyXG5cdHdwYmNfYWp4X2F2YWlsYWJpbGl0eV9fYWpheF9yZXF1ZXN0KCk7XHRcdFx0Ly8gU2VuZCBBamF4IFJlcXVlc3RcdC1cdHdpdGggcGFyYW1ldGVycyB0aGF0ICB3ZSBlYXJseSAgZGVmaW5lZCBpbiBcIndwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZ1wiIE9iai5cclxufVxyXG5cclxuLyoqXHJcbiAqIEhpZGUgTGlzdGluZyBDb250ZW50XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9hdmFpbGFiaWxpdHlfX2FjdHVhbF9jb250ZW50X19oaWRlKCl7XHJcblxyXG5cdGpRdWVyeSggIHdwYmNfYWp4X2F2YWlsYWJpbGl0eS5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSAgKS5odG1sKCAnJyApO1xyXG59XHJcblxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIE0gZSBzIHMgYSBnIGUgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqIFNob3cganVzdCBtZXNzYWdlIGluc3RlYWQgb2YgY29udGVudFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYXZhaWxhYmlsaXR5X19zaG93X21lc3NhZ2UoIG1lc3NhZ2UgKXtcclxuXHJcblx0d3BiY19hanhfYXZhaWxhYmlsaXR5X19hY3R1YWxfY29udGVudF9faGlkZSgpO1xyXG5cclxuXHRqUXVlcnkoIHdwYmNfYWp4X2F2YWlsYWJpbGl0eS5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSApLmh0bWwoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCc8ZGl2IGNsYXNzPVwid3BiYy1zZXR0aW5ncy1ub3RpY2Ugbm90aWNlLXdhcm5pbmdcIiBzdHlsZT1cInRleHQtYWxpZ246bGVmdFwiPicgK1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdG1lc3NhZ2UgK1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPC9kaXY+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdCk7XHJcbn1cclxuXHJcblxyXG5cclxuLyoqXHJcbiAqICAgU3VwcG9ydCBGdW5jdGlvbnMgLSBTcGluIEljb24gaW4gQnV0dG9ucyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU3BpbiBidXR0b24gaW4gRmlsdGVyIHRvb2xiYXIgIC0gIFN0YXJ0XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2F2YWlsYWJpbGl0eV9yZWxvYWRfYnV0dG9uX19zcGluX3N0YXJ0KCl7XHJcblx0alF1ZXJ5KCAnI3dwYmNfYXZhaWxhYmlsaXR5X3JlbG9hZF9idXR0b24gLm1lbnVfaWNvbi53cGJjX3NwaW4nKS5yZW1vdmVDbGFzcyggJ3dwYmNfYW5pbWF0aW9uX3BhdXNlJyApO1xyXG59XHJcblxyXG4vKipcclxuICogU3BpbiBidXR0b24gaW4gRmlsdGVyIHRvb2xiYXIgIC0gIFBhdXNlXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2F2YWlsYWJpbGl0eV9yZWxvYWRfYnV0dG9uX19zcGluX3BhdXNlKCl7XHJcblx0alF1ZXJ5KCAnI3dwYmNfYXZhaWxhYmlsaXR5X3JlbG9hZF9idXR0b24gLm1lbnVfaWNvbi53cGJjX3NwaW4nICkuYWRkQ2xhc3MoICd3cGJjX2FuaW1hdGlvbl9wYXVzZScgKTtcclxufVxyXG5cclxuLyoqXHJcbiAqIFNwaW4gYnV0dG9uIGluIEZpbHRlciB0b29sYmFyICAtICBpcyBTcGlubmluZyA/XHJcbiAqXHJcbiAqIEByZXR1cm5zIHtib29sZWFufVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbl9faXNfc3Bpbigpe1xyXG4gICAgaWYgKCBqUXVlcnkoICcjd3BiY19hdmFpbGFiaWxpdHlfcmVsb2FkX2J1dHRvbiAubWVudV9pY29uLndwYmNfc3BpbicgKS5oYXNDbGFzcyggJ3dwYmNfYW5pbWF0aW9uX3BhdXNlJyApICl7XHJcblx0XHRyZXR1cm4gdHJ1ZTtcclxuXHR9IGVsc2Uge1xyXG5cdFx0cmV0dXJuIGZhbHNlO1xyXG5cdH1cclxufVxyXG4iXSwiZmlsZSI6ImluY2x1ZGVzL3BhZ2UtYXZhaWxhYmlsaXR5L19vdXQvYXZhaWxhYmlsaXR5X3BhZ2UuanMifQ==
