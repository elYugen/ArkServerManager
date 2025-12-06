<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopOrder extends Model
{
    protected $fillable = [
        'product_id',
        'player_id',
        'amount_eur',
        'stripe_payment_id',
        'status',
        'delivered_at'
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(ShopProduct::class, 'product_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }
}
