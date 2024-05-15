<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Google Calendar Sync
 * @category Data Sync
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.06.27
 * @since 5.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

// TODO:
    // 1: Auto import
// 1.1 Write description  about the Cron working only  on opening some page.
// 2. Set assignment of form fields between events and bookings
// 3. Test MultiUser conception - During import of all resources - its will require to import only booking resources of the actual  user
// 3.1 Test auto import paramters for multiuser version,  which  paramters,  like maximum number of events is taken  during auto import process... etc...
// 4. Test everything and refactor if required.


// Membership - create WP users from the bookins
// Unlimited number of bookings
// Bookings for specific times
// Show cost of specific date inside of the date(s).

class WPBC_Google_Calendar {

    public $events;

	public $messages_arr;

    private $feed_url;    
    private $booking_gcal_events_from;
    private $booking_gcal_events_until;
    private $booking_gcal_events_max;
    private $booking_gcal_timezone;    
    private $start_of_week;    
    private $error;
    private $bktype;
    private $user_id;
    private $is_silent;
    function __construct() {

	    $this->messages_arr = array();
        $this->error = '';    
        $this->bktype = 1;
        $this->is_silent = false;
		$this->events = array();
        $user = wpbc_get_current_user();
        $this->setUserID( $user->ID );
        
        if ( ! $this->start_of_week = get_option('start_of_week') ) 
               $this->start_of_week = 0;        
    }

    public function show_message( $message ,  $is_spin = false, $is_error = false ) {

		$this->messages_arr[] = $message;

        if ( $this->is_silent )
            return;

      ?><script type="text/javascript">
            var my_message = '<?php echo html_entity_decode( esc_js( $message ),ENT_QUOTES) ; ?>';
            wpbc_admin_show_message( my_message, '<?php echo ( $is_error ? 'error' : 'info' ); ?>', <?php echo ( $is_error ? '60000' : '3000' ); ?> );                                                                      
        </script><?php

        return;        
        //Old: 
        //$message = str_replace("'", '&#039;', $message);
        $message = esc_js($message);
     
        ?>  <script type="text/javascript"> if (jQuery('#ajax_message').length > 0 ) { 
                <?php if ($is_spin ) { ?>        
                    jQuery('#ajax_message').html('<div class="updated ajax_message<?php echo ($is_error?' error':''); ?>" id="ajax_message"><div style="float:left;"><?php echo $message;  ?></div><div class="wpbc_spin_loader"><img style="vertical-align:middle;box-shadow:none;width:14px;" src="'+wpdev_bk_plugin_url+'/assets/img/ajax-loader.gif"></div></div>');
                <?php } else { ?>
                    jQuery('#ajax_message').html('<div class="updated ajax_message<?php echo ($is_error?' error':''); ?>" id="ajax_message"><?php echo $message;  ?></div>'); 
                <?php } ?>
            }</script> <?php
    }
    
    
    public function setSilent() {
        $this->is_silent = true;
    }
    
    public function setUserID($user_id) {
        $this->user_id = $user_id;
    }
    
    public function getUserID() {
        return $this->user_id;
    }
    
    public function setUrl( $relative_url ) {
        $this->feed_url = $relative_url ;
       // $this->feed_url = 'https://www.google.com' . $relative_url ;
    }
    
    public function setResource($param) {
        $this->bktype = $param;
    }
    
    public function getResource() {
        return $this->bktype;
    }
        
