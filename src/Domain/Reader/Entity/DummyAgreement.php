<?php

namespace App\Domain\Reader\Entity;

class DummyAgreement implements AgreementInterface
{
    public function parties(): array
    {
        return [];
    }
}
