<?php

namespace App\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiAvailabilityTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testAllAvailableRoutes($method, $url)
    {
        $client = self::createClient();
        $client->request($method, $url);
        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['GET', '/teams'];
        yield ['POST', '/teams'];
        yield ['PUT', '/teams/123'];
    }
}