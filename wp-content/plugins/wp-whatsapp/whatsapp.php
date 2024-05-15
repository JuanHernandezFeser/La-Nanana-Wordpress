<?php
/**
 * @wordpress-plugin
 * Plugin Name:       WP Chat App
 * Plugin URI:        https://ninjateam.org/wordpress-whatsapp-chat
 * Description:       Integrate your WhatsApp experience directly into your website. This is one of the best way to connect and interact with your customer.
 * Version:           3.6.5
 * Author:            NinjaTeam
 * Author URI:        https://ninjateam.org
 * Text Domain:       wp-whatsapp
 * Domain Path:       /languages
 */
namespace NTA_WhatsApp;

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'NTA_WhatsApp\\init' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Fallback.php';
	add_action(
		'admin_init',
		function () {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	return;
}

if ( ! defined( 'NTA_WHATSAPP_VERSION' ) ) {
	define( 'NTA_WHATSAPP_VERSION', '3.6.5' );
}

if ( ! defined( 'NTA_WHATSAPP_PLUGIN_URL' ) ) {
	define( 'NTA_WHATSAPP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'NTA_WHATSAPP_PLUGIN_DIR' ) ) {
	define( 'NTA_WHATSAPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'NTA_WHATSAPP_BASE_NAME' ) ) {
	define( 'NTA_WHATSAPP_BASE_NAME', plugin_basename( __FILE__ ) );
}

spl_autoload_register(
	function ( $class ) {
		$prefix   = __NAMESPACE__; // project-specific namespace prefix
		$base_dir = __DIR__ . '/includes'; // base directory for the namespace prefix

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) { // does the class use the namespace prefix?
			return; // no, move to the next registered autoloader
		}

		$relative_class_name = substr( $class, $len );

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php
		$file = $base_dir . str_replace( '\\', '/', $relative_class_name ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);

if ( file_exists( __DIR__ . '/includes/Review.php' ) ) {
	require_once __DIR__ . '/includes/Review.php';
}

if ( file_exists( __DIR__ . '/includes/Cross.php' ) ) {
	require_once __DIR__ . '/includes/Cross.php';
}

if ( file_exists( __DIR__ . '/includes/Recommended/Recommended.php' ) ) {
	require_once __DIR__ . '/includes/Recommended/Recommended.php';
}

if ( ! function_exists( 'NTA_WhatsApp\\init' ) ) {
	function init() {
		Plugin::activate();
		PostType::getInstance();
		I18n::loadPluginTextdomain();
		Shortcode::getInstance();
		Popup::getInstance();
		Settings::getInstance();
		Upgrade::getInstance();
		Support\WPML::getInstance();
		Support\Woocommerce::getInstance();
		if ( function_exists( 'register_block_type' ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'blocks/src/init.php';
		}
	}
}


add_action( 'plugins_loaded', 'NTA_WhatsApp\\init' );

register_activation_hook( __FILE__, array( 'NTA_WhatsApp\\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'NTA_WhatsApp\\Plugin', 'deactivate' ) );
