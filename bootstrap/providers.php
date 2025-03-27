<?php

return [
    App\Providers\AliasServiceProvider::class,
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\DropboxServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\MacrosServiceProvider::class,
    App\Providers\PluginsServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    Larapen\Captcha\CaptchaServiceProvider::class,
    Larapen\Feed\FeedServiceProvider::class,
    Larapen\Honeypot\HoneypotServiceProvider::class,
    Larapen\Impersonate\ImpersonateServiceProvider::class,
    Larapen\LaravelDistance\DistanceServiceProvider::class,
    Larapen\LaravelMetaTags\MetaTagsServiceProvider::class,
    Larapen\ReCaptcha\ReCaptchaServiceProvider::class,
    Larapen\TextToImage\TextToImageServiceProvider::class,
];
