<?php

namespace App\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use PHPUnit\Framework\TestCase;

class BaseGatewayTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testItCanSendRequest()
    {
        $container = [];
        $history = Middleware::history($container);
        $stack = HandlerStack::create();
        $stack->push($history);

        $client = new Client(['handler' => $stack]);

        $baseGateway = new class($client) extends BaseGateway {
            protected $baseUrl = 'http://www.foo.com';
        };

        $baseGateway->request('GET', '/bar');

        $this->assertTrue(count($container) == 1);
    }
}
