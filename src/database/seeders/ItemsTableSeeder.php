<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::first() ?? User::factory()->create();
        $items = [
            [
                'user_id' => $user->id,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax',
                'condition' => '良好',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'condition' => '目立った傷や汚れなし',
                'description' => '高速で信頼性の高いハードディスク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => null,
                'condition' => '良好',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'condition' => 'やや傷や汚れあり',
                'description' => 'クラシックなデザインの革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'condition' => '良好',
                'description' => '高性能なノートパソコン',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => null,
                'condition' => '良好',
                'description' => '高音質のレコーディング用マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'condition' => '良好',
                'description' => 'おしゃれなショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => null,
                'condition' => '良好',
                'description' => '使いやすいタンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'condition' => '良好',
                'description' => '手動のコーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg'
            ],
            [
                'user_id' => $user->id,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'condition' => '良好',
                'description' => '便利なメイクアップセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg'
            ],
        ];

        foreach ($items as $item) {
            DB::table('items')->insert($item);
        }
    }
}
