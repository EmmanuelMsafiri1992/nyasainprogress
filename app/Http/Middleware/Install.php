<?php


namespace App\Http\Middleware;

use App\Http\Middleware\Install\CheckInstallation;
use Closure;
use Illuminate\Http\Request;

class Install
{
	use CheckInstallation;
	
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return \Illuminate\Http\RedirectResponse|mixed
	 * @throws \App\Exceptions\Custom\CustomException
	 */
	public function handle(Request $request, Closure $next)
	{
		if ($this->isInstalled() && $this->installationIsNotInProgress()) {
			return redirect()->to('/');
		}
		
		return $next($request);
	}
}
