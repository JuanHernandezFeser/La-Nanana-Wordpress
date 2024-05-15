<?php
if (!defined('BLOCKBOOSTER_VERSION')) {
    // Replace the version number of the theme on each release.
    define('BLOCKBOOSTER_VERSION', wp_get_theme()->get('Version'));
}
define('BLOCKBOOSTER_DEBUG', defined('WP_DEBUG') && WP_DEBUG === true);
define('BLOCKBOOSTER_DIR', trailingslashit(get_template_directory()));
define('BLOCKBOOSTER_URL', trailingslashit(get_template_directory_uri()));

if (!function_exists('blockbooster_support')) :

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * @since walker_fse 1.0.0
     *
     * @return void
     */
    function blockbooster_support()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        // Add support for block styles.
        add_theme_support('wp-block-styles');
        add_theme_support('post-thumbnails');
        // Enqueue editor styles.
        add_editor_style('style.css');
    }

endif;
add_action('after_setup_theme', 'blockbooster_support');

/*----------------------------------------------------------------------------------
Enqueue Styles
-----------------------------------------------------------------------------------*/
if (!function_exists('blockbooster_styles')) :
    function blockbooster_styles()
    {
        // registering style for theme
        wp_enqueue_style('blockbooster-style', get_stylesheet_uri(), array(), BLOCKBOOSTER_VERSION);
        wp_enqueue_style('blockbooster-blocks-style', get_template_directory_uri() . '/assets/css/blocks.css');
        wp_enqueue_style('blockbooster-aos-style', get_template_directory_uri() . '/assets/css/aos.css');
        if (is_rtl()) {
            wp_enqueue_style('blockbooster-rtl-css', get_template_directory_uri() . '/assets/css/rtl.css', 'rtl_css');
        }
        wp_enqueue_script('jquery');
        wp_enqueue_script('blockbooster-aos-scripts', get_template_directory_uri() . '/assets/js/aos.js', array(), BLOCKBOOSTER_VERSION, true);
        wp_enqueue_script('blockbooster-scripts', get_template_directory_uri() . '/assets/js/blockbooster-scripts.js', array(), BLOCKBOOSTER_VERSION, true);
    }
endif;

add_action('wp_enqueue_scripts', 'blockbooster_styles');

/**
 * Enqueue scripts for admin area
 */
function blockbooster_admin_style()
{
    $hello_notice_current_screen = get_current_screen();
    if (!empty($_GET['page']) && 'about-blockbooster' === $_GET['page'] || $hello_notice_current_screen->id === 'themes' || $hello_notice_current_screen->id === 'dashboard') {
        wp_enqueue_style('blockbooster-admin-style', get_template_directory_uri() . '/assets/css/admin-style.css', array(), BLOCKBOOSTER_VERSION, 'all');
        wp_enqueue_script('blockbooster-admin-scripts', get_template_directory_uri() . '/assets/js/blockbooster-admin-scripts.js', array(), BLOCKBOOSTER_VERSION, true);
        wp_enqueue_script('blockbooster-welcome-notice', get_template_directory_uri() . '/inc/admin/js/blockbooster-welcome-notice.js', array('jquery'), BLOCKBOOSTER_VERSION, true);
        wp_localize_script('blockbooster-welcome-notice', 'blockbooster_localize', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'redirect_url' => admin_url('themes.php?page=_cozy_companions')
        ));
    }
}
add_action('admin_enqueue_scripts', 'blockbooster_admin_style');

/**
 * Enqueue assets scripts for both backend and frontend
 */
function blockbooster_block_assets()
{
    wp_enqueue_style('blockbooster-blocks-style', get_template_directory_uri() . '/assets/css/blocks.css');
}
add_action('enqueue_block_assets', 'blockbooster_block_assets');

/**
 * Load core file.
 */
require_once get_template_directory() . '/inc/core/init.php';

/**
 * Load welcome page file.
 */
require_once get_template_directory() . '/inc/admin/welcome-notice.php';

if (!function_exists('blockbooster_excerpt_more_postfix')) {
    function blockbooster_excerpt_more_postfix($more)
    {
        if (is_admin()) {
            return $more;
        }
        return '...';
    }
    add_filter('excerpt_more', 'blockbooster_excerpt_more_postfix');
}
function blockbooster_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'blockbooster_add_woocommerce_support');
