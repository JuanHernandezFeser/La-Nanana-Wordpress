<?php

if ( ! defined( 'WPBC_FEEDBACK_TIMEOUT' ) ) {       define( 'WPBC_FEEDBACK_TIMEOUT',    '+2 days' ); }
//if ( ! defined( 'WPBC_FEEDBACK_TIMEOUT' ) ) {       define( 'WPBC_FEEDBACK_TIMEOUT',    '+30 seconds' ); }


// Init Timer
// update_option( 'booking_feedback_03',  date( 'Y-m-d H:i:s', strtotime( WPBC_FEEDBACK_TIMEOUT, strtotime( 'now' ) ) ) );

// Reset
// delete_option( 'booking_feedback_03');

function wpbc_get_feedback_defaults( $param_name ){

    $defaults = array(
	    'page_where_load' => 'wpbc-ajx_booking_availability',				// Name of page,  where to  load feedback. Defined in pages,  like this: 		do_action( 'wpbc_hook_settings_page_footer', 'wpbc-ajx_booking_availability' );
		'max_version'     => '11.1',										// If version  of Booking Calendar 9.6 or newer than do not show this Feedback
		'feedback_email'  => 'feedback2@wpbookingcalendar.com'
	);

	return $defaults[ $param_name ];
}


class WPBC_Feedback_01 {

	// <editor-fold     defaultstate="collapsed"                        desc=" ///  A J A X  /// "  >

		// A J A X =====================================================================================================

		/**
		 * Define HOOKs for start  loading Ajax
		 */
		public function define_ajax_hook(){

			// Ajax Handlers.		Note. "locale_for_ajax" rechecked in wpbc-ajax.php
			add_action( 'wp_ajax_'		     . 'WPBC_AJX_FEEDBACK', array( $this, 'ajax_' . 'WPBC_AJX_FEEDBACK' ) );	    // Admin & Client (logged in usres)

			// Ajax Handlers for actions
			// add_action( 'wp_ajax_nopriv_' . 'WPBC_AJX_BOOKING_LISTING', array( $this, 'ajax_' . 'WPBC_AJX_BOOKING_LISTING' ) );	    // Client         (not logged in)
		}


