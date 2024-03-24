<?php

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
