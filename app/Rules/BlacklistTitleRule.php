<?php


namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BlacklistTitleRule implements ValidationRule
{
	/**
	 * Run the validation rule.
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!$this->passes($attribute, $value)) {
			$fail(trans('validation.blacklist_title_rule'));
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
		$value = getAsString($value);
		
		$blacklistWord = new BlacklistWordRule();
		if (!$blacklistWord->passes($attribute, $value)) {
			return false;
		}
		
		// Banned all domain name from title
		$tlds = getTopLevelDomainRefList();
		if (!empty($tlds)) {
			foreach ($tlds as $tld => $label) {
				if (str_contains($value, '.' . strtolower($tld))) {
					return false;
				}
			}
		}
		
		return true;
	}
}
