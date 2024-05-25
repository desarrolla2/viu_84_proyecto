<?php

namespace App\Domain\Reader\Entity;

interface Agreement
{
    public function date(): \DateTimeInterface;

    /** @return Person[] */
    public function parties(): array;
}
