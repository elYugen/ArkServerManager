<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'image_url',
        'price_eur',
        'points_reward',
        'ark_item_command',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_eur' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(ShopCategory::class, 'category_id');
    }

    public function orders()
    {
        return $this->hasMany(ShopOrder::class, 'product_id');
    }
}
