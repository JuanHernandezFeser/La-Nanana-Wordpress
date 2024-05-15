<?php
namespace NTA_WhatsApp;

use NTA_WhatsApp\Fields;

defined( 'ABSPATH' ) || exit;
class Shortcode {

	protected static $instance = null;
	protected $accountID;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->doHooks();
		}
		return self::$instance;
	}

	private function doHooks() {
		add_shortcode( 'njwa_button', array( $this, 'button_shortcode' ) );
	}

	public function button_shortcode( $id ) {
		extract( $id );
		$displayOption   = Fields::getWidgetDisplay();
		$stylesOption    = Fields::getWidgetStyles();
		$analyticsOption = Fields::getAnalyticsSetting();

		$script = array(
			'name'          => get_the_title( $id ),
			'info'          => get_post_meta( $id, 'nta_wa_account_info', true ),
			'styles'        => Fields::getButtonStyles( $id ),
			'avatar'        => get_the_post_thumbnail_url( $id ),
			'options'       => array(
				'display'   => $displayOption,
				'styles'    => $stylesOption,
				'analytics' => $analyticsOption,
			),
			'gdprStatus'    => Helper::checkGDPR( $stylesOption ),
			'defaultAvatar' => NTA_WHATSAPP_PLUGIN_URL . 'assets/img/whatsapp_logo.svg',
		);

		$content = '<div class="nta_wa_button" data-id="' . esc_attr( $id ) . '" data-info="' . esc_attr( wp_json_encode( $script ) ) . '"></div>';

		return $content;
	}
}
