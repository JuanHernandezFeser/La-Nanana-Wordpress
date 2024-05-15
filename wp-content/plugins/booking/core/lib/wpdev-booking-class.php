<?php
if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

class wpdev_booking {

    public $popover_front_end_js_is_writed;		//FixIn: Flex TimeLine 1.0		-- previos this was private and not public property
    
    // <editor-fold defaultstate="collapsed" desc="  C O N S T R U C T O R  &  P r o p e r t i e s ">

    var $wpdev_bk_personal;

    function __construct() {

		// In AJAX request it DO NOT RUNNING.   Important note

	    $this->popover_front_end_js_is_writed = false;

	    if ( class_exists( 'wpdev_bk_personal' ) ) {
		    $this->wpdev_bk_personal = new wpdev_bk_personal();
	    } else {
		    $this->wpdev_bk_personal = false;
	    }


	    // User defined - hooks
	    add_action( 'wpdev_bk_add_calendar', array( &$this, 'add_calendar_action' ), 10, 2 );
	    add_action( 'wpdev_bk_add_form', array( &$this, 'add_booking_form_action' ), 10, 2 );
	    add_bk_action( 'wpdevbk_add_form', array( &$this, 'add_booking_form_action' ) );
	    add_filter( 'wpdev_bk_get_form', array( &$this, 'get_booking_form_action' ), 10, 2 );
	    add_bk_filter( 'wpdevbk_get_booking_form', array( &$this, 'get_booking_form_action' ) );
	    add_filter( 'wpdev_bk_get_showing_date_format', array( &$this, 'get_showing_date_format' ), 10, 1 );

	    // Get script for calendar activation
	    add_bk_filter( 'pre_get_calendar_html', array( &$this, 'pre_get_calendar_html' ) );

	    add_action( 'init', array( $this, 'wpbc_shortcodes_init' ), 9999 );              // <- priority  to  load it last
    }
    // </editor-fold>


	/**
	 *  S H O R T C O D E s      Init
	 *
	 * @return void
	 */
	function wpbc_shortcodes_init(){

	    add_shortcode( 'booking',               array( &$this, 'booking_shortcode' ) );
	    add_shortcode( 'bookingcalendar',       array( &$this, 'booking_calendar_only_shortcode' ) );
	    add_shortcode( 'bookingform',           array( &$this, 'bookingform_shortcode' ) );
	    add_shortcode( 'bookingedit',           array( &$this, 'bookingedit_shortcode' ) );
	    add_shortcode( 'bookingsearch',         array( &$this, 'bookingsearch_shortcode' ) );
	    add_shortcode( 'bookingsearchresults',  array( &$this, 'bookingsearchresults_shortcode' ) );
	    add_shortcode( 'bookingselect',         array( &$this, 'bookingselect_shortcode' ) );
	    add_shortcode( 'bookingresource',       array( &$this, 'bookingresource_shortcode' ) );
	    add_shortcode( 'bookingtimeline',       array( &$this, 'bookingtimeline_shortcode' ) );
	    add_shortcode( 'bookingcustomerlisting', array( &$this, 'bookingcustomerlisting_shortcode' ) );					//FixIn: 8.1.3.5
	    add_shortcode( 'booking_test_speed',    array( &$this, 'booking_test_speed_shortcode' ) );                      //FixIn: 8.7.11.13
	}
	
	
    // <editor-fold defaultstate="collapsed" desc="   S U P P O R T     F U N C T I O N S     ">

	    function silent_deactivate_WPBC() {
	        deactivate_plugins( WPBC_PLUGIN_DIRNAME . '/' . WPBC_PLUGIN_FILENAME, true );
	    }


	    // Change date format
		function get_showing_date_format( $mydate ) {
			$date_format = get_bk_option( 'booking_date_format' );
			if ( $date_format == '' ) {
				$date_format = "d.m.Y";
			}

			$time_format = get_bk_option( 'booking_time_format' );
			if ( $time_format !== false ) {
				$time_format = ' ' . $time_format;
				$my_time     = date( 'H:i:s', $mydate );
				if ( $my_time == '00:00:00' ) {
					$time_format = '';
				}
			} else {
				$time_format = '';
			}

			// return date($date_format . $time_format , $mydate);
			return date_i18n( $date_format, $mydate ) . '<sup class="booking-table-time">' . date_i18n( $time_format, $mydate ) . '</sup>';
		}

    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="   B O O K I N G s       A D M I N       F U N C T I O N s   ">

		/**
		 * Generate booking CAPTCHA fields  for booking form
		 *
		 * @param $bk_tp    resource ID
		 *
		 * @return string|true
		 */
	    function createCapthaContent($bk_tp) {

		    $admin_uri = ltrim( str_replace( get_site_url( null, '', 'admin' ), '', admin_url( 'admin.php?' ) ), '/' );
		    if ( ( get_bk_option( 'booking_is_use_captcha' ) !== 'On' )
		         || ( strpos( $_SERVER['REQUEST_URI'], $admin_uri ) !== false )
		    ) {
			    return '';
		    } else {

			    $html = wpbc_captcha__simple__generate_html_content( $bk_tp );

			    return $html;
		    }
	    }

	    // Get default Booking resource
	    function get_default_type() {

		    if ( $this->wpdev_bk_personal !== false ) {
			    if ( ( isset( $_GET['booking_type'] ) ) && ( $_GET['booking_type'] != '' ) ) {
				    $bk_type = $_GET['booking_type'];
			    } else {
				    $bk_type = $this->wpdev_bk_personal->get_default_booking_resource_id();
			    }
		    } else {
			    $bk_type = 1;
		    }

		    return $bk_type;
	    }

