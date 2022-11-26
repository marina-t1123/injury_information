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
        //URLによってどのCookieを使用するか判定を行う
        //trainerから始まるURL
        if(request()->is('trainer')) {
            config(['session.cookie' => config('session.cookie_trainer')]);
        }

        //doctorから始まるURL
        if (request()->is('doctor')) {
            config(['session.cookie' => config('session.cookie_doctor')]);
        }
    }
}
