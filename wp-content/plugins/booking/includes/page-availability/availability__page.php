<?php
/*
* @package: AJX_Bookings Page
* @category: o Email Reminders
* @description: Define AJX_Bookings in admin settings page. - Sending friendly email reminders based on custom ajx_booking.
* Plugin URI: https://oplugins.com/plugins/email-reminders/#premium
* Author URI: https://oplugins.com
* Author: wpdevelop, oplugins
* Version: 0.0.1
* @modified 2020-05-11
*/
//FixIn: 9.2.1
if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/** Show Content
 *  Update Content
 *  Define Slug
 *  Define where to show
 */
class WPBC_Page_AJX_Availability extends WPBC_Page_Structure {


   	public function __construct() {

        parent::__construct();

		add_action( 'wpbc_toolbar_top_tabs_after',  array( $this, 'wpbc_toolbar_toolbar_tabs' ) );
		add_action( 'wpbc_toolbar_top_tabs_insert', array( $this, 'wpbc_toolbar_toolbar_tabs' ) );
    }


    public function in_page() {
        return 'wpbc-availability';
    }


    public function tabs() {

        $tabs = array();
        $tabs[ 'availability' ] = array(
                              'title'		=> __( 'Days Availability', 'booking' )										// Title of TAB				//FixIn: 9.8.15.2.2
                            , 'hint'		=> __( 'Define available and unavailable days for calendar(s)', 'booking' )						// Hint
                            , 'page_title'	=> __( 'Calendar Availability', 'booking' )						// Title of Page
                            , 'link'		=> ''								// Can be skiped,  then generated link based on Page and Tab tags. Or can  be extenral link
                            , 'position'	=> ''                               // 'left'  ||  'right'  ||  ''
                            , 'css_classes' => ''                               // CSS class(es)
                            , 'icon'		=> ''                               // Icon - link to the real PNG img
                            , 'font_icon'	=> 'wpbc-bi-calendar2-check'//'wpbc_icn_free_cancellation'		// CSS definition  of forn Icon
                            , 'default'		=> true								// Is this tab activated by default or not: true || false.
                            , 'disabled'	=> false                            // Is this tab disbaled: true || false.
                            , 'hided'		=> false                            // Is this tab hided: true || false.
                            , 'subtabs'		=> array()
        );
        // $subtabs = array();
        // $tabs[ 'items' ][ 'subtabs' ] = $subtabs;
        return $tabs;
    }


		/**
		 * Show custom tabs for Toolbar at . - . R i g h t . s i d e.
		 *
		 * @param string $menu_in_page_tag - active page
		 */
		public function wpbc_toolbar_toolbar_tabs( $menu_in_page_tag ) {

			if (
					( $this->in_page() == $menu_in_page_tag )
				 && ( ( empty( $_GET['tab'] ) ) || ( 'availability' == $_GET['tab'] ) )					//FixIn: 9.8.15.2.2
			) {

				wpbc_bs_toolbar_tabs_html_container_start();

				$escaped_search_request_params = $this->get_cleaned_params__saved_requestvalue_default();

				// Check if by  some reason, user was saved request without this parameter, then get  default value
				if ( ! empty( $escaped_search_request_params['ui_usr__availability_selected_toolbar'] ) ) {
					$selected_tab = $escaped_search_request_params['ui_usr__availability_selected_toolbar'];
				} else {
					$default_search_request_params = WPBC_AJX__Availability::request_rules_structure();
					$selected_tab = $default_search_request_params ['ui_usr__availability_selected_toolbar']['default'];
				}

				wpbc_bs_display_tab(   array(
													'title'         => __( 'Set Availability', 'booking' )
													, 'hint' => array( 'title' => __('Availability' ,'booking') , 'position' => 'top' )
													, 'onclick'     =>  "jQuery('.ui_container_toolbar').hide();"
																		. "jQuery('.ui_container_info').show();"
																		. "jQuery('.wpbc_availability_support_tabs').removeClass('nav-tab-active');"
																		. "jQuery(this).addClass('nav-tab-active');"
																		. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
																		. "jQuery('.nav-tab-active i').addClass('icon-white');"
																		/**
																		 * It will save such changes, and if we have selected bookings, then deselect them
																		 */
																		//		. "wpbc_ajx_booking_send_search_request_with_params( { 'ui_usr__availability_selected_toolbar': 'filters' });"
																		/**
																		 * It will save changes with NEXT search request, but not immediately
																		 * it is handy, in case if we have selected bookings,
																		 * we will not lose selection.
																		 */
																		. "wpbc_ajx_availability.search_set_param( 'ui_usr__availability_selected_toolbar', 'info' );"
													, 'font_icon'   => 'wpbc-bi-calendar2-check' //'wpbc_icn_info_outline'
													, 'default'     => ( 'info' == $selected_tab ) ? true : false
													//, 'position' 	=> 'right'
													, 'css_classes' => 'wpbc_availability_support_tabs'
									) );

				wpbc_bs_display_tab(   array(
													'title'         => __('Options', 'booking')
													, 'hint' => array( 'title' => __('User Options' ,'booking') , 'position' => 'top' )
													, 'onclick'     =>  "jQuery('.ui_container_toolbar').hide();"
																		. "jQuery('.ui_container_options').show();"
																		. "jQuery('.wpbc_availability_support_tabs').removeClass('nav-tab-active');"
																		. "jQuery(this).addClass('nav-tab-active');"
																		. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
																		. "jQuery('.nav-tab-active i').addClass('icon-white');"
																		/**
																		 * It will save such changes, and if we have selected bookings, then deselect them
																		 */
																		// 		. "wpbc_ajx_booking_send_search_request_with_params( { 'ui_usr__availability_selected_toolbar': 'options' });"
																		/**
																		 * It will save changes with NEXT search request, but not immediately
																		 * it is handy, in case if we have selected bookings,
																		 * we will not lose selection.
																		 */
																		. "wpbc_ajx_availability.search_set_param( 'ui_usr__availability_selected_toolbar', 'calendar_settings' );"
													, 'font_icon'   => 'wpbc_icn_tune'
													, 'default'     => ( 'calendar_settings' == $selected_tab ) ? true : false
													//, 'position' 	=> 'right'
													, 'css_classes' => 'wpbc_availability_support_tabs'

									) );

				wpbc_bs_toolbar_tabs_html_container_end();

			}
		}


