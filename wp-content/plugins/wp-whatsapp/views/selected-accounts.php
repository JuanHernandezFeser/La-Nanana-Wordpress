<?php
use NTA_WhatsApp\Helper;
?>
<div id="app">
</div>

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
			<div class="nta-list-items" data-index="<%= account.accountId %>" data-position="<%= account.widget_position %>">
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