<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Reader\Service;

use App\Domain\Reader\Entity\Agreement;
use App\Domain\Reader\ValueObject\Text;

interface ProcessorInterface
{
    public function execute(Text $text): ?Agreement;

    public function score(Text $text): int;
}
