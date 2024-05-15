"use strict";
/**
 *   Ajax   ----------------------------------------------------------------------------------------------------- */
//var is_this_action = false;

/**
 * Send Ajax action request,  like approving or cancellation
 *
 * @param action_param
 */

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }

function wpbc_ajx_booking_ajax_action_request() {
  var action_param = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  console.groupCollapsed('WPBC_AJX_BOOKING_ACTIONS');
  console.log(' == Ajax Actions :: Params == ', action_param); //is_this_action = true;

  wpbc_booking_listing_reload_button__spin_start(); // Get redefined Locale,  if action on single booking !

  if (undefined != action_param['booking_id'] && !Array.isArray(action_param['booking_id'])) {
    // Not array
    action_param['locale'] = wpbc_get_selected_locale(action_param['booking_id'], wpbc_ajx_booking_listing.get_secure_param('locale'));
  }

  var action_post_params = {
    action: 'WPBC_AJX_BOOKING_ACTIONS',
    nonce: wpbc_ajx_booking_listing.get_secure_param('nonce'),
    wpbc_ajx_user_id: undefined == action_param['user_id'] ? wpbc_ajx_booking_listing.get_secure_param('user_id') : action_param['user_id'],
    wpbc_ajx_locale: undefined == action_param['locale'] ? wpbc_ajx_booking_listing.get_secure_param('locale') : action_param['locale'],
    action_params: action_param
  }; // It's required for CSV export - getting the same list  of bookings

  if (typeof action_param.search_params !== 'undefined') {
    action_post_params['search_params'] = action_param.search_params;
    delete action_post_params.action_params.search_params;
  } // Start Ajax


  jQuery.post(wpbc_global1.wpbc_ajaxurl, action_post_params,
  /**
   * S u c c e s s
   *
   * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
   * @param textStatus		-	'success'
   * @param jqXHR				-	Object
   */
  function (response_data, textStatus, jqXHR) {
    console.log(' == Ajax Actions :: Response WPBC_AJX_BOOKING_ACTIONS == ', response_data);
    console.groupEnd(); // Probably Error

    if (_typeof(response_data) !== 'object' || response_data === null) {
      jQuery('.wpbc_ajx_under_toolbar_row').hide(); //FixIn: 9.6.1.5

      jQuery(wpbc_ajx_booking_listing.get_other_param('listing_container')).html('<div class="wpbc-settings-notice notice-warning" style="text-align:left">' + response_data + '</div>');
      return;
    }

    wpbc_booking_listing_reload_button__spin_pause();
    wpbc_admin_show_message(response_data['ajx_after_action_message'].replace(/\n/g, "<br />"), '1' == response_data['ajx_after_action_result'] ? 'success' : 'error', 'undefined' === typeof response_data['ajx_after_action_result_all_params_arr']['after_action_result_delay'] ? 10000 : response_data['ajx_after_action_result_all_params_arr']['after_action_result_delay']); // Success response

    if ('1' == response_data['ajx_after_action_result']) {
      var is_reload_ajax_listing = true; // After Google Calendar import show imported bookings and reload the page for toolbar parameters update

      if (false !== response_data['ajx_after_action_result_all_params_arr']['new_listing_params']) {
        wpbc_ajx_booking_send_search_request_with_params(response_data['ajx_after_action_result_all_params_arr']['new_listing_params']);
        var closed_timer = setTimeout(function () {
          if (wpbc_booking_listing_reload_button__is_spin()) {
            if (undefined != response_data['ajx_after_action_result_all_params_arr']['new_listing_params']['reload_url_params']) {
              document.location.href = response_data['ajx_after_action_result_all_params_arr']['new_listing_params']['reload_url_params'];
            } else {
              document.location.reload();
            }
          }
        }, 2000);
        is_reload_ajax_listing = false;
      } // Start download exported CSV file


      if (undefined != response_data['ajx_after_action_result_all_params_arr']['export_csv_url']) {
        wpbc_ajx_booking__export_csv_url__download(response_data['ajx_after_action_result_all_params_arr']['export_csv_url']);
        is_reload_ajax_listing = false;
      }

      if (is_reload_ajax_listing) {
        wpbc_ajx_booking__actual_listing__show(); //	Sending Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
      }
    } // Remove spin icon from  button and Enable this button.


    wpbc_button__remove_spin(response_data['ajx_cleaned_params']['ui_clicked_element_id']); // Hide modals

    wpbc_popup_modals__hide();
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
 * Hide all open modal popups windows
 */


function wpbc_popup_modals__hide() {
  // Hide modals
  if ('function' === typeof jQuery('.wpbc_popup_modal').wpbc_my_modal) {
    jQuery('.wpbc_popup_modal').wpbc_my_modal('hide');
  }
}
/**
 *   Dates  Short <-> Wide    ----------------------------------------------------------------------------------- */


function wpbc_ajx_click_on_dates_short() {
  jQuery('#booking_dates_small,.booking_dates_full').hide();
  jQuery('#booking_dates_full,.booking_dates_small').show();
  wpbc_ajx_booking_send_search_request_with_params({
    'ui_usr__dates_short_wide': 'short'
  });
}

function wpbc_ajx_click_on_dates_wide() {
  jQuery('#booking_dates_full,.booking_dates_small').hide();
  jQuery('#booking_dates_small,.booking_dates_full').show();
  wpbc_ajx_booking_send_search_request_with_params({
    'ui_usr__dates_short_wide': 'wide'
  });
}

function wpbc_ajx_click_on_dates_toggle(this_date) {
  jQuery(this_date).parents('.wpbc_col_dates').find('.booking_dates_small').toggle();
  jQuery(this_date).parents('.wpbc_col_dates').find('.booking_dates_full').toggle();
  /*
  var visible_section = jQuery( this_date ).parents( '.booking_dates_expand_section' );
  visible_section.hide();
  if ( visible_section.hasClass( 'booking_dates_full' ) ){
  	visible_section.parents( '.wpbc_col_dates' ).find( '.booking_dates_small' ).show();
  } else {
  	visible_section.parents( '.wpbc_col_dates' ).find( '.booking_dates_full' ).show();
  }*/

  console.log('wpbc_ajx_click_on_dates_toggle', this_date);
}
/**
 *   Locale   --------------------------------------------------------------------------------------------------- */

/**
 * 	Select options in select boxes based on attribute "value_of_selected_option" and RED color and hint for LOCALE button   --  It's called from 	wpbc_ajx_booking_define_ui_hooks()  	each  time after Listing loading.
 */


function wpbc_ajx_booking__ui_define__locale() {
  jQuery('.wpbc_listing_container select').each(function (index) {
    var selection = jQuery(this).attr("value_of_selected_option"); // Define selected select boxes

    if (undefined !== selection) {
      jQuery(this).find('option[value="' + selection + '"]').prop('selected', true);

      if ('' != selection && jQuery(this).hasClass('set_booking_locale_selectbox')) {
        // Locale
        var booking_locale_button = jQuery(this).parents('.ui_element_locale').find('.set_booking_locale_button'); //booking_locale_button.css( 'color', '#db4800' );		// Set button  red

        booking_locale_button.addClass('wpbc_ui_red'); // Set button  red

        if ('function' === typeof wpbc_tippy) {
          booking_locale_button.get(0)._tippy.setContent(selection);
        }
      }
    }
  });
}
/**
 *   Remark   --------------------------------------------------------------------------------------------------- */

/**
 * Define content of remark "booking note" button and textarea.  -- It's called from 	wpbc_ajx_booking_define_ui_hooks()  	each  time after Listing loading.
 */


function wpbc_ajx_booking__ui_define__remark() {
  jQuery('.wpbc_listing_container .ui_remark_section textarea').each(function (index) {
    var text_val = jQuery(this).val();

    if (undefined !== text_val && '' != text_val) {
      var remark_button = jQuery(this).parents('.ui_group').find('.set_booking_note_button');

      if (remark_button.length > 0) {
        remark_button.addClass('wpbc_ui_red'); // Set button  red

        if ('function' === typeof wpbc_tippy) {
          //remark_button.get( 0 )._tippy.allowHTML = true;
          //remark_button.get( 0 )._tippy.setContent( text_val.replace(/[\n\r]/g, '<br>') );
          remark_button.get(0)._tippy.setProps({
            allowHTML: true,
            content: text_val.replace(/[\n\r]/g, '<br>')
          });
        }
      }
    }
  });
}
/**
 * Actions ,when we click on "Remark" button.
 *
 * @param jq_button  -	this jQuery button  object
 */


function wpbc_ajx_booking__ui_click__remark(jq_button) {
  jq_button.parents('.ui_group').find('.ui_remark_section').toggle();
}
/**
 *   Change booking resource   ---------------------------------------------------------------------------------- */


function wpbc_ajx_booking__ui_click_show__change_resource(booking_id, resource_id) {
  // Define ID of booking to hidden input
  jQuery('#change_booking_resource__booking_id').val(booking_id); // Select booking resource  that belong to  booking

  jQuery('#change_booking_resource__resource_select').val(resource_id).trigger('change');
  var cbr; // Get Resource section

  cbr = jQuery("#change_booking_resource__section").detach(); // Append it to booking ROW

  cbr.appendTo(jQuery("#ui__change_booking_resource__section_in_booking_" + booking_id));
  cbr = null; // Hide sections of "Change booking resource" in all other bookings ROWs
  //jQuery( ".ui__change_booking_resource__section_in_booking" ).hide();

  if (!jQuery("#ui__change_booking_resource__section_in_booking_" + booking_id).is(':visible')) {
    jQuery(".ui__under_actions_row__section_in_booking").hide();
  } // Show only "change booking resource" section  for current booking


  jQuery("#ui__change_booking_resource__section_in_booking_" + booking_id).toggle();
}

function wpbc_ajx_booking__ui_click_save__change_resource(this_el, booking_action, el_id) {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': booking_action,
    'booking_id': jQuery('#change_booking_resource__booking_id').val(),
    'selected_resource_id': jQuery('#change_booking_resource__resource_select').val(),
    'ui_clicked_element_id': el_id
  });
  wpbc_button_enable_loading_icon(this_el); // wpbc_ajx_booking__ui_click_close__change_resource();
}

function wpbc_ajx_booking__ui_click_close__change_resource() {
  var cbrce; // Get Resource section

  cbrce = jQuery("#change_booking_resource__section").detach(); // Append it to hidden HTML template section  at  the bottom  of the page

  cbrce.appendTo(jQuery("#wpbc_hidden_template__change_booking_resource"));
  cbrce = null; // Hide all change booking resources sections

  jQuery(".ui__change_booking_resource__section_in_booking").hide();
}
/**
 *   Duplicate booking in other resource   ---------------------------------------------------------------------- */


function wpbc_ajx_booking__ui_click_show__duplicate_booking(booking_id, resource_id) {
  // Define ID of booking to hidden input
  jQuery('#duplicate_booking_to_other_resource__booking_id').val(booking_id); // Select booking resource  that belong to  booking

  jQuery('#duplicate_booking_to_other_resource__resource_select').val(resource_id).trigger('change');
  var cbr; // Get Resource section

  cbr = jQuery("#duplicate_booking_to_other_resource__section").detach(); // Append it to booking ROW

  cbr.appendTo(jQuery("#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id));
  cbr = null; // Hide sections of "Duplicate booking" in all other bookings ROWs

  if (!jQuery("#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id).is(':visible')) {
    jQuery(".ui__under_actions_row__section_in_booking").hide();
  } // Show only "Duplicate booking" section  for current booking ROW


  jQuery("#ui__duplicate_booking_to_other_resource__section_in_booking_" + booking_id).toggle();
}

function wpbc_ajx_booking__ui_click_save__duplicate_booking(this_el, booking_action, el_id) {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': booking_action,
    'booking_id': jQuery('#duplicate_booking_to_other_resource__booking_id').val(),
    'selected_resource_id': jQuery('#duplicate_booking_to_other_resource__resource_select').val(),
    'ui_clicked_element_id': el_id
  });
  wpbc_button_enable_loading_icon(this_el); // wpbc_ajx_booking__ui_click_close__change_resource();
}

function wpbc_ajx_booking__ui_click_close__duplicate_booking() {
  var cbrce; // Get Resource section

  cbrce = jQuery("#duplicate_booking_to_other_resource__section").detach(); // Append it to hidden HTML template section  at  the bottom  of the page

  cbrce.appendTo(jQuery("#wpbc_hidden_template__duplicate_booking_to_other_resource"));
  cbrce = null; // Hide all change booking resources sections

  jQuery(".ui__duplicate_booking_to_other_resource__section_in_booking").hide();
}
/**
 *   Change payment status   ------------------------------------------------------------------------------------ */


function wpbc_ajx_booking__ui_click_show__set_payment_status(booking_id) {
  var jSelect = jQuery('#ui__set_payment_status__section_in_booking_' + booking_id).find('select');
  var selected_pay_status = jSelect.attr("ajx-selected-value"); // Is it float - then  it's unknown

  if (!isNaN(parseFloat(selected_pay_status))) {
    jSelect.find('option[value="1"]').prop('selected', true); // Unknown  value is '1' in select box
  } else {
    jSelect.find('option[value="' + selected_pay_status + '"]').prop('selected', true); // Otherwise known payment status
  } // Hide sections of "Change booking resource" in all other bookings ROWs


  if (!jQuery("#ui__set_payment_status__section_in_booking_" + booking_id).is(':visible')) {
    jQuery(".ui__under_actions_row__section_in_booking").hide();
  } // Show only "change booking resource" section  for current booking


  jQuery("#ui__set_payment_status__section_in_booking_" + booking_id).toggle();
}

function wpbc_ajx_booking__ui_click_save__set_payment_status(booking_id, this_el, booking_action, el_id) {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': booking_action,
    'booking_id': booking_id,
    'selected_payment_status': jQuery('#ui_btn_set_payment_status' + booking_id).val(),
    'ui_clicked_element_id': el_id + '_save'
  });
  wpbc_button_enable_loading_icon(this_el);
  jQuery('#' + el_id + '_cancel').hide(); //wpbc_button_enable_loading_icon( jQuery( '#' + el_id + '_cancel').get(0) );
}

function wpbc_ajx_booking__ui_click_close__set_payment_status() {
  // Hide all change  payment status for booking
  jQuery(".ui__set_payment_status__section_in_booking").hide();
}
/**
 *   Change booking cost   -------------------------------------------------------------------------------------- */


function wpbc_ajx_booking__ui_click_save__set_booking_cost(booking_id, this_el, booking_action, el_id) {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': booking_action,
    'booking_id': booking_id,
    'booking_cost': jQuery('#ui_btn_set_booking_cost' + booking_id + '_cost').val(),
    'ui_clicked_element_id': el_id + '_save'
  });
  wpbc_button_enable_loading_icon(this_el);
  jQuery('#' + el_id + '_cancel').hide(); //wpbc_button_enable_loading_icon( jQuery( '#' + el_id + '_cancel').get(0) );
}

function wpbc_ajx_booking__ui_click_close__set_booking_cost() {
  // Hide all change  payment status for booking
  jQuery(".ui__set_booking_cost__section_in_booking").hide();
}
/**
 *   Send Payment request   -------------------------------------------------------------------------------------- */


