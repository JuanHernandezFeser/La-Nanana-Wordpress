<?php /**
 * @version 1.0
 * @package Booking Calendar
 * @category Support functions for Settings page
 * @author wpdevelop
 *
 * @web-site https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2022-02-08
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/**
 * Check if show "Settings General" page OR "System Info"
 *
 * @return bool
 */
function wpbc_is_show_general_setting_options(){                                                                        //FixIn: 8.9.4.11

	if ( ( isset( $_GET['system_info'] ) ) && ( $_GET['system_info'] == 'show' ) ) {
		$nonce_gen_time = check_admin_referer( 'wpbc_settings_url_nonce' );            //FixIn: 9.2.2.1
		return false;
	}
    return  true;
}



/**
 * Show system info at  Booking > Settings General page
 */
function wpbc_settings__system_info( $page_name ){

	if ( 'general_settings' != $page_name ) { return false; }

	if ( wpbc_is_this_demo() ) { return false; }

	if ( wpbc_is_show_general_setting_options() ) { return false; }

	//////////////////////////////////////////////////////////////////////////////

	echo '<div class="clear" style="height:30px;"></div>';

	echo '<span class="metabox-holder">';

	wpbc_settings__system_info__reset_booking_forms();

	wpbc_settings__system_info__generate_php_from_pot();

	wpbc_settings__system_info__show_system_info();

	wpbc_settings__system_info__restore_dismissed_windows();

	wpbc_settings__system_info__show_translation_status();

	wpbc_settings__system_info__update_translations();

	wpbc_settings__system_info__debug_and_tests();

	echo '</span>';
}
add_action( 'wpbc_hook_settings_page_footer', 'wpbc_settings__system_info' ,10, 1);


/**
 * System info section - Reset Custom Booking forms
 *
 * Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .' &reset=custom_forms#wpbc_general_settings_system_info_metabox
 *
 */
function wpbc_settings__system_info__reset_booking_forms() {

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( ( isset( $_GET['reset'] ) ) && ( 'custom_forms' == $_GET['reset'] ) ) {        								//FixIn: 8.1.3.21

		wpbc_open_meta_box_section( 'wpbc_general_settings_system_info', 'System Info' );

			// Reset Custom Booking Forms to  NONE
			update_bk_option( 'booking_forms_extended', serialize( array() ) );

			wpbc_show_message_in_settings( '<strong>Custom forms</strong> has been reseted!', 'info' );

		wpbc_close_meta_box_section();
	}
}



/**
 * System info section - Generate new translation PHP files from POT file
 *
 * // Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .' &pot=1#wpbc_general_settings_system_info_metabox
 *
 */
function wpbc_settings__system_info__generate_php_from_pot() {

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( ! empty( $_GET['pot'] ) ) {                                        //FixIn: 8.1.3.21

		wpbc_open_meta_box_section( 'wpbc_general_settings_system_info', 'System Info' );

		if ( '1' == $_GET['pot'] ) {
			wpbc_pot_to_php();
		}

		if ( 'erase__wpbc_all_translations' == $_GET['pot'] ) {
			wpbc_delete_translation_php_files();
		}

		wpbc_close_meta_box_section();
	}
}


/**
 * System info section - Update translations
 *
 * // Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .' &update_translations=1#wpbc_general_settings_system_info_metabox
 *
 */
function wpbc_settings__system_info__update_translations() {

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( ( isset( $_GET['update_translations'] ) ) && ( '1' == $_GET['update_translations'] ) ) {        								//FixIn: 8.1.3.21

		wpbc_open_meta_box_section( 'wpbc_general_settings_system_info', 'System Info' );

			wpbc_update_translations__from_wp();

		wpbc_close_meta_box_section();
	}
}


/**
 * System info section - Show translation status
 *
 * // Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .' &show_translation_status=1#wpbc_general_settings_system_info_metabox
 *
 */
