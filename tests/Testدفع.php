<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\PaymentController;
use App\Repository\PaymentRepository;
use App\Entity\Payment;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testدفع extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    public function setUp(): void
    {
        $this->repository = $this->createMock(PaymentRepository::class);
        $this->controller = new PaymentController($this->repository);
        $this->request = $this->createMock(Request::class);
    }

    public function testGetPayments()
    {
        $payments = [
            new Payment(),
            new Payment(),
            new Payment(),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($payments);

        $response = $this->controller->getPayments($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetPayment()
    {
        $payment = new Payment();

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);

        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);

        $response = $this->controller->getPayment($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreatePayment()
    {
        $payment = new Payment();

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($payment));

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn([
                'name' => 'payment name',
                'amount' => 100,
            ]);

        $response = $this->controller->createPayment($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdatePayment()
    {
        $payment = new Payment();

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);

        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn([
                'name' => 'payment name',
                'amount' => 100,
            ]);

        $response = $this->controller->updatePayment($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeletePayment()
    {
        $payment = new Payment();

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($payment);

        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn(1);

        $response = $this->controller->deletePayment($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetPayments`: Verifies that the `getPayments` method returns a JSON response with a list of payments.
- `testGetPayment`: Verifies that the `getPayment` method returns a JSON response with a single payment.
- `testCreatePayment`: Verifies that the `createPayment` method creates a new payment and returns a JSON response with a 201 status code.
- `testUpdatePayment`: Verifies that the `updatePayment` method updates an existing payment and returns a JSON response with a 200 status code.
- `testDeletePayment`: Verifies that the `deletePayment` method deletes a payment and returns a JSON response with a 204 status code.

Note that this is a basic example and you may need to modify it to fit your specific use case. Additionally, you should replace the mocked objects with real instances in a production environment.