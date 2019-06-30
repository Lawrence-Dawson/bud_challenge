<?php

namespace App\Http;

class Response
{
    private $status;
    private $body;

    public function __construct(int $status, $body = '')
    {
        $this->status = $status;
        if ($this->isJson($body)) {
            $body = json_decode($body, true);
        }
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

    private function isJson(string $body)
    {
        $json = json_decode($body);
        return $json && $body != $json;
    }
}