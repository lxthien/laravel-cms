<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Menu;
use App\Models\ContactRequest;
use App\Models\Comment;
use App\Models\User;
use App\Observers\AdminNotificationObserver;

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
        // Register Observers for Notifications
        ContactRequest::observe(AdminNotificationObserver::class);
        Comment::observe(AdminNotificationObserver::class);
        User::observe(AdminNotificationObserver::class);

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
