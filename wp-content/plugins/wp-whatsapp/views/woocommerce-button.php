<?php
use NTA_WhatsApp\Helper;
?>
<p><?php echo __( 'Display WhatsApp click to chat button on WooCommerce single product pages.', 'wp-whatsapp' ); ?></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="nta-wa-switch-control"><?php echo __( 'Enabled', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-switch-control">
					<input type="checkbox" id="nta-wa-switch" name="isShow" <?php checked( $option['isShow'], 'ON' ); ?>>
					<label for="nta-wa-switch" class="green"></label>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="position"><?php echo __( 'Button position', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-d-row nta-wa-border-box">
					<div class="nta-wa-radio-select-img njt-wa-pro-tooltip">
						<input type="radio" id="nta-wa_before_atc" name="position" value="before_atc" <?php checked( $option['position'], 'before_atc' ); ?>>
						<label for="nta-wa_before_atc">
							<div class="nta-wa-radio-img-wrap">
								<img src="<?php echo NTA_WHATSAPP_PLUGIN_URL . 'assets/img/woo_settings/before_add_to_cart.png'; ?>">
							</div>    
							<span><?php echo __( 'Before Add to Cart button', 'wp-whatsapp' ); ?></span>
						</label>
					</div>
					<div class="nta-wa-radio-select-img">
						<input type="radio" id="nta-wa_after_atc" name="position" value="after_atc" <?php checked( $option['position'], 'after_atc' ); ?>>
						<label for="nta-wa_after_atc">
							<div class="nta-wa-radio-img-wrap">
								<img src="<?php echo NTA_WHATSAPP_PLUGIN_URL . 'assets/img/woo_settings/after_add_to_cart.png'; ?>">
							</div>    
							<span><?php echo __( 'After Add to Cart button', 'wp-whatsapp' ); ?></span>
						</label>
					</div>
					<div class="nta-wa-radio-select-img njt-wa-pro-tooltip">
						<input type="radio" id="nta-wa_after_short_description" name="position" value="after_short_description" <?php checked( $option['position'], 'after_short_description' ); ?>>
						<label for="nta-wa_after_short_description">
							<div class="nta-wa-radio-img-wrap">
								<img src="<?php echo NTA_WHATSAPP_PLUGIN_URL . 'assets/img/woo_settings/after_short_desc.png'; ?>">
							</div>
							<span><?php echo __( 'After short description', 'wp-whatsapp' ); ?></span>
						</label>
					</div>
					<div class="nta-wa-radio-select-img njt-wa-pro-tooltip">
						<input type="radio" id="nta-wa_after_long_description" name="position" value="after_long_description" <?php checked( $option['position'], 'after_long_description' ); ?>>
						<label for="nta-wa_after_long_description">
							<div class="nta-wa-radio-img-wrap">
								<img src="<?php echo NTA_WHATSAPP_PLUGIN_URL . 'assets/img/woo_settings/after_long_desc.png'; ?>">
							</div>
							<span><?php echo __( 'After long description', 'wp-whatsapp' ); ?></span>
						</label>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="selected_accounts"><?php echo __( 'Select accounts to display', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div id="app-woo"></div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for=""><?php echo __( 'Third party integrations (PRO)', 'wp-whatsapp' ); ?></label></th>
			<td>
				<div class="nta-wa-built-in-feature">
					<div class="nta-wa-switch-control">
						<input class="njt-wa-pro" type="checkbox" id="nta-wa-dokan-enabled-switch" name="nta-wa-dokan-enabled-switch" <?php checked( 'OFF' ); ?>>
						<label for="nta-wa-dokan-enabled-switch" class="green njt-wa-pro-tooltip"></label>
					</div>
					<div class="nta-wa-label" style="margin-top:4px"><?php echo __( 'Enable Dokan vendor button', 'wp-whatsapp' ); ?></div>
				</div>
			</td>
		</tr>
	</tbody>
</table>
<button class="button button-large button-primary wa-save"><?php echo __( 'Save Changes', 'wp-whatsapp' ); ?><span></span></button>

<script type="text/template" id="selectedAccountTemplate">
	<div class="search-account">
		<input id="input-users" class="ui-autocomplete-loading" type="text" autocomplete="off" placeholder="Search account by enter name or title">
	</div>
	<br/>
</script>

<script type="text/template" id="accountItemView">
<div class="nta-list-items">
	<div class="box-content">
		<div class="box-row">
			<div class="account-avatar">
				<% if (!_.isEmpty(account.avatar)) { %>
					<div class="wa_img_wrap" style="background: url(<%= account.avatar %>) center center no-repeat; background-size: cover;"></div>
				<% } else { %>
					<?php echo Helper::print_icon(); ?>
				<% } %>
			</div>
			<div class="container-block">
				<h4><%= account.accountName %></h4>
				<p><%= account.title %></p>
				<p>
					<% _.each(daysOfWeek, function (day) { %>
						<% if (account.isAlwaysAvailable == 'ON') { %>
							<span class="active-date"><%= day[1] %></span>
						<% } else { %>
							<span class="<%= (account['daysOfWeekWorking'][day[0]]['isWorkingOnDay'] === 'ON') ? 'active-date' : '' %>"><%= day[1] %></span>
						<% } %>
					<% }); %>
				</p>
			</div>
		</div>
	</div>
</div>
</script>

<script type="text/template" id="accountListTemplate">
	<label class="nta-list-status">
		<strong>
			<% if (_.isEmpty(activeAccounts)) { %>
			<?php echo __( 'Please select accounts you want them to display in WhatsApp Chat Widget', 'wp-whatsapp' ); ?>
			<% } else { %>
			<?php echo __( 'Selected Accounts:', 'wp-whatsapp' ); ?>
			<% } %>
		</strong>
	</label>
	<% if (!_.isEmpty(activeAccounts)) { %>
	<div class="nta-list-box-accounts postbox" id="sortable">
		<% _.each(activeAccounts, function (account) { %>
			<div class="nta-list-items" data-index="<%= account.accountId %>" data-position="<%= account.wc_position %>">
				<div class="box-content box-content-woo">
					<div class="box-row">
						<div class="account-avatar">
							<% if (!_.isEmpty(account.avatar)) { %>
								<div class="wa_img_wrap" style="background: url(<%= account.avatar %>) center center no-repeat; background-size: cover;"></div>
							<% } else { %>
								<?php echo Helper::print_icon(); ?>
							<% } %>
						</div>
						<div class="container-block">
							<a href="<%= account.edit_link %>">
								<h4><%= account.accountName %></h4>
							</a>
							<p><%= account.title %></p>
							<p>
							<% _.each(daysOfWeek, function (day) { %>
								<% if (account.isAlwaysAvailable == 'ON') { %>
								<span class="active-date"><%= day[1] %></span>
							<% } else { %>
								<span class="<%= (account['daysOfWeekWorking'][day[0]]['isWorkingOnDay'] === 'ON') ? 'active-date' : '' %>"><%= day[1] %></span>
								<% } %>
							<% }); %>
							</p>
							<a data-remove="<%= account.accountId %>" class="btn-remove-account">Remove</a>
						</div>
						<div class="icon-block">
							<img src="<?php echo NTA_WHATSAPP_PLUGIN_URL . 'assets/img/bar-sortable.svg'; ?>" width="20px">
						</div>
					</div>
				</div>
			</div>
		<% }); %>
	</div>
	<% } %>
</script>
