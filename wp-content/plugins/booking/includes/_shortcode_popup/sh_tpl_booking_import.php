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
//  Shortcodes such  as: [bookingimport]
// =====================================================================================================================

/**
 * Content of PopUp - for shortcode [bookingsearch]
 *
 * @return void
 */
function wpbc_shortcode_config__content__booking_ics_import() {

	$shortcode_name = 'booking_import_ics';

	?><div id="wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>" class="wpbc_sc_container__shortcode wpbc_sc_container__shortcode_<?php echo $shortcode_name; ?>"><?php

		wpbc_shortcode_config__booking_ics_import__top_tabs();

		// 'General'  --------------------------------------------------------------------------------------------------
		?><div class="wpbc_sc_container__shortcode_section wpbc_sc_container__shortcode_section__general">
			<table class="form-table"><tbody><?php

				//URL
				wpbc_shortcode_config_fields__booking_ics_import__url($shortcode_name . '_wpbc_url', $shortcode_name );

				// Booking Resource
				wpbc_shortcode_config_fields__select_resource( $shortcode_name . '_wpbc_resource_id', $shortcode_name );

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

				wpbc_shortcode_config_fields__booking_ics_import__conditions_import( $shortcode_name . '_conditions_import', $shortcode_name );

				wpbc_shortcode_config_fields__booking_ics_import__conditions_events( $shortcode_name . '_conditions_events', $shortcode_name );

				wpbc_shortcode_config_fields__booking_ics_import__conditions_max_num( $shortcode_name . '_conditions_max_num', $shortcode_name );

				wpbc_shortcode_config_fields__booking_ics_import__silence( $shortcode_name . '_silence', $shortcode_name );
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
	function wpbc_shortcode_config__booking_ics_import__top_tabs(){

		$shortcode_name = 'booking_import_ics';

		wpbc_bs_toolbar_tabs_html_container_start();

			$js = "jQuery(this).parents( '.wpbc_sc_container__shortcode' ).find( '.nav-tab' ).removeClass('nav-tab-active');"
				. "jQuery(this).addClass('nav-tab-active');"
				. "jQuery('.nav-tab i.icon-white').removeClass('icon-white');"
				. "jQuery('.nav-tab-active i').addClass('icon-white');"
				. "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section').hide();";

			wpbc_bs_display_tab(   array(
							'title'         => __('Import Events (.ics feed)', 'booking')
							, 'text_css' => 'line-height: 18px;vertical-align: text-top;'
							// , 'hint' => array( 'title' => __('Manage bookings' ,'booking') , 'position' => 'top' )
							, 'onclick'     =>  $js . "jQuery('.wpbc_sc_container__shortcode_" . $shortcode_name . " .wpbc_sc_container__shortcode_section__general').show();"
							, 'font_icon'   => 'menu_icon icon-1x wpbc-bi-box-arrow-in-down-right'
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

	/**
	 * Shortcode Field:  Header in search results: '{searchresults} Result(s) Found'
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__booking_ics_import__url ($id , $group_key ){

		WPBC_Settings_API::field_text_row_static( $id
									, array(
											  'type'              => 'text'
											, 'placeholder'       =>  __( 'Example', 'booking' ). ':   ' . 'https://calendar.google.com/calendar/ical/CALENDAR_ID/public/basic.ics'
											, 'title'             => __( 'URL to .ICS Feed', 'booking' )  //URL to .ICS Feed
											, 'description'       => sprintf( __( 'Enter the url of .ics feed or file.', 'booking' ) , '<code>{searchresults}</code>' )
											, 'description_tag'   => 'span'
											, 'label'             => ''
											, 'class'             => ''
											, 'css'               => 'width:99%;'
											, 'group'             => $group_key
											, 'tr_class'          => $group_key . '_standard_section '
											, 'only_field'        => false
											, 'attr'              => array()
											, 'value'             => ''
										)
					);

	}

	/**
	 * Shortcode Fields:  "From" | "Until"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__booking_ics_import__from_until( $id , $group_key ,  $options = array() ){

		$defaults = array(
							'options' => array(
													'now'			=> __( 'Now', 'booking' )
												  , 'today'			=> __( '00:00 Today', 'booking' )
												  , 'week'			=> __( 'Start of current week', 'booking' )
												  , 'month-start'	=> __( 'Start of current month', 'booking' )
												  , 'month-end'		=> __( 'End of current month', 'booking' )
												  , 'year-start'	=> __( 'Start of current year', 'booking' )
												  , 'any'			=> __( 'The start of time', 'booking' )
												  , 'date'			=> __( 'Specific date / time', 'booking' )
											)
							, 'value' => 'today'
							, 'title' =>  __('From', 'booking')
					);
		$options = wp_parse_args( $options, $defaults );

			?><tr valign="top"  class="<?php echo $group_key . '_standard_section'; ?> wpbc_sub_settings_grayed0 <?php echo $id; ?>_wpbc_sc__from_to">
				<th scope="row" style="vertical-align: middle;">
					<label for="<?php echo $id . ''; ?>" class=""><?php  echo $options['title']; ?></label>
				</th>
				<td class=""><fieldset><?php

					WPBC_Settings_API::field_select_row_static(  $id
																, array(
																		  'type'              => 'select'
																		, 'title'             => $options['title']
																		, 'description'       => ''
																		, 'description_tag'   => 'span'
																		, 'label'             => ''
																		, 'multiple'          => false
																		, 'group'             => $group_key
																		, 'tr_class'          => $group_key . '_standard_section '
																		, 'class'             => ''
																		, 'css'               => 'margin-right:10px;'
																		, 'only_field'        => true
																		, 'attr'              => array()
																		, 'value'             => $options['value']
																		, 'options'           => $options['options']
																	)
									);

					?><label for="<?php echo $id . '_offset'; ?>" 	class="<?php echo $id . '_offset_label'; ?>" 	style="font-weight:400;padding: 0 8px 0 12px;"><?php
							echo __( 'Offset', 'booking' );
					?></label>
					<label for="<?php echo $id . '_offset'; ?>" 	class="<?php echo $id . '_date_label'; ?>" 	style="font-weight:400;padding: 0 8px 0 12px;display:none;"><?php
							echo __( 'Date', 'booking' );
					?></label><?php

					WPBC_Settings_API::field_text_row_static(  $id . '_offset'
																	, array(
																		  'type'              => 'text'
																		, 'title'             => __('Offset', 'booking' )
																		, 'placeholder'       => ''
																		, 'description'       => ''
																		, 'description_tag'   => 'span'
																		, 'group'             => $group_key
																		, 'tr_class'          => $group_key . '_standard_section '
																		, 'class'             => ''
																		, 'css'               => 'width:7em;'
																		, 'only_field'        => true
																		, 'attr'              => array()
																		, 'value'             => ''
																	)
									);
					WPBC_Settings_API::field_select_row_static(  $id . '_offset_type'
																	, array(
																		  'type'              => 'select'
																		, 'title'             => ''
																		, 'description'       => ''
																		, 'description_tag'   => 'span'
																		, 'label'             => ''
																		, 'multiple'          => false
																		, 'group'             => $group_key
																		, 'tr_class'          => $group_key . '_standard_section '
																		, 'class'             => $id . '_offset_label'
																		, 'css'               => 'width:6em;'
																		, 'only_field'        => true
																		, 'attr'              => array()
																		, 'value'             => 'day',
																		  'options' => array(
																								  'day'    => __( 'days', 'booking' ),
																								  'hour'   => __( 'hours', 'booking' ),
																								  'minute' => __( 'minutes', 'booking' ),
																								  'second' => __( 'seconds', 'booking' )
																							  )
																	)
									);
					?><span class="description <?php echo $id . '_offset_label'; ?>" style="flex0:100%;"> <?php
							_e( 'You can specify an optional offset from you chosen start point. The offset can be negative.', 'booking' );
					?></span>
					<span class="description <?php echo $id . '_date_label'; ?>" style="flex0:100%;display:none;">
						<em><?php printf( __( 'Type your date in format %s. Example: %s', 'booking' ), 'Y-m-d', date( 'Y-m-d', strtotime( '+7 days' ) ) ); ?></em>
					</span><?php

					// Show or hide some UI elements,  depend on from  selection  of "From" options.
					?><script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery( '#<?php echo $id; ?>' ).on( 'change', {'id': '<?php echo $id; ?>'}, function ( event ){
								/* console.log( ':->: on change wpbc_set_shortcode', event.data.id , event ); */
								wpbc_shortcode_config_fields__booking_ics_import__from_until__support( '<?php echo $id; ?>' );
							} );
							wpbc_shortcode_config_fields__booking_ics_import__from_until__support( '<?php echo $id; ?>' );
						});
						function wpbc_shortcode_config_fields__booking_ics_import__from_until__support( id ){
							jQuery( '#' + id + '_offset' ).show();
							if ( 'date' === jQuery( '#' + id  ).val() ){
								jQuery( '.' + id + '_offset_label' ).hide();
								jQuery( '.' + id + '_date_label' ).show();
							} else {
								jQuery( '.' + id + '_offset_label' ).show();
								jQuery( '.' + id + '_date_label' ).hide();
							}
							if ( 'any' === jQuery( '#' + id ).val() ){
								jQuery( '.' + id + '_offset_label' ).hide();
								jQuery( '.' + id + '_date_label' ).hide();
								jQuery( '#' + id + '_offset' ).hide();
							}
						}
					</script><?php
				?></fieldset></td>
			</tr><?php
	}

	/**
	 * Shortcode Field:  Select-box - "Import Conditions"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__booking_ics_import__conditions_import( $id , $group_key ){

		WPBC_Settings_API::field_select_row_static(   $id
													, array(
															  'type'              => 'select'
															, 'title'             => __( 'Import condition', 'booking' )
															, 'description'       => __( 'Whether import events for dates, that already booked in specific booking resource', 'booking' )
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
															, 'options'           => array(
																							'' => __( 'Import in any case', 'booking-manager' )
																						  , 'if_dates_free' => __( 'Import only, if days are available', 'booking-manager' )
																						)
														)
						);
	}

	/**
	 * Shortcode Field:  Select-box - "Import Conditions"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__booking_ics_import__conditions_events( $id , $group_key ){

		WPBC_Settings_API::field_select_row_static(   $id
													, array(
															  'type'              => 'select'
															, 'title'             => __( 'Event condition', 'booking' )
															, 'description'       => __( 'Import booking, if all dates or only some date(s) within conditional parameters from / until', 'booking' )
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => '1'
															, 'options'           => array(
																							'0' => __( 'Some date(s) in conditional interval', 'booking-manager' )
																						  , '1' => __( 'Strict. All dates in conditional interval', 'booking-manager' )
																						)
														)
						);
	}

	/**
	 * Shortcode Field:  Select-box - "Max Number"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__booking_ics_import__conditions_max_num( $id , $group_key ){

		WPBC_Settings_API::field_select_row_static(   $id
													, array(
															  'type'              => 'select'
															, 'title'             => __( 'Maximum number', 'booking' )
															, 'description'       => __( 'You can specify the maximum number of events.', 'booking' )
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => 0
															, 'options'           => array_combine(
																									  array_merge( array( '0' ), range( 500, 10 , 10 ) )
																									, array_merge( array( '&nbsp;-&nbsp;' ),   range( 500, 10 , 10 ) )
																						)
														)
						);
	}

	/**
	 * Shortcode Field:  Select-box - "silence"
	 *
	 * @param $id
	 * @param $group_key
	 *
	 * @return void
	 */
	function wpbc_shortcode_config_fields__booking_ics_import__silence( $id , $group_key ){

		WPBC_Settings_API::field_select_row_static(   $id
													, array(
															  'type'              => 'select'
															, 'title'             => __( 'Display Import Status', 'booking' )
															, 'description'       => ''//__( 'You can specify the maximum number of events.', 'booking' )
															, 'description_tag'   => 'span'
															, 'label'             => ''
															, 'multiple'          => false
															, 'group'             => $group_key
															, 'tr_class'          => $group_key . '_standard_section'
															, 'class'             => ''
															, 'css'               => 'margin-right:10px;'
															, 'only_field'        => false
															, 'attr'              => array()
															, 'value'             => 0
															, 'options'           => array(
																							  ''  => __( 'Show the number of imported bookings', 'booking' )
																							, '1' => __( 'Do not display any messages regarding the import', 'booking' )
																						)
														)
						);
	}
