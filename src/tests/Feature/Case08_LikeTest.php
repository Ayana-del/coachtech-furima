<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Like;

class Case08_LikeTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねアイコンを押下することによって、いいねした商品として登録することができる()
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

        $this->get(route('item.show', ['item_id' => $item->id]));

        $this->post(route('items.like', ['item_id' => $item->id]));

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertSee('1');
    }

    public function test_追加済みのアイコンは色が変化する()
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

        $this->post(route('items.like', ['item_id' => $item->id]));

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertSee('img/ハートロゴ_ピンク.png');
    }

    public function test_再度いいねアイコンを押下することによって、いいねを解除することができる()
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

        Like::create(['user_id' => $user->id, 'item_id' => $item->id]);

        $this->get(route('item.show', ['item_id' => $item->id]));

        $this->post(route('items.like', ['item_id' => $item->id]));

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', ['item_id' => $item->id]));
        $response->assertSee('0');
    }
}
