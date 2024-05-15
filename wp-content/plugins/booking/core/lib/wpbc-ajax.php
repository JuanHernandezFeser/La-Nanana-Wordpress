<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage Ajax Responder
 * @category Bookings
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.05.26
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly

////////////////////////////////////////////////////////////////////////////////
//    S u p p o r t    f u n c t i o n s    f o r     A j a x    ///////////////
////////////////////////////////////////////////////////////////////////////////

// Verify the nonce.    
function wpdev_check_nonce_in_admin_panel( $action_check = 'wpbc_ajax_admin_nonce' ){    
    
    $nonce = ( isset($_REQUEST['wpbc_nonce']) ) ? $_REQUEST['wpbc_nonce'] : '';

	if ( '' === $nonce ) return false;	// Its was request  from  some other plugin										//FixIn: 7.2.1.10

    if ( ! wp_verify_nonce( $nonce, $action_check ) ) {                         // This nonce is not valid.     
        ?>
        <script type="text/javascript">
			if (jQuery("#ajax_respond").length > 0 ){
				jQuery( "#ajax_respond" ).after( "<div class='wpdevelop'><div class='alert alert-warning alert-danger'><?php
					printf( __( '%sError!%s Request do not pass security check! Please refresh the page and try one more time.', 'booking' ), '<strong>', '</strong>' );
					echo '<br/>' . sprintf( __( 'Please check more here %s', 'booking' ), 'https://wpbookingcalendar.com/faq/request-do-not-pass-security-check' );        //FixIn: 8.8.3.6
					?></div></div>" );
			} else if (jQuery(".ajax_respond_insert").length > 0 ){
				jQuery( ".ajax_respond_insert" ).after( "<div class='wpdevelop'><div class='alert alert-warning alert-danger'><?php
					printf( __( '%sError!%s Request do not pass security check! Please refresh the page and try one more time.', 'booking' ), '<strong>', '</strong>' );
					echo '<br/>' . sprintf( __( 'Please check more here %s', 'booking' ), 'https://wpbookingcalendar.com/faq/request-do-not-pass-security-check/' );        //FixIn: 8.8.3.6
					?></div></div>" );
			}
			if ( jQuery( "#ajax_message" ).length ){
				jQuery( "#ajax_message" ).slideUp();
			}
        </script>
        <?php
        die;                
    } 
	return  true;																										//FixIn: 7.2.1.10
}

// Alias
function wpbc_check_nonce_in_admin_panel( $action_check = 'wpbc_ajax_admin_nonce' ){        
    return wpdev_check_nonce_in_admin_panel( $action_check );
}

//FixIn: 8.4.5.1	function wpbc_check_ajax_locale__reload_it($locale)  moved to ../wp-content/plugins/booking/core/wpbc-translation.php

////////////////////////////////////////////////////////////////////////////////
//    A j a x    H o o k s    f o r    s p e c i f i c    A c t i o n s    /////
////////////////////////////////////////////////////////////////////////////////



//FixIn: Flex TimeLine 1.0
function wpbc_ajax_WPBC_FLEXTIMELINE_NAV() {

        // if ( ! wpdev_check_nonce_in_admin_panel( $_POST['action'] ) ) return false;  //FixIn: 7.2.1.10          // This line for admin panel


        $nonce = ( isset($_REQUEST['wpbc_nonce']) ) ? $_REQUEST['wpbc_nonce'] : '';
        if ( ! wp_verify_nonce( $nonce, $_POST['action'] ) ) {                  // This nonce is not valid.
            wp_die(
            			sprintf(__('%sError!%s Request do not pass security check! Please refresh the page and try one more time.' ,'booking'),'<strong>','</strong>')
			. '<br/>' . sprintf( __( 'Please check more %shere%s', 'booking' ), '<a href="https://wpbookingcalendar.com/faq/request-do-not-pass-security-check/" target="_blank">', '</a>.' )      //FixIn: 8.8.3.6
			);                                                         // Its prevent of showing '0' et  the end of request.
        }
        make_bk_action('wpbc_ajax_flex_timeline');
        wp_die('');                                                             // Its prevent of showing '0' et  the end of request.
}


function wpbc_ajax_CALCULATE_THE_COST() {
    
        if ( ! wpdev_check_nonce_in_admin_panel( $_POST['action'] ) ) return false;  //FixIn: 7.2.1.10
        make_bk_action('wpdev_ajax_show_cost');        
}



//FixIn: 9.6.3.5

function wpbc_ajax_UPDATE_APPROVE() {
                    
    global $wpdb;

    if ( ! wpdev_check_nonce_in_admin_panel() ) return false;  //FixIn: 7.2.1.10
	
    make_bk_action('check_multiuser_params_for_client_side_by_user_id', $_POST['user_id'] );

    // Approve or Reject
    $is_approve_or_pending = $_POST[ "is_approve_or_pending" ];
    if ($is_approve_or_pending == 1) 
        $is_approve_or_pending = '1';
    else                             
        $is_approve_or_pending = '0';

    $booking_id         = $_POST[ "booking_id" ];
    $approved_id        = explode('|',$booking_id);
    $approved_id = wpbc_clean_digit_or_csd( $approved_id );    //FixIn: 8.4.5.15

    if (! isset($_POST["denyreason"])) 
        $_POST["denyreason"] = '';
    $denyreason     = stripslashes( $_POST["denyreason"] );                     				//FixIn: 7.0.1.46       - translate words like don\'t to don't
    $is_send_emeils = $_POST["is_send_emeils"];


    if ( ( count($approved_id) > 0 ) && ( $approved_id !== false ) ) {

        $approved_id_str = join( ',', $approved_id);
        $approved_id_str = wpbc_clean_digit_or_csd( $approved_id_str );

        if ( false === $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}bookingdates SET approved = %s WHERE booking_id IN ({$approved_id_str})", $is_approve_or_pending ) ) ){
            ?> <script type="text/javascript"> 
                var my_message = '<?php echo html_entity_decode( esc_js( get_debuge_error('Error during updating to DB' ,__FILE__,__LINE__) ),ENT_QUOTES) ; ?>';
                wpbc_admin_show_message( my_message, 'error', 30000 );                                                                                                                                                                                                  
               </script> <?php
            die();
        }

	    //FixIn: 8.6.1.10
        $curr_user = get_user_by( 'id', (int) $_POST['user_id'] );
        $user_info = $curr_user->first_name . ' ' . $curr_user->last_name . ' (' . $curr_user->user_email . ')';		// get_user_meta( $curr_user->ID, 'nickname' )
        wpbc_db__add_log_info( explode(',',$approved_id_str),
	        				( ( $is_approve_or_pending == '1' ) ? __( 'Approved by:', 'booking' ) : __( 'Declined by:', 'booking' ) )
							. ' ' . $user_info );

        wpbc_db_update_number_new_bookings( explode(',', $approved_id_str) );

	    do_action( 'wpbc_booking_approved', $approved_id_str, $is_approve_or_pending );                                	//FixIn: 8.7.6.1

        if ($is_approve_or_pending == '1') {
            if ( ! empty($is_send_emeils ) )                                    //FixIn: 7.0.1.5
                wpbc_send_email_approved($approved_id_str, $is_send_emeils,$denyreason);
            $all_bk_id_what_canceled = apply_bk_filter('cancel_pending_same_resource_bookings_for_specific_dates', false, $approved_id_str );         
        } else {
            if ( ! empty($is_send_emeils ) )
                wpbc_send_email_deny($approved_id_str, $is_send_emeils,$denyreason);
        }

        ?>  <script type="text/javascript">
                <?php foreach ($approved_id as $bk_id) {
                        if ($is_approve_or_pending == '1') { ?>
                            set_booking_row_approved_in_timeline(<?php echo $bk_id ?>);
                            set_booking_row_approved(<?php echo $bk_id ?>);
                            set_booking_row_read(<?php echo $bk_id ?>);
                        <?php } else { ?>
                            set_booking_row_pending_in_timeline(<?php echo $bk_id ?>);
                            set_booking_row_pending(<?php echo $bk_id ?>);
                        <?php }?>
                <?php } ?>
                <?php if ($is_approve_or_pending == '1') { ?>    
                    var my_message = '<?php echo html_entity_decode( esc_js( __('Set as Approved' ,'booking') ),ENT_QUOTES) ; ?>';
                <?php } else { ?>    
                    var my_message = '<?php echo html_entity_decode( esc_js( __('Set as Pending' ,'booking') ),ENT_QUOTES) ; ?>';
                <?php } ?>    
                wpbc_admin_show_message( my_message, 'success', 3000 );
            </script> <?php
    }
}


//FixIn: 6.1.1.10       
function wpbc_ajax_TRASH_RESTORE() {
    global $wpdb;
    
    if ( ! wpdev_check_nonce_in_admin_panel() ) return false;  //FixIn: 7.2.1.10
	
    make_bk_action('check_multiuser_params_for_client_side_by_user_id', $_POST['user_id'] );

    $booking_id = $_POST[ "booking_id" ];         // Booking ID

    if ( ! isset($_POST["denyreason"] ) ) 
        $_POST["denyreason"] = '';
    $denyreason = stripslashes( $_POST["denyreason"] );                     //FixIn: 7.0.1.46       - trasnalte words like don\'t to don't
    if (       ( $denyreason == __('Reason for cancellation here' ,'booking')) 
            || ( $denyreason == __('Reason of cancellation here' ,'booking')) 
            || ( $denyreason == 'Reason of cancel here') 
        ) $denyreason = '';
    $is_send_emeils = $_POST["is_send_emeils"];

    $approved_id    = explode('|',$booking_id);
	$approved_id = wpbc_clean_digit_or_csd( $approved_id );    //FixIn: 8.4.5.15

    $is_trash = intval( $_POST["is_trash"] );

    if ( (count($approved_id)>0) && ($approved_id !=false) && ($approved_id !='')) {

        $approved_id_str = join( ',', $approved_id);
        $approved_id_str = wpbc_clean_like_string_for_db( $approved_id_str );

        do_action( 'wpbc_booking_trash', $booking_id, $is_trash );                                						//FixIn: 8.7.6.2

        if ( $is_trash ) {
        	if ( ! empty( $is_send_emeils ) ) {    //FixIn: 8.1.3.35
		        wpbc_send_email_trash( $approved_id_str, $is_send_emeils, $denyreason );
	        }
        } else {
        	if ( ! empty( $is_send_emeils ) ) {    //FixIn: 8.1.3.35
		        // wpbc_send_email_approved($approved_id_str, $is_send_emeils,$denyreason);									//FixIn: 8.1.2.7
	        }
        }   
        
        if ( false === $wpdb->query( "UPDATE {$wpdb->prefix}booking AS bk SET bk.trash = {$is_trash} WHERE booking_id IN ({$approved_id_str})" ) ){
            ?> <script type="text/javascript"> 
                    var my_message = '<?php echo html_entity_decode( esc_js( get_debuge_error('Error during trash booking in DB' ,__FILE__,__LINE__) ),ENT_QUOTES) ; ?>';
                    wpbc_admin_show_message( my_message, 'error', 30000 );                                                                                                                                                                                                                  
            </script> <?php
            die();
        }

        // Update the Hash and Cost  of the booking
		$booking_id_arr = explode(',', $approved_id_str );					//FixIn: 8.6.1.11
		foreach ( $booking_id_arr as $booking_id ) {
			wpbc_hash__update_booking_hash( $booking_id );
		}

        ?>  <script type="text/javascript">
                <?php 
                
                if ( $is_trash ) {
                    
                    foreach ($approved_id as $bk_id) { 
                        ?>
                        set_booking_row_trash(<?php echo $bk_id ?>);    
                        //set_booking_row_deleted_in_timeline(<?php echo $bk_id ?>);
                        //setTimeout(function() { set_booking_row_deleted(<?php echo $bk_id ?>); }, 1000);
                        <?php               
                    }
                    ?>  
                    var my_message = '<?php echo html_entity_decode( esc_js( __('Moved to trash' ,'booking') ),ENT_QUOTES) ; ?>';
                    wpbc_admin_show_message( my_message, 'success', 3000 );                    
                    <?php
                } else { 
                    foreach ($approved_id as $bk_id) {
                        ?> set_booking_row_restore(<?php echo $bk_id ?>); <?php
                    }                    
                    ?>
                    var my_message = '<?php echo html_entity_decode( esc_js( __('Restored' ,'booking') ),ENT_QUOTES) ; ?>';
                    wpbc_admin_show_message( my_message, 'success', 3000 );                    
                <?php                 
                } 
                ?>
            </script>
        <?php        
    }        
}

//FixIn: 9.6.3.5


function wpbc_ajax_DELETE_APPROVE() {
        
    global $wpdb;
    
    if ( ! wpdev_check_nonce_in_admin_panel() ) return false;  //FixIn: 7.2.1.10
	
    make_bk_action('check_multiuser_params_for_client_side_by_user_id', $_POST['user_id'] );

    $booking_id = $_POST[ "booking_id" ];         // Booking ID
    if ( ! isset($_POST["denyreason"] ) ) 
        $_POST["denyreason"] = '';
    $denyreason = stripslashes( $_POST["denyreason"] );                     //FixIn: 7.0.1.46       - trasnalte words like don\'t to don't
    if (       ( $denyreason == __('Reason for cancellation here' ,'booking')) 
            || ( $denyreason == __('Reason of cancellation here' ,'booking')) 
            || ( $denyreason == 'Reason of cancel here') 
        ) $denyreason = '';
    $is_send_emeils = $_POST["is_send_emeils"];
    $approved_id    = explode('|',$booking_id);
	$approved_id = wpbc_clean_digit_or_csd( $approved_id );    //FixIn: 8.4.5.15

    if ( (count($approved_id)>0) && ($approved_id !=false) && ($approved_id !='')) {

        $approved_id_str = join( ',', $approved_id);
        $approved_id_str = wpbc_clean_like_string_for_db( $approved_id_str );

        do_action( 'wpbc_booking_delete', $approved_id_str );															//FixIn: 8.7.6.3

		if ( ! empty( $is_send_emeils ) ) {    //FixIn: 8.1.3.35
			wpbc_send_email_deleted( $approved_id_str, $is_send_emeils, $denyreason );
		}

        if ( false === $wpdb->query( "DELETE FROM {$wpdb->prefix}bookingdates WHERE booking_id IN ({$approved_id_str})" ) ){
            ?> <script type="text/javascript"> 
                    var my_message = '<?php echo html_entity_decode( esc_js( get_debuge_error('Error during deleting dates in DB' ,__FILE__,__LINE__) ),ENT_QUOTES) ; ?>';
                    wpbc_admin_show_message( my_message, 'error', 30000 ); 
                </script> <?php
            die();
        }

        if ( false === $wpdb->query( "DELETE FROM {$wpdb->prefix}booking WHERE booking_id IN ({$approved_id_str})" ) ){
            ?> <script type="text/javascript"> 
                    var my_message = '<?php echo html_entity_decode( esc_js( get_debuge_error('Error during deleting booking in  DB' ,__FILE__,__LINE__) ),ENT_QUOTES) ; ?>';
                    wpbc_admin_show_message( my_message, 'error', 30000 );                                                                                                                                                                                                                  
            </script> <?php
            die();
        }
        ?>
            <script type="text/javascript">
                <?php foreach ($approved_id as $bk_id) { ?>
                    set_booking_row_deleted_in_timeline(<?php echo $bk_id ?>);
                    set_booking_row_deleted(<?php echo $bk_id ?>);
                <?php } ?>
                var my_message = '<?php echo html_entity_decode( esc_js( __('Deleted' ,'booking') ),ENT_QUOTES) ; ?>';
                wpbc_admin_show_message( my_message, 'success', 3000 );                                                                                                                                               
            </script>
        <?php        
    }
}


function wpbc_ajax_DELETE_BY_VISITOR() {
        
    if ( ! wpdev_check_nonce_in_admin_panel( $_POST['action'] ) ) return false;  //FixIn: 7.2.1.10
	
    make_bk_action('wpdev_delete_booking_by_visitor');
        
}





function wpbc_ajax_DELETE_BK_FORM() {
        
    if ( ! wpdev_check_nonce_in_admin_panel() ) return false;  //FixIn: 7.2.1.10
    make_bk_action('check_multiuser_params_for_client_side_by_user_id', $_POST['user_id'] );
    make_bk_action('wpbc_make_delete_custom_booking_form');          
}


function wpbc_ajax_USER_SAVE_WINDOW_STATE() {
        
//    if ( ! wpdev_check_nonce_in_admin_panel() ) return false;  //FixIn: 7.2.1.10
//    update_user_option($_POST['user_id'],'booking_win_' . $_POST['window'] ,$_POST['is_closed']);
    
	if ( ! wpbc_check_nonce_in_admin_panel() ) return false;

    update_user_option( (int) $_POST['user_id'], 'booking_win_' . esc_attr( $_POST['window'] ) , (int) $_POST['is_closed'] );
    
	wp_send_json_success();				//FixIn: 7.2.1.10.2	//Fix "400 Bad Request" error showing. At some situations,  if Ajax request  does not return anything,  its will  generate an issue
}


/** Save Custom User Data */
function wpbc_ajax_USER_SAVE_CUSTOM_DATA() {
            
    if ( ! wpbc_check_nonce_in_admin_panel() ) return false;
    /*  Exmaple of $_POST:
        [data_name] => add_booking_calendar_options
        [data_value] => calendar_months_count=1&calendar_months_num_in_1_row=1&calendar_width=500px&calendar_cell_height
     */
    $post_param = explode( '&', $_POST['data_value'] );                         // "&" was set by jQuery.param( data_params ) in client side.
    $data_to_save = array();
    foreach ( $post_param as $param ) {
        $param_data = explode( '=', $param );
                
        $data_to_save[ $param_data[0] ] = ( isset( $param_data[1] ) ) ? esc_attr( $param_data[1] ) : '';
    }
    /*  Exmaple: 
        Array
        (
            [calendar_months_count] => 1
            [calendar_months_num_in_1_row] => 1
            [calendar_width] => 500px
            [calendar_cell_height] => 
        )
     */

    // Save Custom User Data
    update_user_option( (int) $_POST['user_id'], 'booking_custom_' . esc_attr( $_POST['data_name'] ) ,  serialize( $data_to_save ) ); 

    ?>  <script type="text/javascript">            
            var my_message = '<?php echo html_entity_decode( esc_js( __('Saved' ,'booking') ),ENT_QUOTES) ; ?>';
            wpbc_admin_show_message( my_message, 'success', 1000 ); 
            <?php if ( ! empty( $_POST['is_reload'] ) == 1 ) { ?>
            setTimeout(function ( ) {location.reload(true);} ,1500);
            <?php } ?>
        </script> <?php
    die();
    
}




function wpbc_ajax_BOOKING_SEARCH() {
        
    if ( ! wpdev_check_nonce_in_admin_panel( $_POST['action'] ) ) return false;  //FixIn: 7.2.1.10
	
    make_bk_action('wpdev_ajax_booking_search');        
}


function wpbc_ajax_CHECK_BK_NEWS() {
        
    if ( ! wpdev_check_nonce_in_admin_panel() ) return false;  //FixIn: 7.2.1.10
    wpdev_ajax_check_bk_news();
}


function wpbc_ajax_CHECK_BK_FEATURES() {
        
    if ( ! wpdev_check_nonce_in_admin_panel() ) return false;  //FixIn: 7.2.1.10
    wpdev_ajax_check_bk_news('info/features/');
}


function wpbc_ajax_CHECK_BK_VERSION() {
    
    if ( ! wpdev_check_nonce_in_admin_panel() ) return false;  //FixIn: 7.2.1.10
    wpdev_ajax_check_bk_version();
}





////////////////////////////////////////////////////////////////////////////////
//    R u n     A j a x                       //////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
if (  is_admin() && ( defined( 'DOING_AJAX' ) ) && ( DOING_AJAX )  ) {

	//FixIn: 8.9.4.5

    // Hooks list 
    $actions_list = array(
							 'WPBC_FLEXTIMELINE_NAV'                => 'both'		//FixIn: Flex TimeLine 1.0
                            ,'CALCULATE_THE_COST'                   => 'both'


                            ,'UPDATE_APPROVE'               => 'admin'
                            ,'DELETE_APPROVE'               => 'admin'
                            ,'DELETE_BY_VISITOR'                    => 'both'
                            ,'TRASH_RESTORE'                => 'admin'          //FixIn: 6.1.1.10

                            ,'DELETE_BK_FORM'               => 'admin'
                            ,'USER_SAVE_WINDOW_STATE'       => 'admin'
                            ,'USER_SAVE_CUSTOM_DATA'        => 'admin'
                            ,'BOOKING_SEARCH'                       => 'both'
                            ,'CHECK_BK_NEWS'                => 'admin'
                            ,'CHECK_BK_FEATURES'            => 'admin'
                            ,'CHECK_BK_VERSION'             => 'admin'
							
							, 'WPBC_IMPORT_ICS_URL'			=> 'admin'			//FixIn: 7.3
                         );
          
    $actions_list = apply_filters( 'wpbc_ajax_action_list', $actions_list );

    foreach ($actions_list as $action_name => $action_where) {
        
        if ( ( isset($_POST['action']) ) && ( $_POST['action'] == $action_name ) ){
            
            if ( ( $action_where == 'admin' ) || ( $action_where == 'both' ) ) 
                add_action( 'wp_ajax_'        . $action_name, 'wpbc_ajax_' . $action_name);      // Admin & Client (logged in usres)
            
            if ( ( $action_where == 'both' ) || ( $action_where == 'client' ) ) 
                add_action( 'wp_ajax_nopriv_' . $action_name, 'wpbc_ajax_' . $action_name);      // Client         (not logged in)        
        }
    }  
} 