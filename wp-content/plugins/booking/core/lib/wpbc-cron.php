<?php
/**
 * @version 1.0
 * @package Booking Calendar 
 * @subpackage CRON
 * @category Execure Recurent Actions
 * 
 * @author wpdevelop
 * @link https://wpbookingcalendar.com/
 * @email info@wpbookingcalendar.com
 *
 * @modified 2014.09.02
 * @since 5.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;                                             // Exit if accessed directly


/**
 *  Usage example:

    WPBC()->cron->update( 'wpbc_import_gcal'                        // Name of Cron task
                                , array(     
	                                         'action'         => array( 'wpbc_silent_import_all_events' )         // {REQUIRED} Name of Action Hook  and possible parameters. Before you need to add this action:  add_bk_action('wpbc_silent_import_all_events' , 'wpbc_silent_import_all_events' );
	                                       , 'start_time'     => time()                                           // Start  time of Execution. Default = Now
	                                       , 'recurrence'     => 2                                                // Set time interval in 'time_dimension' (hours | minutes | days ) for repeat of execution.  Default = 24
										   , 'time_dimension' => 'h'	                                            // 'h' - hours, 'm' - minutes, 'd' - days
	                                       , 'priority'       => 10                                               // Priority  of actions execution. Default = 10. Lower priority - execution  firstly.
                                        ) 
                                     );
      
    // D E L E T E   specific Cron
    WPBC()->cron->delete( 'wpbc_import_gcal' );                     // Name of Cron task

*/
class WPBC_Cron {

    private $actions;

    
    function __construct() {
        
        $this->actions = array();

	    add_action( 'init', array( $this, 'load' ), 9 );
	    add_bk_action( 'wpbc_other_versions_deactivation', array( &$this, 'deactivate' ) );
    }


    public function load(){

        $booking_cron = get_bk_option('booking_cron');

	    if ( $booking_cron === false ) {
		    $booking_cron = array();
	    } else {
		    if ( is_serialized( $booking_cron ) ) {
			    $booking_cron = unserialize( $booking_cron );
		    }
	    }

        $this->actions = $booking_cron;

	    if ( ! empty( $this->actions ) ) {
		    $this->check();
	    }
    }


    public function check(){

        // Sort by priority of execution
        $priority = array();

        foreach ($this->actions as $action_name => $action) {
            
            if ( ! isset( $priority[ $action['priority'] ] ) ) {
                $priority[ $action['priority'] ] = array();
            }
            
            $priority[ $action['priority'] ][ $action_name ] = $action;
        }

	    ksort( $priority );
        
        // check  for execution  based on priority
	    foreach ( $priority as $actions_list ) {

	        foreach ( $actions_list as $action_name => $action ) {
                
                //1. Check for start time
	            if ( $action['start_time'] > time() ) {
		            continue;
	            }

		        if ( $this->get_task__time_in_seconds__of_next_execution__from_now( $action ) > 0 ) {
			        continue;       // Time is not yet
		        }

				// We need to  run the TASK !

                // Update last_execution time to  current time,  becase we will  run task
                $action['last_execution'] = time();

				// Save changes to CRON
		        $this->update( $action_name, $action );

                // Execute
	            $this->action( $action_name );
            }
        }
    }


	/**
	 * Add Paramters
	 *
	 * @param $action_name
	 * @param $action_params
	 *
	 * @return bool
	 *
	 *                     Example:
     *     $wpbc_cron->add('wpbc_import', array(
                                    'action' => array( 'wpbc_silent_import_all_events' )    // Action and parameters
                                    , 'start_time' => time()                                // Now
                                    , 'recurrence' => 1                                     // each  one hour
                                    ));
     *     $wpbc_cron->add('wpbc_import', array(
                                    'action' => array( 'wpbc_silent_import_all_events' )    // Action and parameters
                                    , 'start_time' => time()                                // Now
                                    , 'recurrence' => 10                                     // each  10 minutes hour
                                    , 'time_dimension' => 'm'
                                    ));

	 */
    public function add($action_name, $action_params) {

	    if ( ! isset( $this->actions[ $action_name ] ) ) {

		    if ( ! isset( $action_params['action'] ) ) {
			    return false;
		    }

		    $defaults = array(
			    'start_time'     => ( time() - 1 ),     // GMT time, when to start this action  - Now
			    'recurrence'     => 24,                 // Each  24 hours
			    'time_dimension' => 'h',                // 'h' - hours, 'm' - minutes, 'd' - days
			    'priority'       => 10,                 // Set priority  of actions execution  at the same time
			    'last_execution' => time()              // Set  last  time execution
		    );
		    $args = wp_parse_args( $action_params, $defaults );

		    $this->actions[ $action_name ] = $args;


		    // Update to DB
		    $booking_cron = get_bk_option( 'booking_cron' );

		    if ( $booking_cron === false ) {
			    $booking_cron = array();
		    } else {
			    if ( is_serialized( $booking_cron ) ) {
				    $booking_cron = unserialize( $booking_cron );
			    }
		    }
		    $booking_cron[ $action_name ] = $args;

		    update_bk_option( 'booking_cron', $booking_cron );

		    return true;
	    } else {
		    return false;
	    }
    }


