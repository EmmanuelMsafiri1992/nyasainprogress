<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyAjax
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		if (!isFromAjax($request)) {
			abort(404);
		}
		
		return $next($request);
	}
}
