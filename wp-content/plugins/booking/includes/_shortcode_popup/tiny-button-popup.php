<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Files Loading
 * @category Bookings
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 29.09.2015
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

//FixIn: 9.9.0.15

class WPBC_TinyMCE_Buttons {
    
    //                                                                              <editor-fold   defaultstate="collapsed"   desc=" I n i t    +    H o o k s" >
    private $settings = array();
    
    function __construct( $params ) {
        
        $this->settings = array(
                                  'tiny_prefix'         => 'wpbc_tiny'
                                , 'tiny_icon_url'       => WPBC_PLUGIN_URL . '/assets/img/bc_black-16x16.png'
                                , 'tiny_js_plugin'      => WPBC_PLUGIN_URL . '/js/wpbc_tinymce_btn.js'
                                , 'tiny_js_function'    => 'wpbc_init_tinymce_buttons'                     // This function NAME exist inside of this JS file: ['tiny_js_plugin']
                                , 'tiny_btn_row'        => 1
                                , 'pages_where_insert'  => array( 'post-new.php', 'page-new.php', 'post.php', 'page.php', 'widgets.php', 'customize.php'  )		//FixIn: 8.8.2.12
                                , 'buttons'             => array(
                                                          'booking_insert' => array(
                                                                                      'hint'  => __('Insert booking calendar' ,'booking')
                                                                                    , 'title' => __('Booking calendar' ,'booking')
                                                                                    , 'img'   => WPBC_PLUGIN_URL . '/assets/img/bc_black-16x16.png'
                                                                                    , 'class' => 'bookig_buttons'
                                                                                    , 'js_func_name_click'    => 'wpbc_tiny_btn_click'
                                                                                    , 'bookmark'              => 'booking'
                                                                                    , 'is_close_bookmark'     => 0
                                                                                )
                                                          )
                            );
        
        $this->settings = wp_parse_args( $params, $this->settings );
        
        add_action( 'init', array( $this, 'define_init_hooks' ) );   // Define init hooks
    }
    
