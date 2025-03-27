<?php


namespace App\Exceptions\Handler;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
 * Too Many Connections Exception
 */

trait DBTooManyConnectionsExceptionHandler
{
	/**
	 * @param \Throwable $e
	 * @return bool
	 */
	protected function isDBTooManyConnectionsException(\Throwable $e): bool
	{
		return (
			appInstallFilesExist()
			&& str_contains($e->getMessage(), 'max_user_connections')
			&& str_contains($e->getMessage(), 'active connections')
		);
	}
	
	/**
	 * @param \Throwable $e
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
	 */
	protected function responseDBTooManyConnectionsException(\Throwable $e, Request $request): Response|JsonResponse
	{
		$message = $this->getDBTooManyConnectionsExceptionMessage($e, $request);
		
		return $this->responseCustomError($e, $request, $message);
	}
	
	// PRIVATE
	
	/**
	 * @param \Throwable $e
	 * @param \Illuminate\Http\Request $request
	 * @return string
	 */
	private function getDBTooManyConnectionsExceptionMessage(\Throwable $e, Request $request): string
	{
		// Too many connections
		$message = 'We are currently receiving a large number of connections. ';
		$message .= 'Please try again later. We apologize for the inconvenience.';
		
		return $message;
	}
}
