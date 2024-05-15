<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Google Calendar Import
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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  A J A X
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//FixIn: 9.6.3.5

function wpbc_silent_import_all_events() {



    global $wpdb;

    $wpbc_Google_Calendar = new WPBC_Google_Calendar();
    
    $wpbc_Google_Calendar->setSilent();
            
    $wpbc_Google_Calendar->set_timezone( get_bk_option('booking_gcal_timezone') );
    
    $wpbc_Google_Calendar->set_events_max( get_bk_option( 'booking_gcal_events_max') );
    
    $wpbc_Google_Calendar->set_events_from_with_array( 
                                                        array(  get_bk_option( 'booking_gcal_events_from')
                                                                , get_bk_option( 'booking_gcal_events_from_offset' )
                                                                , get_bk_option( 'booking_gcal_events_from_offset_type' ) ) 
                                                    ); 
    
    $wpbc_Google_Calendar->set_events_until_with_array( 
                                                        array(  get_bk_option( 'booking_gcal_events_until')
                                                                , get_bk_option( 'booking_gcal_events_until_offset' )
                                                                , get_bk_option( 'booking_gcal_events_until_offset_type' ) ) 
                                                    );
    
    if ( ! class_exists('wpdev_bk_personal') ) { 
        
        $wpbc_Google_Calendar->setUrl( get_bk_option( 'booking_gcal_feed') );
        $import_result = $wpbc_Google_Calendar->run();
        
    } else {
        
        $types_list = $wpdb->get_results( "SELECT booking_type_id, import FROM {$wpdb->prefix}bookingtypes" );

        foreach ($types_list as $wpbc_booking_resource) {
            $wpbc_booking_resource_id = $wpbc_booking_resource->booking_type_id;
            $wpbc_booking_resource_feed = $wpbc_booking_resource->import;
            if ( (! empty($wpbc_booking_resource_feed) ) && ($wpbc_booking_resource_feed != NULL ) && ( $wpbc_booking_resource_feed != '/' ) ) {

                $wpbc_Google_Calendar->setUrl($wpbc_booking_resource_feed);
                $wpbc_Google_Calendar->setResource($wpbc_booking_resource_id);

				//FixIn: 9.9.0.25
	            if ( function_exists( 'wpbm_delete_all_imported_bookings' ) ) {
		            wpbm_delete_all_imported_bookings( array( 'resource_id' => $wpbc_booking_resource_id ) );
	            }
                $import_result = $wpbc_Google_Calendar->run();                
            }
        }                    
    }
//    debuge(2);
}
add_bk_action('wpbc_silent_import_all_events' , 'wpbc_silent_import_all_events' ); 

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Fields for Modal window
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    function wpbc_gcal_settings_content_field_from( $booking_gcal_events_from, $booking_gcal_events_from_offset = '', $booking_gcal_events_from_offset_type = '' ) {
        if ($booking_gcal_events_from == "date") {
            echo '<style type="text/css"> .booking_gcal_events_from .wpbc_offset_value { display:none; } </style>';
        } else {
            echo '<style type="text/css"> .booking_gcal_events_from .wpbc_offset_datetime { display:none; } </style>';            
        }        
        ?>
        <tr valign="top">
            <th scope="row"><label for="booking_gcal_events_from" ><?php _e('From' ,'booking'); ?>:</label></th>
            <td class="booking_gcal_events_from">                        
                <select id="booking_gcal_events_from" name="booking_gcal_events_from"
                        onchange="javascript: if(this.value=='date') {
                            jQuery('.booking_gcal_events_from .wpbc_offset_value').hide();
                            jQuery('.booking_gcal_events_from .wpbc_offset_datetime').show();
                        } else {
                            jQuery('.booking_gcal_events_from .wpbc_offset_value').show();
                            jQuery('.booking_gcal_events_from .wpbc_offset_datetime').hide();                               
                        }
                        jQuery('#booking_gcal_events_from_offset').val('');" >                        
                    <?php 
                    $wpbc_options = array(
                                            "now" => __('Now' ,'booking')
                                          , "today" => __('00:00 today' ,'booking')
                                          , "week" => __('Start of current week' ,'booking')
                                          , "month-start" => __('Start of current month' ,'booking')
                                          , "month-end" => __('End of current month' ,'booking')
                                          , "any" => __('The start of time' ,'booking')
                                          , "date" => __('Specific date / time' ,'booking')
                                    );
                    foreach ($wpbc_options as $key => $value) {
                        ?><option <?php if( $booking_gcal_events_from == $key ) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                    }
                    ?>
                </select>
                <span class="description"><?php _e('Select option, when to start retrieving events.' ,'booking');?></span>                
                <div class="booking_gcal_events_from_offset" style="margin:10px 0 0;">
                    <label for="booking_gcal_events_from_offset"> <span class="wpbc_offset_value"><?php _e('Offset' ,'booking'); ?></span><span class="wpbc_offset_datetime" ><?php _e('Enter date / time' ,'booking'); ?></span>: </label>
                    <input type="text"  id="booking_gcal_events_from_offset" name="booking_gcal_events_from_offset" value="<?php echo $booking_gcal_events_from_offset; ?>" style="width:100px;text-align: right;" />
                    <span class="wpbc_offset_value">
                        <select id="booking_gcal_events_from_offset_type" name="booking_gcal_events_from_offset_type" style="margin-top: -2px;width: 99px;">
                            <?php 
                            $wpbc_options = array(
                                                    "second" => __('seconds' ,'booking')
                                                  , "minute" => __('minutes' ,'booking')
                                                  , "hour" => __('hours' ,'booking')
                                                  , "day" => __('days' ,'booking')
                                            );
                            foreach ($wpbc_options as $key => $value) {
                                ?><option <?php if( $booking_gcal_events_from_offset_type == $key ) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                            }
                            ?>
                        </select>
                        <span class="description"><?php _e('You can specify an additional offset from you chosen start point. The offset can be negative.' ,'booking');?></span>
                    </span>
                    <span class="wpbc_offset_datetime">
                        <em><?php printf(__('Type your date in format %s. Example: %s' ,'booking'),'Y-m-d','2014-08-01'); ?></em>
                    </span>
                </div>
            </td>
        </tr>
        <?php
    }

    
    function wpbc_gcal_settings_content_field_until( $booking_gcal_events_until, $booking_gcal_events_until_offset = '', $booking_gcal_events_until_offset_type = '' ) {  
        if ($booking_gcal_events_until == "date") {
            echo '<style type="text/css"> .booking_gcal_events_until .wpbc_offset_value { display:none; } </style>';
        } else {
            echo '<style type="text/css"> .booking_gcal_events_until .wpbc_offset_datetime { display:none; } </style>';            
        }
        ?>
        <tr valign="top">
            <th scope="row"><label for="booking_gcal_events_until" ><?php _e('Until' ,'booking'); ?>:</label></th>
            <td class="booking_gcal_events_until">                                
                <select id="booking_gcal_events_until" name="booking_gcal_events_until"
                        onchange="javascript: if(this.value=='date') {
                            jQuery('.booking_gcal_events_until .wpbc_offset_value').hide();
                            jQuery('.booking_gcal_events_until .wpbc_offset_datetime').show();
                        } else {
                            jQuery('.booking_gcal_events_until .wpbc_offset_value').show();
                            jQuery('.booking_gcal_events_until .wpbc_offset_datetime').hide();                            
                        }
                        jQuery('#booking_gcal_events_until_offset').val('');" >
                    <?php 
                    $wpbc_options = array(
                                            "now" => __('Now' ,'booking')
                                          , "today" => __('00:00 today' ,'booking')
                                          , "week" => __('Start of current week' ,'booking')
                                          , "month-start" => __('Start of current month' ,'booking')
                                          , "month-end" => __('End of current month' ,'booking')
                                          , "any" => __('The end of time' ,'booking')
                                          , "date" => __('Specific date / time' ,'booking')
                                    );
                    foreach ($wpbc_options as $key => $value) {
                        ?><option <?php if( $booking_gcal_events_until == $key ) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                    }
                    ?>
                </select>
                <span class="description"><?php _e('Select option, when to stop retrieving events.' ,'booking');?></span>
                <div class="booking_gcal_events_until_offset" style="margin:10px 0 0;">
                    <label for="booking_gcal_events_until_offset" > <span class="wpbc_offset_value"><?php _e('Offset' ,'booking'); ?></span><span class="wpbc_offset_datetime" ><?php _e('Enter date / time' ,'booking'); ?></span>: </label>
                    <input type="text" id="booking_gcal_events_until_offset" name="booking_gcal_events_until_offset" value="<?php echo $booking_gcal_events_until_offset; ?>" style="width:100px;text-align: right;" />
                    <span class="wpbc_offset_value">
                        <select id="booking_gcal_events_until_offset_type" name="booking_gcal_events_until_offset_type" style="margin-top: -2px;width: 99px;">
                            <?php 
                            $wpbc_options = array(
                                                    "second" => __('seconds' ,'booking')
                                                  , "minute" => __('minutes' ,'booking')
                                                  , "hour" => __('hours' ,'booking')
                                                  , "day" => __('days' ,'booking')
                                            );
                            foreach ($wpbc_options as $key => $value) {
                                ?><option <?php if( $booking_gcal_events_until_offset_type == $key ) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                            }
                            ?>
                        </select>
                        <span class="description"><?php _e('You can specify an additional offset from you chosen end point. The offset can be negative.' ,'booking');?></span>
                    </span>
                    <span class="wpbc_offset_datetime">
                        <em><?php  printf(__('Type your date in format %s. Example: %s' ,'booking'),'Y-m-d','2014-08-30'); ?></em>
                    </span>
                    
                </div>
            </td>
        </tr>                        
        <?php
    }

    
    function wpbc_gcal_settings_content_field_max_feeds($booking_gcal_events_max) {
        ?>
        <tr valign="top">
            <th scope="row"><label for="booking_gcal_events_max" ><?php _e('Maximum number' ,'booking'); ?>:</label></th>
            <td><input id="booking_gcal_events_max"  name="booking_gcal_events_max" class="regular-text" type="text" value="<?php echo $booking_gcal_events_max; ?>" />
                <span class="description"><?php 
                    _e('You can specify the maximum number of events to import during one session.' ,'booking');
              ?></span>
            </td>
        </tr>                
        <?php
    }
    
        
    function wpbc_gcal_settings_content_field_timezone($booking_gcal_timezone) {
        ?>
        <tr valign="top">
            <th scope="row"><label for="booking_gcal_timezone" ><?php _e('Timezone' ,'booking'); ?>:</label></th>
            <td>                                
                <select id="booking_gcal_timezone" name="booking_gcal_timezone">
                    <?php 
                    $wpbc_options = array(
                                            "" => __('Default' ,'booking')
                                    );
                    foreach ($wpbc_options as $key => $value) {
                        ?><option <?php if( $booking_gcal_timezone == $key ) echo "selected"; ?> value="<?php echo $key; ?>"><?php echo $value; ?></option><?php
                    }


                    // structure: $wpbc_booking_region_cities_list["Pacific"]["Fiji"] = "Fiji";
                    $wpbc_booking_region_cities_list = wpbc_get_booking_region_cities_list();							//FixIn: 8.9.4.9
                    
                    foreach ($wpbc_booking_region_cities_list as $region => $region_cities) {
                        
                        echo '<optgroup label="'. $region .'">';
                        
                        foreach ($region_cities as $city_key => $city_title) {
                            
                            if( $booking_gcal_timezone == $region .'/'. $city_key ) 
                                $is_selected = 'selected'; 
                            else 
                                $is_selected = '';
                            
                            echo '<option '.$is_selected.' value="'. $region .'/'. $city_key .'">' . $city_title . '</option>';
                            
                        }
                        echo '</optgroup>';
                    }
                    
                    
                    ?>
                </select>
                <span class="description"><?php _e('Select a city in your required timezone, if you are having problems with dates and times.' ,'booking');?></span>
            </td>
        </tr>                        
        <?php
    }

