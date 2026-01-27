<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MypageController;

// --- 1. 誰でも見れるルート ---
//商品一覧画面
Route::get('/', [ItemController::class, 'index'])->name('item.index');
//商品詳細画面
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// --- 2. ログイン & メール認証完了後にアクセスできるルート  ---
Route::middleware(['auth', 'verified'])->group(function () {
    //いいね登録・解除
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('items.like');
    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    // 商品購入画面（GET)
    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('item.purchase');
    //出品
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
    // コメント (POST)
    Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])->name('comment.store');
    // プロフィール編集画面 (GET)
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // プロフィール更新処理 (POST) -
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// --- 3. メール認証関連 (Fortifyの要件) ---
// メール認証誘導画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// メール再送処理
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back();
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// 認証リンククリック時の処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('profile.edit');
})->middleware(['auth', 'signed'])->name('verification.verify');
