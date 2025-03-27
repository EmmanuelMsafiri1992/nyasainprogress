<?php


namespace App\Http\Controllers\Api\Post\List\Search;

use App\Http\Controllers\Api\Category\CategoryBy;
use App\Models\Category;

trait CategoryTrait
{
	use CategoryBy;
	
	/**
	 * Get Category (Auto-detecting ID or Slug)
	 *
	 * @return mixed|null
	 */
	public function getCategory()
	{
		// Get the Category's right arguments
		$catParentId = request()->input('c');
		$catId = request()->input('sc', $catParentId);
		$catParentId = ($catParentId == $catId) ? null : $catParentId;
		
		// Validate parameters values
		$catParentId = (is_numeric($catParentId) || is_string($catParentId)) ? $catParentId : null;
		$catId = (is_numeric($catId) || is_string($catId)) ? $catId : null;
		
		// Get the Category
		$cat = null;
		if (!empty($catId)) {
			if (is_numeric($catId)) {
				$cat = $this->getCategoryById($catId);
			} else {
				$isCatIdString = is_string($catId);
				$isCatParentIdStringOrEmpty = (is_string($catParentId) || empty($catParentId));
				
				if ($isCatIdString && $isCatParentIdStringOrEmpty) {
					$cat = $this->getCategoryBySlug($catId, $catParentId);
				}
			}
			
			if (empty($cat)) {
				abort(404, t('category_not_found'));
			}
		}
		
		return $cat;
	}
	
	/**
	 * Get Root Categories
	 *
	 * @return mixed
	 */
	public function getRootCategories()
	{
		$limit = getNumberOfItemsToTake('categories');
		
		$cacheId = 'cat.0.categories.take.' . $limit . '.' . config('app.locale');
		$cats = cache()->remember($cacheId, $this->cacheExpiration, function () use ($limit) {
			return Category::root()->orderBy('lft')->take($limit)->get();
		});
		
		if ($cats->count() > 0) {
			$cats = $cats->keyBy('id');
		}
		
		return $cats;
	}
}
