/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage BackEnd Main Script Lib
 * @category Scripts
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.09.10
 */


/**
	 * Set Booking listing row as   R e a d
 * 
 * @param {type} booking_id
 * @returns {undefined}
 */
function set_booking_row_read(booking_id){
    if (booking_id == 0) {
        jQuery('.new-label').addClass('hidden_items');
        jQuery('.bk-update-count').html( '0' );
    } else {
        jQuery('#booking_mark_'+booking_id + '').addClass('hidden_items');
        decrese_new_counter();
    }
}


//FixIn: 9.6.3.5

/**
	 * Decrease counter about new bookings
 * 
 * @returns {undefined}
 */
function decrese_new_counter () {
    var my_num = parseInt(jQuery('.bk-update-count').html());
    if (my_num>0){
        my_num = my_num - 1;
        jQuery('.bk-update-count').html(my_num);
    }
}


/**
	 * Functions for the TimeLine
 * 
 * @param {type} booking_id
 * @returns {undefined}
 */
function set_booking_row_approved_in_timeline( booking_id ){
    ////Approve   Add    to   [cell_bk_id_9] class [approved]    -- TODO: Also  in the [a_bk_id_9] - chnaged "data-content" attribute
    jQuery( '.cell_bk_id_' + booking_id ).addClass( 'approved' );
    jQuery( '.timeline_info_bk_actionsbar_' + booking_id + ' .approve_bk_link' ).addClass( 'hidden_items' );
    jQuery( '.timeline_info_bk_actionsbar_' + booking_id + ' .pending_bk_link' ).removeClass( 'hidden_items' );

    set_booking_row_approved_in_flextimeline( booking_id );
}

function set_booking_row_pending_in_timeline( booking_id ){
    //Remove    Remove from [cell_bk_id_9] class [approved]      -- TODO: Also  in the [a_bk_id_9] - chnaged "data-content" attribute
    jQuery( '.cell_bk_id_' + booking_id ).removeClass( 'approved' );
    jQuery( '.timeline_info_bk_actionsbar_' + booking_id + ' .pending_bk_link' ).addClass( 'hidden_items' );
    jQuery( '.timeline_info_bk_actionsbar_' + booking_id + ' .approve_bk_link' ).removeClass( 'hidden_items' );

    set_booking_row_pending_in_flextimeline( booking_id );
}

function set_booking_row_deleted_in_timeline( booking_id ){
    //          Remove in [cell_bk_id_9]   classes [time_booked_in_day]
    //          Delete element: [a_bk_id_]
    // TODO: Here is possible issue, if we are have several bookings per the same date and deleted only one

    // make actions on the elements, which are not have CLASS: "here_several_bk_id"
    // And have CLASS a_bk_id_ OR cell_bk_id_        
    jQuery( ':not(.here_several_bk_id).a_bk_id_' + booking_id ).fadeOut( 1000 );
    jQuery( ':not(.here_several_bk_id).cell_bk_id_' + booking_id ).removeClass( 'time_booked_in_day' );

    set_booking_row_deleted_in_flextimeline( booking_id );
}

//FixIn: Flex TimeLine 1.0

function set_booking_row_approved_in_flextimeline( booking_id ){
    jQuery( '.flex_tl_row_bar_show_bookings .booking_id.booking_id_' + booking_id ).removeClass( 'pending_booking' );
    jQuery( '.flex_tl_row_bar_show_bookings .booking_id.booking_id_' + booking_id ).addClass( 'approved_booking' );

    //Popover
    jQuery( '#wpbc-booking-id-' + booking_id + ' .flex-popover-labels-bar .label-pending').addClass( 'hidden_items' );
    jQuery( '#wpbc-booking-id-' + booking_id + ' .flex-popover-labels-bar .label-approved').removeClass( 'hidden_items' );
    //Dates
    jQuery( '#wpbc-booking-id-' + booking_id + ' .field-booking-date').addClass( 'approved' );
}

function set_booking_row_pending_in_flextimeline( booking_id ){
    jQuery( '.flex_tl_row_bar_show_bookings .booking_id.booking_id_' + booking_id ).removeClass( 'approved_booking' );
    jQuery( '.flex_tl_row_bar_show_bookings .booking_id.booking_id_' + booking_id ).addClass( 'pending_booking' );

    //Popover
    jQuery( '#wpbc-booking-id-' + booking_id + ' .flex-popover-labels-bar .label-pending').removeClass( 'hidden_items' );
    jQuery( '#wpbc-booking-id-' + booking_id + ' .flex-popover-labels-bar .label-approved').addClass( 'hidden_items' );
    //Dates
    jQuery( '#wpbc-booking-id-' + booking_id + ' .field-booking-date').removeClass( 'approved' );
}

