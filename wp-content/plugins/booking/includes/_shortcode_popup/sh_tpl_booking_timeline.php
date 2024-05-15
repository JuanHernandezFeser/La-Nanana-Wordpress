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
//  Shortcode [bookingtimeline ... ]
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [booking ...]
 *
 * @return void
 */
function wpbc_shortcode_config__content__bookingtimeline() {

	$shortcode_name = 'bookingtimeline';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		wpbc_shortcode_config__bookingtimeline__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general"><?php

				// Select 'Multiple' --  'Booking Resource'
				wpbc_shortcode_config_fields__select_multiple_resources( $shortcode_name . '_wpbc_multiple_resources', $shortcode_name );

			?>
			<table class="form-table"><tbody><?php

				// Select 'View Mode' for Timeline
				wpbc_shortcode_config_fields__view_mode_timeline( $shortcode_name . '_wpbc_view_mode_timeline', $shortcode_name );


			?></tbody></table>
		</div><?php


		// 'Options'  --------------------------------------------------------------------------------------------------
	    ?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__other">
			<table class="form-table"><tbody><?php

				// Label
				wpbc_shortcode_config_fields__text_label_timeline( $shortcode_name . '_wpbc_text_label_timeline', $shortcode_name );

				// Number of months / days to  scroll
				wpbc_shortcode_config_fields__select_days_months_to_scroll( $shortcode_name . '_wpbc_scroll_timeline', $shortcode_name );

				// Select start date
				wpbc_shortcode_config_fields__start_date_timeline( $shortcode_name . '_wpbc_start_date_timeline', $shortcode_name );

				// Start/end time
				wpbc_shortcode_config_fields__start_end_time_timeline( $shortcode_name . '_wpbc_start_end_time_timeline', $shortcode_name );

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
	function wpbc_shortcode_config__bookingtimeline__top_tabs(){

		$shortcode_name = 'bookingtimeline';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							'title'         => __('TimeLine', 'booking')
							, 'text_css' => 'line-height: 18px;vertical-align: text-top;'
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'menu_icon icon-1x wpbc-bi-bar-chart-steps NOwpbc-bi-diagram-3'
							, 'default'     => true
			) );
