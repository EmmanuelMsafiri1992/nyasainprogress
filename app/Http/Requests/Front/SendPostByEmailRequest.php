<?php


namespace App\Http\Requests\Front;

use App\Http\Requests\Request;
use App\Rules\BetweenRule;
use App\Rules\EmailRule;

class SendPostByEmailRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'sender_email'    => ['required', 'email', new EmailRule(), 'max:100'],
			'recipient_email' => ['required', 'email', new EmailRule(), 'max:100'],
			//'message' 	  => ['required', new BetweenRule(20, 500)],
		];
		
		return $rules;
	}
}
