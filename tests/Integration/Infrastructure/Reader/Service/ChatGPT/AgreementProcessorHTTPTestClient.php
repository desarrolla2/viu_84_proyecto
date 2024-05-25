<?php

namespace App\Tests\Integration\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Component\HttpClient\HttpClientInterface;

class AgreementProcessorHTTPTestClient implements HttpClientInterface
{
    public function __construct(private array $responses)
    {
    }

    public function request(string $method, string $path, array $body): array
    {
        if (empty($this->responses)) {
            throw new \RuntimeException('No responses available.');
        }

        $response = array_shift($this->responses);

        return json_decode($response, true);
    }

    public function withOptions(array $array): void
    {
    }
}
