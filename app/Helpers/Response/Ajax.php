<?php


namespace App\Helpers\Response;

Class Ajax
{
	/**
	 * @param array|null $data
	 * @param int $status
	 * @param array $headers
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function json(?array $data = [], int $status = 200, array $headers = []): \Illuminate\Http\JsonResponse
	{
		$data = is_array($data) ? $data : [];
		
		$status = isValidHttpStatus($status) ? $status : 500;
		$headers = addContentTypeHeader('application/json', $headers);
		
		return response()->json($data, $status, $headers, JSON_UNESCAPED_UNICODE);
	}
	
	/**
	 * @param string|null $content
	 * @param int $status
	 * @param array $headers
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
	 */
	public function text(?string $content = '', int $status = 200, array $headers = [])
	{
		$content = is_string($content) ? $content : '';
		
		$status = isValidHttpStatus($status) ? $status : 500;
		$headers = addContentTypeHeader('text/plain', $headers);
		
		return response($content, $status)->withHeaders($headers);
	}
}
