<?php


namespace App\Http\Controllers\Web\Public\Post\CreateOrEdit\SingleStep;

use App\Helpers\Referrer;
use App\Helpers\UrlGen;
use App\Http\Controllers\Api\Payment\HasPaymentTrigger;
use App\Http\Controllers\Api\Payment\Promotion\SingleStepPayment;
use App\Http\Controllers\Api\Payment\HasPaymentReferrers;
use App\Http\Controllers\Web\Public\Auth\Traits\VerificationTrait;
use App\Http\Controllers\Web\Public\Payment\HasPaymentRedirection;
use App\Http\Controllers\Web\Public\Post\CreateOrEdit\Traits\PricingPageUrlTrait;
use App\Http\Requests\Front\PostRequest;
use App\Models\Post;
use App\Models\Package;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Http\Controllers\Web\Public\FrontController;
use Illuminate\Database\Eloquent\Collection;
use Larapen\LaravelMetaTags\Facades\MetaTag;

class CreateController extends FrontController
{
	use VerificationTrait;
	use HasPaymentReferrers;
	use HasPaymentTrigger, SingleStepPayment, HasPaymentRedirection;
	use PricingPageUrlTrait;
	
	public $request;
	public $data;
	
	// Payment's properties
	public array $msg = [];
	public array $uri = [];
	public ?Package $selectedPackage = null; // See SingleStepPaymentTrait::setPaymentSettingsForPromotion()
	public Collection $packages;
	public Collection $paymentMethods;
	
	/**
	 * CreateController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->commonQueries();
	}
	
	/**
	 * Get the middleware that should be assigned to the controller.
	 */
	public static function middleware(): array
	{
		$array = [];
		
		// Check if guests can post Ads
		if (config('settings.listing_form.guest_can_submit_listings') != '1') {
			$array[] = 'auth';
		}
		
		return $array;
	}
	
	/**
	 * @return void
	 */
	public function commonQueries(): void
	{
		$this->getPaymentReferrersData();
		$this->setPaymentSettingsForPromotion();
		
		// References
		$data = [];
		
		// Get postTypes
		$data['postTypes'] = Referrer::getPostTypes($this->cacheExpiration);
		view()->share('postTypes', $data['postTypes']);
		
		// Get Salary Types
		$data['salaryTypes'] = Referrer::getSalaryTypes($this->cacheExpiration);
		view()->share('salaryTypes', $data['salaryTypes']);
		
		if (auth()->check()) {
			// Get the User's X latest Companies
			$data['companies'] = Referrer::getUsersCompanies($this->cacheExpiration);
			view()->share('companies', $data['companies']);
			
			// Get the User's latest Company
			$companies = collect($data['companies']);
			if ($companies->has(0)) {
				$data['postCompany'] = $companies->get(0);
				view()->share('postCompany', $data['postCompany']);
			}
		}
		
		// Save common's data
		$this->data = $data;
	}
	
