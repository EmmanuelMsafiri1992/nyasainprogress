<?php


namespace App\Models\Traits;

trait SubAdmin2Trait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function getNameHtml(): string
	{
		$currentUrl = preg_replace('#/(search)$#', '', url()->current());
		$editUrl = $currentUrl . '/' . $this->code . '/edit';
		
		return '<a href="' . $editUrl . '" style="float:left;">' . $this->name . '</a>';
	}
	
	public function citiesButton($xPanel = false): string
	{
		$url = admin_url('admins2/' . $this->code . '/cities');
		
		$msg = trans('admin.Cities of admin2', ['admin_division2' => $this->name]);
		$toolTip = ' data-bs-toggle="tooltip" title="' . $msg . '"';
		
		$out = '<a class="btn btn-xs btn-light" href="' . $url . '"' . $toolTip . '>';
		$out .= '<i class="fa-regular fa-eye"></i> ';
		$out .= mb_ucfirst(trans('admin.cities'));
		$out .= '</a>';
		
		return $out;
	}
	
	// ===| OTHER METHODS |===
}
