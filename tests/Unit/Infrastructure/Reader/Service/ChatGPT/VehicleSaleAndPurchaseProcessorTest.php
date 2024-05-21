<?php

namespace App\Tests\Unit\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Reader\Entity\Person;
use App\Domain\Reader\Entity\VehicleSaleAndPurchaseAgreement;
use App\Domain\Reader\Service\ReaderEngine;
use App\Domain\Reader\ValueObject\Text;
use App\Infrastructure\Reader\Service\ChatGPT\VehicleSaleAndPurchaseProcessor;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class VehicleSaleAndPurchaseProcessorTest extends TestCase
{
    public function dataProviderForTestProcessor(): array
    {
        return [
            '001.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMkruoEeQzTi0q1NtwLfFEhLWouk","object":"chat.completion","created":1716309353,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":761,"completion_tokens":3,"total_tokens":764},"system_fingerprint":"fp_e9446dc58f"}',
                    '{"id":"chatcmpl-9RNGDVVzj0DLo5JVBytmPCOXXxVbS","object":"chat.completion","created":1716311297,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"15/05/2024"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":773,"completion_tokens":6,"total_tokens":779},"system_fingerprint":"fp_e9446dc58f"}',
                    '{"id":"chatcmpl-9RMqdyinAvuDI2sgkEEvnTYDKl0gn","object":"chat.completion","created":1716309711,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"| Tipo         | Nombre y Apellidos   | Tipo de Documento | Documento de Identificación |\n|--------------|----------------------|-------------------|----------------------------|\n| Vendedor     | Juan Pérez Martínez  | N.I.F.            | 12345678A                  |\n| Comprador    | María García López   | N.I.F.            | 87654321B                  |"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":793,"completion_tokens":82,"total_tokens":875},"system_fingerprint":"fp_294de9593d"}',
                ],
                'sellers' => ['numbers' => ['12345678A']],
                'buyers' => ['numbers' => ['87654321B']],
                'date' => '15/05/2024',
            ],
            '002.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMtud1NMlV1VcFhsiNc9sSEM0OtU","object":"chat.completion","created":1716309914,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"Sí."},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":828,"completion_tokens":3,"total_tokens":831},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RNGfCsYNadReFTe8auJc1mlx0ivE","object":"chat.completion","created":1716311325,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"15/05/2024"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":840,"completion_tokens":6,"total_tokens":846},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RMtvQQZXpjr0ltvVAq1EDNOxEv5R","object":"chat.completion","created":1716309915,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"| tipo        | nombre y apellidos   | tipo de documento | documento de identificación |\n|-------------|----------------------|-------------------|-----------------------------|\n| Vendedor    | Alejandro Gómez Pérez | N.I.F.            | 98765432X                   |\n| Comprador   | Marta López Martín   | N.I.F.            | 54321678Y                   |"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":860,"completion_tokens":82,"total_tokens":942},"system_fingerprint":"fp_294de9593d"}',
                ],
                'sellers' => ['numbers' => ['98765432X']],
                'buyers' => ['numbers' => ['54321678Y']],
                'date' => '15/05/2024',
            ],
            '003.pdf' => [
                'responses' => [
                    '{"id":"chatcmpl-9RMtvQQZXpjr0ltvVAq1EDNOxEv5R","object":"chat.completion","created":1716309915,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"| tipo        | nombre y apellidos   | tipo de documento | documento de identificación |\n|-------------|----------------------|-------------------|-----------------------------|\n| Vendedor    | Alejandro Gómez Pérez | N.I.F.            | 98765432X                   |\n| Comprador   | Marta López Martín   | N.I.F.            | 54321678Y                   |"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":860,"completion_tokens":82,"total_tokens":942},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RNH96CA449Is2CUKp8m4vR45cXNM","object":"chat.completion","created":1716311355,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"15/05/2024"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1001,"completion_tokens":6,"total_tokens":1007},"system_fingerprint":"fp_294de9593d"}',
                    '{"id":"chatcmpl-9RMv165kiMPNNUGBTMXSwXJuwUaax","object":"chat.completion","created":1716309983,"model":"gpt-4-turbo-2024-04-09","choices":[{"index":0,"message":{"role":"assistant","content":"| Tipo        | Nombre y Apellidos     | Tipo de Documento | Documento de Identificación |\n|-------------|------------------------|-------------------|-----------------------------|\n| Vendedor    | José García Martínez   | N.I.F.            | 12345678A                   |\n| Comprador   | Laura Martínez Ruiz    | N.I.F.            | 98765432B                   |"},"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":1021,"completion_tokens":84,"total_tokens":1105},"system_fingerprint":"fp_294de9593d"}',
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
        $engine = new ReaderEngine(new NullLogger());
        $engine->addProcessor(new VehicleSaleAndPurchaseProcessor(new AgreementProcessorHTTPTestClient($responses), 'change.me'));

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
