<?php
namespace NTA_WhatsApp;

use FileBirdCross;

defined( 'ABSPATH' ) || exit;
class Plugin {
	protected static $instance = null;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
	}

	public static function activate() {
		$firstTimeActive = get_option( 'njt_wa_first_time_active' );
		if ( $firstTimeActive === false ) {
			$waReview = \NJTWhatsAppReview::get_instance( 'njt_wa', 'WhatsApp Plugin', 'wp-whatsapp' );
			$waReview->need_update_option( 1 ); // 1 day
			update_option( 'njt_wa_first_time_active', 1 );
		}

		$currentVersion = get_option( 'njt_wa_version' );
		if ( version_compare( NTA_WHATSAPP_VERSION, $currentVersion, '>' ) ) {
			$filebirdCross = \FileBirdCross::get_instance( 'filebird', 'filebird+ninjateam', NTA_WHATSAPP_PLUGIN_URL, array( 'filebird/filebird.php', 'filebird-pro/filebird.php' ) );
			$filebirdCross->need_update_option();

			if ( $firstTimeActive !== false ) {
				$waReview = \NJTWhatsAppReview::get_instance( 'njt_wa', 'WhatsApp Plugin', 'wp-whatsapp' );
				$waReview->need_update_option( 7 ); // 1 day
			}

			update_option( 'njt_wa_version', NTA_WHATSAPP_VERSION );
		}
	}

	public static function deactivate() {
	}
}
