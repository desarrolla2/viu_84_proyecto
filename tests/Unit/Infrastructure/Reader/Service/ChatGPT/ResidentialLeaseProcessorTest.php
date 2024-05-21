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
                    '{"id":"chatcmpl-9RLp8sFJxbfOwR95SybZ4AoBqLQtA","object":"chat.completion","created":1716305774,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3593,"completion_tokens":3,"total_tokens":3596},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RN97c46MNWTnGm26JmBI3qlMS2UG","object":"chat.completion","created":1716310857,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"01/01/2023"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3589,"completion_tokens":6,"total_tokens":3595},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RMMRXccQAfaZf3FGVvDiw1YjKYzX","object":"chat.completion","created":1716307839,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"| Tipo             | Nombre y Apellidos           | Tipo de documento | Documento de identificación |\n|------------------|------------------------------|--------------------|-----------------------------|\n| Propietario      | Juan Pérez Martín            | DNI                | 98765432B                   |\n| Arrendatario     | Laura Fernández de la Fuente | DNI                | 82657077E                   |"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3613,"completion_tokens":81,"total_tokens":3694},"system_fingerprint":"fp_e9446dc58f"}',
                ],
                'landLords' => ['numbers' => ['98765432B']],
                'tenants' => ['numbers' => ['82657077E']],
                'date' => '01/01/2023',
            ],
            '002.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMNMDpjjXH48Ne2j7GaFrMLlASGm","object":"chat.completion","created":1716307896,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3386,"completion_tokens":3,"total_tokens":3389},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RNA8vrrgGRfBSqfxZXDnJzzIWiGk","object":"chat.completion","created":1716310920,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"01/01/2023"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3397,"completion_tokens":6,"total_tokens":3403},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RMNOXZb6q7Niw0yKEOY4XU00Rofx","object":"chat.completion","created":1716307898,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"| Tipo              | Nombre y Apellidos       | Tipo de Documento | Documento de Identificación |\n|-------------------|--------------------------|-------------------|-----------------------------|\n| Propietario       | Carlos Rodríguez Perez   | NIF               | 98765432B                   |\n| Arrendatario      | Sofia Torres Gutierrez   | NIF               | 54321098C                   |"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":3421,"completion_tokens":82,"total_tokens":3503},"system_fingerprint":"fp_294de9593d"}',
                ],
                'landLords' => ['numbers' => ['98765432B']],
                'tenants' => ['numbers' => ['54321098C']],
                'date' => '01/01/2023',
            ],
            '003.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMOmFbQv8Fji5JMfAOjSN0RBqKE8","object":"chat.completion","created":1716307984,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1245,"completion_tokens":3,"total_tokens":1248},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RNB4NmNMmtNhjCt8lgGcpqmjzDZe","object":"chat.completion","created":1716310978,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"03/03/2023"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1256,"completion_tokens":6,"total_tokens":1262},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RMOnTyPQOFR3Bbx5diqeud2nl9vo","object":"chat.completion","created":1716307985,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"tipo | nombre y apellidos | tipo de documento | documento de identificación \n---|---|---|---\npropietario | Laura García Martínez | DNI | 56382214Z\narrendatario | Raúl Fernández Pérez | DNI | Z2772495B"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1280,"completion_tokens":64,"total_tokens":1344},"system_fingerprint":"fp_e9446dc58f"}',
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
        $engine = new ReaderEngine(new NullLogger());
        $engine->addProcessor(new ResidentialLeaseProcessor(new AgreementProcessorHTTPTestClient($responses), 'change.me'));

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
