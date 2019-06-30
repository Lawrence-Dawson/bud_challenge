<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;

abstract class BaseGateway
{
    private $client;
    protected $baseUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function request(string $method, string $url, array $body) 
    {
        $fullUrl = $this->baseUrl . $url;
        return $this->client->request($method, $fullUrl, $body);
    }
}