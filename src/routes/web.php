<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProductController;

// 商品一覧画面
Route::get('/', [ProductController::class, 'index'])->name('product.index');
// 認証完了後の結果画面（プロフィール）
Route::get('/profile', function () {
    return view('profile.index');
})->middleware(['auth', 'verified'])->name('profile.index');

// メール認証画面の表示
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// メール再送処理
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back();
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// メール内の認証リンクをクリックした時の処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('profile.index'); // 認証完了後にプロフィールへ
})->middleware(['auth', 'signed'])->name('verification.verify');
