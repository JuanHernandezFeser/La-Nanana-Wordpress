<?php /**
 * @version 1.0
 * @description Ajax and Requests Structure  for   WPBC_AJX__Customize_Plugin__Ajax_Request
 * @category   Customize_Plugin Class
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2023-06-23
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


class WPBC_AJX__Customize_Plugin__Ajax_Request {


	// <editor-fold     defaultstate="collapsed"                        desc=" ///  R e q u e s t  /// "  >

	/**
	 * Get params names for escaping and/or default value of such  params
	 *
	 * @return array        array (  'resource_id'      => array( 'validate' => 'digit_or_csd',  	'default' => array( '1' ) )
	 *                             , ... )
	 */
	static public function request_rules_structure(){

		$default_resource_id = wpbc_get_default_resource();

		$days_selection_arr       = self::get_days_selection_parameters_arr();
		$unavailable_weekdays_arr = self::get_calendar_unavailable_weekdays();

		return  array(
			'do_action' => array(
				'validate' => array(
										'none',
										'save_calendar_skin',
										'save_calendar_dates_selection',
										'save_calendar_weekdays_availability',
										'save_calendar_additional',

										'make_reset'
				),
				'default'  => 'none'
			)

			, 'customize_plugin__booking_skin' => array( 'validate' => 's', 'default' => '/css/skins/green-01.css' )	// get_bk_option( 'booking_skin' )

			, 'current_step' => array( 'validate' => array( 'calendar_skin', 'calendar_size', 'calendar_dates_selection', 'calendar_weekdays_availability', 'calendar_additional'
														 , 'form_structure', 'form_times', 'form_legend'
														 , 'emails_active'
														 , 'payments_active'
														 , 'publish_resource' )
									, 'default' => 'calendar_skin' )

		    , 'resource_id' 		=> array( 'validate' => 'd',  	'default' => $default_resource_id )	    	// 'digit_or_csd' can check about 'digit_or_csd' in arrays, as well         // if ['0'] - All  booking resources
			, 'dates_selection' 	=> array( 'validate' => 's', 	'default' => '' )
			, 'dates_customize_plugin'	=> array( 'validate' => array( 'unavailable', 'available' ),     'default' => 'unavailable' )

			, 'ui_clicked_element_id'   				    => array( 'validate' => 's', 'default' => '' )
 			//, 'ui_usr__customize_plugin_selected_toolbar'   => array( 'validate' => array( 'info', 'calendar_settings' ),     'default' => 'info' )

			// Calendar settings
			, 'calendar__start_year' 		=> array( 'validate' => 'd',  	'default' => 0 )
			, 'calendar__start_month' 		=> array( 'validate' => 'd',  	'default' => 0 )

			, 'calendar__view__visible_months' 	=> array( 'validate' => 'd',  	'default' => 2 )

			, 'calendar__view__months_in_row' 	=> array( 'validate' => 'd',  	'default' => '' )
			, 'calendar__view__width' 			=> array( 'validate' => 's',  	'default' => '' )
			, 'calendar__view__cell_height' 	=> array( 'validate' => 's',  	'default' => '' )			// '48px' || ''

			, 'calendar__timeslot_day_bg_as_available' 	=> array( 'validate' => 's'
															    , 'default' => ('On' === get_bk_option( 'booking_timeslot_day_bg_as_available' ) ) ? ' wpbc_timeslot_day_bg_as_available' : '' )
			// Modes :: Selection of Days
			, 'calendar__days_selection_mode' 	=> array( 'validate' => array( 'single', 'multiple', 'dynamic', 'fixed' )
													   ,   'default' => $days_selection_arr['calendar__days_selection_mode'] )
			, 'calendar__bk_1click_mode_days_num'       => array( 'validate' => 'd', 'default'  => $days_selection_arr['calendar__bk_1click_mode_days_num'] )       /* Number of days selection with 1 mouse click */
			, 'calendar__bk_1click_mode_days_start'     => array( 'validate' => 's', 'default'  => $days_selection_arr['calendar__bk_1click_mode_days_start'] )     /* { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat } */
			, 'calendar__bk_2clicks_mode_days_min'      => array( 'validate' => 'd', 'default'  => $days_selection_arr['calendar__bk_2clicks_mode_days_min'] )      /* Min. Number of days selection with 2 mouse clicks */
			, 'calendar__bk_2clicks_mode_days_max'      => array( 'validate' => 'd', 'default'  => $days_selection_arr['calendar__bk_2clicks_mode_days_max'] )      /* Max. Number of days selection with 2 mouse clicks */
			, 'calendar__bk_2clicks_mode_days_specific' => array( 'validate' => 's', 'default'  => $days_selection_arr['calendar__bk_2clicks_mode_days_specific'] ) /* Example: '5,7' */
			, 'calendar__bk_2clicks_mode_days_start'    => array( 'validate' => 's', 'default'  => $days_selection_arr['calendar__bk_2clicks_mode_days_start'] )    /* { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat } */

			, 'calendar__booking_max_monthes_in_calendar' 	=> array( 'validate' => 's',	'default' => get_bk_option( 'booking_max_monthes_in_calendar' ) )
			, 'calendar__booking_start_day_weeek' 	        => array( 'validate' => array( '0','1','2','3','4','5','6' ),	'default' => get_bk_option( 'booking_start_day_weeek' ) )

			// Unavailable Weekdays & more...
			, 'availability__user_unavilable_days'          => array( 'validate' => 's', 'default'  => $unavailable_weekdays_arr['user_unavilable_days'] )
			, 'availability__block_some_dates_from_today'   => array( 'validate' => 'd', 'default'  => $unavailable_weekdays_arr['block_some_dates_from_today']  )
			, 'availability__wpbc_available_days_num_from_today'   => array( 'validate' => 'd', 'default'  => $unavailable_weekdays_arr['wpbc_available_days_num_from_today']  )

			, 'availability__booking_unavailable_extra_in_out' 	    => array( 'validate' => array( '', 'm', 'd' ), 'default'  => $unavailable_weekdays_arr[ 'booking_unavailable_extra_in_out' ] )
			, 'availability__booking_unavailable_extra_minutes_in'  => array( 'validate' => 's', 'default'  => $unavailable_weekdays_arr['booking_unavailable_extra_minutes_in'] )
			, 'availability__booking_unavailable_extra_minutes_out' => array( 'validate' => 's', 'default'  => $unavailable_weekdays_arr['booking_unavailable_extra_minutes_out'] )
			, 'availability__booking_unavailable_extra_days_in'     => array( 'validate' => 's', 'default'  => $unavailable_weekdays_arr['booking_unavailable_extra_days_in'] )
			, 'availability__booking_unavailable_extra_days_out'    => array( 'validate' => 's', 'default'  => $unavailable_weekdays_arr['booking_unavailable_extra_days_out'] )

		);

	}


		/**
		 * Get default params
		 *
		 * @return array        array (  'ui_wh_modification_date_radio' => 0
		 *                             , ... )
		 */
		static public function get__request_values__default() {

			$request_rules_structure = self::request_rules_structure();

			$default_params_arr = array();

			$structure_type = 'default';

			foreach ( $request_rules_structure as $key => $value ) {
				$default_params_arr[ $key ] = $value[ $structure_type ];
			}

			return $default_params_arr;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc=" Support functions "  >

		/**
		 * Generate shortcode for booking form,  from  the  parameters
		 *
		 * @param array $request_params array(
													  'resource_id' => 1
													, 'calendar__view__visible_months' => $request_params['calendar__view__visible_months']
													, 'calendar__view__months_in_row'  => $request_params['calendar__view__months_in_row']
													, 'calendar__view__width' 		   => $request_params['calendar__view__width']
													, 'calendar__view__cell_height'    => $request_params['calendar__view__cell_height']		// '48px' || ''
											)
		 *
		 * @return string
		 */
		public function get_shortcode__from_request__booking_form( $request_params = array() ){

		    $shortcode__booking_form_arr = array();
			$shortcode__booking_form_arr[] = "[booking";
			if ( ( ! empty( $request_params['resource_id'] ) ) && ( $request_params['resource_id'] > 1 ) ) {
				$shortcode__booking_form_arr[] = "type={$request_params['resource_id']}";
			}
			if ( ( ! empty( $request_params['calendar__view__visible_months'] ) ) && ( $request_params['calendar__view__visible_months'] > 1 ) ) {
				$shortcode__booking_form_arr[] = "nummonths={$request_params['calendar__view__visible_months']}";
			}
			if (
					( ! empty( $request_params['calendar__view__months_in_row'] ) )
			     || ( ! empty( $request_params['calendar__view__width'] ) )
			     || ( ! empty( $request_params['calendar__view__cell_height'] ) )
			) {
				$shortcode__booking_form_arr[] = "options='{calendar";
				if ( ! empty( $request_params['calendar__view__months_in_row'] ) ) {
					$shortcode__booking_form_arr[] = "months_num_in_row={$request_params['calendar__view__months_in_row']}";
				}
				if ( ! empty( $request_params['calendar__view__width'] ) ) {
					$shortcode__booking_form_arr[] = "width={$request_params['calendar__view__width']}";
				}
				if ( ! empty( $request_params['calendar__view__cell_height'] ) ) {
					$shortcode__booking_form_arr[] = "cell_height={$request_params['calendar__view__cell_height']}";
				}
				$shortcode__booking_form_arr[] = "}'";
			}
			$shortcode__booking_form = implode( ' ', $shortcode__booking_form_arr );
			$shortcode__booking_form .= ']';

			return $shortcode__booking_form;
		}


		/**
		 * Get days selection parameters,  which saved in database
		 *
		 * @return array
		 */
		static public function get_days_selection_parameters_arr(){

			$specific_days_selection = ( function_exists( 'wpbc_get_specific_range_dates__as_comma_list' ) )
										? wpbc_get_specific_range_dates__as_comma_list( get_bk_option( 'booking_range_selection_days_specific_num_dynamic' ) )
										: '';
			$data_arr = array();
			// Modes :: Selection of Days
			$data_arr['calendar__days_selection_mode'] = ( 'range' === get_bk_option('booking_type_of_day_selections') )
														? get_bk_option('booking_range_selection_type')
														: get_bk_option( 'booking_type_of_day_selections');
			$data_arr['calendar__bk_1click_mode_days_num']   = intval( get_bk_option( 'booking_range_selection_days_count' ) );                     /* Number of days selection with 1 mouse click */
			$data_arr['calendar__bk_1click_mode_days_start'] = get_bk_option( 'booking_range_start_day' );                                          /* { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat } */
			$data_arr['calendar__bk_2clicks_mode_days_min']      = intval( get_bk_option( 'booking_range_selection_days_count_dynamic' ) );         /* Min. Number of days selection with 2 mouse clicks */
			$data_arr['calendar__bk_2clicks_mode_days_max']      = intval( get_bk_option( 'booking_range_selection_days_max_count_dynamic' ) );     /* Max. Number of days selection with 2 mouse clicks */
			$data_arr['calendar__bk_2clicks_mode_days_specific'] = $specific_days_selection;                                                        /* Example: '5,7' */
			$data_arr['calendar__bk_2clicks_mode_days_start']    = get_bk_option( 'booking_range_start_day_dynamic' );                              /* { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat } */

			return $data_arr;
		}


		/**
		 * Get unavailable weekdays,  which saved in database
		 *
		 * @return array
		 */
		static public function get_calendar_unavailable_weekdays(){

		    $data_arr = array();

			$data_arr['user_unavilable_days'] = array();
			foreach ( range( 0, 6 ) as $val ) {
				if ( 'On' == get_bk_option( 'booking_unavailable_day' . $val ) ) {
					$data_arr['user_unavilable_days'][] = $val;
				}
			}
			$data_arr['user_unavilable_days'] = implode( ',', $data_arr['user_unavilable_days'] );

			// Unavailable days from  Today
			$data_arr['block_some_dates_from_today']        = get_bk_option( 'booking_unavailable_days_num_from_today' );
			$data_arr['wpbc_available_days_num_from_today'] = intval( get_bk_option( 'booking_available_days_num_from_today' ) );

			$data_arr['booking_unavailable_extra_in_out']      = get_bk_option( 'booking_unavailable_extra_in_out' );
			$data_arr['booking_unavailable_extra_minutes_in']  = get_bk_option( 'booking_unavailable_extra_minutes_in' );
			$data_arr['booking_unavailable_extra_minutes_out'] = get_bk_option( 'booking_unavailable_extra_minutes_out' );
			$data_arr['booking_unavailable_extra_days_in']     = get_bk_option( 'booking_unavailable_extra_days_in' );
			$data_arr['booking_unavailable_extra_days_out']    = get_bk_option( 'booking_unavailable_extra_days_out' );

			return $data_arr;
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc=" ///  A J A X  /// "  >

		// A J A X =====================================================================================================

		/**
		 * Define HOOKs for start  loading Ajax
		 */
		public function define_ajax_hook(){

			// Ajax Handlers.		Note. "locale_for_ajax" rechecked in wpbc-ajax.php
			add_action( 'wp_ajax_'		     . 'WPBC_AJX_CUSTOMIZE_PLUGIN', array( $this, 'ajax_' . 'WPBC_AJX_CUSTOMIZE_PLUGIN' ) );	    // Admin & Client (logged in usres)

			// Ajax Handlers for actions
			//add_action( 'wp_ajax_'		     . 'WPBC_AJX_BOOKING_ACTIONS', 			'wpbc_ajax_' . 'WPBC_AJX_BOOKING_ACTIONS' );

			// add_action( 'wp_ajax_nopriv_' . 'WPBC_AJX_BOOKING_LISTING', array( $this, 'ajax_' . 'WPBC_AJX_BOOKING_LISTING' ) );	    // Client         (not logged in)
		}


		/**
		 * Ajax - Get Listing Data and Response to JS script
		 */
		public function ajax_WPBC_AJX_CUSTOMIZE_PLUGIN() {

			if ( ! isset( $_POST['search_params'] ) || empty( $_POST['search_params'] ) ) { exit; }

			// Security  -----------------------------------------------------------------------------------------------    // in Ajax Post:   'nonce': wpbc_ajx_booking_listing.get_secure_param( 'nonce' ),
			$action_name    = 'wpbc_ajx_customize_plugin_ajx' . '_wpbcnonce';
			$nonce_post_key = 'nonce';
			$result_check   = check_ajax_referer( $action_name, $nonce_post_key );

			$user_id = ( isset( $_REQUEST['wpbc_ajx_user_id'] ) )  ?  intval( $_REQUEST['wpbc_ajx_user_id'] )  :  wpbc_get_current_user_id();

			/**
			 * SQL  ---------------------------------------------------------------------------
			 *
			 * in Ajax Post:  'search_params': wpbc_ajx_booking_listing.search_get_all_params()
			 *
			 * Use prefix "search_params", if Ajax sent -
			 *                 $_REQUEST['search_params']['page_num'], $_REQUEST['search_params']['page_items_count'],..
			 */

			$user_request = new WPBC_AJX__REQUEST( array(
													   'db_option_name'          => 'booking_customize_plugin_request_params',
													   'user_id'                 => $user_id,
													   'request_rules_structure' => WPBC_AJX__Customize_Plugin__Ajax_Request::request_rules_structure()
													)
							);
			$request_prefix = 'search_params';
			$request_params = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 		// NOT Direct: 	$_REQUEST['search_params']['resource_id']

			//----------------------------------------------------------------------------------------------------------

			$data_arr = array();
			$data_arr['ajx_after_action_message'] = '';
			$data_arr['ajx_after_action_result']  = 1;

			$data_arr['calendar_settings'] = array(
													  'booking_change_over_days_triangles'     => get_bk_option( 'booking_change_over_days_triangles' )
													, 'booking_range_selection_time_is_active' => get_bk_option( 'booking_range_selection_time_is_active' )

													, 'calendar__view__visible_months' => $request_params['calendar__view__visible_months']

													, 'calendar__view__months_in_row'  => $request_params['calendar__view__months_in_row']
													, 'calendar__view__width' 		   => $request_params['calendar__view__width']
													, 'calendar__view__cell_height'    => $request_params['calendar__view__cell_height']		// '48px' || ''

													, 'calendar__booking_max_monthes_in_calendar'   => $request_params['calendar__booking_max_monthes_in_calendar']
													, 'calendar__booking_start_day_weeek'           => $request_params['calendar__booking_start_day_weeek']

													, 'calendar_unavailable' => array()
													, 'calendar_dates_additional_info' => array()
												);

			// Shortcode (for this configuration)
			$data_arr['calendar_settings']['shortcode__booking_form'] = $this->get_shortcode__from_request__booking_form( $request_params );


			$data_arr['customize_steps'] = array();
			$data_arr['customize_steps']['action']    = 'none';

			$top_tab_slug = explode( '_', $request_params['current_step'] );
			$top_tab_slug = ( ! empty( $top_tab_slug ) ) ? $top_tab_slug[0] : 'calendar';


			// sub Tabs  -----------------------------------------------------------------------------------------------
			switch ( $top_tab_slug ) {
			    case 'calendar':
								$data_arr['customize_steps']['steps_arr'] = array(
												 'calendar_skin'  		=> array( 'class' => 'wpbc_option_step', 'html'  =>  __( 'Calendar Skin', 'booking' ) ),
												 'calendar_size'  		=> array( 'class' => 'wpbc_option_step', 'html'  => __( 'Calendar size', 'booking' ) ),
												 'calendar_dates_selection' 		=> array( 'class' => 'wpbc_option_step', 'html'  => __( 'Dates selection', 'booking' ) ),
												 'calendar_weekdays_availability' 	=> array( 'class' => 'wpbc_option_step', 'html'  => __( 'Unavailable weekdays', 'booking' ) ),
												 'calendar_additional' 	=> array( 'class' => 'wpbc_option_step', 'html'  => __( 'Additional Settings', 'booking' ) ),
											);
			        			break;
			    case 'form':
								$data_arr['customize_steps']['steps_arr'] = array(
												 'form_structure'  	=> array( 'class' => 'wpbc_option_step', 'html'  => __( 'Form Structure', 'booking' ) ),
												 'form_times'  		=> array( 'class' => 'wpbc_option_step', 'html'  => __( 'Booked Times', 'booking' ) ),
												 'form_legend' 		=> array( 'class' => 'wpbc_option_step', 'html'  => __( 'Legend', 'booking' ) )
											);
			        			break;
			    default:
			       				$data_arr['customize_steps']['steps_arr'] = array();
			}


			// Steps:  Current, Prior, Next  &  future action ----------------------------------------------------------
			switch ( $request_params['current_step'] ) {
			    case 'calendar_skin':

					$data_arr['customize_steps']['action'] = 'save_calendar_skin';

				    $data_arr['customize_steps']['current'] = $request_params['current_step'];
				    $data_arr['customize_steps']['prior']   = '';
				    $data_arr['customize_steps']['next']    = 'calendar_size';

			        break;
			    case 'calendar_size':

					$data_arr['customize_steps']['action'] = 'none';

				    $data_arr['customize_steps']['current'] = $request_params['current_step'];
				    $data_arr['customize_steps']['prior']   = 'calendar_skin';
				    $data_arr['customize_steps']['next']    = 'calendar_dates_selection';

			        break;
			    case 'calendar_dates_selection':

					$data_arr['customize_steps']['action'] = 'save_calendar_dates_selection';

				    $data_arr['customize_steps']['current'] = $request_params['current_step'];
				    $data_arr['customize_steps']['prior']   = 'calendar_size';
				    $data_arr['customize_steps']['next']    = 'calendar_weekdays_availability';

			        break;
			    case 'calendar_weekdays_availability':

					$data_arr['customize_steps']['action'] = 'save_calendar_weekdays_availability';

				    $data_arr['customize_steps']['current'] = $request_params['current_step'];
				    $data_arr['customize_steps']['prior']   = 'calendar_dates_selection';
				    $data_arr['customize_steps']['next']    = 'calendar_additional';

			        break;
			    case 'calendar_additional':

					$data_arr['customize_steps']['action'] = 'save_calendar_additional';

				    $data_arr['customize_steps']['current'] = $request_params['current_step'];
				    $data_arr['customize_steps']['prior']   = 'calendar_weekdays_availability';
				    $data_arr['customize_steps']['next']    = 'form_structure';

			        break;
			    case 2:
			        
			        break;
			    default:
			       // Default
			}
			

			// Actions =================================================================================================

			if ( 'save_calendar_additional' == $request_params['do_action'] ) {

				$is_updated = update_bk_option( 'booking_max_monthes_in_calendar',  $request_params['calendar__booking_max_monthes_in_calendar'] );
				$is_updated = update_bk_option( 'booking_start_day_weeek',          $request_params['calendar__booking_start_day_weeek'] );
			}

			if ( 'save_calendar_weekdays_availability' == $request_params['do_action'] ) {

				// Update Weekdays Unavailability
				$request_el_arr = $request_params['availability__user_unavilable_days'];

				foreach ( range( 0, 6 ) as $val ) {
					if ( false !== strpos( $request_el_arr, $val ) ) {
						$is_updated = update_bk_option( 'booking_unavailable_day' . $val , 'On');
					} else{
						$is_updated = update_bk_option( 'booking_unavailable_day' . $val , 'Off');
					}
				}

				$is_updated = update_bk_option( 'booking_unavailable_days_num_from_today', $request_params['availability__block_some_dates_from_today'] );
				if ( class_exists( 'wpdev_bk_biz_m' ) ) {
					$is_updated = update_bk_option( 'booking_available_days_num_from_today', $request_params['availability__wpbc_available_days_num_from_today'] );

					$is_updated = update_bk_option( 'booking_unavailable_extra_in_out',         $request_params['availability__booking_unavailable_extra_in_out'] );
					$is_updated = update_bk_option( 'booking_unavailable_extra_minutes_in',     $request_params['availability__booking_unavailable_extra_minutes_in'] );
					$is_updated = update_bk_option( 'booking_unavailable_extra_minutes_out',    $request_params['availability__booking_unavailable_extra_minutes_out'] );
					$is_updated = update_bk_option( 'booking_unavailable_extra_days_in',        $request_params['availability__booking_unavailable_extra_days_in'] );
					$is_updated = update_bk_option( 'booking_unavailable_extra_days_out',       $request_params['availability__booking_unavailable_extra_days_out'] );
				}
			}


			if ( 'save_calendar_dates_selection' == $request_params['do_action'] ) {

				if (  ( 'dynamic' == $request_params['calendar__days_selection_mode'] ) ||  ( 'fixed' == $request_params['calendar__days_selection_mode'] ) ) {
					$is_updated = update_bk_option( 'booking_type_of_day_selections', 'range' );                                            // 'single', 'multiple', 'range
					$is_updated = update_bk_option( 'booking_range_selection_type', $request_params['calendar__days_selection_mode'] );     // 'dynamic', 'fixed'
				} else {
					$is_updated = update_bk_option( 'booking_type_of_day_selections', $request_params['calendar__days_selection_mode'] );
				}
				$is_updated = update_bk_option( 'booking_range_selection_days_count', max( intval( $request_params['calendar__bk_1click_mode_days_num'] ), 1 ) );   //max() :: If user set 0, then get minimum 1 value
				$is_updated = update_bk_option( 'booking_range_start_day',                    $request_params['calendar__bk_1click_mode_days_start'] );

				$is_updated = update_bk_option( 'booking_range_selection_days_count_dynamic',     max( intval( $request_params['calendar__bk_2clicks_mode_days_min'] ), 1 ) );  //max() :: If user set 0, then get minimum 1 value
				$is_updated = update_bk_option( 'booking_range_selection_days_max_count_dynamic', max( intval( $request_params['calendar__bk_2clicks_mode_days_max'] ), 1 ) );

				if ( false !== strpos( $request_params['calendar__bk_2clicks_mode_days_specific'], '-' ) ) {

					// Replace 5,7-10,23 to 5,7,8,9,10,23
					$request_params['calendar__bk_2clicks_mode_days_specific'] = ( function_exists( 'wpbc_get_specific_range_dates__as_comma_list' ) )
																				? wpbc_get_specific_range_dates__as_comma_list( $request_params['calendar__bk_2clicks_mode_days_specific'] )
																				: '';
				}
				// Check to have only digits and comma
				$request_params['calendar__bk_2clicks_mode_days_specific'] = wpbc_sanitize_digit_or_csd( $request_params['calendar__bk_2clicks_mode_days_specific'] );

				$is_updated = update_bk_option( 'booking_range_selection_days_specific_num_dynamic',      $request_params['calendar__bk_2clicks_mode_days_specific'] );

				$calendar__bk_2clicks_mode_days_start = $request_params['calendar__bk_2clicks_mode_days_start'];
				if (
					   ( false !== strpos( $calendar__bk_2clicks_mode_days_start, '-1' ) )
					|| ( false !== strpos( $calendar__bk_2clicks_mode_days_start, '0,1,2,3,4,5,6' ) )
				){
					$calendar__bk_2clicks_mode_days_start = '-1';
				}
				$is_updated = update_bk_option( 'booking_range_start_day_dynamic',                        $calendar__bk_2clicks_mode_days_start );

				$data_arr['ajx_after_action_message'] = __('Dates selection','booking') . ' - ' . __('Saved', 'booking') ;
				$data_arr['ajx_after_action_result']  = 1;
			}

			if ( 'save_calendar_skin' == $request_params['do_action'] ) {

				$is_updated = update_bk_option( 'booking_skin', $request_params['customize_plugin__booking_skin'] );

				$data_arr['ajx_after_action_message'] = __('Calendar Skin','booking') . ' - ' . __('Saved', 'booking') ;
				$data_arr['ajx_after_action_result']  = 1;
			}


			// Get actual data for templates  ==========================================================================

			// Modes :: Selection of Days
			$days_selection_arr = self::get_days_selection_parameters_arr();
			$data_arr['calendar_settings']['calendar__days_selection_mode']       = $days_selection_arr['calendar__days_selection_mode'];                    /* 'single', 'multiple', 'dynamic', 'fixed' */
			$data_arr['calendar_settings']['calendar__bk_1click_mode_days_num']   = $days_selection_arr['calendar__bk_1click_mode_days_num'];                /* Number of days selection with 1 mouse click */
			$data_arr['calendar_settings']['calendar__bk_1click_mode_days_start'] = $days_selection_arr['calendar__bk_1click_mode_days_start'];              /* { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat } */
			$data_arr['calendar_settings']['calendar__bk_2clicks_mode_days_min']      = $days_selection_arr['calendar__bk_2clicks_mode_days_min'];           /* Min. Number of days selection with 2 mouse clicks */
			$data_arr['calendar_settings']['calendar__bk_2clicks_mode_days_max']      = $days_selection_arr['calendar__bk_2clicks_mode_days_max'];           /* Max. Number of days selection with 2 mouse clicks */
			$data_arr['calendar_settings']['calendar__bk_2clicks_mode_days_specific'] = $days_selection_arr['calendar__bk_2clicks_mode_days_specific'];      /* Example: '5,7' */
			$data_arr['calendar_settings']['calendar__bk_2clicks_mode_days_start']    = $days_selection_arr['calendar__bk_2clicks_mode_days_start'];         /* { -1 - Any | 0 - Su,  1 - Mo,  2 - Tu, 3 - We, 4 - Th, 5 - Fr, 6 - Sat } */

			// Weekdays
			$unavailable_weekdays_arr = self::get_calendar_unavailable_weekdays();
			$data_arr['calendar_settings']['calendar_unavailable']['user_unavilable_days']        = $unavailable_weekdays_arr['user_unavilable_days'];

			// UNAVAILABLE  Today days
			$data_arr['calendar_settings']['calendar_unavailable']['block_some_dates_from_today'] = $unavailable_weekdays_arr['block_some_dates_from_today'];
					// Hints
					$last_unavailable_date = '';
					$data_arr['calendar_settings']['calendar_unavailable']['block_some_dates_from_today__hint'] = ': <span style="text-transform: lowercase;font-size:0.9em;">' . __( 'None', 'booking' ) . '</span>';

					if ( 1 == $unavailable_weekdays_arr['block_some_dates_from_today'] ){
						$last_unavailable_date = wp_date( 'Y-m-d 00:00:00' );
						$data_arr['calendar_settings']['calendar_unavailable']['block_some_dates_from_today__hint'] = ': ' . wp_date( 'd M', strtotime( $last_unavailable_date ) );
					}
					if ( $unavailable_weekdays_arr['block_some_dates_from_today'] > 1 ){
						$last_unavailable_date = wp_date( 'Y-m-d 00:00:00', strtotime( '+' . ( $unavailable_weekdays_arr['block_some_dates_from_today'] - 1 ) . ' days' ) );
						$data_arr['calendar_settings']['calendar_unavailable']['block_some_dates_from_today__hint'] = ': ' . wp_date( 'd M' ) . ' - ' . wp_date( 'd M', strtotime( $last_unavailable_date ) );
					}
			// AVAILABLE  Today days
			$data_arr['calendar_settings']['calendar_unavailable']['wpbc_available_days_num_from_today'] = $unavailable_weekdays_arr['wpbc_available_days_num_from_today'];
					// Hints
					$start_available_date = ( '' == $last_unavailable_date ) ? wp_date( 'Y-m-d 00:00:00' ) : wp_date( 'Y-m-d 00:00:00', strtotime( '+1 day', strtotime( $last_unavailable_date ) ) );

					if ( empty( $unavailable_weekdays_arr['wpbc_available_days_num_from_today'] ) ) {
						$last_available_date = '';
					} else {
						$last_available_date = wp_date( 'Y-m-d 00:00:00', strtotime( '+' . ( $unavailable_weekdays_arr['wpbc_available_days_num_from_today'] ) . ' days' ) );
					}

					if ( ! empty( $unavailable_weekdays_arr['wpbc_available_days_num_from_today'] ) ) {

						if ( strtotime($start_available_date) < strtotime($last_available_date) ) {

							$data_arr['calendar_settings']['calendar_unavailable']['wpbc_available_days_num_from_today__hint'] = ': '
							                                                                         .  wp_date( 'd M, Y', strtotime( $start_available_date ) )
							                                                                         . ' - '
							                                                                         .  wp_date( 'd M, Y', strtotime( $last_available_date ) );
						} else if ( strtotime($start_available_date) == strtotime($last_available_date) ) {
							$data_arr['calendar_settings']['calendar_unavailable']['wpbc_available_days_num_from_today__hint']  = ': ' .  wp_date( 'd M, Y', strtotime( $start_available_date ) ) ;
						}else{
							$data_arr['calendar_settings']['calendar_unavailable']['wpbc_available_days_num_from_today__hint'] = ': <span style="text-transform: uppercase;font-size:1.1em;">' . __( 'None', 'booking' ) . '</span>'
																									 . ' - <span style="text-transform: lowercase;font-size:0.9em;">'. wp_date( 'd M, Y', strtotime( $start_available_date ) )
							                                                                         . ' > '
							                                                                         .  wp_date( 'd M, Y', strtotime( $last_available_date ) ) .'</span>';
						}
					} else {
						$data_arr['calendar_settings']['calendar_unavailable']['wpbc_available_days_num_from_today__hint']  = ': ' .  wp_date( 'd M, Y', strtotime( $start_available_date ) ) . ' - ...';
					}

			$data_arr['calendar_settings']['calendar_unavailable']['booking_unavailable_extra_in_out'] = $unavailable_weekdays_arr['booking_unavailable_extra_in_out'];
			$data_arr['calendar_settings']['calendar_unavailable']['booking_unavailable_extra_minutes_in'] = $unavailable_weekdays_arr['booking_unavailable_extra_minutes_in'];
			$data_arr['calendar_settings']['calendar_unavailable']['booking_unavailable_extra_minutes_out'] = $unavailable_weekdays_arr['booking_unavailable_extra_minutes_out'];
			$data_arr['calendar_settings']['calendar_unavailable']['booking_unavailable_extra_days_in'] = $unavailable_weekdays_arr['booking_unavailable_extra_days_in'];
			$data_arr['calendar_settings']['calendar_unavailable']['booking_unavailable_extra_days_out'] = $unavailable_weekdays_arr['booking_unavailable_extra_days_out'];


			// <editor-fold     defaultstate="collapsed"                        desc="  Dates | Resources for calendar  "  >

				$data_arr['calendar_settings']['calendar_dates_rates'] = apply_filters( 'wpbc_get_calendar_dates_rates_arr', array(), $request_params['resource_id'] );

				if ( ( 'On' == get_bk_option( 'booking_is_show_booked_data_in_tooltips' ) ) && ( function_exists( 'wpbc_get_additional_booking_info_to_dates_arr' ) ) ) {
					$data_arr['calendar_settings']['calendar_dates_additional_info'] = wpbc_get_additional_booking_info_to_dates_arr( $request_params['resource_id'] );
				}

				$data_arr['calendar_settings']['booked_dates'] = wpbc__sql__get_booked_dates( array(
																				'resource_id' => $request_params['resource_id']
																			) );

				$data_arr['season_customize_plugin'] = wpbc__sql__get_season_availability( array(
																				'resource_id' => $request_params['resource_id']
																			) );

				$data_arr['resource_unavailable_dates'] =  wpbc_resource__get_unavailable_dates($request_params['resource_id']);


				//----------------------------------------------------------------------------------------------------------

				// Get booking resources (sql)
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

				$data_arr['ajx_booking_resources'] = $resources_arr_sorted;

				//----------------------------------------------------------------------------------------------------------

				$data_arr['ajx_nonce_calendar'] = wp_nonce_field( 'CALCULATE_THE_COST', 'wpbc_nonce' . 'CALCULATE_THE_COST' . $request_params['resource_id'], true, false );

				$data_arr['popover_hints'] = array();

				$data_arr['popover_hints']['season_unavailable'] = '<strong>' . __( 'Season unavailable day', 'booking' ) . '</strong><hr>'
													. sprintf( __( 'Change this date status at %sBooking %s Availability %s Season Availability page.', 'booking' ), '<br>', '&gt;', '&gt;' );
				$data_arr['popover_hints']['weekdays_unavailable'] = '<strong>' . __( 'Unavailable week day', 'booking' ) . '</strong><hr>'
												   . sprintf( __( 'Change this date status at %sBooking %s Settings General page %s in "Availability" section.', 'booking' ), '<br>', '&gt;', '<br>' );
				$data_arr['popover_hints']['before_after_unavailable'] = '<strong>' . __( 'Unavailable day, depends on today day', 'booking' ) . '</strong><hr>'
												   . sprintf( __( 'Change this date status at %sBooking %s Settings General page %s in "Availability" section.', 'booking' ), '<br>', '&gt;', '<br>' );



				$data_arr['popover_hints']['toolbar_text'] = '<span style="font-size: 1.05em;line-height: 1.8em;">'.
												   sprintf( __('%sSelect days%s in calendar then select %sAvailable%s / %sUnavailable%s status and click %sApply%s customize_plugin button.' ,'booking')
															, '<strong>', '&nbsp;</strong>'
															, '<strong>&nbsp;', '&nbsp;</strong>'
															, '<strong>&nbsp;', '&nbsp;</strong>'
															, '<strong>&nbsp;', '&nbsp;</strong>'
												   )
												.'</span>';
				$data_arr['popover_hints']['toolbar_text_available'] = sprintf( __( 'Set dates %s as %s available.', 'booking' )
																				, '_DATES_'
																				, '_HTML_'
																			);
				$data_arr['popover_hints']['toolbar_text_unavailable'] = sprintf( __( 'Set dates %s as %s unavailable.', 'booking' )
																				, '_DATES_'
																				, '_HTML_'
																			);
			// </editor-fold>


			// Clear here DATES selection in $request_params['dates_selection'] to  not save such  selection

			if ( 'make_reset' === $request_params['do_action'] ) {

				$is_reseted = $user_request->user_request_params__db_delete();											// Delete from DB

				$request_params['do_action'] = $is_reseted ? 'reset_done' : 'reset_error';
			} else {

				$request_params_to_save = $request_params;

				// Do not safe such elements
				unset( $request_params_to_save['ui_clicked_element_id'] );
				unset( $request_params_to_save['do_action'] );
				unset( $request_params_to_save['dates_selection'] );
				// Do not save "Do not change background color for partially booked days" option ! it must reflect from Booking > Settings General page and not from User options
				unset( $request_params_to_save['calendar__timeslot_day_bg_as_available'] );                             //FixIn: 9.5.5.4

				$is_success_update = $user_request->user_request_params__db_save( $request_params_to_save );					// Save to DB		// - $request_params - serialized here automatically
			}

			//----------------------------------------------------------------------------------------------------------
			// Send JSON. Its will make "wp_json_encode" - so pass only array, and This function call wp_die( '', '', array( 'response' => null, ) )		Pass JS OBJ: response_data in "jQuery.post( " function on success.
			wp_send_json( array(
								'ajx_data'              => $data_arr,
								'ajx_search_params'     => $_REQUEST[ $request_prefix ],								// $_REQUEST[ 'search_params' ]
								'ajx_cleaned_params'    => $request_params
							) );
		}

	// </editor-fold>

}

/**
 * Just for loading CSS and  JavaScript files
 */
if ( true ) {
	$ajx_customize_plugin_loading = new WPBC_AJX__Customize_Plugin__Ajax_Request;
	$ajx_customize_plugin_loading->define_ajax_hook();
}