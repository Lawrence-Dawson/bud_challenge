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

    public function testItCanGetDecodedResponseBodyIfJson()
    {
        $status = 200;
        $bodyArray = ['foo => bar'];
        $body = json_encode($bodyArray);
        $response = new Response($status, $body);

        $this->assertEquals($bodyArray, $response->getBody());
    }
}
