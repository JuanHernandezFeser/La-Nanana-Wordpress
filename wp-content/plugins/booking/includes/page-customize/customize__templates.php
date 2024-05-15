<?php /**
 * @version 1.0
 * @description  Templates for customize plugin pages
 * @category  Customize_Plugin
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2023-06-23
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


class WPBC_AJX__Customize_Plugin__Templates {

	// <editor-fold     defaultstate="collapsed"                        desc=" ///  JS | CSS files | Tpl loading  /// "  >

	/**
	 * Define HOOKs for loading CSS and  JavaScript files
	 */
	public function init_load_css_js_tpl() {

		// Load only  at  specific  Page
		if  ( strpos( $_SERVER['REQUEST_URI'], 'page=wpbc-customize_plugin' ) !== false ) {

			add_action( 'wpbc_enqueue_js_files',  array( $this, 'js_load_files' ),     50 );
			add_action( 'wpbc_enqueue_css_files', array( $this, 'enqueue_css_files' ), 50 );

			add_action( 'wpbc_hook_settings_page_footer', array( $this, 'hook__load_templates_at_footer' ) );
		}
	}


	/** JS */
	public function js_load_files( $where_to_load ) {

		$in_footer = true;

		if ( wpbc_is_customize_plugin_page() )
		if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

			wp_enqueue_script(    'wpbc-ajx_inline_calendar'
								, wpbc_plugin_url( '/includes/_inline_calendar_js_css/_out/wpbc_inline_calendar.js' )
								, array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

			wp_enqueue_script(    'wpbc-ajx_customize_plugin_page'
								, trailingslashit( plugins_url( '', __FILE__ ) ) . '_out/customize_plugin_page.js'         /* wpbc_plugin_url( '/_out/js/codemirror.js' ) */
								, array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

			wp_enqueue_script( 'wpbc-general_ui_js_css'
				, wpbc_plugin_url( '/includes/_general_ui_js_css/_out/wpbc_main_ui_funcs.js' )
				, array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

			wp_enqueue_script( 'wpbc_all',         wpbc_plugin_url( '/_dist/all/_out/wpbc_all.js' ),                 	array( 'wpbc-datepick' ), WP_BK_VERSION_NUM );      //FixIn: 9.8.6.1
		//	wp_enqueue_script( 'wpbc_balancer',    wpbc_plugin_url( '/includes/_load_balancer/_out/wpbc_balancer.js' ), array( 'wpbc_calendar' ), WP_BK_VERSION_NUM );      //FixIn: 9.8.3.1
		//	wp_enqueue_script( 'wpbc_calendar',    wpbc_plugin_url( '/includes/_wpbc_calendar/_out/wpbc_calendar.js' ), array( 'wpbc-datepick' ), WP_BK_VERSION_NUM );      //FixIn: 9.8.0.3
			wp_enqueue_script( 'wpbc-main-client', wpbc_plugin_url( '/js/client.js' ),     array( 'wpbc-datepick' ),    WP_BK_VERSION_NUM );
			wp_enqueue_script( 'wpbc-times',       wpbc_plugin_url( '/js/wpbc_times.js' ), array( 'wpbc-main-client' ), WP_BK_VERSION_NUM );
			/**
			 *
			 * wp_localize_script( 'wpbc-global-vars', 'wpbc_live_request_obj'
			 * , array(
			 * 'ajx_booking'  => '',
			 * 'reminders' => ''
			 * )
			 * );
			 */
		}
	}


	/** CSS */
	public function enqueue_css_files( $where_to_load ) {

		if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

			wp_enqueue_style( 'wpbc-ajx_customize_plugin_page'
							, trailingslashit( plugins_url( '', __FILE__ ) ) . '_out/customize_plugin_page.css'          //, wpbc_plugin_url( '/includes/listing_ajx_booking/o-ajx_booking-listing.css' )
							, array(), WP_BK_VERSION_NUM );
		}
	}

// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc=" ///  Templates  /// "  >


		/**
		 * Load Templates at footer of page
		 *
		 * @param $page string
		 */
		public function hook__load_templates_at_footer( $page ){

			// page=wpbc&view_mode=vm_booking_listing
			if ( 'wpbc-ajx_booking_customize_plugin'  === $page ) {		// from >>	do_action( 'wpbc_hook_settings_page_footer', 'wpbc-ajx_booking_customize_plugin' ); as customize_page.php in bottom  of content method

				$this->template__main_page_content();

					$this->template__inline_calendar();

				$this->template__toolbar();
					$this->template__toolbar__select_booking_resource();
					$this->template__toolbar__horizontal_text_bar();
					$this->template__toolbar__buttons_prior_next();

				$this->template__status_bar__footer();

				$this->template__widget__change_calendar_skin();
				$this->template__widget__calendar_size();
				$this->template__widget__plugin_shortcode();

				$this->template__widget__calendar_dates_selection();
				$this->template__widget__calendar_weekdays_availability();
				$this->template__widget__calendar_additional();
			}
		}


		// Templates ===================================================================================================

		/**
		 * Template - Main
		 *
		 * 	Help Tips:
		 *
		 *		<script type="text/html" id="tmpl-template_name_a">
		 * 			Escaped:  	 {{data.test_key}}
		 * 			HTML:  		{{{data.test_key}}}
		 * 			JS: 	  	<# if (true) { alert( 1 ); } #>
		 * 		</script>
		 *
		 * 		var template__var = wp.template( 'template_name_a' );
		 *
		 * 		jQuery( '.content' ).html( template__var( { 'test_key' => '<strong>Data</strong>' } ) );
		 *
		 * @return void
		 */
		private function template__main_page_content() {
			?><script type="text/html" id="tmpl-wpbc_ajx_customize_plugin_main_page_content">
				<div class="wpbc_ajx_cstm__container">
					<div class="wpbc_ajx_cstm__section_left">
					</div>
					<div class="wpbc_ajx_cstm__section_right">
						<div class="wpbc_widgets"></div>
					</div>
				</div><#
						var template__customize_plugin__status_bar__footer = wp.template( 'wpbc_ajx_customize_plugin__status_bar__footer' );
				#>{{{

						template__customize_plugin__status_bar__footer( {
													'ajx_data'              : data.ajx_data,
													'ajx_search_params'     : data.ajx_search_params,
													'ajx_cleaned_params'    : data.ajx_cleaned_params
						} )
				}}}
			</script><?php
		}

			/**
			 * Template - Inline Calendar
			 *
			 * 	Help Tips:
			 *
			 *		<script type="text/html" id="tmpl-template_name_a">
			 * 			Escaped:  	 {{data.test_key}}
			 * 			HTML:  		{{{data.test_key}}}
			 * 			JS: 	  	<# if (true) { alert( 1 ); } #>
			 * 		</script>
			 *
			 * 		var template__var = wp.template( 'template_name_a' );
			 *
			 * 		jQuery( '.content' ).html( template__var( { 'test_key' => '<strong>Data</strong>' } ) );
			 *
			 * @return void
			 */
			private function template__inline_calendar(){
				?><script type="text/html" id="tmpl-wpbc_ajx_customize_plugin__inline_calendar">

						<div class="wpbc_ajx_cstm__calendar <# if ('On' == data.ajx_data.calendar_settings.booking_change_over_days_triangles ){ #>wpbc_change_over_triangle<# } #>"><?php
							_e( 'Calendar is loading...', 'booking' );
						?></div><?php

						echo '<div style="margin-top:15px;">'
							 . wpbc_replace_shortcodes_in_booking_form__legend_items(
									'[legend_items '
													. ' items="unavailable,available,pending,approved,partially"'
													. ' titles="'
																//.' unavailable={' . htmlspecialchars(  __( "Unavailable", 'booking' ), ENT_QUOTES ) . '}'
																//.' pending={' . htmlspecialchars(  __( "Pending", 'booking' ), ENT_QUOTES ) . '}'
															 .'"'
													. ' text_for_day_cell="' . date( 'd' ) . '"'
													. ' unavailable_day_cell_tag="span"'
									.']'
							)
						. '</div>';

				?></script><?php
			}

		// Top  ========================================================================================================

		/**
		 * Tpl - top Toolbar
		 *
		 * 	Help Tips:
		 *
		 *		<script type="text/html" id="tmpl-template_name_a">
		 * 			Escaped:  	 {{data.test_key}}
		 * 			HTML:  		{{{data.test_key}}}
		 * 			JS: 	  	<# if (true) { alert( 1 ); } #>
		 * 		</script>
		 *
		 * 		var template__var = wp.template( 'template_name_a' );
		 *
		 * 		jQuery( '.content' ).html( template__var( { 'test_key' => '<strong>Data</strong>' } ) );
		 *
		 * @return void
		 */
		private function template__toolbar() {
			?><script type="text/html" id="tmpl-wpbc_ajx_customize_plugin_toolbar_page_content"><?php

				?><div class="ui_container    ui_container_toolbar		ui_container_mini    ui_container_calendar_skin    ui_container_filter_row_1" ><?php

					// Here will be composed template with  real HTML
					?><div class="ui_group"  id="wpbc_hidden_template__select_booking_resource" ><?php  					//	array( 'class' => 'group_nowrap' )	// Elements at Several or One Line
						// Resource select-box here. 																		Defined as template at: 	private function template_toolbar_select_booking_resource(){
					?></div><?php

					?><#
							var toolbar__horizontal_text_bar = wp.template( 'wpbc_ajx_customize_plugin__toolbar__horizontal_text_bar' );
					#><?php
					?><div class="ui_group" id="wpbc_toolbar_dates_hint"><div class="ui_element">{{{toolbar__horizontal_text_bar( data.ajx_data  )}}}</div></div><?php	  //	array( 'class' => 'group_nowrap' )	// Elements at Several or One Line


					?><div class="ui_group"><?php
					?><#
							var toolbar__buttons_prior_next = wp.template( 'wpbc_ajx_customize_plugin__toolbar__buttons_prior_next' );
					#>{{{toolbar__buttons_prior_next( data.ajx_data  )}}}<?php

/* ?>
						?><div class="ui_element"><?php
							wpbc_ajx_cstm__ui__button_step_prior( array(
																	  'do_action' 			  => 'save_calendar_skin',
																	  'current_step' 		  =>          'calendar_skin',
																	  'ui_clicked_element_id' => 'btn_' . 'calendar_skin'
																) );

						?></div><?php

						?><div class="ui_element"><?php
							wpbc_ajx_cstm__ui__button_step_next( array(
																	  'do_action' 			  => 'save_calendar_skin',
																	  'current_step' 		  =>          'form_structure',
																	  'ui_clicked_element_id' => 'btn_' . 'form_structure'
																) );
						?></div><?php
<?php */
					?></div><?php

				?></div><?php

			?></script><?php
		}

			/**
			 * Tpl - sub Steps Bar
			 * @return void
			 */
			private function template__toolbar__horizontal_text_bar(){
				?><script type="text/html" id="tmpl-wpbc_ajx_customize_plugin__toolbar__horizontal_text_bar"><?php
								 //wpbc_ui_control wpbc_ui_addon wpbc_text_bar 		      // wpbc_option_step wpbc_passed_step
					?><#
						var item_num       = 0;
						var total_num      = _.size( data['customize_steps']['steps_arr'] );
						var is_passed_step = 'wpbc_passed_step';

						_.each( data['customize_steps']['steps_arr'], function ( p_val_arr, p_key_id, p_data ) {

							if ( p_key_id == data['customize_steps']['current'] ) {
								is_passed_step = '';
							}
							item_num++;

							#><span	class="wpbc_ui_control wpbc_ui_addon wpbc_text_bar">
								<span class="{{p_val_arr.class}} {{is_passed_step}} <# if ( p_key_id == data['customize_steps']['current'] ){ #>wpbc_selected_step<# } #>">
									<# if ( ( false ) || ( '' !== is_passed_step ) ) { <?php /* if first "false" then show links only for PASSED sub tabs. */ ?> #>
										<a href="javascript:void(0)"
										   onclick="javascript:wpbc_ajx_customize_plugin__send_request_with_params( { 'current_step': '{{p_key_id}}' });"
										> {{item_num}}. {{{p_val_arr.html}}} </a>
									<# } else { #>
									 	{{item_num}}. {{{p_val_arr.html}}}
									<# } #>
							</span></span><#

							if ( item_num < total_num ) {

								#><span class="wpbc_ui_control wpbc_ui_addon wpbc_text_bar">
									<span class="wpbc_option_separator {{is_passed_step}} ">
										&gt;
									</span>
								</span><#

							}
						});
					#><?php

				?></script><?php
			}

			/**
			 * Tpl - "Previous" | "Next" buttons in  sub Steps Bar
			 * @return void
			 */
			private function template__toolbar__buttons_prior_next(){
				?><script type="text/html" id="tmpl-wpbc_ajx_customize_plugin__toolbar__buttons_prior_next"><?php
								 //wpbc_ui_control wpbc_ui_addon wpbc_text_bar 		      // wpbc_option_step wpbc_passed_step
					?>
					<# if ( '' != data.customize_steps.prior ) { #>
					<div class="ui_element">
						<a 	id="btn__toolbar__buttons_prior"
							class="wpbc_ui_control wpbc_ui_button wpbc_ui_button tooltip_top "
							style=""
							href="javascript:void(0)"
							onclick="javascript:wpbc_ajx_customize_plugin__send_request_with_params( {
													'do_action': 'none',
													'current_step': '{{data.customize_steps.prior}}',
													'ui_clicked_element_id': 'btn__toolbar__buttons_prior'
												} );
												wpbc_button_enable_loading_icon( this );
												wpbc_admin_show_message_processing( '' );
									"
						   title="Go to previous step">
								<i class="menu_icon icon-1x wpbc_icn_arrow_back_ios"></i>&nbsp;
								<span><?php _e('Back','booking') ?>&nbsp;&nbsp;</span>
						</a>
					</div>
					<# } #>
					<# if ( '' != data.customize_steps.next ) { #>
					<div class="ui_element">
						<a 	id="btn__toolbar__buttons_next"
						  	class="wpbc_ui_control wpbc_ui_button wpbc_ui_button wpbc_ui_button_primary tooltip_top "
						  	style=""
						  	href="javascript:void(0)"
						  	onclick="javascript:wpbc_ajx_customize_plugin__send_request_with_params( {
						  							'do_action': '{{data.customize_steps.action}}',
						  							'current_step': '{{data.customize_steps.next}}',
						  							'ui_clicked_element_id': 'btn__toolbar__buttons_next'
							  					} );
							  					wpbc_button_enable_loading_icon( this );
											  	wpbc_admin_show_message_processing( '' );
									"
						  	title="Go to next step">
								<span><?php _e('Save and continue','booking') ?>&nbsp;&nbsp;&nbsp;</span>
								<i class="menu_icon icon-1x wpbc_icn_arrow_forward_ios"></i>
						</a>
					</div>
					<# } #>
					<?php
				?></script><?php
			}

		// Support =====================================================================================================

		/**
		 * Tpl - Shortcode widget
		 *
		 * @return void
		 */
		private function template__widget__plugin_shortcode(){

		    ?><script type="text/html" id="tmpl-wpbc_ajx_widget_plugin_shortcode">
				<div class="wpbc_widget wpbc_widget_plugin_shortcode">
					<div class="wpbc_widget_header">
						<span class="wpbc_widget_header_text"><?php _e('Shortcodes', 'booking'); ?></span>
						<a href="/" class="wpbc_widget_header_settings_link"><i class="menu_icon icon-1x wpbc_icn_settings"></i></a>
					</div>
					<div class="wpbc_widget_content wpbc_ajx_toolbar" style="margin:0 0 20px;">
						<div class="ui_container" >
							<div class="ui_group    ui_group__change_plugin_shortcode"><?php

								//	Calendar  Visible Months
								wpbc_ajx_cstm__ui__template__shortcode_booking_form();
							?>
							</div>
						</div>
					</div>
				</div>
			</script><?php
		}


		/**
		 * Tpl - "Booking Resources" selectbox
		 *
		 * 	Help Tips:
		 *
		 *		<script type="text/html" id="tmpl-template_name_a">
		 * 			Escaped:  	 {{data.test_key}}
		 * 			HTML:  		{{{data.test_key}}}
		 * 			JS: 	  	<# if (true) { alert( 1 ); } #>
		 * 		</script>
		 *
		 * 		var template__var = wp.template( 'template_name_a' );
		 *
		 * 		jQuery( '.content' ).html( template__var( { 'test_key' => '<strong>Data</strong>' } ) );
		 *
		 * @return void
		 */
		private function template__toolbar__select_booking_resource(){

			// Template
			?><script type="text/html" id="tmpl-wpbc_ajx_select_booking_resource"><?php

				if ( ! class_exists('wpdev_bk_personal') ) {
					echo '</script>';
					return  false;
				}
				/*
				?><# console.log( ' == TEMPLATE PARAMS "wpbc_ajx_change_booking_resource" == ', data ); #><?php
				*/
				$booking_action = 'select_booking_resource';

				$el_id = 'ui_btn_' . $booking_action;

				if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) {
					echo '</script>';
					return false;
				}


						?><div class="ui_element"><?php

							wpbc_flex_label(
												array(
													  'id' 	  => $el_id
													, 'label' => '<span class="" style="font-weight:600;">' . __( 'Booking resource', 'booking' ) . ':</span>'
												)
										   );

							?><select class="wpbc_ui_control wpbc_ui_select change_booking_resource_selectbox"
									  id="<?php echo $el_id; ?>" name="<?php echo $el_id; ?>"

									  <?php /* ?>onfocus="javascript:console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"<?php /**/ ?>

									  onchange="javascript:wpbc_admin_show_message_processing( '' );wpbc_ajx_customize_plugin__send_request_with_params( {
																											  'resource_id': 	    jQuery( this ).val()
																											, 'dates_customize_plugin': jQuery( '.wpbc_radio__set_days_customize_plugin:checked' ).val()
																										    , 'dates_selection':    ''
																										    , 'do_action': 'change_booking_resource'
																										} );"
							  ><#
								_.each( data.ajx_data.ajx_booking_resources, function ( p_resource, p_resource_id, p_data ){
									#><option value="{{p_resource.booking_type_id}}"
											  <#
												if ( data.ajx_cleaned_params.resource_id == p_resource.booking_type_id ) {
													#> selected="SELECTED" <#
												}
											  #>
											  style="<#
														if( undefined != p_resource.parent ) {
															if( '0' == p_resource.parent ) {
																#>font-weight:600;<#
															} else {
																#>font-size:0.95em;padding-left:20px;<#
															}
														}
													#>"
									><#
										if( undefined != p_resource.parent ) {
											if( '0' != p_resource.parent ) {
												#>&nbsp;&nbsp;&nbsp;<#
											}
										}
									#>{{p_resource.title}}</option><#
								});
							#>
							</select><?php

						?></div>

						<div class="ui_element"><?php

							?><div class="wpbc_ui_separtor" style="margin-left: 8px;"></div><?php

						?></div><?php

			?></script><?php
		}

		/**
		 * Tpl - Footer	 - Skip | Reset buttons
		 *
		 * 	Help Tips:
		 *
		 *		<script type="text/html" id="tmpl-template_name_a">
		 * 			Escaped:  	 {{data.test_key}}
		 * 			HTML:  		{{{data.test_key}}}
		 * 			JS: 	  	<# if (true) { alert( 1 ); } #>
		 * 		</script>
		 *
		 * 		var template__var = wp.template( 'template_name_a' );
		 *
		 * 		jQuery( '.content' ).html( template__var( { 'test_key' => '<strong>Data</strong>' } ) );
		 *
		 * @return void
		 */
		private function template__status_bar__footer() {
			?><script type="text/html" id="tmpl-wpbc_ajx_customize_plugin__status_bar__footer"><?php

				?><div class="wpbc_ajx_cstm__status_bar__footer wpbc_ajx_toolbar" style="margin: 10px 0 0;border-top: 1px solid #ccc;"><?php

					?><div 	class="ui_container    ui_container_toolbar		ui_container_mini    ui_container_calendar_skin    ui_container_filter_row_1"
							style="border: none;background: transparent;"
					><?php

						?><div class="ui_group" style="flex:1 1 auto;"><?php

							?><div class="ui_element"><?php
								wpbc_ajx_cstm__ui__reset_to_default__btn();		//	Reset Button
							?></div><?php


							?><div class="ui_element"><?php
								 wpbc_ajx_cstm__ui__skip_wizard__btn();			//	Skip Wizard button
							?></div><?php

						?></div><?php

							/**
							 * We do not need the sub tabs here
							?><#
									var toolbar__horizontal_text_bar = wp.template( 'wpbc_ajx_customize_plugin__toolbar__horizontal_text_bar' );
							#><?php
							?><div class="ui_group" id="wpbc_toolbar_dates_hint"><div class="ui_element">{{{toolbar__horizontal_text_bar( data.ajx_data  )}}}</div></div><?php	  //	array( 'class' => 'group_nowrap' )	// Elements at Several or One Line
							*/

						?><div class="ui_group"><?php
							?><#
									var toolbar__buttons_prior_next = wp.template( 'wpbc_ajx_customize_plugin__toolbar__buttons_prior_next' );
							#>{{{toolbar__buttons_prior_next( data.ajx_data  )}}}<?php

						?></div><?php

					?></div><?php	// ui_container

				?></div><?php		// wpbc_ajx_toolbar

			?></script><?php
		}

		// Widgets =====================================================================================================

		/**
		 * Tpl - Calendar - Skin
		 *
		 * 	Help Tips:
		 *
		 *		<script type="text/html" id="tmpl-template_name_a">
		 * 			Escaped:  	 {{data.test_key}}
		 * 			HTML:  		{{{data.test_key}}}
		 * 			JS: 	  	<# if (true) { alert( 1 ); } #>
		 * 		</script>
		 *
		 * 		var template__var = wp.template( 'template_name_a' );
		 *
		 * 		jQuery( '.content' ).html( template__var( { 'test_key' => '<strong>Data</strong>' } ) );
		 *
		 * @return void
		 */
		private function template__widget__change_calendar_skin(){

		    ?><script type="text/html" id="tmpl-wpbc_ajx_widget_change_calendar_skin">
				<div class="wpbc_widget wpbc_widget_change_calendar_skin">
					<div class="wpbc_widget_header">
						<span class="wpbc_widget_header_text"><?php _e('Calendar Skin', 'booking'); ?></span>
						<a href="/" class="wpbc_widget_header_settings_link"><i class="menu_icon icon-1x wpbc_icn_settings"></i></a>
					</div>
					<div class="wpbc_widget_content wpbc_ajx_toolbar" style="margin:0 0 20px;">
						<div class="ui_container" >
							<div class="ui_group    ui_group__change_calendar_skin"><?php

								//	Calendar  skin
								?><div class="ui_element ui_nowrap0"><?php
										$booking_action = 'set_calendar_skin';

										$el_id = 'ui_btn_cstm__' . $booking_action ;

										wpbc_flex_label(
															array(
																  'id' 	  => $el_id
																, 'label' => '<span class="" style="font-weight:600;">' . __( 'Select the skin of the booking calendar', 'booking' ) . ':</span>'
															)
													   );
								?></div><?php
								?><div class="ui_element ui_nowrap"><?php
									wpbc_ajx_cstm__ui__calendar_skin_dropdown();
									$is_apply_rotating_icon = false;
									wpbc_ajx_cstm__ui__selectbox_prior_btn( $el_id, $is_apply_rotating_icon );
									wpbc_ajx_cstm__ui__selectbox_next_btn(  $el_id, $is_apply_rotating_icon );
								?></div><?php

								// Set checked specific OPTION depends on last action from  user
								?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>

									jQuery( document ).ready( function (){
										// Set selected option  in dropdown list based on  data. value
										jQuery( '#ui_btn_cstm__set_calendar_skin option[value="<?php echo WPBC_PLUGIN_URL; ?>' + data.ajx_cleaned_params.customize_plugin__booking_skin + '"]' ).prop( 'selected', true );
										wpbc__calendar__change_skin( '<?php echo WPBC_PLUGIN_URL; ?>' + data.ajx_cleaned_params.customize_plugin__booking_skin  );
									} );

								<?php if (0) { ?></script><?php } ?> #><?php


								//	Calendar  Visible Months
								wpbc_ajx_cstm__ui__template__visible_months();
							?>
							</div>
						</div>
					</div>
				</div>
			</script><?php
		}


		/**
		 * Tpl - Calendar - Size
		 * @return void
		 */
		private function template__widget__calendar_size(){

		    ?><script type="text/html" id="tmpl-wpbc_ajx_widget_calendar_size">
				<div class="wpbc_widget wpbc_widget_calendar_size">
					<div class="wpbc_widget_header">
						<span class="wpbc_widget_header_text"><?php _e('Calendar size', 'booking'); ?></span>
						<a href="/" class="wpbc_widget_header_settings_link"><i class="menu_icon icon-1x wpbc_icn_settings"></i></a>
					</div>
					<div class="wpbc_widget_content wpbc_ajx_toolbar" style="margin:0 0 20px;">
						<div class="ui_container" >
							<div class="ui_group    ui_group__change_calendar_size"><?php

								//	Calendar  Visible Months
								wpbc_ajx_cstm__ui__template__visible_months();

								//	Calendar  Months Number in a Row
								wpbc_ajx_cstm__ui__template__months_in_row();

								//	Calendar  - Width
								wpbc_ajx_cstm__ui__template__calendar_width();

								//	Calendar  - Cell Height
								wpbc_ajx_cstm__ui__template__calendar_cell_height();
							?>
							</div>
						</div>
						<div class="ui_container    ui_container_toolbar		ui_container_small">
							<div class="ui_group    ui_group__change_calendar_size" style="flex: 1 1 auto;"><?php

								?><div class="ui_element ui_nowrap0"><?php
									wpbc_ajx_cstm__ui__calendar_size_reset__btn();
								?></div><?php

								?><div class="ui_element ui_nowrap0" style="margin-left:auto;"><?php
									wpbc_ajx_cstm__ui__calendar_size_apply__btn();
								?></div><?php

								?>
							</div>
						</div>
					</div>
				</div>
			</script><?php
		}


		/**
		 * Tpl - Calendar - Dates Selection
		 *
		 * 	Help Tips:
		 *
		 *		<script type="text/html" id="tmpl-template_name_a">
		 * 			Escaped:  	 {{data.test_key}}
		 * 			HTML:  		{{{data.test_key}}}
		 * 			JS: 	  	<# if (true) { alert( 1 ); } #>
		 * 		</script>
		 *
		 * 		var template__var = wp.template( 'template_name_a' );
		 *
		 * 		jQuery( '.content' ).html( template__var( { 'test_key' => '<strong>Data</strong>' } ) );
		 *
		 * @return void
		 */
		private function template__widget__calendar_dates_selection(){

		    ?><script type="text/html" id="tmpl-wpbc_ajx_widget_calendar_dates_selection">
				<div class="wpbc_widget wpbc_widget_calendar_dates_selection">
					<div class="wpbc_widget_header">
						<span class="wpbc_widget_header_text"><?php _e('Dates selection', 'booking'); ?></span>
						<a href="/" class="wpbc_widget_header_settings_link"><i class="menu_icon icon-1x wpbc_icn_settings"></i></a>
					</div>
					<div class="wpbc_widget_content wpbc_ajx_toolbar" style="margin:0 0 20px;">
						<div class="ui_container" >
							<div class="ui_group    ui_group__change_calendar_dates_selection"><?php

								//	Calendar  Visible Months
								wpbc_ajx_cstm__ui__template__calendar_dates_selection();
							?>
							</div>
						</div>


					</div>
				</div>
				<?php



				?>
				<div class="wpbc_widget wpbc_widget_calendar_dates_selection_range">
					<div class="wpbc_widget_header">
						<span class="wpbc_widget_header_text"><?php _e('Range days selection', 'booking'); ?></span>
						<a href="/" class="wpbc_widget_header_settings_link"><i class="menu_icon icon-1x wpbc_icn_settings"></i></a>
					</div>
					<div class="wpbc_widget_content wpbc_ajx_toolbar" style="margin:0 0 20px;">

						<div class="ui_container    ui_container_toolbar		ui_container_small">
							<?php
							$is_blur = ( ! class_exists( 'wpdev_bk_biz_s' ) ) ? 'wpbc_blur' : '';
							if ( ! empty( $is_blur ) ) {
								wpbc_ajx_cstm__ui__upgrade_note( 'biz_s', 'https://wpbookingcalendar.com/overview/#range-days-selection' );
							}
							?>
							<div class="ui_group    ui_group__change_calendar_dates_selection <?php echo $is_blur; ?>">
								<?php

								wpbc_ajx_cstm__ui__template__calendar_dates_selection_range();
								?>
							</div>
						</div>
					</div>
				</div>
			</script><?php
		}


		/**
		 * Tpl - Calendar - Weekdays Availability
		 *
		 * 	Help Tips:
		 *
		 *		<script type="text/html" id="tmpl-template_name_a">
		 * 			Escaped:  	 {{data.test_key}}
		 * 			HTML:  		{{{data.test_key}}}
		 * 			JS: 	  	<# if (true) { alert( 1 ); } #>
		 * 		</script>
		 *
		 * 		var template__var = wp.template( 'template_name_a' );
		 *
		 * 		jQuery( '.content' ).html( template__var( { 'test_key' => '<strong>Data</strong>' } ) );
		 *
		 * @return void
		 */
		private function template__widget__calendar_weekdays_availability(){

		    ?><script type="text/html" id="tmpl-wpbc_ajx_widget_calendar_weekdays_availability">
				<div class="wpbc_widget wpbc_widget_calendar_weekdays_availability">
					<div class="wpbc_widget_header">
						<span class="wpbc_widget_header_text"><?php _e('Unavailable week days', 'booking'); ?></span>
						<a href="/" class="wpbc_widget_header_settings_link"><i class="menu_icon icon-1x wpbc_icn_settings"></i></a>
					</div>
					<div class="wpbc_widget_content wpbc_ajx_toolbar" style="margin:0 0 20px;">
						<div class="ui_container" >
							<div class="ui_group    ui_group__change_calendar_weekdays_availability"><?php

								//	Calendar  unavailable weekdays
								wpbc_ajx_cstm__ui__template__calendar_weekdays_availability();

							?>
							</div>
						</div>
					</div>
				</div>
				<?php

				?>
				<div class="wpbc_widget wpbc_widget_calendar_weekdays_availability_pro">
					<div class="wpbc_widget_header">
						<span class="wpbc_widget_header_text"><?php _e('Advanced availability', 'booking'); ?></span>
						<a href="/" class="wpbc_widget_header_settings_link"><i class="menu_icon icon-1x wpbc_icn_settings"></i></a>
					</div>
					<div class="wpbc_widget_content wpbc_ajx_toolbar" style="margin:0 0 20px;">

						<div class="ui_container    ui_container_toolbar		ui_container_small0">
							<div class="ui_group    ui_group__change_calendar_today_availability">
							<?php
								wpbc_ajx_cstm__ui__template__calendar_unavailable_from_today();
							?>
							</div>
							<?php
									$is_blur = ( ! class_exists( 'wpdev_bk_biz_m' ) ) ? 'wpbc_blur' : '';
									if ( ! empty( $is_blur ) ) {
										?><div class="clear" style="width:101%;height:50px;"></div><?php
										wpbc_ajx_cstm__ui__upgrade_note( 'biz_m', 'https://wpbookingcalendar.com/overview/#availability-from-today' );
									}
							?>
							<div class="ui_group    ui_group__change_calendar_weekdays_availability <?php echo $is_blur; ?>">
							<?php
								wpbc_ajx_cstm__ui__template__calendar_limit_available_from_today();

								wpbc_ajx_cstm__ui__template__calendar_unavailable_before_after_bookings();
							?>
							</div>
						</div>

						<div class="ui_container    ui_container_toolbar		ui_container_small">
							<div class="ui_group    ui_group__change_calendar_size" style="flex: 1 1 auto;"><?php

								?><div class="ui_element ui_nowrap0" style="margin-left: auto;"><?php
									wpbc_ajx_cstm__ui__calendar_weekdays_availability_reset__btn();
								?></div><?php

								?>
							</div>
						</div>

					</div>
				</div>
			</script><?php
		}



		/**
		 * Tpl - Calendar - Size
		 * @return void
		 */
		private function template__widget__calendar_additional(){

		    ?><script type="text/html" id="tmpl-wpbc_ajx_widget_calendar_additional">
				<div class="wpbc_widget wpbc_widget_calendar_size">
					<div class="wpbc_widget_header">
						<span class="wpbc_widget_header_text"><?php _e( 'Additional Settings', 'booking' ); ?></span>
						<a href="/" class="wpbc_widget_header_settings_link"><i class="menu_icon icon-1x wpbc_icn_settings"></i></a>
					</div>
					<div class="wpbc_widget_content wpbc_ajx_toolbar" style="margin:0 0 20px;">
						<div class="ui_container" >
							<div class="ui_group    ui_group__change_calendar_size"><?php

								//	Calendar  months_to_scroll
								wpbc_ajx_cstm__ui__template__months_to_scroll();

								wpbc_ajx_cstm__ui__template__start_day_weeek();
							?>
							</div>
						</div>

					</div>
				</div>
			</script><?php
		}



	// </editor-fold>

}


