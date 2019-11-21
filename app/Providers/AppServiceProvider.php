<?php

namespace Marketplace\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\UrlGenerator;

use Marketplace\Option;
use Marketplace\Category;
use Marketplace\Page;
use Marketplace\Product;
use Marketplace\Balance;
use Marketplace\Transaction;
use Marketplace\TransactionPayment;
use Marketplace\TransactionProduct;
use Marketplace\TransactionShipping;
use Marketplace\Footer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        /*
        |-------------------------------------------------------------------------
        | Https
        |-------------------------------------------------------------------------
        */

        if (!empty(config('app.force_https')))
        {
            $url->forceScheme('https');
        }

        /*
        |-------------------------------------------------------------------------
        | Schema
        |-------------------------------------------------------------------------
        */

        Schema::defaultStringLength(191);

        /*
        |-------------------------------------------------------------------------
        | Meta Tag
        |-------------------------------------------------------------------------
        */

        $options = Option::get();
        foreach ($options as $option)
        {
            // From logo-white to logo
            $option->type = str_replace('logo-white', 'logo', $option->type);

            // From meta- to seo-
            if ($option->type != 'meta-header')
            {
                $option->type = str_replace('meta-', 'seo-', $option->type);
            }

            // From - to _
            $option->type = str_replace('-', '_', $option->type);

            // Return View
            View::share($option->type, $option->content);
        }

        /*
        |-------------------------------------------------------------------------
        | Category Shared Database
        |-------------------------------------------------------------------------
        */

        $categories = Category::where('parent_id', null)
            ->orderBy('id', 'ASC')
            ->get();
        View::share('categories', $categories);

        /*
        |-------------------------------------------------------------------------
        | Footer Shared Database
        |-------------------------------------------------------------------------
        */

        $footer_one = Footer::where('id', 1)->first();
        View::share('footer_one', $footer_one);
        
        $footer_two = Footer::where('id', 2)->first();
        View::share('footer_two', $footer_two);

        $footer_three = Footer::where('id', 3)->first();
        View::share('footer_three', $footer_three);

        $footer_four = Footer::where('id', 4)->first();
        View::share('footer_four', $footer_four);

        $footer_shipping = Footer::where('position_id', 2)
            ->orderBy('id', 'ASC')
            ->get();
        View::share('footer_shipping', $footer_shipping);
        
        /*
        |-------------------------------------------------------------------------
        | Recomendation Produk Shared Database
        |-------------------------------------------------------------------------
        */

        $products = Product::where('stock', '>', '0')->inRandomOrder()->take(3)->get();
        View::share('productRecomendation', $products);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
