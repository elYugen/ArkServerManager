<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(ShopProduct::class, 'category_id');
    }
}