	/**
	 * Update CRON task
	 *
	 * @param $action_name
	 * @param $action_params
	 *
	 * @return true|void
	 */
	public function update( $action_name, $action_params ) {

        if ( isset( $this->actions[$action_name] ) ) {
            
            $args = wp_parse_args( $action_params, $this->actions[$action_name] );
            
            $this->actions[$action_name] = $args;

            // Update to DB
            $booking_cron = get_bk_option('booking_cron');
            
            if ( $booking_cron === false ) 
                $booking_cron = array();                
            else {
	            if ( is_serialized( $booking_cron ) ) {
		            $booking_cron = unserialize( $booking_cron );
	            }
            }
            $booking_cron[ $action_name] =  $args;
            
            update_bk_option( 'booking_cron' ,   $booking_cron );

            return  true;

        } else {
	        $this->add( $action_name, $action_params );
        }
    }
 
    
    public function delete($action_name) {

	    if ( isset( $this->actions[ $action_name ] ) ) {
		    unset( $this->actions[ $action_name ] );
	    }

	    // Update to DB
	    $booking_cron = get_bk_option( 'booking_cron' );

	    if ( $booking_cron === false ) {
		    $booking_cron = array();
	    } else {
		    if ( is_serialized( $booking_cron ) ) {
			    $booking_cron = unserialize( $booking_cron );
		    }
		    if ( isset( $booking_cron[ $action_name ] ) ) {
			    unset( $booking_cron[ $action_name ] );
		    }
	    }

	    ////////////////////////////////////////////////////////////////////

	    update_bk_option( 'booking_cron', $booking_cron );
    }
    
 
    private function action($action_name) {
        
        // Description:
        // 
        // This type of execution  action  add posibility to use not only name of action  
        // 
        // 'action' => array( 'wpbc_silent_import_all_events' )                 // Action and parameters
        // 
        // but also parameters
        // 
        // 'action' => array( 'wpbc_silent_import_all_events', 2, 'test' )                 // Action and parameters
        // 
	    if ( isset( $this->actions[ $action_name ] ) ) {
		    call_user_func_array( 'make_bk_action', $this->actions[ $action_name ]['action'] );
	    }
            
        // Simple Action execution without parameters.
        //make_bk_action( $this->actions[$action_name]['action'][0] );   
        
    }
    
    
    // Deactivation  of the plugin - Delete this option.
    public function deactivate(){
        delete_bk_option( 'booking_cron' );
    }



	/**
	 * Get info about time execution
	 *
	 * @return array  [
	 *                   'wpbc_import_gcal' =>  [
	 *                                              'interval_in_seconds' => 600
	 *                                              'last_time__run__local' => '2023-09-29 11:35:04'
	 *                                              'next_time__run__local' => '2023-09-29 11:35:04'
	 *                                              'next_run_after_seconds' => 354
	 *                                          ]
	 *                   , ...
	 *                ]
	 *
	 *    Example:
	 *             WPBC()->cron->get_active_tasks_info()
	 */
	public function get_active_tasks_info(){

		$active_tasks = array();

		foreach ( $this->actions as $task_name => $task_params_arr ) {
			$active_tasks[ $task_name ] = array();
			//$active_tasks[ $task_name ]['priority'] = $task_params_arr['priority'];
			$active_tasks[ $task_name ]['interval_in_seconds'] = $this->get_task__execution_interval_in_seconds( $task_params_arr );

				$last_time__run__timestamp = $task_params_arr['last_execution'];
				$next_time__run__timestamp = $this->get_task__timestamp__of_next_execution( $task_params_arr );
				//active_tasks[ $task_name ]['last_time__run__gmt'] = date( 'Y-m-d H:i:s', $active_tasks[ $task_name ]['last_time__run__timestamp'] );
				//$active_tasks[ $task_name ]['next_time__run__gmt'] = date( 'Y-m-d H:i:s', $active_tasks[ $task_name ]['next_time__run__timestamp'] );

			$active_tasks[ $task_name ]['last_time__run__local'] = wp_date( 'Y-m-d H:i:s', $last_time__run__timestamp );
			$active_tasks[ $task_name ]['next_time__run__local'] = wp_date( 'Y-m-d H:i:s', $next_time__run__timestamp );

			$time_in_seconds__of_next_execution = $this->get_task__time_in_seconds__of_next_execution__from_now( $task_params_arr );
			$active_tasks[ $task_name ]['next_run_after_seconds'] = ( $time_in_seconds__of_next_execution < 0 ) ? __( 'Now', 'booking' ) . ' ( ' . $time_in_seconds__of_next_execution . ')' : $time_in_seconds__of_next_execution;
		}

	    return $active_tasks;
	}


