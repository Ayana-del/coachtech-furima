<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // カテゴリ名（content）を保存可能にする
    protected $fillable = ['content'];
    // このカテゴリに属する商品を取得。多対多のリレーション (category_itemテーブルを介する)
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
}
