<?php

namespace App\Http\Gateways;

use GuzzleHttp\Client;
use Wruczek\PhpFileCache\PhpFileCache;

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
        $cache = new PhpFileCache(__DIR__ . "cache");
        
        if ($token = $cache->retrieve("death_star_token")) {
            return $token['access_token'];
        }
        
        $body = [
            'Client secret' => $this->getConfigs()['death_star_secret'],
            'Client ID' => $this->getConfigs()['death_star_id'],
        ];

        $configs = ['cert' => 'certificate.pem'];
        
        $response = $this->request('POST', '/token', $body, [], $configs);
     
        if (!isset($response->getBody()['access_token'])) {
            throw new \Exception(
                $response->getBody['message'] ?? 'Error, token could not be retrieved.',
                $response->getStatus()
            );
        }
        
        $reponseBody = json_decode($response->getBody(), true);

        $cache->store("death_star_token", $reponseBody, $reponseBody['expires_in'] - 3600);

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