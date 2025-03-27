<?php


namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StrictActiveScope implements Scope
{
	/**
	 * Apply the scope to a given Eloquent query builder.
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $builder
	 * @param \Illuminate\Database\Eloquent\Model $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function apply(Builder $builder, Model $model): Builder
	{
		/*
		 * This scope is considered as strict except this condition,
		 * where we need to load all entries from some Admin panel Controllers:
		 */
		$areActiveOrNonActiveEntriesRequired = (
			str_contains(currentRouteAction(), 'Admin\PaymentController')
			|| str_contains(currentRouteAction(), 'Admin\AjaxController')
			|| str_contains(currentRouteAction(), 'Admin\InlineRequestController')
		);
		if ($areActiveOrNonActiveEntriesRequired) {
			return $builder;
		}
		
		// Load only activated entries for the rest of the website (Admin panel & Front)
		return $builder->where('active', 1);
	}
}
