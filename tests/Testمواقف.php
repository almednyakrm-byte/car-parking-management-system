<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مواقفController;
use App\Repository\مواقفRepository;
use App\Entity\مواقف;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testمواقف extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MواقفRepository::class);
        $this->controller = new مواقفController($this->repository);
    }

    public function testGetAll(): void
    {
        $expectedResponse = [
            ['id' => 1, 'name' => 'مواقف 1'],
            ['id' => 2, 'name' => 'مواقف 2'],
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne(): void
    {
        $expectedResponse = ['id' => 1, 'name' => 'مواقف 1'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);

        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOneNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getOne(1);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'مواقف 3'];
        $expectedResponse = ['id' => 3, 'name' => 'مواقف 3'];

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Mواقف::class))
            ->willReturn($expectedResponse);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->create($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $data = ['name' => 'مواقف 1'];
        $expectedResponse = ['id' => 1, 'name' => 'مواقف 1'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($expectedResponse);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Mواقف::class))
            ->willReturn($expectedResponse);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->update(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $data = ['name' => 'مواقف 1'];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], [], [], [], json_encode($data));
        $this->controller->update(1, $request);
    }

    public function testDelete(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'مواقف 1']);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($this->isInstanceOf(Mواقف::class));

        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->delete(1);
    }
}


This test file covers the following scenarios:

*   `testGetAll`: Verifies that the `getAll` method returns a list of all `مواقف` entities.
*   `testGetOne`: Verifies that the `getOne` method returns a single `مواقف` entity by its ID.
*   `testGetOneNotFound`: Verifies that the `getOne` method throws a `NotFoundHttpException` when the entity is not found.
*   `testCreate`: Verifies that the `create` method creates a new `مواقف` entity and returns it with a `201 Created` status code.
*   `testUpdate`: Verifies that the `update` method updates an existing `مواقف` entity and returns it with a `200 OK` status code.
*   `testUpdateNotFound`: Verifies that the `update` method throws a `NotFoundHttpException` when the entity is not found.
*   `testDelete`: Verifies that the `delete` method deletes a `مواقف` entity by its ID.
*   `testDeleteNotFound`: Verifies that the `delete` method throws a `NotFoundHttpException` when the entity is not found.

Note that this test file assumes that the `مواقف` entity and repository are properly defined and configured in the application.