    /** Init all hooks for showing Button in Tiny Toolbar */
    public function define_init_hooks() {

		if ( wpbc_can_i_load_on_this_page__shortcode_config() ) {

            // Load JS file  - TinyMCE plugin
            add_filter( 'mce_external_plugins', array( $this, 'load_tiny_js_plugin' ) );

            // Add the custom TinyMCE buttons
			if ( 1 === $this->settings['tiny_btn_row'] ) {
				add_filter( 'mce_buttons', array( $this, 'add_tiny_button' ) );
			} else {
				add_filter( 'mce_buttons_' . $this->settings['tiny_btn_row'], array( $this, 'add_tiny_button' ) );
			}
                                                                                    
            // Add the old style button to the non-TinyMCE editor views			//Fix: 8.4.2.10 - compatibility with Gutenberg 4.1- 4.3 ( or newer ) at edit post page.
            add_action( 'edit_page_form',       array( $this, 'add_html_button' ) );
            add_action( 'admin_head',           array( $this, 'insert_button') );
            add_action( 'admin_footer',         array( $this, 'modal_content' ) );
            
            // JS        
	        wp_enqueue_script( 'wpbc-modal', wpbc_plugin_url( '/assets/libs/ui/_out/dropdown_modal.js' ), array( 'jquery' ), WP_BK_VERSION_NUM );						//FixIn: 9.8.1

            // CSS
            wp_enqueue_style( 'wpdevelop-bts',              wpbc_plugin_url( '/assets/libs/bootstrap-css/css/bootstrap.css' ),          array(), WP_BK_VERSION_NUM );	//FixIn: 9.8.1
            wp_enqueue_style( 'wpdevelop-bts-theme',        wpbc_plugin_url( '/assets/libs/bootstrap-css/css/bootstrap-theme.css' ),    array(), WP_BK_VERSION_NUM );	//FixIn: 9.8.1
            
            wp_enqueue_style( 'wpbc-admin-support',         wpbc_plugin_url( '/core/any/css/admin-support.css' ),       array(), WP_BK_VERSION_NUM );
            wp_enqueue_style( 'wpbc-admin-menu',            wpbc_plugin_url( '/core/any/css/admin-menu.css' ),          array(), WP_BK_VERSION_NUM );
          //wp_enqueue_style( 'wpbc-admin-toolbar',         wpbc_plugin_url( '/core/any/css/admin-toolbar.css' ),     array(), WP_BK_VERSION_NUM );
			wp_enqueue_style( 'wpbc-flex-toolbar', 			wpbc_plugin_url( '/includes/_toolbar_ui/_src/toolbar_ui.css' ), array(), WP_BK_VERSION_NUM );
            wp_enqueue_style( 'wpbc-admin-modal-popups',    wpbc_plugin_url( '/css/modal.css' ),                        array(), WP_BK_VERSION_NUM);
            wp_enqueue_style( 'wpbc-admin-pages',           wpbc_plugin_url( '/css/admin.css' ),                        array(), WP_BK_VERSION_NUM);
            wp_enqueue_style( 'wpbc-admin-skin',            wpbc_plugin_url( '/css/admin-skin.css' ),                   array( 'wpbc-admin-pages' ), WP_BK_VERSION_NUM);            //FixIn: 8.0.2.4
			if ( 'legacy' != get_bk_option( 'booking_admin_panel_skin' ) ) {                                            //FixIn: 9.5.5.7
		        wp_enqueue_style( 'wpbc-admin-skin-modern_1', wpbc_plugin_url( '/css/admin-skin-modern_1.css' ), array( 'wpbc-admin-skin' ), WP_BK_VERSION_NUM );       //FixIn: 9.5.5.1
	        }

			add_action( 'customize_controls_print_footer_scripts', array( $this, 'add_html_button' ) );					//FixIn: 8.8.2.12
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'insert_button' ) );
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'modal_content' ) );
        }            
    }
    //                                                                              </editor-fold>
                                                                                  
                                                                                  
    //                                                                              <editor-fold   defaultstate="collapsed"   desc=" TinyMCE - Add Button " >    

    /**
	 * Load JS file  - TinyMCE plugin
     * 
     * @param array $plugins
     * @return array
     */
    public function load_tiny_js_plugin( $plugins ){
    
        $plugins[ $this->settings['tiny_prefix'] . '_quicktags'] = $this->settings['tiny_js_plugin'];
        
        return $plugins;
    }
    
    
    /**
	 * Add the custom TinyMCE buttons
     * 
     * @param array $buttons
     * @return array
     */
    public function add_tiny_button( $buttons ) {
                
        array_push( $buttons, "separator" );
        
        foreach ( $this->settings['buttons'] as $type => $strings ) {
            array_push( $buttons, $this->settings['tiny_prefix'] . '_' . $type );
        }

        return $buttons;        
    }
    
    
    /** Add the old style button to the non-TinyMCE editor views */
    public function add_html_button() {
        
        $buttonshtml = '';
        $datajs='';
        
        foreach ( $this->settings['buttons'] as $type => $props ) {

            $buttonshtml .= '<input type="button" class="ed_button button button-small" onclick="'
                                .$props['js_func_name_click'].'(\'' . $type . '\')" title="' . $props['hint'] . '" value="' . $props['title'] . '" />';

            $datajs.= " wpbc_tiny_btn_data['$type'] = {\n";
            $datajs.= '		title: "' . esc_js( $props['title'] ) . '",' . "\n";
            $datajs.= '		tag: "' . esc_js( $props['bookmark'] ) . '",' . "\n";
            $datajs.= '		tag_close: "' . esc_js( $props['is_close_bookmark'] ) . '",' . "\n";
            $datajs.= '		cal_count: "' . get_bk_option( 'booking_client_cal_count' )  . '"' . "\n";
            $datajs.=  "\n	};\n";
        }
        
        ?><script type="text/javascript">
            // <![CDATA[
            
                function wpbc_add_html_button_to_toolbar(){                     // Add buttons  ( HTML view )
                    if ( jQuery( '#ed_toolbar' ).length == 0 ) 
                        setTimeout( 'wpbc_add_html_button_to_toolbar()' , 100 );
                    else 
                        jQuery("#ed_toolbar").append( '<?php echo wp_specialchars_decode( esc_js( $buttonshtml ), ENT_COMPAT ); ?>' );
                }                
                jQuery(document).ready(function(){ 
                    setTimeout( 'wpbc_add_html_button_to_toolbar()' , 100 );
                });

                var selected_booking_shortcode = 'bookingform';
                var wpbc_tiny_btn_data={};
                <?php echo $datajs; ?>
                
            // ]]>
        </script><?php
        
    }
    
    
    public function insert_button() {
        
        $script = '';

		// calendar3-range		https://icons.getbootstrap.com/icons/calendar3-range/
		$svg_icon_integarted = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar3-range" viewBox="-2 -1 20 20">'
								  . '<path d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>'
								  . '<path d="M7 10a1 1 0 0 0 0-2H1v2h6zm2-3h6V5H9a1 1 0 0 0 0 2z"/>'
								. '</svg>';
		$mune_icon_url = sprintf( 'data:image/svg+xml;base64,%s', base64_encode( $svg_icon_integarted ) );


        if ( ! empty( $this->settings['buttons'] ) ){
            
            $script .= '<script type="text/javascript">';

            $script .= ' function '. $this->settings['tiny_js_function'] . '(ed, url) {';

                foreach ( $this->settings['buttons'] as $type => $props ) {

                    $script .=  " if ( typeof ".$props['js_func_name_click']." == 'undefined' ) return; ";
                    $script .=  "  ed.addButton('".  $this->settings['tiny_prefix'] . '_' . $type ."', {";
                    $script .=  "		title : '". $props['hint'] ."',";
                    //$script .=  "		image : '". $props['img'] ."',";
	                $script .= "		image : '" . $mune_icon_url . "',";
                    $script .=  "		onclick : function() {";
                    $script .=  "			". $props['js_func_name_click'] ."('". $type ."');";
                    $script .=  "		}";
                    $script .=  "	});";
                }
            $script .=  ' }';

            $script .= '</script>';
            
            echo $script;
        }        
    }
    
    //                                                                              </editor-fold>
    
    
    //                                                                              <editor-fold   defaultstate="collapsed"   desc=" M o d a l    C o n t e n t " >    
    public function modal_content() {
        
        ?><span class="wpdevelop wpbc_page"><div class="visibility_container clearfix-height" style="display:block;"><?php
        ?><div id="wpbc_tiny_modal" class="modal wpbc_popup_modal wpbc_tiny_modal" tabindex="-1" role="dialog">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">   
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">
							<span>WP Booking Calendar</span>
							<span style="padding: 0 1em;"> / </span>
							<span>
							<?php
							_e( 'Shortcode Configuration', 'booking' );
                        ?></span></h4>
                    </div>
                    <div class="modal-body">

						<div class="wpbc_settings_flex_container">

							<div class="wpbc_settings_flex_container_left" style="flex: 1 1 150px;">
								<?php
									wpbc_shortcode_config__navigation_panel();
								?>
							</div>
							<div class="wpbc_settings_flex_container_right">
								<div class="wpbc_sc_container__all_shortcodes">
									<input name="wpbc_shortcode_type" id="wpbc_shortcode_type" value="booking" autocomplete="off" type="hidden" />
									<?php

									// [booking ... ] ------------------------------------------------------------------
									wpbc_shortcode_config__content__booking();

									// [bookingcalendar ... ] ----------------------------------------------------------
									wpbc_shortcode_config__content__bookingcalendar();

									// [bookingselect ... ] ------------------------------------------------------------
									wpbc_shortcode_config__content__bookingselect();

									// [bookingtimeline ... ] ----------------------------------------------------------
									wpbc_shortcode_config__content__bookingtimeline();

									// [bookingsearch searchresults='https://servcer.com/search-form/' searchresultstitle='{searchresults} Result(s) Found' noresultstitle='Nothing Found']
									wpbc_shortcode_config__content__bookingsearch();

									// [bookingform type=1 selected_dates='03.03.2024'] --------------------------------
									wpbc_shortcode_config__content__bookingform();

									// [bookingedit] , [bookingcustomerlisting] , [bookingresource type=6 show='capacity'] , [booking_confirm]
									wpbc_shortcode_config__content__bookingother();

									// Booking Import of .ics feed shortcode
									wpbc_shortcode_config__content__booking_ics_import();

									// Booking Listing of .ics feed shortcode
									wpbc_shortcode_config__content__booking_ics_listing();

									?>
								</div>
								<div style="display: flex;flex-flow: row wrap;justify-content: flex-start;align-items: baseline;" for="wpbc_text_put_in_shortcode">
									<input name="wpbc_text_put_in_shortcode" id="wpbc_text_put_in_shortcode" class="put-in" readonly="readonly" onfocus="this.select()" type="text"
										   style="width: auto;flex: 1;" />
								</div>
								<input name="wpbc_text_gettenberg_section_id" id="wpbc_text_gettenberg_section_id" type="text" style="display: none;" />
							</div>
						</div>

                    </div>
                    <div class="modal-footer" style="text-align:center;">
						<div class="wpbc_shortcode_config_content_toolbar__container">
							<div class="wpbc_shortcode_config_content_toolbar__reset">
								<a href="javascript:void(0)" class="button button-primary wpbc_tiny_button__reset_to_resource wpbc_ui_button_danger"
								   onclick="javascript:wpbc_shortcode_config__reset( jQuery('#wpbc_shortcode_type').val().trim() );" ><?php _e( 'Reset' ); ?></a>
							</div>
							<div class="wpbc_shortcode_config_content_toolbar__insert">
								<a href="javascript:void(0)" class="button button-primary wpbc_tiny_button__insert_to_editor"    style="float:none;"
								   onclick="javascript:wpbc_send_text_to_editor( jQuery('#wpbc_text_put_in_shortcode').val().trim() );wpbc_tiny_close();"><?php    _e( 'Insert' ); ?></a>
								<a href="javascript:void(0)" class="button button-primary wpbc_tiny_button__insert_to_resource"  style="float:none;display:none;"
								   onclick="javascript:wpbc_send_text_to_resource( jQuery('#wpbc_text_put_in_shortcode').val().trim() );wpbc_tiny_close();" ><?php _e( 'Insert' ); ?></a>
								<!--a href="javascript:void(0)" class="button" style="float:none;" data-dismiss="modal"><?php _e('Close' ,'booking'); ?></a-->
							</div>
						</div>
                   </div>
                </div><!-- /.modal-content -->
              </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <?php  
        ?></div></span><?php

    }
    //                                                                              </editor-fold>
    

}


