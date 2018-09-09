<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\League;

class LeagueContollerTest extends WebTestCase
{
    use FixtureWebTestCase;

    public function testDeleteRemovesLeagueFromDatabase()
    {
        $client = self::createClient();
        $client->request('DELETE', '/leagues/1');
        print_r(self::$entityManager->getRepository(League::class)->findAll());
        $this->assertEmpty(self::$entityManager->getRepository(League::class)->findAll());
    }
}