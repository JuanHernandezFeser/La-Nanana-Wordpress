<?php
/**
 * @version 1.0
 * @package Booking Calendar Shortcodes Config
 * @subpackage Shortcodes Content - Booking Calendar Form e.g.:		[booking resource_id=1 nummonths=3 options='{calendar months_num_in_row=3 width=100% cell_height=50px}']
 * @category Shortcodes
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2024-01-21
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly			//FixIn: 9.9.0.15

// =====================================================================================================================
//  Left Navigation Panel
// =====================================================================================================================

/**
 * Left Vertical Navigation panel
 * @return void
 */
function wpbc_shortcode_config__navigation_panel(){

	$wpbm_version         = wpbc_get_wpbm_version();
	$wpbm_minimum_version = '2.1';

	// If lower than 2,  than  show warning
	if ( version_compare( $wpbm_version, $wpbm_minimum_version, '<') ) {
		$is_bm_exist = false;
	} else {
		$is_bm_exist = true;
	}

	?>
	<div class="wpbc_settings_navigation_column wpbc_shortcode_config_navigation_column">
		<div id="wpbc_shortcode_config__nav_tab__booking" class="wpbc_settings_navigation_item wpbc_settings_navigation_item_active">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_booking', 'booking' );" href="javascript:void(0);">
				<span><?php _e( 'Booking Form with Calendar', 'booking' ) ?></span>
			</a>
		</div>
		<div id="wpbc_shortcode_config__nav_tab__bookingcalendar" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_bookingcalendar', 'bookingcalendar' );" href="javascript:void(0);">
				<span><?php _e( 'Availability Calendar / Days Not Selectable', 'booking' ) ?></span>
			</a>
		</div>
		<div id="wpbc_shortcode_config__nav_tab__bookingselect" class="wpbc_settings_navigation_item wpbc_dismiss__booking_select <?php  echo ( ! class_exists( 'wpdev_bk_personal' ) ) ? ' wpbc_settings_navigation_item_disabled ' : '';  ?>">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_bookingselect', 'bookingselect' );" href="javascript:void(0);">
				<span><?php _e( 'Resource Selection', 'booking' ) ?></span><?php
					echo ( ! class_exists( 'wpdev_bk_personal' ) ) ? '<span class="wpbc_pro_label">Pro</span>' : '';
				?>
			</a>
		</div>
		<div id="wpbc_shortcode_config__nav_tab__bookingform" class="wpbc_settings_navigation_item wpbc_dismiss__booking_form_only <?php  echo ( ! class_exists( 'wpdev_bk_biz_l' ) ) ? ' wpbc_settings_navigation_item_disabled ' : '';  ?>">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_bookingform', 'bookingform' );" href="javascript:void(0);">
				<span><?php _e( 'Only Form', 'booking' ) ?></span><?php
					if ( ! class_exists( 'wpdev_bk_personal' ) ){
						echo '<span class="wpbc_pro_label">Pro</span>';
					} else{
						echo ( ! class_exists( 'wpdev_bk_biz_l' ) ) ? '<span class="wpbc_pro_label">BL</span>' : '';
					}
				?>
			</a>
		</div>
		<div id="wpbc_shortcode_config__nav_tab__bookingtimeline" class="wpbc_settings_navigation_item wpbc_navigation_top_border">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_bookingtimeline', 'bookingtimeline' );" href="javascript:void(0);">
				<span><?php _e( 'TimeLine', 'booking' ) ?></span>
			</a>
		</div>
		<div id="wpbc_shortcode_config__nav_tab__bookingsearch" class="wpbc_settings_navigation_item wpbc_dismiss__booking_search <?php  echo ( ! class_exists( 'wpdev_bk_biz_l' ) ) ? ' wpbc_settings_navigation_item_disabled ' : '';  ?>">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_bookingsearch', 'bookingsearch' );" href="javascript:void(0);">
				<span><?php _e( 'Search Availability', 'booking' ) ?></span><?php
					if ( ! class_exists( 'wpdev_bk_personal' ) ){
						echo '<span class="wpbc_pro_label">Pro</span>';
					} else{
						echo ( ! class_exists( 'wpdev_bk_biz_l' ) ) ? '<span class="wpbc_pro_label">BL</span>' : '';
					}
				?>
			</a>
		</div>
		<div id="wpbc_shortcode_config__nav_tab__bookingother" class="wpbc_settings_navigation_item">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_bookingother', 'bookingother' );" href="javascript:void(0);">
				<span><?php _e( 'Other', 'booking' ) ?></span><?php
					//echo ( ! class_exists( 'wpdev_bk_personal' ) ) ? '<span class="wpbc_pro_label">Pro</span>' : '';
				?>
			</a>
		</div>
		<?php if ( $is_bm_exist ) { ?>
		<div id="wpbc_shortcode_config__nav_tab__booking_import_ics" class="wpbc_settings_navigation_item wpbc_navigation_top_border">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_booking_import_ics', 'booking_import_ics' );" href="javascript:void(0);">
				<span><?php _e( 'Import Events (.ics feed)', 'booking' ) ?></span>
			</a>
		</div>
		<div id="wpbc_shortcode_config__nav_tab__booking_listing_ics" class="wpbc_settings_navigation_item wpbc_navigation_sub_item">
			<a onclick="javascript:wpbc_shortcode_config_click_show_section(this,'#wpbc_sc_container__shortcode_booking_listing_ics' , 'booking_listing_ics');" href="javascript:void(0);">
				<span><?php _e( 'Show .ics Listing of Events', 'booking' ) ?></span>
			</a>
		</div>
		<?php } ?>
	</div>
	<?php
}


// =====================================================================================================================
//  Shortcode [booking ... ]
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [booking ...]
 *
 * @return void
 */
