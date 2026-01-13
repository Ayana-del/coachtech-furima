<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'image_url',
        'brand_name',
        'price',
        'description',
    ];

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
