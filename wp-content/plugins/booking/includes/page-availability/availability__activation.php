<?php
/**
 * @version     1.0
 * @package     Booking Calendar
 * @category    A c t i v a t e    &    D e a c t i v a t e
 * @author      wpdevelop
 *
 * @web-site    https://wpbookingcalendar.com/
 * @email       info@wpbookingcalendar.com
 * @modified    2022-11-23
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/**
 *  A c t i v a t e  - Create new DB Table, for booking dates properties
 *
 * booking date property can be relative to:  	prop_name =  'unavailable', 'available', 'rate', 'allow_start_day_selection', 'allow_days_number_to_select', 'availability_count', ...
 *
 * 											?:	prop_name =  'date_status'		->	 	prop_value =  'unavailable', 'available', 'pending', 'approved'
 *
 * @return void
 */
function wpbc_activation__dates_availability() {                                                                            //FixIn: 9.3.1.2

	global $wpdb;
	$charset_collate  = ( ! empty( $wpdb->charset ) ) ? "DEFAULT CHARACTER SET $wpdb->charset" : '';
	$charset_collate .= ( ! empty( $wpdb->collate ) ) ? " COLLATE $wpdb->collate" : '';

	if ( ! wpbc_is_table_exists( 'booking_dates_props' ) ) {
		$simple_sql = "CREATE TABLE {$wpdb->prefix}booking_dates_props (
                     booking_dates_prop_id bigint(20) unsigned NOT NULL auto_increment,                     
                     resource_id bigint(10) NOT NULL default 1,
                     calendar_date datetime NOT NULL default '0000-00-00 00:00:00',
                     prop_name varchar(200) NOT NULL default '',
                     prop_value text,
                     PRIMARY KEY  (booking_dates_prop_id)
                    ) {$charset_collate}";
		$wpdb->query( $simple_sql );
	}
}
add_bk_action( 'wpbc_activation_after_db_actions', 'wpbc_activation__dates_availability' );


/**
 * D e a c t i v a t e
 */
function wpbc_deactivation__dates_availability() {

	global $wpdb;
	$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}booking_dates_props" );
}
add_bk_action( 'wpbc_other_versions_deactivation', 'wpbc_deactivation__dates_availability' );