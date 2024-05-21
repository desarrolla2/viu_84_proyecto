<?php

namespace App\Infrastructure\Reader\Service\ChatGPT;

use App\Domain\Reader\Entity\AgreementInterface;
use App\Domain\Reader\Entity\VehicleSaleAndPurchaseAgreement;
use App\Domain\Reader\ValueObject\Text;

readonly class VehicleSaleAndPurchaseProcessor extends AbstractAgreementProcessor
{
    protected function agreement(): AgreementInterface
    {
        return new VehicleSaleAndPurchaseAgreement();
    }

    protected function contentForScore(Text $text): string
    {
        return 'A partir del documento que aparece a continuación, ¿Se trata de un contrato de compraventa de vehiculo?'.PHP_EOL
            .$text->content();
    }

    /**
     * @param VehicleSaleAndPurchaseAgreement $agreement
     */
    protected function parties(AgreementInterface $agreement, Text $text): void
    {
        $content = $this->contentForParties($text);

        $response = $this->request($content);
        $message = $this->getMessageFromResponse($response);


        $lines = explode(PHP_EOL, $message);
        foreach ($lines as $line) {
            $line = trim($line, '|');
            if (str_contains($line, 'vendedor')) {
                $data = explode('|', $line);
                $agreement->addSeller($this->person($data));
                continue;
            }
            if (str_contains($line, 'comprador')) {
                $data = explode('|', $line);
                $agreement->addBuyer($this->person($data));
            }
        }
    }

    private function contentForParties(Text $text): string
    {
        return ' A partir del documento que aparece a continuación, '.
            ' deseo que identifiques a las partes, y construyas una tabla con el siguiente formato: '.
            ' tipo (vendedor o comprador) | nombre y apellidos | tipo de documento | documento de identificación '.PHP_EOL
            .$text->content();
    }
}
