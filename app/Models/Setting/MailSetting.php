<?php


namespace App\Models\Setting;

/*
 * settings.mail.option
 */

class MailSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['sendmail_path'] = env('MAIL_SENDMAIL_PATH');
			
		} else {
			
			if (!array_key_exists('sendmail_path', $value)) {
				$value['sendmail_path'] = env('MAIL_SENDMAIL_PATH');
			}
			
		}
		
		return $value;
	}
	
	public static function setValues($value, $setting)
	{
		return $value;
	}
	
	public static function getFields($diskName)
	{
		// Get Drivers List
		$mailDrivers = (array)config('larapen.options.mail');
		
		// Get the drivers selectors list as JS objects
		$mailDriversSelectorsJson = collect($mailDrivers)
			->keys()
			->mapWithKeys(fn ($item) => [$item => '.' . $item])
			->toJson();
		
		$fields = [
			[
				'name'              => 'driver',
				'label'             => trans('admin.mail_driver_label'),
				'type'              => 'select2_from_array',
				'options'           => $mailDrivers,
				'attributes'        => [
					'id' => 'driver',
				],
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
				'newline'           => true,
			],
		];
		
		// sendmail
		if (array_key_exists('sendmail', $mailDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_sendmail_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_sendmail_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 sendmail',
					],
				],
				[
					'name'              => 'driver_sendmail_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_sendmail_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 sendmail',
					],
				],
				[
					'name'              => 'sendmail_path',
					'label'             => trans('admin.sendmail_path_label'),
					'type'              => 'text',
					'hint'              => trans('admin.sendmail_path_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 sendmail',
					],
				],
				[
					'name'              => 'sendmail_email_sender',
					'label'             => trans('admin.mail_email_sender_label'),
					'type'              => 'email',
					'hint'              => trans('admin.mail_email_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 sendmail',
					],
				],
			]);
		}
		
		// smtp
		if (array_key_exists('smtp', $mailDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_smtp_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_smtp_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 smtp',
					],
				],
				[
					'name'              => 'driver_smtp_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_smtp_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 smtp',
					],
				],
				[
					'name'              => 'smtp_host',
					'label'             => trans('admin.mail_smtp_host_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_host_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 smtp',
					],
				],
				[
					'name'              => 'smtp_port',
					'label'             => trans('admin.mail_smtp_port_label'),
					'type'              => 'number',
					'hint'              => trans('admin.mail_smtp_port_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 smtp',
					],
				],
				[
					'name'              => 'smtp_username',
					'label'             => trans('admin.mail_smtp_username_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_username_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 smtp',
					],
				],
				[
					'name'              => 'smtp_password',
					'label'             => trans('admin.mail_smtp_password_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_password_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 smtp',
					],
				],
				[
					'name'              => 'smtp_encryption',
					'label'             => trans('admin.mail_smtp_encryption_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_encryption_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 smtp',
					],
				],
				[
					'name'              => 'smtp_email_sender',
					'label'             => trans('admin.mail_email_sender_label'),
					'type'              => 'email',
					'hint'              => trans('admin.mail_email_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 smtp',
					],
				],
			]);
		}
		
		// mailgun
		if (array_key_exists('mailgun', $mailDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_mailgun_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_mailgun_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 mailgun',
					],
				],
				[
					'name'              => 'driver_mailgun_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_mailgun_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 mailgun',
					],
				],
				[
					'name'              => 'mailgun_domain',
					'label'             => trans('admin.mail_mailgun_domain_label'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
				[
					'name'              => 'mailgun_secret',
					'label'             => trans('admin.mail_mailgun_secret_label'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
				[
					'name'              => 'mailgun_endpoint',
					'label'             => trans('admin.mail_mailgun_endpoint_label'),
					'type'              => 'text',
					'default'           => 'api.mailgun.net',
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
				[
					'name'              => 'mailgun_host',
					'label'             => trans('admin.mail_smtp_host_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_host_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
				[
					'name'              => 'mailgun_port',
					'label'             => trans('admin.mail_smtp_port_label'),
					'type'              => 'number',
					'hint'              => trans('admin.mail_smtp_port_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
				[
					'name'              => 'mailgun_username',
					'label'             => trans('admin.mail_smtp_username_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_username_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
				[
					'name'              => 'mailgun_password',
					'label'             => trans('admin.mail_smtp_password_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_password_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
				[
					'name'              => 'mailgun_encryption',
					'label'             => trans('admin.mail_smtp_encryption_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_encryption_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
				[
					'name'              => 'mailgun_email_sender',
					'label'             => trans('admin.mail_email_sender_label'),
					'type'              => 'email',
					'hint'              => trans('admin.mail_email_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailgun',
					],
				],
			]);
		}
		
		// postmark
		if (array_key_exists('postmark', $mailDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_postmark_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_postmark_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 postmark',
					],
				],
				[
					'name'              => 'driver_postmark_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_postmark_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 postmark',
					],
				],
				[
					'name'              => 'postmark_token',
					'label'             => trans('admin.mail_postmark_token_label'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 postmark',
					],
				],
				[
					'name'              => 'postmark_host',
					'label'             => trans('admin.mail_smtp_host_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_host_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 postmark',
					],
				],
				[
					'name'              => 'postmark_port',
					'label'             => trans('admin.mail_smtp_port_label'),
					'type'              => 'number',
					'hint'              => trans('admin.mail_smtp_port_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 postmark',
					],
				],
				[
					'name'              => 'postmark_username',
					'label'             => trans('admin.mail_smtp_username_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_username_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 postmark',
					],
				],
				[
					'name'              => 'postmark_password',
					'label'             => trans('admin.mail_smtp_password_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_password_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 postmark',
					],
				],
				[
					'name'              => 'postmark_encryption',
					'label'             => trans('admin.mail_smtp_encryption_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_encryption_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 postmark',
					],
				],
				[
					'name'              => 'postmark_email_sender',
					'label'             => trans('admin.mail_email_sender_label'),
					'type'              => 'email',
					'hint'              => trans('admin.mail_email_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 postmark',
					],
				],
			]);
		}
		
		// ses
		if (array_key_exists('ses', $mailDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_ses_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_ses_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 ses',
					],
				],
				[
					'name'              => 'driver_ses_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_ses_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 ses',
					],
				],
				[
					'name'              => 'ses_key',
					'label'             => trans('admin.mail_ses_key_label'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_secret',
					'label'             => trans('admin.mail_ses_secret_label'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_region',
					'label'             => trans('admin.mail_ses_region_label'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_token',
					'label'             => trans('admin.mail_ses_token_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_ses_token_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_host',
					'label'             => trans('admin.mail_smtp_host_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_host_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_port',
					'label'             => trans('admin.mail_smtp_port_label'),
					'type'              => 'number',
					'hint'              => trans('admin.mail_smtp_port_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_username',
					'label'             => trans('admin.mail_smtp_username_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_username_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_password',
					'label'             => trans('admin.mail_smtp_password_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_password_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_encryption',
					'label'             => trans('admin.mail_smtp_encryption_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_encryption_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
				[
					'name'              => 'ses_email_sender',
					'label'             => trans('admin.mail_email_sender_label'),
					'type'              => 'email',
					'hint'              => trans('admin.mail_email_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 ses',
					],
				],
			]);
		}
		
		// sparkpost
		if (array_key_exists('sparkpost', $mailDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_sparkpost_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_sparkpost_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 sparkpost',
					],
				],
				[
					'name'              => 'driver_sparkpost_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_sparkpost_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 sparkpost',
					],
				],
				[
					'name'              => 'sparkpost_secret',
					'label'             => trans('admin.mail_sparkpost_secret_label'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 sparkpost',
					],
				],
				[
					'name'              => 'sparkpost_host',
					'label'             => trans('admin.mail_smtp_host_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_host_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 sparkpost',
					],
				],
				[
					'name'              => 'sparkpost_port',
					'label'             => trans('admin.mail_smtp_port_label'),
					'type'              => 'number',
					'hint'              => trans('admin.mail_smtp_port_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 sparkpost',
					],
				],
				[
					'name'              => 'sparkpost_username',
					'label'             => trans('admin.mail_smtp_username_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_username_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 sparkpost',
					],
				],
				[
					'name'              => 'sparkpost_password',
					'label'             => trans('admin.mail_smtp_password_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_password_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 sparkpost',
					],
				],
				[
					'name'              => 'sparkpost_encryption',
					'label'             => trans('admin.mail_smtp_encryption_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_smtp_encryption_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 sparkpost',
					],
				],
				[
					'name'              => 'sparkpost_email_sender',
					'label'             => trans('admin.mail_email_sender_label'),
					'type'              => 'email',
					'hint'              => trans('admin.mail_email_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 sparkpost',
					],
				],
			]);
		}
		
		// resend
		if (array_key_exists('resend', $mailDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_resend_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_resend_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 resend',
					],
				],
				[
					'name'              => 'driver_resend_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_resend_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 resend',
					],
				],
				[
					'name'              => 'resend_api_key',
					'label'             => trans('admin.mail_resend_api_key_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_resend_api_key_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 resend',
					],
				],
				[
					'name'              => 'resend_email_sender',
					'label'             => trans('admin.mail_email_sender_label'),
					'type'              => 'email',
					'hint'              => trans('admin.mail_email_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 resend',
					],
				],
			]);
		}
		
		// mailersend
		if (array_key_exists('mailersend', $mailDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_mailersend_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_mailersend_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 mailersend',
					],
				],
				[
					'name'              => 'driver_mailersend_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_mailersend_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 mailersend',
					],
				],
				[
					'name'              => 'mailersend_api_key',
					'label'             => trans('admin.mail_mailersend_api_key_label'),
					'type'              => 'text',
					'hint'              => trans('admin.mail_mailersend_api_key_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailersend',
					],
				],
				[
					'name'              => 'mailersend_email_sender',
					'label'             => trans('admin.mail_email_sender_label'),
					'type'              => 'email',
					'hint'              => trans('admin.mail_email_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 mailersend',
					],
				],
			]);
		}
		
		$fields = array_merge($fields, [
			[
				'name'  => 'driver_test_title',
				'type'  => 'custom_html',
				'value' => trans('admin.driver_test_title'),
			],
			[
				'name'  => 'driver_test_info',
				'type'  => 'custom_html',
				'value' => trans('admin.card_light_inverse', [
					'content' => trans('admin.mail_driver_test_info', ['alwaysTo' => trans('admin.email_always_to_label')]),
				]),
			],
			[
				'name'              => 'driver_test',
				'label'             => trans('admin.driver_test_label'),
				'type'              => 'checkbox_switch',
				'attributes'        => [
					'id' => 'driverTest',
				],
				'hint'              => trans('admin.mail_driver_test_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 mt-2',
				],
			],
			[
				'name'              => 'email_always_to',
				'label'             => trans('admin.email_always_to_label'),
				'type'              => 'email',
				'default'           => config('settings.app.email'),
				'attributes'        => [
					'id' => 'alwaysTo',
				],
				'hint'              => trans('admin.email_always_to_hint', ['option' => trans('admin.driver_test_label')]),
				'wrapperAttributes' => [
					'class' => 'col-md-6 driver-test',
				],
			],
		]);
		
		$fields = array_merge($fields, [
			[
				'name'  => 'javascript',
				'type'  => 'custom_html',
				'value' => '<script>
let mailDriversSelectors = ' . $mailDriversSelectorsJson . ';
let mailDriversSelectorsList = Object.values(mailDriversSelectors);

onDocumentReady((event) => {
	/* Driver Selection (select2) */
	let driverElSelector = "#driver";
	let driverEl = document.querySelector(driverElSelector);
	getDriverFields(driverEl);
	/* Vanilla JS is not used since the select2 plugin is built with jQuery */
	$(driverElSelector).on("change", function (event) {
		getDriverFields(this);
	});
	
	/* Driver Test Checking (checkbox) */
	let driverTestEl = document.querySelector("#driverTest");
	applyDriverTestChanges(driverTestEl, event.type);
	driverTestEl.addEventListener("change", (event) => {
		applyDriverTestChanges(event.target, event.type);
	});
	
	/* Mail Always To (input[type=email]) */
	let alwaysToEl = document.querySelector("#alwaysTo");
	alwaysToEl.addEventListener("blur", (event) => {
		applyDriverTestChanges(driverTestEl, event.type);
	});
}, true);

function getDriverFields(driverEl) {
	setElementsVisibility("hide", mailDriversSelectorsList);
	setElementsVisibility("show", mailDriversSelectors[driverEl.value] ?? "");
}

function applyDriverTestChanges(driverTestEl, eventType) {
	let driverTestElSelector = ".driver-test";
	let alwaysToEl = document.querySelector("#alwaysTo");
	
	let alertMessage;
	if (driverTestEl.checked) {
		setElementsVisibility("show", driverTestElSelector);
		
		if (eventType !== "DOMContentLoaded") {
			const alwaysToValue = alwaysToEl.value;
			if (alwaysToValue != "" && isEmailAddress(alwaysToValue)) {
				alertMessage = () => {
					return `' . trans('admin.email_always_to_activated') . '`
				};
				pnAlert(alertMessage(), "notice");
			} else {
				alertMessage = "' . trans('admin.email_to_admin_activated') . '";
				pnAlert(alertMessage, "info");
			}
		}
	}
	if (!driverTestEl.checked) {
		setElementsVisibility("hide", driverTestElSelector);
		
		if (eventType !== "DOMContentLoaded") {
			alertMessage = "' . trans('admin.email_always_to_disabled') . '";
			pnAlert(alertMessage, "info");
		}
	}
}
</script>',
			],
		]);
		
		$fields = array_merge($fields, [
			[
				'name'  => 'mail_notification_types_title',
				'type'  => 'custom_html',
				'value' => trans('admin.mail_notification_types_title'),
			],
			[
				'name'  => 'email_verification',
				'label' => trans('admin.email_verification_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.email_verification_hint'),
			],
			[
				'name'  => 'confirmation',
				'label' => trans('admin.settings_mail_confirmation_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.settings_mail_confirmation_hint'),
			],
			[
				'name'  => 'admin_notification',
				'label' => trans('admin.settings_mail_admin_notification_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.settings_mail_admin_notification_hint'),
			],
			[
				'name'  => 'payment_notification',
				'label' => trans('admin.settings_mail_payment_notification_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.settings_mail_payment_notification_hint'),
			],
		]);
		
		return $fields;
	}
}
