<?php

namespace App\Tests\Unit;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use App\Http\Gateways\BaseGateway;
use function GuzzleHttp\json_encode;

class BaseGatewayTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function createResponse(int $status, array $headers = [], array $body)
    {
        $body = json_encode($body);
        $headers = [];
        return new Response($status, $headers, $body);
    }

    public function testItCanSendRequest()
    {
       $client = Mockery::mock(Client::class);
       $baseGateway = new class($client) extends BaseGateway {
           protected $baseUrl = 'http://www.foo.com';
       };

       $body = ['foo' => 'bar'];
       $response = $this->createResponse(200, [], $body);

       $client->expects()
           ->request('GET', 'http://www.foo.com/bar', [])
           ->once()
           ->andReturns($response);
        
        $response = $baseGateway->request('GET', '/bar', []);

        $this->assertEquals($response->getBody()->getContents(), json_encode($body));
    }
}
