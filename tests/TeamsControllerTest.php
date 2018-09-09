<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

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
}