    // </editor-fold>
    

    // <editor-fold defaultstate="collapsed" desc="   C L I E N T   S I D E     &    H O O K S ">


    // Get HTML for the initilizing inline calendars
    function pre_get_calendar_html( $resource_id=1, $cal_count=1, $bk_otions=array() ){
        //SHORTCODE:
        /*
         * [booking type=56 form_type='standard' nummonths=4 
         *          options='{calendar months_num_in_row=2 width=682px cell_height=48px}']
         */
        
        $bk_otions = wpbc_parse_calendar_options($bk_otions);
        /*  options:
            [months_num_in_row] => 2
            [width] => 341px                define: width: 100%; max-width:341px;
                [strong_width] => 341px     define: width:341px;
            [cell_height] => 48px
         */
        $width = $months_num_in_row = $cell_height = '';
        
        if (!empty($bk_otions)){

	        if ( isset( $bk_otions['months_num_in_row'] ) ) {
		        $months_num_in_row = $bk_otions['months_num_in_row'];
	        }

	        if ( isset( $bk_otions['width'] ) ) {
		        $width = 'width:100%;max-width:' . $bk_otions['width'] . ';';                                           //FixIn: 9.3.1.5
	        }
	        if ( isset( $bk_otions['strong_width'] ) ) {
		        $width .= 'width:' . $bk_otions['strong_width'] . ';';                                                  //FixIn: 9.3.1.6
	        }

	        if ( isset( $bk_otions['cell_height'] ) ) {
		        $cell_height = $bk_otions['cell_height'];
	        }
	        if ( isset( $bk_otions['strong_cell_height'] ) ) {                                                          //FixIn: 9.7.3.3
		        $cell_height = $bk_otions['strong_cell_height'] . '!important;';
	        }
        }
        /* FixIn: 9.7.3.4 */

        if (!empty($cell_height))
             $style= '<style type="text/css" rel="stylesheet" >'.
                        '.hasDatepick .datepick-inline .datepick-title-row th,'.
                        '.hasDatepick .datepick-inline .datepick-days-cell{'.
                            ' height: '.$cell_height.'; '.
                        '}'.
                     '</style>';
        else $style= '';

        //FixIn: 8.2.1.27
        $booking_timeslot_day_bg_as_available = get_bk_option( 'booking_timeslot_day_bg_as_available' );

        $booking_timeslot_day_bg_as_available = ( $booking_timeslot_day_bg_as_available === 'On' ) ? ' wpbc_timeslot_day_bg_as_available' : '';

	    //FixIn: 9.8.1
	    $is_custom_width_css = ( empty( $width ) ) ? ' wpbc_no_custom_width ' : '';

        $calendar  = $style.
                     '<div class="bk_calendar_frame' . $is_custom_width_css . ' months_num_in_row_' . $months_num_in_row . ' cal_month_num_' . $cal_count . $booking_timeslot_day_bg_as_available . '" style="' . $width . '">' .
                        '<div id="calendar_booking' . $resource_id . '">' .
                     __('Calendar is loading...' ,'booking').
                        '</div>'.
                     '</div>'.
                     '';
        
        $booking_is_show_powered_by_notice = get_bk_option( 'booking_is_show_powered_by_notice' );
	    if ( ( ! class_exists( 'wpdev_bk_personal' ) ) && ( $booking_is_show_powered_by_notice == 'On' ) ) {
		    $calendar .= '<div style="font-size:7px;text-align:left;margin:0 0 10px;text-shadow: none;">Powered by <a href="https://wpbookingcalendar.com" target="_blank" title="Booking Calendar plugin for WordPress">Booking Calendar</a></div>';
	    }
                
        $calendar .= '<textarea id="date_booking' . $resource_id . '" name="date_booking' . $resource_id . '" autocomplete="off" style="display:none;"></textarea>';   // Calendar code
        
        $calendar .= wpbc_get_calendar_legend();                                                                        //FixIn: 9.4.3.6

        $calendar_css_class_outer = 'wpbc_calendar_wraper';

        //FixIn: 7.0.1.24
        $is_booking_change_over_days_triangles = get_bk_option( 'booking_change_over_days_triangles' );
        if ( $is_booking_change_over_days_triangles !== 'Off' ) {
			$calendar_css_class_outer .= ' wpbc_change_over_triangle';
        }

	    $calendar = '<div class="' . esc_attr( $calendar_css_class_outer ) . '">' . $calendar . '</div>';

        return $calendar;
    }
    

    // Get form
    function get_booking_form( $my_boook_type ) {
        
        $my_form = apply_bk_filter( 'wpbc_get_free_booking_form' , $my_boook_type );

        return  $my_form;
    }

    // Get booking form
	function get_booking_form_action( $resource_id = 1, $my_boook_count = 1, $my_booking_form = 'standard', $my_selected_dates_without_calendar = '', $start_month_calendar = false, $bk_otions = array() ) {

        $res = $this->add_booking_form_action($resource_id,$my_boook_count, 0, $my_booking_form , $my_selected_dates_without_calendar, $start_month_calendar, $bk_otions );
        return $res;
    }

