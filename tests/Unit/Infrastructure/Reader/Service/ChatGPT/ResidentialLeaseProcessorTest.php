<?php

namespace App\Tests\Unit\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Reader\Entity\Person;
use App\Domain\Reader\Entity\ResidentialLeaseAgreement;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text;
use App\Infrastructure\Reader\Service\ChatGPT\ResidentialLeaseProcessor;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class ResidentialLeaseProcessorTest extends TestCase
{
    public function dataProviderForTestProcessor(): array
    {
        return [
            '001.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-8cEmZlb93XnvAiWBCCuMHtn3FbiLi","object":"chat.completion","created":1704123979,"model":"gpt-3.5-turbo-0613","choices":[{"index":0,"message":{"role":"assistant","content":"Sí"},"logprobs":null,"finish_reason":"stop"}]}',
                    '{"id":"chatcmpl-8cFIY5s7frcfqvJCiVDAxbkFgUsnP","object":"chat.completion","created":1704125962,"model":"gpt-3.5-turbo-0613","choices":[{"index":0,"message":{"role":"assistant","content":"PROPIETARIO: Juan Pérez Martín, DNI 98765432B\nARRENDATARIO: Laura Fernández de la Fuente, DNI 82657077E"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3782,"completion_tokens":40,"total_tokens":3822},"system_fingerprint":null}',
                ],
                'landLords' => ['numbers' => ['98765432B']],
                'tenants' => ['numbers' => ['82657077E']],
            ],
            '002.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-8cFLg6S77apGsfjImWgGxY6ErBDbH","object":"chat.completion","created":1704126156,"model":"gpt-3.5-turbo-0613","choices":[{"index":0,"message":{"role":"assistant","content":"Sí"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3376,"completion_tokens":2,"total_tokens":3378},"system_fingerprint":null}',
                    '{"id":"chatcmpl-8cFLh7RXwsz6UC2KPRxykXfWETspc","object":"chat.completion","created":1704126157,"model":"gpt-3.5-turbo-0613","choices":[{"index":0,"message":{"role":"assistant","content":"PROPIETARIO: Carlos Rodríguez Perez, 98765432B\nARRENDATARIO: Sofia Torres Gutierrez, 54321098C"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3397,"completion_tokens":33,"total_tokens":3430},"system_fingerprint":null}',
                ],
                'landLords' => ['numbers' => ['98765432B']],
                'tenants' => ['numbers' => ['54321098C']],
            ],
        ];
    }

    /** @dataProvider dataProviderForTestProcessor */
    public function testProcessor(array $responses, array $landLords, array $tenants): void
    {
        $engine = new ReaderEngine(new NullLogger());
        $engine->addProcessor(new ResidentialLeaseProcessor(new ResidentialLeaseHTTPTestClient($responses), 'change.me'));

        /** @var ResidentialLeaseAgreement $agreement */
        $agreement = $engine->execute(new Text());
        $this->assertInstanceOf(ResidentialLeaseAgreement::class, $agreement);
        $this->assert($agreement->landLords(), $landLords['numbers']);
        $this->assert($agreement->tenants(), $tenants['numbers']);
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
