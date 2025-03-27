<?php


namespace App\Helpers\Search\Traits\Filters;

trait CompanyFilter
{
	protected function applyCompanyFilter(): void
	{
		if (!isset($this->posts)) {
			return;
		}
		
		$companyId = request()->input('companyId');
		$companyId = (is_numeric($companyId) || is_string($companyId)) ? $companyId : null;
		
		if (empty($companyId)) {
			return;
		}
		
		$this->posts->where('company_id', $companyId);
	}
}
