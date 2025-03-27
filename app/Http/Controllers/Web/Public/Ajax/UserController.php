<?php


namespace App\Http\Controllers\Web\Public\Ajax;

use App\Helpers\Cookie;
use App\Http\Controllers\Web\Public\FrontController;
use Illuminate\Http\Request;

class UserController extends FrontController
{
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function setDarkMode(Request $request): \Illuminate\Http\JsonResponse
	{
		$darkMode = $request->integer('dark_mode');
		
		$status = 200;
		$message = null;
		
		if (auth()->check()) {
			// Call API endpoint
			$endpoint = '/users/' . $request->input('user_id') . '/dark-mode';
			$data = makeApiRequest('put', $endpoint, $request->all(), [], true);
			
			// Parsing the API response
			$status = (int)data_get($data, 'status');
			$message = data_get($data, 'message', t('unknown_error'));
			
			// HTTP Error Found
			if (!data_get($data, 'isSuccessful')) {
				return ajaxResponse()->json(['message' => $message], $status);
			}
			
			// Get entry resource
			$user = data_get($data, 'result');
			$darkMode = (int)data_get($user, 'dark_mode', 0);
		}
		
		// Set or remove dark mode cookie
		if ($darkMode == 1) {
			Cookie::set('darkTheme', 'dark');
			$message = !empty($message) ? $message : t('dark_mode_is_set');
		} else {
			Cookie::forget('darkTheme');
			$message = !empty($message) ? $message : t('dark_mode_is_disabled');
		}
		
		// AJAX response data
		$result = [
			'userId'   => $request->integer('user_id'),
			'darkMode' => $darkMode,
			'message'  => $message,
		];
		
		return ajaxResponse()->json($result, $status);
	}
}
