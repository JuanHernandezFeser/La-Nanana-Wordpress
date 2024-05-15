<?php /**
 * @version 1.0
 * @package Booking Calendar 
 * @category JavaScript files and varibales
 * @author wpdevelop
 *
 * @web-site https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com 
 * 
 * @modified 19.10.2015
 */

class WPBC_JS extends WPBC_JS_CSS {
    
    public function define() {
        
        $this->setType('js');
        
        /*
        $this->add( array(
                            'handle' => 'wpbc-datepick',
                            'src' => wpbc_plugin_url( '/js/datepick/jquery.datepick.js'), 
                            'deps' => array( 'wpbc-global-vars' ),
                            'version' => '1.1',
                            'where_to_load' => array( 'admin', 'client' ),                //Usage: array( 'admin', 'client' )
                            'condition' => false    
                  ) );        
        */
    }

    /**
	 * Enqueue Files and Varibales.
     *  Useful in case, if we use get_options and current user functions...
     * 
     * @param type $where_to_load
     */
    public function enqueue( $where_to_load ) {

		wpbc_remove_js_conflicts();                                                                                     //FixIn: 9.5.4.2

        wpbc_js_load_vars(  $where_to_load );
        
        // Define JavaScript varibales in all other files
        do_action( 'wpbc_define_js_vars', $where_to_load );                         

        wpbc_js_load_libs(  $where_to_load );
        wpbc_js_load_files( $where_to_load );

        if ( wpbc_is_new_booking_page() )   
            $where_to_load = 'both';

        // Load JavaScript files in all other versions
        do_action( 'wpbc_enqueue_js_files', $where_to_load );

		/*
		 * Remove `async` and `defer`  ( check more here https://javascript.info/script-async-defer )
		 * for scripts registered or enqueued, that required for correct  working of plugin,  like
		 * jquery and all Booking Calendar scripts
		 * because inside content of the page can  be something like  jQuery(document).ready( function(){ ...} which  will
		 * generate         Uncaught ReferenceError: jQuery is not defined
		 */
		add_filter( 'script_loader_tag', array( $this, 'filter_script_loader_tag' ), 9000000000 , 3 );
    }


	/**
	 * Remove `async` and `defer`  ( check more here https://javascript.info/script-async-defer )
	 * for scripts registered or enqueued, that required for correct  working of plugin,  like
	 * jquery and all Booking Calendar scripts
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 *
	 * @return string Script HTML string.
	 *
	 */
	public function filter_script_loader_tag( $tag, $handle, $src ) {

		$script_handles_prevent_defer = array(
			  'jquery-core'         // exact value
			, 'jquery-migrate'
			//, 'wpbc-'             //starting from  'wpbc-'  it's not the exact value
			//, 'wpdevelop-'
		);


		// Remove defer and async attribute from  the src.
		if (
			   ( 'jquery-core' === $handle )
			|| ( 'jquery-migrate' === $handle )
			|| ( false !== strpos( $handle, 'wpbc-' ) )                 // Booking Calendar scripts
			|| ( false !== strpos( $handle, 'wpdevelop-' ) )
			|| ( false !== strpos( $handle, 'wpbm-' ) )
		) {

			foreach ( array( 'async', 'defer' ) as $attr ) {

				if ( preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
					$tag = str_replace($attr, '', $tag);
					$tag = str_replace('=""', '', $tag);
					$tag = str_replace("=''", '', $tag);

					/*
					 * Test  here https://regex101.com/
					 *
					 * Expression:   \s+defer(\s*=\s*["']defer["'])?\s?
					 * Test  string: <script type='text/javascript' defer = 'defer'  defer="defer" src='http://beta/wp-content/plugins/booking-manager/js/wpbm_vars.js?ver=1.1' id='wpbm-global-vars-js'></script>
					 *
					 */
					$pattern = ":\s+{$attr}(\s*=\s*[\"']{$attr}[\"'])?\s?:mi";
					$replacement = ' ';
					$tag = preg_replace($pattern, $replacement, $tag);
				}
			}
		}

		return $tag;
	}


    /**
	 * Deregister  some conflict  scripts from  other plugins.
     * 
     * @param type $where_to_load
     */
    public function remove_conflicts( $where_to_load ) {
        
        if ( wpbc_is_bookings_page() ) {
            if (function_exists('wp_dequeue_script')) {
                //wp_dequeue_script( 'jquery.cookie' );
                //wp_dequeue_script( 'jquery-interdependencies' );
                wp_dequeue_script( 'chosen' );
                wp_dequeue_script( 'cs-framework' );
                wp_dequeue_script( 'cgmp-jquery-tools-tooltip' );                               // Remove this script jquery.tools.tooltip.min.js, which is load by the "Comprehensive Google Map Plugin"
	            wp_dequeue_script( 'bootstrap-script' );
            }
        }
    }
}


