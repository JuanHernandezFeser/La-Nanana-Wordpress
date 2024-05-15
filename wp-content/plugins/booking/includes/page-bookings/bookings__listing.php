<?php /**
 * @version 1.0
 * @description AJX_Bookings
 * @category  AJX_Bookings Class
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2020-01-23
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

class WPBC_AJX_Bookings {

    /* Static Variables */
    static $data_separator = array(
									'r_separator'     => '~'
								  , 'f_separator'     => '^'
                         );


	// <editor-fold     defaultstate="collapsed"                        desc=" ///  JS | CSS files | Tpl loading  /// "  >

		// JS | CSS  ===================================================================================================

		/**
		 * Define HOOKs for loading CSS and  JavaScript files
		 */
		public function init_load_css_js_tpl() {
			// JS & CSS

			// Load only  at  AJX_Bookings Settings Page
			if ( 'vm_booking_listing' == wpbc_ajx_booking_listing__get_default_view_mode() ) {
			//if  ( strpos( $_SERVER['REQUEST_URI'], 'view_mode=vm_booking_listing' ) !== false ) {

				add_action( 'wpbc_enqueue_js_files', array( $this, 'js_load_files' ), 50 );
				add_action( 'wpbc_enqueue_css_files', array( $this, 'enqueue_css_files' ), 50 );

				add_action( 'wpbc_hook_settings_page_footer', array( $this, 'hook__page_footer_tmpl' ) );
			}
		}

		/** JSS */
		public function js_load_files( $where_to_load ) {

			$in_footer = true;

			if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

				//wp_enqueue_script( 'wpbc-live_search', wpbc_plugin_url( '/_out/js/live_search.js' ), array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );
				wp_enqueue_script( 'wpbc-booking_ajx_toolbar_hooks'
					, trailingslashit( plugins_url( '', __FILE__ ) ) . '_out/bookings__hooks.js'         /* wpbc_plugin_url( '/_out/js/codemirror.js' ) */
					, array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

				wp_enqueue_script( 'wpbc-booking_ajx_listing'
					, trailingslashit( plugins_url( '', __FILE__ ) ) . '_out/bookings__listing.js'         /* wpbc_plugin_url( '/_out/js/codemirror.js' ) */
					, array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

				wp_enqueue_script( 'wpbc-booking_ajx_actions'
					, trailingslashit( plugins_url( '', __FILE__ ) ) . '_out/bookings__actions.js'         /* wpbc_plugin_url( '/_out/js/codemirror.js' ) */
					, array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

				wp_enqueue_script( 'wpbc-general_ui_js_css'
					, wpbc_plugin_url( '/includes/_general_ui_js_css/_out/wpbc_main_ui_funcs.js' )
					, array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

				/**
				wp_localize_script( 'wpbc-global-vars', 'wpbc_live_request_obj'
									, array(
											'ajx_booking'  => '',
											'reminders' => ''
										)
				);
			    */
			}
		}

		/** CSS */
		public function enqueue_css_files( $where_to_load ) {

			if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

				//wp_enqueue_style( 'wpbc-ajx_booking-listing', wpbc_plugin_url( '/includes/listing_ajx_booking/o-ajx_booking-listing.css' ), array(), WP_BK_VERSION_NUM );
				//wp_enqueue_style( 'wpbc-listing_ajx_booking', trailingslashit( plugins_url( '', __FILE__ ) ) . '_out/bookings__listing.css' , array(), WP_BK_VERSION_NUM );

			}
		}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc=" ///  Templates  /// "  >

		// Templates ===================================================================================================

		/**
		 * Templates at footer of page
		 *
		 * @param $page string
		 */
		public function hook__page_footer_tmpl( $page ){

			// page=wpbc&view_mode=vm_booking_listing
			if ( 'wpbc-ajx_booking'  === $page ) {		// it's from >>	do_action( 'wpbc_hook_settings_page_footer', 'wpbc-ajx_booking' );
				$this->template__listing_header();
				$this->template__listing_row();
				$this->template__content_data();
			}
		}


		private function template__listing_header() {

			// Header
			?><script type="text/html" id="tmpl-wpbc_ajx_booking_list_header">
				<div class="wpbc_listing_usual_row wpbc_list_header wpbc_selectable_head">
					<div class="wpbc_listing_col wpbc_col_id check-column"><div class="content_text"><input type="checkbox" /></div></div>
					<div class="wpbc_listing_col wpbc_col_booking_labels wpbc_col_labels"><div class="content_text"><?php 	echo esc_js( __( 'Labels', 'booking' ) . ' / ' . __('Actions' ,'booking' ) ); ?></div></div>
					<div class="wpbc_listing_col wpbc_col_data"><div class="content_text"><?php 	echo esc_js( __( 'Booking Data', 'booking' ) ); ?></div></div>
					<div class="wpbc_listing_col wpbc_col_dates"><div class="content_text"><?php 	echo esc_js( __( 'Booking Dates', 'booking' ) ); ?>
							<span class="wpbc_listing_header_action_icon">
								<a  id="booking_dates_full"
									onclick="javascript:wpbc_ajx_click_on_dates_wide();" href="javascript:void(0)"
									title="<?php echo esc_attr( __('Show ALL dates of booking' ,'booking') ); ?>"
									style="<# if ( 'wide' === wpbc_ajx_booking_listing.search_get_param('ui_usr__dates_short_wide') ) { #>display: none;<# } #>"
									class="tooltip_top"
								><i class="wpbc_icn_swap_horiz" style=" margin-top: 2px;"></i></a>
								<a  id="booking_dates_small"
									onclick="javascript:wpbc_ajx_click_on_dates_short();" href="javascript:void(0)"
									title="<?php echo esc_attr( __('Show only check in/out dates' ,'booking') ); ?>"
									style="<# if ( 'short' === wpbc_ajx_booking_listing.search_get_param('ui_usr__dates_short_wide') ) { #>display: none;<# } #>"
									class="tooltip_top"
								><i class="wpbc_icn_compare_arrows" style=" margin-top: 2px;"></i></a>
							</span>
					</div></div>
				</div>
			</script><?php
		}


		private function template__listing_row() {

			/**
			 *  data objet =  { "booking_db": {
													"booking_id": "3",
													"trash": "0",
													"sync_gid": "",
													"is_new": "0",
													"status": "",
													"sort_date": "2022-02-03 12:00:01",
													"modification_date": "2022-03-14 17:48:23",
													"form": "select-one^rangetime2^12:00 - 14:00~text^name2^Anthony~text^secondname2^Gomez~text^email2^Gomez.example@wpbookingcalendar.com~text^address2^144 Hitchcock Rd~text^city2^Jacksonville~text^postcode2^38374~text^country2^US~text^phone2^988-48-45~select-one^visitors2^3~checkbox^children2[]^2~textarea^details2^",
													"hash": "993086af7a382f3956e6a0010932c856",
													"booking_type": "2",
													"remark": null,
													"cost": "58.00",
													"pay_status": "",
													"pay_request": "0"
											  },
								"id": "3",
								"approved": "0",
								"dates": [ "2022-02-03 12:00:01", "2022-02-03 14:00:02" ],
								"child_id": [ "", "" ],
								"short_dates": [ "2022-02-03 12:00:01", "-", "2022-02-03 14:00:02" ],
								"short_dates_child_id": [ "", "", "" ],
								"parsed_fields": {
													"rangetime": "12:00 PM - 2:00 PM",
													"name": "Anthony",
													"secondname": "Gomez",
													"email": "Gomez.example@wpbookingcalendar.com",
													"address": "144 Hitchcock Rd",
													"city": "Jacksonville",
													"postcode": "38374",
													"country": "US",
													"phone": "988-48-45",
													"visitors": "3",
													"children": "2",
													"details": "",
													"booking_id": "3",
													"trash": "0",
													"sync_gid": "",
													"is_new": "0",
													"status": "",
													"sort_date": "2022-02-03 12:00:01",
													"modification_date": "March 14, 2022 5:48 PM",
													"hash": "993086af7a382f3956e6a0010932c856",
													"booking_type": "2",
													"cost": "58.00",
													"pay_status": "",
													"pay_request": "0",
													"id": "3",
													"approved": "0"
								},
								"templates": {
												"form_show": "<div class=\"standard-content-form\"> \r\n\t<strong>First Name</strong>:<span class=\"fieldvalue\">Anthony</span>&nbsp;&nbsp; \r\n\t<strong>Last Name</strong>:<span class=\"fieldvalue\">Gomez</span>&nbsp;&nbsp; \r\n\t<strong>Email</strong>:<span class=\"fieldvalue\">Gomez.example@wpbookingcalendar.com</span>&nbsp;&nbsp; \r\n\t<strong>Adults</strong>:<span class=\"fieldvalue\"> 3</span>&nbsp;&nbsp; \r\n\t<strong>Children</strong>:<span class=\"fieldvalue\"> 2</span>&nbsp;&nbsp; \r\n\t<strong>Details</strong>:&nbsp;&nbsp;<span class=\"fieldvalue\"> </span> \r\n</div>"
											},
								"__search_request_keyword__": ""
							  }
			 */

			// Rows
			?><script type="text/html" id="tmpl-wpbc_ajx_booking_list_row">
				<div id="row_id_{{{data.parsed_fields.booking_id}}}" class="wpbc_listing_usual_row wpbc_list_row wpbc_row">
					<div class="wpbc_listing_col wpbc_col_id check-column"><div class="content_text"><input type="checkbox" /></div></div>
					<div class="wpbc_listing_col wpbc_col_booking_labels wpbc_col_labels">
						<div class="content_text">
							<?php
								wpbc_for_booking_template__action_read();
							?>
							<span class="wpbc_label wpbc_label_booking_id">{{data['parsed_fields']['booking_id']}}</span><?php

							/**
							 * Check for errors in translation.
							 *
							 * Example
							 *      French  translation  of this code:
							 *
							 *      if ( '<?php _e( 'Resource not exist', 'booking' ); ?>' == data.parsed_fields.resource_title ) {
							 * is:
							 * 		if ( 'La ressource n'existe pas' == data.parsed_fields.resource_title ) {
							 *
							 * it's generate JS error
							 *
							 * That's why we need to use
							 *      if ( '<?php echo esc_js(__( 'Resource not exist', 'booking' )); ?>' == data.parsed_fields.resource_title ) {
							 * which  is:
							 * 		if ( 'La ressource n\'existe pas' == data.parsed_fields.resource_title ) {
							 */

							?>
							<#
							if ( '<?php echo esc_js(__( 'Resource not exist', 'booking' )); ?>' == data.parsed_fields.resource_title ) {
								#><span class="wpbc_label wpbc_label_resource wpbc_label_deleted_resource">{{{data.parsed_fields.resource_title}}}</span><#
							} else if ( '' != data.parsed_fields.resource_title ) {
								#><span class="wpbc_label wpbc_label_resource">{{{data.parsed_fields.resource_title}}}</span><#
							}

							#><span class="wpbc_label wpbc_label_approved<# if ( '0' == data.approved ) { #> hidden_items <# } #>"><i style="color: #def5d4;" class="menu_icon icon-1x wpbc_icn_done_all"></i><?php echo esc_js(__( 'Approved', 'booking' )); ?></span><#
							#><span class="wpbc_label wpbc_label_pending<#  if ( '1' == data.approved ) { #> hidden_items <# } #>"><i style="color: #f6efe8;" class="menu_icon icon-1x wpbc_icn_hourglass_empty"></i><?php echo esc_js(__( 'Pending', 'booking' )); ?></span><#

							if ( '' != data.templates.payment_label_template ) {
								#>{{{data.templates.payment_label_template}}}<#
							}

							if ( '' != data.parsed_fields.sync_gid ) {
								#><span class="wpbc_label wpbc_label_imported"><i class="menu_icon icon-1x wpbc_icn_cloud_download system_update_alt"></i><?php echo esc_js(__( 'Imported', 'booking' )); ?></span><#
							}

							#><span class="wpbc_label wpbc_label_trash<# if ( '1' != data.parsed_fields.trash ) { #> hidden_items <# } #>"><i style="color: #f6efe8;" class="menu_icon icon-1x wpbc_icn_delete_forever"></i><?php echo esc_js(__( 'In Trash', 'booking' )); ?></span><#

							#><?php


							$labels = get_bk_option( 'wpbc_ajx_booking_labels' );
							$labels = explode( "\n",$labels );
							$labels = array_map( 'trim',$labels );

							// Check for Labels and colors
							foreach ($labels as $label) {

								if ( ( ! empty( $label ) ) && ( false !== strpos( ':', $label ) ) ) {
									list( $label, $color, $text_color ) = explode( ':', $label );
								} else{
									$color = $text_color = '';
								}


								if ( ! empty( $label ) ) {
									?><span class="wpbc_label" style="color:<?php echo $text_color; ?>;background-color:<?php echo $color; ?>;">{{{data.parsed_fields<?php echo $label;  ?>}}}</span><?php
								}
							}

							// Predefined internal Labels
						 	?><#
							if ( 	( undefined != data._paid )
								 && (  -1 != data._paid.toLowerCase().indexOf( 'refund' )  )
							) {
								#><span class="wpbc_label booking_label__refund"><?php echo esc_js(__('Refund','booking')); ?></span><#
							}
console.log( 'row listing', data );	// LISTING_ROWS
						#>
						</div>
					</div>
					<div class="wpbc_listing_col wpbc_col_data">
						<div class="content_text">
						<# <?php if (0) { ?><script type="text/javascript"><?php  /* Hack  for showing  JavaScript syntax */ } ?>
							var booking_details = data.templates.form_show;
							var booking_keyword = data[ '__search_request_keyword__' ];
							    booking_details = wpbc_get_highlighted_search_keyword( booking_details, booking_keyword );
						<?php if (0) { ?></script><?php } ?> #>
						{{{booking_details}}}
						<?php if ( 0 ) { ?>
							<div class="wpbc_next_booking_time">
								<?php do_action( 'wpbca_show_cron_data_in_ajx_booking_listing' ); ?>
								<span><strong><?php echo esc_js(__( 'Source', 'booking' )); ?></strong>: <span class="fieldvalue"><strong>{{data['source']}}</strong></span></span>
								<span><strong><?php echo esc_js(__( 'Booking ID', 'booking' )); ?></strong>: <span class="fieldvalue"><strong>{{data['booking_id']}}</strong></span></span>
								<span class="wpbc_item_actions wpdevelop">
									<a href="javascript:void();"    style="margin:0;"
									   onclick="javascript:wpbc_ajx_booking__modify__ajx_reset( wpbc_get_row_id_from_element( this ) );return false;"
									   class="tooltip_top button-secondary button"
									   title="<?php echo esc_js(__('Set last checked booking id to 0', 'booking' )); ?>"
									   data-original-title="Reset"
									><i class="glyphicon glyphicon-flash -repeat"></i></a>
								</span>
							</div>
							<hr/>
						<?php } ?>
								<# if ( ( !true ) || ( '' == booking_details.trim() ) ) { #>
								<hr/>
								<# <?php if (0) { ?><script type="text/javascript"><?php  /* Hack  for showing  JavaScript syntax */ } ?>

									// Content Data Template
									var my_content_data 	 = wp.template( 'wpbc_content_data' );

									_.each( data['parsed_fields'], function ( p_val, p_key, p_data ) {

										// Skip these fields
										if (
											 ! _.contains(  [
																'__search_request_keyword__'
																, 'booking_id', 'source'
																, 'create_date','edit_date', 'google_calendar_link'
															]
															,  p_key
														)
										){
											#>{{{
													my_content_data( {"key": p_key, "value": wpbc_decode_HTML_entities( p_val ), "keyword": data['__search_request_keyword__'] } )
											}}}<#
										}

									});
								<?php if (0) { ?></script><?php } ?> #>
								<# } #>
					    </div>
					</div>
					<div class="wpbc_listing_col wpbc_col_dates wpbc_col_labels">
						<div class="booking_dates_small booking_dates_expand_section" style="<# if ( 'short' !== wpbc_ajx_booking_listing.search_get_param('ui_usr__dates_short_wide') ) { #>display: none;<# } #>"
						><div class="content_text">{{{data.templates.short_dates_content}}}</div></div>
						<div class="booking_dates_full booking_dates_expand_section"  style="<# if ( 'wide' !== wpbc_ajx_booking_listing.search_get_param('ui_usr__dates_short_wide') ) { #>display: none;<# } #>"
						><div class="content_text">{{{data.templates.wide_dates_content}}}</div></div>
					</div><?php
						$this->for_template__actions_buttons();
					?>
				</div>

			</script><?php
		}


			private function template__content_data(){

				// Content Data
				?><script type="text/html" id="tmpl-wpbc_content_data">
					<strong>{{data.key}}</strong>:<span class="fieldvalue {{data.key}}<#
					if ( 	( data.keyword != '' )
						 && ( undefined != data.value )
						 && (  -1 != data.value.toLowerCase().indexOf( data.keyword.trim().toLowerCase() )  )
					) {
						#> fieldsearchvalue<#
					}
					if ( 	( undefined != data.value )
						 && (  -1 != data.value.toLowerCase().indexOf( 'refund' )  )
					) {
						#> _refund<#
					}
					#>">{{{data.value}}}</span>&nbsp;&nbsp;
				</script><?php
			}


			private function for_template__actions_buttons(){
				?>
				<div class="wpbc_item_actions wpdevelop">
					<div  class="wpbc_actions_buttons">

						<div class="wpbc_ajx_toolbar wpbc_buttons_row wpbc_buttons_row_for_booking">
							<div class="ui_container ui_container_small">
								<div class="ui_group"><?php

									wpbc_for_booking_template__action_cost();

									wpbc_for_booking_template__action_payment_request();

									wpbc_for_booking_template__action_set_payment_status();

									if ( class_exists( 'wpdev_bk_personal' ) ) {
										wpbc_flex_divider();
									}

									wpbc_for_booking_template__action_edit_booking();

									if ( ! class_exists( 'wpdev_bk_personal' ) ) {
										wpbc_flex_divider();
									}

									wpbc_for_booking_template__action_change_resource();

									wpbc_for_booking_template__action_duplicate_booking_to_other_resource();

									wpbc_for_booking_template__action_print();

									wpbc_for_booking_template__action_remark();

									wpbc_for_booking_template__action_locale();

									wpbc_for_booking_template__action_add_google_calendar();

									wpbc_flex_divider();

									wpbc_for_booking_template__action_trash();

									wpbc_for_booking_template__action_trash_restore();

									wpbc_for_booking_template__action_delete();

									wpbc_for_booking_template__action_approved();

									wpbc_for_booking_template__action_pending();

									?><div class="ui_element" style="flex:100%;"></div><?php		// New line

									wpbc_for_booking_template_section__action_remark_textarea();

									wpbc_for_booking_template_section__change_booking_resource();

									wpbc_for_booking_template_section__duplicate_booking_to_other_resource();

									wpbc_for_booking_template_section__set_payment_status();

								?></div>
							</div>
						</div>

					</div>
					<div class="wpbc_actions_sysinfo">
						<span><?php echo esc_js(__( 'Booking ID', 'booking' )); ?>: <strong>{{data['parsed_fields']['booking_id']}}</strong></span>&nbsp;&nbsp;
						<span><?php echo esc_js(__( 'Edited', 'booking' )); ?>: <strong>{{data['parsed_fields']['modification_date']}}</strong></span>&nbsp;&nbsp;
						<span><?php echo esc_js(__( 'Created', 'booking' )); ?>: <strong>{{data['parsed_fields']['creation_date']}}</strong></span>
					</div>
				</div>
				<?php
			}



	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc=" ///  A J A X  /// "  >

		// A J A X =====================================================================================================

		/**
		 * Define HOOKs for start  loading Ajax
		 */
		public function define_ajax_hook(){

			// Ajax Handlers.		Note. "locale_for_ajax" rechecked in wpbc-ajax.php
			add_action( 'wp_ajax_'		     . 'WPBC_AJX_BOOKING_LISTING', array( $this, 'ajax_' . 'WPBC_AJX_BOOKING_LISTING' ) );	    // Admin & Client (logged in usres)

			// Ajax Handlers for actions
			add_action( 'wp_ajax_'		     . 'WPBC_AJX_BOOKING_ACTIONS', 			'wpbc_ajax_' . 'WPBC_AJX_BOOKING_ACTIONS' );

			// add_action( 'wp_ajax_nopriv_' . 'WPBC_AJX_BOOKING_LISTING', array( $this, 'ajax_' . 'WPBC_AJX_BOOKING_LISTING' ) );	    // Client         (not logged in)
		}



		/**
		 * Ajax - Get Listing Data and Response to JS script
		 */
		public function ajax_WPBC_AJX_BOOKING_LISTING() {

			if ( ! isset( $_POST['search_params'] ) || empty( $_POST['search_params'] ) ) { exit; }

			// Security  -----------------------------------------------------------------------------------------------    // in Ajax Post:   'nonce': wpbc_ajx_booking_listing.get_secure_param( 'nonce' ),
			$action_name    = 'wpbc_ajx_booking_listing_ajx' . '_wpbcnonce';
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
													   'db_option_name'          => 'booking_listing_request_params',
													   'user_id'                 => $user_id, 							//FixIn: 9.4.3.2
													   'request_rules_structure' => wpbc_ajx_get__request_params__names_default()
													)
							);
			$request_prefix = 'search_params';
			$request_params = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 		// NOT Direct: 	$_REQUEST['search_params']['resource_id']


			$data_arr       = wpbc_ajx_get_booking_data_arr( $request_params );

			$new_bookings_count = wpbc_db_get_number_new_bookings();

			if ( 'make_reset' === $request_params['ui_reset'] ) {

				$is_reseted = wpbc_ajx__user_request_params__delete( $user_id );

				$request_params['ui_reset'] = $is_reseted ? 'reset_done' : 'reset_error';

			} else {
				$is_success_update = wpbc_ajx__user_request_params__save( $request_params, $user_id );	// - $request_params - serialized here automatically
			}

			//----------------------------------------------------------------------------------------------------------
			// Send JSON. Its will make "wp_json_encode" - so pass only array, and This function call wp_die( '', '', array( 'response' => null, ) )		Pass JS OBJ: response_data in "jQuery.post( " function on success.
			wp_send_json( array(
								'ajx_count'             => $data_arr['count'],
								'ajx_items'             => $data_arr['data_arr'],
								'ajx_booking_resources' => $data_arr['booking_resources'],
								'ajx_search_params'     => $_REQUEST['search_params'],
								'ajx_cleaned_params'    => $request_params,
								'ajx_new_bookings_count'=> $new_bookings_count
							) );
		}

	// </editor-fold>


}


