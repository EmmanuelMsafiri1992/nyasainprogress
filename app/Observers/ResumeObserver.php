<?php


namespace App\Observers;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\Resume;

class ResumeObserver
{
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param Resume $resume
	 * @return void
	 */
	public function deleting(Resume $resume)
	{
		// Storage Disk Init.
		$pDisk = StorageDisk::getDisk('private');
		
		// Remove resume files (if exists)
		if (!empty($resume->filename)) {
			$filename = str_replace('uploads/', '', $resume->filename);
			
			if ($pDisk->exists($filename)) {
				$pDisk->delete($filename);
			}
		}
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param Resume $resume
	 * @return void
	 */
	public function saved(Resume $resume)
	{
		// Removing Entries from the Cache
		$this->clearCache($resume);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param Resume $resume
	 * @return void
	 */
	public function deleted(Resume $resume)
	{
		// Removing Entries from the Cache
		$this->clearCache($resume);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $resume
	 * @return void
	 */
	private function clearCache($resume): void
	{
		$limit = config('larapen.core.selectResumeInto', 5);
		
		try {
			cache()->forget('resumes.take.' . $limit . '.where.user.' . $resume->user_id);
			cache()->forget('resume.where.user.' . $resume->user_id);
		} catch (\Exception $e) {}
	}
}
