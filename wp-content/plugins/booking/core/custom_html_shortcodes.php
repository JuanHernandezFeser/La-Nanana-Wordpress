<?php
/*
This is COMMERCIAL SCRIPT
We are not guarantee correct work and support of Booking Calendar, if some file(s) was modified by someone else then wpdevelop.
*/
if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/**
 * HTML shortcode description:
 *
 *  R E P L A C I N G:
 *
 *  <r>     ->      <div class="wpbc__row">
 *  </r>    ->      </div>
 *
 *  <c>     ->      <div class="wpbc__field">
 *  </c>    ->      </div>
 *
 *  <f>     ->      <span class="fieldvalue">
 *  </f>    ->      </span>
 *
 *  <l>     ->      <label>
 *  </l>    ->      </label>
 */

/**
 * Replace Custom HTML shortcodes,  such  as   <r> -> <div class="wpbc__row"> | <c> -> <div class="wpbc__field">     in Booking Form configuration
 *
 * @param $html_original_content
 *
 * @return array|mixed|string|string[]
 */
function wpbc_bf__replace_custom_html_shortcodes( $html_original_content ){

	$html_content_replaced = $html_original_content;

	$replacement_shortcode_arr = array(
										array( 'r', '<div class="wpbc__row">'   , '</div>' ),            // Row
										array( 'c', '<div class="wpbc__field">' , '</div>' ),            // Column
										array( 'f', '<span class="fieldvalue">' , '</span>' ),           // Field  for "Content of booking fields data" form
										array( 'l', '<label>' , '</label>' )                             // Label
								);

	foreach ( $replacement_shortcode_arr as $shortcode_pair_arr ) {
		// Open tags
		if ( ! is_null( $html_content_replaced ) ) {
			$rx_shortcode          = $shortcode_pair_arr[0];                        // 'r';
			$replacement           = $shortcode_pair_arr[1];                        // 'div class="wpbc__row"';
			$regex                 = '%<\s*' . $rx_shortcode . '\s*>%';                                               // ? $regex = '%<\s*(' . $rx_shortcode . ')\s*|\s+[^>]*>%';
			$html_content_replaced = preg_replace( $regex, $replacement, $html_content_replaced );
		}
		// Close tags
		if ( ! is_null( $html_content_replaced ) ) {
			$regex                 = '%<\/\s*' . $rx_shortcode . '\s*>%';
			$replacement           = $shortcode_pair_arr[2];
			$html_content_replaced = preg_replace( $regex, $replacement, $html_content_replaced );
		}
	}

	if ( ! is_null( $html_content_replaced ) ){

		// Replace      <spacer></spacer>   ->   <div style="width:100%;clear:both;"></div>       |       <spacer>height:10px;</spacer>   ->  <div style="width:100%;clear:both;height:10px"></div>
		$html_content_replaced = wpbc_bf__replace_custom_html_shortcode__spacer( $html_content_replaced );

		return $html_content_replaced;
	}

	// Error
	return 'WPBC. Error during replacing custom HTML shortcodes! <br>' .
	       $html_original_content;
}



	/**
	 * Escape booking from with alloweed HTML  atgs + Simple HTML shortcodes
	 * @param $content
	 *
	 * @return string
	 */
	function wpbc_form_escape_in_demo( $content ) {

		// Replace my comments
		$content = str_replace(
							array(
									'<!--  Simple HTML shortcodes in the form (check more at "Generate Tag" section):',
									'Row: <r>...</r> | Columns: <c>...</c> | Labels: <l>...</l> | Spacer: <spacer></spacer> -->'
								), 	'',
								trim( stripslashes( $content ) )
						);

		$value = wp_kses(
							trim( stripslashes( $content ) ),
							array_merge( array(
												'r' => array(),
												'f' => array(),
												'c' => array(),
												'l' => array(),
												'spacer' => array()
							             ),
										 wp_kses_allowed_html( 'post' )
									)
						);
		return $value;
	}


	/**
	 * Replace SPACER shortcode:   <spacer></spacer>   ->   <div style="width:100%;clear:both;"></div>       |       <spacer>height:10px;</spacer>   ->  <div style="width:100%;clear:both;height:10px"></div>
	 *
	 * @param $html_original_content
	 *
	 * @return mixed
	 */
	function wpbc_bf__replace_custom_html_shortcode__spacer( $html_original_content ) {

		$rx_shortcode = 'spacer';
		$regex        = '%<\s*' . $rx_shortcode . '\s*>([-0-9a-zA-Z:;_!\(\)|\s]*)?<\/\s*' . $rx_shortcode . '\s*>%';
		//			$fields_count = preg_match_all( $regex, $html_original_content, $fields_matches, PREG_PATTERN_ORDER );
		//			debuge($fields_count, $fields_matches);
		$html_content_replaced = preg_replace( $regex, '<div class="wpbc__spacer" style="width:100%;clear:both;${1}"></div>' ,$html_original_content );

		if ( ! is_null( $html_content_replaced ) ){
			return $html_content_replaced;
		}

		return $html_original_content;
	}