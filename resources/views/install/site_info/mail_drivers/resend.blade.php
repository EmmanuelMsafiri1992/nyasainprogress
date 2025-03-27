<div class="row row-cols-2 resend-box">
	<div class="col">
		@include('install.helpers.form_control', [
			'type'  => 'text',
			'name'  => 'resend_api_key',
			'label' => trans('messages.resend_api_key'),
			'value' => $siteInfo['resend_api_key'] ?? '',
			'hint'  => trans('admin.mail_resend_api_key_hint'),
			'rules' => $mailRules['resend'] ?? [],
		])
	</div>
	<div class="col">
		@include('install.helpers.form_control', [
			'type'  => 'text',
			'name'  => 'resend_email_sender',
			'label' => trans('admin.mail_email_sender_label'),
			'value' => $siteInfo['resend_email_sender'] ?? ($siteInfo['email'] ?? ''),
			'hint'  => trans('admin.mail_email_sender_hint'),
			'rules' => $mailRules['resend'] ?? [],
		])
	</div>
</div>
