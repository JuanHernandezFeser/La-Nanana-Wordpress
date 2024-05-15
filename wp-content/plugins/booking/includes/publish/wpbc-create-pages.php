<?php
/**
 * @version 1.0
 * @package Booking Calendar
 * @subpackage Create pages Functions
 * @category Functions
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2023-05-10
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/**
 * Create page for Booking Calendar
 *
 * @param array $page_params  = array(
								      'post_content'   => '[bookingedit]',
								      'post_name'      => 'wpbc-booking-edit',
								      'post_title'     => esc_html__('Booking edit','booking')
								)
 *
 * @return false|int - ID of the page.
 */
function wpbc_create_page( $page_params = array() ){                                                                    //FixIn: 9.6.2.10

	global $wp_rewrite;
	if ( is_null( $wp_rewrite ) ) {     //FixIn: 9.7.1.1
		return false;
	}

    // Create post object
    $defaults = array(
      //'menu_order'     => 0,                          // The order the post should be displayed in. Default 0. If new post is a page, it sets the order in which it should appear in the tabs.
      //'pinged'         => '',                         // Space or carriage return-separated list of URLs that have been pinged. Default empty.
      //'post_author'    => get_current_user_id(),      // The user ID number of the author.  The ID of the user who added the post. Default is the current user ID.
      //'post_category'  => array(),                    // Array of category IDs. Defaults to value of the 'default_category' option.   post_category no longer exists, try wp_set_post_terms() for setting a post's categories
      //'post_date'      => [ Y-m-d H:i:s ]             // The date of the post. Default is the current time. The time post was made.
      //'post_date_gmt'  => [ Y-m-d H:i:s ]             // The time post was made, in GMT.
      //'post_excerpt'   => ''                          //The post excerpt. Default empty. For all your post excerpt needs.
      //'post_parent'    => 0,                          // Set this for the post it belongs to, if any. Default 0. Sets the parent of the new post.
      //'post_password'  => '',                         //The password to access the post. Default empty. password for post?
      //'tags_input'     => '',                         // Array of tag names, slugs, or IDs. Default empty. [ '<tag>, <tag>, <...>' ] //For tags.
      //'to_ping'        => '',                         // Space or carriage return-separated list of URLs to ping. Default empty.  Space or carriage return-separated list of URLs to ping.  Default empty.
      //'tax_input'      => '',                         // Default empty. [ array( 'taxonomy_name' => array( 'term', 'term2', 'term3' ) ) ] // support for custom taxonomies.
      'ID'             => 0,                            // The post ID. If equal to something other than 0, the post with that ID will be updated. Default 0.
	  'post_type'      => 'page',                       // The post type. Default 'post'. [ 'post' | 'page' | 'link' | 'nav_menu_item' | 'custom_post_type' ] //You may want to insert a regular post, page, link, a menu item or some custom post type
      'post_status'    => 'publish',                    // [ 'draft' | 'publish' | 'pending'| 'future' | 'private' | 'custom_registered_status' ] // The post status. Default 'draft'. Set the status of the new post.
      'comment_status' => 'closed',                     // [ 'closed' | 'open' ] // 'closed' means no comments.
      'ping_status'    => 'closed',                     // [ 'closed' | 'open' ] // 'closed' means pingbacks or trackbacks turned off
      'post_content'   => '[bookingedit]',              // The post content. Default empty. The full text of the post.
      'post_name'      => 'wpbc-booking-edit',          // sanitize_title( $post_title ), -- By default, converts accent characters to ASCII characters and further limits the output to alphanumeric characters, underscore (_) and dash (-) // The post name. Default is the sanitized post title when creating a new post. The name (slug) for your post
      'post_title'     => esc_html__('Booking edit','booking')  // The post title. Default empty. The title of your post.
    );


	$my_post   = wp_parse_args( $page_params, $defaults );


	$post_id = wp_insert_post( $my_post );                         // Insert the post into the database

	if ( ( ! is_wp_error( $post_id ) ) && ( ! empty( $post_id ) ) ) {

		// Success

		// $post = get_post( $post_id );
		// $post_url = get_permalink( $post_id );

	} else {
		if ( is_wp_error( $post_id ) ) {
			// Error	    //  __( 'Sorry, the post could not be created.' )
		}
		if ( ! $post_id ) {
			// Error	    // __( 'Sorry, the post could not be created.' )
		}
		$post_id = false;
	}

	return $post_id;
}


