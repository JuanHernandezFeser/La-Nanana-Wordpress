<table class="form-table" id="app-design">
	<p><?php echo esc_html__( 'Setting text and style for the floating widget.', 'wp-whatsapp' ); ?></p>
	<tbody>
		<tr>
			<th scope="row"><label for="title"><?php echo esc_html__( 'Widget Text', 'wp-whatsapp' ); ?></label></th>
			<td><input name="title" placeholder="Start a Conversation" type="text" id="title" value="<?php echo esc_attr( $option['title'] ); ?>" class="regular-text"></td>
		</tr>

		<tr>
			<th scope="row"><label for="isShowBtnLabel"><?php echo esc_html__( 'Show Widget Label', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-switch-control" style="margin-top: 5px;">
					<input type="checkbox" id="isShowBtnLabel" name="isShowBtnLabel" <?php checked( $option['isShowBtnLabel'], 'ON' ); ?>>
					<label for="isShowBtnLabel" class="green"></label>
				</div>
			</td>
		</tr>

		<tr class="<?php echo esc_attr( 'ON' === $option['isShowBtnLabel'] ? '' : 'hidden' ); ?>">
			<th scope="row"><label for="btnLabel"><?php echo esc_html__( 'Widget Label Text', 'wp-whatsapp' ); ?></label></th>
			<td><input name="btnLabel" placeholder="Need Help? <strong>Chat with us</strong>" type="text" id="btnLabel" value="<?php echo esc_attr( $option['btnLabel'] ); ?>" class="regular-text"></td>
		</tr>

		<tr class="<?php echo esc_attr( 'ON' === $option['isShowBtnLabel'] ? '' : 'hidden' ); ?>">
			<th scope="row"><label for="btnLabelWidth"><?php echo esc_html__( 'Widget Label Width(px)', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="range" style='--min:0; --max:500; --value:<?php echo esc_attr( $option['btnLabelWidth'] ); ?>; --text-value:"<?php echo esc_attr( $option['btnLabelWidth'] ); ?>";'>
					<input id="btnLabelWidth" name="btnLabelWidth" type="range" min="0" max="500" value="<?php echo esc_attr( $option['btnLabelWidth'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))">
					<output></output>
					<div class='range__progress'></div>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="textColor"><?php echo esc_html__( 'Widget Text Color', 'wp-whatsapp' ); ?></label></th>
			<td><input type="text" id="textColor" name="textColor" value="<?php echo esc_attr( $option['textColor'] ); ?>" class="textColor" data-default-color="#fff" /></td>
		</tr>

		<tr class="setting font-size">
			<th scope="row"><label for=""><?php echo esc_html__( 'Widget Font Size', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div id="title-range-slider">
					<p><?php echo esc_html__( 'Title', 'wp-whatsapp' ); ?></p>
					<div class="range title-size" style='--min:10; --max:20; --value:<?php echo esc_attr( $option['titleSize'] ); ?>; --text-value:"<?php echo esc_attr( $option['titleSize'] ); ?>"'>
						<input type="range" name="titleSize" min="10" max="20" value="<?php echo esc_attr( $option['titleSize'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))">
						<output></output>
						<div class='range__progress'></div>
					</div>
				</div>
				<div id="description-range-slider">
					<p><?php echo esc_html__( 'Description', 'wp-whatsapp' ); ?></p>
					<div class="range description-size" style='--min:10; --max:20; --value:<?php echo esc_attr( $option['descriptionTextSize'] ); ?>; --text-value:"<?php echo esc_attr( $option['descriptionTextSize'] ); ?>"'>
						<input type="range" name="descriptionTextSize" min="10" max="20" value="<?php echo esc_attr( $option['descriptionTextSize'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))"/>
						<output></output>
						<div class='range__progress'></div>
					</div>
				</div>
				<div id="account-name-range-slider">
					<p><?php echo esc_html__( 'Account Name', 'wp-whatsapp' ); ?></p>
					<div class="range account-name-size" style='--min:10; --max:20; --value:<?php echo esc_attr( $option['accountNameSize'] ); ?>; --text-value:"<?php echo esc_attr( $option['accountNameSize'] ); ?>"'>
						<input type="range" name="accountNameSize" min="10" max="20" value="<?php echo esc_attr( $option['accountNameSize'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))" />
						<output></output>
						<div class='range__progress'></div>
					</div>
				</div>
				<div id="regular-text-range-slider">
					<p><?php echo esc_html__( 'Regular Text', 'wp-whatsapp' ); ?></p>
					<div class="range regular-text-size" style='--min:10; --max:20; --value:<?php echo esc_attr( $option['regularTextSize'] ); ?>; --text-value:"<?php echo esc_attr( $option['regularTextSize'] ); ?>"'>
						<input type="range" name="regularTextSize" min="10" max="20" value="<?php echo esc_attr( $option['regularTextSize'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))"/>
						<output></output>
						<div class='range__progress'></div>
					</div>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="backgroundColor"><?php echo esc_html__( 'Widget Background Color', 'wp-whatsapp' ); ?></label></th>
			<td><input id="backgroundColor" type="text" name="backgroundColor" value="<?php echo esc_attr( $option['backgroundColor'] ); ?>" class="backgroundColor" data-default-color="#2db742" /></td>
		</tr>
		<tr>
			<th scope="row"><label for=""><?php echo esc_html__( 'Widget Position', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="setting align">
					<div class="button-group button-large" data-setting="align">
						<button class="button btn-left disabled njt-wa-pro-tooltip <?php echo esc_attr( 'left' === $option['btnPosition'] ? 'active' : '' ); ?>" value="left" type="button">
							<?php echo esc_html__( 'Left', 'wp-whatsapp' ); ?>
						</button>
						<button class="button btn-right <?php echo esc_attr( 'right' === $option['btnPosition'] ? 'active' : '' ); ?>" value="right" type="button">
							<?php echo esc_html__( 'Right', 'wp-whatsapp' ); ?>
						</button>
					</div>
					<input name="btnPosition" id="btnPosition" class="hidden" value="<?php echo esc_attr( $option['btnPosition'] ); ?>" />
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for=""><?php echo esc_html__( 'Widget Distance', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div id="left-range-slider">
					<div>Left</div>
					<div class="range" style='--min:0; --max:500; --value:<?php echo esc_attr( $option['btnLeftDistance'] ); ?>; --text-value:"<?php echo esc_attr( $option['btnLeftDistance'] ); ?>";'>
						<input id="btnLeftDistance" name="btnLeftDistance" type="range" min="0" max="500" value="<?php echo esc_attr( $option['btnLeftDistance'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))">
						<output></output>
						<div class='range__progress'></div>
					</div>
				</div>
				<div id="right-range-slider">
					<div>Right</div>
					<div class="range" style='--min:0; --max:500; --value:<?php echo esc_attr( $option['btnRightDistance'] ); ?>; --text-value:"<?php echo esc_attr( $option['btnRightDistance'] ); ?>";'>
						<input id="btnRightDistance" name="btnRightDistance" type="range" min="0" max="500" value="<?php echo esc_attr( $option['btnRightDistance'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))">
						<output></output>
						<div class='range__progress'></div>
					</div>
				</div>
				<div>
					<div>Bottom</div>
					<div class="range" style='--min:0; --max:500; --value:<?php echo esc_attr( $option['btnBottomDistance'] ); ?>; --text-value:"<?php echo esc_attr( $option['btnBottomDistance'] ); ?>";'>
						<input id="btnBottomDistance" name="btnBottomDistance" type="range" min="0" max="500" value="<?php echo esc_attr( $option['btnBottomDistance'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))">
						<output></output>
						<div class='range__progress'></div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for=""><?php echo esc_html__( 'Widget Scroll Bar (PRO)', 'wp-whatsapp' ); ?><span class="dashicons dashicons-editor-help njt-wa-tooltip"></span></label></th>
			<td>
				<div class="nta-wa-switch-control" style="margin-top: 5px;">
					<input class="njt-wa-pro" type="checkbox" id="isShowScroll" name="isShowScroll" <?php checked( $option['isShowScroll'], 'ON' ); ?>>
					<label for="isShowScroll" class="green njt-wa-pro-tooltip"></label>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for=""><?php echo esc_html__( 'Powered-by Label (PRO)', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-switch-control" style="margin-top: 5px;">
					<input class="njt-wa-pro" type="checkbox" id="isShowPoweredBy" name="isShowPoweredBy" <?php checked( $option['isShowPoweredBy'], 'ON' ); ?>>
					<label for="isShowPoweredBy" class="green njt-wa-pro-tooltip"></label>
				</div>
			</td>
		</tr>
		<tr class="<?php echo esc_attr( 'ON' === $option['isShowScroll'] ? '' : 'hidden' ); ?>">
			<th scope="row"><label for=""></label></th>
			<td>
				<div class="range" style='--min:300; --max:1000; --value:<?php echo esc_attr( $option['scrollHeight'] ); ?>; --text-value:"<?php echo esc_attr( $option['scrollHeight'] ); ?>";'>
					<input id="scrollHeight" name="scrollHeight" type="range" min="300" max="1000" value="<?php echo esc_attr( $option['scrollHeight'] ); ?>" oninput="this.parentNode.style.setProperty('--value',this.value); this.parentNode.style.setProperty('--text-value', JSON.stringify(this.value))">
					<output></output>
					<div class='range__progress'></div>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="responseText"><?php echo esc_html__( 'Response Time Text', 'wp-whatsapp' ); ?></label></th>
			<td>
				<?php wp_editor( $option['responseText'], 'responseText', $editor_settings ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="wp-description-wrap"><?php echo esc_html__( 'Description', 'wp-whatsapp' ); ?></label></th>
			<td>
				<?php wp_editor( $option['description'], 'description', $editor_settings_quicktags ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="gdprContent"><?php echo esc_html__( 'GDPR Notice', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-switch-control" style="margin-top: 5px;">
					<input type="checkbox" id="nta-wa-switch-gdpr" name="isShowGDPR" <?php checked( $option['isShowGDPR'], 'ON' ); ?>>
					<label for="nta-wa-switch-gdpr" class="green"></label>
				</div>
				<br />
				<div id="nta-gdpr-editor" class="<?php echo esc_attr( 'ON' === $option['isShowGDPR'] ? '' : 'hidden' ); ?>">
					<?php wp_editor( $option['gdprContent'], 'gdprContent', $editor_settings_quicktags ); ?>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<button class="button button-large button-primary wa-save"><?php echo esc_html__( 'Save Changes', 'wp-whatsapp' ); ?><span></span></button>