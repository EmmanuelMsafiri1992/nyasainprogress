<?php


namespace App\Http\Controllers\Web\Public\Traits;

use App\Helpers\DotenvEditor;
use App\Helpers\Files\Storage\StorageDisk;

trait CommonTrait
{
	public $disk;
	public $pDisk;
	
	/**
	 * Set the storage disk
	 */
	private function setStorageDisk(): void
	{
		// Get the storage disk
		$this->disk = StorageDisk::getDisk();
		view()->share('disk', $this->disk);
		
		// Get the storage disk for Resumes
		$this->pDisk = StorageDisk::getDisk('private');
		view()->share('pDisk', $this->pDisk);
	}
	
	/**
	 * Check & update the App Key (If needed, for security reasons)
	 *
	 * @return void
	 */
	private function checkAndGenerateAppKey(): void
	{
		$isUnsecureAppKey = (DotenvEditor::getValue('APP_KEY') == 'SomeRandomStringWith32Characters');
		
		// Generate a new App Key
		if ($isUnsecureAppKey) {
			updateAppKeyWithArtisan();
		}
	}
}
