////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Checkbox Selection functions for Listing
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * Usual DOM Listing structure:
 	<div class="wpbc_listing_container wpbc_selectable_table wpbc_NAME_listing_container">
		<div class="wpbc_listing_usual_row wpbc_list_header wpbc_selectable_head">
			<div class="wpbc_listing_col wpbc_col_id check-column"><div class="content_text"><input type="checkbox" /></div></div>
			<div class="wpbc_listing_col wpbc_col_labels"><div class="content_text"><?php 	echo esc_js( __( 'Actions', 'email-reminders' ) ); ?></div></div>
			<div class="wpbc_listing_col wpbc_col_data"><div class="content_text"><?php 	echo esc_js( __( 'Data', 'email-reminders' ) ); ?></div></div>
		</div>
		<div id="row_id_{{{data.rules_id}}}" class="wpbc_listing_usual_row wpbc_list_row wpbc_row">
			<div class="wpbc_listing_col wpbc_col_id check-column"><div class="content_text"><input type="checkbox" /></div></div>
			<div class="wpbc_listing_col wpbc_col_labels">
				<div class="content_text"><span class="wpbc_label"><?php _e('Email', 'email-reminders'); ?>: {{{data['rule']['email_template']}}}</span></div>
			</div>
			...
 	</div>
 */

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
function wpbc_define_gmail_checkbox_selection( $ ){

	var checks, first, last, checked, sliced, lastClicked = false;

	// Check all checkboxes
	$('.wpbc_selectable_body').find('.check-column').find(':checkbox').on( 'click', function(e) {
	//$('.wpbc_selectable_body').children().children('.check-column').find(':checkbox').on( 'click', function(e) {
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
				} ).trigger( 'change' );
			}
		}
		lastClicked = this;

		// toggle "check all" checkboxes
		var unchecked = $(this).closest('.wpbc_selectable_body').find(':checkbox').filter(':visible:enabled').not(':checked');
		$(this).closest('.wpbc_selectable_table').children('.wpbc_selectable_head, .wpbc_selectable_foot').find(':checkbox').prop('checked', function() {
			return ( 0 === unchecked.length );
		}).trigger( 'change' );

		return true;
	});

	// Head || Foot clicking to  select / deselect ALL
	$('.wpbc_selectable_head, .wpbc_selectable_foot').find('.check-column :checkbox').on( 'click', function( event ) {
		var $this = $(this),
			$table = $this.closest( '.wpbc_selectable_table' ),
			controlChecked = $this.prop('checked'),
			toggle = event.shiftKey || $this.data('wp-toggle');

		$table.children( '.wpbc_selectable_body' ).filter(':visible')
						.find('.check-column').find(':checkbox')
			//.children().children('.check-column').find(':checkbox')
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
			}).trigger( 'change' );

		$table.children('.wpbc_selectable_head,  .wpbc_selectable_foot').filter(':visible')
						.find('.check-column').find(':checkbox')
			//.children().children('.check-column').find(':checkbox')
			.prop('checked', function() {
				if ( toggle ) {
					return false;
				} else if ( controlChecked ) {
					return true;
				}

				return false;
			});
	});


	// Visually  show selected border
	$( '.wpbc_selectable_body' ).find( '.check-column :checkbox' ).on( 'change', function ( event ){
		if ( jQuery( this ).is( ':checked' ) ){
			jQuery( this ).closest( '.wpbc_list_row' ).addClass( 'row_selected_color' );
		} else {
			jQuery( this ).closest( '.wpbc_list_row' ).removeClass( 'row_selected_color' );
		}

		// Disable text selection while pressing 'shift'
		document.getSelection().removeAllRanges();

		// Show or hide buttons on Actions toolbar  at  Booking Listing  page,  if we have some selected bookings.
		wpbc_show_hide_action_buttons_for_selected_bookings();
	} );

	wpbc_show_hide_action_buttons_for_selected_bookings();
}

/**
 * Get ID of row,  based on clciked element
 *
 * @param this_inbound_element  - ususlly  this
 * @returns {number}
 */
function wpbc_get_row_id_from_element( this_inbound_element ){

	var element_id = jQuery( this_inbound_element ).closest('.wpbc_listing_usual_row').attr('id');

	element_id = parseInt( element_id.replace( 'row_id_', '' ) );

	return element_id;
}

/**
 * Get ID array  of selected elements
 */
function wpbc_get_selected_row_id(){

	var $table = jQuery( '.wpbc_listing_container.wpbc_selectable_table');

	var checkboxes = $table.children( '.wpbc_selectable_body' ).filter( ':visible' ).find( '.check-column' ).find( ':checkbox' );

	var selected_id = [];
	jQuery.each( checkboxes, function( key, checkbox ) {

		if ( jQuery( checkbox ).is( ':checked' ) ) {
  			var element_id = wpbc_get_row_id_from_element( checkbox );	//   jQuery( checkbox ).closest('.wpbc_listing_usual_row').attr('id');

			// element_id = parseInt( element_id.replace( 'row_id_', '' ) );

  			selected_id.push(element_id);
		}

	});

//console.log( 'wpbc_get_selected_row_id', selected_id );

	return selected_id;

	// _.each( json_items_arr, function ( p_val, p_key, p_data ){
	//
	// });
}


/**
 * Show or hide buttons on Actions toolbar  at  Booking Listing  page,  if we have some selected bookings.
 */
function wpbc_show_hide_action_buttons_for_selected_bookings(){

	var selected_rows_arr = wpbc_get_selected_row_id();

	if ( selected_rows_arr.length > 0 ){
		jQuery( '.hide_button_if_no_selection' ).show();
	} else {
		jQuery( '.hide_button_if_no_selection' ).hide();
	}
}