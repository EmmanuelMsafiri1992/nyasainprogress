<?php


namespace App\Helpers\Search\Traits\Filters;

trait AuthorFilter
{
	protected function applyAuthorFilter(): void
	{
		if (!isset($this->posts)) {
			return;
		}
		
		$userId = null;
		$username = null;
		if (request()->filled('userId')) {
			$userId = request()->input('userId');
		}
		if (request()->filled('username')) {
			$username = request()->input('username');
		}
		
		$userId = is_numeric($userId) ? $userId : null;
		$username = is_string($username) ? $username : null;
		
		if (empty($userId) && empty($username)) {
			return;
		}
		
		if (!empty($userId)) {
			$this->posts->where('user_id', $userId);
		}
		
		if (!empty($username)) {
			// Use withWhereHas() to load the 'user' model/relationship
			$this->posts->whereHas('user', fn ($query) => $query->where('username', $username));
		}
	}
}
