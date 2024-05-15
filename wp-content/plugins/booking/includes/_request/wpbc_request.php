<?php /**
 * @version 1.0
 * @description Universal class for REQUEST params cleaning (sanitizing) and saving such data, relative to current WP user.
 * @category  WPBC_AJX__REQUEST Class
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2022-10-29
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.3.1.2


/**
 *  Class for sanitizing $_REQUEST parameters and saving or getting it in/from  DB
 *
 *
 * Example 1:
 *
       	$user_request = new WPBC_AJX__REQUEST( array(
												   'db_option_name'          => 'booking_availability_request_params',
												   'user_id'                 => wpbc_get_current_user_id(),
												   'request_rules_structure' => WPBC_AJX__Availability::request_rules_structure()
	       										)
	   					);
		$escaped_request_params_arr = $user_request->get_sanitized__saved__user_request_params();		// Get Saved

	    if ( false === $escaped_request_params_arr ) {			// This request was not saved before, then get sanitized direct parameters, like: 	$_REQUEST['resource_id']

			$request_prefix = false;
		    $escaped_request_params_arr = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 		// Direct: 	$_REQUEST['resource_id']
		}
 *
 *
 * Example 2:
 *
        $user_request = new WPBC_AJX__REQUEST( array(
												    'db_option_name'          => 'booking_availability_request_params',
												    'user_id'                 => wpbc_get_current_user_id(),
												    'request_rules_structure' => array(
			                                                                           'resource_id'                   => array( 'validate' => 'digit_or_csd',  	'default' => 1 )
																					 , 'ui_wh_booking_date_checkout'   => array( 'validate' => 'digit_or_date',  	'default' => '' )		                    // number | date 2016-07-20
																					 , 'wh_modification_date' 			=> array( 'validate' => 'array',  	        'default' => array( '3' ) )		                    // number | date 2016-07-20
																					 , 'ui_wh_modification_date_radio' => array( 'validate' => 'd',  	            'default' => 0 )		                    // '1' | '2' ....
																					 , 'keyword'     			        => array( 'validate' => 's',  	            'default' => '' )			                //string
																					 , 'wh_cost'  		                => array( 'validate' => 'float_or_empty',  	'default' => '' )	        // '1' | ''
																					 , 'ui_usr__send_emails'           => array( 'validate' => array( 'send', 'not_send' ),  'default' => 'send' )
																				 )
                         ) );
 *
 *
 * OR ******************************************************************************************************************
 *      $user_request = new WPBC_AJX__REQUEST();
 *
 *      $user_request->set_db_option_name__for_saved_request( 'booking_availability_request_params' );
 *
 *      $user_request->set_user_id( wpbc_get_current_user_id() );
 *
 *          $user_request->set_default_request_structure(  array(
 *                                                          'resource_id' => array( 'validate' => 'digit_or_csd',  'default' => array( '1' ) )
 *                                                  )  );
 *      OR:
 *          $user_request->set_default_request_structure(  WPBC_AJX__Availability::get__request_params__names_default()  );
 *
 ***********************************************************************************************************************
 *
 *     $params_arr = $user_request->get_sanitized__saved__user_request_params();                                        // Initial Load - get params SAVED in DB - user open page with same view as before
 * OR
 *     $params_arr = $user_request->get_sanitized__in_request__value_or_default( false  );                              // Direct  -  $_REQUEST['resource_id']
 * OR
 *     $params_arr = $user_request->get_sanitized__in_request__value_or_default( 'search_params' );                     // In Ajax request   -  $_REQUEST['search_params']['resource_id']
 *
 *
 ***********************************************************************************************************************
 *  Request Rules Structure
 ***********************************************************************************************************************
 *
  array(
		 , 'page_num'  			            => array( 'validate' => 'd',  	            'default' => 1 )				// 1 | 0
		 , 'cost'  			                => array( 'validate' => 'f',  	            'default' => 1.5 )				// 1.5 | 0
		 , 'wh_cost'  		                => array( 'validate' => 'float_or_empty',  	'default' => '' )			    // 1.5 | ''
		 , 'wh_num'  		                => array( 'validate' => 'digit_or_empty',  	'default' => '' )			    // 1 | ''
         , 'keyword'     			        => array( 'validate' => 's',  	            'default' => '' )			    // string

		 , 'wh_booking_date' 			    => array( 'validate' => 'array',  	        'default' => array( "3" ) )		// Elements of array checked as string
		 , 'ui_wh_booking_date'             => array( 'validate' => 'date',  	        'default' => '' )		        // Date                 e.g.: '2022-05-31' or ''

		                                                                                                                // 'digit_or_csd' can check about 'digit_or_csd' in arrays, as well
         , 'wh_booking_type'               => array( 'validate' => 'digit_or_csd',  	'default' => array( '0' ) )	    // Digit or comma separated digit ( e.g.: '12,a,45,9' => '12,0,45,9' )             // if ['0'] - All  booking resources,  ['-1'] - lost bookings in deleted resources
		 , 'ui_wh_booking_date_checkout'   => array( 'validate' => 'digit_or_date',  	'default' => '' )		        // digit or Date       e.g.:  '2022-05-31' or 5  or ''

		 , 'ui_wh_start'                   => array( 'validate' => 'checked_skip_it',  'default' => '' )		        // Skip  from  checking

		 , 'ui_usr__is_expand_remarks'     => array( 'validate' => array( 'On', 'Off' ),   'default' => 'Off' )
   );
 *
 *
 *
 ***********************************************************************************************************************
 *  Otherwise possible to use      ' Direct Clean Params '
 ***********************************************************************************************************************
 *
				$request_params_ajx_booking  = array(
										  'page_num'          => array( 'validate' => 'd', 					'default' => 1 )
										, 'page_items_count'  => array( 'validate' => 'd', 					'default' => 10 )
										, 'sort'              => array( 'validate' => array( 'ajx_booking_id' ),	'default' => 'ajx_booking_id' )
										, 'sort_type'         => array( 'validate' => array( 'ASC', 'DESC'),'default' => 'DESC' )
										, 'status'            => array( 'validate' => 's', 					'default' => '' )
										, 'keyword'           => array( 'validate' => 's', 					'default' => '' )
										, 'ru_create_date'    => array( 'validate' => 'date', 				'default' => '' )
				);
				$request_params_values = array(                                                                             // Usually 		$request_params_values 	is  $_REQUEST
										'page_num'         => 1,
										'page_items_count' => 3,
										'sort'             => 'ajx_booking_id',
										'sort_type'        => 'DESC',
										'status'           => '',
										'keyword'          => '',
										'ru_create_date'   => ''
								);
				$request_params = wpbc_sanitize_params_in_arr( $request_params_values, $request_params_ajx_booking );

 */
