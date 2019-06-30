<?php

namespace App\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testItCanSetResponseStatus()
    {
        $status = 200;
        $response = new Response($status);

        $this->assertEquals($status, $response->getStatus());
    }
}