function set_booking_row_deleted_in_flextimeline( booking_id ){

    jQuery( '.flex_tl_row_bar_show_bookings .booking_id.booking_id_' + booking_id ).fadeOut( 1000 );
    jQuery( '.flex_tl_row_bar_show_booking_titles .in_cell_date_container_show_booking_titles.booking_id_' + booking_id ).fadeOut( 1000 );

    //jQuery( '.flex_timeline_frame .popover' )
//Deprecated: FixIn: 9.0.1.1.1
// if ( 'function' === typeof( jQuery( ".popover_click.popover_bottom" ).popover ) )       //FixIn: 7.0.1.2  - 2016-12-10
//     jQuery( '.popover_click.popover_bottom' ).popover( 'hide' );                      //Hide all opned popovers

}


// Set Booking listing   R O W   Approved
function set_booking_row_approved(booking_id){
    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-approved').removeClass('hidden_items');
    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-pending').addClass('hidden_items');

    jQuery('#booking_row_'+booking_id + ' .booking-dates .field-booking-date').addClass('approved');

    jQuery('#booking_row_'+booking_id + ' .booking-actions .approve_bk_link').addClass('hidden_items');
    jQuery('#booking_row_'+booking_id + ' .booking-actions .pending_bk_link').removeClass('hidden_items');

}

// Set Booking listing   R O W   Pending
function set_booking_row_pending(booking_id){
    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-approved').addClass('hidden_items');
    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-pending').removeClass('hidden_items');

    jQuery('#booking_row_'+booking_id + ' .booking-dates .field-booking-date').removeClass('approved');

    jQuery('#booking_row_'+booking_id + ' .booking-actions .approve_bk_link').removeClass('hidden_items');
    jQuery('#booking_row_'+booking_id + ' .booking-actions .pending_bk_link').addClass('hidden_items');

}

// Remove  Booking listing   R O W
function set_booking_row_deleted(booking_id){
    jQuery('#booking_row_'+booking_id).fadeOut(1000);        
    jQuery('#gcal_imported_events_id_'+booking_id).remove();
}

//FixIn: 9.6.3.5

// Set in Booking listing   R O W   new Remark in hint
function set_booking_row_payment_status(booking_id, payment_status, payment_status_show){

    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-payment-status').removeClass('label-danger');
    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-payment-status').removeClass('label-success');

    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-payment-status').html(payment_status_show);

    if (payment_status == 'OK') {
        jQuery('#booking_row_'+booking_id + ' .booking-labels .label-payment-status').addClass('label-success');
    } else if (payment_status == '') {
        jQuery('#booking_row_'+booking_id + ' .booking-labels .label-payment-status').addClass('label-danger');
    } else {
        jQuery('#booking_row_'+booking_id + ' .booking-labels .label-payment-status').addClass('label-danger');
    }
}



//FixIn: 9.6.3.5

//FixIn: 5.4.5
/**
 * Get selected Locale of booking in Booking Listing page.
 *
 * @param booking_id                Booking ID              10
 * @param wpdev_active_locale       Active Locale in system 'en_US'
 * @returns {string}                Locale                  'de_DE'
 */
function wpbc_get_selected_locale( booking_id, wpdev_active_locale ){

    var id_to_check = "" + booking_id;
    if ( -1 == id_to_check.indexOf( '|' ) ){ // Not found |
        var selected_locale = jQuery( '#locale_for_booking' + booking_id ).val();

        if ( (selected_locale != '') && (typeof (selected_locale) !== 'undefined') ){
            wpdev_active_locale = selected_locale;
        }
    }
    return wpdev_active_locale;
}


