<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TopPageController; //トップページのコントローラ

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('user.welcome');
});

//LaravelReezeをインストールすると追加されるルート設定
Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware(['auth:users'])->name('dashboard');

//LaravelBreezeのインストール時に追加されるUserのルート情報を設定しているファイルの読み込み
require __DIR__.'/auth.php';
