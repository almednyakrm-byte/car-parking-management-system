<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->authService = new AuthService($this->authRepository);

        $this->connection->method('connect')->willReturn($this->connection);
        $this->connection->method('fetchAll')->willReturn([
            ['id' => 1, 'username' => 'testuser', 'password' => 'testpassword'],
        ]);
    }

    public function testLoginSuccess()
    {
        $this->authRepository->method('getUserByUsername')->willReturn(new User(1, 'testuser', 'testpassword'));

        $result = $this->authService->login('testuser', 'testpassword');

        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $this->authRepository->method('getUserByUsername')->willReturn(null);

        $result = $this->authService->login('testuser', 'testpassword');

        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $this->connection->method('insert')->willReturn(true);

        $result = $this->authService->register('testuser', 'testpassword');

        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $this->connection->method('insert')->willReturn(false);

        $result = $this->authService->register('testuser', 'testpassword');

        $this->assertFalse($result);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method returns `true` when the provided credentials match a user in the database.
- `testLoginFailure`: Tests that the `login` method returns `false` when the provided credentials do not match any user in the database.
- `testRegisterSuccess`: Tests that the `register` method returns `true` when a new user is successfully created in the database.
- `testRegisterFailure`: Tests that the `register` method returns `false` when a new user cannot be created in the database.

Note that this test file assumes that the `AuthService` class uses the `AuthRepository` class to interact with the database, and that the `AuthRepository` class uses a database connection to perform CRUD operations.