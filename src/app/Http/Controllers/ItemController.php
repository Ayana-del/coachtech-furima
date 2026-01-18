<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        $query = Item::query();

        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $items = $query->get();

        return view('items.index', compact('items', 'tab', 'keyword'));
    }
}