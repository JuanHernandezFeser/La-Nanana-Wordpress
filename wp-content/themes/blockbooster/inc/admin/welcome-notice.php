<?php

/**
 * file for holding dashboard welcome page for theme
 */
if (!function_exists('blockbooster_is_plugin_installed')) {
    function blockbooster_is_plugin_installed($plugin_slug)
    {
        $plugin_path = WP_PLUGIN_DIR . '/' . $plugin_slug;
        return file_exists($plugin_path);
    }
}
if (!function_exists('blockbooster_is_plugin_activated')) {
    function blockbooster_is_plugin_activated($plugin_slug)
    {
        return is_plugin_active($plugin_slug);
    }
}
if (!function_exists('blockbooster_welcome_notice')) :
    function blockbooster_welcome_notice()
    {
        if (get_option('blockbooster_dismissed_custom_notice')) {
            return;
        }
        global $pagenow;
        $current_screen  = get_current_screen();

        if (is_admin()) {
            if ($current_screen->id !== 'dashboard' && $current_screen->id !== 'themes') {
                return;
            }
            if (is_network_admin()) {
                return;
            }
            if (!current_user_can('manage_options')) {
                return;
            }
            $theme = wp_get_theme();

            if (is_child_theme()) {
                $theme = wp_get_theme()->parent();
            }
            $blockbooster_version = $theme->get('Version');


?>
            <div class="blockbooster-admin-notice notice notice-info is-dismissible content-install-plugin theme-info-notice" id="blockbooster-dismiss-notice">
                <div class="info-content">
                    <div class="notice-holder">
                        <h5><span class="theme-name"><span><?php echo __('Welcome to Blockbooster', 'blockbooster'); ?></span></h5>
                        <h1><?php echo __('Optimize your workflow effortlessly! Cozy Blocks pairs perfectly with our 30+ Advanced Blocks—install for full benefits', 'blockbooster'); ?></h1>
                        </h3>
                        <div class="notice-text">
                            <p><?php echo __('Enhance your experience with our feature-rich 30+ Advanced Blocks! Unlock the full potential by integrating Cozy Addons today. Explore a multitude of powerful tools and functionalities designed to elevate your user experience.', 'blockbooster') ?></p>
                            <p><?php echo __('Please install and activate all recommended to use blcoks and demo importer features- Cozy Addons, Cozy Essential Addons, Advanced Import.', 'blockbooster') ?></p>
                        </div>
                        <a href="#" id="install-activate-button" class="button admin-button info-button"><?php echo __('Getting started with a single click', 'blockbooster'); ?></a>
                        <a href="<?php echo admin_url(); ?>themes.php?page=about-blockbooster" class="button admin-button info-button"><?php echo __('Explore Blockbooster', 'blockbooster'); ?></a>

                    </div>
                </div>
                <div class="theme-hero-screens">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/inc/admin/images/screen_plugin_ct.png'); ?>" />
                </div>

            </div>
    <?php
        }
    }
endif;
add_action('admin_notices', 'blockbooster_welcome_notice');
function blockbooster_dismissble_notice()
{
    update_option('blockbooster_dismissed_custom_notice', 1);
}
add_action('wp_ajax_blockbooster_dismissble_notice', 'blockbooster_dismissble_notice');
// Hook into a custom action when the button is clicked
add_action('wp_ajax_blockbooster_install_and_activate_plugins', 'blockbooster_install_and_activate_plugins');
add_action('wp_ajax_nopriv_blockbooster_install_and_activate_plugins', 'blockbooster_install_and_activate_plugins');
add_action('wp_ajax_blockbooster_rplugin_activation', 'blockbooster_rplugin_activation');
add_action('wp_ajax_nopriv_blockbooster_rplugin_activation', 'blockbooster_rplugin_activation');

// Function to install and activate the plugins



function check_plugin_installed_status($pugin_slug, $plugin_file)
{
    return file_exists(ABSPATH . 'wp-content/plugins/' . $pugin_slug . '/' . $plugin_file) ? true : false;
}

