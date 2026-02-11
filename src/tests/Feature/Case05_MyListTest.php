<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Condition;

class Case05_MyListTest extends TestCase
{
    use RefreshDatabase;

    public function test_いいねをした商品が表示される()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $condition = Condition::create(['name' => '良好']);

        $likedItem = Item::create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name' => 'いいねした商品',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test.png'
        ]);

        $regularItem = Item::create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name' => '通常の商品',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test2.png'
        ]);

        $this->actingAs($user);

        $user->likedItems()->attach($likedItem->id);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいねした商品');
        $response->assertDontSee('通常の商品');
    }

    public function test_購入済み商品にSoldのラベルが表示される()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '売切れマイリスト商品',
            'price' => 2000,
            'description' => '説明',
            'image_url' => 'sold.png'
        ]);

        $this->actingAs($user);
        $user->likedItems()->attach($item->id);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '東京都',
            'building' => 'テストビル'
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('売切れマイリスト商品');
        $response->assertSee('Sold');
    }

    public function test_未認証の場合は何も表示されない()
    {
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);
        Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '未認証で見えないはずの商品',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test.png'
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('未認証で見えないはずの商品');
    }
}
