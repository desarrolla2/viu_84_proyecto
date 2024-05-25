<?php

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
