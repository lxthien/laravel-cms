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
        view()->composer('*', function ($view) {
            $headerMenu = Menu::with('rootItems.children.children')->where('location', 'header')->first();
            $footerMenu = Menu::with('rootItems.children.children')->where('location', 'footer')->first();

            // Truyền vào view toàn cục
            $view->with(compact('headerMenu', 'footerMenu'));
        });
    }
}
