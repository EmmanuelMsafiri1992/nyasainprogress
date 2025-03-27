<?php


namespace App\Http\Controllers\Web\Public\Ajax;

use App\Helpers\UrlGen;
use App\Http\Controllers\Web\Public\FrontController;
use Illuminate\Http\Request;
use Larapen\TextToImage\Facades\TextToImage;

class PostController extends FrontController
{
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function savePost(Request $request): \Illuminate\Http\JsonResponse
	{
		// Call API endpoint
		$endpoint = '/savedPosts';
		$data = makeApiRequest('post', $endpoint, $request->all(), [], true);
		
		// Parsing the API response
		$status = (int)data_get($data, 'status');
		$message = data_get($data, 'message', t('unknown_error'));
		
		// HTTP Error Found
		if (!data_get($data, 'isSuccessful')) {
			return ajaxResponse()->json(['message' => $message], $status);
		}
		
		// Get entry resource
		$savedPost = data_get($data, 'result');
		
		// AJAX response data
		$result = [
			'isLogged' => !($status == 401), // No longer used. Will be removed.
			'postId'   => $request->input('post_id'),
			'isSaved'  => !empty($savedPost),
			'message'  => $message,
			'loginUrl' => UrlGen::login(), // No longer used. Will be removed.
		];
		
		return ajaxResponse()->json($result, $status);
	}
	
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function saveSearch(Request $request): \Illuminate\Http\JsonResponse
	{
		// Call API endpoint
		$endpoint = '/savedSearches';
		$data = makeApiRequest('post', $endpoint, $request->all(), [], true);
		
		// Parsing the API response
		$status = (int)data_get($data, 'status');
		$message = data_get($data, 'message', t('unknown_error'));
		
		// HTTP Error Found
		if (!data_get($data, 'isSuccessful')) {
			return ajaxResponse()->json(['message' => $message], $status);
		}
		
		// Validate data extraction
		$query = null;
		$queryUrl = $request->input('url');
		if (!empty($queryUrl)) {
			$tmp = parse_url($queryUrl);
			$query = $tmp['query'] ?? null;
		}
		if (empty($query)) {
			$errorMsg = 'The "query" parameter cannot not be extracted.';
			
			return ajaxResponse()->json(['message' => $errorMsg], 400);
		}
		
		// Get entry resource
		$savedSearch = data_get($data, 'result');
		
		// AJAX response data
		$result = [
			'isLogged' => !($status == 401), // No longer used. Will be removed.
			'query'    => $query,
			'isSaved'  => !empty($savedSearch),
			'message'  => $message,
			'loginUrl' => UrlGen::login(), // No longer used. Will be removed.
		];
		
		return ajaxResponse()->json($result, $status);
	}
	
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getPhone(Request $request): \Illuminate\Http\JsonResponse
	{
		// Call API endpoint
		$endpoint = '/posts/' . $request->input('post_id', 0);
		$data = makeApiRequest('get', $endpoint, $request->all());
		
		// Parsing the API response
		$status = (int)data_get($data, 'status');
		$message = data_get($data, 'message', t('unknown_error'));
		
		// HTTP Error Found
		if (!data_get($data, 'isSuccessful')) {
			return ajaxResponse()->json(['message' => $message], $status);
		}
		
		// Get entry resource
		$post = data_get($data, 'result');
		
		// Get the phone
		$phone = data_get($post, 'phone');
		$phoneIntl = data_get($post, 'phone_intl');
		$phoneLink = 'tel:' . $phone;
		
		if (config('settings.listing_page.convert_phone_number_to_img')) {
			try {
				$phone = TextToImage::make($phoneIntl, config('larapen.core.textToImage'));
			} catch (\Throwable $e) {
				$phone = data_get($post, 'phone_intl');
			}
		}
		
		$data = [
			'phone' => $phone,
			'link'  => $phoneLink,
		];
		
		return ajaxResponse()->json($data);
	}
}
