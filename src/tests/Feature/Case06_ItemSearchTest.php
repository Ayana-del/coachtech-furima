<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Condition;

class Case06_ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品名で部分一致検索ができる()
    {
        $condition = Condition::create(['name' => '良好']);
        $seller = User::factory()->create();

        Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'スニーカー（青）',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test1.png'
        ]);

        Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '革靴',
            'price' => 2000,
            'description' => '説明',
            'image_url' => 'test2.png'
        ]);

        $response = $this->get('/?keyword=スニーカー');

        $response->assertStatus(200);
        $response->assertSee('スニーカー（青）');
        $response->assertDontSee('革靴');
    }

    public function test_検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        $condition = Condition::create(['name' => '良好']);
        $item = Item::create([
            'user_id' => User::factory()->create()->id,
            'condition_id' => $condition->id,
            'name' => '対象のスニーカー',
            'price' => 1000,
            'description' => '説明',
            'image_url' => 'test.png'
        ]);

        $this->actingAs($user);
        $user->likedItems()->attach($item->id);

        $response = $this->get('/?tab=mylist&keyword=スニーカー');

        $response->assertStatus(200);
        $response->assertSee('対象のスニーカー');
        $response->assertSee('スニーカー');
    }
}
