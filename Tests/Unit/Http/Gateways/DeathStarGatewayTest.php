<?php

namespace App\Tests\Unit\Http\Gateways;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use App\Http\Gateways\DeathStarGateway;

class DeathStarGatewayTest extends TestCase
{
    public function setUp(): void
    {
        $this->configs = $configs = include('config.php');
    }

    public function createResponse(int $status, array $headers = [], array $body = [])
    {
        $body = json_encode($body);
        $headers = [];
        return new Response($status, $headers, $body);
    }

    public function testItSetsAuthToken()
    {
        $client = Mockery::mock(Client::class);

        $responseBody = [
            'access_token' => 'e31a726c4b90462ccb7619e1b9d8u8d87d87d878d8d',
            'expires_in' => 99999999999,
            'token_type' => 'Bearer',
            'scope' => 'TheForce'
        ];

        $body = [
            'Client secret' => $this->configs['death_star_secret'],
            'Client ID' => $this->configs['death_star_id'],
        ];

        $response = $this->createResponse(200, [], $responseBody);

        $cert = 'certificate.pem';

        $client->expects()
            ->request('POST', 'https://death.star.api/token', [
                'headers' => [],
                'cert' => $cert,
                'body' => json_encode($body)
            ])
            ->once()
            ->andReturns($response);

        $gateway = new DeathStarGateway($client);
        $headers = $gateway->getHeaders();
        
        $this->assertEquals($headers, [
            'Authorization' => 'Bearer ' . $responseBody['access_token']
        ]);
    }

    public function testItCanDestroyDeathstar()
    {
        $client = Mockery::mock(Client::class);

        $authResponseBody = [
            'access_token' => 'e31a726c4b90462ccb7619e1b9d8u8d87d87d878d8d',
            'expires_in' => 99999999999,
            'token_type' => 'Bearer',
            'scope' => 'TheForce'
        ];

        $authResponse = $this->createResponse(200, [], $authResponseBody);

        $cert = 'certificate.pem';

        $client->expects()
            ->request('POST', 'https://death.star.api/token', [
                'headers' => [],
                'cert' => $cert,
                'body' => json_encode([
                    'Client secret' => $this->configs['death_star_secret'],
                    'Client ID' => $this->configs['death_star_id'],
                ])
            ])
            ->once()
            ->andReturns($authResponse);

        $destroyResponse = $this->createResponse(200, [], []);
            
        $client->expects()
            ->request('DELETE', 'https://death.star.api/reactor/exhaust/1', [
                'headers' => [
                    'X-Torpedoes' => 2,
                    'Content-Type:  application/json',
                    'Authorization' => 'Bearer ' . $authResponseBody['access_token']
                ],
                'cert' => $cert,
                'body' => json_encode([])
            ])
            ->once()
            ->andReturns($destroyResponse);

        $gateway = new DeathStarGateway($client);
        $response = $gateway->destroy();

        $this->assertEquals($destroyResponse->getBody(), $response->getBody());
    }

    public function testItCanReleaseLeia()
    {
        $client = Mockery::mock(Client::class);

        $authResponseBody = [
            'access_token' => 'e31a726c4b90462ccb7619e1b9d8u8d87d87d878d8d',
            'expires_in' => 99999999999,
            'token_type' => 'Bearer',
            'scope' => 'TheForce'
        ];

        $authResponse = $this->createResponse(200, [], $authResponseBody);

        $cert = 'certificate.pem';

        $client->expects()
            ->request('POST', 'https://death.star.api/token', [
                'headers' => [],
                'cert' => $cert,
                'body' => json_encode([
                    'Client secret' => $this->configs['death_star_secret'],
                    'Client ID' => $this->configs['death_star_id'],
                ])
            ])
            ->once()
            ->andReturns($authResponse);

        $destroyResponse = $this->createResponse(200, [], []);
            
        $client->expects()
            ->request('GET', 'https://death.star.api/prisoner/leia', [
                'headers' => [
                    'Content-Type:  application/json',
                    'Authorization' => 'Bearer ' . $authResponseBody['access_token']
                ],
                'cert' => $cert,
                'body' => json_encode([])
            ])
            ->once()
            ->andReturns($destroyResponse);

        $gateway = new DeathStarGateway($client);
        $response = $gateway->releasePrincess();

        $this->assertEquals($destroyResponse->getBody(), $response->getBody());
    }

    public function testItCachesTokenAfterInitialRequest()
    {
        $client = Mockery::mock(Client::class);

        $authResponseBody = [
            'access_token' => 'e31a726c4b90462ccb7619e1b9d8u8d87d87d878d8d',
            'expires_in' => 99999999999,
            'token_type' => 'Bearer',
            'scope' => 'TheForce'
        ];

        $authResponse = $this->createResponse(200, [], $authResponseBody);

        $cert = 'certificate.pem';

        $client->expects()
            ->request('POST', 'https://death.star.api/token', [
                'headers' => [],
                'cert' => $cert,
                'body' => json_encode([
                    'Client secret' => $this->configs['death_star_secret'],
                    'Client ID' => $this->configs['death_star_id'],
                ])
            ])
            ->once()
            ->andReturns($authResponse);
        
        new DeathStarGateway($client);

        $client2 = Mockery::mock(Client::class);

        $client2->shouldNotReceive('request')->once();

        $gateway2 = new DeathStarGateway($client2);

        $this->assertEquals($gateway2->getHeaders(), ['Authorization' => 'Bearer ' . $authResponseBody['access_token']]);
    }
}
