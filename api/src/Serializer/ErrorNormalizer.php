<?php

namespace App\Serializer;

use ApiPlatform\Api\UrlGeneratorInterface;
use ApiPlatform\Problem\Serializer\ErrorNormalizerTrait;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ErrorNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    use ErrorNormalizerTrait;

    public const FORMAT = 'jsonld';
    public const TITLE = 'title';
    private array $defaultContext = [self::TITLE => 'An error occurred'];

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator, private readonly bool $debug = false, array $defaultContext = [])
    {
        $this->defaultContext = array_merge($this->defaultContext, $defaultContext);
    }

    /**
     * @inheritDoc
     */
    public function normalize(mixed $object, string $format = null, array $context = []): float|int|bool|\ArrayObject|array|string|null
    {
        $data = [
            '@context' => $this->urlGenerator->generate('api_jsonld_context', ['shortName' => 'Error']),
            '@type' => 'hydra:Error',
            'hydra:title' => $context[self::TITLE] ?? $this->defaultContext[self::TITLE],
            'hydra:description' => $this->getErrorMessage($object, $context, $this->debug),
        ];

        if ($this->debug && null !== $trace = $object->getTrace()) {
            $data['trace'] = $trace;
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return self::FORMAT === $format && ($data instanceof \Exception || $data instanceof FlattenException);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}