<?php

namespace App\Domain\Reader\Entity;

class ResidentialLeaseAgreement extends Agreement
{
    private array $landLords = [];
    private array $tenants = [];

    public function addLandLord(Person $person): void
    {
        $this->landLords[$person->number()] = $person;
    }

    public function addTenant(Person $person): void
    {
        $this->tenants[$person->number()] = $person;
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

    public function toArray(): array
    {
        return array_merge(['date' => $this->date()?->format('d/m/Y')], [
            'land_lords' => array_reduce($this->landLords(), function (array $carry, Person $person): array {
                $carry[] = $person->toArray();

                return $carry;
            }, []),
            'tenants' => array_reduce($this->landLords(), function (array $carry, Person $person): array {
                $carry[] = $person->toArray();

                return $carry;
            }, []),
        ]);
    }
}
