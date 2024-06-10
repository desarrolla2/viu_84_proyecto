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
use App\Domain\Generator\Service\ProcessorInterface;
use App\Domain\Generator\ValueObject\Text;

class DummyProcessor implements ProcessorInterface
{
    public function execute(Document $document): Text
    {
        return new Text();
    }

    public function score(Document $document): int
    {
        return 0;
    }
}
