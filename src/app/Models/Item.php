<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['user_id', 'name', 'brand', 'price', 'description', 'condition', 'image_url'];
    // 出品者
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // カテゴリ（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    // コメント (1対多)
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
