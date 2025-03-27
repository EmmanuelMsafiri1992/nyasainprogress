<?php


namespace App\Http\Controllers\Web\Admin\Auth;

use App\Http\Controllers\Web\Admin\Controller;

class ForgotPasswordController extends Controller
{
	/**
	 * Get the middleware that should be assigned to the controller.
	 */
	public static function middleware(): array
	{
		return ['guest'];
	}
	
	// -------------------------------------------------------
	// Laravel overwrites for loading admin views
	// -------------------------------------------------------
	
	/**
	 * Display the form to request a password reset link.
	 * NOTE: Not used with this admin theme.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function showLinkRequestForm()
	{
		return appView('admin.auth.passwords.email', ['title' => trans('admin.reset_password')]);
	}
}
