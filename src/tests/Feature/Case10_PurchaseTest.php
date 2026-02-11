<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Profile;

class Case10_PurchaseTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $item;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Profile::create([
            'user_id'  => $this->user->id,
            'name'     => $this->user->name,
            'postcode' => '123-4567',
            'address'  => '東京都渋谷区テスト',
            'building' => 'テストビル',
        ]);

        $condition = Condition::create(['name' => '良好']);
        $this->item = Item::create([
            'user_id'      => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name'         => '購入テスト商品',
            'price'        => 3000,
            'description'  => 'テスト説明',
            'image_url'    => 'test.png'
        ]);
    }

    public function test_「購入する」ボタンを押下すると購入が完了する()
    {
        $this->actingAs($this->user);
        $this->get(route('purchases.success', ['item_id' => $this->item->id]));

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ]);
    }

    public function test_購入した商品は商品一覧画面にて「sold」と表示される()
    {
        $this->actingAs($this->user);
        $this->get(route('purchases.success', ['item_id' => $this->item->id]));

        $response = $this->get(route('item.index'));
        $response->assertSee('Sold');
    }

    public function test_プロフィール購入した商品一覧に追加されている()
    {
        $this->actingAs($this->user);
        $this->get(route('purchases.success', ['item_id' => $this->item->id]));

        $response = $this->get(route('mypage.index', ['tab' => 'buy']));
        $response->assertSee('購入テスト商品');
    }
}
