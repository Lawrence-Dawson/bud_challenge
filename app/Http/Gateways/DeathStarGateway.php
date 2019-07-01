<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;

class DeathStarGateway extends BaseGateway
{
    private $configs;

    public function __construct(Client $client)
    {
        $this->setConfigs(include('config.php'));
        $this->client = $client;
        $this->setBaseUrl($this->configs['death_star_url']);
        $accessToken = $this->getAccessToken();
        $this->setHeaders(['Authorization' => 'Bearer ' . $accessToken]);
    }

    private function getAccessToken()
    {
        $body = [
            'Client secret' => $this->getConfigs()['death_star_secret'],
            'Client ID' => $this->getConfigs()['death_star_id'],
        ];
        $configs = ['cert' => 'certificate.pem'];

        $response = $this->request('POST', '/token', $body, [], $configs);

        $reponseBody = json_decode($response->getBody(), true);

        return $reponseBody['access_token'];
    }

    public function destroy()
    {
        $headers = [
            'X-Torpedoes' => 2,
            'Content-Type:  application/json',
        ];
        $requestConfig = ['cert' => 'certificate.pem'];

        return $this->request('DELETE', '/reactor/exhaust/1', [], $headers, $requestConfig);
    }

    public function releasePrincess()
    {
        $headers = ['Content-Type:  application/json'];
        $requestConfig = ['cert' => 'certificate.pem'];

        return $this->request('GET', '/prisoner/leia', [], $headers, $requestConfig);
    }

    private function setConfigs(array $configs)
    {
        $this->configs = $configs;
    }

    private function getConfigs(): array
    {
        return $this->configs;
    }
}