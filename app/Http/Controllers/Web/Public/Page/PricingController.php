<?php


namespace App\Http\Controllers\Web\Public\Page;

use App\Helpers\UrlGen;
use App\Http\Controllers\Web\Public\FrontController;
use Larapen\LaravelMetaTags\Facades\MetaTag;

class PricingController extends FrontController
{
	/**
	 * @return \Illuminate\Contracts\View\View
	 * @throws \Exception
	 */
	public function index()
	{
		// Get Listings' Promo Packages
		$promoPackagesData = $this->getPromotionPackages();
		$promoPackagesErrorMessage = $this->handleHttpError($promoPackagesData);
		$promoPackages = data_get($promoPackagesData, 'result.data');
		
		// Get Subscriptions Packages
		$subsPackagesData = $this->getSubscriptionPackages();
		$subsPackagesErrorMessage = $this->handleHttpError($subsPackagesData);
		$subsPackages = data_get($subsPackagesData, 'result.data');
		
		// Meta Tags
		[$title, $description, $keywords] = getMetaTag('pricing');
		MetaTag::set('title', $title);
		MetaTag::set('description', strip_tags($description));
		MetaTag::set('keywords', $keywords);
		
		// Open Graph
		$this->og->title($title)->description($description)->type('website');
		view()->share('og', $this->og);
		
		return appView(
			'pages.pricing',
			compact(
				'subsPackages',
				'subsPackagesErrorMessage',
				'promoPackages',
				'promoPackagesErrorMessage'
			)
		);
	}
	
	private function getPromotionPackages(): array
	{
		// Get Packages - Call API endpoint
		$endpoint = '/packages/promotion';
		$queryParams = [
			'embed' => 'currency',
			'sort'  => '-lft',
		];
		$queryParams = array_merge(request()->all(), $queryParams);
		$data = makeApiRequest('get', $endpoint, $queryParams);
		
		// Select a Package and go to previous URL
		// Add Listing possible URLs
		$addListingUriArray = [
			'create',
			'post\/create',
			'post\/create\/[^\/]+\/photos',
		];
		// Default Add Listing URL
		$addListingUrl = UrlGen::addPost();
		
		if (request()->filled('from')) {
			$path = request()->input('from');
			if (!empty($path) && is_string($path)) {
				foreach ($addListingUriArray as $uriPattern) {
					if (preg_match('#' . $uriPattern . '#', $path)) {
						$addListingUrl = url($path);
						break;
					}
				}
			}
		}
		
		view()->share('addListingUrl', $addListingUrl);
		
		return $data;
	}
	
	private function getSubscriptionPackages(): array
	{
		// Get Packages - Call API endpoint
		$endpoint = '/packages/subscription';
		$queryParams = [
			'embed' => 'currency',
			'sort'  => '-lft',
		];
		$queryParams = array_merge(request()->all(), $queryParams);
		
		return makeApiRequest('get', $endpoint, $queryParams);
	}
}