/* Check if plugin is activated */


function check_plugin_active_status($pugin_slug, $plugin_file)
{
    return is_plugin_active($pugin_slug . '/' . $plugin_file) ? true : false;
}

require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/misc.php');
require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
function blockbooster_install_and_activate_plugins()
{
    // Define the plugins to be installed and activated
    $recommended_plugins = array(
        array(
            'slug' => 'cozy-addons',
            'file' => 'cozy-addons.php',
            'name' => 'Cozy Addons'
        ),
        array(
            'slug' => 'advanced-import',
            'file' => 'advanced-import.php',
            'name' => 'Advanced Imporrt'
        ),
        array(
            'slug' => 'cozy-essential-addons',
            'file' => 'cozy-essential-addons.php',
            'name' => 'Cozy Essential Addons'
        ),
        // Add more plugins here as needed
    );

    // Include the necessary WordPress functions


    // Set up a transient to store the installation progress
    set_transient('install_and_activate_progress', array(), MINUTE_IN_SECONDS * 10);

    // Loop through each plugin
    foreach ($recommended_plugins as $plugin) {
        $plugin_slug = $plugin['slug'];
        $plugin_file = $plugin['file'];
        $plugin_name = $plugin['name'];

        // Check if the plugin is active
        if (is_plugin_active($plugin_slug . '/' . $plugin_file)) {
            update_install_and_activate_progress($plugin_name, 'Already Active');
            continue; // Skip to the next plugin
        }

        // Check if the plugin is installed but not active
        if (is_blockbooster_plugin_installed($plugin_slug . '/' . $plugin_file)) {
            $activate = activate_plugin($plugin_slug . '/' . $plugin_file);
            if (is_wp_error($activate)) {
                update_install_and_activate_progress($plugin_name, 'Error');
                continue; // Skip to the next plugin
            }
            update_install_and_activate_progress($plugin_name, 'Activated');
            continue; // Skip to the next plugin
        }

        // Plugin is not installed or activated, proceed with installation
        update_install_and_activate_progress($plugin_name, 'Installing');

        // Fetch plugin information
        $api = plugins_api('plugin_information', array(
            'slug' => $plugin_slug,
            'fields' => array('sections' => false),
        ));

        // Check if plugin information is fetched successfully
        if (is_wp_error($api)) {
            update_install_and_activate_progress($plugin_name, 'Error');
            continue; // Skip to the next plugin
        }

        // Set up the plugin upgrader
        $upgrader = new Plugin_Upgrader();
        $install = $upgrader->install($api->download_link);

        // Check if installation is successful
        if ($install) {
            // Activate the plugin
            $activate = activate_plugin($plugin_slug . '/' . $plugin_file);

            // Check if activation is successful
            if (is_wp_error($activate)) {
                update_install_and_activate_progress($plugin_name, 'Error');
                continue; // Skip to the next plugin
            }
            update_install_and_activate_progress($plugin_name, 'Activated');
        } else {
            update_install_and_activate_progress($plugin_name, 'Error');
        }
    }

    // Delete the progress transient
    $redirect_url = admin_url('themes.php?page=advanced-import');

    // Delete the progress transient
    delete_transient('install_and_activate_progress');
    // Return JSON response
    wp_send_json_success(array('redirect_url' => $redirect_url));
}

// Function to check if a plugin is installed but not active
function is_blockbooster_plugin_installed($plugin_slug)
{
    $plugins = get_plugins();
    return isset($plugins[$plugin_slug]);
}

// Function to update the installation and activation progress
function update_install_and_activate_progress($plugin_name, $status)
{
    $progress = get_transient('install_and_activate_progress');
    $progress[] = array(
        'plugin' => $plugin_name,
        'status' => $status,
    );
    set_transient('install_and_activate_progress', $progress, MINUTE_IN_SECONDS * 10);
}


