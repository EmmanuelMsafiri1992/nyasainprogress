<?php


namespace App\Http\Controllers\Web\Admin\Traits\InlineRequest;

trait PayableTrait
{
	/**
	 * - Update the 'featured' column of the payable (posts|users) table
	 * - Add or delete payment using the OfflinePayment plugin
	 *
	 * @param $payable
	 * @param $column
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function updatePayableData($payable, $column): \Illuminate\Http\JsonResponse
	{
		$opTool = '\extras\plugins\offlinepayment\app\Helpers\OpTools';
		$isOfflinePaymentInstalled = (config('plugins.offlinepayment.installed') && class_exists($opTool));
		
		$isValidCondition = (
			in_array($this->table, ['posts', 'users'])
			&& $column == 'featured'
			&& !empty($payable)
			&& $isOfflinePaymentInstalled
		);
		
		if (!$isValidCondition) {
			$error = trans('admin.inline_req_condition', ['table' => $this->table, 'column' => $column]);
			
			return $this->responseError($error, 400);
		}
		
		// Save data
		if ($payable->{$column} == 1) {
			$result = $opTool::deleteFeatured($payable);
		} else {
			$result = $opTool::createFeatured($payable);
		}
		
		$this->message = data_get($result, 'message', $this->message);
		
		if (!data_get($result, 'success')) {
			return $this->responseError($this->message);
		}
		
		return $this->responseSuccess($payable, $column);
	}
}
