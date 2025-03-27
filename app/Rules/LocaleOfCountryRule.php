<?php


namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LocaleOfCountryRule implements ValidationRule
{
	public ?string $countryCode = null;
	
	public function __construct(?string $countryCode)
	{
		$this->countryCode = $countryCode;
	}
	
	/**
	 * Run the validation rule.
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!$this->passes($attribute, $value)) {
			$fail(trans('validation.locale_of_country_rule'));
		}
	}
	
	/**
	 * Determine if the validation rule passes.
	 * Check the Locale related to the Country Code.
	 *
	 * @param string $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes(string $attribute, mixed $value): bool
	{
		$countryCode = $this->countryCode;
		$locales = getLocalesWithName();
		
		$filtered = collect($locales)
			->filter(function ($name, $locale) use ($countryCode) {
				return str_ends_with($locale, '_' . $countryCode);
			});
		
		if ($filtered->isNotEmpty()) {
			return str_ends_with($value, '_' . $countryCode);
		}
		
		return isset($locales[$value]);
	}
}
