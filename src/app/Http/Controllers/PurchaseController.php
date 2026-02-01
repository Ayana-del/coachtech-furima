<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{

    public function showPurchasePage($item_id)
    {
        $item = Item::findOrFail($item_id);

        $user = Auth::user();
        $profile = $user->profile;

        return view('purchases.show', compact('item', 'profile'));
    }

    public function storePurchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // 1. 注文情報をデータベースに保存（これが重要！）
        \App\Models\Order::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
        ]);

        // 購入完了後、商品一覧へ
        return redirect()->route('item.index')->with('message', '購入が完了しました');
    }
}
