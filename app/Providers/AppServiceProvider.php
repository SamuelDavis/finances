<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::macro("invalid", function (
            string $field,
            string $name = "errors",
        ) {
            $messageBag = View::shared($name);
            if (!($messageBag instanceof MessageBag)) {
                return "";
            }

            return $messageBag->has($field) ? "true" : "false";
        });

        View::macro("old", function (
            string $name,
            string $value,
            bool $fallback = false,
        ) {
            if (!empty(old())) {
                return old($name) === $value;
            }
            return $fallback;
        });
    }
}
