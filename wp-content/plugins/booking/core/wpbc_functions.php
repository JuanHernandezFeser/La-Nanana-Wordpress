<?php 
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Support Functions
 * @category Functions
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 29.09.2015
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


	// <editor-fold     defaultstate="collapsed"                        desc="   ==   B o o k i n g   F o r m s   ==   "  >


		/**
		 * Parse  "text^firstname1^John~..."  ->  [ 'firstname':[name:"firstname", value:"John" ...] ... ]   -   Parse (DB) form_data and get   fields array values
		 *
		 * @param string $form_data   	- formatted booking form  data from database,  like this: 'text^selected_short_dates_hint15^21/02/2023 - 24/02/2023~text^days_number_hint15^4~text^cost_hint15^&amp;#36;400.00~text^deposit_hint15^&amp;#36;40.00~text^standard_bk_cost15^100~...'
		 * @param int    $resource_id 	- ID of booking resource
		 * @param array  $params 	  	- (optional)  default: array()		define here what field we need to get	array( 'get' => 'value' )
		 *
		 * @return array
		 * 					[
		 * 					      rangetime = [
		 * 					                      type          = "select-multiple"
		 * 					                      original_name = "rangetime3[]"
		 * 					                      name          = "rangetime"
		 * 					                      value         = "12:00 - 14:00"
		 * 					                  ]
		 * 					      name = [
		 * 					                      type = "text"
		 * 					                      original_name = "name3"
		 * 					                      name = "name"
		 * 					                      value = "test 1"
		 * 					              ]
		 * 					      secondname = [...]
		 * 					      email = [...]
		 * 					 ]
		 *
		 *   Example 1:
		 *						   $structured_booking_data_arr = wpbc_get_parsed_booking_data_arr( $this_booking->form, $resource_id, array( 'get' => 'value' ) );
		 *         		output:    [  	"rangetime" : 	"12:00 - 14:00",
		 *                              "name":			"John" ,
		 * 								"secondname" : 	"Smith" , 		"email" : "test@wpbookingcalendar.com" , "visitors" : "1" ... ]
		 *
		 *   Example 2:
		 *				 		   $structured_booking_data_arr = wpbc_get_parsed_booking_data_arr( $this_booking->form, $resource_id );
		 *   		 	output:    [  rangetime = [
		 * 					                      type          = "select-multiple"
		 * 					                      original_name = "rangetime3[]"
		 * 					                      name          = "rangetime"
		 * 					                      value         = "12:00 - 14:00"
		 * 					                  ]
		 * 					      		name = [
		 * 					                      type = "text"
		 * 					                      original_name = "name3"
		 * 					                      name = "name"
		 * 					                      value = "test 1"
		 * 					              ]
		 * 					      		secondname = [...]
		 * 					      		email = [...]
		 * 					 		]
 */
		function wpbc_get_parsed_booking_data_arr( $form_data, $resource_id = 1, $params = array( ) ) {

			$booking_data_arr = array();

			if ( ! empty( $form_data ) ) {

				$fields_arr = explode( '~', $form_data );

				foreach ( $fields_arr as $field ) {

					if ( false === strpos( $field, '^' ) ) {
						break;            //FixIn: 9.8.10.2
					}

					list( $field_type, $field_original_name, $field_value ) = explode( '^', $field );

					$field_name = $field_original_name;

					// Is this multi select in checkboxes: [checkbox* ConducenteExtra "Si" "No"]   =>   [... 6: "checkbox^ConducenteExtra4[]^Si", 7: "checkbox^ConducenteExtra4[]^" ... ]
					$is_multi_options = ( '[]' == substr( $field_name, - 2 ) );

					$minus_additional = ( '[]' == substr( $field_name, - 2 ) ) ? 2 : 0;
					$field_name       = ( $minus_additional > 0 ) ? substr( $field_name, 0, - $minus_additional ) : $field_name;


					$minus_additional = ( strval( $resource_id ) == substr( $field_name, - 1 * ( strlen( strval( $resource_id ) ) ) ) )
						? strlen( strval( $resource_id ) ) : 0;
					$field_name       = ( $minus_additional > 0 ) ? substr( $field_name, 0, - $minus_additional ) : $field_name;

					if ( ( 'checkbox' === $field_type ) && ( 'true' === $field_value ) ) {
						//$field_value = strtolower( __( 'Yes', 'booking' ) );
						$field_value = 'true';
					}
					if ( ( 'checkbox' === $field_type ) && ( 'false' === $field_value ) ) {
						//$field_value = strtolower( __( 'No', 'booking' ) );
						$field_value = 'false';
					}

					if ( ! isset( $booking_data_arr[ $field_name ] ) ) {
						$booking_data_arr[ $field_name ] = array(
																	'type'          => $field_type,
																	'original_name' => $field_original_name,
																	'name'          => $field_name,
																	'value'         => array( $field_value )
																);
					} else {
						// All values we save as array values,  for situations  of MULTI options:
						//   [checkbox* ConducenteExtra "Si" "No"]   =>   [... 6: "checkbox^ConducenteExtra4[]^Si", 7: "checkbox^ConducenteExtra4[]^" ... ]
						$booking_data_arr[ $field_name ]['value'][] = $field_value;
					}
				}

				// Convert arrays to string
				foreach ( $booking_data_arr as $field_name => $field_structure_arr ) {									//FixIn: 9.8.9.1
					$field_structure_arr['value'] = array_filter( $field_structure_arr['value'], function ( $v ) {
																													return ( $v !== '' );	// Remove All '' entries
																												} );
					$booking_data_arr[ $field_name ]['value'] = implode( ', ', $field_structure_arr['value'] );
				}


				if ( isset( $params['get'] ) ) {
					/**
					 * Now get only values or other fields:  [
					 *                                                "rangetime" : "12:00 - 14:00",
					 *                                              "name":"test 1" ,
					 *                                              "secondname" : "test 1" ,
					 *                                              "email" : "test@wpbookingcalendar.com" ,
					 *                                              "visitors" : "1"
					 *                                        ...
					 *                                        ]
					 */

					foreach ( $booking_data_arr as $field_name => $field_structure_arr ) {

						if ( isset( $field_structure_arr[ $params['get'] ] ) ) {
							$booking_data_arr[ $field_name ] = $field_structure_arr[ $params['get'] ];
						} else {
							$booking_data_arr[ $field_name ] = '-';
						}
					}
				}
			}

			return $booking_data_arr;
		}


		/**
		 *  Convert  [ ... ]  -> "text^firstname1^John~..."   - encoded booking data string (Usually  for inserting into DB),  from  parsed booking data array
		 *
		 * @param array $booking_data_arr	- parsed booking data.
		 * @param int $resource_id			- ID of booking resource  - In case,  if we need to  RE-UPDATE booking resource,  Otherwise skip  it
		 *
		 * @return string
		 */
		function wpbc_encode_booking_data_to_string( $booking_data_arr, $resource_id = 0 ) {

			$fields_arr = array();

			foreach ( $booking_data_arr as $fields ) {

				if ( ! empty( $resource_id ) ) {
					$fields_arr[] =         $fields['type']
									. '^' . $fields['name'] . $resource_id  . ( ( '[]' == substr( $fields['original_name'], - 2 ) ) ? '[]' : '' )
									. '^' . $fields['value'];
				} else {
					$fields_arr[] = $fields['type'] . '^' . $fields['original_name'] . '^' . $fields['value'];
				}

			}

			$form_data = implode( '~', $fields_arr );

			return $form_data;
		}


		// -------------------------------------------------------------------------------------------------------------

		/**
		 * Get Time fields  in booking form_data
		 *
		 * @param string $form_data   	- formatted booking form  data from database,  like this: 'text^selected_short_dates_hint15^21/02/2023 - 24/02/2023~text^days_number_hint15^4~text^cost_hint15^&amp;#36;400.00~text^deposit_hint15^&amp;#36;40.00~text^standard_bk_cost15^100~...'
		 * @param int    $resource_id 	- ID of booking resource
		 * @param array  $params 	  	- (optional)  default: array()		define here what field we need to get	array( 'get' => 'value' )
		 *
		 * @return array	[ "18:00:00", "20:00:00" ]		<- default
		 *
		 *   Example #1:          $times_his_arr = wpbc_get_times_his_arr__in_form_data( $form_data, $resource_id);														--> [ "18:00:01", "20:00:02" ]
		 *        same:           $times_his_arr = wpbc_get_times_his_arr__in_form_data( $form_data, $resource_id, array( 'get' => 'times_his_arr' ) );					--> [ "18:00:01", "20:00:02" ]
		 *   Example #2:    $time_as_seconds_arr = wpbc_get_times_his_arr__in_form_data( $form_data, $resource_id, array( 'get' => 'time_as_seconds_arr' ) );			--> [ 64800, 72000 ]
		 *   Example #4:    $booking_data_arr    = wpbc_get_times_his_arr__in_form_data( $form_data, $resource_id, array( 'get' => 'structured_booking_data_arr' ) );	--> [ name = "John", secondname = "Smith", email = "john.smith@server.com", visitors = "2",... ]
		 *
		 *   Example #5:    $booking_data_arr    = wpbc_get_times_his_arr__in_form_data( $form_data, $resource_id, array( 'get' => 'all' ) );
		 *                    --> [
		 *							  'structured_booking_data_arr' => [ name = "John", secondname = "Smith", email = "john.smith@server.com", visitors = "2",... ]
		 *							  'time_as_seconds_arr' 		=> [ 64800, 72000 ]
		 *							  'time_as_his_arr' 			=> [ "18:00:01", "20:00:02" ]
		 * 						  ]
		 */
		function wpbc_get_times_his_arr__in_form_data( $form_data, $resource_id = 1, $params = array() ) {

			$defaults = array(
								'get' => 'times_his_arr'
							);
			$params   = wp_parse_args( $params, $defaults );


			// Get Time from  booking form
			$local_params = array();
			/**
			 * Get parsed booking form:                 = [ name = "John", secondname = "Smith", email = "john.smith@server.com", visitors = "2",... ]
			 */
			$local_params['structured_booking_data_arr'] = wpbc_get_parsed_booking_data_arr( $form_data, $resource_id, array( 'get' => 'value' ) );
			// $local_params['all_booking_data_arr']     = wpbc_get_parsed_booking_data_arr( $form_data, $resource_id );

			//  Important! : [ 64800, 72000 ]
			$local_params['time_as_seconds_arr'] = wpbc_get_in_booking_form__time_to_book_as_seconds_arr( $local_params['structured_booking_data_arr'] );

			// [ "18:00:00", "20:00:00" ]
			$time_as_seconds_arr    = $local_params['time_as_seconds_arr'];
			$time_as_seconds_arr[0] = ( 0 != $time_as_seconds_arr[0] ) ? $time_as_seconds_arr[0] + 1 : $time_as_seconds_arr[0];                 // set check  in time with  ended 1 second
			$time_as_seconds_arr[1] = ( ( 24 * 60 * 60 ) != $time_as_seconds_arr[1] ) ? $time_as_seconds_arr[1] + 2 : $time_as_seconds_arr[1];  // set check out time with  ended 2 seconds

			// [ '16:00:01', '18:00:02' ]
			$local_params['times_his_arr'] = array(
													  wpbc_transform__seconds__in__24_hours_his( $time_as_seconds_arr[0] ),
													  wpbc_transform__seconds__in__24_hours_his( $time_as_seconds_arr[1] )
													);
			if (
  				   ( isset( $params['get'] ) )
				&& ( isset( $local_params[ $params['get'] ] ) )
			) {
				return $local_params[ $params['get'] ];
			}

			return $local_params;
		}


		// -------------------------------------------------------------------------------------------------------------

		/**
		 * Get readable booking form data.	 Escape values here, as well!
		 *
		 *        - 1.    <= BS : 'Booking form show' configuration    from standard form in versions up to  Business Small version ,
		 *        - 2     >= BM : If form data has field of custom form, then from custom form configuration,
		 *        - 3     >= BM : Otherwise if resource has default custom  booking form,  then  from  this default custom  booking form
		 *        - 4      = MU :  specific form of specific WP User
		 *        - 5   finally : simple standard form
		 *
		 * @param string $form_data
		 * @param int $resource_id
		 *
		 * @return string
		 */
		function wpbc_get__booking_form_data__show( $form_data, $resource_id = 1 , $params = array() ) {

			$defaults = array(
							  'is_replace_unknown_shortcodes' => true,
							  'unknown_shortcodes_replace_by' => ''
						);
			$params   = wp_parse_args( $params, $defaults );

			$booking_form_show = wpbc_get__booking_form_data_configuration( $resource_id, $form_data );

			$booking_data_arr  = wpbc_get_parsed_booking_data_arr( $form_data, $resource_id, array( 'get' => 'value' ) );

			foreach ( $booking_data_arr as $key_param => $value_param ) {                                  					//FixIn: 6.1.1.4

				$value_param = esc_html( $value_param );																	//FixIn: 9.7.4.1	-	escape coded html/xss
				$value_param = esc_html( html_entity_decode( $value_param ) );
				$value_param = nl2br($value_param);                                             							// Add BR instead if /n elements		//FixIn: 9.7.4.2
				$value_param = wpbc_string__escape__then_convert__n_amp__html( $value_param );

				$value_param = wpbc_replace__true_false__to__yes_no( $value_param );												//FixIn: 9.8.9.1

				if (
						( gettype( $value_param ) != 'array' )
					 && ( gettype( $value_param ) != 'object' )
				) {
					$booking_form_show = str_replace( '[' . $key_param . ']', $value_param, $booking_form_show );
				}
			}

			if ($params['is_replace_unknown_shortcodes']) {
				// Remove all shortcodes, which is not replaced early.
				$booking_form_show = preg_replace( '/[\s]{0,}\[[a-zA-Z0-9.,-_]{0,}\][\s]{0,}/', $params['unknown_shortcodes_replace_by'], $booking_form_show );        //FixIn: 6.1.1.4
			}

			$booking_form_show = str_replace( "&amp;", '&', $booking_form_show );											//FixIn:7.1.2.12



			return $booking_form_show;
		}


		/**
		 * Get name of custom  booking form,  if it was used in form  data OR booking resource use this default custom form
		 *
		 * @param int $resource_id
		 * @param string $form_data		Form  data here, required in >= BM, because such  form_data can contain fields about used custom booking form
		 *
		 * @return string
		 */
		function wpbc_get__custom_booking_form_name( $resource_id = 1, $form_data = '' ) {

			$my_booking_form_name = '';

			if ( class_exists( 'wpdev_bk_biz_m' ) ) {

				if ( false !== strpos( $form_data, 'wpbc_custom_booking_form' . $resource_id . '^' ) ) {                        //FixIn: 9.4.3.12

					$custom_booking_form_name = substr( $form_data, strpos( $form_data, 'wpbc_custom_booking_form' . $resource_id . '^' ) + strlen( 'wpbc_custom_booking_form' . $resource_id . '^' ) );

					if ( false !== strpos( $custom_booking_form_name, '~' ) ) {
						$custom_booking_form_name = substr( $custom_booking_form_name, 0, strpos( $custom_booking_form_name, '~' ) );
						$my_booking_form_name     = $custom_booking_form_name;
					}

				} else {

					// BM :: Get default Custom Form  of Resource
					$my_booking_form_name = apply_bk_filter( 'wpbc_get_default_custom_form', 'standard', $resource_id );
				}

				if ( 'standard' == $my_booking_form_name ) {
					$my_booking_form_name = '';
				}
			}

			return $my_booking_form_name;
		}


		/**
		 * Get configuration  of 'BOOKING FORM   F I E L D S   SHORTCODES'  from  -  Booking > Settings > Form page
		 *
		 *        - 1.    <= BS : 'Booking form show' configuration    from standard form in versions up to  Business Small version ,
		 *        - 2     >= BM : If form data has field of custom form, then from custom form configuration,
		 *        - 3     >= BM : Otherwise if resource has default custom  booking form,  then  from  this default custom  booking form
		 *        - 4      = MU :  specific form of specific WP User
		 *        - 5   finally : simple standard form
		 *
		 * @param int $resource_id
		 * @param string $form_name						Name of custom booking form, required in >= BM
		 *
		 * @return string
		 *
		 *   Example 1:     	$booking_form = wpbc_get__booking_form_fields__configuration( 1 );						<-  Load STANDARD booking form
		 *   Example 2:     	$booking_form = wpbc_get__booking_form_fields__configuration( 1 , 'standard' );			<-  Load STANDARD booking form
		 *   Example 3:     	$booking_form = wpbc_get__booking_form_fields__configuration( 1 , 'my_custom_form' );	<-  Load CUSTOM  booking form  with name 'my_custom_form'
		 *   Example 4:     	$booking_form = wpbc_get__booking_form_fields__configuration( 1 , '' );					<-  Load CUSTOM booking form,  which  is DEFAULT for RESOURCE
		 *   Example 4:     	$booking_form = wpbc_get__booking_form_fields__configuration( 10 );						<-  If resource ID = 10  belong to REGULAR User,  then  load  booking form  for this REGULAR USER
		 *
		 */
		function wpbc_get__booking_form_fields__configuration( $resource_id = 1, $form_name = 'standard' ) {

			if ( ! class_exists( 'wpdev_bk_personal' ) ) {
				$booking_form_configuration = wpbc_get_free_form_fields_configuration();
			} else {
				$booking_form_configuration = get_bk_option( 'booking_form' );

				if ( class_exists( 'wpdev_bk_biz_m' ) ) {

					if ( $form_name != 'standard' ) {

						$booking_form_configuration = apply_bk_filter( 'wpdev_get_booking_form', $booking_form_configuration, $form_name );
					}

					// Get default Custom Form  for resource,  if $form_name == ''		wpbc_get__booking_form_fields__configuration(1,'')
					if ( empty( $form_name ) ) {

						$resource_default_custom_form_name = apply_bk_filter( 'wpbc_get_default_custom_form', 'standard', $resource_id );

						$booking_form_configuration = apply_bk_filter( 'wpdev_get_booking_form', $booking_form_configuration, $resource_default_custom_form_name );
					}

					//MU :: if resource of "Regular User" - then  GET STANDARD user form ( if ( get_bk_option( 'booking_is_custom_forms_for_regular_users' ) !== 'On' ) )
					$booking_form_configuration = apply_bk_filter( 'wpbc_multiuser_get_booking_form_fields_configuration_of_regular_user', $booking_form_configuration, $resource_id, $form_name );    //FixIn: 8.1.3.19
				}
			}

			// Language
			$booking_form_configuration = apply_bk_filter( 'wpdev_check_for_active_language', $booking_form_configuration );

			return $booking_form_configuration;
		}


		/**
		 * Get configuration  of 'BOOKING FORM DATA'  from  -  Booking > Settings > Form page
		 *
		 *        - 1.    <= BS : 'Booking form show' configuration    from standard form in versions up to  Business Small version ,
		 *        - 2     >= BM : If form data has field of custom form, then from custom form configuration,
		 *        - 3     >= BM : Otherwise if resource has default custom  booking form,  then  from  this default custom  booking form
		 *        - 4      = MU :  specific form of specific WP User
		 *        - 5   finally : simple standard form
		 *
		 * @param int $resource_id
		 * @param string $form_data		Form  data here, required in >= BM, because such  form_data can contain fields about used custom booking form
		 *
		 * @return string
		 */
		function wpbc_get__booking_form_data_configuration( $resource_id = 1, $form_data = '' ) {

			if ( ! class_exists( 'wpdev_bk_personal' ) ) {

				$booking_form_show = wpbc_get_free_booking_show_form();

			} else {

				$booking_form_show = get_bk_option( 'booking_form_show' );
				$booking_form_show = wpbc_bf__replace_custom_html_shortcodes( $booking_form_show );

				if ( class_exists( 'wpdev_bk_biz_m' ) ) {

					if ( false !== strpos( $form_data, 'wpbc_custom_booking_form' . $resource_id . '^' ) ) {                        //FixIn: 9.4.3.12

						$custom_booking_form_name = substr( $form_data, strpos( $form_data, 'wpbc_custom_booking_form' . $resource_id . '^' ) + strlen( 'wpbc_custom_booking_form' . $resource_id . '^' ) );
						if ( false !== strpos( $custom_booking_form_name, '~' ) ) {
							$custom_booking_form_name = substr( $custom_booking_form_name, 0, strpos( $custom_booking_form_name, '~' ) );
						}
						$booking_form_show    = apply_bk_filter( 'wpdev_get_booking_form_content', $booking_form_show, $custom_booking_form_name );
						$my_booking_form_name = $custom_booking_form_name;
					} else {

						// BM :: Get default Custom Form  of Resource
						$my_booking_form_name = apply_bk_filter( 'wpbc_get_default_custom_form', 'standard', $resource_id );
						if ( ( $my_booking_form_name != 'standard' ) && ( ! empty( $my_booking_form_name ) ) ) {
							$booking_form_show = apply_bk_filter( 'wpdev_get_booking_form_content', $booking_form_show, $my_booking_form_name );
						}
					}

					//MU :: if resource of "Regular User" - then  GET STANDARD user form ( if ( get_bk_option( 'booking_is_custom_forms_for_regular_users' ) !== 'On' ) )
					$booking_form_show = apply_bk_filter( 'wpbc_multiuser_get_booking_form_show_of_regular_user', $booking_form_show, $resource_id, $my_booking_form_name );    //FixIn: 8.1.3.19
				}
			}

			// Language
			$booking_form_show = apply_bk_filter( 'wpdev_check_for_active_language', $booking_form_show );

			return $booking_form_show;
		}


		/**
		 * Get (in Free version) configuration  of 'BOOKING FORM DATA'  from  -  Booking > Settings > Form page
		 *
		 * @return false|mixed
		 */
		function wpbc_get_free_booking_show_form() {

			$booking_form_show = apply_bk_filter( 'wpbc_get_free_booking_show_form' );
			return  $booking_form_show;
		}

		// Get form
		function wpbc_get_free_form_fields_configuration() {

			$my_form = apply_bk_filter( 'wpbc_get_free_booking_form_shortcodes' );

			return  $my_form;
		}

		// -------------------------------------------------------------------------------------------------------------


		/**
		 * Replace resource_ID of booking  in 'form_data'
		 * Useful, when we need to save booking from one resource into another.
		 *
		 * @param $booking__form_data__str		'select-multiple^rangetime2[]^18:00 - 20:00~checkbox^fee2[]^true~text^name2^John~text^secondname2^Smith...'
		 * @param $new_resource_id				10
		 * @param $old_resource_id				2
		 *
		 * @return string						'select-multiple^rangetime10[]^18:00 - 20:00~checkbox^fee10[]^true~text^name10^John~text^secondname10^Smith...'
		 */
		function wpbc_get__form_data__with_replaced_id( $booking__form_data__str, $new_resource_id, $old_resource_id ) {

			$all_booking_data_arr = wpbc_get_parsed_booking_data_arr( $booking__form_data__str, $old_resource_id );

			$new__form_data__str  = wpbc_encode_booking_data_to_string( $all_booking_data_arr, $new_resource_id );

			return $new__form_data__str;
		}


		/**
		 * Get arr   of all Fields Names 	from  all booking forms  (including custom)
		 *
		 * @return array   = [
								 0: [  name = "standard",  num =  8,  listing = [ ... ]    ],
								 1: [
									  name = "minimal"
									  num = 7
									  listing = [
												   labels = [
																0 = " adults"
																1 = " children"
																2 = " infants"
																3 = " gender"
																4 = " full_name"
																5 = " email"
																6 = " phone"
												   fields = {array[7]}
																0 = " adults"
																1 = " children"
																2 = " infants"
																3 = " gender"
																4 = " full_name"
																5 = " email"
																6 = " phone"
												   fields_type = {array[7]}
																0 = "select"
																1 = "select"
																2 = "select"
																3 = "radio"
																4 = "text"
																5 = "email"
																6 = "text"
												]
									]
								 2: [], ...
		 * 					 ]
		 */
		function wpbc_get__in_all_forms__field_names_arr() {

			$booking_form_fields_arr   = array();
			$booking_form_fields_arr[] = array( 'name' => 'standard', 'form' => wpbc_bf__replace_custom_html_shortcodes( get_bk_option( 'booking_form' ) ), 'content' => wpbc_bf__replace_custom_html_shortcodes( get_bk_option( 'booking_form_show' ) ) );

			/**
			 * Get custom booking form configurations: [
			 *                                            [ name = "minimal",
			 *                                              form = "[calendar]...",
			 *                                              content = "<div class="payment-content-form"> [name] ..."
			 * 											  ],
			 * 											  ...
			 * 										   ]
			 */
			$is_can = apply_bk_filter( 'multiuser_is_user_can_be_here', true, 'only_super_admin' );
			if ( ( $is_can ) || ( get_bk_option( 'booking_is_custom_forms_for_regular_users' ) === 'On' ) ) {
				$booking_forms_extended = get_bk_option( 'booking_forms_extended' );
				$booking_forms_extended = maybe_unserialize( $booking_forms_extended );
				if (  false !== $booking_forms_extended ) {
					foreach ( $booking_forms_extended as $form_extended ) {
						$booking_form_fields_arr[] = $form_extended;
					}
				}
			}

			foreach ( $booking_form_fields_arr as $form_key => $booking_form_element ) {

				$booking_form = $booking_form_element['form'];

				$types = 'text[*]?|email[*]?|time[*]?|textarea[*]?|select[*]?|checkbox[*]?|radio|acceptance|captchac|captchar|file[*]?|quiz';
				$regex = '%\[\s*(' . $types . ')(\s+[a-zA-Z][0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)?((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
				$regex2 = '%\[\s*(country[*]?|starttime[*]?|endtime[*]?)(\s*[a-zA-Z]*[0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)*((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
				$fields_count = preg_match_all($regex, $booking_form, $fields_matches) ;
				$fields_count2 = preg_match_all($regex2, $booking_form, $fields_matches2) ;

				//Gathering Together 2 arrays $fields_matches  and $fields_matches2
				foreach ($fields_matches2 as $key => $value) {
					if ($key == 2) $value = $fields_matches2[1];
					foreach ($value as $v) {
						$fields_matches[$key][count($fields_matches[$key])]  = $v;
					}
				}
				$fields_count += $fields_count2;

				$booking_form_fields_arr[ $form_key ]['num']     = $fields_count;
				$booking_form_fields_arr[ $form_key ]['listing'] = array();                //$fields_matches;

				$fields_matches[1] = array_map( 'trim', $fields_matches[1] );
				$fields_matches[2] = array_map( 'trim', $fields_matches[2] );

				$booking_form_fields_arr[ $form_key ]['listing']['labels'] = array_map( 'ucfirst', $fields_matches[2] );
				$booking_form_fields_arr[ $form_key ]['listing']['fields'] = $fields_matches[2] ;

				foreach ( $fields_matches[1] as $key_fm => $value_fm ) {
					$fields_matches[1][ $key_fm ] = trim( str_replace( '*', '', $value_fm ) );
				}

				$booking_form_fields_arr[ $form_key ]['listing']['fields_type'] = $fields_matches[1];

				// Reset
				unset( $booking_form_fields_arr[ $form_key ]['form'] );
				unset( $booking_form_fields_arr[ $form_key ]['content'] );
			}

			return $booking_form_fields_arr;
		}


		// -------------------------------------------------------------------------------------------------------------


		/**
		 * Get arr with booking form fields values,  after parsing form_data. 		Avoid to  use this function  in a future.
		 *
		 * @param $formdata
		 * @param $bktype
		 * @param $booking_form_show
		 * @param $extended_params
		 *
		 * @return array
		 */
		function wpbc__legacy__get_form_content_arr ( $formdata, $bktype =-1, $booking_form_show ='', $extended_params = array() ) {

			if ( $bktype == -1 ) {
				$bktype = ( function_exists( 'get__default_type' ) ) ? get__default_type() : 1;
			}

			if ( $booking_form_show === '' ) {

				$booking_form_show = wpbc_get__booking_form_data_configuration( $bktype, $formdata );
			}

			$formdata_array = explode('~',$formdata);
			$formdata_array_count = count($formdata_array);
			$email_adress='';
			$name_of_person = '';
			$coupon_code = '';
			$secondname_of_person = '';
			$visitors_count = 1;
			$select_box_selected_items = array();
			$check_box_selected_items = array();
			$all_fields_array = array();
			$all_fields_array_without_types = array();
			$checkbox_value=array();

			for ( $i=0 ; $i < $formdata_array_count ; $i++) {

				if ( empty( $formdata_array[$i] ) ) {
					continue;
				}

				$elemnts = explode('^',$formdata_array[$i]);

				$type = $elemnts[0];
				$element_name = $elemnts[1];
				$value = $elemnts[2];

				//FixIn: 9.7.4.1	-	escape coded html/xss
				$value = esc_html( $value );
				$value = esc_html( html_entity_decode( $value ) );
				$value = nl2br($value);                                             // Add BR instead if /n elements		//FixIn: 9.7.4.2
						// Escaping for timeline popovers and for other places
				$value = wpbc_string__escape__then_convert__n_amp__html( $value );

				$count_pos = strlen( $bktype );

				$type_name = $elemnts[1];
				$type_name = str_replace('[]','',$type_name);
				if ($bktype == substr( $type_name,  -1*$count_pos ) ) $type_name = substr( $type_name, 0, -1*$count_pos ); // $type_name = str_replace($bktype,'',$elemnts[1]);

				if ( ( ($type_name == 'email') || ($type == 'email')  ) && ( empty($email_adress) )   )    $email_adress = $value;  //FixIn: 6.0.1.9
				if ( ($type_name == 'coupon') || ($type == 'coupon')  )             $coupon_code = $value;
				if ( ($type_name == 'name') || ($type == 'name')  )                 $name_of_person = $value;
				if ( ($type_name == 'secondname') || ($type == 'secondname')  )     $secondname_of_person = $value;
				if ( ($type_name == 'visitors') || ($type == 'visitors')  )         $visitors_count = $value;


				if ($type == 'checkbox') {
					if ($value == 'true') {
						$value = strtolower( __( 'yes', 'booking' ) );
					}

					if ($value == 'false') {
						$value = strtolower( __( 'no', 'booking' ) );
					}

					if  ( $value !='' )
						if ( ( isset($checkbox_value[ str_replace('[]','',(string) $element_name) ]) ) && ( is_array($checkbox_value[ str_replace('[]','',(string) $element_name) ]) ) ) {
							$checkbox_value[ str_replace('[]','',(string) $element_name) ][] = $value;
						} else {
							$checkbox_value[ str_replace('[]','',(string) $element_name) ] = array($value);
						}

					$value = '['. $type_name .']';                                  //FixIn: 6.1.1.14
				}

				if ( ( $type == 'select-one') || ( $type == 'select-multiple' )  || ( $type == 'radio' ) ) { // add all select box selected items to return array
					$select_box_selected_items[$type_name] = $value;
				}

				if ( ($type == 'checkbox') && (isset($checkbox_value)) ) {
					if (isset(  $checkbox_value[ str_replace('[]','',(string) $element_name) ] )) {
						if (is_array(  $checkbox_value[ str_replace('[]','',(string) $element_name) ] ))
							$current_checkbox_value = implode(', ', $checkbox_value[ str_replace('[]','',(string) $element_name) ] );
						else
							$current_checkbox_value = $checkbox_value[ str_replace('[]','',(string) $element_name) ] ;
					} else {
						$current_checkbox_value = '';
					}
					$all_fields_array[ str_replace('[]','',(string) $element_name) ] = $current_checkbox_value;
					$all_fields_array_without_types[ substr(   str_replace('[]','',(string) $element_name), 0 , -1*strlen( $bktype ) )  ] = $current_checkbox_value;

					$check_box_selected_items[$type_name] = $current_checkbox_value;
				} else {

					//FixIn: 8.4.2.11
					$all_fields_array_without_types[ substr(   str_replace('[]','',(string) $element_name), 0 , -1*strlen( $bktype ) )   ] = $value;
					/**
					   ['_all_']        => $all_fields_array,        CONVERT to  " AM/PM "
					   ['_all_fields_'] => $all_fields_array_without_types => in " 24 hour " format - for ability correct  calculate Booking > Resources > Advanced cost page.
					 */
					if ( ( $type_name == 'rangetime' ) || ( $type == 'rangetime' ) ) {
						$value = wpbc_time_slot_in_format(  $value );
					}
					$all_fields_array[ str_replace('[]','',(string) $element_name) ] = $value;
					//FixIn: 8.4.2.11

				}
				$is_skip_replace = false;                                           //FixIn: 7.0.1.45
				if ( ( $type == 'radio' ) && empty( $value ) )
						$is_skip_replace = true;
				if ( ! $is_skip_replace ) {
					$booking_form_show = str_replace( '[' . $type_name . ']', $value, $booking_form_show );
				}
			}

			if (! isset($all_fields_array_without_types[ 'booking_resource_id'  ])) $all_fields_array_without_types[ 'booking_resource_id'  ] = $bktype;
			if (! isset($all_fields_array_without_types[ 'resource_id'  ]))         $all_fields_array_without_types[ 'resource_id'  ] = $bktype;
			if (! isset($all_fields_array_without_types[ 'type_id'  ]))             $all_fields_array_without_types[ 'type_id'  ] = $bktype;

			if (! isset($all_fields_array_without_types[ 'type'  ]))                $all_fields_array_without_types[ 'type'  ] = $bktype;
			if (! isset($all_fields_array_without_types[ 'resource'  ]))            $all_fields_array_without_types[ 'resource'  ] = $bktype;

			foreach ($extended_params as $key_param=>$value_param) {
				if (! isset($all_fields_array_without_types[  $key_param  ]))            $all_fields_array_without_types[ $key_param  ] = $value_param;
			}

			foreach ( $all_fields_array_without_types as $key_param=>$value_param) {                                  //FixIn: 6.1.1.4
				if (   ( gettype ( $value_param ) != 'array' )
					&& ( gettype ( $value_param ) != 'object' )
					) {
					$booking_form_show = str_replace( '['. $key_param .']', $value_param ,$booking_form_show);

					$all_fields_array_without_types[ $key_param ] = str_replace( "&amp;", '&', $value_param );					//FixIn:7.1.2.12
				}


			}
			// Remove all shortcodes, which is not replaced early.
			$booking_form_show = preg_replace ('/[\s]{0,}\[[a-zA-Z0-9.,-_]{0,}\][\s]{0,}/', '', $booking_form_show);  //FixIn: 6.1.1.4

			$booking_form_show = str_replace( "&amp;", '&', $booking_form_show );											//FixIn:7.1.2.12

			$return_array = array(
									'content'      => $booking_form_show,
									'email'        => $email_adress,
									'name'         => $name_of_person,
									'secondname'   => $secondname_of_person,
									'visitors'     => $visitors_count,
									'coupon'       => $coupon_code,
									'_all_'        => $all_fields_array,
									'_all_fields_' => $all_fields_array_without_types
								);

			foreach ( $select_box_selected_items as $key => $value ) {
				if ( ! isset( $return_array[ $key ] ) ) {
					$return_array[ $key ] = $value;
				}
			}
			foreach ( $check_box_selected_items as $key => $value ) {
				if ( ! isset( $return_array[ $key ] ) ) {
					$return_array[ $key ] = $value;
				}
			}

			return $return_array;
		}


    // </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Booking details | replace / fields functions  ==  "  >

		/**
		 * Get booking resource title (translated/localized)
		 *
		 * @param $resource_id
		 *
		 * @return string
		 */
		function wpbc_get_resource_title( $resource_id = 1 ) {

			$resource_title = '';

			if ( function_exists( 'wpbc_db__get_resource_title' ) ) {

				$resource_title = wpbc_db__get_resource_title( $resource_id );

				if (! empty($resource_title)) {
					$resource_title = apply_bk_filter( 'wpdev_check_for_active_language', $resource_title );
				}
			}

			return $resource_title;
		}


		/**
		 * Check, if exist booking for this hash. If existed, get Email of this booking
		 *
		 * @param string $booking_hash		- booking hash.
		 * @param string $booking_data_key	- booking field key - default 'email'.
		 *
		 * @return bool 					- booking data field
		 */
		function wpbc_get__booking_data_field__by_booking_hash( $booking_hash, $booking_data_key = 'email' ){								//FixIn: 8.1.3.5

			$return_val = false;

			// $booking_hash = '0d55671fd055fd64423294f89d6b58e6';        	// debugging

			if ( ! empty( $booking_hash ) ) {

				$my_booking_id_type = wpbc_hash__get_booking_id__resource_id( $booking_hash );

				if ( ! empty( $my_booking_id_type ) ) {

					list( $booking_id, $resource_id ) = $my_booking_id_type;

					$booking_data = wpbc_db_get_booking_details( $booking_id );

					if ( ! empty( $booking_data ) ) {

						$booking_details = wpbc_get_booking_different_params_arr( $booking_id, $booking_data->form, $resource_id );

						if ( isset( $booking_details[ $booking_data_key ] ) ) {

							$return_val = $booking_details[ $booking_data_key ];
						}
					}
				}
			}
			return $return_val;
		}


		/**
		 * Get booking details object from DB
		 *
		 * @param $booking_id - int
		 *
		 * @return mixed - booking details or false if not found
		 * Example:
		 * stdClass Object
		* (
			* [booking_id] => 26
			* [trash] => 0
			* [sync_gid] =>
			* [is_new] => 0
			* [status] =>
			* [sort_date] => 2018-02-27 00:00:00
			* [modification_date] => 2018-02-18 12:49:30
			* [form] => text^selected_short_dates_hint3^02/27/2018 - 03/02/2018~text^days_number_hint3^4~text^cost_hint3^40.250&nbsp;&#36;~text^name3^Victoria~text^secondname3^vica~email^email3^vica@wpbookingcalendar.com~text^phone3^test booking ~select-one^visitors3^1
			* [hash] => 0d55671fd055fd64423294f89d6b58e6
			* [booking_type] => 3
			* [remark] =>
			* [cost] => 40.25
			* [pay_status] => 151895097121.16
			* [pay_request] => 0
		* )
		 */
		function wpbc_db_get_booking_details( $booking_id ){																	//FixIn: 8.1.3.5

			global $wpdb;

			$slct_sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}booking WHERE booking_id = %d LIMIT 0,1", $booking_id );

			$sql_results = $wpdb->get_row( $slct_sql );

			if ( ! empty( $sql_results ) ) {
				return $sql_results;
			} else {
				return false;
			}
		}


		/**
		 * Get booking   D a t e s  from DB - array of objects
		 *
		 * @param $booking_id - int
		 *
		 * @return mixed - booking dates array or false if not found
		 *
		 */
		function wpbc_db_get_booking_dates( $booking_id ){

			global $wpdb;

			$sql = $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}bookingdates as dt WHERE dt.booking_id = %d ", $booking_id );

			if ( class_exists( 'wpdev_bk_biz_l' ) ) {
				$sql .= " ORDER BY booking_id, type_id, booking_date ";
			} else {
				$sql .= " ORDER BY booking_id, booking_date ";
			}

			$sql_results = $wpdb->get_results( $sql );

			if ( ! empty( $sql_results ) ) {
				return $sql_results;
			} else {
				return false;
			}
		}


		/**
		 * Get booking modification  date from  DB
		 * @param $booking_id
		 *
		 * @return string
		 */
		function wpbc_db_get_booking_modification_date( $booking_id ){																//FixIn: 8.0.1.7
			global $wpdb;
			$modification_date = ' ' . $wpdb->get_var( $wpdb->prepare( "SELECT modification_date FROM {$wpdb->prefix}booking  WHERE booking_id = %d " , $booking_id ) );
			return $modification_date;
		}


		/**
		 * Get user IP
		 *
		 * @return mixed|string
		 */
		function wpbc_get_user_ip() {

			if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
				$userIP = $_SERVER['HTTP_CLIENT_IP'];
			} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				$userIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} elseif ( isset( $_SERVER['HTTP_X_FORWARDED'] ) ) {
				$userIP = $_SERVER['HTTP_X_FORWARDED'];
			} elseif ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
				$userIP = $_SERVER['HTTP_FORWARDED_FOR'];
			} elseif ( isset( $_SERVER['HTTP_FORWARDED'] ) ) {
				$userIP = $_SERVER['HTTP_FORWARDED'];
			} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
				$userIP = $_SERVER['REMOTE_ADDR'];
			} else {
				$userIP = "";
			}
			return $userIP;
		}

		/**
		 * Get IP of Server
		 *
		 * @return string
		 */
		function wpbc_get_server_ip() {																					//FixIn: 9.8.14.3

			$ip_address = '';
			if ( array_key_exists( 'SERVER_ADDR', $_SERVER ) ) {
				$ip_address = $_SERVER['SERVER_ADDR'];
			} else if ( array_key_exists( 'LOCAL_ADDR', $_SERVER ) ) {
				$ip_address = $_SERVER['LOCAL_ADDR'];
			}

			return $ip_address;
		}

		/**
		 * Get different Booking Fields as array for   replace
		 *
		 * @param int    $booking_id 		- ID of booking                                       // 999
		 * @param string $formdata   		- booking form data content                  // select-one^rangetime4^10:00 - 12:00~text^name4^Jo~text^secondname4^Smith~email^email4^smith@wpbookingcalendar.com~...
		 * @param int $booking_resource_id 	- booking resource type                      // 4
		 *
		 * @return array
		 *
		 *   Example:
		 *              [
		 * 					[booking_id] => 26
		 * 					[id] => 26
		 * 					[days_input_format] => 01.03.2018,02.03.2018,27.02.2018,28.02.2018
		 * 					[days_only_sql] => 2018-02-27,2018-02-28,2018-03-01,2018-03-02
		 * 					[dates_sql] => 2018-02-27 00:00:00, 2018-02-28 00:00:00, 2018-03-01 00:00:00, 2018-03-02 00:00:00
		 * 					[check_in_date_sql] => 2018-02-27 00:00:00
		 * 					[check_out_date_sql] =>  2018-03-02 00:00:00
		 * 					[dates] => 02/27/2018 - 03/02/2018
		 * 					[check_in_date] => 02/27/2018
		 * 					[check_out_date] => 03/02/2018
		 * 					[check_out_plus1day] => 03/03/2018
		 * 					[dates_count] => 4
		 * 					[days_count] => 4
		 * 					[nights_count] => 3
		 * 					[check_in_date_hint] => 02/27/2018
		 * 					[check_out_date_hint] => 03/02/2018
		 * 					[start_time_hint] => 00:00
		 * 					[end_time_hint] => 00:00
		 * 					[selected_dates_hint] => 02/27/2018, 02/28/2018, 03/01/2018, 03/02/2018
		 * 					[selected_timedates_hint] => 02/27/2018, 02/28/2018, 03/01/2018, 03/02/2018
		 * 					[selected_short_dates_hint] => 02/27/2018 - 03/02/2018
		 * 					[selected_short_timedates_hint] => 02/27/2018 - 03/02/2018
		 * 					[days_number_hint] => 4
		 * 					[nights_number_hint] => 3
		 * 					[siteurl] => http://beta
		 * 					[resource_title] => Apartment A
		 * 					[bookingtype] => Apartment A
		 * 					[remote_ip] => 127.0.0.1
		 * 					[user_agent] => Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:58.0) Gecko/20100101 Firefox/58.0
		 * 					[request_url] => http://beta/wp-admin/post.php?post=1473&action=edit
		 * 					[current_date] => 02/18/2018
		 * 					[current_time] => 14:11
		 * 					[cost_hint] => 40.250 $
		 * 					[name] => Victoria
		 * 					[secondname] => vica
		 * 					[email] => vica@wpbookingcalendar.com
		 * 					[phone] => test booking
		 * 					[visitors] => 1
		 * 					[booking_resource_id] => 3
		 * 					[resource_id] => 3
		 * 					[type_id] => 3
		 * 					[type] => 3
		 * 					[resource] => 3
		 * 					[content] => '........'
		 * 					[moderatelink] => http://beta/wp-admin/admin.php?page=wpbc&view_mode=vm_listing&tab=actions&wh_booking_id=26
		 * 					[visitorbookingediturl] => http://beta/?booking_hash=0d55671fd055fd64423294f89d6b58e6
		 * 					[visitorbookingcancelurl] => http://beta/?booking_hash=0d55671fd055fd64423294f89d6b58e6&booking_cancel=1
		 * 					[visitorbookingpayurl] => http://beta/?booking_hash=0d55671fd055fd64423294f89d6b58e6&booking_pay=1
		 * 					[bookinghash] => 0d55671fd055fd64423294f89d6b58e6
		 * 					[db_cost] => 40.25
		 * 					[db_cost_hint] => 40.250 $
		 * 					[modification_date] =>  2018-02-18 12:49:30
		 * 					[modification_year] => 2018
		 * 					[modification_month] => 02
		 * 					[modification_day] => 18
		 * 					[modification_hour] => 12
		 * 					[modification_minutes] => 49
		 * 					[modification_seconds] => 30
		 *        		]
		 */
		function wpbc_get_booking_different_params_arr( $booking_id, $formdata, $booking_resource_id = 1 ) {

			$replace = array();

			// Resources ///////////////////////////////////////////////////////////////
			$bk_title = wpbc_get_resource_title( $booking_resource_id );


			////////////////////////////////////////////////////////////////////////////
			// Dates Dif. Formats
			////////////////////////////////////////////////////////////////////////////

			// -> '2023-10-09 12:00:01, 2023-10-09 20:00:02'
			$sql_dates_format = wpbc_db__get_sql_dates__in_booking__as_str( $booking_id );       // 2016-08-03 16:00:01, 2016-08-03 18:00:02

			$sql_dates_only = explode(',',$sql_dates_format);
			$sql_days_only_array = array();
			$days_as_in_form_array = array();
			foreach ( $sql_dates_only as $sql_day_only ) {
				$sql_days_only_array[] = trim( substr($sql_day_only, 0, 11 ) );
				$days_as_in_form_array[] = date_i18n( "d.m.Y", strtotime( trim( substr($sql_day_only, 0, 11 ) ) ) );
			}
			$sql_days_only_array = array_unique( $sql_days_only_array );
			sort( $sql_days_only_array );
			$sql_days_only = implode( ',', $sql_days_only_array );

			$days_as_in_form_array = array_unique( $days_as_in_form_array );
			sort( $days_as_in_form_array );
			$days_as_in_form = implode( ',', $days_as_in_form_array );

			$sql_days_only_with_full_times = array();
			foreach ( $sql_days_only_array as $sql_day ) {
				$sql_days_only_with_full_times[] = $sql_day . ' 00:00:00';
			}
			$sql_days_only_with_full_times = implode(',', $sql_days_only_with_full_times );


			if ( get_bk_option( 'booking_date_view_type' ) == 'short' ) {
				$formated_booking_dates = wpbc_get_dates_short_format( $sql_dates_format );
			} else {
				$formated_booking_dates = wpbc_get_dates_comma_string_localized( $sql_dates_format );
			}

			$sql_dates_format_check_in_out = explode(',', $sql_dates_format );

			$my_check_in_date  = wpbc_get_dates_comma_string_localized( $sql_dates_format_check_in_out[0] );
			$my_check_out_date = wpbc_get_dates_comma_string_localized( $sql_dates_format_check_in_out[ count( $sql_dates_format_check_in_out ) - 1 ] );

			$my_check_out_plus1day = wpbc_datetime_localized(   date(  'Y-m-d H:i:s'
																, strtotime(  $sql_dates_format_check_in_out[ count( $sql_dates_format_check_in_out ) - 1 ]
																			. " +1 day" )
													) );

			$date_format = get_bk_option( 'booking_date_format');
			$check_in_date_hint  = wpbc_date_localized( $sql_days_only_array[0] );
			$check_out_date_hint = wpbc_date_localized( $sql_days_only_array[ ( count( $sql_days_only_array ) - 1  ) ] );

			//FixIn: 9.7.3.16
			$cancel_date_hint = wpbc_datetime_localized(   date(  'Y-m-d H:i:s'
														  , strtotime( '-14 days', strtotime( $sql_days_only_array[0] ) )
													) );

			// Booking Times -------------------------------------------------------------------------------------------
			$start_end_time = wpbc_get_times_in_form( $formdata, $booking_resource_id ); // false ||

			if ( $start_end_time !== false ) {
				$start_time = $start_end_time[0];                                       // array('00','00','01');
				$end_time   = $start_end_time[1];                                       // array('00','00','01');
			} else {
				$start_time = array('00','00','00');
				$end_time   = array('00','00','00');
			}

//TODO: continue here with  replacing date_i18n to wpbc_loc_ ...

			$start_time_hint = wpbc_time_localized( implode( ':', $start_time ) );
			$end_time_hint   = wpbc_time_localized( implode( ':', $end_time   ) );

			//FixIn: 9.5.1.3
			$check_in_date_sql  = wpbc_date_localized( $sql_days_only_array[0], 'Y-m-d' );
			$check_out_date_sql = wpbc_date_localized( $sql_days_only_array[ ( count( $sql_days_only_array ) - 1  ) ], 'Y-m-d' );

			$start_time_sql = wpbc_time_localized( implode( ':', $start_time ), 'H:i' );
			$end_time_sql   = wpbc_time_localized( implode( ':', $end_time )  , 'H:i' );

			////////////////////////////////////////////////////////////////////////////


			// Other ///////////////////////////////////////////////////////////////////
			$replace[ 'booking_id' ]    = $booking_id;
			$replace[ 'id' ]            = $replace[ 'booking_id' ];

			$replace[ 'days_input_format' ] = $days_as_in_form;                         // 15.11.2023,16.11.2023,17.11.2023
			$replace[ 'days_only_sql' ]     = $sql_days_only;                           // 2023-11-15,2023-11-16,2023-11-17
			$replace[ 'dates_sql' ]         = $sql_dates_format;                        // 2016-07-28 16:00:01, 2016-07-28 18:00:02
			$replace[ 'check_in_date_sql' ] = $sql_dates_format_check_in_out[0];        // 2016-07-28 16:00:01
			$replace[ 'check_out_date_sql' ] = $sql_dates_format_check_in_out[ count( $sql_dates_format_check_in_out ) - 1 ];       // 2016-07-28 18:00:02
			$replace[ 'dates' ]             = $formated_booking_dates;                  // July 28, 2016 16:00 - July 28, 2016 18:00
			$replace[ 'check_in_date' ]     = $my_check_in_date;                        // July 28, 2016 16:00
			$replace[ 'check_out_date' ]    = $my_check_out_date;                       // July 28, 2016 18:00
			$replace[ 'check_out_plus1day'] = $my_check_out_plus1day;                   // July 29, 2016 18:00
			$replace[ 'dates_count' ]       = count( $sql_days_only_array );            // 1
			$replace[ 'days_count' ]        = count( $sql_days_only_array );            // 1
			$replace[ 'nights_count' ]      = ( $replace[ 'days_count' ] > 1 ) ? ( $replace[ 'days_count' ] - 1 ) : $replace[ 'days_count' ];       // 1

			//FixIn: 9.7.3.16
			$replace[ 'cancel_date_hint' ]    = $cancel_date_hint;                      // 11/11/2013
			$replace[ 'check_in_date_hint' ]  = $check_in_date_hint;                    // 11/25/2013
			$replace[ 'check_out_date_hint' ] = $check_out_date_hint;                   // 11/27/2013
			$replace[ 'start_time_hint' ]   = $start_time_hint;                         // 10:00
			$replace[ 'end_time_hint' ]     = $end_time_hint;                           // 12:00

			//FixIn: 9.5.1.3
			$replace['check_in_date_hint_sql']  = $check_in_date_sql;                    	// 2023-03-04
			$replace['check_out_date_hint_sql'] = $check_out_date_sql;                   	// 2023-03-12
			$replace['start_time_hint_sql']     = $start_time_sql;                         	// 10:00
			$replace['end_time_hint_sql']       = $end_time_sql;                           	// 12:00

		$replace['selected_dates_hint']       = wpbc_get_dates_comma_string_localized( $sql_days_only_with_full_times );             // 11/25/2013, 11/26/2013, 11/27/2013
			$replace['selected_timedates_hint']   = wpbc_get_dates_comma_string_localized( $sql_dates_format );              		// 11/25/2013 10:00, 11/26/2013, 11/27/2013 12:00
		$replace['selected_short_dates_hint']     =     wpbc_get_dates_short_format( $sql_days_only_with_full_times );      // 11/25/2013 - 11/27/2013
			$replace['selected_short_timedates_hint'] = wpbc_get_dates_short_format( $sql_dates_format );       		    // 11/25/2013 10:00 - 11/27/2013 12:00
			$replace[ 'days_number_hint' ]   = $replace[ 'days_count' ];                // 3
			$replace[ 'nights_number_hint' ] = $replace[ 'nights_count' ];              // 2
			$replace[ 'siteurl' ]       = htmlspecialchars_decode( '<a href="' . home_url() . '">' . home_url() . '</a>' );
			$replace[ 'resource_title'] = apply_bk_filter( 'wpdev_check_for_active_language', $bk_title );
			$replace[ 'bookingtype' ]   = $replace[ 'resource_title'];
			$replace[ 'remote_ip'     ] = wpbc_get_user_ip();          													// The IP address from which the user is viewing the current page.
			$replace[ 'user_agent'    ] = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';  	// Contents of the User-Agent: header from the current request, if there is one.
			$replace[ 'request_url'   ] = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';        	// The address of the page (if any) where action was occured. Because we are sending it in Ajax request, we need to use the REFERER HTTP
			$replace[ 'current_date' ]  = date_i18n( get_bk_option( 'booking_date_format' ) );
			$replace[ 'current_time' ]  = date_i18n( get_bk_option( 'booking_time_format' ) );


			// Form Fields /////////////////////////////////////////////////////////////
			$booking_form_show_array = wpbc__legacy__get_form_content_arr( $formdata, $booking_resource_id, '', $replace );    // We use here $replace array,  becaise in "Content of booking filds data" form  can  be shortcodes from above definition

			foreach ( $booking_form_show_array['_all_fields_'] as $shortcode_name => $shortcode_value ) {

				if ( ! isset( $replace[ $shortcode_name ] ) )
					$replace[ $shortcode_name ] = $shortcode_value;
			}
			$replace[ 'content' ]       = $booking_form_show_array['content'];

			// Links ///////////////////////////////////////////////////////////////////
			$replace[ 'moderatelink' ]  = htmlspecialchars_decode(
																//    '<a href="' .
																	esc_url( wpbc_get_bookings_url() . '&view_mode=vm_listing&tab=actions&wh_booking_id=' . $booking_id )
																//    . '">' . __('here', 'booking') . '</a>'
																);
			$replace[ 'visitorbookingediturl' ]     = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingediturl]', $booking_id );
			$replace[ 'visitorbookingslisting' ]     = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingslisting]', $booking_id );	//FixIn: 8.1.3.5.1
			$replace[ 'visitorbookingcancelurl' ]   = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingcancelurl]', $booking_id );
			$replace[ 'visitorbookingpayurl' ]      = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[visitorbookingpayurl]', $booking_id );
			$replace[ 'bookinghash' ]               = apply_bk_filter( 'wpdev_booking_set_booking_edit_link_at_email', '[bookinghash]', $booking_id );

			// Cost ////////////////////////////////////////////////////////////////////
			$replace[ 'db_cost' ]        = apply_bk_filter( 'get_booking_cost_from_db', '', $booking_id );
			$replace[ 'db_cost_hint' ]   = wpbc_get_cost_with_currency_for_user( $replace[ 'db_cost' ], $booking_resource_id );

			////////////////////////////////////////////////////////////////////////////

			//FixIn: 8.0.1.7
			$modification_date = wpbc_db_get_booking_modification_date( $booking_id );

			// This date $values in GMT date/Time format. So  we need to switch  to  WordPress locale with TIME  sum of actual  GMT date/time value + shift  of timezone from WordPress.
			$is_add_wp_timezone = true;
			$modification_date = wpbc_datetime_localized( trim( $modification_date ), 'Y-m-d H:i:s', $is_add_wp_timezone );

			$replace['modification_date'] = ' ' . $modification_date;

			$modification_date = explode( ' ', $modification_date );
			list( $replace['modification_year'], $replace['modification_month'], $replace['modification_day'] ) = explode( '-', $modification_date[0] );
			list( $replace['modification_hour'], $replace['modification_minutes'], $replace['modification_seconds'] ) = explode( ':', $modification_date[1] );

			return $replace;
		}


		/**
		 * Get additional parameters to the replace array  for specific booking
		 *
		 * @param $replace
		 * @param $booking_id
		 * @param $bktype
		 * @param $formdata
		 *
		 * @return mixed
		 */
		function wpbc_replace_params_for_booking_func( $replace, $booking_id, $bktype, $formdata ){

			$modification_date = wpbc_db_get_booking_modification_date( $booking_id );

			// This date $values in GMT date/Time format. So  we need to switch  to  WordPress locale with TIME  sum of actual  GMT date/time value + shift  of timezone from WordPress.
			$is_add_wp_timezone = true;
			$modification_date = wpbc_datetime_localized( trim( $modification_date ), 'Y-m-d H:i:s', $is_add_wp_timezone );

			$replace['modification_date'] = ' ' . $modification_date;

			$modification_date = explode( ' ', $modification_date );
			list( $replace['modification_year'], $replace['modification_month'], $replace['modification_day'] ) = explode( '-', $modification_date[0] );
			list( $replace['modification_hour'], $replace['modification_minutes'], $replace['modification_seconds'] ) = explode( ':', $modification_date[1] );



			//FixIn: 8.4.2.11
			if ( isset( $replace['rangetime'] ) ) {
				$replace['rangetime'] = wpbc_time_slot_in_format( $replace['rangetime'] );
			}
			if ( isset( $replace['starttime'] ) ) {
				$replace['starttime'] = wpbc_time_in_format( $replace['starttime'] );
			}
			if ( isset( $replace['endtime'] ) ) {
				$replace['endtime'] = wpbc_time_in_format( $replace['endtime'] );
			}

			//FixIn: 8.2.1.25
			$booking_data = wpbc_db_get_booking_details( $booking_id );

			if ( ! empty( $booking_data ) ) {
				foreach ( $booking_data as $booking_key => $booking_data ) {
					if ( ! isset( $replace[ $booking_key ] ) ) {
						$replace[ $booking_key ] = $booking_data;
					}
				}
			}

			return $replace;
		}
		add_filter( 'wpbc_replace_params_for_booking', 'wpbc_replace_params_for_booking_func', 10, 4 );


		/**
			 * Replace shortcodes in string
		 *
		 * @param string $subject 					- string to  manipulate
		 * @param array $replace_array 				- array with  values to  replace                 // array( [booking_id] => 9, [id] => 9, [dates] => July 3, 2016 14:00 - July 4, 2016 16:00, .... )
		 * @param mixed $replace_unknown_shortcodes - replace unknown params, if false, then  no replace unknown params
		 * @return string
		 */
		function wpbc_replace_booking_shortcodes( $subject, $replace_array , $replace_unknown_shortcodes = ' ' ) {

			$defaults = array(
				  'ip'              => wpbc_get_user_ip()
				, 'blogname'        => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES )
				, 'siteurl'         => get_site_url()
			);

			$replace = wp_parse_args( $replace_array, $defaults );

			foreach ( $replace as $replace_shortcode => $replace_value ) {

				$subject = str_replace( array(   '[' . $replace_shortcode . ']'
											   , '{' . $replace_shortcode . '}' )
										, $replace_value
										, $subject );
			}

			// Remove all shortcodes, which is not replaced early.
			if ( $replace_unknown_shortcodes !== false )
				$subject = preg_replace( '/[\s]{0,}[\[\{]{1}[a-zA-Z0-9.,-_]{0,}[\]\}]{1}[\s]{0,}/', $replace_unknown_shortcodes, $subject );


			return $subject;
		}


		// =============================================================================================================

		/**
		 *  >=BS - for 'Billing fields' - Get fields from booking form at the settings page or return false if no fields
		 *
		 * @param string $booking_form
		 * @return mixed  false | array( $fields_count, $fields_matches )
		 */
		function wpbc_get_fields_from_booking_form( $booking_form = '' ){
			
			if ( empty( $booking_form ) ) {
				$booking_form = get_bk_option( 'booking_form' );
			}
			
			$types         = 'text[*]?|email[*]?|time[*]?|textarea[*]?|select[*]?|checkbox[*]?|radio|acceptance|captchac|captchar|file[*]?|quiz';
			$regex         = '%\[\s*(' . $types . ')(\s+[a-zA-Z][0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)?((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
			$regex2        = '%\[\s*(country[*]?|starttime[*]?|endtime[*]?)(\s*[a-zA-Z]*[0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)*((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
			$fields_count  = preg_match_all( $regex, $booking_form, $fields_matches );
			$fields_count2 = preg_match_all( $regex2, $booking_form, $fields_matches2 );
	
			//Gathering Together 2 arrays $fields_matches  and $fields_matches2
			foreach ( $fields_matches2 as $key => $value ) {
				if ( $key == 2 ) {
					$value = $fields_matches2[1];
				}
				foreach ( $value as $v ) {
					$fields_matches[ $key ][ count( $fields_matches[ $key ] ) ] = $v;
				}
			}
			$fields_count += $fields_count2;
	
			if ( $fields_count > 0 ) {
				return array( $fields_count, $fields_matches );
			} else {
				return false;
			}
		}
	
	
		/**
		 * >= BM - for 'Advanced costs' -- Get only SELECT, CHECKBOX & RADIO fields from booking form at the settings page or return false if no fields
		 *
		 * @param string $booking_form
		 * @return mixed  false | array( $fields_count, $fields_matches )
		 */
		function wpbc_get_select_checkbox_fields_from_booking_form( $booking_form = '' ){
	
			if ( empty( $booking_form )  )
				$booking_form  = get_bk_option( 'booking_form' );
	
			$types = 'select[*]?|checkbox[*]?|radio[*]?';                                                                //FixIn: 8.1.3.7
			$regex = '%\[\s*(' . $types . ')(\s+[a-zA-Z][0-9a-zA-Z:._-]*)([-0-9a-zA-Z:#_/|\s]*)?((?:\s*(?:"[^"]*"|\'[^\']*\'))*)?\s*\]%';
	
			$fields_count = preg_match_all($regex, $booking_form, $fields_matches) ;
	
			if ( $fields_count > 0 )
				 return array( $fields_count, $fields_matches );
			else return false;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Replace shortcodes in text  ==  "  >

		/**
		 * Get parameters of shortcode in string   '..some text [visitorbookingpayurl url='https://url.com/a/'] ...'  ->   [ 'url'='https://url.com.com/a/',  'start'=10,  'end'=80  ]
		 *
		 * @param string $shortcode	- shortcode name						- 'visitorbookingcancelurl'
		 * @param string $subject	- string where to  search  shortcode:	- '<p>1 PT. [visitorbookingpayurl url='https://wpbookingcalendar.com/faq/']</p>'
		 * @param int $pos						default 0					- 0
		 * @param string $pattern_to_search		default	'%\s*([^=]*)=\s*[\'"]([^\'"]*)[\'"]\s*%'
		 *
		 * @return array|false			[
		 * 									'url'   = "https://wpbookingcalendar.com/faq/"
		 * 									'start' = 10
		 * 									'end'   = 80
		 *                       		]
		 *
		 * Example:
		 *                		wpbc_get_params_of_shortcode_in_string( 'visitorbookingpayurl', '<p>1 PT. [visitorbookingpayurl url = "https://wpbookingcalendar.com/faq/"]</p>...' );
		 *
		 * 			output ->	[
		 * 							'url'   = "https://wpbookingcalendar.com/faq/"
		 * 							'start' = 10
		 * 							'end'   = 80
		 *                      ]
		 *
		 */
		function wpbc_get_params_of_shortcode_in_string( $shortcode, $subject, $pos = 0, $pattern_to_search = '%\s*([^=]*)=\s*[\'"]([^\'"]*)[\'"]\s*%' ) { //FixIn: 9.7.4.4  //FixIn: 7.0.1.8     7.0.1.52

			if ( strlen( $subject ) < intval( $pos ) ) {                                        //FixIn: (9.7.4.5)
				return false;
			}

			$pos = strpos( $subject, '[' . $shortcode, $pos );                                   //FixIn: 7.0.1.52

			if ( $pos !== false ) {
				$pos2 = strpos( $subject, ']', ( $pos + 2 ) );

				$my_params = substr( $subject, $pos + strlen( '[' . $shortcode ), ( $pos2 - $pos - strlen( '[' . $shortcode ) ) );


				preg_match_all( $pattern_to_search, $my_params, $keywords, PREG_SET_ORDER );

				foreach ( $keywords as $value ) {
					if ( count( $value ) > 1 ) {
						$shortcode_params[ trim( $value[1] ) ] = trim( $value[2] );                                            //FixIn: 9.7.4.4
					}
				}
				$shortcode_params['start'] = $pos + 1;
				$shortcode_params['end']   = $pos2;

				return $shortcode_params;
			} else {
				return false;
			}
		}


		/**
		 * Get shortcodes with params and text for replacing these shortcodes as new uniue shortcodes
		 *
		 * @param string $content_text  Example: "<span class="wpbc_top_news_dismiss">[wpbc_dismiss id="wpbc_top_news__offer_2023_04_21"]</span>"
		 * @param array $shortcode_arr  Example: array( 'wpbc_dismiss' )
		 *
		 * @return array
		 *              Example: array(
										 content    => "<span class="wpbc_top_news_dismiss">[wpbc_dismiss6764]</span>"
										 shortcodes => array(
															  'wpbc_dismiss6764' => array(
																							shortcode => "[wpbc_dismiss6764]",
																							params => array( id => "wpbc_top_news__offer_2023_04_21" )
																							shortcode_original => "[wpbc_dismiss id="wpbc_top_news__offer_2023_04_21"]"
																						  )
															)
									)
		 */
		function wpbc_get_shortcodes_in_text__as_unique_replace( $content_text, $shortcode_arr = array( 'wpbc_dismiss' ) ) {                   //FixIn: 9.6.1.8

			$replace = array();

			foreach ( $shortcode_arr as $single_shortcode ) {

				$pos = 0;           // Loop to find if we have several such shortcodes in $content_text
				do {
					$shortcode_params = wpbc_get_params_of_shortcode_in_string( $single_shortcode, $content_text, $pos );

					if (  ( ! empty( $shortcode_params ) ) && ( isset( $shortcode_params['end'] ) ) && ( $shortcode_params['end'] < strlen( $content_text ) )  ){

						$exist_replace = substr( $content_text, $shortcode_params['start'], ( $shortcode_params['end'] - $shortcode_params['start'] ) );

						$new_replace = $single_shortcode . rand( 1000, 9000 );

						$pos = $shortcode_params['start'] + strlen( $new_replace );

						$content_text = substr_replace( $content_text, $new_replace, $shortcode_params['start'], ( $shortcode_params['end'] - $shortcode_params['start'] ) );

						$params_in_shortcode = $shortcode_params;
						unset( $params_in_shortcode['start'] );
						unset( $params_in_shortcode['end'] );
						$replace[ $new_replace ] = array(
														  'shortcode'          => '[' . $new_replace . ']',
														  'params'             => $params_in_shortcode,
														  'shortcode_original' => '[' . $exist_replace . ']',
													);
					} else {
						$shortcode_params = false;
					}

				} while ( ! empty( $shortcode_params ) );

			}

			return array(
				'content'    => $content_text,
				'shortcodes' => $replace
			);
		}


		/**
		 * Get html  preview of shortcode for Edit pages in Elementor and at Block editors
		 *
		 * @param $shortcode_type
		 * @param $attr
		 *
		 * @return string
		 */
		function wpbc_get_preview_for_shortcode( $shortcode_type, $attr ) {

			//FixIn: 9.9.0.39

			return '<div style="border:2px dashed #ccc;text-align: center; padding:10px;display:flex;flex-flow:column wrap;justify-content: center;align-content: center;">
						<div>WP Booking Calendar Shortcode</div>
						<code>['.$shortcode_type.' ...]</code>
						<div style="font-size:0.8em;">This is not a real preview. Publish the page to see it in action.</div>
					</div>';
		}
	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc=" ==  Get version Nums or Types  ==  "  >

		/**
		 * Check if this demo website
		 *
		 * @return bool
		 */
		function wpbc_is_this_demo() {

// return !true;
			if ( ! class_exists( 'wpdev_bk_personal' ) ) {
				return false;		// If this is Booking Calendar Free version,  then it's not the demo.
			}

			//FixIn: 7.2.1.17
			if (
				   ( ( isset( $_SERVER['SCRIPT_FILENAME'] ) ) && ( strpos( $_SERVER['SCRIPT_FILENAME'], 'wpbookingcalendar.com' ) !== false ) )
				|| ( ( isset( $_SERVER['HTTP_HOST'] ) ) && ( strpos( $_SERVER['HTTP_HOST'], 'wpbookingcalendar.com' ) !== false ) )
			) {
				return true;
			} else {
				return false;
			}
		}


		/** Get Warning Text  for Demo websites */
		function wpbc_get_warning_text_in_demo_mode() {
			// return '<div class="wpbc-error-message wpbc_demo_test_version_warning"><strong>Warning!</strong> Demo test version does not allow changes to these items.</div>'; //Old Style
			return '<div class="wpbc-settings-notice notice-warning"><strong>Warning!</strong> Demo test version does not allow changes to these items.</div>';
		}



		function wpbc_get_version_type__and_mu(){
			$version = 'free';
			if ( class_exists( 'wpdev_bk_personal' ) ) $version = 'personal';
			if ( class_exists( 'wpdev_bk_biz_s' ) )    $version = 'biz_s';
			if ( class_exists( 'wpdev_bk_biz_m' ) )    $version = 'biz_m';
			if ( class_exists( 'wpdev_bk_biz_l' ) )    $version = 'biz_l';
			if ( class_exists('wpdev_bk_multiuser') )  $version = 'multiuser';
			return $version;
		}


		function wpbc_get_plugin_version_type(){
			$version = 'free';
			if ( class_exists( 'wpdev_bk_personal' ) ) $version = 'personal';
			if ( class_exists( 'wpdev_bk_biz_s' ) )    $version = 'biz_s';
			if ( class_exists( 'wpdev_bk_biz_m' ) )    $version = 'biz_m';
			if ( class_exists( 'wpdev_bk_biz_l' ) )    $version = 'biz_l';
			return $version;
		}


		/**
		 * Check if user accidentially update Booking Calendar Paid version to Free
		 *
		 * @return bool
		 */
		function wpbc_is_updated_paid_to_free() {

			if ( ( wpbc_is_table_exists('bookingtypes') ) && ( ! class_exists('wpdev_bk_personal') )  )
				return  true;
			else
				return false;
		}


		function wpbc_get_ver_sufix() {
			if( strpos( strtolower(WPDEV_BK_VERSION) , 'multisite') !== false  ) {
				$v_type = '-multi';
			} else if( strpos( strtolower(WPDEV_BK_VERSION) , 'develop') !== false  ) {
				$v_type = '-dev';
			} else {
				$v_type = '';
			}
			$v = '';
			if (class_exists('wpdev_bk_personal'))  $v = 'ps'. $v_type;
			if (class_exists('wpdev_bk_biz_s'))     $v = 'bs'. $v_type;
			if (class_exists('wpdev_bk_biz_m'))     $v = 'bm'. $v_type;
			if (class_exists('wpdev_bk_biz_l'))     $v = 'bl'. $v_type;
			if (class_exists('wpdev_bk_multiuser')) $v = '';
			return $v ;
		}


		function wpbc_up_link() {
			if ( ! wpbc_is_this_demo() )
				 $v = wpbc_get_ver_sufix();
			else $v = '';
			return 'https://wpbookingcalendar.com/' . ( ( empty($v) ) ? '' : 'upgrade-' . $v  . '/' ) ;
		}


		/**
			 * Check  if "Booking Manager" installed/activated and return version number
		 *
		 * @return string - 0 if not installed,  otherwise version num
		 */
		function wpbc_get_wpbm_version() {

			if ( ! defined( 'WPBM_VERSION_NUM' ) ) {
				return 0;
			} else {
				return WPBM_VERSION_NUM;
			}
		}


		/**
		 * Get header info from this file, just for compatibility with WordPress 2.8 and older versions
		 *
		 * @param $file					= WPBC_FILE
		 * @param $default_headers		= array( 'Name' => 'Plugin Name', 'PluginURI' => 'Plugin URI', 'Version' => 'Version', 'Description' => 'Description', 'Author' => 'Author', 'AuthorURI' => 'Author URI', 'TextDomain' => 'Text Domain', 'DomainPath' => 'Domain Path' )
		 * @param $context				= 'plugin'
		 *
		 * @return array
		 *
		 *  Example:
		 *          $plugin_data = wpbc_file__read_header_info(  WPBC_FILE , array( 'Name' => 'Plugin Name', 'PluginURI' => 'Plugin URI', 'Version' => 'Version', 'Description' => 'Description', 'Author' => 'Author', 'AuthorURI' => 'Author URI', 'TextDomain' => 'Text Domain', 'DomainPath' => 'Domain Path' ) , 'plugin' );
		 */
		function wpbc_file__read_header_info( $file, $default_headers, $context = '' ) {

			$fp = fopen( $file, 'r' );		// We don't need to write to the file, so just open for reading.

			$file_data = fread( $fp, 8192 );		// Pull only the first 8kiB of the file in.

			fclose( $fp );					// PHP will close file handle, but we are good citizens.

			if ( $context != '' ) {
				$extra_headers = array();			//apply_filters( "extra_$context".'_headers', array() );

				$extra_headers = array_flip( $extra_headers );
				foreach ( $extra_headers as $key => $value ) {
					$extra_headers[ $key ] = $key;
				}
				$all_headers = array_merge( $extra_headers, $default_headers );
			} else {
				$all_headers = $default_headers;
			}

			foreach ( $all_headers as $field => $regex ) {
				preg_match( '/' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, ${$field});
				if ( !empty( ${$field} ) )
					${$field} =  trim(preg_replace("/\s*(?:\*\/|\?>).*/", '',  ${$field}[1] ));
				else
					${$field} = '';
			}

			$file_data = compact( array_keys( $all_headers ) );

			return $file_data;
		}



		/**
		 * Check  if we need BLUR this section -- add CSS Class for specific versions
		 *
		 *  Example:   $is_blured = wpbc_is_blured( array( 'free', 'ps' ) ); 		// true in Free and  Personal versions.
		 *
		 * @param $versions_arr
		 *
		 * @return void
		 */
		function wpbc_is_blured( $versions_arr ){

			$ver = wpbc_get_version_type__and_mu();

			$is_blured = false;

			switch ( $ver ) {
				case 'free':
					$is_blured = ( ( in_array( $ver, $versions_arr ) ) || ( in_array( 'f', $versions_arr ) ) ) ? true : $is_blured;
					break;
				case 'personal':
					$is_blured = ( ( in_array( $ver, $versions_arr ) ) || ( in_array( 'ps', $versions_arr ) ) ) ? true : $is_blured;
					break;
				case 'biz_s':
					$is_blured = ( ( in_array( $ver, $versions_arr ) ) || ( in_array( 'bs', $versions_arr ) ) ) ? true : $is_blured;
					break;
				case 'biz_m':
					$is_blured = ( ( in_array( $ver, $versions_arr ) ) || ( in_array( 'bm', $versions_arr ) ) ) ? true : $is_blured;
					break;
				case 'biz_l':
					$is_blured = ( ( in_array( $ver, $versions_arr ) ) || ( in_array( 'bl', $versions_arr ) ) ) ? true : $is_blured;
					break;
				case 'multiuser':
					$is_blured = ( ( in_array( $ver, $versions_arr ) ) || ( in_array( 'mu', $versions_arr ) ) ) ? true : $is_blured;
					break;
				default:
					// Default
			}
		    return $is_blured;
		}


		/**
		 * Echo Blur CSS Class for specific versions
		 *
		 *  Example: wpbc_echo_blur(array('free','ps')); 		// Echo  blur in Free and  Personal versions.
		 *
		 * @param $versions_arr
		 *
		 * @return void
		 */
		function wpbc_echo_blur( $versions_arr ){

			$is_blured = wpbc_is_blured( $versions_arr );

			if ( $is_blured ) {
				echo 'wpbc_blur';
			}
		}


		/**
		 * Show Upgrade Widget
		 *
		 * @param $id
		 * @param $params
		 *
		 * @return string		if upgrade panel hided than  returned ''
		 *
		 *
		 * Example:
		 *                     wpbc_get_up_notice('booking_weekdays_conditions', array(
		 * 																						'feature_link' => array( 'title' => 'feature',        'relative_url' => 'overview/#capacity' ),
		 * 																						'upgrade_link' => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
		 * 																						'versions'     => 'Business Large, MultiUser versions',
		 * 																						'css'          => 'transform: translate(0) translateY(120px);'
		 *                                                                    ));
		 */
		function wpbc_get__upgrade_notice__html_content( $id, $params ){

			$defaults = array(
							'feature_link' => array( 'title' => 'feature', 		  'relative_url' => 'overview/#capacity' ),
							'upgrade_link' => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
							'versions' 	   => 'Business Large, MultiUser versions',
							'css'		   => 'transform: translate(0) translateY(120px);',
							'dismiss_css_class' => '',
							'html_dismiss_btn'  => ''
						);
			$params   = wp_parse_args( $params, $defaults );

			ob_start();

			?><div id="upgrade_notice_<?php echo esc_attr( $id ); ?>"
				   class="wpbc_widget_content wpbc_upgrade_widget <?php echo esc_attr( str_replace( array('.','#'), '', $params['dismiss_css_class'] ) ); ?>"
				   style="<?php echo esc_attr( $params['css'] ); ?>">
				<div class="ui_container    ui_container_toolbar		ui_container_small       wpbc_upgrade_widget_container">
					<div class="ui_group    ui_group__upgrade">
						<div class="wpbc_upgrade_note wpbc_upgrade_theme_green">
							<div>
							<?php
								printf( 'This %s is available in the %s. %s'
									, '<a target="_blank" href="https://wpbookingcalendar.com/' . $params['feature_link']['relative_url'] . '">' . $params['feature_link']['title'] . '</a>'
									, '<strong>' . $params['versions'] . '</strong>'
									, '<a target="_blank" href="https://wpbookingcalendar.com/' . $params['upgrade_link']['relative_url'] . '">' . $params['upgrade_link']['title'] . '</a>'
								);
							?>
							</div>
							<?php
							// Dismiss button
							echo $params['html_dismiss_btn'];
							?>
						</div>
					</div>
				</div>
			</div><?php

			$html = ob_get_clean();

			return  $html ;
		}



		/**
		 * Get for showing Upgrade Widget Content and CSS class if needed to  blur real  content.  If
		 *
		 * @param $params = array(
 		 *																	  'id'                 => $id . '_' . 'weekdays_conditions',
		 *														  			  'dismiss_css_class'  => '.wpbc_dismiss_weekdays_conditions',
		 *														  			  'blured_in_versions' => array( 'free', 'ps', 'bs', 'mu' ),
		 *														  			  'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#advanced-days-selection' ),
		 *														  			  'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
		 *														  			  'versions'           => 'Business Medium / Large, MultiUser versions',
		 *														  			  'css'                => 'transform: translate(0) translateY(120px);'
		 * 					)
		 *
		 * @return array(
		 *     				'content' 				=> $upgrade_panel_html,
		 *     				'maybe_blur_css_class' 	=> $blur_css_class
		 * 				)
		 *
		 * Example of usage:
		 *
		 *		  		$upgrade_content_arr = wpbc_get_upgrade_widget( array(
 		 *																	  'id'                 => $id . '_' . 'weekdays_conditions',
		 *														  			  'dismiss_css_class'  => '.wpbc_dismiss_weekdays_conditions',
		 *														  			  'blured_in_versions' => array( 'free', 'ps', 'bs', 'mu' ),
		 *														  			  'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#advanced-days-selection' ),
		 *														  			  'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
		 *														  			  'versions'           => 'Business Medium / Large, MultiUser versions',
		 *														  			  'css'                => 'transform: translate(0) translateY(120px);'
		 *														   ) );
 		 *
		 * 			echo $upgrade_content_arr['content'];
		 *
		 *			// ... In real  content ...
		 *          <div class=" wpbc_dismiss_weekdays_conditions <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>">
		 *              ...
		 *			</div>
		 */
		function wpbc_get_upgrade_widget( $params ) {

			$defaults = array(
								'id' 				 => 'wpbc_random_' . round( microtime( true ) * 1000 ), 			//$id . '_' . 'weekdays_conditions',
								'blured_in_versions' => array( 'free', 'ps', 'bs', 'bm', 'bl', 'mu' ),
								'feature_link' 		 => array( 'title' => 'feature', 'relative_url' => 'overview/#capacity' ),
								'upgrade_link' 		 => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
								'versions'     		 => 'Business Large, MultiUser versions',
								'css'          		 => 'transform: translate(0) translateY(120px);',
								'dismiss_css_class'  => ''																//'.wpbc_random_' . round( microtime( true ) * 1000 ), //'.'.$id . '_' . 'weekdays_conditions'
						);
			$params = wp_parse_args( $params, $defaults );
			$up_id = $params['id'];


			$is_blured = wpbc_is_blured( $params['blured_in_versions'] );

			$upgrade_panel_html = '';
			$blur_css_class     = '';

			if ( $is_blured ) {

				// ---------------------------------------------------------------------------------------------------------
				// Is dismissed ?
				// ---------------------------------------------------------------------------------------------------------
				ob_start();
				$is_upgrade_panel_visible = wpbc_is_dismissed( $up_id , array(
																			'title' => '<span aria-hidden="true" style="font-size: 28px;">&times;</span>',
																			'hint'  => __( 'Dismiss', 'booking' ),
																			'class' => 'wpbc_panel_get_started_dismiss',
																			'css'   => '',
																			'dismiss_css_class' => $params['dismiss_css_class']
																	) );
				$html_dismiss_btn = ob_get_clean();

				// ---------------------------------------------------------------------------------------------------------
				// Upgrade Widget
				// ---------------------------------------------------------------------------------------------------------
				if ( $is_upgrade_panel_visible ) {

					$upgrade_panel_html =  wpbc_get__upgrade_notice__html_content( $up_id, array(
																'feature_link' => $params['feature_link'],
																'upgrade_link' => $params['upgrade_link'],
																'versions'     => $params['versions'],
																'css'          => $params['css'],
																'dismiss_css_class' => $params['dismiss_css_class'],
																'html_dismiss_btn'=> $html_dismiss_btn
														) );
					$blur_css_class = 'wpbc_blur';
				} else {

					ob_start();

					?><script type="text/javascript">
						jQuery(document).ready(function(){
							setTimeout(function(){
								jQuery( '<?php echo esc_attr( $params['dismiss_css_class'] ); ?>' ).hide() ;
							}, 100);
						});
					</script><?php

					$upgrade_panel_html = ob_get_clean();
				}
			}

			$upgrade_content_arr = array(
							'content' 				=> $upgrade_panel_html,
							'maybe_blur_css_class' 	=> $blur_css_class
						);

			return $upgrade_content_arr;

		}
	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Calendar functions  ==  "  >
		
		/**
		 * Get maximum visible days in calendar
		 *
		 * @return int
		 */
		function wpbc_get_max_visible_days_in_calendar(){
		
			// Number of months to scroll
			$max_visible_days_in_calendar = get_bk_option( 'booking_max_monthes_in_calendar');
		
			if ( false !== strpos( $max_visible_days_in_calendar, 'm' ) ) {
				$max_visible_days_in_calendar = intval( str_replace( 'm', '', $max_visible_days_in_calendar ) ) * 31 + 5;                    //FixIn: 9.6.1.1
			} else {
				$max_visible_days_in_calendar = intval( str_replace( 'y', '', $max_visible_days_in_calendar ) ) * 365 + 15;                  //FixIn: 9.6.1.1
			}
		
			return $max_visible_days_in_calendar;
		}


		/**
		 * Parse {calendar ....}  option parameter  in Calendar | Booking form   shortcode
		 *
		 * @param $bk_options
		 *
		 * @return array|false
		 */
		function wpbc_parse_calendar_options( $bk_options ) {

			if ( empty( $bk_options ) ) {
				return false;
			}
				/* $matches    structure:
				 * Array
					(
						[0] => Array
							(
								[0] => {calendar months="6" months_num_in_row="2" width="341px" cell_height="48px"},
								[1] => calendar
								[2] => months="6" months_num_in_row="2" width="341px" cell_height="48px"
							)

						[1] => Array
							(
								[0] => {select-day condition="weekday" for="5" value="3"},
								[1] => select-day
								[2] => condition="weekday" for="5" value="3"
							)
						 .....
					)
				 */

			$pattern_to_search='%\s*{([^\s]+)\s*([^}]+)\s*}\s*[,]?\s*%';

			preg_match_all( $pattern_to_search, $bk_options, $matches, PREG_SET_ORDER );

			foreach ( $matches as $value ) {
				if ( $value[1] == 'calendar' ) {
					$paramas = $value[2];
					$paramas = trim( $paramas );
					$paramas = explode( ' ', $paramas );
					$options = array();
					foreach ( $paramas as $vv ) {
						if ( ! empty( $vv ) ) {
							$vv = trim( $vv );
							$vv = explode( '=', $vv );
							if ( ( isset( $vv[0] ) ) && ( isset( $vv[1] ) ) ) {
								$options[ $vv[0] ] = trim( $vv[1] );
							}
						}
					}
					if ( count( $options ) == 0 ) {
						return false;
					} else {
						return $options;
					}
				}
			}

			return false;			// We are not have the "calendar" options in the shortcode
		}

		

		/**
		 * Parse {calendar ....}  option parameter  in Calendar | Booking form   shortcode
		 *
		 * @param $bk_options
		 *
		 * @return array|false
		 */
		function wpbc_parse_calendar_options__aggregate_param( $bk_options ) {                    						//FixIn: 9.8.15.10

			if ( empty( $bk_options ) ) {
				return false;
			}
				/* $matches    structure:
					$matches = [
									0 = [
											  0 = "{aggregate type=bookings_only}"
											  1 = "aggregate"
											  2 = "type=bookings_only"
										]
								 ]
				 */

			$pattern_to_search='%\s*{([^\s]+)\s*([^}]+)\s*}\s*[,]?\s*%';

			preg_match_all( $pattern_to_search, $bk_options, $matches, PREG_SET_ORDER );

			foreach ( $matches as $value ) {
				if ( $value[1] == 'aggregate' ) {
					$paramas = $value[2];
					$paramas = trim( $paramas );
					$paramas = explode( ' ', $paramas );
					$options = array();
					foreach ( $paramas as $vv ) {
						if ( ! empty( $vv ) ) {
							$vv = trim( $vv );
							$vv = explode( '=', $vv );
							if ( ( isset( $vv[0] ) ) && ( isset( $vv[1] ) ) ) {
								$options[ $vv[0] ] = trim( $vv[1] );
							}
						}
					}
					if ( count( $options ) == 0 ) {
						return false;
					} else {
						return $options;
					}
				}
			}

			return false;			// We are not have the "calendar" options in the shortcode
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Files functions  ==  "  >

		// Get array of images - icons inside of this directory
		function wpbc_dir_list ($directories) {

			// create an array to hold directory list
			$results = array();

			if (is_string($directories)) $directories = array($directories);
			foreach ($directories as $dir) {
				if ( is_dir( $dir ) ) {
					$directory = $dir;
				} else {
					$directory = WPBC_PLUGIN_DIR . $dir;
				}

				if ( file_exists( $directory ) ) {                                  //FixIn: 5.4.5
					// create a handler for the directory
					$handler = @opendir($directory);
					if ($handler !== false) {
						// keep going until all files in directory have been read
						while ($file = readdir($handler)) {

							// if $file isn't this directory or its parent,
							// add it to the results array
							if ($file != '.' && $file != '..' && ( strpos($file, '.css' ) !== false ) )
								$results[] = array($file, /* WPBC_PLUGIN_URL .*/ $dir . $file,  ucfirst(strtolower( str_replace('.css', '', $file))) );
						}

						// tidy up: close the handler
						closedir($handler);
					}
				}
			}
			// done!
			return $results;
		}


		/**
		 * Check  if such file exist or not.
		 *
		 * @param string $path - relative path to  file (relative to plugin folder).
		 * @return boolean true | false
		 */
		function wpbc_is_file_exist( $path ) {

			if (  file_exists( trailingslashit( WPBC_PLUGIN_DIR ) . ltrim( $path, '/\\' ) )  )  // check if this file exist
				return true;
			else
				return false;
		}


		/**
		 * Count the number of bytes of a given string.
		 * Input string is expected to be ASCII or UTF-8 encoded.
		 * Warning: the function doesn't return the number of chars
		 * in the string, but the number of bytes.
		 * See http://www.cl.cam.ac.uk/~mgk25/unicode.html#utf-8
		 * for information on UTF-8.
		 *
		 * @param string $str The string to compute number of bytes
		 *
		 * @return The length in bytes of the given string.
		 */
		function wpbc_get_bytes_from_str( $str ) {
			// STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
			// Number of characters in string
			$strlen_var = strlen( $str );

			$d = 0;			// string bytes counter

			// Iterate over every character in the string, escaping with a slash or encoding to UTF-8 where necessary
			for ( $c = 0; $c < $strlen_var; ++ $c ) {
				$ord_var_c = ord( $str[$c] );        //FixIn: 2.0.17.1
				switch ( true ) {
					case(($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):		// characters U-00000000 - U-0000007F (same as ASCII)
						$d ++;
						break;
					case(($ord_var_c & 0xE0) == 0xC0):						// characters U-00000080 - U-000007FF, mask 110XXXXX
						$d += 2;
						break;
					case(($ord_var_c & 0xF0) == 0xE0):						// characters U-00000800 - U-0000FFFF, mask 1110XXXX
						$d += 3;
						break;
					case(($ord_var_c & 0xF8) == 0xF0):						// characters U-00010000 - U-001FFFFF, mask 11110XXX
						$d += 4;
						break;
					case(($ord_var_c & 0xFC) == 0xF8):						// characters U-00200000 - U-03FFFFFF, mask 111110XX
						$d += 5;
						break;
					case(($ord_var_c & 0xFE) == 0xFC):						// characters U-04000000 - U-7FFFFFFF, mask 1111110X
						$d += 6;
						break;
					default:
						$d ++;
				}
			}
			return $d;
		}


	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  URL  functions  ==  "  >

		/**
		 * Get absolute URL to  relative plugin path.
		 *  Possibly to load minified version of file,  if its exist
		 * @param string $path    - path
		 * @return string
		 */
		function wpbc_plugin_url( $path ) {

			return trailingslashit( WPBC_PLUGIN_URL ) . ltrim( $path, '/\\' );

		}

		// Set URL from absolute to relative (starting from /)
		function wpbc_set_relative_url( $url ){

			$url = esc_url_raw($url);

			$url_path = parse_url($url,  PHP_URL_PATH);
			$url_path =  ( empty($url_path) ? $url : $url_path );

			$url =  trim($url_path, '/');
			return  '/' . $url;
		}

		/**
		 * Get Relative URL
		 *
		 * @param $maybe_absolute_link
		 *
		 * @return string
		 */
		function wpbc_make_link_relative( $maybe_absolute_link ) {

			if ( $maybe_absolute_link == get_option( 'siteurl' ) ) {
				$maybe_absolute_link = '/';
			}

			$maybe_absolute_link = '/' . trim( wp_make_link_relative( $maybe_absolute_link ), '/' );

			return $maybe_absolute_link;
		}

		/**
		 * Get  Absolute URL  (check for languages)
		 *
		 * @param $maybe_relative_link
		 *
		 * @return string
		 */
		function wpbc_make_link_absolute( $maybe_relative_link ){

			if ( ( $maybe_relative_link != home_url() ) && ( strpos( $maybe_relative_link, 'http' ) !== 0 ) ) {

				$maybe_relative_link = apply_bk_filter( 'wpdev_check_for_active_language', $maybe_relative_link );           //FixIn: 8.4.5.1

				$maybe_relative_link = home_url() . '/' . trim( wp_make_link_relative( $maybe_relative_link ), '/' );        //FixIn: 7.0.1.20
			}

			return esc_js( $maybe_relative_link );
		}


		/**
		 * Redirect browser to a specific page
		 *
		 * @param string $url - URL of page to redirect
		 */
		function wpbc_redirect( $url ) {

			$url = wpbc_make_link_absolute( $url );

			$url = html_entity_decode( esc_url( $url ) );

			echo '<script type="text/javascript">';
			echo 'window.location.href="'.$url.'";';
			echo '</script>';
			echo '<noscript>';
			echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
			echo '</noscript>';
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Get Admin Menu URLs  ==  "  >

		/**
		 * Check  if we edit or create a new page in   WordPress ?
		 * @return bool
		 */
		function wpbc_is_on_edit_page() {

			//FixIn: 9.9.0.39

			if ( ( ! empty( $GLOBALS['pagenow'] ) ) && ( is_admin() ) ) {
				if (
					   ( 'post.php'     === $GLOBALS['pagenow'] )		    // Edit - Post / Page
					|| ( 'post-new.php' === $GLOBALS['pagenow'] )			// Add New - Post / Page
					|| ( ( 'admin-ajax.php' === $GLOBALS['pagenow'] ) && ( ! empty( $_REQUEST['action'] ) ) && ( 'elementor_ajax' === $_REQUEST['action'] ) )		// Elementor Edit page - Ajax
				) {
					return true;
				}
			}

			return false;
		}


		/**
		 * Get URL to specific Admin Menu page
		 *
		 * @param string $menu_type         -   { booking | add | resources | settings }
		 * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
		 * @param boolean $is_old           - { default: true }
		 * @return string                   - URL  to  menu
		 */
		function wpbc_get_menu_url( $menu_type, $is_absolute_url = true, $is_old = true) {

			$is_old = false;

			switch ( $menu_type) {

				case 'booking':                                                     // Bookings
				case 'bookings':
				case 'booking-listing':
				case 'bookings-listing':
				case 'listing':
				case 'overview':
				case 'calendar-overview':
				case 'timeline':
					$link = 'wpbc';
					break;

				case 'add':                                                         // Add New Booking
				case 'add-bookings':
				case 'add-booking':
				case 'new':
				case 'new-bookings':
				case 'new-booking':
					$link = 'wpbc-new';
					break;

				case 'availability':
					$link = 'wpbc-availability';
					break;

				case 'price':
					$link = 'wpbc-prices';
					break;

				case 'resources':                                                   // Resources
				case 'booking-resources':
					$link = 'wpbc-resources';
					break;

				case 'settings':                                                    // Settings
				case 'options':
					$link = 'wpbc-settings';
					break;

				default:                                                            // Bookings
					$link = 'wpbc';
					break;
			}

			if ( $is_absolute_url ) {
				$link = admin_url( 'admin.php' ) . '?page=' . $link ;
			}

			return $link;
		}

		// -----------------------------------------------------------------------------------------------------------------

		/**
		 * Get URL of Booking Listing or Calendar Overview page
		 *
		 * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
		 * @param boolean $is_old           - { default: true }
		 * @return string                   - URL  to  menu
		 */
		function wpbc_get_bookings_url( $is_absolute_url = true, $is_old = true ) {
			return wpbc_get_menu_url( 'booking', $is_absolute_url, $is_old );
		}

		/**
		 * Get URL of Booking > Availability page
		 *
		 * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
		 * @param boolean $is_old           - { default: true }
		 * @return string                   - URL  to  menu
		 */
		function wpbc_get_availability_url( $is_absolute_url = true, $is_old = true ) {
			return wpbc_get_menu_url( 'availability', $is_absolute_url, $is_old );
		}

		/**
		 * Get URL of Booking > Availability page
		 *
		 * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
		 * @param boolean $is_old           - { default: true }
		 * @return string                   - URL  to  menu
		 */
		function wpbc_get_price_url( $is_absolute_url = true, $is_old = true ) {
			return wpbc_get_menu_url( 'price', $is_absolute_url, $is_old );
		}

		/**
		 * Get URL of Booking > Add booking page
		 *
		 * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
		 * @param boolean $is_old           - { default: true }
		 * @return string                   - URL  to  menu
		 */
		function wpbc_get_new_booking_url( $is_absolute_url = true, $is_old = true ) {
			return wpbc_get_menu_url( 'add', $is_absolute_url, $is_old );
		}

		/**
		 * Get URL of Booking > Resources page
		 *
		 * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
		 * @param boolean $is_old           - { default: true }
		 * @return string                   - URL  to  menu
		 */
		function wpbc_get_resources_url( $is_absolute_url = true, $is_old = true ) {
			return wpbc_get_menu_url( 'resources', $is_absolute_url, $is_old );
		}

		/**
		 * Get URL of Booking > Settings page
		 *
		 * @param boolean $is_absolute_url  - Absolute or relative url { default: true }
		 * @param boolean $is_old           - { default: true }
		 * @return string                   - URL  to  menu
		 */
		function wpbc_get_settings_url( $is_absolute_url = true, $is_old = true ) {
			return wpbc_get_menu_url( 'settings', $is_absolute_url, $is_old );
		}

		// -----------------------------------------------------------------------------------------------------------------

		/**
		 * Check if this Booking Listing or Calendar Overview page
		 * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
		 * @return boolean true | false
		 */
		function wpbc_is_bookings_page( $server_param = 'REQUEST_URI' ) {
			// Old
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking') !== false ) &&
				  ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking-reservation') === false )
				) {
				return true;
			}
			// New
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'page=wpbc') !== false ) &&
				  ( strpos($_SERVER[ $server_param ],'page=wpbc-') === false )
				) {
				return true;
			}
			return false;
		}

		/**
		 * Check if this Booking > Add booking page
		 * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
		 * @return boolean true | false
		 */
		function wpbc_is_new_booking_page( $server_param = 'REQUEST_URI' ) {
			// Old
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking-reservation') !== false )
				) {
				return true;
			}
			// New
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'page=wpbc-new') !== false )
				) {
				return true;
			}
			return false;
		}

		/**
		 * Check if this Booking > Availability page
		 * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
		 * @return boolean true | false
		 */
		function wpbc_is_availability_page( $server_param = 'REQUEST_URI' ) {

			// New
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'page=wpbc-availability') !== false )
				) {
				return true;
			}
			return false;
		}


		/**
		 * Check if this Booking > Customize page
		 * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
		 * @return boolean true | false
		 */
		function wpbc_is_customize_plugin_page( $server_param = 'REQUEST_URI' ) {                                            //FixIn: 9.8.0.1

			// New
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'page=wpbc-customize_plugin') !== false )
				) {
				return true;
			}
			return false;
		}


		// -----------------------------------------------------------------------------------------------------------------

		/**
		 * Check if this Booking > Resources page
		 * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
		 * @return boolean true | false
		 */
		function wpbc_is_resources_page( $server_param = 'REQUEST_URI' ) {

			// Old
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking-resources') !== false )
				) {
				return true;
			}
			// New
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'page=wpbc-resources') !== false )
				) {
				return true;
			}
			return false;
		}

		/**
		 * Check if this Booking > Settings page
		 * @param string $server_param -  'REQUEST_URI' | 'HTTP_REFERER'  Default: 'REQUEST_URI'
		 * @return boolean true | false
		 */
		function wpbc_is_settings_page( $server_param = 'REQUEST_URI' ) {

			// Old
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'wpdev-booking.phpwpdev-booking-option') !== false )
				) {
				return true;
			}
			// New
			if (  ( is_admin() ) &&
				  ( strpos($_SERVER[ $server_param ],'page=wpbc-settings') !== false )
				) {
				return true;
			}
			return false;
		}


		// -----------------------------------------------------------------------------------------------------------------


		/**
		 * Transform the REQESTS parameters (GET and POST) into URL
		 *
		 * @param  $page_param
		 * @param array $exclude_params
		 * @param  $only_these_parameters
		 * @return
		 */
		function wpbc_get_params_in_url( $page_param , $exclude_params = array(), $only_these_parameters = false, $is_escape_url = true, $only_get = false ){			//FixIn: 8.0.1.101     //Fix: $is_escape_url = false

			$exclude_params[] = 'page';
			$exclude_params[] = 'post_type';

			if ( isset( $_GET['page'] ) )
				$page_param = $_GET['page'];

			$get_paramaters = array( 'page' => $page_param );

			if ( $only_get )
				$check_params = $_GET;
			else
				$check_params = $_REQUEST;
		//debuge($check_params);
			foreach ( $check_params as $prm_key => $prm_value ) {

				// Skip  parameters arrays,  like $_GET['rvaluation_to'] = Array ( [0] => 6,  [1] => 14,  [2] => 14 )
				if (
					   (  is_string( $prm_value ) )
					|| ( is_numeric( $prm_value ) )
					) {

					if ( strlen( $prm_value ) > 1000 ) {                                    // Check  about TOOO long parameters,  if it exist  then  reset it.
						$prm_value = '';
					}

					if ( ! in_array( $prm_key, $exclude_params ) )
						if ( ( $only_these_parameters === false ) || ( in_array( $prm_key, $only_these_parameters ) ) )
								$get_paramaters[ $prm_key ] = $prm_value;
				}
			}
		//debuge($exclude_params);
			$url = admin_url( add_query_arg(  $get_paramaters , 'admin.php' ) );

			if ( $is_escape_url )
				$url = esc_url_raw( $url );							//FixIn: 8.1.1.7
				// $url = esc_url( $url );

			return $url;

			/*      // Old variant:
					if ( isset( $_GET['page'] ) ) $page_param = $_GET['page'];

					$url_start = 'admin.php?page=' . $page_param . '&';
					$exclude_params[] = 'page';
					foreach ( $_REQUEST as $prm_key => $prm_value ) {

						if ( !in_array( $prm_key, $exclude_params ) )
							if ( ($only_these_parameters === false) || ( in_array( $prm_key, $only_these_parameters ) ) )

								$url_start .= $prm_key . '=' . $prm_value . '&';

					}
					$url_start = substr( $url_start, 0, -1 );

					return $url_start;
			 */
		}


    // </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Admin  Top  Bar  ==  "  >

		function wpbc_add__booking_menu__in__admin_top_bar(){
			global $wp_admin_bar;

			$current_user = wpbc_get_current_user();

			$curr_user_role = get_bk_option( 'booking_user_role_booking' );
			$level = 10;
			if ($curr_user_role == 'administrator')       $level = 10;
			else if ($curr_user_role == 'editor')         $level = 7;
			else if ($curr_user_role == 'author')         $level = 2;
			else if ($curr_user_role == 'contributor')    $level = 1;
			else if ($curr_user_role == 'subscriber')     $level = 0;

			$is_super_admin = apply_bk_filter('multiuser_is_user_can_be_here', false, 'only_super_admin');
			if (   ( ($current_user->user_level < $level) && (! $is_super_admin)  ) || !is_admin_bar_showing() ) return;


			$update_count = wpbc_db_get_number_new_bookings();

			$title = 'Booking Calendar';//__('Booking Calendar' ,'booking');	//FixIn: 9.1.3.3
			$update_title = ''// '<img src="'.WPBC_PLUGIN_URL .'/assets/img/icon-16x16.png" style="height: 16px;vertical-align: sub;" />&nbsp;'
							. $title;



			$is_user_activated = apply_bk_filter('multiuser_is_current_user_active',  true );           //FixIn: 6.0.1.17
			if ( ( $update_count > 0) && ( $is_user_activated ) ) {
				$update_count_title = "&nbsp;<span class='booking-count bk-update-count' style='background: #f0f0f1;color: #2c3338;display: inline;padding: 2px 5px;font-weight: 600;border-radius: 10px;'>"
									  . number_format_i18n($update_count)
									  . "</span>" ; //id='booking-count'
				$update_title .= $update_count_title;
			}

			$link_bookings = wpbc_get_bookings_url();
			$link_res      = wpbc_get_resources_url();
			$link_settings = wpbc_get_settings_url();

			//FixIn: 9.8.15.9
			$wp_admin_bar->add_menu(
					array(
						'id' => 'bar_wpbc',
						'title' => $update_title ,
						'href' => wpbc_get_bookings_url()
						)
					);

			$wp_admin_bar->add_menu(
					array(
						'id' => 'bar_wpbc_bookings',
						'title' => __( 'Bookings', 'booking' ),
						'href' => wpbc_get_bookings_url(),
						'parent' => 'bar_wpbc',
					)
			);
				$wp_admin_bar->add_menu(
						array(
							'id' => 'bar_wpbc_booking_listing',
							'title' => __( 'Booking Listing', 'booking' ),
							'href' => wpbc_get_bookings_url() . '&view_mode=vm_listing',
							'parent' => 'bar_wpbc_bookings',
						)
				);
				$wp_admin_bar->add_menu(
						array(
							'id' => 'bar_wpbc_calendar_overview',
							'title' => __( 'Calendar Overview', 'booking' ),
							'href' => wpbc_get_bookings_url() . '&view_mode=vm_calendar',
							'parent' => 'bar_wpbc_bookings',
						)
				);


			 $curr_user_role_settings = get_bk_option( 'booking_user_role_settings' );
			 $level = 10;
			 if ($curr_user_role_settings == 'administrator')       $level = 10;
			 else if ($curr_user_role_settings == 'editor')         $level = 7;
			 else if ($curr_user_role_settings == 'author')         $level = 2;
			 else if ($curr_user_role_settings == 'contributor')    $level = 1;
			 else if ($curr_user_role_settings == 'subscriber')     $level = 0;

			 if (   ( ($current_user->user_level < $level) && (! $is_super_admin)  ) || !is_admin_bar_showing() ) return;

			// Booking > Add booking page
			$wp_admin_bar->add_menu(
					array(
						'id' => 'bar_wpbc_new',
						'title' => __( 'Add booking', 'booking' ),
						'href' => wpbc_get_new_booking_url(),
						'parent' => 'bar_wpbc',
					)
			);

			// Booking > Availability page
			$wp_admin_bar->add_menu(
					array(
						'id' => 'bar_wpbc_availability',
						'title' => __( 'Availability', 'booking' ),
						'href' => wpbc_get_availability_url(),
						'parent' => 'bar_wpbc',
					)
			);
					$wp_admin_bar->add_menu(
						array(
							'id' => 'bar_wpbc_days_availability',
							'title' => __( 'Days Availability', 'booking' ),
							'href' => wpbc_get_availability_url() ,
							'parent' => 'bar_wpbc_availability'
						)
					);
					if ( class_exists( 'wpdev_bk_biz_m' ) ){
						$wp_admin_bar->add_menu(
							array(
								'id' => 'bar_wpbc_seasons_availability',
								'title' => __( 'Season Availability', 'booking' ),
								'href' => wpbc_get_availability_url() . '&tab=season_availability',
								'parent' => 'bar_wpbc_availability'
							)
						);

						$wp_admin_bar->add_menu(
							array(
								'id' => 'bar_wpbc_seasons_filters',
								'title' => __( 'Seasons', 'booking' ),
								'href' => wpbc_get_availability_url() . '&tab=filter',
								'parent' => 'bar_wpbc_availability'
							)
						);
					}
					$wp_admin_bar->add_menu(
						array(
							'id' => 'bar_wpbc_general_availability',
							'title' => __( 'General Availability', 'booking' ),
							'href' => wpbc_get_settings_url() . '&scroll_to_section=wpbc_general_settings_availability_tab',
							'parent' => 'bar_wpbc_availability'
						)
					);

			// Booking > Prices page
			if ( class_exists( 'wpdev_bk_biz_m' ) ){

				$wp_admin_bar->add_menu(
						array(
							'id' => 'bar_wpbc_prices',
							'title' => __( 'Prices', 'booking' ),
							'href' => wpbc_get_price_url(),
							'parent' => 'bar_wpbc',
						)
				);
					$wp_admin_bar->add_menu(
							array(
								'id' => 'bar_wpbc_daily_costs',
								'title' => __('Daily Costs','booking'),
								'href' => wpbc_get_price_url() . '&tab=cost',
								'parent' => 'bar_wpbc_prices',
							)
					);
					$wp_admin_bar->add_menu(
							array(
								'id' => 'bar_wpbc_cost_advanced',
								'title' => __('Form Options Costs','booking'),
								'href' => wpbc_get_price_url() . '&tab=cost_advanced',
								'parent' => 'bar_wpbc_prices',
							)
					);
					if ( class_exists( 'wpdev_bk_biz_l' ) ){
						$wp_admin_bar->add_menu(
								array(
									'id' => 'bar_wpbc_coupons',
									'title' => __('Discount Coupons','booking'),
									'href' => wpbc_get_price_url() . '&tab=coupons',
									'parent' => 'bar_wpbc_prices',
								)
						);
					}
					$wp_admin_bar->add_menu(
						array(
							'id' => 'bar_wpbc_seasons_costs',
							'title' => __( 'Seasons', 'booking' ),
							'href' =>  wpbc_get_price_url() . '&tab=filter',
							'parent' => 'bar_wpbc_prices'
						)
					);
					$wp_admin_bar->add_menu(
						array(
							'id' => 'bar_wpbc_costs_payment_gateways',
							'title' => __( 'Payment Gateways', 'booking' ),
							'href' =>  wpbc_get_settings_url() . '&tab=payment',
							'parent' => 'bar_wpbc_prices'
						)
					);

			}

			//Booking > Resources page
			$wp_admin_bar->add_menu(
					array(
						'id' => 'bar_wpbc_resources',
						'title' => ( ( class_exists( 'wpdev_bk_personal' ) ) ? __( 'Resources', 'booking' ) : __( 'Resource', 'booking' ) ),
						'href' => $link_res,
						'parent' => 'bar_wpbc',
					)
				);



			//Booking > Settings General page

			$wp_admin_bar->add_menu(
					array(
						'id' => 'bar_wpbc_settings',
						'title' => __( 'Settings', 'booking' ),
						'href' => wpbc_get_settings_url(),
						'parent' => 'bar_wpbc',
					)
			);

					$wp_admin_bar->add_menu(
							array(
								'id' => 'bar_wpbc_settings_form',
								'title' => __( 'Booking Form', 'booking' ),
								'href' => $link_settings . '&tab=form',
								'parent' => 'bar_wpbc_settings'
							)
					);
					$wp_admin_bar->add_menu(
							array(
								'id' => 'bar_wpbc_settings_email',
								'title' => __( 'Emails', 'booking' ),
								'href' => $link_settings . '&tab=email',
								'parent' => 'bar_wpbc_settings'
							)
					);
					$wp_admin_bar->add_menu(
							array(
								'id' => 'bar_wpbc_settings_sync',
								'title' => __( 'Sync', 'booking' ),															//FixIn: 8.0
								'href' => $link_settings . '&tab=sync',
								'parent' => 'bar_wpbc_settings'
							)
					);
					if ( class_exists( 'wpdev_bk_biz_s' ) )
						$wp_admin_bar->add_menu(
							array(
								'id' => 'bar_wpbc_settings_payment',
								'title' => __( 'Payment Gateways', 'booking' ),
								'href' => $link_settings . '&tab=payment',
								'parent' => 'bar_wpbc_settings'
							)
						);
					if ($is_super_admin)
						if ( class_exists( 'wpdev_bk_biz_l' ) )
							$wp_admin_bar->add_menu(
								array(
									'id' => 'bar_wpbc_settings_search',
									'title' => __( 'Search Availability Form', 'booking' ),
									'href' => $link_settings . '&tab=search',
									'parent' => 'bar_wpbc_settings'
								)
							);
					if ($is_super_admin)
						if ( class_exists( 'wpdev_bk_multiuser' ) )
							$wp_admin_bar->add_menu(
								array(
									'id' => 'bar_wpbc_settings_users',
									'title' => __( 'Users', 'booking' ),
									'href' => $link_settings . '&tab=users',
									'parent' => 'bar_wpbc_settings'
								)
							);
		}
		add_action( 'admin_bar_menu', 'wpbc_add__booking_menu__in__admin_top_bar', 70 );									    // Add   -  TOP Bar   - in admin menu


	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Footer  ==  "  >

		function wpbc_show_booking_footer(){

				$wpdev_copyright_adminpanel = get_bk_option( 'booking_wpdev_copyright_adminpanel' );             // check

				$message = '';



				if ( ( 'Off' !== $wpdev_copyright_adminpanel ) && ( ! wpbc_is_this_demo() ) ) {

					/*
								$message .= '<a target="_blank" href="https://wpbookingcalendar.com/">Booking Calendar</a> ' . __('version' ,'booking') . ' ' . WP_BK_VERSION_NUM ;

								$message .= ' | '. sprintf(__('Add your %s on %swordpress.org%s, if you enjoyed by this plugin.' ,'booking'),
												'<a target="_blank" href="http://goo.gl/tcrrpK" >&#9733;&#9733;&#9733;&#9733;&#9733;</a>',
												'<a target="_blank" href="http://goo.gl/tcrrpK" >',
												'</a>'   );
					*/
					$message .= sprintf( __( 'If you like %s please leave us a %s rating. A huge thank you in advance!', 'booking' )
										, '<strong>Booking Calendar</strong> ' . WP_BK_VERSION_NUM
										, '<a href="https://wordpress.org/support/plugin/booking/reviews/#new-post" target="_blank" title="' . esc_attr__( 'Thanks :)', 'booking' ) . '">'
											  . '&#9733;&#9733;&#9733;&#9733;&#9733;'
											  . '</a>'
					);
				}


				if ( ! empty( $message ) ) {

					echo '<div id="wpbc-footer" style="position:absolute;bottom:40px;text-align:left;width:95%;font-size:0.9em;text-shadow:0 1px 0 #fff;margin:0;color:#888;">' . $message . '</div>';

					?><script type="text/javascript">
						jQuery( document ).ready( function (){
							jQuery( '#wpfooter' ).append( jQuery( '#wpbc-footer' ) );
						} );
					</script><?php
				}
			}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==   P a g i n a t i o n    o f    T a b l e    L  i s t i n g  ==  "  >

		/**
			 * Show    P a g i n a t i o n
		 *
		 * @param int $summ_number_of_items     - total  number of items
		 * @param int $active_page_num          - number of activated page
		 * @param int $num_items_per_page       - number of items per page
		 * @param array $only_these_parameters  - array of keys to exclude from links
		 * @param string $url_sufix             - usefule for anchor to  HTML section  with  specific ID,  Example: '#my_section'
		 */
		function wpbc_show_pagination( $summ_number_of_items, $active_page_num, $num_items_per_page , $only_these_parameters = false, $url_sufix = '' ) {

			if ( empty( $num_items_per_page ) ) {
				$num_items_per_page = '10';
			}

			$pages_number = ceil( $summ_number_of_items / $num_items_per_page );
			if ( $pages_number < 2 )
				return;

					//Fix: 5.1.4 - Just in case we are having tooo much  resources, then we need to show all resources - and its empty string
					if ( ( isset($_REQUEST['wh_booking_type'] ) ) && ( strlen($_REQUEST['wh_booking_type']) > 1000 ) ) {
						$_REQUEST['wh_booking_type'] = '';
					}

			// First  parameter  will overwriten by $_GET['page'] parameter
			$bk_admin_url = wpbc_get_params_in_url( wpbc_get_bookings_url( false, false ), array('page_num'), $only_these_parameters );


			?>
			<span class="wpdevelop wpbc-pagination">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-12 text-center control-group0">
							<nav class="btn-toolbar">
							  <div class="btn-group wpbc-no-margin" style="float:none;">

								<?php if ( $pages_number > 1 ) { ?>
										<a class="button button-secondary <?php echo ( $active_page_num == 1 ) ? ' disabled' : ''; ?>"
										   href="<?php echo $bk_admin_url; ?>&page_num=<?php if ($active_page_num == 1) { echo $active_page_num; } else { echo ($active_page_num-1); } echo $url_sufix; ?>">
											<?php _e('Prev', 'booking'); ?>
										</a>
								<?php }

								/** Number visible pages (links) that linked to active page, other pages skipped by "..." */
								$num_closed_steps = 3;

								for ( $pg_num = 1; $pg_num <= $pages_number; $pg_num++ ) {

										if ( ! (
												   ( $pages_number > ( $num_closed_steps * 4) )
												&& ( $pg_num > $num_closed_steps )
												&& ( ( $pages_number - $pg_num + 1 ) > $num_closed_steps )
												&& (  abs( $active_page_num - $pg_num ) > $num_closed_steps )
										   ) ) {
											?> <a class="button button-secondary <?php if ($pg_num == $active_page_num ) echo ' active'; ?>"
												 href="<?php echo $bk_admin_url; ?>&page_num=<?php echo $pg_num;  echo $url_sufix; ?>">
												<?php echo $pg_num; ?>
											  </a><?php

											if ( ( $pages_number > ( $num_closed_steps * 4) )
													&& ( ($pg_num+1) > $num_closed_steps )
													&& ( ( $pages_number - ( $pg_num + 1 ) ) > $num_closed_steps )
													&&  ( abs($active_page_num - ( $pg_num + 1 ) ) > $num_closed_steps )
												) {
												echo ' <a class="button button-secondary disabled" href="javascript:void(0);">...</a> ';
											}
										}
								}

								if ( $pages_number > 1 ) { ?>
										<a class="button button-secondary <?php echo ( $active_page_num == $pages_number ) ? ' disabled' : ''; ?>"
										   href="<?php echo $bk_admin_url; ?>&page_num=<?php  if ($active_page_num == $pages_number) { echo $active_page_num; } else { echo ($active_page_num+1); }  echo $url_sufix; ?>">
											<?php _e('Next', 'booking'); ?>
										</a>
								<?php } ?>

							  </div>
							</nav>
						</div>
					</div>
				</div>
			</span>
			<?php
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  String Manipulation functions  ==  "  >

		/**
		 * Insert New Line symbols after <br> tags. Usefull for the settings pages to  show in redable view
		 *
		 * @param string $param
		 * @return string
		 */
		function wpbc_nl_after_br($param) {

			$value = preg_replace( "@(&lt;|<)br\s*/?(&gt;|>)(\r\n)?@", "<br/>", $param );

			return $value;
		}


		/**
		 * Replace ** to <strong> and * to  <em>
		 *
		 * @param String $text
		 * @return string
		 */
		function wpbc_replace_to_strong_symbols( $text ){

			$patterns =  '/(\*\*)(\s*[^\*\*]*)(\*\*)/';
			$replacement = '<strong>${2}</strong>';
			$value_return = preg_replace($patterns, $replacement, $text);

			$patterns =  '/(\*)(\s*[^\*]*)(\*)/';
			$replacement = '<em>${2}</em>';
			$value_return = preg_replace($patterns, $replacement, $value_return);

			return $value_return;
		}


		/**
		 * Replace 'true' | 'false' to __('yes') | __('no'). E.g.:    '...Fee: true...' => '...Fee: yes...'
		 *
		 * Replace value 'true' to  localized __( 'yes', 'booking' ) in Content -- usually it's required before showing text to  user for saved data of selected checkboxes, that  was configured with  empty  value: [checkbox fee ""]
		 *
		 * @param $value
		 *
		 * @return array|string|string[]
		 */
		function wpbc_replace__true_false__to__yes_no( $value ) {                                                                    //FixIn: 9.8.9.1

			$checkbox_true_value = apply_filters( 'wpbc_checkbox_true_value', __( 'Y_E_S', 'booking' ) );
			$value_replaced = str_replace( 'true', $checkbox_true_value, $value );

			$checkbox_true_value = apply_filters( 'wpbc_checkbox_false_value', __( 'N_O', 'booking' ) );
			$value_replaced = str_replace( 'false', $checkbox_true_value, $value_replaced );

			return $value_replaced;
		}


		/**
		 * Convert Strings in array  to Lower case
		 * @param $array
		 *
		 * @return mixed
		 */
		function wpbc_convert__strings_in_arr__to_lower( $array ){
			return unserialize( strtolower( serialize( $array ) ) );
		}

		/**
		 * Prepare text to show as HTML,  replace double encoded \\n to <br>  and escape \\" and  \\' . 		Mainly used in Ajax.
		 *
		 * @param $text
		 *
		 * @return array|string|string[]|null
		 */
		function wpbc_prepare_text_for_html( $text ){

			/**
			 * Replace <p> | <br> to ' '
			 *
			 * $plain_form_data_show = preg_replace( '/<(br|p)[\t\s]*[\/]?>/i', ' ', $plain_form_data_show );
			 */
			$text = preg_replace( '/(\\\\n)/i', '<br>', $text );                                // New line in text  areas replace with <br>
			$text = preg_replace( '/(\\\\")/i', '&quot;', html_entity_decode( $text ) );        // escape quote symbols;
			$text = preg_replace( "/(\\\\')/i", '&apos;', html_entity_decode( $text ) );        // escape quote symbols;

			// Replace \r \n \t
			$text = preg_replace( '/(\\r|\\n|\\t)/i', ' ', $text );

			return $text;
		}


		// TODO: Need to  check if we really  need to make this.      2023-10-06 12:46
		/**
		 * Escaping text  for output.
		 *
		 * @param string $output
		 *
		 * @return string
		 */
		function wpbc_escaping_text_for_output( $output ){

			// Save empty  spaces
			$original_symbols  = array( '&nbsp;'  );
			$temporary_symbols = array( '^space^' );
			$output = str_replace( $original_symbols, $temporary_symbols, $output );					// &nbsp; 	-> 	^space^

			// Escaping ?
			$output = esc_js( $output );									// it adds 		'\n' symbols  	|		" into &quot		|  		<  ->  &lt;  		...
			$output = html_entity_decode( $output );						// Convert back  to HTML,  but now we have 	'\n' symbols
			$output = str_replace( "\\n", '', $output );					// Remove '\n' symbols

			// Back to empty spaces.
			$original_symbols  = array( '^space^'  );
			$temporary_symbols = array( '&nbsp;'   );
			$output = str_replace( $original_symbols, $temporary_symbols, $output);

			return $output;
		}


		/**
		 * Convert text  with  escaped symbols like '1. Soem text  here\n2. \&quot;Quoted text\&quot;\n3. \&#039;Single quoted text\&#039;\n'        to        HTML:
		 *
		 * @param $text
		 *
		 * @return array|string|string[]
		 */
		function wpbc_string__escape__then_convert__n_amp__html( $text ) {

			$is_escape_sql = false;	// Do not replace %

			$escaped_text = wpbc_escape_any_xss_in_string($text, $is_escape_sql );

			$text_html = str_replace( array( "\\r\\n", "\\r", "\\n", "\r\n", "\r","\n" ), "<br/>", $escaped_text );            //FixIn: 8.1.3.4

			$text_html = str_replace( array( "\\&" ), '&', $text_html );

			return $text_html;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  DB - cheking if table, field or index exists  ==  "  >

		/**
		 * Check if table exist
		 *
		 * @global  $wpdb
		 * @param string $tablename
		 * @return 0|1
		 */
		function wpbc_is_table_exists( $tablename ) {

			global $wpdb;

			if (
				   ( ( ! empty( $wpdb->prefix ) ) && ( strpos( $tablename, $wpdb->prefix ) === false ) )
				|| ( '_' == $wpdb->prefix )																					//FixIn: 8.7.3.16
			) {
				$tablename = $wpdb->prefix . $tablename;
			}

			if ( 0 ) {
				$sql_check_table = $wpdb->prepare( "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE (TABLE_SCHEMA = '{$wpdb->dbname}') AND (TABLE_NAME = %s);", $tablename );

				$res = $wpdb->get_results( $sql_check_table );

				return count( $res );

			} else {

				$sql_check_table = $wpdb->prepare("SHOW TABLES LIKE %s" , $tablename ); 									//FixIn: 5.4.3

				$res = $wpdb->get_results( $sql_check_table );

				return count( $res );
			}

		}


		/**
		 * Check if table exist
		 *
		 * @global  $wpdb
		 * @param string $tablename
		 * @param  $fieldname
		 * @return 0|1
		 */
		function wpbc_is_field_in_table_exists( $tablename , $fieldname) {

			global $wpdb;

			if (
				   ( ( ! empty( $wpdb->prefix ) ) && ( strpos( $tablename, $wpdb->prefix ) === false ) )
				|| ( '_' == $wpdb->prefix )																					//FixIn: 8.7.3.16
			) {
				$tablename = $wpdb->prefix . $tablename;
			}

			if ( 0 ) {

				$sql_check_table = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='{$tablename}' AND TABLE_SCHEMA='{$wpdb->dbname}' ";

				$res = $wpdb->get_results( $sql_check_table );

				foreach ( $res as $fld ) {
					if ( $fieldname === $fld->COLUMN_NAME ) {
						return 1;
					}
				}

			} else {

				$sql_check_table = "SHOW COLUMNS FROM {$tablename}";

				$res = $wpdb->get_results( $sql_check_table );

				foreach ( $res as $fld ) {
					if ( $fld->Field == $fieldname ) {
						return 1;
					}
				}
			}

			return 0;
		}


		/**
		 * Check if index exist
		 *
		 * @global  $wpdb
		 * @param string $tablename
		 * @param  $fieldindex
		 * @return 0|1
		 */
		function wpbc_is_index_in_table_exists( $tablename , $fieldindex) {
			global $wpdb;
			if ( (! empty($wpdb->prefix) ) && ( strpos($tablename, $wpdb->prefix) === false ) ) $tablename = $wpdb->prefix . $tablename ;
			$sql_check_table = $wpdb->prepare("SHOW INDEX FROM {$tablename} WHERE Key_name = %s", $fieldindex );
			$res = $wpdb->get_results( $sql_check_table );
			if (count($res)>0) return 1;
			else               return 0;
		}

    // </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Security: Escaping & Sanitizing  ==  "  >


		/**
		 * Sanitize term to Slug format (no spaces, lowercase).
		 * urldecode - reverse munging of UTF8 characters.
		 *
		 * @param mixed $value
		 * @return string
		 */
		function wpbc_get_slug_format( $value ) {
			return  urldecode( sanitize_title( $value ) );
		}
		

		/**
		 * Check  paramter  if it number or comma separated list  of numbers
		 *
		 * @global type $wpdb
		 * @param string $value
		 * @return string
		 *
		 * Exmaple:
							wpbc_clean_digit_or_csd( '12,a,45,9' )                  => '12,0,45,9'
		 * or
							wpbc_clean_digit_or_csd( '10a' )                        => '10
		 * or
							wpbc_clean_digit_or_csd( array( '12,a,45,9', '10a' ) )  => array ( '12,0,45,9',  '10' )
		 */
		function wpbc_clean_digit_or_csd( $value ) {                                //FixIn:6.2.1.4

			if ( $value === '' ) return $value;


			if ( is_array( $value ) ) {
				foreach ( $value as $key => $check_value ) {
					$value[ $key ] = wpbc_clean_digit_or_csd( $check_value );
				}
				return $value;
			}

			$value = str_replace( ';', ',', $value );

			$array_of_nums = explode(',', $value);

			$result = array();
			foreach ($array_of_nums as $check_element) {

				$result[] = intval( $check_element );						//FixIn: 8.0.2.10
			}
			$result = implode(',', $result );
			return $result;
		}


		/**
		 * Cehck  about Valid date,  like 2016-07-20 or digit
		 *
		 * @param string $value
		 * @return string or int
		 */
		function wpbc_clean_digit_or_date( $value ) {                               //FixIn:6.2.1.4

			if ( $value === '' ) return $value;

			if ( preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value ) ) {

				return $value;                                                      // Date is valid in format: 2016-07-20
			} else {
				return intval( $value );
			}

		}


		/**
		 * Escape any XSS injection from  values in booking form
		 *
		 * @param array $structured_booking_data_arr    [...]
		 *
		 * @return array                                [...]
		 */
		function wpbc_escape_any_xss_in_arr( $structured_booking_data_arr ) {

			foreach ( $structured_booking_data_arr as $field_name => $field_value ) {

				if ( is_array( $field_value ) ) {

					$structured_booking_data_arr[ $field_name ] = wpbc_escape_any_xss_in_arr( $field_value );

				} else {
					$is_escape_sql = false;	// Do not replace %
					$field_value_cleaned = wpbc_escape_any_xss_in_string( $field_value, $is_escape_sql );
					$structured_booking_data_arr[ $field_name ] = $field_value_cleaned;
				}
			}

			return $structured_booking_data_arr;
		}


		/**
		 * Escape any XSS injection from  string values
		 *
		 * @param string $field_value
		 *
		 * @return string
		 */
		function wpbc_escape_any_xss_in_string( $field_value, $is_escape_sql = true ) {

			$field_value_cleaned = wpbc_clean_parameter( $field_value, $is_escape_sql );
			$field_value_cleaned = str_replace( '%', '&#37;', $field_value_cleaned );                                   // clean % in form, because can be problems with SQL prepare function

			return $field_value_cleaned;
		}


		// check $value for injection here
		function wpbc_clean_parameter( $value, $is_escape_sql = true ) {

			$value = preg_replace( '/<[^>]*>/', '', $value );                       // clean any tags
			$value = str_replace( '<', ' ', $value );
			$value = str_replace( '>', ' ', $value );
			$value = strip_tags( $value );

			//FixIn: 9.7.4.1	-	escape coded html/xss							// Escape any XSS injection
			$value = sanitize_textarea_field( $value );
			$value = sanitize_textarea_field( html_entity_decode( $value ) );		// If we have field converted to 'Unicode Hex Character Code', then  we make HTML decode firstly (html_entity_decode) and then make sanitizing

			if ( $is_escape_sql ) {
				$value = esc_sql( $value );			// Clean SQL injection					//FixIn: 9.7.4.2
			}

			$value = esc_textarea( $value );																				//FixIn: 7.1.1.2

			return $value;
		}


		function wpbc_esc_like( $value_trimmed ) {

			global $wpdb;
			if ( method_exists( $wpdb ,'esc_like' ) )
				return $wpdb->esc_like( $value_trimmed );                           // Its require minimum WP 4.0.0
			else
				return addcslashes( $value_trimmed, '_%\\' );                       // Direct implementation  from $wpdb->esc_like(
		}


		/**
		 * Clean user string for using in SQL LIKE statement - append to  LIKE sql
		 *
		 * @param string $value - to clean
		 * @return string       - escaped
		 *                                  Exmaple:
		 *                                              $search_escaped_like_title = wpbc_clean_like_string_for_append_in_sql_for_db( $input_var );
		 *
		 *                                              $where_sql = " WHERE title LIKE ". $search_escaped_like_title ." ";
		 */
		function wpbc_clean_like_string_for_append_in_sql_for_db( $value ) {
			global $wpdb;

			$value_trimmed = trim( stripslashes( $value ) );
		$wild = '%';
		$like = $wild . wpbc_esc_like( $value_trimmed ) . $wild;
		$sql  = $wpdb->prepare( "'%s'", $like );

			return $sql;


		/* Help:
			 * First half of escaping for LIKE special characters % and _ before preparing for MySQL.
		 * Use this only before wpdb::prepare() or esc_sql().  Reversing the order is very bad for security.
		 *
		 * Example Prepared Statement:
		 *
		 *     $wild = '%';
		 *     $find = 'only 43% of planets';
		 *     $like = $wild . wpbc_esc_like( $find ) . $wild;
		 *     $sql  = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_content LIKE '%s'", $like );
		 *
		 * Example Escape Chain:
		 *
		 *     $sql  = esc_sql( wpbc_esc_like( $input ) );
		 */

		}


		/**
		 * Clean string for using in SQL LIKE requests inside single quotes:    WHERE title LIKE '%". $escaped_search_title ."%'
		 *  Replaced _ to \_     % to \%      \   to   \\
		 * @param string $value - to clean
		 * @return string       - escaped
		 *                                  Exmaple:
		 *                                              $search_escaped_like_title = wpbc_clean_like_string_for_db( $input_var );
		 *
		 *                                              $where_sql = " WHERE title LIKE '%". $search_escaped_like_title ."%' ";
		 *
		 *                                  Important! Use SINGLE quotes after in SQL query:  LIKE '%".$data."%'
		 */
		function wpbc_clean_like_string_for_db( $value ){

			global $wpdb;

			$value_trimmed = trim( stripslashes( $value ) );

			$value_trimmed =  wpbc_esc_like( $value_trimmed );

			$value = trim( $wpdb->prepare( "'%s'",  $value_trimmed ) , "'" );

			return $value;

		/* Help:
			 * First half of escaping for LIKE special characters % and _ before preparing for MySQL.
		 * Use this only before wpdb::prepare() or esc_sql().  Reversing the order is very bad for security.
		 *
		 * Example Prepared Statement:
		 *
		 *     $wild = '%';
		 *     $find = 'only 43% of planets';
		 *     $like = $wild . wpbc_esc_like( $find ) . $wild;
		 *     $sql  = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_content LIKE '%s'", $like );
		 *
		 * Example Escape Chain:
		 *
		 *     $sql  = esc_sql( wpbc_esc_like( $input ) );
		 */
		}


		/**
		 * Escape string from SQL for the HTML form field
		 *
		 * @param string $value
		 * @return string
		 *
		 * Used: esc_sql function.
		 *
		 * https://codex.wordpress.org/Function_Reference/esc_sql
		 * Note: Be careful to use this function correctly. It will only escape values to be used in strings in the query.
		 * That is, it only provides escaping for values that will be within quotes in the SQL (as in field = '{$escaped_value}').
		 * If your value is not going to be within quotes, your code will still be vulnerable to SQL injection.
		 * For example, this is vulnerable, because the escaped value is not surrounded by quotes in the SQL query:
		 * ORDER BY {$escaped_value}. As such, this function does not escape unquoted numeric values, field names, or SQL keywords.
		 *
		 */
		function wpbc_clean_string_for_form( $value ){

			global $wpdb;

			$value_trimmed = trim( stripslashes( $value ) );

			//FixIn: 8.0.2.10		//Fix for update of WP 4.8.3
			if ( method_exists( $wpdb, 'remove_placeholder_escape' ) )
				$esc_sql_value =  $wpdb->remove_placeholder_escape( esc_sql( $value_trimmed ) );
			else
				$esc_sql_value =  esc_sql(  $value_trimmed );

			//$value = trim( $wpdb->prepare( "'%s'",  $esc_sql_value ) , "'" );

			$esc_sql_value = trim( stripslashes( $esc_sql_value ) );

			return $esc_sql_value;

		}


		/**
		 * Escape shortcode parameters
		 *
		 * @param array $attr
		 *
		 * @return array
		 */
		function wpbc_escape_shortcode_params( $attr ) {                													//FixIn: 9.7.3.6.1

			if ( is_array( $attr ) ) {

				$scaped_attr = array();

				foreach ( $attr as $attr_key => $attr_val ) {
					$attr_key = esc_attr( $attr_key );
					$attr_val = esc_attr( $attr_val );
					$scaped_attr[ $attr_key ] = $attr_val;
				}
				return $scaped_attr;
			}

			if ( is_string( $attr ) ) {                            //FixIn: 9.7.3.6.2

				$scaped_attr = esc_attr( $attr );

				return $scaped_attr;
			}

			return $attr;
		}


	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Number of New Bookings  ==  "  >

		/**
		 * Reset Cache for getting Number of new bookings. After this operation,  system  will  get  number of new bookings from  the DB.
		 *
		 * @return void
		 */
		function wpbc_booking_cache__new_bookings__reset(){
			update_bk_option( 'booking_cache__new_bookings__saved_date', '' );
		}
		add_action( 'wpbc_track_new_booking', 'wpbc_booking_cache__new_bookings__reset' );
		add_action( 'wpbc_set_booking_pending', 'wpbc_booking_cache__new_bookings__reset' );
		add_action( 'wpbc_set_booking_approved', 'wpbc_booking_cache__new_bookings__reset' );
		add_action( 'wpbc_move_booking_to_trash', 'wpbc_booking_cache__new_bookings__reset' );
		add_action( 'wpbc_restore_booking_from_trash', 'wpbc_booking_cache__new_bookings__reset' );
		add_action( 'wpbc_delete_booking_completely', 'wpbc_booking_cache__new_bookings__reset' );
		add_action( 'wpbc_set_booking_as_read', 'wpbc_booking_cache__new_bookings__reset' );
		add_action( 'wpbc_set_booking_as_unread', 'wpbc_booking_cache__new_bookings__reset' );


		/**
		 * Get number of new bookings
		 * @return false|int|mixed|null
		 */
		function wpbc_db_get_number_new_bookings(){

			$new_bookings__number     = get_bk_option( 'booking_cache__new_bookings__number' );
			$new_bookings__saved_date = get_bk_option( 'booking_cache__new_bookings__saved_date' );

			$nowdate_str_ymdhis = date( 'Y-m-d H:i:s', strtotime( 'now' ) );

			if ( ! empty( $new_bookings__saved_date ) ) {

				$is_expired = ( strtotime( $new_bookings__saved_date ) <= strtotime( '-10 minutes', strtotime( $nowdate_str_ymdhis ) ) );

				if ( ! $is_expired ) {
					return $new_bookings__number;
				}
			}

			if ( 1 ) {
				global $wpdb;

				//if  ( wpbc_is_field_in_table_exists('booking','is_new') == 0 )  return 0;  // do not created this field, so return 0

				$trash_bookings = ' AND bk.trash != 1 ';                                //FixIn: 6.1.1.10  - check also  below usage of {$trash_bookings}
				$sql_req        = "SELECT bk.booking_id FROM {$wpdb->prefix}booking as bk WHERE  bk.is_new = 1 {$trash_bookings} ";

				$sql_req = apply_bk_filter( 'get_sql_for_checking_new_bookings', $sql_req );
				$sql_req = apply_bk_filter( 'get_sql_for_checking_new_bookings_multiuser', $sql_req );

				$bookings      = $wpdb->get_results( $sql_req );
				$bookings_count = count( $bookings );
			}


			update_bk_option( 'booking_cache__new_bookings__number', $bookings_count );
			update_bk_option( 'booking_cache__new_bookings__saved_date', $nowdate_str_ymdhis );

			return $bookings_count;
		}
	
	
		/**
		 * Update 'is_new' status of bookings in Database
		 *
		 * @param $id_of_new_bookings	inr or comma seperated ID of bookings. Example:  '1'  |   '3,5,7,9'
		 * @param $is_new				'0' | '1'
		 * @param $user_id				'user_id'
		 *
		 */
		function wpbc_db_update_number_new_bookings( $id_of_new_bookings, $is_new = '0' , $user_id = 1 ){
			global $wpdb;
	
			if (count($id_of_new_bookings) > 0 ) {
	
				//if  (wpbc_is_field_in_table_exists('booking','is_new') == 0)  return 0;  // do not created this field, so return 0
				
				$id_of_new_bookings = implode(',', $id_of_new_bookings);
				$id_of_new_bookings = wpbc_clean_like_string_for_db( $id_of_new_bookings );
				
				
	//debuge($id_of_new_bookings);           
				if ($id_of_new_bookings == 'all') {
					$update_sql = "UPDATE {$wpdb->prefix}booking AS bk SET bk.is_new = {$is_new}  WHERE bk.is_new != {$is_new} ";    //FixIn: 8.2.1.18
	//debuge($update_sql);                
					$update_sql = apply_bk_filter('update_sql_for_checking_new_bookings', $update_sql, 0 , $user_id );
				} else
					$update_sql = "UPDATE {$wpdb->prefix}booking AS bk SET bk.is_new = {$is_new} WHERE bk.booking_id IN  ( {$id_of_new_bookings} ) ";
	
				if ( false === $wpdb->query( $update_sql  ) ) {
					debuge_error('Error during updating status of bookings at DB',__FILE__,__LINE__);
					die();
				}
			}
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Requests: News , Version  ==  "  >

		define ('OBC_CHECK_URL', 'https://wpbookingcalendar.com/');


		function wpdev_ajax_check_bk_news( $sub_url = '' ){

			$v=array();
			if (class_exists('wpdev_bk_personal'))          $v[] = 'wpdev_bk_personal';
			if (class_exists('wpdev_bk_biz_s'))             $v[] = 'wpdev_bk_biz_s';
			if (class_exists('wpdev_bk_biz_m'))             $v[] = 'wpdev_bk_biz_m';
			if (class_exists('wpdev_bk_biz_l'))             $v[] = 'wpdev_bk_biz_l';
			if (class_exists('wpdev_bk_multiuser'))         $v[] = 'wpdev_bk_multiuser';

			$obc_settings = array();
			$ver = get_bk_option('bk_version_data');
			if ( $ver !== false ) { $obc_settings = array( 'subscription_key'=>wp_json_encode($ver) ); }

			$params = array(
						'action' => 'get_news',
						'subscription_email' => isset($obc_settings['subscription_email'])?$obc_settings['subscription_email']:false,
						'subscription_key'   => isset($obc_settings['subscription_key'])?$obc_settings['subscription_key']:false,
						'bk' => array('bk_ver'=>WPDEV_BK_VERSION, 'bk_url'=>WPBC_PLUGIN_URL,'bk_dir'=>WPBC_PLUGIN_DIR, 'bk_clss'=>$v),
						'siteurl'            => get_option('siteurl'),
						'siteip' 			 => wpbc_get_server_ip(),													//FixIn: 9.8.14.3
						'admin_email'        => get_option('admin_email')
			);

			$request = new WP_Http();
			if (empty($sub_url)) $sub_url = 'info/';
			$result  = $request->request( OBC_CHECK_URL . $sub_url, array(
				'method' => 'POST',
				'timeout' => 15,
				'body' => $params
				));

			if (!is_wp_error($result) && ($result['response']['code']=='200') && (true) ) {

			   $string = ($result['body']);                                         //$string = str_replace( "'", '&#039;', $string );
			   echo $string;
			   echo ' <script type="text/javascript"> ';
			   echo '    jQuery("#ajax_bk_respond").after( jQuery("#ajax_bk_respond #bk_news_loaded") );';
			   echo '    jQuery("#bk_news_loaded").slideUp(1).slideDown(1500);';
			   echo ' </script> ';

			} else  /**/
				{ // Some error appear
				echo '<div id="bk_errror_loading">';
				if (is_wp_error($result))  echo $result->get_error_message();
				else                       echo $result['response']['message'];
				echo '</div>';
				echo ' <script type="text/javascript"> ';
				echo '    document.getElementById("bk_news").style.display="none";';
				echo '    jQuery("#ajax_bk_respond").after( jQuery("#ajax_bk_respond #bk_errror_loading") );';
				echo '    jQuery("#bk_errror_loading").slideUp(1).slideDown(1500);';
				echo '    jQuery("#bk_news_section").animate({opacity:1},3000).slideUp(1500);';
				echo ' </script> ';
			}

		}


		/**
		 * Check  if user defined to  not show up_news section.
		 *
		 */
		function wpbc_is_show_up_news(){                                                                                        //FixIn: 8.1.3.9

			$wpdev_copyright_adminpanel  = get_bk_option( 'booking_wpdev_copyright_adminpanel' );             // check
			if ( 	( $wpdev_copyright_adminpanel === 'Off' )
				 && ( ! wpbc_is_this_demo() )
				 && ( class_exists('wpdev_bk_personal') )
			) {
				return false;
			} else {
				return true;
			}
		}

		
		function wpdev_ajax_check_bk_version(){
			$v=array();
			if (class_exists('wpdev_bk_personal'))            $v[] = 'wpdev_bk_personal';
			if (class_exists('wpdev_bk_biz_s'))        $v[] = 'wpdev_bk_biz_s';
			if (class_exists('wpdev_bk_biz_m'))   $v[] = 'wpdev_bk_biz_m';
			if (class_exists('wpdev_bk_biz_l'))          $v[] = 'wpdev_bk_biz_l';
			if (class_exists('wpdev_bk_multiuser'))      $v[] = 'wpdev_bk_multiuser';

			$obc_settings = array();
			$params = array(
						'action' => 'set_register',
						'order_number'   => isset($_POST['order_num'])?$_POST['order_num']:false,
						'bk' => array('bk_ver'=>WPDEV_BK_VERSION, 'bk_url'=>WPBC_PLUGIN_URL,'bk_dir'=>WPBC_PLUGIN_DIR, 'bk_clss'=>$v),
						'siteurl'            => get_option('siteurl'),
						'siteip'             => wpbc_get_server_ip(), 				//FixIn: 9.8.14.3
						'admin_email'        => get_option('admin_email')
			);

			update_bk_option( 'bk_version_data' ,  serialize($params) );

			$request = new WP_Http();
			$result  = $request->request( OBC_CHECK_URL . 'register/', array(
				'method' => 'POST',
				'timeout' => 15,
				'body' => $params
				));

			if ( ! is_wp_error($result)
				&& ( $result['response']['code']=='200' )
				&& ( true ) ) {

			   $string = ($result['body']);                                         //$string = str_replace( "'", '&#039;', $string );
			   echo $string ;
			   echo ' <script type="text/javascript"> ';
			   echo '    jQuery("#ajax_message").append( jQuery("#ajax_respond #bk_registration_info") );';
			   echo '    jQuery("#ajax_message").append( "<div id=\'bk_registration_info_reload\'>If page will not reload automatically,  please refresh page after 60 seconds...</div>" );';
			   echo ' </script> ';

			} else  /**/
				{ // Some error appear
				echo '<div id="bk_errror_loading" class="warning_message" >';
				echo '<div class="info_message">'; _e('Warning! Some error occur, during sending registration request.' ,'booking'); echo '</div>';

				if (is_wp_error($result))  echo $result->get_error_message();
				else                       echo $result['response']['message'];
				echo '<br /><br />';
				_e('Please refresh this page and if the same error appear again contact support by email (with  info about order number and website) for finishing the registrations' ,'booking'); echo ' <a href="mailto:activate@wpbookingcalendar.com">activate@wpbookingcalendar.com</a>';
				echo '</strong></div>';
				echo ' <script type="text/javascript"> ';
				echo '    jQuery( "#ajax_message" ).html( "" );';

				echo '    jQuery("#ajax_message").append( jQuery("#ajax_respond #bk_errror_loading") );';
				echo '    jQuery("#bk_errror_loading").slideUp(1).slideDown(1500);';

				echo '    jQuery("#recheck_version").animate({opacity:1},3000).slideUp(1500);';
				echo ' </script> ';
			}


		}

	// </editor-fold>


    // <editor-fold     defaultstate="collapsed"                        desc="  ==  User ID | Role  - functions  ==  "  >
			
		/**
			 * Check  if Current User have specific Role
		 * 
		 * @return bool Whether the current user has the given capability. 
		 */
		function wpbc_is_current_user_have_this_role( $user_role ) {
			
		   if ( $user_role == 'administrator' )  $user_role = 'activate_plugins';
		   if ( $user_role == 'editor' )         $user_role = 'publish_pages';
		   if ( $user_role == 'author' )         $user_role = 'publish_posts';
		   if ( $user_role == 'contributor' )    $user_role = 'edit_posts';
		   if ( $user_role == 'subscriber')      $user_role = 'read';
		   
		   return current_user_can( $user_role );
		}
		

		/**
		 * Get Current ID of user or get user ID of Forced log in user in  Booking Calendar MultiUser version.
		 *
		 * @return int
		 */
		function wpbc_get_current_user_id() {																					//FixIn: 9.2.4.1

			if ( function_exists( 'wpbc_mu__wp_get_current_user' ) ) {
				$user       = wpbc_mu__wp_get_current_user();
				$user_bk_id = $user->ID;
			} else {
				$user_bk_id = get_current_user_id();
			}

			return $user_bk_id;
		}
		
		
		/**
		 * Get Current User Object or get User object of Forced log in user in  Booking Calendar MultiUser version.
		 *
		 * @return stdClass|WP_User|null
		 */
		function wpbc_get_current_user() {																						//FixIn: 9.2.4.1

			if ( function_exists( 'wpbc_mu__wp_get_current_user' ) ) {
				$user = wpbc_mu__wp_get_current_user();
			} else {
				$user = wp_get_current_user();
			}

			return $user;
		}
		
	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Messages for Admin panel  ==  "  >

	
		/**
			 * Show "Saved Changes" message at  the top  of settings page.
		 *
		 */
		function wpbc_show_changes_saved_message() {
			wpbc_show_message ( __('Changes saved.','booking'), 5 );
		}
	
	
		/**
			 * Show Message at  Top  of Admin Pages
		 *
		 * @param type $message         - mesage to  show
		 * @param type $time_to_show    - number of seconds to  show, if 0 or skiped,  then unlimited time.
		 * @param type $message_type    - Default: updated   { updated | error | notice }
		 */
		function wpbc_show_message ( $message, $time_to_show , $message_type = 'updated') {
	
			// Generate unique HTML ID  for the message
			$inner_message_id =  intval( time() * rand(10, 100) );
	
			// Get formated HTML message
			$notice = wpbc_get_formated_message( $message, $message_type, $inner_message_id );
	
			// Get the time of message showing
			$time_to_show = intval( $time_to_show ) * 1000;
	
			// Show this Message
			?> <script type="text/javascript">
				if ( jQuery('.wpbc_admin_message').length ) {
						jQuery('.wpbc_admin_message').append( '<?php echo $notice; ?>' );
					<?php if ( $time_to_show > 0 ) { ?>
						jQuery('#wpbc_inner_message_<?php echo $inner_message_id; ?>').animate({opacity: 1},<?php echo $time_to_show; ?>).fadeOut( 2000 );
					<?php } ?>
				}
			</script> <?php
		}
	
	
		/**
			 * Escape and prepare message to  show it
		 *
		 * @param type $message                 - message
		 * @param type $message_type            - Default: updated   { updated | error | notice }
		 * @param string $inner_message_id      - ID of message DIV,  can  be skipped
		 * @return string
		 */
		function wpbc_get_formated_message ( $message, $message_type = 'updated', $inner_message_id = '') {
	
	
			// Recheck  for any "lang" shortcodes for replacing to correct language
			$message =  apply_bk_filter('wpdev_check_for_active_language', $message );
	
			// Escape any JavaScript from  message
			$notice =   html_entity_decode( esc_js( $message ) ,ENT_QUOTES) ;
	
			$notice .= '<a class="close tooltip_left" rel="tooltip" title="'. esc_js(__("Hide",'booking')). '" data-dismiss="alert" href="javascript:void(0)" onclick="javascript:jQuery(this).parent().hide();">&times;</a>';
	
			if (! empty( $inner_message_id ))
				$inner_message_id = 'id="wpbc_inner_message_'. $inner_message_id .'"';
	
			$notice = '<div '.$inner_message_id.' class="wpbc_inner_message '. $message_type . '">' . $notice . '</div>';
	
			return  $notice;
		}
	
	
		/**
			 * Show system info  in settings page
		 *
		 * @param string $message                     ...
		 * @param string $message_type                'info' | 'warning' | 'error'
		 * @param string $title                       __('Important!' ,'booking')  |  __('Note' ,'booking')
		 *
		 * Exmaple:     wpbc_show_message_in_settings( __( 'Nothing Found', 'booking' ), 'warning', __('Important!' ,'booking') );
		 */
		function wpbc_show_message_in_settings( $message, $message_type = 'info', $title = '' , $is_echo = true ) {
	
			$message_content = '';
	
			$message_content .= '<div class="clear"></div>';
	
			$message_content .= '<div class="wpbc-settings-notice notice-' . $message_type . '" style="text-align:left;">';
	
			if ( ! empty( $title ) )
				$message_content .=  '<strong>' . esc_js( $title ) . '</strong> ';
	
			$message_content .= html_entity_decode( esc_js( $message ) ,ENT_QUOTES) ;
	
			$message_content .= '</div>';
	
			$message_content .= '<div class="clear"></div>';
	
			if ( $is_echo )
				echo $message_content;
			else
				return $message_content;
	
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Meta Boxes - Open/Close  ==  "  >

		/**
		 * Meta box section open tag
		 *
		 * @param string $metabox_id		HTML  ID of section
  		 * @param string $title				Title in section
		 * @param array $params		[
										'is_section_visible_after_load' => true,			// Default true		-  is this section Visible,  after  page loading or hidden - useful  for Booking > Settings General page LEFT column sections
										'is_show_minimize'   			=> true 			// Default true		-  is show minimize button at  top right section
									]
		 *
		 * @return void
		 */
		function wpbc_open_meta_box_section( $metabox_id, $title, $params = array() ) {

			$defaults = array(
								'is_section_visible_after_load' => true,
								'is_show_minimize'   => true,
								'dismiss_button'     => ''
						);
			$params   = wp_parse_args( $params, $defaults );

			$my_close_open_win_id = $metabox_id . '_metabox';

			?>
			<div class='meta-box'>
				<div	id="<?php echo $my_close_open_win_id; ?>"
						class="postbox <?php
											if ( $params['is_show_minimize'] ) {
												if ( '1' == get_user_option( 'booking_win_' . $my_close_open_win_id ) ) {
													echo 'closed';
												}
											}
						?>"
						style="<?php		if ( ! $params['is_section_visible_after_load'] ) {
												echo 'display:none';
											}
							?>"
					><div class="postbox-header" style="display: flex;flex-flow: row nowrap;border-bottom: 1px solid #ccd0d4;">
						<h3 class='hndle' style="flex: 1 1 auto;border: none;">
						  <span><?php  echo wp_kses_post( $title ); ?></span>
						  <?php echo $params['dismiss_button']; ?>
						</h3>
						<?php if ( $params['is_show_minimize'] ) { ?>
						<div  	title="<?php _e('Click to toggle','booking'); ?>" class="handlediv"
								onclick="javascript:wpbc_verify_window_opening(<?php echo wpbc_get_current_user_id(); ?>, '<?php echo $my_close_open_win_id; ?>');"
						><br/></div>
						<?php } ?>
					</div>
					<div class="inside">
			<?php
		}


		function wpbc_close_meta_box_section() {
			?>
					  </div>
				</div>
			</div>
			<?php
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Is Dismissed  ==  "  >

		/**
		 * Only  check  if Dismised or visible this section
		 *
		 * @param $element_html_id
		 *
		 * @return bool
		 */
		function wpbc_is_dismissed_panel_visible( $element_html_id ) {
			return ( '1' != get_user_option( 'booking_win_' . $element_html_id ) );

		}

		/**
		 * Show dismiss close button  for specific HTML section
		 *
		 * @param  string $element_html_id   - ID of HTML selection  to  dismiss
		 * @param  array  $params			 - array( 'title' => 'Dismiss', 'is_apply_in_demo' => false ) )
		 *
		 * @return bool
		 *
		 *    Examples:
		 *				$is_dismissed = wpbc_is_dismissed( 'wpbc_dashboard_section_video_f' );
		 *
		 *              $is_dismissed = wpbc_is_dismissed( 'html_id', array( 'is_apply_in_demo' => $is_apply_in_demo ) );
		 *
		 *          $is_panel_visible = wpbc_is_dismissed( 'wpbc-panel-get-started', array(
		 *             									   'title' => '<i class="menu_icon icon-1x wpbc_icn_close"></i> ',
		 *             									   'hint'  => __( 'Dismiss', 'booking' ),
		 *             									   'class' => 'wpbc_panel_get_started_dismiss',
		 *             									   'css'   => ''
		 * 											));
 		 */
		function wpbc_is_dismissed( $element_html_id, $params = array() ){														//FixIn: 8.1.3.10

			$defaults = array(
								  'title' => '&times;'
								, 'hint' => __( 'Dismiss' ,'booking')
								, 'is_apply_in_demo' => ! wpbc_is_this_demo()
								, 'class' => ''									// CSS class of  close X element
								, 'css' => ''									// Style class of  close X element
								, 'dismiss_css_class'  => '' 					//'.'.$id . '_' . 'weekdays_conditions'
						);
			$params = wp_parse_args( $params, $defaults );


			$params['css'] = 'text-decoration: none;font-weight: 600;float:right;' . $params['css'];							// Append CSS instead of replace it

			if ( ( class_exists( 'WPBC_Dismiss' )) && ( $params[ 'is_apply_in_demo' ] ) ) {

				global $wpbc_Dismiss;

				$is_panel_visible = $wpbc_Dismiss->render( 	array(
																	'id'                => $element_html_id,
																	'title'             => $params['title'],
																	'hint'              => $params['hint'],
																	'class'             => $params['class'],
																	'css'               => $params['css'],
																	'dismiss_css_class' => $params['dismiss_css_class']
														) );
			} else {
				$is_panel_visible = false;
			}

			return $is_panel_visible;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Inline JavaScript to Footer page  ==  "  >

		/**
		 * Queue  JavaScript for later output at  footer
		 *
		 * @param string $code
		 */
		function wpbc_enqueue_js( $code ) {
			global $wpbc_queued_js;

			if ( empty( $wpbc_queued_js ) ) {
				$wpbc_queued_js = '';
			}

			$wpbc_queued_js .= "\n" . $code . "\n";
		}


		/**
		 * Output any queued javascript code in the footer.
		 */
		function wpbc_print_js() {

			global $wpbc_queued_js;

			if ( ! empty( $wpbc_queued_js ) ) {

				$wpbc_queued_js = wp_check_invalid_utf8( $wpbc_queued_js );

				$wpbc_queued_js = wp_specialchars_decode( $wpbc_queued_js , ENT_COMPAT);            // Converts double quotes  '&quot;' => '"'

				$wpbc_queued_js = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "'", $wpbc_queued_js );
				$wpbc_queued_js = str_replace( "\r", '', $wpbc_queued_js );

				echo "<!-- WPBC JavaScript -->\n<script type=\"text/javascript\">\njQuery(function($) {" . $wpbc_queued_js . "});\n</script>\n<!-- End WPBC JavaScript -->\n";

				$wpbc_queued_js = '';
				unset( $wpbc_queued_js );
			}
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Support functions for MU version  ==  "  >

		/**
			 * Set  active User Environment in MultiUser  version, depend from owner of booking resource
		 *
		 * @param int $previous_active_user (default=-1) - blank parameter
		 * @param int $bktype - booking resource ID for checking
		 * @return int - ID of Previous Active User
		 *
		 * Usage:
		   $previous_active_user = apply_bk_filter('wpbc_mu_set_environment_for_owner_of_resource', -1, $bktype );

		 */
		function wpbc_mu_set_environment_for_owner_of_resource( $previous_active_user = -1, $bktype = 1 ) {

			if ( class_exists( 'wpdev_bk_multiuser' ) ) {
				// Get  the owner of this booking resource
				$user_bk_id = apply_bk_filter( 'get_user_of_this_bk_resource', false, $bktype );

				$user = wpbc_get_current_user();

				// Get possible other active user settings
				$previous_active_user = apply_bk_filter( 'get_client_side_active_params_of_user' );

				// Set active user of that specific booking resource
				make_bk_action( 'check_multiuser_params_for_client_side_by_user_id', $user_bk_id );
			}

			return $previous_active_user;
		}
		add_bk_filter('wpbc_mu_set_environment_for_owner_of_resource', 'wpbc_mu_set_environment_for_owner_of_resource');


		/**
			 * Set environment for this user in MU version
		 *
		 * @param int $previous_active_user - ID of user
		 * Usage:
		   make_bk_action('wpbc_mu_set_environment_for_user', $previous_active_user );

		 */
		function wpbc_mu_set_environment_for_user( $previous_active_user ) {

			if ( $previous_active_user !== -1 ) {

				// Reactivate the previous active user
				make_bk_action('check_multiuser_params_for_client_side_by_user_id', $previous_active_user );
			}
		}
		add_bk_action('wpbc_mu_set_environment_for_user', 'wpbc_mu_set_environment_for_user');

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Currency  functions (>=BS)  ==  "  >

		/**
			 * Format booking cost with a currency symbol.
		 *  In MultiUser  version also checking about specific currency  that  belong to  specific WordPress user.
		 *  This checking based on belonging specific booking resource to  specific user.
		 *
		 * @param float $cost
		 * @param int $booking_resource_id
		 * @return string                       - $cost_to_show_with_currency
		 */
		function wpbc_get_cost_with_currency_for_user( $cost, $booking_resource_id  = 0 ){

			if ( ( $cost === '' ) || ( ! class_exists( 'wpdev_bk_biz_s' ) ) ) {
				return '';
			}

			if ( ! empty( $booking_resource_id ) ) {
				$previous_active_user = apply_bk_filter( 'wpbc_mu_set_environment_for_owner_of_resource', - 1, $booking_resource_id );
			}       // MU

			$cost_to_show_with_currency = wpbc_cost_show( $cost, array( 'currency' => wpbc_get_currency() ) );

			if ( ! empty( $booking_resource_id ) ) {
				make_bk_action( 'wpbc_mu_set_environment_for_user', $previous_active_user );
			}                                                // MU

			return $cost_to_show_with_currency;
		}


		/**
			 * Get currency Symbol.
		 *  In MultiUser  version also checking about specific currency  that  belong to  specific WordPress user.
		 *  This checking based on belonging specific booking resource to  specific user.
		 *
		 * @param int $booking_resource_id  - ID of specific booking resource
		 * @return string                   - currency  symbol
		 */
		function wpbc_get_currency_symbol_for_user( $booking_resource_id  = 0 ){

			if ( ! class_exists( 'wpdev_bk_biz_s' ) ) {
				return '';
			}

			if ( ! empty( $booking_resource_id ) ) {
				$previous_active_user = apply_bk_filter( 'wpbc_mu_set_environment_for_owner_of_resource', - 1, $booking_resource_id );
			}       // MU

			$currency_symbol = wpbc_get_currency_symbol();

			if ( ! empty( $booking_resource_id ) ) {
				make_bk_action( 'wpbc_mu_set_environment_for_user', $previous_active_user );
			}                                                // MU

			return $currency_symbol;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Approve |  Pending  functions  ==  "  >
		
		/**
		 * Check  is this booking approved -- get booking dates in DB and check  status
		 *
		 * @param $booking_id
		 *
		 * @return bool
		 */
		function wpbc_is_booking_approved( $booking_id ){                                                                       //FixIn: 8.1.2.8
		
			$is_booking_approved = false;
		
			global $wpdb;
		
			$sql = $wpdb->prepare( "SELECT DISTINCT approved FROM {$wpdb->prefix}bookingdates WHERE booking_id = %d ORDER BY booking_date", $booking_id );
		
			$dates_result = $wpdb->get_results( $sql );
		
			foreach ( $dates_result as $my_date ) {
		
				if ( '1' == $my_date->approved ) {
					$is_booking_approved = true;        //FixIn: 8.3.1.2
				}
			}
		
			return $is_booking_approved;
		}
		
		
		/**
		 * Approve booking in DB (update booking dates in DB like approved = '1')
		 *
		 * @param $booking_id  int | CSD e.g. 			100  |	'1,5,10'
		 *
		 * @return bool
		 */
		function wpbc_db__booking_approve( $booking_id ) {
		
			$booking_id = wpbc_clean_digit_or_csd( $booking_id );                   // Check  paramter  if it number or comma separated list  of numbers
		
			global $wpdb;
		
			$update_sql = "UPDATE {$wpdb->prefix}bookingdates SET approved = '1' WHERE booking_id IN ({$booking_id});";
		
			if ( false === $wpdb->query( $update_sql ) ) {
				return false;
			}
		
			return true;
		}
		
		
		/**
		 * Approve specific booking and send email about this.
		 *
		 * @param int $booking_id - ID of booking
		 * @param string $email_reason
		 */
		function wpbc_auto_approve_booking( $booking_id , $email_reason = '' ) {
		
			$booking_id = wpbc_clean_digit_or_csd( $booking_id );                   // Check  paramter  if it number or comma separated list  of numbers
		
			if ( is_numeric( $booking_id ) ) {                                                                                  //FixIn: 8.1.2.8
				if ( ! wpbc_is_booking_approved( $booking_id ) ) {
					do_action( 'wpbc_booking_approved', $booking_id, 1 );                                						//FixIn: 8.7.6.1

					wpbc_send_email_approved( $booking_id, 1, $email_reason );
				}
			} else {
				$booking_id_arr = explode( ',',$booking_id );
				foreach ( $booking_id_arr as $bk_id ) {
					if ( ! wpbc_is_booking_approved( $bk_id ) ) {
						do_action( 'wpbc_booking_approved', $bk_id, 1 );                                						//FixIn: 8.7.6.1
						wpbc_send_email_approved( $bk_id, 1, $email_reason );
					}
				}
			}
		
			$db_result = wpbc_db__booking_approve( $booking_id );
		
			if ( false === $db_result ){
		
				wpbc_redirect( site_url()  );
			}
		}
		
		
		/**
		 * Set as Pending specific booking and send email about this.
		 *
		 * @param int $booking_id - ID of booking
		 * @param string $denyreason
		 */
		function wpbc_auto_pending_booking( $booking_id, $denyreason = '' ) {			 										//FixIn: 8.4.7.25
		
			global $wpdb;
		
			$booking_id = wpbc_clean_digit_or_csd( $booking_id );                   // Check  paramter  if it number or comma separated list  of numbers
		
			if ( is_numeric( $booking_id ) ) {                                                                                  //FixIn: 8.1.2.8
				if ( wpbc_is_booking_approved( $booking_id ) ) {
					wpbc_send_email_deny( $booking_id, 1, $denyreason );
				}
			} else {
				$booking_id_arr = explode( ',',$booking_id );
				foreach ( $booking_id_arr as $bk_id ) {
					if ( wpbc_is_booking_approved( $bk_id ) ) {
						wpbc_send_email_deny( $bk_id, 1, $denyreason );
					}
		
				}
			}
		
			$update_sql = "UPDATE {$wpdb->prefix}bookingdates SET approved = '0' WHERE booking_id IN ({$booking_id});";
		
			if ( false === $wpdb->query( $update_sql  ) ){
		
				wpbc_redirect( site_url()  );
			}
		}
		
		
		/**
		 * Cancel (move to  Trash) specific booking.
		 *
		 * @param int $booking_id - ID of booking
		 * @param string $email_reason	- 	reason  of cancellation
		 */
		function wpbc_auto_cancel_booking( $booking_id , $email_reason = '' ) {					//FixIn: 8.4.7.25
		
			global $wpdb;
		
			$booking_id = wpbc_clean_digit_or_csd( $booking_id );                   // Check  paramter  if it number or comma separated list  of numbers
		
			if ( empty( $email_reason ) ) {    //FixIn: 8.4.7.25
				// Get the reason of cancellation.
				$email_reason  = __( 'Payment rejected', 'booking' );
				$auto_cancel_pending_unpaid_bk_is_send_email = get_bk_option( 'booking_auto_cancel_pending_unpaid_bk_is_send_email' );
				if ( $auto_cancel_pending_unpaid_bk_is_send_email == 'On' ) {
					$email_reason = get_bk_option( 'booking_auto_cancel_pending_unpaid_bk_email_reason' );
				}
			}
			// Send decline emails
			wpbc_send_email_trash( $booking_id, 1, $email_reason );
		
			if ( false === $wpdb->query( "UPDATE {$wpdb->prefix}booking AS bk SET bk.trash = 1 WHERE booking_id IN ({$booking_id})" ) ){
		
				wpbc_redirect( site_url()  );
			}
		}


			//FixIn: 9.9.0.43

		/**
		 * Auto  approve booking and send email, after successful  payment process
		 *
		 * It resolves issue in  Booking Calendar MultiUser version  for sending "regular email" to  visitors,  if was made booking for booking resource,  that  belong to  regular  user,
		 * and was activated this option "Receive all payments only to Super Booking Admin account" at  the WP Booking Calendar > Settings General page in "Multiuser Options" section
		 *
		 * @param $booking_id
		 *
		 * @return void
		 */
		function wpbc_auto_approve_booking__after_payment( $booking_id ) {
			//--------------------------------------------------------------------------------------------------		//FixIn: 9.9.0.43
			$is_force_again = false;
			if ( class_exists( 'wpdev_bk_multiuser' ) ) {

				list( $booking_hash, $booking_resource_id ) = wpbc_hash__get_booking_hash__resource_id( $booking_id );

				$user_id = apply_bk_filter( 'get_user_of_this_bk_resource', false, $booking_resource_id );

				$is_booking_resource_user_super_admin = apply_bk_filter( 'is_user_super_admin', $user_id );

				if (
					( 'On' == get_bk_option( 'booking_super_admin_receive_regular_user_payments' ) )
					&& ( ! $is_booking_resource_user_super_admin )
				) {
					// Finish "Super-User" forcing
					make_bk_action( 'finish_force_using_this_user' );
					//Reactivate data for "regular  user
					make_bk_action( 'check_multiuser_params_for_client_side_by_user_id', $user_id );

					$is_force_again = true;
				}
			}
			// -------------------------------------------------------------------------------------------------

			wpbc_auto_approve_booking( $booking_id );

			if ( $is_force_again ) {                                                                                    //FixIn: 9.9.0.43
				make_bk_action( 'make_force_using_this_user', - 999 );                                                  // '-999' - This ID "by default" is the ID of super booking admin user
			}
		}


		/**
		 * Auto cancel booking and send email, after successful  payment process
		 *
		 * It resolves issue in  Booking Calendar MultiUser version  for sending "regular email" to  visitors,  if was made booking for booking resource,  that  belong to  regular  user,
		 * and was activated this option "Receive all payments only to Super Booking Admin account" at  the WP Booking Calendar > Settings General page in "Multiuser Options" section
		 *
		 * @param $booking_id
		 *
		 * @return void
		 */
		function wpbc_auto_cancel_booking__after_payment( $booking_id ) {
			//--------------------------------------------------------------------------------------------------		//FixIn: 9.9.0.43
			$is_force_again = false;
			if ( class_exists( 'wpdev_bk_multiuser' ) ) {

				list( $booking_hash, $booking_resource_id ) = wpbc_hash__get_booking_hash__resource_id( $booking_id );

				$user_id = apply_bk_filter( 'get_user_of_this_bk_resource', false, $booking_resource_id );

				$is_booking_resource_user_super_admin = apply_bk_filter( 'is_user_super_admin', $user_id );

				if (
					( 'On' == get_bk_option( 'booking_super_admin_receive_regular_user_payments' ) )
					&& ( ! $is_booking_resource_user_super_admin )
				) {
					// Finish "Super-User" forcing
					make_bk_action( 'finish_force_using_this_user' );
					//Reactivate data for "regular  user
					make_bk_action( 'check_multiuser_params_for_client_side_by_user_id', $user_id );

					$is_force_again = true;
				}
			}
			// -------------------------------------------------------------------------------------------------

			wpbc_auto_cancel_booking( $booking_id );

			if ( $is_force_again ) {                                                                                    //FixIn: 9.9.0.43
				make_bk_action( 'make_force_using_this_user', - 999 );                                                  // '-999' - This ID "by default" is the ID of super booking admin user
			}
		}
	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  REGION CITIES LIST  -  Support functions Timezone  in   .ICS & Google Calendar  ==  "  >

		/**
		 * Get list of cities for timezone usage in format like:		$city["Europe"]["Kiev"] = "Kiev";
		 *
		 * @return array|array[]
		 */
		function wpbc_get_booking_region_cities_list(){                                                                         //FixIn: 8.9.4.9

			$city = array(
							  'Africa' => array()
							, 'America' => array()
							, 'Antarctica' => array()
							, 'Arctic' => array()
							, 'Asia' => array()
							, 'Atlantic' => array()
							, 'Australia' => array()
							, 'Europe' => array()
							, 'Indian' => array()
							, 'Pacific' => array()
						);
			$city["Africa"]["Abidjan"] = "Abidjan";
			$city["Africa"]["Accra"] = "Accra";
			$city["Africa"]["Addis_Ababa"] = "Addis Ababa";
			$city["Africa"]["Algiers"] = "Algiers";
			$city["Africa"]["Asmara"] = "Asmara";
			$city["Africa"]["Bamako"] = "Bamako";
			$city["Africa"]["Bangui"] = "Bangui";
			$city["Africa"]["Banjul"] = "Banjul";
			$city["Africa"]["Bissau"] = "Bissau";
			$city["Africa"]["Blantyre"] = "Blantyre";
			$city["Africa"]["Brazzaville"] = "Brazzaville";
			$city["Africa"]["Bujumbura"] = "Bujumbura";
			$city["Africa"]["Cairo"] = "Cairo";
			$city["Africa"]["Casablanca"] = "Casablanca";
			$city["Africa"]["Ceuta"] = "Ceuta";
			$city["Africa"]["Conakry"] = "Conakry";
			$city["Africa"]["Dakar"] = "Dakar";
			$city["Africa"]["Dar_es_Salaam"] = "Dar es Salaam";
			$city["Africa"]["Djibouti"] = "Djibouti";
			$city["Africa"]["Douala"] = "Douala";
			$city["Africa"]["El_Aaiun"] = "El Aaiun";
			$city["Africa"]["Freetown"] = "Freetown";
			$city["Africa"]["Gaborone"] = "Gaborone";
			$city["Africa"]["Harare"] = "Harare";
			$city["Africa"]["Johannesburg"] = "Johannesburg";
			$city["Africa"]["Kampala"] = "Kampala";
			$city["Africa"]["Khartoum"] = "Khartoum";
			$city["Africa"]["Kigali"] = "Kigali";
			$city["Africa"]["Kinshasa"] = "Kinshasa";
			$city["Africa"]["Lagos"] = "Lagos";
			$city["Africa"]["Libreville"] = "Libreville";
			$city["Africa"]["Lome"] = "Lome";
			$city["Africa"]["Luanda"] = "Luanda";
			$city["Africa"]["Lubumbashi"] = "Lubumbashi";
			$city["Africa"]["Lusaka"] = "Lusaka";
			$city["Africa"]["Malabo"] = "Malabo";
			$city["Africa"]["Maputo"] = "Maputo";
			$city["Africa"]["Maseru"] = "Maseru";
			$city["Africa"]["Mbabane"] = "Mbabane";
			$city["Africa"]["Mogadishu"] = "Mogadishu";
			$city["Africa"]["Monrovia"] = "Monrovia";
			$city["Africa"]["Nairobi"] = "Nairobi";
			$city["Africa"]["Ndjamena"] = "Ndjamena";
			$city["Africa"]["Niamey"] = "Niamey";
			$city["Africa"]["Nouakchott"] = "Nouakchott";
			$city["Africa"]["Ouagadougou"] = "Ouagadougou";
			$city["Africa"]["Porto-Novo"] = "Porto-Novo";
			$city["Africa"]["Sao_Tome"] = "Sao Tome";
			$city["Africa"]["Tripoli"] = "Tripoli";
			$city["Africa"]["Tunis"] = "Tunis";
			$city["Africa"]["Windhoek"] = "Windhoek";

			$city["America"]["Adak"] = "Adak";
			$city["America"]["Anchorage"] = "Anchorage";
			$city["America"]["Anguilla"] = "Anguilla";
			$city["America"]["Antigua"] = "Antigua";
			$city["America"]["Araguaina"] = "Araguaina";
			$city["America"]["Argentina/Buenos_Aires"] = "Argentina - Buenos Aires";
			$city["America"]["Argentina/Catamarca"] = "Argentina - Catamarca";
			$city["America"]["Argentina/Cordoba"] = "Argentina - Cordoba";
			$city["America"]["Argentina/Jujuy"] = "Argentina - Jujuy";
			$city["America"]["Argentina/La_Rioja"] = "Argentina - La Rioja";
			$city["America"]["Argentina/Mendoza"] = "Argentina - Mendoza";
			$city["America"]["Argentina/Rio_Gallegos"] = "Argentina - Rio Gallegos";
			$city["America"]["Argentina/Salta"] = "Argentina - Salta";
			$city["America"]["Argentina/San_Juan"] = "Argentina - San Juan";
			$city["America"]["Argentina/San_Luis"] = "Argentina - San Luis";
			$city["America"]["Argentina/Tucuman"] = "Argentina - Tucuman";
			$city["America"]["Argentina/Ushuaia"] = "Argentina - Ushuaia";
			$city["America"]["Aruba"] = "Aruba";
			$city["America"]["Asuncion"] = "Asuncion";
			$city["America"]["Atikokan"] = "Atikokan";
			$city["America"]["Bahia"] = "Bahia";
			$city["America"]["Barbados"] = "Barbados";
			$city["America"]["Belem"] = "Belem";
			$city["America"]["Belize"] = "Belize";
			$city["America"]["Blanc-Sablon"] = "Blanc-Sablon";
			$city["America"]["Boa_Vista"] = "Boa Vista";
			$city["America"]["Bogota"] = "Bogota";
			$city["America"]["Boise"] = "Boise";
			$city["America"]["Cambridge_Bay"] = "Cambridge Bay";
			$city["America"]["Campo_Grande"] = "Campo Grande";
			$city["America"]["Cancun"] = "Cancun";
			$city["America"]["Caracas"] = "Caracas";
			$city["America"]["Cayenne"] = "Cayenne";
			$city["America"]["Cayman"] = "Cayman";
			$city["America"]["Chicago"] = "Chicago";
			$city["America"]["Chihuahua"] = "Chihuahua";
			$city["America"]["Costa_Rica"] = "Costa Rica";
			$city["America"]["Cuiaba"] = "Cuiaba";
			$city["America"]["Curacao"] = "Curacao";
			$city["America"]["Danmarkshavn"] = "Danmarkshavn";
			$city["America"]["Dawson"] = "Dawson";
			$city["America"]["Dawson_Creek"] = "Dawson Creek";
			$city["America"]["Denver"] = "Denver";
			$city["America"]["Detroit"] = "Detroit";
			$city["America"]["Dominica"] = "Dominica";
			$city["America"]["Edmonton"] = "Edmonton";
			$city["America"]["Eirunepe"] = "Eirunepe";
			$city["America"]["El_Salvador"] = "El Salvador";
			$city["America"]["Fortaleza"] = "Fortaleza";
			$city["America"]["Glace_Bay"] = "Glace Bay";
			$city["America"]["Godthab"] = "Godthab";
			$city["America"]["Goose_Bay"] = "Goose Bay";
			$city["America"]["Grand_Turk"] = "Grand Turk";
			$city["America"]["Grenada"] = "Grenada";
			$city["America"]["Guadeloupe"] = "Guadeloupe";
			$city["America"]["Guatemala"] = "Guatemala";
			$city["America"]["Guayaquil"] = "Guayaquil";
			$city["America"]["Guyana"] = "Guyana";
			$city["America"]["Halifax"] = "Halifax";
			$city["America"]["Havana"] = "Havana";
			$city["America"]["Hermosillo"] = "Hermosillo";
			$city["America"]["Indiana/Indianapolis"] = "Indiana - Indianapolis";
			$city["America"]["Indiana/Knox"] = "Indiana - Knox";
			$city["America"]["Indiana/Marengo"] = "Indiana - Marengo";
			$city["America"]["Indiana/Petersburg"] = "Indiana - Petersburg";
			$city["America"]["Indiana/Tell_City"] = "Indiana - Tell City";
			$city["America"]["Indiana/Vevay"] = "Indiana - Vevay";
			$city["America"]["Indiana/Vincennes"] = "Indiana - Vincennes";
			$city["America"]["Indiana/Winamac"] = "Indiana - Winamac";
			$city["America"]["Inuvik"] = "Inuvik";
			$city["America"]["Iqaluit"] = "Iqaluit";
			$city["America"]["Jamaica"] = "Jamaica";
			$city["America"]["Juneau"] = "Juneau";
			$city["America"]["Kentucky/Louisville"] = "Kentucky - Louisville";
			$city["America"]["Kentucky/Monticello"] = "Kentucky - Monticello";
			$city["America"]["La_Paz"] = "La Paz";
			$city["America"]["Lima"] = "Lima";
			$city["America"]["Los_Angeles"] = "Los Angeles";
			$city["America"]["Maceio"] = "Maceio";
			$city["America"]["Managua"] = "Managua";
			$city["America"]["Manaus"] = "Manaus";
			$city["America"]["Marigot"] = "Marigot";
			$city["America"]["Martinique"] = "Martinique";
			$city["America"]["Mazatlan"] = "Mazatlan";
			$city["America"]["Menominee"] = "Menominee";
			$city["America"]["Merida"] = "Merida";
			$city["America"]["Mexico_City"] = "Mexico City";
			$city["America"]["Miquelon"] = "Miquelon";
			$city["America"]["Moncton"] = "Moncton";
			$city["America"]["Monterrey"] = "Monterrey";
			$city["America"]["Montevideo"] = "Montevideo";
			$city["America"]["Montreal"] = "Montreal";
			$city["America"]["Montserrat"] = "Montserrat";
			$city["America"]["Nassau"] = "Nassau";
			$city["America"]["New_York"] = "New York";
			$city["America"]["Nipigon"] = "Nipigon";
			$city["America"]["Nome"] = "Nome";
			$city["America"]["Noronha"] = "Noronha";
			$city["America"]["North_Dakota/Center"] = "North Dakota - Center";
			$city["America"]["North_Dakota/New_Salem"] = "North Dakota - New Salem";
			$city["America"]["Panama"] = "Panama";
			$city["America"]["Pangnirtung"] = "Pangnirtung";
			$city["America"]["Paramaribo"] = "Paramaribo";
			$city["America"]["Phoenix"] = "Phoenix";
			$city["America"]["Port-au-Prince"] = "Port-au-Prince";
			$city["America"]["Port_of_Spain"] = "Port of Spain";
			$city["America"]["Porto_Velho"] = "Porto Velho";
			$city["America"]["Puerto_Rico"] = "Puerto Rico";
			$city["America"]["Rainy_River"] = "Rainy River";
			$city["America"]["Rankin_Inlet"] = "Rankin Inlet";
			$city["America"]["Recife"] = "Recife";
			$city["America"]["Regina"] = "Regina";
			$city["America"]["Resolute"] = "Resolute";
			$city["America"]["Rio_Branco"] = "Rio Branco";
			$city["America"]["Santarem"] = "Santarem";
			$city["America"]["Santiago"] = "Santiago";
			$city["America"]["Santo_Domingo"] = "Santo Domingo";
			$city["America"]["Sao_Paulo"] = "Sao Paulo";
			$city["America"]["Scoresbysund"] = "Scoresbysund";
			$city["America"]["Shiprock"] = "Shiprock";
			$city["America"]["St_Barthelemy"] = "St Barthelemy";
			$city["America"]["St_Johns"] = "St Johns";
			$city["America"]["St_Kitts"] = "St Kitts";
			$city["America"]["St_Lucia"] = "St Lucia";
			$city["America"]["St_Thomas"] = "St Thomas";
			$city["America"]["St_Vincent"] = "St Vincent";
			$city["America"]["Swift_Current"] = "Swift Current";
			$city["America"]["Tegucigalpa"] = "Tegucigalpa";
			$city["America"]["Thule"] = "Thule";
			$city["America"]["Thunder_Bay"] = "Thunder Bay";
			$city["America"]["Tijuana"] = "Tijuana";
			$city["America"]["Toronto"] = "Toronto";
			$city["America"]["Tortola"] = "Tortola";
			$city["America"]["Vancouver"] = "Vancouver";
			$city["America"]["Whitehorse"] = "Whitehorse";
			$city["America"]["Winnipeg"] = "Winnipeg";
			$city["America"]["Yakutat"] = "Yakutat";
			$city["America"]["Yellowknife"] = "Yellowknife";

			$city["Antarctica"]["Casey"] = "Casey";
			$city["Antarctica"]["Davis"] = "Davis";
			$city["Antarctica"]["DumontDUrville"] = "DumontDUrville";
			$city["Antarctica"]["Mawson"] = "Mawson";
			$city["Antarctica"]["McMurdo"] = "McMurdo";
			$city["Antarctica"]["Palmer"] = "Palmer";
			$city["Antarctica"]["Rothera"] = "Rothera";
			$city["Antarctica"]["South_Pole"] = "South Pole";
			$city["Antarctica"]["Syowa"] = "Syowa";
			$city["Antarctica"]["Vostok"] = "Vostok";

			$city["Arctic"]["Longyearbyen"] = "Longyearbyen";

			$city["Asia"]["Aden"] = "Aden";
			$city["Asia"]["Almaty"] = "Almaty";
			$city["Asia"]["Amman"] = "Amman";
			$city["Asia"]["Anadyr"] = "Anadyr";
			$city["Asia"]["Aqtau"] = "Aqtau";
			$city["Asia"]["Aqtobe"] = "Aqtobe";
			$city["Asia"]["Ashgabat"] = "Ashgabat";
			$city["Asia"]["Baghdad"] = "Baghdad";
			$city["Asia"]["Bahrain"] = "Bahrain";
			$city["Asia"]["Baku"] = "Baku";
			$city["Asia"]["Bangkok"] = "Bangkok";
			$city["Asia"]["Beirut"] = "Beirut";
			$city["Asia"]["Bishkek"] = "Bishkek";
			$city["Asia"]["Brunei"] = "Brunei";
			$city["Asia"]["Choibalsan"] = "Choibalsan";
			$city["Asia"]["Chongqing"] = "Chongqing";
			$city["Asia"]["Colombo"] = "Colombo";
			$city["Asia"]["Damascus"] = "Damascus";
			$city["Asia"]["Dhaka"] = "Dhaka";
			$city["Asia"]["Dili"] = "Dili";
			$city["Asia"]["Dubai"] = "Dubai";
			$city["Asia"]["Dushanbe"] = "Dushanbe";
			$city["Asia"]["Gaza"] = "Gaza";
			$city["Asia"]["Harbin"] = "Harbin";
			$city["Asia"]["Ho_Chi_Minh"] = "Ho Chi Minh";
			$city["Asia"]["Hong_Kong"] = "Hong Kong";
			$city["Asia"]["Hovd"] = "Hovd";
			$city["Asia"]["Irkutsk"] = "Irkutsk";
			$city["Asia"]["Jakarta"] = "Jakarta";
			$city["Asia"]["Jayapura"] = "Jayapura";
			$city["Asia"]["Jerusalem"] = "Jerusalem";
			$city["Asia"]["Kabul"] = "Kabul";
			$city["Asia"]["Kamchatka"] = "Kamchatka";
			$city["Asia"]["Karachi"] = "Karachi";
			$city["Asia"]["Kashgar"] = "Kashgar";
			$city["Asia"]["Kathmandu"] = "Kathmandu";
			$city["Asia"]["Kolkata"] = "Kolkata";
			$city["Asia"]["Krasnoyarsk"] = "Krasnoyarsk";
			$city["Asia"]["Kuala_Lumpur"] = "Kuala Lumpur";
			$city["Asia"]["Kuching"] = "Kuching";
			$city["Asia"]["Kuwait"] = "Kuwait";
			$city["Asia"]["Macau"] = "Macau";
			$city["Asia"]["Magadan"] = "Magadan";
			$city["Asia"]["Makassar"] = "Makassar";
			$city["Asia"]["Manila"] = "Manila";
			$city["Asia"]["Muscat"] = "Muscat";
			$city["Asia"]["Nicosia"] = "Nicosia";
			$city["Asia"]["Novosibirsk"] = "Novosibirsk";
			$city["Asia"]["Omsk"] = "Omsk";
			$city["Asia"]["Oral"] = "Oral";
			$city["Asia"]["Phnom_Penh"] = "Phnom Penh";
			$city["Asia"]["Pontianak"] = "Pontianak";
			$city["Asia"]["Pyongyang"] = "Pyongyang";
			$city["Asia"]["Qatar"] = "Qatar";
			$city["Asia"]["Qyzylorda"] = "Qyzylorda";
			$city["Asia"]["Rangoon"] = "Rangoon";
			$city["Asia"]["Riyadh"] = "Riyadh";
			$city["Asia"]["Sakhalin"] = "Sakhalin";
			$city["Asia"]["Samarkand"] = "Samarkand";
			$city["Asia"]["Seoul"] = "Seoul";
			$city["Asia"]["Shanghai"] = "Shanghai";
			$city["Asia"]["Singapore"] = "Singapore";
			$city["Asia"]["Taipei"] = "Taipei";
			$city["Asia"]["Tashkent"] = "Tashkent";
			$city["Asia"]["Tbilisi"] = "Tbilisi";
			$city["Asia"]["Tehran"] = "Tehran";
			$city["Asia"]["Thimphu"] = "Thimphu";
			$city["Asia"]["Tokyo"] = "Tokyo";
			$city["Asia"]["Ulaanbaatar"] = "Ulaanbaatar";
			$city["Asia"]["Urumqi"] = "Urumqi";
			$city["Asia"]["Vientiane"] = "Vientiane";
			$city["Asia"]["Vladivostok"] = "Vladivostok";
			$city["Asia"]["Yakutsk"] = "Yakutsk";
			$city["Asia"]["Yekaterinburg"] = "Yekaterinburg";
			$city["Asia"]["Yerevan"] = "Yerevan";

			$city["Atlantic"]["Azores"] = "Azores";
			$city["Atlantic"]["Bermuda"] = "Bermuda";
			$city["Atlantic"]["Canary"] = "Canary";
			$city["Atlantic"]["Cape_Verde"] = "Cape Verde";
			$city["Atlantic"]["Faroe"] = "Faroe";
			$city["Atlantic"]["Madeira"] = "Madeira";
			$city["Atlantic"]["Reykjavik"] = "Reykjavik";
			$city["Atlantic"]["South_Georgia"] = "South Georgia";
			$city["Atlantic"]["Stanley"] = "Stanley";
			$city["Atlantic"]["St_Helena"] = "St Helena";

			$city["Australia"]["Adelaide"] = "Adelaide";
			$city["Australia"]["Brisbane"] = "Brisbane";
			$city["Australia"]["Broken_Hill"] = "Broken Hill";
			$city["Australia"]["Currie"] = "Currie";
			$city["Australia"]["Darwin"] = "Darwin";
			$city["Australia"]["Eucla"] = "Eucla";
			$city["Australia"]["Hobart"] = "Hobart";
			$city["Australia"]["Lindeman"] = "Lindeman";
			$city["Australia"]["Lord_Howe"] = "Lord Howe";
			$city["Australia"]["Melbourne"] = "Melbourne";
			$city["Australia"]["Perth"] = "Perth";
			$city["Australia"]["Sydney"] = "Sydney";

			$city["Europe"]["Amsterdam"] = "Amsterdam";
			$city["Europe"]["Andorra"] = "Andorra";
			$city["Europe"]["Athens"] = "Athens";
			$city["Europe"]["Belgrade"] = "Belgrade";
			$city["Europe"]["Berlin"] = "Berlin";
			$city["Europe"]["Bratislava"] = "Bratislava";
			$city["Europe"]["Brussels"] = "Brussels";
			$city["Europe"]["Bucharest"] = "Bucharest";
			$city["Europe"]["Budapest"] = "Budapest";
			$city["Europe"]["Chisinau"] = "Chisinau";
			$city["Europe"]["Copenhagen"] = "Copenhagen";
			$city["Europe"]["Dublin"] = "Dublin";
			$city["Europe"]["Gibraltar"] = "Gibraltar";
			$city["Europe"]["Guernsey"] = "Guernsey";
			$city["Europe"]["Helsinki"] = "Helsinki";
			$city["Europe"]["Isle_of_Man"] = "Isle of Man";
			$city["Europe"]["Istanbul"] = "Istanbul";
			$city["Europe"]["Jersey"] = "Jersey";
			$city["Europe"]["Kaliningrad"] = "Kaliningrad";
			$city["Europe"]["Kiev"] = "Kiev";
			$city["Europe"]["Lisbon"] = "Lisbon";
			$city["Europe"]["Ljubljana"] = "Ljubljana";
			$city["Europe"]["London"] = "London";
			$city["Europe"]["Luxembourg"] = "Luxembourg";
			$city["Europe"]["Madrid"] = "Madrid";
			$city["Europe"]["Malta"] = "Malta";
			$city["Europe"]["Mariehamn"] = "Mariehamn";
			$city["Europe"]["Minsk"] = "Minsk";
			$city["Europe"]["Monaco"] = "Monaco";
			$city["Europe"]["Moscow"] = "Moscow";
			$city["Europe"]["Oslo"] = "Oslo";
			$city["Europe"]["Paris"] = "Paris";
			$city["Europe"]["Podgorica"] = "Podgorica";
			$city["Europe"]["Prague"] = "Prague";
			$city["Europe"]["Riga"] = "Riga";
			$city["Europe"]["Rome"] = "Rome";
			$city["Europe"]["Samara"] = "Samara";
			$city["Europe"]["San_Marino"] = "San Marino";
			$city["Europe"]["Sarajevo"] = "Sarajevo";
			$city["Europe"]["Simferopol"] = "Simferopol";
			$city["Europe"]["Skopje"] = "Skopje";
			$city["Europe"]["Sofia"] = "Sofia";
			$city["Europe"]["Stockholm"] = "Stockholm";
			$city["Europe"]["Tallinn"] = "Tallinn";
			$city["Europe"]["Tirane"] = "Tirane";
			$city["Europe"]["Uzhgorod"] = "Uzhgorod";
			$city["Europe"]["Vaduz"] = "Vaduz";
			$city["Europe"]["Vatican"] = "Vatican";
			$city["Europe"]["Vienna"] = "Vienna";
			$city["Europe"]["Vilnius"] = "Vilnius";
			$city["Europe"]["Volgograd"] = "Volgograd";
			$city["Europe"]["Warsaw"] = "Warsaw";
			$city["Europe"]["Zagreb"] = "Zagreb";
			$city["Europe"]["Zaporozhye"] = "Zaporozhye";
			$city["Europe"]["Zurich"] = "Zurich";

			$city["Indian"]["Antananarivo"] = "Antananarivo";
			$city["Indian"]["Chagos"] = "Chagos";
			$city["Indian"]["Christmas"] = "Christmas";
			$city["Indian"]["Cocos"] = "Cocos";
			$city["Indian"]["Comoro"] = "Comoro";
			$city["Indian"]["Kerguelen"] = "Kerguelen";
			$city["Indian"]["Mahe"] = "Mahe";
			$city["Indian"]["Maldives"] = "Maldives";
			$city["Indian"]["Mauritius"] = "Mauritius";
			$city["Indian"]["Mayotte"] = "Mayotte";
			$city["Indian"]["Reunion"] = "Reunion";

			$city["Pacific"]["Apia"] = "Apia";
			$city["Pacific"]["Auckland"] = "Auckland";
			$city["Pacific"]["Chatham"] = "Chatham";
			$city["Pacific"]["Easter"] = "Easter";
			$city["Pacific"]["Efate"] = "Efate";
			$city["Pacific"]["Enderbury"] = "Enderbury";
			$city["Pacific"]["Fakaofo"] = "Fakaofo";
			$city["Pacific"]["Fiji"] = "Fiji";
			$city["Pacific"]["Funafuti"] = "Funafuti";
			$city["Pacific"]["Galapagos"] = "Galapagos";
			$city["Pacific"]["Gambier"] = "Gambier";
			$city["Pacific"]["Guadalcanal"] = "Guadalcanal";
			$city["Pacific"]["Guam"] = "Guam";
			$city["Pacific"]["Honolulu"] = "Honolulu";
			$city["Pacific"]["Johnston"] = "Johnston";
			$city["Pacific"]["Kiritimati"] = "Kiritimati";
			$city["Pacific"]["Kosrae"] = "Kosrae";
			$city["Pacific"]["Kwajalein"] = "Kwajalein";
			$city["Pacific"]["Majuro"] = "Majuro";
			$city["Pacific"]["Marquesas"] = "Marquesas";
			$city["Pacific"]["Midway"] = "Midway";
			$city["Pacific"]["Nauru"] = "Nauru";
			$city["Pacific"]["Niue"] = "Niue";
			$city["Pacific"]["Norfolk"] = "Norfolk";
			$city["Pacific"]["Noumea"] = "Noumea";
			$city["Pacific"]["Pago_Pago"] = "Pago Pago";
			$city["Pacific"]["Palau"] = "Palau";
			$city["Pacific"]["Pitcairn"] = "Pitcairn";
			$city["Pacific"]["Ponape"] = "Ponape";
			$city["Pacific"]["Port_Moresby"] = "Port Moresby";
			$city["Pacific"]["Rarotonga"] = "Rarotonga";
			$city["Pacific"]["Saipan"] = "Saipan";
			$city["Pacific"]["Tahiti"] = "Tahiti";
			$city["Pacific"]["Tarawa"] = "Tarawa";
			$city["Pacific"]["Tongatapu"] = "Tongatapu";
			$city["Pacific"]["Truk"] = "Truk";
			$city["Pacific"]["Wake"] = "Wake";
			$city["Pacific"]["Wallis"] = "Wallis";

			return $city;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  Logs for Notes / remarks  ==  "  >

		/**
		 * Add Log info  to  Notes of bookings
		 *
		 * @param array | int $booking_id_arr
		 * @param string $message
		 */
		function wpbc_db__add_log_info( $booking_id_arr, $message ) {

			if ( get_bk_option( 'booking_log_booking_actions' ) !== 'On' ) {
				return;
			}

			$booking_id_arr = (array) $booking_id_arr;

			$is_append = true;
			foreach ( $booking_id_arr as $booking_id ) {
				$date_time = date_i18n( '[Y-m-d H:i] ' );
				make_bk_action('wpdev_make_update_of_remark' , $booking_id , $date_time . $message , $is_append );                //FixIn: 9.1.2.14
			}
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  CSV  export support function  ==  "  >

		/**
		 * Get Path from request URL.  E.g.  'my-category/file.csv'  from  url,  like  https://server.com/my-category/file.csv
		 *
		 * It can detect internal sub folder or WordPress,  like http://server.com/my-website/
		 *
		 * @return false|string
		 */
		function wpbc_get_request_url_path(){

			if ( function_exists( 'wp_parse_url' ) ) {
				$my_parsed_url = wp_parse_url( $_SERVER['REQUEST_URI'] );
			} else {
				$my_parsed_url = @parse_url( $_SERVER['REQUEST_URI'] );
			}

			if ( false === $my_parsed_url ) {        // seriously malformed URLs, parse_url() may return FALSE.
				return false;
			}

			$my_parsed_url_path = trim( $my_parsed_url['path'] );
			$my_parsed_url_path = trim( $my_parsed_url_path, '/' );


			// Check internal sub folder of WP,  like http://server.com/my-website/[ ... LINK ...]                              //FixIn: 2.0.5.4
			if ( function_exists( 'wp_parse_url' ) ) {
				$wp_home_server_url = wp_parse_url( home_url() );
			} else {
				$wp_home_server_url = @parse_url( home_url() );
			}

			if ( ( false !== $wp_home_server_url ) && ( ! empty( $wp_home_server_url['path'] ) ) ) {		                    // seriously malformed URLs, parse_url() may return FALSE.

				$server_url_sufix	 = trim( $wp_home_server_url[ 'path' ] );       // [path] => /my-website
				$server_url_sufix	 = trim( $server_url_sufix, '/' );              // my-website

				if ( ! empty( $server_url_sufix ) ) {

					$check_sufix = substr( $my_parsed_url_path, 0, strlen( $server_url_sufix ) );

					if ( $check_sufix === $server_url_sufix ) {

						$my_parsed_url_path = substr( $my_parsed_url_path, strlen( $server_url_sufix ) );

						$my_parsed_url_path = trim( $my_parsed_url_path, '/' );
					}
				}
			}                                                                                                                   //End FixIn: 2.0.5.4

			return $my_parsed_url_path;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  SKIP BLANK EMAIL  ==  "  >


		/**
		 * Check if this email  is NOT  blank email to  SKIP email  sending -- from autofill fast booking creation at Booking > Add booking page.
		 *
		 * Currently,  plugin  use 'blank@wpbookingmanager.com' for all  blank  bookings, previously  it was used 'admin@blank.com'
		 *
		 * @param $email_address
		 * @param $email_content
		 *
		 * @return bool
		 */
		function wpbc_is_not_blank_email( $email_address, $email_content ) {

			// Previously: 		if ( ( strpos( $to, '@blank.com' ) === false ) && ( strpos( $replace['content'], 'admin@blank.com' ) === false ) ) {  ->send(...) }

			if (
				   ( false === strpos( $email_address, '@blank.com' ) )
				&& ( false === strpos( $email_content, 'admin@blank.com' ) )
				&& ( false === strpos( $email_address, '@wpbookingmanager.com' ) )
				&& ( false === strpos( $email_content, 'blank@wpbookingmanager.com' ) )
			) {
				return true;
			}

			return false;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc="  ==  PHP PERFORMANCE  ==  "  >


		/**
		 * Start calculation  of time for execution  of specific part  of CODE
		 *
		 * @param string $name              name of section
		 * @param array $php_performance
		 *
		 * @return array
		 *
		 *  Example:
		 *
		 *  	$php_performance = php_performance_START( 'emails_sending' , $php_performance );
		 *
		 *      ... some code here ...
		 *
		 *  	$php_performance = php_performance_END(   'emails_sending' , $php_performance );
		 *
		 *      echo 'Time of execution: ' . $php_performancep['emails_sending']
		 *
		 */
		function php_performance_START( $name, $php_performance ) {

			if ( empty( $php_performance ) ) {
				$php_performance = array();
			}

			$php_performance[ $name ] = microtime( true );

			return $php_performance;
		}

		/**
		 * End calculation  of time for execution  of specific part  of CODE
		 *
		 * @param string $name              name of section
		 * @param array $php_performance
		 *
		 * @return array
		 *
		 *  Example:
		 *
		 *  	$php_performance = php_performance_START( 'emails_sending' , $php_performance );
		 *
		 *      ... some code here ...
		 *
		 *  	$php_performance = php_performance_END(   'emails_sending' , $php_performance );
		 *
		 *      echo 'Time of execution: ' . $php_performancep['emails_sending']
		 *
		 */
		function php_performance_END( $name, $php_performance ) {

			if ( empty( $php_performance ) ) {
				$php_performance = array();
			}
			if ( empty( $php_performance[ $name ] ) ) {
				$php_performance[ $name ] = microtime( true );
			}

			$php_performance[ $name ] = microtime( true ) - $php_performance[ $name ];

			return $php_performance;
		}

		/**
		 * Set maximum number of seconds for execution.
		 *
		 * @param $limit_in_seconds_int
		 *
		 * @return void
		 */
		function wpbc_set_limit_php( $limit_in_seconds_int = 300 ){

			$is_win = ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) );

			// Windows does not have support for this timeout function
			if ( ! $is_win ) {

				$max = (int) ini_get( 'max_execution_time' );

				// If unlimited, or if set_time_limit is disabled,  then skip.
				if (
						( 0 !== $max )
					 && ( $limit_in_seconds_int > $max )
					 && ( function_exists( 'set_time_limit' ) )
					 && ( false === strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) )
					 && ( ! ini_get( 'safe_mode' ) )
				) {
					@set_time_limit( $limit_in_seconds_int );
					@ini_set( 'memory_limit', '512M' );
				}
			}
		}


	// </editor-fold>