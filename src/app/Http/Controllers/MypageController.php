<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class MypageController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $user->load('profile');

        $tab = $request->query('tab', 'sell');
        $keyword = $request->query('keyword');

        // 1. 出品した商品の取得ロジック
        $sellQuery = Item::where('user_id', $user->id);

        // 修正点：検索キーワードがある場合、タブが 'sell' の時だけ絞り込む
        if ($keyword && $tab === 'sell') {
            $sellQuery->where('name', 'LIKE', "%{$keyword}%");
        }
        $sellItems = $sellQuery->get();

        // 2. 購入した商品の取得ロジック
        $buyQuery = Item::whereHas('orders', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });

        // 修正点：検索キーワードがある場合、タブが 'buy' の時だけ絞り込む
        if ($keyword && $tab === 'buy') {
            $buyQuery->where('name', 'LIKE', "%{$keyword}%");
        }
        $buyItems = $buyQuery->get();

        // ビューに tab と keyword を渡すことで、Blade側の hidden フィールド等で再利用可能にする
        return view('mypage.index', compact('user', 'sellItems', 'buyItems', 'tab', 'keyword'));
    }
}
