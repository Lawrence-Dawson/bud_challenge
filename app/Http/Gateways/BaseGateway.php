<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;

abstract class BaseGateway
{
    private $client;
    protected $baseUrl;
    private $headers = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function request(string $method, string $url, array $body) 
    {
        $fullUrl = $this->baseUrl . $url;
        $config = [
            'headers' => $this->getHeaders(),
            'body' => json_encode($body),
        ];
        return $this->client->request($method, $fullUrl, $config);
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addHeader(array $header)
    {
        $this->setHeaders(array_merge($this->getHeaders(), $header));
    }
}