// Approve or set Pending  booking
function approve_unapprove_booking(booking_id, is_approve_or_pending, user_id, wpdev_active_locale, is_send_emeils ) {

    //FixIn: 5.4.5
    wpdev_active_locale = wpbc_get_selected_locale(booking_id,  wpdev_active_locale );

    if ( booking_id !='' ) {

        var ajax_type_action    = 'UPDATE_APPROVE';
        var denyreason          = '';
        if ( is_send_emeils == 1 ) {
            if ( jQuery('#is_send_email_for_pending').length ) {
                is_send_emeils = jQuery( '#is_send_email_for_pending' ).is( ':checked' );       //FixIn: 8.7.9.5
                if ( false === is_send_emeils ) { is_send_emeils = 0; }
                else                            { is_send_emeils = 1; }
            }
            if ( jQuery('#denyreason').length )
                denyreason = jQuery('#denyreason').val();
        } else {
            is_send_emeils = 0;
        }

        wpbc_admin_show_message_processing( '' ); 

        jQuery.ajax({                                           // Start Ajax Sending
            url: wpbc_ajaxurl, 
            type:'POST',
            success: function (data, textStatus){if( textStatus == 'success')   jQuery('#ajax_respond').html( data );},
            error:function (XMLHttpRequest, textStatus, errorThrown){window.status = 'Ajax sending Error status:'+ textStatus;alert(XMLHttpRequest.status + ' ' + XMLHttpRequest.statusText);if (XMLHttpRequest.status == 500) {alert('Please check at this page according this error:' + ' https://wpbookingcalendar.com/faq/#ajax-sending-error');}},
            // beforeSend: someFunction,
            data:{
                // ajax_action : ajax_type_action,         // Action
                action : ajax_type_action,         // Action
                booking_id : booking_id,                  // ID of Booking  - separator |
                is_approve_or_pending : is_approve_or_pending,           // Approve: 1, Reject: 0
                is_send_emeils : is_send_emeils,
                denyreason: denyreason,
                user_id: user_id,
                wpdev_active_locale:wpdev_active_locale,
                wpbc_nonce: document.getElementById('wpbc_admin_panel_nonce').value 
            }
        });
        return false;  
    }

    return true;
}

 
//FixIn: 6.1.1.10 
// Set Booking listing   R O W   Trash
function set_booking_row_trash( booking_id ){
    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-trash').removeClass('hidden_items');    
    jQuery('#booking_row_'+booking_id + ' .booking-actions .trash_bk_link').addClass('hidden_items');
    jQuery('#booking_row_'+booking_id + ' .booking-actions .restore_bk_link').removeClass('hidden_items');
    jQuery('#booking_row_'+booking_id + ' .booking-actions .delete_bk_link').removeClass('hidden_items');
    
    // TimeLine    
    jQuery('.cell_bk_id_'+booking_id).addClass('booking_trash');
    jQuery('.timeline_info_bk_actionsbar_'+booking_id + ' .trash_bk_link').addClass('hidden_items');
    jQuery('.timeline_info_bk_actionsbar_'+booking_id + ' .restore_bk_link').removeClass('hidden_items');
    jQuery('.timeline_info_bk_actionsbar_'+booking_id + ' .delete_bk_link').removeClass('hidden_items');
    
    jQuery('#wpbc-booking-id-'+booking_id + ' .label-trash').removeClass('hidden_items');

    set_booking_row_deleted_in_flextimeline( booking_id );  //FixIn: Flex TimeLine 1.0
}

//FixIn: 6.1.1.10 
// Set Booking listing   R O W   Restore
function set_booking_row_restore( booking_id ){    
    jQuery('#booking_row_'+booking_id + ' .booking-labels .label-trash').addClass('hidden_items');    
    jQuery('#booking_row_'+booking_id + ' .booking-actions .trash_bk_link').removeClass('hidden_items');
    jQuery('#booking_row_'+booking_id + ' .booking-actions .restore_bk_link').addClass('hidden_items');
    jQuery('#booking_row_'+booking_id + ' .booking-actions .delete_bk_link').addClass('hidden_items');

    // TimeLine    
    jQuery('.cell_bk_id_'+booking_id).removeClass('booking_trash');
    jQuery('.timeline_info_bk_actionsbar_'+booking_id + ' .trash_bk_link').removeClass('hidden_items');
    jQuery('.timeline_info_bk_actionsbar_'+booking_id + ' .restore_bk_link').addClass('hidden_items');
    jQuery('.timeline_info_bk_actionsbar_'+booking_id + ' .delete_bk_link').addClass('hidden_items');

    jQuery('#wpbc-booking-id-'+booking_id + ' .label-trash').addClass('hidden_items');
}
   
