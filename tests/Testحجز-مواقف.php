<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\حجز مواقفController;
use App\Repository\حجز مواقفRepository;
use App\Entity\حجز مواقف;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testحجز مواقف extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock('App\Repository\حجز مواقفRepository');
        $this->controller = new حجز مواقفController($this->repository);

        $this->repository->method('findAll')->willReturn([]);
        $this->repository->method('find')->willReturn(null);
        $this->repository->method('findOneBy')->willReturn(null);
        $this->repository->method('save')->willReturn(new حجز مواقف());
        $this->repository->method('remove')->willReturn(null);
    }

    public function testGetAll()
    {
        $request = new Request();
        $request->setMethod('GET');

        $response = $this->controller->getAll($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOne()
    {
        $request = new Request();
        $request->setMethod('GET');

        $this->repository->method('find')->willReturn(new حجز مواقف());

        $response = $this->controller->getOne($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOneNotFound()
    {
        $request = new Request();
        $request->setMethod('GET');

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getOne($request, 1);
    }

    public function testCreate()
    {
        $request = new Request();
        $request->setMethod('POST');
        $request->request->set('name', 'test');

        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate()
    {
        $request = new Request();
        $request->setMethod('PUT');
        $request->request->set('name', 'test');

        $this->repository->method('find')->willReturn(new حجز مواقف());

        $response = $this->controller->update($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateNotFound()
    {
        $request = new Request();
        $request->setMethod('PUT');

        $this->expectException(NotFoundHttpException::class);

        $this->controller->update($request, 1);
    }

    public function testDelete()
    {
        $request = new Request();
        $request->setMethod('DELETE');

        $this->repository->method('find')->willReturn(new حجز مواقف());

        $response = $this->controller->delete($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound()
    {
        $request = new Request();
        $request->setMethod('DELETE');

        $this->expectException(NotFoundHttpException::class);

        $this->controller->delete($request, 1);
    }
}