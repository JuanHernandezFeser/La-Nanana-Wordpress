<?php
/**
 * @version 1.0
 * @package Booking Calendar
 * @subpackage Translations Functions
 * @category Functions
 *
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 29.09.2015
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

class WPBC_Upgrader_Translation_Skin extends WP_Upgrader_Skin{

  function __construct($args = array()){
      $defaults = array( 'url' => '', 'nonce' => '', 'title' => '', 'context' => false );
      $this->options = wp_parse_args($args, $defaults);
  }

  function header(){

  }

  function footer(){

  }

  function error($error){
      $this->installer_error = $error;
  }

  function add_strings(){
	$this->upgrader->strings['starting_upgrade'] = __( 'Some of your translations need updating. Sit tight for a few more seconds while we update them as well.' );
	$this->upgrader->strings['up_to_date']       = __( 'Your translations are all up to date.' );
	$this->upgrader->strings['no_package']       = __( 'Update package not available.' );
	/* translators: %s: Package URL. */
	$this->upgrader->strings['downloading_package'] = sprintf( __( 'Downloading translation from %s&#8230;' ), '<span class="code">%s</span>' );
	$this->upgrader->strings['unpack_package']      = __( 'Unpacking the update&#8230;' );
	$this->upgrader->strings['process_failed']      = __( 'Translation update failed.' );
	$this->upgrader->strings['process_success']     = __( 'Translation updated successfully.' );
	$this->upgrader->strings['remove_old']          = __( 'Removing the old version of the translation&#8230;' );
	$this->upgrader->strings['remove_old_failed']   = __( 'Could not remove the old translation.' );

  }

	public function set_upgrader( &$upgrader ) {
		if ( is_object( $upgrader ) ) {
			$this->upgrader =& $upgrader;
		}
		$this->add_strings();
	}

  function before(){

  }

  function after(){
	  if ( ! empty( $this->installer_error ) ) {
		  if ( is_wp_error( $this->installer_error ) ) {
			  $message = $this->installer_error->get_error_message() . ' (' . $this->installer_error->get_error_data() . ')';

			  echo $message;
		  }
	  }
  }

}