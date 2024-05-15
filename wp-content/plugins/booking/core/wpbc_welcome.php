<?php
/**
 * @version 1.0
 * @package Booking Calendar
 * @subpackage Welcome Panel Functions
 * @category Functions
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2023-10-24
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly



// <editor-fold     defaultstate="collapsed"                        desc="  ==  Welcome Content  ==  "  >

	/**
		 * Show Welcome Panel with  links
	 *
	 * @global type $wpbc_Dismiss
	 */
	function wpbc_welcome_panel() {

		?>
		<style type="text/css" media="screen">
			/*<![CDATA[*/
			/* WPBC Welcome Panel */
			/* //FixIn: 8.9.3.6 */
			.wpbc-panel .welcome-panel .welcome-panel-column-container {
				display: block;
				margin-top: 0px;
				padding: 0px;
				background: #fff;
			}

			.wpbc-panel .welcome-panel .welcome-panel-column ul {
				margin: 1.8em 1em 1em 0;
			}

			.wpbc-panel .welcome-panel {
				background-blend-mode: overlay;
				font-size: 14px;
				line-height: 1.3;
			}

			.wpbc-panel .welcome-panel-column {
				display: block;
			}

			.wpbc-panel .welcome-panel::before {
				content: none;
			}

			.wpbc-panel .welcome-panel-content {
				min-height: 100px;
			}

			/* End //FixIn: 8.9.3.6 */
			.wpbc-panel .welcome-panel {
				background: #fff;
				border-color: #DFDFDF;
				position: relative;
				overflow: auto;
				margin: 5px 0 20px;
				padding: 23px 10px 12px;
				border-width: 1px;
				border-style: solid;
				border-radius: 3px;
				font-size: 13px;
				line-height: 2.1em;
			}

			.wpbc-panel .welcome-panel h3 {
				margin: 0;
				font-size: 21px;
				font-weight: 400;
				line-height: 1.2;
			}

			.wpbc-panel .welcome-panel h4 {
				margin: 0.8rem 0;
				  font-weight: 600;
				  font-size: 1.1rem;
				  color: #7a7a7a;
			}
			.wpbc-panel .welcome-panel a {
				color: #21759B;
			}
			.wpbc-panel .welcome-panel .about-description {
				margin: -10px 0 5px;
				color: #7a7a7a;
				font-size: 20px;
				font-weight: 600;
				border-bottom: 1px solid #eee;
				padding-bottom: 10px;
				padding-left: 0.5em;
			}

			.wpbc-panel .welcome-panel .button.button-hero {
				margin: 15px 0 3px;
			}

			.wpbc-panel .welcome-panel-content {
				display: flex;
				flex-flow: row wrap;
				justify-content: flex-start;
				align-items: flex-start;
				align-content: flex-start;
			}
			.wpbc-panel .welcome-panel-content > * {
				flex: 1 1 auto;
			}
			.wpbc-panel .welcome-panel .welcome-panel-column-container {
				clear: both;
				overflow: hidden;
				position: relative;

				display: flex;
				flex-flow: row wrap;
				justify-content: space-between;
				align-items: flex-start;
				align-content: flex-start;
				padding: 0 1em;
			}
			.wpbc-panel .welcome-panel .welcome-panel-column:first-child {
			   flex: 1 1 40%;
			}
			.wpbc-panel .welcome-panel .welcome-panel-column {
			   flex: 1 1 30%;
			   min-width: 18em;
			}
			.wpbc-panel .welcome-panel .welcome-panel-column:last-child {
			   flex: 1 1 20%;
			}
				/*.welcome-panel-column.welcome-panel-last h4{*/
				/*	font-size: 1.1em;*/
				/*}*/
				/*.wpbc-panel .welcome-panel .welcome-panel-column.welcome-panel-last .welcome-icon{*/
				/*	font-size: 1em;*/
				/*}*/
				/*.wpbc-panel .welcome-panel .welcome-panel-column.welcome-panel-last li{*/
				/*	padding:0;*/
				/*}*/
			.wpbc-panel .welcome-panel-column p {
				margin-top: 7px;
			}

			.wpbc-panel .welcome-panel .welcome-icon {
				background: #fff;
				padding: 0 1em 0 0;
                font-size: 15px;
                line-height: 1.84em;
			}

			.wpbc-panel .welcome-panel .welcome-add-page {
				background-position: 0 2px;
			}

			.wpbc-panel .welcome-panel .welcome-edit-page {
				background-position: 0 -90px;
			}

			.wpbc-panel .welcome-panel .welcome-learn-more {
				background-position: 0 -136px;
			}

			.wpbc-panel .welcome-panel .welcome-comments {
				background-position: 0 -182px;
			}

			.wpbc-panel .welcome-panel .welcome-view-site {
				background-position: 0 -274px;
			}

			.wpbc-panel .welcome-panel .welcome-widgets-menus {
				line-height: 14px;
                font-size: 0.8rem;
			}

			.wpbc-panel .welcome-panel .welcome-write-blog {
				background-position: 0 -44px;
			}

			.wpbc-panel .welcome-panel .welcome-panel-column ul {
				margin: 0.8em 1em 1em 0;
			}

			.wpbc-panel .welcome-panel .welcome-panel-column li {
				line-height: 1.8em;
				list-style-type: none;
			}

			.wpbc_panel_get_started_dismiss:hover,
			.wpbc_panel_get_started_dismiss:active,
			.wpbc_panel_get_started_dismiss:focus,
			.wpbc_panel_get_started_dismiss {
				position: absolute;
				right: 12px;
				top: 12px;
				box-shadow: none;
				outline: none;
			}

			@media screen and (max-width: 870px) {
				.wpbc-panel .welcome-panel .welcome-panel-column,
				.wpbc-panel .welcome-panel .welcome-panel-column:first-child {
					display: block;
					float: none;
					width: 100%;
				}

				.wpbc-panel .welcome-panel .welcome-panel-column li {
					display: inline-block;
					margin-right: 13px;
				}

				.wpbc-panel .welcome-panel .welcome-panel-column ul {
					margin: 0.4em 0 0;
				}

				.wpbc-panel .welcome-panel .welcome-icon {
					/*padding-left: 25px;*/
				}
			}

			/*]]>*/
		</style>
		<script type="text/javascript">
			jQuery( document ).ready( function (){
				setTimeout( function (){
					if ( jQuery( '#wpbc-panel-get-started' ).is( ':visible' ) ){
						jQuery( '#wpbc-panel-get-started' ).slideUp( 1000 );
					}
				}, 60000 );
			} );
		</script>
		<div id="wpbc-panel-get-started" class="wpbc-panel" style="display:none;">
			<div class="welcome-panel"><?php

				$is_panel_visible = wpbc_is_dismissed( 'wpbc-panel-get-started', array(
														'title' => '<i class="menu_icon icon-1x wpbc_icn_close"></i> ',
														'hint'  => __( 'Dismiss', 'booking' ),
														'class' => 'wpbc_panel_get_started_dismiss',
														'css'   => 'background: #fff;border-radius: 7px;'
													));
				if ( $is_panel_visible ) {
					wpbc_welcome_panel_content();
				}

				?> </div>
		</div> <?php
	}


	/**
		 * Content of Welcome Panel with  links
	 *
	 */
	function wpbc_welcome_panel_content() {

		?>
		<div class="welcome-panel-content">
			<p class="about-description"><?php _e( 'We&#8217;ve assembled some links to get you started:','booking'); ?></p>
			<div class="welcome-panel-column-container">
				<div class="welcome-panel-column">
					<h4><?php _e( 'Get Started','booking'); ?></h4>
					<ul>
						<?php
						$is_wp_post_booking = false;
						$wp_post_booking = get_page_by_path( 'wpbc-booking' );
						if ( empty( ! $wp_post_booking ) ) {

							$wp_post_booking_absolute = get_permalink( $wp_post_booking->ID );

							if ( ! empty( $wp_post_booking_absolute ) ) {

								$wp_post_booking_relative = wpbc_make_link_relative( $wp_post_booking_absolute );
								if ( wpbc_is_shortcode_exist_in_page( $wp_post_booking_relative, '[booking' ) ) {
									$is_wp_post_booking = true;
									?>
									<li>
										<div class="welcome-icon" style="margin: 25px 0 15px;"><?php
											printf( __( 'Start by using the %spre-configured booking page%s we have set up.', 'booking' )
												, '<strong><a href="' . esc_url( $wp_post_booking_absolute ) . '" class="button button-secondary"  target="_blank">', '</a></strong>'
											);
										?></div>
									</li><?php
								}
							}
						}
						?><li>
						<div class="welcome-icon" style="margin-bottom: 15px;"><?php
							printf( __( '%sIntegrate booking form%s into a page on your website in just a few clicks.', 'booking' )
								, '<strong><a href="' . esc_url( wpbc_get_resources_url() )  . '" class="button button-primary">', '</a></strong>'
							);
							?></div>
						</li><?php

						?><li><span class="welcome-icon" style="font-size: 0.95em;"><?php
							printf( __('Learn how to %sAdd the Booking Form or Calendar to your page%s in WordPress Block Editor, Elementor, or other non-standard editors.','booking')
									, '<strong><a href="https://wpbookingcalendar.com/faq/insert-booking-calendar-into-page/" target="_blank">', '</a></strong>'
									);
							echo '</span><span class="welcome-icon" style="font-size: 0.95em;">';
						    printf( __('See %sall shortcodes%s of the Booking Calendar that you can use in pages.','booking')
									, '<strong><a href="https://wpbookingcalendar.com/faq/#shortcodes" target="_blank">', '</a></strong>'
//									, '<a href="' . admin_url( 'edit.php?post_type=page' ) . '">', '</a>'
//									, '<a href="' . admin_url( 'edit.php' ) . '">', '</a>'
									);
						?></span></li>
					</ul>
				</div>
				<div class="welcome-panel-column welcome-panel-last">
					<h4><?php _e( 'Next Steps','booking'); ?></h4>
					<ul>

						<?php if ( ! empty( $wp_post_booking_absolute ) ) { ?>
						<li><div class="welcome-icon"><?php
							printf( __('Start creating %snew bookings%s from %syour page%s or in the %sAdmin Panel%s.','booking')
									, '<strong>', '</strong>'
									, '<strong><a href="' . esc_url( $wp_post_booking_absolute ) . '" target="_blank">', '</a></strong>'
									, '<a href="' . esc_url( wpbc_get_new_booking_url() ) . '">', '</a>'
									);
						?></div></li>
						<?php } ?>

						<li><div class="welcome-icon"><?php
							printf( __( 'Check %sBooking Listing%s page for new bookings.','booking')
										, '<a href="' . esc_url( wpbc_get_bookings_url(true, false) . '&view_mode=vm_listing' ) . '">', '</a>'
									);
						?></div></li>
						<li><div class="welcome-icon"><?php
							printf( __( 'Configure  %sForm Fields%s, %sEmails%s and other %sSettings%s.' ,'booking')
										, '<a href="' . esc_url( wpbc_get_settings_url(true, false) . '&tab=form' ) . '">', '</a>'
										, '<a href="' . esc_url( wpbc_get_settings_url(true, false) . '&tab=email' ) . '">', '</a>'
										, '<a href="' . esc_url( wpbc_get_settings_url(true, false) ) . '">', '</a>'
									);
						?></div></li>
					</ul>
<?php /* ?>
				</div>
				<div class="welcome-panel-column welcome-panel-last">
 <?php */ ?>
					<h4><?php _e( 'Have a questions?','booking'); ?></h4>
					<ul>
						<li><span class="welcome-icon"><?php
							printf( __( 'See %sFAQ%s.' ,'booking'),
								'<a href="https://wpbookingcalendar.com/faq/" target="_blank">',
								'</a>' );
							echo '</span><span class="welcome-icon">';
							printf( __( 'Contact %sSupport%s.','booking'),
								'<a href="https://wpbookingcalendar.com/support/" target="_blank">',
								'</a>' );
/*
						?></div></li>
						<li><div class="welcome-icon"><?php
							printf( __( 'Check out our %sHelp%s' ,'booking'),
								'<a href="https://wpbookingcalendar.com/help/" target="_blank">',
								'</a>' );
						?></div></li>
						<li><div class="welcome-icon"><?php
							printf( __( 'Still having questions? Contact %sSupport%s.','booking'),
								'<a href="https://wpbookingcalendar.com/support/" target="_blank">',
								'</a>' );
*/
						?></span></li>
					</ul>
				</div>
			</div>
			<div class="welcome-icon welcome-widgets-menus" style="text-align:right;"><?php
				printf( __( 'Need even more functionality? Check %s Pro Versions %s.','booking'),
						'<a href="https://wpbookingcalendar.com/overview/" target="_blank">',
						'</a>'
					); ?>
			</div>
		</div>
		<?php
	}

// </editor-fold>
