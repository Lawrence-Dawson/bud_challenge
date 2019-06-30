<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;

class DeathStarGateway extends BaseGateway
{
    private $configs;

    public function __construct(Client $client)
    {
        $this->configs = include('config.php');
        $this->client = $client;
        $this->setBaseUrl($this->configs['death_star_url']);
        $this->setTokenHeader();
    }

    private function setTokenHeader()
    {
        $response = $this->request('POST', '/token', 
        [
            'Client secret' => $this->configs['death_star_secret'],
            'Client ID' => $this->configs['death_star_id'],
        ], [], ['cert' => 'certificate.pem']);
        
        $body = json_decode($response->getBody(), true);

        $this->setHeaders([
            'Authorization' => 'Bearer ' . $body['access_token']
        ]);
    }

    public function destroyExhaust(int $exhaust)
    {
        return $this->request('DELETE', '/reactor/exhaust/' . $exhaust, [], ['X-Torpedoes' => 2], ['cert' => 'certificate.pem']);
    }
}