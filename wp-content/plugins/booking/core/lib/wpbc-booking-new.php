<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Create new bookings functions
 * @category Bookings
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.04.23
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


// TODO:  re-update this function: wpbc_check_dates_intersections. we need to  call the where_to_save function

//TODO: 2023-10-03 14:49  the new workflow have to be in bookings__actions.php  in line 1190


/** 
	 * Check if dates intersect with  other dates array
 * 
 * @param array $dates_for_check                            - Dates Array of specific booking, which we checking            - date in SQL format: '2014-11-21 10:00:01'
 * @param array $dates_exist                                - Other dates from booking resource(s),  that  already  exist   - date in SQL format: '2014-11-21 15:00:02'
 * @return bool true - intersect, false - not intersect
 */
function wpbc_check_dates_intersections( $dates_for_check, $dates_exist  ) {    //FixIn: 5.4.5 
    
    $is_intersected = false;

    $booked_dates        = array(); 
    $what_dates_to_check = array();
    
//debuge($dates_for_check, $dates_exist);

    foreach ( $dates_exist as $value ) {

        if (  ( is_object( $value ) ) && ( isset( $value->booking_date ) )  ) 
            $booking_date = $value->booking_date;                               // Its object  with date value
        else 
            $booking_date = $value;                                             // Its array of string dates
                
        
        if ( intval( substr( $booking_date, -1 ) ) == 1 ) {                     // We require time shift  for situation,  when  previous booking end in the same time,  when  new booking start
            $time_shift = 10;                                                   // Plus 10  seconds
        } elseif ( intval( substr( $booking_date, -1 ) ) == 2 ) {
            $time_shift = -10;                                                  // Minus 10  seconds
        } else    
            $time_shift = 0; 
        
        // Booked dates in destination resource,  that can intersect
        $booked_dates[ $booking_date ] = strtotime( $booking_date ) + $time_shift;;

        // Get here only  dates,  without times:                                [2015-11-09] => 1447027200
        $what_dates_to_check[ substr($booking_date, 0, 10) ] = strtotime( substr($booking_date, 0, 10) );
    }            

    asort( $booked_dates );                                                     // Sort dates   
    
    
    $keyi=0;
    $dates_to_add = array();
    foreach ( $booked_dates as $date_key => $date_value ) {
        
        if ( $keyi == 0 ) {                                                     // First element
            if ( intval( substr( $date_key, -1 ) ) == 2 ) {
                // We are having first  date as ending date, its means that  starting date exist somewhere before,  and we need to  set it at the begining 
                $dates_to_add[ substr($date_key, 0, 10) . ' 00:00:11' ] = strtotime( substr($date_key, 0, 10) . ' 00:00:11' );
            }
        }                
        
        if ( $keyi == ( count($booked_dates) - 1 ) ) {                                                     // last  element
            if ( intval( substr( $date_key, -1 ) ) == 1 ) {
                // We are having last  date as ending date, its means that  ending  date exist somewhere after,  and we need to  set it at the end of array 
                $dates_to_add[ substr($date_key, 0, 10) . ' 23:59:42' ] = strtotime( substr($date_key, 0, 10) . ' 23:59:42' );
            }
        }                
        $keyi++;
    }
    $booked_dates = array_merge($booked_dates, $dates_to_add);
    asort( $booked_dates );                                                     // Sort dates       
    
    
    // Skip dates (in original booking) that does not exist in destination  resource at all
    $check_dates = array();  
    foreach ( $dates_for_check as $value ) {

        if (  ( is_object( $value ) ) && ( isset( $value->booking_date ) )  ) 
            $booking_date = $value->booking_date;                               // Its object  with date value
        else 
            $booking_date = $value;                                             // Its array of string dates
        
        // Check  dates only if these dates already  exist in $what_dates_to_check array
        if ( isset( $what_dates_to_check[ substr($booking_date, 0, 10) ] ) ) 
            $check_dates[] = $value;
    }

    if ( count( $check_dates ) == 0 ) return $is_intersected;                   // No intersected dates at all in exist bookings. Return.       //FixIn: 6.0.1.13
    
    foreach ( $check_dates as $value ) {

        if (  ( is_object( $value ) ) && ( isset( $value->booking_date ) )  ) 
            $booking_date = $value->booking_date;                               // Its object  with date value
        else
            $booking_date = $value;                                             // Its array of string dates
        
        if ( isset( $booked_dates[ $booking_date ] ) ) {                        // Already have exactly  this date as booked
            $is_intersected = true;
            break;
        }
                
        if ( intval( substr( $booking_date, -1 ) ) == 1 ) {                     // We require time shift  for situation,  when  previous booking end in the same time,  when  new booking start
            $time_shift = 10;                                                   // Plus 10  seconds
        } elseif ( intval( substr( $booking_date, -1 ) ) == 2 ) {
            $time_shift = -10;                                                  // Minus 10  seconds
        } else    
            $time_shift = 0; 
        
        $booked_dates[ $booking_date ] = strtotime( $booking_date ) + $time_shift;
    }   
    
    
    asort( $booked_dates );                                                     // Sort dates   

//debuge($booked_dates);    
    if ( ! $is_intersected ) {
        
        // check  dates and times for intersections 
        $previos_date_key = 0;
        foreach ( $booked_dates as $date_key => $value ) {
            
            $date_key = intval( substr( $date_key, -1 ) );                      // Get last second

            // Check  if the date fully booked (key = 0), or we are having 2 the same keys,  like 1 and 1 or 2 and 2 one under other. Its means that  we are having time intersection.
            if ( ( $date_key !== 0 ) && ( $date_key != $previos_date_key ) )    
                $previos_date_key = $date_key;
            else {
                $is_intersected = true;
                break;
            }
        }
    }

    return  $is_intersected ;
}
