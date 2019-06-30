<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;

abstract class BaseGateway
{
    protected $baseUrl;
    
    private $client;
    private $config = [];
    private $headers = [];

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function request(string $method, string $url, array $body = []) 
    {
        $fullUrl = $this->baseUrl . $url;
        $config = $this->getConfig();
        $config['headers'] = $this->getHeaders();
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

    public function addHeader(array $header)
    {
        $this->setHeaders(array_merge($this->getHeaders(), $header));
    }

    public function removeHeader(string $key)
    {
        $headers = $this->getHeaders();
        unset($headers[$key]);
        $this->setHeaders($headers);
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    public function getConfig(): array
    {
        return $this->config;
    }
}