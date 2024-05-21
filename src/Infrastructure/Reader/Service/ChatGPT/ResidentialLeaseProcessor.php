<?php

namespace App\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Component\HttpClient\HttpClientInterface;
use App\Domain\Reader\Entity\Person;
use App\Domain\Reader\Entity\ResidentialLeaseAgreement;
use App\Domain\Reader\Service\ProcessorInterface;
use App\Domain\Reader\ValueObject\Text;
use Symfony\Component\HttpFoundation\Request;

readonly class ResidentialLeaseProcessor implements ProcessorInterface
{
    const ENDPOINT = 'https://api.openai.com/v1/chat/completions';
    const MODEL = 'gpt-4-turbo';

    public function __construct(private HttpClientInterface $httpClient, string $authenticationToken)
    {
        $this->httpClient->withOptions(['auth_bearer' => $authenticationToken,]);
    }

    public function execute(Text $text): ResidentialLeaseAgreement
    {
        $agreement = new ResidentialLeaseAgreement();

        $content = $this->content($text);

        $response = $this->request($content);
        $message = $this->getMessageFromResponse($response);

        $lines = explode(PHP_EOL, $message);
        foreach ($lines as $line) {
            if (str_contains($line, 'propietario:')) {
                $line = str_replace('propietario:', '', $line);
                $agreement->addLandLord($this->person($line));
            }
            if (str_contains($line, 'arrendatario:')) {
                $line = str_replace('arrendatario:', '', $line);
                $agreement->addTenant($this->person($line));
            }
        }

        return $agreement;
    }

    public function score(Text $text): int
    {
        $response = $this->request(sprintf('En el siguiente contrato:\n\n %s \n\nResponde sólamente SI o NO, ¿Se trata de un contrato de arrendamiento de una vivienda?', $text->content()));

        $message = $this->getMessageFromResponse($response);
        if (str_contains($message, 'si')) {
            return 90;
        }

        return -1;
    }

    private function content(Text $text): string
    {
        return ' A partir del documento que aparece a continuación, '.
            ' deseo que identifiques a las partes, y construyas una tabla con el siguiente formato: '.
            ' tipo (propietario o arrendatario) | nombre y apellidos | documento de identificación '.PHP_EOL
            .$text->content();
    }

    private function getMessageFromResponse(array $response): string
    {
        $message = array_values($response['choices'])[0]['message']['content'];

        return $this->normalize($message);
    }

    private function normalize(string $message): string
    {
        $message = mb_strtolower($message);

        return str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $message);
    }

    private function person(string $line): Person
    {
        $line = str_replace(['dni', 'd.', ':'], '', $line);
        $line = explode(',', $line);

        return new Person(trim($line[0]), strtoupper(trim($line[1])));
    }

    private function request(string $content): array
    {
        $json = [
            'model' => self::MODEL,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres un abogado. Responde a cada pregunta con precisión. Si una pregunta puede responderse con si o no, simplemente indica si, no justifiques tu respuesta',
                ],
                ['role' => 'user', 'content' => $content,],
            ],
        ];

        return $this->httpClient->request(Request::METHOD_POST, self::ENDPOINT, ['json' => $json,]);
    }
}
