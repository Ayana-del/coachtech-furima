<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;
use App\Models\Profile;

class Case11_PaymentMethodTest extends TestCase
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
            'address'  => '東京都',
        ]);

        $condition = Condition::create(['name' => '良好']);
        $this->item = Item::create([
            'user_id'      => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name'         => 'テスト商品',
            'price'        => 1000,
            'description'  => '説明',
            'image_url'    => 'test.png'
        ]);
    }

    public function test_小計画面で変更が反映される()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('purchases.show', ['item_id' => $this->item->id]));

        $response->assertStatus(200);
        $response->assertSee('コンビニ払い');
        $response->assertSee('カード支払い');

        $response->assertSee('id="payment_select"', false);
        $response->assertSee('id="display-payment"', false);
    }
}
