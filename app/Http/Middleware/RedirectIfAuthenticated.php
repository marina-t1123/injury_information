<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    private const GUARD_USER = 'users';
    private const GUARD_DOCTOR = 'doctors';
    //ここでの定数の値は、config>auth.phpのguardで設定した「users」「doctors」と
    //設定した内容(Guard名)と合わせて、定数の値の文字列を設定する必要がある。

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //     //もし、ログインをしていた場合
        //         return redirect(RouteServiceProvider::HOME);
        //         //サービスプロバイダのHOMEにリダイレクトさせる。
        //     }
        // }

        //user(トレーナーユーザー・ログイン済み)がアクセスした際のリダイレクト処理
        if(Auth::guard(self::GUARD_USER)->check() && $request->routeIs('user.*')){
        //もし、user(トレーナーユーザー)で認証しているかcheckメソッドでチェックをして認証していた場合
        //かつ、リクエストが名前付きルート(/user.*)だった場合
            return redirect(RouteServiceProvider::HOME);
            //RouteServiceProvider.phpで設定したuser(トレーナーユーザー)のログイン後のリダイレクト先を指定
        }

        //doctorユーザー(ログイン済み)がアクセスした際のリダイレクト処理
        if(Auth::guard(self::GUARD_DOCTOR)->check() && $request->routeIs('doctor.*')){
        //もし、trainerユーザーで認証しているかcheckメソッドでチェックをして認証していた場合
        //かつ、リクエストが名前付きルート(/trainer.*)だった場合
            return redirect(RouteServiceProvider::DOCTOR_HOME);
            //RouteServiceProvider.phpで設定したドクターユーザーのログイン後のリダイレクト先を指定
        }

        return $next($request);
    }
}