class WPBC_AJX__REQUEST {

	private $db_option_name__for_saved_request;
	private $request_rules_structure;
	private $user_id;

    public function __construct( $params ) {
		$defaults = array(
						   'db_option_name'          => 'general_booking_request_params',
						   'user_id'                 => wpbc_get_current_user_id(),
						   'request_rules_structure' => array()
                    );
		$params   = wp_parse_args( $params, $defaults );


	    if ( ! empty( $params['db_option_name'] ) ) {
		    $this->set_db_option_name__for_saved_request( $params['db_option_name'] );
	    }

        if ( ! empty( $params['user_id'] ) ) {
	        $this->set_user_id( $params['user_id'] );
        }

        if ( ! empty( $params['request_rules_structure'] ) ) {
	        $this->set_request_rules_structure( $params['request_rules_structure'] );
        }
    }

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Setters
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		/**
		 * Set name of option for saving request parameters into DB
		 *
		 * @param string $db_option_name
		 *
		 * @return void
		 */
		public function set_db_option_name__for_saved_request( $db_option_name ){
		    $this->db_option_name__for_saved_request = $db_option_name;
		}

		/**
		 * Set current active user ID
		 *
		 * @param int $user_id
		 *
		 * @return void
		 */
		public function set_user_id( $user_id ){
		    $this->user_id = $user_id;
		}

