"use strict";

jQuery('body').on({
    'touchmove': function(e) {

		jQuery( '.timespartly' ).each( function ( index ){

			var td_el = jQuery( this ).get( 0 );

			if ( (undefined != td_el._tippy) ){

				var instance = td_el._tippy;
				instance.hide();
			}
		} );
	}
});

/**
 * Request Object
 * Here we can  define Search parameters and Update it later,  when  some parameter was changed
 *
 */
var wpbc_ajx_booking_listing = (function ( obj, $) {

	// Secure parameters for Ajax	------------------------------------------------------------------------------------
	var p_secure = obj.security_obj = obj.security_obj || {
															user_id: 0,
															nonce  : '',
															locale : ''
														  };

	obj.set_secure_param = function ( param_key, param_val ) {
		p_secure[ param_key ] = param_val;
	};

	obj.get_secure_param = function ( param_key ) {
		return p_secure[ param_key ];
	};


	// Listing Search parameters	------------------------------------------------------------------------------------
	var p_listing = obj.search_request_obj = obj.search_request_obj || {
																		sort            : "booking_id",
																		sort_type       : "DESC",
																		page_num        : 1,
																		page_items_count: 10,
																		create_date     : "",
																		keyword         : "",
																		source          : ""
																	};

	obj.search_set_all_params = function ( request_param_obj ) {
		p_listing = request_param_obj;
	};

	obj.search_get_all_params = function () {
		return p_listing;
	};

	obj.search_get_param = function ( param_key ) {
		return p_listing[ param_key ];
	};

	obj.search_set_param = function ( param_key, param_val ) {
		// if ( Array.isArray( param_val ) ){
		// 	param_val = JSON.stringify( param_val );
		// }
		p_listing[ param_key ] = param_val;
	};

	obj.search_set_params_arr = function( params_arr ){
		_.each( params_arr, function ( p_val, p_key, p_data ){															// Define different Search  parameters for request
			this.search_set_param( p_key, p_val );
		} );
	}


	// Other parameters 			------------------------------------------------------------------------------------
	var p_other = obj.other_obj = obj.other_obj || { };

	obj.set_other_param = function ( param_key, param_val ) {
		p_other[ param_key ] = param_val;
	};

	obj.get_other_param = function ( param_key ) {
		return p_other[ param_key ];
	};


	return obj;
}( wpbc_ajx_booking_listing || {}, jQuery ));


/**
 *   Ajax  ------------------------------------------------------------------------------------------------------ */

/**
 * Send Ajax search request
 * for searching specific Keyword and other params
 */