function wpbc_ajx_booking__ui_click__send_payment_request() {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': 'send_payment_request',
    'booking_id': jQuery('#wpbc_modal__payment_request__booking_id').val(),
    'reason_of_action': jQuery('#wpbc_modal__payment_request__reason_of_action').val(),
    'ui_clicked_element_id': 'wpbc_modal__payment_request__button_send'
  });
  wpbc_button_enable_loading_icon(jQuery('#wpbc_modal__payment_request__button_send').get(0));
}
/**
 *   Import Google Calendar  ------------------------------------------------------------------------------------ */


function wpbc_ajx_booking__ui_click__import_google_calendar() {
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': 'import_google_calendar',
    'ui_clicked_element_id': 'wpbc_modal__import_google_calendar__button_send',
    'booking_gcal_events_from': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_from option:selected').val(),
    'booking_gcal_events_from_offset': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_from_offset').val(),
    'booking_gcal_events_from_offset_type': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_from_offset_type option:selected').val(),
    'booking_gcal_events_until': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_until option:selected').val(),
    'booking_gcal_events_until_offset': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_until_offset').val(),
    'booking_gcal_events_until_offset_type': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_until_offset_type option:selected').val(),
    'booking_gcal_events_max': jQuery('#wpbc_modal__import_google_calendar__section #booking_gcal_events_max').val(),
    'booking_gcal_resource': jQuery('#wpbc_modal__import_google_calendar__section #wpbc_booking_resource option:selected').val()
  });
  wpbc_button_enable_loading_icon(jQuery('#wpbc_modal__import_google_calendar__section #wpbc_modal__import_google_calendar__button_send').get(0));
}
/**
 *   Export bookings to CSV  ------------------------------------------------------------------------------------ */


function wpbc_ajx_booking__ui_click__export_csv(params) {
  var selected_booking_id_arr = wpbc_get_selected_row_id();
  wpbc_ajx_booking_ajax_action_request({
    'booking_action': params['booking_action'],
    'ui_clicked_element_id': params['ui_clicked_element_id'],
    'export_type': params['export_type'],
    'csv_export_separator': params['csv_export_separator'],
    'csv_export_skip_fields': params['csv_export_skip_fields'],
    'booking_id': selected_booking_id_arr.join(','),
    'search_params': wpbc_ajx_booking_listing.search_get_all_params()
  });
  var this_el = jQuery('#' + params['ui_clicked_element_id']).get(0);
  wpbc_button_enable_loading_icon(this_el);
}
/**
 * Open URL in new tab - mainly  it's used for open CSV link  for downloaded exported bookings as CSV
 *
 * @param export_csv_url
 */


