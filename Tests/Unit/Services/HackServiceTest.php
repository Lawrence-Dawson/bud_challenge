<?php

namespace App\Tests\Unit\Services;

use Mockery;
use App\Http\Response;
use App\Services\HackService;
use PHPUnit\Framework\TestCase;
use App\Services\TranslatorService;
use App\Http\Gateways\DeathStarGateway;

class HackServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->translator = Mockery::mock(TranslatorService::class);
        $this->deathStar = Mockery::mock(DeathStarGateway::class);
        $this->service = new HackService($this->translator, $this->deathStar);
    }
    public function testItTranslatesAnyDroidSpeakInResponseToGalacticBasic()
    {
        $cell = '01000011 01100101 01101100 01101100
        00100000 00110010 00110001 00111000
        0110111';

        $block = '01000100 01100101 01110100 01100101
        01101110 01110100 01101001 01101111
        01101110 00100000 01000010 01101100
        01101111 01100011 01101011 00100000
        01000001 01000001 00101101 00110010
        00110011 00101100';

        $json = json_encode([
            'cell' => $cell,
            'block' => $block
        ]);

        $response = new Response(200, $json);

        $this->deathStar
            ->expects()
            ->releaseThePrincess()
            ->once()
            ->andReturns($response);
        
        $this->translator
            ->expects()
            ->droidToBasic($cell)
            ->once()
            ->andReturns('CELL 2187');

        $this->translator
            ->expects()
            ->droidToBasic($block)
            ->once()
            ->andReturns('Detention Block AA-23');

        $this->service->releaseThePrincess();
    }
}