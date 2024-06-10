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

readonly class Vehicle
{
    public function __construct(private string $make, private string $model, private string $licensePlate)
    {
    }

    public function make(): string
    {
        return $this->make;
    }

    public function model(): string
    {
        return $this->model;
    }

    public function licensePlate(): string
    {
        return $this->licensePlate;
    }
}
