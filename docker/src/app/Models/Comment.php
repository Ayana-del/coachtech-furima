<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // user_id, item_id, content（最大255文字）を保存可能にする
    protected $fillable = ['user_id', 'item_id', 'content'];
    // コメントを投稿したユーザーを取得
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // コメントされた商品を取得
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