/**
 * Is shortcode or some text  exist  in specific page
 *
 * @param string $relative_url     relative URL of the page, if we have absolute url, then get it using: wpbc_make_link_relative( 'https://...' );
 * @param string $shortcode_to_add shortcode  to  check '[booking'
 *
 * @return bool
 */
function wpbc_is_shortcode_exist_in_page( $relative_url, $shortcode_to_add ) {

	if ( ! empty( $relative_url ) ) {

		$post_obj = get_page_by_path( $relative_url );

		if ( ! empty( $post_obj ) ) {
			if ( false !== strpos( $post_obj->post_content, $shortcode_to_add ) ) {
				return true;
			}
		}
	}

	return false;
}


/**
 * Add shortcode, if it does not exist yet, to the page
 *
 * @param string $relative_url     relative URL of the page, if we have absolute url, then get it using: wpbc_make_link_relative( 'https://...' );
 * @param string $shortcode_to_add shortcode '[bookingedit]'
 *
 * @return bool
 */
function wpbc_add_shortcode_to_exist_page( $relative_url, $shortcode_to_add ) {

	if ( ! empty( $relative_url ) ) {

		$post_obj = get_page_by_path( $relative_url );

		if ( ! empty( $post_obj ) ) {
			if ( false === strpos( $post_obj->post_content, $shortcode_to_add ) ) {                        // No  such  shortcode in this page. So we need to Add it.

				$my_post = array(
 								  'ID'           => $post_obj->ID,
								  'post_content' => $post_obj->post_content . "\r\n" . $shortcode_to_add
							   );
				wp_update_post( $my_post );                                                                             // Update the post into the database

				return true;
			}
		}
	}

	return false;
}


// ---------------------------------------------------------------------------------------------------------------------

/**
 * Create new page or add to existing page the shortcode
 *
 * @param array $params
 *
 * @return array                success: 	[ 'result' => true,  'relative_url' => $relative_post_url, 'message' => __( 'Booking form shortcode embedded into the page.', 'booking' ) ]
 *                              failed: 	[ 'result' => false, 'message' => __( 'We can not embed booking form shortcode into the page.', 'booking' ) ]
 *
 *  Example:
 *           $result_arr = wpbc_add_shortcode_into_page( array(
 *                                                      'shortcode'  => '[booking resource_id=1]',
 *                                                      'post_title' => 'Booking Form'
 *                                              ) );
 */
