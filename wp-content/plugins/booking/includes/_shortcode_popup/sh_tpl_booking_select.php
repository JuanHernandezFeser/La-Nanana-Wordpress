<?php
/**
 * @version 1.0
 * @package Booking Calendar Shortcodes Config
 * @subpackage Shortcodes Content
 * @category Shortcodes
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2024-02-17
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly			//FixIn: 9.9.0.15

// =====================================================================================================================
//  Shortcode [bookingselect ... ]
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [booking ...]
 *
 * @return void
 */
function wpbc_shortcode_config__content__bookingselect() {

	$shortcode_name = 'bookingselect';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		//--------------------------------------------------------------------------------------------------------------
		// 'Upgrade to Pro' widget
		//--------------------------------------------------------------------------------------------------------------
		$upgrade_content_arr = wpbc_get_upgrade_widget( array(
								'id'                 => 'widget_wpbc_dismiss__booking_select',
								'dismiss_css_class'  => '.wpbc_dismiss__booking_select',
								'blured_in_versions' => array( 'free' ),
								'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#booking-resources' ),
								'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
								'versions'           => 'paid versions',
								'css'                => 'transform: translate(0) translateY(190px);'
							) );
		echo $upgrade_content_arr['content'];

		//--------------------------------------------------------------------------------------------------------------
		// Real Content
		//--------------------------------------------------------------------------------------------------------------

		?><div class="wpbc_dismiss__booking_select <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>"><?php


		wpbc_shortcode_config__bookingselect__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general"><?php

				// Select 'Multiple' --  'Booking Resource'
				wpbc_shortcode_config_fields__select_multiple_resources( $shortcode_name . '_wpbc_multiple_resources', $shortcode_name );

			?>
			<table class="form-table"><tbody><?php

				// Select 'SELECTED' by  default 'Booking Resource'
				wpbc_shortcode_config_fields__select_selected_resource( $shortcode_name . '_wpbc_selected_resource', $shortcode_name );

				// Label
				wpbc_shortcode_config_fields__text_label( $shortcode_name . '_wpbc_text_label', $shortcode_name );

				// Title
				wpbc_shortcode_config_fields__text_first_option_title( $shortcode_name . '_wpbc_first_option_title', $shortcode_name );

			?></tbody></table>
		</div><?php


		// 'Options'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__options">
			<table class="form-table"><tbody><?php

				// Custom form
				wpbc_shortcode_config_fields__select_custom_form( $shortcode_name . '_wpbc_custom_form', $shortcode_name );

				if ( class_exists( 'wpdev_bk_biz_m' ) ){
					?>
					<tr><td></td><td>
						<div class="wpbc-settings-notice notice-warning">
							<span><?php printf(__( 'If you choose the %s form, the system will load either the standard form or the default custom booking form (configured on the Resources page). Alternatively, selecting a specific custom booking form here will ensure that all booking resources use only the form you have chosen.', 'booking' ),
								'<strong>\'Standard\'</strong>' ); ?></span>
						</div>
					</td></tr>
					<?php
				}

				// Number of Months
				wpbc_shortcode_config_fields__select_nummonths( $shortcode_name . '_wpbc_nummonths', $shortcode_name );

				// Calendar Size
				wpbc_shortcode_config_fields__calendar_size( $shortcode_name . '_wpbc_size', $shortcode_name );

			?></tbody></table>
		</div><?php

		// 'Conditional Days Selection' --------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__conditions"><?php

			wpbc_shortcode_config_fields__dates_conditions_toolbar();						// TOOLBAR

			?><div class="wpbc_sc_container__shortcode_subsection wpbc_sc_container__shortcode_subsection__weekdays_conditions">
				<?php
					wpbc_shortcode_config_fields__weekdays_conditions( $shortcode_name . 'wpbc_select_day_weekday', $shortcode_name );
				?>
			</div><?php

			?><div class="wpbc_sc_container__shortcode_subsection wpbc_sc_container__shortcode_subsection__seasons_conditions">
				<?php
					wpbc_shortcode_config_fields__season_conditions( $shortcode_name . 'wpbc_select_day_season', $shortcode_name );
				?>
			</div><?php

			?><div class="wpbc_sc_container__shortcode_subsection wpbc_sc_container__shortcode_subsection__startseason_conditions">
				<?php
					wpbc_shortcode_config_fields__season_start_day_conditions( $shortcode_name . 'wpbc_start_day_season', $shortcode_name )
				?>
			</div><?php

			?><div class="wpbc_sc_container__shortcode_subsection wpbc_sc_container__shortcode_subsection__dates_conditions">
				<?php
					wpbc_shortcode_config_fields__fordate_conditions( $shortcode_name . 'wpbc_select_day_fordate', $shortcode_name );
				?>
			</div><?php

		?></div><?php

		// 'AGGREGATE' -------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__aggregate"><?php

				wpbc_shortcode_config_fields__aggregate( $shortcode_name . '_wpbc_aggregate', $shortcode_name );

		?></div><?php

		// 'START MONTH' -----------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__other"><?php
			?><table class="form-table"><tbody><?php
				wpbc_shortcode_config_fields__start_month( $shortcode_name . '_wpbc_startmonth', $shortcode_name );
			?></tbody></table><?php
		?></div><?php



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
	?></div><?php

	wpbc_clear_div();
}



	/**
	 * Top Tabs for shortcode [booking ...]
	 * @return void
	 */
	function wpbc_shortcode_config__bookingselect__top_tabs(){

		$shortcode_name = 'bookingselect';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							'title'         => __('Resource Selection', 'booking')
							, 'text_css' => 'line-height: 18px;vertical-align: text-top;'
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'wpbc-bi-border-width NOwpbc-bi-card-checklist'
							, 'default'     => true
			) );
			wpbc_bs_display_tab(   array(
							'title'         => __('View Options', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__options').show();"
							, 'font_icon'   => 'wpbc_icn_adjust'
							, 'default'     => false
			) );
			wpbc_bs_display_tab(   array(
							'title'         => __('Conditional Days Selection', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__conditions').show();"
							, 'font_icon'   => 'wpbc-bi-calendar2-week'
							, 'default'     => false
							, 'css_classes' => 'wpbc_dismiss__conditional_days'

			) );
			wpbc_bs_display_tab(   array(
							'title'         => __('Aggregate', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__aggregate').show();"
							, 'font_icon'   => 'wpbc-bi-calendar2-plus'
							, 'default'     => false
							, 'css_classes' => 'wpbc_dismiss__booking_param_aggregate'
			) );
			wpbc_bs_display_tab(   array(
							'title'         => __('Advanced', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__other').show();"
							, 'font_icon'   => 'wpbc_icn_tune'
							, 'default'     => false

			) );

		wpbc_bs_toolbar_tabs_html_container_end();
	}



	/**
	 * Shortcode Field:  Select-box - "Booking Resource"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__select_selected_resource( $id , $group_key ){

		$resources_list = array();

			if ( class_exists( 'wpdev_bk_personal' ) ){

				$resources_list_orig = wpbc_get_all_booking_resources_list();

				$resources_list[0] = array( 'title' => ' - '.__('None', 'booking' ).' - ', 'attr' => array ( 'class' => 'wpbc_single_resource', 'style' => 'border-bottom:1px dashed #ddd;') );
				foreach ($resources_list_orig as $res_id => $res_val) {
					$resources_list[$res_id] = $res_val;
				}

			}
			WPBC_Settings_API::field_select_row_static( $id
															, array(
																	  'type'              => 'select'
																	, 'title'             => __( 'Preselected resource', 'booking')
																	, 'description'       => __( 'Choose the initially selected booking resource.', 'booking' )
																	, 'description_tag'   => 'span'
																	, 'label'             => ''
																	, 'multiple'          => false
																	, 'group'             => $group_key
																	, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed'
																	, 'class'             => ''
																	, 'css'               => 'margin-right:10px;min-width:15em;'
																	, 'only_field'        => false
																	, 'attr'              => array()
																	, 'value'             => ''
																	, 'options'           => $resources_list
															) );

	}

	/**
	 * Shortcode Fields:  AGGREGATE
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__select_multiple_resources( $id , $group_key ){

		if ( class_exists( 'wpdev_bk_personal' ) ){
			$resources_list_orig = wpbc_get_all_booking_resources_list();
			$resources_list = array();
			$resources_list[0] = array( 'title' => __('All', 'booking' ), 'attr' => array ( 'class' => 'wpbc_single_resource', 'style' => 'border-bottom:1px dashed #ddd;') );
			foreach ($resources_list_orig as $res_id => $res_val) {
				$resources_list[$res_id] = $res_val;
			}

			?>
			<div class="clear"></div>
			<div class="wpbc-settings-notice0 notice-info notice-header" style="font-size: 15px;font-weight: 600;margin: 15px 0 15px 15px;">
				<label for="<?php echo esc_attr($id); ?>"><?php _e( 'Set Up Booking Resource(s)', 'booking' ); ?></label>
			</div>

			<div class="wpbc_shortcode_config__rules_container">
				<div class="wpbc_shortcode_config__rules__params_section 		wpbc_aggregate">

						<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
							<div class="ui_container ui_container_mini">
								<div class="ui_group">
									<div class="ui_element wpdevelop"><?php

										WPBC_Settings_API::field_select_row_static(  $id //.  '__aggregate'
																					, array(
																							  'type'              => 'select'
																							, 'title'             => ''//__('Aggregate booking dates from other resources', 'booking')
																							, 'description'       => ''//__( 'Select booking resources, for getting booking dates from them and set such dates as unavailable in destination calendar.', 'booking' )
																							, 'description_tag'   => 'span'
																							, 'label'             => ''
																							, 'multiple'          => true
																							, 'group'             => $group_key
																							, 'tr_class'          => ''//$group_key . '_advanced_section'
																							, 'class'             => 'wpbc_ui_control wpbc_ui_select'
																							, 'css'               => 'margin-right:10px;height:12em;'
																							, 'only_field'        => true
																							, 'attr'              => array()
																							, 'value'             => '0'
																							, 'options'           => $resources_list
																						)
														);
									?></div>
								</div>
							</div>
						</div>
				</div>
				<div class="wpbc_shortcode_config__rules__help_section" style="margin-top: 8px;">
					<div class="wpbc-settings-notice notice-info notice-list">
						<ol>
							<li>
								<?php _e( 'Configure the shortcode to show Timeline.', 'booking' ); ?>
							</li>
							<li>
								<?php echo    __( 'To set this up, choose all or multiple booking resources in the multi-select box.', 'booking' )
											. '<br>'
											. __( 'Hold down the Ctrl button to make multiple selections.', 'booking' );
								?>
							</li>
 							<li>
								<?php
								printf( __( 'Refer to the FAQ for detailed information on %sshortcode configuration%s.', 'booking' )
										, '<a href="https://wpbookingcalendar.com/faq/shortcode-timeline/" target="_blank">', '</a>'
									  );
								?>
							</li>
						</ol>
					</div>
				</div>
			</div><?php

		}
	}
	
	/**
	 * Shortcode Field:  Label - text of label for the select box.
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__text_label($id , $group_key ){

		WPBC_Settings_API::field_text_row_static( $id
									, array(
											  'type'              => 'text'
											, 'placeholder'       =>  __( 'Example', 'booking' ). ': ' .__( 'Please select the resource:', 'booking' )
											, 'title'             => __('Label', 'booking')
											, 'description'       => __('The title displayed next to your select box.', 'booking')
											, 'description_tag'   => 'span'
											, 'label'             => ''
											, 'class'             => ''
											, 'css'               => 'width0:5em;'
											, 'tr_class'          => ''

											, 'group'             => $group_key
											, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed'
										    , 'only_field'        => false
											, 'attr'              => array()
											, 'value'             => ''
										)
					);

	}


	/**
	 * Shortcode Field:  Label - text of label for the select box.
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__text_first_option_title($id , $group_key ){

		WPBC_Settings_API::field_text_row_static( $id
									, array(
											  'type'              => 'text'
											, 'placeholder'       => __( 'Example', 'booking' ). ': ' . __( 'Please select', 'booking' )
											, 'title'             => __('Title for first option', 'booking')
											, 'description'       =>  __('The first option in the dropdown list.' ,'booking') . ' <span>' . __('Please leave it empty if you want to skip it.' ,'booking') . '</span>'

											, 'description_tag'   => 'span'
											, 'label'             => ''
											, 'class'             => ''
											, 'css'               => 'width0:5em;'
											, 'tr_class'          => ''

											, 'group'             => $group_key
											, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed'
										    , 'only_field'        => false
											, 'attr'              => array()
											, 'value'             => ''
										)
					);

	}