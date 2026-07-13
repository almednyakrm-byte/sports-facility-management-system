<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\أعضاءController;
use App\Repository\أعضاءRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginatorInterface;

class Testأعضاء extends TestCase
{
    private $controller;
    private $repository;
    private $paginator;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(أعضاءRepository::class);
        $this->paginator = $this->createMock(PaginatorInterface::class);
        $this->controller = new أعضاءController($this->repository, $this->paginator);
    }

    public function testGetAll()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Member 1'],
                ['id' => 2, 'name' => 'Member 2'],
            ]);

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetOne()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'Member 1']);

        $request = new Request();
        $response = $this->controller->getOne($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testGetOneNotFound()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getOne($request, 1);
    }

    public function testCreate()
    {
        $this->repository->expects($this->once())
            ->method('create')
            ->with(['name' => 'Member 1']);

        $request = new Request([], [], ['name' => 'Member 1']);
        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdate()
    {
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Member 1']);

        $request = new Request([], [], ['name' => 'Member 1']);
        $response = $this->controller->update($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $this->assertJson($response->getContent());
    }

    public function testUpdateNotFound()
    {
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Member 1'])
            ->willThrowException(new NotFoundHttpException());

        $request = new Request([], [], ['name' => 'Member 1']);
        $this->expectException(NotFoundHttpException::class);
        $this->controller->update($request, 1);
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

    public function testDeleteNotFound()
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willThrowException(new NotFoundHttpException());

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->delete($request, 1);
    }
}


This test file covers the following scenarios:

*   `testGetAll`: Tests the `getAll` method to ensure it returns a successful response with a list of members.
*   `testGetOne`: Tests the `getOne` method to ensure it returns a successful response with a single member.
*   `testGetOneNotFound`: Tests the `getOne` method to ensure it throws a `NotFoundHttpException` when the member is not found.
*   `testCreate`: Tests the `create` method to ensure it creates a new member and returns a successful response.
*   `testUpdate`: Tests the `update` method to ensure it updates an existing member and returns a successful response.
*   `testUpdateNotFound`: Tests the `update` method to ensure it throws a `NotFoundHttpException` when the member is not found.
*   `testDelete`: Tests the `delete` method to ensure it deletes a member and returns a successful response.
*   `testDeleteNotFound`: Tests the `delete` method to ensure it throws a `NotFoundHttpException` when the member is not found.