function wpbc_settings__system_info__show_translation_status() {

	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( isset( $_GET['show_translation_status'] )  ) {        								//FixIn: 8.1.3.21

		wpbc_open_meta_box_section( 'wpbc_general_settings_system_info', 'System Info' );

			if ( '1' == $_GET['show_translation_status'] ){
				wpbc_show_translation_status_compare_wpbc_wp();
			}
			if ( '2' == $_GET['show_translation_status'] ){
				wpbc_show_translation_status_from_wp();
			}
			if ( '3' == $_GET['show_translation_status'] ){
				wpbc_show_translation_status_from_wpbc();
			}

		wpbc_close_meta_box_section();
	}
}


/**
 *  System info section - Showing information about system - php, server, active plugins,  etc...
 *
 * Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .' #wpbc_general_settings_system_info_metabox
 */
function wpbc_settings__system_info__show_system_info(){

	if ( ( isset( $_GET['booking_system_info'] ) ) && ( $_GET['booking_system_info'] == 'show' ) ) { ?>

		<?php wpbc_open_meta_box_section( 'wpbc_general_settings_system_info', 'System Info' );  ?>

		<?php wpbc_system_info(); ?>

		<?php wpbc_close_meta_box_section();
	}
}


/**
 * System info section - Restore Dismissed Windows
 *
 * // Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .' &restore_dismissed=On#wpbc_general_settings_restore_dismissed_metabox
 *
 */
