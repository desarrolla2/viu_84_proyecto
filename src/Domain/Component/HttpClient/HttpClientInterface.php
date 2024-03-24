<?php

namespace App\Domain\Component\HttpClient;

interface HttpClientInterface
{
    public function request(string $method, string $path, array $body): array;

    public function withOptions(array $array): void;
}
