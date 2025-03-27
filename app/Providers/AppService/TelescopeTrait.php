<?php


namespace App\Providers\AppService;

use Illuminate\Foundation\Http\Kernel;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait TelescopeTrait
{
	/**
	 * @return void
	 */
	private function runInspection(): void
	{
		// Is Debug Bar enabled?
		$isDebugBarEnabled = (config('app.debug') && config('larapen.core.debugBar'));
		if (!$isDebugBarEnabled) {
			Debugbar::disable();
		}
		
		// Know if the server is taking too long to respond than a specific timeout
		$isRequestLifecycleCanBeChecked = (!app()->isProduction());
		if ($isRequestLifecycleCanBeChecked) {
			/**
			 * @var Kernel $kernel
			 */
			$kernel = $this->app[Kernel::class] ?? null;
			if (!is_null($kernel)) {
				if (method_exists($kernel, 'whenRequestLifecycleIsLongerThan')) {
					$httpRequestTimeout = (int)config('larapen.core.performance.httpRequestTimeout', 1);
					$kernel->whenRequestLifecycleIsLongerThan($httpRequestTimeout, function ($startedAt, $request, $response) {
						$message = 'The script detects that your server is taking too long to respond.';
						Log::warning($message);
					});
				}
			}
		}
		
		/*
		 * Configuring Eloquent Strictness
		 * - Disable lazy loading (completely) to increase performance optimization
		 * - Prevent silently discarding attributes
		 * WARNING: Never apply that on production to prevent exception errors.
		 */
		if (!appIsBeingInstalledOrUpgraded()) {
			$shouldBeStrict = (
				!app()->isProduction()
				&& config('larapen.core.performance.preventLazyLoading')
				&& request()->segment(1) != 'feed'
			);
			Model::preventLazyLoading($shouldBeStrict);
		}
	}
}