function wpbc_settings__system_info__restore_dismissed_windows(){

	if ( ( isset( $_GET['restore_dismissed'] ) ) && ( $_GET['restore_dismissed'] == 'On' ) ) {            //FixIn: 8.1.3.10

		update_bk_option( 'booking_is_show_powered_by_notice', 'On' );

		update_bk_option( 'booking_wpdev_copyright_adminpanel', 'On' );

		global $wpdb;
		// Delete all users booking windows states
		if ( false === $wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE meta_key LIKE '%booking_win_%'" ) ) {    // All users data
			debuge_error( 'Error during deleting user meta at DB', __FILE__, __LINE__ );
			die();
		} else {

			wpbc_open_meta_box_section( 'wpbc_general_settings_restore_dismissed', 'Info' );

			?><h2>All dismissed windows has been restored.</h2><?php

			echo '<div class="clear"></div><hr/><center><a class="button button" href="' . wpbc_get_settings_url() . '">'
			     . 'Reload Page'
			     . '</a></center>';

			wpbc_close_meta_box_section();
		}
	}
}



	 /**
	  * Show System Info (status) at Booking > Settings General page
	  *
	  */
	function wpbc_system_info() {



		if ( current_user_can( 'activate_plugins' ) ) {                                // Only for Administrator or Super admin. More here: https://codex.wordpress.org/Roles_and_Capabilities


			global $wpdb, $wp_version;

			$all_plugins = get_plugins();
			$active_plugins = get_option( 'active_plugins' );

			$mysql_info = $wpdb->get_results( "SHOW VARIABLES LIKE 'sql_mode'" );
			if ( is_array( $mysql_info ) )  $sql_mode = $mysql_info[0]->Value;
			if ( empty( $sql_mode ) )       $sql_mode = 'Not set';

			//FixIn: 8.4.7.24
			$allow_url_fopen    = ( ini_get( 'allow_url_fopen' ) ) ?  'On' : 'Off';
			$upload_max_filesize = ( ini_get( 'upload_max_filesize' ) ) ? ini_get( 'upload_max_filesize' ) : 'N/A';
			$post_max_size      = ( ini_get( 'post_max_size' ) ) ? ini_get( 'post_max_size' ) : 'N/A';
			$max_execution_time = ( ini_get( 'max_execution_time' ) ) ? ini_get( 'max_execution_time' ) : 'N/A';
			$memory_limit       = ( ini_get( 'memory_limit' ) ) ? ini_get( 'memory_limit' ) : 'N/A';
			$memory_usage       = ( function_exists( 'memory_get_usage' ) ) ? round( memory_get_usage() / 1024 / 1024, 2 ) . ' Mb' : 'N/A';
			$exif_read_data     = ( is_callable( 'exif_read_data' ) ) ? 'Yes' . " ( V" . substr( phpversion( 'exif' ), 0, 4 ) . ")" : 'No';
			$iptcparse          = ( is_callable( 'iptcparse' ) ) ? 'Yes' : 'No';
			$xml_parser_create  = ( is_callable( 'xml_parser_create' ) ) ? 'Yes' : 'No';
			$theme              = ( function_exists( 'wp_get_theme' ) ) ? wp_get_theme() : get_theme( get_current_theme() );

			if ( function_exists( 'is_multisite' ) ) {
				if ( is_multisite() )   $multisite = 'Yes';
				else                    $multisite = 'No';
			} else {                    $multisite = 'N/A';
			}

			$system_info = array(
				'system_info' => '',
				'php_info' => '',
				'active_plugins' => array(),			//FixIn: 8.4.4.1
				'inactive_plugins' => array()			//FixIn: 8.4.4.1
			);

			$ver_small_name = wpbc_get_plugin_version_type();
			if ( class_exists( 'wpdev_bk_multiuser' ) ) $ver_small_name = 'multiuser';

			$system_info['system_info'] = array(
				'Plugin Update'         => ( defined( 'WPDEV_BK_VERSION' ) ) ? WPDEV_BK_VERSION : 'N/A',
				'Plugin Version'        => ucwords( $ver_small_name ),
				'Plugin Update Date'   => date( "Y-m-d", filemtime( WPBC_FILE ) ),

				'Server Default Timezone' => date_default_timezone_get(),
				'WordPress Timezone'      => wp_timezone()->getName(),

				'WP Version' => $wp_version,
				'WP DEBUG'   =>  ( ( defined('WP_DEBUG') ) && ( WP_DEBUG ) ) ? 'On' : 'Off',
				'WP DB Version' => get_option( 'db_version' ),
				'Operating System' => PHP_OS,
				'Server' => $_SERVER["SERVER_SOFTWARE"],
				'PHP Version' => PHP_VERSION,
				'MYSQL Version' => $wpdb->get_var( "SELECT VERSION() AS version" ),
				'SQL Mode' => $sql_mode,
				'Memory usage' => $memory_usage,
				'Site URL' => get_option( 'siteurl' ),
				'Home URL' => home_url(),
				'SERVER[HTTP_HOST]' => $_SERVER['HTTP_HOST'],
				'SERVER[SERVER_NAME]' => $_SERVER['SERVER_NAME'],
				'Multisite' => $multisite,
				'Active Theme' => $theme['Name'] . ' ' . $theme['Version']
			);

			$system_info['php_info'] = array(
				'PHP Version' => PHP_VERSION,
					'PHP Memory Limit'              => '<strong>' . $memory_limit . '</strong>',
					'PHP Max Script Execute Time'   => '<strong>' . $max_execution_time . '</strong>',

					'PHP Max Post Size'  => '<strong>' . $post_max_size . '</strong>',
					'PHP MAX Input Vars' => '<strong>' . ( ( ini_get( 'max_input_vars' ) ) ? ini_get( 'max_input_vars' ) : 'N/A' ) . '</strong>',           //How many input variables may be accepted (limit is applied to $_GET, $_POST and $_COOKIE superglobal separately).

				'PHP Max Upload Size'   => $upload_max_filesize,
				'PHP Allow URL fopen'   => $allow_url_fopen,
				'PHP Exif support'      => $exif_read_data,
				'PHP IPTC support'      => $iptcparse,
				'PHP XML support'       => $xml_parser_create
			);

			$system_info['php_info']['PHP cURL'] =  ( function_exists('curl_init') ) ? 'On' : 'Off';
			$system_info['php_info']['Max Nesting Level'] = ( ( ini_get( 'max_input_nesting_level' ) ) ? ini_get( 'max_input_nesting_level' ) : 'N/A' );
			$system_info['php_info']['Max Time 4 script'] = ( ( ini_get( 'max_input_time' ) ) ? ini_get( 'max_input_time' ) : 'N/A' );                     //Maximum amount of time each script may spend parsing request data
			$system_info['php_info']['Log'] =      ( ( ini_get( 'error_log' ) ) ? ini_get( 'error_log' ) : 'N/A' );

			if ( ini_get( "suhosin.get.max_value_length" ) ) {

				$system_info['suhosin_info'] = array();
				$system_info['suhosin_info']['POST max_array_index_length']     = ( ( ini_get( 'suhosin.post.max_array_index_length' ) ) ? ini_get( 'suhosin.post.max_array_index_length' ) : 'N/A' );
				$system_info['suhosin_info']['REQUEST max_array_index_length']  = ( ( ini_get( 'suhosin.request.max_array_index_length' ) ) ? ini_get( 'suhosin.request.max_array_index_length' ) : 'N/A' );

				$system_info['suhosin_info']['POST max_totalname_length']    = ( ( ini_get( 'suhosin.post.max_totalname_length' ) ) ? ini_get( 'suhosin.post.max_totalname_length' ) : 'N/A' );
				$system_info['suhosin_info']['REQUEST max_totalname_length'] = ( ( ini_get( 'suhosin.request.max_totalname_length' ) ) ? ini_get( 'suhosin.request.max_totalname_length' ) : 'N/A' );

				$system_info['suhosin_info']['POST max_vars']               = ( ( ini_get( 'suhosin.post.max_vars' ) ) ? ini_get( 'suhosin.post.max_vars' ) : 'N/A' );
				$system_info['suhosin_info']['REQUEST max_vars']            = ( ( ini_get( 'suhosin.request.max_vars' ) ) ? ini_get( 'suhosin.request.max_vars' ) : 'N/A' );

				$system_info['suhosin_info']['POST max_value_length']       = ( ( ini_get( 'suhosin.post.max_value_length' ) ) ? ini_get( 'suhosin.post.max_value_length' ) : 'N/A' );
				$system_info['suhosin_info']['REQUEST max_value_length']    = ( ( ini_get( 'suhosin.request.max_value_length' ) ) ? ini_get( 'suhosin.request.max_value_length' ) : 'N/A' );

				$system_info['suhosin_info']['POST max_name_length']        = ( ( ini_get( 'suhosin.post.max_name_length' ) ) ? ini_get( 'suhosin.post.max_name_length' ) : 'N/A' );
				$system_info['suhosin_info']['REQUEST max_varname_length']  = ( ( ini_get( 'suhosin.request.max_varname_length' ) ) ? ini_get( 'suhosin.request.max_varname_length' ) : 'N/A' );

				$system_info['suhosin_info']['POST max_array_depth']        = ( ( ini_get( 'suhosin.post.max_array_depth' ) ) ? ini_get( 'suhosin.post.max_array_depth' ) : 'N/A' );
				$system_info['suhosin_info']['REQUEST max_array_depth']     = ( ( ini_get( 'suhosin.request.max_array_depth' ) ) ? ini_get( 'suhosin.request.max_array_depth' ) : 'N/A' );
			}


			if ( function_exists('gd_info') ) {
				$gd_info = gd_info();
				if ( isset( $gd_info['GD Version'] ) )
					$gd_info = $gd_info['GD Version'];
				else
					$gd_info = json_encode( $gd_info );
			} else {
				$gd_info = 'Off';
			}
			$system_info['php_info']['PHP GD'] = $gd_info;

			// More here https://docs.woocommerce.com/document/problems-with-large-amounts-of-data-not-saving-variations-rates-etc/


			foreach ( $all_plugins as $path => $plugin ) {
				if ( is_plugin_active( $path ) ) {
						$system_info['active_plugins'][ $plugin['Name'] ] = $plugin['Version'];
				} else {
						$system_info['inactive_plugins'][ $plugin['Name'] ] = $plugin['Version'];
				}
			}

			// Showing
			foreach ( $system_info as $section_name => $section_values ) {
				?>
				<span class="wpdevelop">
				<table class="table table-striped table-bordered">
					<thead><tr><th colspan="2" style="border-bottom: 1px solid #eeeeee;padding: 10px;"><?php echo strtoupper( $section_name ); ?></th></tr></thead>
					<tbody>
					<?php
					if ( !empty( $section_values ) ) {
						foreach ( $section_values as $key => $value ) {
							?>
							<tr>
								<td scope="row" style="width:18em;padding:4px 8px;"><?php echo $key; ?></td>
								<td scope="row" style="padding:4px 8px;"><?php echo $value; ?></td>
							</tr>
							<?php
						}
					}
					?>
					</tbody>
				</table>
				</span>
				<div class="clear"></div>
				<?php
			}
?>
<hr>
<div style="color:#777;">
<h4 style="font-size:1.1em;">Commonly required configuration vars in php.ini file:</h4>
<h4>General section:</h4>
<pre><code>memory_limit = 256M
 max_execution_time = 120
 post_max_size = 8M
 upload_max_filesize = 8M
 max_input_vars = 20480
 post_max_size = 64M</code></pre>
<h4>Suhosin section (if installed):</h4>
<pre><code>suhosin.post.max_array_index_length = 1024
 suhosin.post.max_totalname_length = 65535
 suhosin.post.max_vars = 2048
 suhosin.post.max_value_length = 1000000
 suhosin.post.max_name_length = 256
 suhosin.post.max_array_depth = 1000
 suhosin.request.max_array_index_length = 1024
 suhosin.request.max_totalname_length = 65535
 suhosin.request.max_vars = 2048
 suhosin.request.max_value_length = 1000000
 suhosin.request.max_varname_length = 256
 suhosin.request.max_array_depth = 1000</code></pre>
</div>
<?php
			// phpinfo();
		}
	}



