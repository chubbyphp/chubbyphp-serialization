<?php

declare(strict_types=1);

namespace Chubbyphp\Serialization\Normalizer;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Normalizer implements NormalizerInterface
{
    /**
     * @var NormalizerObjectMappingRegistryInterface
     */
    private $normalizerObjectMappingRegistry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param NormalizerObjectMappingRegistryInterface $normalizerObjectMappingRegistry
     * @param LoggerInterface|null                     $logger
     */
    public function __construct(
        NormalizerObjectMappingRegistryInterface $normalizerObjectMappingRegistry,
        LoggerInterface $logger = null
    ) {
        $this->normalizerObjectMappingRegistry = $normalizerObjectMappingRegistry;
        $this->logger = $logger ?? new NullLogger();
    }

    public function normalize($object, NormalizerContextInterface $context = null, string $path = ''): array
    {
        return [];
    }
}
