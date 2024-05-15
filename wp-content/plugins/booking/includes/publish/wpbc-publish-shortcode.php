<?php
/**
 * @version 1.0
 * @package Booking Calendar
 * @subpackage Publish  Booking Calendar shortcodes into the Pages
 * @category Functions
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2023-12-27
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.15.5


/**
 * Get prepared (for WP Blocks) shortcode of booking form  for inserting into the post  or page
 *
 * @param int $resource_id
 *
 * @return string
 */
function wpbc_get_prepared_shortcode( $resource_id = 1 ) {

	$resource_id = intval( $resource_id );

	$shortcode = '';
	if ( ! empty( $resource_id ) ) {

//		$shortcode .= '<!-- wp:booking/booking {"wpbc_shortcode":"[booking resource_id=' . $resource_id . ' nummonths=1]"} -->';
//		$shortcode .= '<div class="wp-block-booking-booking">[booking resource_id=' . $resource_id . ' nummonths=1]</div>';
//		$shortcode .= '<!-- /wp:booking/booking -->';

		$shortcode .= '<!-- wp:shortcode -->';
		$shortcode .= '[booking resource_id=' . $resource_id . ' nummonths=1]';
		$shortcode .= '<!-- /wp:shortcode -->';
	}

	return $shortcode;
}


/**
 * Submit | Update checking
 *
 * @param $page_name
 *
 * @return false|void
 */
