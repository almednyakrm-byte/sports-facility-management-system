<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\صالات_رياضيةController;
use App\Repository\صالات_رياضيةRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testصالات_رياضية extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(صالات_رياضيةRepository::class);
        $this->pdo = $this->createMock(\PDO::class);
        $this->controller = new صالات_رياضيةController($this->repository, $this->pdo);
    }

    public function testGetAll()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'صالة رياضية 1'],
                ['id' => 2, 'name' => 'صالة رياضية 2'],
            ]);

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetById()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'صالة رياضية 1']);

        $request = new Request();
        $response = $this->controller->getById($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreate()
    {
        $data = ['name' => 'صالة رياضية جديدة'];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(['id' => 1, 'name' => 'صالة رياضية جديدة']);

        $request = new Request([], [], ['name' => 'صالة رياضية جديدة']);
        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate()
    {
        $data = ['name' => 'صالة رياضية مُتحديثة'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, $data)
            ->willReturn(['id' => 1, 'name' => 'صالة رياضية مُتحديثة']);

        $request = new Request([], [], ['name' => 'صالة رياضية مُتحديثة']);
        $response = $this->controller->update($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDelete()
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->controller->delete($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetAll`: Tests the `getAll` method to ensure it returns a list of all `صالات_رياضية` entities.
- `testGetById`: Tests the `getById` method to ensure it returns a single `صالات_رياضية` entity by its ID.
- `testCreate`: Tests the `create` method to ensure it creates a new `صالات_رياضية` entity.
- `testUpdate`: Tests the `update` method to ensure it updates an existing `صالات_رياضية` entity.
- `testDelete`: Tests the `delete` method to ensure it deletes a `صالات_رياضية` entity by its ID.

Note that this test file assumes that the `صالات_رياضيةController` class has the following methods:

- `getAll(Request $request)`: Returns a list of all `صالات_رياضية` entities.
- `getById(Request $request, int $id)`: Returns a single `صالات_رياضية` entity by its ID.
- `create(Request $request)`: Creates a new `صالات_رياضية` entity.
- `update(Request $request, int $id)`: Updates an existing `صالات_رياضية` entity.
- `delete(Request $request, int $id)`: Deletes a `صالات_رياضية` entity by its ID.

Also, this test file assumes that the `صالات_رياضيةRepository` class has the following methods:

- `findAll()`: Returns a list of all `صالات_رياضية` entities.
- `find(int $id)`: Returns a single `صالات_رياضية` entity by its ID.
- `create(array $data)`: Creates a new `صالات_رياضية` entity.
- `update(int $id, array $data)`: Updates an existing `صالات_رياضية` entity.
- `delete(int $id)`: Deletes a `صالات_رياضية` entity by its ID.