/**
 * Just for loading CSS and  JavaScript files
 */
if ( true ) {
	$ajx_customize_plugin_loading = new WPBC_AJX__Customize_Plugin__Templates;
	$ajx_customize_plugin_loading->init_load_css_js_tpl();
}



////////////////////////////////////////////////////////////////////////////////
//   T e m p l a t e s    UI
////////////////////////////////////////////////////////////////////////////////

/**
 * Show Upgrade Note with link to  specific feature.
 *
 * @param $version	- 'free' | 'personal' | 'biz_s' | 'biz_m' | 'biz_l' | 'multiuser';			it's from wpbc_get_version_type__and_mu();

 * @param $url		- full URL
 *
 * @return void
 *
 *             Example:		wpbc_ajx_cstm__ui__upgrade_note( 'biz_s', 'https://wpbookingcalendar.com/overview/#range-days-selection' );
 */
function wpbc_ajx_cstm__ui__upgrade_note( $version, $url ){

	$ver_title = $version;    // wpbc_get_plugin_version_type();
	$ver_title = str_replace( '_m', ' Medium', $ver_title );
	$ver_title = str_replace( '_l', ' Large', $ver_title );
	$ver_title = str_replace( '_s', ' Small', $ver_title );
	$ver_title = str_replace( 'biz', 'Business', $ver_title );
	$ver_title = ucwords( $ver_title );

    ?>
	<div class="ui_group    ui_group__upgrade">
		<div class="wpbc_upgrade_note">
			This <a target="_blank" href="<?php echo $url; ?>">feature</a> requires the
			<a target="_blank" href="<?php echo $url; ?>"><?php echo $ver_title; ?></a>
			<?php if ( 'multiuser' !== $version ) { ?>
				or higher versions
			<?php } ?>
		</div>
	</div>
	<?php
}