function blockbooster_dashboard_menu()
{
    add_theme_page(esc_html__('About Blockbooster', 'blockbooster'), esc_html__('About Blockbooster', 'blockbooster'), 'edit_theme_options', 'about-blockbooster', 'blockbooster_theme_info_display');
}
add_action('admin_menu', 'blockbooster_dashboard_menu');
function blockbooster_theme_info_display()
{ ?>
    <div class="dashboard-about-blockbooster">
        <div class="blockbooster-dashboard">
            <ul id="blockbooster-dashboard-tabs-nav">
                <li><a href="#blockbooster-welcome"><?php echo __('Get Started', 'blockbooster') ?></a></li>
                <li><a href="#blockbooster-setup"><?php echo __('Setup Instruction', 'blockbooster') ?></a></li>
                <li><a href="#blockbooster-comparision"><?php echo __('FREE VS PRO', 'blockbooster') ?></a></li>
            </ul> <!-- END tabs-nav -->
            <div id="tabs-content">
                <div id="blockbooster-welcome" class="tab-content">
                    <h1> <?php echo __('Welcome to the Blockbooster - Ultimate WooCommerce Theme for Shopping', 'blockbooster') ?></h1>
                    <p><?php echo __('Introducing Blockbooster, the ultimate Full Site Editing (FSE) WordPress theme designed to empower you to create any type of website with unparalleled ease. With its innovative block-based approach and intuitive interface, Blockbooster allows you to effortlessly customize every aspect of your site, from headers to footers, to perfectly match your vision. Crafted with a focus on simplicity and versatility, Blockbooster\'s minimalistic design ensures that your content remains the center of attention, while its robust features provide the flexibility needed to bring your ideas to life. Whether you are a startup, motivational speakers, influencers, law firm, ideal blog, business, freelancer, or marketing agency or any type of Corporate website. Blockbooster offers the tools and flexibility you need to build a stunning, professional-grade website in no time. With a wide range of ready-to-use pre-built patterns, Blockbooster enables you to get your site up and running within minutes, eliminating the need for extensive customization. Say goodbye to limitations and hello to limitless possibilities with Blockbooster - the best FSE WordPress theme for unlocking your creativity effortlessly. Explore Blockbooster at https://cozythemes.com/blockbooster-free-wordpress-theme/.
', 'blockbooster') ?></p>
                    <h3><?php echo __('Required Plugins', 'blockbooster'); ?></h3>
                    <p class="notice-text"><?php echo __('Please install and activate all recommended pluign to import the demo with "one click demo import" feature and unlock premium features!(for pro version)', 'blockbooster') ?></p>
                    <ul class="blockbooster-required-plugin">
                        <li>

                            <h4><?php echo __('1. Cozy Addons', 'blockbooster'); ?>
                                <?php
                                if (blockbooster_is_plugin_activated('cozy-addons/cozy-addons.php')) {
                                    echo __(': Plugin has been already activated!', 'blockbooster');
                                } elseif (blockbooster_is_plugin_installed('cozy-addons/cozy-addons.php')) {
                                    echo __(': Plugin does not activated, Activate the plugin to use one click demo import and unlock premium features.', 'blockbooster');
                                } else {
                                    echo ': <a href="' . get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=cozy-addons&TB_iframe=true&width=600&height=550">' . esc_html__('Install and Activate', 'blockbooster') . '</a>';
                                }
                                ?>
                            </h4>
                        </li>
                        <li>

                            <h4><?php echo __('2. Cozy Essential Addons', 'blockbooster'); ?>
                                <?php
                                if (blockbooster_is_plugin_activated('cozy-essential-addons/cozy-essential-addons.php')) {
                                    echo __(': Plugin has been already activated!', 'blockbooster');
                                } elseif (blockbooster_is_plugin_installed('cozy-essential-addons/cozy-essential-addons.php')) {
                                    echo __(': Plugin does not activated, Activate the plugin to use one click demo import and unlock premium features.', 'blockbooster');
                                } else {
                                    echo ': <a href="' . get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=cozy-essential-addons&TB_iframe=true&width=600&height=550">' . esc_html__('Install and Activate', 'blockbooster') . '</a>';
                                }
                                ?>
                            </h4>
                        </li>
                        <li>
                            <h4><?php echo __('3. Advanced Import - Need only to use "one click demo import" features', 'blockbooster'); ?>
                                <?php
                                if (blockbooster_is_plugin_activated('advanced-import/advanced-import.php')) {
                                    echo __(': Plugin has been already activated!', 'blockbooster');
                                } elseif (blockbooster_is_plugin_installed('advanced-import/advanced-import.php')) {
                                    echo __(': Plugin does not activated, Activate the plugin to use one click demo import feature.', 'blockbooster');
                                } else {
                                    echo ': <a href="' . get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=advanced-import&TB_iframe=true&width=600&height=550">' . esc_html__('Install and Activate', 'blockbooster') . '</a>';
                                }
                                ?>
                            </h4>
                        </li>
                    </ul>
                    <a href="#" id="install-activate-button" class="button admin-button info-button"><?php echo __('Getting started with a single click', 'blockbooster'); ?></a>
                </div>
                <div id="blockbooster-setup" class="tab-content">
                    <h3 class="blockbooster-baisc-guideline-header"><?php echo __('Basic Theme Setup', 'blockbooster') ?></h3>
                    <div class="blockbooster-baisc-guideline">
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Setup Header Layout:', 'blockbooster') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Patterns -> Template Parts -> Header:', 'blockbooster') ?></li>
                                        <li> - <?php echo __('click on Header > Click on Edit (Icon) -> Add or Remove Requirend block/content as your requirement.:', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on save to update your layout', 'blockbooster') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Setup Footer Layout:', 'blockbooster') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Patterns -> Template Parts -> Footer:', 'blockbooster') ?></li>
                                        <li> - <?php echo __('click on Footer > Click on Edit (Icon) > Add or Remove Requirend block/content as your requirement.:', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on save to update your layout', 'blockbooster') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Setup Templates like Homepage/404/Search/Page/Single and more templates Layout:', 'blockbooster') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Templates:', 'blockbooster') ?></li>
                                        <li> - <?php echo __('click on Template(You need to edit/update) > Click on Edit (Icon) > Add or Remove Requirend block/content as your requirement.:', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on save to update your layout', 'blockbooster') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Restore/Reset Default Content layout of Template(Like: Frontpage/Blog/Archive etc.)', 'blockbooster') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Templates:', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on Manage all Templates', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on 3 Dots icon at right side of respective Template', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on Clear Customization', 'blockbooster') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="featured-box">
                            <ul>
                                <li><strong><?php echo __('Restore/Reset Default Content layout of Template Parts(Header/Footer/Sidebar)', 'blockbooster') ?></strong>
                                    <ul>
                                        <li> - <?php echo __('Go to Appearance -> Editor -> Patterns:', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on Manage All Template Parts', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on 3 Dots icon at right side of respective Template parts', 'blockbooster') ?></li>
                                        <li> - <?php echo __('Click on Clear Customization', 'blockbooster') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div id="blockbooster-comparision" class="tab-content">
                    <div class="featured-list">
                        <div class="half-col free-features">
                            <h3><?php echo __('Blockbooster Features (Free)', 'blockbooster') ?></h3>
                            <ul>
                                <li><strong> - <?php echo __('blockbooster offer wide range of  ready to use Home Sections Patterns', 'blockbooster') ?></strong>
                                    <ul>
                                        <li><?php echo __('Banner Section', 'blockbooster') ?></li>
                                        <li><?php echo __('About Us Section - 2', 'blockbooster') ?></li>
                                        <li><?php echo __('Services Section - 2', 'blockbooster') ?></li>
                                        <li><?php echo __('Featured Work Section', 'blockbooster') ?></li>
                                        <li><?php echo __('Testimonial Section - 3', 'blockbooster') ?></li>
                                        <li><?php echo __('Call To Action Section - 3', 'blockbooster') ?></li>
                                        <li><?php echo __('Pricing Table Section - 3', 'blockbooster') ?></li>
                                        <li><?php echo __('FAQ Section ', 'blockbooster') ?></li>
                                        <li><?php echo __('Counter Section - 2', 'blockbooster') ?></li>
                                        <li><?php echo __('Latest Post Display Section -2 ', 'blockbooster') ?></li>
                                        <li><?php echo __('Brands Logo Showcase', 'blockbooster') ?></li>
                                    </ul>
                                </li>

                                <li> <strong>- <?php echo __('FSE Templates Ready', 'blockbooster') ?></strong>
                                    <ul>
                                        <li> <?php echo __('404 Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Archive Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Product Catalog Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Blank Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Front Page Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Blog Home Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Index Page Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Search Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Sitemap Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Page Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Left Sidebar Page Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Right sidebar page  Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Single Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Single Product Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Cart Page Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Checkout Page Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Left Sidebar Single Template', 'blockbooster') ?></li>
                                        <li> <?php echo __('Full Width Single Template', 'blockbooster') ?></li>

                                    </ul>
                                <li>
                                <li><strong> - <?php echo __('Fully Customizable Header Layout', 'blockbooster') ?></strong></li>
                                <li> <strong>- <?php echo __('Fully Customizable Footer Layout ', 'blockbooster') ?></strong></li>
                                <li><strong> - <?php echo __('12+ Beautiful Fonts Option', 'blockbooster') ?></strong></li>
                                <li> <strong>- <?php echo __('On Scroll Animation option', 'blockbooster') ?></strong></li>
                                <li> <strong>- <?php echo __('One Click Demo Import Features', 'blockbooster') ?></strong></li>
                                <li> <strong>- <?php echo __('Access Cozy Blocks with upto 20+ Advanced Blocks for FSE and Gutenberg Editor', 'blockbooster') ?></strong></li>
                            </ul>
                        </div>
                        <div class="half-col pro-features">
                            <h3><?php echo __('Premium Features', 'blockbooster') ?></h3>
                            <p><?php echo __('Including all free features and comes with 30+ advanced blocks that enhance and power up the ecommerce website, here are some blocks that add the powerful features for your ecommerce/shopping website. By seamlessly integrating these blocks with our ready-to-use patterns, you have the flexibility to craft a wide selection of captivating online store ease', 'blockbooster') ?></p>
                            <ul>
                                <li><?php echo __('Slider Block', 'blockbooster') ?></li>
                                <li><?php echo __('Counter Block', 'blockbooster') ?></li>
                                <li><?php echo __('Team Block', 'blockbooster') ?></li>
                                <li><?php echo __('Testimonial Block', 'blockbooster') ?></li>
                                <li><?php echo __('Product slider', 'blockbooster') ?></li>
                                <li><?php echo __('Product Grid/Carousel', 'blockbooster') ?></li>
                                <li><?php echo __('Product Category', 'blockbooster') ?></li>
                                <li><?php echo __('Products Showcase Tab', 'blockbooster') ?></li>
                                <li><?php echo __('All Product Reviews', 'blockbooster') ?></li>
                                <li><?php echo __('Post Blocks', 'blockbooster') ?></li>
                                <li><?php echo __('Social Shares Blocks', 'blockbooster') ?></li>
                                <li><?php echo __('Social Icons Blocks', 'blockbooster') ?></li>
                                <li><?php echo __('Breadcrumbs Block', 'blockbooster') ?></li>
                                <li><?php echo __('Portfolio Block with Custom Post Type', 'blockbooster') ?></li>
                                <li><?php echo __('Popup buidler Block to display offer and flash sale', 'blockbooster') ?>
                                    <?php echo __('and access', 'blockbooster') ?> <a href="https://cozythemes.com/cozy-addons/" target="_blank"><?php echo __('Cozy Blocks with more than 30+ advanced block.', 'blockbooster') ?></a>
                                </li>

                            </ul>
                            <br />
                            <a href="https://cozythemes.com/pricing-and-plans/" class="upgrade-btn button" target="_blank"><?php echo __('Upgrade to Pro', 'blockbooster') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
