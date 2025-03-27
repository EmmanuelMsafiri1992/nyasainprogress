<?php


namespace App\Exceptions\Handler;

use App\Helpers\UrlGen;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait AuthenticationExceptionHandler
{
	/**
	 * @param \Throwable $e
	 * @return bool
	 */
	protected function isAuthenticationException(\Throwable $e): bool
	{
		return ($e instanceof AuthenticationException);
	}
	
	/**
	 * @param \Throwable $e
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	protected function responseAuthenticationException(\Throwable $e, Request $request): Response|JsonResponse|RedirectResponse
	{
		$message = t('unauthenticated_or_token_expired');
		
		if (!isFromApi($request) && !isFromAjax($request)) {
			notification($message, 'error');
			
			return redirect()->guest(UrlGen::loginPath());
		}
		
		return $this->responseCustomError($e, $request, $message, Response::HTTP_UNAUTHORIZED);
	}
}
