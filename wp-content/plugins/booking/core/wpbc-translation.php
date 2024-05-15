<?php 
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Translations Functions
 * @category Functions
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 29.09.2015
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


// Check content according  [lang=xx_XX]  shortcode
add_bk_filter( 'wpdev_check_for_active_language', 'wpdev_check_for_active_language' );


/**
 *  Rechecking about the locale on this hook:
 *  								            add_action( 'plugins_loaded', 'wpbc_load_translation', 1000 );
 *  which is executed early then
 * 									            add_action( 'admin_init', 'wpbc_check_...' );
*/
add_action( 'plugins_loaded', 'wpbc_load_translation', 1000 );              // Load translation


/**
 * Load translation   -  Load country list after locale defined by some other translation  plugin.
 */
function wpbc_load_translation(){

	//define( 'WPBC_LOCALE_RELOAD', 'fr_FR' ); wpbc_load_plugin_translation_file__mo( WPBC_LOCALE_RELOAD );


	// Get reloaded 'booking'  or current WordPress locale
	$locale = wpbc_get_maybe_reloaded_booking_locale();             // if NOT defined WPBC_LOCALE_RELOAD define by  current  WordPress locale

	//FixIn: 8.9.4.5
	if (  is_admin() && ( defined( 'DOING_AJAX' ) ) && ( DOING_AJAX )  ) {
		//Reload locale for Ajax request
		wpbc_check_ajax_locale__reload_it( $locale );               // Contain -->     if ( ! defined( 'WPBC_LOCALE_RELOAD' ) ) define( 'WPBC_LOCALE_RELOAD', esc_js( $_REQUEST['wpdev_active_locale'] ) );
	}

	wpbc_load_country_list_file_php( $locale );

    if ( ! wpbc_load_plugin_translation_file__mo( $locale ) ) {
        wpbc_load_plugin_translation_file__mo( 'en_US' );               // Load default English translation,  if other not available
    }
}


/**
 * Check ( $_REQUEST['wpdev_active_locale'] ) and Reload specific Locale for the Ajax request
 */
function wpbc_check_ajax_locale__reload_it( $reloaded_locale ) {                                                                                 //FixIn: 8.4.5.1

	if (  ( isset( $_REQUEST['wpdev_active_locale'] ) ) ||                                                                  // Reload locale according request parameter
	      ( isset( $_REQUEST['wpbc_ajx_locale'] ) )
	) {                                                                  // Reload locale according request parameter

		unload_textdomain( 'booking' );

		/*
		 * Removed because,  it's configured in  $locale = wpbc_get_maybe_reloaded_booking_locale();  which is executed before this action
		 *
	        if ( ! defined( 'WPBC_LOCALE_RELOAD' ) ) {
		        define( 'WPBC_LOCALE_RELOAD', esc_js( $_REQUEST['wpdev_active_locale'] ) );
	        }
		*/
		add_filter( 'locale', 'wpbc_get_maybe_reloaded_booking_locale', 999 );             // Set filter to load the locale of the Booking Calendar

		/**
		 * It's commented because inside of the function load_default_textdomain();  exist:  unload_textdomain( 'default' );  which  is unset( $l10n[ 'default' ] );
		 *
		    // Reload locale settings, it's required for the correct  dates format
		    //if ( isset( $l10n['default'] ) ) { unset( $l10n['default'] ); } // Unload locale
		 */
	    load_default_textdomain();                                          // Load default locale

	    global $wp_locale;
	    $wp_locale = new WP_Locale();                                       // Reload class

	    wpbc_load_plugin_translation_file__mo( $reloaded_locale );


		$current_locale = wpbc_get_current_wordpress_locale();              // 'en_US'  or 'it_IT',  etc...

	    if ( $current_locale != $reloaded_locale ) {

			// The switch_to_locale() function is loaded before it can actually be used.
			if ( function_exists( 'switch_to_locale' ) && isset( $GLOBALS['wp_locale_switcher'] ) ) {
			    $switched_locale = switch_to_locale( $reloaded_locale );
		    } else {
			    load_default_textdomain( $reloaded_locale );
		    }
	    }

    }
}


/**
 * Load MO translation file of plugin,  like    ../wp-content/plugins/booking/languages/booking-fr_FR.mo
 *
 * System firstly try to load MO  from dir:     ../wp-content/plugins/booking/languages
 * then from:                                   ../wp-content/languages/plugins/'
 * otherwise:       unload 'booking' textdomain
 *
 * @param string $locale        default ''
 *
 *                              can be passed like: 'en_US' or 'it_IT'
 *
 *                              if '' then  get  WPBC_LOCALE_RELOAD  locale,
 *                              or if not reloaded then get current WordPress locale
 *                              and define WPBC_LOCALE_RELOAD
 *
 * @return bool
 */
function wpbc_load_plugin_translation_file__mo( $locale = '' ) {

	$is_mo_loaded = false;
	$domain       = 'booking';

	if ( empty( $locale ) ) {
		$locale = wpbc_get_maybe_reloaded_booking_locale();
	}


    if ( ! empty( $locale ) ) {

        $mofile_in_plugin_folder = WPBC_PLUGIN_DIR  . '/languages/' . $domain . '-' . $locale . '.mo';

		// we have long locale like en_US, get only 2 first letters, for general locale, like 'en'
		$mofile_local_short = WPBC_PLUGIN_DIR  . '/languages/' . $domain . '-' . substr( $locale, 0 , 2 ) . '.mo';

		// Load from General folder  /wp-content/languages/plugins/plugin-name-xx_XX.mo
        $mofile_in_wp_folder = WP_LANG_DIR . '/plugins/' . $domain . '-' . $locale . '.mo';                 //FixIn: 8.9.4.6  Fix '/languages/plugin/' to '/languages/plugins/          //FixIn: 8.7.7.1


	    /**
	     * Try to load from the languages' directory first  -> WP_LANG_DIR . '/plugins/' . $mofile
	     * If not possible, then from                       -> WP_PLUGIN_DIR . '/' . trim( $plugin_rel_path, '/' )
	     *
	     */
	    $is_load_from_wp_repository = get_bk_option( 'booking_translation_load_from' );
	    if ( ( empty( $is_load_from_wp_repository ) ) || ( 'wp.org' == $is_load_from_wp_repository ) ) {

		    /**
		     * This function  'load_plugin_textdomain'      load firstly from the   '/wp-content/languages/plugins/'    directory
		     *
		     *           load_plugin_textdomain( $domain, false, WPBC_PLUGIN_DIRNAME . '/languages' )
		     *
		     *      -->  ../wp-content/languages/plugins/booking-fr_FR.mo   !!!!
		     *
		     * In case if need to  load directly,  then  use:
		     *
		     *                          load_textdomain( $domain, WPBC_PLUGIN_DIR  . '/languages/' . $domain . '-' . $locale . '.mo' );
		     */

		    $plugin_rel_path = WPBC_PLUGIN_DIRNAME . '/languages';
			$is_mo_loaded = load_plugin_textdomain( $domain, false, $plugin_rel_path );
	    }


	    if ( ! $is_mo_loaded ) {

		    if ( file_exists( $mofile_in_plugin_folder ) ) {

			    /**
			     *  Direct  load  from    ../wp-content/plugins/booking/languages/booking-fr_FR.mo
			     */

			    $is_mo_loaded = load_textdomain( $domain, $mofile_in_plugin_folder );                                   //FixIn: 8.9.4.5

		    } elseif ( ( ! empty( $mofile_local_short ) ) && ( file_exists( $mofile_local_short ) ) ) {                 //FixIn: 8.1.3.13

			    /**
			     *  Direct  load of  short       " booking-en.mo "   MO file
			     */

			    $is_mo_loaded = load_textdomain( $domain, $mofile_local_short );

		    } elseif ( file_exists( $mofile_in_wp_folder ) ) {                                                          //FixIn: 8.9.4.6

			    /**
			     *  Here possible to  use
			     *                                                                  return load_plugin_textdomain( $domain, false, $plugin_rel_path );
			     *
			     *  which firstly try to load from the language directory:          ../wp-content/languages/plugins/booking-fr_FR.mo   !!!!
			     *
			     *  like this:                                                      load_textdomain( $domain, WP_LANG_DIR . '/plugins/' . $mofile )
			     *  and only  after this try uses this:                             load_textdomain( $domain, WP_PLUGIN_DIR . '/' . trim( $plugin_rel_path, '/' ) . '/' . $mofile );
			     *
			     *  so that's why  we are using direct loadinf:                     load_textdomain( $domain, $mofile_in_wp_folder )  ../wp-content/languages/plugin/booking-fr_FR.mo
			     */

			    $is_mo_loaded = load_textdomain( $domain, $mofile_in_wp_folder );
		    } else {                                                                                                    //FixIn: 7.2.1.21

			    $is_unloaded = unload_textdomain( $domain );
		    }
	    }
    }

    return $is_mo_loaded;
}


