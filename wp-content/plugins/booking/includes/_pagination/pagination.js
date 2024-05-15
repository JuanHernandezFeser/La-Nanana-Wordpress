////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Views
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * Show Pagination
 *
 * @param pagination_container		- '.wpbc_rules_pagination'
 * @param params_obj				- JSON object	~	{ 'page_active': $page_active, 'pages_count': $pages_count }
 */
function wpbc_pagination_echo( pagination_container, params_obj ){

	var pagination = wp.template( 'wpbc_pagination' );
	jQuery( pagination_container ).html( '<div class="wpbc-bottom-pagination"></div>' );

	// Pagination
	jQuery( pagination_container + ' .wpbc-bottom-pagination').append(  pagination( params_obj ) ) ;


	// Number of items per page
	var pagination_items_per_page = wp.template( 'wpbc_pagination_items_per_page' );
	jQuery( pagination_container + ' .wpbc-bottom-pagination').append(  pagination_items_per_page( params_obj ) ) ;

	jQuery( pagination_container ).show();
}


/**
 * Blank function.  -- Redefine this function in specific page-XXXX.php  file for specific actions
 *
 * @param page_number	int
 */
function wpbc_pagination_click_page( page_number ){
	console.log( 'wpbc_pagination_click_page', page_number );
}