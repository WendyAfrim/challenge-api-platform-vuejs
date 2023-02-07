<?php

namespace App\Serializer;

use App\Entity\Document;
use App\Entity\MediaObject;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

class DocumentNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    private const ALREADY_CALLED = 'DOCUMENT_NORMALIZER_ALREADY_CALLED';

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

        return $data instanceof Document;
    }

    /**
     * @inheritDoc
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $context[self::ALREADY_CALLED] = true;

        $object->fileId = "/media_objects/".$object->getId();
        $object->contentUrl = $this->usersStorage->temporaryUrl( $this->storage->resolvePath($object, 'file'), (new \DateTime('now'))->modify('+1 hour'));

        return $this->normalizer->normalize($object, $format, $context);
    }
}