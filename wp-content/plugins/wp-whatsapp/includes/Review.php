<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'NjtReview' ) ) {
	class NjtReview {

		public $pluginPrefix = '';
		public $pluginName   = '';
		public $textDomain   = '';
		public $pluginDirURL = '';

		public $reviewed = false;

		protected static $instance = null;

		public function __construct( $pluginPrefix, $pluginName, $textDomain ) {
			$this->pluginPrefix = $pluginPrefix;
			$this->pluginName   = $pluginName;
			$this->textDomain   = $textDomain;
		}

		public static function get_instance( $pluginPrefix, $pluginName, $textDomain ) {
			if ( null === self::$instance ) {
				self::$instance = new self( $pluginPrefix, $pluginName, $textDomain );
				self::$instance->doHooks();
			}
			return self::$instance;
		}

		public function doHooks() {
			$option = get_option( "{$this->pluginPrefix}_review" );
			if ( time() >= (int) $option && $option !== '1' ) {
				add_action( 'admin_notices', array( $this, 'add_notification' ) );
				add_action( "wp_ajax_{$this->pluginPrefix}_save_review", array( $this, 'save_review' ) );
			}

			if ( $option === '1' ) {
				$this->reviewed = true;
			}
		}

		public function save_review() {
			check_ajax_referer( 'njt_wa_review_nonce', 'nonce', true );

			$field = sanitize_text_field( $_POST['field'] );

			if ( $field === 'later' ) {
				$this->need_update_option( 3 );
			} elseif ( $field === 'alreadyDid' || 'rateNow' === $field ) {
				update_option( "{$this->pluginPrefix}_review", 1 );
			}
			wp_send_json_success();
		}

		public function need_update_option( $days = null, $now = false ) {
			if ( $this->reviewed ) {
				return;
			}
			$time = $now === true ? time() : ( time() + ( $days * 60 * 60 * 24 ) );
			update_option( "{$this->pluginPrefix}_review", $time );
		}

		public function add_notification() {
			if ( function_exists( 'get_current_screen' ) ) {
				if ( get_current_screen()->id === 'plugins' || get_post_type() === 'whatsapp-accounts' ) {
					$selector = esc_attr( $this->pluginPrefix ) . '-review';
					?>
					<div class="notice notice-success is-dismissible" id="<?php echo esc_attr( $selector ); ?>">
						<h3><?php _e( "Give {$this->pluginName} a review" ); ?></h3>
						<p>
							<?php _e( "Thank you for choosing {$this->pluginName}. We hope you love it. Could you take a couple of seconds posting a nice review to share your happy experience?" ); ?>
						</p>
						<p>
							We will be forever grateful. Thank you in advance.
						</p>
						<p>
							<a href="javascript:;" data="rateNow" class="button button-primary" style="margin-right: 5px"><?php esc_html_e( 'Rate now', 'wp-whatsapp' ); ?></a>
							<a href="javascript:;" data="later" class="button" style="margin-right: 5px"><?php esc_html_e( 'Later', 'wp-whatsapp' ); ?></a>
							<a href="javascript:;" data="alreadyDid" class="button"><?php esc_html_e( 'No, thanks', 'wp-whatsapp' ); ?></a>
						</p>
					</div>
					<script>
					jQuery(document).ready(function () {
						jQuery('body').on('click', '#njt_wa-review a,#njt_wa-review button.notice-dismiss', function() {
							var thisElement = this;
							var fieldValue = jQuery(thisElement).attr("data");
							var link = "https://wordpress.org/support/plugin/wp-whatsapp/reviews/#new-post";
							var hidePopup = false;
							if (fieldValue == "rateNow") {
								window.open(link, "_blank");
							} else {
								hidePopup = true;
							}

							if (jQuery(thisElement).hasClass('notice-dismiss')) {
								fieldValue = 'later'
							}

							jQuery.ajax({
								dataType: 'json',
								url: window.ajaxurl,
								type: "post",
								data: {
									action: 'njt_wa_save_review',
									field: fieldValue,
									nonce: '<?php echo esc_attr( wp_create_nonce( 'njt_wa_review_nonce' ) ); ?>',
								},
								}).done(function (result) {
								if (result.success) {
									if (hidePopup == true) {
										jQuery('#njt_wa-review').hide("slow");
									}
								} else {
									console.log("Error", result.message);
									if (hidePopup == true) {
										jQuery('#njt_wa-review').hide("slow");
									}
								}
								}).fail(function (res) {
								console.log(res.responseText);

								if (hidePopup == true) {
									jQuery('#njt_wa-review').hide("slow");
								}
							});
						})
					});
					</script>
					<?php
				}
			}
		}
	}
}

if ( ! class_exists( 'NJTWhatsAppReview' ) ) {
	class NJTWhatsAppReview extends NjtReview {}
	NJTWhatsAppReview::get_instance( 'njt_wa', 'WhatsApp Plugin', 'wp-whatsapp' );
}



