<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


function wpbc_welcome_section_10_0( $obj ){

	$section_param_arr = array( 'version_num' => '10.0', 'show_expand' => false );

	$obj->expand_section_start( $section_param_arr );


	//$obj->asset_path = 'http://beta/assets/';	// TODO: 2023-11-06 comment this


	// <editor-fold     defaultstate="collapsed"                        desc=" = F R E E = "  >
	// -----------------------------------------------------------------------------------------------------------------
	//  = F R E E =
	// -----------------------------------------------------------------------------------------------------------------
	?><div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Explore Simplified Integration and Enhanced Management' ); ?></h2>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Easy Shortcode Integration' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( 'Discover the simplicity of integrating Booking Calendar shortcodes into your pages. Our updated UI for the configuration popup dialog simplifies the process of inserting booking forms, availability calendars, and other Booking Calendar shortcodes. Configuring all parameters is now super easy with our new structured wizard-style configuration dialog. Add the Booking Calendar to your page by using Booking Calendar Blocks in the WP Block Editor or by clicking on the Booking Calendar icon in the WP Classic Editor.' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->section_img_url( '10.0/wp_booking_calendar_insert_shortcode_01.png' ); ?>" style="margin-top:55px" />
			</div>
		</div>
		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
	</div>
	<?php

	?><div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Unavailable Dates legend item' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( 'Easily configure **Unavailable Dates legend item** under the calendar at WP Booking Calendar > Settings General page in the "Calendar" section.' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->section_img_url( '10.0/wp_booking_calendar_unavailable_legend_01.png' ); ?>" style="margin-top:0px" />
			</div>
		</div>
		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
	</div>
	<?php

	// </editor-fold>


	// -----------------------------------------------------------------------------------------------------------------
	//  = P A I D =
	// -----------------------------------------------------------------------------------------------------------------
	?><div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Changes in Premium Versions' ); ?></h2>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Discover an enhanced user interface for the Resources page' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**New** **UI Design of Resource Page**. Easily toggle between table header tabs to view specific information, such as the shortcode for embedding into pages, costs, default form, or parent/child relation for defining booking resource capacity. The interface is now clearer and more straightforward for a seamless experience *(Personal, Business Small/Medium/Large, MultiUser)*.' ) . '</li>'
					. '</ul>';
				?>
				<img src="<?php echo $obj->asset_path; ?>10.0/wp_booking_calendar_resources_page_03.gif" />
				<?php
				echo '<ul>'
					  	. '<li>' . wpbc_replace_to_strong_symbols( '&bull; Added **search booking resources** field to Options toolbar for easy searching by ID or Title keywords (9.9.0.11) *(Personal, Business Small/Medium/Large, MultiUser)*.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '&bull; You now have the ability to **save the number of booking resources per page** directly from the Booking > Resources page (at pagination section). (9.9.0.22) *(Personal, Business Small/Medium/Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Explore the super ease of configuring "Conditional Days Selection"' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '&bull; Set the **number of days** for selection based **on a specific weekday**.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '&bull; Determine the **number of days** for selection **in a specific season**.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '&bull; Define the **number of days** for selection **starting from a specific date**.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '&bull; Choose **weekdays as start days** for selection **during a specific season**.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '*(Business Medium/Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
				<img src="<?php echo $obj->asset_path; ?>10.0/wp_booking_calendar_days_selection_04_2000px.gif" />
			</div>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( '**New shortcode** for **displaying taxes or additional fees based on the total booking cost**.' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'This **[total_cost]** shortcode  is used for displaying additional cost hints without affecting cost calculations. Since the total cost has already been calculated at this stage.' ) . ' '
						 		 . wpbc_replace_to_strong_symbols( 'For example, if you want to **show the tax cost as 20% inclusive part of the total booking cost**, set up your booking form fields like this: ' ) //. '</li>'
						. '<code>&lt;div style="display:none;"&gt; [checkbox tax_fee default:on ""]&lt;/div&gt; Tax: [tax_fee_hint]</code></li>'
						//. '<li>' . wpbc_replace_to_strong_symbols( 'The first shortcode, [checkbox tax_fee default:on ""], is crucial for cost calculation, while the second shortcode, [tax_fee_hint], reveals the cost hint.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Now, define the additional cost for tax_fee at WP Booking Calendar > Prices > Form Options Costs page: ' ) . '<code>tax_fee = ( [total_cost] * 0.2 )</code></li>'
						. '<li>' . wpbc_replace_to_strong_symbols( ' *(Business Medium/Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Track Changes: **Booking Edit Notification**' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '&bull; Whenever a booking is edited, a note stating **\'The booking has been edited\'** will be generated for the booking. This note also contains the URL of the page where the user made the modifications. With this feature, you gain control over tracking changes to booking details.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '&bull; Now, booking notes such as "Imported from Google Calendar," "Payment section displayed," "Total cost manually entered," "Automatically calculated cost," etc., will be **saved only if the "Logging actions for booking" option is activated** in the WP Booking Calendar > Settings General page in the "Admin Panel" section.' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>


	</div><?php



	// <editor-fold     defaultstate="collapsed"                        desc="  = M I X E D = "  >
	// -----------------------------------------------------------------------------------------------------------------
	//  = M I X E D =
	// -----------------------------------------------------------------------------------------------------------------
	?><hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
	<div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Additional Improvements in Free and Pro versions' ); ?></h2>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Improvements in Pro Versions' ); ?></h3>
				<ul>

					<?php // Pro ?>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Sage/Opayo gateway becoming a payment product under the Elavon brand. Update name of payment gateway from SagePay to **Opayo - Elavon**. For more information, visit: https://www.elavon.co.uk/resource-center/news-and-insights/opayo-migration-faqs.html  (9.9.0.34) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Enhanced **Booking Management** - When editing or duplicating a booking in the admin panel, you will now be redirected to the Booking Listing page, where the specific booking will be displayed. (9.9.0.3) (9.9.0.38) *(Personal, Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Enhanced **Shortcode Functionality** - Resolve the "403 permission error" issue encountered when modifying booking forms on the WP Booking Calendar > Settings > Booking Form page by utilizing the **new shortcode [selectbox name "Option 1" "Option 2"]** instead of [select name "Option 1" "Option 2"]. This update addresses the problem faced on certain Apache servers with ModSecurity firewall, which affects specific POST requests when saving booking form configurations. *(Personal, Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Enhanced **Deposit Management** - Now, when a booking is made in the admin panel at WP Booking Calendar > Add booking page, the deposit amount in the cost field and notes section, as well as the total cost for the booking, will be updated accordingly. This feature requires displaying payment forms at the Booking > Add booking page in such cases. (9.9.0.21) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Enhanced **Import Functionality** - You can now utilize the option \'Trash / delete all imported bookings before new import\' in situations where imports are initiated via the Google Calendar API instead of .ics feeds. This feature provides greater control over managing imported bookings and ensures a smoother import process. Please note that this feature requires the installation of the **Booking Manager** plugin.  (9.9.0.25)' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Enhanced **Payment Confirmation** - You now have the ability to **use the [booking_confirm] shortcode on the "Successful payment" page**, where users are redirected after making a payment via the Stripe or PayPal payment system. This allows you to configure the page to display a message such as \'Your payment for the booking has been successfully received. [booking_confirm]\', providing users with confirmation of their payment along with booking details. (9.9.0.37) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>

				</ul>
			</div>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Improvements in Free version' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Ability to dismiss the booking statistic section at Booking > Settings General page in "Info / News" for improved admin panel loading speed. (9.9.0.8).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Enhanced **Performance** - To improve the speed of page loading in the admin panel, statistic functions will now run only on the Dashboard and Booking Calendar settings pages. This optimization ensures a smoother user experience by focusing resource-intensive tasks only where necessary. (9.9.0.40)' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Prevent showing real preview in Elementor to reduce potential conflicts; instead, show \'WP Booking Calendar Shortcode block\' (9.9.0.39).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Prevent execution of Booking Calendar shortcodes while editing posts/pages, reducing potential conflicts (9.9.0.39).' ); ?></li>
				</ul>

				<div style="clear:both;height:20px;"></div>
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Under Hood Changes' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Customize showing booked time slots as inactive slots with a red background after selecting specific dates. Configure this in the ../{Booking Calendar Folder}/js/wpbc_time-selector.js file  by searching //FixIn: 9.9.0.2 (9.9.0.2).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Update the URLs for send transactions for Opayo - Elavon (former SagePay) to an Elavon domain on 31st March 2024. For more details, please refer to the following link: https://developer.elavon.com/products/opayo-forms/v1/test-and-live-urls-4633 (9.9.0.34) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Option to **Hide Payment Form After Visitor Edits Own Booking** - You now have the ability to define not to show the payment form after a visitor edits their own booking. This can be achieved by using the shortcode **[visitorbookingediturl]&is_show_payment_form=Off** in the New (visitor) email template at the WP Booking Calendar > Settings > Emails page. (9.9.0.35) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
				</ul>

				<div style="clear:both;height:20px;"></div>
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Translations' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Hungarian Translation Update**. Translation has been updated, reaching 96% completion, courtesy of VinczeI.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **German Translation Update**. Translation has been updated, reaching 96% completion, courtesy of Wibias.' ); ?></li>
				</ul>

			</div>
		</div>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Bug Fixes' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Resolved possible PHP 8 incompatibility issue (9.9.0.4).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Resolved issue of showing top messages (9.9.0.10).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Corrected the issue of incorrectly showing today\'s date as an unavailable date in the calendar on servers with a timezone different from UTC, especially in American timezones (9.9.0.17).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Addressed the issue of incorrectly showing bookings in Calendar Overview and Timeline when the timezone was incorrectly defined by themes (in functions.php file) or other plugins via incorrectly defined the timezone via this PHP functions: like date_default_timezone_set(\'America/Chicago\'); Even it is not correct way of defining timezone, relative to https://make.wordpress.org/core/2019/09/23/date-time-improvements-wp-5-3/ . Now plugin correctly resolve this issue. (9.9.0.18).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Re-created the "Thank you" confirmation page (such  as https://server.com/wpbc-booking-received/) if the confirmation booking page was previously defined as follow: https://server.com/thank-you/ (9.9.0.27).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Resolved fatal error:  Uncaught Error: Cannot access offset of type string on string in ../wpbc-class-timeline_v2.php on line 3100 (9.9.0.30).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Search Availability** Functionality Issue - An issue has been resolved in the Search Availability functionality. Previously, it was not possible to find pages with booking form shortcodes when the booking resource was defined using the \'resource_id\' parameter instead of \'type\'. This fix ensures that the search functionality now works as expected, allowing users to locate pages with booking forms accurately. (9.9.0.26) *(Business Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Correct Sorting Order for Booking Resources** - An issue has been addressed where the sorting order for booking resources in select-boxes was not defined correctly during the change of booking resource for a specific booking. This fix ensures that booking resources are displayed in the correct order. (9.9.0.23) *(Personal, Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Correction of Start Day Definition** - An issue has been **resolved where Sunday was incorrectly defined as the start day for selection** in specific seasons when using conditions in the shortcode. For example, when using a shortcode like [booking resource_id=1 options=\'{start-day condition="season" for="myHighSeason" value="0"},{select-day condition="season" for="myHighSeason" value="8,15,22,29"}\'], Sunday was inaccurately identified as the start day. This fix ensures that the start day for selection in specific seasons is correctly determined. (9.9.0.9)  *(Business Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Corrected Saving of Booking Dates** - An issue has been resolved where booking dates were incorrectly saved if the option "Set check out date as available" was activated at the WP Booking Calendar > Settings General page in the "Calendar" section. (9.9.0.19) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Corrected Redirect Issue for Failed Payments** - An issue has been addressed where users were incorrectly redirected to the home page instead of the failed URL when **payments failed via iDeal (Buckaroo)**.  (9.9.0.24) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Resolved an issue causing a possible PHP Fatal error has been resolved. This error, specifically the Uncaught TypeError: Unsupported operand types: null + string, occurred in the wpbc-calc-string.php file when non-standard shortcodes with arithmetic operations were used on the WP Booking Calendar > Prices > Form Options Costs page. (9.9.0.28) *(Business Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Corrected Minimum Date Selection Issue** - An issue has been addressed where the minimum number of dates in the calendar was incorrectly selected if a user clicked twice on the same date, and the system had a minimum number defined higher than 1 day. (9.9.0.29) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Corrected End Time Selection Issue** - An issue has been resolved where, if the option "Use selected times for each booking date" was deactivated, the system incorrectly blocked end times when a user selected multiple dates. For example, if the start time was booked from 00:00 to 15:00, the system would block the end time until 15:00, even if the end date was fully available. (9.9.0.31) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Corrected Display Issue with Check-In/Out Dates** - An issue has been resolved where the check-in/out dates (triangles) were incorrectly shown as fully available dates in booking resources with specific capacity. This occurred when one of the child booking resources had a change-over dates situation rather than being fully booked.  (9.9.0.33) *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
				</ul>
			</div>
		</div>

	</div><?php
	// </editor-fold>


	$obj->expand_section_end( $section_param_arr );
}


function wpbc_welcome_section_9_9( $obj ){

	$section_param_arr = array( 'version_num' => '9.9', 'show_expand' => !false );

	$obj->expand_section_start( $section_param_arr );


	//$obj->asset_path = 'http://beta/assets/';	// TODO: 2023-11-06 comment this


	// <editor-fold     defaultstate="collapsed"                        desc=" = F R E E = "  >
	// -----------------------------------------------------------------------------------------------------------------
	//  = F R E E =
	// -----------------------------------------------------------------------------------------------------------------
	?><div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Explore Simplified Workflow and Enhanced User Experience' ); ?></h2>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Enhancements in Booking Admin Panel UI' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( 'Redesigned "Top Tabs" for page selection in the Booking Calendar Admin UI. This update enhances space utilization, resulting in a clearer and smoother interface for users.' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->section_img_url( '9.9/wp_booking_calendar_admin_panel_01.png' ); ?>" />
			</div>
		</div>
		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
	</div>
	<?php

	?><div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Easy Booking Form Integration' ); ?></h3>
				<img src="<?php echo $obj->section_img_url( '9.9/wp_booking_calendar_publish_02.gif' ); ?>" />
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( 'Now it\'s super easy to put your booking form into  the pages of your website. Explore the new  "Resource" menu in the free version,  where you can find \'Publish\' button or use the new \'Publish\' button on the Booking > Resources page in paid versions. Add the form to any page with a few clicks. Simple as that!' ); ?></li>
				</ul>
			</div>
		</div>
		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
	</div>
	<?php

	// </editor-fold>


	// -----------------------------------------------------------------------------------------------------------------
	//  = P A I D =
	// -----------------------------------------------------------------------------------------------------------------
	?>
	<div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Changes in Premium Versions' ); ?></h2>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'New PayPal Standard Checkout integration.' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Integrated **PayPal Standard Checkout** payment gateway. Enjoy various payment methods, including **card payments and PayPal**. Choose from different designs for PayPal buttons. The system now automatically responds from  the PayPal, updating the booking status and payment status. *(Available in Business Small/Medium/Large, MultiUser versions).*' ) . '</li>'
					. '</ul>';
				?>
				<img src="<?php echo $obj->asset_path; ?>9.9/wp_booking_calendar_paypal_setup_03.gif" />
			</div>
		</div>
	</div>
	<?php



	// <editor-fold     defaultstate="collapsed"                        desc="  = M I X E D = "  >
	// -----------------------------------------------------------------------------------------------------------------
	//  = M I X E D =
	// -----------------------------------------------------------------------------------------------------------------
	?>
	<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
	<div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Additional Improvements in Free and Pro versions' ); ?></h2>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Improvements' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; The Prices menu now includes options such as "Seasonal Rates" (formerly "Rates"), "Duration-Based Cost" (formerly "Valuation days"), "Partial Payments" (formerly "Deposit"), "Form Options Costs" (formerly "Advanced costs"), and "Payment Gateways." This centralized location allows you to manage all prices for bookings in one place.  *(Business Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; The "Availability" section has a new structure and titles. Now, the configuration of all "Calendars Availability" is located in the "Availability" menu page and its sub-pages, including "Days Availability," "Season Availability" (formerly "Availability"), and "General Availability." This provides a centralized location to manage all availability settings for calendars. *(Business Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; The Resources menu now features an enhanced toolbar UI for creating new booking resources and managing options. This improvement provides a more user-friendly experience for resource management. *(Paid versions)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Implemented the addition of the "autocomplete" attribute to the booking form fields. This enhancement ensures correct autofill functionality in the Chrome browser when using autofill addons. *(Paid versions)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Show super booking admin  currency  at  front-end,  instead of regular user defined currency,  if was activated "Receive all payments only to Super Booking Admin account" option  at the Booking > Settings General page in "Multiuser Options" section.  (9.8.9.3) *(MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Updated Top WordPress Bar menu for Booking Calendar (9.8.15.9)' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'New Features' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Easy weekdays selection in "Season Dates Filter". Now, you can easily  select specific weekday(s) in specific year and append or remove additional dates by using range "Dates Filter" at  Booking > Resources > Filters page. (9.8.6.3) *(Business Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Introducing the **Server Balancer**, a feature designed for low-level hosting systems with limited server resources. Useful for scenarios where multiple calendars need to load bookings simultaneously on the same page or when using resource selection shortcode for many calendars. Now, you have the ability to define the number of parallel requests sent to the server. A lower number of parallel requests minimizes the impact on the server but extends the time required to load all calendars. Calendars will send requests only after the previous request is finished. By customizing the number of parallel requests, you can find the optimal balance for your specific server, especially when using numerous calendars on the same page. Simply set the value of parallel requests at Booking > Settings General page in the Advanced section. (9.8.6.2)' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Introducing General Import Conditions options for importing events into the Booking Calendar for all booking resources. You can now enable the option **Import if Dates Available** to import events only if dates are available in the source calendar. Additionally, the option **Import Only New Events** allows you to import only if the event has not been imported before. This last option replaces the deprecated "Force import" option and can be configured at Booking > Settings > Sync > "General" page in the "Import advanced" section.  (9.8.15.8)' ); ?></li>
				</ul>
			</div>
		</div>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Under Hood Changes' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Now you can aggregate only \'**bookings**\' without including unavailable dates from the \'Booking > Availability > Days Availability\' page. If you use the \'**aggregate**\' parameter in the Booking Calendar shortcode and wish to include only bookings, utilize the new parameter: **options="{aggregate type=bookings_only}"**. For instance, in the shortcode example below, we aggregate only bookings: <code>[bookingselect type=\'3,4\' aggregate=\'3;4\' options="{aggregate type=bookings_only}"]</code>  (9.8.15.10)' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Ability to define inline checkboxes and radio buttons using <code>&lt;r&gt;&lt;c&gt;...&lt;/c&gt;&lt;/r&gt;</code> constructions. For this, use the new element: <code>&lt;r&gt;&lt;c&gt;&lt;div class="wpbc_row_inline"&gt;...&lt;/div&gt;&lt;/c&gt;&lt;/r&gt;</code>. Example #1:  <code>&lt;r&gt;&lt;c&gt;&lt;p class="wpbc_row_inline"&gt;&lt;l&gt;[checkbox terms "I accept"]&lt;a href="#"&gt;terms and conditions&lt;/a&gt;&lt;/l&gt;&lt;/p&gt;&lt;/c&gt;&lt;/r&gt;</code> Example #2:  <code>&lt;r&gt;&lt;c&gt;&lt;p class="wpbc_row_inline"&gt;&lt;l&gt;Fee: [checkbox somefee default:on ""]&lt;/l&gt;&lt;/p&gt;&lt;/c&gt;&lt;/r&gt;</code> Example #3:  <code>&lt;r&gt;&lt;c&gt;&lt;p class="wpbc_row_inline"&gt;&lt;l&gt;Club: [radio* clubanlass default:No "No" "Yes"]&lt;/l&gt;&lt;/p&gt;&lt;/c&gt;&lt;/r&gt;</code> (9.8.8.1)' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Improved dates selection mode. Introducing a new JavaScript functions for defining simple customization of different date selections for various calendars. Find more information on <a href="https://wpbookingcalendar.com/faq/advanced-javascript-for-the-booking-shortcodes/">this page</a>.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; In case, if you want to wrap checkbox with text in label use parameter "label_wrap" in the shortcode construction: <code>[checkbox* terms label_wrap use_label_element "I Accept"]</code>. System make DOM construction such  as: <code>&lt;label for="cid"&gt;&lt;input class="wpdev-validates-as-required wpdev-checkbox" id="cid" type="checkbox" name="terms" value="I Accept"&gt;&nbsp;&nbsp;I Accept&lt;/label&gt;</code> (9.8.13.2)' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; In case, if you want to use labels and checkbox separately,  please use shortcode construction: <code>[checkbox* terms use_label_element "I Accept"]</code>. System make DOM construction such  as: <code>&lt;input class="wpdev-validates-as-required wpdev-checkbox" id="cid" type="checkbox" name="terms" value="I Accept"&gt;&nbsp;&nbsp;&lt;label for="cid"&gt;I Accept&lt;/label&gt;</code>  (9.8.13.2)' ); ?></li>
				</ul>
			</div>
		</div>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Bug Fixes' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Resolved an issue where bookings with zero cost were not automatically approved when the option "Auto approve booking if booking cost is zero" was activated in the Booking > Settings General page under the "Auto Cancellation / Auto Approval" section.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Resolved a fatal error: Uncaught TypeError: Unsupported operand types: string * int in ../core/sync/wpbc-gcal-class.php' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Translations' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **German Translation Update**. Translation has been updated, reaching 94% completion, courtesy of Res Rickli' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **French Translation Update**. Translation has been updated, reaching 93% completion, courtesy of Res Rickli' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Italian Translation Update**. Translation has been updated, reaching 85% completion, courtesy of Res Rickli' ); ?></li>
				</ul>
			</div>
		</div>

	</div><?php
	// </editor-fold>


	$obj->expand_section_end( $section_param_arr );
}


function wpbc_welcome_section_9_8( $obj ){

	$section_param_arr = array( 'version_num' => '9.8', 'show_expand' => !false );

	$obj->expand_section_start( $section_param_arr );


	//$obj->asset_path = 'http://beta/assets/';	// TODO: 2023-11-06 comment this


	// <editor-fold     defaultstate="collapsed"                        desc=" = F R E E = "  >
	// -----------------------------------------------------------------------------------------------------------------
	//  = F R E E =
	// -----------------------------------------------------------------------------------------------------------------
	?><div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Experience a smoother and more efficient booking process' ); ?></h2>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Completely New  Availability / Capacity engine' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( 'The calendar now dynamically loads bookings with smooth animations, enhancing the overall speed and user experience.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( 'The system is now highly proactive in preventing double bookings. It checks availability at multiple stages, whether it\'s during calendar loading or when users submit bookings. This ensures that double bookings are effectively eliminated. Even if multiple users attempt bookings for the same date/time from different locations at the same time.' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->section_img_url( '9.8/wp_booking_calendar_form_01.gif' ); ?>" style="margin-top:3.5em;"/>
			</div>
		</div>
		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->asset_path; ?>9.8/wp_booking_calendar_form_dark_02.gif" />
			</div>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Introducing the Dark Theme for Booking Form' ); ?></h3>
				<?php
				echo '<ul>'
						//. '<li>' . wpbc_replace_to_strong_symbols( 'In our latest update you have the option to switch to a "Dark" theme for your booking form.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'With this new option, you can seamlessly integrate your booking form into your website\'s design. This Theme automatically selects the appropriate calendar and time picker skins, adjusts the colors of your booking form fields, labels, text, and other UI elements.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'If you prefer Dark design, now you can select  it' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'To enable the Dark Theme, head to the Settings General Page, find the "Form Options" section, and select "Dark" from the "Color Theme" dropdown.' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>

		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Performance improvements.' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'We\'ve supercharged Booking Calendar with significant speed improvements. Compared to previous updates.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Page loading now happens **34% to 78% faster**. The speed boost varies depending on the number of bookings and number of calendars on your page (multiple calendars in paid versions of Booking Calendar).' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Server request execution speed has **improved from 63% to 94%** when you load your initial calendar.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'We\'ve streamlined the system to **reduce the number of SQL requests** for your initial page loading by **49%** to a staggering **89%**.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'All these speed enhancements have been tested with 500 active bookings.' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>

		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->asset_path; ?>9.8/wp_booking_calendar_booking_confirmation_01.gif" />
			</div>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Booking Confirmation Section' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Introduced a new Booking Confirmation section that provides users with a **summary of their booking details**, making it easy for users to confirm their reservations after completing the booking process. This feature allows users to quickly review essential booking information. \'Booking confirmation\' section located on the Booking > Settings General page. Previously, it was located in the \'Form\' section as \'Thank you\' message/page.' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>

		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Enhancements in Settings Interface' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '**Structured General Settings Page:** Redesigned the General Settings page to enhance user experience. The new layout includes a clear navigation column that displays the specific section you click on, making it easier to understand settings, quickly find specific options, and simplify the configuration of the plugin.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '**Toggle Boxes:** Replaced checkboxes in the Booking Calendar User Interface with toggle boxes. This change provides a clearer view of enabling specific options and features, particularly for enabling/disabling Rates and Availability in paid versions.' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->section_img_url( '9.8/wp_booking_calendar_settings_02.gif' ); ?>" style="margin-top:3.5em;"/>
			</div>
		</div>
	</div>
	<?php

	// </editor-fold>


	// -----------------------------------------------------------------------------------------------------------------
	//  = P A I D =
	// -----------------------------------------------------------------------------------------------------------------
	?>
	<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
	<div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Changes in Premium Versions' ); ?></h2>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->asset_path; ?>9.8/wp_booking_calendar_time_capacity_09.gif" />
			</div>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'New Capacity Engine for time-slots' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'With the new capacity engine, you can define booking capacities for your calendar, allowing you to receive a **specific number of bookings per time slots or full dates**. This enhances your control over bookings compared to the previous version, which only supported specific booking limits for full dates. *(Available in Business Large, MultiUser versions).*' ) . '</li>'
					. '</ul>';
				?>
			</div>
			<div class="wpbc_wn_col">
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'The the capacity feature ensures that the **total number of bookings for specific dates or times does not exceed the specified capacity**. This functionality is versatile and can be applied to various scenarios where you need to manage and limit bookings for specific resources to maintain efficient operations.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Booking Quantity Control:** Enabled the ability to enable and define a field for \'Booking Quantity Control,\' allowing visitors to define the number of items they can book for specific dates or times within a single reservation. Find this option in  **New  Capacity** section on Booking > Settings General page. *(Business Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Capacity Shortcode:** Added the Capacity shortcode for showing available (remained) slots per selected dates and times: <code>[capacity_hint]</code>. You can use it in the booking form at the Booking > Settings > Form page. *(Business Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>

		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->asset_path; ?>9.8/wp_booking_calendar_payments_01.gif" />
			</div>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Redesigned Payment Buttons' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Redesigned Payment Buttons:** Payment buttons in the new Booking Confirmation window have been redesigned for a more user-friendly experience. *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Payment System Responses:** Now, responses from payment systems after visitors\' payments are recorded in the Note section of the booking, provided "Logging actions for booking" is activated at the Booking > Settings General page in the "Booking Admin Panel" section. Also the booking log now keeps track of booking details, such as cost calculations, actions related to payment request pages via email links, and other important events. *(Business Small/Medium/Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
			<div class="wpbc_wn_col">
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Additional Notes:** Added the ability to add extended notes about "Total Cost | Discounts applied | Subtotal cost | Deposit Due | Balance Remaining" after creating the booking. Also, added notes about the approval of the booking by the payment system after a response from the Payment gateway. Notes are now added for bookings that were imported from Google Calendar. *(Business Medium/Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->asset_path; ?>9.8/wp_booking_calendar_extended_notes.png" />
			</div>
		</div>

		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">

				<h3><?php echo wpbc_replace_to_strong_symbols( 'Showing Customizable Booking Details in Tooltip' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Booking Details Tooltip:** Added the ability to show booking details in a mouse-over tooltip for specific booked dates or times during a day, significantly improving the speed of this functionality. <br>This can now **show booking details even for fully booked dates**. ' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'It\'s a helpful feature for businesses with booking workflows that require displaying information about the users who\'ve made the bookings or for similar neighborhood-based use cases.<br>*(Business Medium/Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->asset_path; ?>9.8/wp_booking_display_booking_details_05.gif" />
			</div>
		</div>

		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->asset_path; ?>9.8/wp_booking_display_unavailable_before_after_02.gif" />
			</div>
			<div class="wpbc_wn_col">

				<h3><?php echo wpbc_replace_to_strong_symbols( 'Set Unavailable Time Before or After Bookings' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'This feature improves the engine for defining a specific period of unavailability before or after bookings. It now works in all scenarios, even for fully booked dates. When you select multiple dates and specific times for a booking, the system extends the unavailable time interval for those time slots on each day.' ) . '</li>'

					. '</ul>';
				?>
			</div>
			<div class="wpbc_wn_col">
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'It\'s a great feature for preparing your property or service before or after a specific booking, such as allowing time for cleaning.<br>*(Business Medium/Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>

		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Simple HTML Configuration' ); ?></h3>
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Now, you can organize your booking form fields in rows and columns using the new Simple HTML shortcodes. This makes configuring your booking form even easier.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Here\'s how it works:**' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( 'Example of how to create a single row with 2 columns:' ) . '</li>'
						. '<li><pre>&lt;r&gt;<br>  &lt;c&gt;...&lt;/c&gt;<br>  &lt;c&gt;...&lt;/c&gt;  <br>&lt;/r&gt;</pre></li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**With this feature, you have the flexibility to design your booking form exactly as you need it.**' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( ' *(Available in all paid versions).*' ) . '</li>'
					. '</ul>';
				?>
			</div>
			<div class="wpbc_wn_col">
				<img src="<?php echo $obj->asset_path; ?>9.8/wp_booking_calendar_simple_html_09.gif" />
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Create Rows**: Use <code>&lt;r&gt;...&lt;/r&gt;</code> tags to defines a row in your form.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Add Columns**: Inside each row, you can specify columns using <code>&lt;c&gt;...&lt;/c&gt;</code> tags.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Label Fields**: Use the <code>&lt;l&gt;...&lt;/l&gt;</code> tags.' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Spacer**: <code>&lt;spacer&gt;&lt;/spacer&gt;</code> or <code>&lt;spacer&gt;width:1em;&lt;/spacer&gt;</code>.' ) . '</li>'
					. '</ul>';
				?>
			</div>
			<div class="wpbc_wn_col">
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Simplified field data tag**: <code>&lt;f&gt;...&lt;/f&gt;</code>. Easily highlight field data by enclosing it within <code>&lt;f&gt;...&lt;/f&gt;</code> tags in the \'Content of booking fields data\' section on the Booking &gt; Settings &gt; Form page. For example: <code>&lt;f&gt;[secondname]&lt;/f</code>. This will highlight the background of the field on the Booking Listing page. *(All Pro Versions)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>

		<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<?php
				echo '<ul>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Different Rates for options in select-boxes:** Now, different rates are supported, depending on the selection of options in select-boxes. Example of rate configuration at Booking > Resources > Cost and rates > Rate page: <code>[visitors=1:270;2:300;3:380;4:450]</code>  (9.8.0.5) *(Business Medium/Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Date Selection Condition:** Added a condition for defining a specific number of selected dates if started from a specific date. Condition format: <code>\'{select-day condition="date" for="2023-10-01" value="20,25,30-35"}\'</code>.  Example of shortcode: <code>[booking type=3 options=\'{select-day condition="date" for="2023-10-01" value="20,25,30-35"}\']</code> *(Business Medium/Large, MultiUser)*' ) . '</li>'
						. '<li>' . wpbc_replace_to_strong_symbols( '**Cancellation Date Hint Shortcode:** Introduced the <code>[cancel_date_hint]</code> shortcode, which shows the date that is 14 days before the selected check-in date. (9.7.3.16) *(Business Medium/Large, MultiUser)*' ) . '</li>'
					. '</ul>';
				?>
			</div>
		</div>
	</div>
	<?php



	// <editor-fold     defaultstate="collapsed"                        desc="  = M I X E D = "  >
	// -----------------------------------------------------------------------------------------------------------------
	//  = M I X E D =
	// -----------------------------------------------------------------------------------------------------------------
	?>
	<hr class="wpbc_hr_dots"><?php // ---------------------------------------------------------------------- ?>
	<div class="wpbc_wn_container">
		<div class="wpbc_wn_section">
			<h2><?php echo wpbc_replace_to_strong_symbols( 'Additional Improvements in Free and Pro versions' ); ?></h2>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Toolbar Enhancement** Added a \'Reset\' button at Booking > Add booking page for the toolbar of configuring calendar size.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Date : Time** section on the Booking > Settings General page, making it easier to configure date and time options. Now, the \'Time format\' option is also available in the Booking Calendar Free version.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Daylight Saving Time Fix:** Resolved the \'Daylight Saving Time\' issue that existed on some servers (possibly due to themes or other plugins defining different timezones than those in WordPress via date_default_timezone_set(...) )), ensuring localized dates and times work correctly for all booking dates/times without the need to activate any options in the settings.' ); ?></li>

					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Improved Google Calendar Import:** Improved the actual cron system for importing Google Calendar events, allowing you to set import time intervals starting from 15 minutes. The system now shows the last and next time of importing at the Booking > Settings > Sync > "Import Google Calendar Events" page.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Pseudo Cron** System updated  for google calendar imports.' ); ?></li>

					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Scrolling Enhancement:** Enhanced scrolling to specific elements in the booking form, ensuring that the system will not create a new scroll if the previous one was not finished.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Legend Position:** Moved \'Show legend below calendar\' to the \'Calendar\' section on the Booking > Settings General page. Previously, it was located in the \'Form\' section.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Deprecated Options Removal:** Removed deprecated options such as "Use localized time format", \'Time for showing "Thank you" message\',  \'Checking to prevent double booking during submitting booking,\' \'Set capacity based on the number of visitors,\' \'Disable booked time slots in multiple days selection mode\' from Booking > Settings General page  and  option: "for setting maximum  number of visitors per resource" at  the Booking > Resources page in paid versions.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **JS Calendar Scripts:** Updated to version 9.8.0.3.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Caching Improvement:** Introduced new caching for frequently used SQL requests (9.7.3.14).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **\'Reply-To visitor email\' Option:** Added the \'Reply-To visitor email\' option for "Admin emails" at the Booking > Settings > Emails page. By default, this option is disabled to prevent spam detection at some servers in the outbound SMTP relay, which could lead to email rejection (9.7.3.17).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Updated Styles:** Improved the styles of warning messages in the booking form for a better user experience.' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Calendar Dimensions:** Increased the width of the calendar from 284px to 341px and the height of calendar cells from 40px to 48px (9.7.3.2). Improved internal logic for calendar months\' size. The width of the calendar is now based on the maximum width, ensuring great responsiveness at any resolution.  No need to use "strong_width" parameter in options of Booking Calendar shortcode. (9.7.3.4)' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Mobile Optimization:** For small mobile devices (width smaller than 400px), the height cell is now 40px by default (9.7.3.2). You can specify the same height for all devices using the \'strong_cell_height\' parameter in the shortcode. For example: [booking type=1 nummonths=2 options=\'{calendar months_num_in_row=2 width=682px strong_cell_height=55px}\'] (9.7.3.3)' ); ?></li>
<!-- PRO -->
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Shortcodes Enhancement:** All shortcodes can now use the parameter \'resource_id\' instead of the previously deprecated \'type\' parameter. *(All Pro Versions)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Showing the **full URL .ics feed** at the Booking > Settings > Sync > **"Export - .ics" page** for easier copying of URLs (9.8.0.6). *(All Pro Versions)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; When creating new fast blank bookings, all fields are autofilled with \'---\' instead of \'admin\' values, and the email address is filled with \'blank@wpbookingmanager.com,\' which is skipped during sending emails by Booking Calendar. *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Improved \'Aggregate\' Parameter**: Now, when using \'aggregate,\' if you mark specific dates as unavailable in **aggregation resources on the Booking > Availability page**, the system will automatically make those dates unavailable in the source resource. *(All Pro Versions)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Enhanced Availability Management: If you mark dates as unavailable in the aggregate booking resources on the Booking > Availability page, the system treats these dates as unavailable for all booking resources, including parent and child resources. This ensures consistent availability management. *(All Pro Versions. Capacity in Business Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; For booking resources with specific capacities, when you use \'aggregate\' for a \'parent booking resource\' with a set capacity, the system adds bookings from \'aggregate booking resources\' to the \'parent resource\' and its child resources. Any \'unavailable dates\' marked on the Booking > Availability page will affect both the parent and its child resources, making those times or dates unavailable for booking. *(Business Large, MultiUser)*' ); ?></li>
				<ul>
			</div>
		</div>

		<div class="wpbc_wn_section">
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Under Hood Changes' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Removed the JavaScript wpbc_settings variable. Instead of wpbc_settings.set_option( \'pending_days_selectable\', true ); use: _wpbc.calendar__set_param_value( resource_id , \'pending_days_selectable\' , true  );  It\'s give ability to define this parameter separately per each calendar in paid versions.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Replaced deprecated functions related to the new "Capacity and Availability" engine (9.7.3.13), and updated the cron system for Google Calendar imports.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Replaced  JavaScript  function showErrorMessage( element , errorMessage , isScrollStop ) to 	wpbc_front_end__show_message__warning( jq_node, message ).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Test Dates Functions:** Added the [wpbc_test_dates_functions] shortcode for testing different dates functions on the server, relative to the possible \'Daylight Saving Time\' issue.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '**CSS Class:** Added a new \'wpbc_calendar\' CSS class to the calendar HTML table, making it easier to manage CSS conflicts with theme styles). You can use CSS in the theme like this: table:not(.wpbc_calendar){...} instead of table{...} (9.7.3.7)' ); ?></li>
<!-- PRO -->
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Stripe**: Updated Stripe PHP library from version 9.0.0 to 12.6.0. *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
				    <li><?php echo wpbc_replace_to_strong_symbols( 'Max. visitors field at the Booking > Resources page is deprecated and removed. For defining capacity, use child booking resources. For defining max visitors selection, use a new custom booking form with a different number of users/visitors selection. *(Business Medium/Large, MultiUser)*' ); ?></li>

				</ul>
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Support' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; **Support for WordPress 6.4:** Added support for WordPress version 6.4.' ); ?></li>
				</ul>
				</ul>
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Translations' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Translation: Improved German (94% completed) by Reinhard Kappen and French (93% completed) by Roby.' ); ?></li>
				</ul>
			</div>
			<div class="wpbc_wn_col">
				<h3><?php echo wpbc_replace_to_strong_symbols( 'Bug Fixes' ); ?></h3>
				<ul>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Fixed an issue with not correctly showing creation and modification booking times on some servers.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Corrected the problem of showing the calendar with an incorrectly defined Start week date at Booking > Availability page.' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Resolved the issue of not translating some terms in the plugin (9.7.3.9).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Fixed a color issue of daily cost in calendar date cells for the "Light-01" calendar skin (9.7.3.10).' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Fixed an Uncaught TypeError related to the wpbc-gcal-class.php file (9.7.3.15).' ); ?></li>
<!-- PRO -->
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Fixed an issue of redirection to the "Unknown-Stripe-Payment" page after Stripe payment in Booking Calendar MultiUser version, if the option "Receive all payments only to Super Booking Admin account" was activated. (9.7.3.5) *(MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Corrected the issue of not showing conditional time slots, which depend on Seasons.  Uncaught Error: Syntax error, unrecognized expression: # jQuery 10(9.7.3.6) *(Business Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Resolved the problem of removing duplicate days\' selections at the "Specific days" selection option under range days selection mode using 2 mouse clicks. *(Business Small/Medium/Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Fixed the issue of showing available dates in the search form, while such dates were defined as unavailable at Booking > Availability page  (9.7.3.11). *(Business Large, MultiUser)*' ); ?></li>
					<li><?php echo wpbc_replace_to_strong_symbols( '&bull; Eliminated the issue when used conditional days selection logic, and some weekdays were not defined in seasons. In this case, the system will use the default days selection settings.  *(Business Medium/Large, MultiUser)*' ); ?></li>
				<ul>
			</div>
		</div>
	</div><?php
	// </editor-fold>


	$obj->expand_section_end( $section_param_arr );
}
