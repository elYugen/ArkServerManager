<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $table = 'arkshopplayers'; 

    public function orders()
    {
        return $this->hasMany(ShopOrder::class, 'player_id');
    }
}
