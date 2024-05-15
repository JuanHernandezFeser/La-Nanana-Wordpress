<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Core
 * @category Bookings
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.07.29
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

global $wp_version;                                                                                                         
$min_wp_version = version_compare( WP_BK_MIN_WP_VERSION, $wp_version, '<=' );   //FixIn: 7.0.1.6
if ( ( ! class_exists( 'Booking_Calendar' ) ) && ( $min_wp_version ) ) :

    
// General Init Class    
final class Booking_Calendar {
        
    static private $instance = NULL;

    public $cron;
    public $notice;
    public $booking_obj;    

    public $admin_menu;
    public $js;
    public $css;

/** Get Single Instance of this Class and Init Plugin */
public static function init() {
    
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Booking_Calendar ) ) {
            
		global $wpbc_settings;
		$wpbc_settings = array();
        
        self::$instance = new Booking_Calendar;
        self::$instance->constants();
        self::$instance->includes();
        self::$instance->define_version();

        if ( class_exists( 'WPBC_BookingInstall' ) ) {                                 									// Check if we need to run Install / Uninstal process.
            new WPBC_BookingInstall();
        }


	    /**
	     *   Make Ajax, Response,  in this case    				$is_continue_at_frontend = false
	     *   or
	     *   define Booking Class for front-end side,  then 	$is_continue_at_frontend = true
	     */
        $is_continue_at_frontend = self::$instance->start();

        make_bk_action('wpbc_booking_calendar_started');

        if ( $is_continue_at_frontend ) {                                                   							// Possible Load Admin or Front-End page
            
            self::$instance->js     = new WPBC_JS;
            self::$instance->css    = new WPBC_CSS;

            if( is_admin() ) {

                // Define Menu
                add_action( '_admin_menu',   array( self::$instance, 'define_admin_menu') );    						// _admin_menu - Fires before the administration menu loads in the admin.

                add_action( 'admin_footer', 'wpbc_print_js', 50 );              			// Load my Queued JavaScript Code at  the footer of the Admin Panel page. Executed in ALL Admin Menu Pages
                
            } else {  
                
                if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  								// Init booking resources cache
                
                add_action( 'wp_enqueue_scripts', array(self::$instance->css, 'load'), 1000000001 );   					// Load CSS at front-end side  // Enqueue Scripts to All Client pages
                add_action( 'wp_enqueue_scripts', array(self::$instance->js,  'load'), 1000000001 );   					// Load JavaScript files and define JS varibales at forn-end side
                add_action( 'wp_footer', 'wpbc_print_js', 50 );                 			// Load my Queued JavaScript Code at  the footer  of the page, if executed "wp_footer" hook at the Theme.
            }            
        }
                
    }
    return self::$instance;        
}