function wpbc_ajx_booking__export_csv_url__download(export_csv_url) {
  //var selected_booking_id_arr = wpbc_get_selected_row_id();
  document.location.href = export_csv_url; // + '&selected_id=' + selected_booking_id_arr.join(',');
  // It's open additional dialog for asking opening ulr in new tab
  // window.open( export_csv_url, '_blank').focus();
}
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImluY2x1ZGVzL3BhZ2UtYm9va2luZ3MvX3NyYy9ib29raW5nc19fYWN0aW9ucy5qcyJdLCJuYW1lcyI6WyJ3cGJjX2FqeF9ib29raW5nX2FqYXhfYWN0aW9uX3JlcXVlc3QiLCJhY3Rpb25fcGFyYW0iLCJjb25zb2xlIiwiZ3JvdXBDb2xsYXBzZWQiLCJsb2ciLCJ3cGJjX2Jvb2tpbmdfbGlzdGluZ19yZWxvYWRfYnV0dG9uX19zcGluX3N0YXJ0IiwidW5kZWZpbmVkIiwiQXJyYXkiLCJpc0FycmF5Iiwid3BiY19nZXRfc2VsZWN0ZWRfbG9jYWxlIiwid3BiY19hanhfYm9va2luZ19saXN0aW5nIiwiZ2V0X3NlY3VyZV9wYXJhbSIsImFjdGlvbl9wb3N0X3BhcmFtcyIsImFjdGlvbiIsIm5vbmNlIiwid3BiY19hanhfdXNlcl9pZCIsIndwYmNfYWp4X2xvY2FsZSIsImFjdGlvbl9wYXJhbXMiLCJzZWFyY2hfcGFyYW1zIiwialF1ZXJ5IiwicG9zdCIsIndwYmNfZ2xvYmFsMSIsIndwYmNfYWpheHVybCIsInJlc3BvbnNlX2RhdGEiLCJ0ZXh0U3RhdHVzIiwianFYSFIiLCJncm91cEVuZCIsImhpZGUiLCJnZXRfb3RoZXJfcGFyYW0iLCJodG1sIiwid3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSIsIndwYmNfYWRtaW5fc2hvd19tZXNzYWdlIiwicmVwbGFjZSIsImlzX3JlbG9hZF9hamF4X2xpc3RpbmciLCJ3cGJjX2FqeF9ib29raW5nX3NlbmRfc2VhcmNoX3JlcXVlc3Rfd2l0aF9wYXJhbXMiLCJjbG9zZWRfdGltZXIiLCJzZXRUaW1lb3V0Iiwid3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9faXNfc3BpbiIsImRvY3VtZW50IiwibG9jYXRpb24iLCJocmVmIiwicmVsb2FkIiwid3BiY19hanhfYm9va2luZ19fZXhwb3J0X2Nzdl91cmxfX2Rvd25sb2FkIiwid3BiY19hanhfYm9va2luZ19fYWN0dWFsX2xpc3RpbmdfX3Nob3ciLCJ3cGJjX2J1dHRvbl9fcmVtb3ZlX3NwaW4iLCJ3cGJjX3BvcHVwX21vZGFsc19faGlkZSIsImZhaWwiLCJlcnJvclRocm93biIsIndpbmRvdyIsImVycm9yX21lc3NhZ2UiLCJyZXNwb25zZVRleHQiLCJ3cGJjX2FqeF9ib29raW5nX3Nob3dfbWVzc2FnZSIsIndwYmNfbXlfbW9kYWwiLCJ3cGJjX2FqeF9jbGlja19vbl9kYXRlc19zaG9ydCIsInNob3ciLCJ3cGJjX2FqeF9jbGlja19vbl9kYXRlc193aWRlIiwid3BiY19hanhfY2xpY2tfb25fZGF0ZXNfdG9nZ2xlIiwidGhpc19kYXRlIiwicGFyZW50cyIsImZpbmQiLCJ0b2dnbGUiLCJ3cGJjX2FqeF9ib29raW5nX191aV9kZWZpbmVfX2xvY2FsZSIsImVhY2giLCJpbmRleCIsInNlbGVjdGlvbiIsImF0dHIiLCJwcm9wIiwiaGFzQ2xhc3MiLCJib29raW5nX2xvY2FsZV9idXR0b24iLCJhZGRDbGFzcyIsIndwYmNfdGlwcHkiLCJnZXQiLCJfdGlwcHkiLCJzZXRDb250ZW50Iiwid3BiY19hanhfYm9va2luZ19fdWlfZGVmaW5lX19yZW1hcmsiLCJ0ZXh0X3ZhbCIsInZhbCIsInJlbWFya19idXR0b24iLCJsZW5ndGgiLCJzZXRQcm9wcyIsImFsbG93SFRNTCIsImNvbnRlbnQiLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19fcmVtYXJrIiwianFfYnV0dG9uIiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfc2hvd19fY2hhbmdlX3Jlc291cmNlIiwiYm9va2luZ19pZCIsInJlc291cmNlX2lkIiwidHJpZ2dlciIsImNiciIsImRldGFjaCIsImFwcGVuZFRvIiwiaXMiLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zYXZlX19jaGFuZ2VfcmVzb3VyY2UiLCJ0aGlzX2VsIiwiYm9va2luZ19hY3Rpb24iLCJlbF9pZCIsIndwYmNfYnV0dG9uX2VuYWJsZV9sb2FkaW5nX2ljb24iLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19jbG9zZV9fY2hhbmdlX3Jlc291cmNlIiwiY2JyY2UiLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zaG93X19kdXBsaWNhdGVfYm9va2luZyIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3NhdmVfX2R1cGxpY2F0ZV9ib29raW5nIiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfY2xvc2VfX2R1cGxpY2F0ZV9ib29raW5nIiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfc2hvd19fc2V0X3BheW1lbnRfc3RhdHVzIiwialNlbGVjdCIsInNlbGVjdGVkX3BheV9zdGF0dXMiLCJpc05hTiIsInBhcnNlRmxvYXQiLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zYXZlX19zZXRfcGF5bWVudF9zdGF0dXMiLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19jbG9zZV9fc2V0X3BheW1lbnRfc3RhdHVzIiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfc2F2ZV9fc2V0X2Jvb2tpbmdfY29zdCIsIndwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX2Nsb3NlX19zZXRfYm9va2luZ19jb3N0Iiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfX3NlbmRfcGF5bWVudF9yZXF1ZXN0Iiwid3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXIiLCJ3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19fZXhwb3J0X2NzdiIsInBhcmFtcyIsInNlbGVjdGVkX2Jvb2tpbmdfaWRfYXJyIiwid3BiY19nZXRfc2VsZWN0ZWRfcm93X2lkIiwiam9pbiIsInNlYXJjaF9nZXRfYWxsX3BhcmFtcyIsImV4cG9ydF9jc3ZfdXJsIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUVBO0FBQ0E7QUFDQTs7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7O0FBQ0EsU0FBU0Esb0NBQVQsR0FBa0U7QUFBQSxNQUFuQkMsWUFBbUIsdUVBQUosRUFBSTtBQUVsRUMsRUFBQUEsT0FBTyxDQUFDQyxjQUFSLENBQXdCLDBCQUF4QjtBQUFzREQsRUFBQUEsT0FBTyxDQUFDRSxHQUFSLENBQWEsZ0NBQWIsRUFBK0NILFlBQS9DLEVBRlksQ0FHbEU7O0FBRUNJLEVBQUFBLDhDQUE4QyxHQUxtQixDQU9qRTs7QUFDQSxNQUFRQyxTQUFTLElBQUlMLFlBQVksQ0FBRSxZQUFGLENBQTNCLElBQW1ELENBQUVNLEtBQUssQ0FBQ0MsT0FBTixDQUFlUCxZQUFZLENBQUUsWUFBRixDQUEzQixDQUEzRCxFQUE0RztBQUFLO0FBRWhIQSxJQUFBQSxZQUFZLENBQUUsUUFBRixDQUFaLEdBQTJCUSx3QkFBd0IsQ0FBRVIsWUFBWSxDQUFFLFlBQUYsQ0FBZCxFQUFnQ1Msd0JBQXdCLENBQUNDLGdCQUF6QixDQUEyQyxRQUEzQyxDQUFoQyxDQUFuRDtBQUNBOztBQUVELE1BQUlDLGtCQUFrQixHQUFHO0FBQ2xCQyxJQUFBQSxNQUFNLEVBQVksMEJBREE7QUFFbEJDLElBQUFBLEtBQUssRUFBYUosd0JBQXdCLENBQUNDLGdCQUF6QixDQUEyQyxPQUEzQyxDQUZBO0FBR2xCSSxJQUFBQSxnQkFBZ0IsRUFBTVQsU0FBUyxJQUFJTCxZQUFZLENBQUUsU0FBRixDQUEzQixHQUE2Q1Msd0JBQXdCLENBQUNDLGdCQUF6QixDQUEyQyxTQUEzQyxDQUE3QyxHQUFzR1YsWUFBWSxDQUFFLFNBQUYsQ0FIcEg7QUFJbEJlLElBQUFBLGVBQWUsRUFBT1YsU0FBUyxJQUFJTCxZQUFZLENBQUUsUUFBRixDQUEzQixHQUE2Q1Msd0JBQXdCLENBQUNDLGdCQUF6QixDQUEyQyxRQUEzQyxDQUE3QyxHQUFzR1YsWUFBWSxDQUFFLFFBQUYsQ0FKcEg7QUFNbEJnQixJQUFBQSxhQUFhLEVBQUdoQjtBQU5FLEdBQXpCLENBYmlFLENBc0JqRTs7QUFDQSxNQUFLLE9BQU9BLFlBQVksQ0FBQ2lCLGFBQXBCLEtBQXNDLFdBQTNDLEVBQXdEO0FBQ3ZETixJQUFBQSxrQkFBa0IsQ0FBRSxlQUFGLENBQWxCLEdBQXdDWCxZQUFZLENBQUNpQixhQUFyRDtBQUNBLFdBQU9OLGtCQUFrQixDQUFDSyxhQUFuQixDQUFpQ0MsYUFBeEM7QUFDQSxHQTFCZ0UsQ0E0QmpFOzs7QUFDQUMsRUFBQUEsTUFBTSxDQUFDQyxJQUFQLENBQWFDLFlBQVksQ0FBQ0MsWUFBMUIsRUFFR1Ysa0JBRkg7QUFJRztBQUNKO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNJLFlBQVdXLGFBQVgsRUFBMEJDLFVBQTFCLEVBQXNDQyxLQUF0QyxFQUE4QztBQUVsRHZCLElBQUFBLE9BQU8sQ0FBQ0UsR0FBUixDQUFhLDJEQUFiLEVBQTBFbUIsYUFBMUU7QUFBMkZyQixJQUFBQSxPQUFPLENBQUN3QixRQUFSLEdBRnpDLENBSTdDOztBQUNBLFFBQU0sUUFBT0gsYUFBUCxNQUF5QixRQUExQixJQUF3Q0EsYUFBYSxLQUFLLElBQS9ELEVBQXNFO0FBQ3JFSixNQUFBQSxNQUFNLENBQUUsNkJBQUYsQ0FBTixDQUF3Q1EsSUFBeEMsR0FEcUUsQ0FDUjs7QUFDN0RSLE1BQUFBLE1BQU0sQ0FBRVQsd0JBQXdCLENBQUNrQixlQUF6QixDQUEwQyxtQkFBMUMsQ0FBRixDQUFOLENBQTBFQyxJQUExRSxDQUNXLDhFQUNDTixhQURELEdBRUEsUUFIWDtBQUtBO0FBQ0E7O0FBRURPLElBQUFBLDhDQUE4QztBQUU5Q0MsSUFBQUEsdUJBQXVCLENBQ2RSLGFBQWEsQ0FBRSwwQkFBRixDQUFiLENBQTRDUyxPQUE1QyxDQUFxRCxLQUFyRCxFQUE0RCxRQUE1RCxDQURjLEVBRVosT0FBT1QsYUFBYSxDQUFFLHlCQUFGLENBQXRCLEdBQXdELFNBQXhELEdBQW9FLE9BRnRELEVBR1YsZ0JBQWdCLE9BQU9BLGFBQWEsQ0FBRSx3Q0FBRixDQUFiLENBQTJELDJCQUEzRCxDQUF6QixHQUNELEtBREMsR0FFREEsYUFBYSxDQUFFLHdDQUFGLENBQWIsQ0FBMkQsMkJBQTNELENBTGEsQ0FBdkIsQ0FqQjZDLENBeUI3Qzs7QUFDQSxRQUFLLE9BQU9BLGFBQWEsQ0FBRSx5QkFBRixDQUF6QixFQUF3RDtBQUV2RCxVQUFJVSxzQkFBc0IsR0FBRyxJQUE3QixDQUZ1RCxDQUl2RDs7QUFDQSxVQUFLLFVBQVVWLGFBQWEsQ0FBRSx3Q0FBRixDQUFiLENBQTJELG9CQUEzRCxDQUFmLEVBQWtHO0FBRWpHVyxRQUFBQSxnREFBZ0QsQ0FBRVgsYUFBYSxDQUFFLHdDQUFGLENBQWIsQ0FBMkQsb0JBQTNELENBQUYsQ0FBaEQ7QUFFQSxZQUFJWSxZQUFZLEdBQUdDLFVBQVUsQ0FBRSxZQUFXO0FBRXhDLGNBQUtDLDJDQUEyQyxFQUFoRCxFQUFvRDtBQUNuRCxnQkFBSy9CLFNBQVMsSUFBSWlCLGFBQWEsQ0FBRSx3Q0FBRixDQUFiLENBQTJELG9CQUEzRCxFQUFtRixtQkFBbkYsQ0FBbEIsRUFBNEg7QUFDM0hlLGNBQUFBLFFBQVEsQ0FBQ0MsUUFBVCxDQUFrQkMsSUFBbEIsR0FBeUJqQixhQUFhLENBQUUsd0NBQUYsQ0FBYixDQUEyRCxvQkFBM0QsRUFBbUYsbUJBQW5GLENBQXpCO0FBQ0EsYUFGRCxNQUVPO0FBQ05lLGNBQUFBLFFBQVEsQ0FBQ0MsUUFBVCxDQUFrQkUsTUFBbEI7QUFDQTtBQUNEO0FBQ08sU0FUbUIsRUFVckIsSUFWcUIsQ0FBN0I7QUFXQVIsUUFBQUEsc0JBQXNCLEdBQUcsS0FBekI7QUFDQSxPQXJCc0QsQ0F1QnZEOzs7QUFDQSxVQUFLM0IsU0FBUyxJQUFJaUIsYUFBYSxDQUFFLHdDQUFGLENBQWIsQ0FBMkQsZ0JBQTNELENBQWxCLEVBQWlHO0FBQ2hHbUIsUUFBQUEsMENBQTBDLENBQUVuQixhQUFhLENBQUUsd0NBQUYsQ0FBYixDQUEyRCxnQkFBM0QsQ0FBRixDQUExQztBQUNBVSxRQUFBQSxzQkFBc0IsR0FBRyxLQUF6QjtBQUNBOztBQUVELFVBQUtBLHNCQUFMLEVBQTZCO0FBQzVCVSxRQUFBQSxzQ0FBc0MsR0FEVixDQUNjO0FBQzFDO0FBRUQsS0EzRDRDLENBNkQ3Qzs7O0FBQ0FDLElBQUFBLHdCQUF3QixDQUFFckIsYUFBYSxDQUFFLG9CQUFGLENBQWIsQ0FBdUMsdUJBQXZDLENBQUYsQ0FBeEIsQ0E5RDZDLENBZ0U3Qzs7QUFDQXNCLElBQUFBLHVCQUF1QjtBQUV2QjFCLElBQUFBLE1BQU0sQ0FBRSxlQUFGLENBQU4sQ0FBMEJVLElBQTFCLENBQWdDTixhQUFoQyxFQW5FNkMsQ0FtRUs7QUFDbEQsR0EvRUosRUFnRk11QixJQWhGTixDQWdGWSxVQUFXckIsS0FBWCxFQUFrQkQsVUFBbEIsRUFBOEJ1QixXQUE5QixFQUE0QztBQUFLLFFBQUtDLE1BQU0sQ0FBQzlDLE9BQVAsSUFBa0I4QyxNQUFNLENBQUM5QyxPQUFQLENBQWVFLEdBQXRDLEVBQTJDO0FBQUVGLE1BQUFBLE9BQU8sQ0FBQ0UsR0FBUixDQUFhLFlBQWIsRUFBMkJxQixLQUEzQixFQUFrQ0QsVUFBbEMsRUFBOEN1QixXQUE5QztBQUE4RDs7QUFDcEs1QixJQUFBQSxNQUFNLENBQUUsNkJBQUYsQ0FBTixDQUF3Q1EsSUFBeEMsR0FEb0QsQ0FDUzs7QUFDN0QsUUFBSXNCLGFBQWEsR0FBRyxhQUFhLFFBQWIsR0FBd0IsWUFBeEIsR0FBdUNGLFdBQTNEOztBQUNBLFFBQUt0QixLQUFLLENBQUN5QixZQUFYLEVBQXlCO0FBQ3hCRCxNQUFBQSxhQUFhLElBQUl4QixLQUFLLENBQUN5QixZQUF2QjtBQUNBOztBQUNERCxJQUFBQSxhQUFhLEdBQUdBLGFBQWEsQ0FBQ2pCLE9BQWQsQ0FBdUIsS0FBdkIsRUFBOEIsUUFBOUIsQ0FBaEI7QUFFQW1CLElBQUFBLDZCQUE2QixDQUFFRixhQUFGLENBQTdCO0FBQ0MsR0F6RkwsRUEwRlU7QUFDTjtBQTNGSixHQTdCaUUsQ0F5SDFEO0FBQ1A7QUFJRDtBQUNBO0FBQ0E7OztBQUNBLFNBQVNKLHVCQUFULEdBQWtDO0FBRWpDO0FBQ0EsTUFBSyxlQUFlLE9BQVExQixNQUFNLENBQUUsbUJBQUYsQ0FBTixDQUE4QmlDLGFBQTFELEVBQTBFO0FBQ3pFakMsSUFBQUEsTUFBTSxDQUFFLG1CQUFGLENBQU4sQ0FBOEJpQyxhQUE5QixDQUE2QyxNQUE3QztBQUNBO0FBQ0Q7QUFHRDtBQUNBOzs7QUFFQSxTQUFTQyw2QkFBVCxHQUF3QztBQUN2Q2xDLEVBQUFBLE1BQU0sQ0FBRSwwQ0FBRixDQUFOLENBQXFEUSxJQUFyRDtBQUNBUixFQUFBQSxNQUFNLENBQUUsMENBQUYsQ0FBTixDQUFxRG1DLElBQXJEO0FBQ0FwQixFQUFBQSxnREFBZ0QsQ0FBRTtBQUFDLGdDQUE0QjtBQUE3QixHQUFGLENBQWhEO0FBQ0E7O0FBRUQsU0FBU3FCLDRCQUFULEdBQXVDO0FBQ3RDcEMsRUFBQUEsTUFBTSxDQUFFLDBDQUFGLENBQU4sQ0FBcURRLElBQXJEO0FBQ0FSLEVBQUFBLE1BQU0sQ0FBRSwwQ0FBRixDQUFOLENBQXFEbUMsSUFBckQ7QUFDQXBCLEVBQUFBLGdEQUFnRCxDQUFFO0FBQUMsZ0NBQTRCO0FBQTdCLEdBQUYsQ0FBaEQ7QUFDQTs7QUFFRCxTQUFTc0IsOEJBQVQsQ0FBd0NDLFNBQXhDLEVBQWtEO0FBRWpEdEMsRUFBQUEsTUFBTSxDQUFFc0MsU0FBRixDQUFOLENBQW9CQyxPQUFwQixDQUE2QixpQkFBN0IsRUFBaURDLElBQWpELENBQXVELHNCQUF2RCxFQUFnRkMsTUFBaEY7QUFDQXpDLEVBQUFBLE1BQU0sQ0FBRXNDLFNBQUYsQ0FBTixDQUFvQkMsT0FBcEIsQ0FBNkIsaUJBQTdCLEVBQWlEQyxJQUFqRCxDQUF1RCxxQkFBdkQsRUFBK0VDLE1BQS9FO0FBRUE7QUFDRDtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTs7QUFDQzFELEVBQUFBLE9BQU8sQ0FBQ0UsR0FBUixDQUFhLGdDQUFiLEVBQStDcUQsU0FBL0M7QUFDQTtBQUVEO0FBQ0E7O0FBRUE7QUFDQTtBQUNBOzs7QUFDQSxTQUFTSSxtQ0FBVCxHQUE4QztBQUU3QzFDLEVBQUFBLE1BQU0sQ0FBRSxnQ0FBRixDQUFOLENBQTJDMkMsSUFBM0MsQ0FBaUQsVUFBV0MsS0FBWCxFQUFrQjtBQUVsRSxRQUFJQyxTQUFTLEdBQUc3QyxNQUFNLENBQUUsSUFBRixDQUFOLENBQWU4QyxJQUFmLENBQXFCLDBCQUFyQixDQUFoQixDQUZrRSxDQUVHOztBQUVyRSxRQUFLM0QsU0FBUyxLQUFLMEQsU0FBbkIsRUFBOEI7QUFDN0I3QyxNQUFBQSxNQUFNLENBQUUsSUFBRixDQUFOLENBQWV3QyxJQUFmLENBQXFCLG1CQUFtQkssU0FBbkIsR0FBK0IsSUFBcEQsRUFBMkRFLElBQTNELENBQWlFLFVBQWpFLEVBQTZFLElBQTdFOztBQUVBLFVBQU0sTUFBTUYsU0FBUCxJQUFzQjdDLE1BQU0sQ0FBRSxJQUFGLENBQU4sQ0FBZWdELFFBQWYsQ0FBeUIsOEJBQXpCLENBQTNCLEVBQXVGO0FBQVM7QUFFL0YsWUFBSUMscUJBQXFCLEdBQUdqRCxNQUFNLENBQUUsSUFBRixDQUFOLENBQWV1QyxPQUFmLENBQXdCLG9CQUF4QixFQUErQ0MsSUFBL0MsQ0FBcUQsNEJBQXJELENBQTVCLENBRnNGLENBSXRGOztBQUNBUyxRQUFBQSxxQkFBcUIsQ0FBQ0MsUUFBdEIsQ0FBZ0MsYUFBaEMsRUFMc0YsQ0FLcEM7O0FBQ2pELFlBQUssZUFBZSxPQUFRQyxVQUE1QixFQUEwQztBQUMxQ0YsVUFBQUEscUJBQXFCLENBQUNHLEdBQXRCLENBQTBCLENBQTFCLEVBQTZCQyxNQUE3QixDQUFvQ0MsVUFBcEMsQ0FBZ0RULFNBQWhEO0FBQ0M7QUFDRjtBQUNEO0FBQ0QsR0FsQkQ7QUFtQkE7QUFFRDtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBQ0EsU0FBU1UsbUNBQVQsR0FBOEM7QUFFN0N2RCxFQUFBQSxNQUFNLENBQUUscURBQUYsQ0FBTixDQUFnRTJDLElBQWhFLENBQXNFLFVBQVdDLEtBQVgsRUFBa0I7QUFDdkYsUUFBSVksUUFBUSxHQUFHeEQsTUFBTSxDQUFFLElBQUYsQ0FBTixDQUFleUQsR0FBZixFQUFmOztBQUNBLFFBQU10RSxTQUFTLEtBQUtxRSxRQUFmLElBQTZCLE1BQU1BLFFBQXhDLEVBQW1EO0FBRWxELFVBQUlFLGFBQWEsR0FBRzFELE1BQU0sQ0FBRSxJQUFGLENBQU4sQ0FBZXVDLE9BQWYsQ0FBd0IsV0FBeEIsRUFBc0NDLElBQXRDLENBQTRDLDBCQUE1QyxDQUFwQjs7QUFFQSxVQUFLa0IsYUFBYSxDQUFDQyxNQUFkLEdBQXVCLENBQTVCLEVBQStCO0FBRTlCRCxRQUFBQSxhQUFhLENBQUNSLFFBQWQsQ0FBd0IsYUFBeEIsRUFGOEIsQ0FFWTs7QUFDMUMsWUFBSyxlQUFlLE9BQVFDLFVBQTVCLEVBQXlDO0FBQ3hDO0FBQ0E7QUFFQU8sVUFBQUEsYUFBYSxDQUFDTixHQUFkLENBQW1CLENBQW5CLEVBQXVCQyxNQUF2QixDQUE4Qk8sUUFBOUIsQ0FBd0M7QUFDdkNDLFlBQUFBLFNBQVMsRUFBRSxJQUQ0QjtBQUV2Q0MsWUFBQUEsT0FBTyxFQUFJTixRQUFRLENBQUMzQyxPQUFULENBQWtCLFNBQWxCLEVBQTZCLE1BQTdCO0FBRjRCLFdBQXhDO0FBSUE7QUFDRDtBQUNEO0FBQ0QsR0FwQkQ7QUFxQkE7QUFFRDtBQUNBO0FBQ0E7QUFDQTtBQUNBOzs7QUFDQSxTQUFTa0Qsa0NBQVQsQ0FBNkNDLFNBQTdDLEVBQXdEO0FBRXZEQSxFQUFBQSxTQUFTLENBQUN6QixPQUFWLENBQWtCLFdBQWxCLEVBQStCQyxJQUEvQixDQUFvQyxvQkFBcEMsRUFBMERDLE1BQTFEO0FBQ0E7QUFHRDtBQUNBOzs7QUFFQSxTQUFTd0IsZ0RBQVQsQ0FBMkRDLFVBQTNELEVBQXVFQyxXQUF2RSxFQUFvRjtBQUVuRjtBQUNBbkUsRUFBQUEsTUFBTSxDQUFFLHNDQUFGLENBQU4sQ0FBaUR5RCxHQUFqRCxDQUFzRFMsVUFBdEQsRUFIbUYsQ0FLbkY7O0FBQ0FsRSxFQUFBQSxNQUFNLENBQUUsMkNBQUYsQ0FBTixDQUFzRHlELEdBQXRELENBQTJEVSxXQUEzRCxFQUF5RUMsT0FBekUsQ0FBa0YsUUFBbEY7QUFDQSxNQUFJQyxHQUFKLENBUG1GLENBU25GOztBQUNBQSxFQUFBQSxHQUFHLEdBQUdyRSxNQUFNLENBQUUsbUNBQUYsQ0FBTixDQUE4Q3NFLE1BQTlDLEVBQU4sQ0FWbUYsQ0FZbkY7O0FBQ0FELEVBQUFBLEdBQUcsQ0FBQ0UsUUFBSixDQUFjdkUsTUFBTSxDQUFFLHNEQUFzRGtFLFVBQXhELENBQXBCO0FBQ0FHLEVBQUFBLEdBQUcsR0FBRyxJQUFOLENBZG1GLENBZ0JuRjtBQUNBOztBQUNBLE1BQUssQ0FBRXJFLE1BQU0sQ0FBRSxzREFBc0RrRSxVQUF4RCxDQUFOLENBQTJFTSxFQUEzRSxDQUE4RSxVQUE5RSxDQUFQLEVBQWtHO0FBQ2pHeEUsSUFBQUEsTUFBTSxDQUFFLDRDQUFGLENBQU4sQ0FBdURRLElBQXZEO0FBQ0EsR0FwQmtGLENBc0JuRjs7O0FBQ0FSLEVBQUFBLE1BQU0sQ0FBRSxzREFBc0RrRSxVQUF4RCxDQUFOLENBQTJFekIsTUFBM0U7QUFDQTs7QUFFRCxTQUFTZ0MsZ0RBQVQsQ0FBMkRDLE9BQTNELEVBQW9FQyxjQUFwRSxFQUFvRkMsS0FBcEYsRUFBMkY7QUFFMUYvRixFQUFBQSxvQ0FBb0MsQ0FBRTtBQUM1QixzQkFBeUI4RixjQURHO0FBRTVCLGtCQUF5QjNFLE1BQU0sQ0FBRSxzQ0FBRixDQUFOLENBQWlEeUQsR0FBakQsRUFGRztBQUc1Qiw0QkFBeUJ6RCxNQUFNLENBQUUsMkNBQUYsQ0FBTixDQUFzRHlELEdBQXRELEVBSEc7QUFJNUIsNkJBQXlCbUI7QUFKRyxHQUFGLENBQXBDO0FBT0FDLEVBQUFBLCtCQUErQixDQUFFSCxPQUFGLENBQS9CLENBVDBGLENBVzFGO0FBQ0E7O0FBRUQsU0FBU0ksaURBQVQsR0FBNEQ7QUFFM0QsTUFBSUMsS0FBSixDQUYyRCxDQUkzRDs7QUFDQUEsRUFBQUEsS0FBSyxHQUFHL0UsTUFBTSxDQUFDLG1DQUFELENBQU4sQ0FBNENzRSxNQUE1QyxFQUFSLENBTDJELENBTzNEOztBQUNBUyxFQUFBQSxLQUFLLENBQUNSLFFBQU4sQ0FBZXZFLE1BQU0sQ0FBQyxnREFBRCxDQUFyQjtBQUNBK0UsRUFBQUEsS0FBSyxHQUFHLElBQVIsQ0FUMkQsQ0FXM0Q7O0FBQ0EvRSxFQUFBQSxNQUFNLENBQUMsa0RBQUQsQ0FBTixDQUEyRFEsSUFBM0Q7QUFDQTtBQUVEO0FBQ0E7OztBQUVBLFNBQVN3RSxrREFBVCxDQUE2RGQsVUFBN0QsRUFBeUVDLFdBQXpFLEVBQXNGO0FBRXJGO0FBQ0FuRSxFQUFBQSxNQUFNLENBQUUsa0RBQUYsQ0FBTixDQUE2RHlELEdBQTdELENBQWtFUyxVQUFsRSxFQUhxRixDQUtyRjs7QUFDQWxFLEVBQUFBLE1BQU0sQ0FBRSx1REFBRixDQUFOLENBQWtFeUQsR0FBbEUsQ0FBdUVVLFdBQXZFLEVBQXFGQyxPQUFyRixDQUE4RixRQUE5RjtBQUNBLE1BQUlDLEdBQUosQ0FQcUYsQ0FTckY7O0FBQ0FBLEVBQUFBLEdBQUcsR0FBR3JFLE1BQU0sQ0FBRSwrQ0FBRixDQUFOLENBQTBEc0UsTUFBMUQsRUFBTixDQVZxRixDQVlyRjs7QUFDQUQsRUFBQUEsR0FBRyxDQUFDRSxRQUFKLENBQWN2RSxNQUFNLENBQUUsa0VBQWtFa0UsVUFBcEUsQ0FBcEI7QUFDQUcsRUFBQUEsR0FBRyxHQUFHLElBQU4sQ0FkcUYsQ0FnQnJGOztBQUNBLE1BQUssQ0FBRXJFLE1BQU0sQ0FBRSxrRUFBa0VrRSxVQUFwRSxDQUFOLENBQXVGTSxFQUF2RixDQUEwRixVQUExRixDQUFQLEVBQThHO0FBQzdHeEUsSUFBQUEsTUFBTSxDQUFFLDRDQUFGLENBQU4sQ0FBdURRLElBQXZEO0FBQ0EsR0FuQm9GLENBcUJyRjs7O0FBQ0FSLEVBQUFBLE1BQU0sQ0FBRSxrRUFBa0VrRSxVQUFwRSxDQUFOLENBQXVGekIsTUFBdkY7QUFDQTs7QUFFRCxTQUFTd0Msa0RBQVQsQ0FBNkRQLE9BQTdELEVBQXNFQyxjQUF0RSxFQUFzRkMsS0FBdEYsRUFBNkY7QUFFNUYvRixFQUFBQSxvQ0FBb0MsQ0FBRTtBQUM1QixzQkFBeUI4RixjQURHO0FBRTVCLGtCQUF5QjNFLE1BQU0sQ0FBRSxrREFBRixDQUFOLENBQTZEeUQsR0FBN0QsRUFGRztBQUc1Qiw0QkFBeUJ6RCxNQUFNLENBQUUsdURBQUYsQ0FBTixDQUFrRXlELEdBQWxFLEVBSEc7QUFJNUIsNkJBQXlCbUI7QUFKRyxHQUFGLENBQXBDO0FBT0FDLEVBQUFBLCtCQUErQixDQUFFSCxPQUFGLENBQS9CLENBVDRGLENBVzVGO0FBQ0E7O0FBRUQsU0FBU1EsbURBQVQsR0FBOEQ7QUFFN0QsTUFBSUgsS0FBSixDQUY2RCxDQUk3RDs7QUFDQUEsRUFBQUEsS0FBSyxHQUFHL0UsTUFBTSxDQUFDLCtDQUFELENBQU4sQ0FBd0RzRSxNQUF4RCxFQUFSLENBTDZELENBTzdEOztBQUNBUyxFQUFBQSxLQUFLLENBQUNSLFFBQU4sQ0FBZXZFLE1BQU0sQ0FBQyw0REFBRCxDQUFyQjtBQUNBK0UsRUFBQUEsS0FBSyxHQUFHLElBQVIsQ0FUNkQsQ0FXN0Q7O0FBQ0EvRSxFQUFBQSxNQUFNLENBQUMsOERBQUQsQ0FBTixDQUF1RVEsSUFBdkU7QUFDQTtBQUVEO0FBQ0E7OztBQUVBLFNBQVMyRSxtREFBVCxDQUE4RGpCLFVBQTlELEVBQTBFO0FBRXpFLE1BQUlrQixPQUFPLEdBQUdwRixNQUFNLENBQUUsaURBQWlEa0UsVUFBbkQsQ0FBTixDQUFzRTFCLElBQXRFLENBQTRFLFFBQTVFLENBQWQ7QUFFQSxNQUFJNkMsbUJBQW1CLEdBQUdELE9BQU8sQ0FBQ3RDLElBQVIsQ0FBYyxvQkFBZCxDQUExQixDQUp5RSxDQU16RTs7QUFDQSxNQUFLLENBQUN3QyxLQUFLLENBQUVDLFVBQVUsQ0FBRUYsbUJBQUYsQ0FBWixDQUFYLEVBQWtEO0FBQ2pERCxJQUFBQSxPQUFPLENBQUM1QyxJQUFSLENBQWMsbUJBQWQsRUFBb0NPLElBQXBDLENBQTBDLFVBQTFDLEVBQXNELElBQXRELEVBRGlELENBQ29CO0FBQ3JFLEdBRkQsTUFFTztBQUNOcUMsSUFBQUEsT0FBTyxDQUFDNUMsSUFBUixDQUFjLG1CQUFtQjZDLG1CQUFuQixHQUF5QyxJQUF2RCxFQUE4RHRDLElBQTlELENBQW9FLFVBQXBFLEVBQWdGLElBQWhGLEVBRE0sQ0FDbUY7QUFDekYsR0FYd0UsQ0FhekU7OztBQUNBLE1BQUssQ0FBRS9DLE1BQU0sQ0FBRSxpREFBaURrRSxVQUFuRCxDQUFOLENBQXNFTSxFQUF0RSxDQUF5RSxVQUF6RSxDQUFQLEVBQTZGO0FBQzVGeEUsSUFBQUEsTUFBTSxDQUFFLDRDQUFGLENBQU4sQ0FBdURRLElBQXZEO0FBQ0EsR0FoQndFLENBa0J6RTs7O0FBQ0FSLEVBQUFBLE1BQU0sQ0FBRSxpREFBaURrRSxVQUFuRCxDQUFOLENBQXNFekIsTUFBdEU7QUFDQTs7QUFFRCxTQUFTK0MsbURBQVQsQ0FBOER0QixVQUE5RCxFQUEwRVEsT0FBMUUsRUFBbUZDLGNBQW5GLEVBQW1HQyxLQUFuRyxFQUEwRztBQUV6Ry9GLEVBQUFBLG9DQUFvQyxDQUFFO0FBQzVCLHNCQUF5QjhGLGNBREc7QUFFNUIsa0JBQXlCVCxVQUZHO0FBRzVCLCtCQUE0QmxFLE1BQU0sQ0FBRSwrQkFBK0JrRSxVQUFqQyxDQUFOLENBQW9EVCxHQUFwRCxFQUhBO0FBSTVCLDZCQUF5Qm1CLEtBQUssR0FBRztBQUpMLEdBQUYsQ0FBcEM7QUFPQUMsRUFBQUEsK0JBQStCLENBQUVILE9BQUYsQ0FBL0I7QUFFQTFFLEVBQUFBLE1BQU0sQ0FBRSxNQUFNNEUsS0FBTixHQUFjLFNBQWhCLENBQU4sQ0FBaUNwRSxJQUFqQyxHQVh5RyxDQVl6RztBQUVBOztBQUVELFNBQVNpRixvREFBVCxHQUErRDtBQUM5RDtBQUNBekYsRUFBQUEsTUFBTSxDQUFDLDZDQUFELENBQU4sQ0FBc0RRLElBQXREO0FBQ0E7QUFHRDtBQUNBOzs7QUFFQSxTQUFTa0YsaURBQVQsQ0FBNER4QixVQUE1RCxFQUF3RVEsT0FBeEUsRUFBaUZDLGNBQWpGLEVBQWlHQyxLQUFqRyxFQUF3RztBQUV2Ry9GLEVBQUFBLG9DQUFvQyxDQUFFO0FBQzVCLHNCQUF5QjhGLGNBREc7QUFFNUIsa0JBQXlCVCxVQUZHO0FBRzVCLG9CQUFzQmxFLE1BQU0sQ0FBRSw2QkFBNkJrRSxVQUE3QixHQUEwQyxPQUE1QyxDQUFOLENBQTJEVCxHQUEzRCxFQUhNO0FBSTVCLDZCQUF5Qm1CLEtBQUssR0FBRztBQUpMLEdBQUYsQ0FBcEM7QUFPQUMsRUFBQUEsK0JBQStCLENBQUVILE9BQUYsQ0FBL0I7QUFFQTFFLEVBQUFBLE1BQU0sQ0FBRSxNQUFNNEUsS0FBTixHQUFjLFNBQWhCLENBQU4sQ0FBaUNwRSxJQUFqQyxHQVh1RyxDQVl2RztBQUVBOztBQUVELFNBQVNtRixrREFBVCxHQUE2RDtBQUM1RDtBQUNBM0YsRUFBQUEsTUFBTSxDQUFDLDJDQUFELENBQU4sQ0FBb0RRLElBQXBEO0FBQ0E7QUFHRDtBQUNBOzs7QUFFQSxTQUFTb0YsZ0RBQVQsR0FBMkQ7QUFFMUQvRyxFQUFBQSxvQ0FBb0MsQ0FBRTtBQUM1QixzQkFBeUIsc0JBREc7QUFFNUIsa0JBQXlCbUIsTUFBTSxDQUFFLDBDQUFGLENBQU4sQ0FBb0R5RCxHQUFwRCxFQUZHO0FBRzVCLHdCQUF5QnpELE1BQU0sQ0FBRSxnREFBRixDQUFOLENBQTBEeUQsR0FBMUQsRUFIRztBQUk1Qiw2QkFBeUI7QUFKRyxHQUFGLENBQXBDO0FBTUFvQixFQUFBQSwrQkFBK0IsQ0FBRTdFLE1BQU0sQ0FBRSwyQ0FBRixDQUFOLENBQXNEb0QsR0FBdEQsQ0FBMkQsQ0FBM0QsQ0FBRixDQUEvQjtBQUNBO0FBR0Q7QUFDQTs7O0FBRUEsU0FBU3lDLGtEQUFULEdBQTZEO0FBRTVEaEgsRUFBQUEsb0NBQW9DLENBQUU7QUFDNUIsc0JBQXlCLHdCQURHO0FBRTVCLDZCQUF5QixpREFGRztBQUkxQixnQ0FBaUNtQixNQUFNLENBQUUsd0ZBQUYsQ0FBTixDQUFrR3lELEdBQWxHLEVBSlA7QUFLMUIsdUNBQXNDekQsTUFBTSxDQUFFLCtFQUFGLENBQU4sQ0FBMEZ5RCxHQUExRixFQUxaO0FBTTFCLDRDQUEwQ3pELE1BQU0sQ0FBRSxvR0FBRixDQUFOLENBQThHeUQsR0FBOUcsRUFOaEI7QUFRMUIsaUNBQWlDekQsTUFBTSxDQUFFLHlGQUFGLENBQU4sQ0FBbUd5RCxHQUFuRyxFQVJQO0FBUzFCLHdDQUF1Q3pELE1BQU0sQ0FBRSxnRkFBRixDQUFOLENBQTJGeUQsR0FBM0YsRUFUYjtBQVUxQiw2Q0FBMEN6RCxNQUFNLENBQUUscUdBQUYsQ0FBTixDQUErR3lELEdBQS9HLEVBVmhCO0FBWTFCLCtCQUE2QnpELE1BQU0sQ0FBRSx1RUFBRixDQUFOLENBQWtGeUQsR0FBbEYsRUFaSDtBQWExQiw2QkFBMkJ6RCxNQUFNLENBQUUscUZBQUYsQ0FBTixDQUErRnlELEdBQS9GO0FBYkQsR0FBRixDQUFwQztBQWVBb0IsRUFBQUEsK0JBQStCLENBQUU3RSxNQUFNLENBQUUsK0ZBQUYsQ0FBTixDQUEwR29ELEdBQTFHLENBQStHLENBQS9HLENBQUYsQ0FBL0I7QUFDQTtBQUdEO0FBQ0E7OztBQUNBLFNBQVMwQyxzQ0FBVCxDQUFpREMsTUFBakQsRUFBeUQ7QUFFeEQsTUFBSUMsdUJBQXVCLEdBQUdDLHdCQUF3QixFQUF0RDtBQUVBcEgsRUFBQUEsb0NBQW9DLENBQUU7QUFDNUIsc0JBQTBCa0gsTUFBTSxDQUFFLGdCQUFGLENBREo7QUFFNUIsNkJBQTBCQSxNQUFNLENBQUUsdUJBQUYsQ0FGSjtBQUk1QixtQkFBMEJBLE1BQU0sQ0FBRSxhQUFGLENBSko7QUFLNUIsNEJBQTBCQSxNQUFNLENBQUUsc0JBQUYsQ0FMSjtBQU01Qiw4QkFBMEJBLE1BQU0sQ0FBRSx3QkFBRixDQU5KO0FBUTVCLGtCQUFlQyx1QkFBdUIsQ0FBQ0UsSUFBeEIsQ0FBNkIsR0FBN0IsQ0FSYTtBQVM1QixxQkFBa0IzRyx3QkFBd0IsQ0FBQzRHLHFCQUF6QjtBQVRVLEdBQUYsQ0FBcEM7QUFZQSxNQUFJekIsT0FBTyxHQUFHMUUsTUFBTSxDQUFFLE1BQU0rRixNQUFNLENBQUUsdUJBQUYsQ0FBZCxDQUFOLENBQWtEM0MsR0FBbEQsQ0FBdUQsQ0FBdkQsQ0FBZDtBQUVBeUIsRUFBQUEsK0JBQStCLENBQUVILE9BQUYsQ0FBL0I7QUFDQTtBQUVEO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7OztBQUNBLFNBQVNuRCwwQ0FBVCxDQUFxRDZFLGNBQXJELEVBQXFFO0FBRXBFO0FBRUFqRixFQUFBQSxRQUFRLENBQUNDLFFBQVQsQ0FBa0JDLElBQWxCLEdBQXlCK0UsY0FBekIsQ0FKb0UsQ0FJNUI7QUFFeEM7QUFDQTtBQUNBIiwic291cmNlc0NvbnRlbnQiOlsiXCJ1c2Ugc3RyaWN0XCI7XHJcblxyXG4vKipcclxuICogICBBamF4ICAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuLy92YXIgaXNfdGhpc19hY3Rpb24gPSBmYWxzZTtcclxuLyoqXHJcbiAqIFNlbmQgQWpheCBhY3Rpb24gcmVxdWVzdCwgIGxpa2UgYXBwcm92aW5nIG9yIGNhbmNlbGxhdGlvblxyXG4gKlxyXG4gKiBAcGFyYW0gYWN0aW9uX3BhcmFtXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX2FqYXhfYWN0aW9uX3JlcXVlc3QoIGFjdGlvbl9wYXJhbSA9IHt9ICl7XHJcblxyXG5jb25zb2xlLmdyb3VwQ29sbGFwc2VkKCAnV1BCQ19BSlhfQk9PS0lOR19BQ1RJT05TJyApOyBjb25zb2xlLmxvZyggJyA9PSBBamF4IEFjdGlvbnMgOjogUGFyYW1zID09ICcsIGFjdGlvbl9wYXJhbSApO1xyXG4vL2lzX3RoaXNfYWN0aW9uID0gdHJ1ZTtcclxuXHJcblx0d3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9zdGFydCgpO1xyXG5cclxuXHQvLyBHZXQgcmVkZWZpbmVkIExvY2FsZSwgIGlmIGFjdGlvbiBvbiBzaW5nbGUgYm9va2luZyAhXHJcblx0aWYgKCAgKCB1bmRlZmluZWQgIT0gYWN0aW9uX3BhcmFtWyAnYm9va2luZ19pZCcgXSApICYmICggISBBcnJheS5pc0FycmF5KCBhY3Rpb25fcGFyYW1bICdib29raW5nX2lkJyBdICkgKSApe1x0XHRcdFx0Ly8gTm90IGFycmF5XHJcblxyXG5cdFx0YWN0aW9uX3BhcmFtWyAnbG9jYWxlJyBdID0gd3BiY19nZXRfc2VsZWN0ZWRfbG9jYWxlKCBhY3Rpb25fcGFyYW1bICdib29raW5nX2lkJyBdLCB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X3NlY3VyZV9wYXJhbSggJ2xvY2FsZScgKSApO1xyXG5cdH1cclxuXHJcblx0dmFyIGFjdGlvbl9wb3N0X3BhcmFtcyA9IHtcclxuXHRcdFx0XHRcdFx0XHRcdGFjdGlvbiAgICAgICAgICA6ICdXUEJDX0FKWF9CT09LSU5HX0FDVElPTlMnLFxyXG5cdFx0XHRcdFx0XHRcdFx0bm9uY2UgICAgICAgICAgIDogd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9zZWN1cmVfcGFyYW0oICdub25jZScgKSxcclxuXHRcdFx0XHRcdFx0XHRcdHdwYmNfYWp4X3VzZXJfaWQ6ICggKCB1bmRlZmluZWQgPT0gYWN0aW9uX3BhcmFtWyAndXNlcl9pZCcgXSApID8gd3BiY19hanhfYm9va2luZ19saXN0aW5nLmdldF9zZWN1cmVfcGFyYW0oICd1c2VyX2lkJyApIDogYWN0aW9uX3BhcmFtWyAndXNlcl9pZCcgXSApLFxyXG5cdFx0XHRcdFx0XHRcdFx0d3BiY19hanhfbG9jYWxlOiAgKCAoIHVuZGVmaW5lZCA9PSBhY3Rpb25fcGFyYW1bICdsb2NhbGUnIF0gKSAgPyB3cGJjX2FqeF9ib29raW5nX2xpc3RpbmcuZ2V0X3NlY3VyZV9wYXJhbSggJ2xvY2FsZScgKSAgOiBhY3Rpb25fcGFyYW1bICdsb2NhbGUnIF0gKSxcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRhY3Rpb25fcGFyYW1zXHQ6IGFjdGlvbl9wYXJhbVxyXG5cdFx0XHRcdFx0XHRcdH07XHJcblxyXG5cdC8vIEl0J3MgcmVxdWlyZWQgZm9yIENTViBleHBvcnQgLSBnZXR0aW5nIHRoZSBzYW1lIGxpc3QgIG9mIGJvb2tpbmdzXHJcblx0aWYgKCB0eXBlb2YgYWN0aW9uX3BhcmFtLnNlYXJjaF9wYXJhbXMgIT09ICd1bmRlZmluZWQnICl7XHJcblx0XHRhY3Rpb25fcG9zdF9wYXJhbXNbICdzZWFyY2hfcGFyYW1zJyBdID0gYWN0aW9uX3BhcmFtLnNlYXJjaF9wYXJhbXM7XHJcblx0XHRkZWxldGUgYWN0aW9uX3Bvc3RfcGFyYW1zLmFjdGlvbl9wYXJhbXMuc2VhcmNoX3BhcmFtcztcclxuXHR9XHJcblxyXG5cdC8vIFN0YXJ0IEFqYXhcclxuXHRqUXVlcnkucG9zdCggd3BiY19nbG9iYWwxLndwYmNfYWpheHVybCAsXHJcblxyXG5cdFx0XHRcdGFjdGlvbl9wb3N0X3BhcmFtcyAsXHJcblxyXG5cdFx0XHRcdC8qKlxyXG5cdFx0XHRcdCAqIFMgdSBjIGMgZSBzIHNcclxuXHRcdFx0XHQgKlxyXG5cdFx0XHRcdCAqIEBwYXJhbSByZXNwb25zZV9kYXRhXHRcdC1cdGl0cyBvYmplY3QgcmV0dXJuZWQgZnJvbSAgQWpheCAtIGNsYXNzLWxpdmUtc2VhcmNnLnBocFxyXG5cdFx0XHRcdCAqIEBwYXJhbSB0ZXh0U3RhdHVzXHRcdC1cdCdzdWNjZXNzJ1xyXG5cdFx0XHRcdCAqIEBwYXJhbSBqcVhIUlx0XHRcdFx0LVx0T2JqZWN0XHJcblx0XHRcdFx0ICovXHJcblx0XHRcdFx0ZnVuY3Rpb24gKCByZXNwb25zZV9kYXRhLCB0ZXh0U3RhdHVzLCBqcVhIUiApIHtcclxuXHJcbmNvbnNvbGUubG9nKCAnID09IEFqYXggQWN0aW9ucyA6OiBSZXNwb25zZSBXUEJDX0FKWF9CT09LSU5HX0FDVElPTlMgPT0gJywgcmVzcG9uc2VfZGF0YSApOyBjb25zb2xlLmdyb3VwRW5kKCk7XHJcblxyXG5cdFx0XHRcdFx0Ly8gUHJvYmFibHkgRXJyb3JcclxuXHRcdFx0XHRcdGlmICggKHR5cGVvZiByZXNwb25zZV9kYXRhICE9PSAnb2JqZWN0JykgfHwgKHJlc3BvbnNlX2RhdGEgPT09IG51bGwpICl7XHJcblx0XHRcdFx0XHRcdGpRdWVyeSggJy53cGJjX2FqeF91bmRlcl90b29sYmFyX3JvdycgKS5oaWRlKCk7XHQgXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0Ly9GaXhJbjogOS42LjEuNVxyXG5cdFx0XHRcdFx0XHRqUXVlcnkoIHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5nZXRfb3RoZXJfcGFyYW0oICdsaXN0aW5nX2NvbnRhaW5lcicgKSApLmh0bWwoXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPGRpdiBjbGFzcz1cIndwYmMtc2V0dGluZ3Mtbm90aWNlIG5vdGljZS13YXJuaW5nXCIgc3R5bGU9XCJ0ZXh0LWFsaWduOmxlZnRcIj4nICtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0cmVzcG9uc2VfZGF0YSArXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnPC9kaXY+J1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG5cdFx0XHRcdFx0XHRyZXR1cm47XHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0d3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9fc3Bpbl9wYXVzZSgpO1xyXG5cclxuXHRcdFx0XHRcdHdwYmNfYWRtaW5fc2hvd19tZXNzYWdlKFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHQgIHJlc3BvbnNlX2RhdGFbICdhanhfYWZ0ZXJfYWN0aW9uX21lc3NhZ2UnIF0ucmVwbGFjZSggL1xcbi9nLCBcIjxiciAvPlwiIClcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCAoICcxJyA9PSByZXNwb25zZV9kYXRhWyAnYWp4X2FmdGVyX2FjdGlvbl9yZXN1bHQnIF0gKSA/ICdzdWNjZXNzJyA6ICdlcnJvcidcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCAoICggJ3VuZGVmaW5lZCcgPT09IHR5cGVvZihyZXNwb25zZV9kYXRhWyAnYWp4X2FmdGVyX2FjdGlvbl9yZXN1bHRfYWxsX3BhcmFtc19hcnInIF1bICdhZnRlcl9hY3Rpb25fcmVzdWx0X2RlbGF5JyBdKSApXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0PyAxMDAwMFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdDogcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnYWZ0ZXJfYWN0aW9uX3Jlc3VsdF9kZWxheScgXSApXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQpO1xyXG5cclxuXHRcdFx0XHRcdC8vIFN1Y2Nlc3MgcmVzcG9uc2VcclxuXHRcdFx0XHRcdGlmICggJzEnID09IHJlc3BvbnNlX2RhdGFbICdhanhfYWZ0ZXJfYWN0aW9uX3Jlc3VsdCcgXSApe1xyXG5cclxuXHRcdFx0XHRcdFx0dmFyIGlzX3JlbG9hZF9hamF4X2xpc3RpbmcgPSB0cnVlO1xyXG5cclxuXHRcdFx0XHRcdFx0Ly8gQWZ0ZXIgR29vZ2xlIENhbGVuZGFyIGltcG9ydCBzaG93IGltcG9ydGVkIGJvb2tpbmdzIGFuZCByZWxvYWQgdGhlIHBhZ2UgZm9yIHRvb2xiYXIgcGFyYW1ldGVycyB1cGRhdGVcclxuXHRcdFx0XHRcdFx0aWYgKCBmYWxzZSAhPT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnbmV3X2xpc3RpbmdfcGFyYW1zJyBdICl7XHJcblxyXG5cdFx0XHRcdFx0XHRcdHdwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF93aXRoX3BhcmFtcyggcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnbmV3X2xpc3RpbmdfcGFyYW1zJyBdICk7XHJcblxyXG5cdFx0XHRcdFx0XHRcdHZhciBjbG9zZWRfdGltZXIgPSBzZXRUaW1lb3V0KCBmdW5jdGlvbiAoKXtcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdGlmICggd3BiY19ib29raW5nX2xpc3RpbmdfcmVsb2FkX2J1dHRvbl9faXNfc3BpbigpICl7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0aWYgKCB1bmRlZmluZWQgIT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnbmV3X2xpc3RpbmdfcGFyYW1zJyBdWyAncmVsb2FkX3VybF9wYXJhbXMnIF0gKXtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdGRvY3VtZW50LmxvY2F0aW9uLmhyZWYgPSByZXNwb25zZV9kYXRhWyAnYWp4X2FmdGVyX2FjdGlvbl9yZXN1bHRfYWxsX3BhcmFtc19hcnInIF1bICduZXdfbGlzdGluZ19wYXJhbXMnIF1bICdyZWxvYWRfdXJsX3BhcmFtcycgXTtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHR9IGVsc2Uge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0ZG9jdW1lbnQubG9jYXRpb24ucmVsb2FkKCk7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0fVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgMjAwMCApO1xyXG5cdFx0XHRcdFx0XHRcdGlzX3JlbG9hZF9hamF4X2xpc3RpbmcgPSBmYWxzZTtcclxuXHRcdFx0XHRcdFx0fVxyXG5cclxuXHRcdFx0XHRcdFx0Ly8gU3RhcnQgZG93bmxvYWQgZXhwb3J0ZWQgQ1NWIGZpbGVcclxuXHRcdFx0XHRcdFx0aWYgKCB1bmRlZmluZWQgIT0gcmVzcG9uc2VfZGF0YVsgJ2FqeF9hZnRlcl9hY3Rpb25fcmVzdWx0X2FsbF9wYXJhbXNfYXJyJyBdWyAnZXhwb3J0X2Nzdl91cmwnIF0gKXtcclxuXHRcdFx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX19leHBvcnRfY3N2X3VybF9fZG93bmxvYWQoIHJlc3BvbnNlX2RhdGFbICdhanhfYWZ0ZXJfYWN0aW9uX3Jlc3VsdF9hbGxfcGFyYW1zX2FycicgXVsgJ2V4cG9ydF9jc3ZfdXJsJyBdICk7XHJcblx0XHRcdFx0XHRcdFx0aXNfcmVsb2FkX2FqYXhfbGlzdGluZyA9IGZhbHNlO1xyXG5cdFx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0XHRpZiAoIGlzX3JlbG9hZF9hamF4X2xpc3RpbmcgKXtcclxuXHRcdFx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX19hY3R1YWxfbGlzdGluZ19fc2hvdygpO1x0Ly9cdFNlbmRpbmcgQWpheCBSZXF1ZXN0XHQtXHR3aXRoIHBhcmFtZXRlcnMgdGhhdCAgd2UgZWFybHkgIGRlZmluZWQgaW4gXCJ3cGJjX2FqeF9ib29raW5nX2xpc3RpbmdcIiBPYmouXHJcblx0XHRcdFx0XHRcdH1cclxuXHJcblx0XHRcdFx0XHR9XHJcblxyXG5cdFx0XHRcdFx0Ly8gUmVtb3ZlIHNwaW4gaWNvbiBmcm9tICBidXR0b24gYW5kIEVuYWJsZSB0aGlzIGJ1dHRvbi5cclxuXHRcdFx0XHRcdHdwYmNfYnV0dG9uX19yZW1vdmVfc3BpbiggcmVzcG9uc2VfZGF0YVsgJ2FqeF9jbGVhbmVkX3BhcmFtcycgXVsgJ3VpX2NsaWNrZWRfZWxlbWVudF9pZCcgXSApXHJcblxyXG5cdFx0XHRcdFx0Ly8gSGlkZSBtb2RhbHNcclxuXHRcdFx0XHRcdHdwYmNfcG9wdXBfbW9kYWxzX19oaWRlKCk7XHJcblxyXG5cdFx0XHRcdFx0alF1ZXJ5KCAnI2FqYXhfcmVzcG9uZCcgKS5odG1sKCByZXNwb25zZV9kYXRhICk7XHRcdC8vIEZvciBhYmlsaXR5IHRvIHNob3cgcmVzcG9uc2UsIGFkZCBzdWNoIERJViBlbGVtZW50IHRvIHBhZ2VcclxuXHRcdFx0XHR9XHJcblx0XHRcdCAgKS5mYWlsKCBmdW5jdGlvbiAoIGpxWEhSLCB0ZXh0U3RhdHVzLCBlcnJvclRocm93biApIHsgICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdBamF4X0Vycm9yJywganFYSFIsIHRleHRTdGF0dXMsIGVycm9yVGhyb3duICk7IH1cclxuXHRcdFx0XHRcdGpRdWVyeSggJy53cGJjX2FqeF91bmRlcl90b29sYmFyX3JvdycgKS5oaWRlKCk7XHRcdFx0XHRcdFx0XHRcdFx0XHRcdFx0XHRcdC8vRml4SW46IDkuNi4xLjVcclxuXHRcdFx0XHRcdHZhciBlcnJvcl9tZXNzYWdlID0gJzxzdHJvbmc+JyArICdFcnJvciEnICsgJzwvc3Ryb25nPiAnICsgZXJyb3JUaHJvd24gO1xyXG5cdFx0XHRcdFx0aWYgKCBqcVhIUi5yZXNwb25zZVRleHQgKXtcclxuXHRcdFx0XHRcdFx0ZXJyb3JfbWVzc2FnZSArPSBqcVhIUi5yZXNwb25zZVRleHQ7XHJcblx0XHRcdFx0XHR9XHJcblx0XHRcdFx0XHRlcnJvcl9tZXNzYWdlID0gZXJyb3JfbWVzc2FnZS5yZXBsYWNlKCAvXFxuL2csIFwiPGJyIC8+XCIgKTtcclxuXHJcblx0XHRcdFx0XHR3cGJjX2FqeF9ib29raW5nX3Nob3dfbWVzc2FnZSggZXJyb3JfbWVzc2FnZSApO1xyXG5cdFx0XHQgIH0pXHJcblx0ICAgICAgICAgIC8vIC5kb25lKCAgIGZ1bmN0aW9uICggZGF0YSwgdGV4dFN0YXR1cywganFYSFIgKSB7ICAgaWYgKCB3aW5kb3cuY29uc29sZSAmJiB3aW5kb3cuY29uc29sZS5sb2cgKXsgY29uc29sZS5sb2coICdzZWNvbmQgc3VjY2VzcycsIGRhdGEsIHRleHRTdGF0dXMsIGpxWEhSICk7IH0gICAgfSlcclxuXHRcdFx0ICAvLyAuYWx3YXlzKCBmdW5jdGlvbiAoIGRhdGFfanFYSFIsIHRleHRTdGF0dXMsIGpxWEhSX2Vycm9yVGhyb3duICkgeyAgIGlmICggd2luZG93LmNvbnNvbGUgJiYgd2luZG93LmNvbnNvbGUubG9nICl7IGNvbnNvbGUubG9nKCAnYWx3YXlzIGZpbmlzaGVkJywgZGF0YV9qcVhIUiwgdGV4dFN0YXR1cywganFYSFJfZXJyb3JUaHJvd24gKTsgfSAgICAgfSlcclxuXHRcdFx0ICA7ICAvLyBFbmQgQWpheFxyXG59XHJcblxyXG5cclxuXHJcbi8qKlxyXG4gKiBIaWRlIGFsbCBvcGVuIG1vZGFsIHBvcHVwcyB3aW5kb3dzXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX3BvcHVwX21vZGFsc19faGlkZSgpe1xyXG5cclxuXHQvLyBIaWRlIG1vZGFsc1xyXG5cdGlmICggJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mIChqUXVlcnkoICcud3BiY19wb3B1cF9tb2RhbCcgKS53cGJjX215X21vZGFsKSApe1xyXG5cdFx0alF1ZXJ5KCAnLndwYmNfcG9wdXBfbW9kYWwnICkud3BiY19teV9tb2RhbCggJ2hpZGUnICk7XHJcblx0fVxyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqICAgRGF0ZXMgIFNob3J0IDwtPiBXaWRlICAgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9jbGlja19vbl9kYXRlc19zaG9ydCgpe1xyXG5cdGpRdWVyeSggJyNib29raW5nX2RhdGVzX3NtYWxsLC5ib29raW5nX2RhdGVzX2Z1bGwnICkuaGlkZSgpO1xyXG5cdGpRdWVyeSggJyNib29raW5nX2RhdGVzX2Z1bGwsLmJvb2tpbmdfZGF0ZXNfc21hbGwnICkuc2hvdygpO1xyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfc2VuZF9zZWFyY2hfcmVxdWVzdF93aXRoX3BhcmFtcyggeyd1aV91c3JfX2RhdGVzX3Nob3J0X3dpZGUnOiAnc2hvcnQnfSApO1xyXG59XHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9jbGlja19vbl9kYXRlc193aWRlKCl7XHJcblx0alF1ZXJ5KCAnI2Jvb2tpbmdfZGF0ZXNfZnVsbCwuYm9va2luZ19kYXRlc19zbWFsbCcgKS5oaWRlKCk7XHJcblx0alF1ZXJ5KCAnI2Jvb2tpbmdfZGF0ZXNfc21hbGwsLmJvb2tpbmdfZGF0ZXNfZnVsbCcgKS5zaG93KCk7XHJcblx0d3BiY19hanhfYm9va2luZ19zZW5kX3NlYXJjaF9yZXF1ZXN0X3dpdGhfcGFyYW1zKCB7J3VpX3Vzcl9fZGF0ZXNfc2hvcnRfd2lkZSc6ICd3aWRlJ30gKTtcclxufVxyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfY2xpY2tfb25fZGF0ZXNfdG9nZ2xlKHRoaXNfZGF0ZSl7XHJcblxyXG5cdGpRdWVyeSggdGhpc19kYXRlICkucGFyZW50cyggJy53cGJjX2NvbF9kYXRlcycgKS5maW5kKCAnLmJvb2tpbmdfZGF0ZXNfc21hbGwnICkudG9nZ2xlKCk7XHJcblx0alF1ZXJ5KCB0aGlzX2RhdGUgKS5wYXJlbnRzKCAnLndwYmNfY29sX2RhdGVzJyApLmZpbmQoICcuYm9va2luZ19kYXRlc19mdWxsJyApLnRvZ2dsZSgpO1xyXG5cclxuXHQvKlxyXG5cdHZhciB2aXNpYmxlX3NlY3Rpb24gPSBqUXVlcnkoIHRoaXNfZGF0ZSApLnBhcmVudHMoICcuYm9va2luZ19kYXRlc19leHBhbmRfc2VjdGlvbicgKTtcclxuXHR2aXNpYmxlX3NlY3Rpb24uaGlkZSgpO1xyXG5cdGlmICggdmlzaWJsZV9zZWN0aW9uLmhhc0NsYXNzKCAnYm9va2luZ19kYXRlc19mdWxsJyApICl7XHJcblx0XHR2aXNpYmxlX3NlY3Rpb24ucGFyZW50cyggJy53cGJjX2NvbF9kYXRlcycgKS5maW5kKCAnLmJvb2tpbmdfZGF0ZXNfc21hbGwnICkuc2hvdygpO1xyXG5cdH0gZWxzZSB7XHJcblx0XHR2aXNpYmxlX3NlY3Rpb24ucGFyZW50cyggJy53cGJjX2NvbF9kYXRlcycgKS5maW5kKCAnLmJvb2tpbmdfZGF0ZXNfZnVsbCcgKS5zaG93KCk7XHJcblx0fSovXHJcblx0Y29uc29sZS5sb2coICd3cGJjX2FqeF9jbGlja19vbl9kYXRlc190b2dnbGUnLCB0aGlzX2RhdGUgKTtcclxufVxyXG5cclxuLyoqXHJcbiAqICAgTG9jYWxlICAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogXHRTZWxlY3Qgb3B0aW9ucyBpbiBzZWxlY3QgYm94ZXMgYmFzZWQgb24gYXR0cmlidXRlIFwidmFsdWVfb2Zfc2VsZWN0ZWRfb3B0aW9uXCIgYW5kIFJFRCBjb2xvciBhbmQgaGludCBmb3IgTE9DQUxFIGJ1dHRvbiAgIC0tICBJdCdzIGNhbGxlZCBmcm9tIFx0d3BiY19hanhfYm9va2luZ19kZWZpbmVfdWlfaG9va3MoKSAgXHRlYWNoICB0aW1lIGFmdGVyIExpc3RpbmcgbG9hZGluZy5cclxuICovXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2RlZmluZV9fbG9jYWxlKCl7XHJcblxyXG5cdGpRdWVyeSggJy53cGJjX2xpc3RpbmdfY29udGFpbmVyIHNlbGVjdCcgKS5lYWNoKCBmdW5jdGlvbiAoIGluZGV4ICl7XHJcblxyXG5cdFx0dmFyIHNlbGVjdGlvbiA9IGpRdWVyeSggdGhpcyApLmF0dHIoIFwidmFsdWVfb2Zfc2VsZWN0ZWRfb3B0aW9uXCIgKTtcdFx0XHQvLyBEZWZpbmUgc2VsZWN0ZWQgc2VsZWN0IGJveGVzXHJcblxyXG5cdFx0aWYgKCB1bmRlZmluZWQgIT09IHNlbGVjdGlvbiApe1xyXG5cdFx0XHRqUXVlcnkoIHRoaXMgKS5maW5kKCAnb3B0aW9uW3ZhbHVlPVwiJyArIHNlbGVjdGlvbiArICdcIl0nICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApO1xyXG5cclxuXHRcdFx0aWYgKCAoJycgIT0gc2VsZWN0aW9uKSAmJiAoalF1ZXJ5KCB0aGlzICkuaGFzQ2xhc3MoICdzZXRfYm9va2luZ19sb2NhbGVfc2VsZWN0Ym94JyApKSApe1x0XHRcdFx0XHRcdFx0XHQvLyBMb2NhbGVcclxuXHJcblx0XHRcdFx0dmFyIGJvb2tpbmdfbG9jYWxlX2J1dHRvbiA9IGpRdWVyeSggdGhpcyApLnBhcmVudHMoICcudWlfZWxlbWVudF9sb2NhbGUnICkuZmluZCggJy5zZXRfYm9va2luZ19sb2NhbGVfYnV0dG9uJyApXHJcblxyXG5cdFx0XHRcdC8vYm9va2luZ19sb2NhbGVfYnV0dG9uLmNzcyggJ2NvbG9yJywgJyNkYjQ4MDAnICk7XHRcdC8vIFNldCBidXR0b24gIHJlZFxyXG5cdFx0XHRcdGJvb2tpbmdfbG9jYWxlX2J1dHRvbi5hZGRDbGFzcyggJ3dwYmNfdWlfcmVkJyApO1x0XHQvLyBTZXQgYnV0dG9uICByZWRcclxuXHRcdFx0XHQgaWYgKCAnZnVuY3Rpb24nID09PSB0eXBlb2YoIHdwYmNfdGlwcHkgKSApe1xyXG5cdFx0XHRcdFx0Ym9va2luZ19sb2NhbGVfYnV0dG9uLmdldCgwKS5fdGlwcHkuc2V0Q29udGVudCggc2VsZWN0aW9uICk7XHJcblx0XHRcdFx0IH1cclxuXHRcdFx0fVxyXG5cdFx0fVxyXG5cdH0gKTtcclxufVxyXG5cclxuLyoqXHJcbiAqICAgUmVtYXJrICAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG4vKipcclxuICogRGVmaW5lIGNvbnRlbnQgb2YgcmVtYXJrIFwiYm9va2luZyBub3RlXCIgYnV0dG9uIGFuZCB0ZXh0YXJlYS4gIC0tIEl0J3MgY2FsbGVkIGZyb20gXHR3cGJjX2FqeF9ib29raW5nX2RlZmluZV91aV9ob29rcygpICBcdGVhY2ggIHRpbWUgYWZ0ZXIgTGlzdGluZyBsb2FkaW5nLlxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfZGVmaW5lX19yZW1hcmsoKXtcclxuXHJcblx0alF1ZXJ5KCAnLndwYmNfbGlzdGluZ19jb250YWluZXIgLnVpX3JlbWFya19zZWN0aW9uIHRleHRhcmVhJyApLmVhY2goIGZ1bmN0aW9uICggaW5kZXggKXtcclxuXHRcdHZhciB0ZXh0X3ZhbCA9IGpRdWVyeSggdGhpcyApLnZhbCgpO1xyXG5cdFx0aWYgKCAodW5kZWZpbmVkICE9PSB0ZXh0X3ZhbCkgJiYgKCcnICE9IHRleHRfdmFsKSApe1xyXG5cclxuXHRcdFx0dmFyIHJlbWFya19idXR0b24gPSBqUXVlcnkoIHRoaXMgKS5wYXJlbnRzKCAnLnVpX2dyb3VwJyApLmZpbmQoICcuc2V0X2Jvb2tpbmdfbm90ZV9idXR0b24nICk7XHJcblxyXG5cdFx0XHRpZiAoIHJlbWFya19idXR0b24ubGVuZ3RoID4gMCApe1xyXG5cclxuXHRcdFx0XHRyZW1hcmtfYnV0dG9uLmFkZENsYXNzKCAnd3BiY191aV9yZWQnICk7XHRcdC8vIFNldCBidXR0b24gIHJlZFxyXG5cdFx0XHRcdGlmICggJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mICh3cGJjX3RpcHB5KSApe1xyXG5cdFx0XHRcdFx0Ly9yZW1hcmtfYnV0dG9uLmdldCggMCApLl90aXBweS5hbGxvd0hUTUwgPSB0cnVlO1xyXG5cdFx0XHRcdFx0Ly9yZW1hcmtfYnV0dG9uLmdldCggMCApLl90aXBweS5zZXRDb250ZW50KCB0ZXh0X3ZhbC5yZXBsYWNlKC9bXFxuXFxyXS9nLCAnPGJyPicpICk7XHJcblxyXG5cdFx0XHRcdFx0cmVtYXJrX2J1dHRvbi5nZXQoIDAgKS5fdGlwcHkuc2V0UHJvcHMoIHtcclxuXHRcdFx0XHRcdFx0YWxsb3dIVE1MOiB0cnVlLFxyXG5cdFx0XHRcdFx0XHRjb250ZW50ICA6IHRleHRfdmFsLnJlcGxhY2UoIC9bXFxuXFxyXS9nLCAnPGJyPicgKVxyXG5cdFx0XHRcdFx0fSApO1xyXG5cdFx0XHRcdH1cclxuXHRcdFx0fVxyXG5cdFx0fVxyXG5cdH0gKTtcclxufVxyXG5cclxuLyoqXHJcbiAqIEFjdGlvbnMgLHdoZW4gd2UgY2xpY2sgb24gXCJSZW1hcmtcIiBidXR0b24uXHJcbiAqXHJcbiAqIEBwYXJhbSBqcV9idXR0b24gIC1cdHRoaXMgalF1ZXJ5IGJ1dHRvbiAgb2JqZWN0XHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19fcmVtYXJrKCBqcV9idXR0b24gKXtcclxuXHJcblx0anFfYnV0dG9uLnBhcmVudHMoJy51aV9ncm91cCcpLmZpbmQoJy51aV9yZW1hcmtfc2VjdGlvbicpLnRvZ2dsZSgpO1xyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqICAgQ2hhbmdlIGJvb2tpbmcgcmVzb3VyY2UgICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zaG93X19jaGFuZ2VfcmVzb3VyY2UoIGJvb2tpbmdfaWQsIHJlc291cmNlX2lkICl7XHJcblxyXG5cdC8vIERlZmluZSBJRCBvZiBib29raW5nIHRvIGhpZGRlbiBpbnB1dFxyXG5cdGpRdWVyeSggJyNjaGFuZ2VfYm9va2luZ19yZXNvdXJjZV9fYm9va2luZ19pZCcgKS52YWwoIGJvb2tpbmdfaWQgKTtcclxuXHJcblx0Ly8gU2VsZWN0IGJvb2tpbmcgcmVzb3VyY2UgIHRoYXQgYmVsb25nIHRvICBib29raW5nXHJcblx0alF1ZXJ5KCAnI2NoYW5nZV9ib29raW5nX3Jlc291cmNlX19yZXNvdXJjZV9zZWxlY3QnICkudmFsKCByZXNvdXJjZV9pZCApLnRyaWdnZXIoICdjaGFuZ2UnICk7XHJcblx0dmFyIGNicjtcclxuXHJcblx0Ly8gR2V0IFJlc291cmNlIHNlY3Rpb25cclxuXHRjYnIgPSBqUXVlcnkoIFwiI2NoYW5nZV9ib29raW5nX3Jlc291cmNlX19zZWN0aW9uXCIgKS5kZXRhY2goKTtcclxuXHJcblx0Ly8gQXBwZW5kIGl0IHRvIGJvb2tpbmcgUk9XXHJcblx0Y2JyLmFwcGVuZFRvKCBqUXVlcnkoIFwiI3VpX19jaGFuZ2VfYm9va2luZ19yZXNvdXJjZV9fc2VjdGlvbl9pbl9ib29raW5nX1wiICsgYm9va2luZ19pZCApICk7XHJcblx0Y2JyID0gbnVsbDtcclxuXHJcblx0Ly8gSGlkZSBzZWN0aW9ucyBvZiBcIkNoYW5nZSBib29raW5nIHJlc291cmNlXCIgaW4gYWxsIG90aGVyIGJvb2tpbmdzIFJPV3NcclxuXHQvL2pRdWVyeSggXCIudWlfX2NoYW5nZV9ib29raW5nX3Jlc291cmNlX19zZWN0aW9uX2luX2Jvb2tpbmdcIiApLmhpZGUoKTtcclxuXHRpZiAoICEgalF1ZXJ5KCBcIiN1aV9fY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfX3NlY3Rpb25faW5fYm9va2luZ19cIiArIGJvb2tpbmdfaWQgKS5pcygnOnZpc2libGUnKSApe1xyXG5cdFx0alF1ZXJ5KCBcIi51aV9fdW5kZXJfYWN0aW9uc19yb3dfX3NlY3Rpb25faW5fYm9va2luZ1wiICkuaGlkZSgpO1xyXG5cdH1cclxuXHJcblx0Ly8gU2hvdyBvbmx5IFwiY2hhbmdlIGJvb2tpbmcgcmVzb3VyY2VcIiBzZWN0aW9uICBmb3IgY3VycmVudCBib29raW5nXHJcblx0alF1ZXJ5KCBcIiN1aV9fY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfX3NlY3Rpb25faW5fYm9va2luZ19cIiArIGJvb2tpbmdfaWQgKS50b2dnbGUoKTtcclxufVxyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfc2F2ZV9fY2hhbmdlX3Jlc291cmNlKCB0aGlzX2VsLCBib29raW5nX2FjdGlvbiwgZWxfaWQgKXtcclxuXHJcblx0d3BiY19hanhfYm9va2luZ19hamF4X2FjdGlvbl9yZXF1ZXN0KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19hY3Rpb24nICAgICAgIDogYm9va2luZ19hY3Rpb24sXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19pZCcgICAgICAgICAgIDogalF1ZXJ5KCAnI2NoYW5nZV9ib29raW5nX3Jlc291cmNlX19ib29raW5nX2lkJyApLnZhbCgpLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlbGVjdGVkX3Jlc291cmNlX2lkJyA6IGpRdWVyeSggJyNjaGFuZ2VfYm9va2luZ19yZXNvdXJjZV9fcmVzb3VyY2Vfc2VsZWN0JyApLnZhbCgpLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3VpX2NsaWNrZWRfZWxlbWVudF9pZCc6IGVsX2lkXHJcblx0fSApO1xyXG5cclxuXHR3cGJjX2J1dHRvbl9lbmFibGVfbG9hZGluZ19pY29uKCB0aGlzX2VsICk7XHJcblxyXG5cdC8vIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX2Nsb3NlX19jaGFuZ2VfcmVzb3VyY2UoKTtcclxufVxyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfY2xvc2VfX2NoYW5nZV9yZXNvdXJjZSgpe1xyXG5cclxuXHR2YXIgY2JyY2U7XHJcblxyXG5cdC8vIEdldCBSZXNvdXJjZSBzZWN0aW9uXHJcblx0Y2JyY2UgPSBqUXVlcnkoXCIjY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfX3NlY3Rpb25cIikuZGV0YWNoKCk7XHJcblxyXG5cdC8vIEFwcGVuZCBpdCB0byBoaWRkZW4gSFRNTCB0ZW1wbGF0ZSBzZWN0aW9uICBhdCAgdGhlIGJvdHRvbSAgb2YgdGhlIHBhZ2VcclxuXHRjYnJjZS5hcHBlbmRUbyhqUXVlcnkoXCIjd3BiY19oaWRkZW5fdGVtcGxhdGVfX2NoYW5nZV9ib29raW5nX3Jlc291cmNlXCIpKTtcclxuXHRjYnJjZSA9IG51bGw7XHJcblxyXG5cdC8vIEhpZGUgYWxsIGNoYW5nZSBib29raW5nIHJlc291cmNlcyBzZWN0aW9uc1xyXG5cdGpRdWVyeShcIi51aV9fY2hhbmdlX2Jvb2tpbmdfcmVzb3VyY2VfX3NlY3Rpb25faW5fYm9va2luZ1wiKS5oaWRlKCk7XHJcbn1cclxuXHJcbi8qKlxyXG4gKiAgIER1cGxpY2F0ZSBib29raW5nIGluIG90aGVyIHJlc291cmNlICAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfc2hvd19fZHVwbGljYXRlX2Jvb2tpbmcoIGJvb2tpbmdfaWQsIHJlc291cmNlX2lkICl7XHJcblxyXG5cdC8vIERlZmluZSBJRCBvZiBib29raW5nIHRvIGhpZGRlbiBpbnB1dFxyXG5cdGpRdWVyeSggJyNkdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZV9fYm9va2luZ19pZCcgKS52YWwoIGJvb2tpbmdfaWQgKTtcclxuXHJcblx0Ly8gU2VsZWN0IGJvb2tpbmcgcmVzb3VyY2UgIHRoYXQgYmVsb25nIHRvICBib29raW5nXHJcblx0alF1ZXJ5KCAnI2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19yZXNvdXJjZV9zZWxlY3QnICkudmFsKCByZXNvdXJjZV9pZCApLnRyaWdnZXIoICdjaGFuZ2UnICk7XHJcblx0dmFyIGNicjtcclxuXHJcblx0Ly8gR2V0IFJlc291cmNlIHNlY3Rpb25cclxuXHRjYnIgPSBqUXVlcnkoIFwiI2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19zZWN0aW9uXCIgKS5kZXRhY2goKTtcclxuXHJcblx0Ly8gQXBwZW5kIGl0IHRvIGJvb2tpbmcgUk9XXHJcblx0Y2JyLmFwcGVuZFRvKCBqUXVlcnkoIFwiI3VpX19kdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZV9fc2VjdGlvbl9pbl9ib29raW5nX1wiICsgYm9va2luZ19pZCApICk7XHJcblx0Y2JyID0gbnVsbDtcclxuXHJcblx0Ly8gSGlkZSBzZWN0aW9ucyBvZiBcIkR1cGxpY2F0ZSBib29raW5nXCIgaW4gYWxsIG90aGVyIGJvb2tpbmdzIFJPV3NcclxuXHRpZiAoICEgalF1ZXJ5KCBcIiN1aV9fZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VfX3NlY3Rpb25faW5fYm9va2luZ19cIiArIGJvb2tpbmdfaWQgKS5pcygnOnZpc2libGUnKSApe1xyXG5cdFx0alF1ZXJ5KCBcIi51aV9fdW5kZXJfYWN0aW9uc19yb3dfX3NlY3Rpb25faW5fYm9va2luZ1wiICkuaGlkZSgpO1xyXG5cdH1cclxuXHJcblx0Ly8gU2hvdyBvbmx5IFwiRHVwbGljYXRlIGJvb2tpbmdcIiBzZWN0aW9uICBmb3IgY3VycmVudCBib29raW5nIFJPV1xyXG5cdGpRdWVyeSggXCIjdWlfX2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19zZWN0aW9uX2luX2Jvb2tpbmdfXCIgKyBib29raW5nX2lkICkudG9nZ2xlKCk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX3NhdmVfX2R1cGxpY2F0ZV9ib29raW5nKCB0aGlzX2VsLCBib29raW5nX2FjdGlvbiwgZWxfaWQgKXtcclxuXHJcblx0d3BiY19hanhfYm9va2luZ19hamF4X2FjdGlvbl9yZXF1ZXN0KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19hY3Rpb24nICAgICAgIDogYm9va2luZ19hY3Rpb24sXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19pZCcgICAgICAgICAgIDogalF1ZXJ5KCAnI2R1cGxpY2F0ZV9ib29raW5nX3RvX290aGVyX3Jlc291cmNlX19ib29raW5nX2lkJyApLnZhbCgpLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlbGVjdGVkX3Jlc291cmNlX2lkJyA6IGpRdWVyeSggJyNkdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZV9fcmVzb3VyY2Vfc2VsZWN0JyApLnZhbCgpLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3VpX2NsaWNrZWRfZWxlbWVudF9pZCc6IGVsX2lkXHJcblx0fSApO1xyXG5cclxuXHR3cGJjX2J1dHRvbl9lbmFibGVfbG9hZGluZ19pY29uKCB0aGlzX2VsICk7XHJcblxyXG5cdC8vIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX2Nsb3NlX19jaGFuZ2VfcmVzb3VyY2UoKTtcclxufVxyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfY2xvc2VfX2R1cGxpY2F0ZV9ib29raW5nKCl7XHJcblxyXG5cdHZhciBjYnJjZTtcclxuXHJcblx0Ly8gR2V0IFJlc291cmNlIHNlY3Rpb25cclxuXHRjYnJjZSA9IGpRdWVyeShcIiNkdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZV9fc2VjdGlvblwiKS5kZXRhY2goKTtcclxuXHJcblx0Ly8gQXBwZW5kIGl0IHRvIGhpZGRlbiBIVE1MIHRlbXBsYXRlIHNlY3Rpb24gIGF0ICB0aGUgYm90dG9tICBvZiB0aGUgcGFnZVxyXG5cdGNicmNlLmFwcGVuZFRvKGpRdWVyeShcIiN3cGJjX2hpZGRlbl90ZW1wbGF0ZV9fZHVwbGljYXRlX2Jvb2tpbmdfdG9fb3RoZXJfcmVzb3VyY2VcIikpO1xyXG5cdGNicmNlID0gbnVsbDtcclxuXHJcblx0Ly8gSGlkZSBhbGwgY2hhbmdlIGJvb2tpbmcgcmVzb3VyY2VzIHNlY3Rpb25zXHJcblx0alF1ZXJ5KFwiLnVpX19kdXBsaWNhdGVfYm9va2luZ190b19vdGhlcl9yZXNvdXJjZV9fc2VjdGlvbl9pbl9ib29raW5nXCIpLmhpZGUoKTtcclxufVxyXG5cclxuLyoqXHJcbiAqICAgQ2hhbmdlIHBheW1lbnQgc3RhdHVzICAgLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zaG93X19zZXRfcGF5bWVudF9zdGF0dXMoIGJvb2tpbmdfaWQgKXtcclxuXHJcblx0dmFyIGpTZWxlY3QgPSBqUXVlcnkoICcjdWlfX3NldF9wYXltZW50X3N0YXR1c19fc2VjdGlvbl9pbl9ib29raW5nXycgKyBib29raW5nX2lkICkuZmluZCggJ3NlbGVjdCcgKVxyXG5cclxuXHR2YXIgc2VsZWN0ZWRfcGF5X3N0YXR1cyA9IGpTZWxlY3QuYXR0ciggXCJhangtc2VsZWN0ZWQtdmFsdWVcIiApO1xyXG5cclxuXHQvLyBJcyBpdCBmbG9hdCAtIHRoZW4gIGl0J3MgdW5rbm93blxyXG5cdGlmICggIWlzTmFOKCBwYXJzZUZsb2F0KCBzZWxlY3RlZF9wYXlfc3RhdHVzICkgKSApe1xyXG5cdFx0alNlbGVjdC5maW5kKCAnb3B0aW9uW3ZhbHVlPVwiMVwiXScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICk7XHRcdFx0XHRcdFx0XHRcdC8vIFVua25vd24gIHZhbHVlIGlzICcxJyBpbiBzZWxlY3QgYm94XHJcblx0fSBlbHNlIHtcclxuXHRcdGpTZWxlY3QuZmluZCggJ29wdGlvblt2YWx1ZT1cIicgKyBzZWxlY3RlZF9wYXlfc3RhdHVzICsgJ1wiXScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICk7XHRcdC8vIE90aGVyd2lzZSBrbm93biBwYXltZW50IHN0YXR1c1xyXG5cdH1cclxuXHJcblx0Ly8gSGlkZSBzZWN0aW9ucyBvZiBcIkNoYW5nZSBib29raW5nIHJlc291cmNlXCIgaW4gYWxsIG90aGVyIGJvb2tpbmdzIFJPV3NcclxuXHRpZiAoICEgalF1ZXJ5KCBcIiN1aV9fc2V0X3BheW1lbnRfc3RhdHVzX19zZWN0aW9uX2luX2Jvb2tpbmdfXCIgKyBib29raW5nX2lkICkuaXMoJzp2aXNpYmxlJykgKXtcclxuXHRcdGpRdWVyeSggXCIudWlfX3VuZGVyX2FjdGlvbnNfcm93X19zZWN0aW9uX2luX2Jvb2tpbmdcIiApLmhpZGUoKTtcclxuXHR9XHJcblxyXG5cdC8vIFNob3cgb25seSBcImNoYW5nZSBib29raW5nIHJlc291cmNlXCIgc2VjdGlvbiAgZm9yIGN1cnJlbnQgYm9va2luZ1xyXG5cdGpRdWVyeSggXCIjdWlfX3NldF9wYXltZW50X3N0YXR1c19fc2VjdGlvbl9pbl9ib29raW5nX1wiICsgYm9va2luZ19pZCApLnRvZ2dsZSgpO1xyXG59XHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zYXZlX19zZXRfcGF5bWVudF9zdGF0dXMoIGJvb2tpbmdfaWQsIHRoaXNfZWwsIGJvb2tpbmdfYWN0aW9uLCBlbF9pZCApe1xyXG5cclxuXHR3cGJjX2FqeF9ib29raW5nX2FqYXhfYWN0aW9uX3JlcXVlc3QoIHtcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2FjdGlvbicgICAgICAgOiBib29raW5nX2FjdGlvbixcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2lkJyAgICAgICAgICAgOiBib29raW5nX2lkLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3NlbGVjdGVkX3BheW1lbnRfc3RhdHVzJyA6IGpRdWVyeSggJyN1aV9idG5fc2V0X3BheW1lbnRfc3RhdHVzJyArIGJvb2tpbmdfaWQgKS52YWwoKSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCd1aV9jbGlja2VkX2VsZW1lbnRfaWQnOiBlbF9pZCArICdfc2F2ZSdcclxuXHR9ICk7XHJcblxyXG5cdHdwYmNfYnV0dG9uX2VuYWJsZV9sb2FkaW5nX2ljb24oIHRoaXNfZWwgKTtcclxuXHJcblx0alF1ZXJ5KCAnIycgKyBlbF9pZCArICdfY2FuY2VsJykuaGlkZSgpO1xyXG5cdC8vd3BiY19idXR0b25fZW5hYmxlX2xvYWRpbmdfaWNvbiggalF1ZXJ5KCAnIycgKyBlbF9pZCArICdfY2FuY2VsJykuZ2V0KDApICk7XHJcblxyXG59XHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19jbG9zZV9fc2V0X3BheW1lbnRfc3RhdHVzKCl7XHJcblx0Ly8gSGlkZSBhbGwgY2hhbmdlICBwYXltZW50IHN0YXR1cyBmb3IgYm9va2luZ1xyXG5cdGpRdWVyeShcIi51aV9fc2V0X3BheW1lbnRfc3RhdHVzX19zZWN0aW9uX2luX2Jvb2tpbmdcIikuaGlkZSgpO1xyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqICAgQ2hhbmdlIGJvb2tpbmcgY29zdCAgIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcblxyXG5mdW5jdGlvbiB3cGJjX2FqeF9ib29raW5nX191aV9jbGlja19zYXZlX19zZXRfYm9va2luZ19jb3N0KCBib29raW5nX2lkLCB0aGlzX2VsLCBib29raW5nX2FjdGlvbiwgZWxfaWQgKXtcclxuXHJcblx0d3BiY19hanhfYm9va2luZ19hamF4X2FjdGlvbl9yZXF1ZXN0KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19hY3Rpb24nICAgICAgIDogYm9va2luZ19hY3Rpb24sXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19pZCcgICAgICAgICAgIDogYm9va2luZ19pZCxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdib29raW5nX2Nvc3QnIFx0XHQgICA6IGpRdWVyeSggJyN1aV9idG5fc2V0X2Jvb2tpbmdfY29zdCcgKyBib29raW5nX2lkICsgJ19jb3N0JykudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQndWlfY2xpY2tlZF9lbGVtZW50X2lkJzogZWxfaWQgKyAnX3NhdmUnXHJcblx0fSApO1xyXG5cclxuXHR3cGJjX2J1dHRvbl9lbmFibGVfbG9hZGluZ19pY29uKCB0aGlzX2VsICk7XHJcblxyXG5cdGpRdWVyeSggJyMnICsgZWxfaWQgKyAnX2NhbmNlbCcpLmhpZGUoKTtcclxuXHQvL3dwYmNfYnV0dG9uX2VuYWJsZV9sb2FkaW5nX2ljb24oIGpRdWVyeSggJyMnICsgZWxfaWQgKyAnX2NhbmNlbCcpLmdldCgwKSApO1xyXG5cclxufVxyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfY2xvc2VfX3NldF9ib29raW5nX2Nvc3QoKXtcclxuXHQvLyBIaWRlIGFsbCBjaGFuZ2UgIHBheW1lbnQgc3RhdHVzIGZvciBib29raW5nXHJcblx0alF1ZXJ5KFwiLnVpX19zZXRfYm9va2luZ19jb3N0X19zZWN0aW9uX2luX2Jvb2tpbmdcIikuaGlkZSgpO1xyXG59XHJcblxyXG5cclxuLyoqXHJcbiAqICAgU2VuZCBQYXltZW50IHJlcXVlc3QgICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG5cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfX3NlbmRfcGF5bWVudF9yZXF1ZXN0KCl7XHJcblxyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfYWpheF9hY3Rpb25fcmVxdWVzdCgge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Jvb2tpbmdfYWN0aW9uJyAgICAgICA6ICdzZW5kX3BheW1lbnRfcmVxdWVzdCcsXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19pZCcgICAgICAgICAgIDogalF1ZXJ5KCAnI3dwYmNfbW9kYWxfX3BheW1lbnRfcmVxdWVzdF9fYm9va2luZ19pZCcpLnZhbCgpLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3JlYXNvbl9vZl9hY3Rpb24nIFx0ICAgOiBqUXVlcnkoICcjd3BiY19tb2RhbF9fcGF5bWVudF9yZXF1ZXN0X19yZWFzb25fb2ZfYWN0aW9uJykudmFsKCksXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQndWlfY2xpY2tlZF9lbGVtZW50X2lkJzogJ3dwYmNfbW9kYWxfX3BheW1lbnRfcmVxdWVzdF9fYnV0dG9uX3NlbmQnXHJcblx0fSApO1xyXG5cdHdwYmNfYnV0dG9uX2VuYWJsZV9sb2FkaW5nX2ljb24oIGpRdWVyeSggJyN3cGJjX21vZGFsX19wYXltZW50X3JlcXVlc3RfX2J1dHRvbl9zZW5kJyApLmdldCggMCApICk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBJbXBvcnQgR29vZ2xlIENhbGVuZGFyICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbmZ1bmN0aW9uIHdwYmNfYWp4X2Jvb2tpbmdfX3VpX2NsaWNrX19pbXBvcnRfZ29vZ2xlX2NhbGVuZGFyKCl7XHJcblxyXG5cdHdwYmNfYWp4X2Jvb2tpbmdfYWpheF9hY3Rpb25fcmVxdWVzdCgge1xyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Jvb2tpbmdfYWN0aW9uJyAgICAgICA6ICdpbXBvcnRfZ29vZ2xlX2NhbGVuZGFyJyxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCd1aV9jbGlja2VkX2VsZW1lbnRfaWQnOiAnd3BiY19tb2RhbF9faW1wb3J0X2dvb2dsZV9jYWxlbmRhcl9fYnV0dG9uX3NlbmQnXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCAnYm9va2luZ19nY2FsX2V2ZW50c19mcm9tJyA6IFx0XHRcdFx0alF1ZXJ5KCAnI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX3NlY3Rpb24gI2Jvb2tpbmdfZ2NhbF9ldmVudHNfZnJvbSBvcHRpb246c2VsZWN0ZWQnKS52YWwoKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCAnYm9va2luZ19nY2FsX2V2ZW50c19mcm9tX29mZnNldCcgOiBcdFx0alF1ZXJ5KCAnI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX3NlY3Rpb24gI2Jvb2tpbmdfZ2NhbF9ldmVudHNfZnJvbV9vZmZzZXQnICkudmFsKClcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgJ2Jvb2tpbmdfZ2NhbF9ldmVudHNfZnJvbV9vZmZzZXRfdHlwZScgOiBcdGpRdWVyeSggJyN3cGJjX21vZGFsX19pbXBvcnRfZ29vZ2xlX2NhbGVuZGFyX19zZWN0aW9uICNib29raW5nX2djYWxfZXZlbnRzX2Zyb21fb2Zmc2V0X3R5cGUgb3B0aW9uOnNlbGVjdGVkJykudmFsKClcclxuXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsICdib29raW5nX2djYWxfZXZlbnRzX3VudGlsJyA6IFx0XHRcdGpRdWVyeSggJyN3cGJjX21vZGFsX19pbXBvcnRfZ29vZ2xlX2NhbGVuZGFyX19zZWN0aW9uICNib29raW5nX2djYWxfZXZlbnRzX3VudGlsIG9wdGlvbjpzZWxlY3RlZCcpLnZhbCgpXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsICdib29raW5nX2djYWxfZXZlbnRzX3VudGlsX29mZnNldCcgOiBcdFx0alF1ZXJ5KCAnI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX3NlY3Rpb24gI2Jvb2tpbmdfZ2NhbF9ldmVudHNfdW50aWxfb2Zmc2V0JyApLnZhbCgpXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQsICdib29raW5nX2djYWxfZXZlbnRzX3VudGlsX29mZnNldF90eXBlJyA6IGpRdWVyeSggJyN3cGJjX21vZGFsX19pbXBvcnRfZ29vZ2xlX2NhbGVuZGFyX19zZWN0aW9uICNib29raW5nX2djYWxfZXZlbnRzX3VudGlsX29mZnNldF90eXBlIG9wdGlvbjpzZWxlY3RlZCcpLnZhbCgpXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0LCAnYm9va2luZ19nY2FsX2V2ZW50c19tYXgnIDogXHRqUXVlcnkoICcjd3BiY19tb2RhbF9faW1wb3J0X2dvb2dsZV9jYWxlbmRhcl9fc2VjdGlvbiAjYm9va2luZ19nY2FsX2V2ZW50c19tYXgnICkudmFsKClcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCwgJ2Jvb2tpbmdfZ2NhbF9yZXNvdXJjZScgOiBcdGpRdWVyeSggJyN3cGJjX21vZGFsX19pbXBvcnRfZ29vZ2xlX2NhbGVuZGFyX19zZWN0aW9uICN3cGJjX2Jvb2tpbmdfcmVzb3VyY2Ugb3B0aW9uOnNlbGVjdGVkJykudmFsKClcclxuXHR9ICk7XHJcblx0d3BiY19idXR0b25fZW5hYmxlX2xvYWRpbmdfaWNvbiggalF1ZXJ5KCAnI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX3NlY3Rpb24gI3dwYmNfbW9kYWxfX2ltcG9ydF9nb29nbGVfY2FsZW5kYXJfX2J1dHRvbl9zZW5kJyApLmdldCggMCApICk7XHJcbn1cclxuXHJcblxyXG4vKipcclxuICogICBFeHBvcnQgYm9va2luZ3MgdG8gQ1NWICAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fdWlfY2xpY2tfX2V4cG9ydF9jc3YoIHBhcmFtcyApe1xyXG5cclxuXHR2YXIgc2VsZWN0ZWRfYm9va2luZ19pZF9hcnIgPSB3cGJjX2dldF9zZWxlY3RlZF9yb3dfaWQoKTtcclxuXHJcblx0d3BiY19hanhfYm9va2luZ19hamF4X2FjdGlvbl9yZXF1ZXN0KCB7XHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnYm9va2luZ19hY3Rpb24nICAgICAgICA6IHBhcmFtc1sgJ2Jvb2tpbmdfYWN0aW9uJyBdLFxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J3VpX2NsaWNrZWRfZWxlbWVudF9pZCcgOiBwYXJhbXNbICd1aV9jbGlja2VkX2VsZW1lbnRfaWQnIF0sXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2V4cG9ydF90eXBlJyAgICAgICAgICAgOiBwYXJhbXNbICdleHBvcnRfdHlwZScgXSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdjc3ZfZXhwb3J0X3NlcGFyYXRvcicgIDogcGFyYW1zWyAnY3N2X2V4cG9ydF9zZXBhcmF0b3InIF0sXHJcblx0XHRcdFx0XHRcdFx0XHRcdFx0XHQnY3N2X2V4cG9ydF9za2lwX2ZpZWxkcyc6IHBhcmFtc1sgJ2Nzdl9leHBvcnRfc2tpcF9maWVsZHMnIF0sXHJcblxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdFx0J2Jvb2tpbmdfaWQnXHQ6IHNlbGVjdGVkX2Jvb2tpbmdfaWRfYXJyLmpvaW4oJywnKSxcclxuXHRcdFx0XHRcdFx0XHRcdFx0XHRcdCdzZWFyY2hfcGFyYW1zJyA6IHdwYmNfYWp4X2Jvb2tpbmdfbGlzdGluZy5zZWFyY2hfZ2V0X2FsbF9wYXJhbXMoKVxyXG5cdFx0XHRcdFx0XHRcdFx0XHRcdH0gKTtcclxuXHJcblx0dmFyIHRoaXNfZWwgPSBqUXVlcnkoICcjJyArIHBhcmFtc1sgJ3VpX2NsaWNrZWRfZWxlbWVudF9pZCcgXSApLmdldCggMCApXHJcblxyXG5cdHdwYmNfYnV0dG9uX2VuYWJsZV9sb2FkaW5nX2ljb24oIHRoaXNfZWwgKTtcclxufVxyXG5cclxuLyoqXHJcbiAqIE9wZW4gVVJMIGluIG5ldyB0YWIgLSBtYWlubHkgIGl0J3MgdXNlZCBmb3Igb3BlbiBDU1YgbGluayAgZm9yIGRvd25sb2FkZWQgZXhwb3J0ZWQgYm9va2luZ3MgYXMgQ1NWXHJcbiAqXHJcbiAqIEBwYXJhbSBleHBvcnRfY3N2X3VybFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19hanhfYm9va2luZ19fZXhwb3J0X2Nzdl91cmxfX2Rvd25sb2FkKCBleHBvcnRfY3N2X3VybCApe1xyXG5cclxuXHQvL3ZhciBzZWxlY3RlZF9ib29raW5nX2lkX2FyciA9IHdwYmNfZ2V0X3NlbGVjdGVkX3Jvd19pZCgpO1xyXG5cclxuXHRkb2N1bWVudC5sb2NhdGlvbi5ocmVmID0gZXhwb3J0X2Nzdl91cmw7Ly8gKyAnJnNlbGVjdGVkX2lkPScgKyBzZWxlY3RlZF9ib29raW5nX2lkX2Fyci5qb2luKCcsJyk7XHJcblxyXG5cdC8vIEl0J3Mgb3BlbiBhZGRpdGlvbmFsIGRpYWxvZyBmb3IgYXNraW5nIG9wZW5pbmcgdWxyIGluIG5ldyB0YWJcclxuXHQvLyB3aW5kb3cub3BlbiggZXhwb3J0X2Nzdl91cmwsICdfYmxhbmsnKS5mb2N1cygpO1xyXG59Il0sImZpbGUiOiJpbmNsdWRlcy9wYWdlLWJvb2tpbmdzL19vdXQvYm9va2luZ3NfX2FjdGlvbnMuanMifQ==
