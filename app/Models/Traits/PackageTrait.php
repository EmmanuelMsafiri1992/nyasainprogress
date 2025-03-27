<?php


namespace App\Models\Traits;

trait PackageTrait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function getNameHtml(): string
	{
		$currentUrl = preg_replace('#/(search)$#', '', url()->current());
		$url = $currentUrl . '/' . $this->id . '/edit';
		$badge = '';
		if (!empty($this->short_name)) {
			$badge = ' <span class="badge bg-primary float-end">' . $this->short_name . '</span>';
		}
		
		return '<a href="' . $url . '">' . $this->name . '</a>' . $badge;
	}
	
	// ===| OTHER METHODS |===
}
