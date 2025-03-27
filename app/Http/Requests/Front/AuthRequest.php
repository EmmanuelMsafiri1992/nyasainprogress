<?php


namespace App\Http\Requests\Front;

use App\Http\Requests\Request;

class AuthRequest extends Request
{
	/**
	 * Prepare the data for validation.
	 *
	 * @return void
	 */
	protected function prepareForValidation()
	{
		$input = $this->all();
		
		// auth_field
		$input['auth_field'] = getAuthField();
		
		// phone
		if ($this->filled('phone')) {
			$input['phone'] = phoneE164($this->input('phone'), getPhoneCountry());
		}
		
		request()->merge($input); // Required!
		$this->merge($input);
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [];
		
		$phoneIsEnabledAsAuthField = (config('settings.sms.enable_phone_as_auth_field') == '1');
		
		if ($phoneIsEnabledAsAuthField) {
			$authField = $this->input('auth_field');
			if (!empty($authField)) {
				$rules[$authField] = ['required'];
				
				if ($authField == 'phone') {
					$rules['phone_country'] = ['required_with:phone'];
				}
			} else {
				$rules['email'] = ['required'];
			}
		} else {
			$rules['email'] = ['required'];
		}
		
		return $this->captchaRules($rules);
	}
}
