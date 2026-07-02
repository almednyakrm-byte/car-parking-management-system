<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testالدفعات extends TestCase
{
    private $pdo;
    private $statement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->statement = $this->createMock(PDOStatement::class);
    }

    public function testGetالدفعات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM الدفعات')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([]);

        $result = $this->getالدفعات($this->pdo);
        $this->assertIsArray($result);
    }

    public function testPostالدفعات()
    {
        $data = ['name' => 'Test الدفعة'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO الدفعات (name) VALUES (:name)')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $result = $this->postالدفعات($this->pdo, $data);
        $this->assertTrue($result);
    }

    public function testPutالدفعات()
    {
        $id = 1;
        $data = ['name' => 'Updated Test الدفعة'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE الدفعات SET name = :name WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $result = $this->putالدفعات($this->pdo, $id, $data);
        $this->assertTrue($result);
    }

    public function testDeleteالدفعات()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM الدفعات WHERE id = :id')
            ->willReturn($this->statement);

        $this->statement->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->statement->expects($this->once())
            ->method('execute')
            ->with([]);

        $result = $this->deleteالدفعات($this->pdo, $id);
        $this->assertTrue($result);
    }

    private function getالدفعات(PDO $pdo)
    {
        $stmt = $pdo->prepare('SELECT * FROM الدفعات');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postالدفعات(PDO $pdo, array $data)
    {
        $stmt = $pdo->prepare('INSERT INTO الدفعات (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    private function putالدفعات(PDO $pdo, int $id, array $data)
    {
        $stmt = $pdo->prepare('UPDATE الدفعات SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteالدفعات(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('DELETE FROM الدفعات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}