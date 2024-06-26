<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Domain\Generator\Service;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\ValueObject\Text;

interface ProcessorInterface
{
    public function execute(Document $document): Text;

    public function score(Document $document): int;
}
