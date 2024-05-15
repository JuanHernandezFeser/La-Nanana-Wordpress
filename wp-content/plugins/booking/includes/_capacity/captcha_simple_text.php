<?php

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly            //FixIn: 9.8.0.4

// ---------------------------------------------------------------------------------------------------------------------
// Main function  for       ajax_ WPBC_AJX_BOOKING__CREATE
// ---------------------------------------------------------------------------------------------------------------------

/**
 * Check CAPTCHA during Ajax request from  booking form      and if it's incorrect,  then STOP execution  and send request  to  show warning
 *
 * @param $request_params           [ 'captcha_user_input'=> '...', 'captcha_chalange'=> '...']
 * @param $is_from_admin_panel      true | false
 * @param $original_ajx_search_params       usually $_REQUEST[ $request_prefix ]
 *
 * @return void                     wp_send_json() TO front-end  or  skip  and continue
 */
function wpbc_captcha__in_ajx__check( $request_params, $is_from_admin_panel , $original_ajx_search_params ) {

    if (
			( 'On' === get_bk_option( 'booking_is_use_captcha' )  )
	     && ( ! $is_from_admin_panel )
		 && ( ( isset( $original_ajx_search_params['captcha_user_input'] ) ) && ( isset( $original_ajx_search_params['captcha_chalange'] ) ) )
	) {

		if ( ! wpbc_captcha__simple__is_ansfer_correct( $request_params['captcha_user_input'], $request_params['captcha_chalange'] ) ) {

			$captcha_arr = wpbc_captcha__simple__generate_new();

			$ajx_data_arr = array();
			$ajx_data_arr['status']       = 'error';
			$ajx_data_arr['status_error'] = 'captcha_simple_wrong';
			$ajx_data_arr['captcha__simple'] = $captcha_arr;
			$ajx_data_arr['ajx_after_action_message']        = __( 'The code you entered is incorrect', 'booking' );
			$ajx_data_arr['ajx_after_action_message_status'] = 'warning';

			wp_send_json( array(
					'ajx_data'              => $ajx_data_arr,
					'ajx_search_params'     => $original_ajx_search_params,
					'ajx_cleaned_params'    => $request_params,
					'resource_id'           => $request_params['resource_id']
				) );
			// After this page will die;
		}
	}
}



// ---------------------------------------------------------------------------------------------------------------------
// CAPTCHA  Support
// ---------------------------------------------------------------------------------------------------------------------

/**
 * Is entered CAPTCHA correct ?
 *
 * @param string $captcha_user_input		user  entrance
 * @param string $captcha_chalange			chalange
 *
 * @return bool
 */
function wpbc_captcha__simple__is_ansfer_correct( $captcha_user_input, $captcha_chalange ) {

	if ( ( empty( $captcha_user_input ) ) || ( empty( $captcha_chalange ) ) ) {
		return false;
	}

	$captcha_instance = new wpdevReallySimpleCaptcha();
	$correct = $captcha_instance->check( $captcha_chalange, $captcha_user_input );

	return $correct;
}


/**
 * Generate new CAPTCHA image and return  URL  to  this image and challenge code
 *
 * @return array		[
							'url'       => $captcha_url,
							'challenge' => $captcha_challenge
						]
 */
function wpbc_captcha__simple__generate_new() {

	$captcha_instance = new wpdevReallySimpleCaptcha();

	// Clean up dead files older than  2 minutes
	$captcha_instance->cleanup( 2 );                    //FixIn: 7.0.1.67

	//$captcha_instance->img_size = array( 72, 24 );
	/* Background color of CAPTCHA image. RGB color 0-255 */
	//$captcha_instance->bg = array( 0, 0, 0 );//array( 255, 255, 255 );
	/* Foreground (character) color of CAPTCHA image. RGB color 0-255 */
	//$captcha_instance->fg = array( 255, 255, 255 );//array( 0, 0, 0 );
	/* Coordinates for a text in an image. I don't know the meaning. Just adjust. */
	//$captcha_instance->base = array( 6, 18 );
	/* Font size */
	//$captcha_instance->font_size = 14;
	/* Width of a character */
	//$captcha_instance->font_char_width = 15;
	/* Image type. 'png', 'gif' or 'jpeg' */
	//$captcha_instance->img_type = 'png';


	$word   = $captcha_instance->generate_random_word();
	$prefix = mt_rand();

	$captcha_instance->generate_image( $prefix, $word );

	$filename    = $prefix . '.png';

	$captcha_url       = WPBC_PLUGIN_URL . '/js/captcha/tmp/' . $filename;
	$captcha_challenge = substr( $filename, 0, strrpos( $filename, '.' ) );

	return array(
					'url'       => $captcha_url,
					'challenge' => $captcha_challenge
				);
}


/**
 * Generate initial HTML content for CAPTCHA in booking form
 *
 * @param $resource_id
 *
 * @return string|true
 */
function wpbc_captcha__simple__generate_html_content( $resource_id ) {

	if ( true !== wpbc_captcha__simple__is_installed() ) {
		return wpbc_captcha__simple__is_installed();
	}

	$captcha_arr = wpbc_captcha__simple__generate_new();

	$captcha_url       = $captcha_arr['url'];
	$captcha_challenge = $captcha_arr['challenge'];
	$html  = '<span class="wpbc_text_captcha_container">';
	$html .=   '<input  autocomplete="off" type="hidden" name="wpdev_captcha_challenge_' . $resource_id . '"  id="wpdev_captcha_challenge_' . $resource_id . '" value="' . $captcha_challenge . '" />';
	$html .=   '<input  autocomplete="off" type="text" class="captachinput" value="" name="captcha_input' . $resource_id . '" id="captcha_input' . $resource_id . '" />';
	$html .=   '<img class="captcha_img"  id="captcha_img' . $resource_id . '" alt="captcha" src="' . $captcha_url . '" />';
	$html .= '</span>';

	return $html;
}


/**
 * Check  if captcha can  work  here
 *
 * @return string|true         if true then  can work,  Otherwise return  error message
 */
function wpbc_captcha__simple__is_installed() {

	//FixIn: 8.8.3.5
	if ( function_exists( 'gd_info' ) ) {

		return  true;
		/*
		$gd_info = gd_info();
		if ( isset( $gd_info['GD Version'] ) ) {
			$gd_info = $gd_info['GD Version'];
		} else {
			$gd_info = json_encode( $gd_info );
		}
		*/
	} else {
		return  '<strong>Error!</strong>  CAPTCHA requires the GD library activated in your PHP configuration.'
		       .'Please check more <a href="https://wpbookingcalendar.com/faq/captcha-showing-problems/">here</a>.';
	}


}