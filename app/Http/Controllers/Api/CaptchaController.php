<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @group Captcha
 */
class CaptchaController extends Controller
{
	/**
	 * Get CAPTCHA
	 *
	 * Calling this endpoint is mandatory if the captcha is enabled in the Admin panel.
	 * Return JSON data with an 'img' item that contains the captcha image to show and a 'key' item that contains the generated key to send for validation.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getCaptcha(): \Illuminate\Http\JsonResponse
	{
		// Call API endpoint
		$endpoint = '/captcha/api/' . config('settings.security.captcha');
		$captchaData = makeApiRequest('get', $endpoint, [], [], false, false);
		
		$sensitive = data_get($captchaData, 'sensitive');
		$key = data_get($captchaData, 'key');
		$img = data_get($captchaData, 'img');
		
		// Parsing the API response
		$isSuccess = (
			is_bool($sensitive)
			&& (!empty($key) && is_string($key))
			&& (!empty($img) && is_string($img))
		);
		$status = $isSuccess ? 200 : 400;
		$result = [
			'sensitive' => (bool)$sensitive,
			'key'       => $key,
			'img'       => $img,
		];
		$message = !$isSuccess ? 'Error found during captcha retrieving.' : null;
		
		$data = [
			'success' => $isSuccess,
			'result'  => $isSuccess ? $result : null,
			'message' => $message,
		];
		
		return apiResponse()->json($data, $status);
	}
}
