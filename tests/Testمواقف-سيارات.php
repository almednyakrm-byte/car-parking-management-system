<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مواقف سياراتController;
use App\Repository\مواقف سياراتRepository;
use App\Entity\مواقف سيارات;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\QueryException;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testمواقف-سيارات extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    public function setUp(): void
    {
        $this->repository = Mockery::mock(Mواقف سياراتRepository::class);
        $this->entityManager = Mockery::mock(EntityManagerInterface::class);
        $this->entityManager->shouldReceive('getRepository')->andReturn($this->repository);

        $this->controller = new مواقف سياراتController($this->entityManager);
    }

    public function testGetAll(): void
    {
        $this->repository->shouldReceive('findAll')->andReturn([
            new مواقف سيارات(),
            new مواقف سيارات(),
        ]);

        $response = $this->controller->getAll();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetById(): void
    {
        $id = 1;
        $this->repository->shouldReceive('find')->with($id)->andReturn(new مواقف سيارات());

        $response = $this->controller->getById($id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPost(): void
    {
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $this->repository->shouldReceive('save')->with(Mockery::type(مواقف سيارات::class), Mockery::type('array'))->andReturn(new مواقف سيارات());

        $response = $this->controller->post($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPut(): void
    {
        $id = 1;
        $data = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $this->repository->shouldReceive('find')->with($id)->andReturn(new مواقف سيارات());
        $this->repository->shouldReceive('save')->with(Mockery::type(مواقف سيارات::class), Mockery::type('array'));

        $response = $this->controller->put($id, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDelete(): void
    {
        $id = 1;

        $this->repository->shouldReceive('find')->with($id)->andReturn(new مواقف سيارات());
        $this->repository->shouldReceive('remove')->with(Mockery::type(مواقف سيارات::class));

        $response = $this->controller->delete($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\مواقف سياراتController.php

namespace App\Controller;

use App\Repository\مواقف سياراتRepository;
use App\Entity\مواقف سيارات;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class مواقف سياراتController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAll(): Response
    {
        $repository = $this->entityManager->getRepository(مواقف سيارات::class);
        $entities = $repository->findAll();

        return new JsonResponse($entities);
    }

    public function getById(int $id): Response
    {
        $repository = $this->entityManager->getRepository(مواقف سيارات::class);
        $entity = $repository->find($id);

        return new JsonResponse($entity);
    }

    public function post(array $data): Response
    {
        $entity = new مواقف سيارات();
        $entity->setField1($data['field1']);
        $entity->setField2($data['field2']);

        $repository = $this->entityManager->getRepository(مواقف سيارات::class);
        $repository->save($entity);

        return new JsonResponse($entity, Response::HTTP_CREATED);
    }

    public function put(int $id, array $data): Response
    {
        $repository = $this->entityManager->getRepository(مواقف سيارات::class);
        $entity = $repository->find($id);

        $entity->setField1($data['field1']);
        $entity->setField2($data['field2']);

        $repository->save($entity);

        return new JsonResponse($entity);
    }

    public function delete(int $id): Response
    {
        $repository = $this->entityManager->getRepository(مواقف سيارات::class);
        $entity = $repository->find($id);
        $repository->remove($entity);

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}