<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Condition;

class Case04_ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '商品A',
            'price' => 1000,
            'description' => 'テスト説明',
            'image_url' => 'test_a.png'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('商品A');
    }

    public function test_購入済み商品はSoldと表示される()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '売切れ商品',
            'price' => 2000,
            'description' => 'テスト説明',
            'image_url' => 'test_b.png'
        ]);

        Order::create([
            'user_id'        => $buyer->id,
            'item_id'        => $item->id,
            'payment_method' => 'card',
            'postcode'       => '123-4567',
            'address'        => '東京都新宿区',
            'building'       => 'テストビル101',
        ]);

        $response = $this->get('/');

        $response->assertSee('売切れ商品');
        $response->assertSee('Sold');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $me = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Item::create([
            'user_id' => $me->id,
            'condition_id' => $condition->id,
            'name' => '自分が出品した商品',
            'price' => 3000,
            'description' => 'テスト説明',
            'image_url' => 'test_c.png'
        ]);

        $response = $this->actingAs($me)->get('/');

        $response->assertDontSee('自分が出品した商品');
    }
}
