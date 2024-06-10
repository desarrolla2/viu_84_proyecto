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
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AutoconfigureTag('serializer.normalizer')]
class AddressNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @param Address $object */
    /** @param string[] $context */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return ['address' => $object->address(), 'city' => $object->city(), 'country' => $object->country(), 'postal_code' => $object->postalCode()];
    }

    /** @param string[] $context */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Address;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Address::class => true,];
    }

    /** @param string[] $context */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Address
    {
        $address = $data['address'] ?? '';
        $city = $data['city'] ?? '';
        $country = $data['country'] ?? '';
        $postalCode = $data['postal_code'] ?? '';

        return new Address($address, $city, $country, $postalCode);
    }

    /** @param string[] $context */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Address::class;
    }
}