    //Show booking form from action call - wpdev_bk_add_form
	function add_booking_form_action( $resource_id = 1, $cal_count = 1, $is_echo = 1, $my_booking_form = 'standard', $my_selected_dates_without_calendar = '', $start_month_calendar = false, $bk_otions = array() ) {
        
        $additional_bk_types = array();
        if ( strpos($resource_id,';') !== false ) {
            $additional_bk_types = explode(';',$resource_id);
            $resource_id         = $additional_bk_types[0];
        }

		//--------------------------------------------------------------------------------------------------------------
		// Check if booking resource exist
        if ( $resource_id == '') {
            $my_result = __('Booking resource type is not defined. This can be, when at the URL is wrong booking hash.' ,'booking');
            if ( $is_echo )            echo $my_result;
            else                       return $my_result;
            return;
        }

        $is_booking_resource_exist = apply_bk_filter('wpdev_is_booking_resource_exist',true, $resource_id, $is_echo );
        if (! $is_booking_resource_exist) {
            if ( $is_echo )     echo 'Booking resource does not exist.' . ' [ID=' . $resource_id . ']';
            return 'Booking resource does not exist.' . ' [ID=' . $resource_id . ']';
        }

		//--------------------------------------------------------------------------------------------------------------
		// Define   $this->client_side_active_params_of_user  to  specific User,
		//                                                      depends from belonging booking resource to specific User
        make_bk_action('check_multiuser_params_for_client_side', $resource_id );

		//--------------------------------------------------------------------------------------------------------------
		// Get Booking ID and booking resource ID,  if we EDIT booking
	    if ( isset( $_GET['booking_hash'] ) ) {

		    $my_booking_id_type = wpbc_hash__get_booking_id__resource_id( $_GET['booking_hash'] );

			$is_error = false;                                                                                          //FixIn: 9.1.3.2
		    if ( $my_booking_id_type !== false ) {

				list( $my_edited_bk_id, $my_boook_type ) = $my_booking_id_type;

			    if ( $my_boook_type == '' ) {
				    $is_error ='<strong>' . __('Oops!' ,'booking') . '</strong> ' . __('We could not find your booking. The link you used may be incorrect or has expired. If you need assistance, please contact our support team.' ,'booking');
			    }
		    } else {
			    $is_error = '<strong>' . __('Oops!' ,'booking') . '</strong> ' . __('We could not find your booking. The link you used may be incorrect or has expired. If you need assistance, please contact our support team.' ,'booking');
		    }

		    if ( false !== $is_error ) {
			    if ( $is_echo ) {
				    echo $is_error;
			    }
			    return $is_error;
		    }

		    // Check situation when  we have editing "child booking resource",  so  need to  reupdate calendar and form  to have it for parent resource.
		    if (                                                                                                        //FixIn: 6.1.1.9             //Fix:2016-10-12
				   ( ! isset( $_GET['booking_pay'] ) )
				&& ( ! isset( $_GET['resource_no_update'] ) )                                   //FixIn: 9.4.2.3
				&& ( function_exists( 'wpbc_is_this_child_resource' ) )
				&& ( wpbc_is_this_child_resource( $my_boook_type ) )
		    ){
			    $bk_parent_br_id = wpbc_get_parent_resource( $my_boook_type );
			    $resource_id     = $bk_parent_br_id;
		    }
	    }

		//--------------------------------------------------------------------------------------------------------------
		// Get JS vars for      Booked dates | Rates | Availability    &   define function  for showing calendar

		$start_script_code = wpbc__calendar__load( array(
														'resource_id'                     => $resource_id,
														'aggregate_resource_id_arr'       => $additional_bk_types,
														'selected_dates_without_calendar' => $my_selected_dates_without_calendar,
														'calendar_number_of_months'       => $cal_count,
														'start_month_calendar'            => $start_month_calendar,
														'shortcode_options'               => $bk_otions,
														'custom_form'                     => $my_booking_form
													));


		if ( $my_selected_dates_without_calendar !== '' ) {                                                             //FixIn: 9.3.1.1
			// Disable booked time slots, for predefined selected date in the booking form (it's shortcode for booking form,  without the calendar)
			$start_script_code .= "<script type='text/javascript'> jQuery(document).ready( function(){ ";
			$start_script_code .= "    bkDisableBookedTimeSlots( jQuery( '#date_booking{$resource_id}' ).val(), {$resource_id} ); ";
			$start_script_code .= " }); </script>";
		}


		//--------------------------------------------------------------------------------------------------------------
		// Get booking form content
        $my_result =  ' ' . $this->get__client_side_booking_content($resource_id, $my_booking_form, $my_selected_dates_without_calendar, $cal_count, $bk_otions ) . ' ' . $start_script_code ;


        $my_result = apply_filters('wpdev_booking_form', $my_result , $resource_id);        // Add DIV structure, where to show payment form
        

        make_bk_action('finish_check_multiuser_params_for_client_side', $resource_id );

        if ( $is_echo )            echo   $my_result;
        else                       return $my_result;
    }


