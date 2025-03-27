<?php


namespace App\Http\Controllers\Web\Public\Traits;

use App\Helpers\Cookie;
use App\Models\Advertising;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Models\Permission;
use ChrisKonnertz\OpenGraph\OpenGraph;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use App\Helpers\Localization\Country as CountryLocalization;
use Larapen\LaravelMetaTags\Facades\MetaTag;

trait SettingsTrait
{
	public int $cacheExpiration = 3600; // In seconds (e.g.: 60 * 60 for 1h)
	public int $cookieExpiration = 3600; // In seconds (e.g.: 60 * 60 for 1h)
	
	public ?Collection $countries = null;
	
	public EloquentCollection $paymentMethods;
	public int $countPaymentMethods = 0;
	
	public OpenGraph $og;
	
	/**
	 * Set all the front-end settings
	 *
	 * @return void
	 */
	public function applyFrontSettings(): void
	{
		// Cache Expiration Time
		$this->cacheExpiration = (int)config('settings.optimization.cache_expiration');
		view()->share('cacheExpiration', $this->cacheExpiration);
		
		// Cookie Expiration Time
		$this->cookieExpiration = (int)config('settings.other.cookie_expiration');
		view()->share('cookieExpiration', $this->cookieExpiration);
		
		// Share auth user & his role in views
		$authUser = auth()->user();
		view()->share('authUser', $authUser);
		view()->share('authUserIsAdmin', doesUserHavePermission($authUser, Permission::getStaffPermissions()));
		
		// Meta Tags & Open Graph
		if (!request()->expectsJson()) {
			// Meta Tags
			[$title, $description, $keywords] = getMetaTag('home');
			MetaTag::set('title', $title);
			MetaTag::set('description', strip_tags($description));
			MetaTag::set('keywords', $keywords);
			
			// Open Graph
			$this->og = new OpenGraph();
			$locale = config('app.locale', 'en_US');
			try {
				$this->og->siteName(config('settings.app.name', 'Site Name'))
					->locale($locale)
					->type('website')
					->url(rawurldecode(url()->current()));
				
				$ogImageUrl = '';
				if (!empty(config('settings.seo.og_image_url'))) {
					$ogImageUrl = config('settings.seo.og_image_url');
				}
				if (!empty($ogImageUrl)) {
					$this->og->image($ogImageUrl, [
						'width'  => (int)config('settings.seo.og_image_width', 1200),
						'height' => (int)config('settings.seo.og_image_height', 630),
					]);
				}
			} catch (\Throwable $e) {
			}
			view()->share('og', $this->og);
		}
		
		// CSRF Control
		// CSRF - Some JavaScript frameworks, like Angular, do this automatically for you.
		// It is unlikely that you will need to use this value manually.
		Cookie::set('X-XSRF-TOKEN', csrf_token(), $this->cookieExpiration);
		
		// Skin selection
		// config(['app.skin' => getFrontSkin(request()->input('skin'))]);
		
		// Reset session Post view counter
		if (!request()->expectsJson()) {
			if (!str_contains(currentRouteAction(), 'Post\ShowController')) {
				if (session()->has('postIsVisited')) {
					session()->forget('postIsVisited');
				}
			}
		}
		
		// Pages Menu
		$pages = collect();
		try {
			$cacheId = 'pages.' . config('app.locale') . '.menu';
			$pages = cache()->remember($cacheId, $this->cacheExpiration, function () {
				return Page::columnIsEmpty('excluded_from_footer')->orderBy('lft')->get();
			});
		} catch (\Throwable $e) {
		}
		view()->share('pages', $pages);
		
		// Get all Countries
		$this->countries = CountryLocalization::getCountries();
		view()->share('countries', $this->countries);
		
		// Get current country translation
		if ($this->countries->has(config('country.code'))) {
			$country = $this->countries->get(config('country.code'));
			if ($country instanceof Collection && $country->has('name')) {
				config()->set('country.name', $country->get('name', config('country.name')));
			}
		}
		
		// Advertising
		if (!request()->expectsJson()) {
			$topAdvertising = null;
			$bottomAdvertising = null;
			$autoAdvertising = null;
			try {
				$topAdvertising = cache()->remember('advertising.top', $this->cacheExpiration, function () {
					return Advertising::where('integration', 'unitSlot')->where('slug', 'top')->first();
				});
				$bottomAdvertising = cache()->remember('advertising.bottom', $this->cacheExpiration, function () {
					return Advertising::where('integration', 'unitSlot')->where('slug', 'bottom')->first();
				});
				$autoAdvertising = cache()->remember('advertising.auto', $this->cacheExpiration, function () {
					return Advertising::where('integration', 'autoFit')->where('slug', 'auto')->first();
				});
			} catch (\Throwable $e) {
			}
			view()->share('topAdvertising', $topAdvertising);
			view()->share('bottomAdvertising', $bottomAdvertising);
			view()->share('autoAdvertising', $autoAdvertising);
		}
		
		// Get Payment Methods
		$this->paymentMethods = new EloquentCollection;
		try {
			$cacheId = config('country.code') . '.paymentMethods.all';
			$this->paymentMethods = cache()->remember($cacheId, $this->cacheExpiration, function () {
				return PaymentMethod::whereIn('name', array_keys((array)config('plugins.installed')))
					->where(function ($query) {
						$query->whereRaw('FIND_IN_SET("' . config('country.icode') . '", LOWER(countries)) > 0')
							->orWhereNull('countries')->orWhere('countries', '');
					})->orderBy('lft')->get();
			});
		} catch (\Throwable $e) {
		}
		$this->countPaymentMethods = $this->paymentMethods->count();
		view()->share('paymentMethods', $this->paymentMethods);
		view()->share('countPaymentMethods', $this->countPaymentMethods);
	}
}
