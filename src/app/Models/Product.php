<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'productName',
        'brand',
        'description',
        'price',
        'image'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function favoritedByUsers()
    {
    return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function isFavoriteBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    public function purchase()
    {
        $this->status = 'purchased';
        $this->save();
    }


}
