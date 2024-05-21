<?php

namespace App\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Reader\Entity\Agreement;
use App\Domain\Reader\Entity\ResidentialLeaseAgreement;
use App\Domain\Reader\ValueObject\Text;

readonly class ResidentialLeaseProcessor extends AbstractAgreementProcessor
{
    protected function agreement(): Agreement
    {
        return new ResidentialLeaseAgreement();
    }

    protected function contentForScore(Text $text): string
    {
        return 'A partir del documento que aparece a continuación, ¿Se trata de un contrato de arrendamiento de una vivienda?'.PHP_EOL
            .$text->content();
    }

    /**
     * @param ResidentialLeaseAgreement $agreement
     */
    protected function parties(Agreement $agreement, Text $text): void
    {
        $content = $this->contentForParties($text);

        $response = $this->request($content);
        $message = $this->getMessageFromResponse($response);


        $lines = explode(PHP_EOL, $message);
        foreach ($lines as $line) {
            $line = trim($line, '|');
            if (str_contains($line, 'propietario')) {
                $data = explode('|', $line);
                $agreement->addLandLord($this->person($data));
                continue;
            }
            if (str_contains($line, 'arrendatario')) {
                $data = explode('|', $line);
                $agreement->addTenant($this->person($data));
            }
        }
    }

    private function contentForParties(Text $text): string
    {
        return ' A partir del documento que aparece a continuación, '.
            ' deseo que identifiques a las partes, y construyas una tabla con el siguiente formato: '.
            ' tipo (propietario o arrendatario) | nombre y apellidos | tipo de documento | documento de identificación '.PHP_EOL
            .$text->content();
    }
}
