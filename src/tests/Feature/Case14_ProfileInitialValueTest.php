<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;

class Case14_ProfileInitialValueTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $profile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => '既存ユーザー名'
        ]);

        $this->profile = Profile::create([
            'user_id'   => $this->user->id,
            'name'      => 'プロフィール名',
            'image_url' => 'profiles/test_image.png',
            'postcode'  => '123-4567',
            'address'   => '東京都渋谷区',
            'building'  => 'テストビル101',
        ]);
    }

    public function test_変更項目が初期値として過去設定されていること()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200);

        $response->assertSee('value="既存ユーザー名"', false);

        $response->assertSee('value="123-4567"', false);

        $response->assertSee('value="東京都渋谷区"', false);

        $response->assertSee('value="テストビル101"', false);

        $response->assertSee('profiles/test_image.png');
    }
}
