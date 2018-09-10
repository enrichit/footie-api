<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\League;

class LeagueContollerTest extends WebTestCase
{
    use FixtureWebTestCase;

    public function testDeleteRemovesLeagueFromDatabase()
    {
        $client = self::createClient();
        $client->request('DELETE', '/leagues/1');
        $this->assertEmpty(self::$entityManager->getRepository(League::class)->findAll());
    }

    public function testNotifiesifObjectDoesntExist()
    {
        $client = self::createClient();
        $client->request('DELETE', '/leagues/2');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}