<?php


namespace App\Models\Traits;

trait CityTrait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function getAdmin2Html()
	{
		return (!empty($this->subAdmin2))
			? $this->subAdmin2->name
			: ($this->subadmin2_code ?? null);
	}
	
	public function getAdmin1Html()
	{
		return (!empty($this->subAdmin1))
			? $this->subAdmin1->name
			: ($this->subadmin1_code ?? null);
	}
	
	// ===| OTHER METHODS |===
}
