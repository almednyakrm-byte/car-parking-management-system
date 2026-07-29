<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\حجزController;
use App\Repository\حجزRepository;
use App\Entity\حجز;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testحجز extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(حجزRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new حجزController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testGetById(): void
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new حجز());

        $response = $this->controller->getById($id);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testGetByIdNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->getById($id);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'test'];
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(new حجز());

        $response = $this->controller->create($data);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'test'];
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(new حجز());

        $response = $this->controller->update($id, $data);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['name' => 'test'];
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(null);

        $this->controller->update($id, $data);
    }

    public function testDelete(): void
    {
        $id = 1;
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(true);

        $response = $this->controller->delete($id);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(false);

        $this->controller->delete($id);
    }
}



// حجزController.php
namespace App\Controller;

use App\Repository\حجزRepository;
use App\Entity\حجز;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class حجزController
{
    private $repository;
    private $entityManager;

    public function __construct(حجزRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getAll(): Response
    {
        $data = $this->repository->findAll();
        return new Response(json_encode(['data' => $data]));
    }

    public function getById(int $id): Response
    {
        $data = $this->repository->find($id);
        if (!$data) {
            throw new NotFoundHttpException('حجز not found');
        }
        return new Response(json_encode(['data' => $data]));
    }

    public function create(array $data): Response
    {
        $entity = new حجز();
        $this->repository->create($data, $entity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return new Response(json_encode(['data' => $entity]));
    }

    public function update(int $id, array $data): Response
    {
        $entity = $this->repository->find($id);
        if (!$entity) {
            throw new NotFoundHttpException('حجز not found');
        }
        $this->repository->update($id, $data, $entity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return new Response(json_encode(['data' => $entity]));
    }

    public function delete(int $id): Response
    {
        $deleted = $this->repository->delete($id);
        if (!$deleted) {
            throw new NotFoundHttpException('حجز not found');
        }
        return new Response(json_encode(['data' => []]));
    }
}