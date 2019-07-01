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

    public function releaseThePrincess()
    {
        $response = $this->deathStar->releaseThePrincess();
        
        return $this->handleResponse($response);
    }

    private function handleResponse(Response $response)
    {
        $body = $this->parseBody($response);

        if (!$this->isBadResponse($response)) {
            return $body;
        }

        throw new \Exception(
            $body['message'] ?? 'Error, request failed.',
            $response->getStatus()
        );
    }

    private function parseBody(Response $response): array
    {
        $body = json_decode($response->getBody(), true);
        foreach ($body as $key => $droid) {
            $basic = $this->translator->droidToBasic($droid);
            $body[$key] = $basic;
        }
        
        return $body;
    }

    public function isBadResponse(Response $response): bool
    {
        if ($response->getStatus() < 200 || $response->getStatus() > 299) {
            return true;
        }
        return false;
    }
}