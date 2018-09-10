<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Controller\TeamsController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TeamRepository;
use App\Entity\Team;

class TeamControllerTest extends TestCase
{
    /**
     * @TeamsController
     */
    private $controller;

    public function setUp()
    {
        $mockSerializer = $this->createMock(SerializerInterface::class);
        $mockSerializer
            ->expects($this->any())
            ->method('serialize')
            ->willReturnCallback(function($spec) {
                $normalized = is_array($spec) ? $spec[0] : $spec;
                return json_encode([
                    $normalized->getName(),
                    $normalized->getStrip(),
                ]);
            });

        $this->controller = new TeamsController($mockSerializer);
    }

    public function testTeamList()
    {
        $testTeam = new Team();
        $testTeam->setName('name')->setStrip('strip');

        $repository = $this->createMock(TeamRepository::class);
        $repository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$testTeam]);

        $response = $this->controller->list($repository);
        $this->assertEquals(
            [$testTeam->getName(), $testTeam->getStrip()],
            json_decode($response->getContent())
        );
    }

    public function testTeamCreate()
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects($this->atLeast(2))
            ->method('get')
            ->will($this->onConsecutiveCalls('test1', 'test2'));

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $response = $this->controller->create($request, $entityManager);

        $this->assertEquals(
            ['test1', 'test2'],
            json_decode($response->getContent())
        );
    }

    public function testTeamUpdateNotFound()
    {
        $request = $this->createMock(Request::class);

        $repository = $this->createMock(TeamRepository::class);
        $repository
            ->expects($this->once())
            ->method('find')
            ->willReturn(null);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $response = $this->controller->update(1, $request, $repository, $entityManager);
        
        $this->assertEquals(
            (object)['message' => 'Team not found.', 'data' => 1],
            json_decode($response->getContent())
        );

        $this->assertEquals($response->getStatusCode(), 404);
    }

    public function testTeamUpdateSuccess()
    {
        $request = $this->createMock(Request::class);
        $request
            ->expects($this->atLeast(2))
            ->method('get')
            ->willReturn('');

        $repository = $this->createMock(TeamRepository::class);
        $repository
            ->expects($this->once())
            ->method('find')
            ->willReturn(new Team());

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('flush');

        $response = $this->controller->update(1, $request, $repository, $entityManager);
        
        $this->assertEquals('', $response->getContent());
        $this->assertEquals($response->getStatusCode(), 204);
    }
}