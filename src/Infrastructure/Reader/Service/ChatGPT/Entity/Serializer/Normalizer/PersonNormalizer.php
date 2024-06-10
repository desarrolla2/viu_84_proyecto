<?php
/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Infrastructure\Reader\Service\ChatGPT\Entity\Serializer\Normalizer;

use App\Domain\Reader\Entity\Person;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AutoconfigureTag('serializer.normalizer')]
class PersonNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @param Person $object */
    /** @param string[] $context */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return ['name' => $object->name(), 'surname' => $object->surname(), 'number' => $object->number()];
    }

    /** @param string[] $context */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Person;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Person::class => true,];
    }

    /** @param string[] $context */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Person
    {
        $name = $data['name'] ?? '';
        $surname = $data['surname'] ?? '';
        $number = $data['number'] ?? '';

        return new Person($name, $surname, $number);
    }

    /** @param string[] $context */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Person::class;
    }
}
