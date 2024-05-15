<?php
if (!defined('ABSPATH')) {
	exit;
}

use NTA_WhatsApp\Helper;

function njt_wa_block_assets()
{ // phpcs:ignore
	// Register block styles for both frontend + backend.

	// wp_register_style(
	// 	'block-cgb-style-css', // Handle.
	// 	plugins_url('dist/blocks.style.build.css', dirname(__FILE__)), // Block style CSS.
	// 	is_admin() ? array('wp-editor') : null, // Dependency to include the CSS after it.
	// 	null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.style.build.css' ) // Version: File modification time.
	// );

	// Register block editor script for backend.
	wp_register_script(
		'block-cgb-block-js', // Handle.
		plugins_url('/dist/blocks.build.js', dirname(__FILE__)), // Block.build.js: We register the block here. Built with Webpack.
		array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'), // Dependencies, defined above.
		null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
		true // Enqueue the script in the footer.
	);

	// Register block editor styles for backend.
	// wp_register_style(
	// 	'block-cgb-block-editor-css', // Handle.
	// 	plugins_url('dist/blocks.editor.build.css', dirname(__FILE__)), // Block editor CSS.
	// 	array('wp-edit-blocks'), // Dependency to include the CSS after it.
	// 	null // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.editor.build.css' ) // Version: File modification time.
	// );

	// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
	wp_localize_script(
		'block-cgb-block-js',
		'njtwa', // Array containing dynamic data for a JS Global.
		[
			'pluginDirPath' 	=> plugin_dir_path(__DIR__),
			'pluginDirUrl'  	=> plugin_dir_url(__DIR__),
			'avatarDefaultUrl' 	=> NTA_WHATSAPP_PLUGIN_URL . 'assets/img/whatsapp_logo.svg',
			'gutenbergPreview'	=> NTA_WHATSAPP_PLUGIN_URL . 'assets/img/whatsapp-button-preview.png',
			'nonce'				=> wp_create_nonce('njt-wa-gutenberg')
			// Add more data here that you want to access from `cgbGlobal` object.
		]
	);

	register_block_type(
		'ninjateam/nta-whatsapp',
		array(
			'style'         => 'block-cgb-style-css',
			'editor_script' => 'block-cgb-block-js',
			'editor_style'  => 'block-cgb-block-editor-css',
			'render_callback' => 'njt_wa_button_render',
			'attributes' => array(
				'isSelectedAccount' => array(
					'type' => 'string',
					'default' => -1,
				),
				'phoneNumber' => array(
					'type' => 'string',
					'default' => "",
				),
				'imageID' => array(
					'type' => 'number',
					'default' => 0,
				),
				'imageAlt' => array(
					'type' => 'string',
					'default' => "img",
				),
				'imageUrl' => array(
					'type' => 'string',
					'default' => "",
				),
				'buttonStyle' => array(
					'type' => 'string',
					'default' => "round",
				),
				'buttonColor' => array(
					'type' => 'string',
					'default' => "#2DB742",
				),
				'buttonTitle' => array(
					'type' => 'string',
					'default' => "John Doe",
				),
				'buttonInfo' => array(
					'type' => 'string',
					'default' => "Need help? Chat with us",
				),
				'textColor' => array(
					'type' => 'string',
					'default' => "#fff",
				),
				'waUrl' => array(
					'type' => 'string',
					'default' => "",
				),
				'className' => array(
					'type' => 'string',
				),
				'preview' => array(
					'type' => 'boolean',
					'default' => false
				)
			),
		)
	);
}

function njt_wa_button_render($attributes)
{
	if ($attributes['isSelectedAccount'] != -1) {
        return do_shortcode("[njwa_button id={$attributes['isSelectedAccount']}]");
    } else {
        $avatarClass = $attributes['imageUrl'] ? "wa__btn_w_img" : "wa__btn_w_icon";
        $btnStyleClass = $attributes['buttonStyle'] == "round" ? "wa__r_button" : "wa__sq_button";
		$btnStyleClass .= empty($attributes['buttonTitle']) ? ' wa__button_text_only' : '';
        $btn_icon_or_image = '';
        if (empty($attributes['imageUrl'])) {
            $btn_icon_or_image = '<div class="wa__btn_icon"><img src="' . NTA_WHATSAPP_PLUGIN_URL . 'assets/img/whatsapp_logo.svg' . '" alt="' . esc_attr($attributes['imageAlt']) . '"/></div>';
        } else {
            $btn_icon_or_image = '<div class="wa__cs_img"><div class="wa__cs_img_wrap" style="background: url(' . esc_url($attributes['imageUrl']) . ') center center no-repeat; background-size: cover;"></div></div>';
        }
        $html = '';
        $html .= '<div style="margin: 30px 0 30px;">';
        $html .= '<a target="_blank" href="https://api.whatsapp.com/send?phone=' . esc_attr($attributes['phoneNumber']) . '" class="wa__button ' . $btnStyleClass . ' wa__stt_online ' . $avatarClass . '" style="background-color: ' . esc_attr($attributes['buttonColor']) . '; color: ' . esc_attr($attributes['textColor']) . '">';
        $html .= $btn_icon_or_image;
        $html .= '<div class="wa__btn_txt">';
		if (!empty($attributes['buttonTitle'])) {
			$html .= '<div class="wa__cs_info">';
			$html .= '<div class="wa__cs_name" style="color: ' . esc_attr($attributes['textColor']) . '">' . esc_html($attributes['buttonTitle']) . '</div>';
			$html .= '<div class="wa__cs_status">Online</div></div>';
		}
		$html .= '<div class="wa__btn_title">' . esc_html($attributes['buttonInfo']) . '</div></div></a></div>';
        return $html;
    }
}

function njt_wa_assets()
{
	wp_enqueue_style('nta-css-popup', NTA_WHATSAPP_PLUGIN_URL . 'assets/dist/css/style.css');
}

// Hook: Block assets.
add_action('init', 'njt_wa_block_assets');
add_action('enqueue_block_assets', 'njt_wa_assets');
