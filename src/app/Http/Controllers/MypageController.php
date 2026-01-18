<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\User;

class MypageController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $user->load('profile');

        $sellItems = $user->items;

        $buyItems = Item::whereHas('orders', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('mypage.index', compact('user', 'sellItems', 'buyItems'));
    }
}
