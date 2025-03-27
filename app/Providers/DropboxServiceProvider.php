<?php


namespace App\Providers;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;

class DropboxServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(): void
	{
		Storage::extend('dropbox', function ($app, $config) {
			$client = new DropboxClient($config['authorization_token']);
			$adapter = new DropboxAdapter($client);
			
			return new FilesystemAdapter(
				new Filesystem($adapter, $config),
				$adapter,
				$config
			);
		});
	}
}
