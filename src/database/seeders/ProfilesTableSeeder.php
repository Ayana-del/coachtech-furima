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
            'address' => '東京都渋谷区道玄坂2-11',
            'building' => 'テックビル 101',
            'image_url' => null,
        ]);

        Profile::create([
            'user_id' => 2,
            'name' => '購入者 次郎',
            'postcode' => '530-0001',
            'address' => '大阪府大阪市北区梅田1-1',
            'building' => '梅田タワー 20F',
            'image_url' => null,
        ]);

        Profile::create([
            'user_id' => 3,
            'name' => '未認証 三郎',
            'postcode' => '810-0001',
            'address' => '福岡県福岡市中央区天神1-1',
            'building' => '天神プラザ 5F',
            'image_url' => null,
        ]);
    }
}