/**
 * It's for my tests and debugs
 */
function wpbc_settings__system_info__debug_and_tests() {

	if ( 0 ) {

		wpbc_open_meta_box_section( 'wpbc_general_settings_test_debug',  'Test & Debug'  );

			//debuge( get_site_transient( 'update_plugins' ) );

		wpbc_close_meta_box_section();
	}


if (0){
	/**
	 * Install Plugin.
	 */
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
	$skin     = new WP_Ajax_Upgrader_Skin();
	$upgrader = new Plugin_Upgrader( $skin );
	$package  = 'https://wpbookingcalendar.com/download/some-plugin.zip';
	$result   = $upgrader->install( $package );            //$package - The full local path or URI of the package
}

if (0) {

	$plugin_slug = 'booking';
	global $wp_filesystem;

	$plugin_translations = wp_get_installed_translations( 'plugins' );

	$language_updates = wp_get_translation_updates();

debuge( '$language_updates, $plugin_translations[ $plugin_slug ]', $language_updates, $plugin_translations[ $plugin_slug ] );

	if ( 0 ) {
		// Remove language files, silently.
		if ( '.' !== $plugin_slug && ! empty( $plugin_translations[ $plugin_slug ] ) ) {
			$translations = $plugin_translations[ $plugin_slug ];

			foreach ( $translations as $translation => $data ) {
				$wp_filesystem->delete( WP_LANG_DIR . '/plugins/' . $plugin_slug . '-' . $translation . '.po' );
				$wp_filesystem->delete( WP_LANG_DIR . '/plugins/' . $plugin_slug . '-' . $translation . '.mo' );

				$json_translation_files = glob( WP_LANG_DIR . '/plugins/' . $plugin_slug . '-' . $translation . '-*.json' );
				if ( $json_translation_files ) {
					array_map( array( $wp_filesystem, 'delete' ), $json_translation_files );
				}
			}
		}
	}
}

}
