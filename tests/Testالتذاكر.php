<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Controller\التذاكرController;
use App\Repository\التذاكرRepository;
use App\Entity\التذاكر;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Tools\Pagination\Paginator;

class Testالتذاكر extends WebTestCase
{
    private $client;
    private $router;
    private $tokenStorage;
    private $entityManager;
    private $repository;
    private $controller;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->router = static::getContainer()->get(RouterInterface::class);
        $this->tokenStorage = static::getContainer()->get(TokenStorageInterface::class);
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->repository = static::getContainer()->get(التذاكرRepository::class);
        $this->controller = new التذاكرController($this->repository);
    }

    public function testGetAll(): void
    {
        $mockPDO = $this->createMock(\PDO::class);
        $mockPDO->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM التذاكر')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($mockPDO);

        $response = $this->client->request('GET', $this->router->generate('التذاكر_index'));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetOne(): void
    {
        $mockPDO = $this->createMock(\PDO::class);
        $mockPDO->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM التذاكر WHERE id = 1')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($mockPDO);

        $response = $this->client->request('GET', $this->router->generate('التذاكر_show', ['id' => 1]));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPost(): void
    {
        $mockPDO = $this->createMock(\PDO::class);
        $mockPDO->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO التذاكر (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($mockPDO);

        $request = Request::create($this->router->generate('التذاكر_new'), 'POST', [], [], [], ['name' => 'test', 'description' => 'test']);
        $response = $this->client->request($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPut(): void
    {
        $mockPDO = $this->createMock(\PDO::class);
        $mockPDO->expects($this->once())
            ->method('prepare')
            ->with('UPDATE التذاكر SET name = :name, description = :description WHERE id = 1')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($mockPDO);

        $request = Request::create($this->router->generate('التذاكر_edit', ['id' => 1]), 'PUT', [], [], [], ['name' => 'test', 'description' => 'test']);
        $response = $this->client->request($request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $mockPDO = $this->createMock(\PDO::class);
        $mockPDO->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM التذاكر WHERE id = 1')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->entityManager->expects($this->once())
            ->method('getConnection')
            ->willReturn($mockPDO);

        $request = Request::create($this->router->generate('التذاكر_delete', ['id' => 1]), 'DELETE');
        $response = $this->client->request($request);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'التذاكر' module. It uses mocked PDO statements to simulate database interactions. The tests cover the following scenarios:

*   `testGetAll`: Verifies that the `getAll` method returns a list of all 'التذاكر' entities.
*   `testGetOne`: Verifies that the `getOne` method returns a single 'التذاكر' entity by its ID.
*   `testPost`: Verifies that the `post` method creates a new 'التذاكر' entity.
*   `testPut`: Verifies that the `put` method updates an existing 'التذاكر' entity.
*   `testDelete`: Verifies that the `delete` method deletes a 'التذاكر' entity by its ID.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you should replace the mocked PDO statements with actual database interactions in a real-world scenario.