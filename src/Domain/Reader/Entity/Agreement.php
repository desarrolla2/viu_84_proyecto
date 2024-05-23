<?php

namespace App\Domain\Reader\Entity;

abstract class Agreement
{
    private ?\DateTimeInterface $date = null;

    public function toArray(): array
    {
        return ['date' => $this->date()?->format('d/m/Y')];
    }

    public function setDate(\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function date(): ?\DateTimeInterface
    {
        return $this->date;
    }

    /** @return Person[] */
    abstract public function parties(): array;
}
