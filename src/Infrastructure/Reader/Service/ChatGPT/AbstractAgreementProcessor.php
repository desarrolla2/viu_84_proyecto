<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Component\HttpClient\HttpClientInterface;
use App\Domain\Reader\Entity\Agreement;
use App\Domain\Reader\Service\ProcessorInterface;
use App\Domain\Reader\ValueObject\Text;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

abstract readonly class AbstractAgreementProcessor implements ProcessorInterface
{
    const ENDPOINT = 'https://api.openai.com/v1/chat/completions';
    const MODEL = 'gpt-4-turbo';

    public function __construct(protected SerializerInterface $serializer, private HttpClientInterface $httpClient, string $authenticationToken)
    {
        $this->httpClient->withOptions(['auth_bearer' => $authenticationToken, ]);
    }

    public function score(Text $text): int
    {
        $response = $this->request($this->contentForScore($text));
        if (str_contains(mb_strtolower($response), 'sí')) {
            return 75;
        }
        if (str_contains(mb_strtolower($response), 'si')) {
            return 75;
        }

        return -1;
    }

    public function execute(Text $text): ?Agreement
    {
        $content = $this->contentForContent($text);
        $response = $this->request($content);

        $agreement = $this->serializer->deserialize($response, $this->agreementClassName(), 'json');

        return $agreement ?? null;
    }

    abstract protected function agreementClassName(): string;

    abstract protected function contentForScore(Text $text): string;

    abstract protected function getTemplateForContent(): string;

    private function request(string $content): string
    {
        $json = [
            'model' => self::MODEL,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un abogado. Responde a cada pregunta con precisión. No justifiques tu respuesta. No des detalles adicionales.',
                ],
                ['role' => 'user', 'content' => $content, ],
            ],
        ];
        $response = $this->httpClient->request(Request::METHOD_POST, self::ENDPOINT, ['json' => $json, ]);

        return $this->getMessageFromResponse($response);
    }

    private function getMessageFromResponse(array $response): string
    {
        $message = array_values($response['choices'])[0]['message']['content'];

        return $this->normalize($message);
    }

    private function normalize(string $message): string
    {
        return trim($message);
    }

    private function contentForContent(Text $text): string
    {
        $template = $this->getTemplateForContent();

        return sprintf($template, $text->content());
    }
}
