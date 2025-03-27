<?php


namespace App\Http\Controllers\Web\Public\Auth;

use App\Http\Controllers\Web\Public\Auth\Traits\VerificationTrait;
use App\Http\Requests\Front\ForgotPasswordRequest;
use App\Http\Controllers\Web\Public\FrontController;
use Larapen\LaravelMetaTags\Facades\MetaTag;

class ForgotPasswordController extends FrontController
{
	use VerificationTrait;
	
	protected $redirectTo = '/account';
	
	/**
	 * Get the middleware that should be assigned to the controller.
	 */
	public static function middleware(): array
	{
		return ['guest'];
	}
	
	// -------------------------------------------------------
	// Laravel overwrites for loading LaraClassified views
	// -------------------------------------------------------
	
	/**
	 * Display the form to request a password reset link.
	 *
	 * @return \Illuminate\Contracts\View\View
	 */
	public function showLinkRequestForm()
	{
		// Meta Tags
		[$title, $description, $keywords] = getMetaTag('password');
		MetaTag::set('title', $title);
		MetaTag::set('description', strip_tags($description));
		MetaTag::set('keywords', $keywords);
		
		return appView('auth.passwords.email');
	}
	
	/**
	 * Send a reset link to the given user.
	 *
	 * @param ForgotPasswordRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function sendResetLink(ForgotPasswordRequest $request)
	{
		// Call API endpoint
		$endpoint = '/auth/password/email';
		$data = makeApiRequest('post', $endpoint, $request->all());
		
		// Parsing the API response
		$message = data_get($data, 'message', t('unknown_error'));
		
		// Error Found
		if (!data_get($data, 'isSuccessful') || !data_get($data, 'success')) {
			return redirect()->back()
				->withInput($request->only('email'))
				->withErrors(['email' => $message]);
		}
		
		// phone
		if (data_get($data, 'extra.codeSentTo') == 'phone') {
			// Save the password reset link (in session)
			$resetPwdUrl = url('password/reset/' . data_get($data, 'extra.code'));
			session()->put('passwordNextUrl', $resetPwdUrl);
			
			// Phone Number verification
			// Get the token|code verification form page URL
			// The user is supposed to have received this token|code by SMS
			$nextUrl = url('password/verify/phone/');
			
			// Go to the verification page
			return redirect()->to($nextUrl);
		}
		
		// email
		return redirect()->back()->with(['status' => $message]);
	}
}
