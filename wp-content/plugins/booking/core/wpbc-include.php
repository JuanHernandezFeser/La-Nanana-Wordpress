<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Files Loading
 * @category Bookings
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 29.09.2015
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

////////////////////////////////////////////////////////////////////////////////
//   L O A D   F I L E S
////////////////////////////////////////////////////////////////////////////////
require_once( WPBC_PLUGIN_DIR . '/core/wpbc-debug.php' );                       // Debug                                            = Package: WPBC =
require_once( WPBC_PLUGIN_DIR . '/core/wpbc-core.php' );                        // Core


require_once( WPBC_PLUGIN_DIR . '/core/any/class-css-js.php' );                 // Abstract. Loading CSS & JS files                 = Package: Any =
require_once( WPBC_PLUGIN_DIR . '/core/any/class-admin-settings-api.php' );     // Abstract. Settings API.        
require_once( WPBC_PLUGIN_DIR . '/core/any/class-admin-page-structure.php' );   // Abstract. Page Structure in Admin Panel    
require_once( WPBC_PLUGIN_DIR . '/core/any/class-admin-menu.php' );             // CLASS. Menus of plugin
require_once( WPBC_PLUGIN_DIR . '/core/any/admin-bs-ui.php' );                  // Functions. Toolbar BS UI Elements
if( is_admin() ) {
    require_once WPBC_PLUGIN_DIR . '/core/class/wpbc-class-dismiss.php';        // Class - Dismiss                 
    require_once WPBC_PLUGIN_DIR . '/core/class/wpbc-class-notices.php';        // Class - Showing different messages and alerts. Including some predefined static messages.    
    require_once WPBC_PLUGIN_DIR . '/core/class/wpbc-class-welcome.php';        // Class - Welcome Page - info  about  new version.
}

// Functions
require_once( WPBC_PLUGIN_DIR . '/core/wpbc_functions.php' );                   // Functions
require_once( WPBC_PLUGIN_DIR . '/core/wpbc_functions_dates.php' );             // Function Dates                       New in 9.8
require_once( WPBC_PLUGIN_DIR . '/core/form_parser.php' );                      // Parser for booking form              New in 9.8
require_once( WPBC_PLUGIN_DIR . '/core/custom_html_shortcodes.php' );		    // Integrate Custom  booking form HTML shortcodes
require_once( WPBC_PLUGIN_DIR . '/core/wpbc-dates.php' );                       // Dates
require_once( WPBC_PLUGIN_DIR . '/core/wpbc_welcome.php' );                     // Welcome Panel Functions

// New Engine       //FixIn: 9.8.0.4
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/wpbc_cache.php' );                     // Caching different requests to DB                                 //FixIn: 9.8.0.4
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/booking_date_class.php' );             // Functions for booking dates
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/dates_times_support.php' );            // Functions for booking DATES & TIME
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/resource_support.php' );               // Functions for resources support
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/capacity.php' );                       // Get Dates from DB  - Capacity, Availability and more ...
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/get_times_fields.php' );               // Get Times Fields options from  Booking Form
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/aggregate.php' );                      // Aggregate functions  for 'aggregate' parameter in shortcode
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/where_to_save.php' );                  // Check  where to  save booking
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/create_booking.php' );                 // Functions to create new bookings
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/confirmation.php' );                   // Confirmation section - get data
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/confirmation_page.php' );              // Confirmation Page
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/calendar_load.php' );                  // Scripts to  load calendar
require_once( WPBC_PLUGIN_DIR . '/includes/_capacity/captcha_simple_text.php' );            // Simple text captcha checking

