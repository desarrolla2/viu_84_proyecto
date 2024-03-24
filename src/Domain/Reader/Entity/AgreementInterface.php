<?php

namespace App\Domain\Reader\Entity;

interface AgreementInterface
{
    /** @return Person[] */
    public function parties(): array;
}
