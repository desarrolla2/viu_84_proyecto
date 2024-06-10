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

readonly class Person
{
    public function __construct(private string $name, private string $surname, private string $number)
    {
    }

    public function surname(): string
    {
        return $this->surname;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function number(): string
    {
        return $this->number;
    }
}
