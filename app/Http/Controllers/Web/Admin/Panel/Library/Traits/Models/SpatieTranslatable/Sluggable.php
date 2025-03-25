<?php
/*
 * JobClass - Job Board Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com/jobclass
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers\Web\Admin\Panel\Library\Traits\Models\SpatieTranslatable;

use Cviebrock\EloquentSluggable\Sluggable as OriginalSluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Sluggable
{
	use OriginalSluggable;
	
	/**
	 * Hook into the Eloquent model events to create or
	 * update the slug as required.
	 */
	public static function bootSluggable()
	{
		static::observe(app(SluggableObserver::class));
	}
	
	/**
	 * Clone the model into a new, non-existing instance.
	 *
	 * @param array|null $except
	 *
	 * @return Model
	 */
	public function replicate(array $except = null): Model
	{
		$instance = parent::replicate($except);
		(new SlugService())->slug($instance, true);
		
		return $instance;
	}
	
	/**
	 * Query scope for finding "similar" slugs, used to determine uniqueness.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 * @param \Illuminate\Database\Eloquent\Model   $model
	 * @param string                                $attribute
	 * @param array                                 $config
	 * @param string                                $slug
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeFindSimilarSlugs(Builder $query, string $attribute, array $config, string $slug): Builder
	{
		$separator = $config['separator'];
		$attribute = $attribute.'->'.$this->getLocale();
		
		return $query->where(function (Builder $q) use ($attribute, $slug, $separator) {
			$q->where($attribute, '=', $slug)
				->orWhere($attribute, 'LIKE', $slug.$separator.'%')
				// Fixes issues with Json data types in MySQL where data is sourrounded by "
				->orWhere($attribute, 'LIKE', '"'.$slug.$separator.'%');
		});
	}
}
