<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profile::create([
            'user_id' => 1, // 最初に作成されるユーザーID
            'name' => 'テスト太郎',
            'postcode' => '123-4567',
            'address' => '東京都渋谷区道玄坂...',
            'building' => 'テックビル 101',
            'image_url' => null,
        ]);
    }
}
