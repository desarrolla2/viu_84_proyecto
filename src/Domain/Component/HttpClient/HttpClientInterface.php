<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Component\HttpClient;

interface HttpClientInterface
{
    public function request(string $method, string $path, array $body): array;

    public function withOptions(array $array): void;
}
