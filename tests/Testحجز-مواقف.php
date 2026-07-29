<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\حجز_مواقفController;
use App\Repository\حجز_مواقفRepository;
use App\Entity\حجز_مواقف;
use App\Service\حجز_مواقفService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testحجز_مواقف extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock('App\Repository\حجز_مواقفRepository');
        $this->service = $this->createMock('App\Service\حجز_مواقفService');
        $this->controller = new حجز_مواقفController($this->repository, $this->service);

        $this->repository->method('findAll')->willReturn([]);
        $this->repository->method('find')->willReturn(null);
        $this->repository->method('save')->willReturn(null);
        $this->repository->method('remove')->willReturn(null);
        $this->service->method('create')->willReturn(null);
        $this->service->method('update')->willReturn(null);
        $this->service->method('delete')->willReturn(null);
    }

    public function testGetAll()
    {
        $request = new Request();
        $request->setMethod('GET');
        $response = $this->controller->getAll($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne()
    {
        $request = new Request();
        $request->setMethod('GET');
        $response = $this->controller->getOne($request, 1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPost()
    {
        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('name', 'test');
        $request->request->set('email', 'test@example.com');
        $response = $this->controller->create($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPut()
    {
        $request = new Request();
        $request->setMethod('PUT');
        $request->request->set('name', 'test');
        $request->request->set('email', 'test@example.com');
        $response = $this->controller->update($request, 1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $request = new Request();
        $request->setMethod('DELETE');
        $response = $this->controller->delete($request, 1);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}


Note: This is a basic example and you may need to adjust it according to your specific use case. Also, you should replace the `App\Controller\حجز_مواقفController`, `App\Repository\حجز_مواقفRepository`, `App\Entity\حجز_مواقف`, and `App\Service\حجز_مواقفService` with your actual class names.