<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Team;

class ApiAvailabilityTest extends WebTestCase
{
    use FixtureWebTestCase;
    
    /**
     * @dataProvider urlProvider
     */
    public function testAllAvailableRoutes($method, $url, $params = [])
    {
        $client = self::createClient();
        $client->request($method, $url, $params);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['GET', '/teams'];
        yield ['POST', '/teams', ['name' => 'hello', 'strip' => 'world']];
        yield ['PUT', '/teams/1', ['name' => 'hello', 'strip' => 'world']];
    }
}
