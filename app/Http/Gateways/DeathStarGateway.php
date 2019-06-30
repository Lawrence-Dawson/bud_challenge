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
        $body = [
            'Client secret' => $this->configs['death_star_secret'],
            'Client ID' => $this->configs['death_star_id'],
        ];
        $configs = ['cert' => 'certificate.pem'];

        $response = $this->request('POST', '/token', $body, [], $configs);
        
        $reponseBody = json_decode($response->getBody(), true);

        $this->setHeaders([
            'Authorization' => 'Bearer ' . $reponseBody['access_token']
        ]);
    }

    public function destroy()
    {
        $headers = [
            'X-Torpedoes' => 2,
            'Content-Type:  application/json',
        ];
        $configs = ['cert' => 'certificate.pem'];

        return $this->request('DELETE', '/reactor/exhaust/1', [], $headers, $configs);
    }

    public function releaseThePrincess()
    {
        $headers = ['Content-Type:  application/json'];
        $configs = ['cert' => 'certificate.pem'];

        return $this->request('GET', '/prisoner/leia', [], $headers, $configs);
    }
}