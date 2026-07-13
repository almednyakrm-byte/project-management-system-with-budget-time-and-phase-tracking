<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مواردController;
use App\Repository\مواردRepository;
use App\Entity\موارد;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class Testموارد extends TestCase
{
    private $controller;
    private $repository;
    private $router;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MواردRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->controller = new مواردController($this->repository, $this->router);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new موارد('item1'),
                new موارد('item2'),
            ]);

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetOne(): void
    {
        $item = new موارد('item1');
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($item);

        $request = new Request();
        $response = $this->controller->getOne($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreate(): void
    {
        $item = new موارد('item1');
        $this->repository->expects($this->once())
            ->method('save')
            ->with($item);

        $request = new Request([], [], ['item' => 'item1']);
        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdate(): void
    {
        $item = new موارد('item1');
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($item);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($item);

        $request = new Request([], [], ['item' => 'item1']);
        $response = $this->controller->update($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDelete(): void
    {
        $item = new موارد('item1');
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($item);
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($item);

        $request = new Request();
        $response = $this->controller->delete($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}