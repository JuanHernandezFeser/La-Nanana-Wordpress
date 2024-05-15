"use strict";

function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

jQuery('body').on({
  'touchmove': function touchmove(e) {
    jQuery('.timespartly').each(function (index) {
      var td_el = jQuery(this).get(0);

      if (undefined != td_el._tippy) {
        var instance = td_el._tippy;
        instance.hide();
      }
    });
  }
});
/**
 * Request Object
 * Here we can  define Search parameters and Update it later,  when  some parameter was changed
 *
 */

var wpbc_ajx_booking_listing = function (obj, $) {
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


  var p_listing = obj.search_request_obj = obj.search_request_obj || {
    sort: "booking_id",
    sort_type: "DESC",
    page_num: 1,
    page_items_count: 10,
    create_date: "",
    keyword: "",
    source: ""
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
}(wpbc_ajx_booking_listing || {}, jQuery);
/**
 *   Ajax  ------------------------------------------------------------------------------------------------------ */

/**
 * Send Ajax search request
 * for searching specific Keyword and other params
 */


function wpbc_ajx_booking_ajax_search_request() {
  console.groupCollapsed('AJX_BOOKING_LISTING');
  console.log(' == Before Ajax Send - search_get_all_params() == ', wpbc_ajx_booking_listing.search_get_all_params());
  wpbc_booking_listing_reload_button__spin_start();
  /*
  //FixIn: forVideo
  if ( ! is_this_action ){
  	//wpbc_ajx_booking__actual_listing__hide();
  	jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html(
  		'<div style="width:100%;text-align: center;" id="wpbc_loading_section"><span class="wpbc_icn_autorenew wpbc_spin"></span></div>'
  		+ jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html()
  	);
  	if ( 'function' === typeof (jQuery( '#wpbc_loading_section' ).wpbc_my_modal) ){			//FixIn: 9.0.1.5
  		jQuery( '#wpbc_loading_section' ).wpbc_my_modal( 'show' );
  	} else {
  		alert( 'Warning! Booking Calendar. Its seems that  you have deactivated loading of Bootstrap JS files at Booking Settings General page in Advanced section.' )
  	}
  }
  is_this_action = false;
  */
  // Start Ajax

  jQuery.post(wpbc_global1.wpbc_ajaxurl, {
    action: 'WPBC_AJX_BOOKING_LISTING',
    wpbc_ajx_user_id: wpbc_ajx_booking_listing.get_secure_param('user_id'),
    nonce: wpbc_ajx_booking_listing.get_secure_param('nonce'),
    wpbc_ajx_locale: wpbc_ajx_booking_listing.get_secure_param('locale'),
    search_params: wpbc_ajx_booking_listing.search_get_all_params()
  },
  /**
   * S u c c e s s
   *
   * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
   * @param textStatus		-	'success'
   * @param jqXHR				-	Object
   */
  function (response_data, textStatus, jqXHR) {
    //FixIn: forVideo
    //jQuery( '#wpbc_loading_section' ).wpbc_my_modal( 'hide' );
    console.log(' == Response WPBC_AJX_BOOKING_LISTING == ', response_data);
    console.groupEnd(); // Probably Error

    if (_typeof(response_data) !== 'object' || response_data === null) {
      jQuery('.wpbc_ajx_under_toolbar_row').hide(); //FixIn: 9.6.1.5

      jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('<div class="wpbc-settings-notice notice-warning" style="text-align:left">' + response_data + '</div>');
      return;
    } // Reload page, after filter toolbar was reseted


    if (undefined != response_data['ajx_cleaned_params'] && 'reset_done' === response_data['ajx_cleaned_params']['ui_reset']) {
      location.reload();
      return;
    } // Show listing


    if (response_data['ajx_count'] > 0) {
      wpbc_ajx_booking_show_listing(response_data['ajx_items'], response_data['ajx_search_params'], response_data['ajx_booking_resources']);
      wpbc_pagination_echo(wpbc_ajx_booking_listing.get_other_param('pagination_container'), {
        'page_active': response_data['ajx_search_params']['page_num'],
        'pages_count': Math.ceil(response_data['ajx_count'] / response_data['ajx_search_params']['page_items_count']),
        'page_items_count': response_data['ajx_search_params']['page_items_count'],
        'sort_type': response_data['ajx_search_params']['sort_type']
      });
      wpbc_ajx_booking_define_ui_hooks(); // Redefine Hooks, because we show new DOM elements
    } else {
      wpbc_ajx_booking__actual_listing__hide();
      jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('<div class="wpbc-settings-notice0 notice-warning0" style="text-align:center;margin-left:-50px;">' + '<strong>' + 'No results found for current filter options...' + '</strong>' + //'<strong>' + 'No results found...' + '</strong>' +
      '</div>');
    } // Update new booking count


    if (undefined !== response_data['ajx_new_bookings_count']) {
      var ajx_new_bookings_count = parseInt(response_data['ajx_new_bookings_count']);

      if (ajx_new_bookings_count > 0) {
        jQuery('.wpbc_badge_count').show();
      }

      jQuery('.bk-update-count').html(ajx_new_bookings_count);
    }

    wpbc_booking_listing_reload_button__spin_pause();
    jQuery('#ajax_respond').html(response_data); // For ability to show response, add such DIV element to page
  }).fail(function (jqXHR, textStatus, errorThrown) {
    if (window.console && window.console.log) {
      console.log('Ajax_Error', jqXHR, textStatus, errorThrown);
    }

    jQuery('.wpbc_ajx_under_toolbar_row').hide(); //FixIn: 9.6.1.5

    var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown;

    if (jqXHR.responseText) {
      error_message += jqXHR.responseText;
    }

    error_message = error_message.replace(/\n/g, "<br />");
    wpbc_ajx_booking_show_message(error_message);
  }) // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
  ; // End Ajax
}
/**
 *   Views  ----------------------------------------------------------------------------------------------------- */

/**
 * Show Listing Table 		and define gMail checkbox hooks
 *
 * @param json_items_arr		- JSON object with Items
 * @param json_search_params	- JSON object with Search
 */


function wpbc_ajx_booking_show_listing(json_items_arr, json_search_params, json_booking_resources) {
  wpbc_ajx_define_templates__resource_manipulation(json_items_arr, json_search_params, json_booking_resources); //console.log( 'json_items_arr' , json_items_arr, json_search_params );

  jQuery('.wpbc_ajx_under_toolbar_row').css("display", "flex"); //FixIn: 9.6.1.5

  var list_header_tpl = wp.template('wpbc_ajx_booking_list_header');
  var list_row_tpl = wp.template('wpbc_ajx_booking_list_row'); // Header

  jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html(list_header_tpl()); // Body

  jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).append('<div class="wpbc_selectable_body"></div>'); // R o w s

  console.groupCollapsed('LISTING_ROWS'); // LISTING_ROWS

  _.each(json_items_arr, function (p_val, p_key, p_data) {
    if ('undefined' !== typeof json_search_params['keyword']) {
      // Parameter for marking keyword with different color in a list
      p_val['__search_request_keyword__'] = json_search_params['keyword'];
    } else {
      p_val['__search_request_keyword__'] = '';
    }

    p_val['booking_resources'] = json_booking_resources;
    jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container') + ' .wpbc_selectable_body').append(list_row_tpl(p_val));
  });

  console.groupEnd(); // LISTING_ROWS

  wpbc_define_gmail_checkbox_selection(jQuery); // Redefine Hooks for clicking at Checkboxes
}
/**
 * Define template for changing booking resources &  update it each time,  when  listing updating, useful  for showing actual  booking resources.
 *
 * @param json_items_arr		- JSON object with Items
 * @param json_search_params	- JSON object with Search
 * @param json_booking_resources	- JSON object with Resources
 */


function wpbc_ajx_define_templates__resource_manipulation(json_items_arr, json_search_params, json_booking_resources) {
  // Change booking resource
  var change_booking_resource_tpl = wp.template('wpbc_ajx_change_booking_resource');
  jQuery('#wpbc_hidden_template__change_booking_resource').html(change_booking_resource_tpl({
    'ajx_search_params': json_search_params,
    'ajx_booking_resources': json_booking_resources
  })); // Duplicate booking resource

  var duplicate_booking_to_other_resource_tpl = wp.template('wpbc_ajx_duplicate_booking_to_other_resource');
  jQuery('#wpbc_hidden_template__duplicate_booking_to_other_resource').html(duplicate_booking_to_other_resource_tpl({
    'ajx_search_params': json_search_params,
    'ajx_booking_resources': json_booking_resources
  }));
}
/**
 * Show just message instead of listing and hide pagination
 */


function wpbc_ajx_booking_show_message(message) {
  wpbc_ajx_booking__actual_listing__hide();
  jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('<div class="wpbc-settings-notice notice-warning" style="text-align:left">' + message + '</div>');
}
/**
 *   H o o k s  -  its Action/Times when need to re-Render Views  ----------------------------------------------- */

/**
 * Send Ajax Search Request after Updating search request parameters
 *
 * @param params_arr
 */


function wpbc_ajx_booking_send_search_request_with_params(params_arr) {
  // Define different Search  parameters for request
  _.each(params_arr, function (p_val, p_key, p_data) {
    //console.log( 'Request for: ', p_key, p_val );
    wpbc_ajx_booking_listing.search_set_param(p_key, p_val);
  }); // Send Ajax Request


  wpbc_ajx_booking_ajax_search_request();
}
/**
 * Search request for "Page Number"
 * @param page_number	int
 */


