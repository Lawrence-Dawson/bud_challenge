<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;

abstract class BaseGateway
{
    protected $baseUrl;
    
    private $client;
    private $headers = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function request(
        string $method, 
        string $url, 
        array $body = [], 
        array $additionalHeaders = [],
        array $config = []
        ) 
    {
        $fullUrl = $this->baseUrl . $url;
        $config['headers'] = array_merge($this->getHeaders(), $additionalHeaders);
        $config['body'] = json_encode($body);
        
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
}