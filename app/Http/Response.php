<?php

namespace App\Http;

class Response
{
    private $status;

    public function __construct(int $status)
    {
        $this->status = $status;
    }

    public function getStatus(): int
    {
        return $this->status;
    }
}