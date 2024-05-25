<?php

namespace App\Domain\Reader\Entity;

readonly class VehicleSaleAndPurchaseAgreement implements Agreement
{
    public function __construct(
        private \DateTimeInterface $date,
        private Vehicle $vehicle,
        private array $sellers = [],
        private array $buyers = [],
        private float $price = 0.0
    ) {
    }

    public function price(): float
    {
        return round($this->price, 2);
    }

    public function vehicle(): Vehicle
    {
        return $this->vehicle;
    }

    public function date(): \DateTimeInterface
    {
        return $this->date;
    }

    /** @return Person[] */
    public function sellers(): array
    {
        return $this->sellers;
    }

    public function parties(): array
    {
        return array_merge($this->sellers(), $this->buyers());
    }

    /** @return Person[] */
    public function buyers(): array
    {
        return $this->buyers;
    }
}