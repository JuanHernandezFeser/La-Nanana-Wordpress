<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;



		function wpbc_welcome_section_9_7($obj){

			$section_param_arr = array( 'version_num' => '9.7', 'show_expand' => !true );

			$obj->expand_section_start( $section_param_arr );

					?>
					<div class="feature-section two-col" style="margin-bottom:-40px">

						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">
									<img src="<?php echo $obj->asset_path; ?>9.7/wp-bookingooking-calendar-skind-new-green-white-02.png"
										style="margin:20px 5px 0;height: 250px;width:auto;box-shadow: 0 1px 3px #aaa;border-radius: 2px;padding-top: 5px;background: #fff;"
										class="wpbc-section-image" />
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 100%;width: 100%;">
							<?php  echo
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New** **"Light" calendar skin**: Displays booked slots in red, pending slots in orange, and available slots in white.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New** **"Green" calendar skin**.	Shows booked slots in red, pending slots in orange, and available slots in green.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New** **[add_to_google_cal_button] shortcode**: Allows users to add bookings to their Google Calendar. Available in "New (admin)", "New (visitor)", and "Approved" email templates in the Booking > Settings > Emails page.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** [add_to_google_cal_url] shortcode: Now utilizes booking data for seamless integration with Google Calendar.' ) . '</li>'
							. '</ul>';
							?>
						</div>
					</div><?php


					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Improvements and fixes' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Huge **code refactoring**, removing deprecated code from the plugin' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Removed the old deprecated Booking Listing page, and now only the new ajax Booking Listing page is available.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Added a "Find lost booking resources" button to the Booking > Resources page, allowing the retrieval of "child booking resources" of a deleted parent booking resource. *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Enhanced the display of specific booked "child booking resources" in "New (admin)" and "New (visitor)" emails, showing the name of the child resource instead of the parent resource. *(Business Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Streamlined the "Reset" button functionality in the booking form, allowing it to reset to the selected "predefined form template" at the Booking > Settings > Form page. *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
													. '</ul>' ;
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
						<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( '&nbsp;' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Corrected the display of the correct color for change-over days in the "Black 2" calendar skin.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Fixed internal HTML structure issues.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Resolved type inconsistencies in the code for form options.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Addressed conversion types issue.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Fixed the display of "The time(s) may be booked, or already in the past!" error message when selecting a time slot that has already passed for the current date.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Fixed the issue with correctly displaying change-over days (diagonal or vertical line) for check-out dates in booking resources with specific capacity. *(Business Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Resolved the issue where bookings were not being displayed correctly when the "Today check in/out" option was selected in the Filter toolbar on the Booking Listing page.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Lots of other improvements and fixes...' ) . '</li>'
													. '</ul>'; ?>
						</div>
					</div><?php


					//$obj->show_separator();


			$obj->expand_section_end( $section_param_arr );

		}

		function wpbc_welcome_section_9_6($obj){

			$section_param_arr = array( 'version_num' => '9.6', 'show_expand' => true );

			$obj->expand_section_start( $section_param_arr );

					?><div class="feature-section  one-col" style="padding:0;margin-bottom:-30px;">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">
			                <?php echo '<h4>' .wpbc_replace_to_strong_symbols( 'New modern design of booking admin UI' ) . '</h4>' ; ?>
						</div>
					</div><?php

					?><div class="feature-section two-col" style="margin-bottom:-40px">

						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">
							<?php  echo
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New modern design** in the booking **admin panel** that offers a sleek interface for managing your bookings.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Refreshed** and clear look of **UI elements** with updated booking toolbars, headers, and icons, that provides enhanced usability.' ) . '</li>'
							. '</ul>';
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 100%;width: 100%;">
							<?php  echo
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Support**. The ability to select the previous "Legacy Theme" of the booking admin panel on the Settings General page.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Updated styles of season filters on the Resources > Filters page (available in paid versions).' ) . '</li>'
							. '</ul>';
							?>
						</div>
					</div><?php


					?><div class="feature-section  one-col">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">

							<?php //echo '<h4>' .wpbc_replace_to_strong_symbols( 'New modern design of booking admin UI' ) . '</h4>' ;?>

							<img src="<?php echo $obj->asset_path; ?>9.6/wp_booking_calendar__availability_page_modern_ui_02.png"
									style="margin:20px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
									class="wpbc-section-image" />
						</div>
					</div><?php


					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Improvements and fixes' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Under hood**. The CSS for the time selector has been moved from wpbc_time-selector.css to client.css. This enables the use of the \'2 columns with times\' form template even when the times picker option is not activated.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** the issue of a duplicate database entry (DB:Duplicate entry 13-2023-07-19 00:00:00 for key booking_id_dates::INSERT INTO ...) that occurred when the option "Use time selections as recurrent time slots" was activated but no time slots were specified in the booking form.' ) . '</li>'
													. '</ul>' ;
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
						<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( '&nbsp;' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue where partially booked dates on the Booking > Availability page were not displaying correctly when the \'Do not change background color for partially booked days\' option was activated. However, to correctly use this feature, you must open the Booking > Availability page and click the \'Reset selected options to default values\' button in the \'User options menu\' in the top right toolbar.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** the issue where the message "The date or time may be booked, or already in the past!" was incorrectly displayed when using "_hints" shortcodes for the time fields, such as [durationtime_hint].' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Lots of other improvements and fixes...' ) . '</li>'
													. '</ul>'; ?>
						</div>
					</div><?php



					?><div class="feature-section  one-col">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">

								<img src="<?php echo $obj->asset_path; ?>9.6/wp_booking_calendar__bookings_page_modern_ui_01.png"
										style="margin:2px 5px 25px;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
					</div><?php

					//$obj->show_separator();


			$obj->expand_section_end( $section_param_arr );

		}

		function wpbc_welcome_section_9_5($obj){

			$section_param_arr = array( 'version_num' => '9.5', 'show_expand' => true );

			$obj->expand_section_start( $section_param_arr );

					?><div class="feature-section  one-col" style="margin-bottom:-30px">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">

							<div style="display:flex;flex-flow:row wrap; justify-content: space-between; align-items: center;   width: 80%;">
							<?php
								echo '<h4>' .wpbc_replace_to_strong_symbols( 'Super easy set available / unavailable dates in calendar' ) . '</h4>' ;
								echo '<h4>' .wpbc_replace_to_strong_symbols( '... just with 3 mouse clicks' ) . '</h4>' ;
								// echo '<h4>' .wpbc_replace_to_strong_symbols( 'Super easy set dates availability in calendar with just three mouse clicks' ) . '</h4>' ;
							?>
							</div>

							<img src="<?php echo $obj->asset_path; ?>9.5/wp_booking_calendar__availability_page_10.png"
									style="margin:10px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
									class="wpbc-section-image" />
						</div>
					</div><?php


					?><div class="feature-section one-col" style="">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">
							<?php  echo
							'<ul style="list-style: decimal inside;padding: 0 20px;margin:0;">'
		. '<li class="big_numbers">' . wpbc_replace_to_strong_symbols( '**Select** your **days** in calendar by  clicking on first and last day.' ) . '</li>'
		. '<li class="big_numbers">' . wpbc_replace_to_strong_symbols( 'Choose **available** / **unavailable** status in "availability section".' ) . '</li>'
		. '<li class="big_numbers">' . wpbc_replace_to_strong_symbols( 'Click on **Apply** button. That\'s it.' ) . '</li>'
							. '</ul>';
							?>
						</div>
					</div><?php

					$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							//'<h4>' .wpbc_replace_to_strong_symbols( 'Simulate login in MultiUser version' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Simple** configuration  of **dates availability** with 3 mouse clicks at new new **Availability** menu page.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Modern** and intuitive **interface with instant responses** on the same page.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Reset the availability for a specific calendar by clicking on the "**Reset availability**" button at the "User Options" toolbar.  This will remove all unavailable dates from the calendar.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'New calendar skins that show **unavailable dates with stripes**. ' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'The new **"Resource unavailable days" legend item** provides distinction between old unavailable dates and the new interface at Booking > Availability page.' ) . '</li>'
							. '</ul>' ;

							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 70%;">

							<img src="<?php echo $obj->asset_path; ?>9.5/wp_booking_calendar__availability_page_14.png"
							style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
							class="wpbc-section-image" />
						</div>
					</div><?php

					$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'New features and improvements' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New**. Disable show booked times in tooltip, when mouse over a specific day in the calendar. Activate it at the Booking > Settings General page in the "Time Slots" section.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Update showing total  booking cost instead of deposit,  while editing existing booking and previously  was used [cost_correction] shortcode. (9.4.4.2) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Ability to disable scrolling to calendar (open page at top of this page), after clicking on "Book now" button in search results, during searching availability. (9.4.4.6) *(Business Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Rename payment system  Ideal via "Sisow" to Ideal via "Buckaroo (former Sisow)",  because Buckaroo acquires payment service provider Sisow. *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Prevent keyboard activating when using the date picker on android devices in search form fields for selection  check in/out dates (9.4.4.8) *(Business Large, MultiUser)*' ) . '</li>'
		//. '<li>' . wpbc_replace_to_strong_symbols( '' ) . '</li>'
													. '</ul>' ;
							?>
						<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Translations' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Turkish  [99% completed] by Basar Okke' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Brazilian Portuguese [98% Completed] by Myres Hopkins' ) . '</li>'
													. '</ul>'; ?>

						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
						<?php
  							echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Under hood' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( 'If you made the calendar skin customization, you need to add a new CSS section in your calendar skin. This section  starts with **FixIn: 9.5.0.2** and ends with **FixIn End: 9.5.0.2** lines in new calendar skins. Add this section  before this code line: **Dates Cells**' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Load WP Util, that supports wp.template,  based on underscore _.template system, at front-end side. (9.4.4.11)' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'New JS event generated after inline calendar loaded: \'wpbc_datepick_inline_calendar_loaded\'. To catch this event use code: jQuery( \'body\' ).on( \'wpbc_datepick_inline_calendar_loaded\', function( event, resource_id, jCalContainer, instObj ) { ... } ); (9.4.4.12)' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Remove some old function of support IE 7' ) . '</li>'

													. '</ul>';
  							  ?>
						<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Other changes' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Support**. WordPress 6.2' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Correctly showing currency symbols in the "notes/remark" section under bookings in Booking Listing page (9.4.4.1) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Correctly show "booking data" for old bookings at the Booking Listing page that was made with a custom booking form,  which  was defined as "default" custom  booking form at the Booking > Resources page. (9.4.4.9) *(Business Medium/Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Correctly show "booking data" at the Payment summary page, if has been used custom booking form(9.4.4.10) *(Business Medium/Large, MultiUser)*' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Lots of other improvements and fixes...' ) . '</li>'
													. '</ul>'; ?>
						</div>
					</div><?php


			$obj->show_separator();

			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_9_4($obj){

			$section_param_arr = array( 'version_num' => '9.4', 'show_expand' => true );

			$obj->expand_section_start( $section_param_arr );

					?><div class="feature-section  one-col">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">

							<?php echo '<h4>' .wpbc_replace_to_strong_symbols( 'Update of calendar skins' ) . '</h4>' ;?>

							<img src="<?php echo $obj->asset_path; ?>9.4/skins-03.png"
									style="margin:20px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
									class="wpbc-section-image" />
						</div>
					</div><?php


					?><div class="feature-section two-col" style="margin-bottom:-30px">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">
							<?php  echo
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Update styles of all calendar skins. More **modern and crisp look**.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Padding between months** in multiple month view modes.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Calendars have a **minimum width** for correct view at small sections.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Added **two new calendar skins** without outer borders.' ) . '</li>'
							. '</ul>';
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 100%;width: 100%;">
							<?php  echo
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** showing calendars **at mobile devices**, while defined size of calendar in shortcode parameter.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'New parameter **strong_width** for definition  of calendar  width  in shortcode for option  parameter. Please check more at <a href="https://wpbookingcalendar.com/faq/shortcode-booking-form/#booking-options">FAQ</a>' ) . '</li>'
							. '</ul>';
							?>
						</div>
					</div><?php


					?><div class="feature-section  one-col">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">

								<img src="<?php echo $obj->asset_path; ?>9.4/booking_calendar_skin_05.png"
										style="margin:2px 5px 25px;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
					</div><?php

					$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Improvements and fixes' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Removed meta tags from the email templates to **prevent marked bookings emails as spam** on some servers.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood**. New WPBC_AJX__REQUEST class for sanitizing, saving and helping working with user requests.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Fix dashboard/agenda links of showing "New | Pending | Today " bookings, in a new ajax Booking Listing  page.' ) . '</li>'
													. '</ul>' ;
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
						<?php  /*
  							echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Improvements in paid versions' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Disable booked time slots, for predefined selected date in the booking form (it is shortcode for booking form, without the calendar)' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Update text in settings.' ) . '</li>'
													. '</ul>';
  							 */ ?>
						<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Other changes' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Disable booked time slots, for predefined selected date in the booking form (in shortcode for booking form, without the calendar). In Booking Calendar Business Large or higher version.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. During validation when submitting the booking form, focus on the first field that requires action. This will help complete the booking form.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Lots of other improvements and fixes...' ) . '</li>'
													. '</ul>'; ?>
						</div>
					</div><?php


			$obj->show_separator();

			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_9_3($obj){

			$section_param_arr = array( 'version_num' => '9.3', 'show_expand' => true );

			$obj->expand_section_start( $section_param_arr );

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 75%;width: 100%;">
								<img src="<?php echo $obj->asset_path; ?>9.3/edit-booking-free.png"
										style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 80%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Edit bookings in the Free version' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li style="margin-bottom:2em;">' . wpbc_replace_to_strong_symbols( '**Edit bookings** in the Booking Calendar Free version. <p>You can easily **change booking details** for an existing booking or **re-select booking date(s)** for such booking in a few seconds.</p>' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood**. A **hash** field has been added to  the booking table in all versions of the Booking Calendar.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood**. Added **creation_date** field to booking table.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood**. An **is_trash** field has been added to the booking table, to indicate the date of the booking was trashed.' ) . '</li>'
							. '</ul>';
							?>
						</div>
					</div><?php

					$obj->show_separator();

if(1){
					?><div class="feature-section  one-col">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">
							<?php //echo '<h4>' .wpbc_replace_to_strong_symbols( 'New Booking Listing - modern looking & instant working' ) . '</h4>' ;?>
							<?php  if(1) echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Simulate login in MultiUser version' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( 'You can now easily **change** booking **form, email** templates or **payment** details **for Regular user**, while you have **logged in as Super Admin**. <p>You can drill down to regular user admin panel on the Booking > Settings > Users page.</p>' ) . '</li>'
													. '</ul>';
							?>
								<img src="<?php echo $obj->asset_path; ?>9.3/simulate_login2.png"
										style="margin:1px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
					</div><?php
}

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							//'<h4>' .wpbc_replace_to_strong_symbols( 'Simulate login in MultiUser version' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Simulate the login of a regular user** (user with restricted access only to own resources) from a "super booking administrator" account for ability to change the settings of such a regular activated user in the Booking Calendar MultiUser version.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Receive payments for "Regular User" bookings to the "Super Booking Admin" payment account. Activate this option at the Booking > Settings General page in the "MultiUser" section.' ) . '</li>'
							. '</ul>' ;

							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 70%;">

							<img src="<?php echo $obj->asset_path; ?>9.3/simulate_log_out.png"
							style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
							class="wpbc-section-image" />
						</div>
					</div><?php

				$obj->show_separator();



					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 60%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Other new features' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New**. Shortcode in emails **[cost_digits_only]** - to  show booking cost without currency.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to define **title of "Search availability" button** in search form at the Booking > Settings > Search page. Use shortcodes like this: **[search_button "Search availability"]**' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Replaced Stripe php library to newest 9.0.0 version.' ) . '</li>'

													. '</ul>' ;
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
						<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Improvements and fixes' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Error message in Stripe payment form: "Caught exception: You cannot use line_items.amount, line_items.currency, line_items.name, line_items.description, or line_items.images in this API version."' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Notice: Undefined index: select_booking_form in class-admin-settings-api.php' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Lots of other improvements and fixes...' ) . '</li>'
													. '</ul>'; ?>
						</div>
					</div><?php



			$obj->show_separator();

			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_9_2($obj){

			$section_param_arr = array( 'version_num' => '9.2', 'show_expand' => true );

			$obj->expand_section_start( $section_param_arr );


					?><div class="feature-section  one-col">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">
							<?php echo '<h4>' .wpbc_replace_to_strong_symbols( 'New Booking Listing - modern looking & instant working' ) . '</h4>' ;?>
								<img src="<?php echo $obj->asset_path; ?>9.2/booking_listing_text_searching_long.png"
										style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 100%;width: 100%;">
							<?php  echo
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New modern toolbar** with handy buttons, dropdown lists and other elements that have been redesigned and rearranged in a new smarter way.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Refreshed colors** of labels, icons, buttons and other UI elements for modern and clear look.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Rearranged content of booking details in unified way for all Booking Calendar versions.' ) . '</li>'
													. '</ul>';
							?>
						</div>
					</div><?php

					$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Instant working without page reloading' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Instant showing booking listing**, using ajax without page reloading when filtering search results. Single page app design.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Immediate **searching** of bookings for a **specific keyword**. Reservations are displayed just after entering a specific keyword without other user actions.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Highlighting** certain **keywords** in booking details to make it easier to find specific data when entering keywords.' ) . '</li>'
													. '</ul>' ;
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 70%;">

							<img src="<?php echo $obj->asset_path; ?>9.2/screenshot-01_high_res.gif"
							style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
							class="wpbc-section-image" />
						</div>
					</div><?php

				$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 75%;width: 100%;">
								<img src="<?php echo $obj->asset_path; ?>9.2/booking_listing_dates_filtering.png"
										style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'User Saved Options' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Saving** any choice of **filter options** (select-boxes and other UI toolbar elements), toolbar selection or user options **personally for each user**. User will see last configured search filter parameters each time, when open admin panel.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Keeping** last selected **filter or actions toolbar**, during each time, when user open booking admin panel. No need to define default state of toolbars in the settings.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New user options toolbar**, where possible to select "Emails sending" on specific actions or "Show notes" by default (in paid versions).' ) . '</li>'
							. '</ul>';
							?>
						</div>
					</div><?php

					$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Print functionality' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( 'The new **print feature** is available in the **Free version**.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'New **print layout** displaying the exact content of the booking listing page.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Print any selected bookings**. Possibility to print all bookings / selected bookings / specific single booking.' ) . '</li>'

													. '</ul>' ;
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 70%;">

							<img src="<?php echo $obj->asset_path; ?>9.2/booking_listing_printing.png"
							style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
							class="wpbc-section-image" />
						</div>
					</div><?php

				$obj->show_separator();



					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Other new features' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Added 2 new filter options **Check in today** and **Check out today** to display bookings on the booking list page' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to define and **save the locale for each specific booking**. This locale is saved and will exist when the following pages are loaded.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Show action** buttons when such actions **can be applied to specific bookings**. You need to select bookings to display additional action buttons like "Confirm" or "Reject", etc...' ) . '</li>'

													. '</ul>' ;
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
						<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'CSV Export (paid versions)' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New**. Ability to enter **field names to skip** from the export.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Selection **export of single or all pages** and CSV column separator in pop-up window.' ) . '</li>'
													. '</ul>'; ?>
						<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Improvements and fixes' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Smarter structure of request parameters escaping.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Lots of other improvements and fixes...' ) . '</li>'
													. '</ul>'; ?>
						</div>
					</div><?php



			$obj->show_separator();

			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_9_1($obj){

			$section_param_arr = array( 'version_num' => '9.1', 'show_expand' => true );

			$obj->expand_section_start( $section_param_arr );

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 75%;width: 100%;">
								<img src="<?php echo $obj->asset_path; ?>9.1/9.1_new_popovers2.png"
										style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'New Popover and Tooltips' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Faster and more accurate display of beautiful and informative popovers and tooltips. New script for displaying popover and tooltips in the Booking Calendar.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'For 2 or more bookings on the Timeline and Calendar Overview page, the system displays the title of the bookings exactly near to the specific booking details in popovers' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Avoiding script conflicts regarding popovers and tooltips' ) . '</li>'
													. '</ul>';
							?>
						</div>
					</div><?php

					$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'New UI elements' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Icons for UI elements** in the booking admin panel.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Booking Calendar Icon** for WordPress side menu.' ) . '</li>'
													. '</ul>' .
							'<h4>' .wpbc_replace_to_strong_symbols( 'Improvements and fixes' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Separate library for modal windows to prevent script conflicts' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Lots of other improvements and fixes...' ) . '</li>'
													. '</ul>';
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">

							<img src="<?php echo $obj->asset_path; ?>9.1/9.1_new_icons2.png"
							style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
							class="wpbc-section-image" />
							<div style="font-style:italic;font-size:0.75em;padding:1em;text-align: right;">* This picture from paid version of Booking Calendar</div>
						</div>
					</div><?php

			$obj->show_separator();

			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_9_0($obj){

			$section_param_arr = array( 'version_num' => '9.0', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Time slots' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Showing **time slots as dots** in calendar day cells. Modern and beautiful view of time slots within calendar days.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Display the **exact number** of booked **time slots** in calendar day cells. System shows **as many dots** (time slots) as many time slots have been booked for a particular day. Your customers can see occupancy by time interval from the beginning of the calendar view.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** CSS styling in all calendar skins. If you have customized a calendar skin before, check the changes in the new calendar skins marked with a number: 8.9.4.13  ' ) . '</li>'
													. '</ul>';
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">

							<img src="<?php echo $obj->asset_path; ?>9.0/wpbc-9-0-time-slots.png"
							style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
							class="wpbc-section-image" />

						</div>
					</div><?php

					$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
								<img src="<?php echo $obj->asset_path; ?>9.0/wpbc-9-0-co2.png"
										style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Change over days' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Accurate clear display of the diagonal change over days line. Now it correctly shows the **diagonal line for any shape of day cells** (square or rectangle). This means that for any calendar size you will see the correct sharp diagonal line.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Show **diagonal line in dates**, where we have check in/out bookings with the **same status (pending or approved)**. It is useful to see where one booking ends and another begins when both bookings are pending or approved. Previously it was shown just full booked date without diagonal line.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'The ability to use **change over days only on certain pages**. Useful in a situation where you need to make a booking with change over days at on a certain page(s) (check in/out dates with diagonal lines) and make bookings for specific time slots on another page(s). You can define list of pages on which to use this feature at the Booking > Settings General page in "Calendar" section.' ) . '</li>'
													. '</ul>';
							?>
							<span style="font-size:0.8em;padding:1em;">* This feature is available in the Booking Calendar Business Small or higher versions.</span>
						</div>
					</div><?php

					$obj->show_separator();


					?><div class="feature-section  one-col">
						<div class="col col-1" style="flex: 1 1 100%;width: 100%;">
							<?php echo '<h4>' .wpbc_replace_to_strong_symbols( 'Timeline' ) . '</h4>' ;?>
								<img src="<?php echo $obj->asset_path; ?>9.0/wpbc-9-0-timeline.png"
										style="margin:30px 5px 0;width: 100%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
										class="wpbc-section-image" />
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 100%;width: 100%;">
							<?php  echo
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Timeline**. Ability to **define how many days to show in Timeline** at the front-end side, while showing Timeline for one booking resource, and select "**Month view mode**" in shortcode (parameter "view_days_num=30" or this parameter skipped). You can define it at the Booking > Settings General page in "Calendar Overview | Timeline" section.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Calendar Overview** page. Ability to **define how many days to show** in the Calendar Overview page in the admin panel, while showing Calendar Overview page for one booking resource, and selected "**Day view mode**". You can define it at the Booking > Settings General page in "Calendar Overview | Timeline" section.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Timeline / Calendar Overview page. Scroll exactly the number of days (for one booking resource, and select "Month view mode" / "Day view mode"), that was defined at option "Days number to show in Month mode in Timeline" / "Days number to show in Day view mode in Calendar Overview page".' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( 'Separate settings sections for "Calendar Overview" | "Timeline" options at the Booking > Settings General page.' ) . '</li>'
													. '</ul>';
							?>
						</div>
					</div><?php

					$obj->show_separator();

					?><div class="feature-section  two-col">
						<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
							<?php  echo
							'<h4>' .wpbc_replace_to_strong_symbols( 'Translations' ) . '</h4>' .
							'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New**. Ability to define where firstly plugin tries to use translations from "../wp-content/languages/plugins/", or from "../wp-content/plugins/{Booking Calendar Folder}/languages/" folder. You can change this behavior at the Booking > Settings General page.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New**. Force plugin translation update. Download and update plugin translations from WordPress translation repository and from wpbookingcalendar.com You can make updates at Booking > Settings General page in Translation section.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**New**. Check translation status at WordPress translation repository and local translation from wpbookingcalendar.com to understand what translation to load. You can check it at the Booking > Settings General page in the Translation section.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Booking Calendar by default does not contain MO and PO translation files. You can force download them at the Booking > Settings General page in the Translation section.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Switching language/locale by using "Globe icon" in the Booking Listing page has higher priority than switching languages by translation plugins.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Optimization structure of country list file for future translations.' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Compatibility**. Support WPML 4.5.4' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Compatibility**. Support Polylang 3.1.4' ) . '</li>'
		. '<li>' . wpbc_replace_to_strong_symbols( '**Compatibility**. Support qTranslate-X 3.4.6.8 (Currently closed "qTranslate-X", was tested with Booking Calendar in php 5.6)' ) . '</li>'
													. '</ul>';
							?>
						</div>
						<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">

							<img src="<?php echo $obj->asset_path; ?>9.0/wpbc-9-0-translations.png"
							style="margin:30px 5px 0;width: 62%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
							class="wpbc-section-image" />

						</div>
					</div><?php


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_9($obj){

			$section_param_arr = array( 'version_num' => '8.9', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );


				?><div class="feature-section  two-col">

				<div class="col col-1" style="flex: 1 1 50%;width: 100%;">
					<?php
					echo
						'<h4>' .wpbc_replace_to_strong_symbols( 'New' ) . '</h4>' .
						'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Show debug cost information of "Daily costs" and "Additional costs" to better understand how costs are working. Activate it at the Booking > Settings > Payment page in the Payment options section. *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to calculate the deposit amount based on daily costs only, without additional costs. Activate it at  the Booking > Settings > Payment page in the Payment options section.  *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to export into .ics feeds only bookings, that was created in the Booking Calendar plugin,  without any  other imported bookings. Activate it at Booking > Settings > Sync > "General" page.  Available in Booking Manager update 2.0.20 or newer. ' ) . '</li>'

												. '</ul>'
												. '<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Show error message, if activated to use CAPTCHA and PHP configuration does not have activated GD library.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Show help message, about troubleshooting of "Request do not pass security check!" error.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Showing centered booking form,  while using simple booking form  configuration.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'The Debug function  shows HTML elements during output of strings.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'If we are using the [cost_corrections] shortcode in the booking form for entering our cost at Booking > Add booking page, then we can use in the New booking emails such shortcodes [corrected_total_cost], [corrected_deposit_cost], [corrected_balance_cost]. *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Escape any  html  tags from  the booking resource  titles in emails. *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'

												. '</ul>';

					?>
				</div>
				<div class="col col-2 last-feature" style="flex: 1 1 auto;width: 60%;">
					<img src="https://wpbookingcalendar.com/assets/8.9/debug_valuation_days.png"
						style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
						class="wpbc-section-image" />
					<img src="https://wpbookingcalendar.com/assets/8.9/debug_advanced_costs.png"
						style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
						class="wpbc-section-image" />

					<img src="https://wpbookingcalendar.com/assets/8.9/rates_debug_show.png"
						style="margin:30px 5px 0;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
						class="wpbc-section-image" />
					<span style="font-size:0.8em;padding:1em;">* This feature is available in the Booking Calendar Business Medium or higher versions.</span>
				</div>

				</div><?php


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_8($obj){

			$section_param_arr = array( 'version_num' => '8.8', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );


				?><div class="feature-section  two-col">
				<div class="col col-1" style="flex: 1 1 auto;width: 60%;">
					<img src="https://wpbookingcalendar.com/assets/9.0/time-picker-premium-black.png"
						style="margin:30px 5px;width: 98%;box-shadow: 0 1px 3px #aaa;border-radius: 2px;"
						class="wpbc-section-image">
				</div>
				<div class="col col-2 last-feature" style="flex: 1 1 50%;width: 100%;">
					<?php
					echo
						'<h4>' .wpbc_replace_to_strong_symbols( 'New' ) . '</h4>' .
						'<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Time picker** for **times slots selection** in the booking form. Activate it at the Booking > Settings General page in the "Time Slots" section.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Skins** for **Time picker** available for **times slots selection** in the booking form. Activate it at the Booking > Settings General page in Time Slots section.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Premium calendar skins** now available in **Booking Calendar Free** version.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Form template **2 columns with time slots" for showing booking form fields in 2 columns with time slots selection.  *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'

												. '</ul>'
												. '<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'More intuitive adding and editing new fields (during editing in simple booking form mode). Showing the "Save changes" button relative only to active action.' ) . '</li>'
												. '</ul>';

					?>
				</div>
			</div><?php



				$obj->show_col_section( array(
									array( 'text' =>
	''
												. '<h4>' .wpbc_replace_to_strong_symbols( 'Fixes' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Error of correct converting special  symbols,  like #, %, \', " to URL symbols during clicking on "Export to Google Calendar" button. (8.7.11.4)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of correct showing special  symbols,  like #, %, \', " in the titles of bookings at  Calendar Overview page. (8.7.11.5)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of no ability to  book  some time slots when activated multiple days selection. (8.7.11.6)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Warning jQuery.parseJSON event shorthand is deprecated.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Warning jQuery.fn.mousedown() event shorthand is deprecated.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Warning jQuery.fn.click() event shorthand is deprecated.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Warning jQuery.fn.focus() event shorthand is deprecated.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Warning jQuery.fn.change() event shorthand is deprecated.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Warning jQuery.isFunction() event shorthand is deprecated.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Warning jQuery.fn.bind() event shorthand is deprecated.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Warning jQuery.fn.removeAttr no longer sets boolean properties: disabled.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Fixing issue of incorrectly showing booking date in plugin, if visitor was entered end time as 24:00 instead of 23:59. (8.7.11.1) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Fixing issue of incorrectly showing coupon code discount hints, if activated option "Apply discount coupon code directly to days cost". (8.7.11.2) *(Business Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Select first available option (timeslot) in the dropdown list, that showing based on days conditions , after selection of date in calendar. (8.7.11.3) (Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not sending "approved email", if sending email checkbox was unchecked at the Booking > Add booking page and auto approval for Booking > Add booking page has been activated. (8.7.11.8) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
												. '</ul>'
												)
												, array( 'text' =>

												  '<h4>' .wpbc_replace_to_strong_symbols( 'Compatibility' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Support **WordPress 5.6**.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Full support of **jQuery 3.5**.' ) . '</li>'
												. '</ul>'

												. '<h4>' .wpbc_replace_to_strong_symbols( 'Translation' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Dutch translation by Boris Hoekmeijer.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Swedish translation by Jimmy Sjølander.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Norwegian translation by Jimmy Sjølander.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Spanish translation by Jairo Alzate.' ) . '</li>'
												. '</ul>'

												. '<h4>' .wpbc_replace_to_strong_symbols( 'Under Hood' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Trigger event "wpbc_hook_timeslots_disabled" after disabling times in the booking form. To bind this event use this JS: jQuery( ".booking_form_div" ).on( \'wpbc_hook_timeslots_disabled\', function ( event, bk_type, all_dates ){ ... } );' ) . '</li>'
												. '</ul>'

											  )
									)
								);


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_7($obj){

			$section_param_arr = array( 'version_num' => '8.7', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );

				?>
				<img src="<?php echo $obj->asset_path; ?>8.7/booking-calendar-black2.png" style="border:none;box-shadow: 0 0px 2px #bbb;margin: 2%;width:98%;display:block;" />
				<div class="clear"></div>
				<?php

				$obj->show_col_section( array(
									array( 'text' =>
												  '<h4>' .wpbc_replace_to_strong_symbols( 'New' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'New **Calendar Skin** with dark colors: "**Black 2**"' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to define using **Pending days as Selectable days** - its means that such days have the colors the same as Pending days, but visitor still can select and submit new booking, until you do not approve some booking. Its useful in case, if you need to show that at some days already exist bookings, but visitors still can submit the booking. Please note, such feature will not work correctly if you will make bookings for specific time-slots (its will show warning). How to Use ? In the page, where you are having Booking Calendar shortcode, you need to define the js, like this: &lt;script type="text/javascript"&gt; wpbc_settings.set_option( "pending_days_selectable", true ); &lt;/script&gt; [booking type=1 nummonths=2] (8.6.1.18)' ) . '</li>'

	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to define **dates format** for **search availability form** at the Booking > Settings > Search page. (8.6.1.21) *(Business Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Logging** of approving or set as pending bookings to notes section. You can activate this option "Logging of booking approving or rejection" at the Booking > Settings General page in "Booking Admin panel" section. (8.6.1.10) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Updated **iPay88** - Payment Gateway integration v1.6.4 (For Malaysia Only) (8.6.1.3) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
												. '</ul>'

												. '<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to auto fill "nickname" of user, when user logged in, and checked this option "Auto-fill fields". In booking form have to be field with name "nickname". (8.6.1.2)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Usage of **new Wizard style booking form**, where possible to configure several steps in booking form - **more than 2 steps** (8.6.1.15) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to use option "Use check in/out time", for adding check in/out times to use change over days, when importing events via Google Calendar API (using Google API Key) (8.6.1.1) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to use option "Append check out day", for adding check out day, when importing events via Google Calendar API (using Google API Key) (8.6.1.4) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Disable the edit / cancel / payment request links in the "Booking Customer Listing" view for "Approved bookings", in case, if you have activated this option " Change hash after the booking is approved " at the Booking > Settings General page in Advanced section. (8.6.1.6) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Replace non standard symbols (like: . or , or ` ) in options for ability correct saving Advanced cost. Otherwise sometimes was not possible to save "Advanced cost" at Booking > Resources > Advanced cost page.  (8.6.1.7) *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Added filter hook "wpbc_booking_resources_selection_class" for controlling CSS class in dropdown element of booking resource selections (8.6.1.9) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Update booking hash during moving booking to trash or restore bookings, for do not ability to edit or cancel such bookings by visitor (8.6.1.11) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Add ability to use only labels in shortcode for showing one payment method (its works only with these exact options): [select payment-method "All payment methods@@" "Stripe" "PayPal" "Authorize.Net" "Sage Pay" "Bank Transfer" "Pay in Cash" "iPay88" "iDEAL"] (8.6.1.16) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to  activate updating booking cost after editing booking in admin panel, based on new booking data. You can activate this option  at the Booking > Settings > Payment page  (8.6.1.24) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
												. '</ul>'
												. '<h4>' .wpbc_replace_to_strong_symbols( 'Translation' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'

	. '<li>' . wpbc_replace_to_strong_symbols( 'German translation [99% completed] by Markus Neumann.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Slovenian translation [99% completed] by Klemen Gaber.' ) . '</li>'
												. '</ul>'

												)
												, array( 'text' =>

												  '<h4>' .wpbc_replace_to_strong_symbols( 'Compatibility' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Support **WordPress 5.3** - updated styles of booking admin panel.' ) . '</li>'
												. '</ul>'

												. '<h4>' .wpbc_replace_to_strong_symbols( 'Deprecated' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Removing deprecated Timeline v.1. Currently available only new Flex Timeline (Calendar Overview) (8.6.1.13)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Removing deprecated Stripe v.1 integration. Now available only Stripe v.3 integration that support SCA (8.6.1.12) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
												. '</ul>'

												. '<h4>' .wpbc_replace_to_strong_symbols( 'Fixes' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue Undefined index: name in ../core/admin/wpbc-class-timeline.php on line 2137' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not ability to enter new value of CAPTCHA without page reloading, if previous entered value was incorrect. (8.6.1.8)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Order of week days in Arabic translation for calendar' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Show payment description about the booking in Stripe dashboard in Metadata section for Stripe v.3 integration (8.6.1.20) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of showing negative balance hint, during using deposit feature with zero cost (8.6.1.5) *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of incorrectly showing available results in "Advanced search results" (while using the shortcode like this [additional_search "3"] at the Booking > Settings > Search page), and if dates in some resources was marked as unavailable via season filters. (8.6.1.14) *(Business Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of incorrectly showing available results, when searching only for 1 specific day (check in/out dates the same in availability form), and we have booked (as full day), this day in specific booking resource. (8.6.1.19) *(Business Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of incorrectly disabling end time options in select-box (8.6.1.17) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of slow loading calendar (executing too many sql requests), when season filter was deleted at the Booking > Resources > Filters page, but reference relative (Rates) still exist at Booking > Resources > Cost and rates page. Its means that the Rates was not updated (re-saved) relative specific booking resource at the Booking > Resources > Cost and rates page. (8.6.1.22) *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of possible showing status of Stripe v.3 payment as successful at the Booking Listing page, even when its was not completed yet. (8.6.1.23) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'

												. '</ul>'
											  )
									)
								);

			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_6($obj){

			$section_param_arr = array( 'version_num' => '8.6', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );


				/*
				 *	This update in memory of my Father - great, very responsible and lovely person, that set right direction in my life. [ SVI 2.19.52 - 8.6.19 ]
				 */
				?>
				<div style="width:80%;margin:auto;height:auto;background: #f7f7f7;padding: 10px 25px;border-radius: 8px;text-align: center;font-weight: 600;color: #949494;">This update in memory of my Father - great, very responsible and lovely person, that set right direction in my life. <br/>[ SVI 2.19.52 - 8.6.19 ]</div>

				<img src="<?php echo $obj->asset_path; ?>8.6/flex-timeline-single-month-pure-2.png" style="border:none;box-shadow: 0 0px 2px #bbb;margin: 2%;width:98%;display:block;" />
				<p style="text-align:center;"><?php echo wpbc_replace_to_strong_symbols( 'New interface of **Calendar Overview** in admin panel  and **Timeline** at front-end side with new clean, flex design.'); ?></p>
				<div class="clear"></div>
				<?php

				$obj->show_col_section( array(
									array( 'text' =>
												  '<h4>' .wpbc_replace_to_strong_symbols( 'New' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Updated new interface of **Calendar Overview** in admin panel  and **Timeline** at front-end side with new clean, flex design.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Timeline & Calendar Overview** - mobile friendly look.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Timeline & Calendar Overview** - nicely showing several bookings for the same date(s) (dividing day into several rows). For example during bookings for specific times,  while showing Month Timeline view.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Timeline & Calendar Overview** - very handy hints for each day of booking, when mouse over specific booking day.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Timeline & Calendar Overview** - aggregated booking details title marked with different color for easy finding and checking how many bookings in specific date(s).' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Timeline & Calendar Overview** - ability to restore old Timeline look at  Booking > Settings General page in Timeline section.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Section "Calendar Overview | Timeline" at  Booking > Settings General page (8.5.2.20)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Button "**Empty Trash**" at  Booking Listing  page in Action toolbar to completely  delete All bookings from  Trash (8.5.2.24)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to **export only approved bookings into .ics feeds**. Available in Booking Manager plugin since 2.0.11 or newer update. (8.5.2.3)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Do not update cost of booking, while editing this booking. (8.5.2.1)  *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
												. '</ul>'

												. '<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'More clean colors for booking details at the Booking Listing page (8.5.2.5)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Added <code>[add_to_google_cal_url]</code> - shortcode in "Approved booking" email template for fast manual adding of booking to Google Calendar (8.5.2.13)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'New Flex Template for search form - more nice CSS style for search form and search results (8.5.2.11)  *(Business Large, MultiUser)*' ) . '</li>'
												. '</ul>'


												. '<h4>' .wpbc_replace_to_strong_symbols( 'Under Hub' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Useful **hook** for **Auto approve** bookings only for **specific booking resources**: <code>apply_filters( \'wpbc_get_booking_resources_arr_to_auto_approve\', $booking_resources_to_approve );</code>.<br> Add code similar  to this in your functions.php file in your theme,  or in some other php file: <br/><code>function my_wpbc_get_booking_resources_arr_to_auto_approve( $resources_to_approve ) { <br>$resources_to_approve = array( 1, 9, 12, 33 ); <br>return $resources_to_approve; } <br>add_filter( \'wpbc_get_booking_resources_arr_to_auto_approve\', \'my_wpbc_get_booking_resources_arr_to_auto_approve\' );</code>  (8.5.2.27)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Useful **hook** for Google Adwords Conversion tracking: <code>do_action( \'wpbc_track_new_booking\', $params );</code> Add code similar  to this in your functions.php file in your theme,  or in some other php file: <code>add_action( \'wpbc_track_new_booking\', \'my_booking_tracking\' ); <br>function my_booking_tracking( $params ){  <br>//*Your Google Code for Booking Conversion Page*<br>}</code>   (8.5.2.25)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to define rechecking cost with PayPal tax during response of PayPal IPN. Require of adding function like this: <br/><code>function my_wpbc_paypal_ipn_tax( $paypal_tax_percent ){ return 20; } <br/>add_filter( \'wpbc_paypal_ipn_tax\', \'my_wpbc_paypal_ipn_tax\' );</code> (8.5.2.2)  *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'More easy find lost bookings (in booking resource(s) that have been deleted). Now, its show only lost bookings. Use link like this: <br/><code>http://server/wp-admin/admin.php?page=wpbc&wh_booking_type=lost</code> (8.5.2.19)  *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Show only one payment system after booking process, if visitor selected payment system in booking form. Example:  of shortcode for showing selection of payment forms: <code>Select payment method: [select payment-method "All payment methods@@" "Stripe@@stripe_v3" "PayPal@@paypal" "Authorize.Net@@authorizenet" "Sage Pay@@sage" "Bank Transfer@@bank_transfer" "Pay in Cash@@pay_cash" "iPay88@@ipay88" "iDEAL@@ideal"]</code>  This solution  was suggested by "Dan Brown". Thank you. (8.5.2.28) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
												. '</ul>'

												. '<h4>' .wpbc_replace_to_strong_symbols( 'Translation' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'

	. '<li>' . wpbc_replace_to_strong_symbols( 'French translation [100% completed] by Philippe Nowak and Alain Pruvost' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Hungarian translation [99% completed] by Vincze István' ) . '</li>'
												. '</ul>'

												)
												, array( 'text' =>

												  '<h4>' .wpbc_replace_to_strong_symbols( 'Fixes' ) . '</h4>'
												. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of blocking entire day, if in booking form was used start time and end or duration of time fields and visitor use multiple days selection mode, and all start time options for specific day was booked. In multiple day selection mode its incorrect, because user can start days selection at available day, and finish selection with end time at this partially booked day, where no available start-time. Now system block such dates only during single day selection mode. (8.5.2.4)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Disable send button,  after submit booking, for prevent of several same bookings (8.5.2.7)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not showing bookings that  start  from  \'yesterday\' date at Booking Listing  page,  when  selecting \'Current dates\' in Filter toolbar. (8.5.2.14)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not showing bookings that  start  from  \'today\' date at Booking Listing  page,  when  selecting \'Past dates\' in Filter toolbar. (8.5.2.16)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not ability to submit the booking for additional calendar(s),  if used booking form  with  several  calendars and was not selected date(s) in main calendar (8.5.2.26) *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not showing booking resource in search availability results, if resource was booked for specific time-slot on specific date, where we search availability. (8.5.2.7) *(Business Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of showing default booking resource instead of "All booking resources" for Regular user in  MultiUser version at the Booking Listing  and Calendar Overview pages,  while was set show "All resources" at  the Booking > Settings General page. (8.5.2.8) *(MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of prevent loading Stripe v.3 at  some systems,  where PHP version lower than PHP 5.4 (8.5.2.9) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of "not auto selecting dates" during editing/cancellation of the booking by  visitor,  and not updating cost / dates hints in some systems. Conflict with  "WPBakery Page Builder" plugin.  (8.5.2.10) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not showing warning message about not checked checkbox, during validation required checkboxes that have several options and one option was checked. (8.5.2.12) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not submitting booking for additional calendars (if using several  calendars and one booking form), if payment form does not show for such  bookings (8.5.2.17) *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of not showing as booked dates in calendar,  that  relative to  change-over days,  while activated "Allow unlimited bookings per same day(s)" option. (8.5.2.18) *(Business Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Issue of incorrectly  showing additional  cost  hints for options,  that  was defined as percentage at the Booking > Resources > Advanced cost page. (8.5.2.21) *(Business Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Do  not send emails,  if was empty email  field (its possible in situation,  when  in booking form several email  fields for several  persons), otherwise was showing error (8.5.2.22) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Start using "choozen"  library  for selection of booking resources just during page loading (because library loaded in head), instead of using after  full  page loaded. Its prevent issue of showing wide selectbox during page loading. (8.5.2.23)' ) . '</li>'

												. '</ul>'
											  )
									)
								);


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_5($obj){

			$section_param_arr = array( 'version_num' => '8.5', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );

						$obj->show_separator();

									$obj->show_col_section( array(
														array( 'text' =>
						  '<h4>' .wpbc_replace_to_strong_symbols( 'New' ) . '</h4>'
						. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Highlight code syntax** for **booking form** configuration at Booking > Settings > Form page,  and show warnings about possible issues. (8.4.7.18)  *(Personal Business Small/Medium/Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Highlight code syntax** for **search form** and search results form configuration at Booking > Settings > Search page,  and show warnings about possible issues. (8.4.7.18)  *(Business Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Update of **Stripe** Integration via "**Checkout Server**" method, which use "**Strong Customer Authentication**" (SCA) - a new rule coming into effect on September 14, 2019 as part of PSD2 regulation in Europe, will require changes to how your European customers authenticate online payments. (8.4.7.20)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Approve booking in 1 mouse click** on link in email about new booking sending to Administrator. Even without requirement to login to WordPress admin panel. Its require to  use **[click2approve]** shortcode at Booking > Settings > Emails > New (admin) page. (8.4.7.25)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Decline booking in 1 mouse click** on link in email about new booking sending to Administrator. Even without requirement to login to WordPress admin panel. Its require to  use **[click2decline]** shortcode at Booking > Settings > Emails > New (admin) page. (8.4.7.25)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Trash booking in 1 mouse click** on link in email about new booking sending to Administrator. Even without requirement to login to WordPress admin panel. Its require to  use **[click2trash]** shortcode at Booking > Settings > Emails > New (admin) page. (8.4.7.25)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Ability to define sort **order of search  availability results** at the Booking > Settings > Search page. (8.4.7.8) *(Business Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '** Experimental Feature**. Trash all imported bookings before new import. Move all previously imported bookings to trash before new import bookings. Its can **resolve issue of updating deleted and edited events in external sources**. Activate this option at Booking > Settings > Sync > "General" page. Its work only, if you are using one source (.ics feed) for importing into specific booking resource! Work only in update of Booking Manager 2.0.10 or newer. (8.4.7.12)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Force import**. Ability to import bookings without checking, if such bookings already have been imported. Activate this option at Booking > Settings > Sync > "General" page.  Available in the Booking Manager 2.0.10 or newer. (2.0.10.1)(8.4.7.1)' ) . '</li>'
						. '</ul>'

						. '<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
						. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Changed color of "Imported" label for bookings in Booking Listing page (8.4.7.2)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Show "Do you really want to do this ?" popup, when admin try to Trash or Delete booking in Calendar Overview page (8.4.7.14)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Show button "Find Lost Bookings" at the Booking Settings General page in Help  section,  for ability to  show all  exist  bookings, and find possible some lost bookings. (8.4.7.19)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Booking Calendar does not require jquery-migrate library, as obligatory library anymore. Its means that plugin can work with latest jQuery versions (like 3.4.1) just in strait way, without additional libraries. (8.4.7.23)' ) . '</li>'

						. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Checking for seasonal availability in "child booking resources" during submitting booking for booking resource with specific capacity. If you have set unavailable dates in child booking resource via season filters, system will not save bookings in this child booking resource. (8.4.7.3) *(Business Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Set as unavailable the end time fields options,  depend from  selected date with booked timeslots (8.4.7.6) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement**. Added autocomplete Off to the search form fields,  to  prevent of showing tooltips in search fields. (8.4.7.7) *(Business Large, MultiUser)*' ) . '</li>'
						. '</ul>'

						)
						, array( 'text' =>
						 '<h4>' .wpbc_replace_to_strong_symbols( 'Translation' ) . '</h4>'
						. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'

						. '<li>' . wpbc_replace_to_strong_symbols( 'New Romanian translation by Silviu Nita' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Update of Slovenian translation by Klemen Gaber' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Update of Dutch translation by Boris Hoekmeijer' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Update of German translation by Dominik Ziegler' ) . '</li>'
						. '</ul>'

						. '<h4>' .wpbc_replace_to_strong_symbols( 'Fixes' ) . '</h4>'
						. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Issue of not working "Read All" button (issue was exist  in updates 8.4.5, 8.4.6. (8.4.7.15)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Issue of incorrectly  showing months scroll in calendar at some iPads (8.4.7.17)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Issue of not showing bookings for "Today" date in Booking Listing page, when bookings was made for entire date. (8.4.7.21)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Issue of showing bookings,  that was made during "Today" date in Booking Listing page. Previously system  was show some bookings, that was made yesterday, as well. (8.4.7.22)' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Warnings in PHP 7.2 relative INI directive safe_mode is deprecated since PHP 5.3 and removed since PHP 5.4 (8.4.7.24)' ) . '</li>'

						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Warning: Invalid argument supplied for foreach() in ..\multiuser.php on line 558 (8.4.7.4) *(MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Showing of users in Booking > Settings > Users page in WordPress MU installation (8.4.7.5) *(MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Issue with Stripe payment,  when "Subject" have too long description with  dates to book. (8.4.7.10) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Translation  issue of Completed payment status (8.4.7.11) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Showing of showing dates instead of titles of booking resources in Timeline,  when  some Regular  user  was logged in and try  to  scroll timeline (8.4.7.13) *(MultiUser)*' ) . '</li>'

						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Showing Notice: Undefined offset: 9 in ../inc/_bl/wpbc-search-availability.php on line 689 (8.4.7.16) *(Business Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Issue of not updating cost by  making booking at  Booking > Add booking page, while using [cost_correction] shortcode in the booking form (8.4.7.28) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Fix**. Issue of not showing change over days in calendar for single booking resource (capacity = 1),  where maximum  number of visitors > 1 (8.4.7.29) *(Business Large, MultiUser)*' ) . '</li>'

						. '</ul>'
															  )
														)
													);

			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_4($obj){

			$section_param_arr = array( 'version_num' => '8.4', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );

				$obj->show_separator();
				//   <!--iframe width="560" height="315" src="https://www.youtube.com/embed/kLrI7zqKeQQ?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe-->
				?><div style="text-align: center;margin-top:2em;"><iframe width="560" height="315" src="https://www.youtube.com/embed/rpg1kApZCdw?rel=0&amp;start=0&amp;rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div><?php
				?><div style="width:100%;font-size:0.8em;margin:2em 1em;text-align: center;">
					<?php
					printf( 'For more information about current update, see %srelease notes%s',
							'<a class="" href="https://wpbookingcalendar.com/changelog/" target="_blank">', '</a>.' );
					?>
				</div><?php

			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_3($obj){

			$section_param_arr = array( 'version_num' => '8.3', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );

					$obj->show_separator();
		//							   <!--iframe width="560" height="315" src="https://www.youtube.com/embed/kLrI7zqKeQQ?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe-->
					?><div style="text-align: center;margin-top:2em;"><iframe width="560" height="315" src="https://www.youtube.com/embed/-pOTMiyp6Q8?rel=0&amp;start=0&amp;rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div><?php
					?><div style="width:100%;font-size:0.8em;margin:2em 1em;text-align: center;">
						<?php
						printf( 'For more information about current update, see %srelease notes%s',
								'<a class="" href="https://wpbookingcalendar.com/changelog/" target="_blank">', '</a>.' );
						?>
					</div><?php


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_2($obj){

			$section_param_arr = array( 'version_num' => '8.2', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );

					$obj->show_separator();
		//							   <!--iframe width="560" height="315" src="https://www.youtube.com/embed/kLrI7zqKeQQ?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe-->
					?><div style="text-align: center;margin-top:2em;"><iframe width="560" height="315" src="https://www.youtube.com/embed/videoseries?list=PLabuVtqCh9dzBEZCIqayAfvarrngZuqUl&amp;start=0&amp;rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div><?php

					?><div style="width:100%;font-size:0.8em;margin:2em 1em;text-align: center;">
						<?php
						printf( 'For more information about current update, see %srelease notes%s',
								'<a class="" href="https://wpbookingcalendar.com/changelog/" target="_blank">', '</a>.' );
						?>
					</div><?php


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_1($obj){

			$section_param_arr = array( 'version_num' => '8.1', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );


				$obj->show_col_section( array(

						array( 'h4'   => wpbc_replace_to_strong_symbols( 'Different structures of booking forms' ),
							   'text' => wpbc_replace_to_strong_symbols( '<ul style="list-style: none;padding: 5px;margin:0;">'
										. '<li>' . 'Ability to define different structures of booking forms at Booking > Settings > Form page' . '</li>'
										. '<li style="list-style: disc inside;">' . '**Vertical** - form under calendar' . '</li>'
										. '<li style="list-style: disc inside;">' . '**Side by side** - form at right side of calendar' . '</li>'
										. '<li style="list-style: disc inside;">' . '**Centered** - form and calendar are centered' . '</li>'
										. '<li style="list-style: disc inside;">' . '**Dark** - form for dark background' . '</li>'
									  . '</ul>' )

							   . '<span style="font-size:0.85em;">' .wpbc_replace_to_strong_symbols( 'Available in Booking Calendar **Free** version' ) . '</span>'

							 )
					, array(  'img'  => '8.1/booking-form-structure-2.png', 'img_style'=>'margin-top:20px;width: 85%;' )
					)
				);

				$obj->show_separator();

				$obj->show_col_section( array(

						array(  'img'  => '8.1/booking-calendar-stripe-gateway-2.png', 'img_style'=>'margin-top:20px;width: 85%;' )

						, array( 'h4'   => wpbc_replace_to_strong_symbols( '**Stripe** payment system integration' ),
							   'text' => wpbc_replace_to_strong_symbols( '<ul style="list-style: none;padding: 5px;margin:0;">'
										. '<li>' . 'Integration with **<a target="_blank" href="https://stripe.com/">Stripe</a>** payment gateway.' . '</li>'
										. '<li>' . 'Showing on screen (same page) payment form,  with ability to pay by cards.' . '</li>'
										. '<li>' . 'Ability to  auto approve or auto  decline booking,  after successful  or failed payment.' . '</li>'
									  . '</ul>' )

							   . '<span style="font-size:0.85em;">' .wpbc_replace_to_strong_symbols( 'Available in **Business Small / Business Medium / Business Large / MultiUser** versions' ) . '</span>'

							 )
					)
				);

				$obj->show_separator();


			$obj->show_col_section( array(
					array( 'text' =>
								 '<h4>' .wpbc_replace_to_strong_symbols( 'New' ) . '</h4>'
								. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
								. '<li>' . wpbc_replace_to_strong_symbols( '**New** Ability to insert modification/creation date or (Year, month, day, hours,  minutes or seconds) of booking into email templates or in payment summary' ) . '</li>'
								 . '<li>' . wpbc_replace_to_strong_symbols( '**New.** Shortcode for showing check out date plus one additional day: <code>[check_out_plus1day_hint]</code> at Booking > Settings > Form page. (8.0.2.12) *(Business Medium/Large, MultiUser)*' ) . '</li>'
								. '</ul>'
								. '<h4>' .wpbc_replace_to_strong_symbols( 'Translation' ) . '</h4>'
								. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
								. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Spanish translation [100% completed] by Martin Romero' ) . '</li>'
								. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Galician (Spanish) translation [100% completed] by Martin Romero' ) . '</li>'
								. '</ul>'

								. '<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
								. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
								. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Improve admin UI styles in Chrome browser, by setting more sleek view of UI elements (8.0.2.4/5)' ) . '</li>'
								. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** Do not export to .ics feed bookings, that inside of Trash folder (8.0.2.7)' ) . '</li>'
								. '</ul>'
					)
					, array( 'text' =>

								 '<h4>' .wpbc_replace_to_strong_symbols( 'Fixes' ) . '</h4>'
							   . '<ul style="list-style: disc outside;padding: 20px;margin:0;">'

							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** showing booking listing correctly  for "next  1 month" without bookings,  that  include past ("yesterday day") bookings (8.0.1.1)' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** force to load jquery-migrate in case, if we do  not know the version  of jQuery which  was loaded. (8.0.1.2)' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of showing warning "parsererror ~ SyntaxError: JSON.parse: unexpected character at line 1 column 1 of the JSON data" during import process,  when  some bookings already  was imported (8.0.2.1)' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** add support of Apache 2.4 directives relative captcha saving.' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of showing warning: "Email different from website DNS, its can be a reason of not delivery emails" at Booking > Settings > Emails page, in case if website DNS starting with "www." ot some other sub-domain. (8.0.2.9)' ) . '</li>'

							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** showing correctly  change-over days (triangles),  when  inserted only "availability calendar", without booking form (8.0.1.2) *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** ability to use symbol **/** in placeholders in booking form fields shortcodes at Settings Form page (8.0.1.13) *(Personal Business Small/Medium/Large, MultiUser)*' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** correctly showing single and double quotes (\' and ") symbols in textarea during editing booking (8.0.1.3) *(Personal Business Small/Medium/Large, MultiUser)*' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of not saving changes during editing, if you try to search some booking resource (or other item), and this booking resource was not at the 1st  page (during usual listing)  (8.0.1.12) *(Personal Business Small/Medium/Large, MultiUser)*' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of incorrect  cost calculation, during editing booking,  when selected days from 1 to 9 and used some rates. Issue relative of not using leading 0 in textarea. (8.0.2.2) *(Business Medium/Large, MultiUser)*' ) . '</li>'
							   . '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of showing coupon discount description,  does not depend from uppercase or lowercase of entered coupon code (8.0.2.7) *(Business Large, MultiUser)*' ) . '</li>'
							   . '</ul>'
					)
				)
			);


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_8_0($obj){

			$section_param_arr = array( 'version_num' => '8.0', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );

				?><h2 style='font-size: 1.6em;margin:20px 0 -10px 0;'>Sync bookings between different sources easily via <strong>.ics</strong> feeds</h2><?php

				$obj->show_col_section( array(

									  array( 'h4'   => wpbc_replace_to_strong_symbols( 'Import of **.ics** feeds (files)' ),
											 'text' =>  '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Native integration  with our **<a target="_blank" href="https://wpbookingcalendar.com/faq/booking-manager/">Booking Manager</a>** plugin for ability to import **.ics** feeds or files' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Import external **.ics** feeds via shortcodes at pages. 
								It gives a great flexibility to import **.ics** feeds from different sources <em>(like ' )
							. '<strong><a href="https://www.airbnb.com/help/article/99/how-do-i-sync-my-airbnb-calendar-with-another-calendar" target="_blank">Airbnb</a></strong>, '
							. '<strong><a href="https://partnersupport.booking.com/hc/en-us/articles/213424709-How-do-I-export-my-calendar-" target="_blank">Booking.com</a></strong>, '
							. '<strong><a href="https://help.homeaway.com/articles/How-do-I-export-my-calendar-data-to-a-Google-calendar" target="_blank">HomeAway</a></strong>, '
							. '<strong><a href="https://rentalsupport.tripadvisor.com/articles/FAQ/noc-How-does-calendar-sync-work" target="_blank">TripAdvisor</a></strong>, '
							. '<strong><a href="https://help.vrbo.com/articles/How-do-I-export-my-calendar-data-to-a-Google-calendar" target="_blank">VRBO</a></strong>, '
							. '<strong><a href="https://helpcenter.flipkey.com/articles/FAQ/noc-How-does-calendar-sync-work" target="_blank">FlipKey</a></strong> '
							. ' or any other calendar that uses .ics format)</em> '
							. ' into same booking resource.'
							. ' <br>Its means that  you can import bookings or events from different sources into same resource.'
										  . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Define different parameters in this import shortcode. For example, you can  set "start from" and "finish to" date condition or maximum number of items to import or import only events for available dates in exist calendar,	etc...' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Additionally you can configure your server CRON for periodically access these pages and import .ics feeds in automatic way.' ) . '</li>'

														. '</ul>'
										  )
									, array(  'img'  => '8.0/import-ics2.png', 'img_style'=>'margin-top:20px;width: 99%;' )
									)
								);

				$obj->show_separator();

				$obj->show_col_section( array(
									  array(  'img'  => '8.0/export-ics.png', 'img_style'=>'margin-top:20px;width: 99%;' )
									  , array( 'h4'   => wpbc_replace_to_strong_symbols( 'Export of **.ics** feeds (files)' ),
											 'text' =>
														 '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Native integration  with our **<a target="_blank" href="https://wpbookingcalendar.com/faq/booking-manager/">Booking Manager</a>** plugin for ability to export **.ics** feeds' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Configure specific ULR feed(s) at setting page' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Use this URL(s) in external websites <em>(like ' )
							. '<strong><a href="https://www.airbnb.com/help/article/99/how-do-i-sync-my-airbnb-calendar-with-another-calendar" target="_blank">Airbnb</a></strong>, '
							. '<strong><a href="https://partnersupport.booking.com/hc/en-us/articles/213424709-How-do-I-export-my-calendar-" target="_blank">Booking.com</a></strong>, '
							. '<strong><a href="https://help.homeaway.com/articles/How-do-I-export-my-calendar-data-to-a-Google-calendar" target="_blank">HomeAway</a></strong>, '
							. '<strong><a href="https://rentalsupport.tripadvisor.com/articles/FAQ/noc-How-does-calendar-sync-work" target="_blank">TripAdvisor</a></strong>, '
							. '<strong><a href="https://help.vrbo.com/articles/How-do-I-export-my-calendar-data-to-a-Google-calendar" target="_blank">VRBO</a></strong>, '
							. '<strong><a href="https://helpcenter.flipkey.com/articles/FAQ/noc-How-does-calendar-sync-work" target="_blank">FlipKey</a></strong> '
							. ' or any other calendar that uses .ics format)</em> '
							. ' for ability to  import bookings into  these third-party  websites.'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Or you can simply  download .ics file for later  use in some application.' ) . '</li>'
										  . '</li>'
														. '</ul>'
										  )
									)
								);
				$obj->show_separator();


				$obj->show_col_section( array(
									array( 'text' =>
	 '<h4>' .wpbc_replace_to_strong_symbols( 'Translation' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'

	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Dutch translation [100% completed] by Boris Hoekmeijer and Alex Rabayev and Iris Schuster' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Finnish translation [98% completed] by Teemu Valkeapää' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Chinese (Taiwan) translation [98% completed] by Howdy Lee' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Norwegian translation [98% completed] by Bjørn Kåre Løland' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Brazilian Portuguese translation [98% completed] by Rafael Rocha' ) . '</li>'
	. '</ul>'

	. '<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** New setting option for activation showing system  debug log,  for Beta features. Useful in case, if need to find reason, if something was going wrong. You can activate it at the Booking > Settings General page in Advanced section after clicking on "Show advanced settings of JavaScript loading." ( 7.2.1.15 )' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Showing system  messages one under other instead of replacing each other  in admin panel. Its possible to hide top one and see previous notices (7.2.1.16)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Show in "New (visitor)" email (that is sending to the visitor after new booking) the date that is one day  previous to  the last  selected day,  by using this shortcode: <code>[check_out_minus1day]</code> (7.2.1.6)' ) . '</li>'

	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Shortcode for showing coupon discount value of the booking: <code>[coupon_discount_hint]</code> at Booking > Settings > Form page. **(Business Large, MultiUser)**' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Discount coupon codes will not depend from symbols lowercase or uppercase. Prevent of saving coupon codes with specific symbols, which can generate issue of not showing discount at payment form.  (7.2.1.3) **(Business Large, MultiUser)**' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Show "blank" bookings with  different border color at Calendar Overview page. (7.2.1.8) **(Personal Business Small/Medium/Large, MultiUser)**' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Apply "Valuation days" cost  settings "For = LAST",  even if previous TOGATHER = X% settings was applied. (7.2.1.20) **(Business Medium/Large, MultiUser)**' ) . '</li>'
	. '</ul>'

	)
	, array( 'text' =>
	  '<h4>' .wpbc_replace_to_strong_symbols( 'Under Hood' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood.** New **API File** <code>/{Booking Calendar Folder}/core/wpbc-dev-api.php</code> - well documented list of functions and hooks that possible to use for third-party integrations.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood.** New column in booking resources table for saving export info (7.2.1.13) **(Personal Business Small/Medium/Large, MultiUser)**' ) . '</li>'
	. '</ul>'

	. '<h4>' .wpbc_replace_to_strong_symbols( 'Fixes' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Correctly load custom jQuery via https (in some servers), if website is using SSL ( 7.2.1.4 )' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Compatibility issue with  other plugins,  during expand/collapsing sections at  settings pages (7.2.1.10)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Additional  checking about $_SERVER variables, for preventing of showing "Warning Notices" at  some servers ( 7.2.1.17 )' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Loading correct language, if language was set to English in user profile but in WordPress > General > Settings page was set some other default language ( 7.2.1.21 )' ) . '</li>'

	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Issue of not showing search results (during searching in same page - ajax request), when  using custom fields parameters and selected - "" (which is means "any value") ( 7.2.1.5 ) **(Business Large, MultiUser)**' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Issue of showing correct  number of decimal digits depend from  cost  format,  in calendar days cells and mouse-over tooltips ( 7.2.1.11) **(Business Medium/Large, MultiUser)**' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Do not check about required fields, if the fields are hidden (7.2.1.12) **(Personal Business Small/Medium/Large, MultiUser)**' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Issue of not showing links for booking resources in timeline after scrolling, if using (resource_link) parameter with links in timeline shortcode. (7.2.1.14) **(Personal Business Small/Medium/Large, MultiUser)**' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** "Request-URI Too Long" fatal error at "Calendar Overview" page,  when visitor have too many  booking resources (7.2.1.18) **(Personal Business Small/Medium/Large, MultiUser)**' ) . '</li>'

	. '</ul>'
										  )
									)
								);


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

		function wpbc_welcome_section_7_1_7_2($obj){

			$section_param_arr = array( 'version_num' => '7.1 - 7.2', 'show_expand' => true );
			$obj->expand_section_start( $section_param_arr );

				?><h2 style='font-size: 1.6em;margin:40px 0 0 0;text-align: left;'>Changes in all versions</h2><?php

				$obj->show_col_section( array(

									  array( 'h4'   => wpbc_replace_to_strong_symbols( 'Fast Adding bookings to Google Calendar' ),
											 'text' =>  '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Manual **export to Google Calendar** of specific booking by clicking on **Export** button near booking at Booking Listing page' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Shortcode <code>[add_to_google_cal_url]</code> in email template (to admin) for fast manual (adding) of booking to Google Calendar' ) . '</li>'
														. '</ul>'
										  )
									, array(  'img'  => '7.2/add-to-google-calendar.png', 'img_style'=>'margin-top:20px;width: 99%;' )
									)
								);
		$obj->show_separator();
				$obj->show_col_section( array(
									  array(  'img'  => '7.2/timeline-hours-limit.png', 'img_style'=>'margin-top:20px;width: 99%;' )
									  , array( 'h4'   => wpbc_replace_to_strong_symbols( '**Timeline** tricks' ),
											 'text' =>
														 '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Limit times for showing cells in TimeLine for 1 day view mode. <br>For Example: <code>[bookingtimeline type=\'1\' limit_hours=\'9,22\']</code>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Constant **WP_BK_TIMILINE_LIMIT_HOURS** in wpbc-constants.php file. Limit times for showing cells in Calendar Overview page in admin panel for 1 day view mode. ' ) . '</li>'
														. '</ul>'
										  )
									)
								);

		$obj->show_separator();

				$obj->show_col_section( array(
									array( 'text' =>
	 '<h4>' .wpbc_replace_to_strong_symbols( 'Translation' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'

	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Danish translation [100% completed] by Daniel Moesgaard' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Swedish translation [100% completed] by Mikael Göransson' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation.** Italian translation [100% completed]' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Hebrew translation [100% completed] by Alex Rabayev and Iris Schuster' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation** Arabic translation [84% completed]' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation.** Dutch translation [99% completed] ' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation.** German translation [99% completed]' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Translation.** French translation [99% completed]' ) . '</li>'
	. '</ul>'

	. '<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement** remove today day highlighting in calendar, after loading of page (7.1.2.8)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** additional checking of correct loading popover function to  prevent JavaScript error. If visitor disable loading of Bootstrap files or because of some JS conflict,  instead of showing JavaScript error system  will skip showing popover tooltip when mouse over days in calendar,  or when click on booking in timeline. (7.0.1.2)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** added checking about minimum required version of WordPress for using Booking Calendar  (7.0.1.6)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Ability to  use <code>[reason]</code> or <code>[approvereason]</code> in Booking > Settings > Emails > Approve email template.' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Prepare functionality for removing language folder from plugin in a future, for reducing size of plugin. (7.0.1.53)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Showing popovers in timeline (calendar  overview) only at  bottom  direction for better looking at mobile devices (7.0.1.42)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Set color of placeholder text in settings fields lighter. (7.0.1.54)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Improvement.** Increase time for script execution during initial activation  of plugin. (7.0.1.57)' ) . '</li>'
	. '</ul>'

	. '<h4>' .wpbc_replace_to_strong_symbols( 'Under Hood' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood.** do_action( \'wpbc_jquery_unknown\' )  - new hook  for situation,  when we can not make identification version of jQuery,  sometimes,  need manually to  load  jquery-migrate (7.0.1.33)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood.** Trigger event "timeline_nav" after clicking navigation  in timeline. To bind this event use this JS: <code>jQuery( ".wpbc_timeline_front_end" ).on(\'timeline_nav\', function(event, timeline_obj, nav_step ) { ... } );</code> (7.0.1.48)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Under Hood.** New constant. <code>WP_BK_AUTO_APPROVE_WHEN_IMPORT_GCAL</code> - Auto  approve booking,  if imported from Google Calendar. Default set to false (7.0.1.59)' ) . '</li>'
	. '</ul>'
	)
	, array( 'text' =>
	'<h4>' .wpbc_replace_to_strong_symbols( 'Fixes' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** some translation issue (7.1.2.1)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of showing today bookings in Booking Listing page (7.1.2.8)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Renamed Greek translation files from booking-el_GR.mo to booking-el.mo (booking-el_GR.po to booking-el.po) Its seems that  default locale for Greek  is \'el\' (7.1.2.10)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** add possibility to check  and load file for \'short\' translation locale (like \'en\'), if file for \'long\' locale (like \'en_US\') was not found in translation folder. (7.1.2.11)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Update captcha 1.1 to captcha 1.9,  which protect from potensional PHP file inclusion vulnerability (7.0.1.67)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Minimum version of jQuery required as 1.9.1' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Issue of disabling sending emails during approving or cancellation of bookings at  Booking Listing or Calendar Overview pages,  when checkbox "Emails sending" unchecked. (7.0.1.5)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Issue of **auto import events** from Google Calendar into the Booking Calendar (7.0.1.9)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** Issue of generating **JavaScript errors** when  user  deactivated loading of Bootstrap JS files at Booking Settings General page in Advanced section. Instead of it show warning message or skip showing tooltips. (7.0.1.10)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of order loading translation,  if default language is not English  (7.0.1.12)    ' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of redirection  to "Thank  you" page. Using home_url (www.server.com) instead of site_url (www.server.com/wordpress/) at some servers. (7.0.1.20)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of ability to translate options in selectbox in format like <code>Eng 1 [lang=it_IT] Italian 1</code> at Settings Fields page in Booking Calendar Free version  (7.0.1.21)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** set email field as required field in Booking Calendar Free version  (7.0.1.22)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of not sending emails, if server was set with using error_reporting(E_STRICT); and show this warning: "PHP Strict Standards: Only variables should be passed by reference in /wp-content/plugins/booking/core/admin/page-email-new-admin.php on line 1105"  (7.0.1.32)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of not submitting booking in IE. Issue relative to  note support by IE String.trim() function. (7.0.1.39)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of showing additional slashes in emails at reason of cancellation (7.0.1.46) (Also  fixed same issue for approve reason, payment request  text and adding notes to the booking).' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of showing in TimeLine (Calendar Overview) 1st day  of next Month, that  does not belong to current visible month. Sometimes in such  view if booking starting from 1st day of next month, system does not show this booking, and its can confuse visitors. (7.0.1.47)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( '**Fix** issue of not saving Booking > Settings General page if pressed on Save Changes button  at top right side in French language,  and some other languages (7.0.1.56)' ) . '</li>'
	. '</ul>'



										  )
									)
								);




			?><h2 style='font-size: 1.6em;margin:40px 0 0 0;text-align: left;'><?php echo wpbc_replace_to_strong_symbols( 'Changes in **Personal / Business Small / Business Medium / Business Large / MultiUser** versions' ); ?></h2><br/><?php

			$obj->show_separator();

				$obj->show_col_section( array(

									  array( 'h4'   => wpbc_replace_to_strong_symbols( 'iDEAL payment gateway' ),
											 'text' =>  '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( 'Integration of **iDEAL via Sisow** payment gateway. (7.0.1.64) <em>(Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
														. '</ul>'
										  )
									, array(  'img'  => '7.2/ideal-settings.png', 'img_style'=>'margin-top:20px;width: 99%;' )
									)
								);
		$obj->show_separator();
				$obj->show_col_section( array(
									  array(  'img'  => '7.2/change-over-days.png', 'img_style'=>'margin-top:20px;width: 99%;' )
									  , array( 'h4'   => wpbc_replace_to_strong_symbols( '**Change over days as triangles**' ),
											 'text' =>
														 '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **New.** **Show change over days as triangles.** <em>Beta Feature</em>. Its require showing calendar days cells as square (not rectangle). Width and height of calendar you can define in shortcode options parameter. Supported by: Chrome 36.0+, MS IE 10.0+, Firefox 16.0+, Safari 9.0+, Opera 23.0+ (7.0.1.24) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'                                                    . '</ul>'
										  )
									)
								);
		$obj->show_separator();

				$obj->show_col_section( array(
									array( 'text' =>

	'<h4>' .wpbc_replace_to_strong_symbols( 'Improvement' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement** New form template with  30 minutes time-slots selection at Booking > Settings > Form page (7.1.2.6) <em>(Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement** Ability to  add empty parameter "&booking_hash" to URL in browser at  Booking > Add booking page for ability to add bookings for past  days (7.1.2.10) <em>(Personal Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement** Ability to use "Valuation days" cost  settings, if activated "Use time selections as recurrent time slots" and set  cost "per 1 day" and option "Time impact to cost" deactivated at  Booking > Settings > Payment page. Useful, when  need to set cost  per days, but also save time-slots during booking on several days. (7.1.2.11) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'

	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement.** Ability to set lower interval (15, 30 or 45 minutes) for auto cancellation pending bookings that have no successfully paid status (7.0.1.25)  <em>(Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement.** Ability to  use aggregate parameter  in the <code>[bookingedit]</code> shortcode  (7.0.1.26) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement.** Ability to  use in field "From Name" in email templates at Booking - Settings - Emails page different shortcodes from booking form,  like <code>[name] [secondname]</code> (7.0.1.29) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement.** Ability to  show in cost_hints negative (discounted) cost for additional items. Previously  system  set instead of negative value just 0 (7.0.1.30) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement.** Increase accuracy of rates calculation, if we are having more than 2 digits after comma in rates configurations  (7.0.1.44) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement.** Ability to use HTML tags in popup window during sending payment request and then showing <code>[paymentreason]</code> in email template with  HTML formating (7.0.1.60) <em>(Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement.** Showing "blank  bookings" in Calendar Overview page with  different color (red) (7.0.1.40) <em>(Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Improvement.** Showing all title for booking resources with long name (longer than 19 symbols) at the Booking Listing page. Previously  its was cutted of (7.0.1.66) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'


	. '</ul>'

	. '<h4>' .wpbc_replace_to_strong_symbols( 'Under Hood' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **New** Constant WP_BK_CHECK_IF_CUSTOM_PARAM_IN_SEARCH in wpbc-constants.php file. Check in search  results custom fields parameter that  can  include to  multiple selected options in search  form.  Logical OR (7.1.2.9) <em>(Business Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Trick** Using in Booking > Resources page parameter "show_all_resources" in browser URL, will  show all booking resources,  even lost booking resources. Lost  booking resources can be, if you was assigned as parent booking resource to single booking resource,  itself. (7.1.2.2) <em>(Business Large, MultiUser)</em>' ) . '</li>'

	. '<li>' . wpbc_replace_to_strong_symbols( ' **New.** Ability to define links for booking resource titles in TimeLine. Example: <code>[bookingtimeline  ... options=\'{resource_link 3="http://beta/resource-apartment3-id3/"},{resource_link 4="http://beta/resource-3-id4/"}\' ... ]</code> (7.0.1.50) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Tip.** Skip showing rows of booking resource(s) in TimeLine or Calendar Overview, if no any exist booking(s) for current view. For activation this feature you need to add only_booked_resources parameter to the URL. For example: http://server.com/wp-admin/admin.php?page=wpbc&view_mode=vm_calendar&only_booked_resources  Its have to  improve speed of page loading,  when  we are having too many resources at  the page. (7.0.1.51) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'

	. '<li>' . wpbc_replace_to_strong_symbols( ' **Under Hood.** Trigger event "show_cost_hints" after showing cost or time hints in booking form. To bind this event use this JS: jQuery( ".booking_form_div" ).on(\'show_cost_hints\', function(event, bk_type ) { ... } ); (7.0.1.53) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Under Hood.** Add automatically new payment system, after visit Settings Payment page, if payment system folder and file(s) was created correctly. (7.0.1.55,7.0.1.61) <em>(Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'

	. '</ul>'
	)
	, array( 'text' =>
	'<h4>' .wpbc_replace_to_strong_symbols( 'Fixes' ) . '</h4>'
	. '<ul style="list-style: disc outside;padding: 20px;margin:0;">'

	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** do not show option for ability to select as parent booking resource itself, at Booking > Resources page. Its prevent from generating lost booking resources. (7.1.2.3) <em>(Business Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of not having access in modal  windows (like payment request) to enter some data,  when opened page with  mobile device (7.1.2.7) <em>(Personal Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue in Danish translation,  which  was show warning at Booking > Settings > Payment > Bank transfer page (7.1.2.9) <em>(Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of showing &amp;#36, instead of $ symbol in the Booking Listing,  if was used in "Content of booking fields data" form HINT cost shortcodes (7.1.2.12) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of hiding selection of booking resources field after submit of booking (7.1.2.13) <em>(Personal Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of not checking (during booking submit process) elements from  conditional fields logic, if these fields does not visible. (7.1.2.14) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'

	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of not showing "reason of cancellation" in emails, that are sending after auto-cancellation of pending not successfully paid bookings. (7.0.1.1) <em>(Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of incorrectly  booking cost calculation if setted cost  "per 1 night" and previously was used "Valuation days" cost  settings for specific booking resource. (7.0.1.4) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** Do not apply "LAST" cost option for "Valuation days" if previously  was applied "Together" term. No need to  apply "LAST", because its have to be already calculated in together term (7.0.1.7) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** Correctly replacing shortcodes with custom URL parameter, like: \'visitorbookingediturl\', \'visitorbookingcancelurl\', \'visitorbookingpayurl\' in email templates. (7.0.1.8) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of showing notice: "Use of undefined constant <code>WPDEV_BK_LOCALE_RELOAD</code>" in seacrh  results  (7.0.1.9) <em>(Business Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of start showing timeline in "Day view" starting from Today date based on WordPress timezone. (7.0.1.13)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of not showing some bookings,  which was made for specific times in 1 day view mode. (7.0.1.16)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of saving additional  cost  at the Booking > Resources > Advanced cost page,  if some options have LABELs (options still  must  be simple words) with  umlauts. (7.0.1.27) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of updating <code>[cost_correction]</code> shortcode, if selecting dates for new booking and not editing exist booking (7.0.1.28) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of blocking days selection in calendar, when visitor use the search form and manually input dates that lower than minimum number of days selection in settings (7.0.1.31) <em>(Business Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of showing blank page for printing in Chrome browser (7.0.1.34) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of not changing hash of booking after approving of booking,  if this option was activated at settings (7.0.1.35) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of rechecking booking dates (if activated "Checking to prevent double booking, during submitting booking" option), during booking editing (7.0.1.36) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of not correctly blocking check-out day (showing weird 2 checkout days), if activated "Unavailable time before / after booking" option and set unavailable DAYs after booking (7.0.1.38) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of wrong deleting booking,  if activated option "Disable bookings in different booking resources" during editing booking that  try  to  store in different booking resources (7.0.1.43) <em>(Business Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** position of currency symbol in calendar day cells and in mouseover tooltip,  depend from  settings at  Booking > Settings > Payment page  (7.0.1.49) <em>(Business Medium/Large, MultiUser)</em>' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** replacinng shortcodes in a loop, if we are having several shortcodes with  bookingedit{cancel} in email templates (For example, if we have several  languges ). (7.0.1.52)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of infinite loop,  which  was exist  since update 7.0.1.52 to 7.0.1.57 (7.0.1.58)' ) . '</li>'
	. '<li>' . wpbc_replace_to_strong_symbols( ' **Fix** issue of not saving data for radio button selection field in emails and may be in booking listing (7.0.1.62) <em>(Personal, Business Small/Medium/Large, MultiUser)</em>' ) . '</li>'

	. '</ul>'
										  )
									)
								);


			$obj->show_separator();
			$obj->expand_section_end( $section_param_arr );
		}