$wpbc_pages_where_insert_btn = array( 'post-new.php', 'page-new.php', 'post.php', 'page.php', 'widgets.php', 'customize.php' );            //FixIn: 8.8.2.11		//FixIn: 8.8.2.12

if (
		( in_array( basename( $_SERVER['PHP_SELF'] ), $wpbc_pages_where_insert_btn ) )
	 || (
		   ( isset( $_REQUEST['page'] )
	   	&& ( $_REQUEST['page'] == 'wpbc-resources' ) )                  // Check  if this Booking > Resources page
		)
 ){
    new WPBC_TinyMCE_Buttons( 
                            array(
                                      'tiny_prefix'     => 'wpbc_tiny'
                                    , 'tiny_icon_url'   => WPBC_PLUGIN_URL . '/assets/img/bc_black-16x16.png'
                                    , 'tiny_js_plugin'  => WPBC_PLUGIN_URL . '/js/wpbc_tinymce_btn.js'
                                    , 'tiny_js_function' => 'wpbc_init_tinymce_buttons'                     // This function NAME exist inside of this file: ['tiny_js_plugin']
                                    , 'tiny_btn_row'    => 1
                                    , 'pages_where_insert' => $wpbc_pages_where_insert_btn
                                    , 'buttons'            => array(
                                                                'booking_insert' => array(
                                                                                              'hint'  => __('Insert WP Booking Calendar' ,'booking')
                                                                                            , 'title' => __('Booking calendar' ,'booking')
                                                                                            , 'img'   => WPBC_PLUGIN_URL . '/assets/img/bc_black-16x16.png'
                                                                                            , 'class' => 'bookig_buttons'
                                                                                            , 'js_func_name_click'    => 'wpbc_tiny_btn_click'
                                                                                            , 'bookmark'              => 'booking'
                                                                                            , 'is_close_bookmark'     => 0
                                                                                        )
                                                                )
                            )
                        );
}