		/**
		 * Get sanitised request parameters.	:: Firstly  check  if user  saved it. :: Otherwise, check $_REQUEST. :: Otherwise get  default.
		 *
		 * @return array|false
		 */
		public function get_cleaned_params__saved_requestvalue_default(){

			$user_request = new WPBC_AJX__REQUEST( array(
													   'db_option_name'          => 'booking_availability_request_params',
													   'user_id'                 => wpbc_get_current_user_id(),
													   'request_rules_structure' => WPBC_AJX__Availability::request_rules_structure()
													)
							);
			$escaped_request_params_arr = $user_request->get_sanitized__saved__user_request_params();		// Get Saved

//Fix Calendar cell heights in versions older than //FixIn: 9.7.3.2
if ( 	( false !== $escaped_request_params_arr )
	 && ( '' === $escaped_request_params_arr['calendar__view__cell_height'] )
) {
	$user_request->user_request_params__db_delete();    // Delete from DB
}

			if ( false === $escaped_request_params_arr ) {			// This request was not saved before, then get sanitized direct parameters, like: 	$_REQUEST['resource_id']

				$request_prefix = false;
				$escaped_request_params_arr = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 		// Direct: 	$_REQUEST['resource_id']
			}


			//MU
			if ( class_exists( 'wpdev_bk_multiuser' ) ) {

				// Check if this MU user activated or superadmin,  otherwise show warning
				if ( ! wpbc_is_mu_user_can_be_here('activated_user') )
					return  false;

				// Check if this MU user owner of this resource or superadmin,  otherwise show warning
				if ( ! wpbc_is_mu_user_can_be_here( 'resource_owner', $escaped_request_params_arr['resource_id'] ) ) {
					$default_values = $user_request->get_request_rules__default();
					$escaped_request_params_arr['resource_id'] = $default_values['resource_id'];
				}

			}



		    return $escaped_request_params_arr;
		}


    public function content() {

		$this->css_fix();

        do_action( 'wpbc_hook_settings_page_header', 'page_booking_availability');							// Define Notices Section and show some static messages, if needed.

	    if ( ! wpbc_is_mu_user_can_be_here( 'activated_user' ) ) {  return false;  }  						// Check if MU user activated, otherwise show Warning message.

 		// if ( ! wpbc_set_default_resource_to__get() ) return false;                  						// Define default booking resources for $_GET  and  check if booking resource belong to user.


		// Get and escape request parameters	////////////////////////////////////////////////////////////////////////
       	$escaped_request_params_arr = $this->get_cleaned_params__saved_requestvalue_default();

		// During initial load of the page,  we need to  reset  'dates_selection' value in our saved parameter
		 $escaped_request_params_arr['dates_selection'] = '';
		 $escaped_request_params_arr['calendar__start_week_day'] = get_bk_option( 'booking_start_day_weeek' );


        // Submit  /////////////////////////////////////////////////////////////
        $submit_form_name = 'wpbc_ajx_availability_form';                             	// Define form name

		?><span class="wpdevelop"><?php                                         		// BS UI CSS Class

			wpbc_js_for_bookings_page();                                            	// JavaScript functions

		    wpbc_ajx_availability__toolbar( $escaped_request_params_arr );

		?></span><?php

		?><div id="wpbc_log_screen" class="wpbc_log_screen"></div><?php

        // Content  ////////////////////////////////////////////////////////////
        ?>
        <div class="clear" style="margin-bottom:10px;"></div>
        <span class="metabox-holder">
            <form  name="<?php echo $submit_form_name; ?>" id="<?php echo $submit_form_name; ?>" action="" method="post" >
                <?php
                   // N o n c e   field, and key for checking   S u b m i t
                   wp_nonce_field( 'wpbc_settings_page_' . $submit_form_name );
                ?><input type="hidden" name="is_form_sbmitted_<?php echo $submit_form_name; ?>" id="is_form_sbmitted_<?php echo $submit_form_name; ?>" value="1" /><?php

				//wpbc_ajx_booking_modify_container_show();					// Container for showing Edit ajx_booking and define Edit and Delete ajx_booking JavaScript vars.

				wpbc_clear_div();

				$this->ajx_availability_container__show( $escaped_request_params_arr );

				wpbc_clear_div();

		  ?></form>
        </span>
        <?php

		//wpbc_show_wpbc_footer();			// Rating

        do_action( 'wpbc_hook_settings_page_footer', 'wpbc-ajx_booking_availability' );
    }

