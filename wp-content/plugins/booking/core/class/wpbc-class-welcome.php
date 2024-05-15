<?php
/**
 * Welcome Page Class
 * Shows a feature overview for the new version (major).
 * Adapted from code in EDD (Copyright (c) 2012, Pippin Williamson) and WP.
 * @version     2.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

require_once WPBC_PLUGIN_DIR . '/core/class/welcome_current.php';
require_once WPBC_PLUGIN_DIR . '/core/class/welcome_old.php';


class WPBC_Welcome {

    public $minimum_capability = 'read';    //'manage_options';
    
    public $asset_path = 'https://wpbookingcalendar.com/assets/';
    //public $asset_path = 'http://beta/assets/';


    public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menus' ) );
        //add_action( 'admin_head', array( $this, 'admin_head' ) );	//FixIn: 8.5.1.2
        add_action( 'admin_init', array( $this, 'welcome' ) );

        add_action( 'wpbc_premium_content_overview', array( $this, 'content_premium' ) );

	    add_action( 'load-dashboard_page_wpbc-about',           array( $this, 'wpbc_define_page_title_about' ) );       //FixIn: 9.6.2.12
	    add_action( 'load-dashboard_page_wpbc-getting-started', array( $this, 'wpbc_define_page_title_started' ) );
	    add_action( 'load-dashboard_page_wpbc-about-premium',   array( $this, 'wpbc_define_page_title_premium' ) );
    }

	public function wpbc_define_page_title_about() {    //FixIn: 9.6.2.12
		global $title;
		if ( ! isset( $title ) ) {
			$title = 'Welcome to Booking Calendar';
		}
	}
	public function wpbc_define_page_title_started() {  //FixIn: 9.6.2.12
		global $title;
		if ( ! isset( $title ) ) {
			$title = 'Get Started - Booking Calendar';
		}
	}
	public function wpbc_define_page_title_premium() {  //FixIn: 9.6.2.12
		global $title;
		if ( ! isset( $title ) ) {
			$title = 'Get Premium - Booking Calendar';
		}
	}

    private function css() {
        
        ?><style type="text/css">
			.feature-section.three-col,
			.feature-section.two-col {
				display: flex;
				flex-direction: row;
				justify-content: space-between;
				align-items: flex-start;
			}
			.feature-section.two-col .col.col-1{
				width:100%;
			}
			.feature-section.two-col .col.col-2{
				width: 70%;
				padding: 0 0 0 7%;
				box-sizing: border-box;
			}
			@media (max-width: 782px) {
				/* iPad mini and all iPhones  and other Mobile Devices */
				.feature-section.three-col,
				.feature-section.two-col {
					display: block;
				}
			}

            /* Welcome Page ***************************************************************/
            .wpbc-welcome-page .about-text {
                margin-right:0px;
                margin-bottom:0px;
                min-height: 50px;
            }
            .wpbc-welcome-page .wpbc-section-image {
                border:none;
                box-shadow: 0 1px 3px #777777;   
            }
            .wpbc-welcome-page .versions {
                color: #999999;
                font-size: 12px;
                font-style: normal;
                margin: 0;
                text-align: right;
                text-shadow: 0 -1px 0 #EEEEEE;
            }
            .wpbc-welcome-page .versions a,
            .wpbc-welcome-page .versions a:hover{
                color: #999;
                text-decoration:none;
            }
            .wpbc-welcome-page .update-nag {
                border-color: #E3C58E;
                border-radius: 5px;
                -moz-border-radius: 5px;
                -webkit-border-radius: 5px;
                box-shadow: 0 1px 3px #EEEEEE;
                color: #998877;
                font-size: 12px;
                font-weight: 600;
                margin: 15px 0 0;   
                width:90%;
            }
            .wpbc-welcome-page .feature-section {
                margin-top:20px;
                border:none;                
            }
            .wpbc-welcome-page .feature-section div {
                line-height: 1.5em;
            }
            .about-wrap.wpbc-welcome-page .feature-section .last-feature {
                margin-right:0;
            }
            .about-wrap.wpbc-welcome-page .changelog {
                margin-bottom: 10px;
            }
            .about-wrap.wpbc-welcome-page .feature-section h4 {
                font-size: 1.2em;
                margin-bottom: 0.6em;
                margin-left: 0;
                margin-right: 0;
                margin-top: 1.4em;
            }
            .about-wrap.wpbc-welcome-page .feature-section {
                overflow-x: hidden;
                overflow-y: hidden;
                padding-bottom: 20px;
            }
			.about-wrap.wpbc-welcome-page [class$="-col"]{
				align-items: initial;
			}
            @media (max-width: 782px) {      /* iPad mini and all iPhones  and other Mobile Devices */
                .wpbc-welcome-page .feature-section.one-col > div, 
                .wpbc-welcome-page .feature-section.three-col > div, 
                .wpbc-welcome-page .feature-section.two-col > div {
                    border-bottom: none;
                    margin:0px !important;
                }
                .wpbc-welcome-page .feature-section img{
                    /*width:98% !important;*/
                    /*margin:0 1% !important;*/
	                margin:0!important;
	                width: auto !important;
					max-height: 285px;
					overflow: hidden;
                }
				.about-wrap.wpbc-welcome-page .feature-section div.col {
					display:block;
					float:none;
					width: 100% !important;
					padding: 0;
				}
            }
			.wpbc-welcome-page li.big_numbers::marker{
				font-size:1.2em;
				font-weight: 600;
				line-height: 2.1em;
			}

        </style><?php
    }
    // SUPPORT /////////////////////////////////////////////////////////////////

        public function show_separator() {
            ?><div class="clear" style="height:1px;border-bottom:1px solid #DFDFDF;"></div><?php
        }


        public function show_header( $text = '' , $header_type = 'h3', $style = '' ) {
            echo '<' , $header_type  ;
            if ( ! empty($style) )
                echo " style='{$style}'";
            echo '>';    
            echo wpbc_replace_to_strong_symbols( $text ); 
            echo '</' , $header_type , '>' ;
        }


        public function show_col_section( $sections_array = array( ) ) {

            $columns_num = count( $sections_array );

            if ( isset($sections_array['h3'] ) )
                $columns_num--;
            if ( isset($sections_array['h2'] ) )
                $columns_num--;
            ?>
            <div class="changelog"><?php 

                if ( isset( $sections_array[ 'h3' ] ) ) {
                    echo "<h3>" . wpbc_replace_to_strong_symbols( $sections_array[ 'h3' ] ) . "</h3>";
                    unset($sections_array[ 'h3' ]);
                }
                if ( isset( $sections_array[ 'h2' ] ) ) {
                    echo "<h2>" . wpbc_replace_to_strong_symbols( $sections_array[ 'h2' ] ) . "</h2>";
                    unset($sections_array[ 'h2' ]);
                }

                ?><div class="feature-section <?php 
                        if ( $columns_num == 2 ) {
                            echo ' two-col';
                        } if ( $columns_num == 3 ) {
                            echo ' three-col';
                        } ?>">
                    <?php
                    foreach ( $sections_array as $section_key => $section ) {
                        $col_num = ( $section_key + 1 );
                        if ( $columns_num == $col_num )
                            $is_last_feature = ' last-feature ';
                        else
                            $is_last_feature = '';

                        echo "<div class='col col-{$col_num}{$is_last_feature}'>";

                        if ( isset( $section[ 'header' ] ) ) 
                            echo "<h4>" . wpbc_replace_to_strong_symbols( $section[ 'header' ] ) . "</h4>";

                        if ( isset( $section[ 'h4' ] ) ) 
                            echo "<h4>" . wpbc_replace_to_strong_symbols( $section[ 'h4' ] ) . "</h4>";

                        if ( isset( $section[ 'h3' ] ) ) 
                            echo "<h3>" . wpbc_replace_to_strong_symbols( $section[ 'h3' ] ) . "</h3>";

                        if ( isset( $section[ 'h2' ] ) ) 
                            echo "<h2>" . wpbc_replace_to_strong_symbols( $section[ 'h2' ] ) . "</h2>";

                        if ( isset( $section[ 'text' ] ) ) 
                            echo wpbc_replace_to_strong_symbols( $section[ 'text' ] );

                        if ( isset( $section[ 'img' ] ) ) {                         

							$is_full_link = strpos( $section[ 'img' ], 'http' );
							if ( false === $is_full_link ) {
								echo '<img src="' . $this->asset_path . $section['img'] . '" ';
							} else {
								echo '<img src="' . $section[ 'img' ] . '" ';
							}
                            if ( isset( $section[ 'img_style' ] ) ) 
                                echo ' style="'. $section[ 'img_style' ] .'" ';
                            echo ' class="wpbc-section-image" />' ;    
                        }

                        echo "</div>";
                    }
                    ?>        
                </div>                    
            </div>
            <?php
        }

        
        public function get_img( $img, $img_style = '' ) {

			$is_full_link = strpos( $img, 'http' );
			if ( false === $is_full_link ) {
				$img_result = '<img src="' . $this->asset_path . $img  . '" ';
			} else {
				$img_result = '<img src="' . $img  . '" ';
			}

            if ( ! empty( $img_style ) ) 
                $img_result .= ' style="'. $img_style .'" ';
            $img_result .= ' class="wpbc-section-image" />' ;    
            
            return $img_result;
        }
    ////////////////////////////////////////////////////////////////////////////
        
    // Menu    
    public function admin_menus() {
        // What's New
        add_dashboard_page(
                sprintf( 'Welcome to Booking Calendar' ),
                sprintf( 'What\'s New' ),
                $this->minimum_capability, 'wpbc-about',
                array( $this, 'content_whats_new' )
        );
        // Getted Started
        add_dashboard_page(
                sprintf( 'Get Started - Booking Calendar' ),
                sprintf( 'Get Started' ),
                $this->minimum_capability, 'wpbc-getting-started',
                array( $this, 'content_getted_started' )
        );
        // Pro
        add_dashboard_page(
                sprintf( 'Get Premium - Booking Calendar' ),
                sprintf( 'Get Premium' ),
                $this->minimum_capability, 'wpbc-about-premium',
                array( $this, 'content_premium' )
        );
        //FixIn: 8.5.1.2
 		remove_submenu_page( 'index.php', 'wpbc-about' );
        remove_submenu_page( 'index.php', 'wpbc-getting-started' );
        remove_submenu_page( 'index.php', 'wpbc-about-premium' );
    }

    // Head
    public function admin_head() {
        remove_submenu_page( 'index.php', 'wpbc-about' );
        remove_submenu_page( 'index.php', 'wpbc-getting-started' );
        remove_submenu_page( 'index.php', 'wpbc-about-premium' );
    }

    // Title
    public function title_section() {
        list( $display_version ) = explode( '-', WPDEV_BK_VERSION );
        ?>
        <h1><?php printf( 'Welcome to Booking Calendar %s', $display_version ); ?></h1>
        <div class="about-text"><?php
        //echo('Thank you for updating to the latest version!'); 
        // printf(  '%s is more polished, powerful and easy to use than ever before.' , ' Booking Calendar ' . $display_version ); 
        // printf(  '%s has become more powerful and flexible in configuration and easy to use than ever before.' , '<br/>Booking Calendar ');
        printf( 'Booking Calendar is ready to receive and manage bookings from your visitors!' );
        ?></div>


        <h2 class="nav-tab-wrapper">
        <?php
        $is_about_tab_active = $is_about_premium_tab_active = $is_getting_started_tab_active = '';
        if ( ( isset( $_GET[ 'page' ] ) ) && ( $_GET[ 'page' ] == 'wpbc-about' ) )
            $is_about_tab_active = ' nav-tab-active ';
        if ( ( isset( $_GET[ 'page' ] ) ) && ( $_GET[ 'page' ] == 'wpbc-about-premium' ) )
            $is_about_premium_tab_active = ' nav-tab-active ';
        if ( ( isset( $_GET[ 'page' ] ) ) && ( $_GET[ 'page' ] == 'wpbc-getting-started' ) )
            $is_getting_started_tab_active = ' nav-tab-active ';
        ?>
            <a class="nav-tab<?php echo $is_about_tab_active; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array(
            'page' => 'wpbc-about' ), 'index.php' ) ) ); ?>">
                    <?php echo( "What's New" ); ?>
                <a class="nav-tab<?php echo $is_getting_started_tab_active; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array(
                'page' => 'wpbc-getting-started' ), 'index.php' ) ) ); ?>">
        <?php echo( "Get Started" ); ?>
                </a><a class="nav-tab<?php echo $is_about_premium_tab_active; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array(
            'page' => 'wpbc-about-premium' ), 'index.php' ) ) ); ?>">
        <?php echo( "Get even more functionality" ); // echo( "Even more Premium Features" ); ?>
                </a>
        </h2>                
        <?php
    }

    // Maintence section
    public function maintence_section() {

        if ( !( ( defined( 'WP_BK_MINOR_UPDATE' )) && (WP_BK_MINOR_UPDATE) ) )
            return;

        list( $display_version ) = explode( '-', WPDEV_BK_VERSION );
        ?>
        <div class="changelog point-releases" style="margin-top: 45px;">
            <h3><?php echo( "Maintenance Release" ); ?></h3>
            <p><strong><?php printf( 'Version %s',
                $display_version ); ?></strong> <?php printf( 'addressed some minor issues and improvement in functionality',
                '' ); ?>. 
        <?php printf( 'For more information, see %sthe release notes%s',
                '<a href="https://wpbookingcalendar.com/changelog/" target="_blank">',
                '</a>' ) ?>.
            </p>
        </div>                        
        <?php
    }

    // Start
    public function welcome() {

        $booking_activation_process = get_bk_option( 'booking_activation_process' );
        if ( $booking_activation_process == 'On' )
            return;

        // Bail if no activation redirect transient is set
        if ( ! get_transient( '_booking_activation_redirect' ) )
            return;

        // Delete the redirect transient
        delete_transient( '_booking_activation_redirect' );

        // Bail if DEMO or activating from network, or bulk, or within an iFrame
        if ( wpbc_is_this_demo() || is_network_admin() || isset( $_GET[ 'activate-multi' ] ) || defined( 'IFRAME_REQUEST' ) )
            return;

        // Set mark,  that  we already redirected to About screen               //FixIn: 5.4.5
        $redirect_for_version = get_bk_option( 'booking_activation_redirect_for_version' );
        if ( $redirect_for_version == WP_BK_VERSION_NUM )
            return;
        else
            update_bk_option( 'booking_activation_redirect_for_version', WP_BK_VERSION_NUM );
        
        wp_safe_redirect( admin_url( 'index.php?page=wpbc-about' ) );
        exit;
    }


    // CONTENT /////////////////////////////////////////////////////////////////
    
    public function content_whats_new() {

        //$this->css();
if(0){
        ?>
		<style type="text/css">
			.feature-section.two-col {
				display: flex;
				flex-direction: row;
				justify-content: space-between;
				align-items: flex-start;
			}
			.feature-section.two-col .col.col-1{
				width:100%;
			}
			.feature-section.two-col .col.col-2{
				width: 70%;
				padding: 0 0 0 7%;
				box-sizing: border-box;
			}
			@media (max-width: 782px) {
				/* iPad mini and all iPhones  and other Mobile Devices */
				.feature-section.two-col {
					display: block;
				}
			}

			.wpbc-changelog-list ul {
				list-style: outside;
			}
			.wpbc-changelog-list ul li{
				margin-bottom: 0.5em;
				line-height: 1.5em;
			}
			.wpbc-changelog-list ul li strong{
				padding:0 5px;		
			}
			.wpbc_expand_section_link,
			a.wpbc_expand_section_link:hover,
			a.wpbc_expand_section_link:focus {
				color:#21759b;
				cursor: pointer;
				outline: 0;
				border:none;
				border-bottom:1px dashed #21759B;
				text-decoration: none;      
			}
		</style><?php
}
		?>
		<div class="wrap about-wrap wpbc-welcome-page">

            <?php $this->title_section(); ?>

            <table class="about-text" style="margin-bottom:-40px;height:auto;font-size:1em;width:100%;" >
                <tr>
                    <td>
                        <?php  list( $display_version ) = explode( '-', WPDEV_BK_VERSION );  ?>
                        <!--Thank you for updating to the latest version. <strong><code><?php echo $display_version; ?></code></strong>
                        <br/>Booking Calendar has become more polished, powerful and easy to use than ever before.-->
                    </td>
                    <td style="width:10%">
                        <a  href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wpbc-getting-started' ), 'index.php' ) ) ); ?>"
                            style="float: right; height: 36px; line-height: 34px;" 
                            class="button-primary"
                            >&nbsp;<strong>Get Started</strong> <span style="font-size: 20px;line-height: 18px;padding-left: 5px;">&rsaquo;&rsaquo;&rsaquo;</span>
                        </a>
                    </td>
                </tr>
            </table>
            <?php

            $this->maintence_section();

			$this->section_9_8_css();

			wpbc_welcome_section_10_0( $this );

			wpbc_welcome_section_9_9( $this );

			wpbc_welcome_section_9_8( $this );

if (0) {
	wpbc_welcome_section_9_7( $this );

	wpbc_welcome_section_9_6( $this );

	wpbc_welcome_section_9_5( $this );

	wpbc_welcome_section_9_4( $this );

	wpbc_welcome_section_9_3( $this );

	wpbc_welcome_section_9_2( $this );

	wpbc_welcome_section_9_1( $this );

	wpbc_welcome_section_9_0( $this );

	wpbc_welcome_section_8_9( $this );

	wpbc_welcome_section_8_8( $this );

	wpbc_welcome_section_8_7( $this );

	wpbc_welcome_section_8_6( $this );

	wpbc_welcome_section_8_5( $this );

	wpbc_welcome_section_8_4( $this );

	wpbc_welcome_section_8_3( $this );

	wpbc_welcome_section_8_2( $this );

	wpbc_welcome_section_8_1( $this );

	wpbc_welcome_section_8_0( $this );

	wpbc_welcome_section_7_1_7_2( $this );
}

			////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Footer
			////////////////////////////////////////////////////////////////////////////////////////////////////////////	
			?>
            <table class="about-text" style="margin-bottom:30px;height:auto;font-size:1em;width:100%;" >
                <tr>
                    <td>

                    </td>
                    <td style="width:10%">
                        <a  href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wpbc-getting-started' ), 'index.php' ) ) ); ?>"
                            style="float: right; height: 36px; line-height: 34px;" 
                            class="button-primary"
                            >&nbsp;<strong>Get Started</strong> <span style="font-size: 20px;line-height: 18px;padding-left: 5px;">&rsaquo;&rsaquo;&rsaquo;</span>
                        </a>
                    </td>
                </tr>
            </table>
  
        </div><?php
    }

		function expand_section_start( $section_param_arr ){

			?><div class="clear" style="margin-top:20px;"></div><?php

			if( $section_param_arr['show_expand'] ) {

				?><a 	id="wpbc_show_advanced_section_link_show"
					class="wpbc_expand_section_link"
					href="javascript:void(0)"
					onclick="javascript:jQuery( '.version_update_<?php echo str_replace( array( '.', ' ' ), '_', $section_param_arr['version_num'] ); ?>' ).toggle();"
				>+ Show changes in version update <span style="font-size: 1.35em;font-weight: 600;color: #079;font-family: Consolas,Monaco,monospace;padding-left:12px;"><?php echo $section_param_arr['version_num']; ?></span>
				</a>
				<div class="version_update_<?php echo str_replace( array( '.', ' ' ), '_', $section_param_arr['version_num'] ); ?>" style="display:none;">
				<?php

			}

			?><h2 style='font-size: 1.9em;text-align:left;'>What's New in Booking Calendar <span style="font-size: 1.1em;font-weight: 600;font-family: Consolas,Monaco,monospace;padding-left: 10px;color: #5F5F5F;"
				><?php echo $section_param_arr['version_num']; ?></span></h2><?php

		}

		function expand_section_end( $section_param_arr ){
			if( $section_param_arr['show_expand'] ) {
				?></div><?php
			}
		}


		function section_img_url( $relative_path_to_img ) {
			return esc_url( $this->asset_path . $relative_path_to_img );
		}

	// =================================================================================================================

		function section_9_8_css(){

			?><style type="text/css">
				.about-wrap.wpbc-welcome-page {
					position: relative;
					margin: 25px 40px 0 20px;
					max-width: 1050px;
					font-size: 15px;
					clear: both;
				}
				.wpbc_wn_container{
					margin: 24px auto;
					overflow: hidden;
				}
				.wpbc_wn_section{
					display: flex;
					flex-flow: row wrap;
					justify-content: space-between;
					align-content: flex-start;
					align-items: flex-start;
					font-size: 1.05em;
					line-height: 2rem;
					margin: 1em 0;
				}
				.wpbc_wn_col{
					flex: 1 1 50%;
					padding: 1.5em 2em;
					box-sizing: border-box;
				}
				@media screen and (max-width: 782px) {
					.wpbc_wn_col{
					 flex: 1 1 100%;
					}
				}
				.wpbc_wn_separator{
					flex: 1 1 100%;
				}
				.wpbc_wn_col * {
					 margin: 0;
					line-height: 2.2em;
				}
				.wpbc_wn_section > h2,
				.wpbc_wn_section > h3{
					margin: 0 0  0.25em;
					font-size: 2em;
					line-height: 2.16em;
					font-weight: 600;
					flex: 1 1 100%;
					text-align: center;
					padding:0 1.5em;
				}
				.wpbc_wn_section h3 {
					font-size: 1.25em;
					text-align: left;
					margin: 0.25em 0 0.5em;
				}
				.wpbc_wn_section img{
					border-radius: 2px;
					box-shadow: none;
					width: 99%;
					margin: 0.5em 0 auto;
				}
				.wpbc_wn_container .wpbc_hr_dots {
					height: 1px;
					border:none;
					border-bottom: 0.5rem dotted #a9a9a9;
					width: 3rem;
					margin:0  auto;
					clear: both:;
					display: block;
					position: relative;
				}
			</style>
			<?php
		}



	// =================================================================================================================

	function section_1_0_example(){

		$section_param_arr = array( 'version_num' => '7.0', 'show_expand' => true );
		$this->expand_section_start( $section_param_arr );

		// Content here

		$this->show_separator();
		$this->expand_section_end( $section_param_arr );
	}



    public function content_getted_started() {
        
        $this->css();
        
        list( $display_version ) = explode( '-', WPDEV_BK_VERSION );
        ?>
            <div class="wrap about-wrap wpbc-welcome-page">

                <?php $this->title_section(); ?>

                <table class="about-text" style="margin-bottom:30px;height:auto;font-size:1em;width:100%;" >
                    <tr>
                        <td>
                            <a  href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wpbc-about' ), 'index.php' ) ) ); ?>"
                                style="float: left; height: 36px; line-height: 34px;" 
                                class="button-primary"
                                >&nbsp;<span style="font-size: 20px;line-height: 18px;padding-right: 5px;">&lsaquo;&lsaquo;&lsaquo;</span> <strong>What's New</strong> 
                            </a>
                        </td>
                        <td style="width:50%">
                            <a  href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wpbc-about-premium' ), 'index.php' ) ) ); ?>"
                                style="float: right; height: 36px; line-height: 34px;" 
                                class="button-primary"
                                >&nbsp;<strong>Premium Features</strong> <span style="font-size: 20px;line-height: 18px;padding-left: 5px;">&rsaquo;&rsaquo;&rsaquo;</span>
                            </a>
                        </td>
                    </tr>
                </table>

                <h2 style='font-size: 2.1em;'>Get Started</h2>
                <?php

?><div style="text-align: center;"><iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLabuVtqCh9dwLA5cpz1p2RrZOitLuVupR&amp;start=28&amp;rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div><?php

                //$this->show_separator();

                $this->show_col_section( array( 
                                              array( 'h4'   => sprintf( 'Add booking form to your post or page' ),
                                                     'text' => '<ul style="margin:0px;">' 
                                                     . '<li>' . sprintf( 'Open exist or add new %spost%s or %spage%s' 
                                                                        ,  '<a href="' . admin_url( 'edit.php' ) . '">', '</a>'
                                                                        ,  '<a href="' . admin_url( 'edit.php?post_type=page' ) . '">', '</a>' ) . '</li>'
                                                     . '<li>' . sprintf( ' Click on Booking Calendar icon *(button with calendar icon at toolbar)*' ) . '</li>'
                                                     . '<li>' . sprintf( ' In popup dialog select your options, and insert shortcode' ) . '</li>'
                                                     . '<li>' . sprintf( ' Publish or update page' ) . '</li>'
                                                     . '<li>' . sprintf( ' Now your visitors can see and make bookings at the booking form' ) . '</li>'
                                                            . '</ul>'
                                                    )
                                            , array(  'img'  => 'get-started/booking-calendar-insert-form.png', 'img_style'=>'margin: 20px;width:75%;float:right;' ) 
                                           ) 
                                        );  
                $this->show_col_section( array( 

                                              array(
                                                    'text' => 
                                                             '<p class="">' 
                                                             . sprintf( 'Or add Booking Calendar %s**widget**%s to your sidebar.', '<a href="' . admin_url( 'widgets.php' ) . '">', '</a>' ) 
                                                             . '</p>'
                                                             . '<p>' . sprintf( 'If you need to add shortcode manually, you can read how to add it %shere%s.', 
                                                                                '<a href="https://wpbookingcalendar.com/faq/booking-calendar-shortcodes/">', '</a>')
                                                             . '</p>'
                                                             . '<p>' . sprintf( '* **Note.** You can add new booking(s) also from the admin panel (Booking > Add booking page).*' )
                                                             . '</p>'                                                  
                                                    )
                                            , array(  'img'  => 'get-started/booking-calendar-add-widget.png', 'img_style'=>'margin:0 20px;width:75%;float:right;' )

                                           ) );

                ?>
                <div class="feature-section two-col"> 
                    <div class="col col-1 last-feature"  style="margin-top: 0px;width:59%">                    
                        <h4><?php printf( 'Check and manage your bookings' ); ?></h4>
                        <p><?php echo wpbc_replace_to_strong_symbols( 'After email notification about new booking(s), you can check and **approve** or **decline** your **booking(s)** in **responsive**, modern and **easy to use Booking Admin Panel**.'); ?></p>                

                    </div>
                </div>
                <img src="<?php echo $this->asset_path; ?>get-started/booking-listing_350.png" style="float:left;border:none;box-shadow: 0 1px 3px #777777;margin:1% 2%;width:72.3%;" />
                <img src="<?php echo $this->asset_path; ?>get-started/booking-listing-mobile_350.png" style="float:left;border:none;box-shadow: 0 1px 3px #777777;margin: 1% 1% 1% 0;width:19.1%;" />
                <div class="clear"></div>

                <p style="text-align:center;"><?php echo wpbc_replace_to_strong_symbols( 'or get clear view to **all your bookings in** stylish **Calendar Overview** mode, that looks great on any device'); ?></p>                
                <img src="<?php echo $this->asset_path; ?>get-started/booking-calendar-overview.png" style="border:none;box-shadow: 0 1px 3px #777777;margin: 2%;width:94%;display:block;" />
                <div class="clear"></div>


                <h2 style='font-size: 2.1em;margin-top:50px;'><?php printf( 'Next Steps' ); ?></h2>
                <?php 

                $this->show_separator();

                $this->show_col_section( array( 
                                              array( 'h4'   => sprintf( 'Configure different settings' ),
                                                    'text' =>  '<ul style="margin:0px;">' 

    . '<li>' . sprintf( 'Select your calendar skin, for natively fit to your website design.' ) . '</li>'
    . '<li>' . sprintf( 'Configure number of month(s) in calendar.' ) . '</li>'
    . '<li>' . sprintf( 'Set single or multiple days selection mode.' ) . '</li>'
    . '<li>' . sprintf( 'Set specific weekday(s) as unavailable.' ) . '</li>'
    . '<li>' . sprintf( 'Customize calendar legend.' ) . '</li>'
    . '<li>' . sprintf( 'Enable CAPTCHA.' ) . '</li>'
    . '<li>' . sprintf( 'Set redirection to the "Thank you" page, after the booking process.' ) . '</li>'
    . '<li>' . sprintf( 'Configure different settings for your booking admin panel.' ) . '</li>'
    . '<li>' . sprintf( 'And much more ...' ) . '</li>'

                                                             . '</ul>'
                                                    )
                                            , array(  'img'  => 'get-started/settings-general.png', 'img_style'=>'margin: 20px;width:75%;float:right;' ) 
                                           ) 
                                        );  

                ?><div clas="clear"></div><?php

                $this->show_col_section( array( 
                                              array( 'h4'   => sprintf( 'Customize booking form fields and email templates' ),
                                                    'text' =>  '<ul style="margin:0px;">' 

    . '<li>' . sprintf( 'Activate or deactivate specific form fields in your booking form.' ) . '</li>'
    . '<li>' . sprintf( 'Configure labels in your booking form near form fields.' ) . '</li>'
    . '<li>' . sprintf( 'Set specific form fields as required.' ) . '</li>'
    . '<li style="margin-top:30px;">' . sprintf( 'Activate or deactivate specific email(s).' ) . '</li>'
    . '<li>' . sprintf( 'Customize your email templates.' ) . '</li>'
    . '<li style="margin-top:30px;">' . sprintf( 'Or even activate and configure <strong>import</strong> of <strong>Google Calendar Events</strong>.' ) . '</li>'                                                  
    . '<li style="margin-top:30px;">' . sprintf( 'And much more ...' ) . '</li>'

                                                             . '</ul>'
//                                                  . '<h4>' . sprintf( 'Or even activate importing events from Google Calendar' ) . '</h4>'

                                                    )
                                            , array(  'img'  => 'get-started/settings-fields.png', 'img_style'=>'margin: 20px;width:75%;float:right;' ) 
                                           ) 
                                        );  

                ?>                

                <h2 style='font-size: 2.1em;;margin-top:20px;'><?php printf( 'Have a questions?' ); ?></h2>
                <?php 

                $this->show_separator();

                $this->show_col_section( array( 
                                              array( 
                                                     'text' => '<span>' . sprintf( 'Check out our %sHelp%s', '<a href="https://wpbookingcalendar.com/help/" target="_blank" >', '</a>' ) . '</span>'
                                                             . '<p>' . sprintf( 'See %sFAQ%s', '<a href="https://wpbookingcalendar.com/faq/" target="_blank">', '</a>' ) . '</p>'
                                                   ) 
                                            , array( 
                                                     'text' => '<strong>' . sprintf( 'Still having questions?' ) . '</strong>'
                                                             . '<p>' . sprintf( 'Check our %sForum%s or contact %sSupport%s', '<a href="https://wpbookingcalendar.com/support/" target="_blank">', '</a>', '<a href="https://wpbookingcalendar.com/contact/" target="_blank">', '</a>' ) . '</p>'
                                                   ) 
                                            , array( 
                                                     'text' => '<strong>' . sprintf( 'Need even more functionality?' ) . '</strong>'
                                                             . '<p>' . sprintf( ' Check %shigher versions%s of Booking Calendar', '<a href="https://wpbookingcalendar.com/overview/" target="_blank">', '</a>' ) . '</p>'

                                                   ) 

                                            ) 
                                        );  

                ?>                                                                   
                <table class="about-text" style="margin-bottom:30px;height:auto;font-size:1em;width:100%;" >
                    <tr>
                        <td>
                            <a  href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wpbc-about' ), 'index.php' ) ) ); ?>"
                                style="float: left; height: 36px; line-height: 34px;" 
                                class="button-primary"
                                >&nbsp;<span style="font-size: 20px;line-height: 18px;padding-right: 5px;">&lsaquo;&lsaquo;&lsaquo;</span> <strong>What's New</strong> 
                            </a>
                        </td>
                        <td style="width:50%">
                            <a  href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wpbc-about-premium' ), 'index.php' ) ) ); ?>"
                                style="float: right; height: 36px; line-height: 34px;" 
                                class="button-primary"
                                >&nbsp;<strong>Premium Features</strong> <span style="font-size: 20px;line-height: 18px;padding-left: 5px;">&rsaquo;&rsaquo;&rsaquo;</span>
                            </a>
                        </td>
                    </tr>
                </table>

            </div>
        <?php
    }

    
    public function content_premium() {
        
        $this->css();
        
        list( $display_version ) = explode( '-', WPDEV_BK_VERSION );
        
        // $upgrade_link = esc_url( admin_url( add_query_arg( array( 'page' => 'wpbc-about-premium' ), 'index.php' ) ) );
        
        ?>
        <div class="wrap about-wrap wpbc-welcome-page">

                <?php $this->title_section(); ?>

                <table class="about-text" style="margin-bottom:30px;height:auto;font-size:1em;width:100%;" >
                    <tr>
                        <td>
                            <a  href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'wpbc-getting-started' ), 'index.php' ) ) ); ?>"
                                style="float: left; height: 36px; line-height: 34px;" 
                                class="button-primary"
                                >&nbsp;<span style="font-size: 20px;line-height: 18px;padding-right: 5px;">&lsaquo;&lsaquo;&lsaquo;</span> <strong>Get Started</strong> 
                            </a>
                        </td>
                        <td style="width:50%">                            
                            <a class="button button-primary" style="font-weight: 600;float: right; height: 36px; line-height: 34px;"  href="<?php echo wpbc_up_link(); ?>" target="_blank">&nbsp;<?php if ( wpbc_get_ver_sufix() == '' ) { _e('Purchase' ,'booking'); } else { _e('Upgrade Now' ,'booking'); } ?>&nbsp;&nbsp;</a>
                        </td>
                    </tr>
                </table>                        
            <?php
            
            echo '<div style="color: #999;font-size: 24px;margin-top: 0px;text-align: center;width: 100%;">';
                echo 'Get even more functionality with premium versions...';
            echo '</div>';


            
 //           echo '<div class="clear" style="height:30px;"></div>';
