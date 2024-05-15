/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage JS Variables
 * @category Scripts
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.05.20
 */

////////////////////////////////////////////////////////////////////////////////
// Eval specific variable value (integer, bool, arrays, etc...)
////////////////////////////////////////////////////////////////////////////////

function wpbc_define_var( wpbc_global_var ) {
    if (wpbc_global_var === undefined) { return null; }
    else { return JSON.parse(wpbc_global_var); }                          //FixIn:6.1       //FixIn: 8.7.11.12
}

////////////////////////////////////////////////////////////////////////////////
// Define global Booking Calendar Varibales based on Localization
////////////////////////////////////////////////////////////////////////////////
var wpbc_ajaxurl                        = wpbc_global1.wpbc_ajaxurl; 
var wpdev_bk_plugin_url                 = wpbc_global1.wpdev_bk_plugin_url;
var wpbc_today                          = wpbc_define_var( wpbc_global1.wpbc_today );
var visible_booking_id_on_page          = wpbc_define_var( wpbc_global1.visible_booking_id_on_page );
var booking_max_monthes_in_calendar     = wpbc_global1.booking_max_monthes_in_calendar;
var user_unavilable_days                = wpbc_define_var( wpbc_global1.user_unavilable_days );
var wpdev_bk_edit_id_hash               = wpbc_global1.wpdev_bk_edit_id_hash;
var wpdev_bk_plugin_filename            = wpbc_global1.wpdev_bk_plugin_filename;
var bk_days_selection_mode              = wpbc_global1.bk_days_selection_mode; 
var wpdev_bk_personal                   = parseInt( wpbc_global1.wpdev_bk_personal );
var block_some_dates_from_today         = parseInt( wpbc_global1.block_some_dates_from_today );
var message_verif_requred               = wpbc_global1.message_verif_requred;
var message_verif_requred_for_check_box = wpbc_global1.message_verif_requred_for_check_box;
var message_verif_requred_for_radio_box = wpbc_global1.message_verif_requred_for_radio_box;
var message_verif_emeil                 = wpbc_global1.message_verif_emeil;
var message_verif_same_emeil            = wpbc_global1.message_verif_same_emeil;
var message_verif_selectdts             = wpbc_global1.message_verif_selectdts;
var new_booking_title                   = wpbc_global1.new_booking_title;

var type_of_thank_you_message           = wpbc_global1.type_of_thank_you_message;
var thank_you_page_URL                  = wpbc_global1.thank_you_page_URL;
var is_am_pm_inside_time                = ( wpbc_global1.is_am_pm_inside_time == "true" );
var is_booking_used_check_in_out_time   = ( wpbc_global1.is_booking_used_check_in_out_time == "true" );
var wpbc_active_locale                  = wpbc_global1.wpbc_active_locale;
var wpbc_message_processing             = wpbc_global1.wpbc_message_processing;
var wpbc_message_deleting               = wpbc_global1.wpbc_message_deleting;
var wpbc_message_updating               = wpbc_global1.wpbc_message_updating;
var wpbc_message_saving                 = wpbc_global1.wpbc_message_saving;

//FixIn: 8.2.1.99
var message_checkinouttime_error    = wpbc_global1.message_checkinouttime_error;                                        //FixIn:6.1.1.1
var message_starttime_error         = wpbc_global1.message_starttime_error;
var message_endtime_error           = wpbc_global1.message_endtime_error;
var message_rangetime_error         = wpbc_global1.message_rangetime_error;
var message_durationtime_error      = wpbc_global1.message_durationtime_error;
var bk_highlight_timeslot_word      = wpbc_global1.bk_highlight_timeslot_word;

if (typeof wpbc_global2 !== 'undefined') {
    var message_time_error              = wpbc_global2.message_time_error;
}
if (typeof wpbc_global3 !== 'undefined') {    
    var bk_1click_mode_days_num         = parseInt( wpbc_global3.bk_1click_mode_days_num ); 
    var bk_1click_mode_days_start       = wpbc_define_var( wpbc_global3.bk_1click_mode_days_start ); 
    var bk_2clicks_mode_days_min        = parseInt( wpbc_global3.bk_2clicks_mode_days_min ); 
    var bk_2clicks_mode_days_max        = parseInt( wpbc_global3.bk_2clicks_mode_days_max ); 
    var bk_2clicks_mode_days_specific   = wpbc_define_var( wpbc_global3.bk_2clicks_mode_days_specific ); 
    var bk_2clicks_mode_days_start      = wpbc_define_var( wpbc_global3.bk_2clicks_mode_days_start );
    // bk_highlight_timeslot_word          = wpbc_global3.bk_highlight_timeslot_word;                                   //FixIn: 9.4.3.1 //FixIn: 8.2.1.99
    var is_booking_recurrent_time       = ( wpbc_global3.is_booking_recurrent_time  == "true" );
        is_booking_used_check_in_out_time = ( wpbc_global3.is_booking_used_check_in_out_time == "true" );

}
if (typeof wpbc_global4 !== 'undefined') {
    var wpbc_available_days_num_from_today = parseInt( wpbc_global4.wpbc_available_days_num_from_today );
}
if (typeof wpbc_global5 !== 'undefined') {

}