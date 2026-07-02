<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\حجزController;
use App\Repository\حجزRepository;
use App\Entity\حجز;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\QueryException;

class Testحجز extends TestCase
{
    private $controller;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(حجزRepository::class);
        $this->controller = new حجزController($this->repository);
    }

    public function testGetAll()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new حجز()]);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOne()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new حجز());

        $response = $this->controller->getOne($id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreate()
    {
        $data = ['name' => 'Test'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new حجز($data));

        $response = $this->controller->create($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate()
    {
        $id = 1;
        $data = ['name' => 'Test'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new حجز());

        $this->repository->expects($this->once())
            ->method('save')
            ->with(new حجز($data));

        $response = $this->controller->update($id, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDelete()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new حجز());

        $this->repository->expects($this->once())
            ->method('remove')
            ->with(new حجز());

        $response = $this->controller->delete($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// حجزController.php
namespace App\Controller;

use App\Repository\حجزRepository;
use App\Entity\حجز;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class حجزController
{
    private $repository;

    public function __construct(حجزRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return new Response(json_encode($this->repository->findAll()), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function getOne($id)
    {
        $entity = $this->repository->find($id);
        return new Response(json_encode($entity), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function create($data)
    {
        $entity = new حجز($data);
        $this->repository->save($entity);
        return new Response('', Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function update($id, $data)
    {
        $entity = $this->repository->find($id);
        $entity->setName($data['name']);
        $this->repository->save($entity);
        return new Response('', Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function delete($id)
    {
        $entity = $this->repository->find($id);
        $this->repository->remove($entity);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// حجزRepository.php
namespace App\Repository;

use App\Entity\حجز;
use Doctrine\ORM\EntityManagerInterface;

class حجزRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        return $this->entityManager->getRepository(حجز::class)->findAll();
    }

    public function find($id)
    {
        return $this->entityManager->getRepository(حجز::class)->find($id);
    }

    public function save(حجز $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(حجز $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}



// حجز.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class حجز
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}