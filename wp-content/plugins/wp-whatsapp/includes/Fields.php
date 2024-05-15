<?php

namespace NTA_WhatsApp;

defined( 'ABSPATH' ) || exit;

class Fields {

	protected static $instance = null;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
	}

	public static function getButtonStyles( $postId ) {
		$meta = get_post_meta( $postId, 'nta_wa_button_styles', true );
		return wp_parse_args(
			$meta === false ? array() : $meta,
			array(
				'type'            => 'round',
				'backgroundColor' => '#2DB742',
				'textColor'       => '#fff',
				'label'           => __( 'Need Help? Chat with us', 'wp-whatsapp' ),
				'width'           => 300,
				'height'          => 64,
			)
		);
	}


	public static function getWidgetDisplay() {
		return wp_parse_args(
			get_option( 'nta_wa_widget_display', array() ),
			array(
				'displayCondition' => 'showAllPage',
				'includePages'     => array(),
				'excludePages'     => array(),
				'includePosts'     => array(),
				'showOnDesktop'    => 'ON',
				'showOnMobile'     => 'ON',
				'time_symbols'     => 'h:m',
			)
		);
	}

	public static function getURLSettings() {
		return wp_parse_args(
			get_option( 'nta_wa_url', array() ),
			array(
				'onDesktop'    => 'api',
				'onMobile'     => 'api',
				'openInNewTab' => 'ON',
			)
		);
	}

	public static function getWidgetStyles() {
		return wp_parse_args(
			get_option( 'nta_wa_widget_styles', array() ),
			array(
				'title'               => __( 'Start a Conversation', 'wp-whatsapp' ),
				'responseText'        => __( 'The team typically replies in a few minutes.', 'wp-whatsapp' ),
				'description'         => __( 'Hi! Click one of our member below to chat on <strong>WhatsApp</strong>', 'wp-whatsapp' ),
				'backgroundColor'     => '#2db742',
				'textColor'           => '#fff',
				'titleSize'           => 18,
				'accountNameSize'     => 14,
				'descriptionTextSize' => 12,
				'regularTextSize'     => 11,
				'scrollHeight'        => 500,
				'isShowScroll'        => 'OFF',
				'isShowResponseText'  => 'ON',
				'isShowPoweredBy'     => 'ON',

				'btnLabel'            => __( 'Need Help? <strong>Chat with us</strong>', 'wp-whatsapp' ),
				'btnLabelWidth'       => 156,
				'btnPosition'         => 'right',
				'btnLeftDistance'     => 30,
				'btnRightDistance'    => 30,
				'btnBottomDistance'   => 30,
				'isShowBtnLabel'      => 'ON',

				'isShowGDPR'          => 'OFF',
				'gdprContent'         => __( 'Please accept our <a href="https://ninjateam.org/privacy-policy/">privacy policy</a> first to start a conversation.', 'wp-whatsapp' ),
			)
		);
	}

	public static function getWoocommerceSetting() {
		$option = get_option( 'nta_wa_woocommerce', array() );
		return wp_parse_args(
			$option,
			array(
				'position' => 'after_atc',
				'isShow'   => 'OFF',
			)
		);
	}

	public static function getAnalyticsSetting() {
		$option = get_option( 'nta_wa_analytics', array() );
		return wp_parse_args(
			$option,
			array(
				'enabledGoogle'    => 'OFF',
				'enabledFacebook'  => 'OFF',
				'enabledGoogleGA4' => 'OFF',
			)
		);
	}

	public static function getDefaultMetaAccount( $daysOfWeek ) {
		$meta = array(
			'number'            => '',
			'title'             => '',
			'predefinedText'    => '',
			'willBeBackText'    => __( 'I will be back in [njwa_time_work]', 'wp-whatsapp' ),
			'dayOffsText'       => __( 'I will be back soon', 'wp-whatsapp' ),
			'isAlwaysAvailable' => 'ON',
		);

		foreach ( $daysOfWeek as $dayKey ) {
			$meta['daysOfWeekWorking'][ $dayKey ] = array(
				'isWorkingOnDay' => 'OFF',
				'workHours'      => array(
					array(
						'startTime' => '08:00',
						'endTime'   => '17:30',
					),
				),
			);
		}

		return $meta;
	}
}
