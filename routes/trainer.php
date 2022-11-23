<?php

use App\Http\Controllers\TopPageController; //トップページのコントローラ
use App\Http\Controllers\Trainer\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Trainer\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Trainer\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Trainer\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Trainer\Auth\NewPasswordController;
use App\Http\Controllers\Trainer\Auth\PasswordResetLinkController;
use App\Http\Controllers\Trainer\Auth\RegisteredUserController;
use App\Http\Controllers\Trainer\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

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

// トップページ
// Route::get('/', [TopPageController::class, 'show'])->name('top-page.show');

Route::get('/', function () {
    return view('trainer.welcome');
});

Route::get('/dashboard', function () {
    return view('trainer.dashboard');
})->middleware(['auth:trainers'])->name('dashboard');
//->middleware(['auth])だとGuardが設定されていない。なので、(['auth:trainers])として
//Guardの設定を追加するようにする。こうすることで、ログインしているかつ、trainersの権限を持っていたら
//ダッシュボードが表示できるということになる。

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.update');
});

Route::middleware('auth:trainers')->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth:trainers', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth:trainers', 'throttle:6,1'])
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});