function wpbc_check_for_submit__page_resource_publish( $page_name ) {

	if ( 'resources' !== $page_name ) {
		return false;
	}

	// Check $_POST

	if ( ( isset( $_POST['action'] ) ) && ( 'wpbc_page_resource_publish' === $_POST['action'] ) ) {

		if ( wpbc_is_this_demo() ) {
			wpbc_show_notice__for_page_resource_publish( 'This operation is restricted in the demo version.', 'warning' );
			return;
		}

		$nonce_gen_time = check_admin_referer( 'set_resource_publish_check'  );  										// It stops show anything on submitting, if it not refers to the original page

		$add_shortcode_result_arr = false;

		// CREATE NEW PAGE
		if(
			   ( 'create' === $_POST['wpbc_page_resource_publish_what'] )
			&& ( ! empty($_POST['create_page_for_resource_publish'] ) )
			&& ( ! empty($_POST['wpbc_page_resource_publish_resource_id'] ) )
		){
			$shortcode_resource_id = intval( $_POST['wpbc_page_resource_publish_resource_id'] );
			$page_name               = $_POST['create_page_for_resource_publish'];

			if ( ! empty( $_POST['wpbc_page_resource_publish_resource_shortcode'] ) ) {
				//$insert_shortcode = WPBC_Settings_API::validate_textarea_post_static( 'wpbc_page_resource_publish_resource_shortcode' );
				$insert_shortcode = wp_kses(   trim( stripslashes( $_POST[ 'wpbc_page_resource_publish_resource_shortcode' ] ) ),
											   array_merge(    array( 		  //    'iframe' => array( 'src' => true, 'style' => true, 'id' => true, 'class' => true )
																			  //	, 'script' => array( 'type' => true )       // Allow JS
																),
																wp_kses_allowed_html( 'post' )
														)
											);
				$insert_shortcode = '<!-- wp:shortcode -->' . $insert_shortcode .'<!-- /wp:shortcode -->';
			} else {
				// auto_generated_shortcode
				$insert_shortcode = wpbc_get_prepared_shortcode( $shortcode_resource_id );
			}


			// Add shortcode to  specific Page with  POST ID
			$add_shortcode_result_arr = wpbc_add_shortcode_into_page( array(
																		'shortcode' => $insert_shortcode,
																		'check_exist_shortcode' => '[WPBC_' . microtime() . '_WPBC]',  // Just add Fake shortcode,  so  we always add new shortcode  to  the page

																		'page_post_name' => sanitize_title( $page_name ),
																		'post_title'     => esc_html( $page_name ),
																		'resource_id'    => $shortcode_resource_id
																	) );
		}

		// ADD TO EXIST PAGE
		if(
			   ( 'edit' === $_POST['wpbc_page_resource_publish_what'] )
			&& ( ! empty($_POST['select_page_for_resource_publish'] ) )
			&& ( ! empty($_POST['wpbc_page_resource_publish_resource_id'] ) )
		){
			$shortcode_resource_id = intval( $_POST['wpbc_page_resource_publish_resource_id'] );
			$page_id               = intval( $_POST['select_page_for_resource_publish'] );

			// Add shortcode to  specific Page with  POST ID
			$check_exist_shortcode_arr = array(
				'[booking resource_id=' . $shortcode_resource_id . ' ',
				'[booking resource_id=' . $shortcode_resource_id . ']',
				'[booking type=' . $shortcode_resource_id . ' ',
				'[booking type=' . $shortcode_resource_id . ']'
			);
			if ( 1 === $shortcode_resource_id ) {
				$check_exist_shortcode_arr[] = '[booking]';
			}


			if ( ! empty( $_POST['wpbc_page_resource_publish_resource_shortcode'] ) ) {
				//$insert_shortcode = WPBC_Settings_API::validate_textarea_post_static( 'wpbc_page_resource_publish_resource_shortcode' );
				$insert_shortcode = wp_kses(   trim( stripslashes( $_POST[ 'wpbc_page_resource_publish_resource_shortcode' ] ) ),
											   array_merge(    array( 		  //    'iframe' => array( 'src' => true, 'style' => true, 'id' => true, 'class' => true )
																			  //	, 'script' => array( 'type' => true )       // Allow JS
																),
																wp_kses_allowed_html( 'post' )
														)
											);
				$insert_shortcode = '<!-- wp:shortcode -->' . $insert_shortcode .'<!-- /wp:shortcode -->';
			} else {
				// auto_generated_shortcode
				$insert_shortcode = wpbc_get_prepared_shortcode( $shortcode_resource_id );
			}

			$add_shortcode_result_arr = wpbc_add_shortcode_into_page( array(
																		'shortcode' => $insert_shortcode,
																		'page_id' 	=> $page_id,
																		'check_exist_shortcode' => $check_exist_shortcode_arr,  //'[WPBC_' . microtime() . '_WPBC]'  // Just add Fake shortcode,  so  we always add new shortcode  to  the page
																		'resource_id'    => $shortcode_resource_id
																	) );
		}

		// Show Result Message
		if (
				( ! empty( $add_shortcode_result_arr ) )
			 && ( $add_shortcode_result_arr['result'] )
			 && ( ! empty( $add_shortcode_result_arr['relative_url'] ) )
		){
			$relative_post_url = $add_shortcode_result_arr['relative_url'];
			$post_title        = trim( $relative_post_url, '/' );
			$post_obj   = get_page_by_path( $relative_post_url );
			if ( ! empty( $post_obj ) ) {
				$post_title = $post_obj->post_title;
			}
			wpbc_show_notice__for_page_resource_publish( $add_shortcode_result_arr['message']
														// . ' ' . '<a href="' . esc_url( wpbc_make_link_absolute( $relative_post_url ) ) . '">' . $post_title . '</a>'
														);

		} elseif ( false === $add_shortcode_result_arr ) {
			wpbc_show_notice__for_page_resource_publish( 'Error: You may not have chosen the correct page name.', 'warning' );
			wpbc_show_notice__for_page_resource_publish(
														sprintf(	__('Find more information at the %sFAQ page%s','booking'),
																	'<a href="https://wpbookingcalendar.com/faq/#shortcodes">', '</a>'
														), 'info');
		}else {
			wpbc_show_notice__for_page_resource_publish( $add_shortcode_result_arr['message'], 'warning' );
			wpbc_show_notice__for_page_resource_publish(
														sprintf(	__('Find more information at the %sFAQ page%s','booking'),
																	'<a href="https://wpbookingcalendar.com/faq/#shortcodes">', '</a>'
														), 'info');
		}

	}
}
add_action( 'wpbc_hook_settings_page_before_content_table', 'wpbc_check_for_submit__page_resource_publish' ,10, 1);


