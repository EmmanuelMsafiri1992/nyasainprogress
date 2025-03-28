<?php


namespace App\Helpers\UrlGen;

use App\Helpers\Arr;
use App\Helpers\UrlGen;

trait ClearFiltersTrait
{
	/**
	 * @param $cat
	 * @param $city
	 * @return string
	 */
	public static function getCategoryFilterClearLink($cat, $city = null): string
	{
		$cat = (is_array($cat)) ? Arr::toObject($cat) : $cat;
		$city = (is_array($city)) ? Arr::toObject($city) : $city;
		
		$out = '';
		if (
			request()->filled('c')
			|| request()->filled('sc')
			|| str_contains(currentRouteAction(), 'Search\CategoryController')
		) {
			$exceptArr = ['page', 'cf', 'minPrice', 'maxPrice'];
			if (!empty($cat)) {
				if (!empty($cat->parent)) {
					$exceptArr[] = 'sc';
				} else {
					$exceptArr[] = 'c';
				}
			}
			$url = UrlGen::search([], $exceptArr);
			
			if (!empty($cat)) {
				if (str_contains(currentRouteAction(), 'Search\CategoryController')) {
					if (!empty($cat->parent)) {
						$url = UrlGen::category($cat->parent, null, $city);
					}
				}
			}
			
			$out = getFilterClearBtn($url);
		}
		
		return $out;
	}
	
	/**
	 * @param $cat
	 * @param $city
	 * @return string
	 */
	public static function getCityFilterClearLink($cat = null, $city = null): string
	{
		$cat = (is_array($cat)) ? Arr::toObject($cat) : $cat;
		$city = (is_array($city)) ? Arr::toObject($city) : $city;
		
		$out = '';
		if (
			request()->filled('l')
			|| request()->filled('location')
			|| str_contains(currentRouteAction(), 'Search\CityController')
		) {
			$exceptArr = ['page', 'l', 'location', 'distance'];
			$url = UrlGen::search([], $exceptArr);
			
			if (!empty($city)) {
				if (str_contains(currentRouteAction(), 'Search\CityController')) {
					$url = UrlGen::city($city, null, $cat);
				}
			}
			
			$out = getFilterClearBtn($url);
		}
		
		return $out;
	}
	
	/**
	 * @param null $cat
	 * @param null $city
	 * @return string
	 */
	public static function getDateFilterClearLink($cat = null, $city = null): string
	{
		$cat = (is_array($cat)) ? Arr::toObject($cat) : $cat;
		$city = (is_array($city)) ? Arr::toObject($city) : $city;
		
		$out = '';
		if (request()->filled('postedDate')) {
			$queryArr = [];
			if (!empty($cat) && isset($cat->id)) {
				if (!empty($cat->parent)) {
					$queryArr['c'] = $cat->parent->id;
					$queryArr['sc'] = $cat->id;
				} else {
					$queryArr['c'] = $cat->id;
				}
			}
			if (!empty($city) && isset($city->id)) {
				$queryArr['l'] = $city->id;
			}
			$url = UrlGen::search($queryArr, ['page', 'postedDate']);
			$out = getFilterClearBtn($url);
		}
		
		return $out;
	}
	
	/**
	 * @param null $cat
	 * @param null $city
	 * @return string
	 */
	public static function getMinSalaryFilterClearLink($cat = null, $city = null): string
	{
		$cat = (is_array($cat)) ? Arr::toObject($cat) : $cat;
		$city = (is_array($city)) ? Arr::toObject($city) : $city;
		
		$out = '';
		if (request()->filled('minSalary')) {
			$queryArr = [];
			if (!empty($cat) && isset($cat->id)) {
				if (!empty($cat->parent)) {
					$queryArr['c'] = $cat->parent->id;
					$queryArr['sc'] = $cat->id;
				} else {
					$queryArr['c'] = $cat->id;
				}
			}
			if (!empty($city) && isset($city->id)) {
				$queryArr['l'] = $city->id;
			}
			$url = UrlGen::search($queryArr, ['page', 'minSalary']);
			$out = getFilterClearBtn($url);
		}
		
		return $out;
	}
	
	/**
	 * @param null $cat
	 * @param null $city
	 * @return string
	 */
	public static function getMaxSalaryFilterClearLink($cat = null, $city = null): string
	{
		$cat = (is_array($cat)) ? Arr::toObject($cat) : $cat;
		$city = (is_array($city)) ? Arr::toObject($city) : $city;
		
		$out = '';
		if (request()->filled('maxSalary')) {
			$queryArr = [];
			if (!empty($cat) && isset($cat->id)) {
				if (!empty($cat->parent)) {
					$queryArr['c'] = $cat->parent->id;
					$queryArr['sc'] = $cat->id;
				} else {
					$queryArr['c'] = $cat->id;
				}
			}
			if (!empty($city) && isset($city->id)) {
				$queryArr['l'] = $city->id;
			}
			$url = UrlGen::search($queryArr, ['page', 'maxSalary']);
			$out = getFilterClearBtn($url);
		}
		
		return $out;
	}
	
	/**
	 * @param null $cat
	 * @param null $city
	 * @return string
	 */
	public static function getTypeFilterClearLink($cat = null, $city = null): string
	{
		$cat = (is_array($cat)) ? Arr::toObject($cat) : $cat;
		$city = (is_array($city)) ? Arr::toObject($city) : $city;
		
		$out = '';
		if (request()->filled('type')) {
			$queryArr = [];
			if (!empty($cat) && isset($cat->id)) {
				if (!empty($cat->parent)) {
					$queryArr['c'] = $cat->parent->id;
					$queryArr['sc'] = $cat->id;
				} else {
					$queryArr['c'] = $cat->id;
				}
			}
			if (!empty($city) && isset($city->id)) {
				$queryArr['l'] = $city->id;
			}
			$url = UrlGen::search($queryArr, ['page', 'type']);
			$out = getFilterClearBtn($url);
		}
		
		return $out;
	}
}
