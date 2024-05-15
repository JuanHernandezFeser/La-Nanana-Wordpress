<?php


//FixIn: 9.4.3.6


/**
 * Get html content of " Calendar Legend Items " , based on General Settings configuration.
 *
 * @return string HTML content of legend items
 */
function wpbc_get_calendar_legend() {

	$calendar_legend_html = '';

	if ( get_bk_option( 'booking_is_show_legend' ) == 'On' ) {

		$items_sort = array();

		if ( 'On' == get_bk_option( 'booking_legend_is_show_item_available' ) ) { $items_sort[] = 'available'; }

		if ( 'On' == get_bk_option( 'booking_legend_is_show_item_approved' ) ) {  $items_sort[] = 'approved'; }

		if ( 'On' == get_bk_option( 'booking_legend_is_show_item_pending' ) ) {   $items_sort[] = 'pending'; }

		if (    ( class_exists( 'wpdev_bk_biz_s' ) )
		     && ( 'On' == get_bk_option( 'booking_legend_is_show_item_partially' ) ) ) { $items_sort[] = 'partially'; }

		if ( 'On' == get_bk_option( 'booking_legend_is_show_item_unavailable' ) ) {   $items_sort[] = 'unavailable'; }  //FixIn: 9.9.0.5

		$calendar_legend_html = wpbc_get_calendar_legend__content_html( array(
																			  'is_vertical'       => ( 'On' != get_bk_option( 'booking_legend_is_vertical' ) ) ? false : true
																			, 'text_for_day_cell' => ( 'On' != get_bk_option( 'booking_legend_is_show_numbers' ) ) ? '&nbsp;' : date( 'd' )
																			, 'items'             => $items_sort
																	) );
	}

	return $calendar_legend_html;
}


