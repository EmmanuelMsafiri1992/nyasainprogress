<?php


namespace App\Helpers\Search\Traits\Filters;

use Illuminate\Support\Facades\DB;

trait DateFilter
{
	protected function applyDateFilter(): void
	{
		if (!(isset($this->posts) && isset($this->postsTable))) {
			return;
		}
		
		$postedDate = request()->input('postedDate');
		$postedDate = (is_numeric($postedDate) || is_string($postedDate)) ? $postedDate : null;
		
		if (empty($postedDate)) {
			return;
		}
		
		$table = DB::getTablePrefix() . $this->postsTable;
		
		$this->posts->whereRaw($table . '.created_at BETWEEN DATE_SUB(NOW(), INTERVAL ? DAY) AND NOW()', [$postedDate]);
	}
}
