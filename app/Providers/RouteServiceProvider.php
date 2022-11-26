<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route; //Routeファサードを読み込んでいる。

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     * アプリケーションの"ホーム"ルートへのパスです。
     *
     * This is used by Laravel authentication to redirect users after login.
     * これは、Laravel認証で、ログイン後にユーザーをリダイレクトするために使用されます。
     *
     * @var string
     */
    public const HOME = '/dashboard';
    public const DOCTOR_HOME = '/doctor/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     * ルートモデルのバインディング、パターンフィルタなどを定義します。(ルート情報の設定をする。)
     *
     * @return void
     */
    public function boot()
    //bootメソッド：Laravelの画面を読み込んで、すべてのサービスプロパイダ
    //(サービスコンテナにサービスを登録する仕組み)が読み込まれてから実行されるメソッド
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            //User(トレーナー)のルート情報
            Route::prefix('/')
            //このように指定することで、「/doctor」のURL以外はトレーナーのURLということになる。
                ->as('user.')
                //asメソッドを使用してweb.phpの各ルートでのURLの頭に「user.」をつけるようにする
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
                //webミドルウェア:LaravelでView側を表示して、リクエスト・レスポンスを返す場合に使用する
                //routeファサードのmiddlewareメソッドを使って、'web'のミドルウェアを
                //groupメソッド内で、base_path(ヘルパ関数)で指定しているroutesディレクトリのweb.php(ルーティングの設定ファイル)のすべての
                //URLに割り当てている。

            //ドクターユーザーのルート情報を作成
            Route::prefix('doctor')
            //doctor.phpの各ルートでのURLの頭に「doctor」がつく
                ->as('doctor.')
                //asメソッドを使用してdoctor.phpの各ルートでのURLの頭に「doctor.」をつけるようにする
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/doctor.php'));
                //webミドルウェアをdoctor.php(ドクターのルーティングファイル)の
                //すべてのURL(各ルートで指定されているURL)に割り当てている。
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