/**
 * Button -  Skip Wizard
 * @return void
 */
function wpbc_ajx_cstm__ui__skip_wizard__btn(){

	$params  =  array(
					'type'             => 'button' ,
					'title'            => __( 'Skip Wizard', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
					'hint'             => array( 'title' => __( 'Reset selected options to default values', 'booking' ), 'position' => 'top' ),  	// Hint
					'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
					'action'           => "wpbc_ajx_customize_plugin__send_request_with_params( {
																		'do_action': 'make_finish',
																		'ui_clicked_element_id': 'btn__status_bar__skip_wizard'
													} );
										   wpbc_button_enable_loading_icon( this );
										   wpbc_admin_show_message_processing( '' );",																			// JavaScript
					'icon' 			   => array(
												'icon_font' => 'wpbc_icn_rotate_left', //'wpbc_icn_rotate_left',  wpbc_icn_close
												'position'  => 'left',
												'icon_img'  => ''
											),
					'class'            => 'wpbc_ui_button',  																		// ''  | 'wpbc_ui_button_primary'
					'style'            => '',																						// Any CSS class here
					'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
					'attr'             => array( 'id' => 'btn__status_bar__skip_wizard' )
			);

	wpbc_flex_button( $params );
}


/**
 * Button -  Reset Wizard to default values
 * @return void
 */
function wpbc_ajx_cstm__ui__reset_to_default__btn(){

	$params  =  array(
					'type'             => 'button' ,
					'title'            => __( 'Reset Wizard', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
					'hint'             => array( 'title' => __( 'Reset selected options to default values', 'booking' ), 'position' => 'top' ),  	// Hint
					'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
					'action'           => "wpbc_ajx_customize_plugin__send_request_with_params( {
																		'do_action': 'make_reset',
																		'ui_clicked_element_id': 'btn__status_bar__reset'
													} );
										   wpbc_button_enable_loading_icon( this );
										   wpbc_admin_show_message_processing( '' );",																			// JavaScript
					'icon' 			   => array(
												'icon_font' => 'wpbc_icn_settings_backup_restore', //'wpbc_icn_rotate_left',
												'position'  => 'left',
												'icon_img'  => ''
											),
					'class'            => 'wpbc_ui_button_danger',  																// ''  | 'wpbc_ui_button_primary'
					'style'            => '',																						// Any CSS class here
					'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
					'attr'             => array( 'id' => 'btn__status_bar__reset' )
			);

	wpbc_flex_button( $params );
}


/**
 * Button - Select Prior Skin in select-box
 * @return void
 */
function wpbc_ajx_cstm__ui__selectbox_prior_btn( $dropdown_id, $is_apply_rotating_icon = true ){

	$params_button = array(
			  'type' => 'button'
			, 'title' => ''	                 																			// Title of the button
			// , 'hint'  => array( 'title' => __('Previous' ,'booking') , 'position' => 'top' )
			, 'link' => 'javascript:void(0)'    																		// Direct link or skip  it
			, 'action' => // "console.log( 'ON CLICK:', jQuery( '[name=\"set_days_customize_plugin\"]:checked' ).val() , jQuery( 'textarea[id^=\"date_booking\"]' ).val() );"                    // Some JavaScript to execure, for example run  the function
						  " var is_selected = jQuery( '#" . $dropdown_id . " option:selected' ).prop('selected', false).prev(); "
						  . " if ( is_selected.length == 0 ){ "
						  . "    is_selected = jQuery( '#" . $dropdown_id . " option' ).last(); "
						  . " } "
						  . " if ( is_selected.length > 0 ){ "
						  .	"    is_selected.prop('selected', true).trigger('change'); "
						  . 	 ( ( $is_apply_rotating_icon ) ? "		wpbc_button_enable_loading_icon( this ); " : "" )
						  . " } else { "
						  . "    jQuery( this ).addClass( 'disabled' ); "
						  . " } "
			, 'class' => 'wpbc_ui_button'     				  															// wpbc_ui_button  | wpbc_ui_button_primary
			//, 'icon_position' => 'left'         																		// Position  of icon relative to Text: left | right
			, 'icon' 			   => array(
										'icon_font' => 'wpbc_icn_arrow_back_ios', 										// 'wpbc_icn_check_circle_outline',
										'position'  => 'left',
										'icon_img'  => ''
									)
			, 'style' => ''                     																		// Any CSS class here
			, 'mobile_show_text' => false       																		// Show  or hide text,  when viewing on Mobile devices (small window size).
			, 'attr' => array()
	);

	wpbc_flex_button( $params_button );
}

/**
 * Button - Select Next Skin in select-box
 * @return void
 */
function wpbc_ajx_cstm__ui__selectbox_next_btn( $dropdown_id, $is_apply_rotating_icon = true ){

	$params_button = array(
			  'type' => 'button'
			, 'title' => ''	                 // Title of the button
			// , 'hint'  => array( 'title' => __('Next' ,'booking') , 'position' => 'top' )
			, 'link' => 'javascript:void(0)'    // Direct link or skip  it
			, 'action' => //"console.log( 'ON CLICK:', jQuery( '[name=\"set_days_customize_plugin\"]:checked' ).val() , jQuery( 'textarea[id^=\"date_booking\"]' ).val() );"                    // Some JavaScript to execure, for example run  the function
						  " var is_selected = jQuery( '#" . $dropdown_id . " option:selected' ).prop('selected', false).next(); "
						  . " if ( is_selected.length == 0 ){ "
						  . "    is_selected = jQuery( '#" . $dropdown_id . " option' ).first(); "
						  . " } "
						  . " if ( is_selected.length > 0 ){ "
						  .	"    is_selected.prop('selected', true).trigger('change'); "
						  . 	 ( ( $is_apply_rotating_icon ) ? "		wpbc_button_enable_loading_icon( this ); " : "" )
						  . " } else { "
						  . "    jQuery( this ).addClass( 'disabled' ); "
						  . " } "
			, 'class' => 'wpbc_ui_button'     				  // wpbc_ui_button  | wpbc_ui_button_primary
			//, 'icon_position' => 'left'         // Position  of icon relative to Text: left | right
			, 'icon' 			   => array(
										'icon_font' => 'wpbc_icn_arrow_forward_ios', // 'wpbc_icn_check_circle_outline',
										'position'  => 'right',
										'icon_img'  => ''
									)
			, 'style' => ''                     // Any CSS class here
			, 'mobile_show_text' => false       // Show  or hide text,  when viewing on Mobile devices (small window size).
			, 'attr' => array()
	);

	wpbc_flex_button( $params_button );
}


