<?php

namespace App\Serializer;

use App\Entity\MediaObject;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;
class MediaObjectNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    private const ALREADY_CALLED = 'MEDIA_OBJECT_NORMALIZER_ALREADY_CALLED';

    public function __construct(private StorageInterface $storage, private FilesystemOperator $usersStorage)
    {
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }

        return $data instanceof MediaObject;
    }

    /**
     * @inheritDoc
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        $object->contentUrl = $this->storage->resolveUri($object, 'file');
        $object->filePath = $this->usersStorage->temporaryUrl( $this->storage->resolvePath($object, 'file'), (new \DateTime('now'))->modify('+1 hour'));

        return $this->normalizer->normalize($object, $format, $context);
    }

//    public function setNormalizer(NormalizerInterface $normalizer)
//    {
//        // TODO: Implement setNormalizer() method.
//    }
}