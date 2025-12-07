<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $table = "logs";

    protected $fillable = [
        'user_id',
        'on_page',
        'logs'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
