<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyAPIAccess
{
	/**
	 * Handle an incoming request.
	 *
	 * Prevent any other application to call the API
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		if (
			!(app()->environment('local'))
			&& (
				!request()->hasHeader('X-AppApiToken')
				|| request()->header('X-AppApiToken') !== config('larapen.core.api.token')
			)
		) {
			$message = 'You don\'t have access to this API.';
			
			return apiResponse()->forbidden($message);
		}
		
		return $next($request);
	}
}
