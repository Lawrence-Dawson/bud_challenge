<?php

namespace App\Tests\Unit\Http\Gateways;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use App\Http\Gateways\DeathStarGateway;

class DeathStarGatewayTest extends TestCase
{
    public function createResponse(int $status, array $headers = [], array $body = [])
    {
        $body = json_encode($body);
        $headers = [];
        return new Response($status, $headers, $body);
    }

    public function testItCanSetAuthToken()
    {
        $client = Mockery::mock(Client::class);
        $gateway = new DeathStarGateway($client);

        $responseBody = [
            'access_token' => 'e31a726c4b90462ccb7619e1b9d8u8d87d87d878d8d',
            'expires_id' => 99999999999,
            'token_type' => 'Bearer',
            'scope' => 'TheForce'
        ];

        $body = [
            'Client secret' => 'Alderaan',
            'Client ID' => 'R2D2',
        ];

        $response = $this->createResponse(200, [], $responseBody);

        $client->expects()
            ->request('POST', 'https://death.star.api/token', [
                'headers' => [],
                'body' => json_encode($body)
            ])
            ->once()
            ->andReturns($response);

        $headers = $gateway->getHeaders();
        
        $this->assertEquals($headers, [
            'Authorization' => 'Bearer ' . $responseBody['access_token']
        ]);
    }
}
