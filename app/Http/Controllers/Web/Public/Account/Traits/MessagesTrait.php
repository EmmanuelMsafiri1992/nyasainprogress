<?php


namespace App\Http\Controllers\Web\Public\Account\Traits;

use App\Helpers\UrlGen;
use App\Models\Thread;

trait MessagesTrait
{
	/**
	 * Check Threads with New Messages
	 *
	 * @return \Illuminate\Http\JsonResponse|void
	 */
	public function checkNew()
	{
		if (!isFromAjax()) {
			return;
		}
		
		$countLimit = 20;
		$countThreadsWithNewMessages = 0;
		$oldValue = request()->input('oldValue');
		$languageCode = request()->input('languageCode');
		
		if (auth()->check()) {
			$countThreadsWithNewMessages = Thread::whereHas('post', function ($query) {
				$query->inCountry()->unarchived();
			})->forUserWithNewMessages(auth()->id())->count();
		}
		
		$result = [
			'logged'                      => auth()->check() ? auth()->user()->getAuthIdentifier() : 0,
			'countLimit'                  => (int)$countLimit,
			'countThreadsWithNewMessages' => (int)$countThreadsWithNewMessages,
			'oldValue'                    => (int)$oldValue,
			'loginUrl'                    => UrlGen::login(),
		];
		
		return ajaxResponse()->json($result);
	}
	
	/* PRIVATE */
	
	/**
	 * @param $entryId
	 * @return string
	 */
	private function getSelectedIds($entryId): string
	{
		$ids = [];
		if (request()->filled('entries')) {
			$ids = (array)request()->input('entries');
		} else {
			if (is_numeric($entryId) && $entryId > 0) {
				$ids[] = $entryId;
			}
		}
		
		return !empty($ids) ? '/' . implode(',', $ids) : '';
	}
}