?><div style="text-align: center;margin:20px 0;"><iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLabuVtqCh9dyc_EO8L_1FKJyLpBuIv21_&amp;rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div><?php
            
            $this->show_header('Booking Calendar Personal version'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://personal.wpbookingcalendar.com/admin-panel/" target="_blank"' 
                                    . '> **Live Demo** *Admin Panel*</a>'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://personal.wpbookingcalendar.com/" target="_blank"'
                                    . '> **Live Demo** *Front End*</a><div class="clear"></div>'
                              , 'h2', 'line-height: 1.5em;padding:5px 15px 5px 0;font-weight: 600;font-size: 2em;background: none;color: #444;' );

            
            echo $this->get_img( 'premium/admin-panel-calendar-overvew4.png', 'margin:15px auto; width: 98%;' );

			$this->show_separator();

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Unlimited number of **Booking Resources**',
                                                 'text' => 
'<p>Booking resources - it\'s your **services** or **properties** *(like houses, cars, tables, etc...)*, that can be booked by visitors of your website.</p>'
. '<p>Each booking resource have own unique calendar *(with  booking form)*, which  will **prevent of double bookings** for the same date(s).</p>'
. '<p>It\'s means that you can **receive bookings and show unavailable, booked dates** in different calendars **for different booking resources** *(services or properties)*.</p>'
                                
