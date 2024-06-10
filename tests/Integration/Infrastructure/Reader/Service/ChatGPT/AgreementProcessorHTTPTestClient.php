<?php


/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Component\HttpClient\HttpClientInterface;
use RuntimeException;

class AgreementProcessorHTTPTestClient implements HttpClientInterface
{
    public function __construct(private array $responses)
    {
    }

    public function request(string $method, string $path, array $body): array
    {
        if (empty($this->responses)) {
            throw new RuntimeException('No responses available.');
        }

        $response = array_shift($this->responses);

        return json_decode($response, true);
    }

    public function withOptions(array $array): void
    {
    }
}