	/**
	 * For [bookingcalendar ... ] shortcode Only!
	 *
	 * @param $resource_id
	 * @param $cal_count
	 * @param $is_echo
	 * @param $start_month_calendar
	 * @param $bk_otions
	 *
	 * @return string|void
	 */
	function add_calendar_action( $resource_id = 1, $cal_count = 1, $is_echo = 1, $start_month_calendar = false, $bk_otions = array() ) {

        $additional_bk_types = array();
        if ( strpos($resource_id,';') !== false ) {
            $additional_bk_types = explode(';',$resource_id);
            $resource_id         = $additional_bk_types[0];
        }

        make_bk_action('check_multiuser_params_for_client_side', $resource_id );

        if (isset($_GET['booking_hash'])) {
            $my_booking_id_type = wpbc_hash__get_booking_id__resource_id( $_GET['booking_hash'] );
            if ($my_booking_id_type != false)
                if ($my_booking_id_type[1]=='') {
                    $my_result = '<strong>' . __('Oops!' ,'booking') . '</strong> ' . __('We could not find your booking. The link you used may be incorrect or has expired. If you need assistance, please contact our support team.' ,'booking');
                    if ( $is_echo )            echo $my_result;
                    else                       return $my_result;
                    return;
                }
        }


		$start_script_code = wpbc__calendar__load( array(
														'resource_id'                     => $resource_id,
														'aggregate_resource_id_arr'    => $additional_bk_types,
														'selected_dates_without_calendar' => '',
														'calendar_number_of_months'       => $cal_count,
														'start_month_calendar'            => $start_month_calendar,
														'custom_form'                     => 'standard'                 // Because we show only  'AVAILABILITY CALENDAR' without the form,  at all.
													));

        $my_result = '<div style="clear:both;height:10px;"></div>' . $this->pre_get_calendar_html( $resource_id, $cal_count, $bk_otions );

        $my_result .=   ' ' . $start_script_code ;

        $my_result = apply_filters('wpdev_booking_calendar', $my_result , $resource_id);

        
        $booking_form_is_using_bs_css = get_bk_option( 'booking_form_is_using_bs_css' );
        $my_result = '<span ' . (($booking_form_is_using_bs_css == 'On') ? 'class="wpdevelop"' : '') . '>' . $my_result . '</span>';

        make_bk_action('finish_check_multiuser_params_for_client_side', $resource_id );

        if ( $is_echo )            echo $my_result;
        else                       return $my_result;
    }