		/**
		 * Ajax - Get Listing Data and Response to JS script
		 */
		public function ajax_WPBC_AJX_FEEDBACK() {

			if ( ! isset( $_POST['action_params'] ) || empty( $_POST['action_params'] ) ) { exit; }

			$ajax_errors = new WPBC_AJAX_ERROR_CATCHING();

			// Security  -----------------------------------------------------------------------------------------------    // in Ajax Post:   'nonce': wpbc_ajx_booking_listing.get_secure_param( 'nonce' ),
			$action_name    = 'wpbc_ajx__feedback_ajx' . '_wpbcnonce';
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
													   'db_option_name'          => 'booking_feedback_request_params',
													   'user_id'                 => $user_id,
													   'request_rules_structure' => array(
																						  'booking_action'        => array( 'validate' => array( 'feedback_01' ), 'default' => 'none' )
																						, 'feedback_stars'        => array( 'validate' => 'd', 'default' => 0 )
																						, 'feedback__note'        => array( 'validate' => 's', 'default' => '' )
																						, 'ui_clicked_element_id' => array( 'validate' => 's', 'default' => '' )
																					)
													)
							);
			$request_prefix = 'action_params';
			$request_params = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  );		 		// NOT Direct: 	$_REQUEST['action_params']['feedback_stars']

			//----------------------------------------------------------------------------------------------------------

		    $action_result = wpbc_booking_do_action__feedback_01(  $request_params
																			, array(
																				'user_id'          => $user_id
																			  )
																		);

			$defaults = array(
					    'new_listing_params'   => false		// required for Import Google Calendar bookings
                  	  , 'after_action_result'  => false
					  , 'after_action_message' => sprintf( __('No actions %s has been processed.', 'booking')
														 , ' <strong>' . $request_params['booking_action'] . '</strong> ' )
			);
			$action_result = wp_parse_args( $action_result, $defaults );

			// Check if there were some errors  --------------------------------------------------------------------------------
			$error_messages = $ajax_errors->get_error_messages();
			if ( ! empty( $error_messages ) ) {
				$action_result['after_action_message'] .= $error_messages;
			}



			//------------------------------------------------------------------------------------------------------------------
			// Send JSON. Its will make "wp_json_encode" - so pass only array, and This function call wp_die( '', '', array( 'response' => null, ) )		Pass JS OBJ: response_data in "jQuery.post( " function on success.
			wp_send_json( array(
								'ajx_action_params'                      => $_REQUEST['action_params'],							// Do not clean input parameters
								'ajx_cleaned_params'                     => $request_params,									// Cleaned input parameters
								'ajx_after_action_message'               => $action_result['after_action_message'],				// Message to  show
								'ajx_after_action_result'                => (int) $action_result['after_action_result'],		// Result key 				0 | 1
								'ajx_after_action_result_all_params_arr' => $action_result										// All result parameters
							) );
		}

	// </editor-fold>



	/**
	 * Define HOOKs for loading CSS and  JavaScript files
	 */
	public function init_load_css_js() {
		// JS & CSS
		add_action( 'wpbc_enqueue_js_files',  array( $this, 'js_load_files' ),     50  );
		add_action( 'wpbc_enqueue_css_files', array( $this, 'enqueue_css_files' ), 50  );
		// Template
		add_action( 'wpbc_hook_settings_page_footer', array( $this, 'wpbc_hidden_template__content_for_feedback_01' ), 50  );
	}

	/** JSS */
	public function js_load_files( $where_to_load ) {

		$in_footer = true;

		if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

			wp_enqueue_script( 'wpbc-feedback_02', trailingslashit( plugins_url( '', __FILE__ ) ) . 'feedback.js'         /* wpbc_plugin_url( '/_out/js/codemirror.js' ) */
												   , array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );
			/**
			wp_localize_script( 'wpbc-global-vars', 'wpbc_live_request_obj'
								, array(
										'contacts'  => '',
										'reminders' => ''
									)
			);
		 	*/
		}
	}

	/** CSS */
	public function enqueue_css_files( $where_to_load ) {

		if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

			wp_enqueue_style( 'wpbc-feedback_02', trailingslashit( plugins_url( '', __FILE__ ) ) . 'feedback.css', array(), WP_BK_VERSION_NUM );
		}
	}

	/** Feedback Layout - Modal Window structure */
	function wpbc_hidden_template__content_for_feedback_01( $page ) {

		if ( wpbc_get_feedback_defaults( 'page_where_load' ) != $page ) {                                                                                // Load feedback only at  this page
			return  false;
		}

		 if (  wpbc_is__feedback_01__timeout_need_to_show()  ) {														// Check  if time go out
			update_option( 'booking_feedback_03', '' );
		 } else {
			return  false;
		 }


		// Nonce for Ajax and some other data
		?><div  id="wpbc_ajax__feedback_01" style="display:none;"
				data-nonce="<?php echo wp_create_nonce( 'wpbc_ajx__feedback_ajx' . '_wpbcnonce' ); ?>"
				data-user-id="<?php echo wpbc_get_current_user_id(); ?>"
		></div><?php


		?><div id="wpbc_modal__feedback_01__section_mover">
		<span class="wpdevelop">
			<div id="wpbc_modal__feedback_01__section" class="modal wpbc_popup_modal wpbc_modal__feedback_01__section" tabindex="-1" role="dialog">
			  <div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title"><?php _e( 'Feedback' ); ?></h4>
					</div>
					<div class="modal-body">
					<?php

						$this->wpbc_hidden_template__content_for_feedback_01_steps();

					?>
					</div>
					<div class="modal-footer" style="display: none">

						<a href="javascript:void(0)" class="button button-secondary" data-dismiss="modal" style="float: left;">Do not show anymore</a>
						<a href="javascript:void(0)" class="button button-secondary" data-dismiss="modal" style="float: left;margin-left: 1em;">Remind me later</a>

						<a id="wpbc_modal__feedback_01__button_send" class="button button-primary"
						   onclick="javascript: wpbc_ajx_booking__ui_click__send_feedback_01();" href="javascript:void(0);"
						  ><?php _e('Next' ,'booking'); ?> 1/3</a>
					</div>
				</div><!-- /.modal-content -->
			  </div><!-- /.modal-dialog -->
			</div><!-- /.modal -->
		</span>
		</div><?php
																														//	Open modal  window just  after page loaded
		?><script type="text/javascript">
			jQuery(document).ready(function(){
				setTimeout(function() {
				   wpbc_open_feedback_modal();
				}, 2000);
			});
		</script><?php
	}



	/** Feedback Steps 		Content */
	function wpbc_hidden_template__content_for_feedback_01_steps(){

		/* S T A R S */ ?>
		<div class="wpbc_modal__feedback_01__steps wpbc_modal__feedback_01__step_1" >
			<h4 class="modal-title"><?php printf( 'Do you like the new %sNew Update%s of Booking Calendar?'  ,'<strong>','</strong>'); ?></h4>
			<div class="wpbc_feedback_01__content_rating">
				<div class="wpbc_feedback_01__content_rating_stars"><?php
					for( $i = 1; $i < 6; $i++) {
					  ?><a id="wpbc_feedback_01_star_<?php echo $i; ?>"
					   href="javascript:void(0)"
					   onmouseover="javascript:wpbc_feedback_01__over_star(<?php echo $i; ?>, 'over');"
					   onmouseout="javascript:wpbc_feedback_01__over_star(<?php echo $i; ?>, 'out');"
					   onclick="javascript:wpbc_feedback_01__over_star(<?php echo $i; ?>, 'click');"
					   data-original-title="<?php echo esc_attr( sprintf( __('Rate with %s star', 'booking'), $i ) ); ?>"
					   class="tooltip_top "><i class="menu_icon icon-1x wpbc-bi-star 0wpbc_icn_star_outline"></i></a><?php
					}
					?>
				</div>
			</div>
			<div class="modal-footer modal-footer-inside">
				<a href="javascript:void(0)" class="wpbc_btn_as_link" data-dismiss="modal"><?php _e('Do not show anymore' ,'booking'); ?></a>
				<a href="javascript:void(0)" class="wpbc_btn_as_link" onclick="javascript: wpbc_ajx_booking__ui_click__feedback_01_remind_later();"><?php _e('Remind me later' ,'booking'); ?></a>
				<a id="wpbc_modal__feedback_01__button_next__step_1" class="button button-primary wpbc_btn_next disabled"
				   onclick="javascript: wpbc_ajx_booking__ui_click__send_feedback_01(this);" href="javascript:void(0);"
				  ><?php _e('Next' ,'booking'); ?> <!--span>1/2</span--></a>
			</div>
		</div>

		<?php // Star 1 - 2 	Reason	 ?>
		<div class="wpbc_modal__feedback_01__steps wpbc_modal__feedback_01__step_2" style="display:none;">
			<h5 class="modal-title"><?php printf(__('Sorry to hear that... %s' ,'booking')
										,'<i class="menu_icon icon-1x wpbc-bi-emoji-frown 0wpbc_icn_sentiment_very_dissatisfied" style="color:#ca930b;"></i>');
			?></h5><br>
			<h4 class="modal-title"><?php _e('How can we improve it for you?' ,'booking'); ?></h4>
			<textarea id="wpbc_modal__feedback_01__reason_of_action__step_2"  name="wpbc_modal__feedback_01__reason_of_action__step_2"
					  style="width:100%;" cols="57" rows="3" autocomplete="off"
					  onkeyup="javascript:if(jQuery( this ).val() != '' ){ jQuery( '#wpbc_modal__feedback_01__button_next__step_2').removeClass('disabled'); }"
			></textarea>
			<div class="modal-footer modal-footer-inside">

				<a onclick="javascript: wpbc_ajx_booking__ui_click__send_feedback_01( this, '.wpbc_modal__feedback_01__step_1' );" href="javascript:void(0);"
				   class="button button-secondary"><?php _e('Back' ,'booking'); ?></a>

				<a id="wpbc_modal__feedback_01__button_next__step_2" class="button button-primary wpbc_btn_next"
				   onclick="javascript: wpbc_ajx_booking__ui_click__send_feedback_01( this, '.wpbc_modal__feedback_01__step_3');" href="javascript:void(0);"
				  ><?php _e('Next' ,'booking'); ?> <span>2/3</span></a>
			</div>
		</div>

		<?php /* Star 1 - 2 	Done 	*/ ?>
		<div class="wpbc_modal__feedback_01__steps wpbc_modal__feedback_01__step_3" style="display:none;">
			<h5 class="modal-title"><?php printf(__('Thank you for your feedback! %s' ,'booking')
									,'<i class="menu_icon icon-1x wpbc-bi-hand-thumbs-up" style="color: #dd2e44;"></i>' );
			?></h5><br>
			<h4 class="modal-title"><?php printf(__('You\'re helping us do a better job. :) We appreciate that!' ,'booking')
									,'<i class="menu_icon icon-1x wpbc-bi-hand-thumbs-up" style="color: #dd2e44;"></i>' );
			?></h4><br>
			<h4 class="modal-title"><?php printf(__('Thanks for being with us! %s' ,'booking')
									,'<i class="menu_icon icon-1x wpbc-bi-heart-fill 0wpbc_icn_favorite" style="color: #dd2e44;"></i>' );
			?></h4>
			<div class="modal-footer modal-footer-inside ui_element">
				<a class="button button-primary wpbc_ui_button wpbc_ui_button_primary" id="wpbc_modal__feedback_01__submit_1_2"
				onclick="javascript: wpbc_ajx_booking__ui_click__submit_feedback_01( this, '.wpbc_modal__feedback_01__step_3');" href="javascript:void(0);"
				><span><?php _e('Done' ,'booking'); ?></span>&nbsp; <i class="menu_icon icon-1x wpbc_icn_done_all"></i></a>
			</div>
		</div>

		<?php /* Star 3 - 4 	Reason	*/ ?>
		<div class="wpbc_modal__feedback_01__steps wpbc_modal__feedback_01__step_4" style="display:none;">
			<h4 class="modal-title"><?php _e('What functionality important to you are missing in the plugin?'); ?></h4><br>
			<textarea id="wpbc_modal__feedback_01__reason_of_action__step_4"  name="wpbc_modal__feedback_01__reason_of_action__step_4"
					  style="width:100%;" cols="57" rows="3" autocomplete="off"
					  onkeyup="javascript:if(jQuery( this ).val() != '' ){ jQuery( '#wpbc_modal__feedback_01__button_next__step_4').removeClass('disabled'); }"
			></textarea>
			<label class="help-block"><?php printf(__('It\'s an %soptional question%s. But, we\'d love to hear your thoughts!' ,'booking'),'<b>','</b>');?></label>
			<div class="modal-footer modal-footer-inside">

				<a onclick="javascript: wpbc_ajx_booking__ui_click__send_feedback_01( this, '.wpbc_modal__feedback_01__step_1' );" href="javascript:void(0);"
				   class="button button-secondary"><?php _e('Back' ,'booking'); ?></a>

				<a id="wpbc_modal__feedback_01__button_next__step_4" class="button button-primary wpbc_btn_next "
				   onclick="javascript: wpbc_ajx_booking__ui_click__send_feedback_01( this, '.wpbc_modal__feedback_01__step_5');" href="javascript:void(0);"
				  ><?php _e('Next' ,'booking'); ?> <span>2/3</span></a>
			</div>
		</div>

		<?php /* Star 3 - 4 	Done 	*/ ?>
		<div class="wpbc_modal__feedback_01__steps wpbc_modal__feedback_01__step_5" style="display:none;">
			<h4 class="modal-title"><?php printf(__('%sFantastic!%s Thanks for taking the time! %s' ,'booking')
										,'<strong>','</strong>'
										,'<i class="menu_icon icon-1x wpbc-bi-heart-fill 0wpbc_icn_favorite" style="color: #dd2e44;"></i>' );
			?></h4><br>
			<h4 class="modal-title"><?php _e('Your answers will help us to make the plugin better for you!' ,'booking'); ?></h4><br>
			<h4 class="modal-title"><?php _e('Thanks for sharing your feedback! Have a great day :)' ,'booking'); ?></h4>
			<div class="modal-footer modal-footer-inside ui_element">
				<a class="button button-primary wpbc_ui_button wpbc_ui_button_primary" id="wpbc_modal__feedback_01__submit_3_4"
				onclick="javascript: wpbc_ajx_booking__ui_click__submit_feedback_01( this, '.wpbc_modal__feedback_01__step_5');" href="javascript:void(0);"
				><span><?php _e('Done' ,'booking'); ?></span>&nbsp; <i class="menu_icon icon-1x wpbc_icn_done_all"></i></a>
			</div>
		</div>

		<?php /* Star 5 	Review	*/ ?>
		<div class="wpbc_modal__feedback_01__steps wpbc_modal__feedback_01__step_6" style="display:none;">
			<h4 class="modal-title" style="line-height: 2.4em;"><?php //printf(__('%sFantastic!%s Would you like to leave a small review about the product? %s' ,'booking')
				printf( '%sAwesome!%s Can you do us a HUGE favor and leave a 5-star rating for our plugin on WordPress to help us spread the word and boost our motivation? %s'
										,'<strong>','</strong>'
										,'<i class="menu_icon icon-1x wpbc-bi-heart-fill" style="color: #dd2e44;"></i>'
										,'<i class="menu_icon icon-1x wpbc-bi-balloon-heart" style="color: #dd2e44;"></i>'
										,'<i class="menu_icon icon-1x wpbc-bi-balloon-heart" style="color: #dd2e44;"></i>'
				);
			?></h4><br>
			<h4 class="modal-title"><?php //printf(__('It will support us to include more features in the plugin. %s%s%s' ,'booking')
				printf( 'Your support would mean a lot to us and we would be over the moon! :) %s%s%s'
				//printf(__('Your support would mean a lot to us and we would be incredibly appreciative! :) %s%s%s' ,'booking')
										,'<i class="menu_icon icon-1x wpbc-bi-balloon-heart" style="color: #dd2e44;"></i>'
										,'<i class="menu_icon icon-1x wpbc-bi-balloon-heart" style="color: #dd2e44;"></i>'
										,'<i class="menu_icon icon-1x wpbc-bi-balloon-heart" style="color: #dd2e44;"></i>'
				); ?></h4><br>
			<textarea id="wpbc_modal__feedback_01__reason_of_action__step_7"  name="wpbc_modal__feedback_01__reason_of_action__step_7" style="display:none;"
					  style="width:100%;" cols="57" rows="3" autocomplete="off"
					  onkeyup="javascript:if(jQuery( this ).val() != '' ){ jQuery( '#wpbc_modal__feedback_01__button_next__step_7').removeClass('disabled'); }"
					  placeholder="<?php esc_attr_e('What\'s the main benefit from Booking Calendar for you?'); ?>"
			>not_now</textarea>
			<?php //echo 'How it helps you or your business? Is it ease of use? Do you like the Product design ? Value for money...' ; ?>

			<div class="modal-footer modal-footer-inside ui_element" style="justify-content: flex-end;">
				<a id="wpbc_modal__feedback_01__button_next__step_6" class="button button-primary wpbc_btn_next "
				   onclick="javascript: jQuery( '#wpbc_modal__feedback_01__reason_of_action__step_7').val('rate5'); wpbc_ajx_booking__ui_click__submit_feedback_01( this, '.wpbc_modal__feedback_01__step_7');" href="javascript:void(0);"
				  ><?php _e('Yes! Sure, I\'d love to help!' ,'booking');
						?> &nbsp; <i class="menu_icon icon-1x wpbc-bi-emoji-smile"></i></a>

				<a onclick="javascript:  wpbc_ajx_booking__ui_click__submit_feedback_01( this, '.wpbc_modal__feedback_01__step_7');" href="javascript:void(0);"
				   class="button button-secondary wpbc_ui_button"  id="wpbc_modal__feedback_01__submit_5_none"><?php _e('No, sorry - not this time.' ,'booking');
						?>  &nbsp; <i class="menu_icon icon-1x wpbc-bi-emoji-neutral"></i></a>

				<?php /* ?>
				<a id="wpbc_modal__feedback_01__button_next__step_6" class="button button-primary wpbc_btn_next "
				   onclick="javascript: wpbc_ajx_booking__ui_click__send_feedback_01( this, '.wpbc_modal__feedback_01__step_7');" href="javascript:void(0);"
				  ><?php _e('Yes! Sure, I\'d love to help!' ,'booking');
						?> <i class="menu_icon icon-1x wpbc-bi-emoji-smile"></i></a>
 				<?php */ ?>
			</div>

		</div>

		<?php /* Star 5 	Done 	*/ ?>
		<div class="wpbc_modal__feedback_01__steps wpbc_modal__feedback_01__step_7" style="display:none;">
			<h4 class="modal-title"><?php printf(__('%sPerfect!%s Thanks for taking the time! %s' ,'booking')
										,'<strong>','</strong>'
										,'<i class="menu_icon icon-1x wpbc-bi-heart-fill 0wpbc_icn_favorite" style="color: #dd2e44;"></i>' );
			?></h4><br>
			<h4 class="modal-title"><?php _e('Your answers will help us to make the plugin better for you!' ,'booking'); ?></h4><br>
			<h4 class="modal-title"><?php _e('Thanks for sharing your feedback! Have a great day :)' ,'booking'); ?></h4>
			<div class="modal-footer modal-footer-inside ui_element">
				<a class="button button-primary wpbc_ui_button wpbc_ui_button_primary" id="wpbc_modal__feedback_01__submit_5"
				onclick="javascript: wpbc_ajx_booking__ui_click__submit_feedback_01( this, '.wpbc_modal__feedback_01__step_7');" href="javascript:void(0);"
				><span><?php _e('Done' ,'booking'); ?></span>&nbsp; <i class="menu_icon icon-1x wpbc_icn_done_all"></i></a>
			</div>
		</div>
		<?php
	}
}


