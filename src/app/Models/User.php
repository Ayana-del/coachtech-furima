<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // メール認証に必須
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // プロフィール (1対1)
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    // 出品した商品 (1対多)
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function orders()
    {
    // 自分が購入した履歴
        return $this->hasMany(Order::class);
    }
}