		/**
		 * Set request structure   for validation and default values
		 *
		 * @param array $request_rules_structure            array(
	     *                                                          'resource_id' => array( 'validate' => 'digit_or_csd',  'default' => array( '1' ) )
		 *                                                          , ...
	     *                                                  )
		 *
		 * @return void
		 */
		public function set_request_rules_structure( $request_rules_structure ){
		    $this->request_rules_structure = $request_rules_structure;
		}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// Request Structure
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Get params names for escaping and/or default value of such  params
	 *
	 * @param string $structure_type    'validate_and_default' (default) | 'validate' | 'default'
	 *
	 * @return array
	 *                      if $structure_type == ''               array ( 'keyword'      => array( 'validate' => 's', 'default' => '' )
	 *                                                                   , 'wh_booking_date' => array( 'validate' => 'digit_or_date', 'default' => '3' ), ...
	 *                      if $structure_type == 'validate'       array ( 'keyword'      => 's'
	 *                                                                   , 'wh_booking_date' => 'digit_or_date'), ...
	 *                      if $structure_type == 'default'        array ( 'keyword'      => ''
	 *                                                                  , 'wh_booking_date'  => '3' ), ...
	 */
	public function get_request_rules_structure( $structure_type = 'validate_and_default' ){

		$params_for_cleaning = $this->request_rules_structure;

		if ( 'validate_and_default' == $structure_type ) {
			return $params_for_cleaning;
		}

		$return_simple_arr = array();

		foreach ( $params_for_cleaning as $key => $value ) {
			$return_simple_arr[ $key ] = $value[ $structure_type ];
		}
		return $return_simple_arr;

	}


	/**
	 * Get default values from rules structure
	 *
	 * @return array      array ( 'keyword'      => '', 'wh_booking_date'  => '3' ), ...
	 */
	public function get_request_rules__default( ){

		return $this->get_request_rules_structure( 'default' );
	}


	/**
	 * Get validate rules from params structure
	 *
	 * @return array      array ( 'keyword'      => 's', 'wh_booking_date' => 'digit_or_date'), ...
	 */
	public function get_request_rules__validate( ){

		return $this->get_request_rules_structure( 'validate' );
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Get user Saved params
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Get $_REQUEST saved before in DB from user  -  Sanitized Array
	 *
	 * @return
	            * false   - if not saved before
            * OR
	            * array(
					  *   'page_num' 			=> 1
					  * , 'page_items_count' 	=> 100
					  * , 'sort' 				=> 'ajx_booking_id'
					  * , 'sort_type' 			=> 'DESC'
					  * , 'keyword' 			=> ''
					  * , 'status' 				=> ''
				* )
	 */
	public function get_sanitized__saved__user_request_params() {

		$request_params_values_arr = $this->user_request_params__db_get();		// - $request_params_values_arr - un-serialized here automatically

		if ( false !== $request_params_values_arr ) {

			$request_params_structure = $this->get_request_rules_structure();		    /**
																							 *    array(      'page_num'          => array( 'validate' => 'd',                    'default' => 1 )
																							 * , 'page_items_count'  => array( 'validate' => 'd',                    'default' => 10 )
																							 * , 'sort'              => array( 'validate' => array( 'booking_id' ),    'default' => 'booking_id' )
																							 * , 'sort_type'         => array( 'validate' => array( 'ASC', 'DESC'),'default' => 'DESC' )
																							 * , 'keyword'           => array( 'validate' => 's',                    'default' => '' )
																							 * , 'create_date'       => array( 'validate' => 'date',                'default' => '' )
																							 * )
																							 */

			$escaped_request_params   = wpbc_sanitize_params_in_arr( $request_params_values_arr, $request_params_structure );	// Escaping params here

			return $escaped_request_params;

		} else {
			return false;
		}
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Get sanitized $_REQUEST params      |      default values
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Get "sanitized values" from $_REQUEST or "default values", based on rules structure
	 *
	 * @param string | false $prefix_in_request
	 *
	 *          default FALSE
	 *
	 *          string parameter useful, if request like this (usually  in Ajax):
	 *
     *                                        $_REQUEST (
	 *													['listing_page']['page_num'] => 3
	 *													['listing_page']['page_items_count'] => 20
	 *													['listing_page']['sort'] => 'booking_id'
	 *										 )
	 *
	 *          then we use this:            $this->get_sanitized__in_request__value_or_default( 'listing_page' )
	 *
	 *
	 * @return array
	 *                     Example:    array(
	 *                                          ['page_num'] => 3
	 *                                          ['page_items_count'] => 20
	 *                                          ['sort'] => 'booking_id'
	 *                                      )
	 */
	public function get_sanitized__in_request__value_or_default(  $prefix_in_request = false ){

		$request_rules_structure = $this->get_request_rules_structure();

		$request_params_values_arr = array();

		foreach ( $request_rules_structure as $request_key => $clean_type ) {

			$exist_request_param_value = wpbc_get_direct_value_in_request( $request_key, $prefix_in_request );          // Get direct request values:           $_REQUEST['page_num'] => 3
																											// if ( $prefix_in_request == 'listing_page' ):     $_REQUEST['listing_page']['page_num'] => 3
			if ( false !== $exist_request_param_value ) {
				$request_params_values_arr[ $request_key ] = $exist_request_param_value;
			}
		}

		$escaped_request_params = wpbc_sanitize_params_in_arr( $request_params_values_arr, $request_rules_structure );

		return $escaped_request_params;
	}


	// <editor-fold     defaultstate="collapsed"                        desc=" D B    S A V E  /   G E T     U S E R     R E Q U E S T "  >

		/**
		 * Save user filter request - saving user filters in toolbar
		 *
		 * @param array $params_arr
		 *
		 * @return bool|int
		 */
		function user_request_params__db_save( $params_arr ) {

			return update_user_option( $this->user_id, $this->db_option_name__for_saved_request ,  $params_arr );
		}

		/**
		 * Delete saved user request - used for resetting user filters in toolbar
		 *
		 * @return bool
		 */
		function user_request_params__db_delete() {

			return delete_user_option( $this->user_id, $this->db_option_name__for_saved_request );
		}

		/**
		 * Get saved user filter request - params for user filters in toolbar
		 *
		 * @return false|mixed
		 */
		function user_request_params__db_get() {

			return get_user_option( $this->db_option_name__for_saved_request, $this->user_id );
		}

	// </editor-fold>

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Get user option  -   single parameter
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Get user saved option  from  Request in Booking Listing
	 *
	 * @param $user_id		int			1
	 * @param $option_name	string		'ui_usr__send_emails'
	 *
	 * @return false|mixed
	 */
	function user_request_params__get_option( $option_name ){

		// Get User saved option from  request
		$escaped_request_params = $this->get_sanitized__saved__user_request_params();
		if ( ( ! empty( $escaped_request_params ) ) && ( ! empty( $escaped_request_params[ $option_name ] ) ) ) {
			return $escaped_request_params[ $option_name ];
		}

		// Get default option
		$default_param_values   = $this->get_request_rules_structure( 'default' );
		if  ( ! empty( $default_param_values[ $option_name ] ) ) {
			return $default_param_values[ $option_name ];
		}

		// There is no such option
		return false;
	}

		// Helpers:
		/**
		 * Is send emails ?		 Check DB SAVED user defined option from Options toolbar.
		 * @param $user_id  int  ID  of user
		 *
		 * @return int   1 | 0
		 */
		function user_request_option__is_send_emails(){

			$is_send_emeils = $this->user_request_params__get_option( 'ui_usr__send_emails' );

			$is_send_emeils = ( 'send' == $is_send_emeils ) ? 1 : 0;

			return $is_send_emeils;
		}
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Request
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/**
 * Get indexed or direct  request  from  REQUEST
 *
 * @param       $request_key
 * @param false $prefix_in_request
 *
 * @return false|mixed
 */
function wpbc_get_direct_value_in_request( $request_key, $prefix_in_request = false ) {

	/**
	 * Get value from 'direct request' -  "$_REQUEST['page_num'] => 3"
	 *    or
	 * from  'prefix request' - "$_REQUEST['request_params']['page_num'] => 3"
     */

	if (
		   ( empty( $prefix_in_request ) )
		&& ( isset( $_REQUEST[ $request_key ] ) )
	) {
		return $_REQUEST[ $request_key ];
	}

	if (
		    ( ! empty( $prefix_in_request ) )
		 && ( isset( $_REQUEST[ $prefix_in_request ] ) )
	     && ( isset( $_REQUEST[ $prefix_in_request ][ $request_key ] ) )
	) {
		return $_REQUEST[ $prefix_in_request ][ $request_key ];
	}

	return  false;
}


/**
 * Check specific parameters in ARRAY and return cleaned params or default values
 *
 * @param array $request_params_values_arr        = / think like in $_REQUEST parameter /
 *                                      array(
												* 'page_num'         => 1,
												* 'page_items_count' => 10,
												* 'sort'             => 'rule_id',
												* 'sort_type'        => 'DESC',
												* 'status'           => '',
												* 'keyword'          => '',
												* 'create_date'	   => ''
										* )
 * @param array $params_rules                     = array(
 * 'page_num'          => array( 'validate' => 'd', 					'default' => 1 )
											* , 'page_items_count'  => array( 'validate' => 'd', 					'default' => 10 )
											* , 'sort'              => array( 'validate' => array( 'rule_id' ),	'default' => 'rule_id' )
											* , 'sort_type'         => array( 'validate' => array( 'ASC', 'DESC'),'default' => 'DESC' )
											* , 'status'            => array( 'validate' => 's', 					'default' => '' )
											* , 'keyword'           => array( 'validate' => 's', 					'default' => '' )
											* , 'create_date'       => array( 'validate' => 'date', 				'default' => '' )
										* )
 *
 *
 * 'd';                             // '1' | ''
 * 's';                             // string   !!! Clean 'LIKE' string for DB !!!
 * 'digit_or_csd';                  // '0' | '1,2,3' | ''
 * 'digit_or_date';                 // number | date 2016-07-20
 *
 * 'checked_skip_it'                // Skip  checking
 *  array( '0', 'trash', 'any');    // Elements only listed in array
 *
 *@return array $clean_params = Array	(
											* [page_num] => 3
											* [page_items_count] => 20
											* [sort] => booking_id
											* [sort_type] => DESC
											* [keyword] =>
											* [source] =>
											* [create_date] =>
										* )
 *
 *
    Example of Direct Clean Params:

				$request_params_ajx_booking  = array(
										  'page_num'          => array( 'validate' => 'd', 					'default' => 1 )
										, 'page_items_count'  => array( 'validate' => 'd', 					'default' => 10 )
										, 'sort'              => array( 'validate' => array( 'ajx_booking_id' ),	'default' => 'ajx_booking_id' )
										, 'sort_type'         => array( 'validate' => array( 'ASC', 'DESC'),'default' => 'DESC' )
										, 'status'            => array( 'validate' => 's', 					'default' => '' )
										, 'keyword'           => array( 'validate' => 's', 					'default' => '' )
										, 'ru_create_date'    => array( 'validate' => 'date', 				'default' => '' )
				);
				$request_params_values = array(                                                                         // Usually 		$request_params_values 	is  $_REQUEST
										'page_num'         => 1,
										'page_items_count' => 3,
										'sort'             => 'ajx_booking_id',
										'sort_type'        => 'DESC',
										'status'           => '',
										'keyword'          => '',
										'ru_create_date'   => ''
								);
				$request_params = wpbc_sanitize_params_in_arr( $request_params_values, $request_params_ajx_booking );
 */
function wpbc_sanitize_params_in_arr( $request_params_values_arr, $params_rules ){

	$clean_params = array();

	foreach ( $params_rules as $request_key_name => $clean_type ) {

		if ( isset( $request_params_values_arr[ $request_key_name ] ) ) {
			$request_value_check = $request_params_values_arr[ $request_key_name ];
		} else {
			$request_value_check = false;
		}

		// If not defined in VALUES (think like in $_REQUEST parameter),  then  get  default value
		if ( false === $request_value_check ) {

			// D E F A U L T
			$clean_params[ $request_key_name ] = $params_rules[ $request_key_name ]['default'];

		} else {

			// C L E A N I N G
			$clean_type = $params_rules[ $request_key_name ]['validate'];

			// Check only values from this Array
			if ( is_array( $clean_type ) ) {

				$clean_type = array_map( 'strtolower', $clean_type );

				if ( ( isset( $request_value_check ) ) && ( ! in_array( strtolower( $request_value_check ), $clean_type ) ) ) {
					$clean_type          = 'checked_skip_it';
					$request_value_check = $params_rules[ $request_key_name ]['default'];                            //  Reset it, if value not in array And get default value
				} else {
					$clean_type = 'checked_skip_it';
				}
			}

			switch ( $clean_type ) {

				case 'checked_skip_it':
					$clean_params[ $request_key_name ] = $request_value_check;
					break;

				case 'date':													// Date
					$clean_params[ $request_key_name ] = wpbc_sanitize_date( $request_value_check );
					break;

				case 'csv_dates':													// CSV Dates: '11.11.2025, 12.11.2025, 13.11.2025'  or  '2024-02-06, 2024-02-10'
					$clean_params[ $request_key_name ] = wpbc_sanitize_csv_dates( $request_value_check );               //FixIn: 9.9.1.1
					break;

				case 'digit_or_date':                                            // digit or Date
					$clean_params[ $request_key_name ] = wpbc_sanitize_digit_or_date( $request_value_check );
					break;

				case 'digit_or_csd':                                            // digit or comma separated digit
					$clean_params[ $request_key_name ] = wpbc_sanitize_digit_or_csd( $request_value_check );
					break;

				case 's':                                                       // string
					$clean_params[ $request_key_name ] = wpbc_sanitize_text( $request_value_check );
					break;

				case 'strong':                                                       // string
					$clean_params[ $request_key_name ] = wpbc_sanitize_text_strong( $request_value_check );
					break;

				case 'array':
					if ( is_array( $request_value_check ) ) {
						foreach ( $request_value_check as $check_arr_index => $check_arr_value ) {
							$request_value_check[ $check_arr_index ] = wpbc_sanitize_text( $check_arr_value );     // Check  each option as string
						}
						$clean_params[ $request_key_name ] = $request_value_check;

					} else {
						$clean_params[ $request_key_name ] = $params_rules[ $request_key_name ]['default'];
					}
					break;

				case 'digit_or_empty':                                                       // digit or ''
					if ( '' === $request_value_check) {
						$clean_params[ $request_key_name ] = '';
					} else {
						$clean_params[ $request_key_name ] = intval( $request_value_check );
					}
					break;

				case 'float_or_empty':                                                       // digit or ''
					if ( '' === $request_value_check) {
						$clean_params[ $request_key_name ] = '';
					} else {

						// In case if was entered 10,99 instead of 10.99
						$request_value_check = str_replace( ',', '.', $request_value_check );

						$clean_params[ $request_key_name ] = floatval( $request_value_check );
					}
					break;

				case 'f':                                                                   // float
					$clean_params[ $request_key_name ] = floatval( $request_value_check );
					break;

				case 'd':                                                                   // digit
					$clean_params[ $request_key_name ] = intval( $request_value_check );
					break;

				default:
					$clean_params[ $request_key_name ] = intval( $request_value_check );
					break;
			}
		}
	}
	return $clean_params;
}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Sanitize functions
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Check  parameter  if it number or comma separated list  of numbers
	 *
	 * @param string | array $value
	 *
	 * @return string
	 *
	 * Example:
						* wpbc_sanitize_digit_or_csd( '12,a,45,9' )                  => '12,0,45,9'
	 * or
						* wpbc_sanitize_digit_or_csd( '10a' )                        => '10
	 * or
						* wpbc_sanitize_digit_or_csd( array( '12,a,45,9', '10a' ) )  => array ( '12,0,45,9',  '10' )
	 *@global type $wpdb
	 */
	function wpbc_sanitize_digit_or_csd( $value ) {                                //FixIn:6.2.1.4

		if ( $value === '' ) {
			return $value;
		}

		if ( is_array( $value ) ) {
			foreach ( $value as $key => $check_value ) {
				$value[ $key ] = wpbc_sanitize_digit_or_csd( $check_value );
			}

			return $value;
		}

		$value         = str_replace( ';', ',', $value );
		$array_of_nums = explode( ',', $value );


		$result = array();
		foreach ( $array_of_nums as $check_element ) {
			$result[] = intval( $check_element );
		}
		$result = implode( ',', $result );

		return $result;
	}


	/**
	 * Check  about Valid date or number, like 2016-07-20 and return this date or number
	 *
	 * @param string $value
	 *
	 * @return string|digit  '2022-05-31' or 5  or ''
	 */
	function wpbc_sanitize_digit_or_date( $value ) {                               //FixIn:6.2.1.4

		if ( $value === '' ) return $value;

		if ( preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value ) ) {

			return $value;                                                      // Date is valid in format: 2016-07-20
		} else {
			return intval( $value );
		}

	}


	/**
	 * Check about Valid date, like 2016-07-20 and return this date or ''
	 *
	 * @param string $value
	 *
	 * @return string '2022-05-31' or ''
	 */
	function wpbc_sanitize_date( $value ) {                               //FixIn:6.2.1.4

		if ( $value === '' ) return $value;

		if ( preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value ) ) {

			return $value;                                                      // Date is valid in format: 2016-07-20
		} else {
			return '';
		}

	}

	//FixIn: 9.9.1.1
	/**
	 * Check about Valid date, like '31.05.2022 and return this date or ''
	 *
	 * @param string $value
	 *
	 * @return string '31.05.2022' or ''
	 */
	function wpbc_sanitize_date_dmy( $value ) {

		if ( $value === '' ) return $value;

		if ( preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{4}$/", $value ) ) {

			return $value;                                                      // Date is valid in format: 31.05.2022
		} else {
			return '';
		}

	}


	//FixIn: 9.9.1.1
	/**
	 * Check about Valid date(s), like CSV Dates:  such  as: '11.11.2025, 12.11.2025, 13.11.2025'  or  '2024-02-06, 2024-02-10'    and then return them sanitized dates or ''
	 *
	 * @param string $value
	 *
	 * @return string '2022-05-31' or ''
	 */
	function wpbc_sanitize_csv_dates( $value ) {

		if ( '' === $value ) { return $value; }

		$value         = str_replace( ';', ',', $value );
		$array_of_nums = explode( ',', $value );

		$result = array();

		foreach ( $array_of_nums as $single_date ) {

			$single_date = trim( $single_date );

			// Check  for date '2024-02-06
			$date_ymd = wpbc_sanitize_date( $single_date );

			if ( '' !== $date_ymd ) {
				$result[] = $date_ymd;
			} else {

				// Otherwise check  for date: '06.02.2024'
				$date_dmy = wpbc_sanitize_date_dmy( $single_date );
				if ( '' !== $date_dmy ) {
					$result[] = $date_dmy;
				}
			}
		}

		$result = implode( ',', $result );

		return $result;
	}




	/**
	 * Escape string from SQL for the HTML form field
	 *
	 * @param string $value
	 *
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
	function wpbc_sanitize_text( $value ){

		$value_trimmed = trim( stripslashes( $value ) );                    // \' becomes ' and so on

		$esc_sql_value = sanitize_textarea_field( $value_trimmed );         // preserves new lines (\n) and other whitespace
		//$esc_sql_value = sanitize_text_field( $value_trimmed );           // remove new lines (\n) and other whitespace

		//global $wpdb;
		//$value = trim( $wpdb->prepare( "'%s'",  $esc_sql_value ) , "'" );
		//$esc_sql_value = trim( stripslashes( $esc_sql_value ) );

		return $esc_sql_value;
	}




	/**
	 * Escape string from SQL for the HTML form field
	 *
	 * @param string $value
	 *
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
	function wpbc_sanitize_text_strong( $value ){

		$value_trimmed = trim( stripslashes( $value ) );                    // \' becomes ' and so on

		//$esc_sql_value = sanitize_text_field( $value_trimmed );           // remove new lines (\n) and other whitespace
		$esc_sql_value = sanitize_textarea_field( $value_trimmed );         // preserves new lines (\n) and other whitespace

		// clean any tags
        $esc_sql_value = preg_replace( '/<[^>]*>/', '', $esc_sql_value );
        $esc_sql_value = str_replace( '<', ' ', $esc_sql_value );
        $esc_sql_value = str_replace( '>', ' ', $esc_sql_value );
        $esc_sql_value = strip_tags( $esc_sql_value );

		//FixIn: 9.7.4.1	-	escape coded html/xss							// Escape any XSS injection
		// If we have field converted to 'Unicode Hex Character Code', then  we make HTML decode firstly (html_entity_decode) and then make sanitizing
	    $esc_sql_value = sanitize_textarea_field( html_entity_decode( $esc_sql_value ) );

		// $esc_sql_value = str_replace('%', '&#37;', $esc_sql_value );            // clean any % from the form, because otherwise, there is problems with SQL prepare function
		// $esc_sql_value = str_replace('_', '&#95;', $esc_sql_value );            // clean any _

		// $esc_sql_value = str_replace('^', '&#94;', $esc_sql_value );            // clean any ^  caret symbols
		// $esc_sql_value = str_replace('~', '&#126;', $esc_sql_value );           // clean any ~ equivalency sign - tilde

		return $esc_sql_value;
	}