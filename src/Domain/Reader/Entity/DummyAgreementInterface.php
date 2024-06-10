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

use DateTime;
use DateTimeInterface;

class DummyAgreementInterface implements AgreementInterface
{
    public function parties(): array
    {
        return [];
    }

    public function date(): DateTimeInterface
    {
        return new DateTime();
    }
}
