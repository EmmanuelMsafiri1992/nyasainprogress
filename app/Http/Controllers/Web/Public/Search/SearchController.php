<?php


namespace App\Http\Controllers\Web\Public\Search;

use Illuminate\Http\Response;
use Larapen\LaravelMetaTags\Facades\MetaTag;

class SearchController extends BaseController
{
	/**
	 * @return \Illuminate\Contracts\View\View
	 * @throws \Exception
	 */
	public function index()
	{
		$allowedFilters = ['search', 'premium'];
		
		// Get the listings type parameter
		$filterBy = request()->input('filterBy', 'search');
		if (!in_array($filterBy, $allowedFilters)) {
			abort(Response::HTTP_FORBIDDEN, t('unauthorized_filter'));
		}
		
		// Call API endpoint
		$endpoint = '/posts';
		$queryParams = [
			'op' => $filterBy,
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
		
		// Sidebar
		$this->bindSidebarVariables((array)data_get($apiExtra, 'sidebar'));
		
		// Get Titles
		$this->getHtmlTitle($preSearch);
		$this->getBreadcrumb($preSearch);
		
		// Meta Tags
		[$title, $description, $keywords] = $this->getMetaTag($preSearch);
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		MetaTag::set('keywords', $keywords);
		
		// Open Graph
		$this->og->title($title)->description($description)->type('website');
		view()->share('og', $this->og);
		
		// SEO: noindex
		$noIndexCategoriesQueryStringPages = (
			config('settings.seo.no_index_categories_qs')
			&& currentRouteActionContains('Search\SearchController')
			&& !empty(data_get($preSearch, 'cat'))
		);
		$noIndexCitiesQueryStringPages = (
			config('settings.seo.no_index_cities_qs')
			&& currentRouteActionContains('Search\SearchController')
			&& !empty(data_get($preSearch, 'city'))
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
			'search.results',
			compact(
				'apiMessage',
				'apiResult',
				'apiExtra',
				'noIndexCategoriesQueryStringPages',
				'noIndexCitiesQueryStringPages',
				'noIndexFiltersOnEntriesPages',
				'noIndexNoResultPages'
			)
		);
	}
}