function wpbc_add_shortcode_into_page( $params = array() ) {

	$defaults = array(
						'page_post_name'        => '',              // 'wpbc-booking',
						'post_title'            => esc_html( __( 'Booking Form', 'booking' ) ),
						'shortcode'             => '[booking resource_id=1]',
						'check_exist_shortcode' => '[booking',                      // can be an array:  array( '[booking resource_id=1]', '[booking type=1]', '[booking]' )
						'page_id'               => 0,
						'resource_id'           => 0            // Optional
					);
	$params   = wp_parse_args( $params, $defaults );

	if ( empty( $params['page_post_name'] ) ) {
		$params['page_post_name'] = sanitize_title( $params['post_title'] );        // Get slug for the page
	}


	global $wp_rewrite;
	if ( is_null( $wp_rewrite ) ) {
		return array( 'result' => false, 'message' => 'Sorry, we are unable to insert the shortcode into the page. The reason is that wp_rewrite is not initialized.' );
	}
	// -----------------------------------------------------------------------------------------------------------------

	$params['page_id'] = intval( $params['page_id'] );
	if ( ! empty( $params['page_id'] ) ) {
		$wp_post = get_post( $params['page_id'] );
	} else {
		$wp_post = get_page_by_path( $params['page_post_name'] );
	}

	$relative_post_url = '';

	$post_title = '';
	$post_url   = '';
	if ( ! empty( $wp_post ) ) {
		$post_title = $wp_post->post_title;
		$post_url = get_permalink( $wp_post->ID );
	}

	if ( empty( $wp_post ) ) {                                                                                          // No default page.  Create it.

		$page_params = array(
			'post_title'   => $params['post_title'],
			'post_content' => $params['shortcode'],
			'post_name'    => $params['page_post_name']
		);
		$post_id = wpbc_create_page( $page_params );

		if ( ! empty( $post_id ) ) {

			$new_post = get_post( $post_id );
			if ( ! empty( $new_post ) ) {
				$post_title = $new_post->post_title;
				$post_url = get_permalink( $new_post->ID );
			}

			$relative_post_url = wpbc_make_link_relative( get_permalink(  $post_id ) );

			// Scroll to  the booking form.
			if ( ! empty( $params['resource_id'] ) ) {
				$post_url .= '#bklnk' . $params['resource_id'];
			}

			return array( 'result'       => true,
			              'relative_url' => $relative_post_url,
			              'message'      => __( 'A new page has been created.', 'booking' ) . ' ' . sprintf( __( 'The booking form shortcode %s has been embedded into the page %s', 'booking' )
		                                    , "<code class='wpbc_inserted_shortcode_view'>{$params['shortcode']}</code>"
							                , "<a class='wpbc_open_page_as_new_tab' href='" . esc_url( $post_url ) . "'>{$post_title}</a>"
						                    . " <a target='_blank' class='wpbc_open_page_as_new_tab tooltip_top wpbc-bi-arrow-up-right-square' href='" . esc_url( $post_url ) . "' title='" . esc_attr( __( 'Open in new window', 'booking' ) ) . "'></a>"
										)
			);
		}

	} else {
		$relative_post_url = wpbc_make_link_relative( get_permalink(  $wp_post->ID ) );                                          // Page already exist,  so we need to update the
	}

	// Check  if the shortcode in the page
	$is_shortcode_already_in_page = false;
	if ( is_array( $params['check_exist_shortcode'] ) ) {
		foreach ( $params['check_exist_shortcode'] as $check_shortcode ) {
			$is_shortcode_already_in_page = wpbc_is_shortcode_exist_in_page( $relative_post_url, $check_shortcode );
			if ( $is_shortcode_already_in_page ) {
				break;
			}
		}
	} else {
		$is_shortcode_already_in_page = wpbc_is_shortcode_exist_in_page( $relative_post_url, $params['check_exist_shortcode'] );
	}

	// Scroll to  the booking form.
	if ( ! empty( $params['resource_id'] ) ) {
		$post_url .= '#bklnk' . $params['resource_id'];
	}

	// Check  if existing page has our shortcode. We are checking for 'booking'  because it can  be '[booking]' or '[booking type=1]' ...
	if (
		   ( ! $is_shortcode_already_in_page )
		&& ( ! wpbc_is_shortcode_exist_in_page( $relative_post_url, $params['shortcode'] ) )
	) {
		$is_sh_added = wpbc_add_shortcode_to_exist_page( $relative_post_url, $params['shortcode'] );

		if ( $is_sh_added ) {
			return array( 'result'       => true,
			              'relative_url' => $relative_post_url,
			              'message'      => sprintf( __( 'The booking form shortcode %s has been embedded into the page %s', 'booking' )
		                                    , "<code class='wpbc_inserted_shortcode_view'>{$params['shortcode']}</code>"
							                , "<a class='wpbc_open_page_as_new_tab' href='" . esc_url( $post_url ) . "'>{$post_title}</a>"
						                    . " <a target='_blank' class='wpbc_open_page_as_new_tab tooltip_top wpbc-bi-arrow-up-right-square' href='" . esc_url( $post_url ) . "' title='" . esc_attr( __( 'Open in new window', 'booking' ) ) . "'></a>"

										)
			);

		} else {
			return array( 'result'       => false,
			              'message'      => sprintf( __( 'We are unable to embed the booking form shortcode %s into the page %s.', 'booking' )
		                                    , "<code class='wpbc_inserted_shortcode_view'>{$params['shortcode']}</code>"
							                , "<a class='wpbc_open_page_as_new_tab' href='" . esc_url( $post_url ) . "'>{$post_title}</a>"
						                    . " <a target='_blank' class='wpbc_open_page_as_new_tab tooltip_top wpbc-bi-arrow-up-right-square' href='" . esc_url( $post_url ) . "' title='" . esc_attr( __( 'Open in new window', 'booking' ) ) . "'></a>"
										)
			);
		}
	} else {

		return array( 'result'       => false,
		              'message'      => sprintf( __( 'The Booking Calendar shortcode %s is already present on this page %s.', 'booking' )
		                                    , "<code class='wpbc_inserted_shortcode_view'>{$params['shortcode']}</code>"
							                , "<a class='wpbc_open_page_as_new_tab' href='" . esc_url( $post_url ) . "'>{$post_title}</a>"
						                    . " <a target='_blank' class='wpbc_open_page_as_new_tab tooltip_top wpbc-bi-arrow-up-right-square' href='" . esc_url( $post_url ) . "' title='" . esc_attr( __( 'Open in new window', 'booking' ) ) . "'></a>"
										)
		);

	}

}