/** Define JavaScript Varibales */
function wpbc_js_load_vars( $where_to_load ) {
    
    ////////////////////////////////////////////////////////////////////////////
    // JavaScripts Variables               
    ////////////////////////////////////////////////////////////////////////////

    wp_enqueue_script( 'wpbc-global-vars', wpbc_plugin_url( '/js/wpbc_vars.js' ), array( 'jquery' ), WP_BK_VERSION_NUM );        // Blank JS File


	//FixIn: 9.4.3.1
	$booking_highlight_timeslot_word = get_bk_option( 'booking_highlight_timeslot_word' );
	$booking_highlight_timeslot_word = apply_bk_filter( 'wpdev_check_for_active_language', $booking_highlight_timeslot_word );
	if ( empty( $booking_highlight_timeslot_word ) ) {
		$booking_highlight_timeslot_word = '';
	}
	if ( 'On' == get_bk_option( 'booking_disable_timeslots_in_tooltip' ) ) {                                            //FixIn: 9.5.0.2.2
		$booking_highlight_timeslot_word = 'WPBC_DISABLE_TIMES_TOOLTIP';
	}

	//FixIn: 9.9.0.17
	$today_local = wpbc_datetime_localized__use_wp_timezone( date( 'Y-m-d H:i:s', strtotime( 'now' ) ), 'Y-m-d-H-i' );
	$today_local_arr = explode( '-', $today_local );

    wp_localize_script( 'wpbc-global-vars'
                      , 'wpbc_global1', array(
          'wpbc_ajaxurl'                        => admin_url( 'admin-ajax.php' )
        , 'wpdev_bk_plugin_url'                 => plugins_url( '' , WPBC_FILE )                                                     
        , 'wpbc_today'       => '['     . intval( $today_local_arr[0] )            //FixIn: 9.9.0.17
                                        .','. intval( $today_local_arr[1] )
                                        .','. intval( $today_local_arr[2] )
                                        .','. intval( $today_local_arr[3] )
                                        .','. intval( $today_local_arr[4] )
                                    .']'
        , 'visible_booking_id_on_page'          => '[]'
        , 'booking_max_monthes_in_calendar'     => get_bk_option( 'booking_max_monthes_in_calendar')
        , 'user_unavilable_days'                => '['. ( ( get_bk_option( 'booking_unavailable_day0') == 'On' ) ? '0,' : '' )
                                                    . ( ( get_bk_option( 'booking_unavailable_day1') == 'On' ) ? '1,' : '' )
                                                    . ( ( get_bk_option( 'booking_unavailable_day2') == 'On' ) ? '2,' : '' )
                                                    . ( ( get_bk_option( 'booking_unavailable_day3') == 'On' ) ? '3,' : '' )
                                                    . ( ( get_bk_option( 'booking_unavailable_day4') == 'On' ) ? '4,' : '' )
                                                    . ( ( get_bk_option( 'booking_unavailable_day5') == 'On' ) ? '5,' : '' )
                                                    . ( ( get_bk_option( 'booking_unavailable_day6') == 'On' ) ? '6,' : '' )
                                                    .'999]' // 999 - blank day, which will not impact  to the checking of the week days. Required for correct creation of this array.
        , 'wpdev_bk_edit_id_hash'               => ( ( isset( $_GET['booking_hash'] ) ) ? $_GET['booking_hash'] : '' )
        , 'wpdev_bk_plugin_filename'            => WPBC_PLUGIN_FILENAME 
        , 'bk_days_selection_mode'              => ( ( get_bk_option('booking_type_of_day_selections') == 'range' ) ? get_bk_option('booking_range_selection_type') : get_bk_option( 'booking_type_of_day_selections') )     /* {'single', 'multiple', 'fixed', 'dynamic'} */
        , 'wpdev_bk_personal'                   => ( ( class_exists('wpdev_bk_personal') ) ? '1' : '0' )
        , 'block_some_dates_from_today'         => get_bk_option('booking_unavailable_days_num_from_today') 
        , 'message_verif_requred'               => esc_js(__('This field is required' ,'booking'))
        , 'message_verif_requred_for_check_box' => esc_js(__('This checkbox must be checked' ,'booking'))
        , 'message_verif_requred_for_radio_box' => esc_js(__('At least one option must be selected' ,'booking'))
        , 'message_verif_emeil'                 => esc_js(__('Incorrect email field' ,'booking'))
        , 'message_verif_same_emeil'            => esc_js(__('Your emails do not match' ,'booking'))          // Email Addresses Do Not Match
        , 'message_verif_selectdts'             =>  esc_js(__('Please, select booking date(s) at Calendar.' ,'booking'))
        , 'new_booking_title'                   => stripslashes( esc_js( apply_bk_filter('wpdev_check_for_active_language', get_bk_option( 'booking_title_after_reservation' ) ) ) )    //FixIn: 9.1.2.2   //TODO: delete it  2023-10-04 12:19
        , 'type_of_thank_you_message'           => esc_js( get_bk_option( 'booking_type_of_thank_you_message' ) )        
        , 'thank_you_page_URL'                  => wpbc_make_link_absolute( apply_bk_filter('wpdev_check_for_active_language', get_bk_option( 'booking_thank_you_page_URL' ) ) )        
        , 'is_am_pm_inside_time'                => (
        	                                            ( (strpos(get_bk_option('booking_time_format'), 'a')!== false) || (strpos(get_bk_option('booking_time_format'), 'A')!== false)
	                                                 || (     ( (strpos(get_option('time_format'), 'a')!== false) || (strpos(get_option('time_format'), 'A')!== false) )
	                                                 	   && ( ! class_exists( 'wpdev_bk_biz_s' ) )                    //FixIn: 8.7.3.11
	                                                      )
                                                    ) ?  'true': 'false' )                                              //FixIn:  TimeFree 2    -  in Booking Calendar Free version  show by  default times hints in AM/PM format
        , 'is_booking_used_check_in_out_time'   => 'false'
        , 'wpbc_active_locale'                  => wpbc_get_maybe_reloaded_booking_locale()
        , 'wpbc_message_processing'             => esc_js( __('Processing' ,'booking') )
        , 'wpbc_message_deleting'               => esc_js( __('Deleting' ,'booking') )
        , 'wpbc_message_updating'               => esc_js( __('Updating' ,'booking') )
        , 'wpbc_message_saving'                 => esc_js( __('Saving' ,'booking') )
	    //FixIn: 8.2.1.99
		, 'message_checkinouttime_error'        => esc_js(__('Error! Please reset your check-in/check-out dates above.' ,'booking') )  //FixIn:6.1.1.1
		, 'message_starttime_error'             => esc_js(__('Start Time is invalid. The date or time may be booked, or already in the past! Please choose another date or time.' ,'booking') )
		, 'message_endtime_error'               => esc_js(__('End Time is invalid. The date or time may be booked, or already in the past. The End Time may also be earlier that the start time, if only 1 day was selected! Please choose another date or time.' ,'booking') )
		, 'message_rangetime_error'             => esc_js(__('The time(s) may be booked, or already in the past!' ,'booking') )
		, 'message_durationtime_error'          => esc_js(__('The time(s) may be booked, or already in the past!' ,'booking') )
		// , 'bk_highlight_timeslot_word'          => esc_js( __( 'Times:', 'booking' ) )
		, 'bk_highlight_timeslot_word' => esc_js( $booking_highlight_timeslot_word )								    //FixIn: 9.4.3.1    //FixIn: 8.2.1.99
    ));
        
}


