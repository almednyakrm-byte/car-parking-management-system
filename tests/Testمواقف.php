<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\مواقفController;
use App\Repository\مواقفRepository;
use App\Entity\مواقف;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;

class Testمواقف extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MواقفRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new مواقفController($this->repository, $this->entityManager);
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

        $response = $this->controller->getAll($this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testGetOne(): void
    {
        $expectedResponse = ['id' => 1, 'name' => 'مواقف 1'];

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn($expectedResponse);

        $this->request->method('get')
            ->with('id')
            ->willReturn(1);

        $response = $this->controller->getOne($this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testGetOneNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(null);

        $this->request->method('get')
            ->with('id')
            ->willReturn(1);

        $this->controller->getOne($this->request);
    }

    public function testCreate(): void
    {
        $expectedResponse = ['id' => 1, 'name' => 'مواقف 1'];

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Mواقف::class));

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->request->method('request')
            ->willReturn(['name' => 'مواقف 1']);

        $response = $this->controller->create($this->request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testCreateValidationFailed(): void
    {
        $this->expectException(QueryException::class);

        $this->request->method('request')
            ->willReturn(['name' => '']);

        $this->controller->create($this->request);
    }

    public function testUpdate(): void
    {
        $expectedResponse = ['id' => 1, 'name' => 'مواقف 1'];

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn($expectedResponse);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->request->method('get')
            ->with('id')
            ->willReturn(1);

        $this->request->method('request')
            ->willReturn(['name' => 'مواقف 1']);

        $response = $this->controller->update($this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($expectedResponse), $response->getContent());
    }

    public function testUpdateNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(null);

        $this->request->method('get')
            ->with('id')
            ->willReturn(1);

        $this->controller->update($this->request);
    }

    public function testUpdateValidationFailed(): void
    {
        $this->expectException(QueryException::class);

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'مواقف 1']);

        $this->request->method('get')
            ->with('id')
            ->willReturn(1);

        $this->request->method('request')
            ->willReturn(['name' => '']);

        $this->controller->update($this->request);
    }

    public function testDelete(): void
    {
        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'مواقف 1']);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->isInstanceOf(Mواقف::class));

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->request->method('get')
            ->with('id')
            ->willReturn(1);

        $response = $this->controller->delete($this->request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(null);

        $this->request->method('get')
            ->with('id')
            ->willReturn(1);

        $this->controller->delete($this->request);
    }

    public function testDeleteOptimisticLock(): void
    {
        $this->expectException(OptimisticLockException::class);

        $this->repository->expects($this->once())
            ->method('findOneById')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'مواقف 1']);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->isInstanceOf(Mواقف::class));

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willThrowException(new OptimisticLockException());

        $this->request->method('get')
            ->with('id')
            ->willReturn(1);

        $this->controller->delete($this->request);
    }
}