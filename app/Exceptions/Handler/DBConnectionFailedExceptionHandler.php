<?php


namespace App\Exceptions\Handler;

use App\Helpers\DBTool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait DBConnectionFailedExceptionHandler
{
	/**
	 * Test Database Connection
	 *
	 * @return bool
	 * @throws \App\Exceptions\Custom\CustomException
	 */
	private function isDBConnectionFailedException(): bool
	{
		$pdo = DBTool::getPDOConnexion([], true);
		
		return (appInstallFilesExist() && !($pdo instanceof \PDO));
	}
	
	/**
	 * @param \Throwable $e
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
	 */
	protected function responseDBConnectionFailedException(\Throwable $e, Request $request): Response|JsonResponse
	{
		$message = $this->getDBConnectionFailedExceptionMessage($e, $request);
		
		return $this->responseCustomError($e, $request, $message);
	}
	
	// PRIVATE
	
	/**
	 * @param \Throwable $e
	 * @param \Illuminate\Http\Request $request
	 * @return string
	 */
	private function getDBConnectionFailedExceptionMessage(\Throwable $e, Request $request): string
	{
		$message = 'Connection to the database failed.';
		if (!empty($e->getMessage())) {
			$message .= "\n" . $e->getMessage();
		}
		
		return $message;
	}
}
