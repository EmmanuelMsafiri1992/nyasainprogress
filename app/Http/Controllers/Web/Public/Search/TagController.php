<?php


namespace App\Http\Controllers\Web\Public\Search;

use Larapen\LaravelMetaTags\Facades\MetaTag;

class TagController extends BaseController
{
	public ?string $tag;
	
	/**
	 * @param string|null $countryCode
	 * @param string|null $tag
	 * @return \Illuminate\Contracts\View\View
	 * @throws \Exception
	 */
	public function index(?string $countryCode, string $tag = null)
	{
		// Check if the multi-country site option is enabled
		if (!config('settings.seo.multi_country_urls')) {
			$tag = $countryCode;
		}
		
		$this->tag = rawurldecode($tag);
		
		// Call API endpoint
		$endpoint = '/posts';
		$queryParams = [
			'op'  => 'search',
			'tag' => $tag,
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
		
		// SEO: noindex
		$noIndexTagsPages = (
			config('settings.seo.no_index_tags')
			&& currentRouteActionContains('Search\TagController')
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
				'noIndexTagsPages',
				'noIndexFiltersOnEntriesPages',
				'noIndexNoResultPages'
			)
		);
	}
}
