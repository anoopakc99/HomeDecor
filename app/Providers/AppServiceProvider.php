<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $categories = \Illuminate\Support\Facades\Cache::remember('navbar_categories', 3600, function () {
                return \App\Models\Category::whereNull('parent_id')
                    ->with('children')
                    ->get();
            });

            $view->with('navbarCategories', $categories);
        });
    }
}
