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

readonly class ResidentialLeaseAgreement implements AgreementInterface
{
    public function __construct(
        private DateTimeInterface $date,
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

    public function date(): DateTimeInterface
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
