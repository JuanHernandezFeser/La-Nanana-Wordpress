"use strict";

/**
 * Shortcode Config - Main Loop
 */
function wpbc_set_shortcode() {
  var wpbc_shortcode = '[';
  var shortcode_id = jQuery('#wpbc_shortcode_type').val().trim(); // -----------------------------------------------------------------------------------------------------------------
  // [booking]  | [bookingcalendar] | ...
  // -----------------------------------------------------------------------------------------------------

  if ('booking' === shortcode_id || 'bookingcalendar' === shortcode_id || 'bookingselect' === shortcode_id || 'bookingtimeline' === shortcode_id || 'bookingform' === shortcode_id || 'bookingsearch' === shortcode_id || 'bookingother' === shortcode_id || 'booking_import_ics' === shortcode_id || 'booking_listing_ics' === shortcode_id) {
    wpbc_shortcode += shortcode_id;
    var wpbc_options_arr = []; // -------------------------------------------------------------------------------------------------------------
    // [bookingselect] | [bookingtimeline] - Options relative only to this shortcode.
    // -------------------------------------------------------------------------------------------------------------

    if ('bookingselect' === shortcode_id || 'bookingtimeline' === shortcode_id) {
      // [bookingselect type='1,2,3'] - Multiple Resources
      if (jQuery('#' + shortcode_id + '_wpbc_multiple_resources').length > 0) {
        var multiple_resources = jQuery('#' + shortcode_id + '_wpbc_multiple_resources').val();

        if (multiple_resources != null && multiple_resources.length > 0) {
          // Remove empty spaces from  array : '' | "" | 0
          multiple_resources = multiple_resources.filter(function (n) {
            return parseInt(n);
          });
          multiple_resources = multiple_resources.join(',').trim();

          if (multiple_resources != 0) {
            wpbc_shortcode += ' type=\'' + multiple_resources + '\'';
          }
        }
      } // [bookingselect selected_type=1] - Selected Resource


      if (jQuery('#' + shortcode_id + '_wpbc_selected_resource').length > 0) {
        if (jQuery('#' + shortcode_id + '_wpbc_selected_resource').val() !== null //FixIn: 8.2.1.12
        && parseInt(jQuery('#' + shortcode_id + '_wpbc_selected_resource').val()) > 0) {
          wpbc_shortcode += ' selected_type=' + jQuery('#' + shortcode_id + '_wpbc_selected_resource').val().trim();
        }
      } // [bookingselect label='Tada'] - Label


      if (jQuery('#' + shortcode_id + '_wpbc_text_label').length > 0) {
        if ('' !== jQuery('#' + shortcode_id + '_wpbc_text_label').val().trim()) {
          wpbc_shortcode += ' label=\'' + jQuery('#' + shortcode_id + '_wpbc_text_label').val().trim().replace(/'/gi, '') + '\'';
        }
      } // [bookingselect first_option_title='Tada'] - First  Option


      if (jQuery('#' + shortcode_id + '_wpbc_first_option_title').length > 0) {
        if ('' !== jQuery('#' + shortcode_id + '_wpbc_first_option_title').val().trim()) {
          wpbc_shortcode += ' first_option_title=\'' + jQuery('#' + shortcode_id + '_wpbc_first_option_title').val().trim().replace(/'/gi, '') + '\'';
        }
      }
    } // -------------------------------------------------------------------------------------------------------------
    // [bookingtimeline] - Options relative only to this shortcode.
    // -------------------------------------------------------------------------------------------------------------


    if ('bookingtimeline' === shortcode_id) {
      // Visually update
      var wpbc_is_matrix__view_days_num_temp = wpbc_shortcode_config__update_elements_in_timeline();
      var wpbc_is_matrix = wpbc_is_matrix__view_days_num_temp[0];
      var view_days_num_temp = wpbc_is_matrix__view_days_num_temp[1]; // : view_days_num

      if (view_days_num_temp != 30) {
        wpbc_shortcode += ' view_days_num=' + view_days_num_temp;
      } // : header_title


      if (jQuery('#' + shortcode_id + '_wpbc_text_label_timeline').length > 0) {
        var header_title_temp = jQuery('#' + shortcode_id + '_wpbc_text_label_timeline').val().trim();
        header_title_temp = header_title_temp.replace(/'/gi, '');

        if (header_title_temp != '') {
          wpbc_shortcode += ' header_title=\'' + header_title_temp + '\'';
        }
      } // : scroll_month


      if (jQuery('#' + shortcode_id + '_wpbc_scroll_timeline_scroll_month').is(':visible') && jQuery('#' + shortcode_id + '_wpbc_scroll_timeline_scroll_month').length > 0 && parseInt(jQuery('#' + shortcode_id + '_wpbc_scroll_timeline_scroll_month').val().trim()) !== 0) {
        wpbc_shortcode += ' scroll_month=' + parseInt(jQuery('#' + shortcode_id + '_wpbc_scroll_timeline_scroll_month').val().trim());
      } // : scroll_day


      if (jQuery('#' + shortcode_id + '_wpbc_scroll_timeline_scroll_days').is(':visible') && jQuery('#' + shortcode_id + '_wpbc_scroll_timeline_scroll_days').length > 0 && parseInt(jQuery('#' + shortcode_id + '_wpbc_scroll_timeline_scroll_days').val().trim()) !== 0) {
        wpbc_shortcode += ' scroll_day=' + parseInt(jQuery('#' + shortcode_id + '_wpbc_scroll_timeline_scroll_days').val().trim());
      } // :limit_hours
      //FixIn: 7.0.1.17


      jQuery('.bookingtimeline_view_times').hide();

      if (wpbc_is_matrix && view_days_num_temp == 1 || !wpbc_is_matrix && view_days_num_temp == 30) {
        jQuery('.bookingtimeline_view_times').show();
        var view_times_start_temp = parseInt(jQuery('#bookingtimeline_wpbc_start_end_time_timeline_starttime').val().trim());
        var view_times_end_temp = parseInt(jQuery('#bookingtimeline_wpbc_start_end_time_timeline_endtime').val().trim());

        if (view_times_start_temp != 0 || view_times_end_temp != 24) {
          wpbc_shortcode += ' limit_hours=\'' + view_times_start_temp + ',' + view_times_end_temp + '\'';
        }
      } // :scroll_start_date


      if (jQuery('#bookingtimeline_wpbc_start_date_timeline_active').is(':checked') && jQuery('#bookingtimeline_wpbc_start_date_timeline_active').length > 0) {
        wpbc_shortcode += ' scroll_start_date=\'' + jQuery('#bookingtimeline_wpbc_start_date_timeline_year').val().trim() + '-' + jQuery('#bookingtimeline_wpbc_start_date_timeline_month').val().trim() + '-' + jQuery('#bookingtimeline_wpbc_start_date_timeline_day').val().trim() + '\'';
      }
    } // -------------------------------------------------------------------------------------------------------------
    // [bookingform  ] - Form Only        -     [bookingform type=1 selected_dates='01.03.2024']
    // -------------------------------------------------------------------------------------------------------------


    if ('bookingform' === shortcode_id) {
      var wpbc_selected_day = jQuery('#' + shortcode_id + '_wpbc_booking_date_day').val().trim();

      if (parseInt(wpbc_selected_day) < 10) {
        wpbc_selected_day = '0' + wpbc_selected_day;
      }

      var wpbc_selected_month = jQuery('#' + shortcode_id + '_wpbc_booking_date_month').val().trim();

      if (parseInt(wpbc_selected_month) < 10) {
        wpbc_selected_month = '0' + wpbc_selected_month;
      }

      wpbc_shortcode += ' selected_dates=\'' + wpbc_selected_day + '.' + wpbc_selected_month + '.' + jQuery('#' + shortcode_id + '_wpbc_booking_date_year').val().trim() + '\'';
    } // -------------------------------------------------------------------------------------------------------------
    // [bookingsearch  ] - Options relative only to this shortcode.     [bookingsearch searchresultstitle='{searchresults} Result(s) Found' noresultstitle='Nothing Found']
    // -------------------------------------------------------------------------------------------------------------


    if ('bookingsearch' === shortcode_id) {
      // Check  if we selected 'bookingsearch' | 'bookingsearchresults'
      var wpbc_search_form_results = 'bookingsearch';

      if (jQuery("input[name='bookingsearch_wpbc_search_form_results']:checked").length > 0) {
        wpbc_search_form_results = jQuery("input[name='bookingsearch_wpbc_search_form_results']:checked").val().trim();
      } // Show | Hide form  fields for 'bookingsearch' depends from  radio  bution  selection


      if ('bookingsearchresults' === wpbc_search_form_results) {
        wpbc_shortcode = '[bookingsearchresults';
        jQuery('.wpbc_search_availability_form').hide();
      } else {
        jQuery('.wpbc_search_availability_form').show(); // New page for search results

        if (jQuery('#' + shortcode_id + '_wpbc_search_new_page_enabled').length > 0 && jQuery('#' + shortcode_id + '_wpbc_search_new_page_enabled').is(':checked')) {
          // Show
          jQuery('.' + shortcode_id + '_wpbc_search_new_page_wpbc_sc_searchresults_new_page').show(); // : Search Results URL

          if (jQuery('#' + shortcode_id + '_wpbc_search_new_page_url').length > 0) {
            var search_results_url_temp = jQuery('#' + shortcode_id + '_wpbc_search_new_page_url').val().trim();
            search_results_url_temp = search_results_url_temp.replace(/'/gi, '');

            if (search_results_url_temp != '') {
              wpbc_shortcode += ' searchresults=\'' + search_results_url_temp + '\'';
            }
          }
        } else {
          // Hide
          jQuery('.' + shortcode_id + '_wpbc_search_new_page_wpbc_sc_searchresults_new_page').hide();
        } // : Search Header


        if (jQuery('#' + shortcode_id + '_wpbc_search_header').length > 0) {
          var search_header_temp = jQuery('#' + shortcode_id + '_wpbc_search_header').val().trim();
          search_header_temp = search_header_temp.replace(/'/gi, '');

          if (search_header_temp != '') {
            wpbc_shortcode += ' searchresultstitle=\'' + search_header_temp + '\'';
          }
        } // : Nothing Found


        if (jQuery('#' + shortcode_id + '_wpbc_search_nothing_found').length > 0) {
          var nothingfound_temp = jQuery('#' + shortcode_id + '_wpbc_search_nothing_found').val().trim();
          nothingfound_temp = nothingfound_temp.replace(/'/gi, '');

          if (nothingfound_temp != '') {
            wpbc_shortcode += ' noresultstitle=\'' + nothingfound_temp + '\'';
          }
        } // : Users      // [bookingsearch searchresultstitle='{searchresults} Result(s) Found' noresultstitle='Nothing Found' users='3,4543,']


        if (jQuery('#' + shortcode_id + '_wpbc_search_for_users').length > 0) {
          var only_for_users_temp = jQuery('#' + shortcode_id + '_wpbc_search_for_users').val().trim();
          only_for_users_temp = only_for_users_temp.replace(/'/gi, '');

          if (only_for_users_temp != '') {
            wpbc_shortcode += ' users=\'' + only_for_users_temp + '\'';
          }
        }
      }
    } // -------------------------------------------------------------------------------------------------------------
    // [bookingedit] , [bookingcustomerlisting] , [bookingresource type=6 show='capacity'] , [booking_confirm]
    // -------------------------------------------------------------------------------------------------------------


    if ('bookingother' === shortcode_id) {
      //TRICK:
      shortcode_id = 'no'; //required for not update booking resource ID
      // Check  if we selected 'bookingsearch' | 'bookingsearchresults'

      var bookingother_shortcode_type = 'bookingsearch';

      if (jQuery("input[name='bookingother_wpbc_shortcode_type']:checked").length > 0) {
        bookingother_shortcode_type = jQuery("input[name='bookingother_wpbc_shortcode_type']:checked").val().trim();
      } // Show | Hide sections


      if ('booking_confirm' === bookingother_shortcode_type) {
        wpbc_shortcode = '[booking_confirm';
        jQuery('.bookingother_section_additional').hide();
        jQuery('.bookingother_section_' + bookingother_shortcode_type).show();
      }

      if ('bookingedit' === bookingother_shortcode_type) {
        wpbc_shortcode = '[bookingedit';
        jQuery('.bookingother_section_additional').hide();
        jQuery('.bookingother_section_' + bookingother_shortcode_type).show();
      }

      if ('bookingcustomerlisting' === bookingother_shortcode_type) {
        wpbc_shortcode = '[bookingcustomerlisting';
        jQuery('.bookingother_section_additional').hide();
        jQuery('.bookingother_section_' + bookingother_shortcode_type).show();
      }

      if ('bookingresource' === bookingother_shortcode_type) {
        //TRICK:
        shortcode_id = 'bookingother'; //required to force update booking resource ID

        wpbc_shortcode = '[bookingresource';
        jQuery('.bookingother_section_additional').hide();
        jQuery('.bookingother_section_' + bookingother_shortcode_type).show();

        if (jQuery('#bookingother_wpbc_resource_show').val().trim() != 'title') {
          wpbc_shortcode += ' show=\'' + jQuery('#bookingother_wpbc_resource_show').val().trim() + '\'';
        }
      }
    } // [booking-manager-import ...]     ||      [booking-manager-listing ...]


    if ('booking_import_ics' === shortcode_id || 'booking_listing_ics' === shortcode_id) {
      wpbc_shortcode = '[booking-manager-import';

      if ('booking_listing_ics' === shortcode_id) {
        wpbc_shortcode = '[booking-manager-listing';
      } ////////////////////////////////////////////////////////////////
      // : .ics feed URL
      ////////////////////////////////////////////////////////////////


      var shortcode_url_temp = '';

      if (jQuery('#' + shortcode_id + '_wpbc_url').length > 0) {
        shortcode_url_temp = jQuery('#' + shortcode_id + '_wpbc_url').val().trim();
        shortcode_url_temp = shortcode_url_temp.replace(/'/gi, '');

        if (shortcode_url_temp != '') {
          wpbc_shortcode += ' url=\'' + shortcode_url_temp + '\'';
        }
      }

      if (shortcode_url_temp == '') {
        // Error:
        wpbc_shortcode = '[ URL is required ';
      } else {
        // VALID:
        ////////////////////////////////////////////////////////////////
        // [... from='' 'from_offset=''  ...]
        ////////////////////////////////////////////////////////////////
        if (jQuery('#' + shortcode_id + '_from').length > 0) {
          var p_from = jQuery('#' + shortcode_id + '_from').val().trim();
          var p_from_offset = jQuery('#' + shortcode_id + '_from_offset').val().trim();
          p_from = p_from.replace(/'/gi, '');
          p_from_offset = p_from_offset.replace(/'/gi, '');

          if ('' != p_from && 'date' != p_from) {
            // Offset
            wpbc_shortcode += ' from=\'' + p_from + '\'';

            if ('any' != p_from && '' != p_from_offset) {
              p_from_offset = parseInt(p_from_offset);

              if (!isNaN(p_from_offset)) {
                wpbc_shortcode += ' from_offset=\'' + p_from_offset + jQuery('#' + shortcode_id + '_from_offset_type').val().trim().charAt(0) + '\'';
              }
            }
          } else if (p_from == 'date' && p_from_offset != '') {
            // If selected Date
            wpbc_shortcode += ' from=\'' + p_from_offset + '\'';
          }
        } ////////////////////////////////////////////////////////////////
        // [... until='' 'until_offset=''  ...]
        ////////////////////////////////////////////////////////////////


        if (jQuery('#' + shortcode_id + '_until').length > 0) {
          var p_until = jQuery('#' + shortcode_id + '_until').val().trim();
          var p_until_offset = jQuery('#' + shortcode_id + '_until_offset').val().trim();
          p_until = p_until.replace(/'/gi, '');
          p_until_offset = p_until_offset.replace(/'/gi, '');

          if ('' != p_until && 'date' != p_until) {
            // Offset
            wpbc_shortcode += ' until=\'' + p_until + '\'';

            if ('any' != p_until && '' != p_until_offset) {
              p_until_offset = parseInt(p_until_offset);

              if (!isNaN(p_until_offset)) {
                wpbc_shortcode += ' until_offset=\'' + p_until_offset + jQuery('#' + shortcode_id + '_until_offset_type').val().trim().charAt(0) + '\'';
              }
            }
          } else if (p_until == 'date' && p_until_offset != '') {
            // If selected Date
            wpbc_shortcode += ' until=\'' + p_until_offset + '\'';
          }
        } ////////////////////////////////////////////////////////////////
        // Max
        ////////////////////////////////////////////////////////////////


        if (jQuery('#' + shortcode_id + '_conditions_max_num').length > 0) {
          var p_max = parseInt(jQuery('#' + shortcode_id + '_conditions_max_num').val().trim());

          if (p_max != 0) {
            wpbc_shortcode += ' max=' + p_max;
          }
        } ////////////////////////////////////////////////////////////////
        // Silence
        ////////////////////////////////////////////////////////////////


        if (jQuery('#' + shortcode_id + '_silence').length > 0) {
          if ('1' === jQuery('#' + shortcode_id + '_silence').val().trim()) {
            wpbc_shortcode += ' silence=1';
          }
        } ////////////////////////////////////////////////////////////////
        // is_all_dates_in
        ////////////////////////////////////////////////////////////////


        if (jQuery('#' + shortcode_id + '_conditions_events').length > 0) {
          var p_is_all_dates_in = parseInt(jQuery('#' + shortcode_id + '_conditions_events').val().trim());

          if (p_is_all_dates_in != 0) {
            wpbc_shortcode += ' is_all_dates_in=' + p_is_all_dates_in;
          }
        } ////////////////////////////////////////////////////////////////
        // import_conditions
        ////////////////////////////////////////////////////////////////


        if (jQuery('#' + shortcode_id + '_conditions_import').length > 0) {
          var p_import_conditions = jQuery('#' + shortcode_id + '_conditions_import').val().trim();
          p_import_conditions = p_import_conditions.replace(/'/gi, '');

          if (p_import_conditions != '') {
            wpbc_shortcode += ' import_conditions=\'' + p_import_conditions + '\'';
          }
        }
      }
    } // -------------------------------------------------------------------------------------------------------------
    // [booking] , [bookingcalendar] , ...  parameters for these shortcodes and others...
    // -------------------------------------------------------------------------------------------------------------


    if (jQuery('#' + shortcode_id + '_wpbc_resource_id').length > 0) {
      if (jQuery('#' + shortcode_id + '_wpbc_resource_id').val() === null) {
        //FixIn: 8.2.1.12
        jQuery('#wpbc_text_put_in_shortcode').val('---');
        return;
      } else {
        wpbc_shortcode += ' resource_id=' + jQuery('#' + shortcode_id + '_wpbc_resource_id').val().trim();
      }
    }

    if (jQuery('#' + shortcode_id + '_wpbc_custom_form').length > 0) {
      var form_type_temp = jQuery('#' + shortcode_id + '_wpbc_custom_form').val().trim();
      if (form_type_temp != 'standard') wpbc_shortcode += ' form_type=\'' + jQuery('#' + shortcode_id + '_wpbc_custom_form').val().trim() + '\'';
    }

    if (jQuery('#' + shortcode_id + '_wpbc_nummonths').length > 0 && parseInt(jQuery('#' + shortcode_id + '_wpbc_nummonths').val().trim()) > 1) {
      wpbc_shortcode += ' nummonths=' + jQuery('#' + shortcode_id + '_wpbc_nummonths').val().trim();
    }

    if (jQuery('#' + shortcode_id + '_wpbc_startmonth_active').length > 0 && jQuery('#' + shortcode_id + '_wpbc_startmonth_active').is(':checked')) {
      wpbc_shortcode += ' startmonth=\'' + jQuery('#' + shortcode_id + '_wpbc_startmonth_year').val().trim() + '-' + jQuery('#' + shortcode_id + '_wpbc_startmonth_month').val().trim() + '\'';
    }

    if (jQuery('#' + shortcode_id + '_wpbc_aggregate').length > 0) {
      var wpbc_aggregate_temp = jQuery('#' + shortcode_id + '_wpbc_aggregate').val();

      if (wpbc_aggregate_temp != null && wpbc_aggregate_temp.length > 0) {
        wpbc_aggregate_temp = wpbc_aggregate_temp.join(';');

        if (wpbc_aggregate_temp != 0) {
          // Check about 0=>'None'
          wpbc_shortcode += ' aggregate=\'' + wpbc_aggregate_temp + '\'';

          if (jQuery('#' + shortcode_id + '_wpbc_aggregate__bookings_only').is(':checked')) {
            wpbc_options_arr.push('{aggregate type=bookings_only}');
          }
        }
      }
    } // -------------------------------------------------------------------------------------------------------------
    // Option Param
    // -------------------------------------------------------------------------------------------------------------
    // Options : Size


    var wpbc_options_size = '';

    if (jQuery('#' + shortcode_id + '_wpbc_size_enabled').length > 0 && jQuery('#' + shortcode_id + '_wpbc_size_enabled').is(':checked')) {
      // options='{calendar months_num_in_row=2 width=100% cell_height=40px}'
      wpbc_options_size += '{calendar';
      wpbc_options_size += ' ' + 'months_num_in_row=' + Math.min(parseInt(jQuery('#' + shortcode_id + '_wpbc_size_months_num_in_row').val().trim()), parseInt(jQuery('#' + shortcode_id + '_wpbc_nummonths').val().trim()));
      wpbc_options_size += ' ' + 'width=' + parseInt(jQuery('#' + shortcode_id + '_wpbc_size_calendar_width').val().trim()) + jQuery('#' + shortcode_id + '_wpbc_size_calendar_width_px_pr').val().trim();
      wpbc_options_size += ' ' + 'cell_height=' + parseInt(jQuery('#' + shortcode_id + '_wpbc_size_calendar_cell_height').val().trim()) + 'px';
      wpbc_options_size += '}';
      wpbc_options_arr.push(wpbc_options_size);
    } // Options: Days number depend on   Weekday


    if (jQuery('#' + shortcode_id + 'wpbc_select_day_weekday_textarea').length > 0) {
      wpbc_options_size = jQuery('#' + shortcode_id + 'wpbc_select_day_weekday_textarea').val().trim();

      if (wpbc_options_size.length > 0) {
        wpbc_options_arr.push(wpbc_options_size);
      }
    } // Options: Days number depend on   SEASON


    if (jQuery('#' + shortcode_id + 'wpbc_select_day_season_textarea').length > 0) {
      wpbc_options_size = jQuery('#' + shortcode_id + 'wpbc_select_day_season_textarea').val().trim();

      if (wpbc_options_size.length > 0) {
        wpbc_options_arr.push(wpbc_options_size);
      }
    } // Options: Start weekday depend on   SEASON


    if (jQuery('#' + shortcode_id + 'wpbc_start_day_season_textarea').length > 0) {
      wpbc_options_size = jQuery('#' + shortcode_id + 'wpbc_start_day_season_textarea').val().trim();

      if (wpbc_options_size.length > 0) {
        wpbc_options_arr.push(wpbc_options_size);
      }
    } // Option: Days number depend on from  DATE


    if (jQuery('#' + shortcode_id + 'wpbc_select_day_fordate_textarea').length > 0) {
      wpbc_options_size = jQuery('#' + shortcode_id + 'wpbc_select_day_fordate_textarea').val().trim();

      if (wpbc_options_size.length > 0) {
        wpbc_options_arr.push(wpbc_options_size);
      }
    }

    if (wpbc_options_arr.length > 0) {
      wpbc_shortcode += ' options=\'' + wpbc_options_arr.join(',') + '\'';
    }
  }

  wpbc_shortcode += ']';
  jQuery('#wpbc_text_put_in_shortcode').val(wpbc_shortcode);
}
/**
 * Open TinyMCE Modal */


function wpbc_tiny_btn_click(tag) {
  //FixIn: 9.0.1.5
  jQuery('#wpbc_tiny_modal').wpbc_my_modal({
    keyboard: false,
    backdrop: true,
    show: true
  }); //FixIn: 8.3.3.99

  jQuery("#wpbc_text_gettenberg_section_id").val('');
}
/**
 * Open TinyMCE Modal */


function wpbc_tiny_close() {
  jQuery('#wpbc_tiny_modal').wpbc_my_modal('hide'); //FixIn: 9.0.1.5
}
/* ------------------------------------------------------------------------------------------------------------------ */

/** Send Text */

/* ------------------------------------------------------------------------------------------------------------------ */

/**
 * Send text  to editor */


function wpbc_send_text_to_editor(h) {
  // FixIn: 8.3.3.99
  if (typeof wpbc_send_text_to_gutenberg == 'function') {
    var is_send = wpbc_send_text_to_gutenberg(h);

    if (true === is_send) {
      return;
    }
  }

  var ed,
      mce = typeof tinymce != 'undefined',
      qt = typeof QTags != 'undefined';

  if (!wpActiveEditor) {
    if (mce && tinymce.activeEditor) {
      ed = tinymce.activeEditor;
      wpActiveEditor = ed.id;
    } else if (!qt) {
      return false;
    }
  } else if (mce) {
    if (tinymce.activeEditor && (tinymce.activeEditor.id == 'mce_fullscreen' || tinymce.activeEditor.id == 'wp_mce_fullscreen')) ed = tinymce.activeEditor;else ed = tinymce.get(wpActiveEditor);
  }

  if (ed && !ed.isHidden()) {
    // restore caret position on IE
    if (tinymce.isIE && ed.windowManager.insertimagebookmark) ed.selection.moveToBookmark(ed.windowManager.insertimagebookmark);

    if (h.indexOf('[caption') !== -1) {
      if (ed.wpSetImgCaption) h = ed.wpSetImgCaption(h);
    } else if (h.indexOf('[gallery') !== -1) {
      if (ed.plugins.wpgallery) h = ed.plugins.wpgallery._do_gallery(h);
    } else if (h.indexOf('[embed') === 0) {
      if (ed.plugins.wordpress) h = ed.plugins.wordpress._setEmbed(h);
    }

    ed.execCommand('mceInsertContent', false, h);
  } else if (qt) {
    QTags.insertContent(h);
  } else {
    document.getElementById(wpActiveEditor).value += h;
  }

  try {
    tb_remove();
  } catch (e) {}

  ;
}
/**
 * RESOURCES PAGE: Open TinyMCE Modal */


function wpbc_resource_page_btn_click(resource_id) {
  var shortcode_default_value = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
  //FixIn: 9.0.1.5
  jQuery('#wpbc_tiny_modal').wpbc_my_modal({
    keyboard: false,
    backdrop: true,
    show: true
  }); // Disable some options - selection of booking resource - because we configure it only for specific booking resource, where we clicked.

  var shortcode_arr = ['booking', 'bookingcalendar', 'bookingform'];

  for (var shortcde_key in shortcode_arr) {
    var shortcode_id = shortcode_arr[shortcde_key];
    jQuery('#' + shortcode_id + '_wpbc_resource_id').prop('disabled', false);
    jQuery('#' + shortcode_id + "_wpbc_resource_id option[value='" + resource_id + "']").prop('selected', true).trigger('change');
    jQuery('#' + shortcode_id + '_wpbc_resource_id').prop('disabled', true);
  } // Hide left  navigation  items
  //        jQuery( ".wpbc_shortcode_config_navigation_column .wpbc_settings_navigation_item" ).hide();


  jQuery("#wpbc_shortcode_config__nav_tab__booking").show();
  jQuery("#wpbc_shortcode_config__nav_tab__bookingcalendar").show(); // Hide | Show Insert  button  for booking resource page

  jQuery(".wpbc_tiny_button__insert_to_editor").hide();
  jQuery(".wpbc_tiny_button__insert_to_resource").show();
}
/**
 * Get Shortcode Value from  shortcode text field in PopUp shortcode Config dialog and insert  into DIV and INPUT TEXT field near specific booking resource.
 *  But it takes ID  of booking resource,  where to  insert  this shortcode only from  'booking' section  of Config Dialog. usually  such  booking resource  disabled there!
 *  e.g.: jQuery( "#booking_wpbc_resource_id" ).val()
 *
 * @param shortcode_val
 */


function wpbc_send_text_to_resource(shortcode_val) {
  jQuery('#div_booking_resource_shortcode_' + jQuery("#booking_wpbc_resource_id").val()).html(shortcode_val);
  jQuery('#booking_resource_shortcode_' + jQuery("#booking_wpbc_resource_id").val()).val(shortcode_val);
  jQuery('#booking_resource_shortcode_' + jQuery("#booking_wpbc_resource_id").val()).trigger('change'); // Scroll

  if ('function' === typeof wpbc_scroll_to) {
    wpbc_scroll_to('#div_booking_resource_shortcode_' + jQuery("#booking_wpbc_resource_id").val());
  }
}
/* R E S E T */


function wpbc_shortcode_config__reset(shortcode_val) {
  jQuery('#' + shortcode_val + '_wpbc_startmonth_active').prop('checked', false).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_aggregate option:selected').prop('selected', false);
  jQuery('#' + shortcode_val + '_wpbc_aggregate option:eq(0)').prop('selected', true);
  jQuery('#' + shortcode_val + '_wpbc_aggregate__bookings_only').prop('checked', false).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_custom_form option:eq(0)').prop('selected', true);
  jQuery('#' + shortcode_val + '_wpbc_nummonths option:eq(0)').prop('selected', true);
  jQuery('#' + shortcode_val + '_wpbc_size_enabled').prop('checked', false).trigger('change');
  wpbc_shortcode_config__select_day_weekday__reset(shortcode_val + 'wpbc_select_day_weekday');
  wpbc_shortcode_config__select_day_season__reset(shortcode_val + 'wpbc_select_day_season');
  wpbc_shortcode_config__start_day_season__reset(shortcode_val + 'wpbc_start_day_season');
  wpbc_shortcode_config__select_day_fordate__reset(shortcode_val + 'wpbc_select_day_fordate'); // Reset  for [bookingselect] shortcode params

  jQuery('#' + shortcode_val + '_wpbc_multiple_resources option:selected').prop('selected', false);
  jQuery('#' + shortcode_val + '_wpbc_multiple_resources option:eq(0)').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_selected_resource option:eq(0)').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_text_label').val('').trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_first_option_title').val('').trigger('change'); // Reset  for [bookingtimeline] shortcode params

  jQuery('#' + shortcode_val + '_wpbc_text_label_timeline').val('').trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_scroll_timeline_scroll_month option[value="0"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_scroll_timeline_scroll_days option[value="0"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_start_date_timeline_active').prop('checked', false).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_start_end_time_timeline_starttime option[value="0"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_start_end_time_timeline_endtime option[value="24"]').prop('selected', true).trigger('change');
  jQuery('input[name="' + shortcode_val + '_wpbc_view_mode_timeline_months_num_in_row"][value="30"]').prop('checked', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_start_date_timeline_year option[value="' + new Date().getFullYear() + '"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_start_date_timeline_month option[value="' + (new Date().getMonth() + 1) + '"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_start_date_timeline_day option[value="' + new Date().getDate() + '"]').prop('selected', true).trigger('change'); // Reset  for [bookingform] shortcode params

  jQuery('#' + shortcode_val + '_wpbc_booking_date_year option[value="' + new Date().getFullYear() + '"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_booking_date_month option[value="' + (new Date().getMonth() + 1) + '"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_booking_date_day option[value="' + new Date().getDate() + '"]').prop('selected', true).trigger('change'); // Reset  for [[bookingsearch ...] shortcode params

  jQuery('#' + shortcode_val + '_wpbc_search_new_page_url').val('').trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_search_new_page_enabled').prop('checked', false).trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_search_header').val('').trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_search_nothing_found').val('').trigger('change');
  jQuery('#' + shortcode_val + '_wpbc_search_for_users').val('').trigger('change');
  jQuery('input[name="' + shortcode_val + '_wpbc_search_form_results"][value="bookingsearch"]').prop('checked', true).trigger('change'); // Reset  for [bookingedit] , [bookingcustomerlisting] , [bookingresource type=6 show='capacity'] , [booking_confirm]

  jQuery('input[name="' + shortcode_val + '_wpbc_shortcode_type"][value="booking_confirm"]').prop('checked', true).trigger('change'); // booking_import_ics , booking_listing_ics

  jQuery('#' + shortcode_val + '_wpbc_url').val('').trigger('change');
  jQuery('#' + shortcode_val + '_from option[value="today"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_from_offset').val('').trigger('change');
  jQuery('#' + shortcode_val + '_from_offset_type option:eq(0)').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_until option[value="any"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_until_offset').val('').trigger('change');
  jQuery('#' + shortcode_val + '_until_offset_type option:eq(0)').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_conditions_import option:eq(0)').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_conditions_events option[value="1"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_conditions_max_num option[value="0"]').prop('selected', true).trigger('change');
  jQuery('#' + shortcode_val + '_silence option[value="0"]').prop('selected', true).trigger('change');
}
/* ------------------------------------------------------------------------------------------------------------------ */

/**
 *  SHORTCODE_CONFIG
 * */

/* ------------------------------------------------------------------------------------------------------------------ */

/**
 * When click on menu item in "Left Vertical Navigation" panel  in shortcode config popup
 */


function wpbc_shortcode_config_click_show_section(_this, section_id_to_show, shortcode_name) {
  // Menu
  jQuery(_this).parents('.wpbc_settings_flex_container').find('.wpbc_settings_navigation_item_active').removeClass('wpbc_settings_navigation_item_active');
  jQuery(_this).parents('.wpbc_settings_navigation_item').addClass('wpbc_settings_navigation_item_active'); // Content

  jQuery(_this).parents('.wpbc_settings_flex_container').find('.wpbc_sc_container__shortcode').hide();
  jQuery(section_id_to_show).show(); // Scroll

  if ('function' === typeof wpbc_scroll_to) {
    wpbc_scroll_to(section_id_to_show);
  } // Set - Shortcode Type


  jQuery('#wpbc_shortcode_type').val(shortcode_name); // Parse shortcode params

  wpbc_set_shortcode();
}
/**
 * Do Next / Prior step
 * @param _this		button  this
 * @param step		'prior' | 'next'
 */


function wpbc_shortcode_config_content_toolbar__next_prior(_this, step) {
  var j_work_nav_tab;
  var submenu_selected = jQuery(_this).parents('.wpbc_sc_container__shortcode').find('.wpbc_sc_container__shortcode_section:visible').find('.wpdevelop-submenu-tab-selected:visible');

  if (submenu_selected.length) {
    if ('next' === step) {
      j_work_nav_tab = submenu_selected.nextAll('a.nav-tab:visible').first();
    } else {
      j_work_nav_tab = submenu_selected.prevAll('a.nav-tab:visible').first();
    }

    if (j_work_nav_tab.length) {
      j_work_nav_tab.trigger('click');
      return;
    }
  }

  if ('next' === step) {
    j_work_nav_tab = jQuery(_this).parents('.wpbc_sc_container__shortcode').find('.nav-tab.nav-tab-active:visible').nextAll('a.nav-tab:visible').first();
  } else {
    j_work_nav_tab = jQuery(_this).parents('.wpbc_sc_container__shortcode').find('.nav-tab.nav-tab-active:visible').prevAll('a.nav-tab:visible').first();
  }

  if (j_work_nav_tab.length) {
    j_work_nav_tab.trigger('click');
  }
}
/**
 * Condition:   {select-day condition="weekday" for="5" value="3"}
 */


function wpbc_shortcode_config__select_day_weekday__add(id) {
  var condition_rule_arr = [];

  for (var weekday_num = 0; weekday_num < 8; weekday_num++) {
    if (jQuery('#' + id + '__weekday_' + weekday_num).is(':checked')) {
      var days_to_select = jQuery('#' + id + '__days_number_' + weekday_num).val().trim(); // Remove all words except digits and , and -

      days_to_select = days_to_select.replace(/[^0-9,-]/g, '');
      days_to_select = days_to_select.replace(/[,]{2,}/g, ',');
      days_to_select = days_to_select.replace(/[-]{2,}/g, '-');
      jQuery('#' + id + '__days_number_' + weekday_num).val(days_to_select);

      if ('' !== days_to_select) {
        condition_rule_arr.push('{select-day condition="weekday" for="' + weekday_num + '" value="' + days_to_select + '"}');
      } else {
        // Red highlight fields,  if some required fields are empty
        if ('function' === typeof wpbc_field_highlight && '' === jQuery('#' + id + '__days_number_' + weekday_num).val()) {
          wpbc_field_highlight('#' + id + '__days_number_' + weekday_num);
        }
      }
    }
  }

  var condition_rule = condition_rule_arr.join(',');
  jQuery('#' + id + '_textarea').val(condition_rule);
  wpbc_set_shortcode();
}

function wpbc_shortcode_config__select_day_weekday__reset(id) {
  for (var weekday_num = 0; weekday_num < 8; weekday_num++) {
    jQuery('#' + id + '__days_number_' + weekday_num).val('');

    if (jQuery('#' + id + '__weekday_' + weekday_num).is(':checked')) {
      jQuery('#' + id + '__weekday_' + weekday_num).prop('checked', false);
    }
  }

  jQuery('#' + id + '_textarea').val('');
  wpbc_set_shortcode();
}
/**
 * Condition:   {select-day condition="season" for="High season" value="7-14,20"}
 */


function wpbc_shortcode_config__select_day_season__add(id) {
  var season_filter_name = jQuery('#' + id + '__season_filter_name option:selected').text().trim(); // Escape quote symbols

  season_filter_name = season_filter_name.replace(/[\""]/g, '\\"');
  var days_number = jQuery('#' + id + '__days_number').val().trim(); // Remove all words except digits and , and -

  days_number = days_number.replace(/[^0-9,-]/g, '');
  days_number = days_number.replace(/[,]{2,}/g, ',');
  days_number = days_number.replace(/[-]{2,}/g, '-');
  jQuery('#' + id + '__days_number').val(days_number);

  if ('' != days_number && '' != season_filter_name && 0 != jQuery('#' + id + '__season_filter_name').val()) {
    var exist_configuration = jQuery('#' + id + '_textarea').val();
    exist_configuration = exist_configuration.replaceAll("},{", '}~~{');
    var condition_rule_arr = exist_configuration.split('~~'); // Remove empty spaces from  array : '' | ""

    condition_rule_arr = condition_rule_arr.filter(function (n) {
      return n;
    });
    condition_rule_arr.push('{select-day condition="season" for="' + season_filter_name + '" value="' + days_number + '"}'); // Remove duplicates from  the array

    condition_rule_arr = condition_rule_arr.filter(function (item, pos) {
      return condition_rule_arr.indexOf(item) === pos;
    });
    var condition_rule = condition_rule_arr.join(',');
    jQuery('#' + id + '_textarea').val(condition_rule);
    wpbc_set_shortcode();
  } // Red highlight fields,  if some required fields are empty


  if ('function' === typeof wpbc_field_highlight && '' === jQuery('#' + id + '__days_number').val()) {
    wpbc_field_highlight('#' + id + '__days_number');
  }

  if ('function' === typeof wpbc_field_highlight && '0' === jQuery('#' + id + '__season_filter_name').val()) {
    wpbc_field_highlight('#' + id + '__season_filter_name');
  }
}

function wpbc_shortcode_config__select_day_season__reset(id) {
  jQuery('#' + id + '__season_filter_name option:eq(0)').prop('selected', true);
  jQuery('#' + id + '__days_number').val('');
  jQuery('#' + id + '_textarea').val('');
  wpbc_set_shortcode();
}
/**
 * Condition:   {start-day condition="season" for="Low season" value="0,1,5"}
 */


function wpbc_shortcode_config__start_day_season__add(id) {
  var season_filter_name = jQuery('#' + id + '__season_filter_name option:selected').text().trim(); // Escape quote symbols

  season_filter_name = season_filter_name.replace(/[\""]/g, '\\"');

  if ('' != season_filter_name && 0 != jQuery('#' + id + '__season_filter_name').val()) {
    var activated_weekdays = [];

    for (var weekday_num = 0; weekday_num < 8; weekday_num++) {
      if (jQuery('#' + id + '__weekday_' + weekday_num).is(':checked')) {
        activated_weekdays.push(weekday_num);
      }
    }

    activated_weekdays = activated_weekdays.join(',');

    if ('' != activated_weekdays) {
      var exist_configuration = jQuery('#' + id + '_textarea').val();
      exist_configuration = exist_configuration.replaceAll("},{", '}~~{');
      var condition_rule_arr = exist_configuration.split('~~'); // Remove empty spaces from  array : '' | ""

      condition_rule_arr = condition_rule_arr.filter(function (n) {
        return n;
      });
      condition_rule_arr.push('{start-day condition="season" for="' + season_filter_name + '" value="' + activated_weekdays + '"}'); // Remove duplicates from  the array

      condition_rule_arr = condition_rule_arr.filter(function (item, pos) {
        return condition_rule_arr.indexOf(item) === pos;
      });
      var condition_rule = condition_rule_arr.join(',');
      jQuery('#' + id + '_textarea').val(condition_rule);
      wpbc_set_shortcode();
    }
  } // Red highlight fields,  if some required fields are empty


  if ('function' === typeof wpbc_field_highlight && '0' === jQuery('#' + id + '__season_filter_name').val()) {
    wpbc_field_highlight('#' + id + '__season_filter_name');
  }
}

function wpbc_shortcode_config__start_day_season__reset(id) {
  jQuery('#' + id + '__season_filter_name option:eq(0)').prop('selected', true);

  for (var weekday_num = 0; weekday_num < 8; weekday_num++) {
    if (jQuery('#' + id + '__weekday_' + weekday_num).is(':checked')) {
      jQuery('#' + id + '__weekday_' + weekday_num).prop('checked', false);
    }
  }

  jQuery('#' + id + '_textarea').val('');
  wpbc_set_shortcode();
}
/**
 * Condition:   {select-day condition="date" for="2023-10-01" value="20,25,30-35"}
 */


function wpbc_shortcode_config__select_day_fordate__add(id) {
  var start_date__fordate = jQuery('#' + id + '__date').val().trim(); // Remove all words except digits and , and -

  start_date__fordate = start_date__fordate.replace(/[^0-9-]/g, '');
  var globalRegex = new RegExp(/^\d{4}-[01]{1}\d{1}-[0123]{1}\d{1}$/, 'g');
  var is_valid_date = globalRegex.test(start_date__fordate);

  if (!is_valid_date) {
    start_date__fordate = '';
  }

  jQuery('#' + id + '__date').val(start_date__fordate);
  var days_number = jQuery('#' + id + '__days_number').val().trim(); // Remove all words except digits and , and -

  days_number = days_number.replace(/[^0-9,-]/g, '');
  days_number = days_number.replace(/[,]{2,}/g, ',');
  days_number = days_number.replace(/[-]{2,}/g, '-');
  jQuery('#' + id + '__days_number').val(days_number);

  if ('' != days_number && '' != start_date__fordate && 0 != jQuery('#' + id + '__season_filter_name').val()) {
    var exist_configuration = jQuery('#' + id + '_textarea').val();
    exist_configuration = exist_configuration.replaceAll("},{", '}~~{');
    var condition_rule_arr = exist_configuration.split('~~'); // Remove empty spaces from  array : '' | ""

    condition_rule_arr = condition_rule_arr.filter(function (n) {
      return n;
    });
    condition_rule_arr.push('{select-day condition="date" for="' + start_date__fordate + '" value="' + days_number + '"}'); // Remove duplicates from  the array

    condition_rule_arr = condition_rule_arr.filter(function (item, pos) {
      return condition_rule_arr.indexOf(item) === pos;
    });
    var condition_rule = condition_rule_arr.join(',');
    jQuery('#' + id + '_textarea').val(condition_rule);
    wpbc_set_shortcode();
  } else // Red highlight fields,  if some required fields are empty
    if ('function' === typeof wpbc_field_highlight && '' === jQuery('#' + id + '__date').val()) {
      wpbc_field_highlight('#' + id + '__date');
    }

  if ('function' === typeof wpbc_field_highlight && '' === jQuery('#' + id + '__days_number').val()) {
    wpbc_field_highlight('#' + id + '__days_number');
  }
}

function wpbc_shortcode_config__select_day_fordate__reset(id) {
  jQuery('#' + id + '__date').val('');
  jQuery('#' + id + '__days_number').val('');
  jQuery('#' + id + '_textarea').val('');
  wpbc_set_shortcode();
}

function wpbc_shortcode_config__update_elements_in_timeline() {
  var wpbc_is_matrix = false;

  if (jQuery('#bookingtimeline_wpbc_multiple_resources').length > 0) {
    var bookingtimeline_wpbc_multiple_resources_temp = jQuery('#bookingtimeline_wpbc_multiple_resources').val();

    if (bookingtimeline_wpbc_multiple_resources_temp != null && bookingtimeline_wpbc_multiple_resources_temp.length > 0) {
      jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row']").prop("disabled", false);
      jQuery(".wpbc_sc_container__shortcode_bookingtimeline label.wpbc-form-radio").show();

      if (bookingtimeline_wpbc_multiple_resources_temp.length > 1 || bookingtimeline_wpbc_multiple_resources_temp.length == 1 && bookingtimeline_wpbc_multiple_resources_temp[0] == '0') {
        // Matrix
        wpbc_is_matrix = true;
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='90']").prop("disabled", true);
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='90']").parents('.wpbc-form-radio').hide();
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='365']").prop("disabled", true);
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='365']").parents('.wpbc-form-radio').hide();
      } else {
        // Single
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='1']").prop("disabled", true);
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='1']").parents('.wpbc-form-radio').hide();
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='7']").prop("disabled", true);
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='7']").parents('.wpbc-form-radio').hide();
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='60']").prop("disabled", true);
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='60']").parents('.wpbc-form-radio').hide();
      }

      if (jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row']:checked").is(':disabled')) {
        jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='30']").prop("checked", true);
      }
    }
  }

  var view_days_num_temp = 30;

  if (jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row']:checked").length > 0) {
    var view_days_num_temp = parseInt(jQuery("input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row']:checked").val().trim());
  } ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // Hide or Show Scrolling Days and Months, depending on from type of view and number of booking resources
  ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


  jQuery("#wpbc_bookingtimeline_scroll_month,#wpbc_bookingtimeline_scroll_day").prop("disabled", false);
  jQuery(".wpbc_bookingtimeline_scroll_month,.wpbc_bookingtimeline_scroll_day").show(); // Matrix //////////////////////////////////////////////

  if (wpbc_is_matrix && (view_days_num_temp == 1 || view_days_num_temp == 7) // Day | Week view
  ) {
    jQuery("#wpbc_bookingtimeline_scroll_month").prop("disabled", true); // Scroll Month NOT working

    jQuery('.wpbc_bookingtimeline_scroll_month').hide();
  }

  if (wpbc_is_matrix && (view_days_num_temp == 30 || view_days_num_temp == 60) // Month view
  ) {
    jQuery("#wpbc_bookingtimeline_scroll_day").prop("disabled", true); // Scroll Days NOT working

    jQuery('.wpbc_bookingtimeline_scroll_day').hide();
  } // Single //////////////////////////////////////////////


  if (!wpbc_is_matrix && (view_days_num_temp == 30 || view_days_num_temp == 90) // Month | 3 Months view (like week view)
  ) {
    jQuery("#wpbc_bookingtimeline_scroll_month").prop("disabled", true); // Scroll Month NOT working

    jQuery('.wpbc_bookingtimeline_scroll_month').hide();
  }

  if (!wpbc_is_matrix && view_days_num_temp == 365 // Year view
  ) {
    jQuery("#wpbc_bookingtimeline_scroll_day").prop("disabled", true); // Scroll Days NOT working

    jQuery('.wpbc_bookingtimeline_scroll_day').hide();
  } ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


  return [wpbc_is_matrix, view_days_num_temp];
}

jQuery(document).ready(function () {
  // -----------------------------------------------------------------------------------------------------
  // [booking ... ]
  var shortcode_arr = ['booking', 'bookingcalendar', 'bookingselect', 'bookingtimeline', 'bookingform', 'bookingsearch', 'bookingother', 'booking_import_ics', 'booking_listing_ics'];

  for (var shortcde_key in shortcode_arr) {
    var id = shortcode_arr[shortcde_key]; // -------------------------------------------------------------------------------------------------------------
    // Hide by Size sections
    // -------------------------------------------------------------------------------------------------------------

    jQuery('.' + id + '_wpbc_size_wpbc_sc_calendar_size').hide(); // options :: Show / Hide SIZE calendar  section

    jQuery('#' + id + '_wpbc_size_enabled').on('change', {
      'id': id
    }, function (event) {
      if (jQuery('#' + event.data.id + '_wpbc_size_enabled').is(':checked')) {
        jQuery('.' + event.data.id + '_wpbc_size_wpbc_sc_calendar_size').show();
      } else {
        jQuery('.' + event.data.id + '_wpbc_size_wpbc_sc_calendar_size').hide();
      }
    }); // -------------------------------------------------------------------------------------------------------------
    // Update Shortcode on changing: Size
    // -------------------------------------------------------------------------------------------------------------

    jQuery('#' + id + '_wpbc_size_enabled' // Size On | Off
    + ',#' + id + '_wpbc_size_months_num_in_row' // - Month Num in Row
    + ',#' + id + '_wpbc_size_calendar_width' // - Width
    + ',#' + id + '_wpbc_size_calendar_width_px_pr' // - Width PS | %
    + ',#' + id + '_wpbc_size_calendar_cell_height' // - Cell Height
    + ',#' + id + 'wpbc_select_day_weekday_textarea' // Rule Weekday
    + ',#' + id + 'wpbc_select_day_season_textarea' // Rule Season
    + ',#' + id + 'wpbc_start_day_season_textarea' // Rule Season - Start day
    + ',#' + id + 'wpbc_select_day_fordate_textarea' // Rule Date
    + ',#' + id + '_wpbc_resource_id' // Resource ID
    + ',#' + id + '_wpbc_custom_form' // Custom Form
    + ',#' + id + '_wpbc_nummonths' // Num Months
    + ',#' + id + '_wpbc_startmonth_active' // Start Month Enable
    + ',#' + id + '_wpbc_startmonth_year' //  - Year
    + ',#' + id + '_wpbc_startmonth_month' //  - Month
    + ',#' + id + '_wpbc_aggregate' // Aggregate
    + ',#' + id + '_wpbc_aggregate__bookings_only' // aggregate option
    + ',#' + id + '_wpbc_multiple_resources' // [bookingselect] - Multiple Resources
    + ',#' + id + '_wpbc_selected_resource' // [bookingselect] - Selected Resource
    + ',#' + id + '_wpbc_text_label' // [bookingselect] - Label
    + ',#' + id + '_wpbc_first_option_title' // [bookingselect] - First  Option
    // TimeLine
    + ",input[name='" + id + "_wpbc_view_mode_timeline_months_num_in_row']" + ',#' + id + '_wpbc_text_label_timeline' + ',#' + id + '_wpbc_scroll_timeline_scroll_days' + ',#' + id + '_wpbc_scroll_timeline_scroll_month' + ',#' + id + '_wpbc_start_date_timeline_active' + ',#' + id + '_wpbc_start_date_timeline_year' + ',#' + id + '_wpbc_start_date_timeline_month' + ',#' + id + '_wpbc_start_date_timeline_day' + ',#' + id + '_wpbc_start_end_time_timeline_starttime' + ',#' + id + '_wpbc_start_end_time_timeline_endtime' // Form Only
    + ',#' + id + '_wpbc_booking_date_year' + ',#' + id + '_wpbc_booking_date_month' + ',#' + id + '_wpbc_booking_date_day' // [bookingsearch ...]
    + ",input[name='" + id + "_wpbc_search_form_results']" + ',#' + id + '_wpbc_search_new_page_enabled' + ',#' + id + '_wpbc_search_new_page_url' + ',#' + id + '_wpbc_search_header' + ',#' + id + '_wpbc_search_nothing_found' + ',#' + id + '_wpbc_search_for_users' // [bookingother ... ]
    + ",input[name='" + id + "_wpbc_shortcode_type']" + ',#' + id + '_wpbc_resource_show' //booking_import_ics , booking_listing_ics
    + ',#' + id + '_wpbc_url' + ',#' + id + '_from' + ',#' + id + '_from_offset' + ',#' + id + '_from_offset_type' + ',#' + id + '_until' + ',#' + id + '_until_offset' + ',#' + id + '_until_offset_type' + ',#' + id + '_conditions_import' + ',#' + id + '_conditions_events' + ',#' + id + '_conditions_max_num' + ',#' + id + '_silence').on('change', {
      'id': id
    }, function (event) {
      //console.log( 'on change wpbc_set_shortcode', event.data.id );
      wpbc_set_shortcode();
    });
  } // -----------------------------------------------------------------------------------------------------


  wpbc_set_shortcode();
});
//# sourceMappingURL=data:application/json;charset=utf8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbImluY2x1ZGVzL19zaG9ydGNvZGVfcG9wdXAvX3NyYy93cGJjX3Nob3J0Y29kZV9wb3B1cC5qcyJdLCJuYW1lcyI6WyJ3cGJjX3NldF9zaG9ydGNvZGUiLCJ3cGJjX3Nob3J0Y29kZSIsInNob3J0Y29kZV9pZCIsImpRdWVyeSIsInZhbCIsInRyaW0iLCJ3cGJjX29wdGlvbnNfYXJyIiwibGVuZ3RoIiwibXVsdGlwbGVfcmVzb3VyY2VzIiwiZmlsdGVyIiwibiIsInBhcnNlSW50Iiwiam9pbiIsInJlcGxhY2UiLCJ3cGJjX2lzX21hdHJpeF9fdmlld19kYXlzX251bV90ZW1wIiwid3BiY19zaG9ydGNvZGVfY29uZmlnX191cGRhdGVfZWxlbWVudHNfaW5fdGltZWxpbmUiLCJ3cGJjX2lzX21hdHJpeCIsInZpZXdfZGF5c19udW1fdGVtcCIsImhlYWRlcl90aXRsZV90ZW1wIiwiaXMiLCJoaWRlIiwic2hvdyIsInZpZXdfdGltZXNfc3RhcnRfdGVtcCIsInZpZXdfdGltZXNfZW5kX3RlbXAiLCJ3cGJjX3NlbGVjdGVkX2RheSIsIndwYmNfc2VsZWN0ZWRfbW9udGgiLCJ3cGJjX3NlYXJjaF9mb3JtX3Jlc3VsdHMiLCJzZWFyY2hfcmVzdWx0c191cmxfdGVtcCIsInNlYXJjaF9oZWFkZXJfdGVtcCIsIm5vdGhpbmdmb3VuZF90ZW1wIiwib25seV9mb3JfdXNlcnNfdGVtcCIsImJvb2tpbmdvdGhlcl9zaG9ydGNvZGVfdHlwZSIsInNob3J0Y29kZV91cmxfdGVtcCIsInBfZnJvbSIsInBfZnJvbV9vZmZzZXQiLCJpc05hTiIsImNoYXJBdCIsInBfdW50aWwiLCJwX3VudGlsX29mZnNldCIsInBfbWF4IiwicF9pc19hbGxfZGF0ZXNfaW4iLCJwX2ltcG9ydF9jb25kaXRpb25zIiwiZm9ybV90eXBlX3RlbXAiLCJ3cGJjX2FnZ3JlZ2F0ZV90ZW1wIiwicHVzaCIsIndwYmNfb3B0aW9uc19zaXplIiwiTWF0aCIsIm1pbiIsIndwYmNfdGlueV9idG5fY2xpY2siLCJ0YWciLCJ3cGJjX215X21vZGFsIiwia2V5Ym9hcmQiLCJiYWNrZHJvcCIsIndwYmNfdGlueV9jbG9zZSIsIndwYmNfc2VuZF90ZXh0X3RvX2VkaXRvciIsImgiLCJ3cGJjX3NlbmRfdGV4dF90b19ndXRlbmJlcmciLCJpc19zZW5kIiwiZWQiLCJtY2UiLCJ0aW55bWNlIiwicXQiLCJRVGFncyIsIndwQWN0aXZlRWRpdG9yIiwiYWN0aXZlRWRpdG9yIiwiaWQiLCJnZXQiLCJpc0hpZGRlbiIsImlzSUUiLCJ3aW5kb3dNYW5hZ2VyIiwiaW5zZXJ0aW1hZ2Vib29rbWFyayIsInNlbGVjdGlvbiIsIm1vdmVUb0Jvb2ttYXJrIiwiaW5kZXhPZiIsIndwU2V0SW1nQ2FwdGlvbiIsInBsdWdpbnMiLCJ3cGdhbGxlcnkiLCJfZG9fZ2FsbGVyeSIsIndvcmRwcmVzcyIsIl9zZXRFbWJlZCIsImV4ZWNDb21tYW5kIiwiaW5zZXJ0Q29udGVudCIsImRvY3VtZW50IiwiZ2V0RWxlbWVudEJ5SWQiLCJ2YWx1ZSIsInRiX3JlbW92ZSIsImUiLCJ3cGJjX3Jlc291cmNlX3BhZ2VfYnRuX2NsaWNrIiwicmVzb3VyY2VfaWQiLCJzaG9ydGNvZGVfZGVmYXVsdF92YWx1ZSIsInNob3J0Y29kZV9hcnIiLCJzaG9ydGNkZV9rZXkiLCJwcm9wIiwidHJpZ2dlciIsIndwYmNfc2VuZF90ZXh0X3RvX3Jlc291cmNlIiwic2hvcnRjb2RlX3ZhbCIsImh0bWwiLCJ3cGJjX3Njcm9sbF90byIsIndwYmNfc2hvcnRjb2RlX2NvbmZpZ19fcmVzZXQiLCJ3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfd2Vla2RheV9fcmVzZXQiLCJ3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfc2Vhc29uX19yZXNldCIsIndwYmNfc2hvcnRjb2RlX2NvbmZpZ19fc3RhcnRfZGF5X3NlYXNvbl9fcmVzZXQiLCJ3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfZm9yZGF0ZV9fcmVzZXQiLCJEYXRlIiwiZ2V0RnVsbFllYXIiLCJnZXRNb250aCIsImdldERhdGUiLCJ3cGJjX3Nob3J0Y29kZV9jb25maWdfY2xpY2tfc2hvd19zZWN0aW9uIiwiX3RoaXMiLCJzZWN0aW9uX2lkX3RvX3Nob3ciLCJzaG9ydGNvZGVfbmFtZSIsInBhcmVudHMiLCJmaW5kIiwicmVtb3ZlQ2xhc3MiLCJhZGRDbGFzcyIsIndwYmNfc2hvcnRjb2RlX2NvbmZpZ19jb250ZW50X3Rvb2xiYXJfX25leHRfcHJpb3IiLCJzdGVwIiwial93b3JrX25hdl90YWIiLCJzdWJtZW51X3NlbGVjdGVkIiwibmV4dEFsbCIsImZpcnN0IiwicHJldkFsbCIsIndwYmNfc2hvcnRjb2RlX2NvbmZpZ19fc2VsZWN0X2RheV93ZWVrZGF5X19hZGQiLCJjb25kaXRpb25fcnVsZV9hcnIiLCJ3ZWVrZGF5X251bSIsImRheXNfdG9fc2VsZWN0Iiwid3BiY19maWVsZF9oaWdobGlnaHQiLCJjb25kaXRpb25fcnVsZSIsIndwYmNfc2hvcnRjb2RlX2NvbmZpZ19fc2VsZWN0X2RheV9zZWFzb25fX2FkZCIsInNlYXNvbl9maWx0ZXJfbmFtZSIsInRleHQiLCJkYXlzX251bWJlciIsImV4aXN0X2NvbmZpZ3VyYXRpb24iLCJyZXBsYWNlQWxsIiwic3BsaXQiLCJpdGVtIiwicG9zIiwid3BiY19zaG9ydGNvZGVfY29uZmlnX19zdGFydF9kYXlfc2Vhc29uX19hZGQiLCJhY3RpdmF0ZWRfd2Vla2RheXMiLCJ3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfZm9yZGF0ZV9fYWRkIiwic3RhcnRfZGF0ZV9fZm9yZGF0ZSIsImdsb2JhbFJlZ2V4IiwiUmVnRXhwIiwiaXNfdmFsaWRfZGF0ZSIsInRlc3QiLCJib29raW5ndGltZWxpbmVfd3BiY19tdWx0aXBsZV9yZXNvdXJjZXNfdGVtcCIsInJlYWR5Iiwib24iLCJldmVudCIsImRhdGEiXSwibWFwcGluZ3MiOiI7O0FBQUE7QUFDQTtBQUNBO0FBQ0EsU0FBU0Esa0JBQVQsR0FBNkI7QUFFekIsTUFBSUMsY0FBYyxHQUFH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TUEsRUE1RE4sR0E0RFcsbUJBNURYLEdBNkRELElBN0RDLEdBNkRNQSxFQTdETixHQTZEVyxRQTdEWCxHQThERCxJQTlEQyxHQThETUEsRUE5RE4sR0E4RFcsZUE5RFgsR0ErREQsSUEvREMsR0ErRE1BLEVBL0ROLEdBK0RXLG9CQS9EWCxHQWdFRCxJQWhFQyxHQWdFTUEsRUFoRU4sR0FnRVcsb0JBaEVYLEdBaUVELElBakVDLEdBaUVNQSxFQWpFTixHQWlFVyxvQkFqRVgsR0FrRUQsSUFsRUMsR0FrRU1BLEVBbEVOLEdBa0VXLHFCQWxFWCxHQW1FRCxJQW5FQyxHQW1FTUEsRUFuRU4sR0FtRVcsVUFuRWYsQ0FBTixDQW9FTWtGLEVBcEVOLENBb0VVLFFBcEVWLEVBb0VvQjtBQUFDLFlBQU1sRjtBQUFQLEtBcEVwQixFQW9FZ0MsVUFBU21GLEtBQVQsRUFBZTtBQUNuQztBQUNBcEosTUFBQUEsa0JBQWtCO0FBQ3pCLEtBdkVMO0FBd0VILEdBbkdnQyxDQW9HakM7OztBQUNBQSxFQUFBQSxrQkFBa0I7QUFDckIsQ0F0R0QiLCJzb3VyY2VzQ29udGVudCI6WyIvKipcclxuICogU2hvcnRjb2RlIENvbmZpZyAtIE1haW4gTG9vcFxyXG4gKi9cclxuZnVuY3Rpb24gd3BiY19zZXRfc2hvcnRjb2RlKCl7XHJcblxyXG4gICAgdmFyIHdwYmNfc2hvcnRjb2RlID0gJ1snO1xyXG4gICAgdmFyIHNob3J0Y29kZV9pZCA9IGpRdWVyeSggJyN3cGJjX3Nob3J0Y29kZV90eXBlJyApLnZhbCgpLnRyaW0oKTtcclxuXHJcbiAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG4gICAgLy8gW2Jvb2tpbmddICB8IFtib29raW5nY2FsZW5kYXJdIHwgLi4uXHJcbiAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG5cclxuICAgIGlmIChcclxuICAgICAgICAgICAoICdib29raW5nJyA9PT0gc2hvcnRjb2RlX2lkIClcclxuICAgICAgICB8fCAoICdib29raW5nY2FsZW5kYXInID09PSBzaG9ydGNvZGVfaWQgKVxyXG4gICAgICAgIHx8ICggJ2Jvb2tpbmdzZWxlY3QnID09PSBzaG9ydGNvZGVfaWQgKVxyXG4gICAgICAgIHx8ICggJ2Jvb2tpbmd0aW1lbGluZScgPT09IHNob3J0Y29kZV9pZCApXHJcbiAgICAgICAgfHwgKCAnYm9va2luZ2Zvcm0nID09PSBzaG9ydGNvZGVfaWQgKVxyXG4gICAgICAgIHx8ICggJ2Jvb2tpbmdzZWFyY2gnID09PSBzaG9ydGNvZGVfaWQgKVxyXG4gICAgICAgIHx8ICggJ2Jvb2tpbmdvdGhlcicgPT09IHNob3J0Y29kZV9pZCApXHJcblxyXG4gICAgICAgIHx8ICggJ2Jvb2tpbmdfaW1wb3J0X2ljcycgPT09IHNob3J0Y29kZV9pZCApXHJcbiAgICAgICAgfHwgKCAnYm9va2luZ19saXN0aW5nX2ljcycgPT09IHNob3J0Y29kZV9pZCApXHJcbiAgICApe1xyXG5cclxuICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSBzaG9ydGNvZGVfaWQ7XHJcblxyXG4gICAgICAgIHZhciB3cGJjX29wdGlvbnNfYXJyID0gW107XHJcblxyXG4gICAgICAgIC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuICAgICAgICAvLyBbYm9va2luZ3NlbGVjdF0gfCBbYm9va2luZ3RpbWVsaW5lXSAtIE9wdGlvbnMgcmVsYXRpdmUgb25seSB0byB0aGlzIHNob3J0Y29kZS5cclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgaWYgKFxyXG4gICAgICAgICAgICAgICAoICdib29raW5nc2VsZWN0JyA9PT0gc2hvcnRjb2RlX2lkIClcclxuICAgICAgICAgICAgfHwgKCAnYm9va2luZ3RpbWVsaW5lJyA9PT0gc2hvcnRjb2RlX2lkIClcclxuICAgICAgICApe1xyXG5cclxuICAgICAgICAgICAgLy8gW2Jvb2tpbmdzZWxlY3QgdHlwZT0nMSwyLDMnXSAtIE11bHRpcGxlIFJlc291cmNlc1xyXG4gICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX211bHRpcGxlX3Jlc291cmNlcycgKS5sZW5ndGggPiAwICl7XHJcblxyXG4gICAgICAgICAgICAgICAgdmFyIG11bHRpcGxlX3Jlc291cmNlcyA9IGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX211bHRpcGxlX3Jlc291cmNlcycgKS52YWwoKTtcclxuXHJcbiAgICAgICAgICAgICAgICBpZiAoIChtdWx0aXBsZV9yZXNvdXJjZXMgIT0gbnVsbCkgJiYgKG11bHRpcGxlX3Jlc291cmNlcy5sZW5ndGggPiAwKSApe1xyXG5cclxuICAgICAgICAgICAgICAgICAgICAvLyBSZW1vdmUgZW1wdHkgc3BhY2VzIGZyb20gIGFycmF5IDogJycgfCBcIlwiIHwgMFxyXG4gICAgICAgICAgICAgICAgICAgIG11bHRpcGxlX3Jlc291cmNlcyA9IG11bHRpcGxlX3Jlc291cmNlcy5maWx0ZXIoZnVuY3Rpb24obil7cmV0dXJuIHBhcnNlSW50KG4pOyB9KTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgbXVsdGlwbGVfcmVzb3VyY2VzID0gbXVsdGlwbGVfcmVzb3VyY2VzLmpvaW4oICcsJyApLnRyaW0oKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCBtdWx0aXBsZV9yZXNvdXJjZXMgIT0gMCApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIHR5cGU9XFwnJyArIG11bHRpcGxlX3Jlc291cmNlcyArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gW2Jvb2tpbmdzZWxlY3Qgc2VsZWN0ZWRfdHlwZT0xXSAtIFNlbGVjdGVkIFJlc291cmNlXHJcbiAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2VsZWN0ZWRfcmVzb3VyY2UnICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgaWYgKFxyXG4gICAgICAgICAgICAgICAgICAgICAgICggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2VsZWN0ZWRfcmVzb3VyY2UnICkudmFsKCkgIT09IG51bGwgKSAgICAgICAgICAgICAgICAgICAgICAvL0ZpeEluOiA4LjIuMS4xMlxyXG4gICAgICAgICAgICAgICAgICAgICYmICggcGFyc2VJbnQoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3NlbGVjdGVkX3Jlc291cmNlJyApLnZhbCgpICkgPiAwIClcclxuICAgICAgICAgICAgICAgICl7XHJcbiAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBzZWxlY3RlZF90eXBlPScgKyBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zZWxlY3RlZF9yZXNvdXJjZScgKS52YWwoKS50cmltKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIC8vIFtib29raW5nc2VsZWN0IGxhYmVsPSdUYWRhJ10gLSBMYWJlbFxyXG4gICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3RleHRfbGFiZWwnICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgaWYgKCAnJyAhPT0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfdGV4dF9sYWJlbCcgKS52YWwoKS50cmltKCkgKXtcclxuICAgICAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIGxhYmVsPVxcJycgKyBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY190ZXh0X2xhYmVsJyApLnZhbCgpLnRyaW0oKS5yZXBsYWNlKCAvJy9naSwgJycgKSArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAvLyBbYm9va2luZ3NlbGVjdCBmaXJzdF9vcHRpb25fdGl0bGU9J1RhZGEnXSAtIEZpcnN0ICBPcHRpb25cclxuICAgICAgICAgICAgaWYgKCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19maXJzdF9vcHRpb25fdGl0bGUnICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgaWYgKCAnJyAhPT0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfZmlyc3Rfb3B0aW9uX3RpdGxlJyApLnZhbCgpLnRyaW0oKSApe1xyXG4gICAgICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgZmlyc3Rfb3B0aW9uX3RpdGxlPVxcJycgKyBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19maXJzdF9vcHRpb25fdGl0bGUnICkudmFsKCkudHJpbSgpLnJlcGxhY2UoIC8nL2dpLCAnJyApICsgJ1xcJyc7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG5cclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgLy8gW2Jvb2tpbmd0aW1lbGluZV0gLSBPcHRpb25zIHJlbGF0aXZlIG9ubHkgdG8gdGhpcyBzaG9ydGNvZGUuXHJcbiAgICAgICAgLy8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG4gICAgICAgIGlmICggJ2Jvb2tpbmd0aW1lbGluZScgPT09IHNob3J0Y29kZV9pZCApe1xyXG4gICAgICAgICAgICAvLyBWaXN1YWxseSB1cGRhdGVcclxuICAgICAgICAgICAgdmFyIHdwYmNfaXNfbWF0cml4X192aWV3X2RheXNfbnVtX3RlbXAgPSB3cGJjX3Nob3J0Y29kZV9jb25maWdfX3VwZGF0ZV9lbGVtZW50c19pbl90aW1lbGluZSgpO1xyXG4gICAgICAgICAgICB2YXIgd3BiY19pc19tYXRyaXggPSB3cGJjX2lzX21hdHJpeF9fdmlld19kYXlzX251bV90ZW1wWyAwIF07XHJcbiAgICAgICAgICAgIHZhciB2aWV3X2RheXNfbnVtX3RlbXAgPSB3cGJjX2lzX21hdHJpeF9fdmlld19kYXlzX251bV90ZW1wWyAxIF07XHJcblxyXG4gICAgICAgICAgICAvLyA6IHZpZXdfZGF5c19udW1cclxuICAgICAgICAgICAgaWYgKCB2aWV3X2RheXNfbnVtX3RlbXAgIT0gMzAgKXtcclxuICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgdmlld19kYXlzX251bT0nICsgdmlld19kYXlzX251bV90ZW1wO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIDogaGVhZGVyX3RpdGxlXHJcbiAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfdGV4dF9sYWJlbF90aW1lbGluZScgKS5sZW5ndGggPiAwICl7XHJcbiAgICAgICAgICAgICAgICB2YXIgaGVhZGVyX3RpdGxlX3RlbXAgPSBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY190ZXh0X2xhYmVsX3RpbWVsaW5lJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgICAgIGhlYWRlcl90aXRsZV90ZW1wID0gaGVhZGVyX3RpdGxlX3RlbXAucmVwbGFjZSggLycvZ2ksICcnICk7XHJcbiAgICAgICAgICAgICAgICBpZiAoIGhlYWRlcl90aXRsZV90ZW1wICE9ICcnICl7XHJcbiAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBoZWFkZXJfdGl0bGU9XFwnJyArIGhlYWRlcl90aXRsZV90ZW1wICsgJ1xcJyc7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gOiBzY3JvbGxfbW9udGhcclxuICAgICAgICAgICAgaWYgKFxyXG4gICAgICAgICAgICAgICAgICAgKCAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3Njcm9sbF90aW1lbGluZV9zY3JvbGxfbW9udGgnICkuaXMoICc6dmlzaWJsZScgKSlcclxuICAgICAgICAgICAgICAgICYmICggICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zY3JvbGxfdGltZWxpbmVfc2Nyb2xsX21vbnRoJyApLmxlbmd0aCA+IDApXHJcbiAgICAgICAgICAgICAgICAmJiAocGFyc2VJbnQoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3Njcm9sbF90aW1lbGluZV9zY3JvbGxfbW9udGgnICkudmFsKCkudHJpbSgpICkgIT09IDApXHJcbiAgICAgICAgICAgICl7XHJcbiAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIHNjcm9sbF9tb250aD0nICsgcGFyc2VJbnQoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3Njcm9sbF90aW1lbGluZV9zY3JvbGxfbW9udGgnICkudmFsKCkudHJpbSgpICk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgLy8gOiBzY3JvbGxfZGF5XHJcbiAgICAgICAgICAgIGlmIChcclxuICAgICAgICAgICAgICAgICAgICggICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zY3JvbGxfdGltZWxpbmVfc2Nyb2xsX2RheXMnICkuaXMoICc6dmlzaWJsZScgKSlcclxuICAgICAgICAgICAgICAgICYmICggICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zY3JvbGxfdGltZWxpbmVfc2Nyb2xsX2RheXMnICkubGVuZ3RoID4gMClcclxuICAgICAgICAgICAgICAgICYmIChwYXJzZUludCggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2Nyb2xsX3RpbWVsaW5lX3Njcm9sbF9kYXlzJyApLnZhbCgpLnRyaW0oKSApICE9PSAwKVxyXG4gICAgICAgICAgICApe1xyXG4gICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBzY3JvbGxfZGF5PScgKyBwYXJzZUludCggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2Nyb2xsX3RpbWVsaW5lX3Njcm9sbF9kYXlzJyApLnZhbCgpLnRyaW0oKSApO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAvLyA6bGltaXRfaG91cnNcclxuICAgICAgICAgICAgLy9GaXhJbjogNy4wLjEuMTdcclxuICAgICAgICAgICAgalF1ZXJ5KCAnLmJvb2tpbmd0aW1lbGluZV92aWV3X3RpbWVzJyApLmhpZGUoKTtcclxuICAgICAgICAgICAgaWYgKFxyXG4gICAgICAgICAgICAgICAgICAgKCAoIHdwYmNfaXNfbWF0cml4ICkgJiYgKCB2aWV3X2RheXNfbnVtX3RlbXAgPT0gMSApIClcclxuICAgICAgICAgICAgICAgIHx8ICggKCAhIHdwYmNfaXNfbWF0cml4ICkgJiYgKCB2aWV3X2RheXNfbnVtX3RlbXAgPT0gMzAgKSApXHJcbiAgICAgICAgICAgICkge1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnLmJvb2tpbmd0aW1lbGluZV92aWV3X3RpbWVzJyApLnNob3coKTtcclxuICAgICAgICAgICAgICAgIHZhciB2aWV3X3RpbWVzX3N0YXJ0X3RlbXAgPSBwYXJzZUludCggalF1ZXJ5KCAnI2Jvb2tpbmd0aW1lbGluZV93cGJjX3N0YXJ0X2VuZF90aW1lX3RpbWVsaW5lX3N0YXJ0dGltZScgKS52YWwoKS50cmltKCkgKTtcclxuICAgICAgICAgICAgICAgIHZhciB2aWV3X3RpbWVzX2VuZF90ZW1wID0gcGFyc2VJbnQoIGpRdWVyeSggJyNib29raW5ndGltZWxpbmVfd3BiY19zdGFydF9lbmRfdGltZV90aW1lbGluZV9lbmR0aW1lJyApLnZhbCgpLnRyaW0oKSApO1xyXG4gICAgICAgICAgICAgICAgaWYgKCAodmlld190aW1lc19zdGFydF90ZW1wICE9IDApIHx8ICh2aWV3X3RpbWVzX2VuZF90ZW1wICE9IDI0KSApe1xyXG4gICAgICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgbGltaXRfaG91cnM9XFwnJyArIHZpZXdfdGltZXNfc3RhcnRfdGVtcCArICcsJyArIHZpZXdfdGltZXNfZW5kX3RlbXAgKyAnXFwnJztcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gOnNjcm9sbF9zdGFydF9kYXRlXHJcbiAgICAgICAgICAgIGlmICggICggalF1ZXJ5KCcjYm9va2luZ3RpbWVsaW5lX3dwYmNfc3RhcnRfZGF0ZV90aW1lbGluZV9hY3RpdmUnKS5pcygnOmNoZWNrZWQnKSApICAmJiAoIGpRdWVyeSggJyNib29raW5ndGltZWxpbmVfd3BiY19zdGFydF9kYXRlX3RpbWVsaW5lX2FjdGl2ZScgKS5sZW5ndGggPiAwICkgICkge1xyXG4gICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgc2Nyb2xsX3N0YXJ0X2RhdGU9XFwnJyArIGpRdWVyeSggJyNib29raW5ndGltZWxpbmVfd3BiY19zdGFydF9kYXRlX3RpbWVsaW5lX3llYXInICkudmFsKCkudHJpbSgpXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgKyAnLScgKyBqUXVlcnkoICcjYm9va2luZ3RpbWVsaW5lX3dwYmNfc3RhcnRfZGF0ZV90aW1lbGluZV9tb250aCcgKS52YWwoKS50cmltKClcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICArICctJyArIGpRdWVyeSggJyNib29raW5ndGltZWxpbmVfd3BiY19zdGFydF9kYXRlX3RpbWVsaW5lX2RheScgKS52YWwoKS50cmltKClcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICsgJ1xcJyc7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgLy8gW2Jvb2tpbmdmb3JtICBdIC0gRm9ybSBPbmx5ICAgICAgICAtICAgICBbYm9va2luZ2Zvcm0gdHlwZT0xIHNlbGVjdGVkX2RhdGVzPScwMS4wMy4yMDI0J11cclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgaWYgKCAnYm9va2luZ2Zvcm0nID09PSBzaG9ydGNvZGVfaWQgKXtcclxuXHJcbiAgICAgICAgICAgIHZhciB3cGJjX3NlbGVjdGVkX2RheSA9IGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX2Jvb2tpbmdfZGF0ZV9kYXknICkudmFsKCkudHJpbSgpO1xyXG4gICAgICAgICAgICBpZiAoIHBhcnNlSW50KHdwYmNfc2VsZWN0ZWRfZGF5KSA8IDEwICl7XHJcbiAgICAgICAgICAgICAgICB3cGJjX3NlbGVjdGVkX2RheSA9ICcwJyArIHdwYmNfc2VsZWN0ZWRfZGF5O1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIHZhciB3cGJjX3NlbGVjdGVkX21vbnRoID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfYm9va2luZ19kYXRlX21vbnRoJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgaWYgKCBwYXJzZUludCh3cGJjX3NlbGVjdGVkX21vbnRoKSA8IDEwICl7XHJcbiAgICAgICAgICAgICAgICB3cGJjX3NlbGVjdGVkX21vbnRoID0gJzAnICsgd3BiY19zZWxlY3RlZF9tb250aDtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIHNlbGVjdGVkX2RhdGVzPVxcJycgKyB3cGJjX3NlbGVjdGVkX2RheSArICcuJyArIHdwYmNfc2VsZWN0ZWRfbW9udGggKyAnLicgKyBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19ib29raW5nX2RhdGVfeWVhcicgKS52YWwoKS50cmltKCkgKyAnXFwnJztcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuICAgICAgICAvLyBbYm9va2luZ3NlYXJjaCAgXSAtIE9wdGlvbnMgcmVsYXRpdmUgb25seSB0byB0aGlzIHNob3J0Y29kZS4gICAgIFtib29raW5nc2VhcmNoIHNlYXJjaHJlc3VsdHN0aXRsZT0ne3NlYXJjaHJlc3VsdHN9IFJlc3VsdChzKSBGb3VuZCcgbm9yZXN1bHRzdGl0bGU9J05vdGhpbmcgRm91bmQnXVxyXG4gICAgICAgIC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuICAgICAgICBpZiAoICdib29raW5nc2VhcmNoJyA9PT0gc2hvcnRjb2RlX2lkICl7XHJcblxyXG4gICAgICAgICAgICAvLyBDaGVjayAgaWYgd2Ugc2VsZWN0ZWQgJ2Jvb2tpbmdzZWFyY2gnIHwgJ2Jvb2tpbmdzZWFyY2hyZXN1bHRzJ1xyXG4gICAgICAgICAgICB2YXIgd3BiY19zZWFyY2hfZm9ybV9yZXN1bHRzID0gJ2Jvb2tpbmdzZWFyY2gnO1xyXG4gICAgICAgICAgICBpZiAoIGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5nc2VhcmNoX3dwYmNfc2VhcmNoX2Zvcm1fcmVzdWx0cyddOmNoZWNrZWRcIiApLmxlbmd0aCA+IDAgKXtcclxuICAgICAgICAgICAgICAgIHdwYmNfc2VhcmNoX2Zvcm1fcmVzdWx0cyA9IGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5nc2VhcmNoX3dwYmNfc2VhcmNoX2Zvcm1fcmVzdWx0cyddOmNoZWNrZWRcIiApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gU2hvdyB8IEhpZGUgZm9ybSAgZmllbGRzIGZvciAnYm9va2luZ3NlYXJjaCcgZGVwZW5kcyBmcm9tICByYWRpbyAgYnV0aW9uICBzZWxlY3Rpb25cclxuICAgICAgICAgICAgaWYgKCAnYm9va2luZ3NlYXJjaHJlc3VsdHMnID09PSB3cGJjX3NlYXJjaF9mb3JtX3Jlc3VsdHMgKXtcclxuICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlID0gJ1tib29raW5nc2VhcmNocmVzdWx0cyc7XHJcbiAgICAgICAgICAgICAgICBqUXVlcnkoICcud3BiY19zZWFyY2hfYXZhaWxhYmlsaXR5X2Zvcm0nICkuaGlkZSgpO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnLndwYmNfc2VhcmNoX2F2YWlsYWJpbGl0eV9mb3JtJyApLnNob3coKTtcclxuXHJcblxyXG4gICAgICAgICAgICAgICAgLy8gTmV3IHBhZ2UgZm9yIHNlYXJjaCByZXN1bHRzXHJcbiAgICAgICAgICAgICAgICBpZiAoXHJcbiAgICAgICAgICAgICAgICAgICAgKGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3NlYXJjaF9uZXdfcGFnZV9lbmFibGVkJyApLmxlbmd0aCA+IDApXHJcbiAgICAgICAgICAgICAgICAgICAgJiYgKGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3NlYXJjaF9uZXdfcGFnZV9lbmFibGVkJyApLmlzKCAnOmNoZWNrZWQnICkpXHJcbiAgICAgICAgICAgICAgICApe1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIFNob3dcclxuICAgICAgICAgICAgICAgICAgICBqUXVlcnkoICcuJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zZWFyY2hfbmV3X3BhZ2Vfd3BiY19zY19zZWFyY2hyZXN1bHRzX25ld19wYWdlJyApLnNob3coKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gOiBTZWFyY2ggUmVzdWx0cyBVUkxcclxuICAgICAgICAgICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3NlYXJjaF9uZXdfcGFnZV91cmwnICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB2YXIgc2VhcmNoX3Jlc3VsdHNfdXJsX3RlbXAgPSBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zZWFyY2hfbmV3X3BhZ2VfdXJsJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgc2VhcmNoX3Jlc3VsdHNfdXJsX3RlbXAgPSBzZWFyY2hfcmVzdWx0c191cmxfdGVtcC5yZXBsYWNlKCAvJy9naSwgJycgKTtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCBzZWFyY2hfcmVzdWx0c191cmxfdGVtcCAhPSAnJyApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBzZWFyY2hyZXN1bHRzPVxcJycgKyBzZWFyY2hfcmVzdWx0c191cmxfdGVtcCArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAvLyBIaWRlXHJcbiAgICAgICAgICAgICAgICAgICAgalF1ZXJ5KCAnLicgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2VhcmNoX25ld19wYWdlX3dwYmNfc2Nfc2VhcmNocmVzdWx0c19uZXdfcGFnZScgKS5oaWRlKCk7XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgLy8gOiBTZWFyY2ggSGVhZGVyXHJcbiAgICAgICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3NlYXJjaF9oZWFkZXInICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBzZWFyY2hfaGVhZGVyX3RlbXAgPSBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zZWFyY2hfaGVhZGVyJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgICAgICAgICBzZWFyY2hfaGVhZGVyX3RlbXAgPSBzZWFyY2hfaGVhZGVyX3RlbXAucmVwbGFjZSggLycvZ2ksICcnICk7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCBzZWFyY2hfaGVhZGVyX3RlbXAgIT0gJycgKXtcclxuICAgICAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBzZWFyY2hyZXN1bHRzdGl0bGU9XFwnJyArIHNlYXJjaF9oZWFkZXJfdGVtcCArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIC8vIDogTm90aGluZyBGb3VuZFxyXG4gICAgICAgICAgICAgICAgaWYgKCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zZWFyY2hfbm90aGluZ19mb3VuZCcgKS5sZW5ndGggPiAwICl7XHJcbiAgICAgICAgICAgICAgICAgICAgdmFyIG5vdGhpbmdmb3VuZF90ZW1wID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2VhcmNoX25vdGhpbmdfZm91bmQnICkudmFsKCkudHJpbSgpO1xyXG4gICAgICAgICAgICAgICAgICAgIG5vdGhpbmdmb3VuZF90ZW1wID0gbm90aGluZ2ZvdW5kX3RlbXAucmVwbGFjZSggLycvZ2ksICcnICk7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCBub3RoaW5nZm91bmRfdGVtcCAhPSAnJyApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIG5vcmVzdWx0c3RpdGxlPVxcJycgKyBub3RoaW5nZm91bmRfdGVtcCArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIC8vIDogVXNlcnMgICAgICAvLyBbYm9va2luZ3NlYXJjaCBzZWFyY2hyZXN1bHRzdGl0bGU9J3tzZWFyY2hyZXN1bHRzfSBSZXN1bHQocykgRm91bmQnIG5vcmVzdWx0c3RpdGxlPSdOb3RoaW5nIEZvdW5kJyB1c2Vycz0nMyw0NTQzLCddXHJcbiAgICAgICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3NlYXJjaF9mb3JfdXNlcnMnICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBvbmx5X2Zvcl91c2Vyc190ZW1wID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2VhcmNoX2Zvcl91c2VycycgKS52YWwoKS50cmltKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgb25seV9mb3JfdXNlcnNfdGVtcCA9IG9ubHlfZm9yX3VzZXJzX3RlbXAucmVwbGFjZSggLycvZ2ksICcnICk7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCBvbmx5X2Zvcl91c2Vyc190ZW1wICE9ICcnICl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgdXNlcnM9XFwnJyArIG9ubHlfZm9yX3VzZXJzX3RlbXAgKyAnXFwnJztcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuXHJcbiAgICAgICAgLy8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG4gICAgICAgIC8vIFtib29raW5nZWRpdF0gLCBbYm9va2luZ2N1c3RvbWVybGlzdGluZ10gLCBbYm9va2luZ3Jlc291cmNlIHR5cGU9NiBzaG93PSdjYXBhY2l0eSddICwgW2Jvb2tpbmdfY29uZmlybV1cclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgaWYgKCAnYm9va2luZ290aGVyJyA9PT0gc2hvcnRjb2RlX2lkICl7XHJcblxyXG4gICAgICAgICAgICAvL1RSSUNLOlxyXG4gICAgICAgICAgICBzaG9ydGNvZGVfaWQgPSAnbm8nOyAgLy9yZXF1aXJlZCBmb3Igbm90IHVwZGF0ZSBib29raW5nIHJlc291cmNlIElEXHJcblxyXG4gICAgICAgICAgICAvLyBDaGVjayAgaWYgd2Ugc2VsZWN0ZWQgJ2Jvb2tpbmdzZWFyY2gnIHwgJ2Jvb2tpbmdzZWFyY2hyZXN1bHRzJ1xyXG4gICAgICAgICAgICB2YXIgYm9va2luZ290aGVyX3Nob3J0Y29kZV90eXBlID0gJ2Jvb2tpbmdzZWFyY2gnO1xyXG4gICAgICAgICAgICBpZiAoIGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5nb3RoZXJfd3BiY19zaG9ydGNvZGVfdHlwZSddOmNoZWNrZWRcIiApLmxlbmd0aCA+IDAgKXtcclxuICAgICAgICAgICAgICAgIGJvb2tpbmdvdGhlcl9zaG9ydGNvZGVfdHlwZSA9IGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5nb3RoZXJfd3BiY19zaG9ydGNvZGVfdHlwZSddOmNoZWNrZWRcIiApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8gU2hvdyB8IEhpZGUgc2VjdGlvbnNcclxuICAgICAgICAgICAgaWYgKCAnYm9va2luZ19jb25maXJtJyA9PT0gYm9va2luZ290aGVyX3Nob3J0Y29kZV90eXBlICl7XHJcbiAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSA9ICdbYm9va2luZ19jb25maXJtJztcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggJy5ib29raW5nb3RoZXJfc2VjdGlvbl9hZGRpdGlvbmFsJyApLmhpZGUoKTtcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggJy5ib29raW5nb3RoZXJfc2VjdGlvbl8nICsgYm9va2luZ290aGVyX3Nob3J0Y29kZV90eXBlICkuc2hvdygpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmICggJ2Jvb2tpbmdlZGl0JyA9PT0gYm9va2luZ290aGVyX3Nob3J0Y29kZV90eXBlICl7XHJcbiAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSA9ICdbYm9va2luZ2VkaXQnO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnLmJvb2tpbmdvdGhlcl9zZWN0aW9uX2FkZGl0aW9uYWwnICkuaGlkZSgpO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnLmJvb2tpbmdvdGhlcl9zZWN0aW9uXycgKyBib29raW5nb3RoZXJfc2hvcnRjb2RlX3R5cGUgKS5zaG93KCk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYgKCAnYm9va2luZ2N1c3RvbWVybGlzdGluZycgPT09IGJvb2tpbmdvdGhlcl9zaG9ydGNvZGVfdHlwZSApe1xyXG4gICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgPSAnW2Jvb2tpbmdjdXN0b21lcmxpc3RpbmcnO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnLmJvb2tpbmdvdGhlcl9zZWN0aW9uX2FkZGl0aW9uYWwnICkuaGlkZSgpO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnLmJvb2tpbmdvdGhlcl9zZWN0aW9uXycgKyBib29raW5nb3RoZXJfc2hvcnRjb2RlX3R5cGUgKS5zaG93KCk7XHJcblxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmICggJ2Jvb2tpbmdyZXNvdXJjZScgPT09IGJvb2tpbmdvdGhlcl9zaG9ydGNvZGVfdHlwZSApe1xyXG5cclxuICAgICAgICAgICAgICAgIC8vVFJJQ0s6XHJcbiAgICAgICAgICAgICAgICBzaG9ydGNvZGVfaWQgPSAnYm9va2luZ290aGVyJzsgIC8vcmVxdWlyZWQgdG8gZm9yY2UgdXBkYXRlIGJvb2tpbmcgcmVzb3VyY2UgSURcclxuXHJcbiAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSA9ICdbYm9va2luZ3Jlc291cmNlJztcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggJy5ib29raW5nb3RoZXJfc2VjdGlvbl9hZGRpdGlvbmFsJyApLmhpZGUoKTtcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggJy5ib29raW5nb3RoZXJfc2VjdGlvbl8nICsgYm9va2luZ290aGVyX3Nob3J0Y29kZV90eXBlICkuc2hvdygpO1xyXG5cclxuICAgICAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnI2Jvb2tpbmdvdGhlcl93cGJjX3Jlc291cmNlX3Nob3cnICkudmFsKCkudHJpbSgpICE9ICd0aXRsZScgKXtcclxuICAgICAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIHNob3c9XFwnJyArIGpRdWVyeSggJyNib29raW5nb3RoZXJfd3BiY19yZXNvdXJjZV9zaG93JyApLnZhbCgpLnRyaW0oKSArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyBbYm9va2luZy1tYW5hZ2VyLWltcG9ydCAuLi5dICAgICB8fCAgICAgIFtib29raW5nLW1hbmFnZXItbGlzdGluZyAuLi5dXHJcbiAgICAgICAgaWYgKCAoJ2Jvb2tpbmdfaW1wb3J0X2ljcycgPT09IHNob3J0Y29kZV9pZCkgfHwgKCdib29raW5nX2xpc3RpbmdfaWNzJyA9PT0gc2hvcnRjb2RlX2lkKSApe1xyXG5cclxuICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgPSAnW2Jvb2tpbmctbWFuYWdlci1pbXBvcnQnO1xyXG5cclxuICAgICAgICAgICAgaWYgKCAnYm9va2luZ19saXN0aW5nX2ljcycgPT09IHNob3J0Y29kZV9pZCApe1xyXG4gICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgPSAnW2Jvb2tpbmctbWFuYWdlci1saXN0aW5nJztcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xyXG4gICAgICAgICAgICAvLyA6IC5pY3MgZmVlZCBVUkxcclxuICAgICAgICAgICAgLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xyXG4gICAgICAgICAgICB2YXIgc2hvcnRjb2RlX3VybF90ZW1wID0gJydcclxuICAgICAgICAgICAgaWYgKCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY191cmwnICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgc2hvcnRjb2RlX3VybF90ZW1wID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfdXJsJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgICAgIHNob3J0Y29kZV91cmxfdGVtcCA9IHNob3J0Y29kZV91cmxfdGVtcC5yZXBsYWNlKCAvJy9naSwgJycgKTtcclxuICAgICAgICAgICAgICAgIGlmICggc2hvcnRjb2RlX3VybF90ZW1wICE9ICcnICl7XHJcbiAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyB1cmw9XFwnJyArIHNob3J0Y29kZV91cmxfdGVtcCArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcblxyXG5cclxuICAgICAgICAgICAgaWYgKCBzaG9ydGNvZGVfdXJsX3RlbXAgPT0gJycgKXtcclxuICAgICAgICAgICAgICAgIC8vIEVycm9yOlxyXG4gICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgPSAnWyBVUkwgaXMgcmVxdWlyZWQgJ1xyXG5cclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIC8vIFZBTElEOlxyXG5cclxuICAgICAgICAgICAgICAgIC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy9cclxuICAgICAgICAgICAgICAgIC8vIFsuLi4gZnJvbT0nJyAnZnJvbV9vZmZzZXQ9JycgIC4uLl1cclxuICAgICAgICAgICAgICAgIC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy9cclxuICAgICAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX2Zyb20nICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBwX2Zyb20gICAgICAgICAgPSBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfZnJvbScgKS52YWwoKS50cmltKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgdmFyIHBfZnJvbV9vZmZzZXQgICA9IGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ19mcm9tX29mZnNldCcgKS52YWwoKS50cmltKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIHBfZnJvbSAgICAgICAgPSBwX2Zyb20ucmVwbGFjZSggLycvZ2ksICcnICk7XHJcbiAgICAgICAgICAgICAgICAgICAgcF9mcm9tX29mZnNldCA9IHBfZnJvbV9vZmZzZXQucmVwbGFjZSggLycvZ2ksICcnICk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGlmICggKCcnICE9IHBfZnJvbSkgJiYgKCdkYXRlJyAhPSBwX2Zyb20pICl7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBPZmZzZXRcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgZnJvbT1cXCcnICsgcF9mcm9tICsgJ1xcJyc7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoICgnYW55JyAhPSBwX2Zyb20pICYmICgnJyAhPSBwX2Zyb21fb2Zmc2V0KSApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgcF9mcm9tX29mZnNldCA9IHBhcnNlSW50KCBwX2Zyb21fb2Zmc2V0ICk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoICFpc05hTiggcF9mcm9tX29mZnNldCApICl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBmcm9tX29mZnNldD1cXCcnICsgcF9mcm9tX29mZnNldCArIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ19mcm9tX29mZnNldF90eXBlJyApLnZhbCgpLnRyaW0oKS5jaGFyQXQoIDAgKSArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoIChwX2Zyb20gPT0gJ2RhdGUnKSAmJiAocF9mcm9tX29mZnNldCAhPSAnJykgKXtcdFx0ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gSWYgc2VsZWN0ZWQgRGF0ZVxyXG4gICAgICAgICAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIGZyb209XFwnJyArIHBfZnJvbV9vZmZzZXQgKyAnXFwnJztcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xyXG4gICAgICAgICAgICAgICAgLy8gWy4uLiB1bnRpbD0nJyAndW50aWxfb2Zmc2V0PScnICAuLi5dXHJcbiAgICAgICAgICAgICAgICAvLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXHJcbiAgICAgICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ191bnRpbCcgKS5sZW5ndGggPiAwICl7XHJcbiAgICAgICAgICAgICAgICAgICAgdmFyIHBfdW50aWwgICAgICAgICAgPSBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfdW50aWwnICkudmFsKCkudHJpbSgpO1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBwX3VudGlsX29mZnNldCAgID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3VudGlsX29mZnNldCcgKS52YWwoKS50cmltKCk7XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIHBfdW50aWwgICAgICAgID0gcF91bnRpbC5yZXBsYWNlKCAvJy9naSwgJycgKTtcclxuICAgICAgICAgICAgICAgICAgICBwX3VudGlsX29mZnNldCA9IHBfdW50aWxfb2Zmc2V0LnJlcGxhY2UoIC8nL2dpLCAnJyApO1xyXG5cclxuICAgICAgICAgICAgICAgICAgICBpZiAoICgnJyAhPSBwX3VudGlsKSAmJiAoJ2RhdGUnICE9IHBfdW50aWwpICl7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBPZmZzZXRcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgdW50aWw9XFwnJyArIHBfdW50aWwgKyAnXFwnJztcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICggKCdhbnknICE9IHBfdW50aWwpICYmICgnJyAhPSBwX3VudGlsX29mZnNldCkgKXtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHBfdW50aWxfb2Zmc2V0ID0gcGFyc2VJbnQoIHBfdW50aWxfb2Zmc2V0ICk7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoICFpc05hTiggcF91bnRpbF9vZmZzZXQgKSApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgdW50aWxfb2Zmc2V0PVxcJycgKyBwX3VudGlsX29mZnNldCArIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ191bnRpbF9vZmZzZXRfdHlwZScgKS52YWwoKS50cmltKCkuY2hhckF0KCAwICkgKyAnXFwnJztcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2UgaWYgKCAocF91bnRpbCA9PSAnZGF0ZScpICYmIChwX3VudGlsX29mZnNldCAhPSAnJykgKXtcdFx0ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gSWYgc2VsZWN0ZWQgRGF0ZVxyXG4gICAgICAgICAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIHVudGlsPVxcJycgKyBwX3VudGlsX29mZnNldCArICdcXCcnO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuXHJcblx0XHRcdFx0Ly8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xyXG5cdFx0XHRcdC8vIE1heFxyXG5cdFx0XHRcdC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy9cclxuICAgICAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX2NvbmRpdGlvbnNfbWF4X251bScgKS5sZW5ndGggPiAwICl7XHJcbiAgICAgICAgICAgICAgICAgICAgdmFyIHBfbWF4ID0gcGFyc2VJbnQoIGpRdWVyeSggICcjJyArIHNob3J0Y29kZV9pZCArICdfY29uZGl0aW9uc19tYXhfbnVtJyApLnZhbCgpLnRyaW0oKSApO1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICggcF9tYXggIT0gMCApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIG1heD0nICsgcF9tYXg7XHJcbiAgICAgICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAgICAgfVxyXG5cclxuXHRcdFx0XHQvLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXHJcblx0XHRcdFx0Ly8gU2lsZW5jZVxyXG5cdFx0XHRcdC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy9cclxuICAgICAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3NpbGVuY2UnICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgICAgIGlmICggJzEnID09PSBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfc2lsZW5jZScgKS52YWwoKS50cmltKCkgKXtcclxuICAgICAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBzaWxlbmNlPTEnO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuXHJcblx0XHRcdFx0Ly8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xyXG5cdFx0XHRcdC8vIGlzX2FsbF9kYXRlc19pblxyXG5cdFx0XHRcdC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy9cclxuICAgICAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX2NvbmRpdGlvbnNfZXZlbnRzJyApLmxlbmd0aCA+IDAgKXtcclxuICAgICAgICAgICAgICAgICAgICB2YXIgcF9pc19hbGxfZGF0ZXNfaW4gPSBwYXJzZUludCggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX2NvbmRpdGlvbnNfZXZlbnRzJyAgKS52YWwoKS50cmltKCkgKTtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoIHBfaXNfYWxsX2RhdGVzX2luICE9IDAgKXtcclxuICAgICAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBpc19hbGxfZGF0ZXNfaW49JyArIHBfaXNfYWxsX2RhdGVzX2luO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuXHJcblx0XHRcdFx0Ly8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xyXG5cdFx0XHRcdC8vIGltcG9ydF9jb25kaXRpb25zXHJcblx0XHRcdFx0Ly8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xyXG4gICAgICAgICAgICAgICAgaWYgKCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfY29uZGl0aW9uc19pbXBvcnQnICkubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgICAgIHZhciBwX2ltcG9ydF9jb25kaXRpb25zID0galF1ZXJ5KCAgJyMnICsgc2hvcnRjb2RlX2lkICsgJ19jb25kaXRpb25zX2ltcG9ydCcgKS52YWwoKS50cmltKCk7XHJcbiAgICAgICAgICAgICAgICAgICAgcF9pbXBvcnRfY29uZGl0aW9ucyA9IHBfaW1wb3J0X2NvbmRpdGlvbnMucmVwbGFjZSggLycvZ2ksICcnICk7XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCBwX2ltcG9ydF9jb25kaXRpb25zICE9ICcnICl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgaW1wb3J0X2NvbmRpdGlvbnM9XFwnJyArIHBfaW1wb3J0X2NvbmRpdGlvbnMgKyAnXFwnJztcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuXHJcbiAgICAgICAgLy8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG4gICAgICAgIC8vIFtib29raW5nXSAsIFtib29raW5nY2FsZW5kYXJdICwgLi4uICBwYXJhbWV0ZXJzIGZvciB0aGVzZSBzaG9ydGNvZGVzIGFuZCBvdGhlcnMuLi5cclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgaWYgKCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19yZXNvdXJjZV9pZCcgKS5sZW5ndGggPiAwICkge1xyXG4gICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3Jlc291cmNlX2lkJyApLnZhbCgpID09PSBudWxsICkge1x0XHRcdFx0XHRcdFx0XHRcdFx0XHQvL0ZpeEluOiA4LjIuMS4xMlxyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnI3dwYmNfdGV4dF9wdXRfaW5fc2hvcnRjb2RlJyApLnZhbCggJy0tLScgKTtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgcmVzb3VyY2VfaWQ9JyArIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3Jlc291cmNlX2lkJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX2N1c3RvbV9mb3JtJyApLmxlbmd0aCA+IDAgKSB7XHJcbiAgICAgICAgICAgIHZhciBmb3JtX3R5cGVfdGVtcCA9IGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX2N1c3RvbV9mb3JtJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgaWYgKCBmb3JtX3R5cGVfdGVtcCAhPSAnc3RhbmRhcmQnIClcclxuICAgICAgICAgICAgICAgIHdwYmNfc2hvcnRjb2RlICs9ICcgZm9ybV90eXBlPVxcJycgKyBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19jdXN0b21fZm9ybScgKS52YWwoKS50cmltKCkgKyAnXFwnJztcclxuICAgICAgICB9XHJcbiAgICAgICAgaWYgKFxyXG4gICAgICAgICAgICAgICAgKCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19udW1tb250aHMnICkubGVuZ3RoID4gMCApXHJcbiAgICAgICAgICAgICAmJiAoIHBhcnNlSW50KCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19udW1tb250aHMnICkudmFsKCkudHJpbSgpICkgPiAxIClcclxuICAgICAgICApe1xyXG4gICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIG51bW1vbnRocz0nICsgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfbnVtbW9udGhzJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmIChcclxuICAgICAgICAgICAgICAgICggalF1ZXJ5KCcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zdGFydG1vbnRoX2FjdGl2ZScpLmxlbmd0aCA+IDAgKVxyXG4gICAgICAgICAgICAgJiYgKCBqUXVlcnkoJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3N0YXJ0bW9udGhfYWN0aXZlJykuaXMoJzpjaGVja2VkJykgKVxyXG4gICAgICAgICl7XHJcbiAgICAgICAgICAgICB3cGJjX3Nob3J0Y29kZSArPSAnIHN0YXJ0bW9udGg9XFwnJyArIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3N0YXJ0bW9udGhfeWVhcicgKS52YWwoKS50cmltKCkgKyAnLScgKyBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zdGFydG1vbnRoX21vbnRoJyApLnZhbCgpLnRyaW0oKSArICdcXCcnO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19hZ2dyZWdhdGUnICkubGVuZ3RoID4gMCApIHtcclxuICAgICAgICAgICAgdmFyIHdwYmNfYWdncmVnYXRlX3RlbXAgPSBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19hZ2dyZWdhdGUnICkudmFsKCk7XHJcblxyXG4gICAgICAgICAgICBpZiAoICggd3BiY19hZ2dyZWdhdGVfdGVtcCAhPSBudWxsICkgJiYgKCB3cGJjX2FnZ3JlZ2F0ZV90ZW1wLmxlbmd0aCA+IDAgKSAgKXtcclxuICAgICAgICAgICAgICAgIHdwYmNfYWdncmVnYXRlX3RlbXAgPSB3cGJjX2FnZ3JlZ2F0ZV90ZW1wLmpvaW4oJzsnKVxyXG5cclxuICAgICAgICAgICAgICAgIGlmICggd3BiY19hZ2dyZWdhdGVfdGVtcCAhPSAwICl7ICAgICAgICAgICAgICAgICAgICAgLy8gQ2hlY2sgYWJvdXQgMD0+J05vbmUnXHJcbiAgICAgICAgICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBhZ2dyZWdhdGU9XFwnJyArIHdwYmNfYWdncmVnYXRlX3RlbXAgKyAnXFwnJztcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCBqUXVlcnkoJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX2FnZ3JlZ2F0ZV9fYm9va2luZ3Nfb25seScpLmlzKCc6Y2hlY2tlZCcpICl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIHdwYmNfb3B0aW9uc19hcnIucHVzaCggJ3thZ2dyZWdhdGUgdHlwZT1ib29raW5nc19vbmx5fScgKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuICAgICAgICAvLyBPcHRpb24gUGFyYW1cclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgLy8gT3B0aW9ucyA6IFNpemVcclxuICAgICAgICB2YXIgd3BiY19vcHRpb25zX3NpemUgPSAnJztcclxuICAgICAgICBpZiAoXHJcbiAgICAgICAgICAgICAgICAoIGpRdWVyeSgnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2l6ZV9lbmFibGVkJykubGVuZ3RoID4gMCApXHJcbiAgICAgICAgICAgICAmJiAoIGpRdWVyeSgnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2l6ZV9lbmFibGVkJykuaXMoJzpjaGVja2VkJykgKVxyXG4gICAgICAgICl7XHJcblxyXG4gICAgICAgICAgICAvLyBvcHRpb25zPSd7Y2FsZW5kYXIgbW9udGhzX251bV9pbl9yb3c9MiB3aWR0aD0xMDAlIGNlbGxfaGVpZ2h0PTQwcHh9J1xyXG5cclxuICAgICAgICAgICAgd3BiY19vcHRpb25zX3NpemUgKz0gJ3tjYWxlbmRhcicgO1xyXG4gICAgICAgICAgICB3cGJjX29wdGlvbnNfc2l6ZSArPSAnICcgKyAnbW9udGhzX251bV9pbl9yb3c9J1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICArIE1hdGgubWluKFxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBwYXJzZUludCggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2l6ZV9tb250aHNfbnVtX2luX3JvdycgKS52YWwoKS50cmltKCkgKSxcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgcGFyc2VJbnQoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX251bW1vbnRocycgKS52YWwoKS50cmltKCkgKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgKTtcclxuICAgICAgICAgICAgd3BiY19vcHRpb25zX3NpemUgKz0gJyAnICsgJ3dpZHRoPScgKyBwYXJzZUludCggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfc2l6ZV9jYWxlbmRhcl93aWR0aCcgKS52YWwoKS50cmltKCkgKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgKyBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19zaXplX2NhbGVuZGFyX3dpZHRoX3B4X3ByJyApLnZhbCgpLnRyaW0oKSA7XHJcbiAgICAgICAgICAgIHdwYmNfb3B0aW9uc19zaXplICs9ICcgJyArICdjZWxsX2hlaWdodD0nICsgcGFyc2VJbnQoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ193cGJjX3NpemVfY2FsZW5kYXJfY2VsbF9oZWlnaHQnICkudmFsKCkudHJpbSgpICkgKyAncHgnO1xyXG4gICAgICAgICAgICB3cGJjX29wdGlvbnNfc2l6ZSArPSAnfSc7XHJcbiAgICAgICAgICAgIHdwYmNfb3B0aW9uc19hcnIucHVzaCggd3BiY19vcHRpb25zX3NpemUgKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIE9wdGlvbnM6IERheXMgbnVtYmVyIGRlcGVuZCBvbiAgIFdlZWtkYXlcclxuICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ3dwYmNfc2VsZWN0X2RheV93ZWVrZGF5X3RleHRhcmVhJyApLmxlbmd0aCA+IDAgKSB7XHJcbiAgICAgICAgICAgIHdwYmNfb3B0aW9uc19zaXplID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnd3BiY19zZWxlY3RfZGF5X3dlZWtkYXlfdGV4dGFyZWEnICkudmFsKCkudHJpbSgpO1xyXG4gICAgICAgICAgICBpZiAoIHdwYmNfb3B0aW9uc19zaXplLmxlbmd0aCA+IDAgKXtcclxuICAgICAgICAgICAgICAgIHdwYmNfb3B0aW9uc19hcnIucHVzaCggd3BiY19vcHRpb25zX3NpemUgKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgLy8gT3B0aW9uczogRGF5cyBudW1iZXIgZGVwZW5kIG9uICAgU0VBU09OXHJcbiAgICAgICAgaWYgKCBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICd3cGJjX3NlbGVjdF9kYXlfc2Vhc29uX3RleHRhcmVhJyApLmxlbmd0aCA+IDAgKSB7XHJcbiAgICAgICAgICAgIHdwYmNfb3B0aW9uc19zaXplID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnd3BiY19zZWxlY3RfZGF5X3NlYXNvbl90ZXh0YXJlYScgKS52YWwoKS50cmltKCk7XHJcbiAgICAgICAgICAgIGlmICggd3BiY19vcHRpb25zX3NpemUubGVuZ3RoID4gMCApe1xyXG4gICAgICAgICAgICAgICAgd3BiY19vcHRpb25zX2Fyci5wdXNoKCB3cGJjX29wdGlvbnNfc2l6ZSApO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyBPcHRpb25zOiBTdGFydCB3ZWVrZGF5IGRlcGVuZCBvbiAgIFNFQVNPTlxyXG4gICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnd3BiY19zdGFydF9kYXlfc2Vhc29uX3RleHRhcmVhJyApLmxlbmd0aCA+IDAgKSB7XHJcbiAgICAgICAgICAgIHdwYmNfb3B0aW9uc19zaXplID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnd3BiY19zdGFydF9kYXlfc2Vhc29uX3RleHRhcmVhJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgaWYgKCB3cGJjX29wdGlvbnNfc2l6ZS5sZW5ndGggPiAwICl7XHJcbiAgICAgICAgICAgICAgICB3cGJjX29wdGlvbnNfYXJyLnB1c2goIHdwYmNfb3B0aW9uc19zaXplICk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIE9wdGlvbjogRGF5cyBudW1iZXIgZGVwZW5kIG9uIGZyb20gIERBVEVcclxuICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgJ3dwYmNfc2VsZWN0X2RheV9mb3JkYXRlX3RleHRhcmVhJyApLmxlbmd0aCA+IDAgKSB7XHJcbiAgICAgICAgICAgIHdwYmNfb3B0aW9uc19zaXplID0galF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnd3BiY19zZWxlY3RfZGF5X2ZvcmRhdGVfdGV4dGFyZWEnICkudmFsKCkudHJpbSgpO1xyXG4gICAgICAgICAgICBpZiAoIHdwYmNfb3B0aW9uc19zaXplLmxlbmd0aCA+IDAgKXtcclxuICAgICAgICAgICAgICAgIHdwYmNfb3B0aW9uc19hcnIucHVzaCggd3BiY19vcHRpb25zX3NpemUgKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKCB3cGJjX29wdGlvbnNfYXJyLmxlbmd0aCA+IDAgKXtcclxuICAgICAgICAgICAgd3BiY19zaG9ydGNvZGUgKz0gJyBvcHRpb25zPVxcJycgKyB3cGJjX29wdGlvbnNfYXJyLmpvaW4oICcsJyApICsgJ1xcJyc7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuXHJcbiAgICB3cGJjX3Nob3J0Y29kZSArPSAnXSc7XHJcblxyXG4gICAgalF1ZXJ5KCAnI3dwYmNfdGV4dF9wdXRfaW5fc2hvcnRjb2RlJyApLnZhbCggd3BiY19zaG9ydGNvZGUgKTtcclxufVxyXG5cclxuICAgIC8qKlxyXG4gICAgICogT3BlbiBUaW55TUNFIE1vZGFsICovXHJcbiAgICBmdW5jdGlvbiB3cGJjX3RpbnlfYnRuX2NsaWNrKCB0YWcgKSB7XHJcbiAgICAgICAgLy9GaXhJbjogOS4wLjEuNVxyXG4gICAgICAgIGpRdWVyeSgnI3dwYmNfdGlueV9tb2RhbCcpLndwYmNfbXlfbW9kYWwoe1xyXG4gICAgICAgICAgICBrZXlib2FyZDogZmFsc2VcclxuICAgICAgICAgICwgYmFja2Ryb3A6IHRydWVcclxuICAgICAgICAgICwgc2hvdzogdHJ1ZVxyXG4gICAgICAgIH0pO1xyXG4gICAgICAgIC8vRml4SW46IDguMy4zLjk5XHJcbiAgICAgICAgalF1ZXJ5KCBcIiN3cGJjX3RleHRfZ2V0dGVuYmVyZ19zZWN0aW9uX2lkXCIgKS52YWwoICcnICk7XHJcblxyXG4gICAgfVxyXG5cclxuICAgIC8qKlxyXG4gICAgICogT3BlbiBUaW55TUNFIE1vZGFsICovXHJcbiAgICBmdW5jdGlvbiB3cGJjX3RpbnlfY2xvc2UoKSB7XHJcblxyXG4gICAgICAgIGpRdWVyeSgnI3dwYmNfdGlueV9tb2RhbCcpLndwYmNfbXlfbW9kYWwoJ2hpZGUnKTtcdC8vRml4SW46IDkuMC4xLjVcclxuICAgIH1cclxuXHJcbiAgICAvKiAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuICAgIC8qKiBTZW5kIFRleHQgKi9cclxuICAgIC8qIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLSAqL1xyXG4gICAgLyoqXHJcbiAgICAgKiBTZW5kIHRleHQgIHRvIGVkaXRvciAqL1xyXG4gICAgZnVuY3Rpb24gd3BiY19zZW5kX3RleHRfdG9fZWRpdG9yKCBoICkge1xyXG5cclxuICAgICAgICAvLyBGaXhJbjogOC4zLjMuOTlcclxuICAgICAgICBpZiAoIHR5cGVvZiggd3BiY19zZW5kX3RleHRfdG9fZ3V0ZW5iZXJnICkgPT0gJ2Z1bmN0aW9uJyApe1xyXG4gICAgICAgICAgICB2YXIgaXNfc2VuZCA9IHdwYmNfc2VuZF90ZXh0X3RvX2d1dGVuYmVyZyggaCApO1xyXG4gICAgICAgICAgICBpZiAoIHRydWUgPT09IGlzX3NlbmQgKXtcclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIHZhciBlZCwgbWNlID0gdHlwZW9mKHRpbnltY2UpICE9ICd1bmRlZmluZWQnLCBxdCA9IHR5cGVvZihRVGFncykgIT0gJ3VuZGVmaW5lZCc7XHJcblxyXG4gICAgICAgICAgICBpZiAoICF3cEFjdGl2ZUVkaXRvciApIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoIG1jZSAmJiB0aW55bWNlLmFjdGl2ZUVkaXRvciApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVkID0gdGlueW1jZS5hY3RpdmVFZGl0b3I7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICB3cEFjdGl2ZUVkaXRvciA9IGVkLmlkO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoICFxdCApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIHJldHVybiBmYWxzZTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH0gZWxzZSBpZiAoIG1jZSApIHtcclxuICAgICAgICAgICAgICAgICAgICBpZiAoIHRpbnltY2UuYWN0aXZlRWRpdG9yICYmICh0aW55bWNlLmFjdGl2ZUVkaXRvci5pZCA9PSAnbWNlX2Z1bGxzY3JlZW4nIHx8IHRpbnltY2UuYWN0aXZlRWRpdG9yLmlkID09ICd3cF9tY2VfZnVsbHNjcmVlbicpIClcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGVkID0gdGlueW1jZS5hY3RpdmVFZGl0b3I7XHJcbiAgICAgICAgICAgICAgICAgICAgZWxzZVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgZWQgPSB0aW55bWNlLmdldCh3cEFjdGl2ZUVkaXRvcik7XHJcbiAgICAgICAgICAgIH1cclxuXHJcbiAgICAgICAgICAgIGlmICggZWQgJiYgIWVkLmlzSGlkZGVuKCkgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgLy8gcmVzdG9yZSBjYXJldCBwb3NpdGlvbiBvbiBJRVxyXG4gICAgICAgICAgICAgICAgICAgIGlmICggdGlueW1jZS5pc0lFICYmIGVkLndpbmRvd01hbmFnZXIuaW5zZXJ0aW1hZ2Vib29rbWFyayApXHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBlZC5zZWxlY3Rpb24ubW92ZVRvQm9va21hcmsoZWQud2luZG93TWFuYWdlci5pbnNlcnRpbWFnZWJvb2ttYXJrKTtcclxuXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCBoLmluZGV4T2YoJ1tjYXB0aW9uJykgIT09IC0xICkge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgaWYgKCBlZC53cFNldEltZ0NhcHRpb24gKVxyXG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICBoID0gZWQud3BTZXRJbWdDYXB0aW9uKGgpO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoIGguaW5kZXhPZignW2dhbGxlcnknKSAhPT0gLTEgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIGVkLnBsdWdpbnMud3BnYWxsZXJ5IClcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaCA9IGVkLnBsdWdpbnMud3BnYWxsZXJ5Ll9kb19nYWxsZXJ5KGgpO1xyXG4gICAgICAgICAgICAgICAgICAgIH0gZWxzZSBpZiAoIGguaW5kZXhPZignW2VtYmVkJykgPT09IDAgKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICBpZiAoIGVkLnBsdWdpbnMud29yZHByZXNzIClcclxuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgaCA9IGVkLnBsdWdpbnMud29yZHByZXNzLl9zZXRFbWJlZChoKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICAgICAgICAgIGVkLmV4ZWNDb21tYW5kKCdtY2VJbnNlcnRDb250ZW50JywgZmFsc2UsIGgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgaWYgKCBxdCApIHtcclxuICAgICAgICAgICAgICAgICAgICBRVGFncy5pbnNlcnRDb250ZW50KGgpO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKHdwQWN0aXZlRWRpdG9yKS52YWx1ZSArPSBoO1xyXG4gICAgICAgICAgICB9XHJcblxyXG4gICAgICAgICAgICB0cnl7dGJfcmVtb3ZlKCk7fWNhdGNoKGUpe307XHJcbiAgICB9XHJcblxyXG4gICAgLyoqXHJcbiAgICAgKiBSRVNPVVJDRVMgUEFHRTogT3BlbiBUaW55TUNFIE1vZGFsICovXHJcbiAgICBmdW5jdGlvbiB3cGJjX3Jlc291cmNlX3BhZ2VfYnRuX2NsaWNrKCByZXNvdXJjZV9pZCAsIHNob3J0Y29kZV9kZWZhdWx0X3ZhbHVlID0gJycpIHtcclxuXHJcbiAgICAgICAgLy9GaXhJbjogOS4wLjEuNVxyXG4gICAgICAgIGpRdWVyeSgnI3dwYmNfdGlueV9tb2RhbCcpLndwYmNfbXlfbW9kYWwoe1xyXG4gICAgICAgICAgICBrZXlib2FyZDogZmFsc2VcclxuICAgICAgICAgICwgYmFja2Ryb3A6IHRydWVcclxuICAgICAgICAgICwgc2hvdzogdHJ1ZVxyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICAvLyBEaXNhYmxlIHNvbWUgb3B0aW9ucyAtIHNlbGVjdGlvbiBvZiBib29raW5nIHJlc291cmNlIC0gYmVjYXVzZSB3ZSBjb25maWd1cmUgaXQgb25seSBmb3Igc3BlY2lmaWMgYm9va2luZyByZXNvdXJjZSwgd2hlcmUgd2UgY2xpY2tlZC5cclxuICAgICAgICB2YXIgc2hvcnRjb2RlX2FyciA9IFsnYm9va2luZycsICdib29raW5nY2FsZW5kYXInLCAnYm9va2luZ2Zvcm0nXTtcclxuXHJcbiAgICAgICAgZm9yICggdmFyIHNob3J0Y2RlX2tleSBpbiBzaG9ydGNvZGVfYXJyICl7XHJcblxyXG4gICAgICAgICAgICB2YXIgc2hvcnRjb2RlX2lkID0gc2hvcnRjb2RlX2Fyclsgc2hvcnRjZGVfa2V5IF07XHJcblxyXG4gICAgICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV9pZCArICdfd3BiY19yZXNvdXJjZV9pZCcgKS5wcm9wKCBcdFx0ICdkaXNhYmxlZCcsIGZhbHNlICk7XHJcbiAgICAgICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX2lkICsgXCJfd3BiY19yZXNvdXJjZV9pZCBvcHRpb25bdmFsdWU9J1wiICsgcmVzb3VyY2VfaWQgKyBcIiddXCIgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICkudHJpZ2dlciggJ2NoYW5nZScgKTtcclxuICAgICAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfaWQgKyAnX3dwYmNfcmVzb3VyY2VfaWQnICkucHJvcCggXHRcdCAnZGlzYWJsZWQnLCB0cnVlICk7XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyBIaWRlIGxlZnQgIG5hdmlnYXRpb24gIGl0ZW1zXHJcbi8vICAgICAgICBqUXVlcnkoIFwiLndwYmNfc2hvcnRjb2RlX2NvbmZpZ19uYXZpZ2F0aW9uX2NvbHVtbiAud3BiY19zZXR0aW5nc19uYXZpZ2F0aW9uX2l0ZW1cIiApLmhpZGUoKTtcclxuICAgICAgICBqUXVlcnkoIFwiI3dwYmNfc2hvcnRjb2RlX2NvbmZpZ19fbmF2X3RhYl9fYm9va2luZ1wiICkuc2hvdygpO1xyXG4gICAgICAgIGpRdWVyeSggXCIjd3BiY19zaG9ydGNvZGVfY29uZmlnX19uYXZfdGFiX19ib29raW5nY2FsZW5kYXJcIiApLnNob3coKTtcclxuXHJcbiAgICAgICAgLy8gSGlkZSB8IFNob3cgSW5zZXJ0ICBidXR0b24gIGZvciBib29raW5nIHJlc291cmNlIHBhZ2VcclxuICAgICAgICBqUXVlcnkoIFwiLndwYmNfdGlueV9idXR0b25fX2luc2VydF90b19lZGl0b3JcIiApLmhpZGUoKTtcclxuICAgICAgICBqUXVlcnkoIFwiLndwYmNfdGlueV9idXR0b25fX2luc2VydF90b19yZXNvdXJjZVwiICkuc2hvdygpO1xyXG4gICAgfVxyXG5cclxuICAgIC8qKlxyXG4gICAgICogR2V0IFNob3J0Y29kZSBWYWx1ZSBmcm9tICBzaG9ydGNvZGUgdGV4dCBmaWVsZCBpbiBQb3BVcCBzaG9ydGNvZGUgQ29uZmlnIGRpYWxvZyBhbmQgaW5zZXJ0ICBpbnRvIERJViBhbmQgSU5QVVQgVEVYVCBmaWVsZCBuZWFyIHNwZWNpZmljIGJvb2tpbmcgcmVzb3VyY2UuXHJcbiAgICAgKiAgQnV0IGl0IHRha2VzIElEICBvZiBib29raW5nIHJlc291cmNlLCAgd2hlcmUgdG8gIGluc2VydCAgdGhpcyBzaG9ydGNvZGUgb25seSBmcm9tICAnYm9va2luZycgc2VjdGlvbiAgb2YgQ29uZmlnIERpYWxvZy4gdXN1YWxseSAgc3VjaCAgYm9va2luZyByZXNvdXJjZSAgZGlzYWJsZWQgdGhlcmUhXHJcbiAgICAgKiAgZS5nLjogalF1ZXJ5KCBcIiNib29raW5nX3dwYmNfcmVzb3VyY2VfaWRcIiApLnZhbCgpXHJcbiAgICAgKlxyXG4gICAgICogQHBhcmFtIHNob3J0Y29kZV92YWxcclxuICAgICAqL1xyXG4gICAgZnVuY3Rpb24gd3BiY19zZW5kX3RleHRfdG9fcmVzb3VyY2UoIHNob3J0Y29kZV92YWwgKXtcclxuXHJcbiAgICAgICAgalF1ZXJ5KCAnI2Rpdl9ib29raW5nX3Jlc291cmNlX3Nob3J0Y29kZV8nICsgalF1ZXJ5KCBcIiNib29raW5nX3dwYmNfcmVzb3VyY2VfaWRcIiApLnZhbCgpICkuaHRtbCggc2hvcnRjb2RlX3ZhbCApO1xyXG4gICAgICAgICAgICBqUXVlcnkoICcjYm9va2luZ19yZXNvdXJjZV9zaG9ydGNvZGVfJyArIGpRdWVyeSggXCIjYm9va2luZ193cGJjX3Jlc291cmNlX2lkXCIgKS52YWwoKSApLnZhbCggc2hvcnRjb2RlX3ZhbCApO1xyXG4gICAgICAgICAgICBqUXVlcnkoICcjYm9va2luZ19yZXNvdXJjZV9zaG9ydGNvZGVfJyArIGpRdWVyeSggXCIjYm9va2luZ193cGJjX3Jlc291cmNlX2lkXCIgKS52YWwoKSApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG5cclxuICAgICAgICAvLyBTY3JvbGxcclxuICAgICAgICBpZiAoICdmdW5jdGlvbicgPT09IHR5cGVvZiAod3BiY19zY3JvbGxfdG8pICl7XHJcbiAgICAgICAgICAgIHdwYmNfc2Nyb2xsX3RvKCAnI2Rpdl9ib29raW5nX3Jlc291cmNlX3Nob3J0Y29kZV8nICsgalF1ZXJ5KCBcIiNib29raW5nX3dwYmNfcmVzb3VyY2VfaWRcIiApLnZhbCgpICk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIC8qIFIgRSBTIEUgVCAqL1xyXG4gICAgZnVuY3Rpb24gd3BiY19zaG9ydGNvZGVfY29uZmlnX19yZXNldChzaG9ydGNvZGVfdmFsKXtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfc3RhcnRtb250aF9hY3RpdmUnICkucHJvcCggJ2NoZWNrZWQnLCBmYWxzZSApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG5cclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfYWdncmVnYXRlIG9wdGlvbjpzZWxlY3RlZCcpLnByb3AoICdzZWxlY3RlZCcsIGZhbHNlKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfYWdncmVnYXRlIG9wdGlvbjplcSgwKScgICApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfYWdncmVnYXRlX19ib29raW5nc19vbmx5JyApLnByb3AoICdjaGVja2VkJywgZmFsc2UgKS50cmlnZ2VyKCdjaGFuZ2UnKTtcclxuXHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX2N1c3RvbV9mb3JtIG9wdGlvbjplcSgwKScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX251bW1vbnRocyBvcHRpb246ZXEoMCknICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfd3BiY19zaXplX2VuYWJsZWQnICkucHJvcCggJ2NoZWNrZWQnLCBmYWxzZSApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG5cclxuICAgICAgICB3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfd2Vla2RheV9fcmVzZXQoIHNob3J0Y29kZV92YWwgKyAnd3BiY19zZWxlY3RfZGF5X3dlZWtkYXknICk7XHJcbiAgICAgICAgd3BiY19zaG9ydGNvZGVfY29uZmlnX19zZWxlY3RfZGF5X3NlYXNvbl9fcmVzZXQoIHNob3J0Y29kZV92YWwgKyAnd3BiY19zZWxlY3RfZGF5X3NlYXNvbicgKTtcclxuICAgICAgICB3cGJjX3Nob3J0Y29kZV9jb25maWdfX3N0YXJ0X2RheV9zZWFzb25fX3Jlc2V0KCBzaG9ydGNvZGVfdmFsICsgJ3dwYmNfc3RhcnRfZGF5X3NlYXNvbicgKTtcclxuICAgICAgICB3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfZm9yZGF0ZV9fcmVzZXQoIHNob3J0Y29kZV92YWwgKyAnd3BiY19zZWxlY3RfZGF5X2ZvcmRhdGUnICk7XHJcblxyXG4gICAgICAgIC8vIFJlc2V0ICBmb3IgW2Jvb2tpbmdzZWxlY3RdIHNob3J0Y29kZSBwYXJhbXNcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfbXVsdGlwbGVfcmVzb3VyY2VzIG9wdGlvbjpzZWxlY3RlZCcpLnByb3AoICdzZWxlY3RlZCcsIGZhbHNlKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfbXVsdGlwbGVfcmVzb3VyY2VzIG9wdGlvbjplcSgwKScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3NlbGVjdGVkX3Jlc291cmNlIG9wdGlvbjplcSgwKScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3RleHRfbGFiZWwnICkudmFsKCAnJyApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfd3BiY19maXJzdF9vcHRpb25fdGl0bGUnICkudmFsKCAnJyApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG5cclxuICAgICAgICAvLyBSZXNldCAgZm9yIFtib29raW5ndGltZWxpbmVdIHNob3J0Y29kZSBwYXJhbXNcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfdGV4dF9sYWJlbF90aW1lbGluZScgKS52YWwoICcnICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3Njcm9sbF90aW1lbGluZV9zY3JvbGxfbW9udGggb3B0aW9uW3ZhbHVlPVwiMFwiXScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3Njcm9sbF90aW1lbGluZV9zY3JvbGxfZGF5cyBvcHRpb25bdmFsdWU9XCIwXCJdJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKS50cmlnZ2VyKCdjaGFuZ2UnKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfc3RhcnRfZGF0ZV90aW1lbGluZV9hY3RpdmUnICkucHJvcCggJ2NoZWNrZWQnLCBmYWxzZSApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfd3BiY19zdGFydF9lbmRfdGltZV90aW1lbGluZV9zdGFydHRpbWUgb3B0aW9uW3ZhbHVlPVwiMFwiXScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3N0YXJ0X2VuZF90aW1lX3RpbWVsaW5lX2VuZHRpbWUgb3B0aW9uW3ZhbHVlPVwiMjRcIl0nICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG4gICAgICAgIGpRdWVyeSggJ2lucHV0W25hbWU9XCInICsgc2hvcnRjb2RlX3ZhbCArICdfd3BiY192aWV3X21vZGVfdGltZWxpbmVfbW9udGhzX251bV9pbl9yb3dcIl1bdmFsdWU9XCIzMFwiXScgKS5wcm9wKCAnY2hlY2tlZCcsIHRydWUgKS50cmlnZ2VyKCdjaGFuZ2UnKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfc3RhcnRfZGF0ZV90aW1lbGluZV95ZWFyIG9wdGlvblt2YWx1ZT1cIicgKyAobmV3IERhdGUoKS5nZXRGdWxsWWVhcigpKSArICdcIl0nICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApLnRyaWdnZXIoICdjaGFuZ2UnICk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3N0YXJ0X2RhdGVfdGltZWxpbmVfbW9udGggb3B0aW9uW3ZhbHVlPVwiJyArICgobmV3IERhdGUoKS5nZXRNb250aCgpKSArIDEpICsgJ1wiXScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3N0YXJ0X2RhdGVfdGltZWxpbmVfZGF5IG9wdGlvblt2YWx1ZT1cIicgKyAobmV3IERhdGUoKS5nZXREYXRlKCkpICsgJ1wiXScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICkudHJpZ2dlcignY2hhbmdlJyk7XHJcblxyXG4gICAgICAgIC8vIFJlc2V0ICBmb3IgW2Jvb2tpbmdmb3JtXSBzaG9ydGNvZGUgcGFyYW1zXHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX2Jvb2tpbmdfZGF0ZV95ZWFyIG9wdGlvblt2YWx1ZT1cIicgKyAobmV3IERhdGUoKS5nZXRGdWxsWWVhcigpKSArICdcIl0nICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApLnRyaWdnZXIoICdjaGFuZ2UnICk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX2Jvb2tpbmdfZGF0ZV9tb250aCBvcHRpb25bdmFsdWU9XCInICsgKChuZXcgRGF0ZSgpLmdldE1vbnRoKCkpICsgMSkgKyAnXCJdJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKS50cmlnZ2VyKCdjaGFuZ2UnKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfYm9va2luZ19kYXRlX2RheSBvcHRpb25bdmFsdWU9XCInICsgKG5ldyBEYXRlKCkuZ2V0RGF0ZSgpKSArICdcIl0nICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG5cclxuICAgICAgICAvLyBSZXNldCAgZm9yIFtbYm9va2luZ3NlYXJjaCAuLi5dIHNob3J0Y29kZSBwYXJhbXNcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfc2VhcmNoX25ld19wYWdlX3VybCcgKS52YWwoICcnICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3NlYXJjaF9uZXdfcGFnZV9lbmFibGVkJyApLnByb3AoICdjaGVja2VkJywgZmFsc2UgKS50cmlnZ2VyKCdjaGFuZ2UnKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfc2VhcmNoX2hlYWRlcicgKS52YWwoICcnICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3NlYXJjaF9ub3RoaW5nX2ZvdW5kJyApLnZhbCggJycgKS50cmlnZ2VyKCdjaGFuZ2UnKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfc2VhcmNoX2Zvcl91c2VycycgKS52YWwoICcnICkudHJpZ2dlcignY2hhbmdlJyk7XHJcbiAgICAgICAgalF1ZXJ5KCAnaW5wdXRbbmFtZT1cIicgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3NlYXJjaF9mb3JtX3Jlc3VsdHNcIl1bdmFsdWU9XCJib29raW5nc2VhcmNoXCJdJyApLnByb3AoICdjaGVja2VkJywgdHJ1ZSApLnRyaWdnZXIoJ2NoYW5nZScpO1xyXG5cclxuICAgICAgICAvLyBSZXNldCAgZm9yIFtib29raW5nZWRpdF0gLCBbYm9va2luZ2N1c3RvbWVybGlzdGluZ10gLCBbYm9va2luZ3Jlc291cmNlIHR5cGU9NiBzaG93PSdjYXBhY2l0eSddICwgW2Jvb2tpbmdfY29uZmlybV1cclxuICAgICAgICBqUXVlcnkoICdpbnB1dFtuYW1lPVwiJyArIHNob3J0Y29kZV92YWwgKyAnX3dwYmNfc2hvcnRjb2RlX3R5cGVcIl1bdmFsdWU9XCJib29raW5nX2NvbmZpcm1cIl0nICkucHJvcCggJ2NoZWNrZWQnLCB0cnVlICkudHJpZ2dlcignY2hhbmdlJyk7XHJcblxyXG5cclxuICAgICAgICAvLyBib29raW5nX2ltcG9ydF9pY3MgLCBib29raW5nX2xpc3RpbmdfaWNzXHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ193cGJjX3VybCcgKS52YWwoICcnICkudHJpZ2dlciggJ2NoYW5nZScgKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX2Zyb20gb3B0aW9uW3ZhbHVlPVwidG9kYXlcIl0nICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApLnRyaWdnZXIoICdjaGFuZ2UnICk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBzaG9ydGNvZGVfdmFsICsgJ19mcm9tX29mZnNldCcgKS52YWwoICcnICkudHJpZ2dlciggJ2NoYW5nZScgKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX2Zyb21fb2Zmc2V0X3R5cGUgb3B0aW9uOmVxKDApJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKS50cmlnZ2VyKCAnY2hhbmdlJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfdW50aWwgb3B0aW9uW3ZhbHVlPVwiYW55XCJdJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKS50cmlnZ2VyKCAnY2hhbmdlJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfdW50aWxfb2Zmc2V0JyApLnZhbCggJycgKS50cmlnZ2VyKCAnY2hhbmdlJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfdW50aWxfb2Zmc2V0X3R5cGUgb3B0aW9uOmVxKDApJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKS50cmlnZ2VyKCAnY2hhbmdlJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfY29uZGl0aW9uc19pbXBvcnQgb3B0aW9uOmVxKDApJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKS50cmlnZ2VyKCAnY2hhbmdlJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfY29uZGl0aW9uc19ldmVudHMgb3B0aW9uW3ZhbHVlPVwiMVwiXScgKS5wcm9wKCAnc2VsZWN0ZWQnLCB0cnVlICkudHJpZ2dlciggJ2NoYW5nZScgKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIHNob3J0Y29kZV92YWwgKyAnX2NvbmRpdGlvbnNfbWF4X251bSBvcHRpb25bdmFsdWU9XCIwXCJdJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKS50cmlnZ2VyKCAnY2hhbmdlJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgc2hvcnRjb2RlX3ZhbCArICdfc2lsZW5jZSBvcHRpb25bdmFsdWU9XCIwXCJdJyApLnByb3AoICdzZWxlY3RlZCcsIHRydWUgKS50cmlnZ2VyKCAnY2hhbmdlJyApO1xyXG4gICAgfVxyXG5cclxuLyogLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tICovXHJcbi8qKlxyXG4gKiAgU0hPUlRDT0RFX0NPTkZJR1xyXG4gKiAqL1xyXG4vKiAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0gKi9cclxuXHJcbi8qKlxyXG4gKiBXaGVuIGNsaWNrIG9uIG1lbnUgaXRlbSBpbiBcIkxlZnQgVmVydGljYWwgTmF2aWdhdGlvblwiIHBhbmVsICBpbiBzaG9ydGNvZGUgY29uZmlnIHBvcHVwXHJcbiAqL1xyXG5mdW5jdGlvbiB3cGJjX3Nob3J0Y29kZV9jb25maWdfY2xpY2tfc2hvd19zZWN0aW9uKCBfdGhpcywgc2VjdGlvbl9pZF90b19zaG93LCBzaG9ydGNvZGVfbmFtZSApe1xyXG5cclxuICAgIC8vIE1lbnVcclxuICAgIGpRdWVyeSggX3RoaXMgKS5wYXJlbnRzKCAnLndwYmNfc2V0dGluZ3NfZmxleF9jb250YWluZXInICkuZmluZCggJy53cGJjX3NldHRpbmdzX25hdmlnYXRpb25faXRlbV9hY3RpdmUnICkucmVtb3ZlQ2xhc3MoICd3cGJjX3NldHRpbmdzX25hdmlnYXRpb25faXRlbV9hY3RpdmUnICk7XHJcbiAgICBqUXVlcnkoIF90aGlzICkucGFyZW50cyggJy53cGJjX3NldHRpbmdzX25hdmlnYXRpb25faXRlbScgKS5hZGRDbGFzcyggJ3dwYmNfc2V0dGluZ3NfbmF2aWdhdGlvbl9pdGVtX2FjdGl2ZScgKTtcclxuXHJcbiAgICAvLyBDb250ZW50XHJcbiAgICBqUXVlcnkoIF90aGlzICkucGFyZW50cyggJy53cGJjX3NldHRpbmdzX2ZsZXhfY29udGFpbmVyJyApLmZpbmQoICcud3BiY19zY19jb250YWluZXJfX3Nob3J0Y29kZScgKS5oaWRlKCk7XHJcbiAgICBqUXVlcnkoIHNlY3Rpb25faWRfdG9fc2hvdyApLnNob3coKTtcclxuXHJcbiAgICAvLyBTY3JvbGxcclxuICAgIGlmICggJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mICh3cGJjX3Njcm9sbF90bykgKXtcclxuICAgICAgICB3cGJjX3Njcm9sbF90byggc2VjdGlvbl9pZF90b19zaG93ICk7XHJcbiAgICB9XHJcbiAgICAvLyBTZXQgLSBTaG9ydGNvZGUgVHlwZVxyXG4gICAgalF1ZXJ5KCAnI3dwYmNfc2hvcnRjb2RlX3R5cGUnKS52YWwoIHNob3J0Y29kZV9uYW1lICk7XHJcblxyXG4gICAgLy8gUGFyc2Ugc2hvcnRjb2RlIHBhcmFtc1xyXG4gICAgd3BiY19zZXRfc2hvcnRjb2RlKCk7XHJcbn1cclxuXHJcblxyXG4gICAgLyoqXHJcbiAgICAgKiBEbyBOZXh0IC8gUHJpb3Igc3RlcFxyXG4gICAgICogQHBhcmFtIF90aGlzXHRcdGJ1dHRvbiAgdGhpc1xyXG4gICAgICogQHBhcmFtIHN0ZXBcdFx0J3ByaW9yJyB8ICduZXh0J1xyXG4gICAgICovXHJcbiAgICBmdW5jdGlvbiB3cGJjX3Nob3J0Y29kZV9jb25maWdfY29udGVudF90b29sYmFyX19uZXh0X3ByaW9yKCBfdGhpcywgc3RlcCApe1xyXG5cclxuICAgICAgICB2YXIgal93b3JrX25hdl90YWI7XHJcblxyXG4gICAgICAgIHZhciBzdWJtZW51X3NlbGVjdGVkID0galF1ZXJ5KCBfdGhpcyApLnBhcmVudHMoICcud3BiY19zY19jb250YWluZXJfX3Nob3J0Y29kZScgKS5maW5kKCAnLndwYmNfc2NfY29udGFpbmVyX19zaG9ydGNvZGVfc2VjdGlvbjp2aXNpYmxlJyApLmZpbmQoICcud3BkZXZlbG9wLXN1Ym1lbnUtdGFiLXNlbGVjdGVkOnZpc2libGUnICk7XHJcbiAgICAgICAgaWYgKCBzdWJtZW51X3NlbGVjdGVkLmxlbmd0aCApe1xyXG4gICAgICAgICAgICBpZiAoICduZXh0JyA9PT0gc3RlcCApe1xyXG4gICAgICAgICAgICAgICAgal93b3JrX25hdl90YWIgPSBzdWJtZW51X3NlbGVjdGVkLm5leHRBbGwoICdhLm5hdi10YWI6dmlzaWJsZScgKS5maXJzdCgpO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgal93b3JrX25hdl90YWIgPSBzdWJtZW51X3NlbGVjdGVkLnByZXZBbGwoICdhLm5hdi10YWI6dmlzaWJsZScgKS5maXJzdCgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGlmICggal93b3JrX25hdl90YWIubGVuZ3RoICl7XHJcbiAgICAgICAgICAgICAgICBqX3dvcmtfbmF2X3RhYi50cmlnZ2VyKCAnY2xpY2snICk7XHJcbiAgICAgICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmICggJ25leHQnID09PSBzdGVwICl7XHJcbiAgICAgICAgICAgIGpfd29ya19uYXZfdGFiID0galF1ZXJ5KCBfdGhpcyApLnBhcmVudHMoICcud3BiY19zY19jb250YWluZXJfX3Nob3J0Y29kZScgKS5maW5kKCAnLm5hdi10YWIubmF2LXRhYi1hY3RpdmU6dmlzaWJsZScgKS5uZXh0QWxsKCAnYS5uYXYtdGFiOnZpc2libGUnICkuZmlyc3QoKTtcclxuICAgICAgICB9IGVsc2V7XHJcbiAgICAgICAgICAgIGpfd29ya19uYXZfdGFiID0galF1ZXJ5KCBfdGhpcyApLnBhcmVudHMoICcud3BiY19zY19jb250YWluZXJfX3Nob3J0Y29kZScgKS5maW5kKCAnLm5hdi10YWIubmF2LXRhYi1hY3RpdmU6dmlzaWJsZScgKS5wcmV2QWxsKCAnYS5uYXYtdGFiOnZpc2libGUnICkuZmlyc3QoKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIGlmICggal93b3JrX25hdl90YWIubGVuZ3RoICl7XHJcbiAgICAgICAgICAgIGpfd29ya19uYXZfdGFiLnRyaWdnZXIoICdjbGljaycgKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgfVxyXG5cclxuXHJcbiAgICAvKipcclxuICAgICAqIENvbmRpdGlvbjogICB7c2VsZWN0LWRheSBjb25kaXRpb249XCJ3ZWVrZGF5XCIgZm9yPVwiNVwiIHZhbHVlPVwiM1wifVxyXG4gICAgICovXHJcbiAgICBmdW5jdGlvbiB3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfd2Vla2RheV9fYWRkKGlkKXtcclxuICAgICAgICB2YXIgY29uZGl0aW9uX3J1bGVfYXJyID0gW107XHJcbiAgICAgICAgZm9yICggdmFyIHdlZWtkYXlfbnVtID0gMDsgd2Vla2RheV9udW0gPCA4OyB3ZWVrZGF5X251bSsrICl7XHJcbiAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBpZCArICdfX3dlZWtkYXlfJyArIHdlZWtkYXlfbnVtICkuaXMoICc6Y2hlY2tlZCcgKSApe1xyXG4gICAgICAgICAgICAgICAgdmFyIGRheXNfdG9fc2VsZWN0ID0galF1ZXJ5KCAnIycgKyBpZCArICdfX2RheXNfbnVtYmVyXycgKyB3ZWVrZGF5X251bSApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAgICAgICAgIC8vIFJlbW92ZSBhbGwgd29yZHMgZXhjZXB0IGRpZ2l0cyBhbmQgLCBhbmQgLVxyXG4gICAgICAgICAgICAgICAgZGF5c190b19zZWxlY3QgPSBkYXlzX3RvX3NlbGVjdC5yZXBsYWNlKC9bXjAtOSwtXS9nLCAnJyk7XHJcbiAgICAgICAgICAgICAgICBkYXlzX3RvX3NlbGVjdCA9IGRheXNfdG9fc2VsZWN0LnJlcGxhY2UoL1ssXXsyLH0vZywgJywnKTtcclxuICAgICAgICAgICAgICAgIGRheXNfdG9fc2VsZWN0ID0gZGF5c190b19zZWxlY3QucmVwbGFjZSgvWy1dezIsfS9nLCAnLScpO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnIycgKyBpZCArICdfX2RheXNfbnVtYmVyXycgKyB3ZWVrZGF5X251bSApLnZhbCggZGF5c190b19zZWxlY3QgKTtcclxuXHJcbiAgICAgICAgICAgICAgICBpZiAoICcnICE9PSBkYXlzX3RvX3NlbGVjdCApe1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbmRpdGlvbl9ydWxlX2Fyci5wdXNoKCAne3NlbGVjdC1kYXkgY29uZGl0aW9uPVwid2Vla2RheVwiIGZvcj1cIicgKyB3ZWVrZGF5X251bSArICdcIiB2YWx1ZT1cIicgKyBkYXlzX3RvX3NlbGVjdCArICdcIn0nICk7XHJcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIFJlZCBoaWdobGlnaHQgZmllbGRzLCAgaWYgc29tZSByZXF1aXJlZCBmaWVsZHMgYXJlIGVtcHR5XHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKCAoJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mICh3cGJjX2ZpZWxkX2hpZ2hsaWdodCkpICYmICgnJyA9PT0galF1ZXJ5KCAnIycgKyBpZCArICdfX2RheXNfbnVtYmVyXycgKyB3ZWVrZGF5X251bSApLnZhbCgpKSApe1xyXG4gICAgICAgICAgICAgICAgICAgICAgICB3cGJjX2ZpZWxkX2hpZ2hsaWdodCggJyMnICsgaWQgKyAnX19kYXlzX251bWJlcl8nICsgd2Vla2RheV9udW0gKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICB9XHJcbiAgICAgICAgdmFyIGNvbmRpdGlvbl9ydWxlID0gY29uZGl0aW9uX3J1bGVfYXJyLmpvaW4oICcsJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX3RleHRhcmVhJyApLnZhbCggY29uZGl0aW9uX3J1bGUgKTtcclxuICAgICAgICB3cGJjX3NldF9zaG9ydGNvZGUoKTtcclxuICAgIH1cclxuICAgIGZ1bmN0aW9uIHdwYmNfc2hvcnRjb2RlX2NvbmZpZ19fc2VsZWN0X2RheV93ZWVrZGF5X19yZXNldChpZCl7XHJcblxyXG4gICAgICAgIGZvciAoIHZhciB3ZWVrZGF5X251bSA9IDA7IHdlZWtkYXlfbnVtIDwgODsgd2Vla2RheV9udW0rKyApe1xyXG4gICAgICAgICAgICBqUXVlcnkoICcjJyArIGlkICsgJ19fZGF5c19udW1iZXJfJyArIHdlZWtkYXlfbnVtICkudmFsKCAnJyApO1xyXG4gICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgaWQgKyAnX193ZWVrZGF5XycgKyB3ZWVrZGF5X251bSApLmlzKCAnOmNoZWNrZWQnICkgKXtcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX193ZWVrZGF5XycgKyB3ZWVrZGF5X251bSApLnByb3AoICdjaGVja2VkJywgZmFsc2UgKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICBqUXVlcnkoICcjJyArIGlkICsgJ190ZXh0YXJlYScgKS52YWwoICcnICk7XHJcbiAgICAgICAgd3BiY19zZXRfc2hvcnRjb2RlKCk7XHJcbiAgICB9XHJcblxyXG5cclxuICAgIC8qKlxyXG4gICAgICogQ29uZGl0aW9uOiAgIHtzZWxlY3QtZGF5IGNvbmRpdGlvbj1cInNlYXNvblwiIGZvcj1cIkhpZ2ggc2Vhc29uXCIgdmFsdWU9XCI3LTE0LDIwXCJ9XHJcbiAgICAgKi9cclxuICAgIGZ1bmN0aW9uIHdwYmNfc2hvcnRjb2RlX2NvbmZpZ19fc2VsZWN0X2RheV9zZWFzb25fX2FkZChpZCl7XHJcblxyXG4gICAgICAgIHZhciBzZWFzb25fZmlsdGVyX25hbWUgPSBqUXVlcnkoICcjJyArIGlkICsgJ19fc2Vhc29uX2ZpbHRlcl9uYW1lIG9wdGlvbjpzZWxlY3RlZCcgKS50ZXh0KCkudHJpbSgpO1xyXG4gICAgICAgIC8vIEVzY2FwZSBxdW90ZSBzeW1ib2xzXHJcbiAgICAgICAgc2Vhc29uX2ZpbHRlcl9uYW1lID0gc2Vhc29uX2ZpbHRlcl9uYW1lLnJlcGxhY2UoL1tcXFwiXCJdL2csICdcXFxcXCInKTtcclxuXHJcbiAgICAgICAgdmFyIGRheXNfbnVtYmVyID0galF1ZXJ5KCAnIycgKyBpZCArICdfX2RheXNfbnVtYmVyJyApLnZhbCgpLnRyaW0oKTtcclxuICAgICAgICAvLyBSZW1vdmUgYWxsIHdvcmRzIGV4Y2VwdCBkaWdpdHMgYW5kICwgYW5kIC1cclxuICAgICAgICBkYXlzX251bWJlciA9IGRheXNfbnVtYmVyLnJlcGxhY2UoIC9bXjAtOSwtXS9nLCAnJyApO1xyXG4gICAgICAgIGRheXNfbnVtYmVyID0gZGF5c19udW1iZXIucmVwbGFjZSggL1ssXXsyLH0vZywgJywnICk7XHJcbiAgICAgICAgZGF5c19udW1iZXIgPSBkYXlzX251bWJlci5yZXBsYWNlKCAvWy1dezIsfS9nLCAnLScgKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIGlkICsgJ19fZGF5c19udW1iZXInICkudmFsKCBkYXlzX251bWJlciApO1xyXG5cclxuICAgICAgICBpZiAoXHJcbiAgICAgICAgICAgICAgICgnJyAhPSBkYXlzX251bWJlcilcclxuICAgICAgICAgICAgJiYgKCcnICE9IHNlYXNvbl9maWx0ZXJfbmFtZSlcclxuICAgICAgICAgICAgJiYgKDAgIT0galF1ZXJ5KCAnIycgKyBpZCArICdfX3NlYXNvbl9maWx0ZXJfbmFtZScgKS52YWwoKSlcclxuXHJcbiAgICAgICAgKXtcclxuICAgICAgICAgICAgdmFyIGV4aXN0X2NvbmZpZ3VyYXRpb24gPSBqUXVlcnkoICcjJyArIGlkICsgJ190ZXh0YXJlYScgKS52YWwoKTtcclxuXHJcbiAgICAgICAgICAgIGV4aXN0X2NvbmZpZ3VyYXRpb24gPSBleGlzdF9jb25maWd1cmF0aW9uLnJlcGxhY2VBbGwoXCJ9LHtcIiwgJ31+fnsnKVxyXG4gICAgICAgICAgICB2YXIgY29uZGl0aW9uX3J1bGVfYXJyID0gZXhpc3RfY29uZmlndXJhdGlvbi5zcGxpdCggJ35+JyApO1xyXG5cclxuICAgICAgICAgICAgLy8gUmVtb3ZlIGVtcHR5IHNwYWNlcyBmcm9tICBhcnJheSA6ICcnIHwgXCJcIlxyXG4gICAgICAgICAgICBjb25kaXRpb25fcnVsZV9hcnIgPSBjb25kaXRpb25fcnVsZV9hcnIuZmlsdGVyKGZ1bmN0aW9uKG4pe3JldHVybiBuOyB9KTtcclxuXHJcbiAgICAgICAgICAgIGNvbmRpdGlvbl9ydWxlX2Fyci5wdXNoKCAne3NlbGVjdC1kYXkgY29uZGl0aW9uPVwic2Vhc29uXCIgZm9yPVwiJyArIHNlYXNvbl9maWx0ZXJfbmFtZSArICdcIiB2YWx1ZT1cIicgKyBkYXlzX251bWJlciArICdcIn0nICk7XHJcblxyXG4gICAgICAgICAgICAvLyBSZW1vdmUgZHVwbGljYXRlcyBmcm9tICB0aGUgYXJyYXlcclxuICAgICAgICAgICAgY29uZGl0aW9uX3J1bGVfYXJyID0gY29uZGl0aW9uX3J1bGVfYXJyLmZpbHRlciggZnVuY3Rpb24gKCBpdGVtLCBwb3MgKXsgcmV0dXJuIGNvbmRpdGlvbl9ydWxlX2Fyci5pbmRleE9mKCBpdGVtICkgPT09IHBvczsgfSApO1xyXG4gICAgICAgICAgICB2YXIgY29uZGl0aW9uX3J1bGUgPSBjb25kaXRpb25fcnVsZV9hcnIuam9pbiggJywnICk7XHJcbiAgICAgICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX3RleHRhcmVhJyApLnZhbCggY29uZGl0aW9uX3J1bGUgKTtcclxuXHJcbiAgICAgICAgICAgIHdwYmNfc2V0X3Nob3J0Y29kZSgpO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgLy8gUmVkIGhpZ2hsaWdodCBmaWVsZHMsICBpZiBzb21lIHJlcXVpcmVkIGZpZWxkcyBhcmUgZW1wdHlcclxuICAgICAgICBpZiAoICgnZnVuY3Rpb24nID09PSB0eXBlb2YgKHdwYmNfZmllbGRfaGlnaGxpZ2h0KSkgJiYgKCcnID09PSBqUXVlcnkoICcjJyArIGlkICsgJ19fZGF5c19udW1iZXInICkudmFsKCkpICl7XHJcbiAgICAgICAgICAgIHdwYmNfZmllbGRfaGlnaGxpZ2h0KCAnIycgKyBpZCArICdfX2RheXNfbnVtYmVyJyApO1xyXG4gICAgICAgIH1cclxuICAgICAgICBpZiAoICgnZnVuY3Rpb24nID09PSB0eXBlb2YgKHdwYmNfZmllbGRfaGlnaGxpZ2h0KSkgJiYgKCcwJyA9PT0galF1ZXJ5KCAnIycgKyBpZCArICdfX3NlYXNvbl9maWx0ZXJfbmFtZScgKS52YWwoKSkgKXtcclxuICAgICAgICAgICAgd3BiY19maWVsZF9oaWdobGlnaHQoICcjJyArIGlkICsgJ19fc2Vhc29uX2ZpbHRlcl9uYW1lJyApO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICB9XHJcbiAgICBmdW5jdGlvbiB3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfc2Vhc29uX19yZXNldChpZCl7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBpZCArICdfX3NlYXNvbl9maWx0ZXJfbmFtZSBvcHRpb246ZXEoMCknICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX19kYXlzX251bWJlcicgKS52YWwoICcnICk7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBpZCArICdfdGV4dGFyZWEnICkudmFsKCAnJyApO1xyXG4gICAgICAgIHdwYmNfc2V0X3Nob3J0Y29kZSgpO1xyXG4gICAgfVxyXG5cclxuXHJcbiAgICAvKipcclxuICAgICAqIENvbmRpdGlvbjogICB7c3RhcnQtZGF5IGNvbmRpdGlvbj1cInNlYXNvblwiIGZvcj1cIkxvdyBzZWFzb25cIiB2YWx1ZT1cIjAsMSw1XCJ9XHJcbiAgICAgKi9cclxuICAgIGZ1bmN0aW9uIHdwYmNfc2hvcnRjb2RlX2NvbmZpZ19fc3RhcnRfZGF5X3NlYXNvbl9fYWRkKCBpZCApe1xyXG5cclxuICAgICAgICB2YXIgc2Vhc29uX2ZpbHRlcl9uYW1lID0galF1ZXJ5KCAnIycgKyBpZCArICdfX3NlYXNvbl9maWx0ZXJfbmFtZSBvcHRpb246c2VsZWN0ZWQnICkudGV4dCgpLnRyaW0oKTtcclxuICAgICAgICAvLyBFc2NhcGUgcXVvdGUgc3ltYm9sc1xyXG4gICAgICAgIHNlYXNvbl9maWx0ZXJfbmFtZSA9IHNlYXNvbl9maWx0ZXJfbmFtZS5yZXBsYWNlKC9bXFxcIlwiXS9nLCAnXFxcXFwiJyk7XHJcblxyXG4gICAgICAgIGlmIChcclxuICAgICAgICAgICAgICAgKCcnICE9IHNlYXNvbl9maWx0ZXJfbmFtZSlcclxuICAgICAgICAgICAgJiYgKDAgIT0galF1ZXJ5KCAnIycgKyBpZCArICdfX3NlYXNvbl9maWx0ZXJfbmFtZScgKS52YWwoKSlcclxuXHJcbiAgICAgICAgKXtcclxuICAgICAgICAgICAgdmFyIGFjdGl2YXRlZF93ZWVrZGF5cyA9W107XHJcbiAgICAgICAgICAgIGZvciAoIHZhciB3ZWVrZGF5X251bSA9IDA7IHdlZWtkYXlfbnVtIDwgODsgd2Vla2RheV9udW0rKyApe1xyXG4gICAgICAgICAgICAgICAgaWYgKCBqUXVlcnkoICcjJyArIGlkICsgJ19fd2Vla2RheV8nICsgd2Vla2RheV9udW0gKS5pcyggJzpjaGVja2VkJyApICl7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGFjdGl2YXRlZF93ZWVrZGF5cy5wdXNoKCB3ZWVrZGF5X251bSApO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGFjdGl2YXRlZF93ZWVrZGF5cyA9IGFjdGl2YXRlZF93ZWVrZGF5cy5qb2luKCAnLCcgKTtcclxuXHJcbiAgICAgICAgICAgIGlmICggJycgIT0gYWN0aXZhdGVkX3dlZWtkYXlzICl7XHJcblxyXG4gICAgICAgICAgICAgICAgdmFyIGV4aXN0X2NvbmZpZ3VyYXRpb24gPSBqUXVlcnkoICcjJyArIGlkICsgJ190ZXh0YXJlYScgKS52YWwoKTtcclxuXHJcbiAgICAgICAgICAgICAgICBleGlzdF9jb25maWd1cmF0aW9uID0gZXhpc3RfY29uZmlndXJhdGlvbi5yZXBsYWNlQWxsKCBcIn0se1wiLCAnfX5+eycgKVxyXG4gICAgICAgICAgICAgICAgdmFyIGNvbmRpdGlvbl9ydWxlX2FyciA9IGV4aXN0X2NvbmZpZ3VyYXRpb24uc3BsaXQoICd+ficgKTtcclxuXHJcbiAgICAgICAgICAgICAgICAvLyBSZW1vdmUgZW1wdHkgc3BhY2VzIGZyb20gIGFycmF5IDogJycgfCBcIlwiXHJcbiAgICAgICAgICAgICAgICBjb25kaXRpb25fcnVsZV9hcnIgPSBjb25kaXRpb25fcnVsZV9hcnIuZmlsdGVyKCBmdW5jdGlvbiAoIG4gKXtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gbjtcclxuICAgICAgICAgICAgICAgIH0gKTtcclxuXHJcbiAgICAgICAgICAgICAgICBjb25kaXRpb25fcnVsZV9hcnIucHVzaCggJ3tzdGFydC1kYXkgY29uZGl0aW9uPVwic2Vhc29uXCIgZm9yPVwiJyArIHNlYXNvbl9maWx0ZXJfbmFtZSArICdcIiB2YWx1ZT1cIicgKyBhY3RpdmF0ZWRfd2Vla2RheXMgKyAnXCJ9JyApO1xyXG5cclxuICAgICAgICAgICAgICAgIC8vIFJlbW92ZSBkdXBsaWNhdGVzIGZyb20gIHRoZSBhcnJheVxyXG4gICAgICAgICAgICAgICAgY29uZGl0aW9uX3J1bGVfYXJyID0gY29uZGl0aW9uX3J1bGVfYXJyLmZpbHRlciggZnVuY3Rpb24gKCBpdGVtLCBwb3MgKXtcclxuICAgICAgICAgICAgICAgICAgICByZXR1cm4gY29uZGl0aW9uX3J1bGVfYXJyLmluZGV4T2YoIGl0ZW0gKSA9PT0gcG9zO1xyXG4gICAgICAgICAgICAgICAgfSApO1xyXG4gICAgICAgICAgICAgICAgdmFyIGNvbmRpdGlvbl9ydWxlID0gY29uZGl0aW9uX3J1bGVfYXJyLmpvaW4oICcsJyApO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnIycgKyBpZCArICdfdGV4dGFyZWEnICkudmFsKCBjb25kaXRpb25fcnVsZSApO1xyXG5cclxuICAgICAgICAgICAgICAgIHdwYmNfc2V0X3Nob3J0Y29kZSgpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICAvLyBSZWQgaGlnaGxpZ2h0IGZpZWxkcywgIGlmIHNvbWUgcmVxdWlyZWQgZmllbGRzIGFyZSBlbXB0eVxyXG4gICAgICAgIGlmICggKCdmdW5jdGlvbicgPT09IHR5cGVvZiAod3BiY19maWVsZF9oaWdobGlnaHQpKSAmJiAoJzAnID09PSBqUXVlcnkoICcjJyArIGlkICsgJ19fc2Vhc29uX2ZpbHRlcl9uYW1lJyApLnZhbCgpKSApe1xyXG4gICAgICAgICAgICB3cGJjX2ZpZWxkX2hpZ2hsaWdodCggJyMnICsgaWQgKyAnX19zZWFzb25fZmlsdGVyX25hbWUnICk7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG4gICAgZnVuY3Rpb24gd3BiY19zaG9ydGNvZGVfY29uZmlnX19zdGFydF9kYXlfc2Vhc29uX19yZXNldChpZCl7XHJcbiAgICAgICAgalF1ZXJ5KCAnIycgKyBpZCArICdfX3NlYXNvbl9maWx0ZXJfbmFtZSBvcHRpb246ZXEoMCknICkucHJvcCggJ3NlbGVjdGVkJywgdHJ1ZSApO1xyXG4gICAgICAgIGZvciAoIHZhciB3ZWVrZGF5X251bSA9IDA7IHdlZWtkYXlfbnVtIDwgODsgd2Vla2RheV9udW0rKyApe1xyXG4gICAgICAgICAgICBpZiAoIGpRdWVyeSggJyMnICsgaWQgKyAnX193ZWVrZGF5XycgKyB3ZWVrZGF5X251bSApLmlzKCAnOmNoZWNrZWQnICkgKXtcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX193ZWVrZGF5XycgKyB3ZWVrZGF5X251bSApLnByb3AoICdjaGVja2VkJywgZmFsc2UgKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuICAgICAgICBqUXVlcnkoICcjJyArIGlkICsgJ190ZXh0YXJlYScgKS52YWwoICcnICk7XHJcbiAgICAgICAgd3BiY19zZXRfc2hvcnRjb2RlKCk7XHJcbiAgICB9XHJcblxyXG5cclxuICAgIC8qKlxyXG4gICAgICogQ29uZGl0aW9uOiAgIHtzZWxlY3QtZGF5IGNvbmRpdGlvbj1cImRhdGVcIiBmb3I9XCIyMDIzLTEwLTAxXCIgdmFsdWU9XCIyMCwyNSwzMC0zNVwifVxyXG4gICAgICovXHJcbiAgICBmdW5jdGlvbiB3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfZm9yZGF0ZV9fYWRkKGlkKXtcclxuXHJcbiAgICAgICAgdmFyIHN0YXJ0X2RhdGVfX2ZvcmRhdGUgPSBqUXVlcnkoICcjJyArIGlkICsgJ19fZGF0ZScgKS52YWwoKS50cmltKCk7XHJcbiAgICAgICAgLy8gUmVtb3ZlIGFsbCB3b3JkcyBleGNlcHQgZGlnaXRzIGFuZCAsIGFuZCAtXHJcbiAgICAgICAgc3RhcnRfZGF0ZV9fZm9yZGF0ZSA9IHN0YXJ0X2RhdGVfX2ZvcmRhdGUucmVwbGFjZSggL1teMC05LV0vZywgJycgKTtcclxuXHJcbiAgICAgICAgdmFyIGdsb2JhbFJlZ2V4ID0gbmV3IFJlZ0V4cCggL15cXGR7NH0tWzAxXXsxfVxcZHsxfS1bMDEyM117MX1cXGR7MX0kLywgJ2cnICk7XHJcbiAgICAgICAgdmFyIGlzX3ZhbGlkX2RhdGUgPSBnbG9iYWxSZWdleC50ZXN0KCBzdGFydF9kYXRlX19mb3JkYXRlICk7XHJcbiAgICAgICAgaWYgKCAhaXNfdmFsaWRfZGF0ZSApe1xyXG4gICAgICAgICAgICBzdGFydF9kYXRlX19mb3JkYXRlID0gJyc7XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX19kYXRlJyApLnZhbCggc3RhcnRfZGF0ZV9fZm9yZGF0ZSApO1xyXG5cclxuICAgICAgICB2YXIgZGF5c19udW1iZXIgPSBqUXVlcnkoICcjJyArIGlkICsgJ19fZGF5c19udW1iZXInICkudmFsKCkudHJpbSgpO1xyXG4gICAgICAgIC8vIFJlbW92ZSBhbGwgd29yZHMgZXhjZXB0IGRpZ2l0cyBhbmQgLCBhbmQgLVxyXG4gICAgICAgIGRheXNfbnVtYmVyID0gZGF5c19udW1iZXIucmVwbGFjZSggL1teMC05LC1dL2csICcnICk7XHJcbiAgICAgICAgZGF5c19udW1iZXIgPSBkYXlzX251bWJlci5yZXBsYWNlKCAvWyxdezIsfS9nLCAnLCcgKTtcclxuICAgICAgICBkYXlzX251bWJlciA9IGRheXNfbnVtYmVyLnJlcGxhY2UoIC9bLV17Mix9L2csICctJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX19kYXlzX251bWJlcicgKS52YWwoIGRheXNfbnVtYmVyICk7XHJcblxyXG4gICAgICAgIGlmIChcclxuICAgICAgICAgICAgICAgKCcnICE9IGRheXNfbnVtYmVyKVxyXG4gICAgICAgICAgICAmJiAoJycgIT0gc3RhcnRfZGF0ZV9fZm9yZGF0ZSlcclxuICAgICAgICAgICAgJiYgKDAgIT0galF1ZXJ5KCAnIycgKyBpZCArICdfX3NlYXNvbl9maWx0ZXJfbmFtZScgKS52YWwoKSlcclxuXHJcbiAgICAgICAgKXtcclxuICAgICAgICAgICAgdmFyIGV4aXN0X2NvbmZpZ3VyYXRpb24gPSBqUXVlcnkoICcjJyArIGlkICsgJ190ZXh0YXJlYScgKS52YWwoKTtcclxuXHJcbiAgICAgICAgICAgIGV4aXN0X2NvbmZpZ3VyYXRpb24gPSBleGlzdF9jb25maWd1cmF0aW9uLnJlcGxhY2VBbGwoXCJ9LHtcIiwgJ31+fnsnKVxyXG4gICAgICAgICAgICB2YXIgY29uZGl0aW9uX3J1bGVfYXJyID0gZXhpc3RfY29uZmlndXJhdGlvbi5zcGxpdCggJ35+JyApO1xyXG5cclxuICAgICAgICAgICAgLy8gUmVtb3ZlIGVtcHR5IHNwYWNlcyBmcm9tICBhcnJheSA6ICcnIHwgXCJcIlxyXG4gICAgICAgICAgICBjb25kaXRpb25fcnVsZV9hcnIgPSBjb25kaXRpb25fcnVsZV9hcnIuZmlsdGVyKGZ1bmN0aW9uKG4pe3JldHVybiBuOyB9KTtcclxuXHJcbiAgICAgICAgICAgIGNvbmRpdGlvbl9ydWxlX2Fyci5wdXNoKCAne3NlbGVjdC1kYXkgY29uZGl0aW9uPVwiZGF0ZVwiIGZvcj1cIicgKyBzdGFydF9kYXRlX19mb3JkYXRlICsgJ1wiIHZhbHVlPVwiJyArIGRheXNfbnVtYmVyICsgJ1wifScgKTtcclxuXHJcbiAgICAgICAgICAgIC8vIFJlbW92ZSBkdXBsaWNhdGVzIGZyb20gIHRoZSBhcnJheVxyXG4gICAgICAgICAgICBjb25kaXRpb25fcnVsZV9hcnIgPSBjb25kaXRpb25fcnVsZV9hcnIuZmlsdGVyKCBmdW5jdGlvbiAoIGl0ZW0sIHBvcyApeyByZXR1cm4gY29uZGl0aW9uX3J1bGVfYXJyLmluZGV4T2YoIGl0ZW0gKSA9PT0gcG9zOyB9ICk7XHJcbiAgICAgICAgICAgIHZhciBjb25kaXRpb25fcnVsZSA9IGNvbmRpdGlvbl9ydWxlX2Fyci5qb2luKCAnLCcgKTtcclxuICAgICAgICAgICAgalF1ZXJ5KCAnIycgKyBpZCArICdfdGV4dGFyZWEnICkudmFsKCBjb25kaXRpb25fcnVsZSApO1xyXG5cclxuICAgICAgICAgICAgICAgICB3cGJjX3NldF9zaG9ydGNvZGUoKTtcclxuICAgICAgICB9IGVsc2VcclxuXHJcbiAgICAgICAgLy8gUmVkIGhpZ2hsaWdodCBmaWVsZHMsICBpZiBzb21lIHJlcXVpcmVkIGZpZWxkcyBhcmUgZW1wdHlcclxuICAgICAgICBpZiAoICgnZnVuY3Rpb24nID09PSB0eXBlb2YgKHdwYmNfZmllbGRfaGlnaGxpZ2h0KSkgJiYgKCcnID09PSBqUXVlcnkoICcjJyArIGlkICsgJ19fZGF0ZScgKS52YWwoKSkgKXtcclxuICAgICAgICAgICAgd3BiY19maWVsZF9oaWdobGlnaHQoICcjJyArIGlkICsgJ19fZGF0ZScgKTtcclxuICAgICAgICB9XHJcbiAgICAgICAgaWYgKCAoJ2Z1bmN0aW9uJyA9PT0gdHlwZW9mICh3cGJjX2ZpZWxkX2hpZ2hsaWdodCkpICYmICgnJyA9PT0galF1ZXJ5KCAnIycgKyBpZCArICdfX2RheXNfbnVtYmVyJyApLnZhbCgpKSApe1xyXG4gICAgICAgICAgICB3cGJjX2ZpZWxkX2hpZ2hsaWdodCggJyMnICsgaWQgKyAnX19kYXlzX251bWJlcicgKTtcclxuICAgICAgICB9XHJcbiAgICB9XHJcbiAgICBmdW5jdGlvbiB3cGJjX3Nob3J0Y29kZV9jb25maWdfX3NlbGVjdF9kYXlfZm9yZGF0ZV9fcmVzZXQoaWQpe1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX19kYXRlJyApLnZhbCggJycgKTtcclxuICAgICAgICBqUXVlcnkoICcjJyArIGlkICsgJ19fZGF5c19udW1iZXInICkudmFsKCAnJyApO1xyXG4gICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX3RleHRhcmVhJyApLnZhbCggJycgKTtcclxuICAgICAgICB3cGJjX3NldF9zaG9ydGNvZGUoKTtcclxuICAgIH1cclxuXHJcblxyXG4gICAgXHJcbmZ1bmN0aW9uIHdwYmNfc2hvcnRjb2RlX2NvbmZpZ19fdXBkYXRlX2VsZW1lbnRzX2luX3RpbWVsaW5lKCl7XHJcblxyXG4gICAgdmFyIHdwYmNfaXNfbWF0cml4ID0gZmFsc2U7XHJcblxyXG4gICAgaWYgKCBqUXVlcnkoICcjYm9va2luZ3RpbWVsaW5lX3dwYmNfbXVsdGlwbGVfcmVzb3VyY2VzJyApLmxlbmd0aCA+IDAgKSB7XHJcblxyXG4gICAgICAgIHZhciBib29raW5ndGltZWxpbmVfd3BiY19tdWx0aXBsZV9yZXNvdXJjZXNfdGVtcCA9IGpRdWVyeSggJyNib29raW5ndGltZWxpbmVfd3BiY19tdWx0aXBsZV9yZXNvdXJjZXMnICkudmFsKCk7XHJcblxyXG4gICAgICAgIGlmICggKCBib29raW5ndGltZWxpbmVfd3BiY19tdWx0aXBsZV9yZXNvdXJjZXNfdGVtcCAhPSBudWxsICkgJiYgKCBib29raW5ndGltZWxpbmVfd3BiY19tdWx0aXBsZV9yZXNvdXJjZXNfdGVtcC5sZW5ndGggPiAwICkgICl7XHJcblxyXG4gICAgICAgICAgICBqUXVlcnkoIFwiaW5wdXRbbmFtZT0nYm9va2luZ3RpbWVsaW5lX3dwYmNfdmlld19tb2RlX3RpbWVsaW5lX21vbnRoc19udW1faW5fcm93J11cIiApLnByb3AoIFwiZGlzYWJsZWRcIiwgZmFsc2UgKTtcclxuICAgICAgICAgICAgalF1ZXJ5KCBcIi53cGJjX3NjX2NvbnRhaW5lcl9fc2hvcnRjb2RlX2Jvb2tpbmd0aW1lbGluZSBsYWJlbC53cGJjLWZvcm0tcmFkaW9cIiApLnNob3coKTtcclxuXHJcbiAgICAgICAgICAgIGlmIChcclxuICAgICAgICAgICAgICAgICAgICAoIGJvb2tpbmd0aW1lbGluZV93cGJjX211bHRpcGxlX3Jlc291cmNlc190ZW1wLmxlbmd0aCA+IDEgKVxyXG4gICAgICAgICAgICAgICAgfHwgICggKGJvb2tpbmd0aW1lbGluZV93cGJjX211bHRpcGxlX3Jlc291cmNlc190ZW1wLmxlbmd0aCA9PSAxKSAmJiAoYm9va2luZ3RpbWVsaW5lX3dwYmNfbXVsdGlwbGVfcmVzb3VyY2VzX3RlbXBbIDAgXSA9PSAnMCcpKVxyXG4gICAgICAgICAgICApeyAgLy8gTWF0cml4XHJcbiAgICAgICAgICAgICAgICB3cGJjX2lzX21hdHJpeCA9IHRydWU7XHJcbiAgICAgICAgICAgICAgICBqUXVlcnkoIFwiaW5wdXRbbmFtZT0nYm9va2luZ3RpbWVsaW5lX3dwYmNfdmlld19tb2RlX3RpbWVsaW5lX21vbnRoc19udW1faW5fcm93J11bdmFsdWU9JzkwJ11cIiApLnByb3AoIFwiZGlzYWJsZWRcIiwgdHJ1ZSApO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCBcImlucHV0W25hbWU9J2Jvb2tpbmd0aW1lbGluZV93cGJjX3ZpZXdfbW9kZV90aW1lbGluZV9tb250aHNfbnVtX2luX3JvdyddW3ZhbHVlPSc5MCddXCIgKS5wYXJlbnRzKCcud3BiYy1mb3JtLXJhZGlvJykuaGlkZSgpO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCBcImlucHV0W25hbWU9J2Jvb2tpbmd0aW1lbGluZV93cGJjX3ZpZXdfbW9kZV90aW1lbGluZV9tb250aHNfbnVtX2luX3JvdyddW3ZhbHVlPSczNjUnXVwiICkucHJvcCggXCJkaXNhYmxlZFwiLCB0cnVlICk7XHJcbiAgICAgICAgICAgICAgICBqUXVlcnkoIFwiaW5wdXRbbmFtZT0nYm9va2luZ3RpbWVsaW5lX3dwYmNfdmlld19tb2RlX3RpbWVsaW5lX21vbnRoc19udW1faW5fcm93J11bdmFsdWU9JzM2NSddXCIgKS5wYXJlbnRzKCcud3BiYy1mb3JtLXJhZGlvJykuaGlkZSgpO1xyXG4gICAgICAgICAgICB9IGVsc2UgeyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gU2luZ2xlXHJcbiAgICAgICAgICAgICAgICBqUXVlcnkoIFwiaW5wdXRbbmFtZT0nYm9va2luZ3RpbWVsaW5lX3dwYmNfdmlld19tb2RlX3RpbWVsaW5lX21vbnRoc19udW1faW5fcm93J11bdmFsdWU9JzEnXVwiICkucHJvcCggXCJkaXNhYmxlZFwiLCB0cnVlICk7XHJcbiAgICAgICAgICAgICAgICBqUXVlcnkoIFwiaW5wdXRbbmFtZT0nYm9va2luZ3RpbWVsaW5lX3dwYmNfdmlld19tb2RlX3RpbWVsaW5lX21vbnRoc19udW1faW5fcm93J11bdmFsdWU9JzEnXVwiICkucGFyZW50cygnLndwYmMtZm9ybS1yYWRpbycpLmhpZGUoKTtcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5ndGltZWxpbmVfd3BiY192aWV3X21vZGVfdGltZWxpbmVfbW9udGhzX251bV9pbl9yb3cnXVt2YWx1ZT0nNyddXCIgKS5wcm9wKCBcImRpc2FibGVkXCIsIHRydWUgKTtcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5ndGltZWxpbmVfd3BiY192aWV3X21vZGVfdGltZWxpbmVfbW9udGhzX251bV9pbl9yb3cnXVt2YWx1ZT0nNyddXCIgKS5wYXJlbnRzKCcud3BiYy1mb3JtLXJhZGlvJykuaGlkZSgpO1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCBcImlucHV0W25hbWU9J2Jvb2tpbmd0aW1lbGluZV93cGJjX3ZpZXdfbW9kZV90aW1lbGluZV9tb250aHNfbnVtX2luX3JvdyddW3ZhbHVlPSc2MCddXCIgKS5wcm9wKCBcImRpc2FibGVkXCIsIHRydWUgKTtcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5ndGltZWxpbmVfd3BiY192aWV3X21vZGVfdGltZWxpbmVfbW9udGhzX251bV9pbl9yb3cnXVt2YWx1ZT0nNjAnXVwiICkucGFyZW50cygnLndwYmMtZm9ybS1yYWRpbycpLmhpZGUoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgIGlmICggalF1ZXJ5KCBcImlucHV0W25hbWU9J2Jvb2tpbmd0aW1lbGluZV93cGJjX3ZpZXdfbW9kZV90aW1lbGluZV9tb250aHNfbnVtX2luX3JvdyddOmNoZWNrZWRcIiApLmlzKCc6ZGlzYWJsZWQnKSApIHtcclxuICAgICAgICAgICAgICAgIGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5ndGltZWxpbmVfd3BiY192aWV3X21vZGVfdGltZWxpbmVfbW9udGhzX251bV9pbl9yb3cnXVt2YWx1ZT0nMzAnXVwiICkucHJvcCggXCJjaGVja2VkXCIsIHRydWUgKTtcclxuICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIHZhciB2aWV3X2RheXNfbnVtX3RlbXAgPSAzMDtcclxuICAgIGlmICggalF1ZXJ5KCBcImlucHV0W25hbWU9J2Jvb2tpbmd0aW1lbGluZV93cGJjX3ZpZXdfbW9kZV90aW1lbGluZV9tb250aHNfbnVtX2luX3JvdyddOmNoZWNrZWRcIiApLmxlbmd0aCA+IDAgKXtcclxuICAgICAgICB2YXIgdmlld19kYXlzX251bV90ZW1wID0gcGFyc2VJbnQoIGpRdWVyeSggXCJpbnB1dFtuYW1lPSdib29raW5ndGltZWxpbmVfd3BiY192aWV3X21vZGVfdGltZWxpbmVfbW9udGhzX251bV9pbl9yb3cnXTpjaGVja2VkXCIgKS52YWwoKS50cmltKCkgKTtcclxuICAgIH1cclxuXHJcbiAgICAvLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXHJcbiAgICAvLyBIaWRlIG9yIFNob3cgU2Nyb2xsaW5nIERheXMgYW5kIE1vbnRocywgZGVwZW5kaW5nIG9uIGZyb20gdHlwZSBvZiB2aWV3IGFuZCBudW1iZXIgb2YgYm9va2luZyByZXNvdXJjZXNcclxuICAgIC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy9cclxuICAgIGpRdWVyeSggXCIjd3BiY19ib29raW5ndGltZWxpbmVfc2Nyb2xsX21vbnRoLCN3cGJjX2Jvb2tpbmd0aW1lbGluZV9zY3JvbGxfZGF5XCIgKS5wcm9wKCBcImRpc2FibGVkXCIsIGZhbHNlICk7XHJcbiAgICBqUXVlcnkoIFwiLndwYmNfYm9va2luZ3RpbWVsaW5lX3Njcm9sbF9tb250aCwud3BiY19ib29raW5ndGltZWxpbmVfc2Nyb2xsX2RheVwiICkuc2hvdygpO1xyXG4gICAgLy8gTWF0cml4IC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy9cclxuICAgIGlmIChcclxuICAgICAgICAgICggd3BiY19pc19tYXRyaXggKSAmJiAoICggdmlld19kYXlzX251bV90ZW1wID09IDEgKSB8fCAoIHZpZXdfZGF5c19udW1fdGVtcCA9PSA3ICkgKSAvLyBEYXkgfCBXZWVrIHZpZXdcclxuICAgICAgICApIHtcclxuICAgICAgICAgICAgalF1ZXJ5KCBcIiN3cGJjX2Jvb2tpbmd0aW1lbGluZV9zY3JvbGxfbW9udGhcIiApLnByb3AoIFwiZGlzYWJsZWRcIiwgdHJ1ZSApOyAgICAgICAgICAgICAgICAgICAgICAgICAgICAvLyBTY3JvbGwgTW9udGggTk9UIHdvcmtpbmdcclxuICAgICAgICAgICAgalF1ZXJ5KCAnLndwYmNfYm9va2luZ3RpbWVsaW5lX3Njcm9sbF9tb250aCcgKS5oaWRlKCk7XHJcbiAgICAgICAgfVxyXG4gICAgaWYgKFxyXG4gICAgICAgICAgKCB3cGJjX2lzX21hdHJpeCApJiYgKCAoIHZpZXdfZGF5c19udW1fdGVtcCA9PSAzMCApIHx8ICggdmlld19kYXlzX251bV90ZW1wID09IDYwICkgKSAvLyBNb250aCB2aWV3XHJcbiAgICAgICAgKSB7XHJcbiAgICAgICAgICAgIGpRdWVyeSggXCIjd3BiY19ib29raW5ndGltZWxpbmVfc2Nyb2xsX2RheVwiICkucHJvcCggXCJkaXNhYmxlZFwiLCB0cnVlICk7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gU2Nyb2xsIERheXMgTk9UIHdvcmtpbmdcclxuICAgICAgICAgICAgalF1ZXJ5KCAnLndwYmNfYm9va2luZ3RpbWVsaW5lX3Njcm9sbF9kYXknICkuaGlkZSgpO1xyXG4gICAgICAgIH1cclxuICAgIC8vIFNpbmdsZSAvLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXHJcbiAgICBpZiAoXHJcbiAgICAgICAgICAoICEgd3BiY19pc19tYXRyaXggKSAmJiAoICggdmlld19kYXlzX251bV90ZW1wID09IDMwICkgfHwgKCB2aWV3X2RheXNfbnVtX3RlbXAgPT0gOTAgKSApICAvLyBNb250aCB8IDMgTW9udGhzIHZpZXcgKGxpa2Ugd2VlayB2aWV3KVxyXG4gICAgICAgICkge1xyXG4gICAgICAgICAgICBqUXVlcnkoIFwiI3dwYmNfYm9va2luZ3RpbWVsaW5lX3Njcm9sbF9tb250aFwiICkucHJvcCggXCJkaXNhYmxlZFwiLCB0cnVlICk7ICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIFNjcm9sbCBNb250aCBOT1Qgd29ya2luZ1xyXG4gICAgICAgICAgICBqUXVlcnkoICcud3BiY19ib29raW5ndGltZWxpbmVfc2Nyb2xsX21vbnRoJyApLmhpZGUoKTtcclxuICAgICAgICB9XHJcbiAgICBpZiAoXHJcbiAgICAgICAgICAoICEgd3BiY19pc19tYXRyaXggKSYmICggKCB2aWV3X2RheXNfbnVtX3RlbXAgPT0gMzY1ICkgKSAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIFllYXIgdmlld1xyXG4gICAgICAgICkge1xyXG4gICAgICAgICAgICBqUXVlcnkoIFwiI3dwYmNfYm9va2luZ3RpbWVsaW5lX3Njcm9sbF9kYXlcIiApLnByb3AoIFwiZGlzYWJsZWRcIiwgdHJ1ZSApOyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIFNjcm9sbCBEYXlzIE5PVCB3b3JraW5nXHJcbiAgICAgICAgICAgIGpRdWVyeSggJy53cGJjX2Jvb2tpbmd0aW1lbGluZV9zY3JvbGxfZGF5JyApLmhpZGUoKTtcclxuICAgICAgICB9XHJcbiAgICAvLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXHJcblxyXG5cclxuICAgIHJldHVybiBbIHdwYmNfaXNfbWF0cml4LCB2aWV3X2RheXNfbnVtX3RlbXAgXTtcclxufSAgICBcclxuXHJcbiAgICBcclxualF1ZXJ5KCBkb2N1bWVudCApLnJlYWR5KCBmdW5jdGlvbiAoKXtcclxuICAgIC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAvLyBbYm9va2luZyAuLi4gXVxyXG5cclxuICAgIHZhciBzaG9ydGNvZGVfYXJyID0gWydib29raW5nJywgJ2Jvb2tpbmdjYWxlbmRhcicsICdib29raW5nc2VsZWN0JywgJ2Jvb2tpbmd0aW1lbGluZScsICdib29raW5nZm9ybScsICdib29raW5nc2VhcmNoJywgJ2Jvb2tpbmdvdGhlcicsICdib29raW5nX2ltcG9ydF9pY3MnICwgJ2Jvb2tpbmdfbGlzdGluZ19pY3MnXTtcclxuXHJcbiAgICBmb3IgKCB2YXIgc2hvcnRjZGVfa2V5IGluIHNob3J0Y29kZV9hcnIgKXtcclxuXHJcbiAgICAgICAgdmFyIGlkID0gc2hvcnRjb2RlX2Fyclsgc2hvcnRjZGVfa2V5IF07XHJcblxyXG4gICAgICAgIC8vIC0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS1cclxuICAgICAgICAvLyBIaWRlIGJ5IFNpemUgc2VjdGlvbnNcclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgalF1ZXJ5KCAnLicgKyBpZCArICdfd3BiY19zaXplX3dwYmNfc2NfY2FsZW5kYXJfc2l6ZScgKS5oaWRlKCk7XHJcblxyXG4gICAgICAgIC8vIG9wdGlvbnMgOjogU2hvdyAvIEhpZGUgU0laRSBjYWxlbmRhciAgc2VjdGlvblxyXG4gICAgICAgIGpRdWVyeSggJyMnICsgaWQgKyAnX3dwYmNfc2l6ZV9lbmFibGVkJyApLm9uKCAnY2hhbmdlJywgeydpZCc6IGlkfSwgZnVuY3Rpb24oIGV2ZW50ICl7XHJcbiAgICAgICAgICAgIGlmICggalF1ZXJ5KCAnIycgKyBldmVudC5kYXRhLmlkICsgJ193cGJjX3NpemVfZW5hYmxlZCcgKS5pcyggJzpjaGVja2VkJyApICl7XHJcbiAgICAgICAgICAgICAgICBqUXVlcnkoICcuJyArIGV2ZW50LmRhdGEuaWQgKyAnX3dwYmNfc2l6ZV93cGJjX3NjX2NhbGVuZGFyX3NpemUnICkuc2hvdygpO1xyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgalF1ZXJ5KCAnLicgKyBldmVudC5kYXRhLmlkICsgJ193cGJjX3NpemVfd3BiY19zY19jYWxlbmRhcl9zaXplJyApLmhpZGUoKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0gKTtcclxuXHJcbiAgICAgICAgLy8gLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG4gICAgICAgIC8vIFVwZGF0ZSBTaG9ydGNvZGUgb24gY2hhbmdpbmc6IFNpemVcclxuICAgICAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tXHJcbiAgICAgICAgalF1ZXJ5KCAgICcjJyArIGlkICsgJ193cGJjX3NpemVfZW5hYmxlZCcgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIFNpemUgT24gfCBPZmZcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfc2l6ZV9tb250aHNfbnVtX2luX3JvdycgICAgICAgICAgICAgICAgICAgLy8gLSBNb250aCBOdW0gaW4gUm93XHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX3NpemVfY2FsZW5kYXJfd2lkdGgnICAgICAgICAgICAgICAgICAgICAgIC8vIC0gV2lkdGhcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfc2l6ZV9jYWxlbmRhcl93aWR0aF9weF9wcicgICAgICAgICAgICAgICAgLy8gLSBXaWR0aCBQUyB8ICVcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfc2l6ZV9jYWxlbmRhcl9jZWxsX2hlaWdodCcgICAgICAgICAgICAgICAgLy8gLSBDZWxsIEhlaWdodFxyXG5cclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnd3BiY19zZWxlY3RfZGF5X3dlZWtkYXlfdGV4dGFyZWEnICAgICAgICAgICAgICAgLy8gUnVsZSBXZWVrZGF5XHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ3dwYmNfc2VsZWN0X2RheV9zZWFzb25fdGV4dGFyZWEnICAgICAgICAgICAgICAgIC8vIFJ1bGUgU2Vhc29uXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ3dwYmNfc3RhcnRfZGF5X3NlYXNvbl90ZXh0YXJlYScgICAgICAgICAgICAgICAgIC8vIFJ1bGUgU2Vhc29uIC0gU3RhcnQgZGF5XHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ3dwYmNfc2VsZWN0X2RheV9mb3JkYXRlX3RleHRhcmVhJyAgICAgICAgICAgICAgIC8vIFJ1bGUgRGF0ZVxyXG5cclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfcmVzb3VyY2VfaWQnICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gUmVzb3VyY2UgSURcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfY3VzdG9tX2Zvcm0nICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gQ3VzdG9tIEZvcm1cclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfbnVtbW9udGhzJyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gTnVtIE1vbnRoc1xyXG5cclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfc3RhcnRtb250aF9hY3RpdmUnICAgICAgICAgICAgICAgICAgICAgICAvLyBTdGFydCBNb250aCBFbmFibGVcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfc3RhcnRtb250aF95ZWFyJyAgICAgICAgICAgICAgICAgICAgICAgICAvLyAgLSBZZWFyXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX3N0YXJ0bW9udGhfbW9udGgnICAgICAgICAgICAgICAgICAgICAgICAgLy8gIC0gTW9udGhcclxuXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX2FnZ3JlZ2F0ZScgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgLy8gQWdncmVnYXRlXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX2FnZ3JlZ2F0ZV9fYm9va2luZ3Nfb25seScgICAgICAgICAgICAgICAgLy8gYWdncmVnYXRlIG9wdGlvblxyXG5cclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfbXVsdGlwbGVfcmVzb3VyY2VzJyAgICAgICAgICAgICAgICAgICAgIC8vIFtib29raW5nc2VsZWN0XSAtIE11bHRpcGxlIFJlc291cmNlc1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfd3BiY19zZWxlY3RlZF9yZXNvdXJjZScgICAgICAgICAgICAgICAgICAgICAgLy8gW2Jvb2tpbmdzZWxlY3RdIC0gU2VsZWN0ZWQgUmVzb3VyY2VcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfdGV4dF9sYWJlbCcgICAgICAgICAgICAgICAgICAgICAgICAgICAgIC8vIFtib29raW5nc2VsZWN0XSAtIExhYmVsXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX2ZpcnN0X29wdGlvbl90aXRsZScgICAgICAgICAgICAgICAgICAgICAvLyBbYm9va2luZ3NlbGVjdF0gLSBGaXJzdCAgT3B0aW9uXHJcblxyXG4gICAgICAgICAgICAgICAgLy8gVGltZUxpbmVcclxuICAgICAgICAgICAgICAgICtcIixpbnB1dFtuYW1lPSdcIisgaWQgK1wiX3dwYmNfdmlld19tb2RlX3RpbWVsaW5lX21vbnRoc19udW1faW5fcm93J11cIlxyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfd3BiY190ZXh0X2xhYmVsX3RpbWVsaW5lJ1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfd3BiY19zY3JvbGxfdGltZWxpbmVfc2Nyb2xsX2RheXMnXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX3Njcm9sbF90aW1lbGluZV9zY3JvbGxfbW9udGgnXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX3N0YXJ0X2RhdGVfdGltZWxpbmVfYWN0aXZlJ1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfd3BiY19zdGFydF9kYXRlX3RpbWVsaW5lX3llYXInXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX3N0YXJ0X2RhdGVfdGltZWxpbmVfbW9udGgnXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX3N0YXJ0X2RhdGVfdGltZWxpbmVfZGF5J1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfd3BiY19zdGFydF9lbmRfdGltZV90aW1lbGluZV9zdGFydHRpbWUnXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX3N0YXJ0X2VuZF90aW1lX3RpbWVsaW5lX2VuZHRpbWUnXHJcblxyXG4gICAgICAgICAgICAgICAgLy8gRm9ybSBPbmx5XHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX2Jvb2tpbmdfZGF0ZV95ZWFyJ1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfd3BiY19ib29raW5nX2RhdGVfbW9udGgnXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX2Jvb2tpbmdfZGF0ZV9kYXknXHJcblxyXG4gICAgICAgICAgICAgICAgLy8gW2Jvb2tpbmdzZWFyY2ggLi4uXVxyXG4gICAgICAgICAgICAgICAgK1wiLGlucHV0W25hbWU9J1wiKyBpZCArXCJfd3BiY19zZWFyY2hfZm9ybV9yZXN1bHRzJ11cIlxyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfd3BiY19zZWFyY2hfbmV3X3BhZ2VfZW5hYmxlZCdcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfc2VhcmNoX25ld19wYWdlX3VybCdcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfc2VhcmNoX2hlYWRlcidcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfc2VhcmNoX25vdGhpbmdfZm91bmQnXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ193cGJjX3NlYXJjaF9mb3JfdXNlcnMnXHJcblxyXG4gICAgICAgICAgICAgICAgLy8gW2Jvb2tpbmdvdGhlciAuLi4gXVxyXG4gICAgICAgICAgICAgICAgK1wiLGlucHV0W25hbWU9J1wiKyBpZCArXCJfd3BiY19zaG9ydGNvZGVfdHlwZSddXCJcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfcmVzb3VyY2Vfc2hvdydcclxuXHJcbiAgICAgICAgICAgICAgICAvL2Jvb2tpbmdfaW1wb3J0X2ljcyAsIGJvb2tpbmdfbGlzdGluZ19pY3NcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3dwYmNfdXJsJ1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfZnJvbSdcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX2Zyb21fb2Zmc2V0J1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfZnJvbV9vZmZzZXRfdHlwZSdcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX3VudGlsJ1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfdW50aWxfb2Zmc2V0J1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfdW50aWxfb2Zmc2V0X3R5cGUnXHJcbiAgICAgICAgICAgICAgICArJywjJyArIGlkICsgJ19jb25kaXRpb25zX2ltcG9ydCdcclxuICAgICAgICAgICAgICAgICsnLCMnICsgaWQgKyAnX2NvbmRpdGlvbnNfZXZlbnRzJ1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfY29uZGl0aW9uc19tYXhfbnVtJ1xyXG4gICAgICAgICAgICAgICAgKycsIycgKyBpZCArICdfc2lsZW5jZSdcclxuICAgICAgICAgICAgKS5vbiggJ2NoYW5nZScsIHsnaWQnOiBpZH0sIGZ1bmN0aW9uKGV2ZW50KXtcclxuICAgICAgICAgICAgICAgICAgICAvL2NvbnNvbGUubG9nKCAnb24gY2hhbmdlIHdwYmNfc2V0X3Nob3J0Y29kZScsIGV2ZW50LmRhdGEuaWQgKTtcclxuICAgICAgICAgICAgICAgICAgICB3cGJjX3NldF9zaG9ydGNvZGUoKTtcclxuICAgICAgICAgICAgfSk7XHJcbiAgICB9XHJcbiAgICAvLyAtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLVxyXG4gICAgd3BiY19zZXRfc2hvcnRjb2RlKCk7XHJcbn0pO1xyXG4iXSwiZmlsZSI6ImluY2x1ZGVzL19zaG9ydGNvZGVfcG9wdXAvX291dC93cGJjX3Nob3J0Y29kZV9wb3B1cC5qcyJ9
