<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\TeamController;
use App\Repository\TeamRepository;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testفريق-العمل extends TestCase
{
    private $teamController;
    private $teamRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->teamRepository = $this->createMock(TeamRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->teamController = new TeamController($this->teamRepository, $this->entityManager);
    }

    public function testGetTeams()
    {
        $teams = [
            new Team('Team 1'),
            new Team('Team 2'),
            new Team('Team 3'),
        ];

        $this->teamRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($teams);

        $response = $this->teamController->getTeams();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(JsonResponse::CONTENT_TYPE, $response->headers->get('Content-Type'));
        $this->assertEquals(json_encode($teams), $response->getContent());
    }

    public function testGetTeam()
    {
        $team = new Team('Team 1');

        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);

        $response = $this->teamController->getTeam(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(JsonResponse::CONTENT_TYPE, $response->headers->get('Content-Type'));
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testGetTeamNotFound()
    {
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->teamController->getTeam(1);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testCreateTeam()
    {
        $team = new Team('Team 1');
        $team->setId(1);

        $this->teamRepository->expects($this->once())
            ->method('save')
            ->with($team)
            ->willReturn($team);

        $response = $this->teamController->createTeam(new Request(['json' => ['name' => 'Team 1']]));

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(JsonResponse::CONTENT_TYPE, $response->headers->get('Content-Type'));
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testUpdateTeam()
    {
        $team = new Team('Team 1');
        $team->setId(1);

        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);

        $this->teamRepository->expects($this->once())
            ->method('save')
            ->with($team)
            ->willReturn($team);

        $response = $this->teamController->updateTeam(1, new Request(['json' => ['name' => 'Team 2']]));

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(JsonResponse::CONTENT_TYPE, $response->headers->get('Content-Type'));
        $this->assertEquals(json_encode($team), $response->getContent());
    }

    public function testUpdateTeamNotFound()
    {
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->teamController->updateTeam(1, new Request(['json' => ['name' => 'Team 2']]));

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testDeleteTeam()
    {
        $team = new Team('Team 1');
        $team->setId(1);

        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($team);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($team);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $response = $this->teamController->deleteTeam(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteTeamNotFound()
    {
        $this->teamRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $response = $this->teamController->deleteTeam(1);

        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}