function wpbc_ajx_booking_pagination_click(page_number) {
  wpbc_ajx_booking_send_search_request_with_params({
    'page_num': page_number
  });
}
/**
 *   Keyword Searching  ----------------------------------------------------------------------------------------- */

/**
 * Search request for "Keyword", also set current page to  1
 *
 * @param element_id	-	HTML ID  of element,  where was entered keyword
 */


function wpbc_ajx_booking_send_search_request_for_keyword(element_id) {
  // We need to Reset page_num to 1 with each new search, because we can be at page #4,  but after  new search  we can  have totally  only  1 page
  wpbc_ajx_booking_send_search_request_with_params({
    'keyword': jQuery(element_id).val(),
    'page_num': 1
  });
}
/**
 * Send search request after few seconds (usually after 1,5 sec)
 * Closure function. Its useful,  for do  not send too many Ajax requests, when someone make fast typing.
 */


var wpbc_ajx_booking_searching_after_few_seconds = function () {
  var closed_timer = 0;
  return function (element_id, timer_delay) {
    // Get default value of "timer_delay",  if parameter was not passed into the function.
    timer_delay = typeof timer_delay !== 'undefined' ? timer_delay : 1500;
    clearTimeout(closed_timer); // Clear previous timer
    // Start new Timer

    closed_timer = setTimeout(wpbc_ajx_booking_send_search_request_for_keyword.bind(null, element_id), timer_delay);
  };
}();
/**
 *   Define Dynamic Hooks  (like pagination click, which renew each time with new listing showing)  ------------- */

/**
 * Define HTML ui Hooks: on KeyUp | Change | -> Sort Order & Number Items / Page
 * We are hcnaged it each  time, when showing new listing, because DOM elements chnaged
 */


function wpbc_ajx_booking_define_ui_hooks() {
  if ('function' === typeof wpbc_define_tippy_tooltips) {
    wpbc_define_tippy_tooltips('.wpbc_listing_container ');
  }

  wpbc_ajx_booking__ui_define__locale();
  wpbc_ajx_booking__ui_define__remark(); // Items Per Page

  jQuery('.wpbc_items_per_page').on('change', function (event) {
    wpbc_ajx_booking_send_search_request_with_params({
      'page_items_count': jQuery(this).val(),
      'page_num': 1
    });
  }); // Sorting

  jQuery('.wpbc_items_sort_type').on('change', function (event) {
    wpbc_ajx_booking_send_search_request_with_params({
      'sort_type': jQuery(this).val()
    });
  });
}
/**
 *   Show / Hide Listing  --------------------------------------------------------------------------------------- */

/**
 *  Show Listing Table 	- 	Sending Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
 */


function wpbc_ajx_booking__actual_listing__show() {
  wpbc_ajx_booking_ajax_search_request(); // Send Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
}
/**
 * Hide Listing Table ( and Pagination )
 */


function wpbc_ajx_booking__actual_listing__hide() {
  jQuery('.wpbc_ajx_under_toolbar_row').hide(); //FixIn: 9.6.1.5

  jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('');
  jQuery(wpbc_ajx_booking_listing.get_other_param('pagination_container')).html('');
}
/**
 *   Support functions for Content Template data  --------------------------------------------------------------- */

/**
 * Highlight strings,
 * by inserting <span class="fieldvalue name fieldsearchvalue">...</span> html  elements into the string.
 * @param {string} booking_details 	- Source string
 * @param {string} booking_keyword	- Keyword to highlight
 * @returns {string}
 */


function wpbc_get_highlighted_search_keyword(booking_details, booking_keyword) {
  booking_keyword = booking_keyword.trim().toLowerCase();

  if (0 == booking_keyword.length) {
    return booking_details;
  } // Highlight substring withing HTML tags in "Content of booking fields data" -- e.g. starting from  >  and ending with <


  var keywordRegex = new RegExp("fieldvalue[^<>]*>([^<]*".concat(booking_keyword, "[^<]*)"), 'gim'); //let matches = [...booking_details.toLowerCase().matchAll( keywordRegex )];

  var matches = booking_details.toLowerCase().matchAll(keywordRegex);
  matches = Array.from(matches);
  var strings_arr = [];
  var pos_previous = 0;
  var search_pos_start;
  var search_pos_end;

  var _iterator = _createForOfIteratorHelper(matches),
      _step;

  try {
    for (_iterator.s(); !(_step = _iterator.n()).done;) {
      var match = _step.value;
      search_pos_start = match.index + match[0].toLowerCase().indexOf('>', 0) + 1;
      strings_arr.push(booking_details.substr(pos_previous, search_pos_start - pos_previous));
      search_pos_end = booking_details.toLowerCase().indexOf('<', search_pos_start);
      strings_arr.push('<span class="fieldvalue name fieldsearchvalue">' + booking_details.substr(search_pos_start, search_pos_end - search_pos_start) + '</span>');
      pos_previous = search_pos_end;
    }
  } catch (err) {
    _iterator.e(err);
  } finally {
    _iterator.f();
  }

  strings_arr.push(booking_details.substr(pos_previous, booking_details.length - pos_previous));
  return strings_arr.join('');
}
/**
 * Convert special HTML characters   from:	 &amp; 	-> 	&
 *
 * @param text
 * @returns {*}
 */


function wpbc_decode_HTML_entities(text) {
  var textArea = document.createElement('textarea');
  textArea.innerHTML = text;
  return textArea.value;
}
/**
 * Convert TO special HTML characters   from:	 & 	-> 	&amp;
 *
 * @param text
 * @returns {*}
 */


function wpbc_encode_HTML_entities(text) {
  var textArea = document.createElement('textarea');
  textArea.innerText = text;
  return textArea.innerHTML;
}
/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Spin button in Filter toolbar  -  Start
 */


function wpbc_booking_listing_reload_button__spin_start() {
  jQuery('#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin').removeClass('wpbc_animation_pause');
}
/**
 * Spin button in Filter toolbar  -  Pause
 */


function wpbc_booking_listing_reload_button__spin_pause() {
  jQuery('#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin').addClass('wpbc_animation_pause');
}
/**
 * Spin button in Filter toolbar  -  is Spinning ?
 *
 * @returns {boolean}
 */


