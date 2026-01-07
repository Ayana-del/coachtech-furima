<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    // プロフィール (1対1)
    public function profile() {
        return $this->hasOne(Profile::class);
    }
    // 出品した商品 (1対多)
    public function items() {
        return $this->hasMany(Item::class);
    }
    // マイリスト（いいねした商品） (多対多)
    public function likedItems() {
        return $this->belongsToMany(Item::class, 'likes');
    }
}