/**
 * Textarea that  show shortcode for booking form
 * @return void
 */
function wpbc_ajx_cstm__ui__template__shortcode_booking_form(){

	$booking_action = 'set_shortcode_booking_form';

	$el_id = 'ui_btn_cstm__' . $booking_action ;

	//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

	?><div class="ui_element ui_nowrap0"><?php

		wpbc_flex_label( array(  'id' 	  => $el_id
								, 'label' => '<span class="" style="font-weight:600;">' . __('Booking form shortcode' ,'booking') . ':</span>'
						) );
	?></div><?php
	?><div class="ui_element ui_nowrap" style="flex: 1 1 100%;"><?php

		 $param_text = array(
						'type'          => 'text'
						, 'id'          => $el_id
						, 'name'        => $el_id
						, 'label'       => ''
						, 'disabled'    => false
						, 'class'       => 'put-in'
						, 'style'       => 'width:100%;height:auto;font-weight:600;'
						, 'placeholder' => '0'
						, 'attr'        => array()
						, 'is_escape_value' => false
						, 'value' => "{{ data.ajx_data.calendar_settings.shortcode__booking_form }}"
						, 'onfocus' => 'this.select()'
						//, 'onkeydown' => "jQuery('.ui__set_booking_cost__section_in_booking_{{data['parsed_fields']['booking_id']}}').show();"			// JavaScript code
						//, 'onchange' => ""
						, 'rows' 		=> '4'
						, 'cols' 		=> '50'
		 				, 'attr' => array( 'readonly' => 'readonly' )
		);
 		wpbc_flex_textarea( $param_text );
	 	//wpbc_flex_text( $param_text );
	?></div><?php

}


// <editor-fold     defaultstate="collapsed"                        desc=" ==  Calendar Skin UI  == "  >

/**
 * Select-box - Calendar skins
 *
 * @return void
 */
function wpbc_ajx_cstm__ui__calendar_skin_dropdown(){

		$booking_action = 'set_calendar_skin';

		$el_id = 'ui_btn_cstm__' . $booking_action ;

		//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}


        //  Calendar Skin  /////////////////////////////////////////////////////
        $calendar_skins_options  = array();

        // Skins in the Custom User folder (need to create it manually):    http://example.com/wp-content/uploads/wpbc_skins/ ( This folder do not owerwrited during update of plugin )
        $upload_dir = wp_upload_dir();
	    //FixIn: 8.9.4.8
		$files_in_folder = wpbc_dir_list( array(  WPBC_PLUGIN_DIR . '/css/skins/', $upload_dir['basedir'].'/wpbc_skins/' ) );  // Folders where to look about calendar skins
        foreach ( $files_in_folder as $skin_file ) {                                                                            // Example: $skin_file['/css/skins/standard.css'] => 'Standard';

            //FixIn: 8.9.4.8    //FixIn: 9.1.2.10
			$skin_file[1] = str_replace( array( WPBC_PLUGIN_DIR, WPBC_PLUGIN_URL , $upload_dir['basedir'] ), '', $skin_file[1] );                 // Get relative path for calendar skin
            $calendar_skins_options[ WPBC_PLUGIN_URL . $skin_file[1] ] = $skin_file[2];
        }

		$params_select = array(
							  'id'       => $el_id 				// HTML ID  of element
							, 'name'     => $booking_action
							, 'label' => '' //__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
							, 'style'    => '' 					// CSS of select element
									, 'class'    => 'wpbc_radio__set_days_customize_plugin' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => $calendar_skins_options
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param('customize_plugin__booking_skin', jQuery(this).val().replace( '" . WPBC_PLUGIN_URL . "', '') );"
//							, 'onchange' =>  "jQuery(this).hide();
//											 var jButton = jQuery('#button_locale_for_booking{{data[\'parsed_fields\'][\'booking_id\']}}');
//											 jButton.show();
//											 wpbc_button_enable_loading_icon( jButton.get(0) ); "
//											 . " wpbc_ajx_booking_ajax_action_request( {
//																						'booking_action' : '{$booking_action}',
//																						'booking_id'     : {{data[\'parsed_fields\'][\'booking_id\']}},
//																						'booking_meta_locale' : jQuery('#locale_for_booking{{data[\'parsed_fields\'][\'booking_id\']}} option:selected').val()
//																					} );"

						  );


			wpbc_flex_select( $params_select );
}

// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" ==  Calendar Size UI  == "  >

/**
 * Select-box - Number of Visible Months
 *
 * @return void
 */
function wpbc_ajx_cstm__ui__template__visible_months(){

	$booking_action = 'set_calendar_visible_months';

	$el_id = 'ui_btn_cstm__' . $booking_action ;

	//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}


	?><div class="ui_element ui_nowrap0"><?php

		wpbc_flex_label( array(  'id' 	  => $el_id
								, 'label' => '<span class="" style="font-weight:600;">' . __('Number of visible months' ,'booking') . ':</span>'
						) );
	?></div><?php
	?><div class="ui_element ui_nowrap"><?php

		//  Options
		$dropdown_options = array( 1  => 1, 2  => 2, 3  => 3, 4  => 4, 5  => 5, 6  => 6, 7  => 7, 8  => 8, 9  => 9, 10 => 10, 11 => 11, 12 => 12 );

		$params_select = array(
							  'id'       => $el_id 				// HTML ID  of element
							, 'name'     => $booking_action
							, 'label' => '' 				//__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
							, 'style'    => '' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => $dropdown_options
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__visible_months', jQuery(this).val() );
									var t_visible_months = parseInt( wpbc_ajx_customize_plugin.search_get_param( 'calendar__view__visible_months' ) );
									/* var t_months_in_row = (  3 > t_visible_months ) ? '' : 2 ; 	
							   		wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__months_in_row', t_months_in_row );
							   		*/							   		
									wpbc_ajx_customize_plugin__send_request_with_params( {} );
									wpbc_admin_show_message_processing( '' );									
									"
						  );
		wpbc_flex_select( $params_select );


		wpbc_ajx_cstm__ui__selectbox_prior_btn( $el_id );
		wpbc_ajx_cstm__ui__selectbox_next_btn( $el_id );
	?></div><?php

	// Set checked specific OPTION depends on last action from  user
	?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
		jQuery( document ).ready( function (){
			// Set selected option  in dropdown list based on  data. value
			jQuery( '#<?php echo $el_id; ?> option[value="' + data.ajx_data.calendar_settings.calendar__view__visible_months + '"]' ).prop( 'selected', true );
		} );

	<?php if (0) { ?></script><?php } ?> #><?php
}


/**
 * Select-box - Number of Months in a Row
 *
 * @return void
 */
function wpbc_ajx_cstm__ui__template__months_in_row(){

	$booking_action = 'set_calendar_months_in_row';

	$el_id = 'ui_btn_cstm__' . $booking_action ;

	//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}


	?><div class="ui_element ui_nowrap0"><?php

		wpbc_flex_label( array(  'id' 	  => $el_id
								, 'label' => '<span class="" style="font-weight:600;">' . __('Number of months in a row' ,'booking') . ':</span>'
						) );
	?></div><?php
	?><div class="ui_element ui_nowrap"><?php

		//  Options
		$dropdown_options = array( '' => __('Default','booking'), 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6 );

		$params_select = array(
							  'id'       => $el_id 				// HTML ID  of element
							, 'name'     => $booking_action
							, 'label' => '' 				//__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
							, 'style'    => '' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => $dropdown_options
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__months_in_row', jQuery(this).val() );
											 wpbc_ajx_customize_plugin__send_request_with_params( {} );"
						  );
		wpbc_flex_select( $params_select );


		wpbc_ajx_cstm__ui__selectbox_prior_btn( $el_id );
		wpbc_ajx_cstm__ui__selectbox_next_btn( $el_id );
	?></div><?php

	// Set checked specific OPTION depends on last action from  user
	?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
		jQuery( document ).ready( function (){
			// Set selected option  in dropdown list based on  data. value
			jQuery( '#<?php echo $el_id; ?> option[value="' + data.ajx_data.calendar_settings.calendar__view__months_in_row + '"]' ).prop( 'selected', true );
		} );

	<?php if (0) { ?></script><?php } ?> #><?php
}


/**
 * Width of Calendar
 *
 * @return void
 */
function wpbc_ajx_cstm__ui__template__calendar_width(){

	$booking_action = 'set_calendar_width';

	$el_id = 'ui_btn_cstm__' . $booking_action ;

	//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

	?><div class="ui_element ui_nowrap0"><?php

		wpbc_flex_label( array(  'id' 	  => $el_id
								, 'label' => '<span class="" style="font-weight:600;">' . __('Maximum width of calendar' ,'booking') . ':</span>'
						) );
	?></div><?php
	?><div class="ui_element ui_nowrap"><?php

		 $param_text = array(
						'type'          => 'text'
						, 'id'          => $el_id
						, 'name'        => $el_id
						, 'label'       => ''
						, 'disabled'    => false
						, 'class'       => ''
						, 'style'       => 'width:5em;'
						, 'placeholder' => __( 'Default', 'booking' )
						, 'attr'        => array()
						, 'is_escape_value' => false
						, 'value' => "{{ data.ajx_data.calendar_settings.calendar__view__width.replace('px','').replace('%','') }}"
						, 'onfocus' => ''
						//, 'onkeydown' => "jQuery('.ui__set_booking_cost__section_in_booking_{{data['parsed_fields']['booking_id']}}').show();"			// JavaScript code
						, 'onchange' => "var t_width = parseInt( jQuery(this).val() );
										 t_width = isNaN( t_width ) ? '' : t_width;
										 var t_dim = jQuery( '#" . $el_id . "_dim' ).val();
										 if ( '%' == t_dim ){
										 	t_width = ( t_width > 100) ? 100 : t_width;
										 	t_width = ( 0 === t_width ) ? '' : t_width; 
										 }
										 t_dim = ( '' === t_width ) ? '' : t_dim; 
										 wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__width', t_width + t_dim );"
//									 . " wpbc_ajx_customize_plugin__send_request_with_params( {} );"

		);
 		wpbc_flex_text( $param_text );

		$params_select = array(
							  'id'       => $el_id 	. '_dim'			// HTML ID  of element
							, 'name'     => $el_id 	. '_dim'
							, 'label' => '' 				//__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
							, 'style'    => '' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => array( '%' => '% &nbsp; ', 'px' => 'px &nbsp; ' )
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "jQuery( '#" . $el_id . "' ).trigger( 'change' );"
						  );
		wpbc_flex_select( $params_select );

		//wpbc_ajx_cstm__ui__select_skin_prior_btn( $el_id );
		//wpbc_ajx_cstm__ui__select_skin_next_btn( $el_id );
	?></div><?php

	// Set checked specific OPTION depends on last action from  user
	?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
		jQuery( document ).ready( function (){

			if ( -1 !== data.ajx_data.calendar_settings.calendar__view__width.indexOf('%') ) {
				jQuery( '#<?php echo $el_id; ?>_dim option[value="%"]' ).prop( 'selected', true );
			} else {
				jQuery( '#<?php echo $el_id; ?>_dim option[value="px"]' ).prop( 'selected', true );
			}

		} );

	<?php if (0) { ?></script><?php } ?> #><?php
}


/**
 * Height of Calendar Cell
 *
 * @return void
 */
function wpbc_ajx_cstm__ui__template__calendar_cell_height(){

	$booking_action = 'set_calendar_cell_height';

	$el_id = 'ui_btn_cstm__' . $booking_action ;

	//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

	?><div class="ui_element ui_nowrap0"><?php

		wpbc_flex_label( array(  'id' 	  => $el_id
								, 'label' => '<span class="" style="font-weight:600;">' . __('Height of calendar cell' ,'booking') . ':</span>'
						) );
	?></div><?php
	?><div class="ui_element ui_nowrap"><?php

		 $param_text = array(
						'type'          => 'text'
						, 'id'          => $el_id
						, 'name'        => $el_id
						, 'label'       => ''
						, 'disabled'    => false
						, 'class'       => ''
						, 'style'       => 'width:5em;'
						, 'placeholder' => __( 'Default', 'booking' )
						, 'attr'        => array()
						, 'is_escape_value' => false
						, 'value' => "{{ data.ajx_data.calendar_settings.calendar__view__cell_height.replace('px','').replace('%','') }}"
						, 'onfocus' => ''
						//, 'onkeydown' => "jQuery('.ui__set_booking_cost__section_in_booking_{{data['parsed_fields']['booking_id']}}').show();"			// JavaScript code
						, 'onchange' => "var t_cell_height = parseInt( jQuery(this).val() );
										 t_cell_height = isNaN( t_cell_height ) ? '' : t_cell_height;
										 var t_dim = jQuery( '#" . $el_id . "_dim' ).val();
										 t_dim = ( '' === t_cell_height ) ? '' : t_dim; 
										 wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__cell_height', t_cell_height + t_dim );"
//								     . " wpbc_ajx_customize_plugin__send_request_with_params( {} );"

		);
 		wpbc_flex_text( $param_text );

		$params_select = array(
							  'id'       => $el_id 	. '_dim'			// HTML ID  of element
							, 'name'     => $el_id 	. '_dim'
							, 'label' => '' 				//__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
							, 'style'    => '' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => array( 'px' => 'px &nbsp; ' )
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "jQuery( '#" . $el_id . "' ).trigger( 'change' );"
						  );
		wpbc_flex_select( $params_select );

		//wpbc_ajx_cstm__ui__select_skin_prior_btn( $el_id );
		//wpbc_ajx_cstm__ui__select_skin_next_btn( $el_id );
	?></div><?php
}


