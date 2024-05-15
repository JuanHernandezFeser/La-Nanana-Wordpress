<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Define Constants
 * @category Bookings
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.05.17
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

////////////////////////////////////////////////////////////
//   USERS  CONFIGURABLE  CONSTANTS           //////////////
////////////////////////////////////////////////////////////

if ( ! defined( 'WP_BK_CHECK_LESS_THAN_PARAM_IN_SEARCH' ) ) {   define( 'WP_BK_CHECK_LESS_THAN_PARAM_IN_SEARCH', false ); }         // Its will set 'Less than' logic for numbers in search  form  for custom  fields.
if ( ! defined( 'WP_BK_CHECK_IF_CUSTOM_PARAM_IN_SEARCH' ) ) {   define( 'WP_BK_CHECK_IF_CUSTOM_PARAM_IN_SEARCH', true ); }	        // Logical 'OR'.        Check (in search results) custom fields parameter that can include to  multiple selected options in search form.
if ( ! defined( 'WP_BK_CHECK_OUT_MINUS_DAY_SEARCH' ) ) {        define( 'WP_BK_CHECK_OUT_MINUS_DAY_SEARCH', '0' ); }	            // Define minus or plus some day(s) for check out search days. Search availability workflow for some customers.


////////////////////////////////////////////////////////////
//   SYSTEM  CONSTANTS                        //////////////
////////////////////////////////////////////////////////////
if ( ! defined( 'WP_BK_RESPONSE' ) ) {          define( 'WP_BK_RESPONSE',       false ); }
if ( ! defined( 'WP_BK_BETA_DATA_FILL' ) ) {    define( 'WP_BK_BETA_DATA_FILL', 0 ); }                                  // Set 0 for no filling or 2 for 241 bookings or more for more

define( 'WPBC_customize_plugin', false );