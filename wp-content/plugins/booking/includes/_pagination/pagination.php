<?php /**
 * @version 1.0
 * @description Pagination
 * @category  Pagination Class
 * @author wpdevelop
 *
 * @web-site http://oplugins.com/
 * @email info@oplugins.com
 *
 * @modified 2020-01-23
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/*
 * Usage:  Pagination Class

	            <div class="wpbc_rules_pagination"></div>
				<?php
				$wpbc_pagination = new WPBC_Pagination();
				$wpbc_pagination->init(
										array(
											'load_on_page'  => 'wpbc-rules',
											'container'     => '.wpbc_rules_pagination',
											'on_click'	    => 'wpbc_rules_pagination_click'		// onclick = "javascript: wpbc_rules_listing_page( page_num );"  - need to  define this function in JS file
										)
				);
				$wpbc_pagination->show( 													        // Its showing with  JavaScript on document ready
										array(
											'page_active' => 3,
											'pages_count' => 20
										)
				);

OR (for showing with   JavaScript) :

	<script type="text/javascript">
		jQuery( document ).ready( function (){

			wpbc_pagination_echo( '.wpbc_rules_pagination',
									{
										'page_active': page_number,
										'pages_count': Math.ceil( ajx_count / ajx_page_items_count )
									}
								);
		} );
	</script>

 */


class WPBC_Pagination {

	private $settings;

	/**
	 * Get parameter Value
	 *
	 * @param string $parameter	- name of parameter
	 *
	 * @return mixed
	 */
	public function get_settings( $parameter ){

		if ( ! empty( $this->settings[ $parameter ] ) ) {
			return $this->settings[ $parameter ];
		} else {
			return false;
		}
	}


	// <editor-fold     defaultstate="collapsed"                        desc=" ///  JS | CSS files   /// "  >
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// JS | CSS files
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Define HOOKs for loading CSS and  JavaScript files
	 */
	public function init_load_css_js() {
		// JS & CSS
		add_action( 'wpbc_enqueue_js_files',  array( $this, 'js_load_files' ),     50  );
		add_action( 'wpbc_enqueue_css_files', array( $this, 'enqueue_css_files' ), 50  );
	}

