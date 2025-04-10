<?php


namespace App\Http\Controllers\Web\Public\Search;

use Larapen\LaravelMetaTags\Facades\MetaTag;

class CompanyController extends BaseController
{
	public ?array $company;
	
	/**
	 * Listing of Companies
	 *
	 * @return \Illuminate\Contracts\View\View
	 * @throws \Exception
	 */
	public function index()
	{
		// Call API endpoint
		$endpoint = '/companies';
		$queryParams = [
			'countPosts' => true,
			'q'          => request()->input('q'),
			'perPage'    => 24,
			'sort'       => 'created_at',
		];
		$queryParams = array_merge(request()->all(), $queryParams);
		$data = makeApiRequest('get', $endpoint, $queryParams);
		
		$apiMessage = $this->handleHttpError($data);
		$apiResult = data_get($data, 'result');
		
		// Meta Tags
		$title = t('companies_list_title', ['appName' => config('settings.app.name')]);
		$description = t('companies_list_description', ['appName' => config('settings.app.name')]);
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		
		// Open Graph
		$this->og->title($title)->description($description)->type('website');
		view()->share('og', $this->og);
		
		$isFromSearchCompany = currentRouteActionContains('Search\CompanyController');
		
		// SEO: noindex
		$noIndexCompaniesPages = (
			config('settings.seo.no_index_companies')
			&& currentRouteActionContains('Search\CompanyController')
		);
		// Filters (and Orders) on Jobs Pages (Except Pagination)
		$noIndexFiltersOnEntriesPages = (
			config('settings.seo.no_index_filters_orders')
			&& currentRouteActionContains('Search\\')
			&& !empty(request()->except(['page']))
		);
		// "No result" Pages (Empty Searches Results Pages)
		$noIndexNoResultPages = (
			config('settings.seo.no_index_no_entry_found')
			&& currentRouteActionContains('Search\\')
			&& empty(data_get($apiResult, 'data'))
		);
		
		return appView(
			'search.company.index',
			compact(
				'apiResult',
				'apiMessage',
				'isFromSearchCompany',
				'noIndexCompaniesPages',
				'noIndexFiltersOnEntriesPages',
				'noIndexNoResultPages'
			)
		);
	}
	
	/**
	 * Show a Company profiles (with its Jobs ads)
	 *
	 * @param $countryCode
	 * @param $companyId
	 * @return \Illuminate\Contracts\View\View
	 * @throws \Exception
	 */
	public function profile($countryCode, $companyId = null)
	{
		// Check if the multi-country site option is enabled
		if (!config('settings.seo.multi_country_urls')) {
			$companyId = $countryCode;
		}
		
		// Call API endpoint
		$endpoint = '/posts';
		$queryParams = [
			'op'        => 'search',
			'companyId' => trim($companyId),
		];
		$queryParams = array_merge(request()->all(), $queryParams);
		$headers = [
			'X-WEB-CONTROLLER' => class_basename(get_class($this)),
		];
		$data = makeApiRequest('get', $endpoint, $queryParams, $headers);
		
		$apiMessage = $this->handleHttpError($data);
		$apiResult = data_get($data, 'result');
		$apiExtra = data_get($data, 'extra');
		$preSearch = data_get($apiExtra, 'preSearch');
		$company = data_get($preSearch, 'company');
		
		if (empty($company)) {
			abort(404, $apiMessage ?? t('company_not_found'));
		}
		
		// Sidebar
		$this->bindSidebarVariables((array)data_get($apiExtra, 'sidebar'));
		
		// Get Titles
		$this->getBreadcrumb($preSearch);
		$this->getHtmlTitle($preSearch);
		
		// Meta Tags
		[$title, $description, $keywords] = $this->getMetaTag($preSearch);
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		MetaTag::set('keywords', $keywords);
		
		// Open Graph
		$this->og->title($title)->description($description)->type('website');
		view()->share('og', $this->og);
		
		$isFromSearchCompany = currentRouteActionContains('Search\CompanyController');
		
		// SEO: noindex
		$noIndexCompaniesPages = (
			config('settings.seo.no_index_companies')
			&& currentRouteActionContains('Search\CompanyController')
		);
		// Filters (and Orders) on Jobs Pages (Except Pagination)
		$noIndexFiltersOnEntriesPages = (
			config('settings.seo.no_index_filters_orders')
			&& currentRouteActionContains('Search\\')
			&& !empty(request()->except(['page']))
		);
		// "No result" Pages (Empty Searches Results Pages)
		$noIndexNoResultPages = (
			config('settings.seo.no_index_no_entry_found')
			&& currentRouteActionContains('Search\\')
			&& empty(data_get($apiResult, 'data'))
		);
		
		return appView(
			'search.company.profile',
			compact(
				'company',
				'apiMessage',
				'apiResult',
				'apiExtra',
				'isFromSearchCompany',
				'noIndexCompaniesPages',
				'noIndexFiltersOnEntriesPages',
				'noIndexNoResultPages'
			)
		);
	}
}