/**
 * Just for loading CSS and  JavaScript files
 */
if ( true ) {
	$ajx_booking_loading = new WPBC_AJX_Bookings;
	$ajx_booking_loading->init_load_css_js_tpl();
	$ajx_booking_loading->define_ajax_hook();
}




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// API Hooks
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Get booking data by  booking_ID
 *
 * @param $booking_id int
 *
 * @return false | stdClass Object (
										[booking_db] => stdClass Object (
												[booking_id] => 130
												[booking_options] =>
												[trash] => 0
												[sync_gid] => 15l1028ii1v95ctbpgcf2r2s2h_20171201
												[is_new] => 0
												[status] =>
												[sort_date] => 2022-08-08 00:00:00
												[modification_date] => 2022-07-24 11:04:32
												[form] => text^name1^Jessica~text^secondname1^....
												[hash] => 1d7dc4e06d95726bf9
												[booking_type] => 1
												[remark] =>
												[cost] => 100.00
												[pay_status] => Failed
												[pay_request] => 0
											)
										[id] => 130
										[approved] => 0
										[dates] 				=> Array ( [0] => '2022-08-08 00:00:00' [1] => '2022-08-09 00:00:00' [2] => '2022-08-10 00:00:00' )
										[child_id] 				=> Array ( [0] => '' [1] => '' [2] => '' )
										[short_dates] 			=> Array ( [0] => '2022-08-08 00:00:00' [1] => '-' [2] => '2022-08-10 00:00:00' )
										[short_dates_child_id] 	=> Array ( [0] => '' [1] => '' [2] => '' )
										[parsed_fields] => Array (
												[name] => Jessica
												[secondname] => Simson
												...
												[booking_id] => 130
												[trash] => 0
												[sync_gid] => 15l1028ii1v95ctbpgcf2r2s2h_20171201
												[is_new] => 0
												[status] =>
												[sort_date] => 2022-08-08 00:00:00
												[modification_date] => July 24, 2022 11:04
												[hash] => 1d7dc4e06d95726bf9060a66235b7dc6
												[booking_type] => 1
												[remark] =>
												[cost] => 100.00
												[pay_status] => Failed
												[pay_request] => 0
												[id] => 130
												[approved] => 0
												[booking_options] =>
												[is_paid] => 0
												[pay_print_status] => Failed
												[currency_symbol] => $
												[resource_title] => Standard
												[resource_id] => 1
												[resource_owner_user] => 1
												[google_calendar_link] => https://calendar.google.com/calendar/r/eventedit?text=...
											)
										[templates] => Array (
												[form_show] => 		First Name:Jessica
																	Last Name:Simson
																	Email:simson@gmail.com
																	Phone:724 895 34 88
																	Address:Oliver street 10
																	City:Manchester
																	Post code:78998
																	Country:UK
																	Adults: 2
																	Children: 0
																	Details:   I want a room with a terrace

												[form_show_nohtml] =>
																	First Name:Jessica
																	Last Name:Simson
																	Email:simson@gmail.com
																	Phone:724 895 34 88
																	Address:Oliver street 10
																	City:Manchester
																	Post code:78998
																	Country:UK
																	Adults: 2
																	Children: 0
																	Details:   I want a room with a terrace
												[short_dates_content] => August 8, 2022 - August 10, 2022
												[wide_dates_content] => August 8, 2022, August 9, 2022, August 10, 2022
												[payment_label_template] => Payment Failed
											)
									)
 */
