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

use App\Domain\Reader\Entity\Address;
use App\Domain\Reader\Entity\Property;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AutoconfigureTag('serializer.normalizer')]
readonly class PropertyNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct(private AddressNormalizer $addressNormalizer)
    {
    }

    /** @param Property $object */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return ['address' => $this->addressNormalizer->normalize($object->address())];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Property;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Property::class => true, ];
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Property
    {
        $address = $data['address'] ? $this->addressNormalizer->denormalize($data['address'], Address::class) : new Address('', '', '', '');

        return new Property($address);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Property::class;
    }
}
