<?php

namespace App\Infrastructure\Reader\Service\ChatGPT\Entity\Serializer\Normalizer;

use App\Domain\Reader\Entity\Address;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AutoconfigureTag('serializer.normalizer')]
class AddressNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /** @param Address $object */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        return ['address' => $object->address(), 'city' => $object->city(), 'country' => $object->country(), 'postal_code' => $object->postalCode()];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Address;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [Address::class => true,];
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): Address
    {
        $address = $data['address'] ?? '';
        $city = $data['city'] ?? '';
        $country = $data['country'] ?? '';
        $postalCode = $data['postal_code'] ?? '';

        return new Address($address, $city, $country, $postalCode);
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Address::class;
    }
}