// ---------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------
// ---------------------------------------------------------------------------------------------------------------------


/**
 * Create new starter page with  booking form
 *
 * @param $default_options_to_add       - array from function wpbc_get_default_options(),  which  passed only  from 'wpbc_before_activation__add_options'. Here we skip  this option.
 *
 * @return void
 */
function wpbc_create_page_with_booking_form( $default_options_to_add = array() ) {                                                        //FixIn: 9.6.2.11

	global $wp_rewrite;
	if ( is_null( $wp_rewrite ) ) {                                                                                     //FixIn: 9.7.1.1

		// Maybe it was not init,  yet
		if ( ! has_action( 'init', 'wpbc_create_page_with_booking_form' ) ) {
			add_action( 'init', 'wpbc_create_page_with_booking_form', 99 );                                             // <- priority  to  load it last
		}
		return false;
	}

	// -----------------------------------------------------------------------------------------------------------------

	$wp_post = get_page_by_path( 'wpbc-booking' );

	$post_url = '';

	if ( empty( $wp_post ) ) {                                                                                          // No default page.  Create it.

		$page_params = array(
			'post_title'   => esc_html( __( 'Booking Form', 'booking' ) ),
			'post_content' => '[booking resource_id=1]',
			'post_name'    => 'wpbc-booking'
		);
		$post_id = wpbc_create_page( $page_params );

		if ( ! empty( $post_id ) ) {
			$post_url = wpbc_make_link_relative( get_permalink(  $post_id ) );
		}

	} else {
		$post_url = wpbc_make_link_relative( get_permalink(  $wp_post->ID ) );                                          // Page already exist,  so we need to update the
	}

	// -----------------------------------------------------------------------------------------------------------------

	// Check  if existing page has our shortcode. We are checking for 'booking'  because it can  be '[booking]' or '[booking type=1]' ...
	if ( ! wpbc_is_shortcode_exist_in_page( $post_url, '[booking' ) ) {
		$is_sh_added = wpbc_add_shortcode_to_exist_page( $post_url, '[booking resource_id=1]' );
	}

}
add_bk_action( 'wpbc_before_activation__add_options', 'wpbc_create_page_with_booking_form' );

// ---------------------------------------------------------------------------------------------------------------------


/**
 * Create new page and get  URL  of this page
 *
 * @param $default_options_to_add
 *
 * @return void
 */
function wpbc_create_page_thank_you( $default_options_to_add ) {                                                        //FixIn: 9.6.2.11

	global $wp_rewrite;
	if ( is_null( $wp_rewrite ) ) {                                                                                     //FixIn: 9.7.1.1

		// Maybe it was not init,  yet
		if ( ! has_action( 'init', 'wpbc_create_page_thank_you' ) ) {
			add_action( 'init', 'wpbc_create_page_thank_you', 99 );                                                     // <- priority  to  load it last
		}
		return false;
	}

	// -----------------------------------------------------------------------------------------------------------------

	$thank_you_page_url = get_bk_option( 'booking_thank_you_page_URL' );

	if (   ( empty( $thank_you_page_url ) )                                                                             // If   No 'Thank you'   page in Settings, or it's set as Empty.
		|| (
				( '/thank-you' == wpbc_make_link_relative( get_bk_option( 'booking_thank_you_page_URL' ) ) )
			 && ( empty( get_page_by_path( 'thank-you' ) ) )                                                            //FixIn: 9.9.0.27
	       )
		|| ( '/' == wpbc_make_link_relative( get_bk_option( 'booking_thank_you_page_URL' ) ) )
	){
		$wp_post = get_page_by_path( 'wpbc-booking-received' );

		$post_url = '';

		if ( empty( $wp_post ) ) {                                                                                      // No default page.  Create it.

			$page_params = array(
				'post_title'   => esc_html( __( 'Booking Received', 'booking' ) ),
				'post_content' => esc_html( __( 'Thank you for your booking. Your booking has been successfully received.', 'booking' ) )
                                 .  "\r\n" . ' [booking_confirm]',
				'post_name'    => 'wpbc-booking-received'
			);
			$post_id = wpbc_create_page( $page_params );

			if ( ! empty( $post_id ) ) {
				$post_url = wpbc_make_link_relative( get_permalink(  $post_id ) );
			}

		} else {
			$post_url = wpbc_make_link_relative( get_permalink(  $wp_post->ID ) );                                      // Page already exist,  so we need to update the
		}

		if ( ! empty( $post_url ) ) {
			update_bk_option( 'booking_thank_you_page_URL', $post_url );
		}
	}

	// -----------------------------------------------------------------------------------------------------------------

	// Check  if existing page has our shortcode
	$relative_url = wpbc_make_link_relative( get_bk_option( 'booking_thank_you_page_URL' ) );
	$is_sh_added  = wpbc_add_shortcode_to_exist_page( $relative_url, '[booking_confirm]' );

}
add_bk_action( 'wpbc_before_activation__add_options', 'wpbc_create_page_thank_you' );



