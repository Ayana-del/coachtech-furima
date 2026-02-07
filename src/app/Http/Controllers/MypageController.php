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

        $sellQuery = Item::where('user_id', $user->id);

        if ($keyword && $tab === 'sell') {
            $sellQuery->where('name', 'LIKE', "%{$keyword}%");
        }

        $sellItems = $sellQuery->latest()->get();

        $buyQuery = Item::join('orders', 'items.id', '=', 'orders.item_id')
            ->where('orders.user_id', $user->id)
            ->select('items.*');

        if ($keyword && $tab === 'buy') {
            $buyQuery->where('items.name', 'LIKE', "%{$keyword}%");
        }

        $buyItems = $buyQuery->orderBy('orders.created_at', 'desc')->get();

        return view('mypage.index', compact('user', 'sellItems', 'buyItems', 'tab', 'keyword'));
    }
}