//			wpbc_bs_display_tab(   array(
//							'title'         => __('View Options', 'booking')
//							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
//							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__options').show();"
//							, 'font_icon'   => 'wpbc_icn_adjust'
//							, 'default'     => false
//			) );
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
	 * Shortcode Fields:  View Mode:
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__view_mode_timeline( $id , $group_key ){

		$view_days_optiopns = array();
		if ( class_exists( 'wpdev_bk_personal' ) ) {
			$view_days_optiopns[ 1 ] = array( 'title' => __('Day', 'booking' ), 'attr' => array( 'class' => 'wpbc_bookingtimeline_matrix' ) );
			$view_days_optiopns[ 7 ] = array( 'title' => __( 'Week', 'booking' ), 'attr' => array( 'class' => 'wpbc_bookingtimeline_matrix' ) );
		}
		$view_days_optiopns[ 30 ] = array( 'title' => __( 'Month', 'booking' ), 'attr' => array( 'class' => 'wpbc_bookingtimeline_single wpbc_bookingtimeline_matrix' ) );
		if ( class_exists( 'wpdev_bk_personal' ) ) {
			$view_days_optiopns[ 60 ] = array( 'title' => __( '2 Months', 'booking' ), 'attr' => array( 'class' => 'wpbc_bookingtimeline_matrix' ) );
		}
		$view_days_optiopns[ 90 ] = array( 'title' => __( '3 Months', 'booking' ), 'attr' => array( 'class' => 'wpbc_bookingtimeline_single' ) );
		$view_days_optiopns[ 365 ] = array( 'title' => __( 'Year', 'booking' ), 'attr' => array( 'class' => 'wpbc_bookingtimeline_single' ) );

		WPBC_Settings_API::field_radio_row_static(   $id . '_months_num_in_row'
													, array(
															  'type'              => 'radio'
															, 'title'             => __('View mode', 'booking')
															, 'description'       => __('Select type of view format.' ,'booking')
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed0 ' . $id . '_wpbc_sc_view_mode'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => 30
															, 'options'           => $view_days_optiopns
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
	function wpbc_shortcode_config_fields__text_label_timeline($id , $group_key ){

		WPBC_Settings_API::field_text_row_static( $id
									, array(
											  'type'              => 'text'
											, 'placeholder'       =>  __( 'Example', 'booking' ). ': ' .__( 'All Bookings', 'booking' )
											, 'title'             => __( 'Label', 'booking' )
											, 'description'       => __( 'Title', 'booking' ) . '  (' . __( 'optional', 'booking' ) . ').'
											, 'description_tag'   => 'span'
											, 'label'             => ''
											, 'class'             => ''
											, 'css'               => 'width0:5em;'
											, 'tr_class'          => ''

											, 'group'             => $group_key
											, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed0'
										    , 'only_field'        => false
											, 'attr'              => array()
											, 'value'             => ''
										)
					);

	}

	/**
	 * Shortcode Field:  Select-box - "Number of days to  scroll"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__select_days_months_to_scroll( $id , $group_key ){

		WPBC_Settings_API::field_select_row_static(   $id. '_scroll_days'
													, array(
															  'type'              => 'select'
															, 'title'             => __('Number of days to scroll', 'booking')
															, 'description'       => __(' Select number of days to scroll after loading' ,'booking')
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section wpbc_bookingtimeline_scroll_day wpbc_sub_settings_grayed0'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => 0
															, 'options'           =>  array_combine( range( -90, 90 ), range( -90, 90 ) )
														)
						);

		WPBC_Settings_API::field_select_row_static(   $id . '_scroll_month'
													, array(
															  'type'              => 'select'
															, 'title'             => __('Number of months to scroll', 'booking')
															, 'description'       => __('Select number of months to scroll after loading' ,'booking')
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section wpbc_bookingtimeline_scroll_month wpbc_sub_settings_grayed0'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => 0
															, 'options'           => array_combine( range( -12, 12 ), range( -12, 12 ) )
														)
						);

	}


	/**
	 * Shortcode Fields:  Start Date: 2024-02-21
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__start_date_timeline( $id , $group_key ){

			////////////////////////////////////////////////////////////////////
			// Start Month
			////////////////////////////////////////////////////////////////////
			?><tr valign="top" class="<?php echo $group_key . '_standard_section'; ?> wpbc_sub_settings_grayed0">
				<th scope="row" style="vertical-align: middle;"><label for="<?php echo $id; ?>_active" class="wpbc-form-text"><?php  _e('Start date:', 'booking'); ?></label></th>
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

					?><span class="description" style="font-weight:600;flex:0;margin: 3px 0.5em 0.5em 0;"> / </span><?php

					WPBC_Settings_API::field_select_row_static(  $id . '_day'
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
																		, 'value'             => date('j')
																		, 'options'           => array_combine( range( 1, 31 ), range( 1, 31 ) )
																	)
									);

					?><span class="description"> <?php _e('Select start date' ,'booking'); ?></span></fieldset></td>
			</tr><?php

	}


	/**
	 * Shortcode Fields:  show times 0 - 23
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__start_end_time_timeline( $id , $group_key ){

			////////////////////////////////////////////////////////////////////
			// Start Month
			////////////////////////////////////////////////////////////////////
			?><tr valign="top" class="<?php echo $group_key . '_standard_section'; ?> wpbc_sub_settings_grayed0 bookingtimeline_view_times">
				<th scope="row" style="vertical-align: middle;"><label for="<?php echo $id; ?>_active" class="wpbc-form-text"><?php  _e('Show:', 'booking'); ?></label></th>
				<td class=""><fieldset><?php

					?><span class="description" style="font-weight:600;flex:0;margin: 3px 0.5em 0.5em 0;"> <?php _e('from','booking') ?> </span><?php

					WPBC_Settings_API::field_select_row_static(  $id . '_starttime'
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
																		, 'value'             => 0
																		, 'options'           => array_combine( range( 0, 23 ), range( 0, 23 ) )
																	)
									);

					?><span class="description" style="font-weight:600;flex:0;margin: 3px 0.5em 0.5em 0;"> <?php _e('to','booking') ?> </span><?php

					WPBC_Settings_API::field_select_row_static(  $id . '_endtime'
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
																		, 'value'             => 24
																		, 'options'           => array_combine( range( 1, 24 ), range( 1, 24 ) )
																	)
									);

					?><span class="description" style="font-weight:600;flex:0;margin: 3px 0.5em 0.5em 0;"> <?php _e('hours','booking') ?> </span><?php

					?></fieldset></td>
			</tr><?php

	}
