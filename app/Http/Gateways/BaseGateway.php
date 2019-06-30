<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;
use App\Http\Response;
use GuzzleHttp\Psr7\Response as GuzzleResponse;

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

    private function createResponse(GuzzleResponse $guzzleResponse): Response
    {
        $status = $guzzleResponse->getStatusCode();
        $body = $guzzleResponse->getBody()->getContents();
        $headers = $guzzleResponse->getHeaders();

        return new Response($status, $body, $headers);
    }
}