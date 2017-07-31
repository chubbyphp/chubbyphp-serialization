<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Tests\Serialization\Resources\Model;
use Chubbyphp\Tests\Serialization\Resources\ModelMapping;

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $registry = new ObjectMappingRegistry([
            new ModelMapping()
        ]);

        $serializer = new Serializer($registry);

        $model = new Model();
        $model->setName('name1');

        $data = $serializer->serialize($model);

        self::assertEquals([
            'name' => 'name1',
            '_embedded' => [
                'name' => 'name1',
            ]
        ], $data);
    }
}