/**
 * Load Country List file depends on plugin 'booking' locale
 *
 * @string $locale    '' || 'en_US' || 'it_IT'
 */
function wpbc_load_country_list_file_php( $locale ){                                                                             //FixIn: 8.8.2.5

	if ( ! empty( $locale ) ) {
		$locale_lang    = strtolower( substr( $locale, 0, 2 ) );
		$locale_country = strtolower( substr( $locale, 3 ) );

		//FixIn: 8.9.4.12
		if ( ( $locale_lang !== 'en' ) && ( wpbc_is_file_exist( '/core/lang/wpdev-country-list-' . $locale . '.php' ) ) ) {
			require_once WPBC_PLUGIN_DIR . '/core/lang/wpdev-country-list-' . $locale . '.php';
		} else {
			require_once WPBC_PLUGIN_DIR . '/core/lang/wpdev-country-list.php';
		}
	} else {
		require_once WPBC_PLUGIN_DIR . '/core/lang/wpdev-country-list.php';
	}

	do_action( 'wpbc_country_list_loaded' );                                                                            //FixIn: 8.9.4.9
}


/**
 * Get maybe reloaded 'booking' locale ( WPBC_LOCALE_RELOAD )   and if not defined WPBC_LOCALE_RELOAD define it.
 *
 * @return string
 */
