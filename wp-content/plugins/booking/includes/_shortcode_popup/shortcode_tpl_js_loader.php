<?php
/**
 * @version 1.0
 * @package     Template Loader for Booking Calendar shortcodes config in Popup
 * @subpackage  Template Loader
 * @category    Templates
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2024-02-03
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly			//FixIn: 9.9.0.15


/**
 * Can I load scripts on this page for 'shortcode_config'
 *
 * @return bool
 */
function wpbc_can_i_load_on_this_page__shortcode_config() {

	 if ( ( isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] == 'wpbc-resources' ) ) )	{
		// Check  if this Booking > Resources page
	 } else {
		// Other pages
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return false;
		}
	 }


	$pages_where_load = array( 'post-new.php', 'page-new.php', 'post.php', 'page.php', 'widgets.php', 'customize.php' );
	if (
		   ( in_array( basename( $_SERVER['PHP_SELF'] ), $pages_where_load ) )
		|| ( ( isset( $_REQUEST['page'] ) && ( $_REQUEST['page'] == 'wpbc-resources' ) ) )                                // Check for Booking > Resources page
	) {

		return true;
	}

	return false;
}

// =====================================================================================================================
//  Load JS files at  specific pages only
// =====================================================================================================================

/**
 * Load JS files.
 *
 * @param $hook		'post.php' | 'wp-booking-calendar3_page_wpbc-resources'
 *
 * @return void
 */
function wpbc_register_js__shortcode_config( $hook ) {

	if ( wpbc_can_i_load_on_this_page__shortcode_config() ) {
		wp_enqueue_script( 'wpbc_all', wpbc_plugin_url( '/includes/_shortcode_popup/_out/wpbc_shortcode_popup.js' ), array( 'jquery' ), WP_BK_VERSION_NUM ); //FixIn: 9.8.6.1

		//Needed for ability to send dismiss //2024-03-13 //FixIn: 9.9.0.42
		wp_enqueue_script( 'wpbc-admin-support', wpbc_plugin_url( '/core/any/js/admin-support.js' ), array( 'jquery' ), WP_BK_VERSION_NUM );
		wp_add_inline_script( 'wpbc-admin-support', ' var wpbc_ajaxurl ="' . esc_url( admin_url( 'admin-ajax.php' ) ) . '";' );
		//wp_localize_script( 'wpbc-admin-support', 'wpbc_ajaxurl', admin_url( 'admin-ajax.php' ) );
	}


}
add_action( 'admin_enqueue_scripts', 'wpbc_register_js__shortcode_config'  );




//TODO: delete these code:
		// =====================================================================================================================
		//  Load wp Templates
		// =====================================================================================================================

		/**
		 * On WordPress Init, define load of  "WP Util,  that  support wp.template"  and define Templates at Footer
		 * @return void
		 */
		function wpbc_templates__init_hook__shortcode_config(){

			if ( wpbc_can_i_load_on_this_page__shortcode_config() ) {

				// Load: WP Util,  that  support wp.template,  based on underscore _.template system
				wp_enqueue_script( 'wp-util' );

				// Load: Templates
				add_action( 'admin_footer', 'wpbc_templates__shortcode_config__write_templates', 10, 2 );
			}
		}
		//add_action( 'init', 'wpbc_templates__init_hook__shortcode_config' );   								// Define init hooks


			/**
			 * Write all needed templates for shortcode config at the footer.
			 */
			function wpbc_templates__shortcode_config__write_templates(){


			}


		// =====================================================================================================================
		//  Info:  E X A M P L E   of   Template Usage
		// =====================================================================================================================
		 // * Template(s), that loaded 		in Footer 		defined in 		wpbc_templates__init_hook__shortcode_config()
		 // *
		 // *	add_action( 'admin_footer', 'wpbc__template__shortcode_config_booking__EXAMPLE', 10, 2 );
		 // *
		 // *
		 // * Help doc for use templates:
		 // * 1.  JavaScript:				<# console.log( 'JS from  template' ); #>
		 // * 2.  Escaped data:			{{ data.shortcode_config_2 }}
		 // * 2.  Not escaped - HTML data:	{{{ data.shortcode_config_2 }}}
		 // *
		if ( 0 ) {
			function wpbc__template__shortcode_config_booking__EXAMPLE(){

				// Templates here
				?><script type="text/html" id="tmpl-wpbc_shortcode_config__page_content"><?php

					echo 'TADA :) Rally  GOOD !:){{{ data.shortcode_config_2 }}}';

				?></script><?php

				// JS to Load template into page DOM  - HTML
				?>
				<script type="text/javascript">
					jQuery( document ).ready( function (){
						// Toolbar ---------------------------------------------------------------------------------------------------------
						var template__wpbc_shortcode_config__page_content = wp.template( 'wpbc_shortcode_config__page_content' );
						jQuery( '#wpbc_shortcode_config__run_template' ).html( template__wpbc_shortcode_config__page_content( {
																					'shortcode_config_1': 1,
																					'shortcode_config_2': 'This is variable from <strong> JS</strong>',
																					'shortcode_config_3': 'no'
														} ) );
					});
				</script>
				<?php
			}
		}