<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;

class Case07_ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_必要な情報が表示される()
    {
        $condition = Condition::create(['name' => '良好']);
        $category = Category::create(['content' => 'ファッション']);
        $seller = User::factory()->create();
        $commentUser = User::factory()->create(['name' => 'コメント太郎']);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '詳細テスト商品',
            'brand' => 'テストブランド',
            'price' => 5000,
            'description' => 'これは詳細な説明です',
            'image_url' => 'test_item.png'
        ]);

        $item->categories()->attach($category->id);

        Like::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
        ]);

        Comment::create([
            'user_id' => $commentUser->id,
            'item_id' => $item->id,
            'comment' => '素晴らしい商品ですね！'
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertStatus(200);
        $response->assertSee('詳細テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('5,000');
        $response->assertSee('これは詳細な説明です');
        $response->assertSee('ファッション');
        $response->assertSee('良好');

        $response->assertSee('1');

        $response->assertSee('コメント太郎');
        $response->assertSee('素晴らしい商品ですね！');

        $response->assertSee('test_item.png');
    }

    public function test_複数選択されたカテゴリが表示されているか()
    {
        $condition = Condition::create(['name' => '良好']);
        $cat1 = Category::create(['content' => 'レディース']);
        $cat2 = Category::create(['content' => 'アクセサリー']);
        $seller = User::factory()->create();

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'マルチカテゴリ商品',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test.png'
        ]);
        $item->categories()->attach([$cat1->id, $cat2->id]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));

        $response->assertSee('レディース');
        $response->assertSee('アクセサリー');
    }
}
