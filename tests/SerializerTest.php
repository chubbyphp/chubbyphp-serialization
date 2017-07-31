<?php

declare(strict_types=1);

namespace Chubbyphp\Tests\Serialization;

use Chubbyphp\Serialization\Registry\ObjectMappingRegistry;
use Chubbyphp\Serialization\Serializer;
use Chubbyphp\Tests\Serialization\Resources\Model;
use Chubbyphp\Tests\Serialization\Resources\ModelMapping;
use Psr\Http\Message\ServerRequestInterface as Request;

class SerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerialize()
    {
        $registry = new ObjectMappingRegistry([
            new ModelMapping(),
        ]);

        $serializer = new Serializer($registry);

        $request = $this->getRequest();

        $model = new Model('id1');
        $model->setName('name1');

        $data = $serializer->serialize($request, $model);

        self::assertEquals([
            'name' => 'name1',
            '_embedded' => [
                'name' => 'name1',
            ],
            '_links' => [
                'name:read' => [
                    'href' => 'http://test.com/models/id1',
                    'method' => 'get',
                ],
                'name:update' => [
                    'href' => 'http://test.com/models/id1',
                    'method' => 'put',
                ],
                'name:delete' => [
                    'href' => 'http://test.com/models/id1',
                    'method' => 'delete',
                ],
            ],
        ], $data);
    }

    /**
     * @return Request
     */
    private function getRequest(): Request
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(Request::class)->setMethods([])->getMockForAbstractClass();

        return $request;
    }
}
