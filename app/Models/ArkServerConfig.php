<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArkServerConfig extends Model
{
    protected $table = "ark_server_config";
    
    protected $fillable = [
        'ip',
        'port',
        'password',
        'shop_json_path'
    ];
}
