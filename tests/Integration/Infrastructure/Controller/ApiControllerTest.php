<?php


/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Tests\Integration\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiControllerTest extends WebTestCase
{

    public function testIndex(): void
    {
        $client = static::createClient();

        $client->xmlHttpRequest(Request::METHOD_POST, '/api/upload');
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

}