function wpbc_ajx_booking_ajax_search_request(){

console.groupCollapsed('AJX_BOOKING_LISTING'); console.log( ' == Before Ajax Send - search_get_all_params() == ' , wpbc_ajx_booking_listing.search_get_all_params() );

	wpbc_booking_listing_reload_button__spin_start();

/*
//FixIn: forVideo
if ( ! is_this_action ){
	//wpbc_ajx_booking__actual_listing__hide();
	jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html(
		'<div style="width:100%;text-align: center;" id="wpbc_loading_section"><span class="wpbc_icn_autorenew wpbc_spin"></span></div>'
		+ jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html()
	);
	if ( 'function' === typeof (jQuery( '#wpbc_loading_section' ).wpbc_my_modal) ){			//FixIn: 9.0.1.5
		jQuery( '#wpbc_loading_section' ).wpbc_my_modal( 'show' );
	} else {
		alert( 'Warning! Booking Calendar. Its seems that  you have deactivated loading of Bootstrap JS files at Booking Settings General page in Advanced section.' )
	}
}
is_this_action = false;
*/
	// Start Ajax
	jQuery.post( wpbc_global1.wpbc_ajaxurl,
				{
					action          : 'WPBC_AJX_BOOKING_LISTING',
					wpbc_ajx_user_id: wpbc_ajx_booking_listing.get_secure_param( 'user_id' ),
					nonce           : wpbc_ajx_booking_listing.get_secure_param( 'nonce' ),
					wpbc_ajx_locale : wpbc_ajx_booking_listing.get_secure_param( 'locale' ),

					search_params	: wpbc_ajx_booking_listing.search_get_all_params()
				},
				/**
				 * S u c c e s s
				 *
				 * @param response_data		-	its object returned from  Ajax - class-live-searcg.php
				 * @param textStatus		-	'success'
				 * @param jqXHR				-	Object
				 */
				function ( response_data, textStatus, jqXHR ) {
//FixIn: forVideo
//jQuery( '#wpbc_loading_section' ).wpbc_my_modal( 'hide' );

console.log( ' == Response WPBC_AJX_BOOKING_LISTING == ', response_data ); console.groupEnd();
					// Probably Error
					if ( (typeof response_data !== 'object') || (response_data === null) ){
						jQuery( '.wpbc_ajx_under_toolbar_row' ).hide();													//FixIn: 9.6.1.5
						jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html(
																	'<div class="wpbc-settings-notice notice-warning" style="text-align:left">' +
																		response_data +
																	'</div>'
															);
						return;
					}

					// Reload page, after filter toolbar was reseted
					if (       (     undefined != response_data[ 'ajx_cleaned_params' ])
							&& ( 'reset_done' === response_data[ 'ajx_cleaned_params' ][ 'ui_reset' ])
					){
						location.reload();
						return;
					}

					// Show listing
					if ( response_data[ 'ajx_count' ] > 0 ){

						wpbc_ajx_booking_show_listing( response_data[ 'ajx_items' ], response_data[ 'ajx_search_params' ], response_data[ 'ajx_booking_resources' ] );

						wpbc_pagination_echo(
							wpbc_ajx_booking_listing.get_other_param( 'pagination_container' ),
							{
								'page_active': response_data[ 'ajx_search_params' ][ 'page_num' ],
								'pages_count': Math.ceil( response_data[ 'ajx_count' ] / response_data[ 'ajx_search_params' ][ 'page_items_count' ] ),

								'page_items_count': response_data[ 'ajx_search_params' ][ 'page_items_count' ],
								'sort_type'       : response_data[ 'ajx_search_params' ][ 'sort_type' ]
							}
						);
						wpbc_ajx_booking_define_ui_hooks();						// Redefine Hooks, because we show new DOM elements

					} else {

						wpbc_ajx_booking__actual_listing__hide();
						jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html(
											'<div class="wpbc-settings-notice0 notice-warning0" style="text-align:center;margin-left:-50px;">' +
												'<strong>' + 'No results found for current filter options...' + '</strong>' +
												//'<strong>' + 'No results found...' + '</strong>' +
											'</div>'
									);
					}

					// Update new booking count
					if ( undefined !== response_data[ 'ajx_new_bookings_count' ] ){
						var ajx_new_bookings_count = parseInt( response_data[ 'ajx_new_bookings_count' ] )
						if (ajx_new_bookings_count>0){
							jQuery( '.wpbc_badge_count' ).show();
						}
						jQuery( '.bk-update-count' ).html( ajx_new_bookings_count );
					}

					wpbc_booking_listing_reload_button__spin_pause();

					jQuery( '#ajax_respond' ).html( response_data );		// For ability to show response, add such DIV element to page
				}
			  ).fail( function ( jqXHR, textStatus, errorThrown ) {    if ( window.console && window.console.log ){ console.log( 'Ajax_Error', jqXHR, textStatus, errorThrown ); }
					jQuery( '.wpbc_ajx_under_toolbar_row' ).hide();														//FixIn: 9.6.1.5
					var error_message = '<strong>' + 'Error!' + '</strong> ' + errorThrown ;
					if ( jqXHR.responseText ){
						error_message += jqXHR.responseText;
					}
					error_message = error_message.replace( /\n/g, "<br />" );

					wpbc_ajx_booking_show_message( error_message );
			  })
	          // .done(   function ( data, textStatus, jqXHR ) {   if ( window.console && window.console.log ){ console.log( 'second success', data, textStatus, jqXHR ); }    })
			  // .always( function ( data_jqXHR, textStatus, jqXHR_errorThrown ) {   if ( window.console && window.console.log ){ console.log( 'always finished', data_jqXHR, textStatus, jqXHR_errorThrown ); }     })
			  ;  // End Ajax
}


/**
 *   Views  ----------------------------------------------------------------------------------------------------- */

/**
 * Show Listing Table 		and define gMail checkbox hooks
 *
 * @param json_items_arr		- JSON object with Items
 * @param json_search_params	- JSON object with Search
 */
function wpbc_ajx_booking_show_listing( json_items_arr, json_search_params, json_booking_resources ){

	wpbc_ajx_define_templates__resource_manipulation( json_items_arr, json_search_params, json_booking_resources );

//console.log( 'json_items_arr' , json_items_arr, json_search_params );
	jQuery( '.wpbc_ajx_under_toolbar_row' ).css( "display", "flex" );													//FixIn: 9.6.1.5
	var list_header_tpl = wp.template( 'wpbc_ajx_booking_list_header' );
	var list_row_tpl    = wp.template( 'wpbc_ajx_booking_list_row' );


	// Header
	jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html( list_header_tpl() );

	// Body
	jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).append( '<div class="wpbc_selectable_body"></div>' );

	// R o w s
