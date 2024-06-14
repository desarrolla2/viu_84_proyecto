<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Reader\Entity;

use DateTimeInterface;

readonly class VehicleSaleAndPurchaseAgreement implements AgreementInterface
{
    public function __construct(
        private DateTimeInterface $date,
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

    public function date(): DateTimeInterface
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
