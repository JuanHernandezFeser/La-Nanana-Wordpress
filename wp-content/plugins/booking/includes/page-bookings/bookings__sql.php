<?php /**
 * @version 1.0
 * @description Bookings Listing SQL
 * @category  Booking Listing
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2022-04-07
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

// <editor-fold     defaultstate="collapsed"                        desc=" D E F A U L T     R E Q U E S T "  >

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
 *                      if $structure_type == 'default'        array ( 'keyword'      => '' )
 *                                                                  , 'wh_booking_date'  => '3' ), ...
 */
function wpbc_ajx_get__request_params__names_default( $structure_type = 'validate_and_default' ){

	// Clean specific $_REQUEST params, if param is NOT set then return "default"
	$params_for_cleaning =  array(
		  // 'wh_booking_id'  	                => array( 'validate' => 'digit_or_date',  	'default' => '' ),

																 // 'digit_or_csd' can check about 'digit_or_csd' in arrays, as well
		  'wh_booking_type'                 => array( 'validate' => 'digit_or_csd',  	'default' => array( '0' ) )	    // if ['0'] - All  booking resources,  ['-1'] - lost bookings in deleted resources

		, 'wh_approved'  	                => array( 'validate' => 'digit_or_csd',  	'default' => '' )		        // '0' | '1' | ''

			, 'wh_booking_date' 			=> array( 'validate' => 'array',  	        'default' => array( "3" ) )		// array( "0" ) - Current dates, | array( "3" ) - All dates    // number | date 2016-07-20
			, 'ui_wh_booking_date_radio'    => array( 'validate' => 'd',  	            'default' => 0 )		        // '1' | '2' ....
			, 'ui_wh_booking_date_next'     => array( 'validate' => 'd',  	            'default' => 0 )		        // '1' | '2' ....
			, 'ui_wh_booking_date_prior'    => array( 'validate' => 'd',  	            'default' => 0 )		        // '1' | '2' ....
			, 'ui_wh_booking_date_checkin'  => array( 'validate' => 'digit_or_date',  	'default' => '' )		        // number | date 2016-07-20
			, 'ui_wh_booking_date_checkout' => array( 'validate' => 'digit_or_date',  	'default' => '' )		        // number | date 2016-07-20

		, 'wh_what_bookings'            => array( 'validate' => array( 'any', 'new', 'imported', 'in_plugin' ),        'default' => 'any' )	        // '1' | ''

		, 'wh_modification_date' 			=> array( 'validate' => 'array',  	'default' => array( "3" ) )		        // number | date 2016-07-20
		, 'ui_wh_modification_date_radio'    => array( 'validate' => 'd',  	            'default' => 0 )		        // '1' | '2' ....
		, 'ui_wh_modification_date_prior'    => array( 'validate' => 'd',  	            'default' => 0 )		        // '1' | '2' ....
		, 'ui_wh_modification_date_checkin'  => array( 'validate' => 'digit_or_date',  	'default' => '' )		        // number | date 2016-07-20
		, 'ui_wh_modification_date_checkout' => array( 'validate' => 'digit_or_date',  	'default' => '' )		        // number | date 2016-07-20

		, 'keyword'     			        => array( 'validate' => 's',  	            'default' => '' )			    //string

		, 'wh_pay_status'  			        => array( 'validate' => 'array',	        'default' => array( 'all' ) )
		, 'ui_wh_pay_status_radio'          => array( 'validate' => 's',  	            'default' => '' )		        // string
		, 'ui_wh_pay_status_custom'         => array( 'validate' => 's',  	            'default' => '' )		        // string

		, 'wh_cost'  		                => array( 'validate' => 'float_or_empty',  	            'default' => '' )			    // '1' | ''
		, 'wh_cost2'  		                => array( 'validate' => 'float_or_empty',  	            'default' => '' )				// '1' | ''

		, 'wh_sort'                         => array( 'validate' => array(
																			'booking_id__asc',
																			'booking_id__desc',
																			'dates__asc',
																			'dates__desc',
																			'resource__asc',
																			'resource__desc',
																			'cost__asc',
																			'cost__desc'
														),	                                            'default' => 'booking_id__desc' )
		, 'wh_trash'  		                => array( 'validate' => array( '0', 'trash', 'any' ),		'default' => 'any' )

		, 'page_num'  			            => array( 'validate' => 'd',  	            'default' => 1 )				// '1' | ''
		, 'page_items_count'  	            => array( 'validate' => 'd',  	            'default' => 10 )				// '1' | ''

		, 'ui_usr__send_emails'                 => array(   'validate' => array( 'send', 'not_send' ),                  'default' => 'send' )
		, 'ui_usr__is_expand_remarks'           => array(   'validate' => array( 'On', 'Off' ),                         'default' => 'Off' )
 		, 'ui_usr__default_selected_toolbar'    => array(   'validate' => array( 'filters', 'actions', 'options' ),     'default' => 'filters' )

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		, 'ui_reset'  			            => array( 'validate' => 's',  	                    'default' => '' )				// string
		, 'ui_usr__dates_short_wide'        => array( 'validate' => array( 'short', 'wide' ),	'default' => 'short' )
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		, 'view_days_num'  		            => array( 'validate' => 'd',  	            'default' => 30 )				// '1' | ''			//FixIn: 8.9.2.1
		, 'scroll_start_date' 		        => array( 'validate' => 'digit_or_date',  	'default' => '' )		        // number | date 2016-07-20
		, 'scroll_day'  			        => array( 'validate' => 'd',  				'default' => 0 )		        // '1' | ''
		, 'scroll_month'  			        => array( 'validate' => 'd',  				'default' => 0 )		        // '1' | ''
		, 'limit_hours' 			        => array( 'validate' => 'digit_or_csd',  	'default' => '' )		        //
		, 'only_booked_resources'  	        => array( 'validate' => 'd',  				'default' => 0 )		        // '1' | ''

	);

	if ( 'validate_and_default' == $structure_type ) {
		return $params_for_cleaning;
	}

	$return_simple_arr = array();

	foreach ( $params_for_cleaning as $key => $value ) {
		$return_simple_arr[ $key ] = $value[ $structure_type ];
	}
	return $return_simple_arr;
}

// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" S A V E     U S E R     R E Q U E S T "  >

/**
 * Save user filter request - saving user filters in toolbar
 *
 * @param array $params
 * @param integer $user_id
 *
 * @return bool|int
 */
function wpbc_ajx__user_request_params__save( $params, $user_id ) {

	// Reset  some parameters, like selected page
	if ( isset( $params['page_num'] ) ) {
		$params['page_num'] = 1;
		$params['keyword'] = '';
	}

	return update_user_option( (int) $user_id, 'booking_listing_request_params' ,  $params );
}

/**
 * Delete saved user request - used for resetting user filters in toolbar
 * @param integer $user_id
 *
 * @return bool
 */
function wpbc_ajx__user_request_params__delete( $user_id ) {

	if ( empty( $user_id ) ) {
		$user_id = wpbc_get_current_user_id();
	}

	return delete_user_option( (int) $user_id, 'booking_listing_request_params' );
}

/**
 * Get saved user filter request - params for user filters in toolbar
 *
 * @param integer $user_id
 *
 * @return false|mixed
 */
function wpbc_ajx__user_request_params__get( $user_id ) {

	if ( empty( $user_id ) ) {
		$user_id = wpbc_get_current_user_id();
	}

// wpbc_ajx__user_request_params__delete($user_id);       //For debugging. delete it.

	return get_user_option( 'booking_listing_request_params', (int) $user_id );
}

/**
 * Get sanitized array of use request parameters that was saved before
 *
 * @param $user_id  int
 *
 * @return array|false
 */
