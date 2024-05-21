<?php

namespace App\Domain\Reader\Entity;

class DummyAgreement extends Agreement
{
    public function parties(): array
    {
        return [];
    }

    public function date(): \DateTimeInterface
    {
        return new \DateTime();
    }
}
