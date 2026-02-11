<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Comment;

class Case09_CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_ログイン済みのユーザーはコメントを送信できる()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $condition = Condition::create(['name' => '良好']);
        $item = Item::create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test.png'
        ]);

        $this->actingAs($user);

        $commentData = ['comment' => 'テストコメントです'];
        $this->post(route('comment.store', ['item_id' => $item->id]), $commentData);

        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'テストコメントです'
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertSee('コメント (1)');
    }

    public function test_ログイン前のユーザーはコメントを送信できない()
    {
        $condition = Condition::create(['name' => '良好']);
        $item = Item::create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test.png'
        ]);

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), ['comment' => '未ログイン投稿']);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('comments', ['comment' => '未ログイン投稿']);
    }

    public function test_コメントが入力されていない場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $condition = Condition::create(['name' => '良好']);
        $item = Item::create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test.png'
        ]);

        $this->actingAs($user);

        $response = $this->post(route('comment.store', ['item_id' => $item->id]), ['comment' => '']);

        $response->assertSessionHasErrors(['comment' => 'コメントを入力してください']);
    }

    public function test_コメントが255字以上の場合、バリデーションメッセージが表示される()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $condition = Condition::create(['name' => '良好']);
        $item = Item::create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test.png'
        ]);

        $this->actingAs($user);

        $longComment = str_repeat('a', 256);
        $response = $this->post(route('comment.store', ['item_id' => $item->id]), ['comment' => $longComment]);

        $response->assertSessionHasErrors(['comment' => 'コメントは255文字以内で入力してください']);
    }
}
