<?php


namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Api\Post\List\SearchTrait;
use App\Http\Controllers\Api\Post\List\SimilarTrait;
use App\Http\Resources\EntityCollection;
use App\Models\Post;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;

trait ListTrait
{
	use SearchTrait, SimilarTrait;
	
	/**
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function getPostsList(): \Illuminate\Http\JsonResponse
	{
		$countryCode = request()->input('country_code', config('country.code'));
		$areBelongLoggedUser = (
			(request()->filled('belongLoggedUser') && request()->integer('belongLoggedUser') == 1)
			|| request()->input('logged')
		);
		$arePendingApproval = (request()->filled('pendingApproval') && request()->integer('pendingApproval') == 1);
		$areArchived = (request()->filled('archived') && request()->integer('archived') == 1);
		$perPage = getNumberOfItemsPerPage('posts', request()->integer('perPage'));
		
		$posts = Post::query()->with(['user'])->inCountry($countryCode)->has('country');
		
		if ($areBelongLoggedUser) {
			$authUser = auth('sanctum')->user();
			if (!empty($authUser)) {
				$posts->where('user_id', $authUser->getAuthIdentifier());
				
				if ($arePendingApproval) {
					$posts->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->unverified();
				} else if ($areArchived) {
					$posts->archived();
				} else {
					$posts->verified()->unarchived()->reviewed();
				}
			} else {
				return apiResponse()->unauthorized();
			}
		}
		
		$embed = explode(',', request()->input('embed'));
		
		if (in_array('country', $embed)) {
			$posts->with('country');
		}
		if (in_array('user', $embed)) {
			$posts->with('user');
		}
		if (in_array('category', $embed)) {
			$posts->with('category');
		}
		if (in_array('postType', $embed)) {
			$posts->with('postType');
		}
		if (in_array('city', $embed)) {
			$posts->with('city');
		}
		if (in_array('payment', $embed)) {
			if (in_array('package', $embed)) {
				$posts->with(['payment' => fn($builder) => $builder->with(['package'])]);
			} else {
				$posts->with('payment');
			}
		}
		
		// Sorting
		$posts = $this->applySorting($posts, ['created_at']);
		
		$posts = $posts->paginate($perPage);
		
		// If the request is made from the app's Web environment,
		// use the Web URL as the pagination's base URL
		$posts = setPaginationBaseUrl($posts);
		
		$resourceCollection = new EntityCollection(class_basename($this), $posts);
		$message = ($posts->count() <= 0) ? t('no_posts_found') : null;
		$resourceCollection = apiResponse()->withCollection($resourceCollection, $message);
		
		$data = json_decode($resourceCollection->content(), true);
		
		return apiResponse()->json($data);
	}
}
