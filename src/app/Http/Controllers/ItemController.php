<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;

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
    public function show($item_id)
    {
        $item = Item::with(['categories', 'condition', 'comments.user', 'likes'])
            ->findOrFail($item_id);

        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $item->likes->contains('user_id', Auth::id());
        }

        return view('items.show', compact('item', 'isLiked'));
    }
    public function toggleLike($item_id)
    {
        // 未ログインユーザーはログイン画面へリダイレクト
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = Auth::id();

        // 1 & 3. すでにいいねしているか確認（トグルロジック）
        $like = Like::where('item_id', $item_id)->where('user_id', $user_id)->first();

        if ($like) {
            // 3. すでに存在すれば削除（いいね解除）
            $like->delete();
            // 3-a. これにより $item->likes->count() が減少します
        } else {
            // 1. 存在しなければ作成（いいね登録）
            Like::create([
                'item_id' => $item_id,
                'user_id' => $user_id,
            ]);
            // 1-a. これにより $item->likes->count() が増加します
        }

        // 元の詳細画面に戻る（再描画されてアイコンの色とカウントが更新される）
        return back();
    }
    public function purchase($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('items.purchase', compact('item'));
    }
    public function storeComment(CommentRequest $request, $item_id)
    {
        Comment::create([
            'item_id' => $item_id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        // 保存後、詳細画面に戻る
        return back();
    }
}