<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Route;

class Authenticate extends Middleware
{
    //Authenticateクラスのプロパティとして、
    //遷移先としてトレーナーとドクターのログイン画面のURLを作成する。
    protected $trainer_route = 'trainer.login';
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
            if(Route::is('trainer.*')){
                return route($this->trainer_route);
            }elseif(Route::is('doctor.*')){
                return route($this->doctor_route);
            }
        }
    }
}
