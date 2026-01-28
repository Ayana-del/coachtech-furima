<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function showPurchasePage($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('purchase.index', compact('item'));
    }

    /**
     * 購入処理を実行する
     */
    public function storePurchase(Request $request, $item_id)
    {
        // 1. 商品の存在確認
        $item = Item::findOrFail($item_id);

        // 2. バリデーション（支払い方法の選択など）
        $request->validate([
            'payment_method' => 'required', // 支払い方法は必須
        ], [
            'payment_method.required' => '支払い方法を選択してください',
        ]);

        return redirect()->route('item.index')->with('message', '購入が完了しました');
    }
}