	/**
	 * Get execution  interval of this task in seconds,     e.g. -> 600    (10 minutes)
	 *
	 * @param $action   = array(
	 *                              'time_dimension' => m
	 *                              'recurrence'     => 10
	 *                         )
	 *
	 * @return float|int        600  (= 10 * 60)
	 */
	public function get_task__execution_interval_in_seconds( $action ) {

		$number_of_seconds = 60 * 60;                   // Hour by  default

		if ( ! empty( $action['time_dimension'] ) ) {

			switch ( $action['time_dimension'] ) {
				case 'd':
					$number_of_seconds = 24 * 60 * 60;   // Days
					break;
				case 'h':
					$number_of_seconds = 60 * 60;       // Hours
					break;
				case 'm':
					$number_of_seconds = 60;           // Minutes
					break;
				default:
			}
		}

		$execution_interval_in_seconds =  intval( $action['recurrence'] ) * $number_of_seconds;     // number of hours | m | d    //FixIn: 8.4.5.2

		return $execution_interval_in_seconds;
	}


	/**
	 * Get time (timestamp - time in secodns since 1970) of next execution      e.g.:  1695984751
	 *
	 * @param $action   = array(
	 *                              'time_dimension' => m
	 *                              'recurrence'     => 10
	 *                              'last_execution' => 1695984151      <- timestamp
	 *                         )
	 *
	 * @return float|int        1695989151                              <- timestamp
	 */
	public function get_task__timestamp__of_next_execution( $action ){

		$execution_interval_in_seconds = $this->get_task__execution_interval_in_seconds( $action );

		$next_time_execution = intval( $action['last_execution'] ) + $execution_interval_in_seconds;

		return $next_time_execution;
	}


	/**
	 * Get time in seconds until  next execution. If this value negative,  then task must  be executed already      e.g. 354
	 *
	 * @param $action   = array(
	 *                              'time_dimension' => m
	 *                              'recurrence'     => 10
	 *                              'last_execution' => 1695984151      <- timestamp
	 *                         )
	 *
	 * @return float|int        1695989151                              <- timestamp
	 */
	public function get_task__time_in_seconds__of_next_execution__from_now( $action ){

		$next_time_execution = $this->get_task__timestamp__of_next_execution( $action );

		$time_in_seconds = $next_time_execution - time();

		return $time_in_seconds;
	}


	/**
	 * Get readable time of last task  run      ( empty  string '' if no task)
	 *
	 * @param string $action_name   task name
	 *
	 * @return string
	 *
	 *               Example: $readable_time_of_next_run = WPBC()->cron->get_readable_time_of_last_run( 'wpbc_import_gcal' );
	 *               Output:   '2023-09-29 16:27:10'
	 */
	public function get_readable_time_of_last_run( $action_name ) {

		if ( empty( $this->actions[ $action_name ] ) ) {
			return '';
		}

		$cron_info = '';

		$active_tasks_arr = $this->get_active_tasks_info();

		if ( ! empty( $active_tasks_arr[ $action_name ] ) ) {
			$cron_info =  $active_tasks_arr[ $action_name ]['last_time__run__local'];
		}

		return $cron_info;
	}


	/**
	 * Get readable time of next task  run      ( empty  string '' if no task)
	 *
	 * @param string $action_name           task name
	 * @param $after_seconds_prefix_text    'text betweeen '2023-09-29' and '4 minutes 24 seconds'   - default '-'
	 *
	 * @return string
	 *
	 *               Example: $readable_time_of_next_run = WPBC()->cron->get_readable_time_of_next_run( 'wpbc_import_gcal' );
	 *               Output:   '2023-09-29 16:27:10 - 4 minutes 24 seconds'
	 */
	public function get_readable_time_of_next_run( $action_name , $after_seconds_prefix_text = '-') {

		if ( empty( $this->actions[ $action_name ] ) ) {
			return '';
		}

		$cron_info = '';

		$active_tasks_arr = $this->get_active_tasks_info();

		if ( ! empty( $active_tasks_arr[ $action_name ] ) ) {
			$cron_info .= $active_tasks_arr[ $action_name ]['next_time__run__local'];
			$cron_info .= ' ' . $after_seconds_prefix_text . ' ' . wpbc_get_readable_time_interval( $active_tasks_arr[ $action_name ]['next_run_after_seconds'] );
		}

		return $cron_info;
	}

}
