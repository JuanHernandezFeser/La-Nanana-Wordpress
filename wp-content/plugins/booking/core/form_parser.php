<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4


/**
 * Parse booking form  and get  shortcode array
 *
 *  Example:
 *              $form_shortcodes = wpbc_parse_form( $booking_form_fields );
 *
 *
 * @param $booking_form_configuration   - configuration booking form  in shortcodes:    '[calendar]... <p>Time: [select* rangetime "Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00"] ...'
 *
 * @return array
 *
 *              Return example:  [      0 => [
 *                                                'full_shortcode' => '[select rangetime  "10:00 - 11:00" "11:00 - 12:00" "12:00 - 13:00"]',
 *                                                'type' => 'select',
 *                                                'name' => 'rangetime',
 *                                                'options' => '',
 *                                                'values_str' => '"10:00 - 11:00" "11:00 - 12:00" "12:00 - 13:00"',
 *                                                'values_arr' => [
 *                                                                  0 => [ 'value' => '10:00 - 11:00' ],
 *                                                                  1 => [ 'value' => '11:00 - 12:00' ],
 *                                                                  2 => [ 'value' => '12:00 - 13:00' ]
 *                                                                ]
 *                                           ],
 *                                      1 => [
 *                                                'full_shortcode' => '[select rangetime  "10:00 - 12:00" "12:00 - 14:00"]',        <= !!! Several shortcodes with same name - in conditional sections !!!
 *                                                'type' => 'select',
 *                                                'name' => 'rangetime',
 *                                                'options' => '',
 *                                                'values_str' => '"10:00 - 12:00" "12:00 - 14:00"',
 *                                                'values_arr' => [
 *                                                                  0 => [ 'value' => '10:00 - 12:00' ],
 *                                                                  1 => [ 'value' => '12:00 - 14:00' ]
 *                                                                ]
 *                                           ],
 *                                      ...
 *                                      6 => [
 *                                                'full_shortcode' => '[checkbox* term_and_condition use_label_element default:on "I Accept term and conditions@@accept"]',
 *                                                'type' => 'checkbox*',
 *                                                'name' => 'term_and_condition',
 *                                                'options' => 'use_label_element default:on',
 *                                                'values_str' => '"I Accept term and conditions@@accept"',
 *                                                'values_arr' => [
 *                                                                  0 => [  'title' => 'I Accept term and conditions',
 *                                                                          'value' => 'accept',
 *                                                                       ]
 *                                                                ]
 *                                            ],
 *                                      7 => [
 *                                               full_shortcode = "[checkbox fee]"                                      <==  !!! No value !!!
 *                                               type = "checkbox"
 *                                               name = "fee"
 *                                               options = ""
 *                                               values_str = ""
 *                                               values_arr = [ ]                                                       <==  !!! Empty,  if no value !!!
 *                                           ]
 *                               ]
 *
 */