/** Define Admin Menu items */
public function define_admin_menu(){

    $update_count = wpbc_db_get_number_new_bookings();
	//FixIn: 9.1.3.3
    $title = 'WPBC ';//__('Booking', 'booking'); //'&#223;<span style="font-size:0.75em;">&#920;&#920;</span>&kgreen;&imath;&eng;';   // __('Booking', 'booking')
    $is_user_activated = apply_bk_filter('multiuser_is_current_user_active',  true );           //FixIn: 6.0.1.17

    if ( ( $update_count > 0 ) && ( $is_user_activated ) ) {
        $update_count_title = "<span class='update-plugins count-$update_count' title=''><span class='update-count bk-update-count'>" . number_format_i18n($update_count) . "</span></span>" ;
        $title .= $update_count_title;
    }

	$title = '<div class="name_container" style="display: flex;flex-flow: row nowrap;justify-content: flex-start;align-items: center;min-height: 16px;">'
	         . '	<div class="name_item" style="font-size: 6px;font-weight: 600;margin-left: -4px;margin-top: 12px;margin-right: 0px;word-wrap: normal;white-space: nowrap;position: absolute;">WP</div>'
	         . '    <div class="name_item" style="font-size: 11px;margin-left: -5px;margin-top: -7px;white-space: nowrap;margin-right: 4px;">Booking Calendar</div>';
	if ( ( $update_count > 0 ) && ( $is_user_activated ) ) {
		$badge_style= '';
	} else {
		$badge_style = 'display:none;';
	}

	$title .=   '<span style="margin-top:-2px;'.$badge_style.'" class="wpbc_badge_count name_item update-plugins count-'.$update_count.'" title=""><span class="update-count bk-update-count" style="white-space: nowrap;word-wrap: normal;">' . number_format_i18n( $update_count ) . '</span></span>';
	$title .= '</div>';

	/**
		global $menu;
		if ( current_user_can(  ) ) {
			$menu[] = array( '', 'read', 'separator-wpbc', '', 'wp-menu-separator wpbc' );
		}
		debuge($menu);
	*/

    $booking_menu_position = get_bk_option( 'booking_menu_position' );
    switch ( $booking_menu_position ) {
        case 'top':
            $booking_menu_position = '3.3';										//FixIn: 8.7.7.16
            break;
        case 'middle':
            global $_wp_last_object_menu;                                       // The index of the last top-level menu in the object menu group
            $_wp_last_object_menu++;
            $booking_menu_position = $_wp_last_object_menu; 					// 58.9;
            break;
        case 'bottom':
            $booking_menu_position = '99.999';
            break;
        default:
            $booking_menu_position = '3.3';
            break;
    }

	//FixIn: 9.0.1.7		//scale image by  this params: viewBox="-2 -1 20 20"
	if( 0 ) {
			// calendar4-range		https://icons.getbootstrap.com/icons/calendar4-range/
			$svg_icon_integarted = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="" viewBox="-2 -1 20 20">'
									  . '<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>'
									  . '<path d="M9 7.5a.5.5 0 0 1 .5-.5H15v2H9.5a.5.5 0 0 1-.5-.5v-1zm-2 3v1a.5.5 0 0 1-.5.5H1v-2h5.5a.5.5 0 0 1 .5.5z"/>'
									. '</svg>';

			//calendar-range		https://icons.getbootstrap.com/icons/calendar-range/
			$svg_icon_integarted = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-range" viewBox="-2 -1 20 20">'
									  . '<path d="M9 7a1 1 0 0 1 1-1h5v2h-5a1 1 0 0 1-1-1zM1 9h4a1 1 0 0 1 0 2H1V9z"/>'
									  . '<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>'
									. '</svg>';

			//calendar2-range		https://icons.getbootstrap.com/icons/calendar2-range/
			$svg_icon_integarted = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar2-range" viewBox="-2 -1 20 20">'
									  . '<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z"/>'
									  . '<path d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4zM9 8a1 1 0 0 1 1-1h5v2h-5a1 1 0 0 1-1-1zm-8 2h4a1 1 0 1 1 0 2H1v-2z"/>'
									. '</svg>';
	}
	// calendar3-range		https://icons.getbootstrap.com/icons/calendar3-range/
	$svg_icon_integarted = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3-range" viewBox="-2 -1 20 20">'
							  . '<path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>'
							  . '<path d="M7 10a1 1 0 0 0 0-2H1v2h6zm2-3h6V5H9a1 1 0 0 0 0 2z"/>'
							. '</svg>';

    self::$instance->admin_menu['master'] = new WPBC_Admin_Menus(
                                                    'wpbc' , array (
                                                    'in_menu' => 'root'
												//, 'mune_icon_url' => '/assets/img/icon-16x16.png'
												  , 'mune_icon_url' => $svg_icon_integarted								//FixIn: 9.0.1.7
                                                  , 'menu_title' => $title
                                                  , 'menu_title_second' => __('Bookings', 'booking')
                                                  , 'page_header' => __('Bookings Listing','booking')
                                                  , 'browser_header' =>  __('Bookings Listing', 'booking') . ' - ' . __('Booking Calendar', 'booking')
                                                  , 'user_role' => get_bk_option( 'booking_user_role_booking' )
                                                  , 'position' => $booking_menu_position // 3.3 - top           //( 58.9 )  // - middle
                                                                                /*
                                                                                (Optional). Positions for Core Menu Items
                                                                                    2 Dashboard
                                                                                    4 Separator
                                                                                    5 Posts
                                                                                    10 Media
                                                                                    15 Links
                                                                                    20 Pages
                                                                                    25 Comments
                                                                                    59 Separator
                                                                                    60 Appearance
                                                                                    65 Plugins
                                                                                    70 Users
                                                                                    75 Tools
                                                                                    80 Settings
                                                                                    99 Separator
                                                                                     */
                                                                            )
                                                );


    self::$instance->admin_menu['new']    = new WPBC_Admin_Menus(
                                                    'wpbc-new' , array (
                                                    'in_menu' => 'wpbc'
                                                  , 'menu_title'    => ucwords( __('Add booking', 'booking') )
                                                  , 'page_header'   => ucwords( __('Add booking','booking') )
                                                  , 'browser_header'=> ucwords( __('Add booking', 'booking') ) . ' - ' . __('Booking Calendar', 'booking')
                                                  , 'user_role' => get_bk_option( 'booking_user_role_addbooking' )
                                                                            )
                                                );
    self::$instance->admin_menu['availability']    = new WPBC_Admin_Menus(												//FixIn: 9.3.0.1
                                                    'wpbc-availability' , array (
                                                    'in_menu' => 'wpbc'
                                                  , 'menu_title'    => ucwords( __('Availability', 'booking') )
                                                  , 'page_header'   => ucwords( __('Availability','booking') )
                                                  , 'browser_header'=> ucwords( __('Availability', 'booking') ) . ' - ' . __('Booking Calendar', 'booking')
                                                  , 'user_role' => get_bk_option( 'booking_user_role_availability' )	//FixIn: 9.5.2.2
                                                                            )
                                                );
	//FixIn: 9.8.15.2.4
    if ( class_exists( 'wpdev_bk_biz_m' ) )
        self::$instance->admin_menu['prices']    = new WPBC_Admin_Menus(
                                                    'wpbc-prices' , array (
                                                    'in_menu' => 'wpbc'
                                                  , 'menu_title'    => __('Prices', 'booking')
                                                  , 'page_header'   => ucwords( __('Booking Prices','booking') )
                                                  , 'browser_header'=> __('Prices', 'booking') . ' - ' . __('Booking Calendar', 'booking')
                                                  , 'user_role' => get_bk_option( 'booking_user_role_prices' )			//FixIn: 9.8.15.2.6
                                                                            )
                                                );
    if ( class_exists( 'wpdev_bk_personal' ) ) {
        self::$instance->admin_menu['resources']    = new WPBC_Admin_Menus(
                                                    'wpbc-resources' , array (
                                                    'in_menu' => 'wpbc'
                                                  , 'menu_title'    => __('Resources', 'booking')
                                                  , 'page_header'   => ucwords( __('Booking resources','booking') )
                                                  , 'browser_header'=> __('Resources', 'booking') . ' - ' . __('Booking Calendar', 'booking')
                                                  , 'user_role' => get_bk_option( 'booking_user_role_resources' )
                                                                            )
                                                );
	} else {
	    //FixIn: 9.8.15.7
        self::$instance->admin_menu['resources']    = new WPBC_Admin_Menus(
                                                    'wpbc-resources' , array (
                                                    'in_menu' => 'wpbc'
                                                  , 'menu_title'    => __('Resource', 'booking')
                                                  , 'page_header'   => ucwords( __('Booking resource','booking') )
                                                  , 'browser_header'=> __('Resource', 'booking') . ' - ' . __('Booking Calendar', 'booking')
                                                  , 'user_role' => get_bk_option( 'booking_user_role_settings' )
                                                                            )
                                                );
	}

	if ( WPBC_customize_plugin )
    self::$instance->admin_menu['customize_plugin']    = new WPBC_Admin_Menus(												//FixIn: 9.8.0.1
                                                    'wpbc-customize_plugin' , array (
                                                    'in_menu' => 'wpbc'
                                                  , 'menu_title'    => ucwords( __('Customize', 'booking') )
                                                  , 'page_header'   => ucwords( __('Customize','booking') )
                                                  , 'browser_header'=> ucwords( __('Customize', 'booking') ) . ' - ' . __('Booking Calendar', 'booking')
                                                  , 'user_role' => get_bk_option( 'booking_user_role_customize_plugin' )				//FixIn: 9.8.15.2.6
                                                                            )
                                                );

    self::$instance->admin_menu['settings'] = new WPBC_Admin_Menus(
                                                    'wpbc-settings' , array (
                                                    'in_menu' => 'wpbc'
                                                  , 'menu_title'    => __('Settings', 'booking')
                                                  , 'page_header'   => __('General Settings','booking')
                                                  , 'browser_header'=> __('Settings', 'booking') . ' - ' . __('Booking Calendar', 'booking')
                                                  , 'user_role' => get_bk_option( 'booking_user_role_settings' )
                                                                            )
                                                );



	if ( class_exists( 'wpdev_bk_multiuser' ) ) {

		// Is current user suer booking admin  and if this user was simulated log in
		$real_current_user_id = get_current_user_id();
		$is_user_super_admin = apply_bk_filter( 'is_user_super_admin', $real_current_user_id );

		if (  $is_user_super_admin ) {

			// Is user was simulated log in
			$simulate_user_id = intval( get_option( 'booking_simulate_login_as_user' ) );

			if ( ( ! empty( $simulate_user_id ) ) && ( $simulate_user_id > 0 ) ) {

				$custom_user = get_userdata( $simulate_user_id );

				self::$instance->admin_menu['log_off'] = new WPBC_Admin_Menus(
													'wpbc-log-off' , array (
														  'in_menu' 	  => 'wpbc'
														//, 'menu_title' => __( 'Log Out Simulated Login as', 'booking' ) . ' "' . $custom_user->display_name . '"'
														, 'menu_title' => '<span style="color:#e7dd8c;" title="'
																		  		. esc_attr( __( 'Log out as regular user and login as super booking admin user', 'booking' ) )
																		  .'">'
																		  .__( 'Back to Super Admin', 'booking' )
																		  .'<span>'
														, 'page_header'   => ucwords( sprintf( __( 'Need even more functionality? Check %s higher versions %s','booking'), '', '' ) )
														, 'browser_header'=> 'Log In as Super Admin'
														, 'user_role' 	  => get_bk_option( 'booking_user_role_booking' )
													)
												);
			}
		}
	}



	//FixIn: 8.0.1.6
	if ( ! class_exists( 'wpdev_bk_personal' ) ) {

		$is_show_this_menu = get_bk_option('booking_menu_go_pro');

		if ( 'hide' !== $is_show_this_menu )
			self::$instance->admin_menu['go_pro'] = new WPBC_Admin_Menus(
													'wpbc-go-pro' , array (
														  'in_menu' 	  => 'wpbc'
														, 'menu_title'    => __('Get Pro', 'booking')
														, 'page_header'   => ucwords( sprintf( __( 'Need even more functionality? Check %s higher versions %s','booking'), '', '' ) )
														, 'browser_header'=> 'Booking Calendar'
														, 'user_role' 	  => get_bk_option( 'booking_user_role_booking' )
													)
												);
	}

}

    
    
    /** 
	 * Get Menu Object
     * 
     * @param type  - menu type
     * @return boolean
     */
    public function get_menu_object( $type ) {

        if ( isset( self::$instance->admin_menu[ $type ] ) )
            return self::$instance->admin_menu[ $type ];
        else 
            return false;
    }


    // Define constants
    private function constants() {
        require_once WPBC_PLUGIN_DIR . '/core/wpbc-constants.php' ; 
    }
    
    
    // Include Files
    private function includes() {
        require_once WPBC_PLUGIN_DIR . '/core/wpbc-include.php' ; 
    }
    
        
    private function define_version() {
        
        // GET VERSION NUMBER
        $plugin_data = wpbc_file__read_header_info(  WPBC_FILE , array( 'Name' => 'Plugin Name', 'PluginURI' => 'Plugin URI', 'Version' => 'Version', 'Description' => 'Description', 'Author' => 'Author', 'AuthorURI' => 'Author URI', 'TextDomain' => 'Text Domain', 'DomainPath' => 'Domain Path' ) , 'plugin' );
        if (!defined('WPDEV_BK_VERSION'))    define('WPDEV_BK_VERSION',   $plugin_data['Version'] );
    }


    // Cloning instances of the class is forbidden
    public function __clone() {

        _doing_it_wrong( __FUNCTION__, __( 'Action is not allowed!' ), '1.0' );
    }

    
    // Unserializing instances of the class is forbidden
    public function __wakeup() {

        _doing_it_wrong( __FUNCTION__, __( 'Action is not allowed!' ), '1.0' );
    }

    
    // Initialization
    private function start(){
        
        if (  ( defined( 'DOING_AJAX' ) )  && ( DOING_AJAX )  ){                        // New A J A X    R e s p o n d e r

            if ( class_exists('wpdev_bk_personal')) { $wpdev_bk_personal_in_ajax = new wpdev_bk_personal(); }            
            require_once WPBC_PLUGIN_DIR . '/core/lib/wpbc-ajax.php';                        // Ajax 
            
            return false;
        } else {                                                                        // Usual Loading of plugin

            // We are having Response, its executed in other file: wpbc-response.php
            if ( WP_BK_RESPONSE )
                return false;

            if( is_admin() ) {
                // Define Notices System
                self::$instance->notice = new WPBC_Notices();
            }
            
            // Normal Start
            self::$instance->booking_obj = new wpdev_booking();                                    // GO
            
            // Cron Jobs ..... /////////////////////////////////////////////////
            self::$instance->cron = new WPBC_Cron();
            ////////////////////////////////////////////////////////////////////
        }
        return true;
    }
    
}