    // Get content at client side of  C A L E N D A R
	function get__client_side_booking_content( $resource_id = 1, $my_booking_form = 'standard', $my_selected_dates_without_calendar = '', $cal_count = 1, $bk_otions = array() ) {

        $nl = '<div style="clear:both;height:10px;"></div>';                                                            // New line
        if ($my_selected_dates_without_calendar=='') {
			// Get HTML  with  [calendar] shortcode and Styles for calendar. Get legend html.
            $calendar = $this->pre_get_calendar_html( $resource_id, $cal_count, $bk_otions );
        } else {
            $calendar = '<textarea rows="3" cols="50" id="date_booking' . $resource_id . '" name="date_booking' . $resource_id . '"  autocomplete="off" style="display:none;">' . $my_selected_dates_without_calendar . '</textarea>';   // Calendar code
        }

	    //FixIn: 8.2.1.1
        $form = '<a name="bklnk' . $resource_id . '" id="bklnk' . $resource_id . '"></a><div id="booking_form_div' . $resource_id . '" class="booking_form_div">';
        //FixIn:6.0.1.5
        $custom_params = array();
        if (! empty($bk_otions)) {
            $param ='\s*([name|value]+)=[\'"]{1}([^\'"]+)[\'"]{1}\s*'; // Find all possible options
            $pattern_to_search='%\s*{([^\s]+)' . $param . $param .'}\s*[,]?\s*%';
            preg_match_all($pattern_to_search, $bk_otions, $matches, PREG_SET_ORDER);
            //debuge($matches);  
            foreach ( $matches as $matche_value ) {
                if ( $matche_value[1] == 'parameter' ) {
                    $custom_params[ $matche_value[3] ]= $matche_value[5];
                }
            }
        }
        //FixIn:6.0.1.5

		if ( $this->wpdev_bk_personal !== false ) {
			$form .= $this->wpdev_bk_personal->get_booking_form( $resource_id, $my_booking_form, $custom_params );
		} else {
			$form .= $this->get_booking_form( $resource_id );
		}


        // Insert calendar into form
        if ( strpos($form, '[calendar]') !== false )  $form = str_replace('[calendar]', $calendar ,$form);
        else                                          $form = '<div class="booking_form_div">' . $calendar . '</div>' . $nl . $form ;

        // Replace additional calendars like [calendar id=9] to  HTML and JS code
        $form = apply_bk_filter( 'wpdev_check_for_additional_calendars_in_form'
                                                                            , $form
                                                                            , $resource_id
                                                                            , array( 
                                                                                    'booking_form' => $my_booking_form , 
                                                                                    'selected_dates' => $my_selected_dates_without_calendar , 
                                                                                    'cal_count' => $cal_count , 
                                                                                    'otions' => $bk_otions     
                                                                                    )  
                                    );

        if ( strpos($form, '[captcha]') !== false ) {
            $captcha = $this->createCapthaContent($resource_id);
            $form =str_replace('[captcha]', $captcha ,$form);
        }
        
        // Set additional "Check in/out" times, if activated to  use change-over days!
		$form = apply_filters( 'wpbc_booking_form_html__update__append_change_over_times', $form, $resource_id );
        
        
        
        // Add booking type field
        $form      .= '<input id="bk_type' . $resource_id . '" name="bk_type' . $resource_id . '" class="" type="hidden" value="' . $resource_id . '" /></div>';
        $submitting = '<div id="submiting' . $resource_id . '"></div><div class="form_bk_messages" id="form_bk_messages' . $resource_id . '" ></div>';
        
        //Params: $action = -1, $name = "_wpnonce", $referer = true , $echo = true

        $wpbc_nonce = wp_nonce_field('CALCULATE_THE_COST', ( "wpbc_nonceCALCULATE_THE_COST" . $resource_id) ,  true , false );
        
        
        $res = $form . $submitting . $wpbc_nonce;

        $my_random_id = time() * rand(0,1000);
        $my_random_id = 'form_id'. $my_random_id;        
        
        $booking_form_is_using_bs_css = get_bk_option( 'booking_form_is_using_bs_css');
        $booking_form_format_type     = get_bk_option( 'booking_form_format_type');
		$booking_form_theme           = get_bk_option( 'booking_form_theme');

		$return_form = '<div id="' . $my_random_id . '" '
		                 . ' class="wpbc_container wpbc_container_booking_form '
		                    . ( ( wpbc_is_this_demo() == 'On' ) ? 'wpbc_demo_site ' : '' )
		                    . esc_attr( $booking_form_theme ) . ' '
		                    . ( ( $booking_form_is_using_bs_css == 'On' ) ? 'wpdevelop ' : '' )
		               . '" >' .
                         '<form  id="booking_form' . $resource_id . '"   class="booking_form ' . $booking_form_format_type . '" method="post" action="">' .
                       '<div id="ajax_respond_insert' . $resource_id . '" class="ajax_respond_insert" style="display:none;"></div>' .
                       $res.
                         '</form></div>';
        
        $return_form .= '<div id="booking_form_garbage' . $resource_id . '" class="booking_form_garbage"></div>';
        
        if ($my_selected_dates_without_calendar == '' ) {
            // Check according already shown Booking Calendar  and set do not visible of it
            $return_form .= '<script type="text/javascript">
                                jQuery(document).ready( function(){
                                    jQuery(".widget_wpdev_booking .booking_form.form-horizontal").removeClass("form-horizontal");
                                    var visible_booking_id_on_page_num = visible_booking_id_on_page.length;
                                    if (visible_booking_id_on_page_num !== null ) {
                                        for (var i=0;i< visible_booking_id_on_page_num ;i++){
                                          if ( visible_booking_id_on_page[i]=="booking_form_div' . $resource_id . '" ) {
                                              document.getElementById("'.$my_random_id.'").innerHTML = "<span style=\'color:#A00;font-size:10px;\'>'.                                                      
                                                       sprintf( esc_js( __('%sWarning! Booking calendar for this booking resource are already at the page, please check more about this issue at %sthis page%s' ,'booking') )
                                                                , ''
                                                                , ''
                                                                , ': https://wpbookingcalendar.com/faq/why-the-booking-calendar-widget-not-show-on-page/'                                                            
                                                        ) 
                                                .'</span>";                                                                                                  
                                              jQuery("#'.$my_random_id.'").animate( {opacity: 1}, 10000 ).fadeOut(5000);
                                              return;
                                          }
                                        }
                                        visible_booking_id_on_page[ visible_booking_id_on_page_num ]="booking_form_div' . $resource_id . '";
                                    }
                                });
                            </script>';
        } else {
            if (1)                                                              //FixIn:6.1.1.16	//FixIn: 8.2.1.13
            $return_form .= '<script type="text/javascript">
                                jQuery(document).ready( function(){            
                                    if(typeof( showCostHintInsideBkForm ) == "function") {
                                        showCostHintInsideBkForm(' . $resource_id . ');
                                    }
                                });
                            </script>';
        }

        $is_use_auto_fill_for_logged = get_bk_option( 'booking_is_use_autofill_4_logged_user' ) ;


        if (! isset($_GET['booking_hash']))
            if ($is_use_auto_fill_for_logged == 'On') {

                $curr_user = wpbc_get_current_user();
                if ( $curr_user->ID > 0 ) {
//$user_nick_name =get_user_meta($curr_user->ID, 'nickname')[0];
//debuge( $user_nick_name );
	                //FixIn: 8.7.1.5
					$user_nick_name = get_user_meta( $curr_user->ID, 'nickname' );
					if ( empty( $user_nick_name ) ) {
						$user_nick_name = '';
					} else {
						$user_nick_name = $user_nick_name[0];
					}
                    $return_form .= '<script type="text/javascript">
                                jQuery(document).ready( function(){
                                    var bk_af_submit_form = document.getElementById( "booking_form' . $resource_id . '" );
                                    var bk_af_count = bk_af_submit_form.elements.length;
                                    var bk_af_element;
                                    var bk_af_reg;
                                    for (var bk_af_i=0; bk_af_i<bk_af_count; bk_af_i++)   {
                                        bk_af_element = bk_af_submit_form.elements[bk_af_i];
                                        //FixIn: 9.4.3.4
                                        if    ( bk_af_element.type == "text" ) 
                                            if    ( bk_af_element.name !== ("date_booking' . $resource_id . '" ) )
                                            {
                                                // NickName	//FixIn: 8.6.1.2
                                                bk_af_reg = /^([A-Za-z0-9_\-\.])*(nickname){1}([A-Za-z0-9_\-\.])*$/;
                                                if(bk_af_reg.test(bk_af_element.name) != false)
                                                    if (bk_af_element.value == "" )
                                                        bk_af_element.value  = "' . str_replace( "'", '',  $user_nick_name ) . '";                                                        
                                                // Second Name
                                                bk_af_reg = /^([A-Za-z0-9_\-\.])*(last|second){1}([_\-\.])?name([A-Za-z0-9_\-\.])*$/;
                                                if(bk_af_reg.test(bk_af_element.name) != false)
                                                    if (bk_af_element.value == "" )
                                                        bk_af_element.value  = "'.str_replace("'",'',$curr_user->last_name).'";
                                                // First Name
                                                bk_af_reg = /^name([0-9_\-\.])*$/;
                                                if(bk_af_reg.test(bk_af_element.name) != false)
                                                    if (bk_af_element.value == "" )
                                                        bk_af_element.value  = "'.str_replace("'",'',$curr_user->first_name).'";
                                                bk_af_reg = /^([A-Za-z0-9_\-\.])*(first|my){1}([_\-\.])?name([A-Za-z0-9_\-\.])*$/;
                                                if(bk_af_reg.test(bk_af_element.name) != false)
                                                    if (bk_af_element.value == "" )
                                                        bk_af_element.value  = "'.str_replace("'",'',$curr_user->first_name).'";
                                                // Email
                                                bk_af_reg = /^(e)?([_\-\.])?mail([0-9_\-\.])*$/;
                                                if(bk_af_reg.test(bk_af_element.name) != false)
                                                    if (bk_af_element.value == "" )
                                                        bk_af_element.value  = "'.str_replace("'",'',$curr_user->user_email).'";
												// Phone
					                            bk_af_reg = /^([A-Za-z0-9_\-\.])*(phone|fone){1}([A-Za-z0-9_\-\.])*$/;
					                            if(bk_af_reg.test(bk_af_element.name) != false)
					                                if (bk_af_element.value == "" )
					                                    bk_af_element.value  = "'.str_replace("'",'',$curr_user->phone_number).'";
					                            // NB Enfants
					                            bk_af_reg = /^(e)?([_\-\.])?nb_enfant([0-9_\-\.])*$/;
					                            if(bk_af_reg.test(bk_af_element.name) != false)
					                                if (bk_af_element.value == "" )
					                                    bk_af_element.value  = "'.str_replace("'",'',$curr_user->nb_enfant).'";                                                                                            
                                                // URL
                                                bk_af_reg = /^([A-Za-z0-9_\-\.])*(URL|site|web|WEB){1}([A-Za-z0-9_\-\.])*$/;
                                                if(bk_af_reg.test(bk_af_element.name) != false)
                                                    if (bk_af_element.value == "" )
                                                        bk_af_element.value  = "'.str_replace("'",'',$curr_user->user_url).'";
                                           }
                                    }
                                });
                                </script>';
                }
             }

        return $return_form ;
    }
    // </editor-fold>


