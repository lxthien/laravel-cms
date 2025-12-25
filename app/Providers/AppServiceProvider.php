<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Menu;

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
        // Only run view composer for HTTP requests, not console commands
        if ($this->app->runningInConsole()) {
            return;
        }

        view()->composer('*', function ($view) {
            $headerMenu = Menu::with('rootItems.children.children')->where('location', 'header')->first();
            $footerMenu = Menu::with('rootItems.children.children')->where('location', 'footer')->first();

            // Truyền vào view toàn cục
            $view->with(compact('headerMenu', 'footerMenu'));
        });
    }
}
