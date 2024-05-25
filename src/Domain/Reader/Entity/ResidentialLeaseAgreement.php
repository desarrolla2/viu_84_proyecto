<?php

namespace App\Domain\Reader\Entity;

readonly class ResidentialLeaseAgreement implements Agreement
{
    public function __construct(
        private \DateTimeInterface $date,
        private Property $property,
        private array $landLords = [],
        private array $tenants = [],
        private float $monthlyRent = 0.0
    ) {
    }

    public function monthlyRent(): float
    {
        return round($this->monthlyRent, 2);
    }

    public function date(): \DateTimeInterface
    {
        return $this->date;
    }

    public function property(): Property
    {
        return $this->property;
    }

    /** @return Person[] */
    public function landLords(): array
    {
        return $this->landLords;
    }

    public function parties(): array
    {
        return array_merge($this->landLords(), $this->tenants());
    }

    /** @return Person[] */
    public function tenants(): array
    {
        return $this->tenants;
    }
}
