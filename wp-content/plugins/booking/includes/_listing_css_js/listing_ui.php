<?php /**
 * @version 1.0
 * @description Listing
 * @category  Contacts Listings
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2020-02-10
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

class WPBC_Listing {

	/**
	 * Define HOOKs for loading CSS and  JavaScript files
	 */
	public function init_load_css_js() {
		// JS & CSS
		add_action( 'wpbc_enqueue_js_files',  array( $this, 'js_load_files' ),     50  );
		add_action( 'wpbc_enqueue_css_files', array( $this, 'enqueue_css_files' ), 50  );
	}

	/** JSS */
	public function js_load_files( $where_to_load ) {

		$in_footer = true;

		if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

			wp_enqueue_script( 'wpbc-listing_class', trailingslashit( plugins_url( '', __FILE__ ) ) . 'listing_ui.js'         /* wpbc_plugin_url( '/_out/js/codemirror.js' ) */
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

			wp_enqueue_style( 'wpbc-listing_class', trailingslashit( plugins_url( '', __FILE__ ) ) . 'listing_ui.css', array(), WP_BK_VERSION_NUM );
		}
	}

	// </editor-fold>

}

/**
 * Just for loading CSS and  JavaScript files
 */
 if ( true ) {
	$js_css_loading = new WPBC_Listing;
	$js_css_loading->init_load_css_js();
 }


 function wpbc_ajx_booking_listing__get_default_view_mode(){

	 //FixIn: 9.6.3.5
	$booking_default_view_mode = wpbc_get_default_saved_view_mode_for_wpbc_page();
	return $booking_default_view_mode;

 }