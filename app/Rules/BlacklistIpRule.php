<?php


namespace App\Rules;

use App\Models\Blacklist;
use App\Helpers\Ip;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BlacklistIpRule implements ValidationRule
{
	/**
	 * Run the validation rule.
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!$this->passes($attribute, $value)) {
			$fail(trans('validation.blacklist_ip_rule'));
		}
	}
	
	/**
	 * Determine if the validation rule passes.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 * @todo: THIS RULE IS NOT USED IN THE APP.
	 */
	public function passes(string $attribute, mixed $value): bool
	{
		$ip = Ip::get();
		$blacklisted = Blacklist::ofType('ip')->where('entry', $ip)->first();
		
		return empty($blacklisted);
	}
}
