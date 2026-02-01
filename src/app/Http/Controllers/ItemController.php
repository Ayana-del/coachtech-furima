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

        // 自分の出品した商品は表示しない
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        // キーワード検索：タブの状態に関わらず適用
        if ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%");
        }

        // タブによる絞り込み
        if ($tab === 'mylist') {
            if (Auth::check()) {
                // マイリスト（自分がいいねした商品）かつ検索ワードに一致するもの
                $query->whereHas('likes', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            } else {
                // 未ログイン時にマイリストタブを選択した場合は何も表示しない
                $query->whereRaw('1 = 0');
            }
        }
        // $tab が 'recommend' の場合は、追加の whereHas を行わず全件（+キーワード）を表示

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
        // 1. 画像の保存処理
        $image_path = null;
        if ($request->hasFile('image_url')) {
            $image_path = $request->file('image_url')->store('items', 'public');
        }

        // 2. itemsテーブルへの保存
        $item = Item::create([
            'user_id'     => Auth::id(),
            'condition_id' => $request->condition_id,
            'name'        => $request->name,
            'brand'       => $request->brand,
            'price'       => $request->price,
            'description' => $request->description,
            'image_url'     => $image_path,
        ]);

        // 3. 中間テーブル（category_item）への紐付け
        if ($request->categories) {
            $item->categories()->attach($request->categories);
        }

        // 出品完了後、トップページへ遷移（または詳細画面など）
        return redirect()->route('item.index');
    }
}
