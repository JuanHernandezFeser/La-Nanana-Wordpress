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
//  Shortcode [bookingcalendar ... ]
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [booking ...]
 *
 * @return void
 */
function wpbc_shortcode_config__content__bookingcalendar() {

	$shortcode_name = 'bookingcalendar';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		wpbc_shortcode_config__bookingcalendar__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general">
			<table class="form-table"><tbody><?php

				// Booking Resource
				wpbc_shortcode_config_fields__select_resource( $shortcode_name . '_wpbc_resource_id', $shortcode_name );

				// Number of Months
				wpbc_shortcode_config_fields__select_nummonths( $shortcode_name . '_wpbc_nummonths', $shortcode_name );

				// Calendar Size
				wpbc_shortcode_config_fields__calendar_size( $shortcode_name . '_wpbc_size', $shortcode_name );

			?></tbody></table>
		</div><?php

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
	function wpbc_shortcode_config__bookingcalendar__top_tabs(){

		$shortcode_name = 'bookingcalendar';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							'title'         => __('Availability Calendar', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'wpbc-bi-calendar'
							, 'default'     => true
			) );
			wpbc_bs_display_tab(   array(
							'title'         => __('Aggregate', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__aggregate').show();"
							, 'font_icon'   => 'wpbc-bi-calendar-plus'
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

