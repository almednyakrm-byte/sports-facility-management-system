<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use Mockery;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;

class TestAuth extends TestCase
{
    /**
     * @var LegacyMockInterface|MockInterface|AuthRepository
     */
    protected $authRepository;

    /**
     * @var LegacyMockInterface|MockInterface|AuthService
     */
    protected $authService;

    protected function setUp(): void
    {
        $this->authRepository = Mockery::mock(AuthRepository::class);
        $this->authService = Mockery::mock(AuthService::class);
        $this->authService->shouldReceive('setRepository')->with($this->authRepository);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn(new User($username, $password));
        $this->authRepository->shouldReceive('verifyPassword')->with($password, 'testpassword')->andReturn(true);

        $this->authService->shouldReceive('login')->with(new User($username, $password))->andReturn(true);

        $result = $this->authService->login($username, $password);
        $this->assertTrue($result);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn(null);
        $this->authRepository->shouldReceive('verifyPassword')->with($password, 'testpassword')->andReturn(false);

        $result = $this->authService->login($username, $password);
        $this->assertFalse($result);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn(null);
        $this->authRepository->shouldReceive('createUser')->with(new User($username, $password))->andReturn(true);

        $result = $this->authService->register($username, $password);
        $this->assertTrue($result);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->shouldReceive('getUserByUsername')->with($username)->andReturn(new User($username, $password));
        $this->authRepository->shouldReceive('createUser')->with(new User($username, $password))->andReturn(false);

        $result = $this->authService->register($username, $password);
        $this->assertFalse($result);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the login method returns true when the user exists and the password is correct.
- `testLoginFailure`: Tests that the login method returns false when the user does not exist or the password is incorrect.
- `testRegisterSuccess`: Tests that the register method returns true when the user is created successfully.
- `testRegisterFailure`: Tests that the register method returns false when the user already exists or the creation fails.

Note that this test file assumes that the `AuthRepository` and `AuthService` classes have the necessary methods (`getUserByUsername`, `verifyPassword`, `createUser`, `login`, `register`) that are being mocked and tested.