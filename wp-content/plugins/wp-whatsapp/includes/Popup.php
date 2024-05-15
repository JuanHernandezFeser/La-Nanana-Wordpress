<?php
namespace NTA_WhatsApp;

use NTA_WhatsApp\Fields;
use NTA_WhatsApp\PostType;

defined( 'ABSPATH' ) || exit;
class Popup {

	protected static $instance = null;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->doHooks();
		}
		return self::$instance;
	}

	public function __construct() {
	}

	private function doHooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_global_scripts_styles' ) );
		add_action( 'wp_footer', array( $this, 'show_widget' ) );
	}

	public function enqueue_global_scripts_styles() {
		wp_register_style( 'nta-css-popup', NTA_WHATSAPP_PLUGIN_URL . 'assets/dist/css/style.css', array(), NTA_WHATSAPP_VERSION );
		wp_enqueue_style( 'nta-css-popup' );
		wp_style_add_data( 'nta-css-popup', 'rtl', 'replace' );

		//This base script for add_inline_script in shortcode
		wp_enqueue_script( 'nta-wa-libs', NTA_WHATSAPP_PLUGIN_URL . 'assets/dist/js/njt-whatsapp.js', array(), NTA_WHATSAPP_VERSION, true );

		if ( function_exists( 'wp_timezone_string' ) ) {
			$timezone = wp_timezone_string();
		} else {
			$timezone = Helper::wp_timezone_string();
		}

		wp_register_script( 'nta-js-global', NTA_WHATSAPP_PLUGIN_URL . 'assets/js/whatsapp-button.js', array(), NTA_WHATSAPP_VERSION, true );
		wp_localize_script(
			'nta-js-global',
			'njt_wa_global',
			array(
				'ajax_url'         => admin_url( 'admin-ajax.php' ),
				'nonce'            => wp_create_nonce( 'ajax-nonce' ),
				'defaultAvatarSVG' => Helper::print_icon(),
				'defaultAvatarUrl' => NTA_WHATSAPP_PLUGIN_URL . 'assets/img/whatsapp_logo.svg',
				'timezone'         => $timezone,
				'i18n'             => I18n::getTranslation(),
				'urlSettings'      => Fields::getURLSettings(),
			)
		);
		wp_enqueue_script( 'nta-js-global' );
	}

	private function shouldDisplayWidget() {
		/**
		 * This code block prevents the display of the popup in Oxygen Builder.
		 */
		if ( defined( 'SHOW_CT_BUILDER' ) && ! defined( 'OXYGEN_IFRAME' ) ) {
			return false;
		}

		return true;
	}

	public function show_widget() {
		//Used to retrieve the accurate post ID when using Elementor
		wp_reset_postdata();

		if ( ! $this->shouldDisplayWidget() ) {
			return;
		}

		$displayOption = Fields::getWidgetDisplay();
		$postId        = get_the_ID();

		if ( $this->notShowInPage( $postId, $displayOption ) ) {
			return;
		}

		$activeAccounts = $this->get_accounts_active_and_meta();
		if ( count( $activeAccounts ) < 1 ) {
			return;
		}

		if ( wp_is_mobile() && $displayOption['showOnMobile'] === 'OFF'
			|| ! wp_is_mobile() && $displayOption['showOnDesktop'] === 'OFF'
			|| ( $displayOption['showOnMobile'] === 'OFF' && $displayOption['showOnDesktop'] === 'OFF' )
		) {
			return;
		}

		echo '<div id="wa"></div>';
		$this->enqueue_scripts_styles( $activeAccounts, $displayOption );
	}

	public function enqueue_scripts_styles( $activeAccounts, $displayOption ) {
		$stylesOption    = Fields::getWidgetStyles();
		$analyticsOption = Fields::getAnalyticsSetting();
		wp_register_script( 'nta-js-popup', NTA_WHATSAPP_PLUGIN_URL . 'assets/js/whatsapp-popup.js', array(), NTA_WHATSAPP_VERSION );
		wp_localize_script(
			'nta-js-popup',
			'njt_wa',
			array(
				'gdprStatus' => Helper::checkGDPR( $stylesOption ),
				'accounts'   => $activeAccounts,
				'options'    => array(
					'display'   => $displayOption,
					'styles'    => $stylesOption,
					'analytics' => $analyticsOption,
				),
			)
		);
		wp_enqueue_script( 'nta-js-popup' );
	}

	public function notShowInPage( $postId, $option ) {
		$isPageOrShop    = apply_filters( 'njt_whatsapp_is_page_or_shop_filter', is_page() );
		$postId          = apply_filters( 'njt_whatsapp_get_post_id_filter', $postId );
		$showInPostTypes = apply_filters( 'njt_whatsapp_display_in_post_types', array() );

		$postType = get_post_type( $postId );

		$isHiddenWidget = apply_filters( 'njt_whatsapp_hide_widget', false, $postId, $postType, $isPageOrShop, $option );

		if ( $isHiddenWidget ) {
			return true;
		}

		if ( ! empty( $showInPostTypes ) ) {
			if ( in_array( $postType, $showInPostTypes ) ) {
				return false;
			}
		}

		if ( $option['displayCondition'] == 'showAllPage' ) {
			return false;
		}

		if ( $option['displayCondition'] == 'includePages' ) {
			if ( is_array( $option['includePages'] ) && $isPageOrShop && in_array( strval( $postId ), $option['includePages'] ) ) {
				return false;
			}
			return true;
		} elseif ( $option['displayCondition'] == 'excludePages' ) {
			if ( is_array( $option['excludePages'] ) && $isPageOrShop && in_array( strval( $postId ), $option['excludePages'] ) ) {
				return true;
			}
		}

		return false;
	}

	public function get_accounts_active_and_meta() {
		$results  = array();
		$accounts = PostType::getInstance()->get_active_widget_accounts();
		foreach ( $accounts as $account ) {
			$meta   = get_post_meta( $account->ID, 'nta_wa_account_info', true );
			$avatar = get_the_post_thumbnail_url( $account->ID );
			if ( '' !== $meta ) {
				$results[] = array_merge(
					array(
						'accountId'   => $account->ID,
						'accountName' => $account->post_title,
						'avatar'      => $avatar !== false ? $avatar : '',
					),
					$meta
				);
			}
		}
		return $results;
	}
}
