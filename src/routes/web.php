<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

Route::get('/', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('items.like');
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchasePage'])->name('purchases.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'storePurchase'])->name('purchases.store');
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'editAddress'])->name('addresses.edit');
    Route::patch('/purchase/address/{item_id}', [AddressController::class, 'updateAddress'])->name('addresses.update');
    Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'successPurchase'])->name('purchases.success');
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
    Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])->name('comment.store');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back();
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('profile.edit');
})->middleware(['auth', 'signed'])->name('verification.verify');
