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

use App\Domain\Reader\Entity\Vehicle;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AutoconfigureTag('serializer.normalizer')]
class VehicleNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @param Vehicle $object */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return ['make' => $object->make(), 'model' => $object->model(), 'license_plate' => $object->licensePlate(), ];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Vehicle;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Vehicle::class => true, ];
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Vehicle
    {
        $make = $data['make'] ?? '';
        $modelo = $data['model'] ?? '';
        $licencePlate = $data['license_plate'] ?? '';

        return new Vehicle($make, $modelo, $licencePlate);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Vehicle::class;
    }
}
