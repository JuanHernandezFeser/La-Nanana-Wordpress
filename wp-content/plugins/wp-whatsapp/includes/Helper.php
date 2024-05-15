<?php
namespace NTA_WhatsApp;

defined( 'ABSPATH' ) || exit;

class Helper {

	protected static $instance = null;
	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
	}

	public static function printWorkingDays( $array_data ) {
		if ( $array_data['isAlwaysAvailable'] === 'ON' ) {
			return __( 'Always online', 'wp-whatsapp' );
		}

		$date_string = '';
		$daysOfWeek  = array(
			'sunday'    => __( 'Sunday', 'wp-whatsapp' ),
			'monday'    => __( 'Monday', 'wp-whatsapp' ),
			'tuesday'   => __( 'Tuesday', 'wp-whatsapp' ),
			'wednesday' => __( 'Wednesday', 'wp-whatsapp' ),
			'thursday'  => __( 'Thursday', 'wp-whatsapp' ),
			'friday'    => __( 'Friday', 'wp-whatsapp' ),
			'saturday'  => __( 'Saturday', 'wp-whatsapp' ),
		);

		foreach ( $array_data['daysOfWeekWorking'] as $dayKey => $dayVal ) {
			if ( $dayVal['isWorkingOnDay'] === 'ON' ) {
				$date_string .= $daysOfWeek[ $dayKey ] . ', ';
			}
		}

		$date_string = trim( $date_string, ', ' );
		return $date_string;
	}

	public static function getValueOrDefault( $object, $objectKey, $defaultValue = '' ) {
		return ( isset( $object[ $objectKey ] ) ? $object[ $objectKey ] : $defaultValue );
	}

	public static function buildTimeSelector( $default = '08:00', $interval = '+30 minutes' ) {
		$output = '';

		$current = strtotime( '00:00' );
		$end     = strtotime( '23:59' );

		while ( $current <= $end ) {
			$time = date( 'H:i', $current );
			$sel  = ( $time == $default ) ? ' selected' : '';

			$output .= "<option value=\"{$time}\"{$sel}>" . date( 'H:i', $current ) . '</option>';
			$current = strtotime( $interval, $current );
		}
		$sel     = ( $default === '23:59' ) ? ' selected' : '';
		$output .= "<option value=\"23:59\"{$sel}>" . '23:59' . '</option>';
		return $output;
	}

	public static function sanitize_array( $var ) {
		if ( is_array( $var ) ) {
			return array_map( 'self::sanitize_array', $var );
		} else {
			return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
		}
	}

	public static function checkGDPR( $option ) {
		if ( $option['isShowGDPR'] === 'OFF' ) {
			return false;
		}
		if ( isset( $_COOKIE['nta-wa-gdpr'] ) && $_COOKIE['nta-wa-gdpr'] == 'accept' ) {
			return false;
		}
		return true;
	}

	public static function isSaveNewPost( $refererUrl ) {
		$add_new_action = strpos( $refererUrl, 'post-new.php' );
		if ( $add_new_action !== false ) {
			return true;
		}
		return false;
	}

	public static function wp_timezone_string() {
		$timezone_string = get_option( 'timezone_string' );

		if ( $timezone_string ) {
			return $timezone_string;
		}

		$offset  = (float) get_option( 'gmt_offset' );
		$hours   = (int) $offset;
		$minutes = ( $offset - $hours );

		$sign      = ( $offset < 0 ) ? '-' : '+';
		$abs_hour  = abs( $hours );
		$abs_mins  = abs( $minutes * 60 );
		$tz_offset = sprintf( '%s%02d:%02d', $sign, $abs_hour, $abs_mins );

		return $tz_offset;
	}

	public static function print_icon() {
		return '<svg width="48px" height="48px" class="nta-whatsapp-default-avatar" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
            <path style="fill:#EDEDED;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
            S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z"/>
            <path style="fill:#55CD6C;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
            c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
            c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z"/>
            <path style="fill:#FEFEFE;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
            c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
            c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
            c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
            c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
            l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945"/>
        </svg>';
	}
}
