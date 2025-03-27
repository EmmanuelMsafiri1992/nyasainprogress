<?php


namespace App\Http\Controllers\Web\Public\Traits\Sluggable;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

trait CategoryBySlug
{
	/**
	 * Get Category by Slug
	 * NOTE: Slug must be unique
	 *
	 * @param $catSlug
	 * @param null $parentCatSlug
	 * @param null $locale
	 * @return mixed
	 */
	public function getCategoryBySlug($catSlug, $parentCatSlug = null, $locale = null)
	{
		if (empty($locale)) {
			$locale = config('app.locale');
		}
		
		if (!empty($parentCatSlug)) {
			$cacheId = 'cat.' . $parentCatSlug . '.' . $catSlug . '.' . $locale . '.with.parent-children';
			$cat = Cache::remember($cacheId, $this->cacheExpiration, function () use ($parentCatSlug, $catSlug, $locale) {
				$cat = Category::with(['parent', 'children'])
					->whereHas('parent', function ($query) use ($parentCatSlug) {
						$query->where('slug', $parentCatSlug);
					})->where('slug', $catSlug)
					->first();
				
				if (!empty($cat)) {
					$cat->setLocale($locale);
				}
				
				return $cat;
			});
		} else {
			$cacheId = 'cat.' . $catSlug . '.' . $locale . '.with.parent-children';
			$cat = Cache::remember($cacheId, $this->cacheExpiration, function () use ($catSlug, $locale) {
				$cat = Category::with(['parent', 'children'])
					->where('slug', $catSlug)
					->first();
				
				if (!empty($cat)) {
					$cat->setLocale($locale);
				}
				
				return $cat;
			});
		}
		
		return $cat;
	}
	
	/**
	 * Get Category by ID
	 *
	 * @param $catId
	 * @param null $locale
	 * @return mixed
	 */
	public function getCategoryById($catId, $locale = null)
	{
		if (empty($locale)) {
			$locale = config('app.locale');
		}
		
		$cacheId = 'cat.' . $catId . '.' . $locale . '.with.parent-children';
		$cat = Cache::remember($cacheId, $this->cacheExpiration, function () use ($catId, $locale) {
			$cat = Category::with(['parent', 'children'])
				->where('id', $catId)
				->first();
			
			if (!empty($cat)) {
				$cat->setLocale($locale);
			}
			
			return $cat;
		});
		
		return $cat;
	}
}