/**
 * Button -  Apply
 * @return void
 */
function wpbc_ajx_cstm__ui__calendar_size_apply__btn(){

	$params  =  array(
					'type'             => 'button' ,
					'title'            => __( 'Apply Size', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
					'hint'             => '', //array( 'title' => __( 'Reset selected options to default values', 'booking' ), 'position' => 'top' ),  	// Hint
					'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
					'action'           => "wpbc_ajx_customize_plugin__send_request_with_params( { } );
										   wpbc_button_enable_loading_icon( this );
										   wpbc_admin_show_message_processing( '' );",																			// JavaScript
					'icon' 			   => array(
												'icon_font' => 'wpbc_icn_check', //'wpbc_icn_rotate_left',  wpbc_icn_close
												'position'  => 'left',
												'icon_img'  => ''
											),
					'class'            => 'wpbc_ui_button',  																		// ''  | 'wpbc_ui_button_primary'
					'style'            => '',																						// Any CSS class here
					'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
					'attr'             => array()
			);

	wpbc_flex_button( $params );
}


/**
 * Button -  Reset Calendar Size
 * @return void
 */
function wpbc_ajx_cstm__ui__calendar_size_reset__btn(){

	$params  =  array(
					'type'             => 'button' ,
					'title'            => __( 'Reset Size', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
					'hint'             => '',//array( 'title' => __( 'Reset selected options to default values', 'booking' ), 'position' => 'top' ),  	// Hint
					'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
					'action'           =>  "wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__visible_months', 1 );
											wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__months_in_row', '' );
											wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__width',  '' );
											wpbc_ajx_customize_plugin.search_set_param( 'calendar__view__cell_height',  '' );
											
											wpbc_ajx_customize_plugin__send_request_with_params( {} );
										   	wpbc_button_enable_loading_icon( this );
										   	wpbc_admin_show_message_processing( '' );",																			// JavaScript
					'icon' 			   => array(
												'icon_font' => 'wpbc_icn_close', //'wpbc_icn_rotate_left',  wpbc_icn_settings_backup_restore
												'position'  => 'left',
												'icon_img'  => ''
											),
					'class'            => 'wpbc_ui_button_danger0',  																// ''  | 'wpbc_ui_button_primary'
					'style'            => '',																						// Any CSS class here
					'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
					'attr'             => array( 'id' => 'btn__status_bar__reset' )
			);

	wpbc_flex_button( $params );
}

// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" ==  Dates Selection UI  == "  >

/**
 * Radio  buttons  - Dates selection  mode
 *
 * @return void
 */
function wpbc_ajx_cstm__ui__template__calendar_dates_selection(){

	$booking_action = 'set_calendar_dates_selection';

	$el_id = 'ui_btn_cstm__' . $booking_action ;

	//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

	?><div class="ui_element ui_nowrap0"><?php

		wpbc_flex_label( array(  'id' 	  => $el_id
								, 'label' => '<span class="" style="font-weight:600;">' . __('Days selection in calendar' ,'booking') . ':</span>'
						) );
	?></div><?php
	?><div class="ui_element"><?php

		$el_value = 'single';
		?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id. '_' . $el_value . '__outer_button'; ?>" style="padding-right: 8px;"><?php
			$params_radio = array(
							  'id'       => $el_id . '_' . $el_value				// HTML ID  of element
							, 'name'     => $booking_action
							, 'label'    => array( 'title' => __('Single day' ,'booking') , 'position' => 'right' )
							, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
									, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'legend'   => ''					// aria-label parameter
									, 'value'    => $el_value 		// Some Value from options array that selected by default
									//, 'selected' => false
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
									//, 'onchange' => "return false;"
							);
			wpbc_flex_radio( $params_radio );

		?></span><?php
	?></div><?php

	$el_value = 'multiple';
	?><div class="ui_element"><?php
		?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id. '_' . $el_value . '__outer_button'; ?>" style="padding-right: 8px;"><?php
			$params_radio = array(
							  'id'       => $el_id . '_' . $el_value				// HTML ID  of element
							, 'name'     => $booking_action
							, 'label'    => array( 'title' => __('Multiple days' ,'booking') , 'position' => 'right' )
							, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
									, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'legend'   => ''					// aria-label parameter
									, 'value'    => $el_value 		// Some Value from options array that selected by default
									//, 'selected' => false
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
								);
			wpbc_flex_radio( $params_radio );

		?></span><?php
	?></div><?php


	// Set checked specific Radio button,  depends on  last action  from  user
	?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>

		jQuery( document ).ready( function (){

			<?php foreach ( array( 'single', 'multiple' ) as $item_val) { ?>

				// Change and send Ajax
				jQuery( '#ui_btn_cstm__set_calendar_dates_selection_<?php echo $item_val; ?>' ).on( 'change', function ( event ){
					<?php // It's required for not send request second time !  ?>
					jQuery( '#ui_btn_cstm__set_calendar_dates_selection_<?php echo $item_val; ?>' ).off( 'change' );
					wpbc_ajx_customize_plugin.search_set_param( 'calendar__days_selection_mode', '<?php echo $item_val; ?>' );
					wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_dates_selection'} );
					wpbc_button_enable_loading_icon( this );
					wpbc_admin_show_message_processing( '' );
					return false;
				} );
				<?php // Helper,  if we click on button  side,  and not at  radio button or label,  then  make radio checked. ?>
				jQuery(     '.ui_btn_cstm__set_calendar_dates_selection_<?php echo $item_val; ?>__outer_button' ).on( 'click', function (){
					jQuery( '#ui_btn_cstm__set_calendar_dates_selection_<?php echo $item_val; ?>' ).prop( "checked", true ).trigger('change');
				} );

			<?php } ?>

			// Set checked or not, specific radio buttons
			if ( 'single' == data.ajx_data.calendar_settings.calendar__days_selection_mode ){
				jQuery( '#ui_btn_cstm__set_calendar_dates_selection_single' ).prop( 'checked', true );
			}
			if ( 'multiple' == data.ajx_data.calendar_settings.calendar__days_selection_mode ){
				jQuery( '#ui_btn_cstm__set_calendar_dates_selection_multiple' ).prop( 'checked', true );
			}
		} );

	<?php if (0) { ?></script><?php } ?> #><?php


	// wpbc_ajx_cstm__ui__template__calendar_dates_selection_range();
}


/**
 * Radio  buttons  - Range Dates selection  mode -   Business Small and higher
 *
 * @return void
 */
function wpbc_ajx_cstm__ui__template__calendar_dates_selection_range(){

	$booking_action = 'set_calendar_dates_selection';

	$el_id = 'ui_btn_cstm__' . $booking_action ;

	//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

		$el_value = 'dynamic';
	?><div class="ui_element"><?php
		?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id. '_' . $el_value . '__outer_button'; ?>" style="padding-right: 8px;"><?php
			$params_radio = array(
							  'id'       => $el_id . '_' . $el_value			// HTML ID  of element
							, 'name'     => $booking_action
							, 'label'    => array(    'title' => sprintf(__('2 mouse clicks' ,'booking'),'<strong>','</strong>','<strong>','</strong>')
												 	, 'position' => 'right' )
							, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
									, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'legend'   => ''					// aria-label parameter
									, 'value'    => $el_value 		// Some Value from options array that selected by default
									//, 'selected' => false
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
								);
			wpbc_flex_radio( $params_radio );

		?></span><?php
	?></div><?php


	?><div class="ui_element_sub_section ui_element_sub_section_dynamic" ><?php

		wpbc_ajx_cstm__ui__template__calendar_dates_selection_range__min_max();

		wpbc_ajx_cstm__ui__template__calendar_dates_selection_range__specific();

		wpbc_ajx_cstm__ui__template__calendar_dates_selection_range__start_days();

	?></div><?php



		$el_value = 'fixed';
	?><div class="ui_element"><?php
		?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id. '_' . $el_value . '__outer_button'; ?>" style="padding-right: 8px;"><?php
			$params_radio = array(
							  'id'       => $el_id . '_' . $el_value			// HTML ID  of element
							, 'name'     => $booking_action
							, 'label'    => array( 	  'title' => sprintf(__('1 mouse click' ,'booking'),'<strong>','</strong>','<strong>','</strong>')
													, 'position' => 'right' )
							, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
									, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'legend'   => ''					// aria-label parameter
									, 'value'    => $el_value 		// Some Value from options array that selected by default
									//, 'selected' => false
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
								);
			wpbc_flex_radio( $params_radio );

		?></span><?php
	?></div><?php


	?><div class="ui_element_sub_section ui_element_sub_section_fixed" ><?php

		wpbc_ajx_cstm__ui__template__calendar_dates_selection_fixed__number();

		wpbc_ajx_cstm__ui__template__calendar_dates_selection_fixed__start_days();

	?></div><?php


		// Set checked specific Radio button,  depends on  last action  from  user
		?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>

			jQuery( document ).ready( function (){

				<?php foreach ( array( 'dynamic', 'fixed' ) as $item_val) { ?>

					// Change and send Ajax
					jQuery( '#ui_btn_cstm__set_calendar_dates_selection_<?php echo $item_val; ?>' ).on( 'change', function ( event ){
						<?php // It's required for not send request second time !  ?>
						jQuery( '#ui_btn_cstm__set_calendar_dates_selection_<?php echo $item_val; ?>' ).off( 'change' );
							wpbc_ajx_customize_plugin.search_set_param( 'calendar__days_selection_mode', '<?php echo $item_val; ?>' );
							wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_dates_selection'} );
							wpbc_button_enable_loading_icon( this );
							wpbc_admin_show_message_processing( '' );
						jQuery( '.ui_element_sub_section_dynamic,.ui_element_sub_section_fixed').hide();
						if ('fixed' == '<?php echo $item_val; ?>') {
							jQuery( '.ui_element_sub_section_fixed').show();
						} else {
							jQuery( '.ui_element_sub_section_dynamic').show();
						}
						return false;
					} );
					<?php // Helper,  if we click on button  side,  and not at  radio button or label,  then  make radio checked. ?>
					jQuery(     '.ui_btn_cstm__set_calendar_dates_selection_<?php echo $item_val; ?>__outer_button' ).on( 'click', function (){
						jQuery( '#ui_btn_cstm__set_calendar_dates_selection_<?php echo $item_val; ?>' ).prop( "checked", true ).trigger('change');
					} );

				<?php } ?>

				// Set checked or not, specific radio buttons
				if ( 'dynamic' == data.ajx_data.calendar_settings.calendar__days_selection_mode ){
					jQuery( '#ui_btn_cstm__set_calendar_dates_selection_dynamic' ).prop( 'checked', true );
				}
				jQuery( '.ui_element_sub_section_dynamic,.ui_element_sub_section_fixed').hide();
				if ( 'fixed' == data.ajx_data.calendar_settings.calendar__days_selection_mode ){
					jQuery( '#ui_btn_cstm__set_calendar_dates_selection_fixed' ).prop( 'checked', true );
					jQuery( '.ui_element_sub_section_fixed').show();
				} else {
					jQuery( '.ui_element_sub_section_dynamic').show();
				}
			} );

		<?php if (0) { ?></script><?php } ?> #><?php
}

		/**
		 *  Range Days 	Min - Max
		 */
		function wpbc_ajx_cstm__ui__template__calendar_dates_selection_range__min_max(){

			$booking_action = 'set_calendar_dates_selection_dynamic';

			$el_id = 'ui_btn_cstm__' . $booking_action . '_min';

		/*
			?><div class="ui_element ui_element_micro ui_nowrap0" style="flex:1 1 100%"><?php

				wpbc_flex_label( array(  'id' 	  => $el_id
										, 'label' => '<span class="" style="font-weight:600;">' . __('Min-max' ,'booking') . ':</span>'
								) );
			?></div><?php
		*/

			$params = array(
									  'id'       => $el_id 		// HTML ID  of element
									, 'name'     => $el_id
									, 'label'    => __( 'From', 'booking' )//'<span class="" style="font-weight:600;">' . __( 'Days', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
									, 'style'    => 'max-width: 50px;' 					// CSS of select element
									, 'class'    => '' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'placeholder' => '1'
									, 'value'    => "{{ data.ajx_data.calendar_settings.calendar__bk_2clicks_mode_days_min }}"
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
									, 'onchange' => " 	wpbc_ajx_customize_plugin.search_set_param( 'calendar__bk_2clicks_mode_days_min', jQuery(this).val() );
														wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_dates_selection'} );
														wpbc_button_enable_loading_icon( this );
														wpbc_admin_show_message_processing( '' );"
					);
			?><div class="ui_element ui_element_micro" style="margin-right: 5px;"><?php

			wpbc_flex_text( $params );

			?></div><?php


			$el_id = 'ui_btn_cstm__' . $booking_action . '_max';

			$params = array(
									  'id'       => $el_id 		// HTML ID  of element
									, 'name'     => $el_id
									, 'label'    => __( 'to', 'booking' )//'<span class="" style="font-weight:600;"> &dash; </span>'
									, 'style'    => 'max-width: 50px;' 					// CSS of select element
									, 'class'    => '' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'placeholder' => '30'
									, 'value'    => "{{ data.ajx_data.calendar_settings.calendar__bk_2clicks_mode_days_max }}"
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
									, 'onchange' => " 	wpbc_ajx_customize_plugin.search_set_param( 'calendar__bk_2clicks_mode_days_max', jQuery(this).val() );
														wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_dates_selection'} );
														wpbc_button_enable_loading_icon( this );
														wpbc_admin_show_message_processing( '' );"

					);
			?><div class="ui_element ui_element_micro"><?php

			wpbc_flex_text( $params );

			?></div><?php

			?><div class="ui_element ui_element_micro"><?php

				wpbc_flex_label( array(  'id' 	  => $el_id
										, 'label' => __('days' ,'booking')
								) );
			?></div><?php


		}

		/**
		 *  Range Days 	Specific
		 */
		function wpbc_ajx_cstm__ui__template__calendar_dates_selection_range__specific(){

			$booking_action = 'set_calendar_dates_selection_dynamic';

			$el_id = 'ui_btn_cstm__' . $booking_action . '_specific';


			?><div class="ui_element ui_element_micro ui_nowrap0" style="flex:1 1 100%"><?php

				wpbc_flex_label( array(  'id' 	  => $el_id
										, 'label' => '<span class="" style="font-weight:400;">' . __('Specific days selections' ,'booking') . ':</span>'
								) );
			?></div><?php


			$params = array(
									  'id'       => $el_id 		// HTML ID  of element
									, 'name'     => $el_id
									, 'label'    => ''//'<span class="" style="font-weight:600;">' . __( 'Days', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
									, 'style'    => 'max-width: 100%;' 					// CSS of select element
									, 'class'    => '' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'placeholder' => '7,14,21,28'
									, 'value'    => "{{ data.ajx_data.calendar_settings.calendar__bk_2clicks_mode_days_specific }}"
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
									, 'onchange' => " 	wpbc_ajx_customize_plugin.search_set_param( 'calendar__bk_2clicks_mode_days_specific', jQuery(this).val() );
														wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_dates_selection'} );
														wpbc_button_enable_loading_icon( this );
														wpbc_admin_show_message_processing( '' );"

					);
			?><div class="ui_element ui_element_micro"><?php

			wpbc_flex_text( $params );

			?></div><?php
		}

		/**
		 *  Range Days 	Start week days*
		 */
		function wpbc_ajx_cstm__ui__template__calendar_dates_selection_range__start_days(){

			$booking_action = 'set_calendar_dates_selection_dynamic';

			$el_id = 'ui_btn_cstm__' . $booking_action . '_start_days';


			?><div class="ui_element ui_element_micro ui_nowrap0" style="flex:1 1 100%"><?php

				wpbc_flex_label( array(  'id' 	  => $el_id
										, 'label' => '<span class="" style="font-weight:400;">' . __('Start day of range' ,'booking') . ':</span>'
								) );
			?></div><?php

			//TODO: here 2023-07-02
			// 	Set  checked or unchecked checkboxes depends from		data.ajx_data.calendar_settings.calendar__bk_2clicks_mode_days_start = "2,5	|| "0,1,2,3,4,5,6"   || "-1"
			// Then onchange send correct  'calendar__bk_2clicks_mode_days_start' value,  for example if it's "0,1,2,3,4,5,6",  then it have to  be '-1'
			// And create the same UI  for fixed 1 click

			// data.ajx_data.calendar_settings.calendar__bk_2clicks_mode_days_start = "2,5	|| "0,1,2,3,4,5,6"   || "-1"

			$week_days_arr = array(
								  0 => __( 'Su', 'booking' )
								, 1 => __( 'Mo', 'booking' )
								, 2 => __( 'Tu', 'booking' )
								, 3 => __( 'We', 'booking' )
								, 4 => __( 'Th', 'booking' )
								, 5 => __( 'Fr', 'booking' )
								, 6 => __( 'Sa', 'booking' )
								);
			foreach ( $week_days_arr as $day_key => $day_title ) {

				$params_checkbox = array(
										  'id'       => $el_id . '_'.$day_key 		// HTML ID  of element
										, 'name'     => $el_id . '_'.$day_key
										, 'label'    => array( 'title' => $day_title , 'position' => 'right' )           //FixIn: 9.6.1.5
										, 'style'    => '' 					// CSS of select element
										, 'class'    => '' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 							// Any  additional attributes, if this radio | checkbox element
										, 'legend'   => ''									// aria-label parameter
										, 'value'    => $day_key 							// Some Value from optins array that selected by default
										, 'selected' => false								// Selected or not
										//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
										//, 'onchange' => "wpbc_ajx_booking_send_search_request_with_params( {'ui_usr__send_emails': (jQuery( this ).is(':checked') ? 'send' : 'not_send') } );"					// JavaScript code
										, 'onchange' => " 	
										 var sel_arr_el = wpbc_ajx_customize_plugin.search_get_param( 'calendar__bk_2clicks_mode_days_start' );
										 if ( -1 == sel_arr_el ){
											sel_arr_el = '0,1,2,3,4,5,6'; 
										 }
										 if ( jQuery( this ).is(':checked') ) {
											sel_arr_el = _.uniq( ['".$day_key."'].concat( sel_arr_el.split(',') ) ).sort().join( ',');
										 } else {
											sel_arr_el = _.uniq( _.without( sel_arr_el.split(','), '".$day_key."' ) ).sort().join( ',');
										 }											 					 
										 wpbc_ajx_customize_plugin.search_set_param( 'calendar__bk_2clicks_mode_days_start', sel_arr_el );
															wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_dates_selection'} );
															wpbc_button_enable_loading_icon( this );
															wpbc_admin_show_message_processing( '' );"

									);
				?><div class="ui_element ui_element_micro"><?php
					wpbc_flex_toggle( $params_checkbox );
				?></div><?php


				// Set checked specific Radio button,  depends on  last action  from  user
				?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>

					jQuery( document ).ready( function (){
						if (   ( '-1' === data.ajx_data.calendar_settings.calendar__bk_2clicks_mode_days_start )
							|| ( -1 !== data.ajx_data.calendar_settings.calendar__bk_2clicks_mode_days_start.indexOf( '<?php echo $day_key; ?>' ) )
						){
							jQuery( '#<?php echo $el_id . '_'.$day_key; ?>' ).prop( 'checked', true );//.change();
						} else {
							jQuery( '#<?php echo $el_id . '_'.$day_key; ?>' ).prop( 'checked', false );//.change();
						}
					} );
				<?php if (0) { ?></script><?php } ?> #><?php
			}

		}



		/**
		 *  Fixed  Days 	Number
		 */
		function wpbc_ajx_cstm__ui__template__calendar_dates_selection_fixed__number(){

			$booking_action = 'set_calendar_dates_selection_fixed';

			$el_id = 'ui_btn_cstm__' . $booking_action . '_number';


			?><div class="ui_element ui_element_micro ui_nowrap0" style="flex:1 1 100%"><?php

				wpbc_flex_label( array(  'id' 	  => $el_id
										, 'label' => '<span class="" style="font-weight:400;">' . __('Days selection number' ,'booking') . ':</span>'
								) );
			?></div><?php


			$params = array(
									  'id'       => $el_id 		// HTML ID  of element
									, 'name'     => $el_id
									, 'label'    => ''//'<span class="" style="font-weight:600;">' . __( 'Days', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
									, 'style'    => 'max-width: 100%;' 					// CSS of select element
									, 'class'    => '' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
									, 'placeholder' => '7'
									, 'value'    => "{{ data.ajx_data.calendar_settings.calendar__bk_1click_mode_days_num }}"
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).val() , 'in element:' , jQuery( this ) );"					// JavaScript code
									, 'onchange' => " 	wpbc_ajx_customize_plugin.search_set_param( 'calendar__bk_1click_mode_days_num', jQuery(this).val() );
														wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_dates_selection'} );
														wpbc_button_enable_loading_icon( this );
														wpbc_admin_show_message_processing( '' );"

					);
			?><div class="ui_element ui_element_micro"><?php

			wpbc_flex_text( $params );

			?></div><?php
		}

		/**
		 *  Fixed Days 	Start week days*
		 */
		function wpbc_ajx_cstm__ui__template__calendar_dates_selection_fixed__start_days(){

			$booking_action = 'set_calendar_dates_selection_fixed';

			$el_id = 'ui_btn_cstm__' . $booking_action . '_start_days';


			?><div class="ui_element ui_element_micro ui_nowrap0" style="flex:1 1 100%"><?php

				wpbc_flex_label( array(  'id' 	  => $el_id
										, 'label' => '<span class="" style="font-weight:400;">' . __('Start day of range' ,'booking') . ':</span>'
								) );
			?></div><?php

			$week_days_arr = array(
								  0 => __( 'Su', 'booking' )
								, 1 => __( 'Mo', 'booking' )
								, 2 => __( 'Tu', 'booking' )
								, 3 => __( 'We', 'booking' )
								, 4 => __( 'Th', 'booking' )
								, 5 => __( 'Fr', 'booking' )
								, 6 => __( 'Sa', 'booking' )
								);
			foreach ( $week_days_arr as $day_key => $day_title ) {

				$params_checkbox = array(
										  'id'       => $el_id . '_'.$day_key 		// HTML ID  of element
										, 'name'     => $el_id . '_'.$day_key
										, 'label'    => array( 'title' => $day_title , 'position' => 'right' )           //FixIn: 9.6.1.5
										, 'style'    => '' 					// CSS of select element
										, 'class'    => '' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 							// Any  additional attributes, if this radio | checkbox element
										, 'legend'   => ''									// aria-label parameter
										, 'value'    => $day_key 							// Some Value from optins array that selected by default
										, 'selected' => false								// Selected or not
										//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
										//, 'onchange' => "wpbc_ajx_booking_send_search_request_with_params( {'ui_usr__send_emails': (jQuery( this ).is(':checked') ? 'send' : 'not_send') } );"					// JavaScript code
										, 'onchange' => " 	
										 var sel_arr_el = wpbc_ajx_customize_plugin.search_get_param( 'calendar__bk_1click_mode_days_start' );
										 if ( -1 == sel_arr_el ){
											sel_arr_el = '0,1,2,3,4,5,6'; 
										 }
										 if ( jQuery( this ).is(':checked') ) {
											sel_arr_el = _.uniq( ['".$day_key."'].concat( sel_arr_el.split(',') ) ).sort().join( ',');
										 } else {
											sel_arr_el = _.uniq( _.without( sel_arr_el.split(','), '".$day_key."' ) ).sort().join( ',');
										 }											 					 
										 wpbc_ajx_customize_plugin.search_set_param( 'calendar__bk_1click_mode_days_start', sel_arr_el );
															wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_dates_selection'} );
															wpbc_button_enable_loading_icon( this );
															wpbc_admin_show_message_processing( '' );"

									);
				?><div class="ui_element ui_element_micro"><?php
					wpbc_flex_toggle( $params_checkbox );
				?></div><?php


				// Set checked specific Radio button,  depends on  last action  from  user
				?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>

					jQuery( document ).ready( function (){
						if (   ( '-1' === data.ajx_data.calendar_settings.calendar__bk_1click_mode_days_start )
							|| ( -1 !== data.ajx_data.calendar_settings.calendar__bk_1click_mode_days_start.indexOf( '<?php echo $day_key; ?>' ) )
						){
							jQuery( '#<?php echo $el_id . '_'.$day_key; ?>' ).prop( 'checked', true );//.change();
						} else {
							jQuery( '#<?php echo $el_id . '_'.$day_key; ?>' ).prop( 'checked', false );//.change();
						}
					} );
				<?php if (0) { ?></script><?php } ?> #><?php
			}

		}


// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" ==  Unavailable Weekdays UI  == "  >


	/**
	 *  Unavailable - weekdays
	 */
	function wpbc_ajx_cstm__ui__template__calendar_weekdays_availability(){

		$booking_action = 'set_calendar_weekdays_availability';

		$el_id = 'ui_btn_cstm__' . $booking_action ;


		?><div class="ui_element ui_element_micro ui_nowrap0" style="flex:1 1 100%"><?php

			wpbc_flex_label( array(  'id' 	  => $el_id
									, 'style' => 'height: auto;line-height: 1.75em;'
									, 'label' => '<span class="" style="font-weight:400;">' . __('Check unavailable days in calendars. This option will overwrite all other settings.' ,'booking') . '</span>'
							) );
		?></div><?php

		$week_days_arr = array(
							  0 => __( 'Su', 'booking' )
							, 1 => __( 'Mo', 'booking' )
							, 2 => __( 'Tu', 'booking' )
							, 3 => __( 'We', 'booking' )
							, 4 => __( 'Th', 'booking' )
							, 5 => __( 'Fr', 'booking' )
							, 6 => __( 'Sa', 'booking' )
							);



		foreach ( $week_days_arr as $day_key => $day_title ) {

			$params_checkbox = array(
									  'id'       => $el_id . '_'.$day_key 		// HTML ID  of element
									, 'name'     => $el_id . '_'.$day_key
									, 'label'    => array( 'title' => $day_title , 'position' => 'right' )           //FixIn: 9.6.1.5
									, 'style'    => '' 					// CSS of select element
									, 'class'    => '' 					// CSS Class of select element
									, 'disabled' => false
									, 'attr'     => array() 							// Any  additional attributes, if this radio | checkbox element
									, 'legend'   => ''									// aria-label parameter
									, 'value'    => $day_key 							// Some Value from optins array that selected by default
									, 'selected' => false								// Selected or not
									//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
									//, 'onchange' => "wpbc_ajx_booking_send_search_request_with_params( {'ui_usr__send_emails': (jQuery( this ).is(':checked') ? 'send' : 'not_send') } );"					// JavaScript code
									, 'onchange' => " 	
									 var sel_arr_el = wpbc_ajx_customize_plugin.search_get_param( 'availability__user_unavilable_days' );										 
									 if ( jQuery( this ).is(':checked') ) {
										sel_arr_el = _.uniq( ['".$day_key."'].concat( sel_arr_el.split(',') ) ).sort().join( ',');
									 } else {
										sel_arr_el = _.uniq( _.without( sel_arr_el.split(','), '".$day_key."' ) ).sort().join( ',');
									 }												 								 					 
									 wpbc_ajx_customize_plugin.search_set_param( 'availability__user_unavilable_days', sel_arr_el );
														wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
														wpbc_button_enable_loading_icon( this );
														wpbc_admin_show_message_processing( '' );"

								);
			?><div class="ui_element ui_element_micro"><?php
				wpbc_flex_toggle( $params_checkbox );
			?></div><?php


			// Set checked specific Radio button,  depends on  last action  from  user
			?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>

				jQuery( document ).ready( function (){
					if (   ( '-1' === data.ajx_data.calendar_settings.calendar_unavailable.user_unavilable_days )
						|| ( -1 !== data.ajx_data.calendar_settings.calendar_unavailable.user_unavilable_days.indexOf( '<?php echo $day_key; ?>' ) )
					){
						jQuery( '#<?php echo $el_id . '_'.$day_key; ?>' ).prop( 'checked', true );
					} else {
						jQuery( '#<?php echo $el_id . '_'.$day_key; ?>' ).prop( 'checked', false );
					}
				} );
			<?php if (0) { ?></script><?php } ?> #><?php
		}

	}

	/**
	 *  Unavailable - from Today
	 */
	function wpbc_ajx_cstm__ui__template__calendar_unavailable_from_today(){

		$booking_action = 'set_calendar_unavailable_from_today';

		$el_id = 'ui_btn_cstm__' . $booking_action ;

		//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

		?><div class="ui_element ui_nowrap0"><?php

			wpbc_flex_label( array(  'id' 	  => $el_id
									, 'label' => '<div class="" style="font-weight:400;">'
													. 	__( 'Unavailable days from today', 'booking' ) . ': '
													. '<div><code id="' . $el_id . '_hint" style="font-weight:600;font-size:10px;padding:0;color: #626262;"></code></div>'
			                                    . '</div>'
							) );
		?></div><?php

		?><div class="ui_element ui_nowrap"><?php

			//  Options
			$dropdown_options = range( 0, 31 );

			$params_select = array(
								  'id'       => $el_id 				// HTML ID  of element
								, 'name'     => $booking_action
								, 'label' => '' 				//__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
								, 'style'    => '' 					// CSS of select element
								, 'class'    => '' 					// CSS Class of select element
								//, 'multiple' => true
								//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
								, 'disabled' => false
								, 'disabled_options' => array()     								// If some options disabled, then it has to list here
								, 'options' => $dropdown_options
								//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
								//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
								, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'availability__block_some_dates_from_today', jQuery(this).val() );													 
												 wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
												 wpbc_button_enable_loading_icon( this );
												 wpbc_admin_show_message_processing( '' );"
							  );
			wpbc_flex_select( $params_select );


			wpbc_ajx_cstm__ui__selectbox_prior_btn( $el_id );
			wpbc_ajx_cstm__ui__selectbox_next_btn( $el_id );
		?></div><?php

		// Set checked specific OPTION depends on last action from  user
		?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
			jQuery( document ).ready( function (){
				// Set selected option  in dropdown list based on  data. value
				jQuery( '#<?php echo $el_id; ?> option[value="' + data.ajx_data.calendar_settings.calendar_unavailable.block_some_dates_from_today + '"]' ).prop( 'selected', true );

				jQuery( '#<?php echo $el_id; ?>_hint' ).html( '<span style="color: #cc3a5f;text-transform: uppercase;"><?php _e('Unavailable','booking') ?></span>'
															+ data.ajx_data.calendar_settings.calendar_unavailable.block_some_dates_from_today__hint );
			} );

		<?php if (0) { ?></script><?php } ?> #><?php

	}

	/**
	 *  Limit Available - from Today
	 */
	function wpbc_ajx_cstm__ui__template__calendar_limit_available_from_today(){

		$booking_action = 'set_calendar_limit_available_from_today';

		$el_id = 'ui_btn_cstm__' . $booking_action ;

		//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

		?><div class="ui_element ui_nowrap0"><?php

			wpbc_flex_label( array(  'id' 	  => $el_id
									, 'label' => '<div class="" style="font-weight:400;">'
													. 	__( 'Limit available days from today', 'booking' ) . ': '
													. '<div><code id="' . $el_id . '_hint" style="font-weight:600;font-size:10px;padding:0;color: #626262;"></code></div>'
			                                    . '</div>'
							) );
		?></div><?php

		?><div class="ui_element ui_nowrap"><?php

			//  Options
			$dropdown_options = array( '' => ' - ' );
			//foreach ( range( 365, 1, -1 ) as $value ) {
			foreach ( range( 1,365 ) as $value ) {
				$dropdown_options[ $value ] = $value;
			}

			$params_select = array(
								  'id'       => $el_id 				// HTML ID  of element
								, 'name'     => $booking_action
								, 'label' => '' 				//__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
								, 'style'    => '' 					// CSS of select element
								, 'class'    => '' 					// CSS Class of select element
								//, 'multiple' => true
								//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
								, 'disabled' => false
								, 'disabled_options' => array()     								// If some options disabled, then it has to list here
								, 'options' => $dropdown_options
								//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
								//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
								, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'availability__wpbc_available_days_num_from_today', jQuery(this).val() );													 
												 wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
												 wpbc_button_enable_loading_icon( this );
												 wpbc_admin_show_message_processing( '' );"
							  );
			wpbc_flex_select( $params_select );


			wpbc_ajx_cstm__ui__selectbox_prior_btn( $el_id );
			wpbc_ajx_cstm__ui__selectbox_next_btn( $el_id );
		?></div><?php

		// Set checked specific OPTION depends on last action from  user
		?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
			jQuery( document ).ready( function (){
				// Set selected option  in dropdown list based on  data. value
				jQuery( '#<?php echo $el_id; ?> option[value="' + data.ajx_data.calendar_settings.calendar_unavailable.wpbc_available_days_num_from_today + '"]' ).prop( 'selected', true );

				jQuery( '#<?php echo $el_id; ?>_hint' ).html(  '<span style="color: #50be31;text-transform: uppercase;"><?php _e('Available','booking') ?></span>'
															+ data.ajx_data.calendar_settings.calendar_unavailable.wpbc_available_days_num_from_today__hint );
			} );

		<?php if (0) { ?></script><?php } ?> #><?php

	}



	/**
	 * Radio  buttons  - Range Dates selection  mode -   Business Small and higher
	 *
	 * @return void
	 */
	function wpbc_ajx_cstm__ui__template__calendar_unavailable_before_after_bookings(){

		$booking_action = 'set_calendar_unavailable_before_after_bookings';

		$el_id = 'ui_btn_cstm__' . $booking_action ;

		//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}
		?><div class="clear" style="height: 1px;width: 100%;border-top: 1px solid #ccc;margin: 15px 0 10px;"></div><?php
		?><div class="ui_element ui_nowrap0"><?php

			wpbc_flex_label( array(  'id' 	  => $el_id
									, 'style' => 'height: auto;'
									, 'label' => '<span class="" style="font-weight:400;">'
													. __('Unavailable time before / after booking' ,'booking')
												 .':</span>'

							) );
		?></div><?php

			$el_value = '';
		?><div class="ui_element"><?php
			?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id. '_' . $el_value . '__outer_button'; ?>" style="padding-right: 8px;"><?php
				$params_radio = array(
								  'id'       => $el_id . '_' . $el_value			// HTML ID  of element
								, 'name'     => $booking_action
								, 'label'    => array(    'title' => ucfirst( __( 'None', 'booking' ) )
														, 'position' => 'right' )
								, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
										, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
										, 'legend'   => ''					// aria-label parameter
										, 'value'    => $el_value 		// Some Value from options array that selected by default
										//, 'selected' => true
										//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
									);
				wpbc_flex_radio( $params_radio );

			?></span><?php
		?></div><?php


			$el_value = 'm';
		?><div class="ui_element"><?php
			?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id. '_' . $el_value . '__outer_button'; ?>" style="padding-right: 8px;"><?php
				$params_radio = array(
								  'id'       => $el_id . '_' . $el_value			// HTML ID  of element
								, 'name'     => $booking_action
								, 'label'    => array(    'title' => ucfirst( __( 'minutes', 'booking' ) ) . ' / ' . ucfirst( __( 'hours', 'booking' ) )
														, 'position' => 'right' )
								, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
										, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
										, 'legend'   => ''					// aria-label parameter
										, 'value'    => $el_value 		// Some Value from options array that selected by default
										//, 'selected' => false
										//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
									);
				wpbc_flex_radio( $params_radio );

			?></span><?php
		?></div><?php


		?><div class="ui_element_sub_section ui_element_sub_section_m" ><?php

			wpbc_ajx_cstm__ui__template__calendar_unavailable_before_after_bookings_minutes();

		?></div><?php



			$el_value = 'd';
		?><div class="ui_element"><?php
			?><span class="wpbc_ui_control wpbc_ui_button <?php echo $el_id. '_' . $el_value . '__outer_button'; ?>" style="padding-right: 8px;"><?php
				$params_radio = array(
								  'id'       => $el_id . '_' . $el_value			// HTML ID  of element
								, 'name'     => $booking_action
								, 'label'    => array( 	  'title' => ucfirst( __( 'day(s)', 'booking' ) )
														, 'position' => 'right' )
								, 'style'    => 'margin:1px 0 0;' 					// CSS of select element
										, 'class'    => 'wpbc_radio__set_days_availability' 					// CSS Class of select element
										, 'disabled' => false
										, 'attr'     => array() 			// Any  additional attributes, if this radio | checkbox element
										, 'legend'   => ''					// aria-label parameter
										, 'value'    => $el_value 		// Some Value from options array that selected by default
										//, 'selected' => false
										//, 'onfocus' =>  "console.log( 'ON FOCUS:',  jQuery( this ).is(':checked') , 'in element:' , jQuery( this ) );"					// JavaScript code
									);
				wpbc_flex_radio( $params_radio );

			?></span><?php
		?></div><?php


		?><div class="ui_element_sub_section ui_element_sub_section_d" ><?php

			wpbc_ajx_cstm__ui__template__calendar_unavailable_before_after_bookings_days();

		?></div><?php

		?><p><?php
			echo  '<div id="'.$el_id.'_hint" style="font-weight:400;font-size:11px;margin-top:15px;">'
					. '<strong>' . __('Important!' ,'booking') . '</strong> '
					. __( 'This feature is applying only for bookings for specific timeslots, or if activated check in/out time option.', 'booking' )
				.'</div>'
		 ?></p>  <?php

			// Set checked specific Radio button,  depends on  last action  from  user
			?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>

				jQuery( document ).ready( function (){

					<?php foreach ( array( '', 'm', 'd' ) as $item_val) { ?>

						// Change and send Ajax
						jQuery( '#ui_btn_cstm__set_calendar_unavailable_before_after_bookings_<?php echo $item_val; ?>' ).on( 'change', function ( event ){
							<?php // It's required for not send request second time !  ?>
							jQuery( '#ui_btn_cstm__set_calendar_unavailable_before_after_bookings_<?php echo $item_val; ?>' ).off( 'change' );
								wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_in_out', '<?php echo $item_val; ?>' );
								wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
								wpbc_button_enable_loading_icon( this );
								wpbc_admin_show_message_processing( '' );

							jQuery( '.ui_element_sub_section_d,.ui_element_sub_section_m').hide();
							if ('m' == '<?php echo $item_val; ?>') {
								jQuery( '.ui_element_sub_section_m').show();
							}
							if ('d' == '<?php echo $item_val; ?>') {
								jQuery( '.ui_element_sub_section_d').show();
							}
							return false;
						} );
						<?php // Helper,  if we click on button  side,  and not at  radio button or label,  then  make radio checked. ?>
						jQuery(     '.ui_btn_cstm__set_calendar_unavailable_before_after_bookings_<?php echo $item_val; ?>__outer_button' ).on( 'click', function (){
							jQuery( '#ui_btn_cstm__set_calendar_unavailable_before_after_bookings_<?php echo $item_val; ?>' ).prop( "checked", true ).trigger('change');
						} );

					<?php } ?>

					// Set checked or not, specific radio buttons
					jQuery( '#ui_btn_cstm__set_calendar_unavailable_before_after_bookings_' ).prop( 'checked', true );
					jQuery( '.ui_element_sub_section_d,.ui_element_sub_section_m').hide();
					if ( 'm' == data.ajx_data.calendar_settings.calendar_unavailable.booking_unavailable_extra_in_out ){
						jQuery( '#ui_btn_cstm__set_calendar_unavailable_before_after_bookings_m' ).prop( 'checked', true );
						jQuery( '.ui_element_sub_section_m').show();
					}
					if ( 'd' == data.ajx_data.calendar_settings.calendar_unavailable.booking_unavailable_extra_in_out ){
						jQuery( '#ui_btn_cstm__set_calendar_unavailable_before_after_bookings_d' ).prop( 'checked', true );
						jQuery( '.ui_element_sub_section_d').show();
					}
					<?php
					// Show possible selection in paid versions,  while using lower version.
					if ( ! class_exists( 'wpdev_bk_biz_m' ) ) { ?>
						jQuery( '.ui_element_sub_section_m').show();
						jQuery( '.ui_element_sub_section_d').show();
					<?php } ?>
				} );

			<?php if (0) { ?></script><?php } ?> #><?php
	}

	function wpbc_ajx_cstm__ui__template__calendar_unavailable_before_after_bookings_minutes(){

		$booking_action = 'booking_unavailable_extra_minutes';
		$el_id = 'ui_btn_cstm__' . $booking_action;

		//  Options
	   $extra_time = array();
		$extra_time[''] = ' - ';
		foreach ( range( 5, 55 , 5 ) as $extra_num) {                                           // Each 5 minutes
			$extra_time[ $extra_num . 'm' ] = $extra_num . ' ' . __( 'minutes', 'booking' );
		}
		$extra_time[ '60' . 'm' ] =  '1 ' . __( 'hour', 'booking' );
		foreach ( range( 65, 115 , 5 ) as $extra_num) {                                         // 1 hour + Each 5 minutes
			$extra_time[ $extra_num . 'm' ] =  '1 ' . __( 'hour', 'booking' ) . ' ' . ($extra_num - 60 ) . ' ' . __( 'minutes', 'booking' );
		}

		foreach ( range( 120, 1380 , 60 ) as $extra_num) {                                      // Each Hour based on minutes
			$extra_time[ $extra_num . 'm' ] = ($extra_num / 60) . ' ' . __( 'hours', 'booking' );
		}


		$params_select = array(
							  'id'       => $el_id  . '_in'				// HTML ID  of element
							, 'name'     => $el_id  . '_in'
							, 'label'    => __( 'Before booking', 'booking' )//'<span class="" style="font-weight:600;">' . __( 'Days', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
							, 'style'    => 'max-width: 100%;' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => $extra_time
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_minutes_in', jQuery(this).val() );													 
											 wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
											 wpbc_button_enable_loading_icon( this );
											 wpbc_admin_show_message_processing( '' );"
						  );
		?><div class="ui_element ui_element_micro"><?php
			wpbc_flex_select( $params_select );
		?></div><?php


		$params_select = array(
							  'id'       => $el_id  . '_out' 				// HTML ID  of element
							, 'name'     => $el_id  . '_out'
							, 'label'    => __( 'After booking', 'booking' )//'<span class="" style="font-weight:600;">' . __( 'Days', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
							, 'style'    => 'max-width: 100%;' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => $extra_time
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_minutes_out', jQuery(this).val() );													 
											 wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
											 wpbc_button_enable_loading_icon( this );
											 wpbc_admin_show_message_processing( '' );"
						  );
		?><div class="ui_element ui_element_micro"><?php
			wpbc_flex_select( $params_select );
		?></div><?php


		// Set checked specific OPTION depends on last action from  user
		?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
			jQuery( document ).ready( function (){
				// Set selected option  in dropdown list based on  data. value
				jQuery( '#<?php echo $el_id . '_in';  ?> option[value="' + data.ajx_data.calendar_settings.calendar_unavailable.booking_unavailable_extra_minutes_in + '"]' ).prop( 'selected', true );
				jQuery( '#<?php echo $el_id . '_out'; ?> option[value="' + data.ajx_data.calendar_settings.calendar_unavailable.booking_unavailable_extra_minutes_out + '"]' ).prop( 'selected', true );
			} );

		<?php if (0) { ?></script><?php } ?> #><?php
	}

	function wpbc_ajx_cstm__ui__template__calendar_unavailable_before_after_bookings_days(){

		$booking_action = 'booking_unavailable_extra_days';
		$el_id = 'ui_btn_cstm__' . $booking_action;

		//  Options
		$extra_time = array();
		$extra_time[''] = ' - ';
		foreach ( range( 1, 30 , 1 ) as $extra_num) {                                           // Each Day
			$extra_time[ $extra_num . 'd' ] = $extra_num . ' ' . __( 'day(s)', 'booking' );
		}


		$params_select = array(
							  'id'       => $el_id  . '_in'				// HTML ID  of element
							, 'name'     => $el_id  . '_in'
							, 'label'    => __( 'Before booking', 'booking' )//'<span class="" style="font-weight:600;">' . __( 'Days', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
							, 'style'    => 'max-width: 100%;' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => $extra_time
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_days_in', jQuery(this).val() );													 
											 wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
											 wpbc_button_enable_loading_icon( this );
											 wpbc_admin_show_message_processing( '' );"
						  );
		?><div class="ui_element ui_element_micro"><?php
			wpbc_flex_select( $params_select );
		?></div><?php


		$params_select = array(
							  'id'       => $el_id  . '_out' 				// HTML ID  of element
							, 'name'     => $el_id  . '_out'
							, 'label'    => __( 'After booking', 'booking' )//'<span class="" style="font-weight:600;">' . __( 'Days', 'booking' ) . ' <em style="color:#888;">(' . __( 'min-max', 'booking' ) . '):</em></span>'
							, 'style'    => 'max-width: 100%;' 					// CSS of select element
							, 'class'    => '' 					// CSS Class of select element
							//, 'multiple' => true
							//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
							, 'disabled' => false
							, 'disabled_options' => array()     								// If some options disabled, then it has to list here
							, 'options' => $extra_time
							//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
							//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
							, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_days_out', jQuery(this).val() );													 
											 wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
											 wpbc_button_enable_loading_icon( this );
											 wpbc_admin_show_message_processing( '' );"
						  );
		?><div class="ui_element ui_element_micro"><?php
			wpbc_flex_select( $params_select );
		?></div><?php


		// Set checked specific OPTION depends on last action from  user
		?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
			jQuery( document ).ready( function (){
				// Set selected option  in dropdown list based on  data. value
				jQuery( '#<?php echo $el_id . '_in';  ?> option[value="' + data.ajx_data.calendar_settings.calendar_unavailable.booking_unavailable_extra_days_in + '"]' ).prop( 'selected', true );
				jQuery( '#<?php echo $el_id . '_out'; ?> option[value="' + data.ajx_data.calendar_settings.calendar_unavailable.booking_unavailable_extra_days_out + '"]' ).prop( 'selected', true );
			} );

		<?php if (0) { ?></script><?php } ?> #><?php
	}



		/**
		 * Button -  Reset Calendar Availability
		 * @return void
		 */
		function wpbc_ajx_cstm__ui__calendar_weekdays_availability_reset__btn(){

			$params  =  array(
							'type'             => 'button' ,
							'title'            => __( 'Reset availability', 'booking' ) . '&nbsp;&nbsp;',  											// Title of the button
							'hint'             => '',//array( 'title' => __( 'Reset selected options to default values', 'booking' ), 'position' => 'top' ),  	// Hint
							'link'             => 'javascript:void(0)',  																	// Direct link or skip  it
							'action'           =>  "wpbc_ajx_customize_plugin.search_set_param( 'availability__user_unavilable_days', '' );
													wpbc_ajx_customize_plugin.search_set_param( 'availability__block_some_dates_from_today', 0 );
													wpbc_ajx_customize_plugin.search_set_param( 'availability__wpbc_available_days_num_from_today',  '' );
													wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_in_out',  '' );
													wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_minutes_in',  '' );
													wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_minutes_out',  '' );
													wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_days_in',  '' );
													wpbc_ajx_customize_plugin.search_set_param( 'availability__booking_unavailable_extra_days_out',  '' );
													
													wpbc_ajx_customize_plugin__send_request_with_params( {'do_action': 'save_calendar_weekdays_availability'} );
													wpbc_button_enable_loading_icon( this );
													wpbc_admin_show_message_processing( '' );",																			// JavaScript
							'icon' 			   => array(
														'icon_font' => 'wpbc_icn_close', //'wpbc_icn_rotate_left',  wpbc_icn_settings_backup_restore
														'position'  => 'left',
														'icon_img'  => ''
													),
							'class'            => 'wpbc_ui_button_danger',  																// ''  | 'wpbc_ui_button_primary'
							'style'            => '',																						// Any CSS class here
							'mobile_show_text' => true,																						// Show  or hide text,  when viewing on Mobile devices (small window size).
							'attr'             => array( 'id' => 'btn__status_bar__reset' )
					);

			wpbc_flex_button( $params );
		}