//FixIn: 6.1.1.10 
// Trash or restore booking 
function trash__restore_booking( is_trash, booking_id, user_id, wpdev_active_locale, is_send_emeils ) {

    /////////////////////////////////////////////////////////////////////
    //FixIn: 5.4.5
    wpdev_active_locale = wpbc_get_selected_locale(booking_id,  wpdev_active_locale );

    if ( booking_id !='' ) {

        var ajax_type_action    = 'TRASH_RESTORE';
        var denyreason          = '';
        if (is_send_emeils == 1) {
            if ( jQuery('#is_send_email_for_pending').length ) {
                is_send_emeils = jQuery( '#is_send_email_for_pending' ).is( ':checked' );       //FixIn: 8.7.9.5
                if ( false === is_send_emeils ) { is_send_emeils = 0; }
                else                            { is_send_emeils = 1; }
            }
            if ( jQuery('#denyreason').length )
                denyreason = jQuery('#denyreason').val();
        } else {
            is_send_emeils = 0;
        }

        wpbc_admin_show_message_processing( '' ); 

        jQuery.ajax({                                           // Start Ajax Sending
            url: wpbc_ajaxurl, 
            type:'POST',
            success: function (data, textStatus){if( textStatus == 'success')   jQuery('#ajax_respond').html( data );},
            error:function (XMLHttpRequest, textStatus, errorThrown){window.status = 'Ajax sending Error status:'+ textStatus;alert(XMLHttpRequest.status + ' ' + XMLHttpRequest.statusText);if (XMLHttpRequest.status == 500) {alert('Please check at this page according this error:' + ' https://wpbookingcalendar.com/faq/#ajax-sending-error');}},
            // beforeSend: someFunction,
            data:{
                //ajax_action : ajax_type_action,         // Action
                action : ajax_type_action,         // Action
                booking_id : booking_id,                  // ID of Booking  - separator |
                is_send_emeils : is_send_emeils,
                denyreason: denyreason,
                user_id: user_id,
                wpdev_active_locale:wpdev_active_locale,
                is_trash:is_trash,
                wpbc_nonce: document.getElementById('wpbc_admin_panel_nonce').value 
            }
        });
        return false;
    }

    return true;
    
}

// Delete booking
function delete_booking(booking_id, user_id, wpdev_active_locale, is_send_emeils ) {

    //FixIn: 5.4.5
    wpdev_active_locale = wpbc_get_selected_locale(booking_id,  wpdev_active_locale );

    if ( booking_id !='' ) {

        var ajax_type_action    = 'DELETE_APPROVE';
        var denyreason          = '';
        if (is_send_emeils == 1) {
            if ( jQuery('#is_send_email_for_pending').length ) {
                is_send_emeils = jQuery( '#is_send_email_for_pending' ).is( ':checked' );       //FixIn: 8.7.9.5
                if ( false === is_send_emeils ) { is_send_emeils = 0; }
                else                            { is_send_emeils = 1; }
            }
            if ( jQuery('#denyreason').length )
                denyreason = jQuery('#denyreason').val();
        } else {
            is_send_emeils = 0;
        }

        wpbc_admin_show_message_processing( '' ); 

        jQuery.ajax({                                           // Start Ajax Sending
            url: wpbc_ajaxurl, 
            type:'POST',
            success: function (data, textStatus){if( textStatus == 'success')   jQuery('#ajax_respond').html( data );},
            error:function (XMLHttpRequest, textStatus, errorThrown){window.status = 'Ajax sending Error status:'+ textStatus;alert(XMLHttpRequest.status + ' ' + XMLHttpRequest.statusText);if (XMLHttpRequest.status == 500) {alert('Please check at this page according this error:' + ' https://wpbookingcalendar.com/faq/#ajax-sending-error');}},
            // beforeSend: someFunction,
            data:{
                //ajax_action : ajax_type_action,         // Action
                action : ajax_type_action,         // Action
                booking_id : booking_id,                  // ID of Booking  - separator |
                is_send_emeils : is_send_emeils,
                denyreason: denyreason,
                user_id: user_id,
                wpdev_active_locale:wpdev_active_locale,
                wpbc_nonce: document.getElementById('wpbc_admin_panel_nonce').value 
            }
        });
        return false;
    }

    return true;
}

//FixIn: 9.6.3.5

// Get Selected rows in imported Events list
function get_selected_bookings_id_in_this_list( list_tag, skip_id_length ) {

    var checkedd = jQuery( list_tag + ":checked" );
    var id_for_approve = "";

    // get all IDs
    checkedd.each(function(){
        var id_c = jQuery(this).attr('id');
        id_c = id_c.substr(skip_id_length,id_c.length-skip_id_length);
        id_for_approve += id_c + "|";
    });

    if ( id_for_approve.length > 1 )
        id_for_approve = id_for_approve.substr(0,id_for_approve.length-1);      //delete last "|"

    return id_for_approve ;

}

