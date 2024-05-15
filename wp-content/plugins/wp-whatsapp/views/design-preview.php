<?php
use NTA_WhatsApp\Helper;
?>
<div id="app-preview">
</div>

<script type="text/template" id="widget-preview">
	<div class="wa__btn_popup <%= settings.isLaunch ? 'wa__active' : '' %>">
		<div class="wa__btn_popup_txt"><%= settings.btnLabel %></div>
		<div class="wa__btn_popup_icon"></div>
	</div>
	<div class="wa__popup_chat_box <%= settings.isLaunch ? 'wa__active wa__pending wa__lauch' : '' %>">
	<div class="wa__popup_heading">
		<div class="wa__popup_title"><%= settings.title %></div>
		<div class="wa__popup_intro"><%= settings.description %></div>
	</div>
	<!-- /.wa__popup_heading -->
	<div class="wa__popup_content wa__popup_content_left">
		<div class="wa__popup_notice"><%= settings.responseText %></div>
		<% if (settings.isShowGDPR) { %>
			<div class="nta-wa-gdpr"><input id="nta-wa-gdpr" type="checkbox" value="accept">
				<label for="nta-wa-gdpr"><%= settings.gdprContent %></label>
			</div>
			<% }  
		%>
		<div class="wa__popup_content_list">
			<% _.each(accounts, function (account) { %>
			<div class="wa__popup_content_item">
				<a class="wa__stt <%= account.status === 'online' ? 'wa__stt_online' : 'wa__stt_offline' %>">
					<% if (!_.isEmpty(account.avatar)) { %>
						<div class="wa__popup_avatar">
							<div class="wa__cs_img_wrap" style="background: url(<%= account.avatar %>) center center no-repeat; background-size: cover;"></div>
						</div>
					<% } else { %>
						<div class="wa__popup_avatar nta-default-avt">
							<?php echo Helper::print_icon(); ?>
						</div>
					<% } %>
					<div class="wa__popup_txt">
						<div class="wa__member_name"><%= account.accountName %></div>
						<!-- /.wa__member_name -->
						<div class="wa__member_duty"><%= account.title %></div>
						<!-- /.wa__member_duty -->
						<% if (account.status !== 'online') { %>
						<div class="wa__member_status">
							<%= account.status %>
						</div>
						<% } %>
					</div>
					<!-- /.wa__popup_txt -->
				</a>
			</div>
			<% }); %>
		</div>
	</div>
</div>
</script>