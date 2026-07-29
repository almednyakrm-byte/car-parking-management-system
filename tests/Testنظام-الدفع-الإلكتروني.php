<?php

namespace App\Tests\Controller;

use App\Controller\نظام الدفع الإلكترونيController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Slim\Psr7\Factory\UriFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\EnvironmentFactory;
use Slim\Psr7\Environment;
use Slim\Psr7\Server;
use Slim\Views\Twig;

class Testنظام-الدفع-الإلكتروني extends TestCase
{
    private $controller;
    private $app;
    private $request;
    private $response;
    private $pdo;

    protected function setUp(): void
    {
        $this->app = AppFactory::create();
        $this->app->addRoutingMiddleware();
        $this->app->addBodyParsingMiddleware();
        $this->app->addRoutingMiddleware();

        $this->pdo = $this->createMock('PDO');
        $this->pdo->method('prepare')->willReturn($this->createMock('PDOStatement'));

        $this->controller = new نظام الدفع الإلكترونيController($this->pdo);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    public function testGetAll()
    {
        $this->request->method('getMethod')->willReturn('GET');
        $this->request->method('getUri')->willReturn($this->createMock('Psr\Http\Message\UriInterface'));

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM نظام الدفع الإلكتروني')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt = $this->createMock('PDOStatement');
        $stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Electronic Payment System'],
                ['id' => 2, 'name' => 'Online Payment Gateway'],
            ]);

        $this->pdo->method('prepare')->willReturn($stmt);

        $this->controller->getAll($this->request, $this->response);

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals(['Electronic Payment System', 'Online Payment Gateway'], $this->response->getBody()->getContents());
    }

    public function testCreate()
    {
        $this->request->method('getMethod')->willReturn('POST');
        $this->request->method('getParsedBody')->willReturn(['name' => 'New Electronic Payment System']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO نظام الدفع الإلكتروني (name) VALUES (:name)')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => 'New Electronic Payment System'])
            ->willReturn(true);

        $this->controller->create($this->request, $this->response);

        $this->assertEquals(201, $this->response->getStatusCode());
        $this->assertEquals('New Electronic Payment System', $this->response->getBody()->getContents());
    }

    public function testUpdate()
    {
        $this->request->method('getMethod')->willReturn('PUT');
        $this->request->method('getAttribute')->with('routeParams')->willReturn(['id' => 1]);
        $this->request->method('getParsedBody')->willReturn(['name' => 'Updated Electronic Payment System']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE نظام الدفع الإلكتروني SET name = :name WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['name' => 'Updated Electronic Payment System', 'id' => 1])
            ->willReturn(true);

        $this->controller->update($this->request, $this->response);

        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('Updated Electronic Payment System', $this->response->getBody()->getContents());
    }

    public function testDelete()
    {
        $this->request->method('getMethod')->willReturn('DELETE');
        $this->request->method('getAttribute')->with('routeParams')->willReturn(['id' => 1]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM نظام الدفع الإلكتروني WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdo->expects($this->once())
            ->method('execute')
            ->with(['id' => 1])
            ->willReturn(true);

        $this->controller->delete($this->request, $this->response);

        $this->assertEquals(204, $this->response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'نظام الدفع الإلكتروني' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the controller returns the correct HTTP status codes and response bodies for each operation.