<?php

namespace App\Domain\Generator\Service\Dummy;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\Service\PreProcessorInterface;

class DummyPreProcessor implements PreProcessorInterface
{
    public static function order()
    {
        return 0;
    }

    public function execute(Document $document): void
    {
        return;
    }
}
