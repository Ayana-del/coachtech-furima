<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Profile;
use App\Models\Order;

class Case13_UserProfileTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $sellItem;
    private $buyItem;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['name' => 'テストユーザー']);
        Profile::create([
            'user_id'  => $this->user->id,
            'name'     => 'プロフィール上の名前',
            'image_url' => 'test_user_icon.png',
            'postcode' => '123-4567',
            'address'  => '東京都',
        ]);

        $condition = Condition::create(['name' => '良好']);

        $this->sellItem = Item::create([
            'user_id'      => $this->user->id,
            'condition_id' => $condition->id,
            'name'         => '私が出品した商品',
            'price'        => 1000,
            'description'  => '出品説明',
            'image_url'    => 'sell_item.png'
        ]);

        $otherUser = User::factory()->create();
        $this->buyItem = Item::create([
            'user_id'      => $otherUser->id,
            'condition_id' => $condition->id,
            'name'         => '私が購入した商品',
            'price'        => 2000,
            'description'  => '購入説明',
            'image_url'    => 'buy_item.png'
        ]);

        Order::create([
            'user_id' => $this->user->id,
            'item_id' => $this->buyItem->id,
            'payment_method' => 'card',
            'postcode' => '123-4567',
            'address' => '配送先住所',
        ]);
    }

    public function test_必要な情報が取得できる()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('mypage.index'));

        $response->assertStatus(200);

        $response->assertSee('class="user-image"', false);
        $response->assertSee('test_user_icon.png');

        $response->assertSee('プロフィール上の名前');

        $response->assertSee('私が出品した商品');

        $response->assertSee('私が購入した商品');
    }
}