else:   // Its seems that  some instance of Booking Calendar still activted!!!
    
    //FixIn: 7.0.1.6
    global $wp_version;                                                     
    $min_wp_version = version_compare( WP_BK_MIN_WP_VERSION, $wp_version, '<=' );

    //FixIn: 7.0.1.6
    function wpbc_show_min_wp_version_error() {

        $message_type = 'error';
        $title        = __( 'Error' , 'booking') . '!';
        
        
        $message = 'Booking Calendar ';
        
        $booking_version_num = get_option( 'booking_version_num');        
        if ( ! empty( $booking_version_num ) )
            $message .= '<strong>' . $booking_version_num . '</strong> '; 
        
        
        global $wp_version; 

        $message .= sprintf(  'require minimum %s . You are using %s. ' 
                                                        , ' <strong>' . 'WordPress ' . WP_BK_MIN_WP_VERSION . '</strong>'  
                                                        , ' <strong>' . 'WordPress ' . $wp_version . '</strong>' );                
        if ( current_user_can( 'update_core' ) ){
            $message .= ' <a href="' . esc_url( self_admin_url( 'update-core.php' ) ) . '">' .  'Return to Dashboard &rarr; Updates'  . '</a>';
        }				
			
                
        $message_content = '';

        $message_content .= '<div class="clear"></div>';

        $message_content .= '<div class="updated wpbc-settings-notice notice-' . $message_type . ' ' . $message_type . '" style="text-align:left;padding:10px;">';

        if ( ! empty( $title ) )
        $message_content .=  '<strong>' . esc_js( $title ) . '</strong> ';

        $message_content .= html_entity_decode( esc_js( $message ) ,ENT_QUOTES) ;

        $message_content .= '</div>';

        $message_content .= '<div class="clear"></div>';
        
        echo $message_content;
    }    
    
    function wpbc_show_activation_error() {

        $message_type = 'error';
        $title        = __( 'Error' , 'booking') . '!';
        $message      = 'Please deactivate previous old version of'  . ' ' . 'Booking Calendar';
        
        $booking_version_num = get_option( 'booking_version_num');        
        if ( ! empty( $booking_version_num ) )
            $message .= ' <strong>' . $booking_version_num . '</strong>'; 
        
        
        $is_delete_if_deactive =  get_bk_option( 'booking_is_delete_if_deactive' ); // check

        if ( $is_delete_if_deactive == 'On' ) { 
            
            $message .= '<br/><br/> <strong>Warning!</strong> ' . 'All plugin data will be deleted when plugin had deactivated.' . ' '
                . sprintf( 'If you want to save your plugin data, please uncheck the %s"Delete plugin data"%s at the', '<strong>', '</strong>') . ' ' . __( 'Settings' , 'booking' ) . '.';
        }
        
        $message_content = '';

        $message_content .= '<div class="clear"></div>';

        $message_content .= '<div class="updated wpbc-settings-notice notice-' . $message_type . ' ' . $message_type . '" style="text-align:left;padding:10px;">';

        if ( ! empty( $title ) )
        $message_content .=  '<strong>' . esc_js( $title ) . '</strong> ';

        $message_content .= html_entity_decode( esc_js( $message ) ,ENT_QUOTES) ;

        $message_content .= '</div>';

        $message_content .= '<div class="clear"></div>';
        
        echo $message_content;
    }    
    
    
    if ( ! $min_wp_version )                                                    //FixIn: 7.0.1.6
        add_action('admin_notices', 'wpbc_show_min_wp_version_error');    
    else 
        add_action('admin_notices', 'wpbc_show_activation_error');    
    
    return;         // Exit

