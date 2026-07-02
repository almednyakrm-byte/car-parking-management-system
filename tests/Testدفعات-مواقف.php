<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\DefaateMoqafController;
use App\Repository\DefaateMoqafRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestDefaateMoqaf extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(DefaateMoqafRepository::class);
        $this->controller = new DefaateMoqafController($this->repository);
    }

    public function testGetAllDefaateMoqaf()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Defaate Moqaf 1'],
                ['id' => 2, 'name' => 'Defaate Moqaf 2'],
            ]);

        $request = new Request();
        $response = $this->controller->getAllDefaateMoqaf($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateDefaateMoqaf()
    {
        $this->repository->expects($this->once())
            ->method('create')
            ->with(['name' => 'Defaate Moqaf 3']);

        $request = new Request([], [], ['name' => 'Defaate Moqaf 3']);
        $response = $this->controller->createDefaateMoqaf($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateDefaateMoqaf()
    {
        $this->repository->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'Defaate Moqaf 1 Updated']);

        $request = new Request([], [], ['name' => 'Defaate Moqaf 1 Updated']);
        $response = $this->controller->updateDefaateMoqaf(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteDefaateMoqaf()
    {
        $this->repository->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->controller->deleteDefaateMoqaf(1, $request);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// DefaateMoqafController.php
namespace App\Controller;

use App\Repository\DefaateMoqafRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaateMoqafController
{
    private $repository;

    public function __construct(DefaateMoqafRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllDefaateMoqaf(Request $request)
    {
        return new Response(json_encode($this->repository->findAll()), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function createDefaateMoqaf(Request $request)
    {
        $this->repository->create($request->request->all());
        return new Response('', Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function updateDefaateMoqaf(int $id, Request $request)
    {
        $this->repository->update($id, $request->request->all());
        return new Response('', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function deleteDefaateMoqaf(int $id, Request $request)
    {
        $this->repository->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}