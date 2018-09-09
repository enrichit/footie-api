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
        print ($client->getResponse());
        $decoded = json_decode($client->getResponse());
        $this->assertCount(1, count($decoded));
    }
}