/** Default JavaScripts Libraries */
function wpbc_js_load_libs( $where_to_load ) {

    wp_enqueue_script( 'jquery' );                                                  // jQuery

    // Default Admin Libs 
	if ( 'admin' == $where_to_load ) {
		if ( wpbc_is_settings_page() ) {
			wp_enqueue_style( 'thickbox' );                                        // CSS Thickbox
			wp_enqueue_script( 'thickbox' );                                       // JS Thickbox
			wp_enqueue_media();
		}
		wp_enqueue_style(  'wp-color-picker' );                                    // Color Picker
        wp_enqueue_script( 'wp-color-picker' ); 
        wp_enqueue_script( 'jquery-ui-sortable' );                                 // UI Sortable
    }

	// WP Util,  that  support wp.template,  based on underscore _.template system
    wp_enqueue_script( 'wp-util' );                                                                                     //FixIn: TimeFreeGenerator      //FixIn: 9.4.4.11
}


/** Load JavaScript Files */
function wpbc_js_load_files( $where_to_load ) {

    /**
     * Popover functionality.
     * Popover based on Tippy.js ( https://atomiks.github.io/tippyjs/v6/all-props/#allowhtml ), which  is based on  popper.js ( https://popper.js.org/docs/v2/tutorial/ )
     * Install Alt + F12 run
     * npm i @popperjs/core
     * npm i tippy.js
     * Copy ..\node_modules\@popperjs\core\dist\umd     to  ..\wp-content/plugins/booking/assets/libs/popper
     * Copy ..\node_modules\tippy.js\{dist && themes}   to  ..\wp-content/plugins/booking/assets/libs/tippy.js
     * In popper.js  edit global.Popper  to global.wpbc_Popper
     * In tippy-bundle.umd.js  edit global.Popper  to global.wpbc_Popper   and      global.tippy   to  global.wpbc_tippy
     */
	wp_enqueue_script( 'wpbc-popper', wpbc_plugin_url( '/assets/libs/popper/popper.js' ),                   array( 'wpbc-global-vars' ),    WP_BK_VERSION_NUM );            //FixIn: 9.0.1.1
	wp_enqueue_script( 'wpbc-tipcy',  wpbc_plugin_url( '/assets/libs/tippy.js/dist/tippy-bundle.umd.js' ),  array( 'wpbc-popper' )  ,       WP_BK_VERSION_NUM );            //FixIn: 9.8.1


	if ( $where_to_load != 'client' ) {
		wp_enqueue_script( 'wpbc-modal', wpbc_plugin_url( '/assets/libs/ui/_out/dropdown_modal.js' ),       array( 'jquery' ), WP_BK_VERSION_NUM );                         //FixIn: 9.8.1
	}

    // Datepicker    
    wp_enqueue_script( 'wpbc-datepick', wpbc_plugin_url( '/js/datepick/jquery.datepick.wpbc.9.0.js'),       array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM );               //FixIn: 9.8.1
    // Localization
    $calendar_localization_url = wpbc_get_calendar_localization_url();
	if ( ! empty( $calendar_localization_url ) ) {
		wp_enqueue_script( 'wpbc-datepick-localize', $calendar_localization_url, array( 'wpbc-datepick' ), WP_BK_VERSION_NUM );                                             //FixIn: 9.8.1
	}


    if (  ( $where_to_load == 'client' ) || ( wpbc_is_new_booking_page()  )   ) {
		wp_enqueue_script( 'wpbc_all',          wpbc_plugin_url( '/_dist/all/_out/wpbc_all.js' ),                    array( 'wpbc-datepick' ), WP_BK_VERSION_NUM );          //FixIn: 9.8.6.1
	//	wp_enqueue_script( 'wpbc_balancer',     wpbc_plugin_url( '/includes/_load_balancer/_out/wpbc_balancer.js' ), array( 'wpbc_calendar' ), WP_BK_VERSION_NUM );             //FixIn: 9.8.3.1
	//	wp_enqueue_script( 'wpbc_calendar',     wpbc_plugin_url( '/includes/_wpbc_calendar/_out/wpbc_calendar.js' ), array( 'wpbc-datepick' ), WP_BK_VERSION_NUM );             // FixIn: 9.8.0.3
	    wp_enqueue_script( 'wpbc-main-client',  wpbc_plugin_url( '/js/client.js' ),                                  array( 'wpbc-datepick' ), WP_BK_VERSION_NUM );             // Client
	    wp_enqueue_script( 'wpbc_capacity',     wpbc_plugin_url( '/includes/_capacity/_out/create_booking.js' ),           array( 'wpbc-main-client' ), WP_BK_VERSION_NUM );    // Add new bookings   //FixIn: 9.8.0.3
	    wp_enqueue_script( 'wpbc-times',        wpbc_plugin_url( '/js/wpbc_times.js' ),                              array( 'wpbc-main-client' ), WP_BK_VERSION_NUM );          // FixIn: TimeFree 2 //UnComment it for Booking Calendar Free version
	    if ( 'On' === get_bk_option( 'booking_timeslot_picker' ) ) {
			wp_enqueue_script( 'wpbc-time-selector',    wpbc_plugin_url( '/js/wpbc_time-selector.js'),               array( 'wpbc-times' ),     WP_BK_VERSION_NUM);             //FixIn: 8.7.11.10
	    }
    }

    if ( $where_to_load == 'admin' ) {
		wp_enqueue_script( 'wpbc-js-print',      wpbc_plugin_url( '/assets/libs/wpbc_js_print/wpbc_js_print.js' ),  array( 'jquery' )  ,         WP_BK_VERSION_NUM );           //FixIn: 9.8.1            //FixIn: 9.2.1.6   //FixIn: 9.1.2.13
        wp_enqueue_script( 'wpbc-admin-main',    wpbc_plugin_url( '/js/admin.js'),                                  array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM );           // Admin
        wp_enqueue_script( 'wpbc-admin-support', wpbc_plugin_url( '/core/any/js/admin-support.js'),                 array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM );
        wp_enqueue_script( 'wpbc-chosen',        wpbc_plugin_url( '/assets/libs/chosen/chosen.jquery.min.js'),      array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM );           // Chosen Library
    }
}


