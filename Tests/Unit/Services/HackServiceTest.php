<?php

namespace App\Tests\Unit\Services;

use Mockery;
use App\Http\Response;
use App\Services\HackingService;
use PHPUnit\Framework\TestCase;
use App\Services\TranslatorService;
use App\Http\Gateways\DeathStarGateway;

class HackingServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->translator = Mockery::mock(TranslatorService::class);
        $this->deathStar = Mockery::mock(DeathStarGateway::class);
        $this->service = new HackingService($this->deathStar, $this->translator);
    }

    public function testItTranslatesAnyDroidSpeakInResponseToGalacticBasic()
    {
        $cellDroid = '01000011 01100101 01101100 01101100
        00100000 00110010 00110001 00111000
        0110111';
        $cellBasic = 'CELL 2187';
        $blockDroid = '01000100 01100101 01110100 01100101
        01101110 01110100 01101001 01101111
        01101110 00100000 01000010 01101100
        01101111 01100011 01101011 00100000
        01000001 01000001 00101101 00110010
        00110011 00101100';
        $blockBasic = 'Detention Block AA-23';


        $json = json_encode([
            'cell' => $cellDroid,
            'block' => $blockDroid
        ]);

        $response = new Response(200, $json);

        $this->deathStar
            ->expects()
            ->releasePrincess()
            ->once()
            ->andReturns($response);
        
        $this->translator
            ->expects()
            ->droidToBasic($cellDroid)
            ->once()
            ->andReturns($cellBasic);

        $this->translator
            ->expects()
            ->droidToBasic($blockDroid)
            ->once()
            ->andReturns($blockBasic);

        $result = $this->service->releasePrincess();

        $this->assertEquals([
            'cell' => $cellBasic,
            'block' => $blockBasic
        ], $result);
    }

    public function testItCanHandleABadResponse()
    {
        $messageDroid = '01000001 01100011 01100011 01100101 
        01110011 01110011 00100000 01000100 01000101 
        01001110 01001001 01000101 01000100 00100000 
        01111001 01101111 01110101 00100000 01110000 
        01110101 01101110 01111001 00100000 01110010 
        01100101 01100010 01100101 01101100 00100000 
        01110011 01100011 01110101 01101101 00100001';
        $messageBasic = 'Access DENIED you puny rebel scum!';
        $responseCode = 401;

        $json = json_encode([
            'message' => $messageDroid
        ]);

        $response = new Response(401, $json);

        $this->deathStar
            ->expects()
            ->releasePrincess()
            ->once()
            ->andReturns($response);
        
        $this->translator
            ->expects()
            ->droidToBasic($messageDroid)
            ->once()
            ->andReturns($messageBasic);

        $this->expectExceptionCode($responseCode);
        $this->expectExceptionMessage($messageBasic);

        $this->service->releasePrincess();
    }

    public function testItCanDestroyTheDeathStar()
    {
        $messageDroid = '01010101 01101000 00100000 01101111 
                        01101000 00101110 00101110 00101110';
        $messageBasic = 'Uh oh...';

        $json = json_encode([
            'message' => $messageDroid
        ]);

        $response = new Response(200, $json);

        $this->deathStar
            ->expects()
            ->destroy()
            ->once()
            ->andReturns($response);
        
        $this->translator
            ->expects()
            ->droidToBasic($messageDroid)
            ->once()
            ->andReturns($messageBasic);

        $result = $this->service->destroyDeathStar();

        $this->assertEquals([
            'message' => $messageBasic,
        ], $result);
    }
}