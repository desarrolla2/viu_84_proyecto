<?php

namespace App\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Component\HttpClient\HttpClientInterface;
use App\Domain\Reader\Entity\Agreement;
use App\Domain\Reader\Entity\Person;
use App\Domain\Reader\Service\ProcessorInterface;
use App\Domain\Reader\ValueObject\Text;
use Symfony\Component\HttpFoundation\Request;

abstract readonly class AbstractAgreementProcessor implements ProcessorInterface
{
    const ENDPOINT = 'https://api.openai.com/v1/chat/completions';
    const MODEL = 'gpt-4-turbo';

    public function __construct(private HttpClientInterface $httpClient, string $authenticationToken)
    {
        $this->httpClient->withOptions(['auth_bearer' => $authenticationToken,]);
    }

    public function score(Text $text): int
    {
        $response = $this->request($this->contentForScore($text));

        $message = $this->getMessageFromResponse($response);
        if (str_contains($message, 'si')) {
            return 90;
        }

        return -1;
    }

    public function execute(Text $text): Agreement
    {
        $agreement = $this->agreement();

        $this->date($agreement, $text);
        $this->parties($agreement, $text);

        return $agreement;
    }

    abstract protected function contentForScore(Text $text): string;

    abstract protected function agreement(): Agreement;

    abstract protected function parties(Agreement $agreement, Text $text): void;

    protected function person(array $data): Person
    {
        return new Person(trim($data[1]), strtoupper(trim($data[3])));
    }

    protected function getMessageFromResponse(array $response): string
    {
        $message = array_values($response['choices'])[0]['message']['content'];

        return $this->normalize($message);
    }

    protected function request(string $content): array
    {
        $json = [
            'model' => self::MODEL,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un abogado. Responde a cada pregunta con precisión. No justifiques tu respuesta.',
                ],
                ['role' => 'user', 'content' => $content,],
            ],
        ];

        return $this->httpClient->request(Request::METHOD_POST, self::ENDPOINT, ['json' => $json,]);
    }

    private function normalize(string $message): string
    {
        $message = mb_strtolower($message);

        return str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $message);
    }

    private function date(Agreement $agreement, Text $text): void
    {
        $content = $this->contentForDate($text);

        $response = $this->request($content);
        $message = $this->getMessageFromResponse($response);
        $date = \DateTime::createFromFormat('d/m/Y', trim($message));
        if (!$date) {
            return;
        }
        $agreement->setDate($date);
    }

    private function contentForDate(Text $text): string
    {
        return ' A partir del documento que aparece a continuación, '.
            ' deseo que identifiques la fecha en la que toma validez, indica la respuesta en formato dd/mm/YYYY'.PHP_EOL
            .$text->content();
    }
}