/**
 * Remove JS libraries that conflict with  Booking Calendar at the pages with booking forms.
 *
 * You can disable loading of all Booking Calendar  JavaScript  files at  the Booking > Settings General page in "Advanced" section.
 * To do so, you can expand the "Show advanced settings of JavaScript loading" option and select the "Load JS and CSS files only on specific pages" checkbox.
 * You can then specify a list of pages in a textarea where you want the Booking Calendar to appear.
 *
 * @return void
 */
function wpbc_remove_js_conflicts() {
	//if ( wp_script_is( 'script-ID', 'registered' ) ) { }
	wp_deregister_script( 'mphb-kbwood-datepick' );
}

////////////////////////////////////////////////////////////////////////////////
//  Support JavaScript functions
////////////////////////////////////////////////////////////////////////////////

/**
	 * Get URL Datepicker Localization JS File
 * 
 * @return string - URL to  calendar skin
 */
function wpbc_get_calendar_localization_url() {
    // Datepicker Localization - translation for calendar.                      Example:    $locale = 'fr_FR';   
    $locale = wpbc_get_maybe_reloaded_booking_locale();
    
    $calendar_localization_url = false;
    
    if ( ! empty( $locale ) ) {

        $locale_lang    = strtolower( substr( $locale, 0, 2 ) );                // 7.0.1.51
        $locale_country = strtolower( substr( $locale, 3 ) );

        if (   ( $locale_lang !== 'en') && ( wpbc_is_file_exist( '/js/datepick/jquery.datepick-' . $locale_lang . '.js' ) )   ) { 
            
                $calendar_localization_url = wpbc_plugin_url( '/js/datepick/jquery.datepick-'. $locale_lang . '.js' );

        } else if (   ( ! in_array( $locale, array( 'en_US', 'en_CA', 'en_GB', 'en_AU' ) )   )                                      // English Exceptions
                   && ( wpbc_is_file_exist( '/js/datepick/jquery.datepick-'. $locale_country . '.js' ) ) 
        ) { 

                $calendar_localization_url = wpbc_plugin_url( '/js/datepick/jquery.datepick-'. $locale_country . '.js' );                
        }          
    } 

    return $calendar_localization_url;
}


