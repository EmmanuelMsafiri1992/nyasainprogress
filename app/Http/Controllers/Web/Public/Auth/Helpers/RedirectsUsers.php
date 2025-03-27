<?php


namespace App\Http\Controllers\Web\Public\Auth\Helpers;

trait RedirectsUsers
{
	/**
	 * Get the post register / login redirect path.
	 *
	 * @return string
	 */
	public function redirectPath()
	{
		if (method_exists($this, 'redirectTo')) {
			return $this->redirectTo();
		}
		
		return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
	}
}
