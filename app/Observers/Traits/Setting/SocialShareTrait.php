<?php


namespace App\Observers\Traits\Setting;

use App\Helpers\Files\Storage\StorageDisk;

trait SocialShareTrait
{
	/**
	 * Updating
	 *
	 * @param $setting
	 * @param $original
	 */
	public function socialShareUpdating($setting, $original)
	{
		// Storage Disk Init.
		$disk = StorageDisk::getDisk();
		
		$this->removeOldOgImageFile($setting, $original, $disk);
	}
	
	/**
	 * Remove old og_image from disk (Don't remove the default picture)
	 *
	 * @param $setting
	 * @param $original
	 * @param $disk
	 */
	private function removeOldOgImageFile($setting, $original, $disk): void
	{
		if (array_key_exists('og_image', $setting->value)) {
			if (
				is_array($original['value'])
				&& !empty($original['value']['og_image'])
				&& $setting->value['og_image'] != $original['value']['og_image']
				&& !str_contains($original['value']['og_image'], config('larapen.media.picture'))
				&& $disk->exists($original['value']['og_image'])
			) {
				$disk->delete($original['value']['og_image']);
			}
		}
	}
}
