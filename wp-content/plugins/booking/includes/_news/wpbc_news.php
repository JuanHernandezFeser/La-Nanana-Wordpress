<?php
/**
 * @version 1.0
 * @package Booking Calendar
 * @subpackage Show news
 * @category Support
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2023-04-08
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

//FixIn: 9.6.1.8

/**
 * Show message in top  header
 *
 * @param $page_tag
 * @param $active_page_tab
 * @param $active_page_subtab
 *
 * @return void
 */
function wpbc_show_message_top( $page_tag, $active_page_tab, $active_page_subtab ) {

	if ( wpbc_is_this_demo() ) {
		return;
	}

	$version = wpbc_get_version_type__and_mu();
	//	if ( 'free' != $version ) { return; }

	?>
	<style type="text/css">
		.wpbc_message_wrapper {
			margin-left: auto;
			flex: 0 1 auto;
		}
		.wpbc_header_news {
			position: relative;
			z-index: 99;
		}
	</style><?php
	?><div class="wpbc_message_wrapper <?php echo 'wpbc__version__' . $version ?>"><?php

		// Send Ajax request  to  get info  about the notifications
		wp_nonce_field('wpbc_ajax_message_top_nonce',  "wpbc_ajax_message_top_nonce" ,  true , true );

		?><script type="text/javascript">
		jQuery(document).ready(function(){

			var wpbc_ajax_sent_timer = setTimeout( function (){

					console.log( '== Before Ajax Send - SHOW_MESSAGE_TOP ==' );

					jQuery.ajax({
						url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
						type:'POST',
						success: function (data, textStatus){

							// console.log( 'AJAX _ SHOW_MESSAGE_TOP', data );
							if ( textStatus == 'success' ) {
								jQuery( '.wpbc_message_wrapper' ).html( data[ 'ajx_show_content' ] );
							}
							console.log( '== Response - SHOW_MESSAGE_TOP ==' );
						},
						error: function (XMLHttpRequest, textStatus, errorThrown){
								console.log(  'Ajax sending Error:', XMLHttpRequest, textStatus, errorThrown );
						},
						data:{
							action    : 'SHOW_MESSAGE_TOP',
							wpbc_nonce: document.getElementById( 'wpbc_ajax_message_top_nonce' ).value
						}
					});
			}, 2000 );

		});
		</script><?php

	?></div><?php

}
add_bk_action('wpbc_h1_header_content_end', 'wpbc_show_message_top');


/**
 * Send Ajax request for getting message in top header
 *
 * @return void
 */
function wpbc_ajax__get_message_top(){


	$ajax_errors = new WPBC_AJAX_ERROR_CATCHING();

	// Security  -----------------------------------------------------------------------------------------------    // in Ajax Post:   'nonce': wpbc_ajx_booking_listing.get_secure_param( 'nonce' ),
	$action_name    = 'wpbc_ajax_message_top_nonce';
	$nonce_post_key = 'wpbc_nonce';
	$result_check   = check_ajax_referer( $action_name, $nonce_post_key );

	// $user_id = ( isset( $_REQUEST['wpbc_ajx_user_id'] ) )  ?  intval( $_REQUEST['wpbc_ajx_user_id'] )  :  wpbc_get_current_user_id();

	// Check if there were some errors  --------------------------------------------------------------------------------
	$error_messages = $ajax_errors->get_error_messages();

	$top_message = wpbc_send_request__get_top_message();

	//------------------------------------------------------------------------------------------------------------------
	// Send JSON. Its will make "wp_json_encode" - so pass only array, and This function call wp_die( '', '', array( 'response' => null, ) )		Pass JS OBJ: response_data in "jQuery.post( " function on success.
	wp_send_json( array(
							'ajx_show_content'   => $top_message,
							'ajx_error_messages' => $error_messages
					) );
}
// Ajax Handlers.
add_action( 'wp_ajax_' . 'SHOW_MESSAGE_TOP',    'wpbc_ajax__get_message_top' );       // Admin & Client (logged-in users).    Note. "locale_for_ajax" rechecked in wpbc-ajax.php


/**
 * Get top message
 *
 * @return array|false|mixed|string|string[]
 */
function wpbc_send_request__get_top_message(){

	$is_apply_in_demo = true;

    $v=array();
    if (class_exists('wpdev_bk_personal'))          $v[] = 'wpdev_bk_personal';
    if (class_exists('wpdev_bk_biz_s'))             $v[] = 'wpdev_bk_biz_s';
    if (class_exists('wpdev_bk_biz_m'))             $v[] = 'wpdev_bk_biz_m';
    if (class_exists('wpdev_bk_biz_l'))             $v[] = 'wpdev_bk_biz_l';
    if (class_exists('wpdev_bk_multiuser'))         $v[] = 'wpdev_bk_multiuser';

    $obc_settings = array();
	$ver = get_bk_option( 'bk_version_data' );
    if ( $ver !== false ) { $obc_settings = array( 'subscription_key'=>wp_json_encode($ver) ); }

	$params = array( 'action' => 'get_top_message' );

	$request = new WP_Http();
	$result  = $request->request(   OBC_CHECK_URL . 'info/message_top/'
									// 'http://real.wpbookingcalendar.com/info/message_top/'
									, array(
											'method'  => 'POST',
											'timeout' => 15,
											'body'    => $params
										)
						        );
	if ( ! is_wp_error( $result ) && ( $result['response']['code'] == '200' ) && ( true ) ) {

		$string = ( $result['body'] );                                         //$string = str_replace( "'", '&#039;', $string );

		$shortcodes_arr = wpbc_get_shortcodes_in_text__as_unique_replace(  $string , array( 'wpbc_dismiss' ) );
		$string = $shortcodes_arr['content'];
		$is_panel_visible = false;
		foreach ( $shortcodes_arr['shortcodes'] as $shortcode_text_to_replace => $shortcode_params_arr ) {

			if ( isset( $shortcode_params_arr['params']['id'] ) ) {

				ob_start();
				ob_clean();

				$is_panel_visible = wpbc_is_dismissed(  $shortcode_params_arr['params']['id'], array( 'is_apply_in_demo' => $is_apply_in_demo ) );        // This function also display 'Dismiss' button

				$new_html = ob_get_contents();
                ob_end_clean();

				$string = str_replace( '[' . $shortcode_text_to_replace . ']', $new_html, $string );
			}
		}

		if ( $is_panel_visible ) {
			//$string = str_replace( 'script', 'ajax_script', $string );
			return $string;
		}
	}

	return '';
}