<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\حجز_مواعيدRepository;
use App\Entity\حجز_مواعيد;
use App\Service\حجز_مواعيدService;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testحجز_مواعيد extends TestCase
{
    private $router;
    private $repository;
    private $service;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->repository = $this->createMock(حجز_مواعيدRepository::class);
        $this->service = $this->createMock(حجز_مواعيدService::class);
    }

    public function testGetAll()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new حجز_مواعيد(),
                new حجز_مواعيد(),
            ]);

        $this->service->expects($this->once())
            ->method('getAll')
            ->willReturn($this->repository->findAll());

        $request = new Request();
        $response = $this->service->getAll($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetById()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new حجز_مواعيد());

        $this->service->expects($this->once())
            ->method('getById')
            ->with($id)
            ->willReturn($this->repository->find($id));

        $request = new Request();
        $response = $this->service->getById($request, ['id' => $id]);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreate()
    {
        $data = ['name' => 'test', 'email' => 'test@example.com'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new حجز_مواعيد($data))
            ->willReturn(new حجز_مواعيد($data));

        $this->service->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($this->repository->save(new حجز_مواعيد($data)));

        $request = new Request([], [], ['json' => $data]);
        $response = $this->service->create($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'test', 'email' => 'test@example.com'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new حجز_مواعيد($data));

        $this->service->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($this->repository->find($id));

        $request = new Request([], [], ['json' => $data]);
        $response = $this->service->update($request, ['id' => $id]);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new حجز_مواعيد());

        $this->service->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn($this->repository->find($id));

        $request = new Request();
        $response = $this->service->delete($request, ['id' => $id]);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}


Note: This is a basic example and you may need to adjust it according to your specific requirements and the implementation of your service and repository classes.