<?php
namespace NTA_WhatsApp\Support;

use NTA_WhatsApp\Fields;
use NTA_WhatsApp\PostType;


defined( 'ABSPATH' ) || exit;

class Woocommerce {

	protected static $instance   = null;
	protected static $isInserted = false;
	private $activeAccounts      = array();

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->doHooks();
		}
		return self::$instance;
	}

	public function __construct() {
	}


	public function doHooks() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public function isActiveWoocommerce() {
		if ( class_exists( 'woocommerce' ) ) {
			return true; }
		return false;
	}

	public function init() {
		if ( ! $this->isActiveWoocommerce() ) {
			return;
		}

		$postType             = PostType::getInstance();
		$this->activeAccounts = $postType->get_active_woocommerce_accounts();
		$setting              = Fields::getWoocommerceSetting();

		add_filter( 'njt_whatsapp_is_page_or_shop_filter', array( $this, 'isPageOrShop' ), 10, 1 );
		add_filter( 'njt_whatsapp_get_post_id_filter', array( $this, 'getPostId' ), 10, 1 );

		if ( count( $this->activeAccounts ) === 0 || $setting['isShow'] === 'OFF' ) {
			return;
		}

		if ( $setting['position'] === 'after_atc' ) {
			add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'insert_button' ) );
		} elseif ( $setting['position'] === 'before_atc' ) {
			add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'insert_button' ) );
		} elseif ( $setting['position'] === 'after_short_description' ) {
			add_filter( 'woocommerce_short_description', array( $this, 'showAfterShortDescription' ) );
		} elseif ( $setting['position'] === 'after_long_description' ) {
			add_filter( 'the_content', array( $this, 'showAfterLongDescription' ) );
		}

		add_filter( 'woocommerce_get_stock_html', array( $this, 'woocommerce_get_stock_html' ), 10, 2 );
	}

	public function woocommerce_get_stock_html( $html, $product ) {
		$shouldDisplay = apply_filters( 'njt_wa_out_of_stock_display', false );

		if ( $product->is_in_stock() || ! $shouldDisplay ) {
			return $html;
		}

		if ( ! self::$isInserted ) {
			self::$isInserted = true;
		} else {
			return $html;
		}

		foreach ( $this->activeAccounts as $row ) {
			$html .= '<div class="nta-woo-products-button">' . do_shortcode( '[njwa_button id="' . esc_attr( $row->ID ) . '"]' ) . '</div>';
		}

		return $html;
	}

	public function getPostId( $postId ) {
		if ( is_shop() ) {
			return wc_get_page_id( 'shop' );
		}
		return $postId;
	}

	public function isPageOrShop( $isPage ) {
		if ( $isPage === false && is_shop() ) {
			return true;
		}
		return $isPage;
	}

	public function showAfterShortDescription( $post_excerpt ) {
		if ( ! is_single() || empty( $post_excerpt ) ) {
			return $post_excerpt;
		}
		if ( ! self::$isInserted ) {
			self::$isInserted = true;
		} else {
			return $post_excerpt;
		}

		$btnContent = '';
		foreach ( $this->activeAccounts as $row ) {
			$btnContent .= '<div class="nta-woo-products-button">' . do_shortcode( '[njwa_button id="' . $row->ID . '"]' ) . '</div>';
		}

		return $post_excerpt . $btnContent;
	}

	public function showAfterLongDescription( $content ) {
		if ( 'product' !== get_post_type() || ! is_single() ) {
			return $content;
		}

		$btnContent = '';
		foreach ( $this->activeAccounts as $row ) {
			$btnContent .= '<div class="nta-woo-products-button">' . do_shortcode( '[njwa_button id="' . $row->ID . '"]' ) . '</div>';
		}

		return $content . $btnContent;
	}

	public function insert_button() {
		if ( ! self::$isInserted ) {
			self::$isInserted = true;
		} else {
			return;
		}

		foreach ( $this->activeAccounts as $row ) {
			echo '<div class="nta-woo-products-button">' . do_shortcode( '[njwa_button id="' . esc_attr( $row->ID ) . '"]' ) . '</div>';
		}
	}
}
