<?php
/*
* @package: AJX_Customize_Plugin Page
* @category: Initial  setup  and plugin  customization
* Author: wpdevelop, oplugins
* Version: 1.0
* @modified 2023-06-12
*/
//FixIn: 9.8.0.1
if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/** Show Content
 *  Update Content
 *  Define Slug
 *  Define where to show
 */
class WPBC_Page_AJX_Customize_Plugin extends WPBC_Page_Structure {


   	public function __construct() {

        parent::__construct();

		add_action( 'wpbc_toolbar_top_tabs_after',  array( $this, 'wpbc_toolbar_toolbar_tabs' ) );
		add_action( 'wpbc_toolbar_top_tabs_insert', array( $this, 'wpbc_toolbar_toolbar_tabs' ) );
    }


    public function in_page() {
        return 'wpbc-customize_plugin';
    }


    public function tabs() {

        $tabs = array();
        $tabs[ 'customize_plugin' ] = array(
                              'title'		=> __( 'Customize', 'booking' )						// Title of TAB
                            , 'hint'		=> __( 'Customize', 'booking' ) . ' - '	 . 'Booking Calendar'					// Hint
                            , 'page_title'	=> __( 'Configuration Wizard', 'booking' ) 					// Title of Page
                            , 'link'		=> ''								// Can be skiped,  then generated link based on Page and Tab tags. Or can  be extenral link
                            , 'position'	=> ''                               // 'left'  ||  'right'  ||  ''
                            , 'css_classes' => ''                               // CSS class(es)
                            , 'icon'		=> ''                               // Icon - link to the real PNG img
                            , 'font_icon'	=> 'wpbc_icn_tune'//'wpbc_icn_free_cancellation'		// CSS definition  of forn Icon
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

			if ( $this->in_page() == $menu_in_page_tag ) {

				// Just  for get  last  saved default tab
				$escaped_search_request_params = $this->get_cleaned_params__saved_requestvalue_default();

				// Check if by  some reason, user was saved request without this parameter, then get  default value
				if ( ! empty( $escaped_search_request_params['current_step'] ) ) {
					$selected_tab = $escaped_search_request_params['current_step'];
				} else {
					$default_search_request_params = WPBC_AJX__Customize_Plugin__Ajax_Request::request_rules_structure();
					$selected_tab = $default_search_request_params ['current_step']['default'];
				}

				$current_step_page = explode( '_', $selected_tab );		// 'calendar_skin', 'calendar_size', 'calendar_dates_selection', 'calendar_weekdays_availability', 'calendar_additional',   'form_structure', ...
wpbc_bs_toolbar_tabs_html_container_start();
				wpbc_bs_display_tab(   array(
													  'title'       => '1. '. __( 'Calendar', 'booking' )
													, 'hint' 	    => array( 'title' => __('Customize' ,'booking') , 'position' => 'top' )
													, 'onclick'     =>    "jQuery('.ui_container_toolbar').hide();" . "jQuery('.ui_container_calendar_skin').show();" . "jQuery('.wpbc_customize_plugin_support_tabs').removeClass('nav-tab-active');" . "jQuery(this).addClass('nav-tab-active');" . "jQuery('.nav-tab i.icon-white').removeClass('icon-white');" . "jQuery('.nav-tab-active i').addClass('icon-white');"
																		/**
																		 * It will save such changes, and if we have selected bookings, then deselect them
																		 */
																		   . "wpbc_ajx_customize_plugin__send_request_with_params( { 'current_step': 'calendar_skin' });"
																		/**
																		 * It will save changes with NEXT search request, but not immediately
																		 * it is handy, in case if we have selected bookings,
																		 * we will not lose selection.
																		 */
																		// . "wpbc_ajx_customize_plugin.search_set_param( 'current_step', 'calendar_skin' );"
													, 'font_icon'   => 'wpbc-bi-calendar2-check'
													, 'default'     => ( 'calendar' == $current_step_page[0] ) ? true : false
													//, 'position' 	=> 'right'
													, 'css_classes' => 'wpbc_customize_plugin_support_tabs'
									) );
				wpbc_bs_display_tab(   array(
													  'title'       => '2. '. __('Booking Form', 'booking')
													, 'hint' 	    => array( 'title' => __('Customize' ,'booking') , 'position' => 'top' )
													, 'onclick'     =>    "jQuery('.ui_container_toolbar').hide();" . "jQuery('.ui_container_form_structure').show();" . "jQuery('.wpbc_customize_plugin_support_tabs').removeClass('nav-tab-active');" . "jQuery(this).addClass('nav-tab-active');" . "jQuery('.nav-tab i.icon-white').removeClass('icon-white');" . "jQuery('.nav-tab-active i').addClass('icon-white');"
																		/**
																		 * It will save such changes, and if we have selected bookings, then deselect them
																		 */
																		   . "wpbc_ajx_customize_plugin__send_request_with_params( { 'current_step': 'form_structure' });"
																		/**
																		 * It will save changes with NEXT search request, but not immediately
																		 * it is handy, in case if we have selected bookings,
																		 * we will not lose selection.
																		 */
																		// . "wpbc_ajx_customize_plugin.search_set_param( 'current_step', 'calendar_skin' );"
													, 'font_icon'   => 'wpbc_icn_dashboard _customize dashboard rtt draw'
													, 'default'     => ( 'form' == $current_step_page[0] ) ? true : false
													//, 'position' 	=> 'right'
													, 'css_classes' => 'wpbc_customize_plugin_support_tabs'
									) );
				wpbc_bs_display_tab(   array(
													  'title'       => '3. '. __('Emails', 'booking')
													, 'hint' 	    => array( 'title' => __('Customize' ,'booking') , 'position' => 'top' )
													, 'onclick'     =>    "jQuery('.ui_container_toolbar').hide();" . "jQuery('.ui_container_emails_active').show();" . "jQuery('.wpbc_customize_plugin_support_tabs').removeClass('nav-tab-active');" . "jQuery(this).addClass('nav-tab-active');" . "jQuery('.nav-tab i.icon-white').removeClass('icon-white');" . "jQuery('.nav-tab-active i').addClass('icon-white');"
																		/**
																		 * It will save such changes, and if we have selected bookings, then deselect them
																		 */
																		   . "wpbc_ajx_customize_plugin__send_request_with_params( { 'current_step': 'emails_active' });"
																		/**
																		 * It will save changes with NEXT search request, but not immediately
																		 * it is handy, in case if we have selected bookings,
																		 * we will not lose selection.
																		 */
																		// . "wpbc_ajx_customize_plugin.search_set_param( 'current_step', 'calendar_skin' );"
													, 'font_icon'   => 'wpbc_icn_mail_outline'
													, 'default'     => ( 'emails' == $current_step_page[0] ) ? true : false
													//, 'position' 	=> 'right'
													, 'css_classes' => 'wpbc_customize_plugin_support_tabs'
									) );
				wpbc_bs_display_tab(   array(
													  'title'       => '4. '. __('Payments', 'booking')
													, 'hint' 	    => array( 'title' => __('Customize' ,'booking') , 'position' => 'top' )
													, 'onclick'     =>    "jQuery('.ui_container_toolbar').hide();" . "jQuery('.ui_container_payments_active').show();" . "jQuery('.wpbc_customize_plugin_support_tabs').removeClass('nav-tab-active');" . "jQuery(this).addClass('nav-tab-active');" . "jQuery('.nav-tab i.icon-white').removeClass('icon-white');" . "jQuery('.nav-tab-active i').addClass('icon-white');"
																		/**
																		 * It will save such changes, and if we have selected bookings, then deselect them
																		 */
																		   . "wpbc_ajx_customize_plugin__send_request_with_params( { 'current_step': 'payments_active' });"
																		/**
																		 * It will save changes with NEXT search request, but not immediately
																		 * it is handy, in case if we have selected bookings,
																		 * we will not lose selection.
																		 */
																		// . "wpbc_ajx_customize_plugin.search_set_param( 'current_step', 'calendar_skin' );"
													, 'font_icon'   => 'wpbc_icn_payment'
													, 'default'     => ( 'payments' == $current_step_page[0] ) ? true : false
													//, 'position' 	=> 'right'
													, 'css_classes' => 'wpbc_customize_plugin_support_tabs'
									) );
				wpbc_bs_display_tab(   array(
													  'title'       => '5. '. __('Publish Resources', 'booking')
													, 'hint' 	    => array( 'title' => __('Customize' ,'booking') , 'position' => 'top' )
													, 'onclick'     =>    "jQuery('.ui_container_toolbar').hide();" . "jQuery('.ui_container_publish_resource').show();" . "jQuery('.wpbc_customize_plugin_support_tabs').removeClass('nav-tab-active');" . "jQuery(this).addClass('nav-tab-active');" . "jQuery('.nav-tab i.icon-white').removeClass('icon-white');" . "jQuery('.nav-tab-active i').addClass('icon-white');"
																		/**
																		 * It will save such changes, and if we have selected bookings, then deselect them
																		 */
																		   . "wpbc_ajx_customize_plugin__send_request_with_params( { 'current_step': 'publish_resource' });"

																		/**
																		 * It will save changes with NEXT search request, but not immediately
																		 * it is handy, in case if we have selected bookings,
																		 * we will not lose selection.
																		 */
																		// . "wpbc_ajx_customize_plugin.search_set_param( 'current_step', 'calendar_skin' );"
													, 'font_icon'   => 'wpbc_icn_checklist'
													, 'default'     => ( 'publish' == $current_step_page[0] ) ? true : false
													//, 'position' 	=> 'right'
													, 'css_classes' => 'wpbc_customize_plugin_support_tabs'
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
													   'db_option_name'          => 'booking_customize_plugin_request_params',
													   'user_id'                 => wpbc_get_current_user_id(),
													   'request_rules_structure' => WPBC_AJX__Customize_Plugin__Ajax_Request::request_rules_structure()
													)
							);
			$escaped_request_params_arr = $user_request->get_sanitized__saved__user_request_params();		// Get Saved

			if ( false === $escaped_request_params_arr ) {			// This request was not saved before, then get sanitized direct parameters, like: 	$_REQUEST['resource_id']

				$request_prefix = false;
				$escaped_request_params_arr = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 		// Direct: 	$_REQUEST['resource_id']
			}


			// Override parameters from DB  by  parameters from  REQUEST! ----------------------------------------------
			$request_key = 'current_step';
		 	if ( isset( $_REQUEST[ $request_key ] ) ) {

				 // Get SANITIZED REQUEST parameters together with default values
				$request_prefix = false;
				$url_request_params_arr = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 		// Direct: 	$_REQUEST['resource_id']

				// Now get only SANITIZED values that exist in REQUEST
				$url_request_params_only_arr = array_intersect_key( $url_request_params_arr, $_REQUEST );

				// And now override our DB  $escaped_request_params_arr  by  SANITIZED $_REQUEST values
				$escaped_request_params_arr   = wp_parse_args( $url_request_params_only_arr, $escaped_request_params_arr );
			}
			// ---------------------------------------------------------------------------------------------------------

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

        do_action( 'wpbc_hook_settings_page_header', 'page_booking_customize_plugin');						// Define Notices Section and show some static messages, if needed.

	    if ( ! wpbc_is_mu_user_can_be_here( 'activated_user' ) ) {  return false;  }  						// Check if MU user activated, otherwise show Warning message.

 		// if ( ! wpbc_set_default_resource_to__get() ) return false;                  						// Define default booking resources for $_GET  and  check if booking resource belong to user.


		// Get and escape request parameters	////////////////////////////////////////////////////////////////////////
       	$escaped_request_params_arr = $this->get_cleaned_params__saved_requestvalue_default();

		// During initial load of the page,  we need to  reset  'dates_selection' value in our saved parameter
	 	$escaped_request_params_arr['dates_selection'] = '';

        // Submit  /////////////////////////////////////////////////////////////
        $submit_form_name = 'wpbc_ajx_customize_plugin_form';                             	// Define form name

		?><span class="wpdevelop"><?php                                         		// BS UI CSS Class

			wpbc_js_for_bookings_page();                                            	// JavaScript functions

			?><div id="toolbar_booking_customize_plugin" class="wpbc_ajx_toolbar"><?php
					?><div class="wpbc_ajx_customize_plugin_toolbar_container"></div><?php //This Div Required for bottom border radius in container
			?></div><?php

//		    wpbc_ajx_customize_plugin__toolbar( $escaped_request_params_arr );

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

				$this->ajx_customize_plugin_container__show( $escaped_request_params_arr );

				wpbc_clear_div();

		  ?></form>
        </span>
        <?php

		//wpbc_show_wpbc_footer();			// Rating

        do_action( 'wpbc_hook_settings_page_footer', 'wpbc-ajx_booking_customize_plugin' );
    }

		private function ajx_customize_plugin_container__show( $escaped_request_params_arr ) {

			$is_show_resource_unavailable_stripes = ( !true ) ? ' wpbc_ajx_availability_container' : '';
			?>
			<div id="ajx_nonce_calendar_section"></div>
			<div class="wpbc_listing_container wpbc_selectable_table wpbc_ajx_customize_plugin_container wpdevelop<?php echo $is_show_resource_unavailable_stripes; ?>" wpbc_loaded="first_time">
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
					wpbc_ajx_customize_plugin.set_secure_param( 'nonce',   '<?php echo wp_create_nonce( 'wpbc_ajx_customize_plugin_ajx' . '_wpbcnonce' ) ?>' );
					wpbc_ajx_customize_plugin.set_secure_param( 'user_id', '<?php echo wpbc_get_current_user_id(); ?>' );
					wpbc_ajx_customize_plugin.set_secure_param( 'locale',  '<?php echo get_user_locale(); ?>' );

					// Set other parameters
					wpbc_ajx_customize_plugin.set_other_param( 'listing_container',    '.wpbc_ajx_customize_plugin_container' );
					wpbc_ajx_customize_plugin.set_other_param( 'toolbar_container',    '.wpbc_ajx_customize_plugin_toolbar_container' );

					// Send Ajax request and show listing after this.
					wpbc_ajx_customize_plugin__send_request_with_params( <?php echo wp_json_encode( $escaped_request_params_arr ); ?> );
				} );
			</script>
			<?php
		}

		/**
		 * Hide first "Customize_Plugin tab",  because we have only  one tab here,  and need to  show only  tabs for toolbar
		 * @return void
		 */
		private function css_fix(){
			return;
		    ?><style type="text/css">
		    	.nav-tabs .nav-tab:first-child{
				    /*display:none;  !*flex: It's hide first Tab in toolbar. We have visible toolbar, and by default we can see page tab. But by this hook 'wpbc_toolbar_top_tabs_after' we added own custom tabs. *!*/
			    }
			</style><?php
		}

}
add_action('wpbc_menu_created', array( new WPBC_Page_AJX_Customize_Plugin() , '__construct') );    // Executed after creation of Menu
