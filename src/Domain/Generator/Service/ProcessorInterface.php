<?php

namespace App\Domain\Generator\Service;

use App\Domain\Generator\Entity\Document;
use App\Domain\Generator\ValueObject\Text;

interface ProcessorInterface
{
    public function execute(Document $document): Text;

    public function score(Document $document): int;
}