		private function ajx_availability_container__show( $escaped_request_params_arr ) {

			?>
			<div id="ajx_nonce_calendar_section"></div>
			<div class="wpbc_listing_container wpbc_selectable_table wpbc_ajx_availability_container wpdevelop" wpbc_loaded="first_time">
				<style type="text/css">
					.wpbc_calendar_loading .wpbc_icn_autorenew::before{
						font-size: 1.2em;
					}
					.wpbc_calendar_loading {
						width:95%;
						text-align: center;
						margin:2em 0;
						font-size: 1.2em;
						font-weight: 600;
					}
				</style>
				<div class="wpbc_calendar_loading"><span class="wpbc_icn_autorenew wpbc_spin"></span>&nbsp;&nbsp;<span><?php _e( 'Loading', 'booking' ); ?>...</span>
				</div>
			</div>
			<script type="text/javascript">
				jQuery( document ).ready( function (){

					// Set Security - Nonce for Ajax  - Listing
					wpbc_ajx_availability.set_secure_param( 'nonce',   '<?php echo wp_create_nonce( 'wpbc_ajx_availability_ajx' . '_wpbcnonce' ) ?>' );
					wpbc_ajx_availability.set_secure_param( 'user_id', '<?php echo wpbc_get_current_user_id(); ?>' );
					wpbc_ajx_availability.set_secure_param( 'locale',  '<?php echo get_user_locale(); ?>' );

					// Set other parameters
					wpbc_ajx_availability.set_other_param( 'listing_container',    '.wpbc_ajx_availability_container' );

					// Send Ajax request and show listing after this.
					wpbc_ajx_availability__send_request_with_params( <?php echo wp_json_encode( $escaped_request_params_arr ); ?> );
				} );
			</script>
			<?php
		}

		/**
		 * Hide first "Availability tab",  because we have only  one tab hhere,  and need to  show only  tabs for toolbar
		 * @return void
		 */
		private function css_fix(){
			//FixIn: 9.8.15.2
			/*

		    ?><style type="text/css">
		    	.nav-tabs .nav-tab:first-child{
				    display:none;
			    }
		    </style><?php
			*/
		}

}
add_action('wpbc_menu_created', array( new WPBC_Page_AJX_Availability() , '__construct') );    // Executed after creation of Menu


/**
 * Duplicated Genal  Availability Tab - Redirect  to  Booking > Settings General page in "Availability" section
 *
 */
class WPBC_Page_Availability_General extends WPBC_Page_Structure {


    public function in_page() {

	    if ( ! wpbc_is_mu_user_can_be_here( 'only_super_admin' ) ) {            // If this User not "super admin",  then  do  not load this page at all
	        return (string) rand( 100000, 1000000 );        // If this User not "super admin",  then  do  not load this page at all
        }

		return 'wpbc-availability';
    }



    public function tabs() {

        $tabs = array();
        $tabs[ 'general_availability' ] = array(
                              'title'		=> __( 'General Availability', 'booking' )										// Title of TAB				//FixIn: 9.8.15.2.2
                            , 'hint'		=> __( 'Define unavailable weekdays for all calendar(s) and unavailable dates depend from today date', 'booking' )						// Hint
                            , 'page_title'	=> __( 'General Availability', 'booking' )						// Title of Page
                            , 'link' =>  wpbc_get_settings_url( true, false ) . '&scroll_to_section=wpbc_general_settings_availability_tab'                                      // Can be skiped,  then generated link based on Page and Tab tags. Or can  be extenral link
                            , 'position'	=> ''                               // 'left'  ||  'right'  ||  ''
                            , 'css_classes' => 'wpbc_top_tab__general_availability'                               // CSS class(es)
                            , 'icon'		=> ''                               // Icon - link to the real PNG img
                            , 'font_icon'	=> 'wpbc-bi-calendar2-day 0wpbc-bi-calendar2-check'//'wpbc_icn_free_cancellation'		// CSS definition  of forn Icon
                            , 'default'		=> false								// Is this tab activated by default or not: true || false.
                            , 'disabled'	=> false                            // Is this tab disbaled: true || false.
                            , 'hided'		=> false                            // Is this tab hided: true || false.
                            , 'subtabs'		=> array()
        );
        // $subtabs = array();
        // $tabs[ 'items' ][ 'subtabs' ] = $subtabs;
        return $tabs;
    }

	public function content() {

	}
}
add_action('wpbc_menu_created', array( new WPBC_Page_Availability_General() , '__construct') );    // Executed after creation of Menu
