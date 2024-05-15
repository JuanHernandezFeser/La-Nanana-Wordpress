<?php
/**
 * @version 1.0
 * @package Booking Calendar Shortcodes Config
 * @subpackage Shortcodes Content   - 'Search Form':       [bookingsearch searchresults='https://servcer.com/search-form/' searchresultstitle='{searchresults} Result(s) Found' noresultstitle='Nothing Found']
 * @category Shortcodes
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2024-03-05
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly			//FixIn: 9.9.0.15



// =====================================================================================================================
//  Shortcode [bookingsearch searchresults='https://servcer.com/search-form/' searchresultstitle='{searchresults} Result(s) Found' noresultstitle='Nothing Found']
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [bookingsearch]
 *
 * @return void
 */
function wpbc_shortcode_config__content__bookingsearch() {

	$shortcode_name = 'bookingsearch';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		wpbc_shortcode_config__bookingsearch__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general"><?php

			//--------------------------------------------------------------------------------------------------------------
			// 'Upgrade to Pro' widget
			//--------------------------------------------------------------------------------------------------------------
			$upgrade_content_arr = wpbc_get_upgrade_widget( array(
									'id'                 => 'widget_wpbc_dismiss__booking_search',
									'dismiss_css_class'  => '.wpbc_dismiss__booking_search',
									'blured_in_versions' => array( 'free', 'ps', 'bs', 'bm' ),
									'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#search' ),
									'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
									'versions'           => 'Business Large, MultiUser versions',
									'css'                => 'transform: translate(0) translateY(120px);'
								) );
			echo $upgrade_content_arr['content'];

			//--------------------------------------------------------------------------------------------------------------
			// Real Content
			//--------------------------------------------------------------------------------------------------------------

			?><table class="form-table wpbc_dismiss__booking_search <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>"><tbody><?php

				// Select 'Search Form'  |  'Search Results'
				wpbc_shortcode_config_fields__searchform__searchresults( $shortcode_name . '_wpbc_search_form_results', $shortcode_name );

				wpbc_shortcode_config_fields__searchresults_new_page( $shortcode_name . '_wpbc_search_new_page', $shortcode_name );

				wpbc_shortcode_config_fields__searchavailability_text_header( $shortcode_name . '_wpbc_search_header', $shortcode_name );

				wpbc_shortcode_config_fields__searchavailability_text_nothing_found( $shortcode_name . '_wpbc_search_nothing_found', $shortcode_name );

				wpbc_shortcode_config_fields__searchavailability_only_for_users( $shortcode_name . '_wpbc_search_for_users', $shortcode_name );

			?></tbody></table>
		</div><?php


		?>
		<div class="wpbc_shortcode_config_content_toolbar__next_prior">
			<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config_content_toolbar__next_prior(this,'prior');" class="button">
				<span class="in-button-text">&lsaquo;</span>
			</a>
			<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config_content_toolbar__next_prior(this,'next');" class="button">
				<span class="in-button-text">&rsaquo;</span>
			</a>
		</div>
		<?php

	?></div><?php

	wpbc_clear_div();
}


	/**
	 * Top Tabs for shortcode [booking ...]
	 * @return void
	 */
	function wpbc_shortcode_config__bookingsearch__top_tabs(){

		$shortcode_name = 'booking';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							'title'         => __('Search Availability', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'menu_icon icon-1x wpbc-bi-search'
							, 'default'     => true
			) );
			//			wpbc_bs_display_tab(   array(
			//							'title'         => __('Advanced', 'booking')
			//							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
			//							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__other').show();"
			//							, 'font_icon'   => 'wpbc_icn_tune'
			//							, 'default'     => false
			//
			//			) );

		wpbc_bs_toolbar_tabs_html_container_end();
	}