function wpbc_get_maybe_reloaded_booking_locale() {

	if ( ( is_admin() && ( defined( 'DOING_AJAX' ) ) && ( DOING_AJAX ) ) ) {
		$is_ajax = true;
	} else {
		$is_ajax = false;
	}


	/**
	 * Check active locale in some other translation plugins,  like Polylang  (NOT  in Ajax),  etc...
	 */
	if ( ! $is_ajax ) {                                          //FixIn: 8.9.4.7

		// Exception for Polylang plugin. It will force loading locale of Polylang plugin.
		if ( function_exists( 'pll_current_language' ) ) {                                                              //FixIn: 8.1.2.5
			if ( defined( 'POLYLANG_VERSION' ) ) {                                                                      //FixIn: 8.8.3.16
				if (
					( version_compare( POLYLANG_VERSION, '2.6.5', '<' ) )                                               //FixIn: 8.7.1.3
					|| ( version_compare( POLYLANG_VERSION, '2.7.1', '>' ) )                                            //FixIn: 8.7.7.11
				) {
					$locale = pll_current_language( 'locale' );
					if ( ! empty( $locale ) ) {                                                                         //FixIn: 8.7.7.11
						return $locale;
					}
				}
			}
		}
	}

	$wpbc_ajx_locale = false;
	if ( isset( $_REQUEST['wpdev_active_locale'] ) ) {
		$wpbc_ajx_locale = esc_js( $_REQUEST['wpdev_active_locale'] );
	}
	if ( isset( $_REQUEST['wpbc_ajx_locale'] ) ) {
		$wpbc_ajx_locale = esc_js( $_REQUEST['wpbc_ajx_locale'] );
	}

	// Reload locale ONLY in AJAX, and if `isset   $_REQUEST['wpdev_active_locale']
	if (
			   ( ! defined( 'WPBC_LOCALE_RELOAD' ) )
			&& ( ! empty( $wpbc_ajx_locale ) )
	){
			if (
				 	( $is_ajax )
				||  (
							( isset( $_REQUEST['wpbc_ajx_locale_reload'] ) )
						&&  ( 'force' == $_REQUEST['wpbc_ajx_locale_reload'] )
					)
			) {                                                                  // Reload locale according request parameter
				define( 'WPBC_LOCALE_RELOAD', $wpbc_ajx_locale  );
			}
	}



	// If not defined than get current WordPress locale and define
	if ( ! defined( 'WPBC_LOCALE_RELOAD' ) ) {                                                                            //FixIn: 7.2.1.21

		$locale = wpbc_get_current_wordpress_locale();      // 'en_US'  or 'it_IT',  etc...

		define( 'WPBC_LOCALE_RELOAD', $locale );
	}

	return WPBC_LOCALE_RELOAD;
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///   Support
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Get current WordPress locale,        it is not relative to  the Booking Calendar
 *
 * @return string           'en_US'
 */
function wpbc_get_current_wordpress_locale(){

	if ( function_exists( 'determine_locale' ) ) {                      //FixIn: 8.7.5.1            //FixIn: 8.7.3.15
		$current_locale = determine_locale();
	} else {
		if ( function_exists( 'get_user_locale' ) ) {
			$current_locale = is_admin() ? get_user_locale() : get_locale();
		} else {
			$current_locale = get_locale();
		}
	}

	return $current_locale;     //  'en_US'
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// Filters for  Locale of Booking Calendar
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Check locale of plugin, and if previously were defined  WPBC_LOCALE_RELOAD, then return our  WPBC_LOCALE_RELOAD locale
 *
 * @param        $locale             'en_US'
 * @param string $plugin_domain      'booking' - it's Booking Calendar plugin  locale
 *
 * @return mixed|string              'it_IT'
 */
function wpbc_filter_recheck_plugin_locale( $locale, $plugin_domain = '' ) {       //FixIn: 8.4.4.2

	if (
			   ( 'booking' == $plugin_domain )
			&& ( defined( 'WPBC_LOCALE_RELOAD' ) )
	) {
		return WPBC_LOCALE_RELOAD;
	}

	return $locale;
}
add_filter( 'plugin_locale', 'wpbc_filter_recheck_plugin_locale', 100, 2 );            // When load_plugin_textdomain() is run, its get default locale and not that, we reupdate - WPBC_LOCALE_RELOAD



/**
 * Overload loading of plugin translation files from    "wp-content/languages/plugins" -> "wp-content/plugins/plugin_name/languages"
 *
 * W:\home\beta\www/wp-content/languages/plugins/booking-it_IT.mo   ->   W:\home\beta\www\wp-content\plugins\booking/languages/booking-it_IT.mo
 *
 * @param string $mofile
 * @param type $domain
 * @return string
 */
function wpbc_filter_load_custom_plugin_translation_file( $mofile, $domain ) {

    if ( $domain == 'booking' ) {
        $mofile =  WPBC_PLUGIN_DIR . '/languages/' . basename( $mofile );
    }
    return $mofile;
}
//add_filter( 'load_textdomain_mofile', 'wpbc_filter_load_custom_plugin_translation_file' , 10, 2 );



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///   Support functions for         [lang=xx_XX] shortcode
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Check plugin text for active language section -- [lang=xx_XX] shortcode
 *
 * @param string $content_orig
 * @return string
 * Usage:
 * $text = apply_bk_filter('wpdev_check_for_active_language',  $text );
 */
function wpdev_check_for_active_language( $content_orig ) {

    $content = $content_orig;

    $languages = array();
    $content_ex = explode('[lang',$content);

	foreach ( $content_ex as $value ) {

		if ( '=' == substr( $value, 0, 1 ) ) {

			$pos_s             = strpos( $value, '=' );
			$pos_f             = strpos( $value, ']' );
			$key               = trim( substr( $value, ( $pos_s + 1 ), ( $pos_f - $pos_s - 1 ) ) );
			$value_l           = trim( substr( $value, $pos_f + 1 ) );
			$languages[ $key ] = $value_l;

		} else {
			$languages['default'] = $value;
		}
	}

    $locale = wpbc_get_maybe_reloaded_booking_locale(); 						// $locale = 'fr_FR';

	if ( isset( $languages[ $locale ] ) ) {
		$return_text = $languages[ $locale ];
	} else {
		$return_text = $languages['default'];
	}

	$return_text = wpbc_bk_check_qtranslate( $return_text, $locale );
	$return_text = wpbc_check_wpml_tags( $return_text, $locale );               //FixIn: 5.4.5.8

    return $return_text;
}


/**
	 * Get help rows about configuration in_several languages with [lang=xx_XX] shortcode
 *
 * @return array - each  item of array  is text row for showing.
 */
function wpbc_get_help_rows_about_config_in_several_languges() {

    $field_options = array();
    $field_options[] = '<strong>' . __('Configuration in several languages' ,'booking') . '</strong>';
    $field_options[] = sprintf(__('%s - start new translation section, where %s - locale of translation' ,'booking'),'<code>[lang=LOCALE]</code>','<code>LOCALE</code>');
    $field_options[] = sprintf(__('Example #1: %s - start French translation section' ,'booking'),'<code>[lang=fr_FR]</code>');
    $field_options[] = sprintf(__('Example #2: "%s" - English and French translation of some message' ,'booking'),'<code>Thank you for your booking.[lang=fr_FR]Je vous remercie de votre reservation.</code>');

    return $field_options;
}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///   Support functions for     Other Translation Plugins       like WPML, qTranslate,  etc...
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Support function for WPML plugin
 * Register and Translate everything in [wpml]Some Text to translate[/wpml] tags.
 *
 * @param string $text
 * @param string $locale
 *
 * @return string
 */
function wpbc_check_wpml_tags( $text, $locale='' ) {                            //FixIn: 5.4.5.8

    if ( $locale == '' ) {
        $locale = wpbc_get_maybe_reloaded_booking_locale();
    }
    if ( strlen( $locale ) > 2 ) {
        $locale = substr($locale, 0, 2 );
    }

    $is_tranlsation_exist_s = strpos( $text, '[wpml]' );
    $is_tranlsation_exist_f = strpos( $text, '[/wpml]' );

    if ( ( $is_tranlsation_exist_s !== false )  &&  ( $is_tranlsation_exist_f !== false ) )  {

        $shortcode = 'wpml';

        // Find anything between [wpml] and [/wpml] shortcodes. Magic here: [\s\S]*? - fit to any text
        preg_match_all( '/\[' . $shortcode . '\]([\s\S]*?)\[\/' . $shortcode . '\]/i', $text, $wpml_translations, PREG_SET_ORDER );
//debuge( $wpml_translations );

        foreach ( $wpml_translations as $translation ) {
            $text_to_replace      = $translation[0];
            $translation_to_check = $translation[1];

            if ( function_exists ( 'icl_register_string' ) ){

                if ( false ) {   // Depricated functions

                    // Help: https://wpml.org/documentation/support/translation-for-texts-by-other-plugins-and-themes/
                    icl_register_string('Booking Calendar', 'wpbc-' . tag_escape( $translation_to_check ) , $translation_to_check );

                    //TODO: Need to  execurte this after deactivation  of plugin  or after updating of some option...
                    //icl_unregister_string ( 'Booking Calendar', 'wpbc-' . tag_escape( $translation_to_check ) );

                    if ( function_exists ( 'icl_translate' ) ){
                        $translation_to_check = icl_translate ( 'Booking Calendar', 'wpbc-' . tag_escape( $translation_to_check ) , $translation_to_check  );
                    }

                } else { // WPML Version: 3.2

                    // Help info:  do_action( 'wpml_register_single_string', string $context, string $name, string $value )
                    // https://wpml.org/wpml-hook/wpml_register_string_for_translation/
                    do_action( 'wpml_register_single_string', 'Booking Calendar', 'wpbc-' . tag_escape( $translation_to_check ) , $translation_to_check );


                    // Help info:  apply_filters( 'wpml_translate_single_string', string $original_value, string $context, string $name, string $$language_code )
                    // https://wpml.org/wpml-hook/wpml_translate_single_string/
                    //$translation_to_check = apply_filters( 'wpml_translate_single_string', $translation_to_check, 'Booking Calendar',  'wpbc-' . tag_escape( $translation_to_check ) );
                    $language_code = $locale;
                    $translation_to_check = apply_filters( 'wpml_translate_single_string', $translation_to_check, 'Booking Calendar',  'wpbc-' . tag_escape( $translation_to_check ), $language_code );

                }
            }
            $text = str_replace( $text_to_replace, $translation_to_check, $text );
        }
    }

    return $text;
}


/**
 * Support function for qTranslate plugin
 *
 * @param        $text
 * @param string $locale
 *
 * @return false|mixed|string
 */
function wpbc_bk_check_qtranslate( $text, $locale='' ){

    if ($locale == '') {
        $locale = wpbc_get_maybe_reloaded_booking_locale();
    }
    if (strlen($locale)>2) {
        $locale = substr($locale, 0 ,2);
    }

    $is_tranlsation_exist = strpos($text, '<!--:'.$locale.'-->');

    if ($is_tranlsation_exist !== false) {
        $tranlsation_end = strpos($text, '<!--:-->', $is_tranlsation_exist);

        $text = substr($text, $is_tranlsation_exist , ($tranlsation_end - $is_tranlsation_exist ) );
    }

    return $text;
}



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Translation Settings
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Show translation buttons at Booking > Settings General page
 */
function wpbc_translation_buttons_settings_section(){

	if ( ( ! wpbc_is_this_demo() ) && ( current_user_can( 'activate_plugins' ) ) ){

		?><div class="wpbc_booking_system_info_buttons_align"><?php

			echo
				'<a class="button button tooltip_top" 
					data-original-title="'
					. esc_attr( sprintf( __( 'Download translation PO files from %s and update the current translation version.', 'booking' ), 'wp.org / wpbookingcalendar.com' ) )
				. '" href="'
				. wpbc_get_settings_url()
				. '&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .'&update_translations=1#wpbc_general_settings_system_info_metabox">'
				. __( 'Update Translations' )
				. '</a>';

			echo
				'<a class="button button tooltip_top" 
					data-original-title="'
					. esc_attr(  __( 'Show exist translations status', 'booking' )  )
				. '" 
					href="'
				. wpbc_get_settings_url()
				. '&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .'&show_translation_status=1#wpbc_general_settings_system_info_metabox">'
				. __( 'Show translations status', 'booking' )
				. '</a>';


			if ( $_SERVER['HTTP_HOST'] === 'beta' ) {

				?><div style="width:100%;height:2em;border-bottom:1px dashed #777;margin-bottom:1em;"></div><?php

				// Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&pot=1#wpbc_general_settings_system_info_metabox
				echo
					'<a class="button button-secondary tooltip_top" 
						data-original-title="Delete wpbc_all_translations(*).php files,  before Updating POT file (manually)"  					
						style="background:#fff9e6;" href="'
					. wpbc_get_settings_url()
					. '&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .'&pot=erase__wpbc_all_translations#wpbc_general_settings_system_info_metabox">'
					. '1. Erase Translation PHP files'
					. '</a>';
				echo
					'<a class="button button-secondary tooltip_top" 
						data-original-title="Create or update wpbc_all_translations(*).php files, from actual POT file" 
						style="background:#fff9e6;" 
						href="'
					. wpbc_get_settings_url()
					. '&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .'&pot=1#wpbc_general_settings_system_info_metabox">'
					. '2. Generate Translation PHP from POT file'
					. '</a>';

				echo
					'<a class="button button-secondary tooltip_top" 
						data-original-title="Just show WP.ORG translation status from ' . WP_LANG_DIR . '/plugins/' . ' "
						style="background:#fff9e6;" 
						href="'
					. wpbc_get_settings_url()
					. '&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .'&show_translation_status=2#wpbc_general_settings_system_info_metabox">'
					. 'Translation status WP.ORG'
					. '</a>';


				echo
					'<a class="button button-secondary tooltip_top" 
						data-original-title="Just show LOCAL translation status from ' . WPBC_PLUGIN_DIR . '/languages/' . ' " 
						style="background:#fff9e6;" 
						href="'
					. wpbc_get_settings_url()
					. '&system_info=show&_wpnonce='. wp_create_nonce( 'wpbc_settings_url_nonce' ) .'&show_translation_status=3#wpbc_general_settings_system_info_metabox">'
					. 'Translation status WPBC'
					. '</a>';
			}

		?></div><?php

	}
}




//======================================================================================================================
//	Update Translations -- DOWNLOAD from  WP.ORG  and  WPBC
//======================================================================================================================




/**
 * Update transaltions of plugin  from  WP repository
 */
function wpbc_update_translations__from_wp(){

	echo  '<h2>' . __('Start updating translation files', 'booking') . '</h2>';

	if ( function_exists( 'set_time_limit' ) ) { set_time_limit( 900 ); }
	@ini_set('memory_limit','256M');
	@ini_set('max_execution_time', 300);

	$wpbc_download_result = false;
	if ( 'translations_updated_from_wpbc' !== get_bk_option( 'booking_translation_update_status' ) ) {

		$wpbc_download_result = wpbc_translation_download_from_wpbc();

	}
	if ( ( ! is_wp_error( $wpbc_download_result ) ) && ( false !== $wpbc_download_result ) ) {
		update_bk_option( 'booking_translation_update_status', 'translations_updated_from_wpbc' );
	}


	$wp_org_translation_packages_arr = wpbc_get_translation_packages_arr_from_wp(); 		/**
																							 * ..., array ( [language] => sk_SK
																											[version] => 8.9.3
																											[updated] => 2020-03-11 17:10:35
																											[english_name] => Slovak
																											[native_name] => Slovenčina
																											[package] => https://downloads.wordpress.org/translation/plugin/booking/8.9.3/sk_SK.zip
																											[iso] => Array ( [1] => sk,  [2] => slk )
																									),
																							 * ...
																							 */

	$download_result_arr = wpbc_transaltion_download_from_wp_org( $wp_org_translation_packages_arr );

	echo '<h2>' . __('End updating translation files', 'booking') . '</h2>';
	echo '<a class="button button-secondary" style="margin:10px 0;" href="' . wpbc_get_settings_url() . '" >' . __( 'Go to Settings', 'booking' ) . '</a>';

	update_bk_option( 'booking_translation_update_status' , 'translations_updated_from_wpbc_and_wp');
}


		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		///  Support functions for Update Translations
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		/**
		 * Update translation option,  just  after  activation  of plugin.
		 * It must be 0
		 */
		function wpbc_update_translation_status_after_plugin_activation(){
			update_bk_option( 'booking_translation_update_status', '0' );
		}
		add_bk_action( 'wpbc_other_versions_activation', 'wpbc_update_translation_status_after_plugin_activation' );


		/**
		 *  Download translations from wordpress.org,  unpack it to  the ../WP_LANG_DIR/plugins  folder
		 *
		 * @param $wp_org_translation_packages_arr    array of translations data from  wp.org
		 *                                            - required one option - $translation_package['package']
		 *                                            - it's URL to zip  archive of specific translation
		 *
		 * @return array   or  results
		 */
		function wpbc_transaltion_download_from_wp_org( $wp_org_translation_packages_arr ){

			$my_upgrader = wpbc_get_translation_upgrader_obj();

			$result = array();

			foreach ( $wp_org_translation_packages_arr as $translation_package ) {

				$result[] = $my_upgrader->run(  array(
					'package'                     => $translation_package['package'], 	// Please always pass this.
					'destination'                 => WP_LANG_DIR . '/plugins/', 		// WPBC_PLUGIN_DIR  . '/lang/wp_org/', // ...and this.
					'clear_destination'           => false,
					'abort_if_destination_exists' => false, 		// Abort if the destination directory exists. Pass clear_destination as false please.
					'clear_working'               => true,
					'is_multi'                    => true,
					'hook_extra'                  => array(), 	// Pass any extra $hook_extra args here, this will be passed to any hooked filters.
				) );
			}

			return $result;
		}


		/**
		 * Download translations from wpbookingcalendar.com,  unpack it to  the ../WPBC_PLUGIN_DIR/languages/'  folder
		 *
		 * @return array|bool|string|WP_Error  - result
		 */
		function wpbc_translation_download_from_wpbc(){

			$my_upgrader = wpbc_get_translation_upgrader_obj();

			$result = $my_upgrader->run(  array(
				'package'                     => 'https://wpbookingcalendar.com/download/languages/languages.zip', // Please always pass this.
				'destination'                 => WPBC_PLUGIN_DIR  . '/languages/', 		// WPBC_PLUGIN_DIR  . '/lang/', // ...and this.
				'clear_destination'           => false,
				'abort_if_destination_exists' => false, 		// Abort if the destination directory exists. Pass clear_destination as false please.
				'clear_working'               => true,
				'is_multi'                    => false,
				'hook_extra'                  => array(), 	// Pass any extra $hook_extra args here, this will be passed to any hooked filters.
			) );

			return $result;
		}


		/**
		 * Get translation Upgrader object
		 *
		 * @return WP_Upgrader  obj
		 */
		function wpbc_get_translation_upgrader_obj(){

		    require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

			require_once WPBC_PLUGIN_DIR . '/core/class/wpbc-class-upgrader-translation-skin.php';
			$skin = new WPBC_Upgrader_Translation_Skin(
				array( 'skip_header_footer' => true )
			);

			$my_upgrader = new WP_Upgrader( $skin );

			$my_upgrader->init();	// it's required for defining skin messages

			return $my_upgrader;
		}


		/**
		 * Get array of translation packages from  WordPress.org
		 *
		 * @return array     					Array ( [0] => Array (
																		[language] => bg_BG
																		[version] => 8.9.3
																		[updated] => 2018-03-13 19:55:57
																		[english_name] => Bulgarian
																		[native_name] => Български
																		[package] => https://downloads.wordpress.org/translation/plugin/booking/8.9.3/bg_BG.zip
																		[iso] => Array ( [1] => bg,  [2] => bul )
																	)
														[1]  ....
		 */
		function wpbc_get_translation_packages_arr_from_wp(){

			$translations_arr = array();

			if ( ! is_admin() ) {
				return $translations_arr;
			}

			require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );

			$api = translations_api( 'plugins', array(
														'slug'    => 'booking',
														'version' => WP_BK_VERSION_NUM, //'8.9.3',
			) );

			if ( ( is_wp_error( $api ) ) || ( empty( $api['translations'] ) ) ) {
				return $translations_arr;
			} else {
				$translations_arr = $api['translations'];
			}

			return $translations_arr;
		}



//======================================================================================================================
//	Get STATUS of translations in this website
//======================================================================================================================


function wpbc_show_translation_status_compare_wpbc_wp( ) {

	if ( function_exists( 'set_time_limit' ) ) { set_time_limit( 900 ); }
	@ini_set('memory_limit','256M');
	@ini_set('max_execution_time', 300);

	$params = array(
						  'sort_field_name' => 'locale'
						, 'sort_order'      => SORT_ASC
						, 'sort_type'       => SORT_STRING
				);
	$params['wp_available_languages'] = wpbc_get_available_language_locales_from_wp_org_api();

	$current_locale = wpbc_get_maybe_reloaded_booking_locale();             // if NOT defined WPBC_LOCALE_RELOAD define by  current  WordPress locale

	$is_echo = false;

	$from_wp   = wpbc_show_translation_status_from_wp(   $is_echo, $params );
	$from_wpbc = wpbc_show_translation_status_from_wpbc( $is_echo, $params );

	$total_translations = 0;

	$compared_translations = array();
	// WPBC
	foreach ( $from_wpbc as $po_translation ) {
		$compared_translations[ $po_translation['locale'] ] = array(
																	  'filename'     => $po_translation['filename']
																	, 'locale'       => $po_translation['locale']
																	, 'english_name' => $po_translation['english_name']
																	, 'total_wpbc'        => $po_translation['total']
																	, 'error_wpbc'        => $po_translation['error']
																	, 'percent_wpbc'      => $po_translation['percent']
																	, 'done_wpbc'         => $po_translation['done']
																	, 'untranslated_wpbc' => $po_translation['untranslated']
																	, 'fuzzy_wpbc'        => $po_translation['fuzzy']

																	, 'total_wp'        => 0
																	, 'error_wp'        => 0
																	, 'percent_wp'      => 0
																	, 'done_wp'         => 0
																	, 'untranslated_wp' => 0
																	, 'fuzzy_wp'        => 0
																);
		if ( $po_translation['total'] > $total_translations ) {
			$total_translations = $po_translation['total'];
		}
	}


	// WP_LANG_DIR
	foreach ( $from_wp as $po_translation ) {

		if ( isset( $compared_translations[ $po_translation['locale'] ] ) ) {
			$default = $compared_translations[ $po_translation['locale'] ];
		} else {
			$default =  array(
								  'total_wpbc'        => 0
								, 'error_wpbc'        => 0
								, 'percent_wpbc'      => 0
								, 'done_wpbc'         => 0
								, 'untranslated_wpbc' => 0
								, 'fuzzy_wpbc'        => 0
							);
		}

		$compared_translations[ $po_translation['locale'] ] = wp_parse_args(
																				array(
																					  'filename'     => $po_translation['filename']
																					, 'locale'       => $po_translation['locale']
																					, 'english_name' => $po_translation['english_name']
																					, 'total_wp'        => $po_translation['total']
																					, 'error_wp'        => $po_translation['error']
																					, 'percent_wp'      => $po_translation['percent']
																					, 'done_wp'         => $po_translation['done']
																					, 'untranslated_wp' => $po_translation['untranslated']
																					, 'fuzzy_wp'        => $po_translation['fuzzy']
																				),
																				$default
															);
		if ( $po_translation['total'] > $total_translations ) {
			$total_translations = $po_translation['total'];
		}
	}

	$new_compared_translations = array();
	$current_active_translation = false;
	foreach ( $compared_translations as $locale => $compared_translation ) {

		$compared_translation['total'] = $total_translations;//( ( $compared_translation['total_wp'] > $compared_translation['total_wpbc'] ) ? $compared_translation['total_wp'] : $compared_translation['total_wpbc'] );

		$compared_translation['done'] = ( ( $compared_translation['done_wp'] > $compared_translation['done_wpbc'] ) ? $compared_translation['done_wp'] : $compared_translation['done_wpbc'] );

		$compared_translation['percent'] = round( ( $compared_translation['done'] * 100 ) / $compared_translation['total'], 2 );


		$new_compared_translations[] = $compared_translation;
		if ( $current_locale == $compared_translation['locale'] ) {
			$current_active_translation = array();
			$current_active_translation[] = $compared_translation;
		}
	}


	//////////////////////////////////////////////////////////////////////////////////
	// Sort translation  array
	//////////////////////////////////////////////////////////////////////////////////
	$volume = array_column( $new_compared_translations, 'done' );            // default  $params['sort_field_name'] = 'percent'
	array_multisort( $volume, SORT_DESC, SORT_NUMERIC, $new_compared_translations );


//debuge( $new_compared_translations );

		if ( ! empty( $current_active_translation ) ) {
			$new_compared_translations = array_merge( $current_active_translation, $new_compared_translations );
		}
		$translation_message = '';
		$is_already_shown_current_lang = false;
		foreach ( $new_compared_translations as $translation_status ) {

			if ( ( ! empty( $current_active_translation ) ) && ( $current_locale == $translation_status['locale'] ) && ($is_already_shown_current_lang)) {
				continue;
			}

			$translation_message .= ( empty( $translation_status['english_name'] ) ? $translation_status['locale'] : $translation_status['english_name'] )
									. " [{$translation_status['locale']}] "
									. " better to use ". ( ( $translation_status['done_wpbc'] > $translation_status['done_wp'] ) ? "local" : "wordpress.org" ) . " translation"
									. '<br/>'
			                        . " Total translations: <strong>{$translation_status['percent']}%</strong> [ <strong>{$translation_status['done']}</strong> / {$translation_status['total']} ]"
									. '<br/>'
									. "Local translation. " //. esc_html( WPBC_PLUGIN_DIR  . '/languages/' ) . " folder"
			                        . "  Translated <strong>{$translation_status['done_wpbc']}</strong>"
			                        . ", fuzzy <strong>{$translation_status['fuzzy_wpbc']}</strong>"
			                        . ", not translated <strong>{$translation_status['untranslated_wpbc']}</strong>"
									. '<br/>'
									. "wordpress.org translation. " //. esc_html( WP_LANG_DIR . '/plugins/' ) . " folder"
			                        . "  Translated <strong>{$translation_status['done_wp']}</strong>"
			                        . ", fuzzy <strong>{$translation_status['fuzzy_wp']}</strong>"
			                        . ", not translated <strong>{$translation_status['untranslated_wp']}</strong>"
									. '<hr/>';

			if ( ( ! empty( $current_active_translation ) ) && ( $current_locale == $translation_status['locale'] ) ) {
				$is_already_shown_current_lang = true;
			}
		}

		//////////////////////////////////////////////////////////////////////////////////

		wpbc_show_message_in_settings( $translation_message, 'info' );

		echo '<a class="button button-secondary" style="margin:10px 0;" href="' . wpbc_get_settings_url() . '" >' . __( 'Go to Settings', 'booking' ) . '</a>';
}

/**
 * Get  or  Show translation statuses from "WP_LANG_DIR" folder  at Booking > Settings General page
 *
 * Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&show_translation_status=1#wpbc_general_settings_system_info_metabox
 *
 * @param bool  $is_echo
 * @param array $params					array(
												  'wp_available_languages' => array()			- result of wpbc_get_available_language_locales_from_wp_org_api()
												, 'sort_field_name' => 'percent'
												, 'sort_order'      => SORT_DESC
												, 'sort_type'       => SORT_NUMERIC
										);
 *
 * @return array						array (  array ( filename = "booking-ro_RO.po",    total = {int} 1906
														  error = false
														  percent = {float} 98.11
														  done = {int} 1870
														  untranslated = {int} 16
														  fuzzy = {int} 20
														  english_name = "ro_RO"
														  locale = "ro_RO"
												)
											 , ...
										)
 */
function wpbc_show_translation_status_from_wp( $is_echo = true, $params = array() ){

	$defaults = array(
						  'wp_available_languages' => array()
						, 'sort_field_name' => 'done'
						, 'sort_order'      => SORT_DESC
						, 'sort_type'       => SORT_NUMERIC
				);
	$params   = wp_parse_args( $params, $defaults );


	if ( empty( $params['wp_available_languages'] ) ) {
		//  Get available language locales from the WordPress.org API.
		$params['wp_available_languages'] = wpbc_get_available_language_locales_from_wp_org_api();
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Scan 	WP_LANG_DIR 	dir for PO files.
	if (1) {
		$plugin_translations_in_lang_dir = wp_get_installed_translations( 'plugins' );

		$plugin_slug = 'booking';

		if ( ! empty( $plugin_translations_in_lang_dir[ $plugin_slug ] ) ) {
			$plugin_translations_in_lang_dir = $plugin_translations_in_lang_dir[ $plugin_slug ];
		} else {
			$plugin_translations_in_lang_dir = array();
		}

		$po_files_full_path_arr = array();
		foreach ( (array) $plugin_translations_in_lang_dir as $locale => $po_arr ) {

			$po_files_full_path_arr[] = WP_LANG_DIR . '/plugins/' . $plugin_slug . '-' . $locale . '.po';
			/**
			 * array ( [0] => W:\w3\beta\www/wp-content/languages/plugins/booking-bg_BG.po
			 * [1] => W:\w3\beta\www/wp-content/languages/plugins/booking-ca.po
			 * ...
			 * )
			 */
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	// Get array of translation statuses,  like total, fuzzy, not translated...
	$translation_status_arr = wpbc_get_po_transaltion_statuses_of_files_arr(	wp_parse_args(
																								array(
																									  'po_files_full_path_arr' => $po_files_full_path_arr
																								)
																								, $params
																				)
																			);
	/**
	 array (  array ( filename = "booking-ro_RO.po",    total = {int} 1906
						  error = false
						  percent = {float} 98.11
						  done = {int} 1870
						  untranslated = {int} 16
						  fuzzy = {int} 20
						  english_name = "ro_RO"
						  locale = "ro_RO"
				)
			 , ...
		)
	 */

	if ( $is_echo ) {

		$translation_message = '';
		foreach ( $translation_status_arr as $translation_status ) {

			$translation_message .= ( empty( $translation_status['english_name'] ) ? $translation_status['locale'] : $translation_status['english_name'] )
			                        . "  <strong>{$translation_status['percent']}%</strong> [ {$translation_status['done']} / {$translation_status['total']} ]"
			                        . ", fuzzy <strong>{$translation_status['fuzzy']}</strong>"
			                        . ", not translated <strong>{$translation_status['untranslated']}</strong>" . '<br/>';

		}

		wpbc_show_message_in_settings( $translation_message, 'info' );

		echo '<a class="button button-secondary" style="margin:10px 0;" href="' . wpbc_get_settings_url() . '" >' . __( 'Go to Settings', 'booking' ) . '</a>';
	}

	return $translation_status_arr;
}


/**
 * Get  or  Show translation statuses  from  "{Booking Calendar Folder}"   at Booking > Settings General page
 * Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&show_translation_status=1#wpbc_general_settings_system_info_metabox
 *
 * @param bool  $is_echo
 * @param array $params					array(
												  'wp_available_languages' => array()			- result of wpbc_get_available_language_locales_from_wp_org_api()
												, 'sort_field_name' => 'percent'
												, 'sort_order'      => SORT_DESC
												, 'sort_type'       => SORT_NUMERIC
										);
 *
 * @return array						array (  array ( filename = "booking-ro_RO.po",    total = {int} 1906
														  error = false
														  percent = {float} 98.11
														  done = {int} 1870
														  untranslated = {int} 16
														  fuzzy = {int} 20
														  english_name = "ro_RO"
														  locale = "ro_RO"
												)
											 , ...
										)
 */
function wpbc_show_translation_status_from_wpbc( $is_echo = true, $params = array() ){

	$defaults = array(
						  'wp_available_languages' => array()
						, 'sort_field_name' => 'percent'
						, 'sort_order'      => SORT_DESC
						, 'sort_type'       => SORT_NUMERIC
				);
	$params   = wp_parse_args( $params, $defaults );


	if ( empty( $params['wp_available_languages'] ) ) {
		//  Get available language locales from the WordPress.org API.
		$params['wp_available_languages'] = wpbc_get_available_language_locales_from_wp_org_api();
	}


	// Scan dir for PO files.
	if (1) {
		$po_files_array         = wpbc_scan_dir_for_po_files( WPBC_PLUGIN_DIR . '/languages' );                // $po_files_array = array( "booking-ar.po", "booking-bel.po", "booking-bg_BG.po", ... )
		$po_files_full_path_arr = array();
		foreach ( $po_files_array as $po_file_name ) {
			$po_files_full_path_arr[] = WPBC_PLUGIN_DIR . '/languages/' . $po_file_name;                		// W:\w3\beta\www/wp-content/plugins/booking/languages/booking-ar.po
		}
	}

	// Get array of translation statuses,  like total, fuzzy, not translated...
	$translation_status_arr = wpbc_get_po_transaltion_statuses_of_files_arr(	wp_parse_args(
																								array(
																									  'po_files_full_path_arr' => $po_files_full_path_arr
																								)
																								, $params
																				)
																			);
	/**
	 array (  array ( filename = "booking-ro_RO.po",    total = {int} 1906
						  error = false
						  percent = {float} 98.11
						  done = {int} 1870
						  untranslated = {int} 16
						  fuzzy = {int} 20
						  english_name = "ro_RO"
						  locale = "ro_RO"
				)
			 , ...
		)
	 */

	if ( $is_echo ) {
		//////////////////////////////////////////////////////////////////////////////////
		$translation_message = '';
		foreach ( $translation_status_arr as $translation_status ) {

			$translation_message .= ( empty( $translation_status['english_name'] ) ? $translation_status['locale'] : $translation_status['english_name'] )
			                        . "  <strong>{$translation_status['percent']}%</strong> [ {$translation_status['done']} / {$translation_status['total']} ]"
			                        . ", fuzzy <strong>{$translation_status['fuzzy']}</strong>"
			                        . ", not translated <strong>{$translation_status['untranslated']}</strong>" . '<br/>';

		}

		//////////////////////////////////////////////////////////////////////////////////

		wpbc_show_message_in_settings( $translation_message, 'info' );

		echo '<a class="button button-secondary" style="margin:10px 0;" href="' . wpbc_get_settings_url() . '" >' . __( 'Go to Settings', 'booking' ) . '</a>';
	}

	return $translation_status_arr;
}


		/**
		 * Get Language names for all  Locales	from   WordPress.org API
		 *
		 * @return array|array[]
		 						Array(   ["nl_NL"] => array ( 	language = "nl_NL"
																 version = "5.9"
																 updated = "2022-02-10 16:24:09"
																 english_name = "Dutch"
																 native_name = "Nederlands"
																 package = "https://downloads.wordpress.org/translation/core/5.9/nl_NL.zip"
																 iso = array( "nl", "nld" )
																 strings = array(  continue = "Doorgaan" )
													), ...

		 */
		function wpbc_get_available_language_locales_from_wp_org_api(){

			// Get Language names for all  Locales
			require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
			/**
			 * Array(   ["nl_NL"] => array ( language = "nl_NL"
											 version = "5.9"
											 updated = "2022-02-10 16:24:09"
											 english_name = "Dutch"
											 native_name = "Nederlands"
											 package = "https://downloads.wordpress.org/translation/core/5.9/nl_NL.zip"
											 iso = array( "nl", "nld" )
											 strings = array(  continue = "Doorgaan" )
			 * 					), ...
			 */
			if (function_exists('wp_get_available_translations')){
				$wp_api_available_languages =  wp_get_available_translations();		//  Get available translations from the WordPress.org API.
			} else {
				$wp_api_available_languages = array();
			}

			return $wp_api_available_languages;
		}


		/**
		 * Get array of translation statuses (like total,  fuzzy,  not translated) of each PO files
		 *
		 * @param $params (array)    			array(
													  'po_files_full_path_arr' => array()
													, 'wp_available_languages' => array()					- result of this function: 		wp_get_available_translations()
													, 'sort_field_name' => 'percent'						- it can  be any  field from  the returned array,  like 'total'
													, 'sort_order'      => SORT_DESC
													, 'sort_type'       => SORT_NUMERIC
											)
		 * 'po_files_full_path_arr' => array (
												  0 = "W:\w3\beta\www\wp-content\plugins\booking/languages/booking-ar.po"
												  1 = "W:\w3\beta\www\wp-content\plugins\booking/languages/booking-bel.po"
		 *                                        ...
		 *                                 		)
		 *
		 * @return array				 array (
												 0 = array (
															  filename = "booking-ro_RO.po"
															  locale = "ro_RO"
															  total = {int} 1906
															  error = false
															  percent = {float} 98.11
															  done = {int} 1870
															  untranslated = {int} 16
															  fuzzy = {int} 20
															  english_name = "ro_RO"
													)
												 , ...
											)
		 */
		function wpbc_get_po_transaltion_statuses_of_files_arr( $params ){

			$defaults = array(
								  'po_files_full_path_arr' => array()
							  	, 'wp_available_languages' => array()
								, 'sort_field_name' => 'percent'
								, 'sort_order'      => SORT_DESC
								, 'sort_type'       => SORT_NUMERIC
						);
			$params   = wp_parse_args( $params, $defaults );


			$translation_status_arr = array();

			foreach ( $params['po_files_full_path_arr'] as $po_file_index => $pot_file_path ) {


				$translation_status = wpbc_get_po_translation_statuses($pot_file_path);
				/**
				 * array( 	 filename = "booking-ar.po", 	locale = "ar", 		total = {int} 1906, 	percent = {float} 97.74
							 done = {int} 1863
							 untranslated = {int} 17
							 fuzzy = {int} 26
							 error = false
				 *      )
				 */
				
				if ( ! empty( $params['wp_available_languages'][ $translation_status['locale'] ] ) ) {
					$translation_status['english_name'] = $params['wp_available_languages'][ $translation_status['locale'] ]['english_name'];
				} else {
					$translation_status['english_name'] = $translation_status['locale'];
				}

				$translation_status_arr[] = $translation_status;
			}

			//////////////////////////////////////////////////////////////////////////////////
			// Sort translation  array
			//////////////////////////////////////////////////////////////////////////////////
			$volume = array_column( $translation_status_arr, $params['sort_field_name'] );            // default  $params['sort_field_name'] = 'percent'
			array_multisort( $volume, $params['sort_order'], $params['sort_type'], $translation_status_arr );

			return $translation_status_arr;
		}


		/**
		 * Scan directory for PO files
		 *
		 * @param $dir_to_scan      path to directory  with  PO files
		 *
		 * @return array            array with names of PO files in this dir
		 */
		function wpbc_scan_dir_for_po_files( $dir_to_scan ){

			$found_files = array();

			$files = scandir( $dir_to_scan );
			if ( ! $files ) {
				$found_files = array();
			}

			foreach ( $files as $file ) {
				if ( '.' === $file[0] || is_dir( $dir_to_scan . "/$file" ) ) {
					continue;
				}
				if ( substr( $file, - 3 ) !== '.po' ) {
					continue;
				}
				$match_result = preg_match( '/(?:(.+)-)?([a-z]{2,3}(?:_[A-Z]{2})?(?:_[a-z0-9]+)?).po/', $file, $match );
				if ( ! $match_result ) {
					continue;
				}
				if ( ! in_array( substr( $file, 0, - 3 ) . '.mo', $files, true ) ) {
					continue;
				}

				$found_files[] = $file;
			}

			return $found_files;
		}


		/**
		 * Get translation data statuses from  PO file
		 *
		 * @param $pot_file_path       string - full path to PO translation file
		 *
		 * @return array               array  of PO statuses
		 */
		function wpbc_get_po_translation_statuses( $pot_file_path ){

			$translation_status = array(
				'filename' => basename( $pot_file_path )
			  , 'locale'   => substr( $pot_file_path, ( strrpos( $pot_file_path, '-' ) + 1 ), - 3 )
			  , 'total'    => 0
			  , 'error'    => false
			);

			if ( is_readable( $pot_file_path ) ) {

				if ( ! class_exists( 'PO' ) ) {
					require_once ABSPATH . WPINC . '/pomo/po.php';
				}

				if ( class_exists( 'PO' ) ) {

					$po      = new PO();
					$is_good = $po->import_from_file( $pot_file_path );

					if ( $is_good ) {

						$transaltion_total = count( $po->entries );
						$transaltion_no    = 0;
						$transaltion_fuzzy = 0;

						foreach ( $po->entries as $po_index => $po_item ) {

							$translation = $po_item;

							if ( 0 == count( $translation->translations ) ) {
								$transaltion_no++;
							} elseif ( true === in_array( 'fuzzy',  $translation->flags ) ) {
								$transaltion_fuzzy++;
							}
						}

						$transaltion_done    = $transaltion_total - $transaltion_no - $transaltion_fuzzy;
						$transaltion_percent = round( ( $transaltion_done * 100 ) / $transaltion_total, 2 );

						$translation_status = wp_parse_args(
												array(
															'percent'      => $transaltion_percent
														  , 'done'         => $transaltion_done
														  , 'total'        => $transaltion_total
														  , 'untranslated' => $transaltion_no
														  , 'fuzzy'        => $transaltion_fuzzy
														)
												, $translation_status );

					} else {
						$translation_status['error'] = 'Import translation from PO file failed';
					}
				} else {
					$translation_status['error'] = 'Can not access to PO class';
				}

			} else {
				$translation_status['error'] = 'File not readable';
			}
			return  $translation_status;
		}



/**
 * Load translation POT file,  and generate PHP file with all translations relative to plugin.
 *  Link: http://server.com/wp-admin/admin.php?page=wpbc-settings&system_info=show&pot=1#wpbc_general_settings_system_info_metabox
 */
function wpbc_pot_to_php() {

/*
 *         $shortcode = 'wpml';

        // Find anything between [wpml] and [/wpml] shortcodes. Magic here: [\s\S]*? - fit to any text
        preg_match_all( '/\[' . $shortcode . '\]([\s\S]*?)\[\/' . $shortcode . '\]/i', $text, $wpml_translations, PREG_SET_ORDER );
//debuge( $wpml_translations );

        foreach ( $wpml_translations as $translation ) {

 */

    $pot_file = WP_PLUGIN_DIR . '/' . trim( WPBC_PLUGIN_DIRNAME . '/languages/booking.pot' , '/' );

    if ( !is_readable( $pot_file ) ) {
        wpbc_show_message_in_settings( 'POT file not found: ' . $pot_file , 'error' );
        return false;
    } else
        wpbc_show_message_in_settings( 'POT file found: ' . $pot_file , 'info' );


    if ( ! class_exists( 'PO' ) )
        require_once ABSPATH . WPINC . '/pomo/po.php';

    if ( class_exists( 'PO' ) ) {

        $po = new PO();
        $po->import_from_file( $pot_file );

        wpbc_show_message_in_settings( 'Found <strong>' . count($po->entries)  . '</strong> translations' , 'info' );

	    	//FixIn: 8.7.3.6
        	$translation_files = array();


			// Generate content of the file
			//$all_translations = '<?php  function wpbc_all_translations() { $wpbc_all_translations = array(); ';

			$lines_number = 1;
			$all_translations = '';
			foreach ( $po->entries as $transaltion => $transaltion_obj ) {

				$transaltion = str_replace( "'", "\'", $transaltion );
				$all_translations .= ' $wpbc_all_translations[] = __(\''.  $transaltion  .'\', \'booking\'); ' . "\n";
				$lines_number++;

				// Maximum  number of lines in such  files
				if ( $lines_number >= 998 ) {
					$file_number         = count( $translation_files ) + 1;
					$translation_files[] = '<?php  function wpbc_all_translations' . $file_number . '() { $wpbc_all_translations = array(); ' . "\n" . $all_translations . " } ";
					$all_translations    = '';
					$lines_number        = 1;
				}

			}

			if ( ! empty( $all_translations ) ) {
				$file_number         = count( $translation_files ) + 1;
				$translation_files[] = '<?php  function wpbc_all_translations' . $file_number . '() { $wpbc_all_translations = array(); ' . "\n" . $all_translations . " } ";
			}


			//$all_translations .= ' } ';

			foreach ( $translation_files as $file_number => $file_content ) {

				// Path  to new PHP file with  all
				$new_php_file = WP_PLUGIN_DIR . '/' . trim( WPBC_PLUGIN_DIRNAME . '/core/lang/wpbc_all_translations' . ( ( ! empty( $file_number ) ) ? $file_number : '' ) . '.php', '/' );        //FixIn: 8.9.4.12

				$fh = fopen( $new_php_file, 'w' );
				if ( false === $fh ) {
					wpbc_show_message_in_settings( 'Can not create or edit PHP file: ' . $new_php_file, 'error' );

					return false;
				}
				$res = fwrite( $fh, $file_content );
				if ( false === $res ) {
					wpbc_show_message_in_settings( 'Some error during saving data into file ' . $new_php_file, 'error' );

					return false;
				}
				$res = fclose( $fh );

				wpbc_show_message_in_settings( 'Completed! [ ' . htmlentities( $new_php_file ) . ' ]', 'info' );
			}

			echo '<a class="button button-secondary" style="margin:10px 0;" href="' . wpbc_get_settings_url() . '" >' . __( 'Go to Settings', 'booking' ) . '</a>';

        return $res;

    } else  {
        wpbc_show_message_in_settings( 'PO class does not exist or do not loaded' , 'error' );
    }



//                $filename = $pot_file;
//		$reader = new POMO_FileReader( $filename );
////debuge($reader);
//		if ( ! $reader->is_resource() ) {
//			return false;
//		}
//
//		$file_data = $reader->read_all();
//
//                $mo = new PO();
//                $pomo_reader = new POMO_StringReader($file_data);
//                $mo->import_from_reader( $pomo_reader );
//debuge($mo)       ;
//    if ( isset( $l10n[$domain] ) )
//            $mo->merge_with( $l10n[$domain] );



}


/**
 * Delete PHP translation  files,  before updating POT file in plugin, manually.
 * @return void
 */
function wpbc_delete_translation_php_files(){

	$path_arr = array(
		WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations.php',
		WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations1.php',
		WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations2.php',
		WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations3.php',
		WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations4.php',
		WPBC_PLUGIN_DIR . '/core/lang/wpbc_all_translations5.php'
	);


	foreach ( $path_arr as $path ) {

		if ( file_exists( $path ) ) {
			if ( is_file( $path ) ) {
				if ( ! unlink( $path ) ) {
					throw new \RuntimeException( "Failed to unlink {$path}: " . var_export( error_get_last(), true ) );
				} else {
					echo '<p style="color:red;">Deleted: ' . $path . '</p>';
				}

			}
		}
	}
}


// $translation = apply_filters( "gettext_{$domain}", $translation, $text, $domain );

//FixIn: 9.1.3.2
/**
 * Check consistency of translations. For situations, when translators made mistakes with missed symbols like %s or additional items
 *
 * @param $translation
 * @param $text
 * @param $domain
 *
 * @return string
 */
function wpbc_check_translations( $translation, $text, $domain ){

	$check_symbols = array( 's', 'd', 'f', 'b', 'c', 'e', 'E', 'F', 'g', 'G', 'h', 'H', 'o', 'u', 'x', 'X' );
	foreach ( $check_symbols as $check_symbol ) {
		$text_count        = substr_count( $text, '%' . $check_symbol );
		$translation_count = substr_count( $translation, '%' . $check_symbol );

		if ( $text_count != $translation_count ) {        // Number of %s in translation != number of %s in original text -- ERROR in  translation

			/*
			$booking_translation_errors = new WP_Error( 'booking_translation_consistency', 'Translation consistency error', array(
				'text'         => $text,
				'translation'  => $translation,
				'symbol_error' => '%' . $check_symbol
			) );
			*/
			return $text;
		}

	}

	$text_count        = substr_count( $text, '%' );
	$translation_count = substr_count( $translation, '%' );
	if ( $text_count != $translation_count ) {
		return $text;
	}

	return $translation;
}
add_filter( 'gettext_booking', 			'wpbc_check_translations', 1000, 3 );              // check  Booking Calendar translation terms
add_filter( 'gettext_booking-manager', 	'wpbc_check_translations', 1000, 3 );              // check  Booking Manager  translation terms

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Deprecated
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Deprecated
 */
function wpbc_get_booking_locale() {
    return wpbc_get_maybe_reloaded_booking_locale();
}


