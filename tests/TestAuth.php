<?php

namespace App\Tests\Unit\Auth;

use App\Auth\AuthService;
use App\Auth\AuthRepository;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TestAuth extends TestCase
{
    private $authService;
    private $authRepository;
    private $entityManager;
    private $session;

    protected function setUp(): void
    {
        $this->authRepository = $this->createMock(AuthRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->session = $this->createMock(SessionInterface::class);

        $this->authService = new AuthService($this->authRepository, $this->entityManager, $this->session);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('login')
            ->with($username, $password)
            ->willReturn(new User($username));

        $token = $this->authService->login($username, $password);
        $this->assertInstanceOf(TokenInterface::class, $token);
    }

    public function testRegisterSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('register')
            ->with($username, $password)
            ->willReturn(new User($username));

        $user = $this->authService->register($username, $password);
        $this->assertInstanceOf(UserInterface::class, $user);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('login')
            ->with($username, $password)
            ->willReturn(null);

        $token = $this->authService->login($username, $password);
        $this->assertNull($token);
    }

    public function testRegisterFailure()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->authRepository->expects($this->once())
            ->method('register')
            ->with($username, $password)
            ->willReturn(null);

        $user = $this->authService->register($username, $password);
        $this->assertNull($user);
    }
}


This test file covers the following scenarios:

- `testLoginSuccess`: Tests that the `login` method returns a valid token when the user credentials are correct.
- `testRegisterSuccess`: Tests that the `register` method returns a valid user object when the user credentials are correct.
- `testLoginFailure`: Tests that the `login` method returns `null` when the user credentials are incorrect.
- `testRegisterFailure`: Tests that the `register` method returns `null` when the user credentials are incorrect.

Note that this is a basic example and you may need to adjust it to fit your specific use case.