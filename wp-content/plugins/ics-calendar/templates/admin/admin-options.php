<p class="r34ics-input">
	<label for="r34ics-admin-options-transients_expiration"><strong><?php _e('Transient (cache) expiration', 'r34ics'); ?>:</strong> <input type="number" name="transients_expiration" id="r34ics-admin-options-transients_expiration" value="<?php echo esc_attr(get_option('r34ics_transients_expiration') ? get_option('r34ics_transients_expiration') : 3600); ?>" min="0" max="86400" style="width: 100px;" /> <?php _e('seconds', 'r34ics'); ?></label>
	<span class="description"><small class="r34ics-help"><span class="help_content"><?php _e('Sets how long calendar feed data should be cached on the server (WordPress transients) before reloading. Default is 3600 (1 hour).', 'r34ics'); ?></span></small></span>
</p>

<p class="r34ics-input">
	<label for="r34ics_allowed_hosts"><strong><?php _e('Allow access to these hostnames that resolve to reserved IP addresses', 'r34ics'); ?>:</strong><br />
	<textarea name="allowed_hosts" id="r34ics_allowed_hosts" style="max-width: 400px; width: 100%; height: 4.5rem;" /><?php echo esc_attr(implode("\n", get_option('r34ics_allowed_hosts') ? get_option('r34ics_allowed_hosts') : array())); ?></textarea></label>
	<span class="description"><small class="r34ics-help"><span class="help_content"><?php printf(__('If your calendar server is hosted on the same physical server as your website, is part of the same local network, or otherwise uses a %1$sreserved IP address%2$s, enter its hostname here to allow access. For multiple calendar servers, enter one hostname per line. See %3$sthe %4$s documentation%5$s for more information.', 'r34ics'), '<a href="https://en.wikipedia.org/wiki/Internet_Protocol_version_4#Special-use_addresses" target="_blank">', '</a>', '<a href="https://icscalendar.com/developer/#http_request_host_is_external" target="_blank">', 'ICS Calendar', '</a>'); ?></span></small></span><br />
	<small><?php _e('Enter one hostname per line.', 'r34ics'); ?></small>
</p>

<p class="r34ics-input">
	<label for="r34ics_url_get_contents_legacy_method"><input type="checkbox" name="url_get_contents_legacy_method" id="r34ics_url_get_contents_legacy_method"<?php if (get_option('r34ics_url_get_contents_legacy_method')) { echo ' checked="checked"'; } ?> /> <strong><?php _e('Use legacy feed request method', 'r34ics'); ?></strong></label>
	<span class="description"><small class="r34ics-help"><span class="help_content"><?php _e('If your site has stopped retrieving feeds after the version 11 update, turn on this option to use the legacy request method.', 'r34ics'); ?></span></small></span>
</p>

<p class="r34ics-input">
	<label for="r34ics_display_add_calendar_button_false"><input type="checkbox" name="display_add_calendar_button_false" id="r34ics_display_add_calendar_button_false"<?php if (get_option('r34ics_display_add_calendar_button_false')) { echo ' checked="checked"'; } ?> /> <strong><?php _e('Remove "Add ICS Calendar" button in Classic Editor', 'r34ics'); ?></strong></label>
	<span class="description"><small class="r34ics-help"><span class="help_content"><?php _e('By default, ICS Calendar adds an "Add ICS Calendar" button to the toolbar above the WYSIWYG editor when using Classic Editor. Check this box to remove the button. Has no effect on sites using the Block Editor (Gutenberg).', 'r34ics'); ?></span></small></span>
</p>

<p class="r34ics-input">
	<label for="r34ics_use_new_defaults_10_6"><input type="checkbox" name="use_new_defaults_10_6" id="r34ics_use_new_defaults_10_6"<?php if (get_option('r34ics_use_new_defaults_10_6')) { echo ' checked="checked"'; } ?> /> <strong><?php _e('Use new parameter defaults (v.10.6)', 'r34ics'); ?></strong></label>
	<span class="description"><small class="r34ics-help"><span class="help_content"><?php printf(__('%1$s version 10.6 introduced new default values for several shortcode parameters. New installations automatically use the new default values, but upgraded installations will continue to use the old default values, unless this box is checked. Read more about these changes on %2$sour blog%3$s.', 'r34ics'), 'ICS Calendar', '<a href="https://icscalendar.com/updated-parameter-defaults-in-ics-calendar-10-6/" target="_blank">', '</a>'); ?></span></small></span>
</p>

