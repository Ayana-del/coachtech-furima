<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Case15_ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $categories;
    private $condition;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->categories = [
            Category::create(['content' => 'ファッション']),
            Category::create(['content' => '家電']),
        ];
        $this->condition = Condition::create(['name' => '新品、未使用']);

        Storage::fake('public');
    }

    public function test_商品出品画面にて必要な情報が保存できること()
    {
        $this->actingAs($this->user);

        $file = UploadedFile::fake()->create('test_item.jpg', 100, 'image/jpeg');

        $postData = [
            'name'         => 'テスト商品',
            'brand'        => 'テストブランド',
            'description'  => 'これはテスト商品の説明です。',
            'price'        => 5000,
            'condition_id' => $this->condition->id,
            'categories'   => [
                $this->categories[0]->id,
                $this->categories[1]->id,
            ],
            'img_url'      => $file
        ];

        $response = $this->post(route('item.store'), $postData);

        $response->assertRedirect(route('mypage.index', ['tab' => 'sell']));

        $this->assertDatabaseHas('items', [
            'user_id'      => $this->user->id,
            'name'         => 'テスト商品',
            'brand'        => 'テストブランド',
            'price'        => 5000,
            'condition_id' => $this->condition->id,
        ]);

        $item = Item::where('name', 'テスト商品')->first();
        $this->assertCount(2, $item->categories);

        $this->assertNotNull($item->image_url);
        Storage::disk('public')->assertExists($item->image_url);
    }
}
