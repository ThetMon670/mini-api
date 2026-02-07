<?php

namespace App\Providers;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS for non-local environments
        if (!app()->environment('local')) {
            request()->server->set('HTTPS', 'on');
        }

        // Defer DB listener until app is fully booted
        $this->app->booted(function () {
            DB::listen(function ($query) {
                Log::info('SQL Query Executed:', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'execution_time' => $query->time . 'ms',
                ]);
            });
        });
        Scramble::configure()
        ->withDocumentTransformers(function (OpenApi $openApi) {
            $openApi->secure(
                SecurityScheme::http('bearer')
            );
        });
    }
}