console.groupCollapsed( 'LISTING_ROWS' );																				// LISTING_ROWS
	_.each( json_items_arr, function ( p_val, p_key, p_data ){
		if ( 'undefined' !== typeof json_search_params[ 'keyword' ] ){													// Parameter for marking keyword with different color in a list
			p_val[ '__search_request_keyword__' ] = json_search_params[ 'keyword' ];
		} else {
			p_val[ '__search_request_keyword__' ] = '';
		}
		p_val[ 'booking_resources' ] = json_booking_resources;
		jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) + ' .wpbc_selectable_body' ).append( list_row_tpl( p_val ) );
	} );
console.groupEnd(); 																									// LISTING_ROWS

	wpbc_define_gmail_checkbox_selection( jQuery );						// Redefine Hooks for clicking at Checkboxes
}


	/**
	 * Define template for changing booking resources &  update it each time,  when  listing updating, useful  for showing actual  booking resources.
	 *
	 * @param json_items_arr		- JSON object with Items
	 * @param json_search_params	- JSON object with Search
	 * @param json_booking_resources	- JSON object with Resources
	 */
	function wpbc_ajx_define_templates__resource_manipulation( json_items_arr, json_search_params, json_booking_resources ){

		// Change booking resource
		var change_booking_resource_tpl = wp.template( 'wpbc_ajx_change_booking_resource' );

		jQuery( '#wpbc_hidden_template__change_booking_resource' ).html(
																			change_booking_resource_tpl( {
																							'ajx_search_params'    : json_search_params,
																							'ajx_booking_resources': json_booking_resources
																			} )
																	);

		// Duplicate booking resource
		var duplicate_booking_to_other_resource_tpl = wp.template( 'wpbc_ajx_duplicate_booking_to_other_resource' );

		jQuery( '#wpbc_hidden_template__duplicate_booking_to_other_resource' ).html(
																			duplicate_booking_to_other_resource_tpl( {
																							'ajx_search_params'    : json_search_params,
																							'ajx_booking_resources': json_booking_resources
																			} )
																	);
	}


/**
 * Show just message instead of listing and hide pagination
 */
function wpbc_ajx_booking_show_message( message ){

	wpbc_ajx_booking__actual_listing__hide();

	jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' ) ).html(
												'<div class="wpbc-settings-notice notice-warning" style="text-align:left">' +
													message +
												'</div>'
										);
}


/**
 *   H o o k s  -  its Action/Times when need to re-Render Views  ----------------------------------------------- */

/**
 * Send Ajax Search Request after Updating search request parameters
 *
 * @param params_arr
 */
function wpbc_ajx_booking_send_search_request_with_params ( params_arr ){

	// Define different Search  parameters for request
	_.each( params_arr, function ( p_val, p_key, p_data ) {
		//console.log( 'Request for: ', p_key, p_val );
		wpbc_ajx_booking_listing.search_set_param( p_key, p_val );
	});

	// Send Ajax Request
	wpbc_ajx_booking_ajax_search_request();
}

/**
 * Search request for "Page Number"
 * @param page_number	int
 */
function wpbc_ajx_booking_pagination_click( page_number ){

	wpbc_ajx_booking_send_search_request_with_params( {
										'page_num': page_number
									} );
}


/**
 *   Keyword Searching  ----------------------------------------------------------------------------------------- */

/**
 * Search request for "Keyword", also set current page to  1
 *
 * @param element_id	-	HTML ID  of element,  where was entered keyword
 */
function wpbc_ajx_booking_send_search_request_for_keyword( element_id ) {

	// We need to Reset page_num to 1 with each new search, because we can be at page #4,  but after  new search  we can  have totally  only  1 page
	wpbc_ajx_booking_send_search_request_with_params( {
											'keyword'  : jQuery( element_id ).val(),
											'page_num': 1
										} );
}

	/**
	 * Send search request after few seconds (usually after 1,5 sec)
	 * Closure function. Its useful,  for do  not send too many Ajax requests, when someone make fast typing.
	 */
	var wpbc_ajx_booking_searching_after_few_seconds = function (){

		var closed_timer = 0;

		return function ( element_id, timer_delay ){

			// Get default value of "timer_delay",  if parameter was not passed into the function.
			timer_delay = typeof timer_delay !== 'undefined' ? timer_delay : 1500;

			clearTimeout( closed_timer );		// Clear previous timer

			// Start new Timer
			closed_timer = setTimeout( wpbc_ajx_booking_send_search_request_for_keyword.bind(  null, element_id ), timer_delay );
		}
	}();


/**
 *   Define Dynamic Hooks  (like pagination click, which renew each time with new listing showing)  ------------- */

/**
 * Define HTML ui Hooks: on KeyUp | Change | -> Sort Order & Number Items / Page
 * We are hcnaged it each  time, when showing new listing, because DOM elements chnaged
 */
