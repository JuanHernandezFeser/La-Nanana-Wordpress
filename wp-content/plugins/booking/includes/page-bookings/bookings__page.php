<?php
/*
* @package: AJX_Bookings Page
* @category: o Email Reminders
* @description: Define AJX_Bookings in admin settings page. - Sending friendly email reminders based on custom ajx_booking.
* Plugin URI: https://oplugins.com/plugins/email-reminders/#premium
* Author URI: https://oplugins.com
* Author: wpdevelop, oplugins
* Version: 0.0.1
* @modified 2020-05-11
*/
//FixIn: 9.2.1
if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/** Show Content
 *  Update Content
 *  Define Slug
 *  Define where to show
 */
class WPBC_Page_AJX_Bookings extends WPBC_Page_Structure {

    public function __construct() {

        parent::__construct();

        // Redefine TAGs Names,  becasue 'tab' slug already used in the system  for definition  of active toolbar.
        $this->tags['tab']    = 'view_mode';
        $this->tags['subtab'] = 'bottom_nav';
    }

    public function in_page() {
        return 'wpbc';
    }

    public function tabs() {

        $tabs = array();
        $tabs[ 'vm_booking_listing' ] = array(
                              'title'		=> __( 'Booking Listing', 'booking' )						// Title of TAB
                            , 'hint'		=> __( 'Booking Listing', 'booking' )						// Hint
                            , 'page_title'	=> __( 'Booking Listing', 'booking' )			// Title of Page
                            , 'link'		=> ''								// Can be skiped,  then generated link based on Page and Tab tags. Or can  be extenral link
                            , 'position'	=> ''                               // 'left'  ||  'right'  ||  ''
                            , 'css_classes' => ''                               // CSS class(es)
                            , 'icon'		=> ''                               // Icon - link to the real PNG img
                            , 'font_icon'	=> 'wpbc-bi-collection'			// CSS definition  of forn Icon				//FixIn: 9.5.5.3
                            , 'header_font_icon'	=> 'wpbc-bi-collection'			// CSS definition  of forn Icon				//FixIn: 9.5.5.3
                            , 'default'		=> false								// Is this tab activated by default or not: true || false.
                            , 'disabled'	=> false                            // Is this tab disabled: true || false.
                            , 'hided'		=> true                             // Is this tab hided: true || false.
                            , 'subtabs'		=> array()
        );
        // $subtabs = array();
        // $tabs[ 'items' ][ 'subtabs' ] = $subtabs;
        return $tabs;
    }

    public function content() {

        do_action( 'wpbc_hook_settings_page_header', 'page_booking_listing');							// Define Notices Section and show some static messages, if needed.

	    if ( ! wpbc_is_mu_user_can_be_here( 'activated_user' ) ) {  return false;  }  					// Check if MU user activated, otherwise show Warning message.

//?? if ( ! wpbc_set_default_resource_to__get() ) return false;                  // Define default booking resources for $_GET  and  check if booking resource belong to user.


		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Get and escape request parameters
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$user_request = new WPBC_AJX__REQUEST( array(
												   'db_option_name'          => 'booking_listing_request_params',
												   'user_id'                 => wpbc_get_current_user_id(),
												   'request_rules_structure' => wpbc_ajx_get__request_params__names_default()
												)
						);

		$escaped_search_request_params = false;
	    if ( empty( $_REQUEST['overwrite'] ) ) {
		    $escaped_search_request_params = $user_request->get_sanitized__saved__user_request_params();				// Get Saved
	    }

		if (
			   ( false === $escaped_search_request_params )
			|| ( ! empty( $_REQUEST['overwrite'] ) )
		){							// This request was not saved before, then get sanitized direct parameters, like: 	$_REQUEST['resource_id']

			$request_prefix = false;
			$escaped_search_request_params = $user_request->get_sanitized__in_request__value_or_default( $request_prefix  ); 		// Direct: 	$_REQUEST['resource_id']
		}

		////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        // Submit  /////////////////////////////////////////////////////////////
        $submit_form_name = 'wpbc_ajx_booking_form';                             	// Define form name

		?><span class="wpdevelop"><?php                                         // BS UI CSS Class

			wpbc_js_for_bookings_page();                                            // JavaScript functions

			wpbc_welcome_panel();                                                   // Welcome Panel (links)

			wpbc_ajx_bookings_toolbar( $escaped_search_request_params );

		?></span><?php                                         // BS UI CSS Class

		?><div id="wpbc_log_screen" class="wpbc_log_screen"></div><?php

        // Content  ////////////////////////////////////////////////////////////
        ?>
        <div class="clear" style="margin-bottom:10px;"></div>
        <span class="metabox-holder">
            <form  name="<?php echo $submit_form_name; ?>" id="<?php echo $submit_form_name; ?>" action="" method="post" >
                <?php
                   // N o n c e   field, and key for checking   S u b m i t
                   wp_nonce_field( 'wpbc_settings_page_' . $submit_form_name );
                ?><input type="hidden" name="is_form_sbmitted_<?php echo $submit_form_name; ?>" id="is_form_sbmitted_<?php echo $submit_form_name; ?>" value="1" /><?php

				///wpbc_ajx_booking_modify_container_show();					// Container for showing Edit ajx_booking and define Edit and Delete ajx_booking JavaScript vars.

	            ?><div class="wpbc_ajx_under_toolbar_row"><?php

					wpbc_ajx__ui__booking_sorting( $escaped_search_request_params , wpbc_ajx_get__request_params__names_default( 'default' ) );

					?><div class="wpbc_ajx_booking_pagination"></div><?php		// Pagination  container at  head

					//	Is send Emails
		            wpbc_ajx__ui__booking__no_toolbar__is_email_sending( $escaped_search_request_params, wpbc_ajx_get__request_params__names_default( 'default' ) );        //FixIn: 9.6.1.5

                ?></div><?php

	            wpbc_clear_div();

	            $is_test_sql_directly = false;
	            if ( ! $is_test_sql_directly ) {

		            $this->show_ajx_booking_listing_container_ajax( $escaped_search_request_params );

		            $this->show_pagination_container();

	            } else {

		            $this->show_ajx_booking_listing_container_directly();            // Useful  for direct  showing of listing without the ajax request,  its requirement  JavaScript to  show data in template!!
	            }

				?><div class="clear"></div><?php

		  ?></form>
        </span>
        <?php

        do_action( 'wpbc_hook_settings_page_footer', 'wpbc-ajx_booking' );

		wpbc_show_booking_footer(); 	// Show rating line
    }

