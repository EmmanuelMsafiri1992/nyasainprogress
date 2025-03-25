<?php
/*
 * JobClass - Job Board Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com/jobclass
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements ValidationRule
{
	public ?string $countryCode;
	
	public function __construct(?string $countryCode = null)
	{
		$this->countryCode = $countryCode;
	}
	
	/**
	 * Run the validation rule.
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!$this->passes($attribute, $value)) {
			$fail(trans('validation.phone'));
		}
	}
	
	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes(string $attribute, mixed $value): bool
	{
		/*
		$smsSendingIsRequired = (
			config('settings.sms.enable_phone_as_auth_field') == '1'
			&& (
				config('settings.sms.phone_verification') == '1'
				|| config('settings.sms.confirmation') == '1'
				|| config('settings.sms.messenger_notifications') == '1'
			)
		);
		*/
		$phoneValidator = getAsString(config('settings.sms.phone_validator'));
		if ($phoneValidator == 'none' || empty($phoneValidator)) return true;
		
		if (!function_exists($phoneValidator)) return false;
		
		$value = getAsString($value);
		$countryCode = $this->countryCode ?? getPhoneCountry();
		
		return $phoneValidator($value, $countryCode);
	}
}
