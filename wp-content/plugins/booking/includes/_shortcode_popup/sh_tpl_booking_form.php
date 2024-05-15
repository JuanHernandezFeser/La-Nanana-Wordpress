<?php
/**
 * @version 1.0
 * @package Booking Calendar Shortcodes Config
 * @subpackage Shortcodes Content   - 'Booking Form':       [bookingform type=1 selected_dates='03.03.2024']
 * @category Shortcodes
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2024-03-03
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly			//FixIn: 9.9.0.15



// =====================================================================================================================
//  Shortcode [booking ... ]
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [booking ...]
 *
 * @return void
 */
function wpbc_shortcode_config__content__bookingform() {

	$shortcode_name = 'bookingform';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		wpbc_shortcode_config__bookingform__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general"><?php

			//--------------------------------------------------------------------------------------------------------------
			// 'Upgrade to Pro' widget
			//--------------------------------------------------------------------------------------------------------------
			$upgrade_content_arr = wpbc_get_upgrade_widget( array(
									'id'                 => 'widget_wpbc_dismiss__booking_form_only',
									'dismiss_css_class'  => '.wpbc_dismiss__booking_form_only',
									'blured_in_versions' => array( 'free', 'ps', 'bs', 'bm' ),
									'feature_link'       => array( 'title' => 'feature', 'relative_url' => 'faq/shortcode-booking-form-no-calendar/' ),
									'upgrade_link'       => array( 'title' => 'Upgrade to Pro', 'relative_url' => 'features/#bk_news_section' ),
									'versions'           => 'Business Large, MultiUser versions',
									'css'                => 'transform: translate(0) translateY(120px);'
								) );
			echo $upgrade_content_arr['content'];

			//--------------------------------------------------------------------------------------------------------------
			// Real Content
			//--------------------------------------------------------------------------------------------------------------

			?><table class="form-table wpbc_dismiss__booking_form_only <?php echo esc_attr( $upgrade_content_arr['maybe_blur_css_class'] ); ?>"><tbody><?php

				// Booking Resource
				wpbc_shortcode_config_fields__select_resource( $shortcode_name . '_wpbc_resource_id', $shortcode_name );

				// Custom form
				wpbc_shortcode_config_fields__select_custom_form( $shortcode_name . '_wpbc_custom_form', $shortcode_name );

				// Date
				wpbc_shortcode_config_fields__booking_date_bookingform( $shortcode_name . '_wpbc_booking_date', $shortcode_name );

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
	function wpbc_shortcode_config__bookingform__top_tabs(){

		$shortcode_name = 'booking';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							  'title'         => __('Only form without calendar', 'booking')
							, 'text_css' => 'line-height: 18px;vertical-align: text-top;'
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'menu_icon icon-1x menu_icon icon-1x wpbc-bi-card-checklist'
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
	 * Shortcode Fields:  Start Date: 2024-02-21
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__booking_date_bookingform( $id , $group_key ){

			////////////////////////////////////////////////////////////////////
			// Start Month
			////////////////////////////////////////////////////////////////////
			?><tr valign="top" class="<?php echo $group_key . '_standard_section'; ?> wpbc_sub_settings_grayed0">
				<th scope="row" style="vertical-align: middle;"><label for="<?php echo $id; ?>_active" class="wpbc-form-text"><?php  _e('Booking Date', 'booking'); ?>:</label></th>
				<td class=""><fieldset><?php

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

					?><span class="description"> <?php _e('Select date, which you want to use for users to submit the bookings' ,'booking'); ?></span></fieldset></td>
			</tr><?php

	}

