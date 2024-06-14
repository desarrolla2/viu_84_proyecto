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

use App\Domain\Reader\Entity\Person;
use App\Domain\Reader\Entity\VehicleSaleAndPurchaseAgreement;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text;
use App\Infrastructure\Reader\Service\ChatGPT\VehicleSaleAndPurchaseAgreementProcessor;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class VehicleSaleAndPurchaseProcessorTest extends KernelTestCase
{
    public function dataProviderForTestProcessor(): array
    {
        return [
            '001.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMkruoEeQzTi0q1NtwLfFEhLWouk","object":"chat.completion","created":1716309353,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":761,"completion_tokens":3,"total_tokens":764},"system_fingerprint":"fp_e9446dc58f"}',
                    '{"id":"chatcmpl-9SkBdtjlINBfq1vX8xzJbKqS33XZ8","object":"chat.completion","created":1716637753,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"{\n  \"date\": \"2024-05-15\",\n  \"sellers\": [\n    {\n      \"name\": \"Juan\",\n      \"surname\": \"P\u00e9rez Mart\u00ednez\",\n      \"number\": \"12345678A\"\n    }\n  ],\n  \"buyers\": [\n    {\n    \"name\": \"Mar\u00eda\",\n    \"surname\": \"Garc\u00eda L\u00f3pez\",\n    \"number\": \"87654321B\"\n    }\n  ],\n  \"vehicle\": {\n    \"make\": \"Toyota\",\n    \"model\": \"COROLLA\",\n    \"license_plate\": \"1234 ABC\"\n  },\n  \"price\": \"5000\"\n}"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":964,"completion_tokens":136,"total_tokens":1100},"system_fingerprint":"fp_f17df3916f"}',
                ],
                'sellers' => ['numbers' => ['12345678A']],
                'buyers' => ['numbers' => ['87654321B']],
                'date' => '15/05/2024',
            ],
            '002.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMtud1NMlV1VcFhsiNc9sSEM0OtU","object":"chat.completion","created":1716309914,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":828,"completion_tokens":3,"total_tokens":831},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9SkY8RXmR6hA3rVuINjpsqd3wzbOa","object":"chat.completion","created":1716639148,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"{\n  \"date\": \"2024-05-15\",\n  \"sellers\": [\n    {\n      \"name\": \"Alejandro\",\n      \"surname\": \"G\u00f3mez P\u00e9rez\",\n      \"number\": \"98765432X\"\n    }\n  ],\n  \"buyers\": [\n    {\n  \"name\": \"Marta\",\n  \"surname\": \"L\u00f3pez Mart\u00edn\",\n  \"number\": \"54321678Y\"\n    }\n  ],\n  \"vehicle\": {\n    \"make\": \"Volkswagen\",\n    \"model\": \"Polo\",\n    \"license_plate\": \"7890 XYZ\"\n  },\n  \"price\": \"6500.00\"\n}"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1029,"completion_tokens":139,"total_tokens":1168},"system_fingerprint":"fp_f17df3916f"}',
                ],
                'sellers' => ['numbers' => ['98765432X']],
                'buyers' => ['numbers' => ['54321678Y']],
                'date' => '15/05/2024',
            ],
            '003.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMtvQQZXpjr0ltvVAq1EDNOxEv5R","object":"chat.completion","created":1716309915,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"| tipo        | nombre y apellidos   | tipo de documento | documento de identificación |\n|-------------|----------------------|-------------------|-----------------------------|\n| Vendedor    | Alejandro Gómez Pérez | N.I.F.            | 98765432X                   |\n| Comprador   | Marta López Martín   | N.I.F.            | 54321678Y                   |"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":860,"completion_tokens":82,"total_tokens":942},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9SkYbW2PX61qrGWkFodFrP970QxFI","object":"chat.completion","created":1716639177,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"{\n  \"date\": \"2024-05-15\",\n  \"sellers\": [\n    {\n      \"name\": \"Jos\u00e9\",\n      \"surname\": \"Garc\u00eda Mart\u00ednez\",\n      \"number\": \"12345678A\"\n    }\n  ],\n  \"buyers\": [\n    {\n      \"name\": \"Laura\",\n      \"surname\": \"Mart\u00ednez Ruiz\",\n      \"number\": \"98765432B\"\n    }\n  ],\n  \"vehicle\": {\n    \"make\": \"Volkswagen\",\n    \"model\": \"Golf\",\n    \"license_plate\": \"1234 ABC\"\n  },\n  \"price\": \"?\"\n}"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1185,"completion_tokens":135,"total_tokens":1320},"system_fingerprint":"fp_f17df3916f"}',
                ],
                'sellers' => ['numbers' => ['12345678A']],
                'buyers' => ['numbers' => ['98765432B']],
                'date' => '15/05/2024',
            ],
        ];
    }

    /** @dataProvider dataProviderForTestProcessor */
    public function testProcessor(array $responses, array $sellers, array $buyers, string $date): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $serializer = $container->get(SerializerInterface::class);

        $engine = new ReaderEngine(new NullLogger());
        $processor = new VehicleSaleAndPurchaseAgreementProcessor($serializer, new AgreementProcessorHTTPTestClient($responses), 'change.me');

        $engine->addProcessor($processor);

        /** @var VehicleSaleAndPurchaseAgreement $agreement */
        $agreement = $engine->execute(new Text());
        $this->assertInstanceOf(VehicleSaleAndPurchaseAgreement::class, $agreement);
        $this->assert($agreement->sellers(), $sellers['numbers']);
        $this->assert($agreement->buyers(), $buyers['numbers']);
        $this->assertEquals($date, $agreement->date()?->format('d/m/Y'));
    }

    private function assert(array $persons, array $expectedNumbers): void
    {
        $numbers = [];
        /** @var Person $person */
        foreach ($persons as $person) {
            $numbers[] = $person->number();
        }
        $this->assertSameSize($expectedNumbers, $numbers);
        foreach ($expectedNumbers as $expectedNumber) {
            $this->assertContains($expectedNumber, $numbers);
        }
    }
}
