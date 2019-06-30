<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;

class DeathStarGateway extends BaseGateway
{
    private $token;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->setBaseUrl('');
        $this->setTokenHeader();
    }

    private function setTokenHeader()
    {
        $response = $this->request('POST', '/token', [
            'Client secret' => '',
            'Client ID' => '',
        ]);
        
        $body = json_decode($response->getBody(), true);

        $this->setHeaders([
            'Authorization' => 'Bearer ' . $body['access_token']
        ]);
    }
}