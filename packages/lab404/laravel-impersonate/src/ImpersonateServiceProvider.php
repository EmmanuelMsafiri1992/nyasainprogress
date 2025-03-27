<?php


namespace Larapen\Impersonate;

use Larapen\Impersonate\Controllers\ImpersonateController;
use Larapen\Impersonate\Middleware\ProtectFromImpersonation;

/**
 * Class ServiceProvider
 *
 * @package Lab404\Impersonate
 */
class ImpersonateServiceProvider extends \Lab404\Impersonate\ImpersonateServiceProvider
{
	/**
	 * Register routes macro.
	 *
	 * @return void
	 */
	protected function registerRoutesMacro()
	{
		$router = $this->app['router'];
		
		$router->macro('impersonate', function () use ($router) {
			$router->get('impersonate/take/{id}', [ImpersonateController::class, 'take'])->name('impersonate');
			$router->get('impersonate/leave', [ImpersonateController::class, 'leave'])->name('impersonate.leave');
		});
	}
	
	/**
	 * Register plugin middleware.
	 *
	 * @return void
	 */
	public function registerMiddleware()
	{
		$this->app['router']->aliasMiddleware('impersonate.protect', ProtectFromImpersonation::class);
	}
	
	/**
	 * Old register plugin middleware
	 * (Need to be removed)
	 *
	 * @return void
	 */
	public function registerMiddlewares()
	{
		$this->registerMiddleware();
	}
}
