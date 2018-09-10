<?php

use PHPUnit\Framework\TestCase;
use App\Controller\LeagueController;
use App\Entity\League;
use App\Repository\LeagueRepository;
use Doctrine\ORM\EntityManagerInterface;

class LeagueControllerTest extends TestCase
{
    public function testDeleteEntityNotFound()
    {
        $repository = $this->createMock(LeagueRepository::class);
        $repository
            ->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $controller = new LeagueController();
        $response = $controller->delete(1, $repository, $entityManager);
        
        $this->assertEquals(
            (object)['message' => 'League not found.', 'data' => 1],
            json_decode($response->getContent())
        );

        $this->assertEquals($response->getStatusCode(), 404);
    }

    public function testDeleteEntitySuccess()
    {
        $repository = $this->createMock(LeagueRepository::class);
        $repository
            ->expects($this->once())
            ->method('find')
            ->willReturn(new League());

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('remove');
        $entityManager->expects($this->once())->method('flush');

        $controller = new LeagueController();
        $response = $controller->delete(1, $repository, $entityManager);

        $this->assertEquals('', $response->getContent());
        $this->assertEquals($response->getStatusCode(), 204);
    }
}