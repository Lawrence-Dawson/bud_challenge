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
           ->request('GET', 'http://www.foo.com/bar', [
                'headers' => [],
                'body' => json_encode([])
            ])
           ->once()
           ->andReturns($response);
        
        $response = $baseGateway->request('GET', '/bar', []);

        $this->assertEquals($response->getBody()->getContents(), json_encode($body));
    }

    public function testItCanSendRequestWithHeaders()
    {
        $client = Mockery::mock(Client::class);
        $headers = ['test' => 'header'];
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com';
        };

        $body = ['foo' => 'bar'];
        $response = $this->createResponse(200, [], $body);

        $client->expects()
            ->request('GET', 'http://www.foo.com/bar', [
                'headers' => $headers,
                'body' => json_encode([])
            ])
            ->once()
            ->andReturns($response);

        $baseGateway->setHeaders($headers);
        $response = $baseGateway->request('GET', '/bar', []);

        $this->assertEquals($response, $response);
    }

    public function testItCanGetHeaders()
    {
        $client = Mockery::mock(Client::class);
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com';
        };

        $headers = ['test' => 'header'];

        $baseGateway->setHeaders($headers);

        $this->assertEquals($headers, $baseGateway->getHeaders());
    }

    public function testItCanAddHeader()
    {
        $client = Mockery::mock(Client::class);
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com';
        };

        $headers = ['test' => 'header'];
        $addition = ['another' => 'header'];

        $baseGateway->setHeaders($headers);
        $baseGateway->addHeader($addition);

        $this->assertEquals(array_merge($headers, $addition), $baseGateway->getHeaders());
    }


    public function testItCanSendRequestWithBody()
    {
        $client = Mockery::mock(Client::class);
        $headers = ['test' => 'header'];
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com';
        };

        $responseBody = ['foo' => 'bar'];
        $requestBody = ['test' => ['request' => 'body']];
        $response = $this->createResponse(200, [], $responseBody);

        $client->expects()
            ->request('POST', 'http://www.foo.com/bar', [
                'headers' => $headers,
                'body' => json_encode($requestBody)
            ])
            ->once()
            ->andReturns($response);

        $baseGateway->setHeaders($headers);
        $response = $baseGateway->request('POST', '/bar', $requestBody);

        $this->assertEquals($response, $response);
    }
}
