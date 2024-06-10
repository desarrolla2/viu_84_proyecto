<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Generator\Service\Dummy;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\Service\PreProcessorInterface;

class DummyPreProcessor implements PreProcessorInterface
{
    public static function order(): int
    {
        return 0;
    }

    public function execute(Document $document): void
    {
        return;
    }
}
