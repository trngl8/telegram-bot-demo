<?php

namespace App\Tgrm;

interface ApiHttpClientInterface
{
    public function sendRequest(string $command, string $method, ?array $params = []) : array;
}
