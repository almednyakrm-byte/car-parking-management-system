<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TtabeController;
use App\Repository\TtabeRepository;
use App\Entity\Ttabe;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class TtabeTest extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TtabeRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new TtabeController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => [new Ttabe()]];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Ttabe()]);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetById(): void
    {
        $expectedResponse = ['data' => new Ttabe()];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Ttabe());

        $response = $this->controller->getById(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetByIdNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getById(1);
    }

    public function testCreate(): void
    {
        $ttabe = new Ttabe();
        $ttabe->setId(1);
        $ttabe->setName('ttabe');

        $expectedResponse = ['data' => $ttabe];

        $this->repository->expects($this->once())
            ->method('save')
            ->with($ttabe)
            ->willReturn($ttabe);

        $request = new Request([], [], ['ttabe' => $ttabe]);
        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $ttabe = new Ttabe();
        $ttabe->setId(1);
        $ttabe->setName('ttabe');

        $expectedResponse = ['data' => $ttabe];

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($ttabe);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($ttabe)
            ->willReturn($ttabe);

        $request = new Request([], [], ['ttabe' => $ttabe]);
        $response = $this->controller->update(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $ttabe = new Ttabe();
        $ttabe->setId(1);
        $ttabe->setName('ttabe');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], ['ttabe' => $ttabe]);
        $this->controller->update(1, $request);
    }

    public function testDelete(): void
    {
        $ttabe = new Ttabe();
        $ttabe->setId(1);
        $ttabe->setName('ttabe');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($ttabe);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($ttabe);

        $response = $this->controller->delete(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $ttabe = new Ttabe();
        $ttabe->setId(1);
        $ttabe->setName('ttabe');

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->delete(1);
    }
}