endif;


/**
 * The main function responsible for returning the one true Instance to functions everywhere.
 *
 * Example: <?php $wpbc = WPBC(); ?>
 */
function WPBC() {
    return Booking_Calendar::init();
}



// Start
WPBC();



//if (  ! defined( 'SAVEQUERIES') ) define('SAVEQUERIES', true);

 //add_action( 'admin_footer', 'show_debug_info', 130 ); 
function show_debug_info() {
    
    $request_uri = $_SERVER['REQUEST_URI'];                                 //FixIn:5.4.1
    if ( strpos( $request_uri, 'page=wpbc') === false ) {
        return;
    }
    echo '<div style="width:800px;margin:10px auto;"><style type="text/css"> a:link{background: inherit !important; } pre { white-space: pre-wrap; }</style>'; 
    
phpinfo();  echo '</div>'; return;
    
    ?><div style="width:auto;margin:0 0 0 215px;font-size:11px;    "><?php 

// SYSTEM  INFO SHOWING ////////////////////////////////////////////////////////
    
    //Note firstly  need to  define this in functions.php file:   define('SAVEQUERIES', true);
    global $wpdb;
    echo '<div class="clear"></div>START SYSTEM<pre>';
        $qq_kk = 0;
        $total_time = 0;
        $total_num = 0;
        foreach ( $wpdb->queries as $qq_k => $qq ) {
            if ( 
                       ( strpos( $qq[0], 'booking') !== false ) 

                ) {
                if ( $qq[1] > 0.002 ) { echo '<div style="color:#A77;font-weight:bold;">'; }
                debuge($qq_kk++, $qq);
                $total_time += $qq[1];
                $total_num++;
                if ( $qq[1] > 0.002 ) { echo '</div>'; }
            }
        }

        echo '<div><pre class="prettyprint linenums" style="font-size:18px;">[' . $total_num . '/' . $total_time . '] WPBC Requests TOTAL TIME</pre></div>';
    
        echo '<div class="clear"></div>'; 

        echo '<div><pre class="prettyprint linenums" style="font-size:18px;">' . get_num_queries(). '/'  . timer_stop(0, 3) . 'qps</pre></div>';
        
        echo '<div class="clear"></div>'; 
            
    echo "</pre>";
    ?><br/><br/><br/><br/><br/><br/><?php
    echo '<div class="clear"></div>'; 

////////////////////////////////////////////////////////////////////////////////
    ?></div><?php
    
    echo '</div>';
}