	/** JSS */
	public function js_load_files( $where_to_load ) {

		$in_footer = true;

		if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

			//wp_enqueue_script( 'wpbc-live_search', wpbc_plugin_url( '/_out/js/live_search.js' ), array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );
			wp_enqueue_script( 'wpbc-pagination'
				, trailingslashit( plugins_url( '', __FILE__ ) ) . 'pagination.js'         /* wpbc_plugin_url( '/_out/js/codemirror.js' ) */
				, array( 'wpbc-global-vars' ), WP_BK_VERSION_NUM, $in_footer );

			/**
			wp_localize_script( 'wpbc-global-vars', 'wpbc_live_request_obj'
								, array(
										'contacts'  => '',
										'reminders' => ''
									)
			);
		 	*/
		}
	}

	/** CSS */
	public function enqueue_css_files( $where_to_load ) {

		if ( ( is_admin() ) && ( in_array( $where_to_load, array( 'admin', 'both' ) ) ) ) {

			wp_enqueue_style( 'wpbc-pagination', trailingslashit( plugins_url( '', __FILE__ ) ) . 'pagination.css', array(), WP_BK_VERSION_NUM );
		}
	}

	// </editor-fold>


	// <editor-fold     defaultstate="collapsed"                        desc=" ///  Templates  /// "  >
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/// Templates
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function init_load_templates() {

		add_action( 'wpbc_hook_settings_page_footer', array( $this, 'hook__page_footer_tmpl' ) );
	}


	/**
	 * Templates at footer of page
	 *
	 * @param $page string
	 */
	public function hook__page_footer_tmpl( $page ){

		if ( $this->get_settings( 'load_on_page' ) === $page ) {

			$this->template__pagination();
		}
	}


	private function template__pagination(){

		// Pagination
		?><script type="text/html" id="tmpl-wpbc_pagination">
			<div class="wpbc-ajax-pagination wpbc-ajax-pagination-container">
				<# if ( data.pages_count > 1 ) { #>
				<div class="ui_element">
					<a class="wpbc_ui_control wpbc_ui_button <# if ( 1 == data.page_active ) { #> disabled<# } #>"
					   href="javascript:void(0)"
						<# if ( 1 != data.page_active ) { #> onclick="javascript:<?php echo $this->get_settings( 'on_click' ); ?>( parseInt(  {{ data.page_active }} ) - 1 );" <# } #>
					 >
						<?php _e('Prev', 'booking'); ?>
					</a>
				</div>
				<# }

					/** Number visible pages (links) that linked to active page, other pages skipped by "..." */
					var num_closed_steps = 2;

					for ( var pg_num = 1; pg_num <= data.pages_count; pg_num++ ){

						if ( ! (
								   ( data.pages_count > ( num_closed_steps * 4) )
								&& ( pg_num > num_closed_steps )
								&& ( ( data.pages_count - pg_num + 1 ) > num_closed_steps )
								&& (  Math.abs( data.page_active - pg_num ) > num_closed_steps )
						   ) )
						{
							#> <div class="ui_element"><a  class="wpbc_ui_control wpbc_ui_button <# if ( pg_num == data.page_active ) { #> active<# } #>"
								href="javascript:void(0)" onclick="javascript:<?php echo $this->get_settings( 'on_click' ); ?>( {{pg_num}} );" >
						{{pg_num}}</a></div> <#

									if ( ( data.pages_count > ( num_closed_steps * 4) )
									&& ( (pg_num+1) > num_closed_steps )
									&& ( ( data.pages_count - ( pg_num + 1 ) ) > num_closed_steps )
									&& ( Math.abs(data.page_active - ( pg_num + 1 ) ) > num_closed_steps )
									) {
										#><div class="ui_element"><a class="wpbc_ui_control wpbc_ui_button disabled" href="javascript:void(0);">...</a></div><#
									}
						 }
					}

				if ( data.pages_count > 1 ) { #>
				<div class="ui_element">
					<a 	class="wpbc_ui_control wpbc_ui_button <# if ( data.pages_count == data.page_active ) { #> disabled<# } #>"
						href="javascript:void(0)"
						<# if ( data.pages_count != data.page_active ) { #> onclick="javascript:<?php echo $this->get_settings( 'on_click' ); ?>( parseInt(  {{ data.page_active }} ) + 1 );" <# } #>
					>
						<?php _e('Next', 'booking'); ?>
					</a>
				</div>
				<# } #>
			</div>
		</script><?php

		// Pagination Items per page
		?><script type="text/html" id="tmpl-wpbc_pagination_items_per_page">
			<div class="wpbc-ajax-pagination_items_per_page">
				<div class="ui_element">
					<select class="wpbc_items_per_page wpbc_ui_control wpbc_ui_select " autocomplete="off">
						<#
							var my_options_arr = [5, 10, 50, 100];
							var is_selected = '';
							_.each( my_options_arr, function ( p_val, p_key, p_data ) {
								 is_selected = '';
								 if ( data.page_items_count == p_val ) {
									is_selected = ' selected="selected" ';
								 }
								 #><option value="{{p_val}}" {{is_selected}}>{{p_val}}</option><#
							});
						#>
					</select>
				</div>
				<div class="ui_element">
					<label class="wpbc_ui_control_label"><?php _e('per page','booking') ?></label>
				</div>
				<?php

				if (0) {
				?>
				<div class="ui_element">
					<select class="wpbc_items_sort_type wpbc_ui_control wpbc_ui_select" autocomplete="off">
						<#
							my_options_arr = {
												'ASC':  '<?php echo esc_js(__( 'ASC', 'booking' )); ?>',
												'DESC': '<?php echo esc_js(__( 'DESC', 'booking' )); ?>',
											 };
							is_selected = '';
							_.each( my_options_arr, function ( p_val, p_key, p_data ) {
								 is_selected = '';
								 if ( data.sort_type == p_key ) {
									is_selected = ' selected="selected" ';
								 }
								 #><option value="{{p_key}}" {{is_selected}}>{{p_val}}</option><#
							});
						#>
					</select>
				</div>
				<?php } ?>
			</div>
		</script><?php
	}

	// </editor-fold>


	/**
	 * Init Pagination on start - define 'load_on_page', 'container', 'on_click' function
	 *
	 * @param array $params = array(
											'load_on_page'  => 'wpbc-settings',					// defined at 	function in_page() {
											'container'     => '.wpbc_settings_pagination',		// defined in 	function content(),  	like		<div class="wpbc_rules_pagination"></div>
											'on_click'	    => 'wpbc_pagination_click_page'		// onclick = "javascript: wpbc_pagination_click_page( page_active );"  - need to  define this function in JS file
									);
	 */
	public function init( $params = array() ) {

		$defaults = array(
							'load_on_page'  => 'wpbc-settings',					// defined at 	function in_page() {
							'container'     => '.wpbc_settings_pagination',		// defined in 	function content(),  	like		<div class="wpbc_rules_pagination"></div>
							'on_click'	    => 'wpbc_pagination_click_page'		// onclick = "javascript: wpbc_pagination_click_page( page_active );"  - need to  define this function in JS file
					);
		$this->settings   = wp_parse_args( $params, $defaults );

		$this->init_load_templates();
	}


	/**
	 * Show pagination
	 *
	 * @param array $params = array(
										'page_active' => 1,
										'pages_count' => 10
								)
	 */
	public function show( $params = array() ) {
		$defaults = array(
							'page_active' => 1,
							'pages_count' => 1
					);
		$params   = wp_parse_args( $params, $defaults );
		?>
		<script type="text/javascript">

			jQuery( document ).ready( function (){

				wpbc_pagination_echo( '<?php echo $this->get_settings( 'container' ); ?>', <?php
					echo wp_json_encode(
						array(
							'page_active' => $params['page_active'],
							'pages_count' => $params['pages_count']
						)
					);
					?> );
			} );
		</script>
		<?php
	}
}

/**
 * Just for loading CSS and  JavaScript files for all  Settings pages
 */
 if ( true ) {
	$js_css_loading = new WPBC_Pagination;
	$js_css_loading->init_load_css_js();
 }


//TODO: delete ../email-reminders/_src/css/o-pagination.css