require_once( WPBC_PLUGIN_DIR . '/core/wpbc-translation.php' );                 // Translation,  must be loaded after '/core/wpbc-core.php',  because there defined  add_bk_filter(), etc...
//FixIn: 8.9.4.12
if ( file_exists( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations.php' ) ){

	require_once( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations.php' );    // All Translation Terms

	//FixIn: 8.7.3.6
	if ( file_exists( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations1.php' ) ){
		require_once( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations1.php' );
	}
	if ( file_exists( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations2.php' ) ){
		require_once( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations2.php' );
	}
	if ( file_exists( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations3.php' ) ){
		require_once( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations3.php' );
	}
	if ( file_exists( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations4.php' ) ){
		require_once( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations4.php' );
	}
	if ( file_exists( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations5.php' ) ){
		require_once( WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations5.php' );
	}
}

require_once( WPBC_PLUGIN_DIR . '/includes/publish/wpbc-create-pages.php' );                // Create pages for different purposes                  //FixIn: 9.6.2.10
require_once( WPBC_PLUGIN_DIR . '/includes/publish/wpbc-publish-shortcode.php' );           // Publish  Booking Calendar shortcodes into the Pages   //FixIn: 9.8.15.5
require_once( WPBC_PLUGIN_DIR . '/core/wpbc-emails.php' );                      // Emails
// JS & CSS
require_once( WPBC_PLUGIN_DIR . '/core/wpbc-css.php' );                         // Load CSS
require_once( WPBC_PLUGIN_DIR . '/core/wpbc-js.php' );                          // Load JavaScript and define JS Varibales
// Admin UI
require_once( WPBC_PLUGIN_DIR . '/core/admin/wpbc-toolbars.php' );              // Toolbar - BS UI Elements
require_once( WPBC_PLUGIN_DIR . '/core/admin/wpbc-sql.php' );                   // Data Engine for Booking Listing / Calendar Overview pages
//FixIn: 9.6.3.5    //FixIn: 8.6.1.13
require_once( WPBC_PLUGIN_DIR . '/core/timeline/flex-timeline.php' );           // New. Flex. Timeline

require_once( WPBC_PLUGIN_DIR . '/core/admin/wpbc-dashboard.php' );             // Dashboard Widget

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Admin Pages
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FixIn: 9.2.1      //FixIn: 9.6.3.5

require_once( WPBC_PLUGIN_DIR . '/includes/_request/wpbc_request.php' );                                            //FixIn: 9.3.1.2        // Class for sanitizing $_REQUEST parameters and saving or getting it from  DB
require_once( WPBC_PLUGIN_DIR . '/includes/_booking_hash/booking_hash.php' );                                       //FixIn: 9.2.3.3

require_once( WPBC_PLUGIN_DIR . '/includes/_news/wpbc_news.php' );

// Booking Listing
require_once( WPBC_PLUGIN_DIR . '/includes/_toolbar_ui/toolbar_ui.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/_listing_css_js/listing_ui.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/_pagination/pagination.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/print/bookings_print.php' );

require_once( WPBC_PLUGIN_DIR . '/includes/page-bookings/bookings__sql.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/page-bookings/bookings__actions.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/page-bookings/bookings__listing.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/page-bookings/bookings__page.php' );

// Booking > Availability page
require_once( WPBC_PLUGIN_DIR . '/includes/page-availability/availability__activation.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/page-availability/availability__toolbar_ui.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/page-availability/availability__class.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/page-availability/availability__resource.php' );
require_once( WPBC_PLUGIN_DIR . '/includes/page-availability/availability__page.php' );

// Booking > Customize page                                             //FixIn: 9.8.0.1
if ( WPBC_customize_plugin ) {      //FixIn: 9.8.0.2
	require_once( WPBC_PLUGIN_DIR . '/includes/page-customize/customize__templates.php' );
	require_once( WPBC_PLUGIN_DIR . '/includes/page-customize/customize__ajax_request.php' );
	require_once( WPBC_PLUGIN_DIR . '/includes/page-customize/customize__page.php' );
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FixIn: 9.6.3.5
require_once( WPBC_PLUGIN_DIR . '/core/admin/page-timeline.php' );              // Timeline
require_once( WPBC_PLUGIN_DIR . '/core/admin/page-new.php' );                   // Add New Booking page

require_once( WPBC_PLUGIN_DIR . '/core/admin/wpbc-settings-functions.php' );    // Support functions for Booking > Settings General page
require_once( WPBC_PLUGIN_DIR . '/core/admin/page-settings.php' );              // Settings page 
    require_once( WPBC_PLUGIN_DIR . '/core/admin/api-settings.php' );           // Settings API

require_once( WPBC_PLUGIN_DIR . '/core/admin/wpbc-gutenberg.php' );              // Settings page

////////////////////////////////////////////////////////////////////////////////

if ( file_exists( WPBC_PLUGIN_DIR.'/inc/_ps/personal.php' ) ){   
    require_once WPBC_PLUGIN_DIR . '/inc/_ps/personal.php';  
} else {

	require_once( WPBC_PLUGIN_DIR . '/includes/page-resource-free/page-resource-free.php' );              // Resource page for Free version

	require_once( WPBC_PLUGIN_DIR . '/core/admin/page-up.php' );                // Up                                   //FixIn: 8.0.1.6
	require_once( WPBC_PLUGIN_DIR . '/core/admin/page-form-free.php' );         // Fields

    require_once( WPBC_PLUGIN_DIR . '/core/admin/page-email-new-admin.php' );   // Email - New admin.
    require_once( WPBC_PLUGIN_DIR . '/core/admin/page-email-new-visitor.php' ); // Email - New visitor
    require_once( WPBC_PLUGIN_DIR . '/core/admin/page-email-deny.php' );        // Email - Deny - set  pending
    require_once( WPBC_PLUGIN_DIR . '/core/admin/page-email-approved.php' );    // Email - Approved
    require_once( WPBC_PLUGIN_DIR . '/core/admin/page-email-trash.php' );       // Email - Trash
    require_once( WPBC_PLUGIN_DIR . '/core/admin/page-email-deleted.php' );     // Email - Deleted / completely erase

	require_once( WPBC_PLUGIN_DIR . '/core/admin/page-ics-general.php' );		// General ICS Help Settings page		    //FixIn: 8.1.1.10
	require_once( WPBC_PLUGIN_DIR . '/core/admin/page-ics-import.php' );        // Import ICS Help Settings page			//FixIn: 8.0
	require_once( WPBC_PLUGIN_DIR . '/core/admin/page-ics-export.php' );        // Export ICS Feeds Settings page			//FixIn: 8.0
    require_once( WPBC_PLUGIN_DIR . '/core/admin/page-import-gcal.php' );       // Import from  Google Calendar Settings page 
}

require_once( WPBC_PLUGIN_DIR . '/includes/_feedback/feedback.php');                                                    //FixIn: 9.2.3.6

// Old Working        
require_once WPBC_PLUGIN_DIR . '/core/lib/wpdev-booking-widget.php';            // W i d g e t s
require_once WPBC_PLUGIN_DIR . '/js/captcha/captcha.php';                       // C A P T C H A

require_once WPBC_PLUGIN_DIR . '/core/lib/wpbc-calendar-legend.php';            // Calendar Legend                      //FixIn: 9.4.3.6

require_once WPBC_PLUGIN_DIR . '/core/lib/wpdev-booking-class.php';             // C L A S S    B o o k i n g
require_once WPBC_PLUGIN_DIR . '/core/lib/wpbc-booking-new.php';                // N e w    
require_once WPBC_PLUGIN_DIR . '/core/lib/wpbc-cron.php';                       // CRON  @since: 5.2.0

if( is_admin() ) {
	//FixIn: 9.9.0.15
	require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/shortcode_tpl_js_loader.php';                            // Templates JS Loader
    require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking.php';                                     // Booking  -   Shortcode Config Content
    require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking_calendar.php';
	require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking_select.php';
	require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking_timeline.php';
	require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking_form.php';
	require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking_search.php';
	require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking_other.php';
	require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking_import.php';
	require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/sh_tpl_booking_listing.php';
    require_once WPBC_PLUGIN_DIR . '/includes/_shortcode_popup/tiny-button-popup.php';                                  // PopUp for shortcodes     and     Buttons in Edit toolbar
}

require_once WPBC_PLUGIN_DIR . '/core/sync/wpbc-gcal-class.php';                //DONE: in 7.0     // Google Calendar Feeds Import @since: 5.2.0  - v.3.0 API support @since: 5.4.0
require_once WPBC_PLUGIN_DIR . '/core/sync/wpbc-gcal.php';                      //DONE: in 7.0     // Sync Google Calendar Events with  WPBC @since: 5.2.0  - v.3.0 API support @since: 5.4.0

require_once( WPBC_PLUGIN_DIR . '/core/any/activation.php' );
require_once( WPBC_PLUGIN_DIR . '/core/wpbc-activation.php' );

require_once( WPBC_PLUGIN_DIR . '/core/wpbc-dev-api.php' );					    // API for Booking Calendar integrations	//FixIn: 8.0

make_bk_action( 'wpbc_loaded_php_files' );