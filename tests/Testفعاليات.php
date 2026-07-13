<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\FaaliyetController;
use App\Repository\FaaliyetRepository;
use App\Entity\Faaliyet;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\MockObject\MockBuilder;

class TestFaaliyet extends TestCase
{
    private $faaliyetController;
    private $faaliyetRepository;
    private $router;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock('PDO');
        $this->faaliyetRepository = $this->createMock(FaaliyetRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->faaliyetController = new FaaliyetController($this->faaliyetRepository, $this->router);
    }

    public function testGetFaaliyetList()
    {
        $faaliyetList = [
            new Faaliyet('Faaliyet 1', 'Description 1'),
            new Faaliyet('Faaliyet 2', 'Description 2'),
        ];

        $this->faaliyetRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($faaliyetList);

        $request = new Request();
        $response = $this->faaliyetController->getFaaliyetList($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($faaliyetList), $response->getContent());
    }

    public function testCreateFaaliyet()
    {
        $faaliyet = new Faaliyet('Faaliyet 1', 'Description 1');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO faaliyet (name, description) VALUES (:name, :description)');
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $faaliyet->getName(), 'description' => $faaliyet->getDescription()]);

        $request = new Request([], [], ['name' => $faaliyet->getName(), 'description' => $faaliyet->getDescription()]);
        $response = $this->faaliyetController->createFaaliyet($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateFaaliyet()
    {
        $faaliyet = new Faaliyet('Faaliyet 1', 'Description 1');

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE faaliyet SET name = :name, description = :description WHERE id = :id');
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $faaliyet->getName(), 'description' => $faaliyet->getDescription(), 'id' => 1]);

        $request = new Request([], [], ['name' => $faaliyet->getName(), 'description' => $faaliyet->getDescription()]);
        $response = $this->faaliyetController->updateFaaliyet(1, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteFaaliyet()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM faaliyet WHERE id = :id');
        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => 1]);

        $request = new Request();
        $response = $this->faaliyetController->deleteFaaliyet(1, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file includes tests for the following CRUD operations:

- `testGetFaaliyetList`: Tests the GET request to retrieve a list of faaliyet.
- `testCreateFaaliyet`: Tests the POST request to create a new faaliyet.
- `testUpdateFaaliyet`: Tests the PUT request to update an existing faaliyet.
- `testDeleteFaaliyet`: Tests the DELETE request to delete a faaliyet.

Each test method creates a mock object for the `FaaliyetRepository` and `RouterInterface` classes, and uses the `createMock` method to create a mock object for the `PDO` class. The test methods then use the `expects` method to specify the expected behavior of the mock objects, and the `willReturn` method to specify the expected return value of the mock objects.

The test methods also create a `Request` object and pass it to the `getFaaliyetList`, `createFaaliyet`, `updateFaaliyet`, and `deleteFaaliyet` methods of the `FaaliyetController` class. The test methods then assert that the response is an instance of `JsonResponse` and that the status code is as expected.

Note that this is just an example and you may need to modify the test methods to fit your specific use case.