<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\PaymentController;
use App\Repository\PaymentRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class Testدفع extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->repository = $this->createMock(PaymentRepository::class);
        $this->controller = new PaymentController($this->repository);
    }

    public function testGetAllPayments()
    {
        $payments = [
            ['id' => 1, 'amount' => 100],
            ['id' => 2, 'amount' => 200],
        ];

        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM payments')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($payments);

        $response = $this->controller->getAllPayments();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($payments), $response->getBody()->getContents());
    }

    public function testCreatePayment()
    {
        $payment = ['id' => 1, 'amount' => 100];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO payments (amount) VALUES (:amount)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('create')
            ->with($payment)
            ->willReturn($payment);

        $response = $this->controller->createPayment($payment);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(json_encode($payment), $response->getBody()->getContents());
    }

    public function testUpdatePayment()
    {
        $payment = ['id' => 1, 'amount' => 100];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE payments SET amount = :amount WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('update')
            ->with($payment)
            ->willReturn($payment);

        $response = $this->controller->updatePayment($payment['id'], $payment);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_encode($payment), $response->getBody()->getContents());
    }

    public function testDeletePayment()
    {
        $payment = ['id' => 1, 'amount' => 100];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM payments WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($payment['id'])
            ->willReturn(true);

        $response = $this->controller->deletePayment($payment['id']);
        $this->assertEquals(204, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'دفع' module. It uses mocked PDO statements to simulate database interactions. The tests verify that the controller returns the correct HTTP status codes and response bodies for each operation.