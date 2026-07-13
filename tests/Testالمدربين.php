<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مدربينController;
use App\Repository\مدربينRepository;
use App\Entity\مدربين;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testالمدربين extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(مدربينRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new مدربينController($this->repository, $this->entityManager);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $expectedResponse = ['data' => new مدربين()];
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new مدربين());
        $response = $this->controller->getOne($id);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOneNotFound()
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);
        $this->controller->getOne($id);
    }

    public function testCreate()
    {
        $expectedResponse = ['data' => new مدربين()];
        $data = ['name' => 'Test'];
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->repository->expects($this->once())
                ->method('create')
                ->with($data)
                ->willReturn(new مدربين()));
        $this->entityManager->expects($this->once())
            ->method('flush');
        $response = $this->controller->create($data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdate()
    {
        $expectedResponse = ['data' => new مدربين()];
        $id = 1;
        $data = ['name' => 'Test'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new مدربين());
        $this->repository->expects($this->once())
            ->method('update')
            ->with($data)
            ->willReturn(new مدربين());
        $this->entityManager->expects($this->once())
            ->method('flush');
        $response = $this->controller->update($id, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testUpdateNotFound()
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);
        $this->controller->update($id, $data);
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new مدربين());
        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->repository->expects($this->once())
                ->method('find')
                ->with($id)
                ->willReturn(new مدربين()));
        $this->entityManager->expects($this->once())
            ->method('flush');
        $response = $this->controller->delete($id);
        $this->assertEquals(['message' => 'Deleted successfully'], $response);
    }

    public function testDeleteNotFound()
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);
        $this->controller->delete($id);
    }
}



// App\Controller\مدربينController.php
namespace App\Controller;

use App\Repository\مدربينRepository;
use App\Entity\مدربين;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class مدربينController
{
    private $repository;
    private $entityManager;

    public function __construct(مدربينRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        return ['data' => $this->repository->findAll()];
    }

    public function getOne($id)
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Entity not found');
        }
        return ['data' => $entity];
    }

    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $entity = $this->repository->create($data);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return ['data' => $entity];
    }

    public function update($id, Request $request)
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Entity not found');
        }
        $data = json_decode($request->getContent(), true);
        $entity = $this->repository->update($data);
        $this->entityManager->flush();
        return ['data' => $entity];
    }

    public function delete($id)
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('Entity not found');
        }
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
        return ['message' => 'Deleted successfully'];
    }
}