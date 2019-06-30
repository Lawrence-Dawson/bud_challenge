<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;

abstract class BaseGateway
{
    private $client;
    private $headers;
    protected $baseUrl;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function request(string $method, string $url, array $body) 
    {
        $fullUrl = $this->baseUrl . $url;
        $config = [
            'headers' => $this->headers,
            'body' => json_encode($body),
        ];
        return $this->client->request($method, $fullUrl, $config);
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }
}