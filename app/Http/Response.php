<?php

namespace App\Http;

class Response
{
    private $status;
    private $body;

    public function __construct(int $status, string $body = '', array $headers = [])
    {
        $this->status = $status;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaders()
    {
        return $this->body;
    }
}