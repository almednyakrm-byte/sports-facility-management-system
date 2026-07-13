<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
    }

    public function testGetStudents()
    {
        $students = [
            new طلاب('1', 'John Doe', 'john@example.com'),
            new طلاب('2', 'Jane Doe', 'jane@example.com'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($students);

        $response = $this->controller->getStudents();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($students), $response->getContent());
    }

    public function testGetStudent()
    {
        $student = new طلاب('1', 'John Doe', 'john@example.com');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($student);

        $response = $this->controller->getStudent('1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testGetStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->getStudent('1');
    }

    public function testCreateStudent()
    {
        $student = new طلاب('1', 'John Doe', 'john@example.com');

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('INSERT INTO students (id, name, email) VALUES (:id, :name, :email)')
            ->willReturn(1);

        $request = new Request();
        $request->request->set('id', '1');
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->controller->createStudent($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateStudent()
    {
        $student = new طلاب('1', 'John Doe', 'john@example.com');

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('UPDATE students SET name = :name, email = :email WHERE id = :id')
            ->willReturn(1);

        $request = new Request();
        $request->request->set('id', '1');
        $request->request->set('name', 'John Doe Updated');
        $request->request->set('email', 'john.updated@example.com');

        $response = $this->controller->updateStudent('1', $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteStudent()
    {
        $this->pdo->expects($this->once())
            ->method('exec')
            ->with('DELETE FROM students WHERE id = :id')
            ->willReturn(1);

        $response = $this->controller->deleteStudent('1');

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}


Note: This code assumes that the `طلابController` class has methods for each CRUD operation, and that the `طلابRepository` class has methods for interacting with the database. The `طلاب` entity class is also assumed to have properties for the student's ID, name, and email.