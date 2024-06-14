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
use App\Domain\Reader\Entity\ResidentialLeaseAgreement;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text;
use App\Infrastructure\Reader\Service\ChatGPT\ResidentialLeaseAgreementProcessor;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class ResidentialLeaseProcessorTest extends KernelTestCase
{
    public function dataProviderForTestProcessor(): array
    {
        return [
            '001.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RLp8sFJxbfOwR95SybZ4AoBqLQtA","object":"chat.completion","created":1716305774,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3593,"completion_tokens":3,"total_tokens":3596},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9Sk3nKf14GAFjbo8pwBzOV0a5cTxE","object":"chat.completion","created":1716637267,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"{\n    \"date\": \"2023-01-01\",\n    \"landlords\": [\n        {\n            \"name\": \"Juan\",\n            \"surname\": \"P\u00e9rez Mart\u00edn\",\n            \"number\": \"98765432B\"\n        }\n    ],\n    \"tenants\": [\n        {\n        \"name\": \"Laura\",\n        \"surname\": \"Fern\u00e1ndez de la Fuente\",\n        \"number\": \"82657077E\"\n        }\n    ],\n    \"property\": {\n        \"address\": {\n            \"address\": \"Paseo de la Invenci\u00f3n, 789\",\n            \"city\": \"Madrid\",\n            \"country\": \"Spain\",\n            \"postal_code\": \"28003\"\n        }\n    },\n    \"monthly_rent\": \"1000.00\"\n}"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3798,"completion_tokens":161,"total_tokens":3959},"system_fingerprint":"fp_f17df3916f"}',
                ],
                'landLords' => ['numbers' => ['98765432B']],
                'tenants' => ['numbers' => ['82657077E']],
                'date' => '01/01/2023',
            ],
            '002.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMNMDpjjXH48Ne2j7GaFrMLlASGm","object":"chat.completion","created":1716307896,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3386,"completion_tokens":3,"total_tokens":3389},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9SkPY4otddeNMU1pjYN7CYxzmGZKT","object":"chat.completion","created":1716638616,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"{\n    \"date\": \"2023-02-02\",\n    \"landlords\": [\n        {\n            \"name\": \"Carlos\",\n            \"surname\": \"Rodr\u00edguez Perez\",\n            \"number\": \"98765432B\"\n        }\n    ],\n    \"tenants\": [\n        {\n            \"name\": \"Sofia\",\n            \"surname\": \"Torres Gutierrez\",\n            \"number\": \"54321098C\"\n        }\n    ],\n    \"property\": {\n        \"address\": {\n            \"address\": \"Calle de Serrano 200\",\n            \"city\": \"Madrid\",\n            \"country\": \"Espa\u00f1a\",\n            \"postal_code\": \"28001\"\n        }\n    },\n    \"monthly_rent\": \"1000.00\"\n}"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3605,"completion_tokens":159,"total_tokens":3764},"system_fingerprint":"fp_53e33d3c75"}',
                ],
                'landLords' => ['numbers' => ['98765432B']],
                'tenants' => ['numbers' => ['54321098C']],
                'date' => '02/02/2023',
            ],
            '003.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMOmFbQv8Fji5JMfAOjSN0RBqKE8","object":"chat.completion","created":1716307984,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1245,"completion_tokens":3,"total_tokens":1248},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9SkQLcS3TqEoWOwLYVjb40IvpTOla","object":"chat.completion","created":1716638665,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"{\n    \"date\": \"2023-03-03\",\n    \"landlords\": [\n        {\n            \"name\": \"Laura\",\n            \"surname\": \"Garc\u00eda Mart\u00ednez\",\n            \"number\": \"56382214Z\"\n        }\n    ],\n    \"tenants\": [\n        {\n            \"name\": \"Ra\u00fal\",\n            \"surname\": \"Fern\u00e1ndez P\u00e9rez\",\n            \"number\": \"Z2772495B\"\n        }\n    ],\n    \"property\": {\n        \"address\": {\n            \"address\": \"Calle Abril, 9, 55\u00ba C\",\n            \"city\": \"Puerto del Rosario\",\n            \"country\": \"Spain\",\n            \"postal_code\": \"N\/A\"\n        }\n    },\n    \"monthly_rent\": \"1000.00\"\n}"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1464,"completion_tokens":168,"total_tokens":1632},"system_fingerprint":"fp_fe1929285d"}',
                ],
                'landLords' => ['numbers' => ['56382214Z']],
                'tenants' => ['numbers' => ['Z2772495B']],
                'date' => '03/03/2023',
            ],
        ];
    }

    /** @dataProvider dataProviderForTestProcessor */
    public function testProcessor(array $responses, array $landLords, array $tenants, string $date): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $serializer = $container->get(SerializerInterface::class);

        $engine = new ReaderEngine(new NullLogger());
        $processor = new ResidentialLeaseAgreementProcessor($serializer, new AgreementProcessorHTTPTestClient($responses), 'change.me');

        $engine->addProcessor($processor);

        /** @var ResidentialLeaseAgreement $agreement */
        $agreement = $engine->execute(new Text());

        $this->assertInstanceOf(ResidentialLeaseAgreement::class, $agreement);
        $this->assert($agreement->landLords(), $landLords['numbers']);
        $this->assert($agreement->tenants(), $tenants['numbers']);
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
