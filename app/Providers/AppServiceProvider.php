<?php

namespace App\Providers;

use App\Models\bank\Banks;
use App\Models\bank\Enterprise;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        //

        $share_listbanks = Banks::with(['enterprise','projects'])->get();

        $share_enterprise = Enterprise::with([
            'project.city' => function ($query) {
            $query->where('inactive', '=', 0);
        },])->get();


        View::share('share_listbank', $share_listbanks);
        View::share('share_enterprise', $share_enterprise);

    }
}