// </editor-fold>


// <editor-fold     defaultstate="collapsed"                        desc=" ==  Calendar Additional Settings  UI  == "  >

	/**
	 * Select-box - Number of Months to Scroll
	 *
	 * @return void
	 */
	function wpbc_ajx_cstm__ui__template__months_to_scroll(){

		$booking_action = 'set_booking_max_monthes_in_calendar';

		$el_id = 'ui_btn_cstm__' . $booking_action ;

		//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

		?><div class="ui_element ui_nowrap0"><?php

			wpbc_flex_label( array(  'id' 	  => $el_id
									, 'label' => '<span class="" style="font-weight:600;">' . __('Number of months to scroll' ,'booking') . ':</span>'
							) );
		?></div><?php
		?><div class="ui_element ui_nowrap"><?php

			//  Options
			$months_options = array();
			for ($mm = 1; $mm < 12; $mm++) { $months_options[ $mm . 'm' ] = $mm . ' ' .  __('month(s)' ,'booking'); }
			for ($yy = 1; $yy < 11; $yy++) { $months_options[ $yy . 'y' ] = $yy . ' ' .  __('year(s)' ,'booking');  }

			$params_select = array(
								  'id'       => $el_id 				// HTML ID  of element
								, 'name'     => $booking_action
								, 'label' => '' 				//__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
								, 'style'    => '' 					// CSS of select element
								, 'class'    => '' 					// CSS Class of select element
								//, 'multiple' => true
								//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
								, 'disabled' => false
								, 'disabled_options' => array()     								// If some options disabled, then it has to list here
								, 'options' => $months_options
								//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
								//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
								, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'calendar__booking_max_monthes_in_calendar', jQuery(this).val() );
										wpbc_ajx_customize_plugin__send_request_with_params(  {'do_action': 'save_calendar_additional'}  );
										wpbc_admin_show_message_processing( '' );									
										"
							  );
			wpbc_flex_select( $params_select );

			wpbc_ajx_cstm__ui__selectbox_prior_btn( $el_id );
			wpbc_ajx_cstm__ui__selectbox_next_btn( $el_id );
		?></div><?php

		// Set checked specific OPTION depends on last action from  user
		?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
			jQuery( document ).ready( function (){
				// Set selected option  in dropdown list based on  data. value
				jQuery( '#<?php echo $el_id; ?> option[value="' + data.ajx_data.calendar_settings.calendar__booking_max_monthes_in_calendar + '"]' ).prop( 'selected', true );
			} );

		<?php if (0) { ?></script><?php } ?> #><?php
	}


	/**
	 * Select-box - Start week day
	 *
	 * @return void
	 */
	function wpbc_ajx_cstm__ui__template__start_day_weeek(){

		$booking_action = 'set_booking_start_day_weeek';

		$el_id = 'ui_btn_cstm__' . $booking_action ;

		//if ( ! wpbc_is_user_can( $booking_action, wpbc_get_current_user_id() ) ) { 	return false; 	}

		?><div class="ui_element ui_nowrap0"><?php

			wpbc_flex_label( array(  'id' 	  => $el_id
									, 'label' => '<span class="" style="font-weight:600;">' . __('Start Day of the week' ,'booking') . ':</span>'
							) );
		?></div><?php
		?><div class="ui_element ui_nowrap"><?php

			//  Options
			$options =  array(
								  '0' => __('Sunday' ,'booking')
								, '1' => __('Monday' ,'booking')
								, '2' => __('Tuesday' ,'booking')
								, '3' => __('Wednesday' ,'booking')
								, '4' => __('Thursday' ,'booking')
								, '5' => __('Friday' ,'booking')
								, '6' => __('Saturday' ,'booking')
							);
			$params_select = array(
								  'id'       => $el_id 				// HTML ID  of element
								, 'name'     => $booking_action
								, 'label' => '' 				//__( 'Select the skin of the booking calendar', 'booking' )//__('Calendar Skin', 'booking')
								, 'style'    => '' 					// CSS of select element
								, 'class'    => '' 					// CSS Class of select element
								//, 'multiple' => true
								//, 'attr' => array( 'value_of_selected_option' => '{{selected_locale_value}}' )			// Any additional attributes, if this radio | checkbox element
								, 'disabled' => false
								, 'disabled_options' => array()     								// If some options disabled, then it has to list here
								, 'options' => $options
								//, 'value' => isset( $escaped_search_request_params[ $el_id ] ) ?  $escaped_search_request_params[ $el_id ]  : $defaults[ $el_id ]		// Some Value from options array that selected by default
								//, 'onfocus' =>  "console.log( 'ON FOCUS:', jQuery( this ).val(), 'in element:' , jQuery( this ) );"							// JavaScript code
								, 'onchange' => "wpbc_ajx_customize_plugin.search_set_param( 'calendar__booking_start_day_weeek', jQuery(this).val() );
										wpbc_ajx_customize_plugin__send_request_with_params(  {'do_action': 'save_calendar_additional'}  );
										wpbc_admin_show_message_processing( '' );									
										"
							  );
			wpbc_flex_select( $params_select );

			wpbc_ajx_cstm__ui__selectbox_prior_btn( $el_id );
			wpbc_ajx_cstm__ui__selectbox_next_btn( $el_id );
		?></div><?php

		// Set checked specific OPTION depends on last action from  user
		?><# <?php if (0) { ?><script type="text/javascript"><?php } ?>
			jQuery( document ).ready( function (){
				// Set selected option  in dropdown list based on  data. value
				jQuery( '#<?php echo $el_id; ?> option[value="' + data.ajx_data.calendar_settings.calendar__booking_start_day_weeek + '"]' ).prop( 'selected', true );
			} );

		<?php if (0) { ?></script><?php } ?> #><?php
	}

// </editor-fold>