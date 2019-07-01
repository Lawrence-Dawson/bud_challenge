<?php

namespace App\Tests\Unit\Services;

use PHPUnit\Framework\TestCase;
use App\Services\TranslatorService;

class TranslatorServiceTest extends TestCase
{
    public function setUp(): void
    {
        $this->service = new TranslatorService;
    }
    public function testItCanTranslateDroidSpeakToGalacticBasic()
    {
        $text = '01100010 01100001 01110010';
        $result = $this->service->droidToBasic($text);

        $this->assertEquals('bar', $result);
    }
}