// =====================================================================================================================
//  Fields
// =====================================================================================================================


	/**
	 * Shortcode Fields:  View Mode:
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__searchform__searchresults( $id , $group_key ){

		$optiopns_for_search                         = array();
		$optiopns_for_search['bookingsearch']        = array( 'title' => __( 'Search form', 'booking' ), 	'attr'  => array( 'class' => 'wpbc_bookingtimeline_single' ) );
		$optiopns_for_search['bookingsearchresults'] = array( 'title' => __( 'Search results ', 'booking' ), 'attr'  => array( 'class' => 'wpbc_bookingtimeline_single' ) );

		WPBC_Settings_API::field_radio_row_static(   $id
													, array(
															  'type'              => 'radio'
															, 'title'             => __('Select type of shortcode', 'booking')
															, 'description'       => ''//__('Select type of view format.' ,'booking')
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed0 ' . $id . '_wpbc_sc_view_mode'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => 'bookingsearch'
															, 'options'           => $optiopns_for_search
														)
						);
	}


		/**
		 * Shortcode Field:  Header in search results: '{searchresults} Result(s) Found'
		 *
		 * @param $id
		 * @param $group_key
		 *
		 * @return void
		 */
		function wpbc_shortcode_config_fields__searchavailability_text_header ($id , $group_key ){

			WPBC_Settings_API::field_text_row_static( $id
										, array(
												  'type'              => 'text'
												, 'placeholder'       =>  __( 'Example', 'booking' ). ': ' . '{searchresults} ' . __('Result(s) Found','booking')
												, 'title'             => __( 'Title of Search results', 'booking' )
												, 'description'       => sprintf( __( 'Type the title of Search results. %s - show number of search results', 'booking' ) , '<code>{searchresults}</code>' )
												, 'description_tag'   => 'span'
												, 'label'             => ''
												, 'class'             => ''
												, 'css'               => ''
												, 'group'             => $group_key
												, 'tr_class'          => $group_key . '_standard_section wpbc_search_availability_form'
												, 'only_field'        => false
												, 'attr'              => array()
												, 'value'             => '{searchresults} ' . __('Result(s) Found','booking')
											)
						);

		}


		/**
		 * Shortcode Field:  Header in search results: '{searchresults} Result(s) Found'
		 *
		 * @param $id
		 * @param $group_key
		 *
		 * @return void
		 */
		function wpbc_shortcode_config_fields__searchavailability_text_nothing_found ($id , $group_key ){

			WPBC_Settings_API::field_text_row_static( $id
										, array(
												  'type'              => 'text'
												, 'placeholder'       =>  __( 'Example', 'booking' ). ': '   . __('Nothing Found','booking')
												, 'title'             => __( 'Nothing Found Message', 'booking' )
												, 'description'       =>   __( 'Type the message, when nothing found.', 'booking' )
												, 'description_tag'   => 'span'
												, 'label'             => ''
												, 'class'             => ''
												, 'css'               => ''
												, 'group'             => $group_key
												, 'tr_class'          => $group_key . '_standard_section wpbc_search_availability_form'
												, 'only_field'        => false
												, 'attr'              => array()
												, 'value'             =>  __('Nothing Found','booking')
											)
						);

		}


		/**
		 * Shortcode Fields:  OPTION - "Define External URL  for search  results"
		 *
		 * @param $id
		 * @param $group_key
		 *
		 * @return void
		 */
		function wpbc_shortcode_config_fields__searchresults_new_page( $id , $group_key ){

				// ---------------------------------------------------------------------------------------------------------
				WPBC_Settings_API::field_checkbox_row_static( $id . '_enabled'
															, array(
																	  'type'              => 'checkbox'
																	, 'title'             => __('Search results on new page', 'booking')
																	, 'description'       => __('Check this box to show search results on other page', 'booking')
																	, 'description_tag'   => 'span'
																	, 'label'             => ''
																	, 'class'             => ''
																	, 'css'               => ''
																	, 'attr'              => array()
																	, 'group'             => $group_key
																	, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed wpbc_search_availability_form'
																	, 'only_field'        => false
																	, 'is_new_line'       => false
																	, 'value'             => false
																)
						);


					?><tr valign="top"  class="<?php echo $group_key . '_standard_section'; ?> wpbc_sub_settings_grayed wpbc_search_availability_form <?php echo $id; ?>_wpbc_sc_searchresults_new_page">
						<th scope="row" style="vertical-align: middle;">
							<label for="wpbc_booking_width" class="wpbc-form-text"><?php  _e('URL of search results:', 'booking'); ?></label>
						</th>
						<td class=""><fieldset><?php

							WPBC_Settings_API::field_text_row_static( $id . '_url'
																, array(
																		  'type'              => 'text'
																		, 'placeholder'       => get_option('siteurl') . '/search-results/'
																		, 'class'             => ''
																		, 'css'               => ''
																		, 'only_field'        => true
																		, 'attr'              => array()
																		, 'value'             => ''
																	)
												);
							?><span class="description"> <?php _e('Type the URL of search results page.' ,'booking'); ?></span></fieldset></td>
					</tr><?php

					?><tr style="height:20px;border-bottom: 0px solid #eee;"  class="<?php echo $group_key . '_standard_section'; ?> wpbc_search_availability_form <?php echo $id; ?>_wpbc_sc_searchresults_new_page"><td colspan="2"></td></tr><?php
		}


		/**
		 * Shortcode Field:  Header in search results: '{searchresults} Result(s) Found'
		 *
		 * @param $id
		 * @param $group_key
		 *
		 * @return void
		 */
		function wpbc_shortcode_config_fields__searchavailability_only_for_users ($id , $group_key ){

			if ( class_exists( 'wpdev_bk_multiuser' ) ) {

				WPBC_Settings_API::field_text_row_static( $id
										, array(
												  'type'              => 'text'
												, 'placeholder'       =>  __( 'Example', 'booking' ). ': '   . '1,3,99'
												, 'title'             => __( 'Search only for users', 'booking' )
												, 'description'       => __( 'Type IDs of the users (separated by comma ",") for searching availability  only for these users, or leave it blank for searching for all users.', 'booking' )
												, 'description_tag'   => 'p'
												, 'label'             => ''
												, 'class'             => ''
												, 'css'               => ''
												, 'group'             => $group_key
												, 'tr_class'          => $group_key . '_standard_section wpbc_search_availability_form'
												, 'only_field'        => false
												, 'attr'              => array()
												, 'value'             => ''
											)
						);
			}
		}

