<?php
namespace NTA_WhatsApp;

use NTA_WhatsApp\Fields;

defined( 'ABSPATH' ) || exit;
class Upgrade {

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

	public function doHooks() {
		add_action( 'admin_init', array( $this, 'init' ) );
	}

	public function init() {
		$restored           = get_option( 'nta_wa_restored', false );
		$restoredBackground = get_option( 'nta_wa_background_restored', false );

		if ( $restored === false || $restored == 0 ) {
			$old_posts = get_posts(
				array(
					'post_type'   => 'whatsapp-accounts',
					'post_status' => 'any',
					'numberposts' => -1,
					'fields'      => 'ids',
				)
			);
			if ( count( $old_posts ) > 0 ) {
				$this->runBackground( $restoredBackground );
				if ( $restoredBackground == 1 ) {
					add_action( 'admin_notices', array( $this, 'renderNotice' ) );
					add_action( 'wp_ajax_njt_wa_restore', array( $this, 'runRestore' ) );
				}
			} else {
				update_option( 'nta_wa_restored', 1 );
			}
		}
	}

	public function runBackground( $restoredBackground ) {
		if ( $restoredBackground === false || $restoredBackground == 0 ) {
			try {
				update_option( 'nta_wa_background_restored', 1 );
				$this->restoreMeta();
				$this->restoreOption();
				update_option( 'nta_wa_restored', 1 );
			} catch ( Exception $e ) {
				update_option( 'nta_wa_background_restored', 1 );
			}
		}
	}

	public function runRestore() {
		check_ajax_referer( 'nta_wa_restore_nonce', 'nonce', true );
		try {
			$this->restoreMeta( true );
			$this->restoreOption();
			update_option( 'nta_wa_restored', 1 );
		} catch ( Exception $e ) {
			wp_send_json_error(
				array(
					'message' => __( 'Please contact us! we can\'t restore your accounts!', 'wp-whatsapp' ),
					'content' => $e->getMessage(),
				)
			);
		}

		wp_send_json_success( array( 'message' => __( 'Restored Successfully!', 'wp-whatsapp' ) ) );
	}

