<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Unit\Normalizer;

use Chubbyphp\Mock\Call;
use Chubbyphp\Mock\MockByCallsTrait;
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
    use MockByCallsTrait;

    public function testGetObjectMapping(): void
    {
        $object = $this->getObject();

        $registry = new NormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]);

        $mapping = $registry->getObjectMapping(\get_class($object));

        self::assertInstanceOf(NormalizationObjectMappingInterface::class, $mapping);
    }

    public function testGetMissingObjectMapping(): void
    {
        $this->expectException(SerializerLogicException::class);
        $this->expectExceptionMessage('There is no mapping for class: "stdClass"');

        $registry = new NormalizerObjectMappingRegistry([]);

        $registry->getObjectMapping(\get_class(new \stdClass()));
    }

    public function testGetObjectMappingFromDoctrineProxy(): void
    {
        $object = $this->getProxyObject();

        /** @var MockObject|NormalizationObjectMappingInterface $objectMapping */
        $objectMapping = $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getClass')->with()->willReturn(AbstractManyModel::class),
        ]);

        $registry = new NormalizerObjectMappingRegistry([$objectMapping]);

        $mapping = $registry->getObjectMapping(\get_class($object));

        self::assertInstanceOf(NormalizationObjectMappingInterface::class, $mapping);
    }

    private function getNormalizationObjectMapping(): NormalizationObjectMappingInterface
    {
        $object = $this->getObject();

        // @var NormalizationObjectMappingInterface|MockObject $objectMapping
        return $this->getMockByCalls(NormalizationObjectMappingInterface::class, [
            Call::create('getClass')->with()->willReturn(\get_class($object)),
        ]);
    }

    private function getObject(): object
    {
        return new class() {
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
        return new class() extends AbstractManyModel implements Proxy {
            /**
             * Initializes this proxy if its not yet initialized.
             *
             * Acts as a no-op if already initialized.
             */
            public function __load(): void
            {
            }

            /**
             * Returns whether this proxy is initialized or not.
             */
            public function __isInitialized(): bool
            {
            }
        };
    }
}
