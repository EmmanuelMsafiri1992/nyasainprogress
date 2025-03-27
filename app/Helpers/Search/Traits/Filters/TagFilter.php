<?php


namespace App\Helpers\Search\Traits\Filters;

trait TagFilter
{
	protected function applyTagFilter(): void
	{
		if (!isset($this->posts)) {
			return;
		}
		
		$tag = request()->input('tag');
		$tag = is_string($tag) ? $tag : null;
		
		if (empty(trim($tag))) {
			return;
		}
		
		$tag = rawurldecode($tag);
		$tag = mb_strtolower($tag);
		
		$this->posts->whereRaw('FIND_IN_SET(?, LOWER(tags)) > 0', [$tag]);
	}
}
