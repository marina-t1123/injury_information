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
    //ユーザーのログイン後、「/dashboard」にリダイレクトをさせるために、リダイレクトのパスを定数として設定する。
    // public const HOME = '/dashboard';
    //トレーナーユーザーがログインした後のリダイレクト先を定数として設定する。
    //定数の命名規則は、基本的に大文字でつける。
    public const TRAINER_HOME = '/trainer/dashboard';
    //ドクターユーザーがログインした後のリダイレクト先を定数として設定する。
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
        //vender配下にあるRouteServiceProvider.phpのroutesメソッド

            //Routeメソッドで元々２つのコードが書かれている。
            //Route情報は大きく２種類のパターンがある。
            //１つ目はmiddlewareのwebを使用するRoute、２つ目はmiddlewareのapiを使用するRoute。

            Route::prefix('api')
            //api.phpの各routeでのURLの頭に「/api」がつく。
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
            //api:フロント側をすべてJavaScriptなどで作成する場合に使用する。
            //apiミドルウェアをapi.php(apiのルーティングファイル)の
            //すべてのURL(各ルートで指定されているURL)に割り当てている。

            //自動で作成されたユーザーのルート情報
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
            //webミドルウェア:LaravelでView側を表示して、リクエスト・レスポンスを返す場合に使用する
            //routeファサードのmiddlewareメソッドを使って、'web'のミドルウェアを
            //groupメソッド内で、base_path(ヘルパ関数)で指定しているroutesディレクトリのweb.php(ルーティングの設定ファイル)のすべての
            //URLに割り当てている。

            //トレーナーユーザーのルート情報を作成
            Route::prefix('trainer')
            //trainer.phpの各ルートでのURLの頭に「trainer」がつく
                ->as('trainer.')
                //asメソッドを使用してtrainer.phpの各ルートでのURLの頭に「trainer.」をつけるようにする
                ->middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/trainer.php'));
                //webミドルウェアをtrainer.php(トレーナーのルーティングファイル)の
                //すべてのURL(各ルートで指定されているURL)に割り当てている。

            //ドクターユーザーのルート情報を作成
            Route::prefix('doctor')
            //doctor.phpの各ルートでのURLの頭に「doctor」がつく
                ->as('doctor.')
                //asメソッドを使用してtrainer.phpの各ルートでのURLの頭に「doctor.」をつけるようにする
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
