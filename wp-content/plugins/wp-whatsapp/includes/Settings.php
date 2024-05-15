<?php
namespace NTA_WhatsApp;

use NTA_WhatsApp\Helper;
use NTA_WhatsApp\Fields;
use NTA_WhatsApp\PostType;

defined( 'ABSPATH' ) || exit;
/**
 * Settings Page
 */
class Settings {

	protected $option;
	protected $option_group            = 'nta_whatsapp_group';
	protected $option_design           = 'nta_whatsapp_design';
	protected $option_button_group     = 'nta_whatsapp_button_group';
	protected $option_woo_button_group = 'nta_wa_woo_button_group';
	protected $option_ga_group         = 'nta_wa_ga_group';

	protected $settings;

	private $floatingWidgetSlug = '';
	private $settingSlug        = '';

	protected static $instance = null;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
			self::$instance->doHooks();
		}
		return self::$instance;
	}

	private function doHooks() {
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );

		add_action( 'wp_ajax_njt_wa_set_account_position', array( $this, 'set_account_position' ) );
		add_action( 'wp_ajax_njt_wa_load_accounts_ajax', array( $this, 'load_accounts_ajax' ) );
		add_action( 'wp_ajax_njt_wa_set_account_status', array( $this, 'set_account_status' ) );

		add_action( 'wp_ajax_njt_wa_save_display_setting', array( $this, 'save_display_setting' ) );
		add_action( 'wp_ajax_njt_wa_save_design_setting', array( $this, 'save_design_setting' ) );
		add_action( 'wp_ajax_njt_wa_save_woocommerce_setting', array( $this, 'save_woocommerce_setting' ) );
		add_action( 'wp_ajax_njt_wa_save_analytics_setting', array( $this, 'save_analytics_setting' ) );
		add_action( 'wp_ajax_njt_wa_save_url_setting', array( $this, 'save_url_setting' ) );

		add_filter( 'plugin_action_links_' . NTA_WHATSAPP_BASE_NAME, array( $this, 'addActionLinks' ) );
		add_filter( 'plugin_row_meta', array( $this, 'pluginRowMeta' ), 10, 2 );
	}

	public function __construct() {
	}

	public function addActionLinks( $links ) {
		$links = array_merge(
			array(
				'<a href="' . esc_url( admin_url( '/admin.php?page=nta_whatsapp_floating_widget' ) ) . '">' . __( 'Settings', 'wp-whatsapp' ) . '</a>',
			),
			$links
		);

		$links[] = '<a target="_blank" href="https://1.envato.market/WhatsApp-Plugin" style="color: #43B854; font-weight: bold">' . __( 'Go Pro', 'wp-whatsapp' ) . '</a>';

		return $links;
	}

	public function pluginRowMeta( $links, $file ) {
		if ( strpos( $file, 'whatsapp.php' ) !== false ) {
			$new_links = array(
				'doc' => '<a href="https://ninjateam.org/wordpress-whatsapp-chat-tutorial/" target="_blank">' . __( 'Documentation', 'wp-whatsapp' ) . '</a>',
			);

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}

	public function admin_menu() {
		$edit_account_link = 'post-new.php?post_type=whatsapp-accounts';

		add_menu_page( 'NTA Whatsapp', 'WhatsApp', 'manage_options', 'nta_whatsapp', array( $this, 'create_page_setting_widget' ), NTA_WHATSAPP_PLUGIN_URL . 'assets/img/whatsapp-menu.svg', 60 );
		add_submenu_page( 'nta_whatsapp', __( 'Add New account', 'wp-whatsapp' ), __( 'Add New account', 'wp-whatsapp' ), 'manage_options', $edit_account_link );
		$this->floatingWidgetSlug = add_submenu_page( 'nta_whatsapp', __( 'Floating Widget', 'wp-whatsapp' ), __( 'Floating Widget', 'wp-whatsapp' ), 'manage_options', 'nta_whatsapp_floating_widget', array( $this, 'floating_widget_view' ) );
		$this->settingSlug        = add_submenu_page( 'nta_whatsapp', __( 'Settings', 'wp-whatsapp' ), __( 'Settings', 'wp-whatsapp' ), 'manage_options', 'nta_whatsapp_setting', array( $this, 'create_page_setting_widget' ) );
		add_submenu_page(
			'nta_whatsapp',
			'',
			'<span>' . __( 'Go Pro', 'wp-whatsapp' ) . '</span>',
			'manage_options',
			'go_whatsapp_pro',
			array( $this, 'go_pro_redirects' )
		);
	}

	public function go_pro_redirects() {
		if ( empty( $_GET['page'] ) ) {
			return;
		}

		if ( 'go_whatsapp_pro' === $_GET['page'] ) {
			?>
				<script>window.location.href = 'https://1.envato.market/whatsapp-pro'</script>
			<?php
		}
	}

	function admin_footer() {
		?>
		<style>
		body.admin-color-fresh #adminmenu #toplevel_page_nta_whatsapp a[href="admin.php?page=go_whatsapp_pro"] {
			color: #00BC28;
			font-weight: bold;
			position: relative;
			}

		body.admin-color-fresh #adminmenu #toplevel_page_nta_whatsapp a[href="admin.php?page=go_whatsapp_pro"]::after {
			content: '';
			position: absolute;
			width: 4px;
			top: 0;
			bottom: 0;
			left: 0;
			background: green;
			}
		</style>
		<script>
		jQuery(document).ready(function() {
			jQuery('#toplevel_page_nta_whatsapp a[href="admin.php?page=go_whatsapp_pro"]').click(function(event) {
				event.preventDefault()
				window.open('https://1.envato.market/whatsapp-pro', '_blank')
			})
		})
		</script>
		<?php
		$screen = get_current_screen();
		if ( $screen->base !== $this->floatingWidgetSlug ) {
			return;
		}
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/design-preview.php';
	}

	public function admin_enqueue_scripts( $hook_suffix ) {
		if ( $hook_suffix === 'edit.php' || $hook_suffix === 'post-new.php' || $hook_suffix === 'post.php' ) {
			if ( get_post_type() !== 'whatsapp-accounts' ) {
				return;
			}
		} elseif ( ! in_array( $hook_suffix, array( $this->settingSlug, $this->floatingWidgetSlug ) ) ) {
				return;
		}

		wp_register_style( 'nta-css', NTA_WHATSAPP_PLUGIN_URL . 'assets/css/admin.css', array( 'wp-color-picker' ), NTA_WHATSAPP_VERSION );
		wp_enqueue_style( 'nta-css' );

		wp_register_style( 'nta-tippy-css', NTA_WHATSAPP_PLUGIN_URL . 'assets/css/tooltip.css', array(), NTA_WHATSAPP_VERSION );
		wp_enqueue_style( 'nta-tippy-css' );

		wp_dequeue_style( 'woosea_jquery_ui-css' );

		wp_register_style( 'nta-wa-widget', NTA_WHATSAPP_PLUGIN_URL . 'assets/dist/css/style.css', array(), NTA_WHATSAPP_VERSION );
		wp_enqueue_style( 'nta-wa-widget' );
		wp_enqueue_style( 'ui-range', NTA_WHATSAPP_PLUGIN_URL . 'assets/libs/ui-range.css', array(), NTA_WHATSAPP_VERSION );

		if ( function_exists( 'wp_timezone_string' ) ) {
			$timezone = wp_timezone_string();
		} else {
			$timezone = Helper::wp_timezone_string();
		}

		wp_register_script(
			'nta-wa-js',
			NTA_WHATSAPP_PLUGIN_URL . 'assets/dist/js/app.js',
			array(
				'jquery',
				'wp-color-picker',
				'backbone',
				'underscore',
				'jquery-ui-tabs',
				'jquery-ui-sortable',
				'jquery-ui-autocomplete',
			),
			NTA_WHATSAPP_VERSION,
			true
		);
		wp_localize_script(
			'nta-wa-js',
			'njt_wa',
			array(
				'url'      => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'njt-wa-nonce' ),
				'settings' => array(
					'widget' => array(
						'styles' => Fields::getWidgetStyles(),
					),
				),
				'timezone' => $timezone,
				'i18n'     => array(
					'select_post' => __( 'Select posts to display the widget', 'wp-whatsapp' ),
				),
			)
		);
		wp_enqueue_script( 'nta-wa-js' );
		wp_enqueue_script( 'jquery-validate', NTA_WHATSAPP_PLUGIN_URL . 'assets/libs/jquery.validate.min.js', array( 'jquery' ), NTA_WHATSAPP_VERSION, true );
	}

	public function page_display_settings_section_callback() {
		global $wpdb;
		$option                 = Fields::getWidgetDisplay();
		$option['time_symbols'] = explode( ':', $option['time_symbols'] );
		$pages                  = $wpdb->get_results( "Select ID, post_title from {$wpdb->posts} where post_type = 'page' and post_status = 'publish'" );
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/display-settings.php';
	}

	public function page_design_settings_section_callback() {
		$option                    = Fields::getWidgetStyles();
		$editor_settings           = array(
			'media_buttons' => false,
			'textarea_rows' => get_option( 'default_post_edit_rows', 5 ),
			'quicktags'     => false,
			'teeny'         => true,
		);
		$editor_settings_quicktags = array(
			'media_buttons' => false,
			'textarea_rows' => get_option( 'default_post_edit_rows', 5 ),
			'quicktags'     => true,
			'teeny'         => true,
		);
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/design-settings.php';
	}

	public function page_selected_accounts_section_callback() {
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/selected-accounts.php';
	}

	public function woocommerce_button_callback() {
		$option = Fields::getWoocommerceSetting();
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/woocommerce-button.php';
	}

	public function analytics_callback() {
		$option = Fields::getAnalyticsSetting();
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/analytics.php';
	}

	public function url_callback() {
		$option = Fields::getURLSettings();
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/url-settings.php';
	}

	public function create_page_setting_widget() {
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/settings.php';
	}

	public function floating_widget_view() {
		require NTA_WHATSAPP_PLUGIN_DIR . 'views/floating-widget-settings.php';
	}

	public function register_setting() {
		register_setting( $this->option_group, 'nta_whatsapp_setting' );
		register_setting( $this->option_design, 'nta_whatsapp_setting' );
		register_setting( $this->option_woo_button_group, 'nta_wa_woobutton_setting', array( $this, 'save_woobutton_setting' ) );
		register_setting( $this->option_ga_group, 'nta_wa_ga_setting', array( $this, 'save_ga_setting' ) );

		add_settings_section( 'page_selected_accounts_section', '', array( $this, 'page_selected_accounts_section_callback' ), 'floating-widget-whatsapp-1' );
		add_settings_section( 'page_design_settings_section', '', array( $this, 'page_design_settings_section_callback' ), 'floating-widget-whatsapp-2' );
		add_settings_section( 'page_display_settings_section', '', array( $this, 'page_display_settings_section_callback' ), 'floating-widget-whatsapp-3' );
		add_settings_section( 'nta_woocommerce_button', '', array( $this, 'woocommerce_button_callback' ), 'settings-whatsapp-1' );
		add_settings_section( 'nta_analytics', '', array( $this, 'analytics_callback' ), 'settings-whatsapp-2' );
		add_settings_section( 'nta_url', '', array( $this, 'url_callback' ), 'settings-whatsapp-3' );
	}

	public function save_woobutton_setting() {
		$new_input = array();

		$new_input['nta_woo_button_position'] = sanitize_text_field( $_POST['nta_woo_button_position'] );
		$new_input['nta_woo_button_status']   = isset( $_POST['nta_woo_button_status'] ) ? 'ON' : 'OFF';
		return $new_input;
	}

	public function save_ga_setting() {
		if ( isset( $_POST['nta_wa_ga_status'] ) ) {
			return '1';
		}
		return '0';
	}

	public function save_display_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );
		$new_input = array();

		$excludePages = Helper::sanitize_array( $_POST['excludePages'] );
		$includePages = Helper::sanitize_array( $_POST['includePages'] );
		$includePosts = Helper::sanitize_array( $_POST['includePosts'] );

		$new_input                     = Fields::getWidgetDisplay();
		$new_input['displayCondition'] = sanitize_text_field( $_POST['displayCondition'] );
		$new_input['excludePages']     = empty( $excludePages ) ? array() : $excludePages;
		$new_input['includePages']     = empty( $includePages ) ? array() : $includePages;
		$new_input['includePosts']     = empty( $includePosts ) ? array() : $includePosts;
		$new_input['showOnDesktop']    = isset( $_POST['showOnDesktop'] ) ? 'ON' : 'OFF';
		$new_input['showOnMobile']     = isset( $_POST['showOnMobile'] ) ? 'ON' : 'OFF';

		$time_symbols              = Helper::sanitize_array( $_POST['time_symbols'] );
		$new_input['time_symbols'] = wp_unslash( $time_symbols['hourSymbol'] ) . ':' . wp_unslash( $time_symbols['minSymbol'] );

		update_option( 'nta_wa_widget_display', $new_input );
		wp_send_json_success();
	}

	public function save_design_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array();

		$new_input                        = Fields::getWidgetStyles();
		$new_input['title']               = sanitize_text_field( wp_unslash( $_POST['title'] ) );
		$new_input['textColor']           = sanitize_hex_color( $_POST['textColor'] );
		$new_input['titleSize']           = sanitize_text_field( $_POST['titleSize'] );
		$new_input['accountNameSize']     = sanitize_text_field( $_POST['accountNameSize'] );
		$new_input['descriptionTextSize'] = sanitize_text_field( $_POST['descriptionTextSize'] );
		$new_input['regularTextSize']     = sanitize_text_field( $_POST['regularTextSize'] );
		$new_input['backgroundColor']     = sanitize_hex_color( $_POST['backgroundColor'] );
		$new_input['description']         = wp_kses_post( wp_unslash( $_POST['description'] ) );
		$new_input['responseText']        = wp_kses_post( wp_unslash( $_POST['responseText'] ) );
		$new_input['scrollHeight']        = sanitize_text_field( $_POST['scrollHeight'] );
		$new_input['isShowScroll']        = isset( $_POST['isShowScroll'] ) ? 'ON' : 'OFF';
		$new_input['isShowResponseText']  = isset( $_POST['isShowResponseText'] ) ? 'ON' : 'OFF';
		$new_input['isShowPoweredBy']     = isset( $_POST['isShowPoweredBy'] ) ? 'ON' : 'OFF';

		$new_input['btnLabel']          = wp_kses_post( wp_unslash( $_POST['btnLabel'] ) ); // It can be an html tag
		$new_input['btnPosition']       = sanitize_text_field( $_POST['btnPosition'] );
		$new_input['btnLabelWidth']     = sanitize_text_field( $_POST['btnLabelWidth'] );
		$new_input['btnLeftDistance']   = sanitize_text_field( $_POST['btnLeftDistance'] );
		$new_input['btnRightDistance']  = sanitize_text_field( $_POST['btnRightDistance'] );
		$new_input['btnBottomDistance'] = sanitize_text_field( $_POST['btnBottomDistance'] );
		$new_input['isShowBtnLabel']    = isset( $_POST['isShowBtnLabel'] ) ? 'ON' : 'OFF';

		$new_input['isShowGDPR']  = isset( $_POST['isShowGDPR'] ) ? 'ON' : 'OFF';
		$new_input['gdprContent'] = wp_kses_post( wp_unslash( $_POST['gdprContent'] ) );

		update_option( 'nta_wa_widget_styles', $new_input );
		wp_send_json_success();
	}

	public function save_woocommerce_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array();

		$new_input             = Fields::getWoocommerceSetting();
		$new_input['position'] = sanitize_text_field( $_POST['position'] );
		$new_input['isShow']   = isset( $_POST['isShow'] ) ? 'ON' : 'OFF';

		update_option( 'nta_wa_woocommerce', $new_input );
		wp_send_json_success();
	}

	public function save_analytics_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array();

		$new_input                     = Fields::getAnalyticsSetting();
		$new_input['enabledGoogle']    = isset( $_POST['enabledGoogle'] ) ? 'ON' : 'OFF';
		$new_input['enabledFacebook']  = isset( $_POST['enabledFacebook'] ) ? 'ON' : 'OFF';
		$new_input['enabledGoogleGA4'] = isset( $_POST['enabledGoogleGA4'] ) ? 'ON' : 'OFF';

		update_option( 'nta_wa_analytics', $new_input );
		wp_send_json_success();
	}

	public function save_url_setting() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$new_input = array();

		$new_input                 = Fields::getURLSettings();
		$new_input['openInNewTab'] = isset( $_POST['openInNewTab'] ) ? 'ON' : 'OFF';
		$new_input['onDesktop']    = sanitize_text_field( $_POST['onDesktop'] );
		$new_input['onMobile']     = sanitize_text_field( $_POST['onMobile'] );

		update_option( 'nta_wa_url', $new_input );
		wp_send_json_success();
	}

	public function set_account_position() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );

		$positions = Helper::sanitize_array( $_POST['positions'] );
		$type      = sanitize_text_field( $_POST['type'] );

		foreach ( $positions as $index => $id ) {
			update_post_meta( $id, "nta_wa_{$type}", $index );
		}

		wp_send_json_success();
	}

	public function load_accounts_ajax() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );
		$postType     = PostType::getInstance();
		$accountsList = $postType->get_posts();
		$results      = array_map(
			function ( $account ) {
				$meta        = get_post_meta( $account->ID, 'nta_wa_account_info', true );
				$avatar      = get_the_post_thumbnail_url( $account->ID );
				$wg_show     = get_post_meta( $account->ID, 'nta_wa_widget_show', true );
				$wg_position = get_post_meta( $account->ID, 'nta_wa_widget_position', true );
				$wc_show     = get_post_meta( $account->ID, 'nta_wa_wc_show', true );
				$wc_position = get_post_meta( $account->ID, 'nta_wa_wc_position', true );

				return array_merge(
					array(
						'accountId'       => $account->ID,
						'accountName'     => $account->post_title,
						'edit_link'       => get_edit_post_link( $account->ID ),
						'avatar'          => $avatar !== false ? $avatar : '',
						'widget_show'     => empty( $wg_show ) ? 'OFF' : $wg_show,
						'widget_position' => $wg_position,
						'wc_show'         => empty( $wc_show ) ? 'OFF' : $wc_show,
						'wc_position'     => $wc_position,
					),
					$meta
				);
			},
			$accountsList
		);
		wp_send_json_success( $results );
	}

	public function set_account_status() {
		check_ajax_referer( 'njt-wa-nonce', 'nonce', true );
		$id     = sanitize_text_field( $_POST['accountId'] );
		$type   = sanitize_text_field( $_POST['type'] );
		$status = sanitize_text_field( $_POST['status'] );

		$wg_position = get_post_meta( $id, 'nta_wa_widget_position', true );
		$wc_position = get_post_meta( $id, 'nta_wa_wc_position', true );

		if ( '' === $wg_position ) {
			update_post_meta( $id, 'nta_wa_widget_position', 0 );
		}

		if ( '' === $wc_position ) {
			update_post_meta( $id, 'nta_wa_wc_position', 0 );
		}

		update_post_meta( $id, "nta_wa_{$type}", $status );
		wp_send_json_success();
	}
}