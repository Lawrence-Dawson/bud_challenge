<?php

namespace App\Http\Gateways;

class DeathStarGateway
{
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    private function setToken()
    {
        
    }
}