function wpbc_ajx__user_request_params__get_sanitized( $user_id ) {

	$request_params_values_arr = wpbc_ajx__user_request_params__get( $user_id );		// - $request_params_values_arr - unserialized here automatically

	if ( false !== $request_params_values_arr ) {

		$request_params_structure = wpbc_ajx_get__request_params__names_default();		/**
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

/**
 * Get user saved option  from  Request in Booking Listing
 *
 * @param $user_id		int			1
 * @param $option_name	string		'ui_usr__send_emails'
 *
 * @return false|mixed
 */
function wpbc_ajx__user_request_params__get_option( $user_id, $option_name ){

	// Get User saved option from  request
	$escaped_request_params = wpbc_ajx__user_request_params__get_sanitized( $user_id );
	if ( ( ! empty( $escaped_request_params ) ) && ( ! empty( $escaped_request_params[ $option_name ] ) ) ) {
		return $escaped_request_params[ $option_name ];
	}

	// Get default option
	$default_param_values   = wpbc_ajx_get__request_params__names_default( 'default' );
	if  ( ! empty( $default_param_values[ $option_name ] ) ) {
		return $default_param_values[ $option_name ];
	}

	// There is no such option
	return false;
}


	/**
	 * Is send emails ?		 Check DB SAVED user defined option from Options toolbar.
	 * @param $user_id  int  ID  of user
	 *
	 * @return int   1 | 0
	 */
	function wpbc_ajx__user_request_option__is_send_emails( $user_id ){

		$is_send_emeils = wpbc_ajx__user_request_params__get_option( $user_id, 'ui_usr__send_emails' );

		$is_send_emeils = ( 'send' == $is_send_emeils ) ? 1 : 0;

		return $is_send_emeils;
	}

	/**
	 * Is expand remarks by default ?		 Check DB SAVED user defined option from Options toolbar.
	 *
	 *      It's useful only, when we are checking during Ajax action requests,  where need to check data in Database,
	 *      For templates, need to use  JavaScript:
	 *                                              <# if ( 'Off' === wpbc_ajx_booking_listing.search_get_param('ui_usr__is_expand_remarks') ) { #>display: none;<# } #>
	 *
	 *
	 * @param $user_id  int  ID  of user
	 *
	 * @return int   1 | 0
	 */
	function wpbc_ajx__user_request_option__is_expand_remarks( $user_id ){

		$is_send_emeils = wpbc_ajx__user_request_params__get_option( $user_id, 'ui_usr__is_expand_remarks' );

		$is_send_emeils = ( 'On' == $is_send_emeils ) ? 1 : 0;

		return $is_send_emeils;
	}


// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc="  S Q L  "  >


	/**
	 * Get array of bookings    and    total number of items in all  pages.
	 *
	 * @param array $request_params     = Array(
											    [wh_booking_id] =>
											    [wh_booking_type] =>
											    [booking_type] =>
											    [wh_approved] =>
											    [wh_booking_date] =>
											    [wh_booking_date2] =>
											    [wh_booking_datenext] => 0
											    [wh_booking_dateprior] => 0
											    [wh_booking_datefixeddates] =>
											    [wh_booking_date2fixeddates] =>
											    [wh_is_new] => 0
											    [wh_modification_date] =>
											    [wh_modification_date2] =>
											    [wh_modification_dateprior] => 0
											    [wh_modification_datefixeddates] =>
											    [wh_modification_date2fixeddates] =>
											    [wh_pay_statuscustom] =>
											    [wh_pay_status] =>
											    [wh_cost] => 0
											    [wh_cost2] => 0
											    [wh_sort] =>
											    [wh_trash] =>
											    [page_num] => 1
											    [page_items_count] => 10
											    [view_days_num] => 30
											    [scroll_start_date] =>
											    [scroll_day] => 0
											    [scroll_month] => 0
											    [limit_hours] =>
											    [only_booked_resources] => 0
											)
	 *
	 * @return array                    = Array(
												[count] => 14
									            [data_arr] => Array (
														            [0] => Array (
																						[ajx_booking_id] => 14
																	                    [last_check_booking_id] => 0
																	                    [status] =>
																	                    [last_run_date] =>
																	                    [booking] => Array (
																	                            [email_template] => super_new
																	                            [conditions] => Array (
																	                                    [0] => Array (
																	                                            [if] => __system__|source
																	                                            [sign] => >=
																	                                            [value] => 1"0'0\0
																	                                        )
																                                        ), ...
																	                            )
								                                                        [ru_create_date] => 2020-01-25 10:36:55
								                                                        ...
													                ), ...
	 */
	function wpbc_ajx_get_booking_data_arr( $request_params ){

		// 1. Get booking resources (sql)
		$resources_arr = wpbc_ajx_get_all_booking_resources_arr();          /**
		                                                                     * Array (   [0] => Array (     [booking_type_id] => 1
																											[title] => Standard
																											[users] => 1
																											[import] =>
																											[export] =>
																											[cost] => 25
																											[default_form] => standard
																											[prioritet] => 0
																											[parent] => 0
																											[visitors] => 2
																				), ...                  */

		$resources_arr_sorted = wpbc_ajx_get_sorted_booking_resources_arr( $resources_arr );

		// 2. Get all bookings (sql)
		$bookings = wpbc_ajx_get__bookings_obj__sql( $request_params );	    /**
		                                                                     * Array (     [count] => 172,   [bookings] => Array (
																		            * [0] => stdClass Object (
																		                    [booking_id] => 175
																		                    [trash] => 0
																		                    [sync_gid] =>
																		                    [is_new] => 1
																		                    [status] =>
																		                    [sort_date] => 2022-04-19 12:00:01
																		                    [modification_date] => 2022-04-03 08:05:13
																		                    [form] => select-one^rangetime5^12:00 - 14:00~text^name5^test data~ ... ~checkbox^term_and_condition5[]^I Accept terms
																		                    [hash] => ae964965356f7c735139764eebe12a63
																		                    [booking_type] => 5
																		                    [remark] =>
																		                    [cost] => 50.00
																		                    [pay_status] => 164896951360.82
																		                    [pay_request] => 0
																	                ), ....
										                                            */
		$bookings_obj   = $bookings['bookings'];
		$bookings_count = $bookings['count'];


		// 3. Get booking dates (sql)
		$booking_dates_obj = wpbc_ajx_get__booking_dates_obj__sql( $bookings_obj );     /**
																						 * Array ( [0] => stdClass Object (
																												            [booking_dates_id] => 333
																												            [booking_id] => 165
																												            [booking_date] => 2022-03-28 11:00:01
																												            [approved] => 0
																												            [type_id] =>
																												        )
																								   [1] => ....
																						 */


		// 4. Include dates into bookings   (Wide and Short dates view)
		$bookings_with_dates = wpbc_ajx_include_bookingdates_in_bookings( $bookings_obj, $booking_dates_obj );
																   /**
																   Array (  [182] => stdClass Object (

																		            [booking_db] => stdClass Object (
																		                    [booking_id] => 182
																		                    [trash] => 0
																		                    [sync_gid] =>
																		                    [is_new] => 1
																		                    [status] =>
																		                    [sort_date] => 2023-01-23 10:00:01
																		                    [modification_date] => 2022-04-18 12:23:30
																		                    [form] => select-one^rangetime2^10:00 - 12:00~text^name2^rr~text^secondname2^hjk~email^email2^hyui@nbco.csdf~text^phone2^h~text^address2^khj~text^city2^e~text^postcode2^hj~select-one^country2^HT~select-one^visitors2^1~select-one^children2^0~textarea^details2^djkh~checkbox^term_and_condition2[]^I Accept term and conditions
																		                    [hash] => a1ae510d8fee961b7f8ae53101632151
																		                    [booking_type] => 2
																		                    [remark] =>
																		                    [cost] => 75.00
																		                    [pay_status] => 165028101080.84
																		                    [pay_request] => 0
																		                )
																		            [id] => 182
																		            [approved] => 0
																		            [dates] => Array (
																		                    [0] => 2023-01-23 10:00:01
																		                    [1] => 2023-01-25 00:00:00
																		                    [2] => 2023-01-27 12:00:02
																		                )
																		            [child_id] => Array (
																		                    [0] =>
																		                    [1] =>
																		                    [2] =>
																		                )
																		            [short_dates] => Array (
																		                    [0] => 2023-01-23 10:00:01
																		                    [1] => ,
																		                    [2] => 2023-01-25 00:00:00
																		                    [3] => ,
																		                    [4] => 2023-01-27 12:00:02
																		                )
																		            [short_dates_child_id] => Array (
																		                    [0] =>
																		                    [1] =>
																		                    [2] =>
																		                    [3] =>
																		                    [4] =>
																		                )
																		        )
																		    [181] => stdClass Object  ...
		                                                           */

		// 4.5 remove some bookings,  that  does not fit to  Dates conditions
		list( $bookings_with_dates, $bookings_count ) = wpbc_ajx_get__remove_bookings__where_dates_outside( $request_params, $bookings_with_dates, $bookings_count );

		// 5. Parse forms
		$parsed_bookings = wpbc_ajx_parse_bookings( $bookings_with_dates, $resources_arr );
																	/** array(	[188] => stdClass Object (
																					[booking_db] => stdClass Object (
																							[booking_id] => 188
																							[trash] => 0
																							[sync_gid] =>
																							[is_new] => 1
																							[status] =>
																							[sort_date] => 2023-03-05 10:00:01
																							[modification_date] => 2022-04-19 11:58:31
																							[form] => text^selected_short_dates_hint4^March 5, 2023~text^days_number_hint4^1~text^cost_hint4^&amp;#36;95~select-one^rangetime4^10:00 - 12:00~text^name4^test~email^email4^test@wpbookingcalendar.com~select-one^my_select4^1~select-multiple^multi_select4[]^0,1,2,3~checkbox^my_checkbx4[]^false~checkbox^my_multi_checkbx4[]^1~checkbox^my_multi_checkbx4[]^~checkbox^my_multi_checkbx4[]^3~checkbox^exclusive_multi_checkbx4^~checkbox^exclusive_multi_checkbx4^2~checkbox^exclusive_multi_checkbx4^~radio^my_radio4^2~select-one^country4^GB~textarea^details4^s~checkbox^term_and_condition4[]^I Accept term and conditions
																							[hash] => dd12c3a61f14aaca693f52d110d2723a
																							[booking_type] => 4
																							[remark] =>
																							[cost] => 95.00
																							[pay_status] => 165036591118.88
																							[pay_request] => 0 )
																					[id] => 188
																					[approved] => 0
																					[dates] => Array ( [0] => 2023-03-05 10:00:01 [1] => 2023-03-05 12:00:02 )
																					[child_id] => Array([0] => [1] => )
																					[short_dates] => Array ( [0] => 2023-03-05 10:00:01 [1] => - [2] => 2023-03-05 12:00:02 )
																					[short_dates_child_id] => Array ( [0] =>  [1] =>  [2] =>  )
																					[form_data] => Array
																						(
																							[selected_short_dates_hint] => March 5, 2023
																							[days_number_hint] => 1
																							[cost_hint] => &amp;#36;95
																							[rangetime] => 10:00 AM - 12:00 PM
																							[name] => test
																							...
																							[term_and_condition] => I Accept term and conditions
																							[booking_id] => 188
																							[trash] => 0
																							[sync_gid] =>
																							[is_new] => 1
																							[status] =>
																							[sort_date] => 2023-03-05 10:00:01
																							[modification_date] => 2022-04-19 11:58:31
																							[hash] => dd12c3a61f14aaca693f52d110d2723a
																							[booking_type] => 4
																							[cost] => 95.00
																							[pay_status] => 165036591118.88
																							[pay_request] => 0
																							[id] => 188
																							[approved] => 0
																							[resource_title] => Apartment#3
																							[_form_show] => "<div class="payment-content-form"><strong>Times</strong>:<span class="fieldvalue">10:00 AM - 12:00 PM</span>&nbsp;&nbsp; ...."
																						)
																		...
																	 */

	    // Reset array keys for correct  DESC sorting during sending Ajax request.
	    $parsed_bookings = array_values( $parsed_bookings );

		return array(
						'booking_resources' => $resources_arr_sorted, //$resources_arr,
						'data_arr'          => $parsed_bookings,
						'count'             => $bookings_count
					);
	}

		/**
		 * R E S O U R C E S  -  Get SORTED  booking resources as array
		 *
		 * @param $resources_arr array    Array (   [1] => Array (
											            [booking_type_id] => 1
											            [title] => Standard
											            [users] => 1
											            [import] =>
											            [export] =>
											            [cost] => 25
											            [default_form] => standard
											            [prioritet] => 0
											            [parent] => 0
											            [visitors] => 2
											        ), ...
		 *
		 * @return array
		 */
		function wpbc_ajx_get_sorted_booking_resources_arr( $resources_arr ) {

			if ( ! empty( $resources_arr ) ) {
				$resources_arr_sorted = wpbc_ajx_arrange_booking_resources_arr( $resources_arr );
				$resources_arr_sorted = $resources_arr_sorted['linear_resources'];
				foreach ( $resources_arr_sorted as $key_id => $resource ) {
					$resources_arr_sorted[ $key_id ]['title'] = apply_bk_filter( 'wpdev_check_for_active_language', $resources_arr_sorted[ $key_id ]['title'] );
				}
				// Reset keys for having correct  sorting (important for parent/child resources) after ajax response
				$resources_arr_sorted = array_values( $resources_arr_sorted );
			} else {
				$resources_arr_sorted = $resources_arr;
			}

			return $resources_arr_sorted;
		}


		/**
		 * R E S O U R C E S  -  Get all  booking resources as array
		 *
		 * @return array    Array (   [1] => Array (
											            [booking_type_id] => 1
											            [title] => Standard
											            [users] => 1
											            [import] =>
											            [export] =>
											            [cost] => 25
											            [default_form] => standard
											            [prioritet] => 0
											            [parent] => 0
											            [visitors] => 2
											        ), ...
		 */
		function wpbc_ajx_get_all_booking_resources_arr( ){

			if ( ! class_exists( 'wpdev_bk_personal' ) ) {
				return  array();
			}

			$db_names = wpbc_get_db_names();

			$sql = array();

			$sql['select'] = "SELECT * FROM {$db_names['resources']} as bt";

			$sql['where']  = " WHERE ( 1 = 1 )";

			if ( class_exists( 'wpdev_bk_multiuser' ) ) {                       // MultiUser - only specific booking resources for specific Regular User in Admin panel.

				if ( isset( $_REQUEST['wpbc_ajx_user_id'] ) ) {
					$user_bk_id = intval( $_REQUEST['wpbc_ajx_user_id'] );
				} else {
					$user_bk_id = wpbc_get_current_user_id();
				}
				$is_user_super_admin = apply_bk_filter( 'is_user_super_admin', $user_bk_id );

				if ( ! $is_user_super_admin ) {
					$sql['where'] .= 'AND users = ' . $user_bk_id . ' ';
				}
			}

			// Order depends from version       //FixIn: 9.9.0.23
			if ( class_exists( 'wpdev_bk_biz_l' ) ) {
				$sql['order'] = " ORDER BY parent, prioritet, title, booking_type_id";
			} else {
				$sql['order'] = " ORDER BY title, booking_type_id";
			}

			global $wpdb;
			$sql_prepared = //$wpdb->prepare(
											  $sql['select']
											. $sql['where']
											. $sql['order'];
							//				, $sql['sql_args']
							//			);

			$resources = $wpdb->get_results( $sql_prepared );

			$resources_arr = array();

			foreach ( $resources as $resource ) {
				$resources_arr[ $resource->booking_type_id ] = get_object_vars( $resource );
			}

			return $resources_arr;
		}


			/**
			 * Get arranged / sorted booking resources arrays
			 *
			 * @param $resources_sql_arr        array( ...
			 *                                         [4] => Array (
													            [booking_type_id] => 4
													            [title] => Apartment#3
													            [users] => 1
													            [import] =>
													            [export] =>
													            [cost] => 270
													            [default_form] => standard
													            [prioritet] => 1
													            [parent] => 0
													            [visitors] => 1
													        )
													    [5] => Array (
													            [booking_type_id] => 5
													            [title] => Standard-1
													            [users] => 1
													            [import] =>
													            [export] =>
													            [cost] => 25
													            [default_form] => standard
													            [prioritet] => 1
													            [parent] => 1
													            [visitors] => 1
													        )
			                                           ...
			 */
			function wpbc_ajx_arrange_booking_resources_arr( $all_resources ){

		        if ( count( $all_resources ) > 0 ) {

		            $resources               = array();
		            $child_resources         = array();
		            $parent_single_resources = array();

		            foreach ( $all_resources as $single_resources ) {

		                $single_resources       = $single_resources ;
		                $single_resources['id'] = $single_resources['booking_type_id'];

		                // Child booking resource
		                if (   ( ! empty( $single_resources[ 'parent' ] ) )
						   ){	 															// Child

							if ( ! isset( $child_resources[ $single_resources['parent'] ] ) )
		                        $child_resources[ $single_resources['parent'] ] = array();

		                    $child_resources[ $single_resources['parent'] ][ $single_resources['id'] ] =  $single_resources;

		                } else {                                                        // Parent or Single

		                    $parent_single_resources[ $single_resources['id'] ] = $single_resources;
		                }
		                                                                                // All resources
		                $resources[ $single_resources['id'] ] = $single_resources;
		            }


		            $final_resource_array = array();
		            foreach ( $parent_single_resources as $key => $res) {

		                // Calc Capacity
		                if ( isset( $child_resources[$res['id']] ) )    $res['count'] = count( $child_resources[$res['id']] ) + 1;
		                else                                            $res['count'] = 1;

		                // Fill the parent resource
		                $final_resource_array[ $res['id'] ] = $res;

		                // Fill all child resources (its already sorted) - for having linear array with child resourecs.
		                if ( isset( $child_resources[ $res['id'] ] ) ) {
		                    foreach ( $child_resources[ $res['id'] ] as $child_obj ) {
		                        $child_obj['count'] = 1;
		                        $final_resource_array[ $child_obj['id'] ] = $child_obj;
		                    }
		                }
		            }

		            return array(
		                              'linear_resources' => $final_resource_array
		                            , 'single_or_parent' => $parent_single_resources
		                            , 'child'            => $child_resources
		                        );
		        } else {
		            return false;
		        }
			}


		/**
		 *   S Q L  -   B O O K I N G S  -  Get array of "Bookings" objects from DB
		 *  based on request params
		 *
		 * @param array $request_params
		 *
		 * @return array of bookings sql objects
		 */
		function wpbc_ajx_get__bookings_obj__sql( $request_params ) {

			$defaults = wpbc_ajx_get__request_params__names_default( 'default' );

			$params   = wp_parse_args( $request_params, $defaults );

			global $wpdb;
			$db_names = wpbc_get_db_names();

			$sql_args = array();
			$sql      = array();


			$sql['start_select'] = " SELECT * ";
			$sql['start_count']  = " SELECT COUNT(*) as count";
			$sql['from']         = " FROM {$db_names['bookings']} as bk";

			$sql['where']  = " WHERE ( 1 = 1 )";

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// DATES
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sql['where'] .= " AND  (  EXISTS (
											   SELECT *
											   FROM {$db_names['dates']} as dt
											   WHERE  bk.booking_id = dt.booking_id ";

			// W H E R E :     Approved | Pending ----------------------------------------------------------------------
			if ( $request_params['wh_approved'] !== '' ) {
				$sql['where'] .= " AND approved = {$request_params['wh_approved']} ";
			}

			// W H E R E :    D A T E S --------------------------------------------------------------------------------
			if (1){
				if ( is_array( $request_params['wh_booking_date'] ) ) {
					$wh_booking_date = array_replace( array( '', '' ), $request_params['wh_booking_date'] );                // Always have minimum 2 values in arr
				} else {
					$wh_booking_date = array( '', '' );
				}

				if ( count( $wh_booking_date ) > 2 ) {
					$removed_el = array_shift( $wh_booking_date );                                                          // Remove an element off the beginning of array
				}
			}
			$sql['where'] .= wpbc_ajx__sql_where_for_dates( (string) $wh_booking_date[0], (string) $wh_booking_date[1] );

			$sql['where'] .= "	             )
			                         )";
			////////////////////////////////////////////////////////////////////////////////////////////////////////////


			// W H E R E :    M o d i f i c a t i o n    D A T E S    --------------------------------------------------
			if (1){
				if ( is_array( $request_params['wh_modification_date'] ) ) {
					$wh_modification_date = array_replace( array( '', '' ), $request_params['wh_modification_date'] );  // Always have minimum 2 values in arr
				} else {
					$wh_modification_date = array( '', '' );
				}

				if ( count( $wh_modification_date ) > 2 ) {
					$removed_el = array_shift( $wh_modification_date );                                                 // Remove an element off the beginning of array
				}
			}
			$sql['where'] .= wpbc_ajx__sql_where_for_modification_date( (string) $wh_modification_date[0], (string) $wh_modification_date[1] );



			// W H E R E :     Resources  ------------------------------------------------------------------------------
			if ( class_exists( 'wpdev_bk_personal' ) ) {

				if ( ! empty( $request_params['wh_booking_type'] ) ) {

					$sql['where'] .= wpbc_ajx__sql_where_for_resources(
																			$request_params['wh_booking_type'],
																			$request_params['wh_approved'],
																			(string) $wh_booking_date[0],
																			(string) $wh_booking_date[1]
																		);
				}
			}


			// W H E R E :     Payment Status  -------------------------------------------------------------------------
			if ( class_exists( 'wpdev_bk_biz_s' ) ) {

				if ( ! empty( $request_params['wh_pay_status'] ) ) {

					$sql_and_args = wpbc_ajx__sql_where_for_payment_status( $request_params['wh_pay_status'] );

					$sql['where'] .= $sql_and_args[0];

					if ( count( $sql_and_args[1] ) > 0 ) {
						foreach ( $sql_and_args[1] as $my_arg ) {
							$sql_args[] = $my_arg;
						}
					}

				}
			}


			// W H E R E :     C o s t    Min - Max  -------------------------------------------------------------------
			if ( class_exists( 'wpdev_bk_biz_s' ) ) {

				if (    ( isset( $request_params['wh_cost'] ) )
				     || ( isset( $request_params['wh_cost2'] ) ) ) {

					$wh_cost_min = ( isset( $request_params['wh_cost'] ) )  ? $request_params['wh_cost'] : '';
					$wh_cost_max = ( isset( $request_params['wh_cost2'] ) ) ? $request_params['wh_cost2'] : '';

					$sql_and_args = wpbc_ajx__sql_where_cost_min_max( $wh_cost_min, $wh_cost_max );

					$sql['where'] .= $sql_and_args[0];

					if ( count( $sql_and_args[1] ) > 0 ) {
						foreach ( $sql_and_args[1] as $my_arg ) {
							$sql_args[] = $my_arg;
						}
					}

				}
			}



			// W H E R E :    Trash  -----------------------------------------------------------------------------------
			if ( isset( $request_params['wh_trash'] ) ) {

				if ( '0' === $request_params['wh_trash'] ) {            // Existing
					$sql['where'] .= " AND bk.trash = 0 ";
				}

				if ( 'trash' === $request_params['wh_trash'] ) {        // In trash
					$sql['where'] .= " AND bk.trash = 1 ";
				}

				//if ( 'any' === $request_params['wh_trash'] ) { }      // Any
			}


			// W H E R E :    All bookings | New bookings	| Imported bookings	| Plugin bookings
			if ( isset( $request_params['wh_what_bookings'] ) ) {

				if ( 'any' === $request_params['wh_what_bookings'] ) {
					//$sql['where'] .= " AND bk.is_new = 0 ";
				}
				if ( 'new' === $request_params['wh_what_bookings'] ) {
					$sql['where'] .= " AND bk.is_new = 1";
				}
				if ( 'imported' === $request_params['wh_what_bookings'] ) {
					$sql['where'] .= " AND  bk.sync_gid != '' ";
				}
				if ( 'in_plugin' === $request_params['wh_what_bookings'] ) {
					$sql['where'] .= " AND  bk.sync_gid = '' ";
				}
			}


			// W H E R E :    K E Y W O R D
			if ( ! empty( $params['keyword'] ) ) {
				$sql['where'] .= " AND  (  ";

							/**
							 * Relative configuration  of LIKE sanitization
							 * check at  the bottom  here https://code.tutsplus.com/articles/data-sanitization-and-validation-with-wordpress--wp-25536
							 *
							 * So  this is a correct  way:
							 *
							 *      $sql['where'] .= "( bk.form LIKE %s ) ";
							 *      $sql_args[] = '%' . $wpdb->esc_like( $params['keyword'] ) . '%';
							 *
							 */

							$sql['where'] .= "( bk.form LIKE %s ) ";
							$sql_args[] = '%' . $wpdb->esc_like( $params['keyword'] ) . '%';

							if ( is_numeric( $params['keyword'] ) ) {
								$sql['where'] .= " OR ( bk.booking_id = %d ) ";
								$sql_args[] =  intval( $params['keyword'] );
							}

							$sql['where'] .= " OR ( bk.sync_gid LIKE %s ) ";
							$sql_args[] = '%' . $wpdb->esc_like( $params['keyword'] ) . '%';

							if ( class_exists( 'wpdev_bk_personal' ) )   {
								$sql['where'] .= " OR ( bk.remark LIKE %s ) ";
								$sql_args[] = '%' . $wpdb->esc_like( $params['keyword'] ) . '%';

								$sql['where'] .= " OR ( bk.hash LIKE %s ) ";
								$sql_args[] = '%' . $wpdb->esc_like( $params['keyword'] ) . '%';
							}

				$sql['where'] .= "     )";


				// W H E R E :   booking ID             //FixIn: 9.4.3.9
				$is_id = strpos( trim( strtolower( $params['keyword'] ) ) , 'id:' );        // Keyword,  like 'id:200'  OR  'id:100,105'
				if ( 0 === $is_id ){

					$search_booking_id     = substr( trim( $params['keyword'] ), 3 );
					$search_booking_id_arr = explode( ',', $search_booking_id );            // Get array of booking ID:  from  'id:200,201,15'  we get  [200,201,15]

					$sql_where_arr         = array();
					$sql_args              = array();                                       // It's last WHERE in a list so all previous arguments ($sql_args) we are resetting

					foreach ( $search_booking_id_arr as $bk_id ) {

						$sql_where_arr[] = ' bk.booking_id = %d ';
						$sql_args[]      = intval( $bk_id );
					}

					$sql['where'] = " WHERE  ( " . implode( ' OR ', $sql_where_arr ) . ") ";

					$sql['where'] = apply_bk_filter('update_where_sql_for_getting_bookings_in_multiuser', $sql['where'] );      // Add 'AND bk.booking_type IN ( $user_resources )'  to  the end of Where
				}
			}

			////////////////////////////////////////////////////////////////////////////////////////////////////////////////


			switch ( $params['wh_sort'] ) {
				case 'booking_id__asc':
					$order_by = 'booking_id ASC ';
					break;
				case 'booking_id__desc':
					$order_by = 'booking_id DESC ';
					break;
				case 'dates__asc':
					$order_by = 'sort_date ASC ';
					break;
				case 'dates__desc':
					$order_by = 'sort_date DESC ';
					break;
				case 'resource__asc':
					$order_by = 'booking_type ASC ';
					break;
				case 'resource__desc':
					$order_by = 'booking_type DESC ';
					break;
				case 'cost__asc':
					$order_by = 'cost ASC ';
					break;
				case 'cost__desc':
					$order_by = 'cost DESC ';
					break;
			    default:
					$order_by = 'booking_id ASC ';
			}
			$sql['order'] = " ORDER BY bk." . $order_by;            // $sql['order'] = " ORDER BY bk." . esc_sql( $params['sort'] ) . ( ( 'DESC' == $params['sort_type'] ) ? " DESC " : " ASC " );

			$sql_args_count = $sql_args;		                    // For SELECT COUNT(*) as count we do not need other parameters

			$sql['limit'] = " LIMIT %d, %d ";

			$sql_args[] = ( $params['page_num'] - 1 ) * $params['page_items_count'];
			$sql_args[] = $params['page_items_count'];



			/**
			 * Good Practice: https://blog.ircmaxell.com/2017/10/disclosure-wordpress-wpdb-sql-injection-technical.html
			 * fixed in WordPress 4.8.3
			 *
				$where = "WHERE foo = %s";
				$args = [$_GET['data']];
				$args[] = 1;
				$args[] = 2;
				$query = $wpdb->prepare("SELECT * FROM something $where LIMIT %d, %d", $args);
			 *
			 */

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//      SELECT      at this specific PAGE  /////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		    $sql_prepared = $wpdb->prepare(
											  $sql['start_select']
											. $sql['from']
											. $sql['where']
											. $sql['order']
											. $sql['limit']
										, $sql_args
		                        );

		    $bookings_sql_obj = $wpdb->get_results($sql_prepared);


			////////////////////////////////////////////////////////////////////////////////////////////////////////////
		    //    COUNT     of items with this WHERE ///////////////////////////////////////////////////////////////////
			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$sql_for_listing_count =  $sql['start_count']
									. $sql['from']
									. $sql['where'];

			if ( false === strpos(  $sql_for_listing_count, '%' ) ) {

				$sql_prepared = $sql_for_listing_count;

			} else {
				$sql_prepared = $wpdb->prepare(
											  $sql_for_listing_count
											, $sql_args_count
									);
			}
		    $bookings_count = $wpdb->get_results( $sql_prepared );
		    $bookings_count = ( ( count( $bookings_count ) > 0 ) ? $bookings_count[0]->count : 0 );

			return array(
				  'count'    => $bookings_count
				, 'bookings' => $bookings_sql_obj
			);
		}

			//    W H E R E     C O N D I T I O N S

			/**
				 * Get SQL   W H E R E   conditions for   D a t e s   of  bookings
			 *
			 * @param string $wh_booking_date     - Parameter from Booking Listing request (usually  its number)
			 * @param string $wh_booking_date2    - Parameter from Booking Listing request (usually  its number)
			 * @param string $pref                - Optional. Prefix for table.
			 * @return string - WHERE conditions for SQL
			 */
			function wpbc_ajx__sql_where_for_dates( $wh_booking_date, $wh_booking_date2, $pref = 'dt.' ) {

				$wh_booking_date  = (string) $wh_booking_date;
				$wh_booking_date2 = (string) $wh_booking_date2;

			    $sql_where= '';
			    if ($pref == 'dt.')  { $and_pre = ' AND '; $and_suf = ''; }
			    else                 { $and_pre = ''; $and_suf = ' AND '; }

			                                                                                // Actual
			    if (  ( ( $wh_booking_date  === '' ) && ( $wh_booking_date2  === '' ) ) || ($wh_booking_date  === '0') ) {
			        $sql_where =               $and_pre."( ".$pref."booking_date >= ( CURDATE() - INTERVAL '00:00:01' HOUR_SECOND ) ) ".$and_suf ;      //FixIn: 8.5.2.14

			    } else  if ($wh_booking_date  === '1') {                                    // Today								//FixIn: 7.1.2.8
			        $sql_where  =               $and_pre."( ".$pref."booking_date <= ( CURDATE() + INTERVAL '23:59:59' HOUR_SECOND ) ) ".$and_suf ;
			        $sql_where .=               $and_pre."( ".$pref."booking_date >= ( CURDATE() - INTERVAL '00:00:01' HOUR_SECOND ) ) ".$and_suf ;     //FixIn: 8.4.7.21


			    } else if ($wh_booking_date  === '2') {                                     // Previous
			        $sql_where =               $and_pre."( ".$pref."booking_date <= ( CURDATE() - INTERVAL '00:00:01' HOUR_SECOND ) ) ".$and_suf ;      //FixIn: 8.5.2.16

			    } else if ($wh_booking_date  === '3') {                                     // All
			        $sql_where =  '';

			    } else if ($wh_booking_date  === '4') {                                     // Next
			        $sql_where  =               $and_pre."( ".$pref."booking_date <= ( CURDATE() + INTERVAL ". $wh_booking_date2 . " DAY ) ) ".$and_suf ;
			        // $sql_where .=               $and_pre."( ".$pref."booking_date >= ( CURDATE() - INTERVAL 1 DAY ) ) ".$and_suf ;
				    $sql_where .=               $and_pre."( ".$pref."booking_date > ( CURDATE() ) ) ".$and_suf ;                    //FixIn: 8.0.1.1

			    } else if ($wh_booking_date  === '5') {                                     // Prior
			        $wh_booking_date2 = str_replace('-', '', $wh_booking_date2);
			        $sql_where  =               $and_pre."( ".$pref."booking_date >= ( CURDATE() - INTERVAL ". $wh_booking_date2 . " DAY ) ) ".$and_suf ;
			        $sql_where .=               $and_pre."( ".$pref."booking_date <= ( CURDATE() + INTERVAL 1 DAY ) ) ".$and_suf ;

			    } else  if ($wh_booking_date  === '7') {                                    // Check In date - Today/Tomorrow
			          // $sql_where  =               $and_pre."( ".$pref."booking_date <= ( CURDATE() + INTERVAL '23:59:59' HOUR_SECOND ) ) ".$and_suf ;
			          // $sql_where .=               $and_pre."( ".$pref."booking_date >= ( CURDATE() ) ) ".$and_suf ;
			          $sql_where  =               $and_pre."( ".$pref."booking_date <= ( CURDATE() + INTERVAL '1 23:59:59' DAY_SECOND ) ) ".$and_suf ;
			          $sql_where .=               $and_pre."( ".$pref."booking_date >= ( CURDATE() + INTERVAL 1 DAY ) ) ".$and_suf ;

			    } else  if ($wh_booking_date  === '8') {                                    // Check Out date - Tomorrow
			        $sql_where  =               $and_pre."( ".$pref."booking_date <= ( CURDATE() + INTERVAL '1 23:59:59' DAY_SECOND ) ) ".$and_suf ;
			        $sql_where .=               $and_pre."( ".$pref."booking_date >= ( CURDATE() + INTERVAL 1 DAY ) ) ".$and_suf ;

			    } else if ( ( $wh_booking_date === '9' ) || ( $wh_booking_date === '10' ) || ( $wh_booking_date === '11' ) ) {  // Today check in/out
			        $sql_where  =               $and_pre."( ".$pref."booking_date <= ( CURDATE() + INTERVAL 1 DAY ) ) ".$and_suf ;
			        $sql_where .=               $and_pre."( ".$pref."booking_date >= ( CURDATE() - INTERVAL 1 DAY ) ) ".$and_suf ;

			    } else {                                                                    // Fixed

				    $wh_booking_date  = wpbc_sanitize_date( $wh_booking_date );
				    $wh_booking_date2 = wpbc_sanitize_date( $wh_booking_date2 );

					/*
			        if ( $wh_booking_date  !== '' ){
				        $sql_where .=   $and_pre . "( " . $pref . "booking_date >= ( '" . $wh_booking_date . "' - INTERVAL '00:00:01' HOUR_SECOND ) ) " . $and_suf;
			        }
				    if ( $wh_booking_date2 !== '' ) {
					    $sql_where .= $and_pre . "( " . $pref . "booking_date <= ( '" . $wh_booking_date2 . "' + INTERVAL '23:59:59' HOUR_SECOND ) ) " . $and_suf;
				    }
					*/

				    if ( $wh_booking_date !== '' ) {
					    if ( strpos( $wh_booking_date, ':' ) === false ) {
						    $sql_where .= $and_pre . "( " . $pref . "booking_date >= '" . $wh_booking_date . " 00:00:00' ) " . $and_suf;
					    } else {
						    $sql_where .= $and_pre . "( " . $pref . "booking_date >= '" . $wh_booking_date . "' ) " . $and_suf;
					    }
				    }

				    if ( $wh_booking_date2 !== '' ) {
					    if ( strpos( $wh_booking_date2, ':' ) === false ) {
						    $sql_where .= $and_pre . "( " . $pref . "booking_date <= '" . $wh_booking_date2 . " 23:59:59' ) " . $and_suf;
					    } else {
						    $sql_where .= $and_pre . "( " . $pref . "booking_date <= '" . $wh_booking_date2 . "' ) " . $and_suf;
					    }
				    }


			    }

			    return $sql_where;
			}


			/**
				 * Get SQL   W H E R E   conditions for   M o d i f i c a t i o n    D a t e   of  bookings
			 *
			 * @param string $wh_modification_date    - Parameter from Booking Listing request (usually  its number)
			 * @param string $wh_modification_date2   - Parameter from Booking Listing request (usually  its number)
			 * @param string $pref                  - Optional. Prefix for table.
			 * @return string - WHERE conditions for SQL
			 */
			function wpbc_ajx__sql_where_for_modification_date( $wh_modification_date, $wh_modification_date2, $pref = 'bk.' ) {

			    $sql_where = '';

			    if ($pref == 'bk.')  { $and_pre = ' AND '; $and_suf = ''; }
			    else                 { $and_pre = ''; $and_suf = ' AND '; }

			    if ($wh_modification_date  === '1') {                                       // Today
			        $sql_where  =               $and_pre."( ".$pref."modification_date <= ( CURDATE() + INTERVAL '23:59:59' HOUR_SECOND ) ) ".$and_suf ;    //FixIn: 8.4.7.22
			        $sql_where .=               $and_pre."( ".$pref."modification_date >= ( CURDATE() - INTERVAL '00:00:01' HOUR_SECOND ) ) ".$and_suf ;    //FixIn: 8.4.7.22

			    } else if ($wh_modification_date  === '3') {                                // All
			        $sql_where =  '';

			    } else if ($wh_modification_date  === '5') {                                // Prior
			        $wh_modification_date2 = str_replace('-', '', $wh_modification_date2);
			        $sql_where  =               $and_pre."( ".$pref."modification_date >= ( CURDATE() - INTERVAL ". $wh_modification_date2 . " DAY ) ) ".$and_suf ;
			        $sql_where .=               $and_pre."( ".$pref."modification_date <= ( CURDATE() + INTERVAL 1 DAY ) ) ".$and_suf ;

			    } else {                                                                    // Fixed

				    $wh_modification_date  = wpbc_sanitize_date( $wh_modification_date );       //FixIn: 9.4.4.1
				    $wh_modification_date2 = wpbc_sanitize_date( $wh_modification_date2 );

			        if ( $wh_modification_date  !== '' )
			            $sql_where.=               $and_pre."( ".$pref."modification_date >= ( '" . $wh_modification_date  . "' - INTERVAL '00:00:01' HOUR_SECOND ) ) ".$and_suf;

			        if ( $wh_modification_date2  !== '' )
			            $sql_where.=               $and_pre."( ".$pref."modification_date <= ( '" . $wh_modification_date2 . "' + INTERVAL '23:59:59' HOUR_SECOND ) ) ".$and_suf;
			    }

			    return $sql_where;
			}


			/**
			 * Get SQL  W H E R E  conditions for   B o o k i n g   R e s o u r c e s
			 *
			 * @param $wh_booking_type
			 * @param $wh_approved
			 * @param $wh_booking_date
			 * @param $wh_booking_date2
			 *
			 * @return string   - SQL
			 */
	        function wpbc_ajx__sql_where_for_resources( $wh_booking_type, $wh_approved, $wh_booking_date, $wh_booking_date2 ){
				global $wpdb;

		        if ( ! class_exists( 'wpdev_bk_personal' ) ) {
			        return '';
		        }

		        if ( is_array( $wh_booking_type ) ) {
			        $wh_booking_type = implode( ',', $wh_booking_type );
		        }

		        $sql_where = '';

		        if ( '0' === $wh_booking_type ) {   // All  booking resources
			        // Get all  booking resources of this user
			        $resources_sql_arr = wpbc_ajx_get_all_booking_resources_arr();
			        $wh_booking_type   = implode( ',', array_keys( $resources_sql_arr ) );
			        //$resources_arr     = wpbc_ajx_arrange_booking_resources_arr( $resources_sql_arr );
		        }

				if ( '-1' === $wh_booking_type ) {   // Lost  booking resources

					$is_show_lost = true;

					if ( class_exists( 'wpdev_bk_multiuser' ) ) {     // MultiUser - Only  for super booking admin  user

						$user_bk_id = ( isset( $_REQUEST['wpbc_ajx_user_id'] ) ) ? intval( $_REQUEST['wpbc_ajx_user_id'] ) : wpbc_get_current_user_id();

						$is_user_super_admin = apply_bk_filter( 'is_user_super_admin', $user_bk_id );

						if ( ! $is_user_super_admin ) {
							$is_show_lost = false;       // For regular user show all bookings from  the booking resources that  belong to this user.
							$sql_where .= " AND bk.booking_type IN ( SELECT DISTINCT booking_type_id FROM {$wpdb->prefix}bookingtypes WHERE users = " . $user_bk_id . " ) ";
						}
					}

					// "Lost" bookings in deleted booking resources
					if ( $is_show_lost ) {
						$sql_where .= " AND bk.booking_type NOT IN ( SELECT DISTINCT booking_type_id FROM {$wpdb->prefix}bookingtypes ) ";
					}

				} else  if ( ! empty( $wh_booking_type ) ) {
			        // P
			        $sql_where .= " AND (  ";
			        $sql_where .= "       ( bk.booking_type IN  ( " . $wh_booking_type . " ) ) ";

			        //  BL  - Show bookings from  child booking resources,  if was selected only  parent booking resource
					$is_show_bookings_for_child_resources = true;
					if ( $is_show_bookings_for_child_resources ) {
						$sql_where .= wpbc_ajx__sql_where_for_resources_bl( $wh_booking_type, $wh_approved, $wh_booking_date, $wh_booking_date2 );
					}

			        // P
			        $sql_where .= "     )  ";

					// MU   - Check  if searching bookings are belongs to specific user in  Booking Calendar MultiUser version
					$sql_where = apply_bk_filter( 'update_where_sql_for_getting_bookings_in_multiuser', $sql_where );
		        }

		        return $sql_where;
	        }


			/**
			 * Get SQL  W H E R E  conditions for   B o o k i n g   R e s o u r c e s   BL - Capacity
			 *
			 * @param $wh_booking_type
			 * @param $wh_approved
			 * @param $wh_booking_date
			 * @param $wh_booking_date2
			 *
			 * @return string   - SQL
			 */
			function wpbc_ajx__sql_where_for_resources_bl( $wh_booking_type, $wh_approved, $wh_booking_date, $wh_booking_date2 ){

				if ( ! class_exists( 'wpdev_bk_biz_l' ) ) {
					return '';
				}

			        global $wpdb;
			        $sql_where = '';

			        // BL                                                               // Childs in dif sub resources
			        $sql_where.=   "        OR ( bk.booking_id IN (
			                                                 SELECT DISTINCT booking_id
			                                                 FROM {$wpdb->prefix}bookingdates as dtt
			                                                 WHERE  " ;
			        if ($wh_approved !== '')
			                                $sql_where.=                  " dtt.approved = $wh_approved  AND " ;
					$sql_where .= wpbc_ajx__sql_where_for_dates( $wh_booking_date, $wh_booking_date2, 'dtt.' );

			        $sql_where.=                                          "   (
			                                                                dtt.type_id IN ( ". $wh_booking_type ." )
			                                                                OR  dtt.type_id IN (
			                                                                                     SELECT booking_type_id
			                                                                                     FROM {$wpdb->prefix}bookingtypes as bt
			                                                                                     WHERE  bt.parent IN ( ". $wh_booking_type ." )
			                                                                                    )
			                                                             )
			                                                 )
			                              ) " ;

					if ( ( isset($_REQUEST['view_mode']) ) && ( $_REQUEST['view_mode']== 'vm_calendar' ) ) {

						// Skip the bookings from the children  resources, if we are in the Calendar view mode at the admin panel

					} else {
						// BL       // Just children booking resources
						$sql_where .= "         OR ( bk.booking_type IN (
			                                                     SELECT booking_type_id
			                                                     FROM {$wpdb->prefix}bookingtypes as bt
			                                                     WHERE  bt.parent IN ( " . $wh_booking_type . " )
			                                                    )
			                              )";
					}

			    return $sql_where;
			}


			/**
			 *  Get SQL  W H E R E  conditions for      P a y m e n t    S t a t u s
			 *
			 * @param $wh_pay_status        [ 'any ' ]  |  ['group_ok']  |  ['group_unknown' ]  |  ['group_pending']  |  ['group_failed']  |  [ 'user_entered',  'myCustom status' ]
			 *
			 * @return array  [ string SQL,  array ARGS ]
			 */
	        function wpbc_ajx__sql_where_for_payment_status( $wh_pay_status_arr ){

				// [ 'any ' ]  |  ['group_ok']  |  ['group_unknown' ]  |  ['group_pending']  |  ['group_failed']  |  [ 'user_entered',  'myCustom status' ]

		        $wh_pay_status_custom = '';
		        $wh_pay_status        = '';

		        if ( ! empty( $wh_pay_status_arr ) ) {

			        if ( count( $wh_pay_status_arr ) > 1 ) {
				        $wh_pay_status_custom = $wh_pay_status_arr[1];
			        }
			        $wh_pay_status = $wh_pay_status_arr[0];
		        }

				$sql_where = '';
				$sql_args = array();

		        if ( ( '' != $wh_pay_status ) && ( 'all' != $wh_pay_status ) ) {

			        $sql_where .= " AND ( ";

			        // Check  firstly if we are selected some goup of payment status
			        if ( $wh_pay_status == 'group_ok' ) {                // SUCCESS

				        $payment_status = wpbc_get_payment_status_ok();

				        foreach ( $payment_status as $label ) {
					        $sql_where .= " ( bk.pay_status = '" . $label . "' ) OR";
				        }
				        $sql_where = substr( $sql_where, 0, - 2 );

			        } else if ( ( $wh_pay_status == 'group_unknown' ) || ( is_numeric( $wh_pay_status_custom ) )  ) {     // UNKNOWN

				        $payment_status = wpbc_get_payment_status_unknown();
				        foreach ( $payment_status as $label ) {
					        $sql_where .= " ( bk.pay_status = '" . $label . "' ) OR";
				        }
				        //$sql_where = substr($sql_where, 0, -2);
				        $sql_where .= " ( bk.pay_status = '' ) OR ( bk.pay_status regexp '^[0-9]') ";

			        } else if ( $wh_pay_status == 'group_pending' ) {     // Pending

				        $payment_status = wpbc_get_payment_status_pending();
				        foreach ( $payment_status as $label ) {
					        $sql_where .= " ( bk.pay_status = '" . $label . "' ) OR";
				        }
				        $sql_where = substr( $sql_where, 0, - 2 );

			        } else if ( $wh_pay_status == 'group_failed' ) {     // Failed

				        $payment_status = wpbc_get_payment_status_error();
				        foreach ( $payment_status as $label ) {
					        $sql_where .= " ( bk.pay_status = '" . $label . "' ) OR";
				        }
				        $sql_where = substr( $sql_where, 0, - 2 );

			        } else {                                                        // CUSTOM Payment Status
				        $sql_where .= " bk.pay_status = %s ";

						// $wh_pay_status_custom = htmlspecialchars_decode( $wh_pay_status_custom );       // ? Convert special HTML entities back to characters:  "&#60; - &#62;" to "< - >"
						$sql_args[] = $wh_pay_status_custom;
			        }

			        $sql_where .= " ) ";
		        }

		        return array( $sql_where,  $sql_args );
	        }


			/**
			 *  Get SQL  W H E R E  conditions for      C o s t    Min - Max
			 *
			 * @param $wh_cost_min  '' or number
			 * @param $wh_cost_max  '' or number
			 *
			 * @return array  [ string SQL,  array ARGS ]
			 */
			function wpbc_ajx__sql_where_cost_min_max( $wh_cost_min, $wh_cost_max ) {

				$sql_where = '';
				$sql_args  = array();

				if ( $wh_cost_min !== '' ) {
					$sql_where .= " AND (  bk.cost >= %f ) ";
					$sql_args[] = $wh_cost_min;
				}
				if ( $wh_cost_max !== '' ) {
					$sql_where .= " AND (  bk.cost <= %f ) ";
					$sql_args[] = $wh_cost_max;
				}

				return array( $sql_where,  $sql_args );

			}


		/**
		 * D A T E S  -  Get array of "Booking Dates" objects from DB
		 *  relative to specific bookings - array of Bookings objects from DB
		 *
		 * @param array of object $bookings_sql_obj
		 *
		 * @return array of booking dates sql objects
		 */
		function wpbc_ajx_get__booking_dates_obj__sql( $bookings_sql_obj ){

			global $wpdb;
			$db_names = wpbc_get_db_names();

            // Get list of booking ID  from bookings obj ///////////////////////////////////////////////////////////////
			$booking_id_list = array();
			foreach ( $bookings_sql_obj as $booking ) {
				$booking_id_list[] = intval( $booking->booking_id );
			}
			$booking_id_list = array_unique( $booking_id_list );        // remove duplicates
			$booking_id_list = implode( ",", $booking_id_list );
			////////////////////////////////////////////////////////////////////////////////////////////////////////////


			if ( ! empty( $booking_id_list ) ) {                                       // Get Dates for all our Bookings

				$sql = "SELECT *
	                	FROM {$db_names['dates']} as dt
	                	WHERE dt.booking_id in ( {$booking_id_list} ) ";

				if ( class_exists( 'wpdev_bk_biz_l' ) ) {
					$sql .= " ORDER BY booking_id, type_id, booking_date ";
				} else {
					$sql .= " ORDER BY booking_id, booking_date ";
				}

				$booking_dates_sql_obj = $wpdb->get_results( $sql );
			} else {
				$booking_dates_sql_obj = array();
			}

			return $booking_dates_sql_obj;
		}


		/**
		 *   S Q L  -   B O O K I N G S  -  Get array of "Bookings" objects from DB
		 *  based on request params
		 *
		 * @param array $request_params
		 * @param array of object $bookings_sql_obj
		 *
		 * @return array of array( booking dates,  $bookings_count)         array( sql objects, int )
		 */
		function wpbc_ajx_get__remove_bookings__where_dates_outside($request_params, $bookings_obj, $bookings_count){

			$is_id = strpos( trim( strtolower( $request_params['keyword'] ) ) , 'id:' );
			if ( false !== $is_id ) {
				return array( $bookings_obj, $bookings_count );
			}
			// W H E R E :    D A T E S --------------------------------------------------------------------------------
			if (1){
				if ( is_array( $request_params['wh_booking_date'] ) ) {
					$wh_booking_date_arr = array_replace( array( '', '' ), $request_params['wh_booking_date'] );                // Always have minimum 2 values in arr
				} else {
					$wh_booking_date_arr = array( '', '' );
				}

				if ( count( $wh_booking_date_arr ) > 2 ) {
					$removed_el = array_shift( $wh_booking_date_arr );                                                          // Remove an element off the beginning of array
				}
			}

			$wh_booking_date = (string) $wh_booking_date_arr[0];
			$wh_booking_date2 =(string) $wh_booking_date_arr[1];

			// Check In date - Tomorrow
			if ( $wh_booking_date === '7' ) {

				$today_mysql_format = date_i18n( 'Y-m-d', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) + DAY_IN_SECONDS ); // Tommorow day with gmt offset
				foreach ( $bookings_obj as $bc_id => $bc_value ) {
					$check_in_date = $bc_value->short_dates[0];
					$check_in_date = explode( ' ', $check_in_date );
					$check_in_date = $check_in_date[0]; // 2014-02-25
					if ( $today_mysql_format != $check_in_date ) {
						unset( $bookings_obj[ $bc_id ] );
						$bookings_count--;
					}
				}
			}

			// Check Out date - Tomorrow
			if ( $wh_booking_date === '8' ) {

				$tomorrow_mysql_format = date_i18n( 'Y-m-d', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) + DAY_IN_SECONDS ); // Tommorow day with gmt offset
				foreach ( $bookings_obj as $bc_id => $bc_value ) {
					if ( 1 == count( $bc_value->short_dates ) ) {
						$check_out_date = $bc_value->short_dates[0];
					} else {
						$check_out_date = $bc_value->short_dates[2];
					}
					$check_out_date = explode( ' ', $check_out_date );
					$check_out_date = $check_out_date[0];                               // 2014-02-25
					if ( $tomorrow_mysql_format != $check_out_date ) {
						unset( $bookings_obj[ $bc_id ] );
						$bookings_count --;
					}
				}
			}

			//  Today == check in/out      |   Today = Check  in           |   Today = Check out
			if ( ( $wh_booking_date === '9' ) || ( $wh_booking_date === '10' ) || ( $wh_booking_date === '11' ) ) {

				$today_mysql_format = date_i18n( 'Y-m-d', time() + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) + 0 * DAY_IN_SECONDS ); // Today day with gmt offset

				foreach ( $bookings_obj as $bc_id => $bc_value ) {

					$check_in_date = $bc_value->short_dates[0];
					$check_in_date = explode( ' ', $check_in_date );
					$check_in_date = $check_in_date[0];                                 // 2014-02-25

					if ( count( $bc_value->short_dates ) == 1 ) {
						$check_out_date = $bc_value->short_dates[0];
					} else {
						$check_out_date = $bc_value->short_dates[2];
					}
					$check_out_date = explode( ' ', $check_out_date );
					$check_out_date = $check_out_date[0];                               // 2014-02-25

					// Check In
					if ( $wh_booking_date === '10' ) {
						if ( $today_mysql_format != $check_in_date ) {
							unset( $bookings_obj[ $bc_id ] );
							$bookings_count --;
						}
					}
					// Check  out
					if ( $wh_booking_date === '11' ) {
						if ( $today_mysql_format != $check_out_date ) {
							unset( $bookings_obj[ $bc_id ] );
							$bookings_count --;
						}
					}
					// Check In | Check  out            //FixIn: 9.6.3.12
					if ( $wh_booking_date === '9' ) {
						if ( ( $today_mysql_format != $check_in_date ) && ( $today_mysql_format != $check_out_date ) ) {
							unset( $bookings_obj[ $bc_id ] );
							$bookings_count --;
						}
					}

				}
			}

			return array( $bookings_obj, $bookings_count );
		}

		/**
		 *  Get array of bookings with dates (wide and short dates view)
		 *  after inserting  dates into  the bookings
		 *
		 * @param $bookings_obj                     Array (
														    [0] => stdClass Object (
														            [booking_id] => 182
														            [trash] => 0
														            [sync_gid] =>
														            [is_new] => 1
														            [status] =>
														            [sort_date] => 2023-01-23 10:00:01
														            [modification_date] => 2022-04-18 12:23:30
														            [form] => select-one^rangetime2^10:00 - 12:00~text^name2^rr~text^secondname2^hjk~email^email2^hyui@nbco.csdf~text^phone2^h~text^address2^khj~text^city2^e~text^postcode2^hj~select-one^country2^HT~select-one^visitors2^1~select-one^children2^0~textarea^details2^djkh~checkbox^term_and_condition2[]^I Accept term and conditions
														            [hash] => a1ae510d8fee961b7f8ae53101632151
														            [booking_type] => 2
														            [remark] =>
														            [cost] => 75.00
														            [pay_status] => 165028101080.84
														            [pay_request] => 0
														        )
														    [1] => stdClass Object, ....
		 * @param $booking_dates_obj                Array (
														    [0] => stdClass Object (
														            [booking_dates_id] => 294
														            [booking_id] => 147
														            [booking_date] => 2022-06-27 12:00:01
														            [approved] => 0
														            [type_id] =>
														        )
														    [1] => stdClass Object, ...
		 *
		 * @return array                            Array (
													    * [182] => stdClass Object (
		                                                        *                       ... many  other props ....
													            * [id] => 182
													            * [approved] => 0
													            * [dates] => Array (
													                    * [0] => 2023-01-23 10:00:01
													                    * [1] => 2023-01-25 00:00:00
													                    * [2] => 2023-01-27 12:00:02
													                * )
													            * [child_id] => Array (
													                    * [0] =>
													                    * [1] =>
													                    * [2] =>
													                * )
													            * [short_dates] => Array (
													                    * [0] => 2023-01-23 10:00:01
													                    * [1] => ,
													                    * [2] => 2023-01-25 00:00:00
													                    * [3] => ,
													                    * [4] => 2023-01-27 12:00:02
													                * )
													            * [short_dates_child_id] => Array (
													                    * [0] =>
													                    * [1] =>
													                    * [2] =>
													                    * [3] =>
													                    * [4] =>
													                * )
													        * )
													    * [181] => stdClass Object (
													    * ....
		 */
		function wpbc_ajx_include_bookingdates_in_bookings( $bookings_obj, $booking_dates_obj ){

			$bookings_arr = array();
			foreach ( $bookings_obj as $booking ) {

		        $bookings_arr[$booking->booking_id] = new StdClass;
				$bookings_arr[$booking->booking_id]->booking_db = $booking;
				$bookings_arr[$booking->booking_id]->id = $booking->booking_id;
				$bookings_arr[$booking->booking_id]->approved = 0;
		        $bookings_arr[$booking->booking_id]->dates = array();
		        $bookings_arr[$booking->booking_id]->child_id = array();
				/*
		        $resource_id = (isset( $booking->booking_type )) ? $booking->booking_type : '1';

				// booking Form parse can  be here


				        if ( ( isset( $booking->sync_gid ) ) && (!empty( $booking->sync_gid )) ) {
				            $booking->form .= "~text^sync_gid{$booking->booking_type}^{$booking->sync_gid}";
				        }

				        $cont = wpbc__legacy__get_form_content_arr(   $booking->form
				                                    , $resource_id
				                                    , ''
				                                    , array(
				                                            'booking_id'     => $booking->booking_id
				                                          , 'resource_title' => (isset( $booking_types[$booking->booking_type] )) ? $booking_types[$booking->booking_type] : ''
				                                        )
				                                );
				        $search  = array( "'(<br[ ]?[/]?>)+'si", "'(<[/]?p[ ]?>)+'si" );
				        $replace = array( "&nbsp;&nbsp;", " &nbsp; ", " &nbsp; " );
				        $cont['content'] = preg_replace( $search, $replace, $cont['content'] );
						//debuge( htmlentities(  $cont['content'] ) );die;
				        $bookings_arr[$booking->booking_id]->form_show = $cont['content'];
				        unset( $cont['content'] );
				        $bookings_arr[$booking->booking_id]->form_data = $cont;

				 /**/
			}

			// Wide dates in bookings
			foreach ( $booking_dates_obj as $date ) {
				$bookings_arr[ $date->booking_id ]->approved = $date->approved;

			    $bookings_arr[ $date->booking_id ]->dates[]    = $date->booking_date;
			    $bookings_arr[ $date->booking_id ]->child_id[] = ( isset( $date->type_id ) ) ? $date->type_id : '';
			}

			// Short dates
			foreach ( $bookings_arr as $booking_id => $booking ) {

				if ( count( $booking->dates ) == 0 ) { continue; }      // If no dates,  then skip

				$bookings_arr[ $booking_id ]->short_dates          = array( $booking->dates[0] );                       // First Day
				$bookings_arr[ $booking_id ]->short_dates_child_id = array( $bookings_arr[ $booking_id ]->child_id[0] );
				$previous = array(
								'date'       => $booking->dates[0],
								'date_index' => 0,
								'separator'  => false
							);

				if ( count( $booking->dates ) == 1 ) { continue; }      // if 1 date,  then skip


				for ( $date_indx = 1; $date_indx < ( count( $booking->dates ) ); $date_indx ++ ) {                      // Start from 2nd day

					$date = $booking->dates[ $date_indx ];

					// if ( wpbc_is_next_day( $date, $previous['date'] ) ) {
					$next_day_if__check_in__then__check_out = true;
					if ( wpbc_is_less_than_next_day( $date, $previous['date'], $next_day_if__check_in__then__check_out ) ) {

						if ( '-' != $previous['separator'] ) {
							$bookings_arr[ $booking_id ]->short_dates[]          = '-';
							$bookings_arr[ $booking_id ]->short_dates_child_id[] = '';
						}
						$previous['separator'] = '-';

					} else {

						if ( '-' == $previous['separator'] ) {

							$bookings_arr[ $booking_id ]->short_dates[]          = $previous['date'];
							$bookings_arr[ $booking_id ]->short_dates_child_id[] = $bookings_arr[ $booking_id ]->child_id[ $previous['date_index'] ];
						}
						$bookings_arr[ $booking_id ]->short_dates[]          = ',';
						$bookings_arr[ $booking_id ]->short_dates_child_id[] = '';

						$bookings_arr[ $booking_id ]->short_dates[]          = $date;
						$bookings_arr[ $booking_id ]->short_dates_child_id[] = $bookings_arr[ $booking_id ]->child_id[ $date_indx ];

						$previous['separator'] = ',';
					}

					$previous['date']       = $date;
					$previous['date_index'] = $date_indx;

				} // dates loop: $date_indx => $date

				if ( '-' == $previous['separator'] ) {
					$bookings_arr[ $booking_id ]->short_dates[]          = $previous['date'];
					$bookings_arr[ $booking_id ]->short_dates_child_id[] = $bookings_arr[ $booking_id ]->child_id[ $previous['date_index'] ];
				}

			} // bookings loop:  $booking_id => $booking

			return $bookings_arr;
		}


		function wpbc_ajx_parse_bookings( $bookings_arr, $resources_arr ) {

			$user_id = ( isset( $_REQUEST['wpbc_ajx_user_id'] ) )  ?  intval( $_REQUEST['wpbc_ajx_user_id'] )  :  wpbc_get_current_user_id();

			foreach ( $bookings_arr as $booking_id => $booking ) {

				// Booking resource ------------------------------------------------------------------------------------
				$resource_id         = ( isset( $booking->booking_db->booking_type ) ) ? $booking->booking_db->booking_type : '1';
				$resource_title      = '';
				$resource_owner_user = $user_id;

				if ( class_exists( 'wpdev_bk_personal' ) ) {
					$resource_title = ( isset( $resources_arr[ $resource_id ] ) ) ? $resources_arr[ $resource_id ]['title'] : __( 'Resource not exist', 'booking' );
					$resource_title = apply_bk_filter( 'wpdev_check_for_active_language', $resource_title );
				}
				if ( class_exists( 'wpdev_bk_multiuser' ) ) {
					$resource_owner_user = ( isset( $resources_arr[ $resource_id ] ) ) ? $resources_arr[ $resource_id ]['users'] : $user_id;
				}

				// Parse form  fields only  from  $booking->booking_db->form  ------------------------------------------
				$booking_data_arr  = wpbc_parse_booking_data_fields(	    $booking->booking_db->form,
																		array( 	'resource_id' => $resource_id )
																  );


				foreach ( $booking_data_arr as $booking_data_arr_key => $booking_data_arr_value ) {
																// Booking Listing  escaping
					$booking_data_arr[$booking_data_arr_key] = wpbc_string__escape__then_convert__n_amp__html( $booking_data_arr_value );
				}

				// Add system keys to data fields arr, like ( 'booking_id', 'trash', 'sync_gid'... ---------------------
				$booking_data_arr  = wpbc_add_system_booking_data_fields(
																		$booking_data_arr,
																		array_merge(
																			get_object_vars( $booking->booking_db ),
																			array( 'id'                       => $booking->id,
																			       'approved'                 => $booking->approved,
																			       'wpbc_custom_booking_form' => ''             //FixIn: 9.4.3.12       //FixIn: 9.4.4.9
																			)
																		)
								                                        , array(
																			'booking_id',
																			'trash',
																			'sync_gid',
																			'is_new',
																			'status',
																			'sort_date',
																			'modification_date',
																			'creation_date',
																			'hash',
																			'booking_type',
																			'remark',
																			'cost',
																			'pay_status',
																			'pay_request',
																			'id',
																			'approved',
																			'booking_options',
																			'wpbc_custom_booking_form',                 //FixIn: 9.4.3.12
																		)
																	);

				// Set dates and times in correct format ---------------------------------------------------------------
				$booking_data_arr  = wpbc_parse_booking_data_fields_formats( $booking_data_arr );

				// Get SHORT / WIDE Dates showing data -----------------------------------------------------------------
				$short_dates_content = wpbc_get_formated_dates__short( $booking->short_dates, (boolean) $booking->approved, $booking->short_dates_child_id, $resources_arr );
				$wide_dates_content  = wpbc_get_formated_dates__wide(  $booking->dates,       (boolean) $booking->approved, $booking->child_id,             $resources_arr );

				//------------------------------------------------------------------------------------------------------
				// Payment Status
				//------------------------------------------------------------------------------------------------------
				if ( 1 ) {
					$booking_data_arr['is_paid']          = 0;         // 0 | 1
					$booking_data_arr['pay_print_status'] = '';        // "Unknown"            | "Stripe_v3:OK"
					//  $booking_data_arr['pay_status']                // "165458416073.66"    | "Stripe_v3:OK"   |  "PayPal:Ok"

					if ( class_exists( 'wpdev_bk_biz_s' ) ) {

						if ( wpbc_is_payment_status_ok( trim( $booking_data_arr['pay_status'] ) ) ) {
							$booking_data_arr['is_paid'] = 1;
						}

						$payment_status_titles = get_payment_status_titles();

						$current_payment_status_title = array_search( $booking_data_arr['pay_status'], $payment_status_titles );

						if ( $current_payment_status_title === false ) {
							$current_payment_status_title = $booking_data_arr['pay_status'];
						}

						if ( $booking_data_arr['is_paid'] ) {
							$booking_data_arr['pay_print_status'] = $booking_data_arr['pay_status'];
							if ( $current_payment_status_title == 'Completed' ) {
								$booking_data_arr['pay_print_status'] = $current_payment_status_title;
							}
						} else if ( ( is_numeric( $booking_data_arr['pay_status'] ) ) || ( $booking_data_arr['pay_status'] == '' ) ) {
							$booking_data_arr['pay_print_status'] = __( 'Unknown', 'booking' );
						} else {
							$booking_data_arr['pay_print_status'] = $current_payment_status_title;
						}
						////////////////////////////////////////////////////////////////////////////////////////////////////

						if ( $booking_data_arr['pay_print_status'] == 'Completed' ) {            //FixIn: 8.4.7.11
							$booking_data_arr['pay_print_status'] = __( 'Completed', 'booking' );
						}
						$real_payment_css  = empty( $booking_data_arr['pay_status'] ) ? $current_payment_status_title : $booking_data_arr['pay_status'];            //FixIn: 8.7.7.13
						$css_payment_label = 'wpbc_label_payment_status_' . wpbc_check_payment_status( $real_payment_css );  // 'success' | 'pending' | 'unknown' | 'error'                      //FixIn: 8.7.7.13

						if ( $booking_data_arr['is_paid'] ) {
							$css_payment_label .= ' wpbc_label_payment_status_success';
						}
						$payment_label_template = '<span class="wpbc_label wpbc_label_payment_status ' . $css_payment_label . '">'
						                          . '<span style="font-size:07px;padding: 0 1em 0 0;line-height: 2em;">'
						                          . __( 'Payment', 'booking' )
						                          . '</span> '
						                          . '<span>'
						                          . $booking_data_arr['pay_print_status']
						                          . '</span> '
						                          . '</span>';
					} else {
						$payment_label_template = '';
					}
				}

				//------------------------------------------------------------------------------------------------------
				// Currency
				//------------------------------------------------------------------------------------------------------
				if ( class_exists( 'wpdev_bk_biz_s' ) ) {
					$currency_symbol = wpbc_get_currency_symbol_for_user( $resource_id );
					$booking_data_arr['currency_symbol'] = $currency_symbol;
				}

				//------------------------------------------------------------------------------------------------------
				// Add some fields to [ 'parsed_fields' ]
				//------------------------------------------------------------------------------------------------------
				$booking_data_arr['resource_title']      = $resource_title;
				$booking_data_arr['resource_id']         = $resource_id;
				$booking_data_arr['resource_owner_user'] = $resource_owner_user;
				//$booking_data_arr['short_dates_content'] = $short_dates_content;
				//$booking_data_arr['wide_dates_content'] = $wide_dates_content;

				//------------------------------------------------------------------------------------------------------
				// Form Show    -   "Content of booking fields data" form
				//------------------------------------------------------------------------------------------------------

				$custom_booking_form_name = ( ! empty( $booking_data_arr['wpbc_custom_booking_form'] ) ) ? $booking_data_arr['wpbc_custom_booking_form'] : '';  //FixIn: 9.4.3.12
			    $form_show_template = wpbc_get_content_booking_form_show( $resource_id , $custom_booking_form_name );                                    // <strong>First Name</strong>:<span class="fieldvalue">[name]</span>&nbsp;&nbsp; ...
				$parsed_form_show   = wpbc_get_parsed_content_booking_form_show( $booking_data_arr, $form_show_template );   // <strong>First Name</strong>:<span class="fieldvalue">John</span>&nbsp;&nbsp; ...

				//------------------------------------------------------------------------------------------------------
				// Google Calendar link
				//------------------------------------------------------------------------------------------------------
				$booking_data_arr['google_calendar_link'] = wpbc_booking_do_action__get_google_calendar_link( array(
																													'form_data'   => $booking_data_arr,
																													'form_show'   => $parsed_form_show,     //strip_tags( $parsed_form_show ),
																													'dates_short' => $booking->short_dates
																											) );

				// =====================================================================================================
				$bookings_arr[ $booking_id ]->parsed_fields = $booking_data_arr;


				// =====================================================================================================
				$bookings_arr[ $booking_id ]->templates = array(
																'form_show'              => $parsed_form_show,
																'form_show_nohtml'       => strip_tags( $parsed_form_show ),
																'short_dates_content'    => $short_dates_content,
																'wide_dates_content'     => $wide_dates_content,
																'payment_label_template' => $payment_label_template
															);
			}

		    return $bookings_arr;
		}


			/**
			 * Get SHORT Dates showing data
			 *
			 * @param array $short_dates_arr - Array  of dates
			 * @param bool  $is_approved     - is dates approved or not
			 * @param type  $dates_type_id_arr
			 * @param type  $booking_resources_arr
			 *
			 * @return string
			 */
			function wpbc_get_formated_dates__short( $short_dates_arr, $is_approved = false, $dates_type_id_arr = array() , $booking_resources_arr = array() ){

				$css_class_approved  = ( $is_approved ) ? 'approved' : '';
				$short_dates_content = '';
				$date_number         = 0;

			    foreach ( $short_dates_arr as $dt ) {

			        if ( $dt == '-' ) {

			            $short_dates_content .= '<span class="date_tire"> - </span>';

					} elseif ( $dt == ',' ) {

			            $short_dates_content .= '<span class="date_tire">, </span>';

					} else {

						list( $formatted_date, $formatted_time ) = wpbc_get_date_in_correct_format( $dt );

			            $short_dates_content .= '<a href="javascript:void(0)" onclick="javascript:wpbc_ajx_click_on_dates_toggle(this);" class="wpbc_label wpbc_label_booking_dates ' . $css_class_approved . '"><span>';
						$short_dates_content .=         $formatted_date;
			            $short_dates_content .=         '<sup class="field-booking-time">' . $formatted_time . '</sup>';

							// BL
					        if (
								   ( class_exists( 'wpdev_bk_biz_l' ) )
								&& ( ! empty( $dates_type_id_arr[ $date_number ] ) )
								&& ( isset( $booking_resources_arr[ $dates_type_id_arr[ $date_number ] ] ) )
					        ){

						        $resource_title = ( isset( $booking_resources_arr[ $dates_type_id_arr[ $date_number ] ] ) )
  												         ? $booking_resources_arr[ $dates_type_id_arr[ $date_number ] ]['title']
												         : __( 'Resource not exist', 'booking' );
						        $resource_title = apply_bk_filter( 'wpdev_check_for_active_language', $resource_title );

						        if ( strlen( $resource_title ) > 19 ) {
							        $resource_title = substr( $resource_title, 0, 13 ) . '...' . substr( $resource_title, - 3 );
						        }

						        $short_dates_content .= '<sup class="field-booking-time date_from_dif_type"> ' . $resource_title . '</sup>';
					        }

			            $short_dates_content .= '</span></a>';
			        }
			        $date_number++;
			    }

			    return $short_dates_content;
			}


			/**
			 * Get Wide Dates showing data
			 *
			 * @param array $dates_arr             - array of dates in sql string format
			 * @param bool  $is_approved           - is approved (true) or pending (false)
			 * @param array $dates_type_id_arr     - array of $date->type_id from the dates SQL object
			 * @param type  $booking_resources_arr - array of booking resources objects
			 *
			 * @return string
			 */
			function wpbc_get_formated_dates__wide( $dates_arr, $is_approved = false, $dates_type_id_arr = array(), $booking_resources_arr = array() ){

				$wide_dates_arr = array();

				$css_class_approved = ( $is_approved ) ? 'approved' : '';

				foreach ( $dates_arr as $date_number => $sql_booking_date ) {

					list( $formatted_date, $formatted_time ) = wpbc_get_date_in_correct_format( $sql_booking_date );

					$wide_date  = '<a href="javascript:void(0)" onclick="javascript:wpbc_ajx_click_on_dates_toggle(this);" class="wpbc_label wpbc_label_booking_dates ' . $css_class_approved . '"><span>';
					$wide_date .=      $formatted_date;
					$wide_date .=      '<sup class="field-booking-time">' . $formatted_time . '</sup>';

						// BL
						if (
								( class_exists( 'wpdev_bk_biz_l' ) )
							 && ( '' != $dates_type_id_arr[ $date_number ] )
						     && ( isset( $booking_resources_arr[ $dates_type_id_arr[ $date_number ] ] ) )
						){
							$resource_title = ( isset( $booking_resources_arr[ $dates_type_id_arr[ $date_number ] ] ) )
                                                     ? $booking_resources_arr[ $dates_type_id_arr[ $date_number ] ]['title']
											         : __( 'Resource not exist', 'booking' );
							$resource_title = apply_bk_filter( 'wpdev_check_for_active_language', $resource_title );

							if ( strlen( $resource_title ) > 19 ) {
								$resource_title = substr( $resource_title, 0, 13 ) . '...' . substr( $resource_title, - 3 );
							}
							$wide_date .= '<sup class="field-booking-time date_from_dif_type"> ' . $resource_title . '</sup>';
						}

					$wide_date .= '</span></a>';

					$wide_dates_arr[] = $wide_date;
				}

				$wide_dates_content  = implode( '<span class="date_tire">, </span>' , $wide_dates_arr );

			    return $wide_dates_content;
			}


			/**
			 * Parse booking "data field" and get array
			 *
			 * @param string $data  - "id^2~booking_type^Standard~status^Approved~dates^2019-09-18 00:00:00 - 2019-09-20 00:00:00 , 2019-09-18 00:00:00 (Standard-1)  - 2019-09-20 00:00:00 (Standard-1)~modification_date^2019-09-05 10:50:04~cost^100.00~pay_status^156766972609.7~selected_short_timedates_hint^09/18/2019 - 09/20/2019~nights_number_hint^2~cost_hint^$75.00~name^John test~secondname^Smith~email^user@beta.com~phone^~visitors^4~children^~details^~term_and_condition^~user^Support A~wpbc_other_action^~rangetime^~other_email^~visitorsselector^~visitors_fee^~visitors_fee_hint^~trash^~remark^Approved by:John Smith (user@beta.com) [2019-09-11 09:30] Declined by:John Smith (user@beta.com) [2019-09-11 09:30]"
			 * @param array  $attr  - array( 'r_separator' => '~', 'f_separator' => '^' )
			 *
			 * @return array        - Array (
												[id] => 2
												[booking_type] => Standard
												[status] => Approved
												... )
			 */
			function wpbc_parse_booking_data_fields( $data , $attr = array() ){

				$defaults = array(
					'r_separator'   => '~' ,
					'f_separator'   => '^' ,
					'resource_id'   => '1' ,
					'system_fields' => array(
											'booking_id',
											'trash',
											//'sync_gid',
											'is_new',
											'status',
											'sort_date',
											'modification_date',
											'hash',
											'booking_type',
											'remark',
											'cost',
											'pay_status',
											'pay_request',
											'id',
											'approved'
									)
				);
				$attr   = wp_parse_args( $attr, $defaults );

				$data_arr = array();

				if ( ! empty( $data ) ) {

					$data = explode( $attr['r_separator'] ,  $data );                   // ~

					foreach ( $data as $data_rows ) {

						$data_rows = explode( $attr['f_separator'] ,  $data_rows );     // ^

						$data_field = array( 'type'  => $data_rows[0], 'name'  => $data_rows[1], 'value' => $data_rows[2] );

						// remove checkboxes suffix []
						$data_field['name'] = str_replace( '[]', '', $data_field['name'] );

						// remove booking resource ID suffix
						$data_field['name'] = substr( $data_field['name'], 0, - 1 * strlen( strval( $attr['resource_id'] ) ) );

						// System fields - adjust  fields with  same name
						if ( in_array( $data_field['name'], $attr['system_fields'] ) ) {
							$data_field['name'] .= '_data';
						}

						//Checkboxes
						if ( ( 'checkbox' == $data_field['type'] ) && ( empty( $data_field['value'] ) ) ) {
							continue;
						}
						if ( ( 'checkbox' == $data_field['type'] ) && ( ! is_array( $data_field['value'] ) ) ) {
							$data_field['value'] = str_replace( array( 'true', 'false' ), array(
																								strtolower( __( 'Yes', 'booking' ) ),
																								strtolower( __( 'No', 'booking' ) )
																), $data_field['value'] );
						}

						// Several  items with  same name - multi checkboxes
						if ( ! isset( $data_arr[ $data_field['name'] ] ) ) {
							$data_arr[ $data_field['name'] ] = $data_field['value'];
						} else {
							if ( is_array( $data_arr[ $data_field['name'] ] ) ) {
								$data_arr[ $data_field['name'] ][] = $data_field['value'];
							} else {
								$data_arr[ $data_field['name'] ] = array( $data_arr[ $data_field['name'] ], $data_field['value'] );
							}
						}

					}
				}

				foreach ( $data_arr as $key => $value ) {

					// Multi checkboxes:   [9] => checkbox^my_multi_checkbx4[]^1 [10] => checkbox^my_multi_checkbx4[]^ [11] => checkbox^my_multi_checkbx4[]^3
					if ( is_array( $value ) ) {
						$value = implode( ',', $value );
						$data_arr[$key] = $value;
					}
				}

				return $data_arr;
			}


			/**
			 * Add system fields to  Booking fields array  and get  final  array  of fields
			 * @param array $booking_data_arr       Original array of form  fields from  DB
														                                       Array (
																									    [selected_short_dates_hint] => March 5, 2023
																									    [days_number_hint] => 1
																									    [cost_hint] => &amp;#36;95
																									    [rangetime] => 10:00 AM - 12:00 PM
																									    [name] => test
																									    [email] => test@wpbookingcalendar.com
																									     ...				
																									     )

			 * @param array $booking_system_arr     array of system  fields  from  DB
																								Array ( [booking_id] => 188
																									    [trash] => 0
																									    ...
																									    [cost] => 95.00
																									    [pay_status] => 165036591118.88
																									    [pay_request] => 0
																									    [id] => 188
																									    [approved] => 0
																								)
     
			 * @param array $system_keys_arr        system fields keys that  need to  be added,  like this:
																								Array ( booking_id, trash, sync_gid, is_new, status
																								       , sort_date, modification_date, hash, booking_type
																								       , remark, cost, pay_status, pay_request, id, approved )

			 * @return array                        array of form  fields with  system  fields
			 */
			function wpbc_add_system_booking_data_fields( $booking_data_arr, $booking_system_arr, $system_keys_arr ){

				foreach ( $system_keys_arr as $key ) {

					if (
						   ( ! isset( $booking_system_arr[ $key ] ) )
						|| ( is_null( $booking_system_arr[ $key ] ) )
					) {
						$booking_system_arr[ $key ] = '';       //Some fields, like remark or booking_options can  be null,  so we define them as ''
					}

					if ( isset( $booking_system_arr[ $key ] ) ) {

						if ( ! isset( $booking_data_arr[ $key ] ) ) {
							$booking_data_arr[ $key ] = maybe_unserialize( $booking_system_arr[ $key ] );
						} else {
							$booking_data_arr[ $key . '_system' ] = maybe_unserialize( $booking_system_arr[ $key ] );
						}
					}

				}
				return $booking_data_arr;
			}


			/**
			 * Parse booking form fields format - for example: time fields in specific format
			 * @param $data_arr
			 *
			 * @return array
			 */
			function wpbc_parse_booking_data_fields_formats( $data_arr ){

			    foreach ( $data_arr as $key => $value ) {

					if ( 'rangetime' == $key ) {
						$data_arr[$key] = wpbc_time_slot_in_format(  $value );
					}

					if ( in_array( $key, array( 'starttime', 'endtime' ) ) ) {
						$data_arr[$key] = wpbc_time_in_format(  $value );
					}

					if ( in_array( $key, array(  'modification_date', 'creation_date' ) ) ) {

						//$data_arr[$key] = wpbc_get_dates_comma_string_localized(  $value );


						// This date $values in GMT date/Time format. So  we need to switch  to  WordPress locale with TIME  sum of actual  GMT date/time value + shift  of timezone from WordPress.
						$is_add_wp_timezone              = true;
						$its_for_default_datetime_format = '';
						$data_arr[ $key ] = wpbc_datetime_localized( $value, $its_for_default_datetime_format, $is_add_wp_timezone );

					}

				    if ( ( 'hash' === $key ) && ( empty( $value ) ) ) {                                                  //FixIn: 9.2.3.4   //FixIn: 9.4.3.10
						// Update booking Hash  if it was empty
						wpbc_hash__update_booking_hash( $data_arr['id'], $data_arr['booking_type'] );
						// Get new booking hash
						$hash__arr = wpbc_hash__get_booking_hash__resource_id( $data_arr['id'] );
					    if ( ! empty( $hash__arr ) ) {
						    $data_arr[ $key ] = $hash__arr[0];
					    }
					}
				}
				return $data_arr;
			}


			/**
			 * Get pure "Content of booking fields data" with shortcodes inside.
			 * it can depend on specific booking resource in Business Medium version or User in MultiUser version
			 *
			 * @param int $resource_id
			 * @param sting $custom_booking_form_name   -   use this custom  booking form,  if possible
			 *
			 * @return string
			 */
			function wpbc_get_content_booking_form_show( $resource_id , $custom_booking_form_name = '' ){               //FixIn: 9.4.3.12

		        if ( $resource_id == -1 ) {
		            if ( function_exists('get__default_type') )
		                $resource_id = get__default_type();
		            else
		                $resource_id = 1;
		        }

	            if ( ! class_exists('wpdev_bk_personal') ) {

	                $booking_form_show  = wpbc_get_free_booking_show_form();

	            } else {

	                $booking_form_show  = get_bk_option( 'booking_form_show' );
					$booking_form_show = wpbc_bf__replace_custom_html_shortcodes( $booking_form_show );

	                if ( class_exists('wpdev_bk_biz_m') ) {

		                if ( ! empty( $custom_booking_form_name ) ) {       //FixIn: 9.4.3.12

							$booking_form_show = apply_bk_filter( 'wpdev_get_booking_form_content', $booking_form_show, $custom_booking_form_name );
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
	            $booking_form_show =  apply_bk_filter('wpdev_check_for_active_language', $booking_form_show );

				$search  = array( "'(<br[ ]?[/]?>)+'si", "'(<[/]?p[ ]?>)+'si" );
		        $replace = array( "&nbsp;&nbsp;", " &nbsp; ", " &nbsp; " );
		        $booking_form_show = preg_replace( $search, $replace, $booking_form_show );

				return $booking_form_show;
			}


			/**
			 * Get parsed "Content of booking fields data" -  Shortcodes replaced by  Values
			 *
			 * @param array $booking_data_arr
			 * @param string $booking_form_show
			 *
			 * @return string
			 */
			function wpbc_get_parsed_content_booking_form_show( $booking_data_arr, $booking_form_show ){

				foreach ( $booking_data_arr as $key_param => $value_param ) {
		            if (   ( gettype ( $value_param ) != 'array' )
		                && ( gettype ( $value_param ) != 'object' )
		                ) {
		                $booking_form_show = str_replace( '['. $key_param .']', $value_param ,$booking_form_show);
		            }
		        }
		        // Remove all shortcodes, which have not replaced early.
		        $booking_form_show = preg_replace ('/[\s]{0,}\[[a-zA-Z0-9.,-_]{0,}\][\s]{0,}/', '', $booking_form_show);

				$booking_form_show = str_replace( "&amp;", '&', $booking_form_show );

				return $booking_form_show;
			}

// </editor-fold>


//                                                                              <editor-fold   defaultstate="collapsed"   desc=" F u n c t i o n s    f o r    C l e a n i ng s " >

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Escaping
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Escaping for inline  JavaScript.
	 *      Based on  WordPress esc_js()
	 *      plus removing \n in the code
	 *      and back converting escaped single quotes from \' to '
	 *
	 * This function has to be used in the code marked with double quote symbols, not single.
	 * Example: onclick="javascript:<?php echo wpbc_esc_js( $item_params['action'] ); ?>"
	 *
	 * @param $text
	 *
	 * @return string
	 */
	function wpbc_esc_js( $text ) {

		$text = str_replace( "\n", '', $text );                 // removing \n in the code

			//$safe_text = esc_js( $text );                           // js

		// This code get from esc_js()  and modified for correct working with (' symbols) in translations
			$safe_text = wp_check_invalid_utf8( $text );
			$safe_text = _wp_specialchars( $safe_text, ENT_COMPAT );
			$safe_text = preg_replace( '/&#(x)?0*(?(1)27|39);?/i', "\'", stripslashes( $safe_text ) );
			$safe_text = str_replace( "\r", '', $safe_text );
			$safe_text = str_replace( "\n", '\\n', addslashes( $safe_text ) );

		$safe_text = str_replace( "\'", "'", $safe_text );      // back converting escaped single quotes from \' to '
		$safe_text = str_replace( "\\\'", "\'", $safe_text );      // back converting escaped single quotes from \' to '

		return $safe_text;
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// DB
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Check $value for injections. Basically can be used in Requests params checking.
	 *
	 * @param type $value
	 *
	 * @return type
	 */
	function wpbc_esc_clean_parameter( $value ) {

	    $value = preg_replace( '/<[^>]*>/', '', $value );   // clean any tags
	    $value = str_replace( '<', ' ', $value );
	    $value = str_replace( '>', ' ', $value );
	    $value = strip_tags( $value );                      // Strip HTML and PHP tags from a string

	    // Clean SQL injection
	    $value = esc_sql( $value );
		$value = esc_textarea( $value );																				//FixIn: 7.1.1.2

	    return $value;
	}


	function wpbc_esc_sql_like( $value_trimmed ) {

		// check  here https://code.tutsplus.com/articles/data-sanitization-and-validation-with-wordpress--wp-25536

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
	 *
	 * @return string       - escaped
	 *                                  Exmaple:
	 *                                              $search_escaped_like_title = wpbc_esc_clean_like_string_for_append_in_sql_for_db( $input_var );
	 *
	 *                                              $where_sql = " WHERE title LIKE ". $search_escaped_like_title ." ";
	 */
	function wpbc_esc_clean_like_string_for_append_in_sql_for_db( $value ) {

		// check  here https://code.tutsplus.com/articles/data-sanitization-and-validation-with-wordpress--wp-25536

		global $wpdb;

		$value_trimmed = trim( stripslashes( $value ) );

		$wild = '%';
		$like = $wild . wpbc_esc_sql_like( $value_trimmed ) . $wild;
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
	 *     $like = $wild . wpbc_esc_sql_like( $find ) . $wild;
	 *     $sql  = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_content LIKE '%s'", $like );
	 *
	 * Example Escape Chain:
	 *
	 *     $sql  = esc_sql( wpbc_esc_sql_like( $input ) );
	 */

	}


	/**
	 * Clean string for using in SQL LIKE requests inside single quotes:    WHERE title LIKE '%". $escaped_search_title ."%'
	 *  Replaced _ to \_     % to \%      \   to   \\
	 *
	 * @param string $value - to clean
	 *
	 * @return string       - escaped
	 *                                  Exmaple:
	 *                                              $search_escaped_like_title = wpbc_esc_clean_like_string_for_db( $input_var );
	 *
	 *                                              $where_sql = " WHERE title LIKE '%". $search_escaped_like_title ."%' ";
	 *
	 *                                  Important! Use SINGLE quotes after in SQL query:  LIKE '%".$data."%'
	 */
	function wpbc_esc_clean_like_string_for_db( $value ){

		// check  here https://code.tutsplus.com/articles/data-sanitization-and-validation-with-wordpress--wp-25536

		global $wpdb;

		$value_trimmed = trim( stripslashes( $value ) );

		$value_trimmed =  wpbc_esc_sql_like( $value_trimmed );

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
	 *     $like = $wild . wpbc_esc_sql_like( $find ) . $wild;
	 *     $sql  = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_content LIKE '%s'", $like );
	 *
	 * Example Escape Chain:
	 *
	 *     $sql  = esc_sql( wpbc_esc_sql_like( $input ) );
	 */
	}


//                                                                              </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" D B "  >

/***
 * Get list  of DB  of this plugin
 * @return array
 */
function wpbc_get_db_names(){

	global $wpdb;

	$db_names = array(
		'bookings'       => $wpdb->prefix . 'booking',
		'dates'          => $wpdb->prefix . 'bookingdates',
		'resources'      => $wpdb->prefix . 'bookingtypes',
		'resources_meta' => $wpdb->prefix . 'booking_types_meta',
		'seasons'        => $wpdb->prefix . 'booking_seasons',
		'coupons'        => $wpdb->prefix . 'booking_coupons'
	);

	return $db_names;
}

// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" E r r o r s   H a n d l i n g "  >

	/**
	 * PHP Fatal Error! Display expanded error info at  Booking Listing  page
	 *
	 * @param $message
	 * @param $error
	 *
	 * @return mixed|string
	 */
	function wpbc_php_error_message( $message, $error ) {

		// Check to show this error, only from Booking Listing page, checking secure parameters from wpbc_ajx_booking_listing in ..{Booking Calendar Folder}/includes/page-bookings/_src/ajx_booking_listing.js
		if ( ! ( ! isset( $_POST['search_params'] ) || empty( $_POST['search_params'] ) ) ) {
			// Security  -----------------------------------------------------------------------------------------------    // in Ajax Post:   'nonce': wpbc_ajx_booking_listing.get_secure_param( 'nonce' ),
			$action_name    = 'wpbc_ajx_booking_listing_ajx' . '_wpbcnonce';
			$nonce_post_key = 'nonce';
			// $result_check   = check_ajax_referer( $action_name, $nonce_post_key );
			if ( isset( $_REQUEST[ $nonce_post_key ] ) ) {

				$nonce_request       = $_REQUEST[ $nonce_post_key ];

				$result_verify_nonce = wp_verify_nonce( $nonce_request, $action_name );

				if ( false !== $result_verify_nonce ) {
					if ( ( ! empty( $error ) ) && ( ! empty( $error['message'] ) ) ) {
						$message .= '<br>' . $error['message'];
					}
				}
			}
		}

		return $message;
	}
	add_filter( 'wp_php_error_message', 'wpbc_php_error_message', 2 , 10 );


	/**
	 * Nonce Error! Error Handing for not passed nonce, in booking Listing page.
	 * Probably nonce was expired (more than 24 hours)
	 *
	 * @param $action
	 * @param $result   - 1 Good ( 0-12 hours ago ) , 2 Good ( 12-24 hours ago ) , false - Error  ( > 24 hours - nonce is invalid )
	 */
	function wpbc_check_ajax_referer__for_booking_listing( $action, $result ){
		if (
				( false === $result )
		     && ( 'wpbc_ajx_booking_listing_ajx' . '_wpbcnonce' === $action )
		     && ( wp_doing_ajax() )
		) {
			 die( '<strong>Error!</strong> Probably nonce for this page has been expired. Please <a href="javascript:void(0)" onclick="javascript:location.reload();">reload the page</a>.' );
	    }
	}
	add_action( 'check_ajax_referer', 'wpbc_check_ajax_referer__for_booking_listing', 2, 10 );
// </editor-fold>