    // <editor-fold defaultstate="collapsed" desc="   S H O R T    C O D E S ">

	//FixIn: 8.7.11.13
	function booking_test_speed_shortcode( $attr ) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'booking_test_speed', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

    	 echo '<h4>Booking Calendar Test</h4>';

    	 $datesArray = Array(
	            '2021-09-10'
		     ,  '2021-09-11'
		     ,  '2021-09-12'
		     ,  '2021-09-13'
		     ,  '2021-09-14'
		     ,  '2021-09-15'
		     ,  '2021-09-16'
		     ,  '2021-09-17'
		     ,  '2021-09-18'
		) ;
    	 for( $i = 0; $i < 1 ; $i++) {
$result = wpbc_api_is_dates_booked( $datesArray, $resource_id = 13 );
//debuge((int) $result );
    	 }

    	debuge_speed('(int)');
    	 echo '<hr/>';
    	 echo '<hr/>';
	}

	//FixIn: 8.1.3.5
	/** Listing customners bookings in timeline view
	 *
	 * @param $attr	- The same parameters as for bookingtimeline shortcode (function)
	 *
	 * @return mixed|string|void
	 */
	function bookingcustomerlisting_shortcode( $attr ){

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingcustomerlisting', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

		//FixIn: 8.4.5.11
		if (! is_array($attr)) {
			$attr = array();
		}
		if ( ( isset( $_GET['booking_hash'] ) ) || ( isset( $attr['booking_hash'] ) ) ) {


			if ( isset( $_GET['booking_hash'] ) ) {
				$my_booking_id_type = wpbc_hash__get_booking_id__resource_id( $_GET['booking_hash'] );

				$attr['booking_hash'] = $_GET['booking_hash'];
			} else {
				$my_booking_id_type = wpbc_hash__get_booking_id__resource_id( $attr['booking_hash'] );
			}

			if ( $my_booking_id_type !== false ) {

				if ( ! isset( $attr['type' ] ) ) {																		// 8.1.3.5.2

					$br_list = wpbc_get_all_booking_resources_list();
					$br_list = array_keys( $br_list );
					$br_list = implode(',',$br_list);
					$attr['type' ] = $br_list;		//wpbc_get_default_resource();
				}
				if ( ! isset( $attr['view_days_num' ] ) ) {
					$attr['view_days_num' ] = 30;
				}
				if ( ! isset( $attr['scroll_start_date' ] ) ) {
					$attr['scroll_start_date' ] = '';
				}
				if ( ! isset( $attr['scroll_day' ] ) ) {
					$attr['scroll_day' ] = 0;
				}
				if ( ! isset( $attr['scroll_month' ] ) ) {
					$attr['scroll_month' ] = 0;
				}
				if ( ! isset( $attr['header_title' ] ) ) {
					$attr['header_title' ] = __( 'My bookings' , 'booking');
				}

				$timeline_results = $this->bookingtimeline_shortcode( $attr );

				return $timeline_results ;

			} else {
				return '<strong>' . __('Oops!' ,'booking') . '</strong> ' . __('We could not find your booking. The link you used may be incorrect or has expired. If you need assistance, please contact our support team.' ,'booking');
			}

		} else {
			return __( 'This page can only be accessed through links in emails related to your booking.', 'booking' )
			       . ' <br/><em>'
			       . sprintf( __( 'Please check more about configuration at  %sthis page%s', 'booking' ), '<a href="https://wpbookingcalendar.com/faq/configure-editing-cancel-payment-bookings-for-visitors/" target="_blank">', '</a>.' )
			       . '</em>';
		}
	}


    /**
	 * TimeLine shortcode
     * 
     * @param type $attr
     * @return type
     * 
     * Shortcodes exmaples:
     * 
     * 
** Matrix:
     * 1 Month View Mode:
[bookingtimeline type="3,4,1,5,6,7,8,9,2,10,11,12,14" view_days_num=30 scroll_start_date="" scroll_month=0 header_title='All Bookings']
     * 2 Months View Mode:
[bookingtimeline type="1,5,6,7,8,9,2,10,11,12,3,4,14" view_days_num=60 scroll_start_date="" scroll_month=-1 header_title='All Bookings']
     * 1 Week View Mode:
[bookingtimeline type="3,4" view_days_num=7 scroll_start_date="" scroll_day=-7 header_title='All Bookings']
     * 1 Day View Mode:
[bookingtimeline type="3,4" view_days_num=1 scroll_start_date="" scroll_day=0 header_title='All Bookings']

** Single:
     * 1 Month  View Mode:
[bookingtimeline type="4" view_days_num=30 scroll_start_date="" scroll_day=-15 scroll_month=0 header_title='All Bookings']
     * 3 Months View Mode:
[bookingtimeline type="4" view_days_num=90 scroll_start_date="" scroll_day=-30]
     * 1 Year View Mode:
[bookingtimeline type="4" view_days_num=365 scroll_start_date="" scroll_month=-3]


     */
    function bookingtimeline_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingtimeline', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

    	//FixIn: 8.6.1.13
		$timeline_results = bookingflextimeline_shortcode($attr);
		return $timeline_results;
    }
    
    // Replace MARK at post with content at client side   -----    [booking nummonths='1' type='1']
    function booking_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'booking', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

	    if ( isset( $_GET['booking_hash'] ) ) {
		    return
		        __( 'You need to use special shortcode [bookingedit] for booking editing.', 'booking' )
			    . ' '
		        . sprintf( __( 'Please check FAQ instruction how to configure it here %s', 'booking' )
				           , '<a href="https://wpbookingcalendar.com/faq/configure-editing-cancel-payment-bookings-for-visitors/">https://wpbookingcalendar.com/faq/configure-editing-cancel-payment-bookings-for-visitors/</a>'
		                );
	    }

        //if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  // Init booking resources cache
        
        $my_boook_count = get_bk_option( 'booking_client_cal_count' );
        $my_boook_type = 1;
        $my_booking_form = 'standard';
        $start_month_calendar = false;
        $bk_otions = array();

        if ( isset( $attr['nummonths'] ) ) { $my_boook_count = $attr['nummonths'];  }

	    if ( isset( $attr['resource_id'] ) ) {  $attr['type']  = $attr['resource_id']; }
	    if ( isset( $attr['type'] ) ) {         $my_boook_type = $attr['type']; }

        if ( isset( $attr['form_type'] ) ) { $my_booking_form = $attr['form_type']; }

        if ( isset( $attr['agregate'] )  && (! empty( $attr['agregate'] )) ) {
            $additional_bk_types = $attr['agregate'];
            $my_boook_type .= ';'.$additional_bk_types;
        }
        if ( isset( $attr['aggregate'] )  && (! empty( $attr['aggregate'] )) ) {
            $additional_bk_types = $attr['aggregate'];
            $my_boook_type .= ';'.$additional_bk_types;
        }


        if ( isset( $attr['startmonth'] ) ) { // Set start month of calendar, fomrat: '2011-1'

            $start_month_calendar = explode( '-', $attr['startmonth'] );
            if ( (is_array($start_month_calendar))  && ( count($start_month_calendar) > 1) ) { }
            else $start_month_calendar = false;

        }

        if ( isset( $attr['options'] ) ) { $bk_otions = $attr['options']; }

	    $res = $this->add_booking_form_action( $my_boook_type, $my_boook_count, 0, $my_booking_form, '', $start_month_calendar, $bk_otions );

        return $res;
    }


    // Replace MARK at post with content at client side   -----    [booking nummonths='1' type='1']
    function booking_calendar_only_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingcalendar', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

        //if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  // Init booking resources cache

        $my_boook_count = get_bk_option( 'booking_client_cal_count' );
        $my_boook_type = 1;
        $start_month_calendar = false;
        $bk_otions = array();
        if ( isset( $attr['nummonths'] ) ) { $my_boook_count = $attr['nummonths']; }
		if ( isset( $attr['resource_id'] ) ) {  $attr['type']  = $attr['resource_id']; }
        if ( isset( $attr['type'] ) )         { $my_boook_type = $attr['type'];       }
        if ( isset( $attr['agregate'] )  && (! empty( $attr['agregate'] )) ) {
            $additional_bk_types = $attr['agregate'];
            $my_boook_type .= ';'.$additional_bk_types;
        }
        if ( isset( $attr['aggregate'] )  && (! empty( $attr['aggregate'] )) ) {                                        //FixIn: 8.3.3.8
            $additional_bk_types = $attr['aggregate'];
            $my_boook_type .= ';'.$additional_bk_types;
        }

        if ( isset( $attr['startmonth'] ) ) { // Set start month of calendar, fomrat: '2011-1'
            $start_month_calendar = explode( '-', $attr['startmonth'] );
            if ( (is_array($start_month_calendar))  && ( count($start_month_calendar) > 1) ) { }
            else $start_month_calendar = false;
        }
        
        if ( isset( $attr['options'] ) ) { $bk_otions = $attr['options']; }
        $res = $this->add_calendar_action($my_boook_type,$my_boook_count, 0, $start_month_calendar, $bk_otions  );


        $start_script_code = "<div id='calendar_booking_unselectable".$my_boook_type."'></div>";
	    return "<div class='wpbc_only_calendar wpbc_container'>" . $start_script_code . $res . '</div>';                               //FixIn: 8.0.1.2
    }

    // Show only booking form, with already selected dates
    function bookingform_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingform', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

        //if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  // Init booking resources cache
        
        $my_boook_type = 1;
        $my_booking_form = 'standard';
        $my_boook_count = 1;
        $my_selected_dates_without_calendar = '';

		if ( isset( $attr['resource_id'] ) )   {  $attr['type']  = $attr['resource_id']; }
        if ( isset( $attr['type'] ) )           { $my_boook_type = $attr['type'];                                }
        if ( isset( $attr['form_type'] ) )      { $my_booking_form = $attr['form_type'];                         }
        if ( isset( $attr['selected_dates'] ) ) { $my_selected_dates_without_calendar = $attr['selected_dates']; }  //$my_selected_dates_without_calendar = '20.08.2010, 29.08.2010';

        $res = $this->add_booking_form_action($my_boook_type,$my_boook_count, 0 , $my_booking_form, $my_selected_dates_without_calendar, false );

        return $res;
    }

    // Show booking form for editing
    function bookingedit_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingedit', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1


        //if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  // Init booking resources cache

	    if ( isset( $_GET['wpbc_hash'] ) ) {

	    	if ( function_exists( 'wpbc_parse_one_way_hash' ) ) {

			    $one_way_hash_response = wpbc_parse_one_way_hash( $_GET['wpbc_hash'] );

			    return $one_way_hash_response;
		    }
	    }

        $my_boook_count = get_bk_option( 'booking_client_cal_count' );
        $my_boook_type = 1;
        $my_booking_form = 'standard';
        $bk_otions = array();
        if ( isset( $attr['nummonths'] ) )   { $my_boook_count = $attr['nummonths'];  }
		if ( isset( $attr['resource_id'] ) ) {  $attr['type'] = $attr['resource_id']; }
        if ( isset( $attr['type'] ) )        { $my_boook_type = $attr['type'];        }
        if ( isset( $attr['form_type'] ) )   { $my_booking_form = $attr['form_type']; }
        if ( isset( $attr['agregate'] )  && (! empty( $attr['agregate'] )) ) {  //FixIn:7.0.1.26
            $additional_bk_types = $attr['agregate'];
            $my_boook_type .= ';'.$additional_bk_types;
        }
        if ( isset( $attr['aggregate'] )  && (! empty( $attr['aggregate'] )) ) {
            $additional_bk_types = $attr['aggregate'];
            $my_boook_type .= ';'.$additional_bk_types;
        }
		if ( isset( $attr['options'] ) ) { $bk_otions = $attr['options']; }


        if (isset($_GET['booking_hash'])) {
            $my_booking_id_type = wpbc_hash__get_booking_id__resource_id( $_GET['booking_hash'] );
            if ($my_booking_id_type !== false) {
	            $my_edited_bk_id = $my_booking_id_type[0];
	            $my_boook_type   = $my_booking_id_type[1];
                if ($my_boook_type == '') return '<strong>' . __('Oops!' ,'booking') . '</strong> ' . __('We could not find your booking. The link you used may be incorrect or has expired. If you need assistance, please contact our support team.' ,'booking');
            } else {
                return '<strong>' . __('Oops!' ,'booking') . '</strong> ' . __('We could not find your booking. The link you used may be incorrect or has expired. If you need assistance, please contact our support team.' ,'booking');
            }

        } else {
            return __('This page can only be accessed through links in emails related to your booking.' ,'booking')
                    . ' <br/><em>' 
                        . sprintf( __('Please check more about configuration at  %sthis page%s' ,'booking')
									, '<a href="https://wpbookingcalendar.com/faq/configure-editing-cancel-payment-bookings-for-visitors/" target="_blank">' , '</a>.')
                    . '</em>';
        }


        $res = $this->add_booking_form_action($my_boook_type,$my_boook_count, 0 , $my_booking_form, '', false, $bk_otions );

        if (isset($_GET['booking_pay'])) {
            // Payment form
			if ( 'On' == get_bk_option( 'booking_super_admin_receive_regular_user_payments' ) ){								//FixIn: 9.2.3.8
				make_bk_action('make_force_using_this_user', -999 );      													// '-999' - This ID "by default" is the ID of super booking admin user
			}


			$res = wpbc_do_shortcode__booking_confirm( array(
																'booking_hash' => $_GET['booking_hash']
															) );

			if ( 'On' == get_bk_option( 'booking_super_admin_receive_regular_user_payments' ) ){								//FixIn: 9.2.3.8
				make_bk_action( 'finish_force_using_this_user' );
			}

        }

        return $res;
    }

    // Search form
    function bookingsearch_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingsearch', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1


        //if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  // Init booking resources cache
        
        $search_form = apply_bk_filter('wpdev_get_booking_search_form','', $attr );

        return $search_form ;
    }

    // Search Results form
    function bookingsearchresults_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingsearchresults', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

        //if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  // Init booking resources cache
        
        $search_results = apply_bk_filter('wpdev_get_booking_search_results','', $attr );

        return $search_results ;
    }

    // Select Booking form using the selectbox
    function bookingselect_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingselect', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

        //if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  // Init booking resources cache
        
        $search_form = apply_bk_filter('wpdev_get_booking_select_form','', $attr );

        return $search_form ;
    }

    // Select Booking form using the selectbox
    function bookingresource_shortcode($attr) {

	    if ( wpbc_is_on_edit_page() ) {
		    return wpbc_get_preview_for_shortcode( 'bookingresource', $attr );      //FixIn: 9.9.0.39
	    }

		$attr = wpbc_escape_shortcode_params( $attr );          //FixIn: 9.7.3.6.1

        //if ( function_exists( 'wpbc_br_cache' ) ) $br_cache = wpbc_br_cache();  // Init booking resources cache
        
        $search_form = apply_bk_filter('wpbc_booking_resource_info','', $attr );

        return $search_form ;
    }
    
    
    // </editor-fold>
}