		private function show_pagination_container(){
			?>
			<div class="wpbc_ajx_booking_pagination"></div>
			<?php
			wpbc_clear_div();

			$wpbc_pagination = new WPBC_Pagination();
			$wpbc_pagination->init( array(
											'load_on_page'  => 'wpbc-ajx_booking',
											'container'     => '.wpbc_ajx_booking_pagination',
											'on_click'	    => 'wpbc_ajx_booking_pagination_click'		// onclick = "javascript: wpbc_ajx_booking_pagination_click( page_num );"  - need to  define this function in JS file
			));

			/**
			$wpbc_pagination->show( array(												        	// Its showing with  JavaScript on document ready
											'page_active' => 3,
											'pages_count' => 20
			));
			/**/
		}


		private function show_ajx_booking_listing_container_ajax( $escaped_search_request_params ) {

			?>
			<div class="wpbc_listing_container wpbc_selectable_table wpbc_ajx_booking_listing_container">
				<div style="width:100%;text-align: center;"><span class="wpbc_icn_autorenew wpbc_spin"></span><span><?php _e('Loading','booking'); ?>...</span></div>
			</div>
			<script type="text/javascript">
				jQuery( document ).ready( function (){

					// Set Security - Nonce for Ajax  - Listing
					wpbc_ajx_booking_listing.set_secure_param( 'nonce',   '<?php echo wp_create_nonce( 'wpbc_ajx_booking_listing_ajx' . '_wpbcnonce' ) ?>' );
					wpbc_ajx_booking_listing.set_secure_param( 'user_id', '<?php echo wpbc_get_current_user_id();  ?>' );
					wpbc_ajx_booking_listing.set_secure_param( 'locale',  '<?php echo get_user_locale(); ?>' );

					// Set other parameters
					wpbc_ajx_booking_listing.set_other_param( 'listing_container',    '.wpbc_ajx_booking_listing_container' );
					wpbc_ajx_booking_listing.set_other_param( 'pagination_container', '.wpbc_ajx_booking_pagination' );

					// Send Ajax request and show listing after this.
					wpbc_ajx_booking_send_search_request_with_params( <?php echo wp_json_encode( $escaped_search_request_params ); ?> );
				} );
			</script>
			<?php
		}


		private function show_ajx_booking_listing_container_directly(){


    		//TODO: We need to  send Ajax request  and then  show the listing (its will make one same way  of showing listing and pagination)!


			$my_ajx_booking = new WPBC_AJX_Bookings;

			////////////////////////////////////
			// 0. Check Nonce if Ajax ( ! used now )
			////////////////////////////////////
			if ( 0 ){
				$action_name    = 'wpbc_search_field' . '_wpbcnonce';                                                           //   $_POST['element_id'] . '_wpbcnonce';
				$nonce_post_key = 'nonce';
				$result_check   = check_ajax_referer( $action_name, $nonce_post_key );
			}

			////////////////////////////////////
			// 1. Direct Clean Params
			////////////////////////////////////
			$request_params_ajx_booking  = array(
									  'page_num'          => array( 'validate' => 'd', 					'default' => 1 )
									, 'page_items_count'  => array( 'validate' => 'd', 					'default' => 10 )
									, 'sort'              => array( 'validate' => array( 'booking_id' ),	'default' => 'booking_id' )
									, 'sort_type'         => array( 'validate' => array( 'ASC', 'DESC'),'default' => 'DESC' )
									, 'status'            => array( 'validate' => 's', 					'default' => '' )
									, 'keyword'           => array( 'validate' => 's', 					'default' => '' )
									, 'ru_create_date'       => array( 'validate' => 'date', 				'default' => '' )
			);
			$request_params_values = array(                                                                             // Usually 		$request_params_values 	is  $_REQUEST
									'page_num'         => 1,
									'page_items_count' => 3,
									'sort'             => 'booking_id',
									'sort_type'        => 'DESC',
									'status'           => '',
									'keyword'          => '',
									'ru_create_date'	   => ''
							);
			$request_params = wpbc_sanitize_params_in_arr( $request_params_values, $request_params_ajx_booking );

			////////////////////////////////////
			// 2. Get items array from DB
			////////////////////////////////////
			$items_arr = wpbc_ajx_get_booking_data_arr( $request_params );
debuge($items_arr);

			// Show Pagination          -       $total_num_of_items_in_all_pages = $sql_res[ [ 'count' ] ];
//			$wpbc_pagination->show_pagination(
//												$request_params_values['page_num'],
//												ceil( $sql_res[ [ 'count' ] ] / $request_params_values['page_items_count'] )
//								);

		}

}
add_action('wpbc_menu_created', array( new WPBC_Page_AJX_Bookings() , '__construct') );    // Executed after creation of Menu