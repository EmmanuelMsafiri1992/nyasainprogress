<?php


namespace Larapen\TextToImage;

use Illuminate\Support\ServiceProvider;

class TextToImageServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register(): void
	{
		$this->app->bind('texttoimage', fn () => new TextToImage());
	}
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['texttoimage'];
    }
}