//FixIn: 9.6.3.5

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Activation
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function wpbc_sync_gcal_activate() {
        
    add_bk_option( 'booking_gcal_feed' , '' );
    add_bk_option( 'booking_gcal_events_from', 'month-start');
    add_bk_option( 'booking_gcal_events_from_offset' , '' );
    add_bk_option( 'booking_gcal_events_from_offset_type' , '' );
    add_bk_option( 'booking_gcal_events_until', 'any');
    add_bk_option( 'booking_gcal_events_until_offset' , '' );
    add_bk_option( 'booking_gcal_events_until_offset_type' , '' );
    add_bk_option( 'booking_gcal_events_max', '25');
    add_bk_option( 'booking_gcal_api_key', '');
    	add_bk_option( 'booking_gcal_timezone','');
    add_bk_option( 'booking_gcal_is_send_email' , 'Off' );
    add_bk_option( 'booking_gcal_auto_import_is_active' , 'Off'  );
    add_bk_option( 'booking_gcal_auto_import_time', '24' );
    
    	add_bk_option( 'booking_gcal_events_form_fields', 'a:3:{s:5:"title";s:9:"text^name";s:11:"description";s:16:"textarea^details";s:5:"where";s:5:"text^";}');
}
add_bk_action('wpbc_other_versions_activation',   'wpbc_sync_gcal_activate' );

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Deactivation
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function wpbc_sync_gcal_deactivate() {
    
    delete_bk_option( 'booking_gcal_feed' );
    delete_bk_option( 'booking_gcal_events_from');
    delete_bk_option( 'booking_gcal_events_from_offset' );
    delete_bk_option( 'booking_gcal_events_from_offset_type' );
    
    delete_bk_option( 'booking_gcal_events_until');
    delete_bk_option( 'booking_gcal_events_until_offset' );
    delete_bk_option( 'booking_gcal_events_until_offset_type' );
    
    delete_bk_option( 'booking_gcal_events_max' );    
    delete_bk_option( 'booking_gcal_api_key' );    
    	delete_bk_option( 'booking_gcal_timezone');
    delete_bk_option( 'booking_gcal_is_send_email' );
    delete_bk_option( 'booking_gcal_auto_import_is_active' );
    delete_bk_option( 'booking_gcal_auto_import_time' );
    
    	delete_bk_option( 'booking_gcal_events_form_fields');

}
add_bk_action('wpbc_other_versions_deactivation', 'wpbc_sync_gcal_deactivate' );
