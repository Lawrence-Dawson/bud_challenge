<?php

namespace App\Http;

class Response
{
    private $status;
    private $body;

    public function __construct(int $status, $body = '')
    {
        $this->status = $status;
        $this->body = $body;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getBody()
    {
        return $this->body;
    }
}