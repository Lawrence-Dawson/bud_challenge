<?php

namespace App\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;

class TranslatorServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->service = new TranslatorService;
    }
    public function testItCanTranslateDroidSpeakToGalacticBasic()
    {
        $text = '{"foo": "01100010 01100001 01110010"}';
        $result = $this->service->droitToBasic();

        $this->assertEquals('{"foo": "bar"', $result);
    }
}