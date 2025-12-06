<?php

namespace App\Services;

use App\Models\ArkServerConfig;
use Thedudeguy\Rcon;

class RconService
{
    protected $rcon;
    protected $server;

    public function __construct(ArkServerConfig $server)
    {
        $this->server = $server;

        $this->rcon = new Rcon(
            $server->ip,
            $server->port,
            $server->password,
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
