<?php


namespace App\Http\Controllers\Api\Company;

use App\Helpers\Files\Upload;
use App\Http\Requests\Request;
use App\Models\Company;

trait SaveCompany
{
	/**
	 * Store the user's company
	 *
	 * @param $userId
	 * @param \App\Http\Requests\Request $request
	 * @return \App\Models\Company
	 */
	protected function storeCompany($userId, Request $request): Company
	{
		return $this->saveCompany($userId, $request);
	}
	
	/**
	 * Update the user's company
	 *
	 * @param $userId
	 * @param \App\Http\Requests\Request $request
	 * @param \App\Models\Company $company
	 * @return \App\Models\Company
	 */
	protected function updateCompany($userId, Request $request, Company $company): Company
	{
		return $this->saveCompany($userId, $request, $company);
	}
	
	/**
	 * Save the user's company
	 *
	 * @param $userId
	 * @param \App\Http\Requests\Request $request
	 * @param \App\Models\Company|null $company
	 * @return \App\Models\Company
	 */
	protected function saveCompany($userId, Request $request, Company|null $company = null): Company
	{
		// Get Company Input
		$companyInput = $request->input('company');
		if (empty($companyInput['user_id'])) {
			$companyInput['user_id'] = $userId;
		}
		if (empty($companyInput['country_code'])) {
			$companyInput['country_code'] = config('country.code');
		}
		
		// Create
		if (empty($company)) {
			$company = new Company();
		}
		
		// Update
		foreach ($companyInput as $key => $value) {
			if (in_array($key, $company->getFillable())) {
				$company->{$key} = $value;
			}
		}
		$company->save();
		
		// Save the Company's Logo
		if ($request->hasFile('company.logo')) {
			$param = [
				'destPath' => 'files/' . strtolower($company->country_code) . '/' . $company->id,
				'width'    => (int)config('larapen.media.resize.namedOptions.company-logo.width', 800),
				'height'   => (int)config('larapen.media.resize.namedOptions.company-logo.height', 800),
				'ratio'    => config('larapen.media.resize.namedOptions.company-logo.ratio', '1'),
				'upsize'   => config('larapen.media.resize.namedOptions.company-logo.upsize', '1'),
			];
			$company->logo = Upload::image($param['destPath'], $request->file('company.logo'), $param);
			
			$company->save();
		}
		
		return $company;
	}
}
