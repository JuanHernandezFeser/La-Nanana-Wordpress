<p><?php echo __( 'Choose how you want to redirect WhatsApp URL.', 'wp-whatsapp' ); ?></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="nta-wa-switch-control"><?php echo __( 'Open in new tab', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-switch-control">
					<input type="checkbox" id="nta-wa-switch-open-new-tab" name="openInNewTab" <?php checked( $option['openInNewTab'], 'ON' ); ?>>
					<label for="nta-wa-switch-open-new-tab" class="green"></label>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php echo __( 'URL for Desktop', 'wp-whatsapp' ); ?></label></th>
			<td>
				<input name="onDesktop" id="urlOnDesktop" class="hidden" value="<?php echo esc_attr( $option['onDesktop'] ); ?>" />
				<div class="button-group button-large" data-setting="onDesktop">
					<button class="button btn-api <?php echo ( $option['onDesktop'] == 'api' ? 'active' : '' ); ?>" value="api" type="button">
						API
					</button>
					<button class="button btn-web <?php echo ( $option['onDesktop'] == 'web' ? 'active' : '' ); ?>" value="web" type="button">
						Web
					</button>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php echo __( 'URL for Mobile', 'wp-whatsapp' ); ?></label></th>
			<td>
				<input name="onMobile" id="urlOnMobile" class="hidden" value="<?php echo esc_attr( $option['onMobile'] ); ?>" />
				<div class="button-group button-large" data-setting="onMobile">
					<button class="button btn-api <?php echo ( $option['onMobile'] == 'api' ? 'active' : '' ); ?>" value="api" type="button">
						API
					</button>
					<button class="button btn-protocol <?php echo ( $option['onMobile'] == 'protocol' ? 'active' : '' ); ?>" value="protocol" type="button">
						Protocol
					</button>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<button class="button button-large button-primary wa-save"><?php echo __( 'Save Changes', 'wp-whatsapp' ); ?><span></span></button>