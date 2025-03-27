<?php


namespace App\Helpers\Search\Traits;

use App\Helpers\Search\Traits\Relations\CategoryRelation;
use App\Helpers\Search\Traits\Relations\PaymentRelation;

trait Relations
{
	use CategoryRelation, PaymentRelation;
	
	protected function setRelations(): void
	{
		if (!isset($this->posts)) {
			abort(500, 'Fatal Error: Search relations cannot be applied.');
		}
		
		// category
		$this->setCategoryRelation();
		
		// postType
		$this->posts->has('postType');
		if (!config('settings.listings_list.hide_post_type')) {
			$this->posts->with('postType');
		}
		
		// payment
		$this->setPaymentRelation();
		
		// city
		$this->posts->has('city');
		if (!config('settings.listings_list.hide_location')) {
			$this->posts->with('city');
		}
		
		// salaryType
		$this->posts->has('salaryType');
		if (!config('settings.listings_list.hide_salary')) {
			$this->posts->with('salaryType');
		}
		
		// user
		$this->posts->with([
			'user',
			'user.permissions',
			'user.roles',
		]);
		
		// savedByLoggedUser
		$this->posts->with('savedByLoggedUser');
		
		// company
		$this->posts->with([
			'company',
			'company.user',
			'company.user.permissions',
			'company.user.roles',
		]);
	}
}
