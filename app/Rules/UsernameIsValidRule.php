<?php


namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UsernameIsValidRule implements ValidationRule
{
	/**
	 * Run the validation rule.
	 */
	public function validate(string $attribute, mixed $value, Closure $fail): void
	{
		if (!$this->passes($attribute, $value)) {
			$fail(trans('validation.username_is_valid_rule'));
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
		
		// Skip null value or empty string
		// Use Laravel 'required' rule for that
		if ($value == '') {
			return true;
		}
		
		return $this->isValidValue(trim(strtolower($value)));
	}
	
	/* PRIVATES */
	
	/**
	 * Determine whether the given username is composed by alphanumeric characters
	 * and not only composed by numeric characters (to prevent a phone number field).
	 *
	 * @param $value
	 * @return bool
	 */
	private function isValidValue($value): bool
	{
		return (ctype_alnum($value) && !is_numeric($value));
	}
}
