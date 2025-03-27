<?php


namespace App\Http\Controllers\Web\Admin\Traits\InlineRequest;

trait HomeSectionTrait
{
	/**
	 * Update the 'active' column of the home sections table
	 *
	 * @param $homeSection
	 * @param $column
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function updateHomeSectionData($homeSection, $column): \Illuminate\Http\JsonResponse
	{
		$isValidCondition = ($this->table == 'home_sections' && $column == 'active' && !empty($homeSection));
		if (!$isValidCondition) {
			$error = trans('admin.inline_req_condition', ['table' => $this->table, 'column' => $column]);
			
			return $this->responseError($error, 400);
		}
		
		// Update the 'active' column
		// See the "app/Observers/HomeSectionObserver.php" file for complete operation
		$homeSection->{$column} = ($homeSection->{$column} != 1) ? 1 : 0;
		
		// Update the 'active' option in the 'value' column
		$valueColumnValue = $homeSection->value;
		$valueColumnValue[$column] = $homeSection->{$column};
		$homeSection->value = $valueColumnValue;
		
		$homeSection->save();
		
		return $this->responseSuccess($homeSection, $column);
	}
}
