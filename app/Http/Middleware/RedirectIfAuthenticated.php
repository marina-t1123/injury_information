<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    private const GUARD_TRAINER = 'trainers';
    private const GUARD_DOCTOR = 'doctors';
    //ここでの定数の値は、config>auth.phpのguardで設定した「trainers」「doctors」と
    //設定した内容と合わせて文字列を設定する必要がある。

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

        //trainerユーザー(ログイン済み)がアクセスした際のリダイレクト処理
        if(Auth::guard(self::GUARD_TRAINER)->check() && $request->routeIs('trainer.*')){
        //もし、trainerユーザーで認証しているかcheckメソッドでチェックをして認証していた場合
        //かつ、リクエストが名前付きルート(/trainer.*)だった場合
            return redirect(RouteServiceProvider::TRAINER_HOME);
            //RouteServiceProvider.phpで設定したトレーナーユーザーのログイン後のリダイレクト先を指定
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