function wpbc_show_notice__for_page_resource_publish( $message, $message_type='success'){
    ?>
	<div class="wpbc-settings-notice notice-<?php echo $message_type ?>" style="text-align:left;font-size: 1rem;margin-top:20px;">
		<strong><?php echo ( ( 'error' == $message_type ) ? ( __('Error' ,'booking') . '! ' ) : '' ); ?></strong> <?php
			echo $message;
		?>
	</div>
	<?php
}


/** Publish Layout - Modal Window structure */
function wpbc_write_content_for_modal__page_resource_publish( $page_name ) {

	if ( 'resources' !== $page_name ) { return false; }

	?><span class="wpdevelop"><?php

	  ?><div id="wpbc_modal__resource_publish" class="modal wpbc_popup_modal" tabindex="-1" role="dialog">
		<style type="text/css">
			#wpbc_modal__resource_publish .modal-header .modal-title {
				font-weight: 600;
			}
			.wpbc_popup_modal .form-table input[type="radio"]{
					margin: -4px 4px 0 0;
			}
			.wpbc_publish_wizard_steps {
				display: none;
			}
			.wpbc_publish_wizard_step_1 {
				display: block;
			}
			.wpbc_publish_wizard_steps .wpbc_publish_wizard_inner_header {
				line-height: 1.75em;
				text-align: center;
				font-size: 16px;
				font-weight: 400;
				max-width: 30em;
				margin: 0 auto;
				margin-bottom: 15px;
			}
			.wpbc_publish_wizard_steps__buttons,
			.wpbc_publish_wizard_steps__inputs {
				display: flex;
				flex-flow: row wrap;
				justify-content: center;
				align-items: center;
				margin:10px 0;
			}
			#wpbc_modal__resource_publish .wpbc_publish_wizard_steps__buttons a{
				margin: 0 10px;
			}
			#wpbc_modal__resource_publish .modal-footer {
				display: none;
				text-align: center;
			}
			.wp-core-ui .wpbc_page .wpbc_publish_wizard_steps__inputs input[type='text'],
			.wpbc_publish_wizard_steps__inputs input[type='text'],
			.wpbc_publish_wizard_steps__inputs select {
				width: Min(100%,25em);
				max-width: Min(100%,25em);
				margin: 5px 10px 10px;
				min-height: 30px;
			}
			.wpbc_publish_wizard_steps__inputs input[type='submit'] {
				margin: 5px 10px 10px;
			}
		</style>
		  <div class="modal-dialog modal-lg0">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><?php _e( 'Insert into page' ); ?></h4>
				</div>
				<div class="modal-body">
					<div id="wpbc_content_for_js_resource_publish">
						<?php
						if ( wpbc_is_this_demo() ) {
							wpbc_show_notice__for_page_resource_publish( 'In the demo versions such operation is not allowed.', 'warning' );
						} else {
						?>
						<form method="post" action="">
							<input type="hidden" name="action" value="wpbc_page_resource_publish" />
							<input type="hidden" id="wpbc_page_resource_publish_resource_id" name="wpbc_page_resource_publish_resource_id" value="" />
							<input type="hidden" id="wpbc_page_resource_publish_resource_shortcode" name="wpbc_page_resource_publish_resource_shortcode" value="" />
							<input type="hidden" id="wpbc_page_resource_publish_what" name="wpbc_page_resource_publish_what" value="" />
							<?php
								wp_nonce_field( 'set_resource_publish_check' );
							?>
							<div class="wpbc_publish_wizard_steps wpbc_publish_wizard_step_1">
								<div class="wpbc_publish_wizard_inner_header"><?php _e('Choose whether to embed your booking form in an existing page or create a new one.', 'booking'); ?></div>
								<div class="wpbc_publish_wizard_steps__buttons">
									<a href="javascript:void(0)" class="button button-secondary"
									   onclick="javascript:jQuery( '.wpbc_publish_wizard_steps').hide();
									   					   jQuery( '.wpbc_publish_wizard_step_2').show();
									   					   jQuery( '#wpbc_modal__resource_publish .modal-footer').show();
														   jQuery( '#wpbc_page_resource_publish_resource_shortcode' ).val( jQuery( '#booking_resource_shortcode_' + jQuery( '#wpbc_page_resource_publish_resource_id' ).val() ).val() );
														   jQuery( '#wpbc_page_resource_publish_what' ).val( 'edit' );"
										><?php _e('Embed in Existing Page','booking') ?></a>
									<a href="javascript:void(0)" class="button button-secondary"
									   onclick="javascript:jQuery( '.wpbc_publish_wizard_steps').hide();
									   					   jQuery( '.wpbc_publish_wizard_step_3').show();
									   					   jQuery( '#wpbc_modal__resource_publish .modal-footer').show();
														   jQuery( '#wpbc_page_resource_publish_resource_shortcode' ).val( jQuery( '#booking_resource_shortcode_' + jQuery( '#wpbc_page_resource_publish_resource_id' ).val() ).val() );
														   jQuery( '#wpbc_page_resource_publish_what' ).val( 'create' );"
										><?php _e('Create New Page','booking') ?></a>
								</div>
							</div>
							<div class="wpbc_publish_wizard_steps wpbc_publish_wizard_step_2">
								<div class="wpbc_publish_wizard_inner_header"><?php _e('Select the page where you want to embed your booking form.', 'booking'); ?></div>
								<div class="wpbc_publish_wizard_steps__inputs">
									<?php
										wp_dropdown_pages(
											array(
												'name'              => 'select_page_for_resource_publish',
												'show_option_none'  => __( '&mdash; Select &mdash;' ),
												'option_none_value' => '0',
												'selected'          => 0,//$privacy_policy_page_id,
												'post_status'       => array( 'draft', 'publish' ),
											)
										);
										submit_button( __( 'Use This Page' ), 'primary', 'submit', false, array( 'id' => 'set-page' ) );
									?>
								</div>
							</div>
							<div class="wpbc_publish_wizard_steps wpbc_publish_wizard_step_3">
								<div class="wpbc_publish_wizard_inner_header"><?php _e('Provide a name for your new page.', 'booking'); ?></div>
								<div class="wpbc_publish_wizard_steps__inputs">
									<input id="create_page_for_resource_publish" name="create_page_for_resource_publish" type="text" value=""
										   placeholder="<?php echo esc_attr( __( 'Enter Page Name', 'booking' ) ); ?>"/>
									<?php

										submit_button( __( 'Create Page' ), 'primary', 'submit', false, array( 'id' => 'set-page' ) );
									?>
								</div>
							</div>
 						</form>
						<?php } ?>
					</div>
				</div>
				<div class="modal-footer">
					<!--a href="javascript:void(0)" class="button button-secondary" data-dismiss="modal"><?php _e('Close' ,'booking'); ?></a-->
					<a id="wpbc_modal__go_back_button" class="button button-secondary"
					   href="javascript:void(0);"
					   onclick="javascript:jQuery( '.wpbc_publish_wizard_steps').hide();
										   jQuery( '.wpbc_publish_wizard_step_1').show();
										   jQuery( '#wpbc_modal__resource_publish .modal-footer').hide();"
					  ><i class="menu_icon icon-1x wpbc_icn_keyboard_arrow_left"></i> <?php _e('Go Back' ,'booking'); ?></a>
				</div>
			</div><!-- /.modal-content -->
		  </div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
		<?php

	?></span><?php


	?><script type="text/javascript">
		function wpbc_modal_dialog__show__resource_publish( booking_id ){

			if ( 'function' === typeof (jQuery( '#wpbc_modal__resource_publish' ).wpbc_my_modal) ){
				jQuery( '#wpbc_modal__resource_publish' ).wpbc_my_modal( 'show' );
				jQuery( '#wpbc_page_resource_publish_resource_id' ).val(  booking_id );
				jQuery( '.wpbc_publish_wizard_steps').hide();
				jQuery( '.wpbc_publish_wizard_step_1').show();
				jQuery( '#wpbc_modal__resource_publish .modal-footer').hide();
			} else {
				alert( 'Warning! Modal module( wpbc_my_modal ) had not define.' )
			}
		}
	</script><?php
}
add_action( 'wpbc_hook_settings_page_footer', 'wpbc_write_content_for_modal__page_resource_publish' ,10, 1);
