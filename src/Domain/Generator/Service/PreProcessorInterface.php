<?php

namespace App\Domain\Generator\Service;

use App\Domain\Generator\Entity\Document;

interface PreProcessorInterface
{
    public static function order();

    public function execute(Document $document): void;
}