function wpbc_parse_form( $booking_form_configuration ){

	/**
	 * NON-standard shortcodes:
	 *          $regex =                '%\[\s*(' . $types . ')                       (\s+[a-zA-Z][0-9a-zA-Z:._-]*) ([-0-9a-zA-Z:#_/|\s]*)?((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
	 *		    $regex_start_end_time = '%\[\s*(country[*]?|starttime[*]?|endtime[*]?)(\s*[a-zA-Z]*[0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)*((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
	 *		    $regex_submit_ =        '%\[\s*submit                                                             (\s[-0-9a-zA-Z:#_/\s]*)?    (\s+(?:"[^"]*"|\'[^\']*\'))?\s*\]%';
	 */

	// Parse select  shortcodes
	$rx_shortcode_types   = 'text[*]?|email[*]?|coupon[*]?|time[*]?|textarea[*]?|select[*]?|checkbox[*]?|radio[*]?|acceptance|captchac|captchar|file[*]?|quiz';
	// $rx_shortcode_types= 'select[*]?|checkbox[*]?|radio[*]?';
	$rx_shortcode_name    = '\s+[a-zA-Z][0-9a-zA-Z:._-]*';
	$rx_shortcode_options = '[-0-9a-zA-Z:#_/|\s]*';
	$rx_shortcode_values  = '(?:\s*(?:"[^"]*"|\'[^\']*\'))*';
	$regex = '%\[\s*(' . $rx_shortcode_types . ')(' . $rx_shortcode_name . ')(' . $rx_shortcode_options . ')?(' . $rx_shortcode_values . ')?\s*\]%';
	preg_match_all( $regex, $booking_form_configuration, $found_shortcodes, PREG_SET_ORDER );


	/**
	 *        [   [0] => [radio name id:htmlID class:class_name default:Default Value use_label_element label_first "Title A@@1" "Title Booking@@2" ""Title C"@@3" "Other title@@4"]
	 *            [1] => radio
	 *            [2] =>  name
	 *            [3] =>  id:htmlID class:class_name default:Default Value use_label_element label_first
	 *            [4] => "Title A@@1" "Title Booking@@2" ""Title C"@@3" "Other title@@4"
	 *        ]
	 *    OR
	 *        [
	 *           [0] => [select* rangetime "Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00" "12:00 PM - 02:00 PM@@12:00 - 14:00" "02:00 PM - 04:00 PM@@14:00 - 16:00" "04:00 PM - 06:00 PM@@16:00 - 18:00" "06:00 PM - 08:00 PM@@18:00 - 20:00"]
	 *           [1] => select*
	 *           [2] =>  rangetime
	 *           [3] =>
	 *           [4] => "Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00" "12:00 PM - 02:00 PM@@12:00 - 14:00" "02:00 PM - 04:00 PM@@14:00 - 16:00" "04:00 PM - 06:00 PM@@16:00 - 18:00" "06:00 PM - 08:00 PM@@18:00 - 20:00"
	 *        ]
	 */
	$form_shortcodes = array();
	foreach ( $found_shortcodes as $found_shortcode ) {

		$shortcode_config = array();
		$shortcode_config['full_shortcode'] = trim( $found_shortcode[0] );
		$shortcode_config['type']           = trim( $found_shortcode[1] );
		$shortcode_config['name']           = trim( $found_shortcode[2] );
		$shortcode_config['options']        = trim( $found_shortcode[3] );
		$shortcode_config['values_str']         = trim( $found_shortcode[4] );
		$shortcode_config['values_arr'] = array();

		if ( ! empty( $shortcode_config['values_str'] ) ) {
			$shortcode_config['values_arr'] = wpbc_parse_form_shortcode_values( $shortcode_config['values_str'] );
		}

		$form_shortcodes[] = $shortcode_config;
 	}

	return $form_shortcodes;
}


	function wpbc_parse_form_shortcode_values( $shortcode_values ){

		$values_arr = array();

		// $shortcode_values  == '"Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00"... '
		$regex = '%(?:"[^"]*"|\'[^\']*\')%';
		preg_match_all( $regex, $shortcode_values, $found_values, PREG_PATTERN_ORDER );

		/**
		 *	$found_values == 	[   0 => [  [0] => "Full Day@@00:00 - 24:00"
		 *						            [1] => "10:00 AM - 12:00 PM@@10:00 - 12:00"
		 *						            [2] => "12:00 PM - 02:00 PM@@12:00 - 14:00"
		 *						            [3] => "02:00 PM - 04:00 PM@@14:00 - 16:00"
		 *						            [4] => "04:00 PM - 06:00 PM@@16:00 - 18:00"
		 *						            [5] => "06:00 PM - 08:00 PM@@18:00 - 20:00"
		 *						         ]
		 *						]
		 */

		foreach ( $found_values as $found_value ) {

			$found_value = array_map(	function ( $value ) {
											$value = trim( $value );
											$value = trim( $value, '"' );
											$value = trim( $value, "'" );
											$value = explode( '@@', $value );
											return $value;
										}
										, $found_value
									);

			foreach ( $found_value as $f_v ) {
				$value_config_arr = array();
				if ( 1 == count( $f_v )  ) {
					$value_config_arr['value'] = $f_v[0];
				}
				if ( 2 == count( $f_v )  ) {
					$value_config_arr['title'] = $f_v[0];
					$value_config_arr['value'] = $f_v[1];
				}
				$values_arr[] = $value_config_arr;
			}
		}

		return $values_arr;
	}


