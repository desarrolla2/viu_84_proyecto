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

use App\Domain\Reader\Entity\VehicleSaleAndPurchaseAgreementInterface;
use App\Domain\Reader\ValueObject\Text;

readonly class VehicleSaleAndPurchaseAgreementProcessor extends AbstractAgreementProcessor
{
    protected function agreementClassName(): string
    {
        return VehicleSaleAndPurchaseAgreementInterface::class;
    }

    protected function contentForScore(Text $text): string
    {
        return 'A partir del documento que aparece a continuación, ¿Se trata de un contrato de compraventa de vehiculo?'.PHP_EOL
            .$text->content();
    }

    protected function getTemplateForContent(): string
    {
        return <<<EOD
Extrae la siguiente información desde el texto dado:

1. Fecha del acuerdo
2. Vendedores ( nombre, apellidos, número de documento )
3. Compradores ( nombre, apellidos, número de documento )
4. Vehiculo ( marca, modelo y matrícula )
5. Precio de venta

Text:
%s

Por favor proporciona la información en el siguiente formato:

{
  "date": "YYYY-MM-DD",
  "sellers": [
    {
      "name": "John",
      "surname": "Doe",
      "number": "12345678A"
    }
  ],
  "buyers": [
    {
      "name": "Jane",
      "surname": "Smith",
      "number": "87654321B"
    }
  ],
  "vehicle": {
    "make": "Toyota",
    "model": "Corola",
    "license_plate": "7890 XYZ"
  },
  "price": "12000.00"
}

Asegurate que la respuesta es un JSON válido.
EOD;
    }
}
