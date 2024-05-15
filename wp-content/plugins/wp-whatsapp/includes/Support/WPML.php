<?php
namespace NTA_WhatsApp\Support;

defined( 'ABSPATH' ) || exit;

class WPML {

	protected static $instance = null;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->doHooks();
		}
		return self::$instance;
	}

	private function doHooks() {
		global $sitepress;

		if ( $sitepress !== null && get_class( $sitepress ) === 'SitePress' ) {
			$settings  = $sitepress->get_setting( 'custom_posts_sync_option', array() );
			$post_type = 'whatsapp-accounts';
			if ( isset( $settings[ $post_type ] ) && ( $settings[ $post_type ] == 1 || $settings[ $post_type ] == 2 ) ) {
				$this->isActive = true;
				add_filter( 'njt_wa_get_post_type', array( $this, 'getPostType' ), 10, 1 );
			}
		}
	}

	public function getPostType( $args ) {
		$args['suppress_filters'] = false;
		return $args;
	}
}