// ====================================================================================================================

/**
 * Get parsed array of shortcodes with specific Name
 *
 *    Example:
 *              $form_shortcodes = wpbc_parse_form__get_shortcodes_with_name( 'rangetime', $booking_form_fields );
 *
 * @param string $shortcode_name             'rangetime'
 * @param string $booking_form_configuration '[calendar]... <p>Time: [select* rangetime "Full Day@@00:00 - 24:00" "12:00 - 14:00"] ...'
 *
 * @return array      = [
 *                            [0] =>   [
 *                                        [full_shortcode] => [select rangetime  "Full Day@@00:00 - 24:00" "12:00 - 14:00"]
 *                                        [type] => select
 *                                        [name] => rangetime
 *                                        [options] =>
 *                                        [values_str] => "Full Day@@00:00 - 24:00" "12:00 - 14:00"
 *                                        [values_arr] => [
 *                                                            [0] => [
 *                                                                        [title] => Full Day
 *                                                                        [value] => 00:00 - 24:00
 *                                                                   ]
 *                                                            [1] => [
 *                                                                        [value] => 12:00 - 14:00
 *                                                                   ]
 *                                                        ]
 *                                    ],
 *                            [1] =>  [
 *                                        [full_shortcode] => [select rangetime  "14:00 - 16:00" "16:00 - 18:00" "18:00 - 20:00"]
 *                                        [type] => select
 *                                        [name] => rangetime
 *                                        [options] =>
 *                                        [values_str] => "14:00 - 16:00" "16:00 - 18:00" "18:00 - 20:00"
 *                                        [values_arr] => [
 *                                                            [0] => [
 *                                                                        [value] => 14:00 - 16:00
 *                                                                   ]
 *                                                            [1] => [
 *                                                                        [value] => 16:00 - 18:00
 *                                                                   ]
 *                                                            [2] => [
 *                                                                        [value] => 18:00 - 20:00
 *                                                                   ]
 *                                                        ]
 *                                    ]
 *                             , ...
 *                      ]
 */
function wpbc_parse_form__get_shortcodes_with_name( $shortcode_name, $booking_form_configuration ) {

	$form_shortcodes = wpbc_parse_form( $booking_form_configuration );

	$return_shortcode = array();
	foreach ( $form_shortcodes as $form_shortcode ) {
		if ( $shortcode_name == $form_shortcode['name'] ) {
			$return_shortcode[] = $form_shortcode;
		}
	}

	return $return_shortcode;
}


/**
 * Parse booking form  and get value of first  shortcode with  this name. Be careful with conditional  form:
 *              where existing several shortcodes with  the same name. This function return  only  first  shortcode!
 *
 *   Example:
 *              $form_shortcodes = wpbc_parse_form__get_first_shortcode_values( 'rangetime', $booking_form_fields );
 *
 * @param string $shortcode_name                        'rangetime'
 * @param string $booking_form_configuration            '[calendar]... <p>Time: [select* rangetime "Full Day@@00:00 - 24:00" "10:00 AM - 12:00 PM@@10:00 - 12:00"] ...'
 *
 * @return false | array    FALSE  if no field   OR  = [
 *                                                            [0] => [
 *                                                                        [title] => Full Day
 *                                                                        [value] => 00:00 - 24:00
 *                                                                   ]
 *                                                            [1] => [
 *                                                                        [title] => 10:00 AM - 12:00 PM
 *                                                                        [value] => 10:00 - 12:00
 *                                                                   ]
 *                                                                   , ...
 *                                                      ]
 */
function wpbc_parse_form__get_first_shortcode_values( $shortcode_name, $booking_form_configuration ) {

	$form_shortcodes = wpbc_parse_form( $booking_form_configuration );

	$return_shortcode = array();
	foreach ( $form_shortcodes as $form_shortcode ) {
		if ( $shortcode_name == $form_shortcode['name'] ) {
			return $form_shortcode['values_arr'];
		}
	}

	return false;
}