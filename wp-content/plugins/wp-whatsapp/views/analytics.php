<p><?php echo __( 'Enable WhatsApp trackers to monitor your WhatsApp widget, button and other guest activities.', 'wp-whatsapp' ); ?></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="enabledGoogle"><?php echo __( 'Google Analytics (PRO)', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-switch-control">
					<input type="checkbox" id="enabledGoogle" name="enabledGoogle" <?php checked( $option['enabledGoogle'], 'ON' ); ?>>
					<label for="enabledGoogle" class="green njt-wa-pro-tooltip"></label>
				</div>
				<p class="description"><?php echo __( 'Gain insights of WhatsApp tracking in Google Analytics > Behavior > Events', 'wp-whatsapp' ); ?></p>
			</td>
		</tr>
		<tr class="<?php echo ( $option['enabledGoogle'] === 'ON' ) ? '' : 'hidden'; ?>">
			<th></th>
			<td>
				<div class="nta-wa-switch-control">
					<input type="checkbox" id="enabledGoogleGA4" name="enabledGoogleGA4" <?php checked( $option['enabledGoogleGA4'], 'ON' ); ?>>
					<label for="enabledGoogleGA4" class="green"></label>
				</div>
				<p class="description"><?php echo __( 'Please enable this feature if your website is using Google Analytics 4', 'wp-whatsapp' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="enabledFacebook"><?php echo __( 'Facebook Pixel (PRO)', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-switch-control">
					<input type="checkbox" id="enabledFacebook" name="enabledFacebook" <?php checked( $option['enabledFacebook'], 'ON' ); ?>>
					<label for="enabledFacebook" class="green njt-wa-pro-tooltip"></label>
				</div>
				<p class="description"><?php echo __( 'Access Facebook for Business and view recorded events in Event Manager', 'wp-whatsapp' ); ?></p>
			</td>
		</tr>
</tbody>
</table>
<button class="button button-large button-primary wa-save"><?php echo __( 'Save Changes', 'wp-whatsapp' ); ?><span></span></button>