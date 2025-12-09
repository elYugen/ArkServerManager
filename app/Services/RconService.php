<?php

namespace App\Services;

use App\Models\ArkServerConfig;
use Thedudeguy\Rcon;

class RconService
{
    protected $rcon;
    protected $server;

    public function __construct()
    {
        $this->server = ArkServerConfig::first();

        if (!$this->server) {
            throw new \Exception("Aucune configuration RCON détectée dans la base de données.");
        }

        $this->rcon = new Rcon(
            $this->server->ip,
            $this->server->port,
            $this->server->password,
            3
        );
    }


    public function send($command)
    {
        if ($this->rcon->connect()) {
            return $this->rcon->sendCommand($command);
        }

        return false;
    }
}