function wpbc_booking_listing_reload_button__is_spin() {
  if (jQuery('#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin').hasClass('wpbc_animation_pause')) {
    return true;
  } else {
    return false;
  }
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImluY2x1ZGVzL3BhZ2UtYm9va2luZ3MvX3NyYy9ib29raW5nc19fbGlzdGluZy5qcyJdLCJuYW1lcyI6WyJqUXVlcnkiLCJvbiIsImUiLCJlYWNoIiwiaW5kZXgiLCJ0ZF9lbCIsImdldCIsInVuZGVmaW5lZCIsIl90aXBweSIsImluc3RhbmNlIiwiaGlkZSIsIndwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZyIsIm9iaiIsIiQiLCJwX3NlY3VyZSIsInNlY3VyaXR5X29iaiIsInVzZXJfaWQiLCJub25jZSIsImxvY2FsZSIsInNldF9zZWN1cmVfcGFyYW0iLCJwYXJhbV9rZXkiLCJwYXJhbV92YWwiLCJnZXRfc2VjdXJlX3BhcmFtIiwicF9saXN0aW5nIiwic2VhcmNoX3JlcXVlc3Rfb2JqIiwic29ydCIsInNvcnRfdHlwZSIsInBhZ2VfbnVtIiwicGFnZV9pdGVtc19jb3VudCIsImNyZWF0ZV9kYXRlIiwia2V5d29yZCIsInNvdXJjZSIsInNlYXJjaF9zZXRfYWxsX3BhcmFtcyIsInJlcXVlc3RfcGFyYW1fb2JqIiwic2VhcmNoX2dldF9hbGxfcGFyYW1zIiwic2VhcmNoX2dldF9wYXJhbSIsInNlYXJjaF9zZXRfcGFyYW0iLCJzZWFyY2hfc2V0X3BhcmFtc19hcnIiLCJwYXJhbXNfYXJyIiwiXyIsInBfdmFsIiwicF9rZXkiLCJwX2RhdGEiLCJwX290aGVyIiwib3RoZXJfb2JqIiwic2V0X290aGVyX3BhcmFtIiwiZ2V0X290aGVyX3BhcmFtIiwid3BiY19hanhfYm9va2luZ19hamF4X3NlYXJjaF9yZXF1ZXN0IiwiY29uc29sZSIsImdyb3VwQ29sbGFwc2VkIiwibG9nIiwid3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCIsInBvc3QiLCJ3cGJjX2dsb2JhbDEiLCJ3cGJjX2FqYXh1cmwiLCJhY3Rpb24iLCJ3cGJjX2FqeF91c2VyX2lkIiwid3BiY19hanhfbG9jYWxlIiwic2VhcmNoX3BhcmFtcyIsInJlc3BvbnNlX2RhdGEiLCJ0ZXh0U3RhdHVzIiwianFYSFIiLCJncm91cEVuZCIsImh0bWwiLCJsb2NhdGlvbiIsInJlbG9hZCIsIndwYmNfYWp4X2Jvb2tpbmdfc2hvd19saXN0aW5nIiwid3BiY19wYWdpbmF0aW9uX2VjaG8iLCJNYXRoIiwiY2VpbCIsIndwYmNfYWp4X2Jvb2tpbmdfZGVmaW5lX3VpX2hvb2tzIiwid3BiY19hanhfYm9va2luZ19fYWN0dWFsX2xpc3RpbmdfX2hpZGUiLCJhanhfbmV3X2Jvb2tpbmdzX2NvdW50IiwicGFyc2VJbnQiLCJzaG93Iiwid3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSIsImZhaWwiLCJlcnJvclRocm93biIsIndpbmRvdyIsImVycm9yX21lc3NhZ2UiLCJyZXNwb25zZVRleHQiLCJyZXBsYWNlIiwid3BiY19hanhfYm9va2luZ19zaG93X21lc3NhZ2UiLCJqc29uX2l0ZW1zX2FyciIsImpzb25fc2VhcmNoX3BhcmFtcyIsImpzb25fYm9va2luZ19yZXNvdXJjZXMiLCJ3cGJjX2FqeF9kZWZpbmVfdGVtcGxhdGVzX19yZXNvdXJjZV9tYW5pcHVsYXRpb24iLCJjc3MiLCJsaXN0X2hlYWRlcl90cGwiLCJ3cCIsInRlbXBsYXRlIiwibGlzdF9yb3dfdHBsIiwiYXBwZW5kIiwid3BiY19kZWZpbmVfZ21haWxfY2hlY2tib3hfc2VsZWN0aW9uIiwiY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfdHBsIiwiZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfdHBsIiwibWVzc2FnZSIsIndwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF93aXRoX3BhcmFtcyIsIndwYmNfYWp4X2Jvb2tpbmdfcGFnaW5hdGlvbl9jbGljayIsInBhZ2VfbnVtYmVyIiwid3BiY19hanhfYm9va2luZ19zZW5kX3NlYXJjaF9yZXF1ZXN0X2Zvcl9rZXl3b3JkIiwiZWxlbWVudF9pZCIsInZhbCIsIndwYmNfYWp4X2Jvb2tpbmdfc2VhcmNoaW5nX2FmdGVyX2Zld19zZWNvbmRzIiwiY2xvc2VkX3RpbWVyIiwidGltZXJfZGVsYXkiLCJjbGVhclRpbWVvdXQiLCJzZXRUaW1lb3V0IiwiYmluZCIsIndwYmNfZGVmaW5lX3RpcHB5X3Rvb2x0aXBzIiwid3BiY19hanhfYm9va2luZ19fdWlfZGVmaW5lX19sb2NhbGUiLCJ3cGJjX2FqeF9ib29raW5nX191aV9kZWZpbmVfX3JlbWFyayIsImV2ZW50Iiwid3BiY19hanhfYm9va2luZ19fYWN0dWFsX2xpc3RpbmdfX3Nob3ciLCJ3cGJjX2dldF9oaWdobGlnaHRlZF9zZWFyY2hfa2V5d29yZCIsImJvb2tpbmdfZGV0YWlscyIsImJvb2tpbmdfa2V5d29yZCIsInRyaW0iLCJ0b0xvd2VyQ2FzZSIsImxlbmd0aCIsImtleXdvcmRSZWdleCIsIlJlZ0V4cCIsIm1hdGNoZXMiLCJtYXRjaEFsbCIsIkFycmF5IiwiZnJvbSIsInN0cmluZ3NfYXJyIiwicG9zX3ByZXZpb3VzIiwic2VhcmNoX3Bvc19zdGFydCIsInNlYXJjaF9wb3NfZW5kIiwibWF0Y2giLCJpbmRleE9mIiwicHVzaCIsInN1YnN0ciIsImpvaW4iLCJ3cGJjX2RlY29kZV9IVE1MX2VudGl0aWVzIiwidGV4dCIsInRleHRBcmVhIiwiZG9jdW1lbnQiLCJjcmVhdGVFbGVtZW50IiwiaW5uZXJIVE1MIiwidmFsdWUiLCJ3cGJjX2VuY29kZV9IVE1MX2VudGl0aWVzIiwiaW5uZXJUZXh0IiwicmVtb3ZlQ2xhc3MiLCJhZGRDbGFzcyIsIndwYmNfYm9va2luZ19saXN0aW5nX3JlbG9hZF9idXR0b25fX2lzX3NwaW4iLCJoYXNDbGFzcyJdLCJtYXBwaW5ncyI6IkFBQUE7Ozs7Ozs7Ozs7QUFFQUEsTUFBTSxDQUFDLE1BQUQsQ0FBTixDQUFlQyxFQUFmLENBQWtCO0FBQ2QsZUFBYSxtQkFBU0MsQ0FBVCxFQUFZO0FBRTNCRixJQUFBQSxNQUFNLENBQUUsY0FBRixDQUFOLENBQXlCRyxJQUF6QixDQUErQixVQUFXQyxLQUFYLEVBQWtCO0FBRWhELFVBQUlD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IjtcclxuXHJcbmpRdWVyeSgnYm9keScpLm9uKHtcclxuICAgICd0b3VjaG1vdmUnOiBmdW5jdGlvbihlKSB7XHJcblxyXG5cdFx0alF1ZXJ5KCAnLnRpbWVzcGFydGx5JyApLmVhY2goIGZ1bmN0aW9uICggaW5kZXggKXtcclxuXHJcblx0XHRcdHZhciB0ZF9lbCA9IGpRdWVyeSggdGhpcyApLmdldCggMCApO1xyXG5cclxuXHRcdFx0aWYgKCAodW5kZWZpbmVkICE9IHRkX2VsLl90aXBweSkgKXtcclxuXHJcblx0XHRcdFx0dmFyIGluc3RhbmNlID0gdGRfZWwuX3RpcHB5O1xyXG5cdFx0XHRcdGluc3RhbmNlLmhpZGUoKTtcclxuXHRcdFx0fVxyXG5cdFx0fSApO1xyXG5cdH1cclxufSk7XHJcblxyXG4vKipcclxuICogUmVxdWVzdCBPYmplY3RcclxuICogSGVyZSB3ZSBjYW4gIGRlZmluZSBTZWFyY2ggcGFyYW1ldGVycyBhbmQgVXBkYXRlIGl0IGxhdGVyLCAgd2hlbiAgc29tZSBwYXJhbWV0ZXIgd2FzIGNoYW5nZWRcclxuICpcclxuICovXHJcbnZhciB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcgPSAoZnVuY3Rpb24gKCBvYmosICQpIHtcclxuXHJcblx0Ly8gU2VjdXJlIHBhcmFtZXRlcnMgZm9yIEFqYXhcdC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHZhciBwX3NlY3VyZSA9IG9iai5zZWN1cml0eV9vYmogPSBvYmouc2VjdXJpdHlfb2JqIHx8IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0dXNlcl9pZDogMCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0bm9uY2UgIDogJycsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGxvY2FsZSA6ICcnXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIH07XHJcblxyXG5cdG9iai5zZXRfc2VjdXJlX3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXksIHBhcmFtX3ZhbCApIHtcclxuXHRcdHBfc2VjdXJlWyBwYXJhbV9rZXkgXSA9IHBhcmFtX3ZhbDtcclxuXHR9O1xyXG5cclxuXHRvYmouZ2V0X3NlY3VyZV9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5ICkge1xyXG5cdFx0cmV0dXJuIHBfc2VjdXJlWyBwYXJhbV9rZXkgXTtcclxuXHR9O1xyXG5cclxuXHJcblx0Ly8gTGlzdGluZyBTZWFyY2ggcGFyYW1ldGVyc1x0LS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcblx0dmFyIHBfbGlzdGluZyA9IG9iai5zZWFyY2hfcmVxdWVzdF9vYmogPSBvYmouc2VhcmNoX3JlcXVlc3Rfb2JqIHx8IHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0c29ydCAgICAgICAgICAgIDogXCJib29raW5nX2lkXCIsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdHNvcnRfdHlwZSAgICAgICA6IFwiREVTQ1wiLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRwYWdlX251bSAgICAgICAgOiAxLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRwYWdlX2l0ZW1zX2NvdW50OiAxMCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Y3JlYXRlX2RhdGUgICAgIDogXCJcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0a2V5d29yZCAgICAgICAgIDogXCJcIixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0c291cmNlICAgICAgICAgIDogXCJcIlxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9zZXRfYWxsX3BhcmFtcyA9IGZ1bmN0aW9uICggcmVxdWVzdF9wYXJhbV9vYmogKSB7XHJcblx0XHRwX2xpc3RpbmcgPSByZXF1ZXN0X3BhcmFtX29iajtcclxuXHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX2dldF9hbGxfcGFyYW1zID0gZnVuY3Rpb24gKCkge1xyXG5cdFx0cmV0dXJuIHBfbGlzdGluZztcclxuXHR9O1xyXG5cclxuXHRvYmouc2VhcmNoX2dldF9wYXJhbSA9IGZ1bmN0aW9uICggcGFyYW1fa2V5ICkge1xyXG5cdFx0cmV0dXJuIHBfbGlzdGluZ1sgcGFyYW1fa2V5IF07XHJcblx0fTtcclxuXHJcblx0b2JqLnNlYXJjaF9zZXRfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSwgcGFyYW1fdmFsICkge1xyXG5cdFx0Ly8gaWYgKCBBcnJheS5pc0FycmF5KCBwYXJhbV92YWwgKSApe1xyXG5cdFx0Ly8gXHRwYXJhbV92YWwgPSBKU09OLnN0cmluZ2lmeSggcGFyYW1fdmFsICk7XHJcblx0XHQvLyB9XHJcblx0XHRwX2xpc3RpbmdbIHBhcmFtX2tleSBdID0gcGFyYW1fdmFsO1xyXG5cdH07XHJcblxyXG5cdG9iai5zZWFyY2hfc2V0X3BhcmFtc19hcnIgPSBmdW5jdGlvbiggcGFyYW1zX2FyciApe1xyXG5cdFx0Xy5lYWNoKCBwYXJhbXNfYXJyLCBmdW5jdGlvbiAoIHBfdmFsLCBwX2tleSwgcF9kYXRhICl7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gRGVmaW5lIGRpZmZlcmVudCBTZWFyY2ggIHBhcmFtZXRlcnMgZm9yIHJlcXVlc3RcclxuXHRcdFx0dGhpcy5zZWFyY2hfc2V0X3BhcmFtKCBwX2tleSwgcF92YWwgKTtcclxuXHRcdH0gKTtcclxuXHR9XHJcblxyXG5cclxuXHQvLyBPdGhlciBwYXJhbWV0ZXJzIFx0XHRcdC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cdHZhciBwX290aGVyID0gb2JqLm90aGVyX29iaiA9IG9iai5vdGhlcl9vYmogfHwgeyB9O1xyXG5cclxuXHRvYmouc2V0X290aGVyX3BhcmFtID0gZnVuY3Rpb24gKCBwYXJhbV9rZXksIHBhcmFtX3ZhbCApIHtcclxuXHRcdHBfb3RoZXJbIHBhcmFtX2tleSBdID0gcGFyYW1fdmFsO1xyXG5cdH07XHJcblxyXG5cdG9iai5nZXRfb3RoZXJfcGFyYW0gPSBmdW5jdGlvbiAoIHBhcmFtX2tleSApIHtcclxuXHRcdHJldHVybiBwX290aGVyWyBwYXJhbV9rZXkgXTtcclxuXHR9O1xyXG5cclxuXHJcblx0cmV0dXJuIG9iajtcclxufSggd3BiY19hanhfYm9va2luZ19saXN0aW5nIHx8IHt9LCBqUXVlcnkgKSk7XHJcblxyXG5cclxuLyoqXHJcbiAqICAgQWpheCAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU2VuZCBBamF4IHNlYXJjaCByZXF1ZXN0XHJcbiAqIGZvciBzZWFyY2hpbmcgc3BlY2lmaWMgS2V5d29yZCBhbmQgb3RoZXIgcGFyYW1zXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX2FqYXhfc2VhcmNoX3JlcXVlc3QoKXtcclxuXHJcbmNvbnNvbGUuZ3JvdXBDb2xsYXBzZWQoJ0FKWF9CT09LSU5HX0xJU1RJTkcnKTsgY29uc29sZS5sb2coICcgPT0gQmVmb3JlIEFqYXggU2VuZCAtIHNlYXJjaF9nZXRfYWxsX3BhcmFtcygpID09ICcgLCB3cGJjX2FqeF9ib29raW5nX2xpc3Rpbmcuc2VhcmNoX2dldF9hbGxfcGFyYW1zKCkgKTtcclxuXHJcblx0d3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCgpO1xyXG5cclxuLypcclxuLy9GaXhJbjogZm9yVmlkZW9cclxuaWYgKCAhIGlzX3RoaXNfYWN0aW9uICl7XHJcblx0Ly93cGJjX2FqeF9ib29raW5nX19hY3R1YWxfbGlzdGluZ19faGlkZSgpO1xyXG5cdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbChcclxuXHRcdCc8ZGl2IHN0eWxlPVwid2lkdGg6MTAwJTt0ZXh0LWFsaWduOiBjZW50ZXI7XCIgaWQ9XCJ3cGJjX2xvYWRpbmdfc2VjdGlvblwiPjxzcGFuIGNsYXNzPVwid3BiY19pY25fYXV0b3JlbmV3IHdwYmNfc3BpblwiPjwvc3Bhbj48L2Rpdj4nXHJcblx0XHQrIGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICkuaHRtbCgpXHJcblx0KTtcclxuXHRpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiAoalF1ZXJ5KCAnI3dwYmNfbG9hZGluZ19zZWN0aW9uJyApLndwYmNfbXlfbW9kYWwpICl7XHRcdFx0Ly9GaXhJbjogOS4wLjEuNVxyXG5cdFx0alF1ZXJ5KCAnI3dwYmNfbG9hZGluZ19zZWN0aW9uJyApLndwYmNfbXlfbW9kYWwoICdzaG93JyApO1xyXG5cdH0gZWxzZSB7XHJcblx0XHRhbGVydCggJ1dhcm5pbmchIEJvb2tpbmcgQ2FsZW5kYXIuIEl0cyBzZWVtcyB0aGF0ICB5b3UgaGF2ZSBkZWFjdGl2YXRlZCBsb2FkaW5nIG9mIEJvb3RzdHJhcCBKUyBmaWxlcyBhdCBCb29raW5nIFNldHRpbmdzIEdlbmVyYWwgcGFnZSBpbiBBZHZhbmNlZCBzZWN0aW9uLicgKVxyXG5cdH1cclxufVxyXG5pc190aGlzX2FjdGlvbiA9IGZhbHNlO1xyXG4qL1xyXG5cdC8vIFN0YXJ0IEFqYXhcclxuXHRqUXVlcnkucG9zdCggd3BiY19nbG9iYWwxLndwYmNfYWpheHVybCxcclxuXHRcdFx0XHR7XHJcblx0XHRcdFx0XHRhY3Rpb24gICAgICAgICAgOiAnV1BCQ19BSlhfQk9PS0lOR19MSVNUSU5HJyxcclxuXHRcdFx0XHRcdHdwYmNfYWp4X3VzZXJfaWQ6IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfc2VjdXJlX3BhcmFtKCAndXNlcl9pZCcgKSxcclxuXHRcdFx0XHRcdG5vbmNlICAgICAgICAgICA6IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfc2VjdXJlX3BhcmFtKCAnbm9uY2UnICksXHJcblx0XHRcdFx0XHR3cGJjX2FqeF9sb2NhbGUgOiB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X3NlY3VyZV9wYXJhbSggJ2xvY2FsZScgKSxcclxuXHJcblx0XHRcdFx0XHRzZWFyY2hfcGFyYW1zXHQ6IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5zZWFyY2hfZ2V0X2FsbF9wYXJhbXMoKVxyXG5cdFx0XHRcdH0sXHJcblx0XHRcdFx0LyoqXHJcblx0XHRcdFx0ICogUyB1IGMgYyBlIHMgc1xyXG5cdFx0XHRcdCAqXHJcblx0XHRcdFx0ICogQHBhcmFtIHJlc3BvbnNlX2RhdGFcdFx0LVx0aXRzIG9iamVjdCByZXR1cm5lZCBmcm9tICBBamF4IC0gY2xhc3MtbGl2ZS1zZWFyY2cucGhwXHJcblx0XHRcdFx0ICogQHBhcmFtIHRleHRTdGF0dXNcdFx0LVx0J3N1Y2Nlc3MnXHJcblx0XHRcdFx0ICogQHBhcmFtIGpxWEhSXHRcdFx0XHQtXHRPYmplY3RcclxuXHRcdFx0XHQgKi9cclxuXHRcdFx0XHRmdW5jdGlvbiAoIHJlc3BvbnNlX2RhdGEsIHRleHRTdGF0dXMsIGpxWEhSICkge1xyXG4vL0ZpeEluOiBmb3JWaWRlb1xyXG4vL2pRdWVyeSggJyN3cGJjX2xvYWRpbmdfc2VjdGlvbicgKS53cGJjX215X21vZGFsKCAnaGlkZScgKTtcclxuXHJcbmNvbnNvbGUubG9nKCAnID09IFJlc3BvbnNlIFdQQkNfQUpYX0JPT0tJTkdfTElTVElORyA9PSAnLCByZXNwb25zZV9kYXRhICk7IGNvbnNvbGUuZ3JvdXBFbmQoKTtcclxuXHRcdFx0XHRcdC8vIFByb2JhYmx5IEVycm9yXHJcblx0XHRcdFx0XHRpZiAoICh0eXBlb2YgcmVzcG9uc2VfZGF0YSAhPT0gJ29iamVjdCcpIHx8IChyZXNwb25zZV9kYXRhID09PSBudWxsKSApe1xyXG5cdFx0XHRcdFx0XHRqUXVlcnkoICcud3BiY19hanhfdW5kZXJfdG9vbGJhcl9yb3cnICkuaGlkZSgpO1x0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly9GaXhJbjogOS42LjEuNVxyXG5cdFx0XHRcdFx0XHRqUXVlcnkoIHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSApLmh0bWwoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPGRpdiBjbGFzcz1cIndwYmMtc2V0dGluZ3Mtbm90aWNlIG5vdGljZS13YXJuaW5nXCIgc3R5bGU9XCJ0ZXh0LWFsaWduOmxlZnRcIj4nICtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0cmVzcG9uc2VfZGF0YSArXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPC9kaXY+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG5cdFx0XHRcdFx0XHRyZXR1cm47XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0Ly8gUmVsb2FkIHBhZ2UsIGFmdGVyIGZpbHRlciB0b29sYmFyIHdhcyByZXNldGVkXHJcblx0XHRcdFx0XHRpZiAoICAgICAgICggICAgIHVuZGVmaW5lZCAhPSByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdKVxyXG5cdFx0XHRcdFx0XHRcdCYmICggJ3Jlc2V0X2RvbmUnID09PSByZXNwb25zZV9kYXRhWyAnYWp4X2NsZWFuZWRfcGFyYW1zJyBdWyAndWlfcmVzZXQnIF0pXHJcblx0XHRcdFx0XHQpe1xyXG5cdFx0XHRcdFx0XHRsb2NhdGlvbi5yZWxvYWQoKTtcclxuXHRcdFx0XHRcdFx0cmV0dXJuO1xyXG5cdFx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHRcdC8vIFNob3cgbGlzdGluZ1xyXG5cdFx0XHRcdFx0aWYgKCByZXNwb25zZV9kYXRhWyAnYWp4X2NvdW50JyBdID4gMCApe1xyXG5cclxuXHRcdFx0XHRcdFx0d3BiY19hanhfYm9va2luZ19zaG93X2xpc3RpbmcoIHJlc3BvbnNlX2RhdGFbICdhanhfaXRlbXMnIF0sIHJlc3BvbnNlX2RhdGFbICdhanhfc2VhcmNoX3BhcmFtcycgXSwgcmVzcG9uc2VfZGF0YVsgJ2FqeF9ib29raW5nX3Jlc291cmNlcycgXSApO1xyXG5cclxuXHRcdFx0XHRcdFx0d3BiY19wYWdpbmF0aW9uX2VjaG8oXHJcblx0XHRcdFx0XHRcdFx0d3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ3BhZ2luYXRpb25fY29udGFpbmVyJyApLFxyXG5cdFx0XHRcdFx0XHRcdHtcclxuXHRcdFx0XHRcdFx0XHRcdCdwYWdlX2FjdGl2ZSc6IHJlc3BvbnNlX2RhdGFbICdhanhfc2VhcmNoX3BhcmFtcycgXVsgJ3BhZ2VfbnVtJyBdLFxyXG5cdFx0XHRcdFx0XHRcdFx0J3BhZ2VzX2NvdW50JzogTWF0aC5jZWlsKCByZXNwb25zZV9kYXRhWyAnYWp4X2NvdW50JyBdIC8gcmVzcG9uc2VfZGF0YVsgJ2FqeF9zZWFyY2hfcGFyYW1zJyBdWyAncGFnZV9pdGVtc19jb3VudCcgXSApLFxyXG5cclxuXHRcdFx0XHRcdFx0XHRcdCdwYWdlX2l0ZW1zX2NvdW50JzogcmVzcG9uc2VfZGF0YVsgJ2FqeF9zZWFyY2hfcGFyYW1zJyBdWyAncGFnZV9pdGVtc19jb3VudCcgXSxcclxuXHRcdFx0XHRcdFx0XHRcdCdzb3J0X3R5cGUnICAgICAgIDogcmVzcG9uc2VfZGF0YVsgJ2FqeF9zZWFyY2hfcGFyYW1zJyBdWyAnc29ydF90eXBlJyBdXHJcblx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHQpO1xyXG5cdFx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX2RlZmluZV91aV9ob29rcygpO1x0XHRcdFx0XHRcdC8vIFJlZGVmaW5lIEhvb2tzLCBiZWNhdXNlIHdlIHNob3cgbmV3IERPTSBlbGVtZW50c1xyXG5cclxuXHRcdFx0XHRcdH0gZWxzZSB7XHJcblxyXG5cdFx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX19hY3R1YWxfbGlzdGluZ19faGlkZSgpO1xyXG5cdFx0XHRcdFx0XHRqUXVlcnkoIHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSApLmh0bWwoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPGRpdiBjbGFzcz1cIndwYmMtc2V0dGluZ3Mtbm90aWNlMCBub3RpY2Utd2FybmluZzBcIiBzdHlsZT1cInRleHQtYWxpZ246Y2VudGVyO21hcmdpbi1sZWZ0Oi01MHB4O1wiPicgK1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPHN0cm9uZz4nICsgJ05vIHJlc3VsdHMgZm91bmQgZm9yIGN1cnJlbnQgZmlsdGVyIG9wdGlvbnMuLi4nICsgJzwvc3Ryb25nPicgK1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvLyc8c3Ryb25nPicgKyAnTm8gcmVzdWx0cyBmb3VuZC4uLicgKyAnPC9zdHJvbmc+JyArXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPC9kaXY+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG5cdFx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHRcdC8vIFVwZGF0ZSBuZXcgYm9va2luZyBjb3VudFxyXG5cdFx0XHRcdFx0aWYgKCB1bmRlZmluZWQgIT09IHJlc3BvbnNlX2RhdGFbICdhanhfbmV3X2Jvb2tpbmdzX2NvdW50JyBdICl7XHJcblx0XHRcdFx0XHRcdHZhciBhanhfbmV3X2Jvb2tpbmdzX2NvdW50ID0gcGFyc2VJbnQoIHJlc3BvbnNlX2RhdGFbICdhanhfbmV3X2Jvb2tpbmdzX2NvdW50JyBdIClcclxuXHRcdFx0XHRcdFx0aWYgKGFqeF9uZXdfYm9va2luZ3NfY291bnQ+MCl7XHJcblx0XHRcdFx0XHRcdFx0alF1ZXJ5KCAnLndwYmNfYmFkZ2VfY291bnQnICkuc2hvdygpO1xyXG5cdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdGpRdWVyeSggJy5iay11cGRhdGUtY291bnQnICkuaHRtbCggYWp4X25ld19ib29raW5nc19jb3VudCApO1xyXG5cdFx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHRcdHdwYmNfYm9va2luZ19saXN0aW5nX3JlbG9hZF9idXR0b25fX3NwaW5fcGF1c2UoKTtcclxuXHJcblx0XHRcdFx0XHRqUXVlcnkoICcjYWpheF9yZXNwb25kJyApLmh0bWwoIHJlc3BvbnNlX2RhdGEgKTtcdFx0Ly8gRm9yIGFiaWxpdHkgdG8gc2hvdyByZXNwb25zZSwgYWRkIHN1Y2ggRElWIGVsZW1lbnQgdG8gcGFnZVxyXG5cdFx0XHRcdH1cclxuXHRcdFx0ICApLmZhaWwoIGZ1bmN0aW9uICgganFYSFIsIHRleHRTdGF0dXMsIGVycm9yVGhyb3duICkgeyAgICBpZiAoIHdpbmRvdy5jb25zb2xlICYmIHdpbmRvdy5jb25zb2xlLmxvZyApeyBjb25zb2xlLmxvZyggJ0FqYXhfRXJyb3InLCBqcVhIUiwgdGV4dFN0YXR1cywgZXJyb3JUaHJvd24gKTsgfVxyXG5cdFx0XHRcdFx0alF1ZXJ5KCAnLndwYmNfYWp4X3VuZGVyX3Rvb2xiYXJfcm93JyApLmhpZGUoKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly9GaXhJbjogOS42LjEuNVxyXG5cdFx0XHRcdFx0dmFyIGVycm9yX21lc3NhZ2UgPSAnPHN0cm9uZz4nICsgJ0Vycm9yIScgKyAnPC9zdHJvbmc+ICcgKyBlcnJvclRocm93biA7XHJcblx0XHRcdFx0XHRpZiAoIGpxWEhSLnJlc3BvbnNlVGV4dCApe1xyXG5cdFx0XHRcdFx0XHRlcnJvcl9tZXNzYWdlICs9IGpxWEhSLnJlc3BvbnNlVGV4dDtcclxuXHRcdFx0XHRcdH1cclxuXHRcdFx0XHRcdGVycm9yX21lc3NhZ2UgPSBlcnJvcl9tZXNzYWdlLnJlcGxhY2UoIC9cXG4vZywgXCI8YnIgLz5cIiApO1xyXG5cclxuXHRcdFx0XHRcdHdwYmNfYWp4X2Jvb2tpbmdfc2hvd19tZXNzYWdlKCBlcnJvcl9tZXNzYWdlICk7XHJcblx0XHRcdCAgfSlcclxuXHQgICAgICAgICAgLy8gLmRvbmUoICAgZnVuY3Rpb24gKCBkYXRhLCB0ZXh0U3RhdHVzLCBqcVhIUiApIHsgICBpZiAoIHdpbmRvdy5jb25zb2xlICYmIHdpbmRvdy5jb25zb2xlLmxvZyApeyBjb25zb2xlLmxvZyggJ3NlY29uZCBzdWNjZXNzJywgZGF0YSwgdGV4dFN0YXR1cywganFYSFIgKTsgfSAgICB9KVxyXG5cdFx0XHQgIC8vIC5hbHdheXMoIGZ1bmN0aW9uICggZGF0YV9qcVhIUiwgdGV4dFN0YXR1cywganFYSFJfZXJyb3JUaHJvd24gKSB7ICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdhbHdheXMgZmluaXNoZWQnLCBkYXRhX2pxWEhSLCB0ZXh0U3RhdHVzLCBqcVhIUl9lcnJvclRocm93biApOyB9ICAgICB9KVxyXG5cdFx0XHQgIDsgIC8vIEVuZCBBamF4XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBWaWV3cyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBTaG93IExpc3RpbmcgVGFibGUgXHRcdGFuZCBkZWZpbmUgZ01haWwgY2hlY2tib3ggaG9va3NcclxuICpcclxuICogQHBhcmFtIGpzb25faXRlbXNfYXJyXHRcdC0gSlNPTiBvYmplY3Qgd2l0aCBJdGVtc1xyXG4gKiBAcGFyYW0ganNvbl9zZWFyY2hfcGFyYW1zXHQtIEpTT04gb2JqZWN0IHdpdGggU2VhcmNoXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX3Nob3dfbGlzdGluZygganNvbl9pdGVtc19hcnIsIGpzb25fc2VhcmNoX3BhcmFtcywganNvbl9ib29raW5nX3Jlc291cmNlcyApe1xyXG5cclxuXHR3cGJjX2FqeF9kZWZpbmVfdGVtcGxhdGVzX19yZXNvdXJjZV9tYW5pcHVsYXRpb24oIGpzb25faXRlbXNfYXJyLCBqc29uX3NlYXJjaF9wYXJhbXMsIGpzb25fYm9va2luZ19yZXNvdXJjZXMgKTtcclxuXHJcbi8vY29uc29sZS5sb2coICdqc29uX2l0ZW1zX2FycicgLCBqc29uX2l0ZW1zX2FyciwganNvbl9zZWFyY2hfcGFyYW1zICk7XHJcblx0alF1ZXJ5KCAnLndwYmNfYWp4X3VuZGVyX3Rvb2xiYXJfcm93JyApLmNzcyggXCJkaXNwbGF5XCIsIFwiZmxleFwiICk7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL0ZpeEluOiA5LjYuMS41XHJcblx0dmFyIGxpc3RfaGVhZGVyX3RwbCA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfYm9va2luZ19saXN0X2hlYWRlcicgKTtcclxuXHR2YXIgbGlzdF9yb3dfdHBsICAgID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF9ib29raW5nX2xpc3Rfcm93JyApO1xyXG5cclxuXHJcblx0Ly8gSGVhZGVyXHJcblx0alF1ZXJ5KCB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInICkgKS5odG1sKCBsaXN0X2hlYWRlcl90cGwoKSApO1xyXG5cclxuXHQvLyBCb2R5XHJcblx0alF1ZXJ5KCB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInICkgKS5hcHBlbmQoICc8ZGl2IGNsYXNzPVwid3BiY19zZWxlY3RhYmxlX2JvZHlcIj48L2Rpdj4nICk7XHJcblxyXG5cdC8vIFIgbyB3IHNcclxuY29uc29sZS5ncm91cENvbGxhcHNlZCggJ0xJU1RJTkdfUk9XUycgKTtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly8gTElTVElOR19ST1dTXHJcblx0Xy5lYWNoKCBqc29uX2l0ZW1zX2FyciwgZnVuY3Rpb24gKCBwX3ZhbCwgcF9rZXksIHBfZGF0YSApe1xyXG5cdFx0aWYgKCAndW5kZWZpbmVkJyAhPT0gdHlwZW9mIGpzb25fc2VhcmNoX3BhcmFtc1sgJ2tleXdvcmQnIF0gKXtcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIFBhcmFtZXRlciBmb3IgbWFya2luZyBrZXl3b3JkIHdpdGggZGlmZmVyZW50IGNvbG9yIGluIGEgbGlzdFxyXG5cdFx0XHRwX3ZhbFsgJ19fc2VhcmNoX3JlcXVlc3Rfa2V5d29yZF9fJyBdID0ganNvbl9zZWFyY2hfcGFyYW1zWyAna2V5d29yZCcgXTtcclxuXHRcdH0gZWxzZSB7XHJcblx0XHRcdHBfdmFsWyAnX19zZWFyY2hfcmVxdWVzdF9rZXl3b3JkX18nIF0gPSAnJztcclxuXHRcdH1cclxuXHRcdHBfdmFsWyAnYm9va2luZ19yZXNvdXJjZXMnIF0gPSBqc29uX2Jvb2tpbmdfcmVzb3VyY2VzO1xyXG5cdFx0alF1ZXJ5KCB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInICkgKyAnIC53cGJjX3NlbGVjdGFibGVfYm9keScgKS5hcHBlbmQoIGxpc3Rfcm93X3RwbCggcF92YWwgKSApO1xyXG5cdH0gKTtcclxuY29uc29sZS5ncm91cEVuZCgpOyBcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vIExJU1RJTkdfUk9XU1xyXG5cclxuXHR3cGJjX2RlZmluZV9nbWFpbF9jaGVja2JveF9zZWxlY3Rpb24oIGpRdWVyeSApO1x0XHRcdFx0XHRcdC8vIFJlZGVmaW5lIEhvb2tzIGZvciBjbGlja2luZyBhdCBDaGVja2JveGVzXHJcbn1cclxuXHJcblxyXG5cdC8qKlxyXG5cdCAqIERlZmluZSB0ZW1wbGF0ZSBmb3IgY2hhbmdpbmcgYm9va2luZyByZXNvdXJjZXMgJiAgdXBkYXRlIGl0IGVhY2ggdGltZSwgIHdoZW4gIGxpc3RpbmcgdXBkYXRpbmcsIHVzZWZ1bCAgZm9yIHNob3dpbmcgYWN0dWFsICBib29raW5nIHJlc291cmNlcy5cclxuXHQgKlxyXG5cdCAqIEBwYXJhbSBqc29uX2l0ZW1zX2Fyclx0XHQtIEpTT04gb2JqZWN0IHdpdGggSXRlbXNcclxuXHQgKiBAcGFyYW0ganNvbl9zZWFyY2hfcGFyYW1zXHQtIEpTT04gb2JqZWN0IHdpdGggU2VhcmNoXHJcblx0ICogQHBhcmFtIGpzb25fYm9va2luZ19yZXNvdXJjZXNcdC0gSlNPTiBvYmplY3Qgd2l0aCBSZXNvdXJjZXNcclxuXHQgKi9cclxuXHRmdW5jdGlvbiB3cGJjX2FqeF9kZWZpbmVfdGVtcGxhdGVzX19yZXNvdXJjZV9tYW5pcHVsYXRpb24oIGpzb25faXRlbXNfYXJyLCBqc29uX3NlYXJjaF9wYXJhbXMsIGpzb25fYm9va2luZ19yZXNvdXJjZXMgKXtcclxuXHJcblx0XHQvLyBDaGFuZ2UgYm9va2luZyByZXNvdXJjZVxyXG5cdFx0dmFyIGNoYW5nZV9ib29raW5nX3Jlc291cmNlX3RwbCA9IHdwLnRlbXBsYXRlKCAnd3BiY19hanhfY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2UnICk7XHJcblxyXG5cdFx0alF1ZXJ5KCAnI3dwYmNfaGlkZGVuX3RlbXBsYXRlX19jaGFuZ2VfYm9va2luZ19yZXNvdXJjZScgKS5odG1sKFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdGNoYW5nZV9ib29raW5nX3Jlc291cmNlX3RwbCgge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9zZWFyY2hfcGFyYW1zJyAgICA6IGpzb25fc2VhcmNoX3BhcmFtcyxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdhanhfYm9va2luZ19yZXNvdXJjZXMnOiBqc29uX2Jvb2tpbmdfcmVzb3VyY2VzXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fSApXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG5cclxuXHRcdC8vIER1cGxpY2F0ZSBib29raW5nIHJlc291cmNlXHJcblx0XHR2YXIgZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfdHBsID0gd3AudGVtcGxhdGUoICd3cGJjX2FqeF9kdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZScgKTtcclxuXHJcblx0XHRqUXVlcnkoICcjd3BiY19oaWRkZW5fdGVtcGxhdGVfX2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlJyApLmh0bWwoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfdHBsKCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYWp4X3NlYXJjaF9wYXJhbXMnICAgIDoganNvbl9zZWFyY2hfcGFyYW1zLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2FqeF9ib29raW5nX3Jlc291cmNlcyc6IGpzb25fYm9va2luZ19yZXNvdXJjZXNcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHR9IClcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCk7XHJcblx0fVxyXG5cclxuXHJcbi8qKlxyXG4gKiBTaG93IGp1c3QgbWVzc2FnZSBpbnN0ZWFkIG9mIGxpc3RpbmcgYW5kIGhpZGUgcGFnaW5hdGlvblxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19zaG93X21lc3NhZ2UoIG1lc3NhZ2UgKXtcclxuXHJcblx0d3BiY19hanhfYm9va2luZ19fYWN0dWFsX2xpc3RpbmdfX2hpZGUoKTtcclxuXHJcblx0alF1ZXJ5KCB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X290aGVyX3BhcmFtKCAnbGlzdGluZ19jb250YWluZXInICkgKS5odG1sKFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPGRpdiBjbGFzcz1cIndwYmMtc2V0dGluZ3Mtbm90aWNlIG5vdGljZS13YXJuaW5nXCIgc3R5bGU9XCJ0ZXh0LWFsaWduOmxlZnRcIj4nICtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRtZXNzYWdlICtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0JzwvZGl2PidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqICAgSCBvIG8gayBzICAtICBpdHMgQWN0aW9uL1RpbWVzIHdoZW4gbmVlZCB0byByZS1SZW5kZXIgVmlld3MgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogU2VuZCBBamF4IFNlYXJjaCBSZXF1ZXN0IGFmdGVyIFVwZGF0aW5nIHNlYXJjaCByZXF1ZXN0IHBhcmFtZXRlcnNcclxuICpcclxuICogQHBhcmFtIHBhcmFtc19hcnJcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF93aXRoX3BhcmFtcyAoIHBhcmFtc19hcnIgKXtcclxuXHJcblx0Ly8gRGVmaW5lIGRpZmZlcmVudCBTZWFyY2ggIHBhcmFtZXRlcnMgZm9yIHJlcXVlc3RcclxuXHRfLmVhY2goIHBhcmFtc19hcnIsIGZ1bmN0aW9uICggcF92YWwsIHBfa2V5LCBwX2RhdGEgKSB7XHJcblx0XHQvL2NvbnNvbGUubG9nKCAnUmVxdWVzdCBmb3I6ICcsIHBfa2V5LCBwX3ZhbCApO1xyXG5cdFx0d3BiY19hanhfYm9va2luZ19saXN0aW5nLnNlYXJjaF9zZXRfcGFyYW0oIHBfa2V5LCBwX3ZhbCApO1xyXG5cdH0pO1xyXG5cclxuXHQvLyBTZW5kIEFqYXggUmVxdWVzdFxyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfYWpheF9zZWFyY2hfcmVxdWVzdCgpO1xyXG59XHJcblxyXG4vKipcclxuICogU2VhcmNoIHJlcXVlc3QgZm9yIFwiUGFnZSBOdW1iZXJcIlxyXG4gKiBAcGFyYW0gcGFnZV9udW1iZXJcdGludFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19wYWdpbmF0aW9uX2NsaWNrKCBwYWdlX251bWJlciApe1xyXG5cclxuXHR3cGJjX2FqeF9ib29raW5nX3NlbmRfc2VhcmNoX3JlcXVlc3Rfd2l0aF9wYXJhbXMoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHQncGFnZV9udW0nOiBwYWdlX251bWJlclxyXG5cdFx0XHRcdFx0XHRcdFx0XHR9ICk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBLZXl3b3JkIFNlYXJjaGluZyAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBTZWFyY2ggcmVxdWVzdCBmb3IgXCJLZXl3b3JkXCIsIGFsc28gc2V0IGN1cnJlbnQgcGFnZSB0byAgMVxyXG4gKlxyXG4gKiBAcGFyYW0gZWxlbWVudF9pZFx0LVx0SFRNTCBJRCAgb2YgZWxlbWVudCwgIHdoZXJlIHdhcyBlbnRlcmVkIGtleXdvcmRcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF9mb3Jfa2V5d29yZCggZWxlbWVudF9pZCApIHtcclxuXHJcblx0Ly8gV2UgbmVlZCB0byBSZXNldCBwYWdlX251bSB0byAxIHdpdGggZWFjaCBuZXcgc2VhcmNoLCBiZWNhdXNlIHdlIGNhbiBiZSBhdCBwYWdlICM0LCAgYnV0IGFmdGVyICBuZXcgc2VhcmNoICB3ZSBjYW4gIGhhdmUgdG90YWxseSAgb25seSAgMSBwYWdlXHJcblx0d3BiY19hanhfYm9va2luZ19zZW5kX3NlYXJjaF9yZXF1ZXN0X3dpdGhfcGFyYW1zKCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQna2V5d29yZCcgIDogalF1ZXJ5KCBlbGVtZW50X2lkICkudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQncGFnZV9udW0nOiAxXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fSApO1xyXG59XHJcblxyXG5cdC8qKlxyXG5cdCAqIFNlbmQgc2VhcmNoIHJlcXVlc3QgYWZ0ZXIgZmV3IHNlY29uZHMgKHVzdWFsbHkgYWZ0ZXIgMSw1IHNlYylcclxuXHQgKiBDbG9zdXJlIGZ1bmN0aW9uLiBJdHMgdXNlZnVsLCAgZm9yIGRvICBub3Qgc2VuZCB0b28gbWFueSBBamF4IHJlcXVlc3RzLCB3aGVuIHNvbWVvbmUgbWFrZSBmYXN0IHR5cGluZy5cclxuXHQgKi9cclxuXHR2YXIgd3BiY19hanhfYm9va2luZ19zZWFyY2hpbmdfYWZ0ZXJfZmV3X3NlY29uZHMgPSBmdW5jdGlvbiAoKXtcclxuXHJcblx0XHR2YXIgY2xvc2VkX3RpbWVyID0gMDtcclxuXHJcblx0XHRyZXR1cm4gZnVuY3Rpb24gKCBlbGVtZW50X2lkLCB0aW1lcl9kZWxheSApe1xyXG5cclxuXHRcdFx0Ly8gR2V0IGRlZmF1bHQgdmFsdWUgb2YgXCJ0aW1lcl9kZWxheVwiLCAgaWYgcGFyYW1ldGVyIHdhcyBub3QgcGFzc2VkIGludG8gdGhlIGZ1bmN0aW9uLlxyXG5cdFx0XHR0aW1lcl9kZWxheSA9IHR5cGVvZiB0aW1lcl9kZWxheSAhPT0gJ3VuZGVmaW5lZCcgPyB0aW1lcl9kZWxheSA6IDE1MDA7XHJcblxyXG5cdFx0XHRjbGVhclRpbWVvdXQoIGNsb3NlZF90aW1lciApO1x0XHQvLyBDbGVhciBwcmV2aW91cyB0aW1lclxyXG5cclxuXHRcdFx0Ly8gU3RhcnQgbmV3IFRpbWVyXHJcblx0XHRcdGNsb3NlZF90aW1lciA9IHNldFRpbWVvdXQoIHdwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF9mb3Jfa2V5d29yZC5iaW5kKCAgbnVsbCwgZWxlbWVudF9pZCApLCB0aW1lcl9kZWxheSApO1xyXG5cdFx0fVxyXG5cdH0oKTtcclxuXHJcblxyXG4vKipcclxuICogICBEZWZpbmUgRHluYW1pYyBIb29rcyAgKGxpa2UgcGFnaW5hdGlvbiBjbGljaywgd2hpY2ggcmVuZXcgZWFjaCB0aW1lIHdpdGggbmV3IGxpc3Rpbmcgc2hvd2luZykgIC0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBEZWZpbmUgSFRNTCB1aSBIb29rczogb24gS2V5VXAgfCBDaGFuZ2UgfCAtPiBTb3J0IE9yZGVyICYgTnVtYmVyIEl0ZW1zIC8gUGFnZVxyXG4gKiBXZSBhcmUgaGNuYWdlZCBpdCBlYWNoICB0aW1lLCB3aGVuIHNob3dpbmcgbmV3IGxpc3RpbmcsIGJlY2F1c2UgRE9NIGVsZW1lbnRzIGNobmFnZWRcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfZGVmaW5lX3VpX2hvb2tzKCl7XHJcblxyXG5cdGlmICggJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mKCB3cGJjX2RlZmluZV90aXBweV90b29sdGlwcyApICkge1xyXG5cdFx0d3BiY19kZWZpbmVfdGlwcHlfdG9vbHRpcHMoICcud3BiY19saXN0aW5nX2NvbnRhaW5lciAnICk7XHJcblx0fVxyXG5cclxuXHR3cGJjX2FqeF9ib29raW5nX191aV9kZWZpbmVfX2xvY2FsZSgpO1xyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2RlZmluZV9fcmVtYXJrKCk7XHJcblxyXG5cdC8vIEl0ZW1zIFBlciBQYWdlXHJcblx0alF1ZXJ5KCAnLndwYmNfaXRlbXNfcGVyX3BhZ2UnICkub24oICdjaGFuZ2UnLCBmdW5jdGlvbiggZXZlbnQgKXtcclxuXHJcblx0XHR3cGJjX2FqeF9ib29raW5nX3NlbmRfc2VhcmNoX3JlcXVlc3Rfd2l0aF9wYXJhbXMoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdwYWdlX2l0ZW1zX2NvdW50JyAgOiBqUXVlcnkoIHRoaXMgKS52YWwoKSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdwYWdlX251bSc6IDFcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9ICk7XHJcblx0fSApO1xyXG5cclxuXHQvLyBTb3J0aW5nXHJcblx0alF1ZXJ5KCAnLndwYmNfaXRlbXNfc29ydF90eXBlJyApLm9uKCAnY2hhbmdlJywgZnVuY3Rpb24oIGV2ZW50ICl7XHJcblxyXG5cdFx0d3BiY19hanhfYm9va2luZ19zZW5kX3NlYXJjaF9yZXF1ZXN0X3dpdGhfcGFyYW1zKCB7J3NvcnRfdHlwZSc6IGpRdWVyeSggdGhpcyApLnZhbCgpfSApO1xyXG5cdH0gKTtcclxufVxyXG5cclxuXHJcbi8qKlxyXG4gKiAgIFNob3cgLyBIaWRlIExpc3RpbmcgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuLyoqXHJcbiAqICBTaG93IExpc3RpbmcgVGFibGUgXHQtIFx0U2VuZGluZyBBamF4IFJlcXVlc3RcdC1cdHdpdGggcGFyYW1ldGVycyB0aGF0ICB3ZSBlYXJseSAgZGVmaW5lZCBpbiBcIndwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZ1wiIE9iai5cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX2FjdHVhbF9saXN0aW5nX19zaG93KCl7XHJcblxyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfYWpheF9zZWFyY2hfcmVxdWVzdCgpO1x0XHRcdC8vIFNlbmQgQWpheCBSZXF1ZXN0XHQtXHR3aXRoIHBhcmFtZXRlcnMgdGhhdCAgd2UgZWFybHkgIGRlZmluZWQgaW4gXCJ3cGJjX2FqeF9ib29raW5nX2xpc3RpbmdcIiBPYmouXHJcbn1cclxuXHJcbi8qKlxyXG4gKiBIaWRlIExpc3RpbmcgVGFibGUgKCBhbmQgUGFnaW5hdGlvbiApXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX19hY3R1YWxfbGlzdGluZ19faGlkZSgpe1xyXG5cdGpRdWVyeSggJy53cGJjX2FqeF91bmRlcl90b29sYmFyX3JvdycgKS5oaWRlKCk7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly9GaXhJbjogOS42LjEuNVxyXG5cdGpRdWVyeSggd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9vdGhlcl9wYXJhbSggJ2xpc3RpbmdfY29udGFpbmVyJyApICAgICkuaHRtbCggJycgKTtcclxuXHRqUXVlcnkoIHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfb3RoZXJfcGFyYW0oICdwYWdpbmF0aW9uX2NvbnRhaW5lcicgKSApLmh0bWwoICcnICk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBTdXBwb3J0IGZ1bmN0aW9ucyBmb3IgQ29udGVudCBUZW1wbGF0ZSBkYXRhICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBIaWdobGlnaHQgc3RyaW5ncyxcclxuICogYnkgaW5zZXJ0aW5nIDxzcGFuIGNsYXNzPVwiZmllbGR2YWx1ZSBuYW1lIGZpZWxkc2VhcmNodmFsdWVcIj4uLi48L3NwYW4+IGh0bWwgIGVsZW1lbnRzIGludG8gdGhlIHN0cmluZy5cclxuICogQHBhcmFtIHtzdHJpbmd9IGJvb2tpbmdfZGV0YWlscyBcdC0gU291cmNlIHN0cmluZ1xyXG4gKiBAcGFyYW0ge3N0cmluZ30gYm9va2luZ19rZXl3b3JkXHQtIEtleXdvcmQgdG8gaGlnaGxpZ2h0XHJcbiAqIEByZXR1cm5zIHtzdHJpbmd9XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2dldF9oaWdobGlnaHRlZF9zZWFyY2hfa2V5d29yZCggYm9va2luZ19kZXRhaWxzLCBib29raW5nX2tleXdvcmQgKXtcclxuXHJcblx0Ym9va2luZ19rZXl3b3JkID0gYm9va2luZ19rZXl3b3JkLnRyaW0oKS50b0xvd2VyQ2FzZSgpO1xyXG5cdGlmICggMCA9PSBib29raW5nX2tleXdvcmQubGVuZ3RoICl7XHJcblx0XHRyZXR1cm4gYm9va2luZ19kZXRhaWxzO1xyXG5cdH1cclxuXHJcblx0Ly8gSGlnaGxpZ2h0IHN1YnN0cmluZyB3aXRoaW5nIEhUTUwgdGFncyBpbiBcIkNvbnRlbnQgb2YgYm9va2luZyBmaWVsZHMgZGF0YVwiIC0tIGUuZy4gc3RhcnRpbmcgZnJvbSAgPiAgYW5kIGVuZGluZyB3aXRoIDxcclxuXHRsZXQga2V5d29yZFJlZ2V4ID0gbmV3IFJlZ0V4cCggYGZpZWxkdmFsdWVbXjw+XSo+KFtePF0qJHtib29raW5nX2tleXdvcmR9W148XSopYCwgJ2dpbScgKTtcclxuXHJcblx0Ly9sZXQgbWF0Y2hlcyA9IFsuLi5ib29raW5nX2RldGFpbHMudG9Mb3dlckNhc2UoKS5tYXRjaEFsbCgga2V5d29yZFJlZ2V4ICldO1xyXG5cdGxldCBtYXRjaGVzID0gYm9va2luZ19kZXRhaWxzLnRvTG93ZXJDYXNlKCkubWF0Y2hBbGwoIGtleXdvcmRSZWdleCApO1xyXG5cdFx0bWF0Y2hlcyA9IEFycmF5LmZyb20oIG1hdGNoZXMgKTtcclxuXHJcblx0bGV0IHN0cmluZ3NfYXJyID0gW107XHJcblx0bGV0IHBvc19wcmV2aW91cyA9IDA7XHJcblx0bGV0IHNlYXJjaF9wb3Nfc3RhcnQ7XHJcblx0bGV0IHNlYXJjaF9wb3NfZW5kO1xyXG5cclxuXHRmb3IgKCBjb25zdCBtYXRjaCBvZiBtYXRjaGVzICl7XHJcblxyXG5cdFx0c2VhcmNoX3Bvc19zdGFydCA9IG1hdGNoLmluZGV4ICsgbWF0Y2hbIDAgXS50b0xvd2VyQ2FzZSgpLmluZGV4T2YoICc+JywgMCApICsgMSA7XHJcblxyXG5cdFx0c3RyaW5nc19hcnIucHVzaCggYm9va2luZ19kZXRhaWxzLnN1YnN0ciggcG9zX3ByZXZpb3VzLCAoc2VhcmNoX3Bvc19zdGFydCAtIHBvc19wcmV2aW91cykgKSApO1xyXG5cclxuXHRcdHNlYXJjaF9wb3NfZW5kID0gYm9va2luZ19kZXRhaWxzLnRvTG93ZXJDYXNlKCkuaW5kZXhPZiggJzwnLCBzZWFyY2hfcG9zX3N0YXJ0ICk7XHJcblxyXG5cdFx0c3RyaW5nc19hcnIucHVzaCggJzxzcGFuIGNsYXNzPVwiZmllbGR2YWx1ZSBuYW1lIGZpZWxkc2VhcmNodmFsdWVcIj4nICsgYm9va2luZ19kZXRhaWxzLnN1YnN0ciggc2VhcmNoX3Bvc19zdGFydCwgKHNlYXJjaF9wb3NfZW5kIC0gc2VhcmNoX3Bvc19zdGFydCkgKSArICc8L3NwYW4+JyApO1xyXG5cclxuXHRcdHBvc19wcmV2aW91cyA9IHNlYXJjaF9wb3NfZW5kO1xyXG5cdH1cclxuXHJcblx0c3RyaW5nc19hcnIucHVzaCggYm9va2luZ19kZXRhaWxzLnN1YnN0ciggcG9zX3ByZXZpb3VzLCAoYm9va2luZ19kZXRhaWxzLmxlbmd0aCAtIHBvc19wcmV2aW91cykgKSApO1xyXG5cclxuXHRyZXR1cm4gc3RyaW5nc19hcnIuam9pbiggJycgKTtcclxufVxyXG5cclxuLyoqXHJcbiAqIENvbnZlcnQgc3BlY2lhbCBIVE1MIGNoYXJhY3RlcnMgICBmcm9tOlx0ICZhbXA7IFx0LT4gXHQmXHJcbiAqXHJcbiAqIEBwYXJhbSB0ZXh0XHJcbiAqIEByZXR1cm5zIHsqfVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19kZWNvZGVfSFRNTF9lbnRpdGllcyggdGV4dCApe1xyXG5cdHZhciB0ZXh0QXJlYSA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoICd0ZXh0YXJlYScgKTtcclxuXHR0ZXh0QXJlYS5pbm5lckhUTUwgPSB0ZXh0O1xyXG5cdHJldHVybiB0ZXh0QXJlYS52YWx1ZTtcclxufVxyXG5cclxuLyoqXHJcbiAqIENvbnZlcnQgVE8gc3BlY2lhbCBIVE1MIGNoYXJhY3RlcnMgICBmcm9tOlx0ICYgXHQtPiBcdCZhbXA7XHJcbiAqXHJcbiAqIEBwYXJhbSB0ZXh0XHJcbiAqIEByZXR1cm5zIHsqfVxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19lbmNvZGVfSFRNTF9lbnRpdGllcyh0ZXh0KSB7XHJcbiAgdmFyIHRleHRBcmVhID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgndGV4dGFyZWEnKTtcclxuICB0ZXh0QXJlYS5pbm5lclRleHQgPSB0ZXh0O1xyXG4gIHJldHVybiB0ZXh0QXJlYS5pbm5lckhUTUw7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBTdXBwb3J0IEZ1bmN0aW9ucyAtIFNwaW4gSWNvbiBpbiBCdXR0b25zICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBTcGluIGJ1dHRvbiBpbiBGaWx0ZXIgdG9vbGJhciAgLSAgU3RhcnRcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYm9va2luZ19saXN0aW5nX3JlbG9hZF9idXR0b25fX3NwaW5fc3RhcnQoKXtcclxuXHRqUXVlcnkoICcjd3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbiAubWVudV9pY29uLndwYmNfc3BpbicpLnJlbW92ZUNsYXNzKCAnd3BiY19hbmltYXRpb25fcGF1c2UnICk7XHJcbn1cclxuXHJcbi8qKlxyXG4gKiBTcGluIGJ1dHRvbiBpbiBGaWx0ZXIgdG9vbGJhciAgLSAgUGF1c2VcclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYm9va2luZ19saXN0aW5nX3JlbG9hZF9idXR0b25fX3NwaW5fcGF1c2UoKXtcclxuXHRqUXVlcnkoICcjd3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbiAubWVudV9pY29uLndwYmNfc3BpbicgKS5hZGRDbGFzcyggJ3dwYmNfYW5pbWF0aW9uX3BhdXNlJyApO1xyXG59XHJcblxyXG4vKipcclxuICogU3BpbiBidXR0b24gaW4gRmlsdGVyIHRvb2xiYXIgIC0gIGlzIFNwaW5uaW5nID9cclxuICpcclxuICogQHJldHVybnMge2Jvb2xlYW59XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2Jvb2tpbmdfbGlzdGluZ19yZWxvYWRfYnV0dG9uX19pc19zcGluKCl7XHJcbiAgICBpZiAoIGpRdWVyeSggJyN3cGJjX2Jvb2tpbmdfbGlzdGluZ19yZWxvYWRfYnV0dG9uIC5tZW51X2ljb24ud3BiY19zcGluJyApLmhhc0NsYXNzKCAnd3BiY19hbmltYXRpb25fcGF1c2UnICkgKXtcclxuXHRcdHJldHVybiB0cnVlO1xyXG5cdH0gZWxzZSB7XHJcblx0XHRyZXR1cm4gZmFsc2U7XHJcblx0fVxyXG59Il0sImZpbGUiOiJpbmNsdWRlcy9wYWdlLWJvb2tpbmdzL19vdXQvYm9va2luZ3NfX2xpc3RpbmcuanMifQ==
