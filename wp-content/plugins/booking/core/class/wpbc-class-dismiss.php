<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//FixIn: 8.1.3.10

class WPBC_Dismiss {
    
    public  $element_id;
    public  $title;
    public  $hint;
    public  $html_class;
    public  $css;
	public  $dismiss_css_class;

    public function __construct( ) {
        
    }
    
    public function render( $params = array() ){
        if (isset($params['id'])) 
                $this->element_id = $params['id'];
        else    return  false;                                                  // Exit, because we do not have ID of element
        
        if (isset($params['title'])) 
                $this->title = $params['title'];
        else    $this->title = __( 'Dismiss'  ,'booking');
        
        if (isset($params['hint']))
                $this->hint = $params['hint'];
        else    $this->hint = __( 'Dismiss'  ,'booking');

        if (isset($params['class']))
                $this->html_class = $params['class'];
        else    $this->html_class = 'wpbc-panel-dismiss';
        
        if (isset($params['css']))
                $this->css = $params['css'];
        else    $this->css = 'text-decoration: none;font-weight: 600;';

        if (isset($params['dismiss_css_class']))
                $this->dismiss_css_class = $params['dismiss_css_class'];
        else    $this->dismiss_css_class = '';

        return $this->show();
    }

    public function show() {

	    // Check if this window is already Hided or not
		if ( '1' == get_user_option( 'booking_win_' . $this->element_id ) ){     // Panel Hided

			echo '<script type="text/javascript"> jQuery(document).ready(function(){ ';
			if ( ! empty( $this->element_id ) ) {
				echo ' jQuery( "#' . esc_attr( $this->element_id ) . '" ).hide(); ';
			}
			if ( ! empty( $this->dismiss_css_class ) ) {
				echo ' jQuery( "' . esc_attr( $this->dismiss_css_class ) . '" ).hide(); ';
			}
			echo ' }); </script>';

			return false;

		} else {                                                                  // Show Panel
			echo '<script type="text/javascript"> jQuery(document).ready(function(){ ';

			if ( ! empty( $this->element_id ) ) {
				echo ' jQuery( "#' . esc_attr( $this->element_id ) . '" ).show(); ';
			}
			if ( ! empty( $this->dismiss_css_class ) ) {
				echo ' jQuery( "' . esc_attr( $this->dismiss_css_class ) . '" ).show(); ';
			}
			echo ' }); </script>';
        }

        wp_nonce_field('wpbc_ajax_admin_nonce',  "wpbc_admin_panel_dismiss_window_nonce" ,  true , true );
        // Show Hide link
        ?><a class="<?php echo esc_attr( $this->html_class ); ?>"  style="<?php echo esc_attr( $this->css ); ?>"
			 title="<?php echo esc_attr( $this->hint ); ?>"
			 href="javascript:void(0)"
             onclick="javascript: if ( typeof( wpbc_hide_window ) == 'function' ) {
				 	wpbc_hide_window('<?php echo $this->element_id; ?>');
				 	wpbc_dismiss_window(<?php echo wpbc_get_current_user_id(); ?>, '<?php echo $this->element_id; ?>');
				 	jQuery( this ).hide(); <?php
				 	if ( ! empty( $this->dismiss_css_class ) ) {
					    echo "jQuery('" . esc_attr($this->dismiss_css_class) . "').slideUp(500);";
				    }
			 		?>
				 } else {  <?php
             		echo "jQuery('#" . $this->element_id . "').slideUp(500);";
				 	if ( ! empty( $this->dismiss_css_class ) ) {
					    echo "jQuery('" . esc_attr($this->dismiss_css_class) . "').slideUp(500);";
				    }
			  ?> }"
          ><?php echo  $this->title; ?></a><?php

	    return true;
    }
}

global $wpbc_Dismiss;
$wpbc_Dismiss = new WPBC_Dismiss();
