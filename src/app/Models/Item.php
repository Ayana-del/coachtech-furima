<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Comment;
use App\Models\Like;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'condition_id',
        'name',
        'image_url',
        'brand',
        'price',
        'description',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }
    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class,'item_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'item_id');
    }
    public function getIsSoldAttribute()
    {
        return $this->orders()->exists();
    }
}