	public function renderNotice() {
		?>
			<div class="notice notice-error is-dismissible" id="njt-wa-restore-wrapper">
				<div style="font-size: 1.3em; font-weight: 600; margin-top: 1em;">
					<?php _e( 'WhatsApp database update required', 'wp-whatsapp' ); ?>
				</div>
				<p>
					<span><?php _e( 'WhatsApp has been updated! To use the latest version, you have to update your database to make your WhatsApp accounts work correctly.', 'wp-whatsapp' ); ?></span>
					<div>
						<button class="button button-primary" id="nta-wa-restore">
							<strong><?php _e( 'Update WhatsApp Database', 'wp-whatsapp' ); ?></strong>
						</button>
					</div>
				</p>
			</div>
			<script>
			jQuery(document).ready(function() {
				jQuery('#nta-wa-restore').click(function() {
					jQuery(this).addClass("nta-updating-message")
					jQuery.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: {
							'action': 'njt_wa_restore',
							'nonce': '<?php echo wp_create_nonce( 'nta_wa_restore_nonce' ); ?>'
						}
					}).done(function(result) {
						if (result.success) {
							jQuery('#nta-wa-restore').removeClass("nta-updating-message")
							jQuery('#nta-wa-restore').hide()
							jQuery('#njt-wa-restore-wrapper span').html(result.data.message)
						} else {
							alert(result.data.message)
							console.log("Error", result.data.content)
						}
					});
				})
			});
			</script>
			<style>
			.nta-updating-message::before {
				vertical-align: bottom;
				animation: rotation 2s infinite linear;
				color: #f56e28;
				content: "\f463";
				display: inline-block;
				font: normal 20px/1 dashicons;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				vertical-align: middle;
				margin-bottom: 3px;
			}
			</style>
		<?php
	}

	public function daysOfWeekWorkingParse( $old_meta ) {
		$results    = array();
		$daysOfWeek = array( 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' );

		foreach ( $daysOfWeek as $dayKey ) {
			$timeString         = explode( '-', $old_meta[ "nta_{$dayKey}_working" ] );
			$results[ $dayKey ] = array(
				'isWorkingOnDay' => $old_meta[ "nta_{$dayKey}" ] == 'checked' ? 'ON' : 'OFF',
				'workHours'      => array(
					array(
						'startTime' => $timeString[0],
						'endTime'   => $timeString[1],
					),
				),
			);
		}
		return $results;
	}

	public function cleanOldRestored( $old_posts ) {
		foreach ( $old_posts as $old_post ) {
			delete_post_meta( $old_post->ID, 'nta_wa_account_info' );
			delete_post_meta( $old_post->ID, 'nta_wa_button_styles' );
			delete_post_meta( $old_post->ID, 'nta_wa_widget_show' );
			delete_post_meta( $old_post->ID, 'nta_wa_widget_position' );
			delete_post_meta( $old_post->ID, 'nta_wa_wc_show' );
			delete_post_meta( $old_post->ID, 'nta_wa_wc_position' );
		}

		delete_option( 'nta_wa_widget_styles' );
		delete_option( 'nta_wa_widget_display' );
		delete_option( 'nta_wa_woocommerce' );
		delete_option( 'nta_wa_analytics' );
	}

	public function restoreMeta( $cleanBefore = false ) {
		$old_posts = get_posts(
			array(
				'post_type'   => 'whatsapp-accounts',
				'post_status' => 'any',
				'numberposts' => -1,
				'meta_query'  => array(
					array(
						'key'     => 'nta_whatsapp_accounts',
						'value'   => null,
						'compare' => '!=',
					),
				),
			)
		);

		if ( $cleanBefore === true ) {
			$this->cleanOldRestored( $old_posts );
		}

		if ( count( $old_posts ) > 0 ) {
			foreach ( $old_posts as $old_post ) {
				$old_meta_info = get_post_meta( $old_post->ID, 'nta_whatsapp_accounts', true );
				if ( $old_meta_info !== false ) {
					$new_meta_info = array(
						'accountName'       => $old_post->post_title,
						'title'             => $old_meta_info['nta_title'],
						'number'            => $old_meta_info['nta_group_number'],
						'willBeBackText'    => $old_meta_info['nta_offline_text'],
						'dayOffsText'       => $old_meta_info['nta_over_time'],
						'predefinedText'    => $old_meta_info['nta_predefined_text'],
						'isAlwaysAvailable' => ( isset( $old_meta_info['nta_button_available'] ) && $old_meta_info['nta_button_available'] == 'ON' ) ? 'ON' : 'OFF',
						'daysOfWeekWorking' => $this->daysOfWeekWorkingParse( $old_meta_info ),
					);

					$widget_show = $old_meta_info['nta_active'] == 'active' ? 'ON' : 'OFF';
					$wc_show     = $old_meta_info['wo_active'] == 'active' ? 'ON' : 'OFF';

					$widget_position = $old_meta_info['position'];
					$wc_position     = $old_meta_info['wo_position'];
				}

				$old_button_styles = get_post_meta( $old_post->ID, 'nta_wabutton_style', true );

				if ( $old_button_styles === false ) {
					$new_meta_styles = array(
						'type'            => 'round',
						'backgroundColor' => '#2DB742',
						'textColor'       => '#fff',
						'label'           => __( 'Need Help? Chat with us', 'wp-whatsapp' ),
						'width'           => 300,
						'height'          => 64,
					);
				} else {
					$new_meta_styles = array(
						'type'            => empty( $old_button_styles['button_style'] ) ? 'round' : $old_button_styles['button_style'],
						'backgroundColor' => empty( $old_button_styles['button_back_color'] ) ? '#2DB742' : $old_button_styles['button_back_color'],
						'textColor'       => empty( $old_button_styles['button_text_color'] ) ? '#fff' : $old_button_styles['button_text_color'],
						'label'           => empty( $old_button_styles['button-text'] ) ? 'Need Help? Chat with us' : $old_button_styles['button-text'],
						'width'           => 300,
						'height'          => 64,
					);
				}

				update_post_meta( $old_post->ID, 'nta_wa_account_info', $new_meta_info );
				update_post_meta( $old_post->ID, 'nta_wa_button_styles', $new_meta_styles );
				update_post_meta( $old_post->ID, 'nta_wa_widget_show', $widget_show );
				update_post_meta( $old_post->ID, 'nta_wa_widget_position', $widget_position );
				update_post_meta( $old_post->ID, 'nta_wa_wc_show', $wc_show );
				update_post_meta( $old_post->ID, 'nta_wa_wc_position', $wc_position );
			}
		}
	}

	public static function restoreOption() {
		$old_option = get_option( 'nta_whatsapp_setting', false );
		if ( $old_option !== false ) {
			$new_option_styles = array(
				'title'              => $old_option['widget_name'],
				'responseText'       => $old_option['widget_responseText'],
				'description'        => $old_option['widget_description'],
				'backgroundColor'    => $old_option['back_color'],
				'textColor'          => $old_option['text_color'],
				'scrollHeight'       => 500,
				'isShowScroll'       => 'OFF',
				'isShowResponseText' => 'ON',
				'isShowPoweredBy'    => 'ON',

				'btnLabel'           => $old_option['widget_label'],
				'btnLabelWidth'      => 156,
				'btnPosition'        => $old_option['widget_position'],
				'btnLeftDistance'    => 30,
				'btnRightDistance'   => 30,
				'btnBottomDistance'  => 30,
				'isShowBtnLabel'     => 'ON',

				'isShowGDPR'         => ( isset( $old_option['show_gdpr'] ) && $old_option['show_gdpr'] == 'ON' ) ? 'ON' : 'OFF',
				'gdprContent'        => $old_option['widget_gdpr'],
			);

			$new_option_display = array(
				'displayCondition' => $old_option['display-pages'] == 'show' ? 'includePages' : 'excludePages',
				'includePages'     => ! empty( $old_option['nta-wa-show-pages'] ) ? $old_option['nta-wa-show-pages'] : array(),
				'excludePages'     => ! empty( $old_option['nta-wa-hide-pages'] ) ? $old_option['nta-wa-hide-pages'] : array(),
				'showOnDesktop'    => ( isset( $old_option['show_on_desktop'] ) && $old_option['show_on_desktop'] == 'ON' ) ? 'ON' : 'OFF',
				'showOnMobile'     => ( isset( $old_option['show_on_mobile'] ) && $old_option['show_on_mobile'] == 'ON' ) ? 'ON' : 'OFF',
				'time_symbols'     => 'h:m',
			);

		} else {
			$new_option_styles  = Fields::getWidgetStyles();
			$new_option_display = Fields::getWidgetDisplay();
		}

		update_option( 'nta_wa_widget_styles', $new_option_styles );
		update_option( 'nta_wa_widget_display', $new_option_display );

		$old_option = get_option( 'nta_wa_woobutton_setting', false );
		if ( $old_option !== false ) {
			$new_option = array(
				'position' => $old_option['nta_woo_button_position'],
				'isShow'   => ! empty( $old_option['nta_woo_button_status'] ) ? 'ON' : 'OFF',
			);
		} else {
			$new_option = Fields::getWoocommerceSetting();
		}
		update_option( 'nta_wa_woocommerce', $new_option );

		$old_option = get_option( 'nta_wa_ga_setting', false );
		if ( $old_option !== false ) {
			$new_option = array(
				'enabledGoogle'   => 'ON',
				'enabledFacebook' => 'OFF',
			);
		}
		update_option( 'nta_wa_analytics', $new_option );
	}
}
