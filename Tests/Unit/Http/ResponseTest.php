<?php

namespace App\Tests\Unit\Http;

use App\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testItCanGetResponseStatus()
    {
        $status = 200;
        $response = new Response($status);

        $this->assertEquals($status, $response->getStatus());
    }

    public function testItCanGetResponseBody()
    {
        $status = 200;
        $body = 'foo is bar';
        $response = new Response($status, $body);

        $this->assertEquals($body, $response->getBody());
    }

    public function testItCanGetResponseHeaders()
    {
        $status = 200;
        $body = 'foo is bar';
        $headers = ['test' => 'headers'];
        $response = new Response($status, $body, $headers);

        $this->assertEquals($body, $response->getHeaders());
    }
}
