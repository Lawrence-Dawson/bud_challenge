<?php

namespace App\Tests\Unit\Http\Gateways;

use Mockery;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use App\Http\Gateways\BaseGateway;

class BaseGatewayTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function createResponse(int $status, array $headers = [], array $body = [])
    {
        $body = json_encode($body);
        $headers = [];
        return new Response($status, $headers, $body);
    }

    public function testItCanSendRequest()
    {
       $client = Mockery::mock(Client::class);
       $baseGateway = new class($client) extends BaseGateway {
           protected $baseUrl = 'http://www.foo.com/';
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
        
        $baseGateway->request('GET', 'bar', []);

        $this->assertEquals($response->getBody(), json_encode($body));
    }

    public function testItCanSetBaseUrl()
    {
        $client = Mockery::mock(Client::class);
        $baseGateway = new class($client) extends BaseGateway {};

        $body = ['foo' => 'bar'];
        $response = $this->createResponse(200, [], $body);
        $baseUrl = 'http://www.foo.com/';

        $client->expects()
            ->request('GET', $baseUrl . 'bar', [
                'headers' => [],
                'body' => json_encode([])
            ])
            ->once()
            ->andReturns($response);
        
        $baseGateway->setBaseUrl($baseUrl);
        $baseGateway->request('GET', 'bar', []);
            
        $this->assertEquals($baseGateway->getBaseUrl(), $baseUrl);
        $this->assertEquals($response->getBody(), json_encode($body));
    }

    public function testItCanSendRequestWithHeaders()
    {
        $client = Mockery::mock(Client::class);
        $headers = ['test' => 'header'];
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com/';
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
        $requestResponse = $baseGateway->request('GET', 'bar', []);

        $this->assertEquals($response->getBody(), $requestResponse->getBody());
    }

    public function testItCanGetHeaders()
    {
        $client = Mockery::mock(Client::class);
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com/';
        };

        $headers = ['test' => 'header'];

        $baseGateway->setHeaders($headers);

        $this->assertEquals($headers, $baseGateway->getHeaders());
    }

    public function testItCanSendRequestWithBody()
    {
        $client = Mockery::mock(Client::class);
        $headers = ['test' => 'header'];
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com/';
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
        $requestResponse = $baseGateway->request('POST', 'bar', $requestBody);

        $this->assertEquals($response->getBody(), $requestResponse->getBody());
    }

    public function testItCanSendRequestWithAdditionalHeaders()
    {
        $client = Mockery::mock(Client::class);
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com/';
        };
        
        $headers = ['test' => 'header'];
        $requestBody = ['test' => ['request' => 'body']];
        $response = $this->createResponse(200);

        $client->expects()
            ->request('POST', 'http://www.foo.com/bar', [
                'headers' => $headers,
                'body' => json_encode($requestBody)
            ])
            ->once()
            ->andReturns($response);

        $requestResponse = $baseGateway->request('POST', 'bar', $requestBody, $headers);

        $this->assertEquals($response->getBody(), $requestResponse->getBody());
    }

    public function testItCanSendRequestWithAdditionalConfig()
    {
        $client = Mockery::mock(Client::class);
        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com/';
        };
        
        $config = ['test' => 'config'];
        $requestBody = ['test' => ['request' => 'body']];
        $response = $this->createResponse(200);

        $client->expects()
            ->request('POST', 'http://www.foo.com/bar', [
                'headers' => [],
                'test' => 'config',
                'body' => json_encode($requestBody)
            ])
            ->once()
            ->andReturns($response);

        $requestResponse = $baseGateway->request('POST', 'bar', $requestBody, [], $config);

        $this->assertEquals($response->getBody(), $requestResponse->getBody());
    }
}