/**
 * Ajax Response
 *
 * @param $request_params
 * @param $params
 *
 * @return array
 */
function wpbc_booking_do_action__feedback_01( $request_params, $params ) {

	$feedback_stars = intval( $request_params['feedback_stars'] );
	$feedback__note = $request_params['feedback__note'];//esc_textarea( $request_params['feedback__note'] );

	$after_action_result  = true;
	$after_action_message = sprintf( __( 'Thanks for sharing your rating %s', 'booking' ), '<strong style="font-size: 1.1em;">' . $feedback_stars . '</strong>/5' );

	if ( 'remind_later' == $feedback__note ) {

		$after_action_message = __( 'Done', 'booking' );

		// Update new period for showing Feedback
		$feedback_date = date('Y-m-d H:i:s', strtotime( WPBC_FEEDBACK_TIMEOUT, strtotime( 'now' ) ) );
		update_option('booking_feedback_03', $feedback_date );

	} elseif ( ( $feedback_stars > 0 ) && ( $feedback_stars <= 5 ) ) {

		// Send email
		wpbc_feedback_01__send_email( $feedback_stars, $feedback__note );
		update_option('booking_feedback_03_rating', $feedback_stars );

	}

	if ( ( 5 == $feedback_stars ) && ( 'rate5' != $feedback__note ) ) {
		$feedback_stars = 0;
	}

	return array(
		'after_action_result'  => $after_action_result,
		'after_action_message' => $after_action_message,
		'feedback_stars' => $feedback_stars
	);
}


