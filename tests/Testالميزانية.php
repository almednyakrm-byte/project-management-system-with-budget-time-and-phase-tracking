<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\BudgetController;
use App\Repository\BudgetRepository;
use App\Entity\Budget;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class Testالميزانية extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(BudgetRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new BudgetController($this->repository, $this->entityManager);
    }

    public function testGetBudgets(): void
    {
        $budgets = [
            new Budget('budget1', 100),
            new Budget('budget2', 200),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($budgets);

        $response = $this->controller->getBudgets();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($budgets), $response->getContent());
    }

    public function testGetBudget(): void
    {
        $budget = new Budget('budget1', 100);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('budget1')
            ->willReturn($budget);

        $response = $this->controller->getBudget('budget1');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($budget), $response->getContent());
    }

    public function testCreateBudget(): void
    {
        $budget = new Budget('budget1', 100);
        $data = ['name' => 'budget1', 'amount' => 100];

        $this->repository->expects($this->once())
            ->method('save')
            ->with($budget);

        $response = $this->controller->createBudget($data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($budget), $response->getContent());
    }

    public function testUpdateBudget(): void
    {
        $budget = new Budget('budget1', 100);
        $data = ['name' => 'budget1', 'amount' => 200];

        $this->repository->expects($this->once())
            ->method('find')
            ->with('budget1')
            ->willReturn($budget);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($budget);

        $response = $this->controller->updateBudget('budget1', $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($budget), $response->getContent());
    }

    public function testDeleteBudget(): void
    {
        $budget = new Budget('budget1', 100);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('budget1')
            ->willReturn($budget);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($budget);

        $response = $this->controller->deleteBudget('budget1');
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// BudgetController.php

namespace App\Controller;

use App\Repository\BudgetRepository;
use App\Entity\Budget;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BudgetController
{
    private $repository;
    private $entityManager;

    public function __construct(BudgetRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function getBudgets(): Response
    {
        $budgets = $this->repository->findAll();
        return new JsonResponse($budgets);
    }

    public function getBudget(string $id): Response
    {
        $budget = $this->repository->find($id);
        return new JsonResponse($budget);
    }

    public function createBudget(array $data): Response
    {
        $budget = new Budget($data['name'], $data['amount']);
        $this->repository->save($budget);
        return new JsonResponse($budget, Response::HTTP_CREATED);
    }

    public function updateBudget(string $id, array $data): Response
    {
        $budget = $this->repository->find($id);
        $budget->setName($data['name']);
        $budget->setAmount($data['amount']);
        $this->repository->save($budget);
        return new JsonResponse($budget);
    }

    public function deleteBudget(string $id): Response
    {
        $budget = $this->repository->find($id);
        $this->repository->remove($budget);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}