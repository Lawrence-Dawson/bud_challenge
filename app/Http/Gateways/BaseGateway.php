<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;
use App\Http\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

abstract class BaseGateway
{
    protected $baseUrl;
    protected $client;
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
        $fullUrl = $this->getBaseUrl() . $url;
        $config['headers'] = array_merge($this->getHeaders(), $additionalHeaders);
        $config['body'] = json_encode($body);
    
        $guzzleResponse = $this->client->request($method, $fullUrl, $config);
        
        return $this->createResponse($guzzleResponse); 
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setBaseUrl(string $url)
    {
        $this->baseUrl = $url;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    private function createResponse(GuzzleResponse $guzzleResponse): Response
    {
        $status = $guzzleResponse->getStatusCode();
        $body = $guzzleResponse->getBody()->getContents();
        $headers = $guzzleResponse->getHeaders();

        return new Response($status, $body, $headers);
    }
}