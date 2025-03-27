<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @param ...$guards
	 * @return \Illuminate\Http\RedirectResponse|mixed
	 */
	public function handle(Request $request, Closure $next, ...$guards)
	{
		$guards = empty($guards) ? [null] : $guards;
		
		foreach ($guards as $guard) {
			if (auth()->guard($guard)->check()) {
				if (isFromApi()) {
					return $next($request);
				}
				
				$url = isFromAdminPanel() ? admin_uri() : '/';
				$url .= '?login=success';
				
				return redirect()->to($url);
			}
		}
		
		return $next($request);
	}
}
