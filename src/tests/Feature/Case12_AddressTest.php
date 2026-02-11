<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Profile;

class Case12_AddressTest extends TestCase
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
            'postcode' => '000-0000',
            'address'  => '初期住所',
        ]);

        $condition = Condition::create(['name' => '良好']);
        $this->item = Item::create([
            'user_id'      => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name'         => '住所変更テスト商品',
            'price'        => 5000,
            'description'  => '説明',
            'image_url'    => 'test.png'
        ]);
    }

    public function test_送付先住所変更画面にて登録した住所が商品購入画面に反映されている()
    {
        $this->actingAs($this->user);

        $newAddress = [
            'postcode' => '123-4567',
            'address'  => '東京都新宿区新しい住所',
            'building' => '新築ビル101',
            'update_profile' => '0',
        ];

        $this->patch(route('addresses.update', ['item_id' => $this->item->id]), $newAddress);

        $response = $this->get(route('purchases.show', ['item_id' => $this->item->id]));

        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区新しい住所');
        $response->assertSee('新築ビル101');
    }

    public function test_購入した商品に送付先住所が紐づいて登録される()
    {
        $this->actingAs($this->user);

        $newAddress = [
            'postcode' => '999-8888',
            'address'  => '大阪府大阪市配送先住所',
            'building' => '配送先ビル',
            'update_profile' => '0',
        ];
        $this->patch(route('addresses.update', ['item_id' => $this->item->id]), $newAddress);

        $this->get(route('purchases.success', ['item_id' => $this->item->id]));

        $this->assertDatabaseHas('orders', [
            'user_id'  => $this->user->id,
            'item_id'  => $this->item->id,
            'postcode' => '999-8888',
            'address'  => '大阪府大阪市配送先住所',
            'building' => '配送先ビル',
        ]);
    }
}