/**
	 * Check if we activated loading of JS/CSS only on specific pages and then load or no it
 * 
 * @param boolean $is_load_scripts  - Default: true
 * @return boolean                  - true | false
 */
function wpbc_is_load_css_js_on_client_page( $is_load_scripts ) {
    
    if ( ! is_admin() ) {           // Check  on Client side only
        
        $booking_is_load_js_css_on_specific_pages = get_bk_option( 'booking_is_load_js_css_on_specific_pages'  );
        if ( $booking_is_load_js_css_on_specific_pages == 'On' ) {
            
            $booking_pages_for_load_js_css = get_bk_option( 'booking_pages_for_load_js_css' );

            $booking_pages_for_load_js_css = preg_split('/[\r\n]+/', $booking_pages_for_load_js_css, -1, PREG_SPLIT_NO_EMPTY);

            $request_uri = $_SERVER['REQUEST_URI'];                                 //FixIn:5.4.1
	        //FixIn: 8.4.5.8
            if (
            	   ( strpos( $request_uri, 'booking_hash=') !== false )
                || ( strpos( $request_uri, 'check_in=') !== false )
            ) {
                $request_uri = parse_url($request_uri);
                if (  ( ! empty($request_uri ) ) && ( isset($request_uri['path'] ) )  ){
                    $request_uri = $request_uri['path'];
                } else {
                    $request_uri = $_SERVER['REQUEST_URI'];
                }
            }

            if (  ( ! empty($booking_pages_for_load_js_css ) ) && ( ! in_array( $request_uri, $booking_pages_for_load_js_css ) )  )
                    return false;
        }
    }
    return true;
}
add_filter( 'wpbc_is_load_script_on_this_page', 'wpbc_is_load_css_js_on_client_page' );