    public function getErrorMessage(){
        return $this->error;
    }
    public function set_events_from_with_array( $booking_gcal_events_from ) {   // array( 'from type', 'offset', 'offset type' );
  
        if ($booking_gcal_events_from[0]=='date') {
           $booking_gcal_events_from_offset =  $booking_gcal_events_from[1] ; 
        } else {    
            switch ($booking_gcal_events_from[2]) {
                case "second":  
                    $booking_gcal_events_from_offset = intval( $booking_gcal_events_from[1] );
                    break;
                case "minute":  
                    $booking_gcal_events_from_offset = intval( $booking_gcal_events_from[1] ) * 60 ;                    //FixIn: 9.7.3.15
                    break;
                case "hour":  
                    $booking_gcal_events_from_offset = intval( $booking_gcal_events_from[1] ) * 3600 ;
                    break;
                case "day":  
                    $booking_gcal_events_from_offset = intval( $booking_gcal_events_from[1] ) * 86400 ;
                    break;
                default:
                    $booking_gcal_events_from_offset = intval( $booking_gcal_events_from[1] );
            }   
        }
        $booking_gcal_events_from = $booking_gcal_events_from[0];
        
        $this->set_events_from( $booking_gcal_events_from, $booking_gcal_events_from_offset );
    }
    
    
    public function set_events_from( $booking_gcal_events_from, $offset = 0 ){
        
        switch ( $booking_gcal_events_from ) {
            //Don't just use time() for 'now', as this will effectively make cache duration 1 second. Instead set to previous minute. Events in Google Calendar cannot be set to precision of seconds anyway
            case 'now':
	            $this->booking_gcal_events_from = mktime( intval( date( 'H' ) ), intval( date( 'i' ) ), 0, intval( date( 'm' ) ), intval( date( 'j' ) ), intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'today':
	            $this->booking_gcal_events_from = mktime( 0, 0, 0, intval( date( 'm' ) ), intval( date( 'j' ) ), intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'week':
	            $this->booking_gcal_events_from = mktime( 0, 0, 0, intval( date( 'm' ) ), ( intval( date( 'j' ) ) - intval( date( 'w' ) ) + intval( $this->start_of_week ) ), intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'month-start':
	            $this->booking_gcal_events_from = mktime( 0, 0, 0, intval( date( 'm' ) ), 1, intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'month-end':
	            $this->booking_gcal_events_from = mktime( 0, 0, 0, intval( date( 'm' ) ) + 1, 1, intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'date':
                $offset = explode('-', $offset);
	            $this->booking_gcal_events_from = mktime( 0, 0, 0, intval( $offset[1] ), intval( $offset[2] ), intval( $offset[0] ) );
                break;
            default:
                $this->booking_gcal_events_from =  0 ; //any - 1970-01-01 00:00
        }
    }
    
    
    public function set_events_until_with_array( $booking_gcal_events_until ) {   // array( 'from type', 'offset', 'offset type' );

        if ($booking_gcal_events_until[0]=='date') {
           $booking_gcal_events_until_offset =  $booking_gcal_events_until[1] ; 
        } else {
            switch ($booking_gcal_events_until[2]) {
                case "second":
	                $booking_gcal_events_until_offset = intval( $booking_gcal_events_until[1] );
                    break;
                case "minute":
	                $booking_gcal_events_until_offset = intval( $booking_gcal_events_until[1] ) * 60;                   //FixIn: 9.8.15.1
                    break;
                case "hour":
	                $booking_gcal_events_until_offset = intval( $booking_gcal_events_until[1] ) * 3600;
                    break;
                case "day":
	                $booking_gcal_events_until_offset = intval( $booking_gcal_events_until[1] ) * 86400;
                    break;
                default:
                    $booking_gcal_events_until_offset = intval( $booking_gcal_events_until[1] );
            }    
        }
        $booking_gcal_events_until = $booking_gcal_events_until[0];
        
        $this->set_events_until( $booking_gcal_events_until, $booking_gcal_events_until_offset );
    }
    
    public function set_events_until( $booking_gcal_events_until, $offset = 0 ){
        
        switch ( $booking_gcal_events_until ) {        
            case 'now':
	            $this->booking_gcal_events_until = mktime( intval( date( 'H' ) ), intval( date( 'i' ) ), 0, intval( date( 'm' ) ), intval( date( 'j' ) ), intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'today':
	            $this->booking_gcal_events_until = mktime( 0, 0, 0, intval( date( 'm' ) ), intval( date( 'j' ) ), intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'week':
	            $this->booking_gcal_events_until = mktime( 0, 0, 0, intval( date( 'm' ) ), ( intval( date( 'j' ) ) - intval( date( 'w' ) ) + intval( $this->start_of_week ) ), intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'month-start':
	            $this->booking_gcal_events_until = mktime( 0, 0, 0, intval( date( 'm' ) ), 1, intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'month-end':
	            $this->booking_gcal_events_until = mktime( 0, 0, 0, intval( date( 'm' ) ) + 1, 1, intval( date( 'Y' ) ) ) + $offset;
                break;
            case 'date':
                $offset = explode('-', $offset);
	            $this->booking_gcal_events_until = mktime( 0, 0, 0, intval( $offset[1] ), intval( $offset[2] ), intval( $offset[0] ) );
                break;
            case 'any':
                $this->booking_gcal_events_until = 2145916800; //any - 2038-01-01 00:00
        }
        
    }
    
    public function set_events_max( $booking_gcal_events_max ){
        $this->booking_gcal_events_max = intval( $booking_gcal_events_max );
    }
    
    public function set_timezone( $booking_gcal_timezone ){
        $this->booking_gcal_timezone = $booking_gcal_timezone;
    }


    //Convert an ISO date/time to a UNIX timestamp
    private function iso_to_ts( $iso ) {
        sscanf( $iso, "%u-%u-%uT%u:%u:%uZ", $year, $month, $day, $hour, $minute, $second );
	    $year   = intval((string) $year);        //FixIn: 9.6.3.7
	    $month  = intval((string) $month);
	    $day    = intval((string) $day);
	    $hour   = intval((string) $hour);
	    $minute = intval((string) $minute);
	    $second = intval((string) $second);
        return mktime( $hour, $minute, $second, $month, $day, $year );
    }   
    
    
    // Google Calendar Sync
    function run() {  
        
        // Define some variables  //////////////////////////////////////////////
        $is_send_emeils = 0; // ( ( get_bk_option( 'booking_gcal_is_send_email') == 'On' ) ? 1 : 0 );
        
        $this->events = array();
        
//        $url = $this->feed_url;
//
//        // Break the feed URL up into its parts (scheme, host, path, query)
//        $url_parts = parse_url( $url );
//        
//        if (! isset($url_parts['path']))                                        // Something wrong with  URL
//            return  false;
//
//        $scheme_and_host = $url_parts['scheme'] . '://' . $url_parts['host'];
//
//        // Remove the exisitng projection from the path, and replace it with '/full-noattendees'
//        $path = substr( $url_parts['path'], 0, strrpos( $url_parts['path'], '/' ) ) . '/full-noattendees';
//
//        // Add the default parameters to the querystring (retrieving JSON, not XML)
//        $query = '?alt=json&singleevents=false&sortorder=ascending&orderby=starttime';

        $gmt_offset = get_option( 'gmt_offset' ) * 3600;
        
        
//$this->feed_url = '2bfu44fmv0fu8or1duckjj11mo@group.calendar.google.com';
        
        $url = 'https://www.googleapis.com/calendar/v3/calendars/' . $this->feed_url . '/events';

        // Google API Key    -- public Google API key shared across all plugin users. Currently the shared key is limited to 500,000 requests per day and 5 requests per second.        
        $api_key = get_bk_option( 'booking_gcal_api_key');

        // Set API key
        $url .= '?key=' . $api_key;


        $args['timeMin'] = urlencode( date( 'c', $this->booking_gcal_events_from - $gmt_offset ) );

        $args['timeMax'] = urlencode( date( 'c', $this->booking_gcal_events_until - $gmt_offset ) );

        $args['maxResults'] = $this->booking_gcal_events_max;
        
        $args['singleEvents'] = 'True'; //'False';                              // Each  recurrent event will be showing as separate booking.  Google Description: Whether to expand recurring events into instances and only return single one-off events and instances of recurring events, but not the underlying recurring events themselves. Optional. The default is False. 
       
        if ( ! empty( $this->booking_gcal_timezone ) )
            $args['timeZone'] = $this->booking_gcal_timezone;
        
        $url = add_query_arg( $args, $url );
        

//        //Append the feed specific parameters to the querystring
//        $query .= '&start-min=' . date( 'Y-m-d\TH:i:s', $this->booking_gcal_events_from - $gmt_offset );
//        $query .= '&start-max=' . date( 'Y-m-d\TH:i:s', $this->booking_gcal_events_until - $gmt_offset );
//        $query .= '&max-results=' . $this->booking_gcal_events_max;
//
//        if ( ! empty( $this->booking_gcal_timezone ) )
//                $query .= '&ctz=' . $this->booking_gcal_timezone;
//
//        //If enabled, use experimental 'fields' parameter of Google Data API, so that only necessary data is retrieved. This *significantly* reduces amount of data to retrieve and process
//        // $query .= '&fields=entry(title,link[@rel="alternate"],content,gd:where,gd:when,gCal:uid)';
//
//        $url =  $scheme_and_host . $path . $query;               
       
        $this->show_message( __('Importing Feed' ,'booking') . ': ' . $url , true );
//debuge($url);        
        //Retrieve the feed data
        $raw_data = wp_remote_get( $url, array(
                'sslverify' => false, //sslverify is set to false to ensure https URLs work reliably. Data source is Google's servers, so is trustworthy
                'timeout'   => 10     //Increase timeout from the default 5 seconds to ensure even large feeds are retrieved successfully
        ) );
               
//debuge($raw_data);

        //If $raw_data is a WP_Error, something went wrong
        if ( ! is_wp_error( $raw_data ) ) {
       
            //If response code isn't 200, something went wrong
            if ( 200 == $raw_data['response']['code'] ) {
                
                    $this->show_message( __('Data Parsing' ,'booking') , true );
                    
                    //Attempt to convert the returned JSON into an array
                    $raw_data = json_decode( $raw_data['body'], true );
//debuge($raw_data);
                    //If decoding was successful
                    if ( ! empty( $raw_data ) ) {

                        //If there are some entries (events) to process
                        if ( isset( $raw_data['items'] ) ) {
                            //Loop through each event, extracting the relevant information
                            foreach ( $raw_data['items'] as $event ) {
//debuge($event);
                                $id          = esc_html( $event['id'] );
                                $title       = (isset($event['summary']))?esc_html( $event['summary'] ):'';
                                $description = (isset($event['description']))?esc_html( $event['description'] ):'';
                                //$link        = esc_url( $event['link'][0]['href'] );
                                $location    = (isset($event['location']))?esc_html( $event['location'] ):'';
                                
                                if ( isset($event['creator'])  &&  isset($event['creator']['email']) )
                                     $event_author_email    = esc_html( $event['creator']['email'] );
                                else $event_author_email = '';
                                

                                if ( isset( $event['start'] ) && isset( $event['end'] ) ) 
                                    list($range_dates, $range_time) = $this->getCommaSeparatedDates( $event['start'], $event['end'] );
                                else
                                    continue;                                   // Skip  if we gave no dates

//debuge($range_dates, $range_time);
                                $bktype = $this->getResource();

                                //FixIn: 8.6.1.4
//								if (   ( class_exists( 'wpdev_bk_biz_s' ) )
//									&& ( get_bk_option( 'booking_range_selection_time_is_active')  == 'On' )
//									&& ( get_bk_option( 'booking_ics_import_add_change_over_time' ) !== 'Off' )
//									&& ( get_bk_option( 'booking_ics_import_append_checkout_day' ) !== 'Off' )
//								) {
								if ( 'On' == get_bk_option( 'booking_ics_import_append_checkout_day' ) ) {                                      //FixIn: 2.0.27.1		//FixIn: 9.5.4.1
									// Add one additional  day  to .ics event (useful in some cases for bookings with  change-over days),
									//  if the imported .ics dates is coming without check  in/our times
									// Later system is adding check  in/out times from  Booking Calendar to  this event
									$range_dates_arr = explode( ',', $range_dates );
									$ics_event_check_out = trim( $range_dates_arr[ ( count( $range_dates_arr ) - 1 ) ] );
									$ics_event_check_out = explode( '.', $ics_event_check_out );
									$ics_event_check_out = $ics_event_check_out[2] . '-' . $ics_event_check_out[1] . '-' . $ics_event_check_out[0];

									$ics_event_check_out = strtotime( $ics_event_check_out );
									$ics_event_check_out = strtotime( '+1 day', $ics_event_check_out );
									$ics_event_check_out = date_i18n( "d.m.Y", $ics_event_check_out );
									$range_dates .= ', ' . $ics_event_check_out;
								}

	                            //FixIn: 8.6.1.1
	                            if ( empty( $range_time ) ) {

									if ( 	( class_exists( 'wpdev_bk_biz_s' ) )
										 && ( get_bk_option( 'booking_range_selection_time_is_active')  == 'On' )
										 && ( get_bk_option( 'booking_ics_import_add_change_over_time' )  !== 'Off' )
									) {    //FixIn: 2.0.5.1
										//Add check  in/out times to full day  events
										$wpbc_check_in  = get_bk_option( 'booking_range_selection_start_time' );// . ':01';                                    // ' 14:00:01'
										$wpbc_check_out = get_bk_option( 'booking_range_selection_end_time' );	// . ':02';                                    // ' 10:00:02';
										$range_time  = $wpbc_check_in . ' - ' . $wpbc_check_out;
										$range_time = "select-one^rangetime{$bktype}^{$range_time}~";
									}
	                            }
//debuge($range_dates, $range_time); 		//  array(  [0] => 07.08.2017, 08.08.2017,  [1] => select-one^rangetime4^03:00 - 07:00~ )

                                    $previous_active_user = -1;
                                    // MU
                                    if ( class_exists('wpdev_bk_multiuser') )  {
                                        // Get  the owner of this booking resource                                    
                                        $user_bk_id = apply_bk_filter('get_user_of_this_bk_resource', false, $bktype );

                                        // Check if its different user
                                        if ( ($user_bk_id !== false) && ($this->getUserID() != $user_bk_id) ){                                             
                                            // Get possible other active user settings
                                            $previous_active_user = apply_bk_filter('get_client_side_active_params_of_user'); 

                                            // Set active user of that specific booking resource
                                            make_bk_action('check_multiuser_params_for_client_side_by_user_id', $user_bk_id);

                                        }     
                                    }                                
                                    
                                $booking_gcal_events_form_fields = get_bk_option( 'booking_gcal_events_form_fields'); 
                                if ( is_serialized( $booking_gcal_events_form_fields ) )   
                                    $booking_gcal_events_form_fields = unserialize( $booking_gcal_events_form_fields );    

                                    // MU
                                    if ( $previous_active_user !== -1 ) {
                                        // Reactivate the previous active user
                                        make_bk_action('check_multiuser_params_for_client_side_by_user_id', $previous_active_user );
                                    }
                               
                                    
                                    
                               $booking_gcal_events_form_fields1 = explode('^', $booking_gcal_events_form_fields['title']);
                               $booking_gcal_events_form_fields1 = (empty($booking_gcal_events_form_fields1[1]))?false:true;

                               $booking_gcal_events_form_fields2 = explode('^', $booking_gcal_events_form_fields['description']);
                               $booking_gcal_events_form_fields2 = (empty($booking_gcal_events_form_fields2[1]))?false:true;

                               $booking_gcal_events_form_fields3 = explode('^', $booking_gcal_events_form_fields['where']);
                               $booking_gcal_events_form_fields3 = (empty($booking_gcal_events_form_fields3[1]))?false:true;
                               
                                                                    
                                $submit_array = array(    
                                    'bktype'  => $bktype
                                    , 'dates' => $range_dates
                                    , 'form'  => $range_time 
                                        . (($booking_gcal_events_form_fields1)? trim($booking_gcal_events_form_fields['title'])."{$bktype}^{$title}~" :'')
                                        . (($booking_gcal_events_form_fields2)? trim($booking_gcal_events_form_fields['description'])."{$bktype}^{$description}~" :'')
                                        . (($booking_gcal_events_form_fields3)? trim($booking_gcal_events_form_fields['where'])."{$bktype}^{$location}" :'')
                                        
//                                                    . "text^".trim($booking_gcal_events_form_fields['title'])."{$bktype}^{$title}~"
//                                                    //. "text^secondname{$bktype}^{$title}~"
//                                                    //. "email^email{$bktype}^{$title}~"
//                                                    //."text^phone{$bktype}^{$title}~"
//                                                    ."textarea^".trim($booking_gcal_events_form_fields['description'])."{$bktype}^{$description}~"
//                                                    ."text^".trim($booking_gcal_events_form_fields['where'])."{$bktype}^{$location}"
                                    , 'is_send_emeils' => $is_send_emeils
                                    , 'sync_gid' => $id                   
                                );                                                 

                                                    
                                // Add imported data to the array of events
                                $this->events[] = array( 
                                        //  'id'=>$booking_id
                                         'sync_gid' => $id
                                        , 'title'=> $title
                                        , 'description' => $description
                                        , 'location' => $location
                                        , 'dates' => $range_dates
                                        , 'times' => $range_time
                                        , 'booking_submit_data'=>$submit_array
                                        );
                            }
                        }
                        
                    } else {
                        //json_decode failed
                        $this->error = __( 'Some data was retrieved, but could not be parsed successfully. Please ensure your feed URL is correct.', 'booking' );
                    }
            } else {
//debuge($raw_data['response']['code']);                
                //The response code wasn't 200, so generate a helpful(ish) error message depending on error code 
                switch ( $raw_data['response']['code'] ) {
                    case 404:
                        $this->error = __( 'The feed could not be found (404). Please ensure your feed URL is correct.' ,'booking');
                        break;
                    case 403:
                        $this->error = __( 'Access to this feed was denied (403). Please ensure you have public sharing enabled for your calendar.' ,'booking');
                        break;
                    default:
                        $this->error = sprintf( __( 'The feed data could not be retrieved. Error code: %s. Please ensure your feed URL is correct.' ,'booking'), '<strong>' . $raw_data['response']['code'] . '</strong>' );
                        
                        if (isset( $raw_data['body'] ))
                            $this->error .= '<br><br><strong>' . __( 'Info', 'booking' ) . '</strong>: <code>' . $raw_data['body'] . '</code>';

                }
            }
        } else {
    
            //Generate an error message from the returned WP_Error
            $this->error = $raw_data->get_error_message() ;
        }
        
       
        if ( ! empty($this->error) ) {
            $is_spin = false;
            $is_error = true;            
            $this->show_message(  $this->error , $is_spin, $is_error);
	        if (  ( isset( $_REQUEST['action'] ) ) && ( 'WPBC_AJX_BOOKING_ACTIONS' == $_REQUEST['action'] )  ) {
				return  false;
	        }
            die;
        } else 
            $this->show_message(  __('Done' ,'booking') );
        

        
        // Get Already  exist same bookings        
        $exist_bookings_guid = $this->getExistBookings_gid( $this->events );
           
        
        // Create New bookings
        if (! empty($this->events) )
            $this->createNewBookingsFromEvents( $exist_bookings_guid );
        
        
        // Show imported Bookings Table
        if ( (! empty($this->events) ) && ( ! $this->is_silent ) )
            $this->showImportedEvents();
        
        
        return true;
    }

    
    private function getCommaSeparatedDates( $event_dates_start, $event_dates_end ) {                        

        if ( isset($event_dates_start['date']) &&  isset($event_dates_end['date']) ) {
            //$start_date = date_i18n('Y-m-d',   $this->iso_to_ts( $event_dates_start['date'] )  ); 
            //$end_date   = date_i18n('Y-m-d', ( $this->iso_to_ts( $event_dates_end['date'] ) - 86400 ) );
            $start_date =  $this->iso_to_ts( $event_dates_start['date'] )  ; 
            $end_date   =  $this->iso_to_ts( $event_dates_end['date']   ) - 86400 ;
            $range_time = '00:00 - 00:00';
        }
        
        if ( isset($event_dates_start['dateTime']) &&  isset($event_dates_end['dateTime']) ) {


			$start_date = $this->iso_to_ts( $event_dates_start['dateTime'] );
            $end_date   = $this->iso_to_ts( $event_dates_end['dateTime'] );

	        //FixIn: 8.9.4.2
	        /**
	         * Check  for situation,  when  we are having times starting or ending at  the midnight: 00:00:00
			 * Because in this "IF section" we are checking events dates with  times (in the previous "IF section" was FULL day  events,
			 * we need to  be sure that  the event do  not start  or end at  00:00 Otherwise Booking Calendar will
			 * show such  date as fully  booked!
	         */
			$start_date_unix = $start_date;
			$check_start_date = wpbc_datetime_localized( date( 'Y-m-d H:i:s', $start_date_unix ), 'Y-m-d H:i:s' );
			if ( substr( $check_start_date, 11 ) === '00:00:00' ) {
				$start_date_unix = $start_date_unix + 61;
				$start_date = $start_date_unix;
			}
			$end_date_unix = $end_date;
			$check_end_date   = wpbc_datetime_localized( date( 'Y-m-d H:i:s', $end_date_unix ), 'Y-m-d H:i:s' );
			if ( substr( $check_end_date, 11 ) === '00:00:00' ) {
				$end_date_unix = $end_date_unix - 2;
				$end_date = $end_date_unix;
			}

            $range_time  = date_i18n('H:i', $start_date ) . ' - ' . date_i18n('H:i', $end_date );
            
            //$start_date = date_i18n('Y-m-d', $start_date );
            //$end_date   = date_i18n('Y-m-d', $end_date );            
        }
        
        $dates_comma = wpbc_get_comma_seprated_dates_from_to_day( date_i18n("d.m.Y", $start_date ), date_i18n("d.m.Y", $end_date ) ) ;
        //$dates = wpbc_get_dates_array_from_start_end_days($start_date, $end_date );

        //$dates_comma = implode(', ', $dates);
        
        
//        // Get Times
//        $start_time  = $this->iso_to_ts( $event_dates[0]['startTime'] );
//        
//        if ( date_i18n('H:i', $this->iso_to_ts( $event_dates[ (count($event_dates)-1) ]['endTime'] ) ) == '00:00' ) 
//            $end_time = $this->iso_to_ts( $event_dates[ (count($event_dates)-1) ]['endTime'] ) - 86400; // 24 hours - 60*60*24 = 86400
//        else 
//            $end_time = $this->iso_to_ts( $event_dates[ (count($event_dates)-1) ]['endTime'] );
//        
//
//        $range_time  = date_i18n('H:i', $start_time ) . ' - ' . date_i18n('H:i', $end_time );
        if ( $range_time != '00:00 - 00:00' ) { 
            $bktype = $this->getResource();
            $range_time = "select-one^rangetime{$bktype}^{$range_time}~";
        } else
             $range_time = '';

        return array($dates_comma, $range_time);
    }
    
    
    ////////////////////////////////////////////////////////////////////////////
    // Create New bookings based on $this->events array.
    ////////////////////////////////////////////////////////////////////////////
    public function createNewBookingsFromEvents( $exist_bookings_guid = array() ) {
                
        foreach ($this->events as $key => $event) {
            
            if ( ! in_array( $event['sync_gid'], $exist_bookings_guid ) ) {

				$submit_array = $event['booking_submit_data'];

				// Create a new booking
				$request_save_params = array(
											 'resource_id'         => $submit_array['bktype'],					// 2
											 'dates_ddmmyy_csv'    => $submit_array['dates'],					// '04.10.2023, 05.10.2023, 06.10.2023'
											 'form_data'           => $submit_array['form'],					// 'text^cost_hint2^150.00฿~select-multiple^rangetime2[]^14:00...'
											 'booking_hash'        => '',										// 'sdfsf34534rf'
											 'custom_form'         => '',										// 'custom_form_name'
											 'is_emails_send'       => $submit_array['is_send_emeils'],			// 0 | 1
											 'is_show_payment_form' => 0,										// 0 | 1
												//'request_uri'          => $_SERVER['HTTP_REFERER']
												//'user_id' 			 => wpbc_get_current_user_id()
											 'sync_gid' 		    => $submit_array['sync_gid'],
											 'is_approve_booking'   => intval( 'On' === get_bk_option( 'booking_auto_approve_bookings_when_import' ) )		// Auto  approve booking if imported
										);

				if (  'On' != get_bk_option( 'booking_condition_import_if_available' ) ) {		//FixIn: 9.8.15.8     -  'Import only, if days are available'
					$request_save_params['save_booking_even_if_unavailable'] = 1;
				}

				$booking_save_arr = wpbc_booking_save( $request_save_params );

				if ( 'ok' === $booking_save_arr['ajx_data']['status'] ) {												// Everything Cool :) - booking has been created
					$booking_id = $booking_save_arr['booking_id'];
					$this->events[$key]['id'] = $booking_id;

					// Add notes to the booking relative source of imported booking
					if ( class_exists( 'wpdev_bk_personal' ) ) {
						$remark_text = '[' . wpbc_datetime_localized( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' ) . '] ' . sprintf( __( 'Imported from %s ', 'booking-manager' ), 'Google Calendar' );
						$remark_text = str_replace( '%', '&#37;', $remark_text );
						$remark_text = str_replace( array( '"', "'" ), '', $remark_text );
						$remark_text = trim( $remark_text );
						$is_append   = true;
						make_bk_action( 'wpdev_make_update_of_remark', $booking_id, $remark_text, $is_append );
					}

				} else {																								// Error
					// Error message: 		$booking_save_arr['ajx_data']['ajx_after_action_message'];
					$parsed_booking_data_arr = wpbc_get_parsed_booking_data_arr( $request_save_params['form_data'], $request_save_params['resource_id'], array( 'get' => 'value' ) );

					$plain_form_data_show = wpbc_get__booking_form_data__show( $request_save_params['form_data'], $request_save_params['resource_id'], array( 'unknown_shortcodes_replace_by' => ' ... ' ) );

					// Replace <p> | <br> to ' '
					$plain_form_data_show = preg_replace( '/<(br|p)[\t\s]*[\/]?>/i', ' ', $plain_form_data_show );

					// Replace \r \n \t
					$plain_form_data_show = preg_replace( '/(\\r|\\n|\\t)/i', ' ', $plain_form_data_show );

					$this->error .=   '<div style="border:1px solid #ccc;padding:10px 1em;margin:1em 0;">'
									  	. '<strong> Google Calendar ' . __( 'Event', 'booking' ) . '</strong> '.  'UID'  . ': ' . $request_save_params['sync_gid']
									  	. '<hr/>'
									    . ' <span style="color:#8b2122;">' . $booking_save_arr['ajx_data']['ajx_after_action_message'] . '</span>'
									  	. '<hr/>'
					                  	. ' <strong>' . __( 'Resource', 'booking' ) . ' ID : ' . '</strong>' . $request_save_params['resource_id']
										. ' <strong>' . __( 'Dates', 'booking' )         .': ' . '</strong>' . $request_save_params['dates_ddmmyy_csv']
									  	. (   ( ! empty( $parsed_booking_data_arr['rangetime'] ) )
											? ( ' <strong>' . __( 'Times', 'booking' )  . ': ' . '</strong>' . $parsed_booking_data_arr['rangetime']  )
											: ''
									      )
									  	//. ' <strong>' . __( 'Data', 'booking' ) . ': ' . ' </strong>'
									  	. $plain_form_data_show
									. '</div>';

					unset( $this->events[ $key ] );
				}
            } else {
                unset($this->events[$key]);
            }
        }   

        return $this->events;
    }
    
    
    ////////////////////////////////////////////////////////////////////////////
    // Get Already  exist same bookings
    ////////////////////////////////////////////////////////////////////////////
    public function getExistBookings_gid( $events_array ) {
        
        $sql_sync_gid = array();
        foreach ($events_array as $event) {
            $sql_sync_gid[] = $event['sync_gid'];
        }        
        $sql_sync_gid= implode( "','",$sql_sync_gid );
        
        $exist_bookings_guid = array();

		$booking_condition_import_only_new = get_bk_option( 'booking_condition_import_only_new' );        //FixIn: 9.8.15.8
	    if ( ( ! empty( $sql_sync_gid ) ) && ( 'On' === $booking_condition_import_only_new ) ) {
            global $wpdb;       
            $exist_bookings = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}booking WHERE sync_gid IN ('{$sql_sync_gid}') AND trash != 1" );		//FixIn: 9.8.15.8
            foreach ($exist_bookings as $bk) {
                $exist_bookings_guid[]=$bk->sync_gid;
            }
        }
        return $exist_bookings_guid;        
    }
    
    
    ////////////////////////////////////////////////////////////////////////////
    // Show Table of imported Events
    ////////////////////////////////////////////////////////////////////////////    
    public function showImportedEvents( ) {
                ////////////////////////////////////////////////////////////////////////
        ?>        
        <div id="gcal_imported_events<?php echo $this->getResource(); ?>" class="table-responsive">
        <table style="width:99%;margin-top:45px;border:1px solid #ccc;" 
               
               class="resource_table0 booking_table0 table table-striped " 
               cellpadding="0" cellspacing="0">
            <?php
            
                if (function_exists ('wpbc_db__get_resource_title')) {
                    echo '<tr><td colspan="6" style="padding:5px 10px;font-style:italic;"> <h4>' , wpbc_get_resource_title( $this->getResource() ) , '</h4></td></tr>';
                }

            ?>
            <?php // Headers  ?>
            <tr>
                <th style="text-align:center;width:15px;"><input type="checkbox" onclick="javascript:jQuery('#gcal_imported_events<?php echo $this->getResource(); ?> .events_items').attr('checked', this.checked);" class="" id="events_items_all"  name="events_items_all" /></th>
                <th style="text-align:center;width:10px;height:35px;"> <?php _e('ID' ,'booking'); ?> </th>
                <th style="text-align:center;height:35px;width:220px;" style="border-left: 1px solid #ccc;"> <?php _e('Title' ,'booking'); ?> </th>                
                <th style="text-align:center;"> <?php _e('Info' ,'booking'); ?> </th>
                <th style="text-align:center;"> <?php _e('Dates' ,'booking'); ?> </th>
                <th style="text-align:center;width:10px;height:35px;"> <?php _e('GID' ,'booking'); ?> </th>
            </tr>
            <?php
            $alternative_color = '';
            if (! empty($this->events))
              foreach ($this->events as $bt) {
                
                if ( $alternative_color == '')    
                    $alternative_color = ' class="alternative_color" ';
                else                              
                    $alternative_color = '';
                ?>
                   <tr id="gcal_imported_events_id_<?php echo $bt['id']; ?>"> 
                        <td <?php echo $alternative_color; ?> style="border-left: 0;border-right: 1px solid #ccc;">
<!--                            <span class="wpbc_mobile_legend"><?php _e('Selection' ,'booking'); ?>:</span>-->
                            <input type="checkbox" class="events_items" id="events_items_<?php echo $bt['id']; ?>" value="<?php echo $bt['id']; ?>"  name="events_items_<?php echo $bt['id']; ?>" /></td>
                        <td style="border-right: 0;border-left: 1px solid #ccc;text-align: center;" <?php echo $alternative_color; ?> >
<!--                            <span class="wpbc_mobile_legend"><?php _e('ID' ,'booking'); ?>:</span>-->
                            <?php echo $bt['id']; ?></td>
                        <td <?php echo $alternative_color; ?> style="border-right: 0;border-left: 1px solid #ccc;">
<!--                            <span class="wpbc_mobile_legend"><?php _e('Title' ,'booking'); ?>:</span>-->
                            <span ><?php echo $bt['title']; ?></span>
                        </td>                        
                        <td style="border-right: 0;border-left: 1px solid #ccc;text-align: center;" <?php echo $alternative_color; ?> >
<!--                            <span class="wpbc_mobile_legend"><?php _e('Info' ,'booking'); ?>:</span>-->
                            <span ><?php echo $bt['description']; ?></span><br/>
                            <?php 
                            if (! empty($bt['location']) )
                                echo '<span >', __('Location:' ,'booking'), ': ' , $bt['location'], '</span>'; 
                            ?>
                        </td>

                        <td style="border-right: 0;border-left: 1px solid #ccc;text-align: center;" <?php echo $alternative_color; ?> >                                         
                            <span class="wpbc-listing-collumn booking-dates field-dates field-booking-date">
<!--                                <span class="wpbc_mobile_legend"><?php _e('Dates' ,'booking'); ?>:</span>-->
                                <div class="booking_dates_full" ><?php 
                                    $bt['dates'] = explode(', ', $bt['dates']);
                                    foreach ($bt['dates'] as $keyd=>$valued) {
                                        
                                        $valued = explode( '.', $valued );
                                        $valued = wpbc_get_date_in_correct_format( sprintf("%04d-%02d-%02d" ,$valued[2], $valued[1], $valued[0] ) ) ;
                                         
                                        $bt['dates'][$keyd] = '<a href="javascript:void(0)" class="field-booking-date">' . $valued[0] . '</a>';
                                    }
                                    $bt['dates'] = implode('<span class="date_tire">, </span>', $bt['dates']);
                                    echo $bt['dates'];//date_i18n('d.m.Y H:i',$bt['start_time'] ), ' - ', date_i18n('d.m.Y H:i',$bt['end_time']); ?>
                                </div>
                            </span>
                        </td>
                        <td style="border-right: 0;border-left: 1px solid #ccc;text-align: center;" <?php echo $alternative_color; ?> >
<!--                            <span class="wpbc_mobile_legend"><?php _e('GID' ,'booking'); ?>:</span>-->
                            <?php echo $bt['sync_gid']; ?></td>
                   </tr>                   
            <?php } ?>

            <tr class="wpbc_table_footer">
                <td colspan="6" style="text-align: center;"> 
                    <a href="javascript:void(0)" class="button button-primary" style="float:none;margin:10px;" 
                       onclick="javascript:location.reload();" ><?php _e('Reload page' ,'booking'); ?></a>                    
                    <a href="javascript:void(0)" class="button" style="float:none;margin:10px;" 
                       onclick="javascript:jQuery('#gcal_imported_events<?php echo $this->getResource(); ?>').remove();" ><?php _e('Hide' ,'booking'); ?></a>                    
                    <a href="javascript:void(0)" class="button"  style="float:none;margin:10px;"                                        
                       onclick="javascript: if ( wpbc_are_you_sure('<?php echo esc_js(__('Do you really want to delete selected booking(s) ?' ,'booking')); ?>') ) {
                                                    //delete_booking( 
                                                    trash__restore_booking( 1,         <?php //FixIn: 7.0.1  ?>
                                                                    get_selected_bookings_id_in_this_list('#gcal_imported_events<?php echo $this->getResource(); ?> .events_items', 13) 
                                                                    , <?php echo $this->getUserID(); ?>
                                                                    , '<?php echo wpbc_get_maybe_reloaded_booking_locale(); ?>'
                                                                    , 1  
                                                    ); 
                                            } "
                       ><?php _e('Delete selected booking(s)' ,'booking'); ?></i></a>
                    
                </td>
            </tr>

        </table>
        </div>
        <script type="text/javascript"> 
            jQuery("#gcal_imported_events<?php echo $this->getResource(); ?>").insertAfter("#toolbar_booking_listing");
        </script>
        <?php   
    }
                                
}
