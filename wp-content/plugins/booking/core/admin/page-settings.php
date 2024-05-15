<?php /**
 * @version 1.0
 * @package Booking Calendar 
 * @category Content of Settings page 
 * @author wpdevelop
 *
 * @web-site https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com 
 * 
 * @modified 2015-11-02
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/**
	 * Show Content
 *  Update Content
 *  Define Slug
 *  Define where to show
 */
class WPBC_Page_SettingsGeneral extends WPBC_Page_Structure {
    
    private $settings_api = false;
    
    public function __construct() {
        
        if ( ! wpbc_is_mu_user_can_be_here( 'only_super_admin' ) ) {            // If this User not "super admin",  then  do  not load this page at all

            if  (
                    ( isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] == 'wpbc-settings' )  )                  // Check  if this Settings page
                &&
                    (  ( ! isset( $_REQUEST['tab'] ) ) || ( $_REQUEST['tab'] == 'general' )  ) 
                ) {     // If tab  was not selected or selected default,  then  redirect  it to the "form" tab.            
                $_REQUEST['tab'] = 'form';
            }

        }
        else {
            parent::__construct();
        }
        
    }
    
    public function in_page() {
        
        if ( ! wpbc_is_mu_user_can_be_here( 'only_super_admin' ) ) {            // If this User not "super admin",  then  do  not load this page at all            
            return (string) rand(100000, 1000000);
        }
        
        return 'wpbc-settings';
    }        
    

    /**
	 * Get Settings API class - define, show, update "Fields".
     * 
     * @return object Settings API
     */    
    public function settings_api(){
        
        if ( $this->settings_api === false )             
             $this->settings_api = new WPBC_Settings_API_General(); 
        
        return $this->settings_api;
    }
    
    
    public function tabs() {
       
        $tabs = array();
                
        $tabs[ 'general' ] = array(
                                        'title' => __( 'General', 'booking')                     // Title of TAB    
                                      , 'page_title' => __( 'General Settings', 'booking')                // Title of Page    
                                      , 'hint'      => __( 'General Settings', 'booking')               // Hint    
                                      , 'link' => ''                      // Can be skiped,  then generated link based on Page and Tab tags. Or can  be extenral link
                                      , 'position' => ''                  // 'left'  ||  'right'  ||  ''
                                      , 'css_classes' => ''               // CSS class(es)
                                      , 'icon' => ''                      // Icon - link to the real PNG img
                                      , 'font_icon' => 'wpbc_icn_tune'                 // CSS definition  of forn Icon
                                      , 'default' => true                // Is this tab activated by default or not: true || false. 
                    );

        $subtabs = array();
		$subtabs['wpbc-settings-info'] = array( 'type' => 'html', 'html' => wpbc_get_flex_dashboard_info() );

if (0){
        $subtabs['wpbc-settings-calendar'] = array(   'type' => 'goto-link'
                                                    , 'title' => __('Calendar','booking')
                                                    , 'show_section' => 'wpbc_general_settings_calendar_metabox'
                                                );

		//FixIn: 8.7.11.10
        $subtabs['wpbc-settings-time-slots'] = array(   'type' => 'goto-link'
                                                    , 'title' => __('Time Slots','booking')
                                                    , 'show_section' => 'wpbc_general_settings_time_slots_metabox'
                                                );

        $subtabs['wpbc-settings-availability'] = array(   'type' => 'goto-link'
                                                    , 'title' => __('Availability','booking')
                                                    , 'show_section' => 'wpbc_general_settings_availability_metabox'
                                                );

        $subtabs['wpbc-settings-form'] = array(     'type' => 'goto-link'
                                                    , 'title' => __('Form','booking')
                                                    , 'show_section' => 'wpbc_general_settings_form_metabox'
                                                );

        $subtabs['wpbc-settings-booking-listing'] = array(  'type' => 'goto-link'
                                                    , 'title' => __('Booking Admin Panel','booking')
                                                    , 'show_section' => 'wpbc_general_settings_booking_listing_metabox'
                                                );

        $subtabs['wpbc-settings-booking-timeline'] = array(  'type' => 'goto-link'
                                                    , 'title' =>  __('Timeline', 'booking')
                                                    , 'show_section' => 'wpbc_general_settings_booking_timeline_metabox'
                                                );

        if ( class_exists('wpdev_bk_biz_s') ) {


            $subtabs['wpbc-settings-auto-cancelation-approval'] = array(  'type' => 'goto-link'
                                                        , 'title' => __('Auto cancellation / approval','booking')
                                                        , 'show_section' => 'wpbc_general_settings_auto_cancelation_approval_metabox'
                                                    );
        }

        $subtabs['wpbc-settings-capacity'] = array(   'type' => 'goto-link'
                                                    , 'title' => __('Capacity', 'booking') //. ' ' . '<span class="wpbc_new_label">' . __( 'New', 'booking' ) . '</span>'
                                                    , 'show_section' => 'wpbc_general_settings_capacity_metabox'
                                                );
        $subtabs['wpbc-settings-capacity_new'] = array(
													  'type' => 'html'
													, 'html' => '<span class="wpbc_new_label" style="margin-left: -15px;border:none;">' . __( 'New', 'booking' ) . '</span>'
												);

        $subtabs['wpbc-settings-advanced'] = array(  'type' => 'goto-link'
                                                    , 'title' => __('Advanced','booking')
                                                    , 'show_section' => 'wpbc_general_settings_advanced_metabox'
                                                );

        $subtabs['wpbc-settings-menu-access'] = array(  'type' => 'goto-link'
                                                    , 'title' => __('Plugin Menu','booking')
                                                    , 'show_section' => 'wpbc_general_settings_permissions_metabox'
                                                );

        $subtabs['wpbc-settings-uninstall'] = array(  'type' => 'goto-link'
                                                    , 'title' => __('Uninstall','booking')
                                                    , 'show_section' => 'wpbc_general_settings_uninstall_metabox'
                                                );

//        $subtabs['wpbc-settings-technical'] = array(  'type' => 'goto-link'
//                                                    , 'title' => __('Help', 'booking')
//                                                    , 'show_section' => 'wpbc_general_settings_help_metabox'
//                                                );

        $subtabs['form-save'] = array(
                                        'type' => 'button'
                                        , 'title' => __('Save Changes','booking')
                                        , 'form' => 'wpbc_general_settings_form'
                                    );
}
        
        $tabs[ 'general' ][ 'subtabs' ] = $subtabs;
        
        return $tabs;
    }


    public function content() {
                
        // Checking ////////////////////////////////////////////////////////////
        
        do_action( 'wpbc_hook_settings_page_header', 'general_settings');       // Define Notices Section and show some static messages, if needed
        
        if ( ! wpbc_is_mu_user_can_be_here('activated_user') ) return false;    // Check if MU user activated, otherwise show Warning message.
   
        if ( ! wpbc_is_mu_user_can_be_here('only_super_admin') ) return false;  // User is not Super admin, so exit.  Basically its was already checked at the bottom of the PHP file, just in case.
            
        $is_can = apply_bk_filter('recheck_version', true); if ( ! $is_can ) { ?><script type="text/javascript"> jQuery(document).ready(function(){ jQuery( '.wpdvlp-sub-tabs').remove(); }); </script><?php return; }
        
        
        // Init Settings API & Get Data from DB ////////////////////////////////
        $this->settings_api();                                                  // Define all fields and get values from DB
        
        // Submit  /////////////////////////////////////////////////////////////
        
        $submit_form_name = 'wpbc_general_settings_form';                       // Define form name
                
        if ( isset( $_POST['is_form_sbmitted_'. $submit_form_name ] ) ) {

            // Nonce checking    {Return false if invalid, 1 if generated between, 0-12 hours ago, 2 if generated between 12-24 hours ago. }
            $nonce_gen_time = check_admin_referer( 'wpbc_settings_page_' . $submit_form_name  );  // Its stop show anything on submiting, if its not refear to the original page

            // Save Changes 
            $this->update();
        }                
	    if ( ! empty( $_GET['scroll_to_section'] ) ) {
			?><script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery( '#<?php echo esc_js( $_GET['scroll_to_section'] ); ?> a' ).trigger('click');
				});
			</script><?php
	    }

        // JavaScript: Tooltips, Popover, Datepick (js & css) //////////////////
        echo '<span class="wpdevelop">';
        wpbc_js_for_bookings_page();                                        
        echo '</span>';


        // Content  ////////////////////////////////////////////////////////////
        ?>
        <div class="clear" style="margin-bottom:10px;"></div>
		<div class="wpbc_settings_flex_container">

			<div class="wpbc_settings_flex_container_left">

				<div class="wpbc_settings_navigation_column">

					<div id="wpbc_general_settings_calendar_tab" class="wpbc_settings_navigation_item wpbc_settings_navigation_item_active">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_calendar_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Calendar', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_days_tooltips_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_days_tooltips_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Tooltips in days', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_availability_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_availability_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Availability', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_capacity_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_capacity_metabox,#wpbc_general_settings_capacity_upgrade_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Capacity', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_form_tab" class="wpbc_settings_navigation_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_form_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Form Options', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_time_slots_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_time_slots_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Time Slots', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_booking_confirmation_tab" class="wpbc_settings_navigation_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_booking_confirmation_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Booking Confirmation', 'booking' ) ?></span>
						</a>
					</div>

					<div id="wpbc_general_settings_booking_timeline_tab" class="wpbc_settings_navigation_item wpbc_navigation_top_border">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_booking_timeline_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Timeline (front-end)', 'booking' ) ?></span>
						</a>
					</div>
					<?php if ( class_exists('wpdev_bk_personal') ) { ?>
						<div id="wpbc_general_settings_bookings_options_tab" class="wpbc_settings_navigation_item wpbc_navigation_top_border">
							<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_bookings_options_metabox' );" href="javascript:void(0);">
								<span><?php _e( 'Manage Bookings', 'booking' ) ?></span>
							</a>
						</div>
					<?php } ?>

					<?php if ( class_exists('wpdev_bk_biz_s') ) { ?>
						<div id="wpbc_general_settings_auto_cancelation_approval_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
							<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_auto_cancelation_approval_metabox' );" href="javascript:void(0);">
								<span><?php _e( 'Auto Cancellation / Auto Approval', 'booking' ) ?></span>
							</a>
						</div>
					<?php } ?>


					<?php if ( class_exists('wpdev_bk_multiuser') ) { ?>
						<div id="wpbc_general_settings_multiuser_tab" class="wpbc_settings_navigation_item">
							<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_multiuser_metabox' );" href="javascript:void(0);">
								<span><?php _e( 'Multiuser options', 'booking' ) ?></span>
							</a>
						</div>
					<?php } ?>

					<div id="wpbc_general_settings_booking_listing_tab" class="wpbc_settings_navigation_item wpbc_navigation_top_border">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_booking_listing_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Admin Panel', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_booking_calendar_overview_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_booking_calendar_overview_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Calendar Overview', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_datestimes_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_datestimes_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Date / Time Formats', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_permissions_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_permissions_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Plugin Menu / Permissions', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_translations_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_translations_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Translations', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_advanced_tab" class="wpbc_settings_navigation_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_advanced_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Advanced', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_uninstall_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_uninstall_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Uninstall / deactivation', 'booking' ) ?></span>
						</a>
					</div>
					<div id="wpbc_general_settings_information_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_information_metabox' );" href="javascript:void(0);">
							<span><?php _e( 'Info / News', 'booking' ) ?></span>
						</a>
					</div>

					<?php if ( ( class_exists('wpdev_bk_personal') ) && ( ! wpbc_is_this_demo() ) ) { ?>
						<div id="wpbc_general_settings_help_tab" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
							<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'#wpbc_general_settings_help_metabox' );" href="javascript:void(0);">
								<span><?php _e( 'Tools', 'booking' ) ?></span>
							</a>
						</div>
					<?php } ?>

					<div id="wpbc_general_settings_all_tab" class="wpbc_settings_navigation_item">
						<a class="" original-title="" onclick="javascript:wpbc_navigation_click_show_section(this,'.postbox' );" href="javascript:void(0);">
							<span><?php _e( 'Show All Settings', 'booking' ) ?></span>
						</a>
					</div>

				</div>

			</div>
			<div class="wpbc_settings_flex_container_right">

					<span class="metabox-holder">
					<form  name="<?php echo $submit_form_name; ?>" id="<?php echo $submit_form_name; ?>" action="" method="post">
						<?php
						   // N o n c e   field, and key for checking   S u b m i t
						   wp_nonce_field( 'wpbc_settings_page_' . $submit_form_name );
						?><input type="hidden" name="is_form_sbmitted_<?php echo $submit_form_name; ?>" id="is_form_sbmitted_<?php echo $submit_form_name; ?>" value="1" />
						  <input type="hidden" name="form_visible_section" id="form_visible_section" value="" />

					<?php if ( wpbc_is_show_general_setting_options() )  { //FixIn: 8.9.4.11	?>

						<div class="wpbc_settings_row wpbc_settings_row_full_width" >

							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_calendar', __('Calendar', 'booking'), array( 'is_section_visible_after_load' => true, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'calendar' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_days_tooltips', __('Calendar Dates Tooltips', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'days_tooltips' ); ?>

							<?php wpbc_close_meta_box_section(); ?>



							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_time_slots', __('Time Slots', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'time_slots' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_availability', __('Availability', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'availability' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_form', __('Form Options', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'form' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_booking_confirmation', __('Booking Confirmation', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'booking_confirmation' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php if ( class_exists('wpdev_bk_personal') ) { ?>
								<?php wpbc_open_meta_box_section( 'wpbc_general_settings_bookings_options', __('Bookings Options', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

								<?php $this->settings_api()->show( 'bookings_options' ); ?>

								<?php wpbc_close_meta_box_section(); ?>
							<?php } ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_booking_listing', __('Booking Admin Panel', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'booking_listing' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_booking_calendar_overview', __('Calendar Overview (admin panel)', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php //FixIn: 8.5.2.20
								$this->settings_api()->show( 'booking_calendar_overview' );
							?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_booking_timeline', __('Timeline (front-end)', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php
								$this->settings_api()->show( 'booking_timeline' );
							?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php if ( class_exists('wpdev_bk_biz_s') ) { ?>


								<?php wpbc_open_meta_box_section( 'wpbc_general_settings_auto_cancelation_approval', __('Auto cancellation / auto approval of bookings', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

								<?php $this->settings_api()->show( 'auto_cancelation_approval' ); ?>

								<?php wpbc_close_meta_box_section(); ?>

							<?php } ?>



							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_capacity', __('Capacity', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'capacity' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php
							if ( ! class_exists( 'wpdev_bk_personal' ) ) {

								$wpbc_metabox_id = 'wpbc_general_settings_capacity_upgrade';

								ob_start();
								$is_panel_visible = wpbc_is_dismissed( $wpbc_metabox_id . '_metabox', array(
																		'title' => '<i class="menu_icon icon-1x wpbc_icn_close"></i> ',
																		'hint'  => __( 'Dismiss', 'booking' ),
																		'class' => 'wpbc_panel_get_started_dismiss',
																		'css'   => 'background: #fff;border-radius: 7px;'
																	));
								 ?><script type="text/javascript"> jQuery('#<?php echo $wpbc_metabox_id; ?>_metabox').hide(); </script><?php
								$dismiss_button_content = ob_get_clean();

								if ( $is_panel_visible ) {

									wpbc_open_meta_box_section( $wpbc_metabox_id,
																	  __('Booking Quantity Control - Set limits for the number of bookings per day or time slot.', 'booking'),
																	  array(  'is_section_visible_after_load' => false,
																			  'is_show_minimize'   => false,
																			  'dismiss_button'     => $dismiss_button_content
															  ) );

									$this->settings_api()->show( 'capacity_upgrade' );

									wpbc_close_meta_box_section();
								}
							}
							?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_advanced', __('Advanced', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'advanced' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_information', __('Information', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'information' ); ?>

							<?php wpbc_close_meta_box_section(); ?>



							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_datestimes', __('Date : Time', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'date_time' ); ?>

							<?php wpbc_close_meta_box_section(); ?>



							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_permissions', __('Plugin Menu', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'permissions' ); ?>

							<?php wpbc_close_meta_box_section(); ?>


							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_uninstall', __('Uninstall / deactivation', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'uninstall' ); ?>

							<?php wpbc_close_meta_box_section(); ?>

							<?php if ( ( class_exists( 'wpdev_bk_personal' ) ) && ( ! wpbc_is_this_demo() ) ) { ?>
								<?php wpbc_open_meta_box_section( 'wpbc_general_settings_help', __('Tools', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

								<?php $this->settings_api()->show( 'help' ); ?>

								<?php wpbc_close_meta_box_section(); ?>
							<?php } ?>

							<?php wpbc_open_meta_box_section( 'wpbc_general_settings_translations', __('Translations', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

							<?php $this->settings_api()->show( 'translations' ); ?>

							<?php wpbc_translation_buttons_settings_section(); ?>

							<?php wpbc_close_meta_box_section(); ?>

							<?php if ( class_exists('wpdev_bk_multiuser') ) {	//FixIn: 9.2.3.8 ?>

								<?php wpbc_open_meta_box_section( 'wpbc_general_settings_multiuser', __('Multiuser options', 'booking'), array( 'is_section_visible_after_load' => false, 'is_show_minimize' => false ) ); ?>

								<?php $this->settings_api()->show( 'multiuser' ); ?>

								<?php wpbc_close_meta_box_section(); ?>

							<?php } ?>

						</div>

						<div class="clear"></div>
						<input type="submit" value="<?php _e('Save Changes','booking'); ?>" class="button button-primary wpbc_submit_button" />
						<?php
								if ( 'translations_updated_from_wpbc_and_wp' !== get_bk_option( 'booking_translation_update_status' ) ) {

									$current_locale = wpbc_get_maybe_reloaded_booking_locale();

									if	( ! in_array( $current_locale, array( 'en_US', 'en_CA', 'en_GB', 'en_AU' ) ) ) {

										echo
											'<a class="button button" href="'
											. wpbc_get_settings_url()
											. '&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .'&update_translations=1#wpbc_general_settings_system_info_metabox">'
											. __( 'Update Translations' )
											. '</a>';
									}
								}

								if ( ! wpbc_is_this_demo() ) {

									echo  '<a style="margin:0 2em;" class="button button" href="' . wpbc_get_settings_url()
																				 . '&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .'&restore_dismissed=On#wpbc_general_settings_restore_dismissed_metabox">'
																				 . __('Restore all dismissed windows' ,'booking')
										. '</a>';
								}
						?>

					<?php } ?>
					</form>
					</span>

			</div>
		</div>


    	<?php

		do_action( 'wpbc_hook_settings_page_footer', 'general_settings' );
    }


    public function update() {

        $validated_fields = $this->settings_api()->validate_post();             // Get Validated Settings fields in $_POST request.
        
        $validated_fields = apply_filters( 'wpbc_settings_validate_fields_before_saving', $validated_fields );   //Hook for validated fields.

        // unset($validated_fields['booking_start_day_weeek']);					// Skip saving specific option, for example in Demo mode.

	    //FixIn: 9.8.6.1
	    if ( ! empty( $_POST['form_visible_section'] ) ) {
			?><script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery( '<?php echo esc_js( $_POST['form_visible_section'] ); ?> a' ).trigger('click');
				});
			</script><?php
	    }

        $this->settings_api()->save_to_db( $validated_fields );                 // Save fields to DB

		wpbc_show_changes_saved_message();

        /**
        // O L D   W A Y:   Saving Fields Data
        //      update_bk_option( 'booking_is_delete_if_deactive'
        //                       , WPBC_Settings_API::validate_checkbox_post('booking_is_delete_if_deactive') );  
        //      ( (isset( $_POST['booking_is_delete_if_deactive'] ))?'On':'Off') );
		*/
    }
}


/**
 *

if ( ! wpbc_is_mu_user_can_be_here( 'only_super_admin' ) ) {                    // If this User not "super admin",  then  do  not load this page at all

    if (  ( ! isset( $_GET['tab'] ) ) || ( $_GET['tab'] == 'general' )  ) {     // If tab  was not selected or selected default,  then  redirect  it to the "form" tab.
        $_GET['tab'] = 'form';
    }
} else {
    add_action('wpbc_menu_created', array( new WPBC_Page_SettingsGeneral() , '__construct') );    // Executed after creation of Menu
}
*/

 add_action('wpbc_menu_created', array( new WPBC_Page_SettingsGeneral() , '__construct') );    // Executed after creation of Menu
 