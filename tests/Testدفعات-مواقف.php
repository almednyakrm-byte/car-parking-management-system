<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\DefaatMoqafController;
use App\Repository\DefaatMoqafRepository;
use App\Entity\DefaatMoqaf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;

class TestDefaatMoqaf extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(DefaatMoqafRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new DefaatMoqafController($this->repository, $this->entityManager);
    }

    public function testGetDefaatMoqaf()
    {
        $id = 1;
        $defaatMoqaf = new DefaatMoqaf();
        $defaatMoqaf->setId($id);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($defaatMoqaf);

        $request = new Request();
        $response = $this->controller->getDefaatMoqaf($request, $id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($defaatMoqaf, $response->getContent());
    }

    public function testGetDefaatMoqafNotFound()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $request = new Request();
        $this->expectException(NotFoundHttpException::class);
        $this->controller->getDefaatMoqaf($request, $id);
    }

    public function testCreateDefaatMoqaf()
    {
        $data = [
            'name' => 'Defaat Moqaf',
            'description' => 'This is a defaat moqaf',
        ];
        $defaatMoqaf = new DefaatMoqaf();
        $defaatMoqaf->setName($data['name']);
        $defaatMoqaf->setDescription($data['description']);
        $this->repository->expects($this->once())
            ->method('create')
            ->with($defaatMoqaf)
            ->willReturn($defaatMoqaf);

        $request = new Request();
        $request->request->replace($data);
        $response = $this->controller->createDefaatMoqaf($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($defaatMoqaf, $response->getContent());
    }

    public function testUpdateDefaatMoqaf()
    {
        $id = 1;
        $data = [
            'name' => 'Defaat Moqaf Updated',
            'description' => 'This is a defaat moqaf updated',
        ];
        $defaatMoqaf = new DefaatMoqaf();
        $defaatMoqaf->setId($id);
        $defaatMoqaf->setName($data['name']);
        $defaatMoqaf->setDescription($data['description']);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($defaatMoqaf);
        $this->repository->expects($this->once())
            ->method('update')
            ->with($defaatMoqaf)
            ->willReturn($defaatMoqaf);

        $request = new Request();
        $request->request->replace($data);
        $response = $this->controller->updateDefaatMoqaf($request, $id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($defaatMoqaf, $response->getContent());
    }

    public function testDeleteDefaatMoqaf()
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new DefaatMoqaf());

        $request = new Request();
        $response = $this->controller->deleteDefaatMoqaf($request, $id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetDefaatMoqaf`: Tests the GET request for a defaat moqaf by ID.
- `testGetDefaatMoqafNotFound`: Tests the GET request for a defaat moqaf by ID when the defaat moqaf is not found.
- `testCreateDefaatMoqaf`: Tests the POST request to create a new defaat moqaf.
- `testUpdateDefaatMoqaf`: Tests the PUT request to update an existing defaat moqaf.
- `testDeleteDefaatMoqaf`: Tests the DELETE request to delete a defaat moqaf by ID.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you will need to implement the `DefaatMoqafController` and `DefaatMoqafRepository` classes to make this test file work.