.  $this->get_img( 'premium/2-booking-forms.png', 'margin:0 0 10px 0; width: 97%;' )
                                              
.'<p>You can add/delete/modify your booking resources at the Booking > Resource page. 
You can define the calendar *(booking form)* to the specific booking resources, 
at the popup configuration dialog, during inserting booking shortcode into post or page.</p>'

                                                ) 
                                        , array(  'img'  => 'premium/booking-resources.png', 'img_style'=>'margin-top:40px;width:95%;' ) 
                                        ) 
                                    );  
            
            
            
            $this->show_col_section( array( 
                                          
                                          array( 'h4'   => 'Configure Booking Form and Email Templates',
                                                 'text' =>
  '**Booking Form**<br />'
. '<p>Configure any format and view of your booking form *(for example two columns view,  with calendar in left column and form fields at right side, etc...)*</p>'
. '<p>Add **any number of new form fields** *(text fields, drop down lists, radio-buttons, check-boxes or textarea elements, etc...)*</p>'
. '<br />**Email Templates**<br />'
. '<p>You can activate and configure email templates for the different booking actions with shortcodes for any exist form fields and some other system shortcodes *(like inserting address of the page (or user IP), where user made this action)*.</p>' 
                                                ) 
                                        , array(  'img'  => 'premium/booking-form-fields.png', 'img_style'=>'margin-top:20px;width:95%;' ) 
                                        ) 
                                    );  

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Manage Bookings',
                                                 'text' => 