//FixIn: 9.6.3.5


/**
	 * Selections of several  checkboxes like in gMail with shift :)
 * Need to  have this structure: 
 * .wpbc_selectable_table
 *      .wpbc_selectable_head
 *              .check-column
 *                  :checkbox
 *      .wpbc_selectable_body
 *          .wpbc_row
 *              .check-column
 *                  :checkbox
 *      .wpbc_selectable_foot             
 *              .check-column
 *                  :checkbox
 */
( function( $ ){            
    $( document ).ready(function(){
            
	var checks, first, last, checked, sliced, lastClicked = false;

	// check all checkboxes
	$('.wpbc_selectable_body').find('.check-column').find(':checkbox').on( 'click', function(e) {                   //FixIn: 8.7.11.12
		if ( 'undefined' == e.shiftKey ) { return true; }
		if ( e.shiftKey ) {
			if ( !lastClicked ) { return true; }
			//checks = $( lastClicked ).closest( 'form' ).find( ':checkbox' ).filter( ':visible:enabled' );
                        checks = $( lastClicked ).closest( '.wpbc_selectable_body' ).find( ':checkbox' ).filter( ':visible:enabled' );
			first = checks.index( lastClicked );
			last = checks.index( this );
			checked = $(this).prop('checked');
			if ( 0 < first && 0 < last && first != last ) {
				sliced = ( last > first ) ? checks.slice( first, last ) : checks.slice( last, first );
				sliced.prop( 'checked', function() {
					if ( $(this).closest('.wpbc_row').is(':visible') )
						return checked;

					return false;
				});
			}
		}
		lastClicked = this;

		// toggle "check all" checkboxes
		var unchecked = $(this).closest('.wpbc_selectable_body').find(':checkbox').filter(':visible:enabled').not(':checked');
		//FixIn: 8.8.1.15
		$(this).closest('.wpbc_selectable_table').find('.wpbc_selectable_head, .wpbc_selectable_foot').find(':checkbox').prop('checked', function() {
			return ( 0 === unchecked.length );
		});


		// Disable text selection while pressing 'shift'
		document.getSelection().removeAllRanges();              //FixIn: 8.7.6.8

		return true;
	});

	//FixIn: 9.9.0.7
	$('.wpbc_selectable_body').find('.check-column').find(':checkbox').on( 'change', function(e) {
		if ( jQuery( this ).is( ':checked' ) ){
			jQuery( this ).closest( '.wpbc_flextable_row' ).addClass( 'wpbc_flextable_row_selected' );
		} else {
			jQuery( this ).closest( '.wpbc_flextable_row' ).removeClass( 'wpbc_flextable_row_selected' );
		}
	});

	$('.wpbc_selectable_head, .wpbc_selectable_foot').find('.check-column :checkbox').on( 'click.wp-toggle-checkboxes', function( event ) {
		var $this = $(this),
			$table = $this.closest( '.wpbc_selectable_table' ),
			controlChecked = $this.prop('checked'),
			toggle = event.shiftKey || $this.data('wp-toggle');
        //FixIn: 8.8.1.15
		$table.find( '.wpbc_selectable_body' ).filter(':visible')
                        .find('.check-column').find(':checkbox')
			.prop('checked', function() {

				if ( $(this).is(':hidden,:disabled') ) {
					return false;
				}

				if ( toggle ) {
					return ! $(this).prop( 'checked' );
				} else if ( controlChecked ) {
					return true;
				}

				return false;
			}).trigger( 'change' );		//FixIn: 9.9.0.7

        //FixIn: 8.8.1.15
		$table.find('.wpbc_selectable_head,  .wpbc_selectable_foot').filter(':visible')
                        .find('.check-column').find(':checkbox')
			.prop('checked', function() {
				if ( toggle ) {
					return false;
				} else if ( controlChecked ) {
					return true;
				}

				return false;
			});
	});
    });    
}( jQuery ) );

//FixIn: 8.4.7.14
function wpbc_are_you_sure_popup(){
    if ( wpbc_are_you_sure( 'Do you really want to do this ?' ) ) {
        return  true;
    } else {
        return  false;
    }
}