function wpbc_get_calendar_legend__content_html( $params ) {

	$defaults = array(
					  'is_vertical'         => ( 'On' != get_bk_option( 'booking_legend_is_vertical' ) ) ? false : true
					, 'text_for_day_cell'   => ( 'On' != get_bk_option( 'booking_legend_is_show_numbers' ) )  ? '&nbsp;' : date( 'd' )
					, 'items' => array(
										  'available'
										, 'approved'
										, 'pending'
										, 'partially'
									)
					, 'unavailable_day_cell_tag' => 'span'
					, 'titles' => array()
    			);
    $params   = wp_parse_args( $params, $defaults );


	// Content for Partially  booked item - "time slot" or "change over days"
	if(1){
		$my_partially = '';
		$booking_timeslot_day_bg_as_available = ( 'On' === get_bk_option( 'booking_timeslot_day_bg_as_available' ) ) ? ' wpbc_timeslot_day_bg_as_available' : '';
		$booking_timeslot_day_bg_as_available .= ( 'Off' !== get_bk_option( 'booking_change_over_days_triangles' ) ) ? ' wpbc_change_over_triangle' : '';


		$my_partially .= '<span class="' . $booking_timeslot_day_bg_as_available . '">';
		$my_partially .= '<div class="datepick-inline" style="width:30px !important;border: 0;box-shadow: none;float: left;min-width: 30px;padding: 0;">';     //FixIn: 9.3.1.4
		$my_partially .= '<table class="datepick" style=""><tbody><tr>';
		if ( ( function_exists( 'wpbc_is_booking_used_check_in_out_time' ) ) && ( wpbc_is_booking_used_check_in_out_time() ) ) {                                                   //FixIn: 8.9.4.10
			$my_partially .= '<td class="datepick-days-cell date_available date_approved timespartly check_in_time check_in_time_date_approved" style="height: 30px !important;">';
		} else {
			$my_partially .= '<td class="datepick-days-cell date_available date2approve timespartly times_clock" style="height: 30px !important;">';
		}
		$my_partially .= '<div class="wpbc-cell-box">';
		$my_partially .= '	<div class="wpbc-diagonal-el">';
		$my_partially .= '		<div class="wpbc-co-out"><svg height="100%" width="100%" viewBox="0 0 100 100" preserveAspectRatio="none"><polygon points="0,0 0,99 99,0"></polygon><polygon points="0,0 0,100 49,100 49,0"></polygon></svg></div>';
		$my_partially .= '		<div class="wpbc-co-in"><svg height="100%" width="100%" viewBox="0 0 98 98" preserveAspectRatio="none"><polygon points="0,99 99,99 99,0"></polygon><polygon points="50,98 98,98 98,0 50,0"></polygon></svg></div>';
		$my_partially .= '	</div>';
		$my_partially .= '	<div class="date-cell-content">';
		$my_partially .= '		<div class="date-content-top"><div class="wpbc_time_dots">·</div></div>';
		$my_partially .= '		<a>' . $params['text_for_day_cell'] . '</a>';
		$my_partially .= '		<div class="date-content-bottom"></div>';
		$my_partially .= '	</div>';
		$my_partially .= '</div>';
		$my_partially .= '</td></tr></tbody></table>';
		$my_partially .= '</div>';
		$my_partially .= '</span>';
	}

	// Unavailable
	if (1){
		$my_unavailable = '<div class="datepick-inline" style="width:30px !important;border: 0;box-shadow: none;float: left;min-width: 30px;padding: 0;">';     //FixIn: 9.3.1.4
		$my_unavailable .= '<table class="datepick" style=""><tbody><tr>';
		$my_unavailable .= '<td class="datepick-days-cell datepick-unselectable date_user_unavailable" style="height: 30px !important;">';
		$my_unavailable .= '<div class="wpbc-cell-box">';
		$my_unavailable .= '	<div class="date-cell-content">';
		$my_unavailable .= '		<div class="date-content-top"></div>';
		$my_unavailable .= '		<'.$params['unavailable_day_cell_tag'].'>' . $params['text_for_day_cell'] . '</'.$params['unavailable_day_cell_tag'].'>';
		$my_unavailable .= '		<div class="date-content-bottom"></div>';
		$my_unavailable .= '	</div>';
		$my_unavailable .= '</div>';
		$my_unavailable .= '</td></tr></tbody></table>';
		$my_unavailable .= '</div>';
	}

	// Resource Unavailable
	if (1){
		$my_resource_unavailable = '<div class="datepick-inline" style="width:30px !important;border: 0;box-shadow: none;float: left;min-width: 30px;padding: 0;">';     //FixIn: 9.3.1.4
		$my_resource_unavailable .= '<table class="datepick" style=""><tbody><tr>';
		$my_resource_unavailable .= '<td class="datepick-days-cell resource_unavailable date_user_unavailable" style="height: 30px !important;">';
		$my_resource_unavailable .= '<div class="wpbc-cell-box">';
		$my_resource_unavailable .= '	<div class="date-cell-content">';
		$my_resource_unavailable .= '		<div class="date-content-top"></div>';
		$my_resource_unavailable .= '		<'.$params['unavailable_day_cell_tag'].'>' . $params['text_for_day_cell'] . '</'.$params['unavailable_day_cell_tag'].'>';
		$my_resource_unavailable .= '		<div class="date-content-bottom"></div>';
		$my_resource_unavailable .= '	</div>';
		$my_resource_unavailable .= '</div>';
		$my_resource_unavailable .= '</td></tr></tbody></table>';
		$my_resource_unavailable .= '</div>';
	}



	$items_arr = array(   'available' => array(
											  'title'             => ( ! empty( $params['titles']['available'] ) ) ? $params['titles']['available'] : apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_legend_text_for_item_available' ) )
											, 'text_for_day_cell' => '<a>' . $params['text_for_day_cell'] . '</a>'
											, 'css_class'         => 'block_free datepick-days-cell'
										)
						, 'approved' => array(
											  'title'             => ( ! empty( $params['titles']['approved'] ) ) ? $params['titles']['approved'] : apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_legend_text_for_item_approved' ) )
											, 'text_for_day_cell' => $params['text_for_day_cell']
											, 'css_class'         => 'block_booked date_approved'
										)
						, 'pending' => array(
											  'title'             => ( ! empty( $params['titles']['pending'] ) ) ? $params['titles']['pending'] : apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_legend_text_for_item_pending' ) )
											, 'text_for_day_cell' => $params['text_for_day_cell']
											, 'css_class'         => 'block_pending date2approve'
										)
						, 'partially' => array(
											  'title'             => ( ! empty( $params['titles']['partially'] ) )
												                     ? $params['titles']['partially']
												                     : (
												                            ( ! empty ( apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_legend_text_for_item_partially' ) ) ) )
												                            ? apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_legend_text_for_item_partially' ) )
																			: __( 'Partially booked', 'booking' )
												                       )
											, 'text_for_day_cell' => $my_partially
											, 'css_class'         => ''
										)
						, 'unavailable' => array(                       //FixIn: 9.9.0.5
											  'title'             =>  ( ! empty( $params['titles']['unavailable'] ) )
												                     ? $params['titles']['unavailable']
												                     : (
												                            ( ! empty ( apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_legend_text_for_item_unavailable' ) ) ) )
												                            ? apply_bk_filter( 'wpdev_check_for_active_language', get_bk_option( 'booking_legend_text_for_item_unavailable' ) )
																			: __( 'Unavailable', 'booking' )
												                       )
											, 'text_for_day_cell' => $my_unavailable
											, 'css_class'         => 'datepick-days-cell datepick-unselectable date_user_unavailable'
										)
						, 'resource_unavailable' => array(
											  'title'             =>  ( ! empty( $params['titles']['resource_unavailable'] ) ) ? $params['titles']['resource_unavailable'] : __( 'Resource unavailable days', 'booking' )
											, 'text_for_day_cell' => $my_resource_unavailable
											, 'css_class'         => 'datepick-days-cell resource_unavailable date_user_unavailable'
										)
					);

	$calendar_legend_html = '<div class="block_hints datepick ' . ( ( $params['is_vertical'] ) ? ' block_hints_vertical ' : '' ) . '">';

		foreach ( $params['items'] as $item_name ) {

			if ( ! empty( $items_arr[ $item_name ] ) ) {
				$calendar_legend_html .= '<div class="wpdev_hint_with_text">'
						                    . '<div class="' . $items_arr[ $item_name ]['css_class'] . '">'  . $items_arr[ $item_name ]['text_for_day_cell']  . '</div>'
				                            . '<div class="block_text">'                                     . '- '              . '</div>'
						                    . '<div class="block_text">'                                     . $items_arr[ $item_name ]['title']              . '</div>'
					                   . '</div>';
			}
		}

	$calendar_legend_html .= '</div>';

	return $calendar_legend_html;
}

/**
 * Replace [calendar_legend] shortcode to HTML content in booking form from Booking > Settings > Form page
 *
 * @param string $return_form       -   HTML content of booking form                                    Example: '[calendar] <div class="standard-form"><div class="form-hints-dev"><p>Dates: <span class="dates-hints-dev">[selected_short_dates_hint]</span>([days_number_hint]...'
 * @param int $resource_id          -   ID of booking resource                                          Example: '4'
 * @param string $my_booking_form   -   name of booking form (possible usage of custom booking form)    Example: 'standard'
 *
 * @return array|string|string[]
 *
 * Example of shortcode parameters:
 *                              [legend_items items="available,unavailable,pending,approved,partially" text_for_day_cell="31"]
 *                              [legend_items is_vertical="1"]
 *                              [legend_items]
 *  [legend_items items="resource_unavailable,available,unavailable,pending,approved,partially" text_for_day_cell="31" titles="resource_unavailable={Resource unavailable days} unavailable={Season unavailable}"]
 *
 * PHP code example with  escaping single and double quotes:
 *  echo wpbc_replace_shortcodes_in_booking_form__legend_items( '[legend_items'
																			. ' items="unavailable,available,pending,approved,partially"'
																			. ' titles="'
																						.' unavailable={' . htmlspecialchars(  __( "Unavailable day's", 'booking' ), ENT_QUOTES ) . '}'
																						.' pending={' . htmlspecialchars(  __( "Pending", 'booking' ), ENT_QUOTES ) . '}'
																					 .'"'
																			. ' text_for_day_cell="' . date( 'd' ) . '"'
																			. ' unavailable_day_cell_tag="a"'
															.']' );
 */
function wpbc_replace_shortcodes_in_booking_form__legend_items( $return_form, $resource_id = 1, $my_booking_form = '' ) {

	// $legend__content_html = wpbc_get_calendar_legend();
	//$return_form = str_replace('[legend_items]', $legend__content_html, $return_form);

	$pos = 0;

	while ( false !== strpos( $return_form, '[legend_items ' ) ) {

		$shortcode_params = wpbc_get_params_of_shortcode_in_string( 'legend_items', $return_form , $pos );

		if ( ( isset( $shortcode_params['text_for_day_cell'] ) ) && ( empty( $shortcode_params['text_for_day_cell'] ) ) ) {
			$shortcode_params['text_for_day_cell'] = date( 'd' );
		}

		$shortcode_titles_params = array();
		if ( ! empty( $shortcode_params['titles'] ) ){
			$shortcode_titles_params = wpbc_get_params_of_shortcode_in_string( 'titles', '[titles ' . $shortcode_params['titles'] . ']' , 0 , '%\s*([^=]*)=[{]([^}]*)[}]\s*%' );

			foreach ( $shortcode_titles_params as $title_name => $title_val ) {
				$shortcode_titles_params[ $title_name ] = htmlspecialchars_decode( $title_val );
			}
		}


		$calendar_legend_html = wpbc_get_calendar_legend__content_html( array(
																		      'is_vertical'       => ( ! empty( $shortcode_params['is_vertical'] ) ) ? true : false
																			, 'text_for_day_cell' => ( ! empty( $shortcode_params['text_for_day_cell'] ) )
																									? $shortcode_params['text_for_day_cell']
																									: ( ( 'On' != get_bk_option( 'booking_legend_is_show_numbers' ) ) ? '&nbsp;' : date( 'd' ) )
																			, 'items'             => ( ! empty( $shortcode_params['items'] ) )
																									? explode( ',',$shortcode_params['items'] )
																									: array(
																											  'available'
																											, 'approved'
																											, 'pending'
																											, 'partially'

																										)
																			, 'unavailable_day_cell_tag' => ( ! empty( $shortcode_params['unavailable_day_cell_tag'] ) )
																											? $shortcode_params['unavailable_day_cell_tag']
																											: 'span'
																			, 'titles' => $shortcode_titles_params
																	) );

		$return_form = substr( $return_form, 0, ( $shortcode_params['start'] - 1 ) )
		               . $calendar_legend_html
		               . substr( $return_form, ( $shortcode_params['end'] + 1 ) );

		$pos = $shortcode_params['end'];
	}

	return $return_form;
}
add_filter(  'wpbc_replace_shortcodes_in_booking_form', 'wpbc_replace_shortcodes_in_booking_form__legend_items', 10, 3 );