	/**
	 * New Post's Form.
	 *
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function getForm()
	{
		// Check if the 'Pricing Page' must be started first, and make redirection to it.
		$pricingUrl = $this->getPricingPage($this->selectedPackage);
		if (!empty($pricingUrl)) {
			return redirect()->to($pricingUrl)->withHeaders(config('larapen.core.noCacheHeaders'));
		}
		
		// Check if the form type is 'Multi-Step Form' and make redirection to it (permanently).
		$isMultiStepFormEnabled = (config('settings.listing_form.publication_form_type') == '1');
		if ($isMultiStepFormEnabled) {
			$url = url('posts/create');
			
			return redirect()->to($url, 301)->withHeaders(config('larapen.core.noCacheHeaders'));
		}
		
		// Only Admin users and Employers/Companies can post ads
		if (auth()->check()) {
			$isCandidateAccount = (auth()->user()->user_type_id != 1);
			if ($isCandidateAccount) {
				return redirect()->intended('account');
			}
		}
		
		// Meta Tags
		[$title, $description, $keywords] = getMetaTag('create');
		MetaTag::set('title', $title);
		MetaTag::set('description', strip_tags($description));
		MetaTag::set('keywords', $keywords);
		
		// Create
		return appView('post.createOrEdit.singleStep.create');
	}
	
	/**
	 * Store a new Post.
	 *
	 * @param \App\Http\Requests\Front\PostRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postForm(PostRequest $request)
	{
		// Call API endpoint
		$endpoint = '/posts';
		$data = makeApiRequest('post', $endpoint, $request->all(), [], true);
		
		// Parsing the API response
		$message = data_get($data, 'message', t('unknown_error'));
		
		// HTTP Error Found
		if (!data_get($data, 'isSuccessful')) {
			flash($message)->error();
			
			if (data_get($data, 'extra.previousUrl')) {
				return redirect()->to(data_get($data, 'extra.previousUrl'))->withInput($request->except('company.logo'));
			} else {
				return redirect()->back()->withInput($request->except('company.logo'));
			}
		}
		
		// Notification Message
		if (data_get($data, 'success')) {
			session()->put('message', $message);
		} else {
			flash($message)->error();
		}
		
		// Get Listing Resource
		$post = data_get($data, 'result');
		
		abort_if(empty($post), 404, t('post_not_found'));
		
		// Get the Next URL
		$nextUrl = url('create/finish');
		
		// Get the listing ID
		$postId = data_get($data, 'result.id');
		
		// Check if the payment process has been triggered
		// NOTE: Payment bypass email or phone verification
		// ===| Make|send payment (if needed) |==============
		
		$postObj = $this->retrievePayableModel($request, $postId);
		abort_if(empty($postObj), 404, t('post_not_found'));
		
		$payResult = $this->isPaymentRequested($request, $postObj);
		if (data_get($payResult, 'success')) {
			return $this->sendPayment($request, $postObj);
		}
		if (data_get($payResult, 'failure')) {
			flash(data_get($payResult, 'message'))->error();
		}
		
		// ===| If no payment is made (continue) |===========
		
		if (
			data_get($data, 'extra.sendEmailVerification.emailVerificationSent')
			|| data_get($data, 'extra.sendPhoneVerification.phoneVerificationSent')
		) {
			$nextUrl = qsUrl($nextUrl, request()->only(['package']), null, false);
			session()->put('itemNextUrl', $nextUrl);
			
			if (data_get($data, 'extra.sendEmailVerification.emailVerificationSent')) {
				session()->put('emailVerificationSent', true);
				
				// Show the Re-send link
				$this->showReSendVerificationEmailLink($post, 'posts');
			}
			
			if (data_get($data, 'extra.sendPhoneVerification.phoneVerificationSent')) {
				session()->put('phoneVerificationSent', true);
				
				// Show the Re-send link
				$this->showReSendVerificationSmsLink($post, 'posts');
				
				// Phone Number verification
				// Get the token|code verification form page URL
				// The user is supposed to have received this token|code by SMS
				$nextUrl = url('posts/verify/phone/');
			}
		}
		
		// Mail Notification Message
		if (data_get($data, 'extra.mail.message')) {
			$mailMessage = data_get($data, 'extra.mail.message');
			if (data_get($data, 'extra.mail.success')) {
				flash($mailMessage)->success();
			} else {
				flash($mailMessage)->error();
			}
		}
		
		$nextUrl = qsUrl($nextUrl, request()->only(['package']), null, false);
		
		return redirect()->to($nextUrl);
	}
	
	/**
	 * Confirmation
	 *
	 * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function finish()
	{
		if (!session()->has('message')) {
			return redirect()->to('/');
		}
		
		// Clear Session
		if (session()->has('itemNextUrl')) {
			session()->forget('itemNextUrl');
		}
		
		if (session()->has('postId')) {
			// Get the Post
			$post = Post::query()
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('postId'))
				->first();
			
			abort_if(empty($post), 404, t('post_not_found'));
			
			session()->forget('postId');
		}
		
		// Redirect to the Post,
		// - If User is logged
		// - Or if Email and Phone verification option is not activated
		if (auth()->check() || (config('settings.mail.email_verification') != 1 && config('settings.sms.phone_verification') != 1)) {
			if (!empty($post)) {
				flash(session('message'))->success();
				
				return redirect()->to(UrlGen::postUri($post));
			}
		}
		
		// Meta Tags
		MetaTag::set('title', session('message'));
		MetaTag::set('description', session('message'));
		
		return appView('post.createOrEdit.singleStep.finish');
	}
}
