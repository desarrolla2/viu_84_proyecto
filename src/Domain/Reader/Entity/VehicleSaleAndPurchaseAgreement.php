<?php

namespace App\Domain\Reader\Entity;

class VehicleSaleAndPurchaseAgreement extends Agreement
{
    private array $sellers = [];
    private array $buyers = [];

    public function addSeller(Person $person): void
    {
        $this->sellers[$person->number()] = $person;
    }

    public function addBuyer(Person $person): void
    {
        $this->buyers[$person->number()] = $person;
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

    public function toArray(): array
    {
        return array_merge(['date' => $this->date()?->format('d/m/Y')], [
            'sellers' => array_reduce($this->sellers(), function (array $carry, Person $person): array {
                $carry[] = $person->toArray();

                return $carry;
            }, []),
            'buyers' => array_reduce($this->buyers(), function (array $carry, Person $person): array {
                $carry[] = $person->toArray();

                return $carry;
            }, []),
        ]);
    }
}