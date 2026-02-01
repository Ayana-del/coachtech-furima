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

        return view('purchases.index', compact('item', 'profile'));
    }

    /**
     * 商品購入処理
     */
    public function storePurchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        return redirect()->route('item.index')->with('message', '購入が完了しました');
    }
}
