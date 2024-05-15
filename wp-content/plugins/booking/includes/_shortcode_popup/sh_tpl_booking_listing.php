<?php
/**
 * @version 1.0
 * @package Booking Calendar Shortcodes Config
 * @subpackage Shortcodes Content   - Shortcodes booking import (for Booking Manager plugin)
 * @category Shortcodes
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2024-03-08
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly			//FixIn: 9.9.0.15



// =====================================================================================================================
//  Shortcodes such  as: [bookinglisting]
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [bookingsearch]
 *
 * @return void
 */
function wpbc_shortcode_config__content__booking_ics_listing() {

	$shortcode_name = 'booking_listing_ics';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		wpbc_shortcode_config__booking_ics_listing__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general">
			<table class="form-table"><tbody><?php

				//URL
				wpbc_shortcode_config_fields__booking_ics_import__url($shortcode_name . '_wpbc_url', $shortcode_name );

				// From
				wpbc_shortcode_config_fields__booking_ics_import__from_until( $shortcode_name . '_from', $shortcode_name );

				// Until
				wpbc_shortcode_config_fields__booking_ics_import__from_until( $shortcode_name . '_until', $shortcode_name
																		, array(
																				'options' => array(
																									'now'			=> __( 'Now', 'booking' )
																								  , 'today'			=> __( '00:00 Today', 'booking' )
																								  , 'week'			=> __( 'End of current week', 'booking' )
																								  , 'month-start'	=> __( 'Start of current month', 'booking' )
																								  , 'month-end'		=> __( 'End of current month', 'booking' )
																								  , 'year-end'		=> __( 'End of current year', 'booking' )
																								  , 'any'			=> __( 'The end of time', 'booking' )
																								  , 'date'			=> __( 'Specific date / time', 'booking' )
																								)
																				, 'value' => 'any'
																				, 'title' =>  __('Until', 'booking')
																			)
																	);

			?></tbody></table>
		</div><?php

		// 'Advanced' -----------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__other"><?php
			?><table class="form-table"><tbody><?php

				wpbc_shortcode_config_fields__booking_ics_import__conditions_events( $shortcode_name . '_conditions_events', $shortcode_name );

				wpbc_shortcode_config_fields__booking_ics_import__conditions_max_num( $shortcode_name . '_conditions_max_num', $shortcode_name );

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
	function wpbc_shortcode_config__booking_ics_listing__top_tabs(){

		$shortcode_name = 'booking_listing_ics';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							'title'         => __('Listing Events (.ics feed)', 'booking')
							, 'text_css' => 'line-height: 18px;vertical-align: text-top;'
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'menu_icon icon-1x wpbc-bi-list'
							, 'default'     => true
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
