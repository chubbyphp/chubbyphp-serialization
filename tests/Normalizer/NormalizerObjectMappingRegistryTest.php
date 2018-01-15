<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization\Normalizer;

use Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry;
use Chubbyphp\Serialization\SerializerLogicException;
use Chubbyphp\Serialization\Mapping\NormalizationObjectMappingInterface;
use Chubbyphp\Tests\Serialization\Resources\Model\AbstractManyModel;
use Doctrine\Common\Persistence\Proxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Chubbyphp\Serialization\Normalizer\NormalizerObjectMappingRegistry
 */
class NormalizerObjectMappingRegistryTest extends TestCase
{
    public function testGetObjectMapping()
    {
        $object = $this->getObject();

        $registry = new NormalizerObjectMappingRegistry([
            $this->getNormalizationObjectMapping(),
        ]);

        $mapping = $registry->getObjectMapping(get_class($object));

        self::assertInstanceOf(NormalizationObjectMappingInterface::class, $mapping);
    }

    public function testGetMissingObjectMapping()
    {
        self::expectException(SerializerLogicException::class);
        self::expectExceptionMessage('There is no mapping for class: "stdClass"');

        $registry = new NormalizerObjectMappingRegistry([]);

        $registry->getObjectMapping(get_class(new \stdClass()));
    }

    public function testGetObjectMappingFromDoctrineProxy()
    {
        $object = $this->getProxyObject();

        $registry = new NormalizerObjectMappingRegistry([
            $this->getNormalizationProxyObjectMapping(),
        ]);

        $mapping = $registry->getObjectMapping(get_class($object));

        self::assertInstanceOf(NormalizationObjectMappingInterface::class, $mapping);
    }

    /**
     * @return NormalizationObjectMappingInterface
     */
    private function getNormalizationObjectMapping(): NormalizationObjectMappingInterface
    {
        /** @var NormalizationObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $objectMapping */
        $objectMapping = $this->getMockBuilder(NormalizationObjectMappingInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $object = $this->getObject();

        $objectMapping->expects(self::any())->method('getClass')->willReturnCallback(
            function () use ($object) {
                return get_class($object);
            }
        );

        return $objectMapping;
    }

    /**
     * @return NormalizationObjectMappingInterface
     */
    private function getNormalizationProxyObjectMapping(): NormalizationObjectMappingInterface
    {
        /** @var NormalizationObjectMappingInterface|\PHPUnit_Framework_MockObject_MockObject $objectMapping */
        $objectMapping = $this->getMockBuilder(NormalizationObjectMappingInterface::class)
            ->setMethods([])
            ->getMockForAbstractClass();

        $object = $this->getProxyObject();

        $objectMapping->expects(self::any())->method('getClass')->willReturnCallback(
            function () use ($object) {
                return AbstractManyModel::class;
            }
        );

        return $objectMapping;
    }

    /**
     * @return object
     */
    private function getObject()
    {
        return new class() {
            /**
             * @var string
             */
            private $name;

            /**
             * @return string|null
             */
            public function getName()
            {
                return $this->name;
            }

            /**
             * @param string $name
             *
             * @return self
             */
            public function setName(string $name): self
            {
                $this->name = $name;

                return $this;
            }
        };
    }

    /**
     * @return object
     */
    private function getProxyObject()
    {
        return new class() extends AbstractManyModel implements Proxy {
            /**
             * Initializes this proxy if its not yet initialized.
             *
             * Acts as a no-op if already initialized.
             */
            public function __load()
            {
            }

            /**
             * Returns whether this proxy is initialized or not.
             *
             * @return bool
             */
            public function __isInitialized()
            {
            }
        };
    }
}
