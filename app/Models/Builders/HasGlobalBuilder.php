<?php


namespace App\Models\Builders;

use App\Models\Builders\Classes\GlobalBuilder;

trait HasGlobalBuilder
{
	use HasEnumFields;
	
	/**
	 * Get a new query builder instance for the connection
	 * that extend the Laravel eloquent core builder
	 *
	 * @param $query
	 * @return \App\Models\Builders\Classes\GlobalBuilder
	 */
	public function newEloquentBuilder($query)
	{
		return new GlobalBuilder($query);
	}
}
