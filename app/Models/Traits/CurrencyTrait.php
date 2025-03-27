<?php


namespace App\Models\Traits;

trait CurrencyTrait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function getNameHtml(): string
	{
		$currentUrl = preg_replace('#/(search)$#', '', url()->current());
		$url = $currentUrl . '/' . $this->getKey() . '/edit';
		
		return '<a href="' . $url . '">' . $this->name . '</a>';
	}
	
	public function getSymbolHtml(): string
	{
		return html_entity_decode($this->symbol);
	}
	
	public function getPositionHtml(): string
	{
		$toggleIcon = ($this->in_left == 1)
			? 'fa-solid fa-toggle-on'
			: 'fa-solid fa-toggle-off';
		
		return '<i class="admin-single-icon ' . $toggleIcon . '" aria-hidden="true"></i>';
	}
	
	// ===| OTHER METHODS |===
}
