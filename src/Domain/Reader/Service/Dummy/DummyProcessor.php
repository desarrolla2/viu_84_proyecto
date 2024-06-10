<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Reader\Service\Dummy;

use App\Domain\Reader\Entity\AgreementInterface;
use App\Domain\Reader\Entity\DummyAgreementInterface;
use App\Domain\Reader\Service\ProcessorInterface;
use App\Domain\Reader\ValueObject\Text;

class DummyProcessor implements ProcessorInterface
{
    public function execute(Text $text): AgreementInterface
    {
        return new DummyAgreementInterface();
    }

    public function score(Text $text): int
    {
        return 1;
    }
}
