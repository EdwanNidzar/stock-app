<?php

namespace App\Providers;

use App\Models\CategoryProduct;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrap();
        Product::observe(\App\Observers\ProductObserver::class);
        Supplier::observe(\App\Observers\SupplierObserver::class);
        CategoryProduct::observe(\App\Observers\CategoryProductObserver::class);
    }
}
