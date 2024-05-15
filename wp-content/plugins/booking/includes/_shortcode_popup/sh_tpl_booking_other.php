<?php
/**
 * @version 1.0
 * @package Booking Calendar Shortcodes Config
 * @subpackage Shortcodes Content   - Shortcodes such  as: [bookingedit] , [bookingcustomerlisting] , [bookingresource type=6 show='capacity'] , [booking_confirm]
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
//  Shortcodes such  as: [bookingedit] , [bookingcustomerlisting] , [bookingresource type=6 show='capacity'] , [booking_confirm]
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [bookingsearch]
 *
 * @return void
 */
function wpbc_shortcode_config__content__bookingother() {

	$shortcode_name = 'bookingother';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		wpbc_shortcode_config__bookingother__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general">
			<table class="form-table"><tbody><?php

				// Select 'Search Form'  |  'Search Results'
				wpbc_shortcode_config_fields__select_other_shortcodes( $shortcode_name . '_wpbc_shortcode_type', $shortcode_name );

			?></tbody></table>
			<div class="bookingother_section_additional bookingother_section_booking_confirm"><?php
                    wpbc_show_message_in_settings(
													sprintf( __( 'This shortcode %s is used on a confirmation booking page to display booking details and confirmation after a successful booking.', 'booking' ), '<code>[booking_confirm]</code>', '<strong>', '</strong>', '<strong>', '</strong>', '<strong>', '</strong>' )
										. '<br/>' . sprintf( __( 'The content of field %s on the %sgeneral booking settings page%s at %s section must link to this page.', 'booking' )
														, '<strong>' . __( 'Confirmation Page URL', 'booking ' ) .'</strong>'
														, '<a href="' . wpbc_get_settings_url() . '&scroll_to_section=wpbc_general_settings_booking_confirmation_tab">'
														, '</a>'
														,  '<strong>' .__( 'Booking Confirmation', 'booking' ) . ' > '
														 . __( 'Redirect to thank you page', 'booking' ).'</strong>'
														 )
										, 'info', ''  );
			?></div>
			<div class="bookingother_section_additional bookingother_section_bookingedit"><?php
                    wpbc_show_message_in_settings(
													sprintf( __( 'This shortcode %s is used on a page, where visitors can %smodify%s their own booking(s), %scancel%s or make %spayment%s after receiving an admin email payment request', 'booking' ), '<code>[bookingedit]</code>', '<strong>', '</strong>', '<strong>', '</strong>', '<strong>', '</strong>' )
										. '<br/>' . sprintf( __( 'The content of field %sURL to edit bookings%s on the %sgeneral booking settings page%s must link to this page', 'booking' ), '<i>"', '"</i>', '<a href="' . wpbc_get_settings_url() . '">', '</a>' )
										. '<br/>' . sprintf( __( 'Email templates, which use shortcodes: %s, will be linked to this page', 'booking' ), '<code>[visitorbookingediturl]</code>, <code>[visitorbookingcancelurl]</code>, <code>[visitorbookingpayurl]</code>' )
										, 'info', ''  );
			?></div>
			<div class="bookingother_section_additional bookingother_section_bookingcustomerlisting"><?php
					wpbc_show_message_in_settings(
									sprintf( __( 'This shortcode %s is used on a page, where visitors can %sview listing%s of their own booking(s)', 'booking' ), '<code>[bookingcustomerlisting]</code>', '<strong>', '</strong>', '<strong>', '</strong>', '<strong>', '</strong>' )
						. '<br/>' . sprintf( __( 'The content of field %sURL of page for customer bookings listing%s on the %sgeneral booking settings page%s must link to this page', 'booking' ), '<i>"', '"</i>', '<a href="' . wpbc_get_settings_url() . '">', '</a>' )
						. '<br/>' . sprintf( __( 'Email templates, which use shortcodes: %s, will be linked to this page', 'booking' ), '<code>[visitorbookingslisting]</code>' )
						. '<br/>' . sprintf( __( '%s You can use in this shortcode the same parameters as for %s shortcode', 'booking' ), '<strong>' . __('Trick', 'booking') . '.</strong> ', '<code>[bookingtimeline ... ]</code>' )
						, 'info', ''  );

			?></div>
			<div class="bookingother_section_additional bookingother_section_bookingresource">
				<table class="form-table"><tbody><?php

					// Booking Resource
					wpbc_shortcode_config_fields__select_resource( $shortcode_name . '_wpbc_resource_id', $shortcode_name );


                    // What to  Show
					$bookingresource_show_options = array( 'title' => __( 'Title', 'booking' ) );
					if ( class_exists( 'wpdev_bk_biz_s' ) ) {
						$bookingresource_show_options['cost'] = __( 'Cost', 'booking' );
					}
					if ( class_exists( 'wpdev_bk_biz_l' ) ) {
						$bookingresource_show_options['capacity'] = __( 'Capacity', 'booking' );
					}
                    WPBC_Settings_API::field_select_row_static(   $shortcode_name . '_wpbc_resource_show' // 'wpbc_bookingresource_show'
                                                                , array(
                                                                          'type'              => 'select'
                                                                        , 'title'             => __('Show', 'booking')
                                                                        , 'description'       => __('Select type of info to show.' ,'booking')
                                                                        , 'description_tag'   => 'span'
                                                                        , 'label'             => ''
                                                                        , 'multiple'          => false
                                                                        , 'group'             => $shortcode_name . '_section_bookingresource'
                                                                        , 'tr_class'          => 'wpbc_shortcode_bookingresource wpbc_shortcode_bookingother'
                                                                        , 'class'             => ''
                                                                        , 'css'               => 'margin-right:10px;'
                                                                        , 'only_field'        => false
                                                                        , 'attr'              => array()
                                                                        , 'value'             => 'title'
                                                                        , 'options'           => $bookingresource_show_options
                                                                    )
                                    );

            	?></tbody></table>
			</div>
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
	function wpbc_shortcode_config__bookingother__top_tabs(){

		$shortcode_name = 'booking';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							'title'         => __('Booking Support Shortcodes', 'booking')
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'menu_icon icon-1x wpbc_icn_adjust'
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
	function wpbc_shortcode_config_fields__select_other_shortcodes( $id , $group_key ){

		$optiopns_for_radio = array();
		$optiopns_for_radio['booking_confirm'] = array(
														'title' => __( 'Show booking confirmation message', 'booking' ),
														'attr'  => array( 'id' => 'wpbc_shortcode__bookingresource' )
													);

		if ( class_exists( 'wpdev_bk_personal' ) ) {

			$optiopns_for_radio['bookingedit'] = array(
															'title' => __( ' Manage Booking. Required for booking Edit / Cancel / Payment Request.', 'booking' ),
															'attr'  => array( 'id' => 'wpbc_shortcode__bookingedit' )
														);
			$optiopns_for_radio['bookingcustomerlisting'] = array(
															'title' => __( 'Show listing of customer bookings', 'booking' ),
															'attr'  => array( 'id' => 'wpbc_shortcode__bookingcustomerlisting' )
														);
			$optiopns_for_radio['bookingresource'] = array(
															'title' => __( 'Show info about Booking Resource', 'booking' ),
															'attr'  => array( 'id' => 'wpbc_shortcode__bookingresource' )
														);
		}


		WPBC_Settings_API::field_radio_row_static(   $id
													, array(
															  'type'              => 'radio'
															, 'title'             => __('Select type of shortcode', 'booking')
															, 'description'       => ''//__('Select type of view format.' ,'booking')
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section wpbc_sub_settings_grayed0 ' . $id . '_other_shortcodes'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => 'booking_confirm'
															, 'options'           => $optiopns_for_radio
														)
						);
	}

