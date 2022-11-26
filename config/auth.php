<?php

//認証関連の設定ファイル
return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults(認証の初期設定)
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    //デフォルトのGuard設定をusers(トレーナー)に設定する
    'defaults' => [
        'guard' => 'users',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards(ガードの初期設定)
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session"
    | GuardでSessionを使用している。
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        //user(トレーナー)のガード設定
        'users' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        //ドクターのガード設定
        'doctors' => [
            'driver' => 'session',
            'provider' => 'doctors',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
		| データベースやEloquent(モデル)からデータを取得している
    |
    */

    'providers' => [
		//プロバイダ：ストレージ(DBなど)からユーザーを取得する方法を定義している。
        'users' => [
				//users(ユーザー名がuser)の場合
            'driver' => 'eloquent',
						//driverにeloquent(モデル)を指定して
            'model' => App\Models\User::class,
						//modelでUserを指定している。
        ],

        //ドクターのプロバイダ設定
        'doctors' => [
            'driver' => 'eloquent', //ドライバーとしてモデルを設定
            'model' => App\Models\Doctor::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that each reset token will be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],

        //ドクターのパスワードリセット設定
        'doctors' => [
            'provider' => 'doctors',
            'table' => 'doctor_password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
