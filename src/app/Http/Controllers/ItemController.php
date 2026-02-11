<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Condition;

class ItemController extends Controller
{

    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $keyword = $request->query('keyword');

        $query = Item::query();

        if (Auth::check()) {
            $query->where('items.user_id', '!=', Auth::id());
        }

        if ($keyword) {
            $query->where('items.name', 'LIKE', "%{$keyword}%");
        }

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $query->join('likes', 'items.id', '=', 'likes.item_id')
                    ->where('likes.user_id', Auth::id())
                    ->orderBy('likes.created_at', 'desc')
                    ->select('items.*');
            } else {
                $query->whereRaw('1 = 0');
            }
        } else {
            $query->latest('items.created_at');
        }

        $items = $query->get();

        return view('items.index', compact('items', 'tab', 'keyword'));
    }
    public function show($item_id)
    {
        $item = Item::with(['categories', 'condition', 'comments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'comments.user', 'likes'])
            ->findOrFail($item_id);

        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $item->likes->contains('user_id', Auth::id());
        }

        return view('items.show', compact('item', 'isLiked'));
    }
    public function toggleLike($item_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user_id = Auth::id();

        $like = Like::where('item_id', $item_id)->where('user_id', $user_id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'item_id' => $item_id,
                'user_id' => $user_id,
            ]);
        }

        return back();
    }
    public function purchase($item_id)
    {
        $item = Item::findOrFail($item_id);

        $profile = null;
        if (Auth::check()) {
            $profile = Auth::user()->profile;
        }

        return view('items.purchase', compact('item', 'profile'));
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
    public function create()
    {
        // 出品画面の選択肢として使用するデータを取得
        $categories = Category::all();
        $conditions = Condition::all();

        return view('items.create', compact('categories', 'conditions'));
    }

    /**
     * 商品出品処理
     */
    public function store(ItemRequest $request)
    {
        $image_path = null;
        if ($request->hasFile('img_url')) {
            $image_path = $request->file('img_url')->store('items', 'public');
        }

        $item = Item::create([
            'user_id'      => Auth::id(),
            'condition_id' => $request->condition_id,
            'name'         => $request->name,
            'brand'        => $request->brand,
            'price'        => $request->price,
            'description'  => $request->description,
            'image_url'    => $image_path,
        ]);

        if ($request->categories) {
            $item->categories()->attach($request->categories);
        }

        return redirect()->route('mypage.index', ['tab' => 'sell']);
    }
}
