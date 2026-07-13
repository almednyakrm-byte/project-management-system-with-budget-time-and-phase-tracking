<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Response;

class Testمشاريع extends TestCase
{
    private $mockPDO;
    private $request;
    private $response;
    private $stream;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(\PDO::class);
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->stream = $this->createMock(StreamInterface::class);
    }

    public function testGetمشاريع()
    {
        $this->request->method('getMethod')
            ->willReturn('GET');

        $this->mockPDO->method('query')
            ->willReturn($this->mockPDO);

        $this->mockPDO->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'مشروع 1'],
                ['id' => 2, 'name' => 'مشروع 2'],
            ]);

        $مشاريعController = new مشاريعController($this->mockPDO);
        $response = $مشاريعController->getمشاريع($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostمشاريع()
    {
        $this->request->method('getMethod')
            ->willReturn('POST');

        $this->request->method('getParsedBody')
            ->willReturn(['name' => 'مشروع جديد']);

        $this->mockPDO->method('prepare')
            ->willReturn($this->mockPDO);

        $this->mockPDO->method('execute')
            ->willReturn(true);

        $this->mockPDO->method('lastInsertId')
            ->willReturn(1);

        $مشاريعController = new مشاريعController($this->mockPDO);
        $response = $مشاريعController->postمشاريع($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testPutمشاريع()
    {
        $this->request->method('getMethod')
            ->willReturn('PUT');

        $this->request->method('getParsedBody')
            ->willReturn(['name' => 'مشروع محدث']);

        $this->request->method('getAttribute')
            ->willReturn(1);

        $this->mockPDO->method('prepare')
            ->willReturn($this->mockPDO);

        $this->mockPDO->method('execute')
            ->willReturn(true);

        $مشاريعController = new مشاريعController($this->mockPDO);
        $response = $مشاريعController->putمشاريع($this->request, 1);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteمشاريع()
    {
        $this->request->method('getMethod')
            ->willReturn('DELETE');

        $this->request->method('getAttribute')
            ->willReturn(1);

        $this->mockPDO->method('prepare')
            ->willReturn($this->mockPDO);

        $this->mockPDO->method('execute')
            ->willReturn(true);

        $مشاريعController = new مشاريعController($this->mockPDO);
        $response = $مشاريعController->deleteمشاريع($this->request, 1);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}