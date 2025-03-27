<?php


namespace App\Providers\PluginsService;

use App\Helpers\Arr;

trait PluginsTrait
{
	/**
	 * Load all the installed plugins
	 *
	 * @return void
	 */
	private function loadPlugins(): void
	{
		$plugins = plugin_installed_list();
		$plugins = collect($plugins)
			->map(function ($item, $key) {
				if (is_object($item)) {
					$item = Arr::fromObject($item);
				}
				if (!empty($item['item_id'])) {
					$item['installed'] = plugin_check_purchase_code($item);
				}
				
				return $item;
			})->toArray();
		
		config()->set('plugins', $plugins);
		config()->set('plugins.installed', collect($plugins)->whereStrict('installed', true)->toArray());
	}
}
