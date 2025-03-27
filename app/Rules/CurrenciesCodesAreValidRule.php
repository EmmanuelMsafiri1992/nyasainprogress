<?php


namespace App\Rules;

use App\Models\Currency;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CurrenciesCodesAreValidRule implements ValidationRule
{
	/**
	 * Run the validation rule.
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!$this->passes($attribute, $value)) {
			$fail(trans('validation.currencies_codes_are_valid_rule'));
		}
	}
	
	/**
	 * Determine if the validation rule passes.
	 * Check if each the Currency Code in the list is valid.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes(string $attribute, mixed $value): bool
	{
		$value = getAsString($value);
		
		$valid = true;
		
		$currenciesCodes = explode(',', $value);
		if (!empty($currenciesCodes)) {
			foreach ($currenciesCodes as $code) {
				if (Currency::where('code', $code)->count() <= 0) {
					$valid = false;
					break;
				}
			}
		}
		
		return $valid;
	}
}
