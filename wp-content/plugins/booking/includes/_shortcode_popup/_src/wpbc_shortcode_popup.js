/**
 * Shortcode Config - Main Loop
 */
function wpbc_set_shortcode(){

    var wpbc_shortcode = '[';
    var shortcode_id = jQuery( '#wpbc_shortcode_type' ).val().trim();

    // -----------------------------------------------------------------------------------------------------------------
    // [booking]  | [bookingcalendar] | ...
    // -----------------------------------------------------------------------------------------------------

    if (
           ( 'booking' === shortcode_id )
        || ( 'bookingcalendar' === shortcode_id )
        || ( 'bookingselect' === shortcode_id )
        || ( 'bookingtimeline' === shortcode_id )
        || ( 'bookingform' === shortcode_id )
        || ( 'bookingsearch' === shortcode_id )
        || ( 'bookingother' === shortcode_id )

        || ( 'booking_import_ics' === shortcode_id )
        || ( 'booking_listing_ics' === shortcode_id )
    ){

        wpbc_shortcode += shortcode_id;

        var wpbc_options_arr = [];

        // -------------------------------------------------------------------------------------------------------------
        // [bookingselect] | [bookingtimeline] - Options relative only to this shortcode.
        // -------------------------------------------------------------------------------------------------------------
        if (
               ( 'bookingselect' === shortcode_id )
            || ( 'bookingtimeline' === shortcode_id )
        ){

            // [bookingselect type='1,2,3'] - Multiple Resources
            if ( jQuery( '#' + shortcode_id + '_wpbc_multiple_resources' ).length > 0 ){

                var multiple_resources = jQuery( '#' + shortcode_id + '_wpbc_multiple_resources' ).val();

                if ( (multiple_resources != null) && (multiple_resources.length > 0) ){

                    // Remove empty spaces from  array : '' | "" | 0
                    multiple_resources = multiple_resources.filter(function(n){return parseInt(n); });

                    multiple_resources = multiple_resources.join( ',' ).trim();

                    if ( multiple_resources != 0 ){
                        wpbc_shortcode += ' type=\'' + multiple_resources + '\'';
                    }
                }
            }

            // [bookingselect selected_type=1] - Selected Resource
            if ( jQuery( '#' + shortcode_id + '_wpbc_selected_resource' ).length > 0 ){
                if (
                       ( jQuery( '#' + shortcode_id + '_wpbc_selected_resource' ).val() !== null )                      //FixIn: 8.2.1.12
                    && ( parseInt( jQuery( '#' + shortcode_id + '_wpbc_selected_resource' ).val() ) > 0 )
                ){
                    wpbc_shortcode += ' selected_type=' + jQuery( '#' + shortcode_id + '_wpbc_selected_resource' ).val().trim();
                }
            }

            // [bookingselect label='Tada'] - Label
            if ( jQuery( '#' + shortcode_id + '_wpbc_text_label' ).length > 0 ){
                if ( '' !== jQuery( '#' + shortcode_id + '_wpbc_text_label' ).val().trim() ){
                    wpbc_shortcode += ' label=\'' + jQuery( '#' + shortcode_id + '_wpbc_text_label' ).val().trim().replace( /'/gi, '' ) + '\'';
                }
            }

            // [bookingselect first_option_title='Tada'] - First  Option
            if ( jQuery( '#' + shortcode_id + '_wpbc_first_option_title' ).length > 0 ){
                if ( '' !== jQuery( '#' + shortcode_id + '_wpbc_first_option_title' ).val().trim() ){
                    wpbc_shortcode += ' first_option_title=\'' + jQuery( '#' + shortcode_id + '_wpbc_first_option_title' ).val().trim().replace( /'/gi, '' ) + '\'';
                }
            }
        }


        // -------------------------------------------------------------------------------------------------------------
        // [bookingtimeline] - Options relative only to this shortcode.
        // -------------------------------------------------------------------------------------------------------------
        if ( 'bookingtimeline' === shortcode_id ){
            // Visually update
            var wpbc_is_matrix__view_days_num_temp = wpbc_shortcode_config__update_elements_in_timeline();
            var wpbc_is_matrix = wpbc_is_matrix__view_days_num_temp[ 0 ];
            var view_days_num_temp = wpbc_is_matrix__view_days_num_temp[ 1 ];

            // : view_days_num
            if ( view_days_num_temp != 30 ){
                wpbc_shortcode += ' view_days_num=' + view_days_num_temp;
            }
            // : header_title
            if ( jQuery( '#' + shortcode_id + '_wpbc_text_label_timeline' ).length > 0 ){
                var header_title_temp = jQuery( '#' + shortcode_id + '_wpbc_text_label_timeline' ).val().trim();
                header_title_temp = header_title_temp.replace( /'/gi, '' );
                if ( header_title_temp != '' ){
                    wpbc_shortcode += ' header_title=\'' + header_title_temp + '\'';
                }
            }
            // : scroll_month
            if (
                   (   jQuery( '#' + shortcode_id + '_wpbc_scroll_timeline_scroll_month' ).is( ':visible' ))
                && (   jQuery( '#' + shortcode_id + '_wpbc_scroll_timeline_scroll_month' ).length > 0)
                && (parseInt( jQuery( '#' + shortcode_id + '_wpbc_scroll_timeline_scroll_month' ).val().trim() ) !== 0)
            ){
                wpbc_shortcode += ' scroll_month=' + parseInt( jQuery( '#' + shortcode_id + '_wpbc_scroll_timeline_scroll_month' ).val().trim() );
            }
            // : scroll_day
            if (
                   (   jQuery( '#' + shortcode_id + '_wpbc_scroll_timeline_scroll_days' ).is( ':visible' ))
                && (   jQuery( '#' + shortcode_id + '_wpbc_scroll_timeline_scroll_days' ).length > 0)
                && (parseInt( jQuery( '#' + shortcode_id + '_wpbc_scroll_timeline_scroll_days' ).val().trim() ) !== 0)
            ){
                wpbc_shortcode += ' scroll_day=' + parseInt( jQuery( '#' + shortcode_id + '_wpbc_scroll_timeline_scroll_days' ).val().trim() );
            }

            // :limit_hours
            //FixIn: 7.0.1.17
            jQuery( '.bookingtimeline_view_times' ).hide();
            if (
                   ( ( wpbc_is_matrix ) && ( view_days_num_temp == 1 ) )
                || ( ( ! wpbc_is_matrix ) && ( view_days_num_temp == 30 ) )
            ) {
                jQuery( '.bookingtimeline_view_times' ).show();
                var view_times_start_temp = parseInt( jQuery( '#bookingtimeline_wpbc_start_end_time_timeline_starttime' ).val().trim() );
                var view_times_end_temp = parseInt( jQuery( '#bookingtimeline_wpbc_start_end_time_timeline_endtime' ).val().trim() );
                if ( (view_times_start_temp != 0) || (view_times_end_temp != 24) ){
                    wpbc_shortcode += ' limit_hours=\'' + view_times_start_temp + ',' + view_times_end_temp + '\'';
                }
            }

            // :scroll_start_date
            if (  ( jQuery('#bookingtimeline_wpbc_start_date_timeline_active').is(':checked') )  && ( jQuery( '#bookingtimeline_wpbc_start_date_timeline_active' ).length > 0 )  ) {
                 wpbc_shortcode += ' scroll_start_date=\'' + jQuery( '#bookingtimeline_wpbc_start_date_timeline_year' ).val().trim()
                                                     + '-' + jQuery( '#bookingtimeline_wpbc_start_date_timeline_month' ).val().trim()
                                                     + '-' + jQuery( '#bookingtimeline_wpbc_start_date_timeline_day' ).val().trim()
                                                    + '\'';
            }

        }

        // -------------------------------------------------------------------------------------------------------------
        // [bookingform  ] - Form Only        -     [bookingform type=1 selected_dates='01.03.2024']
        // -------------------------------------------------------------------------------------------------------------
        if ( 'bookingform' === shortcode_id ){

            var wpbc_selected_day = jQuery( '#' + shortcode_id + '_wpbc_booking_date_day' ).val().trim();
            if ( parseInt(wpbc_selected_day) < 10 ){
                wpbc_selected_day = '0' + wpbc_selected_day;
            }
            var wpbc_selected_month = jQuery( '#' + shortcode_id + '_wpbc_booking_date_month' ).val().trim();
            if ( parseInt(wpbc_selected_month) < 10 ){
                wpbc_selected_month = '0' + wpbc_selected_month;
            }
            wpbc_shortcode += ' selected_dates=\'' + wpbc_selected_day + '.' + wpbc_selected_month + '.' + jQuery( '#' + shortcode_id + '_wpbc_booking_date_year' ).val().trim() + '\'';
        }

        // -------------------------------------------------------------------------------------------------------------
        // [bookingsearch  ] - Options relative only to this shortcode.     [bookingsearch searchresultstitle='{searchresults} Result(s) Found' noresultstitle='Nothing Found']
        // -------------------------------------------------------------------------------------------------------------
        if ( 'bookingsearch' === shortcode_id ){

            // Check  if we selected 'bookingsearch' | 'bookingsearchresults'
            var wpbc_search_form_results = 'bookingsearch';
            if ( jQuery( "input[name='bookingsearch_wpbc_search_form_results']:checked" ).length > 0 ){
                wpbc_search_form_results = jQuery( "input[name='bookingsearch_wpbc_search_form_results']:checked" ).val().trim();
            }

            // Show | Hide form  fields for 'bookingsearch' depends from  radio  bution  selection
            if ( 'bookingsearchresults' === wpbc_search_form_results ){
                wpbc_shortcode = '[bookingsearchresults';
                jQuery( '.wpbc_search_availability_form' ).hide();
            } else {
                jQuery( '.wpbc_search_availability_form' ).show();


                // New page for search results
                if (
                    (jQuery( '#' + shortcode_id + '_wpbc_search_new_page_enabled' ).length > 0)
                    && (jQuery( '#' + shortcode_id + '_wpbc_search_new_page_enabled' ).is( ':checked' ))
                ){
                    // Show
                    jQuery( '.' + shortcode_id + '_wpbc_search_new_page_wpbc_sc_searchresults_new_page' ).show();

                    // : Search Results URL
                    if ( jQuery( '#' + shortcode_id + '_wpbc_search_new_page_url' ).length > 0 ){
                        var search_results_url_temp = jQuery( '#' + shortcode_id + '_wpbc_search_new_page_url' ).val().trim();
                        search_results_url_temp = search_results_url_temp.replace( /'/gi, '' );
                        if ( search_results_url_temp != '' ){
                            wpbc_shortcode += ' searchresults=\'' + search_results_url_temp + '\'';
                        }
                    }
                } else {
                    // Hide
                    jQuery( '.' + shortcode_id + '_wpbc_search_new_page_wpbc_sc_searchresults_new_page' ).hide();
                }

                // : Search Header
                if ( jQuery( '#' + shortcode_id + '_wpbc_search_header' ).length > 0 ){
                    var search_header_temp = jQuery( '#' + shortcode_id + '_wpbc_search_header' ).val().trim();
                    search_header_temp = search_header_temp.replace( /'/gi, '' );
                    if ( search_header_temp != '' ){
                        wpbc_shortcode += ' searchresultstitle=\'' + search_header_temp + '\'';
                    }
                }
                // : Nothing Found
                if ( jQuery( '#' + shortcode_id + '_wpbc_search_nothing_found' ).length > 0 ){
                    var nothingfound_temp = jQuery( '#' + shortcode_id + '_wpbc_search_nothing_found' ).val().trim();
                    nothingfound_temp = nothingfound_temp.replace( /'/gi, '' );
                    if ( nothingfound_temp != '' ){
                        wpbc_shortcode += ' noresultstitle=\'' + nothingfound_temp + '\'';
                    }
                }
                // : Users      // [bookingsearch searchresultstitle='{searchresults} Result(s) Found' noresultstitle='Nothing Found' users='3,4543,']
                if ( jQuery( '#' + shortcode_id + '_wpbc_search_for_users' ).length > 0 ){
                    var only_for_users_temp = jQuery( '#' + shortcode_id + '_wpbc_search_for_users' ).val().trim();
                    only_for_users_temp = only_for_users_temp.replace( /'/gi, '' );
                    if ( only_for_users_temp != '' ){
                        wpbc_shortcode += ' users=\'' + only_for_users_temp + '\'';
                    }
                }

            }
        }


        // -------------------------------------------------------------------------------------------------------------
        // [bookingedit] , [bookingcustomerlisting] , [bookingresource type=6 show='capacity'] , [booking_confirm]
        // -------------------------------------------------------------------------------------------------------------
        if ( 'bookingother' === shortcode_id ){

            //TRICK:
            shortcode_id = 'no';  //required for not update booking resource ID

            // Check  if we selected 'bookingsearch' | 'bookingsearchresults'
            var bookingother_shortcode_type = 'bookingsearch';
            if ( jQuery( "input[name='bookingother_wpbc_shortcode_type']:checked" ).length > 0 ){
                bookingother_shortcode_type = jQuery( "input[name='bookingother_wpbc_shortcode_type']:checked" ).val().trim();
            }

            // Show | Hide sections
            if ( 'booking_confirm' === bookingother_shortcode_type ){
                wpbc_shortcode = '[booking_confirm';
                jQuery( '.bookingother_section_additional' ).hide();
                jQuery( '.bookingother_section_' + bookingother_shortcode_type ).show();
            }
            if ( 'bookingedit' === bookingother_shortcode_type ){
                wpbc_shortcode = '[bookingedit';
                jQuery( '.bookingother_section_additional' ).hide();
                jQuery( '.bookingother_section_' + bookingother_shortcode_type ).show();
            }
            if ( 'bookingcustomerlisting' === bookingother_shortcode_type ){
                wpbc_shortcode = '[bookingcustomerlisting';
                jQuery( '.bookingother_section_additional' ).hide();
                jQuery( '.bookingother_section_' + bookingother_shortcode_type ).show();

            }
            if ( 'bookingresource' === bookingother_shortcode_type ){

                //TRICK:
                shortcode_id = 'bookingother';  //required to force update booking resource ID

                wpbc_shortcode = '[bookingresource';
                jQuery( '.bookingother_section_additional' ).hide();
                jQuery( '.bookingother_section_' + bookingother_shortcode_type ).show();

                if ( jQuery( '#bookingother_wpbc_resource_show' ).val().trim() != 'title' ){
                    wpbc_shortcode += ' show=\'' + jQuery( '#bookingother_wpbc_resource_show' ).val().trim() + '\'';
                }
            }
        }

        // [booking-manager-import ...]     ||      [booking-manager-listing ...]
        if ( ('booking_import_ics' === shortcode_id) || ('booking_listing_ics' === shortcode_id) ){

            wpbc_shortcode = '[booking-manager-import';

            if ( 'booking_listing_ics' === shortcode_id ){
                wpbc_shortcode = '[booking-manager-listing';
            }

            ////////////////////////////////////////////////////////////////
            // : .ics feed URL
            ////////////////////////////////////////////////////////////////
            var shortcode_url_temp = ''
            if ( jQuery( '#' + shortcode_id + '_wpbc_url' ).length > 0 ){
                shortcode_url_temp = jQuery( '#' + shortcode_id + '_wpbc_url' ).val().trim();
                shortcode_url_temp = shortcode_url_temp.replace( /'/gi, '' );
                if ( shortcode_url_temp != '' ){
                    wpbc_shortcode += ' url=\'' + shortcode_url_temp + '\'';
                }
            }


            if ( shortcode_url_temp == '' ){
                // Error:
                wpbc_shortcode = '[ URL is required '

            } else {
                // VALID:

                ////////////////////////////////////////////////////////////////
                // [... from='' 'from_offset=''  ...]
                ////////////////////////////////////////////////////////////////
                if ( jQuery( '#' + shortcode_id + '_from' ).length > 0 ){
                    var p_from          = jQuery( '#' + shortcode_id + '_from' ).val().trim();
                    var p_from_offset   = jQuery( '#' + shortcode_id + '_from_offset' ).val().trim();

                    p_from        = p_from.replace( /'/gi, '' );
                    p_from_offset = p_from_offset.replace( /'/gi, '' );

                    if ( ('' != p_from) && ('date' != p_from) ){                                                        // Offset

                        wpbc_shortcode += ' from=\'' + p_from + '\'';

                        if ( ('any' != p_from) && ('' != p_from_offset) ){
                            p_from_offset = parseInt( p_from_offset );
                            if ( !isNaN( p_from_offset ) ){
                                wpbc_shortcode += ' from_offset=\'' + p_from_offset + jQuery( '#' + shortcode_id + '_from_offset_type' ).val().trim().charAt( 0 ) + '\'';
                            }
                        }

                    } else if ( (p_from == 'date') && (p_from_offset != '') ){		                                    // If selected Date
                        wpbc_shortcode += ' from=\'' + p_from_offset + '\'';
                    }
                }

                ////////////////////////////////////////////////////////////////
                // [... until='' 'until_offset=''  ...]
                ////////////////////////////////////////////////////////////////
                if ( jQuery( '#' + shortcode_id + '_until' ).length > 0 ){
                    var p_until          = jQuery( '#' + shortcode_id + '_until' ).val().trim();
                    var p_until_offset   = jQuery( '#' + shortcode_id + '_until_offset' ).val().trim();

                    p_until        = p_until.replace( /'/gi, '' );
                    p_until_offset = p_until_offset.replace( /'/gi, '' );

                    if ( ('' != p_until) && ('date' != p_until) ){                                                        // Offset

                        wpbc_shortcode += ' until=\'' + p_until + '\'';

                        if ( ('any' != p_until) && ('' != p_until_offset) ){
                            p_until_offset = parseInt( p_until_offset );
                            if ( !isNaN( p_until_offset ) ){
                                wpbc_shortcode += ' until_offset=\'' + p_until_offset + jQuery( '#' + shortcode_id + '_until_offset_type' ).val().trim().charAt( 0 ) + '\'';
                            }
                        }

                    } else if ( (p_until == 'date') && (p_until_offset != '') ){		                                    // If selected Date
                        wpbc_shortcode += ' until=\'' + p_until_offset + '\'';
                    }
                }

				////////////////////////////////////////////////////////////////
				// Max
				////////////////////////////////////////////////////////////////
                if ( jQuery( '#' + shortcode_id + '_conditions_max_num' ).length > 0 ){
                    var p_max = parseInt( jQuery(  '#' + shortcode_id + '_conditions_max_num' ).val().trim() );
                    if ( p_max != 0 ){
                        wpbc_shortcode += ' max=' + p_max;
                    }
                }

				////////////////////////////////////////////////////////////////
				// Silence
				////////////////////////////////////////////////////////////////
                if ( jQuery( '#' + shortcode_id + '_silence' ).length > 0 ){
                    if ( '1' === jQuery( '#' + shortcode_id + '_silence' ).val().trim() ){
                        wpbc_shortcode += ' silence=1';
                    }
                }

				////////////////////////////////////////////////////////////////
				// is_all_dates_in
				////////////////////////////////////////////////////////////////
                if ( jQuery( '#' + shortcode_id + '_conditions_events' ).length > 0 ){
                    var p_is_all_dates_in = parseInt( jQuery( '#' + shortcode_id + '_conditions_events'  ).val().trim() );
                    if ( p_is_all_dates_in != 0 ){
                        wpbc_shortcode += ' is_all_dates_in=' + p_is_all_dates_in;
                    }
                }

				////////////////////////////////////////////////////////////////
				// import_conditions
				////////////////////////////////////////////////////////////////
                if ( jQuery( '#' + shortcode_id + '_conditions_import' ).length > 0 ){
                    var p_import_conditions = jQuery(  '#' + shortcode_id + '_conditions_import' ).val().trim();
                    p_import_conditions = p_import_conditions.replace( /'/gi, '' );
                    if ( p_import_conditions != '' ){
                        wpbc_shortcode += ' import_conditions=\'' + p_import_conditions + '\'';
                    }
                }

            }
        }


        // -------------------------------------------------------------------------------------------------------------
        // [booking] , [bookingcalendar] , ...  parameters for these shortcodes and others...
        // -------------------------------------------------------------------------------------------------------------
        if ( jQuery( '#' + shortcode_id + '_wpbc_resource_id' ).length > 0 ) {
            if ( jQuery( '#' + shortcode_id + '_wpbc_resource_id' ).val() === null ) {											//FixIn: 8.2.1.12
                jQuery( '#wpbc_text_put_in_shortcode' ).val( '---' );
                return;
            } else {
                wpbc_shortcode += ' resource_id=' + jQuery( '#' + shortcode_id + '_wpbc_resource_id' ).val().trim();
            }
        }
        if ( jQuery( '#' + shortcode_id + '_wpbc_custom_form' ).length > 0 ) {
            var form_type_temp = jQuery( '#' + shortcode_id + '_wpbc_custom_form' ).val().trim();
            if ( form_type_temp != 'standard' )
                wpbc_shortcode += ' form_type=\'' + jQuery( '#' + shortcode_id + '_wpbc_custom_form' ).val().trim() + '\'';
        }
        if (
                ( jQuery( '#' + shortcode_id + '_wpbc_nummonths' ).length > 0 )
             && ( parseInt( jQuery( '#' + shortcode_id + '_wpbc_nummonths' ).val().trim() ) > 1 )
        ){
            wpbc_shortcode += ' nummonths=' + jQuery( '#' + shortcode_id + '_wpbc_nummonths' ).val().trim();
        }

        if (
                ( jQuery('#' + shortcode_id + '_wpbc_startmonth_active').length > 0 )
             && ( jQuery('#' + shortcode_id + '_wpbc_startmonth_active').is(':checked') )
        ){
             wpbc_shortcode += ' startmonth=\'' + jQuery( '#' + shortcode_id + '_wpbc_startmonth_year' ).val().trim() + '-' + jQuery( '#' + shortcode_id + '_wpbc_startmonth_month' ).val().trim() + '\'';
        }

        if ( jQuery( '#' + shortcode_id + '_wpbc_aggregate' ).length > 0 ) {
            var wpbc_aggregate_temp = jQuery( '#' + shortcode_id + '_wpbc_aggregate' ).val();

            if ( ( wpbc_aggregate_temp != null ) && ( wpbc_aggregate_temp.length > 0 )  ){
                wpbc_aggregate_temp = wpbc_aggregate_temp.join(';')

                if ( wpbc_aggregate_temp != 0 ){                     // Check about 0=>'None'
                    wpbc_shortcode += ' aggregate=\'' + wpbc_aggregate_temp + '\'';

                    if ( jQuery('#' + shortcode_id + '_wpbc_aggregate__bookings_only').is(':checked') ){
                        wpbc_options_arr.push( '{aggregate type=bookings_only}' );
                    }
                }
            }
        }

        // -------------------------------------------------------------------------------------------------------------
        // Option Param
        // -------------------------------------------------------------------------------------------------------------
        // Options : Size
        var wpbc_options_size = '';
        if (
                ( jQuery('#' + shortcode_id + '_wpbc_size_enabled').length > 0 )
             && ( jQuery('#' + shortcode_id + '_wpbc_size_enabled').is(':checked') )
        ){

            // options='{calendar months_num_in_row=2 width=100% cell_height=40px}'

            wpbc_options_size += '{calendar' ;
            wpbc_options_size += ' ' + 'months_num_in_row='
                                                      + Math.min(
                                                                  parseInt( jQuery( '#' + shortcode_id + '_wpbc_size_months_num_in_row' ).val().trim() ),
                                                                  parseInt( jQuery( '#' + shortcode_id + '_wpbc_nummonths' ).val().trim() )
                                                                );
            wpbc_options_size += ' ' + 'width=' + parseInt( jQuery( '#' + shortcode_id + '_wpbc_size_calendar_width' ).val().trim() )
                                                          + jQuery( '#' + shortcode_id + '_wpbc_size_calendar_width_px_pr' ).val().trim() ;
            wpbc_options_size += ' ' + 'cell_height=' + parseInt( jQuery( '#' + shortcode_id + '_wpbc_size_calendar_cell_height' ).val().trim() ) + 'px';
            wpbc_options_size += '}';
            wpbc_options_arr.push( wpbc_options_size );
        }

        // Options: Days number depend on   Weekday
        if ( jQuery( '#' + shortcode_id + 'wpbc_select_day_weekday_textarea' ).length > 0 ) {
            wpbc_options_size = jQuery( '#' + shortcode_id + 'wpbc_select_day_weekday_textarea' ).val().trim();
            if ( wpbc_options_size.length > 0 ){
                wpbc_options_arr.push( wpbc_options_size );
            }
        }

        // Options: Days number depend on   SEASON
        if ( jQuery( '#' + shortcode_id + 'wpbc_select_day_season_textarea' ).length > 0 ) {
            wpbc_options_size = jQuery( '#' + shortcode_id + 'wpbc_select_day_season_textarea' ).val().trim();
            if ( wpbc_options_size.length > 0 ){
                wpbc_options_arr.push( wpbc_options_size );
            }
        }

        // Options: Start weekday depend on   SEASON
        if ( jQuery( '#' + shortcode_id + 'wpbc_start_day_season_textarea' ).length > 0 ) {
            wpbc_options_size = jQuery( '#' + shortcode_id + 'wpbc_start_day_season_textarea' ).val().trim();
            if ( wpbc_options_size.length > 0 ){
                wpbc_options_arr.push( wpbc_options_size );
            }
        }

        // Option: Days number depend on from  DATE
        if ( jQuery( '#' + shortcode_id + 'wpbc_select_day_fordate_textarea' ).length > 0 ) {
            wpbc_options_size = jQuery( '#' + shortcode_id + 'wpbc_select_day_fordate_textarea' ).val().trim();
            if ( wpbc_options_size.length > 0 ){
                wpbc_options_arr.push( wpbc_options_size );
            }
        }

        if ( wpbc_options_arr.length > 0 ){
            wpbc_shortcode += ' options=\'' + wpbc_options_arr.join( ',' ) + '\'';
        }
    }


    wpbc_shortcode += ']';

    jQuery( '#wpbc_text_put_in_shortcode' ).val( wpbc_shortcode );
}

    /**
     * Open TinyMCE Modal */
    function wpbc_tiny_btn_click( tag ) {
        //FixIn: 9.0.1.5
        jQuery('#wpbc_tiny_modal').wpbc_my_modal({
            keyboard: false
          , backdrop: true
          , show: true
        });
        //FixIn: 8.3.3.99
        jQuery( "#wpbc_text_gettenberg_section_id" ).val( '' );

    }

    /**
     * Open TinyMCE Modal */
    function wpbc_tiny_close() {

        jQuery('#wpbc_tiny_modal').wpbc_my_modal('hide');	//FixIn: 9.0.1.5
    }

    /* ------------------------------------------------------------------------------------------------------------------ */
    /** Send Text */
    /* ------------------------------------------------------------------------------------------------------------------ */
    /**
     * Send text  to editor */
    function wpbc_send_text_to_editor( h ) {

        // FixIn: 8.3.3.99
        if ( typeof( wpbc_send_text_to_gutenberg ) == 'function' ){
            var is_send = wpbc_send_text_to_gutenberg( h );
            if ( true === is_send ){
                return;
            }
        }

            var ed, mce = typeof(tinymce) != 'undefined', qt = typeof(QTags) != 'undefined';

            if ( !wpActiveEditor ) {
                    if ( mce && tinymce.activeEditor ) {
                            ed = tinymce.activeEditor;
                            wpActiveEditor = ed.id;
                    } else if ( !qt ) {
                            return false;
                    }
            } else if ( mce ) {
                    if ( tinymce.activeEditor && (tinymce.activeEditor.id == 'mce_fullscreen' || tinymce.activeEditor.id == 'wp_mce_fullscreen') )
                            ed = tinymce.activeEditor;
                    else
                            ed = tinymce.get(wpActiveEditor);
            }

            if ( ed && !ed.isHidden() ) {
                    // restore caret position on IE
                    if ( tinymce.isIE && ed.windowManager.insertimagebookmark )
                            ed.selection.moveToBookmark(ed.windowManager.insertimagebookmark);

                    if ( h.indexOf('[caption') !== -1 ) {
                            if ( ed.wpSetImgCaption )
                                    h = ed.wpSetImgCaption(h);
                    } else if ( h.indexOf('[gallery') !== -1 ) {
                            if ( ed.plugins.wpgallery )
                                    h = ed.plugins.wpgallery._do_gallery(h);
                    } else if ( h.indexOf('[embed') === 0 ) {
                            if ( ed.plugins.wordpress )
                                    h = ed.plugins.wordpress._setEmbed(h);
                    }

                    ed.execCommand('mceInsertContent', false, h);
            } else if ( qt ) {
                    QTags.insertContent(h);
            } else {
                    document.getElementById(wpActiveEditor).value += h;
            }

            try{tb_remove();}catch(e){};
    }

    /**
     * RESOURCES PAGE: Open TinyMCE Modal */
    function wpbc_resource_page_btn_click( resource_id , shortcode_default_value = '') {

        //FixIn: 9.0.1.5
        jQuery('#wpbc_tiny_modal').wpbc_my_modal({
            keyboard: false
          , backdrop: true
          , show: true
        });

        // Disable some options - selection of booking resource - because we configure it only for specific booking resource, where we clicked.
        var shortcode_arr = ['booking', 'bookingcalendar', 'bookingform'];

        for ( var shortcde_key in shortcode_arr ){

            var shortcode_id = shortcode_arr[ shortcde_key ];

            jQuery( '#' + shortcode_id + '_wpbc_resource_id' ).prop( 		 'disabled', false );
            jQuery( '#' + shortcode_id + "_wpbc_resource_id option[value='" + resource_id + "']" ).prop( 'selected', true ).trigger( 'change' );
            jQuery( '#' + shortcode_id + '_wpbc_resource_id' ).prop( 		 'disabled', true );
        }

        // Hide left  navigation  items
//        jQuery( ".wpbc_shortcode_config_navigation_column .wpbc_settings_navigation_item" ).hide();
        jQuery( "#wpbc_shortcode_config__nav_tab__booking" ).show();
        jQuery( "#wpbc_shortcode_config__nav_tab__bookingcalendar" ).show();

        // Hide | Show Insert  button  for booking resource page
        jQuery( ".wpbc_tiny_button__insert_to_editor" ).hide();
        jQuery( ".wpbc_tiny_button__insert_to_resource" ).show();
    }

    /**
     * Get Shortcode Value from  shortcode text field in PopUp shortcode Config dialog and insert  into DIV and INPUT TEXT field near specific booking resource.
     *  But it takes ID  of booking resource,  where to  insert  this shortcode only from  'booking' section  of Config Dialog. usually  such  booking resource  disabled there!
     *  e.g.: jQuery( "#booking_wpbc_resource_id" ).val()
     *
     * @param shortcode_val
     */
    function wpbc_send_text_to_resource( shortcode_val ){

        jQuery( '#div_booking_resource_shortcode_' + jQuery( "#booking_wpbc_resource_id" ).val() ).html( shortcode_val );
            jQuery( '#booking_resource_shortcode_' + jQuery( "#booking_wpbc_resource_id" ).val() ).val( shortcode_val );
            jQuery( '#booking_resource_shortcode_' + jQuery( "#booking_wpbc_resource_id" ).val() ).trigger('change');

        // Scroll
        if ( 'function' === typeof (wpbc_scroll_to) ){
            wpbc_scroll_to( '#div_booking_resource_shortcode_' + jQuery( "#booking_wpbc_resource_id" ).val() );
        }
    }

    /* R E S E T */
    function wpbc_shortcode_config__reset(shortcode_val){
        jQuery( '#' + shortcode_val + '_wpbc_startmonth_active' ).prop( 'checked', false ).trigger('change');

        jQuery( '#' + shortcode_val + '_wpbc_aggregate option:selected').prop( 'selected', false);
        jQuery( '#' + shortcode_val + '_wpbc_aggregate option:eq(0)'   ).prop( 'selected', true );
        jQuery( '#' + shortcode_val + '_wpbc_aggregate__bookings_only' ).prop( 'checked', false ).trigger('change');

        jQuery( '#' + shortcode_val + '_wpbc_custom_form option:eq(0)' ).prop( 'selected', true );
        jQuery( '#' + shortcode_val + '_wpbc_nummonths option:eq(0)' ).prop( 'selected', true );
        jQuery( '#' + shortcode_val + '_wpbc_size_enabled' ).prop( 'checked', false ).trigger('change');

        wpbc_shortcode_config__select_day_weekday__reset( shortcode_val + 'wpbc_select_day_weekday' );
        wpbc_shortcode_config__select_day_season__reset( shortcode_val + 'wpbc_select_day_season' );
        wpbc_shortcode_config__start_day_season__reset( shortcode_val + 'wpbc_start_day_season' );
        wpbc_shortcode_config__select_day_fordate__reset( shortcode_val + 'wpbc_select_day_fordate' );

        // Reset  for [bookingselect] shortcode params
        jQuery( '#' + shortcode_val + '_wpbc_multiple_resources option:selected').prop( 'selected', false);
        jQuery( '#' + shortcode_val + '_wpbc_multiple_resources option:eq(0)' ).prop( 'selected', true ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_selected_resource option:eq(0)' ).prop( 'selected', true ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_text_label' ).val( '' ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_first_option_title' ).val( '' ).trigger('change');

        // Reset  for [bookingtimeline] shortcode params
        jQuery( '#' + shortcode_val + '_wpbc_text_label_timeline' ).val( '' ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_scroll_timeline_scroll_month option[value="0"]' ).prop( 'selected', true ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_scroll_timeline_scroll_days option[value="0"]' ).prop( 'selected', true ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_start_date_timeline_active' ).prop( 'checked', false ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_start_end_time_timeline_starttime option[value="0"]' ).prop( 'selected', true ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_start_end_time_timeline_endtime option[value="24"]' ).prop( 'selected', true ).trigger('change');
        jQuery( 'input[name="' + shortcode_val + '_wpbc_view_mode_timeline_months_num_in_row"][value="30"]' ).prop( 'checked', true ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_start_date_timeline_year option[value="' + (new Date().getFullYear()) + '"]' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_wpbc_start_date_timeline_month option[value="' + ((new Date().getMonth()) + 1) + '"]' ).prop( 'selected', true ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_start_date_timeline_day option[value="' + (new Date().getDate()) + '"]' ).prop( 'selected', true ).trigger('change');

        // Reset  for [bookingform] shortcode params
        jQuery( '#' + shortcode_val + '_wpbc_booking_date_year option[value="' + (new Date().getFullYear()) + '"]' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_wpbc_booking_date_month option[value="' + ((new Date().getMonth()) + 1) + '"]' ).prop( 'selected', true ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_booking_date_day option[value="' + (new Date().getDate()) + '"]' ).prop( 'selected', true ).trigger('change');

        // Reset  for [[bookingsearch ...] shortcode params
        jQuery( '#' + shortcode_val + '_wpbc_search_new_page_url' ).val( '' ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_search_new_page_enabled' ).prop( 'checked', false ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_search_header' ).val( '' ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_search_nothing_found' ).val( '' ).trigger('change');
        jQuery( '#' + shortcode_val + '_wpbc_search_for_users' ).val( '' ).trigger('change');
        jQuery( 'input[name="' + shortcode_val + '_wpbc_search_form_results"][value="bookingsearch"]' ).prop( 'checked', true ).trigger('change');

        // Reset  for [bookingedit] , [bookingcustomerlisting] , [bookingresource type=6 show='capacity'] , [booking_confirm]
        jQuery( 'input[name="' + shortcode_val + '_wpbc_shortcode_type"][value="booking_confirm"]' ).prop( 'checked', true ).trigger('change');


        // booking_import_ics , booking_listing_ics
        jQuery( '#' + shortcode_val + '_wpbc_url' ).val( '' ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_from option[value="today"]' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_from_offset' ).val( '' ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_from_offset_type option:eq(0)' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_until option[value="any"]' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_until_offset' ).val( '' ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_until_offset_type option:eq(0)' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_conditions_import option:eq(0)' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_conditions_events option[value="1"]' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_conditions_max_num option[value="0"]' ).prop( 'selected', true ).trigger( 'change' );
        jQuery( '#' + shortcode_val + '_silence option[value="0"]' ).prop( 'selected', true ).trigger( 'change' );
    }

/* ------------------------------------------------------------------------------------------------------------------ */
/**
 *  SHORTCODE_CONFIG
 * */
/* ------------------------------------------------------------------------------------------------------------------ */

/**
 * When click on menu item in "Left Vertical Navigation" panel  in shortcode config popup
 */
function wpbc_shortcode_config_click_show_section( _this, section_id_to_show, shortcode_name ){

    // Menu
    jQuery( _this ).parents( '.wpbc_settings_flex_container' ).find( '.wpbc_settings_navigation_item_active' ).removeClass( 'wpbc_settings_navigation_item_active' );
    jQuery( _this ).parents( '.wpbc_settings_navigation_item' ).addClass( 'wpbc_settings_navigation_item_active' );

    // Content
    jQuery( _this ).parents( '.wpbc_settings_flex_container' ).find( '.wpbc_sc_container__shortcode' ).hide();
    jQuery( section_id_to_show ).show();

    // Scroll
    if ( 'function' === typeof (wpbc_scroll_to) ){
        wpbc_scroll_to( section_id_to_show );
    }
    // Set - Shortcode Type
    jQuery( '#wpbc_shortcode_type').val( shortcode_name );

    // Parse shortcode params
    wpbc_set_shortcode();
}


    /**
     * Do Next / Prior step
     * @param _this		button  this
     * @param step		'prior' | 'next'
     */
    function wpbc_shortcode_config_content_toolbar__next_prior( _this, step ){

        var j_work_nav_tab;

        var submenu_selected = jQuery( _this ).parents( '.wpbc_sc_container__shortcode' ).find( '.wpbc_sc_container__shortcode_section:visible' ).find( '.wpdevelop-submenu-tab-selected:visible' );
        if ( submenu_selected.length ){
            if ( 'next' === step ){
                j_work_nav_tab = submenu_selected.nextAll( 'a.nav-tab:visible' ).first();
            } else {
                j_work_nav_tab = submenu_selected.prevAll( 'a.nav-tab:visible' ).first();
            }
            if ( j_work_nav_tab.length ){
                j_work_nav_tab.trigger( 'click' );
                return;
            }
        }

        if ( 'next' === step ){
            j_work_nav_tab = jQuery( _this ).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab.nav-tab-active:visible' ).nextAll( 'a.nav-tab:visible' ).first();
        } else{
            j_work_nav_tab = jQuery( _this ).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab.nav-tab-active:visible' ).prevAll( 'a.nav-tab:visible' ).first();
        }

        if ( j_work_nav_tab.length ){
            j_work_nav_tab.trigger( 'click' );
        }

    }


    /**
     * Condition:   {select-day condition="weekday" for="5" value="3"}
     */
    function wpbc_shortcode_config__select_day_weekday__add(id){
        var condition_rule_arr = [];
        for ( var weekday_num = 0; weekday_num < 8; weekday_num++ ){
            if ( jQuery( '#' + id + '__weekday_' + weekday_num ).is( ':checked' ) ){
                var days_to_select = jQuery( '#' + id + '__days_number_' + weekday_num ).val().trim();
                // Remove all words except digits and , and -
                days_to_select = days_to_select.replace(/[^0-9,-]/g, '');
                days_to_select = days_to_select.replace(/[,]{2,}/g, ',');
                days_to_select = days_to_select.replace(/[-]{2,}/g, '-');
                jQuery( '#' + id + '__days_number_' + weekday_num ).val( days_to_select );

                if ( '' !== days_to_select ){
                    condition_rule_arr.push( '{select-day condition="weekday" for="' + weekday_num + '" value="' + days_to_select + '"}' );
                } else {
                    // Red highlight fields,  if some required fields are empty
                    if ( ('function' === typeof (wpbc_field_highlight)) && ('' === jQuery( '#' + id + '__days_number_' + weekday_num ).val()) ){
                        wpbc_field_highlight( '#' + id + '__days_number_' + weekday_num );
                    }
                }
            }
        }
        var condition_rule = condition_rule_arr.join( ',' );
        jQuery( '#' + id + '_textarea' ).val( condition_rule );
        wpbc_set_shortcode();
    }
    function wpbc_shortcode_config__select_day_weekday__reset(id){

        for ( var weekday_num = 0; weekday_num < 8; weekday_num++ ){
            jQuery( '#' + id + '__days_number_' + weekday_num ).val( '' );
            if ( jQuery( '#' + id + '__weekday_' + weekday_num ).is( ':checked' ) ){
                jQuery( '#' + id + '__weekday_' + weekday_num ).prop( 'checked', false );
            }
        }
        jQuery( '#' + id + '_textarea' ).val( '' );
        wpbc_set_shortcode();
    }


    /**
     * Condition:   {select-day condition="season" for="High season" value="7-14,20"}
     */
    function wpbc_shortcode_config__select_day_season__add(id){

        var season_filter_name = jQuery( '#' + id + '__season_filter_name option:selected' ).text().trim();
        // Escape quote symbols
        season_filter_name = season_filter_name.replace(/[\""]/g, '\\"');

        var days_number = jQuery( '#' + id + '__days_number' ).val().trim();
        // Remove all words except digits and , and -
        days_number = days_number.replace( /[^0-9,-]/g, '' );
        days_number = days_number.replace( /[,]{2,}/g, ',' );
        days_number = days_number.replace( /[-]{2,}/g, '-' );
        jQuery( '#' + id + '__days_number' ).val( days_number );

        if (
               ('' != days_number)
            && ('' != season_filter_name)
            && (0 != jQuery( '#' + id + '__season_filter_name' ).val())

        ){
            var exist_configuration = jQuery( '#' + id + '_textarea' ).val();

            exist_configuration = exist_configuration.replaceAll("},{", '}~~{')
            var condition_rule_arr = exist_configuration.split( '~~' );

            // Remove empty spaces from  array : '' | ""
            condition_rule_arr = condition_rule_arr.filter(function(n){return n; });

            condition_rule_arr.push( '{select-day condition="season" for="' + season_filter_name + '" value="' + days_number + '"}' );

            // Remove duplicates from  the array
            condition_rule_arr = condition_rule_arr.filter( function ( item, pos ){ return condition_rule_arr.indexOf( item ) === pos; } );
            var condition_rule = condition_rule_arr.join( ',' );
            jQuery( '#' + id + '_textarea' ).val( condition_rule );

            wpbc_set_shortcode();
        }

        // Red highlight fields,  if some required fields are empty
        if ( ('function' === typeof (wpbc_field_highlight)) && ('' === jQuery( '#' + id + '__days_number' ).val()) ){
            wpbc_field_highlight( '#' + id + '__days_number' );
        }
        if ( ('function' === typeof (wpbc_field_highlight)) && ('0' === jQuery( '#' + id + '__season_filter_name' ).val()) ){
            wpbc_field_highlight( '#' + id + '__season_filter_name' );
        }

    }
    function wpbc_shortcode_config__select_day_season__reset(id){
        jQuery( '#' + id + '__season_filter_name option:eq(0)' ).prop( 'selected', true );
        jQuery( '#' + id + '__days_number' ).val( '' );
        jQuery( '#' + id + '_textarea' ).val( '' );
        wpbc_set_shortcode();
    }


    /**
     * Condition:   {start-day condition="season" for="Low season" value="0,1,5"}
     */
    function wpbc_shortcode_config__start_day_season__add( id ){

        var season_filter_name = jQuery( '#' + id + '__season_filter_name option:selected' ).text().trim();
        // Escape quote symbols
        season_filter_name = season_filter_name.replace(/[\""]/g, '\\"');

        if (
               ('' != season_filter_name)
            && (0 != jQuery( '#' + id + '__season_filter_name' ).val())

        ){
            var activated_weekdays =[];
            for ( var weekday_num = 0; weekday_num < 8; weekday_num++ ){
                if ( jQuery( '#' + id + '__weekday_' + weekday_num ).is( ':checked' ) ){
                        activated_weekdays.push( weekday_num );
                }
            }
            activated_weekdays = activated_weekdays.join( ',' );

            if ( '' != activated_weekdays ){

                var exist_configuration = jQuery( '#' + id + '_textarea' ).val();

                exist_configuration = exist_configuration.replaceAll( "},{", '}~~{' )
                var condition_rule_arr = exist_configuration.split( '~~' );

                // Remove empty spaces from  array : '' | ""
                condition_rule_arr = condition_rule_arr.filter( function ( n ){
                    return n;
                } );

                condition_rule_arr.push( '{start-day condition="season" for="' + season_filter_name + '" value="' + activated_weekdays + '"}' );

                // Remove duplicates from  the array
                condition_rule_arr = condition_rule_arr.filter( function ( item, pos ){
                    return condition_rule_arr.indexOf( item ) === pos;
                } );
                var condition_rule = condition_rule_arr.join( ',' );
                jQuery( '#' + id + '_textarea' ).val( condition_rule );

                wpbc_set_shortcode();
            }
        }

        // Red highlight fields,  if some required fields are empty
        if ( ('function' === typeof (wpbc_field_highlight)) && ('0' === jQuery( '#' + id + '__season_filter_name' ).val()) ){
            wpbc_field_highlight( '#' + id + '__season_filter_name' );
        }
    }
    function wpbc_shortcode_config__start_day_season__reset(id){
        jQuery( '#' + id + '__season_filter_name option:eq(0)' ).prop( 'selected', true );
        for ( var weekday_num = 0; weekday_num < 8; weekday_num++ ){
            if ( jQuery( '#' + id + '__weekday_' + weekday_num ).is( ':checked' ) ){
                jQuery( '#' + id + '__weekday_' + weekday_num ).prop( 'checked', false );
            }
        }
        jQuery( '#' + id + '_textarea' ).val( '' );
        wpbc_set_shortcode();
    }


    /**
     * Condition:   {select-day condition="date" for="2023-10-01" value="20,25,30-35"}
     */
    function wpbc_shortcode_config__select_day_fordate__add(id){

        var start_date__fordate = jQuery( '#' + id + '__date' ).val().trim();
        // Remove all words except digits and , and -
        start_date__fordate = start_date__fordate.replace( /[^0-9-]/g, '' );

        var globalRegex = new RegExp( /^\d{4}-[01]{1}\d{1}-[0123]{1}\d{1}$/, 'g' );
        var is_valid_date = globalRegex.test( start_date__fordate );
        if ( !is_valid_date ){
            start_date__fordate = '';
        }
        jQuery( '#' + id + '__date' ).val( start_date__fordate );

        var days_number = jQuery( '#' + id + '__days_number' ).val().trim();
        // Remove all words except digits and , and -
        days_number = days_number.replace( /[^0-9,-]/g, '' );
        days_number = days_number.replace( /[,]{2,}/g, ',' );
        days_number = days_number.replace( /[-]{2,}/g, '-' );
        jQuery( '#' + id + '__days_number' ).val( days_number );

        if (
               ('' != days_number)
            && ('' != start_date__fordate)
            && (0 != jQuery( '#' + id + '__season_filter_name' ).val())

        ){
            var exist_configuration = jQuery( '#' + id + '_textarea' ).val();

            exist_configuration = exist_configuration.replaceAll("},{", '}~~{')
            var condition_rule_arr = exist_configuration.split( '~~' );

            // Remove empty spaces from  array : '' | ""
            condition_rule_arr = condition_rule_arr.filter(function(n){return n; });

            condition_rule_arr.push( '{select-day condition="date" for="' + start_date__fordate + '" value="' + days_number + '"}' );

            // Remove duplicates from  the array
            condition_rule_arr = condition_rule_arr.filter( function ( item, pos ){ return condition_rule_arr.indexOf( item ) === pos; } );
            var condition_rule = condition_rule_arr.join( ',' );
            jQuery( '#' + id + '_textarea' ).val( condition_rule );

                 wpbc_set_shortcode();
        } else

        // Red highlight fields,  if some required fields are empty
        if ( ('function' === typeof (wpbc_field_highlight)) && ('' === jQuery( '#' + id + '__date' ).val()) ){
            wpbc_field_highlight( '#' + id + '__date' );
        }
        if ( ('function' === typeof (wpbc_field_highlight)) && ('' === jQuery( '#' + id + '__days_number' ).val()) ){
            wpbc_field_highlight( '#' + id + '__days_number' );
        }
    }
    function wpbc_shortcode_config__select_day_fordate__reset(id){
        jQuery( '#' + id + '__date' ).val( '' );
        jQuery( '#' + id + '__days_number' ).val( '' );
        jQuery( '#' + id + '_textarea' ).val( '' );
        wpbc_set_shortcode();
    }


    
function wpbc_shortcode_config__update_elements_in_timeline(){

    var wpbc_is_matrix = false;

    if ( jQuery( '#bookingtimeline_wpbc_multiple_resources' ).length > 0 ) {

        var bookingtimeline_wpbc_multiple_resources_temp = jQuery( '#bookingtimeline_wpbc_multiple_resources' ).val();

        if ( ( bookingtimeline_wpbc_multiple_resources_temp != null ) && ( bookingtimeline_wpbc_multiple_resources_temp.length > 0 )  ){

            jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row']" ).prop( "disabled", false );
            jQuery( ".wpbc_sc_container__shortcode_bookingtimeline label.wpbc-form-radio" ).show();

            if (
                    ( bookingtimeline_wpbc_multiple_resources_temp.length > 1 )
                ||  ( (bookingtimeline_wpbc_multiple_resources_temp.length == 1) && (bookingtimeline_wpbc_multiple_resources_temp[ 0 ] == '0'))
            ){  // Matrix
                wpbc_is_matrix = true;
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='90']" ).prop( "disabled", true );
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='90']" ).parents('.wpbc-form-radio').hide();
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='365']" ).prop( "disabled", true );
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='365']" ).parents('.wpbc-form-radio').hide();
            } else {                                            // Single
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='1']" ).prop( "disabled", true );
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='1']" ).parents('.wpbc-form-radio').hide();
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='7']" ).prop( "disabled", true );
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='7']" ).parents('.wpbc-form-radio').hide();
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='60']" ).prop( "disabled", true );
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='60']" ).parents('.wpbc-form-radio').hide();
            }
           if ( jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row']:checked" ).is(':disabled') ) {
                jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row'][value='30']" ).prop( "checked", true );
           }
        }
    }

    var view_days_num_temp = 30;
    if ( jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row']:checked" ).length > 0 ){
        var view_days_num_temp = parseInt( jQuery( "input[name='bookingtimeline_wpbc_view_mode_timeline_months_num_in_row']:checked" ).val().trim() );
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // Hide or Show Scrolling Days and Months, depending on from type of view and number of booking resources
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    jQuery( "#wpbc_bookingtimeline_scroll_month,#wpbc_bookingtimeline_scroll_day" ).prop( "disabled", false );
    jQuery( ".wpbc_bookingtimeline_scroll_month,.wpbc_bookingtimeline_scroll_day" ).show();
    // Matrix //////////////////////////////////////////////
    if (
          ( wpbc_is_matrix ) && ( ( view_days_num_temp == 1 ) || ( view_days_num_temp == 7 ) ) // Day | Week view
        ) {
            jQuery( "#wpbc_bookingtimeline_scroll_month" ).prop( "disabled", true );                            // Scroll Month NOT working
            jQuery( '.wpbc_bookingtimeline_scroll_month' ).hide();
        }
    if (
          ( wpbc_is_matrix )&& ( ( view_days_num_temp == 30 ) || ( view_days_num_temp == 60 ) ) // Month view
        ) {
            jQuery( "#wpbc_bookingtimeline_scroll_day" ).prop( "disabled", true );                              // Scroll Days NOT working
            jQuery( '.wpbc_bookingtimeline_scroll_day' ).hide();
        }
    // Single //////////////////////////////////////////////
    if (
          ( ! wpbc_is_matrix ) && ( ( view_days_num_temp == 30 ) || ( view_days_num_temp == 90 ) )  // Month | 3 Months view (like week view)
        ) {
            jQuery( "#wpbc_bookingtimeline_scroll_month" ).prop( "disabled", true );                                        // Scroll Month NOT working
            jQuery( '.wpbc_bookingtimeline_scroll_month' ).hide();
        }
    if (
          ( ! wpbc_is_matrix )&& ( ( view_days_num_temp == 365 ) )                              // Year view
        ) {
            jQuery( "#wpbc_bookingtimeline_scroll_day" ).prop( "disabled", true );                                          // Scroll Days NOT working
            jQuery( '.wpbc_bookingtimeline_scroll_day' ).hide();
        }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    return [ wpbc_is_matrix, view_days_num_temp ];
}    

    
jQuery( document ).ready( function (){
    // -----------------------------------------------------------------------------------------------------
    // [booking ... ]

    var shortcode_arr = ['booking', 'bookingcalendar', 'bookingselect', 'bookingtimeline', 'bookingform', 'bookingsearch', 'bookingother', 'booking_import_ics' , 'booking_listing_ics'];

    for ( var shortcde_key in shortcode_arr ){

        var id = shortcode_arr[ shortcde_key ];

        // -------------------------------------------------------------------------------------------------------------
        // Hide by Size sections
        // -------------------------------------------------------------------------------------------------------------
        jQuery( '.' + id + '_wpbc_size_wpbc_sc_calendar_size' ).hide();

        // options :: Show / Hide SIZE calendar  section
        jQuery( '#' + id + '_wpbc_size_enabled' ).on( 'change', {'id': id}, function( event ){
            if ( jQuery( '#' + event.data.id + '_wpbc_size_enabled' ).is( ':checked' ) ){
                jQuery( '.' + event.data.id + '_wpbc_size_wpbc_sc_calendar_size' ).show();
            } else {
                jQuery( '.' + event.data.id + '_wpbc_size_wpbc_sc_calendar_size' ).hide();
            }
        } );

        // -------------------------------------------------------------------------------------------------------------
        // Update Shortcode on changing: Size
        // -------------------------------------------------------------------------------------------------------------
        jQuery(   '#' + id + '_wpbc_size_enabled'                             // Size On | Off
                +',#' + id + '_wpbc_size_months_num_in_row'                   // - Month Num in Row
                +',#' + id + '_wpbc_size_calendar_width'                      // - Width
                +',#' + id + '_wpbc_size_calendar_width_px_pr'                // - Width PS | %
                +',#' + id + '_wpbc_size_calendar_cell_height'                // - Cell Height

                +',#' + id + 'wpbc_select_day_weekday_textarea'               // Rule Weekday
                +',#' + id + 'wpbc_select_day_season_textarea'                // Rule Season
                +',#' + id + 'wpbc_start_day_season_textarea'                 // Rule Season - Start day
                +',#' + id + 'wpbc_select_day_fordate_textarea'               // Rule Date

                +',#' + id + '_wpbc_resource_id'                              // Resource ID
                +',#' + id + '_wpbc_custom_form'                              // Custom Form
                +',#' + id + '_wpbc_nummonths'                                // Num Months

                +',#' + id + '_wpbc_startmonth_active'                       // Start Month Enable
                +',#' + id + '_wpbc_startmonth_year'                         //  - Year
                +',#' + id + '_wpbc_startmonth_month'                        //  - Month

                +',#' + id + '_wpbc_aggregate'                               // Aggregate
                +',#' + id + '_wpbc_aggregate__bookings_only'                // aggregate option

                +',#' + id + '_wpbc_multiple_resources'                     // [bookingselect] - Multiple Resources
                +',#' + id + '_wpbc_selected_resource'                      // [bookingselect] - Selected Resource
                +',#' + id + '_wpbc_text_label'                             // [bookingselect] - Label
                +',#' + id + '_wpbc_first_option_title'                     // [bookingselect] - First  Option

                // TimeLine
                +",input[name='"+ id +"_wpbc_view_mode_timeline_months_num_in_row']"
                +',#' + id + '_wpbc_text_label_timeline'
                +',#' + id + '_wpbc_scroll_timeline_scroll_days'
                +',#' + id + '_wpbc_scroll_timeline_scroll_month'
                +',#' + id + '_wpbc_start_date_timeline_active'
                +',#' + id + '_wpbc_start_date_timeline_year'
                +',#' + id + '_wpbc_start_date_timeline_month'
                +',#' + id + '_wpbc_start_date_timeline_day'
                +',#' + id + '_wpbc_start_end_time_timeline_starttime'
                +',#' + id + '_wpbc_start_end_time_timeline_endtime'

                // Form Only
                +',#' + id + '_wpbc_booking_date_year'
                +',#' + id + '_wpbc_booking_date_month'
                +',#' + id + '_wpbc_booking_date_day'

                // [bookingsearch ...]
                +",input[name='"+ id +"_wpbc_search_form_results']"
                +',#' + id + '_wpbc_search_new_page_enabled'
                +',#' + id + '_wpbc_search_new_page_url'
                +',#' + id + '_wpbc_search_header'
                +',#' + id + '_wpbc_search_nothing_found'
                +',#' + id + '_wpbc_search_for_users'

                // [bookingother ... ]
                +",input[name='"+ id +"_wpbc_shortcode_type']"
                +',#' + id + '_wpbc_resource_show'

                //booking_import_ics , booking_listing_ics
                +',#' + id + '_wpbc_url'
                +',#' + id + '_from'
                +',#' + id + '_from_offset'
                +',#' + id + '_from_offset_type'
                +',#' + id + '_until'
                +',#' + id + '_until_offset'
                +',#' + id + '_until_offset_type'
                +',#' + id + '_conditions_import'
                +',#' + id + '_conditions_events'
                +',#' + id + '_conditions_max_num'
                +',#' + id + '_silence'
            ).on( 'change', {'id': id}, function(event){
                    //console.log( 'on change wpbc_set_shortcode', event.data.id );
                    wpbc_set_shortcode();
            });
    }
    // -----------------------------------------------------------------------------------------------------
    wpbc_set_shortcode();
});
