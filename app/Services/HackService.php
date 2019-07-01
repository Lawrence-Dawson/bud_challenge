<?php

namespace App\Services;

use App\Http\Response;
use App\Services\TranslatorService;
use App\Http\Gateways\DeathStarGateway;

class HackService
{
    private $deathStar;
    private $translator;

    public function __construct(DeathStarGateway $deathStar, TranslatorService $translator)
    {
        $this->deathStar = $deathStar;
        $this->translator = $translator;
    }

    public function releaseThePrincess(): array
    {
        $response = $this->deathStar->releaseThePrincess();
        $body = $this->parseResponse($response);
        
        return $body;
    }

    private function parseResponse(Response $response)
    {
        $body = json_decode($response->getBody(), true);
        foreach ($body as $key => $droid) {
            $basic = $this->translator->droidToBasic($droid);
            $body[$key] = $basic;
        }
        return $body;
    }
}