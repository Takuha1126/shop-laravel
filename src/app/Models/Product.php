<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_name',
        'brand',
        'description',
        'price',
        'image',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function favoriteByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')->withTimestamps();
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function isFavoriteBy($userId)
    {
        return $this->favoriteByUsers()->where('user_id', $userId)->exists();
    }

    public function purchase()
    {
        $this->status = 'purchased';
        $this->save();
    }


}
