<?php


namespace App\Models\Traits;

use App\Helpers\UrlGen;

trait PageTrait
{
	// ===| ADMIN PANEL METHODS |===
	
	public function getNameHtml(): string
	{
		return '<a href="' . UrlGen::page($this) . '" target="_blank">' . $this->name . '</a>';
	}
	
	// ===| OTHER METHODS |===
	
	/**
	 * Return the sluggable configuration array for this model.
	 *
	 * @return array
	 */
	public function sluggable(): array
	{
		return [
			'slug' => [
				'source' => ['slug', 'name'],
			],
		];
	}
}