//'You can edit the exist bookings, add notes to the bookings, print and export bookings to the CSV format, etc...' 
'<ul>
    <li style="margin-left: 1em;">**Edit** your bookings by changing details or dates of specific booking</li>
    <li style="margin-left: 1em;">**Duplicate** booking in other booking resource</li>
    <li style="margin-left: 1em;">**Change resource** for exist booking</li>
    <li style="margin-left: 1em;">Add **notes** to bookings for do not forget important info</li>
    <li style="margin-left: 1em;">**Print** booking listings</li>
    <li style="margin-left: 1em;">**Export** bookings to the **CSV format**</li>
    <li style="margin-left: 1em;">**Import** bookings from **Google Calendar**</li>
    <li style="margin-left: 1em;">**Sync bookings via .ics feeds**, as well</li>
    <li style="margin-left: 1em;">And much more...</li>
</ul>'                                               
                                              ) 
                                        , array(  'img'  => 'premium/booking-actions-buttons.png', 'img_style'=>'margin-top:20px;width:95%;' ) 
                                        ) 
                                    );  
            
            $this->show_separator();

            echo wpbc_replace_to_strong_symbols( '<div style="font-size: 0.95em;font-style:italic;text-align:right;margin:5px 0 10px;">Check many other nice features in  Booking Calendar Personal version at <a target="_blank" href="https://wpbookingcalendar.com/overview/">features list</a> and test <a target="_blank" href="https://wpbookingcalendar.com/demo/">live demo</a>.</div>' );
            
            ?><div class="clear" style="height:30px;"></div><?php
            
            $this->show_header('Booking Calendar Business Small version'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://bs.wpbookingcalendar.com/admin-panel/" target="_blank"' 
                                    . '> **Live Demo** *Admin Panel*</a>'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://bs.wpbookingcalendar.com/" target="_blank"'
                                    . '> **Live Demo** *Front End*</a><div class="clear"></div>'
                              , 'h2', 'line-height: 1.5em;padding:5px 15px 5px 0;font-weight: 600;font-size: 2em;background: none;color: #444;' );

            
            $this->show_separator();

            echo wpbc_replace_to_strong_symbols( '<div style="font-size: 0.85em;font-style: italic;margin: 5px 0 0 10px;">**Note!** This version support **all functionality** from the Booking Calendar **Personal** version.</div>' );
            
            
            $this->show_col_section( array( 
                                          array( 'h4'   => 'Advanced Hourly Bookings',
                                                 'text' => 
  '<p>Add ability to make bookings for specific **times** *(in addition to timeslots bookings, the bookings for specific start time and time duration or start time and end time, as well)*.</p>'
. '<p>Configure selections or entering any times interval *(several hours or few minutes)* at the Booking > Settings > Fields page:'
.'<ul>
    <li style="margin-left: 1em;">Start time and end **time entering** in *"time text fields"*</li>
    <li style="margin-left: 1em;">Selections **start time and end time**</li>
    <li style="margin-left: 1em;">Selections **start time and duration** of time</li>
    <li style="margin-left: 1em;">Selections specific time in **timeslot** list</li>
</ul></p>'
                                              
.'<p>**Please note**, if you will make the booking for specific timeslot, this timeslot become unavailable for the other visitors for this selected date.</p>'

.'<p>You can even activate booking of same timeslot in the several selected dates during the same booking session.</p>'
                                              ) 
                                        , array(  'img'  => 'premium/time-slots-booking.png', 'img_style'=>'margin:20px 25% auto;width:50%;' ) 
                                        ) 
                                    );         
            
            $this->show_col_section( array( 
                                          array( 'h4'   => 'Online Payments',
                                                 'text' => 
    '<p>' . 'You can set cost per specific booking resource and activate online payments' . '</p>'                                              
  . '<p>' . 'Suport Payment Gateways:'
  .'<ul>
      <li style="margin-left: 1em;">**Stripe**</li>
      <li style="margin-left: 1em;">PayPal Standard</li>
      <li style="margin-left: 1em;">PayPal Pro Hosted Solution *(note, its doesn\'t PayPal Pro)*</li>
      <li style="margin-left: 1em;">Authorize.Net *(Server Integration Method (SIM))*</li>
      <li style="margin-left: 1em;">Sage Pay</li>
      <li style="margin-left: 1em;">iDEAL via Buckaroo (former Sisow)</li>
      <li style="margin-left: 1em;">iPay88</li>
      <li style="margin-left: 1em;">Direct/wire bank transfer</li>
      <li style="margin-left: 1em;">Cash payments</li>
  </ul></p>'
  .'<p>' . 'You can activate and configure these gateways at Booking > Settings > Payment page.' . '</p>'
  .'<p>' . '*You can even send payment request by email for specific booking*.' . '</p>'
                                               )
                                         , array(  'img'  => 'premium/payment-buttons1.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              

            
            
            $this->show_col_section( array( 
                                          array( 'h4'   => 'Change over days',
                                                 'text' => 
    '<p>' . 'You can use the **same date** as **"check in/out"** for **different bookings**.' . '</p>'

  . '<p>' . 'These **half booked days** will mark  by vertical line *(as in <a href="http://bm.wpbookingcalendar.com/" targe="_blank">this live demo</a>)*.' . '</p>'

  . '<p>' . 'It\'s means that  your visitors can start  new booking on the same date,  where some old bookings was ending.' . '</p>'

  . '<p>' . 'To activate this feature you need select *range days selection* or *multiple days selections* mode on the *General Booking Settings* page in calendar  section.'
          . ' After  this you can activate the *"Use check in/out time"* option  and configure the check in/out times. For example, check in time as 14:00 and check out time as 12:00.' . '</p>'

  . '<p>' . '**Tip**. You can also activate to show change-over days as triangles (diagonal lines), instead of showing them via vertical lines.' . '</p>'
                                               )
                                        , array(  'img'  => 'premium/change-over-days2.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                    
                                    );              

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Range days selection',
                                                 'text' =>  
  '<p>' . 'Activate **several days selection with 1 or 2 mouse clicks** *(by selecting check in and check out dates, all middle days will be selected automatically)*.' . '</p>'
. '<p>' . 'Its means that you can set only **week(s) selections** or any other number of days selections.' . '</p>'
. '<p>' . 'Configure **specific number of days** selections for *range days selection with one mouse click*. ' 
        . 'Or set **minimum and maximum number of days** selections (or even several  specific number of days) for *range days selection with two mouse clicks*.' . '</p>'
. '<p>' . 'In addition you can **set start day(s)** selections for only **specific week days**.' . '</p>'
                                               )
                                        , array(  'img'  => 'premium/range-days-settings.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              

            

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Auto Cancellation  / Auto Approval',
                                                 'text' => 
  '<p>' . 'You can activate **auto cancellation of all pending booking(s)**, which have no successfully paid status, after specific amount of time, when booking(s) was making.' . '</p>'
. '<p>' . 'This feature will set dates again available for new booking(s) to other visitors.' . '</p>'
. '<p>' . 'You can even activate sending of emails to the visitors, during such cancelation.' . '</p>'
. '<p>' . 'Or you can activate **auto approval of all incoming bookings**.' . '</p>'
                                              )
                                        , array(  'img'  => 'premium/auto-cancelation-settings.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              

            
                         
            
            $this->show_separator();

            echo wpbc_replace_to_strong_symbols( '<div style="font-size: 0.95em;font-style:italic;text-align:right;margin:5px 0 10px;">Check many other nice features in Booking Calendar Business Small version at <a target="_blank" href="https://wpbookingcalendar.com/overview/">features list</a> and test <a target="_blank" href="https://wpbookingcalendar.com/demo/">live demo</a>.</div>' );
            
            ?><div class="clear" style="height:30px;"></div><?php
            
            $this->show_header('Booking Calendar Business Medium version'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://bm.wpbookingcalendar.com/admin-panel/" target="_blank"' 
                                    . '> **Live Demo** *Admin Panel*</a>'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://bm.wpbookingcalendar.com/" target="_blank"'
                                    . '> **Live Demo** *Front End*</a><div class="clear"></div>'
                              , 'h2', 'line-height: 1.5em;padding:5px 15px 5px 0;font-weight: 600;font-size: 2em;background: none;color: #444;' );

            
            $this->show_separator();

            echo wpbc_replace_to_strong_symbols( '<div style="font-size: 0.85em;font-style: italic;margin: 5px 0 0 10px;">**Note!** This version support **all functionality** from the Booking Calendar **Business Small** version.</div>' );

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Season Availability',
                                                 'text' => 
  '<p>' . 'You can set as **unavailable days** in your booking resources **for specific seasons**.' . '</p>'
. '<p>' . 'Its useful, when you need to **block days for holidays** or any other seasons during a year.' . '</p>'
. '<p>' . 'You can set days as conditional seasons filters *(for example, only weekends during summer)* or simply select range of days for specific seasons.' . '</p>'
. '<p>' . 'Note, instead of definition days as unavailable, you can set all days unavailable and only days from specific season filer as available.' . '</p>'
. '<p>' . '* **Configuration.** You can create season filters at the Booking > Resources > Filters page and then at the Booking > Resources > **Availability** page set days from  specific season as unavailable for the specific booking resources.*' . '</p>'
                                              )
                                        , array(  'img'  => 'premium/season-filters.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              
                                              
            $this->show_col_section( array(
                                          array( 'h4'   => 'Set available days interval depending from today date',
                                                 'text' =>
  '<p>' . '**Limit available days from today** - defining specific number of available days, that start from today. All other future days will be unavailable. Also in any versions of Booking Calendar possible to define **Unavailable days from today** - defining specific number of unavailable days in calendar start from today. Its means that with these 2 options you can set interval of available days, that depending from today date.' . '</p>'
                                              )
                                        , array( 'h4'   => 'Set unavailable minutes/hours/days before or after booking date/time',
                                                 'text' =>
   '<p>' . 'This option is useful, if you need to define some unavailable time/days for cleaning or any other srvices before or after booking.' . '</p>'
.  '<p>' . 'Important! This feature is applying only for bookings for specific timeslots, or if activated change-over days feature. Its does not work for full booked days.' . '</p>'
                                              )
                                        )
                                    );


            $this->show_col_section( array(
                                          array( 'h4'   => 'Set Rates for different Seasons',
                                                 'text' => 
 '<p>' . 'Set different **daily cost (rates) for** different **seasons**.' . '</p>'
. '<p>' . '*For example, you can have higher cost for the "High Season" or at weekends.*' . '</p>'
. '<p>' . 'You can set rates as **fixed cost per day** (night) **or as percent** from original cost of booking resource.' . '</p>'
. '<p>' . '* **Configuration.** You can set rates for your booking resources at Booking > Resources > **Cost and rates** page by clicking on **Rate** button.*' . '</p>'
                                              )
                                        , array(  'img'  => 'premium/season-rates.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              
                                              
            $this->show_col_section( array( 
                                          array( 'h4'   => 'Cost depends from number of selected days',
                                                 'text' => 
  '<p>' . 'You can configure **different cost** for different **number of selected days**.' . '</p>'
. '<p>' . '*For example, cost of second selected week, can be lower then cost of first week.*' . '</p>'
. '<p>' . 'You can set **cost per day(s)** or **percentage** from the original cost:' 
  .'<ul>
      <li style="margin-left: 2em;">**For** specific selected day number</li>
      <li style="margin-left: 2em;">**From** one day number **to** other selected day number</li>
  </ul>'                                                                                             
. 'or you can set the **total cost** of booking for **all days**:'
  .'<ul>
      <li style="margin-left: 2em;">If selected, exactly specific number of days *(term "**Together**")*</li>      
  </ul></p>'
. '<p>' . 'In addition, you can even set applying this cost only, if the "Check In" day in specific season filter.' . '</p>'
. '<p>' . '* **Configuration.** You can set rates for your booking resources at Booking > Resources > **Cost and rates** page by clicking on "**Valuation days**" button.*' . '</p>'
                                              )
                                        , array(  'img'  => 'premium/valuation-days.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              
                                              

            $this->show_col_section( array(
                                          array( 'h4'   => 'Early booking / Last minute booking discounts',
                                                 'text' =>
  '<p>' . '**Last minute booking discounts.**' . '</p>'
. '<p>' . 'Set discount, if difference between "today" and "check in" day **LESS** than N days.' . '</p>'
. '<p>' . '**Early booking discount.**' . '</p>'
. '<p>' . 'Set discount, if difference between "today" and "check in" day **MORE** than N days.' . '</p>'
. '<p>' . 'You can set discounts as fixed cost or as percent from original cost of booking resource.' . '</p>'
. '<p>' . '* **Configuration.** You can set these discounts for your booking resources at Booking > Resources > **Cost and rates** page by clicking on **Early / Late** button.*' . '</p>'
                                              )
                                        , array(  'img'  => 'https://wpbookingcalendar.com/wp-content/uploads/2018/06/booking-calendar-early-booking-last-minute-discounts.png', 'img_style'=>'margin:20px 0;width:99%;' )
                                        )
                                    );
            $this->show_col_section( array(
                                          array( 'h4'   => 'Cost depends from selection options in booking form',
                                                 'text' => 
  '<p>' . 'You can set additional costs, like tax or some other additional charges *(cleaning, breakfast,  etc...)*, or just increase the cost of booking depends from the visitor number selection in your booking form.' . '</p>'
. '<p>' . 'Its means that you can set additional cost for any selected option(s) in select-boxes or checkboxes at your booking form.' . '</p>'
. '<p>' . 'You can set fixed cost or percentage from the total booking cost or additional cost per each selected day or night.' . '</p>'
. '<p>' . '* **Configuration.** Firstly you need to configure options selection in select-boxes or checkboxes in your booking form at Booking > Settings > Fields page, then you be able to configure additional cost for each such option at the Booking > Resources > **Advanced cost** page .*' . '</p>'
. '<p>' . '* **Tip & Trick.** ' .  'You can **show cost hints** separately for the each items, that have additional cost *at Booking > Resources > Advanced cost page*. 
                                    <br>For example, if you have configured additional cost for **my_tax** option at **Advanced cost page**, 
                                    then in booking form you can use this shortcode <code>[my_tax_hint]</code> to show additional cost of this specific option. 
                                    <br>Add **"_hint"** term to name of shortcode for creation hint shortcode. *'
          .'</p>'                                              
                                              )
                                        , array(  'img'  => 'premium/advanced-cost.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              
                                              
            $this->show_col_section( array( 
                                          array( 'h4'   => 'Deposit payments',
                                                 'text' => 
  '<p>' . 'You can activate ability to **pay deposit (part of the booking cost)**, after visitor made the booking. ' . '</p>'
. '<p>' . 'It\'s possible to set fixed deposit value or percent from the original cost for the specific booking resource.' . '</p>'
. '<p>' . 'You can even activate to show deposit payment form, only when  the difference between *"today"* and *"check in"* days more than specific number of days. Or if *"check in"* day inside of specific season.' . '</p>'
. '<p>' . '* **Configuration.** You can activate and configure **deposit** value for specific booking resources at the Booking > Resources > **Cost and rates** page by clicking on "**Deposit amount**" button.*' . '</p>'
                                              )
                                        , array(  'img'  => 'premium/deposit-settings.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              
                                              

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Multiple Custom Booking Forms',
                                                 'text' => 
  '<p>' . 'You can create **several custom forms** configurations.' . '</p>'
. '<p>' . 'Its means that you can have the different booking forms *(which have the different form fields)* for different booking resources.' . '</p>'
. '<p>' . 'You can also set specific custom form  as **default booking form to** each  of your **booking resources** at Booking > Resources page.' . '</p>'
. '<p>' . '* **Configuration.** You can create several custom booking forms at the Booking > Settings > **Fields** page by clicking on **"Add new Custom Form"** button.*' . '</p>'                                                                                           
                                              )
                                        , array(  'img'  => 'https://wpbookingcalendar.com/wp-content/uploads/2018/01/custom-booking-forms.png', 'img_style'=>'margin:20px 0;width:99%;' )
                                        ) 
                                    );              
                                              

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Advanced days selection',
                                                 'text' => 
  '<p>' . 'Specify that on **specific week days** (or during certain seasons), the specific minimum (or fixed) **number of days** must be booked.' 
        . '<br/>*For example: visitor can select only 3 days starting at Friday and Saturday, 4 days – Friday, 5 days – Monday, 7 days – Saturday, etc...*' . '</p>'
                                              
. '<p>' . 'Also, you can define **specific week day(s) as start day** in calendar selection for the **specific season**.' 
        . '<br/>*For example, in "High Season", you can allow start day selection only at Friday in the "Low Season" to start day selection from any weekday.*' . '</p>'

. '<p>' . '*Read more about this configuration <a href="https://wpbookingcalendar.com/faq/booking-calendar-shortcodes/" targe="_blank">here</a> (at **options** parameter section).*' . '</p>'
                                                                                            
                                              )
                                        
                                         , array( 'h4'   => 'Different time slots for different days',
                                                 'text' => 
  '<p>' . 'This feature provide ability to use the **different time slots selections** in the booking form **for different selected week days or seasons**.' . '</p>' 
. '<p>' . 'Each week day (day of specific season filter) can have different time slots list.' . '</p>'
                                              
. '<p>' . 'You can check more info about this configuration at <a href="https://wpbookingcalendar.com/faq/different-time-slots-selections-for-different-days/" targe="_blank">this page</a>.' . '</p>'
. '<p>' . '**Note.** In the same way you can configure showing any different form fields, not only  timeslots.' . '</p>'                                             
                                              )
                                        ) 
                                    );              

            $this->show_separator();

            echo wpbc_replace_to_strong_symbols( '<div style="font-size: 0.95em;font-style:italic;text-align:right;margin:5px 0 10px;">Check many other nice features in Booking Calendar Business Medium version at <a target="_blank" href="https://wpbookingcalendar.com/overview/">features list</a> and test <a target="_blank" href="https://wpbookingcalendar.com/demo/">live demo</a>.</div>' );
            
            ?><div class="clear" style="height:30px;"></div><?php
            
            $this->show_header('Booking Calendar Business Large version'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://bl.wpbookingcalendar.com/admin-panel/" target="_blank"' 
                                    . '> **Live Demo** *Admin Panel*</a>'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://bl.wpbookingcalendar.com/" target="_blank"'
                                    . '> **Live Demo** *Front End*</a><div class="clear"></div>'
                              , 'h2', 'line-height: 1.5em;padding:5px 15px 5px 0;font-weight: 600;font-size: 2em;background: none;color: #444;' );

            
            $this->show_separator();

            echo wpbc_replace_to_strong_symbols( '<div style="font-size: 0.85em;font-style: italic;margin: 5px 0 0 10px;">**Note!** This version support **all functionality** from the Booking Calendar **Business Medium** version.</div>' );

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Capacity and Availability',
                                                 'text' => 
 '<p>' . 'You can receive **several specific number of bookings per same days**. ' . '</p>'
.'<p>' . 'Define **capacity** for your **booking resource(s)**, 
          and then **dates** in calendar will be **available until number of bookings less than capacity** of the booking resource.' . '</p>'

.'<p>' . '**Note!** Its possible to make reservation only for **entire date(s)**, not a time slots  
   *(data about time slots for booking resources with capacity higher than one, will be record into your DB, but do not apply to availability)*.' . '</p>'
. '<p>' . '* **Configuration.** Set capacity of booking resources at Booking > **Resources** page. You can read more info about configurations of booking resources, capacity and availability  at  <a href="https://wpbookingcalendar.com/faq/booking-resource/" target="_blank">this page</a>.*' . '</p>'
                                              )
                                        , array(  'img'  => 'premium/capacity3.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              
                                              

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Search Availability',
                                                 'text' =>
 '<p>' . 'Your visitors can even **search available booking resources** (properties or services) **for specific dates** *(like in this <a href="http://bl.wpbookingcalendar.com/search/" target="_blank">live demo</a>)*.' . '</p>'
.'<p>' . 'Beside standard parameters: **check in** and **check out** dates, number of **visitors**, you can define **additional parameters** for your search form *(for example, searching property  with  specific amenities)*.
    <br />You can read more about this configurations at <a href="https://wpbookingcalendar.com/faq/selecting-tags-in-search-form/" target="_blank">FAQ</a>.' . '</p>'
.'<p>' . '**Note!** Plugin  will search only among pages with booking forms for *<a href="https://wpbookingcalendar.com/faq/booking-resource/" target="_blank">single or parent</a>* booking resources. You need to insert one booking form per page.' . '</p>'
. '<p>' . '* **Configuration.** Customize your **search form**  and **search  results** at Booking > Settings > **Search** page. 
    After that you can <a href="https://wpbookingcalendar.com/faq/booking-calendar-shortcodes/"  target="_blank">insert search form</a> shortcode into page and test.*' . '</p>'

                                              )
                                        , array(  'img'  => 'premium/search-results2.png', 'img_style'=>'margin:20px 0;width:99%;' ) 
                                        ) 
                                    );              
                                              
            $this->show_col_section( array( 
                                          array( 'h4'   => 'Coupons for Discounts',
                                                 'text' => 
 '<p>' . 'You can provide **discounts for bookings** to your visitors. Your visitors can **enter coupon codes** in booking form to **get discount** for booking(s).' . '</p>'
.'<p>' . 'Its possible to create coupon code(s), which  will apply to  all or specific booking resources.
    You can set **expiration  date** of coupon code and **minimum cost** of booking, where this coupon code will apply.
    <br/>You can define discount as **fixed cost** or as **percentage** from the total cost  of booking.
' . '</p>'
. '<p>' . '* **Configuration.** Create your coupons codes for discounts at Booking > Resources > **Coupons** page. 
    Then insert <a href="https://wpbookingcalendar.com/faq/booking-form-fields/" target="_blank">coupon text field</a> into your booking form at Booking > Settings > Fields page.*' . '</p>'

                                              )
                                        , array(  'img'  => 'premium/coupons.png', 'img_style'=>'margin:2px 0;width:99%;' ) 
                                        ) 
                                    );              
                                              
            $this->show_col_section( array( 
                                          array( 'h4'   => 'Automatic cancellation of pending bookings',
                                                 'text' => 
//  '<p>' . 'Set **pending days as available** in booking form to prevent from SPAM bookings.' . '</p>'
  '<p>' . 'Activate **automatic cancelation** of **pending bookings** for specific date(s), if you **approved booking** on these date(s) at same booking resource.' . '</p>'
. '<p>' . '*You can activate this feature at the General Booking Settings page in "Advanced" section.*' . '</p>'
                                              )
                                        , array(  'img'  => 'premium/pending-available.png', 'img_style'=>'margin:40px 0;width:99%;' ) 
                                        ) 
                                    );              
              
            

            $this->show_separator();

            echo wpbc_replace_to_strong_symbols( '<div style="font-size: 0.95em;font-style:italic;text-align:right;margin:5px 0 10px;">Check many other nice features in Booking Calendar Business Large version at <a target="_blank" href="https://wpbookingcalendar.com/overview/">features list</a> and test <a target="_blank" href="https://wpbookingcalendar.com/demo/">live demo</a>.</div>' );
            
            ?><div class="clear" style="height:30px;"></div><?php
            
            $this->show_header('Booking Calendar MultiUser version'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://multiuser.wpbookingcalendar.com/admin-panel/" target="_blank"' 
                                    . '> **Live Demo** *Admin Panel*</a>'
                                    . '<a class="button-secondary" style="float: right; height: 36px; line-height: 34px;margin:6px 1em;" href="http://multiuser.wpbookingcalendar.com/" target="_blank"'
                                    . '> **Live Demo** *Front End*</a><div class="clear"></div>'
                              , 'h2', 'line-height: 1.5em;padding:5px 15px 5px 0;font-weight: 600;font-size: 2em;background: none;color: #444;' );

            
            $this->show_separator();

            echo wpbc_replace_to_strong_symbols( '<div style="font-size: 0.85em;font-style: italic;margin: 5px 0 0 10px;">**Note!** This version support **all functionality** from the Booking Calendar **Business Large** version.</div>' );

            $this->show_col_section( array( 
                                          array( 'h4'   => 'Separate Booking Admin Panels for your Users',
                                                 'text' => 
  '<p>' . 'You can activate **independent booking admin panels** for each registered wordpress **users of your website** *(withing one website)*. ' . '</p>'
. '<p>' . 'Such users *(**owners**)* can **see and manage only own bookings** and booking resources. 
           Other active users *(owners)* will not see the bookings from this owner, they can see only own bookings.' . '</p>' 
                                              
. '<p>' . 'Each *owner* can **configure own booking form**  and **own email templates**, activate and configure payment gateways to **own payment account**. 
           <br />Such users will receive notifications about new bookings to own emails and can approve or decline such  bookings. 
           ' . '</p>'  

. '<p>' . 'There are 2 types of the users: **super booking admin** and **regular users**. 
          Super booking admins can see and manage the bookings and booking resources from any users. Super booking admin can activate and manage status of other users.' . '</p>' 

. '<p>' . 'You can read more about the initial configuration at <a href="https://wpbookingcalendar.com/faq/multiuser-version-init-config/" target="_blank">FAQ</a>.' . '</p>'   

                                              ) 
                                        , array(  'img'  => 'premium/users2.png', 'img_style'=>'margin-top:20px;width:95%;' ) 
                                        ) 
                                    );              
            
            $this->show_separator();
            
            ?><div class="clear" style="height:30px;"></div><?php
            
            
            ?>
            <table class="about-text" style="margin-bottom:30px;height:auto;font-size:1.1em;width:100%;" >
                <tr>
                    <td>
<?php
                            printf( 'Start using %scurrent version%s of Booking Calendar or upgrade to higher version'
                                    , '<a class="button-secondary" style="height: 36px; line-height: 32px;font-size:15px;margin-top: -3px;" href="'
                                      . wpbc_get_bookings_url() .'" >'
                                    , '</a>' 
                                    );
                            ?>
                            <a class="button button-primary" style="font-weight: 600; height: 36px; line-height: 32px;font-size:15px;margin-top: -3px;"  href="<?php echo wpbc_up_link(); ?>" target="_blank">&nbsp;<?php if ( wpbc_get_ver_sufix() == '' ) { _e('Purchase' ,'booking'); } else { _e('Upgrade Now' ,'booking'); } ?>&nbsp;&nbsp;</a>
                    </td>
                </tr>
            </table> 
            
        </div>
        <?php
    }

}

$wpbc_welcome = new WPBC_Welcome();