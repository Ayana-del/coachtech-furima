<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'id' => 1, // 明示的にIDを指定しておくとProfileとの紐付けが確実です
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => \Hash::make('password123'), // ログイン用パスワード
            'email_verified_at' => now(),
        ]);
    }
}
