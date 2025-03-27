<?php


namespace App\Models\Setting;

/*
 * settings.sms.option
 */

class SmsSetting
{
	public static function getValues($value, $disk)
	{
		if (empty($value)) {
			
			$value['phone_of_countries'] = 'local';
			
			$value['vonage_key'] = env('VONAGE_KEY', '');
			$value['vonage_secret'] = env('VONAGE_SECRET', '');
			$value['vonage_application_id'] = env('VONAGE_APPLICATION_ID', '');
			$value['vonage_from'] = env('VONAGE_SMS_FROM', '');
			
			$value['twilio_username'] = env('TWILIO_USERNAME', '');
			$value['twilio_password'] = env('TWILIO_PASSWORD', '');
			$value['twilio_auth_token'] = env('TWILIO_AUTH_TOKEN', '');
			$value['twilio_account_sid'] = env('TWILIO_ACCOUNT_SID', '');
			$value['twilio_from'] = env('TWILIO_FROM', '');
			$value['twilio_alpha_sender'] = env('TWILIO_ALPHA_SENDER', '');
			$value['twilio_sms_service_sid'] = env('TWILIO_SMS_SERVICE_SID', '');
			$value['twilio_debug_to'] = env('TWILIO_DEBUG_TO', '');
			
			$value['phone_verification'] = '1';
			
		} else {
			
			if (!array_key_exists('phone_of_countries', $value)) {
				$value['phone_of_countries'] = 'local';
			}
			
			if (!array_key_exists('enable_phone_as_auth_field', $value)) {
				$value['enable_phone_as_auth_field'] = env('DISABLE_PHONE') ? '0' : '1'; // from old method
			}
			if (!array_key_exists('vonage_key', $value)) {
				$value['vonage_key'] = env('VONAGE_KEY', '');
			}
			if (!array_key_exists('vonage_secret', $value)) {
				$value['vonage_secret'] = env('VONAGE_SECRET', '');
			}
			if (!array_key_exists('vonage_application_id', $value)) {
				$value['vonage_application_id'] = env('VONAGE_APPLICATION_ID', '');
			}
			if (!array_key_exists('vonage_from', $value)) {
				$value['vonage_from'] = env('VONAGE_SMS_FROM', '');
			}
			
			if (!array_key_exists('twilio_username', $value)) {
				$value['twilio_username'] = env('TWILIO_USERNAME', '');
			}
			if (!array_key_exists('twilio_password', $value)) {
				$value['twilio_password'] = env('TWILIO_PASSWORD', '');
			}
			if (!array_key_exists('twilio_auth_token', $value)) {
				$value['twilio_auth_token'] = env('TWILIO_AUTH_TOKEN', '');
			}
			if (!array_key_exists('twilio_account_sid', $value)) {
				$value['twilio_account_sid'] = env('TWILIO_ACCOUNT_SID', '');
			}
			if (!array_key_exists('twilio_from', $value)) {
				$value['twilio_from'] = env('TWILIO_FROM', '');
			}
			if (!array_key_exists('twilio_alpha_sender', $value)) {
				$value['twilio_alpha_sender'] = env('TWILIO_ALPHA_SENDER', '');
			}
			if (!array_key_exists('twilio_sms_service_sid', $value)) {
				$value['twilio_sms_service_sid'] = env('TWILIO_SMS_SERVICE_SID', '');
			}
			if (!array_key_exists('twilio_debug_to', $value)) {
				$value['twilio_debug_to'] = env('TWILIO_DEBUG_TO', '');
			}
			
			if (!array_key_exists('phone_verification', $value)) {
				$value['phone_verification'] = '1';
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
		$smsDrivers = (array)config('larapen.options.sms');
		
		// Get the drivers selectors list as JS objects
		$smsDriversSelectorsJson = collect($smsDrivers)
			->keys()
			->mapWithKeys(fn ($item) => [$item => '.' . $item])
			->toJson();
		
		$fields = [
			[
				'name'              => 'enable_phone_as_auth_field',
				'label'             => trans('admin.enable_phone_as_auth_field_label'),
				'type'              => 'checkbox_switch',
				'attributes'        => [
					'id' => 'phoneAsAuthField',
				],
				'hint'              => trans('admin.enable_phone_as_auth_field_hint', [
					'phone_verification_label' => trans('admin.phone_verification_label'),
				]),
				'wrapperAttributes' => [
					'class' => 'col-md-6 mt-3',
				],
			],
			[
				'name'              => 'phone_of_countries',
				'label'             => trans('admin.phone_of_countries_label'),
				'type'              => 'select2_from_array',
				'options'           => [
					'local'     => trans('admin.phone_of_countries_op_1'),
					'activated' => trans('admin.phone_of_countries_op_2'),
					'all'       => trans('admin.phone_of_countries_op_3'),
				],
				'hint'              => trans('admin.phone_of_countries_hint', [
					'local'     => trans('admin.phone_of_countries_op_1'),
					'activated' => trans('admin.phone_of_countries_op_2'),
					'all'       => trans('admin.phone_of_countries_op_3'),
				]),
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
			],
			
			[
				'name'              => 'driver',
				'label'             => trans('admin.SMS Driver'),
				'type'              => 'select2_from_array',
				'options'           => $smsDrivers,
				'attributes'        => [
					'id' => 'driver',
				],
				'wrapperAttributes' => [
					'class' => 'col-md-6',
				],
				'newline'           => true,
			],
		];
		
		// vonage
		if (array_key_exists('vonage', $smsDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_vonage_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_vonage_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 vonage',
					],
				],
				[
					'name'              => 'driver_vonage_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_vonage_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 vonage',
					],
				],
				[
					'name'              => 'vonage_key',
					'label'             => trans('admin.Vonage Key'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 vonage',
					],
				],
				[
					'name'              => 'vonage_secret',
					'label'             => trans('admin.Vonage Secret'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 vonage',
					],
				],
				[
					'name'              => 'vonage_application_id',
					'label'             => trans('admin.vonage_application_id'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 vonage',
					],
				],
				[
					'name'              => 'vonage_from',
					'label'             => trans('admin.Vonage From'),
					'type'              => 'text',
					'wrapperAttributes' => [
						'class' => 'col-md-6 vonage',
					],
				],
			]);
		}
		
		// twilio
		if (array_key_exists('twilio', $smsDrivers)) {
			$fields = array_merge($fields, [
				[
					'name'              => 'driver_twilio_title',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_twilio_title'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 twilio',
					],
				],
				[
					'name'              => 'driver_twilio_info',
					'type'              => 'custom_html',
					'value'             => trans('admin.driver_twilio_info'),
					'wrapperAttributes' => [
						'class' => 'col-md-12 twilio',
					],
				],
				[
					'name'              => 'twilio_username',
					'label'             => trans('admin.twilio_username_label'),
					'type'              => 'text',
					'hint'              => trans('admin.twilio_username_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'              => 'twilio_password',
					'label'             => trans('admin.twilio_password_label'),
					'type'              => 'text',
					'hint'              => trans('admin.twilio_password_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'              => 'twilio_account_sid',
					'label'             => trans('admin.twilio_account_sid_label'),
					'type'              => 'text',
					'hint'              => trans('admin.twilio_account_sid_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'              => 'twilio_auth_token',
					'label'             => trans('admin.twilio_auth_token_label'),
					'type'              => 'text',
					'hint'              => trans('admin.twilio_auth_token_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'              => 'twilio_from',
					'label'             => trans('admin.twilio_from_label'),
					'type'              => 'text',
					'hint'              => trans('admin.twilio_from_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'              => 'twilio_alpha_sender',
					'label'             => trans('admin.twilio_alpha_sender_label'),
					'type'              => 'text',
					'hint'              => trans('admin.twilio_alpha_sender_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'              => 'twilio_sms_service_sid',
					'label'             => trans('admin.twilio_sms_service_sid_label'),
					'type'              => 'text',
					'hint'              => trans('admin.twilio_sms_service_sid_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 twilio',
					],
				],
				[
					'name'              => 'twilio_debug_to',
					'label'             => trans('admin.twilio_debug_to_label'),
					'type'              => 'text',
					'hint'              => trans('admin.twilio_debug_to_hint'),
					'wrapperAttributes' => [
						'class' => 'col-md-6 twilio',
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
					'content' => trans('admin.sms_driver_test_info', ['smsTo' => trans('admin.sms_to_label')]),
				]),
			],
			[
				'name'              => 'driver_test',
				'label'             => trans('admin.driver_test_label'),
				'type'              => 'checkbox_switch',
				'attributes'        => [
					'id' => 'driverTest',
				],
				'hint'              => trans('admin.sms_driver_test_hint'),
				'wrapperAttributes' => [
					'class' => 'col-md-6 mt-2',
				],
			],
			[
				'name'              => 'sms_to',
				'label'             => trans('admin.sms_to_label'),
				'type'              => 'tel',
				'default'           => config('settings.app.phone_number'),
				'attributes'        => [
					'id' => 'smsTo',
				],
				'hint'              => trans('admin.sms_to_hint', ['option' => trans('admin.driver_test_label')]),
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
let smsDriversSelectors = ' . $smsDriversSelectorsJson . ';
let smsDriversSelectorsList = Object.values(smsDriversSelectors);

onDocumentReady(function(event) {
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
	
	/* SMS To (input[type=tel]) */
	let smsToEl = document.querySelector("#smsTo");
	smsToEl.addEventListener("blur", (event) => {
		applyDriverTestChanges(driverTestEl, event.type);
	});
}, true);

function getDriverFields(driverEl) {
	setElementsVisibility("hide", smsDriversSelectorsList);
	setElementsVisibility("show", smsDriversSelectors[driverEl.value] ?? "");
}

function applyDriverTestChanges(driverTestEl, eventType) {
	let driverTestElSelector = ".driver-test";
	let smsToEl = document.querySelector("#smsTo");
	
	if (driverTestEl.checked) {
		setElementsVisibility("show", driverTestElSelector);
		
		if (eventType !== "DOMContentLoaded") {
			const smsToValue = smsToEl.value;
			if (smsToValue != "") {
				const fnAlertMessage = () => {
					return `' . trans('admin.sms_to_activated') . '`
				};
				pnAlert(fnAlertMessage(), "info");
			} else {
				let alertMessage = "' . trans('admin.sms_to_admin_activated') . '";
				pnAlert(alertMessage, "info");
			}
		}
	}
	if (!driverTestEl.checked) {
		setElementsVisibility("hide", driverTestElSelector);
		
		if (eventType !== "DOMContentLoaded") {
			let alertMessage = "' . trans('admin.sms_to_disabled') . '";
			pnAlert(alertMessage, "info");
		}
	}
}
</script>',
			],
		]);
		
		$fields = array_merge($fields, [
			[
				'name'  => 'sms_notification_types_title',
				'type'  => 'custom_html',
				'value' => trans('admin.sms_notification_types_title'),
			],
			[
				'name'  => 'phone_verification',
				'label' => trans('admin.phone_verification_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.phone_verification_hint', ['email_verification_label' => trans('admin.email_verification_label')])
					. '<br>' . trans('admin.sms_sending_requirements'),
			],
			[
				'name'  => 'confirmation',
				'label' => trans('admin.settings_sms_confirmation_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.settings_sms_confirmation_hint') . '<br>' . trans('admin.sms_sending_requirements'),
			],
			[
				'name'  => 'messenger_notifications',
				'label' => trans('admin.messenger_notifications_label'),
				'type'  => 'checkbox_switch',
				'hint'  => trans('admin.messenger_notifications_hint') . '<br>' . trans('admin.sms_sending_requirements'),
			],
			
			[
				'name'              => 'default_auth_field_sep',
				'type'              => 'custom_html',
				'value'             => '<hr style="border: 1px dashed #EFEFEF;" class="my-3">',
				'wrapperAttributes' => [
					'class' => 'col-12 auth-field-el',
				],
			],
			[
				'name'              => 'default_auth_field',
				'label'             => trans('admin.default_auth_field_label'),
				'type'              => 'select_from_array',
				'options'           => [
					'email' => t('email_address'),
					'phone' => t('phone_number'),
				],
				'default'           => 'email',
				'attributes'        => [
					'id' => 'defaultAuthField',
				],
				'hint'              => trans('admin.default_auth_field_hint', [
					'enable_phone_as_auth_field_label' => trans('admin.enable_phone_as_auth_field_label'),
					'email'                            => t('email_address'),
					'phone'                            => t('phone_number'),
				]),
				'wrapperAttributes' => [
					'class' => 'col-md-6 auth-field-el',
				],
			],
		]);
		
		$fields = array_merge($fields, [
			[
				'name'  => 'javascript_auth_field',
				'type'  => 'custom_html',
				'value' => '<script>
onDocumentReady((event) => {
	let phoneAsAuthFieldEl = document.querySelector("#phoneAsAuthField");
	enablePhoneNumberAsAuthField(phoneAsAuthFieldEl);
	phoneAsAuthFieldEl.addEventListener("change", (event) => {
		enablePhoneNumberAsAuthField(event.target);
	});
});

function enablePhoneNumberAsAuthField(phoneAsAuthFieldEl) {
	if (phoneAsAuthFieldEl.checked) {
		setElementsVisibility("show", ".auth-field-el");
	} else {
		setDefaultAuthField();
		setElementsVisibility("hide", ".auth-field-el");
	}
}
function setDefaultAuthField(defaultValue = "email") {
	let defaultAuthFieldEl = document.querySelector("#defaultAuthField");
	defaultAuthFieldEl.value = defaultValue;
}
</script>',
			],
		]);
		
		return $fields;
	}
}