/**
 * Send email with  feedback
 *
 * @param $stars_num
 * @param $feedback_description
 *
 * @return void
 */
function wpbc_feedback_01__send_email( $stars_num, $feedback_description ) {

 	$us_data = wp_get_current_user();

	$fields_values = array();
	$fields_values['from_email'] = get_option( 'admin_email' );
	$fields_values['from_name'] = $us_data->display_name;
	$fields_values['from_name']  = wp_specialchars_decode( esc_html( stripslashes( $fields_values['from_name'] ) ), ENT_QUOTES );
	$fields_values['from_email'] = sanitize_email( $fields_values['from_email'] );

	$subject = 'Booking Calendar Feedback ' . $stars_num . '/5';


	$message = '=============================================' . "\n";
	$message .= $feedback_description . "\n";
	$message .= '=============================================' . "\n";
	$message .="\n";

	$message .= $fields_values['from_name'] . "\n";
	$message .="\n";
	$message  .= 'Booking Calendar Rating: ' . $stars_num . '/5' . "\n";
	$message .= '---------------------------------------------' . "\n";

	$message .= 'Booking Calendar ' . wpbc_feedback_01_get_version()  . "\n";

	global $wpdb;
	$sql = "SELECT modification_date FROM  {$wpdb->prefix}booking as bk ORDER by booking_id  LIMIT 0,1";
	$res = $wpdb->get_results( $sql );
	if ( ! empty( $res ) ) {
		$first_booking_date = wpbc_datetime_localized( date( 'Y-m-d H:i:s', strtotime( $res[0]->modification_date ) ), 'Y-m-d H:i:s' );
		$message .= "\n";
		$message .= 'From: ' . $first_booking_date;

		$dif_days = wpbc_get_difference_in_days( date( 'Y-m-d 00:00:00', strtotime( 'now' ) ), date( 'Y-m-d 00:00:00', strtotime( $res[0]->modification_date ) ) );
		$message .= ' - ' . $dif_days . ' days ago.';
	}

	$message .="\n";
	$message .= '---------------------------------------------' . "\n";
	$message .= '[' .  date_i18n( get_bk_option( 'booking_date_format' ) ) . ' ' .  date_i18n( get_bk_option( 'booking_time_format' ) ) . ']'. "\n";
	$message .= home_url() ;


	$headers = '';

	if ( ! empty( $fields_values['from_email'] ) ) {

		$headers .= 'From: ' . $fields_values['from_name'] . ' <' . $fields_values['from_email'] . '> ' . "\r\n";
	} else {
            /* If we don't have an email from the input headers default to wordpress@$sitename
             * Some hosts will block outgoing mail from this address if it doesn't exist but
             * there's no easy alternative. Defaulting to admin_email might appear to be another
             * option but some hosts may refuse to relay mail from an unknown domain. See
             * https://core.trac.wordpress.org/ticket/5007.
             */
	}
	$headers .= 'Content-Type: ' . 'text/plain' . "\r\n" ;			// 'text/html'

	$attachments = '';


	$to = wpbc_get_feedback_defaults( 'feedback_email' );

// debuge('In email', htmlentities($to), $subject, htmlentities($message), $headers, $attachments)  ;
// debuge( '$to, $subject, $message, $headers, $attachments',htmlspecialchars($to), htmlspecialchars($subject), htmlspecialchars($message), htmlspecialchars($headers), htmlspecialchars($attachments));

	$return = wp_mail( $to, $subject, $message, $headers, $attachments );

}



		/**
		 * Get data about Booking Calendar version. Support function
		 * @return string
		 */
		function wpbc_feedback_01_get_version(){

			if ( substr( WPDEV_BK_VERSION, 0, 3 ) == '10.' ) {
				$show_version = substr( WPDEV_BK_VERSION, 3 );
				if ( substr( $show_version, ( - 1 * ( strlen( WP_BK_VERSION_NUM ) ) ) ) === WP_BK_VERSION_NUM ) {
					$show_version = substr( $show_version, 0, ( - 1 * ( strlen( WP_BK_VERSION_NUM ) ) - 1 ) );
					$show_version = str_replace( '.', ' ', $show_version ) . ' ' . WP_BK_VERSION_NUM;
				}

			} else {
				$show_version = WPDEV_BK_VERSION;
			}

			$ver = wpbc_get_plugin_version_type();
			if ( class_exists( 'wpdev_bk_multiuser' ) ) {
				$ver = 'MultiUser';
			}
			$ver = str_replace( '_m', ' Medium', $ver );
			$ver = str_replace( '_l', ' Large', $ver );
			$ver = str_replace( '_s', ' Small', $ver );
			$ver = str_replace( 'biz', 'Business', $ver );
			$ver = ucwords( $ver );


			$v_type = '';
			if ( strpos( strtolower( WPDEV_BK_VERSION ), 'multisite' ) !== false ) {
				$v_type = '5';
			} else if ( strpos( strtolower( WPDEV_BK_VERSION ), 'develop' ) !== false ) {
				$v_type = '2';
			}
			if ( ! empty( $v_type ) ) {
				$v_type = ' ' . $v_type . ' ' . __( 'websites', 'booking' );
			} else {
				$v_type = ' 1' . ' ' . __( 'website', 'booking' );
			}

			return 	  $ver . ' (' . $show_version . ') '
					. ( ( 'Free' !== $ver ) ?  ' :: ' . $v_type . '.' : '' )
					. ' :: ' 	. date( "d.m.Y", filemtime( WPBC_FILE ) );
		}


		/**
		 * Check if we need to show the Feedback - is it Timer expired already ?
		 * @return bool
		 */
		function wpbc_is__feedback_01__timeout_need_to_show() {

		//return true;

			$feedback_01_date = get_option( 'booking_feedback_03' );

			if (
				( ! empty( $feedback_01_date ) )
				&& ( ( strtotime( 'now' ) - strtotime( $feedback_01_date ) ) > 0 )
			) {
				return true;
			} else {
				return false;
			}

		}



		/**
		 * Set timer to show Feedback after this amount of time
		 * @return void
		 */
		function wpbc_is__feedback_01__timer_install() {

			// If version  of Booking Calendar 9.6 (e.g. 'max_version') or newer than do not show this Feedback
			if ( version_compare( WP_BK_VERSION_NUM, wpbc_get_feedback_defaults( 'max_version' ) , '>=') ) {
				return false;
			}
			$feedback_date = date( 'Y-m-d H:i:s', strtotime( WPBC_FEEDBACK_TIMEOUT, strtotime( 'now' ) ) );
			add_option( 'booking_feedback_03', $feedback_date );
		}
		add_bk_action( 'wpbc_before_activation' , 'wpbc_is__feedback_01__timer_install' );



/**
 * Just for loading CSS and  JavaScript files   and  define Ajax hook
 */
 if (
	   ( ! wpbc_is_this_demo() )
 ) {
	$js_css_loading = new WPBC_Feedback_01;
	$js_css_loading->define_ajax_hook();

	if ( ! empty( get_option( 'booking_feedback_03' ) ) ){
		$js_css_loading->init_load_css_js();
	}
 }