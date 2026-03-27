<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TelescopeServiceProvider::class,

    // Only load Telescope in non-production environments
    ...(env('APP_ENV') !== 'production' ? [App\Providers\TelescopeServiceProvider::class] : []),
];
