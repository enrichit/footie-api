<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Team;

class TeamsContollerTest extends WebTestCase
{
    use FixtureWebTestCase;

    public function testListTeams()
    {
        $client = self::createClient();
        $client->request('GET', '/teams');
        $decoded = json_decode($client->getResponse()->getContent());
        $this->assertCount(1, $decoded);
    }

    public function testListTeamsReturnsFirstFixtureNameAndStrip()
    {
        $client = self::createClient();
        $client->request('GET', '/teams');
        $decoded = json_decode($client->getResponse()->getContent());
        $this->assertEquals($decoded[0]->Name, 'Chelsea');
        $this->assertEquals($decoded[0]->Strip, 'Blue');
    }

    public function testCreateNewTeam()
    {
        $client = self::createClient();
        $client->request('POST', '/teams', ['name' => 'hello', 'strip' => 'test']);
        $decoded = json_decode($client->getResponse()->getContent());
        $this->assertEquals($decoded->Name, 'hello');
        $this->assertEquals($decoded->Strip, 'test');
    }

    public function testUpdateTeam()
    {
        $client = self::createClient();
        $client->request('PUT', '/teams/1', ['name' => 'Tottenham Hotspur', 'strip' => 'White']);
        $decoded = json_decode($client->getResponse()->getContent());
        $this->assertEquals(204, $client->getResponse()->getStatusCode());
        
        $this->assertEquals(
            'Tottenham Hotspur',
            self::$entityManager->getRepository(Team::class)->find(1)->getName()
        );
        
        $this->assertEquals(
            'White',
            self::$entityManager->getRepository(Team::class)->find(1)->getStrip()
        );
    }
}
