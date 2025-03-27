<?php


namespace App\Models\Traits;

trait PaymentMethodTrait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function getDisplayNameHtml(): string
	{
		$out = $this->display_name ?? '--';
		if (!empty($this->name)) {
			$out = $out . plugin_demo_info($this->name);
		}
		
		return $out;
	}
	
	public function getCountriesHtml(): string
	{
		$out = strtoupper(trans('admin.All'));
		if (!empty($this->countries)) {
			$countriesCropped = str($this->countries)->limit(50, ' [...]');
			$out = '<div title="' . $this->countries . '">' . $countriesCropped . '</div>';
		}
		
		return $out;
	}
	
	// ===| OTHER METHODS |===
}
