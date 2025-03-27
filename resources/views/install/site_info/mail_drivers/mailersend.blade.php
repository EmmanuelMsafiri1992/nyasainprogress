<div class="row row-cols-2 mailersend-box">
	<div class="col">
		@include('install.helpers.form_control', [
			'type'  => 'text',
			'name'  => 'mailersend_api_key',
			'label' => trans('messages.mailersend_api_key'),
			'value' => $siteInfo['mailersend_api_key'] ?? '',
			'hint'  => trans('admin.mail_mailersend_api_key_hint'),
			'rules' => $mailRules['mailersend'] ?? [],
		])
	</div>
	<div class="col">
		@include('install.helpers.form_control', [
			'type'  => 'text',
			'name'  => 'mailersend_email_sender',
			'label' => trans('admin.mail_email_sender_label'),
			'value' => $siteInfo['mailersend_email_sender'] ?? ($siteInfo['email'] ?? ''),
			'hint'  => trans('admin.mail_email_sender_hint'),
			'rules' => $mailRules['mailersend'] ?? [],
		])
	</div>
</div>
