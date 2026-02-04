<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Category;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        // ユーザーの取得（存在しない場合は作成）
        $user = User::first() ?? User::factory()->create();

        // 商品の状態を取得
        $conds = Condition::all()->pluck('id', 'name');

        // 全カテゴリーを取得
        $categories = Category::all();

        $items = [
            [
                'user_id' => $user->id,
                'name' => '腕時計',
                'price' => 15000,
                'brand' => 'Rolax', // brand_name を brand に修正
                'condition_id' => $conds['良好'],
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'HDD',
                'price' => 5000,
                'brand' => '西芝',
                'condition_id' => $conds['目立った傷や汚れなし'],
                'description' => '高速で信頼性の高いハードディスク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => '玉ねぎ3束',
                'price' => 300,
                'brand' => null,
                'condition_id' => $conds['良好'],
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => '革靴',
                'price' => 4000,
                'brand' => null,
                'condition_id' => $conds['やや傷や汚れあり'],
                'description' => 'クラシックなデザインの革靴',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'ノートPC',
                'price' => 45000,
                'brand' => null,
                'condition_id' => $conds['良好'],
                'description' => '高性能なノートパソコン',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'マイク',
                'price' => 8000,
                'brand' => null,
                'condition_id' => $conds['良好'],
                'description' => '高音質のレコーディング用マイク',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'brand' => null,
                'condition_id' => $conds['良好'],
                'description' => 'おしゃれなショルダーバッグ',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'タンブラー',
                'price' => 500,
                'brand' => null,
                'condition_id' => $conds['良好'],
                'description' => '使いやすいタンブラー',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'コーヒーミル',
                'price' => 4000,
                'brand' => 'Starbacks',
                'condition_id' => $conds['良好'],
                'description' => '手動のコーヒーミル',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'user_id' => $user->id,
                'name' => 'メイクセット',
                'price' => 2500,
                'brand' => null,
                'condition_id' => $conds['良好'],
                'description' => '便利なメイクアップセット',
                'image_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            ],
        ];

        foreach ($items as $itemData) {
            // Item::create で保存（タイムスタンプは自動付与されるので省略可能）
            $item = Item::create($itemData);

            // カテゴリーをランダムに1〜2個紐付ける
            if ($categories->isNotEmpty()) {
                $randomCategoryIds = $categories->random(rand(1, 2))->pluck('id');
                $item->categories()->attach($randomCategoryIds);
            }
        }
    }
}
