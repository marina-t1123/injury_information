<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    //Authenticateクラスのプロパティとして、
    //遷移先としてuser(トレーナー)とドクターのログイン画面のURLを作成する。
    protected $user_route = 'user.login';
    protected $doctor_route = 'doctor.login';

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if(Route::is('doctor.*')){
                return route($this->doctor_route);
            } else {
                return route($this->user_route);
            }
        }
    }
}
