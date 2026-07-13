<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CourseRepository;
use App\Service\CourseService;
use PHPUnit\Framework\MockObject\MockObject;

class Testدورات extends TestCase
{
    private $courseRepository;
    private $courseService;

    protected function setUp(): void
    {
        $this->courseRepository = $this->createMock(CourseRepository::class);
        $this->courseService = new CourseService($this->courseRepository);
    }

    public function testGetCourses()
    {
        $courses = [
            ['id' => 1, 'name' => 'Course 1'],
            ['id' => 2, 'name' => 'Course 2'],
        ];

        $this->courseRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($courses);

        $response = $this->courseService->getCourses();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($courses), $response->getContent());
    }

    public function testGetCourseById()
    {
        $course = ['id' => 1, 'name' => 'Course 1'];

        $this->courseRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $response = $this->courseService->getCourseById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testGetCourseByIdNotFound()
    {
        $this->courseRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->courseService->getCourseById(1);
    }

    public function testCreateCourse()
    {
        $course = ['id' => 1, 'name' => 'Course 1'];

        $this->courseRepository->expects($this->once())
            ->method('save')
            ->with($course)
            ->willReturn($course);

        $request = new Request();
        $request->request->set('name', 'Course 1');

        $response = $this->courseService->createCourse($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testUpdateCourse()
    {
        $course = ['id' => 1, 'name' => 'Course 1'];

        $this->courseRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $this->courseRepository->expects($this->once())
            ->method('save')
            ->with($course)
            ->willReturn($course);

        $request = new Request();
        $request->request->set('name', 'Course 1');

        $response = $this->courseService->updateCourse(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($course), $response->getContent());
    }

    public function testUpdateCourseNotFound()
    {
        $this->courseRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $request = new Request();
        $request->request->set('name', 'Course 1');

        $this->courseService->updateCourse(1, $request);
    }

    public function testDeleteCourse()
    {
        $course = ['id' => 1, 'name' => 'Course 1'];

        $this->courseRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($course);

        $this->courseRepository->expects($this->once())
            ->method('remove')
            ->with($course);

        $response = $this->courseService->deleteCourse(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteCourseNotFound()
    {
        $this->courseRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->courseService->deleteCourse(1);
    }
}