/**
 * Create pages for [bookingedit]  and [bookingcustomerlisting] shortcodes,
 *
 * if previously was not defined options
 * 'booking_url_bookings_edit_by_visitors' and 'booking_url_bookings_listing_by_customer'
 * to some pages different from homepage.
 *
 * @return void
 */
function wpbc_create_page_bookingedit(){                                                                                //FixIn: 9.6.2.10

	global $wp_rewrite;
	if ( is_null( $wp_rewrite ) ) {     //FixIn: 9.7.1.1
		// Maybe it was not init,  yet
		if ( ! has_action( 'init', 'wpbc_create_page_bookingedit' ) ) {
			add_action( 'init', 'wpbc_create_page_bookingedit', 99 );                                                     // <- priority  to  load it last
		}
		return false;
	}

	// Booking Edit page - set  default page and URL ---------------------------------------------------------------
	$url_booking_edit = get_bk_option( 'booking_url_bookings_edit_by_visitors' );
	if (
		   ( site_url() == $url_booking_edit )
		&& ( empty( get_page_by_path( 'wpbc-my-booking' ) ) )
	){
		$page_params = array(
			'post_content' => '[bookingedit]',                          // The post content
			'post_name'    => 'wpbc-my-booking',                        // sanitize_title( $post_title )
			'post_title'   => esc_html__( 'My Booking', 'booking' )     // Title
		);

		$post_id = wpbc_create_page( $page_params );

		if ( ! empty( $post_id ) ) {
			$post_url = get_permalink( $post_id );
			update_bk_option( 'booking_url_bookings_edit_by_visitors', $post_url );
		}
	} else if (
				(
					( site_url() == $url_booking_edit )
				 || ( empty( $url_booking_edit ) )
				)
			    && ( ! empty( get_page_by_path( 'wpbc-my-booking' ) ) )
	){
		$wp_post = get_page_by_path( 'wpbc-my-booking' );
		if ( ! empty( $wp_post ) ) {
			update_bk_option( 'booking_url_bookings_edit_by_visitors', get_permalink(  $wp_post->ID ) );
		}
	}

	// -----------------------------------------------------------------------------------------------------------------
	// Check  if existing page has our shortcode
	// -----------------------------------------------------------------------------------------------------------------
	$booking_edit_relative_url = wpbc_make_link_relative( get_bk_option( 'booking_url_bookings_edit_by_visitors' ) );
	$is_added = wpbc_add_shortcode_to_exist_page( $booking_edit_relative_url, '[bookingedit]' );


	// =================================================================================================================
	// =================================================================================================================
	// =================================================================================================================


	// Booking Listing page - set  default page and URL ---------------------------------------------------------------
	$url_booking_edit = get_bk_option( 'booking_url_bookings_listing_by_customer' );
	if (
		   ( site_url() == $url_booking_edit )
		&& ( empty( get_page_by_path( 'wpbc-my-bookings-listing' ) ) )
	){

		$page_params = array(
			'post_content' => '[bookingcustomerlisting]',                           // The post content
			'post_name'    => 'wpbc-my-bookings-listing',                           // sanitize_title( $post_title )
			'post_title'   => esc_html__( 'My Bookings Listing', 'booking' )                // Title
		);

		$post_id = wpbc_create_page( $page_params );

		if ( ! empty( $post_id ) ) {
			$post_url = get_permalink( $post_id );
			update_bk_option( 'booking_url_bookings_listing_by_customer', $post_url );
		}
	} else if (
				(
					( site_url() == $url_booking_edit )
				 || ( empty( $url_booking_edit ) )
				)
			    && ( ! empty( get_page_by_path( 'wpbc-my-bookings-listing' ) ) )
	){
		$wp_post = get_page_by_path( 'wpbc-my-bookings-listing' );
		if (  ! empty( $wp_post )  ){
			update_bk_option( 'booking_url_bookings_listing_by_customer', get_permalink(  $wp_post->ID ) );
		}
	}


	// -----------------------------------------------------------------------------------------------------------------
	// Check  if existing page has our shortcode
	// -----------------------------------------------------------------------------------------------------------------
	$booking_listing_relative_url = wpbc_make_link_relative( get_bk_option( 'booking_url_bookings_listing_by_customer' ) );
	$is_added = wpbc_add_shortcode_to_exist_page( $booking_listing_relative_url, '[bookingcustomerlisting]' );

}


