<?php

namespace App\Http\Controllers;

use App\Services\RconService;
use Illuminate\Http\Request;

class ArkControllerTest extends Controller
{
    public function players(RconService $rcon)
    {
        $response = $rcon->send("ListPlayers");

        return response()->json([
            'players_raw' => $response
        ]);
    }

    
}
