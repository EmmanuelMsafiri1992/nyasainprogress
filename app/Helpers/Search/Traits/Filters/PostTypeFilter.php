<?php


namespace App\Helpers\Search\Traits\Filters;

trait PostTypeFilter
{
	protected function applyPostTypeFilter(): void
	{
		if (!isset($this->posts)) {
			return;
		}
		
		$postTypeIds = request()->input('type', []);
		
		if (empty($postTypeIds)) {
			return;
		}
		
		if (is_array($postTypeIds)) {
			$this->posts->whereIn('post_type_id', $postTypeIds);
		}
		
		// Optional
		if (is_numeric($postTypeIds)) {
			$this->posts->where('post_type_id', $postTypeIds);
		}
	}
}