function wpbc_create_page_booking_payment_status(){                                                                     //FixIn: 9.6.2.13

	global $wp_rewrite;
	if ( is_null( $wp_rewrite ) ) {     //FixIn: 9.7.1.1
		return false;
	}

	// Successful Payment page options ---------------------------------------------------------------------------------

	$post_url_relative = false;
	$slug              = 'wpbc-booking-payment-successful';
	if ( empty( get_page_by_path( $slug ) ) ) {
		// Create page
		$page_params = array(
			'post_title'   => esc_html( __( 'Booking Payment Confirmation', 'booking' ) ),
			'post_content' => esc_html( __( 'Thank you for your booking. Your payment for the booking has been successfully received.', 'booking' ) ),
			'post_name'    => $slug
		);

		$post_id = wpbc_create_page( $page_params );

		if ( ! empty( $post_id ) ) {
			$post_url          = get_permalink( $post_id );
			$post_url_relative = str_replace( site_url(), '', $post_url );
		}
	}
	if ( ! empty( $post_url_relative ) ) {

		//$post_url_relative = wpbc_make_link_absolute( $post_url_relative );

		$payment_systems = array(
			'booking_stripe_v3_order_successful',
			'booking_paypal_return_url',
			'booking_authorizenet_order_successful',
			'booking_sage_order_successful',
			'booking_ideal_return_url',
			'booking_ipay88_return_url'
		);

		foreach ( $payment_systems as $payment_system ) {

			if (
				    ( false === get_bk_option( $payment_system ) )
			     || ( '/successful' == wpbc_make_link_relative( get_bk_option( $payment_system ) ) )
			) {
				update_bk_option( $payment_system, wpbc_make_link_relative( $post_url_relative ) );
			}
		}
	}

	// Failed Payment page options -------------------------------------------------------------------------------------

	$post_url_relative = false;
	$slug              = 'wpbc-booking-payment-failed';
	if ( empty( get_page_by_path( $slug ) ) ) {
		// Create page
		$page_params = array(
			'post_title'   => esc_html( __( 'Booking Payment Failed', 'booking' ) ),
			'post_content' => esc_html( __( 'Payment Unsuccessful. Please contact us for assistance.', 'booking' ) ),
			'post_name'    => $slug
		);

		$post_id = wpbc_create_page( $page_params );

		if ( ! empty( $post_id ) ) {
			$post_url          = get_permalink( $post_id );
			$post_url_relative = str_replace( site_url(), '', $post_url );
		}
	}
	if ( ! empty( $post_url_relative ) ) {

		//$post_url_relative = wpbc_make_link_absolute( $post_url_relative );

		$payment_systems = array(
			'booking_stripe_v3_order_failed',
			'booking_paypal_cancel_return_url',
			'booking_authorizenet_order_failed',
			'booking_sage_order_failed',
			'booking_ideal_cancel_return_url',
			'booking_ipay88_cancel_return_url'
		);

		foreach ( $payment_systems as $payment_system ) {

			if (
				    ( false === get_bk_option( $payment_system ) )
			     || ( '/failed' == wpbc_make_link_relative( get_bk_option( $payment_system ) ) )
			) {
				update_bk_option( $payment_system, wpbc_make_link_relative( $post_url_relative ) );
			}
		}
	}
}