function wpbc_ajx_booking_define_ui_hooks(){

	if ( 'function' === typeof( wpbc_define_tippy_tooltips ) ) {
		wpbc_define_tippy_tooltips( '.wpbc_listing_container ' );
	}

	wpbc_ajx_booking__ui_define__locale();
	wpbc_ajx_booking__ui_define__remark();

	// Items Per Page
	jQuery( '.wpbc_items_per_page' ).on( 'change', function( event ){

		wpbc_ajx_booking_send_search_request_with_params( {
											'page_items_count'  : jQuery( this ).val(),
											'page_num': 1
										} );
	} );

	// Sorting
	jQuery( '.wpbc_items_sort_type' ).on( 'change', function( event ){

		wpbc_ajx_booking_send_search_request_with_params( {'sort_type': jQuery( this ).val()} );
	} );
}


/**
 *   Show / Hide Listing  --------------------------------------------------------------------------------------- */

/**
 *  Show Listing Table 	- 	Sending Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
 */
function wpbc_ajx_booking__actual_listing__show(){

	wpbc_ajx_booking_ajax_search_request();			// Send Ajax Request	-	with parameters that  we early  defined in "wpbc_ajx_booking_listing" Obj.
}

/**
 * Hide Listing Table ( and Pagination )
 */
function wpbc_ajx_booking__actual_listing__hide(){
	jQuery( '.wpbc_ajx_under_toolbar_row' ).hide();																		//FixIn: 9.6.1.5
	jQuery( wpbc_ajx_booking_listing.get_other_param( 'listing_container' )    ).html( '' );
	jQuery( wpbc_ajx_booking_listing.get_other_param( 'pagination_container' ) ).html( '' );
}


/**
 *   Support functions for Content Template data  --------------------------------------------------------------- */

/**
 * Highlight strings,
 * by inserting <span class="fieldvalue name fieldsearchvalue">...</span> html  elements into the string.
 * @param {string} booking_details 	- Source string
 * @param {string} booking_keyword	- Keyword to highlight
 * @returns {string}
 */
function wpbc_get_highlighted_search_keyword( booking_details, booking_keyword ){

	booking_keyword = booking_keyword.trim().toLowerCase();
	if ( 0 == booking_keyword.length ){
		return booking_details;
	}

	// Highlight substring withing HTML tags in "Content of booking fields data" -- e.g. starting from  >  and ending with <
	let keywordRegex = new RegExp( `fieldvalue[^<>]*>([^<]*${booking_keyword}[^<]*)`, 'gim' );

	//let matches = [...booking_details.toLowerCase().matchAll( keywordRegex )];
	let matches = booking_details.toLowerCase().matchAll( keywordRegex );
		matches = Array.from( matches );

	let strings_arr = [];
	let pos_previous = 0;
	let search_pos_start;
	let search_pos_end;

	for ( const match of matches ){

		search_pos_start = match.index + match[ 0 ].toLowerCase().indexOf( '>', 0 ) + 1 ;

		strings_arr.push( booking_details.substr( pos_previous, (search_pos_start - pos_previous) ) );

		search_pos_end = booking_details.toLowerCase().indexOf( '<', search_pos_start );

		strings_arr.push( '<span class="fieldvalue name fieldsearchvalue">' + booking_details.substr( search_pos_start, (search_pos_end - search_pos_start) ) + '</span>' );

		pos_previous = search_pos_end;
	}

	strings_arr.push( booking_details.substr( pos_previous, (booking_details.length - pos_previous) ) );

	return strings_arr.join( '' );
}

/**
 * Convert special HTML characters   from:	 &amp; 	-> 	&
 *
 * @param text
 * @returns {*}
 */
function wpbc_decode_HTML_entities( text ){
	var textArea = document.createElement( 'textarea' );
	textArea.innerHTML = text;
	return textArea.value;
}

/**
 * Convert TO special HTML characters   from:	 & 	-> 	&amp;
 *
 * @param text
 * @returns {*}
 */
function wpbc_encode_HTML_entities(text) {
  var textArea = document.createElement('textarea');
  textArea.innerText = text;
  return textArea.innerHTML;
}


/**
 *   Support Functions - Spin Icon in Buttons  ------------------------------------------------------------------ */

/**
 * Spin button in Filter toolbar  -  Start
 */
function wpbc_booking_listing_reload_button__spin_start(){
	jQuery( '#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin').removeClass( 'wpbc_animation_pause' );
}

/**
 * Spin button in Filter toolbar  -  Pause
 */
function wpbc_booking_listing_reload_button__spin_pause(){
	jQuery( '#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin' ).addClass( 'wpbc_animation_pause' );
}

/**
 * Spin button in Filter toolbar  -  is Spinning ?
 *
 * @returns {boolean}
 */
function wpbc_booking_listing_reload_button__is_spin(){
    if ( jQuery( '#wpbc_booking_listing_reload_button .menu_icon.wpbc_spin' ).hasClass( 'wpbc_animation_pause' ) ){
		return true;
	} else {
		return false;
	}
}