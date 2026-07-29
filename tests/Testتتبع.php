<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class Testتتبع extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetتتبع()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM تتبع')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $تتبع = new تتبع($this->pdo);
        $result = $تتبع->get($request, $response);

        $this->assertIsArray($result);
    }

    public function testPostتتبع()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'test']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO تتبع (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'test');

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $تتبع = new تتبع($this->pdo);
        $result = $تتبع->post($request, $response);

        $this->assertIsArray($result);
    }

    public function testPutتتبع()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['id' => 1, 'name' => 'test']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE تتبع SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'test');

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $تتبع = new تتبع($this->pdo);
        $result = $تتبع->put($request, $response);

        $this->assertIsArray($result);
    }

    public function testDeleteتتبع()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM تتبع WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->statement->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $تتبع = new تتبع($this->pdo);
        $result = $تتبع->delete($request, $response);

        $this->assertIsArray($result);
    }
}