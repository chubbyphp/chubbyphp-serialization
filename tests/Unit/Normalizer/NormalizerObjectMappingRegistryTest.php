<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\MockMethod\WithReturn;
use Chubbyphp\Mock\MockObjectBuilder;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\SerializerLogicException;
use Chubbyphp\Tests\Serialization\Resources\Model\AbstractManyModel;
use Doctrine\Persistence\Proxy;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry
 *
 * @internal
 */
final class NormalizerObjectMappingRegistryTest extends TestCase
{
    public function testGetObjectMapping(): void
    {
        $object = $this->getObject();

        $registry = new NormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]);

        $mapping = $registry->getObjectMapping($object::class);

        self::assertInstanceOf(NormalizationObjectMappingInterface::class, $mapping);
    }

    public function testGetMissingObjectMapping(): void
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no mapping for class: "stdClass"');

        $registry = new NormalizerObjectMappingRegistry([]);

        $registry->getObjectMapping((new \stdClass())::class);
    }

    public function testGetObjectMappingFromDoctrineProxy(): void
    {
        $object = $this->getProxyObject();

        $builder = new MockObjectBuilder();

        /** @var MockObject|NormalizationObjectMappingInterface $objectMapping */
        $objectMapping = $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getClass', [], AbstractManyModel::class),
        ]);

        $registry = new NormalizerObjectMappingRegistry([$objectMapping]);

        $mapping = $registry->getObjectMapping($object::class);

        self::assertInstanceOf(NormalizationObjectMappingInterface::class, $mapping);
    }

    private function getNormalizationObjectMapping(): NormalizationObjectMappingInterface
    {
        $object = $this->getObject();

        $builder = new MockObjectBuilder();

        // @var NormalizationObjectMappingInterface|MockObject $objectMapping
        return $builder->create(NormalizationObjectMappingInterface::class, [
            new WithReturn('getClass', [], $object::class),
        ]);
    }

    private function getObject(): object
    {
        return new class {
            private ?string $name = null;

            public function getName(): ?string
            {
                return $this->name;
            }

            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }
        };
    }

    private function getProxyObject(): object
    {
        return new class extends AbstractManyModel implements Proxy {
            private bool $initialized = false;

            public function __load(): void
            {
                $this->initialized = true;
            }

            public function __isInitialized(): bool
            {
                return $this->initialized;
            }
        };
    }
}