function wpbc_search_booking_by_id( $booking_id ) {

	$booking_id = intval( $booking_id );

	if ( ! empty( $booking_id ) ) {
		$booking_data = wpbc_search_booking_by_keyword( 'id:' . $booking_id );

		if ( ! empty( $booking_data['data_arr'] ) ) {
			return $booking_data['data_arr'][0];
		}
	}

	return false;
}

/**
 * Search specific booking(s) by Keyword
 *
 * @param string   	$keyword								'email@serv.com'
 * @param array 	$search_params		default array()		 array( 'source' => 'csv' )
 *
 * @return array(
				[data_arr] => Array (
									[0] => Array(
											[booking_id] => 2772
											[product_name] => Personal
											[date_placed] => 2019-10-16
											[order] => XXA3443ASDDA-232423-423423
											[email] => email@serv.com
											[_license_key] => 74826576578436
											[full_product_name] => Personal (single site)
											 ....
										)
					)
				[count] => 1
 *              
 */
 function wpbc_search_booking_by_keyword( $keyword , $search_params = array() ){

	 $ajx_booking_listing = new WPBC_AJX_Bookings;

	 $request_params = array(
		 'page_num'         => 1,
		 'page_items_count' => 99999,
		 'sort'             => 'booking_id',
		 'sort_type'        => 'DESC',
		 'keyword'          => ''
	 );

	 // Get Default Parameters
	 $default_param_values = wpbc_ajx_get__request_params__names_default( 'default' );
	 $request_params       = wp_parse_args( $request_params, $default_param_values );

	// Get Search Parameters,  if passed into  function
	 $request_params = wp_parse_args( $search_params, $request_params );

	 $request_params['keyword'] = wpbc_sanitize_text( $keyword );

	 $ajx_booking_arr = wpbc_ajx_get_booking_data_arr( $request_params );

	 return $ajx_booking_arr;
 }
 add_filter( 'wpbc_search_booking_by_keyword' , 'wpbc_search_booking_by_keyword' ,10, 2 );

/**
 * DevApi:   apply_filters( 'wpbc_search_booking_by_keyword',  ' d1ca3d0b476c ', array( 'source' => 'csv' )  );
 */

