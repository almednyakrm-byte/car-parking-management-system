<?php

namespace App\Tests\Controller;

use App\Controller\مواقف سياراتController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testمواقف-سيارات extends TestCase
{
    private $controller;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->controller = new مواقف سياراتController();
        $this->pdoMock = $this->createMock(PDO::class);
    }

    public function testGetAll(): void
    {
        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM مواقف سيارات')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getAll($request, $this->pdoMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        $data = ['name' => 'مواقف سيارات جديدة'];
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO مواقف سيارات (name) VALUES (:name)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['data' => $data]);
        $response = $this->controller->create($request, $this->pdoMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdate(): void
    {
        $data = ['name' => 'مواقف سيارات مُحديثة'];
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE مواقف سيارات SET name = :name WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['data' => $data, 'id' => $id]);
        $response = $this->controller->update($request, $this->pdoMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM مواقف سيارات WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['id' => $id]);
        $response = $this->controller->delete($request, $this->pdoMock);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetAll`: Tests the `getAll` method of the `مواقف سياراتController` class by mocking the PDO statement to return a list of all `مواقف سيارات`.
- `testCreate`: Tests the `create` method of the `مواقف سياراتController` class by mocking the PDO statement to insert a new `مواقف سيارات` with the provided data.
- `testUpdate`: Tests the `update` method of the `مواقف سياراتController` class by mocking the PDO statement to update an existing `مواقف سيارات` with the provided data.
- `testDelete`: Tests the `delete` method of the `مواقف سياراتController` class by mocking the PDO statement to delete a `مواقف سيارات` with the provided ID.

Note that this is a basic example and you may need to adjust the test cases based on the actual implementation of the `مواقف سياراتController` class.