function wpbc_shortcode_config__content__booking() {

	$shortcode_name = 'booking';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		wpbc_shortcode_config__booking__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general">
			<table class="form-table"><tbody><?php

				// Booking Resource
				wpbc_shortcode_config_fields__select_resource( $shortcode_name . '_wpbc_resource_id', $shortcode_name );

				// Custom form
				wpbc_shortcode_config_fields__select_custom_form( $shortcode_name . '_wpbc_custom_form', $shortcode_name );

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

	wpbc_clear_div();
}


	/**
	 * Top Tabs for shortcode [booking ...]
	 * @return void
	 */
	function wpbc_shortcode_config__booking__top_tabs(){

		$shortcode_name = 'booking';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							'title'         => __('Booking Calendar Form', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'wpbc-bi-calendar3-range'
							, 'default'     => true
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



// =====================================================================================================================
//  Fields
// =====================================================================================================================

	/**
	 * Shortcode Field:  Select-box - "Booking Resource"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__select_resource( $id , $group_key ){

			if ( class_exists( 'wpdev_bk_personal' ) ){

				WPBC_Settings_API::field_select_row_static( $id
															, array(
																	  'type'              => 'select'
																	, 'title'             => __( 'Booking resource', 'booking')
																	, 'description'       => __( 'Select booking resource', 'booking' )
																	, 'description_tag'   => 'span'
																	, 'label'             => ''
																	, 'multiple'          => false
																	, 'group'             => $group_key
																	, 'tr_class'          => $group_key . '_standard_section'
																	, 'class'             => ''
																	, 'css'               => 'margin-right:10px;'
																	, 'only_field'        => false
																	, 'attr'              => array()
																	, 'value'             => ''
																	, 'options'           => wpbc_get_all_booking_resources_list()
															) );
			}

	}

	/**
	 * Shortcode Field:  Select-box - "Custom Booking Form"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__select_custom_form( $id , $group_key ){

		if ( class_exists( 'wpdev_bk_biz_m' ) ){
			wpbc_in_settings__form_selection( array(
													  'name'        => $id
													, 'title'       => __('Booking Form', 'booking')
													, 'description' => __('Select custom booking form' ,'booking')
													, 'group'       => $group_key
												)
										);
		}
	}

	/**
	 * Shortcode Field:  Select-box - "Calendar Months Number"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__select_nummonths( $id , $group_key ){

		WPBC_Settings_API::field_select_row_static(   $id
													, array(
															  'type'              => 'select'
															, 'title'             => __('Visible months', 'booking')
															, 'description'       => __('Select number of month to show for calendar.' ,'booking')
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => get_bk_option( 'booking_client_cal_count' )
															, 'options'           => array_combine( range( 1, 12 ), range( 1, 12 ) )
														)
						);
	}

	/**
	 * Shortcode Fields:  OPTION - "Define Calendar Size"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__calendar_size( $id , $group_key ){

			// ---------------------------------------------------------------------------------------------------------
			WPBC_Settings_API::field_checkbox_row_static( $id . '_enabled'
														, array(
																  'type'              => 'checkbox'
																, 'title'             => __('Setup Size & Structure', 'booking')
																, 'description'       => __('Configure Calendar Size and Structure', 'booking')
																, 'description_tag'   => 'span'
																, 'label'             => ''
																, 'class'             => ''
																, 'css'               => ''
																, 'tr_class'          => ''
																, 'attr'              => array()
																, 'group'             => $group_key
																, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed'
																, 'only_field'        => !true
																, 'is_new_line'       => false
																, 'value'             => false
															)
					);

			WPBC_Settings_API::field_select_row_static(   $id . '_months_num_in_row'
														, array(
																  'type'              => 'select'
																, 'title'             => __('Number of months in a row', 'booking')
																, 'description'       => __('Select the number of months to show in one row on a wide screen.' ,'booking')
																, 'description_tag'   => 'span'
																, 'label'             => ''
																, 'multiple'          => false
																, 'group'             => $group_key
																, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed ' . $id . '_wpbc_sc_calendar_size'
																, 'class'             => ''
																, 'css'               => 'margin-right:10px;'
																, 'only_field'        => false
																, 'attr'              => array()
																, 'value'             => get_bk_option( 'booking_client_cal_count' )
																, 'options'           => array_combine( range( 1, 6 ), range( 1, 6 ) )
															)
							);

				?><tr valign="top"  class="<?php echo $group_key . '_standard_section'; ?> wpbc_sub_settings_grayed <?php echo $id; ?>_wpbc_sc_calendar_size">
					<th scope="row" style="vertical-align: middle;">
						<label for="wpbc_booking_width" class="wpbc-form-text"><?php  _e('Calendar width:', 'booking'); ?></label>
					</th>
					<td class=""><fieldset><?php

						WPBC_Settings_API::field_text_row_static( $id . '_calendar_width'
															, array(
																	  'type'              => 'text'
																	, 'placeholder'       => '100'
																	, 'class'             => ''
																	, 'css'               => 'width:5em;'
																	, 'only_field'        => true
																	, 'attr'              => array()
																	, 'value'             => '100'
																)
											);
						WPBC_Settings_API::field_select_row_static(  $id . '_calendar_width_px_pr'
																	, array(
																			  'type'              => 'select'
																			, 'multiple'          => false
																			, 'class'             => ''
																			, 'css'               => 'width:4em;'
																			, 'only_field'        => true
																			, 'attr'              => array()
																			, 'value'             => '%'
																			, 'options'           => array( 'px' => 'px', '%'  => '%' )
																		)
										);
						?><span class="description"> <?php _e('Set width of calendar' ,'booking'); ?></span></fieldset></td>
				</tr><?php

				?><tr valign="top" class="<?php echo $group_key . '_standard_section'; ?> wpbc_sub_settings_grayed <?php echo $id; ?>_wpbc_sc_calendar_size">
					<th scope="row" style="vertical-align: middle;"><label for="wpbc_booking_cell_height" class="wpbc-form-text"><?php  _e('Calendar cell height:', 'booking'); ?></label></th>
					<td class="">
						<fieldset><?php

							WPBC_Settings_API::field_text_row_static( $id . '_calendar_cell_height'
															, array(
																	  'type'              => 'text'
																	, 'placeholder'       => '50'
																	, 'class'             => ''
																	, 'css'               => 'width:5em;'
																	, 'only_field'        => true
																	, 'attr'              => array()
																	, 'value'             => '50'
																)
											);
							WPBC_Settings_API::field_select_row_static(  $id . '_calendar_cell_height_px_pr'
																	, array(
																			  'type'              => 'select'
																			, 'multiple'          => false
																			, 'class'             => ''
																			, 'css'               => 'width:4em;'
																			, 'only_field'        => true
																			, 'attr'              => array()
																			, 'value'             => '%'
																			, 'options'           => array( 'px' => 'px' )
																		)
										);
							?><span class="description"> <?php _e('Set cell height for calendar' ,'booking'); ?></span>
						</fieldset>
					</td>
				</tr><?php

	}

	/**
	 * Shortcode Fields:  Start Month
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__start_month( $id , $group_key ){

			////////////////////////////////////////////////////////////////////
			// Start Month
			////////////////////////////////////////////////////////////////////
			?><tr valign="top" class="<?php echo $group_key . '_standard_section'; ?>">
				<th scope="row" style="vertical-align: middle;"><label for="<?php echo $id; ?>_active" class="wpbc-form-text"><?php  _e('Start month:', 'booking'); ?></label></th>
				<td class=""><fieldset><?php

					WPBC_Settings_API::field_checkbox_row_static( $id . '_active'
																, array(
																		  'type'              => 'checkbox'
																		, 'title'             => ''
																		, 'description'       => ''
																		, 'description_tag'   => 'span'
																		, 'label'             => ''
																		, 'class'             => ''
																		, 'css'               => ''
																		, 'tr_class'          => ''
																		, 'attr'              => array()
																		, 'group'             => $group_key
																		, 'only_field'        => true
																		, 'is_new_line'       => false
																		, 'value'             => false
																	)
							);

					WPBC_Settings_API::field_select_row_static(  $id . '_year'
																, array(
																		  'type'              => 'select'
																		, 'title'             => ''
																		, 'description'       => ''
																		, 'description_tag'   => 'span'
																		, 'label'             => ''
																		, 'multiple'          => false
																		, 'group'             => $group_key
																		, 'class'             => ''
																		, 'css'               => 'width:5em;'
																		, 'only_field'        => true
																		, 'attr'              => array()
																		, 'value'             => date( 'Y' )
																		, 'options'           => array_combine( range( ( date('Y') - 1 ), ( date('Y') + 10 ) ), range( ( date('Y') - 1 ), ( date('Y') + 10 ) )  )
																	)
									);
					?><span class="description" style="font-weight:600;flex:0;margin: 3px 0.5em 0.5em 0;"> / </span><?php

					WPBC_Settings_API::field_select_row_static(  $id . '_month'
																, array(
																		  'type'              => 'select'
																		, 'title'             => ''
																		, 'description'       => ''
																		, 'description_tag'   => 'span'
																		, 'label'             => ''
																		, 'multiple'          => false
																		, 'group'             => $group_key
																		, 'class'             => ''
																		, 'css'               => 'width:4em;'
																		, 'only_field'        => true
																		, 'attr'              => array()
																		, 'value'             => date('n')
																		, 'options'           => array_combine( range( 1, 12 ), range( 1, 12 ) )
																	)
									);

					?><span class="description"> <?php _e('Select start month of calendar' ,'booking'); ?></span></fieldset></td>
			</tr><?php

	}

	/**
	 * Shortcode Fields:  AGGREGATE
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__aggregate( $id , $group_key ){

			//--------------------------------------------------------------------------------------------------------------
			// 'Upgrade to Pro' widget
			//--------------------------------------------------------------------------------------------------------------
			$upgrade_content_arr = wpbc_get_upgrade_widget( array(
									'id'                 => 'widget_wpbc_dismiss__booking_param_aggregate',
									'dismiss_css_class'  => '.wpbc_dismiss__booking_param_aggregate',
									'blured_in_versions' => array( 'free' ),
									'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#booking-resources' ),
									'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
									'versions'           => 'paid versions',
									'css'                => 'transform: translate(0) translateY(180px);'
								) );
			echo $upgrade_content_arr['content'];

			//--------------------------------------------------------------------------------------------------------------
			// Real Content
			//--------------------------------------------------------------------------------------------------------------

			$resources_list = array();
			if ( class_exists( 'wpdev_bk_personal' ) ){
				$resources_list_orig = wpbc_get_all_booking_resources_list();

				$resources_list[0] = array( 'title' => __('None', 'booking' ), 'attr' => array ( 'class' => 'wpbc_single_resource', 'style' => 'border-bottom:1px dashed #ddd;') );
				foreach ($resources_list_orig as $res_id => $res_val) {
					$resources_list[$res_id] = $res_val;
				}
			}

			?>
			<div class="clear"></div>
			<div class="wpbc-settings-notice0 notice-info notice-header" style="font-size: 15px;font-weight: 600;margin: 15px 0 15px 15px;">
				<label for="<?php echo esc_attr($id); ?>"><?php _e( 'Aggregate booking dates from other resources', 'booking' ); ?></label>
			</div>

			<div class="wpbc_shortcode_config__rules_container wpbc_dismiss__booking_param_aggregate <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>">
				<div class="wpbc_shortcode_config__rules__params_section 		wpbc_aggregate">

						<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
							<div class="ui_container ui_container_mini">
								<div class="ui_group">
									<div class="ui_element wpdevelop"><?php

										WPBC_Settings_API::field_select_row_static(  $id //.  '__aggregate'
																					, array(
																							  'type'              => 'select'
																							, 'title'             => __('Aggregate booking dates from other resources', 'booking')
																							, 'description'       => ''//__( 'Select booking resources, for getting booking dates from them and set such dates as unavailable in destination calendar.', 'booking' )
																							, 'description_tag'   => 'span'
																							, 'label'             => ''
																							, 'multiple'          => true
																							, 'group'             => $group_key
																							, 'tr_class'          => ''//$group_key . '_advanced_section'
																							, 'class'             => 'wpbc_ui_control wpbc_ui_select'
																							, 'css'               => 'margin-right:10px;height:20em;min-width:15em;'
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
						<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
							<div class="ui_container ui_container_mini 		ui_container_weekdays">
								<div class="ui_group  	ui_group_weekdays_checkboxes">
									<div class="ui_element"><?php

										WPBC_Settings_API::field_checkbox_row_static( $id . '__bookings_only'
																			, array(
																					  'type'              => 'checkbox'
																					, 'title'             => ''
																					, 'description'       => ''
																					, 'description_tag'   => 'span'
																					, 'label'             => __( 'Aggregate only bookings', 'booking' )
																					, 'class'             => ''
																					, 'css'               => ''
																					, 'tr_class'          => ''
																					, 'attr'              => array()
																					, 'group'             => $group_key
																					, 'only_field'        => true
																					, 'is_new_line'       => false
																					, 'value'             => !false
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
								<?php _e( "Show dates as booked in the current calendar if such dates are unavailable in one of the selected resources.", 'booking' ); ?>
							</li>
							<li>
								<?php
								echo    __( 'To set this up, select one or multiple booking resources in the multi-select box.', 'booking' )
											. '<br>'
											. __( 'Hold down the Ctrl button to make multiple selections.', 'booking' );

								echo ' <strong>' . __( 'Code Example', 'booking' ) . ': </strong>'
												. '<code>[booking resource_id=1 aggregate=\'3;5;9\']</code>';
								?>

							</li>

							<li>
								<?php
								 printf(__('Enable %sAggregate only bookings%s option to  aggregate only bookings without including unavailable dates from the %s page.','booking')
											 , '<strong>', '</strong>'
											, '<strong>Booking > Availability > Days Availability</strong>'
								 );
								?>

								<?php
								 echo ' <strong>' . __( 'Code Example', 'booking' ) . ': </strong>'
								 			, '<code>[booking resource_id=1 aggregate=\'3;5;9\' options=\'{aggregate type=bookings_only}\']</code>';

								?>
							</li>
							<li>
								<?php
								printf(	__ ('Check the FAQ for details on %sshortcode configuration%s, especially this %soption%s.' , 'booking' )
										, '<a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/" target="_blank">', '</a>'
										, '<strong><a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/#booking-options-start-day-condition" target="_blank">', '</a></strong>' );
								?>
							</li>
						</ol>
					</div>
				</div>
			</div><?php


	}



	// -----------------------------------------------------------------------------------------------------------------


	function wpbc_shortcode_config_fields__dates_conditions_toolbar(){

		?><div class="clear"></div><?php
		?><div class="wpdevelop wpdvlp-nav-tabs-container wpbc_shortcode_config_fields__dates_conditions_toolbar"><?php

			$js = "jQuery(this).parents( '.wpbc_shortcode_config_fields__dates_conditions_toolbar' ).find( '.nav-tab' ).removeClass('wpdevelop-submenu-tab-selected');
				   jQuery(this).addClass('wpdevelop-submenu-tab-selected');
				   jQuery('.nav-tab i.icon-white').removeClass('icon-white');
				   jQuery('.nav-tab-active i').addClass('icon-white');
				   jQuery(this).parents('.wpbc_sc_container__shortcode_section').find('.wpbc_sc_container__shortcode_subsection').hide();
				   jQuery(this).parents('.wpbc_sc_container__shortcode_section')";

		  	wpbc_bs_toolbar_sub_html_container_start();

				?><a class="nav-tab wpdevelop-submenu-tab tooltip_top wpdevelop-submenu-tab-selected wpbc_dismiss__conditional_days"
				     onclick="javascript:<?php echo $js; ?>.find('.wpbc_sc_container__shortcode_subsection__weekdays_conditions').show();"	href="javascript:void(0)"
				   ><i class="menu_icon icon-1x wpbc-bi-calendar2-week"></i><span class="nav-tab-text">&nbsp;&nbsp;<?php
						_e( 'Weekdays Conditions', 'booking' );
					?></span></a><?php

				?><a class="nav-tab wpdevelop-submenu-tab tooltip_top wpbc_dismiss__conditional_days"
				     onclick="javascript:<?php echo $js; ?>.find('.wpbc_sc_container__shortcode_subsection__seasons_conditions').show();"	href="javascript:void(0)"
				   ><i class="menu_icon icon-1x wpbc-bi-calendar2-month"></i><span class="nav-tab-text">&nbsp;&nbsp;<?php
						_e( 'Seasons Conditions', 'booking' );
					?></span></a><?php

				?><a class="nav-tab wpdevelop-submenu-tab tooltip_top wpbc_dismiss__conditional_days"
				     onclick="javascript:<?php echo $js; ?>.find('.wpbc_sc_container__shortcode_subsection__startseason_conditions').show();"	href="javascript:void(0)"
				   ><i class="menu_icon icon-1x wpbc-bi-calendar2-day"></i><span class="nav-tab-text">&nbsp;&nbsp;<?php
						_e( 'Start selection at Seasons', 'booking' );
					?></span></a><?php

				?><a class="nav-tab wpdevelop-submenu-tab tooltip_top wpbc_dismiss__conditional_days"
				     onclick="javascript:<?php echo $js; ?>.find('.wpbc_sc_container__shortcode_subsection__dates_conditions').show();"	href="javascript:void(0)"
				   ><i class="menu_icon icon-1x wpbc-bi-calendar2-date"></i><span class="nav-tab-text">&nbsp;&nbsp;<?php
						_e( 'Dates selection Conditions', 'booking' );
					?></span></a><?php

 		wpbc_bs_toolbar_sub_html_container_end();

		?>
		</div><?php
	}


	/**
	 * Week Days - Condition fields for 'option' parameter.
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__weekdays_conditions( $id , $group_key ){

		//--------------------------------------------------------------------------------------------------------------
		// 'Upgrade to Pro' widget
		//--------------------------------------------------------------------------------------------------------------
		$upgrade_content_arr = wpbc_get_upgrade_widget( array(
								'id'                 => $id . '_' . 'conditional_days_weekdays',
								'dismiss_css_class'  => '.wpbc_dismiss__conditional_days',
								'blured_in_versions' => array( 'free', 'ps', 'bs' ),
								'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#advanced-days-selection' ),
								'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
								'versions'           => 'Business Medium / Large, MultiUser versions',
								'css'                => 'transform: translate(0) translateY(120px);'
							) );
		echo $upgrade_content_arr['content'];

		//--------------------------------------------------------------------------------------------------------------
		// Conditions for Weekdays selection
		//--------------------------------------------------------------------------------------------------------------
		?><div class="wpbc_shortcode_config__rules_container wpbc_dismiss__conditional_days <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>">

			<div class="wpbc_shortcode_config__rules__params_section 		wpbc_select_day_weekday">

				<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
					<div class="ui_container ui_container_mini 		ui_container_weekdays">
						<?php

							$week_days_arr = array(
												  1 => __( 'Mo', 'booking' )
												, 2 => __( 'Tu', 'booking' )
												, 3 => __( 'We', 'booking' )
												, 4 => __( 'Th', 'booking' )
												, 5 => __( 'Fr', 'booking' )
												, 6 => __( 'Sa', 'booking' )
												, 0 => __( 'Su', 'booking' )
											);
							foreach ( $week_days_arr as $weekday_key => $weekday_title ) {

								?><div class="ui_group  	ui_group_weekdays_checkboxes"><?php

									?><div class="ui_element"><?php

										WPBC_Settings_API::field_checkbox_row_static( $id . '__weekday_' . $weekday_key
																			, array(
																					  'type'              => 'checkbox'
																					, 'title'             => ''
																					, 'description'       => ''
																					, 'description_tag'   => 'span'
																					, 'label'             => $weekday_title
																					, 'class'             => ''
																					, 'css'               => ''
																					, 'tr_class'          => ''
																					, 'attr'              => array()
																					, 'group'             => $group_key
																					, 'only_field'        => true
																					, 'is_new_line'       => false
																					, 'value'             => false
																				)
															);

									?></div>
									<div class="ui_element"><?php

										$placeholder = '';
										switch ( $weekday_key ) {
											case 0: $placeholder = '1-7'; break;
											case 1: $placeholder = '5,7'; break;
											case 2: $placeholder = '3,4'; break;
											case 3: $placeholder = '5'; break;
											case 4: $placeholder = '4'; break;
											case 5: $placeholder = '3'; break;
											case 6: $placeholder = '2-7'; break;
										}

										WPBC_Settings_API::field_text_row_static(   $id . '__days_number_' . $weekday_key
																			, array(
																				  'type'              => 'text'
																				, 'placeholder'       => $placeholder
																				, 'class'             => 'wpbc_dates_conditions_value_weekday'
																				, 'css'               => ''
																				, 'only_field'        => true
																				, 'attr'              => array()
																				, 'value'             => ''
																			)
											);

									?></div>
									<div class="ui_element"><?php
										?><div class="wpbc_ui_control" style="line-height: 2;"><?php _e('days to select' ,'booking'); ?></div><?php
									?></div><?php

								?></div><?php
							}

					?>
					</div>
				</div>

				<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
					<div class="ui_container ui_container_mini">
						<div class="ui_group"><?php

							?><div class="ui_element">
								<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config__select_day_weekday__add('<?php echo $id; ?>');" class="button button-primary"><?php _e( 'Set Rule', 'booking' ); ?></a>
							</div><?php
							?><div class="ui_element">
								<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config__select_day_weekday__reset('<?php echo $id; ?>');"
								   class="wpbc_ui_control wpbc_ui_button wpbc_ui_button_danger" ><?php _e('Reset','booking'); ?></a>
							</div><?php

						?>
						</div>
					</div>
				</div>

			</div>

			<div class="wpbc_shortcode_config__rules__help_section">
				<div class="wpbc-settings-notice notice-info notice-header">
					<span><?php _e( 'Specify the number of days to select when starting the selection on a specific weekday.', 'booking' ); ?></span>
				</div>
				<div class="wpbc-settings-notice notice-info notice-list">
					<ol>
						<li>
							<?php _e( "For example, a visitor can choose to book only 5 or 7 days starting on Monday, or any number from 2 to 7 days starting on Saturday, and so forth. To configure this, check the 'Monday' box and set the number of days to select as '5,7'. Then, check the 'Saturday' box and set the number of days to select as '2-7'. Finally, click the 'Set Rule' button.", 'booking' ); ?>
						</li>
						<li>
							<?php
							echo '<strong>' . __( 'Code Example', 'booking' ) . ': </strong>'
											. '<code>{select-day condition="weekday" for="1" value="5,7"},{select-day condition="weekday" for="6" value="2-7"}</code>';
							?>
						</li>
						<li>
							<?php
							printf(	__ ('Check the FAQ for details on %sshortcode configuration%s, especially this %soption%s.' , 'booking' )
									, '<a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/" target="_blank">', '</a>'
									, '<strong><a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/#booking-options-condition" target="_blank">', '</a></strong>' );
							?>
						</li>
						<li>
							<?php
							 printf(__('Explore %sJavaScript customization%s for advanced different day selection in different calendars.' ,'booking')
										, '<a href="https://wpbookingcalendar.com/faq/advanced-javascript-for-the-booking-shortcodes/" target="_blank">','</a>');
							?>
						</li>
					</ol>
				</div>
				<textarea id="<?php echo $id; ?>_textarea" name="<?php echo $id; ?>_textarea" style="width:100%;margin-top:15px;"></textarea>
			</div>
		</div><?php

	}


	/**
	 * Season Days  Selection - Condition fields for 'option' parameter.
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__season_conditions( $id, $group_key ) {

		//--------------------------------------------------------------------------------------------------------------
		// 'Upgrade to Pro' widget
		//--------------------------------------------------------------------------------------------------------------
		$upgrade_content_arr = wpbc_get_upgrade_widget( array(
								'id'                 => $id . '_' . 'conditional_days_seasons',
								'dismiss_css_class'  => '.wpbc_dismiss__conditional_days',
								'blured_in_versions' => array( 'free', 'ps', 'bs' ),
								'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#advanced-days-selection' ),
								'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
								'versions'           => 'Business Medium / Large, MultiUser versions',
								'css'                => 'transform: translate(0) translateY(120px);'
							) );
		echo $upgrade_content_arr['content'];

		//--------------------------------------------------------------------------------------------------------------
		// Conditions for Seasons days selection
		//--------------------------------------------------------------------------------------------------------------

		$options = wpbc_shortcode_config_fields__get_options_for_seasons();

		?><div class="wpbc_shortcode_config__rules_container wpbc_dismiss__conditional_days  <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>">
				<div class="wpbc_shortcode_config__rules__params_section 		wpbc_select_day_season">

					<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
						<div class="ui_container ui_container_mini">
							<div class="ui_group">

								<div class="ui_element">
									<div class="wpbc_ui_control" style="line-height: 2;">
										<span><?php _e('In season' ,'booking'); ?></span>
									</div>
								</div>

								<div class="ui_element wpdevelop"><?php
									WPBC_Settings_API::field_select_row_static( $id . '__season_filter_name'
																						, array(
																								  'type'              => 'select'
																								, 'title'             => ''
																								, 'label'             => ''
																								, 'disabled'          => false
																								, 'disabled_options'  => array()
																								, 'multiple'          => false
																								, 'description'       => ''
																								, 'description_tag'   => 'span'
																								, 'group'             => $group_key
																								, 'tr_class'          => ''
																								, 'class'             => 'wpbc_ui_control wpbc_ui_select'
																								, 'css'               => 'max-width: Min(14em, 100%);flex: 0 1 auto;width: 14em;border-radius:3px 0 0 3px;'
																								, 'only_field'        => true
																								, 'attr'              => array()
																								, 'value'             => '0'
																								, 'options'           => $options
																							)
																				);
									?><a href="<?php echo wpbc_get_availability_url(); ?>&tab=filter"
										 title="<?php echo esc_attr( __( 'Add new season', 'booking' ) ); ?>"
									   class="wpbc_ui_control wpbc_ui_button tooltip_top" ><i class="menu_icon icon-1x wpbc_icn_add _circle_outline"></i></a><?php
								?></div>

							</div>
						</div>

						<div class="ui_container ui_container_mini">
							<div class="ui_group">

								<div class="ui_element">
									<div class="wpbc_ui_control " style="line-height: 2;"><?php _e('allow to select' ,'booking'); ?></div>
								</div>

								<div class="ui_element"><?php
										WPBC_Settings_API::field_text_row_static(   $id . '__days_number'
																			, array(
																				  'type'              => 'text'
																				, 'placeholder'       => '5 ' . __( 'or', 'booking' ) . ' 1-7 ' . __( 'or', 'booking' ) . ' 1,5,7'
																				, 'class'             => 'wpbc_ui_control wpbc_ui_text'
																				, 'css'               => 'width:9em;border-radius:3px;'
																				, 'only_field'        => true
																				, 'attr'              => array()
																				, 'value'             => ''
																			)
											);
								?></div>

								<div class="ui_element">
									<div class="wpbc_ui_control" style="line-height: 2;"><?php _e('days' ,'booking'); ?></div>
								</div>
							</div>
						</div>

						<div class="ui_container ui_container_mini">
							<div class="ui_group">

								<div class="ui_element">
									<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config__select_day_season__add('<?php echo $id; ?>');"
											   class="wpbc_ui_control wpbc_ui_button wpbc_ui_button_primary" ><?php _e('Add Rule','booking'); ?></a>
								</div>
								<div class="ui_element">
									<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config__select_day_season__reset('<?php echo $id; ?>');"
											   class="wpbc_ui_control wpbc_ui_button wpbc_ui_button_danger" ><?php _e('Reset','booking'); ?></a>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="wpbc_shortcode_config__rules__help_section">
					<div class="wpbc-settings-notice notice-info notice-header">
						<span><?php _e( 'Specify the number of days to select when starting the selection in a specific season.', 'booking' ); ?></span>
					</div>
					<div class="wpbc-settings-notice notice-info notice-list">
						<ol>
							<li>
								<?php _e( "For example, a visitor can choose to book any number from 7 to 14 days or 21 days starting on 'High season', or any number from 2 to 5 days starting on 'Low season', and so forth. To configure this, select the 'High season' option in the dropdown menu and set the number of days to select as '7-14,21'. Click the 'Add Rule' button. Next, select the 'Low season' option in the dropdown menu and set the number of days to select as '2-5'. Click the 'Add Rule' button again.", 'booking' ); ?>
							</li>
							<li>
								<?php
								echo '<strong>' . __( 'Code Example', 'booking' ) . ': </strong>'
												. '<code>{select-day condition="season" for="High season" value="7-14,21"},{select-day condition="season" for="Low season" value="2-5"}</code>';
								?>
							</li>
							<li>
								<?php
								printf(	__ ('Check the FAQ for details on %sshortcode configuration%s, especially this %soption%s.' , 'booking' )
										, '<a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/" target="_blank">', '</a>'
										, '<strong><a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/#booking-options-condition-seasons" target="_blank">', '</a></strong>' );
								?>
							</li>
							<li>
								<?php
								 printf(__('Explore %sJavaScript customization%s for advanced different day selection in different calendars.' ,'booking')
											, '<a href="https://wpbookingcalendar.com/faq/advanced-javascript-for-the-booking-shortcodes/" target="_blank">','</a>');
								?>
							</li>
						</ol>
					</div>
					<textarea id="<?php echo $id; ?>_textarea" name="<?php echo $id; ?>_textarea" style="width:100%;margin:10px 0;"></textarea>
				</div>
		</div><?php

	}


		/**
		 * Get Season Filters options  - for selectbox in PopUp shortcode config
		 *
		 * @return array|array[]
		 */
		function wpbc_shortcode_config_fields__get_options_for_seasons(){

			if (function_exists('wpbc_sf_cache')) {
				// -----------------------------------------------------------------------------------------------------------------
				// Get Season Filters data:
				// -----------------------------------------------------------------------------------------------------------------
				$wpbc_sf_cache = wpbc_sf_cache();
				/**
				 * $wpbc_sf_cache->get_data() = [
				 * 1 => [
				 * booking_filter_id = "1"
				 * title = "Weekend"
				 * filter = "a:4:{s:8:"weekdays";a:7..."
				 * users = "1"
				 * id = "1"
				 */
				$season_filter_arr = $wpbc_sf_cache->get_data();
			} else {
				$season_filter_arr = array();
			}
			// $wpbc_br_cache = wpbc_br_cache();
			/*
				$wpbc_br_cache->get_resources() = [
													 1 = [
														  booking_type_id = "1"
														  title = "Standard"
														  users = "1"
														  import = null
														  export = "/ics/wpbm.ics"
														  cost = "25"
														  default_form = "standard"
														  prioritet = "1"
														  parent = "0"
														  visitors = "2"
														  id = "1"
														  count = {int} 5
													 7 = [...
			 */
			$current_user_id  = wpbc_get_current_user_id();

			$options = array(
				0 => array(
					'title' => ' - ' . __( 'Please Select', 'booking' ) . ' - ',
					'attr'  => array( 'style' => 'color:#999;font-weight: 400;' )
				)
			);
			foreach ( $season_filter_arr as $filter_id => $filter_arr ) {

				$options[ $filter_arr['id'] ] = $filter_arr;

				// MultiUser functionality  for regular  users showing season filters.
				if ( ( class_exists( 'wpdev_bk_multiuser' ) ) && ( ! empty( $filter_arr['users'] ) ) ) {

					// Is current user suer booking admin  and if this user was simulated log in
					$season_user_id = intval( $filter_arr['users'] );
					$is_user_super_admin = apply_bk_filter( 'is_user_super_admin', $season_user_id );

					if ( ( ! $is_user_super_admin ) && ( $current_user_id != $season_user_id ) ) {
						$options[ $filter_arr['id'] ]['attr'] = array( 'style' => 'color:#a43232;font-weight: 400;font-style: italic;' );
						$user_name = get_userdata( $season_user_id );
						$user_name = $user_name->display_name;
						$options[ $filter_arr['id'] ]['title'] .= ' &nbsp; (' . __( 'Regular User', 'booking' ) . ': ' . $user_name . ')';
					}
				}
			}

			return $options;

		}


	/**
	 * Season Days  Start Day - Condition fields for 'option' parameter.
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__season_start_day_conditions( $id, $group_key ) {

		//--------------------------------------------------------------------------------------------------------------
		// 'Upgrade to Pro' widget
		//--------------------------------------------------------------------------------------------------------------
		$upgrade_content_arr = wpbc_get_upgrade_widget( array(
								'id'                 => $id . '_' . 'conditional_days_startday',
								'dismiss_css_class'  => '.wpbc_dismiss__conditional_days',
								'blured_in_versions' => array( 'free', 'ps', 'bs' ),
								'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#advanced-days-selection' ),
								'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
								'versions'           => 'Business Medium / Large, MultiUser versions',
								'css'                => 'transform: translate(0) translateY(120px);'
							) );
		echo $upgrade_content_arr['content'];

		//--------------------------------------------------------------------------------------------------------------
		// Conditions for Seasons days  START selection
		//--------------------------------------------------------------------------------------------------------------

		$options = wpbc_shortcode_config_fields__get_options_for_seasons();

		?><div class="wpbc_shortcode_config__rules_container wpbc_dismiss__conditional_days  <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>">
			<div class="wpbc_shortcode_config__rules__params_section 		wpbc_start_day_season">

					<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
						<div class="ui_container ui_container_mini">
							<div class="ui_group">
								<div class="ui_element">
									<div class="wpbc_ui_control" style="line-height: 2;">
										<span><?php _e('In season' ,'booking'); ?></span>
									</div>
								</div>
								<div class="ui_element wpdevelop"><?php

									WPBC_Settings_API::field_select_row_static( $id .  '__season_filter_name'
																				, array(
																						  'type'              => 'select'
																						, 'title'             => ''
																						, 'label'             => ''
																						, 'disabled'          => false
																						, 'disabled_options'  => array()
																						, 'multiple'          => false
																						, 'description'       => ''
																						, 'description_tag'   => 'span'
																						, 'group'             => $group_key
																						, 'tr_class'          => ''
																						, 'class'             => 'wpbc_ui_control wpbc_ui_select'
																						, 'css'               => 'max-width: Min(14em, 100%);flex: 0 1 auto;width: 14em;border-radius:3px 0 0 3px;'
																						, 'only_field'        => true
																						, 'attr'              => array()
																						, 'value'             => '0'
																						, 'options'           => $options
																					)
																		);

									?><a href="<?php echo wpbc_get_availability_url(); ?>&tab=filter"
										 title="<?php echo esc_attr( __( 'Add new season', 'booking' ) ); ?>"
									   class="wpbc_ui_control wpbc_ui_button tooltip_top" ><i class="menu_icon icon-1x wpbc_icn_add _circle_outline"></i></a><?php

								?></div>
							</div>
						</div>
					</div>
					<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
						<div class="ui_container ui_container_mini 		ui_container_weekdays">
							 <div class="ui_group">
								 <div class="ui_element">
									 <div class="wpbc_ui_control " style="line-height: 2;"><?php
										 _e('allow to start days selection only for these weekdays' ,'booking');
									 ?></div>
								 </div>
							 </div>
							<div class="ui_group  	ui_group_weekdays_checkboxes"><?php

									$week_days_arr = array(
														  1 => __( 'Mo', 'booking' )
														, 2 => __( 'Tu', 'booking' )
														, 3 => __( 'We', 'booking' )
														, 4 => __( 'Th', 'booking' )
														, 5 => __( 'Fr', 'booking' )
														, 6 => __( 'Sa', 'booking' )
														, 0 => __( 'Su', 'booking' )
													);
									foreach ( $week_days_arr as $weekday_key => $weekday_title ) {

										?><div class="ui_element"><?php

											WPBC_Settings_API::field_checkbox_row_static( $id . '__weekday_' . $weekday_key
																				, array(
																						  'type'              => 'checkbox'
																						, 'title'             => ''
																						, 'description'       => ''
																						, 'description_tag'   => 'span'
																						, 'label'             => $weekday_title
																						, 'class'             => ''
																						, 'css'               => ''
																						, 'tr_class'          => ''
																						, 'attr'              => array()
																						, 'group'             => $group_key
																						, 'only_field'        => true
																						, 'is_new_line'       => false
																						, 'value'             => false
																					)
											);
										?></div><?php

									}

							?></div>
						</div>
					</div>
					<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
						<div class="ui_container ui_container_mini">
							<div class="ui_group"><?php

								?><div class="ui_element">
									<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config__start_day_season__add('<?php echo $id; ?>');"
									   class="wpbc_ui_control wpbc_ui_button wpbc_ui_button_primary" ><?php _e('Add Rule','booking'); ?></a>
								</div><?php
								?><div class="ui_element">
									<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config__start_day_season__reset('<?php echo $id; ?>');"
									   class="wpbc_ui_control wpbc_ui_button wpbc_ui_button_danger" ><?php _e('Reset','booking'); ?></a>
								</div><?php

							?>
							</div>
						</div>
					</div>
			</div>
			<div class="wpbc_shortcode_config__rules__help_section">
				<div class="wpbc-settings-notice notice-info notice-header">
					<span><?php _e( 'Specify the weekdays as start days for selection when starting the selection in a specific season.', 'booking' ); ?></span>
				</div>
				<div class="wpbc-settings-notice notice-info notice-list">
					<ol>
						<li>
							<?php _e( "For example, a visitor can starting days selection only on 'Sunday' or 'Friday' if select days on 'Low season'. To configure this, select the 'Low season' option in the dropdown menu and enable 'Sunday' and 'Friday' boxes. Click the 'Add Rule' button.", 'booking' ); ?>
						</li>
						<li>
							<?php
							echo '<strong>' . __( 'Code Example', 'booking' ) . ': </strong>'
											. '<code>{start-day condition="season" for="Low season" value="0,5"}</code>';
							?>
						</li>
						<li>
							<?php
							printf(	__ ('Check the FAQ for details on %sshortcode configuration%s, especially this %soption%s.' , 'booking' )
									, '<a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/" target="_blank">', '</a>'
									, '<strong><a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/#booking-options-start-day-condition" target="_blank">', '</a></strong>' );
							?>
						</li>
						<li>
							<?php
							 printf(__('Explore %sJavaScript customization%s for advanced different day selection in different calendars.' ,'booking')
										, '<a href="https://wpbookingcalendar.com/faq/advanced-javascript-for-the-booking-shortcodes/" target="_blank">','</a>');
							?>
						</li>
					</ol>
				</div>
				<textarea id="<?php echo $id; ?>_textarea" name="<?php echo $id; ?>_textarea" style="width:100%;margin:10px 0;"></textarea>
			</div>
		</div><?php

	}



	/**
	 * Dates Condition  - Set Number of Days to Select, if start selection on specific date '2024-02-14'
	 *
	 * Config dialog for Booking Calendar shortcode
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__fordate_conditions( $id, $group_key ) {

		//--------------------------------------------------------------------------------------------------------------
		// 'Upgrade to Pro' widget
		//--------------------------------------------------------------------------------------------------------------
		$upgrade_content_arr = wpbc_get_upgrade_widget( array(
								'id'                 => $id . '_' . 'conditional_days_fordate',
								'dismiss_css_class'  => '.wpbc_dismiss__conditional_days',
								'blured_in_versions' => array( 'free', 'ps', 'bs' ),
								'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'overview/#advanced-days-selection' ),
								'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
								'versions'           => 'Business Medium / Large, MultiUser versions',
								'css'                => 'transform: translate(0) translateY(120px);'
							) );
		echo $upgrade_content_arr['content'];

		//--------------------------------------------------------------------------------------------------------------
		// Conditions for DATE selection
		//--------------------------------------------------------------------------------------------------------------

		?><div class="wpbc_shortcode_config__rules_container wpbc_dismiss__conditional_days  <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>">
			<div class="wpbc_shortcode_config__rules__params_section 		wpbc_select_fordate">

				<div class="wpbc_ajx_toolbar wpbc_no_background wpbc_buttons_row">
					<div class="ui_container ui_container_mini">
						<div class="ui_group">

							<div class="ui_element">
								<div class="wpbc_ui_control" style="line-height: 2;">
									<span><?php _e('For date' ,'booking'); ?></span>
								</div>
							</div>

							<div class="ui_element wpdevelop"><?php
									WPBC_Settings_API::field_text_row_static(   $id . '__date'
																		, array(
																			  'type'              => 'text'
																			, 'placeholder'       => '2024-02-14'
																			, 'class'             => 'wpbc_ui_control wpbc_ui_text'
																			, 'css'               => 'width:9em;border-radius:3px;'
																			, 'only_field'        => true
																			, 'attr'              => array()
																			, 'value'             => ''
																		)
										);
							?></div>

						</div>
					</div>

					<div class="ui_container ui_container_mini">
						<div class="ui_group">

							<div class="ui_element">
								<div class="wpbc_ui_control " style="line-height: 2;"><?php _e('allow to select' ,'booking'); ?></div>
							</div>

							<div class="ui_element"><?php
									WPBC_Settings_API::field_text_row_static(   $id . '__days_number'
																		, array(
																			  'type'              => 'text'
																			, 'placeholder'       => '5 ' . __( 'or', 'booking' ) . ' 1-7 ' . __( 'or', 'booking' ) . ' 1,5,7'
																			, 'class'             => 'wpbc_ui_control wpbc_ui_text'
																			, 'css'               => 'width:9em;border-radius:3px;'
																			, 'only_field'        => true
																			, 'attr'              => array()
																			, 'value'             => ''
																		)
										);
							?></div>

							<div class="ui_element">
								<div class="wpbc_ui_control" style="line-height: 2;"><?php _e('days' ,'booking'); ?></div>
							</div>
						</div>
					</div>

					<div class="ui_container ui_container_mini">
						<div class="ui_group">

							<div class="ui_element">
								<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config__select_day_fordate__add('<?php echo $id; ?>');"
										   class="wpbc_ui_control wpbc_ui_button wpbc_ui_button_primary" ><?php _e('Add Rule','booking'); ?></a>
							</div>
							<div class="ui_element">
								<a href="javascript:void(0)" onclick="javascript:wpbc_shortcode_config__select_day_fordate__reset('<?php echo $id; ?>');"
										   class="wpbc_ui_control wpbc_ui_button wpbc_ui_button_danger" ><?php _e('Reset','booking'); ?></a>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="wpbc_shortcode_config__rules__help_section">
				<div class="wpbc-settings-notice notice-info notice-header">
					<span><?php _e( 'Specify the number of days to select when starting the selection on a specific date.', 'booking' ); ?></span>
				</div>
				<div class="wpbc-settings-notice notice-info notice-list">
					<ol>
						<li>
							<?php _e( "For example, a visitor can choose to book 7 or 14 days or 21 days starting on '2027-02-15'. To configure this, enter date in format 'YYYY-MM-DD' such as '2027-02-15' and set the number of days to select as '7,14,21'. Click the 'Add Rule' button.", 'booking' ); ?>
						</li>
						<li>
							<?php
							echo '<strong>' . __( 'Code Example', 'booking' ) . ': </strong>'
											. '<code>{select-day condition="date" for="2027-02-15" value="7,14,21"}</code>';
							?>
						</li>
						<li>
							<?php
							printf(	__ ('Check the FAQ for details on %sshortcode configuration%s, especially this %soption%s.' , 'booking' )
									, '<a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/" target="_blank">', '</a>'
									, '<strong><a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/#aggregate" target="_blank">', '</a></strong>' );
							?>
						</li>
						<li>
							<?php
							 printf(__('Explore %sJavaScript customization%s for advanced different day selection in different calendars.' ,'booking')
										, '<a href="https://wpbookingcalendar.com/faq/advanced-javascript-for-the-booking-shortcodes/" target="_blank">','</a>');
							?>
						</li>
					</ol>
				</div>
				<textarea id="<?php echo $id; ?>_textarea" name="<?php echo $id; ?>_textarea" style="width:100%;margin:10px 0;"></textarea>
			</div>
		</div><?php

	}

